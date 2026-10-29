<?php
/**
 * ----------------------------------------------
 * Get  Cancellation Status
 * ---------------------------------------------
 */
if(!function_exists("get_cancellation_status")){
    function get_cancellation_status($cancellationStatus)
    {
        switch ($cancellationStatus) {
        case "1":
            $status='Unassigned';
            break;
        case "2":
            $status='Assigned';
            break;
        case "3":
            $status='Acknowledged';
            break;
        case "4":
            $status='Completed';
            break;
        case "5":
            $status='Rejected';
            break;
        case "6":
            $status='Closed';
            break;
        case "7":
            $status='Pending';
            break;
        case "8":
            $status='Other';
            break;
        default:
            $status = "NotSet";
        }
        return $status;
    }
   }

/**
 * ----------------------------------------------
 * Get GDS Flight
 * ---------------------------------------------
 */
if(!function_exists("get_flight_source")){
 function get_flight_source($airline_code)
 {
     switch ($airline_code) {
     case "6E":
         $source='6E';
         break;
     case "SG":
         $source='SG';
         break;
     case "G8":
         $source='G8';
         break;
     case "AI":
         $source='GDS';
         break;
     case "UK":
         $source='GDS';
         break;
     case "I5":
         $source='I5';
         break;
     default:
         $source = "ALL";
     }
     return $source;
 }
}
/**
 * ----------------------------------------------
 * TBO API Request (Return Json Format)
 * ---------------------------------------------
 */
function TBO_Request($url,$data) {
    $request_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: gzip,deflate',
        'Cache-Control: no-cache',
        'Pragma: no-cache',
        'Content-Length: ' . strlen($request_json)));
    $response = curl_exec($ch);
    
    // Check for errors and display the error message
    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        api_custom_message(408,$error_message,false);
    }
    curl_close($ch);
    if($response)
    {
        return json_decode($response,true);
    } else{
        api_custom_message(400,'something went wrong please try again',false);
    }
}

function TBO_Other_Request($url, $data){
    $payload= json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: gzip,deflate',
        'Cache-Control: no-cache',
        'Pragma: no-cache',
        'Content-Length: ' . strlen($payload)
    ));

    $response = curl_exec($ch);
    
    if ($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        curl_close($ch);
        api_custom_message(408, $error_message, false);
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        api_custom_message($http_code, "HTTP error $http_code", false);
    }

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        api_custom_message(500, 'Invalid JSON response from API', false);
    }
    
    return $decoded;
}



/**
 * ----------------------------------------------
 * TBO Cabin Class
 * ---------------------------------------------
 */

function get_cabin_class()
{

    
}

/**
 * ----------------------------------------------
 * TBO Get Pax Type
 * ---------------------------------------------
 */

function get_pax_type($type)
{
    switch ($type) {
    case "1":
        $paxtype='ADT';
        break;
    case "2":
        $paxtype='CHD';
        break;
    case "3":
        $paxtype='INF';
        break;
    default:
        $paxtype = "ADT";
    }
    return $paxtype;
}

/**
 * ----------------------------------------------
 * SSR Way Type Get
 * ---------------------------------------------
 */

function get_ssr_waytype($type)
{
    switch ($type) {
    case "1":
        $waytype='Segment';
        break;
    case "2":
        $waytype='FullJourney';
        break;
    default:
        $waytype = "NotSet";
    }
    return $waytype;
}

