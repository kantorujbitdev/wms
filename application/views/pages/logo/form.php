<?php
$is_edit = isset($item);
?>
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        <?= $title; ?>
    </h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_pt" class="form-control"
                        value="<?= $is_edit ? $item['nama_pt'] : ''; ?>" required>
                </div>
                <?php if ($is_edit && !empty($item['logo'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Logo Saat Ini</label>
                        <div>
                            <img src="<?= base_url($item['logo']); ?>" class="img-thumbnail" style="max-height:150px;">
                        </div>

                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">
                        <?= $is_edit ? 'Upload Logo Baru' : 'Upload Logo'; ?>
                    </label>
                    <input type="file" name="logo" class="form-control" <?= !$is_edit ? 'required' : ''; ?>>
                    <?php if ($is_edit): ?>
                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti logo.
                        </small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status_aktif" class="form-control">

                        <option value="1" <?= ($is_edit && $item['status_aktif'] == 1) ? 'selected' : ''; ?>> Aktif
                        </option>
                        <option value="0" <?= ($is_edit && $item['status_aktif'] == 0) ? 'selected' : ''; ?>> Nonaktif
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"> Simpan </button>

                <a href="<?= site_url('logo'); ?>" class="btn btn-secondary">Kembali </a>

            </form>

        </div>

    </div>

</div>