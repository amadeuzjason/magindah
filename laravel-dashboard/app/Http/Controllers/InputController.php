<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class InputController extends Controller
{
    protected $db;

    public function __construct(DataService $db)
    {
        $this->db = $db;
    }

    public function show()
    {
        $username = session('username', '');
        $userBranch = '';
        try {
            $userModel = \App\Models\User::where('username', $username)->first();
            if ($userModel) {
                $userBranch = $userModel->lokasi_branch;
            }
        } catch (\Throwable $e) {}

        return view('input', compact('userBranch'));
    }

    public function store(Request $request)
    {
        // Define validation rules based on columns
        $validator = Validator::make($request->all(), [
            'nop' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'justifikasi_objective' => 'nullable|string',
            'justifikasi_alasan' => 'nullable|string',
            'justifikasi_distribusi' => 'nullable|string',
            'justifikasi_lingkup' => 'nullable|string',
            'justifikasi_teknis' => 'nullable|string',
            'justifikasi_rab' => 'nullable|string',
            'justifikasi_summary' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'revenue' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'incremental_1' => 'nullable|numeric',
            'incremental_2' => 'nullable|numeric',
            'incremental_3' => 'nullable|numeric',
            // 'status' => 'auto-set',
            // 'pilot' => removed,
            'driven_program' => 'nullable|string|max:255',
            // assign_by and approved_by are auto-generated
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Auto-generated proposal info since file upload is removed
            // The actual PDF will be generated on-the-fly and we don't store the BLOB on input anymore
            $data['proposal'] = 'Generated PDF'; // Placeholder text for PROPOSAL column

            // Auto-assign logic
            $data['assign_by'] = session('username', 'system');

            $username = session('username', 'user');
            $userFullName = session('user_name', $username);
            $userJabatan = session('user_jabatan', '');
            $sigUrl = null;
            $userBranch = '';
            
            try {
                $userModel = \App\Models\User::where('username', $username)->first();
                if ($userModel) {
                    $sigUrl = $userModel->signature;
                    $userBranch = $userModel->lokasi_branch;
                    $userJabatan = $userModel->jabatan;
                }
            } catch (\Throwable $e) {}

            $isManager = \Illuminate\Support\Str::contains(strtolower($userJabatan ?? ''), ['manager', 'gm', 'vp', 'general manager', 'vice president']);
            
            if (!$isManager && $userBranch) {
                // Find manager of their division
                $managerModel = \App\Models\User::where('lokasi_branch', $userBranch)
                                    ->where(function ($query) {
                                        $query->where('jabatan', 'like', '%manager%')
                                              ->orWhere('jabatan', 'like', '%gm%')
                                              ->orWhere('jabatan', 'like', '%vp%');
                                    })->first();
                if ($managerModel) {
                    $sigUrl = $managerModel->signature;
                    $userFullName = $managerModel->name;
                    $userJabatan = $managerModel->jabatan;
                    $userBranch = $managerModel->lokasi_branch;
                }
            }

            $userTitle = trim($userJabatan . ' ' . $userBranch);

            if ($sigUrl) {
                // Convert to base64 to prevent DomPDF artisan serve deadlock
                $localPath = str_replace(asset('storage/'), storage_path('app/public/'), $sigUrl);
                if (file_exists($localPath)) {
                    $fileType = pathinfo($localPath, PATHINFO_EXTENSION);
                    $fileContent = file_get_contents($localPath);
                    $sigUrl = 'data:image/' . $fileType . ';base64,' . base64_encode($fileContent);
                }
                $userSig = '<img src="' . $sigUrl . '" width="100" style="margin-bottom: 5px;"><br><span style="font-size: 8pt; color: #555;">Signed on ' . date('d M Y') . '</span><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;"><b>' . $userFullName . '</b></div><span style="font-size: 10pt; font-weight: bold; color: black;">' . $userTitle . '</span>';
            } else {
                $userSig = '<br><br><span style="font-size: 8pt; color: #555;">Signed on ' . date('d M Y') . '</span><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;"><b>' . $userFullName . '</b></div><span style="font-size: 10pt; font-weight: bold; color: black;">' . $userTitle . '</span>';
            }

            // Auto-set Status
            $data['status'] = 'Submitted';
            
            // Force approved_by to be NULL for new submissions
            $data['approved_by'] = null;

            // Calculate row_hash
            $hashParts = [];
            foreach ($data as $key => $value) {
                if ($key === 'proposal') continue; 
                $hashParts[] = $key . '=' . ($value ?? '');
            }
            sort($hashParts);
            $rowHash = hash('sha256', implode('|', $hashParts));

            $initialStage = 'Manager_NOP';

            $tableCols = [];
            try {
                $tableCols = $this->db->getColumns('records_current');
            } catch (\Exception $e) {
                $tableCols = [];
            }

            $dataMap = [
                'NOP' => $data['nop'],
                'PROGRAM' => $data['program'],
                'KATEGORI' => $data['kategori'],
                'PROPOSAL' => $data['proposal'],
                'BUDGET' => $data['budget'] ?? null,
                'REVENUE' => $data['revenue'] ?? null,
                'COST' => $data['cost'] ?? null,
                'PROFIT' => $data['profit'] ?? null,
                'INCREMENTAL 1' => $data['incremental_1'] ?? null,
                'INCREMENTAL 2' => $data['incremental_2'] ?? null,
                'INCREMENTAL 3' => $data['incremental_3'] ?? null,
                'STATUS' => $data['status'],
                // 'PILOT' removed
                'DRIVEN PROGRAM' => $data['driven_program'] ?? null,
                'ASSIGN BY' => $data['assign_by'],
                'APPROVED BY' => $data['approved_by'],
                'ingest_timestamp' => now()->toIso8601String(),
                'source_file' => 'web_input_' . session('username', 'user'),
                'row_hash' => $rowHash,
                'justifikasi_objective' => $data['justifikasi_objective'] ?? null,
                'justifikasi_alasan' => $data['justifikasi_alasan'] ?? null,
                'justifikasi_distribusi' => $data['justifikasi_distribusi'] ?? null,
                'justifikasi_lingkup' => $data['justifikasi_lingkup'] ?? null,
                'justifikasi_teknis' => $data['justifikasi_teknis'] ?? null,
                'justifikasi_rab' => $data['justifikasi_rab'] ?? null,
                'justifikasi_summary' => $data['justifikasi_summary'] ?? null,
                'sign_user' => $userSig,
                'approval_stage' => 'Manager_SQA_MBA',
            ];

            $insertCols = [];
            $placeholders = [];
            $params = [];
            $i = 0;
            foreach ($dataMap as $col => $value) {
                if (!in_array($col, $tableCols, true)) continue;
                $ph = ':p' . $i;
                $insertCols[] = '`' . $col . '`';
                $placeholders[] = $ph;
                $params[$ph] = $value;
                $i++;
            }

            if (empty($insertCols)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan: struktur tabel tidak dikenali.'
                ], 500);
            }

            $sql = 'INSERT INTO records_current (' . implode(', ', $insertCols) . ') VALUES (' . implode(', ', $placeholders) . ')';
            $this->db->execute($sql, $params);

            // Fetch the ID of the inserted record
            $insertedRecord = $this->db->query('SELECT id FROM records_current WHERE row_hash = :hash ORDER BY id DESC LIMIT 1', [':hash' => $rowHash]);
            $insertedId = $insertedRecord[0]['id'] ?? null;
            $data['id'] = $insertedId;

            // Send notification to Managers
            try {
                $proposer = \App\Models\User::where('username', $data['assign_by'])->first();
                $data['proposer_name'] = $proposer ? $proposer->name : $data['assign_by'];

                $kat = $data['kategori'] ?? '';
                $recipients = [];
                if (in_array($kat, ['Productivity', 'Civil', 'CME', 'Optime'], true)) {
                    $recipients = ['manager_sqa'];
                } else {
                    $recipients = ['manager_mba'];
                }
                $managers = \App\Models\User::whereIn('username', $recipients)->get();
                if ($managers->count() > 0) \Illuminate\Support\Facades\Mail::to($managers)->send(new \App\Mail\ProposalSubmittedMail($data));
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim email submission: ' . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan! PDF Justifikasi dapat diunduh di halaman persetujuan.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}
