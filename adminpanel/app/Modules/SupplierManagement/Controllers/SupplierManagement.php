<?php

namespace Modules\SupplierManagement\Controllers;

use App\Modules\SupplierManagement\Models\ApiSupplierFlightMgtModel;
use App\Controllers\BaseController;
use Modules\SupplierManagement\Config\Validation;


class SupplierManagement extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Supplier Management";

        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
    }

    public function index()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active"){
            $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $ApiSupplierFlightMgtModel->search_data($this->request->getGet());
            } else {
                $lists = $ApiSupplierFlightMgtModel->supplier_mgt_list();
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "SupplierManagement\Views/flight-api-supplier-mgt-list",
                'pager' => $ApiSupplierFlightMgtModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ]; 

            return view('template/sidebar-layout', $data);
        }else{
            access_denied();
        }
    }

    public function add_flight_api_mgt_template()
    {
        //if (permission_access_error("Feedback", "add_feedback")) {
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();
        $data = [
            'title' => $this->title,
            'supplier_list' => $supplier_list
        ];
        $add_blog_view = view('Modules\SupplierManagement\Views\add-flight-api-mgt', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }

    public function apiFlightSupplierMgtTemplate()
    {
        //if (permission_access_error("Feedback", "add_feedback")) {
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();

        $api_account_id = $ApiSupplierFlightMgtModel->api_account_id();
        $api_account_group = [];
        if ($api_account_id){
            foreach ($api_account_id as $account_id){
                $api_supplier_name = $account_id['api_supplier_name'];
                unset($account_id['api_supplier_name']);
                $api_account_group[$api_supplier_name][]=$account_id;
            }
        }

        $data = [
            'title' => $this->title,
            'supplier_list' => $supplier_list,
            'api_account_group'=> $api_account_group
        ];
        $add_blog_view = view('Modules\SupplierManagement\Views\api-flight-supplier-mgt', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }


    public function apiFlightSupplierMgt()
    {


        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $apisupplierdata = $this->request->getPost();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();
        foreach ($supplier_list as $supplier) {
            $status = "active";
            if (!in_array($supplier['id'], $apisupplierdata['api_supplier'])) {
                $status = "inactive";
            }
            $data['flight'] = $status;
            $ApiSupplierFlightMgtModel->updateData("api_supplier", ["id" => $supplier['id']], $data);
        }



        if (isset($apisupplierdata['api_account_group']) && !empty($apisupplierdata['api_account_group'])){
            foreach ($apisupplierdata['api_account_group'] as $group_key => $api_account) {
                foreach ($api_account as $account_key => $account) {

                    $primary_id = $account['id'];
                    unset($account['id']);
                    $ApiSupplierFlightMgtModel->updateData('api_account_id',['id'=>$primary_id],$account);
                }
            }
        }





        $message = array("StatusCode" => 0, "Message" => "data successfully updated", "Class" => "success_popup");
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);

        //}
    }


    public function add_flight_api_mgt()
    {
        //if (permission_access_error("Feedback", "add_feedback")) {
        $data = $this->request->getPost();
        $validate = new Validation();

        /*   if ($data['allowed_airline']=='' && $data['excluded_airline']==''){
               $validate->flight_api_supllier_mgt_validation['allowed_airline']['rules'] = "required";
           }*/

        $rules = $this->validate($validate->flight_api_supllier_mgt_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
                $message = array("StatusCode" => 2, "Message" => "please select atlest one allowed airline or excluded airline", "Class" => "error_popup");
                return $this->response->setJSON($message);
            } else {

                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();


                $allowed_airline_array = explode(',', $data['allowed_airline']);
                $airline_code = "";
                foreach ($allowed_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['allowed_airline'] = substr_replace($airline_code, "", -2);


                $excluded_airline_array = explode(',', $data['excluded_airline']);
                $airline_code = "";
                foreach ($excluded_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['excluded_airline'] = substr_replace($airline_code, "", -2);

                if (isset($data['fare_type'])) {
                    $data['fare_type'] = implode(',', $data['fare_type']);
                }


                $data['created'] = create_date();

                $added_data = $ApiSupplierFlightMgtModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "data successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "data not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        //}
    }

    public function edit_flight_api_mgt_template()
    {
        //if (permission_access_error("Feedback", "edit_feedback")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $supplier_list = $ApiSupplierFlightMgtModel->supplier_list();

        $details = $ApiSupplierFlightMgtModel->details($id);

        if ($details['allowed_airline']) {
            $details['allowed_airline'] = $details['allowed_airline'] . ',';
        }

        if ($details['excluded_airline']) {
            $details['excluded_airline'] = $details['excluded_airline'] . ',';
        }
        $details['fare_type'] = explode(',', $details['fare_type']);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'supplier_list' => $supplier_list,
            'details' => $details,
        ];
        $blog_details = view('Modules\SupplierManagement\Views\edit-flight-api-mgt', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        //}
    }


    public function edit_flight_api_mgt()
    {

        //if (permission_access_error("Feedback", "edit_feedback")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $data = $this->request->getPost();
        $validate = new Validation();

        unset($validate->flight_api_supllier_mgt_validation['status']);

        /*if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
            $validate->flight_api_supllier_mgt_validation['allowed_airline']['rules'] = "required";
        }*/

        $rules = $this->validate($validate->flight_api_supllier_mgt_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {

            if ($data['allowed_airline'] == '' && $data['excluded_airline'] == '') {
                $message = array("StatusCode" => 2, "Message" => "please select atlest one allowed airline or excluded airline", "Class" => "error_popup");
                return $this->response->setJSON($message);
            } else {

                $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();


                $allowed_airline_array = explode(',', $data['allowed_airline']);
                $airline_code = "";
                foreach ($allowed_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['allowed_airline'] = substr_replace($airline_code, "", -2);


                $excluded_airline_array = explode(',', $data['excluded_airline']);
                $airline_code = "";
                foreach ($excluded_airline_array as $airline) {
                    $airline_data = explode('-', $airline);
                    $airline_code .= $airline_data[0] . ',';
                }
                $data['excluded_airline'] = substr_replace($airline_code, "", -2);

                if (isset($data['fare_type'])) {
                    $data['fare_type'] = implode(',', $data['fare_type']);
                }
                $data['modified'] = create_date();
                $added_data = $ApiSupplierFlightMgtModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "data successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "data not updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        //}
    }


    public function remove_flight_api_mgt()
    {
        //if (permission_access_error("Feedback", "delete_feedback")) {
        $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
        $ids = $this->request->getPost('checklist');
        $delete = $ApiSupplierFlightMgtModel->remove($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "record successfully Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "record  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }

    public function flight_api_mgt_status_change()
    {

        //if (permission_access_error("Feedback", "feedback_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $ApiSupplierFlightMgtModel = new ApiSupplierFlightMgtModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $ApiSupplierFlightMgtModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => " status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => " status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

}