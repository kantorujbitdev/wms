<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <?php
        $back_url = 'penerimaan/dari_supplier';
        if ($from_status == '1')
            $back_url = 'penerimaan/dari_pengguna';
        elseif ($from_status == '3')
            $back_url = 'penerimaan/antar_gudang';
        ?>
        <a href="<?= site_url($back_url) ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form <?= $title ?></h6>
        </div>
        <div class="card-body">
            <form id="penerimaanForm" action="<?= site_url('penerimaan/create') ?>" method="POST">
                <input type="hidden" name="from_status" value="<?= $from_status ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_date">Tanggal Penerimaan *</label>
                            <input type="date" class="form-control" id="stockin_date" name="stockin_date"
                                value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                            <small class="form-text text-muted">Tidak bisa memilih tanggal yang akan datang</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stockin_code">Kode Penerimaan *</label>
                            <?php
                            $kode_prefix = 'RI/INV/';
                            if ($from_status == '1')
                                $kode_prefix = 'RET/IN/';
                            elseif ($from_status == '3')
                                $kode_prefix = 'TRF/IN/';
                            ?>
                            <input type="text" class="form-control bg-light" id="stockin_code" name="stockin_code"
                                value="<?= $kode_prefix . date('m/Y') ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Kode otomatis generated oleh sistem</small>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Penerimaan dari Pengguna (from_status = 1) -->
                <?php if ($from_status == '1'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">Pengguna *</label>
                                <select class="form-control select2" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pengguna</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['customer_id'] ?>">
                                            <?= $customer['customer_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stockin_invoice">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    placeholder="Masukkan nomor referensi" required>
                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Penerimaan dari Supplier (from_status = 2) -->
                <?php elseif ($from_status == '2'): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Supplier *</label>
                                <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['supplier_id'] ?>">
                                            <?= $supplier['supplier_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stockin_invoice">No Invoice *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    placeholder="Masukkan nomor invoice" required>
                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Penerimaan Antar Gudang (from_status = 3) -->
                <?php elseif ($from_status == '3'): ?>
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
                                <label for="stockin_invoice">No Referensi *</label>
                                <input type="text" class="form-control" id="stockin_invoice" name="stockin_invoice"
                                    placeholder="Masukkan nomor referensi transfer" required>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="warehouse_id">Gudang Tujuan *</label>
                            <select class="form-control select2" id="warehouse_id" name="warehouse_id" required>
                                <option value="">Pilih Gudang Tujuan</option>
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
                            <label for="from_status">Tipe Penerimaan</label>
                            <?php
                            $tipe_text = 'Dari Supplier';
                            if ($from_status == '1')
                                $tipe_text = 'Dari Pengguna';
                            elseif ($from_status == '3')
                                $tipe_text = 'Antar Gudang';
                            ?>
                            <input type="text" class="form-control bg-light" value="<?= $tipe_text ?>" readonly
                                style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                            <small class="form-text text-muted">Tipe penerimaan sudah ditentukan berdasarkan
                                menu</small>
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
                                <select class="form-control select2" name="product_id[]" required>
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
                                <input type="number" class="form-control" name="qty[]" step="0.01" min="0.01" required>
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
                            <i class="fas fa-save"></i> Simpan Penerimaan
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
        $('.select2').select2({ width: '100%', theme: 'bootstrap4' });

        // Prevent future date selection
        $('#stockin_date').on('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate > today) {
                alert('Tidak bisa memilih tanggal yang akan datang');
                this.value = '<?= date('Y-m-d') ?>';
            }
        });

        // Prevent typing future dates
        $('#stockin_date').on('keydown', function (e) {
            e.preventDefault();
        });

        // Add item row
        $('#addItem').click(function () {
            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select class="form-control select2" name="product_id[]" required>
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
                        <input type="number" class="form-control" name="qty[]" step="0.01" min="0.01" required>
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
            $('#itemsContainer .item-row:last .select2').select2({ width: '100%', theme: 'bootstrap4' });
            $('.remove-item').prop('disabled', false);
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
        $('#penerimaanForm').submit(function (e) {
            let valid = true;

            // Check if at least one item has product selected
            let hasItems = false;
            $('select[name="product_id[]"]').each(function () {
                if ($(this).val()) hasItems = true;
            });

            if (!hasItems) {
                alert('Minimal satu barang harus ditambahkan');
                valid = false;
            }

            // Check quantity values
            $('input[name="qty[]"]').each(function (index) {
                const qty = parseFloat($(this).val());
                if (qty <= 0) {
                    alert('Quantity harus lebih dari 0');
                    valid = false;
                    return false;
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>