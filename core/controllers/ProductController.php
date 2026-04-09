<?php

declare(strict_types=1);

class ProductController
{
    public function __construct(private Product $productModel, private array $config)
    {
    }

    public function index(Request $request): void
    {
        $filters = [
            'category' => $request->query['category'] ?? null,
            'location' => $request->query['location'] ?? null,
            'min_rating' => $request->query['min_rating'] ?? null,
            'search' => $request->query['search'] ?? null,
        ];

        Response::success('Products fetched successfully.', $this->productModel->all($filters));
    }

    public function show(array $params): void
    {
        $product = $this->productModel->findById((int)$params['id']);
        if (!$product) {
            Response::error('Product not found.', 404);
        }

        Response::success('Product fetched successfully.', $product);
    }

    public function store(Request $request): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $data = $request->body;
        $required = ['name', 'category', 'price'];

        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                Response::error("The {$field} field is required.", 422);
            }
        }

        $productId = $this->productModel->create($data, (int)$auth['vendor_id']);
        Response::success('Product created successfully.', $this->productModel->findById($productId), 201);
    }

    public function update(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $productId = (int)$params['id'];

        $existing = $this->productModel->findById($productId);
        if (!$existing) {
            Response::error('Product not found.', 404);
        }

        $data = array_merge($existing, $request->body);
        $updated = $this->productModel->update($productId, (int)$auth['vendor_id'], $data);

        if (!$updated) {
            Response::error('Product update failed or not allowed.', 403);
        }

        Response::success('Product updated successfully.', $this->productModel->findById($productId));
    }

    public function delete(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $deleted = $this->productModel->delete((int)$params['id'], (int)$auth['vendor_id']);

        if (!$deleted) {
            Response::error('Product delete failed or not allowed.', 403);
        }

        Response::success('Product deleted successfully.');
    }
}
