<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;

class ProposalController extends Controller
{
    protected $db;

    public function __construct(DataService $db)
    {
        $this->db = $db;
    }

    public function edit($id)
    {
        if (session('username') !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $records = $this->db->query('SELECT * FROM records_current WHERE id = :id', [':id' => $id]);
        if (empty($records)) {
            abort(404, 'Proposal tidak ditemukan.');
        }

        $proposal = $records[0];

        return view('admin.proposals.edit', compact('proposal'));
    }

    public function update(Request $request, $id)
    {
        if (session('username') !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nop'                      => 'required|string|max:255',
            'program'                  => 'required|string|max:255',
            'kategori'                 => 'required|string|max:255',
            'budget'                   => 'nullable|numeric',
            'revenue'                  => 'nullable|numeric',
            'cost'                     => 'nullable|numeric',
            'profit'                   => 'nullable|numeric',
            'incremental_1'            => 'nullable|numeric',
            'incremental_2'            => 'nullable|numeric',
            'incremental_3'            => 'nullable|numeric',
            'driven_program'           => 'nullable|string|max:255',
            'justifikasi_objective'    => 'nullable|string',
            'justifikasi_alasan'       => 'nullable|string',
            'justifikasi_distribusi'   => 'nullable|string',
            'justifikasi_lingkup'      => 'nullable|string',
            'justifikasi_teknis'       => 'nullable|string',
            'justifikasi_rab'          => 'nullable|string',
            'justifikasi_summary'      => 'nullable|string',
        ]);

        $params = [
            ':id'                    => $id,
            ':nop'                   => $request->nop,
            ':program'               => $request->program,
            ':kategori'              => $request->kategori,
            ':budget'                => $request->budget,
            ':revenue'               => $request->revenue,
            ':cost'                  => $request->cost,
            ':profit'                => $request->profit,
            ':i1'                    => $request->incremental_1,
            ':i2'                    => $request->incremental_2,
            ':i3'                    => $request->incremental_3,
            ':dp'                    => $request->driven_program,
            ':obj'                   => $request->justifikasi_objective,
            ':alasan'                => $request->justifikasi_alasan,
            ':distribusi'            => $request->justifikasi_distribusi,
            ':lingkup'               => $request->justifikasi_lingkup,
            ':teknis'                => $request->justifikasi_teknis,
            ':rab'                   => $request->justifikasi_rab,
            ':summary'               => $request->justifikasi_summary,
        ];

        $sql = "UPDATE records_current SET
            `NOP` = :nop,
            `PROGRAM` = :program,
            `KATEGORI` = :kategori,
            `BUDGET` = :budget,
            `REVENUE` = :revenue,
            `COST` = :cost,
            `PROFIT` = :profit,
            `INCREMENTAL 1` = :i1,
            `INCREMENTAL 2` = :i2,
            `INCREMENTAL 3` = :i3,
            `DRIVEN PROGRAM` = :dp,
            `justifikasi_objective` = :obj,
            `justifikasi_alasan` = :alasan,
            `justifikasi_distribusi` = :distribusi,
            `justifikasi_lingkup` = :lingkup,
            `justifikasi_teknis` = :teknis,
            `justifikasi_rab` = :rab,
            `justifikasi_summary` = :summary
            WHERE id = :id
        ";

        $this->db->execute($sql, $params);

        return redirect()->route('approvals')->with('success', 'Data proposal berhasil diperbarui oleh admin.');
    }
}
