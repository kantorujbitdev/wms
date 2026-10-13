<!DOCTYPE html>
<html lang="id">
<?php
$config = get_app_config();

// Helper format angka: 5740.00 -> "5.740", 12.50 -> "12,5", ".00" -> "0"
$fmt_qty = function ($v) {
    $s = number_format((float) $v, 2, ',', '.');
    return rtrim(rtrim($s, '0'), ',');
};

// Summary (dihitung controller dari seluruh data proyek)
$total_produk = $summary['total'];
$total_tersedia = $summary['tersedia'];
$total_habis = $summary['habis'];
?>

<head>
    <meta charset="UTF-8">
    <style>
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

        /* Header */
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

        /* Info Gudang */
        .warehouse-info {
            width: 100%;
            background-color: #eaf2fb;
            border: 1px solid #a9cce3;
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

        /* Summary */
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

        .card-orange {
            background: #fdebd0;
            border-color: #e67e22;
            color: #784212;
        }

        .card-red {
            background: #fadbd8;
            border-color: #e74c3c;
            color: #922b21;
        }

        /* Tabel */
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

        .text-used {
            color: #922b21;
            font-weight: bold;
        }

        .text-stock {
            color: #1d6a39;
            font-weight: bold;
        }

        .text-minus {
            color: #922b21;
            font-weight: bold;
        }

        .text-zero {
            color: #aaa;
        }

        .status-cell {
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            color: #ffffff;
        }

        .status-tersedia {
            background-color: #27ae60;
        }

        .status-habis {
            background-color: #f39c12;
        }

        .status-minus {
            background-color: #e74c3c;
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

    <!-- HEADER DOKUMEN -->
    <div class="doc-header">
        <table>
            <tr>
                <td style="width:60%;">
                    <div class="company-name">Laporan Penggunaan Material</div>
                    <div style="font-size:8.5pt; color:#555; margin-top:2px;">
                        <?= $config['app_fullname'] ?> - <?= $config['app_name'] ?>
                    </div>
                </td>
                <td style="width:40%; text-align:right;">
                    <div class="doc-title">PENGGUNAAN MATERIAL</div>
                    <div class="doc-subtitle">
                        Status: <strong><?= htmlspecialchars($status_label) ?></strong>
                    </div>
                    <div class="doc-subtitle">
                        Dicetak: <?= date('d/m/Y H:i') ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFO GUDANG / PROYEK -->
    <?php if (!empty($warehouse_info)): ?>
        <div class="warehouse-info">
            <table>
                <tr>
                    <td colspan="4" style="padding-bottom:4px;">
                        <span class="wh-name"><?= htmlspecialchars($warehouse_info['warehouse_name']) ?></span>
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

    <!-- SUMMARY -->
    <div class="summary-section">
        <table>
            <tr>
                <td style="width:33%;" class="summary-card card-blue">
                    <div class="s-label">Jenis Barang: <?= number_format($total_produk) ?></div>
                </td>
                <td style="width:33%;" class="summary-card card-green">
                    <div class="s-label">Stok Tersedia: <?= number_format($total_tersedia) ?></div>
                </td>
                <td style="width:33%;" class="summary-card card-orange">
                    <div class="s-label">Stok Habis: <?= number_format($total_habis) ?></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TABEL DATA -->
    <div class="section-title">Daftar Penggunaan Material</div>

    <?php if (empty($penggunaan_material_list)): ?>
        <div class="empty-state">Tidak ada data penggunaan material pada proyek ini.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th color="white" style="width:30px;">No</th>
                    <th color="white" style="width:65px;">Kode</th>
                    <th color="white">Nama Barang</th>
                    <th color="white" style="width:45px;">Satuan</th>
                    <th color="white" style="width:60px;">Total<br>Masuk</th>
                    <th color="white" style="width:60px;">Total<br>Terpakai</th>
                    <th color="white" style="width:60px;">Stok<br>Saat Ini</th>
                    <th color="white" style="width:55px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($penggunaan_material_list as $row): ?>
                    <?php
                    $stok = (float) $row['current_stock'];
                    $used = (float) $row['total_used_qty'];
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><strong><?= htmlspecialchars($row['product_code']) ?></strong></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['unit']) ?></td>

                        <td class="text-right"><?= $fmt_qty($row['total_qty_in']) ?></td>

                        <td class="text-right">
                            <?php if ($used > 0): ?>
                                <span class="text-used"><?= $fmt_qty($used) ?></span>
                            <?php else: ?>
                                <span class="text-zero">0</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-right">
                            <?php if ($stok < 0): ?>
                                <span class="text-minus"><?= $fmt_qty($stok) ?></span>
                            <?php elseif ($stok == 0): ?>
                                <span class="text-zero">0</span>
                            <?php else: ?>
                                <span class="text-stock"><?= $fmt_qty($stok) ?></span>
                            <?php endif; ?>
                        </td>

                        <?php if ($stok <= 0): ?>
                            <td class="status-cell status-habis">Habis</td>
                        <?php else: ?>
                            <td class="status-cell status-tersedia">Tersedia</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Footer (Dicetak oleh & nomor halaman) ditangani lewat $mpdf->SetHTMLFooter() di controller -->

</body>

</html>