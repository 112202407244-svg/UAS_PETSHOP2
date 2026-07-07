<?php

class OrderDetail extends Model
{
    private $table = "order_details";

    public function getAll()
    {
        $sql = "SELECT
                    od.*,
                    o.order_code,
                    p.name AS product_name
                FROM order_details od
                JOIN orders o
                    ON od.order_id = o.id
                JOIN products p
                    ON od.product_id = p.id
                ORDER BY od.id DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT *
            FROM order_details
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getByOrder($orderId)
    {
        $sql = "SELECT
                    od.*,
                    p.image
                FROM order_details od
                JOIN products p
                    ON od.product_id = p.id
                WHERE od.order_id = ?";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$orderId]);

        return $stmt->fetchAll();
    }

    public function store($data)
    {
        $sql = "INSERT INTO order_details
                (
                    order_id,
                    product_id,
                    product_name,
                    price,
                    qty,
                    subtotal
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?
                )";

        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([
            $data['order_id'],
            $data['product_id'],
            $data['product_name'],
            $data['price'],
            $data['qty'],
            $data['subtotal']
        ]);
    }

    public function deleteByOrder($orderId)
    {
        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM order_details
            WHERE order_id = ?
        ");

        return $stmt->execute([$orderId]);
    }

    public function delete($id)
    {
        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM order_details
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function totalItem($orderId)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT SUM(qty) AS total
            FROM order_details
            WHERE order_id = ?
        ");

        $stmt->execute([$orderId]);

        $result = $stmt->fetch();

        return $result['total'] ?? 0;
    }

    public function totalPrice($orderId)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT SUM(subtotal) AS total
            FROM order_details
            WHERE order_id = ?
        ");

        $stmt->execute([$orderId]);

        $result = $stmt->fetch();

        return $result['total'] ?? 0;
    }
}
