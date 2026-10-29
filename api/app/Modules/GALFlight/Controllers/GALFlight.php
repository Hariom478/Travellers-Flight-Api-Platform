<?php

namespace Modules\GALFlight\Controllers;

use App\Controllers\BaseController;
use App\Modules\GALFlight\Models\GALFlightModel;
use Modules\GALFlight\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;
use App\Modules\Airservice\Models\RestModel;
 

class GALFlight extends BaseController
{
    protected $airline_list=[];
    protected $airport_list=[];

    public function __construct()
    {
       helper('Modules\GALFlight\Helpers\flight');
       $this->GetTimeZone=app_timezone();

       $apiconfig= new APIConfig;
       $credential=$apiconfig->travelport_credential;

       $this->Credentials = $credential['UserName'].':'.$credential['Password'];
       $this->TargetBranch = $credential['TargetBranch'];
       $this->Provider = $credential['Provider'];
       $this->AuthorizedBy = $credential['AuthorizedBy'];
       $this->PPC = $credential['PPC'];
       $this->Provider_Type=$credential['Provider_Type'];
       $this->TraceId='';
       $this->AirService_URL = $credential['AirService_URL'];
       $this->UniversalService_URL = $credential['UniversalService_URL'];
       $this->Mode = $credential['Mode'];
    }

    public function Search(array $input,$tts_search_token,array $userauthdata)
    {
          $this->TraceId=$tts_search_token;
          $journeytype = $input["JourneyType"];
          $userip = $input["UserIp"];
          $no_adult = $input["Adult"];
          $no_child = $input["Child"];
          $no_infants = $input["Infant"];
          $class = Cabinclass($input["CabinClass"]);
          $directflight=false;
          if(isset($input["DirectFlight"]))
          {
              $directflight=$input["DirectFlight"];
          }
          $preferredairlines='';
          if(isset($input["PreferredCarriers"]))
          {
              $preferredairlines=$input["PreferredCarriers"];
          }
          $airsegments = $input["AirSegments"];
          $isdomestic = $input["IsDomestic"];

          $AirPricingModifiers=''; $set_Provider='';
          $set_Provider='<com:Provider Code="' . $this->Provider . '" />';
          $AirPricingModifiers='<air:AirPricingModifiers FaresIndicator="AllFares" ETicketability="Required"/>';

          $classxml = "";
          if($class !== "") {
                $classxml = '<air:AirLegModifiers>
                                <air:PreferredCabins>
                                <com:CabinClass Type="' . $class . '" />
                                </air:PreferredCabins>
                             </air:AirLegModifiers>';
          }

          $SearchAirLeg = "";
          $SplitTicketingSearch="";
          $SolutionResult ='SolutionResult="false"';
          if($isdomestic==true && $journeytype=='2')
          {
            $SplitTicketingSearch ='<air:SplitTicketingSearch/>';
          }

          foreach($airsegments as $airsegment) {
              $PreferredTime = explode('T', $airsegment['PreferredTime']);
              $SearchAirLeg .= '<air:SearchAirLeg>
                                  <air:SearchOrigin>
                                          <com:CityOrAirport Code="' . $airsegment['Origin'] . '" PreferCity="true" />
                                  </air:SearchOrigin>
                                  <air:SearchDestination>
                                          <com:CityOrAirport Code="' . $airsegment['Destination'] . '" PreferCity="true" />
                                  </air:SearchDestination>
                                  <air:SearchDepTime PreferredTime="' . $PreferredTime[0] . '"/>
                                  ' . $classxml . '
                          </air:SearchAirLeg>';
          }

          $adultxml = "";
          for ($i = 1; $i <= $no_adult; $i++) {
              $adultxml .= '<com:SearchPassenger Code="ADT" BookingTravelerRef="'.$this->TraceId.'_ADT_'.$i.'" />';
          }

          $childxml = "";
          for ($j = 1; $j <= $no_child; $j++) {
              $childxml .= '<com:SearchPassenger Code="CNN" Age="10" BookingTravelerRef="'.$this->TraceId.'_CHD_'.$j.'" />';
          }

          $infantxml = "";
          for ($k = 1; $k <= $no_infants; $k++) {
              $infantxml .= '<com:SearchPassenger Code="INF" Age="1" PricePTCOnly="true" BookingTravelerRef="'.$this->TraceId.'_INF_'.$k.'" />';
          }

          $preferred_airlinexml = "";
          if ($preferredairlines !== "" || $preferredairlines !== NULL) {
              if (is_array($preferredairlines)) {
                  $PreferredCarriers = "";
                  foreach ($preferredairlines as $preferredairline) {
                      $PreferredCarriers .= '<com:Carrier Code="' . $preferredairline . '" />';
                  }
                  $preferred_airlinexml = '<air:PreferredCarriers>
                                              ' . $PreferredCarriers . '
                                            </air:PreferredCarriers>';
              }
          }

          $nonstopxml = "";
          if ($directflight == TRUE) {
              $nonstopxml = '<air:FlightType NonStopDirects="true" />';
          }

          $request='<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                    <soapenv:Header/>
                    <soapenv:Body>
                            <air:LowFareSearchReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" AuthorizedBy="' . $this->AuthorizedBy . '" TargetBranch="' . $this->TargetBranch . '" TraceId="' . $this->TraceId . '" '.$SolutionResult.'>
                                    <com:BillingPointOfSaleInfo OriginApplication="UAPI" />
                                      ' . $SearchAirLeg . '
                                        <air:AirSearchModifiers IncludeFlightDetails="true">
                                              <air:PreferredProviders>
                                                      '.$set_Provider.'
                                              </air:PreferredProviders>
                                              ' . $preferred_airlinexml . '
                                              ' . $nonstopxml . '
                                        </air:AirSearchModifiers>
                                          ' . $SplitTicketingSearch . '
                                          ' . $adultxml . '
                                          ' . $infantxml . ' 
                                          ' . $childxml . '
                                          ' . $AirPricingModifiers.'
                            </air:LowFareSearchReq>
                    </soapenv:Body>
          </soapenv:Envelope>';

          return array('Supplier'=>'TRAVELPORT','Credentials'=>$this->Credentials,'URL'=>$this->AirService_URL,'Request'=>$request);

    }

