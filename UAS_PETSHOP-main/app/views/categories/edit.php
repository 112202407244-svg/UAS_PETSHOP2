<div class="page-header">
    <div>
        <h1>Edit Kategori</h1>
        <p>Perbarui nama kategori dan slug yang digunakan pada katalog.</p>
    </div>
    <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=category">Kembali</a>
</div>

<div class="content-card">
    <form action="<?= BASE_URL ?>index.php?url=category/update/<?= (int) $category['id'] ?>" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nama Kategori</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($category['slug']) ?>" readonly>
            </div>
        </div>

        <div class="page-actions">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>index.php?url=category">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
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
