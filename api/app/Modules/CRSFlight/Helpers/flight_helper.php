<?php

use CodeIgniter\I18n\Time;
/* cabin class type  CRS start */

if (!function_exists('get_crs_flight_duration')) {
    function get_crs_flight_duration($domesticType, $departureDate, $ArrivalDate)
    {
        $duration = 0;
        if ($domesticType) {
            $departureDate =  str_replace("T", " ", $departureDate);
            $ArrivalDate =  str_replace("T", " ", $ArrivalDate);
            $departureDate = Time::parse($departureDate);
            $ArrivalDate    = Time::parse($ArrivalDate);
            $diff  =  $departureDate->difference($ArrivalDate);
            $duration = $diff->minutes;
        }
        return $duration;
    }
}
/* cabin class type CRS end */
/* cabin class type  CRS start */
if (!function_exists('get_crs_cabinclass')) {
    function get_crs_cabinclass($class)
    {

        switch ($class) {
            case 2:
                $classtype = "Economy";
                break;
            case 3:
                $classtype = "PremiumEconomy";
                break;
            case 4:
                $classtype = "Business";
                break;
            case 5:
                $classtype = "PremiumBusiness";
                break;
            case 6:
                $classtype = "First";
                break;
            case 1:
                $classtype = "Any";
                break;
            default:
                $classtype = "Any";
        }
        return $classtype;
    }
}
/* cabin class type CRS end */

