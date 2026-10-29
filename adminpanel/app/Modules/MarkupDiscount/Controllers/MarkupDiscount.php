<?php
namespace Modules\MarkupDiscount\Controllers;
use App\Modules\MarkupDiscount\Models\FlightDiscountModel;
use App\Modules\MarkupDiscount\Models\FlightMarkupModel;
use App\Modules\MarkupDiscount\Models\FlightAirportModel;
use App\Modules\MarkupDiscount\Models\FlightAirlineModel;
use App\Modules\MarkupDiscount\Models\CarMarkupModel;
use App\Modules\MarkupDiscount\Models\CarDiscountModel;
use App\Modules\MarkupDiscount\Models\BusMarkupModel;
use App\Modules\MarkupDiscount\Models\BusDiscountModel;
use App\Modules\MarkupDiscount\Models\HolidayMarkupModel;
use App\Modules\MarkupDiscount\Models\HolidayDiscountModel;
use App\Modules\MarkupDiscount\Models\HotelMarkupModel;
use App\Modules\MarkupDiscount\Models\HotelDiscountModel;
use App\Modules\MarkupDiscount\Models\VisaMarkupModel;
use App\Modules\MarkupDiscount\Models\VisaDiscountModel;
use App\Modules\MarkupDiscount\Models\CruiseDiscountModel;
use App\Modules\MarkupDiscount\Models\CruiseMarkupModel;
use App\Modules\Cruise\Models\CruiseLineModel;
use App\Modules\Cruise\Models\CruisePortModel;
use App\Modules\Cruise\Models\CruiseShipModel;
use App\Modules\Visa\Models\VisaCountryModel;
use App\Modules\Visa\Models\VisaListModel;
use App\Modules\Cruise\Models\CruiseCabinModel;
use App\Modules\MarkupDiscount\Models\AgentClassModel;
use App\Controllers\BaseController;
use Modules\MarkupDiscount\Config\Validation;
/* class MarkupDiscount extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Admin";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
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
    public function get_airline()
    {
        $terms = $this->request->getGet('term');
        $FlightAirlineModel = new FlightAirlineModel();
        $get_airport = $FlightAirlineModel->get_airline_autosuggestion($terms);
        $availableAirline = [];
        $availableAirline[] = 'ANY' . '-' . 'Any Airline';
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
            }
        }
        echo json_encode($availableAirline);
    }

    public function flight_markup(): string
    {
       
        //if (permission_access_error("Flight", "flight_markup_list")) {
        $FlightMarkupModel = new FlightMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightMarkupModel->search_data($this->request->getGet());
        } else {
            $lists = $FlightMarkupModel->markup_list($this->web_partner_id);
        }
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'list' => $lists,
            "agent_class_list" => $agent_class_list,
            'view' => "MarkupDiscount\Views\Flight-markup-list",
            'pager' => $FlightMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}
    }

    public function flight_markup_view()
    {
        //if (permission_access_error("Flight", "add_flight_markup")) {
        $AgentClassModel = new AgentClassModel();
        $FlightMarkupModel = new FlightMarkupModel();
        $data = [
            'title' => $this->title,
            'agent_class' => $AgentClassModel->agent_class_list($this->web_partner_id),
            'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
        ];
        $add_blog_view = view('Modules\MarkupDiscount\Views\add-flight-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }

    public function add_markup()
    {

        // if (permission_access_error("Flight", "add_flight_markup")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();
            $temp_airline = $data['airline_code'];
            $temp_airline = explode('-', $temp_airline);
            $data['airline_code'] = $temp_airline[0];
            $data['airline_name'] = $temp_airline[1];
            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }
            $data['web_partner_id'] = $this->web_partner_id;
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);
            $added_data = $FlightMarkupModel->insert($data);
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Added", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }
    }

    public function edit_markup_view()
    {
        //if (permission_access_error("Flight", "edit_flight_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightMarkupModel = new FlightMarkupModel();
        $details = $FlightMarkupModel->markup_details($id);
        if (isset($details['travel_date_from']) && $details['travel_date_from'] != '') {
            $details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
        }
        if (isset($details['travel_date_to']) && $details['travel_date_to'] != '') {
            $details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
        }
        $AgentClassModel = new AgentClassModel();
        $details['is_domestic'] = explode(',', $details['is_domestic']);
        $details['journey_type'] = explode(',', $details['journey_type']);
        $details['cabin_class'] = explode(',', $details['cabin_class']);
        $details['faretype'] = explode(',', $details['faretype']);
        $data['web_partner_id'] = $this->web_partner_id;
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),   
            'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
        ];
        $blog_details = view('Modules\MarkupDiscount\Views\edit-flight-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        //}
    }

    public function edit_markup()
    {
        //if (permission_access_error("Flight", "edit_flight_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
        }
        $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();

            $temp_airline = $this->request->getPost('airline_code');
            $temp_airline = explode('-', $temp_airline);
            if (isset($temp_airline[0])) {
                $data['airline_code'] = $temp_airline[0];
            }
            if (isset($temp_airline[1])) {
                $data['airline_name'] = $temp_airline[1];
            }
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }
            $data['web_partner_id'] = $this->web_partner_id;
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);
            $data['modified'] = create_date();
            $added_data = $FlightMarkupModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Updated", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Updated", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function markup_status_change()
    {
        //if (permission_access_error("Flight", "flight_markup_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $FlightMarkupModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "FlightMarkup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "FlightMarkup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }
    }

    public function remove_markup()
    {
        //if (permission_access_error("Flight", "delete_flight_markup")) {
        $FlightMarkupModel = new FlightMarkupModel();
        $ids = $this->request->getPost('checklist');
        $delete = $FlightMarkupModel->remove_markup($ids);
        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "FlightMarkup Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "FlightMarkup  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }


    public function flight_markup_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightMarkupModel = new FlightMarkupModel();
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'id' => $id,
            'agent_class_list' => $agent_class_list,
            'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
            'details' => $FlightMarkupModel->markup_details($id),
        ];
        $details = view('Modules\MarkupDiscount\Views\markup-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
} */
class MarkupDiscount extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Admin";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
    }
    public function flight_discount(): string
    {
        //if (permission_access_error("Flight", "flight_discount_list")) {
        $FlightDiscountModel = new FlightDiscountModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightDiscountModel->search_data($this->request->getGet());
        } else {
            $lists = $FlightDiscountModel->discount_list();
        }
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'agent_class_list' => $agent_class_list,
            'view' => "MarkupDiscount\Views\Flight-discount-list",
            'pager' => $FlightDiscountModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}
    }

    public function flight_discount_view()
    {
        //if (permission_access_error("Flight", "add_flight_discount")) {
        $CarMarkupModel = new CarMarkupModel();
        $AgentClassModel = new AgentClassModel();
        $data = [
            'title' => $this->title,
            'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
        ];
        $add_blog_view = view('Modules\MarkupDiscount\Views\add-flight-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        // }
    }

    public function add_discount()
    {
        //if (permission_access_error("Flight", "add_flight_discount")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        if ($data['extra_discount']) {
            $validate->flight_discount_markup_validation['extra_discount']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightDiscountModel = new FlightDiscountModel();

            $temp_airline = $data['airline_code'];
            $temp_airline = explode('-', $temp_airline);
            if (isset($temp_airline[0])) {
                $data['airline_code'] = $temp_airline[0];
            }
            if (isset($temp_airline[0])) {
                $data['airline_name'] = $temp_airline[1];
            }
            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }

            $data['agent_class'] = implode(',', $data['agent_class']);
            $data['web_partner_id'] = $this->web_partner_id;
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);

            $added_data = $FlightDiscountModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Discount Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Discount not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function edit_discount_view()
    {
        //if (permission_access_error("Flight", "edit_flight_discount")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightDiscountModel = new FlightDiscountModel();
        $details = $FlightDiscountModel->discount_details($id);
        $AgentClassModel =  new AgentClassModel();
        if (isset($details['travel_date_from']) && $details['travel_date_from'] != '') {
            $details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
        }

        if (isset($details['travel_date_to']) && $details['travel_date_to'] != '') {
            $details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
        }

        $data['agent_class'] = implode(',', $details['agent_class']);
        $details['is_domestic'] = explode(',', $details['is_domestic']);
        $details['journey_type'] = explode(',', $details['journey_type']);
        $details['cabin_class'] = explode(',', $details['cabin_class']);
        $details['faretype'] = explode(',', $details['faretype']);


        $CarMarkupModel = new CarMarkupModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
        ];
        $blog_details = view('Modules\MarkupDiscount\Views\edit-flight-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        // }
    }

    public function edit_discount()
    {
        //if (permission_access_error("Flight", "edit_flight_discount")) {
        $data = $this->request->getPost();
        $id = dev_decode($this->request->uri->getSegment(3));

        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        if ($data['extra_discount']) {
            $validate->flight_discount_markup_validation['extra_discount']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $validate->flight_discount_markup_validation['discount_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightDiscountModel = new FlightDiscountModel();

            $temp_airline = $this->request->getPost('airline_code');
            $temp_airline = explode('-', $temp_airline);
            if (isset($temp_airline[0])) {
                $data['airline_code'] = $temp_airline[0];
            }
            if (isset($temp_airline[1])) {
                $data['airline_name'] = $temp_airline[1];
            }
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }

            $data['agent_class'] = implode(',', $data['agent_class']);
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);
            $data['modified'] = create_date();
            $added_data = $FlightDiscountModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Discount Successfully Updated", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Discount not  Updated", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function discount_status_change()
    {
        //if (permission_access_error("Flight", "flight_discount_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightDiscountModel = new FlightDiscountModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $FlightDiscountModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "FlightDiscount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "FlightDiscount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function remove_discount()
    {
        // if (permission_access_error("Flight", "flight_discount_status")) {
        $FlightDiscountModel = new FlightDiscountModel();
        $ids = $this->request->getPost('checklist');
        $delete = $FlightDiscountModel->remove_discount($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "FlightDiscount Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "FlightDiscount  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        // }
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

    public function get_airline()
    {
        $terms = $this->request->getGet('term');
        $FlightAirlineModel = new FlightAirlineModel();

        $get_airport = $FlightAirlineModel->get_airline_autosuggestion($terms);
        $availableAirline = [];
        $availableAirline[] = 'ANY' . '-' . 'Any Airline';
        if (!empty($get_airport)) {
            foreach ($get_airport as $data) {
                $availableAirline[] = $data['airline_code'] . '-' . $data['airline_name'];
            }
        }

        echo json_encode($availableAirline);
    }

    public function flight_discount_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));

        $FlightDiscountModel = new FlightDiscountModel();

        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'id' => $id,
            "agent_class_list" => $agent_class_list,
            'details' => $FlightDiscountModel->discount_details($id),
        ];
        $details = view('Modules\MarkupDiscount\Views\discount-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    public function flight_markup(): string
    {
       
        //if (permission_access_error("Flight", "flight_markup_list")) {
        $FlightMarkupModel = new FlightMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightMarkupModel->search_data($this->request->getGet());
        } else {
            $lists = $FlightMarkupModel->markup_list();
        }
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'list' => $lists,
            "agent_class_list" => $agent_class_list,
            'view' => "MarkupDiscount\Views\Flight-markup-list",
            'pager' => $FlightMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}
    }

    public function flight_markup_view()
    {
        //if (permission_access_error("Flight", "add_flight_markup")) {
        $CarMarkupModel = new CarMarkupModel();
        
        $data = [
            'title' => $this->title,
            'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
           
        ];
        $add_blog_view = view('Modules\MarkupDiscount\Views\add-flight-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }

    public function add_markup()
    {

        // if (permission_access_error("Flight", "add_flight_markup")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();

            $temp_airline = $data['airline_code'];
            $temp_airline = explode('-', $temp_airline);
            $data['airline_code'] = $temp_airline[0];
            $data['airline_name'] = $temp_airline[1];
            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }
            $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
            $data['supplier'] = implode(',', $data['supplier']);
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);


            $added_data = $FlightMarkupModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }
    }

    public function edit_markup_view()
    {
        //if (permission_access_error("Flight", "edit_flight_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightMarkupModel = new FlightMarkupModel();
        $details = $FlightMarkupModel->markup_details($id);

        if (isset($details['travel_date_from']) && $details['travel_date_from'] != '') {
            $details['travel_date_from'] = timestamp_to_date($details['travel_date_from']);
        }

        if (isset($details['travel_date_to']) && $details['travel_date_to'] != '') {
            $details['travel_date_to'] = timestamp_to_date($details['travel_date_to']);
        }
        $CarMarkupModel = new CarMarkupModel();
        

        $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
        $details['supplier'] = explode(',', $details['supplier']);
        $details['is_domestic'] = explode(',', $details['is_domestic']);
        $details['journey_type'] = explode(',', $details['journey_type']);
        $details['cabin_class'] = explode(',', $details['cabin_class']);
        $details['faretype'] = explode(',', $details['faretype']);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id),
           
        ];
        $blog_details = view('Modules\MarkupDiscount\Views\edit-flight-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        //}
    }

    public function edit_markup()
    {
        //if (permission_access_error("Flight", "edit_flight_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->flight_discount_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        if ($data['max_limit']) {
            $validate->flight_discount_markup_validation['max_limit']['rules'] = 'trim|numeric';
        }
        $validate->flight_discount_markup_validation['markup_type']['rules'] = 'trim|required';
        $rules = $this->validate($validate->flight_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();

            $temp_airline = $this->request->getPost('airline_code');
            $temp_airline = explode('-', $temp_airline);
            if (isset($temp_airline[0])) {
                $data['airline_code'] = $temp_airline[0];
            }
            if (isset($temp_airline[1])) {
                $data['airline_name'] = $temp_airline[1];
            }
            if ($data['travel_date_from']) {
                $data['travel_date_from'] = strtotime($data['travel_date_from']);
            }
            if ($data['travel_date_to']) {
                $data['travel_date_to'] = strtotime($data['travel_date_to']);
            }

            $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
            $data['supplier'] = implode(',', $data['supplier']);
            $data['is_domestic'] = implode(',', $data['is_domestic']);
            $data['journey_type'] = implode(',', $data['journey_type']);
            $data['cabin_class'] = implode(',', $data['cabin_class']);
            $data['faretype'] = implode(',', $data['faretype']);
            $data['modified'] = create_date();

            $added_data = $FlightMarkupModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Updated", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Updated", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function markup_status_change()
    {
        //if (permission_access_error("Flight", "flight_markup_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FlightMarkupModel = new FlightMarkupModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $FlightMarkupModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "FlightMarkup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "FlightMarkup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }
    }

    public function remove_markup()
    {
        //if (permission_access_error("Flight", "delete_flight_markup")) {
        $FlightMarkupModel = new FlightMarkupModel();
        $ids = $this->request->getPost('checklist');
        $delete = $FlightMarkupModel->remove_markup($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "FlightMarkup Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "FlightMarkup  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }


    public function flight_markup_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));

        $FlightMarkupModel = new FlightMarkupModel();
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
        $data = [
            'title' => $this->title,
            'id' => $id,
            'agent_class_list' => $agent_class_list,
            'details' => $FlightMarkupModel->markup_details($id),
        ];
        $details = view('Modules\MarkupDiscount\Views\markup-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function car_markup_list()
    {
        if (permission_access_error("CarExtranet", "car_markup_list")) {
            $CarMarkupModel = new CarMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CarMarkupModel->search_data($this->request->getGet());
            } else {
                $lists = $CarMarkupModel->car_markup_list();
            }

            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\car-markup-list",
                'pager' => $CarMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function car_markup_view()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {

            $CarMarkupModel = new CarMarkupModel();
            $data = [
                'title' => $this->title,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-car-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_car_markup()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {
            $validate = new Validation();
            $rules = $this->validate($validate->car_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarMarkupModel = new CarMarkupModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();

                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);

                $added_data = $CarMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Car Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Car Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }

    }

    public function car_markup_status_change()
    {
        if (permission_access_error("CarExtranet", "car_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarMarkupModel = new CarMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CarMarkupModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Car Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Car Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_car_markup_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $CarMarkupModel = new CarMarkupModel();
            $details = $CarMarkupModel->car_markup_details($id);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $AgentClassModel->agent_class_list($this->web_partner_id)
            ];

            $details = view('Modules\MarkupDiscount\Views\edit-car-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_car_markup()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->car_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarMarkupModel = new CarMarkupModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $CarMarkupModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "car markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "car markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_car_markup()
    {
        if (permission_access_error("CarExtranet", "delete_car_markup")) {
            $CarMarkupModel = new CarMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $CarMarkupModel->remove_markup($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "car markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "car markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function car_discount_list()
    {
        if (permission_access_error("CarExtranet", "car_discount_list")) {
            $CarDiscountModel = new CarDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CarDiscountModel->search_data($this->request->getGet());
            } else {
                $lists = $CarDiscountModel->car_discount_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\car-discount-list",
                'pager' => $CarDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function car_discount_view()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {

            $CarDiscountModel = new CarDiscountModel();
            $data = [
                'title' => $this->title,
                'agent_class_list' => $CarDiscountModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-car-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_car_discount()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->car_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->car_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            $rules = $this->validate($validate->car_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarDiscountModel = new CarDiscountModel();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['created'] = create_date();
                $added_data = $CarDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Car Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Car Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function car_discount_status_change()
    {
        if (permission_access_error("CarExtranet", "car_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarDiscountModel = new CarDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CarDiscountModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "car discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "car discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_car_discount_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $CarDiscountModel = new CarDiscountModel();
            $details = $CarDiscountModel->car_discount_details($id);

            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $CarDiscountModel->agent_class_list($this->web_partner_id)

            ];

            $details = view('Modules\MarkupDiscount\Views\edit-car-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_car_discount()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->car_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->car_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            $rules = $this->validate($validate->car_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CarDiscountModel = new CarDiscountModel();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['modified'] = create_date();

                $added_data = $CarDiscountModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "car discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "car discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_car_discount()
    {
        if (permission_access_error("CarExtranet", "delete_car_discount")) {
            $CarDiscountModel = new CarDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $CarDiscountModel->remove_discount($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "car discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "car discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function bus_markup_list()
    {
        if (permission_access_error("CarExtranet", "car_markup_list")) {
            $BusMarkupModel = new BusMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $BusMarkupModel->search_data($this->request->getGet());
            } else {
                $lists = $BusMarkupModel->bus_markup_list();
            }

            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\bus-markup-list",
                'pager' => $BusMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function bus_markup_view()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {

            $BusMarkupModel = new BusMarkupModel();
            $data = [
                'title' => $this->title,
                'agent_class_list' => $BusMarkupModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-bus-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_bus_markup()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {
            $validate = new Validation();
            $rules = $this->validate($validate->bus_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusMarkupModel = new BusMarkupModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $BusMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Bus Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bus Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }

    }

    public function bus_markup_status_change()
    {
        if (permission_access_error("CarExtranet", "car_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusMarkupModel = new BusMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $BusMarkupModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Bus Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bus Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_bus_markup_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $BusMarkupModel = new BusMarkupModel();
            $details = $BusMarkupModel->bus_markup_details($id);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $BusMarkupModel->agent_class_list($this->web_partner_id)
            ];

            $details = view('Modules\MarkupDiscount\Views\edit-bus-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_bus_markup()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->bus_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusMarkupModel = new BusMarkupModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $BusMarkupModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "bus markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "bus markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_bus_markup()
    {
        if (permission_access_error("CarExtranet", "delete_car_markup")) {
            $BusMarkupModel = new BusMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $BusMarkupModel->remove_markup($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "bus markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "bus markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function bus_discount_list()
    {
        if (permission_access_error("CarExtranet", "car_discount_list")) {
            $BusDiscountModel = new BusDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $BusDiscountModel->search_data($this->request->getGet());
            } else {
                $lists = $BusDiscountModel->bus_discount_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\bus-discount-list",
                'pager' => $BusDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function bus_discount_view()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {

            $BusDiscountModel = new BusDiscountModel();
            $data = [
                'title' => $this->title,
                'agent_class_list' => $BusDiscountModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-bus-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_bus_discount()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->bus_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->bus_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            $rules = $this->validate($validate->bus_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusDiscountModel = new BusDiscountModel();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['created'] = create_date();
                $added_data = $BusDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Bus Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Bus Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function bus_discount_status_change()
    {
        if (permission_access_error("CarExtranet", "car_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusDiscountModel = new BusDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $BusDiscountModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "bus discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "bus discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_bus_discount_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $BusDiscountModel = new BusDiscountModel();
            $details = $BusDiscountModel->bus_discount_details($id);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $BusDiscountModel->agent_class_list($this->web_partner_id)

            ];

            $details = view('Modules\MarkupDiscount\Views\edit-bus-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_bus_discount()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['extra_discount']) {
                $validate->bus_discount_validation['extra_discount']['rules'] = 'trim|numeric';
                $validate->bus_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
            }
            $rules = $this->validate($validate->bus_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $BusDiscountModel = new BusDiscountModel();

                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $BusDiscountModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "bus discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "bus discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_bus_discount()
    {
        if (permission_access_error("CarExtranet", "delete_car_discount")) {
            $BusDiscountModel = new BusDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $BusDiscountModel->remove_discount($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "bus discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "bus discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function hotel_markup_list()
    {
        if (permission_access_error("CarExtranet", "car_markup_list")) {
            $HotelMarkupModel = new HotelMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HotelMarkupModel->search_data($this->request->getGet());
            } else {
                $lists = $HotelMarkupModel->hotel_markup_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list' => $agent_class_list,
                'view' => "MarkupDiscount\Views\hotel-markup-list",
                'pager' => $HotelMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function hotel_markup_view()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {

            $HotelMarkupModel = new HotelMarkupModel();
            
            $data = [
                'title' => $this->title,
                'agent_class_list' => $HotelMarkupModel->agent_class_list($this->web_partner_id),
                'ApiSupplierModel' => $ApiSupplierModel->supplier_list()
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-hotel-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_hotel_markup()
    {
        if (permission_access_error("CarExtranet", "add_car_markup")) {
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $HotelMarkupModel = new HotelMarkupModel();

                /*  $get_data = $HotelMarkupModel->where('region_type', $data['region_type'])->where('star_rating', $data['star_rating'])->where('agent_class_list_id', $data['agent_class_list_id'])->first();

                  if ($get_data) {
                      $message = array("StatusCode" => 2, "Message" => "Similar data already exists ", "Class" => "error_popup");
                      $this->session->setFlashdata('Message', $message);
                      return $this->response->setJSON($message);
                  } else {*/
                $data['created'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['supplier'] = implode(',', $data['supplier']);
                $data['star_rating'] = implode(',', $data['star_rating']);
                $data['region_type'] = implode(',', $data['region_type']);
                $added_data = $HotelMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
                /*}*/
            }
        }

    }

    public function hotel_markup_status_change()
    {
        if (permission_access_error("CarExtranet", "car_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelMarkupModel = new HotelMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HotelMarkupModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_hotel_markup_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            
            $HotelMarkupModel = new HotelMarkupModel();
            $details = $HotelMarkupModel->hotel_markup_details($id);


            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $details['supplier'] = explode(',', $details['supplier']);
            $details['region_type'] = explode(',', $details['region_type']);
            $details['star_rating'] = explode(',', $details['star_rating']);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $HotelMarkupModel->agent_class_list($this->web_partner_id),
                'ApiSupplierModel' => $ApiSupplierModel->supplier_list()
            ];

            $details = view('Modules\MarkupDiscount\Views\edit-hotel-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_hotel_markup()
    {
        if (permission_access_error("CarExtranet", "edit_car_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelMarkupModel = new HotelMarkupModel();
                $data = $this->request->getPost();

              

                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['supplier'] = implode(',', $data['supplier']);
                $data['star_rating'] = implode(',', $data['star_rating']);
                $data['region_type'] = implode(',', $data['region_type']);
                $data['modified'] = create_date();

                $added_data = $HotelMarkupModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
                /*}*/
            }
        }
    }

    public function remove_hotel_markup()
    {
        if (permission_access_error("CarExtranet", "delete_car_markup")) {
            $HotelMarkupModel = new HotelMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HotelMarkupModel->remove_markup($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "hotel markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function hotel_discount_list()
    {
        if (permission_access_error("CarExtranet", "car_discount_list")) {
            $HotelDiscountModel = new HotelDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HotelDiscountModel->search_data($this->request->getGet());
            } else {
                $lists = $HotelDiscountModel->hotel_discount_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list=  array_column($agent_class_list,'class_name','id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\hotel-discount-list",
                'pager' => $HotelDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function hotel_discount_view()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {
            
            $HotelDiscountModel = new HotelDiscountModel();
            $data = [
                'title' => $this->title,
                'agent_class_list' => $HotelDiscountModel->agent_class_list($this->web_partner_id),
                'ApiSupplierModel' => $ApiSupplierModel->supplier_list()
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-hotel-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_hotel_discount()
    {
        if (permission_access_error("CarExtranet", "add_car_discount")) {
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();


                $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
                $data['supplier'] = implode(',',$data['supplier']);
                $data['region_type'] = implode(',',$data['region_type']);

                $added_data = $HotelDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function hotel_discount_status_change()
    {
        if (permission_access_error("CarExtranet", "car_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HotelDiscountModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "hotel discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_hotel_discount_template()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $HotelDiscountModel = new HotelDiscountModel();
            $details = $HotelDiscountModel->hotel_discount_details($id);


            $details['agent_class_list_id'] = explode(',',$details['agent_class_list_id']);
            $details['supplier'] = explode(',',$details['supplier']);
            $details['region_type'] = explode(',',$details['region_type']);

            
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'agent_class_list' => $HotelDiscountModel->agent_class_list($this->web_partner_id),
                'ApiSupplierModel' => $ApiSupplierModel->supplier_list()

            ];

            $details = view('Modules\MarkupDiscount\Views\edit-hotel-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_hotel_discount()
    {
        if (permission_access_error("CarExtranet", "edit_car_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelDiscountModel = new HotelDiscountModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
                $data['supplier'] = implode(',',$data['supplier']);
                $data['region_type'] = implode(',',$data['region_type']);
                $added_data = $HotelDiscountModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_hotel_discount()
    {
        if (permission_access_error("CarExtranet", "delete_car_discount")) {
            $HotelDiscountModel = new HotelDiscountModel();
            $ids = $this->request->getPost('checklist');



            $delete = $HotelDiscountModel->remove_discount($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "hotel discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function visa_markup_list()
    {
        if (permission_access_error("Visa", "visa_markup_list")) {
            $VisaMarkupModel = new VisaMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $VisaMarkupModel->search_data($this->request->getGet());
            } else {
                $lists = $VisaMarkupModel->visa_markup_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');


            $visa_type_list = $VisaMarkupModel->visa_type_list();
            $visa_type_list = array_column($visa_type_list, 'visa_title', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'visa_type_list'=>$visa_type_list,
                'view' => "MarkupDiscount\Views\visa-markup-list",
                'pager' => $VisaMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function visa_markup_view()
    {
        if (permission_access_error("Visa", "add_visa_markup")) {
            $VisaCountryModel = new VisaCountryModel();
            $VisaMarkupModel = new VisaMarkupModel();
            $data = [
                'title' => $this->title,
                'country' => $VisaCountryModel->get_country_code(),
                'agent_class_list' => $VisaMarkupModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-visa-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_visa_markup()
    {
        if (permission_access_error("Visa", "add_visa_markup")) {
            $validate = new Validation();
            $rules = $this->validate($validate->visa_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaMarkupModel = new VisaMarkupModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['visa_type_id'] = implode(',', $data['visa_type_id']);
                $added_data = $VisaMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Visa Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Visa Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }

    }

    public function visa_markup_status_change()
    {
        if (permission_access_error("Visa", "visa_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaMarkupModel = new VisaMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $VisaMarkupModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Visa Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Visa Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_visa_markup_template()
    {
        if (permission_access_error("Visa", "edit_visa_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $VisaCountryModel = new VisaCountryModel();

            $country = $VisaCountryModel->get_country_code();
            $VisaMarkupModel = new VisaMarkupModel();
            $details = $VisaMarkupModel->visa_markup_details($id);

            $VisaListModel = new VisaListModel();
            $country_id = $details['visa_country_id'];
            $visa_list = $VisaListModel->visa_list_page_select($country_id);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $details['visa_type_id'] = explode(',', $details['visa_type_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'country' => $country,
                'visa_list' => $visa_list,
                'agent_class_list' => $VisaMarkupModel->agent_class_list($this->web_partner_id)

            ];

            $details = view('Modules\MarkupDiscount\Views\edit-visa-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_visa_markup()
    {
        if (permission_access_error("Visa", "edit_visa_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->visa_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaMarkupModel = new VisaMarkupModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['visa_type_id'] = implode(',', $data['visa_type_id']);
                $added_data = $VisaMarkupModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "visa markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "visa markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_visa_markup()
    {
        if (permission_access_error("Visa", "delete_visa_markup")) {
            $VisaMarkupModel = new VisaMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $VisaMarkupModel->remove_markup($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "visa markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "visa markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function visa_discount_list()
    {
        if (permission_access_error("Visa", "visa_discount_list")) {
            $VisaDiscountModel = new VisaDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $VisaDiscountModel->search_data($this->request->getGet());
            } else {
                $lists = $VisaDiscountModel->visa_discount_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');

            $visa_type_list = $VisaDiscountModel->visa_type_list();
            $visa_type_list = array_column($visa_type_list, 'visa_title', 'id');

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'visa_type_list'=>$visa_type_list,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views/visa-discount-list",
                'pager' => $VisaDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }

    public function visa_discount_view()
    {
        if (permission_access_error("Visa", "add_visa_discount")) {
            $VisaCountryModel = new VisaCountryModel();
            $VisaDiscountModel = new VisaDiscountModel();
            $data = [
                'title' => $this->title,
                'country' => $VisaCountryModel->get_country_code(),
                'agent_class_list' => $VisaDiscountModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-visa-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_visa_discount()
    {
        if (permission_access_error("Visa", "add_visa_discount")) {
            $validate = new Validation();
            $rules = $this->validate($validate->visa_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaDiscountModel = new VisaDiscountModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['visa_type_id'] = implode(',', $data['visa_type_id']);
                $added_data = $VisaDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Visa Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Visa Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function visa_discount_status_change()
    {
        if (permission_access_error("Visa", "visa_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaDiscountModel = new VisaDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $VisaDiscountModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Visa Discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Visa Discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_visa_discount_template()
    {
        if (permission_access_error("Visa", "edit_visa_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $VisaCountryModel = new VisaCountryModel();

            $country = $VisaCountryModel->get_country_code();
            $VisaDiscountModel = new VisaDiscountModel();
            $details = $VisaDiscountModel->visa_discount_details($id);

            $VisaListModel = new VisaListModel();
            $country_id = $details['visa_country_id'];
            $visa_list = $VisaListModel->visa_list_page_select($country_id);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);

            $details['visa_type_id'] = explode(',', $details['visa_type_id']);


            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'country' => $country,
                'visa_list' => $visa_list,
                'agent_class_list' => $VisaDiscountModel->agent_class_list($this->web_partner_id)

            ];

            $details = view('Modules\MarkupDiscount\Views\edit-visa-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }

    }

    public function edit_admin_visa_discount()
    {
        if (permission_access_error("Visa", "edit_visa_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->visa_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $VisaDiscountModel = new VisaDiscountModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $data['visa_type_id'] = implode(',', $data['visa_type_id']);
                $added_data = $VisaDiscountModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "visa discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "visa discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_visa_discount()
    {
        if (permission_access_error("Visa", "delete_visa_discount")) {
            $VisaDiscountModel = new VisaDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $VisaDiscountModel->remove_discount($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "visa discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "visa discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function cruise_markup_list()
    {
        if (permission_access_error("Cruise", "cruise_markup_list")) {
            $CruiseMarkupModel = new CruiseMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseMarkupModel->search_data($this->request->getGet());
            } else {
                $lists = $CruiseMarkupModel->cruise_markup_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            // pr($lists);
            // pr($agent_class_list);exit;
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\cruise-markup-list",
                'pager' => $CruiseMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }

    }


    public function cruise_markup_view()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();

            $CruiseMarkupModel = new CruiseMarkupModel();
            $data = [
                'title' => $this->title,
                'cruise_line' => $CruiseLineModel->cruise_line_select(),
                'cruise_port' => $CruisePortModel->cruise_port_select_all(),
                'agent_class_list' => $CruiseMarkupModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-cruise-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_markup()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseMarkupModel = new CruiseMarkupModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $CruiseMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function cruise_markup_status_change()
    {
        if (permission_access_error("Cruise", "cruise_markup_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseMarkupModel = new CruiseMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CruiseMarkupModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_cruise_markup_template()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();

            $CruiseMarkupModel = new CruiseMarkupModel();
            $details = $CruiseMarkupModel->cruise_markup_details($id);

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select_cruise_line($details['cruise_line_id']);

            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select_ship_id($details['cruise_ship_id']);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_ship' => $cruise_ship,
                'cruise_cabin' => $cruise_cabin,
                'cruise_line' => $CruiseLineModel->cruise_line_select(),
                'cruise_port' => $CruisePortModel->cruise_port_select_all(),
                'agent_class_list' => $CruiseMarkupModel->agent_class_list($this->web_partner_id)
            ];

            $details = view('Modules\MarkupDiscount\Views\edit-cruise-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_cruise_markup()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseMarkupModel = new CruiseMarkupModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $CruiseMarkupModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_markup()
    {
        if (permission_access_error("Cruise", "delete_cruise_markup")) {
            $CruiseMarkupModel = new CruiseMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $CruiseMarkupModel->remove_markup($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function cruise_discount_list()
    {
        if (permission_access_error("Cruise", "cruise_discount_list")) {
            $CruiseDiscountModel = new CruiseDiscountModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $CruiseDiscountModel->search_data($this->request->getGet());
            } else {
                $lists = $CruiseDiscountModel->cruise_discount_list();
            }
            $AgentClassModel = new AgentClassModel();
            $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
            $agent_class_list = array_column($agent_class_list, 'class_name', 'id');
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'agent_class_list'=>$agent_class_list,
                'view' => "MarkupDiscount\Views\cruise-discount-list",
                'pager' => $CruiseDiscountModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function cruise_discount_view()
    {
        if (permission_access_error("Cruise", "add_cruise_discount")) {
            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();

            $CruiseMarkupModel = new CruiseMarkupModel();
            $data = [
                'title' => $this->title,
                'cruise_line' => $CruiseLineModel->cruise_line_select(),
                'cruise_port' => $CruisePortModel->cruise_port_select_all(),
                'agent_class_list' => $CruiseMarkupModel->agent_class_list($this->web_partner_id)
            ];
            $add_blog_view = view('Modules\MarkupDiscount\Views\add-cruise-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_cruise_discount()
    {
        if (permission_access_error("Cruise", "add_cruise_discount")) {
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseDiscountModel = new CruiseDiscountModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();

                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);

                $added_data = $CruiseDiscountModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Discount Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Discount not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function cruise_discount_status_change()
    {
        if (permission_access_error("Cruise", "cruise_discount_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseDiscountModel = new CruiseDiscountModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CruiseDiscountModel->status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Cruise Discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Cruise Discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_admin_cruise_discount_template()
    {
        if (permission_access_error("Cruise", "edit_cruise_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));


            $CruiseLineModel = new CruiseLineModel();
            $CruisePortModel = new CruisePortModel();

            $CruiseDiscountModel = new CruiseDiscountModel();
            $details = $CruiseDiscountModel->cruise_discount_details($id);

            $CruiseShipModel = new CruiseShipModel();
            $cruise_ship = $CruiseShipModel->cruise_ship_select_cruise_line($details['cruise_line_id']);

            $CruiseCabinModel = new CruiseCabinModel();
            $cruise_cabin = $CruiseCabinModel->cruise_cabin_select_ship_id($details['cruise_ship_id']);
            $details['agent_class_list_id'] = explode(',', $details['agent_class_list_id']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'cruise_ship' => $cruise_ship,
                'cruise_cabin' => $cruise_cabin,
                'cruise_line' => $CruiseLineModel->cruise_line_select(),
                'cruise_port' => $CruisePortModel->cruise_port_select_all(),
                'agent_class_list' => $CruiseDiscountModel->agent_class_list($this->web_partner_id)
            ];


            $details = view('Modules\MarkupDiscount\Views\edit-cruise-discount', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_cruise_discount()
    {
        if (permission_access_error("Cruise", "edit_cruise_discount")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->cruise_discount_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CruiseDiscountModel = new CruiseDiscountModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $data['agent_class_list_id'] = implode(',', $data['agent_class_list_id']);
                $added_data = $CruiseDiscountModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "cruise discount successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "cruise discount not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_cruise_discount()
    {
        if (permission_access_error("Cruise", "delete_cruise_discount")) {
            $CruiseDiscountModel = new CruiseDiscountModel();
            $ids = $this->request->getPost('checklist');

            $delete = $CruiseDiscountModel->remove_discount($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "cruise discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "cruise discount not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function holiday_markup_list()
    {
        //if (permission_access_error("CarExtranet", "car_markup_list")) {
        $HolidayMarkupModel = new HolidayMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HolidayMarkupModel->search_data($this->request->getGet());
        } else {
            $lists = $HolidayMarkupModel->holiday_markup_list();
        }
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list=  array_column($agent_class_list,'class_name','id');
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'agent_class_list'=>$agent_class_list,
            'view' => "MarkupDiscount\Views\holiday-markup-list",
            'pager' => $HolidayMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }

    //}

    public function holiday_markup_view()
    {
        //if (permission_access_error("CarExtranet", "add_car_markup")) {

        $HolidayMarkupModel = new HolidayMarkupModel();
        $data = [
            'title' => $this->title,
            'agent_class_list' => $HolidayMarkupModel->agent_class_list($this->web_partner_id)
        ];
        $add_blog_view = view('Modules\MarkupDiscount\Views\add-holiday-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }

    public function add_holiday_markup()
    {
        // if (permission_access_error("CarExtranet", "add_car_markup")) {
        $validate = new Validation();
        $rules = $this->validate($validate->holiday_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayMarkupModel = new HolidayMarkupModel();
            $data = $this->request->getPost();

            $data['created'] = create_date();
            $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
            $added_data = $HolidayMarkupModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Holiday Markup Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Holiday Markup not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }

    }

    public function holiday_markup_status_change()
    {
        // if (permission_access_error("CarExtranet", "car_markup_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayMarkupModel = new HolidayMarkupModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $HolidayMarkupModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Holiday Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Holiday Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }


    public function edit_admin_holiday_markup_template()
    {
        //if (permission_access_error("CarExtranet", "edit_car_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));

        $HolidayMarkupModel = new HolidayMarkupModel();
        $details = $HolidayMarkupModel->holiday_markup_details($id);
        $details['agent_class_list_id'] = explode(',',$details['agent_class_list_id']);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'agent_class_list' => $HolidayMarkupModel->agent_class_list($this->web_partner_id)
        ];

        $details = view('Modules\MarkupDiscount\Views\edit-holiday-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        // }

    }

    public function edit_admin_holiday_markup()
    {
        // if (permission_access_error("CarExtranet", "edit_car_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $rules = $this->validate($validate->holiday_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayMarkupModel = new HolidayMarkupModel();
            $data = $this->request->getPost();

            $data['modified'] = create_date();

            $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
            $added_data = $HolidayMarkupModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "holiday markup successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday markup not updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        // }
    }

    public function remove_holiday_markup()
    {
        //if (permission_access_error("CarExtranet", "delete_car_markup")) {
        $HolidayMarkupModel = new HolidayMarkupModel();
        $ids = $this->request->getPost('checklist');

        $delete = $HolidayMarkupModel->remove_markup($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "holiday markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "holiday markup not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }


    public function holiday_discount_list()
    {
        //if (permission_access_error("CarExtranet", "car_discount_list")) {
        $HolidayDiscountModel = new HolidayDiscountModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HolidayDiscountModel->search_data($this->request->getGet());
        } else {
            $lists = $HolidayDiscountModel->holiday_discount_list();
        }
        $AgentClassModel = new AgentClassModel();
        $agent_class_list = $AgentClassModel->agent_class_list($this->web_partner_id);
        $agent_class_list=  array_column($agent_class_list,'class_name','id');
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'agent_class_list'=>$agent_class_list,
            'view' => "MarkupDiscount\Views\holiday-discount-list",
            'pager' => $HolidayDiscountModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}

    }

    public function holiday_discount_view()
    {
        //if (permission_access_error("CarExtranet", "add_car_discount")) {

        $HolidayDiscountModel = new HolidayDiscountModel();
        $data = [
            'title' => $this->title,
            'agent_class_list' => $HolidayDiscountModel->agent_class_list($this->web_partner_id)
        ];
        $add_blog_view = view('Modules\MarkupDiscount\Views\add-holiday-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}
    }

    public function add_holiday_discount()
    {
        //if (permission_access_error("CarExtranet", "add_car_discount")) {
        $validate = new Validation();
        $data = $this->request->getPost();
        if ($data['extra_discount']) {
            $validate->holiday_discount_validation['extra_discount']['rules'] = 'trim|numeric';
            $validate->holiday_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $rules = $this->validate($validate->holiday_discount_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayDiscountModel = new HolidayDiscountModel();
            $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
            $data['created'] = create_date();

            $added_data = $HolidayDiscountModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Holiday Discount Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Holiday Discount not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function holiday_discount_status_change()
    {
        //if (permission_access_error("CarExtranet", "car_discount_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayDiscountModel = new HolidayDiscountModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');

            $update = $HolidayDiscountModel->status_change($ids, $data);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "holiday discount status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday discount status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }


    public function edit_admin_holiday_discount_template()
    {
        //if (permission_access_error("CarExtranet", "edit_car_discount")) {
        $id = dev_decode($this->request->uri->getSegment(3));

        $HolidayDiscountModel = new HolidayDiscountModel();
        $details = $HolidayDiscountModel->holiday_discount_details($id);

        $details['agent_class_list_id'] = explode(',',$details['agent_class_list_id']);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'agent_class_list' => $HolidayDiscountModel->agent_class_list($this->web_partner_id)

        ];

        $details = view('Modules\MarkupDiscount\Views\edit-holiday-discount', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        //}

    }

    public function edit_admin_holiday_discount()
    {
        //if (permission_access_error("CarExtranet", "edit_car_discount")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $data = $this->request->getPost();
        if ($data['extra_discount']) {
            $validate->holiday_discount_validation['extra_discount']['rules'] = 'trim|numeric';
            $validate->holiday_discount_validation['extra_discount']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $rules = $this->validate($validate->holiday_discount_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HolidayDiscountModel = new HolidayDiscountModel();
            $data['agent_class_list_id'] = implode(',',$data['agent_class_list_id']);
            $data['modified'] = create_date();

            $added_data = $HolidayDiscountModel->where("id", $id)->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "holiday discount successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday discount not updated", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        //}
    }

    public function remove_holiday_discount()
    {
        //if (permission_access_error("CarExtranet", "delete_car_discount")) {
        $HolidayDiscountModel = new HolidayDiscountModel();
        $ids = $this->request->getPost('checklist');

        $delete = $HolidayDiscountModel->remove_discount($ids);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "holiday discount successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "holiday discount not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }


}