<?php



namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightTicketUploadModel;
use App\Models\CommonModel;

use App\Controllers\BaseController;

use Modules\Flight\Config\TicketUploadValidation;

use CodeIgniter\I18n\Time;



class FlightTicketUpload extends BaseController
{

  public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
  {
    parent::initController($request, $response, $logger);

    $this->title = "Flight Ticket Upload";

    $this->UpdateTicketTitle = "Flight Ticket Update";

    helper('Modules\Flight\Helpers\flight_upload_helper');

    helper('Modules\Flight\Helpers\flight_helper');
    $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
    $this->web_partner_details = admin_cookie_data()['admin_user_details'];
    $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
    $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    $this->IrNGenerateURl  =  API_REQUEST_URL . 'einvoice/rest/generate-irn';
  }



  public function ticket_upload()

  {

    $segmentIndicator = 0;

    $TripIndicator = 1;

    $segmentview = "";

    $tripsegmentInfo =  array();

    $tripsegmentInfoData =  array();

    $tripSegmentInfoId =  "";

    $ticketType  =  "UploadTicket";

    $FlightTicketUploadModel  =  new FlightTicketUploadModel();

    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {

      $input  =  $this->request->getGET();

      $tripsegmentInfoData   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);

      $tripsegmentInfo =  json_decode($tripsegmentInfoData['segment_temp_data'], true);

      $ticketType   =  $tripsegmentInfoData['ticket_type'];

      unset($tripsegmentInfoData['segment_temp_data']);

      $tripSegmentInfoId  = $_GET['segmentinfokey'];
    }

    if (empty($tripsegmentInfo)) {

      $segments['segmentIndicator'] = $segmentIndicator;

      $segments['TripIndicator'] = $TripIndicator;

      $segmentview = view('Modules\Flight\Views\FlightTicketUpload\segment-details', $segments);
    }

    $flightSupplier  =  $FlightTicketUploadModel->getData("offline_provider", array('flight_service' => 'active'), $singalRecord = 0, $whereApply  =  1, 'id,supplier_name');

    $tripView  =  view('Modules\Flight\Views\FlightTicketUpload\add-more-trip', array("segmentview" => $segmentview, "TripIndicator" => $TripIndicator, "segmentIndicator" => $segmentIndicator, 'tripsegmentInfo' => $tripsegmentInfo, 'tripSegmentInfoId' => $tripSegmentInfoId, 'flightSupplier' => $flightSupplier, 'tripsegmentInfoData' => $tripsegmentInfoData, 'ticketType' => $ticketType));

    $data = [

      'title' => $this->title,

      'tripView' => $tripView,

      'TripIndicator' => $TripIndicator,

      'view' => "Flight\Views\FlightTicketUpload\Ticket-upload",

    ];



