<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan</h3>
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
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Laporan</h3>
                        <p>Stok Barang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <a href="<?php echo site_url('laporan/stock'); ?>" class="small-box-footer">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Laporan</h3>
                        <p>Barang Masuk</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <a href="<?php echo site_url('laporan/transaction_in'); ?>" class="small-box-footer">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-md-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Laporan</h3>
                        <p>Barang Keluar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <a href="<?php echo site_url('laporan/transaction_out'); ?>" class="small-box-footer">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Filter Laporan</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('laporan', array('method' => 'get')); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="warehouse_id">Gudang</label>
                                        <select class="form-control select2" id="warehouse_id" name="warehouse_id">
                                            <option value="">Semua Gudang</option>
                                            <?php if (isset($warehouses['data']) && is_array($warehouses['data'])): ?>
                                                <?php foreach ($warehouses['data'] as $warehouse): ?>
                                                    <option value="<?php echo $warehouse['id']; ?>" <?php echo set_select('warehouse_id', $warehouse['id']); ?>><?php echo $warehouse['name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="item_id">Barang</label>
                                        <select class="form-control select2" id="item_id" name="item_id">
                                            <option value="">Semua Barang</option>
                                            <?php if (isset($items['data']) && is_array($items['data'])): ?>
                                                <?php foreach ($items['data'] as $item): ?>
                                                    <option value="<?php echo $item['id']; ?>" <?php echo set_select('item_id', $item['id']); ?>><?php echo $item['name'] . ' (' . $item['code'] . ')'; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_date">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo set_value('start_date'); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="end_date">Tanggal Selesai</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo set_value('end_date'); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-default" id="reset-filter">Reset</button>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/select2.min.css'); ?>">
<script src="<?php echo base_url('assets/js/select2.min.js'); ?>"></script>

<script>
 $(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
    
    // Reset filter
    $('#reset-filter').click(function() {
        window.location.href = '<?php echo site_url('laporan'); ?>';
    });
});
</script>