/* Fare Breakup  CRS start */
if (!function_exists('get_crs_FareBreakup')) {
    function get_crs_FareBreakup($input, $sectorInfo)
    {
        $FareBreakdown  =  array();
        $Fare  =  array();
        $TotalBaseFare  = 0;
        $TotalTax  = 0;
        $TotalGST  = 0;
        $PublishFare = 0;
        $OfferedFare = 0;
        $AdultBaseFare = 0;
        $AdultTax = 0;
        $AdultGst = 0;
        $ChildBaseFare = 0;
        $ChildTax = 0;
        $ChildGst = 0;
        $InfantBaseFare = 0;
        $InfantTax = 0;
        $InfantGst = 0;
        $TotalAdultBaseFare = 0;
        $TotalAdultTax = 0;
        $TotalAdultGst = 0;
        $TotalChildBaseFare = 0;
        $TotalChildTax = 0;
        $TotalChildGst = 0;
        $TotalInfantBaseFare = 0;
        $TotalInfantTax = 0;
        $TotalInfantGst = 0;
        $NoOfAdult  =  $input['Adult'];
        $NoOfChild  =  $input['Child'];
        $NoOfInfant  =  $input['Infant'];
        $AdultBaseFare  =  $sectorInfo['AdultBaseFare'];
        $AdultTax  =  $sectorInfo['AdultTax'];
        $AdultGst  =  $sectorInfo['AdultGst'];
        $ChildBaseFare  =  $sectorInfo['ChildBaseFare'];
        $ChildTax  =  $sectorInfo['ChildTax'];
        $ChildGst  =  $sectorInfo['ChildGst'];
        $InfantBaseFare  =  $sectorInfo['InfantBaseFare'];
        $InfantTax  =  $sectorInfo['InfantTax'];
        $InfantGst  =  $sectorInfo['InfantGst'];
        if ($NoOfAdult > 0) {
            $TotalAdultBaseFare =  ($AdultBaseFare * $NoOfAdult);
            $TotalAdultTax =  ($AdultTax * $NoOfAdult);
            $TotalAdultGst =  ($AdultGst * $NoOfAdult);
            $TotalBaseFare =   $TotalBaseFare + $TotalAdultBaseFare;
            $TotalTax =  $TotalTax + $TotalAdultTax;
            $TotalGST =  $TotalGST + $TotalAdultGst;
            $FareBreakdown['ADT'] =  array(
                "PassengerCount" => $NoOfAdult,
                "BaseFare" => $TotalAdultBaseFare,
                "Tax" => $TotalAdultTax + $TotalAdultGst,
                "YQTax" => 0,
            );
        }
        if ($NoOfChild > 0) {
            $TotalChildBaseFare =  ($ChildBaseFare * $NoOfChild);
            $TotalChildTax =  ($ChildTax * $NoOfChild);
            $TotalChildGst =  ($ChildGst * $NoOfChild);
            $TotalBaseFare =   $TotalBaseFare + $TotalChildBaseFare;
            $TotalTax =  $TotalTax + $TotalChildTax;
            $TotalGST =  $TotalGST + $TotalChildGst;
            $FareBreakdown['CHD'] =  array(
                "PassengerCount" => $NoOfChild,
                "BaseFare" => $TotalChildBaseFare,
                "Tax" => $TotalChildTax + $TotalChildGst,
                "YQTax" => 0,
            );
        }
        if ($NoOfInfant > 0) {
            $TotalInfantBaseFare =  ($InfantBaseFare * $NoOfInfant);
            $TotalInfantTax =  ($InfantTax * $NoOfInfant);
            $TotalInfantGst =  ($InfantGst * $NoOfInfant);
            $TotalBaseFare =   $TotalBaseFare + $TotalInfantBaseFare;
            $TotalTax =  $TotalTax + $TotalInfantTax;
            $TotalGST =  $TotalGST + $TotalInfantGst;
            $FareBreakdown['INF'] =  array(
                "PassengerCount" => $NoOfInfant,
                "BaseFare" => $TotalInfantBaseFare,
                "Tax" => $TotalInfantTax + $TotalInfantGst,
                "YQTax" => 0,
            );
        }
        $PublishFare =  $TotalBaseFare + $TotalTax + $TotalGST;
        $OfferedFare =  $TotalBaseFare + $TotalTax + $TotalGST;
        $Fare  =   array(
            "BaseFare" => $TotalBaseFare,
            "Tax" => ($TotalTax + $TotalGST),
            "YQTax" => 0,
            "OtherCharges" => 0,
            "ServiceFee" => 0,
            "GST" => $TotalGST,
            "CommissionEarned" => 0,
            "PublishedPrice" => $PublishFare,
            "OfferedPrice" => $OfferedFare,
        );
        return array("Fare" => $Fare, "FareBreakdown" => $FareBreakdown);
    }
}
/* Fare Breakup CRS end */
/* Get Arrival Date CRS Start */
function get_arrivaldate($Departdate, $arrival_time, $is_next_day_arrival)
{
    $addDay  =  0;
    if ($is_next_day_arrival == "Yes") {
        $addDay  =  1;
    }
    $newtimestamp = strtotime('+' . $addDay . ' day', strtotime($Departdate));
    return date('Y-m-d', $newtimestamp) . "T" . $arrival_time . ":00";
}

/* Get Arrival Date CRS end */

/*   Segment Convert  Start*/

