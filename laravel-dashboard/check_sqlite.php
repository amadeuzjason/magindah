<?php
$db = new SQLite3('../data_pipeline.sqlite');
$columns = [
    'justifikasi_objective',
    'justifikasi_alasan',
    'justifikasi_distribusi',
    'justifikasi_lingkup',
    'justifikasi_teknis',
    'justifikasi_rab',
    'justifikasi_summary',
    'sign_user',
    'sign_manager_nop',
    'sign_manager_sqa_mba',
    'sign_manager_nos',
    'sign_gm',
    'approval_stage'
];

foreach ($columns as $col) {
    try {
        $db->exec("ALTER TABLE records_current ADD COLUMN $col TEXT");
        echo "Added $col\n";
    } catch (Exception $e) {
        echo "Error adding $col: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
