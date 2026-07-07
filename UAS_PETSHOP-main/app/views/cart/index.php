<?php $summary = $summary ?? ['items' => [], 'subtotal' => 0, 'total_items' => 0, 'total_weight' => 0]; ?>

<div class="page-header">
    <div>
        <h1>Keranjang Belanja</h1>
        <p>Kelola item, ubah jumlah, dan lanjutkan ke checkout.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Lanjut Belanja</a>
</div>

<?php if (!empty($summary['items'])): ?>
    <div class="content-card table-card">
        <div class="table-responsive">
            <table class="data-table cart-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="product-inline">
                                    <div class="product-thumb">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <?php else: ?>
                                            <span class="product-thumb-placeholder">No Image</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                                        <div class="muted-text">Stok tersedia: <?= (int) $item['stock'] ?></div>
                                        <div class="muted-text">Berat/item: <?= number_format((int) $item['weight'], 0, ',', '.') ?> gram</div>
                                    </div>
                                </div>
                            </td>
                            <td>Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></td>
                            <td>
                                <form class="qty-form" method="POST" action="<?= BASE_URL ?>index.php?url=cart/update/<?= (int) $item['product_id'] ?>">
                                    <input type="number" name="qty" min="0" max="<?= (int) $item['stock'] ?>" value="<?= (int) $item['qty'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                            <td>Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></td>
                            <td>
                                <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>index.php?url=cart/remove/<?= (int) $item['product_id'] ?>" onclick="return confirm('Hapus item ini dari keranjang?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <span>Total Item</span>
            <strong><?= (int) $summary['total_items'] ?></strong>
        </div>
        <div class="summary-card">
            <span>Total Berat</span>
            <strong><?= number_format((int) $summary['total_weight'], 0, ',', '.') ?> gram</strong>
        </div>
        <div class="summary-card summary-card-accent">
            <span>Subtotal Belanja</span>
            <strong>Rp <?= number_format((float) $summary['subtotal'], 0, ',', '.') ?></strong>
        </div>
    </div>

    <div class="page-actions">
        <a class="btn btn-primary" href="<?= BASE_URL ?>index.php?url=cart/checkout">Lanjut ke Checkout</a>
    </div>
<?php else: ?>
    <div class="content-card empty-state">
        <h3>Keranjang masih kosong</h3>
        <p>Tambahkan produk terlebih dahulu sebelum melakukan checkout.</p>
        <a class="btn btn-primary" href="<?= BASE_URL ?>index.php?url=product">Lihat Produk</a>
    </div>
<?php endif; ?>
