<!DOCTYPE html>
<html lang="id">
<?php $config = get_app_config(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . $config['app_name'] : $config['app_name']; ?></title>

    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">
    <!-- <link rel="icon" href="<?php $config['app_logo']; ?>" type="image/x-icon"> -->

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- SaaSpal Main CSS -->
    <link href="<?php echo base_url('assets/temp/css/main.css'); ?>" rel="stylesheet">

    <!-- jQuery (wajib untuk Select2 dan SweetAlert jika interaktif) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">