<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Tambah Produk</h3>
        </div>

        <div class="card-body">

            <form action="<?= BASEURL; ?>/product/store"
                  method="POST"
                  enctype="multipart/form-data">

                <!-- Nama Produk -->
                <div class="mb-3">
                    <label class="form-label">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        required>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label class="form-label">
                        Slug
                    </label>

                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        class="form-control"
                        readonly>
                </div>

                <!-- Kategori -->
                <div class="mb-3">

                    <label class="form-label">
                        Kategori
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required>

                        <option value="">-- Pilih Kategori --</option>

                        <?php foreach($categories as $category): ?>

                            <option value="<?= $category['id']; ?>">

                                <?= htmlspecialchars($category['name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Harga -->
                <div class="mb-3">

                    <label class="form-label">
                        Harga
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        required>

                </div>

                <!-- Stock -->
                <div class="mb-3">

                    <label class="form-label">
                        Stock
                    </label>

                    <input
                        type="number"
                        name="stock"
                        class="form-control"
                        min="0"
                        required>

                </div>

                <!-- Berat -->
                <div class="mb-3">

                    <label class="form-label">
                        Berat (gram)
                    </label>

                    <input
                        type="number"
                        name="weight"
                        class="form-control"
                        min="1"
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

                        <option value="1">

                            Aktif

                        </option>

                        <option value="0">

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
                        name="description"
                        rows="5"
                        class="form-control"
                        required></textarea>

                </div>

                <!-- Upload Gambar -->
                <div class="mb-3">

                    <label class="form-label">
                        Gambar Produk
                    </label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                        required>

                    <small class="text-muted">

                        Maksimal 2 MB.
                        Format: JPG, JPEG, PNG, WEBP

                    </small>

                </div>

                <!-- Preview -->
                <div class="mb-3">

                    <img
                        id="preview"
                        src=""
                        width="200"
                        class="img-thumbnail d-none">

                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-between">

                    <a href="<?= BASEURL; ?>/product"
                       class="btn btn-secondary">

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Simpan Produk

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

/* Generate Slug */

const nama = document.getElementById("name");
const slug = document.getElementById("slug");

nama.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    value = value.replace(/[^a-z0-9]+/g,"-");

    value = value.replace(/^-+|-+$/g,"");

    slug.value = value;

});

/* Preview Gambar */

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