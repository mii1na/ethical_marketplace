<?php

declare(strict_types=1);

class AuthMiddleware
{
    public static function authenticate(Request $request, array $config): array
    {
        $authHeader = $request->headers['Authorization'] ?? $request->headers['authorization'] ?? '';

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            Response::error('Missing or invalid authorization token.', 401);
        }

        $token = trim($matches[1]);
        $payload = JwtHandler::decode($token, $config['app']['jwt_secret']);

        if (!$payload || empty($payload['vendor_id'])) {
            Response::error('Unauthorized.', 401);
        }

        return $payload;
    }
}
