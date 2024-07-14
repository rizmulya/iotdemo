<?php

use SolidPHP\Section;
use SolidPHP\Route;
use SolidPHP\Filter;
use SolidPHP\CSRF;
use SolidPHP\Flash;

Section::extends(__DIR__ . '/../layout.php');

Section::set('title', 'User');

Section::start('content');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Pengguna</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Daftar Pengguna
                                <?php if (Flash::has('message')) : ?>
                                    <b>, <?= Flash::get('message'); ?></b>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Hak Akses</th>
                                        <th>Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user_i) : ?>
                                        <tr>
                                            <td><?= Filter::out($user_i['username']) ?></td>
                                            <td><?= Filter::out($user_i['nama_lengkap']) ?></td>
                                            <td><?= Filter::out($user_i['hak_akses']) ?></td>
                                            <td><?= Filter::out($user_i['aktif']) ?></td>
                                            <td><a href="<?= Route::is('user/') . Filter::out($user_i['enc_username']) . '/edit' ?>"><i class="fas fa-edit"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <?php if (!Route::contains('edit')) : ?>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Tambah Data</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" action="<?= Route::is('user') ?>">
                                <?= CSRF::token() ?>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Tidak Boleh Ada Yang Sama">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Hak Akses</label>
                                        <select class="form-control" name="hak_akses">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Aktif</label>
                                        <select class="form-control" name="aktif">
                                            <option value="ya">
                                                Ya
                                            </option>
                                            <option value="tidak">
                                                Tidak
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>

                    <?php else : ?>

                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Ubah Data</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="post" action="<?= Route::is('user/'). Filter::out($user['enc_username']) ?>">
                                <?= CSRF::token('PUT') ?>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="<?= Filter::out($user['username']) ?>" placeholder="Tidak Boleh Ada Yang Sama">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="<?= Filter::out($user['nama_lengkap']) ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Hak Akses</label>
                                        <select class="form-control" name="hak_akses">
                                            <option value="user" <?= Filter::out($user["hak_akses"]) == "user" ? "selected" : "" ?>>
                                                User
                                            </option>
                                            <option value="admin" <?= Filter::out($user["hak_akses"]) == "admin" ? "selected" : "" ?>>
                                                Admin
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Aktif</label>
                                        <select class="form-control" name="aktif">
                                            <option value="ya" <?= Filter::out($user["aktif"]) == "ya" ? "selected" : "" ?>>
                                                Ya
                                            </option>
                                            <option value="tidak" <?= Filter::out($user["aktif"]) == "tidak" ? "selected" : "" ?>>
                                                Tidak
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">Ubah</button>
                                </div>
                            </form>
                        </div>

                    <?php endif; ?>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<?php Section::end('content'); ?>