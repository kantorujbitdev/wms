<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pengiriman untuk Penggunaan</h1>
        <a href="<?= site_url('pengiriman/penggunaan') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pengiriman untuk Penggunaan</h6>
        </div>
        <div class="card-body">
            <form id="pengirimanForm" action="<?= site_url('pengiriman/create_penggunaan') ?>" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="StockOutDate">Tanggal Pengiriman *</label>
                            <input type="date" class="form-control" id="StockOutDate" name="StockOutDate"
                                value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="StockOutCode">Kode Pengiriman *</label>
                            <input type="text" class="form-control" id="StockOutCode" name="StockOutCode"
                                value="RO/INV/<?= date('m/Y') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="CustomerID">Customer *</label>
                            <select class="form-control select2" id="CustomerID" name="CustomerID" required>
                                <option value="">Pilih Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['customer_id'] ?>"><?= $customer['customer_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="WarehouseID">Gudang *</label>
                            <select class="form-control select2" id="WarehouseID" name="WarehouseID" required>
                                <option value="">Pilih Gudang</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>"><?= $warehouse['warehouse_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="StockOutNote">Keterangan</label>
                            <textarea class="form-control" id="StockOutNote" name="StockOutNote" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
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
                                <select class="form-control select2" name="Stock_Id[]" required>
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
                                <input type="number" class="form-control" name="Qty[]" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="DetailNote[]">
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
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="<?= site_url('pengiriman/penggunaan') ?>" class="btn btn-secondary"><i
                                class="fas fa-times"></i> Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2({ width: '100%', theme: 'bootstrap4' });

        $('#addItem').click(function () {
            const newRow = `
            <div class="item-row row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Produk *</label>
                        <select class="form-control select2" name="Stock_Id[]" required>
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
                        <input type="number" class="form-control" name="Qty[]" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" name="DetailNote[]">
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

        $(document).on('click', '.remove-item', function () {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
            if ($('.item-row').length === 1) {
                $('.remove-item').prop('disabled', true);
            }
        });

        $('#pengirimanForm').submit(function (e) {
            let hasItems = false;
            $('select[name="Stock_Id[]"]').each(function () {
                if ($(this).val()) hasItems = true;
            });
            if (!hasItems) {
                alert('Minimal satu barang harus ditambahkan');
                e.preventDefault();
            }
        });
    });
</script>