<?php

namespace Modules\TBOFlight\Controllers;

use App\Controllers\BaseController;
use App\Modules\TBOFlight\Models\TBOFlightModel;
use Modules\TBOFlight\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;


class TBOFlight extends BaseController
{

   public function __construct()
   {
      helper('Modules\TBOFlight\Helpers\flight');
      $this->GetTimeZone = app_timezone();
      $this->TokenId = TBOFlight::Authenticate();
   }

   /**
    * ----------------------------------------------
    * TBO Authenticate Function And Generate Token
    * ---------------------------------------------
    */

   private function Authenticate()
   {

      $TBOFlightModel = new TBOFlightModel();
      $request   = \Config\Services::request();
      $apiconfig = new APIConfig;
      $credential = $apiconfig->tbo_credential;

      // Live/Test, credentials and URLs all come from app/.env via APIConfig
      $ClientId  = $credential['ClientId'];
      $UserName  = $credential['UserName'];
      $Password  = $credential['Password'];
      $this->Authenticate_URL = $credential['Authenticate_URL'];
      $this->AirService_URL = $credential['AirService_URL'];
      $this->AirBooking_URL = $credential['AirBooking_URL'];
      $this->Mode = $credential['Mode'];
      $token_id = null;
      $db_token_id = $TBOFlightModel->fetch_auth_token(strtotime(Time::today($this->GetTimeZone)), $this->Mode);
      
    
    //   if (empty($db_token_id)) {
    if (empty($db_token_id) || empty($db_token_id['token_id'])) {
         // TBO whitelists the server IP, so prefer tbo.endUserIp from .env
         $requestdata = array(
            'ClientId'  => $ClientId,
            'UserName'  => $UserName,
            'Password'  => $Password,
            'EndUserIp' => $credential['EndUserIp'] ?: $request->getIPAddress(),
         );
         
         $url = $this->Authenticate_URL;
        //  $response = TBO_Request($url, $requestdata);
        
         $response = TBO_Other_Request($url, $requestdata);
         
         if ($response['Status'] == 1) {
            $token_id = $response['TokenId'];
         }
         /*--------------Start Insert API Logs------------------*/
         $insertlog = array(
            'id'       => 1,
            'token_id' => $token_id,
            'api_mode' => $this->Mode,
            'service' => "Flight",
            'request'  => json_encode(array_merge($requestdata, ['Password' => '******'])),
            'response' => json_encode($response),
            'created'  => strtotime(Time::today())
         );
         $TBOFlightModel->insert_update_data('tbo_auth_token', $insertlog);
         /*--------------End Insert API Logs------------------*/
      } else {
         $token_id = $db_token_id['token_id'];
      }
      return $token_id;
   }

   public function Search(array $input, $tts_search_token, array $userauthdata)
   {
      $class = $input['CabinClass'];
      $segments = array();
      if ($input['AirSegments']) {
         foreach ($input['AirSegments'] as $airsegment) {
            $segments[] = array(
               'Origin' => $airsegment['Origin'],
               'Destination' => $airsegment['Destination'],
               'FlightCabinClass' => $class,
               'PreferredDepartureTime' => $airsegment['PreferredTime'],
               'PreferredArrivalTime' => $airsegment['PreferredTime'],
            );
         }
      }

      $directflight = false;
      if (isset($input['DirectFlight'])) {
         $directflight = $input['DirectFlight'];
      }

      $preferredairlines = null;
      $sources = null;
      if (isset($input['PreferredCarriers'])) {
         $airlineSource =  array();
         $sourceAirlines  =  array();
         $preferredairlinesSource  =  array();
         foreach ($input['PreferredCarriers'] as $PreferredAirline) {
            $sourceAirlines           =  get_flight_source($PreferredAirline);
            if ($sourceAirlines != 'ALL') {
               if ($PreferredAirline == "UK" || $PreferredAirline == "AI") {
                  array_push($preferredairlinesSource, $PreferredAirline);
               }
               array_push($airlineSource, $sourceAirlines);
            }
         }
         if (!empty($preferredairlinesSource)) {
            $preferredairlines = $preferredairlinesSource;
         }
         $sources =  $airlineSource;
      }


      $request = array(
         'EndUserIp'         => $input['UserIp'],
         'TokenId'           => $this->TokenId,
         'AdultCount'        => intval($input['Adult']),
         'ChildCount'        => intval($input['Child']),
         'InfantCount'       => intval($input['Infant']),
         'DirectFlight'      => (bool)$directflight,
         'OneStopFlight'     => false,
         'JourneyType'       => intval($input['JourneyType']),
         'PreferredAirlines' => $preferredairlines,
         'Segments'          => $segments,
         'Sources'           => $sources
      );
      
      $url = "$this->AirService_URL/rest/Search/";
      
      return array('Supplier' => 'TBO', 'URL' => $url, 'Request' => json_encode($request));
   }

