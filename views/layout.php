<?php

use SolidPHP\Section;
use SolidPHP\Route;
use SolidPHP\Debug;
use SolidPHP\JWT;

$user = JWT::getUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Section::yield('title') ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= Route::is('public/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= Route::is('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= Route::is('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= Route::is('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= Route::is('public/dist/css/adminlte.min.css') ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= Route::is('public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php
        Section::include(__DIR__ . '/components/navbar.php');
        Section::include(__DIR__ . '/components/sidebar.php', ['user' => $user]);
        Section::yield('content');
        ?>

        <footer class="main-footer">
            <strong>&copy; <a href="https://github.com/rizmulya">rizmulya</a>.</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>IoT Developer</b>
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?= Route::is('public/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= Route::is('public/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= Route::is('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?= Route::is('public/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/jszip/jszip.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/pdfmake/pdfmake.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/pdfmake/vfs_fonts.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
    <script src="<?= Route::is('public/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
    <!-- overlayScrollbars -->
    <script src="<?= Route::is('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= Route::is('public/dist/js/adminlte.js') ?>"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <?php if (APP_DEBUG)
        echo Debug::showResponseTime(microtime(true));
    ?>
</body>

</html>