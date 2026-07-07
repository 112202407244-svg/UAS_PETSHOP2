<div class="page-header">
    <div>
        <h1>Tambah Produk</h1>
        <p>Lengkapi data produk baru yang akan ditampilkan di toko.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Kembali</a>
</div>

<div class="content-card">
    <form action="<?= BASE_URL ?>index.php?url=product/store" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" readonly>
            </div>

            <div class="form-group">
                <label for="category_id">Kategori</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Harga</label>
                <input type="number" name="price" id="price" min="0" required>
            </div>

            <div class="form-group">
                <label for="stock">Stok</label>
                <input type="number" name="stock" id="stock" min="0" required>
            </div>

            <div class="form-group">
                <label for="weight">Berat (gram)</label>
                <input type="number" name="weight" id="weight" min="1" required>
            </div>

            <div class="form-group">
                <label for="is_active">Status Produk</label>
                <select name="is_active" id="is_active">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Gambar Produk</label>
                <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp" required>
                <div class="muted-text">Maksimal 2 MB. Format: JPG, JPEG, PNG, WEBP.</div>
            </div>

            <div class="form-group form-group-full">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="5" required></textarea>
            </div>

            <div class="form-group form-group-full">
                <img id="preview" src="" alt="Preview gambar produk" style="max-width: 220px; display: none; border-radius: 12px; border: 1px solid #e2e8f0;">
            </div>
        </div>

        <div class="page-actions">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=product">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
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
