<?php
define('APP_START', microtime(true));
define('APP_URL', 'http://localhost:8000');
define('APP_DEBUG', 1);

require 'SolidPHP.php';

use SolidPHP\Router;
use SolidPHP\CSRF;
use SolidPHP\JWT;
use SolidPHP\Flash;
use SolidPHP\Route;
use SolidPHP\UrlCryptor;
use SolidPHP\DBMysql;

$app = new Router();

// db
$db = new DBMysql([
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'database' => 'iottestdb',
]);
// define table
$db
    ->table('devices', [
        'serial_number' => 's',
        'jenis_controller' => 's',
        'lokasi' => 's',
        'aktif' => 's'
    ])
    ->table('sensor', [
        'id' => 'i',
        'jenis_sensor' => 's',
        'data_sensor' => 's',
        'waktu' => 's',
        'serial_number' => 's'
    ])
    ->table('user', [
        'username' => 's',
        'password' => 's',
        'nama_lengkap' => 's',
        'hak_akses' => 's',
        'aktif' => 's'
    ]);

// app keys
$cryptor = new UrlCryptor('your-key-here-must-be-32-bytes--');
JWT::setSecretKey('12345678');

// MQTT const
define('MQTT_SERVER', 'wss://xxxxxxxxx.cloud.shiftr.io:443');
define('MQTT_USERNAME', 'xxxxxxxxx');
define('MQTT_PASSWORD', 'xxxxxxxxx');
define('MQTT_CLIENT_ID', 'xxxxxxxxx');


/**
 * Middleware
 */
function useAuth($req, $res, $next)
{
    $jwtToken = JWT::getToken();
    $isValid = JWT::verify($jwtToken);

    if (!$isValid) {
        $res->status(401);
        return $res->redirect(Route::is('login'));
    };

    $next();
}

/**
 * Routes
 */
$app->get('/', function ($req, $res) {
    return $res->redirect(Route::is('login'));
});

$app->get('/login', function ($req, $res) {
    return $res->view(__DIR__ . '/views/pages/login.php');
});

$app->post('/login', function ($req, $res) use ($db, $cryptor) {
    $result = $db->prepare("SELECT * FROM user WHERE username = ? LIMIT 1")
        ->bind_param('s', $req['body']['username'])
        ->execute()
        ->get_result();
    if ($result->num_rows == 0) {
        Flash::set('message', 'User not found!');
        return $res->redirect(Route::is('login'));
    }
    $user = $result->fetch_assoc();
    if (!password_verify($req['body']['password'], $user['password'])) {
        Flash::set('message', 'Password wrong!');
        return $res->redirect(Route::is('login'));
    }

    $token = JWT::generate(['username' => $user['username'], 'fullname' => $user['nama_lengkap'], 'role' => $user['hak_akses']], 2592000); // 30 day in second
    JWT::setCookie($token);
    return $res->redirect(Route::is('dashboard'));
});

$app->get('/logout', function ($req, $res) {
    JWT::deleteCookie();
    return $res->redirect(Route::is('login'));
});

$app->get('/dashboard', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $devices = $db->query('SELECT * FROM devices')->fetch_all(MYSQLI_ASSOC);
    return $res->view(__DIR__ . '/views/pages/dashboard.php', ['devices' => $devices]);
});

$app->get('/device', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $devices = $db->query('SELECT * FROM devices')->fetch_all(MYSQLI_ASSOC);
    $devices = $cryptor->addEncryptedField($devices, 'serial_number', 'enc_serial_number');
    return $res->view(__DIR__ . '/views/pages/device.php', ['devices' => $devices]);
});

$app->get('/device/:serial_number/edit', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $devices = $db->query('SELECT * FROM devices')->fetch_all(MYSQLI_ASSOC);
    $devices = $cryptor->addEncryptedField($devices, 'serial_number', 'enc_serial_number');

    $device = $db->prepare("SELECT * FROM devices WHERE serial_number = ?")
        ->bind_param('s', $cryptor->decrypt($req['params']['serial_number']))
        ->execute()
        ->get_result()
        ->fetch_assoc();
    $device = $cryptor->addEncryptedField($device, 'serial_number', 'enc_serial_number');

    return $res->view(__DIR__ . '/views/pages/device.php', ['devices' => $devices, 'device' => $device]);
});

