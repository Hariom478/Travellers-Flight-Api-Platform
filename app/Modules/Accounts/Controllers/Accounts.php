<?php

namespace Modules\Accounts\Controllers;

use App\Modules\Accounts\Models\MakePaymentModel;
use App\Modules\Accounts\Models\AccountsModel;

use App\Controllers\BaseController;
use Modules\Accounts\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Accounts extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Accounts";


        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_details = session()->get('wl_agent');
        $this->user_id = session()->get('wl_agent')['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_class_id = $this->wl_agent_details['agent_class'];
        $this->booking_source ="Wl_b2b";
        $this->make_payment_upload_folder = "make_payment";
        if(permission_access_error("Accounts","Accounts_Module")){

            
        }
    }



    public function MakePaymentView()
    {

        $AccountsModel = new AccountsModel();
        $MakePaymentModel = new MakePaymentModel();
        $data = [
            'title' => $this->title,
            'view' => "Accounts\Views\make-payment",
            'superAdminBankAccountinfo' => $AccountsModel->bank_list($this->web_partner_id),
            'ConvenienceFeeInfo' => $MakePaymentModel->convenience_fee('Make_Payment',$this->web_partner_id,$this->wl_agent_class_id),
        ];
        return view('template/default-layout', $data);
    }

    public function MakePayment()
    {

        $Validation = new Validation();
        $upload_file = $this->request->getFile('upload_file');
        $rules = $this->validate($Validation->makePaymentRequest($this->request->getPost(), $upload_file));
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $AccountsModel = new AccountsModel();
            $postData = $this->request->getPost();
            $insertData['web_partner_id'] = $this->web_partner_id;
            $insertData['wl_agent_id'] = $this->wl_agent_id;
            $insertData['user_id'] = $this->user_id;
            $insertData['amount'] = $postData['amount'];
            $insertData['payment_mode'] = $postData['payment_mode'];
            $insertData['remark'] = $postData['remark'];
            $insertData['cheque_draft_utr_number'] = isset($postData['cheque_draft_utr_number']) ? $postData['cheque_draft_utr_number'] : null;
            $insertData['payment_date'] = isset($postData['date']) && $postData['date'] != "" ? strtotime($postData['date']) : create_date();
            $insertData['bank'] = isset($postData['bank']) ? $postData['bank'] : null;
            $insertData['bank_branch'] = isset($postData['branch']) ? $postData['branch'] : null;
            $insertData['bank_transaction_id'] = isset($postData['bank_transaction_id']) ? $postData['bank_transaction_id'] : null;
            $insertData['status'] = 'pending';
            $insertData['created'] = create_date();
            if (isset($postData['company_bank_account']) && $postData['company_bank_account'] != "") {
                $company_Bank_account_detail = $AccountsModel->super_admin_bank_account_details($postData['company_bank_account']);
                $insertData['company_bank_name'] = isset($company_Bank_account_detail['bank_name']) ? $company_Bank_account_detail['bank_name'] : null;
                $insertData['company_branch_name'] = isset($company_Bank_account_detail['branch_name']) ? $company_Bank_account_detail['branch_name'] : null;
                $insertData['company_account_holder_name'] = isset($company_Bank_account_detail['account_holder_name']) ? $company_Bank_account_detail['account_holder_name'] : null;
                $insertData['company_account_no'] = isset($company_Bank_account_detail['account_no']) ? $company_Bank_account_detail['account_no'] : null;
                $insertData['company_ifsc_code'] = isset($company_Bank_account_detail['ifsc_code']) ? $company_Bank_account_detail['ifsc_code'] : null;
                $insertData['company_swift_code'] = isset($company_Bank_account_detail['swift_code']) ? $company_Bank_account_detail['swift_code'] : null;
            }
            if ($upload_file->getName() != '') {

                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'upload_file';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 560, 'height' => 360);
                $image_upload = image_upload($file, $field_name, $this->make_payment_upload_folder, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $insertData['file_name'] = $image_upload['file_name'];
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            }
            $insertId = $AccountsModel->insertData("wl_agent_make_payment", $insertData);
            $Redirect_Url = "";
            $Reload = "true";
            $StatusCode = 0;
            $onlinepaymentMode = array('CreditCard', 'DebitCard', 'NetBanking', 'UPIPayments','RuPayCreditCard','VisaCreditCard','MastercardCreditCard','AmericanExpressCreditCard');
            if (in_array($postData['payment_mode'], $onlinepaymentMode)) {
                $Reload = "true";
                $StatusCode = 3;
                $Redirect_Url = site_url('payment/make-payment/' . dev_encode($insertId));
            }
            if ($insertId) {
                $message = array("StatusCode" => $StatusCode, "Message" => "Make payment request successfully created", "Class" => "success_popup", "Reload" => $Reload, "Redirect_Url" => $Redirect_Url);
            } else {
                $message = array("StatusCode" => 2, "Message" => "Unable to created make payment request", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }


    }

    public function RechargeMakePaymentView()
    {

        $AccountsModel = new AccountsModel();
        $MakePaymentModel = new MakePaymentModel();
        $data = [
            'title' => $this->title,
            'view' => "Accounts\Views\\recharge-make-payment",
            'superAdminBankAccountinfo' => $AccountsModel->bank_list($this->web_partner_id),
            'ConvenienceFeeInfo' => $MakePaymentModel->convenience_fee('Make_Payment',$this->web_partner_id,$this->wl_agent_class_id),
        ];
        return view('template/default-layout', $data);
    }

    public function payment_history()
    {
        $MakePaymentModel = new MakePaymentModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $MakePaymentModel->search_data($this->web_partner_id, $this->wl_agent_id,$this->request->getGet());
        } else {
            $lists = $MakePaymentModel->payment_history($this->web_partner_id,$this->wl_agent_id);
        }


        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Accounts\Views\payment-history",
            'pager' => $MakePaymentModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);
    }


    public function payment_history_detail()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $MakePaymentModel = new MakePaymentModel();

        $details = $MakePaymentModel->payment_history_detail($id, $this->web_partner_id,$this->wl_agent_id);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $details = view('Modules\Accounts\Views\payment-history-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }


}
