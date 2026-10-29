<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;
use DateTime;

class FlightBookingModel extends Model
{
    protected $table = 'flight_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_booking_list($web_partner_id, $userId, $userType)
    {
        $array = ['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId, 'flight_booking_list.booking_source' => $userType];

        return $this->select('flight_booking_list.id, flight_booking_list.agent_fare_break_up,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
        flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,
        flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,
        flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
        CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->where($array)
            ->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }

    function getCalenderList($web_partner_id, $userId, $data)
    {
        if (!array_key_exists("to_date", $data)) {
            $year = date('Y');
        } else {
            $year = date('Y', strtotime($data['to_date']));
        }


        if (!array_key_exists("to_date", $data)) {
            $to_date = new DateTime('now');
            $to_date->modify('last day of this month');
            $to_date = $to_date->format('d M Y');
            $data['to_date'] = $to_date;
        }

        if (!array_key_exists("from_date", $data)) {
            $data['from_date'] = date('d M Y');
        }


        if (!array_key_exists("month", $data)) {
            $data['month'] = date('m');
        }


        $builder = $this->db->table("flight_booking_list");

        if (($data['from_date']) && $data['to_date']) {

            $from_date = date('Y-m-d', strtotime($data['from_date']));
            $to_date = date('Y-m-d', strtotime($data['to_date']));

            $array = ['flight_booking_list.departure_date >=' => $from_date, 'flight_booking_list.departure_date <=' => $to_date, "flight_booking_list.trip_indicator" => 1];

            $builder->select("flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.resultIndex,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.journey_type,
            flight_booking_list.trip_indicator,flight_booking_list.book_request,flight_booking_list.booking_status,flight_booking_list.departure_date,flight_booking_list.segments,
            flight_booking_list.tts_search_token,flight_booking_travelers.title,flight_booking_travelers.first_name,flight_booking_travelers.last_name,flight_booking_list.is_domestic,
                    
            concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal) separator ','), ']') as travelersInfo
            ")
                ->where(['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId]);
             $builder->whereIn('flight_booking_list.booking_status', ['Confirmed', 'Hold']);
            $builder->where($array);
            $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'Left');

            $builder->orderBy("flight_booking_list.departure_date", "ASC");
            $builder->groupBy('flight_booking_list.id');
            $query = $builder->get()->getResultArray();

            return $query;
        } elseif ($data['month']) {
            $builder->select("flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.resultIndex,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.journey_type,
            flight_booking_list.trip_indicator,flight_booking_list.book_request,flight_booking_list.booking_status,flight_booking_list.departure_date,flight_booking_list.segments,
            flight_booking_list.tts_search_token,flight_booking_travelers.title,flight_booking_travelers.first_name,flight_booking_travelers.last_name,flight_booking_list.is_domestic,
                    
            concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal) separator ','), ']') as travelersInfo
            ")->where(['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId]);
            $builder->where("MONTH(departure_date) = {$data['month']} AND YEAR(departure_date) = {$year}");
            $builder->where(["flight_booking_list.trip_indicator" => 1]);
            $builder->whereIn('flight_booking_list.booking_status', ['Confirmed', 'Hold']);
            $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'Left');
            $builder->orderBy("flight_booking_list.departure_date", "ASC");
            $builder->groupBy('flight_booking_list.id');
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    function getReturnBookingDetail($web_partner_id, $userId, $tts_search_token, $data)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select("flight_booking_list.id,flight_booking_list.trip_indicator,flight_booking_list.segments,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.booking_status,flight_booking_list.departure_date");
        $builder->where(["trip_indicator" => 2, "tts_search_token" => $tts_search_token, 'flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId]);
        $query = $builder->get()->getRowArray();

        return $query;
    }

    public function flight_booking_list_sales_report($web_partner_id, $userId, $bookingType = "all", $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];


        $builder = $this->select('flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status, flight_booking_list.agent_fare_break_up,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.web_partner_fare_break_up,
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->where($array)->where("flight_booking_list.web_partner_id", $web_partner_id)
            ->where("flight_booking_list.wl_agent_id", $userId)
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = flight_booking_list.assign_user", 'left')
            ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left');
        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(30);
    }

    function flight_service_booking_sales_report($web_partner_id, $userId, $bookingType = "all", $source = null)
    {
        $builder = $this->select('flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.agent_fare_break_up,flight_booking_list.api_supplier,flight_booking_list.supplier_booking_id,web_partner.company_name,web_partner.company_id,flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.segments,flight_booking_list.web_partner_fare_break_up,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name ,flight_booking_list.lead_pax,flight_booking_travelers.ticket_number,flight_booking_travelers.date_of_birth')
            ->where("flight_booking_list.web_partner_id", $web_partner_id)->where("flight_booking_list.wl_agent_id", $userId)
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = flight_booking_list.assign_user", 'left');

        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }

    function search_flight_service_bookings_sales_report($web_partner_id, $userId, $data, $bookinglistype = "all")
    {

        $builder = $this->select('flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.agent_fare_break_up,flight_booking_list.api_supplier,flight_booking_list.supplier_booking_id,web_partner.company_name,web_partner.company_id,flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.segments,flight_booking_list.web_partner_fare_break_up,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name,flight_booking_list.lead_pax,flight_booking_travelers.ticket_number,flight_booking_travelers.date_of_birth')
            ->where("flight_booking_list.web_partner_id", $web_partner_id)->where("flight_booking_list.wl_agent_id", $userId)
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = flight_booking_list.assign_user", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }

        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }
    function search_bookings_sales_report($web_partner_id, $userId, $data, $bookinglistype = "all")
    {


        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "flight_booking_travelers.first_name", "last_name" => "flight_booking_travelers.last_name", "ticket_number" => "ticket_number", "pnr" => "flight_booking_list.pnr", "web_partner_fare_break_up" => "flight_booking_list.web_partner_fare_break_up", "booking_status" => "flight_booking_list.booking_status", "payment_status" => "flight_booking_list.payment_status");
        $builder = $this->select('flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.issue_supplier,flight_booking_list.assign_user,flight_booking_list.is_manual,flight_booking_list.update_ticket_by,flight_booking_list.web_partner_fare_break_up,flight_booking_list.api_supplier,flight_booking_list.supplier_booking_id,web_partner.company_name,web_partner.company_id,flight_booking_list.id,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as assign_user_name ,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')
            ->where("flight_booking_list.web_partner_id", $web_partner_id)->where("flight_booking_list.wl_agent_id", $userId)
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')
            ->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
            ->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left')
            ->join('super_admin_users', "super_admin_users.id = flight_booking_list.assign_user", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['flight_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        $builder->where('flight_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("flight_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
    }



    public function flight_booking_detail($web_partner_id, $booking_refrence_number, $userId, $userType)
    {
        $array = ['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId, 'flight_booking_list.booking_source' => $userType, 'flight_booking_list.booking_ref_number' => $booking_refrence_number];

        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'agent_fare',flight_booking_travelers.agent_fare,'date_of_birth',flight_booking_travelers.date_of_birth) separator ','), ']') as travelersInfo,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left');
        $builder->where($array);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $builder = $this->db->table('flight_booking_list');
            $builder->select("agent_account_log.id,agent_account_log.acc_ref_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.service,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.action_type,agent_account_log.created")
                ->join("agent_account_log", "agent_account_log.booking_ref_no=flight_booking_list.id");
            $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id, "agent_account_log.service" => "flight"]);
            $query['paymentInfo'] = $builder->get()->getResultArray();
        }
        return $query;
    }



    function search_bookings($data, $web_partner_id, $userId, $userType)
    {

        $user_array = ['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId, 'flight_booking_list.booking_source' => $userType];

        $arrayValue = array("booking_ref_number" => "booking_ref_number", "first_name" => "flight_booking_travelers.first_name", "last_name" => "flight_booking_travelers.last_name", "ticket_number" => "ticket_number", "pnr" => "flight_booking_list.pnr", "booking_status" => "flight_booking_list.booking_status", "payment_status" => "flight_booking_list.payment_status", "web_partner_fare_break_up" => "flight_booking_list.web_partner_fare_break_up");

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
                return $this->select('flight_booking_list.id, flight_booking_list.agent_fare_break_up,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
                flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
                    ->where($array)->where($user_array)->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);

            } else {
                $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];
                return $this->select('flight_booking_list.id, flight_booking_list.agent_fare_break_up,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
                flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
                    ->where($array)->where($user_array)->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
            }
        } else {

            return $this->select('flight_booking_list.id, flight_booking_list.agent_fare_break_up,flight_booking_list.booking_ref_number,flight_booking_list.journey_type,flight_booking_list.web_partner_fare_break_up,
            flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,flight_booking_list.created,flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(flight_booking_travelers.title," ",flight_booking_travelers.first_name," ",flight_booking_travelers.last_name) as lead_passenger_name')->join("agent_users", "agent_users.id=flight_booking_list.wl_agent_staff_id", 'left')->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id", 'left')
                ->where($user_array)->like($arrayValue[trim($data['key'])], trim($data['value']))->groupBy("flight_booking_list.id")->orderBy("flight_booking_list.id", "DESC")->paginate(40);
        }

    }
    function getBookingWithVariableFieldNameData($booking_refrence_number, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    public function flight_amendment_itinerary_detail($web_partner_id, $booking_refrence_number)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_booking_list.id,flight_booking_list.pnr,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth,'booking_status',flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }
    public function amendment_list($web_partner_id, $booking_reference_number)
    {
        $result = $this->db->table('flight_amendment')->select("flight_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
            ->where(["flight_amendment.web_partner_id" => $web_partner_id, "flight_amendment.booking_ref_no" => $booking_reference_number])
            ->get()->getResultArray();
        return $result;
    }
    public function flight_amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("flight_amendment.*,flight_booking_list.id as flightbookingid,flight_booking_list.pnr,flight_booking_list.is_domestic,flight_booking_list.tts_search_token,flight_booking_list.booking_ref_number,flight_booking_list.search_request,flight_booking_list.segments,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth,'booking_status',flight_booking_travelers.booking_status) separator ','), ']') as travelersInfo");
        $builder->where(['flight_amendment.id' => $amendment_id, 'flight_amendment.web_partner_id' => $web_partner_id]);
        $builder->join('flight_amendment', "flight_amendment.booking_ref_no = flight_booking_list.id");
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    public function pax_details($pax_id)
    {
        $builder = $this->db->table('flight_booking_travelers');
        $builder->select("title,first_name,last_name");
        $builder->whereIn('flight_booking_travelers.id', $pax_id);

        $builder->groupBy('flight_booking_travelers.id');
        $query = $builder->get()->getResultArray();
        return $query;
    }
}