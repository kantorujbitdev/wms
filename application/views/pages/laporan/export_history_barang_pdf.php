<!DOCTYPE html>
<html lang="id">

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

        /* Info Barang */
        .product-info {
            width: 100%;
            background-color: #eaf2fb;
            border: 1px solid #a9cce3;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 10px;
        }

        .product-info table {
            width: 100%;
        }

        .product-info .p-name {
            font-size: 11pt;
            font-weight: bold;
            color: #1a5276;
        }

        .product-info td {
            vertical-align: top;
            padding: 1px 4px;
        }

        .product-info .label {
            color: #555;
            width: 110px;
            white-space: nowrap;
        }

        .product-info .value {
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
            font-size: 8pt;
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

        /* Footer */
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

    <!-- Header -->
    <div class="doc-header">
        <table>
            <tr>
                <td style="width:60%;">
                    <div class="company-name">Laporan History Barang</div>
                    <div style="font-size:8.5pt; color:#555; margin-top:2px;">
                        Sistem Manajemen Gudang
                    </div>
                </td>
                <td style="width:40%; text-align:right;">
                    <div class="doc-title">HISTORY BARANG</div>
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

    <!-- Info Barang -->
    <?php if (!empty($product_info)): ?>
        <div class="product-info">
            <table>
                <tr>
                    <td class="label">Kode Barang</td>
                    <td class="value">: <?= htmlspecialchars($product_info['product_code'] ?? '-') ?></td>
                    <td class="label">Satuan</td>
                    <td class="value">: <?= htmlspecialchars($product_info['unit_code'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Barang</td>
                    <td class="value" colspan="3">
                        : <?= htmlspecialchars($product_info['type_name'] ?? '-') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>


    <!-- Tabel Data -->
    <div class="section-title">Daftar Histori Barang</div>

    <?php if (empty($history_barang_list)): ?>
        <div class="empty-state">Tidak ada data transaksi pada periode ini.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th color="white" style="width:25px;">No</th>
                    <th color="white" style="width:65px;">Tanggal</th>
                    <th color="white" style="width:120px;">Nomor Surat</th>
                    <th color="white" style="width:90px;">Gudang Asal</th>
                    <th color="white" style="width:90px;">Gudang Tujuan</th>
                    <th color="white" style="width:70px;">Kode</th>
                    <th color="white">Nama Barang</th>
                    <th color="white" style="width:45px;">Satuan</th>
                    <th color="white" style="width:75px;">Qty</th>
                    <th color="white" style="width:55px;">Jenis</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($history_barang_list as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($row['movement_date'])) ?>
                        </td>
                        <td><?= htmlspecialchars($row['movement_refno']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['from_origin_name'] ?? '-') ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['to_destination_name'] ?? '-') ?></td>
                        <td class="text-center"><strong><?= htmlspecialchars($row['product_code']) ?></strong></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($row['unit_code']) ?></td>
                        <td class="text-center">
                            <?php if ($row['movement_type_name'] === 'MASUK'): ?>
                                <span class="text-masuk">+ <?= number_format($row['qty']) ?></span>
                            <?php else: ?>
                                <span class="text-keluar">- <?= number_format($row['qty']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['movement_type_name'] === 'MASUK'): ?>
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

    <!-- Footer -->
    <div class="doc-footer">
        <table>
            <tr>
                <td>Dokumen ini digenerate secara otomatis oleh sistem.</td>
                <td style="text-align:right;">
                    Halaman <span style="font-weight:bold;">{PAGENO}</span>
                    dari <span style="font-weight:bold;">{nbpg}</span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>