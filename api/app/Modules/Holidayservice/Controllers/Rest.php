<?php

namespace Modules\Holidayservice\Controllers;

use App\Controllers\BaseController;
use App\Modules\Holidayservice\Models\RestModel;
use Modules\Holidayservice\Config\Validation;
use CodeIgniter\I18n\Time;

class Rest extends BaseController
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        helper('Modules\Holidayservice\Helpers\holiday');
    }

    public function search()
    {
        $search = $this->request->getGet('id');
        $tag = $this->request->getGet('tag');
        $RestModel = new RestModel();
        $search = $RestModel->holiday_list_search($search, $tag, $this->userauthdata['web_partner_id']);
        if (!empty ($search) && is_array($search)) {
            $themes = [];
            $destination = [];
            $DaysNights = [];
            foreach ($search as $sup_key => $data) {

                $super_admin_markup = $RestModel->get_super_admin_markup($this->userauthdata['web_partner_class_id'], $data['Themes'], $data['Destinations']);
                if ($super_admin_markup == "") {
                    $super_admin_markup = array();
                }
                $super_admin_discount = $RestModel->get_super_admin_discount($this->userauthdata['web_partner_class_id'], $data['Themes'], $data['Destinations']);
                if ($super_admin_discount == "") {
                    $super_admin_discount = array();
                }

                $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $data['StandardPrice'], $this->userauthdata, $super_admin_gst_state_code);
                $search[$sup_key]['priceBreakup'] = $priceBreakup;

                unset($search[$sup_key]['StandardPrice'], $search[$sup_key]['DeluxePrice'], $search[$sup_key]['LuxuryPrice']);


                $itinerary = json_decode($data['itinerary'], true);
                $days = is_array($itinerary) ? count($itinerary) : 1;
                if (!empty ($itinerary)) {
                    foreach ($itinerary as $key => $itinerarys) {
                        if ($itinerary[$key]['itinerary_image']) {
                            $itinerary[$key]['itinerary_image'] = $itinerary[$key]['itinerary_image'];
                        }

                        if ($itinerary[$key]['flight_data']) {
                            $itinerary[$key]['flight_data'] = json_decode($itinerary[$key]['flight_data'], true);
                        }

                        if ($itinerary[$key]['hotel_data']) {
                            $itinerary[$key]['hotel_data'] = json_decode($itinerary[$key]['hotel_data'], true);
                        }

                        if ($itinerary[$key]['transfer_data']) {
                            $itinerary[$key]['transfer_data'] = json_decode($itinerary[$key]['transfer_data'], true);
                        }
                    }
                }

                $search[$sup_key]['itinerary'] = $itinerary;
                $cities = array_column($itinerary, 'city');
                $cityStay = city_iternary_stay($cities);
                $search[$sup_key]['HolidayImage'] = $search[$sup_key]['HolidayImage'];
                $search[$sup_key]['CityIncude'] = $cityStay;

                $Nights = $days - 1;
                $search[$sup_key]['DaysNights'] = "{$days} Days & {$Nights} Nights";
                array_push($DaysNights, "{$days} Days & {$Nights} Nights");
                $destination_list = $RestModel->holiday_destination($data['Destinations'], $this->userauthdata['web_partner_id']);
                $themes_list = $RestModel->holiday_themes($data['Themes'], $this->userauthdata['web_partner_id']);


                $string = strip_tags($data['Description']);
                if (strlen($string) > 200) {
                    // truncate string
                    $stringCut = substr($string, 0, 200);
                    $endPoint = strrpos($stringCut, ' ');
                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);

                    $string = $string . ' ...';
                }
                $search[$sup_key]['Description'] = $string;

                foreach ($themes_list as $theme_key => $theme) {
                    array_push($themes, $theme['Name']);
                    $themes_list[$theme_key]['Image'] = $theme['Image'];
                    if ($theme['Icon']) {
                        $themes_list[$theme_key]['Icon'] = $theme['Icon'];
                    } else {
                        $themes_list[$theme_key]['Icon'] = "";
                    }
                }
                foreach ($destination_list as $dest_key => $destinations) {
                    array_push($destination, $destinations['Name']);

                    $destination_list[$dest_key]['Image'] = $destinations['Image'];
                }


                $search[$sup_key]['Themes'] = $themes_list;

                $search[$sup_key]['Destinations'] = $destination_list;
            }

            $Min = reset($search);
            $Max = end($search);

            $final_search = [
                'Holidays' => $search,
                'Themes' => createfilerarray(array_unique($themes)),
                'Destinations' => createfilerarray(array_unique($destination)),
                'DaysNights' => createfilerarray(array_unique($DaysNights)),
                'Min' => $Min['priceBreakup']['PublishedPrice'],
                'Max' => $Max['priceBreakup']['PublishedPrice']
            ];


            $tts_response = array(
                'SearchTokenId' => null,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $final_search,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no holiday found. ', false);
        }
    }


    public function holiday_themes()
    {
        $RestModel = new RestModel();
        $holiday_themes = $RestModel->holiday_themes_list($this->userauthdata['web_partner_id']);
        if (!empty ($holiday_themes) && is_array($holiday_themes)) {

            $tts_response = array(
                'SearchTokenId' => null,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $holiday_themes,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no holiday theme found. ', false);
        }
    }

    public function get_themes_by_slug()
    {
        $RestModel = new RestModel();
        $slug = $this->request->getGet('slug');
        $holiday_themes = $RestModel->holiday_themes_by_slug($this->userauthdata['web_partner_id'], $slug);
        if (!empty ($holiday_themes) && is_array($holiday_themes)) {

            $tts_response = array(
                'SearchTokenId' => null,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $holiday_themes,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no theme found. ', false);
        }
    }
    public function get_destinations_by_slug()
    {
        $RestModel = new RestModel();
        $slug = $this->request->getGet('slug');
        $holiday_themes = $RestModel->holiday_destination_by_slug($this->userauthdata['web_partner_id'], $slug);
        if (!empty ($holiday_themes) && is_array($holiday_themes)) {

            $tts_response = array(
                'SearchTokenId' => null,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $holiday_themes,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no theme found. ', false);
        }
    }

    public function holiday_list()
    {

        $RestModel = new RestModel();
        $holiday_list = $RestModel->holiday_list($this->userauthdata['web_partner_id']);
        if (!empty ($holiday_list) && is_array($holiday_list)) {
            foreach ($holiday_list as $key => $list) {
                /*   $holiday_list[$key]['Image'] =  root_url . 'uploads/holiday/' . $holiday_list[$key]['Image']; */
                $holiday_list[$key]['Image'] = $holiday_list[$key]['Image'];
                $holiday_list[$key]['itinerary'] = json_decode($holiday_list[$key]['itinerary'], true);

                $holiday_list[$key]['Includes'] = explode(',', $holiday_list[$key]['Includes']);


                $check_price = 0;
                $price = 0;
                if ($holiday_list[$key]['StandardPrice'] == '' || $holiday_list[$key]['StandardPrice'] == 0) {
                    $check_price += 1;
                }
                if ($holiday_list[$key]['DeluxePrice'] == '' || $holiday_list[$key]['DeluxePrice'] == 0) {
                    $check_price += 1;
                }

                if ($holiday_list[$key]['LuxuryPrice'] == '' || $holiday_list[$key]['LuxuryPrice'] == 0) {
                    $check_price += 1;
                }



                if ($holiday_list[$key]['StandardPrice'] != '' && $holiday_list[$key]['StandardPrice'] != 0) {


                    $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_markup == "") {
                        $super_admin_markup = array();
                    }
                    $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_discount == "") {
                        $super_admin_discount = array();
                    }
                    $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                    $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list[$key]['StandardPrice'], $this->userauthdata, $super_admin_gst_state_code);




                    $price = $priceBreakup['PublishedPrice'];
                    $holiday_list[$key]['StandardPrice'] = $price;
                } else if ($holiday_list[$key]['DeluxePrice'] != '' && $holiday_list[$key]['DeluxePrice'] != 0) {


                    $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_markup == "") {
                        $super_admin_markup = array();
                    }
                    $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_discount == "") {
                        $super_admin_discount = array();
                    }
                    $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                    $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list[$key]['DeluxePrice'], $this->userauthdata, $super_admin_gst_state_code);


                    $price = $priceBreakup['PublishedPrice'];
                    $holiday_list[$key]['DeluxePrice'] = $price;
                } else if ($holiday_list[$key]['LuxuryPrice'] != '' && $holiday_list[$key]['LuxuryPrice'] != 0) {


                    $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_markup == "") {
                        $super_admin_markup = array();
                    }
                    $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $list['ThemeId'], $list['DestinationId']);
                    if ($super_admin_discount == "") {
                        $super_admin_discount = array();
                    }
                    $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                    $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list[$key]['LuxuryPrice'], $this->userauthdata, $super_admin_gst_state_code);


                    $price = $priceBreakup['PublishedPrice'];

                    $holiday_list[$key]['LuxuryPrice'] = $price;
                }


                if ($check_price == 3) {
                    $book_online = 0;
                } else {
                    $book_online = 1;
                }

                $holiday_list[$key]['PriceOnCall'] = $book_online;

                $holiday_list[$key]['ShowPrice'] = $price;
            }
            $tts_response = array(
                'SearchTokenId' => null,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $holiday_list,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no holiday package found. ', false);
        }
    }

    public function holiday_details()
    {
        $tts_search_token = generate_token();
        $holiday_slug = $this->request->getGet('slug');

        $RestModel = new RestModel();
        $holiday_list = $RestModel->holiday_details($holiday_slug, $this->userauthdata['web_partner_id']);

        $theme_id = $holiday_list['Themes'];
        $destinations_id = $holiday_list['Destinations'];


        if (!empty ($holiday_list) && is_array($holiday_list)) {
            $holiday_list['location_data'] = json_decode($holiday_list['location_data']);
            $inclusion_list = $RestModel->holiday_inclusions($holiday_list['Inclusion'], $this->userauthdata['web_partner_id']);

            $exclusion_list = $RestModel->holiday_exclusion($holiday_list['Exclusion'], $this->userauthdata['web_partner_id']);

            $destination_list = $RestModel->holiday_destination($holiday_list['Destinations'], $this->userauthdata['web_partner_id']);

            $themes_list = $RestModel->holiday_themes($holiday_list['Themes'], $this->userauthdata['web_partner_id']);
            $holiday_price = $RestModel->holiday_price($holiday_list['id'], $this->userauthdata['web_partner_id']);
            $price_array = [];
            if ($holiday_price) {


                $super_admin_markup = $RestModel->get_super_admin_markup($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_markup == "") {
                    $super_admin_markup = array();
                }
                $super_admin_discount = $RestModel->get_super_admin_discount($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_discount == "") {
                    $super_admin_discount = array();
                }
                $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];

                foreach ($holiday_price as $price_key => $item_price) {

                    $priceBreakupStandardAdultPrice = get_holiday_price($super_admin_markup, $super_admin_discount, $item_price['StandardAdultPrice'], $this->userauthdata, $super_admin_gst_state_code);

                    $priceBreakupDeluxeAdultPrice = get_holiday_price($super_admin_markup, $super_admin_discount, $item_price['DeluxeAdultPrice'], $this->userauthdata, $super_admin_gst_state_code);

                    $priceBreakupLuxuryAdultPrice = get_holiday_price($super_admin_markup, $super_admin_discount, $item_price['LuxuryAdultPrice'], $this->userauthdata, $super_admin_gst_state_code);

                    $item_price['StandardAdultPrice'] = $priceBreakupStandardAdultPrice['PublishedPrice'];
                    $item_price['DeluxeAdultPrice'] = $priceBreakupDeluxeAdultPrice['PublishedPrice'];
                    $item_price['LuxuryAdultPrice'] = $priceBreakupLuxuryAdultPrice['PublishedPrice'];

                    $price_array[] = $item_price;
                }
            }
            $holiday_list['Price'] = $price_array;
            // unset($holiday_list['StandardPrice'], $holiday_list['DeluxePrice'], $holiday_list['LuxuryPrice']);

            foreach ($themes_list as $theme_key => $theme) {
                $themes_list[$theme_key]['Image'] = $theme['Image'];
                $themes_list[$theme_key]['Icon'] = $theme['Icon'];
            }
            foreach ($destination_list as $dest_key => $destinations) {
                $destination_list[$dest_key]['Image'] = $destinations['Image'];
            }

            $holiday_gallery = $RestModel->holiday_gallery($holiday_list['id'], $this->userauthdata['web_partner_id']);
            if ($holiday_gallery) {
                foreach ($holiday_gallery as $key => $gallery) {
                    $holiday_gallery[$key]['images'] = $holiday_gallery[$key]['images'];
                }
            } else {
                $holiday_gallery = null;
            }
            $holiday_list['Gallery'] = $holiday_gallery;

            $holiday_list['HolidayImage'] = $holiday_list['HolidayImage'];
            $holiday_list['Themes'] = $themes_list;
            $holiday_list['Inclusion'] = $inclusion_list;
            $holiday_list['Exclusion'] = $exclusion_list;
            $holiday_list['Destinations'] = $destination_list;


            $itinerary = json_decode($holiday_list['itinerary'], true);
            if (!empty ($itinerary)) {
                foreach ($itinerary as $key => $itinerarys) {
                    if ($itinerary[$key]['itinerary_image']) {
                        $itinerary[$key]['itinerary_image'] = $itinerary[$key]['itinerary_image'];
                    } else {
                        $itinerary[$key]['itinerary_image'] = 'Noimage.png';
                    }

                    if ($itinerary[$key]['flight_data']) {
                        $itinerary[$key]['flight_data'] = json_decode($itinerary[$key]['flight_data'], true);
                    }

                    if ($itinerary[$key]['hotel_data']) {
                        $itinerary[$key]['hotel_data'] = json_decode($itinerary[$key]['hotel_data'], true);
                    }

                    if ($itinerary[$key]['transfer_data']) {
                        $itinerary[$key]['transfer_data'] = json_decode($itinerary[$key]['transfer_data'], true);
                    }
                }
            }
            $check_price = 0;
            $price = 0;
            if ($holiday_list['StandardPrice'] == '' || $holiday_list['StandardPrice'] == 0) {
                $check_price += 1;
            }
            if ($holiday_list['DeluxePrice'] == '' || $holiday_list['DeluxePrice'] == 0) {
                $check_price += 1;
            }

            if ($holiday_list['LuxuryPrice'] == '' || $holiday_list['LuxuryPrice'] == 0) {
                $check_price += 1;
            }



            if ($holiday_list['StandardPrice'] != '' && $holiday_list['StandardPrice'] != 0) {



                $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_markup == "") {
                    $super_admin_markup = array();
                }
                $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_discount == "") {
                    $super_admin_discount = array();
                }
                $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list['StandardPrice'], $this->userauthdata, $super_admin_gst_state_code);

                $price = $priceBreakup['PublishedPrice'];
            } else if ($holiday_list['DeluxePrice'] != '' && $holiday_list['DeluxePrice'] != 0) {


                $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_markup == "") {
                    $super_admin_markup = array();
                }
                $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_discount == "") {
                    $super_admin_discount = array();
                }
                $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list['DeluxePrice'], $this->userauthdata, $super_admin_gst_state_code);

                $price = $priceBreakup['PublishedPrice'];
            } else if ($holiday_list['LuxuryPrice'] != '' && $holiday_list['LuxuryPrice'] != 0) {

                $super_admin_markup = $RestModel->get_super_admin_markup_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_markup == "") {
                    $super_admin_markup = array();
                }
                $super_admin_discount = $RestModel->get_super_admin_discount_inarray($this->userauthdata['web_partner_class_id'], $theme_id, $destinations_id);
                if ($super_admin_discount == "") {
                    $super_admin_discount = array();
                }
                $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
                $priceBreakup = get_holiday_price($super_admin_markup, $super_admin_discount, $holiday_list['LuxuryPrice'], $this->userauthdata, $super_admin_gst_state_code);

                $price = $priceBreakup['PublishedPrice'];
            }


            if ($check_price == 3) {
                $book_online = 0;
            } else {
                $book_online = 1;
            }

            $holiday_list['PriceOnCall'] = $book_online;

            $holiday_list['ShowPrice'] = $price;



            $holiday_list['Includes'] = explode(',', $holiday_list['Includes']);
            $days = is_array($itinerary) ? count($itinerary) : 1;
            $Nights = $days - 1;
            $holiday_list['DaysNights'] = "{$days} Days & {$Nights} Nights";
            $holiday_list['itinerary'] = $itinerary;
            $holiday_list['Latitude'] = '';
            $holiday_list['Longitude'] = '';
            $tts_response = array(
                'SearchTokenId' => $tts_search_token,
                "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                "Result" => $holiday_list,
            );
            return $this->response->setContentType('application/json')->setJson($tts_response);
        } else {
            api_custom_message(101, 'Sorry, no holiday package found. ', false);
        }
    }


    public function block_holiday()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->block_holiday_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $tts_search_token = $input['SearchTokenId'];
            $HolidayId = $input['HolidayId'];
            $TravelDate = $input['TravelDate'];
            $HolidayCategory = $input['HolidayCategory'];
            $PriceId = $input['PriceId'];
            $UserIp = $input['UserIp'];
            $RoomData = $input['RoomData'];
            $RestModel = new RestModel();
            $holiday_details = $RestModel->holiday_details_block($HolidayId, $this->userauthdata['web_partner_id']);
            $price_details = $RestModel->holiday_price_block($HolidayId, $PriceId);

            $itinerary = json_decode($holiday_details['itinerary'], true);
            $days = is_array($itinerary) ? count($itinerary) : 1;
            if (!empty ($itinerary)) {
                foreach ($itinerary as $key => $itinerarys) {
                    if ($itinerary[$key]['itinerary_image']) {
                        $itinerary[$key]['itinerary_image'] = $itinerary[$key]['itinerary_image'];
                    } else {
                        $itinerary[$key]['itinerary_image'] = 'Noimage.png';
                    }

                    if ($itinerary[$key]['flight_data']) {
                        $itinerary[$key]['flight_data'] = json_decode($itinerary[$key]['flight_data'], true);
                    }

                    if ($itinerary[$key]['hotel_data']) {
                        $itinerary[$key]['hotel_data'] = json_decode($itinerary[$key]['hotel_data'], true);
                    }

                    if ($itinerary[$key]['transfer_data']) {
                        $itinerary[$key]['transfer_data'] = json_decode($itinerary[$key]['transfer_data'], true);
                    }
                }
            }
            $inclusion_list = $RestModel->holiday_inclusions($holiday_details['Inclusion'], $this->userauthdata['web_partner_id']);

            $exclusion_list = $RestModel->holiday_exclusion($holiday_details['Exclusion'], $this->userauthdata['web_partner_id']);

            $holiday_details['Inclusion'] = $inclusion_list;
            $holiday_details['Exclusion'] = $exclusion_list;

            //$super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];

            $adult_price = 0;
            $adult_extra_bed_price = 0;
            $child_extra_bed_price = 0;
            $child_price = 0;
            $total_pax = 0;

            foreach ($RoomData as $room_key => $room) {
                $total_pax += $room['total_adult'] + $room['total_child'];
                //if ($room['total_adult'] == 1 || $room['total_adult'] == 2) {
                if ($room['total_adult'] == 1) {
                    $adult_price += $price_details["{$HolidayCategory}AdultPrice"];
                } else {
                    $adult_price += $price_details["{$HolidayCategory}AdultPrice"] * $room['total_adult'];
                    // $adult_extra_bed_price += $price_details["{$HolidayCategory}ExtraBadPrice"];
                }

                if ($room['total_adult'] > 0) {
                    $child_price += $price_details["{$HolidayCategory}ChildPrice"] * $room['total_child'];
                    foreach ($room['child_details'] as $child_key => $child) {
                        if ($child['pax_type'] == "child") {
                            if ($child['child_need_bed'] == "yes") {
                                $child_extra_bed_price += $price_details["{$HolidayCategory}ExtraBadPrice"];
                            }
                        }
                    }
                }
            }

            $total_price = $adult_price + $adult_extra_bed_price + $child_price + $child_extra_bed_price;

            $super_admin_markup = $RestModel->get_super_admin_markup($this->userauthdata['web_partner_class_id'], $holiday_details['Themes'], $holiday_details['Destinations']);
            if ($super_admin_markup == "") {
                $super_admin_markup = array();
            }
            $super_admin_discount = $RestModel->get_super_admin_discount($this->userauthdata['web_partner_class_id'], $holiday_details['Themes'], $holiday_details['Destinations']);
            if ($super_admin_discount == "") {
                $super_admin_discount = array();
            }
            $super_admin_gst_state_code = $RestModel->get_super_admin_gst_state_code()['gst_state_code'];
            $priceBreakup = get_holiday_fare_summary($super_admin_markup, $super_admin_discount, $total_price, $this->userauthdata, $super_admin_gst_state_code);
            $TTSInvoiceAmount = $priceBreakup['TTSBreakup']['OfferedPrice'] + $priceBreakup['TDS'];

            $holiday_details['HolidayPriceSlot'] = $price_details;
            $holiday_details['AdminPriceBreakup'] = $priceBreakup;

            $holiday_details['PriceBreakup'] = $priceBreakup['TTSBreakup'];

            $holiday_details['SelectedRoomOccupancy'] = $RoomData;
            $holiday_details['HolidayCategory'] = $HolidayCategory;


            $days = count($itinerary);
            $Nights = $days - 1;
            $holiday_details['DaysNights'] = "{$days} Days & {$Nights} Nights";

            $holiday_details['HolidayImage'] = $holiday_details['HolidayImage'];

            $holiday_details['itinerary'] = $itinerary;
            $get_block_data = $RestModel->get_block_data($tts_search_token);


            $holiday_details['TravelDate'] = $TravelDate;

            if ($get_block_data) {
                if ($get_block_data['service'] == "booked") {
                    api_custom_message(101, 'Search token expired. ', false);
                } else {

                    $tts_response = array(
                        'SearchTokenId' => $tts_search_token,
                        "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                        "Result" => json_decode($get_block_data['response']),
                    );
                }
            } else {

                $RestModel->insert_tts_holiday_logs($this->userauthdata['web_partner_id'], $tts_search_token, $input, $holiday_details, 'blockholiday', $holiday_details['id'], $holiday_details);
                $tts_response = array(
                    'SearchTokenId' => $tts_search_token,
                    "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                    "Result" => $holiday_details,
                );
            }

            return $this->response->setContentType('application/json')->setJson($tts_response);
        }
    }


    public function holiday_block_detail()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->details_holiday_validation);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $tts_search_token = $input['SearchTokenId'];
            $RestModel = new RestModel();

            $get_block_data = $RestModel->get_block_detail_data($tts_search_token);

            if ($get_block_data) {
                $tts_response = array(
                    'SearchTokenId' => $tts_search_token,
                    "Error" => array("ErrorCode" => 0, "ErrorMessage" => ""),
                    "Result" => json_decode($get_block_data['response']),
                );
                return $this->response->setContentType('application/json')->setJson($tts_response);
            } else {
                api_custom_message(101, 'No data found. ', false);
            }
        }
    }

    function generateVoucherInvoice()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingId = $input['BookingId'];
            $BookingToken = $input['SearchTokenId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingId, "*");
            $package_details = json_decode($BookingDetail['package_details'], true);

            if (isset ($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {

                $travelersInfo = $RestModel->getTravellerDetailById($BookingDetail['id']);
                $lead_pax = "";
                foreach ($travelersInfo as $traveler) {
                    if ($traveler['lead_pax'] == 1) {
                        $lead_pax = $traveler['title'] . ' ' . $traveler['first_name'] . ' ' . $traveler['last_name'];
                        break;
                    }
                }
                $data['lead_pax'] = $lead_pax;

                $data['TravellersCount'] = count($travelersInfo);

                $data['Travellers'] = $travelersInfo;
                $data['travel_date'] = $BookingDetail['travel_date'];

                $totalMarkupablepax = $data['Travellers'];
                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");

                $BookingStatus = $BookingDetail['booking_status'];
                $PaymentStatus = $BookingDetail['payment_status'];
                $FareBreakUp = [];
                $fareBreakupArrayInvoice = [];
                $WebPDiscount = 0;
                $WebPDiscount = 0;
                $WebPPerPaxMarkUp = 0;
                $TicketInvoiceData = [];
                $paxName = "";
                if ($UserType == "WebPartner") {
                    $logoFolderName = "logo";
                    $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                    $WebPMarkUp = isset ($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                    $WebPDiscount = isset ($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $WebPMarkUp, "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $WebPDiscount, "LabelText" => "Discount (-)"),
                            /*"PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'] + $WebPMarkUp - $WebPDiscount, "LabelText" => "Published Price"),
                            "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),*/
                            "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                    );

                    // pr($fareBreakupArray); die;

                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Package Cost"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                            "PublishedPrice" => array("Value" => '', "LabelText" => ""),
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                        "GSTDetails" => $fareBreakupArray['GST']
                    );
                }
                if ($UserType == "Admin") {
                    $logoFolderName = "logo";
                    $fareBreakupArray = json_decode($BookingDetail['super_admin_fare_break_up'], true);
                    $discount = 0;
                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $WebPMarkUp, "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $discount, "LabelText" => "Discount (-)"),
                            "PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),
                            "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),
                            "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                    );
                }

                if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $InvoiceNumber = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "service" => 'holiday', "web_partner_id" => $BookingDetail['web_partner_id']))['acc_ref_number'];

                    $data['InvoiceNumber'] = $InvoiceNumber;
                    $data['InvoiceDate'] = custom_date_format($BookingDetail['created']);

                    $FareBreakUp = array();
                    $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                    $markup = isset ($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                    $discount = isset ($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;

                    $fareBreakupArrayInvoice = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $WebPMarkUp, "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $WebPDiscount, "LabelText" => "Discount (-)"),
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['PublishedPrice'] + $WebPMarkUp - $WebPDiscount, "LabelText" => "Total Amount"),
                        "GSTDetails" => $fareBreakupArray['GST']
                    );
                }


                if ($HtmlType == "AgencyInvoice") {
                    $fareBreakupArrayInvoice = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => 0, "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                        "GSTDetails" => $fareBreakupArray['GST']
                    );
                }

                if (isset ($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $FareBreakUp = array();
                }


                $data['TicketInvoiceData'] = $TicketInvoiceData;
                $data['BookingRefNumber'] = $BookingDetail['booking_ref_number'];

                $data['Name'] = $package_details['Name'];
                $data['HolidayCategory'] = $package_details['HolidayCategory'];
                $data['Inclusion'] = $package_details['Inclusion'];
                $data['Exclusion'] = $package_details['Exclusion'];
                $data['Itinerary'] = $package_details['itinerary'];
                $data['DayNights'] = $BookingDetail['day_nights'];
                $data['BookingDate'] = custom_date_format($BookingDetail['created']);
                $data['BookingStatus'] = $BookingStatus;
                $data['PaymentStatus'] = $PaymentStatus;
                $data['FareBreakUp'] = $FareBreakUp;

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
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();


                if ($HtmlType == "CustomerInvoice" || $HtmlType == "AgencyInvoice") {
                    $data['FareBreakUp'] = $fareBreakupArrayInvoice;
                } else {
                    $data['FareBreakUp'] = $FareBreakUp;
                }
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                if ($partnerInfo['company_logo'] != "") {
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $partnerInfo['company_logo'];
                }
                if ($partnerInfo['company_logo'] == "") {
                    $data['CompanyLogo'] = root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['logo'];
                }
                if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
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
                    $data['City'] = '';
                    $data['CompanyName'] = $paxName;
                    $data['Address'] = '';
                    $data['Pincode'] = "";
                    $data['SupportNo'] = "";
                    $data['SupportEmail'] = "";
                    $data['Country'] = '';
                    $data['PanNo'] = "";
                    $data['GstNo'] = isset ($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";
                }


                if ($HtmlType == "Ticket") {
                    $html = view('Modules\Holidayservice\Views\ticket', $data);
                } else if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Holidayservice\Views\invoice', $data);
                }
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }

    function generateWLVoucherInvoice()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $BookingId = $input['BookingId'];
            $BookingToken = $input['SearchTokenId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingId, "*");
            $package_details = json_decode($BookingDetail['package_details'], true);
            $web_partner_id = $BookingDetail['web_partner_id'];
            $bookingSource = array(
                'wl-customer' => 'Wl_b2c',
                'wl-agent' => 'Wl_b2b',
                'wl-agent-customer' => 'Wl_b2b'
            );
            if (isset ($BookingDetail['id']) && $BookingId == $BookingDetail['id'] && isset ($bookingSource[$UserType]) && $BookingDetail['booking_source'] == $bookingSource[$UserType] || ($UserType == "WebPartner" && $HtmlType == "AgencyInvoice")) {
                $addagentMarkupInTax = 0;
                $addagentMarkupInServiceCharge = 0;
                $AgentWebPDiscount = 0;
                $AgentWebPMarkUp = 0;
                $couponAmount = 0;
                $conveniencefee = 0;
                $logoFolderName = "logo";
                $TicketInvoiceData = array();
                $FareBreakUp = array();
                if ($web_partner_id) {
                    $web_partner_setting = $RestModel->getwhiteLabelSetting($web_partner_id);
                }
                $travelersInfo = $RestModel->getTravellerDetailById($BookingDetail['id']);
                $lead_pax = "";
                foreach ($travelersInfo as $traveler) {
                    if ($traveler['lead_pax'] == 1) {
                        $lead_pax = $traveler['title'] . ' ' . $traveler['first_name'] . ' ' . $traveler['last_name'];
                        break;
                    }
                }
                $data['lead_pax'] = $lead_pax;

                $data['TravellersCount'] = count($travelersInfo);

                $data['Travellers'] = $travelersInfo;
                $data['travel_date'] = $BookingDetail['travel_date'];

                $totalMarkupablepax = $data['Travellers'];
                if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                    $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
                    $accountLogTable = "customer_account_log";
                    $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'holiday', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                }
                if ($UserType == "WebPartner") {
                    $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);
                    $accountLogTable = "web_partner_account_log";
                    $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'holiday', "web_partner_id" => $BookingDetail['web_partner_id']);
                }

                if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                    $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
                    $accountLogTable = "agent_account_log";
                    $accountWhere = array("booking_ref_no" => $BookingDetail['id'], "service" => 'holiday', "web_partner_id" => $BookingDetail['web_partner_id'], 'action_type' => 'booking');
                    if ($UserType == "wl-agent-customer") {
                        $AgentWebPMarkUp = isset ($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                        $AgentWebPDiscount = isset ($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                        $AgentWebPDisplayMarkup = isset ($fareBreakupArray['AgentWebPDisplayMarkup']) ? $fareBreakupArray['AgentWebPDisplayMarkup'] : "in_tax";
                        if (isset ($fareBreakupArray['AgentWebPDisplayMarkup']) && $fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_service_charge') {
                            $addagentMarkupInServiceCharge = $AgentWebPMarkUp;
                        } else {
                            $addagentMarkupInTax = $AgentWebPMarkUp;
                        }
                    }
                }
                $couponInfo = json_decode($BookingDetail['coupon_info'], true);
                if (!empty ($couponInfo)) {
                    $couponAmount = (isset ($couponInfo['couponAmount']) && $couponInfo['couponAmount'] > 0) ? $couponInfo['couponAmount'] : 0;
                }
                $gst = $fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'];
                $conveniencefee = (isset ($BookingDetail['conveniencefee']) && $BookingDetail['conveniencefee'] > 0) ? $BookingDetail['conveniencefee'] : 0;
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'] + $addagentMarkupInTax, "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'] + $addagentMarkupInServiceCharge, "LabelText" => "Other & Service Charges"),
                        "Discount" => array("Value" => $AgentWebPDiscount + $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                        "gst" => array("Value" => $gst, "LabelText" => "GST (+)"),
                    ),
                    "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] + $AgentWebPMarkUp - $AgentWebPDiscount - $couponAmount + $conveniencefee, "LabelText" => "Total Amount"),
                );
                if ($fareBreakupArray['TDS']) {
                    $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)");
                }
                if ($couponAmount) {
                    $FareBreakUp['FareBreakup']['Promocode'] = array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)");
                }
                if ($conveniencefee) {
                    $FareBreakUp['FareBreakup']['conveniencefee'] = array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)");
                }

                if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $InvoiceNumber = $RestModel->getBookingAccountInfo($accountLogTable, "invoice_number", $accountWhere);
                    $data['InvoiceNumber'] = (isset ($InvoiceNumber['invoice_number'])) ? $InvoiceNumber['invoice_number'] : '';
                    $data['InvoiceDate'] = custom_date_format($BookingDetail['created']);
                }
                $BookingStatus = $BookingDetail['booking_status'];
                $PaymentStatus = $BookingDetail['payment_status'];
                if (isset ($input['WithPrice']) && $input['WithPrice'] == 0) {
                    $FareBreakUp = array();
                }
                if ($web_partner_setting['is_direct_website'] == "active") {
                    $root_url = root_url;
                    if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                        $partnerInfo = [];
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                            $partnerInfo = $SuperAdminInfo;
                        }
                    }

                    if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                        $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number");
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if ($UserType == "wl-agent-customer") {
                            $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number"); //SuperAdminInfo
                            $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_number AS company_gst_no,pan_number"); //partnerInfo
                            if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                                $SuperAdminInfo = $partnerInfo;
                            }
                        }
                    }
                    if ($UserType == "WebPartner") {
                        $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                    }
                } else {
                    $root_url = root_url;
                    $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                    $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                }

                $data['BookingRefNumber'] = $BookingDetail['booking_ref_number'];

                $data['Name'] = $package_details['Name'];
                $data['HolidayCategory'] = $package_details['HolidayCategory'];
                $data['Inclusion'] = $package_details['Inclusion'];
                $data['Exclusion'] = $package_details['Exclusion'];
                $data['Itinerary'] = $package_details['itinerary'];
                $data['DayNights'] = $BookingDetail['day_nights'];
                $data['BookingDate'] = custom_date_format($BookingDetail['created']);
                $data['BookingStatus'] = $BookingStatus;
                $data['PaymentStatus'] = $PaymentStatus;
                $data['FareBreakUp'] = $FareBreakUp;
                if (!empty ($partnerInfo)) {
                    $data['Country'] = $partnerInfo['country'];
                    $data['State'] = $partnerInfo['state'];
                    $data['City'] = $partnerInfo['city'];
                    $data['Address'] = $partnerInfo['address'];
                    $data['Pincode'] = $partnerInfo['pincode'];
                    $data['SupportNo'] = $partnerInfo['support_no'];
                    $data['SupportEmail'] = $partnerInfo['support_email'];
                    $data['GstNo'] = $partnerInfo['company_gst_no'];
                    $data['PanNo'] = $partnerInfo['pan_number'];
                    $data['CompanyName'] = $partnerInfo['company_name'];
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

                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();
                if(isset($input['RequestBy']) && $input['RequestBy'] == "WebPartner"){
                    $data['CompanyLogo'] = $root_url . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                }
                if(isset($input['RequestBy']) && $input['RequestBy'] == "Agent"){
                    $logoFolderName = "agent/logo";
                    $imageString = ($input['HtmlType'] != "Ticket")?$partnerInfo['company_logo']:$SuperAdminInfo['company_logo'];
                    $data['CompanyLogo'] = $root_url . 'uploads/' . $logoFolderName . '/' . $imageString;
                }
                if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 0) {
                    $data['State'] = $SuperAdminInfo['state'];
                    $data['City'] = $SuperAdminInfo['city'];
                    $data['CompanyName'] = $SuperAdminInfo['company_name'];
                    $data['Address'] = $SuperAdminInfo['address'];
                    $data['Pincode'] = $SuperAdminInfo['pincode'];
                    $data['SupportNo'] = $SuperAdminInfo['support_no'];
                    $data['SupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['Country'] = $SuperAdminInfo['country'];
                    $data['CompanyLogo'] = $root_url . '/' . 'uploads/' . $logoFolderName . '/' . $SuperAdminInfo['company_logo'];
                    $data['GstNo'] = (isset ($SuperAdminInfo['gst_state_code'])) ? $SuperAdminInfo['gst_state_code'] : $SuperAdminInfo['company_gst_no'];
                }
                if ($HtmlType == "CustomerInvoice") {
                    $data['SuperAdminState'] = $SuperAdminInfo['state'];
                    $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                    $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                    $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                    $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                    $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                    $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                    $data['SuperAdminGstNo'] = (isset ($SuperAdminInfo['gst_state_code'])) ? $SuperAdminInfo['gst_state_code'] : $SuperAdminInfo['company_gst_no'];
                    $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                    $data['State'] = "";
                    $data['City'] = '';
                    $data['CompanyName'] = $lead_pax;
                    $data['Address'] = '';
                    $data['Pincode'] = "";
                    $data['SupportNo'] = "";
                    $data['SupportEmail'] = "";
                    $data['Country'] = '';
                    $data['PanNo'] = "";
                    $data['GstNo'] = isset ($data['GstInfo']['gst_number']) ? $data['GstInfo']['gst_number'] : "";
                }


                if ($HtmlType == "Ticket") {
                    $html = view('Modules\Holidayservice\Views\ticket', $data);
                } else if ($HtmlType == "AgencyInvoice" || $HtmlType == "CustomerInvoice") {
                    $html = View('Modules\Holidayservice\Views\invoice', $data);
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
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            ;
            return api_custom_message(400, $message);
            $message = validation_string_message($this->validation->getErrors());
        } else {
            $BookingId = $input['BookingId'];
            $BookingToken = $input['SearchTokenId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingId, "*");
            $package_details = json_decode($BookingDetail['package_details'], true);
            if (isset ($BookingDetail['id']) && $BookingDetail['id'] == $BookingId) {

                $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,pan_number,state,company_gst_no,city,pincode,support_no,support_email,company_logo,pre_fix");
                $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo,company_gst_no,pan_number");
                $CreditNote = $RestModel->getaccountLogCreditNote($BookingDetail['web_partner_id'], $BookingDetail['refund_account_id']);
                $BookingDate = $BookingDetail['created'];

                $data['CreditNoteDate'] = custom_date_format($CreditNote['created']);
                $data['CreditNoteNo'] = $CreditNote['acc_ref_number'];

                $data['InvoiceNumber'] = $RestModel->getWebpartnerBookingAccountInfo("acc_ref_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "holiday"))['acc_ref_number'];


                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['ConfirmationNo'] = $BookingDetail['confirmation_no'];

                $data['amendment_charges'] = $BookingDetail['amendment_charges'];

                $data['Name'] = $package_details['Name'];
                $data['HolidayCategory'] = $package_details['HolidayCategory'];

                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];
                $data['CompanyLogo'] = $partnerInfo['company_logo'];
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];

                $fareBreakupArray = json_decode($BookingDetail['web_partner_fare_break_up'], true);

                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => 0, "LabelText" => "Other & Service Charges"),
                        "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                    "GSTDetails" => $fareBreakupArray['GST']
                );

                $data['FareBreakUp'] = $FareBreakUp;
                $html = View('Modules\Holidayservice\Views\holiday-credit-note', $data);

                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array('Html' => $html));
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }

    function generateWLCreditNote()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->generate_html_voucher_invoice);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
            $message = validation_string_message($this->validation->getErrors());
        } else {
            $BookingId = $input['BookingId'];
            $BookingToken = $input['SearchTokenId'];
            $HtmlType = $input['HtmlType'];
            $UserType = $input['UserType'];
            $ViewService = $input['ViewService'];
            $ViewSize = $input['ViewSize'];
            $RestModel = new RestModel();
            $BookingDetail = $RestModel->getbookingDetailByToken($BookingId, "*");
            $package_details = json_decode($BookingDetail['package_details'], true);
            if (isset ($BookingDetail['id']) && $BookingId == $BookingDetail['id']) {
                $web_partner_id = $BookingDetail['web_partner_id'];
                if ($BookingDetail['booking_source'] == "Wl_b2b") {
                    $accountTableName = "agent_account_log";
                    $account_id = $BookingDetail['wl_agent_id'];
                    $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
                    $key = "agent";
                } else {
                    $accountTableName = "customer_account_log";
                    $account_id = $BookingDetail['wl_customer_id'];
                    $key = "customer";
                    $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
                }

                if ($web_partner_id) {
                    $web_partner_setting = $RestModel->getwhiteLabelSetting($web_partner_id);
                }
                $CreditNote = $RestModel->getaccountLogWlCreditNote($accountTableName, $BookingDetail['web_partner_id'], $BookingDetail['refund_account_id']);
                $BookingDate = $BookingDetail['created'];

                if ($web_partner_setting['is_direct_website'] == "active") {
                    $root_url = $web_partner_setting['domain_name'];
                    if ($BookingDetail['booking_source'] == "Wl_b2c" && $UserType == "wl-customer") {
                        $partnerInfo = [];
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                            $partnerInfo = $SuperAdminInfo;
                        }
                    }

                    if ($BookingDetail['booking_source'] == "Wl_b2b" && ($UserType == "wl-agent-customer" || $UserType == "wl-agent")) {
                        $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_state_code AS company_gst_no,pan_number");
                        $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        if ($UserType == "wl-agent-customer") {
                            $SuperAdminInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number"); //SuperAdminInfo
                            $partnerInfo = $RestModel->GetpartnerInfo("agent", array("id" => $BookingDetail['wl_agent_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,gst_state_code AS company_gst_no,pan_number"); //partnerInfo
                            if (isset ($input['WithAgencyDetail']) && $input['WithAgencyDetail'] == 1) {
                                $SuperAdminInfo = $partnerInfo;
                            }
                        }
                    }
                    if ($UserType == "WebPartner") {
                        $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                        $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                    }
                } else {
                    $root_url = root_url;
                    $partnerInfo = $RestModel->GetpartnerInfo("web_partner", array("id" => $BookingDetail['web_partner_id']), "company_name,address,country,state,city,pincode,support_no,support_email,company_logo,company_gst_no,pre_fix,pan_number");
                    $SuperAdminInfo = $RestModel->super_admin_detail("company_name,address,country,state,city,pincode,support_no,support_email,logo AS company_logo,company_gst_no,pan_number");
                }

                $data['CreditNoteDate'] = custom_date_format($CreditNote['created']);
                $data['CreditNoteNo'] = $CreditNote['invoice_number'];
                $data['InvoiceNumber'] = $RestModel->getBookingAccountInfo($accountTableName, "acc_ref_number,invoice_number", array("booking_ref_no" => $BookingDetail['id'], "web_partner_id" => $BookingDetail['web_partner_id'], "service" => "holiday", "action_type" => "booking"))['invoice_number'];

                $data['InvoiceDate'] = custom_date_format($BookingDate);
                $data['BookingDate'] = custom_date_format($BookingDate);
                $data['ConfirmationNo'] = $BookingDetail['confirmation_no'];

                $data['amendment_charges'] = $BookingDetail['amendment_charges'];
                $data['Name'] = $package_details['Name'];
                $data['HolidayCategory'] = $package_details['HolidayCategory'];

                $data['CompanyName'] = $partnerInfo['company_name'];
                $data['Address'] = $partnerInfo['address'];
                $data['Country'] = $partnerInfo['country'];
                $data['State'] = $partnerInfo['state'];
                $data['City'] = $partnerInfo['city'];
                $data['Pincode'] = $partnerInfo['pincode'];
                $data['SupportNo'] = $partnerInfo['support_no'];
                $data['SupportEmail'] = $partnerInfo['support_email'];
                $data['CompanyLogo'] = $partnerInfo['company_logo'];
                $data['GstInfo'] = $BookingDetail['gst_info'] != null ? json_decode($BookingDetail['gst_info'], true) : array();

                $data['SuperAdminCompanyName'] = $SuperAdminInfo['company_name'];
                $data['SuperAdminAddress'] = $SuperAdminInfo['address'];
                $data['SuperAdminPincode'] = $SuperAdminInfo['pincode'];
                $data['SuperAdminSupportNo'] = $SuperAdminInfo['support_no'];
                $data['SuperAdminSupportEmail'] = $SuperAdminInfo['support_email'];
                $data['SuperAdminGstNo'] = $SuperAdminInfo['company_gst_no'];
                $data['SuperAdminPanNo'] = $SuperAdminInfo['pan_number'];
                $data['SuperAdminCountry'] = $SuperAdminInfo['country'];
                $data['SuperAdminState'] = $SuperAdminInfo['state'];
                $data['SuperAdminCity'] = $SuperAdminInfo['city'];
                $data['GstNo'] = $partnerInfo['company_gst_no'];
                $data['PanNo'] = $partnerInfo['pan_number'];
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Package Cost"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => 0, "LabelText" => "Other & Service Charges"),
                        "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                    "GSTDetails" => $fareBreakupArray['GST']
                );
                $data['FareBreakUp'] = $FareBreakUp;
                $html = View('Modules\Holidayservice\Views\holiday-credit-note', $data);

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
            if (isset ($input['AmendmentId']) && $input['RequesterInfo']['Requester'] == "SuperAdmin") {
                $whereCondition = array("id" => $input['AmendmentId'], "booking_ref_no" => $input['BookingId']);
                $amendmentupdateData = array(
                    "amendment_status" => $input['AmendmentStatus'],
                    "remark_from_web_partner" => $input['Remarks'],
                    "sup_staff_id" => isset ($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                    "modified" => create_date(),
                );
                $RestModel->updateUserData("holiday_amendment", $whereCondition, $amendmentupdateData);
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Status Successfully Updated"), "Result" => array("AmendmentId" => $input['AmendmentId']));
            } elseif ((isset ($input['RequesterInfo']['wl_agent_id']) && $input['RequesterInfo']['Requester'] == "Whitelabel")) {

                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);

                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("holiday_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId']), "id,amendment_status");

                    if (!$AmendmentStatus) {
                        $amendmentsaveData = array(
                            "wl_agent_id" => $input['RequesterInfo']['wl_agent_id'],
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "wl_agent_staff_id" => $input['RequesterInfo']['RequesterId'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => isset ($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "remark_from_user" => $input['Remarks'],
                            "created" => create_date(),
                        );
                        $amendmentId = $RestModel->insertData("holiday_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_holiday_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } elseif ((isset ($input['RequesterInfo']['wl_customer_id']) && $input['RequesterInfo']['Requester'] == "WhitelabelB2C")) {
                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);

                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("holiday_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId']), "id,amendment_status");

                    if (!$AmendmentStatus) {
                        $amendmentsaveData = array(
                            "wl_customer_id" => $input['RequesterInfo']['wl_customer_id'],
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => isset ($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "remark_from_user" => $input['Remarks'],
                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("holiday_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_holiday_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {

                $verify_data = $RestModel->get_booking_info(['id' => $input['BookingId'], 'web_partner_id' => $this->userauthdata['web_partner_id']]);

                if ($verify_data) {
                    $AmendmentStatus = $RestModel->GetAmendmentInfo("holiday_amendment", array("amendment_type" => $input['Type'], "booking_ref_no" => $input['BookingId']), "id,amendment_status");

                    if (!$AmendmentStatus) {
                        $amendmentsaveData = array(
                            "web_partner_id" => $this->userauthdata['web_partner_id'],
                            "booking_ref_no" => $input['BookingId'],
                            "amendment_type" => $input['Type'],
                            "amendment_status" => isset ($input['AmendmentStatus']) ? $input['AmendmentStatus'] : "requested",
                            "agent_staff_id" => isset ($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                            "remark_from_web_partner" => $input['Remarks'],

                            "created" => create_date(),

                        );
                        $amendmentId = $RestModel->insertData("holiday_amendment", $amendmentsaveData);
                        $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => "Amendment Successfully Send"), "Result" => array("AmendmentId" => $amendmentId));
                    } else {
                        $response = array("Error" => array("ErrorCode" => 400, "ErrorMessage" => "Amendment in Progress"));
                    }
                } else {
                    $message = "Invalid Details.";
                    return api_custom_message(400, $message);
                }
                // Insert TTS Logs
                $RestModel->insert_tts_holiday_logs($this->userauthdata['web_partner_id'], $verify_data['tts_search_token'], $input, $response, 'amendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            }
        }
    }



    public function SubmitAmendmentAdmin()
    {
        $input = json_validate(file_get_contents("php://input"));
        $validate = new Validation();
        $this->validation->setRules($validate->amendment_validation_admin);
        $rules = $this->validation->run($input);
        if (!$rules) {
            $message = validation_string_message($this->validation->getErrors());
            return api_custom_message(400, $message);
        } else {
            $RestModel = new RestModel();
            $verify_data = $RestModel->get_book_detail(['id' => $input['BookingId']]);
            if ($verify_data) {
                $amendmentsaveData = array(
                    "web_partner_id" => $input['WebPartnerId'],
                    "amendment_id" => $input['AmendmentId'],
                    "booking_ref_no" => $input['BookingId'],
                    "amendment_type" => $input['Type'],
                    "amendment_status" => $input['AmendmentStatus'],
                    "sup_staff_id" => isset ($input['RequesterInfo']['RequesterId']) ? $input['RequesterInfo']['RequesterId'] : null,
                    "remark_from_super_admin" => $input['Remarks'],
                    "created" => create_date(),
                );
                $amendmentId = $RestModel->insertData("holiday_amendment", $amendmentsaveData);
                $response = array("Error" => array("ErrorCode" => 0, "ErrorMessage" => ""), "Result" => array("AmendmentId" => $amendmentId));


                // Insert TTS Logs
                $RestModel->insert_tts_holiday_logs($input['RequesterInfo']['RequesterId'], $verify_data['tts_search_token'], $input, '', 'adminamendmentrequest');
                return $this->response->setContentType('application/json')->setJSON($response);
            } else {
                $message = "Invalid Details.";
                return api_custom_message(400, $message);
            }
        }
    }
}
