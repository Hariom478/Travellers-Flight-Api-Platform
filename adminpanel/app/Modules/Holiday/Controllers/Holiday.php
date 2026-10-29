<?php

namespace Modules\Holiday\Controllers;

use App\Modules\Holiday\Models\HolidayThemesModel;
use App\Modules\Holiday\Models\HolidayDestinationModel;
use App\Modules\Holiday\Models\HolidayInclusionModel;
use App\Modules\Holiday\Models\HolidayExclusionModel;
use App\Modules\Holiday\Models\HolidayListModel;
use App\Modules\Holiday\Models\HolidayGalleryModel;
use App\Modules\Holiday\Models\HolidayPriceModel;
use App\Modules\Holiday\Models\HolidayItineraryModel;
use App\Modules\Holiday\Models\HolidayCitiesModel;
use App\Modules\Holiday\Models\HolidayModel;
use App\Modules\Holiday\Models\HolidayQueryModel;
use App\Modules\Holiday\Models\HolidayMarkupModel;
use App\Modules\Holiday\Models\HolidayDiscountModel;
use App\Modules\Holiday\Models\AgentClassModel;
use App\Models\CommonModel;
use App\Modules\Holiday\Models\AmendmentModel;

use App\Controllers\BaseController;
use Modules\Holiday\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Holiday extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Holiday";

        $this->Services = API_REQUEST_URL . '/holidayservice/rest/';
        $this->folder_name = 'holiday';
        if (permission_access_error("Holiday", "Holiday_Module")) {
        }

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->Google_Place_API_Key = admin_cookie_data()['admin_comapny_detail']['google_places_api_key'];

        $this->web_partner_details = admin_cookie_data()['admin_user_details'];

        helper('Modules\Holiday\Helpers\holiday');
    }

    function AssignUpdateHolidayTicket()
    {
        $bookingReferenceNumber = dev_decode($this->request->uri->getSegment(3));
        $HolidayModel = new HolidayModel();
        $BookingDetail = $HolidayModel->getBookingDetailData($this->web_partner_id, $bookingReferenceNumber);
        if ($BookingDetail) {
            $checkbookingflighttime = checkbookingflighttime($BookingDetail['created'], 'Holiday');
            if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->redirect($this->request->getUserAgent()->getReferrer());
            }
            $updateData['webpartner_assign_user'] = $this->user_id;
            $HolidayModel->updateData("holiday_booking_list", array("booking_ref_number" => $bookingReferenceNumber), $updateData);
            $message = array("StatusCode" => 0, "Message" => "Voucher assign successfully", "Class" => "success_popup", "Reload" => "true");
            $this->session->setFlashdata('Message', $message);
            return $this->response->redirect($this->request->getUserAgent()->getReferrer());
        } else {
            return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
        }
    }

    public function get_google_place_id($data)
    {

        //$data = $this->request->getGet();
        $google_key = $this->Google_Place_API_Key;
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($data);
        $url = $url . "&key=" . $google_key;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result_array = json_decode($result, true);
        $search_data = "";
        if ($result_array['status'] == "OK") {
            $lat = $result_array['results'][0]['geometry']['location']['lat'];
            $lng = $result_array['results'][0]['geometry']['location']['lng'];
            $search_data = $lat . ',' . $lng;
        }
        return $search_data;
    }
    public function get_google_placeall($data)
    {

        //$data = $this->request->getGet();
        $google_key = $this->Google_Place_API_Key;
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($data);

        $url = $url . "&key=" . $google_key;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result_array = json_decode($result, true);



        return $result_array;
    }

    public function index()
    {
        if (permission_access_error("Holiday", "holiday_list")) {
            $HolidayListModel = new HolidayListModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayListModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayListModel->holiday_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\holiday-list",
                'pager' => $HolidayListModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_holiday_list()
    {
        if (permission_access_error("Holiday", "holiday_add")) {
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayInclusionModel = new HolidayInclusionModel();
            $HolidayExclusionModel = new HolidayExclusionModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $HolidayModel = new HolidayModel();
            $themes_lists = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination_lists = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $inclusion_lists = $HolidayInclusionModel->holiday_inclusion_list_select($this->web_partner_id);
            $exclusion_lists = $HolidayExclusionModel->holiday_exclusion_list_select($this->web_partner_id);
            $settings_list = $HolidayModel->holiday_settings_list_select($this->web_partner_id);
            $setting_data = array_column($settings_list, 'value', 'key_source');
            $data = [
                'title' => $this->title,
                'themes_lists' => $themes_lists,
                'inclusion_lists' => $inclusion_lists,
                'exclusion_lists' => $exclusion_lists,
                'destination_lists' => $destination_lists,
                'setting_data' => $setting_data,
                'view' => "Holiday\Views\add-holiday-list",
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_holiday_list_save()
    {
        if (permission_access_error("Holiday", "holiday_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_list_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayListModel = new HolidayListModel();
                $data = $this->request->getPost();
                $submit_type = $data['submit_type'];
                unset($data['submit_type']);


                $value = $data['holiday_slug'];
                $existingValue = $HolidayListModel->CheckUniqueSlug($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["holiday_slug" => "Holiday slug already exists"]);
                    return $this->response->setJSON($data_validation);
                }

                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'holiday_image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 600, 'height' => 600);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    if (isset($data['price_on_request']) && $data['price_on_request'] == 1) {
                        $data['price_on_request'] = 1;
                    } else {
                        $data['price_on_request'] = 0;
                    }
                    if (isset($data['passport_required_adult']) && $data['passport_required_adult'] == 1) {
                        $data['passport_required_adult'] = 1;
                    } else {
                        $data['passport_required_adult'] = 0;
                    }
                    if (isset($data['passport_required_child']) && $data['passport_required_child'] == 1) {
                        $data['passport_required_child'] = 1;
                    } else {
                        $data['passport_required_child'] = 0;
                    }
                    if (isset($data['pan_card_required_adult']) && $data['pan_card_required_adult'] == 1) {
                        $data['pan_card_required_adult'] = 1;
                    } else {
                        $data['pan_card_required_adult'] = 0;
                    }
                    if (isset($data['pan_card_required_child']) && $data['pan_card_required_child'] == 1) {
                        $data['pan_card_required_child'] = 1;
                    } else {
                        $data['pan_card_required_child'] = 0;
                    }
                    if (isset($data['book_online']) && $data['book_online'] == 1) {
                        $data['book_online'] = 1;
                    } else {
                        $data['book_online'] = 0;
                    }

                    $check_price = 0;
                    if ($data['starting_price_standard'] == '' || $data['starting_price_standard'] == 0) {
                        $check_price += 1;
                    }
                    if ($data['starting_price_deluxe'] == '' || $data['starting_price_deluxe'] == 0) {
                        $check_price += 1;
                    }

                    if ($data['starting_price_luxury'] == '' || $data['starting_price_luxury'] == 0) {
                        $check_price += 1;
                    }

                    if ($check_price == 3) {
                        $data['book_online'] = 0;
                        $data['price_on_request'] = 1;
                    }

                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['status'] = 'inactive';
                    $data['holiday_includes'] = implode(',', $data['holiday_includes']);
                    $data['holiday_themes_id'] = implode(',', $data['holiday_themes_id']);
                    $data['holiday_destination_id'] = implode(',', $data['holiday_destination_id']);
                    $data['holiday_inclusion_id'] = implode(',', $data['holiday_inclusion_id']);
                    $data['holiday_exclusion_id'] = implode(',', $data['holiday_exclusion_id']);

                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $HolidayListModel->insert($data);

                    $Redirect_Url = site_url('holiday/holiday-itinerary-list/') . dev_encode($added_data);

                    if ($added_data) {
                        if ($submit_type == 2) {
                            $StatusCode = 3;
                        } else {
                            $StatusCode = 0;
                        }
                        $message = array("StatusCode" => $StatusCode, "Message" => "holiday package successfully added", "Class" => "success_popup", "Reload" => "true", "Redirect_Url" => $Redirect_Url);
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday package not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_holiday_list_view()
    {
        if (permission_access_error("Holiday", "holiday_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayInclusionModel = new HolidayInclusionModel();
            $HolidayExclusionModel = new HolidayExclusionModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $HolidayListModel = new HolidayListModel();
            $themes_lists = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination_lists = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $inclusion_lists = $HolidayInclusionModel->holiday_inclusion_list_select($this->web_partner_id);
            $exclusion_lists = $HolidayExclusionModel->holiday_exclusion_list_select($this->web_partner_id);

            $details = $HolidayListModel->holiday_list_details($id, $this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'themes_lists' => $themes_lists,
                'inclusion_lists' => $inclusion_lists,
                'exclusion_lists' => $exclusion_lists,
                'destination_lists' => $destination_lists,
                'view' => "Holiday\Views\Edit-holiday-list",
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function edit_holiday_list()
    {
        if (permission_access_error("Holiday", "holiday_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'holiday_image';
            $validate = new Validation();

            $images = $this->request->getFile($field_name);
            if ($images->getName() == '') {
                unset($validate->holiday_list_validation[$field_name]);
            }

            $rules = $this->validate($validate->holiday_list_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayListModel = new HolidayListModel();
                $data = $this->request->getPost();

                $submit_type = $data['submit_type'];
                unset($data['submit_type']);
                $Redirect_Url = site_url('holiday/holiday-itinerary-list/') . dev_encode($id);
                $previous_data = $HolidayListModel->holiday_list_details($id, $this->web_partner_id);


                if ($previous_data['holiday_slug'] != $data['holiday_slug']) {
                    $value = $data['holiday_slug'];
                    $existingValue = $HolidayListModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["holiday_slug" => "holiday slug already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }



                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 600, 'height' => 600);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("./uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }
                        if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                            $data['show_on_home'] = 1;
                        } else {
                            $data['show_on_home'] = 0;
                        }
                        if (isset($data['price_on_request']) && $data['price_on_request'] == 1) {
                            $data['price_on_request'] = 1;
                        } else {
                            $data['price_on_request'] = 0;
                        }
                        if (isset($data['passport_required_adult']) && $data['passport_required_adult'] == 1) {
                            $data['passport_required_adult'] = 1;
                        } else {
                            $data['passport_required_adult'] = 0;
                        }
                        if (isset($data['passport_required_child']) && $data['passport_required_child'] == 1) {
                            $data['passport_required_child'] = 1;
                        } else {
                            $data['passport_required_child'] = 0;
                        }
                        if (isset($data['pan_card_required_adult']) && $data['pan_card_required_adult'] == 1) {
                            $data['pan_card_required_adult'] = 1;
                        } else {
                            $data['pan_card_required_adult'] = 0;
                        }
                        if (isset($data['pan_card_required_child']) && $data['pan_card_required_child'] == 1) {
                            $data['pan_card_required_child'] = 1;
                        } else {
                            $data['pan_card_required_child'] = 0;
                        }
                        if (isset($data['book_online']) && $data['book_online'] == 1) {
                            $data['book_online'] = 1;
                        } else {
                            $data['book_online'] = 0;
                        }

                        if ($previous_data['status'] == 'active') {
                            $data['status'] = 'active';
                        } else {
                            $data['status'] = 'inactive';
                        }
                        $check_price = 0;
                        if ($data['starting_price_standard'] == '' || $data['starting_price_standard'] == 0) {
                            $check_price += 1;
                        }
                        if ($data['starting_price_deluxe'] == '' || $data['starting_price_deluxe'] == 0) {
                            $check_price += 1;
                        }

                        if ($data['starting_price_luxury'] == '' || $data['starting_price_luxury'] == 0) {
                            $check_price += 1;
                        }

                        if ($check_price == 3) {
                            $data['book_online'] = 0;
                            $data['price_on_request'] = 1;
                        }


                        $data['holiday_includes'] = implode(',', $data['holiday_includes']);
                        $data['holiday_themes_id'] = implode(',', $data['holiday_themes_id']);
                        $data['holiday_destination_id'] = implode(',', $data['holiday_destination_id']);
                        $data['holiday_inclusion_id'] = implode(',', $data['holiday_inclusion_id']);
                        $data['holiday_exclusion_id'] = implode(',', $data['holiday_exclusion_id']);
                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $HolidayListModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {

                            if ($submit_type == 2) {
                                $StatusCode = 3;
                            } else {
                                $StatusCode = 0;
                            }
                            $message = array("StatusCode" => $StatusCode, "Message" => "holiday package successfully updated", "Class" => "success_popup", "Reload" => "true", "Redirect_Url" => $Redirect_Url);
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "holiday package not  updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    if (isset($data['price_on_request']) && $data['price_on_request'] == 1) {
                        $data['price_on_request'] = 1;
                    } else {
                        $data['price_on_request'] = 0;
                    }
                    if (isset($data['passport_required_adult']) && $data['passport_required_adult'] == 1) {
                        $data['passport_required_adult'] = 1;
                    } else {
                        $data['passport_required_adult'] = 0;
                    }


                    if (isset($data['passport_required_child']) && $data['passport_required_child'] == 1) {
                        $data['passport_required_child'] = 1;
                    } else {
                        $data['passport_required_child'] = 0;
                    }
                    if (isset($data['pan_card_required_adult']) && $data['pan_card_required_adult'] == 1) {
                        $data['pan_card_required_adult'] = 1;
                    } else {
                        $data['pan_card_required_adult'] = 0;
                    }
                    if (isset($data['pan_card_required_child']) && $data['pan_card_required_child'] == 1) {
                        $data['pan_card_required_child'] = 1;
                    } else {
                        $data['pan_card_required_child'] = 0;
                    }
                    if (isset($data['book_online']) && $data['book_online'] == 1) {
                        $data['book_online'] = 1;
                    } else {
                        $data['book_online'] = 0;
                    }

                    if ($previous_data['status'] == 'active') {
                        $data['status'] = 'active';
                    } else {
                        $data['status'] = 'inactive';
                    }

                    $check_price = 0;
                    if ($data['starting_price_standard'] == '' || $data['starting_price_standard'] == 0) {
                        $check_price += 1;
                    }
                    if ($data['starting_price_deluxe'] == '' || $data['starting_price_deluxe'] == 0) {
                        $check_price += 1;
                    }

                    if ($data['starting_price_luxury'] == '' || $data['starting_price_luxury'] == 0) {
                        $check_price += 1;
                    }

                    if ($check_price == 3) {
                        $data['book_online'] = 0;
                        $data['price_on_request'] = 1;
                    }
                    $data['holiday_includes'] = implode(',', $data['holiday_includes']);
                    $data['holiday_themes_id'] = implode(',', $data['holiday_themes_id']);
                    $data['holiday_destination_id'] = implode(',', $data['holiday_destination_id']);
                    $data['holiday_inclusion_id'] = implode(',', $data['holiday_inclusion_id']);
                    $data['holiday_exclusion_id'] = implode(',', $data['holiday_exclusion_id']);
                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $HolidayListModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        if ($submit_type == 2) {
                            $StatusCode = 3;
                        } else {
                            $StatusCode = 0;
                        }
                        $message = array("StatusCode" => $StatusCode, "Message" => "holiday package successfully updated", "Class" => "success_popup", "Reload" => "true", "Redirect_Url" => $Redirect_Url);
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday package not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_holiday_list()
    {
        if (permission_access_error("Holiday", "holiday_delete")) {
            $HolidayListModel = new HolidayListModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'holiday_image';

            foreach ($ids as $id) {
                $details = $HolidayListModel->delete_image($id, $this->web_partner_id);

                if ($details !== null && isset($details[$field_name])) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $HolidayListModel->remove_holiday_list($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday package successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday package not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function holiday_list_status_change()
    {
        if (permission_access_error("Holiday", "holiday_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayListModel = new HolidayListModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayListModel->holiday_list_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday package status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday package status changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function holiday_gallery()
    {
        if (permission_access("Holiday", "holiday_gallery_list")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $HolidayGalleryModel = new HolidayGalleryModel();
            $lists = $HolidayGalleryModel->holiday_gallery($holiday_list_id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'holiday_list_id' => $holiday_list_id,
                'list' => $lists,
                'pager' => $HolidayGalleryModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            $view = view('Modules\Holiday\Views\holiday-gallery', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_holiday_gallery()
    {
        if (permission_access("Holiday", "holiday_gallery_add")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_gallery_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayGalleryModel = new HolidayGalleryModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'images';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 800, 'height' => 450);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['holiday_list_id'] = $holiday_list_id;
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $HolidayGalleryModel->insert($data);

                    $lists = $HolidayGalleryModel->holiday_gallery($holiday_list_id, $this->web_partner_id);

                    $data = [
                        'title' => $this->title,
                        'holiday_list_id' => $holiday_list_id,
                        'list' => $lists,
                        'pager' => $HolidayGalleryModel->pager,
                    ];
                    $data_table = view('Modules\Holiday\Views\holiday-gallery-table-html', $data);

                    if ($added_data) {
                        $message = array("StatusCode" => 7, "Html_data" => $data_table, "Message" => "image successfully uploaded", "FormBlank" => "true", "Class" => "success_popup", "Reload" => "false");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "image not uploaded", "Class" => "error_popup", "Reload" => "false");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "false");
                }
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_holiday_gallery()
    {
        if (permission_access_error("Holiday", "holiday_gallery_delete")) {
            $HolidayGalleryModel = new HolidayGalleryModel();
            $ids = $this->request->getPost('checklist');
            $field_name = 'images';
            foreach ($ids as $id) {
                $details = $HolidayGalleryModel->delete_image($id, $this->web_partner_id);
                if ($details[$field_name]) {
                    if (file_exists("./uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $HolidayGalleryModel->remove_holiday_gallery($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday gallery image successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday gallery image not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            return $this->response->setJSON($message);
        }
    }

    public function holiday_gallery_status_change()
    {
        if (permission_access_error("Holiday", "holiday_gallery_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayGalleryModel = new HolidayGalleryModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayGalleryModel->holiday_gallery_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday gallery status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday gallery status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }

                return $this->response->setJSON($message);
            }
        }
    }

    public function holiday_price_list()
    {
        if (permission_access_error("Holiday", "holiday_price_list")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $HolidayPriceModel = new HolidayPriceModel();
            $lists = $HolidayPriceModel->holiday_price_list($holiday_list_id, $this->web_partner_id);
            $data = [
                'title' => $this->title,
                'holiday_list_id' => $holiday_list_id,
                'list' => $lists,
                'view' => 'Holiday\Views\holiday-price-list',
                'pager' => $HolidayPriceModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_holiday_price_view()
    {
        if (permission_access("Holiday", "holiday_price_add")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $data = [
                'title' => $this->title,
                'holiday_list_id' => $holiday_list_id,
            ];
            $details = view('Modules\Holiday\Views\add-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_holiday_price()
    {
        if (permission_access_error("Holiday", "holiday_price_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_price_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayPriceModel = new HolidayPriceModel();
                $data = $this->request->getPost();
                $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
                if ($data['start_date']) {
                    $data['start_date'] = strtotime($data['start_date']);
                }
                if ($data['end_date']) {
                    $data['end_date'] = strtotime($data['end_date']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['holiday_list_id'] = $holiday_list_id;
                $added_data = $HolidayPriceModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "price successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "price not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_holiday_price_view()
    {
        if (permission_access("Holiday", "holiday_price_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayPriceModel = new HolidayPriceModel();
            $details = $HolidayPriceModel->holiday_price_details($id, $this->web_partner_id);

            if (isset($details['start_date']) && $details['start_date'] != '') {
                $details['start_date'] = timestamp_to_date($details['start_date']);
            }
            if (isset($details['end_date']) && $details['end_date'] != '') {
                $details['end_date'] = timestamp_to_date($details['end_date']);
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\Holiday\Views\edit-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_holiday_price()
    {
        if (permission_access_error("Holiday", "holiday_price_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_price_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayPriceModel = new HolidayPriceModel();
                $data = $this->request->getPost();
                if ($data['start_date']) {
                    $data['start_date'] = strtotime($data['start_date']);
                }
                if ($data['end_date']) {
                    $data['end_date'] = strtotime($data['end_date']);
                }
                $data['modified'] = create_date();
                $added_data = $HolidayPriceModel->where("id", $id)->where('web_partner_id', $this->web_partner_id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "price successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "price not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_holiday_price()
    {
        if (permission_access_error("Holiday", "holiday_price_delete")) {
            $HolidayPriceModel = new HolidayPriceModel();
            $ids = $this->request->getPost('checklist');
            $delete = $HolidayPriceModel->remove_holiday_price($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "price successfully  deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "price not deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function themes_list()
    {
        if (permission_access_error("Holiday", "holiday_theme_list")) {
            $HolidayThemesModel = new HolidayThemesModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayThemesModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayThemesModel->holiday_themes_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\holiday-themes-list",
                'pager' => $HolidayThemesModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_themes_view()
    {
        if (permission_access("Holiday", "holiday_theme_add")) {
            $add_slider_view = view('Modules\Holiday\Views\add-holiday-theme');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_themes()
    {
        if (permission_access_error("Holiday", "holiday_theme_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_theme_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayThemesModel = new HolidayThemesModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'theme_image';
                $file = $this->request->getFile($field_name);


                $value = $data['theme_slug'];
                $existingValue = $HolidayThemesModel->CheckUniqueSlug($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["theme_slug" => "Theme slug already exists"]);
                    return $this->response->setJSON($data_validation);
                }



                $field_name_theme_icon = 'theme_icon';
                $name_theme_icon_file = $this->request->getFile($field_name_theme_icon);


                $resizeDim = array('width' => 400, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);

                $image_upload_icon = image_upload($name_theme_icon_file, $field_name_theme_icon, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0 && $image_upload_icon['status_code'] == 0) {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $data[$field_name_theme_icon] = $image_upload_icon['file_name'];
                    $added_data = $HolidayThemesModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "holiday theme successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday theme not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_themes_view()
    {
        if (permission_access("Holiday", "holiday_theme_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayThemesModel = new HolidayThemesModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $HolidayThemesModel->holiday_themes_details($id, $this->web_partner_id),
            ];

            $details = view('Modules\Holiday\Views\edit-holiday-theme', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_themes()
    {
        if (permission_access_error("Holiday", "holiday_theme_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'theme_image';
            $validate = new Validation();

            $slider_images = $this->request->getFile($field_name);
            if ($slider_images->getName() == '') {
                unset($validate->holiday_theme_validation[$field_name]);
            }

            $field_name_theme_icon = 'theme_icon';
            $name_theme_icon_file = $this->request->getFile($field_name_theme_icon);
            if ($name_theme_icon_file->getName() == '') {
                unset($validate->holiday_theme_validation[$field_name_theme_icon]);
            }

            $rules = $this->validate($validate->holiday_theme_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $resizeDim = array('width' => 400, 'height' => 400);
                $HolidayThemesModel = new HolidayThemesModel();
                $data = $this->request->getPost();
                $previous_data = $HolidayThemesModel->holiday_themes_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);

                if ($previous_data['theme_slug'] != $data['theme_slug']) {
                    $value = $data['theme_slug'];
                    $existingValue = $HolidayThemesModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["theme_slug" => "Theme slug already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }

                if ($file->getName() != '' || $name_theme_icon_file != '') {
                    if ($name_theme_icon_file->getName() || $slider_images->getName()) {
                        if ($slider_images->getName()) {
                            $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                            if ($image_upload['status_code'] == 0) {
                                if ($previous_data[$field_name]) {
                                    if (file_exists("./uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                        unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                                    }
                                }
                                $data[$field_name] = $image_upload['file_name'];
                            }
                        }

                        if ($name_theme_icon_file->getName()) {
                            $image_upload_icon = image_upload($name_theme_icon_file, $field_name_theme_icon, $this->folder_name, $resizeDim); //prd($name_theme_icon_file);
                            if ($image_upload_icon['status_code'] == 0) {
                                if ($previous_data[$field_name_theme_icon]) {
                                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name_theme_icon])) {
                                        unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name_theme_icon]);
                                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_theme_icon]);
                                    }
                                }
                                $data[$field_name_theme_icon] = $image_upload_icon['file_name'];
                            }
                        }

                        if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                            $data['show_on_home'] = 1;
                        } else {
                            $data['show_on_home'] = 0;
                        }
                        $data['modified'] = create_date();

                        $added_data = $HolidayThemesModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "holiday theme successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "holiday theme not  updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $data[$field_name_theme_icon] = $previous_data[$field_name_theme_icon];
                    $added_data = $HolidayThemesModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "holiday theme successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday theme not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_themes()
    {
        if (permission_access_error("Holiday", "holiday_theme_delete")) {
            $HolidayThemesModel = new HolidayThemesModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'theme_image';

            foreach ($ids as $id) {
                $blog_details = $HolidayThemesModel->delete_image($id, $this->web_partner_id);

                if ($blog_details !== null && isset($blog_details[$field_name])) {
                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }
                $delete = $HolidayThemesModel->remove_holiday_themes($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday theme successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday theme not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function themes_status_change()
    {
        if (permission_access_error("Holiday", "holiday_theme_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayThemesModel = new HolidayThemesModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayThemesModel->holiday_themes_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday theme status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday theme status changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function destination_list()
    {
        if (permission_access_error("Holiday", "holiday_destination_list")) {
            $HolidayDestinationModel = new HolidayDestinationModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayDestinationModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayDestinationModel->holiday_destination_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\holiday-destination-list",
                'pager' => $HolidayDestinationModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_destination_view()
    {
        if (permission_access("Holiday", "holiday_destination_add")) {
            $add_slider_view = view('Modules\Holiday\Views\add-holiday-destination');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_destination()
    {
        if (permission_access_error("Holiday", "holiday_destination_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_destination_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDestinationModel = new HolidayDestinationModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'destination_image';


                $value = $data['destination_slug'];
                $existingValue = $HolidayDestinationModel->CheckUniqueSlug($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["destination_slug" => "Destination slug already exists"]);
                    return $this->response->setJSON($data_validation);
                }



                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 400, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;

                    $data[$field_name] = $image_upload['file_name'];

                    $added_data = $HolidayDestinationModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "holiday destination successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday destination not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_destination_view()
    {
        if (permission_access("Holiday", "holiday_destination_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayDestinationModel = new HolidayDestinationModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $HolidayDestinationModel->holiday_destination_details($id, $this->web_partner_id),
            ];

            $details = view('Modules\Holiday\Views\edit-holiday-destination', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_destination()
    {
        if (permission_access_error("Holiday", "holiday_destination_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'destination_image';
            $validate = new Validation();

            $slider_images = $this->request->getFile($field_name);
            if ($slider_images->getName() == '') {
                unset($validate->holiday_destination_validation[$field_name]);
            }

            $rules = $this->validate($validate->holiday_destination_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDestinationModel = new HolidayDestinationModel();
                $data = $this->request->getPost();
                $previous_data = $HolidayDestinationModel->holiday_destination_details($id, $this->web_partner_id);



                if ($previous_data['destination_slug'] != $data['destination_slug']) {
                    $value = $data['destination_slug'];
                    $existingValue = $HolidayDestinationModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["destination_slug" => "Destination slug already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }





                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 400, 'height' => 400);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }
                        if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                            $data['show_on_home'] = 1;
                        } else {
                            $data['show_on_home'] = 0;
                        }
                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $HolidayDestinationModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "holiday destination successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "holiday destination not  updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    if (isset($data['show_on_home']) && $data['show_on_home'] == 1) {
                        $data['show_on_home'] = 1;
                    } else {
                        $data['show_on_home'] = 0;
                    }
                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $HolidayDestinationModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "holiday destination successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "holiday destination not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_destination()
    {
        if (permission_access_error("Holiday", "holiday_destination_delete")) {
            $HolidayDestinationModel = new HolidayDestinationModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'destination_image';

            foreach ($ids as $id) {
                $blog_details = $HolidayDestinationModel->delete_image($id, $this->web_partner_id);

                if ($blog_details !== null && isset($blog_details[$field_name])) {
                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }
                $delete = $HolidayDestinationModel->remove_holiday_destination($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday destination successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday destination not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function destination_status_change()
    {
        if (permission_access_error("Holiday", "holiday_destination_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDestinationModel = new HolidayDestinationModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayDestinationModel->holiday_destination_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday destination status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday destination status changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }




    public function inclusion_list()
    {
        if (permission_access_error("Holiday", "holiday_inclusions_list")) {
            $HolidayInclusionModel = new HolidayInclusionModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayInclusionModel->search_data($this->request->getGet());
            } else {
                $lists = $HolidayInclusionModel->holiday_inclusion_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\holiday-inclusion-list",
                'pager' => $HolidayInclusionModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_inclusion_view()
    {
        if (permission_access("Holiday", "holiday_inclusions_add")) {
            $add_slider_view = view('Modules\Holiday\Views\add-holiday-inclusion');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_inclusion()
    {
        if (permission_access_error("Holiday", "holiday_inclusions_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_inclusion_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayInclusionModel = new HolidayInclusionModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $HolidayInclusionModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday inclusion successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday inclusion not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_inclusion_view()
    {
        if (permission_access("Holiday", "holiday_inclusions_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayInclusionModel = new HolidayInclusionModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $HolidayInclusionModel->holiday_inclusion_details($id, $this->web_partner_id),
            ];
            $details = view('Modules\Holiday\Views\edit-holiday-inclusion', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_inclusion()
    {
        if (permission_access_error("Holiday", "holiday_inclusions_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_inclusion_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayInclusionModel = new HolidayInclusionModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $added_data = $HolidayInclusionModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday inclusion successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday inclusion not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_inclusion()
    {
        if (permission_access_error("Holiday", "holiday_inclusions_delete")) {
            $HolidayInclusionModel = new HolidayInclusionModel();
            $ids = $this->request->getPost('checklist');
            $delete = $HolidayInclusionModel->remove_holiday_inclusion($ids, $this->web_partner_id);
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday inclusion successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday inclusion not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function inclusion_status_change()
    {
        if (permission_access_error("Holiday", "holiday_inclusions_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayInclusionModel = new HolidayInclusionModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayInclusionModel->holiday_inclusion_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday inclusion status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday inclusion status changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function exclusion_list()
    {
        if (permission_access_error("Holiday", "holiday_exclusion_list")) {
            $HolidayExclusionModel = new HolidayExclusionModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayExclusionModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayExclusionModel->holiday_exclusion_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\holiday-exclusion-list",
                'pager' => $HolidayExclusionModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_exclusion_view()
    {
        if (permission_access("Holiday", "holiday_exclusion_add")) {
            $add_slider_view = view('Modules\Holiday\Views\add-holiday-exclusion');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_exclusion()
    {
        if (permission_access_error("Holiday", "holiday_exclusion_add")) {
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_exclusion_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayExclusionModel = new HolidayExclusionModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $HolidayExclusionModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday exclusion successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday exclusion not  added", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_exclusion_view()
    {
        if (permission_access("Holiday", "holiday_exclusion_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $HolidayExclusionModel = new HolidayExclusionModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $HolidayExclusionModel->holiday_exclusion_details($id, $this->web_partner_id),
            ];
            $details = view('Modules\Holiday\Views\edit-holiday-exclusion', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_exclusion()
    {
        if (permission_access_error("Holiday", "holiday_exclusion_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->holiday_exclusion_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayExclusionModel = new HolidayExclusionModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $added_data = $HolidayExclusionModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday exclusion successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday exclusion not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_exclusion()
    {
        if (permission_access_error("Holiday", "holiday_exclusion_delete")) {
            $HolidayExclusionModel = new HolidayExclusionModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HolidayExclusionModel->remove_holiday_exclusion($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday exclusion successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday exclusion not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function exclusion_status_change()
    {
        if (permission_access_error("Holiday", "holiday_exclusion_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayExclusionModel = new HolidayExclusionModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayExclusionModel->holiday_exclusion_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday exclusion status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday exclusion status changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function get_cities()
    {
        $terms = $this->request->getGet('term');
        if (!$terms) {
            $terms = 'delhi';
        }
        $result_array = Holiday::get_google_placeall($terms);

        if ($result_array['status'] == "OK") {
            foreach ($result_array['results'] as $result_place) {
                $cities_list['results'][] = ['text' => $result_place['name'], 'id' => $result_place['name'], 'label' => $result_place['name'], 'value' => $result_place['name']];
            }
        } else {
            $cities_list['results'][] = [];
        }
        echo json_encode($cities_list);
    }

    public function add_itinerary_flight_template()
    {
        $data['data_counter'] = $this->request->getPost('data_counter');
        //$data['itinery_counter'] = $this->request->getPost('holday_counter');
        $data['itinery_counter'] = rand(100, 100000);
        $data_table = view('Modules\Holiday\Views\add-itinerary-flight', $data);

        $message = array("StatusCode" => 7, "Html_data" => $data_table, "Reload" => "false");
        return $this->response->setJSON($message);
    }

    public function add_itinerary_hotel_template()
    {
        $data['data_counter'] = $this->request->getPost('data_counter');
        //$data['itinery_counter'] = $this->request->getPost('holday_counter');
        $data['itinery_counter'] = rand(100, 100000);
        $data_table = view('Modules\Holiday\Views\add-itinerary-hotel', $data);

        $message = array("StatusCode" => 7, "Html_data" => $data_table, "Reload" => "false");
        return $this->response->setJSON($message);
    }

    public function add_itinerary_transfer_template()
    {
        $data['data_counter'] = $this->request->getPost('data_counter');
        //$data['itinery_counter'] = $this->request->getPost('holday_counter');
        $data['itinery_counter'] = rand(100, 100000);
        $data_table = view('Modules\Holiday\Views\add-itinerary-transfer', $data);

        $message = array("StatusCode" => 7, "Html_data" => $data_table, "Reload" => "false");
        return $this->response->setJSON($message);
    }

    public function holiday_itinerary_list()
    {
        if (permission_access_error("Holiday", "holiday_itinerary_list")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $HolidayItineraryModel = new HolidayItineraryModel();
            $itinerary = $HolidayItineraryModel->holiday_itinerary($holiday_list_id, $this->web_partner_id);
            $data = [
                'title' => $this->title,
                'itinerary' => $itinerary,
                'view' => "Holiday\Views\add-holiday-itinerary",
                'holiday_list_id' => $holiday_list_id,
            ];
            return view('template/sidebar-layout', $data);
        }
    }



    public function add_holiday_itinerary()
    {
        if (permission_access_error("Holiday", "holiday_itinerary_add")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));

            $data = $this->request->getPost();
            $validate = new Validation();
            $validationConfigArray = $validate->holiday_itinerary_validation($data);
            $this->validation->setRules($validationConfigArray);
            $rules = $this->validation->run($data);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayItineraryModel = new HolidayItineraryModel();
                if (isset($data['itinerary'])) {
                    $holiday_itinerary = $data['itinerary'];
                    $location_data = [];
                    foreach ($data['itinerary'] as $key => $item) {
                        $city = $holiday_itinerary[$key]['city'];
                        $day_title = $holiday_itinerary[$key]['day_title'];
                        $meal_tpe = $holiday_itinerary[$key]['meal_type'];
                        $description = $holiday_itinerary[$key]['description'];

                        if (isset($data['flight'][$key])) {
                            $flight_data = json_encode(array_values($data['flight'][$key]));
                        } else {
                            $flight_data = null;
                        }

                        if (isset($data['transfer'][$key])) {
                            $transfer = json_encode(array_values($data['transfer'][$key]));
                        } else {
                            $transfer = null;
                        }

                        if (isset($data['hotel'][$key])) {
                            $hotel = json_encode(array_values($data['hotel'][$key]));
                        } else {
                            $hotel = null;
                        }

                        $pan_card_file = $this->request->getFile("itinerary.$key.image");
                        $resizeDim = array('width' => 600, 'height' => 600);
                        $itinerary_image = image_upload($pan_card_file, "itinerary.$key.image", $this->folder_name, $resizeDim);
                        $latocation = Holiday::get_google_place_id($city);

                        $latocation = explode(',', $latocation);
                        $location_data[] = [
                            $city,
                            $latocation[0],
                            $latocation[1],
                            $key
                        ];

                        $holiday_list_update['location_data'] = json_encode($location_data);
                        $store_data = [
                            'holiday_list_id' => $holiday_list_id,
                            'day' => $key + 1,
                            'itinerary_title' => $day_title,
                            'itinerary_description' => $description,
                            'city' => $city,
                            'day_meal_plan' => $meal_tpe,
                            'created' => create_date(),
                            'flight_data' => $flight_data,
                            'hotel_data' => $hotel,
                            'web_partner_id' => $this->web_partner_id,
                            'transfer_data' => $transfer,
                            'itinerary_image' => $itinerary_image['file_name']
                        ];

                        $added_data = $HolidayItineraryModel->insert($store_data);
                    }
                }

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday itinerary successfully updated", "Class" => "success_popup", "Reload" => "true");
                    $HolidayListModel = new HolidayListModel();

                    $added_data = $HolidayListModel->where("id", $holiday_list_id)->set($holiday_list_update)->update();
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday itinerary not uploaded", "Class" => "error_popup", "Reload" => "false");
                }

                return $this->response->setJSON($message);
            }
        }
    }

    public function update_holiday_itinerary()
    {
        if (permission_access_error("Holiday", "holiday_itinerary_edit")) {
            $holiday_list_id = dev_decode($this->request->uri->getSegment(3));
            $data = $this->request->getPost();
            $validate = new Validation();
            $validationConfigArray = $validate->holiday_itinerary_validation($data);
            $this->validation->setRules($validationConfigArray);
            $rules = $this->validation->run($data);
            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayItineraryModel = new HolidayItineraryModel();
                $field_name = 'itinerary_image';

                if (isset($data['itinerary'])) {
                    $holiday_itinerary = $data['itinerary'];
                    $location_data = [];
                    foreach ($data['itinerary'] as $key => $item) {
                        $city = $holiday_itinerary[$key]['city'];
                        $day_title = $holiday_itinerary[$key]['day_title'];
                        $meal_tpe = $holiday_itinerary[$key]['meal_type'];
                        $description = $holiday_itinerary[$key]['description'];
                        $primary_id = $holiday_itinerary[$key]['primary_id'];
                        if (isset($data['flight'][$key])) {
                            $flight_data = json_encode(array_values($data['flight'][$key]));
                        } else {
                            $flight_data = null;
                        }

                        if (isset($data['transfer'][$key])) {
                            $transfer = json_encode(array_values($data['transfer'][$key]));
                        } else {
                            $transfer = null;
                        }

                        if (isset($data['hotel'][$key])) {
                            $hotel = json_encode(array_values($data['hotel'][$key]));
                        } else {
                            $hotel = null;
                        }

                        $file = $this->request->getFile("itinerary.$key.image");
                        $resizeDim = array('width' => 600, 'height' => 600);
                        if ($file->getName() != '') {
                            $itinerary_image_upload = image_upload($file, "itinerary.$key.image", $this->folder_name, $resizeDim);
                            $itinerary_image = $itinerary_image_upload['file_name'];

                            if ($itinerary_image_upload['status_code'] == 0 && $primary_id) {

                                $previous_data = $HolidayItineraryModel->delete_image($primary_id);
                                if ($previous_data[$field_name]) {
                                    if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                        unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                                    }
                                }
                            }
                        } else if ($primary_id) {
                            $previous_data = $HolidayItineraryModel->delete_image($primary_id);
                            $itinerary_image = $previous_data[$field_name];
                        }
                        $latocation = Holiday::get_google_place_id($city);
                        $latocation = explode(',', $latocation);
                        $location_data[] = [
                            $city,
                            $latocation[0],
                            $latocation[1],
                            $key
                        ];

                        $holiday_list_update['location_data'] = json_encode($location_data);


                        $store_data = [
                            'holiday_list_id' => $holiday_list_id,
                            'day' => $key + 1,
                            'itinerary_title' => $day_title,
                            'itinerary_description' => $description,
                            'city' => $city,
                            'day_meal_plan' => $meal_tpe,
                            'created' => create_date(),
                            'flight_data' => $flight_data,
                            'hotel_data' => $hotel,
                            'transfer_data' => $transfer,
                            'itinerary_image' => $itinerary_image
                        ];
                        if ($primary_id) {
                            $added_data = $HolidayItineraryModel->where("id", $primary_id)->where('web_partner_id', $this->web_partner_id)->set($store_data)->update();
                            $HolidayListModel = new HolidayListModel();

                            $added_data = $HolidayListModel->where("id", $holiday_list_id)->where('web_partner_id', $this->web_partner_id)->set($holiday_list_update)->update();
                        } else {
                            $store_data['web_partner_id'] = $this->web_partner_id;
                            $added_data = $HolidayItineraryModel->insert($store_data);
                        }
                    }
                }

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday itinerary successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday itinerary not uploaded", "Class" => "error_popup", "Reload" => "false");
                }

                return $this->response->setJSON($message);
            }
        }
    }

    public function booking_list()
    {
        if (permission_access_error("Holiday", "holiday_booking_list")) {
            $HolidayModel = new HolidayModel();
            $bookingType = 'all';
            $source = '';
            $getData = $this->request->getGET();

            if (isset($getData['key'])) {
                $lists = $HolidayModel->search_data($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
            } else {
                if (isset($getData['bookingtype'])) {
                    $bookingType = $getData['bookingtype'];
                }
                $lists = $HolidayModel->holiday_booking_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\booking\holiday-booking-list",
                'pager' => $HolidayModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    function confirmation()
    {
        if (permission_access_error("Holiday", "holiday_confirmation")) {
            $uri = service('uri');
            $ticketData = $uri->getSegment(3);
            $bookingId = json_decode(dev_decode($ticketData), true);
            if ($bookingId) {
                $HolidayModel = new HolidayModel();
                $bookingInfo = $HolidayModel->getBookingConfirmationData($bookingId['BookingId']);

                $bookingInfo['package_details'] = json_decode($bookingInfo['package_details'], true);
                $BookingRefNumber = $bookingInfo['booking_ref_number'];
                $BookingStatus = $bookingInfo['booking_status'];
                $PaymentStatus = $bookingInfo['payment_status'];
                $markup = 0;
                $discount = 0;
                if ($bookingInfo['booking_source'] == "Wl_b2b") {
                    $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
                    $markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                    $discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                } else {
                    $fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
                }
                $couponAmount = 0;
                $conveniencefee = isset($bookingInfo['conveniencefee']) ? $bookingInfo['conveniencefee'] : 0;
                $couponInfo = json_decode($bookingInfo['coupon_info'], true);
                if (!empty($couponInfo)) {
                    $couponAmount = $couponInfo['couponAmount'];
                }
                $gst = number_format($fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'], 2);

                $TotalAmount = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] - $couponAmount + $conveniencefee;
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                        "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                        "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                        "PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),
                        "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),
                    ),
                    "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                    "BookingId" => $bookingInfo['id'],
                    "WebPMarkUp" => $markup,
                    "WebPDiscount" => $discount,
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
                return view('template/sidebar-layout', $data);
            } else {
                return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
            }
        }
    }


    public function get_invoice_ticket()
    {
        if (permission_access_error("Holiday", "holiday_invoice_ticket")) {
            $HolidayModel = new HolidayModel();
            if (!$this->request->isAJAX()) {
                $getData = $this->request->getGet();
                $bookingRefNumbers = $getData['booking_ref_number'];
                $getTicketInvioceType = array("PrintTicket" => "Ticket", "AgencyInvoice" => "AgencyInvoice", "CustomerInvoice" => "CustomerInvoice");
                $getUserType = array("PrintTicket" => "wl-agent", "AgencyInvoice" => "webpartner", "CustomerInvoice" => "wl-agent");
                if ($bookingRefNumbers) {

                    $bookingInfoData = $HolidayModel->getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumbers, "id,tts_search_token,booking_source");
                    $bookingInfo = $bookingInfoData;
                    $bookingInfoId = $bookingInfoData['id'];
                    $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                    $booking_source = isset($bookingInfoData['booking_source']) ? $bookingInfoData['booking_source'] : "";

                    if ($bookingRefNumbers && $bookingInfo) {

                        if ($booking_source == "Wl_b2b") {
                            $getUserType = array("PrintTicket" => "wl-agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-agent");
                        } else {
                            $getUserType = array("PrintTicket" => "wl-customer", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-customer");
                        }

                        if ($getData['type'] == "PrintTicket") {
                            $TicketViewRequest = array(
                                "BookingId" => $bookingInfoId,
                                "SearchTokenId" => $tts_search_token,
                                "HtmlType" => $getTicketInvioceType[$getData['type']],
                                "UserType" => $getUserType[$getData['type']],
                                "ViewService" => "View",
                                "WithPrice" => isset($getData['price']) ? 1 : 0,
                                "WithAgencyDetail" => isset($getData['agency_detail']) ? 1 : 0,
                                "ViewSize" => "",
                                "RequestBy" => "WebPartner"
                            );
                        } else {
                            $TicketViewRequest = array(
                                "BookingId" => $bookingInfoId,
                                "SearchTokenId" => $tts_search_token,
                                "HtmlType" => $getTicketInvioceType[$getData['type']],
                                "UserType" => $getUserType[$getData['type']],
                                "ViewService" => "View",
                                "WithPrice" => 1,
                                "WithAgencyDetail" => 1,
                                "ViewSize" => "",
                                "RequestBy" => "WebPartner"
                            );
                        }
                        $url = $this->Services . 'generate-wl-voucher-invoice';
                        $response = RequestWithoutAuth($TicketViewRequest, $url);
                        $data = [
                            'title' => $this->title,
                            'view' => "Holiday\Views\booking\print_ticket",
                            'data' => $response['Result']['Html'],
                        ];
                        return view('template/sidebar-layout', $data);
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

                        $bookingInfoData = $HolidayModel->getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumbers, "id,tts_search_token,booking_source");
                        $bookingInfo = $bookingInfoData;
                        $bookingInfoId = $bookingInfoData['id'];
                        $tts_search_token = isset($bookingInfoData['tts_search_token']) ? $bookingInfoData['tts_search_token'] : "";
                        $booking_source = isset($bookingInfoData['booking_source']) ? $bookingInfoData['booking_source'] : "";
                        if ($bookingRefNumbers && $bookingInfo) {

                            if ($booking_source == "Wl_b2b") {
                                $getUserType = array("EmailTicket" => "wl-agent", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-agent");
                            } else {
                                $getUserType = array("EmailTicket" => "wl-customer", "AgencyInvoice" => "WebPartner", "CustomerInvoice" => "wl-customer");
                            }
                            $TicketViewRequest = array(
                                "BookingId" => $bookingInfoId,
                                "SearchTokenId" => $tts_search_token,
                                "HtmlType" => "Ticket",
                                "UserType" => $getUserType[$getData['type']],
                                "ViewService" => "Email",
                                "WithPrice" => "1",
                                "WithAgencyDetail" => "0",
                                "TicketInvoiceJourney" => "Both",
                                "ViewSize" => "",
                                "RequestBy" => "WebPartner"
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

    public function query_list()
    {
        if (permission_access_error("Holiday", "holiday_query_list")) {
            $HolidayQueryModel = new HolidayQueryModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayQueryModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayQueryModel->query_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Holiday\Views\booking\holiday-query-list",
                'pager' => $HolidayQueryModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }



    public function export_query()
    {
        /*  if (permission_access("Agent", "agent_export")) { */
        $rules = $this->validate([
            'from_date' => [
                'label' => 'From Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select from date.'
                ]
            ],
            'to_date' => [
                'label' => 'To Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select to date.'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }

        $fileName = 'Holiday-Query.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:J1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:J")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->setCellValue('A1', 'Sr. No.');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Mobile Number');
        $sheet->setCellValue('E1', 'Package Name');
        $sheet->setCellValue('F1', 'Travel Date');
        $sheet->setCellValue('G1', 'No Of Person');
        $sheet->setCellValue('H1', 'No Of Night');
        $sheet->setCellValue('I1', 'Messages');
        $sheet->setCellValue('J1', 'Query Date');
        $rows = 2;
        $HolidayQueryModel = new HolidayQueryModel();
        $Query_excel = $HolidayQueryModel->query_list_export_query($this->web_partner_id);
        foreach ($Query_excel as $key => $val) {

            $sheet->setCellValue('A' . $rows, $key + 1);
            $sheet->setCellValue('B' . $rows, ucwords($val['name']));
            $sheet->setCellValue('C' . $rows, $val['email']);
            $sheet->setCellValue('D' . $rows, $val['mobile']);
            $sheet->setCellValue('E' . $rows, $val['holiday_name']);
            $sheet->setCellValue('F' . $rows, $val['travel_date']);
            $sheet->setCellValue('G' . $rows, $val['no_of_person']);
            $sheet->setCellValue('H' . $rows, $val['no_of_nights']);
            $sheet->setCellValue('I' . $rows, $val['comment']);
            $sheet->setCellValue('J' . $rows, date_created_format(intval($val['created'])));
            $rows++;
        }
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $this->response->setJSON($data_validation);
        /*  } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        } */
    }

    public function holiday_booking_detail()
    {
        if (permission_access_error("Holiday", "holiday_booking_detail")) {
            $uri = service('uri');
            $ref_id = $uri->getSegment(3);
            $HolidayModel = new HolidayModel();
            $bookingInfo = $HolidayModel->getBookingDetailData($this->web_partner_id, $ref_id);
            $amendment_list = $HolidayModel->amendment_list($this->web_partner_id, $bookingInfo['id'], '');
            $web_partner_fare_break_up = json_decode($bookingInfo['web_partner_fare_break_up'], true);
            $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
            $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
            if ($bookingInfo['booking_source'] == "Wl_b2b") {
                $fareBreakupArray = json_decode($bookingInfo['agent_fare_break_up'], true);
            } else {
                $fareBreakupArray = json_decode($bookingInfo['customer_fare_break_up'], true);
            }
            $couponAmount = 0;
            $conveniencefee = isset($bookingInfo['conveniencefee']) ? $bookingInfo['conveniencefee'] : 0;
            $couponInfo = json_decode($bookingInfo['coupon_info'], true);
            if (!empty($couponInfo)) {
                $couponAmount = $couponInfo['couponAmount'];
            }
            $gst = number_format($fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'], 2);
            $TotalAmount = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] - $couponAmount + $conveniencefee;
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                    "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                    "PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),
                    "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),
                    "Promocode" => array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)"),
                    "conveniencefee" => array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)"),
                ),
                "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                "BookingId" => $bookingInfo['id'],
                "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
            );

            if ($fareBreakupArray['TDS']) {
                $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)");
            }

            $bookingInfo['FareBreakUp'] = $FareBreakUp;
            $Booking_id = $bookingInfo['id'];
            $bookingInfo['Booking_id'] = $Booking_id;
            unset($bookingInfo['id']);

            $data = [
                'title' => $this->title,
                'amendment_list' => $amendment_list,
                'bookingDetail' => $bookingInfo,
                'view' => "Holiday\Views\booking\holiday-booking-details",
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    function getUpdateholidayVoucherInfo()
    {
        if (permission_access_error("Holiday", "holiday_update_voucher_info")) {
            $uri = service('uri');
            $bookingReferenceNumber = $uri->getSegment(3);
            $HolidayModel = new HolidayModel();
            $BookingDetail = $HolidayModel->getBookingDetailData($this->web_partner_id, $bookingReferenceNumber);
            $amendment_list = $HolidayModel->amendment_list($this->web_partner_id, $BookingDetail['id']);
            $web_partner_fare_break_up = json_decode($BookingDetail['web_partner_fare_break_up'], true);
            $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
            $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
            if ($BookingDetail['booking_source'] == "Wl_b2b") {
                $fareBreakupArray = json_decode($BookingDetail['agent_fare_break_up'], true);
            } else {
                $fareBreakupArray = json_decode($BookingDetail['customer_fare_break_up'], true);
            }
            $couponAmount = 0;
            $conveniencefee = isset($BookingDetail['conveniencefee']) ? $BookingDetail['conveniencefee'] : 0;
            $couponInfo = json_decode($BookingDetail['coupon_info'], true);
            if (!empty($couponInfo)) {
                $couponAmount = $couponInfo['couponAmount'];
            }
            $gst = $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'];
            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                    "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                    "PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),
                    "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),
                    "Promocode" => array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)"),
                    "conveniencefee" => array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)"),
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "BookingId" => $BookingDetail['id'],
                "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
            );

            if ($fareBreakupArray['TDS']) {
                $FareBreakUp['FareBreakup']['TDS'] = array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)");
            }

            $hotelSupplier = $HolidayModel->getDataArray("offline_provider", array('web_partner_id' => $this->web_partner_id, 'holiday_service' => 'active'), $singalRecord = 0, $whereApply = 1, 'id,supplier_name');

            $BookingDetail['FareBreakUp'] = $FareBreakUp;

            $Booking_id = $BookingDetail['id'];
            $BookingDetail['Booking_id'] = $Booking_id;
            unset($BookingDetail['id']);
            $data = [
                'title' => $this->title,
                'view' => "Holiday\Views\booking\holiday-booking-details",
                "bookingDetail" => $BookingDetail,
                "amendment_list" => $amendment_list,
                "hotelSupplier" => $hotelSupplier,

                "UpdateVoucher" => 1,
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function UpdateHolidayVoucherInfo()
    {
        if (permission_access_error("Holiday", "holiday_update_voucher_info")) {
            $input = $this->request->getPost();
            $validate = new Validation();
            $validationConfigArray = $validate->voucher_update_validation($input);
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
                $HolidayModel = new HolidayModel();
                $booking_refrence_number = dev_decode($input['booking_ref_number']);
                $hotel_booking_id = dev_decode($input['hotel_booking_id']);
                $bookingInfo = $HolidayModel->getBookingDetailData($this->web_partner_id, $booking_refrence_number);
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
                if ($bookingInfo && (isset($bookingInfo['id']) && ($bookingInfo['id'] == $hotel_booking_id))) {

                    $checkbookingflighttime = checkbookingflighttime($bookingInfo['created'], "Holiday");
                    if (isset($checkbookingflighttime['WaitingTime']) && $checkbookingflighttime['WaitingTime']) {
                        $message = array("StatusCode" => 2, "Message" => $checkbookingflighttime['WaitingMessage'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        $RedirectUrl = site_url('holiday/booking-list');
                        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                        return $this->response->setJSON($data_validation);
                    }
                    if ($bookingInfo['webpartner_assign_user'] == $this->user_id || admin_cookie_data()['admin_user_details']['primary_user'] == 1) {
                        if (isset($input['refundbookingamount']) && $input['refundbookingamount'] == "yes") {
                            if (((isset($bookingInfo['booking_status']) && $bookingInfo['booking_status'] == 'Failed') || $input['booking_status'] == "Failed") && (isset($bookingInfo['payment_status']) && $bookingInfo['payment_status'] == 'Successful')) {
                                $bookingWhere = array("booking_ref_no" => $bookingInfo['id'], 'service' => "holiday", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']);
                                $bookingRefundWhere = array("booking_ref_no" => $bookingInfo['id'], 'service' => "holiday", "action_type" => "bookingrefund", 'transaction_type' => "credit", 'web_partner_id' => $bookingInfo['web_partner_id']);
                                $bookingWhere[$key] = $account_log_id;
                                $bookingRefundWhere[$key] = $account_log_id;
                                $hotelBookingAccountinfo = $HolidayModel->getDataArray($AccountTableName, $bookingWhere, $singalRecord = 1, $whereApply = 1);
                                $checkflighBookingRefund = $HolidayModel->getDataArray($AccountTableName, $bookingRefundWhere, $singalRecord = 1, $whereApply = 1);
                                if (empty($checkflighBookingRefund)) {
                                    if (!empty($hotelBookingAccountinfo) && $hotelBookingAccountinfo) {
                                        $serviceLog = json_decode($hotelBookingAccountinfo['service_log'], true);

                                        $extra_param = json_decode($hotelBookingAccountinfo['extra_param'], true);

                                        if (empty($serviceLog)) {

                                            $serviceLog = array();
                                        }

                                        if (empty($extra_param)) {

                                            $extra_param = array();
                                        }

                                        $serviceLog['BookingRefrenceNumber'] = $booking_refrence_number;
                                        $web_partner_id = $hotelBookingAccountinfo['web_partner_id'];
                                        $topupAmount = round_value(($hotelBookingAccountinfo['debit']));
                                        $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                                        $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                        $WebPartnerAccountLogData[$key] = $account_log_id;
                                        $WebPartnerAccountLogData['created'] = create_date();
                                        $WebPartnerAccountLogData['transaction_type'] = "credit";
                                        $WebPartnerAccountLogData['action_type'] = "bookingrefund";
                                        $WebPartnerAccountLogData['role'] = 'web_partner';
                                        $WebPartnerAccountLogData['remark'] = $input['remark'];
                                        $WebPartnerAccountLogData['service_log'] = json_encode($serviceLog);
                                        $WebPartnerAccountLogData['extra_param'] = json_encode($extra_param);
                                        $WebPartnerAccountLogData['service'] = "holiday";
                                        $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                        $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                                        $available_balance = $HolidayModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                        if (!$available_balance) {
                                            $available_balance['balance'] = 0;
                                        }
                                        $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));
                                        $WebPartnerAccountLogData['credit'] = $topupAmount;
                                        $added_data_id = $HolidayModel->InsertData($AccountTableName, $WebPartnerAccountLogData);
                                        $updateData['acc_ref_number'] = reference_number($added_data_id);
                                        $HolidayModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);
                                    } else {
                                        $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                                        $this->session->setFlashdata('Message', $message);
                                        ;
                                        $RedirectUrl = site_url('holiday/booking-list');
                                        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                        return $this->response->setJSON($data_validation);
                                    }
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "Refund for this booking has been done already", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);
                                    ;
                                    $RedirectUrl = site_url('holiday/booking-list');
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            } else {
                                $message = array("StatusCode" => 2, "Message" => "You are not eligible update voucher", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);
                                ;
                                $RedirectUrl = site_url('holiday/booking-list');
                                $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                return $this->response->setJSON($data_validation);
                            }
                        }
                        if ($input['booking_status'] == "Confirmed") {
                            $bookingWhereArray = array("booking_ref_no" => $bookingInfo['id'], 'service' => "holiday", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $bookingInfo['web_partner_id']);
                            $bookingWhereArray[$key] = $account_log_id;
                            $hotelBookingAccountinfo = $HolidayModel->getDataArray($AccountTableName, $bookingWhereArray, $singalRecord = 1, $whereApply = 1);
                            if (empty($hotelBookingAccountinfo)) {
                                if (isset($input['deductbookingamount']) && $input['deductbookingamount'] == "yes") {
                                    $web_partner_id = $bookingInfo['web_partner_id'];
                                    $available_balance = $HolidayModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                    $travellerInfo = json_decode($bookingInfo['travellers'], true);
                                    foreach ($travellerInfo as $tkey => $traveller) {
                                        if ($traveller['lead_pax'] == 1) {
                                            $first_name = $traveller['first_name'];
                                            $last_name = $traveller['last_name'];
                                            break;
                                        }
                                    }
                                    $PaxName = $first_name . ' ' . $last_name;
                                    $serviceLog = json_encode(array('PaxName' => $PaxName, 'Sector' => $bookingInfo['package_name'] . '-' . $bookingInfo['package_category'], 'ConfirmationNo' => $input['confirmation_number']));
                                    $extra_parm['booking_ref_number'] = $bookingInfo['booking_ref_number'];
                                    $extra_parm['webPartnerBreakUpInfo'] = json_decode($bookingInfo['web_partner_fare_break_up'], true);
                                    if ($bookingInfo['booking_source'] == "Wl_b2b") {
                                        $extra_parm['agentBreakUpInfo'] = json_decode($bookingInfo['agent_fare_break_up'], true);
                                        $extra_param['convenienceFee'] = (isset($extra_param['agentBreakUpInfo']['convenienceFee'])) ? $extra_param['agentBreakUpInfo']['convenienceFee'] : 0;
                                    } else {
                                        $extra_parm['customerBreakUpInfo'] = json_decode($bookingInfo['customer_fare_break_up'], true);
                                        $extra_param['convenienceFee'] = (isset($extra_param['customerBreakUpInfo']['convenienceFee'])) ? $extra_param['customerBreakUpInfo']['convenienceFee'] : 0;
                                    }
                                    if (isset($available_balance['balance']) && $available_balance['balance'] > $bookingInfo['total_price']) {
                                        $debitAmount = round_value(($bookingInfo['total_price']));
                                        $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                                        $WebPartnerAccountLogData['user_id'] = $this->user_id;
                                        $WebPartnerAccountLogData[$key] = $account_log_id;
                                        $WebPartnerAccountLogData['created'] = create_date();
                                        $WebPartnerAccountLogData['transaction_type'] = "debit";
                                        $WebPartnerAccountLogData['action_type'] = "booking";
                                        $WebPartnerAccountLogData['role'] = 'web_partner';
                                        $WebPartnerAccountLogData['remark'] = $input['remark'];
                                        $WebPartnerAccountLogData['service_log'] = $serviceLog;
                                        $WebPartnerAccountLogData['extra_param'] = json_encode($extra_parm);
                                        $WebPartnerAccountLogData['service'] = "holiday";
                                        $WebPartnerAccountLogData['booking_ref_no'] = $bookingInfo['id'];
                                        $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                                        $available_balance = $HolidayModel->agent_user_available_balance($AccountTableName, $key, $account_log_id, $web_partner_id);
                                        if (!$available_balance) {
                                            $available_balance['balance'] = 0;
                                        }
                                        $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] - $debitAmount));
                                        $WebPartnerAccountLogData['debit'] = $debitAmount;
                                        $added_data_id = $HolidayModel->InsertData($AccountTableName, $WebPartnerAccountLogData);
                                        $updateData['acc_ref_number'] = reference_number($added_data_id, "holiday", $isDomestic = null);
                                        $HolidayModel->updateData($AccountTableName, array("id" => $added_data_id), $updateData);

                                        $input['payment_status'] = "Successful";
                                    } else {
                                        $message = array("StatusCode" => 2, "Message" => "$user have not enough balance", "Class" => "error_popup");
                                        $this->session->setFlashdata('Message', $message);
                                        ;
                                        $RedirectUrl = site_url('/holiday/get-update-holiday-voucher-info/' . $booking_refrence_number);
                                        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                        return $this->response->setJSON($data_validation);
                                    }
                                } else {
                                    $message = array("StatusCode" => 2, "Message" => "Payment  have not done for this booking", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);
                                    ;
                                    $RedirectUrl = site_url('/holiday/get-update-holiday-voucher-info/' . $booking_refrence_number);
                                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                                    return $this->response->setJSON($data_validation);
                                }
                            }
                        }
                        $saveNoteData = array(
                            "booking_ref_no" => $hotel_booking_id,
                            'agent_staff_id' => $this->user_id,
                            'web_partner_id' => $this->web_partner_id,
                            'wl_agent_staff_id' => $bookingInfo['wl_agent_user_id'],
                            'service_type' => "holiday",
                            'add_by' => "weppartner",
                            'comment' => $input['remark'],
                            'created' => create_date()
                        );
                        $saveNoteDataId = $HolidayModel->insertData('web_partner_booking_notes', $saveNoteData);
                        if ($input['booking_status'] == 'Confirmed' && $bookingInfo['booking_status'] != 'Confirmed') {
                            $agent_fare_break_up = $fareBreakupArray;
                            $bookingWhere = ['booking_ref_no' => $bookingInfo['id'], "action_type" => "booking", "transaction_type" => "debit", "web_partner_id" => $this->web_partner_id];
                            $bookingWhere[$key] = $account_log_id;
                            $checkTaxableInvoce = checkTaxableNonTaxableINV($agent_fare_break_up, "", 'holiday', 'INV');
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
                            $agent_account_log = $HolidayModel->getData($AccountTableName, $bookingWhere, "service_log");
                            $service_log = json_decode($agent_account_log['service_log'], true);
                            $HolidayModel->updateUserData($AccountTableName, $bookingWhere, ["invoice_number" => $InvoiceNumber, "service_log" => json_encode($service_log)]);
                            $HolidayModel->updateUserData('invoice_suffix_list', $whereCondition, $InvoiceupdateData);
                        }
                        $superAdminStaffDetail = admin_cookie_data()['admin_user_details'];
                        $updateFlightBookingData = array(
                            "booking_status" => $input['booking_status'],
                            "payment_status" => $input['payment_status'],
                            "issue_supplier" => $input['supplier'],
                            "confirmation_no" => $input['confirmation_number'],
                            "webpartner_assign_user" => null,
                            'is_manual' => 1,
                            "update_ticket_by" => json_encode(array("first_name" => $superAdminStaffDetail['first_name'], "last_name" => $superAdminStaffDetail['last_name'], "StaffId" => $superAdminStaffDetail['id'])),

                        );
                        $HolidayModel->updateData("holiday_booking_list", array("id" => $hotel_booking_id), $updateFlightBookingData);
                        $message = array("StatusCode" => 1, "Message" => "Voucher Update successfully", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        ;
                        $RedirectUrl = site_url('holiday/booking-list');
                        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                        return $this->response->setJSON($data_validation);
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "You are not eligible update Voucher", "Class" => "error_popup");
                        $this->session->setFlashdata('Message', $message);
                        ;
                        $RedirectUrl = site_url('holiday/booking-list');
                        $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                        return $this->response->setJSON($data_validation);
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Record not found", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    ;
                    $RedirectUrl = site_url('holiday/booking-list');
                    $data_validation = array("StatusCode" => 3, "ErrorMessage" => '', "Redirect_Url" => $RedirectUrl);
                    return $this->response->setJSON($data_validation);
                }
            }
        }
    }

    public function raiseAmendment()
    {
        if (permission_access_error("Holiday", "holiday_raise_mendment")) {
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
                            "WebPartnerId" => $bookingInfo['web_partner_id'],
                            "AmendmentId" => $this->request->getPOST('amendment_id'),
                            "BookingId" => $bookingInfo['id'],
                            "AmendmentStatus" => $this->request->getPOST('amendment_status'),
                            "Type" => $this->request->getPOST('amendment_type'),
                            "Remarks" => $this->request->getPOST('remark'),
                            "RequesterInfo" => array("RequesterId" => $this->user_id, "Requester" => "SuperAdmin"),
                        );

                        //echo json_encode($request);die;

                        $service = "adminsubmitamendment";
                        $url = $this->Services . $service;
                        $response = RequestWithoutAuth($request, $url, $service);
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
    }

    function amendment_lists()
    {
        if (permission_access_error("Holiday", "holiday_amendment_lists")) {
            $AmendmentModel = new AmendmentModel();
            $bookingType = 'all';
            $source = '';
            $getData = $this->request->getGET();

            if (isset($getData['key'])) {
                $list = $AmendmentModel->search_bookings($getData, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
            } else {
                $source = $this->request->getGET('source');
                if ($source == 'dashboard') {
                    $source = 'dashboard';
                }
                $bookingType = 'all';
                if ($this->request->getGET('bookingtype')) {
                    $bookingType = $this->request->getGET('bookingtype');
                }

                $list = $AmendmentModel->amendment_list($this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);
            }


            $data = [
                'title' => $this->title,
                'view' => "Holiday\Views/amendment/amendment-list",
                "list" => $list,
                "search_bar_data" => $getData,
                'pager' => $AmendmentModel->pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    function amendments_details()
    {
        if (permission_access_error("Holiday", "holiday_amendment_details")) {
            $uri = service('uri');
            $amendmentId = $uri->getSegment(3);
            $amendmentId = dev_decode($amendmentId);
            $AmendmentModel = new AmendmentModel();
            $AmendmentDetail = $AmendmentModel->amendment_detail($this->web_partner_id, $amendmentId);
            if ($AmendmentDetail) {

                $FareBreakUp = array();
                $web_partner_fare_break_up = json_decode($AmendmentDetail['web_partner_fare_break_up'], true);
                $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
                $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
                if ($AmendmentDetail['booking_source'] == "Wl_b2b") {
                    $fareBreakupArray = json_decode($AmendmentDetail['agent_fare_break_up'], true);
                } else {
                    $fareBreakupArray = json_decode($AmendmentDetail['customer_fare_break_up'], true);
                }
                $couponAmount = 0;
                $conveniencefee = isset($AmendmentDetail['conveniencefee']) ? $AmendmentDetail['conveniencefee'] : 0;
                $couponInfo = json_decode($AmendmentDetail['coupon_info'], true);
                if (!empty($couponInfo)) {
                    $couponAmount = $couponInfo['couponAmount'];
                }
                $gst = $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'];
                $TotalAmount = $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'] - $couponAmount + $conveniencefee;
                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                        "Discount" => array("Value" => $fareBreakupArray['Discount'] + $fareBreakupArray['AgentCommission'], "LabelText" => "Discount (-)"),
                        "GST" => array("Value" => $gst, "LabelText" => "GST (+)"),
                        "PublishedPrice" => array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"),
                        "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"),
                        "Promocode" => array("Value" => $couponAmount, "LabelText" => "Promocode Discount (-)"),
                        "conveniencefee" => array("Value" => $conveniencefee, "LabelText" => "Convenience Fee (+)"),
                    ),
                    "TotalAmount" => array("Value" => $TotalAmount, "LabelText" => "Total Amount"),
                    "BookingId" => $AmendmentDetail['Booking_id'],
                    "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                    "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
                );
                $AmendmentDetail['FareBreakUp'] = $FareBreakUp;
                $AmendmentDetail['web_partner_fare_break_up'] = $fareBreakupArray;
                $AmendmentDetail['package_details'] = json_decode($AmendmentDetail['package_details'], true);
                $data = [
                    'title' => $this->title,
                    'view' => "Holiday\Views/booking/holiday-booking-details",
                    "bookingDetail" => $AmendmentDetail,
                    "amendments_details" => true
                ];
                return view('template/sidebar-layout', $data);
            } else {
                return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
            }
        }
    }


    function amendment_cancellation_charge()
    {
        if (permission_access_error("Holiday", "holiday_cancellation_charge")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            $rules = $this->validate($validate->amendment_refund_validation);
            if (!$rules) {
                $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            } else {

                $AmendmentModel = new AmendmentModel();
                $amendment_id = dev_decode($data['amendment_id']);
                $AmendmentDetail = $AmendmentModel->amendment_detail_by_id($amendment_id, $this->web_partner_id);

                if ($AmendmentDetail) {
                    $hotel_booking_id = $AmendmentDetail['booking_ref_no'];
                    $whereArray = array('id' => $hotel_booking_id, 'web_partner_id' => $AmendmentDetail['web_partner_id']);
                    $booking_list = $AmendmentModel->get_list_by_table_name('holiday_booking_list', 'id,agent_fare_break_up,customer_fare_break_up', $whereArray);
                    if (isset($AmendmentDetail['wl_agent_id']) && $AmendmentDetail['wl_agent_id'] > 0) {
                        $tableName = "agent";
                        $fareBreakupArray = json_decode($booking_list['agent_fare_break_up'], true);
                        $user_id = $AmendmentDetail['wl_agent_id'];
                        $agentUserGstCode = $AmendmentModel->agent_user_gst_state_code($tableName, $user_id, $this->web_partner_id);
                    } else {
                        $tableName = "customer";
                        $fareBreakupArray = json_decode($booking_list['customer_fare_break_up'], true);
                        $user_id = $AmendmentDetail['wl_customer_id'];
                        $agentUserGstCode = "";
                    }
                    $company_gst_code = admin_cookie_data()['admin_comapny_detail']['gst_state_code'];
                    $offeredfare = $fareBreakupArray['OfferedPrice'];

                    $TDS = 0;

                    $TDSReturnidentifier = "no";

                    if (isset($data['tdsreturn']) && $data['tdsreturn'] == "yes") {
                        $TDSReturnidentifier = "yes";
                        $TDSReturn = $TDS;
                    }
                    $GSTInfo = gst_calculate("Holiday", $agentUserGstCode, $company_gst_code, $data['service_charge']);
                    $refundAmount = $offeredfare - $data['charge'] - $data['service_charge'] - $GSTInfo['TotalGSTAmount'] + $TDS;

                    $RefundChargeDetails = array(
                        "Charge" => $data['charge'],
                        "ServiceCharge" => $data['service_charge'],
                        "Refund" => $refundAmount,
                        "GST" => $GSTInfo,
                        "TDSReturnIdentifier" => $TDSReturnidentifier,
                    );

                    $updateData = array();
                    if ($refundAmount > 0) {
                        $updateData = array(
                            "amendment_charges" => json_encode($RefundChargeDetails),
                            "amendment_type" => $AmendmentDetail['amendment_type'],
                            "amendment_id" => $amendment_id,

                        );
                        $update = $AmendmentModel->updateWithTableData("holiday_booking_list", $updateData, array("id" => $hotel_booking_id));

                        $updateAmendmentData = array();
                        $updateAmendmentData['agent_staff_id'] = $this->user_id;
                        $updateAmendmentData['refund_status'] = "Open";
                        $updateAmendmentData['refund_amount'] = $refundAmount;
                        $updateAmendmentData['modified'] = create_date();
                        $updateAmendmentData['refund_date'] = create_date();
                        $update = $AmendmentModel->updateData($updateAmendmentData, array("id" => $amendment_id));
                        $message = array("StatusCode" => 0, "Message" => "Refund is Opened", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Please check refund amount value is negative", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function amendment_status_change()
    {
        if (permission_access_error("Holiday", "holiday_amendment_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->amendment_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $AmendmentModel = new AmendmentModel();
                $amendment_id = dev_decode($data['amendment_id']);
                if ($data['status'] == "approved") {
                    $AmendmentDetail = $AmendmentModel->amendment_detail($this->web_partner_id, $amendment_id);
                    if ($AmendmentDetail['amendment_type'] == "cancellation") {
                        $booking_id = $AmendmentDetail['booking_ref_no'];
                        if ($AmendmentDetail['amendment_type'] == 'cancellation' && $data['status'] == 'approved') {
                            $AmendmentModel->updateWithTableData("holiday_booking_list", array("booking_status" => 'Cancelled'), array("id" => $booking_id));
                        }
                    }
                }


                $updateData['amendment_status'] = $data['status'];
                $updateData['remark_from_web_partner'] = $data['admin_remark'];
                $updateData['agent_staff_id'] = $this->user_id;


                $update = $AmendmentModel->updateData($updateData, array("id" => $amendment_id));

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Amendment status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Amendment status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    function refund_lists()
    {
        if (permission_access_error("Holiday", "holiday_refund_lists")) {
            $AmendmentModel = new AmendmentModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {
                $list = $AmendmentModel->search_refund_list($getData, $this->web_partner_id);
            } else {
                $source = $this->request->getGET('source');
                if ($source == 'dashboard') {
                    $source = 'dashboard';
                }
                $bookingType = 'all';
                $list = $AmendmentModel->refund_list($this->web_partner_id, $bookingType, $source, );
            }

            if (isset($getData['export_excel']) && $getData['export_excel'] == 1) {
                if (isset($getData['key'])) {
                    $excellist = $AmendmentModel->search_refund_list($getData, $this->web_partner_id);
                } else {
                    $excellist = $AmendmentModel->search_refund_list($this->web_partner_id);
                }
                HotelListings::export_refund_list($excellist);
            }

            $data = [
                'title' => $this->title,
                'view' => "Holiday\Views/refund/refund-list",
                "list" => $list,
                "search_bar_data" => $getData,
                'pager' => $AmendmentModel->pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function refund_close()
    {
        if (permission_access_error("Holiday", "holiday_refund_close")) {
            $validate = new Validation();
            $rules = $this->validate($validate->refund_close_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();

                $HolidayModel = new HolidayModel();
                $CommonModel = new CommonModel();
                $AmendmentModel = new AmendmentModel();
                $amendment_id = dev_decode($data['amendment_id']);
                $AmendmentDetail = $AmendmentModel->amendment_detail_by_id($amendment_id, $this->web_partner_id);

                if ($AmendmentDetail) {
                    $holiday_booking_id = $AmendmentDetail['booking_ref_no'];
                    $whereArray = array('id' => $holiday_booking_id, 'web_partner_id' => $AmendmentDetail['web_partner_id']);
                    $booking_list = $AmendmentModel->get_list_by_table_name('holiday_booking_list', 'id,package_name,package_category,booking_ref_number,agent_fare_break_up,customer_fare_break_up,amendment_charges', $whereArray);
                    $web_partner_id = $AmendmentDetail['web_partner_id'];
                    if (isset($AmendmentDetail['wl_agent_id']) && $AmendmentDetail['wl_agent_id'] > 0) {
                        $AccounttableName = "agent_account_log";
                        $fareBreakupArray = json_decode($booking_list['agent_fare_break_up'], true);
                        $user_id = $AmendmentDetail['wl_agent_id'];
                        $key = "wl_agent_id";
                        $CompanyGSTInfo = $CommonModel->getDataRowType('agent', array("id" => $user_id, 'web_partner_id' => $this->web_partner_id), "gst_state_code");
                    } else {
                        $AccounttableName = "customer_account_log";
                        $fareBreakupArray = json_decode($booking_list['customer_fare_break_up'], true);
                        $key = "customer_id";
                        $user_id = $AmendmentDetail['wl_customer_id'];
                        $CompanyGSTInfo = "";
                    }

                    $offeredfare = $fareBreakupArray['OfferedPrice'];
                    $hotelBookingAccountinfo = $HolidayModel->getDataArray($AccounttableName, array("booking_ref_no" => $holiday_booking_id, 'service' => "holiday", "action_type" => "booking", 'transaction_type' => "debit", 'web_partner_id' => $AmendmentDetail['web_partner_id']), $singalRecord = 1, $whereApply = 1);
                    if (!empty($hotelBookingAccountinfo)) {
                        $amendmentChargesArray = json_decode($booking_list['amendment_charges'], true);
                        $TDSReturn = 0;
                        if (isset($amendmentChargesArray['TDSReturnIdentifier']) && $amendmentChargesArray['TDSReturnIdentifier'] == "yes") {
                            $TDSReturn = $fareBreakupArray['TDS'];
                        }
                        $refundAmount = $offeredfare - $amendmentChargesArray['Charge'] - $amendmentChargesArray['ServiceCharge'] - $amendmentChargesArray['GST']['TotalGSTAmount'] + $TDSReturn;

                        $extra_param = json_decode($hotelBookingAccountinfo['extra_param'], true);

                        /* invoice  Number Generate Number */
                        $ServiceCharges = intval($amendmentChargesArray['ServiceCharge']);
                        $checkTaxableInvoce = checkTaxableNonTaxableINV($ServiceCharges, $CompanyGSTInfo, 'holiday', 'RFND');

                        $INVPrifix = getTaxableNonTaxableINVSuffix('RFND', $checkTaxableInvoce, 'holiday');
                        $financialYear = get_financial_year();
                        $whereCondition['service'] = 'holiday';
                        $whereCondition['invoice_type'] = 'RFND';
                        $whereCondition['financial_year'] = $financialYear;
                        $otherParameter['financialYear'] = $financialYear;
                        $otherParameter['service'] = 'holiday';
                        $otherParameter['invoice_type'] = 'RFND';
                        $otherParameter['INVPrifix'] = $INVPrifix;
                        $otherParameter['web_partner_id'] = $this->web_partner_id;
                        $otherParameter['checkTaxableInvoce'] = $checkTaxableInvoce;

                        $generateInvoiceData = $CommonModel->getInvoiceSuffixData($whereCondition, $otherParameter);
                        $InvoiceInfoData = generateInvoiceNumber($generateInvoiceData);
                        $InvoiceNumber = $InvoiceInfoData['InvoiceNumber'];
                        $InvoiceupdateData = $InvoiceInfoData['updateData'];
                        $HolidayModel->updateWithTableData('invoice_suffix_list', $InvoiceupdateData, $whereCondition);

                        /* invoice  Number Generate Number */

                        if (empty($extra_param)) {

                            $extra_param = array();
                        }

                        $web_partner_id = $hotelBookingAccountinfo['web_partner_id'];
                        $topupAmount = round_value($refundAmount);
                        $WebPartnerAccountLogData['web_partner_id'] = $web_partner_id;
                        $WebPartnerAccountLogData['user_id'] = $this->user_id;
                        $WebPartnerAccountLogData[$key] = $user_id;
                        $WebPartnerAccountLogData['created'] = create_date();
                        $WebPartnerAccountLogData['transaction_type'] = "credit";
                        $WebPartnerAccountLogData['action_type'] = "refund";
                        $WebPartnerAccountLogData['role'] = 'web_partner';
                        $WebPartnerAccountLogData['extra_param'] = json_encode($extra_param);
                        $WebPartnerAccountLogData['service'] = "holiday";
                        $WebPartnerAccountLogData['invoice_number'] = $InvoiceNumber;
                        $WebPartnerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);
                        $available_balance = $HolidayModel->agent_user_available_balance($AccounttableName, $key, $user_id, $web_partner_id);
                        if (!$available_balance) {
                            $available_balance['balance'] = 0;
                        }
                        $WebPartnerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));
                        $WebPartnerAccountLogData['credit'] = $topupAmount;

                        $pax_detail = $AmendmentModel->get_list_by_table_name('holiday_booking_travelers', "first_name,last_name", ["holiday_booking_id" => $AmendmentDetail['booking_ref_no'], 'lead_pax' => 1]);
                        $PaxName = $pax_detail['first_name'] . ' ' . $pax_detail['last_name'];
                        $WebPartnerAccountLogData['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $booking_list['package_name'] . '-' . $booking_list['package_category']));

                        $WebPartnerAccountLogData['remark'] = "Refund for Reference No - " . $booking_list['booking_ref_number'] . " Remark " . $AmendmentDetail['remark_from_user'] . " Remark " . $AmendmentDetail['remark_from_web_partner'] . " Remark " . $data['account_remark'];
                        $WebPartnerAccountLogData['booking_ref_no'] = $holiday_booking_id;
                        $added_data_id = $HolidayModel->insertData($AccounttableName, $WebPartnerAccountLogData);
                        $WebPatnerAccountLogDataUpdate['acc_ref_number'] = reference_number($added_data_id);
                        $HolidayModel->updateWithTableData($AccounttableName, $WebPatnerAccountLogDataUpdate, array("id" => $added_data_id));
                        $HolidayModel->updateWithTableData("holiday_booking_list", array("refund_account_id" => $added_data_id), array("id" => $holiday_booking_id));
                        $updateData['refund_status'] = "Close";
                        $updateData['account_remark'] = $data['account_remark'];
                        $updateData['agent_staff_id'] = $this->user_id;
                        $updateData['refund_close_date'] = create_date();
                        $update = $AmendmentModel->updateData($updateData, array("id" => $amendment_id));
                        if ($update) {
                            $message = array("StatusCode" => 0, "Message" => "Refund  has been successfully done", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Refund  has not been successfully done", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Refund unable to process,Booking amount not deducted", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "In Sufficient Refund Parameter ", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function holiday_settings()
    {
        if (permission_access_error("Holiday", "holiday_setting")) {
            $HolidayModel = new HolidayModel();
            if ($this->request->getMethod() == 'post') {
                $validate = new Validation();
                $rules = $this->validate($validate->holiday_settings_validation);
                if (!$rules) {
                    $errors = $this->validation->getErrors();
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                } else {
                    $getData = $this->request->getPost();

                    foreach ($getData['key_source'] as $key => $value) {
                        $where = ['key_source' => $value, 'service' => 'Holiday', 'web_partner_id' => $this->web_partner_id];
                        $checkData = $HolidayModel->getData('website_common_data', $where, 'id');
                        if ($checkData) {
                            $uData = ['value' => $getData[$value]];
                            $HolidayModel->updateUserData('website_common_data', $where, $uData);
                        } else {
                            $iData = array(
                                'web_partner_id' => $this->web_partner_id,
                                'service' => 'Holiday',
                                'key_source' => $value,
                                'value' => $getData[$value]
                            );
                            $HolidayModel->insertData('website_common_data', $iData);
                        }
                    }
                    $message = array("StatusCode" => 0, "Message" => "Data Added Successfully", "Class" => "success_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            } else {
                $dataKeyValue = [
                    'polices' => "Terms & Conditions",
                    'payment_structure' => "Payment Structure",
                    'documentation' => "Documentation",
                    'cancellation_policy' => "Cancellation Policy",
                    'refund_policy' => "Refund Policy",
                ];
                $settings_list = $HolidayModel->holiday_settings_list_select($this->web_partner_id);

                $data = [
                    'title' => $this->title,
                    'view' => "Holiday\Views/holiday-settings",
                    'datalist' => $dataKeyValue,
                    'getData' => array_column($settings_list, 'value', 'key_source')
                ];
                return view('template/sidebar-layout', $data);
            }
        }
    }

    public function export_amendments()
    {
        $rules = $this->validate([
            'from_date' => [
                'label' => 'From Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select from date.'
                ]
            ],
            'to_date' => [
                'label' => 'To Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select to date.'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            $get_data = $this->request->getPost();
            $bookingType = 'all';
            $source = '';

            $AmendmentModel = new AmendmentModel();
            $BookingDetail = $AmendmentModel->search_bookings($get_data, $this->web_partner_id, $this->web_partner_details['id'], $this->web_partner_details['primary_user'], $bookingType, $source);

            $fileName = 'holiday-amendments-report' . "." . 'xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
            $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            $sheet->getColumnDimension('R')->setAutoSize(true);


            $sheet->setCellValue('A1', 'Ref. No.');
            $sheet->setCellValue('B1', 'Amendment Id');
            $sheet->setCellValue('C1', 'Amendment Type');
            $sheet->setCellValue('D1', 'Amendment Status');
            $sheet->setCellValue('E1', 'Package');
            $sheet->setCellValue('F1', 'Type');
            $sheet->setCellValue('G1', 'Travel Date');


            $sheet->setCellValue('H1', 'Booking Status');
            $sheet->setCellValue('I1', 'Admin Remark');

            $sheet->setCellValue('J1', 'Generate By');

            $sheet->setCellValue('K1', 'Booking Source');
            $sheet->setCellValue('L1', 'Admin Staff');
            $sheet->setCellValue('M1', 'Created');
            $sheet->setCellValue('N1', '');

            $sheet->setCellValue('O1', '');


            $rows = 2;

            foreach ($BookingDetail as $key => $data) {

                $sheet->setCellValue('A' . $rows, $data['booking_ref_number']);
                $sheet->setCellValue('B' . $rows, $data['id']);
                $sheet->setCellValue('C' . $rows, ucfirst($data['amendment_type']));
                $sheet->setCellValue('D' . $rows, ucfirst($data['amendment_status']));
                $sheet->setCellValue('E' . $rows, ucwords($data['package_name']));
                $sheet->setCellValue('F' . $rows, ucwords($data['package_category']));
                $sheet->setCellValue('G' . $rows, ($data['travel_date']));


                $sheet->setCellValue('H' . $rows, ucfirst($data['booking_status']));
                $sheet->setCellValue('I' . $rows, $data['remark_from_web_partner']);

                $sheet->setCellValue('J' . $rows, ($data['booking_source'] == 'Wl_b2b') ? $data['agent_name'] : $data['customer_name']);

                $sheet->setCellValue('K' . $rows, service_booking_source($data['booking_source'] ?? ""));
                $sheet->setCellValue('L' . $rows, $data['staff_name']);
                $sheet->setCellValue('M' . $rows, date_created_format($data['created']));
                $sheet->setCellValue('N' . $rows, '');
                $sheet->setCellValue('O' . $rows, '');
                $rows++;
            }

            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);

            return $this->response->setJSON($data_validation);
        }
    }



    function getCreditNote()
    {
        $HolidayModel = new HolidayModel();
        $getData = $this->request->getPOST();
        if (!$this->request->isAJAX()) {
            $getTicketInvioceType = array("CreditNote" => "CreditNote");
            $getData = $this->request->getGet();
            $bookingRefNumber = $getData['booking_ref_number'];
            $bookingInfo = array();
            if ($bookingRefNumber) {
                $whereClauseBookingCheck = array("booking_ref_number" => $bookingRefNumber);
                $bookingInfo = $HolidayModel->getData("holiday_booking_list", $whereClauseBookingCheck, "*");
                if ($bookingInfo) {

                    if ($bookingInfo['booking_source'] == "Wl_b2b") {
                        $getTicketUserType = array("CreditNote" => "wl-agent");
                    } else {
                        $getTicketUserType = array("CreditNote" => "wl-customer");
                    }
                    $TicketViewRequest = array(
                        "BookingId" => isset($bookingInfo['id']) ? $bookingInfo['id'] : "",
                        "SearchTokenId" => isset($bookingInfo['tts_search_token']) ? $bookingInfo['tts_search_token'] : "",
                        "HtmlType" => $getTicketInvioceType[$getData['type']],
                        "UserType" => $getTicketUserType[$getData['type']],
                        "ViewService" => "View",
                        "WithAgencyDetail" => "1",
                        "ViewSize" => "",
                    );
                    $url = $this->Services . 'generate-wl-credit-note';
                    $response = RequestWithoutAuth($TicketViewRequest, $url);
                    $data = [
                        'title' => $this->title,
                        'view' => "Holiday\Views\booking\print_ticket",
                        'data' => $response['Result']['Html'],
                    ];
                    return view('template/sidebar-layout', $data);
                } else {
                    return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
                }
            } else {
                return $this->response->redirect(site_url('holiday/error?errormessage=Request Not Allowed'));
            }
        }
    }




    public function holiday_markup_list()
    {
        if (permission_access_error("Holiday", "holiday_markup_list")) {
            $HolidayMarkupModel = new HolidayMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayMarkupModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayMarkupModel->holiday_markup_list($this->web_partner_id);
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list' => $agent_class_list,
                'view' => "Holiday\Views\MarkupAndDiscount\holiday-markup-list",
                'pager' => $HolidayMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function holiday_markup_view()
    {
        if (permission_access_error("Holiday", "holiday_markup_add")) {
            $AgentClassModel = new AgentClassModel();
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayModel = new HolidayModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $theme = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $package_list = $HolidayModel->getDataArray('holiday_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id,holiday_name');
            $data = [
                'title' => $this->title,
                'holiday_theme' => $theme,
                'holiday_destination' => $destination,
                'holiday_package' => $package_list,
                'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id),
            ];
            $add_blog_view = view('Modules\Holiday\Views\MarkupAndDiscount\add-holiday-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_holiday_markup()
    {
        if (permission_access("Holiday", "holiday_markup_add")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            if ($data['markup_type'] == "percent") {
                $validate->holiday_markup_validation['max_limit']['rules'] = 'trim|required|numeric';
                $validate->holiday_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
            }
            if ($data['markup_for'] == "B2C") {
                unset($validate->holiday_markup_validation['agent_class.*']);
            }
            if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['destination_name.*']);
            }
            if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['theme_name.*']);
            }
            if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['holiday_package.*']);
            }
            $rules = $this->validate($validate->holiday_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['agent_class.*'])) {
                    $errors['agent_class[]'] = $errors['agent_class.*'];
                    unset($errors['agent_class.*']);
                }
                if (isset($errors['theme_name.*'])) {
                    $errors['theme_name[]'] = $errors['theme_name.*'];
                    unset($errors['theme_name.*']);
                }
                if (isset($errors['destination_name.*'])) {
                    $errors['destination_name[]'] = $errors['destination_name.*'];
                    unset($errors['destination_name.*']);
                }
                if (isset($errors['holiday_package.*'])) {
                    $errors['holiday_package[]'] = $errors['holiday_package.*'];
                    unset($errors['holiday_package.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayMarkupModel = new HolidayMarkupModel();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

                if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                    $data['destination_name'] = 'ANY';
                    $data['destination_id'] = 'ANY';
                    unset($data['destination_select']);
                } else {
                    $destination_id = array();
                    $destination_name = array();
                    foreach ($data['destination_name'] as $destination) {
                        $temp_des = explode('_', $destination);
                        array_push($destination_id, $temp_des[0]);
                        array_push($destination_name, $temp_des[1]);
                    }
                    $data['destination_id'] = implode(',', $destination_id);
                    $data['destination_name'] = implode(',', $destination_name);
                }
                if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                    $data['theme_name'] = 'ANY';
                    $data['theme_id'] = 'ANY';
                    unset($data['theme_select']);
                } else {
                    $theme_id = array();
                    $theme_name = array();
                    foreach ($data['theme_name'] as $theme) {
                        $temp_theme = explode('_', $theme);
                        array_push($theme_id, $temp_theme[0]);
                        array_push($theme_name, $temp_theme[1]);
                    }
                    $data['theme_id'] = implode(',', $theme_id);
                    $data['theme_name'] = implode(',', $theme_name);
                }
                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['holiday_package'] = 'ANY';
                    $data['holiday_package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $holiday_package_id = array();
                    $holiday_package = array();
                    foreach ($data['holiday_package'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($holiday_package_id, $temp_package[0]);
                        array_push($holiday_package, $temp_package[1]);
                    }
                    $data['holiday_package_id'] = implode(',', $holiday_package_id);
                    $data['holiday_package'] = implode(',', $holiday_package);
                }

                $added_data = $HolidayMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_admin_holiday_markup_template()
    {
        if (permission_access_error("Holiday", "holiday_markup_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $HolidayMarkupModel = new HolidayMarkupModel();
            $details = $HolidayMarkupModel->holiday_markup_details($id, $this->web_partner_id);
            $details['agent_class'] = explode(',', $details['agent_class']);

            $AgentClassModel = new AgentClassModel();
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayModel = new HolidayModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $theme = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $package_list = $HolidayModel->getDataArray('holiday_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id,holiday_name');
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'holiday_theme' => $theme,
                'holiday_destination' => $destination,
                'holiday_package' => $package_list,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
            ];

            $details = view('Modules\Holiday\Views\MarkupAndDiscount\edit-holiday-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_holiday_markup()
    {
        if (permission_access("Holiday", "holiday_markup_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $data = $this->request->getPost();
            $validate = new Validation();
            if ($data['markup_type'] == "percent") {
                $validate->holiday_markup_validation['max_limit']['rules'] = 'trim|required|numeric';
                $validate->holiday_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
            }
            if ($data['markup_for'] == "B2C") {
                unset($validate->holiday_markup_validation['agent_class.*']);
            }
            if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['destination_name.*']);
            }
            if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['theme_name.*']);
            }
            if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                unset($validate->holiday_markup_validation['holiday_package.*']);
            }
            $rules = $this->validate($validate->holiday_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['agent_class.*'])) {
                    $errors['agent_class[]'] = $errors['agent_class.*'];
                    unset($errors['agent_class.*']);
                }
                if (isset($errors['theme_name.*'])) {
                    $errors['theme_name[]'] = $errors['theme_name.*'];
                    unset($errors['theme_name.*']);
                }
                if (isset($errors['destination_name.*'])) {
                    $errors['destination_name[]'] = $errors['destination_name.*'];
                    unset($errors['destination_name.*']);
                }
                if (isset($errors['holiday_package.*'])) {
                    $errors['holiday_package[]'] = $errors['holiday_package.*'];
                    unset($errors['holiday_package.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayMarkupModel = new HolidayMarkupModel();
                $data['modified'] = create_date();
                $data['agent_class'] = ($data['markup_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                    $data['destination_name'] = 'ANY';
                    $data['destination_id'] = 'ANY';
                    unset($data['destination_select']);
                } else {
                    $destination_id = array();
                    $destination_name = array();
                    foreach ($data['destination_name'] as $destination) {
                        $temp_des = explode('_', $destination);
                        array_push($destination_id, $temp_des[0]);
                        array_push($destination_name, $temp_des[1]);
                    }
                    $data['destination_id'] = implode(',', $destination_id);
                    $data['destination_name'] = implode(',', $destination_name);
                }
                if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                    $data['theme_name'] = 'ANY';
                    $data['theme_id'] = 'ANY';
                    unset($data['theme_select']);
                } else {
                    $theme_id = array();
                    $theme_name = array();
                    foreach ($data['theme_name'] as $theme) {
                        $temp_theme = explode('_', $theme);
                        array_push($theme_id, $temp_theme[0]);
                        array_push($theme_name, $temp_theme[1]);
                    }
                    $data['theme_id'] = implode(',', $theme_id);
                    $data['theme_name'] = implode(',', $theme_name);
                }
                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['holiday_package'] = 'ANY';
                    $data['holiday_package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $holiday_package_id = array();
                    $holiday_package = array();
                    foreach ($data['holiday_package'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($holiday_package_id, $temp_package[0]);
                        array_push($holiday_package, $temp_package[1]);
                    }
                    $data['holiday_package_id'] = implode(',', $holiday_package_id);
                    $data['holiday_package'] = implode(',', $holiday_package);
                }

                $added_data = $HolidayMarkupModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function holiday_markup_status_change()
    {
        if (permission_access("Holiday", "holiday_markup_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayMarkupModel = new HolidayMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayMarkupModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_holiday_markup()
    {
        if (permission_access_error("Holiday", "holiday_markup_delete")) {
            $HolidayMarkupModel = new HolidayMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HolidayMarkupModel->remove_markup($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function holiday_discount_list()
    {
        if (permission_access_error("Holiday", "holiday_discount_list")) {
            $HolidayDiscountModel = new HolidayDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayDiscountModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HolidayDiscountModel->holiday_discount_list($this->web_partner_id);
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list' => $agent_class_list,
                'view' => "Holiday\Views\MarkupAndDiscount\holiday-discount-list",
                'pager' => $HolidayDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function holiday_discount_view()
    {
        if (permission_access_error("Holiday", "holiday_discount_add")) {

            $AgentClassModel = new AgentClassModel();
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayModel = new HolidayModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $theme = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $package_list = $HolidayModel->getDataArray('holiday_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id,holiday_name');
            $data = [
                'title' => $this->title,
                'holiday_theme' => $theme,
                'holiday_destination' => $destination,
                'holiday_package' => $package_list,
                'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id),
            ];
            $add_blog_view = view('Modules\Holiday\Views\MarkupAndDiscount\add-holiday-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_holiday_discount()
    {
        if (permission_access_error("Holiday", "holiday_discount_add")) {
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->holiday_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->holiday_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            if ($data['discount_type'] == "percent") {
                $validate->holiday_discount_validation['max_limit']['rules'] = 'trim|required|numeric';
                $validate->holiday_discount_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
            }
            if ($data['discount_for'] == "B2C") {
                unset($validate->holiday_discount_validation['agent_class.*']);
            }
            if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['destination_name.*']);
            }
            if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['theme_name.*']);
            }
            if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['holiday_package.*']);
            }
            //pr($data);exit;
            $rules = $this->validate($validate->holiday_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['agent_class.*'])) {
                    $errors['agent_class[]'] = $errors['agent_class.*'];
                    unset($errors['agent_class.*']);
                }
                if (isset($errors['theme_name.*'])) {
                    $errors['theme_name[]'] = $errors['theme_name.*'];
                    unset($errors['theme_name.*']);
                }
                if (isset($errors['destination_name.*'])) {
                    $errors['destination_name[]'] = $errors['destination_name.*'];
                    unset($errors['destination_name.*']);
                }
                if (isset($errors['holiday_package.*'])) {
                    $errors['holiday_package[]'] = $errors['holiday_package.*'];
                    unset($errors['holiday_package.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDiscountModel = new HolidayDiscountModel();
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);

                if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                    $data['destination_name'] = 'ANY';
                    $data['destination_id'] = 'ANY';
                    unset($data['destination_select']);
                } else {
                    $destination_id = array();
                    $destination_name = array();
                    foreach ($data['destination_name'] as $destination) {
                        $temp_des = explode('_', $destination);
                        array_push($destination_id, $temp_des[0]);
                        array_push($destination_name, $temp_des[1]);
                    }
                    $data['destination_id'] = implode(',', $destination_id);
                    $data['destination_name'] = implode(',', $destination_name);
                }
                if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                    $data['theme_name'] = 'ANY';
                    $data['theme_id'] = 'ANY';
                    unset($data['theme_select']);
                } else {
                    $theme_id = array();
                    $theme_name = array();
                    foreach ($data['theme_name'] as $theme) {
                        $temp_theme = explode('_', $theme);
                        array_push($theme_id, $temp_theme[0]);
                        array_push($theme_name, $temp_theme[1]);
                    }
                    $data['theme_id'] = implode(',', $theme_id);
                    $data['theme_name'] = implode(',', $theme_name);
                }
                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['holiday_package'] = 'ANY';
                    $data['holiday_package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $holiday_package_id = array();
                    $holiday_package = array();
                    foreach ($data['holiday_package'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($holiday_package_id, $temp_package[0]);
                        array_push($holiday_package, $temp_package[1]);
                    }
                    $data['holiday_package_id'] = implode(',', $holiday_package_id);
                    $data['holiday_package'] = implode(',', $holiday_package);
                }
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $HolidayDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }




    public function edit_admin_holiday_discount_template()
    {
        if (permission_access_error("Holiday", "holiday_discount_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $AgentClassModel = new AgentClassModel();
            $HolidayDiscountModel = new HolidayDiscountModel();
            $details = $HolidayDiscountModel->holiday_discount_details($id, $this->web_partner_id);
            $HolidayThemesModel = new HolidayThemesModel();
            $HolidayModel = new HolidayModel();
            $HolidayDestinationModel = new HolidayDestinationModel();
            $theme = $HolidayThemesModel->holiday_themes_list_select($this->web_partner_id);
            $destination = $HolidayDestinationModel->holiday_destination_list_select($this->web_partner_id);
            $package_list = $HolidayModel->getDataArray('holiday_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id,holiday_name');

            $details['agent_class'] = explode(',', $details['agent_class']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'holiday_theme' => $theme,
                'holiday_destination' => $destination,
                'holiday_package' => $package_list,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id)

            ];

            $details = view('Modules\Holiday\Views\MarkupAndDiscount\edit-holiday-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_holiday_discount()
    {
        if (permission_access("Holiday", "holiday_discount_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->holiday_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->holiday_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            if ($data['discount_type'] == "percent") {
                $validate->holiday_discount_validation['max_limit']['rules'] = 'trim|required|numeric';
                $validate->holiday_discount_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
            }
            if ($data['discount_for'] == "B2C") {
                unset($validate->holiday_discount_validation['agent_class.*']);
            }
            if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['destination_name.*']);
            }
            if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['theme_name.*']);
            }
            if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                unset($validate->holiday_discount_validation['holiday_package.*']);
            }
            $rules = $this->validate($validate->holiday_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if (isset($errors['agent_class.*'])) {
                    $errors['agent_class[]'] = $errors['agent_class.*'];
                    unset($errors['agent_class.*']);
                }
                if (isset($errors['theme_name.*'])) {
                    $errors['theme_name[]'] = $errors['theme_name.*'];
                    unset($errors['theme_name.*']);
                }
                if (isset($errors['destination_name.*'])) {
                    $errors['destination_name[]'] = $errors['destination_name.*'];
                    unset($errors['destination_name.*']);
                }
                if (isset($errors['holiday_package.*'])) {
                    $errors['holiday_package[]'] = $errors['holiday_package.*'];
                    unset($errors['holiday_package.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDiscountModel = new HolidayDiscountModel();
                $data['agent_class'] = ($data['discount_for'] == 'B2C') ? NULL : implode(',', $data['agent_class']);
                if (isset($data['destination_select']) && $data['destination_select'] == 'ANY') {
                    $data['destination_name'] = 'ANY';
                    $data['destination_id'] = 'ANY';
                    unset($data['destination_select']);
                } else {
                    $destination_id = array();
                    $destination_name = array();
                    foreach ($data['destination_name'] as $destination) {
                        $temp_des = explode('_', $destination);
                        array_push($destination_id, $temp_des[0]);
                        array_push($destination_name, $temp_des[1]);
                    }
                    $data['destination_id'] = implode(',', $destination_id);
                    $data['destination_name'] = implode(',', $destination_name);
                }
                if (isset($data['theme_select']) && $data['theme_select'] == 'ANY') {
                    $data['theme_name'] = 'ANY';
                    $data['theme_id'] = 'ANY';
                    unset($data['theme_select']);
                } else {
                    $theme_id = array();
                    $theme_name = array();
                    foreach ($data['theme_name'] as $theme) {
                        $temp_theme = explode('_', $theme);
                        array_push($theme_id, $temp_theme[0]);
                        array_push($theme_name, $temp_theme[1]);
                    }
                    $data['theme_id'] = implode(',', $theme_id);
                    $data['theme_name'] = implode(',', $theme_name);
                }
                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['holiday_package'] = 'ANY';
                    $data['holiday_package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $holiday_package_id = array();
                    $holiday_package = array();
                    foreach ($data['holiday_package'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($holiday_package_id, $temp_package[0]);
                        array_push($holiday_package, $temp_package[1]);
                    }
                    $data['holiday_package_id'] = implode(',', $holiday_package_id);
                    $data['holiday_package'] = implode(',', $holiday_package);
                }
                $data['modified'] = create_date();

                $added_data = $HolidayDiscountModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function holiday_discount_status_change()
    {
        if (permission_access_error("Holiday", "holiday_discount_change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayDiscountModel = new HolidayDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HolidayDiscountModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "holiday discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_holiday_discount()
    {
        if (permission_access_error("Holiday", "holiday_discount_delete")) {
            $HolidayDiscountModel = new HolidayDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HolidayDiscountModel->remove_discount($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }



    public function remove_holiday_itinerary()
    {
        $itinerary_id = $this->request->getPOST()['itinerary_id'];
        $HolidayItineraryModel = new HolidayItineraryModel();
        $deleted = $HolidayItineraryModel->where('id', $itinerary_id)->delete();

        if ($deleted) {
            return $this->response->setJSON(['success' => true, 'message' => 'Itinerary deleted successfully', "class" => "success_popup"]);
        } else {
            return $this->response->setJSON(['success' => true, 'message' => 'Failed to delete itinerary', "class" => "error_popup"]);
        }

    }


    public function get_package()
    {
        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);
        $HolidayModel = new HolidayModel();
        $get_package = $HolidayModel->get_packages_autosuggestion($terms, $this->web_partner_id);

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
}
