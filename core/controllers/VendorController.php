<?php

declare(strict_types=1);

class VendorController
{
    public function __construct(private Vendor $vendorModel, private array $config)
    {
    }

    public function index(): void
    {
        Response::success('Vendors fetched successfully.', $this->vendorModel->all());
    }

    public function show(array $params): void
    {
        $vendor = $this->vendorModel->findById((int)$params['id']);
        if (!$vendor) {
            Response::error('Vendor not found.', 404);
        }

        Response::success('Vendor fetched successfully.', $vendor);
    }

    public function store(Request $request): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $data = $request->body;

        if ((int)$auth['vendor_id'] !== (int)($data['id'] ?? $auth['vendor_id'])) {
            Response::error('You cannot create another vendor profile through this endpoint.', 403);
        }

        Response::error('Use /register to create vendor accounts.', 400);
    }

    public function update(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $vendorId = (int)$params['id'];

        if ((int)$auth['vendor_id'] !== $vendorId) {
            Response::error('Forbidden.', 403);
        }

        $current = $this->vendorModel->findById($vendorId);
        if (!$current) {
            Response::error('Vendor not found.', 404);
        }

        $data = array_merge($current, $request->body);
        $updated = $this->vendorModel->update($vendorId, $data);

        if (!$updated) {
            Response::error('Vendor update failed.', 500);
        }

        Response::success('Vendor updated successfully.', $this->vendorModel->findById($vendorId));
    }

    public function delete(Request $request, array $params): void
    {
        $auth = AuthMiddleware::authenticate($request, $this->config);
        $vendorId = (int)$params['id'];

        if ((int)$auth['vendor_id'] !== $vendorId) {
            Response::error('Forbidden.', 403);
        }

        if (!$this->vendorModel->delete($vendorId)) {
            Response::error('Vendor delete failed.', 500);
        }

        Response::success('Vendor deleted successfully.');
    }
}
