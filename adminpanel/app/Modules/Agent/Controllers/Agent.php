<?php

namespace Modules\Agent\Controllers;

use App\Modules\Agent\Models\AgentModel;
use App\Modules\Agent\Models\AgentUsersModel;
use App\Modules\Agent\Models\AgentClassModel;
use App\Modules\Agent\Models\AgentAccountLogModel;
use App\Modules\Agent\Models\AgentCreditLogModel;
use App\Controllers\BaseController;
use Modules\Agent\Config\Validation;
use PhpParser\Node\Expr\PreDec;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Agent extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Agent";
        $this->company_id_prefix = "Agent";

        $admin_cookie_data = admin_cookie_data();
        $this->web_partner_id = $admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->user_id = $admin_cookie_data['admin_user_details']['id'];
        $this->whitelabel_user = $admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $whitelabel_setting_data = $admin_cookie_data['whitelabel_setting_data'];
        $this->logo_folder = "agent/logo";
        $this->aadhar_folder = "agent/aadhar";
        $this->pan_folder = "agent/pan-card";
        $this->profile_folder = "agent/profile-pic";
        $this->gst_folder = "agent/gst";
        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['b2b_business'] != "active") {
            access_denied();
        }
        if (permission_access_error("Agent", "Agent_Module")) {
        }
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
            'UserIp' => $this->request->getIpAddress(),
            'view' => "Agent\Views\agent-list",
            'pager' => $AgentModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }


    public function add_agent_view()
    {
        if (permission_access("Agent", "add_agent")) {
            $AgentClassModel = new AgentClassModel();
            $data['agent_class'] = $AgentClassModel->agent_class_list($this->web_partner_id);
            $add_slider_view = view('Modules\Agent\Views\add-agent', $data);
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_agent()
    {

        if (permission_access("Agent", "add_agent")) {
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
                $validate->agent_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,2048]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $company_logo = $this->request->getFile($field_name_company_logo);
            if ($company_logo->getName() != '') {
                $validate->agent_validation[$field_name_company_logo]['rules'] = "uploaded[$field_name_company_logo]|max_size[$field_name_profile_pic,2048]|mime_in[$field_name_company_logo,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_company_logo]['errors'] = $errors;
            }

            $gst_scan_copy = $this->request->getFile($field_name_gst_scan_copy);
            if ($gst_scan_copy->getName() != '') {
                $validate->agent_validation[$field_name_gst_scan_copy]['rules'] = "uploaded[$field_name_gst_scan_copy]|max_size[$field_name_profile_pic,2048]|mime_in[$field_name_gst_scan_copy,image/jpg,image/jpeg,image/png]";
                $validate->agent_validation[$field_name_gst_scan_copy]['errors'] = $errors;
            }

            $rules = $this->validate($validate->agent_validation);
            if (!$rules) {

                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $AgentModel = new AgentModel();
                $checkRegisterAgent = $AgentModel->checkRegisterAgent($data['email_id'], $this->web_partner_id);
                if ($checkRegisterAgent > 0) {
                    $errors['email_id'] = "The user agent is already registered. ";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }
                $AgentUsersModel = new AgentUsersModel();


                $permissions_agent = file_get_contents(FCPATH . "app/Libraries/permissions_agent.json");
                #use getFile() for single image or getFiles() for multiple image

                $field_name_pan_scan_copy = 'pan_scan_copy';
                $pan_scan_copy = $this->request->getFile($field_name_pan_scan_copy);
                $image_upload = image_upload($pan_scan_copy, $field_name_pan_scan_copy, $this->pan_folder, $resizeDim);

                $field_name_aadhar_scan_copy = 'aadhar_scan_copy';
                $aadhar_scan_copy = $this->request->getFile($field_name_aadhar_scan_copy);
                $image_upload_aadhar = image_upload($aadhar_scan_copy, $field_name_aadhar_scan_copy, $this->aadhar_folder, $resizeDim);
                if ($image_upload['status_code'] == 0 && $image_upload_aadhar['status_code'] == 0) {

                    /* optional file upload */

                    if ($profile_pic->getName() != '') {
                        $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->profile_folder, $resizeDim);
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
                        $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->logo_folder, $resizeDim);
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
                        $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->gst_folder, $resizeDim);
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
                    $data[$field_name_aadhar_scan_copy] = $image_upload_aadhar['file_name'];
                    $data[$field_name_profile_pic] = $profile_pic;
                    $data[$field_name_company_logo] = $company_logo;
                    $data[$field_name_gst_scan_copy] = $gst_scan_copy;

                    $agent_list = array(
                        'web_partner_id' => $data['web_partner_id'],
                        'agent_class' => $data['agent_class'],
                        'company_name' => $data['company_name'],
                        'company_logo' => $data['company_logo'],
                        'gst_holder_name' => $data['gst_holder_name'],
                        'gst_number' => $data['gst_number'],
                        'gst_scan_copy' => $data['gst_scan_copy'],
                        'pan_holder_name' => $data['pan_holder_name'],
                        'pan_number' => $data['pan_number'],
                        'pan_scan_copy' => $data['pan_scan_copy'],
                        'aadhaar_no' => $data['aadhaar_no'],
                        'aadhar_scan_copy' => $data['aadhar_scan_copy'],
                        'user_access' => $permissions_agent,
                        'address' => $data['address'],
                        'country' => $data['country'],
                        'state' => $data['state'],
                        'city' => $data['city'],
                        'pincode' => $data['pincode'],
                        'status' => $data['status'],
                        'created' => $data['created']
                    );
                    if (!empty($data['gst_number'])) {
                        $agent_list['gst_state_code'] = substr($data['gst_number'], 0, 2);
                    }
                    $agent_user = array(
                        'web_partner_id' => $data['web_partner_id'],
                        'login_email' => $data['email_id'],
                        'profile_pic' => $data['profile_pic'],
                        'password' => $data['password'],
                        'title' => $data['title'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'mobile_no' => $data['mobile_number'],
                        'date_of_birth' => $data['dob'],
                        'street' => $data['address'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'country' => $data['country'],
                        'pin_code' => $data['pincode'],
                        'access_permission' => $permissions_agent,
                        'status' => $data['status'],
                        'primary_user' => 1,
                        'created_date' => $data['created']
                    );

                    $added_data = $AgentModel->insert($agent_list);
                    $update_agent_data['company_id'] = $this->company_id_prefix . $added_data;
                    $AgentModel->where('id', $added_data)->set($update_agent_data)->update();
                    $agent_user['agent_id'] = $added_data;
                    $added_users = $AgentUsersModel->insert($agent_user);
                    $log['wl_agent_id'] = $added_data;
                    $log['web_partner_id'] = $this->web_partner_id;
                    $log['created'] = create_date();
                    $log['action_type'] = 'credit';
                    $data['credit'] = 0;
                    $log['balance'] = 0;
                    $log['remark'] = 'Account created by admin';
                    $AgentAccountLogModel = new AgentAccountLogModel();
                    $account_log_added = $AgentAccountLogModel->insert($log);

                    if ($added_data) {
                        $email = [
                            "email" => $this->request->getPost('email_id'),
                            "mobile_no" => $this->request->getPost('mobile_number'),
                            "password" => $this->request->getPost('password'),
                            "message" => "Thanks for signing up! We’re so glad you decided to be part of the family."
                        ];
                        $subject = "Congratulations! Your account has been created successfully.";
                        $message = view('Views/emails/common-registration-email', $email);

                        $email_type = 'Your Account Has Been Created';
                        send_email($email['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);

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
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_agent_view()
    {
        if (permission_access("Agent", "edit_agent")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $AgentClassModel = new AgentClassModel();
            $agent_class = $AgentClassModel->agent_class_list($this->web_partner_id);
            $AgentModel = new AgentModel();
            $details = $AgentModel->agent_list_details($id, $this->web_partner_id);
            unset($details['id']);

            if (isset($details['date_of_birth']) && $details['date_of_birth'] != '') {
                $details['dob'] = timestamp_to_date($details['date_of_birth']);
            } else {
                $details['dob'] = '';
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
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_agent()
    {
        if (permission_access("Agent", "edit_agent")) {
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
            $field_name_aadhar_scan_copy = 'aadhar_scan_copy';

            $pan_scan_copy = $this->request->getFile($field_name_pan_scan_copy);
            if ($pan_scan_copy->getName() == '') {
                unset($validate->agent_validation[$field_name_pan_scan_copy]);
            }

            $aadhar_scan_copy = $this->request->getFile($field_name_aadhar_scan_copy);
            if ($aadhar_scan_copy->getName() == '') {
                unset($validate->agent_validation[$field_name_aadhar_scan_copy]);
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

            $validate->agent_validation['email_id']['rules'] = "required|valid_email";
            $rules = $this->validate($validate->agent_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $data = $this->request->getPost();


                $AgentUsersModel = new AgentUsersModel();
                $previous_data = $AgentModel->agent_list_details($id, $this->web_partner_id);
                $previous_user_data = $AgentUsersModel->agent_list_details($id, $this->web_partner_id);


                if ($previous_user_data['login_email'] != $data['email_id']) {
                    $value = $data['email_id'];
                    $existingValue = $AgentUsersModel->CheckUniqueSlug($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["email_id" => "This email already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }

                /* optional file upload */

                if ($profile_pic->getName() != '') {
                    $profile_pic_upload = image_upload($profile_pic, $field_name_profile_pic, $this->profile_folder, $resizeDim);
                    if ($profile_pic_upload['status_code'] == 0) {
                        $profile_pic = $profile_pic_upload['file_name'];

                        if ($previous_user_data[$field_name_profile_pic]) {
                            if (file_exists(FCPATH . "../uploads/$this->profile_folder/" . $previous_user_data[$field_name_profile_pic])) {
                                unlink(FCPATH . "../uploads/$this->profile_folder/" . $previous_user_data[$field_name_profile_pic]);
                                unlink(FCPATH . "../uploads/$this->profile_folder/thumbnail/" . $previous_user_data[$field_name_profile_pic]);
                            }
                        }
                    } else {
                        $profile_pic = null;
                        $message = array("StatusCode" => 2, "Message" => $profile_pic_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $profile_pic = $previous_user_data[$field_name_profile_pic];
                }

                if ($company_logo->getName() != '') {
                    $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->logo_folder, $resizeDim);
                    if ($company_logo_upload['status_code'] == 0) {
                        $company_logo = $company_logo_upload['file_name'];

                        if ($previous_data[$field_name_company_logo]) {
                            if (file_exists(FCPATH . "../uploads/$this->logo_folder/" . $previous_data[$field_name_company_logo])) {
                                unlink(FCPATH . "../uploads/$this->logo_folder/" . $previous_data[$field_name_company_logo]);
                                unlink(FCPATH . "../uploads/$this->logo_folder/thumbnail/" . $previous_data[$field_name_company_logo]);
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
                    $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->gst_folder, $resizeDim);
                    if ($gst_scan_copy_upload['status_code'] == 0) {
                        $gst_scan_copy = $gst_scan_copy_upload['file_name'];
                        if ($previous_data[$field_name_gst_scan_copy]) {
                            if (file_exists(FCPATH . "../uploads/$this->gst_folder/" . $previous_data[$field_name_gst_scan_copy])) {
                                unlink(FCPATH . "../uploads/$this->gst_folder/" . $previous_data[$field_name_gst_scan_copy]);
                                unlink(FCPATH . "../uploads/$this->gst_folder/thumbnail/" . $previous_data[$field_name_gst_scan_copy]);
                            }
                        }
                    } else {
                        $gst_scan_copy = null;
                        $message = array("StatusCode" => 2, "Message" => $gst_scan_copy_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $gst_scan_copy = $previous_data[$field_name_gst_scan_copy];
                    ;
                }

                $data[$field_name_profile_pic] = $profile_pic;
                $data[$field_name_company_logo] = $company_logo;
                $data[$field_name_gst_scan_copy] = $gst_scan_copy;
                /*end  optional file upload */

                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                if ($aadhar_scan_copy->getName() != '') {
                    $image_upload_aadhar = image_upload($aadhar_scan_copy, $field_name_aadhar_scan_copy, $this->aadhar_folder, $resizeDim);
                    if ($image_upload_aadhar['status_code'] == 0) {

                        if ($previous_data[$field_name_aadhar_scan_copy]) {
                            if (file_exists(FCPATH . "../uploads/$this->aadhar_folder/" . $previous_data[$field_name_aadhar_scan_copy])) {
                                unlink(FCPATH . "../uploads/$this->aadhar_folder/" . $previous_data[$field_name_aadhar_scan_copy]);
                                unlink(FCPATH . "../uploads/$this->aadhar_folder/thumbnail/" . $previous_data[$field_name_aadhar_scan_copy]);
                            }
                        }
                        $data[$field_name_aadhar_scan_copy] = $image_upload_aadhar['file_name'];
                    } else {
                        $aadhar_scan_copy = null;
                        $message = array("StatusCode" => 2, "Message" => $image_upload_aadhar['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $data[$field_name_aadhar_scan_copy] = $previous_data[$field_name_aadhar_scan_copy];
                }
                if ($pan_scan_copy->getName() != '') {

                    $image_upload = image_upload($pan_scan_copy, $field_name_pan_scan_copy, $this->pan_folder, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name_pan_scan_copy]) {
                            if (file_exists(FCPATH . "../uploads/$this->pan_folder/" . $previous_data[$field_name_pan_scan_copy])) {
                                unlink(FCPATH . "../uploads/$this->pan_folder/" . $previous_data[$field_name_pan_scan_copy]);
                                unlink(FCPATH . "../uploads/$this->pan_folder/thumbnail/" . $previous_data[$field_name_pan_scan_copy]);
                            }
                        }
                        $data[$field_name_pan_scan_copy] = $image_upload['file_name'];
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $data[$field_name_pan_scan_copy] = $previous_data[$field_name_pan_scan_copy];
                }
                $data['modified'] = create_date();
                $agent_list = array(
                    'agent_class' => $data['agent_class'],
                    'company_name' => $data['company_name'],
                    'company_logo' => $data['company_logo'],
                    'gst_holder_name' => $data['gst_holder_name'],
                    'gst_number' => $data['gst_number'],
                    'gst_scan_copy' => $data['gst_scan_copy'],
                    'pan_holder_name' => $data['pan_holder_name'],
                    'pan_number' => $data['pan_number'],
                    'pan_scan_copy' => $data['pan_scan_copy'],
                    'aadhaar_no' => $data['aadhaar_no'],
                    'aadhar_scan_copy' => $data['aadhar_scan_copy'],
                    'address' => $data['address'],
                    'country' => $data['country'],
                    'state' => $data['state'],
                    'city' => $data['city'],
                    'pincode' => $data['pincode'],
                    'status' => $data['status'],
                    'modified' => $data['modified']
                );
                if (!empty($data['gst_number'])) {
                    $agent_list['gst_state_code'] = substr($data['gst_number'], 0, 2);
                }
                $agent_user = array(
                    'profile_pic' => $data['profile_pic'],
                    'title' => $data['title'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'mobile_no' => $data['mobile_number'],
                    'date_of_birth' => $data['dob'],
                    'street' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'pin_code' => $data['pincode'],
                    'status' => $data['status'],
                    'modified' => $data['modified'],
                    'login_email' => $data['email_id']
                );

                $added_data = $AgentModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($agent_list)->update();

                $added_users_data = $AgentUsersModel->where(['agent_id' => $id, "web_partner_id" => $this->web_partner_id])->where('primary_user', 1)->set($agent_user)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Agent Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Agent not  Updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function agent_details()
    {
        $agent_id = dev_decode($this->request->uri->getSegment(3));
        $AgentModel = new AgentModel();
        $details = $AgentModel->agent_view_details($agent_id, $this->web_partner_id);
        $data = [
            'title' => $this->title,
            'id' => $agent_id,
            'details' => $details
        ];
        $blog_details = view('Modules\Agent\Views\agent-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    public function remove_agent()
    {
        if (permission_access("Agent", "delete_agent")) {
            $AgentModel = new AgentModel();
            $AgentUsersModel = new AgentUsersModel();
            $ids = $this->request->getPost('checklist');

            $field_name_profile_pic = "profile_pic";
            $field_name_company_logo = "company_logo";
            $field_name_gst_scan_copy = "gst_scan_copy";
            $field_name_pan_scan_copy = 'pan_scan_copy';

            foreach ($ids as $id) {

                $details = $AgentModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name_profile_pic]) {
                    if (file_exists(FCPATH . "../uploads/$this->profile_folder/" . $details[$field_name_profile_pic])) {
                        unlink(FCPATH . "../uploads/$this->profile_folder/" . $details[$field_name_profile_pic]);
                        unlink(FCPATH . "../uploads/$this->profile_folder/thumbnail/" . $details[$field_name_profile_pic]);
                    }
                }

                if ($details[$field_name_company_logo]) {
                    if (file_exists(FCPATH . "../uploads/$this->logo_folder/" . $details[$field_name_company_logo])) {
                        unlink(FCPATH . "../uploads/$this->logo_folder/" . $details[$field_name_company_logo]);
                        unlink(FCPATH . "../uploads/$this->logo_folder/thumbnail/" . $details[$field_name_company_logo]);
                    }
                }

                if ($details[$field_name_gst_scan_copy]) {
                    if (file_exists(FCPATH . "../uploads/$this->gst_folder/" . $details[$field_name_gst_scan_copy])) {
                        unlink(FCPATH . "../uploads/$this->gst_folder/" . $details[$field_name_gst_scan_copy]);
                        unlink(FCPATH . "../uploads/$this->gst_folder/thumbnail/" . $details[$field_name_gst_scan_copy]);
                    }
                }

                if ($details[$field_name_pan_scan_copy]) {
                    if (file_exists(FCPATH . "../uploads/$this->pan_folder/" . $details[$field_name_pan_scan_copy])) {
                        unlink(FCPATH . "../uploads/$this->pan_folder/" . $details[$field_name_pan_scan_copy]);
                        unlink(FCPATH . "../uploads/$this->pan_folder/thumbnail/" . $details[$field_name_pan_scan_copy]);
                    }
                }

                $delete = $AgentModel->remove_agent($id, $this->web_partner_id);

                $delete_users = $AgentUsersModel->remove_agent_users($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Agent  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Agent  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }






    public function change_agent_password()
    {

        if (permission_access("Agent", "change_password")) {

            $validate = new Validation();
            $rules = $this->validate($validate->agent_password_change);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentUsersModel = new AgentUsersModel();

                $send_email = $this->request->getPost('send_email'); //send email pending
                $id = dev_decode($this->request->getPost('agent_id'));

                $primary_user_data = $AgentUsersModel->agent_list_details($id, $this->web_partner_id);
                $EmailId = $primary_user_data['login_email'];
                $data['password'] = md5($this->request->getPost('password'));

                $send_status = 0;
                if (isset($send_email) && $send_email) {
                    if ($send_email == 'send') {
                        $send_status = 1;
                        unset($send_email);
                    }
                }
                $update = $AgentUsersModel->where("web_partner_id", $this->web_partner_id)->where("id", $primary_user_data['id'])->set($data)->update();
                if ($update) {

                    if ($send_status == 1) {

                        $PasswordReset = [
                            "email" => $EmailId,
                            "password" => $this->request->getPost('password'),
                            "passwordMessage" => "Congratulations! Your password has been reset successfully."
                        ];

                        $subject = "Congratulations! Your password has been reset successfully.";
                        $message = view('Views/emails/common-registration-email', $PasswordReset);
                        $email_type = 'Password Reset';

                        send_email($PasswordReset['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);
                    }

                    $message = array("StatusCode" => 0, "Message" => "Password  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Password not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function agent_status_change()
    {

        if (permission_access("Agent", "agent_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AgentModel = new AgentModel();
                $AgentUsersModel = new AgentUsersModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $AgentModel->agent_status_change($this->web_partner_id, $ids, $data);
                $update_user = $AgentUsersModel->agent_users_status_change($this->web_partner_id, $ids, $data);
                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Agent status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Agent status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function agent_class()
    {
        if (permission_access("Agent", "agent_class_list")) {
            $AgentClassModel = new AgentClassModel();
            $data = [
                'title' => $this->title,
                'list' => $AgentClassModel->agent_class_list($this->web_partner_id),
                'pager' => $AgentClassModel->pager
            ];
            $agent_class = view('Modules\Agent\Views\agent-class', $data);
            $data = array("StatusCode" => 0, "Message" => $agent_class, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_agent_class()
    {
        if (permission_access("Agent", "add_agent_class")) {
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
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function edit_agent_class()
    {
        if (permission_access("Agent", "edit_agent_class")) {
            $validate = new Validation();
            $rules = $this->validate($validate->agent_class_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $id = dev_decode($this->request->uri->getSegment(3));
                $AgentClassModel = new AgentClassModel();
                $data = $this->request->getPost();
                $data['class_name'] = ucwords($data['class_name']);
                $data['modified'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $update_data = $AgentClassModel->update_agent_class($data, $this->web_partner_id, $id);
                if ($update_data) {
                    $message = array("StatusCode" => 0, "Message" => "Agent Class Successfully Update", "Class" => "success_popup", "Reload" => "false");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Agent Class not  Update", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function agent_account_logs()
    {
        if (permission_access_error("Agent", "account_logs")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentModel = new AgentModel();
            $AgentAccountLogModel = new AgentAccountLogModel();

            $agentAcountBalance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);
            $agentBalance = isset($agentAcountBalance['balance']) ? $agentAcountBalance['balance'] : 0;
            $Agentdetails = $AgentModel->agent_view_details($agent_id, $this->web_partner_id);
            $Agentdetails['balance'] = $agentBalance;

            $searchData = array();
            if (isset($_GET['key'])) {

                $searchData = $this->request->getGet();
                $account_logs = $AgentAccountLogModel->account_logs($agent_id, $this->web_partner_id, $searchData);
                $pager = $AgentAccountLogModel->pager;
            } else {
                $account_logs = $AgentAccountLogModel->account_logs($agent_id, $this->web_partner_id);
                $pager = $AgentAccountLogModel->pager;
            }
            if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
                Agent::export_agent_account_logs($account_logs);
            }

            $data = [
                'title' => $this->title,
                'account_logs' => $account_logs,
                'search_bar_data' => $searchData,
                'details' => $Agentdetails,
                'view' => "Agent\Views\agent-account-logs-list",
                'pager' => $pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function getAgent()
    {
        $terms = trim($this->request->getGet('term'));
        $AgentModel = new AgentModel();
        $AgentAccountLogModel = new AgentAccountLogModel();
        $agentInfo = $AgentModel->agentinfo($terms, $this->web_partner_id);
        $availableAgentInfo = [];
        if (!empty($agentInfo)) {
            foreach ($agentInfo as $info) {
                $agentAcountBalance = $AgentAccountLogModel->available_balance($info['id'], $this->web_partner_id);
                $agentBalance = isset($agentAcountBalance['balance']) ? $agentAcountBalance['balance'] : 0;
                $availableAgentInfo[] = ['id' => $info['id'], 'agent_name' => $info['first_name'] . ' ' . $info['last_name'], 'label' => $info['first_name'] . ' ' . $info['last_name'], 'pan_name' => $info['pan_holder_name'], 'email_id' => $info['login_email'], 'mobile_number' => $info['mobile_no'], 'balance' => $agentBalance];
            }
        }
        echo json_encode($availableAgentInfo);
    }

    public function virtual_topup_view()
    {
        if (permission_access("Agent", "virtual_topup")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentAccountLogModel = new AgentAccountLogModel();
            $AgentUsersModel = new AgentUsersModel();
            $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);

            $data['details'] = $AgentUsersModel->agent_list_details($agent_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }
            $view = view('Modules\Agent\Views\topup-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_topup()
    {
        if (permission_access("Agent", "virtual_topup")) {

            $validate = new Validation();
            $rules = $this->validate($validate->virtual_credit);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $wl_agent_id = dev_decode($this->request->uri->getSegment(3));
                $AgentAccountLogModel = new AgentAccountLogModel();
                $available_balance = $AgentAccountLogModel->available_balance($wl_agent_id, $this->web_partner_id);

                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['wl_agent_id'] = $wl_agent_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "credit";
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['credit'] = $Postdata['credit'];
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $AgentAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id);
                    if (isset($data['booking_ref_no']['id']) && $data['booking_ref_no']['id'] != '') {
                        $data['booking_ref_no'] = $data['booking_ref_no']['id'];
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Unable to update account logs please booking refrence number", "Class" => "error_popup", "Reload" => "true");
                        unset($data['booking_ref_no']);
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }

                }
                $data['balance'] = $available_balance['balance'] + $Postdata['credit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);
                $added_data_id = $AgentAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data_id);
                $AgentAccountLogModel->where(["id" => $added_data_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit_view()
    {
        if (permission_access("Agent", "virtual_deduct")) {
            $agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentAccountLogModel = new AgentAccountLogModel();
            $AgentUsersModel = new AgentUsersModel();

            $available_balance = $AgentAccountLogModel->available_balance($agent_id, $this->web_partner_id);

            $data['details'] = $AgentUsersModel->agent_list_details($agent_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Agent\Views\debit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit()
    {
        if (permission_access("Agent", "virtual_deduct")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_debit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $wl_agent_id = dev_decode($this->request->uri->getSegment(3));

                $AgentAccountLogModel = new AgentAccountLogModel();
                $available_balance = $AgentAccountLogModel->available_balance($wl_agent_id, $this->web_partner_id);
                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['wl_agent_id'] = $wl_agent_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "debit";
                $data['debit'] = $Postdata['debit'];
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $AgentAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id);
                    if (isset($data['booking_ref_no']['id']) && $data['booking_ref_no']['id'] != '') {
                        $data['booking_ref_no'] = $data['booking_ref_no']['id'];
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Unable to update account logs please booking refrence number", "Class" => "error_popup", "Reload" => "true");
                        unset($data['booking_ref_no']);
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }

                }
                $data['balance'] = $available_balance['balance'] - $Postdata['debit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);
                $added_data_id = $AgentAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data_id);
                $AgentAccountLogModel->where(["id" => $added_data_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function credit_limit_view()
    {
        if (permission_access("Agent", "credit_limit")) {
            $wl_agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentCreditLogModel = new AgentCreditLogModel();
            $available_balance = $AgentCreditLogModel->available_credit_details($wl_agent_id, $this->web_partner_id);
            $AgentModel = new AgentModel();
            $data['details'] = $AgentModel->agent_list_details($wl_agent_id, $this->web_partner_id);
            if (isset($available_balance['credit_limit'])) {
                $data['details']['balance'] = $available_balance['credit_limit'];
                $data['details']['credit_expire_date'] = date("d-m-Y", $available_balance['credit_expire_date']);
                $data['details']['credit_expire'] = $available_balance['credit_expire'];
            }
            $view = view('Modules\Agent\Views\credit-limit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_creditlimit()
    {
        if (permission_access("Agent", "credit_limit")) {
            $data = $this->request->getPost();
            $filed_name = "credit_expire_date";
            $validate = new Validation();
            if ($data['credit_expire'] == "No") {
                unset($validate->virtual_creditlimt[$filed_name]);
            }
            $rules = $this->validate($validate->virtual_creditlimt);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                
                $wl_agent_id = dev_decode($this->request->uri->getSegment(3));
                $AgentCreditLogModel = new AgentCreditLogModel();
                $data = $this->request->getPost();
                $data['wl_agent_id'] = $wl_agent_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['credit_expire_date'] = strtotime(date("Y-m-d", strtotime($data['credit_expire_date'])));
                $data['remark'] = $data['remark'];
                $data['created'] = create_date();
                $added_data_id = $AgentCreditLogModel->insert($data);
                $update['credit_expire'] = "Yes";
                $AgentCreditLogModel->where(["id !=" => $added_data_id, 'wl_agent_id' => $wl_agent_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Credit Limit successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update credit limit", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function agent_account_credit_logs()
    {
        if (permission_access("Agent", "credit_account_logs")) {
            $wl_agent_id = dev_decode($this->request->uri->getSegment(3));
            $AgentCreditLogModel = new AgentCreditLogModel();
            $AgentModel = new AgentModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $account_logs = $AgentCreditLogModel->search_result_data($this->request->getGet(), $wl_agent_id, $this->web_partner_id);
            } else {
                $account_logs = $AgentCreditLogModel->account_credit_logs($wl_agent_id, $this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'account_logs' => $account_logs,
                'details' => $AgentModel->agent_list_details($wl_agent_id, $this->web_partner_id),
                'view' => "Agent\Views\agent-account-credit-logs-list",
                'pager' => $AgentCreditLogModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function export_agent()
    {
        if (permission_access("Agent", "agent_export")) {
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

                if ($val['modified'] && $val['modified'] != null) {
                    $modified = date_created_format(intval($val['modified']));
                } else {
                    $modified = '';
                }

                if ($val['date_of_birth']) {
                    $dob = timestamp_to_date(intval($val['date_of_birth']));
                } else {
                    $dob = '';
                }

                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['title'] . ' ' . $val['first_name'] . ' ' . $val['last_name']);
                $sheet->setCellValue('C' . $rows, $val['login_email']);
                $sheet->setCellValue('D' . $rows, $val['mobile_no']);
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
                $sheet->setCellValue('U' . $rows, date_created_format(intval($val['created'])));
                $rows++;
            }
            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
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
            'data' => $AgentAccountLogModel->view_remark_detail($id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\Agent\Views\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function accountUpdateLogRemark()
    {
        if (permission_access("Agent", "update_remark")) {
            $validate = new Validation();
            $rules = $this->validate($validate->accountUpdateLogRemark);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $wl_agent_data = dev_decode($this->request->uri->getSegment(3));
                $added_data_id = explode("-", $wl_agent_data)[0];
                $wl_agent_id = explode("-", $wl_agent_data)[1];
                $AgentAccountLogModel = new AgentAccountLogModel();
                $data = array();
                $Postdata = $this->request->getPost();
                if ($Postdata['action_type'] == "booking") {
                    $data['invoice_number'] = $Postdata['invoice_number'];
                }

                $data['user_id'] = $this->user_id;
                $data['modified'] = create_date();
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                $AgentAccountLogModel->where(["id" => $added_data_id, 'web_partner_id' => $this->web_partner_id, 'wl_agent_id' => $wl_agent_id])->set($data)->update();

                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account remark successfully updated", "Class" => "success_popup", "Reload" => "false");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account remark", "Class" => "error_popup", "Reload" => "false");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }

        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    // public function export_agent_account_logs($account_logs)
    // {
    //     if (permission_access_error("Agent", "export_account_logs")) {
    //         $fileName = 'agent-Account-Logs' . "." . 'xlsx';
    //         $spreadsheet = new Spreadsheet();
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $sheet->getStyle("A1:K1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
    //         $sheet->getStyle("A:K")->getFont()->setName('Arial')->setSize(11);
    //         $sheet->getColumnDimension('A')->setAutoSize(true);
    //         $sheet->getColumnDimension('B')->setAutoSize(true);
    //         $sheet->getColumnDimension('C')->setAutoSize(true);
    //         $sheet->getColumnDimension('D')->setAutoSize(true);
    //         $sheet->getColumnDimension('E')->setAutoSize(true);
    //         $sheet->getColumnDimension('F')->setAutoSize(true);
    //         $sheet->getColumnDimension('G')->setAutoSize(true);
    //         $sheet->getColumnDimension('H')->setAutoSize(true);
    //         $sheet->getColumnDimension('I')->setAutoSize(true);
    //         $sheet->getColumnDimension('J')->setAutoSize(true);
    //         $sheet->getColumnDimension('K')->setAutoSize(true);


    //         $sheet->setCellValue('A1', 'Sr.No.');
    //         $sheet->setCellValue('B1', 'Company');
    //         $sheet->setCellValue('C1', 'Invoice No.');
    //         $sheet->setCellValue('D1', 'Credit Note No.');
    //         $sheet->setCellValue('E1', 'Debit');
    //         $sheet->setCellValue('F1', 'Credit');
    //         $sheet->setCellValue('G1', 'Balance');
    //         $sheet->setCellValue('H1', 'Date');
    //         $sheet->setCellValue('I1', 'Payment Type');
    //         $sheet->setCellValue('J1', 'Staff Name');
    //         $sheet->setCellValue('K1', 'Remark');
    //         $rows = 2;

    //         foreach ($account_logs as $key => $val) {
    //             $PaymentType = "";
    //             $transaction_id = '';
    //             if (isset($val['transaction_id']) && $val['transaction_id'] != '') {
    //                 $PaymentType = $PaymentType . 'Transaction Id -' . $val['transaction_id'] . " ";
    //             }

    //             if (isset($val['payment_mode']) && $val['payment_mode'] != '') {
    //                 $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . $val['payment_mode'];
    //             } else {
    //                 $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . 'Wallet';
    //             }

    //             if ($val['action_type'] == 'refund') {
    //                 $creditNoteNumber = "-";
    //                 $invoiceNumber = "-";
    //                 $creditNoteNumber = $val['invoice_number'];
    //             } else {
    //                 $creditNoteNumber = "-";
    //                 $invoiceNumber = "-";
    //                 $invoiceNumber = $val['invoice_number'];
    //             }
    //             $sheet->setCellValue('A' . $rows, $key + 1);
    //             $sheet->setCellValue('B' . $rows, $val['company_name']);
    //             $sheet->setCellValue('C' . $rows, $invoiceNumber);
    //             $sheet->setCellValue('D' . $rows, $creditNoteNumber);
    //             $sheet->setCellValue('E' . $rows, $val['debit']);
    //             $sheet->setCellValue('F' . $rows, $val['credit']);
    //             $sheet->setCellValue('G' . $rows, $val['balance']);
    //             $sheet->setCellValue('H' . $rows, $val['created']);
    //             $sheet->setCellValue('I' . $rows, $PaymentType);
    //             $sheet->setCellValue('J' . $rows, $val['web_partner_staff_name']);
    //             $sheet->setCellValue('K' . $rows, $val['remark']);
    //             $rows++;
    //         }

    //         ob_start();
    //         $writer = new Xlsx($spreadsheet);
    //         header("Content-Type: application/vnd.ms-excel");
    //         header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
    //         header('Expires: 0');
    //         $writer->save("php://output");
    //         ob_end_clean();
    //         exit;

    //     }
    // }


    public function export_agent_account_logs($account_logs)
    {
        if (permission_access_error("Agent", "export_account_logs")) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = [
                'Sr.No.',
                'Company',
                'Invoice No.',
                'Credit Note No.',
                'Debit',
                'Credit',
                'Balance',
                'Date',
                'Payment Type',
                'Staff Name',
                'Remark'
            ];
            $sheet->fromArray($headers, NULL, 'A1');
            $headerStyleArray = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF000000'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $sheet->getStyle('A1:K1')->applyFromArray($headerStyleArray);

            $columnWidths = [
                'A' => 15,
                'B' => 20,
                'C' => 20,
                'D' => 15,
                'E' => 15,
                'F' => 12,
                'G' => 12,
                'H' => 12,
                'I' => 20,
                'J' => 15,
                'K' => 20
            ];

            foreach ($columnWidths as $columnID => $width) {
                $sheet->getColumnDimension($columnID)->setWidth($width);
            }
            $rowNumber = 2;
            foreach ($account_logs as $key => $val) {
                $PaymentType = "";
                if (!empty($val['transaction_id'])) {
                    $PaymentType .= 'Transaction Id -' . $val['transaction_id'] . " ";
                }

                $PaymentType .= ucfirst($val['action_type']) . ' - ' . (!empty($val['payment_mode']) ? $val['payment_mode'] : 'Wallet');

                if ($val['action_type'] == 'refund') {
                    $creditNoteNumber = $val['invoice_number'];
                    $invoiceNumber = "-";
                } else {
                    $invoiceNumber = $val['invoice_number'];
                    $creditNoteNumber = "-";
                }

                $baseData = [
                    $key + 1,
                    $val['company_name'] ?? "",
                    $invoiceNumber,
                    $creditNoteNumber,
                    $val['debit'] ?? "",
                    $val['credit'] ?? "",
                    $val['balance'] ?? "",
                    $val['created'] ?? "",
                    $PaymentType,
                    $val['web_partner_staff_name'] ?? "",
                    $val['remark'] ?? ""
                ];

                $sheet->fromArray($baseData, NULL, 'A' . $rowNumber);
                $dataStyleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle('A' . $rowNumber . ':K' . $rowNumber)->applyFromArray($dataStyleArray);
                $rowNumber++;
            }

            $fileName = 'agent-Account-Logs' . date('YmdHis') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }


    public function getAgent_debit_logs()
    {
        $account_logs = array();
        $webpartnerDetail = array();
        $balance = 0;
        $pager = array();
        $searchData = $this->request->getGet();
        if ($this->request->getGet() && $this->request->getGet('from_date') && $this->request->getGet('to_date')) {

            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }

            $account_logs = $WebPartnerAccountLogModel->debit_account_logs($web_partner_id, $searchData);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        } else {
            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }
            $account_logs = $WebPartnerAccountLogModel->debit_account_logs_without_serach($web_partner_id);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        }

        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $webpartnerDetail,
            'view' => "Agent\Views\web-partner-debit-logs-list",
            'pager' => $pager,
            'available_balance' => $balance,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }
}