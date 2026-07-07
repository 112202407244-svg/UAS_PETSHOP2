<?php

class CartController extends Controller
{
    private object $cartModel;
    private object $productModel;
    private object $orderModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
    }

    public function index(): void
    {
        $data = [
            'title' => 'Keranjang Belanja',
            'summary' => $this->cartModel->getSummaryByUser((int) $_SESSION['user_id']),
        ];

        $this->view('layouts/header', $data);
        $this->view('cart/index', $data);
        $this->view('layouts/footer');
    }

    public function add($productId = null): void
    {
        if (!$this->isPost()) {
            $this->redirect('index.php?url=product');
        }

        $userId = (int) $_SESSION['user_id'];
        $productId = (int) ($productId ?? ($_POST['product_id'] ?? 0));
        $qty = max(1, (int) ($_POST['qty'] ?? 1));

        $product = $this->productModel->find($productId);
        if (!$product || (int) $product['is_active'] !== 1) {
            $_SESSION['error'] = 'Produk tidak ditemukan atau tidak aktif.';
            $this->redirectBack('index.php?url=product');
        }

        if ($qty > (int) $product['stock']) {
            $_SESSION['error'] = 'Jumlah melebihi stok produk.';
            $this->redirectBack('index.php?url=product');
        }

        $summary = $this->cartModel->getSummaryByUser($userId);
        $currentQty = 0;

        foreach ($summary['items'] as $item) {
            if ((int) $item['product_id'] === $productId) {
                $currentQty = (int) $item['qty'];
                break;
            }
        }

        if (($currentQty + $qty) > (int) $product['stock']) {
            $_SESSION['error'] = 'Total jumlah produk di keranjang melebihi stok.';
            $this->redirectBack('index.php?url=product');
        }

        if ($this->cartModel->addItem($userId, $productId, $qty)) {
            $_SESSION['success'] = 'Produk berhasil ditambahkan ke keranjang.';
        } else {
            $_SESSION['error'] = 'Produk gagal ditambahkan ke keranjang.';
        }

        $this->redirectBack('index.php?url=cart');
    }

    public function update($productId = null): void
    {
        if (!$this->isPost()) {
            $this->redirect('index.php?url=cart');
        }

        $userId = (int) $_SESSION['user_id'];
        $productId = (int) ($productId ?? ($_POST['product_id'] ?? 0));
        $qty = max(0, (int) ($_POST['qty'] ?? 0));

        $product = $this->productModel->find($productId);
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            $this->redirect('index.php?url=cart');
        }

        if ($qty > (int) $product['stock']) {
            $_SESSION['error'] = 'Jumlah melebihi stok produk.';
            $this->redirect('index.php?url=cart');
        }

        if ($this->cartModel->updateQty($userId, $productId, $qty)) {
            $_SESSION['success'] = $qty > 0
                ? 'Jumlah item berhasil diperbarui.'
                : 'Item berhasil dihapus dari keranjang.';
        } else {
            $_SESSION['error'] = 'Keranjang gagal diperbarui.';
        }

        $this->redirect('index.php?url=cart');
    }

    public function remove($productId = null): void
    {
        $userId = (int) $_SESSION['user_id'];
        $productId = (int) ($productId ?? ($_POST['product_id'] ?? 0));

        if ($productId <= 0) {
            $_SESSION['error'] = 'Item keranjang tidak valid.';
            $this->redirect('index.php?url=cart');
        }

        if ($this->cartModel->removeItem($userId, $productId)) {
            $_SESSION['success'] = 'Item berhasil dihapus dari keranjang.';
        } else {
            $_SESSION['error'] = 'Item gagal dihapus dari keranjang.';
        }

        $this->redirect('index.php?url=cart');
    }

    public function checkout(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $summary = $this->cartModel->getSummaryByUser($userId);

        if (empty($summary['items'])) {
            $_SESSION['error'] = 'Keranjang masih kosong.';
            $this->redirect('index.php?url=cart');
        }

        if ($this->isPost()) {
            $this->processCheckout($summary);
            return;
        }

        $data = [
            'title' => 'Checkout',
            'summary' => $summary,
        ];

        $this->view('layouts/header', $data);
        $this->view('cart/checkout', $data);
        $this->view('layouts/footer');
    }

    private function processCheckout(array $summary): void
    {
        $recipient = trim($_POST['recipient'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $province = trim($_POST['province'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $postalCode = trim($_POST['postal_code'] ?? '');
        $courier = trim($_POST['courier'] ?? '');
        $courierService = trim($_POST['courier_service'] ?? '');
        $shippingCost = max(0, (float) ($_POST['shipping_cost'] ?? 0));
        $note = trim($_POST['note'] ?? '');

        if (
            $recipient === '' ||
            $phone === '' ||
            $province === '' ||
            $city === '' ||
            $address === '' ||
            $courier === '' ||
            $courierService === '' ||
            $shippingCost <= 0
        ) {
            $_SESSION['error'] = 'Lengkapi data checkout dan pilih layanan ongkir.';
            $this->redirect('index.php?url=cart/checkout');
        }

        foreach ($summary['items'] as $item) {
            $product = $this->productModel->find((int) $item['product_id']);

            if (!$product || (int) $product['stock'] < (int) $item['qty']) {
                $_SESSION['error'] = 'Ada produk yang stoknya tidak mencukupi untuk checkout.';
                $this->redirect('index.php?url=cart');
            }
        }

        $orderData = [
            'user_id' => (int) $_SESSION['user_id'],
            'recipient' => $recipient,
            'phone' => $phone,
            'province' => $province,
            'city' => $city,
            'address' => $address,
            'postal_code' => $postalCode,
            'courier' => $courier,
            'courier_service' => $courierService,
            'shipping_cost' => $shippingCost,
            'subtotal' => (float) $summary['subtotal'],
            'total' => (float) $summary['subtotal'] + $shippingCost,
            'note' => $note,
        ];

        $result = $this->orderModel->create($orderData, $summary['items']);

        if ($result) {
            $_SESSION['success'] = 'Checkout berhasil. Pesanan Anda sudah dibuat.';
            $this->redirect('index.php?url=cart');
        }

        $_SESSION['error'] = 'Checkout gagal diproses. Silakan coba lagi.';
        $this->redirect('index.php?url=cart/checkout');
    }

    private function redirectBack(string $fallback): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if ($referer !== '' && strpos($referer, BASE_URL) === 0) {
            header('Location: ' . $referer);
            exit();
        }

        $this->redirect($fallback);
    }
}
