<?php
use App\Modules\Airservice\Models\RestModel;
use CodeIgniter\I18n\Time;

/**
 * ------------------------------------------------
 * API Multi Curl Request
 * ------------------------------------------------
 */
function MultiCurl_Request(array $request)
{
    if ($request) {
        $response = array();
        $errno_array = array();
        $i = 0;
        foreach ($request as $key => $item) {
            $itemarray = array();
            if (isset($item['Supplier'])) {
                $itemarray = array($item);
            } else {
                $itemarray = $item;
            }
            foreach ($itemarray as $subitem) {
                if ($subitem['Supplier'] == 'TBO') {
                    $subkey = $i;
                    $url = $subitem['URL'];
                    $request_json = $subitem['Request'];
                    $chs[$subkey] = curl_init();
                    curl_setopt($chs[$subkey], CURLOPT_URL, $url);
                    curl_setopt($chs[$subkey], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chs[$subkey], CURLOPT_ENCODING, 'gzip');
                    curl_setopt($chs[$subkey], CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($chs[$subkey], CURLOPT_TIMEOUT, 30);
                    curl_setopt($chs[$subkey], CURLOPT_CUSTOMREQUEST, 'POST');
                    curl_setopt($chs[$subkey], CURLOPT_POSTFIELDS, $request_json);
                    curl_setopt($chs[$subkey], CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: gzip,deflate', 'Cache-Control: no-cache', 'Pragma: no-cache', 'Content-Length: ' . strlen($request_json)));
                }
                if ($subitem['Supplier'] == 'TRAVELPORT') {
                    $subkey = $i;
                    $url = $subitem['URL'];
                    $auth = base64_encode($subitem['Credentials']);
                    $chs[$subkey] = curl_init();
                    $header = array(
                        "Content-Type: text/xml;charset=UTF-8",
                        "Accept: gzip,deflate",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: \"\"",
                        "Authorization: Basic $auth",
                        "Content-length: " . strlen($subitem['Request']),
                    );
                    curl_setopt($chs[$subkey], CURLOPT_URL, $url);
                    curl_setopt($chs[$subkey], CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($chs[$subkey], CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($chs[$subkey], CURLOPT_ENCODING, 'gzip');
                    curl_setopt($chs[$subkey], CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($chs[$subkey], CURLOPT_TIMEOUT, 30);
                    curl_setopt($chs[$subkey], CURLOPT_POST, true);
                    curl_setopt($chs[$subkey], CURLOPT_POSTFIELDS, $subitem['Request']);
                    curl_setopt($chs[$subkey], CURLOPT_HTTPHEADER, $header);
                    curl_setopt($chs[$subkey], CURLOPT_RETURNTRANSFER, true);
                }
                if ($subitem['Supplier'] == 'INDIGO') {
                    $subkey = $i;
                    $url = $subitem['URL'];

                    $auth = base64_encode($subitem['Credentials']);
                    $chs[$subkey] = curl_init();
                    $header = array(
                        "Content-Type: text/xml;charset=UTF-8",
                        "Accept: gzip,deflate",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: \"\"",
                        "Authorization: Basic $auth",
                        "Content-length: " . strlen($subitem['Request']),
                    );
                    curl_setopt($chs[$subkey], CURLOPT_URL, $url);
                    curl_setopt($chs[$subkey], CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($chs[$subkey], CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($chs[$subkey], CURLOPT_ENCODING, 'gzip');
                    curl_setopt($chs[$subkey], CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($chs[$subkey], CURLOPT_TIMEOUT, 30);
                    curl_setopt($chs[$subkey], CURLOPT_POST, true);
                    curl_setopt($chs[$subkey], CURLOPT_POSTFIELDS, $subitem['Request']);
                    curl_setopt($chs[$subkey], CURLOPT_HTTPHEADER, $header);
                    curl_setopt($chs[$subkey], CURLOPT_RETURNTRANSFER, true);
                }

                $i++;
            }
        }

        //create the multiple cURL handle
        $mh = curl_multi_init();
        //add the handles
        foreach ($chs as &$ch) {
            curl_multi_add_handle($mh, $ch);
        }
        $active = null;
        //execute the handles
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        $q = 0;
        foreach ($request as $item) {
            $itemarray = array();
            if (isset($item['Supplier'])) {
                $itemarray = array($item);
            } else {
                $itemarray = $item;
            }
            foreach ($itemarray as $subitem) {
                $subkey = $q;
                $response[$subkey] = array(
                    'Supplier' => $subitem['Supplier'],
                    'Request' => $subitem['Request'],
                    'Response' => curl_multi_getcontent($chs[$subkey]),
                );
                if (isset($subitem['JourneyIdentifiers'])) {
                    $response[$subkey]['JourneyIdentifiers'] = $subitem['JourneyIdentifiers'];
                }
                curl_multi_remove_handle($mh, $chs[$subkey]);
                $q++;
            }
        }
        curl_multi_close($mh);
        return $response;
    } else {
        return array();
        // $message=api_validation_message('supplier_inactive_error');
        // api_custom_message(400,$message,false);
    }
}

/**
 * ------------------------------------------
 * Static Top Airline List
 * ------------------------------------------
 */

function static_airline_array_list()
{
    return array('6E' => 'Indigo', 'SG' => 'Spicejet', 'G8' => 'Go First', 'I5' => 'Air Asia', 'AI' => 'Air India', 'UK' => 'Vistara', 'G9' => 'Air Arabia', 'EY' => 'Etihad Airways', 'LH' => 'Lufthansa', 'QR' => 'Qatar Airways', 'SQ' => 'Singapore Airlines', 'KU' => 'Kuwait Airways Corporation', 'EK' => 'Emirates Airlines', 'AF' => 'Air France', 'TG' => 'Thai Airways International', 'FZ' => 'Fly Dubai', 'IX' => 'Air India Express', 'BA' => 'British Airways', 'KL' => 'Klm Royal Dutch Airlines', 'LX' => 'Swiss');
}

/**
 * ------------------------------------------
 * Static Top Airport List
 * ------------------------------------------
 */

function static_airport_array_list()
{
    return array(
        'DEL' => array('code' => 'DEL', 'name' => 'Delhi Indira Gandhi Intl', 'city_code' => 'DEL', 'city_name' => 'Delhi', 'country_name' => 'India', 'country_code' => 'IN'),
        'BOM' => array('code' => 'BOM', 'name' => 'Chhatrapati Shivaji', 'city_code' => 'BOM', 'city_name' => 'Mumbai', 'country_name' => 'India', 'country_code' => 'IN'),
        'GOI' => array('code' => 'GOI', 'name' => 'Dabolim Arpt', 'city_code' => 'GOI', 'city_name' => 'Goa In', 'country_name' => 'India', 'country_code' => 'IN'),
        'BLR' => array('code' => 'BLR', 'name' => 'Bengaluru Intl Arpt', 'city_code' => 'BLR', 'city_name' => 'Bengaluru', 'country_name' => 'India', 'country_code' => 'IN'),
        'PNQ' => array('code' => 'PNQ', 'name' => 'Lohegaon Arpt', 'city_code' => 'PNQ', 'city_name' => 'Pune', 'country_name' => 'India', 'country_code' => 'IN'),
        'HYD' => array('code' => 'HYD', 'name' => 'Shamshabad Rajiv Gandhi Intl Arpt', 'city_code' => 'HYD', 'city_name' => 'Hyderabad', 'country_name' => 'India', 'country_code' => 'IN'),
        'CCU' => array('code' => 'CCU', 'name' => 'Netaji Subhas Chandra Bose Intl', 'city_code' => 'CCU', 'city_name' => 'Kolkata', 'country_name' => 'India', 'country_code' => 'IN'),
        'MAA' => array('code' => 'MAA', 'name' => 'Chennai Arpt', 'city_code' => 'MAA', 'city_name' => 'Chennai', 'country_name' => 'India', 'country_code' => 'IN'),
        'JLR' => array('code' => 'JLR', 'name' => 'Jabalpur Airport', 'city_code' => 'JLR', 'city_name' => 'Jabalpur', 'country_name' => 'India', 'country_code' => 'IN'),
        'DBR' => array('code' => 'DBR', 'name' => 'Darbhanga Airport', 'city_code' => 'DBR', 'city_name' => 'Darbhanga', 'country_name' => 'India', 'country_code' => 'IN'),
        'SXR' => array('code' => 'SXR', 'name' => 'Srinagar Arpt', 'city_code' => 'SXR', 'city_name' => 'Srinagar', 'country_name' => 'India', 'country_code' => 'IN'),
        'AMD' => array('code' => 'AMD', 'name' => 'Sardar Vallabh Bhai Patel Intl Arpt', 'city_code' => 'AMD', 'city_name' => 'Ahmedabad', 'country_name' => 'India', 'country_code' => 'IN'),
        'PAT' => array('code' => 'PAT', 'name' => 'Jai Prakash Narayan Arpt', 'city_code' => 'PAT', 'city_name' => 'Patna', 'country_name' => 'India', 'country_code' => 'IN'),
        'LKO' => array('code' => 'LKO', 'name' => 'Amausi Arpt', 'city_code' => 'LKO', 'city_name' => 'Lucknow', 'country_name' => 'India', 'country_code' => 'IN'),
        'JAI' => array('code' => 'JAI', 'name' => 'Sanganeer Arpt', 'city_code' => 'JAI', 'city_name' => 'Jaipur', 'country_name' => 'India', 'country_code' => 'IN'),
        'VTZ' => array('code' => 'VTZ', 'name' => 'Vishakhapatnam', 'city_code' => 'VTZ', 'city_name' => 'Vishakhapatanam', 'country_name' => 'India', 'country_code' => 'IN'),
        'ATQ' => array('code' => 'ATQ', 'name' => 'Raja Sansi Arpt', 'city_code' => 'ATQ', 'city_name' => 'Amritsar', 'country_name' => 'India', 'country_code' => 'IN'),
        'IXC' => array('code' => 'IXC', 'name' => 'Chandigarh Arpt', 'city_code' => 'IXC', 'city_name' => 'Chandigarh', 'country_name' => 'India', 'country_code' => 'IN'),
        'JRG' => array('code' => 'JRG', 'name' => 'Jharsuguda Airport', 'city_code' => 'JRG', 'city_name' => 'Odisha', 'country_name' => 'India', 'country_code' => 'IN'),
        'IDR' => array('code' => 'IDR', 'name' => 'Devi Ahilya Bai Holkar Arpt', 'city_code' => 'IDR', 'city_name' => 'Indore', 'country_name' => 'India', 'country_code' => 'IN'),
        'RAJ' => array('code' => 'RAJ', 'name' => 'Rajkot Civil Arpt', 'city_code' => 'RAJ', 'city_name' => 'Rajkot', 'country_name' => 'India', 'country_code' => 'IN'),
        'BHO' => array('code' => 'BHO', 'name' => 'Raja Bhoj Arpt', 'city_code' => 'BHO', 'city_name' => 'Bhopal', 'country_name' => 'India', 'country_code' => 'IN'),
        'IXR' => array('code' => 'IXR', 'name' => 'Birsa Munda Arpt', 'city_code' => 'IXR', 'city_name' => 'Ranchi', 'country_name' => 'India', 'country_code' => 'IN'),
        'IXU' => array('code' => 'IXU', 'name' => 'Chikkalthana Arpt', 'city_code' => 'IXU', 'city_name' => 'Aurangabad', 'country_name' => 'India', 'country_code' => 'IN'),
        'UDR' => array('code' => 'UDR', 'name' => 'Maharana Pratap Arpt', 'city_code' => 'UDR', 'city_name' => 'Udaipur', 'country_name' => 'India', 'country_code' => 'IN'),
        'NAG' => array('code' => 'NAG', 'name' => 'Dr Ambedkar Intl Arpt', 'city_code' => 'NAG', 'city_name' => 'Nagpur', 'country_name' => 'India', 'country_code' => 'IN'),
        'RPR' => array('code' => 'RPR', 'name' => 'Raipur Arpt', 'city_code' => 'RPR', 'city_name' => 'Raipur', 'country_name' => 'India', 'country_code' => 'IN'),
        'JDH' => array('code' => 'JDH', 'name' => 'Jodhpur Arpt', 'city_code' => 'JDH', 'city_name' => 'Jodhpur', 'country_name' => 'India', 'country_code' => 'IN'),
        'VNS' => array('code' => 'VNS', 'name' => 'Lal Bahadur Shastri Arpt', 'city_code' => 'VNS', 'city_name' => 'Varanasi', 'country_name' => 'India', 'country_code' => 'IN'),
        'BBI' => array('code' => 'BBI', 'name' => 'Biju Patnaik Arpt', 'city_code' => 'BBI', 'city_name' => 'Bhubaneswar', 'country_name' => 'India', 'country_code' => 'IN'),
        'CNN' => array('code' => 'CNN', 'name' => 'Kannur International Airport', 'city_code' => 'CNN', 'city_name' => 'Kannur', 'country_name' => 'India', 'country_code' => 'IN')
    );
}



/**
 * ------------------------------------------
 * Identification of Fare type for Flight
 * ------------------------------------------
 */

if (!function_exists('get_flight_fare_type')) {
    function get_flight_fare_type($value)
    {
        switch ($value) {
            case "FF":
                $faretype = "FF";
                break;
            case "SPLC":
                $faretype = "SPLC";
                break;
            case "CPN":
                $faretype = "Coupon";
                break;
            case "CPNS1":
                $faretype = "CPNS1";
                break;
            case "TBF":
                $faretype = "TBF";
                break;
            case "SPLS":
                $faretype = "SPLS";
                break;
            case "CPNS":
                $faretype = "CPNS";
                break;
            case "CDF":
                $faretype = "CDF";
                break;
            case "SME":
                $faretype = "SME";
                break;
            case "CORP":
                $faretype = "Corporate";
                break;
            case "FLX":
                $faretype = "FLX";
                break;
            case "SME.CrpCon":
                $faretype = "SME";
                break;
            case "InstantPur":
                $faretype = "InstantPur";
                break;
            case "Coupon":
                $faretype = "Coupon";
                break;
            case "Corporate":
                $faretype = "Corporate";
                break;
            case "Corp Connect":
                $faretype = "CorporateConnect";
                break;
            case "Corporate Fare":
                $faretype = "Corporate";
                break;
            case "Regular Fare":
                $faretype = "Regular";
                break;
            case "SME Fare":
                $faretype = "SME";
                break;
            case "Flexible Plus Fare":
                $faretype = "FLX";
                break;
            default:
                $faretype = "Publish";
        }

        return $faretype;
    }
}

/**
 * ------------------------------------------
 * Get Class 
 * ------------------------------------------
 */

if (!function_exists('get_cabin_class_name')) {
    function get_cabin_class_name($value)
    {
        switch ($value) {
            case "1":
                $cabin = "All";
                break;
            case "2":
                $cabin = "Economy";
                break;
            case "3":
                $cabin = "PremiumEconomy";
                break;
            case "4":
                $cabin = "Business";
                break;
            case "5":
                $cabin = "PremiumBusiness";
                break;
            case "6":
                $cabin = "First";
                break;
            default:
                $cabin = "Economy";
        }

        return $cabin;
    }
}

/**
 * ------------------------------------------
 * Get Journey Type  Name
 * ------------------------------------------
 */

if (!function_exists('get_journey_type_name')) {
    function get_journey_type_name($value)
    {
        switch ($value) {
            case "1":
                $type = "OneWay";
                break;
            case "2":
                $type = "RoundTrip";
                break;
            case "3":
                $type = "MultiCity";
                break;
            default:
                $type = "OneWay";
        }
        return $type;
    }
}
/**
 * ------------------------------------------
 * Get Journey Type  Code
 * ------------------------------------------
 */

if (!function_exists('get_journey_type_code')) {
    function get_journey_type_code($value)
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

/**
 * ------------------------------------------
 * Get Pax Type Name
 * ------------------------------------------
 */

if (!function_exists('get_paxtype_name')) {
    function get_paxtype_name($value)
    {
        switch ($value) {
            case "1":
                $type = "Adult";
                break;
            case "2":
                $type = "Child";
                break;
            case "3":
                $type = "Infant";
                break;
            default:
                $type = "Adult";
        }
        return $type;
    }
}

/**
 * ------------------------------------------
 * Get Pax Type Name
 * ------------------------------------------
 */

if (!function_exists('get_paxtype_name_bycode')) {
    function get_paxtype_name_bycode($value)
    {
        switch ($value) {
            case "Adult":
                $type = 1;
                break;
            case "Child":
                $type = 2;
                break;
            case "Infant":
                $type = 3;
                break;
            default:
                $type = 1;
        }
        return $type;
    }
}

/**
 * ------------------------------------------
 * Get Pax Type Code Like ADT,CHD,INF
 * ------------------------------------------
 */

if (!function_exists('get_paxtype_code')) {
    function get_paxtype_code($value)
    {
        switch ($value) {
            case "1":
                $type = "ADT";
                break;
            case "2":
                $type = "CHD";
                break;
            case "3":
                $type = "INF";
                break;
            default:
                $type = "ADT";
        }
        return $type;
    }
}

/**
 * ------------------------------------------
 * Get Gender Name
 * ------------------------------------------
 */
if (!function_exists('get_gender')) {
    function get_gender($val)
    {
        $Gender = 'male';
        if ($val == 1) {
            $Gender = 'male';
        }
        if ($val == 2) {
            $Gender = 'female';
        }
        return $Gender;
    }
}

/**
 * ------------------------------------------
 * Get Gender value
 * ------------------------------------------
 */
if (!function_exists('get_gender_code')) {
    function get_gender_code($val)
    {
        if ($val == 'male') {
            $Gender = 1;
        }
        if ($val == 'female') {
            $Gender = 2;
        }
        return $Gender;
    }
}


/**
 * ------------------------------------------
 * Genearte Book data
 * ------------------------------------------
 */
if (!function_exists('ConvertBookingDetail')) {
    function ConvertBookingDetail($input, $data)
    {

        $Passengers = array();
        if ($data['PassengerDetails']) {
            foreach ($data['PassengerDetails'] as $paxinfo) {
                $baggagedata = array();
                $mealdata = array();
                $seatdata = array();
                
               
                
                if (isset($paxinfo['baggage']) && $paxinfo['baggage']) {
                    $baggagedata = json_decode($paxinfo['baggage'], true);
                }
                if (isset($paxinfo['meal']) && $paxinfo['meal']) {
                    $mealdata = json_decode($paxinfo['meal'], true);
                }
                if (isset($paxinfo['seat']) && $paxinfo['seat']) {
                    $seatdata = json_decode($paxinfo['seat'], true);
                }
                
                 // Added By Hariom
                $specialservicedata = array();
                
                //  if ($paxinfo['Baggage']) {
                //     $baggagedata = json_decode($paxinfo['Baggage'], true);
                // }
                // if ($paxinfo['MealDynamic']) {
                //     $mealdata = json_decode($paxinfo['MealDynamic'], true);
                // }
                // if ($paxinfo['SeatDynamic']) {
                //     $seatdata = json_decode($paxinfo['SeatDynamic'], true);
                // }
                
                
                 if (isset($paxinfo['SpecialServices']) && $paxinfo['SpecialServices']) {
                    $specialservicedata = json_decode($paxinfo['SpecialServices'], true);
                }
                
                // Added By Hariom End
                
                $paxdata = array(
                    'PaxId' => (int) $paxinfo['id'],
                    'Title' => $paxinfo['title'],
                    'FirstName' => $paxinfo['first_name'],
                    'LastName' => $paxinfo['last_name'],
                    'PaxType' => get_paxtype_name_bycode($paxinfo['pax_type']),
                    'Gender' => get_gender_code($paxinfo['gendar']),
                    'DateOfBirth' => $paxinfo['date_of_birth'],
                    'PAN' => $paxinfo['pan_number'],
                    'PassportNo' => $paxinfo['passport_number'],
                    'PassportExpiry' => $paxinfo['passport_expiry'],
                    'IsLeadPax' => $paxinfo['lead_pax'],
                    'Email' => $paxinfo['email_id'],
                    'ContactNo' => $paxinfo['mobile_number'],
                    'AddressLine1' => $paxinfo['address_1'],
                    'AddressLine2' => $paxinfo['address_2'],
                    'City' => $paxinfo['city'],
                    'CountryCode' => $paxinfo['country_code'],
                    'CountryName' => $paxinfo['country_name'],
                    'FFAirline' => $paxinfo['ff_airline'],
                    'FFNumber' => $paxinfo['ff_number'],
                    'TicketNumber' => $paxinfo['ticket_number'],
                    'Fare' => json_decode($paxinfo['fare'], true),
                    'Baggage' => $baggagedata,
                    'Meal' => $mealdata,
                    'Seat' => $seatdata,
                    'SpecialServices' => $specialservicedata
                );

                array_push($Passengers, $paxdata);
            }
        }

        $airline_pnrarray = array();
        if ($data['airline_pnr']) {
            $airline_pnrarray = json_decode($data['airline_pnr'], true);
        }

        $Segments = array();
        $segmentarray = json_decode($data['segments'], true);
        if ($segmentarray) {
            foreach ($segmentarray as $jkey => $segment) {
                foreach ($segment as $segkey => $item) {
                    $trip = $item['TripIndicator'];
                    $segtrip = $item['SegmentIndicator'];
                    if (isset($airline_pnrarray[$trip][$segtrip])) {
                        $item['AirlinePNR'] = $airline_pnrarray[$trip][$segtrip];
                    } else {
                        $item['AirlinePNR'] = "";
                    }
                    $Segments[$jkey][$segkey] = $item;
                }
            }
        }

        $TTS_Result = array(
            'BookingId' => (int) $data['id'],
            'JourneyType' => get_journey_type_code($data['journey_type']),
            'TripIndicator' => (int) $data['trip_indicator'],
            'PNR' => $data['pnr'],
            'Supplier' => $data['api_supplier'],
            'IsDomestic' => (bool) $data['is_domestic'],
            'IsManual' => (bool) $data['is_manual'],
            'IsLCC' => (bool) $data['is_lcc'],
            'IsRefundable' => (bool) $data['is_refundable'],
            'FareType' => $data['fare_type'],
            'Origin' => $data['origin'],
            'Destination' => $data['destination'],
            'AirlineCode' => $data['airline_code'],
            'LastTicketDate' => $data['last_ticket_date'],
            'ValidatingAirlineCode' => $data['validating_airline_code'],
            'AirlineRemark' => $data['airline_remark'],
            'Fare' => json_decode($data['web_partner_fare_break_up'], true),
            'Passenger' => $Passengers,
            'Segments' => $Segments,
            'FareRules' => json_decode($data['fare_rule'], true),
            /*   'InvoiceAmount'          => (float) $data['AccountDetails']['debit'],
              'InvoiceNo'              => $data['AccountDetails']['acc_ref_number'],
              'InvoiceCreatedOn'       => api_date_format_with_time($data['AccountDetails']['created'])  */
        );

        $tts_response = array(
            'UserIp' => $input['UserIp'],
            'SearchTokenId' => $data['tts_search_token'],
            'Error' => array("ErrorCode" => 0, "ErrorMessage" => ''),
            'Result' => $TTS_Result
        );
        return $tts_response;
    }
}
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
        return date("d M ' y, D", strtotime($dt));
    }
}
/* get convert To Hours Mins from  Duration (in Minutes) */
if (!function_exists('get_convertToHoursMinsfromMinDuration')) {
    function get_convertToHoursMinsfromMinDuration($minutes)
    {
        return $hours = intdiv($minutes, 60) . ' h : ' . ($minutes % 60) . ' m ';
    }
}

function getAirlineDeal($input, $SuperadminFlightDeal, $journeyData, $Pricedata, $apiSupplier, $cabinclass)
{
    $SupplierCommission = 0;
    $DealInfo = array();
    $GetTimeZone = app_timezone();
    $date = strtotime(Time::now($GetTimeZone));
    $applyFlightDeal = array();
    if ($SuperadminFlightDeal) {
        $Origin = "";
        $FareClass = "";
        $traveldate = "";
        foreach ($journeyData as $journey) {
            $applyFlightDeal = array();
            $Origin = "";
            $FareClass = "";
            $traveldate = "";
            $basicdealAmount = 0;
            $yqdealAmount = 0;
            $basic_iatadealAmount = 0;
            $yq_iatadealAmount = 0;
            $journeyFirstSegment = reset($journey);
            $Origin = trim($journeyFirstSegment['Origin']['AirportCode']);
            $AirlineCode = trim($journeyFirstSegment['Airline']['AirlineCode']);
            $traveldate = explode("T", trim($journeyFirstSegment['Origin']['DepartTime']))[0];
            $traveldate = strtotime($traveldate);
            $FareClass = trim($journeyFirstSegment['Airline']['FareClass']);
            foreach ($SuperadminFlightDeal as $flightDeal) {
                $applyFlightDeal = array();
                $dealbookingclassincluded = "";
                $dealbookingclassexcluded = "";
                $dealsector_included = "";
                $dealsector_excluded = "";
                $dealCabinClass = explode(",", $flightDeal['cabin_class']);
                $dealairlineCode = explode(",", $flightDeal['airline_code']);
                if ($flightDeal['booking_class_included'] != "") {
                    $dealbookingclassincluded = explode(",", $flightDeal['booking_class_included']);
                }
                if ($flightDeal['booking_class_excluded'] != "") {
                    $dealbookingclassexcluded = explode(",", $flightDeal['booking_class_excluded']);
                }
                if ($flightDeal['sector_included'] != "") {
                    $dealsector_included = explode(",", $flightDeal['sector_included']);
                }
                if ($flightDeal['sector_excluded'] != "") {
                    $dealsector_excluded = explode(",", $flightDeal['sector_excluded']);
                }
                $supplierInfo = explode(",", $flightDeal['supplier']);
                if (in_array($apiSupplier, $supplierInfo) && (in_array($cabinclass, $dealCabinClass) || in_array("All", $dealCabinClass)) && in_array($AirlineCode, $dealairlineCode)) {
                    if (($dealsector_included == "") && ($dealsector_excluded == "")) {
                        if (($flightDeal['booking_start_date'] == "" && $flightDeal['booking_end_date'] == "") && ($flightDeal['travel_start_date'] == "" && $flightDeal['travel_end_date'] == "")) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else if (($flightDeal['booking_start_date'] != "" && $flightDeal['booking_end_date'] != "") && ($flightDeal['booking_start_date'] <= $date && $flightDeal['booking_end_date'] >= $date)) {
                            if ($flightDeal['booking_class_included'] == "" && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }


                        } else if (($flightDeal['travel_start_date'] != "" && $flightDeal['travel_end_date'] != "") && ($flightDeal['travel_start_date'] <= $traveldate && $flightDeal['travel_end_date'] >= $traveldate)) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }
                        }
                    } else if (($dealsector_included != "") && in_array($Origin, $dealsector_included)) {
                        if (($flightDeal['booking_start_date'] == "" && $flightDeal['booking_end_date'] == "") && ($flightDeal['travel_start_date'] == "" && $flightDeal['travel_end_date'] == "")) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }
                        } else if (($flightDeal['booking_start_date'] != "" && $flightDeal['booking_end_date'] != "") && ($flightDeal['booking_start_date'] <= $date && $flightDeal['booking_end_date'] >= $date)) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }


                        } else if (($flightDeal['travel_start_date'] != "" && $flightDeal['travel_end_date'] != "") && ($flightDeal['travel_start_date'] <= $traveldate && $flightDeal['travel_end_date'] >= $traveldate)) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }
                        }
                    } else if (($flightDeal['sector_excluded'] != "") && !in_array($Origin, $dealsector_excluded)) {
                        if (($flightDeal['booking_start_date'] == "" && $flightDeal['booking_end_date'] == "") && ($flightDeal['travel_start_date'] == "" && $flightDeal['travel_end_date'] == "")) {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {

                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else if (($flightDeal['booking_start_date'] != "" && $flightDeal['booking_end_date'] != "") && ($flightDeal['booking_start_date'] <= $date && $flightDeal['booking_end_date'] >= $date)) {

                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else if (($flightDeal['travel_start_date'] != "" && $flightDeal['travel_end_date'] != "") && ($flightDeal['travel_start_date'] <= $traveldate && $flightDeal['travel_end_date'] >= $traveldate)) {

                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }

                        } else {
                            if (($flightDeal['booking_class_included'] == "") && ($flightDeal['booking_class_excluded'] == "")) {

                                $applyFlightDeal = $flightDeal;
                            } else if (($flightDeal['booking_class_included'] != "") && in_array($FareClass, $dealbookingclassincluded)) {

                                $applyFlightDeal = $flightDeal;
                            } else {
                                if (($flightDeal['booking_class_excluded'] != "") && !in_array($FareClass, $dealbookingclassexcluded)) {
                                    $applyFlightDeal = $flightDeal;
                                }
                            }
                        }
                    }
                }
                if ($applyFlightDeal && !empty($applyFlightDeal)) {
                    array_push($DealInfo, $applyFlightDeal);
                    $Fare = $Pricedata['Fare'];
                    if ($flightDeal['basic'] != "") {
                        $basicdealAmount = round_value((($Fare['BaseFare'] * $flightDeal['basic']) / 100));
                    }
                    if ($flightDeal['yq'] != "") {
                        $yqdealAmount = round_value((($Fare['YQTax'] * $flightDeal['yq']) / 100));
                    }
                    if ($flightDeal['basic_iata'] != "") {
                        $basic_iatadealAmount = round_value((($Fare['BaseFare'] * $flightDeal['basic_iata']) / 100));
                    }
                    if ($flightDeal['yq'] != "") {
                        $yq_iatadealAmount = round_value((($Fare['YQTax'] * $flightDeal['yq_iata']) / 100));
                    }
                    $SupplierCommission = $SupplierCommission + $basicdealAmount + $yqdealAmount + $basic_iatadealAmount + $yq_iatadealAmount;

                }
            }
        }
    }

    return array("SupplierCommission" => $SupplierCommission, "DealInfo" => $DealInfo);
}

/**
 * -Get  second To hhmm  
 * ---------------------------------------------
 */
if (!function_exists('second_to_hhmm')) {
    function second_to_hhmm($time)
    {
        $hour = floor($time / 3600);
        $minute = strval(floor(($time % 3600) / 60));
        if ($minute == 0) {
            $minute = "00";
        } else {
            $minute = $minute;
        }
        $time = $hour . "H :" . $minute . "M";
        return $time;
    }
}
/**
 * -Get  Flight Journey Time  
 * ---------------------------------------------
 */
if (!function_exists('journeyTime')) {
    function journeyTime($origintimezone, $destinationtimezone, $depdatetime, $arridatetime, $type = "")
    {
        $origintimezone = trim($origintimezone);
        $destinationtimezone = trim($destinationtimezone);
        if ($origintimezone == "" || $destinationtimezone == "") {
            $origintimezone = "Asia/Kolkata";
            $destinationtimezone = "Asia/Kolkata";
        }
        if ($origintimezone != "" && $destinationtimezone != "") {
            $depaturedate = str_replace("T", " ", $depdatetime);
            $arrivaldate = str_replace("T", " ", $arridatetime);
            $departureDate = Time::parse($depaturedate, $origintimezone);
            $arrivalDate = Time::parse($arrivaldate, $destinationtimezone);
            $diff = $departureDate->difference($arrivalDate);

            if ($type == "duration") {
                return $diff->getMinutes();
            } else {
                $timeInSecond = $diff->getSeconds();
                return second_to_hhmm($timeInSecond);
            }
        } else {
            return "";
        }
    }
}

