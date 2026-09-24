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
                <i class="<?= get_menu_icon('dashboard'); ?> me-2"></i>
                <span><?= $wording['dashboard']; ?></span>
            </a>
        </li>

        <!-- User Management -->
        <?php if (can_access_menu('user')): ?>
            <li class="sidebar-item <?= ($active_menu == 'user') ? 'active' : ''; ?>">
                <a href="<?= site_url('user'); ?>" class="sidebar-link">
                    <i class="<?= get_menu_icon('user'); ?> me-2"></i>
                    <span><?= $wording['user']; ?></span>
                </a>
            </li>
        <?php endif; ?>



        <!-- Customer -->
        <?php if (can_access_menu('customer')): ?>
            <li class="sidebar-item <?= ($active_menu == 'customer') ? 'active' : ''; ?>">
                <a href="<?= site_url('customer'); ?>" class="sidebar-link">
                    <i class="<?= get_menu_icon('customer'); ?> me-2"></i>
                    <span><?= $wording['customer']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Supplier -->
        <?php if (can_access_menu('supplier')): ?>
            <li class="sidebar-item <?= ($active_menu == 'supplier') ? 'active' : ''; ?>">
                <a href="<?= site_url('supplier'); ?>" class="sidebar-link">
                    <i class="<?= get_menu_icon('supplier'); ?> me-2"></i>
                    <span><?= $wording['supplier']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Gudang -->
        <?php if (can_access_menu('gudang')): ?>
            <li class="sidebar-item <?= ($active_menu == 'gudang') ? 'active' : ''; ?>">
                <a href="#gudangSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'gudang') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('gudang'); ?> me-2"></i>
                    <span><?= $wording['gudang']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'gudang') ? 'show' : ''; ?>" id="gudangSubmenu">
                    <?php if (can_access_menu('gudang_project')): ?>
                        <li>
                            <a href="<?= site_url('gudang/gudang_project'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'gudang_project') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('gudang_project'); ?> me-2"></i>
                                <span>Gudang Project</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('gudang_utama')): ?>
                        <li>
                            <a href="<?= site_url('gudang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'gudang_utama') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('gudang_utama'); ?> me-2"></i>
                                <span>Gudang Utama</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Barang Dropdown -->
        <?php if (can_access_menu('barang')): ?>
            <li class="sidebar-item <?= ($active_menu == 'barang') ? 'active' : ''; ?>">
                <a href="#barangSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'barang') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('barang'); ?> me-2"></i>
                    <span><?= $wording['master_barang']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'barang') ? 'show' : ''; ?>" id="barangSubmenu">
                    <?php if (can_access_menu('tipe_produk')): ?>
                        <li>
                            <a href="<?= site_url('barang/tipe_produk'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'tipe_produk') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('tipe_produk'); ?> me-2"></i><?= $wording['tipe_produk']; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('tipe_satuan')): ?>
                        <li>
                            <a href="<?= site_url('barang/tipe_satuan'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'tipe_satuan') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('tipe_satuan'); ?> me-2"></i><?= $wording['tipe_satuan']; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('produk')): ?>
                        <li>
                            <a href="<?= site_url('barang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'produk') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('produk'); ?> me-2"></i><?= $wording['barang']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Gudang Stok -->
        <?php if (can_access_menu('gudang_stok')): ?>
            <li class="sidebar-item <?= ($active_menu == 'gudang_stok') ? 'active' : ''; ?>">
                <a href="<?= site_url('gudang_stok'); ?>" class="sidebar-link">
                    <i class="<?= get_menu_icon('gudang_stok'); ?> me-2"></i>
                    <span><?= $wording['gudang_stok']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Penerimaan -->
        <?php if (can_access_menu('penerimaan')): ?>
            <li class="sidebar-item <?= ($active_menu == 'penerimaan') ? 'active' : ''; ?>">
                <a href="#penerimaanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'penerimaan') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('penerimaan'); ?> me-2"></i>
                    <span><?= $wording['penerimaan']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'penerimaan') ? 'show' : ''; ?>"
                    id="penerimaanSubmenu">
                    <?php if (can_access_menu('penerimaan_antar_gudang')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/antar_gudang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'penerimaan_antar_gudang') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('penerimaan_antar_gudang'); ?> me-2"></i>
                                <span>Antar Gudang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('supplier_penerimaan')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/dari_supplier'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'supplier_penerimaan') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('supplier_penerimaan'); ?> me-2"></i>
                                <span>Supplier</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('pengguna_penerimaan')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/dari_pengguna'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'pengguna') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('pengguna_penerimaan'); ?> me-2"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Pengiriman -->
        <?php if (can_access_menu('pengiriman')): ?>
            <li class="sidebar-item <?= ($active_menu == 'pengiriman') ? 'active' : ''; ?>">
                <a href="#pengirimanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'pengiriman') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('pengiriman'); ?> me-2"></i>
                    <span><?= $wording['pengiriman']; ?></span>
                </a>

                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'pengiriman') ? 'show' : ''; ?>"
                    id="pengirimanSubmenu">
                    <?php if (can_access_menu('pengiriman_antar_gudang')): ?>
                        <li>
                            <a href="<?= site_url('pengiriman/antar_gudang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'pengiriman_antar_gudang') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('pengiriman_antar_gudang'); ?> me-2"></i>
                                <span>Antar Gudang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('penggunaan')): ?>
                        <li>
                            <a href="<?= site_url('pengiriman/penggunaan'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'penggunaan') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('penggunaan'); ?> me-2"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- laporan -->
        <?php if (can_access_menu('laporan')): ?>
            <li class="sidebar-item <?= ($active_menu == 'laporan') ? 'active' : ''; ?>">
                <a href="#laporanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'laporan') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('laporan'); ?> me-2"></i>
                    <span><?= $wording['laporan']; ?></span>
                </a>

                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'laporan') ? 'show' : ''; ?>"
                    id="laporanSubmenu">
                    <?php if (can_access_menu('laporan')): ?>
                        <li>
                            <a href="<?= site_url('laporan/stok'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_stok') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('gudang_stok'); ?> me-2"></i>
                                <span>Laporan Stok</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('laporan')): ?>
                        <li>
                            <a href="<?= site_url('laporan/stok_card'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_stok_card') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('laporan_stok_card'); ?> me-2"></i>
                                <span>Kartu Stok</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('laporan')): ?>
                        <li>
                            <a href="<?= site_url('laporan/barang_proses'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_barang_proses') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('barang_proses'); ?> me-2"></i>
                                <span>Antar Gudang</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (can_access_menu('laporan_history_proyek')): ?>
                        <li>
                            <a href="<?= site_url('laporan/history_proyek'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_history_proyek') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('history_proyek'); ?> me-2"></i>
                                <span>History Proyek</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (can_access_menu('laporan_history_barang')): ?>
                        <li>
                            <a href="<?= site_url('laporan/history_barang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_history_barang') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('history_barang'); ?> me-2"></i>
                                <span>History Barang</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>




        <!-- Pengaturan -->
        <?php $usernames = strtolower($this->session->userdata('username')); ?>

        <?php if ($usernames == 'adminwms'): ?>
            <li class="sidebar-item <?= ($active_menu == 'pengaturan') ? 'active' : ''; ?>">
                <a href="#pengaturanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= ($active_menu == 'pengaturan') ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= get_menu_icon('pengaturan'); ?> me-2"></i>
                    <span><?= $wording['pengaturan']; ?></span>
                </a>
                <ul class="collapse list-unstyled ps-0 <?= ($active_menu == 'pengaturan') ? 'show' : ''; ?>"
                    id="pengaturanSubmenu">
                    <?php if (can_access_menu('web_pengaturan')): ?>
                        <li><a href="<?= site_url('pengaturan'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'web') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('web_pengaturan'); ?> me-2"></i>Web</a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('api_pengaturan')): ?>
                        <li><a href="<?= site_url('api'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'api') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('api_pengaturan'); ?> me-2"></i>API</a>
                        </li>
                    <?php endif; ?>

                    <?php if (can_access_menu('logo_pengaturan')): ?>
                        <li><a href="<?= site_url('logo'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'logo') ? 'active' : ''; ?>">
                                <i class="<?= get_menu_icon('logo_pengaturan'); ?> me-2"></i>Logo</a>
                        </li>
                    <?php endif; ?>
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
    <div class="container-fluid pt-3">