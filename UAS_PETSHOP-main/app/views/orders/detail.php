<?php
$details = $details ?? [];
$isAdmin = $isAdmin ?? false;
$statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
?>

<div class="page-header">
    <div>
        <h1>Detail Pesanan</h1>
        <p>Informasi pengiriman, item checkout, dan status pesanan.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=order">Kembali ke Pesanan</a>
</div>

<div class="checkout-grid">
    <div class="content-card">
        <h2 class="section-title">Informasi Pesanan</h2>
        <div class="summary-list">
            <div>
                <span>Kode Pesanan</span>
                <strong><?= htmlspecialchars($order['order_code']) ?></strong>
            </div>
            <div>
                <span>Pelanggan</span>
                <strong><?= htmlspecialchars($order['name'] ?? '-') ?></strong>
            </div>
            <div>
                <span>Email</span>
                <strong><?= htmlspecialchars($order['email'] ?? '-') ?></strong>
            </div>
            <div>
                <span>Status</span>
                <strong><?= htmlspecialchars(ucfirst($order['status'])) ?></strong>
            </div>
            <div>
                <span>Tanggal</span>
                <strong><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></strong>
            </div>
            <div>
                <span>Penerima</span>
                <strong><?= htmlspecialchars($order['recipient']) ?></strong>
            </div>
            <div>
                <span>Telepon</span>
                <strong><?= htmlspecialchars($order['phone']) ?></strong>
            </div>
            <div>
                <span>Kurir</span>
                <strong><?= htmlspecialchars(strtoupper($order['courier'])) ?> - <?= htmlspecialchars($order['courier_service']) ?></strong>
            </div>
            <div>
                <span>Alamat</span>
                <strong><?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['province']) ?> <?= htmlspecialchars($order['postal_code']) ?></strong>
            </div>
            <?php if (!empty($order['note'])): ?>
                <div>
                    <span>Catatan</span>
                    <strong><?= nl2br(htmlspecialchars($order['note'])) ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($isAdmin): ?>
            <form method="POST" action="<?= BASE_URL ?>index.php?url=order/updatestatus/<?= (int) $order['id'] ?>" style="margin-top: 24px;">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Ubah Status</label>
                        <select name="status" id="status">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status ?>" <?= $status === $order['status'] ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="page-actions">
                    <button type="submit" class="btn btn-primary">Simpan Status</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div class="content-card">
        <h2 class="section-title">Ringkasan Pembayaran</h2>
        <div class="summary-list">
            <div>
                <span>Subtotal</span>
                <strong>Rp <?= number_format((float) $order['subtotal'], 0, ',', '.') ?></strong>
            </div>
            <div>
                <span>Ongkir</span>
                <strong>Rp <?= number_format((float) $order['shipping_cost'], 0, ',', '.') ?></strong>
            </div>
            <div>
                <span>Total</span>
                <strong>Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="content-card table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details)): ?>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td>
                                <div class="product-inline">
                                    <div class="product-thumb">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= UPLOAD_URL . htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                                        <?php else: ?>
                                            <span class="product-thumb-placeholder">No Image</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td>Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></td>
                            <td><?= (int) $item['qty'] ?></td>
                            <td>Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="muted-text">Belum ada detail item untuk pesanan ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
