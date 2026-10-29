<?php

namespace Modules\FlightExtranet\Controllers;


use App\Modules\FlightExtranet\Models\FlightCrsFareListModel;
use App\Modules\FlightExtranet\Models\FlightAirportModel;
use App\Modules\FlightExtranet\Models\FlightAirlineModel;
use App\Modules\FlightExtranet\Models\FlightCrsDetailsModel;
use App\Modules\FlightExtranet\Models\FlightCrsFareRuleModel;
use App\Modules\FlightExtranet\Models\FlightCrsFareRuleCriteriaModel;
use App\Modules\FlightExtranet\Models\FlightCrsRatePlanModel;
use App\Modules\FlightExtranet\Models\FlightCrsSeatAllocationModel;
use App\Controllers\BaseController;
use DateInterval;
use DatePeriod;
use DateTime;
use Modules\FlightExtranet\Config\Validation;


class FlightExtranet extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight Private Fare";

        $admin_cookie_data = admin_cookie_data();
        $this->web_partner_id = $admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->web_partner_details = $admin_cookie_data['admin_user_details'];
        $this->admin_comapny_detail = $admin_cookie_data['admin_comapny_detail'];
        $this->user_id = $admin_cookie_data['admin_user_details']['id'];

        if (permission_access_error("FlightExtranet", "FlightExtranet_Module")) {
        }
    }


    public function private_fare_list(): string
    {
        if (permission_access_error("FlightExtranet", "private_fare_list")) {



            $FlightCrsFareListModel = new FlightCrsFareListModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightCrsFareListModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $FlightCrsFareListModel->flight_crs_fare_list($this->web_partner_id);
            }

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "FlightExtranet\Views\private-fare-list",
                'pager' => $FlightCrsFareListModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_private_fare_template(): string
    {

        if (permission_access_error("FlightExtranet", "add_private_fare")) {

            $post_data['data'] = [
                'onward_stops' => 0,
                'load_by_page' => 1,
                'trip_type' => 'domestic',
                'journey_type' => 'oneway'
            ];



            $segment_detail = view("Modules\FlightExtranet\Views\segment-details", $post_data);

            $data = [
                'title' => $this->title,
                'segment_detail' => $segment_detail,
                'view' => "FlightExtranet\Views\add-private-fare",
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function addTripDetails()
    {
        $tripView = view('Modules\FlightExtranet\Views\return-trip-info');
        $data = array("TripIndicator" => 1, "TripView" => $tripView);
        return $tripView;
    }

    public function add_private_fare()
    {
        if (permission_access("FlightExtranet", "add_private_fare")) {
            $data = $this->request->getPost();
            $validate = new Validation();
            $validationConfigArray = $validate->private_fare($data);
            $this->validation->setRules($validationConfigArray);
            $rules = $this->validation->run($data);

            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $firstSegment = reset($data['onward'][0]);
                $EndSegment = end($data['onward'][0]);
                $origin = $firstSegment['origin_airport_code'];
                $destination = $firstSegment['destination_airport_code'];
                $data['origin'] = $origin;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['return_stops'] = 0;
                $data['destination'] = $destination;
                $onward_segment_detail = array();
                foreach ($data['onward'] as $segkey => $onward) {
                    $onward_data = array_values($onward);
                    if ($segkey == 1) {
                        $return_stops = count($onward_data);
                        $data['return_stops'] = $return_stops == 1 ? 0 : $return_stops - 1;
                    } else {
                        $onward_stops = count($onward_data);
                        $data['onward_stops'] = $onward_stops == 1 ? 0 : $onward_stops - 1;
                    }
                    array_push($onward_segment_detail, $onward_data);
                }
                $onward_segment_detail = array_values($data['onward']);
                $data['onward_segment_detail'] = json_encode($onward_segment_detail);
                unset($data['onward']);
                $data['created'] = create_date();

                $added_data = $FlightCrsFareListModel->insert($data);
                $Redirect_Url = site_url('private-fare/seat-allocation/') . dev_encode($added_data);
                if ($added_data) {
                    $message = array("StatusCode" => 3, "Redirect_Url" => $Redirect_Url, "Message" => "private fare successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "private fare not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function segment_details(): string
    {
        $post_data['data'] = $this->request->getPost();

        if ($post_data['data']['onward_stops'] == 0) {
            $stop = 1;
        } else {
            $stop = $post_data['data']['onward_stops'];
        }
        $post_data['data']['onward_stops'] = $stop;
        return view("Modules\FlightExtranet\Views\segment-details", $post_data);
    }

    public function segment_details_edit()
    {
        $data = $this->request->getPost();
        $id = dev_decode($this->request->uri->getSegment(3));
        $FlightCrsFareListModel = new FlightCrsFareListModel();
        $details = $FlightCrsFareListModel->flight_crs_fare_details($id, $this->web_partner_id);
        $segment_detail['data']['onward_segment_detail'] = json_decode($details['onward_segment_detail'], true);
        $segment_detail['data']['trip_type'] = $data['trip_type'];
        if ($data['onward_stops'] == 0) {
            $stop = 1;
        } else {
            $stop = $data['onward_stops'];
        }
        $segment_detail['data']['onward_stops'] = $stop;
        $segment = view("Modules\FlightExtranet\Views\segment-details-edit", $segment_detail);
        return $segment;
    }


    public function edit_private_fare_template(): string
    {

        if (permission_access_error("FlightExtranet", "edit_private_fare")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $details = $FlightCrsFareListModel->flight_crs_fare_details($id, $this->web_partner_id);
            $onward_segment_detail = array_values(json_decode($details['onward_segment_detail'], true));
            $segment_detail['segment_detail'] = $onward_segment_detail;
            $onward_stops = $details['onward_stops'];
            $return_stops = 0;
            if ($details['return_stops']) {
                $return_stops = $details['return_stops'];
            }
            $segment_detail['trip_type'] = $details['trip_type'];
            $segment_detail['onward_stops'] = $onward_stops;
            $segment_detail['return_stops'] = $return_stops;
            $segment = view("Modules\FlightExtranet\Views\segment-details-edit", $segment_detail);

            $data = [
                'onward_stops' => $onward_stops,
                'return_stops' => $return_stops,
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'segment' => $segment,
                'view' => "FlightExtranet\Views\Edit-private-fare",
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function edit_private_fare()
    {

        if (permission_access("FlightExtranet", "edit_private_fare")) {
            $data = $this->request->getPost();
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $validationConfigArray = $validate->private_fare($data);
            $this->validation->setRules($validationConfigArray);
            $rules = $this->validation->run($data);

            if (!$rules) {
                $errors = $this->validation->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
                $firstSegment = reset($data['onward'][0]);
                $EndSegment = end($data['onward'][0]);
                $origin = $firstSegment['origin_airport_code'];
                $destination = $firstSegment['destination_airport_code'];
                $data['origin'] = $origin;
                $data['return_stops'] = 0;
                $data['destination'] = $destination;
                $onward_segment_detail = array();
                foreach ($data['onward'] as $segkey => $onward) {
                    $onward_data = array_values($onward);
                    if ($segkey == 1) {
                        $return_stops = count($onward_data);
                        $data['return_stops'] = $return_stops == 1 ? 0 : $return_stops - 1;
                    } else {
                        $onward_stops = count($onward_data);
                        $data['onward_stops'] = $onward_stops == 1 ? 0 : $onward_stops - 1;
                    }
                    array_push($onward_segment_detail, $onward_data);
                }
                $onward_segment_detail = array_values($data['onward']);
                $data['onward_segment_detail'] = json_encode($onward_segment_detail);
                unset($data['onward']);

                $data['modified'] = create_date();

                $added_data = $FlightCrsFareListModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                $datatoinsert = array(
                    "segment_info" => $data['onward_segment_detail'],
                );

                $added_data_segment_info = $FlightCrsSeatAllocationModel->updateDataTable($datatoinsert, $id);

                $Redirect_Url = site_url('private-fare/seat-allocation/') . dev_encode($id);
                if ($added_data) {
                    $message = array("StatusCode" => 3, "Redirect_Url" => $Redirect_Url, "Message" => "private fare successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "private fare not  updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function private_fare_status_change()
    {

        if (permission_access("FlightExtranet", "private_fare_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $FlightCrsFareListModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "private fare status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "private fare status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }

                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_private_fare()
    {

        if (permission_access("FlightExtranet", "delete_private_fare")) {
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $ids = $this->request->getPost('checklist');

            $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
            $isFareDetailExist = $FlightCrsSeatAllocationModel->isFareDetailExist($ids);
            if (empty($isFareDetailExist)) {
                $delete = $FlightCrsFareListModel->remove_flight_crs_fare($ids, $this->web_partner_id);
            } else {
                $message = array("StatusCode" => 2, "Message" => "Seat Allocation exist for the fare.", "Class" => "error_popup");
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "private fare successfully  deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "private fare  not deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function privateFareDetailList()
    {


        $uri = service('uri');
        $privateFareId = dev_decode($uri->getSegment(3));
        $FlightCrsDetailsModel = new FlightCrsDetailsModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $FlightCrsDetailsModel->search_data($this->request->getGet(), $privateFareId, $this->web_partner_id);
        } else {
            $lists = $FlightCrsDetailsModel->flight_crs_details_list($privateFareId, $this->web_partner_id);
        }
        $PrivateFareInfo = $FlightCrsDetailsModel->flight_crs_fare_detail($privateFareId, $this->web_partner_id);
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'PrivateFareInfo' => $PrivateFareInfo,
            'privateFareId' => $privateFareId,
            'view' => "FlightExtranet\Views\private-fare-detail-list",
            'pager' => $FlightCrsDetailsModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }

    public function addPrivateFarePnrTemplate()
    {
        if (permission_access_error("FlightExtranet", "add_fare_rule")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $details = $FlightCrsFareListModel->flight_crs_fare_details($id, $this->web_partner_id);
            $data = [
                'title' => $details['reference_number'],
                'details' => $details,
            ];
            $add_private_fare_pnr = view('Modules\FlightExtranet\Views\add-private-fare-pnr', $data);
            $data = array("StatusCode" => 0, "Message" => $add_private_fare_pnr, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function fare_rule()
    {
        if (permission_access_error("FlightExtranet", "add_fare_rule")) {
            $data = [
                'title' => $this->title,
                'view' => "FlightExtranet\Views\add-fare-rule",
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function fare_rule_save()
    {
        if (permission_access("FlightExtranet", "add_fare_rule")) {
            $validate = new Validation();
            $rules = $this->validate($validate->fare_rule);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();
                $airline = explode('-', $data['airline_code']);
                if (isset($airline[0])) {
                    $data['airline_code'] = $airline[0];
                }
                if (isset($airline[1])) {
                    $data['airline_name'] = $airline[1];
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();
                $added_data = $FlightCrsFareRuleModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "fare rule successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "fare not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function fare_rule_list()
    {
        if (permission_access_error("FlightExtranet", "fare_rule_list")) {
            $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightCrsFareRuleModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $FlightCrsFareRuleModel->fare_rule_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "FlightExtranet\Views/fare-rule-list",
                'pager' => $FlightCrsFareRuleModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function edit_fare_rule()
    {
        if (permission_access_error("FlightExtranet", "edit_fare_rule")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'view' => "FlightExtranet\Views/edit-fare-rule",
                'details' => $FlightCrsFareRuleModel->fare_rule_detail($id, $this->web_partner_id),
            ];
            return view('template/sidebar-layout', $data);
        }
    }
    public function edit_rate_plan_update()
    {
        if (permission_access("FlightExtranet", "edit_fare_rule")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->fare_rule);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();
                $airline = explode('-', $data['airline_code']);
                if (isset($airline[0])) {
                    $data['airline_code'] = $airline[0];
                }
                if (isset($airline[1])) {
                    $data['airline_name'] = $airline[1];
                }
                $added_data = $FlightCrsFareRuleModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "fare rule successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "fare rule not  updated", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function remove_fare_rule()
    {
        if (permission_access("FlightExtranet", "delete_fare_rule")) {
            $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();

            $ids = $this->request->getPost('checklist');
            $delete = $FlightCrsFareRuleModel->remove_fare_rule_list($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Fare Rule Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Fare Rule  Not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function fare_rule_status_change()
    {
        if (permission_access("FlightExtranet", "fare_rule_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareRuleModel = new FlightCrsFareRuleModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['status'] = $this->request->getPost('status');
                $update = $FlightCrsFareRuleModel->fare_rule_status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "fare rule status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "fare rule status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function rate_plan()
    {
        if (permission_access_error("FlightExtranet", "rate_plane_list")) {
            $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $FlightCrsRatePlanModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $FlightCrsRatePlanModel->fare_rule_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "FlightExtranet\Views/rate-plan-list",
                'pager' => $FlightCrsRatePlanModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_rate_plan_template()
    {
        if (permission_access("Flight", "add_rate_plane")) {
            $add_blog_view = view('Modules\FlightExtranet\Views\add-rate-plan', );
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_rate_plan()
    {

        if (permission_access("FlightExtranet", "add_rate_plane")) {
            $validate = new Validation();
            $rules = $this->validate($validate->rate_plan);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $FlightCrsRatePlanModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Rate plan successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Rate plan not  added", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_rate_plan_template()
    {
        if (permission_access_error("FlightExtranet", "edit_rate_plane")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FlightCrsRatePlanModel->details($id, $this->web_partner_id),
            ];

            $blog_details = view('Modules\FlightExtranet\Views\edit-rate-plan', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function edit_rate_plan()
    {

        if (permission_access("FlightExtranet", "edit_rate_plane")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->rate_plan);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
                $data = $this->request->getPost();
                $added_data = $FlightCrsRatePlanModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "rate plan successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "rate plan not  updated", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_rate_plan()
    {

        if (permission_access("FlightExtranet", "delete_rate_plane")) {
            $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightCrsRatePlanModel->remove_rate_plan($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "rate plan successfully deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "rate plan  not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function seat_allocation()
    {
        if (permission_access_error("FlightExtranet", "seat_allocation_list")) {
            $private_fare_id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $details = $FlightCrsFareListModel->flight_crs_fare_details($private_fare_id, $this->web_partner_id);
            $segment_detail = json_decode($details['onward_segment_detail'], true);
            $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();

            $seat_list = $FlightCrsSeatAllocationModel->seat_allocation_list($private_fare_id, $this->web_partner_id);

            $data = [
                'list' => $seat_list,
                'private_fare_id' => $private_fare_id,
                'title' => $this->title,
                'segment_details' => $segment_detail,
                'pager' => $FlightCrsSeatAllocationModel->pager,
                'view' => "FlightExtranet\Views/seat-allocation-list",
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_seat_allocation_template()
    {
        if (permission_access("FlightExtranet", "add_seat_allocation")) {
            $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
            $data['rate_plan'] = $FlightCrsRatePlanModel->rate_plan_select($this->web_partner_id);

            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $details = $FlightCrsFareListModel->flight_crs_fare_details($id, $this->web_partner_id);
            $data['segment_detail'] = json_decode($details['onward_segment_detail'], true);
            $data['private_fare_id'] = $id;
            if ($details['trip_type'] == "international" && $details['journey_type'] == "roundtrip") {
                $add_view = view('Modules\FlightExtranet\Views\add-return-international-seat-allocation', $data);
            } else {
                $add_view = view('Modules\FlightExtranet\Views\add-seat-allocation', $data);
            }
            $data = array("StatusCode" => 0, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_seat_allocation()
    {
        if (permission_access("FlightExtranet", "add_seat_allocation")) {
            $private_fare_id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->seat_allocation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
                $data = $this->request->getPost();
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                $end_date = date('Y-m-d', strtotime($data['end_date']));

                $date_period = getDateDiffrence($start_date, $end_date);

                $details = $FlightCrsFareListModel->flight_crs_fare_details($private_fare_id, $this->web_partner_id);
                $segment_detail = json_decode($details['onward_segment_detail'], true);
                foreach ($segment_detail as $tripkey => $segment_details) {
                    $onward_stops = count($segment_details);
                    for ($i = 0; $i < $onward_stops; $i++) {
                        $segment_detail[$tripkey][$i]['flight_number'] = $data['onward'][$tripkey][$i]['flight_number'];
                        $segment_detail[$tripkey][$i]['departure_time'] = $data['onward'][$tripkey][$i]['departure_time'];
                        $segment_detail[$tripkey][$i]['arrival_time'] = $data['onward'][$tripkey][$i]['arrival_time'];
                        $segment_detail[$tripkey][$i]['is_next_day_arrival'] = $data['onward'][$tripkey][$i]['is_next_day_arrival'];
                    }
                }
                unset($data['onward'], $data['start_date'], $data['end_date']);
                $data['privatefare_id'] = $private_fare_id;
                $data['segment_info'] = json_encode($segment_detail);
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                if (!empty($date_period)) {
                    foreach ($date_period as $key => $period) {
                        $data['date'] = $period;
                        $data['b2c_status'] = 'active';
                        $data['b2b_status'] = 'active';
                        $added_data = $FlightCrsSeatAllocationModel->insert($data);
                    }
                }

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "seat allocation successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "seat allocation not added", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function add_return_international_seat_allocation()
    {
        if (permission_access("FlightExtranet", "add_seat_allocation")) {
            $private_fare_id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->seat_international_return_allocation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
                $data = $this->request->getPost();
                $date = date('Y-m-d', strtotime($data['date']));
                $date_return = date('Y-m-d', strtotime($data['date_return']));


                $details = $FlightCrsFareListModel->flight_crs_fare_details($private_fare_id, $this->web_partner_id);
                $segment_detail = json_decode($details['onward_segment_detail'], true);
                foreach ($segment_detail as $tripkey => $segment_details) {
                    $onward_stops = count($segment_details);
                    for ($i = 0; $i < $onward_stops; $i++) {
                        $segment_detail[$tripkey][$i]['flight_number'] = $data['onward'][$tripkey][$i]['flight_number'];
                        $segment_detail[$tripkey][$i]['departure_time'] = $data['onward'][$tripkey][$i]['departure_time'];
                        $segment_detail[$tripkey][$i]['arrival_time'] = $data['onward'][$tripkey][$i]['arrival_time'];
                        $segment_detail[$tripkey][$i]['is_next_day_arrival'] = $data['onward'][$tripkey][$i]['is_next_day_arrival'];
                    }
                }
                unset($data['onward'], $data['date'], $data['date_return']);
                $data['privatefare_id'] = $private_fare_id;
                $data['segment_info'] = json_encode($segment_detail);
                $data['created'] = create_date();
                $data['date'] = $date;
                $data['date_return'] = $date_return;
                $data['b2c_status'] = 'active';
                $data['b2b_status'] = 'active';
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $FlightCrsSeatAllocationModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "seat allocation successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "seat allocation not added", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_seat_allocation_template()
    {
        if (permission_access("FlightExtranet", "edit_seat_allocation")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsRatePlanModel = new FlightCrsRatePlanModel();
            $FlightCrsFareListModel = new FlightCrsFareListModel();
            $data['rate_plan'] = $FlightCrsRatePlanModel->rate_plan_select($this->web_partner_id);

            $id = dev_decode($this->request->uri->getSegment(3));
            $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
            $details = $FlightCrsSeatAllocationModel->details($id, $this->web_partner_id);
            $private_fare_id = $details['privatefare_id'];
            $data['segment_detail'] = json_decode($details['segment_info'], true);
            $fareListDetail = $FlightCrsFareListModel->flight_crs_fare_details($private_fare_id, $this->web_partner_id);



            $data['details'] = $details;
            if ($fareListDetail['trip_type'] == "international" && $fareListDetail['journey_type'] == "roundtrip") {
                $seat_details = view('Modules\FlightExtranet\Views\edit-return-international-seat-allocation', $data);
            } else {
                $seat_details = view('Modules\FlightExtranet\Views\edit-seat-allocation', $data);
            }
            $data = array("StatusCode" => 0, "Message" => $seat_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_seat_allocation()
    {
        if (permission_access("FlightExtranet", "edit_seat_allocation")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->seat_allocation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
                $data = $this->request->getPost();
                $start_date = date('Y-m-d', strtotime($data['start_date']));
                $end_date = date('Y-m-d', strtotime($data['end_date']));
                $date_period = getDateDiffrence($start_date, $end_date);
                if (isset($data['segment_check']) && $data['segment_check'] == 1) {
                    $details = $FlightCrsSeatAllocationModel->details($id, $this->web_partner_id);


                    $private_fare_id = $details['privatefare_id'];

                    $segment_detail = json_decode($details['segment_info'], true);
                    foreach ($segment_detail as $tripkey => $segment_details) {
                        $onward_stops = count($segment_details);
                        for ($i = 0; $i < $onward_stops; $i++) {
                            $segment_detail[$tripkey][$i]['flight_number'] = $data['onward'][$tripkey][$i]['flight_number'];
                            $segment_detail[$tripkey][$i]['departure_time'] = $data['onward'][$tripkey][$i]['departure_time'];
                            $segment_detail[$tripkey][$i]['arrival_time'] = $data['onward'][$tripkey][$i]['arrival_time'];
                            $segment_detail[$tripkey][$i]['is_next_day_arrival'] = $data['onward'][$tripkey][$i]['is_next_day_arrival'];
                        }
                    }
                    $data['segment_info'] = json_encode($segment_detail);
                }

                if (!isset($data['rate_plan_check'])) {
                    unset($data['rate_plan_id']);
                }
                if (!isset($data['seat_check'])) {
                    unset($data['available_seats']);
                }

                if (!isset($data['pnr_check'])) {
                    unset($data['pnr']);
                }

                unset($data['onward'], $data['start_date'], $data['end_date'], $data['segment_check'], $data['rate_plan_check'], $data['seat_check'], $data['pnr_check']);
                $added_data = 0;

                if (!empty($date_period)) {
                    foreach ($date_period as $key => $period) {
                        $details = $FlightCrsSeatAllocationModel->details_by_date($period, $this->web_partner_id);
                        if ($details) {
                            $added_data = $FlightCrsSeatAllocationModel->where(["date" => $period, 'privatefare_id' => $private_fare_id, "web_partner_id" => $this->web_partner_id])->set($data)->update();
                        }
                    }
                }

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "seat allocation successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "seat allocation not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function edit_return_international_seat_allocation()
    {
        if (permission_access("FlightExtranet", "edit_seat_allocation")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->seat_international_return_allocation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $FlightCrsFareListModel = new FlightCrsFareListModel();
                $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
                $data = $this->request->getPost();
                $date = date('Y-m-d', strtotime($data['date']));
                $date_return = date('Y-m-d', strtotime($data['date_return']));
                $data['date'] = $date;
                $data['date_return'] = $date_return;
                $details = $FlightCrsSeatAllocationModel->details($id, $this->web_partner_id);
                if (isset($data['segment_check']) && $data['segment_check'] == 1) {

                    $segment_detail = json_decode($details['segment_info'], true);
                    foreach ($segment_detail as $tripkey => $segment_details) {
                        $onward_stops = count($segment_details);
                        for ($i = 0; $i < $onward_stops; $i++) {
                            $segment_detail[$tripkey][$i]['flight_number'] = $data['onward'][$tripkey][$i]['flight_number'];
                            $segment_detail[$tripkey][$i]['departure_time'] = $data['onward'][$tripkey][$i]['departure_time'];
                            $segment_detail[$tripkey][$i]['arrival_time'] = $data['onward'][$tripkey][$i]['arrival_time'];
                            $segment_detail[$tripkey][$i]['is_next_day_arrival'] = $data['onward'][$tripkey][$i]['is_next_day_arrival'];
                        }
                    }
                    $data['segment_info'] = json_encode($segment_detail);
                }

                if (!isset($data['rate_plan_check'])) {
                    unset($data['rate_plan_id']);
                }
                if (!isset($data['seat_check'])) {
                    unset($data['available_seats']);
                }

                if (!isset($data['pnr_check'])) {
                    unset($data['pnr']);
                }

                unset($data['onward'], $data['date'], $data['date_return'], $data['segment_check'], $data['rate_plan_check'], $data['seat_check'], $data['pnr_check']);
                $added_data = 0;
                $added_data = $FlightCrsSeatAllocationModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($data)->update();
                /*  if (!empty($date_period)) {
                foreach ($date_period as $key=>$period) {
                    $details = $FlightCrsSeatAllocationModel->details_by_date($period);
                    if ($details){
                        $added_data = $FlightCrsSeatAllocationModel->where("date", $period)->set($data)->update();
                    }
                }
            } */

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "seat allocation successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "seat allocation not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_seat_allocation()
    {
        if (permission_access("FlightExtranet", "delete_seat_allocation")) {
            $FlightCrsSeatAllocationModel = new FlightCrsSeatAllocationModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightCrsSeatAllocationModel->remove_seat_allocation($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "seat successfully deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "seat  not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
}
