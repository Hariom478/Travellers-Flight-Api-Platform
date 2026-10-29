<?php

namespace Modules\Indigo\Controllers;

use App\Controllers\BaseController;
use App\Modules\Indigo\Models\IndigoModel;
use Modules\Indigo\Config\Validation;
use CodeIgniter\I18n\Time;
use Config\APIConfig;
use App\Modules\Airservice\Models\RestModel;
 

class Indigo extends BaseController
{
    protected $airline_list=[];
    protected $airport_list=[];

    public function __construct()
    {
       helper('Modules\Indigo\Helpers\Indigo');
       $this->GetTimeZone=app_timezone();

       $apiconfig= new APIConfig;
       $credential=$apiconfig->travelport_indigo_credential;

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
          $FinalRequest=array();
          if($input['JourneyType']==2 && $input['IsDomestic']==true)
          {
            $OBSegment=current($input['AirSegments']);
            $IBSegment=end($input['AirSegments']);

            $input['AirSegments']=array($OBSegment);
            $request=IndigoGenerateRequest($input,$tts_search_token,$userauthdata,$this->AuthorizedBy,$this->TargetBranch,$this->AirService_URL,$this->Credentials,'OB');
            array_push($FinalRequest,$request);

            $input['AirSegments']=array($IBSegment);
            $request=IndigoGenerateRequest($input,$tts_search_token,$userauthdata,$this->AuthorizedBy,$this->TargetBranch,$this->AirService_URL,$this->Credentials,'IB');
            array_push($FinalRequest,$request);

          } else {
            if($input['JourneyType']!=3) {
            $FinalRequest=IndigoGenerateRequest($input,$tts_search_token,$userauthdata,$this->AuthorizedBy,$this->TargetBranch,$this->AirService_URL,$this->Credentials,'OB');
            }
          }
          return $FinalRequest;
    }

