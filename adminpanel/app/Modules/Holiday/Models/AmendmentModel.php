<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class AmendmentModel extends Model
{
    protected $table = 'holiday_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function amendment_list($web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['holiday_amendment.created >=' => $from_date, 'holiday_amendment.created <=' => $to_date];

        $builder = $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.travel_date,
        holiday_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')

            ->join('agent', "holiday_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.agent_id=holiday_amendment.wl_agent_id", 'left')
            ->join("admin_users", "admin_users.id=holiday_amendment.agent_staff_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=holiday_amendment.wl_customer_id", 'left')
            ->where("holiday_amendment.web_partner_id", $web_partner_id);



        if ($source != "dashboard") {
            $builder->where($array);
        }

        if ($bookingType == "approved") {
            $builder->where(["holiday_amendment.amendment_status" => "approved"]);
            if ($source != "dashboard") {
                $builder->where($array);
            }
        }
        if ($bookingType == "requested") {
            $builder->where(["holiday_amendment.amendment_status" => "requested"]);
        }

        if ($bookingType == "rejected") {
            $builder->where(["holiday_amendment.amendment_status" => "rejected"]);
        }
        if ($bookingType == "processing") {
            $builder->where(["holiday_amendment.amendment_status" => "processing"]);
        }


        return $builder->groupBy("holiday_amendment.id")->orderBy("holiday_amendment.id", "DESC")->paginate(30);
    }


    function search_bookings($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {

        $arrayValue = array("amendment_status" => "holiday_amendment.amendment_status", "booking_ref_number" => "holiday_booking_list.booking_ref_number", "booking_status" => "holiday_booking_list.booking_status", "id" => "holiday_amendment.id");
        $bookinSource = ["B2B" => "Wl_b2b", "B2C" => "Wl_b2c"];
        $builder = $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.travel_date,
        holiday_booking_list.booking_source,customer.customer_id,customer.email_id as customeremailid,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,
        agent.company_name,agent.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->join('agent', "holiday_amendment.wl_agent_id = agent.id", 'left')
            ->join("agent_users", "agent_users.agent_id=holiday_amendment.wl_agent_id", 'left')
            ->join("admin_users", "admin_users.id=holiday_amendment.agent_staff_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=holiday_amendment.wl_customer_id", 'left');

        $builder->where("holiday_amendment.web_partner_id", $web_partner_id);
        if (isset ($data['from_date']) && isset ($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['holiday_amendment.created >=' => $from_date, 'holiday_amendment.created <=' => $to_date];
        }

        if (isset ($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];
        if (isset ($data['booking_source']) && $data['booking_source'] != "") {
            $array['holiday_booking_list.booking_source'] = $bookinSource[$data['booking_source']];
        }

        if (isset ($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['holiday_booking_list.wl_agent_id'] = $data['tts_web_partner_info'];
        }


        if ($bookingType == "requested") {
            $array['holiday_amendment.amendment_status'] = "requested";
        }
        if ($bookingType == "approved") {
            $array['holiday_amendment.amendment_status'] = "approved";
        }
        if ($bookingType == "rejected") {
            $array['holiday_amendment.amendment_status'] = "rejected";
        }
        if ($bookingType == "processing") {
            $array['holiday_amendment.amendment_status'] = "processing";
        }


        if (!empty ($array)) {
            $builder->where($array);
        }

        if (!empty ($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset ($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }



        return $builder->groupBy("holiday_amendment.id")->orderBy("holiday_amendment.id", "DESC")->paginate(30);
    }

    function updateData($updateData, $Where)
    {
        return $this->select("*")->where($Where)->set($updateData)->update();
    }

    function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    function updateWithTableData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }

    public function amendment_detail($web_partner_id, $amendment_id)
    {
        $builder = $this->db->table('holiday_amendment');
        $builder = $this->select("holiday_amendment.*,holiday_booking_list.id as Booking_id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.tts_search_token,holiday_booking_list.customer_fare_break_up,holiday_booking_list.amendment_charges,
        concat('[', group_concat(JSON_OBJECT('id', holiday_booking_travelers.id,'email_id',holiday_booking_travelers.email_id,'title',holiday_booking_travelers.title,'first_name',holiday_booking_travelers.first_name,'last_name',holiday_booking_travelers.last_name,'pax_type',holiday_booking_travelers.pax_type,'gendar',holiday_booking_travelers.gendar,'mobile_number',holiday_booking_travelers.mobile_number,'age',holiday_booking_travelers.age,'child_need_bed',holiday_booking_travelers.child_need_bed,'pan_number',holiday_booking_travelers.pan_number,'lead_pax',holiday_booking_travelers.lead_pax,'passport_number',holiday_booking_travelers.passport_number) separator ','), ']') as travellers,
       holiday_booking_list.booking_source,holiday_booking_list.coupon_info,holiday_booking_list.conveniencefee,holiday_booking_list.web_partner_id,holiday_booking_list.wl_customer_id,holiday_booking_list.wl_agent_id,holiday_booking_list.confirmation_no,holiday_booking_list.webpartner_assign_user, holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.created,
      holiday_booking_list.agent_fare_break_up,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.booking_channel,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join('admin_users', 'admin_users.id = holiday_amendment.agent_staff_id', 'Left')
            ->join("agent_users", "agent_users.agent_id=holiday_amendment.wl_agent_id", 'Left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'Left')
            ->join("holiday_booking_travelers", "holiday_booking_travelers.holiday_booking_id=holiday_booking_list.id", 'left');
        $builder->where(['holiday_amendment.id' => $amendment_id, 'holiday_amendment.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        if ($query) {
            if ($query['booking_source'] == "Wl_b2b") {
                $builder = $this->db->table('agent_account_log');
                $builder->select("agent_account_log.id,agent_account_log.acc_ref_number,agent_account_log.debit,agent_account_log.credit,agent_account_log.balance,agent_account_log.invoice_number,agent_account_log.extra_param,agent_account_log.service,agent_account_log.remark,agent_account_log.service_log,agent_account_log.transaction_id,agent_account_log.payment_mode,agent_account_log.transaction_type,agent_account_log.action_type,agent_account_log.created");
                $builder->where(['agent_account_log.booking_ref_no' => $query['Booking_id'], "agent_account_log.web_partner_id" => $web_partner_id, "agent_account_log.service" => "holiday"]);
                $query['paymentInfo'] = $builder->get()->getResultArray();
            } else {
                $builder = $this->db->table('customer_account_log');
                $builder->select("customer_account_log.id,customer_account_log.acc_ref_number,customer_account_log.debit,customer_account_log.credit,customer_account_log.service,customer_account_log.remark,customer_account_log.service_log,customer_account_log.transaction_id,customer_account_log.extra_param,customer_account_log.invoice_number,customer_account_log.balance,customer_account_log.payment_mode,customer_account_log.transaction_type,customer_account_log.action_type,customer_account_log.created");
                $builder->where(['customer_account_log.booking_ref_no' => $query['Booking_id'], "booking_ref_no.service" => "holiday", "customer_account_log.web_partner_id" => $web_partner_id]);
                $query['paymentInfo'] = $builder->get()->getResultArray();
            }
            // $flightBookingTravelersbuilder = $this->db->table('holiday_booking_travelers');
            // $query['travellers'] = $flightBookingTravelersbuilder->select("*")->where(['holiday_booking_travelers.holiday_booking_id' => $query['Booking_id']])->get()->getResultArray();
        }
        return $query;
    }

    function amendment_detail_by_id($amendment_id, $web_partner_id)
    {
        $builder = $this->db->table('holiday_amendment');
        return $builder->select("*")->where(["id" => $amendment_id, "web_partner_id" => $web_partner_id, "amendment_status" => "approved"])->get()->getRowArray();
    }



    function webpartner_gst_state_code($web_partner_id)
    {
        $builder = $this->db->table('web_partner');
        $builder->where("id", $web_partner_id);
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function agent_user_gst_state_code($tableName, $user_id, $web_partner_id)
    {
        return $this->db->table($tableName)->where('id', $user_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function refund_list($web_partner_id, $bookingType = "all", $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['holiday_amendment.created >=' => $from_date, 'holiday_amendment.created <=' => $to_date];

        $builder = $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.booking_ref_number,holiday_booking_list.travel_date,holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,holiday_booking_list.total_price,holiday_booking_list.booking_source,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->join('admin_users', 'admin_users.id = holiday_amendment.agent_staff_id', 'Left')
            ->join("agent_users", "agent_users.agent_id=holiday_amendment.wl_agent_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=holiday_amendment.wl_customer_id", 'left')
            ->where('holiday_amendment.web_partner_id', $web_partner_id)
            ->whereIn('booking_source', ['Wl_b2b', 'Wl_b2c']);
        if ($source != 'dashboard') {
            $builder->where($array);
        }

        return $builder->orderBy("holiday_amendment.id", "DESC")->paginate(40);
    }

    function search_refund_list($data, $web_partner_id)
    {
        $BookingSource = array('B2B' => 'Wl_b2b', 'B2C' => 'Wl_b2c');
        if (isset ($data['from_date']) && isset ($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray = ['holiday_amendment.modified >=' => $from_date, 'holiday_amendment.modified <=' => $to_date];
        }
        if (isset ($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['holiday_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }
        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "holiday_amendment.id", "refund_status" => "holiday_amendment.refund_status");

        $builder = $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.booking_ref_number,holiday_booking_list.travel_date,holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_name,holiday_booking_list.total_price,holiday_booking_list.booking_source,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,CONCAT(customer.first_name," ",customer.last_name) as customer_name')
            ->join('admin_users', 'admin_users.id = holiday_amendment.agent_staff_id', 'Left')
            ->join("agent_users", "agent_users.agent_id=holiday_amendment.wl_agent_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join("customer", "customer.id=holiday_amendment.wl_customer_id", 'left');
        $builder->whereIn("refund_status", array('Open', 'Close'));

        if (isset ($data['booking_source']) && $data['booking_source'] != "") {
            $array['holiday_booking_list.booking_source'] = $BookingSource[$data['booking_source']];
        }
        if (!empty ($array)) {
            $builder->where($array);
        }
        if (!empty ($Datearray)) {
            $builder->groupStart();
            $builder->where($Datearray);
            $Datearray = ['holiday_amendment.refund_date >=' => $from_date, 'holiday_amendment.refund_date <=' => $to_date];
            $builder->orWhere($Datearray);
            $builder->groupEnd();
        }
        if (isset ($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("holiday_amendment.id")->where(['holiday_amendment.web_partner_id' => $web_partner_id])->orderBy("holiday_amendment.id", "DESC")->paginate(40);
    }

    public function web_partner_available_balance($web_partner_id)
    {
        return $this->db->table('web_partner_account_log')->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    function get_list_by_table_name($table, $field, $where)
    {
        $builder = $this->db->table($table);
        $builder->select($field);
        if (!empty ($where)) {
            $builder->where($where);
        }
        return $builder->get()->getRowArray();
    }
}
