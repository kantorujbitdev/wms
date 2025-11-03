<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo isset($title) ? $title . ' - ' . $this->config->item('app_name') : $this->config->item('app_name'); ?>
    </title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url('assets/img/logo_ujb_no_name_with.png'); ?>" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/css/all.min.css'); ?>" rel="stylesheet">

    <!-- DataTables -->
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
</head>

<body>
    <div class="wrapper">