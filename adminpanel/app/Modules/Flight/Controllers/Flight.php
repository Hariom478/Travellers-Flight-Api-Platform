<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightModel;
use App\Modules\Flight\Models\FlightBookingModel;
use App\Modules\Flight\Models\FlightAmendmentModel;
use App\Modules\Flight\Models\FlightAirportModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Modules\Flight\Models\FlightMarkupModel;
use App\Modules\Flight\Models\AgentClassModel;
use App\Modules\Flight\Models\FlightDiscountModel;
use App\Modules\Flight\Models\DistributorModel;
use App\Modules\Flight\Models\SupplierModel;
use App\Models\CommonModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;

class Flight extends BaseController
{
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		$this->title = "Flight";
		$this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
		$this->web_partner_details = admin_cookie_data()['admin_user_details'];
		$this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
		$this->user_id = admin_cookie_data()['admin_user_details']['id'];
		helper('Modules\Flight\Helpers\flight');

		$this->Services = API_REQUEST_URL . '/airservice/rest/';
	}

	function AssignUpdateflightTicket()
	{
		$bookingReferenceNumber = dev_decode($this->request->uri->getSegment(3));
		$FlightBookingModel = new FlightBookingModel();
		$BookingDetail = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
		if ($BookingDetail) {
			$checkbookingflighttime = checkbookingflighttime($BookingDetail['created']);
			if (isset ($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
				$message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
				$this->session->setFlashdata('Message', $message);
				return $this->response->redirect($this->request->getUserAgent()->getReferrer());
			}
			$updateData['webpartner_assign_user'] = $this->user_id;
			$FlightBookingModel->updateData("flight_booking_list", array("booking_ref_number" => $bookingReferenceNumber, "web_partner_id" => $this->web_partner_id), $updateData);
			$message = array("StatusCode" => 0, "Message" => "Ticket assign successfully", "Class" => "success_popup", "Reload" => "true");
			$this->session->setFlashdata('Message', $message);
			return $this->response->redirect($this->request->getUserAgent()->getReferrer());
		} else {
			return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
		}
	}
	public function get_airports()
	{
		$terms = $this->request->getGet('term');
		$terms = explode(',', $terms);
		$terms = end($terms);
		$FlightAirportModel = new FlightAirportModel();
		$get_airport = $FlightAirportModel->get_airport_autosuggestion($terms);
		$availableAirport = [];
		if (!empty ($get_airport)) {
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
		$availableAirline[] = 'ANY' . '-' . 'Any Airline';
		if (!empty ($get_airport)) {
			foreach ($get_airport as $data) {
				$availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
			}
		}
		echo json_encode($availableAirline);
	}

	function flight_booking_calender()
	{
		$getData = $this->request->getGet();
		$FlightBookingModel = new FlightBookingModel();
		$booking_list = $FlightBookingModel->getCalenderList($this->web_partner_id, $getData);
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
					$return_data = $FlightBookingModel->getReturnBookingDetail($this->web_partner_id, $booking['tts_search_token'], $getData);
					if (isset ($return_data['segments']) && $return_data['segments']) {
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
		return view('template/sidebar-layout', $data);
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
			$airlineLogoClass = "";
			$BookingStatus = array();
			$Pnr = array();
			$BookingRefNumber = array();
			$FareBreakUpDataArray = array();
			$TicketOption = array();
			$InvoiceOption = array();
			foreach ($bookingIds as $bookingId) {
				$paymentGatewayInfo = array();
				$FareBreakUp = array();
				$couponAmount = 0;
				$bookingInfo = $FlightModel->getBookingConfirmationData($bookingId, $this->web_partner_id);
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
				$couponInfo = json_decode($bookingInfo['coupon_info'], true);
				if (isset ($couponInfo['couponAmount']) && $bookingInfo['coupon_info'] != NULL && !empty ($bookingInfo['coupon_info'])) {
					$couponAmount = $couponInfo['couponAmount'];
				}
				if ($bookingInfo['trip_indicator'] == 1) {
					$onwardPnr = $bookingInfo['pnr'];
					$onwardBookingRefNumber = $bookingInfo['booking_ref_number'];
					$onwardBookingstatus = $bookingInfo['booking_status'];
					$BookingStatus[] = $onwardBookingstatus;
					if ($onwardPnr) {
						$Pnr[] = $onwardPnr;
						if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic']) {
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
						if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic']) {
							$TicketOption[] = "Return";
							$TicketOption[] = "Both";
							$InvoiceOption[] = "Return";
						}
					}
					$BookingRefNumber[] = $returnBookingRefNumber;
				}
				$webpartnerBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
				if ($bookingInfo['booking_source'] == "Wl_b2b") {
					$fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
				} else if ($bookingInfo['booking_source'] == "Wl_b2c") {
					$fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
				}
				$MealCharge = isset ($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
				$SeatCharge = isset ($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
				$BaggageCharge = isset ($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
				$markup = isset ($webpartnerBreakupArray['WebPMarkUp']) ? $webpartnerBreakupArray['WebPMarkUp'] : 0;
				$discount = isset ($webpartnerBreakupArray['WebPDiscount']) ? $webpartnerBreakupArray['WebPDiscount'] : 0;
				$addMarkupInTax = 0;
				$addMarkupInServiceCharge = 0;
				if (isset ($webpartnerBreakupArray['WebPDisplayMarkup']) && $webpartnerBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
					$WebPDisplayMarkup = $webpartnerBreakupArray['WebPDisplayMarkup'];
					$addMarkupInServiceCharge = $markup;
				} else {
					$WebPDisplayMarkup = "in_tax";
					$addMarkupInTax = $markup;
				}
				$TotalAmount = round_value($fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
				if ($bookingInfo['booking_source'] == "Wl_b2b") {
					$TotalAmount = round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
				}
				$GSTAmount = 0;
				if (isset ($fareBreakupArray['GST']['TotalGSTAmount'])) {
					$GSTAmount = $fareBreakupArray['GST']['TotalGSTAmount'];
				}
				$FareBreakUp = array(
					"FareBreakup" => array(
						"BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
						"Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
						"ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Other & Service Charges"),
						"MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
						"BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
						"GST" => array("Value" => round_value($GSTAmount), "LabelText" => "GST"),
						/*  "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
						"PublishedPrice" => array("Value" => round_value($fareBreakupArray['PublishedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Published Price"),
						/* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
						"CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Discount (-)"),
						"TDS" => array("Value" => round_value($fareBreakupArray['TDS']), "LabelText" => "TDS (+)")
					),
					"TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
					"BookingId" => $bookingInfo['id'],
					"WebPMarkUp" => round_value($markup),
					"WebPDiscount" => round_value($discount),
					"WebPDisplayMarkup" => $WebPDisplayMarkup,
				);
				if ($couponAmount > 0) {
					$FareBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promocode Discount (-)");
				}
				$FareBreakUpDataArray[$rtype] = $FareBreakUp;
				$ConfirmationBookingData = array(
					'Segments' => json_decode($bookingInfo['segments'], true),
					"FareRule" => json_decode($bookingInfo['fare_rule'], true),
					"IsRefundable" => $bookingInfo['is_refundable'],
					"PaymentStatus" => $bookingInfo['payment_status'],
					"BookingStatus" => $bookingInfo['booking_status'],
					"BookingSource" => $bookingInfo['booking_source'],
					"BookingRefNumber" => $bookingInfo['booking_ref_number'],
					"BookingId" => $bookingInfo['id'],
					"FareType" => $bookingInfo['fare_type'],
					"TravelersInfo" => json_decode($bookingInfo['travelersInfo'], true),
				);
				$bookingConfrimationData[$rtype] = $ConfirmationBookingData;
				if (isset ($_GET['type']) && $_GET['type'] == "Booking") {
					$smsBookingStatus = $bookingInfo['booking_status'];
					$smsbookingRefNumber = $bookingInfo['booking_ref_number'];
					$tempid = $this->SmsTemplate['FlightBookingFailed'];
					$Smsmessage = "Dear Travel Partner, Your Booking with Id " . $smsbookingRefNumber . " has Processing. In case of any issues, kindly contact the support team";
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
						if (empty ($checkSmssendingStatus)) {
							/* send_sms($MobileNumber, $Smsmessage, $tempid, $sms_type,array("service"=>"flight","booking_id"=>$bookingInfo['id'],"sending_type"=>"booking")); */
						}
						$checkEmailsendingStatus = $FlightModel->getData("logs_email", array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"), "id");
					}
					if (empty ($checkEmailsendingStatus)) {
						if ($smsBookingStatus == "Confirmed" || $smsBookingStatus == "Hold") {
							$TicketViewRequest = array(
								"BookingId" => array($bookingInfo['id']),
								"SearchTokenId" => $bookingInfo['tts_search_token'],
								"HtmlType" => "Ticket",
								"UserType" => "WebPartner",
								"ViewService" => "Email",
								"WithPrice" => 0,
								"WithAgencyDetail" => 1,
								"TicketInvoiceJourney" => $bookingInfo['trip_indicator'] == 1 ? "Onward" : "Return",
								"ViewSize" => "",
							);
							$url = $this->Services . 'generate-ticket-invoice';
							$response = RequestWithoutAuth($TicketViewRequest, $url);
							$emailMessage = $response['Result']['Html'];
							$EmailType = $sms_type;
							$EmailId = $this->web_partner_details['login_email'];
							send_email($EmailId, $Smsmessage, $emailMessage, $EmailType, null, array("service" => "flight", "booking_id" => $bookingInfo['id'], "sending_type" => "booking"));
						}
					}
				}
			}
			$pnrString = "";
			if (!empty ($Pnr)) {
				$pnrString = implode(',', $Pnr);
			}
			$ConfrimationData = array("ConfirmationBookingData" => $bookingConfrimationData, "childCount" => $childCount, "infantCount" => $infantCount, "airlineLogoClass" => $airlineLogoClass, "bookingRefNumber" => $BookingRefNumber, "pnr" => $Pnr, "bookingStatus" => $BookingStatus, "bookingStatusString" => implode(',', $BookingStatus), "bookingRefNumberString" => implode(',', $BookingRefNumber), "pnrString" => $pnrString, "FareBreakUpData" => $FareBreakUpDataArray, "TicketOption" => $TicketOption, "InvoiceOption" => $InvoiceOption, "paymentGatewayInfo" => $paymentGatewayInfo);
		}
		$data = [
			'title' => $this->title,
			'bookingConfrimationData' => $ConfrimationData,
			'view' => "Flight\Views\listing\booking-confirmation-page",
		];
		return view('template/sidebar-layout', $data);
	}
	function getInvoiceTicket()
	{
		$FlightModel = new FlightModel();
		if (!$this->request->isAJAX()) {
			$getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
			$getData = $this->request->getGet();
			$bookingRefNumbers = explode(",", $getData['booking_ref_number']);
			$bookingInfo = array();
			if ($bookingRefNumbers) {
				foreach ($bookingRefNumbers as $BookingRefNumber) {
					$bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,booking_source,tts_search_token");
					if ($bookingInfoData['booking_source'] == "Wl_b2b") {
						$getTicketUserType = array("PrintTicket" => "wl-agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-agent");
						$getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "AgencyInvoice");
					} else {
						$getTicketUserType = array("PrintTicket" => "wl-customer", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-customer");
						$getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
					}
					if ($bookingInfoData) {
						$bookingInfo[] = $bookingInfoData;
						$bookingInfoId[] = $bookingInfoData['id'];
						$tts_search_token = isset ($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
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
							"WithPrice" => isset ($getData['price']) ? 1 : 0,
							"WithAgencyDetail" => 0,
							"TicketInvoiceJourney" => isset ($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : 'Onward',
							"ViewSize" => "",
							"RequestBy"=>"WebPartner"
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
							"TicketInvoiceJourney" => isset ($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : 'Onward',
							"ViewSize" => "",
							"RequestBy"=>"WebPartner"
						);
					}
					$url = $this->Services . 'generate-wl-ticket-invoice';
					$response = RequestWithoutAuth($TicketViewRequest, $url);
					$data = [
						'title' => $this->title,
						'view' => "Flight\Views\listing\print_ticket",
						'data' => $response['Result']['Html'],
					];
					return view('Modules\Flight\Views\listing\print_ticket', $data);
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
						$bookingInfoData = $FlightModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, $this->web_partner_id, "id,booking_source,tts_search_token");
						if ($bookingInfoData['booking_source'] == "Wl_b2b") {
							$getTicketUserType = array("PrintTicket" => "wl-agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-agent", "EmailTicket" => "wl-agent");
						} else {
							$getTicketUserType = array("PrintTicket" => "wl-customer", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-customer", "EmailTicket" => "wl-customer");
						}
						if ($bookingInfoData) {
							$bookingInfo[] = $bookingInfoData;
							$bookingInfoId[] = $bookingInfoData['id'];
							$tts_search_token = isset ($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
						}
					}
					if ($bookingRefNumbers && $bookingInfo) {
						$TicketViewRequest = array(
							"BookingId" => $bookingInfoId,
							"SearchTokenId" => $tts_search_token,
							"HtmlType" => "Ticket",
							"UserType" => $getTicketUserType[$getData['type']],
							"ViewService" => "Email",
							"WithPrice" => 1,
							"WithAgencyDetail" => 0,
							"TicketInvoiceJourney" => "Both",
							"ViewSize" => "",
							"RequestBy"=>"WebPartner"
						);
						$url = $this->Services . 'generate-wl-ticket-invoice';
						$response = RequestWithoutAuth($TicketViewRequest, $url);
						$htmlView = $response['Result']['Html'];
						$subject = "Flight Ticket";
						$to = $getData['email'];
						$data = send_email($to, $subject, $htmlView);
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
		$bookingType = 'all';
		$source = '';
		$getData = $this->request->getGET();
		if (isset ($getData['key'])) {
			$list = $FlightBookingModel->search_bookings($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
		} else {
			if (isset ($_GET['bookingtype'])) {
				$bookingType = $this->request->getGET('bookingtype');
			}
			$list = $FlightBookingModel->flight_booking_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType);
		}
		$data = [
			'title' => $this->title,
			'view' => "Flight\Views\listing/flight-booking-list",
			"list" => $list,
			"search_bar_data" => $getData,
			'pager' => $FlightBookingModel->pager,
		];
		return view('template/sidebar-layout', $data);
	}
	function bookingDetails()
	{
		$uri = service('uri');
		$bookingReferenceNumber = $uri->getSegment(3);
		$FlightBookingModel = new FlightBookingModel();
		$amendmentList = array();
		$airlineLogoClass = "";
		$BookingDetail = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
		if ($BookingDetail) {
			$amendmentList = $FlightBookingModel->amendment_list($this->web_partner_id, $BookingDetail['id'], $BookingDetail['booking_source']);
			$BookingDetail['airlineLogoClass'] = $airlineLogoClass;
			$FareBreakUp = array();
			$DistributorInfo = array();
			$SupplierInfo = array();

			if (!empty ($BookingDetail['distributor_id']) && $BookingDetail['distributor_id'] > 0) {
				$DistributorModel = new DistributorModel();
				$DistributorInfo = $DistributorModel->get_distributor_detail($BookingDetail['distributor_id'], $this->web_partner_id);
			}

			if (!empty ($BookingDetail['supplier_id']) && $BookingDetail['supplier_id'] > 0) {
				$SupplierModel = new SupplierModel();
				$SupplierInfo = $SupplierModel->get_supplier_detail($BookingDetail['supplier_id'], $this->web_partner_id);
			}

			$webpartnerfareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
			if ($BookingDetail['booking_source'] == "Wl_b2b") {
				$fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
			} else {
				$fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
			}
			$couponAmount = 0;
			$couponInfo = json_decode($BookingDetail['coupon_info'], true);
			if (isset ($couponInfo['couponAmount']) && $BookingDetail['coupon_info'] != NULL && !empty ($BookingDetail['coupon_info'])) {
				$couponAmount = $couponInfo['couponAmount'];
			}
			$MealCharge = isset ($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
			$SeatCharge = isset ($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
			$BaggageCharge = isset ($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
			$markup = isset ($webpartnerfareBreakupArray['WebPMarkUp']) ? $webpartnerfareBreakupArray['WebPMarkUp'] : 0;
			$discount = isset ($webpartnerfareBreakupArray['WebPDiscount']) ? $webpartnerfareBreakupArray['WebPDiscount'] : 0;
			if (isset ($webpartnerfareBreakupArray['WebPDisplayMarkup']) && $webpartnerfareBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
				$WebPDisplayMarkup = $webpartnerfareBreakupArray['WebPDisplayMarkup'];
				$addMarkupInServiceCharge = $markup;
			} else {
				$WebPDisplayMarkup = "in_tax";
				$addMarkupInTax = $markup;
			}
			$TotalAmount = round_value($fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
			if ($BookingDetail['booking_source'] == "Wl_b2b") {
				$TotalAmount = round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
			}
			$FareBreakUp = array(
				"FareBreakup" => array(
					"BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
					"Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
					"ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
					"MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
					"BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
					"CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Discount (-)"),
					/* "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"), */
					"TDS" => array("Value" => round_value($fareBreakupArray['TDS']), "LabelText" => "TDS (+)")
				),
				"TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
				"GSTDetails" => ($fareBreakupArray['GST']),
				"WebPMarkUp" => array("Value" => round_value($markup), "LabelText" => "Apply Mark Up"),
				"WebPDiscount" => array("Value" => round_value($discount), "LabelText" => "Apply Discount"),
				"WebPDisplayMarkup" => array("Value" => ucfirst(str_replace("_", " ", $WebPDisplayMarkup)), "LabelText" => "Apply Markup At"),
			);
			if ($couponAmount > 0) {
				$FareBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promocode Discount (-)");
			}
			$BookingDetail['FareBreakUp'] = $FareBreakUp;
			$BookingDetail['DistributorInfo'] = $DistributorInfo;
			$BookingDetail['SupplierInfo'] = $SupplierInfo;
			$data = [
				'title' => $this->title,
				'view' => "Flight\Views\listing/flight-booking-detail",
				"bookingDetail" => $BookingDetail,
				"amendmentList" => $amendmentList,
			];
			return view('template/sidebar-layout', $data);
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
			$airlineLogoClass = "";
			$BookingDetail['airlineLogoClass'] = $airlineLogoClass;
			$data = [
				'title' => $this->title,
				'view' => "Flight\Views\listing/flight-amendment-detail",
				"bookingDetail" => $BookingDetail,
			];
			return view('template/sidebar-layout', $data);
		} else {
			return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
		}
	}


	public function amendmentStatusChange()
	{
		$validate = new Validation();
		$rules = $this->validate($validate->amendment_status);
		if (!$rules) {
			$errors = $this->validator->getErrors();
			$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
			return $this->response->setJSON($data_validation);
		} else {
			$data = $this->request->getPost();
			$FlightBookingModel = new FlightBookingModel();
			$amendment_id = dev_decode($data['amendment_id']);
			if ($data['status'] == "approved") {
				$AmendmentDetail = $FlightBookingModel->flight_amendment_detail($this->web_partner_id, $amendment_id);
				if ($AmendmentDetail['amendment_type'] == "cancellation") {
					$cancelledPaxIds = explode(",", $AmendmentDetail['pax_id']);
					$flightBookingStatus = "PartialCancelled";
					$updateflightBookingData = array();
					$bookingTravelInfoStatus = array();
					$bookingTravelInfo = array();
					foreach ($cancelledPaxIds as $cancelledPaxId) {
						$paxUpdatedata = array();
						$paxUpdatedata['booking_status'] = "Cancelled";
						$FlightBookingModel->updateData("flight_booking_travelers", array("id" => $cancelledPaxId), $paxUpdatedata);
					}
					$bookingInfo = $FlightBookingModel->getBookingConfirmationData($AmendmentDetail['booking_ref_no']);
					$bookingTravelInfo = json_decode($bookingInfo['travelersInfo'], true);
					$bookingTravelInfoStatus = array_column($bookingTravelInfo, "booking_status");
					$bookingTravelInfoStatus = array_unique($bookingTravelInfoStatus);
					if (count($bookingTravelInfoStatus) == 1 && $bookingTravelInfoStatus[0] == "Cancelled") {
						$flightBookingStatus = "Cancelled";
					}
					$updateflightBookingData['booking_status'] = $flightBookingStatus;
					$updateflightBookingData['webpartner_update_ticket_by'] = json_encode(array("first_name" => admin_cookie_data()['admin_user_details']['first_name'], "last_name" => admin_cookie_data()['admin_user_details']['last_name'], "StaffId" => $this->user_id));
					if ($updateflightBookingData) {
						$FlightBookingModel->updateData("flight_booking_list", array("id" => $AmendmentDetail['booking_ref_no']), $updateflightBookingData);
					}
				}
			}
			$updateData['amendment_status'] = $data['status'];
			$updateData['remark_from_web_partner'] = $data['admin_remark'];
			$updateData['agent_staff_id'] = $this->user_id;
			$updateData['modified'] = create_date();
			$update = $FlightBookingModel->updateData("flight_amendment", array("id" => $amendment_id, "web_partner_id" => $this->web_partner_id), $updateData);
			if ($update) {
				$message = array("StatusCode" => 0, "Message" => "Amendment status successfully changed", "Class" => "success_popup", "Reload" => "true");
			} else {
				$message = array("StatusCode" => 2, "Message" => "Amendment status not changed successfully", "Class" => "error_popup", "Reload" => "true");
			}
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
		}
	}

	function amendmentDetail()
	{
		$uri = service('uri');
		$amendmentId = $uri->getSegment(3);
		$amendmentId = dev_decode($amendmentId);
		$FlightBookingModel = new FlightBookingModel();
		$AmendmentDetail = $FlightBookingModel->flight_amendment_detail($this->web_partner_id, $amendmentId);
		$bookingReferenceNumber = $AmendmentDetail['booking_ref_number'];
		if ($amendmentId && $AmendmentDetail) {
			$BookingDetail = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $bookingReferenceNumber, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);
			$airlineLogoClass = "";
			$DistributorInfo = array();
			$SupplierInfo = array();
			$BookingDetail['airlineLogoClass'] = $airlineLogoClass;
			$webpartnerBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
			if ($BookingDetail['booking_source'] == "Wl_b2b") {
				$fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
			} else if ($BookingDetail['booking_source'] == "Wl_b2c") {
				$fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
			}
			if (!empty ($BookingDetail['distributor_id']) && $BookingDetail['distributor_id'] > 0) {
				$DistributorModel = new DistributorModel();
				$DistributorInfo = $DistributorModel->get_distributor_detail($BookingDetail['distributor_id'], $this->web_partner_id);
			}

			if (!empty ($BookingDetail['supplier_id']) && $BookingDetail['supplier_id'] > 0) {
				$SupplierModel = new SupplierModel();
				$SupplierInfo = $SupplierModel->get_supplier_detail($BookingDetail['supplier_id'], $this->web_partner_id);
			}
			$MealCharge = isset ($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
			$SeatCharge = isset ($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
			$BaggageCharge = isset ($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
			$markup = isset ($webpartnerBreakupArray['WebPMarkUp']) ? $webpartnerBreakupArray['WebPMarkUp'] : 0;
			$discount = isset ($webpartnerBreakupArray['WebPDiscount']) ? $webpartnerBreakupArray['WebPDiscount'] : 0;
			if (isset ($webpartnerBreakupArray['WebPDisplayMarkup']) && $webpartnerBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
				$WebPDisplayMarkup = $webpartnerBreakupArray['WebPDisplayMarkup'];
				$addMarkupInServiceCharge = $markup;
			} else {
				$WebPDisplayMarkup = "in_tax";
				$addMarkupInTax = $markup;
			}
			$couponAmount = 0;
			$couponInfo = json_decode($BookingDetail['coupon_info'], true);
			if (isset ($couponInfo['couponAmount']) && $BookingDetail['coupon_info'] != NULL && !empty ($BookingDetail['coupon_info'])) {
				$couponAmount = $couponInfo['couponAmount'];
			}
			$TDS = 0;
			if (isset ($fareBreakupArray['TDS'])) {
				$TDS = $fareBreakupArray['TDS'];
			}
			$TotalAmount = round_value($fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
			if ($BookingDetail['booking_source'] == "Wl_b2b") {
				$TotalAmount = round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge - $couponAmount);
			}
			$FareBreakUp = array(
				"FareBreakup" => array(
					"BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
					"Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
					"ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Other & Service Charges"),
					"MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
					"BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
					"CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Discount(-)"),
					/* "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"), */
					"TDS" => array("Value" => round_value($TDS), "LabelText" => "TDS (+)")
				),
				"TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
				"GSTDetails" => ($fareBreakupArray['GST']),
				"WebPMarkUp" => array("Value" => round_value($markup), "LabelText" => "Apply Mark Up"),
				"WebPDiscount" => array("Value" => round_value($discount), "LabelText" => "Apply Discount"),
				"WebPDisplayMarkup" => array("Value" => ucfirst(str_replace("_", " ", $WebPDisplayMarkup)), "LabelText" => "Apply Markup At"),
			);
			if ($couponAmount > 0) {
				$FareBreakUp['FareBreakup']['Promocode'] = array("Value" => round_value($couponAmount), "LabelText" => "Promocode Discount (-)");
			}
			$BookingDetail['FareBreakUp'] = $FareBreakUp;
			$BookingDetail['DistributorInfo'] = $DistributorInfo;
			$BookingDetail['SupplierInfo'] = $SupplierInfo;
			$data = [
				'title' => $this->title,
				'view' => "Flight\Views/flightAmendment/flight-amendment-details",
				"bookingDetail" => $BookingDetail,
				"amendmentDetail" => $AmendmentDetail
			];
			return view('template/sidebar-layout', $data);
		} else {
			return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
		}
	}

	function flightAmendmentCancellationCharge()
	{
		$validate = new Validation();
		$rules = $this->validate($validate->amendment_refund_validation);
		if (!$rules) {
			$message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
		} else {
			$data = $this->request->getPost();
			$FlightAmendmentModel = new FlightAmendmentModel();
			$amendment_id = dev_decode($data['amendment_id']);
			$FlightAmendmentDetail = $FlightAmendmentModel->flight_amendment_detail_by_id($amendment_id, $this->web_partner_id);
			if ($FlightAmendmentDetail) {
				$flight_booking_id = $FlightAmendmentDetail['booking_ref_no'];
				$amendmentpaxIdArray = explode(",", $FlightAmendmentDetail['pax_id']);
				$FlightTravellerDetails = $FlightAmendmentModel->flight_booking_travelers_detail($flight_booking_id, $amendmentpaxIdArray);
				if ($FlightTravellerDetails) {
					$amendmentpaxIdArray = explode(",", $FlightAmendmentDetail['pax_id']);
					$TravellersChargeInfo = $data['charge'];
					$totalRefundAmount = 0;
					$totalSsrReturnAmount = 0;
					if (isset ($FlightAmendmentDetail['wl_agent_id']) && $FlightAmendmentDetail['wl_agent_id'] > 0) {
						$tableName = "agent";
						$user_id = $FlightAmendmentDetail['wl_agent_id'];
						$agentUserGstCode = $FlightAmendmentModel->agent_user_gst_state_code($tableName, $user_id, $this->web_partner_id);
					} else {
						$tableName = "customer";
						$user_id = $FlightAmendmentDetail['wl_customer_id'];
						$agentUserGstCode = "";
					}
					$paxsRefundChargeDetails = array();
					foreach ($FlightTravellerDetails as $paxiteratekey => $FlightTravellerDetail) {
						$refundAmount = 0;
						$OtherCharges = 0;
						$Discount = 0;
						$TDS = 0;
						$GSTAmount = 0;
						$AgentCommission = 0;
						$TDSReturn = 0;
						$TDSReturnidentifier = "no";
						if ($tableName == "agent") {
							$PaxAirlineFareCharges = json_decode($FlightTravellerDetail['agent_fare'], true);
						} else {
							$PaxAirlineFareCharges = json_decode($FlightTravellerDetail['customer_fare'], true);
						}
						/*  $PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax']  + $PaxAirlineFareCharges['ServiceCharges'] + $PaxAirlineFareCharges['BaggageCharges'] + $PaxAirlineFareCharges['MealCharges'] + $PaxAirlineFareCharges['SeatCharges']; */
						if (isset ($PaxAirlineFareCharges['OtherCharges']) && $PaxAirlineFareCharges['OtherCharges'] != null) {
							$OtherCharges = $PaxAirlineFareCharges['OtherCharges'];
						}
						if (isset ($PaxAirlineFareCharges['Discount']) && $PaxAirlineFareCharges['Discount'] != null) {
							$Discount = $PaxAirlineFareCharges['Discount'];
						}
						if (isset ($PaxAirlineFareCharges['TDS']) && $PaxAirlineFareCharges['TDS'] != null && $tableName = "agent") {
							$TDS = $PaxAirlineFareCharges['TDS'];
						}
						if (isset ($PaxAirlineFareCharges['AgentCommission']) && $PaxAirlineFareCharges['AgentCommission'] != null) {
							$AgentCommission = $PaxAirlineFareCharges['AgentCommission'];
						}
						if (isset ($PaxAirlineFareCharges['GSTAmount']) && $PaxAirlineFareCharges['GSTAmount'] != null) {
							$GSTAmount = $PaxAirlineFareCharges['GSTAmount'];
						}
						$PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax'] + $OtherCharges + $GSTAmount - $AgentCommission - $Discount;
						$paxKey = array_search($FlightTravellerDetail['id'], array_column($TravellersChargeInfo, 'pax_id'));
						$paxChargeInfo = $TravellersChargeInfo[$paxKey];
						if (isset ($paxChargeInfo['tdsreturn']) && $paxChargeInfo['tdsreturn'] == "yes") {
							$TDSReturnidentifier = "yes";
							$TDSReturn = $TDS;
						}
						$GSTInfo = gst_calculate("Flight", $agentUserGstCode, $this->admin_comapny_detail['gst_state_code'], $paxChargeInfo['service_charge']);
						/*  $totalPaxRefundCharge = $paxChargeInfo['charge'] + $paxChargeInfo['service_charge'] + $paxChargeInfo['meal_charge'] + $paxChargeInfo['baggage_charge'] + $paxChargeInfo['seat_charge'] + $GSTInfo['TotalGSTAmount']; */
						$totalPaxRefundCharge = $paxChargeInfo['charge'] + $paxChargeInfo['service_charge'] + $GSTInfo['TotalGSTAmount'];
						$ssrReturnAmount = $paxChargeInfo['meal_charge'] + $paxChargeInfo['baggage_charge'] + $paxChargeInfo['seat_charge'];

						$refundAmount = round_value(($PaxTotalAmountAirlineFareCharges - $totalPaxRefundCharge + $TDSReturn));
						$totalRefundAmount = $totalRefundAmount + $refundAmount;
						$totalSsrReturnAmount = $totalSsrReturnAmount + $ssrReturnAmount;
						if ($totalRefundAmount < 0) {
							break;
						}
						$paxsRefundChargeDetails[$paxiteratekey] = array(
							"Charge" => $paxChargeInfo['charge'],
							"ServiceCharge" => $paxChargeInfo['service_charge'],
							"MealCharge" => $paxChargeInfo['meal_charge'],
							"BaggageCharge" => $paxChargeInfo['baggage_charge'],
							"SeatCharge" => $paxChargeInfo['seat_charge'],
							"PaxId" => $paxChargeInfo['pax_id'],
							"Refund" => $refundAmount + $ssrReturnAmount,
							"GST" => $GSTInfo,
							"TDSReturnIdentifier" => $TDSReturnidentifier,
						);
					}
					$updateData = array();
					if ($totalRefundAmount > 0) {
						foreach ($paxsRefundChargeDetails as $paxsRefundChargeDetail) {
							$PaxId = $paxsRefundChargeDetail['PaxId'];
							unset($paxsRefundChargeDetail['PaxId']);
							$updateData = array(
								"amendment_charges" => json_encode($paxsRefundChargeDetail),
								"amendment_type" => $FlightAmendmentDetail['amendment_type'],
								"amendment_id" => $amendment_id,

							);
							$update = $FlightAmendmentModel->updateWithTableData("flight_booking_travelers", $updateData, array("id" => $PaxId, "flight_booking_id" => $flight_booking_id));
						}
						$updateAmendmentData = array();
						$updateAmendmentData['agent_staff_id'] = $this->user_id;
						$updateAmendmentData['refund_status'] = "Open";
						$updateAmendmentData['refund_amount'] = $totalRefundAmount + $totalSsrReturnAmount;
						$updateAmendmentData['refund_date'] = create_date();
						$updateAmendmentData['modified'] = create_date();
						$update = $FlightAmendmentModel->updateWithTableData("flight_amendment", $updateAmendmentData, array("id" => $amendment_id));
						$message = array("StatusCode" => 0, "Message" => "Refund is Opened", "Class" => "success_popup", "Reload" => "true");
					} else {
						$message = array("StatusCode" => 2, "Message" => "Please check refund amount value is negative", "Class" => "error_popup", "Reload" => "true");
					}
				} else {
					$message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
				}
			} else {
				$message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
			}
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
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
						$tts_search_token = isset ($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
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
						"TicketInvoiceJourney" => isset ($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : $rtype,
						"ViewSize" => "",
					);


					 $url = $this->Services . 'generate-credit-note';

					 
					$response = RequestWithoutAuth($TicketViewRequest, $url);
					$data = [
						'title' => $this->title,
						'view' => "Flight\Views\listing\print_ticket",
						'data' => $response['Result']['Html'],
					];
					return view('template/sidebar-layout', $data);
				} else {
					return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
				}
			} else {
				return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
			}
		}
	}
	function flightRefundLists()
	{
		$FlightAmendmentModel = new FlightAmendmentModel();
		$getData = $this->request->getGET();
		if (isset ($getData['key'])) {
			$list = $FlightAmendmentModel->search_flight_refund_list($this->web_partner_id, $getData);
		} else {
			$source = $this->request->getGET('source');
			if ($source == 'dashboard') {
				$source = 'dashboard';
			}
			$bookingType = 'all';
			$list = $FlightAmendmentModel->flight_refund_list($this->web_partner_id, $bookingType, $source);
		}
		$data = [
			'title' => $this->title,
			'view' => "Flight\Views/flightRefund/flight-refund-list",
			"list" => $list,
			"search_bar_data" => $getData,
			'pager' => $FlightAmendmentModel->pager,
		];
		return view('template/sidebar-layout', $data);
	}

	function flightAmendmentLists()
	{
		$FlightAmendmentModel = new FlightAmendmentModel();
		$bookingType = 'all';
		$source = '';
		$getData = $this->request->getGET();

		if (isset ($getData['key'])) {
			$list = $FlightAmendmentModel->search_data($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
		} else {
			$source = $this->request->getGET('source');
			if ($source == 'dashboard') {
				$source = 'dashboard';
			}
			$bookingType = 'all';
			if ($this->request->getGET('bookingtype')) {
				$bookingType = $this->request->getGET('bookingtype');
			}
			$list = $FlightAmendmentModel->flight_amendment_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
		}


		$data = [
			'title' => $this->title,
			'view' => "Flight\Views/flightAmendment/flight-amendment-list",
			"list" => $list,
			"search_bar_data" => $getData,
			'pager' => $FlightAmendmentModel->pager,
		];
		return view('template/sidebar-layout', $data);
	}

	public function flightRefundClose()
	{
		$validate = new Validation();
		$rules = $this->validate($validate->refund_close_status);
		if (!$rules) {
			$errors = $this->validator->getErrors();
			$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
			return $this->response->setJSON($data_validation);
		} else {
			$data = $this->request->getPost();
			$FlightAmendmentModel = new FlightAmendmentModel();
			$CommonModel = new CommonModel();
			$amendment_id = dev_decode($data['amendment_id']);
			$FlightAmendmentDetail = $FlightAmendmentModel->flight_amendment_detail_by_id($amendment_id, $this->web_partner_id);

			if (isset ($FlightAmendmentDetail['wl_agent_id']) && $FlightAmendmentDetail['wl_agent_id'] > 0) {
				$AccountTableName = "agent_account_log";
				$key = "wl_agent_id";
				$account_log_id = $FlightAmendmentDetail['wl_agent_id'];
				$CompanyGSTInfo = $CommonModel->getDataRowType('agent', array("id" => $account_log_id, 'web_partner_id' => $this->web_partner_id), "gst_state_code");
			} else {
				$AccountTableName = "customer_account_log";
				$key = "customer_id";
				$account_log_id = $FlightAmendmentDetail['wl_customer_id'];
				$CompanyGSTInfo = "";
			}
			if (!empty ($FlightAmendmentDetail)) {
				$flight_booking_id = $FlightAmendmentDetail['booking_ref_no'];
				$BookingRefNumber = $CommonModel->getDataRowType('flight_booking_list', array("id" => $flight_booking_id, 'web_partner_id' => $FlightAmendmentDetail['web_partner_id']), "booking_ref_number")['booking_ref_number'];
				$amendmentpaxIdArray = explode(",", $FlightAmendmentDetail['pax_id']);
				$FlightTravellerDetails = $FlightAmendmentModel->flight_booking_travelers_detail($flight_booking_id, $amendmentpaxIdArray);
				if ($FlightTravellerDetails) {
					foreach ($FlightTravellerDetails as $TravellerDetail) {
						$refundAmount = 0;
						$OtherCharges = 0;
						$Discount = 0;
						$TDS = 0;
						$GSTAmount = 0;
						$AgentCommission = 0;
						$ssrReturnAmount = 0;
						$TDSReturn = 0;
						if ($key == "wl_agent_id") {
							$PaxAirlineFareCharges = json_decode($TravellerDetail['agent_fare'], true);
						} else {
							$PaxAirlineFareCharges = json_decode($TravellerDetail['customer_fare'], true);
						}
						if (isset ($PaxAirlineFareCharges['OtherCharges']) && $PaxAirlineFareCharges['OtherCharges'] != null) {
							$OtherCharges = $PaxAirlineFareCharges['OtherCharges'];
						}
						if (isset ($PaxAirlineFareCharges['Discount']) && $PaxAirlineFareCharges['Discount'] != null) {
							$Discount = $PaxAirlineFareCharges['Discount'];
						}
						if (isset ($PaxAirlineFareCharges['TDS']) && $PaxAirlineFareCharges['TDS'] != null) {
							$TDS = $PaxAirlineFareCharges['TDS'];
						}
						if (isset ($PaxAirlineFareCharges['AgentCommission']) && $PaxAirlineFareCharges['AgentCommission'] != null) {
							$AgentCommission = $PaxAirlineFareCharges['AgentCommission'];
						}
						if (isset ($PaxAirlineFareCharges['GSTAmount']) && $PaxAirlineFareCharges['GSTAmount'] != null) {
							$GSTAmount = $PaxAirlineFareCharges['GSTAmount'];
						}
						$paxChargeInfo = json_decode($TravellerDetail['amendment_charges'], true);
						if (isset ($paxChargeInfo['TDSReturnIdentifier']) && $paxChargeInfo['TDSReturnIdentifier'] == "yes") {
							$TDSReturn = $TDS;
						}
						/*  $PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax']  + $PaxAirlineFareCharges['ServiceCharges'] + $PaxAirlineFareCharges['BaggageCharges'] + $PaxAirlineFareCharges['MealCharges'] + $PaxAirlineFareCharges['SeatCharges'];
						 */
						$PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax'] + $OtherCharges + $GSTAmount - $AgentCommission - $Discount;
						/*             $totalPaxRefundCharge = $paxChargeInfo['Charge'] + $paxChargeInfo['ServiceCharge'] + $paxChargeInfo['MealCharge'] + $paxChargeInfo['BaggageCharge'] + $paxChargeInfo['SeatCharge'] + $paxChargeInfo['GST']['TotalGSTAmount']; */
						$totalPaxRefundCharge = $paxChargeInfo['Charge'] + $paxChargeInfo['ServiceCharge'] + $paxChargeInfo['GST']['TotalGSTAmount'];
						$ssrReturnAmount = ($paxChargeInfo['MealCharge'] + $paxChargeInfo['BaggageCharge'] + $paxChargeInfo['SeatCharge']);
						$refundAmount = round_value(($PaxTotalAmountAirlineFareCharges - $totalPaxRefundCharge + $TDSReturn)) + $ssrReturnAmount;
						$available_balance = $FlightAmendmentModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $FlightAmendmentDetail['web_partner_id']);
						/* invoice  Number Generate Number */
						$ServiceCharges = intval($paxChargeInfo['ServiceCharge']);
						$checkTaxableInvoce = checkTaxableNonTaxableINV($ServiceCharges, $CompanyGSTInfo, 'flight', 'RFND');

						$INVPrifix = getTaxableNonTaxableINVSuffix('RFND', $checkTaxableInvoce, 'flight');
						$financialYear = get_financial_year();
						$whereCondition['service'] = 'flight';
						$whereCondition['invoice_type'] = 'RFND';
						$whereCondition['financial_year'] = $financialYear;
						$otherParameter['financialYear'] = $financialYear;
						$otherParameter['service'] = 'flight';
						$otherParameter['invoice_type'] = 'RFND';
						$otherParameter['INVPrifix'] = $INVPrifix;
						$otherParameter['web_partner_id'] = $this->web_partner_id;
						$otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;

						$generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
						$InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
						$InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
						$InvoiceupdateData = $InvoiceInfoData['updateData'];
						$FlightAmendmentModel->updateWithTableData('invoice_suffix_list', $InvoiceupdateData, $whereCondition);

						/* invoice  Number Generate Number */

						$webPartnerBalance = 0;
						if (isset ($available_balance['balance'])) {
							$webPartnerBalance = $available_balance['balance'];
						}
						$WebPatnerAccountLogData['web_partner_id'] = $FlightAmendmentDetail['web_partner_id'];
						$WebPatnerAccountLogData['user_id'] = $this->user_id;
						$WebPatnerAccountLogData[$key] = $account_log_id;
						$WebPatnerAccountLogData['created'] = create_date();
						$WebPatnerAccountLogData['transaction_type'] = 'credit';
						$WebPatnerAccountLogData['action_type'] = 'refund';
						$WebPatnerAccountLogData['payment_mode'] = 'Account_Transfer';
						$WebPatnerAccountLogData['role'] = 'web_partner';
						$WebPatnerAccountLogData['credit'] = $refundAmount;
						$WebPatnerAccountLogData['service'] = "flight";
						$WebPatnerAccountLogData['service_log'] = json_encode(array("PaxName" => $TravellerDetail['title'] . " " . $TravellerDetail['first_name'] . " " . $TravellerDetail['last_name'], "TicketNo" => $TravellerDetail['ticket_number']));
						$WebPatnerAccountLogData['remark'] = "Refund for Ticket No - " . $TravellerDetail['ticket_number'] . " Name - " . $TravellerDetail['title'] . " " . $TravellerDetail['first_name'] . " " . $TravellerDetail['last_name'] . " Remark " . $FlightAmendmentDetail['remark_from_user'] . " Remark " . $FlightAmendmentDetail['remark_from_web_partner'] . " Remark " . $data['account_remark'];
						$WebPatnerAccountLogData['booking_ref_no'] = $flight_booking_id;
						$WebPatnerAccountLogData['extra_param'] = json_encode(['booking_ref_number' => $BookingRefNumber, 'pax_id' => $FlightAmendmentDetail['pax_id'], 'fareBreakUp' => $PaxAirlineFareCharges]);
						$WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
						$WebPatnerAccountLogData['invoice_number'] = $InvoiceNumber;
						$WebPatnerAccountLogData['balance'] = round_value(($webPartnerBalance + $refundAmount));
						$added_data_id = $FlightAmendmentModel->insertData($AccountTableName, $WebPatnerAccountLogData);
						$WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
						$FlightAmendmentModel->updateWithTableData($AccountTableName, $WebPatnerAccountLogDataUpdate, array("id" => $added_data_id));
						$FlightAmendmentModel->updateWithTableData("flight_booking_travelers", array("refund_account_id" => $added_data_id), array("id" => $TravellerDetail['id']));
					}

					$updateData['refund_status'] = "Close";
					$updateData['account_remark'] = $data['account_remark'];
					$updateData['agent_staff_id'] = $this->user_id;
					$updateData['modified'] = create_date();
					$updateData['refund_close_date'] = create_date();
					$update = $FlightAmendmentModel->updateWithTableData("flight_amendment", $updateData, array("id" => $amendment_id));
					if ($update) {
						$message = array("StatusCode" => 0, "Message" => "Refund  has been successfully done", "Class" => "success_popup", "Reload" => "true");
					} else {
						$message = array("StatusCode" => 2, "Message" => "Refund  has not been successfully done", "Class" => "error_popup", "Reload" => "true");
					}
				} else {

					$message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
				}
			} else {
				$message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
			}
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
		}
	}
	public function flight_markup()
	{
		if (permission_access_error("Flight", "flight_markup_list")) {
			$FlightMarkupModel = new FlightMarkupModel();

			if ($this->request->getGet() && $this->request->getGet('key')) {
				$lists = $FlightMarkupModel->search_data($this->request->getGet(), $this->web_partner_id);
			} else {
				$lists = $FlightMarkupModel->markup_list($this->web_partner_id);
			}
			$AgentClassModel = new AgentClassModel();
			$agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
			$agent_class_list = array_column($agent_class_list, 'class_name', 'id');
			$data = [
				'title' => $this->title,
				'list' => $lists,
				"agent_class_list" => $agent_class_list,
				'view' => "Flight\Views\Flight-markup-list",
				'pager' => $FlightMarkupModel->pager,
				'search_bar_data' => $this->request->getGet(),
			];

			return view('template/sidebar-layout', $data);
		}
	}

	public function flight_markup_view()
	{
		if (permission_access_error("Flight", "add_flight_markup")) {
			$AgentClassModel = new AgentClassModel();
			$FlightMarkupModel = new FlightMarkupModel();
			$data = [
				'title' => $this->title,
				'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id),
				'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
			];
			$add_blog_view = view('Modules\Flight\Views\add-flight-markup', $data);
			$data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
			return $this->response->setJSON($data);
		}
	}

	public function add_markup()
	{
		if (permission_access_error("Flight", "add_flight_markup")) {
			$data = $this->request->getPost();
			$validate = new Validation();
			if ($data['max_limit'] || $data['markup_type'] == "percent") {
				$validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			$validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['markup_for']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['markup_for']['errors'] = ['required' => 'Please select markup for'];

			if ($data['markup_for'] == "B2C") {
				unset($validate->flight_discount_markup_validation['agent_class.*']);
			}
			$rules = $this->validate($validate->flight_discount_markup_validation);

			if (!$rules) {
				$errors = $this->validator->getErrors();
				if (isset ($errors['agent_class.*'])) {
					$errors['agent_class[]'] = $errors['agent_class.*'];
					unset($errors['agent_class.*']);
				}
				if (isset ($errors['cabin_class.*'])) {
					$errors['cabin_class[]'] = $errors['cabin_class.*'];
					unset($errors['cabin_class.*']);
				}
				if (isset ($errors['is_domestic.*'])) {
					$errors['is_domestic[]'] = $errors['is_domestic.*'];
					unset($errors['is_domestic.*']);
				}
				if (isset ($errors['journey_type.*'])) {
					$errors['journey_type[]'] = $errors['journey_type.*'];
					unset($errors['journey_type.*']);
				}
				if (isset ($errors['faretype.*'])) {
					$errors['faretype[]'] = $errors['faretype.*'];
					unset($errors['faretype.*']);
				}
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightMarkupModel = new FlightMarkupModel();
				$temp_airline = $data['airline_code'];
				$temp_airline = explode('-', $temp_airline);
				$data['airline_code'] = $temp_airline[0];
				$data['airline_name'] = $temp_airline[1];
				$data['created'] = create_date();
				if ($data['travel_date_from']) {
					$data['travel_date_from'] = strtotime($data['travel_date_from']);
				}
				if ($data['travel_date_to']) {
					$data['travel_date_to'] = strtotime($data['travel_date_to']);
				}
				$data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
				$data['web_partner_id'] = $this->web_partner_id;
				$data['is_domestic'] = implode(',', $data['is_domestic']);
				$data['journey_type'] = implode(',', $data['journey_type']);
				$data['cabin_class'] = implode(',', $data['cabin_class']);
				$data['faretype'] = implode(',', $data['faretype']);

				$added_data = $FlightMarkupModel->insert($data);
				if ($added_data) {
					$message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Added", "Class" => "success_popup");
				} else {
					$message = array("StatusCode" => 2, "Message" => "Flight Markup not  Added", "Class" => "error_popup");
				}
				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function edit_markup_view()
	{
		if (permission_access_error("Flight", "edit_flight_markup")) {
			$id = dev_decode($this->request->uri->getSegment(3));
			$FlightMarkupModel = new FlightMarkupModel();
			$details = $FlightMarkupModel->markup_details($id, $this->web_partner_id);

			if (isset ($details['travel_date_from']) && $details['travel_date_from'] != '') {
				$details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
			}
			if (isset ($details['travel_date_to']) && $details['travel_date_to'] != '') {
				$details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
			}

			$AgentClassModel = new AgentClassModel();
			$details['is_domestic'] = explode(',', $details['is_domestic']);
			$details['journey_type'] = explode(',', $details['journey_type']);
			$details['cabin_class'] = explode(',', $details['cabin_class']);
			$details['faretype'] = explode(',', $details['faretype']);
			$data['web_partner_id'] = $this->web_partner_id;

			$data = [
				'title' => $this->title,
				'id' => $id,
				'details' => $details,
				'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
				'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
			];

			$blog_details = view('Modules\Flight\Views\edit-flight-markup', $data);
			$data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
			return $this->response->setJSON($data);
		}
	}

	public function edit_markup()
	{
		if (permission_access_error("Flight", "edit_flight_markup")) {
			$id = dev_decode($this->request->uri->getSegment(3));
			$data = $this->request->getPost();

			$validate = new Validation();
			if ($data['max_limit'] || $data['markup_type'] == "percent") {
				$validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			$validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['markup_for']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['markup_for']['errors'] = ['required' => 'Please select markup for'];

			if ($data['markup_for'] == "B2C") {
				unset($validate->flight_discount_markup_validation['agent_class.*']);
			}
			$rules = $this->validate($validate->flight_discount_markup_validation);
			if (!$rules) {
				$errors = $this->validator->getErrors();
				if (isset ($errors['agent_class.*'])) {
					$errors['agent_class[]'] = $errors['agent_class.*'];
					unset($errors['agent_class.*']);
				}
				if (isset ($errors['cabin_class.*'])) {
					$errors['cabin_class[]'] = $errors['cabin_class.*'];
					unset($errors['cabin_class.*']);
				}
				if (isset ($errors['is_domestic.*'])) {
					$errors['is_domestic[]'] = $errors['is_domestic.*'];
					unset($errors['is_domestic.*']);
				}
				if (isset ($errors['journey_type.*'])) {
					$errors['journey_type[]'] = $errors['journey_type.*'];
					unset($errors['journey_type.*']);
				}
				if (isset ($errors['faretype.*'])) {
					$errors['faretype[]'] = $errors['faretype.*'];
					unset($errors['faretype.*']);
				}
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightMarkupModel = new FlightMarkupModel();

				$temp_airline = $this->request->getPost('airline_code');
				$temp_airline = explode('-', $temp_airline);
				if (isset ($temp_airline[0])) {
					$data['airline_code'] = $temp_airline[0];
				}
				if (isset ($temp_airline[1])) {
					$data['airline_name'] = $temp_airline[1];
				}
				if ($data['travel_date_from']) {
					$data['travel_date_from'] = strtotime($data['travel_date_from']);
				}
				if ($data['travel_date_to']) {
					$data['travel_date_to'] = strtotime($data['travel_date_to']);
				}
				$data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

				$data['is_domestic'] = implode(',', $data['is_domestic']);
				$data['journey_type'] = implode(',', $data['journey_type']);
				$data['cabin_class'] = implode(',', $data['cabin_class']);
				$data['faretype'] = implode(',', $data['faretype']);
				$data['modified'] = create_date();

				$added_data = $FlightMarkupModel->where("id", $id)->where('web_partner_id', $this->web_partner_id)->set($data)->update();
				if ($added_data) {
					$message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Updated", "Class" => "success_popup");
				} else {
					$message = array("StatusCode" => 2, "Message" => "Flight Markup not  Updated", "Class" => "error_popup");
				}

				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function markup_status_change()
	{
		if (permission_access_error("Flight", "flight_markup_status")) {
			$validate = new Validation();
			$rules = $this->validate($validate->status);
			if (!$rules) {
				$errors = $this->validator->getErrors();
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightMarkupModel = new FlightMarkupModel();
				$ids = $this->request->getPost('checkedvalue');

				$data['status'] = $this->request->getPost('status');

				$update = $FlightMarkupModel->status_change($ids, $data, $this->web_partner_id);

				if ($update) {
					$message = array("StatusCode" => 0, "Message" => "FlightMarkup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
				} else {
					$message = array("StatusCode" => 2, "Message" => "FlightMarkup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
				}
				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function remove_markup()
	{
		if (permission_access_error("Flight", "delete_flight_markup")) {
			$FlightMarkupModel = new FlightMarkupModel();
			$ids = $this->request->getPost('checklist');
			$delete = $FlightMarkupModel->remove_markup($ids, $this->web_partner_id);
			if ($delete) {
				$message = array("StatusCode" => 0, "Message" => "FlightMarkup Successfully  Deleted", "Class" => "success_popup");
			} else {
				$message = array("StatusCode" => 2, "Message" => "FlightMarkup  not Deleted", "Class" => "error_popup");
			}
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
		}
	}


	public function flight_markup_details()
	{
		$id = dev_decode($this->request->uri->getSegment(3));
		$FlightMarkupModel = new FlightMarkupModel();
		$AgentClassModel = new AgentClassModel();
		$agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
		$agent_class_list = array_column($agent_class_list, 'class_name', 'id');
		$data = [
			'title' => $this->title,
			'id' => $id,
			'agent_class_list' => $agent_class_list,
			'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
			'details' => $FlightMarkupModel->markup_details($id, $this->web_partner_id),
		];
		$details = view('Modules\Flight\Views\markup-details', $data);
		$data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
		return $this->response->setJSON($data);
	}



	public function flight_discount(): string
	{
		if (permission_access_error("Flight", "flight_discount_list")) {
			$FlightDiscountModel = new FlightDiscountModel();

			if ($this->request->getGet() && $this->request->getGet('key')) {
				$lists = $FlightDiscountModel->search_data($this->request->getGet(), $this->web_partner_id);
			} else {
				$lists = $FlightDiscountModel->discount_list($this->web_partner_id);
			}
			$AgentClassModel = new AgentClassModel();
			$agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
			$agent_class_list = array_column($agent_class_list, 'class_name', 'id');
			$data = [
				'title' => $this->title,
				'list' => $lists,
				'agent_class_list' => $agent_class_list,
				'view' => "Flight\Views\Flight-discount-list",
				'pager' => $FlightDiscountModel->pager,
				'search_bar_data' => $this->request->getGet(),
			];

			return view('template/sidebar-layout', $data);
		}
	}

	public function flight_discount_view()
	{
		if (permission_access_error("Flight", "add_flight_discount")) {

			$AgentClassModel = new AgentClassModel();
			$FlightDiscountModel = new FlightDiscountModel();
			$data = [
				'title' => $this->title,
				'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
				'ApiFlighFareType' => $FlightDiscountModel->getApiFlighFareType(),
			];
			$add_blog_view = view('Modules\Flight\Views\add-flight-discount', $data);
			$data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
			return $this->response->setJSON($data);
		}
	}

	public function add_discount()
	{
		if (permission_access_error("Flight", "add_flight_discount")) {
			$data = $this->request->getPost();

			$validate = new Validation();
			if ($data['max_limit'] || $data['discount_type'] == "percent") {
				$validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			if ($data['extra_discount']) {
				$validate->flight_discount_markup_validation['extra_discount']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			$validate->flight_discount_markup_validation['discount_for']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['discount_for']['errors'] = ['required' => 'Please select discount for'];

			$validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';

			if ($data['discount_for'] == "B2C") {
				unset($validate->flight_discount_markup_validation['agent_class.*']);
			}

			$rules = $this->validate($validate->flight_discount_markup_validation);


			if (!$rules) {
				$errors = $this->validator->getErrors();
				if (isset ($errors['agent_class.*'])) {
					$errors['agent_class[]'] = $errors['agent_class.*'];
					unset($errors['agent_class.*']);
				}
				if (isset ($errors['cabin_class.*'])) {
					$errors['cabin_class[]'] = $errors['cabin_class.*'];
					unset($errors['cabin_class.*']);
				}
				if (isset ($errors['is_domestic.*'])) {
					$errors['is_domestic[]'] = $errors['is_domestic.*'];
					unset($errors['is_domestic.*']);
				}
				if (isset ($errors['journey_type.*'])) {
					$errors['journey_type[]'] = $errors['journey_type.*'];
					unset($errors['journey_type.*']);
				}
				if (isset ($errors['faretype.*'])) {
					$errors['faretype[]'] = $errors['faretype.*'];
					unset($errors['faretype.*']);
				}
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightDiscountModel = new FlightDiscountModel();

				$temp_airline = $data['airline_code'];
				$temp_airline = explode('-', $temp_airline);
				if (isset ($temp_airline[0])) {
					$data['airline_code'] = $temp_airline[0];
				}
				if (isset ($temp_airline[0])) {
					$data['airline_name'] = $temp_airline[1];
				}
				$data['created'] = create_date();

				if ($data['travel_date_from']) {
					$data['travel_date_from'] = strtotime($data['travel_date_from']);
				}
				if ($data['travel_date_to']) {
					$data['travel_date_to'] = strtotime($data['travel_date_to']);
				}



				$data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

				$data['web_partner_id'] = $this->web_partner_id;
				$data['is_domestic'] = implode(',', $data['is_domestic']);
				$data['journey_type'] = implode(',', $data['journey_type']);
				$data['cabin_class'] = implode(',', $data['cabin_class']);
				$data['faretype'] = implode(',', $data['faretype']);

				$added_data = $FlightDiscountModel->insert($data);

				if ($added_data) {
					$message = array("StatusCode" => 0, "Message" => "Flight Discount Successfully Added", "Class" => "success_popup");
				} else {
					$message = array("StatusCode" => 2, "Message" => "Flight Discount not  Added", "Class" => "error_popup");
				}

				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function edit_discount_view()
	{
		if (permission_access_error("Flight", "edit_flight_discount")) {
			$id = dev_decode($this->request->uri->getSegment(3));
			$FlightDiscountModel = new FlightDiscountModel();
			$details = $FlightDiscountModel->discount_details($id, $this->web_partner_id);
			$FlightDiscountModel = new FlightDiscountModel();

			$AgentClassModel = new AgentClassModel();
			if (isset ($details['travel_date_from']) && $details['travel_date_from'] != '') {
				$details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
			}

			if (isset ($details['travel_date_to']) && $details['travel_date_to'] != '') {
				$details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
			}

			$data['agent_class'] = explode(',', $details['agent_class']);

			$details['is_domestic'] = explode(',', $details['is_domestic']);
			$details['journey_type'] = explode(',', $details['journey_type']);
			$details['cabin_class'] = explode(',', $details['cabin_class']);
			$details['faretype'] = explode(',', $details['faretype']);

			$data = [
				'title' => $this->title,
				'id' => $id,
				'details' => $details,
				'ApiFlighFareType' => $FlightDiscountModel->getApiFlighFareType(),
				'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
			];
			$blog_details = view('Modules\Flight\Views\edit-flight-discount', $data);
			$data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
			return $this->response->setJSON($data);
		}
	}

	public function edit_discount()
	{
		if (permission_access_error("Flight", "edit_flight_discount")) {
			$data = $this->request->getPost();
			$id = dev_decode($this->request->uri->getSegment(3));

			$validate = new Validation();
			if ($data['max_limit'] || $data['discount_type'] == "percent") {
				$validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			if ($data['extra_discount']) {
				$validate->flight_discount_markup_validation['extra_discount']['rules'] = 'trim|numeric';
				$validate->flight_discount_markup_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
			}
			$validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['discount_for']['rules'] = 'trim|required';
			$validate->flight_discount_markup_validation['discount_for']['errors'] = ['required' => 'Please select discount for'];
			if ($data['discount_for'] == "B2C") {
				unset($validate->flight_discount_markup_validation['agent_class.*']);
			}
			$rules = $this->validate($validate->flight_discount_markup_validation);
			if (!$rules) {
				$errors = $this->validator->getErrors();
				if (isset ($errors['agent_class.*'])) {
					$errors['agent_class[]'] = $errors['agent_class.*'];
					unset($errors['agent_class.*']);
				}
				if (isset ($errors['cabin_class.*'])) {
					$errors['cabin_class[]'] = $errors['cabin_class.*'];
					unset($errors['cabin_class.*']);
				}
				if (isset ($errors['is_domestic.*'])) {
					$errors['is_domestic[]'] = $errors['is_domestic.*'];
					unset($errors['is_domestic.*']);
				}
				if (isset ($errors['journey_type.*'])) {
					$errors['journey_type[]'] = $errors['journey_type.*'];
					unset($errors['journey_type.*']);
				}
				if (isset ($errors['faretype.*'])) {
					$errors['faretype[]'] = $errors['faretype.*'];
					unset($errors['faretype.*']);
				}
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightDiscountModel = new FlightDiscountModel();

				$temp_airline = $this->request->getPost('airline_code');
				$temp_airline = explode('-', $temp_airline);
				if (isset ($temp_airline[0])) {
					$data['airline_code'] = $temp_airline[0];
				}
				if (isset ($temp_airline[1])) {
					$data['airline_name'] = $temp_airline[1];
				}
				if ($data['travel_date_from']) {
					$data['travel_date_from'] = strtotime($data['travel_date_from']);
				}
				if ($data['travel_date_to']) {
					$data['travel_date_to'] = strtotime($data['travel_date_to']);
				}

				$data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

				$data['is_domestic'] = implode(',', $data['is_domestic']);
				$data['journey_type'] = implode(',', $data['journey_type']);
				$data['cabin_class'] = implode(',', $data['cabin_class']);
				$data['faretype'] = implode(',', $data['faretype']);
				$data['modified'] = create_date();

				$added_data = $FlightDiscountModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($data)->update();
				if ($added_data) {
					$message = array("StatusCode" => 0, "Message" => "Flight Discount Successfully Updated", "Class" => "success_popup");
				} else {
					$message = array("StatusCode" => 2, "Message" => "Flight Discount not  Updated", "Class" => "error_popup");
				}
				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function flight_discount_details()
	{
		$id = dev_decode($this->request->uri->getSegment(3));

		$FlightDiscountModel = new FlightDiscountModel();

		$AgentClassModel = new AgentClassModel();
		$agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
		$agent_class_list = array_column($agent_class_list, 'class_name', 'id');
		$data = [
			'title' => $this->title,
			'id' => $id,
			"agent_class_list" => $agent_class_list,
			'details' => $FlightDiscountModel->discount_details($id, $this->web_partner_id),
		];
		$details = view('Modules\Flight\Views\discount-details', $data);
		$data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
		return $this->response->setJSON($data);
	}

	public function discount_status_change()
	{
		if (permission_access_error("Flight", "flight_discount_status")) {
			$validate = new Validation();
			$rules = $this->validate($validate->status);
			if (!$rules) {
				$errors = $this->validator->getErrors();
				$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
				return $this->response->setJSON($data_validation);
			} else {
				$FlightDiscountModel = new FlightDiscountModel();
				$ids = $this->request->getPost('checkedvalue');

				$data['status'] = $this->request->getPost('status');

				$update = $FlightDiscountModel->status_change($ids, $data, $this->web_partner_id);

				if ($update) {
					$message = array("StatusCode" => 0, "Message" => "FlightDiscount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
				} else {
					$message = array("StatusCode" => 2, "Message" => "FlightDiscount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
				}
				$this->session->setFlashdata('Message', $message);
				return $this->response->setJSON($message);
			}
		}
	}

	public function remove_discount()
	{
		if (permission_access_error("Flight", "flight_discount_status")) {
			$FlightDiscountModel = new FlightDiscountModel();
			$ids = $this->request->getPost('checklist');
			$delete = $FlightDiscountModel->remove_discount($ids, $this->web_partner_id);

			if ($delete) {
				$message = array("StatusCode" => 0, "Message" => "FlightDiscount Successfully  Deleted", "Class" => "success_popup");
			} else {
				$message = array("StatusCode" => 2, "Message" => "FlightDiscount  not Deleted", "Class" => "error_popup");
			}
			$this->session->setFlashdata('Message', $message);
			return $this->response->setJSON($message);
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
				if (isset ($errors['passengers.*'])) {
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
					/* if ($requestData['amendment_type'] == "cancellation") { 
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
													  } */

					$request = array(
						"BookingId" => $BookingDetail['id'],
						"Type" => $requestData['amendment_type'],
						"Remarks" => $requestData['remark'],
						"RequesterInfo" => array("RequesterId" => $this->web_partner_details['id'], "Requester" => "WebPartner"),
						"Sectors" => $sectors,
						"PaxId" => $PaxIds,
					);

					if ($AmendmentStatus != "") {
						$request['AmendmentStatus'] = $AmendmentStatus;
					}
					$service = "submitamendment";
					$url = $this->Services . $service;

					$CommonModel = new CommonModel();
					if (isset (session()->get('admin_user')['web_partner_id'])) {

						$results = $CommonModel->api_webpartner_setting(session()->get('admin_user')['web_partner_id']);
						if (is_array($results) && !empty ($results)) {
							$auth_data = [
								'Username' => $results['api_username'],
								'Password' => $results['api_password'],
								'Btype' => 'Web'
							];
						} else {
							$auth_data = [
								'Username' => null,
								'Password' => null,
								'Btype' => 'Web'
							];
						}
						defined('credential') || define('credential', $auth_data);
					}
					$response = Request($request, $url, $service);
					$EmailType = 'Amendment';
					$EmailId = $this->admin_comapny_detail['support_email'];
					if ($EmailId) {
						$data['paxs'] = $FlightBookingModel->pax_details($PaxIds);
						$data['company_name'] = $this->admin_comapny_detail['company_name'];
						$data['pnr'] = $BookingDetail['pnr'];
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

	function GetUpdateFlightTicketInfo()
	{
		$booking_refrence_number = $this->request->uri->getSegment(3);
		$FlightBookingModel = new FlightBookingModel();
		$amendmentList = array();
		$bookingInfo = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $booking_refrence_number, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);

		$flightSupplier = $FlightBookingModel->getData("offline_provider", array('web_partner_id' => $this->web_partner_id, 'flight_service' => 'active'), $singalRecord = 0, $whereApply = 1, 'id,supplier_name');
		if ($bookingInfo) {
			$amendmentList = $FlightBookingModel->amendment_list($this->web_partner_id, $bookingInfo['id'], $bookingInfo['booking_source']);
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

			$webpartnerBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
			if ($bookingInfo['booking_source'] == "Wl_b2b") {
				$fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
			} else if ($bookingInfo['booking_source'] == "Wl_b2c") {
				$fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
			}

			$MealCharge = isset ($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
			$SeatCharge = isset ($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
			$BaggageCharge = isset ($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
			$markup = isset ($webpartnerBreakupArray['WebPMarkUp']) ? $webpartnerBreakupArray['WebPMarkUp'] : 0;
			$discount = isset ($webpartnerBreakupArray['WebPDiscount']) ? $webpartnerBreakupArray['WebPDiscount'] : 0;
			if (isset ($webpartnerBreakupArray['WebPDisplayMarkup']) && $webpartnerBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
				$WebPDisplayMarkup = $webpartnerBreakupArray['WebPDisplayMarkup'];
				$addMarkupInServiceCharge = $markup;
			} else {
				$WebPDisplayMarkup = "in_tax";
				$addMarkupInTax = $markup;
			}
			$TDS = 0;
			if (isset ($fareBreakupArray['TDS']) && $bookingInfo['booking_source'] == "Wl_b2b") {
				$TDS = $fareBreakupArray['TDS'];
			}

			$FareBreakUp = array(
				"FareBreakup" => array(
					"BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
					"Taxes" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['Tax']), "LabelText" => "Taxes"),
					"ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['ServiceCharges']), "LabelText" => "Service Charges"),
					"MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
					"BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
					"CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Discount (-)"),
					"TDS" => array("Value" => round_value($TDS), "LabelText" => "TDS (+)")
				),
				"TotalAmount" => array("Value" => round_value($TDS + $fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Total Amount"),
				"GSTDetails" => ($fareBreakupArray['GST']),
				"WebPMarkUp" => array("Value" => round_value($markup), "LabelText" => "Apply Mark Up"),
				"WebPDiscount" => array("Value" => round_value($discount), "LabelText" => "Apply Discount"),
				"WebPDisplayMarkup" => array("Value" => ucfirst(str_replace("_", " ", $WebPDisplayMarkup)), "LabelText" => "Apply Markup At"),
			);
			$bookingInfo['FareBreakUp'] = $FareBreakUp;
			$data = [
				'title' => $this->title,
				'bookingDetail' => $bookingInfo,
				'flightSupplier' => $flightSupplier,
				"amendmentList" => $amendmentList,
				'view' => "Flight\Views\listing\\flight-booking-detail",
			];

			return view('template/sidebar-layout', $data);
		} else {
			$message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
			$this->session->setFlashdata('Message', $message);
			return redirect()->to(site_url('flight/bookings'));
		}
	}

	public function UpdateFlightTicketInfo()
	{
		$input = $this->request->getPost();
		$validate = new Validation();
		$validationConfigArray = $validate->ticket_update_validation($input);

		$this->validation->setRules($validationConfigArray);

		$rules = true;

		if ($input['booking_status'] != "Failed") {

			$rules = $this->validation->run($input);
		}

		if (!$rules) {

			$errors = $this->validation->getErrors();

			$data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

			return $this->response->setJSON($data_validation);
		} else {

			$FlightBookingModel = new FlightBookingModel();

			$booking_refrence_number = dev_decode($input['booking_ref_number']);

			$flight_booking_id = dev_decode($input['flight_booking_id']);

			$bookingInfo = $FlightBookingModel->flight_booking_detail($this->web_partner_id, $booking_refrence_number, $this->web_partner_details['id'], $this->web_partner_details['primary_user']);


			if ($bookingInfo && (isset ($bookingInfo['id']) && ($bookingInfo['id'] == $flight_booking_id))) {

				$checkbookingflighttime = checkbookingflighttime($bookingInfo['created']);

				if (isset ($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {

					$message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");

					$this->session->setFlashdata('Message', $message);

					$RedirectUrl = site_url('flight/bookings');

					$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

					return $this->response->setJSON($data_validation);
				}

				if ($bookingInfo['booking_source'] == "Wl_b2b") {
					$AccountTableName = "agent_account_log";
					$key = "wl_agent_id";
					$user = "Agent";
					$account_log_id = $bookingInfo['wl_agent_id'];
					$fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
				} else if ($bookingInfo['booking_source'] == "Wl_b2c") {
					$AccountTableName = "customer_account_log";
					$key = 'customer_id';
					$user = "Customer";
					$account_log_id = $bookingInfo['wl_customer_id'];
					$fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
				}

				if ($bookingInfo['webpartner_assign_user'] == $this->user_id || admin_cookie_data()['admin_user_details']['primary_user'] == 1) {

					if (isset ($input['refundbookingamount']) && $input['refundbookingamount'] == "yes") {

						if (((isset ($bookingInfo['booking_status']) && $bookingInfo['booking_status'] == 'Failed') || $input['booking_status'] == "Failed") && (isset ($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == 'Successful')) {

							$bookingWhereArray = array("booking_ref_no" => $bookingInfo['id'], 'service' => "flight", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']);
							$bookRefundWhereArray = array("booking_ref_no" => $bookingInfo['id'], 'service' => "flight", "action_type" => "bookingrefund", 'transaction_type' => "credit", 'web_partner_id' => $bookingInfo['web_partner_id']);
							$bookingWhereArray[$key] = $account_log_id;
							$bookRefundWhereArray[$key] = $account_log_id;
							$flighBookingAccountinfo = $FlightBookingModel->getData($AccountTableName, $bookingWhereArray, $singalRecord = 1, $whereApply = 1);

							$checkflighBookingRefund = $FlightBookingModel->getData($AccountTableName, $bookRefundWhereArray, $singalRecord = 1, $whereApply = 1);

							if (empty ($checkflighBookingRefund)) {

								if (!empty ($flighBookingAccountinfo) && $flighBookingAccountinfo) {

									$serviceLog = json_decode($flighBookingAccountinfo['service_log'], true);

									$extra_param = json_decode($flighBookingAccountinfo['extra_param'], true);

									if (empty ($serviceLog)) {

										$serviceLog = array();
									}

									if (empty ($extra_param)) {

										$extra_param = array();
									}

									$serviceLog['BookingRefrenceNumber'] = $booking_refrence_number;

									$web_partner_id = $flighBookingAccountinfo['web_partner_id'];

									$topupAmount = round_value(($flighBookingAccountinfo['debit']));

									$WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;

									$WebPartnerAccountLogData['user_id'] = $this->user_id;

									$WebPartnerAccountLogData[$key] = $account_log_id;

									$WebPartnerAccountLogData['created'] = create_date();

									$WebPartnerAccountLogData['transaction_type'] = "credit";

									$WebPartnerAccountLogData['payment_mode'] = "Account_Transfer";

									$WebPartnerAccountLogData['action_type'] = "bookingrefund";

									$WebPartnerAccountLogData['role'] = 'web_partner';

									$WebPartnerAccountLogData['remark'] = $input['remark'];

									$WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);

									$WebPartnerAccountLogData['extra_param'] = json_encode($extra_param);

									$WebPartnerAccountLogData['service'] = "flight";

									$WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];

									$WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);

									$available_balance = $FlightBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);

									if (!$available_balance) {

										$available_balance['balance'] = 0;
									}

									$WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));

									$WebPartnerAccountLogData['credit'] = $topupAmount;

									$added_data_id = $FlightBookingModel->insertData($AccountTableName, $WebPartnerAccountLogData);

									$updateData['acc_ref_number'] = reference_number($added_data_id);

									$FlightBookingModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);
								} else {

									$message = array("StatusCode" => 2, "Message" => "You are not eligible update ticket", "Class" => "error_popup");

									$this->session->setFlashdata('Message', $message);
									;

									$RedirectUrl = site_url('flight/bookings');

									$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

									return $this->response->setJSON($data_validation);
								}
							} else {

								$message = array("StatusCode" => 2, "Message" => "Refund for this booking has been done already", "Class" => "error_popup");

								$this->session->setFlashdata('Message', $message);
								;

								$RedirectUrl = site_url('flight/bookings');

								$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

								return $this->response->setJSON($data_validation);
							}
						} else {

							$message = array("StatusCode" => 2, "Message" => "You are not eligible update ticket", "Class" => "error_popup");

							$this->session->setFlashdata('Message', $message);

							$RedirectUrl = site_url('flight/bookings');

							$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

							return $this->response->setJSON($data_validation);
						}
					}

					if ($input['booking_status'] == "Confirmed") {
						$bookingWhereArray = array("booking_ref_no" => $bookingInfo['id'], 'service' => "flight", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']);
						$bookingWhereArray[$key] = $account_log_id;
						$flighBookingAccountinfo = $FlightBookingModel->getData($AccountTableName, $bookingWhereArray, $singalRecord = 1, $whereApply = 1);

						if (empty ($flighBookingAccountinfo)) {

							if (isset ($input['deductbookingamount']) && $input['deductbookingamount'] == "yes") {

								$web_partner_id = $bookingInfo['web_partner_id'];

								$travelersInfo = json_decode($bookingInfo['travelersInfo'], true);

								$available_balance = $FlightBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
								$FirstPaxInfo = current($travelersInfo);

								$serviceLog['PaxName'] = $FirstPaxInfo['first_name'] . " " . $FirstPaxInfo['last_name'] . " X " . count($travelersInfo);

								$serviceLog['Sector'] = $bookingInfo['origin'] . "-" . $bookingInfo['destination'];

								$serviceLog['TravelDate'] = $bookingInfo['departure_date'];

								$serviceLog['TicketNo'] = $bookingInfo['pnr'];

								$serviceLog['AirlineString'] = $bookingInfo['airline_code'];

								$extra_parm['booking_ref_number'] = $bookingInfo['booking_ref_number'];
								$extra_parm['webPartnerBreakUpInfo'] = json_decode($bookingInfo['web_partner_fare_break_up'], true);

								if ($bookingInfo['booking_source'] == "Wl_b2b") {
									$extra_parm['agentBreakUpInfo'] = json_decode($bookingInfo['agent_fare_break_up'], true);
									$extra_param['convenienceFee'] = (isset ($extra_param['agentBreakUpInfo']['convenienceFee'])) ? $extra_param['agentBreakUpInfo']['convenienceFee'] : 0;
								} else {
									$extra_parm['customerBreakUpInfo'] = json_decode($bookingInfo['customer_fare_break_up'], true);
									$extra_param['convenienceFee'] = (isset ($extra_param['customerBreakUpInfo']['convenienceFee'])) ? $extra_param['customerBreakUpInfo']['convenienceFee'] : 0;
								}

								if (isset ($available_balance['balance']) && $available_balance['balance'] >= $bookingInfo['total_price']) {

									$debitAmount = round_value(($bookingInfo['total_price']));

									$WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;

									$WebPartnerAccountLogData['user_id'] = $this->user_id;

									$WebPartnerAccountLogData[$key] = $account_log_id;

									$WebPartnerAccountLogData['created'] = create_date();

									$WebPartnerAccountLogData['transaction_type'] = "debit";

									$WebPartnerAccountLogData['action_type'] = "booking";

									$WebPartnerAccountLogData['payment_mode'] = "Account_Transfer";

									$WebPartnerAccountLogData['role'] = 'web_partner';

									$WebPartnerAccountLogData['remark'] = $input['remark'];

									$WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);

									$WebPartnerAccountLogData['extra_param'] = json_encode($extra_parm);

									$WebPartnerAccountLogData['service'] = "flight";

									$WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];

									$WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);

									$available_balance = $FlightBookingModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);

									if (!$available_balance) {

										$available_balance['balance'] = 0;
									}

									$WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] - $debitAmount));

									$WebPartnerAccountLogData['debit'] = $debitAmount;

									$added_data_id = $FlightBookingModel->insertData($AccountTableName, $WebPartnerAccountLogData);

									$updateData['acc_ref_number'] = reference_number($added_data_id, "Flight", $bookingInfo['is_domestic']);

									$FlightBookingModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);

									$input['payment_status'] = "Successful";
								} else {
									$message = array("StatusCode" => 2, "Message" => "$user have not enough balance", "Class" => "error_popup");

									$this->session->setFlashdata('Message', $message);

									$RedirectUrl = site_url('flight/get-update-flight-ticket-info/' . $booking_refrence_number);

									$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

									return $this->response->setJSON($data_validation);
								}
							} else {
								$message = array("StatusCode" => 2, "Message" => "Payment  have not done for this booking", "Class" => "error_popup");

								$this->session->setFlashdata('Message', $message);
								;

								$RedirectUrl = site_url('flight/get-update-flight-ticket-info/' . $booking_refrence_number);

								$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

								return $this->response->setJSON($data_validation);
							}
						}
					}

					$saveNoteData = array(

						"booking_ref_no" => $flight_booking_id,

						'agent_staff_id' => $this->user_id,

						'web_partner_id' => $this->web_partner_id,

						'wl_agent_staff_id' => $bookingInfo['wl_agent_staff_id'],

						'service_type' => "flight",

						'add_by' => "weppartner",

						'comment' => $input['remark'],

						'created' => create_date()

					);

					$saveNoteDataId = $FlightBookingModel->insertData('web_partner_booking_notes', $saveNoteData);



					foreach ($input['pax'] as $paxid => $pax) {

						$pnr = $pax['pnr'];

						$updatepaxdata = array(

							"ticket_number" => $pax['ticket_number'],

							"ticket_id" => $pax['ticket_id'],

							"booking_status" => $pax['booking_status'],

						);

						$FlightBookingModel->updateData("flight_booking_travelers", array("id" => $paxid), $updatepaxdata);
					}

					if ($input['booking_status'] == 'Confirmed' && $bookingInfo['booking_status'] != 'Confirmed') {

						$InvoiceNumber = "";
						/* invoice  Number Generate Number */
						$CommonModel = new CommonModel();
						$WebPartnerfareBreakup = $fareBreakupArray;
						// $WebpartnerGSTInfo  =  $CommonModel->getDataRowType("web_partner", array("id" => $bookingInfo['web_partner_id']), "company_gst_no");
						$checkTaxableInvoce = checkTaxableNonTaxableINV($WebPartnerfareBreakup, "", 'flight', 'INV');
						$INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'flight');
						$financialYear = get_financial_year();
						$whereCondition['service'] = 'flight';
						$whereCondition['invoice_type'] = 'INV';
						$whereCondition['financial_year'] = $financialYear;
						$otherParameter['financialYear'] = $financialYear;
						$otherParameter['service'] = 'flight';
						$otherParameter['invoice_type'] = 'INV';
						$otherParameter['INVPrifix'] = $INVPrifix;
						$otherParameter['web_partner_id'] = $this->web_partner_id;
						$otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
						$generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
						$InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
						$InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
						$InvoiceupdateData = $InvoiceInfoData['updateData'];
						$FlightBookingModel->updateData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
						$FlightBookingModel->updateData($AccountTableName, ['booking_ref_no' => $flight_booking_id, "service" => "flight", 'transaction_type' => "debit", 'action_type' => "booking"], ["invoice_number" => $InvoiceNumber]);
						/* invoice  Number Generate Number */

						//$irnGenerateRequestData  =  array(array("Service" => "Flight", "DocType" => "INV", "BookingRefNumber" => $bookingInfo['booking_ref_number']));
						//$GenerateIRNResponse = GenerateIRN_Request($this->IrNGenerateURl, $irnGenerateRequestData);
					}

					$superAdminStaffDetail = admin_cookie_data()['admin_user_details'];

					$updateFlightBookingData = array(

						"booking_status" => $input['booking_status'],

						"payment_status" => $input['payment_status'],

						"issue_supplier" => $input['supplier'],

						"pnr" => $pnr,

						"webpartner_assign_user" => null,

						'is_manual' => 1,

						"webpartner_update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),
					);

					$FlightBookingModel->updateData("flight_booking_list", array("id" => $flight_booking_id), $updateFlightBookingData);

					$message = array("StatusCode" => 1, "Message" => "Ticket Update successfully", "Class" => "success_popup");

					$this->session->setFlashdata('Message', $message);
					;

					$RedirectUrl = site_url('flight/details/' . $booking_refrence_number);

					$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

					return $this->response->setJSON($data_validation);
				} else {

					$message = array("StatusCode" => 2, "Message" => "You are not eligible update ticket", "Class" => "error_popup");

					$this->session->setFlashdata('Message', $message);
					;

					$RedirectUrl = site_url('flight/bookings');

					$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

					return $this->response->setJSON($data_validation);
				}
			} else {

				$message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");

				$this->session->setFlashdata('Message', $message);
				;

				$RedirectUrl = site_url('flight/bookings');

				$data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);

				return $this->response->setJSON($data_validation);
			}
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
					return view('template/sidebar-layout', $data);
				} else {
					return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
				}
			}
		} else {
			return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
		}
	}
}
