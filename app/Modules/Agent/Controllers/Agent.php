<?php

namespace Modules\Agent\Controllers;

use App\Modules\Agent\Models\FlightDiscountModel;
use App\Modules\Agent\Models\FlightMarkupModel;
use App\Modules\Agent\Models\FlightAirportModel;
use App\Modules\Agent\Models\FlightAirlineModel;
use App\Modules\Agent\Models\AgentModel;
use App\Modules\Agent\Models\AgentClassModel;
use App\Modules\Agent\Models\AgentAccountLogModel;
use App\Modules\Agent\Models\BusMarkupModel;
use App\Controllers\BaseController;
use Modules\Agent\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Agent extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Agent";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->wl_agent_id = admin_cookie_data()['admin_user_details']['agent_id'];
        $this->folder_name = 'agent';
        // if (permission_access_error("Agent", "Agent_Module")) {

        // }

    }

    public function index()
    {
        $AgentModel = new AgentModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $AgentModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $AgentModel->agent_list($this->web_partner_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Agent\Views\agent-list",
            'pager' => $AgentModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function add_agent_view()
    {
        if (permission_access_error("Agent", "add_agent")) {
            $AgentClassModel = new AgentClassModel();
            $data['agent_class'] = $AgentClassModel->agent_class_list($this->web_partner_id);
            $add_slider_view = view('Modules\Agent\Views\add-agent', $data);
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_agent()
    {
        if (permission_access_error("Agent", "add_agent")) {
            $resizeDim = array('width' => 150, 'height' => 80);

            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $validate = new Validation();

            $field_name_profile_pic = "profile_pic";
            $field_name_company_logo = "company_logo";
            $field_name_gst_scan_copy = "gst_scan_copy";

            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->agent_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $company_logo = $this->request->getFile($field_name_company_logo);
            if ($company_logo->getName() != '') {
                $validate->agent_validation[$field_name_company_logo]['rules'] = "uploaded[$field_name_company_logo]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_company_logo,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_company_logo]['errors'] = $errors;
            }

            $gst_scan_copy = $this->request->getFile($field_name_gst_scan_copy);
            if ($gst_scan_copy->getName() != '') {
                $validate->agent_validation[$field_name_gst_scan_copy]['rules'] = "uploaded[$field_name_gst_scan_copy]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_gst_scan_copy,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_gst_scan_copy]['errors'] = $errors;
            }

            $rules = $this->validate($validate->agent_validation);


            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image

                $field_name_pan_scan_copy = 'pan_scan_copy';
                $pan_scan_copy = $this->request->getFile($field_name_pan_scan_copy);
                $image_upload = image_upload($pan_scan_copy, $field_name_pan_scan_copy, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {

                    /* optional file upload */

                    if ($profile_pic->getName() != '') {
                        $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->folder_name, $resizeDim);
                        if ($profile_pic_upload['status_code'] == 0) {
                            $profile_pic = $profile_pic_upload['file_name'];
                        } else {
                            $profile_pic = null;
                            $message = array("StatusCode" => 2, "Message" => $profile_pic_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $profile_pic = null;
                    }


                    if ($company_logo->getName() != '') {
                        $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->folder_name, $resizeDim);
                        if ($company_logo_upload['status_code'] == 0) {
                            $company_logo = $company_logo_upload['file_name'];
                        } else {
                            $company_logo = null;
                            $message = array("StatusCode" => 2, "Message" => $company_logo_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $company_logo = null;
                    }


                    if ($gst_scan_copy->getName() != '') {
                        $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->folder_name, $resizeDim);
                        if ($gst_scan_copy_upload['status_code'] == 0) {
                            $gst_scan_copy = $gst_scan_copy_upload['file_name'];
                        } else {
                            $gst_scan_copy = null;
                            $message = array("StatusCode" => 2, "Message" => $gst_scan_copy_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $gst_scan_copy = null;
                    }
                    /*end  optional file upload */

                    /*password encryption*/

                    $password = md5($this->request->getPost('password'));
                    $data['password'] = $password;

                    /*end password encryption*/

                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    if ($data['dob']) {
                        $data['dob'] = strtotime($data['dob']);
                    }
                    $data[$field_name_pan_scan_copy] = $image_upload['file_name'];
                    $data[$field_name_profile_pic] = $profile_pic;
                    $data[$field_name_company_logo] = $company_logo;
                    $data[$field_name_gst_scan_copy] = $gst_scan_copy;

                    $added_data = $AgentModel->insert($data);


                    $log['agent_id'] = $added_data;
                    $log['web_partner_id'] = $this->web_partner_id;
                    $log['created'] = create_date();
                    $log['action_type'] = 'credit';
                    $data['credit'] = 0;
                    $log['balance'] = 0;
                    $log['remark'] = 'Account created by admin';
                    $log['payment_mode'] = 'Temporary_Credit';

                    $AgentAccountLogModel = new AgentAccountLogModel();
                    $account_log_added = $AgentAccountLogModel->insert($log);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Agent Successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Agent not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_agent_view()
    {
        if (permission_access_error("Agent", "edit_agent")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);
            $AgentModel = new AgentModel();
            $details = $AgentModel->agent_list_details($id, $this->web_partner_id);

            if (isset($details['dob']) && $details['dob'] != '') {
                $details['dob'] = timestamp_to_date($details['dob']);
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'agent_class' => $agent_class,
                'details' => $details,
            ];
            $details = view('Modules\Agent\Views\edit-agent', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_agent()
    {
        if (permission_access_error("Agent", "edit_agent")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];

            $validate = new Validation();
            unset($validate->agent_validation['password']);
            $resizeDim = array('width' => 150, 'height' => 80);

            $field_name_profile_pic = "profile_pic";
            $field_name_company_logo = "company_logo";
            $field_name_gst_scan_copy = "gst_scan_copy";
            $field_name_pan_scan_copy = 'pan_scan_copy';

            $pan_scan_copy = $this->request->getFile($field_name_pan_scan_copy);
            if ($pan_scan_copy->getName() == '') {
                unset($validate->agent_validation[$field_name_pan_scan_copy]);
            }

            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->agent_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $company_logo = $this->request->getFile($field_name_company_logo);
            if ($company_logo->getName() != '') {
                $validate->agent_validation[$field_name_company_logo]['rules'] = "uploaded[$field_name_company_logo]|max_size[$field_name_company_logo,1024]|mime_in[$field_name_company_logo,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_company_logo]['errors'] = $errors;
            }

            $gst_scan_copy = $this->request->getFile($field_name_gst_scan_copy);
            if ($gst_scan_copy->getName() != '') {
                $validate->agent_validation[$field_name_gst_scan_copy]['rules'] = "uploaded[$field_name_gst_scan_copy]|max_size[$field_name_gst_scan_copy,1024]|mime_in[$field_name_gst_scan_copy,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_gst_scan_copy]['errors'] = $errors;
            }

            $validate->agent_validation['email_id']['rules'] = "required|valid_email|is_unique[agent.email_id,id,$id]";
            $rules = $this->validate($validate->agent_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $data = $this->request->getPost();
                $previous_data = $AgentModel->agent_list_details($id, $this->web_partner_id);

                /* optional file upload */

                if ($profile_pic->getName() != '') {
                    $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->folder_name, $resizeDim);
                    if ($profile_pic_upload['status_code'] == 0) {
                        $profile_pic = $profile_pic_upload['file_name'];

                        if ($previous_data[$field_name_profile_pic]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name_profile_pic])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name_profile_pic]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_profile_pic]);
                            }
                        }
                    } else {
                        $profile_pic = null;
                        $message = array("StatusCode" => 2, "Message" => $profile_pic_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $profile_pic = $previous_data[$field_name_profile_pic];
                }

                if ($company_logo->getName() != '') {
                    $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->folder_name, $resizeDim);
                    if ($company_logo_upload['status_code'] == 0) {
                        $company_logo = $company_logo_upload['file_name'];

                        if ($previous_data[$field_name_company_logo]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name_company_logo])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name_company_logo]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_company_logo]);
                            }
                        }
                    } else {
                        $company_logo = null;
                        $message = array("StatusCode" => 2, "Message" => $company_logo_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $company_logo = $previous_data[$field_name_company_logo];
                }

                if ($gst_scan_copy->getName() != '') {
                    $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->folder_name, $resizeDim);
                    if ($gst_scan_copy_upload['status_code'] == 0) {
                        $gst_scan_copy = $gst_scan_copy_upload['file_name'];
                        if ($previous_data[$field_name_gst_scan_copy]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name_gst_scan_copy])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name_gst_scan_copy]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_gst_scan_copy]);
                            }
                        }
                    } else {
                        $gst_scan_copy = null;
                        $message = array("StatusCode" => 2, "Message" => $gst_scan_copy_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $gst_scan_copy = $previous_data[$field_name_gst_scan_copy];;
                }

                $data[$field_name_profile_pic] = $profile_pic;
                $data[$field_name_company_logo] = $company_logo;
                $data[$field_name_gst_scan_copy] = $gst_scan_copy;
                /*end  optional file upload */

                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }

                if ($pan_scan_copy->getName() != '') {

                    $image_upload = image_upload($pan_scan_copy, $field_name_pan_scan_copy, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name_pan_scan_copy]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name_pan_scan_copy])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name_pan_scan_copy]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_pan_scan_copy]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name_pan_scan_copy] = $image_upload['file_name'];
                        $added_data = $AgentModel->where("id", $id)->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Agent Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Agent not  Updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data[$field_name_pan_scan_copy] = $previous_data[$field_name_pan_scan_copy];
                    $added_data = $AgentModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Agent Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Agent not  Updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function agent_details()
    {
        $agent_id = dev_decode($this->request->uri->getSegment(3));
        $AgentModel = new AgentModel();
        $AgentAccountLogModel = new AgentAccountLogModel();

        $details = $AgentModel->agent_view_details($agent_id, $this->web_partner_id);
        $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);
        if (isset($available_balance['balance'])) {
            $details['balance'] = $available_balance['balance'];
        }

        $data = [
            'title' => $this->title,
            'id' => $agent_id,
            'details' => $details,
            'account_logs' => $AgentAccountLogModel->account_logs_detail($agent_id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\Agent\Views\agent-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    public function remove_agent()
    {
        if (permission_access_error("Agent", "delete_agent")) {
            $AgentModel = new AgentModel();
            $ids = $this->request->getPost('checklist');

            $field_name_profile_pic = "profile_pic";
            $field_name_company_logo = "company_logo";
            $field_name_gst_scan_copy = "gst_scan_copy";
            $field_name_pan_scan_copy = 'pan_scan_copy';

            foreach ($ids as $id) {
                $details = $AgentModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name_profile_pic]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name_profile_pic])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name_profile_pic]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name_profile_pic]);
                    }
                }

                if ($details[$field_name_company_logo]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name_company_logo])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name_company_logo]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name_company_logo]);
                    }
                }

                if ($details[$field_name_gst_scan_copy]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name_gst_scan_copy])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name_gst_scan_copy]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name_gst_scan_copy]);
                    }
                }

                if ($details[$field_name_pan_scan_copy]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name_pan_scan_copy])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name_pan_scan_copy]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name_pan_scan_copy]);
                    }
                }

                $delete = $AgentModel->remove_agent($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Agent  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Agent  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function change_agent_password()
    {
        if (permission_access_error("Agent", "change_password")) {

            $validate = new Validation();
            $rules = $this->validate($validate->agent_password_change);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $send_email = $this->request->getPost('send_email'); //send email pending
                $id = dev_decode($this->request->getPost('agent_id'));
                $data['password'] = md5($this->request->getPost('password'));

                $update = $AgentModel->where("id", $id)->set($data)->update();

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Password  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Password not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function agent_status_change()
    {

        if (permission_access_error("Agent", "agent_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $AgentModel->agent_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Agent status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Agent status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function agent_class()
    {
        if (permission_access_error("Agent", "agent_class_list")) {
            $AgentClassModel = new AgentClassModel();
            $data = [
                'title' => $this->title,
                'list' => $AgentClassModel->agent_class_list($this->web_partner_id),
                'pager' => $AgentClassModel->pager
            ];
            $agent_class = view('Modules\Agent\Views\agent-class', $data);
            $data = array("StatusCode" => 0, "Message" => $agent_class, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_agent_class()
    {
        if (permission_access_error("Agent", "add_agent_class")) {
            $validate = new Validation();
            $rules = $this->validate($validate->agent_class_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentClassModel = new AgentClassModel();
                $data = $this->request->getPost();
                $data['class_name'] = ucwords($data['class_name']);
                $data['created_date'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $AgentClassModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Agent Class Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Agent Class not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_agent_class()
    {
        if (permission_access_error("Agent", "delete_agent_class")) {
            $AgentClassModel = new AgentClassModel();
            $ids = dev_decode($this->request->uri->getSegment(3));
            $delete = $AgentClassModel->remove_agent_class($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Agent Class  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Agent Class  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return redirect()->back();
        }
    }

    public function agent_account_logs(): string
    {
        if (permission_access_error("Agent", "account_logs")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));;
            $AgentModel = new AgentModel();
            $AgentAccountLogModel = new AgentAccountLogModel();
            $data = [
                'title' => $this->title,
                'account_logs' => $AgentAccountLogModel->account_logs($agent_id, $this->web_partner_id),
                'details' => $AgentModel->agent_view_details($agent_id, $this->web_partner_id),
                'view' => "Agent\Views\agent-account-logs-list",
                'pager' => $AgentAccountLogModel->pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function virtual_topup_view()
    {
        if (permission_access_error("Agent", "virtual_topup")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentAccountLogModel = new AgentAccountLogModel();
            $AgentModel = new AgentModel();

            $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);

            $data['details'] = $AgentModel->agent_list_details($agent_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Agent\Views\topup-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function virtual_topup()
    {
        if (permission_access_error("Agent", "virtual_topup")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_credit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $agent_id = dev_decode($this->request->uri->getSegment(3));
                $AgentAccountLogModel = new AgentAccountLogModel();
                $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);


                $data = $this->request->getPost();
                $data['agent_id'] = $agent_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['action_type'] = 'credit';

                $data['balance'] = $available_balance['balance'] + $data['credit'];

                $added_data = $AgentAccountLogModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function virtual_debit_view()
    {
        if (permission_access_error("Agent", "virtual_deduct")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentAccountLogModel = new AgentAccountLogModel();
            $AgentModel = new AgentModel();

            $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);

            $data['details'] = $AgentModel->agent_list_details($agent_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Agent\Views\debit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function virtual_debit()
    {
        if (permission_access_error("Agent", "virtual_deduct")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_debit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $agent_id = dev_decode($this->request->uri->getSegment(3));

                $AgentAccountLogModel = new AgentAccountLogModel();
                $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);
                $data = $this->request->getPost();
                $data['agent_id'] = $agent_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['action_type'] = 'debit';
                $data['payment_mode'] = 'Temporary_Credit';

                $data['balance'] = $available_balance['balance'] - $data['debit'];

                $added_data = $AgentAccountLogModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    //
    public function flight_discount(): string
    {
        if (permission_access_error("Agent", "flight_discount_list")) {

            $FlightDiscountModel = new FlightDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightDiscountModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $FlightDiscountModel->discount_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Agent\Views\Flight-discount-list",
                'pager' => $FlightDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function flight_discount_view()
    {
        if (permission_access_error("Agent", "add_flight_discount")) {
            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'agent_class' => $agent_class
            ];
            $add_blog_view = view('Modules\Agent\Views\add-flight-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_flight_discount()
    {
        if (permission_access_error("Agent", "add_flight_discount")) {
            $validate = new Validation();

            $validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';
            $rules = $this->validate($validate->flight_discount_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightDiscountModel = new FlightDiscountModel();
                $data = $this->request->getPost();
                $temp_airline = $data['airline_code'];
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[0])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }


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

    public function edit_flight_discount_view()
    {
        if (permission_access_error("Agent", "edit_flight_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightDiscountModel = new FlightDiscountModel();
            $details = $FlightDiscountModel->discount_details($this->web_partner_id, $id);

            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);

            if (isset($details['travel_date_from']) && $details['travel_date_from'] != '') {
                $details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
            }

            if (isset($details['travel_date_to']) && $details['travel_date_to'] != '') {
                $details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
            }

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class' => $agent_class
            ];
            $blog_details = view('Modules\Agent\Views\edit-flight-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_flight_discount()
    {
        if (permission_access_error("Agent", "edit_flight_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';
            $rules = $this->validate($validate->flight_discount_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightDiscountModel = new FlightDiscountModel();
                $data = $this->request->getPost();
                $temp_airline = $this->request->getPost('airline_code');
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }


                $data['modified'] = create_date();

                $added_data = $FlightDiscountModel->where("id", $id)->set($data)->update();
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

    public function flight_discount_status_change()
    {
        if (permission_access_error("Agent", "flight_discount_status")) {
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
                $data['modified'] = create_date();
                $update = $FlightDiscountModel->status_change($this->web_partner_id, $ids, $data);

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

    public function remove_flight_discount()
    {
        if (permission_access_error("Agent", "delete_flight_discount")) {
            $FlightDiscountModel = new FlightDiscountModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightDiscountModel->remove_discount($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "FlightDiscount Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "FlightDiscount  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
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

    public function flight_discount_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));

        $FlightDiscountModel = new FlightDiscountModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $FlightDiscountModel->discount_details($this->web_partner_id, $id),
        ];
        $details = view('Modules\Agent\Views\flight-discount-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    public function flight_markup(): string
    {

        if (permission_access_error("Agent", "flight_markup_list")) {
            $FlightMarkupModel = new FlightMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightMarkupModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $FlightMarkupModel->markup_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Agent\Views\Flight-markup-list",
                'pager' => $FlightMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function flight_markup_view()
    {
        if (permission_access_error("Agent", "add_flight_markup")) {
            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'agent_class' => $agent_class
            ];
            $add_blog_view = view('Modules\Agent\Views\add-flight-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_flight_markup()
    {
        if (permission_access_error("Agent", "add_flight_markup")) {
            $validate = new Validation();

            $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
            $rules = $this->validate($validate->flight_discount_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightMarkupModel = new FlightMarkupModel();
                $data = $this->request->getPost();
                $temp_airline = $data['airline_code'];
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }
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

    public function edit_flight_markup_view()
    {
        if (permission_access_error("Agent", "edit_flight_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightMarkupModel = new FlightMarkupModel();
            $details = $FlightMarkupModel->markup_details($this->web_partner_id, $id);

            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);

            if (isset($details['travel_date_from']) && $details['travel_date_from'] != '') {
                $details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
            }

            if (isset($details['travel_date_to']) && $details['travel_date_to'] != '') {
                $details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
            }

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class' => $agent_class
            ];
            $blog_details = view('Modules\Agent\Views\edit-flight-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_flight_markup()
    {
        if (permission_access_error("Agent", "edit_flight_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
            $rules = $this->validate($validate->flight_discount_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightMarkupModel = new FlightMarkupModel();
                $data = $this->request->getPost();
                $temp_airline = $this->request->getPost('airline_code');
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }


                $data['modified'] = create_date();

                $added_data = $FlightMarkupModel->where("id", $id)->set($data)->update();
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

    public function flight_markup_status_change()
    {
        if (permission_access_error("Agent", "flight_markup_status")) {
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
                $data['modified'] = create_date();
                $update = $FlightMarkupModel->status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_flight_markup()
    {
        if (permission_access_error("Agent", "delete_flight_markup")) {
            $FlightMarkupModel = new FlightMarkupModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightMarkupModel->remove_flight_markup($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Markup  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function flight_markup_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));

        $FlightMarkupModel = new FlightMarkupModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $FlightMarkupModel->markup_details($this->web_partner_id, $id),
        ];
        $details = view('Modules\Agent\Views\flight-markup-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }



    public function export_agent()
    {
        if (permission_access_error("Agent", "agent_export")) {
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

            $fileName = 'agent.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:U1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A:U")->getFont()->setName('Arial')->setSize(11);
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
            $sheet->getColumnDimension('S')->setAutoSize(true);
            $sheet->getColumnDimension('T')->setAutoSize(true);
            $sheet->getColumnDimension('U')->setAutoSize(true);

            $sheet->setCellValue('A1', 'Sr. No.');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Mobile');
            $sheet->setCellValue('E1', 'Agent Class');
            $sheet->setCellValue('F1', 'Company Name');
            $sheet->setCellValue('G1', 'DOB');
            $sheet->setCellValue('H1', 'Address');
            $sheet->setCellValue('I1', 'City');
            $sheet->setCellValue('J1', 'State');
            $sheet->setCellValue('K1', 'Country');
            $sheet->setCellValue('L1', 'Pin Code');
            $sheet->setCellValue('M1', 'status');
            $sheet->setCellValue('N1', 'Balance');
            $sheet->setCellValue('O1', 'Credit Limit');
            $sheet->setCellValue('P1', 'GST Holder Name');
            $sheet->setCellValue('Q1', 'GST Number');
            $sheet->setCellValue('R1', 'PAN Holder Name');
            $sheet->setCellValue('S1', 'Pan Number');
            $sheet->setCellValue('T1', 'modified');
            $sheet->setCellValue('U1', 'Created Date');

            $rows = 2;
            $AgentModel = new AgentModel();
            $agent_excel = $AgentModel->agent_export($this->web_partner_id, $this->request->getPost());


            foreach ($agent_excel as $key => $val) {

                if ($val['modified']) {
                    $modified = date_created_format($val['modified']);
                } else {
                    $modified = '';
                }

                if ($val['dob']) {
                    $dob = timestamp_to_date($val['dob']);
                } else {
                    $dob = '';
                }

                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['title'] . ' ' . $val['first_name'] . ' ' . $val['last_name']);
                $sheet->setCellValue('C' . $rows, $val['email_id']);
                $sheet->setCellValue('D' . $rows, $val['mobile_number']);
                $sheet->setCellValue('E' . $rows, $val['class_name']);
                $sheet->setCellValue('F' . $rows, $val['company_name']);
                $sheet->setCellValue('G' . $rows, $dob);
                $sheet->setCellValue('H' . $rows, $val['address']);
                $sheet->setCellValue('I' . $rows, $val['city']);
                $sheet->setCellValue('J' . $rows, $val['state']);
                $sheet->setCellValue('K' . $rows, $val['country']);
                $sheet->setCellValue('L' . $rows, $val['pincode']);
                $sheet->setCellValue('M' . $rows, $val['status']);
                $sheet->setCellValue('N' . $rows, $val['balance']);
                $sheet->setCellValue('O' . $rows, $val['credit_limit']);
                $sheet->setCellValue('P' . $rows, $val['gst_holder_name']);
                $sheet->setCellValue('Q' . $rows, $val['gst_number']);
                $sheet->setCellValue('R' . $rows, $val['pan_holder_name']);
                $sheet->setCellValue('S' . $rows, $val['pan_number']);
                $sheet->setCellValue('T' . $rows, $modified);
                $sheet->setCellValue('U' . $rows, date_created_format($val['created']));
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



    public function bus_markup()
    {

        if (permission_access_error("Agent", "bus_markup_list")) {
            $BusMarkupModel = new BusMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $BusMarkupModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $BusMarkupModel->markup_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Agent\Views\bus-markup-list",
                'pager' => $BusMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }


    public function bus_markup_view()
    {
        if (permission_access_error("Agent", "add_bus_markup")) {
            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'agent_class' => $agent_class
            ];
            $add_blog_view = view('Modules\Agent\Views\add-bus-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_bus_markup()
    {
        if (permission_access_error("Agent", "add_bus_markup")) {
            $validate = new Validation();


            $rules = $this->validate($validate->bus_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusMarkupModel = new BusMarkupModel();
                $data = $this->request->getPost();
                if ($data['min_range'] >= $data['max_range'] ){
                    $errors['max_range'] ="max range should be greater than min range";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }else {

                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $added_data = $BusMarkupModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Bus Markup Successfully Added", "Class" => "success_popup");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Bus Markup not  Added", "Class" => "error_popup");
                    }

                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        }
    }

    public function edit_bus_markup_view()
    {
        if (permission_access_error("Agent", "edit_bus_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $BusMarkupModel = new BusMarkupModel();
            $details = $BusMarkupModel->markup_details($this->web_partner_id, $id);

            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class' => $agent_class
            ];
            $blog_details = view('Modules\Agent\Views\edit-bus-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_bus_markup()
    {
        if (permission_access_error("Agent", "edit_bus_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();


            $rules = $this->validate($validate->bus_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                if ($data['min_range'] >= $data['max_range'] ){
                    $errors['max_range'] ="max range should be greater than min range";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }else {
                    $BusMarkupModel = new BusMarkupModel();
                    $data['modified'] = create_date();

                    $added_data = $BusMarkupModel->where("id", $id)->set($data)->update();
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Bus Markup Successfully Updated", "Class" => "success_popup");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Bus Markup not  Updated", "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        }
    }

    public function bus_markup_status_change()
    {
        if (permission_access_error("Agent", "bus_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusMarkupModel = new BusMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();

                $update = $BusMarkupModel->status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Bus Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bus Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_bus_markup()
    {
        if (permission_access_error("Agent", "delete_bus_markup")) {
            $BusMarkupModel = new BusMarkupModel();
            $ids = $this->request->getPost('checklist');
            $delete = $BusMarkupModel->remove_markup($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Bus Markup Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Bus Markup  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function view_remark()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $AgentAccountLogModel = new AgentAccountLogModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'data' => $AgentAccountLogModel->view_remark_detail($id, $this->web_partner_id,$this->wl_agent_id),
        ];
        $blog_details = view('Modules\Agent\Views\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }





}