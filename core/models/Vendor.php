<?php

declare(strict_types=1);

class Vendor
{
    public function __construct(private PDO $db)
    {
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO vendors (business_name, owner_name, email, password_hash, category, location, sustainability_rating, description)
                VALUES (:business_name, :owner_name, :email, :password_hash, :category, :location, :sustainability_rating, :description)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'business_name' => $data['business_name'],
            'owner_name' => $data['owner_name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'category' => $data['category'] ?? null,
            'location' => $data['location'] ?? null,
            'sustainability_rating' => $data['sustainability_rating'] ?? 0,
            'description' => $data['description'] ?? null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM vendors WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $vendor = $stmt->fetch();

        return $vendor ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, business_name, owner_name, email, category, location, sustainability_rating, description, created_at FROM vendors WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $vendor = $stmt->fetch();

        return $vendor ?: null;
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT id, business_name, owner_name, email, category, location, sustainability_rating, description, created_at FROM vendors ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE vendors SET
                    business_name = :business_name,
                    owner_name = :owner_name,
                    category = :category,
                    location = :location,
                    sustainability_rating = :sustainability_rating,
                    description = :description
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'business_name' => $data['business_name'],
            'owner_name' => $data['owner_name'],
            'category' => $data['category'] ?? null,
            'location' => $data['location'] ?? null,
            'sustainability_rating' => $data['sustainability_rating'] ?? 0,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM vendors WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
