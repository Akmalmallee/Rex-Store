<?php
// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check products
$products = \App\Models\Product::select('id', 'name', 'thumbnail', 'updated_at')->limit(5)->get();

echo "=== Products Debug Info ===\n\n";
foreach ($products as $product) {
    echo "ID: {$product->id}\n";
    echo "Name: {$product->name}\n";
    echo "Thumbnail: {$product->thumbnail}\n";
    echo "Updated At: {$product->updated_at}\n";
    echo "---\n";
}

// Check if storage/app/public/products directory exists
echo "\n=== Storage Directory Check ===\n";
$productsDir = storage_path('app/public/products');
echo "Products dir path: $productsDir\n";
echo "Exists: " . (is_dir($productsDir) ? "YES" : "NO") . "\n";

$thumbnailDir = storage_path('app/public/products/thumbnails');
echo "Thumbnails dir path: $thumbnailDir\n";
echo "Exists: " . (is_dir($thumbnailDir) ? "YES" : "NO") . "\n";

// Check public/storage symlink
echo "\n=== Symlink Check ===\n";
$publicStorage = public_path('storage');
echo "Public storage link: $publicStorage\n";
echo "Is link: " . (is_link($publicStorage) ? "YES" : "NO") . "\n";
echo "Is dir: " . (is_dir($publicStorage) ? "YES" : "NO") . "\n";

if (is_link($publicStorage)) {
    echo "Link target: " . readlink($publicStorage) . "\n";
}
