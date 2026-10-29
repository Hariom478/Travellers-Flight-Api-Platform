<?php

namespace Modules\AdminMarkupDiscount\Controllers;


use App\Modules\AdminMarkupDiscount\Models\FlightMarkupModel;
use App\Modules\AdminMarkupDiscount\Models\HotelMarkupModel;
use App\Modules\AdminMarkupDiscount\Models\BusMarkupModel;
use App\Modules\AdminMarkupDiscount\Models\CruiseMarkupModel;
use App\Modules\AdminMarkupDiscount\Models\HolidayMarkupModel;

use App\Modules\AdminMarkupDiscount\Models\FlightAirportModel;
use App\Modules\AdminMarkupDiscount\Models\FlightAirlineModel;

use App\Modules\AdminMarkupDiscount\Models\CruiseCabinModel;
use App\Modules\AdminMarkupDiscount\Models\CruiseLineModel;
use App\Modules\AdminMarkupDiscount\Models\CruisePortModel;
use App\Modules\AdminMarkupDiscount\Models\CruiseShipModel;

use App\Modules\AdminMarkupDiscount\Models\TourguideMarkupModel;

use App\Modules\AdminMarkupDiscount\Models\ActivitiesMarkupModel;

use App\Modules\AdminMarkupDiscount\Models\ApiSupplierModel;

use App\Controllers\BaseController;
use Modules\AdminMarkupDiscount\Config\Validation;


