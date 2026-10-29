<?php
use CodeIgniter\I18n\Time;


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