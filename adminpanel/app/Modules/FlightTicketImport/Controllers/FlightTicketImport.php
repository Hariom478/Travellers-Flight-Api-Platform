<?php

namespace Modules\FlightTicketImport\Controllers;

use App\Modules\FlightTicketImport\Models\FlightTicketImportModel;
use App\Controllers\BaseController;
use Modules\FlightTicketImport\Config\Validation;
use CodeIgniter\I18n\Time;
use App\Models\CommonModel;

class FlightTicketImport extends BaseController
{

  public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
  {
    parent::initController($request, $response, $logger);
    $this->title = "Flight  Import PNR";
    $this->UpdateTicketTitle = "Flight  Import PNR";
    helper('Modules\FlightTicketImport\Helpers\flight_ticket_import_helper');
    $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    $this->Services = API_REQUEST_URL . 'airservice/rest/';
    $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
  }


  function  index()
  {
    if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active"){
    $FlightTicketImportModel  =  new FlightTicketImportModel();
    $apiSupplier  =  $FlightTicketImportModel->getData("api_supplier", array(), $singalRecord = 0, $whereApply  =  0, 'id,supplier_name');
    $issueSupplier  =  $FlightTicketImportModel->getData("offline_provider", array('flight_service' => 'active'), $singalRecord = 0, $whereApply  =  1, 'id,supplier_name');
    $data = [
      'title' => $this->title,
      'apiSupplier' => $apiSupplier,
      'issueSupplier' => $issueSupplier,
      'view' => "FlightTicketImport\Views\index",
    ];
    return view('template/sidebar-layout', $data);

  }else{
    access_denied();
}
  }


  public function checkPNR()
  {
    $data = $this->request->getPost();
    $validate = new Validation();
    $validationConfigArray = $validate->checkPNR($data);
    $this->validation->setRules($validationConfigArray);
    $rules = $this->validation->run($data);
    if (!$rules) {
      $errors = $this->validation->getErrors();
      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
      return $this->response->setJSON($data_validation);
    } else {
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $CheckExistingPNR   =  $FlightTicketImportModel->getData('flight_booking_list', array("pnr" => $data['pnr']), $singalRecord = 1, $whereApply  =  1);
      /*  empty($CheckExistingPNR) */
      if (empty($CheckExistingPNR)) {
        $ImportPNRRequest = array(
          "UserIp" => $this->request->getIPAddress(),
          "PNR" => $data['pnr'],
          "APISupplier" => $data['supplier'],
          "LastName" => $data['last_name'],
        );
        $url = $this->Services . 'importpnr';
        $response   =  RequestWithoutAuth($ImportPNRRequest, $url);
        /*  echo  $url;
         pr( $response);die; */
        /*
        $response = file_get_contents(FCPATH . "webroot/importPNR.json"); 
        $response = json_decode($response, true);   */
        $bookingid   = Null;
        $ssrSgmentData =  array();
        if ($response) {
          if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0) {
            $Result   =  $response['Result'];
            $segmentInfo  =  $Result['Segments'];
            $countryCode =  array();
            $orginDestinationAirports  =  array();
            foreach ($segmentInfo as $tripkey => $tripinfo) {
              $firstSegment  =  reset($tripinfo);
              $lastSegment  =  end($tripinfo);
              $AirSegments[($tripkey - 1)]['Origin'] =  trim($firstSegment['Origin']['AirportCode']);
              $AirSegments[($tripkey - 1)]['Destination'] =  trim($lastSegment['Destination']['AirportCode']);
              $AirSegments[($tripkey - 1)]['PreferredTime'] = $firstSegment['Origin']['DepartTime'];
              $AirSegments[($tripkey - 1)]['PreferredTime'] =  date("Y-m-d", strtotime(explode("T", $firstSegment['Origin']['DepartTime'])[0])) . "T00:00:00";
            }
            unset($Result['Segments']);
            unset($response['Result']);
            $developerData  =  array(
              "AirSegments" => $AirSegments,
              "JourneyType" => get_journey_type_import_ticket($Result['JourneyType']),
              "IsDomestic" => $Result['IsDomestic'],
              "Result" => $Result,
              "ApiResponse" => $response,
            );
            $insertData  =  array(
              "service" => "flight",
              "booking_id" => $bookingid,
              "issue_by_supplier" => $data['issue_supplier'],
              "api_supplier" => $data['supplier'],
              "for_issued_short_info" => $data['webpartner_info'],
              "is_refundable" => $Result['IsRefundable'],
              "cabin_class" => $firstSegment['CabinClass'],
              "for_issued" => $data['tts_web_partner_info_id'],
              "for_issued_info" => $data['tts_web_partner_info'],
              "airline_remark" => $Result['AirlineRemark'],
              "ticket_type" => 'ImportPNR',
              "segment_temp_data" => json_encode($segmentInfo),
              "ssr_info" => json_encode($ssrSgmentData),
              "passenger_detail" => json_encode($Result['Passenger']),
              "developer_booking_data" => json_encode($developerData),
              "created" => create_date(),

            );
            $insertId  =  $FlightTicketImportModel->insertData("flight_ticket_upload_data_temp", $insertData);
            $RedirectUrl  =  site_url('flight-ticket-import/import-pnr-details?segmentinfokey=' . $insertId);
            $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
            return $this->response->setJSON($data_validation);
          } else {
            $message = array("StatusCode" => 2, "Message" => isset($response['Error']['ErrorMessage']) ? $response['Error']['ErrorMessage'] : "Technical problem occurred", "Class" => "error_popup");
            return $this->response->setJSON($message);
          }
        } else {
          $message = array("StatusCode" => 2, "Message" => "Technical problem occurred", "Class" => "error_popup");
          return $this->response->setJSON($message);
        }
      } else {
        $message = array("StatusCode" => 2, "Message" => "Booking already exist for " . $data['pnr'] . " PNR, Booking Reference Number is " . $CheckExistingPNR['booking_ref_number'], "Class" => "error_popup");
        return $this->response->setJSON($message);
      }
    }
  }
  public function ImportPNRDetails()
  {
    $tripsegmentInfo =  array();
    $tripsegmentInfoData =  array();
    $tripSegmentInfoId =  "";
    $ticketType  =  "ImportPNR";
    $FlightTicketImportModel  =  new FlightTicketImportModel();
    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {
      $input  =  $this->request->getGET();
      $tripsegmentInfoData   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight", "ticket_type" => "ImportPNR"), $singalRecord = 1, $whereApply  =  1);
      $tripsegmentInfo =  json_decode($tripsegmentInfoData['segment_temp_data'], true);
      $ticketType   =  $tripsegmentInfoData['ticket_type'];
      unset($tripsegmentInfoData['segment_temp_data']);
      $tripSegmentInfoId  = $_GET['segmentinfokey'];
    }
    $apiSupplier  =  $FlightTicketImportModel->getData("api_supplier", array(), $singalRecord = 0, $whereApply  =  0, 'id,supplier_name');
    $issueSupplier  =  $FlightTicketImportModel->getData("offline_provider", array('flight_service' => 'active'), $singalRecord = 0, $whereApply  =  1, 'id,supplier_name');
    $data = [
      'title' => $this->title,
      'tripsegmentInfo' => $tripsegmentInfo,
      'tripSegmentInfoId' => $tripSegmentInfoId,
      'tripsegmentInfoData' => $tripsegmentInfoData,
      'ticketType' => $ticketType,
      'apiSupplier' => $apiSupplier,
      'issueSupplier' => $issueSupplier,
      'view' => "FlightTicketImport\Views\Import-Ticket-Details",
    ];

    return view('template/sidebar-layout', $data);
  }
  public function storeSegementInfo()

  {
    $data = $this->request->getPost();
    $validate = new Validation();
    $validationConfigArray = $validate->segmentsinfo_validation($data);
    $this->validation->setRules($validationConfigArray);
    $rules = $this->validation->run($data);
    if (!$rules) {
      $errors = $this->validation->getErrors();
      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
      return $this->response->setJSON($data_validation);
    } else {
      $countryCode =  array();
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $orginDestinationAirports  =  array();
      foreach ($data['segmentinfo'] as $tripkey => $tripinfo) {
        $orginDestinationAirports =  array_merge($orginDestinationAirports,  array_column($tripinfo, "origin_airport_code"));
        $orginDestinationAirports =     array_merge($orginDestinationAirports,  array_column($tripinfo, "destination_airport_code"));
        $firstSegment  =  reset($tripinfo);
        $lastSegment  =  end($tripinfo);
        $AirSegments[($tripkey - 1)]['Origin'] =  trim($firstSegment['origin_airport_code']);
        $AirSegments[($tripkey - 1)]['Destination'] =  trim($lastSegment['destination_airport_code']);
        $AirSegments[($tripkey - 1)]['PreferredTime'] =  date("Y-m-d", strtotime($firstSegment['depart_time'])) . "T00:00:00";
      }

      $orginDestinationAirports =  array_unique($orginDestinationAirports);
      $orginDestinationAirportDetail  =  $FlightTicketImportModel->selected_airport_detail($orginDestinationAirports);
      $CustomTripData =  array();
      $ssrSgmentData  =  array();
      $tripIndicator = 0;
      $gdsPnr =  array();
      foreach ($data['segmentinfo'] as $tripkey => $tripinfo) {
        $segmentIndicator = 0;
        $CustomSegmenttripData =  array();
        $originAirportCode =  array();
        $destinationAirportCode =  array();
        $origindestinationAirportCode =  array();
        $originAirportCode  =  array_column($tripinfo, 'origin_airport_code');
        $destinationAirportCode  =  array_column($tripinfo, 'destination_airport_code');
        $origindestinationAirportCode =  array_merge($originAirportCode, $destinationAirportCode);
        $originDestinationTimeZone  = $FlightTicketImportModel->getAirportCodeTimeZoneIn($origindestinationAirportCode);
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
          $Duration  =  journeyTimeImportTicket($origintimezone, $destinationtimezone, str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['depart_date'] . " " . $segmentData['depart_time']))), str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($segmentData['arrival_date'] . " " . $segmentData['arrival_time']))));

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
    $tripsegmentInfoData   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $data['temptripSegmentId'], 'service' => "flight", "ticket_type" => "ImportPNR"), $singalRecord = 1, $whereApply  =  1);
    $developerData =   json_decode($tripsegmentInfoData['developer_booking_data'], true);
    $developerData['AirSegments'] = $AirSegments;
    $developerData['gdsPnr'] = $gdsPnr;
    $insertId = $data['temptripSegmentId'];
    $updateData  =  array(
      "issue_by_supplier" => $data['issue_supplier'],
      "api_supplier" => $data['supplier'],
      /*  "issuer_remark"=>$data['issuer_remark'], */
      "cabin_class" => $data['cabin_class'],
      "is_refundable" => $data['is_refundable'],
      "for_issued" => $data['tts_web_partner_info_id'],
      "for_issued_short_info" => $data['webpartner_info'],
      "for_issued_info" => $data['tts_web_partner_info'],
      "airline_remark" => $data['airline_remark'],
      "segment_temp_data" => json_encode($CustomTripData),
      "ssr_info" => json_encode($ssrSgmentData),
      "developer_booking_data" => json_encode($developerData),
      "modified" => create_date(),
    );
    $FlightTicketImportModel->updateData("flight_ticket_upload_data_temp", array("id" => $data['temptripSegmentId']), $updateData);
    $RedirectUrl  =  site_url('flight-ticket-import/segment-passenger-detail?segmentinfokey=' . $insertId);
    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
    return $this->response->setJSON($data_validation);
  }
  public  function segmentPassengerDetail()
  {
    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {
      $input  =  $this->request->getGET();
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $segmentInfo   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);
      if ($segmentInfo) {
        $paxInfoData =  array();
        $dealData =  array();
        $passengerInfo['ssrtripsegmentInfo'] =   json_decode($segmentInfo['ssr_info'], true);
        $developerData =   json_decode($segmentInfo['developer_booking_data'], true);
        $PNR =  $developerData['Result']['PNR'];
        $passengerInfo['TicketType'] =   $segmentInfo['ticket_type'];
        $paxInfoData  =  json_decode($segmentInfo['passenger_detail'], true);
        $paxInfoPricingData  =  json_decode($segmentInfo['passenger_pricing'], true);
        $dealData  =  json_decode($segmentInfo['deal_info'], true);
        $passengerInfo['paxDataInfo'] =     $paxInfoData;
        $passengerInfo['dealData'] =     $dealData;
        $passengerInfo['PNR'] =     $PNR;
        $passengerInfo['FareBreakup'] =     $developerData['Result']['Fare'];
        $passengerDetailinfoView = view('Modules\FlightTicketImport\Views\passenger-details', $passengerInfo);
        $importticketdeal  = view("Modules\FlightTicketImport\Views/import-ticket-deal", $passengerInfo);
        $data = [
          'title' => $this->title,
          'view' => "FlightTicketImport\Views/ticket-upload-second-step",
          'SegmentInfokey' => $input['segmentinfokey'],
          'passengerDetailinfoView' => $passengerDetailinfoView,
          'importticketdeal' => $importticketdeal,
          'PNR' => $PNR,
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
  public  function reviewDetail()

  {

    if (isset($_GET['segmentinfokey']) && $_GET['segmentinfokey'] != "") {
      $input  =  $this->request->getGET();
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $segmentInfo   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);
      if ($segmentInfo) {
        $developerData =   json_decode($segmentInfo['developer_booking_data'], true);
        $passenger_detail =   json_decode($segmentInfo['passenger_detail'], true);
        $DealInfo  =  json_decode($segmentInfo['deal_info'], true);
        $segmentInfo['pnr'] = $developerData['Result']['PNR'];
        $FareBreakUp  =  $developerData['Result']['Fare'];
        $segmentInfo['fare_type'] = $developerData['Result']['FareType'];
        $segmentInfo['WebPartnerInfo'] = $FlightTicketImportModel->getData('web_partner', array("id" => $segmentInfo['for_issued']), $singalRecord = 1, $whereApply  =  1, 'company_name,company_id,gst_state_code');
        $super_admin_gst_state_code =  $FlightTicketImportModel->getData('super_admin_website_setting', array(), $singalRecord = 1, $whereApply  =  0, 'gst_state_code')['gst_state_code'];
        $flightFare  = get_flight_fare_import_ticket($developerData['Result']['Fare'], $DealInfo, $segmentInfo['WebPartnerInfo'], $super_admin_gst_state_code, $passenger_detail);
        $fareBreakupArray  = $flightFare['WebPartnerFareBreakup'];
        $super_admin_fare_break_up  = $flightFare['SuperAdminFareBreakup'];
        $markup = isset($super_admin_fare_break_up['SUP_Markup']) ? $super_admin_fare_break_up['SUP_Markup'] : 0;
        $discount = isset($super_admin_fare_break_up['SUP_Discount']) ? $super_admin_fare_break_up['SUP_Discount'] + $super_admin_fare_break_up['SUP_ExtraDiscount'] : 0;
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

        $webPartnerBalanceInfo  = $FlightTicketImportModel->web_partner_available_balance($segmentInfo['for_issued']);
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
          'view' => "FlightTicketImport\Views/flight-booking-detail",
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
    $validate = new Validation();
    $validationConfigArray = $validate->pax_validation($input);
    $this->validation->setRules($validationConfigArray);
    $rules = $this->validation->run($input);
    if (!$rules) {
      $errors = $this->validation->getErrors();
      $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
      return $this->response->setJSON($data_validation);
    } else {
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $segmentinfokey = $input['temptripSegmentId'];
      $segmentInfo   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $input['temptripSegmentId'], 'service' => "flight"), $singalRecord = 1, $whereApply  =  1, 'developer_booking_data,passenger_detail');
      $updateData  =  array(
        "import_update_passenger_info" => json_encode($input['pax']),
        "deal_info" => json_encode($input['deal']),
        "modified" => create_date(),
      );
      $FlightTicketImportModel->updateData("flight_ticket_upload_data_temp", array("id" => $input['temptripSegmentId']), $updateData);
      $bookingStoreData   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $segmentinfokey, 'service' => "flight"), $singalRecord = 1, $whereApply  =  1);
      if ($bookingStoreData) {
        $RedirectUrl  =  site_url('flight-ticket-import/review-detail?segmentinfokey=' . $input['temptripSegmentId']);
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

  function generateTicket()
  {

    $input  =  $this->request->getGET();
    if (isset($input['segmentinfokey']) && $input['segmentinfokey'] != "") {
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      $bookingStoreData   =  $FlightTicketImportModel->getData('flight_ticket_upload_data_temp', array("id" => $input['segmentinfokey'], 'service' => "flight", 'ticket_type' => "ImportPNR"), $singalRecord = 1, $whereApply  =  1);
      $developer_booking_data   =  json_decode($bookingStoreData['developer_booking_data'], true);
      $segment_temp_data   =  json_decode($bookingStoreData['segment_temp_data'], true);
      $passenger_detail   =  json_decode($bookingStoreData['passenger_detail'], true);
      $DealInfo   =  json_decode($bookingStoreData['deal_info'], true);
      $FareBreakUp  =  $developer_booking_data['Result']['Fare'];
      $WebPartnerInfo = $FlightTicketImportModel->getData('web_partner', array("id" => $bookingStoreData['for_issued']), $singalRecord = 1, $whereApply  =  1, 'company_name,company_id,gst_state_code');
      $super_admin_gst_state_code =  $FlightTicketImportModel->getData('super_admin_website_setting', array(), $singalRecord = 1, $whereApply  =  0, 'gst_state_code')['gst_state_code'];
      $flightFare  = get_flight_fare_import_ticket($developer_booking_data['Result']['Fare'], $DealInfo, $WebPartnerInfo, $super_admin_gst_state_code, $passenger_detail);
      $FlightTicketImportModel  =  new FlightTicketImportModel();
      /* web partner primary user Detail  */
      $forIssuedContactDetail  = $FlightTicketImportModel->getData('admin_users', array("web_partner_id" => $bookingStoreData['for_issued'], 'primary_user' => 1), $singalRecord = 1, $whereApply  =  1, 'id,login_email,first_name,last_name,mobile_no,street,city,state,country,pin_code');
      /* web partner primary user Detail  */
      $SuperAdminfareBreakup =  $flightFare['SuperAdminFareBreakup'];
      $WebPartnerfareBreakup =  $flightFare['WebPartnerFareBreakup'];
      $PassengerFareInformation  = generateImportPnrPaxData($passenger_detail, $flightFare, $developer_booking_data['Result']);
      $PassengerInformation =  $PassengerFareInformation['paxData'];
      $gst_info = json_encode($PassengerFareInformation['gstInfo']);
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

      $superAdminStaffDetail  =  admin_cookie_data()['admin_user_details'];
      $totalPrice =  round_value($WebPartnerfareBreakup['OfferedPrice'] + $WebPartnerfareBreakup['TDS']);
      $savePaxinfo = array(
        'tts_search_token' => $developer_booking_data['ApiResponse']['SearchTokenId'],
        'web_partner_id' => $bookingStoreData['for_issued'],
        'is_price_changed' => 0,
        'is_time_changed' => 0,
        'trip_indicator' => $developer_booking_data['Result']['TripIndicator'],
        'search_request' => json_encode($searchRequest),
        'journey_type' =>   $developer_booking_data['JourneyType'],
        'origin' => $origin,
        'pnr' => $pnr,
        'resultIndex' => null,
        'destination' => $destination,
        'departure_date' => $departure_date,
        'is_domestic' => $developer_booking_data['IsDomestic'],
        'is_lcc' => $developer_booking_data['Result']['IsLCC'],
        'is_refundable' => $bookingStoreData['is_refundable'],
        'fare_type' => $developer_booking_data['Result']['FareType'],
        'airline_code' => $developer_booking_data['Result']['AirlineCode'],
        'validating_airline_code' => $developer_booking_data['Result']['ValidatingAirlineCode'],
        'airline_pnr' => json_encode($developer_booking_data['gdsPnr']),
        'last_ticket_date' => "",
        'airline_remark' => $bookingStoreData['airline_remark'],
        'segments' => json_encode($segment_temp_data),
        'api_supplier' => $bookingStoreData['api_supplier'],
        'issue_supplier' => explode("#", $bookingStoreData['issue_by_supplier'])[1],
        'offline_supplier_id' => explode("#", $bookingStoreData['issue_by_supplier'])[0],
        'supplier_booking_id' => isset($developer_booking_data['Result']['SupplierBookingId']) ? $developer_booking_data['Result']['SupplierBookingId'] : NULL,
        'payment_mode' => 'API_Wallet',
        "update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),
        'is_manual' => 1,
        'book_request' => "requested",
        'payment_status' => $payment_status,
        'booking_status' => $booking_status,
        'gst_info' =>  $gst_info,
        'sup_staff_id' =>  $this->user_id,
        'super_admin_fare_break_up' => json_encode($SuperAdminfareBreakup),
        'web_partner_fare_break_up' => json_encode($WebPartnerfareBreakup),
        'booking_channel' => 'ImportTicket',
        'total_price' => $totalPrice,
        'agent_staff_id' => isset($forIssuedContactDetail['id']) ? $forIssuedContactDetail['id'] : null,
        'offline_supplier_id' => $bookingStoreData['issue_by_supplier'],
        'is_gst_mandatory' => 0,
        'is_gst_allowed' => 0,
        'fare_rule' => isset($developer_booking_data['Result']['FareRules']) ? json_encode($developer_booking_data['Result']['FareRules']) : Null,
        'created' => create_date()
      );
      $webPartnerBalanceInfo  = $FlightTicketImportModel->web_partner_available_balance($bookingStoreData['for_issued']);
      $webPartnerBalance = 0;
      if (isset($webPartnerBalanceInfo['balance'])) {
        $webPartnerBalance = $webPartnerBalanceInfo['balance'];
      }
      if ($webPartnerBalance >= $totalPrice) {
        $flight_booking_id =  $FlightTicketImportModel->insertData('flight_booking_list', $savePaxinfo);
        $InsertDatapassengerInfo = array_map(
          function ($value, $flightBookingId) {
            $value['flight_booking_id'] = $flightBookingId;
            return $value;
          },
          $PassengerInformation,
          array_fill(0, count($PassengerInformation), $flight_booking_id)
        );
        $FlightTicketImportModel->insertBatchData('flight_booking_travelers', $InsertDatapassengerInfo);
        $super_admin__booking_pre_fix_code = $FlightTicketImportModel->super_admin_booking_pre_fix_code()['pre_fix'];
        $booking_ref_number = $super_admin__booking_pre_fix_code . $flight_booking_id;
        $FlightTicketImportModel->updateData("flight_booking_list", array("id" => $flight_booking_id), array("booking_ref_number" => $booking_ref_number, "payment_status" => "Successful"));
        $InvoiceNumber = "";
        /* invoice  Number Generate Number */
        $CommonModel  =  new CommonModel();
        $WebpartnerGSTInfo  =  $CommonModel->getDataRowType("web_partner", array("id" => $bookingStoreData['for_issued']), "company_gst_no");
        $checkTaxableInvoce  =  checkTaxableNonTaxableINV($WebPartnerfareBreakup, $WebpartnerGSTInfo, 'flight', 'INV');
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
        /* invoice  Number Generate Number */
        $WebPatnerAccountLogData['web_partner_id'] = $bookingStoreData['for_issued'];
        $WebPatnerAccountLogData['user_id'] = $this->user_id;
        $WebPatnerAccountLogData['created'] = create_date();
        $WebPatnerAccountLogData['transaction_type'] = 'debit';
        $WebPatnerAccountLogData['action_type'] = 'booking';
        $WebPatnerAccountLogData['role'] = 'super_admin';
        $WebPatnerAccountLogData['debit'] = $totalPrice;
        $WebPatnerAccountLogData['service'] = "flight";
        $WebPatnerAccountLogData['service_log'] = json_encode(array("PaxName" => $PassengerInformation[0]['title'] . " " . $PassengerInformation[0]['first_name'] . " " . $PassengerInformation[0]['last_name'], "Sector" => $origin . "-" . $destination, "TravelDate" => $departure_date, "AirlineString" => $airline_code, "TicketNo" => $pnr));
        $WebPatnerAccountLogData['remark'] = "Ticket Created Through  Mannual";
        $WebPatnerAccountLogData['booking_ref_no'] = $flight_booking_id;
        $WebPatnerAccountLogData['invoice_number'] = $InvoiceNumber;
        $WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
        $WebPatnerAccountLogData['balance'] = $webPartnerBalance - $totalPrice;
        $added_data_id = $FlightTicketImportModel->insertData('web_partner_account_log', $WebPatnerAccountLogData);
        $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
        $FlightTicketImportModel->updateData("web_partner_account_log", array("id" => $added_data_id), $WebPatnerAccountLogDataUpdate);
        if ($booking_status == 'Confirmed') {
          $Confirmationprefix          =  $FlightTicketImportModel->getDataRowType("super_admin_website_setting", array(), "flight_confirmation_counter,flight_confirmation_prefix,id");
          $BookingConfirmationNumber  =  GenerateConfirmationNumber("Flight", $Confirmationprefix['flight_confirmation_prefix'], ($Confirmationprefix['flight_confirmation_counter'] + 1));
          $FlightTicketImportModel->updateData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("flight_confirmation_counter" => ($Confirmationprefix['flight_confirmation_counter'] + 1)));
          $FlightTicketImportModel->updateData('web_partner_account_log', ['booking_ref_no' => $flight_booking_id, "service" => "flight", 'transaction_type' => "debit", 'action_type' => "booking"], ["booking_confirmation_number" => $BookingConfirmationNumber]);
        }

        $saveNoteData  =  array(
          "booking_ref_no" => $flight_booking_id,
          'sup_staff_id' =>  $this->user_id,
          'service_type' =>  "flight",
          'add_by' =>  "superadmin",
          /*   'comment' =>  $bookingStoreData['issuer_remark'], */
          'created' => create_date()
        );
        /*   $saveNoteDataId =  $FlightTicketImportModel->insertData('web_partner_booking_notes',$saveNoteData); */
        $RedirectUrl  =  site_url('flight/confirmation/' . $ticketData  =  dev_encode(json_encode(array($flight_booking_id))));
        $message = array("StatusCode" => 1, "Message" => "Import Pnr Successfully", "Class" => "success_popup");
        $this->session->setFlashdata('Message', $message);
        return  redirect()->to($RedirectUrl);
      } else {
        $message = array("StatusCode" => 2, "Message" => "Agency have insufficient balance", "Class" => "error_popup");
        $this->session->setFlashdata('Message', $message);
        $RedirectUrl  =  site_url('flight-ticket-import/review-detail?segmentinfokey=' . $input['segmentinfokey']);
        return  redirect()->to($RedirectUrl);
      }
    } else {
      $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
      $this->session->setFlashdata('Message', $message);
      $RedirectUrl  =  site_url('flight-ticket-import/review-detail?segmentinfokey=' . $input['segmentinfokey']);
      return  redirect()->to($RedirectUrl);
    }
  }
}
