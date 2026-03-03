<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SQLiteService;
use Illuminate\Support\Facades\Validator;

class InputController extends Controller
{
    protected $sqlite;

    public function __construct(SQLiteService $sqlite)
    {
        $this->sqlite = $sqlite;
    }

    public function show()
    {
        return view('input');
    }

    public function store(Request $request)
    {
        // Define validation rules based on columns
        $validator = Validator::make($request->all(), [
            'nop' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'justifikasi' => 'nullable|string',
            'proposal' => 'required|file|mimes:pdf|max:5120', // Max 5MB, PDF only
            'budget' => 'nullable|numeric',
            'revenue' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'incremental_1' => 'nullable|numeric',
            'incremental_2' => 'nullable|numeric',
            'incremental_3' => 'nullable|numeric',
            // 'status' => 'required|string|in:Draft,Submitted,Approved,Rejected', // Removed as it is auto-set
            'pilot' => 'nullable|string|max:255',
            'driven_program' => 'nullable|string|max:255',
            // assign_by and approved_by are removed from validation as they are auto-generated
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

            // Handle File Upload
            if ($request->hasFile('proposal')) {
                $file = $request->file('proposal');
                $filename = 'proposal_' . time() . '_' . $file->getClientOriginalName();
                // Save to public storage (accessible via symlink usually, or private if needed)
                // For simplicity in this local setup, we store in storage/app/public/proposals
                $path = $file->storeAs('public/proposals', $filename);
                // We store the relative path or filename in the DB
                $data['proposal'] = str_replace('public/', 'storage/', $path);
            }

            // Auto-assign logic
            $data['assign_by'] = session('username', 'system');

            // Auto-set Status
            $data['status'] = 'Submitted';
            
            // Force approved_by to be NULL for new submissions
            $data['approved_by'] = null;

            // Calculate row_hash
            $hashParts = [];
            foreach ($data as $key => $value) {
                if ($key === 'proposal') continue; // Exclude file path from hash to avoid issues with dynamic names? Or keep it? keeping it is safer for uniqueness.
                $hashParts[] = $key . '=' . ($value ?? '');
            }
            sort($hashParts);
            $rowHash = hash('sha256', implode('|', $hashParts));

            // Prepare SQL insert
            $sql = "INSERT INTO records_current (
                NOP, PROGRAM, KATEGORI, JUSTIFIKASI, PROPOSAL, BUDGET, 
                REVENUE, COST, PROFIT, \"INCREMENTAL 1\", \"INCREMENTAL 2\", 
                \"INCREMENTAL 3\", STATUS, PILOT, \"DRIVEN PROGRAM\", \"ASSIGN BY\", 
                \"APPROVED BY\", ingest_timestamp, source_file, row_hash
            ) VALUES (
                :nop, :program, :kategori, :justifikasi, :proposal, :budget, 
                :revenue, :cost, :profit, :incremental_1, :incremental_2, 
                :incremental_3, :status, :pilot, :driven_program, :assign_by, 
                :approved_by, :ingest_timestamp, :source_file, :row_hash
            )";

            $params = [
                ':nop' => $data['nop'],
                ':program' => $data['program'],
                ':kategori' => $data['kategori'],
                ':justifikasi' => $data['justifikasi'] ?? null,
                ':proposal' => $data['proposal'],
                ':budget' => $data['budget'] ?? null,
                ':revenue' => $data['revenue'] ?? null,
                ':cost' => $data['cost'] ?? null,
                ':profit' => $data['profit'] ?? null,
                ':incremental_1' => $data['incremental_1'] ?? null,
                ':incremental_2' => $data['incremental_2'] ?? null,
                ':incremental_3' => $data['incremental_3'] ?? null,
                ':status' => $data['status'],
                ':pilot' => $data['pilot'] ?? null,
                ':driven_program' => $data['driven_program'] ?? null,
                ':assign_by' => $data['assign_by'],
                ':approved_by' => $data['approved_by'],
                ':ingest_timestamp' => now()->toIso8601String(),
                ':source_file' => 'web_input_' . session('username', 'user'),
                ':row_hash' => $rowHash
            ];

            $this->sqlite->execute($sql, $params);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan! Proposal diunggah: ' . $data['proposal']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}