   public function ConvertSearchResponse($input, $response, $convert_response, $custom_index, $common_data)
   {
      // increase allowed memory size
      ini_set('memory_limit', '128M');

      $airline_list = $common_data['airline_list'];
      $airport_list = $common_data['airport_list'];

      if ($response) {
         $response_array = json_decode($response, true);
         if (isset($response_array['Response']['Error']['ErrorCode']) && $response_array['Response']['Error']['ErrorCode'] == 0) { //Added By HS
        //  if ($response_array['Response']['Error']['ErrorCode'] == 0) {  Closed BY HS
            $super_admin_markup = $common_data['super_admin_markup'];
            $super_admin_discount = $common_data['super_admin_discount'];
            $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
            $userauthdata = $common_data['userauthdata'];
            $journeykey  =  "OB";
            $TraceId = $response_array['Response']['TraceId'];
            foreach ($response_array['Response']['Results'] as $jkey => $result) {
               foreach ($result as $listkey => $list) {
                  if ($jkey == 1) {
                     $journeykey  = "IB";
                  }
                  /* $FareId='T'.$list['ResultIndex']; */
                  $FareId = 'T' . $journeykey . $listkey;
                  $custom_index[$FareId] = array('ResultIndex' => $list['ResultIndex'], 'TraceId' => $TraceId, 'Supplier' => 'TBO');

                  $previous_fare = array();
                  $FlightPrice = get_flight_fare($input, $super_admin_markup, $super_admin_discount, $list['Fare'], $list['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $list['AirlineCode']);

                  if (isset($list['FareClassification']['Type'])) {
                     $FareType = get_flight_fare_type($list['FareClassification']['Type']);
                  } else {
                     $FareType = get_flight_fare_type('Publish');
                  }
                  $CabinClass = '';
                  if (count($list['Segments']) == 1) {
                     $first_segment =  current($list['Segments'][0]);
                     $last_segment  =  end($list['Segments'][0]);
                  } else {
                     $first_segment =  current($list['Segments']);
                     $first_segment =  current($first_segment);

                     $last_segment  =  end($list['Segments']);
                     $last_segment  =  end($last_segment);
                  }

                  $CabinClass = get_cabin_class_name($first_segment['CabinClass']);
                  $dep_time = substr(str_replace("T", "-", $first_segment['Origin']['DepTime']), 0, -3);
                  $arr_time = substr(str_replace("T", "-", $last_segment['Destination']['ArrTime']), 0, -3);
                  $custom_key = trim($first_segment['Airline']['AirlineCode']) . '-' . trim($first_segment['Airline']['FlightNumber']) . '-' . trim($dep_time) . '-' . trim($arr_time);

                  $main_segment = array();
                  $ttsbaggageinfo = array();
                  foreach ($list['Segments'] as $mkey => $Segment) {
                     $ttssegment = array();
                     foreach ($Segment as $segkey => $item) {
                        /*---Start Temporary Store Airline and Airport Data  ------*/
                        $airline_list[$item['Airline']['AirlineCode']] = ucwords(strtolower($item['Airline']['AirlineName']));
                        $airport_list[$item['Origin']['Airport']['AirportCode']] = array(
                           'code'        => $item['Origin']['Airport']['AirportCode'],
                           'name'        => $item['Origin']['Airport']['AirportName'],
                           'city_code'   => $item['Origin']['Airport']['CityCode'],
                           'city_name'   => $item['Origin']['Airport']['CityName'],
                           'country_name' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                           'country_code' => $item['Origin']['Airport']['CountryCode']
                        );

                        $airport_list[$item['Destination']['Airport']['AirportCode']] = array(
                           'code'        => $item['Destination']['Airport']['AirportCode'],
                           'name'        => $item['Destination']['Airport']['AirportName'],
                           'city_code'   => $item['Destination']['Airport']['CityCode'],
                           'city_name'   => $item['Destination']['Airport']['CityName'],
                           'country_name' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                           'country_code' => $item['Destination']['Airport']['CountryCode']
                        );
                        /*---End Temporary Store Airline and Airport Data  ------*/

                        $Sector = $item['Origin']['Airport']['AirportCode'] . '-' . $item['Destination']['Airport']['AirportCode'];
                        $ttsbaggageinfo[$mkey][$segkey] = array(
                           'NoOfSeatAvailable' => check_isset($item, 'NoOfSeatAvailable'),
                           'Sector'            => $Sector,
                           'CheckIn'           => $item['Baggage'],
                           'Cabin'             => $item['CabinBaggage']
                        );

                        $TripIndicator = 1;
                        if (isset($item['TripIndicator'])) {
                           $TripIndicator = $item['TripIndicator'];
                        }
                        $ttssegment[$segkey] = array(
                           'TripIndicator'     => $TripIndicator,
                           'SegmentIndicator'  => $item['SegmentIndicator'],
                           'Duration'          => $item['Duration'],
                           'TechStopPoint'     => array(),
                           'Craft'             => $item['Craft'],
                           'Airline' => array(
                              'AirlineCode'      => $item['Airline']['AirlineCode'],
                              'AirlineName'      => ucwords(strtolower($item['Airline']['AirlineName'])),
                              'FlightNumber'     => (int) $item['Airline']['FlightNumber'],
                              'FareClass'        => $item['Airline']['FareClass'],
                              'OperatingCarrier' => $item['Airline']['OperatingCarrier']
                           ),
                           'Origin' => array(
                              'AirportCode' => $item['Origin']['Airport']['AirportCode'],
                              'AirportName' => $item['Origin']['Airport']['AirportName'],
                              'CityCode'    => $item['Origin']['Airport']['CityCode'],
                              'CityName'    => $item['Origin']['Airport']['CityName'],
                              'CountryCode' => $item['Origin']['Airport']['CountryCode'],
                              'CountryName' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                              'Terminal'    => $item['Origin']['Airport']['Terminal'],
                              'DepartTime'  => $item['Origin']['DepTime']
                           ),
                           'Destination' => array(
                              'AirportCode' => $item['Destination']['Airport']['AirportCode'],
                              'AirportName' => $item['Destination']['Airport']['AirportName'],
                              'CityCode'    => $item['Destination']['Airport']['CityCode'],
                              'CityName'    => $item['Destination']['Airport']['CityName'],
                              'CountryCode' => $item['Destination']['Airport']['CountryCode'],
                              'CountryName' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                              'Terminal'    => $item['Destination']['Airport']['Terminal'],
                              'ArrivalTime' => $item['Destination']['ArrTime']
                           )
                        );
                     }
                     $main_segment[$mkey] = $ttssegment;
                  }

                  $FareList = array(
                     'FareId'           => $FareId,
                     'IsRefundable'     => $list['IsRefundable'],
                     'AirlineRemark'    => $list['AirlineRemark'],
                     'FareType'         => $FareType,
                     'SeatBaggage'      => $ttsbaggageinfo,
                     'CabinClass'       => $CabinClass,
                     'Fare'             => $FlightPrice['Fare'],
                     'FareBreakdown'    => $FlightPrice['FareBreakdown'],
                     'PublishedPrice'   => $FlightPrice['Fare']['PublishedPrice'],
                     'OfferedPrice'     => $FlightPrice['Fare']['OfferedPrice'],
                     'Source'     => "TBO",
                  );

                  /*------ Add Source    --------*/
                  if (!isset($convert_response[$jkey][$custom_key]['Segments'])) {
                     $convert_response[$jkey][$custom_key]['Segments'] = $main_segment;
                  }
                  if (isset($convert_response[$jkey][$custom_key]['FareList'])) {
                     $previous_fare = $convert_response[$jkey][$custom_key]['FareList'];
                  }
                  array_push($previous_fare, $FareList);
                  $convert_response[$jkey][$custom_key]['FareList'] = $previous_fare;
               }
            }
         }
         return array('convert_response' => $convert_response, 'airline_list' => $airline_list, 'airport_list' => $airport_list, 'custom_index' => $custom_index);
      } else {
         return array();
      }
   }
   
   public function GetCalendarFare(array $input, $tts_search_token, array $userauthdata)
   {
       
    //   $totalPassenger=$input['Child']+$input['Adult']+$input['Infant'];
    //   $class = $input['CabinClass'];
    //   $segments = array();
    //   $daysDiffrent = "";
    //   $nextMonthSegment  =  array();
    //   if ($input['AirSegments']) {
    //      foreach ($input['AirSegments'] as $airsegment) {
    //         $currentMonth  =  date("m");
    //         $DepartureDate  =  explode("T", $airsegment['PreferredTime'])[0];
    //         $DepartureMonth = date('m', strtotime($DepartureDate));
    //         $maxDaysMonth = date('t', strtotime($DepartureDate));
    //         $currentDayOfMonth = date('j', strtotime($DepartureDate));
    //         $daysDiffrent  =  $maxDaysMonth - $currentDayOfMonth;
    //         $segments[] = array(
    //           'Origin' => $airsegment['Origin'],
    //           'Destination' => $airsegment['Destination'],
    //           'FlightCabinClass' => $class,
    //           'PreferredDepartureTime' => $airsegment['PreferredTime'],
    //           'PreferredArrivalTime' => $airsegment['PreferredTime'],
    //         );
    //      }
    //      if (($currentMonth != $DepartureMonth)) {
             
    //         $nextMonthSegment =   $segments[0];
    //         $DayYear = date('Y', strtotime("+1 months", strtotime($DepartureDate)));
    //         $DayMonth = date('m', strtotime("+1 months", strtotime($DepartureDate)));
    //         $nextMonthSegment['PreferredDepartureTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
    //         $nextMonthSegment['PreferredArrivalTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
            
    //         $currentDateSegment = $segments[0];
    //         $currentDateSegment['PreferredDepartureTime'] = date('Y-m-d') . 'T00:00:00';
    //         $currentDateSegment['PreferredArrivalTime']   = date('Y-m-d') . 'T00:00:00';
    //      }else
    //      {
    //         $nextMonthSegment =   $segments[0];
    //         $DayYear = date('Y', strtotime("+1 months", strtotime($DepartureDate)));
    //         $DayMonth = date('m', strtotime("+1 months", strtotime($DepartureDate)));
    //         $nextMonthSegment['PreferredDepartureTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
    //         $nextMonthSegment['PreferredArrivalTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
    //      }
    //   }
      
      
    //   $preferredairlines = null;
    //   if (isset($input['PreferredCarriers'])) {
    //      $preferredairlines = $input['PreferredCarriers'];
    //   }
    //   $sources = null;

    //   $request[0] = array(
    //      'EndUserIp'         => $input['UserIp'],
    //      'TokenId'           => $this->TokenId,
    //      'JourneyType'       => intval($input['JourneyType']),
    //      'PreferredAirlines' => $preferredairlines,
    //      'Segments'          => $segments,
    //      'Sources'           => $sources
    //   );
      
      
    //   if(!empty($currentDateSegment)){
    //      $request[1] =  $request[0];
    //      $request[1]['Segments'] = array($currentDateSegment);
    //   }
     
      
    //   if (!empty($nextMonthSegment)) {
    //      $request[2] =  $request[0];
    //      $request[2]['Segments'] = array($nextMonthSegment);
    //   }
    
    $totalPassenger = $input['Child'] + $input['Adult'] + $input['Infant'];
        $class = $input['CabinClass'];
        
        $segments = [];
        $request = [];
        
        if (!empty($input['AirSegments'])) {
        
            foreach ($input['AirSegments'] as $airsegment) {
        
                $DepartureDate = explode("T", $airsegment['PreferredTime'])[0];
        
                $segments[] = [
                    'Origin'                 => $airsegment['Origin'],
                    'Destination'            => $airsegment['Destination'],
                    'FlightCabinClass'       => $class,
                    'PreferredDepartureTime' => $airsegment['PreferredTime'],
                    'PreferredArrivalTime'   => $airsegment['PreferredTime'],
                ];
            }
        
            $preferredairlines = isset($input['PreferredCarriers'])
                ? $input['PreferredCarriers']
                : null;
        
            $sources = null;
        
            // Original request
            $request[] = [
                'EndUserIp'         => $input['UserIp'],
                'TokenId'           => $this->TokenId,
                'JourneyType'       => (int)$input['JourneyType'],
                'PreferredAirlines' => $preferredairlines,
                'Segments'          => $segments,
                'Sources'           => $sources
            ];
        
            // Base segment
            $baseSegment = $segments[0];
        
            $currentMonth   = (int)date('n');
            $departureMonth = (int)date('n', strtotime($DepartureDate));
        
            /*
             * If departure month is different from current month,
             * create one request for today's date.
             */
            if ($currentMonth != $departureMonth) {
        
                $currentDateSegment = $baseSegment;
        
                $todayDate = date('Y-m-d') . 'T00:00:00';
        
                $currentDateSegment['PreferredDepartureTime'] = $todayDate;
                $currentDateSegment['PreferredArrivalTime']   = $todayDate;
        
                $request[] = [
                    'EndUserIp'         => $input['UserIp'],
                    'TokenId'           => $this->TokenId,
                    'JourneyType'       => (int)$input['JourneyType'],
                    'PreferredAirlines' => $preferredairlines,
                    'Segments'          => [$currentDateSegment],
                    'Sources'           => $sources
                ];
            }
        
            /*
             * Create requests for every remaining month
             * of the departure year.
             */
        
            $departureYear = (int)date('Y', strtotime($DepartureDate));
        
            for ($month = $departureMonth + 1; $month <= 12; $month++) {
        
                $monthSegment = $baseSegment;
        
                $segmentDate = sprintf(
                    '%04d-%02d-01T00:00:00',
                    $departureYear,
                    $month
                );
        
                $monthSegment['PreferredDepartureTime'] = $segmentDate;
                $monthSegment['PreferredArrivalTime']   = $segmentDate;
        
                $request[] = [
                    'EndUserIp'         => $input['UserIp'],
                    'TokenId'           => $this->TokenId,
                    'JourneyType'       => (int)$input['JourneyType'],
                    'PreferredAirlines' => $preferredairlines,
                    'Segments'          => [$monthSegment],
                    'Sources'           => $sources
                ];
            }
        }
      
     
      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      foreach ($request as $supperkey => $requestData) {
         $url = "$this->AirService_URL/rest/GetCalendarFare/";
         $response = TBO_Request($url, $requestData);
        //  if ($response['Response']['Error']['ErrorCode'] == 0) {
        if (isset($response['Response']['Error']['ErrorCode']) && $response['Response']['Error']['ErrorCode'] == 0){
            $ErrorCode = 0;
            $ErrorMessage = '';
            $results = $response['Response']['SearchResults'];
            if ($results) {
               foreach ($results as $key => $result) {
                  $Calendar  =  array(
                     'AirlineCode'         => $result['AirlineCode'],
                     'AirlineName'         => $result['AirlineName'],
                     'DepartureDate'       => $result['DepartureDate'],
                     'IsLowestFareOfMonth' => $result['IsLowestFareOfMonth'],
                     'Fare'                => $result['Fare']*$totalPassenger - 0.5 
                  );
                  array_push($TTS_Result, $Calendar);
               }
            }
         } else {
            $ErrorCode = $response['Response']['Error']['ErrorCode'];
            $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
         }
         /*--------------Start Insert API Logs------------------*/
         $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'CalendarFare', 'TBO', strtotime(Time::now($this->GetTimeZone)));
         /*--------------End Insert API Logs--------------------*/
      }
      
              // Make API result date-wise
        $calendar = array();
        
        foreach ($TTS_Result as $row) {
        
            $date = date('Y-m-d', strtotime($row['DepartureDate']));
        
            $calendar[$date] = $row;
        }
        
        $FinalResult = array();
        
        foreach ($request as $requestData) {
        
            $requestDate = date(
                'Y-m-d',
                strtotime($requestData['Segments'][0]['PreferredDepartureTime'])
            );
        
            $year  = date('Y', strtotime($requestDate));
            $month = date('m', strtotime($requestDate));
        
            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
            for ($i = 1; $i <= $days; $i++) {
        
                $currentDate = sprintf(
                    '%04d-%02d-%02d',
                    $year,
                    $month,
                    $i
                );
        
                if (isset($calendar[$currentDate])) {
        
                    $FinalResult[] = $calendar[$currentDate];
        
                } else {
        
                    $FinalResult[] = array(
                        'AirlineCode'         => '',
                        'AirlineName'         => '',
                        'DepartureDate'       => $currentDate,
                        'IsLowestFareOfMonth' => false,
                        'Fare'                => 0
                    );
                }
            }
        }
        
        $TTS_Result = $FinalResult;

      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTS_Result
      );

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }

//   public function GetCalendarFare(array $input, $tts_search_token, array $userauthdata)
//   {
       
//       $totalPassenger=$input['Child']+$input['Adult']+$input['Infant'];
//       $class = $input['CabinClass'];
//       $segments = array();
//       $daysDiffrent = "";
//       $nextMonthSegment  =  array();
//       if ($input['AirSegments']) {
//          foreach ($input['AirSegments'] as $airsegment) {
//             $currentMonth  =  date("m");
//             $DepartureDate  =  explode("T", $airsegment['PreferredTime'])[0];
//             $DepartureMonth = date('m', strtotime($DepartureDate));
//             $maxDaysMonth = date('t', strtotime($DepartureDate));
//             $currentDayOfMonth = date('j', strtotime($DepartureDate));
//             $daysDiffrent  =  $maxDaysMonth - $currentDayOfMonth;
//             $segments[] = array(
//               'Origin' => $airsegment['Origin'],
//               'Destination' => $airsegment['Destination'],
//               'FlightCabinClass' => $class,
//               'PreferredDepartureTime' => $airsegment['PreferredTime'],
//               'PreferredArrivalTime' => $airsegment['PreferredTime'],
//             );
//          }
//          if ($daysDiffrent <= 7 && ($currentMonth == $DepartureMonth)) {
//             $nextMonthSegment =   $segments[0];
//             $DayYear = date('Y', strtotime("+1 months", strtotime($DepartureDate)));
//             $DayMonth = date('m', strtotime("+1 months", strtotime($DepartureDate)));
//             $nextMonthSegment['PreferredDepartureTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
//             $nextMonthSegment['PreferredArrivalTime'] =   $DayYear . "-" . $DayMonth . "-" . "01" . "T" . "00:00:00";
//          }
//       }
//       $preferredairlines = null;
//       if (isset($input['PreferredCarriers'])) {
//          $preferredairlines = $input['PreferredCarriers'];
//       }
//       $sources = null;

//       $request[0] = array(
//          'EndUserIp'         => $input['UserIp'],
//          'TokenId'           => $this->TokenId,
//          'JourneyType'       => intval($input['JourneyType']),
//          'PreferredAirlines' => $preferredairlines,
//          'Segments'          => $segments,
//          'Sources'           => $sources
//       );
      
     
      
//       if (!empty($nextMonthSegment)) {
//          $request[1] =  $request[0];
//          $request[1]['Segments'] = array($nextMonthSegment);
//       }
//       $TBOFlightModel = new TBOFlightModel();
//       $TTS_Result = array();
//       $custom_index = array();
//       foreach ($request as $supperkey => $requestData) {
//          $url = "$this->AirService_URL/rest/GetCalendarFare/";
//          $response = TBO_Request($url, $requestData);
//         //  if ($response['Response']['Error']['ErrorCode'] == 0) {
//         if (isset($response['Response']['Error']['ErrorCode']) && $response['Response']['Error']['ErrorCode'] == 0){
//             $ErrorCode = 0;
//             $ErrorMessage = '';
//             $results = $response['Response']['SearchResults'];
//             if ($results) {
//               foreach ($results as $key => $result) {
//                   $Calendar  =  array(
//                      'AirlineCode'         => $result['AirlineCode'],
//                      'AirlineName'         => $result['AirlineName'],
//                      'DepartureDate'       => $result['DepartureDate'],
//                      'IsLowestFareOfMonth' => $result['IsLowestFareOfMonth'],
//                      'Fare'                => $result['Fare']*$totalPassenger - 0.5 
//                   );
//                   array_push($TTS_Result, $Calendar);
//               }
//             }
//          } else {
//             $ErrorCode = $response['Response']['Error']['ErrorCode'];
//             $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
//          }
//          /*--------------Start Insert API Logs------------------*/
//          $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'CalendarFare', 'TBO', strtotime(Time::now($this->GetTimeZone)));
//          /*--------------End Insert API Logs--------------------*/
//       }

//       $tts_response = array(
//          'UserIp'        => $input['UserIp'],
//          'SearchTokenId' => $tts_search_token,
//          'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
//          'Result'        => $TTS_Result
//       );

//       return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
//   }

