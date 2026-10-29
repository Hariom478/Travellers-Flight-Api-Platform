<?php

use CodeIgniter\I18n\Time;
use CodeIgniter\I18n\TimeDifference;


/**
 * ----------------------------------------------
 *  API Request GET (Return Json Format)
 * ---------------------------------------------
 */
if (!function_exists('RequestWithoutAuth')) {
    function RequestWithoutAuth($data, $url)
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
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}




function Request_holiday($data, $url)
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Username:' . credential['Username'] . '', 'Password:' . credential['Password'] . '', 'Btype:' . credential['Btype'] . ''));
    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response, true);
}

function GetRequest($url)
{

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Username:' . credential['Username'] . '', 'Password:' . credential['Password'] . '', 'Btype:' . credential['Btype'] . ''));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);

}

function RequestWithoutAuth($data, $url)
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
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
/**
 * -------------------------------------
 * Time stump to date holiday price  Function
 * -------------------------------------
 */

if (!function_exists('timestamp_to_date_holiday')) {
    function timestamp_to_date_holiday($created_date)
    {
        $timezone=admin_cookie_data()['admin_comapny_detail']['timezone'];
        date_default_timezone_set($timezone);
        $Date = date("d-M-Y", $created_date);
        return date('d-M-Y', strtotime($Date. ' + 1 days'));


    }
}