<?php

declare(strict_types=1);

class Product
{
    public function __construct(private PDO $db)
    {
    }

    public function create(array $data, int $vendorId): int
    {
        $sql = 'INSERT INTO products (vendor_id, name, category, price, stock, sustainability_rating, description)
                VALUES (:vendor_id, :name, :category, :price, :stock, :sustainability_rating, :description)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'vendor_id' => $vendorId,
            'name' => $data['name'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'sustainability_rating' => $data['sustainability_rating'] ?? 0,
            'description' => $data['description'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function all(array $filters = []): array
    {
        $sql = 'SELECT p.*, v.business_name, v.location
                FROM products p
                INNER JOIN vendors v ON p.vendor_id = v.id
                WHERE 1=1';

        $params = [];

        if (!empty($filters['category'])) {
            $sql .= ' AND p.category = :category';
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['location'])) {
            $sql .= ' AND v.location LIKE :location';
            $params['location'] = '%' . $filters['location'] . '%';
        }

        if (!empty($filters['min_rating'])) {
            $sql .= ' AND p.sustainability_rating >= :min_rating';
            $params['min_rating'] = $filters['min_rating'];
        }

        if (!empty($filters['search'])) {
            $sql .= ' AND (p.name LIKE :search OR p.description LIKE :search OR v.business_name LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $sql .= ' ORDER BY p.id DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function update(int $id, int $vendorId, array $data): bool
    {
        $sql = 'UPDATE products SET
                    name = :name,
                    category = :category,
                    price = :price,
                    stock = :stock,
                    sustainability_rating = :sustainability_rating,
                    description = :description
                WHERE id = :id AND vendor_id = :vendor_id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'vendor_id' => $vendorId,
            'name' => $data['name'],
            'category' => $data['category'],
            'price' => $data['price'],
            'stock' => $data['stock'] ?? 0,
            'sustainability_rating' => $data['sustainability_rating'] ?? 0,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id, int $vendorId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = :id AND vendor_id = :vendor_id');
        return $stmt->execute([
            'id' => $id,
            'vendor_id' => $vendorId,
        ]);
    }
}
