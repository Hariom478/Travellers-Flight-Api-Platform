<?php
use CodeIgniter\I18n\Time;
/**
 * ----------------------------------------------
 *  API Request (Return Json Format)
 * ---------------------------------------------
 */
function Request($data, $url, $credential)
{
    $request_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-client-id:' . $credential['x_client_id'] . '', 'x-client-secret:' . $credential['x_client_secret'] . '', 'X-Cf-Signature:' . $credential['signature'] . '', 'x-api-version:' . 'v2' . ''));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function RequestPan($data, $url, $credential)
{
    $request_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-client-id:' . $credential['x_client_id'] . '', 'x-client-secret:' . $credential['x_client_secret'] . '', 'X-Cf-Signature:' . $credential['signature'] . '', 'x-api-version:' . 'v1' . ''));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function getSignature($clientId)
{

    $publicKey = openssl_pkey_get_public(file_get_contents(FCPATH . "app/Libraries/accountId_31859_public_key.pem"));
    $encodedData = $clientId . "." . strtotime("now");
    return encrypt_RSA($encodedData, $publicKey);
}

function encrypt_RSA($plainData, $publicKey)
{
    if (openssl_public_encrypt($plainData, $encrypted, $publicKey,
        OPENSSL_PKCS1_OAEP_PADDING))
        $encryptedData = base64_encode($encrypted);
    else return NULL;
    return $encryptedData;
}


if (!function_exists('login_date_format_with_time')) {
    function login_date_format_with_time($strtotime)
    {
        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        return $date_obj->format('Y-m-d\TH:i:s');
    }
}

if (!function_exists('time_interval')) {
    function time_interval($creation_time, $current)
    {
        $dateTimeObject1 = date_create($creation_time);
        $dateTimeObject2 = date_create($current);
        $interval = date_diff($dateTimeObject1, $dateTimeObject2);

        $minutes = $interval->days * 24 * 60;
        $minutes += $interval->h * 60;
        $minutes += $interval->i;

        return $minutes;
    }
}