<?php

namespace Modules\Holiday\Controllers;

use App\Modules\Holiday\Models\HolidayModel;
use App\Modules\Holiday\Models\AmendmentModel;
use App\Modules\Holiday\Models\WebPartnerHolidayMarkupModel;
use App\Modules\Holiday\Models\WebPartnerHolidayDiscountModel;
use App\Modules\Holiday\Models\AgentHolidayMarkupModel;

use App\Modules\Holiday\Models\HolidayDestinationModel;
use App\Modules\Holiday\Models\HolidayThemesModel;

use App\Models\CommonModel;
use App\Controllers\BaseController;
use Modules\Holiday\Config\Validation;


class HolidayBooking extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Holiday";

        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_user_id = session()->get('wl_agent')['id'];
        $this->booking_source = "Wl_b2b";
        $this->agent_gst_number = session()->get('wl_agent')['gst_number'];
        $this->agent_class = session()->get('wl_agent')['agent_class'];
        helper('Modules\Holiday\Helpers\holiday');
        $this->folder_name = 'holiday';

        $this->Services = API_REQUEST_URL . '/holidayservice/rest/';
        if (permission_access_error("Holiday", "Holiday_Module")) {

        }
    }

    public function get_destinations()
    {
        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);
        $HolidayDestinationModel = new HolidayDestinationModel();
        $destination = $HolidayDestinationModel->holiday_destination_list_select_ajax($terms, $this->web_partner_id);
        echo json_encode($destination);
    }

    public function get_themes()
    {
        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);
        $HolidayThemesModel = new HolidayThemesModel();
        $theme = $HolidayThemesModel->holiday_themes_list_select_ajax($terms, $this->web_partner_id);
        echo json_encode($theme);
    }

    public function get_package()
    {
        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);
        $HolidayModel = new HolidayModel();
        $get_package = $HolidayModel->get_packages_autosuggestion($this->web_partner_id, $terms);

        $availableAirport = [];
        if (!empty($get_package)) {
            foreach ($get_package as $data) {
                $availableAirport[] = [
                    'id' => $data['id'],
                    'Package' => $data['holiday_name'],
                    'label' => $data['holiday_name'] . ' (' . $data['id'] . ')',
                ];
            }
        }

        echo json_encode($availableAirport);
    }

    public function auto_suggest()
    {
        $HolidayModel = new HolidayModel();
        $terms = $this->request->getGet('term');
        $suggest = $HolidayModel->auto_suggest($terms, $this->web_partner_id);
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
        $HolidayModel = new HolidayModel();
        $offers_list = $HolidayModel->offers_list($this->web_partner_id);

        $holiday_themes_list = GetRequest($this->Services . 'themes');
        $holiday_list = GetRequest($this->Services . 'holiday-list');
        $slider_list = $HolidayModel->slider_list();
        $important_notification = $HolidayModel->important_notification();
        $general_notification = $HolidayModel->general_notification();

        if (isset($holiday_themes_list['Error']['ErrorCode']) && $holiday_themes_list['Error']['ErrorCode'] == 0) {
            $holiday_themes_list = $holiday_themes_list['Result'];
        } else {
            $holiday_themes_list = null;
        }


        if (isset($holiday_list['Error']['ErrorCode']) && $holiday_list['Error']['ErrorCode'] == 0) {
            $holiday_list = $holiday_list['Result'];
        } else {
            $holiday_list = null;
        }

        $MetaInfoData = static_meta_information('Holiday', 'Index');
        $data = [
            'offers_list' => $offers_list,
            'holiday_list' => $holiday_list,
            'slider_list' => $slider_list,
            'general_notification' => $general_notification,
            'important_notification' => $important_notification,
            'holiday_themes_list' => $holiday_themes_list,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',

            'view' => "Holiday\Views\booking\index",
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
            'view' => "Holiday\Views\booking\holiday_result",
        ];
        return view('template/default-layout', $data);
    }

    public function holiday_result()
    {
        $id = $this->request->getGet('id');
        $tag = $this->request->getGet('tag');
        $slug = $this->request->getGet('slug');
        $WebPartnerHolidayMarkupModel = new WebPartnerHolidayMarkupModel();
        $WebPartnerHolidayDiscountModel = new WebPartnerHolidayDiscountModel();
        $AgentHolidayMarkupModel = new AgentHolidayMarkupModel();
        $holiday_list = GetRequest($this->Services . 'search?id=' . $id . '&tag=' . $tag);
        if ($holiday_list['Error']['ErrorCode'] == 0) {
            $input = array(
                'id' => $id,
                'tag' => $tag,
                'agent_class' => $this->agent_class
            );
            $WebPartnermarkupData = $WebPartnerHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input);
            $WebpartnerdiscountData = $WebPartnerHolidayDiscountModel->getHolidayDiscount($this->web_partner_id, $input);
            $AgentMarkupData = $AgentHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input, $this->wl_agent_id);
            foreach ($holiday_list['Result']['Holidays'] as $key => $holidayData) {
                $priceBreakup = $holidayData['priceBreakup'];
                $holiday_list['Result']['Holidays'][$key]['priceBreakup'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $priceBreakup)['Fare'];
            }
            $MinPriceBreakUp = get_fare_breakup($holiday_list['Result']['Min']);
            $MaxPriceBreakUp = get_fare_breakup($holiday_list['Result']['Max']);
            $holiday_list['Result']['Min'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $MinPriceBreakUp)['Fare']['PublishedPrice'];
            $holiday_list['Result']['Max'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $MaxPriceBreakUp)['Fare']['PublishedPrice'];
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
        $input = [];
        if (isset($holiday_list['Error']['ErrorCode']) && $holiday_list['Error']['ErrorCode'] == 0) {
            $input = array(
                'id' => $holiday_list['Result']['id'],
                'tag' => 'Packages',
                'agent_class' => $this->agent_class
            );
            $WebPartnerHolidayMarkupModel = new WebPartnerHolidayMarkupModel();
            $WebPartnerHolidayDiscountModel = new WebPartnerHolidayDiscountModel();
            $AgentHolidayMarkupModel = new AgentHolidayMarkupModel();
            $WebPartnermarkupData = $WebPartnerHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input);
            $WebpartnerdiscountData = $WebPartnerHolidayDiscountModel->getHolidayDiscount($this->web_partner_id, $input);
            $AgentMarkupData = $AgentHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input, $this->wl_agent_id);
            $priceBreakup = get_fare_breakup($holiday_list['Result']['StandardPrice']);
            $DeluxePrice = get_fare_breakup($holiday_list['Result']['DeluxePrice']);
            $LuxuryPrice = get_fare_breakup($holiday_list['Result']['LuxuryPrice']);
            $holiday_list['Result']['ShowPrice'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $priceBreakup)['Fare']['PublishedPrice'];
            $holiday_list['Result']['StandardPrice'] = $holiday_list['Result']['ShowPrice'];
            $holiday_list['Result']['DeluxePrice'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $DeluxePrice)['Fare']['PublishedPrice'];
            $holiday_list['Result']['LuxuryPrice'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentMarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $LuxuryPrice)['Fare']['PublishedPrice'];
            $holiday_data['holiday_list'] = $holiday_list;
            $holiday_data['holiday_list']['MarkupArray'] = $WebPartnermarkupData;
            $holiday_data['holiday_list']['DiscountArray'] = $WebpartnerdiscountData;
            $holiday_data['holiday_list']['AgentMarkupArray'] = $AgentMarkupData;
            $holiday_data['holiday_list']['agent_gst_code'] = substr($this->agent_gst_number, 0, 2);
            $holiday_data['holiday_list']['gst_state_code'] = $this->web_partner_details['gst_state_code'];
            $data_calender = view('Modules\Holiday\Views\booking\full-calender', $holiday_data);

            $data = [
                'calender' => $data_calender,
                'title' => $this->title,
                'holiday_list' => $holiday_list,
                'title' => isset($holiday_list['Result']['MetaTitle']) ? trim($holiday_list['Result']['MetaTitle']) : '',
                'metakeywords' => isset($holiday_list['Result']['Metakeyword']) ? trim($holiday_list['Result']['Metakeyword']) : '',
                'metadescription' => isset($holiday_list['Result']['MetaDescription']) ? trim($holiday_list['Result']['MetaDescription']) : '',
                'view' => "Holiday\Views\booking\holiday_details",
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
        }
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
        $input = array(
            'id' => $holiday_list['Result']['id'],
            'tag' => 'Packages',
            'agent_class' => $this->agent_class
        );

        $holiday_list['HolidayType'] = $data["price_category"];
        $holiday_data['holiday_list'] = $holiday_list;
        $WebPartnerHolidayMarkupModel = new WebPartnerHolidayMarkupModel();
        $WebPartnerHolidayDiscountModel = new WebPartnerHolidayDiscountModel();
        $AgentHolidayMarkupModel = new AgentHolidayMarkupModel();
        $WebPartnermarkupData = $WebPartnerHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input);
        $WebpartnerdiscountData = $WebPartnerHolidayDiscountModel->getHolidayDiscount($this->web_partner_id, $input);
        $AgentmarkupData = $AgentHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input, $this->wl_agent_id);
        $holiday_data['holiday_list']['MarkupArray'] = $WebPartnermarkupData;
        $holiday_data['holiday_list']['DiscountArray'] = $WebpartnerdiscountData;
        $holiday_data['holiday_list']['AgentMarkupArray'] = $AgentmarkupData;
        $holiday_data['holiday_list']['agent_gst_code'] = substr($this->agent_gst_number, 0, 2);
        $holiday_data['holiday_list']['gst_state_code'] = $this->web_partner_details['gst_state_code'];
        $data_calender = view('Modules\Holiday\Views\booking\full-calender', $holiday_data);

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

                        $pax_type = "child";


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


            $block_data = Request($request_data, $this->Services . 'block-holiday');

            $Redirect_Url = site_url("holiday/booking-review?TokenId=" . $data['SearchTokenId']);
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
        $details_data = Request($request_data, $this->Services . 'holiday-block-detail');
        $totalpax = $details_data['Result']['SelectedRoomOccupancy'][0]['total_adult'] + $details_data['Result']['SelectedRoomOccupancy'][0]['total_child'];
        $input = array(
            'id' => $details_data['Result']['id'],
            'tag' => 'Packages',
            'agent_class' => $this->agent_class,
            'totalpax' => $totalpax
        );
        $priceBreakup = $details_data['Result']['PriceBreakup'];
        $WebPartnerHolidayMarkupModel = new WebPartnerHolidayMarkupModel();
        $WebPartnerHolidayDiscountModel = new WebPartnerHolidayDiscountModel();
        $AgentHolidayMarkupModel = new AgentHolidayMarkupModel();
        $WebPartnermarkupData = $WebPartnerHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input);
        $WebpartnerdiscountData = $WebPartnerHolidayDiscountModel->getHolidayDiscount($this->web_partner_id, $input);
        $AgentmarkupData = $AgentHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input, $this->wl_agent_id);
        $details_data['Result']['PriceBreakup'] = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentmarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $priceBreakup)['AgentFareBreakUp'];
        $fareBreakupArray = $details_data['Result']['PriceBreakup'];
        $gst = $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'];
        $FareBreakUp = array();
        $agentAddInTax = 0;
        $agentAddInServiceCharges = 0;
        $AgentWebPMarkUp = $fareBreakupArray['AgentWebPMarkUp'];
        $AgentWebPDiscount = $fareBreakupArray['AgentWebPDiscount'];
        $AgentWebPDisplayMarkup = $fareBreakupArray['AgentWebPDisplayMarkup'];
        if ($AgentWebPDisplayMarkup == "in_tax") {
            $agentAddInTax = $AgentWebPMarkUp;
        } else {
            $agentAddInServiceCharges = $AgentWebPMarkUp;
        }
        $FareBreakUp = array(
            "FareBreakup" => array(
                "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                "Taxes" => array("Value" => $fareBreakupArray['Tax'] + $agentAddInTax, "LabelText" => "Taxes"),
                "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $agentAddInServiceCharges, "LabelText" => "Other & Service Charges"),
                "Discount" => array("Value" => $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                "GST" => array("Value" => $gst, "LabelText" => "GST (+)")
            ),
            "TotalAmount" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount + $fareBreakupArray['TDS']), "LabelText" => "Pay  Amount"),
            "OfferedFare" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS']), "LabelText" => "Offered Fare"),
            "AgentWebPMarkUp" => round_value($AgentWebPMarkUp),
            "AgentWebPDiscount" => round_value($AgentWebPDiscount),
            "AgentWebPDisplayMarkup" => $AgentWebPDisplayMarkup,
        );
        $HolidayModel = new HolidayModel();
        $dial_code = $HolidayModel->get_dial_code();
        $data = [
            'dial_code' => $dial_code,
            'web_partner_details' => $this->web_partner_details,
            'title' => $this->title,
            'details' => $details_data,
            'FareBreakUp' => $FareBreakUp,
            'view' => "Holiday\Views\booking\booking-review",
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
            $totalpax = $detail['SelectedRoomOccupancy'][0]['total_adult'] + $detail['SelectedRoomOccupancy'][0]['total_child'];
            $input = array(
                'id' => $detail['id'],
                'tag' => 'Packages',
                'agent_class' => $this->agent_class,
                'totalpax' => $totalpax
            );
            $priceBreakup = $detail['PriceBreakup'];
            $WebPartnerHolidayMarkupModel = new WebPartnerHolidayMarkupModel();
            $WebPartnerHolidayDiscountModel = new WebPartnerHolidayDiscountModel();
            $AgentHolidayMarkupModel = new AgentHolidayMarkupModel();
            $WebPartnermarkupData = $WebPartnerHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input);
            $WebpartnerdiscountData = $WebPartnerHolidayDiscountModel->getHolidayDiscount($this->web_partner_id, $input);
            $AgentmarkupData = $AgentHolidayMarkupModel->getHolidayMarkup($this->web_partner_id, $input, $this->wl_agent_id);
            $priceBreakup = get_fare_price($input, $WebPartnermarkupData, $WebpartnerdiscountData, $AgentmarkupData, $this->agent_gst_number, $this->web_partner_details['gst_state_code'], $priceBreakup);
            $total_price = $priceBreakup['Fare']['OfferedPrice'] + $priceBreakup['Fare']['TDS'];
            $web_partner_booking_total_price = $priceBreakup['WebPartnerFareBreakUp']['OfferedPrice'] + $priceBreakup['WebPartnerFareBreakUp']['TDS'];
            $TravelDate = date('Y-m-d', strtotime($detail['TravelDate']));

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
                "package_image" => $detail['HolidayImage'],
                'web_partner_booking_total_price' => $web_partner_booking_total_price,
                'gst_info' => isset($data['gst']) ? json_encode($data['gst']) : null,
                "super_admin_fare_break_up" => json_encode($detail['AdminPriceBreakup']),
                'web_partner_fare_break_up' => json_encode($priceBreakup['WebPartnerFareBreakUp']),
                'agent_fare_break_up' => json_encode($priceBreakup['AgentFareBreakUp']),
                'book_request' => '',
                'booking_channel' => 'Desktop',
                'agent_staff_id' => '',
                'wl_agent_id' => $this->wl_agent_id,
                'wl_agent_user_id' => $this->wl_agent_user_id,
                'booking_source' => $this->booking_source,
                "created" => create_date(),
                'travel_date' => $TravelDate
            );

            $booking_id = $HolidayModel->insertData("holiday_booking_list", $insertData);

            $super_admin__booking_pre_fix_code = $HolidayModel->service_booking_pre_fix_code()['pre_fix'];
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
                            $PaxType = 'Child';
                            $Age = $paxinfo['child_age'];
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
            return $this->response->redirect(site_url('holiday/error?errormessage=Payment Record not found'));
        } else {
            $HolidayModel = new HolidayModel();
            $bookingId = $paymentdata['booking_id'];

            $whereClauseBookingCheck = array("id" => $bookingId);
            $bookingInfo = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");

            if (isset($bookingInfo['id']) && $bookingInfo['id'] == $bookingId) {
                if (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == "Successful") {
                    if ($bookingInfo['book_request'] != "requested") {
                        $updateData = array("book_request" => "requested", "booking_status" => "Confirmed");
                        $whereCondition = array("id" => $bookingId);
                        $HolidayModel->updateUserData("holiday_booking_list", $whereCondition, $updateData);
                        $getBookingInfo = $HolidayModel->getData("holiday_booking_list", $whereCondition, "booking_status,booking_ref_number,agent_fare_break_up,wl_agent_id,gst_info");
                        if ($getBookingInfo['booking_status'] == 'Confirmed') {
                            $agent_fare_break_up = json_decode($getBookingInfo['agent_fare_break_up'], true);
                            $gst_info = json_decode($getBookingInfo['gst_info'], true);
                            $AgentGSTInfo = $HolidayModel->getData("agent", array("id" => $getBookingInfo['wl_agent_id'], "web_partner_id" => $this->web_partner_id), "gst_number");
                            $AgentGSTNumber = $AgentGSTInfo['gst_number'];
                            $GstNumber = isset($data['number']) && $gst_info['number'] != "" ? $gst_info['number'] : $AgentGSTNumber;
                            $checkTaxableInvoce = checkTaxableNonTaxableINV($agent_fare_break_up, $GstNumber, 'holiday', 'INV');
                            $INVPrifix = getTaxableNonTaxableINVSuffix('INV', $checkTaxableInvoce, 'holiday');
                            $financialYear = get_financial_year();
                            $whereCondition = array();
                            $whereCondition['service'] = 'holiday';
                            $whereCondition['web_partner_id'] = $this->web_partner_id;
                            $whereCondition['invoice_type'] = 'INV';
                            $whereCondition['financial_year'] = $financialYear;
                            $otherParameter['financialYear'] = $financialYear;
                            $otherParameter['service'] = 'holiday';
                            $otherParameter['invoice_type'] = 'INV';
                            $otherParameter['INVPrifix'] = $INVPrifix;
                            $otherParameter['web_partner_id'] = $this->web_partner_id;
                            $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;
                            $CommonModel = new CommonModel();
                            $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                            $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                            $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                            $InvoiceupdateData = $InvoiceInfoData['updateData'];
                            $agent_account_log = $HolidayModel->getData("agent_account_log", ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "wl_agent_id" => $getBookingInfo['wl_agent_id']], "service_log");
                            $service_log = json_decode($agent_account_log['service_log'], true);
                            // $service_log['TicketNo'] = $getBookingInfo['pnr'];
                            $HolidayModel->updateUserData('agent_account_log', ['booking_ref_no' => $bookingId, "action_type" => "booking", "transaction_type" => "debit", "wl_agent_id" => $getBookingInfo['wl_agent_id'], "web_partner_id" => $this->web_partner_id], ["invoice_number" => $InvoiceNumber, "service_log" => json_encode($service_log)]);
                            $HolidayModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                        }

                        $ticketData = dev_encode(json_encode(array("BookingId" => $bookingInfo['id'], "BookingToken" => $bookingInfo['tts_search_token'])));
                        return $this->response->redirect(site_url('holiday/confirmation/' . $ticketData));
                    } else {
                        return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
                    }
                } else {
                    return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
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
        $uri = service('uri');
        $ticketData = $uri->getSegment(3);
        $bookingId = json_decode(dev_decode($ticketData), true);
        if ($bookingId) {
            $HolidayModel = new HolidayModel();
            $bookingInfo = $HolidayModel->getBookingConfirmationData($bookingId['BookingId'], $this->web_partner_id, $this->wl_agent_id, $this->booking_source);


            $bookingInfo['package_details'] = json_decode($bookingInfo['package_details'], true);
            $BookingRefNumber = $bookingInfo['booking_ref_number'];
            $BookingStatus = $bookingInfo['booking_status'];
            $PaymentStatus = $bookingInfo['payment_status'];

            $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
            $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
            $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
            $wl_agent_markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
            $wl_agent_discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;

            $Tax = $fareBreakupArray['Tax'];
            $ServiceCharges = $fareBreakupArray['ServiceCharges'];
            if ($fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_tax') {
                $Tax += $wl_agent_markup;
            } else {
                $ServiceCharges += $wl_agent_markup;
            }
            $gst = number_format($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'], 2);
            $TotalAmount = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $markup - $discount + $bookingInfo['conveniencefee'] + $wl_agent_markup - $wl_agent_discount;
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $Tax, "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $ServiceCharges, "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $wl_agent_discount + $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                    /*"PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'] + $markup - $discount, "LabelText" => "Published Price"),*/
                    /*"OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                    "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                    "conveniencefee" => array("Value" => $bookingInfo['conveniencefee'], "LabelText" => "Convenience Fee (+)"),
                ),
                "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => $wl_agent_markup,
                "WebPDiscount" => $wl_agent_discount,
            );

            $ConfrimationData = array(
                "ConfirmationBookingData" => $bookingInfo,
                "bookingRefNumber" => $BookingRefNumber,
                'FareBreakUpData' => $FareBreakUp,
                "paymentStatus" => $PaymentStatus,
                "bookingStatus" => $BookingStatus,
                "package_name" => $bookingInfo['package_name'],
                "package_category" => $bookingInfo['package_category']
            );


            $data = [
                'title' => $this->title,
                'booking' => $ConfrimationData,
                'view' => "Holiday\Views\booking\booking-confirmation-page",
            ];
            return view('template/default-layout', $data);
        } else {
            return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
        }
    }

    public function update_web_partner_markup_discount()
    {
        $MarkupdiscountUpdate_data = $this->request->getPOST();


        if ($MarkupdiscountUpdate_data) {
            $HolidayModel = new HolidayModel();
            $bookingId = $MarkupdiscountUpdate_data['bookingId'];
            $bookingInfo = $HolidayModel->getBookingWithVariableFieldNameData($bookingId, $this->web_partner_id, "id,agent_fare_break_up,conveniencefee");
            $web_partner_fare_break_up = json_decode($bookingInfo['agent_fare_break_up'], true);
            $web_partner_fare_break_up['AgentWebPMarkUp'] = $MarkupdiscountUpdate_data['webPartnerMarkup'];
            $web_partner_fare_break_up['AgentWebPDiscount'] = $MarkupdiscountUpdate_data['webPartnerDiscount'];
            // pr($web_partner_fare_break_up);exit;
            $fareBreakupArray = $web_partner_fare_break_up;


            // $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
            // $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
            $markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
            $discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;

            $Tax = $fareBreakupArray['Tax'];
            $ServiceCharges = $fareBreakupArray['ServiceCharges'];
            if ($fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_tax') {
                $Tax += $markup;
            } else {
                $ServiceCharges += $markup;
            }
            $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
            $TotalAmount = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $bookingInfo['conveniencefee'] + $markup - $discount;
            $FareBreakUp = array();
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $Tax, "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $ServiceCharges, "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $discount + $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                    /*"PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'] + $markup - $discount, "LabelText" => "Published Price"),*/
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                    "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                    "conveniencefee" => array("Value" => $bookingInfo['conveniencefee'], "LabelText" => "Convenience Fee (+)"),
                ),
                "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => $markup,
                "WebPDiscount" => $discount,
            );
            $HolidayModel->updateUserData("holiday_booking_list", array("id" => $bookingId, "web_partner_id" => $this->web_partner_id), array("agent_fare_break_up" => json_encode($web_partner_fare_break_up)));
            $data = array("StatusCode" => 0, "Message" => "Markup and Discount Successfully Updated.", "Farebreakup" => $FareBreakUp);
        } else {
            $data = array("StatusCode" => 1, "Message" => "Something went wrong");
        }

        return $this->response->setJSON($data);
    }


    public function get_invoice_ticket()
    {

        $HolidayModel = new HolidayModel();


        if (!$this->request->isAJAX()) {
            $getData = $this->request->getGet();
            $bookingRefNumbers = $getData['booking_ref_number'];
            $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
            $getTicketUserType = array("PrintTicket" => "wl-agent-customer", "AgencyInvoice" => "wl-agent", "CustomerInvoice" => "wl-agent-customer");
            $getRequestType = array("PrintTicket" => "Agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "Agent");
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
                            "UserType" => $getTicketUserType[$getData['type']],
                            "ViewService" => "View",
                            "WithPrice" => isset($getData['price']) ? 1 : 0,
                            "WithAgencyDetail" => isset($getData['agency_detail']) ? 1 : 0,
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
                            "ViewSize" => "",
                            "RequestBy" => $getRequestType[$getData['type']]
                        );
                    }

                    $url = $this->Services . 'generate-wl-voucher-invoice';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    if (isset($response['Result']['Html'])) {
                        $Html = $response['Result']['Html'];
                    } else {
                        $Html = "Something went wrong please call admin";
                    }
                    $data = [
                        'title' => $this->title,
                        'view' => "Holiday\Views\booking\print_ticket",
                        'data' => $Html,
                    ];
                    return view('template/default-layout', $data);
                } else {
                    return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
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
                            "UserType" => "wl-agent-customer",
                            "ViewService" => "Email",
                            "WithPrice" => "1",
                            "WithAgencyDetail" => "1",
                            "ViewSize" => "",
                            "RequestBy" => "Agent",
                        );

                        $url = $this->Services . 'generate-wl-voucher-invoice';
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
                    return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
                }
            }
        }
    }

    public function booking_list()
    {
        $HolidayModel = new HolidayModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HolidayModel->search_data($this->request->getGet(), $this->web_partner_id, $this->wl_agent_id, $this->booking_source);
        } else {
            $lists = $HolidayModel->holiday_booking_list($this->web_partner_id, $this->wl_agent_id, $this->booking_source);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Holiday\Views\booking\holiday-booking-list",
            'pager' => $HolidayModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
    }

    public function holiday_booking_detail()
    {
        $uri = service('uri');
        $ref_id = $uri->getSegment(3);

        $HolidayModel = new HolidayModel();
        $bookingInfo = $HolidayModel->getBookingDetailData($ref_id, $this->web_partner_id, $this->wl_agent_id, $this->booking_source);
        $amendment_list = $HolidayModel->amendment_list($this->web_partner_id, $bookingInfo['id']);

        $FareBreakUp = array();
        $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
        $wl_agent_markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
        $wl_agent_discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;

        $Tax = $fareBreakupArray['Tax'];
        $ServiceCharges = $fareBreakupArray['ServiceCharges'];
        if ($fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_tax') {
            $Tax += $wl_agent_markup;
        } else {
            $ServiceCharges += $wl_agent_markup;
        }

        $FareBreakUp = array(
            "FareBreakup" => array(
                "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                "Taxes" => array("Value" => $Tax, "LabelText" => "Taxes"),
                "ServiceCharges" => array("Value" => $ServiceCharges, "LabelText" => "Service Charges"),
                /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
            ),
            "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
            "GSTDetails" => $fareBreakupArray['GST'],
            "WebPMarkUp" => array("Value" => $wl_agent_markup, "LabelText" => "Apply Mark Up"),
            "WebPDiscount" => array("Value" => $wl_agent_discount, "LabelText" => "Apply Discount"),
        );


        $bookingInfo['FareBreakUp'] = $FareBreakUp;

        $data = [
            'title' => $this->title,
            'bookingDetail' => $bookingInfo,
            'amendment_list' => $amendment_list,
            'view' => "Holiday\Views\booking\holiday-booking-details",
        ];

        return view('template/default-layout', $data);
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
                $HolidayModel = new HolidayModel();
                $BookingRefNumber = $this->request->getPOST('booking_ref_number');
                $whereClauseBookingCheck = array("booking_ref_number" => $BookingRefNumber);
                $bookingInfo = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");


                if ($BookingRefNumber && $bookingInfo) {
                    $request = array(
                        "BookingId" => $bookingInfo['id'],
                        "Type" => $this->request->getPOST('amendment_type'),
                        "Remarks" => $this->request->getPOST('remark'),
                        "RequesterInfo" => array("RequesterId" => $this->wl_agent_user_id, "Requester" => "Whitelabel", "wl_agent_id" => $this->wl_agent_id),
                    );


                    $service = "submitamendment";
                    $url = $this->Services . $service;
                    $response = Request($request, $url, $service);
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
            }
        } else {
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
    }


    public function amendment_lists()
    {

        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $list = $AmendmentModel->search_data($this->web_partner_id, $this->wl_agent_id, $getData);
        } else {
            $list = $AmendmentModel->amendment_list($this->web_partner_id, $this->wl_agent_id);
        }
        //prd($list);
        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views/booking/amendment-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }

    public function amendments_details()
    {

        $uri = service('uri');
        $amendmentId = $uri->getSegment(3);
        $amendmentId = dev_decode($amendmentId);
        $AmendmentModel = new AmendmentModel();
        $AmendmentDetail = $AmendmentModel->amendment_detail($this->web_partner_id, $this->wl_agent_id, $amendmentId);
        if ($AmendmentDetail) {


            $FareBreakUp = array();
            $fareBreakupArray = json_decode($AmendmentDetail['agent_fare_break_up'], true);
            $wl_agent_markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
            $wl_agent_discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;

            $Tax = $fareBreakupArray['Tax'];
            $ServiceCharges = $fareBreakupArray['ServiceCharges'];
            if ($fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_tax') {
                $Tax += $wl_agent_markup;
            } else {
                $ServiceCharges += $wl_agent_markup;
            }


            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $Tax, "LabelText" => "Taxes"),
                    "ServiceCharges" => array("Value" => $ServiceCharges, "LabelText" => "Service Charges"),
                    /*"PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),*/
                    /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                    "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST'],
                "WebPMarkUp" => array("Value" => $wl_agent_markup, "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => $wl_agent_discount, "LabelText" => "Apply Discount"),
            );
            $AmendmentDetail['FareBreakUp'] = $FareBreakUp;

            $AmendmentDetail['web_partner_fare_break_up'] = $fareBreakupArray;
        }

        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views\booking\amendments-detail",
            "amendmentDetail" => $AmendmentDetail,

        ];
        return view('template/default-layout', $data);
    }

    function refund_lists()
    {
        $AmendmentModel = new AmendmentModel();
        $getData = $this->request->getGET();
        if (isset($getData['key'])) {

            $list = $AmendmentModel->search_refund_list($this->web_partner_id, $this->wl_agent_id, $getData);
        } else {
            $list = $AmendmentModel->refund_list($this->web_partner_id, $this->wl_agent_id);
        }


        $data = [
            'title' => $this->title,
            'view' => "Holiday\Views/booking/refund-list",
            "list" => $list,
            "search_bar_data" => $getData,
            'pager' => $AmendmentModel->pager,
        ];
        return view('template/default-layout', $data);
    }
}
