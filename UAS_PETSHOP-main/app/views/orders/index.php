<?php
$orders = $orders ?? [];
$isAdmin = $isAdmin ?? false;
?>

<div class="page-header">
    <div>
        <h1>Daftar Pesanan</h1>
        <p><?= $isAdmin ? 'Pantau seluruh transaksi pelanggan dan status pengirimannya.' : 'Lihat riwayat checkout dan status pesanan Anda.' ?></p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Kembali ke Produk</a>
</div>

<div class="content-card table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php if ($isAdmin): ?>
                        <th>Pelanggan</th>
                    <?php endif; ?>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_code']) ?></td>
                            <?php if ($isAdmin): ?>
                                <td><?= htmlspecialchars($order['customer'] ?? '-') ?></td>
                            <?php endif; ?>
                            <td><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                            <td>Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars(ucfirst($order['status'])) ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>index.php?url=order/detail/<?= (int) $order['id'] ?>">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? '6' : '5' ?>" class="muted-text">Belum ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
