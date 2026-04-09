<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

$config = require __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/utils/JwtHandler.php';
require_once __DIR__ . '/../core/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../core/models/Vendor.php';
require_once __DIR__ . '/../core/models/Product.php';
require_once __DIR__ . '/../core/models/Order.php';
require_once __DIR__ . '/../core/controllers/AuthController.php';
require_once __DIR__ . '/../core/controllers/VendorController.php';
require_once __DIR__ . '/../core/controllers/ProductController.php';
require_once __DIR__ . '/../core/controllers/OrderController.php';

$db = Database::connect($config);
$request = new Request();
$router = new Router();

$vendorModel = new Vendor($db);
$productModel = new Product($db);
$orderModel = new Order($db);

$authController = new AuthController($vendorModel, $config);
$vendorController = new VendorController($vendorModel, $config);
$productController = new ProductController($productModel, $config);
$orderController = new OrderController($orderModel, $productModel, $config);

$basePath = $config['app']['base_path'];

$router->add('GET', '/', function () {
    Response::success('Ethical Marketplace API is running.', [
        'version' => '1.0.0',
        'documentation' => 'See README.md and docs/postman_examples.md',
    ]);
});

$router->add('POST', $basePath . '/register', function (Request $request) use ($authController) {
    $authController->register($request);
});

$router->add('POST', $basePath . '/login', function (Request $request) use ($authController) {
    $authController->login($request);
});

$router->add('GET', $basePath . '/me', function (Request $request) use ($authController) {
    $authController->me($request);
});

$router->add('GET', $basePath . '/vendors', function () use ($vendorController) {
    $vendorController->index();
});

$router->add('GET', $basePath . '/vendors/{id}', function (Request $request, array $params) use ($vendorController) {
    $vendorController->show($params);
});

$router->add('POST', $basePath . '/vendors', function (Request $request) use ($vendorController) {
    $vendorController->store($request);
});

$router->add('PUT', $basePath . '/vendors/{id}', function (Request $request, array $params) use ($vendorController) {
    $vendorController->update($request, $params);
});

$router->add('DELETE', $basePath . '/vendors/{id}', function (Request $request, array $params) use ($vendorController) {
    $vendorController->delete($request, $params);
});

$router->add('GET', $basePath . '/products', function (Request $request) use ($productController) {
    $productController->index($request);
});

$router->add('GET', $basePath . '/products/{id}', function (Request $request, array $params) use ($productController) {
    $productController->show($params);
});

$router->add('POST', $basePath . '/products', function (Request $request) use ($productController) {
    $productController->store($request);
});

$router->add('PUT', $basePath . '/products/{id}', function (Request $request, array $params) use ($productController) {
    $productController->update($request, $params);
});

$router->add('DELETE', $basePath . '/products/{id}', function (Request $request, array $params) use ($productController) {
    $productController->delete($request, $params);
});

$router->add('GET', $basePath . '/orders', function (Request $request) use ($orderController) {
    $orderController->index($request);
});

$router->add('GET', $basePath . '/orders/{id}', function (Request $request, array $params) use ($orderController) {
    $orderController->show($request, $params);
});

$router->add('POST', $basePath . '/orders', function (Request $request) use ($orderController) {
    $orderController->store($request);
});

$router->add('PUT', $basePath . '/orders/{id}/status', function (Request $request, array $params) use ($orderController) {
    $orderController->updateStatus($request, $params);
});

$router->dispatch($request);
