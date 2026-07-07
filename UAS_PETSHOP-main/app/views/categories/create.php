<div class="page-header">
    <div>
        <h1>Tambah Kategori</h1>
        <p>Buat kategori baru untuk mengelompokkan produk.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=category">Kembali</a>
</div>

<div class="content-card">
    <form action="<?= BASE_URL ?>index.php?url=category/store" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" name="name" id="name" placeholder="Masukkan nama kategori" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" placeholder="Slug akan dibuat otomatis" readonly>
            </div>
        </div>

        <div class="page-actions">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=category">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
const nama = document.getElementById("name");
const slug = document.getElementById("slug");

nama.addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    value = value.replace(/[^a-z0-9]+/g, "-");
    value = value.replace(/^-+|-+$/g, "");
    slug.value = value;
});
</script>
