<?php
/**
 * ----------------------------------------------------------------------------
 * Get Distance Between Source and Destination Place Id
 * -----------------------------------------------------------------------------
 */

function createfilerarray($array)
{
    $response = [];
    if ($array) {
        foreach ($array as $key => $value) {
            $obj['key'] = $key;
            $obj['label'] = $value;
            $obj['isChecked'] = false;
            $obj['isTempChecked'] = false;
            array_push($response, $obj);
        }
    }
    return $response;
}

function get_holiday_price(array $markup_data, array $discount_data, $price, array $userauthdata, $super_admin_gst_state_code)
{

    $Discount = 0;
    $ServiceCharges = 0;
    $Tax = 0;
    $TDS = 0;
    $price = $price;
    $markup_value = 0;
    $discount_value = 0;
    $extra_discount = 0;
    $commission = 0;
    if ($markup_data) {
        if ($markup_data['markup_type'] == 'percent') {
            /*   markup apply for Embassy Fees */
            $markup_value = round_value(($price * abs($markup_data['value'])) / 100);
            /*   markup check max limit  */
            if ($markup_data['max_limit']) {
                if (round_value($markup_data['max_limit']) <= $markup_value) {
                    $markup_value = round_value(abs($markup_data['max_limit']));
                }
            }
        } elseif ($markup_data['markup_type'] == 'fixed') {

            $markup_value = round_value($markup_data['value']);
        }

        /* display markup tag */
        if ($markup_data['display_markup'] == 'in_tax') {
            $Tax = $markup_value;
        } elseif ($markup_data['display_markup'] == 'in_service_charge') {
            $ServiceCharges = $markup_value;
        }


    }
    $OfferedPrice = round_value(($price + $markup_value));
    if ($discount_data) {
        /*  discount apply for  Total Journey Price */
        $discount_value =$discount_data['value'];

        if($discount_data['extra_discount'])
        {
            $extra_discount=round_value($discount_data['extra_discount']);
        } else {
            $extra_discount=0;
        }
        /* discount check max limit */
        if ($discount_data['max_limit']) {
            if (round_value($discount_data['max_limit']) <= $discount_value) {
                $discount_value = 0;
            }

        }


        /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
        $OfferedPrice = round_value(($price + $markup_value) - ($discount_value+ $extra_discount));
        if ($OfferedPrice > 0) {
        } elseif ($OfferedPrice == 0) {
            $discount_value = 0;
            $extra_discount = 0;
            $OfferedPrice = round_value(($price + $markup_value));
        } elseif ($OfferedPrice < 0) {

            $discount_value = 0;
            $extra_discount = 0;
            $OfferedPrice = round_value(($price + $markup_value));
        }
    }



    $AgentCommission =  $discount_value;
    $ServiceCharges =  $ServiceCharges;
    $Tax =  $Tax;
    $Discount =  $extra_discount;
    $PublishedPrice = $price + $Tax + $ServiceCharges;

    /*-- calculate tds-- */
    $TDS = tds_calculate($Discount+$AgentCommission );

    /* -- Calculate GST on Markup ---*/
    $GST = array();
    $GST = gst_calculate_holiday('Holiday', $userauthdata['gst_state_code'], $super_admin_gst_state_code, $ServiceCharges);
    $PublishedPrice = $PublishedPrice + $GST['TotalGSTAmount'];
    $OfferedPrice = $OfferedPrice + $GST['TotalGSTAmount'];
    unset($GST['TotalGSTAmount']);
    $tts_holiday_breakup = array(
        'BasePrice' => $price,
        'Tax' => $Tax,
        'Discount' => $Discount+$AgentCommission,
        'PublishedPrice' => $PublishedPrice,
        'OfferedPrice' => $OfferedPrice,
        'ServiceCharges' => $ServiceCharges,
        'TDS' => $TDS,
        'GST' => $GST
    );

    return $tts_holiday_breakup;
}

/**
 * ----------------------------------------------------------------------------
 * Get holiday price Apply Superadmin Markup, Discount, Calculate GST And TDS For Fare Summary
 * -----------------------------------------------------------------------------
 */

