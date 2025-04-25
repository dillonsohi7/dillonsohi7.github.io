<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$username = 'dennis.wong002@umb.edu';
$password = 123456;
$deviceId = '8b534c60-1667-11f0-8f83-43727cd6bc90';
$keys = 'TempC, TempF,Humidity';

$tokenFile = __DIR__ . '/auth.json';

function login($username, $password, $tokenFile) {
    $data = json_encode(['username' => $username, 'password' => $password]);
    $opts = ['http' => ['method' => 'POST', 'header' => "Content-Type: application/json
", 'content' => $data]];
    $context = stream_context_create($opts);
    $result = file_get_contents('https://thingsboard.cloud/api/auth/login', false, $context);
    $auth = json_decode($result, true);

    if (isset($auth['token'])) {
        $auth['expires'] = time() + 3300;
        file_put_contents($tokenFile, json_encode($auth));
        return $auth;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Login failed"]);
        exit;
    }
}

function getToken($username, $password, $tokenFile) {
    if (!file_exists($tokenFile)) return login($username, $password, $tokenFile);
    $auth = json_decode(file_get_contents($tokenFile), true);

    if (time() < $auth['expires']) return $auth;

    $opts = ['http' => ['method' => 'POST', 'header' => "Content-Type: application/json
Authorization: Bearer {$auth['refreshToken']}
"]];
    $context = stream_context_create($opts);
    $result = file_get_contents('https://thingsboard.cloud/api/auth/token', false, $context);
    $new = json_decode($result, true);

    if (isset($new['token'])) {
        $auth['token'] = $new['token'];
        $auth['expires'] = time() + 3300;
        file_put_contents($tokenFile, json_encode($auth));
        return $auth;
    } else {
        return login($username, $password, $tokenFile);
    }
}

$auth = getToken($username, $password, $tokenFile);
$url = "https://thingsboard.cloud/api/plugins/telemetry/DEVICE/$deviceId/values/timeseries?keys=$keys";
$headers = ["http" => ["method" => "GET", "header" => "X-Authorization: Bearer {$auth['token']}
"]];
$context = stream_context_create($headers);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to retrieve data"]);
} else {
    echo $response;
}
?>