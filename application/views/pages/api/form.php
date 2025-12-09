<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo isset($api) ? 'Edit API' : 'Tambah API'; ?></h1>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form API</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama API</label>
                    <input type="text" class="form-control" name="nama_api" required
                        value="<?= isset($api) ? $api['nama_api'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Endpoint</label>
                    <input type="text" class="form-control" name="endpoint" required
                        value="<?= isset($api) ? $api['endpoint'] : ''; ?>">
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="status_aktif" value="1"
                        <?= isset($api) && $api['status_aktif'] ? 'checked' : ''; ?>>
                    <label class="form-check-label">Aktif</label>
                </div>
                <button class="btn btn-primary" type="submit"><?= $wording['save']; ?></button>
                <a href="<?= site_url('api'); ?>" class="btn btn-secondary"><?= $wording['back']; ?></a>
            </form>
        </div>
    </div>
</div>
