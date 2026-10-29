<?php

use CodeIgniter\I18n\Time;
use CodeIgniter\I18n\TimeDifference;

/**
 * ------------------------------------------------
 * Filter  Flight Markup
 * ------------------------------------------------
 */
function getFliterFlightMarkup($markup_data,$input,$airline_code,$CabinClass,$selectedMarkupDataInfo,$extraParameter)
{
  
    $selMarkupData  =  array();
    $filtermarkupArray  =  array();  
    $FareType =  "Publish";
    $FareClass =  "ANY";
    if(isset($extraParameter['FareType'])) 
    {
        $FareType =  $extraParameter['FareType'];
    }
    if(isset($extraParameter['FareClass'])) 
    {
        $FareClass =  $extraParameter['FareClass'];
    }
    if(isset($selectedMarkupDataInfo[$CabinClass][$airline_code][$FareType][$FareClass]))
    {
        $selMarkupData = $selectedMarkupDataInfo[$CabinClass][$airline_code][$FareType][$FareClass];
    }
    if(empty($selMarkupData)){
    foreach($markup_data as $markup)
    {
    $cabinClass  =  explode(",",$markup['cabin_class']);
    $fareTypeArray  =  explode(",",$markup['faretype']);
    $bookingClassArray  =  explode(",",$markup['booking_class']);
    if(in_array($CabinClass,$cabinClass) && in_array($FareType,$fareTypeArray) && (in_array($FareClass,$bookingClassArray)||in_array('ANY',$bookingClassArray)))
    {
        array_push($filtermarkupArray,$markup);
    }
    }
    if($filtermarkupArray)
    {
        $airline_array =array_column($filtermarkupArray, 'airline_code');
        if(in_array($airline_code, $airline_array))
        {
            $sel_key_airline = array_search($airline_code,$airline_array);
            $selMarkupData=$filtermarkupArray[$sel_key_airline];
        }
        else
        {
            if(in_array('ANY', $airline_array))
            {
                $sel_key_airline = array_search('ANY',$airline_array);
                $selMarkupData=$filtermarkupArray[$sel_key_airline];
                $airline_code  = 'ANY';  
            }
        }
        $selectedMarkupDataInfo[$CabinClass][$markup['airline_code']][$FareType][$FareClass] = $markup;
    }
        }      
    return array("selectedMarkupDataInfo"=>$selectedMarkupDataInfo,"selMarkupData"=>$selMarkupData);
}
/**
 * ------------------------------------------------
 * Filter  Flight Discount
 * ------------------------------------------------
 */
function getFliterFlightDiscount($discount_data,$input,$airline_code,$CabinClass,$selectedDiscountDataInfo,$extraParameter)
{
    $selDiscountData  =  array();
    $filterDiscountArray  =  array();  
    $FareType =  "Publish";
    $FareClass =  "ANY";
    if(isset($extraParameter['FareType'])) 
    {
        $FareType =  $extraParameter['FareType'];
    }
    if(isset($extraParameter['FareClass'])) 
    {
        $FareClass =  $extraParameter['FareClass'];
    }
    if(isset($selectedDiscountDataInfo[$CabinClass][$airline_code][$FareType][$FareClass]))
    {
        $selDiscountData = $selectedDiscountDataInfo[$CabinClass][$airline_code][$FareType][$FareClass];
    }
    if(empty($selDiscountData)){
    foreach($discount_data as $discount)
    {
    $cabinClass  =  explode(",",$discount['cabin_class']);
    $fareTypeArray  =  explode(",",$discount['faretype']);
    $bookingClassArray  =  explode(",",$discount['booking_class']);
    if(in_array($CabinClass,$cabinClass)&& in_array($FareType,$fareTypeArray) && (in_array($FareClass,$bookingClassArray)||in_array('ANY',$bookingClassArray)))
    {
        array_push($filterDiscountArray,$discount);
    }
    }
    if($filterDiscountArray)
    {
        $airline_array =array_column($filterDiscountArray, 'airline_code');
        if(in_array($airline_code, $airline_array))
        {
            $sel_key_airline = array_search($airline_code,$airline_array);
            $selDiscountData=$filterDiscountArray[$sel_key_airline];
        }
        else
        {
            if(in_array('ANY', $airline_array))
            {
                $sel_key_airline = array_search('ANY',$airline_array);
                $selDiscountData=$filterDiscountArray[$sel_key_airline];
                $airline_code  = 'ANY';  
            }
        }
        $selectedDiscountDataInfo[$CabinClass][$discount['airline_code']][$FareType][$FareClass] = $discount;
    }
        }      
        return array("selectedDiscountDataInfo"=>$selectedDiscountDataInfo,"selDiscountData"=>$selDiscountData);
}

