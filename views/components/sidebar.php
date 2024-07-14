<?php

use SolidPHP\Route;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Route::is('') ?>" class="brand-link">
        <img src="<?= Route::is('public/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">IoT System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= Route::is('public/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user['fullname'] ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= Route::is("dashboard") ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Route::is("sensor") ?>" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Sensors</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Route::is("device") ?>" class="nav-link">
                        <i class="nav-icon fas fa-laptop-code"></i>
                        <p>Devices</p>
                    </a>
                </li>
                <?php if ($user["role"] === "admin") : ?>
                    <li class="nav-item">
                        <a href="<?= Route::is("user") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= Route::is("logout") ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>