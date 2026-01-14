<?php
$back_url = 'penerimaan/dari_supplier';
if ($active_submenu == 'pengguna')
    $back_url = 'penerimaan/dari_pengguna';
elseif ($active_submenu == 'penerimaan_antar_gudang')
    $back_url = 'penerimaan/antar_gudang'; ?>

<div class="container-fluid">

    <!-- Detail Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                    <?= $wording['back']; ?>
                </a>
                <h6 class="m-0 font-weight-bold text-primary">Informasi Penerimaan</h6>
                <div class="btn-group" role="group">
                    <a href="<?= site_url('penerimaan/cetak/' . $penerimaan['header']['stockin_id']) ?>" target="_blank"
                        class="btn btn-primary">
                        <i class="fas fa-eye"></i> Preview Cetak
                    </a>
                    <!-- <a href="<?= site_url('penerimaan/cetak_langsung/' . $penerimaan['header']['stockin_id']) ?>"
                        target="_blank" class="btn btn-success">
                        <i class="fas fa-print"></i> Cetak Langsung
                    </a> -->
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($penerimaan) && isset($penerimaan['header'])): ?>
                <?php
                $header = $penerimaan['header'];
                $detail = $penerimaan['detail'] ?? [];
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Penerimaan</th>
                                <td><?= $header['stockin_code'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td><?= $header['stockin_date'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>No Invoice/Referensi</th>
                                <td><?= $header['stockin_invoice'] ?? '-' ?></td>
                            </tr>
                            <?php if ($header['from_Status'] == '1'): ?>
                                <tr>
                                    <th>Dari Pengguna</th>
                                    <td><?= $header['from_name'] ?? '-' ?></td>
                                </tr>
                            <?php elseif ($header['from_Status'] == '2'): ?>
                                <tr>
                                    <th>Dari Supplier</th>
                                    <td><?= $header['from_name'] ?? '-' ?></td>
                                </tr>
                            <?php elseif ($header['from_Status'] == '3'): ?>
                                <tr>
                                    <th>Dari Gudang</th>
                                    <td><?= $header['from_name'] ?? '-' ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Ke Gudang</th>
                                <td><?= $header['warehouse_name'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Tipe Penerimaan</th>
                                <td>
                                    <?php
                                    $from_status = $header['from_Status'] ?? '';
                                    if ($from_status == '1') {
                                        echo 'Dari Pengguna';
                                    } elseif ($from_status == '2') {
                                        echo 'Dari Supplier';
                                    } elseif ($from_status == '3') {
                                        echo 'Antar Gudang';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td><?= $header['stockin_note'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Dibuat Oleh</th>
                                <td><?= $header['user_name'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Total Items</th>
                                <td><?= count($detail) ?></td>
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
                                <th>Satuan</th>
                                <th>Qty Diterima</th>
                                <th>Stok Saat Ini</th>
                                <th>Keterangan Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($detail)): ?>
                                <?php $no = 1;
                                foreach ($detail as $item): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $item['product_code'] ?? '-' ?></td>
                                        <td><?= $item['product_name'] ?? '-' ?></td>
                                        <td class="text-center"><?= $item['unit_code'] ?? '-' ?></td>
                                        <td class="text-right"><?= viewNumber($item['qty']) ?></td>
                                        <td class="text-right"><?= viewNumber($item['current_stock']) ?></td>
                                        <td><?= $item['detail_note'] ?? '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data barang</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if (!empty($detail)): ?>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td colspan="4" class="text-right">Total:</td>
                                    <td class="text-right">
                                        <?= viewNumber(array_sum(array_column($detail, 'qty'))) ?>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Data penerimaan tidak ditemukan</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk print halaman saat ini
    function printPage() {
        window.print();
    }

    // Event listener untuk auto-hide tombol saat print
    window.addEventListener('beforeprint', function () {
        // Sembunyikan tombol aksi saat print
        document.querySelectorAll('.btn').forEach(function (btn) {
            btn.classList.add('d-print-none');
        });
        // Sembunyikan sidebar jika ada
        var sidebar = document.getElementById('accordionSidebar');
        if (sidebar) sidebar.classList.add('d-print-none');
    });

    // Tambahkan style untuk print di CSS
</script>

<style>
    @media print {
        .d - print - none {
            display: none !important;
        }

        body {
            font - size: 12pt !important;
            color: #000 !important;
            background: #fff !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        .table {
            border - collapse: collapse !important;
        }

        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 5px !important;
        }

        .table thead th {
            background - color: #f2f2f2 !important;
            font-weight: bold !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #000 !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>