if (!function_exists('get_convert_segment_info')) {
    function get_convert_segment_info($input, $sectorInfo, $OriginDsetinationAirportCodeInfo, $airline_list, $airport_list, $airport_timezone, $FareRuleData)
    {
        $Segments_details = json_decode($sectorInfo['SectorSegmentDetail'], true);
        $SegmentInfo = json_decode($sectorInfo['SegmentInfo'], true);
        $NumberOfSegment =  count($SegmentInfo);
        $ValidatingAirline = $Segments_details[0][0]['airline_code'];
        $TripIndicator = 1;
        $Departdate = $sectorInfo['FarePnrDate'];

        $available_seats = $sectorInfo['AvailableSeats'];
        if ($available_seats > 9) {
            $available_seats = 9;
        }
       
       
       
        $fligth_Segments_data_array = array();
        foreach ($SegmentInfo as $mainkey => $super_flight_segment) {  
            $fligth_Segments_array = array();
            if ($mainkey == 1) {
                $Departdate = $sectorInfo['FareReturnPnrDate'];
            }

           

            foreach ($super_flight_segment as $segkey => $flight_segment) { 
                
              
               
               
                    $city_OriginKey =  array_search($flight_segment['origin_airport_code'], array_column($OriginDsetinationAirportCodeInfo, 'city_code'));
                    $city_Origin = $OriginDsetinationAirportCodeInfo[$city_OriginKey];
                    $city_destiKey =  array_search($flight_segment['destination_airport_code'], array_column($OriginDsetinationAirportCodeInfo, 'city_code'));
                    $city_desti = $OriginDsetinationAirportCodeInfo[$city_destiKey];
                    $Departdatetime = $Departdate . 'T' . $flight_segment['departure_time'] . ":00";
                    $ArrivaldateTime = get_arrivaldate($Departdate, $flight_segment['arrival_time'], $flight_segment['is_next_day_arrival']);
                    $Departdate =  explode("T", $ArrivaldateTime[0]);
                    $airline_list[explode("-", $flight_segment['airline_code'])[0]]  = explode("-", $flight_segment['airline_code'])[1];
                    $layoverTime = "";
                    $airport_list[$flight_segment['origin_airport_code']] =  array(
                        "code" => $flight_segment['destination_airport_code'],
                        "name" => $city_Origin['name'],
                        "city_code"    => $city_Origin['city_code'],
                        "city_name"    => $city_Origin['city_name'],
                        "country_name" => $city_Origin['country_name'],
                        "country_code" => $city_Origin['country_code']
                    );
                    $airport_list[$flight_segment['origin_airport_code']] =  array(
                        "code" => $flight_segment['destination_airport_code'],
                        "name" => $city_desti['name'],
                        "city_code"    => $city_desti['city_code'],
                        "city_name"    => $city_desti['city_name'],
                        "country_name" => $city_desti['country_name'],
                        "country_code" => $city_desti['country_code']
                    );
                    $fligth_Segments_array[] = array(
                        'TripIndicator' => $TripIndicator,
                        'SegmentIndicator' => $segkey + 1,
                        'Duration' => get_crs_flight_duration($input['IsDomestic'], $Departdatetime, $ArrivaldateTime),
                        'TechStopPoint' => array(),
                        'Craft' => isset($flight_segment['Craft'])?$flight_segment['Craft']:"",
                        "LayoverTime" => $layoverTime,
                        'Airline' => array(
                            "AirlineCode"      => explode("-", $flight_segment['airline_code'])[0],
                            "AirlineName"      => explode("-", $flight_segment['airline_code'])[1],
                            "FlightNumber"     => $flight_segment['flight_number'],
                            "FareClass"        => $sectorInfo['BookingClass'],
                            "OperatingCarrier" => explode("-", $flight_segment['airline_code'])[0],
                        ),
                        'Origin' => array(
                            "AirportCode" => $flight_segment['origin_airport_code'],
                            "AirportName" => $city_Origin['name'],
                            "CityCode"    => $city_Origin['city_code'],
                            "CityName"    => $city_Origin['city_name'],
                            "CountryCode" => $city_Origin['country_code'],
                            "CountryName" => $city_Origin['country_name'],
                            "Terminal"    => $flight_segment['origin_terminal'],
                            "DepartTime"  => $Departdatetime,
                        ),
                        'Destination' => array(
                            "AirportCode" => $flight_segment['destination_airport_code'],
                            "AirportName" => $city_desti['name'],
                            "CityCode"    => $city_desti['city_code'],
                            "CityName"    => $city_desti['city_name'],
                            "CountryCode" => $city_desti['country_code'],
                            "CountryName" => $city_desti['country_name'],
                            "Terminal"    => $flight_segment['destination_terminal'],
                            "ArrivalTime" => $ArrivaldateTime,
                        ),
                    );
                    $Sector = $flight_segment['origin_airport_code'] . '-' . $flight_segment['destination_airport_code'];
                    $ttsbaggageinfo[$mainkey][$segkey] = array(
                        'NoOfSeatAvailable' => $available_seats,
                        'Sector'            => $Sector,
                        'CheckIn'           => isset($FareRuleData['checkin_baggage_adult']) ? $FareRuleData['checkin_baggage_adult'] : "",
                        'Cabin'             => isset($FareRuleData['hand_baggage_adult']) ? $FareRuleData['hand_baggage_adult'] : "",
                    );
               
            }
            $fligth_Segments_data_array[$mainkey] = $fligth_Segments_array;
        }
        return  array("Segments" => $fligth_Segments_data_array, "airport_list" => $airport_list, "airline_list" => $airline_list, "ttsbaggageinfo" => $ttsbaggageinfo);
    }
}

