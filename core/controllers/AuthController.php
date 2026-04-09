<?php

declare(strict_types=1);

class AuthController
{
    public function __construct(private Vendor $vendorModel, private array $config)
    {
    }

    public function register(Request $request): void
    {
        $data = $request->body;
        $required = ['business_name', 'owner_name', 'email', 'password'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                Response::error("The {$field} field is required.", 422);
            }
        }

        if ($this->vendorModel->findByEmail($data['email'])) {
            Response::error('Email already exists.', 409);
        }

        $vendorId = $this->vendorModel->create($data);
        $token = JwtHandler::encode(['vendor_id' => $vendorId, 'email' => $data['email']], $this->config['app']['jwt_secret'], $this->config['app']['jwt_expiration_seconds']);

        Response::success('Vendor registered successfully.', [
            'vendor_id' => $vendorId,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): void
    {
        $data = $request->body;

        if (empty($data['email']) || empty($data['password'])) {
            Response::error('Email and password are required.', 422);
        }

        $vendor = $this->vendorModel->findByEmail($data['email']);
        if (!$vendor || !password_verify($data['password'], $vendor['password_hash'])) {
            Response::error('Invalid credentials.', 401);
        }

        $token = JwtHandler::encode(['vendor_id' => $vendor['id'], 'email' => $vendor['email']], $this->config['app']['jwt_secret'], $this->config['app']['jwt_expiration_seconds']);

        Response::success('Login successful.', ['token' => $token]);
    }

    public function me(Request $request): void
    {
        $payload = AuthMiddleware::authenticate($request, $this->config);
        $vendor = $this->vendorModel->findById((int)$payload['vendor_id']);

        if (!$vendor) {
            Response::error('Vendor not found.', 404);
        }

        Response::success('Authenticated vendor fetched successfully.', $vendor);
    }
}
