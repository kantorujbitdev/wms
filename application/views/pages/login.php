<!DOCTYPE html>
<html lang="en">
<?php
$config = get_app_config();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?= isset($title) ? $title . ' - ' . $config['app_name'] : $config['app_name']; ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link href="<?php echo base_url('assets/css/login_style.css'); ?>" rel="stylesheet">

    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">
</head>

<body>
    <div class="login-container">
        <!-- Side dengan gambar gudang - Hanya tampil di desktop -->
        <div class="login-side">
            <div class="login-side-content">
                <h1 class="side-title"><?= $config['app_pt_name'] ?></h1>
                <p class="side-subtitle">
                    Sistem Manajemen Gudang Terintegrasi untuk Efisiensi Operasional dan Kontrol Inventaris yang Lebih
                    Baik
                </p>

                <ul class="features-list">
                    <li><i class="fas fa-chart-line"></i> Monitoring Inventaris Real-time</li>
                    <li><i class="fas fa-robot"></i> Manajemen Stok Otomatis</li>
                    <li><i class="fas fa-file-alt"></i> Laporan dan Analytics Lengkap</li>
                    <li><i class="fas fa-users-cog"></i> Multi-level Akses Pengguna</li>
                    <li><i class="fas fa-shipping-fast"></i> Manajemen Distribusi</li>
                </ul>

                <!-- Info untuk mobile (hidden) -->
                <div class="mobile-info d-none">
                    <div class="text-center mt-4">
                        <img src="<?php echo base_url($config['app_logo']); ?>" alt="Logo" style="height: 50px;">
                        <h4 class="mt-3"><?= $config['app_name'] ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Login - Fokus utama di mobile -->
        <div class="login-form">
            <div class="form-container">
                <div class="login-logo">
                    <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo">
                    <h1><?= $config['app_pt_name'] ?></h1>
                    <h2><?= $config['app_name'] ?></h2>
                </div>

                <!-- Display flash messages -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $this->session->flashdata('error'); ?></span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $this->session->flashdata('success'); ?></span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php echo form_open('auth/login', ['id' => 'loginForm']); ?>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Username"
                            value="<?php echo set_value('username'); ?>" required autocomplete="username"
                            autocapitalize="none">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group" id="passwordGroup">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="passwordInput" type="password" name="password" class="form-control"
                            placeholder="Password" required aria-label="Password" autocomplete="current-password">
                        <button id="togglePassword" type="button"
                            class="btn btn-outline-secondary input-group-text btn-eye" aria-pressed="false"
                            title="Tampilkan password">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login btn-primary" id="submitBtn">
                        <i class="fas fa-sign-in-alt me-2"></i><?= $wording['login'] ?>
                    </button>
                </div>

                <?php echo form_close(); ?>

                <div class="login-footer">
                    <div class="footer-text">
                        <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo">
                        &copy; <?php echo date('Y'); ?>
                        <?= ' ' . $config['app_fullname'] ?>
                    </div>
                    <div class="footer-text mb-2">
                        <?= $config['app_footer_text'] ?>
                    </div>
                    <?php if (isset($config['app_version'])): ?>
                        <div class="version">
                            <i class="fas fa-code-branch me-1"></i> v<?= $config['app_version'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwInput = document.getElementById('passwordInput');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            // Toggle show/hide password
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isHidden = pwInput.type === 'password';
                pwInput.type = isHidden ? 'text' : 'password';
                toggleIcon.classList.toggle('fa-eye', !isHidden);
                toggleIcon.classList.toggle('fa-eye-slash', isHidden);
                toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleBtn.title = isHidden ? 'Sembunyikan password' : 'Tampilkan password';
            });

            // Auto focus on username field
            const usernameField = document.querySelector('input[name="username"]');
            if (usernameField && !usernameField.value) {
                setTimeout(() => usernameField.focus(), 300);
            }

            // Form submission loading animation
            loginForm.addEventListener('submit', function () {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 3000);
            });

            // Auto hide alerts after 5 seconds
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const fade = new bootstrap.Alert(alert);
                    setTimeout(() => fade.close(), 5000);
                });
            }, 5000);

            // Handle mobile keyboard
            if (window.innerWidth <= 768) {
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('focus', function () {
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    });
                });
            }

            // Prevent zoom on mobile
            document.addEventListener('touchstart', function (e) {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }, { passive: false });
        });
    </script>
</body>

</html>