    public function ConvertSearchResponse($input,$response,$convert_response,$custom_index,$common_data)
    {
       // echo $response; die();
       if($response) {
        // increase allowed memory size
        ini_set('memory_limit', '512M');

        $this->airline_list=$common_data['airline_list'];
        $this->airport_list=$common_data['airport_list'];

        $response_array = Indigoxml2array($response);
        if(!isset($response_array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['SOAP-ENV:Fault']) && !isset($response_array['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']))
	      {
         

          $result = $response_array['SOAP:Envelope']['SOAP:Body']['air:LowFareSearchRsp'];
          $CurrencyType=$result['CurrencyType'];

          if(isset($result['air:FlightDetailsList']['air:FlightDetails'][0]))
          {
            $FlightDetailsList = $result['air:FlightDetailsList']['air:FlightDetails'];
          } else{
            $FlightDetailsList = array($result['air:FlightDetailsList']['air:FlightDetails']);
          }
          if(isset($result['air:AirSegmentList']['air:AirSegment'][0]))
          {
            $AirSegmentList = $result['air:AirSegmentList']['air:AirSegment'];
          } else{
            $AirSegmentList = array($result['air:AirSegmentList']['air:AirSegment']);
          }
          if(isset($result['air:FareInfoList']['air:FareInfo'][0]))
          {
            $FareInfoList = $result['air:FareInfoList']['air:FareInfo'];
          } else {
            $FareInfoList = array($result['air:FareInfoList']['air:FareInfo']);
          }
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
          $HostTokenList=array();
          if(isset($result['air:HostTokenList']))
          {
            $HostTokenList=$result['air:HostTokenList']['common_v51_0:HostToken'];
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
          $HostTokenListKeys=array_column($HostTokenList,'Key');

          $CommonInfo=array();
          $CommonInfo['FareInfoListKeys']=$FareInfoListKeys;
          $CommonInfo['FareInfoList']=$FareInfoList;
          $CommonInfo['CurrencyType']=$CurrencyType;

          $JourneyIdentifiers = $common_data['JourneyIdentifiers'];
          $journeykey =  $JourneyIdentifiers=="OB"?0:1;

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
                      $resultcombinations=IndigoGenerateCombinations($journeygroup);             
                      if($resultcombinations)
                      { 
                          foreach($resultcombinations as $comkey=>$combination)
                          {     
                                $FinalSegment=array(); $FinalBaggageInfo=array(); $AirSegmentData=""; $hotshtml=""; $FareRulekey=array(); $AirSegmentPricingModifiers="";
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
                                        
                                          $Baggage="15 KG";
                                          $CabinBaggage="7 KG";
                                        
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
                                          if(isset($BookingInfo['HostTokenRef'])) {
                                             $Sagments['HostTokenRef']=$BookingInfo['HostTokenRef'];
                                             $HostIndexKey=array_search($BookingInfo['HostTokenRef'],$HostTokenListKeys);
                                             $GetHostInfo=$HostTokenList[$HostIndexKey];
                                             $hotshtml.='<com:HostToken Key="'.$GetHostInfo['Key'].'">'.$GetHostInfo['value'].'</com:HostToken>';	
                                          }
    
                                          $Sagments['ProviderCode']=$Sagments['air:AirAvailInfo']['ProviderCode'];
                                          $Sagments['ClassOfService']=$BookingInfo['BookingCode'];

                                          $rulearray=array(
                                                            'Airline'           => $Sagments['Carrier'],
                                                            'FareBasis'         => $GetFareInfo['FareBasis'],
                                                            'PassengerTypeCode' => $GetFareInfo['PassengerTypeCode'],
                                                            'Origin'		      	=> $GetFareInfo['Origin'],
                                                            'Destination'	      => $GetFareInfo['Destination'],
                                                            'FareRuleKey'	    	=> $GetFareInfo['air:FareRuleKey']
                                                          );
                                      
                                          array_push($FareRulekey,$rulearray);

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
                                          $AirSegmentPricingModifiers.='<air:AirSegmentPricingModifiers AirSegmentRef="'.$BookingInfo['SegmentRef'].'" FareBasisCode="'.$GetFareInfo['FareBasis'].'"/>';

                                    
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
                                              $OperatingCarrier=Indigocheck_attribute_exist($Sagments['air:CodeshareInfo'],'OperatingCarrier');
                                          }  else {
                                              $OperatingCarrier = $Sagments['Carrier'];
                                          }

                                          $Craft="";
                                          if(isset($Sagments['Equipment']))
                                          {
                                            $Craft=$Sagments['Equipment'];
                                          }
    
                                          $air_data=Indigo::GetAirData($Sagments['Carrier'],$Sagments['Origin'],$Sagments['Destination']);
                                          $dep_airport_data=$air_data['dep_airport_data'];
                                          $arr_airport_data=$air_data['arr_airport_data'];
    
                                          $ttssegment=array(
                                                            'TripIndicator'     => $jkey+1,
                                                            'SegmentIndicator'  => $segkey+1,
                                                            'Duration'          => $Sagments['FlightTime'],
                                                            'TechStopPoint'     => $TechStopPoint,
                                                            'Craft'             => $Craft,
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
                                                                              'DepartTime'  => Indigodatetime_format($Sagments['DepartureTime'])
                                                                          ),
                                                            'Destination'=>array(
                                                                              'AirportCode' => $Sagments['Destination'],
                                                                              'AirportName' => $arr_airport_data['name'],
                                                                              'CityCode'    => $arr_airport_data['city_code'],
                                                                              'CityName'    => $arr_airport_data['city_name'],
                                                                              'CountryCode' => $arr_airport_data['country_code'],
                                                                              'CountryName' => ucwords(strtolower($arr_airport_data['country_name'])),
                                                                              'Terminal'    => $arrival_terminal,
                                                                              'ArrivalTime' => Indigodatetime_format($Sagments['ArrivalTime'])
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
                                $FlightPrice=Indigo_get_flight_fare($input,$CommonInfo,$super_admin_markup,$super_admin_discount,$AirPricePoint,$userauthdata,$super_admin_gst_state_code);
                                $INDIGOflightDeal = getAirlineDeal($input,$common_data['super_admin_flight_deal'],$FinalSegment,$FlightPrice,"INDIGO",$CabinClass);
                                $FlightPrice     =  Indigo_get_flight_fare_with_discount($super_admin_discount,$FlightPrice,$INDIGOflightDeal);
                              
                                $previous_fare=array();
                                $FareId='GAL6E'.$JourneyIdentifiers.$mainkey.$comkey;
    
                                $custom_index[$FareId] = array('FareRule' =>$FareRulekey,'Supplier'=>'INDIGO','AirSegment'=>htmlentities($AirSegmentData),'HostToken'=>htmlentities($hotshtml),'AirSegmentPricingModifiers'=>htmlentities($AirSegmentPricingModifiers),'CurrencyType'=>$CurrencyType,'PublishedPrice'=>$FlightPrice['Fare']['PublishedPrice']);
    
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
                                                  'Source'           => 'INDIGO'
                                              );  
        
                                if(!isset($convert_response[$journeykey][$custom_key]['Segments']))
                                {
                                  $convert_response[$journeykey][$custom_key]['Segments']=$FinalSegment;
                                }
                                if(isset($convert_response[$journeykey][$custom_key]['FareList']))
                                {
                                  $previous_fare=$convert_response[$journeykey][$custom_key]['FareList'];
                                } 
                                array_push($previous_fare,$FareList);
                                $convert_response[$journeykey][$custom_key]['FareList']=$previous_fare;
                          }
                      }
               /* End AirPricingInfo First Pax */         
           }
        }
        return array('convert_response'=>$convert_response,'airline_list'=>$this->airline_list,'airport_list'=>$this->airport_list,'custom_index'=>$custom_index);
      } else 
      {
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
      $response= Indigo_Request($this->AirService_URL,$request);
      //$response=file_get_contents(FCPATH."writable/indigo-fare-rule.xml");
      $fare_rule_array = Indigoxml2array($response);

      $IndigoModel= new IndigoModel();
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
                                  'FareRuleDetail'=>'<h3>'.IndigoGetPaxType($FareRuleList[$key_main]['PassengerTypeCode']).'</h3> <br/> '.$FareRuleDetail,
                               );
          }					
      }
      /*--------------Start Insert API Logs------------------*/
      $IndigoModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareRule', 'INDIGO', strtotime(Time::now($this->GetTimeZone)));
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
        $adtpax.='<com:SearchPassenger Code="ADT" Key="'.$this->TraceId.'_ADT_'.$adt.'" />';
      }
      $chdpax="";
      for($chd=1;$chd<=$chd_count;$chd++)
      {			 
        $chdpax.='<com:SearchPassenger Age="10" Code="CHD" Key="'.$this->TraceId.'_CHD_'.$chd.'"  />';
      }
      $infpax="";
      for($inf=1;$inf<=$inf_count;$inf++)
      {
        $infpax.='<com:SearchPassenger Age="1" Code="INF" Key="'.$this->TraceId.'_INF_'.$inf.'"  />';
      }

      $AirSegment=htmlspecialchars_decode($common_data['indexinfo']['AirSegment']);
      $HostToken=htmlspecialchars_decode($common_data['indexinfo']['HostToken']);
      $AirSegmentPricingModifiers=htmlspecialchars_decode($common_data['indexinfo']['AirSegmentPricingModifiers']);
 
      $request='<?xml version="1.0" encoding="utf-8"?>
                  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                  <soapenv:Header/>
                  <soapenv:Body>								
                    <air:AirPriceReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" AuthorizedBy="'.$this->AuthorizedBy.'" TargetBranch="'.$this->TargetBranch.'" TraceId="'.$this->TraceId.'" CheckOBFees="All" FareRuleType="long">
                      <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
                        <air:AirItinerary>
                        '.$AirSegment.'
                        '.$HostToken.'
                        </air:AirItinerary>
                        '.$adtpax.'
                        '.$infpax.'
                        '.$chdpax.'
                      <air:AirPricingCommand>
                        '.$AirSegmentPricingModifiers.'
                      </air:AirPricingCommand>
                      </air:AirPriceReq>
                    </soapenv:Body>
              </soapenv:Envelope>';
     $response= Indigo_Request($this->AirService_URL,$request);    
      //$response=file_get_contents(FCPATH."writable/indigo-price.xml");
      $airprice_array=Indigoxml2array($response);

      $IndigoModel= new IndigoModel();
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
        
        $main_segment=array(); $ValidatingAirline="";
        if($FinalAirSegment)
        { 
            $group_check=array();
            foreach($FinalAirSegment as $segkey=>$Sagments)
            { 
                if($SearchInputRequest['JourneyType']==2 && $SearchInputRequest['IsDomestic']=='true')
                {
                  $jkey=0;
                } else {
                  $jkey=intval($Sagments['Group']);
                }

                if(in_array($jkey, $group_check))
                {
                  $SegmentIndicator++;
                } else {
                  $SegmentIndicator=1;
                  array_push($group_check,$jkey);
                }

                $FlightDetails=array();
                if(isset($Sagments['air:FlightDetails']['Key'])) 
                {
                  $FlightDetails = array($Sagments['air:FlightDetails']);
                } elseif(isset($Sagments['air:FlightDetails'])) {
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
                  $OperatingCarrier=Indigocheck_attribute_exist($Sagments['air:CodeshareInfo'],'OperatingCarrier');
                }  else {
                  $OperatingCarrier = $Sagments['Carrier'];
                }

                $TripIndicator=$Sagments['Group']+1;
                $air_data=Indigo::GetAirData($Sagments['Carrier'],$Sagments['Origin'],$Sagments['Destination']);
                $dep_airport_data=$air_data['dep_airport_data'];
                $arr_airport_data=$air_data['arr_airport_data'];

                $CabinClass=$BookingInfo[$segkey]['CabinClass'];
      
                if($jkey==0 && $segkey==0)
                {
                  $ValidatingAirline=$Sagments['Carrier'];
                }
               
                $Craft="";
                if(isset($Sagments['Equipment']))
                {
                  $Craft=$Sagments['Equipment'];
                }

                $main_segment[$jkey][]=array(
                                            'TripIndicator'     => $TripIndicator,
                                            'SegmentIndicator'  => $SegmentIndicator,
                                            'CheckInBaggage'    => $CheckInBaggage,
                                            'CabinBaggage'      => $CabinBaggage,
                                            'CabinClass'        => $CabinClass,
                                            'Duration'          => $Sagments['FlightTime'],
                                            'TechStopPoint'     => $TechStopPoint,
                                            'Craft'             => $Craft,
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
                                                              'DepartTime'  => Indigodatetime_format($Sagments['DepartureTime'])
                                                          ),
                                            'Destination'=>array(
                                                              'AirportCode' => $Sagments['Destination'],
                                                              'AirportName' => $arr_airport_data['name'],
                                                              'CityCode'    => $arr_airport_data['city_code'],
                                                              'CityName'    => $arr_airport_data['city_name'],
                                                              'CountryCode' => $arr_airport_data['country_code'],
                                                              'CountryName' => ucwords(strtolower($arr_airport_data['country_name'])),
                                                              'Terminal'    => $arrival_terminal,
                                                              'ArrivalTime' => Indigodatetime_format($Sagments['ArrivalTime'])
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

        $FlightPrice=Indigo_get_flight_fare($SearchInputRequest,$CommonInfo,$super_admin_markup,$super_admin_discount,$AirPricing,$userauthdata,$super_admin_gst_state_code,'FareConfirmation');
        $INDIGOflightDeal = getAirlineDeal($input,$common_data['super_admin_flight_deal'],$main_segment,$FlightPrice,"INDIGO",$CabinClass);
        $FlightPrice     =  Indigo_get_flight_fare_with_discount($super_admin_discount,$FlightPrice,$INDIGOflightDeal);
        $FareType=get_flight_fare_type('Publish');
        $ValidatingAirline = $ValidatingAirline;

        $IsLCC=true;
        $IsPanRequired=false;
        $IsPassportRequired=false;
        $GSTAllowed=true;
        $IsGSTMandatory=false;

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
                                                        'Supplier'=>'INDIGO',
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
      $IndigoModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'FareConfirmation', 'INDIGO', strtotime(Time::now($this->GetTimeZone)));
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

    public function Ticket(array $input,array $common_data,array $userauthdata)
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
      $airprice_array = Indigoxml2array($AirPriceRsp);

      $AirPriceResult=array();
      if(isset($airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']['air:AirPriceResult']))
      { 
        $AirPriceResult=array($airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']['air:AirPriceResult']);
      } else {
        $AirPriceResult=$airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']['air:AirPriceResult'];
      }

      $selectAirSegment=$airprice_array['SOAP:Envelope']['SOAP:Body']['air:AirPriceRsp']['air:AirItinerary']['air:AirSegment'];
		  if(isset($selectAirSegment[0]))
		  {
			  $selectAirSegmentarray=$selectAirSegment;
		  } else {
			  $selectAirSegmentarray=array($selectAirSegment);
		  }
      $airseg_airlines= array_unique(array_column($selectAirSegmentarray, 'Carrier'));
     

      $xml = simplexml_load_String($AirPriceRsp, null, LIBXML_NOBLANKS, 'SOAP', true);
      $PriceResults = $xml->children('SOAP',true);
   
      $AirItinerary=""; $i=0;  $AirPricingSolution=""; 
      foreach($PriceResults->children('air',true) as $PriceResponse)
      {
           $XMLAirSegment=""; $HostToken="";
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
              foreach($airsegment->children('common_v51_0',true) as $segment)
              {
                  if(strcmp($segment->getName(),'HostToken') == 0)
                  {
                      $HostToken.=$segment->asXML();
                  }
              }
            }
            
          }
          $Solution="";  $PaxBookingTravelerRef=array(); 
          foreach($PriceResponse->children('air',true)->AirPriceResult->AirPricingSolution[0] as $airkey=>$airPriceSol)
          {
    
              if(strcmp($airPriceSol->getName(),'OptionalServices') == 0)
              {
                
              } else {

                  if(strcmp($airPriceSol->getName(),'AirSegmentRef') == 0)
                  {
                   
                  } else {
                    if(strcmp($airPriceSol->getName(),'AirPricingInfo') == 0)
                    {
                          foreach($airPriceSol->children('air',true) as $airkey=>$Paxdata)
                         {
                           if(strcmp($Paxdata->getName(),'PassengerType') == 0)
                           {
                               $object=$Paxdata->attributes();

                               $object=(array) $object;
                          
                               $paxcode=$object['@attributes']['Code'];
                               $travelerRef=$object['@attributes']['BookingTravelerRef'];
                               $PaxBookingTravelerRef[$paxcode][]=$travelerRef;
                           }
                           
                         }

                          /*$air_modifiers=$airPriceSol->addChild('AirPricingModifiers');
                          $air_modifiers->addAttribute('FaresIndicator','AllFares');
                          $air_modifiers->addAttribute('ETicketability','Required');*/
                    }
    
                    $Solution.=$airPriceSol->asXML();
                  }

              }

            
          } 
          $keyval=""; 
          foreach($PriceResponse->children('air',true)->AirPriceResult->AirPricingSolution[0]->attributes() as $a => $b)
          {
              $keyval.=$a ."=". '"'.$b.'"' . " ";
          }
          $AirPricingSolution="<air:AirPricingSolution ".$keyval.">
                                  $XMLAirSegment $Solution $HostToken
                              </air:AirPricingSolution>";
      }

      $PaxBookingTravelerRef=(array)$PaxBookingTravelerRef;

      $TravelerData=IndigoGeneratPaxRequest($input,$airseg_airlines,$this->AuthorizedBy,$PaxBookingTravelerRef);

      $BookingTraveler=$TravelerData['BookingTraveler'];
      $searches = ['common_v51_0'];
      $replacements=['com'];
      $NewAirPricingSolution = str_replace($searches, $replacements, $AirPricingSolution);


      if($this->Mode =='Live')
      {
        $account_detail=$IndigoModel->get_api_account_id(['api_supplier_name'=>'INDIGO','status'=>'active']);
        if($account_detail)
        {
          $FormOfPayment='<com:FormOfPayment Type="AgencyPayment">
                              <com:AgencyPayment AgencyBillingIdentifier="'.$account_detail['username'].'" AgencyBillingPassword="'.$account_detail['password'].'"/>
                          </com:FormOfPayment>';
        } else {
          api_custom_message(400, 'indigo account id not setup.',false);
        }
  
      } else{
        $FormOfPayment='<com:FormOfPayment Type="Credit">
                          <com:CreditCard Type="MC" BankCountryCode="US" CVV="123" ExpDate="2023-12" Name="Test Travelport" Number="5200000000000007">
                            <com:BillingAddress>
                              <com:AddressName>Home</com:AddressName>
                              <com:Street>2914 N. Dakota Avenue</com:Street>
                              <com:City>Denver</com:City>
                              <com:State>CO</com:State>
                              <com:PostalCode>80206</com:PostalCode>
                              <com:Country>US</com:Country>
                            </com:BillingAddress>
                          </com:CreditCard>
                        </com:FormOfPayment>';
      }

      $request='<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header/>
                <soapenv:Body>
                  <univ:AirCreateReservationReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:univ="http://www.travelport.com/schema/universal_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" TraceId="'.$this->TraceId.'" TargetBranch="'.$this->TargetBranch.'" RetainReservation="Both" AuthorizedBy="'.$this->AuthorizedBy.'">
                  <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
                  '.$BookingTraveler.'
                  <com:AgencyContactInfo>
                    <com:PhoneNumber Type="Agency" Location="DEL" CountryCode="0091" AreaCode="11" Number="4089917" Text="'.$this->AuthorizedBy.'" />
                  </com:AgencyContactInfo>
                    '.$FormOfPayment.'
                    '.$NewAirPricingSolution.'	
                    <com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v51_0" Type="ACTIVE" ProviderCode="ACH" TicketDate="T*" />
                  </univ:AirCreateReservationReq>	
                  </soapenv:Body>
            </soapenv:Envelope>';
      //echo $request;
      // /die();

      $response= Indigo_Request($this->AirService_URL,$request);  
      /*--------------Start Insert API Logs------------------*/
      $IndigoModel= new IndigoModel();
      $IndigoModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response, 'Book', 'INDIGO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/


       /*---------------Start Fare Rule Request ----------------*/
       $farerule_Result=array();
      $fareruleinput=array(
                              'UserIp'        => $input['UserIp'],
                              'ResultIndex'   => $input['ResultIndex'],
                              'SearchTokenId' => $input['SearchTokenId']
                          );
      $FareRuleResponse=Indigo::FareRule($fareruleinput,$common_data,$userauthdata);
      if($FareRuleResponse['Response']['Error']['ErrorCode']==0)
      {
      $farerule_Result=$FareRuleResponse['Response']['Result'];
      }
     /*---------------End Fare Rule Request ----------------*/
      
      $AirCreateReservation_array = Indigoxml2array($response);
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
              $UniversalRecordLocatorCode=$UniversalRecord['LocatorCode'];
              
              if(isset($UniversalRecord['air:AirReservation']['air:AirPricingInfo']))
		        	{
                   Indigo::UpdateBookingData($UniversalRecord,$booking_lastid,$account_log_lastid,$farerule_Result,$CurrencyType,$FareConfirmPublishedPrice);
                   Indigo::GetBookingDetails($input,$UniversalRecordLocatorCode,$userauthdata);

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
        $booking_data=$IndigoModel->verify_booking_detail(['tts_search_token'=>$input['SearchTokenId'],"id"=>$booking_lastid]);
        $tts_response = ConvertBookingDetail($input,$booking_data);
      } else {
        $booking_status='Processing';
        $booking_update_data=array('booking_status' => $booking_status,'fare_rule'=> json_encode($farerule_Result),);
        $IndigoModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
        $IndigoModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid], array('booking_status' => $booking_status));

        $tts_response = array(
                                'UserIp'        => $input['UserIp'],
                                'SearchTokenId' => $tts_search_token,
                                'Error'         => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                                'Result'        => $TTS_Result
                             );
      }
      return $tts_response;
    }

    public function UpdateBookingData($UniversalRecord,$booking_lastid,$account_log_lastid,$fare_rule,$currency_type,$previous_fare)
    {
            $IndigoModel= new IndigoModel();
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

            $airline_pnr=array();            
            $universal_pnr=$UniversalRecord['LocatorCode'];

            $gds_pnr=""; $booking_status="Processing";
            if(isset($UniversalRecord['universal:ProviderReservationInfo']['LocatorCode']))
            {
              $gds_pnr=$UniversalRecord['universal:ProviderReservationInfo']['LocatorCode'];
              $booking_status="Confirmed";
            }
          
            if($previous_fare==$totalFare)
            {
              $is_price_changed=false;
            } else{
              $is_price_changed=true;
            }
            $is_time_changed=false;


            $pax_update_data=array(
                                        'pax_id'            => "",
                                        'ticket_id'         => "",
                                        'ticket_number'     => $gds_pnr,
                                        'validating_airline'=> "",
                                        "booking_status"    => $booking_status
                                  );

            $IndigoModel->updateUserData('flight_booking_travelers', ['flight_booking_id' => $booking_lastid],$pax_update_data);

            $booking_update_data=array(
                                                'is_price_changed'   => $is_price_changed,
                                                'is_time_changed'    => $is_time_changed,
                                                'supplier_booking_id'=> $universal_pnr,
                                                'booking_status'     => $booking_status,
                                                'fare_rule'          => json_encode($fare_rule),
                                                'pnr'                => $gds_pnr,
                                                'airline_pnr'        => json_encode($airline_pnr)
                                      );
            $IndigoModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
            $PaxName = $Pax_info_array[0]['common_v51_0:BookingTravelerName']['First'] . ' ' . $Pax_info_array[0]['common_v51_0:BookingTravelerName']['Last'] . ' X ' . count($Pax_info_array);
            $Sector = $origin. '-' . $destination;
            $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date,'AirlineString'=>$airline_code.$flight_number,'TicketNo'=>$gds_pnr);
            $account_update_data=array('service_log'    => json_encode($service_log));
            $IndigoModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);     
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
      $response= Indigo_Request($this->UniversalService_URL,$request);   

      /*--------------Start Insert API Logs------------------*/
      $IndigoModel= new IndigoModel();
      $IndigoModel->insert_flight_logs($userauthdata['web_partner_id'], $tts_search_token, $request, $response,'GetBookingDetails', 'INDIGO', strtotime(Time::now($this->GetTimeZone)));
      /*--------------End Insert API Logs--------------------*/

      $UniversalRecordRetrieveReq_array = Indigoxml2array($response);
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

    public function SendCancelRequest(array $input,$common_data,array $userauthdata)
    {
       $tts_search_token=$input['SearchTokenId'];
       if($input['RequestType']=='FullCancellation')
       {

       }
       if($input['RequestType']=='PartialCancellation')
       {
          
       }

       $AirReservationLocatorCode=$common_data['pnr'];

       $request='<?xml version="1.0" encoding="utf-8"?>
       <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" xmlns:air="http://www.travelport.com/schema/air_v51_0">
         <soapenv:Header>
           <univ:SupportedVersions/>
         </soapenv:Header>
         <soapenv:Body>
             <AirCancelReq Version="2" xmlns="http://www.travelport.com/schema/universal_v51_0" TraceId="'.$this->TraceId.'" AuthorizedBy="'.$this->AuthorizedBy.'" TargetBranch="'.$this->TargetBranch.'">
             <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v51_0" OriginApplication="UAPI" />
             <AirReservationLocatorCode xmlns="http://www.travelport.com/schema/air_v51_0">'.$AirReservationLocatorCode.'</AirReservationLocatorCode>
           </AirCancelReq>
         </soapenv:Body>
       </soapenv:Envelope>';

       $response= Indigo_Request($this->AirService_URL,$request);  
     

       $ErrorCode=1;
       $ErrorMessage='Air Service cancel getting error from supplier';

       $tts_response = array(
                                'UserIp'        => $input['UserIp'],
                                'SearchTokenId' => $tts_search_token,
                                'Error'         => array("ErrorCode" =>$ErrorCode, "ErrorMessage" => $ErrorMessage),
                                'Result'        => array()
                            );
      return $tts_response;
    }

}
