<?php

declare(strict_types=1);

class OrderController
{
    public function __construct(private Order $orderModel, private Product $productModel, private array $config)
    {
    }

    public function index(Request $request): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $orders = $this->orderModel->allByVendor((int)$auth['vendor_id']);
        Response::success('Orders fetched successfully.', $orders);
    }

    public function show(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $order = $this->orderModel->findByIdForVendor((int)$params['id'], (int)$auth['vendor_id']);

        if (!$order) {
            Response::error('Order not found.', 404);
        }

        Response::success('Order fetched successfully.', $order);
    }

    public function store(Request $request): void
    {
        $data = $request->body;
        $required = ['product_id', 'customer_name', 'customer_email', 'quantity'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("The {$field} field is required.", 422);
            }
        }

        $product = $this->productModel->findById((int)$data['product_id']);
        if (!$product) {
            Response::error('Product not found.', 404);
        }

        $quantity = (int)$data['quantity'];
        $data['total_amount'] = $quantity * (float)$product['price'];

        $orderId = $this->orderModel->create($data);
        Response::success('Order created successfully.', ['order_id' => $orderId], 201);
    }

    public function updateStatus(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $status = $request->body['status'] ?? '';

        if ($status === '') {
            Response::error('Status is required.', 422);
        }

        $updated = $this->orderModel->updateStatus((int)$params['id'], (int)$auth['vendor_id'], $status);
        if (!$updated) {
            Response::error('Status update failed. Check order ownership or status value.', 400);
        }

        $order = $this->orderModel->findByIdForVendor((int)$params['id'], (int)$auth['vendor_id']);
        Response::success('Order status updated successfully.', $order);
    }
}
