<?php

use SolidPHP\Section;
use SolidPHP\Filter;

Section::extends(__DIR__ . '/../layout.php');

Section::set('title', 'Dashboard');

Section::start('content');
?>

<!-- CONNECT USING JAVASCRIPT CLIENT SIDE -->
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script>
    const host = "<?= MQTT_SERVER; ?>";
    const options = {
        keepalive: 30,
        clientId: "<?= MQTT_CLIENT_ID ?>",
        protocolId: "MQTT",
        protocolVersion: 4,
        clean: true,
        username: "<?= MQTT_USERNAME ?>",
        password: "<?= MQTT_PASSWORD ?>",
        reconnectPeriod: 1000,
        connectTimeout: 30 * 1000
    };

    const client = mqtt.connect(host, options);

    client.on("connect", () => {
        client.subscribe(`${options.username}/#`, {
            qos: 1
        });
        document.getElementById('status').innerHTML = "🟢 Connected";
    });

    const onMessage = (serialNumber) => {
        client.on("message", (topic, message) => {
            if (topic === `${options.username}/${serialNumber}/potentiometer`) {
                document.getElementById(`brightnessRange-${serialNumber}`).value = Math.round((message / 4095) * 100);
                document.getElementById(`brightnessPerc-${serialNumber}`).innerHTML = Math.round((message / 4095) * 100) + " %";
            }
            if (topic === `${options.username}/${serialNumber}/led`) {
                document.getElementById(`ledStatus-${serialNumber}`).innerHTML = `${message.toString()}`;
            }
        });
    }

    const publishLed = (serialNumber, data) => {
        client.publish(`${options.username}/${serialNumber}/led`, data, {
            qos: 1,
            retain: true
        });
    }

    const publishBrightness = (serialNumber, value) => {
        const brightnessPerc = Math.round((value / 100) * 4095);
        client.publish(`${options.username}/${serialNumber}/potentiometer`, brightnessPerc.toString(), {
            qos: 1,
            retain: true
        });
        document.getElementById(`brightnessPerc-${serialNumber}`).innerHTML = value + " %";
    }
</script>


<!-- VIEWS -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <?php foreach ($devices as $device) : ?>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Smart Lamp</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Serial Number:</label> <?= Filter::out($device['serial_number']) ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Color:</label> <span id="ledStatus-<?= Filter::out($device['serial_number']) ?>">...</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Brightness</label>
                                            <input type="range" class="form-control-range" id="brightnessRange-<?= Filter::out($device['serial_number']) ?>" min="0" max="100" oninput="publishBrightness('<?= Filter::out($device['serial_number']) ?>', this.value)">
                                            <span id="brightnessPerc-<?= Filter::out($device['serial_number']) ?>">0%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <button type="button" class="btn btn-danger btn-block" onclick="publishLed('<?= Filter::out($device['serial_number']) ?>', 'red')">
                                            <i class="fa fa-lightbulb"></i> Red
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button type="button" class="btn btn-success btn-block" onclick="publishLed('<?= Filter::out($device['serial_number']) ?>', 'green')">
                                            <i class="fa fa-lightbulb"></i> Green
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button type="button" class="btn btn-primary btn-block" onclick="publishLed('<?= Filter::out($device['serial_number']) ?>', 'blue')">
                                            <i class="fa fa-lightbulb"></i> Blue
                                        </button>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button type="button" class="btn btn-secondary btn-block" onclick="publishLed('<?= Filter::out($device['serial_number']) ?>', 'white')">
                                            <i class="fa fa-lightbulb"></i> White
                                        </button>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <button type="button" class="btn btn-dark btn-block" onclick="publishLed('<?= Filter::out($device['serial_number']) ?>', 'off')">
                                            <i class="far fa-lightbulb"></i> Turn Off
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                        <script>
                            onMessage("<?= Filter::out($device['serial_number']) ?>");
                        </script>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
</div>

<?php Section::end('content'); ?>