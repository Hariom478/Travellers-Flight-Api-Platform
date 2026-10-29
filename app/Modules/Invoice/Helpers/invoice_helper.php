<?php

use CodeIgniter\I18n\Time;
use CodeIgniter\I18n\TimeDifference;

/**
 * ----------------------------------------------
 *  API Request (Return Json Format)
 * ---------------------------------------------
 */
if (!function_exists('Request')) {
    function Request($data, $url, $service)
    {
        $request_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Username:' . credential['Username'] . '', 'Password:' . credential['Password'] . '', 'Btype:' . credential['Btype'] . ''));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
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
        //curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
/**
 * ----------------------------------------------
 *  Get Date Difference 
 * ---------------------------------------------
 */
if (!function_exists('invoice_date_diff')) {
    function invoice_date_diff($from, $to)
    {
        $current = Time::parse($from, 'Asia/Kolkata');

        $test    = Time::parse($to, 'Asia/Kolkata');

        $diff = $current->difference($test);

        return $diff->getDays();
        //return $diff->getDays();
    }
}
