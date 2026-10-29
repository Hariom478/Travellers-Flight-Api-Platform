<?php

namespace Modules\BankAccounts\Controllers;

use App\Modules\BankAccounts\Models\BankAccountDetailsModel;

use App\Controllers\BaseController;
use Modules\BankAccounts\Config\Validation;



class BankAccounts extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Bank Accounts";
        $admin_cookie_data=admin_cookie_data();
        $this->web_partner_id =$admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->user_id =$admin_cookie_data['admin_user_details']['id'];
        $this->whitelabel_user = $admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $whitelabel_setting_data = $admin_cookie_data['whitelabel_setting_data'];

        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['b2b_business'] != "active") {
            access_denied();
        }
    }

    public function index()
    {
        if(permission_access_error("Setting", "bank_account_list"))
        {
            $BankAccountDetailsModel = new BankAccountDetailsModel();
            if($this->request->getGet() && $this->request->getGet('key'))
            {
                $lists=$BankAccountDetailsModel->search_data($this->web_partner_id,$this->request->getGet());
            }  else {
                $lists=$BankAccountDetailsModel->bank_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "BankAccounts\Views\bank-accounts-list",
                'pager' => $BankAccountDetailsModel->pager,
                'search_bar_data'=>$this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }
   
    public function add_account_view()
    {
        if(permission_access("Setting", "add_bank_account"))
        {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\BankAccounts\Views\add-bank-account', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function add_account()
    {
        if(permission_access("Setting", "add_bank_account"))
        {
            $validate = new Validation();
            $rules = $this->validate($validate->bank_accounts_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BankAccountDetailsModel = new BankAccountDetailsModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $BankAccountDetailsModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Bank Account Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bank Account not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_account_view()
    {
        if(permission_access("Setting", "edit_bank_account"))
        {
            $id = dev_decode($this->request->uri->getSegment(3));
            $BankAccountDetailsModel = new BankAccountDetailsModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $BankAccountDetailsModel->bank_details($this->web_partner_id, $id),
            ];
            $blog_details = view('Modules\BankAccounts\Views\edit-bank-account', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }


    public function edit_account()
    {
        if(permission_access("Setting", "edit_bank_account"))
        {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $validate->bank_accounts_validation['account_no']['rules'] = "required|numeric|is_unique[bank_account_detail.account_no,id,$id]";
            $rules = $this->validate($validate->bank_accounts_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BankAccountDetailsModel = new BankAccountDetailsModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $added_data = $BankAccountDetailsModel->where("id", $id)->where('web_partner_id',$this->web_partner_id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Bank Account Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bank Account not  Updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }

        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }

    public function remove_account()
    {
        if(permission_access("Setting", "delete_bank_account"))
        {
            $BankAccountDetailsModel = new BankAccountDetailsModel();
            $ids = $this->request->getPost('checklist');
            $delete = $BankAccountDetailsModel->remove_bank($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Bank Account Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Bank Account  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
            
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

}