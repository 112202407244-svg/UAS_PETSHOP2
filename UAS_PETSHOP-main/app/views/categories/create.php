<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Kategori</h4>
                </div>

                <div class="card-body">

                    <form action="<?= BASE_URL; ?>category/store" method="POST">

                        <div class="mb-3">
                            <label class="form-label">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="name"
                                placeholder="Masukkan nama kategori"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="slug"
                                id="slug"
                                placeholder="Slug akan dibuat otomatis"
                                readonly>
                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="<?= BASE_URL; ?>category"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const nama = document.getElementById('name');
const slug = document.getElementById('slug');

nama.addEventListener('keyup', function(){

    let value = this.value.toLowerCase();

    value = value.replace(/[^a-z0-9]+/g,'-');

    value = value.replace(/^-+|-+$/g,'');

    slug.value = value;

});

</script>

</body>

</html>

