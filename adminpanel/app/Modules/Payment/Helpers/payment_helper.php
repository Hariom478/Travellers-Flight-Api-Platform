<?php
/* get Flight Time */
if (!function_exists('get_flight_time')) {
    function get_flight_time($var)
    {
        list($dt, $tm) = explode('T', $var);
        $tm = substr($tm, 0, 5);
        return $tm;
    }
    }
    /* get Flight Date */
    if (!function_exists('get_flight_date')) {
    function get_flight_date($var)
    {
        list($dt, $tm) = explode('T', $var);
        return date("d M", strtotime($dt));
    }
    }
    /* get convert To Hours Mins from  Duration (in Minutes) */
    if (!function_exists('get_convertToHoursMinsfromMinDuration')) {
    function get_convertToHoursMinsfromMinDuration($minutes)
    {
        return  $hours = intdiv($minutes, 60) . ' h ' . ($minutes % 60) . ' m ';
    }
    }
function calculate_convenience_fee($data,$payment_type,$amount)
{
    $totalfare=0;
    $value=$data[$payment_type.'_value'];
    $type=$data[$payment_type.'_type'];
    if($type=='fixed')
    {
        $value=round_value($value);
        $totalfare=round_value($amount+$value);
    } else {
        $value=round_value((($amount*$value)/100));
        $totalfare=round_value($amount+$value);
    }
    return array('totalfare'=>$totalfare,'conveniencefee'=>$value);
}

/**
 * ------------------------------------------
 * Identification of Payment Mode
 * ------------------------------------------
 */

if (!function_exists('get_payment_mode')) {
    function get_payment_mode($value)
    {
        switch ($value) {
            case "RuPayCreditCard":
                $payment_mode = "CreditCard";
                break;
            case "MastercardCreditCard":
                $payment_mode = "CreditCard";
                break;
            case "AmericanExpressCreditCard":
                $payment_mode = "CreditCard";
                break;

            case "VisaCreditCard":
                $payment_mode = "CreditCard";
                break;


            case "CRDC":
                $payment_mode = "CreditCard";
                break;
            case "DBCRD":
                $payment_mode = "DebitCard";
                break;
            case "NBK":
                $payment_mode = "NetBanking";
                break;
            case "MOBP":
                $payment_mode = "MobileWallet";
                break;
            case "UPI":
                $payment_mode = "UPI";
                break;
            default:
                $payment_mode = "Online";
        }

        return $payment_mode;
    }


}
if (!function_exists('get_card_name')) {
    function get_card_name($value)
    {
        switch ($value) {
            case "visa_credit_card":
                $payment_mode = "Visa";
                break;
            case "american_express_credit_card":
                $payment_mode = "AMEX";
                break;
            case "mastercard_credit_card":
                $payment_mode = "MasterCard";
                break;

            case "rupay_credit_card":
                $payment_mode = "RuPay";
                break;
            default:
                $payment_mode = "";

        }

        return $payment_mode;
    }
}