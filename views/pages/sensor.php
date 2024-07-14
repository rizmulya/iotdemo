<?php

use SolidPHP\Section;
use SolidPHP\Filter;

Section::extends(__DIR__ . '/../layout.php');

Section::set('title', 'Sensor');

Section::start('content');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Sensor</h1>
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
                            <h3 class="card-title">Riwayat Sensor</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Jenis Sensor</th>
                                        <th>Data Sensor</th>
                                        <th>Waktu</th>
                                        <th>Serial Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sensors as $sensor) : ?>
                                        <tr>
                                            <td><?= Filter::out($sensor['id']) ?></td>
                                            <td><?= Filter::out($sensor['jenis_sensor']) ?></td>
                                            <td><?= Filter::out($sensor['data_sensor']) ?></td>
                                            <td><?= Filter::out($sensor['waktu']) ?></td>
                                            <td><?= Filter::out($sensor['serial_number']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
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