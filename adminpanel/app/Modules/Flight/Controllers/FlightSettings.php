<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightAirportModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class FlightSettings extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight Settings";

        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
       
     
    }

    public function index()
    { 
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active"){
            if (permission_access_error("Setting", "airport_list")) 
            {
                $FlightAirportModel = new FlightAirportModel();
                if ($this->request->getGet() && $this->request->getGet('key')) {
                    $lists = $FlightAirportModel->search_data($this->request->getGet());
                } else {
                    $lists = $FlightAirportModel->airport_list();
                }
                $data = [
                    'title' => $this->title,
                    'airport_list' => $lists,
                    'view' => "Flight\Views\FlightSettings\Flight-Airport-list",
                    'pager' => $FlightAirportModel->pager,
                    'search_bar_data' => $this->request->getGet(),
                ];
                return view('template/sidebar-layout', $data);
            }
        }else{
            access_denied();
        }
       
    }


    public function add_airport_template()
    {
        
        if (permission_access("Setting", "add_airport")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightSettings\add-airport', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_airport()
    {
        if (permission_access("Setting", "add_airport")) {
            $validate = new Validation();
            $rules = $this->validate($validate->airport_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirportModel = new FlightAirportModel();
                $data = $this->request->getPost();
                $added_data = $FlightAirportModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airport Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airport not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airport_view()
    {
        if (permission_access("Setting", "edit_airport")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightAirportModel = new FlightAirportModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FlightAirportModel->airport_details($id),
            ];
            $blog_details = view('Modules\Flight\Views\FlightSettings\edit-airport', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airports()
    {
        if (permission_access("Setting", "edit_airport")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();
            $validate->airport_validation['code']['rules'] = "required|is_unique[flight_airports.code,id,$id]";
            $rules = $this->validate($validate->airport_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirportModel = new FlightAirportModel();
                $data = $this->request->getPost();
                $added_data = $FlightAirportModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airport Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airport not  Updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_airport()
    {
        if (permission_access("Setting", "delete_airport")) {
            $FlightAirportModel = new FlightAirportModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightAirportModel->remove_airport($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Airport Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Airport not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function airlines_list()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active"){
            if (permission_access_error("Setting", "airlines_list")) {
                $FlightAirlineModel = new FlightAirlineModel();
                if ($this->request->getGet() && $this->request->getGet('key')) {
                    $lists = $FlightAirlineModel->search_data($this->request->getGet());
                } else {
                    $lists = $FlightAirlineModel->airline_list();
                }

                $data = [
                    'title' => $this->title,
                    'aiport_list' => $lists,
                    'view' => "Flight\Views\FlightSettings\Flight-Airline-list",
                    'pager' => $FlightAirlineModel->pager,
                    'search_bar_data' => $this->request->getGet(),
                ]; 
                return view('template/sidebar-layout', $data);
            }
        }else{
            access_denied();
        }
    }

    public function add_airline_template()
    {
        if (permission_access("Setting", "add_airlines")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightSettings\add-airline', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_airline()
    {
        if (permission_access("Setting", "add_airlines")) {
            $validate = new Validation();
            $rules = $this->validate($validate->airline_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirlineModel = new FlightAirlineModel();
                $data = $this->request->getPost();
                $added_data = $FlightAirlineModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airline Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airline not added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_airline_view()
    {
        if (permission_access("Setting", "edit_airlines")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightAirlineModel = new FlightAirlineModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FlightAirlineModel->airline_details($id),
            ];
            $blog_details = view('Modules\Flight\Views\FlightSettings\edit-airline', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airline()
    {
        if (permission_access("Setting", "edit_airlines")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $validate = new Validation();

            $validate->airline_validation['airline_code']['rules'] = "required|is_unique[flight_airline_code.airline_code,id,$id]";
            $rules = $this->validate($validate->airline_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirlineModel = new FlightAirlineModel();
                $data = $this->request->getPost();

                $added_data = $FlightAirlineModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airline Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airline not  Updated", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_airline()
    {
        if (permission_access("Setting", "delete_airlines")) {
            $FlightAirlineModel = new FlightAirlineModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightAirlineModel->remove_airline($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Airline Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Airline not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

}