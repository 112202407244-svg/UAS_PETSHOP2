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
            SELECT *
            FROM products
            WHERE id = ?
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

        if($image == false){
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
            1

        ]);

    }

    public function updateProduct($id, $data, $file)
    {

        $produk = $this->find($id);

        if(!$produk){
            return false;
        }

        $gambar = $produk['image'];

        if($file['image']['error']==0){

            if(file_exists("../storage/produk/".$gambar)){

                unlink("../storage/produk/".$gambar);

            }

            $gambar = $this->uploadImage($file);

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
            $data['is_active'],
            $id

        ]);

    }

    public function deleteProduct($id)
    {

        $produk = $this->find($id);

        if(!$produk){

            return false;

        }

        if($produk['image'] != ""){

            $path = "../storage/produk/".$produk['image'];

            if(file_exists($path)){

                unlink($path);

            }

        }

        $stmt = $this->db->getConnection()->prepare("
            DELETE FROM products
            WHERE id=?
        ");

        return $stmt->execute([$id]);

    }

    private function uploadImage($file)
    {

        if($file['image']['error'] != 0){

            return false;

        }

        $nama = $file['image']['name'];
        $tmp = $file['image']['tmp_name'];
        $size = $file['image']['size'];

        $ext = strtolower(pathinfo($nama,PATHINFO_EXTENSION));

        $allow = [

            'jpg',
            'jpeg',
            'png',
            'webp'

        ];

        if(!in_array($ext,$allow)){

            return false;

        }

        if($size > 2097152){

            return false;

        }

        $newName = uniqid().".".$ext;

        move_uploaded_file(

            $tmp,

            "../storage/produk/".$newName

        );

        return $newName;

    }

    private function createSlug($text)
    {

        $text = strtolower($text);

        $text = preg_replace('/[^a-z0-9]+/','-',$text);

        return trim($text,'-');

    }

}