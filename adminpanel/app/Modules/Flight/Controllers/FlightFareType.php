<?php

namespace Modules\Flight\Controllers;

use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;
use App\Modules\Flight\Models\FlightFareTypeModel;

class FlightFareType extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight Fare Type";

        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];

    }

    public function index()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            if (permission_access_error("Setting", "flight_fare_type_list")) 
            {

                $FlightFareTypeModel = new FlightFareTypeModel();

                if ($this->request->getGet() && $this->request->getGet('key')) {
                    $lists = $FlightFareTypeModel->search_data($this->request->getGet());
                } else {
                    $lists = $FlightFareTypeModel->flight_fare_type_list();
                }
                $data = [
                    'title' => $this->title,
                    'lists' => $lists,
                    'view' => 'Flight\Views\FlightFareType\flight-faretype-list',
                    'pager' => $FlightFareTypeModel->pager,
                    'search_bar_data' => $this->request->getGet(),
                ];
                return view('template/sidebar-layout', $data);
            }
        } else {
            access_denied();
        }
    }

    public function add_faretype_template()
    {
        if (permission_access("Setting", "add_flight_fare_type")) {
            $FlightFareTypeModel = new FlightFareTypeModel();
            $api_supplier = $FlightFareTypeModel->api_supplier_list();

            $data = [
                'api_supplier' => $api_supplier,
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightFareType\add-fare-type', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_faretype()
    {
        if (permission_access("Setting", "add_flight_fare_type")) {
            $validate = new Validation();
            $rules = $this->validate($validate->api_fairtype_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightFareTypeModel = new FlightFareTypeModel();
                $data = $this->request->getPost();
                $data['color'] = 'yellow';
                $added_data = $FlightFareTypeModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "API Fare Type Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "API Fare Type not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_faretype_view()
    {
        if (permission_access("Setting", "edit_flight_fare_type")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightFareTypeModel = new FlightFareTypeModel();
            $api_supplier = $FlightFareTypeModel->api_supplier_list();
            $data = [
                'title' => $this->title,
                'api_supplier' => $api_supplier,
                'id' => $id,
                'details' => $FlightFareTypeModel->flight_fare_type_details($id),
            ];
            $blog_details = view('Modules\Flight\Views\FlightFareType\edit-faretype', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_faretype()
    {
        if (permission_access("Setting", "edit_flight_fare_type")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();
            $validate->api_fairtype_validation['supplier_fare_type']['rules'] = "required|is_unique[api_flight_fare_type.supplier_fare_type,id,$id]";
            $rules = $this->validate($validate->api_fairtype_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightFareTypeModel = new FlightFareTypeModel();
                $data = $this->request->getPost();
                $added_data = $FlightFareTypeModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "API Fare Type Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "API Fare Type not  Updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_faretype()
    {
        if (permission_access("Setting", "delete_flight_fare_type")) {
            $FlightFareTypeModel = new FlightFareTypeModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightFareTypeModel->remove_fare_type($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "API Fare Type Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "API Fare Type not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

}