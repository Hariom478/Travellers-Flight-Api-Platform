<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;

class AgentAccountLogModel extends Model
{
    protected $table = 'agent_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function account_logs_detail($web_partner_id,$agent_id)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        return $this->select('agent_account_log.id,agent_account_log.web_partner_id,agent_account_log.acc_ref_number,agent_account_log.payment_mode,
                agent_account_log.action_type,agent_account_log.service,agent_account_log.service_log,agent_account_log.transaction_type,
                agent_account_log.credit,agent_account_log.debit,agent_account_log.balance,agent_account_log.remark,
                agent_account_log.created,agent_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
                super_admin_payment_transaction.transaction_id,
                
                 hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
                     bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
                     visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
                     car_booking_list.booking_ref_number as car_booking_ref_number,
                
                 flight_booking_list.api_supplier,flight_booking_list.pnr,flight_booking_list.booking_ref_number as flight_booking_ref_number,web_partner_account_log.booking_confirmation_number
        ')
            ->join('flight_booking_list', 'flight_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="flight"','Left')
            ->join('hotel_booking_list', 'hotel_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="hotel"','Left')
            ->join('bus_booking_list', 'bus_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="bus"','Left')
            ->join('holiday_booking_list', 'holiday_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="holiday"','Left')
            ->join('visa_booking_list', 'visa_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="visa"','Left')
            ->join('cruise_booking_list', 'cruise_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="cruise"','Left')

            ->join('car_booking_list', 'car_booking_list.id = agent_account_log.booking_ref_no AND agent_account_log.service="car"','Left')
            ->join('web_partner_account_log', 'web_partner_account_log.booking_ref_no = agent_account_log.booking_ref_no AND web_partner_account_log.action_type="booking"', 'Left')

            ->join("($subquery) web_partner", 'web_partner.id = agent_account_log.web_partner_id', 'left')
            ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = agent_account_log.payment_transaction_id','Left')
            ->where('agent_account_log.web_partner_id', $web_partner_id)->where('agent_account_log.wl_agent_id', $agent_id)->groupBy("agent_account_log.id")->orderBy("agent_account_log.id", "DESC")->paginate(10);
    }

    public function available_balance($web_partner_id,$agent_id){
        return  $this->select('balance')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $agent_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}


