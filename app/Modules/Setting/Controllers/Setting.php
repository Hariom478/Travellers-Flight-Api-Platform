<?php

namespace Modules\Setting\Controllers;

use App\Modules\Setting\Models\SettingModel;
use App\Controllers\BaseController;
use Modules\Setting\Config\Validation; // Import Validation

class Setting extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Setting";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->folder_name = "agent/logo";
        $this->wl_agent_detail = session()->get('wl_agent');
        $this->profile_folder = "agent/profile-pic";
    }

    public function index()
    {
        $profile_details = session()->get('wl_agent');
        $company_details = session()->get('wl_agent');

        $data = [
            'title' => $this->title,
            'view' => "Setting\Views\index",
            'profile_details' => $profile_details,
            'company_details' => $company_details
        ];
        return view('template/default-layout', $data);
    }

    public function update_company_setting()
    {

        if ($this->request->getMethod() == 'post') {
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $validate = new Validation();
            $field_name_company_logo = "company_logo";
            $field_name_company_favicon = "company_favicon";
            $company_logo = $this->request->getFile($field_name_company_logo);
            if ($company_logo->getName() != '') {
                $validate->companysetting[$field_name_company_logo]['rules'] = "uploaded[$field_name_company_logo]|max_size[$field_name_company_logo,1024]|mime_in[$field_name_company_logo,image/jpg,image/jpeg,image/png]";
                $validate->companysetting[$field_name_company_logo]['errors'] = $errors;
            }

            /*$company_favicon = $this->request->getFile($field_name_company_favicon);
                if ($company_favicon->getName() != '') {
                    $validate->companysetting[$field_name_company_favicon]['rules'] = "uploaded[$field_name_company_favicon]|max_size[$field_name_company_favicon,1024]|mime_in[$field_name_company_favicon,image/jpg,image/jpeg,image/png]";
                    $validate->companysetting[$field_name_company_favicon]['errors'] = $errors;
                }*/

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
                $data = $this->request->getPost();
                $resizeDim = array('width' => 150, 'height' => 80);
                $previous_data = $this->wl_agent_detail;

                /* optional file upload */


                if ($company_logo->getName() != '') {
                    $company_logo_upload = image_upload($company_logo, $field_name_company_logo, $this->folder_name, $resizeDim);

                    if ($company_logo_upload['status_code'] == 0) {
                        $company_logo = $company_logo_upload['file_name'];

                        if ($previous_data[$field_name_company_logo]) {
                            if (file_exists(FCPATH . "./uploads/$this->folder_name/" . $previous_data[$field_name_company_logo])) {
                                unlink(FCPATH . "./uploads/$this->folder_name/" . $previous_data[$field_name_company_logo]);
                                unlink(FCPATH . "./uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name_company_logo]);
                            }
                        }
                        $data['company_logo'] = $company_logo_upload['file_name'];
                    } else {
                        $company_logo = null;
                        $message = array("StatusCode" => 2, "Message" => $company_logo_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $data['company_logo'] = $previous_data[$field_name_company_logo];
                }



                $updated_data = $settingmodel->update_agent($this->web_partner_id, $this->wl_agent_detail['agent_id'], $data);

                $user = $settingmodel->edit_user($this->web_partner_id, $this->wl_agent_detail['agent_id'], $this->wl_agent_id);
                $this->session->set('wl_agent', $user);

                if ($updated_data) {

                    $response = array("StatusCode" => 0, "Message" => 'details successfully updated', 'class' => 'success_popup', 'FormBlank' => 'false');
                } else {
                    $response = array("StatusCode" => 2, "Message" => 'details not saved!', 'class' => 'error_popup');
                }
                return $this->response->setJSON($response);
            }
        }
    }

    public function user_management()
    {
        $profile_details = session()->get('wl_agent');
        $company_details = session()->get('wl_agent');

        $settingmodel = new SettingModel();
        $staff_lists = $settingmodel->get_staff_list($this->web_partner_id, $this->wl_agent_detail['agent_id'], $this->wl_agent_detail['id']);

        $this->title = "User Setting";
        $data = [
            'title' => $this->title,
            'view' => "Setting\Views\user_management",
            'profile_details' => $profile_details,
            'company_details' => $company_details,
            'staff_lists' => $staff_lists
        ];
        return view('template/default-layout', $data);
    }

    public function profile()
    {
        $profile_details = session()->get('wl_agent');
        $company_details = session()->get('wl_agent');

        $settingmodel = new SettingModel();

        $data = [
            'title' => $this->title,
            'view' => "Setting\Views\profile",
            'profile_details' => $profile_details,
            'company_details' => $company_details,

        ];
        return view('template/default-layout', $data);
    }

    public function add_user()
    {
        if ($this->request->getMethod() == 'post') {
            $validate = new Validation();
            $rules = $this->validate($validate->adduser);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($response);
            } else {
                $data = $this->request->getPost();
                $data['password'] = md5(trim($this->request->getPost('password')));
                $data['status'] = "active";
                $data['web_partner_id'] = $this->web_partner_id;
                $data['agent_id'] = $this->wl_agent_detail['agent_id'];
                $data['created_date'] = create_date();
                $data['access_permission'] = $this->wl_agent_detail['access_permission'];
                $data['primary_user'] = 0;
                $settingmodel = new SettingModel();
                $added_data = $settingmodel->add_user($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "User Successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "User not  Updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }



    public function user_status_change()
    {
        if (permission_access("Setting", "staff_status")) {

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

                $update = $SettingModel->update_user($this->web_partner_id, $this->wl_agent_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "User status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "User status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function user_access_permission()
    {
        if (permission_access("Setting", "staff_permissions")) {
            $user_id = dev_decode($this->request->uri->getSegment(3));
            $user_access = $this->wl_agent_detail['access_permission'];

            $SettingModel = new SettingModel();
            $user_access_list = $SettingModel->user_access_list($this->web_partner_id, $this->wl_agent_id, $user_id);
            $data = [
                'user_id' => $user_id,
                'user_access' => $user_access,
                'user_access_list' => $user_access_list
            ];


            $Message = view('Modules\Setting\Views\user_access_permission', $data);
            $data = array("StatusCode" => 0, "Message" => $Message, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $data = array("StatusCode" => 0, "Message" => "Permission Denied", 'class' => 'error_popup');
            return $this->response->setJSON($data);
        }
    }

    public function access_level_update()
    {
        if (permission_access("Setting", "staff_permissions")) {
            $user_id = dev_decode($this->request->uri->getSegment(3));
            $settingmodel = new SettingModel();
            $data['access_permission'] = json_encode($this->request->getPost());
            $updated_data = $settingmodel->update_permition($this->web_partner_id, $this->wl_agent_id, $user_id, $data);


            if ($updated_data) {
                $message = array("StatusCode" => 0, "Message" => "User Successfully added", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "User not  Updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->remove('admin_user_details');
            $this->session->remove('admin_comapny_detail');
            $this->session->setFlashdata('Message', $message);
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
            $data['password'] = md5(trim($this->request->getPost('change_password')));
            $user_id = $this->wl_agent_id;


            $updated_data = $settingmodel->change_password($this->web_partner_id, $user_id, $data);


            $user = $settingmodel->edit_user($this->web_partner_id, $this->wl_agent_detail['agent_id'], $this->wl_agent_id);


            $Name = $user['first_name'] . '' . $user['last_name'];


            $email = [
                "email" => $user['login_email'],
                "password" => trim($this->request->getPost('change_password')),
                "message" => "Dear $Name , This is to confirm that the password for your account has been successfully changed. Your account is now secured with the new password that you have set. If you did not change your password, please contact us immediately to report any unauthorized access to your account."
            ];



            $subject = "Congratulations! Your Password has been successfully change .";
            $message = view('Views/emails/common-registration-email', $email);
            $email_type = 'Your Account Has Been Created';
            send_email($user['login_email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);



            if ($updated_data) {



                $message = array("StatusCode" => 0, "Message" => "password successfully added", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "password not updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->remove('admin_common_data');
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function edit_user()
    {
        $edit_user_id = dev_decode($this->request->uri->getSegment(3));

        $SettingModel = new SettingModel();
        $data = [
            'edit_user_id' => $edit_user_id,
            'user_detail' => $SettingModel->edit_user($this->web_partner_id, $this->wl_agent_id, $edit_user_id),
        ];

        $Message = view('Modules\Setting\Views\edit_user', $data);
        $data = array("StatusCode" => 0, "Message" => $Message, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }



    public function update_user()
    {
        if ($this->request->getMethod() == 'post') {
            $field_name = 'profile_pic';
            $validate = new Validation();
            $post_images = $this->request->getFile($field_name);
            if ($post_images->getName() == '') {
                unset($validate->update_user[$field_name]);
            }
            $rules = $this->validate($validate->update_user);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($response);
            } else {

                $user_id = dev_decode($this->request->uri->getSegment(3));
                $settingmodel = new SettingModel();

                $data = $this->request->getPost();

                if (!empty($data['password'])) {
                    $data['password'] = md5($data['password']);
                } else {
                    unset($data['password']);
                }
                $previous_data = $settingmodel->edit_user($this->web_partner_id, $this->wl_agent_id, $user_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->profile_folder, $resizeDim);
                    if ($image_upload['status_code'] == 0) {
                        if ($previous_data[$field_name]) {
                            if (file_exists(FCPATH . "../uploads/$this->profile_folder" . $previous_data[$field_name])) {
                                unlink(FCPATH . "../uploads/ $this->profile_folder/" . $previous_data[$field_name]);
                                unlink(FCPATH . "../uploads/ $this->profile_folder/thumbnail/" . $previous_data[$field_name]);
                            }
                        }
                        $data[$field_name] = $image_upload['file_name'];

                        $updated_data = $settingmodel->update_user($this->web_partner_id, $this->wl_agent_id, $user_id, $data);

                        $user = $settingmodel->edit_user($this->web_partner_id, $this->wl_agent_id, $user_id);

                        if ($updated_data) {
                            $message = array("StatusCode" => 0, "Message" => "User Successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "User not  Updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['profile_pic'] = $previous_data[$field_name];

                    $updated_data = $settingmodel->update_user($this->web_partner_id, $this->wl_agent_id, $user_id, $data);

                    $user = $settingmodel->edit_user($this->web_partner_id, $this->wl_agent_id, $user_id);

                    if ($updated_data) {
                        $message = array("StatusCode" => 0, "Message" => "User Successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "User not  Updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function staff_change_password()
    {
        $edit_staff_id = dev_decode($this->request->uri->getSegment(3));

        $SettingModel = new SettingModel();
        $data = [
            'edit_staff_id' => $edit_staff_id
        ];

        $Message = view('Modules\Setting\Views\change_staff_password', $data);
        $data = array("StatusCode" => 0, "Message" => $Message, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function update_staff_change_password()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->change_password_user);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $response = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($response);
        } else {
            $settingmodel = new SettingModel();
            $data['password'] = md5(trim($this->request->getPost('change_password')));
            $user_id = dev_decode($this->request->uri->getSegment(3));
            $updated_data = $settingmodel->change_password($this->web_partner_id, $user_id, $data);
            if ($updated_data) {
                $message = array("StatusCode" => 0, "Message" => "Staff Password successfully added", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Staff Password not updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->remove('admin_common_data');
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
}
