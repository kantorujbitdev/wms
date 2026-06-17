<!-- C:\xampp\htdocs\wms\application\views\pages\pengiriman\cetak_surat_terima.php -->
<!DOCTYPE html>
<html lang="id">
<?php
$config = get_app_config();
$isCetak = (int) ($pengiriman['header']['is_cetak'] ?? 0);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $jenis_surat ?>
    </title>
    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style></style>
    <?php $this->load->view('style/cetak_style'); ?>

    </style>
</head>

<body>
    <?php $this->load->view('style/list_logo'); ?>
    <form id="formPrint" action="<?= site_url('pengiriman/print_pengiriman') ?>" method="post" style="display:none">
        <input type="hidden" name="stockout_id" value="<?= $pengiriman['header']['stockout_id'] ?>">
        <input type="hidden" id="logo_id" name="logo_id" value="<?= $logo['id_logo'] ?>">
    </form>

    <?php
    // Hitung total halaman dengan optimasi tinggi
    $max_items_per_page = 20; // Maksimal 20 item per halaman untuk A4
    $all_items = $pengiriman['detail'];
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
                            <?php if (!empty($logo['logo'])): ?>
                                <img id="preview-logo" src="<?= base_url($logo['logo']) ?>" alt="Logo Perusahaan">
                            <?php endif; ?>
                            <?php if ($isCetak < 1): ?>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="showLogoModal()"
                                    title="Edit Logo">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>

                        </div>

                        <div class="info-perusahaan">
                            <h1 id="preview-nama-pt"> <?= strtoupper($logo['nama_pt'] ?? 'PT. USAHA JAYAMAS BHAKTI') ?> </h1>
                            <p> <?= $jenis_surat ?></p>
                        </div>
                        <div class="stamp-original">
                            <strong>ORIGINAL</strong>
                        </div>
                    </div>
                </div>

                <!-- Informasi pengiriman (hanya di halaman pertama) -->
                <div class="info-grid">
                    <div class="info-box">
                        <h4>INFORMASI PENGIRIM</h4>
                        <div class="info-row">
                            <div class="info-label">No. Surat:</div>
                            <div class="info-value">
                                <?= $pengiriman['header']['stockout_code'] ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal:</div>
                            <div class="info-value">
                                <?= tanggal_indo_singkat($pengiriman['header']['stockout_date']) ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Dari:</div>
                            <div class="info-value">
                                <?= $pengiriman['header']['warehouse_name'] ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-box">
                        <h4>INFORMASI TUJUAN</h4>
                        <div class="info-row">
                            <div class="info-label">Kepada Yth:</div>
                            <div class="info-value">
                                <?= $pengiriman['header']['to_name'] ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Jenis:</div>
                            <div class="info-value">
                                <?= $tipe_pengiriman ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Keterangan:</div>
                            <div class="info-value">
                                <?= $pengiriman['header']['stockout_note'] ?: 'Tidak ada keterangan' ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Dibuat Oleh:</div>
                            <div class="info-value">
                                <?= $pengiriman['header']['user_name'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Konten Utama -->
            <div class="content-wrapper">

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
                                    <td class="text-center"> <?= $start_number + $index ?> </td>
                                    <td> <?= $item['product_code'] ?> </td>
                                    <td> <?= $item['product_name'] ?> </td>
                                    <td class="text-center">
                                        <?php
                                        $qty = $item['qty'];
                                        echo viewNumber($qty);
                                        $total_qty_page += $qty;
                                        ?>
                                    </td>
                                    <td class="text-center"> <?= $item['unit_code'] ?> </td>
                                    <td> <?= $item['detail_note'] ?> </td>
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
                                foreach ($pengiriman['detail'] as $item) {
                                    $total_qty_all += $item['qty'];
                                }
                                ?>
                                <tr class="total-row">
                                    <td colspan="3" class="text-center">TOTAL</td>
                                    <td class="text-center">
                                        <?= viewNumber($total_qty_all) ?>
                                    </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            <?php else: ?>
                                <!-- Baris Sub Total untuk halaman non-terakhir -->
                                <tr class="subtotal-row">
                                    <td colspan="3" class="text-center">SUB TOTAL HALAMAN
                                        <?= $current_page ?>
                                    </td>
                                    <td class="text-right">
                                        <?= viewNumber($total_qty_page) ?>
                                    </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tanda Tangan (hanya di halaman terakhir) -->
                <?php if ($is_last_page): ?>
                    <div class="signature-section">
                        <div class="signature-grid-pengiriman">
                            <div class="signature-box">
                                <div class="signature-title">Dibuat oleh</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                            </div>
                            <div class="signature-box">
                                <div class="signature-title">Pengirim</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                            </div>

                            <div class="signature-box">
                                <div class="signature-title">Dikeluarkan oleh</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                            </div>

                            <div class="signature-box">
                                <div class="signature-title">Penerima</div>
                                <div class="signature-line"></div>
                                <div class="signature-name">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Footer -->
                <div class="footer">
                    <p>Tanggal cetak:
                        <?= date('d/m/Y') ?>
                    </p>
                </div>
            </div>

            <!-- Nomor Halaman -->
            <div class="page-number">
                Halaman
                <?= $current_page ?> dari
                <?= $total_pages ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Tombol Aksi (Screen Only) -->
    <?php if ($isCetak < 3): ?>
        <div class="action-buttons no-print">
            <button type="button" onclick="konfirmasiCetak()" class="btn-print">
                <span style="font-size:20px;">🖨️</span> CETAK SURAT JALAN

            </button>
        </div>
    <?php endif; ?>

    <!-- Dynamic Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel"> Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <p id="confirmationMessage"> Apakah Anda yakin ingin melanjutkan? </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmButton">Ya </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi print dengan pengaturan optimal
        function printDocument() {
            const printStyle = document.createElement('style');
            printStyle.id = 'dynamic-print-style';
            printStyle.textContent = `
            @media print {
                @page {
                    size: Letter portrait;
                    margin: 5mm;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: white !important;
                }

                .page {
                    width: 216mm !important;
                    min-height: auto !important;

                    margin: 0 !important;
                    padding: 5mm !important;

                    page-break-after: always;

                    box-shadow: none !important;
                    border: none !important;

                    display: block !important;
                }

                .detail-table {
                    page-break-inside: auto !important;
                }

                .detail-table tr {
                    page-break-inside: avoid !important;
                    page-break-after: auto !important;
                }

                .signature-section {
                    page-break-inside: avoid !important;
                }

                .action-buttons,
                #confirmationModal,
                #modalPilihLogo {
                    display: none !important;
                }
            }
            `;
            document.head.appendChild(printStyle);
            window.print();
            window.onafterprint = function () {
                const style = document.getElementById('dynamic-print-style');
                if (style) {
                    style.remove();
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('autoPrint') === '1') {
                sessionStorage.removeItem('autoPrint');
                setTimeout(function () {
                    printDocument();
                }, 500);
            }
        });

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

    </script>

    <script>
        function showLogoModal() {
            document.getElementById('modalPilihLogo').style.display = 'block';
        }

        function hideLogoModal() {
            document.getElementById('modalPilihLogo').style.display = 'none';
        }

        window.addEventListener('click', function (event) {
            const modal = document.getElementById('modalPilihLogo');
            if (event.target === modal) {
                hideLogoModal();
            }

        });

        function pilihLogo(urlLogo, namaPT, logoId) {
            const logo = document.getElementById('preview-logo');
            if (logo) {
                logo.src = urlLogo;
            }
            const nama = document.getElementById('preview-nama-pt');
            if (nama) {
                nama.innerText = namaPT;
            }
            document.getElementById('logo_id').value = logoId;
            hideLogoModal();
        }

        function showConfirmationModal(options) {
            $('#confirmationModalLabel').text(options.title || 'Konfirmasi');
            $('#confirmationMessage').html(options.message || '');

            const confirmButton = $('#confirmButton');
            confirmButton.text(options.confirmText || 'Ya')
                .removeClass('btn-primary btn-danger btn-success btn-warning')
                .addClass(options.confirmClass || 'btn-danger');

            confirmButton.off('click');
            confirmButton.on('click', function () {
                // pindahkan fokus keluar modal
                document.body.focus();

                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal'));

                modal.hide();
                if (typeof options.onConfirm === 'function') {
                    setTimeout(function () {
                        options.onConfirm();
                    }, 200);
                }
            });

            bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmationModal')).show();
        }

        function konfirmasiCetak() {
            const btnPrint = $('.action-buttons .btn-print');

            showConfirmationModal({
                title: 'Konfirmasi Cetak',
                message: `Apakah Anda yakin ingin mencetak surat ini?<br><br>
                <h5><b>Catatan:</h5><h6>- Jika surat sudah dicetak, Logo dan Nama PT tidak dapat diubah lagi.<br>- Maximum Cetak: 2 kali.<b></h6>`,
                confirmText: 'Ya, Cetak',
                confirmClass: 'btn-primary',
                onConfirm: function () {
                    btnPrint.prop('disabled', true);
                    $.ajax({
                        url: $('#formPrint').attr('action'),
                        type: 'POST',
                        data: $('#formPrint').serialize(),
                        dataType: 'json',

                        success: function (result) {
                            btnPrint.prop('disabled', false);
                            if (result.success) {
                                // tandai bahwa setelah reload harus auto print
                                sessionStorage.setItem('autoPrint', '1');

                                // reload halaman agar data terbaru diambil lagi
                                location.reload();
                            } else {
                                alert(result.message || 'Gagal memproses cetak.');
                            }
                        },
                        error: function () {
                            btnPrint.prop('disabled', false);
                            alert('Terjadi kesalahan saat memproses cetak.');
                        }
                    });
                }
            });


        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>