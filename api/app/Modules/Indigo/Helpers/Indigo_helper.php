<?php
use Config\APIConfig;

/**
 * ----------------------------------------------
 * Indigo API Request 
 * ---------------------------------------------
 */
function Indigo_Request($soapurl,$message)
{
    $apiconfig= new APIConfig;
    $credential=$apiconfig->travelport_indigo_credential;
    $auth=base64_encode($credential['UserName'].':'.$credential['Password']);

    $soap_do = curl_init ($soapurl);
    $header = array(
                    "Connection: Keep-Alive", 
                    "Content-Type: text/xml;charset=UTF-8", 
                    "Accept: gzip,deflate", 
                    "Cache-Control: no-cache", 
                    "Pragma: no-cache", 
                    "SOAPAction: \"\"",
                    "Authorization: Basic $auth", 
                    "Content-length: ".strlen($message),
                    ); 		
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($soap_do, CURLOPT_POST, true ); 
    curl_setopt($soap_do, CURLOPT_ENCODING, 'gzip');
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 60);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); 
    return curl_exec($soap_do);
}

function IndigoGenerateRequest($input,$tts_search_token,$userauthdata,$AuthorizedBy,$TargetBranch,$AirService_URL,$Credentials,$type=null)
{
		  $TraceId=$tts_search_token;
          $journeytype = $input["JourneyType"];
          $userip = $input["UserIp"];
          $no_adult = $input["Adult"];
          $no_child = $input["Child"];
          $no_infants = $input["Infant"];
          $class = IndigoCabinclass($input["CabinClass"]);
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

          $set_Provider='<com:Provider Code="ACH" />';
          $AirPricingModifiers='<air:AirPricingModifiers/>';

          $classxml = "";
          if($class !== "") {
                $classxml = '<air:AirLegModifiers>
                                <air:PreferredCabins>
                                <com:CabinClass Type="' . $class . '" />
                                </air:PreferredCabins>
                             </air:AirLegModifiers>';
          }

          $SearchAirLeg = "";
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
              $adultxml .= '<com:SearchPassenger Code="ADT" />';
          }

          $childxml = "";
          for ($j = 1; $j <= $no_child; $j++) {
              $childxml .= '<com:SearchPassenger Code="CHD" Age="10" />';
          }

          $infantxml = "";
          for ($k = 1; $k <= $no_infants; $k++) {
              $infantxml .= '<com:SearchPassenger Code="INF" Age="1" />';
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
                            <air:LowFareSearchReq xmlns:air="http://www.travelport.com/schema/air_v51_0" xmlns:com="http://www.travelport.com/schema/common_v51_0" AuthorizedBy="' . $AuthorizedBy . '" TargetBranch="' . $TargetBranch . '" TraceId="' . $TraceId . '">
                                    <com:BillingPointOfSaleInfo OriginApplication="UAPI" />
                                      ' . $SearchAirLeg . '
                                        <air:AirSearchModifiers IncludeFlightDetails="true">
                                              <air:PreferredProviders>
                                                      '.$set_Provider.'
                                              </air:PreferredProviders>
                                              ' . $preferred_airlinexml . '
                                              ' . $nonstopxml . '
                                        </air:AirSearchModifiers>
                                          ' . $adultxml . '
                                          ' . $infantxml . '
                                          ' . $childxml . ' 
                                          ' . $AirPricingModifiers.'
                            </air:LowFareSearchReq>
                    </soapenv:Body>
          </soapenv:Envelope>';
          return array('Supplier'=>'INDIGO','Credentials'=>$Credentials,'URL'=>$AirService_URL,'Request'=>$request,'JourneyIdentifiers'=>$type);
}

function IndigoCabinclass($cabinval) 
{
        $classtype="";
        switch ($cabinval) {
        case "1":
            $classtype="";
            break;
        case "2":
            $classtype="Economy";
            break;
        case "3":
            $classtype="PremiumEconomy";
            break;
        case "4":
            $classtype="Business";
            break;   
        case "6":
            $classtype="First";
            break;
        default:
            $classtype="Economy";			
    }
    return $classtype;
}

