<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$prod = App\Models\Product::first();
echo 'DB_IMAGE:' . ($prod ? $prod->image : 'NULL') . PHP_EOL;