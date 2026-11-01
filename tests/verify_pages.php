<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

$routesToTest = [
    '/' => ['status' => 200, 'see' => ['A&C', 'Connecting Global Brands to UK Markets']],
    '/products' => ['status' => 200, 'see' => ['Hardware', 'A&C']],
    '/retail' => ['status' => 200, 'see' => ['Consumer', 'A&C']],
    '/bulk-buy' => ['status' => 200, 'see' => ['Master Carton', 'Wholesale']],
    '/about' => ['status' => 200, 'see' => ['Connecting Global Brands to UK Markets', 'Trusted Partnerships']],
    '/contact' => ['status' => 200, 'see' => ['UK Trade Desk', 'London Logistics']],
    '/privacy' => ['status' => 200, 'see' => ['Privacy', 'A&C Electronics']],
    '/terms' => ['status' => 200, 'see' => ['Terms', 'A&C Electronics']],
    '/cart' => ['status' => 200, 'see' => ['Bag', 'A&C']],
    '/login' => ['status' => 200, 'see' => ['A&C ELECTRONICS', 'Sign In']],
    '/register' => ['status' => 200, 'see' => ['A&C ELECTRONICS', 'Create an Account']],
];

echo "=== A&C ELECTRONICS STOREFRONT VERIFICATION ===\n";
$allPassed = true;

foreach ($routesToTest as $uri => $expectations) {
    $request = Request::create($uri, 'GET');
    $response = $app->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();

    $statusOk = ($status === $expectations['status']);
    $contentOk = true;
    $missingTerms = [];

    foreach ($expectations['see'] as $term) {
        if (stripos($content, $term) === false) {
            $contentOk = false;
            $missingTerms[] = $term;
        }
    }

    if ($statusOk && $contentOk) {
        echo " [PASS] {$uri} (Status {$status})\n";
    } else {
        $allPassed = false;
        echo " [FAIL] {$uri} (Status {$status})";
        if (!$contentOk) {
            echo " Missing terms: " . implode(', ', $missingTerms);
        }
        echo "\n";
    }
}

// Test first product PDP
$firstProduct = \Lunar\Models\Product::with('urls')->first();
if ($firstProduct) {
    $slug = $firstProduct->urls->first()?->slug ?? $firstProduct->id;
    $pdpUri = '/product/' . $slug;
    $request = Request::create($pdpUri, 'GET');
    $response = $app->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();

    if ($status === 200 && stripos($content, 'A&C') !== false) {
        echo " [PASS] {$pdpUri} (Status 200 - Product PDP)\n";
    } else {
        $allPassed = false;
        echo " [FAIL] {$pdpUri} (Status {$status} - Product PDP)\n";
    }
}

echo "===============================================\n";
if ($allPassed) {
    echo "ALL PAGES VERIFIED SUCCESSFULLY!\n";
    exit(0);
} else {
    echo "SOME CHECKS FAILED!\n";
    exit(1);
}
