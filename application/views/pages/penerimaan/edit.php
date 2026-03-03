<style>
    /* Loading Screen */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.3s ease;
    }

    .loader {
        text-align: center;
        max-width: 400px;
        padding: 30px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .spinner {
        width: 60px;
        height: 60px;
        margin: 0 auto 20px;
        border: 5px solid #36b9cc;
        border-top: 5px solid #4e73df;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loader h4 {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .loader p {
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .progress {
        height: 8px;
        margin-top: 20px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #4e73df, #36b9cc);
        animation: progress 2s ease-in-out infinite;
    }

    @keyframes progress {
        0% {
            width: 0%;
        }

        50% {
            width: 70%;
        }

        100% {
            width: 100%;
        }
    }

    /* Fade out animation */
    .fade-out {
        opacity: 0;
        pointer-events: none;
    }
</style>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loader">
        <div class="spinner"></div>
        <h4>Memuat Data Penerimaan</h4>
        <p>Mohon tunggu sebentar...</p>
        <p class="small text-muted" id="loading-status">Mengambil data penerimaan</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar"></div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php
    $back_url = 'penerimaan/dari_pengguna';
    if ($from_status == '2')
        $back_url = 'penerimaan/dari_supplier';
    elseif ($from_status == '3')
        $back_url = 'penerimaan/antar_gudang';
    ?>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm mb-4">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i>
                <?= $wording['back']; ?>
            </a>
            <h6 class="m-0 font-weight-bold text-primary"><?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm"
                action="<?= site_url('penerimaan/update/' . $penerimaan['header']['stockin_id']) ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_date">Tanggal Penerimaan *</label>
                            <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                value="<?= date('Y-m-d', strtotime($penerimaan['header']['stockin_date'])) ?>"
                                max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_code">Kode Penerimaan *</label>
                            <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                value="<?= $penerimaan['header']['stockin_code'] ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_warehouse_id">Ke Gudang *</label>
                            <?php if ($user_role == 'superadmin'): ?>
                                <!-- Superadmin dapat mengubah gudang tujuan -->
                                <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                    <option value="">Pilih Gudang Tujuan</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($penerimaan['header']['warehouse_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Superadmin dapat mengubah gudang tujuan</small>
                            <?php else: ?>
                                <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                <input type="text" class="form-control bg-light"
                                    value="<?= $penerimaan['header']['warehouse_name'] ?>" readonly
                                    style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                                <input type="hidden" id="to_warehouse_id" name="to_warehouse_id"
                                    value="<?= $penerimaan['header']['warehouse_id'] ?>">
                                <small class="form-text text-muted">Gudang tujuan tidak dapat diubah</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_note">Keterangan</label>
                            <textarea class="form-control" id="stockin_note" name="stockin_note"
                                placeholder="Masukkan keterangan tambahan"
                                rows="2"><?= $penerimaan['header']['stockin_note'] ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Penerimaan dari Pengguna (from_status = 1) -->
                <?php if ($from_status == '1'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">Dari Pengguna *</label>
                                <select class="form-control select2" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>"
                                            <?= ($penerimaan['header']['from_id'] == $customer['id']) ? 'selected' : '' ?>>
                                            <?= $customer['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stockin_invoice">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                    placeholder="Masukkan nomor referensi" required>
                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Penerimaan dari Supplier (from_status = 2) -->
                <?php elseif ($from_status == '2'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Dari Supplier *</label>
                                <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['id'] ?>"
                                            <?= ($penerimaan['header']['from_id'] == $supplier['id']) ? 'selected' : '' ?>>
                                            <?= $supplier['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stockin_invoice">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                    placeholder="Masukkan nomor referensi" required>
                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Penerimaan Antar Gudang (from_status = 3) -->
                <?php elseif ($from_status == '3'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_warehouse_id">Dari Gudang *</label>
                                <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id"
                                    required>
                                    <option value="">Pilih Gudang Asal</option>
                                    <?php foreach ($warehouses as $warehouse):
                                        // Jangan tampilkan gudang yang sama dengan tujuan
                                        $disabled = '';
                                        if ($warehouse['warehouse_id'] == $penerimaan['header']['warehouse_id']) {
                                            $disabled = 'disabled';
                                        }
                                        ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($penerimaan['header']['from_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>                                           <?= $disabled ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                            <?= ($disabled) ? ' (Gudang Tujuan)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Pilih gudang asal penerimaan</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stockin_invoice">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    value="<?= $penerimaan['header']['stockin_invoice'] ?>"
                                    placeholder="Masukkan nomor referensi transfer" required>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_status">Tipe Penerimaan</label>
                            <?php
                            $tipe_text = 'Dari Pengguna';
                            if ($from_status == '2')
                                $tipe_text = 'Dari Supplier';
                            elseif ($from_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Tipe penerimaan tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Detail Barang</h5>
                        <small class="text-muted">Klik tombol edit untuk mengubah barang</small>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                <?php if (!empty($penerimaan['detail'])): ?>
                    <?php foreach ($penerimaan['detail'] as $index => $detail): ?>
                        <div class="item-row row mb-3" data-index="<?= $index ?>" data-product-id="<?= $detail['product_id'] ?>">

                            <!-- Tampilan Mode View (Default) -->
                            <div class="view-mode col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Produk</label>
                                            <div class="form-control-plaintext font-weight-bold product-display">
                                                <?= ($detail['product_code'] ?? '') ?> -
                                                <?= ($detail['product_name'] ?? '') ?>
                                                <small class="text-muted d-block unit-display">
                                                    Satuan: <?= ($detail['unit_code'] ?? '') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qty</label>
                                            <div class="form-control-plaintext font-weight-bold qty-display">
                                                <?= $detail['qty'] ?? 0 ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Keterangan Barang</label>
                                            <div class="form-control-plaintext note-display">
                                                <?= $detail['detail_note'] ?? '-' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm edit-item" title="Edit Barang">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm remove-item" title="Hapus Barang">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tampilan Mode Edit (Hidden by default) -->
                            <div class="edit-mode col-12" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Produk *</label>
                                            <select class="form-control product-select-lazy" 
                                                    name="product_id[]" 
                                                    data-index="<?= $index ?>"
                                                    data-row-index="<?= $index ?>"
                                                    data-selected-id="<?= $detail['product_id'] ?>"
                                                    data-selected-text="<?= ($detail['product_code'] ?? '') ?> - <?= ($detail['product_name'] ?? '') ?>"
                                                    style="width: 100%;" required>
                                                <option value="<?= $detail['product_id'] ?>" selected>
                                                    <?= ($detail['product_code'] ?? '') ?> -
                                                    <?= ($detail['product_name'] ?? '') ?>
                                                </option>
                                            </select>
                                            <input type="hidden" name="stock_id[]" value="<?= $detail['product_id'] ?>">
                                            <small class="form-text text-info stock-info" id="stockInfo<?= $index ?>">
                                                Satuan: <?= $detail['unit_code'] ?? '' ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Qty *</label>
                                            <input type="number" class="form-control qty-input" 
                                                name="qty[]" 
                                                data-index="<?= $index ?>"
                                                data-row-index="<?= $index ?>"
                                                min="0.01" step="0.01" 
                                                value="<?= $detail['qty'] ?? 0 ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Keterangan Barang</label>
                                            <input type="text" class="form-control detail-note-input" 
                                                name="detail_note[]" 
                                                data-row-index="<?= $index ?>"
                                                value="<?= $detail['detail_note'] ?? '' ?>"
                                                placeholder="Keterangan tambahan">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label d-block">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-item-edit">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Template untuk item baru -->
            <template id="newItemTemplate">
                <div class="item-row row mb-3" data-index="new">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Produk *</label>
                                    <select class="form-control product-select-lazy" 
                                            name="product_id[]" 
                                            data-index="new"
                                            data-row-index="new"
                                            style="width: 100%;" required>
                                        <option value="">Pilih Produk</option>
                                    </select>
                                    <input type="hidden" name="stock_id[]" value="">
                                    <small class="form-text text-info stock-info"></small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Qty *</label>
                                    <input type="number" class="form-control qty-input" 
                                        name="qty[]" 
                                        data-index="new"
                                        data-row-index="new"
                                        min="0.01" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Keterangan Barang</label>
                                    <input type="text" class="form-control detail-note-input" 
                                        name="detail_note[]" 
                                        data-row-index="new"
                                        placeholder="Keterangan tambahan">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Penerimaan
                        </button>
                        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* OPTIMASI: Virtual scrolling untuk dropdown */
    .select2-container--default .select2-results>.select2-results__options {
        max-height: 300px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* OPTIMASI: Loading more indicator */
    .select2-results__option--loading-more {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        padding: 8px;
    }

    /* Style untuk mode view/edit */
    .form-control-plaintext {
        padding: 0.375rem 0;
        background-color: transparent;
        border: 1px solid transparent;
    }

    .item-row {
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .btn-group {
        display: flex;
        gap: 5px;
    }

    .btn-group .btn {
        flex: 1;
    }

    /* Loading overlay (sama seperti sebelumnya) */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.3s ease;
    }

    .loader {
        text-align: center;
        max-width: 400px;
        padding: 30px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .spinner {
        width: 60px;
        height: 60px;
        margin: 0 auto 20px;
        border: 5px solid #36b9cc;
        border-top: 5px solid #4e73df;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .fade-out {
        opacity: 0;
        pointer-events: none;
    }
</style>

<script>
$(document).ready(function () {
    // Store products data safely (sama seperti di form.php)
    const productsData = <?= $products_json ?: '[]' ?>;
    console.log('Total products loaded:', productsData.length);

    let itemCounter = <?= count($penerimaan['detail']) ?>;

    // Function to escape HTML (sama seperti di form.php)
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
            '`': '&#96;'
        };
        return text.replace(/[&<>"'`]/g, function (m) { return map[m]; });
    }

    // Initialize Select2 for Bootstrap 5 (SAMA PERSIS dengan form.php)
    function initSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Produk',
            allowClear: true,
            dropdownParent: $(element).closest('.modal').length ? $(element).closest('.modal') : $('.card-body')
        });
    }

    // Tampilkan loading
    $('#loading-overlay').show();

    /**
     * Update tampilan view mode dengan data terbaru
     */
    function updateViewMode(row, productId, qty, note) {
        const $viewMode = row.find('.view-mode');
        const $editMode = row.find('.edit-mode');

        // Cari data produk lengkap
        const product = productsData.find(p => p.id == productId) || {};

        // Update display
        $viewMode.find('.product-display').html(`
            ${product.text || ''}
            <small class="text-muted d-block unit-display">
                Satuan: ${product.unit_code || ''}
            </small>
        `);

        $viewMode.find('.qty-display').text(qty);
        $viewMode.find('.note-display').text(note || '-');

        // Update hidden input
        row.find('input[name="stock_id[]"]').val(productId);

        // Kembali ke view mode
        $editMode.hide();
        $viewMode.show();

        // Destroy Select2 untuk hemat memori
        const $select = $editMode.find('.product-select-lazy');
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
    }

    /**
     * Function untuk generate options produk (sama seperti di form.php)
     */
    function generateProductOptions(selectedProductId = '') {
        let optionsHtml = '<option value="">Pilih Produk</option>';

        if (productsData && productsData.length > 0) {
            productsData.forEach(function (product) {
                const isSelected = (selectedProductId && product.id == selectedProductId) ? 'selected' : '';
                const displayName = escapeHtml(product.text || '');
                optionsHtml += `
                    <option value="${product.id}" ${isSelected} data-display="${displayName}">
                        ${displayName}
                    </option>
                `;
            });
        }

        return optionsHtml;
    }

    /**
     * Inisialisasi Select2 untuk mode edit (menggunakan pola form.php)
     */
    function initSelect2Lazy(element) {
        const $element = $(element);

        // Cek apakah sudah diinisialisasi
        if ($element.hasClass('select2-hidden-accessible')) {
            return;
        }

        // Generate options dari productsData
        const selectedId = $element.data('selected-id');
        const optionsHtml = generateProductOptions(selectedId);
        
        // Replace options
        $element.html(optionsHtml);

        // Inisialisasi Select2 dengan tema bootstrap-5 (SAMA PERSIS dengan form.php)
        $element.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Produk',
            allowClear: true,
            dropdownParent: $('.card-body')
        });

        // Set value jika ada selectedId
        if (selectedId) {
            $element.val(selectedId).trigger('change');
        }

        // Event ketika produk dipilih - LANGSUNG SIMPAN
        $element.on('change', function (e) {
            const $row = $(this).closest('.item-row');
            const selectedId = $(this).val();
            const $qtyInput = $row.find('.qty-input');
            const $noteInput = $row.find('.detail-note-input');

            if (selectedId) {
                // Validasi qty
                const qty = parseFloat($qtyInput.val()) || 0;
                if (qty <= 0) {
                    alert('Quantity harus lebih dari 0');
                    $qtyInput.focus();
                    return;
                }

                // Update view mode
                updateViewMode($row, selectedId, qty, $noteInput.val());
            }
        });
    }

    /**
     * Edit item
     */
    $(document).on('click', '.edit-item', function () {
        const $row = $(this).closest('.item-row');
        const $viewMode = $row.find('.view-mode');
        const $editMode = $row.find('.edit-mode');

        $viewMode.hide();
        $editMode.show();

        // Inisialisasi Select2 dengan sedikit delay
        setTimeout(() => {
            initSelect2Lazy($editMode.find('.product-select-lazy'));
        }, 100);
    });

    /**
     * Event untuk quantity input - LANGSUNG SIMPAN SAAT ENTER ATAU BLUR
     */
    $(document).on('blur', '.qty-input', function () {
        const $row = $(this).closest('.item-row');
        const $select = $row.find('.product-select-lazy');
        const selectedId = $select.val();
        const qty = parseFloat($(this).val()) || 0;
        const $noteInput = $row.find('.detail-note-input');

        // Hanya simpan jika produk sudah dipilih dan qty valid
        if (selectedId && qty > 0) {
            updateViewMode($row, selectedId, qty, $noteInput.val());
        } else if (selectedId && qty <= 0) {
            alert('Quantity harus lebih dari 0');
            $(this).focus();
        }
    });

    /**
     * Event untuk enter key di quantity input
     */
    $(document).on('keypress', '.qty-input', function (e) {
        if (e.which === 13) { // Enter key
            $(this).trigger('blur');
        }
    });

    /**
     * Event untuk note input - LANGSUNG SIMPAN SAAT ENTER ATAU BLUR
     */
    $(document).on('blur', '.detail-note-input', function () {
        const $row = $(this).closest('.item-row');
        const $select = $row.find('.product-select-lazy');
        const selectedId = $select.val();
        const $qtyInput = $row.find('.qty-input');
        const qty = parseFloat($qtyInput.val()) || 0;
        const note = $(this).val();

        // Hanya simpan jika produk sudah dipilih dan qty valid
        if (selectedId && qty > 0) {
            updateViewMode($row, selectedId, qty, note);
        }
    });

    /**
     * Event untuk enter key di note input
     */
    $(document).on('keypress', '.detail-note-input', function (e) {
        if (e.which === 13) { // Enter key
            $(this).trigger('blur');
        }
    });

    /**
     * Tambah item baru (mengikuti pola form.php)
     */
    $('#addItem').click(function () {
        const optionsHtml = generateProductOptions();
        const newIndex = itemCounter++;
        
        const newRow = `
            <div class="item-row row mb-3" data-index="${newIndex}">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Produk *</label>
                                <select class="form-control product-select-lazy" 
                                        name="product_id[]" 
                                        data-index="${newIndex}"
                                        data-row-index="${newIndex}"
                                        style="width: 100%;" required>
                                    ${optionsHtml}
                                </select>
                                <input type="hidden" name="stock_id[]" value="">
                                <small class="form-text text-info stock-info"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Qty *</label>
                                <input type="number" class="form-control qty-input" 
                                    name="qty[]" 
                                    data-index="${newIndex}"
                                    data-row-index="${newIndex}"
                                    min="0.01" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Keterangan Barang</label>
                                <input type="text" class="form-control detail-note-input" 
                                    name="detail_note[]" 
                                    data-row-index="${newIndex}"
                                    placeholder="Keterangan tambahan">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#itemsContainer').append(newRow);

        // Inisialisasi Select2 untuk item baru
        initSelect2Lazy($('#itemsContainer .item-row:last-child .product-select-lazy'));
    });

    /**
     * Hapus item (dari mode view)
     */
    $(document).on('click', '.remove-item', function () {
        if ($('.item-row').length > 1) {
            const $row = $(this).closest('.item-row');

            // Destroy Select2 jika ada
            const $select = $row.find('.product-select-lazy');
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $row.remove();
        } else {
            alert('Minimal satu barang harus ada');
        }
    });

    /**
     * Validasi form sebelum submit (mengikuti pola form.php)
     */
    $('#penerimaanForm').submit(function (e) {
        e.preventDefault();

        // Pastikan tidak ada yang dalam mode edit
        if ($('.edit-mode:visible').length > 0) {
            alert('Harap selesaikan pengeditan barang terlebih dahulu');
            return false;
        }

        let valid = true;
        let errorMessages = [];

        // Validasi untuk superadmin - harus memilih gudang tujuan
        <?php if ($user_role == 'superadmin'): ?>
            if (!$('#to_warehouse_id').val()) {
                errorMessages.push('Harap pilih gudang tujuan');
                $('#to_warehouse_id').focus();
                valid = false;
            }
        <?php endif; ?>

        // Validasi berdasarkan tipe penerimaan
        <?php if ($from_status == '1'): ?>
            if (!$('#customer_id').val()) {
                errorMessages.push('Harap pilih pengguna');
                $('#customer_id').focus();
                valid = false;
            }
        <?php elseif ($from_status == '2'): ?>
            if (!$('#supplier_id').val()) {
                errorMessages.push('Harap pilih supplier');
                $('#supplier_id').focus();
                valid = false;
            }
        <?php elseif ($from_status == '3'): ?>
            if (!$('#from_warehouse_id').val()) {
                errorMessages.push('Harap pilih gudang asal');
                $('#from_warehouse_id').focus();
                valid = false;
            }

            const fromWarehouseId = $('#from_warehouse_id').val();
            const toWarehouseId = $('#to_warehouse_id').val();
            <?php if ($user_role == 'superadmin'): ?>
                if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                    errorMessages.push('Gudang asal dan tujuan tidak boleh sama');
                    valid = false;
                }
            <?php endif; ?>
        <?php endif; ?>

        // Validasi nomor invoice/referensi
        const invoiceValue = $('#stockin_invoice').val().trim();
        if (!invoiceValue) {
            errorMessages.push('Harap isi nomor invoice/referensi');
            $('#stockin_invoice').focus();
            valid = false;
        }

        // Validasi setiap baris memiliki produk dan qty
        let hasItems = false;
        $('.item-row').each(function (index) {
            const hasProduct = $(this).find('input[name="stock_id[]"]').val();
            const qtyDisplay = parseFloat($(this).find('.qty-display').text()) || 0;
            const qtyInput = $(this).find('.qty-input').val();

            // Cek apakah dalam mode edit
            if ($(this).find('.edit-mode').is(':visible')) {
                errorMessages.push(`Baris ${index + 1} masih dalam mode edit, harap simpan terlebih dahulu`);
                valid = false;
            }

            // Gunakan qtyDisplay untuk mode view, atau qtyInput untuk mode edit
            const qty = qtyDisplay || parseFloat(qtyInput) || 0;

            if (hasProduct && qty > 0) {
                hasItems = true;
            } else {
                if (!hasProduct) {
                    errorMessages.push(`Produk pada baris ${index + 1} harus dipilih`);
                } else if (qty <= 0) {
                    errorMessages.push(`Quantity pada baris ${index + 1} harus lebih dari 0`);
                }
                valid = false;
            }
        });

        if (!hasItems) {
            errorMessages.push('Minimal satu barang harus ditambahkan');
            valid = false;
        }

        if (!valid) {
            if (errorMessages.length > 0) {
                alert('PERBAIKI ERROR BERIKUT:\n\n' + errorMessages.join('\n'));
            }
            return false;
        }

        // Jika semua valid, submit form
        this.submit();
    });

    // Sembunyikan loading
    setTimeout(function () {
        $('#loading-overlay').addClass('fade-out');
        setTimeout(function () {
            $('#loading-overlay').hide();
            $('#loading-overlay').removeClass('fade-out');
        }, 300);
    }, 800);
});
</script>