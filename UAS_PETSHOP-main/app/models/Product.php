<?php

class Product extends Model
{
    protected string $table = "products";

    public function getAll(string $extra = ''): array
    {
        $sql = "SELECT
                    p.*,
                    c.name AS category_name
                FROM products p
                JOIN categories c
                    ON p.category_id = c.id
                {$extra}
                ORDER BY p.id DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            JOIN categories c
                ON p.category_id = c.id
            WHERE p.id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getCategories()
    {
        $stmt = $this->db->getConnection()->prepare("
            SELECT *
            FROM categories
            ORDER BY name ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function store($data, $file)
    {
        $image = $this->uploadImage($file);
        if ($image === false) {
            return false;
        }

        $slug = $this->createSlug($data['name']);

        $sql = "INSERT INTO products
                (
                    category_id,
                    name,
                    slug,
                    description,
                    price,
                    stock,
                    weight,
                    image,
                    is_active
                )
                VALUES
                (
                    ?,?,?,?,?,?,?,?,?
                )";

        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $slug,
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['weight'],
            $image,
            (int) ($data['is_active'] ?? 1)
        ]);
    }

    public function updateProduct($id, $data, $file)
    {
        $produk = $this->find($id);
        if (!$produk) {
            return false;
        }

        $gambar = $produk['image'];

        if (($file['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $newImage = $this->uploadImage($file);
            if ($newImage === false) {
                return false;
            }

            $this->deleteImageFile($gambar);
            $gambar = $newImage;
        }

        $slug = $this->createSlug($data['name']);

        $sql = "UPDATE products SET

                category_id=?,
                name=?,
                slug=?,
                description=?,
                price=?,
                stock=?,
                weight=?,
                image=?,
                is_active=?

                WHERE id=?";

        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $slug,
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['weight'],
            $gambar,
            (int) ($data['is_active'] ?? 1),
            $id
        ]);
    }

    public function deleteProduct($id)
    {
        $produk = $this->find($id);
        if (!$produk) {
            return false;
        }

        $this->deleteImageFile($produk['image'] ?? '');

        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM products
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }

    private function uploadImage($file)
    {
        if (!isset($file['image'])) {
            return null;
        }

        if (($file['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return false;
        }

        $nama = $file['image']['name'];
        $tmp = $file['image']['tmp_name'];
        $size = $file['image']['size'];
        $ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));

        if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
            return false;
        }

        if ($size > 2097152) {
            return false;
        }

        if (!is_dir(UPLOAD_PATH) && !mkdir(UPLOAD_PATH, 0775, true) && !is_dir(UPLOAD_PATH)) {
            return false;
        }

        $newName = uniqid('', true) . "." . $ext;

        if (!move_uploaded_file($tmp, UPLOAD_PATH . $newName)) {
            return false;
        }

        return $newName;
    }

    private function createSlug($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-');
    }

    private function deleteImageFile(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $path = UPLOAD_PATH . $filename;
        if (is_file($path)) {
            unlink($path);
        }
    }
}
