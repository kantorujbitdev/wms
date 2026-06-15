<?php
$back_url = 'pengiriman/penggunaan';
if ($pengiriman['header']['to_status'] == '3')
    $back_url = 'pengiriman/antar_gudang'; ?>

<div class="container-fluid">

    <!-- Data Pengiriman -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mt-2 mb-4">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                    <?= $wording['back']; ?>
                </a>
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pengiriman </h6>
                <!-- TAMBAHKAN TOMBOL CETAK DISINI -->
                <div class="btn-group" role="group">
                    <a href="<?= site_url('pengiriman/cetak/' . $pengiriman['header']['stockout_id']) ?>"
                        target="_blank" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Preview Cetak
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Kode Pengiriman</th>
                            <td><?= $pengiriman['header']['stockout_code'] ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengiriman</th>
                            <td><?= date('d-m-Y', strtotime($pengiriman['header']['stockout_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Dari Gudang</th>
                            <td><?= $pengiriman['header']['warehouse_name'] ?></td>
                        </tr>
                        <!-- <tr>
                            <th>Referensi</th>
                            <td><?= $pengiriman['header']['stockout_invoice'] ?? '-' ?></td>
                        </tr> -->
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Tujuan</th>
                            <td>
                                <?php if ($pengiriman['header']['to_status'] == '1'): ?>
                                    <?= $pengiriman['header']['to_name'] ?>
                                <?php else: ?>
                                    <?= $pengiriman['header']['to_name'] ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tipe Pengiriman</th>
                            <td>
                                <?php if ($pengiriman['header']['to_status'] == '1'): ?>
                                    Ke Pengguna
                                <?php else: ?>
                                    Antar Gudang
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?= $pengiriman['header']['stockout_note'] ?: '-' ?></td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td><?= $pengiriman['header']['user_name'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Barang -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($pengiriman['detail'] as $detail): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $detail['product_code'] ?></td>
                                <td><?= $detail['product_name'] ?></td>
                                <td><?= viewNumber($detail['qty']) ?></td>
                                <td><?= $detail['unit_code'] ?></td>
                                <td><?= $detail['detail_note'] ?: '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script JavaScript di bagian bawah sebelum </body> -->
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