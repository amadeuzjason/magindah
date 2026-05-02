<?php

// Script to copy data from data_pipeline.sqlite to MySQL db_excel_nop records_current

$sqlite = new SQLite3(__DIR__ . '/data_pipeline.sqlite');
$mysql = new PDO('mysql:host=127.0.0.1;dbname=db_excel_nop;charset=utf8mb4', 'root', '');

// Clear existing records
$mysql->exec("TRUNCATE TABLE records_current");

$res = $sqlite->query('SELECT * FROM records_current');

$inserted = 0;
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $columns = array_keys($row);
    
    // SQLite might have id, but let's just insert all fields as is including ID to maintain relationships if any
    
    $cols = implode(', ', array_map(function($c) { return "`$c`"; }, $columns));
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    
    $sql = "INSERT INTO records_current ($cols) VALUES ($placeholders)";
    
    $stmt = $mysql->prepare($sql);
    
    $values = array_values($row);
    try {
        $stmt->execute($values);
        $inserted++;
    } catch (PDOException $e) {
        echo "Error on row: " . json_encode($row) . "\n";
        echo $e->getMessage() . "\n";
    }
}

echo "Successfully restored $inserted records.\n";
