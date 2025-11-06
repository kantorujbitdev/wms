<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Pengaturan</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama Pengaturan</label>
                    <input type="text" class="form-control" name="nama_pengaturan"
                        value="<?= $item['nama_pengaturan']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Value</label>
                    <input type="text" class="form-control" name="value" value="<?= $item['value']; ?>" required>
                </div>
                <button class="btn btn-primary" type="submit">Simpan</button>
                <a href="<?= site_url('pengaturan'); ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>