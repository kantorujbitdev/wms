<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= site_url('dashboard'); ?>"
            class="text-decoration-none text-white fw-bold fs-5 d-flex align-items-center">
            <img src="<?php echo base_url('assets/images/logo_ujb_no_name_white.png'); ?>" alt="Logo"
                style="height: 28px; vertical-align: middle; margin-right: 8px;">
            <span><?= $wording['app_name']; ?></span>
        </a>
    </div>

    <ul class="list-unstyled components mt-3 px-2">
        <li class="sidebar-item <?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?= site_url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span><?= $wording['dashboard']; ?></span>
            </a>
        </li>

        <li class="sidebar-item <?= ($active_menu == 'barang') ? 'active' : ''; ?>">
            <a href="<?= site_url('barang'); ?>" class="sidebar-link">
                <i class="fas fa-boxes me-2"></i>
                <span><?= $wording['barang']; ?></span>
            </a>
        </li>

        <li class="sidebar-item <?= ($active_menu == 'gudang') ? 'active' : ''; ?>">
            <a href="<?= site_url('gudang'); ?>" class="sidebar-link">
                <i class="fas fa-warehouse me-2"></i>
                <span><?= $wording['gudang']; ?></span>
            </a>
        </li>

        <!-- Transaksi Dropdown -->
        <li class="sidebar-item <?= ($active_menu == 'transaksi') ? 'active' : ''; ?>">
            <a href="#transaksiSubmenu" data-bs-toggle="collapse"
                aria-expanded="<?= ($active_menu == 'transaksi') ? 'true' : 'false'; ?>"
                class="sidebar-link dropdown-toggle">
                <i class="fas fa-exchange-alt me-2"></i>
                <span><?= $wording['transaksi']; ?></span>
            </a>
            <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'transaksi') ? 'show' : ''; ?>"
                id="transaksiSubmenu">
                <li><a href="<?= site_url('transaksi/masuk'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'masuk') ? 'active' : ''; ?>">
                        <i class="fas fa-sign-in-alt me-2"></i><?= $wording['transaksi_masuk']; ?></a>
                </li>
                <li><a href="<?= site_url('transaksi/keluar'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'keluar') ? 'active' : ''; ?>">
                        <i class="fas fa-sign-out-alt me-2"></i><?= $wording['transaksi_keluar']; ?></a>
                </li>
                <li><a href="<?= site_url('transaksi/transfer'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'transfer') ? 'active' : ''; ?>">
                        <i class="fas fa-exchange-alt me-2"></i><?= $wording['transaksi_transfer']; ?></a>
                </li>
                <li><a href="<?= site_url('transaksi'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'riwayat') ? 'active' : ''; ?>">
                        <i class="fas fa-history me-2"></i>Riwayat</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item <?= ($active_menu == 'laporan') ? 'active' : ''; ?>">
            <a href="<?= site_url('laporan'); ?>" class="sidebar-link">
                <i class="fas fa-chart-bar me-2"></i>
                <span><?= $wording['laporan']; ?></span>
            </a>
        </li>

        <?php if (isset($user) && $user['role'] == 'Admin'): ?>
            <li class="sidebar-item <?= ($active_menu == 'user') ? 'active' : ''; ?>">
                <a href="<?= site_url('user'); ?>" class="sidebar-link">
                    <i class="fas fa-users me-2"></i>
                    <span><?= $wording['user']; ?></span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_menu == 'pengaturan') ? 'active' : ''; ?>">
                <a href="<?= site_url('pengaturan'); ?>" class="sidebar-link">
                    <i class="fas fa-cog me-2"></i>
                    <span><?= $wording['pengaturan']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <hr class="sidebar-divider my-3">
        <!-- Sidebar Footer -->
        <li class="sidebar-item mt-auto mb-3">
            <div class="px-3 py-2 text-center small" style="color: #f8f9fa;">
                &copy; <?= date('Y'); ?>
                <span><?= $wording['app_name']; ?></span><br><span><?= $wording['app_name_full']; ?></span>
            </div>
        </li>

    </ul>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper" id="content-wrapper">
    <?php $this->load->view('layouts/top_profile'); ?>
    <div class="container-fluid mt-4 p-4">