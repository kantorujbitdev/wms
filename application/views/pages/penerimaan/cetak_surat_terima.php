<!-- C:\xampp\htdocs\wms\application\views\pages\penerimaan\cetak_surat_terima.php -->
<!DOCTYPE html>
<html lang="id">
<?php
$config = get_app_config();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $jenis_surat ?></title>
    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">
    <style></style>
    <?php include 'cetak_style.php'; ?>
    </style>
</head>

<body>
    <?php
    // Hitung total halaman dengan optimasi tinggi
    $max_items_per_page = 20; // Maksimal 20 item per halaman untuk A4
    $all_items = $penerimaan['detail'];
    $total_items = count($all_items);
    $total_pages = ceil($total_items / $max_items_per_page);

    // Atur items per halaman berdasarkan kondisi
    $items_per_page = $total_pages > 1 ? 18 : $max_items_per_page; // Beri ruang untuk footer
    
    // Split items per halaman
    $pages_items = array_chunk($all_items, $items_per_page);
    $total_pages = count($pages_items); // Recalculate berdasarkan chunk
    ?>

    <?php foreach ($pages_items as $page_num => $page_items): ?>
        <?php
        $current_page = $page_num + 1;
        $is_first_page = ($current_page == 1);
        $is_last_page = ($current_page == $total_pages);
        ?>

        <div class="page" id="page-<?= $current_page ?>">
            <!-- Watermark -->
            <div class="watermark">
                <?php if (!empty($config['app_logo_blue'])): ?>
                    <img src="<?= base_url($config['app_logo_blue']) ?>" alt="Watermark" class="watermark-logo">
                <?php endif; ?>
            </div>

            <!-- Kop Surat (hanya di halaman pertama) -->
            <?php if ($is_first_page): ?>
                <div class="header-surat">
                    <div class="kop-surat">
                        <div class="logo-perusahaan">
                            <?php if (!empty($config['app_logo_blue'])): ?>
                                <img src="<?= base_url($config['app_logo_blue']) ?>" alt="Logo Perusahaan">
                            <?php endif; ?>
                        </div>

                        <div class="info-perusahaan">
                            <h1><?= strtoupper($config['app_pt_name'] ?? 'PT. USAHA JAYAMAS BHAKTI') ?></h1>
                            <h2><?= strtoupper($config['app_fullname'] ?? 'WAREHOUSE MANAGEMENT SYSTEM') ?></h2>
                            <div class="header-line"></div>
                            <p><?= $jenis_surat ?></p>
                        </div>

                        <div class="stamp-original">
                            <strong>ORIGINAL</strong>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penerimaan (hanya di halaman pertama) -->
                <div class="info-grid">
                    <div class="info-box">
                        <h4>INFORMASI PENERIMA</h4>
                        <div class="info-row">
                            <span class="info-label">No. Surat Terima:</span>
                            <span class="info-value"><?= $penerimaan['header']['stockin_code'] ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal:</span>
                            <span
                                class="info-value"><?= date('d F Y', strtotime($penerimaan['header']['stockin_date'])) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gudang Tujuan:</span>
                            <span class="info-value"><?= $penerimaan['header']['warehouse_name'] ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">No. Referensi:</span>
                            <span class="info-value"><?= $penerimaan['header']['stockin_invoice'] ?? '-' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Dibuat Oleh:</span>
                            <span class="info-value"><?= $penerimaan['header']['user_name'] ?? 'System' ?></span>
                        </div>
                    </div>

                    <div class="info-box">
                        <h4>INFORMASI PENGIRIM</h4>
                        <div class="info-row">
                            <span class="info-label">Dari:</span>
                            <span class="info-value"><?= $penerimaan['header']['from_name'] ?? '-' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Jenis Pengirim:</span>
                            <span class="info-value"><?= $tipe_penerimaan ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">No. Surat Jalan:</span>
                            <span class="info-value"><?= $penerimaan['header']['stockin_invoice'] ?? '-' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Keterangan:</span>
                            <span class="info-value"><?= $penerimaan['header']['stockin_note'] ?: '-' ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Alamat:</span>
                            <span class="info-value"><?= $penerimaan['header']['from_address'] ?? '-' ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Konten Utama -->
            <div class="content-wrapper">
                <?php if ($is_first_page): ?>
                    <div class="table-title">DAFTAR BARANG YANG DITERIMA</div>
                <?php endif; ?>

                <div class="table-container">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th class="col-no">NO</th>
                                <th class="col-code">KODE BARANG</th>
                                <th class="col-name">NAMA BARANG</th>
                                <th class="col-qty">QTY</th>
                                <th class="col-unit">SATUAN</th>
                                <th class="col-note">KET</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $start_number = ($current_page - 1) * $items_per_page + 1;
                            $total_qty_page = 0;
                            ?>

                            <?php foreach ($page_items as $index => $item): ?>
                                <tr>
                                    <td class="text-center"><?= $start_number + $index ?></td>
                                    <td><?= $item['product_code'] ?></td>
                                    <td><?= $item['product_name'] ?></td>
                                    <td class="text-right">
                                        <?php
                                        $qty = $item['qty'];
                                        echo viewNumber($qty);
                                        $total_qty_page += $qty;
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $item['unit_code'] ?></td>
                                    <td class="text-center"><?= !empty($item['detail_note']) ? $item['detail_note'] : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- Baris kosong untuk mengisi sisa halaman -->
                            <?php
                            $remaining_rows = $items_per_page - count($page_items);
                            if ($remaining_rows > 0 && !$is_last_page):
                                for ($i = 0; $i < $remaining_rows; $i++):
                                    ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                endfor;
                            endif;
                            ?>

                            <!-- Baris Total -->
                            <?php if ($is_last_page): ?>
                                <?php
                                $total_qty_all = 0;
                                foreach ($penerimaan['detail'] as $item) {
                                    $total_qty_all += $item['qty'];
                                }
                                ?>
                                <tr class="total-row">
                                    <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                                    <td class="text-right"><?= viewNumber($total_qty_all) ?></td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            <?php else: ?>
                                <!-- Baris Sub Total untuk halaman non-terakhir -->
                                <tr class="subtotal-row">
                                    <td colspan="3" class="text-center">SUB TOTAL HALAMAN <?= $current_page ?></td>
                                    <td class="text-right"><?= viewNumber($total_qty_page) ?></td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tanda Tangan (hanya di halaman terakhir) -->
                <?php if ($is_last_page): ?>
                    <div class="signature-section">
                        <div class="signature-grid">
                            <div class="signature-box">
                                <div class="signature-title">Diterima oleh</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                                <!-- <div class="signature-note">Nama & Tanda Tangan</div> -->
                            </div>

                            <div class="signature-box">
                                <div class="signature-title">Diketahui</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                                <!-- <div class="signature-name"><?= $penerimaan['header']['user_name'] ?? 'System' ?></div>
                                <div class="signature-note">Staff Gudang</div> -->
                            </div>

                            <div class="signature-box">
                                <div class="signature-title">Diserahkan oleh</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                                <!-- <div class="signature-note">Kepala Gudang</div> -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Footer -->
                <div class="footer">
                    <p>Dokumen ini dicetak secara elektronik dari <?= $config['app_name'] ?? 'WMS' ?>
                        pada <?= date('d/m/Y H:i:s') ?> | <?= $penerimaan['header']['stockin_code'] ?></p>
                </div>
            </div>

            <!-- Nomor Halaman -->
            <div class="page-number">
                Halaman <?= $current_page ?> dari <?= $total_pages ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Tombol Aksi (Screen Only) -->
    <div class="action-buttons no-print">
        <!-- <button onclick="printDocument()" class="btn-print">
            <span style="font-size:20px;">🖨️</span> CETAK SURAT TERIMA
        </button> -->
        <button onclick="window.print()" class="btn-print">
            <span style="font-size:20px;">🖨️</span> CETAK SURAT TERIMA
        </button>
    </div>

    <script>
        // Fungsi print dengan pengaturan optimal
        function printDocument() {
            // Tambahkan styling sebelum print
            const printStyle = document.createElement('style');
            printStyle.textContent = `
                 @media print {
                    /* Reset semua margin browser */
                    @page {
                        margin: 0mm !important;
                        size: Letter portrait !important;
                        padding: 0 !important;
                    }
                    
                    /* Reset body untuk full page */
                    body {
                        width: 216mm !important;
                        min-height: 279mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        background: white !important;
                    }
                    
                    /* Atur page layout */
                    .page {
                        width: 216mm !important;
                        min-height: 279mm !important;
                        margin: 0 !important;
                        padding: 15mm 20mm !important;
                        page-break-after: always;
                        page-break-inside: avoid;
                        box-shadow: none !important;
                        border: none !important;
                    }
                    
                    /* Pastikan elemen penting tidak terpotong */
                    .signature-section {
                        page-break-inside: avoid !important;
                        page-break-before: avoid !important;
                    }
                    
                    .detail-table {
                        page-break-inside: avoid !important;
                    }
                    
                    .detail-table tr {
                        page-break-inside: avoid !important;
                    }
                    
                    /* Sembunyikan tombol print */
                    .action-buttons {
                        display: none !important;
                    }
                }
            `;
            document.head.appendChild(printStyle);

            window.print();

            // Hapus style setelah print
            setTimeout(() => {
                document.head.removeChild(printStyle);
            }, 1000);
        }

        // Auto print jika diakses dari cetak langsung
        <?php if (isset($auto_print) && $auto_print): ?>
            window.onload = function () {
                setTimeout(function () {
                    printDocument();
                }, 500);
            };

            window.onafterprint = function () {
                setTimeout(function () {
                    window.close();
                }, 300);
            };
        <?php endif; ?>

        // Atur tinggi baris tabel secara dinamis
        document.addEventListener('DOMContentLoaded', function () {
            // Atur ulang nomor halaman
            const pages = document.querySelectorAll('.page');
            const totalPages = pages.length;

            pages.forEach((page, index) => {
                const pageNumElement = page.querySelector('.page-number');
                if (pageNumElement) {
                    const currentPage = index + 1;
                    pageNumElement.textContent = `Halaman ${currentPage} dari ${totalPages}`;
                }

                // Atur tinggi maksimal untuk tabel
                const tableContainer = page.querySelector('.table-container');
                if (tableContainer && !page.querySelector('.signature-section')) {
                    // Untuk halaman non-terakhir, beri tinggi maksimal
                    tableContainer.style.maxHeight = 'calc(297mm - 60mm)';
                }
            });
        });
    </script>
</body>

</html>