<?php

namespace Modules\FlightTopRoutes\Controllers;

use App\Modules\FlightTopRoutes\Models\FlightTopRoutesModel; 
use App\Modules\Flight\Models\FlightAirportModel;
use App\Controllers\BaseController;
use Modules\FlightTopRoutes\Config\Validation;

class FlightTopRoutes extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight to Routes";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
		$this->web_partner_details = admin_cookie_data()['admin_user_details'];
		$this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
		$this->user_id = admin_cookie_data()['admin_user_details']['id'];

        if (permission_access_error("Flight", "Flight_Module")) {

        }
 
    }


    public function index(): string
    { 

        if (permission_access_error("Flight", "flight_top_routes_List")) {
        $FlightTopRoutesModel = new FlightTopRoutesModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightTopRoutesModel->search_data($this->request->getGet(),$this->web_partner_id); 
        } else { 
            $lists = $FlightTopRoutesModel->flight_top_route_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists, 
            'view' => "FlightTopRoutes\Views\Flight-top-Routes-list",
            'pager' => $FlightTopRoutesModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }
        
    }

    public function get_airports()
    {

        $terms = $this->request->getGet('term');
        $terms = explode(',', $terms);
        $terms = end($terms);

        $FlightAirportModel = new FlightAirportModel();

        $get_airport = $FlightAirportModel->get_airport_autosuggestion($terms);
        $availableAirport = [];
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirport[] = ['city' => $data['city_name'], 'airport_code' => $data['code'], 'label' => $data['city_name'] . ' (' . $data['code'] . '), ' . ucfirst(strtolower($data['country_name'])) . '', 'airport_name' => $data['name'], 'country_code' => $data['country_code'], 'country_name' => ucfirst(strtolower($data['country_name']))];
            }
        }
        echo json_encode($availableAirport);
    } 

    public function remove_top_routes_List()
    {

        if (permission_access_error("Flight", "remove_top_routes_List")) {
        $FlightTopRoutesModel = new FlightTopRoutesModel();
        $ids = $this->request->getPost('checklist');
        foreach ($ids as $id) {
            $delete = $FlightTopRoutesModel->remove_top_routes_list($id,$this->web_partner_id);
        }
        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Flight Top Routes successfully  deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Flight Top Routes not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);

    }
    }


    public function flight_top_routes_status_change()
    {

        if (permission_access_error("Flight", "flight_top_routes_status_change")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightTopRoutesModel = new FlightTopRoutesModel();
            $ids = $this->request->getPost('checkedvalue');
            $data['status'] = $this->request->getPost('status');
            $update = $FlightTopRoutesModel->status_change($ids, $data,$this->web_partner_id);
            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Flight Top Routes status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Top Routes status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
    }


    public function FlightTopRoutesListView()
    { 
        if (permission_access_error("Flight", "add_flight_top_routes")) {
        $data = [
            'title' => $this->title, 
        ];
        $add_blog_view = view('Modules\FlightTopRoutes\Views\add-flight-top-routes', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }
       
    }

    public function add_FlightTopRoutes_Saved()
    { 

        if (permission_access_error("Flight", "add_flight_top_routes")) {
        $data = $this->request->getPost(); 
        $validate = new Validation(); 
        $rules = $this->validate($validate->flight_top_routes_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightTopRoutesModel = new FlightTopRoutesModel();

            $data = $this->request->getPost();

            $data['origin_code'] = $this->request->getPost('origin_code');
            $data['web_partner_id'] = $this->web_partner_id;
            $data['destination_code'] = $this->request->getPost('destination_code');
            $added_data = $FlightTopRoutesModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Top Routes Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Top Routes not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }

    }
         
    }

    public function edit_flight_top_routes_view()
    {

        if (permission_access_error("Flight", "edit_flight_top_routes")) {
        $id = dev_decode($this->request->uri->getSegment(3));
    
        $FlightTopRoutesModel = new FlightTopRoutesModel();
        $details = $FlightTopRoutesModel->flight_top_routus_details($id,$this->web_partner_id); 
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        
        $details = view('Modules\FlightTopRoutes\Views\edit-flight-top-routes', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);

    }
    }
    
    public function edit_flight_top_routes_Seved()
    {

        if (permission_access_error("Flight", "edit_flight_top_routes")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $rules = $this->validate($validate->flight_top_routes_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightTopRoutesModel = new FlightTopRoutesModel();
            $data = $this->request->getPost(); 
            
            $added_data = $FlightTopRoutesModel->where(["id"=>$id,'web_partner_id'=>$this->web_partner_id])->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Top Routes successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Top Routes not  updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
}

}
