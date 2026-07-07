<?php
$summary = $summary ?? ['items' => [], 'subtotal' => 0, 'total_items' => 0, 'total_weight' => 0];
$defaultRecipient = $_SESSION['user_name'] ?? '';
?>

<div class="page-header">
    <div>
        <h1>Checkout</h1>
        <p>Lengkapi alamat pengiriman lalu cek ongkir sebelum membuat pesanan.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=cart">Kembali ke Keranjang</a>
</div>

<div class="checkout-grid">
    <div class="content-card">
        <h2 class="section-title">Form Pengiriman</h2>

        <form method="POST" action="<?= BASE_URL ?>index.php?url=cart/checkout" data-checkout-form>
            <div class="form-grid">
                <div class="form-group">
                    <label for="recipient">Nama Penerima</label>
                    <input id="recipient" type="text" name="recipient" value="<?= htmlspecialchars($defaultRecipient) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">No. Telepon</label>
                    <input id="phone" type="text" name="phone" placeholder="08xxxxxxxxxx" required>
                </div>

                <div class="form-group form-group-full">
                    <label for="destination_keyword">Cari Kota/Kecamatan Tujuan</label>
                    <input
                        id="destination_keyword"
                        type="text"
                        placeholder="Contoh: Semarang, Bandung, Surabaya"
                        autocomplete="off"
                        data-destination-search>
                    <div class="search-results" id="destination_results"></div>
                    <input type="hidden" name="destination" id="destination_id">
                </div>

                <div class="form-group">
                    <label for="province">Provinsi</label>
                    <input id="province" type="text" name="province" readonly required>
                </div>

                <div class="form-group">
                    <label for="city">Kota / Kabupaten</label>
                    <input id="city" type="text" name="city" readonly required>
                </div>

                <div class="form-group">
                    <label for="courier">Kurir</label>
                    <select id="courier" name="courier" required>
                        <option value="">Pilih kurir</option>
                        <option value="jne">JNE</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="jnt">J&T</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="postal_code">Kode Pos</label>
                    <input id="postal_code" type="text" name="postal_code" placeholder="Kode pos tujuan">
                </div>

                <div class="form-group form-group-full">
                    <label for="address">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="4" placeholder="Nama jalan, nomor rumah, RT/RW, patokan" required></textarea>
                </div>

                <div class="form-group form-group-full">
                    <label for="note">Catatan</label>
                    <textarea id="note" name="note" rows="3" placeholder="Catatan tambahan untuk pesanan (opsional)"></textarea>
                </div>
            </div>

            <div class="checkout-toolbar">
                <div class="muted-text">
                    Total berat kirim: <strong><?= number_format((int) $summary['total_weight'], 0, ',', '.') ?> gram</strong>
                </div>
                <button
                    type="button"
                    class="btn btn-outline"
                    id="btn-check-ongkir"
                    data-check-ongkir
                    data-weight="<?= (int) $summary['total_weight'] ?>">
                    Cek Ongkir
                </button>
            </div>

            <div class="shipping-panel" id="shipping_panel">
                <div class="muted-text">Pilih kota tujuan dan kurir untuk menampilkan layanan ongkir.</div>
            </div>

            <input type="hidden" name="courier_service" id="courier_service">
            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">

            <div class="checkout-total">
                <div>
                    <span>Subtotal</span>
                    <strong>Rp <?= number_format((float) $summary['subtotal'], 0, ',', '.') ?></strong>
                </div>
                <div>
                    <span>Ongkir</span>
                    <strong id="shipping_cost_text">Rp 0</strong>
                </div>
                <div class="grand-total">
                    <span>Total Bayar</span>
                    <strong id="grand_total_text" data-subtotal="<?= (float) $summary['subtotal'] ?>">
                        Rp <?= number_format((float) $summary['subtotal'], 0, ',', '.') ?>
                    </strong>
                </div>
            </div>

            <div class="page-actions">
                <button type="submit" class="btn btn-primary">Buat Pesanan</button>
            </div>
        </form>
    </div>

    <div class="content-card">
        <h2 class="section-title">Ringkasan Pesanan</h2>
        <div class="checkout-items">
            <?php foreach ($summary['items'] as $item): ?>
                <div class="checkout-item">
                    <div>
                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                        <div class="muted-text"><?= (int) $item['qty'] ?> x Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></div>
                    </div>
                    <strong>Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></strong>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="summary-list">
            <div>
                <span>Total Item</span>
                <strong><?= (int) $summary['total_items'] ?></strong>
            </div>
            <div>
                <span>Total Berat</span>
                <strong><?= number_format((int) $summary['total_weight'], 0, ',', '.') ?> gram</strong>
            </div>
            <div>
                <span>Subtotal</span>
                <strong>Rp <?= number_format((float) $summary['subtotal'], 0, ',', '.') ?></strong>
            </div>
        </div>
    </div>
</div>