function Indigoxml2array($contents, $get_attributes=1)
{ 
		if(!$contents) return array(); 
		if(!function_exists('xml_parser_create'))
		{ 
			return array(); 
		} 
		$parser = xml_parser_create(); 
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
		xml_parse_into_struct( $parser, $contents, $xml_values ); 
		xml_parser_free( $parser ); 
		if(!$xml_values) return;
		$xml_array = array(); 
		$parents = array(); 
		$opened_tags = array(); 
		$arr = array(); 
		$current = &$xml_array; 
		foreach($xml_values as $data)
		{ 
			unset($attributes,$value);
			extract($data);
			$result = ''; 
			if($get_attributes)
			{
				$result = array(); 
				if(isset($value)) $result['value'] = $value; 
				if(isset($attributes))
				{ 
					foreach($attributes as $attr => $val)
					{ 
						if($get_attributes == 1) $result[$attr] = $val; 
					} 
				} 
			}
			elseif(isset($value))
			{ 
				$result = $value; 
			} 
			if($type == "open")
			{
				$parent[$level-1] = &$current; 
				if(!is_array($current) or (!in_array($tag, array_keys($current))))
				{ 
					$current[$tag] = $result; 
					$current = &$current[$tag]; 
				}
				else
				{ 
					if(isset($current[$tag][0]))
					{				
						array_push($current[$tag], $result); 
					}
					else
					{ 
						$current[$tag] = array($current[$tag],$result); 
					} 
					$last = count($current[$tag]) - 1; 
					$current = &$current[$tag][$last]; 
				} 
			}
			elseif($type == "complete")
			{ 
				if(!isset($current[$tag]))
				{
					$current[$tag] = $result;
				}
				else
				{ 
					if((is_array($current[$tag]) and $get_attributes == 0)
						or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1))
					{ 
						array_push($current[$tag],$result); 
					}
					else
					{ 
						$current[$tag] = array($current[$tag],$result); 
					} 
				} 
			}
			elseif ($type == 'close')
			{  
				$current = &$parent[$level-1]; 
			} 
		} 
		return($xml_array); 
}

function IndigoGetPaxType($type)
{
	switch ($type) {
		case "ADT":
			$paxtype='Adult';
			break;
		case "CHD":
			$paxtype='Child';
			break;
		case "INF":
			$paxtype='Infant';
			break;
	}
	return $paxtype;
}
function IndigoGetPaxCode($type)
{
	switch ($type) {
		case "ADT":
			$paxtype='ADT';
			break;
		case "CHD":
			$paxtype='CHD';
			break;
		case "INF":
			$paxtype='INF';
			break;
	}
	return $paxtype;
}

function Indigodatetime_format($date)
{
	$d=explode('.',$date);
	return $d[0];
}

function Indigocheck_attribute_exist($arr,$element,$char=NULL)
{
	if(isset($arr[$element]))
	{
		$value=$char.' '.$arr[$element];
	} else {
		$value=NULL;
	}
	return $value;
}

function IndigoListFareInfoList($key,$FareInfoList,$FareInfoListKeys)
{
	$FareInfoListarray=array();
	if(isset($FareInfoList['key']))
	{
		$FareInfoListarray=array($FareInfoList);
	} elseif(isset($FareInfoList['Key'])) { 
		$FareInfoListarray=array($FareInfoList);
	} else { 
		$FareInfoListarray=$FareInfoList;
	}
	$IndexKey=array_search($key,$FareInfoListKeys);
	return $FareInfoList[$IndexKey];
}

function Indigobaggage_unit_check($value)
{
	switch ($value) {
		case "Kilograms":
			$returnval="KG";
			break;
		default:
		   $returnval=$value;
	}
	return $returnval;
}


/**
 * ----------------------------------------------------------------------------
 * Get Flight Fare Apply Superadmin Markup, Discount, Calculate GST And TDS
 * -----------------------------------------------------------------------------
 */

