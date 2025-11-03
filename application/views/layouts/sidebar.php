<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo site_url('dashboard'); ?>" class="text-decoration-none text-white fw-bold fs-4">
            <i class="fas fa-warehouse me-2"></i>WMS
        </a>
    </div>

    <ul class="list-unstyled components mt-3 px-2">
        <li class="sidebar-item mb-2 <?php echo $active_menu == 'dashboard' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('dashboard'); ?>"
                class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-item mb-2 <?php echo $active_menu == 'barang' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('barang'); ?>"
                class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                <i class="fas fa-boxes me-2"></i> Barang
            </a>
        </li>
        <li class="sidebar-item mb-2 <?php echo $active_menu == 'gudang' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('gudang'); ?>"
                class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                <i class="fas fa-warehouse me-2"></i> Gudang
            </a>
        </li>

        <li class="sidebar-item mb-2">
            <a href="#transaksiSubmenu" data-bs-toggle="collapse" aria-expanded="false"
                class="d-flex align-items-center py-2 px-3 rounded sidebar-link dropdown-toggle <?php echo $active_menu == 'transaksi' ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt me-2"></i> Transaksi
            </a>
            <ul class="collapse list-unstyled ps-4 <?php echo $active_menu == 'transaksi' ? 'show' : ''; ?>"
                id="transaksiSubmenu">
                <li class="<?php echo $active_submenu == 'index' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('transaksi/index'); ?>" class="sidebar-sublink"><i
                            class="fas fa-sign-in-alt me-2"></i>
                        Daftar</a>
                </li>
                <li class="<?php echo $active_submenu == 'masuk' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('transaksi/masuk'); ?>" class="sidebar-sublink"><i
                            class="fas fa-sign-in-alt me-2"></i>
                        Barang Masuk</a>
                </li>
                <li class="<?php echo $active_submenu == 'keluar' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('transaksi/keluar'); ?>" class="sidebar-sublink"><i
                            class="fas fa-sign-out-alt me-2"></i> Barang Keluar</a>
                </li>
                <li class="<?php echo $active_submenu == 'transfer' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('transaksi/transfer'); ?>" class="sidebar-sublink"><i
                            class="fas fa-exchange-alt me-2"></i> Transfer Stok</a>
                </li>
                <li class="<?php echo $active_submenu == 'index' ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('transaksi'); ?>" class="sidebar-sublink"><i
                            class="fas fa-history me-2"></i> Riwayat</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item mb-2 <?php echo $active_menu == 'laporan' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('laporan'); ?>"
                class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                <i class="fas fa-chart-bar me-2"></i> Laporan
            </a>
        </li>

        <?php if (isset($user) && $user['role'] == 'Admin'): ?>
            <li class="sidebar-item mb-2 <?php echo $active_menu == 'user' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('user'); ?>" class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                    <i class="fas fa-users me-2"></i> User Management
                </a>
            </li>
            <li class="sidebar-item mb-2 <?php echo $active_menu == 'pengaturan' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('pengaturan'); ?>"
                    class="d-flex align-items-center py-2 px-3 rounded sidebar-link">
                    <i class="fas fa-cog me-2"></i> Pengaturan
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Content Wrapper -->
<div id="content" class="flex-grow-1">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-outline-primary me-3">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo isset($user) ? $user['name'] : 'User'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?php echo site_url('auth/logout'); ?>"><i
                                        class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Main Content -->
    <div class="container-fluid mt-3">