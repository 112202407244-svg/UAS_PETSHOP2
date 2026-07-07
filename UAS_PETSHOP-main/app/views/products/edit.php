<div class="page-header">
    <div>
        <h1>Edit Produk</h1>
        <p>Perbarui informasi produk, stok, dan gambar bila diperlukan.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Kembali</a>
</div>

<div class="content-card">
    <form action="<?= BASE_URL ?>index.php?url=product/update/<?= (int) $product['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($product['slug']) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>" <?= (int) $category['id'] === (int) $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" id="price" name="price" min="0" value="<?= (float) $product['price'] ?>" required>
            </div>

            <div class="form-group">
                <label for="stock">Stok</label>
                <input type="number" id="stock" name="stock" min="0" value="<?= (int) $product['stock'] ?>" required>
            </div>

            <div class="form-group">
                <label for="weight">Berat (gram)</label>
                <input type="number" id="weight" name="weight" min="1" value="<?= (int) $product['weight'] ?>" required>
            </div>

            <div class="form-group">
                <label for="is_active">Status Produk</label>
                <select id="is_active" name="is_active">
                    <option value="1" <?= (int) $product['is_active'] === 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= (int) $product['is_active'] === 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Ganti Gambar</label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
                <div class="muted-text">Kosongkan jika tidak ingin mengganti gambar.</div>
            </div>

            <div class="form-group form-group-full">
                <label for="description">Deskripsi</label>
                <textarea id="description" rows="5" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group form-group-full">
                <label>Gambar Saat Ini</label>
                <?php if (!empty($product['image'])): ?>
                    <img id="oldPreview" src="<?= UPLOAD_URL . htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 220px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <?php else: ?>
                    <div class="muted-text">Belum ada gambar.</div>
                <?php endif; ?>
            </div>

            <div class="form-group form-group-full">
                <img id="preview" src="" alt="Preview gambar baru" style="max-width: 220px; display: none; border-radius: 12px; border: 1px solid #e2e8f0;">
            </div>
        </div>

        <div class="page-actions">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Batal</a>
            <button type="submit" class="btn btn-primary">Update Produk</button>
        </div>
    </form>
</div>

<script>
const nama = document.getElementById("name");
const slug = document.getElementById("slug");
const image = document.getElementById("image");
const preview = document.getElementById("preview");

nama.addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    value = value.replace(/[^a-z0-9]+/g, "-");
    value = value.replace(/^-+|-+$/g, "");
    slug.value = value;
});

image.addEventListener("change", function () {
    const file = this.files[0];

    if (!file) {
        preview.style.display = "none";
        preview.removeAttribute("src");
        return;
    }

    preview.src = URL.createObjectURL(file);
    preview.style.display = "block";
});
</script>
