<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $page_title; ?></h1>
        <a href="<?= site_url('api/add'); ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus"></i> Tambah API
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar API</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama API</th>
                        <th>Endpoint</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($apis as $api): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= $api['nama_api']; ?></td>
                            <td><?= $api['endpoint']; ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?= $api['status_aktif'] ? 'success' : 'secondary'; ?>">
                                    <?= $api['status_aktif'] ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('api/edit/' . $api['id_api']); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- <a href="<?= site_url('api/delete/' . $api['id_api']); ?>"
                                    class="btn btn-danger btn-sm delete-btn">
                                    <i class="fas fa-trash"></i>
                                </a> -->

                                <a href="<?= site_url('api/delete/' . $api['id_api']); ?>"
                                    data-id="<?php echo $api['id_api']; ?>" data-name="<?php echo $api['nama_api']; ?>"
                                    class="btn btn-danger btn-sm delete-btn">
                                    <i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Delete confirmation using our dynamic modal
        $('.delete-btn').click(function () {
            var id = $(this).data('id');
            var name = $(this).data('name');

            showConfirmationModal({
                title: 'Konfirmasi Hapus',
                message: `Apakah Anda yakin ingin menghapus user "${name}"?`,
                confirmText: 'Ya, Hapus',
                confirmClass: 'btn-danger',
                confirmUrl: '<?php echo site_url('api/delete/'); ?>' + id
            });
        });
    });
</script>