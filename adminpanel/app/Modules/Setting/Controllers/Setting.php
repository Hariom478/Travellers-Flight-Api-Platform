<?php

namespace Modules\Setting\Controllers;

use App\Modules\Setting\Models\SettingModel;
use App\Modules\Setting\Models\WhitelabelWebpartnerSettingModel;
use App\Controllers\BaseController;
use Modules\Setting\Config\Validation; // Import Validation

class Setting extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Web Setting";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->logo_folder = "logo";
        $this->faviocn_folder = "favicon";
        if (permission_access_error("Setting", "Setting_Module")) {
        }
    }

    public function index()
    {
        $profile_details = admin_cookie_data()['admin_user_details'];
        $company_details = admin_cookie_data()['admin_comapny_detail'];
        $settingmodel = new SettingModel();
        //$whitelable_setting = $settingmodel->whitelabel_webpartner_email_setting($this->web_partner_id);
        $whitelable_setting = admin_cookie_data()['whitelabel_setting_data'];

        if (isset($whitelable_setting['email_setting']) and $whitelable_setting['email_setting'] != "") {
            $company_details['email_setting'] = $whitelable_setting['email_setting'];
        }

        if (isset($whitelable_setting['copyright']) and $whitelable_setting['copyright'] != "") {
            $company_details['copyright'] = $whitelable_setting['copyright'];
        }
        if (isset($whitelable_setting['google_analytics']) and $whitelable_setting['google_analytics'] != "") {
            $company_details['google_analytics'] = $whitelable_setting['google_analytics'];
        }
        $gateway_type['gateway_type'] = $whitelable_setting['payment_gateway_name'];
        $gateway_type['payment_gateway_setting'] = json_decode($whitelable_setting['payment_gateway_setting'], true);
        $data_html = view('Modules\Setting\Views\payment-gateway', $gateway_type);




        $data = [
            'title' => $this->title,
            'view' => "Setting\Views\index",
            'profile_details' => $profile_details,
            'data_html' => $data_html,
            'company_details' => $company_details,
            'whitelable_setting' => $whitelable_setting,
            'payment_gateway_name' => $gateway_type
        ];
        return view('template/sidebar-layout', $data);
    }

    public function update_company_setting()
    {
        if (permission_access("Setting", "edit_company_setting")) {
            if ($this->request->getMethod() == 'post') {
                $resizeDim = array('width' => 150, 'height' => 80);
                $errors = [
                    'max_size' => 'Image size should not be more than 1024kb',
                    'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
                ];
                $validate = new Validation();
                $field_name_company_logo = "company_logo";
                $field_name_company_favicon = "company_favicon";
                $company_logo = $this->request->getFile($field_name_company_logo);
                $company_favicon = $this->request->getFile($field_name_company_favicon);
                $data = $this->request->getPost();
                // $whatsapp_no = $data['whatsapp_no'];

                // if ($whatsapp_no == '') {
                //     unset($validate->companysetting[$whatsapp_no]);
                // }
                if ($company_logo->getName() != '') {
                    $validate->companysetting[$field_name_company_logo]['rules'] = "uploaded[$field_name_company_logo]|max_size[$field_name_company_logo,1024]|mime_in[$field_name_company_logo,image/jpg,image/jpeg,image/png]";
                    $validate->companysetting[$field_name_company_logo]['errors'] = $errors;
                }
                if ($company_favicon->getName() != '') {
                    $validate->companysetting[$field_name_company_favicon]['rules'] = "uploaded[$field_name_company_favicon]|max_size[$field_name_company_favicon,1024]|mime_in[$field_name_company_favicon,image/jpg,image/jpeg,image/png]";
                    $validate->companysetting[$field_name_company_favicon]['errors'] = $errors;
                }

                $id = dev_decode($this->request->uri->getSegment(3));
                $validate = new Validation();
                $rules = $this->validate($validate->companysetting);
                if (!$rules) {
                    $finalerror = [];
                    $errors = $this->validator->getErrors();
                    if ($errors) {
                        foreach ($errors as $key => $error) {
                            $arraykey = explode(".", $key);
                            if (count($arraykey) == 1) {
                                $finalerror[$arraykey[0]] = $error;
                            } else {
                                $key1 = $arraykey[0];
                                $key2 = $arraykey[1];
                                $finalerror['' . $key1 . '[' . $key2 . ']'] = $error;
                            }
                        }
                    }
                    $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($finalerror));
                    return $this->response->setJSON($response);
                } else {
                    $settingmodel = new SettingModel();
                    $WhitelabelWebpartnerSettingModel = new WhitelabelWebpartnerSettingModel();

                    $data = $this->request->getPost();
                    if ($data['email_setting'] == 0) {
                        $data['email_setting']['email_type'] = "Default";
                    } else {
                        $data['email_setting']['email_type'] = "Custom";
                    }
                    $data['email_setting'] = json_encode($data['email_setting']);
                    $previous_data = admin_cookie_data()['admin_comapny_detail'];
                    /* optional file upload */
                    if ($company_logo->getName() != '') {
                        $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->logo_folder, $resizeDim);
                        if ($company_logo_upload['status_code'] == 0) {
                            $data['company_logo'] = $company_logo_upload['file_name'];

                            if ($previous_data[$field_name_company_logo]) {
                                if (file_exists(FCPATH . "../uploads/$this->logo_folder/" . $previous_data[$field_name_company_logo])) {
                                    unlink(FCPATH . "../uploads/$this->logo_folder/" . $previous_data[$field_name_company_logo]);
                                    unlink(FCPATH . "../uploads/$this->logo_folder/thumbnail/" . $previous_data[$field_name_company_logo]);
                                }
                            }
                        } else {

                            $message = array("StatusCode" => 2, "Message" => $company_logo_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $data['company_logo'] = $previous_data[$field_name_company_logo];
                    }
                    if ($company_favicon->getName() != '') {
                        $company_faviocn_upload = image_upload($company_favicon, $field_name_company_favicon, $this->faviocn_folder, $resizeDim);
                        if ($company_faviocn_upload['status_code'] == 0) {
                            $data['company_favicon'] = $company_faviocn_upload['file_name'];

                            if ($previous_data[$field_name_company_favicon]) {
                                if (file_exists(FCPATH . "../uploads/$this->faviocn_folder/" . $previous_data[$field_name_company_favicon])) {
                                    unlink(FCPATH . "../uploads/$this->faviocn_folder/" . $previous_data[$field_name_company_favicon]);
                                    unlink(FCPATH . "../uploads/$this->faviocn_folder/thumbnail/" . $previous_data[$field_name_company_favicon]);
                                }
                            }
                        } else {

                            $message = array("StatusCode" => 2, "Message" => $company_faviocn_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $data['company_favicon'] = $previous_data[$field_name_company_favicon];
                    }


                    $WhitelabelWebpartnerSettingModel = new WhitelabelWebpartnerSettingModel();
                    if ($data['payment_gateway_name'] !== "Default") {
                        $data['payment_gateway_setting'] = json_encode($data['payment_gateway_setting']);
                        $emailSettingToUpdate = [
                            'email_setting' => $data['email_setting'],
                            'payment_gateway_name' => $data['payment_gateway_name'],
                            'payment_gateway_setting' => $data['payment_gateway_setting'],
                            'google_analytics' => $data['google_analytics'],
                            'copyright' => $data['copyright'],
                        ];
                    } else {
                        $emailSettingToUpdate = [
                            'email_setting' => $data['email_setting'],
                            'payment_gateway_name' => $data['payment_gateway_name'],
                            // 'payment_gateway_setting' =>  $data['payment_gateway_setting']
                            'google_analytics' => $data['google_analytics'],
                            'copyright' => $data['copyright'],
                        ];
                    }

                    $WhitelabelWebpartnerSettingModel->set($emailSettingToUpdate)->where('web_partner_id', $this->web_partner_id)->update();
                    unset($data['email_setting'], $data['payment_gateway_name'], $data['payment_gateway_setting'], $data['google_analytics'], $data['copyright']);

                    if ($settingmodel->where(["id" => $id])->set($data)->update()) {
                        delete_cookie('admin_comapny_detail');
                        $this->session->remove('admin_comapny_detail');
                        $message = array("StatusCode" => 0, "Message" => 'Record successfully updated', 'Class' => 'success_popup', 'FormBlank' => 'false');
                    } else {
                        $message = array("StatusCode" => 2, "Message" => 'Data not saved!', 'Class' => 'error_popup');
                    }
                    $this->session->setFlashdata('Message', $message);
                    $this->session->remove('super_admin_user_details');
                    $this->session->remove('super_admin_comapny_detail');
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function user_management()
    {
        if (permission_access_error("Setting", "user_list")) {
            $profile_details = admin_cookie_data()['admin_user_details'];
            $company_details = admin_cookie_data()['admin_comapny_detail'];

            $settingmodel = new SettingModel();
            $staff_lists = $settingmodel->get_staff_list($this->web_partner_id, $this->user_id);


            $this->title = "User Setting";

            $data = [
                'title' => $this->title,
                'view' => "Setting\Views\user_management",
                'profile_details' => $profile_details,
                'company_details' => $company_details,
                'staff_lists' => $staff_lists
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function profile()
    {
        $profile_details = admin_cookie_data()['admin_user_details'];
        $company_details = admin_cookie_data()['admin_comapny_detail'];

        $settingmodel = new SettingModel();
        $staff_lists = $settingmodel->get_staff_list($this->web_partner_id, $this->user_id);
        $this->title = "Profile Setting";
        $data = [
            'title' => $this->title,
            'view' => "Setting\Views\profile",
            'profile_details' => $profile_details,
            'company_details' => $company_details,
            'staff_lists' => $staff_lists
        ];
        return view('template/sidebar-layout', $data);
    }




    public function add_user()
    {
        if (permission_access("Setting", "add_user")) {
            if ($this->request->getMethod() == 'post') {
                $validate = new Validation();
                $rules = $this->validate($validate->adduser);
                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($response);
                } else {
                    $data = $this->request->getPost();
                    $settingmodel = new SettingModel();
                    $checkRegisterUser = $settingmodel->checkRegisterUser($data['login_email'], $this->web_partner_id);
                    if ($checkRegisterUser > 0) {
                        $errors['login_email'] = "The user is already registered. ";
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                        return $this->response->setJSON($data_validation);
                    }
                    $data['password'] = md5(trim($this->request->getPost('password')));
                    $data['status'] = "active";
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['created_date'] = create_date();
                    $data['access_permission'] = admin_cookie_data()['admin_comapny_detail']['user_access'];

                    /*code to send email */
                    $send_status = 0;
                    if (isset($data['send_email'])) {
                        if ($data['send_email'] == 'send') {
                            $send_status = 1;
                            unset($data['send_email']);
                        }
                    }

                    /*end code to send email */


                    $added_data = $settingmodel->add_user($data);

                    if ($added_data) {
                        if ($send_status == 1) {
                            $email = [
                                "email" => $this->request->getPost('login_email'),
                                "mobile_no" => $this->request->getPost('mobile_no'),
                                "password" => trim($this->request->getPost('password')),
                                "message" => "I hope this email finds you well. On behalf of the entire team, I wanted to extend a warm welcome to each of you. We are absolutely thrilled to have you on board, and we can't wait to embark on this exciting journey together.\n Thanks for choosing to be a part of our family. Your decision to join us is a testament to your talent, dedication, and potential. We firmly believe that with your unique skills and perspectives, we will achieve extraordinary things."
                            ];

                            $subject = "Congratulations! Your account has been created successfully.";
                            $message = view('Views/emails/common-registration-email', $email);
                            $email_type = 'Your Account Has Been Created';
                            send_email($email['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);
                        }
                        $message = array("StatusCode" => 0, "Message" => "User has been added successfully", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "User not added", "Class" => "error_popup", "Reload" => "true");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function change_password()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->change_password_user);
        if (!$rules) {

            $errors = $this->validator->getErrors();
            $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($response);
        } else {
            $settingmodel = new SettingModel();
            $data['password'] = md5(trim($this->request->getPost('password')));
            $user_id = $this->user_id;
            $updated_data = $settingmodel->change_password($this->web_partner_id, $user_id, $data);
            if ($updated_data) {
                $message = array("StatusCode" => 0, "Message" => "Password successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "password not changed", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->remove('admin_common_data');
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function edit_user()
    {
        if (permission_access("Setting", "edit_user")) {
            $edit_user_id = dev_decode($this->request->uri->getSegment(3));
            $SettingModel = new SettingModel();
            $data = [
                'edit_user_id' => $edit_user_id,
                'user_detail' => $SettingModel->edit_user($this->web_partner_id, $edit_user_id),
            ];

            $Message = view('Modules\Setting\Views\edit_user', $data);
            $data = array("StatusCode" => 0, "Message" => $Message, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function user_status_change()
    {
        if (permission_access("Setting", "user_status")) {

            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SettingModel = new SettingModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $SettingModel->user_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "User status changed successfully", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "User status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function update_user()
    {
        if (permission_access("Setting", "edit_user")) {
            if ($this->request->getMethod() == 'post') {
                $validate = new Validation();
                $rules = $this->validate($validate->update_user);
                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($response);
                } else {

                    $user_id = dev_decode($this->request->uri->getSegment(3));
                    $settingmodel = new SettingModel();
                    $previous_detail = $settingmodel->edit_user($this->web_partner_id, $user_id);
                    $data = $this->request->getPost();
                    if ($data['password']) {
                        $data['password'] = md5(trim($data['password']));
                    } else {
                        $data['password'] = $previous_detail['password'];
                    }
                    $updated_data = $settingmodel->update_user($this->web_partner_id, $user_id, $data);

                    if ($updated_data) {
                        $message = array("StatusCode" => 0, "Message" => "User has been updated successfully", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "User has not been updated", "Class" => "error_popup", "Reload" => "true");
                    }
                    $this->session->remove('admin_user_details');
                    $this->session->remove('admin_comapny_detail');
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }



    public function user_access_permission()
    {
        if (permission_access_error("Setting", "user_permissions")) {
            $user_id = dev_decode($this->request->uri->getSegment(3));
            $user_access = admin_cookie_data()['admin_comapny_detail']['user_access'];



            $SettingModel = new SettingModel();
            $user_access_list = $SettingModel->user_access_list($this->web_partner_id, $user_id);

            $data = [
                'user_id' => $user_id,
                'user_access' => $user_access,
                'user_access_list' => $user_access_list
            ];
            $Message = view('Modules\Setting\Views\user_access_permission', $data);
            $data = array("StatusCode" => 0, "Message" => $Message, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }




    public function access_level_update()
    {
        if (permission_access("Setting", "user_permissions")) {
            $user_id = dev_decode($this->request->uri->getSegment(3));
            $settingmodel = new SettingModel();
            $data['access_permission'] = json_encode($this->request->getPost());
            $updated_data = $settingmodel->update_permition($this->web_partner_id, $user_id, $data);


            if ($updated_data) {
                $message = array("StatusCode" => 0, "Message" => "User has been updated successfully", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "User has not been updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->remove('admin_user_details');
            $this->session->remove('admin_comapny_detail');
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function delete_user()
    {
        if (permission_access("Setting", "user_status")) {
            $SettingModel = new SettingModel();
            $user_ids = $this->request->getPost('checklist');
            $user_delete = $SettingModel->delete_user_data($this->web_partner_id, $user_ids);

            if ($user_delete) {
                $message = array("StatusCode" => 0, "Message" => "User has been successfully deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "User has not been deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
}