function Indigo_get_flight_fare($input,$CommonInfo,$markup_data,$discount_data,$AirPricePoint,$userauthdata,$super_admin_gst_state_code,$service=null) {

	$AirPricingInfos=array();
	if (isset($AirPricePoint['air:AirPricingInfo'][0]))
	{
	   $AirPricingInfos = $AirPricePoint['air:AirPricingInfo'];
	} else {
	   $AirPricingInfos = array($AirPricePoint['air:AirPricingInfo']);
	}

	if(isset($AirPricingInfos[0]['air:TaxInfo'][0]))
	{
		$airline_code=$AirPricingInfos[0]['air:TaxInfo'][0]['SupplierCode'];
	} else{
		$airline_code=$AirPricingInfos[0]['air:TaxInfo']['SupplierCode'];
	}
	$currency_type=$CommonInfo['CurrencyType'];
	$currency_length=strlen($currency_type);
	$price=array();

	$totalpax=intval($input['Adult'])+intval($input['Child']);

    $BaseFare=0; $Tax=0; $YQTax=0; $OtherCharges=0; $Discount=0; $PublishedPrice=0; $OfferedPrice=0; $AgentCommission=0; $ServiceCharges=0; $TDS=0;


	if(substr($AirPricePoint['BasePrice'],0,$currency_length)==$currency_type)
	{
		$BasePrice = substr($AirPricePoint['BasePrice'], $currency_length);
	} else {
		$BasePrice = substr($AirPricePoint['ApproximateBasePrice'], $currency_length);
	}
	
	$price=array(
					'BasePrice' 	 => floatval($BasePrice),
					'Tax'	   		 => floatval(substr($AirPricePoint['Taxes'], $currency_length)),
					'PublishedPrice' => floatval(substr($AirPricePoint['TotalPrice'], $currency_length)),
				);
	$Tax=$price['Tax'];
	
	$markup_value=0; $discount_value=0;$extra_discount=0; $commission=0; $totalgst=0;
    $sel_markup_data=array(); $sel_discount_data=array();
    if($markup_data)
    {
        $supplier_array =array_column($markup_data, 'supplier');
        if(in_array('INDIGO', $supplier_array))
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
    $PublishedPrice=round_value($price['PublishedPrice']+$markup_value+$totalgst);

	unset($GST['TotalGSTAmount']);

    if($discount_data)
    {
        $supplier_array =array_column($discount_data, 'supplier');
        if(in_array('INDIGO', $supplier_array))
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

            $SupplierCommission=0;
            if($sel_discount_data['discount_type']=='percent')
            {
                $commission=round_value(($SupplierCommission*$sel_discount_data['value'])/100);
            } else {
                $commission=round_value($sel_discount_data['value']);
            }
            if($sel_discount_data['extra_discount'])
            {
                $extra_discount=round_value($sel_discount_data['extra_discount']);
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

     $BaseFare=$price['BasePrice'];
     $Discount=$extra_discount;
     $AgentCommission=$commission;

     /*-- calculate tds-- */
     $TDS=tds_calculate($discount_value);

	$per_pax_markup=($markup_value/$totalpax);	
	$FareBreakdown=array();

	 /* Start AirPricePointLists per pax wise */
	 $farerulekey=array();
	 foreach($AirPricingInfos as $pax_key=>$AirPricingInfo) 
	 {
		 if(isset($AirPricingInfo['air:PassengerType'][0]['Code']))
		 {
		   $paxtype=IndigoGetPaxCode($AirPricingInfo['air:PassengerType'][0]['Code']);
		   $noofpax=count($AirPricingInfo['air:PassengerType']);
		 } else {
		   $paxtype=IndigoGetPaxCode($AirPricingInfo['air:PassengerType']['Code']);
		   $noofpax=1;
		 }

		 if($service=='FareConfirmation') {
		  /*-----------------Start For Fare Rule key --------------*/
		  $FareInfoRef=array();
		  if(isset($AirPricingInfo['air:FareInfoRef']['Key']))
		  {
				$FareInfoRef=array($AirPricingInfo['air:FareInfoRef']);
		  } elseif(isset($AirPricingInfo['air:FareInfoRef'])) {
				$FareInfoRef=$AirPricingInfo['air:FareInfoRef'];
		  } else {
				if(isset($AirPricingInfo['air:FareInfo']['Key']))
				{
					$FareInfoRef=array($AirPricingInfo['air:FareInfo']);
				} else {
					$FareInfoRef=$AirPricingInfo['air:FareInfo'];
				}
		  }
		  foreach($FareInfoRef as $farekey)
		  {
			if(isset($CommonInfo['FareInfoListKeys']) && isset($CommonInfo['FareInfoList']))
			{
				$FareInfoIndexKey=array_search($farekey['Key'],$CommonInfo['FareInfoListKeys']);
				$GetFareInfo=$CommonInfo['FareInfoList'][$FareInfoIndexKey];	
			} else{
				$GetFareInfo=$farekey;
			}
			$rulearray=array(
								'Airline'           => $airline_code,
								'FareBasis'         => $GetFareInfo['FareBasis'],
								'PassengerTypeCode' => $GetFareInfo['PassengerTypeCode'],
								'Origin'			=> $GetFareInfo['Origin'],
								'Destination'	    => $GetFareInfo['Destination'],
								'FareRuleKey'		=> $GetFareInfo['air:FareRuleKey']
							);
		    array_push($farerulekey,$rulearray);
		  }
		 /*-------------------End For Fare Rule key -------------------------*/
		}

		 $paxwise_markup_value=0;
		 $per_pax_ServiceCharges=0;
		 if($paxtype!='INF') 
		 {
			  if($sel_markup_data && $sel_markup_data['display_markup']=='in_tax')
			  {
				  $paxwise_markup_value=round_value($per_pax_markup*$noofpax);
			  } elseif($sel_markup_data && $sel_markup_data['display_markup']=='in_service_charge')
			  {
				  $per_pax_ServiceCharges=round_value($per_pax_markup*$noofpax);
			  } 
		 }

		if(substr($AirPricingInfo['BasePrice'],0,$currency_length)==$currency_type)
		{
			$PerPaxBaseFare = floatval(substr($AirPricingInfo['BasePrice'], $currency_length));
		} else {
			$PerPaxBaseFare = floatval(substr($AirPricingInfo['ApproximateBasePrice'], $currency_length));
		}
		if(isset($AirPricingInfo['air:TaxInfo']))	
		{
			$YQ=array_search('YQ',array_column($AirPricingInfo['air:TaxInfo'], 'Category','Amount'));
			if(isset($YQ))
			{
				$PerPaxYQTax=floatval(substr($YQ, $currency_length));
			} else {
				$PerPaxYQTax=0;
			}
		} else {
				$PerPaxYQTax=0;
		}

		 if($paxtype=='INF') 
		 {
			$Fees=floatval(substr($AirPricingInfo['Fees'], $currency_length));
			$PerPaxBaseFare=floatval($Fees *$noofpax);
			$PerPaxYQTax=floatval($PerPaxYQTax*$noofpax);
			$PerPaxTax =0;

			$BaseFare=floatval($BaseFare)+floatval($PerPaxBaseFare);
			
		 } else {
			$PerPaxBaseFare=floatval($PerPaxBaseFare *$noofpax);
			$PerPaxYQTax=floatval($PerPaxYQTax*$noofpax);
			$PerPaxTax =floatval(substr($AirPricingInfo['Taxes'], $currency_length));
		 }
		
		 $YQTax+=$PerPaxYQTax;
		

		 $FareBreakdown[$paxtype]=array(
											'PassengerCount' => $noofpax,
											'BaseFare'       => $PerPaxBaseFare,
											'Tax'            => $PerPaxTax+$paxwise_markup_value,
											'YQTax'          => $PerPaxYQTax,
											'ServiceCharges' => $per_pax_ServiceCharges
		  							   );
	 }
	 /* End AirPricePointLists per pax wise */
	 $price['YQTax']=$YQTax;

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
		'GST'            => $GST,
	 );

	 
	 $SuperAdminFareBreakup=array();
	 $WebPartnerFareBreakup=array();
	 if($service=='FareConfirmation')
	 {
		 $SupplierCommission=0;
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
										 'BaseFare'             => $price['BasePrice'],
										 'Tax'                  => $price['Tax'],
										 'YQTax'                => $price['YQTax'],
										 'OtherCharges'         => 0,
										 'Discount'             => $SupplierCommission,
										 'PublishedPrice'       => $price['PublishedPrice'],
										 'OfferedPrice'         => $price['PublishedPrice'],
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

	 return array('Fare'=>$tts_flight_breakup,'FareBreakdown'=>$FareBreakdown,'SuperAdminFareBreakup'=>$SuperAdminFareBreakup,'WebPartnerFareBreakup'=>$WebPartnerFareBreakup,'farerulekey'=>$farerulekey,"airline_code"=>$airline_code);

}
function Indigo_get_flight_fare_with_discount($discount_data,$fare,$SupplierCommissionData)
{
	$discount_value=0;$extra_discount=0; $commission=0;$sel_discount_data=array();
	$SupplierCommission = $SupplierCommissionData['SupplierCommission'];
	$airline_code=$fare['airline_code'];
	if($discount_data)
    {
        $supplier_array =array_column($discount_data, 'supplier');
        if(in_array('INDIGO', $supplier_array))
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
            if($sel_discount_data['discount_type']=='percent')
            {
                $commission=round_value(($SupplierCommission*$sel_discount_data['value'])/100);
            } else {
                $commission=round_value($sel_discount_data['value']);
            }
            if($sel_discount_data['extra_discount'])
            {
                $extra_discount=round_value($sel_discount_data['extra_discount']);
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
	$price = $fare['Fare'];
	$OfferedPrice=round_value(($price['PublishedPrice'])-($commission+$extra_discount));
      if ($OfferedPrice > 0)
      { 

      } elseif ($OfferedPrice == 0){
          $commission=0;
          $extra_discount=0;
          $discount_value=0;
          $OfferedPrice=$price['PublishedPrice'];

      } elseif ($OfferedPrice < 0) {

          $commission=0;
          $extra_discount=0;
          $discount_value=0;
          $OfferedPrice=$price['PublishedPrice'];
      }

     $Discount=$extra_discount;
     $AgentCommission=$commission;

     /*-- calculate tds-- */
     $TDS=tds_calculate($discount_value);
	 $price['Discount'] =  $Discount;
	 $price['AgentCommission'] =  $AgentCommission;
	 $price['TDS'] =  $TDS;
	 $price['OfferedPrice'] =   $OfferedPrice;
	 $SuperAdminFareBreakup =  $fare['SuperAdminFareBreakup'];
	 $WebPartnerFareBreakup =  $fare['WebPartnerFareBreakup'];
	 $discount_type='';
	 if(isset($sel_discount_data['discount_type']))
	 {
		 $discount_type=$sel_discount_data['discount_type'];
	 }
	 if(isset($fare['SuperAdminFareBreakup']) && !empty($fare['SuperAdminFareBreakup']))
	 {
		$SuperAdminFareBreakup['Discount'] =  $SupplierCommission;
		$SuperAdminFareBreakup['SUP_Discount'] =  $commission;
		$SuperAdminFareBreakup['SUP_ExtraDiscount'] =  $extra_discount;
		$SuperAdminFareBreakup['SUP_DiscountType'] =  $discount_type;
		$SuperAdminFareBreakup['OfferedPrice'] =   $price['PublishedPrice']-$SupplierCommission;
		$SuperAdminFareBreakup['DealInfo'] =   $SupplierCommissionData['DealInfo'];  
		$WebPartnerFareBreakup =  $price;
	 }
	 $fare['Fare'] =  $price;
	 $fare['SuperAdminFareBreakup'] =  $SuperAdminFareBreakup;
	 $fare['WebPartnerFareBreakup'] =  $WebPartnerFareBreakup;
	 unset($airline_code);
	 return $fare;
}

function IndigoGenerateCombinations($data, &$all = array(), $group = array(), $val = null, $i = 0) {
	if (isset($val)) {
		array_push($group, $val);
	}
	if ($i >= count($data)) {
		array_push($all, $group);
	} else {
		foreach ($data[$i] as $v) {
			IndigoGenerateCombinations($data, $all, $group, $v, $i + 1);
		}
	}
	return $all;
}

function Indigocustom_pax_sort($array)
{
    $order = array(1,3,2);
    usort($array, function ($a, $b) use ($order) {
        $pos_a = array_search($a, $order);
        $pos_b = array_search($b, $order);
        return $pos_a - $pos_b;
    });
    return $array;
}

function IndigoGeneratPaxRequest($input,$airseg_airlines,$AuthorizedBy,$PaxBookingTravelerRef)
{
	$key_values= array_column($input['Passengers'], 'PaxType');
	$key_values=Indigocustom_pax_sort($key_values);
	array_multisort($key_values, SORT_ASC, $input['Passengers']);
	$primary_airline=$airseg_airlines[0];

	$BookingTraveler="";
	$a=0; $c=0; $i=0;
	foreach($input['Passengers'] as $key=>$Passenger)
	{

			$PaxType=IndigoTravelerType($Passenger['PaxType']);


			$BookingTravelerRef="";
			if($PaxType=="ADT")
			{
				$BookingTravelerRef=$PaxBookingTravelerRef[$PaxType][$a];
				$a++;
				
			} else if($PaxType=="CHD")
			{
				$BookingTravelerRef=$PaxBookingTravelerRef[$PaxType][$c];
				$c++;				
			} else if($PaxType=="INF")
			{
				$BookingTravelerRef=$PaxBookingTravelerRef[$PaxType][$i];
				$i++;
			}
			

			$Gender=IndigoGenderType($Passenger['Title'],$PaxType);
			$Prefix=IndigoPrefix($Passenger['Title'],$PaxType);
			$First_name=$Passenger['FirstName'];
			$Last_name=$Passenger['LastName'];
			
			$AddressLine1=$Passenger['AddressLine1'];
			$AddressLine2=$Passenger['AddressLine2'];
			$City=$Passenger['City'];
			$CountryCode=$Passenger['CountryCode'];
			$CountryName=$Passenger['CountryName'];
			$ContactNo=$Passenger['ContactNo'];
			$Email=$Passenger['Email'];

			$paxkey=$key+1;
		

			$gst_html="";
			if($Passenger['GSTCompanyAddress'] && $Passenger['GSTCompanyContactNumber'] && $Passenger['GSTCompanyName'] && $Passenger['GSTNumber'] && $Passenger['GSTCompanyEmail'])
			{
				$GSTCompanyName=$Passenger['GSTCompanyName'];
				$GSTA=$Passenger['GSTCompanyAddress'];
				$GSTN=$Passenger['GSTNumber'];
				$GSTP=$Passenger['GSTCompanyContactNumber'];
				$GSTE=str_replace('@','//',$Passenger['GSTCompanyEmail']);

				if($airseg_airlines)
				{
					foreach($airseg_airlines as $airkey=>$Carrierinfo) 
					{
					$gst_html.='<com:SSR Key="'.IndigoAutoGenerateKey($PaxType,$paxkey,$airkey,2).'" Type="GSTN" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="/IND/'.$GSTN.'/'.$GSTCompanyName.'"/>
					<com:SSR Key="'.IndigoAutoGenerateKey($PaxType,$paxkey,$airkey,4).'" Type="GSTE" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="/IND/'.$GSTE.'"/>';
					}
				}
					
			}
			$Passport_html="";
			if($Passenger['PassportNo'] && $Passenger['PassportExpiry'])
			{
				$country_code=$Passenger['CountryCode'];
				$passport_no=$Passenger['PassportNo'];
				$passport_DOB=Indigoconvert_date_format($Passenger['DateOfBirth']);
				$passport_expiry=Indigoconvert_date_format($Passenger['PassportExpiry']);
				$first_name=$Passenger['FirstName'];
				$last_name=$Passenger['LastName'];
				if($PaxType=="INF")
				{
					if($Gender=="M")
					{
						$PassportGender="MI";
					} else {
						$PassportGender="FI";
					}
				} else {
					$PassportGender=$Gender;
				}

				if($airseg_airlines)
				{	
					foreach($airseg_airlines as $Carrierinfo) 
					{
						$Passport_html.='<com:SSR Type="DOCS" Status="HK" FreeText="P/'.$country_code.'/'.$passport_no.'/'.	$country_code.'/'.$passport_DOB.'/'.$PassportGender.'/'.$passport_expiry.'/'.$last_name.'/'.$first_name.'" Carrier="'.$Carrierinfo.'"/>';
					}
				}
			}

			$age=date_diff(date_create(Indigodate_without_time($Passenger['DateOfBirth'])), date_create('today'))->y;
			if(strlen($age)==1)
			{
					$age="0".$age;
			}
			$DOB=Indigodate_without_time($Passenger['DateOfBirth']);

			if($Passenger['IsLeadPax'])
			{
					$CTCE=str_replace('@','//',$Email);		
					$BookingTraveler.='<com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v51_0" Key="'.$BookingTravelerRef.'" TravelerType="'.$PaxType.'" Gender="'.$Gender.'" Age="'.$age.'" DOB="'.$DOB.'">
							<com:BookingTravelerName Prefix="'.$Prefix.'" Last="'.$Last_name.'" First="'.$First_name.'" />
							<com:PhoneNumber Number="'.$ContactNo.'" Type="Mobile" Text="'.$First_name.' PAX CTCM '.$ContactNo.'"/>
							<com:Email EmailID="'.$Email.'" Type="P"/>
							'.$Passport_html.'
							'.$gst_html.'
							 <com:Address>
								<com:AddressName>'.$AuthorizedBy.'</com:AddressName>
								<com:Street>'.$AddressLine1.'</com:Street>
								<com:City>'.$City.'</com:City>
								<com:State>Uttar Pradesh</com:State>
								<com:PostalCode>201309</com:PostalCode>
								<com:Country>'.$CountryCode.'</com:Country>
							 </com:Address>
						</com:BookingTraveler>';
			} else {
					$CTCE=str_replace('@','//',$Email);
				
					$BookingTraveler.='<com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v51_0" Key="'.$BookingTravelerRef.'" TravelerType="'.$PaxType.'" Gender="'.$Gender.'" Age="'.$age.'" DOB="'.$DOB.'">
									<com:BookingTravelerName Prefix="'.$Prefix.'" Last="'.$Last_name.'" First="'.$First_name.'" />
									'.$Passport_html.'
									'.$gst_html.'
									 <com:Address/>
								</com:BookingTraveler>';
			}


			

	}
	return array('BookingTraveler'=>$BookingTraveler);
}


function IndigoTravelerType($PaxType)
{
	 switch ($PaxType) {
			case 1:
				$traveler_type="ADT";
				break;
			case 2:
				$traveler_type="CHD";
				break;
			case 3:
				$traveler_type="INF";
				break;		
		}
		return $traveler_type;
}

function IndigoGenderType($title,$PaxType)
{
		switch($title) {
			case "Mr":
				$titletype="M";
				break;
			case "Mrs":
				$titletype="F";
				break;
			case "Miss":
				$titletype="F";
				break;   
			case "Ms":
				$titletype="F";
				break;
			case "Mstr":
				$titletype="M";
				break;	
		}
	  return $titletype;
}

function IndigoPrefix($title,$PaxType)
{
	$title=strtoupper($title);
	if($PaxType=="ADT")
	{
		return $title;
	} else if($PaxType=="CHD")
	{
		return "MASTER";
	} else if($PaxType=="INF")
	{
		return "MASTER";
	}
	
}

function Indigodate_without_time($date) { 
	$date=explode("T",$date);
	return $date[0];
}

function Indigoconvert_date_format($date) { 
	$date=explode("T",$date);
	return date("dMy",strtotime($date[0]));
}

function IndigoAutoGenerateKey($paxtype,$paxkey,$airkey,$sequence)
{
	$key=$paxtype.$paxkey.$airkey.$sequence.'k';
	return strtolower($key);
}

