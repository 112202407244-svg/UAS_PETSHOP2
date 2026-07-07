<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-warning">
            <h3 class="mb-0">Edit Produk</h3>
        </div>

        <div class="card-body">

            <form action="<?= BASEURL; ?>/product/update/<?= $product['id']; ?>"
                  method="POST"
                  enctype="multipart/form-data">

                <!-- Nama Produk -->
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>

                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($product['name']); ?>"
                        required>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label class="form-label">Slug</label>

                    <input
                        type="text"
                        class="form-control"
                        id="slug"
                        name="slug"
                        value="<?= htmlspecialchars($product['slug']); ?>"
                        readonly>
                </div>

                <!-- Kategori -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>

                    <select
                        class="form-select"
                        name="category_id"
                        required>

                        <?php foreach($categories as $category): ?>

                            <option
                                value="<?= $category['id']; ?>"
                                <?= ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($category['name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Harga -->
                <div class="mb-3">

                    <label class="form-label">Harga</label>

                    <input
                        type="number"
                        class="form-control"
                        name="price"
                        min="0"
                        value="<?= $product['price']; ?>"
                        required>

                </div>

                <!-- Stock -->
                <div class="mb-3">

                    <label class="form-label">Stock</label>

                    <input
                        type="number"
                        class="form-control"
                        name="stock"
                        min="0"
                        value="<?= $product['stock']; ?>"
                        required>

                </div>

                <!-- Berat -->
                <div class="mb-3">

                    <label class="form-label">

                        Berat (gram)

                    </label>

                    <input
                        type="number"
                        class="form-control"
                        name="weight"
                        value="<?= $product['weight']; ?>"
                        required>

                </div>

                <!-- Status -->
                <div class="mb-3">

                    <label class="form-label">

                        Status Produk

                    </label>

                    <select
                        name="is_active"
                        class="form-select">

                        <option
                            value="1"
                            <?= $product['is_active']==1 ? 'selected' : ''; ?>>

                            Aktif

                        </option>

                        <option
                            value="0"
                            <?= $product['is_active']==0 ? 'selected' : ''; ?>>

                            Tidak Aktif

                        </option>

                    </select>

                </div>

                <!-- Deskripsi -->
                <div class="mb-3">

                    <label class="form-label">

                        Deskripsi

                    </label>

                    <textarea
                        class="form-control"
                        rows="5"
                        name="description"
                        required><?= htmlspecialchars($product['description']); ?></textarea>

                </div>

                <!-- Gambar Lama -->
                <div class="mb-3">

                    <label class="form-label">

                        Gambar Saat Ini

                    </label>

                    <br>

                    <?php if(!empty($product['image'])): ?>

                        <img
                            src="<?= BASEURL; ?>/../storage/produk/<?= $product['image']; ?>"
                            class="img-thumbnail"
                            width="220"
                            id="oldPreview">

                    <?php else: ?>

                        <p class="text-muted">

                            Belum ada gambar.

                        </p>

                    <?php endif; ?>

                </div>

                <!-- Upload Gambar Baru -->
                <div class="mb-3">

                    <label class="form-label">

                        Ganti Gambar (Opsional)

                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted">

                        Kosongkan jika tidak ingin mengganti gambar.

                    </small>

                </div>

                <!-- Preview Gambar Baru -->
                <div class="mb-3">

                    <img
                        id="preview"
                        src=""
                        width="220"
                        class="img-thumbnail d-none">

                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-between">

                    <a
                        href="<?= BASEURL; ?>/product"
                        class="btn btn-secondary">

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Update Produk

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

// Generate slug otomatis

const nama = document.getElementById("name");
const slug = document.getElementById("slug");

nama.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    value = value.replace(/[^a-z0-9]+/g,"-");

    value = value.replace(/^-+|-+$/g,"");

    slug.value = value;

});

// Preview gambar baru

const image = document.getElementById("image");
const preview = document.getElementById("preview");

image.addEventListener("change", function(){

    const file = this.files[0];

    if(file){

        preview.src = URL.createObjectURL(file);

        preview.classList.remove("d-none");

    }

});

</script>

</body>
</html>