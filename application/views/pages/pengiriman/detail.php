<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penerimaan Barang</h1>
        <a href="<?= site_url('penerimaan') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i>
            Kembali
        </a>
    </div>

    <!-- Detail Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Penerimaan</h6>
        </div>
        <div class="card-body">
            <?php if (isset($penerimaan)): ?>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Penerimaan</th>
                                <td><?= $penerimaan['stockin_code'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td><?= $penerimaan['stockin_date'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>No Invoice</th>
                                <td><?= $penerimaan['stockin_invoice'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td><?= $penerimaan['supplier_name'] ?? '-' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Gudang Tujuan</th>
                                <td><?= $penerimaan['warehouse_name'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Status Asal</th>
                                <td>
                                    <?php
                                    $from_status = $penerimaan['from_status'] ?? '';
                                    echo $from_status == '2' ? 'Supplier' : ($from_status == '1' ? 'Gudang Lain' : '-');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Oleh</th>
                                <td><?= $penerimaan['login_name'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Total Items</th>
                                <td><?= $penerimaan['total_items'] ?? '0' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Items Detail -->
                <hr class="my-4">
                <h5 class="font-weight-bold">Detail Barang</h5>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center align-middle">
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Tipe</th>
                                <th>Qty</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($penerimaan['items'])): ?>
                                <?php $no = 1;
                                foreach ($penerimaan['items'] as $item): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $item['product_code'] ?? '-' ?></td>
                                        <td><?= $item['product_name'] ?? '-' ?></td>
                                        <td><?= $item['type_name'] ?? '-' ?></td>
                                        <td class="text-right"><?= $item['qty'] ?? '0' ?></td>
                                        <td><?= $item['detail_note'] ?? '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data barang</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Data penerimaan tidak ditemukan</div>
            <?php endif; ?>
        </div>
    </div>
</div>