   public function FareRule(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];

      $request = array(
         'EndUserIp'   => $input['UserIp'],
         'TokenId'     => $this->TokenId,
         'TraceId'     => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex']
      );
      $url = "$this->AirService_URL/rest/FareRule/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';

         if ($response['Response']['FareRules']) {
            foreach ($response['Response']['FareRules'] as $key => $rules) {
               $TTS_Result[$key] = array(
                  'Airline'         => $rules['Airline'],
                  'Origin'          => $rules['Origin'],
                  'Destination'     => $rules['Destination'],
                  'FareBasisCode'   => $rules['FareBasisCode'],
                  'FareRestriction' => $rules['FareRestriction'],
                  'FareRuleDetail'  => $rules['FareRuleDetail'],
               );
            }
         }
      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareRule', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
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
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $super_admin_markup = $common_data['super_admin_markup'];
      $super_admin_discount = $common_data['super_admin_discount'];
      $super_admin_gst_state_code = $common_data['super_admin_gst_state_code'];
      $InputRequest = $common_data['InputRequest'];

      $request = array(
         'EndUserIp'   => $input['UserIp'],
         'TokenId'     => $this->TokenId,
         'TraceId'     => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
      );


      $url = "$this->AirService_URL/rest/FareQuote/";
      $response = TBO_Request($url, $request);

      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      $IsPriceChanged = false;
      $FlightDetailChangeInfo = null;
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $TraceId = $response['Response']['TraceId'];
         $IsPriceChanged = $response['Response']['IsPriceChanged'];
         $FlightDetailChangeInfo = $response['Response']['FlightDetailChangeInfo'];

