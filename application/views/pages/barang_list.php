<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
                <div class="input-group-append">
                    <button type="button" class="btn btn-default" id="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($items['data']) && is_array($items['data']) && count($items['data']) > 0): ?>
                    <?php foreach ($items['data'] as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['code']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['category_name']; ?></td>
                            <td><?php echo $item['unit']; ?></td>
                            <td><?php echo 'Rp ' . number_format($item['price'], 0, ',', '.'); ?></td>
                            <td><?php echo $item['total_stock'] . ' ' . $item['unit']; ?></td>
                            <td>
                                <?php if ($item['total_stock'] <= $item['min_stock']): ?>
                                    <span class="badge bg-danger">Stok Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo site_url('barang/detail/' . $item['id']); ?>"
                                        class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo site_url('barang/edit/' . $item['id']); ?>"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?php echo $item['id']; ?>" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data barang</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <a href="<?php echo site_url('barang/add'); ?>" class="btn btn-sm btn-info float-left">Tambah Barang</a>
        <?php if (isset($items['pagination'])): ?>
            <ul class="pagination pagination-sm m-0 float-right">
                <?php for ($i = 1; $i <= $items['pagination']['total_pages']; $i++): ?>
                    <li class="page-item <?php echo ($i == $items['pagination']['current_page']) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo site_url('barang?page=' . $i); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<!-- /.card -->

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('barang', array('method' => 'get')); ?>
                <div class="form-group">
                    <label for="search">Kata Kunci</label>
                    <input type="text" class="form-control" id="search" name="search"
                        placeholder="Masukkan kata kunci pencarian">
                </div>
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        <?php if (isset($categories['data']) && is_array($categories['data'])): ?>
                            <?php foreach ($categories['data'] as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="available">Tersedia</option>
                        <option value="low">Stok Menipis</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Search button click
        $('#search-btn').click(function () {
            $('#searchModal').modal('show');
        });

        // Delete button click
        $('.delete-btn').click(function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo site_url('barang/delete/'); ?>' + id;
                }
            });
        });
    });
</script>