<?php

namespace Modules\CRSFlight\Controllers;

use App\Controllers\BaseController;
use App\Modules\CRSFlight\Models\CRSFlightModel;
use Modules\CRSFlight\Config\Validation;
use CodeIgniter\I18n\Time;


class CRSFlight extends BaseController
{

   public function __construct()
   {
      helper('Modules\CRSFlight\Helpers\flight');
      $this->FareType =  "Ti Special Fare";
      $this->FareTypeWithoutPNR =  "Offer Fare";
      $this->GetTimeZone = app_timezone();
   }

   public function Search(array $input, $tts_search_token, array $userauthdata)
   {
      if($input['JourneyType']!=3){
      $CRSFlightModel  =  new CRSFlightModel();
      $JourneyInfo  =  $CRSFlightModel->GetSectorData($input);
      return $JourneyInfo;
      }
      return array();
   }
   public function ConvertSearchResponse($input, $response, $convert_response, $custom_index, $common_data)
   {
      // increase allowed memory size
      ini_set('memory_limit', '128M');
      $selectedMarkupDataInfo = array();
      $selectedDiscountDataInfo = array();
      $airline_list = $common_data['airline_list'];
      $airport_list = $common_data['airport_list'];
      $airport_timezone = $common_data['airport_timezone'];
      $response  =  json_decode($response, true);
      if ($response) {
         $OriginDestinationCode =  array();
         $CRSFlightModel  =  new CRSFlightModel();
         $originAirportCode  =  array_column($input['AirSegments'], "Origin");
         $DestinationAirportCode  =  array_column($input['AirSegments'], "Destination");
         $OriginDestinationAirportCode  =  array_merge($originAirportCode, $DestinationAirportCode);
         $OriginDestinationAirportCode =  array_unique($OriginDestinationAirportCode);
         $OriginDsetinationAirportCodeInfo  =  $CRSFlightModel->GetOriginDsetinationAirportCodeInfo($OriginDestinationAirportCode);
         $super_admin_markup = $common_data['super_admin_markup'];
         $supplier_markup = $CRSFlightModel->supplier_markup($input);
         $super_admin_discount = $common_data['super_admin_discount'];
         $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
         $userauthdata = $common_data['userauthdata'];
         $preferredairlines = null;
         if (isset($input['PreferredCarriers'])) {
            $preferredairlines = $input['PreferredCarriers'];
         }
         // return array("airline_list"=>$airline_list);
         foreach ($response as $jkey => $result) {  
            $rtype = "OB";
            if ($jkey != 0) {
               $rtype = "IB";
            }
            foreach ($result as $list) {
                
               if ($list['FarePnrsupplierId'] != null && $list['SupplierStatus'] == "inactive") {
                  continue;
               }
               date_default_timezone_set("Asia/Kolkata");
               $SegmentInfo = json_decode($list['SegmentInfo'], true);
               foreach ($SegmentInfo as $mainkey => $super_flight_segment) {
               $originAirportCode = array_column($super_flight_segment, "origin_airport_code");
         $DestinationAirportCode = array_column($super_flight_segment, "destination_airport_code");
         $OriginDestinationAirportCode = array_merge($originAirportCode, $DestinationAirportCode);
               }
         $OriginDestinationAirportCode = array_unique($OriginDestinationAirportCode);
         $OriginDsetinationAirportCodeInfo = $CRSFlightModel->GetOriginDsetinationAirportCodeInfo($OriginDestinationAirportCode);
               $currentTimeStamp  =  strtotime(date("Y-m-d H:i"));
             
               $FareId = 'CRS' . $rtype . $list['SectorId'] . $list['FarePnrId'];
               $custom_index[$FareId] = array('Supplier' => 'CRS', "SectorId" => $list['SectorId'], "FarePnrId" => $list['FarePnrId'], "Rtype" => $rtype);
               $input['indexinfo'] =  array('Supplier' => 'CRS', "SectorId" => $list['SectorId'], "FarePnrId" => $list['FarePnrId'], "Rtype" => $rtype);
               $SectorSegmentDetail =  json_decode($list['SectorSegmentDetail'], true);
               $firstSegment  =  current($SectorSegmentDetail[0]);
               $AirlineCode =  trim(explode("-", $firstSegment['airline_code'])[0]);
               if ($preferredairlines != null) {
                  if (!in_array($AirlineCode, $preferredairlines)) {
                     continue;
                  }
               }
               $previous_fare = array();
               $PrivateFare =  get_crs_FareBreakup($input, $list);
               
               $FareType = get_flight_fare_type($this->FareType);
               if ($list['FarePnr'] == "") {
                  $FareType = get_flight_fare_type($this->FareTypeWithoutPNR);
               }

               /*  $FareType =  $FareTypeIdentifier['fareType'];
               $FareTypeColor =  $FareTypeIdentifier['color']; */
               $FareTypeColor =  "yellow";
               /*   $FareType =  $FareTypeIdentifier['fareType'];
               $FareTypeColor =  $FareTypeIdentifier['color']; */
               $CabinClass = $list['CabinClass'];
               $extraParam  =  array();
               $extraParam['FareType'] =  $FareType;
               $extraParam['FareClass'] = $list['BookingClass'];
               $FlightPrice = get_crs_flight_fare($input, $super_admin_markup, $supplier_markup, $super_admin_discount, $PrivateFare['Fare'], $PrivateFare['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $AirlineCode, $CabinClass, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam);
               $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
               $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
               $FareRuleData = [];
               $SelectedSectorFareData  =  $CRSFlightModel->GetSelectedSectorFareData($input);
               if ($SelectedSectorFareData) {
                  $Segments = json_decode($SelectedSectorFareData['SegmentInfo'], true);
                
               }
                $PnrDate  =  strtotime(date("Y-m-d H:i",strtotime($list['FarePnrDate']." ".$Segments[0][0]['departure_time'])));
                if($currentTimeStamp>$PnrDate){
                  continue;
                }
               if ($SelectedSectorFareData) {
                  $Segments = json_decode($SelectedSectorFareData['SegmentInfo'], true);
                  $firstSegment  =  current($Segments);
                  $firstSegment = reset($firstSegment);
                  $AirlineCode =  trim(explode("-", $firstSegment['airline_code'])[0]);
                  $FareRuleData   =   $CRSFlightModel->getFareRule($input, $SelectedSectorFareData['BookingClass'], $AirlineCode);
               }
               $SegmentInfo =  get_convert_segment_info($input, $list, $OriginDsetinationAirportCodeInfo, $airline_list, $airport_list, $airport_timezone, $FareRuleData);

              

               if (count($SegmentInfo['Segments']) == 1) {
                  $first_segment =  current($SegmentInfo['Segments'][0]);
                  $last_segment  =  end($SegmentInfo['Segments'][0]);
               } else {
                  $first_segment =  current($SegmentInfo['Segments']);
                  $first_segment =  current($first_segment);

                  $last_segment  =  end($SegmentInfo['Segments']);
                  $last_segment  =  end($last_segment);
               }

               $datetimeString =  str_replace("T", " ", $first_segment['Origin']['DepartTime']);

// Create a DateTime object from the string
$datetime = new \DateTime($datetimeString);

// Subtract four hours using DateInterval
$interval = new \DateInterval('PT'.$list['DisableBeforeDeparture']."H"); // PT4H represents a period of 4 hours
$datetime->sub($interval);

// Format the datetime as per your requirement
  $newDatetimeString = $datetime->format('Y-m-d H:i');
  $strtotimeStrig  =  strtotime($newDatetimeString);

  $todaystrtotime  =  strtotime(date('Y-m-d H:i'));
 
if($strtotimeStrig<=$todaystrtotime ){
continue;
}

               $dep_time = substr(str_replace("T", "-", $first_segment['Origin']['DepartTime']), 0, -3);
               $arr_time = substr(str_replace("T", "-", $last_segment['Destination']['ArrivalTime']), 0, -3);
               $custom_key = trim($first_segment['Airline']['AirlineCode']) . '-' . trim($first_segment['Airline']['FlightNumber']) . '-' . trim($dep_time) . '-' . trim($arr_time);
               $main_segment = array();
               $ttsbaggageinfo = array();
               $ttsbaggageinfo =  $SegmentInfo['ttsbaggageinfo'];
               $main_segment =  $SegmentInfo['Segments'];
               $main_segment =  $SegmentInfo['Segments'];
               $airline_list = $SegmentInfo['airline_list'];
               $airport_list = $SegmentInfo['airport_list'];

               $FareList = array(
                  'FareId'           => $FareId,
                  'IsRefundable'     => false,
                  'AirlineRemark'    => "",
                  'FareType'         => $FareType,
                  'FareTypeColor'     => $FareTypeColor,
                  'SeatBaggage'      => $ttsbaggageinfo,
                  'CabinClass'       => $CabinClass,
                  'FareClass'       => $list['BookingClass'],
                  'Fare'             => $FlightPrice['Fare'],
                  'SupplierFareBreakup'             => $FlightPrice,
                  'FareBreakdown'    => $FlightPrice['FareBreakdown'],
                  'PublishedPrice'   => $FlightPrice['Fare']['PublishedPrice'],
                  'OfferedPrice'   => $FlightPrice['Fare']['OfferedPrice'],
                  'Source'     => "CRS",
               );

               if (!isset($convert_response[$jkey][$custom_key]['Segments'])) {
                  $convert_response[$jkey][$custom_key]['Segments'] = $main_segment;
               }
               // pr($convert_response);
               if (isset($convert_response[$jkey][$custom_key]['FareList'])) {
                  $previous_fare = $convert_response[$jkey][$custom_key]['FareList'];
               }
               array_push($previous_fare, $FareList);
               /*------ Sort Price Group    --------*/
               $keys_publishprice = array_column($previous_fare, 'PublishedPrice');
               array_multisort($keys_publishprice, SORT_ASC, $previous_fare);
               $convert_response[$jkey][$custom_key]['FareList'] = $previous_fare;
            }
         }
      }
      return array('convert_response' => $convert_response, 'airline_list' => $airline_list, 'airport_list' => $airport_list, 'custom_index' => $custom_index, "airport_timezone" => $airport_timezone);
   }
   public function FareRule(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TTS_Result = array();
      $custom_index = array();
      $ErrorCode = 0;
      $ErrorMessage = '';
      $ErrorCode = 2;
      $ErrorMessage = 'data not available';
      $InputRequest = $common_data['InputRequest'];

      $InputRequest['indexinfo'] = $common_data['indexinfo'];

      $FareRuleData =  array();

      $CRSFlightModel  =  new CRSFlightModel();

      $SelectedSectorFareData  =  $CRSFlightModel->GetSelectedSectorFareData($InputRequest);

      if ($SelectedSectorFareData) {

         $Segments = json_decode($SelectedSectorFareData['SegmentInfo'], true);
         $firstSegment  =  current($Segments);
         $firstSegment = reset($firstSegment);

         $lastSegment  = end($Segments);
         $lastSegment = reset($lastSegment);

         $AirlineCode =  trim(explode("-", $firstSegment['airline_code'])[0]);

         $FareRuleData   =   $CRSFlightModel->getFareRule($InputRequest, $SelectedSectorFareData['BookingClass'], $AirlineCode);

         if ($FareRuleData && !empty($FareRuleData)) {

            $ErrorCode = 0;

            $ErrorMessage = '';

            $TTS_Result[0] = array(

               'Airline'         => $AirlineCode,

               'Origin'          => $firstSegment['origin_airport_code'],

               'Destination'     => $lastSegment['destination_airport_code'],

               'FareBasisCode'   => "",

               'FareRestriction' => "",

               'FareRuleDetail'  => $FareRuleData['description'],

            );
         }
      }

      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTS_Result
      );
      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }
   public function FareQuote(array $input, array $common_data, array $userauthdata)
   {
      $CRSFlightModel  =  new CRSFlightModel();
      $selectedMarkupDataInfo = $selectedDiscountDataInfo = array();
      $tts_search_token = $input['SearchTokenId'];
      $super_admin_markup = $common_data['super_admin_markup'];
      $super_admin_discount = $common_data['super_admin_discount'];
      $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
      $InputRequest = $common_data['InputRequest'];
      $supplier_markup = $CRSFlightModel->supplier_markup($InputRequest);
      $InputRequest['indexinfo'] = $common_data['indexinfo'];
      $TTS_Result = array();
      $custom_index = array();
      $IsPriceChanged = false;
      $FlightDetailChangeInfo = null;

      $SelectedSectorFareData  =  $CRSFlightModel->GetSelectedSectorFareData($InputRequest);

      if ($SelectedSectorFareData) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $IsPriceChanged = 0;
         $CabinClass = $SelectedSectorFareData['CabinClass'];
         $SegmentsData = json_decode($SelectedSectorFareData['SegmentInfo'], true);
         $main_segment = array();
         $OriginDestinationAirportCode = array();
         foreach ($SegmentsData as $Segments) {
            $OriginDestinationAirportCode =  array_merge($OriginDestinationAirportCode, array_column($Segments, "origin_airport_code"));
            $OriginDestinationAirportCode =   array_merge($OriginDestinationAirportCode, array_column($Segments, "destination_airport_code"));
         }
         $OriginDestinationAirportCode =  array_unique($OriginDestinationAirportCode);
         $OriginDsetinationAirportCodeInfo  =  $CRSFlightModel->GetOriginDsetinationAirportCodeInfo($OriginDestinationAirportCode);
         $TripIndicator = 1;
         $Departdate = $SelectedSectorFareData['FarePnrDate'];

         $available_seats = $SelectedSectorFareData['AvailableSeats'];
         if ($available_seats > 9) {
            $available_seats = 9;
         }
         $FlightDetailChangeInfo =  null;
         foreach ($SegmentsData as $mainkey => $Segments) {
            if ($mainkey == 1) {
               $Departdate = $SelectedSectorFareData['FareReturnPnrDate'];
            }
            $NumberOfSegment  =  count($Segments);
            if ($NumberOfSegment > 1) {
               if (!$InputRequest['IsDomestic']) {
                  $originAirportCode  =  array_column($flight_segment, 'origin_airport_code');
                  $destinationAirportCode  =  array_column($flight_segment, 'destination_airport_code');
                  $origindestinationAirportCode =  array_merge($originAirportCode, $destinationAirportCode);
                  $originDestinationTimeZone  = $CRSFlightModel->getAirportCodeTimeZoneIn($origindestinationAirportCode);
               }
            }
            foreach ($Segments as $segkey => $flight_segment) {
               $city_OriginKey =  array_search($flight_segment['origin_airport_code'], array_column($OriginDsetinationAirportCodeInfo, 'city_code'));
               $city_Origin = $OriginDsetinationAirportCodeInfo[$city_OriginKey];
               $city_destiKey =  array_search($flight_segment['destination_airport_code'], array_column($OriginDsetinationAirportCodeInfo, 'city_code'));
               $city_desti = $OriginDsetinationAirportCodeInfo[$city_destiKey];
               $Departdatetime = $Departdate . 'T' . $flight_segment['departure_time'] . ":00";
               $ArrivaldateTime = get_arrivaldate($Departdate, $flight_segment['arrival_time'], $flight_segment['is_next_day_arrival']);
               $Departdate =  explode("T", $ArrivaldateTime[0]);
               $layoverTime = "";
               $main_segment[$mainkey][$segkey] = array(
                  'TripIndicator'     => $TripIndicator,
                  'SegmentIndicator'  => $segkey + 1,
                  'CheckInBaggage'    => "",
                  'CabinBaggage'      => "",
                  'CabinClass'        => $CabinClass,
                  'Duration'          => get_crs_flight_duration($InputRequest['IsDomestic'], $Departdatetime, $ArrivaldateTime),
                  'TechStopPoint'     => array(),
                  'Craft'             => isset($flight_segment['Craft'])?$flight_segment['Craft']:"",
                  "LayoverTime" => $layoverTime,
                  'Airline' => array(
                     "AirlineCode"      => explode("-", $flight_segment['airline_code'])[0],
                     "AirlineName"      => explode("-", $flight_segment['airline_code'])[1],
                     "FlightNumber"     => $flight_segment['flight_number'],
                     "FareClass"        => $SelectedSectorFareData['BookingClass'],
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
                  )
               );
            }
         }

         /* $FareTypeIdentifier=get_flight_fare_type($this->FareType);
                           if($SelectedSectorFareData['FarePnr']=="") {
                              $FareTypeIdentifier=get_flight_fare_type($this->FareTypeWithoutPNR);
                           }
                           
                  $FareType =  $FareTypeIdentifier['fareType'];
                  $FareTypeColor =  $FareTypeIdentifier['color']; */
         $FareType = get_flight_fare_type($this->FareType);
         if ($SelectedSectorFareData['FarePnr'] == "") {
            $FareType = get_flight_fare_type($this->FareTypeWithoutPNR);
         }

         $FareTypeColor =  "yellow";

         $IsPanRequired = false;
         $GSTAllowed = false;
         $IsRefundable = false;
         $IsLCC = false;
         $IsGSTMandatory = false;
         $IsPassportRequired = false;
         $SectorSegmentDetail =  json_decode($SelectedSectorFareData['SectorSegmentDetail'], true);
         $firstSegment  =  current($SectorSegmentDetail[0]);
         $AirlineCode =  trim(explode("-", $firstSegment['airline_code'])[0]);
         $IsLCC =   $CRSFlightModel->GetAirlineInfo($AirlineCode, "islcc")['islcc'];
         $IsLCC =  (bool)$IsLCC;
         $PrivateFare =  get_crs_FareBreakup($InputRequest, $SelectedSectorFareData);
         $extraParam  =  array();
         $extraParam['FareType'] =  $FareType;
         $extraParam['FareClass'] = $SelectedSectorFareData['BookingClass'];
         $FlightPrice = get_crs_flight_fare($InputRequest, $super_admin_markup, $supplier_markup, $super_admin_discount, $PrivateFare['Fare'], $PrivateFare['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $AirlineCode, $CabinClass, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');


         $TTS_Result = array(
            'ResultIndex'                 => $input['ResultIndex'],
            'IsLCC'                       => $IsLCC,
            'IsRefundable'                => $IsRefundable,
            'IsPanRequiredAtBook'         => $IsPanRequired,
            'IsPassportRequiredAtBook'    => $IsPassportRequired,
            'GSTAllowed'                  => $GSTAllowed,
            'IsGSTMandatory'              => $IsGSTMandatory,
            'AirlineRemark'               => '',
            'FareType'                    => $FareType,
            'FareTypeColor'                    => $FareTypeColor,
            'Fare'                        => $FlightPrice['Fare'],
            'FareBreakdown'               => $FlightPrice['FareBreakdown'],
            'Segments'                    => $main_segment,
            'LastTicketDate'              => '',
            'TicketAdvisory'              => '',
            'ValidatingAirline'           => $AirlineCode
         );
         $FarePnrsupplierId  =  null;
         $supplierfareBreaup  =  array();
         $IsSupplierFare  =  0;
         $TTS_Invoice_Amount = floatval($FlightPrice['Fare']['OfferedPrice']) + floatval($FlightPrice['Fare']['TDS']);
         if (isset($SelectedSectorFareData['FarePnrsupplierId']) && ($SelectedSectorFareData['FarePnrsupplierId'] != null && $SelectedSectorFareData['FarePnrsupplierId'] != '')) {
            $FarePnrsupplierId =  $SelectedSectorFareData['FarePnrsupplierId'];
            $supplierfareBreaup    = get_crs_FareBreakup($InputRequest, $SelectedSectorFareData);
            $IsSupplierFare  =  1;
         }
         $custom_index[$input['ResultIndex']] = array('TTS_Invoice_Amount' => $TTS_Invoice_Amount, 'IsLCC' => $IsLCC, 'IsPanRequiredAtBook' => $IsPanRequired, 'IsPassportRequiredAtBook' => $IsPassportRequired, 'GSTAllowed' => $GSTAllowed, 'IsGSTMandatory' => $IsGSTMandatory, 'AirlineCode' => $AirlineCode, 'FareBreakdown' => $FlightPrice['FareBreakdown'], 'SuperAdminFareBreakup' => $FlightPrice['SuperAdminFareBreakup'], 'WebPartnerFareBreakup' => $FlightPrice['WebPartnerFareBreakup'], 'SupplierFareBreakup' => $FlightPrice['SupplierFareBreakup'],  'SectorId' => $common_data['indexinfo']['SectorId'], 'FarePnrId' => $common_data['indexinfo']['FarePnrId'], 'Rtype' => $common_data['indexinfo']['Rtype'], 'Supplier' => 'CRS', 'SupplierFareBreaup' => $supplierfareBreaup, "FarePnrsupplierId" => $FarePnrsupplierId, 'IsSupplierFare' => $IsSupplierFare);
      } else {
         $ErrorCode = 2;
         $ErrorMessage = "Seat Sold Out.";
      }
      $tts_response = array(
         'UserIp'                 => $input['UserIp'],
         'SearchTokenId'          => $tts_search_token,
         'Error'                  => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'FlightDetailChangeInfo' => $FlightDetailChangeInfo,
         'IsPriceChanged'         => $IsPriceChanged,
         'Result'                 => $TTS_Result
      );

      /*--------------Start Insert API Logs------------------*/
      $CRSFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $input, $tts_response, 'FareConfirmation', 'CRS', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      if ($ErrorCode != 0) {
         unset($tts_response['IsPriceChanged']);
      }

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }
   public function SSR(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];
      $ErrorCode = 0;
      $ErrorMessage = '';
      $TTS_Result = array();
      $custom_index = array();
      $ErrorCode = 2;
      $ErrorMessage = 'data not available';
      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTS_Result
      );
      $CRSFlightModel  =  new CRSFlightModel();
      /*--------------Start Insert API Logs------------------*/
      $CRSFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $input, $tts_response, 'SSR', 'CRS', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }
   public function Ticket(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];
      $flight_offline = $common_data['FlightOffline'];
      $account_log_lastid = $common_data['SaveData']['account_log_lastid'];
      $booking_lastid = $common_data['SaveData']['booking_lastid'];
      $CRSFlightModel  =  new CRSFlightModel();
      $InputRequest = $common_data['InputRequest'];
      $InputRequest['indexinfo']['SectorId'] = $common_data['indexinfo']['SectorId'];
      $InputRequest['indexinfo']['FarePnrId'] = $common_data['indexinfo']['FarePnrId'];
      $InputRequest['indexinfo']['Rtype'] = $common_data['indexinfo']['Rtype'];
      $SelectedSectorFareData  =  $CRSFlightModel->GetSelectedSectorFareData($InputRequest);
      $custom_index = array();
      $getbooking_detail = array();
      if ($SelectedSectorFareData && !empty($SelectedSectorFareData)) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $result = $SelectedSectorFareData;
         if ($IsLCC) {
            $getbooking_detail = $result;
         } else {
            // Flight Hold Condition
            if ($flight_offline && $flight_offline['is_hold'] == 'Hold') {
               $getbooking_detail = $result;
            } else {
               $getbooking_detail = $result;
            }
         }
      } else {
         $ErrorCode = 2;
         $ErrorMessage = "Seat Sold Out.";
      }
      if ($ErrorCode == 0) {
         CRSFlight::UpdateBookingData($getbooking_detail, $input['Passengers'], $common_data, $booking_lastid, $account_log_lastid);
         $booking_data = $CRSFlightModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId'], "id" => $booking_lastid]);
         $tts_response = ConvertBookingDetail($input, $booking_data);
      } else {
         if ($getbooking_detail) {
            CRSFlight::UpdateBookingData($getbooking_detail, $input['Passengers'], $common_data, $booking_lastid, $account_log_lastid);
         } else {
            $booking_status = 'Pending';
            $booking_update_data = array('booking_status' => $booking_status);
            $CRSFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $CRSFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], $booking_update_data);  /* change By harish */
         }

         $tts_response = array(
            'UserIp'        => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result'        => array()
         );
      }
      /*--------------Start Insert API Logs------------------*/
      $CRSFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $input, $tts_response, "Book", 'CRS', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $tts_response;
   }
   public function UpdateBookingData($getbooking_detail, $Passengers, $CommonData, $booking_lastid, $account_log_lastid)
   {

      $CRSFlightModel = new CRSFlightModel();
      $SearchInputRequest =  $CommonData['InputRequest'];
      $SearchInputRequest =  $CommonData['InputRequest'];
      $IsPriceChanged =  false;
      $IsTimeChanged =  false;
      $origin = $getbooking_detail['Origin'];
      $destination = $getbooking_detail['Destination'];
      $pnr = trim($getbooking_detail['FarePnr']);
      $SegmentInfo = json_decode($getbooking_detail['SegmentInfo'], true);
      $firstSegment =  current($SegmentInfo[0]);
      $airline_code = trim(explode("-", $firstSegment['airline_code'])[0]);
      $flight_number = trim($firstSegment['flight_number']);
      $departure_date = $getbooking_detail['FarePnrDate'];
      $fare_rule = array();
      $airline_pnr = array();
      $pax_update_data = array();
      $booking_status = 'Processing';
      $ticket_id = "";
      $ticket_number = "";
      if (isset($pnr) && ($pnr != "" && $pnr != null)) {
         $ticket_number =  $pnr;
         $booking_status = 'Confirmed';
      }


      if ($booking_status == 'Confirmed') {
/* 
         $Confirmationprefix          =  $CRSFlightModel->getDataRowType("super_admin_website_setting", array(), "flight_confirmation_counter,flight_confirmation_prefix,id");

         $BookingConfirmationNumber  =  GenerateConfirmationNumber("Flight", $Confirmationprefix['flight_confirmation_prefix'], ($Confirmationprefix['flight_confirmation_counter'] + 1));

         $CRSFlightModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("flight_confirmation_counter" => ($Confirmationprefix['flight_confirmation_counter'] + 1)));

         $CRSFlightModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $booking_lastid, "service" => "flight", 'transaction_type' => "debit", 'action_type' => "booking"], ["booking_confirmation_number" => $BookingConfirmationNumber]);
   */    }
      $NoofBookedSheet  =  $SearchInputRequest['Adult'] + $SearchInputRequest['Child'];
      foreach ($Passengers as $key => $Passenger) {

         $PaxId =  $key + 1;
         $pax_update_data = array(
            'pax_id'            => $PaxId,
            'ticket_id'         => $ticket_id,
            'ticket_number'     => $ticket_number,
            "booking_status" => $booking_status
         );
         $CRSFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid, "first_name" => trim($Passenger['FirstName']), 'pax_type' => get_paxtype_name($Passenger['PaxType'])], $pax_update_data);
      }
      $bookedSheetInfo =  $CRSFlightModel->GetData("flight_crs_details", ['id' => trim($getbooking_detail['FarePnrId']), "privatefare_id" => trim($getbooking_detail['SectorId'])], "booked_seats,available_seats,supplier_id");
      $SeatUpdateData  =  array("booked_seats" => ($bookedSheetInfo['booked_seats'] + $NoofBookedSheet), "available_seats" => ($bookedSheetInfo['available_seats'] - $NoofBookedSheet));
      if ($booking_status == 'Confirmed') {
         $CRSFlightModel->updateUserData('flight_crs_details', ['id' => trim($getbooking_detail['FarePnrId']), "privatefare_id" => trim($getbooking_detail['SectorId'])], $SeatUpdateData);
      }
      $booking_update_data = array(
         'is_price_changed'   => $IsPriceChanged,
         'is_time_changed'    => $IsTimeChanged,
         'booking_status'     => $booking_status,
         'fare_rule'          => json_encode($fare_rule),
         'pnr'                => $pnr,
         'fare_crs_details_id'     => $CommonData['indexinfo']['FarePnrId'],
         'airline_pnr'       => json_encode($airline_pnr)
      );
      if(!empty($bookedSheetInfo['supplier_id']) && $bookedSheetInfo['supplier_id'] > 0){
         $booking_update_data['supplier_id'] = $bookedSheetInfo['supplier_id'];
      }else{
         $booking_update_data['supplier_booking_id'] = $booking_lastid;
      }

      $CRSFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);

      $PaxName = $Passengers[0]['FirstName'] . ' ' . $Passengers[0]['LastName'] . ' X ' . count($Passengers);
      $Sector = $origin . '-' . $destination;
      $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date, 'AirlineString' => $airline_code . $flight_number, 'TicketNo' => $pnr);
      if(!empty($bookedSheetInfo['supplier_id']) && $bookedSheetInfo['supplier_id'] > 0 && $booking_status == 'Confirmed'){
         $bookingInfo = $CRSFlightModel->get_data("flight_booking_list", ['id' => trim($booking_lastid)], "web_partner_id,is_domestic,supplier_fare_break_up");
         $supplierfareBreaup = json_decode($bookingInfo['supplier_fare_break_up'],true);
         $TTS_Invoice_Amount = $supplierfareBreaup['PublishedPrice'];
         $Auth_User_Balance = $CRSFlightModel->get_auth_supplier_account_balance($bookedSheetInfo['supplier_id']);
         $balance = $Auth_User_Balance['balance'] + $TTS_Invoice_Amount;
         $supplier_account_log = array(
             'supplier_id' => $bookedSheetInfo['supplier_id'],
             'credit' => $TTS_Invoice_Amount,
             'booking_confirmation_number' => $BookingConfirmationNumber,
             'balance' => round_value($balance),
             'service_log' => json_encode($service_log),
             'remark' => 'Ticket Created Through API',
             'service' => 'flight',
             'booking_ref_no' => $booking_lastid,
             'transaction_type' => 'credit',
             'action_type' => 'booking',
             'payment_mode' => 'Account_Transfer',
             'created' => create_date()
         );
         $supplier_account_log_lastid = $CRSFlightModel->insertData('supplier_account_log', $supplier_account_log);

         $acc_ref_number = reference_number($supplier_account_log_lastid, "Flight", $bookingInfo['is_domestic'], "booking");

         $CRSFlightModel->updateUserData('supplier_account_log', ['id' => $supplier_account_log_lastid], ['acc_ref_number'=>$acc_ref_number]);
      }

     
      $account_update_data = array('service_log'    => json_encode($service_log));
      $CRSFlightModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
   }
}
