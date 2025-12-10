<!-- C:\xampp\htdocs\wms\application\views\pages\pengiriman\cetak_surat_jalan.php -->
<!DOCTYPE html>
<html lang="id">
<?php
$config = get_app_config();
$items_per_page = 12; // Jumlah item per halaman untuk perhitungan halaman
$total_items = count($pengiriman['detail']);
$total_pages = ceil(($total_items + 10) / $items_per_page); // +10 untuk baris kosong
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $jenis_surat ?></title>
    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Reset dan base style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            background: #fff;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 10mm;
            background: white;
            position: relative;
            page-break-after: always;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
        }

        .watermark-logo {
            width: 400px;
            height: 400px;
            object-fit: contain;
        }

        /* Header Surat */
        .header-surat {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 5px;
            position: relative;
        }

        .kop-surat {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .logo-perusahaan {
            width: 100px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-perusahaan img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .info-perusahaan {
            text-align: center;
            flex-grow: 1;
            padding: 0 10px;
        }

        .info-perusahaan h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-perusahaan h2 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }

        .info-perusahaan h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
            border: 1px solid #000;
            padding: 3px;
            display: inline-block;
        }

        .stamp-original {
            width: 80px;
            text-align: center;
            flex-shrink: 0;
            border: 1px solid #000;
            padding: 5px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Informasi Pengiriman */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 8px;
            border-radius: 3px;
        }

        .info-box h4 {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #000;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
        }

        .info-label {
            width: 100px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .info-value {
            flex-grow: 1;
        }

        /* Tabel Detail Barang */
        .detail-container {
            margin: 15px 0;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: fixed;
        }

        .detail-table th {
            background-color: #2c3e50 !important;
            color: white;
            font-weight: bold;
            padding: 6px 3px;
            text-align: center;
            border: 1px solid #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .detail-table td {
            padding: 4px 3px;
            border: 1px solid #000;
            vertical-align: top;
            word-wrap: break-word;
        }

        .detail-table tr:nth-child(even) {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Bagian Tanda Tangan */
        .tanda-tangan {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #000;
        }

        .ttd-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            text-align: center;
        }

        .ttd-box {
            padding: 5px;
        }

        .ttd-box h4 {
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .ttd-line {
            height: 1px;
            border-top: 1px solid #000;
            margin: 40px auto 5px;
            width: 80%;
        }

        .under-line {
            height: 1px;
            border-top: 1px solid #000;
            margin: 10px auto 5px;
            width: 80%;
        }

        .under-line-all {
            height: 1px;
            border-top: 5px solid #000;
            margin: 1px auto 15px;
            width: 100%;
        }

        .ttd-info {
            font-size: 10px;
            color: #000;
            margin-top: 5px;
        }

        /* Footer */
        .footer-surat {
            position: absolute;
            bottom: 10mm;
            left: 10mm;
            right: 10mm;
            font-size: 9px;
            color: #666;
            text-align: center;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }

        /* Nomor Halaman */
        .page-number {
            position: absolute;
            bottom: 5mm;
            right: 10mm;
            font-size: 9px;
            color: #666;
        }

        /* Tombol Aksi (Screen Only) */
        .action-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .btn-print {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Print Styles */
        @media print {
            body {
                font-size: 10px;
                background: white;
                margin: 0;
                padding: 0;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 15mm 10mm;
                page-break-after: always;
                box-shadow: none;
            }

            .action-buttons,
            .no-print {
                display: none !important;
            }

            /* Pastikan tabel tidak terpotong antar halaman */
            .detail-table {
                page-break-inside: auto;
            }

            .detail-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .detail-table td,
            .detail-table th {
                page-break-inside: avoid;
            }

            /* Force background colors in print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Screen Styles */
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }

            .page {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <?php
    // Hitung total halaman
    $items_per_page = 12;
    $all_items = array_merge(
        $pengiriman['detail'],
        array_fill(0, 10, []) // Tambahkan 10 baris kosong
    );
    $total_items = count($all_items);
    $total_pages = ceil($total_items / $items_per_page);

    // Split items per halaman
    $pages_items = array_chunk($all_items, $items_per_page);
    ?>

    <?php foreach ($pages_items as $page_num => $page_items): ?>
        <?php $current_page = $page_num + 1; ?>
        <div class="page" id="page-<?= $current_page ?>">
            <!-- Watermark -->
            <div class="watermark">
                <?php if (!empty($config['app_logo_blue'])): ?>
                    <img src="<?= base_url($config['app_logo_blue']) ?>" alt="Watermark" class="watermark-logo">
                <?php else: ?>
                    <div style="font-size: 120px; color: #f0f0f0; font-weight: bold;">
                        <?= substr($config['app_pt_name'] ?? 'UJB', 0, 3) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kop Surat (hanya di halaman pertama) -->
            <?php if ($current_page == 1): ?>
                <div class="header-surat">
                    <div class="kop-surat">
                        <div class="logo-perusahaan">
                            <?php if (!empty($config['app_logo_blue'])): ?>
                                <img src="<?= base_url($config['app_logo_blue']) ?>" alt="Logo Perusahaan">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: #f0f0f0; 
                                        display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #666; font-size: 10px;">LOGO</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="info-perusahaan">
                            <h1><?= strtoupper($config['app_pt_name'] ?? 'PT. USAHA JAYAMAS BHAKTI') ?></h1>
                            <h2><?= strtoupper($config['app_fullname'] ?? 'WAREHOUSE MANAGEMENT SYSTEM') ?></h2>
                            <div class="under-line"></div>
                            <p><?= $jenis_surat ?></p>
                        </div>

                        <div class="stamp-original">
                            <strong>ORIGINAL</strong>
                        </div>
                    </div>
                </div>
                <div class="under-line-all"></div>

                <!-- Informasi Pengiriman (hanya di halaman pertama) -->
                <div class="info-grid">
                    <div class="info-box">
                        <h4>INFORMASI PENGIRIMAN</h4>
                        <div class="info-row">
                            <div class="info-label">No. Surat Jalan:</div>
                            <div class="info-value"><?= $pengiriman['header']['stockout_code'] ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal:</div>
                            <div class="info-value"><?= date('d F Y', strtotime($pengiriman['header']['stockout_date'])) ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Dari Gudang:</div>
                            <div class="info-value"><?= $pengiriman['header']['warehouse_name'] ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">No. Referensi:</div>
                            <div class="info-value"><?= $pengiriman['header']['stockout_invoice'] ?? '-' ?></div>
                        </div>
                    </div>

                    <div class="info-box">
                        <h4>INFORMASI TUJUAN</h4>
                        <div class="info-row">
                            <div class="info-label">Kepada Yth:</div>
                            <div class="info-value"><?= $pengiriman['header']['to_name'] ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jenis Tujuan:</div>
                            <div class="info-value"><?= $tipe_pengiriman ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Keterangan:</div>
                            <div class="info-value"><?= $pengiriman['header']['stockout_note'] ?: 'Tidak ada keterangan' ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Dibuat Oleh:</div>
                            <div class="info-value"><?= $pengiriman['header']['createby_name'] ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Detail Barang -->
            <div class="detail-container">
                <?php if ($current_page == 1): ?>
                    <h4 style="text-align: center; margin-bottom: 10px; font-weight: bold; font-size: 12px;">
                        DAFTAR BARANG YANG DIKIRIM
                    </h4>
                <?php endif; ?>

                <table class="detail-table">
                    <?php if ($current_page == 1): ?>
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th width="15%">KODE BARANG</th>
                                <th width="30%">NAMA BARANG</th>
                                <th width="10%">QTY</th>
                                <th width="10%">SATUAN</th>
                                <th width="15%">KETERANGAN</th>
                                <th width="15%">CATATAN PENERIMA</th>
                            </tr>
                        </thead>
                    <?php endif; ?>

                    <tbody>
                        <?php
                        $start_number = ($current_page - 1) * $items_per_page + 1;
                        $total_qty_page = 0;
                        ?>

                        <?php foreach ($page_items as $index => $item): ?>
                            <tr>
                                <td class="text-center"><?= $start_number + $index ?></td>
                                <td><?= isset($item['product_code']) ? $item['product_code'] : '&nbsp;' ?></td>
                                <td><?= isset($item['product_name']) ? $item['product_name'] : '&nbsp;' ?></td>
                                <td class="text-right">
                                    <?php
                                    if (isset($item['qty'])) {
                                        echo number_format($item['qty'], 2);
                                        $total_qty_page += $item['qty'];
                                    } else {
                                        echo '&nbsp;';
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= isset($item['unit_code']) ? $item['unit_code'] : '&nbsp;' ?></td>
                                <td><?= (isset($item['detail_note']) && !empty($item['detail_note'])) ? $item['detail_note'] : '-' ?>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Baris Total (hanya di halaman terakhir) -->
                        <?php if ($current_page == $total_pages): ?>
                            <tr
                                style="font-weight: bold; background-color: #e9ecef !important; -webkit-print-color-adjust: exact;">
                                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                                <td class="text-right">
                                    <?php
                                    $total_qty_all = 0;
                                    foreach ($pengiriman['detail'] as $item) {
                                        $total_qty_all += $item['qty'];
                                    }
                                    echo number_format($total_qty_all, 2);
                                    ?>
                                </td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                        <?php else: ?>
                            <!-- Baris Sub Total untuk halaman non-terakhir -->
                            <tr style="font-weight: bold;">
                                <td colspan="3" class="text-center">SUB TOTAL HALAMAN <?= $current_page ?></td>
                                <td class="text-right"><?= number_format($total_qty_page, 2) ?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tanda Tangan (hanya di halaman terakhir) -->
            <?php if ($current_page == $total_pages): ?>
                <div class="tanda-tangan">
                    <div class="ttd-grid">
                        <!-- Penerima -->
                        <div class="ttd-box">
                            <h4>PENERIMA</h4>
                            <div class="ttd-line"></div>
                            <div class="ttd-info">
                                <p>Nama Penerima</p>
                                <p>Tanggal: _______________</p>
                            </div>
                        </div>

                        <!-- Pengirim -->
                        <div class="ttd-box">
                            <h4>PENGIRIM</h4>
                            <div class="ttd-line"></div>
                            <div class="ttd-info">
                                <p><?= strtoupper($pengiriman['header']['createby_name']) ?></p>
                                <p>Tanggal: <?= date('d F Y') ?></p>
                            </div>
                        </div>

                        <!-- Mengetahui -->
                        <div class="ttd-box">
                            <h4>MENGETAHUI</h4>
                            <div class="ttd-line"></div>
                            <div class="ttd-info">
                                <p>Kepala Gudang</p>
                                <p>Tanggal: _______________</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="footer-surat">
                <p>Dokumen ini dicetak secara elektronik dari <?= $config['app_name'] ?? 'WMS' ?>
                    pada <?= date('d/m/Y H:i:s') ?></p>
                <p><?= $pengiriman['header']['stockout_code'] ?></p>
            </div>

            <!-- Nomor Halaman -->
            <div class="page-number">
                Halaman <?= $current_page ?> dari <?= $total_pages ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Tombol Aksi (Screen Only) -->
    <div class="action-buttons no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> CETAK SURAT JALAN
        </button>
    </div>

    <script>
        // Auto print jika diakses dari cetak langsung
        <?php if (isset($auto_print) && $auto_print): ?>
            window.onload = function () {
                setTimeout(function () {
                    window.print();
                }, 1000);
            };

            window.onafterprint = function () {
                setTimeout(function () {
                    window.close();
                }, 500);
            };
        <?php endif; ?>

        // Perbaikan tampilan saat print
        document.addEventListener('DOMContentLoaded', function () {
            // Set margin halaman untuk print
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    @page {
                        margin: 15mm 10mm;
                        size: A4;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Hitung ulang nomor halaman
            const pages = document.querySelectorAll('.page');
            const totalPages = pages.length;

            pages.forEach((page, index) => {
                const pageNumElement = page.querySelector('.page-number');
                if (pageNumElement) {
                    const currentPage = index + 1;
                    pageNumElement.textContent = `Halaman ${currentPage} dari ${totalPages}`;
                }
            });
        });
    </script>
</body>

</html>