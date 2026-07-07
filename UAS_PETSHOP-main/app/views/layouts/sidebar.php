<?php
$currentUrl = $_GET['url'] ?? 'product';

$menus = [
    [
        'label' => 'Data Produk',
        'url' => 'product',
    ],
    [
        'label' => 'Kategori',
        'url' => 'category',
    ],
    [
        'label' => 'Keranjang',
        'url' => 'cart',
    ],
];
?>

<aside class="sidebar">
    <div class="sidebar-card">
        <div class="sidebar-title">Admin Panel</div>
        <div class="sidebar-subtitle">Kelola data toko, kategori, dan keranjang.</div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menus as $menu): ?>
            <a
                class="sidebar-link <?= strpos($currentUrl, $menu['url']) === 0 ? 'is-active' : '' ?>"
                href="<?= BASE_URL ?>index.php?url=<?= $menu['url'] ?>">
                <?= htmlspecialchars($menu['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
