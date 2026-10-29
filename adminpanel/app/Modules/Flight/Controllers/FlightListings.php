<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightBookingModel;
use App\Modules\Flight\Models\FlightAmendmentModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class FlightListings extends BaseController
{
  
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight";
        if (permission_access_error("Flight", "Flight_Module")) {
            $this->web_partner_id = "";
        }
        $this->Services = API_REQUEST_URL . '/airservice/rest/';
        helper('Modules\Flight\Helpers\flight');

        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->company_name = admin_cookie_data()['admin_comapny_detail']['company_name'];
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
        $FlightBookingModel = new FlightBookingModel();
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
            foreach ($bookingIds as $bookingId) {
                $FareBreakUp = array();
                $bookingInfo = $FlightBookingModel->getBookingConfirmationData($bookingId);
                $rtype = $bookingInfo['trip_indicator'] == 1 ? "OB" : "IB";
                $searchData = json_decode($bookingInfo['search_request'], true);
                $childCount = $searchData['Child'];
                $infantCount = $searchData['Infant'];

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
                        if ($searchData['JourneyType'] == 2 && $bookingInfo['is_domestic']) {
                            /*    $TicketOption[] = "Onward";
                               $InvoiceOption[] = "Onward"; */
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
                            /*   $TicketOption[] = "Return";
                              $TicketOption[] = "Both";
                              $InvoiceOption[] = "Return"; */
                        }
                    }
                    $BookingRefNumber[] = $returnBookingRefNumber;
                }
                $fareBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                $MealCharge  = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                $SeatCharge  = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                $BaggageCharge  = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;

                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => custom_money_format(round_value($fareBreakupArray['BaseFare'])), "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => custom_money_format(round_value($fareBreakupArray['Tax'])), "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => custom_money_format(round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $markup)), "LabelText" => "Other & Service Charges"),
                            "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                            "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                            /* "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
                        "Discount" => array("Value" => custom_money_format(round_value($discount)), "LabelText" => "Agent Discount (-)"),
                        "PublishedPrice" => array("Value" => custom_money_format(round_value($fareBreakupArray['PublishedPrice'] + $markup - $discount+$MealCharge+$SeatCharge+$BaggageCharge)), "LabelText" => "Published Price"),
                        /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                        "CommEarned" => array("Value" => custom_money_format(round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'])), "LabelText" => "Comm Earned (-)"),
                        "TDS" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'])), "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice']+$MealCharge+$SeatCharge+$BaggageCharge)), "LabelText" => "Total Amount"),
                    "BookingId" => $bookingInfo['id'],
                    "WebPMarkUp" => custom_money_format(round_value($markup)),
                    "WebPDiscount" => custom_money_format(round_value($discount)),
                );
                $FareBreakUpDataArray[$rtype] = $FareBreakUp;
                $ConfirmationBookingData = array(
                    'Segments' => json_decode($bookingInfo['segments'], true),
                    "FareRule" => json_decode($bookingInfo['fare_rule'], true),
                    "IsRefundable" => $bookingInfo['is_refundable'],
                    "PaymentStatus" => $bookingInfo['payment_status'],
                    "BookingStatus" => $bookingInfo['booking_status'],
                    "BookingSource" => $bookingInfo['booking_source'],
                    "validating_airline" => $bookingInfo['validating_airline_code'],
                    "BookingRefNumber" => $bookingInfo['booking_ref_number'],
                    "BookingId" => $bookingInfo['id'],
                    "FareType" => $bookingInfo['fare_type'],
                    "TravelersInfo" => json_decode($bookingInfo['travelersInfo'], true),
                );


                $bookingConfrimationData[$rtype] = $ConfirmationBookingData;
            }
            $pnrString = "";
            if (!empty($Pnr)) {
                $pnrString = implode(',', $Pnr);
            }
            $ConfrimationData = array("ConfirmationBookingData" => $bookingConfrimationData, "childCount" => $childCount, "infantCount" => $infantCount, "airlineLogoClass" => $airlineLogoClass, "bookingRefNumber" => $BookingRefNumber, "pnr" => $Pnr, "bookingStatus" => $BookingStatus, "bookingStatusString" => implode(',', $BookingStatus), "bookingRefNumberString" => implode(',', $BookingRefNumber), "pnrString" => $pnrString, "FareBreakUpData" => $FareBreakUpDataArray, "TicketOption" => $TicketOption, "InvoiceOption" => $InvoiceOption);
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
        $FlightBookingModel = new FlightBookingModel();
        if (!$this->request->isAJAX()) {
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice", "SendEmail" => "SendEmail");
            $getData = $this->request->getGet();
            $bookingRefNumbers = explode(",", $getData['booking_ref_number']);
            $bookingInfo = array();
            if ($bookingRefNumbers) {
                foreach ($bookingRefNumbers as $BookingRefNumber) {
                    $bookingInfoData = $FlightBookingModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, "id,tts_search_token,trip_indicator");
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
                            "UserType" => "WebPartner",
                            "ViewService" => "View",
                            "WithPrice" => isset($getData['price']) ? 1 : 0,
                            "WithAgencyDetail" => isset($getData['agency_detail']) ? 1 : 0,
                            "TicketInvoiceJourney" => isset($getData['ticketinvoicejourney']) ? $getData['ticketinvoicejourney'] : $rtype,
                            "ViewSize" => "",
                        );
                    } else {
                        $TicketViewRequest = array(
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
                    }

                    //echo json_encode($TicketViewRequest);die;

                    $url = $this->Services . 'generate-ticket-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);


//                   pdf_lib($title="Ticket",$content=$response['Result']['Html'],$pdf_filename="Ticket",$view="");
//                    die;
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
                        $bookingInfoData = $FlightBookingModel->getBookingWithBookingRefNumberWithVariableFieldNameData($BookingRefNumber, "id,tts_search_token");
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
                            "HtmlType" => "SendEmail",
                            "UserType" => "WebPartner",
                            "ViewService" => "Email",
                            "WithPrice" => "1",
                            "WithAgencyDetail" => "1",
                            "TicketInvoiceJourney" => "Both",
                            "ViewSize" => "",
                        );
                        $url = $this->Services . 'generate-ticket-invoice';


                        $response = RequestWithoutAuth($TicketViewRequest, $url);
                        $htmlView = $response['Result']['Html'];
                        $bookingRefNumbers= implode(',',$bookingRefNumbers);
                        $subject = "Booking Confirmation Email {$bookingRefNumbers}";
                        $to = $getData['email'];

                        $attachment = "";
                        if (isset($response['Result']['Pdf'])) {
                            $PDFView = $response['Result']['Pdf'];
                            pdf_lib($title = "Ticket", $content = $PDFView, $pdf_filename = "Ticket", $view = "");
                            $attachment = APPPATH . 'ThirdParty/tcpdf/Ticket.pdf';
                        }

                        $data = send_email($to, $subject, $htmlView, $email_type = 'Flight Ticket', $attachment);


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


    function AssignUpdateflightTicket()
    {
        $bookingReferenceNumber = dev_decode($this->request->uri->getSegment(3));
        $FlightBookingModel = new FlightBookingModel();
        $BookingDetail = $FlightBookingModel->flight_booking_detail($bookingReferenceNumber);
        if ($BookingDetail) {
            $checkbookingflighttime = checkbookingflighttime($BookingDetail['created']);
            if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->redirect($this->request->getUserAgent()->getReferrer());
            }
            $updateData['assign_user'] = $this->user_id;
            $FlightBookingModel->updateData("flight_booking_list", array("booking_ref_number" => $bookingReferenceNumber), $updateData);
            $message = array("StatusCode" => 0, "Message" => "Ticket assign successfully", "Class" => "success_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->redirect($this->request->getUserAgent()->getReferrer());
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }

    function bookingLists()
    {
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $booking_list = $FlightBookingModel->search_bookings($getData);
        } else {
            $booking_list = $FlightBookingModel->flight_booking_list();
        }
        if (isset($getData['source']) && $getData['source'] =="dashboard") {
            $booking_list = $FlightBookingModel->flight_booking_all();
        }

        if ($booking_list) {
            foreach ($booking_list as $key => $list) {

                if ($list['is_domestic'] == 1) {
                    $airType = 'Domestic';
                } else {
                    $airType = 'International';
                }

                $segments = json_decode($list['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];
                $lead_pax = "";
                $travelersInfo = json_decode($list['travelersInfo'], true);

                $pax_count = count($travelersInfo);

                foreach ($travelersInfo as $pax) {
                    if ($pax['lead_pax'] == 1) {
                        $lead_pax = $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name'];
                        break;
                    }
                }

                $DateOfReturnJourneyShort = date('d M', strtotime($list['departure_date']));

                $DateOfJourney = date_created_format(strtotime($first_segment['Origin']['DepartTime']));
                $summery = $first_segment['Airline']['AirlineCode'] . '-' . $first_segment['Airline']['FlightNumber'] . ' ' . $list['origin'] . '-' . $list['destination'] . ' ' . $DateOfReturnJourneyShort . ', x ' . $pax_count;

                $today_date = date('Y-m-d');
                $today_date = date_create($today_date);
                $Journey_date = date_create($list['departure_date']);
                $diff = date_diff($today_date, $Journey_date);
                $timeToTravel = $diff->format("%a");

                $invert = $diff->invert;

                if ($invert == 1) {
                    $timeToTravel = 0;
                }

                if ($list['is_lcc'] == 1) {
                    $booking_list[$key]['is_lcc'] = 'LLC';
                } else {
                    $booking_list[$key]['is_lcc'] = 'GDS';
                }


                $booking_id[] = $list['id'];
                $booking_list[$key]['summery'] = $summery;
                $booking_list[$key]['departure_date'] = $DateOfJourney;
                $booking_list[$key]['firstPaxName'] = $lead_pax;

                $booking_list[$key]['airType'] = $airType;

                $booking_list[$key]['timeToTravel'] = $timeToTravel;

                $booking_list[$key]['created'] = date_created_format($list['created']);

            }
        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $booking_list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }



    function pending_bookings()
    {
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $booking_list = $FlightBookingModel->search_bookings($getData);
        } else {
            $booking_list = $FlightBookingModel->flight_booking_list();
        }
        if (isset($getData['source']) && $getData['source'] =="dashboard") {
            $booking_list = $FlightBookingModel->flight_booking_pending();
        }

        if ($booking_list) {
            foreach ($booking_list as $key => $list) {

                if ($list['is_domestic'] == 1) {
                    $airType = 'Domestic';
                } else {
                    $airType = 'International';
                }

                $segments = json_decode($list['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];
                $lead_pax = "";
                $travelersInfo = json_decode($list['travelersInfo'], true);

                $pax_count = count($travelersInfo);

                foreach ($travelersInfo as $pax) {
                    if ($pax['lead_pax'] == 1) {
                        $lead_pax = $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name'];
                        break;
                    }
                }

                $DateOfReturnJourneyShort = date('d M', strtotime($list['departure_date']));

                $DateOfJourney = date_created_format(strtotime($first_segment['Origin']['DepartTime']));
                $summery = $first_segment['Airline']['AirlineCode'] . '-' . $first_segment['Airline']['FlightNumber'] . ' ' . $list['origin'] . '-' . $list['destination'] . ' ' . $DateOfReturnJourneyShort . ', x ' . $pax_count;

                $today_date = date('Y-m-d');
                $today_date = date_create($today_date);
                $Journey_date = date_create($list['departure_date']);
                $diff = date_diff($today_date, $Journey_date);
                $timeToTravel = $diff->format("%a");

                $invert = $diff->invert;

                if ($invert == 1) {
                    $timeToTravel = 0;
                }

                if ($list['is_lcc'] == 1) {
                    $booking_list[$key]['is_lcc'] = 'LLC';
                } else {
                    $booking_list[$key]['is_lcc'] = 'GDS';
                }


                $booking_id[] = $list['id'];
                $booking_list[$key]['summery'] = $summery;
                $booking_list[$key]['departure_date'] = $DateOfJourney;
                $booking_list[$key]['firstPaxName'] = $lead_pax;

                $booking_list[$key]['airType'] = $airType;

                $booking_list[$key]['timeToTravel'] = $timeToTravel;

                $booking_list[$key]['created'] = date_created_format($list['created']);

            }
        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $booking_list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }



    function cancelled_bookings()
    {
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $booking_list = $FlightBookingModel->search_bookings($getData);
        } else {
            $booking_list = $FlightBookingModel->flight_booking_list();
        }
        if (isset($getData['source']) && $getData['source'] =="dashboard") {
            $booking_list = $FlightBookingModel->flight_booking_cancelled();
        }

        if ($booking_list) {
            foreach ($booking_list as $key => $list) {

                if ($list['is_domestic'] == 1) {
                    $airType = 'Domestic';
                } else {
                    $airType = 'International';
                }

                $segments = json_decode($list['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];
                $lead_pax = "";
                $travelersInfo = json_decode($list['travelersInfo'], true);

                $pax_count = count($travelersInfo);

                foreach ($travelersInfo as $pax) {
                    if ($pax['lead_pax'] == 1) {
                        $lead_pax = $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name'];
                        break;
                    }
                }

                $DateOfReturnJourneyShort = date('d M', strtotime($list['departure_date']));

                $DateOfJourney = date_created_format(strtotime($first_segment['Origin']['DepartTime']));
                $summery = $first_segment['Airline']['AirlineCode'] . '-' . $first_segment['Airline']['FlightNumber'] . ' ' . $list['origin'] . '-' . $list['destination'] . ' ' . $DateOfReturnJourneyShort . ', x ' . $pax_count;

                $today_date = date('Y-m-d');
                $today_date = date_create($today_date);
                $Journey_date = date_create($list['departure_date']);
                $diff = date_diff($today_date, $Journey_date);
                $timeToTravel = $diff->format("%a");

                $invert = $diff->invert;

                if ($invert == 1) {
                    $timeToTravel = 0;
                }

                if ($list['is_lcc'] == 1) {
                    $booking_list[$key]['is_lcc'] = 'LLC';
                } else {
                    $booking_list[$key]['is_lcc'] = 'GDS';
                }


                $booking_id[] = $list['id'];
                $booking_list[$key]['summery'] = $summery;
                $booking_list[$key]['departure_date'] = $DateOfJourney;
                $booking_list[$key]['firstPaxName'] = $lead_pax;

                $booking_list[$key]['airType'] = $airType;

                $booking_list[$key]['timeToTravel'] = $timeToTravel;

                $booking_list[$key]['created'] = date_created_format($list['created']);

            }
        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $booking_list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }


    public function get_seat_bookings()
    {
        $fare_detail_id = dev_decode($this->request->uri->getSegment(3));
        $FlightBookingModel = new FlightBookingModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) { 
            $booking_list = $FlightBookingModel->search_bookings_fare_detail($getData, $fare_detail_id);
        } else {
            $booking_list = $FlightBookingModel->flight_booking_list_fare_detail($fare_detail_id);
        }

        /* pr($booking_list);exit; */

        if ($booking_list) {
            foreach ($booking_list as $key => $list) {

                if ($list['is_domestic'] == 1) {
                    $airType = 'Domestic';
                } else {
                    $airType = 'International';
                }

                $segments = json_decode($list['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];
                $lead_pax = "";
                $travelersInfo = json_decode($list['travelersInfo'], true);

                $pax_count = count($travelersInfo);

                foreach ($travelersInfo as $pax) {
                    if ($pax['lead_pax'] == 1) {
                        $lead_pax = $pax['title'] . ' ' . $pax['first_name'] . ' ' . $pax['last_name'];
                        break;
                    }
                }

                $DateOfReturnJourneyShort = date('d M', strtotime($list['departure_date']));

                $DateOfJourney = date_created_format(strtotime($first_segment['Origin']['DepartTime']));
                $summery = $first_segment['Airline']['AirlineCode'] . '-' . $first_segment['Airline']['FlightNumber'] . ' ' . $list['origin'] . '-' . $list['destination'] . ' ' . $DateOfReturnJourneyShort . ', x ' . $pax_count;

                $today_date = date('Y-m-d');
                $today_date = date_create($today_date);
                $Journey_date = date_create($list['departure_date']);
                $diff = date_diff($today_date, $Journey_date);
                $timeToTravel = $diff->format("%a");

                $invert = $diff->invert;

                if ($invert == 1) {
                    $timeToTravel = 0;
                }

                if ($list['is_lcc'] == 1) {
                    $booking_list[$key]['is_lcc'] = 'LLC';
                } else {
                    $booking_list[$key]['is_lcc'] = 'GDS';
                }


                $booking_id[] = $list['id'];
                $booking_list[$key]['summery'] = $summery;
                $booking_list[$key]['departure_date'] = $DateOfJourney;
                $booking_list[$key]['firstPaxName'] = $lead_pax;

                $booking_list[$key]['airType'] = $airType;

                $booking_list[$key]['timeToTravel'] = $timeToTravel;

                $booking_list[$key]['created'] = date_created_format($list['created']);

            }
        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views\listing/flight-booking-list",
            "list" => $booking_list,
            "search_bar_data" => $getData,
            'pager' => $FlightBookingModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }


    function flight_booking_calender()
    {

        $getData = $this->request->getGet();
        $FlightBookingModel = new FlightBookingModel();

        $booking_list = $FlightBookingModel->getCalenderList($getData);
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
                    $return_data = $FlightBookingModel->getReturnBookingDetail($booking['tts_search_token'], $getData);
                    if (isset($return_data['segments'])) {
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
        return view('template/sidebar-layout', $data);
    }


    function bookingDetails()
    {
        $uri = service('uri');
        $bookingReferenceNumber = $uri->getSegment(3);
        $FlightBookingModel = new FlightBookingModel();
        $BookingDetail = $FlightBookingModel->flight_booking_detail($bookingReferenceNumber);
        if ($BookingDetail) {
            $searchData = json_decode($BookingDetail['search_request'], true);
            $childCount = $searchData['Child'];
            $infantCount = $searchData['Infant'];

            if ($BookingDetail['is_domestic']) {
                $airlineLogoClass = "domAirLogo";
            } else {
                $airlineLogoClass = "intAirLogo";
            }
            $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
            $BookingDetail['childCount'] = $childCount;
            $BookingDetail['infantCount'] = $infantCount;
            $BookingDetail['airlineLogoClass'] = $airlineLogoClass;
            $FareBreakUp = array();
            $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
            $super_admin_fare_break_up = json_decode($BookingDetail['super_admin_fare_break_up'], true);
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
                "TotalAmount" => array("Value" => custom_money_format(round_value($fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice']+ $MealCharge+$SeatCharge+ $BaggageCharge)), "LabelText" => "Total Amount"),
                "GSTDetails" => ($fareBreakupArray['GST']),
                "WebPMarkUp" => array("Value" => custom_money_format(round_value($markup)), "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => custom_money_format(round_value($discount)), "LabelText" => "Apply Discount"),
            );
            $BookingDetail['FareBreakUp'] = $FareBreakUp;
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views\listing/flight-booking-detail",
                "bookingDetail" => $BookingDetail,
            ];
            return view('template/sidebar-layout', $data);
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
                $BookingDetail = $FlightBookingModel->getBookingWithVariableFieldNameData($bookingReferenceNumber, $web_partner_id = null, "booking_ref_number");
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
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($web_partner_id = null, $bookingReferenceNumber);
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
                $BookingDetail = $FlightBookingModel->flight_amendment_itinerary_detail($web_partner_id = null, $bookingReferenceNumber);
                if ($bookingReferenceNumber && $BookingDetail) {
                    $results = $FlightBookingModel->api_webpartner_setting($BookingDetail['web_partner_id']);


                    $api_auth_data = [
                        'Username' => $results['api_username'],
                        'Password' => $results['api_password'],
                        'Btype' => 'Web'
                    ];


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
                    $request = array(
                        "BookingId" => $BookingDetail['id'],
                        "Type" => $requestData['amendment_type'],
                        "Remarks" => $requestData['remark'],
                        "RequesterInfo" => array("RequesterId" => $this->user_id, "Requester" => "SuperAdmin"),
                        "Sectors" => $sectors,
                        "PaxId" => $PaxIds,
                    );
                    if ($AmendmentStatus != "") {
                        $request['AmendmentStatus'] = $AmendmentStatus;
                    }
                    $service = "submitamendment";
                    $url = $this->Services . $service;
                    $response = Request($request, $url, $service, $api_auth_data);


                    $EmailType = 'Amendment';
                    $EmailId = null;//$this->admin_comapny_detail['support_email'];
                    if ($EmailId) {
                        $data['paxs'] = $FlightBookingModel->pax_details($PaxIds);
                        $data['company_name'] = $this->admin_comapny_detail['company_name'];
                        $data['pnr'] = $BookingDetail['pnr'];
                        $Smsmessage = "Amendment request successfully submitted";
                        $data['remark'] = $requestData['remark'];
                        $data['message'] = $Smsmessage;
                        $data['type'] = $requestData['amendment_type'];
                        #$message = view('Views/emails/amendment-emails', $data);
                        $param1 = 'ticketing';
                        //send_email($EmailId, $Smsmessage, $message, $EmailType, $attachment = null, $extraprameter = null, $param1);
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


    public function raiseAmendment_pre()
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
                $FlightBookingModel = new FlightBookingModel();
                $BookingRefNumber = $this->request->getPOST('booking_ref_number');
                $whereClauseBookingCheck = array("booking_ref_number" => $BookingRefNumber);
                $bookingInfo = $FlightBookingModel->getData("flight_booking_list", $whereClauseBookingCheck, "*");
                if ($BookingRefNumber && $bookingInfo) {

                    $results = $FlightBookingModel->api_webpartner_setting($bookingInfo['web_partner_id']);
                    if (is_array($results) && !empty($results)) {

                        $api_auth_data = [
                            'Username' => $results['api_username'],
                            'Password' => $results['api_password'],
                            'Btype' => 'Web'
                        ];

                        $request = array(
                            "BookingId" => $bookingInfo['id'],
                            "Type" => $this->request->getPOST('amendment_type'),
                            "Remarks" => $this->request->getPOST('remark'),
                            "RequesterInfo" => array("RequesterId" => $this->user_id, "Requester" => "SuperAdmin"),
                        );
                        $service = "submitamendment";
                        $url = $this->Services . $service;
                        $response = Request($request, $url, $service, $api_auth_data);
                        if ($response['Error']['ErrorCode'] == 0) {
                            $message = array("StatusCode" => 0, "Message" => "Amendment has  Successfully Submitted.", 'Class' => 'success_popup');
                        } else {
                            $message = array("StatusCode" => 2, "Message" => $response['Error']['ErrorMessage'], "Class" => "error_popup");
                        }
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
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


    function amendmentsDetails()
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
			$BookingDetail['airlineLogoClass'] = $airlineLogoClass;
			$webpartnerBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
			if ($BookingDetail['booking_source'] == "Wl_b2b") {
				$fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
			} else if ($BookingDetail['booking_source'] == "Wl_b2c") {
				$fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
			}
			$MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
			$SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
			$BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
			$markup = isset($webpartnerBreakupArray['WebPMarkUp']) ? $webpartnerBreakupArray['WebPMarkUp'] : 0;
			$discount = isset($webpartnerBreakupArray['WebPDiscount']) ? $webpartnerBreakupArray['WebPDiscount'] : 0;
			if (isset($webpartnerBreakupArray['WebPDisplayMarkup']) && $webpartnerBreakupArray['WebPDisplayMarkup'] == 'in_service_charge') {
				$WebPDisplayMarkup = $webpartnerBreakupArray['WebPDisplayMarkup'];
				$addMarkupInServiceCharge = $markup;
			} else {
				$WebPDisplayMarkup = "in_tax";
				$addMarkupInTax = $markup;
			}
			$TDS = 0;
			if (isset($fareBreakupArray['TDS'])) {
				$TDS = $fareBreakupArray['TDS'];
			}
			$FareBreakUp = array(
				"FareBreakup" => array(
					"BaseFare" => array("Value" => round_value($fareBreakupArray['BaseFare']), "LabelText" => "Base Fare"),
					"Taxes" => array("Value" => round_value($fareBreakupArray['Tax']), "LabelText" => "Taxes"),
					"ServiceAndOtherCharge" => array("Value" => round_value($fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges']), "LabelText" => "Other & Service Charges"),
					/*   "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
					"MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
					"BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
					/* "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
					/* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
					"CommEarned" => array("Value" => round_value($fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount']), "LabelText" => "Comm Earned (-)"),
					/* "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"), */
					"TDS" => array("Value" => round_value($TDS), "LabelText" => "TDS (+)")
				),
				"TotalAmount" => array("Value" => round_value($TDS + $fareBreakupArray['OfferedPrice'] + $MealCharge + $BaggageCharge + $SeatCharge), "LabelText" => "Total Amount"),
				"GSTDetails" => ($fareBreakupArray['GST']),
				"WebPMarkUp" => array("Value" => round_value($markup), "LabelText" => "Apply Mark Up"),
				"WebPDiscount" => array("Value" => round_value($discount), "LabelText" => "Apply Discount"),
				"WebPDisplayMarkup" => array("Value" => ucfirst(str_replace("_", " ", $WebPDisplayMarkup)), "LabelText" => "Apply Markup At"),
			);
			$BookingDetail['FareBreakUp'] = $FareBreakUp;
            $data = [
                'title' => $this->title,
                'view' => "Flight\Views/flightAmendment/flight-amendments-detail",
                "amendmentDetail" => $AmendmentDetail,
            ];
            prd($data);
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
					if (isset($FlightAmendmentDetail['wl_agent_id']) && $FlightAmendmentDetail['wl_agent_id'] > 0) {
						$tableName = "agent";
						$user_id = $FlightAmendmentDetail['wl_agent_id'];
						$agentUserGstCode = $FlightAmendmentModel->agent_user_gst_state_code($tableName, $user_id, $this->web_partner_id);
					} else {
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
						if (isset($PaxAirlineFareCharges['OtherCharges']) && $PaxAirlineFareCharges['OtherCharges'] != null) {
							$OtherCharges = $PaxAirlineFareCharges['OtherCharges'];
						}
						if (isset($PaxAirlineFareCharges['Discount']) && $PaxAirlineFareCharges['Discount'] != null) {
							$Discount = $PaxAirlineFareCharges['Discount'];
						}
						if (isset($PaxAirlineFareCharges['TDS']) && $PaxAirlineFareCharges['TDS'] != null) {
							$TDS = $PaxAirlineFareCharges['TDS'];
						}
						if (isset($PaxAirlineFareCharges['AgentCommission']) && $PaxAirlineFareCharges['AgentCommission'] != null) {
							$AgentCommission = $PaxAirlineFareCharges['AgentCommission'];
						}
						if (isset($PaxAirlineFareCharges['GSTAmount']) && $PaxAirlineFareCharges['GSTAmount'] != null) {
							$GSTAmount = $PaxAirlineFareCharges['GSTAmount'];
						}

						$PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax'] + $OtherCharges + $GSTAmount - $AgentCommission - $Discount;
						$paxKey = array_search($FlightTravellerDetail['id'], array_column($TravellersChargeInfo, 'pax_id'));
						$paxChargeInfo = $TravellersChargeInfo[$paxKey];
						if (isset($paxChargeInfo['tdsreturn']) && $paxChargeInfo['tdsreturn'] == "yes") {
							$TDSReturnidentifier = "yes";
							$TDSReturn = $TDS;
						}
						$GSTInfo = gst_calculate("Flight", $agentUserGstCode, super_admin_website_setting['gst_state_code'], $paxChargeInfo['service_charge']);
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
						$update = $FlightAmendmentModel->updateData($updateAmendmentData, array("id" => $amendment_id));
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
            $FlightAmendmentModel = new FlightAmendmentModel();
            $amendment_id = dev_decode($data['amendment_id']);
            if ($data['status'] == "approved") {
                $AmendmentDetail = $FlightAmendmentModel->flight_amendment_detail($amendment_id);
                if ($AmendmentDetail['amendment_type'] == "cancellation") {
                    $FlightBookingModel = new FlightBookingModel();
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
                    $updateflightBookingData['update_ticket_by'] = json_encode(array("first_name" => admin_cookie_data()['admin_user_details']['first_name'], "last_name" => admin_cookie_data()['admin_user_details']['last_name'], "StaffId" => $this->user_id));
                    if ($updateflightBookingData) {
                        $FlightBookingModel->updateData("flight_booking_list", array("id" => $AmendmentDetail['booking_ref_no']), $updateflightBookingData);
                    }
                }
            }
            $updateData['amendment_status'] = $data['status'];
            $updateData['remark_from_super_admin'] = $data['admin_remark'];
            $updateData['sup_staff_id'] = $this->user_id;
            $updateAmendmentData['modified'] = create_date();
            $update = $FlightAmendmentModel->updateData($updateData, array("id" => $amendment_id));
            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Amendment status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Amendment status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function flightAmendmentLists()
    {
        $getData = $this->request->getGet();

        $FlightAmendmentModel = new  FlightAmendmentModel();

        $booking_list = $FlightAmendmentModel->getAmendmentList($getData);
        if ($booking_list) {
            foreach ($booking_list as $key => $list) {
                $booking_list[$key]['created'] = date_created_format($list['created']);

                if ($list['Sector'] == 1) {
                    $booking_list[$key]['Sector'] = 'Domestic';
                } else {
                    $booking_list[$key]['Sector'] = 'International';
                }

                if ($list['Carrier'] == 1) {
                    $booking_list[$key]['Carrier'] = 'LLC';
                } else {
                    $booking_list[$key]['Carrier'] = 'GDS';
                }

                $segments = json_decode($list['segments'], true);
                $first_segment = reset($segments);
                $first_segment = $first_segment[0];

                $DateOfJourney = date_created_format(strtotime($first_segment['Origin']['DepartTime']));

                $booking_list[$key]['Departure'] = $DateOfJourney;

                unset($booking_list[$key]['segments']);
            }

        }

        $data = [
            'title' => $this->title,
            'view' => "Flight\Views/flightAmendment/flight-amendment-list",
            "list" => $booking_list,
            "search_bar_data" => $getData,
            'pager' => $FlightAmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }


    /* Refund Process Start */
    function flightRefundLists()
    {
        $FlightAmendmentModel = new FlightAmendmentModel();
        $getData = $this->request->getGET();
        $source = $this->request->getGET('source');
        if (isset($getData['key'])) {

            $list = $FlightAmendmentModel->search_flight_refund_list($getData);
        } else {



            $list = $FlightAmendmentModel->flight_refund_list();

        }
        if ($source == 'dashboard') {
            $list = $FlightAmendmentModel->flight_refund_list_all();
        }
        if (isset($getData['export_excel']) && $getData['export_excel'] == 1) {
            if (isset($getData['key'])) {

                $excellist = $FlightAmendmentModel->search_flight_refund_excel_list($getData);
            } else {
                $excellist = $FlightAmendmentModel->flight_refund_excel_list();
            }

            FlightListings::export_flight_refund_list($excellist);
        }


        // prd($list);exit;
        $data = [
            'title' => $this->title,
            'view' => "Flight\Views/flightRefund/flight-refund-list",
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
            $amendment_id = dev_decode($data['amendment_id']);
            $FlightAmendmentDetail = $FlightAmendmentModel->flight_amendment_detail_by_id($amendment_id);
            if ($FlightAmendmentDetail) {
                $flight_booking_id = $FlightAmendmentDetail['booking_ref_no'];
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
                        $PaxAirlineFareCharges = json_decode($TravellerDetail['fare'], true);
                        if (isset($PaxAirlineFareCharges['OtherCharges']) && $PaxAirlineFareCharges['OtherCharges'] != null) {
                            $OtherCharges = $PaxAirlineFareCharges['OtherCharges'];
                        }
                        if (isset($PaxAirlineFareCharges['Discount']) && $PaxAirlineFareCharges['Discount'] != null) {
                            $Discount = $PaxAirlineFareCharges['Discount'];
                        }
                        if (isset($PaxAirlineFareCharges['TDS']) && $PaxAirlineFareCharges['TDS'] != null) {
                            $TDS = $PaxAirlineFareCharges['TDS'];
                        }
                        if (isset($PaxAirlineFareCharges['AgentCommission']) && $PaxAirlineFareCharges['AgentCommission'] != null) {
                            $AgentCommission = $PaxAirlineFareCharges['AgentCommission'];
                        }
                        if (isset($PaxAirlineFareCharges['GSTAmount']) && $PaxAirlineFareCharges['GSTAmount'] != null) {
                            $GSTAmount = $PaxAirlineFareCharges['GSTAmount'];
                        }
                        $paxChargeInfo = json_decode($TravellerDetail['amendment_charges'], true);
                        if (isset($paxChargeInfo['TDSReturnIdentifier']) && $paxChargeInfo['TDSReturnIdentifier'] == "yes") {
                            $TDSReturn = $TDS;
                        }
                        /*  $PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax']  + $PaxAirlineFareCharges['ServiceCharges'] + $PaxAirlineFareCharges['BaggageCharges'] + $PaxAirlineFareCharges['MealCharges'] + $PaxAirlineFareCharges['SeatCharges'];
                        */
                        $PaxTotalAmountAirlineFareCharges = $PaxAirlineFareCharges['BaseFare'] + $PaxAirlineFareCharges['Tax'] + $PaxAirlineFareCharges['ServiceCharges'] + $OtherCharges + $GSTAmount - $AgentCommission - $Discount;
                        /*             $totalPaxRefundCharge = $paxChargeInfo['Charge'] + $paxChargeInfo['ServiceCharge'] + $paxChargeInfo['MealCharge'] + $paxChargeInfo['BaggageCharge'] + $paxChargeInfo['SeatCharge'] + $paxChargeInfo['GST']['TotalGSTAmount']; */
                        $totalPaxRefundCharge = $paxChargeInfo['Charge'] + $paxChargeInfo['ServiceCharge'] + $paxChargeInfo['GST']['TotalGSTAmount'];
                        $ssrReturnAmount = ($paxChargeInfo['MealCharge'] + $paxChargeInfo['BaggageCharge'] + $paxChargeInfo['SeatCharge']);
                        $refundAmount = round_value(($PaxTotalAmountAirlineFareCharges - $totalPaxRefundCharge + $TDSReturn)) + $ssrReturnAmount;
                        $webPartnerBalanceInfo = $FlightAmendmentModel->web_partner_available_balance($FlightAmendmentDetail['web_partner_id']);
                        $webPartnerBalance = 0;
                        if (isset($webPartnerBalanceInfo['balance'])) {
                            $webPartnerBalance = $webPartnerBalanceInfo['balance'];
                        }
                        $WebPatnerAccountLogData['web_partner_id'] = $FlightAmendmentDetail['web_partner_id'];
                        $WebPatnerAccountLogData['user_id'] = $this->user_id;
                        $WebPatnerAccountLogData['payment_mode'] = 'Deposit';
                        $WebPatnerAccountLogData['created'] = create_date();
                        $WebPatnerAccountLogData['transaction_type'] = 'credit';
                        $WebPatnerAccountLogData['action_type'] = 'refund';
                        $WebPatnerAccountLogData['role'] = 'super_admin';
                        $WebPatnerAccountLogData['credit'] = $refundAmount;
                        $WebPatnerAccountLogData['service'] = "flight";
                        $WebPatnerAccountLogData['service_log'] = json_encode(array("PaxName" => $TravellerDetail['title'] . " " . $TravellerDetail['first_name'] . " " . $TravellerDetail['last_name'], "TicketNo" => $TravellerDetail['ticket_number']));
                        $WebPatnerAccountLogData['remark'] = "Refund for Ticket No - " . $TravellerDetail['ticket_number'] . " Name - " . $TravellerDetail['title'] . " " . $TravellerDetail['first_name'] . " " . $TravellerDetail['last_name'] . " Remark " . $FlightAmendmentDetail['remark_from_web_partner'] . " Remark " . $FlightAmendmentDetail['remark_from_super_admin'] . " Remark " . $data['account_remark'];
                        $WebPatnerAccountLogData['booking_ref_no'] = $flight_booking_id;
                        $WebPatnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                        $WebPatnerAccountLogData['balance'] = round_value(($webPartnerBalance + $refundAmount));
                        $added_data_id = $FlightAmendmentModel->insertData('web_partner_account_log', $WebPatnerAccountLogData);
                        $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
                        $FlightAmendmentModel->updateWithTableData("web_partner_account_log", $WebPatnerAccountLogDataUpdate, array("id" => $added_data_id));
                        $FlightAmendmentModel->updateWithTableData("flight_booking_travelers", array("refund_account_id" => $added_data_id), array("id" => $TravellerDetail['id']));
                    }

                    $updateData['refund_status'] = "Close";
                    $updateData['account_remark'] = $data['account_remark'];
                    $updateData['sup_staff_id'] = $this->user_id;
                    $updateData['modified'] = create_date();
                    $updateData['refund_close_date'] = create_date();
                    $update = $FlightAmendmentModel->updateData($updateData, array("id" => $amendment_id));
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


    public function assign_update_flight_amendment()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightAmendmentModel = new  FlightAmendmentModel();
        $amendment_detail = $FlightAmendmentModel->amendment_detail($id);
        if ($amendment_detail) {
            $data['assign_user'] = $this->user_id;
            $data['assigned_time'] = create_date();
            $added_data = $FlightAmendmentModel->where("id", $id)->set($data)->update();

            $message = array("StatusCode" => 0, "Message" => "amendment successfully assigned", "Class" => "success_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->redirect(site_url('flight/flight-amendments'));
        } else {
            return $this->response->redirect(site_url('flight/error?errormessage=Request Not Allowed'));
        }
    }
}
