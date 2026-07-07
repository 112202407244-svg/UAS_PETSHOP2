<?php

class Order extends Model
{
    public function getAll()
    {
        $sql = "SELECT
                    o.*,
                    u.name AS customer
                FROM orders o
                JOIN users u
                    ON o.user_id = u.id
                ORDER BY o.created_at DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByUser($userId)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT *
            FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT
                o.*,
                u.name,
                u.email
            FROM orders o
            JOIN users u
                ON o.user_id = u.id
            WHERE o.id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getDetails($orderId)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT *
            FROM order_details
            WHERE order_id = ?
        ");

        $stmt->execute([$orderId]);

        return $stmt->fetchAll();
    }

    public function create($data, $cartItems)
    {
        try {

            $this->db->getConnection()->beginTransaction();

            $orderCode = "ORD-" . date('YmdHis');

            $sql = "INSERT INTO orders
            (
                user_id,
                order_code,
                recipient,
                phone,
                province,
                city,
                address,
                postal_code,
                courier,
                courier_service,
                shipping_cost,
                subtotal,
                total,
                note
            )
            VALUES
            (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([

                $data['user_id'],
                $orderCode,
                $data['recipient'],
                $data['phone'],
                $data['province'],
                $data['city'],
                $data['address'],
                $data['postal_code'],
                $data['courier'],
                $data['courier_service'],
                $data['shipping_cost'],
                $data['subtotal'],
                $data['total'],
                $data['note']

            ]);

            $orderId = $this->db->getConnection()->lastInsertId();

            foreach ($cartItems as $item) {

                $detail = $this->db->getConnection()->prepare("
                    INSERT INTO order_details
                    (
                        order_id,
                        product_id,
                        product_name,
                        price,
                        qty,
                        subtotal
                    )
                    VALUES
                    (?,?,?,?,?,?)
                ");

                $detail->execute([

                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['price'],
                    $item['qty'],
                    $item['price'] * $item['qty']

                ]);

                $stock = $this->db->getConnection()->prepare("
                    UPDATE products
                    SET stock = stock - ?
                    WHERE id = ?
                ");

                $stock->execute([
                    $item['qty'],
                    $item['product_id']
                ]);

            }

            $cart = $this->db->getConnection()->prepare("
                DELETE FROM carts
                WHERE user_id = ?
            ");

            $cart->execute([
                $data['user_id']
            ]);

            $this->db->getConnection()->commit();

            return $orderId;

        } catch (Exception $e) {

            if ($this->db->getConnection()->inTransaction()) {
                $this->db->getConnection()->rollBack();
            }

            return false;

        }
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->getConnection()->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $status,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM orders
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function totalOrders()
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT COUNT(*) AS total
            FROM orders
        ");

        $stmt->execute();

        return $stmt->fetch()['total'];
    }

    public function totalRevenue()
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT SUM(total) AS revenue
            FROM orders
            WHERE status != 'cancelled'
        ");

        $stmt->execute();

        return $stmt->fetch()['revenue'];
    }

}
