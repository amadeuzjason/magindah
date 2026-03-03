<?php

require __DIR__ . '/laravel-dashboard/vendor/autoload.php';

$app = require_once __DIR__ . '/laravel-dashboard/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\DashboardController;
use App\Services\SQLiteService;
use Illuminate\Http\Request;

// Mock Request if needed, but apiData doesn't use it.
// We need to resolve DashboardController

try {
    $sqlite = new SQLiteService();
    // We need to make sure SQLiteService uses the correct path.
    // The service might rely on config or hardcoded path.
    // Let's check SQLiteService.php content again to be sure.
    
    $controller = new DashboardController($sqlite);
    $response = $controller->apiData();
    
    echo "Status: " . $response->status() . "\n";
    echo "Content: " . $response->content() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
