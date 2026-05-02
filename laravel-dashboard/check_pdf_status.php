<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$filename = 'Trustworthy AI is crucial for business video transcript_EN.pdf';
// Try to find partially
$record = DB::table('records_current')
            ->where('PROPOSAL', 'LIKE', '%' . $filename . '%')
            ->first();

if ($record) {
    echo "Found record ID: " . $record->id . "\n";
    echo "Stored Path: " . $record->PROPOSAL . "\n";
    
    // Check if file exists
    // The path in DB is usually 'storage/proposals/...'
    // The physical path should be 'public/storage/proposals/...' (if symlinked) or 'storage/app/public/proposals/...'
    
    $relativePath = $record->PROPOSAL;
    // Remove 'storage/' prefix if present to find in storage/app/public
    $internalPath = str_replace('storage/', '', $relativePath);
    
    $physicalPath = storage_path('app/public/' . $internalPath);
    echo "Expected Physical Path: " . $physicalPath . "\n";
    echo "Exists: " . (file_exists($physicalPath) ? 'YES' : 'NO') . "\n";
    
    $publicPath = public_path($relativePath);
    echo "Expected Public Symlink Path: " . $publicPath . "\n";
    echo "Symlink Target Exists: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
    
} else {
    echo "Record not found in database for filename: $filename\n";
}
