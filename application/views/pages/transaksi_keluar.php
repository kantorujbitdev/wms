<div class="card">
    <div class="card-header">
        <h3 class="card-title">Transaksi Barang Keluar</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php echo form_open('transaksi/keluar'); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="item_id">Barang <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="item_id" name="item_id" required>
                        <option value="">Pilih Barang</option>
                        <?php if (isset($items['data']) && is_array($items['data'])): ?>
                            <?php foreach ($items['data'] as $item): ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo set_select('item_id', $item['id']); ?>>
                                    <?php echo $item['name'] . ' (' . $item['code'] . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php echo form_error('item_id', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="warehouse_id">Gudang <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="warehouse_id" name="warehouse_id" required>
                        <option value="">Pilih Gudang</option>
                        <?php if (isset($warehouses['data']) && is_array($warehouses['data'])): ?>
                            <?php foreach ($warehouses['data'] as $warehouse): ?>
                                <option value="<?php echo $warehouse['id']; ?>" <?php echo set_select('warehouse_id', $warehouse['id']); ?>><?php echo $warehouse['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php echo form_error('warehouse_id', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="<?php echo set_value('quantity'); ?>" required>
                    <?php echo form_error('quantity', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="date">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo set_value('date', date('Y-m-d')); ?>" required>
                    <?php echo form_error('date', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="customer">Customer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="customer" name="customer"
                        value="<?php echo set_value('customer'); ?>" required>
                    <?php echo form_error('customer', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label for="reference_no">Nomor Referensi</label>
                    <input type="text" class="form-control" id="reference_no" name="reference_no"
                        value="<?php echo set_value('reference_no'); ?>">
                    <?php echo form_error('reference_no', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="notes">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes"
                        rows="3"><?php echo set_value('notes'); ?></textarea>
                    <?php echo form_error('notes', '<div class="text-danger">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo site_url('transaksi'); ?>" class="btn btn-default">Batal</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/select2.min.css'); ?>">
<script src="<?php echo base_url('assets/js/select2.min.js'); ?>"></script>

<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2();

        // Set current date if empty
        if ($('#date').val() == '') {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            $('#date').val(today);
        }
    });
</script>