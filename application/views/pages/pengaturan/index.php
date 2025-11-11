<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengaturan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>No</th>
                            <th>Nama Pengaturan</th>
                            <th>Value</th>
                            <th>Is Image</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($pengaturan as $row): ?>
                            <tr class="<?= $row['is_image'] == 'true' ? 'table-image-row' : ''; ?>">
                                <td class="text-center"><?= $no++; ?></td>
                                <td>
                                    <?= $row['nama_pengaturan']; ?>
                                    <?php if ($row['is_image'] == 'true'): ?>
                                        <i class="fas fa-image text-primary ms-1" title="This setting contains an image"></i>
                                    <?php endif; ?>
                                </td>
                                <td <?php if ($row['is_image'] === 'true'): ?> style="background-color: #f0f8ff;" <?php endif; ?>>
                                    <?php if ($row['is_image'] === 'true'): ?>
                                        <?php
                                        // Pastikan path valid dan absolute (dari base_url)
                                        $image_path = base_url($row['value']);
                                        $local_path = FCPATH . ltrim($row['value'], '/'); // untuk validasi file lokal
                                
                                        if (file_exists($local_path)):
                                            ?>
                                            <img src="<?= $image_path; ?>"
                                                alt="<?= htmlspecialchars($row['nama_pengaturan'], ENT_QUOTES); ?>"
                                                class="img-thumbnail" style="max-height: 50px;"
                                                onerror="this.onerror=null; this.src='<?= base_url('assets/temp/img/no-image.png'); ?>';">
                                        <?php else: ?>
                                            <span class="text-danger">Invalid image path</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($row['value'], ENT_QUOTES); ?>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php
                                    switch ($row['is_image']) {
                                        case 'false':
                                            $status_class = 'badge bg-secondary';
                                            break;
                                        case 'true':
                                            $status_class = 'badge bg-info';
                                            break;
                                        default:
                                            $status_class = 'badge bg-light';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $status_class; ?>"><?= strtoupper($row['is_image']); ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('pengaturan/edit/' . $row['id_pengaturan']); ?>"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>