<?php

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?url=auth/login");
            exit;
        }

        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        $data = [
            'title' => 'Data Produk',
            'products' => $this->productModel->getAll()
        ];

        $this->view('layouts/header', $data);
        $this->view('products/index', $data);
        $this->view('layouts/footer');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'categories' => $this->productModel->getCategories()
        ];

        $this->view('layouts/header', $data);
        $this->view('products/create', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?url=product");
            exit;
        }

        $result = $this->productModel->store($_POST, $_FILES);

        if ($result) {
            $_SESSION['success'] = "Produk berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Produk gagal ditambahkan.";
        }

        header("Location: " . BASE_URL . "index.php?url=product");
        exit;
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Produk',
            'product' => $this->productModel->find($id),
            'categories' => $this->productModel->getCategories()
        ];

        if (!$data['product']) {
            die("Produk tidak ditemukan.");
        }

        $this->view('layouts/header', $data);
        $this->view('products/edit', $data);
        $this->view('layouts/footer');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?url=product");
            exit;
        }

        $result = $this->productModel->updateProduct($id, $_POST, $_FILES);

        if ($result) {
            $_SESSION['success'] = "Produk berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Produk gagal diperbarui.";
        }

        header("Location: " . BASE_URL . "index.php?url=product");
        exit;
    }

    public function delete($id)
    {
        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            $_SESSION['success'] = "Produk berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Produk gagal dihapus.";
        }

        header("Location: " . BASE_URL . "index.php?url=product");
        exit;
    }

    public function show($id)
    {
        $data = [
            'title' => 'Detail Produk',
            'product' => $this->productModel->find($id)
        ];

        if (!$data['product']) {
            die("Produk tidak ditemukan.");
        }

        $this->view('layouts/header', $data);
        $this->view('products/detail', $data);
        $this->view('layouts/footer');
    }
}