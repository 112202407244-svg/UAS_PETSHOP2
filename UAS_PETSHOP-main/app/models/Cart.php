<?php

class Cart extends Model
{
    protected string $table = 'carts';

    public function getItemsByUser(int $userId): array
    {
        $sql = "SELECT
                    c.user_id,
                    c.product_id,
                    c.qty,
                    p.name,
                    p.price,
                    p.stock,
                    p.weight,
                    p.image,
                    p.is_active,
                    (c.qty * p.price) AS subtotal
                FROM carts c
                JOIN products p
                    ON p.id = c.product_id
                WHERE c.user_id = ?
                ORDER BY c.updated_at DESC, c.id DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function getSummaryByUser(int $userId): array
    {
        $items = $this->getItemsByUser($userId);
        $subtotal = 0;
        $totalWeight = 0;
        $totalItems = 0;

        foreach ($items as &$item) {
            $item['qty'] = (int) $item['qty'];
            $item['stock'] = (int) $item['stock'];
            $item['weight'] = (int) $item['weight'];
            $item['price'] = (float) $item['price'];
            $item['subtotal'] = (float) $item['subtotal'];
            $subtotal += $item['subtotal'];
            $totalWeight += $item['weight'] * $item['qty'];
            $totalItems += $item['qty'];
        }

        unset($item);

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_weight' => $totalWeight,
            'total_items' => $totalItems,
        ];
    }

    public function addItem(int $userId, int $productId, int $qty = 1): bool
    {
        $qty = max(1, $qty);
        $pdo = $this->db->getConnection();

        $check = $pdo->prepare("
            SELECT qty
            FROM carts
            WHERE user_id = ? AND product_id = ?
        ");
        $check->execute([$userId, $productId]);
        $existing = $check->fetch();

        if ($existing) {
            $stmt = $pdo->prepare("
                UPDATE carts
                SET qty = qty + ?
                WHERE user_id = ? AND product_id = ?
            ");

            return $stmt->execute([$qty, $userId, $productId]);
        }

        $stmt = $pdo->prepare("
            INSERT INTO carts (user_id, product_id, qty)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $productId, $qty]);
    }

    public function updateQty(int $userId, int $productId, int $qty): bool
    {
        if ($qty <= 0) {
            return $this->removeItem($userId, $productId);
        }

        $stmt = $this->db->getConnection()->prepare("
            UPDATE carts
            SET qty = ?
            WHERE user_id = ? AND product_id = ?
        ");

        return $stmt->execute([$qty, $userId, $productId]);
    }

    public function removeItem(int $userId, int $productId): bool
    {
        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM carts
            WHERE user_id = ? AND product_id = ?
        ");

        return $stmt->execute([$userId, $productId]);
    }

    public function clearByUser(int $userId): bool
    {
        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM carts
            WHERE user_id = ?
        ");

        return $stmt->execute([$userId]);
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT COALESCE(SUM(qty), 0) AS total
            FROM carts
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();

        return (int) ($result['total'] ?? 0);
    }
}