/**
 * ----------------------------------------------
 * Get Origin and Destination Airport Code
 * ---------------------------------------------
 */

 function getGdsPnr($gdspnrvalue)
 {
    $array  =  array();
    if($gdspnrvalue) {
  foreach($gdspnrvalue as $value){
    $array =  array_merge($array,array_values($value));
  }
  if(!empty($array))
  {
   return implode(",",$array);
  }
}
  return "---";
 }

/**
 * ----------------------------------------------
 * Get Origin and Destination Airport Code
 * ---------------------------------------------
 */

 function getSearchOriginDestinationAirportCode($searchData)
 {
    $airportCodeArray  =  array();
  if($searchData['journeytype']=="Oneway"|| $searchData['journeytype'] == 'Roundtrip')
  {
    $origin = trim(AirportCode($searchData['origin']));
    $destination = trim(AirportCode($searchData['destination']));
    array_push( $airportCodeArray,$origin);
    array_push( $airportCodeArray,$destination);
  }
  else
  {
    $SearchOriginDestination  = $searchData['search_data'];
    foreach ($SearchOriginDestination as $key => $searchjourney) {
        $origin = trim(AirportCode($searchjourney['origin']));
        $destination = trim(AirportCode($searchjourney['destination']));
        array_push( $airportCodeArray,$origin);
        array_push( $airportCodeArray,$destination);
        $SearchOriginDestination[$key]['OriginCity'] =  trim(AirportCity($searchjourney['origin']));
        $SearchOriginDestination[$key]['DestinationCity'] =  trim(AirportCity($searchjourney['destination']));
        $SearchOriginDestination[$key]['OriginCityCode'] =  trim(AirportCode($searchjourney['origin']));
        $SearchOriginDestination[$key]['DestinationCityCode'] =  trim(AirportCode($searchjourney['destination']));
        $SearchOriginDestination[$key]['DepartDateDay'] =  date('l', strtotime($searchjourney['departdate']));
    }
    $searchData['search_data']= $SearchOriginDestination;
  }
return array("airportCodeArray"=>array_unique($airportCodeArray),"searchData"=>$searchData);
 }


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
/* cabin class type  Api start */
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
/* get AirportCode   start */
if (!function_exists('AirportCode')) {
function AirportCode($origin)
{
    $pieces = explode(",", $origin);
    $code = @trim($pieces[0]);
    if ($code != "") {
        return  substr($code, strpos($code, "(") + 1, 3);
    } else {
        return $origin;
    }
}
}
/* get  AirportCode end */
/* get City  Name  start */
if (!function_exists('AirportCity')) {
function AirportCity($origin)
{

    $pieces = explode(",", $origin);
    $code = @trim($pieces[0]);
    if ($code != "") {
        return  substr($code, 0, strlen($code)-5);
    } else {
        return $origin;
    }
   
}
}
/* get   City  Name end */
/* get Api Date format   start */
if (!function_exists('get_api_date_format')) {
function get_api_date_format($date)
{
    return date("Y-m-d", strtotime($date));
}
}
/* get  Api Date format end */

