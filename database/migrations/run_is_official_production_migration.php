<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
require __DIR__ . '/2026_08_18_000002_add_is_official_to_transaction_tables.php';

(new AddIsOfficialToTransactionTables())->up();
echo "is_official column added successfully to all production tables!\n";