function get_holiday_fare_summary(array $markup_data, array $discount_data, $price, array $userauthdata, $super_admin_gst_state_code)
{

    $MarkupInfo = array(
        "Value" => 0,
        "DisplayFormat" => "in_tax",
    );
    $DiscountInfo = array(
        "Value" => 0,
    );
    $fare_summary = array();
    $Discount = 0;
    $ServiceCharges = 0;
    $Tax = 0;
    $TDS = 0;
    $Holiday_price = $price;
    $markup_value = 0;
    $discount_value = 0;
    $extra_discount = 0;
    $commission = 0;
    if ($markup_data) {
        if ($markup_data['markup_type'] == 'percent') {
            /*   markup apply for Embassy Fees */
            $markup_value = round_value(($price * abs($markup_data['value'])) / 100);
            /*   markup check max limit  */
            if ($markup_data['max_limit']) {
                if (round_value($markup_data['max_limit']) <= $markup_value) {
                    $markup_value = round_value(abs($markup_data['max_limit']));
                }
            }
        } elseif ($markup_data['markup_type'] == 'fixed') {

            $markup_value = round_value($markup_data['value']);
        }
        $MarkupInfo = array(
            "Value" => $markup_value,
            "DisplayFormat" => 'tax',
        );
        /* display markup tag */
        if ($markup_data['display_markup'] == 'in_tax') {
            $Tax = $markup_value;
        } elseif ($markup_data['display_markup'] == 'in_service_charge') {
            $ServiceCharges = $markup_value;
        }



    }
    $OfferedPrice = round_value(($price + $markup_value));
    if ($discount_data) {
        /*  discount apply for  Total Journey Price */
        $discount_value = round_value(($price * $discount_data['value']) / 100);
        if($discount_data['extra_discount'])
        {
            $extra_discount=round_value($discount_data['extra_discount']);
        } else {
            $extra_discount=0;
        }

        /* discount check max limit */
        if ($discount_data['max_limit']) {
            if (round_value($discount_data['max_limit']) <= $discount_value) {
                $discount_value = round_value($discount_data['max_limit']);
            }
        }
        $DiscountInfo =  array(
            "Value" => $discount_value,
            "ExtraDiscount" => $extra_discount,
        );


        /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
        $OfferedPrice = round_value(($price + $markup_value) - ($discount_value+ $extra_discount));
        if ($OfferedPrice > 0) {
        } elseif ($OfferedPrice == 0) {
            $discount_value = 0;
            $extra_discount = 0;
            $OfferedPrice = round_value(($price + $markup_value));
        } elseif ($OfferedPrice < 0) {

            $discount_value = 0;
            $extra_discount = 0;
            $OfferedPrice = round_value(($price + $markup_value));
        }
    }


    $AgentCommission =  $discount_value;
    $ServiceCharges =  $ServiceCharges;
    $Tax =  $Tax;
    $Discount =  $extra_discount;
    $PublishedPrice = $price + $Tax + $ServiceCharges;

    /*-- calculate tds-- */
    $TDS = tds_calculate($Discount+$AgentCommission );

    /* -- Calculate GST on Markup ---*/
    $GST = array();
    $GST = gst_calculate_holiday('Holiday', $userauthdata['gst_state_code'], $super_admin_gst_state_code, $ServiceCharges);
    $PublishedPrice = $PublishedPrice + $GST['TotalGSTAmount'];
    $OfferedPrice = $OfferedPrice + $GST['TotalGSTAmount'];
    unset($GST['TotalGSTAmount']);
    $tts_holiday_breakup = array(
        'BasePrice' => $Holiday_price,
        'Tax' => $Tax,
        'Discount' => $Discount+$AgentCommission,
        'PublishedPrice' => $PublishedPrice,
        'OfferedPrice' => $OfferedPrice,
        'ServiceCharges' => $ServiceCharges,
        'AgentCommission' => $AgentCommission,
        'TDS' => $TDS,
        'GST' => $GST
    );
    $fare_summary = array(
        "Markup" => $MarkupInfo,
        "Discount" => $DiscountInfo,
        "TTSBreakup" => $tts_holiday_breakup,
        "GST" => $GST,
        "TDS" => $TDS,

    );


    return $fare_summary;
}

/**
 * ----------------------------------------------------------------------------
 * Get Cities From itinerary with day Stay
 * -----------------------------------------------------------------------------
 */

 function city_iternary_stay($cities){
    $key =0;
    $cityInc[$key] = array(
        'name'=>$cities[0],
        'value'=>1
    );
    for($i=0;$i<count($cities)-1;$i++){
        if(strcasecmp($cities[$i],$cities[$i+1]) == 0){
            $cityInc[$key]['value'] += 1;
          }else{
            ++$key;
            $cityInc[$key]['name'] = $cities[$i+1];
            $cityInc[$key]['value'] = 1;
          }
    }
    return $cityInc;
 }