/* get Country Name   start */
if (!function_exists('get_country_name')) {
function get_country_name($data)
{
    $country_name = explode(',', $data);
    return  strtolower(trim($country_name[1]));
}
}
/* get Country Name end */
if (!function_exists('tag_exist')) {
function tag_exist($value)
{
    $return = "";
    if (isset($value)) {
        $return = $value;
    }
    return $return;
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
/* get flight Arrival Days */
if (!function_exists('get_flight_arrival_days')) {
function get_flight_arrival_days($departureDate, $arrivalDate)
{
    if ($departureDate && $arrivalDate) {
        list($ddt, $dtm) = explode('T', $departureDate);
        list($adt, $atm) = explode('T', $arrivalDate);
        $departureDate = Time::parse($ddt);
        $arrivalDate    = Time::parse($adt);
        $diff = $departureDate->difference($arrivalDate);
        return   $days    = $diff->getDays();
    }
}
}
/* get flight Arrival Days */
/* get flight  price low down  Icon */
if (!function_exists('check_int')) {
function check_int($int)
{
    if ($int > 0) {
        $imageurl = site_url('webroot/img/change-price-up.png');
        return array('sign' => '+', 'text' => 'increased', 'image' => $imageurl);
    } elseif ($int < 0) {
        $imageurl = site_url('webroot/img/change-price-down.png');
        return  array('sign' => '-', 'text' => 'decreased', 'image' => $imageurl);
    } elseif ($int == 0) {
        $imageurl = site_url('webroot/img/bell-512.png');
        return  array('sign' => '=', 'text' => 'equals', 'image' => $imageurl);
    }
}
}
/* get flight  price low down  Icon */
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
 * Get Booking Gender Type 
 * ------------------------------------------
 */

if (!function_exists('pax_title_type')) {
    function pax_title_type($title)
    {
    
        switch ($title) {
            case "Mr":
                $titletype = "1";
                break;
            case "Mrs":
                $titletype = "2";
                break;
            case "Miss":
                $titletype = "2";
                break;
            case "Ms":
                $titletype = "2";
                break;
            case "Mstr":
                $titletype = "1";
                case "Master":
                    $titletype = "1";
                break;
                default:
                $titletype = "1";

        }
        return $titletype;
    }
}
/**
 * ------------------------------------------
 * Get Booking Adult  Type 
 * ------------------------------------------
 */

if (!function_exists('type_adt')) {
    function type_adt($adttype)
{
    switch ($adttype) {
        case "Adult":
            $classtype = 1;
            break;
        case "Child":
            $classtype = 2;
            break;
        case "Infant":
            $classtype = 3;
            break;
        default:
            $classtype = 1;
    }
    return $classtype;
}
}
/**
 * ------------------------------------------
 * Get Pax Type Code
 * ------------------------------------------
 */

if (!function_exists('get_paxtype_code')) {
    function get_paxtype_code($adttype)
{
    switch ($adttype) {
        case "Adult":
            $classtype = 'ADT';
            break;
        case "Child":
            $classtype = 'CHD';
            break;
        case "Infant":
            $classtype = 'INF';
            break;
        default:
            $classtype = 'ADT';
    }
    return $classtype;
}
}
/**
 * ------------------------------------------
 * get Modify Search Date
 * ------------------------------------------
 */

if (!function_exists('get_modify_search_date')) 
{
    function get_modify_search_date($search_date,$dateFormat="")
{
    if($dateFormat !="") 
    {
    return  date($dateFormat,strtotime($search_date));
    }
    else{
        return  date("M ' Y , l",strtotime($search_date));
    }
}
}
/**
 * ------------------------------------------
 * get Total Number Pax
 * ------------------------------------------
 */

if (!function_exists('get_total_number_pax')) 
{
    function get_total_number_pax($searchData)
{
   return ($searchData['adults']+$searchData['child']+$searchData['infant']);
}
}
/**
 * ------------------------------------------
 * Get  Depart and Arrival Time  
 * ------------------------------------------
 */

if (!function_exists('filter_deparr_string')) 
{
    function filter_deparr_string($time)
    {
        $time = trim(str_replace(":", "", $time));
        if (($time >= "0000") && ($time <= "0600")) {
            return "EarlyMorning";
        } elseif (($time >= "0600") && ($time <= "1200")) {
            return "Morning";
        } elseif (($time >= "1200") && ($time <= "1800")) {
            return "Afternoon";
        } elseif (($time >= "1800") || ($time <= "2359")) {
            return "Night";
        } else {
            echo "";
        }
    }
}

/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply  Markup,
 * -----------------------------------------------------------------------------
 */

 function get_flight_fare($input,$markup_data,$discount_data,$agent_flight_markup,$price,$airline_code,$selectedMarkupDataInfo,$selectedDiscountDataInfo,$extraParameter,$service  =  "") {

    $totalpax=intval($input['Adult'])+intval($input['Child']);
   $PublishedPrice=0; $OfferedPrice=0; $ServiceCharges=0;
    $Tax=$price['Tax'];
    $ServiceCharges=$price['ServiceCharges'];
    $markup_value=0; $agent_discount_value=0;
    $wl_agent_markup_value =0;
    $sel_markup_data=array(); $sel_discount_data=array();
    $display_markup  =  "in_tax";
    $wl_agent_display_markup =  "in_tax";
    if( !empty($markup_data) and $markup_data)
    {
        $filter_markup_data =  getFliterFlightMarkup($markup_data,$input,$airline_code,$CabinClass,$selectedMarkupDataInfo,$extraParameter);
        $selectedMarkupDataInfo =  $filter_markup_data['selectedMarkupDataInfo'];
        $sel_markup_data =  $filter_markup_data['selMarkupData']; 
   if(!empty($sel_markup_data) && $sel_markup_data) {
            if($sel_markup_data['markup_type']=='percent')
            {
                /*   markup apply for PublishedPrice */
                    $markup_value=round_value(($price['PublishedPrice']*abs($sel_markup_data['value']))/100);
                /*   markup check max limit  */
            } elseif($sel_markup_data['markup_type']=='fixed') {
                       $markup_value=round_value($sel_markup_data['value']*$totalpax);
            }
            /* display markup tag */
            if($sel_markup_data['display_markup']=='in_tax')
            {
                $display_markup =  "in_tax";
                $Tax=$price['Tax']+$markup_value;
            } elseif($sel_markup_data['display_markup']=='in_service_charge') {
                $ServiceCharges=$ServiceCharges+$markup_value;
                $display_markup =  "in_service_charge";
            }   
   }
    }
    if(!empty($discount_data) && $discount_data)
    {
        
        $filter_discount_data =  getFliterFlightDiscount($discount_data,$input,$airline_code,$CabinClass,$selectedDiscountDataInfo,$extraParameter);
        $selectedDiscountDataInfo =  $filter_discount_data['selectedDiscountDataInfo'];
        $sel_discount_data =  $filter_discount_data['selDiscountData'];
            $SupplierCommission=$price['Discount']+$price['AgentCommission'];
            if(!empty($sel_discount_data) && $sel_discount_data) {
            if($sel_discount_data['discount_type']=='percent')
            {
                $agent_discount_value=round_value(($SupplierCommission*$sel_discount_data['value'])/100);
            } else {
                $agent_discount_value=round_value($sel_discount_data['value']);
            }
            }
        }
        $OfferedPrice=round_value($price['PublishedPrice']+$markup_value-$agent_discount_value);
        $PublishedPrice=round_value($price['PublishedPrice']+$markup_value);
        if( !empty($agent_flight_markup) and $agent_flight_markup)
        {
                $airline_array =array_column($agent_flight_markup, 'airline_code');
                if(in_array($airline_code, $airline_array))
                {
                    $wl_agent_sel_key_airline = array_search($airline_code,$airline_array);
                    $wl_agent_sel_markup_data=$agent_flight_markup[$wl_agent_sel_key_airline];
                }
                else
                {
                    if(in_array('ANY', $airline_array))
                    {
                        $wl_agent_sel_key_airline = array_search('ANY',$airline_array);
                        $wl_agent_sel_markup_data=$agent_flight_markup[$wl_agent_sel_key_airline];
                    }
                }
       if(!empty($wl_agent_sel_markup_data) && $wl_agent_sel_markup_data) {
                if($wl_agent_sel_markup_data['markup_type']=='percent')
                {
                    /*   markup apply for PublishedPrice */
                        $wl_agent_markup_value=round_value(($PublishedPrice*abs($wl_agent_sel_markup_data['value']))/100);
                    /*   markup check max limit  */
                } elseif($wl_agent_sel_markup_data['markup_type']=='fixed') {
                           $wl_agent_markup_value=round_value($wl_agent_sel_markup_data['value']*$totalpax);
                }
                /* display markup tag */
                if($wl_agent_sel_markup_data['display_markup']=='in_tax')
                {
                    $wl_agent_display_markup =  "in_tax";
                    if($service!="FareConfirmation"){
                    $Tax=$Tax+$wl_agent_markup_value;
                    }
                } elseif($wl_agent_sel_markup_data['display_markup']=='in_service_charge') {
                    if($service!="FareConfirmation"){
                    $ServiceCharges=$ServiceCharges+$wl_agent_markup_value;
                    }
                    $wl_agent_display_markup =  "in_service_charge";
                }   
       }
        }
        if($service!="FareConfirmation"){
        $PublishedPrice=round_value($price['PublishedPrice']+$markup_value+$wl_agent_markup_value);
        }
            $price['PublishedPrice']  =   $PublishedPrice;
            $price['OfferedPrice']  =   $OfferedPrice;
            $price['Tax']  =   $Tax;
            $price['ServiceCharges']  =   $ServiceCharges;
            $price['WebPMarkUp']  =   $markup_value;
            $price['WebPDiscount']  =   $agent_discount_value;
            $price['AgentWebPMarkUp']  =   $wl_agent_markup_value;
            $price['AgentWebPDisplayMarkup']  =   $wl_agent_display_markup;
            $price['AgentWebPDiscount']  =   0;
            $price['DisplayMarkup']  =   $display_markup;
            return array('Fare'=>$price,"selectedMarkupDataInfo"=>$selectedMarkupDataInfo,"selectedDiscountDataInfo"=>$selectedDiscountDataInfo);
}