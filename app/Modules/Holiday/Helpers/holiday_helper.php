<?php

/**
 * ----------------------------------------------
 *  API Request (Return Json Format)
 * ---------------------------------------------
 */
function Request($data, $url)
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
        $timezone = web_partner_details['timezone'];
        date_default_timezone_set($timezone);
        $Date = date("d-M-Y", $created_date);

        return date('d-M-Y', strtotime($Date));
    }
}

if (!function_exists('timestamp_to_date_holiday_enddate')) {
    function timestamp_to_date_holiday_enddate($created_date)
    {
        $timezone = web_partner_details['timezone'];
        date_default_timezone_set($timezone);
        $Date = date("d-M-Y", $created_date);

        return date('d-M-Y', strtotime($Date . ' +1 day'));
    }
}


function get_fare_price($input, $markup_data, $discount_data, $agent_markup_data, $agent_gst_number, $webpartner_gst_code, $price)
{
    $PublishedPrice = 0;
    $OfferedPrice = 0;
    $ServiceCharges = 0;
    $Tax = $price['Tax'];
    $ServiceCharges = $price['ServiceCharges'];
    $webAddInServiceCharge = 0;
    $wl_agent_markup_value = 0;
    $wl_agent_discount_value = 0;
    $wl_agent_display_markup = "in_tax";
    $agentAddInServiceCharge = 0;
    $agentAddInTax = 0;
    $markup_value = 0;
    $web_discount_value = 0;
    $extra_discount = 0;
    $AgentCommision = 0;
    $Tds = 0;
    $totalpax = 1;
    if (isset($input['totalpax'])) {
        $totalpax = $input['totalpax'];
    }
    $display_markup = "in_tax";
    $agent_gst_state_code = substr($agent_gst_number, 0, 2);
    $Discount = $price['Discount'];
    if (!empty($markup_data) && $markup_data) {
        if ($markup_data['markup_type'] == 'percent') {
            /*   markup apply for PublishedPrice */
            $markup_value = round_value(($price['PublishedPrice'] * abs($markup_data['value'])) / 100);
            /*   markup check max limit  */
            if ($markup_value > $markup_data['max_limit']) {
                $markup_value = $markup_data['max_limit'];
            }
        } elseif ($markup_data['markup_type'] == 'fixed') {
            $markup_value = round_value($markup_data['value'] * $totalpax);
        }
        /* display markup tag */
        if ($markup_data['display_markup'] == 'in_tax') {
            $display_markup = "in_tax";
            $Tax = $price['Tax'] + $markup_value;
        } elseif ($markup_data['display_markup'] == 'in_service_charge') {
            $ServiceCharges = $ServiceCharges + $markup_value;
            $webAddInServiceCharge = $markup_value;
            $display_markup = "in_service_charge";
        }
    }

    if (!empty($discount_data) && $discount_data) {
        if ($discount_data['discount_type'] == 'percent') {
            $web_discount_value = round_value(($price['PublishedPrice'] * abs($discount_data['value'])) / 100);
            if ($web_discount_value > $discount_data['max_limit']) {
                $web_discount_value = $discount_data['max_limit'];
            }
        } else {
            $web_discount_value = round_value($discount_data['value']);
        }
        if (isset($discount_data['extra_discount'])) {
            $extra_discount = $discount_data['extra_discount'];
        }
        $AgentCommision = $web_discount_value;
        $Discount += $extra_discount;
    }

    $gst = gst_calculate('Holiday', $agent_gst_state_code, $webpartner_gst_code, $webAddInServiceCharge);
    $totalgst = $gst['TotalGSTAmount'];
    unset($gst['TotalGSTAmount']);

    $OfferedPrice = round_value($price['PublishedPrice'] + $markup_value - $web_discount_value - $extra_discount + $totalgst);
    $PublishedPrice = round_value($price['PublishedPrice'] + $markup_value + $totalgst);

    if ($OfferedPrice < 0) {
        $web_discount_value = 0;
        $extra_discount = 0;
        $OfferedPrice = round_value($price['PublishedPrice'] + $markup_value + $totalgst);
    }
    if ($OfferedPrice == 0) {
        $web_discount_value = 0;
        $extra_discount = 0;
        $OfferedPrice = round_value($price['PublishedPrice'] + $markup_value + $totalgst);
    }
    /*-- calculate tds-- */
    $Tds = tds_calculate($web_discount_value + $extra_discount);

    if (!empty($agent_markup_data) && $agent_markup_data) {
        if ($agent_markup_data['markup_type'] == 'percent') {
            /*   markup apply for PublishedPrice */
            $wl_agent_markup_value = round_value(($PublishedPrice * abs($agent_markup_data['value'])) / 100);
            /*   markup check max limit  */
            if ($wl_agent_markup_value > $agent_markup_data['max_limit']) {
                $wl_agent_markup_value = $agent_markup_data['max_limit'];
            }
        } elseif ($agent_markup_data['markup_type'] == 'fixed') {
            $wl_agent_markup_value = round_value($agent_markup_data['value'] * $totalpax);
        }
        /* display markup tag */
        if ($agent_markup_data['display_markup'] == 'in_tax') {
            $wl_agent_display_markup = "in_tax";
            $agentAddInTax = $wl_agent_markup_value;
        } elseif ($agent_markup_data['display_markup'] == 'in_service_charge') {
            $agentAddInServiceCharge = $wl_agent_markup_value;
            $wl_agent_display_markup = "in_service_charge";
        }
    }

    $WebpartnerFareBreakUp = $price;
    $WebpartnerFareBreakUp['WebPMarkUp'] = $markup_value;
    $WebpartnerFareBreakUp['WebPDiscount'] = $web_discount_value + $extra_discount;
    $WebpartnerFareBreakUp['DisplayMarkup'] = $display_markup;
    $tts_fare_price = array(
        'BasePrice' => $price['BasePrice'],
        'Tax' => $Tax + $agentAddInTax,
        'Discount' => $Discount,
        'AgentCommission' => $AgentCommision,
        'PublishedPrice' => $PublishedPrice + $wl_agent_markup_value,
        'OfferedPrice' => $OfferedPrice,
        'ServiceCharges' => $ServiceCharges + $agentAddInServiceCharge,
        'TDS' => $Tds,
        'GST' => $gst,
    );
    $AgentFareBreakUp = array(
        'BasePrice' => $price['BasePrice'],
        'Tax' => $Tax,
        'Discount' => $Discount,
        'AgentCommission' => $AgentCommision,
        'PublishedPrice' => $PublishedPrice,
        'OfferedPrice' => $OfferedPrice,
        'ServiceCharges' => $ServiceCharges,
        'TDS' => $Tds,
        'GST' => $gst,
        'AgentWebPMarkUp' => $wl_agent_markup_value,
        'AgentWebPDiscount' => $wl_agent_discount_value,
        'AgentWebPDisplayMarkup' => $wl_agent_display_markup
    );

    return ['Fare' => $tts_fare_price, 'WebPartnerFareBreakUp' => $WebpartnerFareBreakUp, 'AgentFareBreakUp' => $AgentFareBreakUp];
}

function get_fare_breakup($price)
{

    $breakup = array(
        'BasePrice' => $price,
        'Tax' => 0,
        'Discount' => 0,
        'AgentCommision' => 0,
        'PublishedPrice' => $price,
        'OfferedPrice' => $price,
        'ServiceCharges' => 0,
        'TDS' => 0,
        'GST' => array(
            'CGSTAmount' => 0,
            'CGSTRate' => 0,
            'IGSTAmount' => 0,
            'IGSTRate' => 0,
            'SGSTAmount' => 0,
            'SGSTRate' => 0,
            'TaxableAmount' => 0
        )
    );
    return $breakup;
}