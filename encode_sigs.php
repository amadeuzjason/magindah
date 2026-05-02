<?php

$files = [
    'manager_nop' => 'd:/12/test-excel-py/sample sign/nop-makassar-sampettd.jpeg',
    'manager_sqa' => 'd:/12/test-excel-py/sample sign/ManagerSQASulawesi_ttd.jpeg',
    'manager_mba' => 'd:/12/test-excel-py/sample sign/ManagerMBASulawesi_ttd.jpeg',
    'manager_nos' => 'd:/12/test-excel-py/sample sign/ManagerNOSSulawesi_ttd.jpeg',
    'manager_gm'  => 'd:/12/test-excel-py/sample sign/GMRNOPSulawesi_ttd.jpeg'
];

$out = [];
foreach ($files as $role => $file) {
    if (file_exists($file)) {
        $out[$role] = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($file));
    }
}

file_put_contents('d:/12/test-excel-py/laravel-dashboard/storage/app/signatures.json', json_encode($out));
echo "Signatures encoded.";
