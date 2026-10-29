<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightModel;
use App\Modules\Flight\Models\FlightAirportModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Modules\Flight\Models\FlightBookingModel;
use App\Modules\Flight\Models\WebPartnerFlightMarkupModel;
use App\Modules\Flight\Models\WebPartnerFlightDiscountModel;
use App\Modules\Flight\Models\FlightAmendmentModel;
use App\Modules\Flight\Models\AgentFlightMarkupModel;
use App\Modules\Flight\Models\CreditNotesModel;
use App\Models\CommonModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class Flight extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {

        parent::initController($request, $response, $logger);
        $this->title = "Flight";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_details = session()->get('wl_agent');

        $this->wl_agent_gst_code = "09";
        $this->web_partner_gst_code = substr(web_partner_details['company_gst_no'], 0, 2);
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_class = session()->get('wl_agent')['agent_class'];
        $this->booking_source = "Wl_b2b";
        $this->wl_agent_staff_id = session()->get('wl_agent')['id'];

        $this->Services = API_REQUEST_URL . '/airservice/rest/';

        helper('Modules\Flight\Helpers\flight');

        /*  if (permission_access_error("flight_module")) {
         } */

    }



    function flight_booking_calender()
    {

        $getData = $this->request->getGet();

        $FlightBookingModel = new FlightBookingModel();
       
        $booking_list = $FlightBookingModel->getCalenderList($this->web_partner_id, $this->wl_agent_id, $getData);
      
      
        $booking_result = [];

        if ($booking_list) {
            foreach ($booking_list as $key => $booking) {
                unset($booking_id);
                $segments = json_decode($booking['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];
                $lead_pax = "";
                $travelersInfo = json_decode($booking['travelersInfo'], true);
                $status = $booking['booking_status'];
                $pax_count = count($travelersInfo);

                $booking_id[] = $booking['id'];

                $DateOfJourney = date('d M', strtotime($booking['departure_date']));

                foreach ($travelersInfo as $pax) {
                    if ($pax['lead_pax'] == 1) {
                        $lead_pax = $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name'];
                        break;
                    }
                }

                $summery = $first_segment['Airline']['AirlineCode'] . '-' . $first_segment['Airline']['FlightNumber'] . ' ' . $booking['origin'] . '-' . $booking['destination'] . ' ' . $DateOfJourney;

                if ($booking['journey_type'] == "RoundTrip" && $booking['is_domestic'] == 1) {
                    $return_data = $FlightBookingModel->getReturnBookingDetail($this->web_partner_id, $this->wl_agent_id, $booking['tts_search_token'], $getData);
                    if ($return_data['segments']) {
                        $return_segments = json_decode($return_data['segments'], true);
                        $return_first_segment = reset($return_segments);
                        $return_first_segment = $return_first_segment[0];

                        $DateOfReturnJourney = date('d M', strtotime($return_data['departure_date']));

                        $return_summery = $return_first_segment['Airline']['AirlineCode'] . '-' . $return_first_segment['Airline']['FlightNumber'] . ' ' . $return_data['origin'] . '-' . $return_data['destination'] . ' ' . $DateOfReturnJourney;
                        $summery = $return_summery . ', ' . $return_summery;

                        if ($booking['booking_status'] != $return_data['booking_status']) {
                            $status = $booking['booking_status'] . ',' . $return_data['booking_status'];
                        }

                        $booking_id[] = $return_data['id'];
                    }
                }

                //$ticketData = dev_encode(json_encode($booking_id));

                $summery = $summery . ', x ' . $pax_count;

                $DepartTime = explode('T', $first_segment['Origin']['DepartTime']);
                $DepartTime = substr($DepartTime[1], 0, 5);
                $insert_data = array(
                    "leadPassengerName" => $lead_pax,
                    "bookingRefNo" => $booking['booking_ref_number'],
                    "bookingId" => $booking['id'],
                    "bookingStatus" => $status,
                    "generationTime" => $DepartTime,
                    "summary" => $summery,
                    'Triptype' => $booking['journey_type'],
                    'Token' => dev_encode(json_encode($booking_id))
                );

                if (array_key_exists($booking['departure_date'], $booking_result)) {
                    $booking_result[$booking['departure_date']][] = $insert_data;
                } else {
                    $booking_result[$booking['departure_date']][] = $insert_data;
                }
            }
        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-calender",
            "list" => $booking_result,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/default-layout', $data);
    }



    public function check_search_validation()
    {
        $data = $this->request->getPOST();
        $validate = new Validation();
        $rules = $this->validate($validate->search_validation($data));
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 0, "Message" => "");
            return $this->response->setJSON($message);
        }
    }

    public function index()
    {
        $FlightModel = new FlightModel();
        $offers_list = $FlightModel->offers_list($this->web_partner_id);
        $notification_list = $FlightModel->admin_notification();
        $slider_list = $FlightModel->slider_list();
        $important_notification = $FlightModel->important_notification();
        $general_notification = $FlightModel->general_notification();
        $top_routes_list = $FlightModel->flight_top_route_list($this->web_partner_id);
        /*  pr($top_routes_list);
         die; */
        $custom_routes_list = array();
        foreach ($top_routes_list as $key => $routes_list) {
            $origin = explode("(", $routes_list['origin']);
            $custom_routes_list[$key]['city_origin'] = $origin[0];
            $destination = explode("(", $routes_list['destination']);
            $custom_routes_list[$key]['city_destination'] = $destination[0];
            if ($routes_list['journeytype'] == 'oneway') {
                $arrowType = 'to';
                $journeytype = 'Oneway';
                $seperator = '-';
            } else {
                $arrowType = '<i class="fa fa-exchange" aria-hidden="true"></i>';
                $journeytype = 'Roundtrip';
                $seperator = '<i class="fa fa-exchange" aria-hidden="true"></i>';
            }
            $custom_routes_list[$key]['arrowType'] = $arrowType;
            $custom_routes_list[$key]['seperator'] = $seperator;
            $custom_routes_list[$key]['OriginCode'] = $routes_list['origin_code'];
            $custom_routes_list[$key]['DestinationCode'] = $routes_list['destination_code'];
            $custom_routes_list[$key]['url']['journeytype'] = $journeytype;
            $custom_routes_list[$key]['url']['origin'] = $routes_list['origin'];
            $custom_routes_list[$key]['url']['destination'] = $routes_list['destination'];
            $custom_routes_list[$key]['url']['adults'] = $routes_list['adult'];
            $custom_routes_list[$key]['url']['child'] = $routes_list['child'];
            $custom_routes_list[$key]['url']['infant'] = $routes_list['infant'];
            if (!empty($routes_list['depart_date'])) {
                $custom_routes_list[$key]['url']['departdate'] = date('d M y', strtotime($routes_list['depart_date']));
            } else {
                $custom_routes_list[$key]['url']['departdate'] = date('d M y');
            }

            if ($routes_list['journeytype'] == 'round-trip' && !empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = date('d M y', strtotime($routes_list['return_date']));
            } else if ($routes_list['journeytype'] == 'round-trip' && empty($routes_list['return_date'])) {
                $custom_routes_list[$key]['url']['returndate'] = date('d M y', strtotime($custom_routes_list[$key]['url']['departdate'] . ' +1 day'));
            } else {
                $custom_routes_list[$key]['url']['returndate'] = '';
            }
            $custom_routes_list[$key]['url']['cabinclass'] = $routes_list['cabin_class'];
            if ($routes_list['direct_flight'] == 'true') {
                $custom_routes_list[$key]['url']['direct_flight'] = 1;
            } else {
                $custom_routes_list[$key]['url']['direct_flight'] = 0;
            }
            $custom_routes_list[$key]['url']['preferred_carriers'] = '';
        }

        /* pr($custom_routes_list);exit; */
        $MetaInfoData = static_meta_information('Flight', 'Index');

        $data = [
            'notification_list' => $notification_list,
            'title' => $this->title,
            'slider_list' => $slider_list,
            'general_notification' => $general_notification,
            'top_routes_list' => $custom_routes_list,
            'important_notification' => $important_notification,
            'offers_list' => $offers_list,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => "Flight\Views\FlightBookingtemplate\index",
        ];
        return view('template/home-layout', $data);
    }

    public function search()
    {
        $FlightModel = new FlightModel();
        if (isset($_GET['token']) && $_GET['token'] != "") {
            // echo "get token";die;
            $token = $_GET['token'];
            $searchInfo = $FlightModel->getApiLogsData(array("tts_search_token" => $token, "service" => "search"), "tts_custom_request");
            $searchData = json_decode($searchInfo['tts_custom_request'], true);
        } else {
            // echo "not get token";die;
            $searchData = $this->request->getGET();
            // print_r($searchData);die;
        }
        $type = $searchData['journeytype'];
        $OriginDestinationAirportCodeData = getSearchOriginDestinationAirportCode($searchData);

        $searchData = $OriginDestinationAirportCodeData['searchData'];

        $OriginDestinationAirportCode = $OriginDestinationAirportCodeData['airportCodeArray'];
        $OriginDestinationAirportDetail = $FlightModel->selected_airport_detail($OriginDestinationAirportCode);
        // echo "<pre>"; print_r($OriginDestinationAirportDetail);die;

        $view = "Flight\Views\FlightBookingtemplate/flight_result";

        if ($type == "Roundtrip") {
            /* Start Checking is domestic  */
            $origin_country_code = get_country_name($searchData['origin']);
            $destination_country_code = get_country_name($searchData['destination']);
            if ($origin_country_code == "india" && $destination_country_code == "india") {
                $isdomestic = "true";
                $view = "Flight\Views\FlightBookingtemplate/flight_result_roundtrip";
            }
            /* End Checking is domestic  */
        }
        $dial_code = $FlightModel->get_dial_code();

        $searchSegement = "";
        if ($searchData['journeytype'] == 'Roundtrip' || $searchData['journeytype'] == 'Oneway') {
            // echo "Oneway";
            $searchSegement = explode(" ", $searchData['origin'])[0] . "-" . explode(" ", $searchData['destination'])[0];

        }
        if ($searchData['journeytype'] == 'Roundtrip') {
            // echo "roundtrip";
            $searchSegement = $searchSegement . "-" . explode(" ", $searchData['origin'])[0];
        }
        $totalPax = $searchData['adults'] + $searchData['child'] + $searchData['infant'];

        $MetaInfoData = static_meta_information('Flight', 'Result');

        $data = [
            'title' => $this->title,
            'searchData' => $searchData,
            'dial_code' => $dial_code,
            'searchSegement' => $searchSegement,
            'totalPax' => $totalPax,
            'OriginDestinationAirportDetail' => $OriginDestinationAirportDetail,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => $view,
        ];
        //   echo "<pre>"; print_r($data);die;
        return view('template/default-layout', $data);
    }

    public function flightResult()
    {
        $FlightModel = new FlightModel();
        if (isset($_GET['token']) && $_GET['token'] != "") {
            $token = $_GET['token'];
            $search_data = $FlightModel->getApiLogsData(array("tts_search_token" => $token, "service" => "search"), "request,response");
        } else {
            $search_data = $this->request->getGET();
        }
        if ($search_data) {
            $airlineLogoClass = "";
            if (!isset($_GET['token'])) {
                $type = $this->request->getGET("journeytype");
                $no_adult = $this->request->getGET("adults");
                $no_child = $this->request->getGET("child");
                $no_infants = $this->request->getGET("infant");
                $class = get_api_cabinclass($this->request->getGET("cabinclass"));
                /*  $response = json_decod$classe(file_get_contents(FCPATH . '/webroot/flight_response.json'), true); */

                if ($type == "Oneway") {
                    $journeytype = 1;
                    $origin = AirportCode($this->request->getGET("origin"));
                    $destination = AirportCode($this->request->getGET("destination"));
                    $depart_date_time = get_api_date_format($this->request->getGET("departdate")) . "T00:00:00";
                    $searchjson_value = array(
                        array(
                            'Origin' => $origin,
                            'Destination' => $destination,
                            'PreferredTime' => $depart_date_time
                        )
                    );

                    /* Start Checking is domestic  */
                    $origin_country_code = get_country_name($this->request->getGET("origin"));
                    $destination_country_code = get_country_name($this->request->getGET("destination"));

                    if ($origin_country_code == "india" && $destination_country_code == "india") {
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                } else if ($type == "Roundtrip") {
                    $journeytype = 2;
                    $origin = AirportCode($this->request->getGET("origin"));
                    $destination = AirportCode($this->request->getGET("destination"));
                    $depart_date_time = get_api_date_format($this->request->getGET("departdate")) . "T00:00:00";
                    $return_date_time = get_api_date_format($this->request->getGET("returndate")) . "T00:00:00";
                    $searchjson_value = array(
                        array(
                            'Origin' => $origin,
                            'Destination' => $destination,
                            'PreferredTime' => $depart_date_time
                        ),
                        array(
                            'Origin' => $destination,
                            'Destination' => $origin,
                            'PreferredTime' => $return_date_time
                        )
                    );
                    /* Start Checking is domestic  */
                    $origin_country_code = get_country_name($this->request->getGET("origin"));
                    $destination_country_code = get_country_name($this->request->getGET("destination"));
                    if ($origin_country_code == "india" && $destination_country_code == "india") {
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                } else {
                    $journeytype = 3;
                    $search_data = $this->request->getGET("search_data");
                    foreach ($search_data as $key => $searchjourney) {
                        $country_name[] = get_country_name($searchjourney['origin']);
                        $country_name[] = get_country_name($searchjourney['origin']);
                        $searchjson_value[] = array(
                            'Origin' => AirportCode($searchjourney['origin']),
                            'Destination' => AirportCode($searchjourney['destination']),
                            'PreferredTime' => get_api_date_format($searchjourney['departdate']) . "T00:00:00"
                        );
                    }

                    $country_code_data = array_unique($country_name);
                    $country_code_count = count(array_unique($country_name));
                    /* Start Checking is domestic  */
                    if ($country_code_count == 1 && $country_code_data[0] == "india") {
                        $isdomestic = "true";
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $isdomestic = "false";
                        $airlineLogoClass = "intAirLogo";
                    }
                    /* End Checking is domestic  */
                }
                $DirectFlight = false;
                $PreferredCarriers = NULL;
                if (isset($search_data['direct_flight']) && $search_data['direct_flight'] != 0) {
                    $DirectFlight = true;
                }
                if (isset($search_data['preferred_carriers']) && $search_data['preferred_carriers'] != '' && $search_data['preferred_carriers'] != 'all') {
                    $PreferredCarriers = explode(",", $search_data['preferred_carriers']);
                }

                $request = array(
                    'UserIp' => $this->request->getIpAddress(),
                    'Adult' => $no_adult,
                    'Child' => $no_child,
                    'Infant' => $no_infants,
                    'DirectFlight' => $DirectFlight,
                    'JourneyType' => $journeytype,
                    'PreferredCarriers' => $PreferredCarriers,
                    'CabinClass' => $class,
                    'AirSegments' => $searchjson_value,
                    'Sources' => NULL
                );

                $service = "search";
                $url = $this->Services . $service;
                /*  echo $url = $this->Services . $service; */

                /*   echo json_encode($request);exit; */
                $response = Request($request, $url, $service);
                //  echo "<pre>"; print_r($response);die;
                $MarkupInput = $request;
                $MarkupInput['IsDomestic'] = $isdomestic;
            } else {
                $searchData = json_decode($search_data['request'], true);
                $no_adult = $searchData['Adult'];
                $no_child = $searchData['Child'];
                $MarkupInput = $searchData;
                if ($searchData['IsDomestic'] == "true") {
                    $isdomestic = "true";
                    $airlineLogoClass = "domAirLogo";
                } else {
                    $isdomestic = "false";
                    $airlineLogoClass = "intAirLogo";
                }
                $response = json_decode($search_data['response'], true);
                // echo "HELLLO";die;
                //  echo "<pre>"; print_r($response);die;
            }
            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                if (!isset($_GET['token'])) {
                    $FlightModel->updateApiLogsData(array("tts_search_token" => $response['SearchTokenId'], "service" => "search"), array("tts_custom_request" => json_encode($_GET)));
                }
                $FlightJourneyResults = $response['Result'];
                $TotalResults = 0;
                $TotalReturnResults = 0;
                $priceArray = array();
                unset($response['Result']);
                if ($FlightJourneyResults) {
                    $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                    $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                    $AgentFlightMarkupModel = new AgentFlightMarkupModel();

                    $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $this->wl_agent_class, $MarkupInput);

                    $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $this->wl_agent_class, $MarkupInput);
                    $AgentFlightMarkupData = $AgentFlightMarkupModel->getAgentFlightmarkup($this->web_partner_id, $this->wl_agent_id, $MarkupInput);

                    $FilterData = array();
                    foreach ($FlightJourneyResults as $journeykey => $FlightJourneyResult) {
                        if ($journeykey == 0) {
                            $TotalResults = count($FlightJourneyResult);
                        } else if ($journeykey == 1) {
                            $TotalReturnResults = count($FlightJourneyResult);
                        }

                        /*-------Start For Filter----*/
                        $FilterPrice = array();
                        $FilterAirline = array();
                        $FilterFareType = array();
                        $FilterStop = array();
                        $FilterDepart = array();
                        $totalpax = $no_adult + $no_child;
                        $selectedMarkupDataInfo = array();
                        $selectedDiscountDataInfo = array();
                        foreach ($FlightJourneyResult as $flightkey => $flight_result) {
                            $first_segment = current($flight_result['Segments']);
                            $first_segment = current($first_segment);
                            $FareListOptions = $flight_result['FareList'];

                            foreach ($flight_result['FareList'] as $farelistkey => $FareList) {
                                $extraParam = array();
                                $extraParam['CabinClass'] = $FareList['CabinClass'];
                                $extraParam['FareType'] = $FareList['FareType'];
                                $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                                $per_pax_markup = 0;
                                $per_pax_markup_wl_agent = 0;
                                $FlightPrice = get_flight_fare($MarkupInput, $this->wl_agent_details['gst_number'], $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $AgentFlightMarkupData, $FareList['Fare'], $FareList['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam);


                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $FareWithMarkup = $FlightPrice['Fare'];
                                $AgentWebPMarkUp = $FlightPrice['AgentFareBreakup']['AgentWebPMarkUp'];
                                if ($AgentWebPMarkUp) {
                                    $per_pax_markup_wl_agent = round_value($AgentWebPMarkUp / $totalpax);
                                }
                                $FareBreakdown = array();
                                $price_breakdown = $FlightPrice['FareBreakdown'];
                                foreach ($price_breakdown as $paxkey => $pax) {
                                    $paxwise_markup_value = 0;
                                    $per_pax_ServiceCharges = 0;
                                    $paxwise_markup_value_agent = 0;
                                    $per_pax_ServiceCharges_agent = 0;
                                    if ($paxkey != 'INF') {
                                        if ($FlightPrice['AgentFareBreakup'] && $FlightPrice['AgentFareBreakup']['AgentWebPDisplayMarkup'] == 'in_tax') {
                                            $paxwise_markup_value_agent = round_value($per_pax_markup_wl_agent * $pax['PassengerCount']);
                                        } elseif ($FlightPrice['AgentFareBreakup'] && $FlightPrice['AgentFareBreakup']['AgentWebPDisplayMarkup'] == 'in_service_charge') {
                                            $per_pax_ServiceCharges_agent = round_value($per_pax_markup_wl_agent * $pax['PassengerCount']);
                                        }
                                    }
                                    $FareBreakdown[$paxkey] = array(
                                        'PassengerCount' => $pax['PassengerCount'],
                                        'BaseFare' => $pax['BaseFare'],
                                        'Tax' => $pax['Tax'] + $paxwise_markup_value + $paxwise_markup_value_agent,
                                        'YQTax' => $pax['YQTax'],
                                        'ServiceCharges' => $per_pax_ServiceCharges + $per_pax_ServiceCharges_agent
                                    );
                                }

                                $FareList['Fare'] = $FareWithMarkup;
                                $FareList['FareBreakdown'] = $FareBreakdown;
                                $FareList['Fare']['PublishedPrice'] = $FareWithMarkup['PublishedPrice'] + $FlightPrice['AgentFareBreakup']['AgentWebPMarkUp'];
                                $FareList['PublishedPrice'] = $FareWithMarkup['PublishedPrice'] + $FlightPrice['AgentFareBreakup']['AgentWebPMarkUp'];
                                $FareListOptions[$farelistkey] = $FareList;
                            }


                            /*------ Sort Price Group    --------*/
                            $keys_publishprice = array_column($FareListOptions, 'PublishedPrice');
                            array_multisort($keys_publishprice, SORT_ASC, $FareListOptions);
                            $flight_result['FareList'] = $FareListOptions;
                            $mainSegment = array();
                            foreach ($flight_result['Segments'] as $tripindicatorkey => $result) {
                                $Duration = array();
                                $Airline = array();
                                $firstSegment = reset($result);
                                $lastSegment = end($result);
                                $departurecity = "";
                                $arrivalcity = "";
                                $departtime = "";
                                $airlineName = "";
                                $TotalDurationMin = 0;
                                $arrivalDays = 0;
                                $tripStops = "";
                                $airlineCodes = array();
                                $airlineNames = array();
                                $airlineFlightNumber = array();
                                $airlineCodeFlightNumber = array();
                                $Airline = array_column($result, "Airline");
                                $Duration = array_column($result, "Duration");
                                $airlineCodes = array_column($Airline, "AirlineCode");
                                $airlineCodeFlightNumber = array_column($Airline, "AirlineCode", "FlightNumber");
                                $airlineNames = array_column($Airline, "AirlineName");
                                $airlineFlightNumber = array_column($Airline, "FlightNumber");
                                $departurecity = $firstSegment['Origin']['AirportCode'];
                                $arrivalcity = $lastSegment['Destination']['AirportCode'];
                                $departtime = get_flight_time($firstSegment['Origin']['DepartTime']);
                                $arrivaltime = get_flight_time($lastSegment['Destination']['ArrivalTime']);
                                $departdate = get_flight_date($firstSegment['Origin']['DepartTime']);
                                $arrivaldate = get_flight_date($lastSegment['Destination']['ArrivalTime']);
                                $tripStops = (count($result) - 1);
                                /*  $TotalDurationMin = array_sum($Duration); */
                                $TotalDurationMin = $firstSegment['TotalDuration'];
                                $arrivalDays = get_flight_arrival_days($firstSegment['Origin']['DepartTime'], $lastSegment['Destination']['ArrivalTime']);
                                $airlineCodeFlightNumber = array_map(function ($value, $key) {
                                    return $value . '-' . $key;
                                }, array_values($airlineCodeFlightNumber), array_keys($airlineCodeFlightNumber));
                                $airlineCodeFlightNumber = implode(',', $airlineCodeFlightNumber);


                                array_push($FilterPrice, $flight_result['FareList'][0]['Fare']['PublishedPrice']);
                                $airline_code = array_unique($airlineCodes)[0];
                                $airline_name = array_unique($airlineNames)[0];
                                $subairlineArray = array('value' => $airline_code, 'label' => $airline_name, 'isChecked' => false);
                                array_push($FilterAirline, $subairlineArray);

                                if ($tripStops == 0) {
                                    $stoplabel = 'Non Stop';
                                } else {
                                    $stoplabel = $tripStops . ' Stop';
                                }
                                $substopArray = array('value' => $tripStops, 'label' => $stoplabel, 'isChecked' => false);
                                array_push($FilterStop, $substopArray);


                                $mainSegment[$tripindicatorkey] = array(
                                    "DepartureCity" => $departurecity,
                                    "Fare" => $flight_result['FareList'][0]['Fare']['PublishedPrice'],
                                    "ArrivalCity" => $arrivalcity,
                                    "DepartTime" => $departtime,
                                    "DepartDate" => $departdate,
                                    "ArrivalTime" => $arrivaltime,
                                    "ArrivalDate" => $arrivaldate,
                                    "Stops" => $tripStops,
                                    "ArrivalDays" => $arrivalDays,
                                    "Seats" => isset($flight_result['FareList'][0]['SeatBaggage'][$tripindicatorkey][0]['NoOfSeatAvailable']) ? $flight_result['FareList'][0]['SeatBaggage'][$tripindicatorkey][0]['NoOfSeatAvailable'] : 0,
                                    "Airlinecodes" => array_unique($airlineCodes),
                                    "Airlinecode" => $airline_code,
                                    "AirlineName" => $airline_name,
                                    "OriginAirportName" => $firstSegment['Origin']['AirportName'],
                                    "DestinationAirportName" => $lastSegment['Destination']['AirportName'],
                                    "AirlineFlightNumber" => array_unique($airlineFlightNumber),
                                    "AirlineCodeFlightNumberString" => trim($airlineCodeFlightNumber, ", "),
                                    "DurationMin" => $TotalDurationMin,
                                    "Duration" => get_convertToHoursMinsfromMinDuration($TotalDurationMin),
                                    "DepartString" => filter_deparr_string($departtime),
                                    "ArrivalString" => filter_deparr_string($arrivaltime),
                                );
                            }
                            $FlightJourneyResults[$journeykey][$flightkey]['MainSegment'] = $mainSegment;
                            $FlightJourneyResults[$journeykey][$flightkey]['TtsIndex'] = $flightkey;
                            $FlightJourneyResults[$journeykey][$flightkey]['FareList'] = $flight_result['FareList'];
                            $FlightJourneyResults[$journeykey][$flightkey]['AirlineRemark'] = $flight_result['FareList'][0]['AirlineRemark'];
                        }

                        $FilterAirline = array_values(array_map("unserialize", array_unique(array_map("serialize", $FilterAirline))));
                        $FilterStop = array_values(array_map("unserialize", array_unique(array_map("serialize", $FilterStop))));

                        array_multisort(array_column($FilterStop, 'value'), SORT_ASC, $FilterStop);
                        array_multisort(array_column($FilterAirline, 'label'), SORT_ASC, $FilterAirline);

                        $FilterData[$journeykey] = array(
                            'Price' => array('min' => min($FilterPrice), 'max' => max($FilterPrice)),
                            'FareType' => array(
                                array('label' => 'Refundable', 'value' => true, 'isChecked' => false),
                                array('label' => 'Non Refundable', 'value' => false, 'isChecked' => false)
                            ),
                            'Stop' => $FilterStop,
                            'DepartTime' => array(
                                array('label' => '12AM-6AM', 'value' => 'EarlyMorning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/morning (1).svg')),
                                array('label' => '6AM-12PM', 'value' => 'Morning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sun.svg')),
                                array('label' => '12PM-6PM', 'value' => 'Afternoon', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sunset.svg')),
                                array('label' => '6PM-12AM', 'value' => 'Night', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/moon.svg')),
                            ),
                            'ArrivalTime' => array(
                                array('label' => '12AM-6AM', 'value' => 'EarlyMorning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/morning (1).svg')),
                                array('label' => '6AM-12PM', 'value' => 'Morning', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sun.svg')),
                                array('label' => '12PM-6PM', 'value' => 'Afternoon', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/sunset.svg')),
                                array('label' => '6PM-12AM', 'value' => 'Night', 'isChecked' => false, 'icon' => site_url('webroot/img/svg_icon/moon.svg')),
                            ),
                            'Airline' => $FilterAirline
                        );
                    }
                    $response['Result'] = $FlightJourneyResults;
                    $response['TotalResults'] = $TotalResults;
                    $response['TotalReturnResults'] = $TotalReturnResults;
                    $response['airlineLogoClass'] = $airlineLogoClass;
                    $response['Filter'] = $FilterData;

                    /*if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                        ob_start('ob_gzhandler');
                    else ob_start();*/


                    return $this->response->setJson($response);
                }
            } else {
                return $this->response->setJson($response);
            }
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "Search is not allowed.",
                )
            );
            return $this->response->setJson($response);
        }
    }

    function fareRule()
    {
        $fare_rule_data = $this->request->getPOST();
        if ($fare_rule_data) {
            $request = array(
                'UserIp' => $this->request->getIpAddress(),
                'ResultIndex' => $fare_rule_data['FareId'],
                'SearchTokenId' => $fare_rule_data['token'],
            );
            $service = "farerule";
            $url = $this->Services . $service;
            $response = Request($request, $url, $service);
            return $this->response->setJson($response);
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
            return $this->response->setJson($response);
        }
    }
    function fareConfirmation()
    {
        $fare_confirmation_data = $this->request->getPOST();
        if ($fare_confirmation_data) {
            $request = array(
                'UserIp' => $this->request->getIpAddress(),
                'ResultIndex' => $fare_confirmation_data['FareId'],
                'SearchTokenId' => $fare_confirmation_data['token'],
            );
            $service = "fareconfirmation";
            $url = $this->Services . $service;

            $response = Request($request, $url, $service);

            /*   $response = json_decode(file_get_contents(FCPATH.'/webroot/fare_confirmation.json'),true); */
            if ($response['Error']['ErrorCode'] == 0) {
                $getConfirmshowbox = 0;
                $title = "Confirming Your Flight";
                $price_change = "";
                $redirectUrl = site_url() . "flight/flight-details?token=" . $fare_confirmation_data['token'] . "&farecode=" . $fare_confirmation_data['FareId'] . "&rtype=frcmn";
                if ($response['IsPriceChanged'] == 1) {
                    $title = "Price has been changed";
                    $getConfirmshowbox = 1;
                    $newprice = $response['Result']['Fare']['PublishedPrice'];
                    $FlightModel = new FlightModel();
                    $searchInfo = $FlightModel->getApiLogsData(array("tts_search_token" => $fare_confirmation_data['token'], "service" => "search"), "response");
                    $SearchResultIndexInfo = json_decode($searchInfo['response'], true)['Result'][0][$fare_confirmation_data['TtsResultIndexkey']];
                    unset($searchInfo);
                    $SeletedFareInfo = $SearchResultIndexInfo['FareList'][$fare_confirmation_data['FareListOptionkey']];
                    unset($SearchResultIndexInfo);
                    $oldprice = $SeletedFareInfo['Fare']['PublishedPrice'];
                    $difference = ($newprice) - ($oldprice);
                    $sign_type = check_int($difference);
                    $price_change = '<div class="col-lg-12 align-self-center">
                                <img src="' . $sign_type['image'] . '" alt="change price"  class="w90 pb10"> 
                                <div class="row">
                                <p class="msg"><samp>The Airline has ' . $sign_type['text'] . ' the fare on this route by</samp><samp> <i class="fa fa-inr"></i>' . change_money_format($difference) . '.</samp>Please note that airfares are dynamic and subject to change. Select form the options below. </p>
                              </div>
                              </div>
                        <div class="row justify-content-center"> 
                                <div class="col-12 col-lg-8 col-sm-8 p0 fare-update">
                                <table class="table">							  
                                      <tbody>
                                            <tr> 
                                              <td> <strong>Updated Fare</strong> </td> 
                                              <td><samp><i class="fa fa-inr"></i> ' . change_money_format($newprice) . '</samp></td>
                                            </tr>
                                            <tr> 
                                              <td><strong>Original Fare</strong></td> 
                                              <td><samp><i class="fa fa-inr"></i> ' . change_money_format($oldprice) . '</samp></td>
                                            </tr>
                                            <tr class="total"> 
                                              <td><strong>Difference</strong></td>  
                                              <td><samp><b>' . $sign_type['sign'] . '</b><i class="fa fa-inr"></i> ' . change_money_format($difference) . '</samp></td>
                                            </tr>  
                                      </tbody>
                             </table> 
                             </div>
                             </div>
                                 <div class="row justify-content-center mb15 mlr0">
                                    <div class="col col-auto">
                                    <button type="button" class="btn btn-primary another_fare" data-bs-dismiss="modal" aria-label="Close">Select Another Flight</button>
                                    </div> 
                                    
                                    <div class="col col-auto">
                                        <a href="' . $redirectUrl . '" class="btn go_button">Continue</a> 
                                    </div>      
                                  
                              </div>
                              ';
                }
                $response = array(
                    "Error" => array(
                        "ErrorCode" => 0,
                        "ErrorMessage" => "",
                    ),
                    "Title" => $title,
                    "RedirectUrl" => $redirectUrl,
                    "GetConfirmShowBox" => $getConfirmshowbox,
                    "PriceChange" => $price_change,
                );
            }
            return $this->response->setJson($response);
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
            return $this->response->setJson($response);
        }
    }

    function fareConfirmationRoundtrip()
    {
        $fare_confirmation_data = $this->request->getPOST();
        if ($fare_confirmation_data['OnwardFareId'] != "" && $fare_confirmation_data['ReturnFareId'] != "") {
            $requestData['OB'] = array(
                'UserIp' => $this->request->getIpAddress(),
                'ResultIndex' => $fare_confirmation_data['OnwardFareId'],
                'SearchTokenId' => $fare_confirmation_data['token'],
            );
            $requestData['IB'] = array(
                'UserIp' => $this->request->getIpAddress(),
                'ResultIndex' => $fare_confirmation_data['ReturnFareId'],
                'SearchTokenId' => $fare_confirmation_data['token'],
            );
            $getConfirmshowbox = 0;
            $title = "Confirming Your Flight";
            $price_change = "";
            $IsPriceChanged = 0;
            $ErrorCode = 0;
            $ErrorMessage = "";
            $redirectUrl = site_url() . "flight/flight-details?token=" . $fare_confirmation_data['token'] . "&farecode=" . $fare_confirmation_data['OnwardFareId'] . "&farecodereturn=" . $fare_confirmation_data['ReturnFareId'] . "&rtype=frcmn";
            foreach ($requestData as $key => $request) {
                if ($key == "OB") {
                    $selectedJourney = 0;
                    $resultIndexkey = $fare_confirmation_data['TtsOnwardResultIndexkey'];
                    $Selectfarekey = $fare_confirmation_data['FareListOptionOnwardkey'];
                    $JourneyType = "Outbound";
                }
                if ($key == "IB") {
                    $selectedJourney = 1;
                    $resultIndexkey = $fare_confirmation_data['TtsReturnResultIndexkey'];
                    $Selectfarekey = $fare_confirmation_data['FareListOptionReturnkey'];
                    $JourneyType = "Inbound";
                }
                $service = "fareconfirmation";
                $url = $this->Services . $service;
                $response = Request($request, $url, $service);
                /*   $response = json_decode(file_get_contents(FCPATH.'/webroot/fare_confirmation.json'),true);  */
                if ($response['Error']['ErrorCode'] == 0) {
                    if ($response['IsPriceChanged'] == 1) {
                        $IsPriceChanged = $response['IsPriceChanged'];
                        $title = "Price has been changed";
                        $getConfirmshowbox = 1;
                        $newprice = $response['Result']['Fare']['PublishedPrice'];
                        $FlightModel = new FlightModel();
                        $searchInfo = $FlightModel->getApiLogsData(array("tts_search_token" => $fare_confirmation_data['token'], "service" => "search"), "response");
                        $SearchResultIndexInfo = json_decode($searchInfo['response'], true)['Result'][$selectedJourney][$resultIndexkey];
                        unset($searchInfo);
                        $SeletedFareInfo = $SearchResultIndexInfo['FareList'][$Selectfarekey];
                        unset($SearchResultIndexInfo);
                        $oldprice = $SeletedFareInfo['Fare']['PublishedPrice'];
                        $difference = ($newprice) - ($oldprice);
                        $sign_type = check_int($difference);
                        $price_change = '<table class="table table-striped">
								<tr>
								  <td class="info" colspan="2"><strong>' . $JourneyType . ' </strong></td>
								</tr>
						   </table>';
                        $price_change = $price_change . '<div class="col-lg-12 align-self-center">
                                <img src="' . $sign_type['image'] . '" alt="change price"  class="w90 pb10"> 
                                <div class="row">
                                <p class="msg"><samp>The Airline has ' . $sign_type['text'] . ' the fare on this route by</samp><samp> <i class="fa fa-inr"></i>' . change_money_format($difference) . '.</samp>Please note that airfares are dynamic and subject to change. Select form the options below. </p>
                              </div>
                              </div>
                        <div class="row justify-content-center"> 
                                <div class="col-12 col-lg-8 col-sm-8 p0 fare-update">
                                <table class="table">							  
                                      <tbody>
                                            <tr> 
                                              <td> <strong>updated fare</strong> </td> 
                                              <td><samp><i class="fa fa-inr"></i>' . change_money_format($newprice) . '</samp></td>
                                            </tr>
                                            <tr> 
                                              <td><strong>original fare</strong></td> 
                                              <td><samp><i class="fa fa-inr"></i>' . change_money_format($oldprice) . '</samp></td>
                                            </tr>
                                            <tr class="total"> 
                                              <td><strong>Difference</strong></td>  
                                              <td><samp><b>' . $sign_type['sign'] . '</b><i class="fa fa-inr"></i> ' . change_money_format($difference) . '</samp></td>
                                            </tr>  
                                      </tbody>
                             </table> 
                             </div>
                             </div>
                              ';
                    }
                } else {
                    $ErrorCode = $response['Error']['ErrorCode'];
                    $ErrorMessage = $response['Error']['ErrorMessage'];
                    $redirectUrl = "";
                    $title = "Confirming Your Flight";
                    $getConfirmshowbox = 0;
                    $price_change = "";
                    break;
                }
            }
            if ($IsPriceChanged) {
                $price_change = $price_change . '<div class="row justify-content-center mb15 mlr0">
        <div class="col col-auto">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">>Select Another Flight</button>
        </div> 
        
        <div class="col col-auto">
            <a href="' . $redirectUrl . '" class="btn go_button">Continue</a> 
        </div>      
      
        </div>';
            }
            $response = array(
                "Error" => array(
                    "ErrorCode" => $ErrorCode,
                    "ErrorMessage" => $ErrorMessage,
                ),
                "Title" => $title,
                "RedirectUrl" => $redirectUrl,
                "GetConfirmShowBox" => $getConfirmshowbox,
                "PriceChange" => $price_change,
            );

            return $this->response->setJson($response);
        } else {
            $response = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
            return $this->response->setJson($response);
        }
    }

    public function flight_details()
    {
        $flightConfrimationRequest = $this->request->getGET();

        if ($flightConfrimationRequest) {
            if ($flightConfrimationRequest['rtype'] == "frcmn") {
                $FlightModel = new FlightModel();
                $flightConfrimationData = array();
                $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecode']);
                $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');

                if ($flightConfrimationDataOB) {
                    $flightConfrimationData['OB'] = $flightConfrimationDataOB['response'];
                } else {
                    $flightConfrimationData['OB'] = array();
                }
                if (isset($flightConfrimationRequest['farecodereturn']) and $flightConfrimationRequest['farecodereturn'] != "") {
                    $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                    $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
                    if ($flightConfrimationDataIB) {
                        $flightConfrimationData['IB'] = $flightConfrimationDataIB['response'];
                    } else {
                        $flightConfrimationData['IB'] = array();
                    }
                }
                $searchRequest = array();
                $dial_code = array();
                $whereFlightSearch = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "search");
                $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
                $searchRequest = json_decode($FlightSearchInfo['request'], true);
                $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                $AgentFlightMarkupModel = new AgentFlightMarkupModel();
                $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $this->wl_agent_class, $searchRequest);
                $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $this->wl_agent_class, $searchRequest);
                $AgentFlightMarkupData = $AgentFlightMarkupModel->getAgentFlightmarkup($this->web_partner_id, $this->wl_agent_id, $searchRequest);
                $IsPanMandatory = false;
                $IsPassportMandatory = false;
                $IsGSTMandatory = false;
                $GSTAllowed = false;
                $IsGDS = false;
                if ($flightConfrimationData) {
                    $ErrorCode = 0;
                    $ErrorMessage = "";
                    $flightConfrimationResponseData = array();
                    $flightConfrimationFareDetailData = array();
                    foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                        if ($fareconfirmation) {
                            $selectedMarkupDataInfo = array();
                            $selectedDiscountDataInfo = array();
                            $fareconfirmationArray = json_decode($fareconfirmation, true);
                            if ($fareconfirmationArray['Error']['ErrorCode'] == 0) {
                                if ($fareconfirmationArray['Result']['IsPanRequiredAtBook']) {
                                    $IsPanMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['IsPassportRequiredAtBook']) {
                                    $IsPassportMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['IsGSTMandatory']) {
                                    $IsGSTMandatory = true;
                                }
                                if ($fareconfirmationArray['Result']['GSTAllowed']) {
                                    $GSTAllowed = true;
                                }
                                if (!$fareconfirmationArray['Result']['IsLCC']) {
                                    $IsGDS = true;
                                }
                                unset($fareconfirmationArray['Error']);
                                $first_segment = current($fareconfirmationArray['Result']['Segments']);
                                $first_segment = current($first_segment);
                                $extraParam = array();
                                $extraParam['CabinClass'] = $first_segment['CabinClass'];
                                $extraParam['FareType'] = $fareconfirmationArray['Result']['FareType'];
                                $extraParam['FareClass'] = $first_segment['Airline']['FareClass'];
                                $FlightPrice = get_flight_fare($searchRequest, $this->wl_agent_details['gst_number'], $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $AgentFlightMarkupData, $fareconfirmationArray['Result']['Fare'], $fareconfirmationArray['Result']['FareBreakdown'], $first_segment['Airline']['AirlineCode'], $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam);
                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $FareWithMarkup = $FlightPrice['Fare'];
                                // pr($WebPartnermarkupData);
                                // pr($AgentFlightMarkupData);
                                // pr($FlightPrice);
                                // exit;
                                $fareconfirmationArray['Result']['Fare'] = $FareWithMarkup;
                                $fareBreakupArray = $fareconfirmationArray['Result']['Fare'];
                                $AgentWebPMarkUp = $FlightPrice['AgentFareBreakup']['AgentWebPMarkUp'];
                                $AgentWebPDiscount = $FlightPrice['AgentFareBreakup']['AgentWebPDiscount'];
                                $AgentWebPDisplayMarkup = $FlightPrice['AgentFareBreakup']['AgentWebPDisplayMarkup'];
                                $FareBreakUp = array();
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Other & Service Charges"),
                                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                                        "Discount" => array("Value" => 0, "LabelText" => "Discount (-)"),
                                        "Meal" => array("Value" => 0, "LabelText" => "Meal (+)"),
                                        "Baggage" => array("Value" => 0, "LabelText" => "Baggage (+)"),
                                    ),
                                    "TotalAmount" => array("Value" => round_value($fareBreakupArray['PublishedPrice']), "LabelText" => "Pay  Amount"),
                                    "OfferedFare" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS']), "LabelText" => "Offered Fare"),
                                    "AgentWebPMarkUp" => round_value($AgentWebPMarkUp),
                                    "AgentWebPDisplayMarkup" => $AgentWebPDisplayMarkup,
                                    "AgentWebPDiscount" => round_value($AgentWebPDiscount),
                                );
                                $flightConfrimationFareDetailData[$fareconfirmationKey] = $FareBreakUp;
                                $flightConfrimationResponseData[$fareconfirmationKey] = $fareconfirmationArray;
                            } else {
                                $ErrorCode = $fareconfirmationArray['Error']['ErrorCode'];
                                $ErrorMessage = $fareconfirmationArray['Error']['ErrorMessage'];
                                $flightConfrimationResponseData = array();
                                $flightConfrimationFareDetailData = array();
                                break;
                            }
                        } else {
                            $ErrorCode = 400;
                            $ErrorMessage = "No Result Found.";
                            $flightConfrimationResponseData = array();
                            $flightConfrimationFareDetailData = array();
                            break;
                        }
                    }
                    $returnResponse = array(
                        "Error" => array(
                            "ErrorCode" => $ErrorCode,
                            "ErrorMessage" => $ErrorMessage,
                        ),
                        "flightConfrimationResponse" => $flightConfrimationResponseData,
                        "FareBreakUpData" => $flightConfrimationFareDetailData
                    );

                    $dial_code = $FlightModel->get_dial_code();
                } else {
                    $returnResponse = array(
                        "Error" => array(
                            "ErrorCode" => 400,
                            "ErrorMessage" => "No Result Found.",
                        )
                    );
                }
            } else {
                $returnResponse = array(
                    "Error" => array(
                        "ErrorCode" => 400,
                        "ErrorMessage" => "No Result Found.",
                    )
                );
            }
        } else {
            $returnResponse = array(
                "Error" => array(
                    "ErrorCode" => 400,
                    "ErrorMessage" => "No Result Found.",
                )
            );
        }
        $searchPaxInfo = array(
            "Adult" => $searchRequest['Adult'],
            "Child" => $searchRequest['Child'],
            "Infant" => $searchRequest['Infant'],
        );
        $IsDomestic = $searchRequest['IsDomestic'];
        $CommonModel = new CommonModel();
        $processedbutton = 1;
        $webpartnerBalance = $CommonModel->web_partner_balance($this->web_partner_id);
        if (isset($webpartnerBalance['balance']) && $webpartnerBalance['balance'] < 0) {
            $processedbutton = 0;
        }
        $gst_detail = $FlightModel->getDataRowType("master_travelers_gst_info", ['web_partner_id' => $this->web_partner_id, 'wl_agent_id' => $this->wl_agent_id], 'id,company_name,gst_number,phone_number,email,address', 1);
        $MetaInfoData = static_meta_information('Flight', 'Details');
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\FlightBookingtemplate/flight_details",
            'confirmationResponse' => $returnResponse,
            'searchRequest' => $searchRequest,
            'searchData' => $searchRequest,
            'searchPaxInfo' => $searchPaxInfo,
            'IsPanMandatory' => $IsPanMandatory,
            'IsPassportMandatory' => $IsPassportMandatory,
            'IsGSTMandatory' => $IsGSTMandatory,
            'GSTAllowed' => $GSTAllowed,
            'IsGDS' => $IsGDS,
            'gst_detail' => $gst_detail,
            'IsDomestic' => $IsDomestic,
            'dial_code' => $dial_code,
            'processedbutton' => $processedbutton,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
        ];

        return view('template/default-layout', $data);
    }
    function GetMealBaggageData()
    {
        $flightConfrimationRequest = $this->request->getGET();
        $response_ssr = [];
        $MealBaggageContainer = [];
        if ($flightConfrimationRequest) {

            $FlightModel = new FlightModel();

            $whereFlightSearch = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "search");
            $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
            $searchRequest = json_decode($FlightSearchInfo['request'], true);
            $searchPaxInfo = array(
                "Adult" => $searchRequest['Adult'],
                "Child" => $searchRequest['Child'],
                "Infant" => $searchRequest['Infant'],
            );
            $IsDomestic = $searchRequest['IsDomestic'];

            $whereClauseSSR = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "ssr", "selected_index" => $flightConfrimationRequest['farecode']);
            $SSRDataOB = $FlightModel->getApiLogsData($whereClauseSSR, 'response');
            if (empty($SSRDataOB)) {
                $request = array(
                    'UserIp' => $this->request->getIpAddress(),
                    'ResultIndex' => $flightConfrimationRequest['farecode'],
                    'SearchTokenId' => $flightConfrimationRequest['token'],
                );
                $service = "ssr";
                $url = $this->Services . $service;
                $response_ssr['ssrOB'] = Request($request, $url, $service);
            } else {
                $response_ssr['ssrOB'] = json_decode($SSRDataOB['response'], true);
            }
            $ssrOBmeal = [];
            $ssrOBbaggage = [];
            $ssrIBmeal = [];
            $ssrIBbaggage = [];


            if (isset($response_ssr['ssrOB']['Error']['ErrorCode']) && $response_ssr['ssrOB']['Error']['ErrorCode'] == 0) {
                $IsGDS = false;
                $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecode']);
                $flightConfrimationDataOB = $FlightModel->getApiLogsData($whereClause, 'response');
                $flightConfrimationDataOB = json_decode($flightConfrimationDataOB['response'], true);
                if (!$flightConfrimationDataOB['Result']['IsLCC']) {
                    $IsGDS = true;
                }
                if (isset($response_ssr['ssrOB']['Result']['Meal'])) {
                    if ($IsGDS) {
                        $ssrOBmeal = $response_ssr['ssrOB']['Result']['Meal'];
                        $ssrOBmeal = array(array($ssrOBmeal));

                    } else {
                        foreach ($response_ssr['ssrOB']['Result']['Meal'] as $key => $meals) {
                            foreach ($meals as $sub_key => $meal) {
                                $segmentKey = "{$meal['Origin']}-{$meal['Destination']}";
                                if (isset($ssrOBmeal[$key]) && array_key_exists($segmentKey, $ssrOBmeal[$key])) {
                                    $ssrOBmeal[$key][$segmentKey][] = $meal;
                                } else {
                                    $ssrOBmeal[$key][$segmentKey][] = $meal;
                                }
                            }
                        }
                    }
                }

                if (isset($response_ssr['ssrOB']['Result']['Baggage'])) {

                    foreach ($response_ssr['ssrOB']['Result']['Baggage'] as $key => $baggages) {
                        foreach ($baggages as $sub_key => $baggage) {
                            $segmentKey = "{$baggage['Origin']}-{$baggage['Destination']}";
                            if (isset($ssrOBbaggage[$key]) && array_key_exists($segmentKey, $ssrOBbaggage[$key])) {
                                $ssrOBbaggage[$key][$segmentKey][] = $baggage;
                            } else {
                                $ssrOBbaggage[$key][$segmentKey][] = $baggage;
                            }
                        }
                    }

                }
            }
            if ($ssrOBmeal) {
                $MealBaggageContainer[0]['Meal'] = $ssrOBmeal;
            }
            if ($ssrOBbaggage) {
                $MealBaggageContainer[0]['Baggage'] = $ssrOBbaggage;
            }

            if (isset($flightConfrimationRequest['farecodereturn']) && $flightConfrimationRequest['farecodereturn'] != '') {
                $whereClauseSSRIB = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "ssr", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                $SSRDataIB = $FlightModel->getApiLogsData($whereClauseSSRIB, 'response');
                if (empty($SSRDataIB)) {
                    $request = array(
                        'UserIp' => $this->request->getIpAddress(),
                        'ResultIndex' => $flightConfrimationRequest['farecodereturn'],
                        'SearchTokenId' => $flightConfrimationRequest['token'],
                    );
                    $service = "ssr";
                    $url = $this->Services . $service;
                    $response_ssr['ssrIB'] = Request($request, $url, $service);
                } else {
                    $response_ssr['ssrIB'] = json_decode($SSRDataIB['response'], true);
                }

                if ($response_ssr['ssrIB']['Error']['ErrorCode'] == 0) {
                    $IsGDS = false;
                    $whereClause = array("tts_search_token" => $flightConfrimationRequest['token'], "service" => "fareconfirmation", "selected_index" => $flightConfrimationRequest['farecodereturn']);
                    $flightConfrimationDataIB = $FlightModel->getApiLogsData($whereClause, 'response');
                    $flightConfrimationDataIB = json_decode($flightConfrimationDataIB['response'], true);
                    if (!$flightConfrimationDataIB['Result']['IsLCC']) {
                        $IsGDS = true;
                    }
                    if (isset($response_ssr['ssrIB']['Result']['Meal'])) {
                        if ($IsGDS) {
                            $ssrIBmeal = $response_ssr['ssrIB']['Result']['Meal'];
                            $ssrIBmeal = array(array($ssrIBmeal));

                        } else {
                            foreach ($response_ssr['ssrIB']['Result']['Meal'] as $key => $meals) {
                                foreach ($meals as $sub_key => $meal) {
                                    $segmentKey = "{$meal['Origin']}-{$meal['Destination']}";
                                    if (isset($ssrIBmeal[$key]) && array_key_exists($segmentKey, $ssrIBmeal[$key])) {
                                        $ssrIBmeal[$key][$segmentKey][] = $meal;
                                    } else {
                                        $ssrIBmeal[$key][$segmentKey][] = $meal;
                                    }
                                }
                            }
                        }
                    }

                    if (isset($response_ssr['ssrIB']['Result']['Baggage'])) {

                        foreach ($response_ssr['ssrIB']['Result']['Baggage'] as $key => $baggages) {
                            foreach ($baggages as $sub_key => $baggage) {

                                $segmentKey = "{$baggage['Origin']}-{$baggage['Destination']}";

                                if (isset($ssrIBbaggage[$key]) && array_key_exists($segmentKey, $ssrIBbaggage[$key])) {
                                    $ssrIBbaggage[$key][$segmentKey][] = $baggage;
                                } else {
                                    $ssrIBbaggage[$key][$segmentKey][] = $baggage;
                                }
                            }
                        }
                    }
                }
                if ($ssrIBmeal) {
                    $MealBaggageContainer[1]['Meal'] = $ssrIBmeal;
                }
                if ($ssrIBbaggage) {
                    $MealBaggageContainer[1]['Baggage'] = $ssrIBbaggage;
                }


            }
        }

        $finalssrvalue = array();
        if ($MealBaggageContainer) {
            foreach ($MealBaggageContainer as $key => $ssrinfo) {
                foreach ($searchPaxInfo as $paxkey => $pax) {
                    if ($paxkey != 'Infant') {
                        if ($pax != 0) {
                            for ($i = 1; $i <= $pax; $i++) {
                                $finalssrvalue[$key][$paxkey][$i] = $ssrinfo;
                            }
                        }
                    }
                }

            }
        }

        if ($MealBaggageContainer) {
            $returnData['ErrorCode'] = 0;
            $returnData['ErrorMessage'] = '';
            $returnData['SSRData'] = $finalssrvalue;
        } else {
            $returnData['ErrorCode'] = 1;
            $returnData['ErrorMessage'] = 'No SSR Available';
            $returnData['SSRData'] = [];
        }

        return $this->response->setJSON($returnData);
    }



    public function validate_travellers()
    {
        $data = $this->request->getPost();

        $duplicateData = Flight::hasDuplicatePassengerNames($data['pax']);

        if (!empty($duplicateData)) {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => "Duplicate passenger name");
            $Message = ["StatusCode" => 2, "Message" => "Duplicate passenger name", "Class" => "error_popup"];
            return $this->response->setJSON($Message);
        }
        $FlightModel = new FlightModel();
        $whereFlightSearch = array("tts_search_token" => $data['SearchTokenId'], "service" => "search");
        $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
        $searchRequest = json_decode($FlightSearchInfo['request'], true);
        $validate = new Validation();
        $validationConfigArray = $validate->pax_validation($data, $searchRequest);

        $this->validation->setRules($validationConfigArray);
        $rules = $this->validation->run($data);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $response = Flight::savaData($data);
            return $this->response->setJSON($response);
        }
    }

    function hasDuplicatePassengerNames($pax)
    {
        $names = [];
        foreach ($pax as $category => $passengers) {
            foreach ($passengers as $passenger) {
                $name = $passenger['first_name'] . ' ' . $passenger['last_name'];
                if (isset($names[$category][$name])) {
                    return true;
                }
                $names[$category][$name] = true;
            }
        }
        return false;
    }


    private function savaData($data)
    {
        $FlightConfirmationPaxInfo = $data;
        if ($FlightConfirmationPaxInfo) {
            $FlightModel = new FlightModel();
            $gst_info_detail = array();
            $whereClauseBookingCheck = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId']);
            $bookingInfo = $FlightModel->getData("flight_booking_list", $whereClauseBookingCheck, "tts_search_token");
            $AgentGSTInfo = $FlightModel->getDataRowType("agent", array("id" => $this->wl_agent_id, "web_partner_id" => $this->web_partner_id, ), "gst_number");
            $AgentGSTNumber = $AgentGSTInfo['gst_number'];
            $GstNumber = isset($data['gst']['number']) && $data['gst']['number'] != "" ? $data['gst']['number'] : $AgentGSTNumber;

            if (!isset($bookingInfo['tts_search_token'])) {
                if ($FlightConfirmationPaxInfo['rtype'] == "frcmn") {

                    if (!isset($data['gst'])) {
                        $gst_info = json_encode(
                            array(
                                'name' => '',
                                'number' => '',
                                'phone' => '',
                                'email' => '',
                                'address' => ''
                            )
                        );
                    }

                    if (isset($data['savegst']) && $data['savegst'] == 'on') {

                        if (isset($data['gst'])) {
                            $gst_info_detail['company_name'] = $data['gst']['name'];
                            $gst_info_detail['gst_number'] = $data['gst']['number'];
                            $gst_info_detail['phone_number'] = $data['gst']['phone'];
                            $gst_info_detail['email'] = $data['gst']['email'];
                            $gst_info_detail['address'] = $data['gst']['address'];
                            $gst_info_detail['created'] = create_date();
                            $gst_info_detail['wl_agent_id'] = $this->wl_agent_id;
                            $gst_info_detail['web_partner_id'] = $this->web_partner_id;
                            $FlightModel->insertData("master_travelers_gst_info", $gst_info_detail);
                        }
                    }

                    $BaggageData = array();
                    $MealData = array();
                    $SelectedPassengerBaggageData = array();
                    $SelectedPassengerMealData = array();
                    $IsGDSArray = array();
                    if (isset($FlightConfirmationPaxInfo['ssr']['meal']) && !empty($FlightConfirmationPaxInfo['ssr']['meal'])) {
                        $SelectedPassengerMealData = $FlightConfirmationPaxInfo['ssr']['meal'];
                    }
                    if (isset($FlightConfirmationPaxInfo['ssr']['baggage']) && !empty($FlightConfirmationPaxInfo['ssr']['baggage'])) {
                        $SelectedPassengerBaggageData = $FlightConfirmationPaxInfo['ssr']['baggage'];
                    }
                    $whereFlightSearch = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "search");
                    $FlightSearchInfo = $FlightModel->getApiLogsData($whereFlightSearch, 'request');
                    $searchRequest = json_decode($FlightSearchInfo['request'], true);
                    $TotalPax = $searchRequest['Adult'] + $searchRequest['Child'];
                    $TotalPaxWithInfant = $searchRequest['Adult'] + $searchRequest['Child'] + $searchRequest['Infant'];
                    $flightConfrimationData = array();
                    $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $FlightConfirmationPaxInfo['farecode']);
                    $flightConfrimationData['OB'] = $FlightModel->getApiLogsData($whereClause, 'response,tts_index_response');
                    $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "ssr", "selected_index" => $FlightConfirmationPaxInfo['farecode']);
                    $OBSSRADTA = $FlightModel->getApiLogsData($whereClause, 'response');
                    if (isset($OBSSRADTA['response'])) {

                        $OBSSRADTA = json_decode($OBSSRADTA['response'], true);
                        if ($OBSSRADTA['Error']['ErrorCode'] == 0) {
                            $IsGDSArray[0] = json_decode($flightConfrimationData['OB']['response'], true)['Result']['IsLCC'];
                            if (isset($OBSSRADTA['Result']['Meal'])) {
                                $MealData[0] = $OBSSRADTA['Result']['Meal'];
                            }
                            if (isset($OBSSRADTA['Result']['Baggage'])) {
                                $BaggageData[0] = $OBSSRADTA['Result']['Baggage'];
                            }
                        }
                    }
                    if (isset($FlightConfirmationPaxInfo['farecodereturn']) && $FlightConfirmationPaxInfo['farecodereturn'] != "" && $searchRequest['IsDomestic']) {
                        $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "fareconfirmation", "selected_index" => $FlightConfirmationPaxInfo['farecodereturn']);
                        $flightConfrimationData['IB'] = $FlightModel->getApiLogsData($whereClause, 'response,tts_index_response');
                        $whereClause = array("tts_search_token" => $FlightConfirmationPaxInfo['SearchTokenId'], "service" => "ssr", "selected_index" => $FlightConfirmationPaxInfo['farecodereturn']);
                        $IBSSRADTA = $FlightModel->getApiLogsData($whereClause, 'response');
                        if (isset($IBSSRADTA['response'])) {
                            $IBSSRADTA = json_decode($IBSSRADTA['response'], true);
                            if ($IBSSRADTA['Error']['ErrorCode'] == 0) {
                                $IsGDSArray[1] = json_decode($flightConfrimationData['IB']['response'], true)['Result']['IsLCC'];
                                if (isset($IBSSRADTA['Result']['Meal'])) {
                                    $MealData[1] = $IBSSRADTA['Result']['Meal'];
                                }
                                if (isset($IBSSRADTA['Result']['Baggage'])) {
                                    $BaggageData[1] = $IBSSRADTA['Result']['Baggage'];
                                }
                            }
                        }
                    }
                    if ($flightConfrimationData) {
                        $ErrorCode = 0;
                        $ErrorMessage = "";
                        $SaveDataArray = array();
                        $passenger_info = $FlightConfirmationPaxInfo['pax'];
                        $ConvertSSRData = ConvertSSRDataIntoTTSFormat($MealData, $BaggageData, $IsGDSArray);
                        $MealData = $ConvertSSRData['MealData'];
                        $BaggageData = $ConvertSSRData['BaggageData'];
                        $tts_search_token = $FlightConfirmationPaxInfo['SearchTokenId'];
                        $WebPartnerFlightMarkupModel = new WebPartnerFlightMarkupModel();
                        $WebPartnerFlightDiscountModel = new WebPartnerFlightDiscountModel();
                        $AgentFlightMarkupModel = new AgentFlightMarkupModel();
                        $AgentFlightMarkupData = $AgentFlightMarkupModel->getAgentFlightmarkup($this->web_partner_id, $this->wl_agent_id, $searchRequest);
                        $WebPartnermarkupData = $WebPartnerFlightMarkupModel->getFlightmarkup($this->web_partner_id, $this->wl_agent_class, $searchRequest);
                        $WebPartnerdiscountData = $WebPartnerFlightDiscountModel->getFlightdiscount($this->web_partner_id, $this->wl_agent_class, $searchRequest);
                        foreach ($flightConfrimationData as $fareconfirmationKey => $fareconfirmation) {
                            $getTripIndicator = $fareconfirmationKey == 'OB' ? 0 : 1;
                            $selectedMarkupDataInfo = $selectedDiscountDataInfo = array();
                            $common_data = array();
                            if ($fareconfirmation) {
                                $fareconfirmationArray = json_decode($fareconfirmation['response'], true);
                                $fare_confimation = $fareconfirmationArray['Result'];
                                $firstSegment = reset($fare_confimation['Segments']);
                                $lastSegment = end($fare_confimation['Segments']);
                                $origin = $firstSegment['0']['Origin']['CityCode'];
                                $departure_date = explode("T", $firstSegment['0']['Origin']['DepartTime'])[0];
                                if ($searchRequest['JourneyType'] == 3) {
                                    $lastSegmentKey = count($lastSegment) - 1;
                                    $destination = $lastSegment[$lastSegmentKey]['Destination']['CityCode'];
                                } else {
                                    $lastSegmentKey = count($firstSegment) - 1;
                                    $destination = $firstSegment[$lastSegmentKey]['Destination']['CityCode'];
                                }
                                $airline_code = $firstSegment['0']['Airline']['AirlineCode'];
                                $extraParam['CabinClass'] = $firstSegment[0]['CabinClass'];
                                $extraParam['FareType'] = $fare_confimation['FareType'];
                                $extraParam['FareClass'] = $firstSegment[0]['Airline']['FareClass'];
                                $FlightPrice = get_flight_fare($searchRequest, $GstNumber, $this->web_partner_gst_code, $WebPartnermarkupData, $WebPartnerdiscountData, $AgentFlightMarkupData, $fareconfirmationArray['Result']['Fare'], $fareconfirmationArray['Result']['FareBreakdown'], $airline_code, $selectedMarkupDataInfo, $selectedDiscountDataInfo, $extraParam, 'FareConfirmation');

                                $selectedMarkupDataInfo = $FlightPrice['selectedMarkupDataInfo'];
                                $selectedDiscountDataInfo = $FlightPrice['selectedDiscountDataInfo'];
                                $AgentFare = $FlightPrice['Fare'];
                                $AgentFareBreakdown = $FlightPrice['FareBreakdown'];
                                $WebPartnerFareBreakup = $FlightPrice['WebPartnerFareBreakup'];
                                $fareBreakupArray = $fareconfirmationArray['Result']['Fare'];
                                $AgentWebPMarkUp = $FlightPrice['AgentFareBreakup']['AgentWebPMarkUp'];
                                $AgentWebPDiscount = $FlightPrice['AgentFareBreakup']['AgentWebPDiscount'];
                                $AgentWebPDisplayMarkup = $FlightPrice['AgentFareBreakup']['AgentWebPDisplayMarkup'];
                                $WebPMarkUp = isset($WebPartnerFareBreakup['WebPMarkUp']) ? $WebPartnerFareBreakup['WebPMarkUp'] : 0;
                                $WebPDiscount = isset($WebPartnerFareBreakup['WebPDiscount']) ? $WebPartnerFareBreakup['WebPDiscount'] : 0;
                                $WebPDisplayMarkup = isset($WebPartnerFareBreakup['DisplayMarkup']) ? $WebPartnerFareBreakup['DisplayMarkup'] : 'in_tax';
                                $TotalBaggagePrice = 0;
                                $TotalMealPrice = 0;
                                $TotalGST = 0;
                                $perPaxDiscount = 0;
                                $perPaxOtherCharges = 0;
                                $perPaxTDS = 0;
                                $perPaxAgentCommission = 0;
                                $perPaxGST = 0;
                                $perPaxWebPartnermarkup = 0;
                                $perPaxWebPartnerdiscount = 0;
                                $is_price_changed = $fareconfirmationArray['IsPriceChanged'];
                                $FlightFareInfo = $fareconfirmationArray['Result']['Fare'];
                                $TotalGST = $FlightFareInfo['GST']['CGSTAmount'] + $FlightFareInfo['GST']['IGSTAmount'] + $FlightFareInfo['GST']['SGSTAmount'];
                                $perPaxGST = round_value(($TotalGST / $TotalPax));
                                $perPaxDiscount = round_value(($FlightFareInfo['Discount'] / $TotalPax));
                                $perPaxAgentCommission = round_value(($FlightFareInfo['AgentCommission'] / $TotalPax));
                                $perPaxTDS = round_value(($FlightFareInfo['TDS'] / $TotalPax));
                                $perPaxOtherCharges = round_value(($FlightFareInfo['OtherCharges'] / $TotalPax));
                                $perPaxWebPartnermarkup = round_value(($WebPMarkUp / $TotalPax));
                                $perPaxWebPartnerdiscount = round_value(($WebPDiscount / $TotalPax));
                                /*B2B Calculation */
                                if ($fareconfirmationKey == "OB") {
                                    if (isset($data['onwardagentwebpartnermarkup']) && $data['onwardagentwebpartnermarkup']) {
                                        $AgentFare['AgentWebPMarkUp'] = $data['onwardagentwebpartnermarkup'];
                                        $perPaxAgentmarkup = round_value(($data['onwardagentwebpartnermarkup'] / $TotalPax));
                                    } else {
                                        $AgentFare['AgentWebPMarkUp'] = 0;
                                        $perPaxAgentmarkup = 0;
                                    }
                                    if (isset($data['onwardagentwebpartnerdiscount']) && $data['onwardagentwebpartnerdiscount']) {
                                        $AgentFare['AgentWebPDiscount'] = $data['onwardagentwebpartnerdiscount'];
                                        $perPaxB2BAgentrdiscount = round_value(($data['onwardagentwebpartnerdiscount'] / $TotalPax));
                                    } else {
                                        $AgentFare['AgentWebPDiscount'] = 0;
                                        $perPaxB2BAgentrdiscount = 0;
                                    }
                                    if (isset($data['onwardagentdisplaymarkup']) && $data['onwardagentdisplaymarkup']) {
                                        $AgentFare['AgentWebPDisplayMarkup'] = $data['onwardagentdisplaymarkup'];
                                    } else {
                                        $AgentFare['AgentWebPDisplayMarkup'] = "in_tax";
                                    }
                                }
                                if ($fareconfirmationKey == "IB") {
                                    if (isset($data['returnagentwebpartnermarkup']) && $data['returnagentwebpartnermarkup']) {
                                        $AgentFare['AgentWebPMarkUp'] = $data['returnagentwebpartnermarkup'];
                                        $perPaxAgentmarkup = round_value(($data['returnagentwebpartnermarkup'] / $TotalPax));
                                    } else {
                                        $AgentFare['AgentWebPMarkUp'] = 0;
                                        $perPaxAgentmarkup = 0;
                                    }
                                    if (isset($data['returnagentwebpartnerdiscount']) && $data['returnagentwebpartnerdiscount']) {
                                        $AgentFare['AgentWebPDiscount'] = $data['returnagentwebpartnerdiscount'];
                                        $perPaxB2BAgentrdiscount = round_value(($data['returnagentwebpartnerdiscount'] / $TotalPax));
                                    } else {
                                        $AgentFare['AgentWebPDiscount'] = 0;
                                        $perPaxB2BAgentrdiscount = 0;
                                    }
                                    if (isset($data['returnagentdisplayMarkup']) && $data['returnagentdisplayMarkup']) {
                                        $AgentFare['AgentWebPDisplayMarkup'] = $data['returnagentdisplayMarkup'];
                                    } else {
                                        $AgentFare['AgentWebPDisplayMarkup'] = "in_tax";
                                    }
                                }
                                $AgentTotalGST = 0;
                                $perPaxAgentDiscount = 0;
                                $perPaxAgentOtherCharges = 0;
                                $perPaxAgentTDS = 0;
                                $perPaxB2BAgentCommission = 0;
                                $perPaxAgentGST = 0;
                                $perPaxAgentmarkup = 0;
                                $AgentTotalGST = $AgentFare['GST']['CGSTAmount'] + $AgentFare['GST']['IGSTAmount'] + $AgentFare['GST']['SGSTAmount'];
                                $perPaxAgentGST = round_value(($AgentTotalGST / $TotalPax));
                                $perPaxAgentDiscount = round_value(($AgentFare['Discount'] / $TotalPax));
                                $perPaxB2BAgentCommission = round_value(($AgentFare['AgentCommission'] / $TotalPax));
                                $perPaxAgentTDS = round_value(($AgentFare['TDS'] / $TotalPax));
                                $perPaxAgentOtherCharges = round_value(($AgentFare['OtherCharges'] / $TotalPax));
                                /*B2B Calculation */
                                $common_data = json_decode($fareconfirmation['tts_index_response'], true);
                                $common_data = $common_data[$fare_confimation['ResultIndex']];
                                $TTS_Invoice_Amount = $common_data['TTS_Invoice_Amount'];
                                $is_time_changed = false;
                                $savePaxinfo = array();
                                $insertPaxdata = array();
                                if ($fareconfirmationArray['Error']['ErrorCode'] == 0) {
                                    foreach ($passenger_info as $paxsupkey => $passengers) {
                                        foreach ($passengers as $paxsubkey => $passenger) {
                                            $apipassengerData = array();
                                            $baggage = array();
                                            $meal = array();
                                            $seat = null;
                                            $baggage_charges = 0;
                                            $meal_charges = 0;
                                            $seat_charges = 0;
                                            if ($SelectedPassengerMealData) {
                                                if (isset($SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey]) && !empty($SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey])) {
                                                    $PassengermealDataInfos = $SelectedPassengerMealData[$getTripIndicator][$paxsupkey][$paxsubkey];
                                                    if ($PassengermealDataInfos) {
                                                        foreach ($PassengermealDataInfos as $journeyKey => $PassengermealDataInfo) {
                                                            foreach ($PassengermealDataInfo as $segmentkey => $selectpaxMeal) {
                                                                if ($selectpaxMeal) {
                                                                    $selectpaxMealCode = explode('@@', $selectpaxMeal)[0];
                                                                    $segMentMealInfo = $MealData[$getTripIndicator][$journeyKey][$segmentkey];
                                                                    $MealCodeArray = array_column($segMentMealInfo, 'Code');
                                                                    $MealCodeIndexKey = array_search($selectpaxMealCode, $MealCodeArray);
                                                                    $selectedMeal = isset($segMentMealInfo[$MealCodeIndexKey]) ? $segMentMealInfo[$MealCodeIndexKey] : array();
                                                                    if ($selectedMeal) {
                                                                        array_push($meal, $selectedMeal);
                                                                        $meal_charges = $meal_charges + $selectedMeal['Price'];
                                                                        $TotalMealPrice = $TotalMealPrice + $selectedMeal['Price'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if ($SelectedPassengerBaggageData) {
                                                if (isset($SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey]) && !empty($SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey])) {
                                                    $PassengerBaggageDataInfos = $SelectedPassengerBaggageData[$getTripIndicator][$paxsupkey][$paxsubkey];
                                                    if ($PassengerBaggageDataInfos) {
                                                        foreach ($PassengerBaggageDataInfos as $journeyKey => $PassengerBaggageDataInfo) {
                                                            foreach ($PassengerBaggageDataInfo as $segmentkey => $selectpaxBaggage) {
                                                                if ($selectpaxBaggage) {
                                                                    $selectpaxBaggageCode = explode('@@', $selectpaxBaggage)[0];
                                                                    $segMentBaggageInfo = $BaggageData[$getTripIndicator][$journeyKey][$segmentkey];
                                                                    $BaggageCodeArray = array_column($segMentBaggageInfo, 'Code');
                                                                    $BaggageCodeIndexKey = array_search($selectpaxBaggageCode, $BaggageCodeArray);
                                                                    $selectedBaggage = isset($segMentBaggageInfo[$BaggageCodeIndexKey]) ? $segMentBaggageInfo[$BaggageCodeIndexKey] : array();
                                                                    if ($selectedBaggage) {
                                                                        array_push($baggage, $selectedBaggage);
                                                                        $baggage_charges = $baggage_charges + $selectedBaggage['Price'];
                                                                        $TotalBaggagePrice = $TotalBaggagePrice + $selectedBaggage['Price'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if (isset($passenger['passport_no']) && $passenger['passport_no'] != '') {
                                                $PassportNo = $passenger['passport_no'];
                                            } else {
                                                $PassportNo = NULL;
                                            }
                                            if (isset($passenger['passport_issue_date']) && $passenger['passport_issue_date'] != '') {
                                                $PassportIssueDate = date("Y-m-d", strtotime($passenger['passport_issue_date'])) . "T00:00:00";
                                            } else {
                                                $PassportIssueDate = NULL;
                                            }
                                            if (isset($passenger['passport_expire_date']) && $passenger['passport_expire_date'] != '') {
                                                $PassportExpDate = date("Y-m-d", strtotime($passenger['passport_expire_date'])) . "T00:00:00";
                                            } else {
                                                $PassportExpDate = NULL;
                                            }
                                            if (isset($passenger['dob']) && $passenger['dob'] != '') {
                                                $dob = date("Y-m-d", strtotime($passenger['dob'])) . "T00:00:00";
                                            } else {
                                                $dob = NULL;
                                            }
                                            if (isset($passenger['pancard'])) {
                                                $pancard = $passenger['pancard'];
                                            } else {
                                                $pancard = NULL;
                                            }
                                            if (isset($passenger['nationality']) && $passenger['nationality'] != "") {
                                                $nationality = $passenger['nationality'];
                                            } else {
                                                $nationality = NULL;
                                            }
                                            $paxcode = get_paxtype_code($paxsupkey);
                                            $pax_fare_breakdown = $fare_confimation['FareBreakdown'][$paxcode];
                                            $base_fare = round_value($pax_fare_breakdown['BaseFare'] / $pax_fare_breakdown['PassengerCount']);
                                            $tax = round_value($pax_fare_breakdown['Tax'] / $pax_fare_breakdown['PassengerCount']);
                                            $yq_tax = round_value($pax_fare_breakdown['YQTax'] / $pax_fare_breakdown['PassengerCount']);
                                            $service_charge = round_value($pax_fare_breakdown['ServiceCharges'] / $pax_fare_breakdown['PassengerCount']);
                                            if ($paxsupkey == "Infant") {
                                                $perPaxDiscount = 0;
                                                $perPaxTDS = 0;
                                                $perPaxAgentCommission = 0;
                                                $perPaxGST = 0;
                                                $perPaxOtherCharges = 0;
                                                $perPaxWebPartnermarkup = 0;
                                                $perPaxWebPartnerdiscount = 0;
                                            }
                                            $fare = array(
                                                'BaseFare' => $base_fare,
                                                'Tax' => $tax,
                                                'YQTax' => $yq_tax,
                                                'ServiceCharges' => $service_charge,
                                                'OtherCharges' => $perPaxOtherCharges,
                                                'Discount' => $perPaxDiscount,
                                                'AgentCommission' => $perPaxAgentCommission,
                                                'TDS' => $perPaxTDS,
                                                'WebPMarkUp' => $perPaxWebPartnermarkup,
                                                'WebPDiscount' => $perPaxWebPartnerdiscount,
                                                'GSTAmount' => $perPaxGST,
                                                'PublishedPrice' => round_value($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST),
                                                'OfferedPrice' => round_value(($base_fare + $tax + $service_charge + $perPaxOtherCharges + $perPaxGST) - ($perPaxAgentCommission + $perPaxDiscount)),
                                                'BaggageCharges' => $baggage_charges,
                                                'MealCharges' => $meal_charges,
                                                'SeatCharges' => $seat_charges
                                            );
                                            $pax_fare_breakdown_agent = $AgentFareBreakdown[$paxcode];
                                            $base_fare_agent = round_value($pax_fare_breakdown_agent['BaseFare'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $tax_agent = round_value($pax_fare_breakdown_agent['Tax'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $yq_tax_agent = round_value($pax_fare_breakdown_agent['YQTax'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            $service_charge_agent = round_value($pax_fare_breakdown_agent['ServiceCharges'] / $pax_fare_breakdown_agent['PassengerCount']);
                                            if ($paxsupkey == "Infant") {
                                                $perPaxAgentDiscount = 0;
                                                $perPaxAgentOtherCharges = 0;
                                                $perPaxAgentTDS = 0;
                                                $perPaxB2BAgentCommission = 0;
                                                $perPaxAgentGST = 0;
                                                $perPaxAgentmarkup = 0;
                                                $perPaxB2BAgentrdiscount = 0;
                                            }

                                            $agent_fare = array(
                                                'BaseFare' => $base_fare_agent,
                                                'Tax' => $tax_agent,
                                                'YQTax' => $yq_tax_agent,
                                                'ServiceCharges' => $service_charge_agent,
                                                'OtherCharges' => $perPaxAgentOtherCharges,
                                                'Discount' => $perPaxAgentDiscount,
                                                'AgentCommission' => $perPaxB2BAgentCommission,
                                                'TDS' => $perPaxAgentTDS,
                                                'MarkUp' => $perPaxAgentmarkup,
                                                'AgentDiscount' => $perPaxB2BAgentrdiscount,
                                                'GSTAmount' => $perPaxAgentGST,
                                                'PublishedPrice' => round_value($base_fare_agent + $tax_agent + $service_charge_agent + $perPaxAgentOtherCharges + $perPaxAgentGST),
                                                'OfferedPrice' => round_value(($base_fare_agent + $tax_agent + $service_charge_agent + $perPaxAgentOtherCharges + $perPaxAgentGST) - ($perPaxB2BAgentCommission + $perPaxB2BAgentrdiscount)),
                                                'BaggageCharges' => $baggage_charges,
                                                'MealCharges' => $meal_charges,
                                                'SeatCharges' => $seat_charges
                                            );
                                            $gender = pax_title_type($passenger['title']);
                                            $apipassengerData = array(
                                                "title" => $passenger['title'],
                                                "first_name" => $passenger['first_name'],
                                                "last_name" => $passenger['last_name'],
                                                "pax_type" => type_adt($paxsupkey),
                                                "gendar" => $gender == 1 ? "male" : "female",
                                                "date_of_birth" => $dob,
                                                "pan_number" => $pancard,
                                                "passport_number" => $PassportNo,
                                                "passport_expiry" => $PassportExpDate,
                                                "passport_issue_date" => $PassportIssueDate,
                                                "lead_pax" => $paxsupkey == "Adult" && $paxsubkey == "1" ? true : false,
                                                "email_id" => $FlightConfirmationPaxInfo['email'],
                                                "mobile_number" => $FlightConfirmationPaxInfo['mobile_number'],
                                                "address_1" => (isset($this->wl_agent_details['address']) && $this->wl_agent_details['address'] != null) ? $this->wl_agent_details['address'] : $this->web_partner_details['address'],
                                                "address_2" => "",
                                                "city" => (isset($this->wl_agent_details['city']) && $this->wl_agent_details['city'] != null) ? $this->wl_agent_details['city'] : $this->web_partner_details['city'],
                                                "country_code" => "IN",
                                                "nationality" => $nationality,
                                                "country_name" => (isset($this->wl_agent_details['country']) && $this->wl_agent_details['country'] != null) ? $this->wl_agent_details['country'] : $this->web_partner_details['country'],
                                                "ff_airline" => isset($passenger['frequent_fly_airline']) ? $passenger['frequent_fly_airline'] : "",
                                                "ff_number" => isset($passenger['frequent_fly_number']) ? $passenger['frequent_fly_number'] : "",
                                                "fare" => json_encode($fare),
                                                "agent_fare" => json_encode($agent_fare),
                                                "baggage" => !empty($baggage) ? json_encode($baggage) : null,
                                                "meal" => !empty($meal) ? json_encode($meal) : null,
                                                "seat" => $seat,
                                                'booking_status' => "Processing"
                                            );
                                            array_push($insertPaxdata, $apipassengerData);
                                        }
                                    }
                                    $leadPax = $insertPaxdata[0]['title'] . " " . $insertPaxdata[0]['first_name'] . " " . $insertPaxdata[0]['last_name'];
                                    $WebPartnerFareBreakup = $common_data['WebPartnerFareBreakup'];
                                    if ($WebPartnerFareBreakup) {
                                        $WebPartnerFareBreakup['WebPMarkUp'] = $WebPMarkUp;
                                        $WebPartnerFareBreakup['WebPDiscount'] = $WebPDiscount;
                                        $WebPartnerFareBreakup['WebPDisplayMarkup'] = $WebPDisplayMarkup;
                                    }
                                    $WebPartnerFareBreakup['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $WebPartnerFareBreakup['TotalMealCharges'] = $TotalMealPrice;
                                    $WebPartnerFareBreakup['TotalSeatCharges'] = 0;
                                    $AgentFare['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $AgentFare['TotalMealCharges'] = $TotalMealPrice;
                                    $AgentFare['TotalSeatCharges'] = 0;
                                    $common_data['SuperAdminFareBreakup']['TotalBaggageCharges'] = $TotalBaggagePrice;
                                    $common_data['SuperAdminFareBreakup']['TotalMealCharges'] = $TotalMealPrice;
                                    $common_data['SuperAdminFareBreakup']['TotalSeatCharges'] = 0;
                                    $TTS_Invoice_Amount = floatval($AgentFare['OfferedPrice']) + floatval($TotalMealPrice) + floatval($TotalBaggagePrice) + floatval($AgentFare['TDS']);
                                    $web_partner_booking_total_price = floatval($WebPartnerFareBreakup['OfferedPrice']) + floatval($WebPartnerFareBreakup['TDS']) + floatval($TotalMealPrice) + floatval($TotalBaggagePrice);
                                    $savePaxinfo = array(
                                        'tts_search_token' => $tts_search_token,
                                        'web_partner_id' => $this->web_partner_id,
                                        'is_price_changed' => $is_price_changed,
                                        'is_time_changed' => $is_time_changed,
                                        'trip_indicator' => $fareconfirmationKey == "OB" ? 1 : 2,
                                        'search_request' => json_encode($searchRequest),
                                        'journey_type' => get_journey_type($searchRequest['JourneyType']),
                                        'origin' => $origin,
                                        'resultIndex' => $fare_confimation['ResultIndex'],
                                        'destination' => $destination,
                                        'departure_date' => $departure_date,
                                        'is_domestic' => $searchRequest['IsDomestic'],
                                        'is_lcc' => $fare_confimation['IsLCC'],
                                        'is_refundable' => $fare_confimation['IsRefundable'],
                                        'fare_type' => $fare_confimation['FareType'],
                                        'airline_code' => $airline_code,
                                        'validating_airline_code' => $fare_confimation['ValidatingAirline'],
                                        'last_ticket_date' => $fare_confimation['LastTicketDate'],
                                        'airline_remark' => $fare_confimation['AirlineRemark'],
                                        'segments' => json_encode($fare_confimation['Segments']),
                                        'api_supplier' => $common_data['Supplier'],
                                        'payment_mode' => 'Online',
                                        'is_manual' => false,
                                        'payment_status' => 'Processing',
                                        'booking_status' => 'Processing',
                                        'wl_agent_staff_id' => $this->wl_agent_staff_id,
                                        'lead_pax' => $leadPax,
                                        'gst_info' => isset($data['gst']) ? json_encode($data['gst']) : $gst_info,
                                        'super_admin_fare_break_up' => json_encode($common_data['SuperAdminFareBreakup']),
                                        'web_partner_fare_break_up' => json_encode($WebPartnerFareBreakup),
                                        'agent_fare_break_up' => json_encode($AgentFare),
                                        'wl_agent_id' => $this->wl_agent_id,
                                        'distributor_id' => $this->wl_agent_details['distributor_id'],
                                        'booking_source' => $this->booking_source,
                                        'booking_channel' => 'Desktop',
                                        'total_price' => $TTS_Invoice_Amount,
                                        'web_partner_booking_total_price' => $web_partner_booking_total_price,
                                        'web_partner_payment_status' => 'Processing',
                                        'is_gst_mandatory' => $fare_confimation['IsGSTMandatory'],
                                        'is_gst_allowed' => $fare_confimation['GSTAllowed'],
                                        'passenger_info' => $insertPaxdata,
                                        'created' => create_date()
                                    );

                                    $SaveDataArray[$fareconfirmationKey] = $savePaxinfo;
                                } else {
                                    $ErrorCode = $fareconfirmationArray['Error']['ErrorCode'];
                                    $ErrorMessage = $fareconfirmationArray['Error']['ErrorMessage'];
                                    $flightConfrimationResponseData = array();
                                    $SaveDataArray = array();
                                    break;
                                }
                            } else {
                                $ErrorCode = 400;
                                $ErrorMessage = "No Result Found.";
                                $SaveDataArray = array();
                                break;
                            }
                        }
                    } else {
                        $ErrorCode = 400;
                        $ErrorMessage = "No Result Found.";
                        $SaveDataArray = array();
                    }
                    if ($ErrorCode == 0 && $SaveDataArray) {
                        $bookingIdArray = array();
                        foreach ($SaveDataArray as $savekey => $flight_booking) {
                            $passengerInfo = $flight_booking['passenger_info'];
                            unset($flight_booking['passenger_info']);
                            $booking_id = $FlightModel->insertData('flight_booking_list', $flight_booking);
                            $bookingIdArray[$savekey] = $booking_id;
                            $InsertDatapassengerInfo = array_map(function ($value, $flightBookingId) {
                                $value['flight_booking_id'] = $flightBookingId;
                                return $value;
                            }, $passengerInfo, array_fill(0, (count($passengerInfo)), $booking_id));
                            $FlightModel->insertBatchData('flight_booking_travelers', $InsertDatapassengerInfo);
                            /*------------------ Update Booking  Data ----------------------------*/
                            $super_admin__booking_pre_fix_code = $FlightModel->service_booking_pre_fix_code()['pre_fix'];
                            $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_id;
                            $booking_update_data = array(
                                'booking_ref_number' => $booking_ref_number,
                            );
                            $FlightModel->updateUserData('flight_booking_list', ['id' => $booking_id], $booking_update_data);
                            /*------------------ Update BookingData ----------------------------*/
                        }
                        $paymentkey = dev_encode(json_encode(array('service' => 'flight', 'booking_id' => $bookingIdArray, "SearchTokenId" => $tts_search_token)));
                        $url = site_url('payment/opt/') . $paymentkey;
                        return array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $url);
                    } else {
                        return array("StatusCode" => 9, "ErrorMessage" => $ErrorMessage ? $ErrorMessage : "Technical problem occured");
                    }
                } else {
                    return array("StatusCode" => 9, "ErrorMessage" => "Technical problem occured");
                }
            } else {
                return array("StatusCode" => 9, "ErrorMessage" => "Request Not Allowed");
            }
        } else {
            return array("StatusCode" => 9, "ErrorMessage" => "Request Not Allowed");
        }
    }

    function paymentStatus()
    {
        $uri = service('uri');
        $payment_token = $uri->getSegment(3);
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return $this->response->redirect(site_url('flight/error?errormessage=Payment Record not found'));
        } else {
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\FlightBookingtemplate\payment_redirect",
                'payment_token' => $payment_token,
                'paymentdata' => $paymentdata
            ];
            return view('template/default-layout', $data);
        }
    }

    function flightBooking()
    {
        $flightbookRequest = $this->request->getPOST();
        if ($flightbookRequest) {
            $FlightModel = new FlightModel();
            $payment_token = $flightbookRequest['payment_token'];
            $paymentdata = json_decode(dev_decode($payment_token), true);
            if ($paymentdata) {
                $bookingIds = explode(",", $paymentdata['booking_id']);
                $bookingRefNo = array();
                $bookingRefNo = $bookingIds;
                $bookingData = array();
                if ($bookingIds) {
                    foreach ($bookingIds as $bookingId) {
                        $bookingInfo = $FlightModel->getBookingData($bookingId, $this->web_partner_id);
                        if (isset($bookingInfo['id']) && $bookingInfo['id'] == $bookingId) {
                            if ($bookingInfo['booking_status'] == "Processing" && $bookingInfo['book_request'] != "requested") {
                                $bookingData[] = $bookingInfo;
                            } else {
                                $bookingData = array();
                                break;
                            }
                        } else {
                            $bookingData = array();
                            break;
                        }
                    }
                }
                if ($bookingData) {
                    $ErrorCode = 0;
                    $ErrorMessage = "";
                    foreach ($bookingData as $bookingInfo) {
                        $bookingId = $bookingInfo['id'];
                        $bookingRefNumber = $bookingInfo['booking_ref_number'];
                        $rtype = $bookingInfo['trip_indicator'] == 1 ? "OB" : "IB";
                        $passengerInfo = json_decode($bookingInfo['travelersInfo'], true);
                        $gst_info = json_decode($bookingInfo['gst_info'], true);
                        $MobileNumber = $passengerInfo[0]['mobile_number'];
                        $EmailId = $passengerInfo[0]['email_id'];
                        $bookingPaxdata = array();
                        foreach ($passengerInfo as $passenger) {
                            $FFAirline = "";
                            $FFNumber = "";
                            if (!$bookingInfo['is_lcc']) {
                                $FFAirline = $passenger['ff_airline'];
                                $FFNumber = $passenger['ff_number'];
                            }
                            $apipassengerData = array(
                                "Title" => $passenger['title'],
                                "FirstName" => $passenger['first_name'],
                                "LastName" => $passenger['last_name'],
                                "PaxType" => type_adt($passenger['pax_type']),
                                "DateOfBirth" => $passenger['date_of_birth'],
                                "Gender" => $passenger['gendar'] == 'male' ? 1 : 2,
                                "PassportNo" => $passenger['passport_number'],
                                "PassportExpiry" => $passenger['passport_expiry'],
                                "PassportIssue" => $passenger['passport_issue_date'],
                                "PAN" => $passenger['pan_number'],
                                "AddressLine1" => substr($passenger['address_1'], 0, 28),
                                "AddressLine2" => $passenger['address_2'],
                                "City" => $passenger['city'],
                                "CountryCode" => $passenger['country_code'],
                                "CountryName" => $passenger['country_name'],
                                "ContactNo" => $passenger['mobile_number'],
                                "Email" => $passenger['email_id'],
                                "Nationality" => $passenger['nationality'],
                                "IsLeadPax" => $passenger['lead_pax'],
                                "FFAirline" => $FFAirline,
                                "FFNumber" => $FFNumber,
                                "Baggage" => $passenger['baggage'] != null ? json_decode($passenger['baggage'], true) : null,
                                "Meal" => $passenger['meal'] != null ? json_decode($passenger['meal'], true) : null,

                            );
                            if ($bookingInfo['is_gst_allowed']) {
                                $apipassengerData['GSTCompanyAddress'] = isset($gst_info['address']) ? $gst_info['address'] : null;
                                $apipassengerData['GSTCompanyContactNumber'] = isset($gst_info['phone']) ? $gst_info['phone'] : null;
                                $apipassengerData['GSTCompanyEmail'] = isset($gst_info['email']) ? $gst_info['email'] : null;
                                $apipassengerData['GSTCompanyName'] = isset($gst_info['name']) ? $gst_info['name'] : null;
                                $apipassengerData['GSTNumber'] = isset($gst_info['number']) ? $gst_info['number'] : null;
                            } else {
                                $apipassengerData['GSTCompanyAddress'] = null;
                                $apipassengerData['GSTCompanyContactNumber'] = null;
                                $apipassengerData['GSTCompanyEmail'] = null;
                                $apipassengerData['GSTCompanyName'] = null;
                                $apipassengerData['GSTNumber'] = null;
                            }
                            array_push($bookingPaxdata, $apipassengerData);
                        }
                        $bookRequest = array(
                            "UserIp" => $this->request->getIpAddress(),
                            "SearchTokenId" => $bookingInfo['tts_search_token'],
                            "ResultIndex" => $bookingInfo['resultIndex'],
                            "Passengers" => $bookingPaxdata,

                        );
                        if (!$bookingInfo['is_lcc']) {
                            $service = "book";
                        } else {
                            $service = "ticket";
                        }
                        $url = $this->Services . $service;

                        $response = Request($bookRequest, $url, $service);

                        if ($response) {
                            if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                                if (!$bookingInfo['is_lcc']) {
                                    $bookingInfo = $FlightModel->getBookingData($bookingId, $this->web_partner_id);
                                    $service = "ticket";
                                    $ticketRequest = array(
                                        "UserIp" => $this->request->getIpAddress(),
                                        "SearchTokenId" => $bookingInfo['tts_search_token'],
                                        "ResultIndex" => $bookingInfo['resultIndex'],
                                        "PNR" => $bookingInfo['pnr'],
                                        "BookingId" => $bookingInfo['id']
                                    );
                                    $url = $this->Services . $service;
                                    $response = Request($ticketRequest, $url, $service);
                                }
                                $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id);
                                $ErrorCode = $response['Error']['ErrorCode'];
                                $ErrorMessage = $response['Error']['ErrorMessage'];
                                $updateData = array("book_request" => "requested");
                                $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                                $getBookingInfo = $FlightModel->getDataRowType("flight_booking_list", $whereCondition, "booking_status,booking_ref_number,agent_fare_break_up,wl_agent_id,gst_info,pnr");
                                if ($getBookingInfo['booking_status'] == 'Confirmed') {
                                    $agent_fare_break_up = json_decode($getBookingInfo['agent_fare_break_up'], true);
                                    $gst_info = json_decode($getBookingInfo['gst_info'], true);
                                    $AgentGSTInfo = $FlightModel->getDataRowType("agent", array("id" => $getBookingInfo['wl_agent_id'], "web_partner_id" => $this->web_partner_id), "gst_number");
                                    $AgentGSTNumber = $AgentGSTInfo['gst_number'];
                                    $GstNumber = isset($data['number']) && $gst_info['number'] != "" ? $gst_info['number'] : $AgentGSTNumber;
                                    $checkTaxableInvoce = checkTaxableNonTaxableINV($agent_fare_break_up, $GstNumber, 'flight', 'INV');
                                    $INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'flight');
                                    $financialYear = get_financial_year();
                                    $whereCondition = array();
                                    $whereCondition['service'] = 'flight';
                                    $whereCondition['web_partner_id'] = $this->web_partner_id;
                                    $whereCondition['invoice_type'] = 'INV';
                                    $whereCondition['financial_year'] = $financialYear;
                                    $otherParameter['financialYear'] = $financialYear;
                                    $otherParameter['service'] = 'flight';
                                    $otherParameter['invoice_type'] = 'INV';
                                    $otherParameter['INVPrifix'] = $INVPrifix;
                                    $otherParameter['web_partner_id'] = $this->web_partner_id;
                                    $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
                                    $CommonModel = new CommonModel();
                                    $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                                    $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                                    $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                                    $InvoiceupdateData = $InvoiceInfoData['updateData'];
                                    $agent_account_log = $FlightModel->getDataRowType("agent_account_log", ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "wl_agent_id" => $getBookingInfo['wl_agent_id']], "service_log");
                                    $service_log = json_decode($agent_account_log['service_log'], true);
                                    $service_log['TicketNo'] = $getBookingInfo['pnr'];
                                    $FlightModel->updateUserData('agent_account_log', ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "wl_agent_id" => $getBookingInfo['wl_agent_id'], "web_partner_id" => $this->web_partner_id], ["invoice_number" => $InvoiceNumber, "service_log" => json_encode($service_log)]);
                                    $FlightModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                                }
                            } else {
                                $ErrorCode = $response['Error']['ErrorCode'];
                                $ErrorMessage = $response['Error']['ErrorMessage'];
                                $updateData = array("book_request" => "requested");
                                $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id);
                                $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                            }
                            if ($ErrorCode != 0) {
                                break;
                            }
                        } else {
                            $ErrorCode == 400;
                            $ErrorMessage = "Request Not Allowed";
                            $updateData = array("book_request" => "requested");
                            $whereCondition = array("id" => $bookingId, "web_partner_id" => $this->web_partner_id);
                            $FlightModel->updateUserData("flight_booking_list", $whereCondition, $updateData);
                            break;
                        }
                    }
                    if ($rtype == "OB" && $ErrorCode != 0) {
                        $sms_type = "Flight Booking Failed";
                        $tempid = $this->SmsTemplate['FlightBookingFailed'];
                        $Smsmessage = "Dear Travel Partner, Your Booking with Id " . $bookingRefNumber . " has failed. In case of any issues, kindly contact the support team ";
                        /*   send_sms($MobileNumber, $Smsmessage, $tempid, $sms_type); */
                        return $this->response->redirect(site_url('flight/error?errormessage=' . $ErrorMessage));
                    } else {
                        $ticketData = dev_encode(json_encode($bookingRefNo));
                        return $this->response->redirect(site_url('flight/confirmation/' . $ticketData . "?type=Booking"));
                    }
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }


    function error()
    {
        $error = $this->request->getGET('errormessage');
        return view('template/custom-error-layout', ['error_message' => $error]);
    }

    function confirmation()
    {
        $uri = service('uri');
        $bookingIds = $uri->getSegment(3);
        $bookingIds = json_decode(dev_decode($bookingIds), true);
        $FlightModel = new FlightModel();
        $bookingConfrimationData = array();
        $ConfrimationData = array();

        if ($bookingIds) {
            $onwardPnr = "";
            $returnPnr = "";
            $returnBookingRefNumber = "";
            $onwardBookingRefNumber = "";
            $airlineLogoClass = "domAirLogo";
            $BookingStatus = array();
            $Pnr = array();
            $BookingRefNumber = array();
            $FareBreakUpDataArray = array();
            $TicketOption = array();
            $InvoiceOption = array();
            $smsCount = 0;
            foreach ($bookingIds as $bookingId) {
                $paymentGatewayInfo = array();
                $FareBreakUp = array();
                $bookingInfo = $FlightModel->getBookingConfirmationData($bookingId, $this->web_partner_id, $this->wl_agent_id, $this->booking_source);
                $rtype = $bookingInfo['trip_indicator'] == 1 ? "OB" : "IB";
                $searchData = json_decode($bookingInfo['search_request'], true);
                $segments = json_decode($bookingInfo['segments'], true);
                $FlightNumber = $bookingInfo['airline_code'] . "-" . $segments[0][0]['Airline']['FlightNumber'];
                $childCount = $searchData['Child'];
                $infantCount = $searchData['Infant'];
                $TravelersInfo = json_decode($bookingInfo['travelersInfo'], true);
                $Name = $TravelersInfo[0]['title'] . " " . $TravelersInfo[0]['first_name'] . " " . $TravelersInfo[0]['last_name'];
                $MobileNumber = $TravelersInfo[0]['mobile_number'];
                $EmailId = $TravelersInfo[0]['email_id'];
                if ($bookingInfo['is_domestic']) {
                    $airlineLogoClass = "domAirLogo";
                } else {
                    $airlineLogoClass = "intAirLogo";
                }
                if ($bookingInfo['trip_indicator'] == 1) {
                    $onwardPnr = $bookingInfo['pnr'];
                    $onwardBookingRefNumber = $bookingInfo['booking_ref_number'];
                    $onwardBookingstatus = $bookingInfo['booking_status'];
                    $BookingStatus[] = $onwardBookingstatus;
                    if ($onwardPnr) {
                        $Pnr[] = $onwardPnr;
                        if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic'] && isset($_GET['type']) && $_GET['type'] == "Booking") {
                            $TicketOption[] = "Onward";
                            $InvoiceOption[] = "Onward";
                        }
                    }
                    $BookingRefNumber[] = $onwardBookingRefNumber;
                } else {
                    $returnPnr = $bookingInfo['pnr'];
                    $returnBookingRefNumber = $bookingInfo['booking_ref_number'];
                    $returnBookingstatus = $bookingInfo['booking_status'];
                    $BookingStatus[] = $returnBookingstatus;
                    if ($returnPnr) {
                        $Pnr[] = $returnPnr;
                        if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic'] && isset($_GET['type']) && $_GET['type'] == "Booking") {
                            $TicketOption[] = "Return";
                            $TicketOption[] = "Both";
                            $InvoiceOption[] = "Return";
                        }
                    }
                    $BookingRefNumber[] = $returnBookingRefNumber;
                }
                $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);

                $addagentMarkupInTax = 0;
                $addagentMarkupInServiceCharge = 0;
                $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                $AgentWebPDisplayMarkup = isset($fareBreakupArray['AgentWebPDisplayMarkup']) ? $fareBreakupArray['AgentWebPDisplayMarkup'] : "in_tax";
                $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                if (isset($fareBreakupArray['AgentWebPDisplayMarkup']) && $fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_service_charge') {
                    $addagentMarkupInServiceCharge = $AgentWebPMarkUp;
                } else {
                    $addagentMarkupInTax = $AgentWebPMarkUp;
                }
                $FareBreakUp = array();
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax), "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge), "LabelText" => "Service Charges"),
                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                        /*  "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
                        "Discount" => array("Value" => round_value($AgentWebPDiscount), "LabelText" => "Discount (-)"),

                    ),
                    "TotalAmount" => array("Value" => round_value($fareBreakupArray['PublishedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Pay  Amount"),
                    "OfferedFare" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS'] + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Offered Fare"),
                    "AgentWebPMarkUp" => round_value($AgentWebPMarkUp),
                    "BookingId" => $bookingInfo['id'],
                    "AgentWebPDisplayMarkup" => $AgentWebPDisplayMarkup,
                    "AgentWebPDiscount" => round_value($AgentWebPDiscount),
                );
                $FareBreakUpDataArray[$rtype] = $FareBreakUp;
                $ConfirmationBookingData = array(
                    'Segments' => json_decode($bookingInfo['segments'], true),
                    "FareRule" => json_decode($bookingInfo['fare_rule'], true),
                    "IsRefundable" => $bookingInfo['is_refundable'],
                    "PaymentStatus" => $bookingInfo['payment_status'],
                    "BookingStatus" => $bookingInfo['booking_status'],
                    "BookingRefNumber" => $bookingInfo['booking_ref_number'],
                    "BookingId" => $bookingInfo['id'],
                    "FareType" => $bookingInfo['fare_type'],
                    "TravelersInfo" => json_decode($bookingInfo['travelersInfo'], true),
                );


                $bookingConfrimationData[$rtype] = $ConfirmationBookingData;
                if (isset($_GET['type']) && $_GET['type'] == "Booking") {
                    $smsBookingStatus = $bookingInfo['booking_status'];
                    $smsbookingRefNumber = $bookingInfo['booking_ref_number'];
                    $tempid = $this->SmsTemplate['FlightBookingFailed'];
                    $Smsmessage = "Dear Travel Partner, Your Booking with Id " . $smsbookingRefNumber . " has Processing. In case of any issues, kindly contact the support team ";
                    $sms_type = "Flight Booking";
                    if ($smsBookingStatus == "Confirmed") {
                        $sms_type = "Flight Booking Confirmed";
                        $tempid = $this->SmsTemplate['FlightBookingConfirm'];
                        $Smsmessage = "Dear " . $Name . ",Thanks for booking with " . super_admin_website_setting['company_name'] . ". BookingRef-" . $smsbookingRefNumber . ",Service-" . "Flight,PNR-" . $bookingInfo['pnr'] . ",FltNum-" . $FlightNumber . ",Route-" . $bookingInfo['origin'] . "-" . $bookingInfo['destination'] . "|Trip Date-" . display_custom_date_format($bookingInfo['departure_date']);
                    }
                    if ($smsBookingStatus == "Hold") {
                        $sms_type = "Flight Booking Hold";
                        $tempid = $this->SmsTemplate['FlightBookingHold'];
                        $Smsmessage = "Dear Travel Partner, your Booking with Id " . $smsbookingRefNumber . " is on hold. In case of any issues, kindly contact the support team";
                    }
                    if ($smsBookingStatus == "Confirmed" || $smsBookingStatus == "Hold") {
                        $checkSmssendingStatus = $FlightModel->getData("logs_sms", array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"), "id");
                        if (empty($checkSmssendingStatus)) {
                            /* send_sms($MobileNumber, $Smsmessage, $tempid, $sms_type, array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"));
                             */
                        }
                        $checkEmailsendingStatus = $FlightModel->getData("logs_email", array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"), "id");
                    }
                    if (empty($checkEmailsendingStatus)) {
                        if ($smsBookingStatus == "Confirmed" || $smsBookingStatus == "Hold") {
                            $TicketViewRequest = array(
                                "BookingId" => array($bookingInfo['id']),
                                "SearchTokenId" => $bookingInfo['tts_search_token'],
                                "HtmlType" => "Ticket",
                                "UserType" => "wl-agent-customer",
                                "ViewService" => "Email",
                                "WithPrice" => 1,
                                "WithAgencyDetail" => 1,
                                "TicketInvoiceJourney" => $bookingInfo['trip_indicator'] == 1 ? "Onward" : "Return",
                                "ViewSize" => "",
                                "RequestBy" => "Agent",
                            );
                            $url = $this->Services . 'generate-wl-ticket-invoice';
                            $response = RequestWithoutAuth($TicketViewRequest, $url);
                            $emailMessage = $response['Result']['Html'];
                            $EmailType = $sms_type;
                            send_email($EmailId, $Smsmessage, $emailMessage, $EmailType, null, array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"));
                        }
                    }
                }
            }
            $pnrString = "";
            if (!empty($Pnr)) {
                $pnrString = implode(',', $Pnr);
            }
            $ConfrimationData = array("ConfirmationBookingData" => $bookingConfrimationData, "childCount" => $childCount, "infantCount" => $infantCount, "airlineLogoClass" => $airlineLogoClass, "bookingRefNumber" => $BookingRefNumber, "pnr" => $Pnr, "bookingStatus" => $BookingStatus, "bookingStatusString" => implode(',', $BookingStatus), "bookingRefNumberString" => implode(',', $BookingRefNumber), "pnrString" => $pnrString, "FareBreakUpData" => $FareBreakUpDataArray, "TicketOption" => $TicketOption, "InvoiceOption" => $InvoiceOption, "paymentGatewayInfo" => $paymentGatewayInfo);
        }
        /*  pr($ConfrimationData['bookingRefNumber']);exit; */
        $data = [
            'title' => $this->title,
            'bookingConfrimationData' => $ConfrimationData,
            'view' => "Flight\Views\FlightBookingtemplate\booking-confirmation-page",
        ];
        return view('template/default-layout', $data);
    }

    public function get_airports()
    {

        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);

        $FlightAirportModel = new FlightAirportModel();

        $get_airport = $FlightAirportModel->get_airport_autosuggestion($terms);
        $availableAirport = [];
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirport[] = ['city' => $data['city_name'], 'airport_code' => $data['code'], 'label' => $data['city_name'] . ' (' . $data['code'] . '), ' . ucfirst(strtolower($data['country_name'])) . '', 'airport_name' => $data['name'], 'country_code' => $data['country_code'], 'country_name' => ucfirst(strtolower($data['country_name']))];
            }
        }
        echo json_encode($availableAirport);
    }

    public function get_airline()
    {
        $terms = $this->request->getGet('term');
        $FlightAirlineModel = new FlightAirlineModel();

        $get_airport = $FlightAirlineModel->get_airline_autosuggestion($terms);
        $availableAirline = [];
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
            }
        }
        $availableAirline[] = 'ANY' . '-' . 'Any Airline';
        echo json_encode($availableAirline);
    }

    public function updateWebPartnerMarkupDiscount()
    {
        $MarkupdiscountUpdate_data = $this->request->getPOST();
        if ($MarkupdiscountUpdate_data) {
            $FlightModel = new FlightModel();
            $bookingId = $MarkupdiscountUpdate_data['bookingId'];
            $bookingInfo = $FlightModel->getBookingWithVariableFieldNameData($bookingId, $this->web_partner_id, "id,web_partner_fare_break_up,agent_fare_break_up");
            if ($bookingInfo) {
                $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
                $fareBreakupArray['AgentWebPMarkUp'] = $MarkupdiscountUpdate_data['AgentWebPMarkUp'];
                $fareBreakupArray['AgentWebPDiscount'] = $MarkupdiscountUpdate_data['AgentWebPDiscount'];
                $fareBreakupArray['DisplayMarkup'] = isset($MarkupdiscountUpdate_data['AgentDisplayMarkup']) ? $MarkupdiscountUpdate_data['AgentDisplayMarkup'] : "in_tax";
                $addagentMarkupInTax = 0;
                $addagentMarkupInServiceCharge = 0;
                $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                $AgentWebPDisplayMarkup = isset($fareBreakupArray['DisplayMarkup']) ? $fareBreakupArray['DisplayMarkup'] : "in_tax";
                $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                if (isset($fareBreakupArray['DisplayMarkup']) && $fareBreakupArray['DisplayMarkup'] == 'in_service_charge') {
                    $addagentMarkupInServiceCharge = $AgentWebPMarkUp;
                } else {
                    $addagentMarkupInTax = $AgentWebPMarkUp;
                }
                $FareBreakUp = array();
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax'] + $addagentMarkupInTax), "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge), "LabelText" => "Other & Service Charges"),
                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                        /*  "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
                        "Discount" => array("Value" => round_value($AgentWebPDiscount), "LabelText" => "Discount (-)"),

                    ),
                    "TotalAmount" => array("Value" => round_value($fareBreakupArray['PublishedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Pay  Amount"),
                    "OfferedFare" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS'] + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Offered Fare"),
                    "AgentWebPMarkUp" => round_value($AgentWebPMarkUp),
                    "BookingId" => $bookingInfo['id'],
                    "AgentWebPDisplayMarkup" => $AgentWebPDisplayMarkup,
                    "AgentWebPDiscount" => round_value($AgentWebPDiscount),
                );
                $FlightModel->updateUserData("flight_booking_list", array("id" => $bookingId, "web_partner_id" => $this->web_partner_id), array("agent_fare_break_up" => json_encode($fareBreakupArray)));
                $data = array("StatusCode" => 0, "Message" => "Markup and Discount Successfully Updated.", "Farebreakup" => $FareBreakUp);
            } else {
                $data = array("StatusCode" => 1, "Message" => "Somthing went wrong");
            }
        } else {
            $data = array("StatusCode" => 1, "Message" => "Somthing went wrong");
        }

        return $this->response->setJSON($data);
    }

    function getInvoiceTicket()
    {
        $FlightModel = new FlightModel();
        if (!$this->request->isAJAX()) {
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getTicketUserType = array("PrintTicket" => "wl-agent-customer", "AgencyInvoice" => "wl-agent", "CustomerInvoice" => "wl-agent-customer");
            $getRequestType = array("PrintTicket" => "Agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "Agent");
            $getData = $this->request->getGet();
            $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
            $bookingInfo = array();
            if ($bookingRefNumbers) {
                foreach ($bookingRefNumbers as $BookingRefNumber) {
                    $bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,tts_search_token,trip_indicator");
                    if ($bookingInfoData) {
                        $bookingInfo[] = $bookingInfoData;
                        $bookingInfoId[] = $bookingInfoData['id'];
                        $rtype = $bookingInfoData['trip_indicator'] == 1 ? "Onward" : "Return";
                        $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                    }
                }
                if ($bookingRefNumbers && $bookingInfo) {
                    if ($getData['type'] == "PrintTicket") {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => $getTicketUserType[$getData['type']],
                            "ViewService" => "View",
                            "WithPrice" => isset($getData['price']) ? 1 : 0,
                            "WithAgencyDetail" => isset($getData['agency_detail']) ? 1 : 0,
                            "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : $rtype,
                            "ViewSize" => "",
                            "RequestBy" => isset($getData['agency_detail']) ? 'Agent' : 'WebPartner',
                        );
                    } else {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => $getTicketInvioceType[$getData['type']],
                            "UserType" => $getTicketUserType[$getData['type']],
                            "ViewService" => "View",
                            "WithPrice" => 1,
                            "WithAgencyDetail" => 1,
                            "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : $rtype,
                            "ViewSize" => "",
                            "RequestBy" => $getRequestType[$getData['type']]
                        );
                    }
                    $url = $this->Services . 'generate-wl-ticket-invoice';

                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    //pr($response);exit;
                    $data = [
                        'title' => $this->title,
                        'view' => "Flight\Views\FlightBookingtemplate\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
            }
        } else {
            /* special ajax here */
            $getData = $this->request->getPOST();
            $validate = new Validation();
            $rules = $this->validate($validate->EmailTicketValidation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $getData = $this->request->getPost();
                $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
                $bookingInfo = array();
                if ($bookingRefNumbers) {
                    foreach ($bookingRefNumbers as $BookingRefNumber) {
                        $bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,tts_search_token");
                        if ($bookingInfoData) {
                            $bookingInfo[] = $bookingInfoData;
                            $bookingInfoId[] = $bookingInfoData['id'];
                            $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                        }
                    }
                    if ($bookingRefNumbers && $bookingInfo) {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => "Ticket",
                            "UserType" => "wl-agent-customer",
                            "ViewService" => "Email",
                            "WithPrice" => "1",
                            "WithAgencyDetail" => "1",
                            "TicketInvoiceJourney" => "Both",
                            "ViewSize" => "",
                            "RequestBy" => "Agent",
                        );

                        $url = $this->Services . 'generate-wl-ticket-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);
                        $htmlView = $response['Result']['Html'];
                        $subject = "Flight Ticket";
                        $to = $getData['email'];

                        send_email($to, $subject, $htmlView);
                        $message = array("StatusCode" => 0, "Message" => "Email Successfully Send", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    function bookingLists()
    {
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $list = $FlightBookingModel->search_bookings($getData, $this->web_partner_id, $this->wl_agent_id, $this->booking_source);
        } else {
            $list = $FlightBookingModel->flight_booking_list($this->web_partner_id, $this->wl_agent_id, $this->booking_source);
        }
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/default-layout', $data);
    }

    function bookingDetails()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $FlightBookingModel = new FlightBookingModel();
        $amendmentList = array();
        $BookingDetail = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->wl_agent_id, $this->booking_source);
        if ($BookingDetail) {
            $amendmentList = $FlightBookingModel->amendment_list($this->web_partner_id, $BookingDetail['id']);
            if ($BookingDetail['is_domestic']) {
                $airlineLogoClass = "domAirLogo";
            } else {
                $airlineLogoClass = "intAirLogo";
            }
            $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
            $FareBreakUp = array();
            $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
            $GSTAmount = 0;
            if (isset($fareBreakupArray['GST']['TotalGSTAmount'])) {
                $GSTAmount = $fareBreakupArray['GST']['TotalGSTAmount'];
            }
            $AgentWebPMarkUp = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
            $TotalBaggageCharges = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
            $TotalMealCharges = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
            $AgentWebPDiscount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
            $AgentWebPDisplayMarkup = isset($fareBreakupArray['AgentWebPDisplayMarkup']) ? $fareBreakupArray['AgentWebPDisplayMarkup'] : "in_tax";
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
                    "Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
                    "GST" => array("Value" => round_value($GSTAmount), "LabelText" => "GST"),
                    "MealBaggageCharge" => array("Value" => $TotalBaggageCharges + $TotalMealCharges, "LabelText" => "Meal & Baggage Charges"),
                    "CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Comm Earned (-)"),
                    "TDS" => array("Value" => round_value($fareBreakupArray['TDS']), "LabelText" => "TDS (+)"),
                ),
                "TotalAmount" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS'] + $TotalBaggageCharges + $TotalMealCharges), "LabelText" => "Pay  Amount"),
                "GSTDetails" => ($fareBreakupArray['GST']),
                "AgentWebPMarkUp" => array("Value" => round_value($AgentWebPMarkUp), "LabelText" => "Apply Mark Up"),
                "AgentWebPDiscount" => array("Value" => round_value($AgentWebPDiscount), "LabelText" => "Apply Discount"),
                "AgentWebPDisplayMarkup" => array("Value" => ucfirst(str_replace("_", " ", $AgentWebPDisplayMarkup)), "LabelText" => "Apply Markup At"),
            );




            $BookingDetail['FareBreakUp'] = $FareBreakUp;
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\listing/flight-booking-detail",
                "bookingDetail" => $BookingDetail,
                "amendmentList" => $amendmentList,
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    public function raiseAmendment()
    {
        $requestData = $this->request->getPOST();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendment);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->getBookingWithVariableFieldNameData($bookingReferenceNumber, $this->web_partner_id, "booking_ref_number");
                if ($bookingReferenceNumber && $BookingDetail) {
                    $query_string = http_build_query($requestData);
                    $redirect_url = site_url('flight/amendment-itinerary?' . $query_string);
                    $message = array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $redirect_url);
                    return $this->response->setJSON($message);
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }

    public function amendmentItinerary()
    {
        $requestData = $this->request->getGET();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendment);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $errors = implode(",", array_values($errors));
                return $this->response->redirect(site_url('flight/error?errormessage=' . $errors));
            } else {
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($this->web_partner_id, $bookingReferenceNumber);
                if ($bookingReferenceNumber && $BookingDetail) {
                    if ($BookingDetail['is_domestic']) {
                        $airlineLogoClass = "domAirLogo";
                    } else {
                        $airlineLogoClass = "intAirLogo";
                    }
                    $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
                    $data = [
                        'title' => $this->title,
                        'view' => "Flight\Views\listing/flight-amendment-itinerary",
                        "bookingDetail" => $BookingDetail,
                        "requestData" => $requestData,
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    public function raiseAmendmentType()
    {
        $requestData = $this->request->getPOST();
        $errors = array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendmentType);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                if (isset($errors['passengers.*'])) {
                    $errors['passengers[]'] = $errors['passengers.*'];
                }
                unset($errors['passengers.*']);
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $bookingReferenceNumber = $requestData['booking_ref_number'];
                $FlightBookingModel = new FlightBookingModel();
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($this->web_partner_id, $bookingReferenceNumber);

                if ($bookingReferenceNumber && $BookingDetail) {
                    $AmendmentStatus = "";
                    $sectors = array();
                    $PaxIds = array();
                    foreach ($requestData['passengers'] as $passengerId) {
                        $paxId = dev_decode($passengerId);
                        array_push($PaxIds, $paxId);
                    }


                    $tripInfo = json_decode($BookingDetail['segments'], true);
                    foreach ($tripInfo as $key => $trips) {
                        foreach ($trips as $segmentIndicatorkey => $segment) {
                            $sector = array(
                                "Origin" => $segment['Origin']['CityCode'],
                                "Destination" => $segment['Destination']['CityCode'],
                            );
                            array_push($sectors, $sector);
                        }
                    }
                    /*   if ($requestData['amendment_type'] == "cancellation") {

                          $request = array(
                              "UserIp" => $this->request->getIpAddress(),
                              "BookingId" => $BookingDetail['id'],
                              "SearchTokenId" => $BookingDetail['tts_search_token'],
                              "RequestType" => "PartialCancellation",
                              "Remark" => $requestData['remark'],
                              "Sectors" => $sectors,
                              "PaxId" => $PaxIds,
                          );
                          $service = "cancelrequest";
                          $url = $this->Services . $service;
                          $response = Request($request, $url, $service);
                          if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
                              $AmendmentStatus = "approved";
                          } else {
                              $AmendmentStatus = "rejected";
                          }
                      }
   */
                    $request = array(
                        "BookingId" => $BookingDetail['id'],
                        "Type" => $requestData['amendment_type'],
                        "Remarks" => $requestData['remark'],
                        "RequesterInfo" => array("RequesterId" => $this->wl_agent_staff_id, "Requester" => "Whitelabel", "wl_agent_id" => $this->wl_agent_id),
                        "Sectors" => $sectors,
                        "PaxId" => $PaxIds,
                    );


                    if ($AmendmentStatus != "") {
                        $request['AmendmentStatus'] = $AmendmentStatus;
                    }
                    $service = "submitamendment";
                    $url = $this->Services . $service;


                    $response = Request($request, $url, $service);


                    $EmailType = 'Amendment';
                    $EmailId = $this->wl_agent_details['login_email'];
                    if ($EmailId) {

                        $data['paxs'] = $FlightBookingModel->pax_details($PaxIds);

                        $Smsmessage = "Amendment request successfully submitted";
                        $data['remark'] = $requestData['remark'];
                        $data['message'] = $Smsmessage;

                        $data['type'] = $requestData['amendment_type'];
                        $message = view('Views/emails/amendment-emails', $data);

                        $param1 = 'ticketing';

                        send_email($EmailId, $Smsmessage, $message, $EmailType, $attachment = null, $extraprameter = null, $param1);


                    }

                    if ($response['Error']['ErrorCode'] == 0) {
                        $message = array("StatusCode" => 0, "Message" => "Amendment has  Successfully Submitted.", 'Class' => 'success_popup', "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $response['Error']['ErrorMessage'], "Class" => "error_popup", "Reload" => "true");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            }
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    public function amendmentDetails()
    {
        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $FlightBookingModel = new FlightBookingModel();
        $BookingDetail = $FlightBookingModel->flight_amendment_detail($this->web_partner_id, $amendmentId);
        if ($amendmentId && $BookingDetail) {
            if ($BookingDetail['is_domestic']) {
                $airlineLogoClass = "domAirLogo";
            } else {
                $airlineLogoClass = "intAirLogo";
            }
            $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\listing/flight-amendment-detail",
                "bookingDetail" => $BookingDetail,
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    function getCreditNote()
    {
        $FlightModel = new FlightModel();
        if (!$this->request->isAJAX()) {
            $getTicketInvioceType = array("CreditNote" => "CreditNote");
            $getData = $this->request->getGet();
            $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
            $traveller_ref_number = $getData['traveller_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumbers) {
                foreach ($bookingRefNumbers as $BookingRefNumber) {
                    $bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,tts_search_token,trip_indicator");
                    if ($bookingInfoData) {
                        $rtype = $bookingInfoData['trip_indicator'] == 1 ? "Onward" : "Return";
                        $bookingInfo[] = $bookingInfoData;
                        $bookingInfoId[] = $bookingInfoData['id'];
                        $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                    }
                }
                if ($bookingRefNumbers && $bookingInfo) {

                    $TicketViewRequest = array(
                        'traveller_ref_number' => $traveller_ref_number,
                        "BookingId" => $bookingInfoId,
                        "SearchTokenId" => $tts_search_token,
                        "HtmlType" => $getTicketInvioceType[$getData['type']],
                        "UserType" => "WebPartner",
                        "ViewService" => "View",
                        "WithPrice" => 1,
                        "WithAgencyDetail" => 1,
                        "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : $rtype,
                        "ViewSize" => "",
                    );

                    $url = $this->Services . 'generate-credit-note';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Flight\Views\FlightBookingtemplate\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
            }
        }
    }

    public function credit_notes()
    {
        $CreditNotesModel = new CreditNotesModel();
        if ($this->request->getGet()) {
            $credit_notes = $CreditNotesModel->credit_notes_search($this->wl_agent_id, $this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $CreditNotesModel->credit_notes($this->wl_agent_id, $this->web_partner_id, );
        }
        $pager = $CreditNotesModel->pager;
        $data = [
            'title' => $this->title,
            'account_logs' => $credit_notes,
            'view' => "Flight\Views\credit-notes-list",
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,
            'service' => 'Flight'
        ];
        return view('template/default-layout', $data);
    }

    function flightRefundLists()
    {
        $FlightAmendmentModel = new FlightAmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $list = $FlightAmendmentModel->search_flight_refund_list($this->web_partner_id, $this->wl_agent_id, $getData);
        } else {
            $source = $this->request->getGET('source');
            if ($source == 'dashboard') {
                $source = 'dashboard';
            }
            $bookingType = 'all';
            $list = $FlightAmendmentModel->flight_refund_list($this->web_partner_id, $this->wl_agent_id, $bookingType, $source);

        }


        $data = [
            'title' => $this->title,
            'view' => "Flight\Views/flightRefund/flight-refund-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $FlightAmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }

    function flightAmendmentLists()
    {
        $FlightAmendmentModel = new FlightAmendmentModel();
        $getData = $this->request->getGET();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $FlightAmendmentModel->search_data($this->web_partner_id, $this->wl_agent_id, $getData);
        } else {
            $list = $FlightAmendmentModel->flight_amendment_list($this->web_partner_id, $this->wl_agent_id);
        }
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views/flightAmendment/flight-amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $FlightAmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }



    function shareItinerary()
    {
        $data = $this->request->getJSON();

        $emailMessage = "";
        $searchSegement = $data->searchSegment;
        $totalPax = $data->totalPax;
        $finalselectitinerary = $data->seldata;
        $markupvalue = $data->markupvalue;
        $emailMessage .= "Your Flight Itinerary for {$searchSegement}  Air Travel for {$totalPax} Traveller : <br><br>";
        foreach ($finalselectitinerary as $key => $flightitem) {
            $isRefundable = 'Non Refundable';
            $flightprice = floatval($flightitem->selectedFare->Fare->PublishedPrice) + floatval($markupvalue);
            $flight = $key + 1;
            $DepartureAirport = $flightitem->Segments[0][0]->Origin->AirportName;
            $ArrivalAirport = $flightitem->Segments[0][0]->Destination->AirportName;
            foreach ($flightitem->MainSegment as $subkey => $value) {
                $emailMessage .= "<b>{$flight}. {$value->AirlineName} : {$value->Airlinecode} {$value->AirlineFlightNumber[0]}</b> : <br><br>"
                    . "<b>Departure:*</b> {$value->DepartureCity}({$DepartureAirport}) <br>"
                    . "<b>Time:</b> {$value->DepartDate} {$value->DepartTime} <br><br>"
                    . "<b>Arrival:*</b> {$value->ArrivalCity}({$ArrivalAirport}) <br>"
                    . "<b>Time:</b> {$value->ArrivalDate} {$value->ArrivalTime} <br><br>"
                    . "<b>Duration:</b> {$value->Duration}<br>";

                if ($flightitem->selectedFare->IsRefundable == true) {
                    $isRefundable = "Refundable";
                }
                $emailMessage .= "{$isRefundable} <br><br>";
                $emailMessage .= "<b>*Baggage:</b> {$flightitem->selectedFare->SeatBaggage[$subkey][0]->CheckIn} <br><br><br>";
            }
            $emailMessage .= "<b style='color:red';>Total Price: ₹{$flightprice}</b> <br><br><hr><br><br>";
        }
        $emailMessage .= "Please confirm the same at the earliest. :<br><br>Contact : {$this->web_partner_details['support_no']} <br>Email : {$this->web_partner_details['support_email']} <br><br>";
        $emailMessage .= "Thanks <br>{$this->web_partner_details['company_name']} <br><br>";
        $emailMessage .= "<b>*Note:*</b> Flight ticket pricing is dynamic. Fares are valid as of now and might change at the time of issuance.";

        $emailId = $data->inputdata->emailid;
        $subject = 'Flight Itinerary Result';

        try {
            send_email($emailId, $subject, $emailMessage, $attachment = null, $extraprameter = null, $param1 = null);
            $message = array("StatusCode" => 0, "Message" => "Email Successfully Sent to {$emailId}", "Class" => "success_popup", "Reload" => "false");
        } catch (Exception $e) {
            // If an exception occurs during the function execution, handle the exception
            echo 'Error sending email: ' . $e->getMessage();
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }





}