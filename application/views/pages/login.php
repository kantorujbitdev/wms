<!DOCTYPE html>
<html lang="en">
<?php $config = get_app_config(); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title . ' - ' . $config['app_name'] : $config['app_name']; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/login_style.css'); ?>" rel="stylesheet">

    <link rel="icon" href="<?php echo base_url(
        $config['app_logo']
    ); ?>" type="image/x-icon">
</head>

<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo">
            <h1><?= $config['app_pt_name'] ?></h1>
            <h2><?= $config['app_name'] ?></h2>
        </div>

        <!-- Display flash messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
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
            <button type="submit" class="btn btn-primary mb-3"><?= $wording['login'] ?></button>
        </div>
        <?php echo form_close(); ?>

        <div class="footer text-center py-3 mt-3">
            <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo"
                style="height: 28px; vertical-align: middle; margin-right: 8px;">
            &copy; <?php echo date('Y'); ?>
            <?= ' ' . $config['app_fullname'] ?> <br>
            <?= ' ' . $config['app_footer_text'] ?> <br>
        </div>

    </div>

    <!-- JS -->
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

    <script>
        // Auto hide alerts
        setTimeout(function () {
            $(".alert").fadeOut('slow');
        }, 3000);
    </script>
</body>

</html>