/*   Segment Convert  End*/
/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */


function get_crs_flight_fare($input, $markup_data, $supplier_markup, $discount_data, $price, $price_breakdown, $userauthdata, $super_admin_gst_state_code, $airline_code, $CabinClass, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, $service = null)
{
    $totalpax = intval($input['Adult']) + intval($input['Child']);
    $BaseFare = 0;
    $Tax = 0;
    $YQTax = 0;
    $OtherCharges = 0;
    $Discount = 0;
    $PublishedPrice = 0;
    $OfferedPrice = 0;
    $AgentCommission = 0;
    $ServiceCharges = 0;
    $TDS = 0;
    $Tax = $price['Tax'];
    $markup_value = 0;
    $discount_value = 0;
    $extra_discount = 0;
    $commission = 0;
    $totalgst = 0;
    $supplier_markup_value = 0;
    $sel_markup_data = array();
    $sel_discount_data = array();
    $sel_sup_markup_data = array();
    if ($markup_data) {
        $filter_markup_data =  getFliterFlightMarkup("CRS", $markup_data, $input, $airline_code, $CabinClass, $selectedMarkupDataInfo, $extraParam);
        $selectedMarkupDataInfo =  $filter_markup_data['selectedMarkupDataInfo'];
        $sel_markup_data =  $filter_markup_data['selMarkupData'];
        if ($sel_markup_data) {
            if ($sel_markup_data['markup_type'] == 'percent') {
                /*   markup apply for BaseFare */
                $markup_value = round_value(($price['BaseFare'] * abs($sel_markup_data['value'])) / 100);
                /*   markup check max limit  */
                if ($sel_markup_data['max_limit']) {
                    if (round_value($sel_markup_data['max_limit']) <= $markup_value) {
                        $markup_value = round_value(abs($sel_markup_data['max_limit']));
                    }
                }
            } elseif ($sel_markup_data['markup_type'] == 'fixed') {
                $markup_value = round_value($sel_markup_data['value'] * $totalpax);
            }
            /* display markup tag */
            if ($sel_markup_data['display_markup'] == 'in_tax') {
                $Tax = $price['Tax'] + $markup_value;
            } elseif ($sel_markup_data['display_markup'] == 'in_service_charge') {
                $ServiceCharges = $markup_value;
            }
        }
    }

    if ($supplier_markup) {
        $filter_sup_markup_data =  getFliterFlightMarkup("CRS", $supplier_markup, $input, $airline_code, $CabinClass, $selectedMarkupDataInfo, $extraParam);
        $selectedSupplierMarkupDataInfo =  $filter_sup_markup_data['selectedMarkupDataInfo'];
        $sel_sup_markup_data =  $filter_sup_markup_data['selMarkupData'];
        if ($sel_sup_markup_data) {
            if ($sel_sup_markup_data['markup_type'] == 'percent') {
                /*   markup apply for BaseFare */
                $supplier_markup_value = round_value(($price['BaseFare'] * abs($sel_sup_markup_data['value'])) / 100);
                /*   markup check max limit  */
                if ($sel_sup_markup_data['max_limit']) {
                    if (round_value($sel_sup_markup_data['max_limit']) <= $supplier_markup_value) {
                        $supplier_markup_value = round_value(abs($sel_sup_markup_data['max_limit']));
                    }
                }
            } elseif ($sel_sup_markup_data['markup_type'] == 'fixed') {
                $supplier_markup_value = round_value($sel_sup_markup_data['value'] * $totalpax);
            }
            /* display markup tag */
            if ($sel_sup_markup_data['display_markup'] == 'in_tax') {
                $Tax = $Tax + $supplier_markup_value;
            } elseif ($sel_sup_markup_data['display_markup'] == 'in_service_charge') {
                $ServiceCharges = $ServiceCharges + $supplier_markup_value;
            }
        }
    }

    $GST = array();
    $GST = gst_calculate('Flight', $userauthdata['gst_state_code'], $super_admin_gst_state_code, $ServiceCharges);
    $totalgst = $GST['TotalGSTAmount'];
    $PublishedPrice = round_value($price['PublishedPrice'] + $markup_value + $totalgst + $supplier_markup_value);
    unset($GST['TotalGSTAmount']);
    if ($discount_data) {
        $filter_discount_data =  getFliterFlightDiscount("CRS", $discount_data, $input, $airline_code, $CabinClass, $selectedDiscountDataInfo, $extraParam);
        $selectedDiscountDataInfo =  $filter_discount_data['selectedDiscountDataInfo'];
        $sel_discount_data =  $filter_discount_data['selDiscountData'];
        if ($sel_discount_data) {
            $SupplierCommission = $price['CommissionEarned'];
            if ($sel_discount_data['discount_type'] == 'percent') {
                $commission = round_value(($SupplierCommission * $sel_discount_data['value']) / 100);
            } else {
                $commission = round_value($sel_discount_data['value']);
            }
            if ($sel_discount_data['extra_discount']) {
                $extra_discount = round_value($sel_discount_data['extra_discount']);
            } else {
                $extra_discount = 0;
            }

            $discount_value = round_value($commission + $extra_discount);
            /* discount check max limit */
            if ($sel_discount_data['max_limit']) {
                if (round_value($sel_discount_data['max_limit']) <= $discount_value) {
                    $discount_value = round_value(abs($sel_discount_data['max_limit']));
                }
            }
        }
    }

    /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
    $OfferedPrice = round_value(($PublishedPrice) - ($commission + $extra_discount));
    if ($OfferedPrice > 0) {
    } elseif ($OfferedPrice == 0) {
        $commission = 0;
        $extra_discount = 0;
        $discount_value = 0;
        $OfferedPrice = round_value(($price['PublishedPrice'] + $markup_value + $totalgst));
    } elseif ($OfferedPrice < 0) {
        $commission = 0;
        $extra_discount = 0;
        $discount_value = 0;
        $OfferedPrice = round_value(($price['PublishedPrice'] + $markup_value + $totalgst));
    }

    $BaseFare = $price['BaseFare'];
    $YQTax = $price['YQTax'];
    $OtherCharges = $price['OtherCharges'] + $price['ServiceFee'];
    $Discount = $extra_discount;
    $AgentCommission = $commission;
    /*-- calculate tds-- */
    $TDS = tds_calculate($discount_value);
    /* -- Calculate GST on Markup ---*/
    $per_pax_markup = ($markup_value / $totalpax);
    $FareBreakdown = array();
    foreach ($price_breakdown as $paxkey => $pax) {
        $paxwise_markup_value = 0;
        $per_pax_ServiceCharges = 0;
        if ($paxkey != 'INF') {
            if ($sel_markup_data && $sel_markup_data['display_markup'] == 'in_tax') {
                $paxwise_markup_value = round_value($per_pax_markup * $pax['PassengerCount']);
            } elseif ($sel_markup_data && $sel_markup_data['display_markup'] == 'in_service_charge') {
                $per_pax_ServiceCharges = round_value($per_pax_markup * $pax['PassengerCount']);
            }
            if ($sel_sup_markup_data && $sel_sup_markup_data['display_markup'] == 'in_tax') {
                $paxwise_markup_value = $paxwise_markup_value + round_value(($supplier_markup_value / $totalpax) * $pax['PassengerCount']);
            } elseif ($sel_sup_markup_data && $sel_sup_markup_data['display_markup'] == 'in_service_charge') {
                $per_pax_ServiceCharges = $paxwise_markup_value + round_value(($supplier_markup_value / $totalpax) * $pax['PassengerCount']);
            }
        }

        $FareBreakdown[$paxkey] = array(
            'PassengerCount' => $pax['PassengerCount'],
            'BaseFare'       => $pax['BaseFare'],
            'Tax'            => $pax['Tax'] + $paxwise_markup_value,
            'YQTax'          => $pax['YQTax'],
            'ServiceCharges' => $per_pax_ServiceCharges
        );
    }

    $tts_flight_breakup = array(
        'BaseFare'       => $BaseFare,
        'Tax'            => $Tax,
        'YQTax'          => $YQTax,
        'OtherCharges'   => $OtherCharges,
        'Discount'       => $Discount,
        'PublishedPrice' => $PublishedPrice,
        'OfferedPrice'   => $OfferedPrice,
        'AgentCommission' => $AgentCommission,
        'ServiceCharges' => $ServiceCharges,
        'TDS'            => $TDS,
        'GST'            => $GST
    );

    $SuperAdminFareBreakup = array();
    $WebPartnerFareBreakup = array();
    $SupplierFareBreakup = array();
    if ($service == 'FareConfirmation') {
        $SupplierCommission = $price['CommissionEarned'];
        $display_markup = '';
        $supplier_display_markup = '';
        if (isset($sel_markup_data['display_markup'])) {
            $display_markup = $sel_markup_data['display_markup'];
        }
        if (isset($sel_sup_markup_data['display_markup'])) {
            $supplier_display_markup = $sel_sup_markup_data['display_markup'];
        }
        $discount_type = '';
        if (isset($sel_discount_data['discount_type'])) {
            $discount_type = $sel_discount_data['discount_type'];
        }
        $SuperAdminFareBreakup = array(
            'BaseFare'             => $price['BaseFare'],
            'Tax'                  => $price['Tax'],
            'YQTax'                => $price['YQTax'],
            'OtherCharges'         => floatval($price['OtherCharges']) + floatval($price['ServiceFee']),
            'Discount'             => $SupplierCommission,
            'PublishedPrice'       => $price['PublishedPrice'],
            'OfferedPrice'         => $price['OfferedPrice'],
            'TotalBaggageCharges'  => 0,
            'TotalMealCharges'     => 0,
            'TotalSeatCharges'     => 0,
            'SUP_Markup'           => $markup_value,
            'SUP_DisplayMarkup'    => $display_markup,
            'SUP_Discount'         => $commission,
            'SUP_ExtraDiscount'    => $extra_discount,
            'SUP_DiscountType'     => $discount_type
        );

        $SupplierFareBreakup = array(
            'BaseFare'             => $price['BaseFare'],
            'Tax'                  => $price['Tax'],
            'YQTax'                => $price['YQTax'],
            'OtherCharges'         => floatval($price['OtherCharges']) + floatval($price['ServiceFee']),
            'Discount'             => $SupplierCommission,
            'PublishedPrice'       => $price['PublishedPrice'],
            'OfferedPrice'         => $price['OfferedPrice'],
            'TotalBaggageCharges'  => 0,
            'TotalMealCharges'     => 0,
            'TotalSeatCharges'     => 0,
            'SUPPLIER_Markup'           => $supplier_markup_value,
            'SUPPLIER_DisplayMarkup'    => $supplier_display_markup,
        );
        $WebPartnerFareBreakup = $tts_flight_breakup;
    }
    return array(
        'Fare' => $tts_flight_breakup, 'FareBreakdown' => $FareBreakdown, 'SuperAdminFareBreakup' => $SuperAdminFareBreakup, 'WebPartnerFareBreakup' => $WebPartnerFareBreakup,
        "selectedMarkupDataInfo" => $selectedMarkupDataInfo, "selectedDiscountDataInfo" => $selectedDiscountDataInfo, "SupplierFareBreakup" => $SupplierFareBreakup,
    );
}
