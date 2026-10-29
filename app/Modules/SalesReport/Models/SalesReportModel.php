<?php

namespace App\Modules\SalesReport\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SalesReportModel extends Model
{

    public function flight_booking_list_report($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
            $builder = $this->db->table('flight_booking_list');
            $builder->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.airline_code,flight_booking_list.is_lcc,flight_booking_list.segments,flight_booking_list.web_partner_fare_break_up,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
            flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.agent_fare_break_up,flight_booking_list.issue_supplier,
            flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
            flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
            web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,flight_booking_list.lead_pax,
            agent_account_log.id as web_partner_account_log_id,agent_account_log.acc_ref_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.action_type,agent_account_log.invoice_number,
            
            ')->where(['flight_booking_list.web_partner_id' => $web_partner_id,"flight_booking_list.wl_agent_id"=>$wl_agent_id])
                ->where(["agent_account_log.service" => "flight", "agent_account_log.action_type" => "booking"])
                ->where($array)->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'Left')

                ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'Left')
                ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'Left')
                ->join("agent_account_log", "agent_account_log.booking_ref_no=flight_booking_list.id", 'Left');

            return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->get()->getResultArray();
        }else{
            return null;
        }

    }

    public function flight_travellers_list($booking_id){
        return  $this->db->table("flight_booking_travelers")->select('*')->where('flight_booking_id',$booking_id)->orderBy("id","DESC")->get()->getResultArray();
    }



    public function hotel_booking_detail($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];
            $builder = $this->db->table('hotel_booking_list');

            $builder->select('hotel_booking_list.super_admin_fare_break_up,hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.is_domestic,hotel_booking_list.hotel_rooms_details,hotel_booking_list.assign_user,web_partner.company_name,web_partner.company_id,hotel_booking_list.api_supplier,hotel_booking_list.supplier_booking_id,hotel_booking_list.agent_fare_break_up,
            hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,
            hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,
            hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,
            CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,
            agent_account_log.id as web_partner_account_log_id,agent_account_log.acc_ref_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.action_type,

            ')->where(['hotel_booking_list.web_partner_id' => $web_partner_id,"hotel_booking_list.wl_agent_id"=>$wl_agent_id])
                ->where(["agent_account_log.service" => "hotel", "agent_account_log.action_type" => "booking"])
                ->where($array)->whereNotIn("hotel_booking_list.booking_status", ['Failed','Processing'])
                ->join("admin_users", "admin_users.id=hotel_booking_list.agent_staff_id", 'Left')
                ->join('web_partner', "hotel_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = hotel_booking_list.assign_user", 'left')
                ->join("agent_account_log", "agent_account_log.booking_ref_no=hotel_booking_list.id", 'Left');
            return $builder->groupBy("hotel_booking_list.id")->orderBy("hotel_booking_list.id", "DESC")->get()->getResultArray();

        }else {
            return null;
        }
    }


    public function holiday_booking_list_report($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];


            $builder = $this->db->table('holiday_booking_list');
            $builder->select('holiday_booking_list.update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
            holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
            holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.api_supplier,
            holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
             web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name
             
             ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,
            
        ')->where($array)->where(['holiday_booking_list.web_partner_id' => $web_partner_id,"holiday_booking_list.wl_agent_id"=>$wl_agent_id])->where('holiday_booking_list.payment_status','Successful')
            ->whereNotIn("holiday_booking_list.booking_status", ['Failed','Processing'])
                ->where(["web_partner_account_log.service" => "holiday", "web_partner_account_log.action_type" => "booking"])
                ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
                ->join("admin_users", "admin_users.id=holiday_booking_list.agent_staff_id", 'Left')
                ->join('web_partner', "holiday_booking_list.web_partner_id = web_partner.id", 'Left')
                ->join('super_admin_users', "super_admin_users.id = holiday_booking_list.assign_user", 'left')
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=holiday_booking_list.id", 'Left');

                return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->get()->getResultArray();
        }else{
            return null;
        }
    }

    public function visa_booking_list_report($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['visa_booking_list.created >=' => $from_date, 'visa_booking_list.created <=' => $to_date];


            $builder = $this->db->table('visa_booking_list');
            $builder->select('visa_booking_list.id,visa_booking_list.web_partner_id,visa_booking_list.date_of_journey,visa_booking_list.payment_mode,visa_booking_list.tts_search_token,visa_booking_list.booking_ref_number,visa_booking_list.visa_country,visa_booking_list.assign_user,visa_booking_list.update_ticket_by,visa_booking_list.visa_type,
        visa_booking_list.payment_status,visa_booking_list.booking_status,visa_booking_list.total_price,visa_booking_list.created,visa_booking_list.is_manual,visa_booking_list.web_partner_fare_break_up,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,
        visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_travelers.title,visa_booking_travelers.first_name,visa_booking_travelers.last_name,
        
         ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,
            
        ')->where(['visa_booking_list.web_partner_id' => $web_partner_id,"visa_booking_list.wl_agent_id"=>$wl_agent_id])
                ->where(["web_partner_account_log.service" => "visa", "web_partner_account_log.action_type" => "booking"])
                ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
                ->join("admin_users", "admin_users.id=visa_booking_list.agent_staff_id", 'left')
                ->join('web_partner', "visa_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = visa_booking_list.assign_user", 'left')->where($array)
            ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=visa_booking_list.id", 'Left');



            $builder->where('visa_booking_list.payment_status','Successful');
            $builder->whereNotIn("visa_booking_list.booking_status", ['Failed','Processing']);

            return $builder->groupBy("visa_booking_list.id")->orderBy("visa_booking_list.id", "DESC")->get()->getResultArray();

        }else{
            return null;
        }
    }
    public function car_booking_list_report($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['car_booking_list.created >=' => $from_date, 'car_booking_list.created <=' => $to_date];


            $builder = $this->db->table('car_booking_list');
            $builder->select('car_booking_list.*,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,
        
        
         ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,
            
        ')->where(['car_booking_list.web_partner_id' => $web_partner_id,"car_booking_list.wl_agent_id"=>$wl_agent_id])
                ->where(["web_partner_account_log.service" => "car", "web_partner_account_log.action_type" => "booking"])

                ->join("admin_users", "admin_users.id=car_booking_list.agent_staff_id", 'left')
                ->join('web_partner', "car_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = car_booking_list.assign_user", 'left')->where($array)
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=car_booking_list.id", 'Left');



            $builder->where('car_booking_list.payment_status','Successful');
            $builder->whereNotIn("car_booking_list.booking_status", ['Failed','Processing']);

            return $builder->groupBy("car_booking_list.id")->orderBy("car_booking_list.id", "DESC")->get()->getResultArray();

        }else{
            return null;
        }
    }
    public function bus_booking_list_report($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['bus_booking_list.created >=' => $from_date, 'bus_booking_list.created <=' => $to_date];
            $builder = $this->db->table('bus_booking_list');
            $builder->select('bus_booking_list.id,bus_booking_list.booking_ref_number,bus_booking_list.web_partner_fare_break_up,bus_booking_list.api_supplier,
            bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.created,
            bus_booking_list.is_domestic,bus_booking_list.payment_status,bus_booking_list.booking_status,bus_booking_list.bus_type,
            bus_booking_list.total_price,bus_booking_list.agent_staff_id,bus_booking_list.booking_channel,
            web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(bus_booking_travelers.title," ",bus_booking_travelers.first_name," ",bus_booking_travelers.last_name) as lead_passenger_name,
            web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,
            
            ')->where(['bus_booking_list.web_partner_id' => $web_partner_id,"bus_booking_list.wl_agent_id"=>$wl_agent_id])->whereNotIn("bus_booking_list.booking_status", ['Failed','Processing'])
                ->where(["web_partner_account_log.service" => "bus", "web_partner_account_log.action_type" => "booking"])
                ->where($array)->join("admin_users", "admin_users.id=bus_booking_list.agent_staff_id", 'Left')

                ->join('bus_booking_travelers', "bus_booking_travelers.bus_booking_id = bus_booking_list.id", 'Left')
                ->join('web_partner', "bus_booking_list.web_partner_id = web_partner.id", 'Left')
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=bus_booking_list.id", 'Left');

            return $builder->groupBy("bus_booking_list.id")->orderBy("bus_booking_list.id", "DESC")->get()->getResultArray();
        }else{
            return null;
        }

    }

    public function bus_travellers_list($booking_id){
        return  $this->db->table("bus_booking_travelers")->select('*')->where('bus_booking_id',$booking_id)->orderBy("id","DESC")->get()->getResultArray();
    }

    
}
