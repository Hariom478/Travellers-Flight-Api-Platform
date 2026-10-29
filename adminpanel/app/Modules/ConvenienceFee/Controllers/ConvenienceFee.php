<?php

namespace Modules\ConvenienceFee\Controllers;

use App\Modules\ConvenienceFee\Models\WebPartnerConvenienceFeeModel;
use App\Modules\ConvenienceFee\Models\AgentClassModel;
use App\Controllers\BaseController;
use Modules\ConvenienceFee\Config\Validation;


class ConvenienceFee extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "convenience Fee ";
        $admin_cookie_data=admin_cookie_data();
        $this->web_partner_id =$admin_cookie_data['admin_user_details']['web_partner_id'];
		$this->web_partner_details =$admin_cookie_data['admin_user_details'];
		$this->admin_comapny_detail =$admin_cookie_data['admin_comapny_detail'];
		$this->user_id = $admin_cookie_data['admin_user_details']['id'];
        $this->whitelabel_user = $admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $this->whitelabel_setting = $admin_cookie_data['whitelabel_setting_data']; 
        $whitelabel_setting_data = $admin_cookie_data['whitelabel_setting_data'];

        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['b2b_business'] != "active" || strtolower($this->whitelabel_setting['payment_gateway_name']) == 'default') {
            access_denied();
        }
    }

    public function index()
    {
        if (permission_access_error("Setting", "convenience_fee_list")) {
            $WebPartnerConvenienceFeeModel = new WebPartnerConvenienceFeeModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $WebPartnerConvenienceFeeModel->search_data($this->request->getGet(),$this->web_partner_id);
            } else {
                $lists = $WebPartnerConvenienceFeeModel->convenience_list($this->web_partner_id);
            }

            $AgentClassModel = new AgentClassModel();
            $AgentClass =   $AgentClassModel->agent_class_list($this->web_partner_id);

            $agent_class_list = array_column($AgentClass, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'payment_gateway' => $this->whitelabel_setting['payment_gateway_name'],
                'view' => "ConvenienceFee\Views\convenience-list",
                'pager' => $WebPartnerConvenienceFeeModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_convenience_view()
    {
        if (permission_access("Setting", "add_convenience_fee") && strtolower($this->whitelabel_setting['payment_gateway_name']) != 'default') {
            $AgentClassModel = new AgentClassModel();
            $AgentClass =   $AgentClassModel->agent_class_list($this->web_partner_id);
            $payment_gateway = array();
            if(strtolower($this->whitelabel_setting['payment_gateway_name']) != 'default'){
                array_push($payment_gateway,$this->whitelabel_setting['payment_gateway_name']);
            }
            $data = [
                'title' => $this->title,
                'agent_class' => $AgentClass,
                'payment_gateway' => $payment_gateway
            ];
            $add_blog_view = view('Modules\ConvenienceFee\Views\add-convenience', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function add_convenience()
    {
        $data = $this->request->getPost();
        if (permission_access("Setting", "add_convenience_fee") && strtolower($this->whitelabel_setting['payment_gateway_name']) != 'default') {
            $validate = new Validation();
            $rules = $this->validate($validate->convenience_fee_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerConvenienceFeeModel = new WebPartnerConvenienceFeeModel();
                $data = $this->request->getPost();
                 
                $data['agent_class_id'] = ($data['convenience_fee_for'] == 'B2C') ? NULL : implode(',', $data['agent_class_id']);
                $data['service'] = implode(',',$data['service']); 
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();

                $added_data = $WebPartnerConvenienceFeeModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Convenience Fee Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Convenience Fee Successfully not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
               
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_convenience_view()
    {
        if (permission_access("Setting", "edit_convenience_fee")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $WebPartnerConvenienceFeeModel = new WebPartnerConvenienceFeeModel();
            $details = $WebPartnerConvenienceFeeModel->convenience_details($id,$this->web_partner_id);
            $AgentClassModel = new AgentClassModel();
            $AgentClass =   $AgentClassModel->agent_class_list($this->web_partner_id);
            $details['service'] = explode(',',$details['service']);
            $payment_gateway = array();
            if(strtolower($this->whitelabel_setting['payment_gateway_name']) != 'default'){
                array_push($payment_gateway,$this->whitelabel_setting['payment_gateway_name']);
            }
            $details['agent_class_id'] = explode(',',$details['agent_class_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $AgentClass,
                'payment_gateway' => $payment_gateway
            ];
            $blog_details = view('Modules\ConvenienceFee\Views\edit-convenience', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_convenience()
    {
        if (permission_access("Setting", "edit_convenience_fee")) 
        {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();

            $rules = $this->validate($validate->convenience_fee_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebPartnerConvenienceFeeModel = new WebPartnerConvenienceFeeModel();
                $data = $this->request->getPost();
                $data['service'] = implode(',',$data['service']);
                $data['agent_class_id'] = ($data['convenience_fee_for'] == 'B2C') ? NULL : implode(',', $data['agent_class_id']);
                $data['modified'] = create_date();
                $added_data = $WebPartnerConvenienceFeeModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Convenience Fee Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Convenience Fee Not  Updated", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
       } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
          }
    }

    public function remove_convenience()
    {
        if (permission_access_error("Setting", "delete_convenience_fee")) {
            $WebPartnerConvenienceFeeModel = new WebPartnerConvenienceFeeModel();
            $ids = $this->request->getPost('checklist');
            $delete = $WebPartnerConvenienceFeeModel->remove_convenience($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Convenience Fee Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Convenience Fee Not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
          }
    }

}