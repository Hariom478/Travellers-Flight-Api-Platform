<?php

namespace Modules\Holiday\Controllers;

use App\Modules\Holiday\Models\HolidayModel;
use App\Modules\Holiday\Models\AmendmentModel;
use App\Controllers\BaseController;
use Modules\Holiday\Config\Validation;


class HolidayBooking extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Holiday";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        helper('Modules\Holiday\Helpers\holiday');
        $this->folder_name = 'holiday';
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->Services = API_REQUEST_URL . '/holidayservice/rest/';
        /*if(permission_access_error("Holiday","Holiday_Module")) {

        }*/
    }

    public function auto_suggest()
    {
        $HolidayModel = new HolidayModel();
        $terms = $this->request->getGet('term');
        $suggest = $HolidayModel->auto_suggest($terms);
        $suggest_array = [];
        if ($suggest['themes']) {
            foreach ($suggest['themes'] as $data) {
                $data['tag'] = 'Themes';
                $data['value'] = trim($data['Name']);
                $suggest_array[] = $data;
            }
        }
        if ($suggest['destination']) {
            foreach ($suggest['destination'] as $data) {
                $data['tag'] = 'Destinations';
                $data['value'] = trim($data['Name']);
                $suggest_array[] = $data;
            }
        }

        if ($suggest['holiday_list']) {
            foreach ($suggest['holiday_list'] as $data) {
                $data['tag'] = 'Packages';
                $data['value'] = trim($data['Name']);
                $suggest_array[] = $data;
            }
        }
        echo json_encode($suggest_array);
    }


    public function index()
    {
        return redirect()->to(site_url('/dashboard'));

        /*  $data['name'] = 'Agency Name';
          $message = view('Views/emails/registration-success-email', $data);*/

        // send_email("praveen@traveltechnologysolution.com", $subject = "Agency Registration", $message, $email_type = 'Forgot Password', $attachment = null);

        //send_sms('8077281897','message',$tempid='22',$sms_type='user activation'); die;


        $HolidayModel = new HolidayModel();
        $offers_list = $HolidayModel->offers_list();

        $holiday_themes_list = GetRequest($this->Services . 'themes');
        $holiday_list = GetRequest($this->Services . 'holiday-list');
        if ($holiday_themes_list['Error']['ErrorCode'] == 0) {
            $holiday_themes_list = $holiday_themes_list['Result'];
        } else {
            $holiday_themes_list = null;
        }

        if ($holiday_list['Error']['ErrorCode'] == 0) {
            $holiday_list = $holiday_list['Result'];
        } else {
            $holiday_list = null;
        }


        $data = [
            'offers_list' => $offers_list,
            'holiday_list' => $holiday_list,
            'holiday_themes_list' => $holiday_themes_list,
            'title' => $this->title,
            'view' => "Holiday\Views/frentend\index",
        ];
        return view('template/home-layout', $data);
    }


    public function search()
    {

        $tag = $this->request->getGet('tag');
        $id = $this->request->getGet('id');
        $slug = $this->request->getGet('slug');
        $search_data = ['id' => $id, 'slug' => $slug, 'tag' => $tag];


        $data = [
            'search_data' => $search_data,
            'title' => $this->title,
            'view' => "Holiday\Views/frentend\holiday_result",
        ];
        
        return view('template/default-layout', $data);
    }

    public function holiday_result()
    {
        $id = $this->request->getGet('id');
        $tag = $this->request->getGet('tag');
        $slug = $this->request->getGet('slug');
        $holiday_list = GetRequest($this->Services . 'search?id=' . $id . '&tag=' . $tag);
        if ($holiday_list['Error']['ErrorCode'] == 0) {
            $holiday_list['TotalResults'] = count($holiday_list['Result']['Holidays']);
        }
        return $this->response->setJSON($holiday_list);
    }


    public function holiday_details()
    {
        $id = $this->request->getGet('id');
        $slug = $this->request->getGet('slug');
        $holiday_list = GetRequest($this->Services . 'holiday-details?slug=' . $slug);
        $holiday_list['HolidayType'] = "Standard";


        $holiday_data['holiday_list'] = $holiday_list;
        $data_calender = view('Modules\Holiday\Views/frentend\full-calender', $holiday_data);


        $data = [
            'calender' => $data_calender,
            'title' => $this->title,
            'holiday_list' => $holiday_list,
            'view' => "Holiday\Views/frentend\holiday_details",
        ];
        return view('template/default-layout', $data);
    }

    public function check_search_validation()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->search_validation);
 
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 0, "Message" => "");
            return $this->response->setJSON($message);
        }
    }

    public function holiday_query()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->query_validation);

        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();
            $HolidayModel = new HolidayModel();
            $data['ip_address'] = $this->request->getIPAddress();
            $data['web_partner_id'] = $this->web_partner_id;
            $data['created'] = create_date();
            $added_data = $HolidayModel->insertData('holiday_query', $data);
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "holiday query successfully added", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday query not added", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function fare_calender()
    {
        $data = $this->request->getPost();
        $holiday_list = GetRequest($this->Services . 'holiday-details?slug=' . $data['slug']);
        $holiday_list['HolidayType'] = $data["price_category"];
        $holiday_data['holiday_list'] = $holiday_list;
        $data_calender = view('Modules\Holiday\Views/frentend\full-calender', $holiday_data);

        if ($holiday_list['Error']['ErrorCode'] == 0) {
            $message = array("StatusCode" => 0, "Html_data" => $data_calender, "Message" => "", "Class" => "success_popup", "Reload" => "false");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Something went wrong oops!!", "Class" => "error_popup", "Reload" => "false");
        }

        return $this->response->setJSON($message);
    }

    public function holiday_block()
    {

        $validate = new Validation();
        $rules = $this->validate($validate->block_validation);

        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            $data = $this->request->getPost();
            $total_room = $data['txt_num_rooms'];

            $room_data = [];
            for ($rooms = 1; $rooms <= $total_room; $rooms++) {
                $total_adult = $data["txt_adults_{$rooms}"];
                $room_num_child = 0;
                $child_age = 0;
                $child_details = [];
                if ($data["txt_children_{$rooms}"] != 0) {

                    $room_num_child = $data["txt_children_{$rooms}"];
                    for ($child = 1; $child <= $room_num_child; $child++) {
                        $child_need_bed = 'no';
                        $child_age = $data["txt_childAge_{$rooms}_{$child}"];
                        if ($child_age > 2) {
                            $pax_type = "child";
                        } else {
                            $pax_type = "infant";
                        }

                        if (isset($data["need_bed_{$rooms}_{$child}"])) {
                            $child_need_bed = $data["need_bed_{$rooms}_{$child}"];
                            if ($child_need_bed == 1) {
                                $child_need_bed = "yes";
                            }
                        }
                        $child_details[] = [
                            'child_age' => $child_age,
                            'child_need_bed' => $child_need_bed,
                            'pax_type' => $pax_type
                        ];
                    }
                }

                $room_data[] = [
                    'total_adult' => $total_adult,
                    'total_child' => $room_num_child,
                    'child_details' => $child_details
                ];
            }

            $tts_holiday_category = $data['tts_holiday_category'];
            $travel_date = $data['travel_date'];
            $holiday_id = dev_decode($data['holiday_id']);
            $tts_price_key = $data['tts_price_key'];

            $request_data = [
                'SearchTokenId' => $data['SearchTokenId'],
                'UserIp' => $this->request->getIPAddress(),
                'HolidayId' => $holiday_id,
                'TravelDate' => $travel_date,
                'HolidayCategory' => $tts_holiday_category,
                'PriceId' => $tts_price_key,
                'RoomData' => $room_data
            ];


            $block_data = Request_holiday($request_data, $this->Services . 'block-holiday');

            $Redirect_Url = site_url("holiday-booking/booking-review?TokenId=" . $data['SearchTokenId']);
            $message = array("StatusCode" => 3, "Message" => "", "Redirect_Url" => $Redirect_Url, "Class" => "", "Reload" => "false");

            //$this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);

        }
    }

    public function booking_review()
    {
        $token = $this->request->getGet('TokenId');
        $request_data = [
            "SearchTokenId" => $token
        ];
        $details_data = Request_holiday($request_data, $this->Services . 'holiday-block-detail');
        $HolidayModel = new HolidayModel();
        $dial_code = $HolidayModel->get_dial_code();
        $data = [
            'dial_code' => $dial_code,
            'web_partner_details' => $this->web_partner_details,
            'title' => $this->title,
            'details' => $details_data,
            'view' => "Holiday\Views/frentend\booking-review",
        ];
        return view('template/default-layout', $data);
    }


    public function validate_travellers()
    {
        $data = $this->request->getPost();
        $validate = new Validation();
        $validationConfigArray = $validate->pax_validation($data);
        $this->validation->setRules($validationConfigArray);
        $rules = $this->validation->run($data);
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $response = HolidayBooking::savaData($data);
            return $this->response->setJSON($response);
        }
    }

    private function savaData($data)
    {

        $SearchTokenId = $data['SearchTokenId'];
        $HolidayModel = new HolidayModel();
        $verify_data = $HolidayModel->get_block_detail_data($SearchTokenId, $this->web_partner_id);

        if ($verify_data) {


            $detail = json_decode($verify_data['response'], true);
            $priceBreakup = $detail['PriceBreakup'];
            $total_price = $priceBreakup['OfferedPrice'] + $priceBreakup['TDS'];

            $TravelDate = $detail['TravelDate'];

            $insertData = array(
                "tts_search_token" => $SearchTokenId,
                "web_partner_id" => $this->web_partner_id,
                'package_details' => $verify_data['response'],
                'package_name' => $detail['Name'],
                'package_id' => $detail['id'],
                'package_category' => $detail['HolidayCategory'],
                'day_nights' => $detail['DaysNights'],
                "booking_ref_number" => '',
                "payment_mode" => "Online",
                "payment_status" => "Processing",
                "booking_status" => "Processing",
                "total_price" => $total_price,
                'gst_info' => isset($data['gst']) ? json_encode($data['gst']) : null,
                "super_admin_fare_break_up" => json_encode($detail['AdminPriceBreakup']),
                'web_partner_fare_break_up' => json_encode($priceBreakup),
                'agent_fare_break_up' => '',
                'book_request' => '',
                'booking_channel' => 'Desktop',
                'agent_staff_id' => $this->user_id,
                "created" => create_date(),
                'travel_date' => $TravelDate
            );

            $booking_id = $HolidayModel->insertData("holiday_booking_list", $insertData);

            $super_admin__booking_pre_fix_code = $HolidayModel->super_admin_booking_pre_fix_code()['pre_fix'];
            $booking_ref_number = $super_admin__booking_pre_fix_code . $booking_id;
            $booking_update_data = array(
                'booking_ref_number' => $booking_ref_number,
            );
            $HolidayModel->updateUserData('holiday_booking_list', ['id' => $booking_id], $booking_update_data);

            $insertPassengersData = [];
            $paxInfromation = $data['pax'];
            foreach ($paxInfromation as $key => $pax_details) {


                foreach ($pax_details as $key1 => $paxinfos) {

                    foreach ($paxinfos as $key2 => $paxinfo) {
                        if (isset($paxinfo['passport_no'])) {
                            $PassportNo = $paxinfo['passport_no'];
                        } else {
                            $PassportNo = NULL;
                        }
                        if (isset($paxinfo['passport_issue_date'])) {
                            $PassportIssueDate = $paxinfo['passport_issue_date'];
                        } else {
                            $PassportIssueDate = NULL;
                        }
                        if (isset($paxinfo['passport_expire_date'])) {
                            $PassportExpDate = $paxinfo['passport_expire_date'];
                        } else {
                            $PassportExpDate = NULL;
                        }
                        if (isset($paxinfo['pancard'])) {
                            $pancard = $paxinfo['pancard'];
                        } else {
                            $pancard = NULL;
                        }

                        if ($key == 0 && $key1 == "Adult" && $key2 == 1) {
                            $Lead = true;
                        } else {
                            $Lead = false;
                        }

                        if ($key1 == "Adult") {
                            $PaxType = 'Adult';
                            $Age = 0;
                        } else {
                            if ($paxinfo['child_age'] <= 2) {
                                $PaxType = 'Infant';
                                $Age = $paxinfo['child_age'];
                            } else {
                                $PaxType = 'Child';
                                $Age = $paxinfo['child_age'];
                            }

                        }


                        $insertPassengersData[] = array(
                            'holiday_booking_id' => $booking_id,
                            "title" => $paxinfo['title'],
                            "first_name" => $paxinfo['first_name'],
                            "last_name" => $paxinfo['last_name'],
                            "mobile_number" => $data['mobile_number'],
                            "email_id" => $data['email'],
                            "pax_type" => $PaxType,
                            "lead_pax" => $Lead,
                            "age" => $Age,
                            "passport_number" => $PassportNo,
                            "passport_issue_date" => $PassportIssueDate,
                            "passport_expiry_date" => $PassportExpDate,
                            "pan_number" => $pancard,
                            "dial_code" => $data['dial_code'],
                            "child_need_bed" => isset($paxinfo['child_need_bed']) ? $paxinfo['child_need_bed'] : null,
                        );
                    }
                }

            }

            $HolidayModel->insertBatchData("holiday_booking_travelers", $insertPassengersData);

            $paymentkey = dev_encode(json_encode(array('service' => 'holiday', 'booking_id' => $booking_id)));
            $url = site_url('payment/opt/') . $paymentkey;

            return array("StatusCode" => 3, "ErrorMessage" => "", "Redirect_Url" => $url);

        } else {
            $message = 'invalid token error';
            return array("StatusCode" => 9, "ErrorMessage" => $message);
        }
    }


    public function payment_status()
    {
        $uri = service('uri');
        $payment_token = $uri->getSegment(3);
        $paymentdata = json_decode(dev_decode($payment_token), true);
        if (!$paymentdata) {
            return $this->response->redirect(site_url('holiday-booking/error?errormessage=Payment Record not found'));
        } else {
            $HolidayModel = new HolidayModel();
            $bookingId = $paymentdata['booking_id'];

            $whereClauseBookingCheck = array("id" => $bookingId);
            $bookingInfo = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");

            if (isset($bookingInfo['id']) && $bookingInfo['id'] == $bookingId) {
                if (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == "Successful") {
                    if ($bookingInfo['book_request'] != "requested") {

                        $Confirmationprefix =  $HolidayModel->getData("super_admin_website_setting", array('id' => 1), "holiday_confirmation_counter,holiday_confirmation_prefix,id");
                        $BookingConfirmationNumber  =  GenerateConfirmationNumber("Holiday", $Confirmationprefix['holiday_confirmation_prefix'], ($Confirmationprefix['holiday_confirmation_counter'] + 1));

                        $updateData = array("book_request" => "requested", "booking_status" => "Confirmed");
                        $whereCondition = array("id" => $bookingId);
                        $HolidayModel->updateUserData("holiday_booking_list", $whereCondition, $updateData);
                        $HolidayModel->updateUserData('super_admin_website_setting', ['id' => $Confirmationprefix['id']], array("holiday_confirmation_counter" => ($Confirmationprefix['holiday_confirmation_counter'] + 1)));
                        $HolidayModel->updateUserData('web_partner_account_log', ['booking_ref_no' => $bookingId, "service" => "holiday", 'transaction_type' => "debit", 'action_type' => "booking"], ["booking_confirmation_number" => $BookingConfirmationNumber]);

                        $ticketData = dev_encode(json_encode(array("BookingId" => $bookingInfo['id'], "BookingToken" => $bookingInfo['tts_search_token'])));
                        return $this->response->redirect(site_url('holiday-booking/confirmation/' . $ticketData));
                    } else {
                        return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
                    }
                } else {
                    return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
            }
        }
    }


    function error()
    {
        $error = $this->request->getGET('errormessage');
        return view('template/custom-error-layout', ['error_message' => $error]);
    }


    function confirmation()
    {
        if(permission_access("Holiday", "holiday_confirmation")){
        $uri = service('uri');
        $ticketData = $uri->getSegment(3);
        $bookingId = json_decode(dev_decode($ticketData), true);
        if ($bookingId) {
            $HolidayModel = new HolidayModel();
            $bookingInfo = $HolidayModel->getBookingConfirmationData($bookingId['BookingId'], $this->web_partner_id);


            $bookingInfo['package_details'] = json_decode($bookingInfo['package_details'], true);
            $BookingRefNumber = $bookingInfo['booking_ref_number'];
            $BookingStatus = $bookingInfo['booking_status'];
            $PaymentStatus = $bookingInfo['payment_status'];

            $fareBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
            $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
            $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;


            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $markup, "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $discount + $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    /*"PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'] + $markup - $discount, "LabelText" => "Published Price"),*/
                    /*"CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),*/
                    /*"OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/

                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => $markup,
                "WebPDiscount" => $discount,
            );

            $ConfrimationData = array("ConfirmationBookingData" => $bookingInfo, "bookingRefNumber" => $BookingRefNumber, 'FareBreakUpData' => $FareBreakUp, "paymentStatus" => $PaymentStatus,
                "bookingStatus" => $BookingStatus, "package_name" => $bookingInfo['package_name'], "package_category" => $bookingInfo['package_category']);


            $data = [
                'title' => $this->title,
                'booking' => $ConfrimationData,
                'view' => "Holiday\Views/frentend\booking-confirmation-page",
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
        }
    }
    }

    public function update_web_partner_markup_discount()
    {
        
        $MarkupdiscountUpdate_data = $this->request->getPOST();


        if ($MarkupdiscountUpdate_data) {
            $HolidayModel = new HolidayModel();
            $bookingId = $MarkupdiscountUpdate_data['bookingId'];
            $bookingInfo = $HolidayModel->getBookingWithVariableFieldNameData($bookingId, $this->web_partner_id, "id,web_partner_fare_break_up");
            $web_partner_fare_break_up = json_decode($bookingInfo['web_partner_fare_break_up'], true);
            $web_partner_fare_break_up['WebPMarkUp'] = $MarkupdiscountUpdate_data['webPartnerMarkup'];
            $web_partner_fare_break_up['WebPDiscount'] = $MarkupdiscountUpdate_data['webPartnerDiscount'];
            $fareBreakupArray = $web_partner_fare_break_up;


            $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
            $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
            $FareBreakUp = array();
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $markup, "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $discount + $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    /*"PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'] + $markup - $discount, "LabelText" => "Published Price"),*/
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => $markup,
                "WebPDiscount" => $discount,
            );
            $HolidayModel->updateUserData("holiday_booking_list", array("id" => $bookingId, "web_partner_id" => $this->web_partner_id), array("web_partner_fare_break_up" => json_encode($web_partner_fare_break_up)));
            $data = array("StatusCode" => 0, "Message" => "Markup and Discount Successfully Updated.", "Farebreakup" => $FareBreakUp);
        } else {
            $data = array("StatusCode" => 1, "Message" => "Something went wrong");
        }

        return $this->response->setJSON($data);
    }



    public function get_invoice_ticket()
    {
        if(permission_access("Holiday", "holiday_invoice_ticket")){

        $HolidayModel = new HolidayModel();


        if (!$this->request->isAJAX()) {
            $getData = $this->request->getGet();
            $bookingRefNumbers = $getData['booking_ref_number'];
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            if ($bookingRefNumbers) {

                $bookingInfoData = $HolidayModel->getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumbers, $this->web_partner_id, "id,tts_search_token");
                $bookingInfo = $bookingInfoData;
                $bookingInfoId = $bookingInfoData['id'];
                $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";

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
                            "ViewSize" => "",
                        );
                    }


                    $url = $this->Services . 'generate-voucher-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    if (isset($response['Result']['Html'])) {
                        $Html = $response['Result']['Html'];
                    } else {
                        $Html = "Something went wrong please call admin";
                    }
                    $data = [
                        'title' => $this->title,
                        'view' => "Holiday\Views/frentend\print_ticket",
                        'data' => $Html,
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
            }
        } else {
            $validate = new Validation();
            $rules = $this->validate($validate->EmailTicketValidation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $getData = $this->request->getPost();
                $bookingRefNumbers = $getData['booking_ref_number'];
                if ($bookingRefNumbers) {

                    $bookingInfoData = $HolidayModel->getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumbers, $this->web_partner_id, "id,tts_search_token");
                    $bookingInfo = $bookingInfoData;
                    $bookingInfoId = $bookingInfoData['id'];
                    $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";

                    if ($bookingRefNumbers && $bookingInfo) {
                        $TicketViewRequest = array(
                            "BookingId" => $bookingInfoId,
                            "SearchTokenId" => $tts_search_token,
                            "HtmlType" => "Ticket",
                            "UserType" => "WebPartner",
                            "ViewService" => "Email",
                            "WithPrice" => "1",
                            "WithAgencyDetail" => "0",
                            "TicketInvoiceJourney" => "Both",
                            "ViewSize" => "",
                        );
                        $url = $this->Services . 'generate-voucher-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);

                        $htmlView = $response['Result']['Html'];
                        $subject = "Holiday Voucher";
                        $to = $getData['email'];
                        $data = send_email($to, $subject, $htmlView);
                        $message = array("StatusCode" => 0, "Message" => "Email Successfully Send", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Request Not Allowed", "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                } else {
                    return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
                }
            }
        }
    }
    }

    public function booking_list()
    {
        if(permission_access("Holiday", "holiday_booking_list")){
        $HolidayModel = new HolidayModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HolidayModel->search_data($this->request->getGet(), $this->web_partner_id);
        } else {
            $lists = $HolidayModel->holiday_booking_list($this->web_partner_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Holiday\Views/frentend\holiday-booking-list",
            'pager' => $HolidayModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }
}

    public function holiday_booking_detail()
    {
        if(permission_access("Holiday", "holiday_booking_detail")){
        $uri = service('uri');
        $ref_id = $uri->getSegment(3);


        $HolidayModel = new HolidayModel();
        $bookingInfo = $HolidayModel->getBookingDetailData($ref_id, $this->web_partner_id);
        $amendment_list = $HolidayModel->amendment_list($this->web_partner_id,$bookingInfo['id']);

        $FareBreakUp = array();
        $fareBreakupArray = json_decode($bookingInfo['web_partner_fare_break_up'], true);
        $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
        $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;


        $FareBreakUp = array(
            "FareBreakup" => array(
                "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
            ),
            "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
            "GSTDetails" => $fareBreakupArray['GST'],
            "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
            "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
        );


        $bookingInfo['FareBreakUp'] = $FareBreakUp;

        $data = [
            'title' => $this->title,
            'bookingDetail' => $bookingInfo,
            'amendment_list'=>$amendment_list,
            'view' => "Holiday\Views/frentend\holiday-booking-details",
        ];

        return view('template/sidebar-layout', $data);
    }
}

    public function raiseAmendment()
    {
        if(permission_access("Holiday", "holiday_raise_mendment")){
        
        $requestData  =  $this->request->getPOST();
        $errors  =  array();
        if ($requestData) {
            $validate = new Validation();
            $this->validation->setRules($validate->raiseAmendment);
            $rules = $this->validation->run($requestData);
            if (!$rules) {
                $errors =   $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayModel = new HolidayModel();
                $BookingRefNumber =  $this->request->getPOST('booking_ref_number');
                $whereClauseBookingCheck  =  array("booking_ref_number" =>  $BookingRefNumber);
                $bookingInfo   = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");


                if ($BookingRefNumber && $bookingInfo) {
                    $request = array(
                        "BookingId" => $bookingInfo['id'],
                        "Type" => $this->request->getPOST('amendment_type'),
                        "Remarks" => $this->request->getPOST('remark'),
                        "RequesterInfo"=>array("RequesterId"=>$this->web_partner_details['id'],"Requester"=>"WebPartner"),
                    );


                    $service = "submitamendment";
                    $url =   $this->Services . $service;
                    $response = Request_holiday($request, $url, $service);
                    if($response['Error']['ErrorCode']==0) {
                        $message = array("StatusCode" => 0, "Message" => "Amendment has  Successfully Submitted.",'Class' => 'success_popup');
                    }
                    else{
                        $message = array("StatusCode" => 2, "Message" => $response['Error']['ErrorMessage'],"Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
                else{
                    $message = array("StatusCode" => 2, "Message" =>"Request Not Allowed","Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }
}

    public function amendment_lists()
    {
        if(permission_access("Holiday", "holiday_amendment_lists")){

        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $AmendmentModel->search_data($this->web_partner_id, $getData);
        } else {
            $list = $AmendmentModel->amendment_list($this->web_partner_id);
        }
        //prd($list);
        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views/frentend/amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }
    }
    public function amendments_details()
    {
        if(permission_access("Holiday", "holiday_amendment_details")){

        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $AmendmentModel = new AmendmentModel();
        $AmendmentDetail = $AmendmentModel->amendment_detail($this->web_partner_id,$amendmentId);
        if ($AmendmentDetail) {


            $FareBreakUp = array();
            $fareBreakupArray = json_decode($AmendmentDetail['web_partner_fare_break_up'], true);
            $super_admin_fare_break_up = json_decode($AmendmentDetail['super_admin_fare_break_up'], true);
            $markup = isset($super_admin_fare_break_up['SUP_Markup']) ? $super_admin_fare_break_up['SUP_Markup'] : 0;
            $discount = isset($super_admin_fare_break_up['SUP_Discount']) ? $super_admin_fare_break_up['SUP_Discount'] + $super_admin_fare_break_up['SUP_ExtraDiscount'] : 0;


            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                    /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                    "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST'],
                "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
            );
            $AmendmentDetail['FareBreakUp'] = $FareBreakUp;

            $AmendmentDetail['web_partner_fare_break_up'] = $fareBreakupArray;
        }

        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views/frentend\amendments-detail",
            "amendmentDetail" => $AmendmentDetail,

        ];
        return view('template/sidebar-layout', $data);
    }
}

    function refund_lists()
    {
        if(permission_access("Holiday", "holiday_refund_lists")){
        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $list = $AmendmentModel->search_refund_list($this->web_partner_id,$getData);
        } else {
            $list = $AmendmentModel->refund_list($this->web_partner_id,);

        }

        if (isset($getData['export_excel']) && $getData['export_excel'] == 1) {
            if (isset($getData['key'])) {

                $excellist = $AmendmentModel->search_refund_list($this->web_partner_id,$getData);
            } else {
                $excellist = $AmendmentModel->search_refund_list($this->web_partner_id,);
            }
            //prd($excellist);exit;
            HotelListings::export_refund_list($excellist);
        }

        // prd($list);exit;
        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views/frentend/refund-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/sidebar-layout', $data);
    }
}

    function getCreditNote()
    {
    
        $HolidayModel= new HolidayModel();
        $getData = $this->request->getPOST();
        if (!$this->request->isAJAX()) {
            $getVoucherInvioceType = array("PrintVoucher" => "Voucher", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {
                    $TicketViewRequest = array(
                        "BookingId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => "Ticket",
                        "UserType" => "WebPartner",
                        "ViewService" => "View",
                        "WithAgencyDetail" => "1",
                        "ViewSize" => "",
                    );
                    $url = $this->Services . 'generate-credit-note';

                    $response = RequestWithoutAuth($TicketViewRequest, $url);

                    // echo $url; die;
                    $data = [
                        'title' => $this->title,
                        'view' => "Holiday\Views/frentend\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday-booking/error?errormessage=Request Not Allowed'));
            }
        }
  
}
}

