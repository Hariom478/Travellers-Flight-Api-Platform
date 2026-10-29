<?php

namespace Modules\Customer\Controllers;

use App\Modules\Customer\Models\CustomerModel;
use App\Modules\Customer\Models\CustomerAccountLogModel;
use App\Modules\Customer\Models\CustomerTravellerModel;

use App\Controllers\BaseController;
use Modules\Customer\Config\Validation;
require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Customer extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Customer Management";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->whitelabel_user = admin_cookie_data()['admin_comapny_detail']['whitelabel_user'];
        if ($this->whitelabel_user != "active"){
            access_denied();
        }
        $this->folder_name = 'customer';

        if (permission_access_error("Customer", "Customer_Module")) {

        }
    }

    public function index()
    {
        $CustomerModel = new CustomerModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CustomerModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $CustomerModel->customer_list($this->web_partner_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Customer\Views\customer-list",
            'pager' => $CustomerModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function add_customer_view()
    {
        if (permission_access_error("Customer", "add_customer")) {
            $add_slider_view = view('Modules\Customer\Views\add-customer');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_customer()
    {
        if (permission_access_error("Customer", "add_customer")) {
            $validate = new Validation();
            $resizeDim = array('width' => 360, 'height' => 200);
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $field_name_profile_pic = 'profile_pic';
            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->customer_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->customer_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $rules = $this->validate($validate->customer_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $data = $this->request->getPost();
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

                /*code to send email */

                if ($data['send_email'] == 'send') {
                    unset($data['send_email']);
                }

                /*end code to send email */

                /*password encryption*/
                $password = md5($this->request->getPost('password'));
                $data['password'] = $password;
                /*end password encryption*/
                $email_verify = $this->request->getPost('email_verify');
                $mobile_verify = $this->request->getPost('mobile_verify');
                if ($mobile_verify == 'Verified') {
                    $data['mobile_verify'] = 1;
                } else {
                    $data['mobile_verify'] = 0;
                }
                if ($email_verify == 'Verified') {
                    $data['email_verify'] = 1;
                } else {
                    $data['email_verify'] = 0;
                }

                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data[$field_name_profile_pic] = $profile_pic;
                $data['created'] = create_date();

                $added_data = $CustomerModel->insert($data);

                $log['customer_id'] = $added_data;
                $log['web_partner_id'] = $this->web_partner_id;
                $log['created'] = create_date();
                $log['action_type'] = 'credit';
                $data['credit'] = 0;
                $log['balance'] = 0;
                $log['remark'] = 'Account created by admin';
                $log['payment_mode'] = 'Temporary_Credit';

                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $account_log_added = $CustomerAccountLogModel->insert($log);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Customer Successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer not  added", "Class" => "error_popup", "Reload" => "true");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_customer_view()
    {
        if (permission_access_error("Customer", "edit_customer")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CustomerModel = new CustomerModel();
            $details = $CustomerModel->customer_details($id, $this->web_partner_id);
            if (isset($details['dob']) && $details['dob'] != '') {
                $details['dob'] = timestamp_to_date($details['dob']);
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\Customer\Views\edit-customer', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_customer()
    {
        if (permission_access_error("Customer", "edit_customer")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();
            unset($validate->customer_validation['password']);
            $validate->customer_validation['email_id']['rules'] = "required|valid_email|is_unique[customer.email_id,id,$id]";

            $resizeDim = array('width' => 360, 'height' => 200);
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $field_name_profile_pic = 'profile_pic';
            $profile_pic = $this->request->getFile($field_name_profile_pic);
            if ($profile_pic->getName() != '') {
                $validate->customer_validation[$field_name_profile_pic]['rules'] = "uploaded[$field_name_profile_pic]|max_size[$field_name_profile_pic,1024]|mime_in[$field_name_profile_pic,image/jpg,image/jpeg,image/png]";
                $validate->customer_validation[$field_name_profile_pic]['errors'] = $errors;
            }

            $rules = $this->validate($validate->customer_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $data = $this->request->getPost();

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
                /* end optional file upload */

                $email_verify = $this->request->getPost('email_verify');
                $mobile_verify = $this->request->getPost('mobile_verify');
                if ($mobile_verify == 'Verified') {
                    $data['mobile_verify'] = 1;
                } else {
                    $data['mobile_verify'] = 0;
                }
                if ($email_verify == 'Verified') {
                    $data['email_verify'] = 1;
                } else {
                    $data['email_verify'] = 0;
                }
                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                $data[$field_name_profile_pic] = $profile_pic;
                $data['modified'] = create_date();
                $added_data = $CustomerModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Customer Successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function customer_status_change()
    {
        if (permission_access_error("Customer", "customer_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $CustomerModel->customer_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Customer status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function change_customer_password()
    {
        if (permission_access_error("Customer", "change_password")) {
            $validate = new Validation();
            $rules = $this->validate($validate->customer_password_change);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerModel = new CustomerModel();
                $send_email = $this->request->getPost('send_email'); //send email pending
                $id = dev_decode($this->request->getPost('agent_id'));
                $data['password'] = md5($this->request->getPost('password'));

                $update = $CustomerModel->where("id", $id)->set($data)->update();

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Customer Password  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Customer Password not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_customer()
    {
        if (permission_access_error("Customer", "delete_customer")) {
            $CustomerModel = new CustomerModel();
            $ids = $this->request->getPost('checklist');

            $field_name_profile_pic = "profile_pic";

            foreach ($ids as $id) {
                $details = $CustomerModel->delete_image($id, $this->web_partner_id);

                if ($details[$field_name_profile_pic]) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name_profile_pic])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name_profile_pic]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name_profile_pic]);
                    }
                }

                $delete = $CustomerModel->remove_customer($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Customer  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Customer  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function customer_details()
    {
        $customer_id = dev_decode($this->request->uri->getSegment(3));
        $CustomerModel = new CustomerModel();
        $CustomerAccountLogModel = new CustomerAccountLogModel();

        $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

        $details = $CustomerModel->customer_details($customer_id, $this->web_partner_id);

        if (isset($available_balance['balance'])) {
            $details['balance'] = $available_balance['balance'];
        }

        $data = [
            'title' => $this->title,
            'id' => $customer_id,
            'details' => $details,
            'account_logs' => $CustomerAccountLogModel->account_logs_detail($customer_id, $this->web_partner_id),
        ];
        $details = view('Modules\Customer\Views\customer-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function customer_account_logs(): string
    {
        if (permission_access_error("Customer", "account_logs")) {

            $customer_id = dev_decode($this->request->uri->getSegment(3));;
            $CustomerModel = new CustomerModel();
            $CustomerAccountLogModel = new CustomerAccountLogModel();

            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

            $details = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            $details['balance'] = $available_balance['balance'];
            $data = [
                'title' => $this->title,
                'account_logs' => $CustomerAccountLogModel->account_logs($customer_id, $this->web_partner_id),
                'details' => $details,
                'view' => "Customer\Views\customer-account-logs-list",
                'pager' => $CustomerAccountLogModel->pager,
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function virtual_topup_view()
    {
        if (permission_access_error("Customer", "virtual_topup")) {
            $customer_id = dev_decode($this->request->uri->getSegment(3));
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $CustomerModel = new CustomerModel();

            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

            $data['details'] = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Customer\Views\topup-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function virtual_topup()
    {
        if (permission_access_error("Customer", "virtual_topup")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_credit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $customer_id = dev_decode($this->request->uri->getSegment(3));

                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
                $data = $this->request->getPost();
                $data['customer_id'] = $customer_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['action_type'] = 'credit';

                $data['balance'] = $available_balance['balance'] + $data['credit'];

                $added_data = $CustomerAccountLogModel->insert($data);
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
        if (permission_access_error("Customer", "virtual_deduct")) {
            $customer_id = dev_decode($this->request->uri->getSegment(3));
            $CustomerAccountLogModel = new CustomerAccountLogModel();
            $CustomerModel = new CustomerModel();

            $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);

            $data['details'] = $CustomerModel->customer_details($customer_id, $this->web_partner_id);
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }

            $view = view('Modules\Customer\Views\debit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function virtual_debit()
    {
        if (permission_access_error("Customer", "virtual_deduct")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_debit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $customer_id = dev_decode($this->request->uri->getSegment(3));

                $CustomerAccountLogModel = new CustomerAccountLogModel();
                $available_balance = $CustomerAccountLogModel->available_balance($customer_id, $this->web_partner_id);
                $data = $this->request->getPost();
                $data['customer_id'] = $customer_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $data['action_type'] = 'debit';
                $data['payment_mode'] = 'Temporary_Credit';

                $data['balance'] = $available_balance['balance'] - $data['debit'];

                $added_data = $CustomerAccountLogModel->insert($data);
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

    public function travellers_list()
    {
        if (permission_access_error("Customer", "traveller_list")) {
            $CustomerTravellerModel = new CustomerTravellerModel();
            $customer_id = dev_decode($this->request->uri->getSegment(3));
            $lists = $CustomerTravellerModel->traveller_list($this->web_partner_id, $customer_id);
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Customer\Views\Traveller-list",
                'pager' => $CustomerTravellerModel->pager,
                'customer_id' => $customer_id
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function add_travellers_view()
    {
        if (permission_access_error("Customer", "add_traveller")) {
            $customer_id = dev_decode($this->request->uri->getSegment(3));

            $data = [
                'title' => $this->title,
                'customer_id' => $customer_id,
            ];
            $details = view('Modules\Customer\Views\add-traveller', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_travellers()
    {
        if (permission_access_error("Customer", "add_traveller")) {
            $validate = new Validation();

            $rules = $this->validate($validate->traveller_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerTravellerModel = new CustomerTravellerModel();
                $data = $this->request->getPost();
                $customer_id = dev_decode($this->request->uri->getSegment(3));


                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                if ($data['passport_expiry_date']) {
                    $data['passport_expiry_date'] = strtotime($data['passport_expiry_date']);
                }
                if ($data['passport_issue_date']) {
                    $data['passport_issue_date'] = strtotime($data['passport_issue_date']);
                }
                $data['web_partner_id'] = $this->web_partner_id;

                $data['created'] = create_date();
                $data['customer_id'] = $customer_id;

                $added_data = $CustomerTravellerModel->insert($data);


                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Traveller Successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Traveller not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_traveller_view()
    {
        if (permission_access_error("Customer", "edit_traveller")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CustomerTravellerModel = new CustomerTravellerModel();
            $details = $CustomerTravellerModel->traveller_details($this->web_partner_id, $id);
            if (isset($details['dob']) && $details['dob'] != '') {
                $details['dob'] = timestamp_to_date($details['dob']);
            }

            if (isset($details['passport_expiry_date']) && $details['passport_expiry_date'] != '') {
                $details['passport_expiry_date'] = timestamp_to_date($details['passport_expiry_date']);

            }
            if (isset($details['passport_issue_date']) && $details['passport_issue_date'] != '') {
                $details['passport_issue_date'] = timestamp_to_date($details['passport_issue_date']);
            }

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\Customer\Views\edit-traveller', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_travellers()
    {
        if (permission_access_error("Customer", "edit_traveller")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->traveller_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerTravellerModel = new CustomerTravellerModel();
                $data = $this->request->getPost();

                if ($data['dob']) {
                    $data['dob'] = strtotime($data['dob']);
                }
                if ($data['passport_expiry_date']) {
                    $data['passport_expiry_date'] = strtotime($data['passport_expiry_date']);
                }
                if ($data['passport_issue_date']) {
                    $data['passport_issue_date'] = strtotime($data['passport_issue_date']);
                }

                $data['modified'] = create_date();
                $added_data = $CustomerTravellerModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Traveller Successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Traveller not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function traveller_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $CustomerTravellerModel = new CustomerTravellerModel();
        $details = $CustomerTravellerModel->traveller_details($this->web_partner_id, $id);
        if (isset($details['dob']) && $details['dob'] != '') {
            $details['dob'] = timestamp_to_date($details['dob']);
        }

        if (isset($details['passport_expiry_date']) && $details['passport_expiry_date'] != '') {
            $details['passport_expiry_date'] = timestamp_to_date($details['passport_expiry_date']);

        }
        if (isset($details['passport_issue_date']) && $details['passport_issue_date'] != '') {
            $details['passport_issue_date'] = timestamp_to_date($details['passport_issue_date']);
        }

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $details = view('Modules\Customer\Views\traveller-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function traveller_status_change()
    {
        if (permission_access_error("Customer", "traveller_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CustomerTravellerModel = new CustomerTravellerModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['status'] = $this->request->getPost('status');
                $data['modified'] = create_date();
                $update = $CustomerTravellerModel->traveller_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Traveller status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Traveller status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_traveller()
    {
        if (permission_access_error("Customer", "delete_traveller")) {
            $CustomerTravellerModel = new CustomerTravellerModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CustomerTravellerModel->remove_traveller($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Traveller Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Traveller  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function export_customer()
    {
        if (permission_access_error("Customer", "customer_export")) {
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

        $fileName = 'customer.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:S1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

        $sheet->getStyle("A:S")->getFont()->setName('Arial')->setSize(11);
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

        $sheet->setCellValue('A1', 'Sr. No.');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Mobile');
        $sheet->setCellValue('E1', 'Mobile Verify');
        $sheet->setCellValue('F1', 'Email Verify');
        $sheet->setCellValue('G1', 'DOB');
        $sheet->setCellValue('H1', 'Marital Status');
        $sheet->setCellValue('I1', 'Address');
        $sheet->setCellValue('J1', 'City');
        $sheet->setCellValue('K1', 'State');
        $sheet->setCellValue('L1', 'Country');
        $sheet->setCellValue('M1', 'Pin Code');
        $sheet->setCellValue('N1', 'status');
        $sheet->setCellValue('O1', 'Balance');
        $sheet->setCellValue('P1', 'Last Login Time');
        $sheet->setCellValue('Q1', 'Login IP Address');
        $sheet->setCellValue('R1', 'modified');
        $sheet->setCellValue('S1', 'Created Date');


        $rows = 2;
        $CustomerModel = new CustomerModel();
        $customer_excel = $CustomerModel->customer_export($this->web_partner_id, $this->request->getPost());

        foreach ($customer_excel as $key => $val) {
            if ($val['modified']){
                $modified = date_created_format($val['modified']);
            }else{
                $modified='';
            }

            if ($val['dob']){
                $dob = timestamp_to_date($val['dob']);
            }else{
                $dob='';
            }

            $sheet->setCellValue('A' . $rows, $key + 1);
            $sheet->setCellValue('B' . $rows, $val['title'] . ' ' . $val['first_name'] . ' ' . $val['last_name']);
            $sheet->setCellValue('C' . $rows, $val['email_id']);
            $sheet->setCellValue('D' . $rows, $val['mobile_no']);
            $sheet->setCellValue('E' . $rows, $val['mobile_verify']);
            $sheet->setCellValue('F' . $rows, $val['email_verify']);
            $sheet->setCellValue('G' . $rows, $dob);
             $sheet->setCellValue('H' . $rows, $val['marital_status']);
            $sheet->setCellValue('I' . $rows, $val['address']);
            $sheet->setCellValue('J' . $rows, $val['city']);
            $sheet->setCellValue('K' . $rows, $val['state']);
            $sheet->setCellValue('L' . $rows, $val['country']);
            $sheet->setCellValue('M' . $rows, $val['pin_code']);
            $sheet->setCellValue('N' . $rows, $val['status']);
            $sheet->setCellValue('O' . $rows, $val['balance']);
            $sheet->setCellValue('P' . $rows, $val['last_login_time']);
            $sheet->setCellValue('Q' . $rows, $val['login_ip_address']);
            $sheet->setCellValue('R' . $rows, $modified);
            $sheet->setCellValue('S' . $rows, date_created_format($val['created']));
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

}
