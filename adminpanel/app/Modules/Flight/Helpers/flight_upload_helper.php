<?php



use CodeIgniter\I18n\Time;

use CodeIgniter\I18n\TimeDifference;

/**

 * ----------------------------------------------

 * Get Origin and Destination Airport Code

 * ---------------------------------------------

 */



function getGdsPnrUploadTicket($gdspnrvalue)

{

    $array  =  array();

    if ($gdspnrvalue) {

        foreach ($gdspnrvalue as $value) {

            $array =  array_merge($array, array_values($value));
        }

        if (!empty($array)) {

            return implode(",", $array);
        }
    }

    return "---";
}
/* get convert To Hours Mins from  Duration (in Minutes) */

if (!function_exists('get_convertToHoursMinsfromMinDuration_upload_ticket')) {

    function get_convertToHoursMinsfromMinDuration_upload_ticket($minutes)

    {

        return  $hours = intdiv($minutes, 60) . ' h ' . ($minutes % 60) . ' m ';
    }
}
/* get Flight Date */

if (!function_exists('get_flight_date_upload_ticket')) {

    function get_flight_date_upload_ticket($var)

    {

        list($dt, $tm) = explode('T', $var);

        return date("d M", strtotime($dt));
    }
}
/* get Flight Time */

if (!function_exists('get_flight_time_upload_ticket')) {

    function get_flight_time_upload_ticket($var)

    {

        list($dt, $tm) = explode('T', $var);

        $tm = substr($tm, 0, 5);

        return $tm;
    }
}


/**

 * -Get  Flight Journey Time  

 * ---------------------------------------------

 */

if (!function_exists('journeyTimeTicketUpload')) {

    function journeyTimeTicketUpload($origintimezone, $destinationtimezone, $depdatetime, $arridatetime)

    {

        if ($origintimezone == "" || $destinationtimezone == "") {

            $origintimezone =  "Asia/Kolkata";

            $destinationtimezone =  "Asia/Kolkata";
        }

        if ($origintimezone != "" && $destinationtimezone != "") {

            $depaturedate = str_replace("T", " ", $depdatetime);

            $arrivaldate = str_replace("T", " ", $arridatetime);

            $departureDate = Time::parse($depaturedate, trim($origintimezone));

            $arrivalDate =  Time::parse($arrivaldate, trim($destinationtimezone));

            $diff = $arrivalDate->difference($departureDate);

            $timeInMinutes =  $diff->getMinutes();
            $timeInMinutes =  str_replace("-", "", $timeInMinutes);
            return $timeInMinutes;
        } else {

            return "";
        }
    }
}



//* cabin class type  Api start */

if (!function_exists('get_api_cabinclass')) {

    function get_api_cabinclass($class)

    {



        switch ($class) {

            case "Economy":

                $classtype = 2;

                break;

            case "PremiumEconomy":

                $classtype = 3;

                break;

            case "Business":

                $classtype = 4;

                break;

            case "PremiumBusiness":

                $classtype = 5;

                break;

            case "First":

                $classtype = 6;

                break;

            case "Any":

                $classtype = 1;

                break;

            default:

                $classtype = 1;
        }

        return $classtype;
    }
}

/* cabin class type Api end */





/**

 * ------------------------------------------

 * Get Journey Type 

 * ------------------------------------------

 */





if (!function_exists('get_journey_type')) {

    function get_journey_type($value)

    {

        switch ($value) {

            case "1":

                $type = "OneWay";

                break;

            case "2":

                $type = "RoundTrip";

                break;

            default:

                $type = "MultiCity";
        }

        return $type;
    }
}

/* Get journey type  end */



//*  Api journey type */





if (!function_exists('get_api_journey_type')) {

    function get_api_journey_type($value)

    {

        switch ($value) {

            case "OneWay":

                $type = 1;

                break;

            case "RoundTrip":

                $type = 2;

                break;

            case "MultiCity":

                $type = 3;

                break;

            default:

                $type = 1;
        }

        return $type;
    }
}

/* Get journey type  end */



/**

 * ------------------------------------------

 * Get  Check Domestic Type

 * ------------------------------------------

 */



if (!function_exists('check_domestic_type')) {

    function check_domestic_type($countryCode)

    {

        $uniqueCountryCode  =  array_unique($countryCode);

        $domestic_type   =  0;

        if (count($uniqueCountryCode) == 1) {

            if ($uniqueCountryCode[0] == "IN") {

                $domestic_type = 1;
            }
        }

        return $domestic_type;
    }
}

/*Get  Check Domestic Type End */



/**

 * -------------------------------------

 * Generate Random Token ID Use for Search

 * -------------------------------------

 */

