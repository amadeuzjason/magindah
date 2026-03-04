<?php

namespace App\Http\Controllers;

use App\Services\SQLiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $sqlite;

    public function __construct(SQLiteService $sqlite)
    {
        $this->sqlite = $sqlite;
    }

    public function index()
    {
        $df = $this->loadData();
        return view('dashboard', [
            'columns' => $df['columns'],
            'rows' => $df['rows'],
            'username' => Session::get('username')
        ]);
    }

    public function approvals()
    {
        $df = $this->loadData();
        return view('approvals', [
            'rows' => $df['rows'],
            'username' => Session::get('username')
        ]);
    }

    public function approve(Request $request)
    {
        $nop = $request->input('nop');
        $username = Session::get('username');

        // Check if NOP exists
        $rows = $this->sqlite->query('SELECT * FROM records_current WHERE "NOP" = :nop', [':nop' => $nop]);
        if (empty($rows)) {
            return response()->json(['error' => 'NOP tidak ditemukan.'], 404);
        }

        // Update approval
        $this->sqlite->execute('UPDATE records_current SET "STATUS" = :status, "APPROVED BY" = :approved_by WHERE "NOP" = :nop', [
            ':status' => 'APPROVED',
            ':approved_by' => $username,
            ':nop' => $nop
        ]);

        return response()->json(['message' => "Proposal $nop telah disetujui oleh $username."]);
    }

    public function reject(Request $request)
    {
        $nop = $request->input('nop');
        $username = Session::get('username');

        // Update rejection
        $this->sqlite->execute('UPDATE records_current SET "STATUS" = :status, "APPROVED BY" = :approved_by WHERE "NOP" = :nop', [
            ':status' => 'REJECTED',
            ':approved_by' => $username,
            ':nop' => $nop
        ]);

        return response()->json(['message' => "Proposal $nop telah ditolak oleh $username."]);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $username = Session::get('username', 'Admin'); // Default to Admin if not set for now

        if (!in_array($status, ['Approved', 'Rejected'])) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }

        // Check authorization
        // Only admin can approve/reject
        if ($username !== 'admin') {
             return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.'], 403);
        }

        try {
            // Check current status
            $current = $this->sqlite->query('SELECT "STATUS" FROM records_current WHERE "id" = :id', [':id' => $id]);
            if (empty($current)) {
                return response()->json(['success' => false, 'message' => 'Proposal tidak ditemukan.'], 404);
            }
            
            $currentStatus = strtoupper($current[0]['STATUS'] ?? 'SUBMITTED');
            
            // Check authorization for approving/rejecting
            // Only admin can approve/reject? Or any logged in user?
            // Requirement 1 mentioned "Analisis hak akses user... pastikan permission level sesuai role (admin/manager/staff)"
            // Since we only have simple auth with username, let's assume 'admin' is required for approval actions or specific roles.
            // For now, let's enforce that only 'admin' user can approve/reject to fix the "Validasi submitted" requirement indirectly if needed,
            // OR strictly follow requirement 3: "Validasi submitted tanpa respon... implementasikan pengecekan bahwa belum ada data approval"
            
            // Requirement 3: "Untuk status submitted, implementasikan pengecekan bahwa belum ada data approval (accept/reject) di database."
            // This implies we should check if it's already approved/rejected.
            // Wait, I just removed that check in previous step? No, I removed the block that prevents modification. 
            // The requirement says: "Validasi submitted tanpa respon: Untuk status submitted, implementasikan pengecekan bahwa belum ada data approval (accept/reject) di database."
            // This sounds like ensuring we don't approve/reject something that is already approved/rejected?
            // "Validasi submitted tanpa respon" - maybe it means "Validate that for a submitted status, there is NO response yet".
            // If it HAS a response (Approved/Rejected), we should NOT allow another response?
            
            if (in_array($currentStatus, ['APPROVED', 'REJECTED'])) {
                 return response()->json(['success' => false, 'message' => "Proposal sudah diproses (Status: $currentStatus)."], 400);
            }

            // Update using ID
            $sql = 'UPDATE records_current SET "STATUS" = :status, "APPROVED BY" = :approved_by WHERE "id" = :id';
            $params = [
                ':status' => $status,
                ':approved_by' => $username,
                ':id' => $id
            ];
            
            $this->sqlite->execute($sql, $params);

            return response()->json(['success' => true, 'message' => "Proposal telah diupdate menjadi $status."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function apiData(Request $request)
    {
        $status = $request->input('status');
        $df = $this->loadData($status);
        return response()->json($df);
    }

    private function loadData($status = null)
    {
        try {
        $allCols = $this->sqlite->getColumns('records_current');
        
        // 1. Fetch data with optional filter
        $sql = "SELECT * FROM records_current";
        $params = [];
        
        if ($status && strtolower($status) !== 'all') {
            $sql .= ' WHERE UPPER("STATUS") = :status';
            $params[':status'] = strtoupper($status);
        }
        
        $rows = $this->sqlite->query($sql, $params);
        
        // Fetch counts for stats
        // We can do this efficiently with one query: SELECT STATUS, COUNT(*) as count FROM records_current GROUP BY STATUS
        $statsRows = $this->sqlite->query('SELECT "STATUS", COUNT(*) as count FROM records_current GROUP BY "STATUS"');
        $counts = [
            'all' => 0,
            'submitted' => 0,
            'approved' => 0,
            'rejected' => 0
        ];
        
        foreach ($statsRows as $stat) {
            $s = strtolower($stat['STATUS'] ?? 'submitted');
            $c = $stat['count'];
            $counts['all'] += $c;
            if (isset($counts[$s])) {
                $counts[$s] += $c;
            } else {
                // Map other statuses to submitted or ignore?
                // Assume default is submitted
                $counts['submitted'] += $c; 
            }
        }
        
        // 2. Define standard columns we want to expose
        $desiredOrder = [
            "id", "NOP", "PROGRAM", "KATEGORI", "JUSTIFIKASI", "PROPOSAL", "BUDGET", 
            "REVENUE", "COST", "PROFIT", "INCREMENTAL 1", "INCREMENTAL 2", 
            "INCREMENTAL 3", "STATUS", "PILOT", "DRIVEN PROGRAM", "ASSIGN BY", 
            "APPROVED BY", "ingest_timestamp"
        ];
        
        // 3. Process rows
        $processedRows = [];
        foreach ($rows as $row) {
            $newRow = [];
            foreach ($desiredOrder as $col) {
                $newRow[$col] = $row[$col] ?? null;
            }
            // Handle specific logic if needed
            if (isset($row['REVENUE INCREMENTAL 1']) && empty($newRow['INCREMENTAL 1'])) {
                $newRow['INCREMENTAL 1'] = $row['REVENUE INCREMENTAL 1'];
            }
            $processedRows[] = $newRow;
        }

        return [
            'columns' => $desiredOrder,
            'rows' => $processedRows,
            'counts' => $counts
        ];
        } catch (\Exception $e) {
            return [
                'columns' => [],
                'rows' => [],
                'error' => $e->getMessage()
            ];
        }
    }
}
