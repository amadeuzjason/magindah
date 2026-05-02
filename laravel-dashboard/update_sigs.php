<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$db = app('App\Services\DataService');
$records = $db->query('SELECT id, sign_user, sign_manager_nop, sign_manager_sqa_mba, sign_manager_nos, sign_gm FROM records_current');
foreach ($records as $r) {
    foreach (['sign_user', 'sign_manager_nop', 'sign_manager_sqa_mba', 'sign_manager_nos', 'sign_gm'] as $col) {
        $html = $r[$col] ?? '';
        if (strpos($html, 'img src="http') !== false) {
            preg_match('/src="([^"]+)"/', $html, $m);
            if (!empty($m[1])) {
                $url = $m[1];
                $local = str_replace(asset('storage/'), storage_path('app/public/'), $url);
                if (file_exists($local)) {
                    $type = pathinfo($local, PATHINFO_EXTENSION);
                    $data = file_get_contents($local);
                    $b64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    $html = str_replace($url, $b64, $html);
                    $db->execute('UPDATE records_current SET `' . $col . '` = :val WHERE id = :id', [':val' => $html, ':id' => $r['id']]);
                    echo "Updated $col for ID {$r['id']}\n";
                }
            }
        }
    }
}
echo "Done\n";
