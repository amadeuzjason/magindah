<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $db;

    public function __construct(DataService $db)
    {
        $this->db = $db;
    }

    public function generatePdf($id)
    {
        // 1. Fetch data
        $rows = $this->db->query('SELECT * FROM records_current WHERE `id` = :id', [':id' => $id]);
        
        if (empty($rows)) {
            abort(404, 'Proposal tidak ditemukan');
        }
        
        $proposal = $rows[0];
        
        // 1.5 Convert old remote localhost URLs in signatures/justifikasi to base64 to prevent dompdf deadlocks
        $baseUrl = url('/');
        $fieldsToProcess = ['sign_user', 'sign_manager_sqa_mba', 'sign_manager_nos', 'sign_gm'];
        foreach ($fieldsToProcess as $field) {
            if (!empty($proposal[$field])) {
                // Find <img src="http://.../storage/...">
                $proposal[$field] = preg_replace_callback('/src="('.preg_quote($baseUrl, '/').'\/storage\/([^"]+))"/', function($matches) {
                    $localPath = storage_path('app/public/' . $matches[2]);
                    if (file_exists($localPath)) {
                        $type = pathinfo($localPath, PATHINFO_EXTENSION);
                        $data = file_get_contents($localPath);
                        return 'src="data:image/' . $type . ';base64,' . base64_encode($data) . '"';
                    }
                    return $matches[0];
                }, $proposal[$field]);
            }
        }
        
        // 2. Load View and Pass Data
        $pdf = Pdf::loadView('pdf.justifikasi', ['proposal' => $proposal]);
        
        // 3. Customize options if needed
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', false); // Prevent deadlock with php artisan serve
        
        // 4. Download PDF
        $fileName = 'Justifikasi_' . ($proposal['NOP'] ?? 'Program') . '.pdf';
        
        if (strtoupper($proposal['STATUS'] ?? '') !== 'APPROVED') {
            return $pdf->stream($fileName);
        }
        return $pdf->download($fileName);
    }
}
