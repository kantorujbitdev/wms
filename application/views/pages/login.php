<!DOCTYPE html>
<html lang="en">
<?php
$config = get_app_config();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?= isset($title) ? $title . ' - ' . $config['app_name'] : $config['app_name']; ?></title>

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#2c3e50">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Preload critical resources -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/all.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/login_style.css'); ?>" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet" crossorigin="anonymous">


    <!-- Custom CSS -->
    <link href="<?php echo base_url('assets/css/login_style.css'); ?>" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url($config['app_logo']); ?>" type="image/x-icon">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="<?php echo base_url($config['app_logo']); ?>">


    <style>
        /* Inline critical CSS untuk initial load */
        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .splash-logo {
            width: 170px;
            height: 170px;
            margin-bottom: 20px;
            animation: splashPulse 2s infinite ease-in-out;
        }

        .splash-text {
            color: white;
            font-size: 1.2rem;
            opacity: 0.9;
        }

        @keyframes splashPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }
    </style>
</head>

<body>
    <!-- Splash Screen -->
    <div id="splashScreen" class="splash-screen">
        <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo" class="splash-logo">
        <div class="splash-text">Loading <?= $config['app_name'] ?>...</div>
    </div>

    <!-- Video Background -->
    <video autoplay muted loop playsinline id="bg-video" class="video"
        poster="<?= base_url('assets/img/video-poster.jpg') ?>">
        <source src="<?= base_url('assets/img/ujb-verse2.mp4') ?>" type="video/mp4">
        <source src="<?= base_url('assets/img/ujb-verse2.webm') ?>" type="video/webm">
        Your browser does not support the video tag.
    </video>

    <!-- Main Login Container -->
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
                    <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo"
                        data-src="<?php echo base_url($config['app_logo_blue']); ?>"
                        onerror="this.src='<?php echo base_url('assets/img/logo-fallback.png'); ?>'">
                    <h1><?= $config['app_pt_name'] ?></h1>
                    <h1><?= $config['app_name'] ?></h1>
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
                            autocapitalize="none" minlength="3" maxlength="50" pattern="[A-Za-z0-9_]+"
                            title="Username hanya boleh mengandung huruf, angka, dan underscore">
                        <div class="invalid-feedback">
                            Username harus diisi (minimal 3 karakter)
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group" id="passwordGroup">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="passwordInput" type="password" name="password" class="form-control"
                            placeholder="Password" required aria-label="Password" autocomplete="current-password"
                            minlength="2" maxlength="100">
                        <button id="togglePassword" type="button"
                            class="btn btn-outline-secondary input-group-text btn-eye" aria-pressed="false"
                            title="Tampilkan password" aria-label="Toggle password visibility">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                        <div class="invalid-feedback">
                            Password harus diisi (minimal 6 karakter)
                        </div>
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
                        <img src="<?php echo base_url($config['app_logo_blue']); ?>" alt="Logo"
                            onerror="this.style.display='none'">
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

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

    <script>
        // Set CSS custom properties untuk mobile detection
        document.documentElement.style.setProperty('--is-mobile', window.innerWidth <= 768 ? '1' : '0');

        // Hide splash screen setelah semua konten siap
        window.addEventListener('load', function () {
            setTimeout(function () {
                const splash = document.getElementById('splashScreen');
                if (splash) {
                    splash.style.opacity = '0';
                    setTimeout(() => splash.style.display = 'none', 500);
                }
            }, 1000);
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Elemen DOM
            const pwInput = document.getElementById('passwordInput');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const video = document.getElementById('bg-video');
            const body = document.body;

            // ===== VIDEO BACKGROUND HANDLING =====
            // Handle video autoplay issues
            const handleVideoPlayback = async () => {
                try {
                    if (video) {
                        // Set video quality based on device
                        if (window.innerWidth <= 768) {
                            video.playbackRate = 1.0;
                        }

                        const playPromise = video.play();
                        if (playPromise !== undefined) {
                            await playPromise;
                        }
                    }
                } catch (error) {
                    console.log('Video autoplay blocked, showing fallback');
                    body.classList.add('no-video');

                    // Show fallback image
                    if (video && video.poster) {
                        const fallbackDiv = document.createElement('div');
                        fallbackDiv.className = 'video-fallback';
                        fallbackDiv.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-image: url('${video.poster}');
                            background-size: cover;
                            background-position: center;
                            filter: brightness(0.6);
                            z-index: -1;
                        `;
                        document.body.appendChild(fallbackDiv);
                    }
                }
            };

            // Initialize video
            handleVideoPlayback();

            // ===== PASSWORD TOGGLE =====
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isHidden = pwInput.type === 'password';
                pwInput.type = isHidden ? 'text' : 'password';
                toggleIcon.classList.toggle('fa-eye', !isHidden);
                toggleIcon.classList.toggle('fa-eye-slash', isHidden);
                toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleBtn.title = isHidden ? 'Sembunyikan password' : 'Tampilkan password';

                // Focus kembali ke input
                pwInput.focus();
            });

            // ===== AUTO FOCUS =====
            const usernameField = document.querySelector('input[name="username"]');
            if (usernameField && !usernameField.value) {
                setTimeout(() => {
                    usernameField.focus();

                    // Untuk mobile, scroll ke field
                    if (window.innerWidth <= 768) {
                        setTimeout(() => {
                            usernameField.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }, 300);
                    }
                }, 300);
            }

            // ===== FORM VALIDATION =====
            // Bootstrap validation
            (function () {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
            })()

            // ===== FORM SUBMISSION =====
            loginForm.addEventListener('submit', function (e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }

                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="visually-hidden">Loading...</span>';

                // Simulate network delay untuk UX
                setTimeout(() => {
                    if (!document.querySelector('.alert')) {
                        submitBtn.classList.remove('loading');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i><?= $wording['login'] ?>';
                    }
                }, 3000);
            });

            // ===== AUTO HIDE ALERTS =====
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const fade = new bootstrap.Alert(alert);
                    setTimeout(() => {
                        try {
                            fade.close();
                        } catch (e) {
                            alert.style.opacity = '0';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.parentNode.removeChild(alert);
                                }
                            }, 300);
                        }
                    }, 5000);
                });
            }, 5000);

            // ===== MOBILE KEYBOARD HANDLING =====
            if (window.innerWidth <= 768) {
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    input.addEventListener('focus', function () {
                        setTimeout(() => {
                            this.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center',
                                inline: 'center'
                            });
                        }, 100);
                    });
                });

                // Adjust form on keyboard show/hide
                let viewportHeight = window.innerHeight;
                window.addEventListener('resize', function () {
                    if (window.innerHeight < viewportHeight) {
                        // Keyboard is shown
                        document.querySelector('.login-form').style.paddingBottom = '20px';
                    } else {
                        // Keyboard is hidden
                        document.querySelector('.login-form').style.paddingBottom = '';
                    }
                });
            }

            // ===== PREVENT ZOOM ON MOBILE =====
            document.addEventListener('touchstart', function (e) {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }, { passive: false });

            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (e) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                lastTouchEnd = now;
            }, false);

            // ===== PERFORMANCE OPTIMIZATIONS =====
            // Pause video when tab is not visible
            document.addEventListener('visibilitychange', function () {
                if (video) {
                    if (document.hidden) {
                        video.pause();
                    } else {
                        video.play().catch(() => {
                            body.classList.add('no-video');
                        });
                    }
                }
            });

            // Lazy load non-critical images
            const lazyImages = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.getAttribute('data-src');
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));

            // ===== ENTER KEY SUBMISSION =====
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    const activeElement = document.activeElement;
                    if (activeElement.tagName === 'INPUT' &&
                        !activeElement.type === 'checkbox' &&
                        loginForm.checkValidity()) {
                        e.preventDefault();
                        loginForm.requestSubmit();
                    }
                }
            });

            // ===== ERROR BOUNDARY =====
            window.addEventListener('error', function (e) {
                console.error('Error occurred:', e.error);
                // Bisa ditambahkan error reporting ke server di sini
            });

            // ===== OFFLINE DETECTION =====
            window.addEventListener('offline', function () {
                const offlineAlert = document.createElement('div');
                offlineAlert.className = 'alert alert-warning alert-dismissible fade show';
                offlineAlert.innerHTML = `
                    <i class="fas fa-wifi-slash"></i>
                    <span>Anda sedang offline. Beberapa fitur mungkin tidak tersedia.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.form-container').prepend(offlineAlert);
            });

            window.addEventListener('online', function () {
                const alerts = document.querySelectorAll('.alert-warning');
                alerts.forEach(alert => {
                    if (alert.textContent.includes('offline')) {
                        alert.remove();
                    }
                });
            });
        });

        // Handle resize events efficiently
        let resizeTimeout;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                document.documentElement.style.setProperty('--is-mobile', window.innerWidth <= 768 ? '1' : '0');

                // Adjust video brightness based on screen size
                const video = document.getElementById('bg-video');
                if (video) {
                    if (window.innerWidth <= 768) {
                        video.style.filter = 'brightness(0.6)';
                    } else {
                        video.style.filter = 'brightness(0.7)';
                    }
                }
            }, 250);
        });
    </script>
</body>

</html>