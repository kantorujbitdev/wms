<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gudang</h1>
        <a href="<?php echo site_url('gudang/add'); ?>"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Gudang
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Gudang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center align-middle">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kapasitas</th>
                            <th>Manager</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <tr>
                                    <td><?php echo $warehouse['code']; ?></td>
                                    <td><?php echo $warehouse['name']; ?></td>
                                    <td><?php echo $warehouse['address']; ?></td>
                                    <td><?php echo $warehouse['capacity']; ?></td>
                                    <td><?php echo $warehouse['manager']; ?></td>
                                    <td><?php echo $warehouse['phone']; ?></td>
                                    <td>
                                        <a href="<?php echo site_url('gudang/edit/' . $warehouse['id']); ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('gudang/stock/' . $warehouse['id']); ?>"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-btn"
                                            data-id="<?php echo $warehouse['id']; ?>"
                                            data-name="<?php echo $warehouse['name']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No warehouses found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Delete confirmation
        $('.delete-btn').click(function () {
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo site_url('gudang/delete/'); ?>' + id;
                }
            });
        });
    });
</script>