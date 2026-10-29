<?php

namespace Modules\Airservice\Controllers;

use App\Controllers\BaseController;
use App\Modules\Airservice\Models\RestModel;
use Modules\Airservice\Config\Validation;
use CodeIgniter\I18n\Time;


class Rest extends BaseController
{

    public function __construct()
    {
        helper('Modules\Airservice\Helpers\rest');
        $this->GetTimeZone = app_timezone();

        $RestModel = new RestModel();
        $this->supplier_list = $RestModel->get_api_supplier();
        if ($this->supplier_list) {
            if (isset($this->supplier_list['TBO'])) {
                $this->TBO_Module = new \Modules\TBOFlight\Controllers\TBOFlight();
            }
            if (isset($this->supplier_list['TRAVELPORT'])) {
                $this->TRAVELPORT_Module = new \Modules\GALFlight\Controllers\GALFlight();
            }
            if (isset($this->supplier_list['INDIGO'])) {
                $this->INDIGO_Module = new \Modules\Indigo\Controllers\Indigo();
            }
            if (isset($this->supplier_list['CRS'])) {
                $this->CRS_Module = new \Modules\CRSFlight\Controllers\CRSFlight();
            }
        } else {
            $message = api_validation_message('supplier_inactive_error');
            api_custom_message(400, $message, false);
        }
        ini_set('memory_limit', -1);
    }

