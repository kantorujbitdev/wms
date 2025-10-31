<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo isset($summary['data']['total_items']) ? number_format($summary['data']['total_items']) : '0'; ?>
                </h3>
                <p>Total Barang</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="<?php echo site_url('barang'); ?>" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo isset($summary['data']['total_warehouses']) ? number_format($summary['data']['total_warehouses']) : '0'; ?>
                </h3>
                <p>Total Gudang</p>
            </div>
            <div class="icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <a href="<?php echo site_url('gudang'); ?>" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo isset($summary['data']['transactions_today']) ? number_format($summary['data']['transactions_today']) : '0'; ?>
                </h3>
                <p>Transaksi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <a href="<?php echo site_url('transaksi'); ?>" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo isset($summary['data']['low_stock_items']) ? number_format($summary['data']['low_stock_items']) : '0'; ?>
                </h3>
                <p>Stok Menipis</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?php echo site_url('barang'); ?>" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <div class="col-md-8">
        <!-- TABLE: LATEST TRANSACTIONS -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Transaksi Terbaru</h3>
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recent_transactions['data']) && is_array($recent_transactions['data']) && count($recent_transactions['data']) > 0): ?>
                                <?php foreach ($recent_transactions['data'] as $transaction): ?>
                                    <tr>
                                        <td><?php echo $transaction['id']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($transaction['date'])); ?></td>
                                        <td><?php echo $transaction['item_name']; ?></td>
                                        <td>
                                            <?php if ($transaction['type'] == 'in'): ?>
                                                <span class="badge bg-success">Masuk</span>
                                            <?php elseif ($transaction['type'] == 'out'): ?>
                                                <span class="badge bg-danger">Keluar</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Transfer</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $transaction['quantity'] . ' ' . $transaction['unit']; ?></td>
                                        <td>
                                            <?php if ($transaction['status'] == 'completed'): ?>
                                                <span class="badge bg-success">Selesai</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Proses</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <a href="<?php echo site_url('transaksi'); ?>" class="btn btn-sm btn-info float-left">Lihat Semua
                    Transaksi</a>
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->

        <!-- CHART: STOCK MOVEMENT -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Grafik Pergerakan Stok</h3>
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
                <div class="chart">
                    <canvas id="stockChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->

    <div class="col-md-4">
        <!-- CARD: LOW STOCK ITEMS -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Stok Menipis</h3>
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
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    <?php if (isset($low_stock['data']) && is_array($low_stock['data']) && count($low_stock['data']) > 0): ?>
                        <?php foreach ($low_stock['data'] as $item): ?>
                            <li class="item">
                                <div class="product-info">
                                    <a href="<?php echo site_url('barang/detail/' . $item['id']); ?>"
                                        class="product-title"><?php echo $item['name']; ?>
                                        <span
                                            class="badge badge-warning float-right"><?php echo $item['stock'] . ' ' . $item['unit']; ?></span>
                                    </a>
                                    <span class="product-description">
                                        <?php echo $item['warehouse_name']; ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="item">
                            <div class="product-info text-center">
                                <p>Tidak ada barang dengan stok menipis</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="<?php echo site_url('barang'); ?>" class="uppercase">Lihat Semua Barang</a>
            </div>
        </div>
        <!-- /.card -->

        <!-- CARD: ACTIVITY LOG -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Log Aktivitas</h3>
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
            <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">User admin menambah barang baru
                                <span class="badge badge-info float-right">10 menit lalu</span>
                            </a>
                        </div>
                    </li>
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">User supervisor membuat transaksi barang
                                masuk
                                <span class="badge badge-info float-right">1 jam lalu</span>
                            </a>
                        </div>
                    </li>
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">User staff mengubah data gudang
                                <span class="badge badge-info float-right">2 jam lalu</span>
                            </a>
                        </div>
                    </li>
                    <li class="item">
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">User admin menghapus transaksi
                                <span class="badge badge-info float-right">3 jam lalu</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">Lihat Semua Log</a>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- ChartJS -->
<script src="<?php echo base_url('assets/js/chart.min.js'); ?>"></script>
<script>
    $(document).ready(function () {
        // Stock Movement Chart
        var stockChartCanvas = $('#stockChart').get(0).getContext('2d');
        var stockChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Barang Masuk',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80]
                },
                {
                    label: 'Barang Keluar',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [28, 48, 40, 19, 86, 27, 90, 75, 65, 55, 60, 70]
                }
            ]
        };

        var stockChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false,
                    }
                }]
            }
        };

        // This will get the first returned node in the jQuery collection.
        var stockChart = new Chart(stockChartCanvas, {
            type: 'line',
            data: stockChartData,
            options: stockChartOptions
        });
    });
</script>