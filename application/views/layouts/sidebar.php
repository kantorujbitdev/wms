<?php $config = get_app_config(); ?>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= site_url('dashboard'); ?>"
            class="text-decoration-none text-white fw-bold fs-5 d-flex align-items-center">
            <img src="<?php echo base_url($config['app_logo']); ?>" alt="Logo"
                style="height: 28px; vertical-align: middle; margin-right: 8px;">
            <span><?= $config['app_name']; ?></span>
        </a>
    </div>

    <ul class="list-unstyled components mt-3 px-2">
        <!-- Dashboard -->
        <li class="sidebar-item <?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?= site_url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span><?= $wording['dashboard']; ?></span>
            </a>
        </li>

        <!-- Gudang -->
        <?php if (is_role(['superadmin'])): ?>
            <li class="sidebar-item <?= ($active_menu == 'gudang') ? 'active' : ''; ?>">
                <a href="#gudangSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'gudang') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="fas fa-warehouse me-2"></i>
                    <span><?= $wording['gudang']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'gudang') ? 'show' : ''; ?>" id="gudangSubmenu">
                    <li>
                        <a href="<?= site_url('gudang/gudang_project'); ?>"
                            class="sidebar-sublink <?= ($active_submenu == 'gudang_project') ? 'active' : ''; ?>">
                            <i class="fas fa-box me-2"></i>
                            <span>Gudang Project</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('gudang'); ?>"
                            class="sidebar-sublink <?= ($active_submenu == 'gudang_utama') ? 'active' : ''; ?>">
                            <i class="fas fa-layer-group me-2"></i><span>Gudang Utama</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Barang Dropdown -->
        <li class="sidebar-item <?= ($active_menu == 'barang') ? 'active' : ''; ?>">
            <a href="#barangSubmenu" data-bs-toggle="collapse"
                aria-expanded="<?= ($active_menu == 'barang') ? 'true' : 'false'; ?>"
                class="sidebar-link dropdown-toggle">
                <i class="fas fa-boxes me-2"></i>
                <span><?= $wording['master_barang']; ?></span>
            </a>
            <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'barang') ? 'show' : ''; ?>" id="barangSubmenu">
                <li>
                    <a href="<?= site_url('barang/tipe_produk'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'tipe_produk') ? 'active' : ''; ?>">
                        <i class="fas fa-tags me-2"></i>Product Type
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('barang/tipe_satuan'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'tipe_satuan') ? 'active' : ''; ?>">
                        <i class="fas fa-balance-scale me-2"></i>Unit Type
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('barang'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'produk') ? 'active' : ''; ?>">
                        <i class="fas fa-box me-2"></i>Product
                    </a>
                </li>
            </ul>
        </li>

        <!-- Penerimaan -->
        <li class="sidebar-item <?= ($active_menu == 'penerimaan') ? 'active' : ''; ?>">
            <a href="#penerimaanSubmenu" data-bs-toggle="collapse"
                aria-expanded="<?= ($active_menu == 'penerimaan') ? 'true' : 'false'; ?>"
                class="sidebar-link dropdown-toggle">
                <i class="fas fa-inbox me-2"></i>
                <span><?= $wording['penerimaan']; ?></span>
            </a>
            <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'penerimaan') ? 'show' : ''; ?>"
                id="penerimaanSubmenu">

                <li>
                    <a href="<?= site_url('penerimaan/antar_gudang'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'antar_gudang') ? 'active' : ''; ?>">
                        <i class="fas fa-right-left me-2"></i>
                        <span>Antar Gudang</span>
                    </a>
                </li>

                <li>
                    <a href="<?= site_url('penerimaan/from_supplier'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'supplier') ? 'active' : ''; ?>">
                        <i class="fas fa-truck-ramp-box me-2"></i>
                        <span>Supplier</span>
                    </a>
                </li>

            </ul>
        </li>
        <!-- Pengiriman -->
        <li class="sidebar-item <?= ($active_menu == 'pengiriman') ? 'active' : ''; ?>">
            <a href="#pengirimanSubmenu" data-bs-toggle="collapse"
                aria-expanded="<?= ($active_menu == 'pengiriman') ? 'true' : 'false'; ?>"
                class="sidebar-link dropdown-toggle">
                <i class="fas fa-paper-plane me-2"></i>
                <span><?= $wording['pengiriman']; ?></span>
            </a>

            <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'pengiriman') ? 'show' : ''; ?>"
                id="pengirimanSubmenu">

                <li>
                    <a href="<?= site_url('pengiriman/antar_gudang'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'antar_gudang') ? 'active' : ''; ?>">
                        <i class="fas fa-right-left me-2"></i>
                        <span>Antar Gudang</span>
                    </a>
                </li>

                <li>
                    <a href="<?= site_url('pengiriman/penggunaan'); ?>"
                        class="sidebar-sublink <?= ($active_submenu == 'penggunaan') ? 'active' : ''; ?>">
                        <i class="fas fa-dolly me-2"></i>
                        <span>Penggunaan</span>
                    </a>
                </li>

            </ul>
        </li>


        <!-- Transaksi Dropdown -->
        <!-- <li class="sidebar-item <?= ($active_menu == 'transaksi') ? 'active' : ''; ?>">
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
        </li> -->

        <!-- Laporan -->
        <li class="sidebar-item <?= ($active_menu == 'laporan') ? 'active' : ''; ?>">
            <a href="<?= site_url('laporan'); ?>" class="sidebar-link">
                <i class="fas fa-chart-bar me-2"></i>
                <span><?= $wording['laporan']; ?></span>
            </a>
        </li>

        <!-- Customer -->
        <li class="sidebar-item <?= ($active_menu == 'customer') ? 'active' : ''; ?>">
            <a href="<?= site_url('customer'); ?>" class="sidebar-link">
                <i class="fas fa-users me-2"></i>
                <span><?= $wording['customer']; ?></span>
            </a>
        </li>

        <!-- Supplier -->
        <li class="sidebar-item <?= ($active_menu == 'supplier') ? 'active' : ''; ?>">
            <a href="<?= site_url('supplier'); ?>" class="sidebar-link">
                <i class="fas fa-chart-bar me-2"></i>
                <span><?= $wording['supplier']; ?></span>
            </a>
        </li>

        <!-- Admin Only -->
        <?php if (is_role(['superadmin', 'admin'])): ?>
            <!-- User Management -->
            <li class="sidebar-item <?= ($active_menu == 'user') ? 'active' : ''; ?>">
                <a href="<?= site_url('user'); ?>" class="sidebar-link">
                    <i class="fas fa-users me-2"></i>
                    <span><?= $wording['user']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (is_role('superadmin')): ?>

            <!-- Pengaturan Dropdown -->
            <li class="sidebar-item <?= ($active_menu == 'pengaturan') ? 'active' : ''; ?>">
                <a href="#pengaturanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'pengaturan') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="fas fa-cog me-2"></i>
                    <span><?= $wording['pengaturan']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'pengaturan') ? 'show' : ''; ?>"
                    id="pengaturanSubmenu">
                    <li><a href="<?= site_url('pengaturan'); ?>"
                            class="sidebar-sublink <?= ($active_submenu == 'web') ? 'active' : ''; ?>">
                            <i class="fas fa-globe me-2"></i>Web</a>
                    </li>
                    <li><a href="<?= site_url('api'); ?>"
                            class="sidebar-sublink <?= ($active_submenu == 'api') ? 'active' : ''; ?>">
                            <i class="fas fa-plug me-2"></i>API</a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>

        <hr class="sidebar-divider my-3">

        <!-- Sidebar Footer -->
        <li class="sidebar-item mt-auto mb-3">
            <div class="px-3 py-2 text-center small" style="color: #f8f9fa;">
                &copy; <?= date('Y'); ?>
                <span><?= $config['app_name']; ?></span><br>
                <span><?= $config['app_fullname']; ?></span>
            </div>
        </li>
    </ul>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper" id="content-wrapper">
    <?php $this->load->view('layouts/top_profile'); ?>
    <div class="container-fluid mt-4 p-4">