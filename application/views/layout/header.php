<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo isset($title) ? $title . ' - ' . $this->config->item('app_name') : $this->config->item('app_name'); ?>
    </title>

    <!-- Mantis Template CSS -->
    <link href="<?php echo base_url('assets/mantis/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/mantis/css/icons.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/mantis/css/menu.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/mantis/css/style.css'); ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/mantis/css/all.min.css'); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('assets/mantis/images/favicon.ico'); ?>" type="image/x-icon">
</head>

<body class="app sidebar-mini">
    <!-- App Loading -->
    <div id="app-loading">
        <div class="app-loading-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <!-- /App Loading -->

    <!-- App Header -->
    <div class="app-header header sticky">
        <div class="container-fluid main-container">
            <div class="d-flex align-items-center">
                <a href="<?php echo site_url('dashboard'); ?>" class="logo-horiz">
                    <img src="<?php echo base_url('assets/mantis/images/logo-horiz.png'); ?>" alt="Logo"
                        class="logo-horiz-img">
                </a>
                <a href="#" class="app-sidebar__toggle" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
                <ul class="header-menu nav">
                    <li class="nav-item">
                        <a href="<?php echo site_url('dashboard'); ?>" class="nav-link">
                            <i class="nav-link-icon fa fa-home"></i> Home
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle no-arrow" data-toggle="dropdown" aria-expanded="false">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="#">
                            <div class="dropdown-item-icon bg-success text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                <b>Stok barang menipis</b>
                                <span class="message-time">2 mins ago</span>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="dropdown-item-icon bg-info text-white">
                                <i class="fas fa-info"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                <b>Barang masuk baru</b>
                                <span class="message-time">5 hours ago</span>
                            </div>
                        </a>
                        <a class="dropdown-item" href="#">
                            <div class="dropdown-item-icon bg-warning text-white">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="dropdown-item-desc">
                                <b>Laporan bulanan tersedia</b>
                                <span class="message-time">1 day ago</span>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center">See all notifications</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle no-arrow" data-toggle="dropdown" aria-expanded="false">
                        <div class="icon-circle">
                            <img src="<?php echo base_url('assets/mantis/images/user.png'); ?>" alt="User"
                                class="user-img">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo base_url('assets/mantis/images/user.png'); ?>" alt="User"
                                    class="rounded-circle mr-2" width="40">
                                <div>
                                    <div class="h5 m-0"><?php echo $this->session->userdata('name'); ?></div>
                                    <div class="text-muted"><?php echo ucfirst($this->session->userdata('role')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo site_url('user/detail/' . $this->session->userdata('user_id')); ?>"
                            class="dropdown-item">
                            <i class="dropdown-icon fas fa-user"></i> Profile
                        </a>
                        <a href="<?php echo site_url('pengaturan'); ?>" class="dropdown-item">
                            <i class="dropdown-icon fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo site_url('logout'); ?>" class="dropdown-item">
                            <i class="dropdown-icon fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /App Header -->

    <!-- App Sidebar -->
    <div class="app-sidebar sidebar-dark">
        <div class="sidebar-header">
            <a href="<?php echo site_url('dashboard'); ?>" class="logo">
                <img src="<?php echo base_url('assets/mantis/images/logo.png'); ?>" alt="Logo" class="logo-img">
            </a>
        </div>
        <div class="app-sidebar__body">
            <ul class="nav nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="<?php echo site_url('dashboard'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-header">Master Data</li>

                <!-- Barang -->
                <li class="nav-item">
                    <a href="<?php echo site_url('barang'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'barang') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <span>Barang</span>
                    </a>
                </li>

                <!-- Gudang -->
                <li class="nav-item">
                    <a href="<?php echo site_url('gudang'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'gudang') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <span>Gudang</span>
                    </a>
                </li>

                <!-- Transaksi -->
                <li class="nav-header">Transaksi</li>

                <!-- Barang Masuk -->
                <li class="nav-item">
                    <a href="<?php echo site_url('transaksi/masuk'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'transaksi') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-arrow-down"></i>
                        <span>Barang Masuk</span>
                    </a>
                </li>

                <!-- Barang Keluar -->
                <li class="nav-item">
                    <a href="<?php echo site_url('transaksi/keluar'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'transaksi') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-arrow-up"></i>
                        <span>Barang Keluar</span>
                    </a>
                </li>

                <!-- Transfer Stok -->
                <li class="nav-item">
                    <a href="<?php echo site_url('transaksi/transfer'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'transaksi') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <span>Transfer Stok</span>
                    </a>
                </li>

                <!-- Riwayat Transaksi -->
                <li class="nav-item">
                    <a href="<?php echo site_url('transaksi'); ?>"
                        class="nav-link <?php echo (isset($page) && $page == 'transaksi') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>

                <!-- Laporan -->
                <?php if ($this->session->userdata('role') == 'admin' || $this->session->userdata('role') == 'supervisor'): ?>
                    <li class="nav-header">Laporan</li>

                    <li class="nav-item">
                        <a href="<?php echo site_url('laporan'); ?>"
                            class="nav-link <?php echo (isset($page) && $page == 'laporan') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- System -->
                <?php if ($this->session->userdata('role') == 'admin'): ?>
                    <li class="nav-header">System</li>

                    <li class="nav-item">
                        <a href="<?php echo site_url('user'); ?>"
                            class="nav-link <?php echo (isset($page) && $page == 'user') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo site_url('pengaturan'); ?>"
                            class="nav-link <?php echo (isset($page) && $page == 'pengaturan') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!-- /App Sidebar -->

    <!-- App Main Content -->
    <div class="app-main">
        <div class="app-content">
            <div class="container-fluid main-container p-4">
                <!-- Content Header (Page header) -->
                <div class="mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-sm-12">
                            <h1 class="page-title"><?php echo isset($title) ? $title : 'Dashboard'; ?></h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo site_url('dashboard'); ?>">Home</a>
                                    </li>
                                    <?php if (isset($page)): ?>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $page; ?></li>
                                    <?php endif; ?>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <!-- Page actions can be added here -->
                        </div>
                    </div>
                </div>
                <!-- /Content Header -->

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Warning!</strong> <?php echo $this->session->flashdata('warning'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Info!</strong> <?php echo $this->session->flashdata('info'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Main Content -->