<?php
$config = get_app_config();
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">

    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="<?= site_url('dashboard'); ?>"
            class="text-decoration-none text-white fw-bold fs-5 d-flex align-items-center">
            <img src="<?= base_url($config['app_logo']); ?>" alt="Logo"
                style="height: 28px; vertical-align: middle; margin-right: 8px;">
            <span><?= htmlspecialchars($config['app_name']); ?></span>
        </a>
    </div>

    <ul class="list-unstyled components mt-3 px-2">

        <!-- ========================================================= -->
        <!-- DASHBOARD -->
        <!-- ========================================================= -->
        <?php if ($can_access_menu('dashboard')): ?>
            <li class="sidebar-item <?= ($active_menu == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?= site_url('dashboard'); ?>" class="sidebar-link">
                    <i class="<?= $get_menu_icon('dashboard'); ?> me-2"></i>
                    <span><?= $wording['dashboard']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- MASTER DATA -->
        <!-- ========================================================= -->
        <?php
        $master_data_open = in_array($active_menu, ['user', 'customer', 'supplier', 'proyek', 'gudang', 'barang']);

        $barang_open = ($active_menu == 'barang');

        $barang_access =
            $can_access_menu('barang') ||
            $can_access_menu('tipe_produk') ||
            $can_access_menu('tipe_satuan') ||
            $can_access_menu('produk');

        $master_data_access =
            $can_access_menu('user') ||
            $can_access_menu('customer') ||
            $can_access_menu('supplier') ||
            $can_access_menu('proyek') ||
            $can_access_menu('gudang') ||
            $barang_access;
        ?>

        <?php if ($master_data_access): ?>
            <li class="sidebar-item <?= $master_data_open ? 'active' : ''; ?>">

                <!-- MASTER DATA LEVEL 1 -->
                <a href="#masterDataSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= $master_data_open ? 'true' : 'false'; ?>" class="sidebar-link dropdown-toggle">
                    <i class="fas fa-database me-2"></i>
                    <span>Master Data</span>
                </a>

                <!-- MASTER DATA LEVEL 2 -->
                <ul id="masterDataSubmenu" class="collapse list-unstyled ps-0 <?= $master_data_open ? 'show' : ''; ?>">

                    <!-- USER -->
                    <?php if ($can_access_menu('user')): ?>
                        <li class="sidebar-submenu-item">
                            <a href="<?= site_url('user'); ?>"
                                class="sidebar-sublink <?= ($active_menu == 'user') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('user'); ?> me-2"></i>
                                <span><?= $wording['user']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- CUSTOMER -->
                    <?php if ($can_access_menu('customer')): ?>
                        <li class="sidebar-submenu-item">
                            <a href="<?= site_url('customer'); ?>"
                                class="sidebar-sublink <?= ($active_menu == 'customer') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('customer'); ?> me-2"></i>
                                <span><?= $wording['customer']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- SUPPLIER -->
                    <?php if ($can_access_menu('supplier')): ?>
                        <li class="sidebar-submenu-item">
                            <a href="<?= site_url('supplier'); ?>"
                                class="sidebar-sublink <?= ($active_menu == 'supplier') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('supplier'); ?> me-2"></i>
                                <span><?= $wording['supplier']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- PROYEK -->
                    <?php if ($can_access_menu('gudang_project')): ?>
                        <li class="sidebar-submenu-item">
                            <a href="<?= site_url('gudang/gudang_project'); ?>"
                                class="sidebar-sublink <?= ($active_menu == 'proyek') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('gudang_project'); ?> me-2"></i>
                                <span><?= $wording['proyek']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- GUDANG -->
                    <?php if ($can_access_menu('gudang')): ?>
                        <li class="sidebar-submenu-item">
                            <a href="<?= site_url('gudang'); ?>"
                                class="sidebar-sublink <?= ($active_menu == 'gudang') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('gudang'); ?> me-2"></i>
                                <span><?= $wording['gudang']; ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- BARANG - LEVEL 2 -->
                    <?php if ($barang_access): ?>
                        <li class="sidebar-submenu-item">
                            <a href="#barangSubmenu" data-bs-toggle="collapse"
                                aria-expanded="<?= $barang_open ? 'true' : 'false'; ?>"
                                class="sidebar-sublink dropdown-toggle <?= $barang_open ? 'parent-active' : ''; ?>">
                                <i class="<?= $get_menu_icon('barang'); ?> me-2"></i>
                                <span>Barang</span>
                            </a>

                            <!-- BARANG LEVEL 3 -->
                            <ul id="barangSubmenu"
                                class="collapse list-unstyled sidebar-subsublink-container <?= $barang_open ? 'show' : ''; ?>">

                                <?php if ($can_access_menu('tipe_produk')): ?>
                                    <li>
                                        <a href="<?= site_url('barang/tipe_produk'); ?>"
                                            class="sidebar-subsublink <?= ($active_menu == 'barang' && $active_submenu == 'tipe_produk') ? 'active' : ''; ?>">
                                            <i class="<?= $get_menu_icon('tipe_produk'); ?> me-2"></i>
                                            <span><?= $wording['tipe_produk']; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($can_access_menu('tipe_satuan')): ?>
                                    <li>
                                        <a href="<?= site_url('barang/tipe_satuan'); ?>"
                                            class="sidebar-subsublink <?= ($active_menu == 'barang' && $active_submenu == 'tipe_satuan') ? 'active' : ''; ?>">
                                            <i class="<?= $get_menu_icon('tipe_satuan'); ?> me-2"></i>
                                            <span><?= $wording['tipe_satuan']; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($can_access_menu('produk')): ?>
                                    <li>
                                        <a href="<?= site_url('barang'); ?>"
                                            class="sidebar-subsublink <?= ($active_menu == 'barang' && $active_submenu == 'produk') ? 'active' : ''; ?>">
                                            <i class="<?= $get_menu_icon('produk'); ?> me-2"></i>
                                            <span><?= $wording['barang']; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>


        <!-- ========================================================= -->
        <!-- RAP -->
        <!-- ========================================================= -->
        <?php if ($can_access_menu('rap')): ?>
            <li class="sidebar-item <?= ($active_menu == 'rap') ? 'active' : ''; ?>">
                <a href="<?= site_url('rap'); ?>" class="sidebar-link">
                    <i class="<?= $get_menu_icon('rap'); ?> me-2"></i>
                    <span><?= $wording['rap']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- STOK GUDANG -->
        <!-- ========================================================= -->
        <?php if ($can_access_menu('gudang_stok')): ?>
            <li class="sidebar-item <?= ($active_menu == 'gudang_stok') ? 'active' : ''; ?>">
                <a href="<?= site_url('gudang_stok'); ?>" class="sidebar-link">
                    <i class="<?= $get_menu_icon('gudang_stok'); ?> me-2"></i>
                    <span><?= $wording['gudang_stok']; ?></span>
                </a>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- LAPORAN -->
        <!-- ========================================================= -->
        <?php
        $laporan_open = ($active_menu == 'laporan');

        $laporan_access =
            $can_access_menu('laporan') ||
            $can_access_menu('laporan_history_proyek') ||
            $can_access_menu('laporan_history_barang');
        ?>
        <?php if ($laporan_access): ?>
            <li class="sidebar-item <?= $laporan_open ? 'active' : ''; ?>">
                <a href="#laporanSubmenu" data-bs-toggle="collapse" aria-expanded="<?= $laporan_open ? 'true' : 'false'; ?>"
                    class="sidebar-link dropdown-toggle">
                    <i class="<?= $get_menu_icon('laporan'); ?> me-2"></i>
                    <span><?= $wording['laporan']; ?></span>
                </a>

                <ul id="laporanSubmenu" class="collapse list-unstyled ps-0 <?= $laporan_open ? 'show' : ''; ?>">

                    <?php if ($can_access_menu('laporan')): ?>
                        <li>
                            <a href="<?= site_url('laporan/stok'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_stok') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('gudang_stok'); ?> me-2"></i>
                                <span>Stok</span>
                            </a>
                        </li>

                        <li>
                            <a href="<?= site_url('laporan/stok_card'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_stok_card') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('laporan_stok_card'); ?> me-2"></i>
                                <span>Kartu Stok</span>
                            </a>
                        </li>

                        <li>
                            <a href="<?= site_url('laporan/barang_proses'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_barang_proses') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('laporan_barang_proses'); ?> me-2"></i>
                                <span>Barang Proses</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('laporan_history_proyek')): ?>
                        <li>
                            <a href="<?= site_url('laporan/history_proyek'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_history_proyek') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('laporan_history_proyek'); ?> me-2"></i>
                                <span>Histori Proyek</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('laporan_history_barang')): ?>
                        <li>
                            <a href="<?= site_url('laporan/history_barang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'laporan_history_barang') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('laporan_history_barang'); ?> me-2"></i>
                                <span>Histori Barang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- PENERIMAAN -->
        <!-- ========================================================= -->
        <?php $penerimaan_open = ($active_menu == 'penerimaan'); ?>
        <?php if ($can_access_menu('penerimaan')): ?>
            <li class="sidebar-item <?= $penerimaan_open ? 'active' : ''; ?>">
                <a href="#penerimaanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= $penerimaan_open ? 'true' : 'false'; ?>" class="sidebar-link dropdown-toggle">
                    <i class="<?= $get_menu_icon('penerimaan'); ?> me-2"></i>
                    <span><?= $wording['penerimaan']; ?></span>
                </a>

                <ul id="penerimaanSubmenu" class="collapse list-unstyled ps-0 <?= $penerimaan_open ? 'show' : ''; ?>">

                    <?php if ($can_access_menu('penerimaan_antar_gudang')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/antar_gudang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'penerimaan_antar_gudang') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('penerimaan_antar_gudang'); ?> me-2"></i>
                                <span>Antar Gudang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('supplier_penerimaan')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/dari_supplier'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'dari_supplier') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('supplier_penerimaan'); ?> me-2"></i>
                                <span>Supplier</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('pengguna_penerimaan')): ?>
                        <li>
                            <a href="<?= site_url('penerimaan/dari_pengguna'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'dari_pengguna') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('pengguna_penerimaan'); ?> me-2"></i>
                                <span>Pengguna Proyek</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- PENGIRIMAN -->
        <!-- ========================================================= -->
        <?php $pengiriman_open = ($active_menu == 'pengiriman'); ?>
        <?php if ($can_access_menu('pengiriman')): ?>
            <li class="sidebar-item <?= $pengiriman_open ? 'active' : ''; ?>">
                <a href="#pengirimanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= $pengiriman_open ? 'true' : 'false'; ?>" class="sidebar-link dropdown-toggle">
                    <i class="<?= $get_menu_icon('pengiriman'); ?> me-2"></i>
                    <span><?= $wording['pengiriman']; ?></span>
                </a>

                <ul id="pengirimanSubmenu" class="collapse list-unstyled ps-0 <?= $pengiriman_open ? 'show' : ''; ?>">

                    <?php if ($can_access_menu('pengiriman_antar_gudang')): ?>
                        <li>
                            <a href="<?= site_url('pengiriman/antar_gudang'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'pengiriman_antar_gudang') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('pengiriman_antar_gudang'); ?> me-2"></i>
                                <span>Antar Gudang</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('penggunaan')): ?>
                        <li>
                            <a href="<?= site_url('pengiriman/penggunaan'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'penggunaan') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('penggunaan'); ?> me-2"></i>
                                <span>Pengguna Proyek</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- PENGATURAN -->
        <!-- ========================================================= -->
        <?php
        $username = strtolower($this->session->userdata('username'));
        $pengaturan_open = ($active_menu == 'pengaturan');
        ?>
        <?php if ($username == 'adminwms'): ?>
            <li class="sidebar-item <?= $pengaturan_open ? 'active' : ''; ?>">
                <a href="#pengaturanSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?= $pengaturan_open ? 'true' : 'false'; ?>" class="sidebar-link dropdown-toggle">
                    <i class="<?= $get_menu_icon('pengaturan'); ?> me-2"></i>
                    <span><?= $wording['pengaturan']; ?></span>
                </a>

                <ul id="pengaturanSubmenu" class="collapse list-unstyled ps-0 <?= $pengaturan_open ? 'show' : ''; ?>">

                    <?php if ($can_access_menu('web_pengaturan')): ?>
                        <li>
                            <a href="<?= site_url('pengaturan'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'web_pengaturan') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('web_pengaturan'); ?> me-2"></i>
                                <span>Web</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('api_pengaturan')): ?>
                        <li>
                            <a href="<?= site_url('api'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'api_pengaturan') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('api_pengaturan'); ?> me-2"></i>
                                <span>API</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($can_access_menu('logo_pengaturan')): ?>
                        <li>
                            <a href="<?= site_url('logo'); ?>"
                                class="sidebar-sublink <?= ($active_submenu == 'logo_pengaturan') ? 'active' : ''; ?>">
                                <i class="<?= $get_menu_icon('logo_pengaturan'); ?> me-2"></i>
                                <span>Logo</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>

        <!-- ========================================================= -->
        <!-- DIVIDER -->
        <!-- ========================================================= -->
        <hr class="sidebar-divider my-3">

        <!-- ========================================================= -->
        <!-- FOOTER -->
        <!-- ========================================================= -->
        <li class="sidebar-item mt-auto mb-3">
            <div class="px-3 py-2 text-center small" style="color: #f8f9fa;">
                &copy; <?= date('Y'); ?>
                <span><?= htmlspecialchars($config['app_name']); ?></span>
                <br>
                <span><?= htmlspecialchars($config['app_fullname']); ?></span>
            </div>
        </li>

    </ul>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper" id="content-wrapper">
    <?php $this->load->view('layouts/top_profile'); ?>
    <div class="container-fluid pt-3">