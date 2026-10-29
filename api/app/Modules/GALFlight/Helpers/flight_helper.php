<?php
use Config\APIConfig;

/**
 * ----------------------------------------------
 * Galileo API Request 
 * ---------------------------------------------
 */
function GAL_Request($soapurl,$message)
{
    $apiconfig= new APIConfig;
    $credential=$apiconfig->travelport_credential;
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

function Cabinclass($cabinval)
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

function xml2array($contents, $get_attributes=1)
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

function GetPaxType($type)
{
	switch ($type) {
		case "ADT":
			$paxtype='Adult';
			break;
		case "CNN":
			$paxtype='Child';
			break;
		case "INF":
			$paxtype='Infant';
			break;
	}
	return $paxtype;
}
function GetPaxCode($type)
{
	switch ($type) {
		case "ADT":
			$paxtype='ADT';
			break;
		case "CNN":
			$paxtype='CHD';
			break;
		case "INF":
			$paxtype='INF';
			break;
	}
	return $paxtype;
}
function GetPaxCodeNumber($type)
{
	switch ($type) {
		case "1":
			$paxtype='ADT';
			break;
		case "2":
			$paxtype='CHD';
			break;
		case "3":
			$paxtype='INF';
			break;
	}
	return $paxtype;
}

function datetime_format($date)
{
	$d=explode('.',$date);
	return $d[0];
}

function check_attribute_exist($arr,$element,$char=NULL)
{
	if(isset($arr[$element]))
	{
		$value=$char.' '.$arr[$element];
	} else {
		$value=NULL;
	}
	return $value;
}

function ListFareInfoList($key,$FareInfoList,$FareInfoListKeys)
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

function baggage_unit_check($value)
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

function Gal_get_flight_fare($input,$CommonInfo,$markup_data,$discount_data,$AirPricePoint,$userauthdata,$super_admin_gst_state_code,$service=null) {

	$AirPricingInfos=array();
	if (isset($AirPricePoint['air:AirPricingInfo'][0]))
	{
	   $AirPricingInfos = $AirPricePoint['air:AirPricingInfo'];
	} else {
	   $AirPricingInfos = array($AirPricePoint['air:AirPricingInfo']);
	}
	$airline_code='';
	if(isset($AirPricingInfos[0]['PlatingCarrier']))
	{
		$airline_code=$AirPricingInfos[0]['PlatingCarrier'];
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
        if(in_array('TRAVELPORT', $supplier_array))
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
	  /* check discount value not greater then publish price , in case of discount value greater than publish price discount value is automatically set zero */
      $OfferedPrice=round_value(($PublishedPrice)-($commission+$extra_discount));


     $BaseFare=$price['BasePrice'];
     $Discount=$extra_discount;
     $AgentCommission=$commission;
	$per_pax_markup=($markup_value/$totalpax);	
	$FareBreakdown=array();

	 /* Start AirPricePointLists per pax wise */
	 $farerulekey=array();
	 foreach($AirPricingInfos as $pax_key=>$AirPricingInfo) 
	 {
		 if(isset($AirPricingInfo['air:PassengerType'][0]['Code']))
		 {
		   $paxtype=GetPaxCode($AirPricingInfo['air:PassengerType'][0]['Code']);
		   $noofpax=count($AirPricingInfo['air:PassengerType']);
		 } else {
		   $paxtype=GetPaxCode($AirPricingInfo['air:PassengerType']['Code']);
		   $noofpax=1;
		 }

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
		$PerPaxBaseFare=floatval($PerPaxBaseFare *$noofpax);
		$PerPaxYQTax=floatval($PerPaxYQTax*$noofpax);
		$PerPaxTax =floatval(substr($AirPricingInfo['Taxes'], $currency_length));
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
		'GST'            => $GST
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
function Gal_get_flight_fare_with_discount($discount_data,$fare,$SupplierCommissionData)
{
	$discount_value=0;$extra_discount=0; $commission=0;$sel_discount_data=array();
	$SupplierCommission = $SupplierCommissionData['SupplierCommission'];
	$airline_code=$fare['airline_code'];
	if($discount_data)
    {
        $supplier_array =array_column($discount_data, 'supplier');
        if(in_array('TRAVELPORT', $supplier_array))
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

function GenerateCombinations($data, &$all = array(), $group = array(), $val = null, $i = 0) {
	if (isset($val)) {
		array_push($group, $val);
	}
	if ($i >= count($data)) {
		array_push($all, $group);
	} else {
		foreach ($data[$i] as $v) {
			GenerateCombinations($data, $all, $group, $v, $i + 1);
		}
	}
	return $all;
}

function custom_pax_sort($array)
{
    $order = array(1,3,2);
    usort($array, function ($a, $b) use ($order) {
        $pos_a = array_search($a, $order);
        $pos_b = array_search($b, $order);
        return $pos_a - $pos_b;
    });
    return $array;
}

function GeneratPaxRequest($input,$airseg_airlines,$AuthorizedBy)
{
	$key_values= array_column($input['Passengers'], 'PaxType');
	$key_values=custom_pax_sort($key_values);
	array_multisort($key_values, SORT_ASC, $input['Passengers']);
	$primary_airline=$airseg_airlines[0];

	$BookingTraveler="";
	$replacements_adult=""; $replacements_child=""; $replacements_infant="";
	$searches_adult=""; $searches_child=""; $searches_infant="";

	foreach($input['Passengers'] as $key=>$Passenger)
	{

			$PaxType=TravelerType($Passenger['PaxType']);
			$Gender=GenderType($Passenger['Title'],$PaxType);
			$Prefix=Prefix($Passenger['Title'],$PaxType);
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
			$Loyaltypoint="";
			if($Passenger['FFAirline'] && $Passenger['FFNumber'])
			{
				$FFAirline=$Passenger['FFAirline'];
				$FFNumber=$Passenger['FFNumber'];
				$Loyaltypoint='<com:LoyaltyCard SupplierType="Air" CardNumber="'.$FFNumber.'" SupplierCode="'.$FFAirline.'" Key="'.$paxkey.'" />';
			}
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
					$gst_html.='<com:SSR Key="'.AutoGenerateKey($PaxType,$paxkey,$airkey,1).'" Type="GSTA" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="IND/'.$GSTA.'" />
					<com:SSR Key="'.AutoGenerateKey($PaxType,$paxkey,$airkey,2).'" Type="GSTN" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="IND/'.$GSTN.'/'.$GSTCompanyName.'" />
					<com:SSR Key="'.AutoGenerateKey($PaxType,$paxkey,$airkey,3).'" Type="GSTP" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="IND/'.$GSTP.'" />
					<com:SSR Key="'.AutoGenerateKey($PaxType,$paxkey,$airkey,4).'" Type="GSTE" Carrier="'.$Carrierinfo.'" Status="NN" FreeText="IND/'.$GSTE.'" />';
					}
				}
					
			}
			$Passport_html="";
			if($Passenger['PassportNo'] && $Passenger['PassportExpiry'])
			{
				$country_code=$Passenger['CountryCode'];
				$passport_no=$Passenger['PassportNo'];
				$passport_DOB=convert_date_format($Passenger['DateOfBirth']);
				$passport_expiry=convert_date_format($Passenger['PassportExpiry']);
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

			$age=date_diff(date_create(date_without_time($Passenger['DateOfBirth'])), date_create('today'))->y;
			if(strlen($age)==1)
			{
					$age="0".$age;
			}
			$DOB=date_without_time($Passenger['DateOfBirth']);

			if($Passenger['IsLeadPax'])
			{
					$CTCE=str_replace('@','//',$Email);		
					$BookingTraveler.='<com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v51_0" Key="'.$paxkey.'" TravelerType="'.$PaxType.'" Gender="'.$Gender.'" Age="'.$age.'">
							<com:BookingTravelerName Prefix="'.$Prefix.'" Last="'.$Last_name.'" First="'.$First_name.'" />
							<com:DeliveryInfo>
								<com:ShippingAddress>
									<com:AddressName>'.$AuthorizedBy.'</com:AddressName>
									<com:Street>'.$AddressLine1.'</com:Street>
									<com:City>'.$City.'</com:City>
									<com:State>Uttar Pradesh</com:State>
									<com:PostalCode>201309</com:PostalCode>
									<com:Country>'.$CountryCode.'</com:Country>
								</com:ShippingAddress>
							</com:DeliveryInfo>
							<com:PhoneNumber Number="'.$ContactNo.'" />
							<com:Email Type="Home" EmailID="'.$Email.'"/>
							'.$Loyaltypoint.'
							'.$Passport_html.'
							'.$gst_html.'
							 <com:SSR Type="CTCM" Carrier="'.$primary_airline.'" Status="HK" FreeText="'.$ContactNo.'" />
							 <com:SSR Type="CTCE" Carrier="'.$primary_airline.'" Status="HK" FreeText="'.$CTCE.'" />
						</com:BookingTraveler>';
			} else {
					$CTCE=str_replace('@','//',$Email);
					$NameRemark='';
					if($PaxType=="CNN")
					{
						$DOB=strtoupper(convert_date_format($Passenger['DateOfBirth']));
						$NameRemark='<com:NameRemark>
										<com:RemarkData>P-C'.$age.' DOB'.$DOB.'</com:RemarkData>
									 </com:NameRemark>';
									 
					} else if($PaxType=="INF")
					{
						if($age=="00")
						{
						  $age="01";		
						}

						$DOB=strtoupper(convert_date_format($Passenger['DateOfBirth']));
						$NameRemark='<com:NameRemark>
										<com:RemarkData>'.$DOB.'</com:RemarkData>
									 </com:NameRemark>';
					}
					
					$BookingTraveler.='<com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v51_0" Key="'.$paxkey.'" TravelerType="'.$PaxType.'" Gender="'.$Gender.'" Age="'.$age.'">
									<com:BookingTravelerName Prefix="'.$Prefix.'" Last="'.$Last_name.'" First="'.$First_name.'" />
									'.$Passport_html.'
									'.$gst_html.'
									 <com:SSR Type="CTCM" Carrier="'.$primary_airline.'" Status="HK" FreeText="'.$ContactNo.'" />
									 <com:SSR Type="CTCE" Carrier="'.$primary_airline.'" Status="HK" FreeText="'.$CTCE.'" />
									'.$NameRemark.'
									'.$Loyaltypoint.'
								</com:BookingTraveler>';
			}


			if($PaxType=="ADT")
			{
				$replacements_adult.='<air:PassengerType Code="'.$PaxType.'" Age="'.$age.'" BookingTravelerRef="'.$paxkey.'"/>';	
				$searches_adult.='<air:PassengerType Code="ADT"/>';
				
			} else if($PaxType=="CNN")
			{
				$replacements_child.='<air:PassengerType Code="'.$PaxType.'" Age="'.$age.'" BookingTravelerRef="'.$paxkey.'"/>';
				$searches_child.='<air:PassengerType Code="CNN" Age="10"/>';
				
			} else if($PaxType=="INF")
			{
				$replacements_infant.='<air:PassengerType Code="'.$PaxType.'" Age="'.$age.'" PricePTCOnly="true" BookingTravelerRef="'.$paxkey.'"/>';
				$searches_infant.='<air:PassengerType Code="INF" Age="1" PricePTCOnly="true"/>';
			}

	}
	return array('BookingTraveler'=>$BookingTraveler,'searches_adult'=>$searches_adult,'replacements_adult'=>$replacements_adult,'searches_child'=>$searches_child,'replacements_child'=>$replacements_child,'searches_infant'=>$searches_infant,'replacements_infant'=>$replacements_infant);
}


function TravelerType($PaxType)
{
	 switch ($PaxType) {
			case 1:
				$traveler_type="ADT";
				break;
			case 2:
				$traveler_type="CNN";
				break;
			case 3:
				$traveler_type="INF";
				break;		
		}
		return $traveler_type;
}

function GenderType($title,$PaxType)
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

function Prefix($title,$PaxType)
{
	$title=strtoupper($title);
	if($PaxType=="ADT")
	{
		return $title;
	} else if($PaxType=="CNN")
	{
		return "MSTR";
	} else if($PaxType=="INF")
	{
		return "MSTR";
	}
	
}

function date_without_time($date) { 
	$date=explode("T",$date);
	return $date[0];
}

function convert_date_format($date) { 
	$date=explode("T",$date);
	return date("dMy",strtotime($date[0]));
}

function AutoGenerateKey($paxtype,$paxkey,$airkey,$sequence)
{
	$key=$paxtype.$paxkey.$airkey.$sequence.'k';
	return strtolower($key);
}