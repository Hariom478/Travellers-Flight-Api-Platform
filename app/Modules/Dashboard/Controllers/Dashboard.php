<?php

namespace Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Modules\Dashboard\Models\WebPartnerAccountModel;
use App\Modules\Dashboard\Models\AgentAccountLogModel;
use App\Modules\Dashboard\Models\WebPartnerModel;

use App\Modules\Dashboard\Models\DashboardModel;

class Dashboard extends BaseController
{


    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Dashboard";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
    }

    public function index_()
    {
        $DashboardModel = new DashboardModel();

        $AgentAccountLogModel = new AgentAccountLogModel();
        $web_partner_id = $this->web_partner_id;
        $available_balance = $AgentAccountLogModel->available_balance($web_partner_id, $this->wl_agent_id);
        if (isset ($available_balance['balance'])) {
            $balance = $available_balance['balance'];
        } else {
            $balance = 0;
        }
        $account_logs = $AgentAccountLogModel->account_logs_detail($web_partner_id, $this->wl_agent_id);

        $flight_booking_count = $DashboardModel->flight_booking_count($this->web_partner_id, $this->wl_agent_id);

        $hotel_booking_count = $DashboardModel->hotel_booking_count($this->web_partner_id, $this->wl_agent_id);

        $bus_booking_count = $DashboardModel->bus_booking_count($this->web_partner_id, $this->wl_agent_id);

        $holiday_booking_count = $DashboardModel->holiday_booking_count($this->web_partner_id, $this->wl_agent_id);

        $visa_booking_count = $DashboardModel->visa_booking_count($this->web_partner_id, $this->wl_agent_id);

        $car_booking_count = $DashboardModel->car_booking_count($this->web_partner_id, $this->wl_agent_id);

        $data = [
            'flight_booking_count' => $flight_booking_count,
            'hotel_booking_count' => $hotel_booking_count,
            'bus_booking_count' => $bus_booking_count,
            'holiday_booking_count' => $holiday_booking_count,
            'visa_booking_count' => $visa_booking_count,
            'car_booking_count' => $car_booking_count,
            'account_logs' => $account_logs,

            'available_balance' => $balance,
            'title' => $this->title,
            'view' => "Dashboard\Views\dashbaord"
        ];
        return view('template/default-layout', $data);
    }







    public function index()
    { 
        $DashboardModel = new DashboardModel();  
        $active_serveic =  get_active_whitelable_service();  
        $service_array = array();  // Initialize
        $service_array_amendment = array();  // Initialize
        $service_total_booking_count = array(); // Initialize $service_total_booking_count
        $service_total_amendment_count = array(); // Initialize $service_total_amendment_count
        $service_array_refund_status = array(); // Initialize $service_array_refund_status
        
        foreach ($active_serveic as $service) 
        {
            $dynamic_table = strtolower($service);    
            $booking_result = $DashboardModel->getServiceCountInfo($dynamic_table.'_booking_list', array('web_partner_id' => $this->web_partner_id, 'wl_agent_id'=> $this->wl_agent_id, 'booking_source'=> 'Wl_b2b'), 'booking_status');
            $service_total_booking_count[$service] = count($booking_result);
            $values = array_column($booking_result, "booking_status");
            $service_array[$service] = array_count_values($values);
        
            $amendment_result = $DashboardModel->getServiceCountInfo($dynamic_table.'_amendment', array('web_partner_id' => $this->web_partner_id,'wl_agent_id'=> $this->wl_agent_id), 'amendment_status,refund_status');
            $service_total_amendment_count[$service] = count($amendment_result);
            $values_amendment_result = array_column($amendment_result, "amendment_status");
            $service_array_amendment[$service] = array_count_values($values_amendment_result);

            $values_amendment_refund_status = array_column($amendment_result, "refund_status");
            $values_amendment_refund_status = array_count_values(
                array_map(function ($value) {
                    if (is_string($value) || is_numeric($value)) {
                        return $value;
                    } else {
                        return strval($value);
                    }
                }, $values_amendment_refund_status)
            );
            $service_array_refund_status[$service] = $values_amendment_refund_status;
        }  
        $AgentAccountLogModel = new AgentAccountLogModel();
        $web_partner_id = $this->web_partner_id;
        $available_balance = $AgentAccountLogModel->available_balance($web_partner_id, $this->wl_agent_id);
        if (isset ($available_balance['balance'])) {
            $balance = $available_balance['balance'];
        } else {
            $balance = 0;
        }
        $account_logs = $AgentAccountLogModel->account_logs_detail($web_partner_id, $this->wl_agent_id);
        unset($booking_result);
        unset($values);
        unset($amendment_result);
        unset($values_amendment_result);
        unset($values_amendment_refund_status);
        $data = [ 
            'totle_booking_cont' => $service_total_booking_count,
            'totle_amendment_cont' => $service_total_amendment_count,
            'commonData' => $service_array,
            'CommonDataAmendment' => $service_array_amendment,
            'CommonDatarefund_status' => $service_array_refund_status,
            'account_logs' => $account_logs, 
            'available_balance' => $balance,
            'UserIp' => $this->request->getIpAddress(),
            'title' => $this->title,


            'flight_booking_count' => 0,
            'hotel_booking_count' => 0,
            'bus_booking_count' => 0,
            'holiday_booking_count' => 0,
            'visa_booking_count' => 0,
            'car_booking_count' => 0,

            'view' => "Dashboard\Views/dashbaord"
        ]; 
        return view('template/default-layout', $data);
    }



 



} 