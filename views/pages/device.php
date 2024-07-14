<?php

use SolidPHP\Section;
use SolidPHP\Route;
use SolidPHP\Flash;
use SolidPHP\CSRF;
use SolidPHP\Filter;

Section::extends(__DIR__ . '/../layout.php');

Section::set('title', 'Device');

Section::start('content');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Perangkat</h1>
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
                                Daftar Perangkat
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
                                        <th>Serial Number</th>
                                        <th>Jenis Controller</th>
                                        <th>Lokasi</th>
                                        <th>Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($devices as $device_i) : ?>
                                        <tr>
                                            <td><?= Filter::out($device_i['serial_number']) ?></td>
                                            <td><?= Filter::out($device_i['jenis_controller']) ?></td>
                                            <td><?= Filter::out($device_i['lokasi']) ?></td>
                                            <td><?= Filter::out($device_i['aktif']) ?></td>
                                            <td><a href="<?= Route::is('device/') . Filter::out($device_i['enc_serial_number']) . '/edit' ?>"><i class="fas fa-edit"></i></a></td>
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
                            <form method="post" action="<?= Route::is('device') ?>">
                                <?= CSRF::token(); ?>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" name="serial_number" placeholder="Tidak Boleh Ada Yang Sama">
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Controller</label>
                                        <input type="text" name="jenis_controller" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <input type="text" name="lokasi" class="form-control">
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
                            <form method="post" action="<?= Route::is('device/' . Filter::out($device['enc_serial_number']) ?? '') ?>">
                                <?= CSRF::token('PUT'); ?>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" name="serial_number" value="<?= Filter::out($device['serial_number']) ?? '' ?>" placeholder="Tidak Boleh Ada Yang Sama">
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Controller</label>
                                        <input type="text" name="jenis_controller" value="<?= Filter::out($device['jenis_controller']) ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <input type="text" name="lokasi" value="<?= Filter::out($device['lokasi']) ?? '' ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Aktif</label>
                                        <select class="form-control" name="aktif">
                                            <option value="ya" <?= Filter::out($device["aktif"]) == "ya" ? "selected" : "" ?>>
                                                Ya
                                            </option>
                                            <option value="tidak" <?= Filter::out($device["aktif"]) == "tidak" ? "selected" : "" ?>>
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