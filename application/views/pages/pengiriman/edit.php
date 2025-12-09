<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <?php
        $back_url = 'pengiriman/penggunaan';
        if ($to_status == '3')
            $back_url = 'pengiriman/antar_gudang';
        ?>
        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> <?= $wording['back']; ?>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="pengirimanForm"
                action="<?= site_url('pengiriman/update/' . $pengiriman['header']['stockout_id']) ?>" method="POST">
                <input type="hidden" name="to_status" value="<?= $to_status ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_date">Tanggal Pengiriman *</label>
                            <input type="date" class="form-control" id="stockout_date" name="stockout_date"
                                value="<?= date('Y-m-d', strtotime($pengiriman['header']['stockout_date'])) ?>"
                                max="<?= date('Y-m-d') ?>" required>
                            <small class="form-text text-muted">Tidak bisa memilih tanggal yang akan datang</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_code">Kode Pengiriman *</label>
                            <input type="text" class="form-control bg-light" id="stockout_code" name="stockout_code"
                                value="<?= $pengiriman['header']['stockout_code'] ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Kode tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_warehouse_id">Dari Gudang *</label>
                            <?php if ($user_role == 'superadmin'): ?>
                                <!-- Superadmin dapat mengubah gudang asal -->
                                <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id"
                                    required>
                                    <option value="">Pilih Gudang Asal</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($pengiriman['header']['warehouse_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Superadmin dapat mengubah gudang asal</small>
                            <?php else: ?>
                                <!-- Non-superadmin hanya bisa melihat gudang mereka sendiri -->
                                <input type="text" class="form-control bg-light"
                                    value="<?= $pengiriman['header']['warehouse_name'] ?>" readonly
                                    style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                                <input type="hidden" id="from_warehouse_id" name="from_warehouse_id"
                                    value="<?= $pengiriman['header']['warehouse_id'] ?>">
                                <small class="form-text text-muted">Gudang asal tidak dapat diubah</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_note">Keterangan</label>
                            <textarea class="form-control" id="stockout_note" name="stockout_note"
                                placeholder="Masukkan keterangan tambahan"
                                rows="2"><?= $pengiriman['header']['stockout_note'] ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Pengiriman ke Pengguna (to_status = 1) -->
                <?php if ($to_status == '1'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">Ke Pengguna *</label>
                                <select class="form-control select2" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>"
                                            <?= ($pengiriman['header']['to_id'] == $customer['id']) ? 'selected' : '' ?>>
                                            <?= $customer['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Form untuk Pengiriman Antar Gudang (to_status = 3) -->
                <?php elseif ($to_status == '3'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_warehouse_id">Ke Gudang *</label>
                                <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                    <option value="">Pilih Gudang Tujuan</option>
                                    <?php foreach ($warehouses as $warehouse):
                                        // Jangan tampilkan gudang yang sama dengan asal
                                        $disabled = '';
                                        if ($warehouse['warehouse_id'] == $pengiriman['header']['warehouse_id']) {
                                            $disabled = 'disabled';
                                        }
                                        ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"
                                            <?= ($pengiriman['header']['to_id'] == $warehouse['warehouse_id']) ? 'selected' : '' ?>
                                            <?= $disabled ?>>
                                            <?= $warehouse['warehouse_name'] ?>
                                            <?= ($disabled) ? ' (Gudang Asal)' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">Pilih gudang tujuan pengiriman</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_status">Tipe Pengiriman</label>
                            <?php
                            $tipe_text = 'Ke Pengguna';
                            if ($to_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Tipe pengiriman tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Detail Barang</h5>
                        <small class="text-muted">Pilih barang dari stok yang tersedia di gudang</small>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <?php foreach ($pengiriman['detail'] as $index => $detail): ?>
                        <div class="item-row row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Produk *</label>
                                    <select class="form-control select2 product-select" name="product_id[]"
                                        data-index="<?= $index ?>" required>
                                        <option value="">Pilih Produk</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['product_id'] ?>"
                                                data-stock-id="<?= $product['stock_id'] ?>"
                                                data-available-qty="<?= $product['current_stock'] ?>"
                                                <?= ($detail['product_id'] == $product['product_id']) ? 'selected' : '' ?>>
                                                <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                                (Stok: <?= number_format($product['current_stock'], 2) ?>
                                                <?= $product['type_name'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="stock_id[]" value="<?= $detail['stock_id'] ?>">
                                    <small class="form-text text-info stock-info" id="stockInfo<?= $index ?>">
                                        Stok tersedia: <?= number_format($detail['available_qty'] ?? 0, 2) ?>
                                        <?= $detail['unit_code'] ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Qty *</label>
                                    <input type="number" class="form-control qty-input" name="qty[]"
                                        data-index="<?= $index ?>" min="0" max="<?= $detail['available_qty'] ?? 0 ?>"
                                        value="<?= $detail['qty'] ?>" required>
                                    <small class="form-text text-danger qty-error" id="qtyError<?= $index ?>"
                                        style="display: none;">
                                        Melebihi stok tersedia
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Keterangan Barang</label>
                                    <input type="text" class="form-control" name="detail_note[]"
                                        value="<?= $detail['detail_note'] ?>"
                                        placeholder="Keterangan tambahan untuk barang ini">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Pengiriman
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

<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2();

        // Load products when warehouse changes (for superadmin in edit mode)
        <?php if ($user_role == 'superadmin'): ?>
            $('#from_warehouse_id').on('change', function () {
                const warehouseId = $(this).val();
                if (warehouseId) {
                    if (confirm('Mengubah gudang akan mereset daftar barang. Lanjutkan?')) {
                        loadProductsByWarehouse(warehouseId);
                    } else {
                        // Reset to original value
                        $(this).val('<?= $pengiriman['header']['warehouse_id'] ?>').trigger('change');
                    }
                }
            });
        <?php endif; ?>

        // Prevent future date selection
        $('#stockout_date').on('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Tidak bisa memilih tanggal yang akan datang');
                this.value = '<?= date('Y-m-d', strtotime($pengiriman['header']['stockout_date'])) ?>';
            }
        });

        // Prevent typing future dates
        $('#stockout_date').on('keydown', function (e) {
            e.preventDefault();
        });

        // Handle product selection
        $(document).on('change', '.product-select', function () {
            const index = $(this).data('index');
            const selectedOption = $(this).find('option:selected');
            const stockId = selectedOption.data('stock-id');
            const availableQty = selectedOption.data('available-qty') || 0;

            // Update hidden stock_id input
            $(this).closest('.item-row').find('input[name="stock_id[]"]').val(stockId);

            // Update stock info
            $('#stockInfo' + index).text('Stok tersedia: ' + parseFloat(availableQty).toFixed(2));

            // Update max qty
            const qtyInput = $(this).closest('.item-row').find('.qty-input');
            qtyInput.attr('max', availableQty);

            // Clear error if any
            $('#qtyError' + index).hide();
        });

        // Handle qty input validation
        $(document).on('input', '.qty-input', function () {
            const index = $(this).data('index');
            const qty = parseFloat($(this).val()) || 0;
            const maxQty = parseFloat($(this).attr('max')) || 0;

            if (qty > maxQty) {
                $('#qtyError' + index).show();
            } else {
                $('#qtyError' + index).hide();
            }
        });

        // Add item row
        let itemCounter = <?= count($pengiriman['detail']) ?>;
        $('#addItem').click(function () {
            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select class="form-control select2 product-select" name="product_id[]" data-index="${itemCounter}" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['product_id'] ?>" 
                                    data-stock-id="<?= $product['stock_id'] ?>"
                                    data-available-qty="<?= $product['current_stock'] ?>">
                                    <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                    (Stok: <?= number_format($product['current_stock'], 2) ?> <?= $product['type_name'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="stock_id[]" value="">
                        <small class="form-text text-info stock-info" id="stockInfo${itemCounter}"></small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Qty *</label>
                        <input type="number" class="form-control qty-input" name="qty[]" 
                            data-index="${itemCounter}"  min="0" max="0" required>
                        <small class="form-text text-danger qty-error" id="qtyError${itemCounter}" style="display: none;">
                            Melebihi stok tersedia
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Keterangan Barang</label>
                        <input type="text" class="form-control" name="detail_note[]" placeholder="Keterangan tambahan untuk barang ini">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
            $('#itemsContainer').append(newRow);

            // Reinitialize Select2 for new row
            $('#itemsContainer .item-row:last-child .select2').select2();
            itemCounter++;

            // Enable remove buttons if more than one row
            if ($('.item-row').length > 1) {
                $('.remove-item').prop('disabled', false);
            }
        });

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
            if ($('.item-row').length === 1) {
                $('.remove-item').prop('disabled', true);
            }
        });

        // Form validation
        $('#pengirimanForm').submit(function (e) {
            let valid = true;

            // Validasi untuk pengiriman antar gudang
            <?php if ($to_status == '3'): ?>
                const fromWarehouseId = $('#from_warehouse_id').val();
                const toWarehouseId = $('#to_warehouse_id').val();
                if (fromWarehouseId && toWarehouseId && fromWarehouseId === toWarehouseId) {
                    alert('Tidak bisa mengirim ke gudang yang sama');
                    valid = false;
                }
            <?php endif; ?>

            // Check if at least one item has product selected
            let hasItems = false;
            $('select[name="product_id[]"]').each(function () {
                if ($(this).val()) hasItems = true;
            });

            if (!hasItems) {
                alert('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            // Check quantity values and stock availability
            $('.qty-input').each(function (index) {
                const qty = parseFloat($(this).val()) || 0;
                const maxQty = parseFloat($(this).attr('max')) || 0;

                if (qty <= 0 || isNaN(qty)) {
                    alert('Quantity harus lebih dari 0');
                    valid = false;
                    return false;
                }

                if (qty > maxQty) {
                    alert('Quantity melebihi stok tersedia');
                    valid = false;
                    return false;
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });

        // Function to load products by warehouse
        function loadProductsByWarehouse(warehouseId) {
            $.ajax({
                url: '<?= site_url("pengiriman/load_products_by_warehouse") ?>',
                type: 'POST',
                data: {
                    warehouse_id: warehouseId,
                    <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
                },
                dataType: 'json',
                beforeSend: function () {
                    // Show loading
                    $('#itemsContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
                },
                success: function (response) {
                    if (response.success) {
                        // Update products data
                        const products = response.data;

                        // Clear existing items and add one empty row
                        $('#itemsContainer').html(`
                            <div class="item-row row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Produk *</label>
                                        <select class="form-control select2 product-select" name="product_id[]" data-index="0" required>
                                            <option value="">Pilih Produk</option>
                                            ${products.map(product => `
                                                <option value="${product.product_id}" 
                                                    data-stock-id="${product.stock_id}"
                                                    data-available-qty="${product.current_stock}">
                                                    ${product.product_code} - ${product.product_name}
                                                    (Stok: ${parseFloat(product.current_stock).toFixed(2)} ${product.type_name})
                                                </option>
                                            `).join('')}
                                        </select>
                                        <input type="hidden" name="stock_id[]" value="">
                                        <small class="form-text text-info stock-info" id="stockInfo0"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Qty *</label>
                                        <input type="number" class="form-control qty-input" name="qty[]" 
                                            data-index="0" min="0" max="0" required>
                                        <small class="form-text text-danger qty-error" id="qtyError0" style="display: none;">
                                            Melebihi stok tersedia
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keterangan Barang</label>
                                        <input type="text" class="form-control" name="detail_note[]"
                                            placeholder="Keterangan tambahan untuk barang ini">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block remove-item" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Reinitialize Select2
                        $('.select2').select2();
                        itemCounter = 1;
                    } else {
                        alert(response.message || 'Gagal memuat data produk');
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan saat memuat data produk');
                }
            });
        }
    });
</script>