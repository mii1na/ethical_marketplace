<?php

declare(strict_types=1);

class Order
{
    private array $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered'];

    public function __construct(private PDO $db)
    {
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO orders (product_id, customer_name, customer_email, quantity, total_amount, status)
                VALUES (:product_id, :customer_name, :customer_email, :quantity, :total_amount, :status)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'product_id' => $data['product_id'],
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'quantity' => $data['quantity'],
            'total_amount' => $data['total_amount'],
            'status' => 'pending',
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function allByVendor(int $vendorId): array
    {
        $sql = 'SELECT o.*, p.name AS product_name, p.vendor_id
                FROM orders o
                INNER JOIN products p ON o.product_id = p.id
                WHERE p.vendor_id = :vendor_id
                ORDER BY o.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['vendor_id' => $vendorId]);
        return $stmt->fetchAll();
    }

    public function findByIdForVendor(int $id, int $vendorId): ?array
    {
        $sql = 'SELECT o.*, p.name AS product_name, p.vendor_id
                FROM orders o
                INNER JOIN products p ON o.product_id = p.id
                WHERE o.id = :id AND p.vendor_id = :vendor_id
                LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'vendor_id' => $vendorId,
        ]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public function updateStatus(int $id, int $vendorId, string $status): bool
    {
        if (!in_array($status, $this->allowedStatuses, true)) {
            return false;
        }

        $sql = 'UPDATE orders o
                INNER JOIN products p ON o.product_id = p.id
                SET o.status = :status
                WHERE o.id = :id AND p.vendor_id = :vendor_id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'id' => $id,
            'vendor_id' => $vendorId,
        ]);
    }
}
