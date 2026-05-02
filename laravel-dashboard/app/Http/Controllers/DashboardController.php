<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class DashboardController extends Controller
{
    protected $db;

    public function __construct(DataService $db)
    {
        $this->db = $db;
    }

    public function index()
    {
        return view('dashboard', [
            'username' => Session::get('username')
        ]);
    }

    public function guide()
    {
        return view('guide', [
            'username' => Session::get('username')
        ]);
    }

    public function approvals()
    {
        $username = Session::get('username');

        return view('approvals', [
            'username' => $username
        ]);
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $username = Session::get('username', 'user'); 
        $userFullName = Session::get('user_name', $username); // Get real name, fallback to username

        if (!in_array($status, ['Approved', 'Rejected'])) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
        }
        
        $allowed = ['admin', 'NOP-MKS', 'NOP-PALU', 'NOP-MANADO', 'manager_sqa', 'manager_mba', 'manager_nos', 'manager_gm'];
        if (!in_array($username, $allowed, true)) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.'], 403);
        }

        try {
            $current = $this->db->query('SELECT * FROM records_current WHERE `id` = :id', [':id' => $id]);
            if (empty($current)) {
                return response()->json(['success' => false, 'message' => 'Proposal tidak ditemukan.'], 404);
            }
            
            $record = $current[0];
            $currentStatus = strtoupper($record['STATUS'] ?? 'SUBMITTED');
            $tableCols = [];
            try {
                $tableCols = $this->db->getColumns('records_current');
            } catch (\Exception $e) {
                $tableCols = [];
            }
            
            if (in_array($currentStatus, ['APPROVED', 'REJECTED'])) {
                 return response()->json(['success' => false, 'message' => "Proposal sudah diproses (Status: $currentStatus)."], 400);
            }

            if ($status === 'Rejected') {
                $sql = 'UPDATE records_current SET `STATUS` = :status, `APPROVED BY` = :approved_by WHERE `id` = :id';
                $this->db->execute($sql, [
                    ':status' => 'REJECTED',
                    ':approved_by' => trim(($record['APPROVED BY'] ?? '') . ' ' . $userFullName),
                    ':id' => $id
                ]);
                return response()->json(['success' => true, 'message' => "Proposal telah ditolak."]);
            }

            $hasApprovalStage = in_array('approval_stage', $tableCols, true);
            if (!$hasApprovalStage) {
                $sql = 'UPDATE records_current SET `STATUS` = :status, `APPROVED BY` = :approved_by WHERE `id` = :id';
                $this->db->execute($sql, [
                    ':status' => 'APPROVED',
                    ':approved_by' => trim(($record['APPROVED BY'] ?? '') . ' ' . $userFullName),
                    ':id' => $id
                ]);
                return response()->json(['success' => true, 'message' => "Proposal $id telah di-approve."]);
            }

            $stage = $record['approval_stage'] ?? null;
            if (!$stage || strtolower(trim((string) $stage)) === 'submitted') $stage = 'Manager_NOP';
            $newStatus = $currentStatus;
            $updateFields = [];
            $params = [];
            
            $userSigImage = null;
            $userBranch = '';
            try {
                $userModel = \App\Models\User::where('username', $username)->first();
                if ($userModel) {
                    $userSigImage = $userModel->signature;
                    $userBranch = $userModel->lokasi_branch;
                }
            } catch (\Throwable $e) {}
            
            $userJabatan = session('user_jabatan', 'Manager');
            $userTitle = trim($userJabatan . ' ' . $userBranch);

            if ($userSigImage) {
                // Convert to base64 to prevent DomPDF artisan serve deadlock
                $localPath = str_replace(asset('storage/'), storage_path('app/public/'), $userSigImage);
                if (file_exists($localPath)) {
                    $type = pathinfo($localPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($localPath);
                    $userSigImage = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
                $signatureHtml = '<img src="' . $userSigImage . '" width="100" style="margin-bottom: 5px;"><br><span style="font-size: 8pt; color: #555;">Signed on ' . date('d M Y') . '</span>';
            } else {
                $signatureHtml = '<br><br><span style="font-size: 8pt; color: #555;">Signed on ' . date('d M Y') . '</span>';
            }
            $finalSignature = $signatureHtml . '<div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;"><b>' . $userFullName . '</b></div><span style="font-size: 10pt; font-weight: bold; color: black;">' . $userTitle . '</span>';

            if ($stage === 'Manager_SQA_MBA') {
                $nextStage = 'Manager_NOS';
                if (in_array('sign_manager_sqa_mba', $tableCols, true)) {
                    $updateFields[] = "`sign_manager_sqa_mba` = :sign";
                    $params[':sign'] = $finalSignature;
                }
                $this->notifyNextApprovers(['manager_nos'], $record, 'Manager NOS');
            } elseif ($stage === 'Manager_NOS') {
                $nextStage = 'GM_RNOP';
                if (in_array('sign_manager_nos', $tableCols, true)) {
                    $updateFields[] = "`sign_manager_nos` = :sign";
                    $params[':sign'] = $finalSignature;
                }
                $this->notifyNextApprovers(['manager_gm'], $record, 'GM RNOP');
            } elseif ($stage === 'GM_RNOP') {
                $nextStage = 'Approved';
                $newStatus = 'APPROVED';
                if (in_array('sign_gm', $tableCols, true)) {
                    $updateFields[] = "`sign_gm` = :sign";
                    $params[':sign'] = $finalSignature;
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Proposal sudah dalam status Approved.'], 400);
            }

            $updateFields[] = "`approval_stage` = :stage";
            $updateFields[] = "`STATUS` = :newStatus";
            $updateFields[] = "`APPROVED BY` = :approved_by";
            
            $params[':stage'] = $nextStage;
            $params[':newStatus'] = $newStatus;
            $params[':approved_by'] = trim(($record['APPROVED BY'] ?? '') . ' ' . $userFullName);
            $params[':id'] = $id;

            $sql = 'UPDATE records_current SET ' . implode(', ', $updateFields) . ' WHERE `id` = :id';
            $this->db->execute($sql, $params);

            // If fully approved, notify proposer
            if ($newStatus === 'APPROVED') {
                try {
                    // Fetch full record to get proposer
                    $fullRecord = $this->db->query('SELECT * FROM records_current WHERE `id` = :id', [':id' => $id]);
                    if (!empty($fullRecord)) {
                        $rec = $fullRecord[0];
                        $proposerUsername = $rec['ASSIGN BY'] ?? null;
                        if ($proposerUsername) {
                            $proposerUser = null;
                            try {
                                $proposerUser = User::where('username', $proposerUsername)->first();
                            } catch (\Throwable $e) {
                                $proposerUser = null;
                            }
                            if ($proposerUser && $proposerUser->email) {
                                $mailData = [
                                    'nop' => $rec['NOP'] ?? '-',
                                    'program' => $rec['PROGRAM'] ?? '-',
                                    'kategori' => $rec['KATEGORI'] ?? '-',
                                ];
                                \Illuminate\Support\Facades\Mail::to($proposerUser->email)->send(new \App\Mail\ProposalApprovedMail($mailData));
                            }
                        }
                    }
                } catch (\Exception $mailEx) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email approval: ' . $mailEx->getMessage());
                }
            }

            return response()->json(['success' => true, 'message' => "Proposal $id telah di-approve ($nextStage)."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showProposal($id)
    {
        // Redirect completely to the generated PDF route
        return redirect()->route('proposal.pdf', ['id' => $id]);
    }

    public function apiData(Request $request)
    {
        $status = $request->input('status');
        $context = $request->input('context');
        $df = $this->loadData($status);

        if ($context === 'approvals') {
             $username = Session::get('username');
             // Show all proposals in approvals
             
             // Update counts to reflect filtered data
             $counts = ['all' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0];
             foreach ($df['rows'] as $row) {
                 $counts['all']++;
                 $s = strtolower($row['STATUS'] ?? 'submitted');
                 if (isset($counts[$s])) $counts[$s]++; else $counts['submitted']++;
             }
             $df['counts'] = $counts;
        }
        
        if ($context === 'review') {
            $username = Session::get('username');
            $fullName = Session::get('user_name', $username);
            
            if (!$username) {
                $df['rows'] = [];
                $df['counts'] = ['all' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0];
                return response()->json($df);
            }
            
            $filteredRows = $this->filterForReview($df['rows'], $username, $fullName);
            $df['rows'] = array_values($filteredRows);
            
            $counts = ['all' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0];
            foreach ($df['rows'] as $row) {
                $counts['all']++;
                $s = strtolower($row['STATUS'] ?? 'submitted');
                if (isset($counts[$s])) $counts[$s]++; else $counts['submitted']++;
            }
            $df['counts'] = $counts;
        }

        return response()->json($df);
    }

    private function notifyNextApprovers($usernames, $proposal, $stageName)
    {
        try {
            $managers = User::whereIn('username', $usernames)->get();
            if ($managers->count() > 0) {
                $mailData = [
                    'nop' => $proposal['NOP'] ?? '-',
                    'program' => $proposal['PROGRAM'] ?? '-',
                    'kategori' => $proposal['KATEGORI'] ?? '-',
                    'assign_by' => $proposal['ASSIGN BY'] ?? 'System',
                ];
                
                \Illuminate\Support\Facades\Mail::to($managers)->send(new \App\Mail\ProposalNextStageMail($mailData, $stageName));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email notifikasi next stage: ' . $e->getMessage());
        }
    }

    private function filterForApprovals($rows, $username)
    {
        $fullName = Session::get('user_name', $username);
        return array_filter($rows, function($row) use ($username, $fullName) {
            $stage = $row['approval_stage'] ?? 'Submitted';
            $status = strtoupper($row['STATUS'] ?? '');
            
            // If admin, they see all
            if ($username === 'admin') return true;

            // If it's already processed, check if they were involved
            if ($status === 'APPROVED' || $status === 'REJECTED') {
                $approvedBy = strtolower((string) ($row['APPROVED BY'] ?? ''));
                if (strpos($approvedBy, strtolower($fullName)) !== false || strpos($approvedBy, strtolower($username)) !== false) {
                    return true;
                }
                return false;
            }
            
            if (in_array($username, ['NOP-MKS', 'NOP-PALU', 'NOP-MANADO'], true) && $stage === 'Manager_NOP') {
                // Determine if this NOP manager should see it based on Assign By or location if we have it
                // For now, any NOP manager can see NOP stage proposals (or we can filter by user branch)
                return true;
            }
            
            if ($stage === 'Manager_SQA_MBA') {
                $kat = $row['KATEGORI'] ?? '';
                if ($username === 'manager_sqa' && in_array($kat, ['Productivity', 'Civil', 'CME', 'Optime'], true)) return true;
                if ($username === 'manager_mba' && in_array($kat, ['Power', 'CME Electrical', 'PSB', 'Transmisi'], true)) return true;
            }
            
            if (in_array($username, ['manager_nos'], true) && $stage === 'Manager_NOS') return true;
            if (in_array($username, ['manager_gm'], true) && $stage === 'GM_RNOP') return true;
            
            return false;
        });
    }
    
    private function filterForReview($rows, $username, $fullName)
    {
        if ($username === 'admin') return $rows;
        
        $isNopUser = is_string($username) && str_starts_with($username, 'NOP-');
        if ($isNopUser) {
            return array_filter($rows, function($row) use ($username) {
                return ($row['ASSIGN BY'] ?? null) === $username;
            });
        }
        
        $isApprover = in_array($username, ['manager_sqa', 'manager_mba', 'manager_nos', 'manager_gm'], true);
        if ($isApprover) {
            $needle1 = strtolower((string) $fullName);
            $needle2 = strtolower((string) $username);
            return array_filter($rows, function($row) use ($needle1, $needle2) {
                $approvedBy = strtolower((string) ($row['APPROVED BY'] ?? ''));
                if ($approvedBy === '') return false;
                return (strpos($approvedBy, $needle1) !== false) || (strpos($approvedBy, $needle2) !== false);
            });
        }
        
        return array_filter($rows, function($row) use ($username) {
            return ($row['ASSIGN BY'] ?? null) === $username;
        });
    }

    private function loadData($status = null)
    {
        try {
        $allCols = $this->db->getColumns('records_current');
        
        // Exclude heavy text/blob columns to speed up loading
        $excludeCols = [
            'proposal_blob', 
            'justifikasi_objective', 'justifikasi_alasan', 'justifikasi_distribusi', 
            'justifikasi_lingkup', 'justifikasi_teknis', 'justifikasi_rab', 'justifikasi_summary',
            'sign_user', 'sign_manager_nop', 'sign_manager_sqa_mba', 'sign_manager_nos', 'sign_gm'
        ];
        
        $selectCols = [];
        foreach ($allCols as $col) {
            if (!in_array($col, $excludeCols, true)) {
                $selectCols[] = '`' . $col . '`';
            }
        }
        $selectStr = empty($selectCols) ? '*' : implode(', ', $selectCols);
        
        // 1. Fetch data with optional filter
        $sql = "SELECT $selectStr FROM records_current";
        $params = [];
        
        if ($status && strtolower($status) !== 'all') {
            $sql .= ' WHERE UPPER(`STATUS`) = :status';
            $params[':status'] = strtoupper($status);
        }
        
        $rows = $this->db->query($sql, $params);
        
        // Fetch counts for stats
        $statsRows = $this->db->query('SELECT `STATUS`, COUNT(*) as count FROM records_current GROUP BY `STATUS`');
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
        // ID is excluded from columns list to hide it in frontend table, but included in row data
        $desiredOrder = [
            "NOP", "PROGRAM", "KATEGORI", "PROPOSAL", "BUDGET", 
            "REVENUE", "COST", "PROFIT", "INCREMENTAL 1", "INCREMENTAL 2", 
            "INCREMENTAL 3", "STATUS", "PILOT", "DRIVEN PROGRAM", "ASSIGN BY", 
            "APPROVED BY", "ingest_timestamp", "approval_stage"
        ];
        
        // 3. Process rows
        $processedRows = [];
        foreach ($rows as $row) {
            $newRow = [];
            $newRow['id'] = $row['id'] ?? null; // Keep ID for backend operations
            
            // Add Justification fields (hidden from main columns but available for details)
            $justFields = [
                'justifikasi_objective', 'justifikasi_alasan', 'justifikasi_distribusi',
                'justifikasi_lingkup', 'justifikasi_teknis', 'justifikasi_rab', 'justifikasi_summary'
            ];
            foreach ($justFields as $field) {
                $newRow[$field] = $row[$field] ?? null;
            }

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
                'counts' => [
                    'all' => 0,
                    'submitted' => 0,
                    'approved' => 0,
                    'rejected' => 0
                ],
                'error' => $e->getMessage()
            ];
        }
    }
}
