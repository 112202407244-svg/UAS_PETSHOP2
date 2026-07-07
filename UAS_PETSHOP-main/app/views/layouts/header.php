<?php
$appName = defined('APP_NAME') ? APP_NAME : 'Pet Shop';
$title = $title ?? $appName;
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && (($_SESSION['user_role'] ?? '') === 'admin');
$currentUrl = $_GET['url'] ?? 'product';
$cartCount = 0;

if ($isLoggedIn) {
    $cartModelFile = BASE_PATH . '/app/models/Cart.php';

    if (file_exists($cartModelFile)) {
        require_once $cartModelFile;

        if (class_exists('Cart')) {
            try {
                $cartCount = (new Cart())->countByUser((int) $_SESSION['user_id']);
            } catch (Throwable $e) {
                $cartCount = 0;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="<?= $isAdmin ? 'has-admin-sidebar' : '' ?>">
    <script>
        window.APP_BASE_URL = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
    </script>

    <nav class="navbar">
        <div class="navbar-inner">
            <a class="navbar-brand" href="<?= BASE_URL ?>index.php?url=product">
                <span class="brand-mark">PS</span>
                <span><?= htmlspecialchars($appName) ?></span>
            </a>

            <div class="navbar-menu">
                <?php if ($isLoggedIn): ?>
                    <a class="<?= strpos($currentUrl, 'product') === 0 ? 'is-active' : '' ?>" href="<?= BASE_URL ?>index.php?url=product">Produk</a>
                    <?php if ($isAdmin): ?>
                        <a class="<?= strpos($currentUrl, 'category') === 0 ? 'is-active' : '' ?>" href="<?= BASE_URL ?>index.php?url=category">Kategori</a>
                    <?php endif; ?>
                    <a class="<?= strpos($currentUrl, 'cart') === 0 ? 'is-active' : '' ?>" href="<?= BASE_URL ?>index.php?url=cart">
                        Keranjang
                        <?php if ($cartCount > 0): ?>
                            <span class="nav-badge"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                    <span class="navbar-user">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a class="btn-logout" href="<?= BASE_URL ?>index.php?url=auth/logout">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>index.php?url=auth/login">Login</a>
                    <a href="<?= BASE_URL ?>index.php?url=auth/register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="app-shell">
        <?php if ($isAdmin): ?>
            <?php require BASE_PATH . '/app/views/layouts/sidebar.php'; ?>
        <?php endif; ?>

        <main class="content-area">
            <div class="container">
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
