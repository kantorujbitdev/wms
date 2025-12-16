<?php
$role_id = $this->session->userdata('role');
$user_id = $this->session->userdata('user_id');
$usernames = $this->session->userdata('username');
$warehouse_id = $this->session->userdata('warehouse_id');
$warehouse_name = $this->session->userdata('warehouse_name');
?>

<!-- LOAD JQUERY FIRST - PENTING! -->
<script src="<?php echo base_url('assets/datatables/jquery-3.6.4.min.js'); ?>"></script>

<!-- Bootstrap 5 JS -->
<script src="<?php echo base_url('assets/bootstrap/bootstrap.bundle.min.js'); ?>"></script>

<!-- DataTables JS -->
<script src="<?php echo base_url('assets/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/dataTables.bootstrap5.min.js'); ?>"></script>

<!-- Select2 -->
<script src="<?php echo base_url('assets/select2/select2.min.js'); ?>"></script>

<!-- Toastr CDN -->
<script src="<?php echo base_url('assets/toastr/toastr.min.js'); ?>"></script>

<!-- SaaSpal Main JS - Load setelah semua dependency -->
<script src="<?php echo base_url('assets/temp/js/main.js'); ?>"></script>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm topbar sticky-top">
    <div class="container-fluid">
        <button id="sidebarToggle" class="btn btn-primary me-3 d-lg-none">
            <i class="fas fa-bars"></i>
        </button>
        <button id="sidebarToggleDesktop" class="btn btn-outline-primary me-3 d-none d-lg-inline-block">
            <i class="fas fa-bars"></i>
        </button>
        <?php if (!is_role(['superadmin'])): ?>
            <span class="fw-bold text-primary d-none d-md-inline">
                (<?php echo strtoupper($warehouse_id); ?>) <?php echo strtoupper($warehouse_name); ?>
            </span>
        <?php endif; ?>

        <!-- User Profile Section - Always Visible -->
        <div class="ms-auto d-flex align-items-center">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center p-0" href="#"
                    id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle user-icon"></i>
                    <div class="user-details ms-2 d-none d-sm-block">
                        <span class="user-name"><?php echo strtoupper($usernames) ?>
                            (<?php echo ucfirst($role_id) ?>)
                        </span>
                    </div>
                    <!-- For mobile only - show username inline -->
                    <span class="user-name-mobile d-sm-none ms-2"><?php echo strtoupper($usernames) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item text-danger" href="#" id="logoutBtn"
                            data-url="<?= site_url('auth/logout'); ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>