<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?php echo $this->config->item('app_name'); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/css/all.min.css'); ?>" rel="stylesheet">

    <!-- SaaSpal Template CSS -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo img {
            width: 100px;
            height: auto;
        }

        .login-logo h1 {
            font-size: 24px;
            margin-top: 10px;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <div class="login-logo">
            <img src="<?php echo base_url('assets/img/logo_warehouse.png'); ?>" alt="Logo">
            <h1><?php echo $this->config->item('app_name'); ?></h1>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <?php echo form_open('auth/login'); ?>
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username"
                        value="<?php echo set_value('username'); ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block mb-3">Sign In</button>
                    </div>
                </div>
                <?php echo form_close(); ?>


                <small class="login-box-msg text-muted">Copyright &copy; Warehouse Manajemen Sistem
                    <?php echo date('Y'); ?></small>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 5 JS -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>