if (!function_exists('generate_token_ticket_upload')) {

    function generate_token_ticket_upload()

    {

        date_default_timezone_set("UTC");

        $t = microtime(true);

        $micro = sprintf("%03d", ($t - floor($t)) * 1000);

        $date = new DateTime(date('Y-m-d H:i:s.' . $micro));

        $timestamp = $date->format("Y-m-d\TH:i:s:") . $micro . 'Z';

        $string_value = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 8)), 8, 8);

        $tokenid = sha1(base64_encode(sha1(base64_decode($string_value) . $timestamp, true)));

        return $tokenid;
    }
}
/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function get_flight_fare_upload_ticket($price, $deal, $webpartnerInfo, $super_admin_gst_state_code, $passenger, $totalpax)
{
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
    $sel_markup_data = array();
    $sel_discount_data = array();
    if ($deal) {
        $markup_value   = ($deal['markup'] * $totalpax);
        if ($deal['display_markup'] == 'in_tax') {
            $Tax = $price['Tax'] + $markup_value;
        } elseif ($deal['display_markup'] == 'in_service_charge') {
            $ServiceCharges = $markup_value;
        }
    }
    $GST = array();
    $GST = gst_calculate('Flight', $webpartnerInfo['gst_state_code'], $super_admin_gst_state_code, $ServiceCharges);
    $totalgst = $GST['TotalGSTAmount'];
    $PublishedPrice = round_value($price['PublishedPrice'] + $markup_value + $totalgst);

    unset($GST['TotalGSTAmount']);

    if ($deal) {
        $commission = $commission + round_value(($price['BaseFare'] * $deal['basic']) / 100);
        $commission = $commission + round_value(($price['YQTax'] * $deal['yq']) / 100);
        $commission = $commission + round_value(($price['BaseFare'] * $deal['basic_iata']) / 100);
        $commission = $commission + round_value(($price['YQTax'] * $deal['yq_iata']) / 100);
    }
    /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
    $OfferedPrice = round_value(($PublishedPrice) - ($commission));
    if ($OfferedPrice > 0) {
    } elseif ($OfferedPrice == 0) {
        $commission = 0;
        $OfferedPrice = round_value(($price['PublishedPrice'] + $markup_value + $totalgst));
    } elseif ($OfferedPrice < 0) {

        $commission = 0;
        $OfferedPrice = round_value(($price['PublishedPrice'] + $markup_value + $totalgst));
    }

    $BaseFare = $price['BaseFare'];
    $YQTax = $price['YQTax'];
    $OtherCharges = $price['OtherCharges'];
    $Discount = $extra_discount;
    $AgentCommission = round_value($commission);

    /*-- calculate tds-- */
    $TDS = tds_calculate($AgentCommission);
    /* -- Calculate GST on Markup ---*/
    $per_pax_markup = $deal['markup'];
    $per_pax_tds = round_value(($TDS / $totalpax));
    $per_pax_gst = round_value(($totalgst / $totalpax));
    $per_pax_discount = round_value(($Discount / $totalpax));
    $per_pax_AgentCommission = round_value(($AgentCommission / $totalpax));
    $FareBreakdown = array();
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

    $SuperAdminFareBreakup = array(
        'BaseFare'             => $price['BaseFare'],
        'Tax'                  => $price['Tax'],
        'YQTax'                => $price['YQTax'],
        'OtherCharges'         => floatval($price['OtherCharges']),
        'Discount'             => $price['AgentCommission'] + $price['Discount'],
        'PublishedPrice'       => $price['PublishedPrice'],
        'OfferedPrice'         => $price['OfferedPrice'],
        'TaxBreakup'            => isset($price['TaxBreakup']) ? $price['TaxBreakup'] : array(),
        'TotalBaggageCharges'  => isset($price['TotalBaggageCharges']) ? $price['TotalBaggageCharges'] : 0,
        'TotalMealCharges'     => isset($price['TotalMealCharges']) ? $price['TotalMealCharges'] : 0,
        'TotalSeatCharges'     => isset($price['TotalSeatCharges']) ? $price['TotalSeatCharges'] : 0,
        'SUP_Markup'           => 0,
        'SUP_DisplayMarkup'    => $deal['display_markup'],
        'SUP_Discount'         => 0,
        'SUP_ExtraDiscount'    => $extra_discount,
        'SUP_DiscountType'     => "percent"
    );
    $GST = array();
    $webpGST = gst_calculate('Flight', $webpartnerInfo['gst_state_code'], $super_admin_gst_state_code, 0);
    unset($webpGST['TotalGSTAmount']);
    $WebPartnerFareBreakup = array(
        'BaseFare'             => $price['BaseFare'],
        'Tax'                  => $price['Tax'],
        'YQTax'                => $price['YQTax'],
        'OtherCharges'         => floatval($price['OtherCharges']),
        'Discount'             => $price['AgentCommission'] + $price['Discount'],
        'ServiceCharges'       => $price['ServiceCharges'],
        'AgentCommission'      => $price['AgentCommission'],
        'TDS'                  => $price['TDS'],
        'PublishedPrice'       => $price['PublishedPrice'],
        'OfferedPrice'         => $price['OfferedPrice'],
        'GST'                  => $webpGST,
        'TotalBaggageCharges'  => isset($price['TotalBaggageCharges']) ? $price['TotalBaggageCharges'] : 0,
        'TotalMealCharges'     => isset($price['TotalMealCharges']) ? $price['TotalMealCharges'] : 0,
        'TotalSeatCharges'     => isset($price['TotalSeatCharges']) ? $price['TotalSeatCharges'] : 0,
        'WebPMarkUp'           => $markup_value,
        'WebPDiscount'         => $AgentCommission,
        'WebPDisplayMarkup'    => $deal['display_markup']
    );

    $AgentFareBreakup = $tts_flight_breakup;
    $AgentFareBreakup['AgentWebPMarkUp'] = 0;
    $AgentFareBreakup['AgentWebPDisplayMarkup'] = "in_tax";
    $AgentFareBreakup['AgentWebPDiscount'] = 0;
    $CustomerFareBreakup = $tts_flight_breakup;
    /*  $per_pax_markup=($markup_value/$totalpax);
        $per_pax_tds=round_value(($TDS/$totalpax));
        $per_pax_gst=round_value(($totalgst/$totalpax));
        $per_pax_discount=round_value(($Discount/$totalpax));
        $per_pax_AgentCommission=round_value(($AgentCommission/$totalpax)); */
    return array('Fare' => $tts_flight_breakup, 'FareBreakdown' => $FareBreakdown,'AgentFareBreakup'=>$AgentFareBreakup,'CustomerFareBreakup'=>$CustomerFareBreakup, 'SuperAdminFareBreakup' => $SuperAdminFareBreakup, 'WebPartnerFareBreakup' => $WebPartnerFareBreakup, 'per_pax_markup' => $per_pax_markup, "display_markup" => $deal['display_markup'], "per_pax_tds" => $per_pax_tds, "per_pax_gst" => $per_pax_gst, "per_pax_discount" => $per_pax_discount, "per_pax_AgentCommission" => $per_pax_AgentCommission);
}
/* get Pax type start */