$app->post('/device', function ($req, $res) use ($db) {
    CSRF::verify($req);

    $db->prepare("INSERT INTO devices ({$db->fields('devices')}) VALUES (?, ?, ?, ?)")
        ->bind_param(
            'devices',
            $req['body']['serial_number'],
            $req['body']['jenis_controller'],
            $req['body']['lokasi'],
            $req['body']['aktif']
        )
        ->execute();

    Flash::set('message', 'added!');
    return $res->redirect(Route::is('device'));
});

$app->put('/device/:serial_number', function ($req, $res) use ($db, $cryptor) {
    CSRF::verify($req);

    $db->prepare("UPDATE devices SET {$db->setClause('devices')} WHERE serial_number = ?")
        ->bind_param(
            'sssss',
            $req['body']['serial_number'],
            $req['body']['jenis_controller'],
            $req['body']['lokasi'],
            $req['body']['aktif'],
            $cryptor->decrypt($req['params']['serial_number']),
        )
        ->execute();

    Flash::set('message', 'updated!');
    return $res->redirect(Route::is('device'));
});

$app->get('/sensor', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $sensors = $db->query('SELECT * FROM sensor')->fetch_all(MYSQLI_ASSOC);
    return $res->view(__DIR__ . '/views/pages/sensor.php', ['sensors' => $sensors]);
});

$app->get('/user', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $users = $db->query('SELECT * FROM user')->fetch_all(MYSQLI_ASSOC);
    $users = $cryptor->addEncryptedField($users, 'username', 'enc_username');
    return $res->view(__DIR__ . '/views/pages/user.php', ['users' => $users]);
});

$app->get('/user/:username/edit', 'useAuth', function ($req, $res) use ($db, $cryptor) {
    $users = $db->query('SELECT * FROM user')->fetch_all(MYSQLI_ASSOC);
    $users = $cryptor->addEncryptedField($users, 'username', 'enc_username');

    $user = $db->prepare("SELECT * FROM user WHERE username = ?")
        ->bind_param('s', $cryptor->decrypt($req['params']['username']))
        ->execute()
        ->get_result()
        ->fetch_assoc();
    $user = $cryptor->addEncryptedField($user, 'username', 'enc_username');

    return $res->view(__DIR__ . '/views/pages/user.php', ['users' => $users, 'user' => $user]);
});

$app->post('/user', function ($req, $res) use ($db) {
    CSRF::verify($req);

    $db->prepare("INSERT INTO user ({$db->fields('user')}) VALUES (?, ?, ?, ?, ?)")
        ->bind_param(
            'user',
            $req['body']['username'],
            password_hash($req['body']['password'], PASSWORD_DEFAULT),
            $req['body']['nama_lengkap'],
            $req['body']['hak_akses'],
            $req['body']['aktif'],
        )
        ->execute();

    Flash::set('message', 'added!');
    return $res->redirect(Route::is('user'));
});

$app->put('/user/:username', function ($req, $res) use ($db, $cryptor) {
    CSRF::verify($req);

    $password = !empty($req['body']['password']) ? password_hash($req['body']['password'], PASSWORD_DEFAULT) : null;
    $params = [
        $req['body']['username'],
        $req['body']['nama_lengkap'],
        $req['body']['hak_akses'],
        $req['body']['aktif'],
        $cryptor->decrypt($req['params']['username'])
    ];
    if ($password) array_splice($params, 1, 0, $password);

    $setClause = $password ? $db->setClause('user') : $db->setClause('user', ['password']);
    $types = str_repeat('s', count($params));

    $db->prepare("UPDATE user SET {$setClause} WHERE username = ?")
        ->bind_param($types, ...$params)
        ->execute();

    Flash::set('message', 'updated!');
    return $res->redirect(Route::is('user'));
});


$app->error(function (Exception $e, $res) {
    return $res->send('path not found', 404);
});


$app->start();

$db->shutdown();
