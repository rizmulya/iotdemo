<?php

use SolidPHP\Route;
use SolidPHP\Flash;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem IoT | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= Route::is('public/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= Route::is('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= Route::is('public/dist/css/adminlte.min.css') ?>">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Sistem</b> IoT
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <?php if (Flash::has('message')) : ?>
                    <p class="login-box-msg"><?= Flash::get('message'); ?></p>
                <?php endif; ?>

                <form action="<?= Route::is('login') ?>" method="POST" id="loginForm">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <script>
        // document.getElementById('loginForm').addEventListener('submit', async function(e) {
        //     e.preventDefault();
        //     const formData = new FormData(this);
        //     const username = formData.get('username');
        //     const password = formData.get('password');

        //     try {
        //         const response = await fetch(`<?= Route::is('login') ?>`, {
        //             method: 'POST',
        //             body: formData,
        //         });

        //         if (!response.ok) {
        //             throw new Error('Login failed.');
        //         }

        //         const data = await response.json();
        //         if (data.status == 'success') {
        //             location.href = '<?= Route::is('person') ?>';
        //         };
        //     } catch (err) {
        //         console.error(err.message);
        //     }
        // });
    </script>

    <!-- jQuery -->
    <script src="<?= Route::is('public/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= Route::is('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= Route::is('public/dist/js/adminlte.min.js') ?>"></script>
</body>

</html>