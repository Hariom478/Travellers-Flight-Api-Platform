<?php

use CodeIgniter\I18n\Time;
use CodeIgniter\I18n\TimeDifference;

/**

 * ----------------------------------------------

 * Get Origin and Destination Airport Code

 * ---------------------------------------------

 */



 function getGdsPnrImportPnr($gdspnrvalue)

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
/* get convert To Hours Mins from  Duration (in Minutes) */

if (!function_exists('get_convertToHoursMinsfromMinDuration_import_pnr')) {

    function get_convertToHoursMinsfromMinDuration_import_pnr($minutes)
    
    {
    
        return  $hours = intdiv($minutes, 60) . ' h ' . ($minutes % 60) . ' m ';
    
    }
    
    }
/* get Flight Date */

if (!function_exists('get_flight_date_import_pnr')) {

    function get_flight_date_import_pnr($var)
    
    {
    
        list($dt, $tm) = explode('T', $var);
    
        return date("d M", strtotime($dt));
    
    }
    
    }
    /* get Flight Time */

if (!function_exists('get_flight_time_import_pnr')) {

    function get_flight_time_import_pnr($var)
    
    {
    
        list($dt, $tm) = explode('T', $var);
    
        $tm = substr($tm, 0, 5);
    
        return $tm;
    
    }
    
    }
