<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm topbar sticky-top">
    <div class="container-fluid">
        <button id="sidebarToggle" class="btn btn-primary me-3 d-lg-none">
            <i class="fas fa-bars"></i>
        </button>
        <button id="sidebarToggleDesktop" class="btn btn-outline-primary me-3 d-none d-lg-inline-block">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center" href="#"
                        id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1 fs-5"></i>
                        <span
                            class="d-none d-md-inline-block"><?= isset($user['name']) ? $user['name'] : 'User'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" id="logoutBtn">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>