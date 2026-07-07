<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-info text-white">
            <h3 class="mb-0">Detail Produk</h3>
        </div>

        <div class="card-body">

            <div class="row">

                <!-- Gambar Produk -->
                <div class="col-md-4 text-center">

                    <?php if (!empty($product['image'])) : ?>

                        <img
                            src="<?= BASEURL; ?>/../storage/produk/<?= htmlspecialchars($product['image']); ?>"
                            class="img-fluid rounded border"
                            alt="<?= htmlspecialchars($product['name']); ?>">

                    <?php else : ?>

                        <img
                            src="<?= BASEURL; ?>/assets/images/no-image.png"
                            class="img-fluid rounded border"
                            alt="Tidak Ada Gambar">

                    <?php endif; ?>

                </div>

                <!-- Informasi Produk -->
                <div class="col-md-8">

                    <table class="table table-bordered">

                        <tr>
                            <th width="30%">ID Produk</th>
                            <td><?= $product['id']; ?></td>
                        </tr>

                        <tr>
                            <th>Nama Produk</th>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                        </tr>

                        <tr>
                            <th>Slug</th>
                            <td><?= htmlspecialchars($product['slug']); ?></td>
                        </tr>

                        <tr>
                            <th>Kategori</th>
                            <td><?= htmlspecialchars($product['category_name']); ?></td>
                        </tr>

                        <tr>
                            <th>Harga</th>
                            <td>
                                Rp <?= number_format($product['price'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Stok</th>
                            <td><?= $product['stock']; ?></td>
                        </tr>

                        <tr>
                            <th>Berat</th>
                            <td><?= number_format($product['weight']); ?> gram</td>
                        </tr>

                        <tr>
                            <th>Status</th>

                            <td>

                                <?php if($product['is_active']) : ?>

                                    <span class="badge bg-success">

                                        Aktif

                                    </span>

                                <?php else : ?>

                                    <span class="badge bg-danger">

                                        Tidak Aktif

                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                        <tr>

                            <th>Deskripsi</th>

                            <td>

                                <?= nl2br(htmlspecialchars($product['description'])); ?>

                            </td>

                        </tr>

                        <tr>

                            <th>Dibuat</th>

                            <td>

                                <?= date('d-m-Y H:i', strtotime($product['created_at'])); ?>

                            </td>

                        </tr>

                        <tr>

                            <th>Terakhir Diubah</th>

                            <td>

                                <?= date('d-m-Y H:i', strtotime($product['updated_at'])); ?>

                            </td>

                        </tr>

                    </table>

                    <div class="mt-4">

                        <a
                            href="<?= BASEURL; ?>/product"
                            class="btn btn-secondary">

                            Kembali

                        </a>

                        <a
                            href="<?= BASEURL; ?>/product/edit/<?= $product['id']; ?>"
                            class="btn btn-warning">

                            Edit

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>