    public function ConvertSearchResponse($input,$response,$convert_response,$custom_index,$common_data)
    {
       // echo $response; die();

        // increase allowed memory size
        ini_set('memory_limit', '512M');

        $this->airline_list=$common_data['airline_list'];
        $this->airport_list=$common_data['airport_list'];

        if($response)
        {
        $response_array = xml2array($response);
        if(!isset($response_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']) && !isset($response_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
	      {

          $result = $response_array['SOAP:Envelope']['SOAP:Body']['air:LowFareSearchRsp'];
          $CurrencyType=$result['CurrencyType'];
          $FlightDetailsList = $result['air:FlightDetailsList']['air:FlightDetails'];
          $AirSegmentList = $result['air:AirSegmentList']['air:AirSegment'];
          $FareInfoList = $result['air:FareInfoList']['air:FareInfo'];
          if(isset($result['air:RouteList']['air:Route']['air:Leg']))
          {
            if(isset($result['air:RouteList']['air:Route']['air:Leg'][0]))
            {
              $RouteList = $result['air:RouteList']['air:Route']['air:Leg'];
            } else {
              $RouteList = array($result['air:RouteList']['air:Route']['air:Leg']);
            }
          } else {
            $RouteList=array_column($result['air:RouteList']['air:Route'],'air:Leg');
          }
          $AirPricePointList = $result['air:AirPricePointList'];
          if(isset($AirPricePointList['air:AirPricePoint'][0]))
          {
            $AirPricePoints=$AirPricePointList['air:AirPricePoint'];
          } else {
            $AirPricePoints=array($AirPricePointList['air:AirPricePoint']);
          }	
          $BrandList=array();
          if(isset($result['air:BrandList']))
          {
            $BrandList=$result['air:BrandList']['air:Brand'];
          }
      
          $super_admin_markup=$common_data['super_admin_markup'];
          $super_admin_discount=$common_data['super_admin_discount'];
          $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
          $userauthdata=$common_data['userauthdata'];
          
          $AirSegmentListKeys=array_column($AirSegmentList,'Key');
          $FlightDetailsListKeys=array_column($FlightDetailsList,'Key');
          $FareInfoListKeys=array_column($FareInfoList,'Key');
          $BrandListKeys=array_column($BrandList,'Key');
          $RouteListKeys=array_column($RouteList,'Key');

          $CommonInfo=array();
          $CommonInfo['FareInfoListKeys']=$FareInfoListKeys;
          $CommonInfo['FareInfoList']=$FareInfoList;
          $CommonInfo['CurrencyType']=$CurrencyType;

           /* Start Main Air Price loop */
           foreach($AirPricePoints as $mainkey=> $AirPricePoint)
           {
                 
                
                /* Start AirPricingInfo  First Pax */
                  $AirPricingInfo=array();
                  if(isset($AirPricePoint['air:AirPricingInfo'][0]))
                  {
                    $AirPricingInfo=$AirPricePoint['air:AirPricingInfo'];
                  } else {
                    $AirPricingInfo=array($AirPricePoint['air:AirPricingInfo']);
                  }
                  $FirstPax_data=array();
                  $FirstPax_data=$AirPricingInfo[0]; $FlightOptionsList=array(); $TTSFlightOptionsList=array();
                  if(isset($FirstPax_data['air:FlightOptionsList']['air:FlightOption'][0]))
                  {
                    $FlightOptionsList=$FirstPax_data['air:FlightOptionsList']['air:FlightOption'];
                  } else {
                    $FlightOptionsList=array($FirstPax_data['air:FlightOptionsList']['air:FlightOption']);
                  }
                  if($input['JourneyType']==2 && $input['IsDomestic']=='true')
                  {
                      /*-----------Start Round Trip Domestic -----------*/
                      if(count($FlightOptionsList)==1)
                      {
                          $LegIndexKey=array_search($FlightOptionsList[0]['LegRef'],$RouteListKeys);
                          $LegRefDetail=$RouteList[$LegIndexKey];  // find for journey
                          $TTSFlightOptionsList[$LegRefDetail['Group']]=$FlightOptionsList;
                          if($TTSFlightOptionsList)
                          {
                              foreach($TTSFlightOptionsList as $jkey=>$FlightOption)
                              {
                                    if(isset($FlightOption[0]['air:Option'][0]))
                                    {
                                      $AirOptions=$FlightOption[0]['air:Option'];
                                    } else {
                                      $AirOptions=array($FlightOption[0]['air:Option']);
                                    }  
                                    foreach($AirOptions as $subjkey=>$AirOption)
                                    {
                                        $FinalSegment=array(); $FinalBaggageInfo=array(); $AirSegmentData="";
                                        $TravelTime=$AirOption['TravelTime'];
                                        $AirBookingInfo=array();
                                        if(isset($AirOption['air:BookingInfo']['SegmentRef']))
                                        {
                                            $AirBookingInfo=array($AirOption['air:BookingInfo']);
                                        } else {
                                            $AirBookingInfo=$AirOption['air:BookingInfo'];
                                        }
                                        $connectionarray=array(); $ConnSegmentIndex=array();
                                        if(isset($AirOption['air:Connection']))
                                        {
                                          if(isset($AirOption['air:Connection'][0]))
                                          {
                                              $connectionarray=$AirOption['air:Connection'];
                                          } else {
                                              $connectionarray=array($AirOption['air:Connection']);
                                          }
                                          $ConnSegmentIndex = array_column($connectionarray, 'SegmentIndex');
                                        }
                                        $TTSSegmentInfo=array(); $ttsbaggageinfo=array(); $CabinClass='';
                                        foreach($AirBookingInfo as $segkey=>$BookingInfo)
                                        {
                                            $CabinClass=$BookingInfo['CabinClass'];
                                            $FareInfoIndexKey=array_search($BookingInfo['FareInfoRef'],$FareInfoListKeys);
                                            $GetFareInfo=$FareInfoList[$FareInfoIndexKey];
                                            if(isset($GetFareInfo['air:BaggageAllowance']))
                                            {
                                              if(isset($GetFareInfo['air:BaggageAllowance']['air:NumberOfPieces']))
                                              {
                                                $Baggage=$GetFareInfo['air:BaggageAllowance']['air:NumberOfPieces']['value'].' Unit(s)';
                                              } else {
                                                $unit=baggage_unit_check($GetFareInfo['air:BaggageAllowance']['air:MaxWeight']['Unit']);
                                                $Baggage=$GetFareInfo['air:BaggageAllowance']['air:MaxWeight']['Value'].' '.$unit;
                                              }
                                              $CabinBaggage="7 KG";
                                            } else {
                                              $Baggage=null;
                                              $CabinBaggage="7 KG";
                                            }
                                            $SegmentIndexKey=array_search($BookingInfo['SegmentRef'],$AirSegmentListKeys);
                                            $Sagments=$AirSegmentList[$SegmentIndexKey];
                                            /*-------- Create Air Segment Data------------------*/
                                            $airconnection="";
                                            if(!empty($ConnSegmentIndex))
                                            {
                                              if (in_array($segkey, $ConnSegmentIndex))
                                              {
                                                $airconnection="<air:Connection/>";
                                              }
                                            }
                                            $Sagments['ProviderCode']=$Sagments['air:AirAvailInfo']['ProviderCode'];
                                            $airsegmentkeyvalue=""; $airsegmentmiddelvalue="";
                                            foreach($Sagments as $fieldkey=>$fieldvalue)
                                            {   
                                                  if(is_array($fieldvalue))
                                                  {
                                                      if($fieldkey=="air:AirAvailInfo")
                                                      {     
                                                        $airsegmentmiddelvalue.='<air:AirAvailInfo ProviderCode="'.$fieldvalue['ProviderCode'].'"/>';
                                                      }
                                                      if($fieldkey=="air:CodeshareInfo")
                                                      {
                                                        $OperatingFlightNumber='';
                                                        if(isset($fieldvalue['OperatingFlightNumber']))
                                                        {
                                                          $OperatingFlightNumber='OperatingFlightNumber="'.$fieldvalue['OperatingFlightNumber'].'"';
                                                        }
                                                        $OperatingCarrier='';
                                                        if(isset($fieldvalue['OperatingCarrier']))
                                                        {
                                                          $OperatingCarrier=$fieldvalue['OperatingCarrier'];
                                                          $airsegmentmiddelvalue.='<air:CodeshareInfo OperatingCarrier="'.$OperatingCarrier.'" '.$OperatingFlightNumber.'>'.$fieldvalue['value'].'</air:CodeshareInfo>';
                                                        } else {
                                                          $airsegmentmiddelvalue.='<air:CodeshareInfo>'.$fieldvalue['value'].'</air:CodeshareInfo>';
                                                        }
                                                      }
                                                  } else {
                                                    $airsegmentkeyvalue.=$fieldkey ."=". '"'.$fieldvalue.'"' . " ";
                                                  }
                                            }
                                            $AirSegmentData.="<air:AirSegment ".$airsegmentkeyvalue.">".$airsegmentmiddelvalue."  ".$airconnection."</air:AirSegment>";
                                            /*-------- End Air Segment Data------------------*/
                                            $Sector=$Sagments['Origin'].'-'.$Sagments['Destination'];
                                            $ttsbaggageinfo[$segkey]=array(
                                                                                  'NoOfSeatAvailable' => intval($BookingInfo['BookingCount']),
                                                                                  'FareClass'         => $BookingInfo['BookingCode'],
                                                                                  'Sector'            => $Sector,
                                                                                  'CheckIn'           => $Baggage,
                                                                                  'Cabin'             => $CabinBaggage
                                                                                );
                                            $FlightDetailsRefs=array();
                                            if(isset($Sagments['air:FlightDetailsRef']['Key'])) 
                                            {
                                              $FlightDetailsRefs = array($Sagments['air:FlightDetailsRef']);
                                            } else {
                                              $FlightDetailsRefs = $Sagments['air:FlightDetailsRef'];
                                            }
                                            $TechStopPoint=array();$FD=array();  $depart_terminal=''; $arrival_terminal='';
                                            foreach($FlightDetailsRefs as $key_fd=>$FlightDetails)
                                            {
                                                  $FDIndexKey=array_search($FlightDetails['Key'],$FlightDetailsListKeys);
                                                  $FD=$FlightDetailsList[$FDIndexKey];
                                                  if($key_fd!=0) 
                                                  {
                                                      array_push($TechStopPoint,$FD['Origin']);
                                                  }
                                                  if($key_fd==0)
                                                  {
                                                    if(isset($FD['OriginTerminal'])) {
                                                      $depart_terminal = $FD['OriginTerminal'];
                                                    }
                                                  }
                                            }
                                            if(isset($FD['DestinationTerminal'])) {
                                              $arrival_terminal = $FD['DestinationTerminal'];
                                            }
                                            if (isset($Sagments['air:CodeshareInfo'])) {
                                                $OperatingCarrier=check_attribute_exist($Sagments['air:CodeshareInfo'],'OperatingCarrier');
                                            }  else {
                                                $OperatingCarrier = $Sagments['Carrier'];
                                            }
      
                                            $air_data=GALFlight::GetAirData($Sagments['Carrier'],$Sagments['Origin'],$Sagments['Destination']);
                                            $dep_airport_data=$air_data['dep_airport_data'];
                                            $arr_airport_data=$air_data['arr_airport_data'];
      
                                            $ttssegment=array(
                                                              'TripIndicator'     => $jkey+1,
                                                              'SegmentIndicator'  => $segkey+1,
                                                              'Duration'          => $Sagments['FlightTime'],
                                                              'TechStopPoint'     => $TechStopPoint,
                                                              'Craft'             => $Sagments['Equipment'],
                                                              'Airline'=>array(
                                                                                'AirlineCode'      => $Sagments['Carrier'],
                                                                                'AirlineName'      => $air_data['airline_name'],
                                                                                'FlightNumber'     => (int) $Sagments['FlightNumber'],
                                                                                'FareClass'        => $BookingInfo['BookingCode'],
                                                                                'OperatingCarrier' => $OperatingCarrier
                                                                            ),
                                                              'Origin'=>array(
                                                                                'AirportCode' => $Sagments['Origin'],
                                                                                'AirportName' => $dep_airport_data['name'],
                                                                                'CityCode'    => $dep_airport_data['city_code'],
                                                                                'CityName'    => $dep_airport_data['city_name'],
                                                                                'CountryCode' => $dep_airport_data['country_code'],
                                                                                'CountryName' => ucwords(strtolower($dep_airport_data['country_name'])),
                                                                                'Terminal'    => $depart_terminal,
                                                                                'DepartTime'  => datetime_format($Sagments['DepartureTime'])
                                                                            ),
                                                              'Destination'=>array(
                                                                                'AirportCode' => $Sagments['Destination'],
                                                                                'AirportName' => $arr_airport_data['name'],
                                                                                'CityCode'    => $arr_airport_data['city_code'],
                                                                                'CityName'    => $arr_airport_data['city_name'],
                                                                                'CountryCode' => $arr_airport_data['country_code'],
                                                                                'CountryName' => ucwords(strtolower($arr_airport_data['country_name'])),
                                                                                'Terminal'    => $arrival_terminal,
                                                                                'ArrivalTime' => datetime_format($Sagments['ArrivalTime'])
                                                                            )
                                                            );                                               
                                            array_push($TTSSegmentInfo,$ttssegment);
                                        }
                                        array_push($FinalSegment,$TTSSegmentInfo);
                                        array_push($FinalBaggageInfo,$ttsbaggageinfo);

                                        $rtfirst_segment=array(); $rtlast_segment=array();
                                        $rtfirst_segment =  current($TTSSegmentInfo);
                                        $rtlast_segment  =  end($TTSSegmentInfo);
                                        $rtdep_time=substr(str_replace("T", "-",$rtfirst_segment['Origin']['DepartTime']),0, -3);
                                        $rtarr_time=substr(str_replace("T", "-",$rtlast_segment['Destination']['ArrivalTime']),0, -3);
                                        $rtcustom_key=trim($rtfirst_segment['Airline']['AirlineCode']).'-'.trim($rtfirst_segment['Airline']['FlightNumber']).'-'.trim($rtdep_time).'-'.trim($rtarr_time);
                                        $FlightPrice=Gal_get_flight_fare($input,$CommonInfo,$super_admin_markup,$super_admin_discount,$AirPricePoint,$userauthdata,$super_admin_gst_state_code);
                                        $TRAVELPORTflightDeal = getAirlineDeal($input,$common_data['super_admin_flight_deal'],$FinalSegment,$FlightPrice,"TRAVELPORT",$CabinClass);
                                        $FlightPrice     =  Gal_get_flight_fare_with_discount($super_admin_discount,$FlightPrice,$TRAVELPORTflightDeal);
                                        $rtprevious_fare=array();
                                        $FareId='GAL'.$mainkey.$subjkey;
          
                                        $custom_index[$FareId] = array('FareRule' =>$FlightPrice['farerulekey'],'Supplier'=>'TRAVELPORT','AirSegment'=>htmlentities($AirSegmentData),'CurrencyType'=>$CurrencyType,'PublishedPrice'=>$FlightPrice['Fare']['PublishedPrice']);
    
                                        if (isset($FirstPax_data['Refundable']) && $FirstPax_data['Refundable'] == true) {
                                          $IsRefundable = true;
                                        } else {
                                          $IsRefundable = false;
                                        }  
                                        $AirlineRemark='';
                                        $FareType=get_flight_fare_type('Publish');

                                    
                                        $FareList=array(
                                                          'FareId'           => $FareId,
                                                          'IsRefundable'     => $IsRefundable,
                                                          'AirlineRemark'    => $AirlineRemark,
                                                          'FareType'         => $FareType,
                                                          'SeatBaggage'      => $FinalBaggageInfo,
                                                          'CabinClass'       => $CabinClass,
                                                          'Fare'             => $FlightPrice['Fare'],
                                                          'FareBreakdown'    => $FlightPrice['FareBreakdown'],
                                                          'PublishedPrice'   => $FlightPrice['Fare']['PublishedPrice'],
                                                          'OfferedPrice'     => $FlightPrice['Fare']['OfferedPrice'],
                                                          'Source'           => 'TRAVELPORT',
                                                      );
      
                                          if(!isset($convert_response[$jkey][$rtcustom_key]['Segments']))
                                          {
                                            $convert_response[$jkey][$rtcustom_key]['Segments']=array($TTSSegmentInfo);
                                          } 
                                          if(isset($convert_response[$jkey][$rtcustom_key]['FareList']))
                                          {
                                            $rtprevious_fare=$convert_response[$jkey][$rtcustom_key]['FareList'];
                                          } 
                                          array_push($rtprevious_fare,$FareList);
                                          $convert_response[$jkey][$rtcustom_key]['FareList']=$rtprevious_fare;
                                        
                                    }
                              }
                          }
                      }
                    /*-----------End Round Trip Domestic -----------*/
                  } else {
                      $TTSFlightOptionsList=$FlightOptionsList;
                      $FinalSegment=array();  $CabinClass=''; $journeygroup=array();
                      if($TTSFlightOptionsList)
                      {
                        foreach($TTSFlightOptionsList as $FlightOption)
                        {
                            $LegIndexKey=array_search($FlightOption['LegRef'],$RouteListKeys);
                            $LegRefDetail=$RouteList[$LegIndexKey];  // find for journey
                            $AirOptions=array();
                            if(isset($FlightOption['air:Option'][0]))
                            {
                              $AirOptions=$FlightOption['air:Option'];
                            } else {
                              $AirOptions=array($FlightOption['air:Option']);
                            }   
                            array_push($journeygroup,$AirOptions);
                        }
                      }
                      $resultcombinations=array();
                      $resultcombinations=GenerateCombinations($journeygroup);             
                      if($resultcombinations)
                      { 
                          foreach($resultcombinations as $comkey=>$combination)
                          {     
                                $FinalSegment=array(); $FinalBaggageInfo=array(); $AirSegmentData="";
                                foreach($combination as $jkey=>$AirOption)
                                {
                                      $TravelTime=$AirOption['TravelTime'];
                                      $AirBookingInfo=array();
                                      if(isset($AirOption['air:BookingInfo']['SegmentRef']))
                                      {
                                          $AirBookingInfo=array($AirOption['air:BookingInfo']);
                                      } else {
                                          $AirBookingInfo=$AirOption['air:BookingInfo'];
                                      }
    
                                      $connectionarray=array(); $ConnSegmentIndex=array();
                                      if(isset($AirOption['air:Connection']))
                                      {
                                        if(isset($AirOption['air:Connection'][0]))
                                        {
                                            $connectionarray=$AirOption['air:Connection'];
                                        } else {
                                            $connectionarray=array($AirOption['air:Connection']);
                                        }
                                        $ConnSegmentIndex = array_column($connectionarray, 'SegmentIndex');
                                      }
                                
                                      $TTSSegmentInfo=array(); $ttsbaggageinfo=array(); $CabinClass='';
                                      foreach($AirBookingInfo as $segkey=>$BookingInfo)
                                      {
                                          $CabinClass=$BookingInfo['CabinClass'];
                                          $FareInfoIndexKey=array_search($BookingInfo['FareInfoRef'],$FareInfoListKeys);
                                          $GetFareInfo=$FareInfoList[$FareInfoIndexKey];
                                          if(isset($GetFareInfo['air:BaggageAllowance']))
                                          {
                                            if(isset($GetFareInfo['air:BaggageAllowance']['air:NumberOfPieces']))
                                            {
                                              $Baggage=$GetFareInfo['air:BaggageAllowance']['air:NumberOfPieces']['value'].' Unit(s)';
                                            } else {
                                              $unit=baggage_unit_check($GetFareInfo['air:BaggageAllowance']['air:MaxWeight']['Unit']);
                                              $Baggage=$GetFareInfo['air:BaggageAllowance']['air:MaxWeight']['Value'].' '.$unit;
                                            }
                                            $CabinBaggage="7 KG";
                                          } else {
                                            $Baggage=null;
                                            $CabinBaggage="7 KG";
                                          }
                                        
                                          $SegmentIndexKey=array_search($BookingInfo['SegmentRef'],$AirSegmentListKeys);
                                          $Sagments=$AirSegmentList[$SegmentIndexKey];
      
                                          /*-------- Create Air Segment Data------------------*/
                                          $airconnection="";
                                          if(!empty($ConnSegmentIndex))
                                          {
                                            if (in_array($segkey, $ConnSegmentIndex))
                                            {
                                              $airconnection="<air:Connection/>";
                                            }
                                          }
    
                                          $Sagments['ProviderCode']=$Sagments['air:AirAvailInfo']['ProviderCode'];
                                          $airsegmentkeyvalue=""; $airsegmentmiddelvalue="";
                                          foreach($Sagments as $fieldkey=>$fieldvalue)
                                          {   
                                                if(is_array($fieldvalue))
                                                {
                                                    if($fieldkey=="air:AirAvailInfo")
                                                    {     
                                                      $airsegmentmiddelvalue.='<air:AirAvailInfo ProviderCode="'.$fieldvalue['ProviderCode'].'"/>';
                                                    }
                                                    if($fieldkey=="air:CodeshareInfo")
                                                    {
                                                      $OperatingFlightNumber='';
                                                      if(isset($fieldvalue['OperatingFlightNumber']))
                                                      {
                                                        $OperatingFlightNumber='OperatingFlightNumber="'.$fieldvalue['OperatingFlightNumber'].'"';
                                                      }
                                                      $OperatingCarrier='';
                                                      if(isset($fieldvalue['OperatingCarrier']))
                                                      {
                                                        $OperatingCarrier=$fieldvalue['OperatingCarrier'];
                                                        $airsegmentmiddelvalue.='<air:CodeshareInfo OperatingCarrier="'.$OperatingCarrier.'" '.$OperatingFlightNumber.'>'.$fieldvalue['value'].'</air:CodeshareInfo>';
                                                      } else {
                                                        $airsegmentmiddelvalue.='<air:CodeshareInfo>'.$fieldvalue['value'].'</air:CodeshareInfo>';
                                                      }
                                                    }
                                                } else {
                                                  $airsegmentkeyvalue.=$fieldkey ."=". '"'.$fieldvalue.'"' . " ";
                                                }
                                          }
                                          $AirSegmentData.="<air:AirSegment ".$airsegmentkeyvalue.">".$airsegmentmiddelvalue."  ".$airconnection."</air:AirSegment>";
                                          /*-------- End Air Segment Data------------------*/
                                          
                                          $Sector=$Sagments['Origin'].'-'.$Sagments['Destination'];
                                          $ttsbaggageinfo[$segkey]=array(
                                                                                'NoOfSeatAvailable' => intval($BookingInfo['BookingCount']),
                                                                                'FareClass'         => $BookingInfo['BookingCode'],
                                                                                'Sector'            => $Sector,
                                                                                'CheckIn'           => $Baggage,
                                                                                'Cabin'             => $CabinBaggage
                                                                              );
    
                                          $FlightDetailsRefs=array();
                                          if(isset($Sagments['air:FlightDetailsRef']['Key'])) 
                                          {
                                            $FlightDetailsRefs = array($Sagments['air:FlightDetailsRef']);
                                          } else {
                                            $FlightDetailsRefs = $Sagments['air:FlightDetailsRef'];
                                          }
                                          $TechStopPoint=array();$FD=array();  $depart_terminal=''; $arrival_terminal='';
                                          foreach($FlightDetailsRefs as $key_fd=>$FlightDetails)
                                          {
                                                $FDIndexKey=array_search($FlightDetails['Key'],$FlightDetailsListKeys);
                                                $FD=$FlightDetailsList[$FDIndexKey];
                                                if($key_fd!=0) 
                                                {
                                                    array_push($TechStopPoint,$FD['Origin']);
                                                }
                                                if($key_fd==0)
                                                {
                                                  if(isset($FD['OriginTerminal'])) {
                                                    $depart_terminal = $FD['OriginTerminal'];
                                                  }
                                                }
                                          }
                                          if(isset($FD['DestinationTerminal'])) {
                                            $arrival_terminal = $FD['DestinationTerminal'];
                                          }
                                          if (isset($Sagments['air:CodeshareInfo'])) {
                                              $OperatingCarrier=check_attribute_exist($Sagments['air:CodeshareInfo'],'OperatingCarrier');
                                          }  else {
                                              $OperatingCarrier = $Sagments['Carrier'];
                                          }
    
                                          $air_data=GALFlight::GetAirData($Sagments['Carrier'],$Sagments['Origin'],$Sagments['Destination']);
                                          $dep_airport_data=$air_data['dep_airport_data'];
                                          $arr_airport_data=$air_data['arr_airport_data'];
    
                                          $ttssegment=array(
                                                            'TripIndicator'     => $jkey+1,
                                                            'SegmentIndicator'  => $segkey+1,
                                                            'Duration'          => $Sagments['FlightTime'],
                                                            'TechStopPoint'     => $TechStopPoint,
                                                            'Craft'             => $Sagments['Equipment'],
                                                            'Airline'=>array(
                                                                              'AirlineCode'      => $Sagments['Carrier'],
                                                                              'AirlineName'      => $air_data['airline_name'],
                                                                              'FlightNumber'     => (int) $Sagments['FlightNumber'],
                                                                              'FareClass'        => $BookingInfo['BookingCode'],
                                                                              'OperatingCarrier' => $OperatingCarrier
                                                                          ),
                                                            'Origin'=>array(
                                                                              'AirportCode' => $Sagments['Origin'],
                                                                              'AirportName' => $dep_airport_data['name'],
                                                                              'CityCode'    => $dep_airport_data['city_code'],
                                                                              'CityName'    => $dep_airport_data['city_name'],
                                                                              'CountryCode' => $dep_airport_data['country_code'],
                                                                              'CountryName' => ucwords(strtolower($dep_airport_data['country_name'])),
                                                                              'Terminal'    => $depart_terminal,
                                                                              'DepartTime'  => datetime_format($Sagments['DepartureTime'])
                                                                          ),
                                                            'Destination'=>array(
                                                                              'AirportCode' => $Sagments['Destination'],
                                                                              'AirportName' => $arr_airport_data['name'],
                                                                              'CityCode'    => $arr_airport_data['city_code'],
                                                                              'CityName'    => $arr_airport_data['city_name'],
                                                                              'CountryCode' => $arr_airport_data['country_code'],
                                                                              'CountryName' => ucwords(strtolower($arr_airport_data['country_name'])),
                                                                              'Terminal'    => $arrival_terminal,
                                                                              'ArrivalTime' => datetime_format($Sagments['ArrivalTime'])
                                                                          )
                                                          );                                               
                                          array_push($TTSSegmentInfo,$ttssegment);
                                      }
                                      array_push($FinalSegment,$TTSSegmentInfo);
                                      array_push($FinalBaggageInfo,$ttsbaggageinfo);
                                }
                                $first_segment=array(); $last_segment=array();
                                if(count($FinalSegment)==1)
                                {
                                  $first_segment =  current($FinalSegment[0]);
                                  $last_segment  =  end($FinalSegment[0]);
                                } else {
                                  $first_segment =  current($FinalSegment);
                                  $first_segment =  current($first_segment);
    
                                  $last_segment  =  end($FinalSegment);
                                  $last_segment  =  end($last_segment);
                                }
  
                                $dep_time=substr(str_replace("T", "-",$first_segment['Origin']['DepartTime']),0, -3);
                                $arr_time=substr(str_replace("T", "-",$last_segment['Destination']['ArrivalTime']),0, -3);
                                $custom_key=trim($first_segment['Airline']['AirlineCode']).'-'.trim($first_segment['Airline']['FlightNumber']).'-'.trim($dep_time).'-'.trim($arr_time);

                                $FlightPrice=Gal_get_flight_fare($input,$CommonInfo,$super_admin_markup,$super_admin_discount,$AirPricePoint,$userauthdata,$super_admin_gst_state_code);
                                $TRAVELPORTflightDeal = getAirlineDeal($input,$common_data['super_admin_flight_deal'],$FinalSegment,$FlightPrice,"TRAVELPORT",$CabinClass);
                                $FlightPrice     =  Gal_get_flight_fare_with_discount($super_admin_discount,$FlightPrice,$TRAVELPORTflightDeal);
                                $previous_fare=array();
                                $FareId='GAL'.$mainkey.$comkey;
    
                                $custom_index[$FareId] = array('FareRule' =>$FlightPrice['farerulekey'],'Supplier'=>'TRAVELPORT','AirSegment'=>htmlentities($AirSegmentData),'CurrencyType'=>$CurrencyType,'PublishedPrice'=>$FlightPrice['Fare']['PublishedPrice']);
    
                                if (isset($FirstPax_data['Refundable']) && $FirstPax_data['Refundable'] == true) {
                                  $IsRefundable = true;
                                } else {
                                  $IsRefundable = false;
                                }  
                                $AirlineRemark='';
                                $FareType=get_flight_fare_type('Publish');
                            
                                $FareList=array(
                                                  'FareId'           => $FareId,
                                                  'IsRefundable'     => $IsRefundable,
                                                  'AirlineRemark'    => $AirlineRemark,
                                                  'FareType'         => $FareType,
                                                  'SeatBaggage'      => $FinalBaggageInfo,
                                                  'CabinClass'       => $CabinClass,
                                                  'Fare'             => $FlightPrice['Fare'],
                                                  'FareBreakdown'    => $FlightPrice['FareBreakdown'],
                                                  'PublishedPrice'   => $FlightPrice['Fare']['PublishedPrice'],
                                                  'OfferedPrice'     => $FlightPrice['Fare']['OfferedPrice'],
                                                  'Source'           => 'TRAVELPORT',
                                              );  
        
                                if(!isset($convert_response[0][$custom_key]['Segments']))
                                {
                                  $convert_response[0][$custom_key]['Segments']=$FinalSegment;
                                }
                                if(isset($convert_response[0][$custom_key]['FareList']))
                                {
                                  $previous_fare=$convert_response[0][$custom_key]['FareList'];
                                } 
                                array_push($previous_fare,$FareList);
                                $convert_response[0][$custom_key]['FareList']=$previous_fare;
                          }
                      }
                  }
               /* End AirPricingInfo First Pax */         
           }
        }
        return array('convert_response'=>$convert_response,'airline_list'=>$this->airline_list,'airport_list'=>$this->airport_list,'custom_index'=>$custom_index);
      } else {
        return array();
      }
    }

    private function GetAirData($airline_code,$origin_code,$destination_code)
    {
        /* ------------------Start  Get Airline And Airport Information----------------*/
        $RestModel=new RestModel();
        if(array_key_exists($airline_code,$this->airline_list))
        { 
                $airline_name=$this->airline_list[$airline_code];
        } else {
             $db_airline_info=$RestModel->get_airline_name($airline_code);
            if($db_airline_info)
            {
                $airline_name=ucwords(strtolower($db_airline_info['airline_name']));
                $this->airline_list[$airline_code]=$airline_name;
            }else {
                $airline_name='';
            }
        }

        if(array_key_exists($origin_code,$this->airport_list))
        { 
            $dep_airport_data=$this->airport_list[$origin_code];
        } else {
            $db_airport_info=$RestModel->get_airport($origin_code);
            if($db_airport_info)
            {
            $dep_airport_data=$db_airport_info;
            $this->airport_list[$origin_code]=$db_airport_info;
            }else {
            $dep_airport_data=array('code'=>$origin_code,'name'=>'','city_code'=>'','city_name'=>'','country_name'=>'','country_code'=>'');
            }
        }

        if(array_key_exists($destination_code,$this->airport_list))
        { 
            $arr_airport_data=$this->airport_list[$destination_code];
        } else {
            $db_airport_info=$RestModel->get_airport($destination_code);
            if($db_airport_info)
            {
            $arr_airport_data=$db_airport_info;
            $this->airport_list[$destination_code]=$db_airport_info;
            }else {
            $arr_airport_data=array('code'=>$destination_code,'name'=>'','city_code'=>'','city_name'=>'','country_name'=>'','country_code'=>'');
            }
        }
        return array('airline_code'=>$airline_code,'airline_name'=>$airline_name,'dep_airport_data'=>$dep_airport_data,'arr_airport_data'=>$arr_airport_data);
        /* ------------------End Get Airline And Airport Information----------------*/
    }

    public function FareRule(array $input,array $common_data,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;
      $fare_rulexml="";
      $FareRuleList=$common_data['indexinfo']['FareRule'];
      if($FareRuleList)
      {
        foreach($FareRuleList as $item)
        {
            $fare_rulexml.='<air:FareRuleKey FareInfoRef="'.$item['FareRuleKey']['FareInfoRef'].'" ProviderCode="'.$item['FareRuleKey']['ProviderCode'].'">'.$item['FareRuleKey']['value'].'</air:FareRuleKey>';
        }
      }
      $request='<?xml version="1.0" encoding="utf-8"?>
		         <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
							<soapenv:Header/>
							<soapenv:Body>
								<air:AirFareRulesReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" AuthorizedBy="'.$this->AuthorizedBy.'" TargetBranch="'.$this->TargetBranch.'" TraceId="'.$this->TraceId.'">
								<com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
									'.$fare_rulexml.'
								 </air:AirFareRulesReq>
							 </soapenv:Body>
				 </soapenv:Envelope>';
      $response= GAL_Request($this->AirService_URL,$request);
     // $response=file_get_contents(FCPATH."writable/gal-farerule-1.xml");
      $fare_rule_array = xml2array($response);

      $GALFlightModel= new GALFlightModel();
      $TTS_Result=array();
      $custom_index = array();
	    $ErrorCode=0;
	    $ErrorMessage="";
	    $fare_rule_data_array=array();
      if(isset($fare_rule_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      {
        if(isset($fare_rule_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['detail']['common_v51_0:ErrorInfo']))
        {
          $ErrorMessage=$fare_rule_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['detail']['common_v51_0:ErrorInfo']['common_v51_0:Description']['value'];
          $ErrorCode=1;
        } else {
          $ErrorMessage=$fare_rule_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['detail']['ErrorInfo']['Description']['value'];
          $ErrorCode=2;
        }
      } else {
          $fare_rule_data=$fare_rule_array['SOAP:Envelope']['SOAP:Body']['air:AirFareRulesRsp'];
          if(isset($fare_rule_data['air:FareRule'][0]))
          {
            $farerule=$fare_rule_data['air:FareRule'];
          } else {
            $farerule=array($fare_rule_data['air:FareRule']);
          }
          foreach($farerule as $key_main=>$farerules)
          {
              if(isset($farerules['air:FareRuleLong']['value']))
              {
                  $farerules['air:FareRuleLong']=array($farerules['air:FareRuleLong']);
              }
            $FareRuleDetail="";
            $farecount=count($farerules['air:FareRuleLong']);
            for($s=0;$s<$farecount;$s++) {
              $farecategory=$farerules['air:FareRuleLong'][$s]['Category'];
              $FareRuleDetail.=$farecategory.'.'.$farerules['air:FareRuleLong'][$s]['value']."<br/><br/>";
            }
      
           $TTS_Result[$key_main]=array(
                                  'Airline'=>$FareRuleList[$key_main]['Airline'],
                                  'Origin'=>$FareRuleList[$key_main]['Origin'],
                                  'Destination'=>$FareRuleList[$key_main]['Destination'],
                                  'FareBasisCode'=>$FareRuleList[$key_main]['FareBasis'],
                                  'FareRuleDetail'=>'<h3>'.GetPaxType($FareRuleList[$key_main]['PassengerTypeCode']).'</h3> <br/> '.$FareRuleDetail,
                               );
          }					
      }
      /*--------------Start Insert API Logs------------------*/
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareRule', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
      $tts_response = array(
                              'UserIp'        => $input['UserIp'],
                              'SearchTokenId' => $tts_search_token,
                              'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                              'Result'        => $TTS_Result
                            );
      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
    }

    public function FareQuote(array $input,array $common_data,array $userauthdata)
    {
      ini_set('memory_limit', '64M');

      $SearchPublishedPrice=$common_data['indexinfo']['PublishedPrice'];
      $CurrencyType=$common_data['indexinfo']['CurrencyType'];
      
      $SearchInputRequest=$common_data['InputRequest'];
      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;

      $super_admin_markup=$common_data['super_admin_markup'];
      $super_admin_discount=$common_data['super_admin_discount'];
      $super_admin_gst_state_code=$common_data['super_admin_gst_state_code'];
  

      $adt_count=$SearchInputRequest['Adult'];
      $chd_count=$SearchInputRequest['Child'];
      $inf_count=$SearchInputRequest['Infant'];

      $adtpax=""; 
      for($adt=1;$adt<=$adt_count;$adt++)
      {
        $adtpax.='<com:SearchPassenger Code="ADT" BookingTravelerRef="'.$this->TraceId.'_ADT_'.$adt.'" />';
      }
      $chdpax="";
      for($chd=1;$chd<=$chd_count;$chd++)
      {			 
        $chdpax.='<com:SearchPassenger Age="10" Code="CNN" BookingTravelerRef="'.$this->TraceId.'_CHD_'.$chd.'"  />';
      }
      $infpax="";
      for($inf=1;$inf<=$inf_count;$inf++)
      {
        $infpax.='<com:SearchPassenger Age="1" PricePTCOnly="true" Code="INF" BookingTravelerRef="'.$this->TraceId.'_INF_'.$inf.'"  />';
      }

      $AirSegment=htmlspecialchars_decode($common_data['indexinfo']['AirSegment']);
 
      $request='<?xml version="1.0" encoding="utf-8"?>
                  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                  <soapenv:Header/>
                  <soapenv:Body>								
                    <air:AirPriceReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" AuthorizedBy="'.$this->AuthorizedBy.'" TargetBranch="'.$this->TargetBranch.'" TraceId="'.$this->TraceId.'" CheckOBFees="All" FareRuleType="long">
                      <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
                        <air:AirItinerary>
                        '.$AirSegment.'
                        </air:AirItinerary>
                        <air:AirPricingModifiers FaresIndicator="AllFares" ETicketability="Required"/>
                        '.$adtpax.'
                        '.$infpax.'
                        '.$chdpax.'
                      <air:AirPricingCommand></air:AirPricingCommand>
                      </air:AirPriceReq>
                    </soapenv:Body>
              </soapenv:Envelope>';
     $response= GAL_Request($this->AirService_URL,$request);    
     // $response=file_get_contents(FCPATH."writable/gal_price-response.xml");
      $airprice_array=xml2array($response);

      $GALFlightModel= new GALFlightModel();
      $TTS_Result=array();
      $custom_index = array();
	    $ErrorCode=0;
	    $ErrorMessage="";
      $IsPriceChanged=false;
      if(isset($airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']))
      {
        $AirPriceRsp=$airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp'];
        $AirSegment=$AirPriceRsp['air:AirItinerary']['air:AirSegment'];
        $AirPriceResult=$AirPriceRsp['air:AirPriceResult'];

        $CommonInfo=array();
        $CommonInfo['CurrencyType']=$CurrencyType;

        $FinalAirSegment=array();
        if(isset($AirSegment['Key']))
        {
          $FinalAirSegment=array($AirSegment);
        } else {
          $FinalAirSegment=$AirSegment;
        }
        if(isset($AirPriceResult['air:AirPricingSolution'][0]))
        {
          $AirPricing=$AirPriceResult['air:AirPricingSolution'][0];
        } else {
          $AirPricing=$AirPriceResult['air:AirPricingSolution'];
        }

   

        $AirPricingInfo=array();
        if(isset($AirPricing['air:AirPricingInfo'][0]))
        {
          $AirPricingInfo=$AirPricing['air:AirPricingInfo'];
        } else {
          $AirPricingInfo=array($AirPricing['air:AirPricingInfo']);
        }
        $FirstPax_data=array();
        $FirstPax_data=$AirPricingInfo[0];

        $CheckInBaggage=null; $CabinBaggage=null;
        $BaggageAllowanceInfo=array();   $CarryOnAllowanceInfo=array();
        if(isset($FirstPax_data['air:BaggageAllowances']))
        {
           if(isset($FirstPax_data['air:BaggageAllowances']['air:BaggageAllowanceInfo']['TravelerType']))
           {
              $BaggageAllowanceInfo=array($FirstPax_data['air:BaggageAllowances']['air:BaggageAllowanceInfo']);
           } else{
              $BaggageAllowanceInfo=$FirstPax_data['air:BaggageAllowances']['air:BaggageAllowanceInfo'];
           }
           if(isset($FirstPax_data['air:BaggageAllowances']['air:CarryOnAllowanceInfo'][0]))
           {
              $CarryOnAllowanceInfo=$FirstPax_data['air:BaggageAllowances']['air:CarryOnAllowanceInfo'];
           } else {
              $CarryOnAllowanceInfo=array($FirstPax_data['air:BaggageAllowances']['air:CarryOnAllowanceInfo']);
           }
           if($BaggageAllowanceInfo[0]['air:TextInfo']['air:Text'][0]['value'])
           {
               $CheckInBaggage=$BaggageAllowanceInfo[0]['air:TextInfo']['air:Text'][0]['value'];
           }
           if($CarryOnAllowanceInfo[0]['air:TextInfo']['air:Text']['value']=='7K')
           {
               $CabinBaggage="7 KG";
           } 
          
        }

        $BookingInfo=array();
        if(isset($FirstPax_data['air:BookingInfo']['BookingCode']))
        {
          $BookingInfo=array($FirstPax_data['air:BookingInfo']);
        } else {
          $BookingInfo=$FirstPax_data['air:BookingInfo'];
        }
        
        $main_segment=array();
        if($FinalAirSegment)
        {
            foreach($FinalAirSegment as $segkey=>$Sagments)
            { 
                if($SearchInputRequest['JourneyType']==2 && $SearchInputRequest['IsDomestic']=='true')
                {
                  $jkey=0;
                } else {
                  $jkey=intval($Sagments['Group']);
                }

                $FlightDetails=array();
                if(isset($Sagments['air:FlightDetails']['Key'])) 
                {
                  $FlightDetails = array($Sagments['air:FlightDetails']);
                } else {
                  $FlightDetails = $Sagments['air:FlightDetails'];
                }
                $first_flightdetail =  current($FlightDetails);
                $last_flightdetail  =  end($FlightDetails);

                $depart_terminal=""; $arrival_terminal="";
                if(isset($first_flightdetail['OriginTerminal'])) {
                  $depart_terminal = $first_flightdetail['OriginTerminal'];
                }
                if(isset($last_flightdetail['DestinationTerminal'])) {
                  $arrival_terminal = $last_flightdetail['DestinationTerminal'];
                }
                $TechStopPoint=array();
                foreach($FlightDetails as $key_fd=>$FlightDetail)
                {
                      if($key_fd!=0) 
                      {
                          array_push($TechStopPoint,$FlightDetail['Origin']);
                      }
                }

                if (isset($Sagments['air:CodeshareInfo'])) {
                  $OperatingCarrier=check_attribute_exist($Sagments['air:CodeshareInfo'],'OperatingCarrier');
                }  else {
                  $OperatingCarrier = $Sagments['Carrier'];
                }

                $TripIndicator=$Sagments['Group']+1;
                $air_data=GALFlight::GetAirData($Sagments['Carrier'],$Sagments['Origin'],$Sagments['Destination']);
                $dep_airport_data=$air_data['dep_airport_data'];
                $arr_airport_data=$air_data['arr_airport_data'];

                $CabinClass=$BookingInfo[$segkey]['CabinClass'];
      
                $main_segment[$jkey][]=array(
                                            'TripIndicator'     => $TripIndicator,
                                            'SegmentIndicator'  => $segkey+1,
                                            'CheckInBaggage'    => $CheckInBaggage,
                                            'CabinBaggage'      => $CabinBaggage,
                                            'CabinClass'        => $CabinClass,
                                            'Duration'          => $Sagments['FlightTime'],
                                            'TechStopPoint'     => $TechStopPoint,
                                            'Craft'             => $Sagments['Equipment'],
                                            'Airline'=>array(
                                                              'AirlineCode'      => $Sagments['Carrier'],
                                                              'AirlineName'      => $air_data['airline_name'],
                                                              'FlightNumber'     => (int) $Sagments['FlightNumber'],
                                                              'FareClass'        => $BookingInfo[$segkey]['BookingCode'],
                                                              'OperatingCarrier' => $OperatingCarrier
                                                          ),
                                            'Origin'=>array(
                                                              'AirportCode' => $Sagments['Origin'],
                                                              'AirportName' => $dep_airport_data['name'],
                                                              'CityCode'    => $dep_airport_data['city_code'],
                                                              'CityName'    => $dep_airport_data['city_name'],
                                                              'CountryCode' => $dep_airport_data['country_code'],
                                                              'CountryName' => ucwords(strtolower($dep_airport_data['country_name'])),
                                                              'Terminal'    => $depart_terminal,
                                                              'DepartTime'  => datetime_format($Sagments['DepartureTime'])
                                                          ),
                                            'Destination'=>array(
                                                              'AirportCode' => $Sagments['Destination'],
                                                              'AirportName' => $arr_airport_data['name'],
                                                              'CityCode'    => $arr_airport_data['city_code'],
                                                              'CityName'    => $arr_airport_data['city_name'],
                                                              'CountryCode' => $arr_airport_data['country_code'],
                                                              'CountryName' => ucwords(strtolower($arr_airport_data['country_name'])),
                                                              'Terminal'    => $arrival_terminal,
                                                              'ArrivalTime' => datetime_format($Sagments['ArrivalTime'])
                                                          )
                                ); 
            }
        }

        if (isset($FirstPax_data['Refundable']) && $FirstPax_data['Refundable'] == true) {
          $IsRefundable = true;
          $FareType_text = "REFUNDABLE";
        } else {
          $IsRefundable = false;
          $FareType_text = "NON-REFUNDABLE";
        } 
        if(isset($FirstPax_data['LatestTicketingTime']))
				{
					$LastTicketDate=explode('+',$FirstPax_data['LatestTicketingTime'])[0];	
          $ModifyLastTicketDate=date('d M Y H:i:s',strtotime($FirstPax_data['LatestTicketingTime']));
          $TicketAdvisory="TICKETS ARE $FareType_text \nLAST TKT DTE $ModifyLastTicketDate  - SEE ADV PURCHASE \n";
				} else {
					$LastTicketDate=null;
          $TicketAdvisory="";
				}

        $FareType=get_flight_fare_type('Publish');
        $ValidatingAirline = $FirstPax_data['PlatingCarrier'];

        $IsLCC=false;
        $IsPanRequired=false;
        $IsPassportRequired=false;
        $GSTAllowed=true;
        $IsGSTMandatory=false;
        $FlightPrice=Gal_get_flight_fare($SearchInputRequest,$CommonInfo,$super_admin_markup,$super_admin_discount,$AirPricing,$userauthdata,$super_admin_gst_state_code,'FareConfirmation');
        $TRAVELPORTflightDeal = getAirlineDeal($SearchInputRequest,$common_data['super_admin_flight_deal'],$main_segment,$FlightPrice,"TRAVELPORT",$CabinClass);
        $FlightPrice     =  Gal_get_flight_fare_with_discount($super_admin_discount,$FlightPrice,$TRAVELPORTflightDeal);
        $key_values = array_keys($FlightPrice['FareBreakdown']); 
        array_multisort($key_values, SORT_ASC, $FlightPrice['FareBreakdown']);

              
         $TTS_Result=array(
                              'ResultIndex'                 => $input['ResultIndex'],
                              'IsLCC'                       => $IsLCC,
                              'IsRefundable'                => $IsRefundable,
                              'IsPanRequiredAtBook'         => $IsPanRequired,
                              'IsPassportRequiredAtBook'    => $IsPassportRequired,
                              'GSTAllowed'                  => $GSTAllowed,
                              'IsGSTMandatory'              => $IsGSTMandatory,
                              'AirlineRemark'               => "",
                              'FareType'                    => $FareType,
                              'Fare'                        => $FlightPrice['Fare'],
                              'FareBreakdown'               => $FlightPrice['FareBreakdown'],
                              'Segments'                    => $main_segment,
                              'LastTicketDate'              => $LastTicketDate,
                              'TicketAdvisory'              => $TicketAdvisory,
                              'ValidatingAirline'           => $ValidatingAirline
                        );


          $TTS_Invoice_Amount=floatval($FlightPrice['Fare']['OfferedPrice'])+floatval($FlightPrice['Fare']['TDS']);

          $custom_index[$input['ResultIndex']]=array(
                                                        'TTS_Invoice_Amount'=>$TTS_Invoice_Amount,
                                                        'IsLCC'=>$IsLCC,
                                                        'IsPanRequiredAtBook'=>$IsPanRequired,
                                                        'IsPassportRequiredAtBook'=>$IsPassportRequired,
                                                        'GSTAllowed'=>$GSTAllowed,
                                                        'IsGSTMandatory'=>$IsGSTMandatory,
                                                        'AirlineCode'=>$ValidatingAirline,
                                                        'FareRule'=>$FlightPrice['farerulekey'],
                                                        'SuperAdminFareBreakup'=>$FlightPrice['SuperAdminFareBreakup'],
                                                        'WebPartnerFareBreakup'=>$FlightPrice['WebPartnerFareBreakup'],
                                                        'Supplier'=>'TRAVELPORT',
                                                        'PublishedPrice'=>$FlightPrice['Fare']['PublishedPrice'],
                                                        'CurrencyType'=>$CurrencyType
                                                     );

          $custom_index['EXTRAPARAM']=$response;

          if($SearchPublishedPrice==$FlightPrice['Fare']['PublishedPrice'])
          {
            $IsPriceChanged=false;
          } else {
            $IsPriceChanged=true;
          }
          
      } elseif(isset($airprice_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']))
      {
        $ErrorMessage=$airprice_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']['SOAP-ENV:faultstring']['value'];
        $ErrorCode=1;	        
      } elseif (isset($airprice_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      { 
        $ErrorMessage=$airprice_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring']['value'];
        $ErrorCode=2;	
      }

      /*--------------Start Insert API Logs------------------*/
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareConfirmation', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/
     
      
       
      $tts_response = array(
                              'UserIp'                 => $input['UserIp'],
                              'SearchTokenId'          => $tts_search_token,
                              'Error'                  => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                              'FlightDetailChangeInfo' => array(),
                              'IsPriceChanged'         => $IsPriceChanged,
                              'Result'                 => $TTS_Result
                          );

                      if($ErrorCode!=0)  
                      {   
                      unset($tts_response['IsPriceChanged']);  
                      }
        return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
    }

    public function SSR(array $input,array $common_data,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;

      $ErrorCode=1;
      $ErrorMessage="No SSR Detail Found.";
      $TTS_Result=array();
      $custom_index=array();
      $tts_response = array(
                                'UserIp'        => $input['UserIp'],
                                'SearchTokenId' => $tts_search_token,
                                'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                                'Result'        => $TTS_Result
                            );
      return array('Response' => $tts_response, 'CustomIndex' => $custom_index);
          
    }

    public function Book(array $input,array $common_data,array $userauthdata)
    {
      ini_set('memory_limit', '64M');

      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;
      $flight_offline=$common_data['FlightOffline'];
      $account_log_lastid=$common_data['SaveData']['account_log_lastid'];
      $booking_lastid=$common_data['SaveData']['booking_lastid'];  

      $FareConfirmPublishedPrice=$common_data['indexinfo']['PublishedPrice'];  
      $CurrencyType=$common_data['indexinfo']['CurrencyType'];  


      $AirPriceRsp=$common_data['EXTRAPARAM'];
      $airprice_array = xml2array($AirPriceRsp);
      
      $selectAirSegment=$airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']['air:AirItinerary']['air:AirSegment'];
		  if(isset($selectAirSegment[0]))
		  {
			  $selectAirSegmentarray=$selectAirSegment;
		  } else {
			  $selectAirSegmentarray=array($selectAirSegment);
		  }
      $airseg_airlines= array_unique(array_column($selectAirSegmentarray, 'Carrier'));
      $TravelerData=GeneratPaxRequest($input,$airseg_airlines,$this->AuthorizedBy);

      $xml = simplexml_load_String($AirPriceRsp, null, null, 'SOAP', true);
      $PriceResults = $xml->children('SOAP',true);
   
      $AirItinerary=""; $i=0;  $AirPricingSolution=""; 
      foreach($PriceResults->children('air',true) as $PriceResponse)
      {
           $XMLAirSegment="";
          foreach($PriceResponse->children('air',true)->AirItinerary as $airsegment)
          {
            if(strcmp($airsegment->getName(),'AirItinerary') == 0)
            {
              foreach($airsegment->children('air',true) as $segment)
              {
                if(strcmp($segment->getName(),'AirSegment') == 0)
                {
                  $XMLAirSegment.=$segment->asXML();
                }
              }
            }
          }
          $XMLAirSegmentRef=""; $Solution="";
          foreach($PriceResponse->children('air',true)->AirPriceResult->AirPricingSolution[0] as $airkey=>$airPriceSol)
          {
              if(strcmp($airPriceSol->getName(),'AirSegmentRef') == 0)
              {
                $XMLAirSegmentRef.=$airPriceSol->asXML();
              } else {
                 if(strcmp($airPriceSol->getName(),'AirPricingInfo') == 0)
                 {              
                      foreach($airPriceSol->children('air',true) as $airkey=>$child)
                      {
                          if(strcmp($child->getName(),'CancelPenalty') == 0)
                          {
                              $from = new \SimpleXMLElement('<air:AirPricingModifiers FaresIndicator="AllFares" ETicketability="Yes" />', LIBXML_NOERROR, false, 'air', true);
                              $from->addAttribute('xmlns:xmlns:air', 'http://www.travelport.com/schema/air_v51_0');

                              $new=dom_import_simplexml($from);
                              $target = dom_import_simplexml($child);
                              $new1=$target->ownerDocument->importNode($new, true);
                              if($target->nextSibling) {
                                $target->parentNode->insertBefore($new1, $target->nextSibling);
                              }                      
                          } 
                      }
                 }
                 $Solution.=$airPriceSol->asXML();
              }
          } 
          $keyval=""; 
          foreach($PriceResponse->children('air',true)->AirPriceResult->AirPricingSolution[0]->attributes() as $a => $b)
          {
              $keyval.=$a ."=". '"'.$b.'"' . " ";
          }
         $AirPricingSolution="<air:AirPricingSolution ".$keyval.">
                                 $XMLAirSegment $Solution
                              </air:AirPricingSolution>";

      }

      $BookingTraveler=$TravelerData['BookingTraveler'];
      $searches_adult=$TravelerData['searches_adult'];
      $replacements_adult=$TravelerData['replacements_adult'];
      $searches_child=$TravelerData['searches_child'];
      $replacements_child=$TravelerData['replacements_child'];
      $searches_infant=$TravelerData['searches_infant'];
      $replacements_infant=$TravelerData['replacements_infant'];
      
      $searches = [$searches_adult,$searches_child,$searches_infant,'common_v51_0'];
      $replacements=[$replacements_adult,$replacements_child,$replacements_infant,'com'];
      $NewAirPricingSolution = str_replace($searches, $replacements, $AirPricingSolution);

      $request='<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header/>
                <soapenv:Body>
                  <univ:AirCreateReservationReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:univ="http://www.travelport.com/schema/universal_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" TraceId="'.$this->TraceId.'" TargetBranch="'.$this->TargetBranch.'" RetainReservation="Both" AuthorizedBy="'.$this->AuthorizedBy.'">
                  <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
                  '.$BookingTraveler.'
                  <com:ContinuityCheckOverride Key="1T" xmlns:com="http://www.travelport.com/schema/common_v51_0">yes</com:ContinuityCheckOverride>
                  <com:AgencyContactInfo>
                    <com:PhoneNumber Type="Agency" Location="DEL" CountryCode="0091" AreaCode="11" Number="4089917" Text="'.$this->AuthorizedBy.'" />
                  </com:AgencyContactInfo> 
                  <com:FormOfPayment xmlns:com="http://www.travelport.com/schema/common_v51_0" Type="Cash" />
                    '.$NewAirPricingSolution.'	
                  <com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v51_0" Type="ACTIVE" ProviderCode="1G" TicketDate="T*" />
                  </univ:AirCreateReservationReq>	
                  </soapenv:Body>
            </soapenv:Envelope>';
    //  echo $request;  die();
      $response= GAL_Request($this->AirService_URL,$request);  
      // $response=file_get_contents(FCPATH."writable/book.xml");
      /*--------------Start Insert API Logs------------------*/
      $GALFlightModel= new GALFlightModel();
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Book', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/


       /*---------------Start Fare Rule Request ----------------*/
       $fareruleinput=array(
                              'UserIp'        => $input['UserIp'],
                              'ResultIndex'   => $input['ResultIndex'],
                              'SearchTokenId' => $input['SearchTokenId']
                          );
      $FareRuleResponse=GALFlight::FareRule($fareruleinput,$common_data,$userauthdata);
      $farerule_Result=array();
      if($FareRuleResponse['Response']['Error']['ErrorCode']==0)
      {
      $farerule_Result=$FareRuleResponse['Response']['Result'];
      }
     /*---------------End Fare Rule Request ----------------*/
      
      $AirCreateReservation_array = xml2array($response);
      $TTS_Result=array();
      $custom_index = array();
	    $ErrorCode=0;
	    $ErrorMessage="";
      if(isset($AirCreateReservation_array['SOAP:Envelope']['SOAP:Body']['universal:AirCreateReservationRsp']))
      {
        $AirCreateReservationRsp=$AirCreateReservation_array['SOAP:Envelope']['SOAP:Body']['universal:AirCreateReservationRsp'];
        if(isset($AirCreateReservationRsp['universal:UniversalRecord']))
		    {
              $UniversalRecord=$AirCreateReservationRsp['universal:UniversalRecord'];
              if(isset($UniversalRecord['air:AirReservation']['air:AirPricingInfo']))
		        	{
                   GALFlight::UpdateBookingData($UniversalRecord,$booking_lastid,$account_log_lastid,$farerule_Result,$CurrencyType,$FareConfirmPublishedPrice);
                   GALFlight::GetBookingDetails($input,$UniversalRecord,$userauthdata);
              } else {
                    $ResponseMessages=$AirCreateReservationRsp['common_v51_0:ResponseMessage'];
                    $ErrorCode=5;	
                    if($ResponseMessages)
                    {
                      foreach($ResponseMessages as $keyRm=>$ResponseMessage)
                      {
                          $ErrorMessage.=$ResponseMessage['value'].' ';
                      }
                    }
              }
        } elseif(isset($AirCreateReservationRsp['air:AirSolutionChangedInfo']))
        {
          $ResponseMessages=$AirCreateReservationRsp['common_v51_0:ResponseMessage'];
          $ErrorCode=4;	
            if($ResponseMessages)
            {
                if(isset($ResponseMessages['value']))
                {
                    $ErrorMessage=$ResponseMessages['value'];
                } else {
                      foreach($ResponseMessages as $keyRm=>$ResponseMessage)
                      {
                        $ErrorMessage.=$ResponseMessage['value'];
                      }
                  }
            }
        } else {
          $ErrorMessage="Please contact your provider";
          $ErrorCode=3;	
        }

      } elseif(isset($AirCreateReservation_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']))
      {
          $ErrorMessage=$AirCreateReservation_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']['SOAP-ENV:faultstring']['value'];
          $ErrorCode=1;	
      } elseif(isset($AirCreateReservation_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      {
          $ErrorMessage=$AirCreateReservation_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring']['value'];
		  	  $ErrorCode=2;	 
      }

      if($ErrorCode==0)
      {
        $booking_data=$GALFlightModel->verify_booking_detail(['tts_search_token'=>$input['SearchTokenId']]);
        $tts_response = ConvertBookingDetail($input,$booking_data);
      } else {
        $booking_status='Processing';
        $booking_update_data=array('booking_status' => $booking_status,'fare_rule'=> json_encode($farerule_Result),);
        $GALFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
        $GALFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], array('booking_status' => $booking_status));

        $tts_response = array(
                                'UserIp'        => $input['UserIp'],
                                'SearchTokenId' => $tts_search_token,
                                'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                                'Result'        => $TTS_Result
                             );
      }
      return $tts_response;
    }
    public function Ticket(array $input,array $common_data,array $userauthdata)
    {
      ini_set('memory_limit', '64M');
      $tts_search_token=$input['SearchTokenId'];
      $AirReservationLocatorCode=$input['PNR'];
      $ResultIndex=$input['ResultIndex'];
      $booking_lastid=$input['BookingId'];
      $this->TraceId=$tts_search_token;
      $GALFlightModel= new GALFlightModel();
      $BookData  =   $GALFlightModel->getData("common_flight_log",array("tts_search_token"=>$tts_search_token,"service"=>"Book","api_supplier"=>"TRAVELPORT","web_partner_id"=>$userauthdata['web_partner_id']),array(),1,0,"response");
                  $response =  $BookData['response'];
                  $FinalAirPricingInfoRef=""; 
                  $xmlTicket=simplexml_load_String($response, null, null, 'SOAP', true);
                  $Results=$xmlTicket->children('SOAP',true);
                  foreach($Results->children('universal',true) as $universalresp)
                  {	
                    foreach($universalresp->children('universal',true) as $UniversalRecord)
                    {
                      foreach($UniversalRecord->children('air',true) as $AirReservation)
                      {
                        $AirReservationLocatorCode=$AirReservation->attributes()->{'LocatorCode'};
                        foreach($AirReservation->children('air',true) as $AirSegment)
                        {									
                          if(strcmp($AirSegment->getName(),'AirPricingInfo') == 0)
                          {
                            $AirPricingInfoRefKeys=$AirSegment->attributes()->{'Key'};
                            $FinalAirPricingInfoRef.='<air:AirPricingInfoRef Key="'.$AirPricingInfoRefKeys.'"/>';
                          }
                        }
                      }
                    }
                  }
                $request='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                  <soapenv:Header/>
                  <soapenv:Body>
                    <air:AirTicketingReq xmlns:air="http://www.travelport.com/schema/air_v51_0" AuthorizedBy="'.$this->AuthorizedBy.'" BulkTicket="false" ReturnInfoOnFail="true" TargetBranch="'.$this->TargetBranch.'" TraceId="'.$this->TraceId.'">
                      <com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v51_0" OriginApplication="UAPI"/>
                      <air:AirReservationLocatorCode>'.$AirReservationLocatorCode.'</air:AirReservationLocatorCode>
                      '.$FinalAirPricingInfoRef.'
                    </air:AirTicketingReq>	
                    </soapenv:Body>
               </soapenv:Envelope>';

      $response= GAL_Request($this->AirService_URL,$request);   
       //$response=file_get_contents(FCPATH."writable/AirTicketingRsp.xml"); 
      /*--------------Start Insert API Logs------------------*/
     
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Ticket', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $AirTicketingRsp_array = xml2array($response);

      $TTS_Result=array();
      $custom_index = array();
	    $ErrorCode=0;
	    $ErrorMessage="";
      if(isset($AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['air:AirTicketingRsp']))
      {
           $AirTicketing=$AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['air:AirTicketingRsp'];
          if(isset($AirTicketing['air:ETR']))
          {
                  $etrarray=array();
                  if(isset($AirTicketing['air:ETR'][0]))
                  {
                      $etrarray=$AirTicketing['air:ETR'];
                  } else {
                      $etrarray=array($AirTicketing['air:ETR']);
                  }
                 
                  if($etrarray)
                  {
                      foreach($etrarray as $ticketresp)
                      {
                           $finalairTicket=array();
                           if(isset($ticketresp['air:Ticket'][0]))
                           {
                              $finalairTicket=$ticketresp['air:Ticket'];
                           } else {
                              $finalairTicket=array($ticketresp['air:Ticket']);
                           }
                           
                           $ticket_number=$finalairTicket[0]['TicketNumber'];
                           $validating_airline=substr($ticket_number, 0, 3);
                           $ticket_number=substr($ticket_number,3);
                           
                           $booking_status='Confirmed';
                           $FirstName=$ticketresp['common_v51_0:BookingTraveler']['common_v51_0:BookingTravelerName']['First'];
                           $PaxType=$ticketresp['common_v51_0:BookingTraveler']['TravelerType'];

                           $pax_update_data=array(
                                                      'pax_id'            => "",
                                                      'ticket_id'         => "",
                                                      'ticket_number'     => $ticket_number,
                                                      'validating_airline'=> $validating_airline,
                                                      "booking_status"    => $booking_status
                                                 );
                          $GALFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid,"first_name"=>trim($FirstName),'pax_type'=>GetPaxType($PaxType)], $pax_update_data);

                          $GALFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], array('booking_status'=>$booking_status));
                      }
                  }
          } elseif(isset($AirTicketing['air:TicketFailureInfo'])) {
            $ErrorCode=4;	 
				    $ErrorMessage=$AirTicketing['air:TicketFailureInfo']['Message'];
          } else {
            $ErrorCode=3;
            $ErrorMessage="Please contact your provider";
          }

      } elseif(isset($AirTicketingRsp_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']))
      {
         $ErrorMessage=$AirTicketingRsp_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']['SOAP-ENV:faultstring']['value'];
         $ErrorCode=1;	

      } elseif(isset($AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      {
         $ErrorMessage=$AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring']['value'];
			   $ErrorCode=2;	 
      }
      $tts_response = array(
                              'UserIp'        => $input['UserIp'],
                              'SearchTokenId' => $tts_search_token,
                              'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                              'Result'        => $TTS_Result
                          );
      return $tts_response;
    }

    public function UpdateBookingData($UniversalRecord,$booking_lastid,$account_log_lastid,$fare_rule,$currency_type,$previous_fare)
    {
            $GALFlightModel= new GALFlightModel();
            $AirPricingInfo=$UniversalRecord['air:AirReservation']['air:AirPricingInfo'];
            if(isset($AirPricingInfo['Key']))
            {
              $AirPricingInfo=array($AirPricingInfo);
            }  
            $totalFare=0; $currency_length=strlen($currency_type);
            foreach($AirPricingInfo as $AirPricing)
            {
              $totalFare+=floatval(substr($AirPricing['TotalPrice'], $currency_length));
            }

            $AirSegment=$UniversalRecord['air:AirReservation']['air:AirSegment'];
            $FinalAirSegment=array();
            if(isset($AirSegment[0]))
            {
              $FinalAirSegment=$AirSegment;
            } else {
              $FinalAirSegment=array($AirSegment);
            }

            $firstAirsegment=current($FinalAirSegment);
            $lastAirsegment=end($FinalAirSegment);
            
            $origin=$firstAirsegment['Origin'];
            $destination=$lastAirsegment['Destination'];
            $departure_date=explode("T",$firstAirsegment['DepartureTime'])[0];
            $airline_code=$firstAirsegment['Carrier'];
            $flight_number=$firstAirsegment['FlightNumber'];
           
            $Pax_info_array=array();
            if(isset($UniversalRecord['common_v51_0:BookingTraveler'][0]))
            {
              $Pax_info_array=$UniversalRecord['common_v51_0:BookingTraveler'];
            } else {
              $Pax_info_array=array($UniversalRecord['common_v51_0:BookingTraveler']);
            }

            $SupplierLocator=array();
            if(isset($UniversalRecord['air:AirReservation']['common_v51_0:SupplierLocator']))
            {
                if(isset($UniversalRecord['air:AirReservation']['common_v51_0:SupplierLocator']['SupplierLocatorCode']))
                {
                  $SupplierLocator=array($UniversalRecord['air:AirReservation']['common_v51_0:SupplierLocator']);
                } else{
                  $SupplierLocator=$UniversalRecord['air:AirReservation']['common_v51_0:SupplierLocator'];
                }
            } else {
                  if(isset($UniversalRecord['universal:ProviderReservationInfo']))
                  {
                      
                  }
            }

            $ProviderReservationInfoRef=array_column($SupplierLocator,'ProviderReservationInfoRef');
            $airline_pnr=array();
            foreach($FinalAirSegment as $segkey=>$Segment)
            {
                if(isset($Segment['ProviderReservationInfoRef']))
                {
                    $jkey=$Segment['Group']+1;
                    $skey=$segkey+1;
                    $ProviderReservationInfoRefKey=array_search($Segment['ProviderReservationInfoRef'],$ProviderReservationInfoRef);
                    if($SupplierLocator)
                    {
                      if(isset($SupplierLocator[$ProviderReservationInfoRefKey]))
                      {
                        $airline_pnr[$jkey][$skey]=$SupplierLocator[$ProviderReservationInfoRefKey]['SupplierLocatorCode'];
                      }
                    }
                }
            }
            $universal_pnr=$UniversalRecord['LocatorCode'];
            $gds_pnr=$UniversalRecord['universal:ProviderReservationInfo']['LocatorCode'];
           
            $booking_status='Hold';
            if($previous_fare==$totalFare)
            {
              $is_price_changed=false;
            } else{
              $is_price_changed=true;
            }
            $is_time_changed=false;

            $GALFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid],array('booking_status' => $booking_status));

            $booking_update_data=array(
                                                'is_price_changed'   => $is_price_changed,
                                                'is_time_changed'    => $is_time_changed,
                                                'supplier_booking_id'=> $universal_pnr,
                                                'booking_status'     => $booking_status,
                                                'fare_rule'          => json_encode($fare_rule),
                                                'pnr'                => $gds_pnr,
                                                'airline_pnr'        => json_encode($airline_pnr)
                                      );
            $GALFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $PaxName = $Pax_info_array[0]['common_v51_0:BookingTravelerName']['First'] . ' ' . $Pax_info_array[0]['common_v51_0:BookingTravelerName']['Last'] . ' X ' . count($Pax_info_array);
            $Sector = $origin. '-' . $destination;
            $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date,'AirlineString'=>$airline_code.$flight_number,'TicketNo'=>$gds_pnr);
            $account_update_data=array('service_log'    => json_encode($service_log));
          /*   $GALFlightModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);   */   
    }
  
    private function Ticketbck(array $input,array $common_data,array $userauthdata,$AirReservationLocatorCode,$FinalAirPricingInfoRef,$booking_lastid)
    {
      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;

      $request='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                  <soapenv:Header/>
                  <soapenv:Body>
                    <air:AirTicketingReq xmlns:air="http://www.travelport.com/schema/air_v51_0" AuthorizedBy="'.$this->AuthorizedBy.'" BulkTicket="false" ReturnInfoOnFail="true" TargetBranch="'.$this->TargetBranch.'" TraceId="'.$this->TraceId.'">
                      <com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v51_0" OriginApplication="UAPI"/>
                      <air:AirReservationLocatorCode>'.$AirReservationLocatorCode.'</air:AirReservationLocatorCode>
                      '.$FinalAirPricingInfoRef.'
                    </air:AirTicketingReq>	
                    </soapenv:Body>
               </soapenv:Envelope>';

      $response= GAL_Request($this->AirService_URL,$request);   
       //$response=file_get_contents(FCPATH."writable/AirTicketingRsp.xml"); 
      /*--------------Start Insert API Logs------------------*/
      $GALFlightModel= new GALFlightModel();
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Ticket', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $AirTicketingRsp_array = xml2array($response);

      $TTS_Result=array();
      $custom_index = array();
	    $ErrorCode=0;
	    $ErrorMessage="";
      if(isset($AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['air:AirTicketingRsp']))
      {
           $AirTicketing=$AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['air:AirTicketingRsp'];
          if(isset($AirTicketing['air:ETR']))
          {
                  $etrarray=array();
                  if(isset($AirTicketing['air:ETR'][0]))
                  {
                      $etrarray=$AirTicketing['air:ETR'];
                  } else {
                      $etrarray=array($AirTicketing['air:ETR']);
                  }
                 
                  if($etrarray)
                  {
                      foreach($etrarray as $ticketresp)
                      {
                           $finalairTicket=array();
                           if(isset($ticketresp['air:Ticket'][0]))
                           {
                              $finalairTicket=$ticketresp['air:Ticket'];
                           } else {
                              $finalairTicket=array($ticketresp['air:Ticket']);
                           }
                           
                           $ticket_number=$finalairTicket[0]['TicketNumber'];
                           $validating_airline=substr($ticket_number, 0, 3);
                           $ticket_number=substr($ticket_number,3);
                           
                           $booking_status='Confirmed';
                           $FirstName=$ticketresp['common_v51_0:BookingTraveler']['common_v51_0:BookingTravelerName']['First'];
                           $PaxType=$ticketresp['common_v51_0:BookingTraveler']['TravelerType'];

                           $pax_update_data=array(
                                                      'pax_id'            => "",
                                                      'ticket_id'         => "",
                                                      'ticket_number'     => $ticket_number,
                                                      'validating_airline'=> $validating_airline,
                                                      "booking_status"    => $booking_status
                                                 );
                          $GALFlightModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid,"first_name"=>trim($FirstName),'pax_type'=>GetPaxType($PaxType)], $pax_update_data);

                          $GALFlightModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], array('booking_status'=>$booking_status));
                      }
                  }
          } elseif(isset($AirTicketing['air:TicketFailureInfo'])) {
            $ErrorCode=4;	 
				    $ErrorMessage=$AirTicketing['air:TicketFailureInfo']['Message'];
          } else {
            $ErrorCode=3;
            $ErrorMessage="Please contact your provider";
          }

      } elseif(isset($AirTicketingRsp_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']))
      {
         $ErrorMessage=$AirTicketingRsp_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']['SOAP-ENV:faultstring']['value'];
         $ErrorCode=1;	

      } elseif(isset($AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      {
         $ErrorMessage=$AirTicketingRsp_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring']['value'];
			   $ErrorCode=2;	 
      }
      $tts_response = array(
                              'UserIp'        => $input['UserIp'],
                              'SearchTokenId' => $tts_search_token,
                              'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                              'Result'        => $TTS_Result
                          );
      return $tts_response;
    }

    public function GetBookingDetails(array $input,$UniversalRecordLocatorCode,array $userauthdata)
    {
      $tts_search_token=$input['SearchTokenId'];
      $this->TraceId=$tts_search_token;
      $request='<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                  <soapenv:Header/>
                  <soapenv:Body>
                    <UniversalRecordRetrieveReq xmlns="http://www.travelport.com/schema/universal_v51_0" TraceId="'.$this->TraceId.'" AuthorizedBy="'.$this->AuthorizedBy.'" TargetBranch="'.$this->TargetBranch.'">
                      <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v51_0" OriginApplication="UAPI" />
                      <UniversalRecordLocatorCode>'.$UniversalRecordLocatorCode.'</UniversalRecordLocatorCode>
                    </UniversalRecordRetrieveReq>
                  </soapenv:Body>
                </soapenv:Envelope>';
      $response= GAL_Request($this->UniversalService_URL,$request);   
      //$response=file_get_contents(FCPATH."writable/UniversalRecordRetrieveRsp.xml");
   
      /*--------------Start Insert API Logs------------------*/
      $GALFlightModel= new GALFlightModel();
      $GALFlightModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response,'GetBookingDetails', 'TRAVELPORT', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $UniversalRecordRetrieveReq_array = xml2array($response);
      if(isset($UniversalRecordRetrieveReq_array['SOAP:Envelope']['SOAP:Body']['universal:UniversalRecordRetrieveRsp']))
      {
       

        $UniversalRecordRetrieveRsp=$UniversalRecordRetrieveReq_array['SOAP:Envelope']['SOAP:Body']['universal:UniversalRecordRetrieveRsp'];
        $UniversalRecord=$UniversalRecordRetrieveRsp['universal:UniversalRecord'];
        $AirSegment=$UniversalRecord['air:AirReservation']['air:AirSegment'];
        $AirPricingInfo=$UniversalRecord['air:AirReservation']['air:AirPricingInfo'];

      


      } elseif(isset($UniversalRecordRetrieveReq_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']))
      {

      } else if(isset($UniversalRecordRetrieveReq_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
      {

      }

    }

}
