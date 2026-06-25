<!DOCTYPE html>
<html lang="id">
<?php
$config = get_app_config();
?>

<head>
    <meta charset="UTF-8">
    <style>
        /* ==============================
           Reset & Base
        ============================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #2d2d2d;
        }

        /* ==============================
           Header Perusahaan / Dokumen
        ============================== */
        .doc-header {
            width: 100%;
            border-bottom: 3px solid #1a5276;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .doc-header table {
            width: 100%;
        }

        .doc-header .company-name {
            font-size: 15pt;
            font-weight: bold;
            color: #1a5276;
            line-height: 1.2;
        }

        .doc-header .doc-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1a5276;
            text-align: right;
        }

        .doc-header .doc-subtitle {
            font-size: 9pt;
            color: #555;
            text-align: right;
        }

        /* ==============================
           Info Gudang
        ============================== */
        .warehouse-info {
            width: 100%;
            background-color: #eaf2fb;
            border: 1px solid #a9cce3;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 10px;
        }

        .warehouse-info table {
            width: 100%;
        }

        .warehouse-info .wh-name {
            font-size: 11pt;
            font-weight: bold;
            color: #1a5276;
        }

        .warehouse-info td {
            vertical-align: top;
            padding: 1px 4px;
        }

        .warehouse-info .label {
            color: #555;
            width: 110px;
            white-space: nowrap;
        }

        .warehouse-info .value {
            color: #1a1a1a;
            font-weight: bold;
        }

        .badge-aktif {
            background-color: #27ae60;
            color: #fff;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
        }

        .badge-nonaktif {
            background-color: #e74c3c;
            color: #fff;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
        }

        /* ==============================
           Summary Cards
        ============================== */
        .summary-section {
            width: 100%;
            margin-bottom: 10px;
        }

        .summary-section table {
            width: 100%;
            border-spacing: 4px;
        }

        .summary-card {
            padding: 6px 10px;
            border-radius: 4px;
            text-align: center;
            border: 1px solid;
        }

        .summary-card .s-label {
            font-size: 7.5pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .summary-card .s-value {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 2px;
        }

        .card-blue {
            background: #d6eaf8;
            border-color: #2e86c1;
            color: #1a5276;
        }

        .card-green {
            background: #d5f5e3;
            border-color: #27ae60;
            color: #1d6a39;
        }

        .card-red {
            background: #fadbd8;
            border-color: #e74c3c;
            color: #922b21;
        }

        .card-orange {
            background: #fdebd0;
            border-color: #e67e22;
            color: #784212;
        }

        /* ==============================
           Tabel Data
        ============================== */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1a5276;
            border-bottom: 1.5px solid #1a5276;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        table.data-table thead tr {
            background-color: #1a5276;
            color: #fff;
        }

        table.data-table thead th {
            padding: 5px 6px;
            text-align: center;
            border: 1px solid #1a5276;
            font-weight: bold;
            white-space: nowrap;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #f2f9ff;
        }

        table.data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        table.data-table tbody td {
            padding: 4px 6px;
            border: 1px solid #d0d8e0;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-masuk {
            color: #1d6a39;
            font-weight: bold;
        }

        .text-keluar {
            color: #922b21;
            font-weight: bold;
        }

        .badge-masuk {
            background-color: #27ae60;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            white-space: nowrap;
        }

        .badge-keluar {
            background-color: #e74c3c;
            color: #fff;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            white-space: nowrap;
        }

        /* ==============================
           Footer
        ============================== */
        .doc-footer {
            margin-top: 14px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 7.5pt;
            color: #888;
        }

        .doc-footer table {
            width: 100%;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- ==============================
         HEADER DOKUMEN
    ============================== -->
    <div class="doc-header">
        <table>
            <tr>
                <td style="width:60%;">
                    <div class="company-name">Laporan History Proyek</div>
                    <div style="font-size:8.5pt; color:#555; margin-top:2px;">
                        <?= $config['app_fullname'] ?> - <?= $config['app_name'] ?>
                    </div>
                </td>
                <td style="width:40%; text-align:right;">
                    <div class="doc-title">HISTORY PROYEK</div>
                    <div class="doc-subtitle">
                        Periode:
                        <?= date('d/m/Y', strtotime($filter_date_start)) ?>
                        &ndash;
                        <?= date('d/m/Y', strtotime($filter_date_end)) ?>
                    </div>
                    <div class="doc-subtitle">
                        Dicetak: <?= date('d/m/Y H:i') ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ==============================
         INFO GUDANG
    ============================== -->
    <?php if (!empty($warehouse_info)): ?>
        <div class="warehouse-info">
            <table>
                <tr>
                    <td colspan="4" style="padding-bottom:4px;">
                        <span class="wh-name"><?= htmlspecialchars($warehouse_info['warehouse_name']) ?></span>
                        &nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="label">Kode Gudang</td>
                    <td class="value">: <?= htmlspecialchars($warehouse_info['warehouse_code'] ?? '-') ?></td>
                    <td class="label">Tipe Gudang</td>
                    <td class="value">: <?= htmlspecialchars($warehouse_info['warehouse_type_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Contact Person</td>
                    <td class="value">: <?= htmlspecialchars($warehouse_info['contact_person'] ?? '-') ?></td>
                    <td class="label">Telepon</td>
                    <td class="value">: <?= htmlspecialchars($warehouse_info['phone'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="value" colspan="3">
                        : <?= htmlspecialchars($warehouse_info['warehouse_address'] ?? '-') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <!-- ==============================
         SUMMARY CARDS
    ============================== -->
    <div class="summary-section">
        <table>
            <tr>
                <td style="width:25%; padding:3px;">
                    <div class="summary-card card-blue">
                        <div class="s-label">Total Transaksi: <?= number_format($total_transaksi) ?></div>
                    </div>
                </td>
                <td style="width:25%; padding:3px;">
                    <div class="summary-card card-green">
                        <div class="s-label">Total Barang Masuk: <?= number_format($total_masuk) ?></div>
                    </div>
                </td>
                <td style="width:25%; padding:3px;">
                    <div class="summary-card card-red">
                        <div class="s-label">Total Barang Keluar: <?= number_format($total_keluar) ?></div>
                    </div>
                </td>
                <td style="width:25%; padding:3px;">
                    <div class="summary-card card-orange">
                        <div class="s-label">Jumlah Jenis Barang: <?= number_format($total_produk) ?></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ==============================
         TABEL DATA
    ============================== -->
    <div class="section-title">Daftar Histori Proyek</div>

    <?php if (empty($pengiriman_list)): ?>
        <div class="empty-state">Tidak ada data transaksi pada periode ini.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th color="white" style="width:30px;">No</th>
                    <th color="white" style="width:70px;">Tanggal</th>
                    <th color="white" style="width:130px;">Nomor Surat</th>
                    <th color="white" style="width:70px;">Kode</th>
                    <th color="white">Nama Barang</th>
                    <th color="white" style="width:50px;">Satuan</th>
                    <th color="white" style="width:80px;">Qty Masuk</th>
                    <th color="white" style="width:80px;">Qty Keluar</th>
                    <th color="white" style="width:60px;">Jenis</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($pengiriman_list as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($row['transaction_date'])) ?>
                        </td>
                        <td><?= htmlspecialchars($row['transaction_code']) ?></td>
                        <td class="text-center">
                            <strong><?= htmlspecialchars($row['product_code']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['unit']) ?></td>
                        <td class="text-center">
                            <?php if ($row['transaction_type'] === 'Masuk'): ?>
                                <span class="text-masuk">+ <?= number_format($row['qty']) ?></span>
                            <?php else: ?>
                                <span style="color:#aaa;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['transaction_type'] === 'Keluar'): ?>
                                <span class="text-keluar">- <?= number_format($row['qty']) ?></span>
                            <?php else: ?>
                                <span style="color:#aaa;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['transaction_type'] === 'Masuk'): ?>
                                <span class="badge-masuk">Masuk</span>
                            <?php else: ?>
                                <span class="badge-keluar">Keluar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- ==============================
         FOOTER
    ============================== -->
    <div class="doc-footer">
        <table>
            <tr>
                <td>
                    Dokumen ini digenerate secara otomatis oleh sistem - <?= $config['app_fullname'] ?>.
                </td>
                <td style="text-align:right;">
                    Halaman <span style="font-weight:bold;">{PAGENO}</span>
                    dari <span style="font-weight:bold;">{nbpg}</span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>