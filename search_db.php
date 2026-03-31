<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = DB::select('SHOW TABLES'); 
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    $columns = Schema::getColumnListing($tableName);
    foreach ($columns as $column) {
        try {
            $count = DB::table($tableName)->where($column, 'APPROVED')->count();
            if ($count > 0) {
                echo "Found $count rows in table '$tableName', column '$column' with value 'APPROVED'\n";
            }
        } catch (\Exception $e) {
            // Probably not a string column or other error
        }
    }
}
echo "Check complete.\n";