/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

 function get_flight_fare_import_ticket($price,$deal,$webpartnerInfo,$super_admin_gst_state_code,$passenger) {

    

    $BaseFare=0; $Tax=0; $YQTax=0; $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;
    $Tax=$price['Tax'];

    $markup_value=0; $discount_value=0;$extra_discount=0; $commission=0; $totalgst=0;
    $sel_markup_data=array(); $sel_discount_data=array();
    $paxTypeValue  =  array_column($passenger,"PaxType");
    $paxTypeValue           =  array_count_values($paxTypeValue);
    $totalpax =  $paxTypeValue['1'];
    if(isset($paxTypeValue['2'])){
        $totalpax = $totalpax+$paxTypeValue['2'];
    }
    if($deal)
    {
        $markup_value   = ($deal['markup']*$totalpax);
        if($deal['display_markup']=='in_tax')
        {
            $Tax=$price['Tax']+$markup_value;
        } elseif($deal['display_markup']=='in_service_charge') {
            $ServiceCharges=$markup_value;
        }   
    }
   
    $GST=array();
    $GST=gst_calculate('Flight',$webpartnerInfo['gst_state_code'],$super_admin_gst_state_code,$ServiceCharges);
    $totalgst=$GST['TotalGSTAmount'];
    $PublishedPrice=round_value($price['PublishedPrice']+$markup_value+$totalgst);

    unset($GST['TotalGSTAmount']);

    if($deal)
    {
        $commission= $commission+round_value(($price['BaseFare']*$deal['basic'])/100);
        $commission= $commission+round_value(($price['YQTax']*$deal['yq'])/100);
        $commission= $commission+round_value(($price['BaseFare']*$deal['basic_iata'])/100);
        $commission= $commission+round_value(($price['YQTax']*$deal['yq_iata'])/100);
     
    }

      /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
      $OfferedPrice=round_value(($PublishedPrice)-($commission));
      if ($OfferedPrice > 0)
      { 

      } elseif ($OfferedPrice == 0){
          $commission=0;
          $OfferedPrice=round_value(($price['PublishedPrice']+$markup_value+$totalgst));

      } elseif ($OfferedPrice < 0) {

          $commission=0;
          $OfferedPrice=round_value(($price['PublishedPrice']+$markup_value+$totalgst));
      }

     $BaseFare=$price['BaseFare'];
     $YQTax=$price['YQTax'];
     $OtherCharges=$price['OtherCharges'];
     $Discount=$extra_discount;
     $AgentCommission=round_value($commission);

     /*-- calculate tds-- */
     $TDS=tds_calculate($AgentCommission);
    /* -- Calculate GST on Markup ---*/
   /*  $per_pax_markup=($markup_value/$totalpax); */
    $per_pax_markup=$deal['markup'];
    $per_pax_tds=round_value(($TDS/$totalpax));
    $per_pax_gst=round_value(($totalgst/$totalpax));
    $per_pax_discount=round_value(($Discount/$totalpax));
    $per_pax_AgentCommission=round_value(($AgentCommission/$totalpax));
    $FareBreakdown=array();
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
   
        
        $SuperAdminFareBreakup=array(
                                        'BaseFare'             => $price['BaseFare'],
                                        'Tax'                  => $price['Tax'],
                                        'YQTax'                => $price['YQTax'],
                                        'OtherCharges'         => floatval($price['OtherCharges']),
                                        'Discount'             => $price['AgentCommission']+$price['Discount'],
                                        'PublishedPrice'       => $price['PublishedPrice'],
                                        'OfferedPrice'         => $price['OfferedPrice'],
                                        'TaxBreakup'            => isset($price['TaxBreakup'])?$price['TaxBreakup']:array(),
                                        'TotalBaggageCharges'  => isset($price['TotalBaggageCharges'])?$price['TotalBaggageCharges']:0,
                                        'TotalMealCharges'     => isset($price['TotalMealCharges'])?$price['TotalMealCharges']:0,
                                        'TotalSeatCharges'     => isset($price['TotalSeatCharges'])?$price['TotalSeatCharges']:0,
                                        'SUP_Markup'           => $markup_value,
                                        'SUP_DisplayMarkup'    => $deal['display_markup'],
                                        'SUP_Discount'         => $AgentCommission,
                                        'SUP_ExtraDiscount'    => $extra_discount,
                                        'SUP_DiscountType'     => "percent"                                      
                                    );
        $WebPartnerFareBreakup=$tts_flight_breakup;
     return array('Fare'=>$tts_flight_breakup,'FareBreakdown'=>$FareBreakdown,'SuperAdminFareBreakup'=>$SuperAdminFareBreakup,'WebPartnerFareBreakup'=>$WebPartnerFareBreakup,'per_pax_markup'=>$per_pax_markup,"display_markup"=> $deal['display_markup'],"per_pax_tds"=>$per_pax_tds,"per_pax_gst"=>$per_pax_gst,"per_pax_discount"=>$per_pax_discount,"per_pax_AgentCommission"=>$per_pax_AgentCommission);
}
/* get Pax type start */
if (!function_exists('get_pax_type')) 
{
    function get_pax_type($paxTypekey)
    {
    
        switch ($paxTypekey) {
            case "1":
                $paxType = "Adult";
                break;
            case "2":
                $paxType = "Child";
                break;
            case "3":
                $paxType = "Infant";
                break;
            default:
                $paxType = "Adult";
        }
        return $paxType;
    }
    }
    /*  get Pax type end */
/* get Pax type start */
if (!function_exists('get_gender_type_import_pnr')) 
{
    function get_gender_type_import_pnr($genderTypekey)
    {
    
        switch ($genderTypekey) {
            case "1":
                $genderType = "male";
                break;
            case "2":
                $genderType = "female";
                break;
            default:
                $genderType = "male";
        }
        return $genderType;
    }
    }
    /*  get Pax type end */
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    }
    

/**
 * -Get  Flight Journey Time  
 * ---------------------------------------------
 */
if (!function_exists('journeyTimeImportTicket')) {
    function journeyTimeImportTicket($origintimezone, $destinationtimezone, $depdatetime, $arridatetime)
    {
        if ($origintimezone == "" || $destinationtimezone == "") 
        {
            $origintimezone =  "Asia/Kolkata";
            $destinationtimezone =  "Asia/Kolkata";
        }
        if ($origintimezone!= "" && $destinationtimezone!= "") {
            $depaturedate = str_replace("T", " ", $depdatetime);
            $arrivaldate = str_replace("T", " ", $arridatetime);
            $departureDate = Time::parse($depaturedate,trim($origintimezone));
            $arrivalDate =  Time::parse($arrivaldate,trim($destinationtimezone));
            $diff = $arrivalDate->difference($departureDate);
            $timeInMinutes =  $diff->getMinutes();
            $timeInMinutes =  str_replace("-","", $timeInMinutes);
            return $timeInMinutes;
        } else {
            return "";
        }
    }
}
//* cabin class type  Api start */
if (!function_exists('get_api_cabinclass')) 
{
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


if (!function_exists('get_journey_type_import_ticket')) {
    function get_journey_type_import_ticket($value)
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
    }}
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

