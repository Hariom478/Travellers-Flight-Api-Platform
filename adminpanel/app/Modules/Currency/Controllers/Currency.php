<?php

namespace Modules\Currency\Controllers;

use App\Modules\Currency\Models\CurrencyModel;
use App\Controllers\BaseController;
use Modules\Currency\Config\Validation;


class Currency extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        ini_set('serialize_precision', -1);
        parent::initController($request, $response, $logger);
        $this->title = "Super Admin Currency";
        $this->folder_name = "Currency";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->whitelabel_setting_data =  admin_cookie_data()['whitelabel_setting_data'];
        if ($this->whitelabel_setting_data['multi_currency'] == "active") {
        } else {
            echo view(
                "errors/html/custom_error",
                [
                    'error_title' => "Permission Denied Multi Currency ",
                    'error_message' => "Multi Currency not active for this page",
                    'error_code' => 405
                ]
            );
            die();
        } 
        if (permission_access_error("Currency", "Currency_Module"))
        {

        }  

    }

    
    public function index()
    {
   
        $CurrencyModel = new CurrencyModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$CurrencyModel->search_data($this->request->getGet(),$this->web_partner_id,);
        }  else {
            $lists=$CurrencyModel->currency_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Currency\Views\currency-list",
            'pager' => $CurrencyModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];


        return view('template/sidebar-layout', $data);
    }

    public function add_currency_view()
    {
         if (permission_access_error("Currency", "add_currency")) {
        $CurrencyModel = new CurrencyModel();
        $currency = $CurrencyModel->get_currency();
            $data = [
                'title' => $this->title,
                'currency'=> $currency,
            ];
            $add_blog_view = view('Modules\Currency\Views\add-currency', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_currency()
    {
        if (permission_access_error("Currency", "add_currency")) {
            $validate = new Validation();
            $rules = $this->validate($validate->currency_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CurrencyModel = new CurrencyModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] =  $this->web_partner_id;
        
                $added_data = $CurrencyModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "currency successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "currency not  added", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_currency_view()
    {
        if (permission_access_error("Currency", "edit_currency")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $CurrencyModel = new CurrencyModel();
            $currency = $CurrencyModel->get_currency();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'currency' => $currency,
                'details' => $CurrencyModel->currency_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Currency\Views\edit-currency', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_currency()
    {

         if (permission_access_error("Currency", "edit_currency")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $rules = $this->validate($validate->currency_validation_update);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CurrencyModel = new CurrencyModel();
            $data = $this->request->getPost();
            $data['web_partner_id'] =  $this->web_partner_id;
            $data['modified'] = create_date();

            $added_data = $CurrencyModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "currency successfully Edit", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "currency not  Edit", "Class" => "error_popup", "Reload" => "true");
            }
           
           
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
       }
    }

    public function remove_currency()
    {
       if (permission_access_error("Currency", "delete_currency")) {

        $CurrencyModel = new CurrencyModel();
        $id= $this->request->getPost('checklist');
            $delete = $CurrencyModel->remove_currency($id,$this->web_partner_id);
        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "currency  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "currency  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }




   


}