/**

 * -------------------------------------

 * End Generate Random Token ID Use for Search

 * -------------------------------------

 */



function generatePaxDatas($passengerInfo, $passenger_pricing, $forIssuedContactDetail, $extraparameter =  array())
{
    $addMarkupInTax  = 0;
    $addMarkupInserviceCharge  =  0;
    $GST  =  0;
    $TDS  =  0;
    $AgentCommission = 0;
    $Discount = 0;
    $PerpaxaddMarkupInTax  = 0;
    $PerpaxaddMarkupInserviceCharge  =  0;
    $PerpaxGST  =  0;
    $PerpaxTDS  =  0;
    $PerpaxAgentCommission = 0;
    $PerpaxDiscount = 0;
    if (isset($extraparameter['flightFare'])) {
        $flightFare =  $extraparameter['flightFare'];
        $PerpaxGST =  $flightFare['per_pax_gst'];
        $PerpaxDiscount =  $flightFare['per_pax_discount'];
        $PerpaxAgentCommission =  $flightFare['per_pax_AgentCommission'];
        $PerpaxTDS =  $flightFare['per_pax_tds'];
        if ($flightFare['display_markup'] == "in_tax") {
            $PerpaxaddMarkupInTax  = $flightFare['per_pax_markup'];
        } else {
            $PerpaxaddMarkupInserviceCharge  =  $flightFare['per_pax_markup'];
        }
    }
    $NoofAdult = 0;

    $NoofChild = 0;

    $NoofInfant = 0;

    $insertPaxdata  =  array();

    $SuperAdminfareBreakup  =  array();

    $WebPartnerfareBreakup  =  array();

    $insertPaxdata  =  array();

    $email  =  "";

    $mobile_no  =  "";

    $address  = "";

    $city  =  "";

    $state  =  "";

    $country  =  "";

    $pin_code  =  "";



    $totalBaseFare  = 0;

    $PNR  = null;

    $TicketNumber  = null;

    $totalMarkup  = 0;

    $totalTax  = 0;

    $totalYQTax  = 0;

    $totalOtherCharges  = 0;

    $totalDiscount  = 0;

    $PublishedPrice  = 0;

    $OfferedPrice  = 0;

    $TotalBaggageCharges  = 0;

    $TotalMealCharges  = 0;

    $TotalSeatCharges  = 0;

    $SUP_Markup  = 0;

    $SUP_DisplayMarkup  = "in_tax";

    $SUP_Discount  = 0;

    $SUP_ExtraDiscount  = 0;

    $SUP_DiscountType  = 'fixed';

    /* web partner price break  */
    $TotalServiceCharges = 0;
    $WebPMarkUp = 0;

    $WebPDiscount = 0;

    $TotalWebPTax = 0;

    $webPartnerPublishedPrice = 0;

    $webPartnerOfferedPrice = 0;

    $totalWebPartnerDiscount = 0;

    /* end web partner price break  */

    if ($forIssuedContactDetail) {

        $email  =  $forIssuedContactDetail['login_email'];

        $mobile_no  =  $forIssuedContactDetail['mobile_no'];

        $address  =  $forIssuedContactDetail['street'];

        $city  =  $forIssuedContactDetail['city'];

        $state  =  $forIssuedContactDetail['state'];

        $country  =  $forIssuedContactDetail['country'];

        $pin_code  =  $forIssuedContactDetail['pin_code'];
    }

    if ($passengerInfo) {
        foreach ($passengerInfo as $paxsubkey => $passenger) {

            if ($passenger['pax_type'] == "Infant") {
                $GST =  0;
                $Discount = 0;
                $AgentCommission =   0;
                $TDS =  0;
                $addMarkupInTax  = 0;
                $addMarkupInserviceCharge  = 0;
            } else {
                $GST =  $PerpaxGST;
                $Discount =  $PerpaxDiscount;
                $AgentCommission =   $PerpaxAgentCommission;
                $TDS =  $PerpaxTDS;
                $addMarkupInTax  = $PerpaxaddMarkupInTax;
                $addMarkupInserviceCharge  =  $PerpaxaddMarkupInserviceCharge;
            }
            $passenger['base_fare'] =  $passenger_pricing[$passenger['pax_type']]['base_fare'];

            $passenger['tax'] =  $passenger_pricing[$passenger['pax_type']]['tax'] + $addMarkupInTax;

            $passenger['other_charges'] =  $passenger_pricing[$passenger['pax_type']]['other_charges'];

            /*  $passenger['markup'] =  $passenger_pricing[$passenger['pax_type']]['markup']; */

            $baggage = null;

            $meal = null;

            $seat = null;

            $paxfare  =  array();

            $agentfare  =  array();

            $customerfare  =  array();

            $paxBaseFare  = 0;

            $paxMarkup  = 0;

            $paxTax  = 0;

            $paxYQTax  = 0;

            $paxOtherCharges  = 0;

            $paxBaggageCharges  = 0;

            $paxMealCharges  = 0;

            $paxSeatCharges  = 0;

            $paxServiceCharges  = $addMarkupInserviceCharge;

            $PNR =  $passenger['pnr'];

            $TicketNumber =  $passenger['ticket_number'];

            $paxBaseFare =  $passenger['base_fare'];

            $paxTax =  $passenger['tax'];

            $paxOtherCharges =  $passenger['other_charges'];

            /*    $paxMarkup =  $passenger['markup']; */

            $totalMarkup =  $totalMarkup + $paxMarkup;

            $SUP_Markup =  $SUP_Markup + $paxMarkup;

            $totalBaseFare =  $totalBaseFare + $paxBaseFare;

            $totalTax =  $totalTax + $paxTax;

            $TotalWebPTax =  $TotalWebPTax + $paxTax + $paxMarkup;

            $totalOtherCharges =  $totalOtherCharges + $paxOtherCharges;

            $TotalBaggageCharges  =  $paxBaggageCharges + $TotalBaggageCharges;

            $TotalMealCharges  =  $paxMealCharges + $TotalMealCharges;

            $TotalSeatCharges  =  $paxSeatCharges + $TotalSeatCharges;

            $webPartnerPublishedPrice = $totalBaseFare + $TotalWebPTax + $totalOtherCharges + $TotalBaggageCharges + $TotalMealCharges + $TotalSeatCharges;

            $webPartnerOfferedPrice = $webPartnerPublishedPrice;

            $PublishedPrice = $totalBaseFare + $totalTax + $totalOtherCharges + $TotalBaggageCharges + $TotalMealCharges + $TotalSeatCharges;

            $OfferedPrice = $PublishedPrice;

            if ($passenger['pax_type'] == "Adult") {
                $NoofAdult = $NoofAdult + 1;
            }

            if ($passenger['pax_type'] == "Child") {
                $NoofChild = $NoofChild + 1;
            }

            if ($passenger['pax_type'] == "Infant") {
                $NoofInfant = $NoofInfant + 1;
            }



            $apipassengerData = array();



            if (isset($passenger['passport_number']) && $passenger['passport_number'] != "") {

                $PassportNo = $passenger['passport_number'];
            } else {

                $PassportNo = NULL;
            }

            if (isset($passenger['passport_issue_date']) && $passenger['passport_issue_date'] != "") {

                $PassportIssueDate = date("Y-m-d", strtotime($passenger['passport_issue_date'])) . "T00:00:00";
            } else {

                $PassportIssueDate = NULL;
            }

            if (isset($passenger['passport_expiry']) && $passenger['passport_expiry'] != "") {

                $PassportExpDate = date("Y-m-d", strtotime($passenger['passport_expiry'])) . "T00:00:00";
            } else {

                $PassportExpDate = NULL;
            }

            if (isset($passenger['date_of_birth']) && $passenger['date_of_birth'] != '') {

                $dob = date("Y-m-d", strtotime($passenger['date_of_birth'])) . "T00:00:00";
            } else {

                $dob = NULL;
            }

            if (isset($passenger['pan_number']) && $passenger['pan_number'] != "") {

                $pancard = $passenger['pan_number'];
            } else {

                $pancard = NULL;
            }

            if (isset($passenger['passport_nationality']) && $passenger['passport_nationality'] != "") {

                $nationality = $passenger['passport_nationality'];
            } else {

                $nationality = NULL;
            }

            $paxfare = array(

                'BaseFare' => $paxBaseFare,
                'Tax' => ($paxTax),
                'YQTax' => $paxYQTax,
                'ServiceCharges' => $paxServiceCharges,
                'OtherCharges' => $paxOtherCharges,
                'Discount' => $Discount,
                'AgentCommission' => $AgentCommission,
                'TDS' => $TDS,
                'GSTAmount' =>  $GST,
                'PublishedPrice' => round_value($paxBaseFare + $paxTax + $paxOtherCharges + $paxServiceCharges + $GST),
                'OfferedPrice' =>   round_value($paxBaseFare + $paxTax + $paxOtherCharges + $paxServiceCharges + $GST - ($AgentCommission + $Discount)),
                'BaggageCharges' => $paxBaggageCharges,
                'MealCharges' => $paxMealCharges,
                'SeatCharges' => $paxSeatCharges,
                "WebPMarkUp" => 0,
                "WebPDiscount" => 0,
            );

            $agentfare = array(
                'BaseFare' => $paxBaseFare,
                'Tax' => ($paxTax),
                'YQTax' => $paxYQTax,
                'ServiceCharges' => $paxServiceCharges,
                'OtherCharges' => $paxOtherCharges,
                'Discount' => $Discount,
                'AgentCommission' => $AgentCommission,
                'TDS' => $TDS,
                'GSTAmount' =>  $GST,
                'PublishedPrice' => round_value($paxBaseFare + $paxTax + $paxOtherCharges + $paxServiceCharges + $GST),
                'OfferedPrice' =>   round_value($paxBaseFare + $paxTax + $paxOtherCharges + $paxServiceCharges + $GST - ($AgentCommission + $Discount)),
                'BaggageCharges' => $paxBaggageCharges,
                'MealCharges' => $paxMealCharges,
                'SeatCharges' => $paxSeatCharges,
                "MarkUp" => 0,
            );

            $gender = strtolower($passenger['gendar']);

            $booking_status =  "Processing";

            if ($PNR != "") {

                $booking_status =  "Confirmed";
            }

            $apipassengerData = array(

                "title" => $passenger['title'],

                "first_name" => $passenger['first_name'],

                "last_name" => $passenger['last_name'],

                "pax_type" => $passenger['pax_type'],

                "gendar" => $gender,

                "date_of_birth" => $dob,

                "pan_number" => $pancard,

                "passport_number" => $PassportNo,

                "passport_expiry" => $PassportExpDate,

                "passport_issue_date" => $PassportIssueDate,

                "lead_pax" =>  $paxsubkey == "1" ? true : false,

                "email_id" => $email,

                "mobile_number" => $mobile_no,

                "address_1" => $address,

                "address_2" => "",

                "city" => $city,

                "country_code" => "IN",

                "nationality" => $nationality,

                "country_name" => $country,

                "ff_airline" => null,

                "ff_number" => null,

                "fare" => json_encode($paxfare),

                "baggage" => $baggage,

                "meal" => $meal,

                "seat" => $seat,

                "ticket_number" => $TicketNumber,

                'booking_status' => $booking_status

            );

            array_push($insertPaxdata, $apipassengerData);
        }

        $SuperAdminfareBreakup  =  array(

            "BaseFare" => $totalBaseFare,

            "Tax" => $totalTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalDiscount,

            "PublishedPrice" => $PublishedPrice,

            "OfferedPrice" => $OfferedPrice,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "SUP_Markup" => $SUP_Markup,

            "SUP_DisplayMarkup" => $SUP_DisplayMarkup,

            "SUP_Discount" => $SUP_Discount,

            "SUP_ExtraDiscount" => $SUP_ExtraDiscount,

            "SUP_DiscountType" => $SUP_DiscountType

        );

        $WebPartnerfareBreakup  =  array(

            "BaseFare" => $totalBaseFare,

            "Tax" => $TotalWebPTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalWebPartnerDiscount,

            "PublishedPrice" => $webPartnerPublishedPrice,

            "OfferedPrice" => $webPartnerOfferedPrice,

            "AgentCommission" => $AgentCommission,

            "ServiceCharges" => $TotalServiceCharges,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "TDS" => $TDS,

            "GST" => array(

                "CGSTAmount" => 0,

                "CGSTRate" => 0,

                "IGSTAmount" => 0,

                "IGSTRate" => 18,

                "SGSTAmount" => 0,

                "SGSTRate" => 0,

                "TaxableAmount" => 0

            ),

            "WebPMarkUp" => $WebPMarkUp,

            "WebPDiscount" => $WebPDiscount

        );
    }

    return  array("paxData" => $insertPaxdata, "WebPartnerfareBreakup" => $WebPartnerfareBreakup, "SuperAdminfareBreakup" => $SuperAdminfareBreakup, "NoofAdult" => $NoofAdult, "NoofChild" => $NoofChild, "NoofInfant" => $NoofInfant, "PNR" => $PNR);
}

