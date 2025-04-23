<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Your ThingsBoard settings
$token = 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJkZW5uaXMud29uZzAwMkB1bWIuZWR1IiwidXNlcklkIjoiNDk2NDFhYzAtMTY3NC0xMWYwLTg2M2ItZjczMGFkZGM2OGJhIiwic2NvcGVzIjpbIlRFTkFOVF9BRE1JTiJdLCJzZXNzaW9uSWQiOiJhYmM4NGY5Yy1mNGFjLTQ3YWYtODg0YS1iNWZiNmNhMjJkYjQiLCJleHAiOjE3NDU0MDU5ODQsImlzcyI6InRoaW5nc2JvYXJkLmNsb3VkIiwiaWF0IjoxNzQ1Mzc3MTg0LCJmaXJzdE5hbWUiOiJEZW5uaXMiLCJsYXN0TmFtZSI6IldvbmciLCJlbmFibGVkIjp0cnVlLCJpc1B1YmxpYyI6ZmFsc2UsImlzQmlsbGluZ1NlcnZpY2UiOmZhbHNlLCJwcml2YWN5UG9saWN5QWNjZXB0ZWQiOnRydWUsInRlcm1zT2ZVc2VBY2NlcHRlZCI6dHJ1ZSwidGVuYW50SWQiOiIxMDVhMjBiMC0xNjY3LTExZjAtODYzYi1mNzMwYWRkYzY4YmEiLCJjdXN0b21lcklkIjoiMTM4MTQwMDAtMWRkMi0xMWIyLTgwODAtODA4MDgwODA4MDgwIn0.gF7i_lZ_Njm9u9fgzPAymzzdXtriV94ZaEOmLRcFTLKA0udPkXjWS9rQcWw_e0yIrRP9h89wxsp3TSJcXbQpbQ';
$deviceId = '8b534c60-1667-11f0-8f83-43727cd6bc90';
$keys = 'TempF,Humidity';

$url = "https://thingsboard.cloud/api/plugins/telemetry/DEVICE/$deviceId/values/timeseries?keys=$keys";

$headers = [
    "X-Authorization: Bearer $token"
];

// Send request to ThingsBoard
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => curl_error($ch)]);
} else {
    echo $response;
}

curl_close($ch);
?>
