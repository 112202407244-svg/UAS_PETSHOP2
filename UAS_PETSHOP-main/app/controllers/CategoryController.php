<?php

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?url=auth/login");
            exit;
        }

        if ($_SESSION['user_role'] != 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }

        $this->categoryModel = $this->model('Category');
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kategori',
            'categories' => $this->categoryModel->getAll()
        ];

        $this->view('layouts/header', $data);
        $this->view('categories/index', $data);
        $this->view('layouts/footer');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori'
        ];

        $this->view('layouts/header', $data);
        $this->view('categories/create', $data);
        $this->view('layouts/footer');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . BASE_URL . "index.php?url=category");
            exit;
        }

        if ($this->categoryModel->store($_POST)) {

            $_SESSION['success'] = "Kategori berhasil ditambahkan.";

        } else {

            $_SESSION['error'] = "Kategori gagal ditambahkan.";

        }

        header("Location: " . BASE_URL . "index.php?url=category");
        exit;
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            die("Kategori tidak ditemukan.");
        }

        $data = [
            'title' => 'Edit Kategori',
            'category' => $category
        ];

        $this->view('layouts/header', $data);
        $this->view('categories/edit', $data);
        $this->view('layouts/footer');
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: " . BASE_URL . "index.php?url=category");
            exit;
        }

        if ($this->categoryModel->update($id, $_POST)) {

            $_SESSION['success'] = "Kategori berhasil diperbarui.";

        } else {

            $_SESSION['error'] = "Kategori gagal diperbarui.";

        }

        header("Location: " . BASE_URL . "index.php?url=category");
        exit;
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {

            $_SESSION['error'] = "Kategori tidak ditemukan.";

            header("Location: " . BASE_URL . "index.php?url=category");

            exit;
        }

        if ($this->categoryModel->delete($id)) {

            $_SESSION['success'] = "Kategori berhasil dihapus.";

        } else {

            $_SESSION['error'] = "Kategori gagal dihapus.";

        }

        header("Location: " . BASE_URL . "index.php?url=category");
        exit;
    }

}