function generatePaxData($passengerInfo, $passenger_pricing, $forIssuedContactDetail, $extraparameter =  array())
{
    $addMarkupInTax  = 0;
    $addMarkupInserviceCharge  =  0;
    $GST  =  0;
    $TDS  =  0;
    $AgentCommission = 0;
    $Discount = 0;
    $PerpaxaddMarkupInTax  = 0;
    $PerpaxaddMarkupInserviceCharge  =  0;
    $PerpaxGST  =  0;
    $PerpaxTDS  =  0;
    $PerpaxAgentCommission = 0;
    $PerpaxDiscount = 0;

    if (isset($extraparameter['flightFare'])) {
        $flightFare =  $extraparameter['flightFare'];
        $PerpaxGST =  $flightFare['per_pax_gst'];
        $PerpaxDiscount =  $flightFare['per_pax_discount'];
        $PerpaxAgentCommission =  $flightFare['per_pax_AgentCommission'];
        $PerpaxTDS =  $flightFare['per_pax_tds'];
        if ($flightFare['display_markup'] == "in_tax") {
            $PerpaxaddMarkupInTax  = $flightFare['per_pax_markup'];
        } else {
            $PerpaxaddMarkupInserviceCharge  =  $flightFare['per_pax_markup'];
        }
    }
    $NoofAdult = 0;

    $NoofChild = 0;

    $NoofInfant = 0;

    $insertPaxdata  =  array();

    $SuperAdminfareBreakup  =  array();

    $WebPartnerfareBreakup  =  array();

    $totalMarkup = 0;

    $totalBaseFare = 0;

    $totalTax = 0;

    $totalYQTax = 0;

    $totalOtherCharges = 0;

    $totalDiscount = 0;

    $PublishedPrice = 0;

    $OfferedPrice = 0;

    $TotalBaggageCharges = 0;

    $TotalMealCharges = 0;

    $TotalSeatCharges = 0;

    $SUP_Markup = 0;

    $SUP_DisplayMarkup = "in_tax";

    $SUP_Discount = 0;

    $SUP_ExtraDiscount = 0;

    $SUP_DiscountType = "fixed";

    /**
     * WebPartner FareBreakUp Variable
     */

    $TotalWebPTax = 0;

    $totalWebPartnerDiscount = 0;

    $webPartnerPublishedPrice = 0;

    $webPartnerOfferedPrice = 0;

    $TotalServiceCharges = 0;

    $WebPMarkUp = 0;

    $WebPDiscount = 0;


    /**
     *  Agent FareBreakUp Variable
     */

    $TotalAgentPTax = 0;

    $totalAgentDiscount = 0;

    $AgentPublishedPrice = 0;

    $agentOfferedPrice = 0;

    $TotalAgentServiceCharges = 0;

    $AgentWebPMarkUp = 0;

    $AgentWebPDisplayMarkup = "in_tax";

    $AgentWebPDiscount = 0;


    if ($forIssuedContactDetail) {

        $email  =  isset($forIssuedContactDetail['login_email'])?$forIssuedContactDetail['login_email']:$forIssuedContactDetail['email_id'];

        $mobile_no  =  $forIssuedContactDetail['mobile_no'];

        $address  =  isset($forIssuedContactDetail['street'])?$forIssuedContactDetail['street']:$forIssuedContactDetail['address'];

        $city  =  $forIssuedContactDetail['city'];

        $state  =  $forIssuedContactDetail['state'];

        $country  =  $forIssuedContactDetail['country'];

        $pin_code  =  $forIssuedContactDetail['pin_code'];

        $role = $forIssuedContactDetail['role'];

        $webgstCode = $forIssuedContactDetail['webpgstCode'];

        $gstcode = $forIssuedContactDetail['gst']['gst_state_code'];
    }

    if ($passengerInfo) {
        foreach ($passengerInfo as $paxsubkey => $passenger) {

            if ($passenger['pax_type'] == "Infant") {
                $GST =  0;
                $Discount = 0;
                $AgentCommission =   0;
                $TDS =  0;
                $addMarkupInTax  = 0;
                $addMarkupInserviceCharge  = 0;
            } else {
                $GST =  $PerpaxGST;
                $Discount =  $PerpaxDiscount;
                $AgentCommission =   $PerpaxAgentCommission;
                $TDS =  $PerpaxTDS;
                $addMarkupInTax  = $PerpaxaddMarkupInTax;
                $addMarkupInserviceCharge  =  $PerpaxaddMarkupInserviceCharge;
            }
            $passenger['base_fare'] =  $passenger_pricing[$passenger['pax_type']]['base_fare'];

            $passenger['tax'] =  $passenger_pricing[$passenger['pax_type']]['tax'];

            $passenger['other_charges'] =  $passenger_pricing[$passenger['pax_type']]['other_charges'];

            $baggage = null;

            $meal = null;

            $seat = null;

            $webpaxfare  =  array();

            $paxfare  =  array();

            $customerpaxfare = array();

            $webpaxBaseFare = 0;

            $PNR =  $passenger['pnr'];

            $TicketNumber =  $passenger['ticket_number'];

            $webpaxTax = 0;


            $webpaxYQTax = 0;

            $webpaxServiceCharges = 0;

            $webpaxOtherCharges = 0;

            $webpaxBaggageCharges = 0;

            $webpaxMealCharges = 0;

            $webpaxSeatCharges = 0;

            $webpaxmarkup = 0;

            $webpaxdiscount = 0;

            $agentpaxTax = 0;

            $agentpaxServiceCharges = 0;

            $agentpaxmarkup = 0;

            $agentpaxdiscount = 0;

            $totalMarkup = $totalMarkup + $addMarkupInTax + $addMarkupInserviceCharge;

            $webpaxmarkup = $addMarkupInTax + $addMarkupInserviceCharge;

            $webpaxdiscount = $AgentCommission;

            $webpaxBaseFare =  $passenger['base_fare'];

            $webpaxTax = $passenger['tax'];

            $webpaxOtherCharges = $passenger['other_charges'];

            $agentpaxTax = $webpaxTax + $addMarkupInTax;

            $agentpaxServiceCharges = $addMarkupInserviceCharge;
            /**
             * SuperAdmin Calculation
             */
            $totalBaseFare = $totalBaseFare + $webpaxBaseFare;

            $totalTax = $totalTax + $webpaxTax;

            $totalOtherCharges = $totalOtherCharges + $webpaxOtherCharges;

            $PublishedPrice += $webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges;

            $OfferedPrice += $webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges;

            /**
             * Webpartner FareBreak calculation
             */

            $TotalWebPTax += $webpaxTax;

            $webPartnerPublishedPrice += $webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges;

            $webPartnerOfferedPrice += $webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges;

            $WebPMarkUp += $webpaxmarkup;

            $WebPDiscount += $webpaxdiscount;

            /**
             * Agent fareBreak calculation
             */
            $TotalAgentPTax += $agentpaxTax;

            $AgentPublishedPrice += $webpaxBaseFare + $agentpaxTax + $webpaxOtherCharges + $agentpaxServiceCharges + $GST;

            $agentOfferedPrice += $webpaxBaseFare + $agentpaxTax + $webpaxOtherCharges + $agentpaxServiceCharges + $GST - ($AgentCommission + $Discount);

            $TotalAgentServiceCharges += $agentpaxServiceCharges;

            if ($passenger['pax_type'] == "Adult") {
                $NoofAdult = $NoofAdult + 1;
            }

            if ($passenger['pax_type'] == "Child") {
                $NoofChild = $NoofChild + 1;
            }

            if ($passenger['pax_type'] == "Infant") {
                $NoofInfant = $NoofInfant + 1;
            }

            $apipassengerData = array();

            if (isset($passenger['passport_number']) && $passenger['passport_number'] != "") {

                $PassportNo = $passenger['passport_number'];
            } else {
                $PassportNo = NULL;
            }

            if (isset($passenger['passport_issue_date']) && $passenger['passport_issue_date'] != "") {

                $PassportIssueDate = date("Y-m-d", strtotime($passenger['passport_issue_date'])) . "T00:00:00";
            } else {

                $PassportIssueDate = NULL;
            }

            if (isset($passenger['passport_expiry']) && $passenger['passport_expiry'] != "") {

                $PassportExpDate = date("Y-m-d", strtotime($passenger['passport_expiry'])) . "T00:00:00";
            } else {

                $PassportExpDate = NULL;
            }

            if (isset($passenger['date_of_birth']) && $passenger['date_of_birth'] != '') {

                $dob = date("Y-m-d", strtotime($passenger['date_of_birth'])) . "T00:00:00";
            } else {

                $dob = NULL;
            }

            if (isset($passenger['pan_number']) && $passenger['pan_number'] != "") {

                $pancard = $passenger['pan_number'];
            } else {

                $pancard = NULL;
            }

            if (isset($passenger['passport_nationality']) && $passenger['passport_nationality'] != "") {

                $nationality = $passenger['passport_nationality'];
            } else {
                $nationality = NULL;
            }

            $webpaxfare = array(
                'BaseFare' => $webpaxBaseFare,
                'Tax' => ($webpaxTax),
                'YQTax' => $webpaxYQTax,
                'ServiceCharges' => $webpaxServiceCharges,
                'OtherCharges' => $webpaxOtherCharges,
                'Discount' => $Discount,
                'AgentCommission' => 0,
                'TDS' => 0,
                'GSTAmount' =>  0,
                'PublishedPrice' => round_value($webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges),
                'OfferedPrice' =>   round_value($webpaxBaseFare + $webpaxTax + $webpaxOtherCharges + $webpaxServiceCharges),
                'BaggageCharges' => $webpaxBaggageCharges,
                'MealCharges' => $webpaxMealCharges,
                'SeatCharges' => $webpaxSeatCharges,
                "WebPMarkUp" => $webpaxmarkup,
                "WebPDiscount" => $webpaxdiscount,
            );

            $paxfare = array(
                'BaseFare' => $webpaxBaseFare,
                'Tax' => ($agentpaxTax),
                'YQTax' => $webpaxYQTax,
                'ServiceCharges' => $agentpaxServiceCharges,
                'OtherCharges' => $webpaxOtherCharges,
                'Discount' => $Discount,
                'AgentCommission' => $AgentCommission,
                'TDS' => $TDS,
                'GSTAmount' =>  $GST,
                'PublishedPrice' => round_value($webpaxBaseFare + $agentpaxTax + $webpaxOtherCharges + $agentpaxServiceCharges + $GST),
                'OfferedPrice' =>   round_value($webpaxBaseFare + $agentpaxTax + $webpaxOtherCharges + $agentpaxServiceCharges + $GST - ($AgentCommission + $Discount)),
                'BaggageCharges' => $webpaxBaggageCharges,
                'MealCharges' => $webpaxMealCharges,
                'SeatCharges' => $webpaxSeatCharges,
                "MarkUp" => $agentpaxmarkup,
                "AgentDiscount" => $agentpaxdiscount,
            );

            $customerpaxfare = $paxfare;

            unset($customerpaxfare['MarkUp'], $customerpaxfare['AgentDiscount']);

            if ($role == "agent") {
                $paxfare = json_encode($paxfare);
                $customerpaxfare = NULL;
            } else {
                $paxfare = NULL;
                $customerpaxfare = json_encode($customerpaxfare);
            }

            $gender = strtolower($passenger['gendar']);

            $booking_status =  "Processing";

            if ($PNR != "") {
                $booking_status =  "Confirmed";
            }

            $apipassengerData = array(

                "title" => $passenger['title'],

                "first_name" => $passenger['first_name'],

                "last_name" => $passenger['last_name'],

                "pax_type" => $passenger['pax_type'],

                "gendar" => $gender,

                "date_of_birth" => $dob,

                "pan_number" => $pancard,

                "passport_number" => $PassportNo,

                "passport_expiry" => $PassportExpDate,

                "passport_issue_date" => $PassportIssueDate,

                "lead_pax" =>  $paxsubkey == "1" ? true : false,

                "email_id" => $email,

                "mobile_number" => $mobile_no,

                "address_1" => $address,

                "address_2" => "",

                "city" => $city,

                "country_code" => "IN",

                "nationality" => $nationality,

                "country_name" => $country,

                "ff_airline" => null,

                "ff_number" => null,

                "fare" => json_encode($webpaxfare),

                "agent_fare" => $paxfare,

                "customer_fare" => $customerpaxfare,

                "baggage" => $baggage,

                "meal" => $meal,

                "seat" => $seat,

                "ticket_number" => $TicketNumber,

                'booking_status' => $booking_status

            );

            array_push($insertPaxdata, $apipassengerData);
        }

        $SuperAdminfareBreakup  =  array(

            "BaseFare" => $totalBaseFare,

            "Tax" => $totalTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalDiscount,

            "PublishedPrice" => $PublishedPrice,

            "OfferedPrice" => $OfferedPrice,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "SUP_Markup" => $SUP_Markup,

            "SUP_DisplayMarkup" => $SUP_DisplayMarkup,

            "SUP_Discount" => $SUP_Discount,

            "SUP_ExtraDiscount" => $SUP_ExtraDiscount,

            "SUP_DiscountType" => $SUP_DiscountType

        );

        $WebPartnerfareBreakup  =  array(

            "BaseFare" => $totalBaseFare,

            "Tax" => $TotalWebPTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalWebPartnerDiscount,

            "PublishedPrice" => $webPartnerPublishedPrice,

            "OfferedPrice" => $webPartnerOfferedPrice,

            "AgentCommission" => 0,

            "ServiceCharges" => $TotalServiceCharges,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "TDS" => 0,

            "GST" => array(

                "CGSTAmount" => 0,

                "CGSTRate" => 0,

                "IGSTAmount" => 0,

                "IGSTRate" => 18,

                "SGSTAmount" => 0,

                "SGSTRate" => 0,

                "TaxableAmount" => 0

            ),

            "WebPMarkUp" => $WebPMarkUp,

            "WebPDiscount" => $WebPDiscount

        );

        $Agentgst = gst_calculate("flight", $gstcode, $webgstCode, $TotalAgentServiceCharges);
        unset($Agentgst['TotalGSTAmount']);
        $AgentfareBreakup  =  array(
            "BaseFare" => $totalBaseFare,

            "Tax" => $TotalAgentPTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalAgentDiscount,

            "PublishedPrice" => $AgentPublishedPrice,

            "OfferedPrice" => $agentOfferedPrice,

            "AgentCommission" => $AgentCommission,

            "ServiceCharges" => $TotalAgentServiceCharges,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "TDS" => $TDS,

            "GST" => $Agentgst,

            "AgentWebPMarkUp" => $AgentWebPMarkUp,

            "AgentWebPDisplayMarkup" => $AgentWebPDisplayMarkup,

            "AgentWebPDiscount" => $AgentWebPDiscount

        );

        $CustomerfareBreakup  =  array(
            "BaseFare" => $totalBaseFare,

            "Tax" => $TotalAgentPTax,

            "YQTax" => $totalYQTax,

            "OtherCharges" => $totalOtherCharges,

            "Discount" => $totalAgentDiscount,

            "PublishedPrice" => $AgentPublishedPrice,

            "OfferedPrice" => $agentOfferedPrice,

            "AgentCommission" => $AgentCommission,

            "ServiceCharges" => $TotalAgentServiceCharges,

            "TotalBaggageCharges" => $TotalBaggageCharges,

            "TotalMealCharges" => $TotalMealCharges,

            "TotalSeatCharges" => $TotalSeatCharges,

            "TDS" => $TDS,

            "GST" => $Agentgst

        );
    }

    return  array("paxData" => $insertPaxdata, "CustomerfareBreakup" => $CustomerfareBreakup, "AgentfareBreakup" => $AgentfareBreakup, "AgentfareBreakup" => $AgentfareBreakup, "WebPartnerfareBreakup" => $WebPartnerfareBreakup, "SuperAdminfareBreakup" => $SuperAdminfareBreakup, "NoofAdult" => $NoofAdult, "NoofChild" => $NoofChild, "NoofInfant" => $NoofInfant, "PNR" => $PNR);
}
