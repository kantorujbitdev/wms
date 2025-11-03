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

    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 30px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-logo img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .login-logo h1 {
            font-size: 22px;
            font-weight: 600;
            color: #007bff;
            margin-bottom: 5px;
        }

        .login-logo h2 {
            font-size: 16px;
            font-weight: 400;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e7e7e7;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .input-group .input-group-text {
            border-radius: 10px;
            background: transparent;
            border: 1px solid #e7e7e7;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .btn-eye {
            border-radius: 0 10px 10px 0;
            border-left: 0;
            border: 1px solid #e7e7e7;
        }

        .btn-primary {
            border-radius: 10px;
            background: linear-gradient(135deg, #007bff, #00c6ff);
            border: none;
            padding: 12px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0069d9, #0090d0);
            transform: translateY(-1px);
        }

        .login-box-msg {
            text-align: center;
            margin-bottom: 20px;
            color: #6c757d;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #adb5bd;
            margin-top: 15px;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .debug-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }

        /* small on mobile tweak */
        @media (max-width: 420px) {
            .login-card {
                padding: 20px;
                margin: 12px;
            }
        }
    </style>

    <link rel="icon" href="<?php echo base_url('assets/img/logo_ujb_no_name_with.png'); ?>" type="image/x-icon">
</head>

<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="<?php echo base_url('assets/img/logo_ujb_no_name.png'); ?>" alt="Logo">
            <h1>PT. Usaha Jayamas Bhakti</h1>
            <h2><?php echo $this->config->item('app_name'); ?></h2>
        </div>

        <!-- Display flash messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Display debug info in development mode -->
        <?php if ($this->session->flashdata('debug_info') && ENVIRONMENT == 'development'): ?>
            <div class="debug-info">
                <strong>Debug Information:</strong><br>
                <?php echo $this->session->flashdata('debug_info'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('auth/login'); ?>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Username"
                value="<?php echo set_value('username'); ?>" required>
        </div>

        <!-- Password input with show/hide feature -->
        <div class="input-group mb-4" id="passwordGroup">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input id="passwordInput" type="password" name="password" class="form-control" placeholder="Password"
                required aria-label="Password">
            <button id="togglePassword" type="button" class="btn btn-outline-secondary input-group-text btn-eye"
                aria-pressed="false" title="Tampilkan password">
                <i id="toggleIcon" class="fas fa-eye"></i>
            </button>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary mb-3">Sign In</button>
        </div>
        <?php echo form_close(); ?>

        <!-- Test API button in development mode
        <?php if (ENVIRONMENT == 'development'): ?>
            <div class="text-center mt-3">
                <a href="<?php echo site_url('auth/test_api'); ?>" class="btn btn-sm btn-outline-info" target="_blank">
                    <i class="fas fa-bug"></i> Test API Connection
                </a>
            </div>
        <?php endif; ?> -->

        <div class="footer text-center py-3 mt-3">
            <img src="<?php echo base_url('assets/img/logo_ujb_no_name.png'); ?>" alt="Logo"
                style="height: 28px; vertical-align: middle; margin-right: 8px;">
            &copy; <?php echo date('Y'); ?> Warehouse Management System<br>
            All Rights Reserved
        </div>

    </div>

    <!-- JS -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

    <script>
        (function () {
            const pwInput = document.getElementById('passwordInput');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');

            // Toggle show/hide password
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isHidden = pwInput.type === 'password';
                pwInput.type = isHidden ? 'text' : 'password';
                toggleIcon.classList.toggle('fa-eye', !isHidden);
                toggleIcon.classList.toggle('fa-eye-slash', isHidden);
                // Accessibility: indicate state
                toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleBtn.title = isHidden ? 'Sembunyikan password' : 'Tampilkan password';
            });

            // Optional: long-press to temporarily show (for mobile)
            let pressTimer = null;
            toggleBtn.addEventListener('mousedown', startPress);
            toggleBtn.addEventListener('touchstart', startPress);
            toggleBtn.addEventListener('mouseup', cancelPress);
            toggleBtn.addEventListener('mouseleave', cancelPress);
            toggleBtn.addEventListener('touchend', cancelPress);

            function startPress(e) {
                // temporary reveal while holding for 450ms
                pressTimer = setTimeout(() => {
                    pwInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }, 450);
            }

            function cancelPress(e) {
                if (pressTimer) {
                    clearTimeout(pressTimer);
                    pressTimer = null;
                }
                // if it was temporarily shown, hide it again
                if (pwInput.type === 'text' && toggleIcon.classList.contains('fa-eye-slash')) {
                    // only hide if the main toggle isn't currently in "visible" mode
                    if (toggleBtn.getAttribute('aria-pressed') === 'false') {
                        pwInput.type = 'password';
                        toggleIcon.classList.remove('fa-eye-slash');
                        toggleIcon.classList.add('fa-eye');
                    }
                }
            }
        })();
    </script>
</body>

</html>