         $Results = $response['Response']['Results'];
         $main_segment = array();
         foreach ($Results['Segments'] as $mkey => $Segment) {
            $ttssegment = array();
            foreach ($Segment as $segkey => $item) {
               $CabinClass = get_cabin_class_name($item['CabinClass']);
               $ttssegment[$segkey] = array(
                  'TripIndicator'     => isset($item['TripIndicator']) ? $item['TripIndicator'] : $mkey + 1,
                  'SegmentIndicator'  => $item['SegmentIndicator'],
                  'CheckInBaggage'    => $item['Baggage'],
                  'CabinBaggage'      => $item['CabinBaggage'],
                  'CabinClass'        => $CabinClass,
                  'Duration'          => $item['Duration'],
                  'TechStopPoint'     => array(),
                  'Craft'             => $item['Craft'],
                  'Airline' => array(
                     'AirlineCode'      => $item['Airline']['AirlineCode'],
                     'AirlineName'      => ucwords(strtolower($item['Airline']['AirlineName'])),
                     'FlightNumber'     => (int) $item['Airline']['FlightNumber'],
                     'FareClass'        => $item['Airline']['FareClass'],
                     'OperatingCarrier' => $item['Airline']['OperatingCarrier']
                  ),
                  'Origin' => array(
                     'AirportCode' => $item['Origin']['Airport']['AirportCode'],
                     'AirportName' => $item['Origin']['Airport']['AirportName'],
                     'CityCode'    => $item['Origin']['Airport']['CityCode'],
                     'CityName'    => $item['Origin']['Airport']['CityName'],
                     'CountryCode' => $item['Origin']['Airport']['CountryCode'],
                     'CountryName' => ucwords(strtolower($item['Origin']['Airport']['CountryName'])),
                     'Terminal'    => $item['Origin']['Airport']['Terminal'],
                     'DepartTime'  => $item['Origin']['DepTime']
                  ),
                  'Destination' => array(
                     'AirportCode' => $item['Destination']['Airport']['AirportCode'],
                     'AirportName' => $item['Destination']['Airport']['AirportName'],
                     'CityCode'    => $item['Destination']['Airport']['CityCode'],
                     'CityName'    => $item['Destination']['Airport']['CityName'],
                     'CountryCode' => $item['Destination']['Airport']['CountryCode'],
                     'CountryName' => ucwords(strtolower($item['Destination']['Airport']['CountryName'])),
                     'Terminal'    => $item['Destination']['Airport']['Terminal'],
                     'ArrivalTime' => $item['Destination']['ArrTime']
                  )
               );
            }
            $main_segment[$mkey] = $ttssegment;
         }

         if (isset($Results['FareClassification']['Type'])) {
            $FareType = get_flight_fare_type($Results['FareClassification']['Type']);
         } else {
            $FareType = get_flight_fare_type('Publish');
         }

         $FlightPrice = get_flight_fare($InputRequest, $super_admin_markup, $super_admin_discount, $Results['Fare'], $Results['FareBreakdown'], $userauthdata, $super_admin_gst_state_code, $Results['AirlineCode'], 'FareConfirmation');

         if ($Results['IsPanRequiredAtBook'] || $Results['IsPanRequiredAtTicket']) {
            $IsPanRequired = true;
         } else {
            $IsPanRequired = false;
         }
         if ($Results['IsPassportRequiredAtBook'] || $Results['IsPassportRequiredAtTicket']) {
            $IsPassportRequired = true;
         } else {
            $IsPassportRequired = false;
         }

         $TTS_Result = array(
            'ResultIndex'                 => $input['ResultIndex'],
            'IsLCC'                       => $Results['IsLCC'],
            'IsRefundable'                => $Results['IsRefundable'],
            'IsPanRequiredAtBook'         => $IsPanRequired,
            'IsPassportRequiredAtBook'    => $IsPassportRequired,
            'GSTAllowed'                  => $Results['GSTAllowed'],
            'IsGSTMandatory'              => $Results['IsGSTMandatory'],
            'AirlineRemark'               => $Results['AirlineRemark'],
            'FareType'                    => $FareType,
            'Fare'                        => $FlightPrice['Fare'],
            'FareBreakdown'               => $FlightPrice['FareBreakdown'],
            'Segments'                    => $main_segment,
            'LastTicketDate'              => $Results['LastTicketDate'],
            'TicketAdvisory'              => $Results['TicketAdvisory'],
            'ValidatingAirline'           => $Results['ValidatingAirline']
         );

         $TTS_Invoice_Amount = floatval($FlightPrice['Fare']['OfferedPrice']) + floatval($FlightPrice['Fare']['TDS']);

