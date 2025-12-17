<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

echo "Starting Manual Seeder Debug...\n";

try {
    DB::beginTransaction(); // Wrap in transaction to rollback so we don't mess up half-state if possible, though schema changes happened already
    
    // We assume migration is done or we just want to test the seeder logic
    // If migration failed, this script won't help much, but migration *tables* seem to exist from logs
    
    $seeder = new DatabaseSeeder();
    $seeder->run();

    echo "Seeding completed successfully (simulation)!\n";
    DB::rollBack(); 

} catch (Exception $e) {
    echo "\n\nCRITICAL SEEDER ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    
    if ($e instanceof Illuminate\Database\QueryException) {
        echo "SQL: " . $e->getSql() . "\n";
        echo "Bindings: " . json_encode($e->getBindings()) . "\n";
    }
    
    DB::rollBack();
    exit(1);
}
