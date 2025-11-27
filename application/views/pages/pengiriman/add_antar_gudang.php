<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pengiriman Antar Gudang</h1>
        <a href="<?= site_url('pengiriman/antar_gudang') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pengiriman Antar Gudang</h6>
        </div>
        <div class="card-body">
            <form id="pengirimanForm" action="<?= site_url('pengiriman/create_antar_gudang') ?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_date">Tanggal Pengiriman *</label>
                            <input type="date" class="form-control" id="stockout_date" name="stockout_date"
                                value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_code">Kode Pengiriman *</label>
                            <input type="text" class="form-control" id="stockout_code" name="stockout_code"
                                value="TRF/OUT/<?= date('m/Y') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_warehouse_id">Gudang Asal *</label>
                            <select class="form-control select2" id="from_warehouse_id" name="from_warehouse_id"
                                required>
                                <option value="">Pilih Gudang Asal</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>">
                                        <?= $warehouse['warehouse_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_warehouse_id">Gudang Tujuan *</label>
                            <select class="form-control select2" id="to_warehouse_id" name="to_warehouse_id" required>
                                <option value="">Pilih Gudang Tujuan</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>">
                                        <?= $warehouse['warehouse_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockout_reference">No Referensi *</label>
                            <input type="text" class="form-control" id="stockout_reference" name="stockout_reference"
                                placeholder="Masukkan nomor referensi" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Items Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5 class="font-weight-bold">Detail Barang</h5>
                        <button type="button" id="addItem" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div id="itemsContainer">
                    <div class="item-row row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Produk *</label>
                                <select class="form-control select2 product-select" name="product_id[]" required>
                                    <option value="">Pilih Produk</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['stock_id'] ?>">
                                            <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Qty *</label>
                                <input type="number" class="form-control" name="qty[]" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="detail_note[]"
                                    placeholder="Keterangan tambahan">
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
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengiriman
                        </button>
                        <a href="<?= site_url('pengiriman/antar_gudang') ?>" class="btn btn-secondary">
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
        $('.select2').select2({
            width: '100%',
            theme: 'bootstrap4'
        });

        // Add item row
        $('#addItem').click(function () {
            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select class="form-control select2 product-select" name="product_id[]" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['stock_id'] ?>">
                                    <?= $product['product_code'] ?> - <?= $product['product_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Qty *</label>
                        <input type="number" class="form-control" name="qty[]" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" name="detail_note[]" placeholder="Keterangan tambahan">
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
            $('#itemsContainer .item-row:last .select2').select2({
                width: '100%',
                theme: 'bootstrap4'
            });

            // Enable remove buttons for all rows except first
            $('.remove-item').prop('disabled', false);
        });

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }

            // Disable remove button if only one row left
            if ($('.item-row').length === 1) {
                $('.remove-item').prop('disabled', true);
            }
        });

        // Form validation
        $('#pengirimanForm').submit(function (e) {
            let valid = true;

            // Check if at least one item has product selected
            let hasItems = false;
            $('select[name="product_id[]"]').each(function () {
                if ($(this).val()) {
                    hasItems = true;
                }
            });

            if (!hasItems) {
                alert('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            // Check if from and to warehouse are different
            const fromWarehouse = $('#from_warehouse_id').val();
            const toWarehouse = $('#to_warehouse_id').val();

            if (fromWarehouse && toWarehouse && fromWarehouse === toWarehouse) {
                alert('Gudang asal dan gudang tujuan tidak boleh sama');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>