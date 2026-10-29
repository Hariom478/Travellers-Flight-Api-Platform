<?php

namespace App\Modules\SalesReport\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SalesReportModel extends Model
{

    public function flight_booking_list_report($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
            $builder = $this->db->table('flight_booking_list');
            $builder->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.airline_code,flight_booking_list.is_lcc,flight_booking_list.segments,flight_booking_list.web_partner_fare_break_up,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
            flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.booking_source,flight_booking_list.agent_fare_break_up,flight_booking_list.customer_fare_break_up,
            flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
            flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
            agent.company_name,agent.company_id,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name
            ')->where(['flight_booking_list.web_partner_id' => $web_partner_id]);

            $builder->where($array)
                ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'Left')
                ->join('agent', "agent.id = flight_booking_list.wl_agent_id", 'Left');
            $builder->whereIn('flight_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
            $builder->where('flight_booking_list.payment_status', 'Successful');
            $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
            return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }

    public function flight_travellers_list($booking_id)
    {
        return  $this->db->table("flight_booking_travelers")->select('*')->where('flight_booking_id', $booking_id)->orderBy("id", "DESC")->get()->getResultArray();
    }



    public function hotel_booking_detail($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];

            $builder = $this->db->table('hotel_booking_list');
            $builder->select('hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.is_domestic,hotel_booking_list.hotel_rooms_details,hotel_booking_list.webpartner_assign_user,agent.company_name,agent.company_id,hotel_booking_list.api_supplier,hotel_booking_list.supplier_booking_id,hotel_booking_list.agent_fare_break_up,
            hotel_booking_list.id,hotel_booking_list.booking_ref_number,hotel_booking_list.city,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.customer_fare_break_up,hotel_booking_list.booking_source,
            hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,
            hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,
            ')->where(['hotel_booking_list.web_partner_id' => $web_partner_id])
                ->join('agent', "agent.id = hotel_booking_list.wl_agent_id", 'Left')
                ->where($array)->where("hotel_booking_list.booking_status", 'Confirmed');
            $builder->whereIn('hotel_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
            return $builder->groupBy("hotel_booking_list.id")->orderBy("hotel_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }


    public function holiday_booking_list_report($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];


            $builder = $this->db->table('holiday_booking_list');
            $builder->select('holiday_booking_list.webpartner_update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.webpartner_assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.agent_fare_break_up,
            holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
            holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.api_supplier,holiday_booking_list.agent_fare_break_up,holiday_booking_list.booking_source,holiday_booking_list.wl_customer_id,holiday_booking_list.wl_agent_id,holiday_booking_list.customer_fare_break_up,
            holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
             agent.company_name,agent.company_id,
            
        ')->where($array)->where(['holiday_booking_list.web_partner_id' => $web_partner_id])->where('holiday_booking_list.payment_status', 'Successful')
                ->whereNotIn("holiday_booking_list.booking_status", ['Failed', 'Processing'])
                ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
                ->join('agent', "agent.id = holiday_booking_list.wl_agent_id", 'Left');
                $builder->whereIn('holiday_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
            return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }

    public function visa_booking_list_report($web_partner_id, $data)
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
        
         ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.invoice_number,
            
        ')->where(['visa_booking_list.web_partner_id' => $web_partner_id])
                ->where(["web_partner_account_log.service" => "visa", "web_partner_account_log.action_type" => "booking"])
                ->join('visa_booking_travelers', 'visa_booking_travelers.visa_booking_id = visa_booking_list.id', 'Left')
                ->join("admin_users", "admin_users.id=visa_booking_list.agent_staff_id", 'left')
                ->join('web_partner', "visa_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = visa_booking_list.assign_user", 'left')->where($array)
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=visa_booking_list.id", 'Left');
            $builder->where('visa_booking_list.payment_status', 'Successful');
            $builder->whereNotIn("visa_booking_list.booking_status", ['Failed', 'Processing']);

            return $builder->groupBy("visa_booking_list.id")->orderBy("visa_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }
    public function car_booking_list_report($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['car_booking_list.created >=' => $from_date, 'car_booking_list.created <=' => $to_date];


            $builder = $this->db->table('car_booking_list');
            $builder->select('car_booking_list.*,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,
        
        
         ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.invoice_number,
            
        ')->where(['car_booking_list.web_partner_id' => $web_partner_id])
                ->where(["web_partner_account_log.service" => "car", "web_partner_account_log.action_type" => "booking"])

                ->join("admin_users", "admin_users.id=car_booking_list.agent_staff_id", 'left')
                ->join('web_partner', "car_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = car_booking_list.assign_user", 'left')->where($array)
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=car_booking_list.id", 'Left');



            $builder->where('car_booking_list.payment_status', 'Successful');
            $builder->whereNotIn("car_booking_list.booking_status", ['Failed', 'Processing']);

            return $builder->groupBy("car_booking_list.id")->orderBy("car_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }
    public function bus_booking_list_report($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['bus_booking_list.created >=' => $from_date, 'bus_booking_list.created <=' => $to_date];
            $builder = $this->db->table('bus_booking_list');
            $builder->select('bus_booking_list.id,bus_booking_list.booking_ref_number,bus_booking_list.web_partner_fare_break_up,bus_booking_list.api_supplier,
            bus_booking_list.ticket_no,bus_booking_list.travel_operator_pnr,bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,bus_booking_list.created,bus_booking_list.agent_fare_break_up,bus_booking_list.booking_source,bus_booking_list.wl_customer_id,bus_booking_list.wl_agent_id,
            bus_booking_list.is_domestic,bus_booking_list.payment_status,bus_booking_list.booking_status,bus_booking_list.bus_type,
            bus_booking_list.total_price,bus_booking_list.agent_staff_id,bus_booking_list.booking_channel,bus_booking_list.customer_fare_break_up,
            agent.company_name,agent.company_id,CONCAT(bus_booking_travelers.title," ",bus_booking_travelers.first_name," ",bus_booking_travelers.last_name) as lead_passenger_name, 
            ')->where(['bus_booking_list.web_partner_id' => $web_partner_id])->whereNotIn("bus_booking_list.booking_status", ['Failed', 'Processing'])
                ->where($array)
                ->join('bus_booking_travelers', "bus_booking_travelers.bus_booking_id = bus_booking_list.id", 'Left')
                ->join('agent', "agent.id = bus_booking_list.wl_agent_id", 'Left');
            $builder->whereIn('bus_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
            $builder->where('bus_booking_list.payment_status', 'Successful');
            $builder->whereNotIn("bus_booking_list.booking_status", ['Failed', 'Processing']);

            return $builder->groupBy("bus_booking_list.id")->orderBy("bus_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }

    public function cruise_booking_list_report($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {

            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date];


            $builder = $this->db->table('cruise_booking_list');
            $builder->select('cruise_booking_list.id,cruise_booking_list.web_partner_id,cruise_booking_list.sailing_date,cruise_booking_list.payment_mode,cruise_booking_list.tts_search_token,cruise_booking_list.booking_ref_number,cruise_booking_list.cruise_line_name,cruise_booking_list.assign_user,cruise_booking_list.update_ticket_by,cruise_booking_list.ship_name,
        cruise_booking_list.payment_status,cruise_booking_list.booking_status,cruise_booking_list.total_price,cruise_booking_list.created,cruise_booking_list.is_manual,cruise_booking_list.web_partner_fare_break_up,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,
        cruise_booking_travelers.email_id,cruise_booking_travelers.mobile_number,cruise_booking_travelers.title,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name,
        
         ,web_partner_account_log.id as web_partner_account_log_id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.invoice_number,
            
        ')->where(['cruise_booking_list.web_partner_id' => $web_partner_id])
                ->where(["web_partner_account_log.service" => "cruise", "web_partner_account_log.action_type" => "booking"])
                ->join('cruise_booking_travelers', 'cruise_booking_travelers.cruise_booking_id = cruise_booking_list.id', 'Left')
                ->join("admin_users", "admin_users.id=cruise_booking_list.agent_staff_id", 'left')
                ->join('web_partner', "cruise_booking_list.web_partner_id = web_partner.id", 'left')
                ->join('super_admin_users', "super_admin_users.id = cruise_booking_list.assign_user", 'left')->where($array)
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=cruise_booking_list.id", 'Left');



            $builder->where('cruise_booking_list.payment_status', 'Successful');
            $builder->whereNotIn("cruise_booking_list.booking_status", ['Failed', 'Processing']);

            return $builder->groupBy("cruise_booking_list.id")->orderBy("cruise_booking_list.id", "DESC")->get()->getResultArray();
        } else {
            return null;
        }
    }

    public function bus_travellers_list($booking_id)
    {
        return  $this->db->table("bus_booking_travelers")->select('*')->where('bus_booking_id', $booking_id)->orderBy("id", "DESC")->get()->getResultArray();
    }

    public function getData($tableName, $Where, $Select, $rowType = 0)
    {
        $builder = $this->db->table($tableName);
        if (!empty($Where)) {
            $builder->where($Where);
        }
        $builder->select($Select);
        if ($rowType == 1) {
            return $builder->get()->getResultArray();
        } else {
            return $builder->get()->getRowArray();
        }
    }
}
