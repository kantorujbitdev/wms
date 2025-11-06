<?php
$role_id = $this->session->userdata('role');
$user_id = $this->session->userdata('user_id');
$usernames = $this->session->userdata('username');
?>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm topbar sticky-top">
    <div class="container-fluid">
        <button id="sidebarToggle" class="btn btn-primary me-3 d-lg-none">
            <i class="fas fa-bars"></i>
        </button>
        <button id="sidebarToggleDesktop" class="btn btn-outline-primary me-3 d-none d-lg-inline-block">
            <i class="fas fa-bars"></i>
        </button>

        <!-- User Profile Section - Always Visible -->
        <div class="ms-auto d-flex align-items-center">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center p-0" href="#"
                    id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle user-icon"></i>
                    <div class="user-details ms-2 d-none d-sm-block">
                        <span class="user-name d-block"><?php echo $usernames ?> (<?php echo $role_id ?>)</span>
                    </div>
                    <!-- For mobile only - show username inline -->
                    <span class="user-name-mobile d-sm-none ms-2"><?php echo $usernames ?></span>
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
            </div>
        </div>
    </div>
</nav>