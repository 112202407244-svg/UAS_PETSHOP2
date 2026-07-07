<?php

class OrderController extends Controller
{
    private object $orderModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->orderModel = $this->model('Order');
    }

    public function index(): void
    {
        $isAdmin = (($_SESSION['user_role'] ?? '') === 'admin');
        $orders = $isAdmin
            ? $this->orderModel->getAll()
            : $this->orderModel->getByUser((int) $_SESSION['user_id']);

        $data = [
            'title' => 'Daftar Pesanan',
            'orders' => $orders,
            'isAdmin' => $isAdmin,
        ];

        $this->view('layouts/header', $data);
        $this->view('orders/index', $data);
        $this->view('layouts/footer');
    }

    public function detail($id): void
    {
        $order = $this->orderModel->find((int) $id);

        if (!$order) {
            $_SESSION['error'] = 'Pesanan tidak ditemukan.';
            $this->redirect('index.php?url=order');
        }

        $isAdmin = (($_SESSION['user_role'] ?? '') === 'admin');
        if (!$isAdmin && (int) $order['user_id'] !== (int) $_SESSION['user_id']) {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke pesanan ini.';
            $this->redirect('index.php?url=order');
        }

        $data = [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'details' => $this->orderModel->getDetails((int) $id),
            'isAdmin' => $isAdmin,
        ];

        $this->view('layouts/header', $data);
        $this->view('orders/detail', $data);
        $this->view('layouts/footer');
    }

    public function updatestatus($id): void
    {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('index.php?url=order/detail/' . (int) $id);
        }

        $allowedStatuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        $status = trim($_POST['status'] ?? '');

        if (!in_array($status, $allowedStatuses, true)) {
            $_SESSION['error'] = 'Status pesanan tidak valid.';
            $this->redirect('index.php?url=order/detail/' . (int) $id);
        }

        if ($this->orderModel->updateStatus((int) $id, $status)) {
            $_SESSION['success'] = 'Status pesanan berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Status pesanan gagal diperbarui.';
        }

        $this->redirect('index.php?url=order/detail/' . (int) $id);
    }
}
