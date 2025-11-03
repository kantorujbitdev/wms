<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
    </div>

    <!-- Report Cards -->
    <div class="row">
        <!-- Stock Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Laporan Stok</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Current Stock</div>
                            <p class="mb-0">Laporan stok barang di semua gudang</p>
                            <a href="<?php echo site_url('laporan/stok'); ?>" class="btn btn-primary btn-sm mt-2">View
                                Report</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Barang Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">In Transactions</div>
                            <p class="mb-0">Laporan barang masuk per periode</p>
                            <a href="<?php echo site_url('laporan/masuk'); ?>" class="btn btn-success btn-sm mt-2">View
                                Report</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out Report Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Barang Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Out Transactions</div>
                            <p class="mb-0">Laporan barang keluar per periode</p>
                            <a href="<?php echo site_url('laporan/keluar'); ?>" class="btn btn-danger btn-sm mt-2">View
                                Report</a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>