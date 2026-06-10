<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><?= $wording['logo_list']; ?></h6>
                <a href="<?= site_url('logo/add') ?>" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Logo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th width="50">No</th>
                            <th width="150">Logo</th>
                            <th>Nama Perusahaan</th>
                            <th width="120">Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($logo as $row): ?>
                            <tr>
                                <td class="text-center">
                                    <?= $no++; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($row['logo'])): ?>
                                        <img src="<?= base_url($row['logo']); ?>" class="img-thumbnail" style="max-height:60px;"
                                            alt="<?= $row['nama_pt']; ?>">
                                    <?php endif; ?>

                                </td>
                                <td> <?= $row['nama_pt']; ?> </td>
                                <td class="text-center">
                                    <span class="badge bg-<?= $row['status_aktif'] ? 'success' : 'secondary'; ?>">
                                        <?= $row['status_aktif'] ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="<?= site_url('logo/edit/' . $row['id_logo']); ?>" class="btn btn-info btn-sm">
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