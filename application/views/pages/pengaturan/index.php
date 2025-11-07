<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
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
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= $row['nama_pengaturan']; ?></td>
                                <td><?= $row['value']; ?></td>
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