/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function get_flight_fare($input,$markup_data,$discount_data,$price,$price_breakdown,$userauthdata,$super_admin_gst_state_code,$airline_code,$service=null) {

    $totalpax=intval($input['Adult'])+intval($input['Child']);

    $BaseFare=0; $Tax=0; $YQTax=0; $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;
    $Tax=$price['Tax'];

    $markup_value=0; $discount_value=0;$extra_discount=0; $commission=0; $totalgst=0;
    $sel_markup_data=array(); $sel_discount_data=array();
    if($markup_data)
    {
        $supplier_array =array_column($markup_data, 'supplier');
        if(in_array('TBO', $supplier_array))
        {
            $airline_array =array_column($markup_data, 'airline_code');
            if(in_array($airline_code, $airline_array))
            {
                $sel_key_airline = array_search($airline_code,$airline_array);
                $sel_markup_data=$markup_data[$sel_key_airline];
            }
            else
            {
                if(in_array('ANY', $airline_array))
                {
                    $sel_key_airline = array_search('ANY',$airline_array);
                    $sel_markup_data=$markup_data[$sel_key_airline];
                }
            }

            if($sel_markup_data['markup_type']=='percent')
            {
                /*   markup apply for BaseFare */
                    $markup_value=round_value(($price['BaseFare']*abs($sel_markup_data['value']))/100);
                /*   markup check max limit  */
                if($sel_markup_data['max_limit']) {
                    if(round_value($sel_markup_data['max_limit']) <= $markup_value)
                    {
                       $markup_value=round_value(abs($sel_markup_data['max_limit']));
                    }
                }
            } elseif($sel_markup_data['markup_type']=='fixed') {
    
                       $markup_value=round_value($sel_markup_data['value']);
            }
    
            /* display markup tag */
            if($sel_markup_data['display_markup']=='in_tax')
            {
                $Tax=$price['Tax']+$markup_value;
            } elseif($sel_markup_data['display_markup']=='in_service_charge') {
                $ServiceCharges=$markup_value;
            }   
        }
    }

    $GST=array();
    $GST=gst_calculate('Flight',$userauthdata['gst_state_code'],$super_admin_gst_state_code,$ServiceCharges);
    $totalgst=$GST['TotalGSTAmount'];
    $PublishedPrice=round_value($price['PublishedFare']+$markup_value+$totalgst);

    unset($GST['TotalGSTAmount']);

    if($discount_data)
    {
        $supplier_array =array_column($discount_data, 'supplier');
        if(in_array('TBO', $supplier_array))
        {
            $airline_array =array_column($discount_data, 'airline_code');
            if(in_array($airline_code, $airline_array))
            {
                $sel_key_airline = array_search($airline_code,$airline_array);
                $sel_discount_data=$discount_data[$sel_key_airline];
            }
            else
            {
                if(in_array('ANY', $airline_array))
                {
                    $sel_key_airline = array_search('ANY',$airline_array);
                    $sel_discount_data=$discount_data[$sel_key_airline];
                }
            }

            $SupplierCommission=$price['Discount']+$price['CommissionEarned']+$price['PLBEarned']+$price['IncentiveEarned'];
            if($sel_discount_data['discount_type']=='percent')
            {
                $commission=round_value(($SupplierCommission*$sel_discount_data['value'])/100);
            } else {
                $commission=round_value($sel_discount_data['value']);
            }
            if($sel_discount_data['extra_discount'])
            {
                $extra_discount=round_value(($sel_discount_data['extra_discount']*$totalpax));
            } else {
                $extra_discount=0;
            }
            $discount_value=round_value($commission+$extra_discount);
            /* discount check max limit */        
            if($sel_discount_data['max_limit']) {
                if(round_value($sel_discount_data['max_limit']) <= $discount_value)
                {
                   $discount_value=round_value(abs($sel_discount_data['max_limit']));
                }
            }
        }
    }

      /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
      $OfferedPrice=round_value(($PublishedPrice)-($commission+$extra_discount));
      if ($OfferedPrice > 0)
      { 

      } elseif ($OfferedPrice == 0){
          $commission=0;
          $extra_discount=0;
          $discount_value=0;
          $OfferedPrice=round_value(($price['PublishedFare']+$markup_value+$totalgst));

      } elseif ($OfferedPrice < 0) {

          $commission=0;
          $extra_discount=0;
          $discount_value=0;
          $OfferedPrice=round_value(($price['PublishedFare']+$markup_value+$totalgst));
      }

     $BaseFare=$price['BaseFare'];
     $YQTax=$price['YQTax'];
     $OtherCharges=$price['OtherCharges']+$price['ServiceFee'];
     $Discount=$extra_discount;
     $AgentCommission=$commission;

     /*-- calculate tds-- */
     $TDS=tds_calculate($discount_value);
    /* -- Calculate GST on Markup ---*/


    $per_pax_markup=($markup_value/$totalpax);
    $FareBreakdown=array();
    foreach($price_breakdown as $pax)
    {
       $paxkey=get_pax_type($pax['PassengerType']);
       $paxwise_markup_value=0;
       $per_pax_ServiceCharges=0;
       if($paxkey!='INF') 
       {
            if($sel_markup_data && $sel_markup_data['display_markup']=='in_tax')
            {
                $paxwise_markup_value=round_value($per_pax_markup*$pax['PassengerCount']);
            } elseif($sel_markup_data && $sel_markup_data['display_markup']=='in_service_charge')
            {
                $per_pax_ServiceCharges=round_value($per_pax_markup*$pax['PassengerCount']);
            } 
       }
       $FareBreakdown[$paxkey]=array(
                                     'PassengerCount' => $pax['PassengerCount'],
                                     'BaseFare'       => $pax['BaseFare'],
                                     'Tax'            => $pax['Tax']+$paxwise_markup_value,
                                     'YQTax'          => $pax['YQTax'],
                                     'ServiceCharges' => $per_pax_ServiceCharges
                                    );
    }
   
     $tts_flight_breakup=array(
                                'BaseFare'       => $BaseFare,
                                'Tax'            => $Tax,
                                'YQTax'          => $YQTax,
                                'OtherCharges'   => $OtherCharges,
                                'Discount'       => $Discount,
                                'PublishedPrice' => $PublishedPrice,
                                'OfferedPrice'   => $OfferedPrice,
                                'AgentCommission'=> $AgentCommission,
                                'ServiceCharges' => $ServiceCharges,
                                'TDS'            => $TDS,
                                'GST'            => $GST
                             );


    $SuperAdminFareBreakup=array();
    $WebPartnerFareBreakup=array();
    if($service=='FareConfirmation')
    {
        $SupplierCommission=$price['Discount']+$price['CommissionEarned']+$price['PLBEarned']+$price['IncentiveEarned'];
        $display_markup='';
        if(isset($sel_markup_data['display_markup']))
        {
            $display_markup=$sel_markup_data['display_markup'];
        }
        $discount_type='';
        if(isset($sel_discount_data['discount_type']))
        {
            $discount_type=$sel_discount_data['discount_type'];
        }
        $SuperAdminFareBreakup=array(
                                        'BaseFare'             => $price['BaseFare'],
                                        'Tax'                  => $price['Tax'],
                                        'YQTax'                => $price['YQTax'],
                                        'OtherCharges'         => floatval($price['OtherCharges'])+floatval($price['ServiceFee'])+floatval($price['AdditionalTxnFeePub']),
                                        'Discount'             => $SupplierCommission,
                                        'PublishedPrice'       => $price['PublishedFare'],
                                        'OfferedPrice'         => $price['OfferedFare'],
                                        'TotalBaggageCharges'  => 0,
                                        'TotalMealCharges'     => 0,
                                        'TotalSeatCharges'     => 0,
                                        'SUP_Markup'           => $markup_value,
                                        'SUP_DisplayMarkup'    => $display_markup,
                                        'SUP_Discount'         => $commission,
                                        'SUP_ExtraDiscount'    => $extra_discount,
                                        'SUP_DiscountType'     => $discount_type                                      
                                    );
        $WebPartnerFareBreakup=$tts_flight_breakup;
    }
   
     return array('Fare'=>$tts_flight_breakup,'FareBreakdown'=>$FareBreakdown,'SuperAdminFareBreakup'=>$SuperAdminFareBreakup,'WebPartnerFareBreakup'=>$WebPartnerFareBreakup);
}