class AdminMarkupDiscount extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Agent";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_details = session()->get('wl_agent');
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_user_id = session()->get('wl_agent')['id'];
        /*  if (permission_access_error("Flight", "Flight_Module")) {

        } */
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
        if (permission_access_error("Flight", "flight_markup_list")) {
            $FlightMarkupModel = new FlightMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
            } else {
                $lists = $FlightMarkupModel->markup_list($this->web_partner_id, $this->wl_agent_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "AdminMarkupDiscount\Views\Flight-markup-list",
                'pager' => $FlightMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/default-layout', $data);
        }
    }

    public function flight_markup_view()
    {
        if (permission_access_error("Flight", "add_flight_markup")) {

            $FlightMarkupModel = new FlightMarkupModel();
            $data = [
                'title' => $this->title,
                'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
            ];
            $add_blog_view = view('Modules\AdminMarkupDiscount\Views\add-flight-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_markup()
    {

        if (permission_access_error("Flight", "add_flight_markup")) {
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
                if (isset($errors['is_domestic.*'])) {
                    $errors['is_domestic[]'] = $errors['is_domestic.*'];
                    unset($errors['is_domestic.*']);
                }
                if (isset($errors['journey_type.*'])) {
                    $errors['journey_type[]'] = $errors['journey_type.*'];
                    unset($errors['journey_type.*']);
                }
                if (isset($errors['cabin_class.*'])) {
                    $errors['cabin_class[]'] = $errors['cabin_class.*'];
                    unset($errors['cabin_class.*']);
                }
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightMarkupModel = new FlightMarkupModel();

                $temp_airline = $data['airline_code'];
                $temp_airline = explode('-', $temp_airline);
                $data['airline_code'] = $temp_airline[0];
                $data['airline_name'] = $temp_airline[1];
                $data['created'] = create_date();
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
                $data['created'] = create_date();
                $data['wl_agent_id'] = $this->wl_agent_id;
                $added_data = $FlightMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_markup_view()
    {
        if (permission_access_error("Flight", "edit_flight_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightMarkupModel = new FlightMarkupModel();
            $details = $FlightMarkupModel->markup_details($id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'ApiFlighFareType' => $FlightMarkupModel->getApiFlighFareType(),
            ];
            $blog_details = view('Modules\AdminMarkupDiscount\Views\edit-flight-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_markup()
    {
        if (permission_access_error("Flight", "edit_flight_markup")) {
            $id = dev_decode($this->request->uri->getSegment(3));
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
                if (isset($errors['is_domestic.*'])) {
                    $errors['is_domestic[]'] = $errors['is_domestic.*'];
                    unset($errors['is_domestic.*']);
                }
                if (isset($errors['journey_type.*'])) {
                    $errors['journey_type[]'] = $errors['journey_type.*'];
                    unset($errors['journey_type.*']);
                }
                if (isset($errors['cabin_class.*'])) {
                    $errors['cabin_class[]'] = $errors['cabin_class.*'];
                    unset($errors['cabin_class.*']);
                }
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

                $data['is_domestic'] = implode(',', $data['is_domestic']);
                $data['journey_type'] = implode(',', $data['journey_type']);
                $data['cabin_class'] = implode(',', $data['cabin_class']);
                $data['faretype'] = implode(',', $data['faretype']);


                $data['modified'] = create_date();

                $added_data = $FlightMarkupModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id, 'wl_agent_id' => $this->wl_agent_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Flight Markup Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Flight Markup not  Updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function markup_status_change()
    {
        if (permission_access_error("Flight", "flight_markup_status")) {
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
        }
    }

    public function remove_markup()
    {
        if (permission_access_error("Flight", "delete_flight_markup")) {
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
        }
    }


    public function flight_markup_details()
    {
        $id = dev_decode($this->request->uri->getSegment(3));

        $FlightMarkupModel = new FlightMarkupModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $FlightMarkupModel->markup_details($id),
        ];
        $details = view('Modules\AdminMarkupDiscount\Views\markup-details', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
    /**
     * This function shows hotel markup list to the agent
     */
    public function hotel_markup()
    {
        $HotelMarkupModel = new HotelMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $HotelMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
        } else {
            $lists = $HotelMarkupModel->markup_list($this->web_partner_id, $this->wl_agent_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "AdminMarkupDiscount\Views\Hotel-markup-list",
            'pager' => $HotelMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
    }
    /**
     * This function will show the add hotel markup view Modal Page 
     */
    public function hotel_markup_view()
    {
        if (permission_access_error("Hotel", "add_hotel_markup")) {

            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\AdminMarkupDiscount\Views\add-hotel-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }
    /**
     * This function add the data in the hotel markup database
     */
    public function add_hotel_markup()
    {
        if (permission_access_error("Hotel", "add_hotel_markup")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        $rules = $this->validate($validate->hotel_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            if (isset($errors['star_rating.*'])) {
                $errors['star_rating[]'] = $errors['star_rating.*'];
                unset($errors['star_rating.*']);
            }
            if (isset($errors['region_type.*'])) {
                $errors['region_type[]'] = $errors['region_type.*'];
                unset($errors['region_type.*']);
            }
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $HotelMarkupModel = new HotelMarkupModel();
            //condition of unique entry
            $data['web_partner_id'] = $this->web_partner_id;
            $data['star_rating'] = implode(',', $data['star_rating']);
            $data['region_type'] = implode(',', $data['region_type']);
            $data['created'] = create_date();
            $data['wl_agent_id'] = $this->wl_agent_id;
            $added_data = $HotelMarkupModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Hotel Markup Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel Markup not  Added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
    }

    public function hotel_markup_status_change()
    {
        if (permission_access_error("Hotel", "hotel_markup_status")) {
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

    public function edit_hotel_markup_view()
    {
        if (permission_access_error("Hotel", "edit_hotel_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $HotelMarkupModel = new HotelMarkupModel();
        $details = $HotelMarkupModel->markup_details($id);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $blog_details = view('Modules\AdminMarkupDiscount\Views\edit-hotel-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        }
    }

    public function edit_hotel_markup()
    {
        if (permission_access_error("hotel", "edit_hotel_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $rules = $this->validate($validate->hotel_discount_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            if (isset($errors['star_rating.*'])) {
                $errors['star_rating[]'] = $errors['star_rating.*'];
                unset($errors['star_rating.*']);
            }
            if (isset($errors['region_type.*'])) {
                $errors['region_type[]'] = $errors['region_type.*'];
                unset($errors['region_type.*']);
            }
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $data = $this->request->getPost();
            $HotelMarkupModel = new HotelMarkupModel();
            //condition of unique entry
            $data['star_rating'] = implode(',', $data['star_rating']);
            $data['region_type'] = implode(',', $data['region_type']);
            $data['modified'] = create_date();
            $added_data = $HotelMarkupModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id, 'wl_agent_id' => $this->wl_agent_id])->set($data)->update();

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Hotel Markup Successfully Updated", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel Markup not  Updated", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
    }

    public function remove_hotel_markup()
    {
        if (permission_access_error("Hotel", "delete_hotel_markup")) {
        $HotelMarkupModel = new HotelMarkupModel();
        $ids = $this->request->getPost('checklist');
        $delete = $HotelMarkupModel->remove_markup($ids, $this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Hotel Markup Successfully  Deleted", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Hotel Markup  not Deleted", "Class" => "error_popup");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        }
    }

    public function bus_markup_list()
    {
        //if (permission_access_error("CarExtranet", "car_markup_list")) {
        $BusMarkupModel = new BusMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $BusMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
        } else {
            $lists = $BusMarkupModel->bus_markup_list($this->web_partner_id, $this->wl_agent_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "AdminMarkupDiscount\Views\bus-markup-list",
            'pager' => $BusMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
        // }
    }

    public function bus_markup_view()
    {
        if (permission_access_error("Bus", "add_bus_markup")) {

        $BusMarkupModel = new BusMarkupModel();
        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\AdminMarkupDiscount\Views\add-bus-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }

    public function add_bus_markup()
    {
        if (permission_access_error("Bus", "add_bus_markup")) {
        $validate = new Validation();
        $rules = $this->validate($validate->bus_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $BusMarkupModel = new BusMarkupModel();
            $data = $this->request->getPost();
            $data['web_partner_id'] = $this->web_partner_id;
            $data['created'] = create_date();
            $data['wl_agent_id'] = $this->wl_agent_id;
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
        if (permission_access_error("Bus", "bus_markup_status")) {
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


    public function edit_bus_markup_template()
    {
        if (permission_access_error("Bus", "edit_bus_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));

        $BusMarkupModel = new BusMarkupModel();
        $details = $BusMarkupModel->bus_markup_details($id);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details
        ];

        $details = view('Modules\AdminMarkupDiscount\Views\edit-bus-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }


    public function edit_bus_markup()
    {
        if (permission_access_error("Bus", "edit_bus_markup")) {
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
            $added_data = $BusMarkupModel->where("id", $id)->where('web_partner_id', $this->web_partner_id)->set($data)->update();
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
        if (permission_access_error("Bus", "delete_bus_markup")) {
        $BusMarkupModel = new BusMarkupModel();
        $ids = $this->request->getPost('checklist');

        $delete = $BusMarkupModel->remove_markup($ids, $this->web_partner_id, $this->wl_agent_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "bus markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "bus markup not deleted", "Class" => "error_popup", "Reload" => "true");
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
            $lists = $CruiseMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
        } else {
            $lists = $CruiseMarkupModel->markup_list($this->web_partner_id, $this->wl_agent_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "AdminMarkupDiscount\Views/cruise/cruise-markup-list",
            'pager' => $CruiseMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
         }
    }

    public function cruise_markup_view()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
        $CruiseLineModel = new CruiseLineModel();
        $CruisePortModel = new CruisePortModel();
        $data = [
            'title' => $this->title,
            'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
            'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
        ];
        $add_blog_view = view('Modules\AdminMarkupDiscount\Views\cruise\add-cruise-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }

    public function add_cruise_markup()
    {
        if (permission_access_error("Cruise", "add_cruise_markup")) {
        $data = $this->request->getPost();
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->cruise_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->cruise_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $rules = $this->validate($validate->cruise_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CruiseMarkupModel = new CruiseMarkupModel();
            $data = $this->request->getPost();
            $data['created'] = create_date();
            $data['web_partner_id'] = $this->web_partner_id;
            $data['wl_agent_id'] = $this->wl_agent_id;
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

    public function edit_cruise_markup_template()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
        $id = dev_decode($this->request->uri->getSegment(3));

        $CruiseLineModel = new CruiseLineModel();
        $CruisePortModel = new CruisePortModel();
        $CruiseMarkupModel = new CruiseMarkupModel();
        $details = $CruiseMarkupModel->markup_details($id);


        $CruiseShipModel = new CruiseShipModel();
        $cruise_ship = $CruiseShipModel->cruise_ship_select_cruise_line($details['cruise_line_id']);

        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
            'cruise_ship' => $cruise_ship,
            'cruise_line' => $CruiseLineModel->cruise_line_select($this->web_partner_id),
            'cruise_port' => $CruisePortModel->cruise_port_select_all($this->web_partner_id),
        ];
        $details = view('Modules\AdminMarkupDiscount\Views\cruise\edit-cruise-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
         }
    }

    public function edit_cruise_markup()
    {
        if (permission_access_error("Cruise", "edit_cruise_markup")) {
        $data = $this->request->getPost();
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        if ($data['max_limit']) {
            $validate->cruise_markup_validation['max_limit']['rules'] = 'trim|numeric';
            $validate->cruise_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value'];
        }
        $rules = $this->validate($validate->cruise_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CruiseMarkupModel = new CruiseMarkupModel();

            $data['modified'] = create_date();
            $added_data = $CruiseMarkupModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id])->set($data)->update();
            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "Cruise markup successfully updated", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Cruise markup not updated", "Class" => "error_popup", "Reload" => "true");
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
            $data['modified'] = create_date();

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

    public function holiday_markup_list()
    {
        if (permission_access_error("Holiday", "holiday_markup_list")) {
            $HolidayMarkupModel = new HolidayMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HolidayMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
            } else {
                $lists = $HolidayMarkupModel->holiday_markup_list($this->web_partner_id, $this->wl_agent_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "AdminMarkupDiscount\Views\holiday\holiday-markup-list",
                'pager' => $HolidayMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/default-layout', $data);
        }
    }

    public function holiday_markup_view()
    {
        if (permission_access_error("Holiday", "holiday_markup_add")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\AdminMarkupDiscount\Views\holiday\add-holiday-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_holiday_markup()
    {
        if (permission_access("Holiday", "holiday_markup_add")) {
            $validate = new Validation();
            $data = $this->request->getPost();
            if ($data['markup_type'] == "percent") {
                $validate->holiday_markup_validation['max_limit']['rules'] = 'trim|required|numeric';
                $validate->holiday_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
            }
            $rules = $this->validate($validate->holiday_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayMarkupModel = new HolidayMarkupModel();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $data['wl_agent_id'] = $this->wl_agent_id;
                $data['theme_id'] = ($data['theme_name'] == "ANY") ? "ANY" : $data['theme_id'];
                $data['destination_id'] = ($data['destination_name'] == "ANY") ? "ANY" : $data['destination_id'];
                $data['holiday_package_id'] = ($data['holiday_package'] == "ANY") ? "ANY" : $data['holiday_package_id'];
                $added_data = $HolidayMarkupModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Markup Successfully Added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Markup not  Added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }

    public function edit_holiday_markup_template()
    {
        if (permission_access("Holiday", "holiday_markup_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));

            $HolidayMarkupModel = new HolidayMarkupModel();
            $details = $HolidayMarkupModel->holiday_markup_details($id, $this->web_partner_id, $this->wl_agent_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];

            $details = view('Modules\AdminMarkupDiscount\Views\holiday\edit-holiday-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
            return $this->response->setJSON($message);
        }
    }

    public function edit_holiday_markup()
    {
        if (permission_access("Holiday", "holiday_markup_edit")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $data = $this->request->getPost();
            $validate = new Validation();

            $rules = $this->validate($validate->holiday_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HolidayMarkupModel = new HolidayMarkupModel();
                $data['modified'] = create_date();
                $data['theme_id'] = ($data['theme_name'] == "ANY") ? "ANY" : $data['theme_id'];
                $data['destination_id'] = ($data['destination_name'] == "ANY") ? "ANY" : $data['destination_id'];
                $data['holiday_package_id'] = ($data['holiday_package'] == "ANY") ? "ANY" : $data['holiday_package_id'];

                $added_data = $HolidayMarkupModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "holiday markup successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "holiday markup not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function holiday_markup_status_change()
    {
        if (permission_access("Holiday", "holiday_markup_change_status")) {
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

                $update = $HolidayMarkupModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Holiday Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Holiday Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_holiday_markup()
    {
        if (permission_access("Holiday", "holiday_markup_delete")) {
            $HolidayMarkupModel = new HolidayMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $HolidayMarkupModel->remove_markup($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "holiday markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "holiday markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function tourguide_markup_list()
    {
        //    if (permission_access_error("Tourguide", "tourguide_markup_list")) {
        $TourguideMarkupModel = new TourguideMarkupModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $TourguideMarkupModel->search_data($this->web_partner_id, $this->wl_agent_id, $this->request->getGet());
        } else {
            $lists = $TourguideMarkupModel->tourguide_markup_list($this->web_partner_id, $this->wl_agent_id);
        }

        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "AdminMarkupDiscount\Views\\tourguide-markup-list",
            'pager' => $TourguideMarkupModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/default-layout', $data);
        //  }
    }

    public function tourguide_markup_view()
    {
        if (permission_access_error("TourGuide", "tourguide_markup_add")) {
        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\AdminMarkupDiscount\Views\add-tourguide-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }

    public function add_tourguide_markup()
    {
        if (permission_access("TourGuide", "tourguide_markup_add")) {
        $validate = new Validation();
        $data = $this->request->getPost();
        if ($data['markup_type'] == "percent") {
            $validate->tourguide_markup_validation['max_limit']['rules'] = 'trim|required|numeric';
            $validate->tourguide_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
        }
        $rules = $this->validate($validate->tourguide_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $TourguideMarkupModel = new TourguideMarkupModel();

            $data = $this->request->getPost();



            $data['created'] = create_date();
            $data['web_partner_id'] = $this->web_partner_id;
            $data['wl_agent_id'] = $this->wl_agent_id;
            $data['markup_for'] = "B2B";

            $added_data = $TourguideMarkupModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "TourGuide Markup Successfully Added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "TourGuide Markup not Added", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }

    public function edit_tourguide_markup_template()
    {
        if (permission_access("TourGuide", "tourguide_markup_edit")) {
        $id = dev_decode($this->request->uri->getSegment(3));

        $TourguideMarkupModel = new TourguideMarkupModel();
        $details = $TourguideMarkupModel->tourguide_markup_details($id, $this->web_partner_id, $this->wl_agent_id);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];

        $details = view('Modules\AdminMarkupDiscount\Views\edit-tourguide-markup', $data);
        $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
            } else {
                $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return $this->response->setJSON($message);
            }
    }

    public function edit_tourguide_markup()
    {
        if (permission_access("TourGuide", "tourguide_markup_edit")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $validate = new Validation();
        $data = $this->request->getPost();
        if ($data['markup_type'] == "percent") {
            $validate->tourguide_markup_validation['max_limit']['rules'] = 'trim|required|numeric';
            $validate->tourguide_markup_validation['max_limit']['errors'] = ['numeric' => 'Please enter numeric value', 'required' => 'Please enter max limit'];
        }
        $rules = $this->validate($validate->tourguide_markup_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $TourguideMarkupModel = new TourguideMarkupModel();

            $data = $this->request->getPost();
            $data['modified'] = create_date();
            $data['markup_for'] = "B2B";


            $added_data = $TourguideMarkupModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id])->set($data)->update();

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "TourGuide Markup Successfully Updated", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "TourGuide Markup not Updated", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }

    public function tourguide_markup_status_change()
    {
        if (permission_access("TourGuide", "tourguide_markup_change_status")) {
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $TourguideMarkupModel = new TourguideMarkupModel();
            $ids = $this->request->getPost('checkedvalue');

            $data['status'] = $this->request->getPost('status');


            $update = $TourguideMarkupModel->status_change($ids, $data, $this->web_partner_id);

            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "Tourguide Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Tourguide Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_tourguide_markup()
    {
        if (permission_access("TourGuide", "tourguide_markup_delete")) {
        $TourguideMarkupModel = new TourguideMarkupModel();
        $ids = $this->request->getPost('checklist');

        $delete = $TourguideMarkupModel->remove_markup($ids, $this->web_partner_id);

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Tourguide markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Tourguide markup not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }





    public function activities_markup_list()
    {
     
            $ActivitiesMarkupModel = new ActivitiesMarkupModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $ActivitiesMarkupModel->search_data($this->request->getGet(), $this->web_partner_id,$this->wl_agent_id);
            } else {
                $lists = $ActivitiesMarkupModel->activities_markup_list($this->web_partner_id,$this->wl_agent_id);
            }
           
            $data = [
                'title' => $this->title,
                'list' => $lists,
               
                'view' => "AdminMarkupDiscount\Views\activities-markup-list",
                'pager' => $ActivitiesMarkupModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

          
        return view('template/default-layout', $data);
       
    }

    public function activities_markup_view()
    {
     if(permission_access_error("Activities","activities_markup_add")){
            
            $data = [
                'title' => $this->title,
               
            ];
     
            $add_blog_view = view('Modules\AdminMarkupDiscount\Views\add-activities-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
       
    }
}

      

    public function add_activities_markup()
    {

        if (permission_access_error("Activities", "activities_markup_add")) {

    
    
            $data = $this->request->getPost();
            $validate = new Validation();

            // Remove agent_class validation if markup_for is B2C
           

            $rules = $this->validate($validate->activities_markup_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = ["StatusCode" => 1, "ErrorMessage" => array_filter($errors)];
                return $this->response->setJSON($data_validation);
            }

            // Handle "ANY" cases
           
            $data['destination_id'] = ($data['destination_name'] == "ANY") ? "ANY" : $data['destination_id'];

            // Set additional data before insertion
            $data['created'] = create_date();
            $data['web_partner_id'] = $this->web_partner_id;
            $data['wl_agent_id'] = $this->wl_agent_id;
         
 
            $ActivitiesMarkupModel = new ActivitiesMarkupModel();
            $added_data = $ActivitiesMarkupModel->insert($data);

            if ($added_data) {
                $message = ["StatusCode" => 0, "Message" => "Successful Addition of Markup for Activities", "Class" => "success_popup"];
            } else {
                $message = ["StatusCode" => 2, "Message" => "Incomplete Addition of Activities Markup. Kindly make another attempt.", "Class" => "error_popup"];
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
       
    }

    public function edit_admin_activities_markup_template()
    {
        if(permission_access_error("Activities","activities_markup_edit")){
            $id = dev_decode($this->request->uri->getSegment(3));

            $ActivitiesMarkupModel = new ActivitiesMarkupModel();
            $details = $ActivitiesMarkupModel->activities_markup_details($id, $this->web_partner_id,$this->wl_agent_id);
           

         
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
               
            ];

            $details = view('Modules\AdminMarkupDiscount\Views\edit-activities-markup', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_admin_activities_markup()
    {
        if(permission_access_error("Activities","activities_markup_edit")){
            $id = dev_decode($this->request->uri->getSegment(3));
            $data = $this->request->getPost();
            $validate = new Validation();

          
            $rules = $this->validate($validate->activities_markup_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $ActivitiesMarkupModel = new ActivitiesMarkupModel();
                $data['modified'] = create_date();
             

                // Handle "ANY" cases
              
                $data['destination_id'] = ($data['destination_name'] == "ANY") ? "ANY" : $data['destination_id'];

               
                $added_data = $ActivitiesMarkupModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id, "wl_agent_id" => $this->wl_agent_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Markup for Activities Successfully Updated. The activities markup has been successfully refreshed.", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Incomplete update of Activities Markup. Kindly attempt again. The Activities markup has not been updated.", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function activities_markup_status_change()
    {
        if(permission_access_error("Activities","activities_markup_change_status")){
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $ActivitiesMarkupModel = new ActivitiesMarkupModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $ActivitiesMarkupModel->status_change($ids, $data, $this->web_partner_id,$this->wl_agent_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Activities Markup status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Activities Markup status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
       
    }
    public function remove_activities_markup()
    {
        if(permission_access_error("Activities","activities_markup_delete")){
            $ActivitiesMarkupModel = new ActivitiesMarkupModel();
            $ids = $this->request->getPost('checklist');

            $delete = $ActivitiesMarkupModel->remove_markup($ids, $this->web_partner_id,$this->wl_agent_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Activities markup successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Activities markup not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
       
    }
}




}