    return view('template/sidebar-layout', $data);
  }



  public function segment_details()

  {

    $data = $this->request->getPost();

    $segmentIndicator = $data['segmentIndicator'] + 1;

    $segments['segmentIndicator'] = $segmentIndicator;

    $TripIndicator = $data['tripIndicator'];

    $segments['TripIndicator'] = $TripIndicator;

    $segmentview = view('Modules\Flight\Views\FlightTicketUpload\segment-details', $segments);

    $data = array("TripIndicator" => $TripIndicator, "segmentView" => $segmentview, "SegmentIndicator" => $segmentIndicator);

    return $this->response->setJSON($data);
  }

  public function addTripDetails()

  {

    $data = $this->request->getPost();

    $segmentIndicator = 0;

    $segments['segmentIndicator'] = $segmentIndicator;

    $TripIndicator = $data['trip_indicator'] + 1;

    $segments['TripIndicator'] = $TripIndicator;

    $segmentview = view('Modules\Flight\Views\FlightTicketUpload\segment-details', $segments);

    $tripView  =  view('Modules\Flight\Views\FlightTicketUpload\add-more-trip', array("segmentview" => $segmentview, "TripIndicator" => $TripIndicator, "segmentIndicator" => $segmentIndicator));

    $data = array("TripIndicator" => $TripIndicator, "TripView" => $tripView);

    return $this->response->setJSON($data);
  }



  public function passenger_details()

  {

    $passengerCounter = $this->request->getPost('passenger_counter') + 1;

    $segmentinfokey  =   $this->request->getPost('temptripSegmentId');

    $pax_type  =   $this->request->getPost('pax_type');

    $pax_type_count_value  =   $this->request->getPost('pax_type_count_value');

    $passenger['passengerCounter'] = $passengerCounter;

    $passenger['pax_type'] = $pax_type;

    $paxTypeCount = 0;

    if ($pax_type == "Child") {

      $paxTypeCount =  $pax_type_count_value + 1;
    }

    if ($pax_type == "Infant") {

      $paxTypeCount =  $pax_type_count_value + 1;
    }

    $FlightTicketUploadModel  =  new FlightTicketUploadModel();

    $segmentInfo   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $segmentinfokey, 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);

    if ($segmentInfo) {

      $passenger['ssrtripsegmentInfo'] =   json_decode($segmentInfo['ssr_info'], true);
    }

    $passengerView  = view("Modules\Flight\Views\FlightTicketUpload\passenger-details", $passenger);

    $passengerPricingView  = view("Modules\Flight\Views\FlightTicketUpload/ticket-upload-pax-pricing", $passenger);

    $data = array("passengerCounter" => $passengerCounter, "passengerView" => $passengerView, "pax_type" => $pax_type, "passengerPricingView" => $passengerPricingView, "paxTypeCount" => $paxTypeCount);

    return $this->response->setJSON($data);
  }





  public function storeSegementInfo()
  {
    $data = $this->request->getPost();
    $validate = new TicketUploadValidation();
    $validationConfigArray = $validate->segmentsinfo_validation($data);
    $this->validation->setRules($validationConfigArray);
    $rules = $this->validation->run($data);
    if (!$rules) {
      $errors = $this->validation->getErrors();
      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
      return $this->response->setJSON($data_validation);
    } else {
      $countryCode =  array();
      $FlightTicketUploadModel  =  new FlightTicketUploadModel();
      $orginDestinationAirports  =  array();
      $bookingid  =  null;
      if (isset($_GET['bookingId']) && $_GET['bookingId'] != "") {
        $bookingid =  $_GET['bookingId'];
      }
      foreach ($data['segmentinfo'] as $tripkey => $tripinfo) {
        $orginDestinationAirports =  array_merge($orginDestinationAirports,  array_column($tripinfo, "origin_airport_code"));
        $orginDestinationAirports =     array_merge($orginDestinationAirports,  array_column($tripinfo, "destination_airport_code"));
        $firstSegment  =  reset($tripinfo);
        $lastSegment  =  end($tripinfo);
        $AirSegments[($tripkey - 1)]['Origin'] =  trim($firstSegment['origin_airport_code']);
        $AirSegments[($tripkey - 1)]['Destination'] =  trim($lastSegment['destination_airport_code']);
        $AirSegments[($tripkey - 1)]['PreferredTime'] =  date("Y-m-d", strtotime($firstSegment['depart_date'])) . "T00:00:00";
      }
      $orginDestinationAirports =  array_unique($orginDestinationAirports);
      $orginDestinationAirportDetail  =  $FlightTicketUploadModel->selected_airport_detail($orginDestinationAirports);
      $CustomTripData =  array();
      $ssrSgmentData  =  array();
      $gdsPnr  =  array();
      $tripIndicator = 0;
      foreach ($data['segmentinfo'] as $tripkey => $tripinfo) {
        $segmentIndicator = 0;
        $CustomSegmenttripData =  array();
        $originAirportCode =  array();
        $destinationAirportCode =  array();
        $origindestinationAirportCode =  array();
        $originAirportCode  =  array_column($tripinfo, 'origin_airport_code');
        $destinationAirportCode  =  array_column($tripinfo, 'destination_airport_code');
        $origindestinationAirportCode =  array_merge($originAirportCode, $destinationAirportCode);
        $originDestinationTimeZone  = $FlightTicketUploadModel->getAirportCodeTimeZoneIn($origindestinationAirportCode);
        foreach ($tripinfo as $segmentkey => $segmentData) {
          $origintimezone = isset($originDestinationTimeZone[$segmentData['origin_airport_code']]) ? $originDestinationTimeZone[$segmentData['origin_airport_code']] : "";
          $destinationtimezone = isset($originDestinationTimeZone[$segmentData['destination_airport_code']]) ? $originDestinationTimeZone[$segmentData['destination_airport_code']] : "";
          $OriginAirportInfoIndex =  array_search($segmentData['origin_airport_code'], array_column($orginDestinationAirportDetail, 'code'));
          $OriginAirportInfo = $orginDestinationAirportDetail[$OriginAirportInfoIndex];
          $DestinationAirportInfoIndex = array_search($segmentData['destination_airport_code'], array_column($orginDestinationAirportDetail, 'code'));
          $DestinationAirportInfo = $orginDestinationAirportDetail[$DestinationAirportInfoIndex];
          $ssrSgmentData[$tripIndicator][$segmentIndicator]['orgin']  = $segmentData['origin_airport_code'];
          $ssrSgmentData[$tripIndicator][$segmentIndicator]['destination']  = $segmentData['destination_airport_code'];
          array_push($countryCode, $OriginAirportInfo['country_code']);
          array_push($countryCode, $DestinationAirportInfo['country_code']);
          $Duration  =  journeyTimeTicketUpload($origintimezone, $destinationtimezone, str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['depart_date'] . " " . $segmentData['depart_time']))), str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['arrival_date'] . " " . $segmentData['arrival_time']))));
          if (isset($segmentData['airline_pnr'])) {
            $gdsPnr[$tripIndicator + 1][$segmentIndicator + 1] = $segmentData['airline_pnr'];
          }
          $segemntInfo  =  array(
            "TripIndicator" => ($tripIndicator + 1),
            "SegmentIndicator" => ($segmentIndicator + 1),
            "CheckInBaggage" => $segmentData['baggage'],
            "CabinBaggage" => $segmentData['cabin_baggage'],
            "CabinClass" => $data['cabin_class'],
            "Duration" => $Duration,
            "TechStopPoint" => [],
            "Craft" => $segmentData['cabin_baggage'],
            "AirlinePNR" => $segmentData['airline_pnr'],
            "Airline" => array(
              "AirlineCode" => explode("-", $segmentData['airline_code'])[0],
              "AirlineName" =>  explode("-", $segmentData['airline_code'])[1],
              "FlightNumber" => $segmentData['flight_number'],
              "FareClass" => $segmentData['fare_class'],
              "FareBasisCode" => $segmentData['fare_basis'],
              "OperatingCarrier" => ""
            ),
            "Origin" => array(
              "AirportCode" => $OriginAirportInfo['code'],
              "AirportName" => $OriginAirportInfo['name'],
              "CityCode" => $OriginAirportInfo['city_code'],
              "CityName" => $OriginAirportInfo['city_name'],
              "CountryCode" => $OriginAirportInfo['country_code'],
              "CountryName" => $OriginAirportInfo['country_name'],
              "Terminal" => $segmentData['origin_terminal'],
              "DepartTime" => str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['depart_date'] . " " . $segmentData['depart_time'])))
            ),
            "Destination" => array(
              "AirportCode" => $DestinationAirportInfo['code'],
              "AirportName" => $DestinationAirportInfo['name'],
              "CityCode" => $DestinationAirportInfo['city_code'],
              "CityName" => $DestinationAirportInfo['city_name'],
              "CountryCode" => $DestinationAirportInfo['country_code'],
              "CountryName" => $DestinationAirportInfo['country_name'],
              "Terminal" => $segmentData['destination_terminal'],
              "ArrivalTime" => str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['arrival_date'] . " " . $segmentData['arrival_time'])))
            )
          );
          $CustomSegmenttripData[$segmentIndicator] =   $segemntInfo;
          $segmentIndicator = ($segmentIndicator + 1);
        }
        $CustomTripData[$tripIndicator] =   $CustomSegmenttripData;
        $tripIndicator =  ($tripIndicator + 1);
      }
    }
    $developerData  =  array(
      "AirSegments" => $AirSegments,
      "JourneyType" => get_journey_type(count($data['segmentinfo'])),
      "IsDomestic" => check_domestic_type($countryCode),
      "gdsPnr" => $gdsPnr,
    );
    if (!isset($data['temptripSegmentId'])) {
      $insertData  =  array(
        "bussiness_type" => $data['bussiness_type'],
        "service" => "flight",
        "booking_id" => $bookingid,
        "issue_by_supplier" => $data['supplier'],
        /*  "issuer_remark"=>$data['issuer_remark'], */
        "cabin_class" => $data['cabin_class'],
        "is_refundable" => $data['is_refundable'],
        // "for_issued" => $data['tts_web_partner_info_id'],
        // "for_issued_info" => $data['tts_web_partner_info'],
        "airline_remark" => $data['airline_remark'],
        "ticket_type" => $data['ticket_type'],
        "segment_temp_data" => json_encode($CustomTripData),
        "ssr_info" => json_encode($ssrSgmentData),
        "developer_booking_data" => json_encode($developerData),
        "created" => create_date(),
      );
      if ($data['bussiness_type'] == "B2B") {
        $insertData['for_issued_short_info'] = $data['agent_info'];
        $insertData['for_issued'] = $data['tts_agent_info_id'];
        $insertData['for_issued_info'] = $data['tts_agent_info'];
      }
      if ($data['bussiness_type'] == "B2C") {
        $insertData['for_issued_short_info'] = $data['customer_info'];
        $insertData['for_issued'] = $data['tts_customer_info_id'];
        $insertData['for_issued_info'] = $data['tts_customer_info'];
      }
      $insertId  =  $FlightTicketUploadModel->insertData("flight_ticket_upload_data_temp", $insertData);
    } else {
      $insertId = $data['temptripSegmentId'];
      $updateData  =  array(
        "web_partner_id" => $this->web_partner_id,
        "bussiness_type" => $data['bussiness_type'],
        "issue_by_supplier" => $data['supplier'],
        /*  "issuer_remark"=>$data['issuer_remark'], */
        "cabin_class" => $data['cabin_class'],
        "is_refundable" => $data['is_refundable'],
        "airline_remark" => $data['airline_remark'],
        "segment_temp_data" => json_encode($CustomTripData),
        "ssr_info" => json_encode($ssrSgmentData),
        "developer_booking_data" => json_encode($developerData),
        "modified" => create_date(),
      );
      if ($data['bussiness_type'] == "B2B") {
        $insertData['for_issued_short_info'] = $data['agent_info'];
        $insertData['for_issued'] = $data['tts_agent_info_id'];
        $insertData['for_issued_info'] = $data['tts_agent_info'];
      }
      if ($data['bussiness_type'] == "B2C") {
        $insertData['for_issued_short_info'] = $data['customer_info'];
        $insertData['for_issued'] = $data['tts_customer_info_id'];
        $insertData['for_issued_info'] = $data['tts_customer_info'];
      }
      $FlightTicketUploadModel->updateData("flight_ticket_upload_data_temp", array("id" => $data['temptripSegmentId']), $updateData);
    }
    $RedirectUrl  =  site_url('flight-ticket-upload/segment-passenger-detail?segmentinfokey=' . $insertId);
    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
    return $this->response->setJSON($data_validation);
  }

  public function savePassenger_pre()

  {

    $input = $this->request->getPost();

    $validate = new TicketUploadValidation();

    $validationConfigArray = $validate->pax_validation($input);

    $this->validation->setRules($validationConfigArray);

    $rules = $this->validation->run($input);

    if (!$rules) {

      $errors = $this->validation->getErrors();

      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

      return $this->response->setJSON($data_validation);
    } else {

      $FlightTicketUploadModel  =  new FlightTicketUploadModel();

      $segmentinfokey = $input['temptripSegmentId'];

      $updateData  =  array(

        "passenger_detail" => json_encode($input['pax']),

        "passenger_pricing" => json_encode($input['pricing']),
        "deal_info" => json_encode($input['deal']),

        "modified" => create_date(),

      );

      $FlightTicketUploadModel->updateData("flight_ticket_upload_data_temp", array("id" => $input['temptripSegmentId']), $updateData);

      $bookingStoreData   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $segmentinfokey, 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);

      if ($bookingStoreData) {

        if ($bookingStoreData['ticket_type'] == "UploadTicket") {

          return $response =   FlightTicketUpload::generateUploadTicket($bookingStoreData);
        }
      } else {

        $message = array("StatusCode" => 2, "Message" => "Technical problem occurred", "Class" => "error_popup");

        $this->session->setFlashdata('Message', $message);

        $RedirectUrl  =  site_url('flight-ticket-upload');

        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

        return $this->response->setJSON($data_validation);
      }
    }
  }



  function generateUploadTicket()
  {
    $input  =  $this->request->getGET();
    if (isset($input['segmentinfokey']) && $input['segmentinfokey'] != "") {
      $FlightTicketUploadModel  =  new FlightTicketUploadModel();
      $bookingStoreData   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight", 'ticket_type' => "UploadTicket"), $singalRecord = 1, $whereApply  =  1);
      $developer_booking_data   =  json_decode($bookingStoreData['developer_booking_data'], true);

      $segment_temp_data   =  json_decode($bookingStoreData['segment_temp_data'], true);

      $passenger_detail   =  json_decode($bookingStoreData['passenger_detail'], true);

      $passenger_pricing   =  json_decode($bookingStoreData['passenger_pricing'], true);
      $DealInfo   =  json_decode($bookingStoreData['deal_info'], true);

      $gst_info = json_encode(array(

        'name' => '',

        'number' => '',

        'phone' => '',

        'email' => '',

        'address' => ''

      ));

      $FlightTicketUploadModel  =  new FlightTicketUploadModel();

      $super_admin_gst_state_code =  $FlightTicketUploadModel->getData('web_partner', array('id' => $this->web_partner_id), $singalRecord = 1, $whereApply  =  0, 'gst_state_code')['gst_state_code'];
      if ($bookingStoreData['bussiness_type'] == "B2B") {
        $AccountTableName = "agent_account_log";
        $account_log_id = $bookingStoreData['for_issued'];
        $key = "wl_agent_id";
        $segmentInfo['UserInfo'] = $FlightTicketUploadModel->getData('agent', array("id" => $bookingStoreData['for_issued']), $singalRecord = 1, $whereApply  =  1, 'company_name,company_id,gst_state_code');
        $forIssuedContactDetail  = $FlightTicketUploadModel->getData('agent_users', array("agent_id" => $bookingStoreData['for_issued'], 'primary_user' => 1), $singalRecord = 1, $whereApply  =  1, 'id,login_email,first_name,last_name,mobile_no,street,city,state,country,pin_code');
        $forIssuedContactDetail['gst'] = $segmentInfo['UserInfo'];
        $forIssuedContactDetail['role'] = "agent";
        $segmentInfo['UserInfo']['role'] = "agent";
      } else {
        $AccountTableName = "customer_account_log";
        $account_log_id = $bookingStoreData['for_issued'];
        $key = "customer_id";
        $segmentInfo['UserInfo']['gst_state_code'] = "";
        $forIssuedContactDetail  = $FlightTicketUploadModel->getData('customer', array("id" => $bookingStoreData['for_issued']), $singalRecord = 1, $whereApply  =  1, 'id,email_id,first_name,last_name,mobile_no,address,city,state,country,pin_code');
        $forIssuedContactDetail['gst'] = $segmentInfo['UserInfo'];
        $segmentInfo['UserInfo'] = $forIssuedContactDetail;
        $segmentInfo['UserInfo']['gst_state_code'] = "";
        $segmentInfo['UserInfo']['role'] = "customer";
        $forIssuedContactDetail['role'] = "customer";
      }
      $forIssuedContactDetail['webpgstCode'] = $super_admin_gst_state_code;
      /* web partner primary user Detail  */

      $PassengerFareInformation  = generatePaxData($passenger_detail, $passenger_pricing, $forIssuedContactDetail);
      /* web partner primary user Detail  */
      $FareBreakUp  =  $PassengerFareInformation['WebPartnerfareBreakup'];
      $totalpax  =   $PassengerFareInformation['NoofAdult'] + $PassengerFareInformation['NoofChild'];
      $flightFare  = get_flight_fare_upload_ticket($FareBreakUp, $DealInfo, $segmentInfo['UserInfo'], $super_admin_gst_state_code, $passenger_detail, $totalpax);
      $PassengerFareInformation  = generatePaxData($passenger_detail, $passenger_pricing, $forIssuedContactDetail, array("flightFare" => $flightFare));
      $PassengerInformation =  $PassengerFareInformation['paxData'];

      $SuperAdminfareBreakup =  $flightFare['SuperAdminFareBreakup'];

      $WebPartnerfareBreakup =  $flightFare['WebPartnerFareBreakup'];

      if ($bookingStoreData['bussiness_type'] == "B2B") {
        $AgentfareBreakup = json_encode($flightFare['AgentFareBreakup']);
        $CustomerfareBreakup = NULL;
        $fareBreakup = $flightFare['AgentFareBreakup'];
        $bookingSource = "Wl_b2b";
      } else {
        $AgentfareBreakup = NULL;
        $CustomerfareBreakup = json_encode($flightFare['CustomerFareBreakup']);
        $fareBreakup = $flightFare['CustomerFareBreakup'];
        $bookingSource = "Wl_b2c";
      }


      $firstSegment  =  reset($developer_booking_data['AirSegments']);

      $lastSegment  =  end($developer_booking_data['AirSegments']);

      $origin =  $firstSegment['Origin'];

      $destination =  $lastSegment['Destination'];

      $departure_date =  explode("T", $firstSegment['PreferredTime'])[0];

      $airline_code =   $segment_temp_data[0][0]['Airline']['AirlineCode'];

      $ValidatingAirline =   $segment_temp_data[0][0]['Airline']['AirlineCode'];

      $searchRequest =  array(
        "UserIp" => $this->request->getIPAddress(),
        "Adult" => $PassengerFareInformation['NoofAdult'],
        "Child" => $PassengerFareInformation['NoofChild'],
        "Infant" => $PassengerFareInformation['NoofInfant'],
        "DirectFlight" => false,
        "JourneyType" => get_api_journey_type($developer_booking_data['JourneyType']),
        "PreferredCarriers" => null,
        "CabinClass" => get_api_cabinclass($bookingStoreData['cabin_class']),
        "AirSegments" => $developer_booking_data['AirSegments'],
        "Sources" => null,
        "IsDomestic" => $developer_booking_data['IsDomestic'] == 1 ? true : false
      );
      $pnr  = $PassengerFareInformation['PNR'];
      $booking_status =  "Processing";
      $payment_status =  "Processing";
      if ($pnr != "") {
        $booking_status =  "Confirmed";
      }
      $IsLCC =  1;
      $leadpaxArray = current($PassengerInformation);
      $leadPax = $leadpaxArray['title'] . ' ' . $leadpaxArray['first_name'] . ' ' . $leadpaxArray['last_name'];
      $totalPrice =  round_value($fareBreakup['OfferedPrice'] + $fareBreakup['TDS']);
      $webptotalPrice = round_value($WebPartnerfareBreakup['OfferedPrice'] + $WebPartnerfareBreakup['TDS']);
      $superAdminStaffDetail  =  admin_cookie_data()['admin_user_details'];
      $savePaxinfo = array(
        'tts_search_token' => generate_token_ticket_upload(),
        'web_partner_id' => $this->web_partner_id,
        'is_price_changed' => 0,
        'is_time_changed' => 0,
        'trip_indicator' => 1,
        'search_request' => json_encode($searchRequest),
        'journey_type' =>   $developer_booking_data['JourneyType'],
        'origin' => $origin,
        'pnr' => $pnr,
        'airline_pnr' => json_encode($developer_booking_data['gdsPnr']),
        'resultIndex' => null,
        'destination' => $destination,
        'departure_date' => $departure_date,
        'is_domestic' => $developer_booking_data['IsDomestic'],
        'is_lcc' => $IsLCC,
        'is_refundable' => $bookingStoreData['is_refundable'],
        'fare_type' => "Publish",
        'airline_code' => $airline_code,
        'validating_airline_code' => $ValidatingAirline,
        'last_ticket_date' => "",
        'airline_remark' => $bookingStoreData['airline_remark'],
        'segments' => json_encode($segment_temp_data),
        'api_supplier' => explode("#", $bookingStoreData['issue_by_supplier'])[1],
        'issue_supplier' => explode("#", $bookingStoreData['issue_by_supplier'])[1],
        'payment_mode' => 'Wallet',
        "webpartner_update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),
        'is_manual' => 1,
        'book_request' => "requested",
        'payment_status' => $payment_status,
        'booking_status' => $booking_status,
        'gst_info' =>  $gst_info,
        'agent_staff_id' =>  $this->user_id,
        'super_admin_fare_break_up' => json_encode($SuperAdminfareBreakup),
        'web_partner_fare_break_up' => json_encode($WebPartnerfareBreakup),
        'agent_fare_break_up' => $AgentfareBreakup,
        'customer_fare_break_up' => $CustomerfareBreakup,
        'booking_channel' => 'UploadTicket',
        'total_price' => $totalPrice,
        'lead_pax' => $leadPax,
        'booking_source' => $bookingSource,
        'web_partner_booking_total_price' => $webptotalPrice,
        'web_partner_payment_status' => 'Successful',
        'offline_supplier_id' => explode("#", $bookingStoreData['issue_by_supplier'])[0],
        'is_gst_mandatory' => 0,
        'is_gst_allowed' => 0,
        'created' => create_date(),
        'booking_date_time' => create_date()
      );
      if ($bookingStoreData['bussiness_type'] == "B2B") {
        $savePaxinfo["wl_agent_id"] = $account_log_id;
      } else {
        $savePaxinfo["wl_customer_id"] = $account_log_id;
      }
      $webPartnerBalanceInfo  = $FlightTicketUploadModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $this->web_partner_id);
      $webPartnerBalance = 0;
      if (isset($webPartnerBalanceInfo['balance'])) {
        $webPartnerBalance = round_value($webPartnerBalanceInfo['balance']);
      }
      if ($webPartnerBalance >= $totalPrice) {
        $flight_booking_id =  $FlightTicketUploadModel->insertData('flight_booking_list', $savePaxinfo);
        $InsertDatapassengerInfo = array_map(

          function ($value, $flightBookingId) {

            $value['flight_booking_id'] = $flightBookingId;

            return $value;
          },

          $PassengerInformation,

          array_fill(0, count($PassengerInformation), $flight_booking_id)

        );

        $FlightTicketUploadModel->insertBatchData('flight_booking_travelers', $InsertDatapassengerInfo);

        $super_admin__booking_pre_fix_code = $FlightTicketUploadModel->super_admin_booking_pre_fix_code($this->web_partner_id)['pre_fix'];

        $booking_ref_number = $super_admin__booking_pre_fix_code . $flight_booking_id;

        $FlightTicketUploadModel->updateData("flight_booking_list", array("id" => $flight_booking_id), array("booking_ref_number" => $booking_ref_number, "payment_status" => "Successful"));
        $InvoiceNumber = "";
        /* invoice  Number Generate Number */
        if ($booking_status ==  "Confirmed") {
          $CommonModel  =  new CommonModel();
          $WebpartnerGSTInfo = $segmentInfo['UserInfo']['gst_state_code'];
          $checkTaxableInvoce  =  checkTaxableNonTaxableINV($fareBreakup, $WebpartnerGSTInfo, 'flight', 'INV');
          $INVPrifix  =  getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'flight');
          $financialYear  =  get_financial_year();
          $whereCondition['service']   = 'flight';
          $whereCondition['invoice_type']   = 'INV';
          $whereCondition['financial_year']   = $financialYear;
          $otherParameter['financialYear']   = $financialYear;
          $otherParameter['service'] =  'flight';
          $otherParameter['invoice_type'] =  'INV';
          $otherParameter['INVPrifix'] = $INVPrifix;
          $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;

          $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
          $InvoiceInfoData =  generateInvoiceNumber($generateInvoiceData);
          $InvoiceNumber  =  $InvoiceInfoData['InvoiceNumber'];
          $InvoiceupdateData  =  $InvoiceInfoData['updateData'];
          $CommonModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
        }
        /* invoice  Number Generate Number */

        $extra_parm['booking_ref_number'] = $booking_ref_number;
        $extra_parm['webPartnerBreakUpInfo'] = $WebPartnerfareBreakup;
        $extra_parm['convenienceFee'] = 0;
        if ($bookingStoreData['bussiness_type'] == "B2B") {
          $extra_parm['agentBreakUpInfo'] = $fareBreakup;
        } else {
          $extra_parm['customerBreakUpInfo'] = $fareBreakup;
        }

        $WebPatnerAccountLogData['web_partner_id'] = $this->web_partner_id;

        $WebPatnerAccountLogData['user_id'] = $this->user_id;

        $WebPatnerAccountLogData[$key] = $account_log_id;


        $WebPatnerAccountLogData['created'] = create_date();

        $WebPatnerAccountLogData['transaction_type'] = 'debit';

        $WebPartnerAccountLogData['payment_mode'] = "Wallet";

        $WebPatnerAccountLogData['action_type'] = 'booking';

        $WebPatnerAccountLogData['role'] = 'web_partner';

        $WebPatnerAccountLogData['debit'] = $totalPrice;

        $WebPatnerAccountLogData['service'] = "flight";

        $WebPatnerAccountLogData['service_log'] = json_encode(array("PaxName" => $PassengerInformation[0]['title'] . " " . $PassengerInformation[0]['first_name'] . " " . $PassengerInformation[0]['last_name'], "Sector" => $origin . "-" . $destination, "TravelDate" => $departure_date, "AirlineString" => $airline_code, "TicketNo" => $PassengerInformation[0]['ticket_number']));

        $WebPatnerAccountLogData['extra_param'] = json_encode($extra_parm);

        $WebPatnerAccountLogData['remark'] = "Ticket Created Through  Mannual";

        $WebPatnerAccountLogData['booking_ref_no'] = $flight_booking_id;
        $WebPatnerAccountLogData['invoice_number'] = $InvoiceNumber;

        $WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);

        $WebPatnerAccountLogData['balance'] = round_value(($webPartnerBalance - $totalPrice));

        $added_data_id = $FlightTicketUploadModel->insertData($AccountTableName, $WebPatnerAccountLogData);

        $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);

        $FlightTicketUploadModel->updateData($AccountTableName, array("id" => $added_data_id), $WebPatnerAccountLogDataUpdate);

        $saveNoteData  =  array(

          "booking_ref_no" => $flight_booking_id,

          'sup_staff_id' =>  $this->user_id,

          'service_type' =>  "flight",

          'add_by' =>  "webpartner",

          /*  'comment' =>  $bookingStoreData['issuer_remark'], */

          'created' => create_date()

        );

        /*   $saveNoteDataId =  $FlightTicketUploadModel->insertData('web_partner_booking_notes',$saveNoteData); */
        $RedirectUrl  =  site_url('flight/confirmation/' . $ticketData  =  dev_encode(json_encode(array($flight_booking_id))));
        $message = array("StatusCode" => 1, "Message" => "Ticket Upload Successfully", "Class" => "success_popup");
        $this->session->setFlashdata('Message', $message);
        return  redirect()->to($RedirectUrl);
      } else {
        $message = array("StatusCode" => 2, "Message" => "Agency have insufficient balance", "Class" => "error_popup");
        $this->session->setFlashdata('Message', $message);
        $RedirectUrl  =  site_url('flight-ticket-upload/review-detail?segmentinfokey=' . $input['segmentinfokey']);
        return  redirect()->to($RedirectUrl);
      }
    } else {
      $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
      $this->session->setFlashdata('Message', $message);
      $RedirectUrl  =  site_url('flight-ticket-uplaod/review-detail?segmentinfokey=' . $input['segmentinfokey']);
      return  redirect()->to($RedirectUrl);
    }
  }

  function GetUpdateFlightTicketInfo()

  {

    $booking_refrence_number =  $this->request->uri->getSegment(3);

    $FlightTicketUploadModel  =  new FlightTicketUploadModel();

    $bookingInfo  = $FlightTicketUploadModel->flight_booking_detail($booking_refrence_number);

    $flightSupplier  =  $FlightTicketUploadModel->getData("offline_provider", array('flight_service' => 'active'), $singalRecord = 0, $whereApply  =  1, 'id,supplier_name');

    if ($bookingInfo) {

      if ($bookingInfo['is_domestic']) {

        $airlineLogoClass = "domAirLogo";
      } else {

        $airlineLogoClass = "intAirLogo";
      }

      $searchData = json_decode($bookingInfo['search_request'], true);

      $childCount = $searchData['Child'];

      $infantCount = $searchData['Infant'];

      $bookingInfo['airlineLogoClass'] = $airlineLogoClass;

      $bookingInfo['childCount'] = $childCount;

      $bookingInfo['infantCount'] = $infantCount;

      $FareBreakUp = array();

      $fareBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);

      $super_admin_fare_break_up = json_decode($bookingInfo['super_admin_fare_break_up'], true);

      $markup = isset($super_admin_fare_break_up['SUP_Markup']) ? $super_admin_fare_break_up['SUP_Markup'] : 0;

      $discount = isset($super_admin_fare_break_up['SUP_Discount']) ? $super_admin_fare_break_up['SUP_Discount'] + $super_admin_fare_break_up['SUP_ExtraDiscount'] : 0;

      $MealCharge  = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
      $SeatCharge  = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
      $BaggageCharge  = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;

      $FareBreakUp = array(
        "FareBreakup" => array(
          "BaseFare" => array("Value" => custom_money_format(round_value($fareBreakupArray['BaseFare'])), "LabelText" => "Base Fare"),
          "Taxes" => array("Value" => custom_money_format(round_value($fareBreakupArray['Tax'])), "LabelText" => "Taxes"),
          "ServiceAndOtherCharge" => array("Value" => custom_money_format(round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'])), "LabelText" => "Other & Service Charges"),
          /*   "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
          "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
          "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
          /* "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
          /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
          "CommEarned" => array("Value" => custom_money_format(round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'])), "LabelText" => "Comm Earned (-)"),
          /*   "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"), */
          "TDS" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'])), "LabelText" => "TDS (+)")
        ),
        "TotalAmount" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $MealCharge + $SeatCharge + $BaggageCharge)), "LabelText" => "Total Amount"),
        "GSTDetails" => ($fareBreakupArray['GST']),
        "WebPMarkUp" => array("Value" => custom_money_format(round_value($markup)), "LabelText" => "Apply Mark Up"),
        "WebPDiscount" => array("Value" => custom_money_format(round_value($discount)), "LabelText" => "Apply Discount"),
      );

      $bookingInfo['FareBreakUp'] = $FareBreakUp;

      $data = [

        'title' => $this->UpdateTicketTitle,

        'bookingInfo' => $bookingInfo,

        'flightSupplier' => $flightSupplier,

        'view' => "Flight\Views\FlightTicketUpload\Update-Flight-Ticket",

      ];



      return view('template/default-layout', $data);
    } else {

      $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");

      $this->session->setFlashdata('Message', $message);

      return  redirect()->to(site_url('flight/bookings'));
    }
  }


  public  function segmentPassengerDetail()
  {

    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {

      $input  =  $this->request->getGET();

      $FlightTicketUploadModel  =  new FlightTicketUploadModel();

      $segmentInfo   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);

      if ($segmentInfo) {

        $paxInfoData =  array();

        $passengerInfo['passengerCounter'] =  1;

        $passengerInfo['passengerChild'] =  0;

        $passengerInfo['passengerInfant'] =  0;

        $passengerInfo['pax_type'] =  "Adult";

        $passengerInfo['ChildPricingPaxShow'] =  "no";

        $passengerInfo['InfantPricingPaxShow'] =  "no";

        $passengerInfo['ssrtripsegmentInfo'] =   json_decode($segmentInfo['ssr_info'], true);

        $passengerInfo['TicketType'] =   $segmentInfo['ticket_type'];

        $paxInfoData  =  json_decode($segmentInfo['passenger_detail'], true);

        $paxInfoPricingData  =  json_decode($segmentInfo['passenger_pricing'], true);

        if (!empty($paxInfoData)) {
          $passengerInfo['paxDataInfo'] =     $paxInfoData;
          $passengerInfo['paxInfoPricingData'] =     $paxInfoPricingData;
          $passengerInfo['dealData'] =     json_decode($segmentInfo['deal_info'], true);
        }
        $passengerDetailinfoView = view('Modules\Flight\Views\FlightTicketUpload\passenger-details', $passengerInfo);
        $passengerPricingView  = view("Modules\Flight\Views\FlightTicketUpload/ticket-upload-pax-pricing", $passengerInfo);
        $data = [

          'title' => $this->title,

          'view' => "Flight\Views\FlightTicketUpload/ticket-upload-second-step",

          'SegmentInfokey' => $input['segmentinfokey'],

          'passengerDetailinfoView' => $passengerDetailinfoView,

          'passengerPricingView' => $passengerPricingView,

          'passengerCounter' => 1,

        ];

        return view('template/sidebar-layout', $data);
      } else {

        $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");

        $this->session->setFlashdata('Message', $message);

        return  redirect()->to(site_url('flight-ticket-upload'));
      }
    } else {

      $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");

      $this->session->setFlashdata('Message', $message);

      return  redirect()->to(site_url('flight-ticket-upload'));
    }
  }
  public  function reviewDetail()
  {
    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {
      $input  =  $this->request->getGET();
      $FlightTicketUploadModel  =  new FlightTicketUploadModel();
      $segmentInfo   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);
      if ($segmentInfo) {
        $developerData =   json_decode($segmentInfo['developer_booking_data'], true);
        $passenger_detail =   json_decode($segmentInfo['passenger_detail'], true);
        $passenger_pricing =   json_decode($segmentInfo['passenger_pricing'], true);
        $DealInfo  =  json_decode($segmentInfo['deal_info'], true);
        //user info start
        //Working in progress
        $super_admin_gst_state_code =  $FlightTicketUploadModel->getData('web_partner', array('id' => $this->web_partner_id), $singalRecord = 1, $whereApply  =  0, 'gst_state_code')['gst_state_code'];
        if ($segmentInfo['bussiness_type'] == "B2B") {
          $AccountTableName = "agent_account_log";
          $account_log_id = $segmentInfo['for_issued'];
          $key = "wl_agent_id";
          $segmentInfo['UserInfo'] = $FlightTicketUploadModel->getData('agent', array("id" => $segmentInfo['for_issued']), $singalRecord = 1, $whereApply  =  1, 'company_name,company_id,gst_state_code');
          $forIssuedContactDetail  = $FlightTicketUploadModel->getData('agent_users', array("agent_id" => $segmentInfo['for_issued'], 'primary_user' => 1), $singalRecord = 1, $whereApply  =  1, 'id,login_email,first_name,last_name,mobile_no,street,city,state,country,pin_code');
          $forIssuedContactDetail['gst'] = $segmentInfo['UserInfo'];
          $forIssuedContactDetail['role'] = "agent";
          $segmentInfo['UserInfo']['role'] = "agent";
        } else {
          $AccountTableName = "customer_account_log";
          $account_log_id = $segmentInfo['for_issued'];
          $key = "customer_id";
          $segmentInfo['UserInfo']['gst_state_code'] = "";
          $forIssuedContactDetail  = $FlightTicketUploadModel->getData('customer', array("id" => $segmentInfo['for_issued']), $singalRecord = 1, $whereApply  =  1, 'id,email_id,first_name,last_name,mobile_no,address,city,state,country,pin_code');
          $forIssuedContactDetail['gst'] = $segmentInfo['UserInfo'];
          $segmentInfo['UserInfo'] = $forIssuedContactDetail;
          $segmentInfo['UserInfo']['gst_state_code'] = "";
          $segmentInfo['UserInfo']['role'] = "customer";
          $forIssuedContactDetail['role'] = "customer";
        }
        $forIssuedContactDetail['webpgstCode'] = $super_admin_gst_state_code;
        /* web partner primary user Detail  */
        $PassengerFareInformation  = generatePaxData($passenger_detail, $passenger_pricing, $forIssuedContactDetail);
        $segmentInfo['pnr'] = $PassengerFareInformation['PNR'];
        $segmentInfo['passenger_detail'] = $PassengerFareInformation['paxData'];
        $segmentInfo['issue_by_supplier'] = explode("#", $segmentInfo['issue_by_supplier'])[1];
        $FareBreakUp  =  $PassengerFareInformation['WebPartnerfareBreakup'];
        $segmentInfo['fare_type'] = "Publish";
        $totalpax  =   $PassengerFareInformation['NoofAdult'] + $PassengerFareInformation['NoofChild'];;
        $flightFare  = get_flight_fare_upload_ticket($FareBreakUp, $DealInfo, $segmentInfo['UserInfo'], $super_admin_gst_state_code, $passenger_detail, $totalpax);
        $web_partner_fare_break_up  = $flightFare['WebPartnerFareBreakup'];
        if ($forIssuedContactDetail['role'] == "agent") {
          $fareBreakupArray  = $flightFare['AgentFareBreakup'];
        } else {
          $fareBreakupArray = $flightFare['CustomerFareBreakup'];
        }
        $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
        $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
        $FareBreakUp = array(
          "FareBreakup" => array(
            "BaseFare" => array("Value" => custom_money_format(round_value($fareBreakupArray['BaseFare'])), "LabelText" => "Base Fare"),
            "Taxes" => array("Value" => custom_money_format(round_value($fareBreakupArray['Tax'])), "LabelText" => "Taxes"),
            "ServiceAndOtherCharge" => array("Value" => custom_money_format(round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'])), "LabelText" => "Other & Service Charges"),
            /*  "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"), */
            "CommEarned" => array("Value" => custom_money_format(round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'])), "LabelText" => "Comm Earned (-)"),
            "TDS" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'])), "LabelText" => "TDS (+)")
          ),
          "TotalAmount" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'])), "LabelText" => "Total Amount"),
          "GSTDetails" => ($fareBreakupArray['GST']),
          "WebPMarkUp" => array("Value" => custom_money_format(round_value($markup)), "LabelText" => "Apply Mark Up"),
          "WebPDiscount" => array("Value" => custom_money_format(round_value($discount)), "LabelText" => "Apply Discount"),
        );
        $segmentInfo['FareBreakUp'] = $FareBreakUp;
        $segmentInfo['total_price'] = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'];

        $webPartnerBalanceInfo  = $FlightTicketUploadModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $this->web_partner_id);
      
        $webPartnerBalance = 0;
        if (isset($webPartnerBalanceInfo['balance'])) {
          $webPartnerBalance = $webPartnerBalanceInfo['balance'];
        }
        $showSaveButton  = 1;
        if ($webPartnerBalance < ($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'])) {
          $message = array("StatusCode" => 2, "Message" => "Agency have insufficient balance", "Class" => "error_popup");
          $this->session->setFlashdata('Message', $message);
          $showSaveButton  = 0;
        }
       
        $data = [
          'title' => $this->title,
          'view' => "Flight\Views/FlightTicketUpload/flight-booking-detail",
          'SegmentInfokey' => $_GET['segmentinfokey'],
          'bookingDetail' => $segmentInfo,
          'showSaveButton' => $showSaveButton,
        ];
        return view('template/sidebar-layout', $data);
      } else {
        $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
        $this->session->setFlashdata('Message', $message);
        $RedirectUrl  =  site_url('flight-ticket-import/segment-passenger-detail?segmentinfokey=' . $input['segmentinfokey']);
        return  redirect()->to($RedirectUrl);
      }
    } else {
      $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
      $this->session->setFlashdata('Message', $message);
      $RedirectUrl  =  site_url('flight-ticket-import/segment-passenger-detail?segmentinfokey=' . $input['segmentinfokey']);
      return  redirect()->to($RedirectUrl);
    }
  }
  public function savePassenger()
  {
    $input = $this->request->getPost();
    $validate = new TicketUploadValidation();
    $validationConfigArray = $validate->pax_validation($input);
    $this->validation->setRules($validationConfigArray);
    $rules = $this->validation->run($input);
    if (!$rules) {
      $errors = $this->validation->getErrors();
      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
      return $this->response->setJSON($data_validation);
    } else {
      $FlightTicketUploadModel  =  new FlightTicketUploadModel();
      $segmentinfokey = $input['temptripSegmentId'];
      $segmentInfo   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $input['temptripSegmentId'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1, 'developer_booking_data,passenger_detail');
      $updateData  =  array(
        "passenger_detail" => json_encode($input['pax']),
        "passenger_pricing" => json_encode($input['pricing']),
        "deal_info" => json_encode($input['deal']),
        "modified" => create_date(),
      );
      $FlightTicketUploadModel->updateData("flight_ticket_upload_data_temp", array("id" => $input['temptripSegmentId']), $updateData);
      $bookingStoreData   =  $FlightTicketUploadModel->getData('flight_ticket_upload_data_temp', array("id" => $segmentinfokey, 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);
      if ($bookingStoreData) {
        $RedirectUrl  =  site_url('flight-ticket-upload/review-detail?segmentinfokey=' . $input['temptripSegmentId']);
        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
        return $this->response->setJSON($data_validation);
      } else {
        $message = array("StatusCode" => 2, "Message" => "Technical problem occurred", "Class" => "error_popup");
        $this->session->setFlashdata('Message', $message);
        $RedirectUrl  =  site_url('flight-ticket-upload');
        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
        return $this->response->setJSON($data_validation);
      }
    }
  }
}