    public function Search()
    {
        
        ini_set('memory_limit', '1024M');
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $validate_search = $validate->search_validation($input);
        $this->validation->setRules($validate_search);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $is_dom_type = array();
            $currentdatetime = Time::today();
            if ($input['AirSegments']) {
                foreach ($input['AirSegments'] as $key => $value) {
                    $preferred_time = $value['PreferredTime'];
                    $country_code = $RestModel->get_country_code(array($value['Origin'], $value['Destination']));
                    $country_code = array_column($country_code, 'country_code');
                    if (isset($country_code[0]) && isset($country_code[1])) {
                        if (trim($country_code[0]) == 'IN' && trim($country_code[1]) == 'IN') {
                            $is_dom_type[] = 'true';
                        } else {
                            $is_dom_type[] = 'false';
                        }
                    } else {
                        $message = "Invalid Airport Code";
                        api_custom_message(400, $message, false);
                    }
                    if (strtotime($preferred_time) >= strtotime($currentdatetime)) {
                    } else {
                        $message = "Departure Date can not less than today date";
                        api_custom_message(400, $message, false);
                    }
                }
            }
            if ($is_dom_type) {
                if (in_array('false', $is_dom_type)) {
                    $input['IsDomestic'] = false;
                } else {
                    $input['IsDomestic'] = true;
                }
            }

            // Genrate TTS Search Token 
            $tts_search_token = generate_token();
            $multirequest = array();
            $multiresponse = array();
            $convert_response = array();
            $crsresponse = array();
            $custom_index = array();

            if ($this->supplier_list) {
                if (isset($input['Supplier']) && $input['Supplier'] != "") {
                    if (in_array($input['Supplier'], $this->supplier_list)) {
                        unset($this->supplier_list);
                        $this->supplier_list = array();
                        array_push($this->supplier_list, $input['Supplier']);
                    } else {
                        unset($this->supplier_list);
                        $this->supplier_list = array();
                    }
                }
                if ($this->supplier_list) {
                    foreach ($this->supplier_list as $module) {
                        $loadmodule = $module . '_Module';
                        $Request = $this->$loadmodule->Search($input, $tts_search_token, $this->userauthdata);
                        if ($module != 'CRS') {
                            if ($Request) {
                                array_push($multirequest, $Request);
                            }
                        } else {
                            $crsresponse['CRS'] = $Request;
                        }
                    }
                }
            }

            // $multiresponse = MultiCurl_Request($multirequest);
            $multiresponse = MultiCurl_Request($multirequest);
            $RestModel->db->reconnect(); // refresh DB handle — the curl calls above can run long enough for MySQL to drop an idle connection


            if (isset($crsresponse['CRS']) && !empty($crsresponse)) {
                $crsInfo = array("Supplier" => "CRS", "Request" => json_encode($input), "Response" => json_encode($crsresponse['CRS']));
                array_push($multiresponse, $crsInfo);
                unset($crsresponse['CRS']);
            }

            if ($multiresponse) {
                $insert_data = array();
                $common_data = array();
                $common_data['airline_list'] = static_airline_array_list();
                $common_data['airport_list'] = static_airport_array_list();
                $common_data['airport_timezone'] = array();
                $common_data['super_admin_markup'] = $RestModel->super_admin_markup($this->userauthdata['web_partner_class_id'], $input, $this->supplier_list);

                /* Include and exclude supplier  Process */
                $IncludeExcludeSupplierAirline = $RestModel->get_include_exclude_supplier_airline($input);
                $common_data['super_admin_flight_deal'] = $RestModel->get_super_admin_flight_deal($this->userauthdata['web_partner_class_id'], $input);
                $IncludeExcludesupplierArray = array();
                if ($IncludeExcludeSupplierAirline) {
                    $IncludeExcludesupplierArray = array_column($IncludeExcludeSupplierAirline, 'api_supplier');
                }
                /* Include and exclude supplier End  Process */

                $common_data['super_admin_discount'] = $RestModel->super_admin_discount($this->userauthdata['web_partner_class_id'], $input, $this->supplier_list);
                $common_data['super_admin_gst_state_code'] = $RestModel->super_admin_gst_state_code()['gst_state_code'];
                $common_data['userauthdata'] = $this->userauthdata;
                foreach ($multiresponse as $key => $item) {
                    $loadmodule = $item['Supplier'] . '_Module';
                    if (isset($item['JourneyIdentifiers'])) {
                        $common_data['JourneyIdentifiers'] = $item['JourneyIdentifiers'];
                    }
                    $Return_Response = $this->$loadmodule->ConvertSearchResponse($input, $item['Response'], $convert_response, $custom_index, $common_data);
                    if ($Return_Response) {
                        $convert_response = $Return_Response['convert_response'];
                        $custom_index = $Return_Response['custom_index'];
                        $common_data['airline_list'] = $Return_Response['airline_list'];
                        $common_data['airport_list'] = $Return_Response['airport_list'];
                    }
                    $insert_data[] = array(
                        'web_partner_id' => $this->userauthdata['web_partner_id'],
                        'tts_search_token' => $tts_search_token,
                        'request' => $item['Request'],
                        'response' => $item['Response'],
                        'service' => 'Search',
                        'api_supplier' => $item['Supplier'],
                        'created' => strtotime(Time::now($this->GetTimeZone))
                    );
                }

                $preferredairlines = null;
                if ((isset($input['PreferredCarriers']) && $input['PreferredCarriers'] != null) && is_array($input['PreferredCarriers'])) {
                    $preferredairlines = $input['PreferredCarriers'];
                }

                // Removing Custom Key
                foreach ($convert_response as $Convertkey => $convertResponse) {
                    $ReturnFlightResponse = array();
                    $NoOfStops = array();
                    if (isset($convert_response[$Convertkey])) {
                        $FlightResults = array_values($convertResponse);
                        foreach ($FlightResults as $custom_key => $FlightResult) {

                            if (count($FlightResult['Segments']) == 1) {
                                $first_segment = current($FlightResult['Segments'][0]);
                            } else {
                                $first_segment = current($FlightResult['Segments']);
                                $first_segment = current($first_segment);
                            }
                            $airlineCode = trim($first_segment['Airline']['AirlineCode']);
                            if ($preferredairlines != null && !in_array($airlineCode, $preferredairlines)) {
                                continue;
                            }
                            $AirlineFlightNumberCodeData = array();
                            $airport_timezone = array();
                            $trips = array();
                            foreach ($FlightResult['Segments'] as $Segment) {
                                array_push($NoOfStops, count($Segment));
                                $AirlineInfo = array_column($Segment, 'Airline');
                                $AirlineFlightNumberCode = array_column($AirlineInfo, 'AirlineCode', 'FlightNumber');
                                if ($AirlineFlightNumberCode) {
                                    $AirlineFlightNumberCode = array_map(
                                        function ($value, $key) {
                                            return $value . '-' . $key;
                                        },
                                        array_values($AirlineFlightNumberCode),
                                        array_keys($AirlineFlightNumberCode)
                                    );
                                }
                                $AirlineFlightNumberCodeData = array_merge($AirlineFlightNumberCodeData, $AirlineFlightNumberCode);
                                $NumberOfSegment = count($Segment);
                                $ttssegment = array();
                                $first_segment_data = reset($Segment);
                                $last_segment = end($Segment);
                                $origintimezone = $RestModel->getAirportCodeTimeZone($first_segment_data['Origin']['CityCode']);

                                $origintimezone = "";
                                $destinationtimezone = "";
                                $TotalDuration = 0;

                                if (!$input['IsDomestic']) {

                                    if (isset($airport_timezone[$first_segment_data['Origin']['CityCode']])) {
                                        $origintimezone = $airport_timezone[$first_segment_data['Origin']['CityCode']];
                                    } else {
                                        $origintimezone = $RestModel->getAirportCodeTimeZone($first_segment_data['Origin']['CityCode']);
                                        $airport_timezone[$first_segment_data['Origin']['CityCode']] = $origintimezone;
                                    }
                                    if (isset($airport_timezone[$last_segment['Destination']['CityCode']])) {
                                        $destinationtimezone = $airport_timezone[$last_segment['Destination']['CityCode']];
                                    } else {
                                        $destinationtimezone = $RestModel->getAirportCodeTimeZone($last_segment['Destination']['CityCode']);
                                        $airport_timezone[$last_segment['Destination']['CityCode']] = $destinationtimezone;
                                    }

                                }

                                $TotalDuration = journeyTime($origintimezone, $destinationtimezone, $first_segment_data['Origin']['DepartTime'], $last_segment['Destination']['ArrivalTime'], "duration");
                                $tripSegement = array();
                                foreach ($Segment as $segkey => $item) {
                                    $layoverTime = "";
                                    $layoverTimeInMinutes = 0;
                                    $Duration = $item['Duration'];

                                    if ($NumberOfSegment > 1) {
                                        $origintimezone = "";
                                        $destinationtimezone = "";
                                        if (!$input['IsDomestic']) {
                                            if (isset($Segment[$segkey + 1])) {
                                                if (isset($airport_timezone[$item['Destination']['CityCode']])) {
                                                    $origintimezone = $airport_timezone[$item['Destination']['CityCode']];
                                                } else {
                                                    $origintimezone = $RestModel->getAirportCodeTimeZone($item['Destination']['CityCode']);
                                                    $airport_timezone[$item['Destination']['CityCode']] = $origintimezone;
                                                }
                                                if (isset($airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']])) {
                                                    $destinationtimezone = $airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']];
                                                } else {
                                                    $destinationtimezone = $RestModel->getAirportCodeTimeZone($Segment[$segkey + 1]['Origin']['CityCode']);
                                                    $airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']] = $destinationtimezone;
                                                }
                                            }
                                        }
                                    }
                                    if (isset($Segment[$segkey + 1])) {
                                        $layoverTime = journeyTime($origintimezone, $destinationtimezone, $item['Destination']['ArrivalTime'], $Segment[$segkey + 1]['Origin']['DepartTime']);
                                        $layoverTimeInMinutes = journeyTime($origintimezone, $destinationtimezone, $item['Destination']['ArrivalTime'], $Segment[$segkey + 1]['Origin']['DepartTime'], "duration");
                                    }
                                    $origintimezone = "";
                                    $destinationtimezone = "";
                                    if (!$input['IsDomestic']) {

                                        if (isset($airport_timezone[$item['Origin']['CityCode']])) {
                                            $origintimezone = $airport_timezone[$item['Origin']['CityCode']];
                                        } else {
                                            $origintimezone = $RestModel->getAirportCodeTimeZone($item['Origin']['CityCode']);
                                            $airport_timezone[$item['Origin']['CityCode']] = $origintimezone;
                                        }
                                        if (isset($airport_timezone[$item['Destination']['CityCode']])) {
                                            $destinationtimezone = $airport_timezone[$item['Destination']['CityCode']];
                                        } else {
                                            $destinationtimezone = $RestModel->getAirportCodeTimeZone($item['Destination']['CityCode']);
                                            $airport_timezone[$item['Destination']['CityCode']] = $destinationtimezone;
                                        }
                                    }
                                    $Duration = journeyTime($origintimezone, $destinationtimezone, $item['Origin']['DepartTime'], $item['Destination']['ArrivalTime'], "duration");

                                    $item['Duration'] = $Duration;
                                    $item['LayoverTime'] = $layoverTimeInMinutes;
                                    $item['Layover'] = $layoverTime;
                                    $item['TotalDuration'] = $TotalDuration;
                                    array_push($tripSegement, $item);
                                }
                                array_push($trips, $tripSegement);

                            }
                            $previous_fare = array();
                            $previous_fare = $FlightResult['FareList'];
                            $FlightResult['Segments'] = $trips;
                            ;
                            $tempgroup = array();
                            $finalarrayfare = array();
                            foreach ($previous_fare as $arr) {
                                if (!empty($IncludeExcludesupplierArray) && in_array($arr['Source'], $IncludeExcludesupplierArray)) {
                                    $incexcludeSupplierInfokey = array_search($arr['Source'], $IncludeExcludesupplierArray);
                                    $incexcludeSupplierInfo = $IncludeExcludeSupplierAirline[$incexcludeSupplierInfokey];
                                    $fareType = explode(",", $incexcludeSupplierInfo['fare_type']);
                                    if ($incexcludeSupplierInfo['allowed_airline'] == "" || ($incexcludeSupplierInfo['allowed_airline'] == null)) {
                                        $excluded_airline = explode(",", $incexcludeSupplierInfo['excluded_airline']);
                                        if (in_array($airlineCode, $excluded_airline)) {
                                            $arr = array();
                                        } else {
                                            if (!in_array('All', $fareType) && !in_array($arr['FareType'], $fareType)) {
                                                $arr = array();
                                            }
                                        }
                                    } else {
                                        $allowed_airline = explode(",", $incexcludeSupplierInfo['allowed_airline']);
                                        if (!in_array($airlineCode, $allowed_airline)) {
                                            $arr = array();
                                        } else {
                                            if (!in_array('All', $fareType) && !in_array($arr['FareType'], $fareType)) {
                                                $arr = array();
                                            }
                                        }
                                    }
                                }
                                array_push($finalarrayfare, $arr);
                            }

                            /*------ Sort Price Group    --------*/
                            $keys_publishprice = array_column($finalarrayfare, 'PublishedPrice');
                            array_multisort($keys_publishprice, SORT_ASC, $finalarrayfare);

                            if ($finalarrayfare) {
                                $FlightResult['MinPublishedPrice'] = $finalarrayfare[0]['PublishedPrice'];
                                $FlightResult['FareList'] = $finalarrayfare;
                                array_push($ReturnFlightResponse, $FlightResult);
                            }
                        }
                        $keys_minpublishprice = array_column($ReturnFlightResponse, 'MinPublishedPrice');
                        array_multisort($keys_minpublishprice, SORT_ASC, $ReturnFlightResponse);
                        if ($ReturnFlightResponse) {
                            $convert_response[$Convertkey] = $ReturnFlightResponse;
                        }
                        if (empty($ReturnFlightResponse)) {
                            unset($convert_response[$Convertkey]);
                        }
                        unset($FlightResults);
                        unset($ReturnFlightResponse);
                        unset($tempgroup);
                        unset($finalarrayfare);
                    }
                }
                $RestModel->insertBatchData('common_flight_log', $insert_data);
                if (count($convert_response) != 0 && !empty($convert_response)) {
                    $ErrorCode = 0;
                    $ErrorMessage = '';
                } else {
                    $ErrorCode = 1;
                    $ErrorMessage = 'no result found';
                }
            } else {
                $ErrorCode = 1;
                $ErrorMessage = 'no result found';
            }

            $tts_response = array(
                'UserIp' => $input['UserIp'],
                'SearchTokenId' => $tts_search_token,
                'Error' => array("ErrorCode" => $ErrorCode, "ErrorMessage" => $ErrorMessage),
                'Result' => $convert_response
            );

            // Insert TTS Logs
            $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $tts_search_token, $input, $tts_response, 'search', null, $custom_index);

            // if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) //Closed BY HS
            //     ob_start('ob_gzhandler');
            // else
            //     ob_start();
            
            if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {   // Added BY HS
                ob_start('ob_gzhandler');
            } else {
                ob_start();
            }

            return $this->response->setContentType('application/json')->setJSON($tts_response);
        }
    }

    public function GetCalendarFare()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->calendar_fare_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $tts_search_token = generate_token();
            $response = $this->TBO_Module->GetCalendarFare($input, $tts_search_token, $this->userauthdata);
            // Insert TTS Logs
            $RestModel = new RestModel();
            $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $tts_search_token, $input, $response['Response'], 'getcalendarfare', null, $response['CustomIndex']);
            return $this->response->setContentType('application/json')->setJSON($response['Response']);
        }
    }

    public function FareRule()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->farerule_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search'], 'request');
            if ($verify_data) {
                $Index_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($Index_data[$input['ResultIndex']])) {
                    $indexinfo = $Index_data[$input['ResultIndex']];
                    $InputRequest = json_decode($verify_data['request'], true);
                    $common_data = array();
                    $common_data['indexinfo'] = $indexinfo;
                    $common_data['InputRequest'] = $InputRequest;
                    $loadmodule = $indexinfo['Supplier'] . '_Module';
                    $response = $this->$loadmodule->FareRule($input, $common_data, $this->userauthdata);

                    // Insert TTS Logs
                    $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'farerule', $input['ResultIndex'], $response['CustomIndex']);

                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function FareConfirmation()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->fareconfirmation_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search'], 'request');
            if ($verify_data) {
                $Index_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($Index_data[$input['ResultIndex']])) {

                    $indexinfo = $Index_data[$input['ResultIndex']];

                    $InputRequest = json_decode($verify_data['request'], true);
                    $common_data = array();
                    $common_data['airline_list'] = static_airline_array_list();
                    $common_data['airport_list'] = static_airport_array_list();
                    $common_data['super_admin_markup'] = $RestModel->super_admin_markup($this->userauthdata['web_partner_class_id'], $InputRequest, $this->supplier_list);
                    $common_data['super_admin_discount'] = $RestModel->super_admin_discount($this->userauthdata['web_partner_class_id'], $InputRequest, $this->supplier_list);
                    $common_data['super_admin_gst_state_code'] = $RestModel->super_admin_gst_state_code()['gst_state_code'];
                    $common_data['indexinfo'] = $indexinfo;
                    $common_data['InputRequest'] = $InputRequest;
                    $common_data['super_admin_flight_deal'] = $RestModel->get_super_admin_flight_deal($this->userauthdata['web_partner_class_id'], $InputRequest);
                    if (isset($Index_data['EXTRAPARAM'])) {
                        $common_data['EXTRAPARAM'] = $Index_data['EXTRAPARAM'];
                    }

                    $loadmodule = $indexinfo['Supplier'] . '_Module';

                    $response = $this->$loadmodule->FareQuote($input, $common_data, $this->userauthdata);

                    $FlightResult = $response['Response'];

                    $airport_timezone = array();
                    $trips = array();
                    if ($FlightResult['Error']['ErrorCode'] == 0) {
                        foreach ($FlightResult['Result']['Segments'] as $Segment) {

                            $NumberOfSegment = count($Segment);
                            $ttssegment = array();
                            $first_segment_data = reset($Segment);
                            $last_segment = end($Segment);
                            $origintimezone = $RestModel->getAirportCodeTimeZone($first_segment_data['Origin']['CityCode']);

                            $origintimezone = "";
                            $destinationtimezone = "";
                            $TotalDuration = 0;

                            if (!$InputRequest['IsDomestic']) {

                                if (isset($airport_timezone[$first_segment_data['Origin']['CityCode']])) {
                                    $origintimezone = $airport_timezone[$first_segment_data['Origin']['CityCode']];
                                } else {
                                    $origintimezone = $RestModel->getAirportCodeTimeZone($first_segment_data['Origin']['CityCode']);
                                    $airport_timezone[$first_segment_data['Origin']['CityCode']] = $origintimezone;
                                }
                                if (isset($airport_timezone[$last_segment['Destination']['CityCode']])) {
                                    $destinationtimezone = $airport_timezone[$last_segment['Destination']['CityCode']];
                                } else {
                                    $destinationtimezone = $RestModel->getAirportCodeTimeZone($last_segment['Destination']['CityCode']);
                                    $airport_timezone[$last_segment['Destination']['CityCode']] = $destinationtimezone;
                                }
                            }

                            $TotalDuration = journeyTime($origintimezone, $destinationtimezone, $first_segment_data['Origin']['DepartTime'], $last_segment['Destination']['ArrivalTime'], "duration");
                            $tripSegement = array();
                            foreach ($Segment as $segkey => $item) {
                                $layoverTime = "";
                                $layoverTimeInMinutes = 0;
                                $Duration = $item['Duration'];

                                if ($NumberOfSegment > 1) {
                                    $origintimezone = "";
                                    $destinationtimezone = "";
                                    if (!$InputRequest['IsDomestic']) {
                                        if (isset($Segment[$segkey + 1])) {
                                            if (isset($airport_timezone[$item['Destination']['CityCode']])) {
                                                $origintimezone = $airport_timezone[$item['Destination']['CityCode']];
                                            } else {
                                                $origintimezone = $RestModel->getAirportCodeTimeZone($item['Destination']['CityCode']);
                                                $airport_timezone[$item['Destination']['CityCode']] = $origintimezone;
                                            }
                                            if (isset($airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']])) {
                                                $destinationtimezone = $airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']];
                                            } else {
                                                $destinationtimezone = $RestModel->getAirportCodeTimeZone($Segment[$segkey + 1]['Origin']['CityCode']);
                                                $airport_timezone[$Segment[$segkey + 1]['Origin']['CityCode']] = $destinationtimezone;
                                            }
                                        }
                                    }
                                }
                                if (isset($Segment[$segkey + 1])) {
                                    $layoverTime = journeyTime($origintimezone, $destinationtimezone, $item['Destination']['ArrivalTime'], $Segment[$segkey + 1]['Origin']['DepartTime']);
                                    $layoverTimeInMinutes = journeyTime($origintimezone, $destinationtimezone, $item['Destination']['ArrivalTime'], $Segment[$segkey + 1]['Origin']['DepartTime'], "duration");
                                }
                                $origintimezone = "";
                                $destinationtimezone = "";
                                if (!$InputRequest['IsDomestic']) {

                                    if (isset($airport_timezone[$item['Origin']['CityCode']])) {
                                        $origintimezone = $airport_timezone[$item['Origin']['CityCode']];
                                    } else {
                                        $origintimezone = $RestModel->getAirportCodeTimeZone($item['Origin']['CityCode']);
                                        $airport_timezone[$item['Origin']['CityCode']] = $origintimezone;
                                    }
                                    if (isset($airport_timezone[$item['Destination']['CityCode']])) {
                                        $destinationtimezone = $airport_timezone[$item['Destination']['CityCode']];
                                    } else {
                                        $destinationtimezone = $RestModel->getAirportCodeTimeZone($item['Destination']['CityCode']);
                                        $airport_timezone[$item['Destination']['CityCode']] = $destinationtimezone;
                                    }
                                }
                                $Duration = journeyTime($origintimezone, $destinationtimezone, $item['Origin']['DepartTime'], $item['Destination']['ArrivalTime'], "duration");

                                $item['Duration'] = $Duration;
                                $item['LayoverTime'] = $layoverTimeInMinutes;
                                $item['Layover'] = $layoverTime;
                                $item['TotalDuration'] = $TotalDuration;
                                array_push($tripSegement, $item);
                            }
                            array_push($trips, $tripSegement);
                        }
                        $FlightResult['Result']['Segments'] = $trips;
                    }

                    // Insert TTS Logs
                    $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $FlightResult, 'fareconfirmation', $input['ResultIndex'], $response['CustomIndex']);

                    return $this->response->setContentType('application/json')->setJSON($FlightResult);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function SSR()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->ssr_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'fareconfirmation']);
            if ($verify_data) {
                $Index_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($Index_data[$input['ResultIndex']])) {
                    $indexinfo = $Index_data[$input['ResultIndex']];
                    $common_data = array();
                    $common_data['indexinfo'] = $indexinfo;
                    $loadmodule = $indexinfo['Supplier'] . '_Module';
                    $response = $this->$loadmodule->SSR($input, $common_data, $this->userauthdata);

                    // Insert TTS Logs
                    $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response['Response'], 'ssr', $input['ResultIndex'], $response['CustomIndex']);

                    return $this->response->setContentType('application/json')->setJSON($response['Response']);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    // public function Book()
    // {
    //     $input = json_validate(file_get_contents("php://input"));
    //     $validate = new Validation();
    //     $this->validation->setRules($validate->book_validation);
    //     $rules = $this->validation->run($input);
    //     if (!$rules) {
    //         $message = validation_string_message($this->validation->getErrors());
    //         return api_custom_message(400, $message);
    //     } else {
    //         $RestModel = new RestModel();
    //         $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'fareconfirmation'], 'response');
    //         if ($verify_data) {
    //             $Index_data = json_decode($verify_data['tts_index_response'], true);
    //             if (isset($Index_data[$input['ResultIndex']])) {
    //                 if (isset($Index_data[$input['ResultIndex']]['IsLCC']) && !$Index_data[$input['ResultIndex']]['IsLCC']) {
    //                     $search_request = $RestModel->get_search_request(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
    //                     $search_request_array = json_decode($search_request['request'], true);
    //                     $indexinfo = $Index_data[$input['ResultIndex']];

    //                     $fare_confimation = json_decode($verify_data['response'], true);

    //                     $fare_confimation_result = $fare_confimation['Result'];
    //                     if (count($fare_confimation_result['Segments']) == 1) {
    //                         $first_segment = current($fare_confimation_result['Segments'][0]);
    //                         $last_segment = end($fare_confimation_result['Segments'][0]);
    //                     } else {
    //                         $first_segment = current($fare_confimation_result['Segments']);
    //                         $first_segment = current($first_segment);

    //                         $last_segment = end($fare_confimation_result['Segments']);
    //                         $last_segment = end($last_segment);
    //                     }

    //                     $origin = $first_segment['Origin']['AirportCode'];
    //                     $destination = $last_segment['Destination']['AirportCode'];
    //                     $airline_code = $indexinfo['AirlineCode'];

    //                     // Pax Validation 
    //                     $validate_pax = $validate->book_pax_validation($input, $indexinfo, $search_request_array);
    //                     $this->validation->setRules($validate_pax);
    //                     $paxrules = $this->validation->run($input);

    //                     if (!$paxrules) {
    //                         $message = validation_string_message($this->validation->getErrors());
    //                         return api_custom_message(400, $message);
    //                     } else {

    //                         $tts_response = array();
    //                         /*-- Check Search Token Id is Valid for Single Request Book Method other wise Token will be expire ---  */
    //                         $check_book_request = $RestModel->book_record_exists(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'book', 'selected_index' => $input['ResultIndex']]);
    //                         if (empty($check_book_request)) {

    //                             /*-- Check data save in Booking Table with Search Token Id  ---  */
    //                             $BookingDetail = $RestModel->getbookingDetailByToken($input['SearchTokenId'], $input['ResultIndex'], "id,payment_status");
    //                             if (isset($BookingDetail['id'])) {
    //                                 $Is_Save_data = 'true';
    //                                 $TTS_Booking_ID = $BookingDetail['id'];
    //                             } else {
    //                                 $Is_Save_data = 'false';
    //                                 $TTS_Booking_ID = null;
    //                             }
    //                             /*---------through api service  ------*/
    //                             $ssr_response = array();
    //                             $ssrdata = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'ssr']);
    //                             if ($ssrdata) {
    //                                 $SSRIndex_data = json_decode($ssrdata['tts_index_response'], true);
    //                                 if (isset($SSRIndex_data[$input['ResultIndex']])) {
    //                                     $ssr_response = $SSRIndex_data[$input['ResultIndex']];
    //                                 }
    //                             }
    //                             /*--------- IF Fare With SSR -----------*/
    //                             $baggage_price = 0;
    //                             $meal_price = 0;
    //                             $seat_price = 0;
    //                             $insertpaxdata = array();
    //                             foreach ($input['Passengers'] as $Passenger) {
    //                                 if (isset($Passenger['Baggage']) && $Passenger['Baggage']) {
    //                                     $price_bag = 0;
    //                                     foreach ($Passenger['Baggage'] as $baggage) {
    //                                         if (isset($ssr_response['Baggage'][$baggage['Code']]) && $ssr_response) {
    //                                             $price_bag += $ssr_response['Baggage'][$baggage['Code']]['Price'];
    //                                         } else {
    //                                             $message = "Invalid Baggage Detail";
    //                                             api_custom_message(400, $message, false);
    //                                         }
    //                                     }
    //                                     $Passenger['BaggagePrice'] = $price_bag;
    //                                     $baggage_price += $price_bag;
    //                                 }
    //                                 if (isset($Passenger['Meal']) && $Passenger['Meal']) {
    //                                     $price_meal = 0;
    //                                     foreach ($Passenger['Meal'] as $meal) {
    //                                         if (isset($ssr_response['Meal'][$meal['Code']]) && $ssr_response) {
    //                                             /*  $price_meal += $ssr_response['Meal'][$meal['Code']]['Price']; */
    //                                             $price_meal = 0;
    //                                         } else {
    //                                             $message = "Invalid Meal Detail";
    //                                             api_custom_message(400, $message, false);
    //                                         }
    //                                     }
    //                                     $Passenger['MealPrice'] = $price_meal;
    //                                     $meal_price += $price_meal;
    //                                 }
    //                                 array_push($insertpaxdata, $Passenger);
    //                             }

    //                             $indexinfo['SuperAdminFareBreakup']['TotalBaggageCharges'] = $baggage_price;
    //                             $indexinfo['SuperAdminFareBreakup']['TotalMealCharges'] = $meal_price;
    //                             $indexinfo['SuperAdminFareBreakup']['TotalSeatCharges'] = $seat_price;
    //                             $indexinfo['WebPartnerFareBreakup']['TotalBaggageCharges'] = $baggage_price;
    //                             $indexinfo['WebPartnerFareBreakup']['TotalMealCharges'] = $meal_price;
    //                             $indexinfo['WebPartnerFareBreakup']['TotalSeatCharges'] = $seat_price;
    //                             $TTS_Invoice_Amount = floatval($indexinfo['TTS_Invoice_Amount']) + floatval($baggage_price) + floatval($meal_price);
    //                             $indexinfo['TTS_Invoice_Amount'] = $TTS_Invoice_Amount;
    //                             $Auth_User_Balance = $RestModel->get_auth_user_account_balance($this->userauthdata['web_partner_id']);
    //                             $flight_offline = $RestModel->get_flight_offline($airline_code, $origin, $destination);
    //                             $common_data = array();
    //                             $common_data['indexinfo'] = $indexinfo;
    //                             $common_data['InputRequest'] = $search_request_array;
    //                             $common_data['Supplier'] = $indexinfo['Supplier'];
    //                             $common_data['SSRData'] = $ssr_response;
    //                             /*   if(isset($input['IsHold'])&&$input['IsHold']){ 
    //                                   $flight_offline['is_hold'] = "Hold";
    //                               } */
    //                             $common_data['FlightOffline'] = $flight_offline;
    //                             if (isset($Index_data['EXTRAPARAM'])) {
    //                                 $common_data['EXTRAPARAM'] = $Index_data['EXTRAPARAM'];
    //                             }
    //                             $save_data = Rest::SaveData($input, $common_data, $this->userauthdata, $search_request_array, $fare_confimation, $insertpaxdata, $Is_Save_data, $TTS_Booking_ID);
    //                             $common_data['SaveData'] = $save_data;

    //                             $loadmodule = $indexinfo['Supplier'] . '_Module';
    //                             if (isset($flight_offline['is_offline']) && $flight_offline['is_offline'] == 'Pending') {
    //                                 $booking_data = $RestModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId']]);
    //                                 $tts_response = ConvertBookingDetail($input, $booking_data);
    //                             } else {
    //                                 $tts_response = $this->$loadmodule->Book($input, $common_data, $this->userauthdata);
    //                             }

    //                             // Insert TTS Logs
    //                             $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $tts_response, 'book', $input['ResultIndex']);
    //                             return $this->response->setContentType('application/json')->setJSON($tts_response);
    //                         } else {
    //                             $message = api_validation_message('expire_token_error');
    //                             return api_custom_message(5, $message, false);
    //                         }
    //                     }
    //                 } else {
    //                     $message = api_validation_message('not_lcc_flight');
    //                     return api_custom_message(400, $message);
    //                 }
    //             } else {
    //                 $message = api_validation_message('invalid_token_error');
    //                 return api_custom_message(400, $message);
    //             }
    //         } else {
    //             $message = api_validation_message('invalid_token_error');
    //             return api_custom_message(400, $message);
    //         }
    //     }
    // }
    
    public function Book()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->book_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'fareconfirmation'], 'response');
            if ($verify_data) {
                $Index_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($Index_data[$input['ResultIndex']])) {
                    if (isset($Index_data[$input['ResultIndex']]['IsLCC']) && !$Index_data[$input['ResultIndex']]['IsLCC']) {
                        $search_request = $RestModel->get_search_request(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
                        $search_request_array = json_decode($search_request['request'], true);
                        $indexinfo = $Index_data[$input['ResultIndex']];

                        $fare_confimation = json_decode($verify_data['response'], true);

                        $fare_confimation_result = $fare_confimation['Result'];
                        if (count($fare_confimation_result['Segments']) == 1) {
                            $first_segment = current($fare_confimation_result['Segments'][0]);
                            $last_segment = end($fare_confimation_result['Segments'][0]);
                        } else {
                            $first_segment = current($fare_confimation_result['Segments']);
                            $first_segment = current($first_segment);

                            $last_segment = end($fare_confimation_result['Segments']);
                            $last_segment = end($last_segment);
                        }

                        $origin = $first_segment['Origin']['AirportCode'];
                        $destination = $last_segment['Destination']['AirportCode'];
                        $airline_code = $indexinfo['AirlineCode'];

                        // Pax Validation 
                        $validate_pax = $validate->book_pax_validation($input, $indexinfo, $search_request_array);
                        $this->validation->setRules($validate_pax);
                        $paxrules = $this->validation->run($input);

                        if (!$paxrules) {
                            $message = validation_string_message($this->validation->getErrors());
                            return api_custom_message(400, $message);
                        } else {

                            $tts_response = array();
                            /*-- Check Search Token Id is Valid for Single Request Book Method other wise Token will be expire ---  */
                            $check_book_request = $RestModel->book_record_exists(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'book', 'selected_index' => $input['ResultIndex']]);
                            if (empty($check_book_request)) {

                                /*-- Check data save in Booking Table with Search Token Id  ---  */
                                $BookingDetail = $RestModel->getbookingDetailByToken($input['SearchTokenId'], $input['ResultIndex'], "id,payment_status");
                                if (isset($BookingDetail['id'])) {
                                    $Is_Save_data = 'true';
                                    $TTS_Booking_ID = $BookingDetail['id'];
                                } else {
                                    $Is_Save_data = 'false';
                                    $TTS_Booking_ID = null;
                                }
                                /*---------through api service  ------*/
                                $ssr_response = array();
                                $ssrdata = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'ssr']);
                                if ($ssrdata) {
                                    $SSRIndex_data = json_decode($ssrdata['tts_index_response'], true);
                                    if (isset($SSRIndex_data[$input['ResultIndex']])) {
                                        $ssr_response = $SSRIndex_data[$input['ResultIndex']];
                                    }
                                }
                                /*--------- IF Fare With SSR -----------*/
                                $baggage_price = 0;
                                $meal_price = 0;
                                $seat_price = 0;
                                $insertpaxdata = array();
                                foreach ($input['Passengers'] as $Passenger) {
                                    if (isset($Passenger['Baggage']) && $Passenger['Baggage']) {
                                        $price_bag = 0;
                                        foreach ($Passenger['Baggage'] as $baggage) {
                                            if (isset($ssr_response['Baggage'][$baggage['Code']]) && $ssr_response) {
                                                $price_bag += $ssr_response['Baggage'][$baggage['Code']]['Price'];
                                            } else {
                                                $message = "Invalid Baggage Detail";
                                                api_custom_message(400, $message, false);
                                            }
                                        }
                                        $Passenger['BaggagePrice'] = $price_bag;
                                        $baggage_price += $price_bag;
                                    }
                                    if (isset($Passenger['Meal']) && $Passenger['Meal']) {
                                        $price_meal = 0;
                                        foreach ($Passenger['Meal'] as $meal) {
                                            if (isset($ssr_response['Meal'][$meal['Code']]) && $ssr_response) {
                                                /*  $price_meal += $ssr_response['Meal'][$meal['Code']]['Price']; */
                                                $price_meal = 0;
                                            } else {
                                                $message = "Invalid Meal Detail";
                                                api_custom_message(400, $message, false);
                                            }
                                        }
                                        $Passenger['MealPrice'] = $price_meal;
                                        $meal_price += $price_meal;
                                    }
                                    array_push($insertpaxdata, $Passenger);
                                }

                                $indexinfo['SuperAdminFareBreakup']['TotalBaggageCharges'] = $baggage_price;
                                $indexinfo['SuperAdminFareBreakup']['TotalMealCharges'] = $meal_price;
                                $indexinfo['SuperAdminFareBreakup']['TotalSeatCharges'] = $seat_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalBaggageCharges'] = $baggage_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalMealCharges'] = $meal_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalSeatCharges'] = $seat_price;
                                $TTS_Invoice_Amount = floatval($indexinfo['TTS_Invoice_Amount']) + floatval($baggage_price) + floatval($meal_price);
                                $indexinfo['TTS_Invoice_Amount'] = $TTS_Invoice_Amount;
                                $Auth_User_Balance = $RestModel->get_auth_user_account_balance($this->userauthdata['web_partner_id']);
                                $flight_offline = $RestModel->get_flight_offline($airline_code, $origin, $destination);
                                $common_data = array();
                                $common_data['indexinfo'] = $indexinfo;
                                $common_data['InputRequest'] = $search_request_array;
                                $common_data['Supplier'] = $indexinfo['Supplier'];
                                $common_data['SSRData'] = $ssr_response;
                                /*   if(isset($input['IsHold'])&&$input['IsHold']){ 
                                      $flight_offline['is_hold'] = "Hold";
                                  } */
                                $common_data['FlightOffline'] = $flight_offline;
                                if (isset($Index_data['EXTRAPARAM'])) {
                                    $common_data['EXTRAPARAM'] = $Index_data['EXTRAPARAM'];
                                }
                                $save_data = Rest::SaveData($input, $common_data, $this->userauthdata, $search_request_array, $fare_confimation, $insertpaxdata, $Is_Save_data, $TTS_Booking_ID);
                                $common_data['SaveData'] = $save_data;

                                $loadmodule = $indexinfo['Supplier'] . '_Module';
                                if (isset($flight_offline['is_offline']) && $flight_offline['is_offline'] == 'Pending') {
                                    $booking_data = $RestModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId']]);
                                    $tts_response = ConvertBookingDetail($input, $booking_data);
                                } else {
                                    $tts_response = $this->$loadmodule->Book($input, $common_data, $this->userauthdata);
                                }

                                // Insert TTS Logs
                                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $tts_response, 'book', $input['ResultIndex']);
                                return $this->response->setContentType('application/json')->setJSON($tts_response);
                            } else {
                                $message = api_validation_message('expire_token_error');
                                return api_custom_message(5, $message, false);
                            }
                        }
                    } else {
                        $message = api_validation_message('not_lcc_flight');
                        return api_custom_message(400, $message);
                    }
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }
    
    public function Ticket()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->book_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'fareconfirmation'], 'response');
            if ($verify_data) {
                $Index_data = json_decode($verify_data['tts_index_response'], true);
                if (isset($Index_data[$input['ResultIndex']])) {
                    if (isset($Index_data[$input['ResultIndex']]['IsLCC']) && $Index_data[$input['ResultIndex']]['IsLCC']) {
                        $search_request = $RestModel->get_search_request(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
                        $search_request_array = json_decode($search_request['request'], true);
                        $indexinfo = $Index_data[$input['ResultIndex']];

                        $fare_confimation = json_decode($verify_data['response'], true);

                        $fare_confimation_result = $fare_confimation['Result'];
                        if (count($fare_confimation_result['Segments']) == 1) {
                            $first_segment = current($fare_confimation_result['Segments'][0]);
                            $last_segment = end($fare_confimation_result['Segments'][0]);
                        } else {
                            $first_segment = current($fare_confimation_result['Segments']);
                            $first_segment = current($first_segment);

                            $last_segment = end($fare_confimation_result['Segments']);
                            $last_segment = end($last_segment);
                        }

                        $origin = $first_segment['Origin']['AirportCode'];
                        $destination = $last_segment['Destination']['AirportCode'];
                        $airline_code = $indexinfo['AirlineCode'];

                        // Pax Validation 
                        $validate_pax = $validate->book_pax_validation($input, $indexinfo, $search_request_array);
                        $this->validation->setRules($validate_pax);
                        $paxrules = $this->validation->run($input);
                        if (!$paxrules) {
                            $message = validation_string_message($this->validation->getErrors());
                            return api_custom_message(400, $message);
                        } else {
                            $tts_response = array();
                            /*-- Check Search Token Id is Valid for Single Request Book Method other wise Token will be expire ---  */
                            $check_book_request = $RestModel->book_record_exists(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'ticket', 'selected_index' => $input['ResultIndex']]);
                            if (empty($check_book_request)) {

                                /*-- Check data save in Booking Table with Search Token Id  ---  */
                                $BookingDetail = $RestModel->getbookingDetailByToken($input['SearchTokenId'], $input['ResultIndex'], "id,payment_status");
                                if (isset($BookingDetail['id'])) {
                                    $Is_Save_data = 'true';
                                    $TTS_Booking_ID = $BookingDetail['id'];
                                } else {
                                    $Is_Save_data = 'false';
                                    $TTS_Booking_ID = null;
                                }
                                /*---------through api service  ------*/
                                $ssr_response = array();
                                $ssrdata = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'ssr']);
                                if ($ssrdata) {
                                    $SSRIndex_data = json_decode($ssrdata['tts_index_response'], true);
                                    if (isset($SSRIndex_data[$input['ResultIndex']])) {
                                        $ssr_response = $SSRIndex_data[$input['ResultIndex']];
                                    }
                                }
                                /*--------- IF Fare With SSR -----------*/
                                $baggage_price = 0;
                                $meal_price = 0;
                                $seat_price = 0;
                                $insertpaxdata = array();
                                foreach ($input['Passengers'] as $Passenger) {
                                    if (isset($Passenger['Baggage']) && $Passenger['Baggage']) {
                                        $price_bag = 0;
                                        foreach ($Passenger['Baggage'] as $baggage) {
                                            if (isset($ssr_response['Baggage'][$baggage['Code']]) && $ssr_response) {
                                                $price_bag += $ssr_response['Baggage'][$baggage['Code']]['Price'];
                                            } else {
                                                $message = "Invalid Baggage Detail";
                                                api_custom_message(400, $message, false);
                                            }
                                        }
                                        $Passenger['BaggagePrice'] = $price_bag;
                                        $baggage_price += $price_bag;
                                    }
                                    if (isset($Passenger['Meal']) && $Passenger['Meal']) {
                                        $price_meal = 0;
                                        foreach ($Passenger['Meal'] as $meal) {
                                            if (isset($ssr_response['Meal'][$meal['Code']]) && $ssr_response) {
                                                $price_meal += $ssr_response['Meal'][$meal['Code']]['Price'];
                                            } else {
                                                $message = "Invalid Meal Detail";
                                                api_custom_message(400, $message, false);
                                            }
                                        }
                                        $Passenger['MealPrice'] = $price_meal;
                                        $meal_price += $price_meal;
                                    }
                                    array_push($insertpaxdata, $Passenger);
                                }

                                $indexinfo['SuperAdminFareBreakup']['TotalBaggageCharges'] = $baggage_price;
                                $indexinfo['SuperAdminFareBreakup']['TotalMealCharges'] = $meal_price;
                                $indexinfo['SuperAdminFareBreakup']['TotalSeatCharges'] = $seat_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalBaggageCharges'] = $baggage_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalMealCharges'] = $meal_price;
                                $indexinfo['WebPartnerFareBreakup']['TotalSeatCharges'] = $seat_price;
                                $TTS_Invoice_Amount = floatval($indexinfo['TTS_Invoice_Amount']) + floatval($baggage_price) + floatval($meal_price);
                                $indexinfo['TTS_Invoice_Amount'] = $TTS_Invoice_Amount;
                                $Auth_User_Balance = $RestModel->get_auth_user_account_balance($this->userauthdata['web_partner_id']);


                                $flight_offline = $RestModel->get_flight_offline($airline_code, $origin, $destination);


                                $common_data = array();
                                $common_data['indexinfo'] = $indexinfo;
                                $common_data['InputRequest'] = $search_request_array;
                                $common_data['Supplier'] = $indexinfo['Supplier'];
                                $common_data['SSRData'] = $ssr_response;
                                $common_data['FlightOffline'] = $flight_offline;
                                if (isset($Index_data['EXTRAPARAM'])) {
                                    $common_data['EXTRAPARAM'] = $Index_data['EXTRAPARAM'];
                                }
                                $save_data = Rest::SaveData($input, $common_data, $this->userauthdata, $search_request_array, $fare_confimation, $insertpaxdata, $Is_Save_data, $TTS_Booking_ID);
                                $common_data['SaveData'] = $save_data;

                                $loadmodule = $indexinfo['Supplier'] . '_Module';
                                if (isset($flight_offline['is_offline']) && $flight_offline['is_offline'] == 'Pending') {
                                    $booking_data = $RestModel->verify_booking_detail(['tts_search_token' => $input['SearchTokenId']]);
                                    $tts_response = ConvertBookingDetail($input, $booking_data);
                                } else {
                                    $tts_response = $this->$loadmodule->Ticket($input, $common_data, $this->userauthdata);
                                }


                                // Insert TTS Logs
                                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $tts_response, 'ticket', $input['ResultIndex']);
                                return $this->response->setContentType('application/json')->setJSON($tts_response);
                            } else {
                                $message = api_validation_message('expire_token_error');
                                return api_custom_message(5, $message, false);
                            }
                        }
                    } else {
                        $validate = new Validation();
                        $this->validation->setRules($validate->ticket_validation);
                        $rules = $this->validation->run($input);
                        if (!$rules) {
                            $message = validation_string_message($this->validation->getErrors());
                            return api_custom_message(400, $message);
                        } else {
                            $verify_data = $RestModel->verify_tts_search_token(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'selected_index' => $input['ResultIndex'], 'service' => 'fareconfirmation'], 'response');
                            $Index_data = json_decode($verify_data['tts_index_response'], true);
                            $search_request = $RestModel->get_search_request(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'search']);
                            $search_request_array = json_decode($search_request['request'], true);
                            $indexinfo = $Index_data[$input['ResultIndex']];
                            $common_data = array();
                            $common_data['indexinfo'] = $indexinfo;
                            $common_data['InputRequest'] = $search_request_array;
                            $common_data['Supplier'] = $indexinfo['Supplier'];
                            $common_data['SaveData']['account_log_lastid'] = 0;
                            $common_data['SaveData']['booking_lastid'] = $input['BookingId'];
                            /*  $common_data['IsLCC'] = false; */
                            $check_book_request = $RestModel->book_record_exists(['web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $input['SearchTokenId'], 'service' => 'ticket', 'selected_index' => $input['ResultIndex']]);
                            if (empty($check_book_request)) {
                                $loadmodule = $indexinfo['Supplier'] . '_Module';
                                $tts_response = $this->$loadmodule->Ticket($input, $common_data, $this->userauthdata);
                                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $tts_response, 'ticket', $input['ResultIndex']);
                                return $this->response->setContentType('application/json')->setJSON($tts_response);
                            } else {
                                $message = api_validation_message('expire_token_error');
                                return api_custom_message(5, $message, false);
                            }
                        }
                    }
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    function SaveData(array $input, $common_data, array $userauthdata, $search_request_array, $fare_confimation, $passenger_info, $Is_Save_data, $TTS_Booking_ID = null)
    {
        $RestModel = new RestModel();
        $tts_search_token = $input['SearchTokenId'];
        $TTS_Invoice_Amount = $common_data['indexinfo']['TTS_Invoice_Amount'];
        $super_admin_fare_break_up = $common_data['indexinfo']['SuperAdminFareBreakup'];
        $web_partner_fare_break_up = $common_data['indexinfo']['WebPartnerFareBreakup'];
        /*--------------------------------Start Debit Webpartner Balance --------------------------------------*/
        /* $Auth_User_Balance = $RestModel->get_auth_user_account_balance($userauthdata['web_partner_id']);
        $balance = $Auth_User_Balance['balance'] - $TTS_Invoice_Amount;
        $web_partner_account_log = array(
            'web_partner_id' => $userauthdata['web_partner_id'],
            'debit' => $TTS_Invoice_Amount,
            'balance' => round_value($balance),
            'remark' => 'Ticket Created Through API',
            'service' => 'flight',
            'transaction_type' => 'debit',
            'action_type' => 'booking',
            'created' => create_date()
        ); 
        $account_log_lastid = $RestModel->insertData('web_partner_account_log', $web_partner_account_log);
        if ($account_log_lastid) {
            $payment_status = 'Successful';
        } else {
            $payment_status = 'Failed';
        }*/
        $account_log_lastid = 0;
        $payment_status = 'Successful';
        $acc_ref_number = reference_number($account_log_lastid, "Flight", $search_request_array['IsDomestic'], "booking");
        /*-------------------------------End Debit Webpartner Balance --------------------------------------*/

        $is_price_changed = $fare_confimation['IsPriceChanged'];
        $is_time_changed = false;
        $fare_confimation = $fare_confimation['Result'];
        $airline_code = $fare_confimation['Segments'][0][0]['Airline']['AirlineCode'];
        $flight_number = $fare_confimation['Segments'][0][0]['Airline']['FlightNumber'];
        $trip_indicator = $fare_confimation['Segments'][0][0]['TripIndicator'];

        if (count($fare_confimation['Segments']) == 1) {
            $first_segment = current($fare_confimation['Segments'][0]);
            $last_segment = end($fare_confimation['Segments'][0]);
        } else {
            $first_segment = current($fare_confimation['Segments']);
            $first_segment = current($first_segment);

            $last_segment = end($fare_confimation['Segments']);
            $last_segment = end($last_segment);
        }

        $origin = $first_segment['Origin']['AirportCode'];
        $destination = $last_segment['Destination']['AirportCode'];
        $departure_date = explode("T", $first_segment['Origin']['DepartTime'])[0];

        $leadpax = $input['Passengers'][0];


        if ($Is_Save_data == 'false') {
            /*--------------------------------Start Save Booking Data  and Pax Data ------------------------------------------*/
            $gst_info = array(
                'name' => $leadpax['GSTCompanyName'],
                'number' => $leadpax['GSTNumber'],
                'phone' => $leadpax['GSTCompanyContactNumber'],
                'email' => $leadpax['GSTCompanyEmail'],
                'address' => $leadpax['GSTCompanyAddress']
            );

            $fare_rule = array();
            $getfare_rule = $RestModel->get_fare_rule(['web_partner_id' => $userauthdata['web_partner_id'], 'tts_search_token' => $tts_search_token, 'selected_index' => $input['ResultIndex'], 'service' => 'farerule']);
            if ($getfare_rule) {
                $fare_rule_data = json_decode($getfare_rule['response'], true);
                if ($fare_rule_data['Error']['ErrorCode'] == 0) {
                    $fare_rule = $fare_rule_data['Result'];
                }
            }
            $TotalPax = $search_request_array['Adult'] + $search_request_array['Child'];
            $TotalPaxWithInfant = $search_request_array['Adult'] + $search_request_array['Child'] + $search_request_array['Infant'];
            $TotalGST = 0;
            $perPaxDiscount = 0;
            $perPaxOtherCharges = 0;
            $perPaxTDS = 0;
            $perPaxAgentCommission = 0;
            $perPaxGST = 0;
            $FlightFareInfo = $fare_confimation['Fare'];
            $TotalGST = $FlightFareInfo['GST']['CGSTAmount'] + $FlightFareInfo['GST']['IGSTAmount'] + $FlightFareInfo['GST']['SGSTAmount'];
            $perPaxGST = round_value(($TotalGST / $TotalPax));
            $perPaxDiscount = round_value(($FlightFareInfo['Discount'] / $TotalPax));
            $perPaxAgentCommission = round_value(($FlightFareInfo['AgentCommission'] / $TotalPax));
            $perPaxTDS = round_value(($FlightFareInfo['TDS'] / $TotalPax));
            $perPaxOtherCharges = round_value(($FlightFareInfo['OtherCharges'] / $TotalPax));
            $save_book_data = array(
                'tts_search_token' => $tts_search_token,
                'web_partner_id' => $userauthdata['web_partner_id'],
                'is_price_changed' => $is_price_changed,
                'is_time_changed' => $is_time_changed,
                'trip_indicator' => $trip_indicator,
                'search_request' => json_encode($search_request_array),
                'journey_type' => get_journey_type_name($search_request_array['JourneyType']),
                'origin' => $origin,
                'destination' => $destination,
                'departure_date' => $departure_date,
                'is_domestic' => $search_request_array['IsDomestic'],
                'is_lcc' => $fare_confimation['IsLCC'],
                'is_refundable' => $fare_confimation['IsRefundable'],
                'is_manual' => false,
                'fare_type' => $fare_confimation['FareType'],
                'airline_code' => $airline_code,
                'validating_airline_code' => $fare_confimation['ValidatingAirline'],
                'last_ticket_date' => $fare_confimation['LastTicketDate'],
                'airline_remark' => $fare_confimation['AirlineRemark'],
                'segments' => json_encode($fare_confimation['Segments']),
                'fare_rule' => json_encode($fare_rule),
                'api_supplier' => $common_data['Supplier'],
                'payment_mode' => 'API_Wallet',
                'payment_status' => $payment_status,
                'booking_status' => 'Processing',
                'gst_info' => json_encode($gst_info),
                'super_admin_fare_break_up' => json_encode($super_admin_fare_break_up),
                'web_partner_fare_break_up' => json_encode($web_partner_fare_break_up),
                'booking_channel' => 'API',
                'is_gst_mandatory' => $fare_confimation['IsGSTMandatory'],
                'is_gst_allowed' => $fare_confimation['GSTAllowed'],
                'total_price' => $TTS_Invoice_Amount,
                'resultIndex' => $input['ResultIndex'],
                'created' => create_date()
            );
            $booking_lastid = $RestModel->insertData('flight_booking_list', $save_book_data);
            $super_admin__booking_pre_fix_code = $RestModel->super_admin_booking_pre_fix_code()['pre_fix'];
            $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_lastid;
            if ($passenger_info) {
                $save_pax_data = array();
                foreach ($passenger_info as $key => $Passenger) {
                    $baggage = null;
                    $meal = null;
                    $seat = null;
                    $baggage_charges = 0;
                    $meal_charges = 0;
                    $seat_charges = 0;
                    if (check_isset($Passenger, 'Baggage')) {
                        $baggage = json_encode($Passenger['Baggage']);
                        $baggage_charges = $Passenger['BaggagePrice'];
                    }
                    if (check_isset($Passenger, 'Meal')) {
                        $meal = json_encode($Passenger['Meal']);
                        $meal_charges = $Passenger['MealPrice'];
                    }
                    if (check_isset($Passenger, 'Seat')) {
                        $seat = json_encode($Passenger['Seat']);
                        $seat_charges = $Passenger['SeatPrice'];
                    }

                    $paxcode = get_paxtype_code($Passenger['PaxType']);
                    $pax_fare_breakdown = $fare_confimation['FareBreakdown'][$paxcode];
                    $base_fare = round_value($pax_fare_breakdown['BaseFare'] / $pax_fare_breakdown['PassengerCount']);
                    $tax = round_value($pax_fare_breakdown['Tax'] / $pax_fare_breakdown['PassengerCount']);
                    $yq_tax = round_value($pax_fare_breakdown['YQTax'] / $pax_fare_breakdown['PassengerCount']);
                    $service_charge = round_value($pax_fare_breakdown['ServiceCharges'] / $pax_fare_breakdown['PassengerCount']);
                    $fare = array(
                        'BaseFare' => $base_fare,
                        'Tax' => $tax,
                        'YQTax' => $yq_tax,
                        'ServiceCharges' => $service_charge,
                        'OtherCharges' => $perPaxOtherCharges,
                        'Discount' => $perPaxDiscount,
                        'AgentCommission' => $perPaxAgentCommission,
                        'TDS' => $perPaxTDS,
                        'GSTAmount' => $perPaxGST,
                        'PublishedPrice' => round_value($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST),
                        'OfferedPrice' => round_value(($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST) - ($perPaxAgentCommission + $perPaxDiscount)),
                        'BaggageCharges' => $baggage_charges,
                        'MealCharges' => $meal_charges,
                        'SeatCharges' => $seat_charges
                    );
                    if ($Passenger['PaxType'] == 3) {
                        $perPaxDiscount = 0;
                        $perPaxTDS = 0;
                        $perPaxAgentCommission = 0;
                        $perPaxGST = 0;
                        $perPaxOtherCharges = 0;
                    }
                    $save_pax_data[$key] = array(
                        'flight_booking_id' => $booking_lastid,
                        'title' => $Passenger['Title'],
                        'first_name' => trim($Passenger['FirstName']),
                        'last_name' => trim($Passenger['LastName']),
                        'pax_type' => get_paxtype_name($Passenger['PaxType']),
                        'gendar' => get_gender($Passenger['Gender']),
                        'date_of_birth' => check_isset($Passenger, 'DateOfBirth'),
                        'pan_number' => check_isset($Passenger, 'PAN'),
                        'passport_number' => check_isset($Passenger, 'PassportNo'),
                        'passport_expiry' => check_isset($Passenger, 'PassportExpiry'),
                        'nationality' => check_isset($Passenger, 'Nationality'),
                        'lead_pax' => $Passenger['IsLeadPax'],
                        'email_id' => $Passenger['Email'],
                        'mobile_number' => $Passenger['ContactNo'],
                        'address_1' => $Passenger['AddressLine1'],
                        'address_2' => $Passenger['AddressLine2'],
                        'city' => $Passenger['City'],
                        'country_code' => $Passenger['CountryCode'],
                        'country_name' => $Passenger['CountryName'],
                        'booking_status' => "Processing",
                        'ff_airline' => check_isset($Passenger, 'FFAirline'),
                        'ff_number' => check_isset($Passenger, 'FFNumber'),
                        'fare' => json_encode($fare),
                        'baggage' => $baggage,
                        'meal' => $meal,
                        'seat' => $seat,
                    );
                }

                $RestModel->insertBatchDataPrimayDB('flight_booking_travelers', $save_pax_data);
                /*------------------ Update Account Log Data ----------------------------*/
                $PaxName = $passenger_info[0]['FirstName'] . ' ' . $passenger_info[0]['LastName'] . ' X ' . count($passenger_info);
                $Sector = $origin . '-' . $destination;
                $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date, 'AirlineString' => $airline_code . $flight_number, 'TicketNo' => '');
                $account_update_data = array(
                    'acc_ref_number' => $acc_ref_number,
                    'booking_ref_no' => $booking_lastid,
                    'service_log' => json_encode($service_log)
                );
                $RestModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);
                /*------------------ Update Account Log Data ----------------------------*/

                /*------------------ Update Booking  Data ----------------------------*/
                $booking_update_data = array(
                    'booking_ref_number' => $booking_ref_number,
                );
                $RestModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $booking_update_data);
                /*------------------ Update BookingData ----------------------------*/
            }
            /*--------------------------------End Save Booking Data  and Pax Data ------------------------------------------*/
        } elseif ($Is_Save_data == 'true') {
            $booking_lastid = $TTS_Booking_ID;

            $super_admin__booking_pre_fix_code = $RestModel->super_admin_booking_pre_fix_code()['pre_fix'];
            $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_lastid;

            $PaxName = $passenger_info[0]['FirstName'] . ' ' . $passenger_info[0]['LastName'] . ' X ' . count($passenger_info);
            $Sector = $origin . '-' . $destination;
            $service_log = array('PaxName' => $PaxName, 'Sector' => $Sector, 'TravelDate' => $departure_date, 'AirlineString' => $airline_code . $flight_number, 'TicketNo' => '');
            $account_update_data = array(
                'acc_ref_number' => $acc_ref_number,
                'booking_ref_no' => $booking_lastid,
                'service_log' => json_encode($service_log)
            );
            $RestModel->updateUserData('web_partner_account_log', ['id' => $account_log_lastid], $account_update_data);

            $save_book_data = array(
                'payment_status' => $payment_status,
                'booking_ref_number' => $booking_ref_number
            );

            $RestModel->updateUserData('flight_booking_list', ['id' => $booking_lastid], $save_book_data);
        }

        return array('account_log_lastid' => $account_log_lastid, 'booking_lastid' => $booking_lastid);
    }

    public function GetBookingDetail()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->getbookingdetail_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {

            $condition = ['web_partner_id' => $this->userauthdata['web_partner_id'], 'id' => $input['BookingId'], 'tts_search_token' => $input['SearchTokenId']];
            if ($input['PNR']) {
                $condition['pnr'] = $input['PNR'];
            }
            $RestModel = new RestModel();
            $verify_data = $RestModel->verify_booking_detail($condition);
            if ($verify_data) {
                $response = ConvertBookingDetail($input, $verify_data);
                // Insert TTS Logs
                // $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'getbookingdetail');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function CancelRequest()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $validate_cancel = $validate->cancel_validation($input);
        $this->validation->setRules($validate_cancel);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $SearchTokenId = isset($input['SearchTokenId']) ? $input['SearchTokenId'] : "";
            $book_data = $RestModel->verify_booking_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $SearchTokenId]);
            if ($book_data) {

                $loadmodule = $book_data['api_supplier'] . '_Module';
                $response = $this->$loadmodule->SendCancelRequest($input, $book_data, $this->userauthdata);
                // Insert TTS Logs
                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'cancelrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function GetChangeRequestStatus()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $validate_cancel = $validate->cancel_status_validation($input);
        $this->validation->setRules($validate_cancel);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $SearchTokenId = isset($input['SearchTokenId']) ? $input['SearchTokenId'] : "";
            $book_data = $RestModel->verify_booking_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id'], 'tts_search_token' => $SearchTokenId]);
            if ($book_data) {
                if ($book_data['api_supplier'] == "TBO") {
                    $loadmodule = 'TBO_Module';
                    $response = $this->$loadmodule->GetChangeRequestStatus($input, $book_data, $this->userauthdata);
                    // Insert TTS Logs
                    $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $input['SearchTokenId'], $input, $response, 'cancelrequest');
                    return $this->response->setContentType('application/json')->setJSON($response);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    public function GetCancellationCharges()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $validate_cancel = $validate->cancel_charge_validation($input);

        $this->validation->setRules($validate_cancel);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $SearchTokenId = isset($input['SearchTokenId']) ? $input['SearchTokenId'] : "";
            $book_data = $RestModel->verify_booking_detail(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
            if ($book_data) {
                if (($book_data['api_supplier'] == "TBO" && $book_data['is_lcc']) && $book_data['booking_status'] != "Processing") {
                    $loadmodule = 'TBO_Module';
                    $response = $this->$loadmodule->GetCancellationCharges($input, $book_data, $this->userauthdata);
                    // Insert TTS Logs
                    return $this->response->setContentType('application/json')->setJSON($response);
                } else {
                    $message = api_validation_message('invalid_token_error');
                    return api_custom_message(400, $message);
                }
            } else {
                $message = api_validation_message('invalid_token_error');
                return api_custom_message(400, $message);
            }
        }
    }

    function generateTicketInvoice()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_ticket_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingToken = $input['SearchTokenId'];
            $BookingIds = $input['BookingId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $TicketInvoiceDataArray = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareRNRType = array();
            $Bookingstatus = array();
            $PaymentStatus = array();
            $TicketFareBreakUp = array();
            $InvoiceNumber = array();
            if ($BookingIds) {
                $BaseFare = 0;
                $Taxes = 0;
                $ServiceAndOtherCharge = 0;
                $MealBaggageCharge = 0;
                $Discount = 0;
                $TotalAmount = 0;
                $paxName = "";
                $Address = "";
                $City = "";
                $CountryName = "";
                $State = "";
                $InvoiceFareBreakUp = array();
                foreach ($BookingIds as $BookingId) {
                    $logoFolderName = "logo";
                    $TicketInvoiceData = array();
                    $FareBreakUp = array();
                    $BookingDetail = $RestModel->getBookingData($BookingId);

                    if ($BookingDetail) {
                        $rtype = $BookingDetail['trip_indicator'] == 1 ? "OB" : "IB";
                        if ($BookingDetail['booking_status'] == "Confirmed" || $BookingDetail['booking_status'] == "Hold" || $BookingDetail['booking_status'] == "Cancelled" || $BookingDetail['booking_status'] == "PartialCancelled") {
                            $Pnr[$rtype] = $BookingDetail['pnr'];
                            $BookingRefNumber[$rtype] = $BookingDetail['booking_ref_number'];
                            $FareRNRType[$rtype] = $BookingDetail['is_refundable'] == 1 ? "Refundable" : "Non-Refundable";
                            $Bookingstatus[$rtype] = $BookingDetail['booking_status'];
                            $PaymentStatus[$rtype] = $BookingDetail['payment_status'];

                            $searchData = json_decode($BookingDetail['search_request'], true);
                            $adultCount = $searchData['Adult'];
                            $childCount = $searchData['Child'];
                            $infantCount = $searchData['Infant'];
                            $totalMarkupablepax = $adultCount + $childCount;
                            if ($BookingDetail['is_domestic']) {
                                $airlineLogoClass = "domAirLogo";
                            } else {
                                $airlineLogoClass = "intAirLogo";
                            }
                            $FareBreakUp = array();
                            $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                            $WebPMarkUp = 0;
                            $WebPDiscount = 0;
                            if ($HtmlType == "Ticket" || $HtmlType == "CustomerInvoice") {
                                $WebPMarkUp = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                                $WebPDiscount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                                $addMarkupInTax = 0;
                                $addMarkupInServiceCharge = 0;
                                if (isset($fareBreakupArray['WebPDisplayMarkup']) && $fareBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
                                    $addMarkupInServiceCharge = $WebPMarkUp;
                                } else {
                                    $addMarkupInTax = $WebPMarkUp;
                                }
                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['Tax'] + $addMarkupInTax;
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $addMarkupInServiceCharge;
                                $Discount = $WebPDiscount + $Discount;
                                $TotalAmount = $TotalAmount + $fareBreakupArray['PublishedPrice'] + $WebPMarkUp - $WebPDiscount;
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['Tax'] + $addMarkupInTax, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $addMarkupInServiceCharge, "LabelText" => "Other & Service Charges"),
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        "Discount" => array("Value" => $WebPDiscount, "LabelText" => "Discount (-)"),

                                    ),
                                    "TotalAmount" => array("Value" => $fareBreakupArray['PublishedPrice'] + $WebPMarkUp - $WebPDiscount, "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );

                                $TicketFareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $BaseFare, "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $Taxes, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge, "LabelText" => "Other & Service Charges"),
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),

                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                                );

                            }

                            $AccountDataInfo = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "flight"));
                            $InvoiceNumber[$rtype] = isset($AccountDataInfo['acc_ref_number']) ? $AccountDataInfo['acc_ref_number'] : "";

                            if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {

                                $WebPPerPaxMarkUp = 0;
                                $addPerPaxMarkupInTax = 0;
                                $addPerPaxMarkupInServiceCharge = 0;
                                $travelersInfo = json_decode($BookingDetail['travelersInfo'], true);
                                if ($HtmlType == "CustomerInvoice") {
                                    if ($WebPMarkUp) {
                                        $WebPPerPaxMarkUp = round($WebPMarkUp / $totalMarkupablepax);
                                        if (isset($fareBreakupArray['WebPDisplayMarkup']) && $fareBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
                                            $addPerPaxMarkupInServiceCharge = $WebPPerPaxMarkUp;
                                        } else {
                                            $addPerPaxMarkupInTax = $WebPPerPaxMarkUp;
                                        }
                                    }
                                }
                                if ($travelersInfo) {
                                    foreach ($travelersInfo as $paxkey => $travelers) {
                                        if ($travelers['lead_pax'] == 1) {
                                            $paxName = $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name'];
                                            $Address = $travelers['address_1'];
                                            $City = $travelers['city'];
                                            $CountryName = $travelers['country_name'];
                                        }
                                        $farebreakUp = json_decode($travelers['fare'], true);
                                        if ($travelers['pax_type'] == "Adult" || $travelers['pax_type'] == "Child") {
                                            $farebreakUp['ServiceCharges'] = $farebreakUp['ServiceCharges'] + $addPerPaxMarkupInServiceCharge;
                                            $farebreakUp['Tax'] = $farebreakUp['Tax'] + $addPerPaxMarkupInTax;
                                        }
                                        $travelers['fare'] = $farebreakUp;
                                        $travelersInfo[$paxkey] = $travelers;
                                    }
                                }
                                $BookingDetail['travelersInfo'] = json_encode($travelersInfo);
                            }
                            if ($HtmlType == "AgencyInvoice") {
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                                        /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                                        "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'], "LabelText" => "Comm Earned (-)"),
                                        /*   "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"), */
                                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                                    ),
                                    "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                            }


                            $TicketInvoiceData['JourneyType'] = $BookingDetail['journey_type'];
                            $TicketInvoiceData['Origin'] = $BookingDetail['origin'];
                            $TicketInvoiceData['Destination'] = $BookingDetail['destination'];
                            $TicketInvoiceData['DepartureDate'] = $BookingDetail['departure_date'];
                            $TicketInvoiceData['IsDomestic'] = $BookingDetail['is_domestic'];
                            $TicketInvoiceData['IsRefundable'] = $BookingDetail['is_refundable'];
                            $TicketInvoiceData['FareType'] = $BookingDetail['fare_type'];
                            $TicketInvoiceData['FareRule'] = json_decode($BookingDetail['fare_rule'], true);
                            $TicketInvoiceData['TotalPrice'] = $BookingDetail['total_price'];
                            $TicketInvoiceData['Segments'] = json_decode($BookingDetail['segments'], true);
                            $TicketInvoiceData['AirlinePNR'] = json_decode($BookingDetail['airline_pnr'], true);
                            $TicketInvoiceData['FareBreakUp'] = $FareBreakUp;
                            $TicketInvoiceData['TravelersInfo'] = json_decode($BookingDetail['travelersInfo'], true);
                            $TicketInvoiceDataArray[$rtype] = $TicketInvoiceData;
                        }
                        $JourneyType = $BookingDetail['journey_type'];
                        $BookingDate = $BookingDetail['created'];
                        $web_partner_id = $BookingDetail['web_partner_id'];
                    }
                }
                if ($rtype == "OB") {
                    $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                }
                /* if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    unset($TicketInvoiceDataArray["IB"]);
                } */

                if ($searchData['JourneyType'] == 2 && $BookingDetail['is_domestic']) {
                    if ($input['TicketInvoiceJourney'] == "Onward") {
                        if (isset($TicketInvoiceDataArray['IB'])) {
                            unset($TicketInvoiceDataArray["IB"]);
                            unset($BookingRefNumber["IB"]);
                            unset($Pnr["IB"]);
                            unset($FareRNRType["IB"]);
                            unset($Bookingstatus["IB"]);
                            $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                            unset($PaymentStatus["IB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["IB"]);
                            }
                        }
                    }
                    if ($input['TicketInvoiceJourney'] == "Return") {
                        $InvoiceFareBreakUp = $TicketInvoiceDataArray['IB']['FareBreakUp'];
                        if (isset($TicketInvoiceDataArray['OB'])) {
                            unset($TicketInvoiceDataArray["OB"]);
                            unset($BookingRefNumber["OB"]);
                            unset($Pnr["OB"]);
                            unset($FareRNRType["OB"]);
                            unset($Bookingstatus["OB"]);
                            unset($PaymentStatus["OB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["OB"]);
                            }
                        }
                    }
                }
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $web_partner_id), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pan_number");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");
                if (isset($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $TicketFareBreakUp = array();
                }
                if (!empty($InvoiceNumber)) {
                    $data['InvoiceNumber'] = implode(",", array_values($InvoiceNumber));
                    $data['InvoiceDate'] = custom_date_format($BookingDate);
                }
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['JourneyType'] = $JourneyType;
                $data['TicketInvoiceData'] = $TicketInvoiceDataArray;
                $data['BookingRefNumber'] = implode(",", array_values($BookingRefNumber));
                $data['Pnr'] = implode(",", array_values($Pnr));
                $data['FareRNRType'] = implode(",", array_values($FareRNRType));
                $data['Bookingstatus'] = implode(",", array_values($Bookingstatus));
                $data['PaymentStatus'] = implode(",", array_values($PaymentStatus));
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];
                if ($HtmlType == "CustomerInvoice" || $HtmlType == "AgencyInvoice") {
                    $data['FareBreakUp'] = $InvoiceFareBreakUp;
                } else {
                    $data['FareBreakUp'] = $TicketFareBreakUp;
                }
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                if ($partnerInfo['company_logo'] != "") {
                    $data['CompanyLogo'] = root_url . 'uploads/logo/' . $logoFolderName . '/' . $partnerInfo['company_logo'];
                }
                if ($partnerInfo['company_logo'] == "") {
                    $data['CompanyLogo'] = root_url . 'uploads/agent/' . $logoFolderName . '/' . $SuperAdminInfo['logo'];
                }
                if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
                    $data['State'] = $SuperAdminInfo['state'];
                    $data['City'] = $SuperAdminInfo['city'];
                    $data['CompanyName'] = $SuperAdminInfo['company_name'];
                    $data['Address'] = $SuperAdminInfo['address'];
                    $data['Pincode'] = $SuperAdminInfo['pincode'];
                    $data['SupportNo'] = $SuperAdminInfo['support_no'];
                    $data['SupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['Country'] = $SuperAdminInfo['country'];
                    $data['CompanyLogo'] = root_url . 'uploads/logo/' . $logoFolderName . '/' . $SuperAdminInfo['logo'];
                }

                if ($HtmlType == "CustomerInvoice") {
                    $data['SuperAdminState'] = $partnerInfo['state'];
                    $data['SuperAdminCity'] = $partnerInfo['city'];
                    $data['SuperAdminCompanyName'] = $partnerInfo['company_name'];
                    $data['SuperAdminAddress'] = $partnerInfo['address'];
                    $data['SuperAdminPincode'] = $partnerInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $partnerInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $partnerInfo['support_email'];
                    $data['SuperAdminGstNo'] = $partnerInfo['company_gst_no'];
                    $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                    $data['SuperAdminCountry'] = $partnerInfo['country'];
                    $data['State'] = "";
                    $data['City'] = $City;
                    $data['CompanyName'] = $paxName;
                    $data['Address'] = $Address;
                    $data['Pincode'] = "";
                    $data['SupportNo'] = "";
                    $data['SupportEmail'] = "";
                    $data['Country'] = $CountryName;
                    $data['GstNo'] = isset($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";

                    $data['PanNo'] = "";

                }
                if ($HtmlType == "Ticket") {
                    $html = view('Modules\Airservice\Views\ticket', $data);
                } else if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Airservice\Views\invoice', $data);
                }
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }


    function generateCreditNote()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_ticket_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $traveller_ref_number = $input['traveller_ref_number'];
            $BookingToken = $input['SearchTokenId'];
            $BookingIds = $input['BookingId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $TicketInvoiceDataArray = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareRNRType = array();
            $Bookingstatus = array();
            $PaymentStatus = array();
            $TicketFareBreakUp = array();
            $InvoiceNumber = array();
            if ($BookingIds) {
                $BaseFare = 0;
                $Taxes = 0;
                $ServiceAndOtherCharge = 0;
                $MealBaggageCharge = 0;
                $Discount = 0;
                $TotalAmount = 0;
                $paxName = "";
                $Address = "";
                $City = "";
                $CountryName = "";
                $State = "";
                $InvoiceFareBreakUp = array();
                foreach ($BookingIds as $BookingId) {
                    $logoFolderName = "logo";
                    $TicketInvoiceData = array();
                    $FareBreakUp = array();
                    $BookingDetail = $RestModel->getBookingData($BookingId);
                    if ($BookingDetail) {
                        $travellers = $RestModel->getTravellerData($traveller_ref_number);
                        $CreditNote = $RestModel->getaccountLogCreditNote($BookingDetail['web_partner_id'], $travellers['refund_account_id']);
                        $travelersInfo = json_decode($BookingDetail['travelersInfo'], true);

                        $fare = json_decode($travellers['fare'], true);

                        $travellerCount = count($travelersInfo);
                        $rtype = $BookingDetail['trip_indicator'] == 1 ? "OB" : "IB";
                        if ($BookingDetail['booking_status'] == "Confirmed" || $BookingDetail['booking_status'] == "Hold" || $BookingDetail['booking_status'] == "Cancelled" || $BookingDetail['booking_status'] == "PartialCancelled") {
                            $Pnr[$rtype] = $BookingDetail['pnr'];
                            $BookingRefNumber[$rtype] = $BookingDetail['booking_ref_number'];
                            $FareRNRType[$rtype] = $BookingDetail['is_refundable'] == 1 ? "Refundable" : "Non-Refundable";
                            $Bookingstatus[$rtype] = $BookingDetail['booking_status'];
                            $PaymentStatus[$rtype] = $BookingDetail['payment_status'];

                            $searchData = json_decode($BookingDetail['search_request'], true);
                            $adultCount = $searchData['Adult'];
                            $childCount = $searchData['Child'];
                            $infantCount = $searchData['Infant'];
                            $totalMarkupablepax = $adultCount + $childCount;
                            if ($BookingDetail['is_domestic']) {
                                $airlineLogoClass = "domAirLogo";
                            } else {
                                $airlineLogoClass = "intAirLogo";
                            }
                            $FareBreakUp = array();
                            $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                            $WebPMarkUp = 0;
                            $WebPDiscount = 0;
                            if ($HtmlType == "Ticket" || $HtmlType == "CustomerInvoice") {
                                $WebPMarkUp = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                                $WebPDiscount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['Tax'];
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $WebPMarkUp;
                                $Discount = $WebPDiscount + $Discount;
                                $TotalAmount = $TotalAmount + $fareBreakupArray['PublishedPrice'] + $WebPMarkUp - $WebPDiscount;
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'] / $travellerCount, "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['Tax'] / $travellerCount, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => ($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $WebPMarkUp) / $travellerCount, "LabelText" => "Other & Service Charges"),
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        "Discount" => array("Value" => $WebPDiscount / $travellerCount, "LabelText" => "Discount (-)"),

                                    ),

                                    "GSTDetails" => $fareBreakupArray['GST']
                                );

                                $TicketFareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $BaseFare / $travellerCount, "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $Taxes, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge / $travellerCount, "LabelText" => "Other & Service Charges"),
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        "Discount" => array("Value" => $Discount / $travellerCount, "LabelText" => "Discount (-)"),

                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount / $travellerCount, "LabelText" => "Total Amount"),
                                );

                            }


                            if ($HtmlType == "CreditNote" || $HtmlType == "CustomerInvoice") {
                                $InvoiceNumber[$rtype] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "flight"))['acc_ref_number'];
                                $WebPPerPaxMarkUp = 0;

                                if ($WebPMarkUp) {
                                    $WebPPerPaxMarkUp = round($WebPMarkUp / $totalMarkupablepax);
                                }
                                if ($travelersInfo) {
                                    foreach ($travelersInfo as $paxkey => $travelers) {
                                        if ($travelers['lead_pax'] == 1) {
                                            $paxName = $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name'];
                                            $Address = $travelers['address_1'];
                                            $City = $travelers['city'];
                                            $CountryName = $travelers['country_name'];
                                        }
                                        $farebreakUp = json_decode($travelers['fare'], true);
                                        if ($travelers['pax_type'] == "Adult" || $travelers['pax_type'] == "Child") {
                                            $farebreakUp['ServiceCharges'] = $farebreakUp['ServiceCharges'] + $WebPPerPaxMarkUp;
                                        }
                                        $travelers['fare'] = $farebreakUp;
                                        $travelersInfo[$paxkey] = $travelers;
                                    }
                                }
                                $BookingDetail['travelersInfo'] = json_encode($travelersInfo);


                            }
                            if ($HtmlType == "CreditNote") {
                                /*  $AgentCommission = round_value(($fareBreakupArray['AgentCommission']) / $travellerCount);
                                 $Discount = round_value(($fareBreakupArray['Discount']) / $travellerCount);
                                 $ServiceAndOtherCharge = round_value(($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges']) / $travellerCount);

                                 $OfferedPrice = ($fareBreakupArray['OfferedPrice'] / $travellerCount);
                                 $OfferedPrice = ($OfferedPrice - $ServiceAndOtherCharge);
                                 $TotalPrice = round_value($OfferedPrice + $AgentCommission + $Discount); */
                                $OtherCharges = 0;
                                $Discount = 0;
                                $TDS = 0;
                                $GSTAmount = 0;
                                $OfferedPrice = 0;
                                $AgentCommission = 0;
                                if (isset($fare['OtherCharges']) && $fare['OtherCharges'] != null) {
                                    $OtherCharges = $fare['OtherCharges'];
                                }
                                if (isset($fare['Discount']) && $fare['Discount'] != null) {
                                    $Discount = $fare['Discount'];
                                }
                                if (isset($fare['TDS']) && $fare['TDS'] != null) {
                                    $TDS = $fare['TDS'];
                                }
                                if (isset($fare['AgentCommission']) && $fare['AgentCommission'] != null) {
                                    $AgentCommission = $fare['AgentCommission'];
                                }
                                if (isset($fare['GSTAmount']) && $fare['GSTAmount'] != null) {
                                    $GSTAmount = $fare['GSTAmount'];
                                }
                                if (isset($fare['OfferedPrice']) && $fare['OfferedPrice'] != null) {
                                    $OfferedPrice = $fare['OfferedPrice'];
                                }

                                $ServiceAndOtherCharge = $OtherCharges + $fare['ServiceCharges'];

                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => round_value($fare['BaseFare']), "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => round_value($fare['Tax']), "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge, "LabelText" => "Other & Service Charges"),
                                        /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
                                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                                        /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                                        "CommEarned" => array("Value" => round_value($AgentCommission + $Discount), "LabelText" => "Comm Earned (-)"),
                                        /*  "Discount" => array("Value" => $Discount, "LabelText" => "Discount (+)"), */
                                        "GSTAmount" => array("Value" => round_value($GSTAmount), "LabelText" => "GST Amount (+)"),
                                        "TDS" => array("Value" => round_value($TDS), "LabelText" => "TDS (+)")
                                    ),
                                    "TotalAmount" => array("Value" => $OfferedPrice + $TDS, "LabelText" => "Total Amount"),

                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                            }


                            $TicketInvoiceData['JourneyType'] = $BookingDetail['journey_type'];
                            $TicketInvoiceData['Origin'] = $BookingDetail['origin'];
                            $TicketInvoiceData['Destination'] = $BookingDetail['destination'];
                            $TicketInvoiceData['DepartureDate'] = $BookingDetail['departure_date'];
                            $TicketInvoiceData['IsDomestic'] = $BookingDetail['is_domestic'];
                            $TicketInvoiceData['IsRefundable'] = $BookingDetail['is_refundable'];
                            $TicketInvoiceData['FareType'] = $BookingDetail['fare_type'];
                            $TicketInvoiceData['FareRule'] = json_decode($BookingDetail['fare_rule'], true);
                            $TicketInvoiceData['TotalPrice'] = $BookingDetail['total_price'];
                            $TicketInvoiceData['Segments'] = json_decode($BookingDetail['segments'], true);
                            $TicketInvoiceData['FareBreakUp'] = $FareBreakUp;
                            $TicketInvoiceData['TravelersInfo'] = $travellers;
                            $TicketInvoiceDataArray[$rtype] = $TicketInvoiceData;
                        }
                        $JourneyType = $BookingDetail['journey_type'];
                        $BookingDate = $BookingDetail['created'];
                        $web_partner_id = $BookingDetail['web_partner_id'];
                    }
                }

                if ($rtype == "OB") {
                    $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                }
                /* if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    unset($TicketInvoiceDataArray["IB"]);
                } */

                if ($searchData['JourneyType'] == 2 && $BookingDetail['is_domestic']) {
                    if ($input['TicketInvoiceJourney'] == "Onward") {
                        if (isset($TicketInvoiceDataArray['IB'])) {
                            unset($TicketInvoiceDataArray["IB"]);
                            unset($BookingRefNumber["IB"]);
                            unset($Pnr["IB"]);
                            unset($FareRNRType["IB"]);
                            unset($Bookingstatus["IB"]);
                            $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                            unset($PaymentStatus["IB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["IB"]);
                            }
                        }
                    }
                    if ($input['TicketInvoiceJourney'] == "Return") {
                        $InvoiceFareBreakUp = $TicketInvoiceDataArray['IB']['FareBreakUp'];
                        if (isset($TicketInvoiceDataArray['OB'])) {
                            unset($TicketInvoiceDataArray["OB"]);
                            unset($BookingRefNumber["OB"]);
                            unset($Pnr["OB"]);
                            unset($FareRNRType["OB"]);
                            unset($Bookingstatus["OB"]);
                            unset($PaymentStatus["OB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["OB"]);
                            }
                        }
                    }
                }
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $web_partner_id), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pan_number");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");
                if (isset($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $TicketFareBreakUp = array();
                }
                if (!empty($InvoiceNumber)) {
                    $data['InvoiceNumber'] = implode(",", array_values($InvoiceNumber));
                    $data['InvoiceDate'] = custom_date_format($BookingDate);
                }
                $data['CreditNoteDate'] = custom_date_format($CreditNote['created']);
                $data['CreditNoteNo'] = $CreditNote['acc_ref_number'];
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['JourneyType'] = $JourneyType;
                $data['TicketInvoiceData'] = $TicketInvoiceDataArray;
                $data['BookingRefNumber'] = implode(",", array_values($BookingRefNumber));
                $data['Pnr'] = implode(",", array_values($Pnr));
                $data['FareRNRType'] = implode(",", array_values($FareRNRType));
                $data['Bookingstatus'] = implode(",", array_values($Bookingstatus));
                $data['PaymentStatus'] = implode(",", array_values($PaymentStatus));
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];
                if ($HtmlType == "CustomerInvoice" || $HtmlType == "CreditNote") {
                    $data['FareBreakUp'] = $InvoiceFareBreakUp;
                } else {
                    $data['FareBreakUp'] = $TicketFareBreakUp;
                }
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                if ($partnerInfo['company_logo'] != "") {
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $partnerInfo['company_logo'];
                }
                if ($partnerInfo['company_logo'] == "") {
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['logo'];
                }
                if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
                    $data['State'] = $SuperAdminInfo['state'];
                    $data['City'] = $SuperAdminInfo['city'];
                    $data['CompanyName'] = $SuperAdminInfo['company_name'];
                    $data['Address'] = $SuperAdminInfo['address'];
                    $data['Pincode'] = $SuperAdminInfo['pincode'];
                    $data['SupportNo'] = $SuperAdminInfo['support_no'];
                    $data['SupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['Country'] = $SuperAdminInfo['country'];
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['logo'];
                }


                if ($HtmlType == "CreditNote") {
                    $html = View('Modules\Airservice\Views\credit-note-invoice', $data);
                }
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }

    function generateWLTicketInvoice_bck()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_ticket_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingToken = $input['SearchTokenId'];
            $BookingIds = $input['BookingId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $TicketInvoiceDataArray = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareRNRType = array();
            $Bookingstatus = array();
            $PaymentStatus = array();
            $TicketFareBreakUp = array();
            $InvoiceNumber = array();
            $CompanyLogo = "";
            $GstNo = "";
            if ($BookingIds) {
                $BaseFare = 0;
                $Taxes = 0;
                $ServiceAndOtherCharge = 0;
                $TDS = 0;
                $MealBaggageCharge = 0;
                $Discount = 0;
                $TotalAmount = 0;
                $GST = 0;
                $paxName = "";
                $Address = "";
                $City = "";
                $CountryName = "";
                $State = "";
                $InvoiceFareBreakUp = array();
                $TotalMealCharges = 0;
                $TotalSeatCharges = 0;
                $TotalBaggageCharges = 0;
                $web_partner_id = "";
                $web_partner_setting = array();
                foreach ($BookingIds as $BookingId) {
                    $addagentMarkupInTax = 0;
                    $addagentMarkupInServiceCharge = 0;
                    $AgentWebPDiscount = 0;
                    $AgentWebPMarkUp = 0;
                    $logoFolderName = "logo";
                    $TicketInvoiceData = array();
                    $FareBreakUp = array();
                    $BookingDetail = $RestModel->getBookingData($BookingId);
                    if ($BookingDetail) {
                        $web_partner_id = $BookingDetail['web_partner_id'];
                        if (!empty($web_partner_id)) {
                            $web_partner_setting = $RestModel->getwhiteLabelSetting($web_partner_id);
                        }
                        $rtype = $BookingDetail['trip_indicator'] == 1 ? "OB" : "IB";
                        if ($BookingDetail['booking_status'] == "Confirmed" || $BookingDetail['booking_status'] == "Hold" || $BookingDetail['booking_status'] == "Cancelled" || $BookingDetail['booking_status'] == "PartialCancelled") {
                            $Pnr[$rtype] = $BookingDetail['pnr'];
                            $BookingRefNumber[$rtype] = $BookingDetail['booking_ref_number'];
                            $FareRNRType[$rtype] = $BookingDetail['is_refundable'] == 1 ? "Refundable" : "Non-Refundable";
                            $Bookingstatus[$rtype] = $BookingDetail['booking_status'];
                            $PaymentStatus[$rtype] = $BookingDetail['payment_status'];

                            $searchData = json_decode($BookingDetail['search_request'], true);
                            $adultCount = $searchData['Adult'];
                            $childCount = $searchData['Child'];
                            $infantCount = $searchData['Infant'];
                            $totalMarkupablepax = $adultCount + $childCount;
                            if ($BookingDetail['is_domestic']) {
                                $airlineLogoClass = "domAirLogo";
                            } else {
                                $airlineLogoClass = "intAirLogo";
                            }
                            $FareBreakUp = array();
                            if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                                $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
                                $accountLogTable = "customer_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                            }
                            if ($UserType == "WebPartner") {
                                $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                                $accountLogTable = "web_partner_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id']);
                            }

                            if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                                $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
                                $accountLogTable = "agent_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                                if ($UserType == "wl-agent-customer") {
                                    $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                                    $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                                    $AgentWebPDisplayMarkup = isset($fareBreakupArray['DisplayMarkup']) ? $fareBreakupArray['DisplayMarkup'] : "in_tax";
                                    if (isset($fareBreakupArray['DisplayMarkup']) && $fareBreakupArray['DisplayMarkup'] == 'in_service_charge') {
                                        $addagentMarkupInServiceCharge = $AgentWebPMarkUp;
                                    } else {
                                        $addagentMarkupInTax = $AgentWebPMarkUp;
                                    }
                                }
                            }
                            $couponAmount = 0;
                            if ($BookingDetail['booking_source'] == "Wl_b2b") {
                                $TDS = $fareBreakupArray['TDS'];
                            }
                            $couponInfo = json_decode($BookingDetail['coupon_info'], true);
                            if (!empty($couponInfo)) {
                                $couponAmount = (isset($couponInfo['couponAmount']) && $couponInfo['couponAmount'] > 0) ? $couponInfo['couponAmount'] : 0;
                            }

                            if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                                $InvoiceNumberInfo = $RestModel->getBookingAccountInfo($accountLogTable, "invoice_number", $accountWhere);
                                $InvoiceNumber[$rtype] = (isset($InvoiceNumberInfo['invoice_number'])) ? $InvoiceNumberInfo['invoice_number'] : '';
                            }
                            $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
                            $conveniencefee = (isset($fareBreakupArray['convenienceFee']) && $fareBreakupArray['convenienceFee'] > 0) ? $fareBreakupArray['convenienceFee'] : 0;
                            $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                            $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                            $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                            $TotalMealCharges = $TotalMealCharges + $MealCharge;
                            $TotalSeatCharges = $TotalSeatCharges + $SeatCharge;
                            $TotalBaggageCharges = $TotalBaggageCharges + $BaggageCharge;
                            if ($HtmlType == "Ticket" || $HtmlType == "CustomerInvoice") {

                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax;
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge;
                                if ($UserType == "wl-agent-customer") {
                                    $Discount = $AgentWebPDiscount + $Discount;
                                    $TotalAmount = $TotalAmount + $fareBreakupArray['PublishedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount;
                                } else {
                                    $Discount = $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount + $Discount;
                                    $TotalAmount = $TotalAmount + $fareBreakupArray['OfferedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount;
                                }
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge, "LabelText" => "Service Charges"),
                                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                                        "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount + $MealCharge - $couponAmount + $conveniencefee + $BaggageCharge, "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                                if ($TDS) {
                                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }
                                if ($couponAmount) {
                                    $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $FareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }

                                $TicketFareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $BaseFare, "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $Taxes, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge, "LabelText" => "Other & Service Charges"),
                                        "MealCharges" => array("Value" => round_value($TotalMealCharges), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($TotalBaggageCharges), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                                        "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount + $TotalBaggageCharges + $TotalMealCharges - $couponAmount + $conveniencefee, "LabelText" => "Total Amount"),
                                );
                                if ($TDS) {
                                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }

                                if ($couponAmount) {
                                    $TicketFareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $TicketFareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }
                            }

                            $wl_agent_id = 0;
                            if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                                $addPerPaxMarkupInTax = 0;
                                $addPerPaxMarkupInServiceCharge = 0;
                                $agentPerPaxMarkUp = 0;
                                $addPeragentPaxMarkupInTax = 0;
                                $addPeragentPaxMarkupInServiceCharge = 0;
                                $travelersInfo = json_decode($BookingDetail['travelersInfo'], true);
                                if ($UserType == "wl-agent-customer") {
                                    $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                                    $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                                    if ($AgentWebPMarkUp) {
                                        $agentPerPaxMarkUp = round($AgentWebPMarkUp / $totalMarkupablepax);
                                        if (isset($fareBreakupArray['DisplayMarkup']) && $fareBreakupArray['DisplayMarkup'] == 'in_service_charge') {
                                            $addPeragentPaxMarkupInServiceCharge = $agentPerPaxMarkUp;
                                        } else {
                                            $addPeragentPaxMarkupInTax = $agentPerPaxMarkUp;
                                        }
                                    }
                                }
                                $farebreakUp = array();
                                if ($travelersInfo) {
                                    foreach ($travelersInfo as $paxkey => $travelers) {
                                        if ($travelers['lead_pax'] == 1) {
                                            $paxName = $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name'];
                                            $Address = $travelers['address_1'];
                                            $City = $travelers['city'];
                                            $CountryName = $travelers['country_name'];
                                        }
                                        if ($UserType == "WebPartner") {
                                            $farebreakUp = json_decode($travelers['fare'], true);
                                        }
                                        if ($UserType == "wl-agent-customer" || $UserType == "wl-agent") {
                                            $farebreakUp = json_decode($travelers['agent_fare'], true);
                                        }
                                        if ($UserType == "wl-customer") {
                                            $farebreakUp = json_decode($travelers['customer_fare'], true);
                                        }

                                        if ($UserType == "wl-agent-customer") {
                                            if ($travelers['pax_type'] == "Adult" || $travelers['pax_type'] == "Child") {
                                                $farebreakUp['ServiceCharges'] = $farebreakUp['ServiceCharges'] + $addPerPaxMarkupInServiceCharge + $addPeragentPaxMarkupInServiceCharge;
                                                $farebreakUp['Tax'] = $farebreakUp['Tax'] + $addPerPaxMarkupInTax + $addPeragentPaxMarkupInTax;
                                            }
                                        }

                                        $travelers['fare'] = $farebreakUp;
                                        $travelersInfo[$paxkey] = $travelers;
                                    }
                                }
                                $BookingDetail['travelersInfo'] = json_encode($travelersInfo);

                            }




                            if ($HtmlType == "AgencyInvoice") {

                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax;
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge;
                                $Discount = $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount + $Discount;
                                $TotalAmount = $TotalAmount + $fareBreakupArray['OfferedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount;
                                $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
                                $conveniencefee = (isset($fareBreakupArray['convenienceFee']) && $fareBreakupArray['convenienceFee'] > 0) ? $fareBreakupArray['convenienceFee'] : 0;
                                if ($BookingDetail['booking_source'] == "Wl_b2b") {
                                    $TDS = $fareBreakupArray['TDS'];
                                }
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge, "LabelText" => "Service Charges"),
                                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount, "LabelText" => "Discount (-)"),
                                        "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $fareBreakupArray['OfferedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount + $MealCharge - $couponAmount + $conveniencefee + $BaggageCharge, "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                                if ($TDS) {
                                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }
                                if ($couponAmount) {
                                    $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $FareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }
                            }

                            $TicketInvoiceData['JourneyType'] = $BookingDetail['journey_type'];
                            $TicketInvoiceData['Origin'] = $BookingDetail['origin'];
                            $TicketInvoiceData['Destination'] = $BookingDetail['destination'];
                            $TicketInvoiceData['DepartureDate'] = $BookingDetail['departure_date'];
                            $TicketInvoiceData['IsDomestic'] = $BookingDetail['is_domestic'];
                            $TicketInvoiceData['IsRefundable'] = $BookingDetail['is_refundable'];
                            $TicketInvoiceData['FareType'] = $BookingDetail['fare_type'];
                            $TicketInvoiceData['FareRule'] = json_decode($BookingDetail['fare_rule'], true);
                            $TicketInvoiceData['TotalPrice'] = $BookingDetail['total_price'];
                            $TicketInvoiceData['Segments'] = json_decode($BookingDetail['segments'], true);
                            $TicketInvoiceData['AirlinePNR'] = json_decode($BookingDetail['airline_pnr'], true);
                            $TicketInvoiceData['FareBreakUp'] = $FareBreakUp;
                            $TicketInvoiceData['TravelersInfo'] = json_decode($BookingDetail['travelersInfo'], true);
                            // $BarCodeInfo = Rest::generateBarcodeData($BookingDetail);
                            // $TicketInvoiceData['BarCodeInfo'] = $BarCodeInfo;
                            $TicketInvoiceDataArray[$rtype] = $TicketInvoiceData;
                        }
                        $JourneyType = $BookingDetail['journey_type'];
                        $BookingDate = $BookingDetail['created'];
                        $web_partner_id = $BookingDetail['web_partner_id'];
                        $wl_agent_id = $BookingDetail['wl_agent_id'];
                    }
                }
                if ($rtype == "OB") {
                    $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                }
                /* if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    unset($TicketInvoiceDataArray["IB"]);
                } */


                if ($searchData['JourneyType'] == 2 && $BookingDetail['is_domestic']) {
                    if ($input['TicketInvoiceJourney'] == "Onward") {
                        if (isset($TicketInvoiceDataArray['IB'])) {
                            unset($TicketInvoiceDataArray["IB"]);
                            unset($BookingRefNumber["IB"]);
                            unset($Pnr["IB"]);
                            unset($FareRNRType["IB"]);
                            unset($Bookingstatus["IB"]);
                            $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                            unset($PaymentStatus["IB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["IB"]);
                            }
                        }
                    }

                    if ($input['TicketInvoiceJourney'] == "Return") {
                        $InvoiceFareBreakUp = $TicketInvoiceDataArray['IB']['FareBreakUp'];
                        if (isset($TicketInvoiceDataArray['OB'])) {
                            unset($TicketInvoiceDataArray["OB"]);
                            unset($BookingRefNumber["OB"]);
                            unset($Pnr["OB"]);
                            unset($FareRNRType["OB"]);
                            unset($Bookingstatus["OB"]);
                            unset($PaymentStatus["OB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["OB"]);
                            }
                        }
                    }
                }
                if ($web_partner_setting['is_direct_website'] == "active") {
                    $root_url = $web_partner_setting['domain_name'] . '/';
                    if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                        $partnerInfo = [];
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                            $partnerInfo = $SuperAdminInfo;
                        }
                    }

                    if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                        $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number");
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if ($UserType == "wl-agent-customer") {
                            $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number"); //SuperAdminInfo
                            $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number"); //partnerInfo
                            if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                                $SuperAdminInfo = $partnerInfo;
                            }
                        }
                    }
                    if ($UserType == "WebPartner") {
                        $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                    }
                } else {
                    $root_url = $web_partner_setting['domain_name'] . '/';
                    $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                    $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                }


                if (isset($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $TicketFareBreakUp = array();
                    $FareBreakUp = array();
                }
                $data['InvoiceNumber'] = "";
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                if (!empty($InvoiceNumber)) {
                    $data['InvoiceNumber'] = implode(",", array_values($InvoiceNumber));
                    $data['InvoiceDate'] = custom_date_format($BookingDate);
                }
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['JourneyType'] = $JourneyType;
                $data['TicketInvoiceData'] = $TicketInvoiceDataArray;
                $data['BookingRefNumber'] = implode(",", array_values($BookingRefNumber));
                $data['Pnr'] = implode(",", array_values($Pnr));
                $data['FareRNRType'] = implode(",", array_values($FareRNRType));
                $data['Bookingstatus'] = implode(",", array_values($Bookingstatus));
                $data['PaymentStatus'] = implode(",", array_values($PaymentStatus));
                if (isset($partnerInfo) && !empty($partnerInfo)) {
                    $data['Country'] = $partnerInfo['country'];
                    $data['State'] = $partnerInfo['state'];
                    $data['City'] = $partnerInfo['city'];
                    $data['CompanyName'] = $partnerInfo['company_name'];
                    $data['Address'] = $partnerInfo['address'];
                    $data['Pincode'] = $partnerInfo['pincode'];
                    $data['SupportNo'] = $partnerInfo['support_no'];
                    $data['SupportEmail'] = $partnerInfo['support_email'];
                    $data['GstNo'] = $partnerInfo['company_gst_no'];
                    $data['PanNo'] = $partnerInfo['pan_number'];
                } else {
                    $data['GstNo'] = "";
                    $data['PanNo'] = "";
                }
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];

                if ($HtmlType == "AgencyInvoice") {
                    $data['FareBreakUp'] = $InvoiceFareBreakUp;
                } else {
                    $data['FareBreakUp'] = $TicketFareBreakUp;
                }
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                if ($partnerInfo['company_logo'] != "") {
                    $data['CompanyLogo'] = $root_url . 'uploads/agent/' . $logoFolderName . '/' . $partnerInfo['company_logo'];
                }
                if ($partnerInfo['company_logo'] == "") {
                    $data['CompanyLogo'] = $root_url . 'uploads/agent/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                }

                if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
                    $data['State'] = $SuperAdminInfo['state'];
                    $data['City'] = $SuperAdminInfo['city'];
                    $data['CompanyName'] = $SuperAdminInfo['company_name'];
                    $data['Address'] = $SuperAdminInfo['address'];
                    $data['Pincode'] = $SuperAdminInfo['pincode'];
                    $data['SupportNo'] = $SuperAdminInfo['support_no'];
                    $data['SupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['Country'] = $SuperAdminInfo['country'];
                    $data['CompanyLogo'] = $root_url . 'uploads/agent/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                }


                if ($HtmlType == "CustomerInvoice") {
                    $data['SuperAdminState'] = $partnerInfo['state'];
                    $data['SuperAdminCity'] = $partnerInfo['city'];
                    $data['SuperAdminCompanyName'] = $partnerInfo['company_name'];
                    $data['SuperAdminAddress'] = $partnerInfo['address'];
                    $data['SuperAdminPincode'] = $partnerInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $partnerInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $partnerInfo['support_email'];
                    $data['SuperAdminGstNo'] = $partnerInfo['company_gst_no'];
                    $data['SuperAdminCountry'] = $partnerInfo['country'];
                    $data['State'] = "";
                    $data['City'] = $City;
                    $data['CompanyName'] = $paxName;
                    $data['Address'] = $Address;
                    $data['Pincode'] = "";
                    $data['SupportNo'] = "";
                    $data['SupportEmail'] = "";
                    $data['Country'] = $CountryName;
                    $data['GstNo'] = isset($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";

                    $data['PanNo'] = "";
                }
                if ($HtmlType == "Ticket") {
                    $html = view('Modules\Airservice\Views\ticket', $data);
                } else if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Airservice\Views\invoice', $data);
                }

                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }

    function generateWLTicketInvoice()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_ticket_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingToken = $input['SearchTokenId'];
            $BookingIds = $input['BookingId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $TicketInvoiceDataArray = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareRNRType = array();
            $Bookingstatus = array();
            $PaymentStatus = array();
            $TicketFareBreakUp = array();
            $InvoiceNumber = array();
            $CompanyLogo = "";
            $GstNo = "";
            if ($BookingIds) {
                $BaseFare = 0;
                $Taxes = 0;
                $ServiceAndOtherCharge = 0;
                $TDS = 0;
                $MealBaggageCharge = 0;
                $Discount = 0;
                $TotalAmount = 0;
                $GST = 0;
                $paxName = "";
                $Address = "";
                $City = "";
                $CountryName = "";
                $State = "";
                $InvoiceFareBreakUp = array();
                $TotalMealCharges = 0;
                $TotalSeatCharges = 0;
                $TotalBaggageCharges = 0;
                $web_partner_id = "";
                $web_partner_setting = array();
                foreach ($BookingIds as $BookingId) {
                    $addagentMarkupInTax = 0;
                    $addagentMarkupInServiceCharge = 0;
                    $AgentWebPDiscount = 0;
                    $AgentWebPMarkUp = 0;
                    $logoFolderName = "logo";
                    $TicketInvoiceData = array();
                    $FareBreakUp = array();
                    $BookingDetail = $RestModel->getBookingData($BookingId);
                    // pr($BookingDetail);
                    // die;
                    if ($BookingDetail) {
                        $web_partner_id = $BookingDetail['web_partner_id'];
                        if (!empty($web_partner_id)) {
                            $web_partner_setting = $RestModel->getwhiteLabelSetting($web_partner_id);
                        }
                        $rtype = $BookingDetail['trip_indicator'] == 1 ? "OB" : "IB";
                        if ($BookingDetail['booking_status'] == "Confirmed" || $BookingDetail['booking_status'] == "Hold" || $BookingDetail['booking_status'] == "Cancelled" || $BookingDetail['booking_status'] == "PartialCancelled") {
                            $Pnr[$rtype] = $BookingDetail['pnr'];
                            $BookingRefNumber[$rtype] = $BookingDetail['booking_ref_number'];
                            $FareRNRType[$rtype] = $BookingDetail['is_refundable'] == 1 ? "Refundable" : "Non-Refundable";
                            $Bookingstatus[$rtype] = $BookingDetail['booking_status'];
                            $PaymentStatus[$rtype] = $BookingDetail['payment_status'];

                            $searchData = json_decode($BookingDetail['search_request'], true);
                            $adultCount = $searchData['Adult'];
                            $childCount = $searchData['Child'];
                            $infantCount = $searchData['Infant'];
                            $totalMarkupablepax = $adultCount + $childCount;
                            if ($BookingDetail['is_domestic']) {
                                $airlineLogoClass = "domAirLogo";
                            } else {
                                $airlineLogoClass = "intAirLogo";
                            }
                            $FareBreakUp = array();
                            if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                                $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
                                $accountLogTable = "customer_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                            }
                            if ($UserType == "WebPartner") {
                                $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                                $accountLogTable = "web_partner_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id']);
                            }

                            if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                                $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
                                $accountLogTable = "agent_account_log";
                                $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'flight', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                                if ($UserType == "wl-agent-customer") {
                                    $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                                    $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                                    $AgentWebPDisplayMarkup = isset($fareBreakupArray['DisplayMarkup']) ? $fareBreakupArray['DisplayMarkup'] : "in_tax";
                                    if (isset($fareBreakupArray['DisplayMarkup']) && $fareBreakupArray['DisplayMarkup'] == 'in_service_charge') {
                                        $addagentMarkupInServiceCharge = $AgentWebPMarkUp;
                                    } else {
                                        $addagentMarkupInTax = $AgentWebPMarkUp;
                                    }
                                }
                            }
                            $couponAmount = 0;
                            if ($BookingDetail['booking_source'] == "Wl_b2b") {
                                $TDS = $fareBreakupArray['TDS'];
                            }
                            $couponInfo = json_decode($BookingDetail['coupon_info'], true);
                            if (!empty($couponInfo)) {
                                $couponAmount = (isset($couponInfo['couponAmount']) && $couponInfo['couponAmount'] > 0) ? $couponInfo['couponAmount'] : 0;
                            }

                            if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                                $InvoiceNumberInfo = $RestModel->getBookingAccountInfo($accountLogTable, "invoice_number", $accountWhere);
                                $InvoiceNumber[$rtype] = (isset($InvoiceNumberInfo['invoice_number'])) ? $InvoiceNumberInfo['invoice_number'] : '';
                            }
                            $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
                            $conveniencefee = (isset($fareBreakupArray['convenienceFee']) && $fareBreakupArray['convenienceFee'] > 0) ? $fareBreakupArray['convenienceFee'] : 0;
                            $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                            $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                            $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                            $TotalMealCharges = $TotalMealCharges + $MealCharge;
                            $TotalSeatCharges = $TotalSeatCharges + $SeatCharge;
                            $TotalBaggageCharges = $TotalBaggageCharges + $BaggageCharge;
                            if ($HtmlType == "Ticket" || $HtmlType == "CustomerInvoice") {
                                $InvoiceNumberInfo = $RestModel->getBookingAccountInfo($accountLogTable, "invoice_number", $accountWhere);
                                $InvoiceNumber[$rtype] = (isset($InvoiceNumberInfo['invoice_number'])) ? $InvoiceNumberInfo['invoice_number'] : '';
                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax;
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge;
                                if ($UserType == "wl-agent-customer") {
                                    $Discount = $AgentWebPDiscount + $Discount;
                                    $TotalAmount = $TotalAmount + $fareBreakupArray['PublishedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount;
                                } else {
                                    $Discount = $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount + $Discount;
                                    $TotalAmount = $TotalAmount + $fareBreakupArray['OfferedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount;
                                }
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax + $TDS, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge, "LabelText" => "Service Charges"),
                                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                                        "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount + $MealCharge - $couponAmount + $conveniencefee + $BaggageCharge, "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                                if ($TDS) {
                                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }
                                if ($couponAmount) {
                                    $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $FareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }

                                $TicketFareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $BaseFare, "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $Taxes + $TDS, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $ServiceAndOtherCharge, "LabelText" => "Other & Service Charges"),
                                        "MealCharges" => array("Value" => round_value($TotalMealCharges), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($TotalBaggageCharges), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                                        "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $TotalAmount + $TotalBaggageCharges + $TotalMealCharges - $couponAmount + $conveniencefee, "LabelText" => "Total Amount"),
                                );
                                if ($TDS) {
                                    //$TicketFareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }

                                if ($couponAmount) {
                                    $TicketFareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $TicketFareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }
                            }

                            $wl_agent_id = 0;
                            if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                                $addPerPaxMarkupInTax = 0;
                                $addPerPaxMarkupInServiceCharge = 0;
                                $agentPerPaxMarkUp = 0;
                                $addPeragentPaxMarkupInTax = 0;
                                $addPeragentPaxMarkupInServiceCharge = 0;
                                $travelersInfo = json_decode($BookingDetail['travelersInfo'], true);
                                if ($UserType == "wl-agent-customer") {
                                    $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                                    $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                                    if ($AgentWebPMarkUp) {
                                        $agentPerPaxMarkUp = round($AgentWebPMarkUp / $totalMarkupablepax);
                                        if (isset($fareBreakupArray['DisplayMarkup']) && $fareBreakupArray['DisplayMarkup'] == 'in_service_charge') {
                                            $addPeragentPaxMarkupInServiceCharge = $agentPerPaxMarkUp;
                                        } else {
                                            $addPeragentPaxMarkupInTax = $agentPerPaxMarkUp;
                                        }
                                    }
                                }
                                $farebreakUp = array();
                                if ($travelersInfo) {
                                    foreach ($travelersInfo as $paxkey => $travelers) {
                                        if ($travelers['lead_pax'] == 1) {
                                            $paxName = $travelers['title'] . " " . $travelers['first_name'] . " " . $travelers['last_name'];
                                            $Address = $travelers['address_1'];
                                            $City = $travelers['city'];
                                            $CountryName = $travelers['country_name'];
                                        }
                                        if ($UserType == "WebPartner") {
                                            $farebreakUp = json_decode($travelers['fare'], true);
                                        }
                                        if ($UserType == "wl-agent-customer" || $UserType == "wl-agent") {
                                            $farebreakUp = json_decode($travelers['agent_fare'], true);
                                        }
                                        if ($UserType == "wl-customer") {
                                            $farebreakUp = json_decode($travelers['customer_fare'], true);
                                        }

                                        if ($UserType == "wl-agent-customer") {
                                            if ($travelers['pax_type'] == "Adult" || $travelers['pax_type'] == "Child") {
                                                $farebreakUp['ServiceCharges'] = $farebreakUp['ServiceCharges'] + $addPerPaxMarkupInServiceCharge + $addPeragentPaxMarkupInServiceCharge;
                                                $farebreakUp['Tax'] = $farebreakUp['Tax'] + $addPerPaxMarkupInTax + $addPeragentPaxMarkupInTax;
                                            }
                                        }

                                        $travelers['fare'] = $farebreakUp;
                                        $travelersInfo[$paxkey] = $travelers;
                                    }
                                }
                                $BookingDetail['travelersInfo'] = json_encode($travelersInfo);

                            }




                            if ($HtmlType == "AgencyInvoice") {

                                $BaseFare = $BaseFare + $fareBreakupArray['BaseFare'];
                                $Taxes = $Taxes + $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax;
                                $ServiceAndOtherCharge = $ServiceAndOtherCharge + $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge;
                                $Discount = $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount + $Discount;
                                $TotalAmount = $TotalAmount + $fareBreakupArray['OfferedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount;
                                $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
                                $conveniencefee = (isset($fareBreakupArray['convenienceFee']) && $fareBreakupArray['convenienceFee'] > 0) ? $fareBreakupArray['convenienceFee'] : 0;
                                if ($BookingDetail['booking_source'] == "Wl_b2b") {
                                    $TDS = $fareBreakupArray['TDS'];
                                }
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge, "LabelText" => "Service Charges"),
                                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                                        "Discount" => array("Value" => $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'] + $AgentWebPDiscount, "LabelText" => "Discount (-)"),
                                        "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => $fareBreakupArray['OfferedPrice'] + $TDS + $AgentWebPMarkUp - $AgentWebPDiscount + $MealCharge - $couponAmount + $conveniencefee + $BaggageCharge, "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );
                                if ($TDS) {
                                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $TDS, "LabelText" => "TDS (+)");
                                }
                                if ($couponAmount) {
                                    $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                                }

                                if ($conveniencefee) {
                                    $FareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                                }
                            }

                            $TicketInvoiceData['JourneyType'] = $BookingDetail['journey_type'];
                            $TicketInvoiceData['Origin'] = $BookingDetail['origin'];
                            $TicketInvoiceData['Destination'] = $BookingDetail['destination'];
                            $TicketInvoiceData['DepartureDate'] = $BookingDetail['departure_date'];
                            $TicketInvoiceData['IsDomestic'] = $BookingDetail['is_domestic'];
                            $TicketInvoiceData['IsRefundable'] = $BookingDetail['is_refundable'];
                            $TicketInvoiceData['FareType'] = $BookingDetail['fare_type'];
                            $TicketInvoiceData['FareRule'] = json_decode($BookingDetail['fare_rule'], true);
                            $TicketInvoiceData['TotalPrice'] = $BookingDetail['total_price'];
                            $TicketInvoiceData['Segments'] = json_decode($BookingDetail['segments'], true);
                            $TicketInvoiceData['AirlinePNR'] = json_decode($BookingDetail['airline_pnr'], true);
                            $TicketInvoiceData['FareBreakUp'] = $FareBreakUp;
                            $TicketInvoiceData['TravelersInfo'] = json_decode($BookingDetail['travelersInfo'], true);
                            // $BarCodeInfo = Rest::generateBarcodeData($BookingDetail);
                            // $TicketInvoiceData['BarCodeInfo'] = $BarCodeInfo;
                            $TicketInvoiceDataArray[$rtype] = $TicketInvoiceData;
                        }
                        $JourneyType = $BookingDetail['journey_type'];
                        $BookingDate = $BookingDetail['created'];
                        $web_partner_id = $BookingDetail['web_partner_id'];
                        $wl_agent_id = $BookingDetail['wl_agent_id'];
                    }
                }
                if ($rtype == "OB") {
                    $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                }
                /* if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    unset($TicketInvoiceDataArray["IB"]);
                } */


                if ($searchData['JourneyType'] == 2 && $BookingDetail['is_domestic']) {
                    if ($input['TicketInvoiceJourney'] == "Onward") {
                        if (isset($TicketInvoiceDataArray['IB'])) {
                            unset($TicketInvoiceDataArray["IB"]);
                            unset($BookingRefNumber["IB"]);
                            unset($Pnr["IB"]);
                            unset($FareRNRType["IB"]);
                            unset($Bookingstatus["IB"]);
                            $InvoiceFareBreakUp = $TicketInvoiceDataArray['OB']['FareBreakUp'];
                            unset($PaymentStatus["IB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["IB"]);
                            }
                        }
                    }

                    if ($input['TicketInvoiceJourney'] == "Return") {
                        $InvoiceFareBreakUp = $TicketInvoiceDataArray['IB']['FareBreakUp'];
                        if (isset($TicketInvoiceDataArray['OB'])) {
                            unset($TicketInvoiceDataArray["OB"]);
                            unset($BookingRefNumber["OB"]);
                            unset($Pnr["OB"]);
                            unset($FareRNRType["OB"]);
                            unset($Bookingstatus["OB"]);
                            unset($PaymentStatus["OB"]);
                            if (!empty($InvoiceNumber)) {
                                unset($InvoiceNumber["OB"]);
                            }
                        }
                    }
                }
                if ($web_partner_setting['is_direct_website'] == "active") {
                    $root_url = $web_partner_setting['domain_name'] . '/';
                    if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                        $partnerInfo = [];
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                            $partnerInfo = $SuperAdminInfo;
                        }
                    }

                    if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                        $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number");
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if ($UserType == "wl-agent-customer") {
                            $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number"); //SuperAdminInfo
                            $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number"); //partnerInfo
                            if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                                $SuperAdminInfo = $partnerInfo;
                            }
                        }
                    }
                    if ($UserType == "WebPartner") {
                        $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                    }
                } else {
                    $root_url = $web_partner_setting['domain_name'] . '/';
                    $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                    $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                }


                if (isset($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $TicketFareBreakUp = array();
                    $FareBreakUp = array();
                }
                $data['InvoiceNumber'] = "";
                $data['InvoiceDate'] = custom_date_format($BookingDate);
                if (!empty($InvoiceNumber)) {
                    $data['InvoiceNumber'] = implode(",", array_values($InvoiceNumber));
                    $data['InvoiceDate'] = custom_date_format($BookingDate);
                }
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['JourneyType'] = $JourneyType;
                $data['TicketInvoiceData'] = $TicketInvoiceDataArray;
                $data['BookingRefNumber'] = implode(",", array_values($BookingRefNumber));
                $data['Pnr'] = implode(",", array_values($Pnr));
                $data['FareRNRType'] = implode(",", array_values($FareRNRType));
                $data['Bookingstatus'] = implode(",", array_values($Bookingstatus));
                $data['PaymentStatus'] = implode(",", array_values($PaymentStatus));

                if (isset($partnerInfo) && !empty($partnerInfo)) {
                    $data['Country'] = $partnerInfo['country'];
                    $data['State'] = $partnerInfo['state'];
                    $data['City'] = $partnerInfo['city'];
                    $data['CompanyName'] = $partnerInfo['company_name'];
                    $data['Address'] = $partnerInfo['address'];
                    $data['Pincode'] = $partnerInfo['pincode'];
                    $data['SupportNo'] = $partnerInfo['support_no'];
                    $data['SupportEmail'] = $partnerInfo['support_email'];
                    $data['GstNo'] = $partnerInfo['company_gst_no'];
                    $data['PanNo'] = $partnerInfo['pan_number'];
                }
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];

                if ($HtmlType == "AgencyInvoice") {
                    $data['FareBreakUp'] = $InvoiceFareBreakUp;
                } else {
                    $data['FareBreakUp'] = $TicketFareBreakUp;
                }
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                if (isset($input['RequestBy']) && $input['RequestBy'] == "WebPartner") {
                    $data['CompanyLogo'] = $root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                }
                if (isset($input['RequestBy']) && $input['RequestBy'] == "Agent") {
                    $logoFolderName = "agent/logo";
                    $imageString = ($input['HtmlType'] != "Ticket") ? $partnerInfo['company_logo'] : $SuperAdminInfo['company_logo'];
                    $data['CompanyLogo'] = $root_url . 'uploads/' . $logoFolderName . '/' . $imageString;
                }
                if (isset($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
                    $data['State'] = $SuperAdminInfo['state'];
                    $data['City'] = $SuperAdminInfo['city'];
                    $data['CompanyName'] = $SuperAdminInfo['company_name'];
                    $data['Address'] = $SuperAdminInfo['address'];
                    $data['Pincode'] = $SuperAdminInfo['pincode'];
                    $data['SupportNo'] = $SuperAdminInfo['support_no'];
                    $data['GstNo'] = $SuperAdminInfo['company_gst_no'];
                    $data['SupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['Country'] = $SuperAdminInfo['country'];
                    //$data['CompanyLogo'] = $root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                }


                if ($HtmlType == "CustomerInvoice") {
                    $data['SuperAdminState'] = $partnerInfo['state'];
                    $data['SuperAdminCity'] = $partnerInfo['city'];
                    $data['SuperAdminCompanyName'] = $partnerInfo['company_name'];
                    $data['SuperAdminAddress'] = $partnerInfo['address'];
                    $data['SuperAdminPincode'] = $partnerInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $partnerInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $partnerInfo['support_email'];
                    $data['SuperAdminGstNo'] = $partnerInfo['company_gst_no'];
                    $data['SuperAdminCountry'] = $partnerInfo['country'];
                    $data['State'] = "";
                    $data['City'] = $City;
                    $data['CompanyName'] = $paxName;
                    $data['Address'] = $Address;
                    $data['Pincode'] = "";
                    $data['SupportNo'] = "";
                    $data['SupportEmail'] = "";
                    $data['Country'] = $CountryName;
                    $data['GstNo'] = isset($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";

                    $data['PanNo'] = "";
                }
                if ($HtmlType == "Ticket") {
                    $html = view('Modules\Airservice\Views\ticket', $data);
                } else if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Airservice\Views\invoice', $data);
                }

                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }


    public function SubmitAmendment()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->amendment_validation($input));
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            if (isset($input['AmendmentId']) && $input['RequesterInfo']['Requester'] == "SuperAdmin") {
                $whereCondition = array("id" => $input['AmendmentId'], "booking_ref_no" => $input['BookingId']);
                $amendmentupdateData = array(
                    "amendment_status" => $input['AmendmentStatus'],
                    "remark_from_web_partner" => $input['Remarks'],
                    "request_for" => "SuperAdmin",
                    "sup_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                    "modified" => create_date(),
                );
                $RestModel->updateUserData("flight_amendment", $whereCondition, $amendmentupdateData);
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Status Successfully Updated"), "Result" => array("AmendmentId" => $input['AmendmentId']));
            } elseif (isset($input['RequesterInfo']['wl_agent_id']) && $input['RequesterInfo']['Requester'] == "Whitelabel") {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("flight_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId'], "pax_id" => $input['PaxId']), "id,amendment_status");
                    if ($AmendmentStatus) {
                        $amendmentsaveData = array(
                            "wl_agent_id" => $input['RequesterInfo']['wl_agent_id'],
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "request_for" => "Wl_B2B",
                            "amendment_status" => isset($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "wl_agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_user" => $input['Remarks'],
                            "request" => json_encode(array("PaxId" => $input['PaxId'], "Sectors" => $input['Sectors'])),
                            "pax_id" => implode(",", $input['PaxId']),
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("flight_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } elseif (isset($input['RequesterInfo']['wl_customer_id']) && $input['RequesterInfo']['Requester'] == "WhitelabelB2C") {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("flight_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId'], "pax_id" => $input['PaxId']), "id,amendment_status");
                    if ($AmendmentStatus) {
                        $amendmentsaveData = array(
                            "wl_customer_id" => $input['RequesterInfo']['wl_customer_id'],
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "request_for" => "Wl_B2C",
                            "amendment_status" => isset($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_user" => $input['Remarks'],
                            "request" => json_encode(array("PaxId" => $input['PaxId'], "Sectors" => $input['Sectors'])),
                            "pax_id" => implode(",", $input['PaxId']),
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("flight_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);
                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("flight_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId'], "pax_id" => $input['PaxId']), "id,amendment_status");
                    if ($AmendmentStatus) {
                        $amendmentsaveData = array(
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "request_for" => "webpartner",
                            "amendment_status" => isset($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "agent_staff_id" => isset($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],
                            "request" => json_encode(array("PaxId" => $input['PaxId'], "Sectors" => $input['Sectors'])),
                            "pax_id" => implode(",", $input['PaxId']),
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("flight_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_flight_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            }
        }
    }

    public function GetLogs()
    {
        $uri = service('uri');
        $bookingrefno = $uri->getSegment(4);
        if ($bookingrefno) {
            $RestModel = new RestModel();
            $booking_detail = $RestModel->get_detail_by_refno($bookingrefno);
            $searchtoken = $booking_detail['tts_search_token'];
            $supplier = $booking_detail['api_supplier'];
            if ($supplier == 'TRAVELPORT' || $supplier == 'INDIGO') {
                $ext = 'xml';
            } else {
                $ext = 'json';
            }

            $logdata = $RestModel->get_supplier_logs(['tts_search_token' => $searchtoken, 'api_supplier' => $supplier]);
            if ($logdata) {
                $path = FCPATH . "writable/apilogs/flight/" . $bookingrefno;
                $destipath = FCPATH . "writable/apilogs/flight/" . $bookingrefno . '.zip';
                if (!is_dir($path)) {
                    mkdir($path);
                }
                foreach ($logdata as $log) {
                    $key = $log['service'];
                    $request = $log['request'];
                    $myfile = fopen($path . "/Request_" . $key . "." . $ext, "w") or die("Unable to open file!");
                    fwrite($myfile, $request);
                    fclose($myfile);

                    $response = $log['response'];
                    $myfile = fopen($path . "/Response_" . $key . "." . $ext, "w") or die("Unable to open file!");
                    fwrite($myfile, $response);
                    fclose($myfile);
                }
                zipDir($path, $destipath);

                // http headers for zip downloads
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"$bookingrefno.zip\"");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($destipath));
                ob_end_flush();
                @readfile($destipath);

                $tts_response = array(
                    'Search_Token' => $searchtoken,
                    'Error' => array("ErrorCode" => 0, "ErrorMessage" => ""),
                    'Result' => "Log file Successful created"
                );
                return $this->response->setContentType('application/json')->setJSON($tts_response);
            }
        }
    }

    function generateBarcodeData($bookingData)
    {
        $BarCodeInfoData = array();
        if ($bookingData) {
            $segmentInfoData = json_decode($bookingData['segments'], true);
            $journeyInfo = array();
            $SegmemtStringData = "";
            foreach ($segmentInfoData as $tripkey => $segmentInfo) {
                $DepartureDate = array();
                $FlightLegNumber = array();
                $SegmnetInfo = array();
                $firstSegment = current($segmentInfo);
                $lastSegment = end($segmentInfo);
                $DepartureStation = $firstSegment['Origin']['CityCode'];
                $ArrivalStation = $lastSegment['Destination']['CityCode'];
                $CarrierCode = $firstSegment['Airline']['AirlineCode'];
                foreach ($segmentInfo as $segmentkey => $segment) {
                    $departdate = date("d M Y", strtotime(explode("T", $segment['Origin']['DepartTime'])[0]));
                    array_push($DepartureDate, $departdate);
                    $FlightNumber = $segment['Airline']['FlightNumber'];
                    array_push($FlightLegNumber, $FlightNumber);
                    $journeyInfo[$tripkey][$segmentkey] = array("Sector" => $segment['Origin']['CityCode'] . "-" . $segment['Destination']['CityCode'], "FlightInfo" => $segment['Airline']['AirlineCode'] . "-" . $segment['Airline']['FlightNumber']);
                }
                $SegmemtStringData .= " " . implode(" ", $DepartureDate) . " " . $CarrierCode . " " . implode(" ", $FlightLegNumber) . " " . $DepartureStation . " " . $ArrivalStation;
            }
            $travelersInfo = json_decode($bookingData['travelersInfo'], true);

            foreach ($travelersInfo as $Travelers) {
                $paxNameData = array();
                $paxNameData['Name'] = $Travelers['title'] . " " . $Travelers['first_name'] . " " . $Travelers['last_name'];
                $BarCodeInfo = $Travelers['last_name'] . "/" . $Travelers['first_name'] . " " . $bookingData['pnr'] . " " . $SegmemtStringData;
                $BarCode = generateBarCode($BarCodeInfo);
                $paxNameData['BarCode'] = $BarCode;
                $paxNameData['JourneyInfo'] = $journeyInfo;
                array_push($BarCodeInfoData, $paxNameData);
            }
        }
        return $BarCodeInfoData;
    }
}
