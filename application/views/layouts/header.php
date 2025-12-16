<!DOCTYPE html>
<html lang="id">
<?php $config = get_app_config(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . $config['app_name'] : $config['app_name']; ?></title>

    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/all.min.css'); ?>" rel="stylesheet">

    <!-- Select2 -->
    <link href="<?php echo base_url('assets/select2/select2.min.css'); ?>" rel="stylesheet" />

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> -->

    <!-- SaaSpal Main CSS -->
    <link href="<?php echo base_url('assets/temp/css/main.css'); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/toastr/toastr.min.css'); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap5.min.css'); ?>">

</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">