         $custom_index[$input['ResultIndex']] = array('TTS_Invoice_Amount' => $TTS_Invoice_Amount, 'ResultIndex' => $Results['ResultIndex'], 'IsLCC' => $Results['IsLCC'], 'IsPanRequiredAtBook' => $IsPanRequired, 'IsPassportRequiredAtBook' => $IsPassportRequired, 'GSTAllowed' => $Results['GSTAllowed'], 'IsGSTMandatory' => $Results['IsGSTMandatory'], 'AirlineCode' => $Results['AirlineCode'], 'FareBreakdown' => $Results['FareBreakdown'], 'SuperAdminFareBreakup' => $FlightPrice['SuperAdminFareBreakup'], 'WebPartnerFareBreakup' => $FlightPrice['WebPartnerFareBreakup'], 'TraceId' => $TraceId, 'Supplier' => 'TBO');
      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareConfirmation', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp'                 => $input['UserIp'],
         'SearchTokenId'          => $tts_search_token,
         'Error'                  => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'FlightDetailChangeInfo' => $FlightDetailChangeInfo,
         'IsPriceChanged'         => $IsPriceChanged,
         'Result'                 => $TTS_Result
      );

      if ($ErrorCode != 0) {
         unset($tts_response['IsPriceChanged']);
      }

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }
   
   public function SSR(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];

      $request = array(
         'EndUserIp'   => $input['UserIp'],
         'TokenId'     => $this->TokenId,
         'TraceId'     => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
      );
      
    

      $url = "$this->AirService_URL/rest/SSR/";
      $response = TBO_Request($url, $request);
      
      
      
     
      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      $custom_index = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $SSRInfo = $response['Response'];
         $SSRData = array();
         if ($IsLCC) {
             
            

            if (isset($SSRInfo['Baggage']) && $SSRInfo['Baggage']) {
               $BagaageData = array();
               foreach ($SSRInfo['Baggage'] as $key => $Baggages) {
                  foreach ($Baggages as $Baggage) {
                     $baggagekey = $Baggage['Origin'] . '-' . $Baggage['Destination'];
                     $BagaageData[$key][] = array( //closed By Hariom 8-10-2025
                        // $BagaageData[] = array(  //Added By Hariom 8-10-2025
                        'AirlineCode'  => $Baggage['AirlineCode'],
                        'FlightNumber' => $Baggage['FlightNumber'],
                        'WayType'      => get_ssr_waytype($Baggage['WayType']),
                        'Code'         => $Baggage['Code'],
                        'Description'  => $Baggage['Description'],
                        'Weight'       => $Baggage['Weight'],
                        'Price'        => $Baggage['Price'],
                        'Origin'       => $Baggage['Origin'],
                        'Destination'  => $Baggage['Destination'],
                        'connecting_flight'        =>  isset($Baggage['Text']) ? $Baggage['Text'] : 'Null',
                     );
                     $SSRData['Baggage'][$Baggage['Code']] = $Baggage;
                  }
               }
               $TTS_Result['Baggage'] = $BagaageData;
            }
            
            if (isset($SSRInfo['MealDynamic']) && $SSRInfo['MealDynamic']) {
              $MealData = array();
              foreach ($SSRInfo['MealDynamic'] as $key => $MealDynamic) {
                  foreach ($MealDynamic as $Meal) {
                     $mealkey = $Meal['Origin'] . '-' . $Meal['Destination'];
                     $MealData[$key][] = array( //closed By Hariom 8-10-2025
                        // $MealData[] = array(  //Added By Hariom 8-10-2025
                        'AirlineCode'        => $Meal['AirlineCode'],
                        'FlightNumber'       => $Meal['FlightNumber'],
                        'WayType'            => get_ssr_waytype($Meal['WayType']),
                        'Code'               => $Meal['Code'],
                        'Description'        => $Meal['Description'],
                        'AirlineDescription' => $Meal['AirlineDescription'],
                        'Quantity'           => $Meal['Quantity'],
                        'Price'              => $Meal['Price'],
                        'Origin'             => $Meal['Origin'],
                        'Destination'        => $Meal['Destination']
                     );
                     $SSRData['Meal'][$Meal['Code']] = $Meal;
                  }
              }
              $TTS_Result['Meal'] = $MealData;
            }
            
            
             if (isset($SSRInfo['Meal']) && is_array($SSRInfo['Meal'])) {
                foreach ($SSRInfo['Meal'] as $key=> $staticMeal) {
                    $code = $staticMeal['Code'];
            
                    // Skip if already exists from dynamic data
                    if (!isset($SSRData['Meal'][$code])) {
                        $MealData['static'][] = array(
                            'AirlineCode'        => isset($staticMeal['AirlineCode']) ? $staticMeal['AirlineCode'] : '',
                            'FlightNumber'       => isset($staticMeal['FlightNumber']) ? $staticMeal['FlightNumber'] : '',
                            'WayType'            => '',
                            'Code'               => $staticMeal['Code'],
                            'Description'        => $staticMeal['Description'],
                            'AirlineDescription' => '',
                            'Quantity'           => 1,
                            'Price'              => 0,
                            'Origin'             => '',
                            'Destination'        => '',
                            'Text'              =>''
                        );
                    }
                }
                
                $TTS_Result['Meal'] = $MealData;
            }
            
            
           if(isset($SSRInfo['SeatDynamic']) && $SSRInfo['SeatDynamic'])
            {
                $allSeats = [];
               
                foreach($SSRInfo['SeatDynamic'] as $newsegment)
                {
                    foreach ($newsegment['SegmentSeat'] as $segment) 
                    {
                        foreach($segment['RowSeats'] as $seg)
                        {
                            foreach ($seg['Seats'] as $seat)
                            {
                                $allSeats[] = $seat;
                            }
                        }
                    }
                }
                
                $TTS_Result['SeatDynamic'] = $allSeats; 
            }
       

            // if(isset($SSRInfo['SeatDynamic']) && $SSRInfo['SeatDynamic'])
            // {
            //     $allSeats = [];
                
            //     echo "<pre>";
            //     print_r($SSRInfo['SeatDynamic']);
            //     die;
            //      foreach ($SSRInfo['SeatDynamic'][0]['SegmentSeat'] as $segment) 
            //     {
            //         foreach($segment['RowSeats'] as $seg)
            //         {
            //             foreach ($seg['Seats'] as $seat)
            //             {
            //                 $allSeats[] = $seat;
            //             }
            //         }
            //     }
                
               
                
            //     $TTS_Result['SeatDynamic'] = $allSeats; 
            // }
            
            // if(isset($SSRInfo['SpecialServices']) && $SSRInfo['SpecialServices'])
            // {
            //     $TTS_Result['SpecialServices'] = $SSRInfo['SpecialServices']; 
            // }
            
            
             if(isset($SSRInfo['SpecialServices']) && $SSRInfo['SpecialServices'])
            {
                // $specialservice=[];
                // foreach($SSRInfo['SpecialServices']['SegmentSpecialService'])
                // {
                    
                // }
                $TTS_Result['SpecialServices'] = $SSRInfo['SpecialServices']; 
            }
            
         
            
         } else {
             
         
            if (isset($SSRInfo['Baggage']) && $SSRInfo['Baggage']) {
              $BagaageData = array();
              foreach ($SSRInfo['Baggage'] as $key => $Baggages) {
                  foreach ($Baggages as $Baggage) {
                     $baggagekey = $Baggage['Origin'] . '-' . $Baggage['Destination'];
                       $BagaageData[$key][] = array( //closed By Hariom 8-10-2025
                        // $BagaageData[] = array(  //Added By Hariom 8-10-2025
                        'AirlineCode'  => $Baggage['AirlineCode'],
                        'FlightNumber' => $Baggage['FlightNumber'],
                        'WayType'      => get_ssr_waytype($Baggage['WayType']),
                        'Code'         => $Baggage['Code'],
                        'Description'  => $Baggage['Description'],
                        'Weight'       => $Baggage['Weight'],
                        'Price'        => $Baggage['Price'],
                        'Origin'       => $Baggage['Origin'],
                        'Destination'  => $Baggage['Destination'],
                        'connecting_flight'        =>  isset($Baggage['Text']) ? $Baggage['Text'] : 'Null',
                     );
                     $SSRData['Baggage'][$Baggage['Code']] = $Baggage;
                  }
              }
              $TTS_Result['Baggage'] = $BagaageData;
            }
            
            
            
             if (isset($SSRInfo['MealDynamic']) && $SSRInfo['MealDynamic']) {
              $MealData = array();
              foreach ($SSRInfo['MealDynamic'] as $key => $MealDynamic) {
                  foreach ($MealDynamic as $Meal) {
                     $mealkey = $Meal['Origin'] . '-' . $Meal['Destination'];
                     $MealData[$key][] = array( //closed By Hariom 8-10-2025
                        // $MealData[] = array(  //Added By Hariom 8-10-2025
                        'AirlineCode'        => $Meal['AirlineCode'],
                        'FlightNumber'       => $Meal['FlightNumber'],
                        'WayType'            => get_ssr_waytype($Meal['WayType']),
                        'Code'               => $Meal['Code'],
                        'Description'        => $Meal['Description'],
                        'AirlineDescription' => $Meal['AirlineDescription'],
                        'Quantity'           => $Meal['Quantity'],
                        'Price'              => $Meal['Price'],
                        'Origin'             => $Meal['Origin'],
                        'Destination'        => $Meal['Destination']
                     );
                     $SSRData['Meal'][$Meal['Code']] = $Meal;
                  }
              }
              $TTS_Result['Meal'] = @$MealData;
            }
            
            
            if (isset($SSRInfo['Meal']) && is_array($SSRInfo['Meal'])) {
                foreach ($SSRInfo['Meal'] as $key=> $staticMeal) {
                    $code = $staticMeal['Code'];
            
                    // Skip if already exists from dynamic data
                    if (!isset($SSRData['Meal'][$code])) {
                        $MealData['static'][] = array(
                            'AirlineCode'        => isset($staticMeal['AirlineCode']) ? $staticMeal['AirlineCode'] : '',
                            'FlightNumber'       => isset($staticMeal['FlightNumber']) ? $staticMeal['FlightNumber'] : '',
                            'WayType'            => '',
                            'Code'               => $staticMeal['Code'],
                            'Description'        => $staticMeal['Description'],
                            'AirlineDescription' => '',
                            'Quantity'           => 1,
                            'Price'              => 0,
                            'Origin'             => '',
                            'Destination'        => '',
                            'Text'              =>''
                        );
                    }
                }
                
                $TTS_Result['Meal'] = @$MealData;
            }
            
              if(isset($SSRInfo['SeatDynamic']) && $SSRInfo['SeatDynamic'])
            {
                $allSeats = [];
               
                foreach($SSRInfo['SeatDynamic'] as $newsegment)
                {
                    foreach ($newsegment['SegmentSeat'] as $segment) 
                    {
                        foreach($segment['RowSeats'] as $seg)
                        {
                            foreach ($seg['Seats'] as $seat)
                            {
                                $allSeats[] = $seat;
                            }
                        }
                    }
                }
                
                $TTS_Result['SeatDynamic'] = $allSeats; 
            }
            
         
            // if(isset($SSRInfo['SeatDynamic']) && $SSRInfo['SeatDynamic'])
            // {
               
            //     $allSeats = [];
                
            //      foreach ($SSRInfo['SeatDynamic'][0]['SegmentSeat'] as $segment) 
            //     {
            //         foreach($segment['RowSeats'] as $seg)
            //         {
            //             foreach ($seg['Seats'] as $seat)
            //             {
            //                 $allSeats[] = $seat;
            //             }
            //         }
            //     }
                
            //     $TTS_Result['SeatDynamic'] = $allSeats; 
            // }
            
            if(isset($SSRInfo['SpecialServices']) && $SSRInfo['SpecialServices'])
            {
                $TTS_Result['SpecialServices'] = $SSRInfo['SpecialServices']; 
            }
    
         }
     
         
         
         if (count($TTS_Result) == 0) {
            $ErrorCode = 2;
            $ErrorMessage = 'data not available';
         }
         $custom_index[$input['ResultIndex']] = $SSRData;
      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'SSR', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTS_Result
      );

      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
   }



   public function Book(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];
      $SSRData = $common_data['SSRData'];
      $flight_offline = $common_data['FlightOffline'];
      $account_log_lastid = $common_data['SaveData']['account_log_lastid'];
      $booking_lastid = $common_data['SaveData']['booking_lastid'];

      $FareBreakdowns = array();
      if ($common_data['indexinfo']['FareBreakdown']) {
         foreach ($common_data['indexinfo']['FareBreakdown'] as $PaxFare) {
            $FareBreakdowns[$PaxFare['PassengerType']] = $PaxFare;
         }
      }

      $Passengers = array();
      if ($input['Passengers']) {
         foreach ($input['Passengers'] as $key => $value) {

            $pax_type = $value['PaxType'];
            $Passengers[$key] = array(
               'Title'                    => $value['Title'],
               'FirstName'                => $value['FirstName'],
               'LastName'                 => $value['LastName'],
               'PaxType'                  => $value['PaxType'],
               'DateOfBirth'              => $value['DateOfBirth'],
               'Gender'                   => $value['Gender'],
               'PassportNo'               => $value['PassportNo'],
               'PassportExpiry'           => $value['PassportExpiry'],
               'Nationality'              => isset($value['Nationality']) ? $value['Nationality'] : null,
               'PAN'                      => isset($value['PAN']) ? $value['PAN'] : null,
               'AddressLine1'             => $value['AddressLine1'],
               'AddressLine2'             => $value['AddressLine2'],
               'City'                     => $value['City'],
               'CountryCode'              => $value['CountryCode'],
               'CountryName'              => $value['CountryName'],
               'ContactNo'                => $value['ContactNo'],
               'Email'                    => $value['Email'],
               'IsLeadPax'                => $value['IsLeadPax'],
               'FFAirline'                => $value['FFAirline'],
               'FFNumber'                 => $value['FFNumber'],
               
                  'Baggage'                  => @$value['Baggage'], //Added By Hariom
                  'MealDynamic'              => @$value['MealDynamic'], //Added By Hariom
                  'SeatDynamic'              => @$value['SeatDynamic'], //Added By Hariom
                  'SpecialServices'          => @$value['SpecialServices'], //Added By Hariom
                
                
                //   'PaxBaggage'           => @$value['Baggage'], //Added By Hariom
                //   'PaxMeal'              => @$value['MealDynamic'], //Added By Hariom
                //   'PaxSeat'              => @$value['SeatDynamic'], //Added By Hariom
                //   'SpecialServices'          => @$value['SpecialServices'], //Added By Hariom
                
               'GSTCompanyAddress'        => $value['GSTCompanyAddress'],
               'GSTCompanyContactNumber'  => $value['GSTCompanyContactNumber'],
               'GSTCompanyName'           => $value['GSTCompanyName'],
               'GSTNumber'                => $value['GSTNumber'],
               'GSTCompanyEmail'          => $value['GSTCompanyEmail'],
               'Fare' => array(
                  'BaseFare'              => $FareBreakdowns[$pax_type]['BaseFare'],
                  'Tax'                   => $FareBreakdowns[$pax_type]['Tax'],
                  'TransactionFee'        => 0,
                  'YQTax'                 => $FareBreakdowns[$pax_type]['YQTax'],
                  'AdditionalTxnFeeOfrd'  => $FareBreakdowns[$pax_type]['AdditionalTxnFeeOfrd'],
                  'AdditionalTxnFeePub'   => $FareBreakdowns[$pax_type]['AdditionalTxnFeePub'],
                  'AirTransFee'           => 0,
               ),
            );
         }
      }

      $request = array(
         'EndUserIp'  => $input['UserIp'],
         'TokenId'    => $this->TokenId,
         'TraceId'    => $TraceId,
         'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
         'Passengers' => $Passengers, //Added By Hariom
      );
      

     
      $service = 'Book';
      $url = "$this->AirBooking_URL/rest/Book/";

      $response = TBO_Request($url, $request);
      $PNR=null;
      // print_r($response);
      $TBOFlightModel = new TBOFlightModel();
      $custom_index = array();
      $getbooking_detail = array();
      if ($response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $result = $response['Response']['Response'];

         $PNR = $result['PNR'];
         $BookingId = $result['BookingId'];
         $source = $result['FlightItinerary']['Source'];
         $getbooking_detail = $result;

         //  echo "HELLLLLO";
         //  print_r($BookingId);
         //  print_r($source);

      } else {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      }
      if ($ErrorCode == 0) {
         $db_token_id = $TBOFlightModel->fetch_auth_token(strtotime(Time::today($this->GetTimeZone)), $this->Mode);

         TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         $booking_data = $TBOFlightModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId'], 'id' => $booking_lastid]);
         $tts_response = ConvertBookingDetail($input, $booking_data);

         $tts_response['db_token_id'] = $db_token_id;
         $tts_response['Result']['Source'] = $source;
         $tts_response['Result']['supplierBookingId'] = $BookingId;

         //  print_r($tts_response);die;
      } else {
         if ($getbooking_detail) {
            TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         } else {
            $booking_status = 'Processing';
            $booking_update_data = array('booking_status' => $booking_status);
            $TBOFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $TBOFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], $booking_update_data);  /* change By harish */
         }

         $tts_response = array(
            'UserIp'        => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result'        => array()
         );
      }
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $service, 'TBO', strtotime(Time::now($this->GetTimeZone)),$PNR);
      /*--------------End Insert API Logs--------------------*/
      return $tts_response;
   }
   
   public function Ticket(array $input, array $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $common_data['indexinfo']['TraceId'];
      $IsLCC = $common_data['indexinfo']['IsLCC'];
      $account_log_lastid = $common_data['SaveData']['account_log_lastid'];
      $booking_lastid = $common_data['SaveData']['booking_lastid'];
      $TBOFlightModel = new TBOFlightModel();
      if ($IsLCC) {


         $SSRData = $common_data['SSRData'];
         $flight_offline = $common_data['FlightOffline'];
         $FareBreakdowns = array();
         if ($common_data['indexinfo']['FareBreakdown']) {
            foreach ($common_data['indexinfo']['FareBreakdown'] as $PaxFare) {
               $FareBreakdowns[$PaxFare['PassengerType']] = $PaxFare;
            }
         }

         $Passengers = array();
         if ($input['Passengers']) {
            foreach ($input['Passengers'] as $key => $value) {
                
               $pax_type = $value['PaxType'];
               $Passengers[$key] = array(
                  'Title'                    => $value['Title'],
                  'FirstName'                => $value['FirstName'],
                  'LastName'                 => $value['LastName'],
                  'PaxType'                  => $value['PaxType'],
                  'DateOfBirth'              => $value['DateOfBirth'],
                  'Gender'                   => $value['Gender'],
                  'PassportNo'               => $value['PassportNo'],
                  'PassportExpiry'           => $value['PassportExpiry'],
                  'Nationality'              => isset($value['Nationality']) ? $value['Nationality'] : null,
                  'PAN'                      => isset($value['PAN']) ? $value['PAN'] : null,
                  'AddressLine1'             => $value['AddressLine1'],
                  'AddressLine2'             => $value['AddressLine2'],
                  'City'                     => $value['City'],
                  'CountryCode'              => $value['CountryCode'],
                  'CountryName'              => $value['CountryName'],
                  'ContactNo'                => $value['ContactNo'],
                  'Email'                    => $value['Email'],
                  'IsLeadPax'                => $value['IsLeadPax'],
                  'FFAirline'                => $value['FFAirline'],
                  'FFNumber'                 => $value['FFNumber'],
                //   'Baggage'                  => @$value['Baggage'], //Added By Hariom
                //   'MealDynamic'              => @$value['MealDynamic'], //Added By Hariom
                //   'SeatDynamic'              => @$value['SeatDynamic'], //Added By Hariom
                //   'SpecialServices'          => @$value['SpecialServices'], //Added By Hariom
                  'GSTCompanyAddress'        => @$value['GSTCompanyAddress'],
                  'GSTCompanyContactNumber'  => @$value['GSTCompanyContactNumber'],
                  'GSTCompanyName'           => @$value['GSTCompanyName'],
                  'GSTNumber'                => @$value['GSTNumber'],
                  'GSTCompanyEmail'          => @$value['GSTCompanyEmail'],
                  'Fare' => array(
                     'BaseFare'              => $FareBreakdowns[$pax_type]['BaseFare'],
                     'Tax'                   => $FareBreakdowns[$pax_type]['Tax'],
                     'TransactionFee'        => 0,
                     'YQTax'                 => $FareBreakdowns[$pax_type]['YQTax'],
                     'AdditionalTxnFeeOfrd'  => $FareBreakdowns[$pax_type]['AdditionalTxnFeeOfrd'],
                     'AdditionalTxnFeePub'   => $FareBreakdowns[$pax_type]['AdditionalTxnFeePub'],
                     'AirTransFee'           => 0,
                  ),
               );
               
                //Added By Hariom
                if ($pax_type != 3) {
                    $Passengers[$key]['Baggage']         = @$value['Baggage'];
                    $Passengers[$key]['MealDynamic']     = @$value['MealDynamic'];
                    $Passengers[$key]['SeatDynamic']     = @$value['SeatDynamic'];
                    $Passengers[$key]['SpecialServices'] = @$value['SpecialServices'];
                    
                    // $Passengers[$key]['PaxBaggage']         = @$value['Baggage'];
                    // $Passengers[$key]['PaxMeal']     = @$value['MealDynamic'];
                    // $Passengers[$key]['PaxSeat']     = @$value['SeatDynamic'];
                    // $Passengers[$key]['SpecialServices'] = @$value['SpecialServices'];
                }
        
            }
         }

         $request = array(
            'EndUserIp'  => $input['UserIp'],
            'TokenId'    => $this->TokenId,
            'TraceId'    => $TraceId,
            'ResultIndex' => $common_data['indexinfo']['ResultIndex'],
            'Passengers' => $Passengers,
         );
         
      } else {
          
          
         $PNR =  $input['PNR'];
         $BookData  =   $TBOFlightModel->getDataAPI("common_flight_log", array("tts_search_token" => $tts_search_token, "service" => "Book", "api_supplier" => "TBO", "web_partner_id" => $userauthdata['web_partner_id']), array(), 1, 0, "response",$PNR);
         $response =  json_decode($BookData['response'], true);
         $result = $response['Response']['Response'];
         $request =  array();
         if (isset($result['BookingId'])) {
            $BookingId = $result['BookingId'];
            $request = array(
               'EndUserIp' => $input['UserIp'],
               'TokenId'   => $this->TokenId,
               'TraceId'   => $TraceId,
               'BookingId' => $BookingId,
               'PNR'       => $PNR,
            );
            
           
         }
      }

      $service = 'Ticket';
      $url = "$this->AirBooking_URL/rest/Ticket/";
      $response =  array();
      if ($request) {
         $response = TBO_Request($url, $request);
      }
      
      
      $custom_index = array();
      $getbooking_detail = array();
      if (isset($response['Response']['Error']['ErrorCode']) && $response['Response']['Error']['ErrorCode'] == 0) {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $result = $response['Response']['Response'];

         $PNR = $result['PNR'];
         $BookingId = $result['BookingId'];
         $getbooking_detail = $result;
      } else {
         $ErrorCode = @$response['Response']['Error']['ErrorCode'];
         $ErrorMessage = @$response['Response']['Error']['ErrorMessage'];
      }
      if ($ErrorCode == 0) {
         TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         $booking_data = $TBOFlightModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId'], 'id' => $booking_lastid]);
         $tts_response = ConvertBookingDetail($input, $booking_data);
      } else {
         if ($getbooking_detail) {
            TBOFlight::UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid);
         } else {
            $booking_status = 'Processing';
            $booking_update_data = array('booking_status' => $booking_status);
            $TBOFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $TBOFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], $booking_update_data);  /* change By harish */
         }

         $tts_response = array(
            'UserIp'        => $input['UserIp'],
            'SearchTokenId' => $tts_search_token,
            'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
            'Result'        => array()
         );
         
         
      }
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, $service, 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $tts_response;
   }



   public function UpdateBookingData($getbooking_detail, $booking_lastid, $account_log_lastid)
   {
      $TBOFlightModel = new TBOFlightModel();
      $Passengers = $getbooking_detail['FlightItinerary']['Passenger'];
      $origin = $getbooking_detail['FlightItinerary']['Origin'];
      $destination = $getbooking_detail['FlightItinerary']['Destination'];
      $pnr = $getbooking_detail['PNR'];
      $airline_code = $getbooking_detail['FlightItinerary']['AirlineCode'];
      $flight_number = $getbooking_detail['FlightItinerary']['Segments'][0]['Airline']['FlightNumber'];
      $departure_date = explode("T", $getbooking_detail['FlightItinerary']['Segments'][0]['Origin']['DepTime'])[0];
      $fare_rule = $getbooking_detail['FlightItinerary']['FareRules'];
      $segments = $getbooking_detail['FlightItinerary']['Segments'];

      $airline_pnr = array();
      $jkey  = 1;
      if ($segments) {
         foreach ($segments as $segment) {
            $jkey = isset($segment['TripIndicator']) ? $segment['TripIndicator'] : $jkey;
            $skey = $segment['SegmentIndicator'];
            $airline_pnr[$jkey][$skey] = $segment['AirlinePNR'];
            $jkey  =  $jkey + 1;
         }
      }

      $pax_update_data = array();

      if (isset($getbooking_detail['FlightItinerary']['Passenger'][0]['Ticket']) && $getbooking_detail['FlightItinerary']['Passenger'][0]['Ticket']['TicketNumber']) {
         $booking_status = 'Confirmed';
      } else if ($getbooking_detail['PNR']) {
         $booking_status = 'Hold';
      } else {
         $booking_status = 'Processing';
      }
      foreach ($Passengers as $key => $Passenger) {
         $ticket_id = "";
         $ticket_number = "";
         $validating_airline = "";
         if (isset($Passenger['Ticket'])) {
            $ticket_id = $Passenger['Ticket']['TicketId'];
            $ticket_number = $Passenger['Ticket']['TicketNumber'];
            $validating_airline = $Passenger['Ticket']['ValidatingAirline'];
         }
         $pax_update_data = array(
            /* 'flight_booking_id' => $booking_lastid,
                                             'first_name'        => trim($Passenger['FirstName']),
                                             'pax_type'          => get_paxtype_name($Passenger['PaxType']), */
            'pax_id'            => $Passenger['PaxId'],
            'ticket_id'         => $ticket_id,
            'ticket_number'     => $ticket_number,
            "validating_airline" => $validating_airline,
            "booking_status" => $booking_status,
            "meal"=>!empty($Passenger['MealDynamic']) ? json_encode($Passenger['MealDynamic']) : null, //Added By Hariom,
            "seat"=>!empty($Passenger['SeatDynamic']) ? json_encode($Passenger['SeatDynamic']) : null, //Added By Hariom,
            "SpecialServices"=>!empty($Passenger['SpecialServices']) ? json_encode($Passenger['SpecialServices']) : null, //Added By Hariom,
         );
         $TBOFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid, "first_name" => trim($Passenger['FirstName']), 'pax_type' => get_paxtype_name($Passenger['PaxType'])], $pax_update_data);
      }
      /*    $TBOFlightModel->updatePaxData('flight_booking_travelers',$pax_update_data,'first_name'); */
      $booking_update_data = array(
         'is_price_changed'   => $getbooking_detail['IsPriceChanged'],
         'is_time_changed'    => $getbooking_detail['IsTimeChanged'],
         'supplier_booking_id' => $getbooking_detail['BookingId'],
         'booking_status'     => $booking_status,
         'fare_rule'          => json_encode($fare_rule),
         'pnr'                => $pnr,
         'airline_pnr'       => json_encode($airline_pnr)
      );
      $TBOFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);

      $PaxName = $Passengers[0]['FirstName'] . ' ' . $Passengers[0]['LastName'] . ' X ' . count($Passengers);
      $Sector = $origin . '-' . $destination;
      $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date, 'AirlineString' => $airline_code . $flight_number, 'TicketNo' => $pnr);
      $account_update_data = array('service_log'    => json_encode($service_log));
      /*  $TBOFlightModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data); */
   }

   private function GDSTicket(array $input, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $input['TraceId'];
      $BookingId = $input['BookingId'];
      $PNR = $input['PNR'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId'   => $this->TokenId,
         'TraceId'   => $TraceId,
         'BookingId' => $BookingId,
         'PNR'       => $PNR
      );
      $url = "$this->AirBooking_URL/rest/Ticket/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Ticket', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;
   }

   public function GetBookingDetails(array $input, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $TraceId = $input['TraceId'];
      $BookingId = $input['BookingId'];
      $PNR = $input['PNR'];

      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId'   => $this->TokenId,
         'BookingId' => $BookingId,
         'PNR'       => $PNR,
         'TraceId'   => $TraceId
      );
      $url = "$this->AirBooking_URL/rest/GetBookingDetails/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetBookingDetails', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      return $response;
   }


   public function ReleasePNRRequest(array $input, array $userauthdata)
   {
      $request = array(
         'EndUserIp' => $input['UserIp'],
         'TokenId'   => $this->TokenId,
         'BookingId' => $BookingId,
         'Source'    => $Source,
      );

      $url = "$this->AirBooking_URL/rest/ReleasePNRRequest/";
      $response = TBO_Request($url, $request);

      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'ReleasePNRRequest', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $response;
   }
   public function SendCancelRequest(array $input, $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $Sectors = array();
      $TicketId = array();
      $PassengerDetails = $common_data['PassengerDetails'];
      $PassengerDetails = array_column($PassengerDetails, 'ticket_id', 'id');
      if (isset($input['PaxId'])) {
         $nopax = count($PassengerDetails);
         if ($nopax >= count($input['PaxId'])) {
         } else {
            $message = "Invalid Pax Count.Total Passanger $nopax";
            api_custom_message(400, $message, false);
         }
         foreach ($input['PaxId'] as $paxid) {
            if (isset($PassengerDetails[$paxid])) {
               array_push($TicketId, $PassengerDetails[$paxid]);
            } else {
               $message = "Invalid PaxId Detail";
               api_custom_message(400, $message, false);
            }
         }
      }

      $SegmentsDetails = json_decode($common_data['segments'], true);

      if ($input['RequestType'] == 'FullCancellation') {
         $RequestType = 1;
      }
      if ($input['RequestType'] == 'PartialCancellation') {
         $RequestType = 2;
      }
      $BookingId = $common_data['supplier_booking_id'];
      $CancellationType = 2;

      $request = array(
         'EndUserIp'        => $input['UserIp'],
         'TokenId'          => $this->TokenId,
         'BookingId'        => $BookingId,
         'RequestType'      => $RequestType,
         'CancellationType' => $CancellationType,
         'Remarks'          => $input['Remark'],
      );

      if ($input['RequestType'] == 'PartialCancellation') {
         $request['Sectors'] = $input['Sectors'];
         $request['TicketId'] = $TicketId;
      }
      $url = "$this->AirBooking_URL/rest/SendChangeRequest/";
      $response = TBO_Request($url, $request);
      $TBOFlightModel = new TBOFlightModel();
      $TTS_Result = array();
      if (isset($response['Response']['Error'])) {
         $ErrorCode = $response['Response']['Error']['ErrorCode'];
         $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
      } else {
         $ErrorCode = 0;
         $ErrorMessage = '';
         $ticket_info = $response['Response']['TicketCRInfo'];

         if ($ticket_info) {
            $tts_update_data = array();
            $PassengerDetails = array_flip($PassengerDetails);
            foreach ($ticket_info as $item) {
               $TTSPaxId = $PassengerDetails[$item['TicketId']];
               $canceldata = array(
                  'PaxId' => $TTSPaxId,
                  'Remarks' => $item['Remarks']
               );
               array_push($TTS_Result, $canceldata);
               $update_data = array(
                  'id' => $TTSPaxId,
                  'booking_status' => "Cancelled",
                  'flight_booking_id' => $input['BookingId'],
                  'supplier_data' => json_encode($item),
               );
               array_push($tts_update_data, $update_data);
            }
            $BookingStatus =  "";
            $TBOFlightModel->updatePaxData('flight_booking_travelers', $tts_update_data, 'id');
            $BookingTravelersCount           =  $TBOFlightModel->getDataResultType("flight_booking_travelers", array("flight_booking_id" => $input['BookingId']), "id,flight_booking_id,booking_status");
            $PaxBookingStatus  =  array_unique(array_column($BookingTravelersCount, 'booking_status'));
            $PaxBookingStatusCount  =  count($PaxBookingStatus);
            if ($PaxBookingStatusCount == 1) {

               if (isset($PaxBookingStatus[0]) &&  $PaxBookingStatus[0] == "Cancelled") {
                  $BookingStatus  =  "Cancelled";
               }
            } else {
               $BookingStatus  =  "PartialCancelled";
            }
            if ($BookingStatus != "") {
               $TBOFlightModel->updateUserData('flight_booking_list', array("id" => $input['BookingId']), array("booking_status" => $BookingStatus));
            }
         } else {
            $ErrorCode = 1;
            $ErrorMessage = 'Cancellation failed from supplier side.';
         }
      }
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'SendChangeRequest', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTS_Result
      );
      return $tts_response;
   }
   public function GetChangeRequestStatus(array $input, $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $paxId  =   $input['PaxId'];
      $BookingPax  =   $common_data['PassengerDetails'];
      $ApiRequest  =  array();
      $requestpax  =  array();
      $TTSResponse  =  array();
      $changeRequestIds  =  array();
      $ErrorCode = 0;
      $ErrorMessage = "";
      $TBOFlightModel = new TBOFlightModel();
      foreach ($BookingPax as $paxData) {
         if (in_array($paxData['id'], $paxId) && $paxData['supplier_data'] != null) {
            $requestpax[$paxData['id']] =  $paxData;
            array_push($requestpax, $paxData);
            $supplier_data =  json_decode($paxData['supplier_data'], true);
            if (isset($changeRequestIds[$supplier_data['ChangeRequestId']])) {
               array_push($changeRequestIds[$supplier_data['ChangeRequestId']], $paxData['id']);
            } else {
               $changeRequestIds[$supplier_data['ChangeRequestId']] =  array($paxData['id']);
            }
         }
      }
      foreach ($changeRequestIds as $changeRequestIdkey => $changeRequestId) {
         $request = array(
            'EndUserIp'        => $input['UserIp'],
            'TokenId'          => $this->TokenId,
            'ChangeRequestId'  => $changeRequestIdkey
         );
         $url = "$this->AirBooking_URL/rest/GetChangeRequestStatus/";
         $response = TBO_Request($url, $request);

         /*--------------Start Insert API Logs------------------*/
         $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetChangeRequestStatus', 'TBO', strtotime(Time::now($this->GetTimeZone)));
         /*--------------End Insert API Logs--------------------*/
         if (isset($response['Response']['Error'])) {
            $ErrorCode = $response['Response']['Error']['ErrorCode'];
            $ErrorMessage = $response['Response']['Error']['ErrorMessage'];
         } else {
            $RequestedPaxIds  =   $changeRequestIds[$changeRequestIdkey];
            $status  = get_cancellation_status($response['Response']['ChangeRequestStatus']);
            $ErrorCode = 0;
            $ErrorMessage = "";
            foreach ($RequestedPaxIds as $RequestedPaxId) {
               $statusresponse  =  array(
                  "PaxId" => $RequestedPaxId,
                  "Status" => $status,
                  "RefundedAmount" => isset($response['Response']['RefundedAmount']) ? $response['Response']['RefundedAmount'] : "",
                  "CancellationCharge" => isset($response['Response']['CancellationCharge']) ? $response['Response']['CancellationCharge'] : "",
                  "ServiceTaxOnRAF" => isset($response['Response']['ServiceTaxOnRAF']) ? $response['Response']['ServiceTaxOnRAF'] : "",
               );
               array_push($TTSResponse, $statusresponse);
            }
         }
      }
      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
         'Result'        => $TTSResponse
      );
      return $tts_response;
   }
   public function GetCancellationCharges(array $input, $common_data, array $userauthdata)
   {
      $tts_search_token = $input['SearchTokenId'];
      $BookingId = $common_data['supplier_booking_id'];
      $BookingMode = 5;
      if ($input['RequestType'] == 'FullCancellation') {
         $RequestType = 1;
      }
      if ($input['RequestType'] == 'PartialCancellation') {
         $RequestType = 2;
      }
      $request = array(
         'EndUserIp'        => $input['UserIp'],
         'TokenId'          => $this->TokenId,
         'BookingId'        => $BookingId,
         'RequestType'      => $RequestType,
         'BookingMode'      => $BookingMode
      );

      $url = "$this->AirBooking_URL/rest/GetCancellationCharges/";
      $response = TBO_Request($url, $request);
      unset($response['Response']['ResponseStatus']);
      unset($response['Response']['TraceId']);
      $tts_response = array(
         'UserIp'        => $input['UserIp'],
         'SearchTokenId' => $tts_search_token,
         'Error'         => array("ErrorCode" => 0, "ErrorMessage" => ""),
         'Result'        => $response['Response']
      );
      /*--------------Start Insert API Logs------------------*/
      $TBOFlightModel = new TBOFlightModel();
      $TBOFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'GetCancellationCharges', 'TBO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      return $tts_response;
   }
}
