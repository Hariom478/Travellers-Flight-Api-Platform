<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightOfflineModel;
use App\Modules\Flight\Models\ApiSupplierModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class FlightOffline extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight Offline"; 

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
        // if (permission_access_error("FlightOffline", "FlightOffline_Module")) {

        // }

    }

    public function index(): string
    {

        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active"){
            $FlightOfflineModel = new FlightOfflineModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightOfflineModel->search_data($this->request->getGet(),$this->web_partner_id);
            } else {
                $lists = $FlightOfflineModel->flight_offline_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Flight\Views\FlightOffline\offline-flight-list",
                'pager' => $FlightOfflineModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }else{
            access_denied();
        }
    }

    public function flight_offline_view()
    {
        //if (permission_access_error("FlightOffline", "add_flight_offline")) {

            $ApiSupplierModel = new ApiSupplierModel();
            $supplier_list = $ApiSupplierModel->supplier_list('flight');
           
            $data = [
                'title' => $this->title,
                'supplier_list' => $supplier_list
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightOffline\add-flight-offline', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
       // }
    }


    public function flight_offline()
    {
        //if (permission_access_error("FlightOffline", "add_flight_offline")) {
            $validate = new Validation();
            $rules = $this->validate($validate->flight_offline);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if(isset($errors['departure_days.*'])){
                    $errors['departure_days[]'] = $errors['departure_days.*'];
                    unset($errors['departure_days.*']);
                }
                if(isset($errors['supplier.*'])){
                    $errors['supplier[]'] = $errors['supplier.*'];
                    unset($errors['supplier.*']);
                }
                if(isset($errors['faretype.*'])){
                    $errors['faretype[]'] = $errors['faretype.*'];
                    unset($errors['faretype.*']);
                }
                if(isset($errors['cabin_class.*'])){
                    $errors['cabin_class[]'] = $errors['cabin_class.*'];
                    unset($errors['cabin_class.*']);
                }
                if(isset($errors['is_domestic.*'])){
                    $errors['is_domestic[]'] = $errors['is_domestic.*'];
                    unset($errors['is_domestic.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightOfflineModel = new FlightOfflineModel();
                $data = $this->request->getPost();

                $temp_airline = $this->request->getPost('airline_code');
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                $tts_is_hold = $this->request->getPost('tts_is_hold');

                if ($tts_is_hold == 'Hold') {
                    $data['is_hold'] = 'Hold';
                    $data['is_offline'] = '';
                } else {
                    $data['is_offline'] = 'Pending';
                    $data['is_hold'] = '';
                }
                unset($data['tts_is_hold']);

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $data['supplier'] = implode(',', $data['supplier']);
                $data['is_domestic'] = implode(',', $data['is_domestic']);
                $data['faretype'] = implode(',', $data['faretype']);
                $data['cabin_class'] = implode(',', $data['cabin_class']);
                $added_data = $FlightOfflineModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Offline Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Offline not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        //}
    }

    public function edit_flight_offline_view()
    {
        // if (permission_access_error("FlightOffline", "edit_flight_offline")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightOfflineModel = new FlightOfflineModel();

            $ApiSupplierModel = new ApiSupplierModel();
            $supplier_list = $ApiSupplierModel->supplier_list('flight');
            $details = $FlightOfflineModel->flight_offline_details($id,$this->web_partner_id);
            $details['supplier'] = explode(',', $details['supplier']);
            $details['faretype'] = explode(',', $details['faretype']);
            $details['cabin_class'] = explode(',', $details['cabin_class']);
            $details['is_domestic'] = explode(',', $details['is_domestic']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'supplier_list'=>$supplier_list
            ];
            $blog_details = view('Modules\Flight\Views\FlightOffline\edit-flight-offline', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        // }
    }


    public function edit_flight_offline()
    {

        //if (permission_access_error("FlightOffline", "edit_flight_offline")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->flight_offline);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if(isset($errors['supplier.*'])){
                    $errors['supplier[]'] = $errors['supplier.*'];
                    unset($errors['supplier.*']);
                }
                if(isset($errors['faretype.*'])){
                    $errors['faretype[]'] = $errors['faretype.*'];
                    unset($errors['faretype.*']);
                }
                if(isset($errors['cabin_class.*'])){
                    $errors['cabin_class[]'] = $errors['cabin_class.*'];
                    unset($errors['cabin_class.*']);
                }
                if(isset($errors['is_domestic.*'])){
                    $errors['is_domestic[]'] = $errors['is_domestic.*'];
                    unset($errors['is_domestic.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightOfflineModel = new FlightOfflineModel();
                $data = $this->request->getPost();
                $temp_airline = $this->request->getPost('airline_code');
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }

                $tts_is_hold = $this->request->getPost('tts_is_hold');

                if ($tts_is_hold == 'Hold') {
                    $data['is_hold'] = 'Hold';
                    $data['is_offline'] = '';
                } else {
                    $data['is_offline'] = 'Pending';
                    $data['is_hold'] = '';
                }
                unset($data['tts_is_hold']);
                $data['supplier'] = implode(',', $data['supplier']);
                $data['is_domestic'] = implode(',', $data['is_domestic']);
                $data['faretype'] = implode(',', $data['faretype']);
                $data['cabin_class'] = implode(',', $data['cabin_class']);
                $data['modified'] = create_date();
                $added_data = $FlightOfflineModel->where(["id"=>$id,'web_partner_id'=>$this->web_partner_id])->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Offline Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Offline not  Updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        //}
    }

    public function remove_flight_offline()
    {
        //if (permission_access_error("FlightOffline", "delete_flight_offline")) {
            $FlightOfflineModel = new FlightOfflineModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightOfflineModel->remove_flight_offline($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Flight Offline Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Offline  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        //}
    }

    public function flight_offline_status_change()
    {
        //if (permission_access_error("FlightOffline", "flight_offline_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightOfflineModel = new FlightOfflineModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $FlightOfflineModel->status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Offline status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Offline status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        //}
    }

}