if (!function_exists('check_domestic_type')) 
{
    function check_domestic_type($countryCode)
    {
       $uniqueCountryCode  =  array_unique($countryCode);
       $domestic_type   =  0;
       if(count($uniqueCountryCode)==1){
        if($uniqueCountryCode[0]=="IN"){
            $domestic_type=1;
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
if (!function_exists('generate_token_ticket_upload')) 
{
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
 * -------------------------------------
 * End Generate Random Token ID Use for Search
 * -------------------------------------
 */

 function generateImportPnrPaxData($passengerInfo,$flightFare,$result)
 {
    $NoofAdult = 0;
    $NoofChild = 0;
    $NoofInfant = 0;
    $insertPaxdata  =  array();
    $PNR  =  $result['PNR'];
    if($passengerInfo)
    {
       $gstInfo    = array(
            'name' => isset($passengerInfo[0]['GSTCompanyName'])?$passengerInfo[0]['GSTCompanyName']:'',
            'number' => isset($passengerInfo[0]['GSTNumber'])?$passengerInfo[0]['GSTNumber']:'',
            'phone' => isset($passengerInfo[0]['GSTCompanyContactNumber'])?$passengerInfo[0]['GSTCompanyContactNumber']:'',
            'email' => isset($passengerInfo[0]['GSTCompanyEmail'])?$passengerInfo[0]['GSTCompanyEmail']:'',
            'address' => isset($passengerInfo[0]['GSTCompanyAddress'])?$passengerInfo[0]['GSTCompanyAddress']:''
       );
    foreach ($passengerInfo as $paxsubkey => $passenger) {
    
         if($passenger['PaxType']=="1")
         {
            $NoofAdult = $NoofAdult+1;
         }
         if($passenger['PaxType']=="2")
         {
            $NoofChild = $NoofChild+1;
         }
         if($passenger['PaxType']=="3")
         {
            $NoofInfant = $NoofInfant+1;
         }
        $apipassengerData = array();

        if (isset($passenger['PassportNo']) && $passenger['PassportNo']!="") {
            $PassportNo = $passenger['PassportNo'];
        } else {
            $PassportNo = NULL;
        }
        if (isset($passenger['PassportIssue']) && $passenger['PassportIssue']!="") {
            $PassportIssueDate = $passenger['PassportIssue'];
        } else {
            $PassportIssueDate = NULL;
        }
        if (isset($passenger['PassportExpiry']) && $passenger['PassportExpiry']!="") {
            $PassportExpDate = $passenger['PassportExpiry'];
        } else {
            $PassportExpDate = NULL;
        }
        if (isset($passenger['DateOfBirth']) && $passenger['DateOfBirth'] != '') {
            $dob = $passenger['DateOfBirth'];
        } else {
            $dob = NULL;
        }
        if (isset($passenger['PAN']) && $passenger['PAN']!="") {
            $pancard = $passenger['PAN'];
        } else {
            $pancard = NULL;
        }
        if (isset($passenger['Nationality']) && $passenger['Nationality']!="") {
            $nationality = $passenger['Nationality'];
        } else {
            $nationality = NULL;
        }
        $addMarkupInTax  = 0;
        $addMarkupInserviceCharge  =  0;
        if($flightFare['display_markup']=="in_tax"){
            $addMarkupInTax  = $flightFare['per_pax_markup'];
        }
        else{
            $addMarkupInserviceCharge  =  $flightFare['per_pax_markup'];
        }
        $paxfare = $passenger['Fare'];
        if($passenger['PaxType']==1||$passenger['PaxType']==2){
            $paxfare['Discount'] = $flightFare['per_pax_discount'];
            $paxfare['AgentCommission'] = $flightFare['per_pax_AgentCommission'];
            $paxfare['TDS'] = $flightFare['per_pax_tds'];
            $paxfare['Tax'] = $paxfare['Tax']+$addMarkupInTax;
            $paxfare['GSTAmount'] = $flightFare['per_pax_gst'];
            $paxfare['ServiceCharges'] = $addMarkupInserviceCharge;
            $paxfare['OfferedPrice'] = round_value(($paxfare['PublishedPrice']+$flightFare['per_pax_gst']+$addMarkupInserviceCharge+$addMarkupInTax)-($flightFare['per_pax_discount']+$flightFare['per_pax_AgentCommission']));
            $paxfare['PublishedPrice'] = round_value($paxfare['PublishedPrice']+$flightFare['per_pax_gst']+$addMarkupInserviceCharge+$addMarkupInTax);
        }
        else {
        $paxfare['Discount'] = 0;
        $paxfare['AgentCommission'] = 0;
        $paxfare['TDS'] = 0;
        $paxfare['GSTAmount'] = 0;
        $paxfare['OfferedPrice'] = round_value(($paxfare['PublishedPrice']));
        $paxfare['PublishedPrice'] = round_value($paxfare['PublishedPrice']);
        }
        $gender = strtolower(get_gender_type_import_pnr($passenger['Gender']));
        $booking_status =  "Processing";
        if($PNR!="")
        {
        $booking_status =  "Confirmed";
        }
        $apipassengerData = array(
            "title" => $passenger['Title'],
            "pax_id" => isset($passenger['PaxId'])?$passenger['PaxId']:null,
            "ticket_id" => isset($passenger['TicketId'])?$passenger['TicketId']:null,
            "validating_airline" => isset($passenger['ValidatingAirline'])?$passenger['ValidatingAirline']:null,
            "first_name" => $passenger['FirstName'],
            "last_name" => $passenger['LastName'],
            "pax_type" =>get_pax_type($passenger['PaxType']),
            "gendar" => $gender,
            "date_of_birth" => $dob,
            "pan_number" => $pancard,
            "passport_number" => $PassportNo,
            "passport_expiry" => $PassportExpDate,
            "passport_issue_date" => $PassportIssueDate,
            "lead_pax" =>   isset($passenger['IsLeadPax'])?$passenger['IsLeadPax']:NUll,
            "email_id" => isset($passenger['Email'])?$passenger['Email']:NUll,
            "mobile_number" => isset($passenger['ContactNo'])?$passenger['ContactNo']:NUll,
            "address_1" => isset($passenger['AddressLine1'])?$passenger['AddressLine1']:NUll,
            "address_2" => "",
            "city" => isset($passenger['City'])?$passenger['City']:NUll,
            "country_code" => isset($passenger['CountryCode'])?$passenger['CountryCode']:NUll,
            "nationality" => $nationality,
            "country_name" => isset($passenger['CountryName'])?$passenger['CountryName']:NUll,
            "ff_airline" => isset($passenger['FFAirline'])?$passenger['FFAirline']:NUll,
            "ff_number" => isset($passenger['FFNumber'])?$passenger['FFNumber']:NUll,
            "fare" => json_encode($paxfare),
            "baggage" => isset($passenger['Baggage'])?json_encode($passenger['Baggage']):NUll,
            "meal" => isset($passenger['Meal'])?json_encode($passenger['Meal']):NUll,
            "seat" => isset($passenger['Seat'])?json_encode($passenger['Seat']):NUll,
            "ticket_number" => isset($passenger['TicketNumber'])?$passenger['TicketNumber']:NUll,
            'booking_status' => $booking_status
        );
        array_push($insertPaxdata, $apipassengerData);
    }


}
return  array("paxData"=>$insertPaxdata,"NoofAdult"=>$NoofAdult,"NoofChild"=>$NoofChild,"NoofInfant"=>$NoofInfant,"PNR"=>$PNR,'gstInfo'=>$gstInfo);
 }