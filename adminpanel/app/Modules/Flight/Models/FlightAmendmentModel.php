<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightAmendmentModel extends Model
{
    protected $table = 'flight_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_amendment_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date];
 
        $builder = $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(admin_users.first_name," ",admin_users.last_name) as admin_staff_name,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
           ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left') 
            ->join('agent', "flight_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left') 
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=flight_amendment.wl_customer_id", 'left')
            ->where("flight_amendment.web_partner_id", $web_partner_id);

 

        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "approved") {
            $builder->where(["flight_amendment.amendment_status" => "approved"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "requested") {
            $builder->where(["flight_amendment.amendment_status" => "requested"]);
        }

        if ($bookingType == "rejected") {
            $builder->where(["flight_amendment.amendment_status" => "rejected"]);
        }
        if ($bookingType == "processing") {
            $builder->where(["flight_amendment.amendment_status" => "processing"]);
        }
 
      
        return $builder->groupBy("flight_amendment.id")->orderBy("flight_amendment.id", "DESC")->paginate(30);
    }





    function search_data($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {  
        $arrayValue = array("booking_ref_number" => "flight_booking_list.booking_ref_number", "ticket_number" => "flight_booking_travelers.ticket_number", "pnr" => "flight_booking_list.pnr","booking_status" => "flight_booking_list.booking_status","amendment_status" => "flight_amendment.amendment_status","id" => "flight_amendment.id");
       
        $builder =$this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,flight_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(admin_users.first_name," ",admin_users.last_name) as admin_staff_name,
        agent.company_name,agent.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name') 
        ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left') 
        ->join('agent', "flight_amendment.wl_agent_id = agent.id", 'left')
        ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
        ->join("customer", "customer.id=flight_amendment.wl_customer_id", 'left')
        ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left'); 
        $builder->where("flight_amendment.web_partner_id", $web_partner_id); 

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];
        
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['flight_amendment.wl_agent_id'] = $data['tts_web_partner_info'];
        }
        if ($bookingType == "requested") {
            $array['flight_amendment.amendment_status'] = "requested";
        }
        if ($bookingType == "approved") {
            $array['flight_amendment.amendment_status'] = "approved";
        }
        if ($bookingType == "rejected") {
            $array['flight_amendment.amendment_status'] = "rejected";
        }
        if ($bookingType == "processing") {
            $array['flight_amendment.amendment_status'] = "processing";
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
        return $builder->groupBy("flight_amendment.id")->orderBy("flight_amendment.id", "DESC")->paginate(30);
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }



    function flight_amendment_detail_by_id($amendment_id, $web_partner_id)
    {
        return $this->select("*")->where(["id" => $amendment_id, "amendment_status" => "approved", "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }

    function flight_booking_travelers_detail($flight_booking_id, $selectedPaxId)
    {
        return $this->db->table("flight_booking_travelers")->select("*")->where(["flight_booking_id" => $flight_booking_id, "refund_account_id" => null])->whereIn("id", $selectedPaxId)->get()->getResultArray();
    }

    public function agent_user_available_balance($tableName, $key, $user_id, $web_partner_id)
    {
        return $this->db->table($tableName)->select('balance')->where($key, $user_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    function updateWithTableData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }

    public function flight_refund_list($web_partner_id,$bookingType = 'all', $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['flight_amendment.refund_date >=' => $from_date, 'flight_amendment.refund_date <=' => $to_date];

        $builder = $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.booking_source,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        ')->where(['flight_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('agent', "flight_amendment.wl_agent_id = agent.id", 'left');
        if ($source != 'dashboard') {
            $builder->whereIn("refund_status", array('Open', 'Close'));
            $builder->where($array);
        }

        return $builder->orderBy("flight_amendment.id", "DESC")->paginate(40);
    }

    function agent_user_gst_state_code($tableName, $user_id, $web_partner_id)
    {
        $builder = $this->db->table($tableName);
        $builder->where("id", $user_id);
        $builder->where('web_partner_id', $web_partner_id);
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }


    function search_flight_refund_list($web_partner_id,$data)
    {
        if ($data['key']){
            unset($data['from_date'],$data['to_date']);
        }
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            //$Datearray = ['flight_amendment.modified >=' => $from_date, 'flight_amendment.modified <=' => $to_date];

            $Datearray = ['flight_amendment.refund_date >=' => $from_date, 'flight_amendment.refund_date <=' => $to_date];

        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['flight_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }


        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "flight_amendment.id", "refund_status" => "flight_amendment.refund_status", 'pnr' => 'flight_booking_list.pnr');

        $builder = $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,flight_booking_list.booking_source,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        ')->where(['flight_amendment.web_partner_id' => $web_partner_id])
            ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('agent', "flight_amendment.wl_agent_id = agent.id", 'left');
        $builder->whereIn("refund_status", array('Open', 'Close'));
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {

            $builder->where($Datearray);

        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("flight_amendment.id")->orderBy("flight_amendment.refund_date", "DESC")->paginate(40);
    }
}


