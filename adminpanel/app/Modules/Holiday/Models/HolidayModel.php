<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table = 'holiday_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function auto_suggest($term)
    {
        if ($term) {
            $destination = $this->db->table('holiday_destination')->select("id,destination_name as Name")->where('holiday_destination.status', 'active')->like('destination_name', $term)->get()->getResultArray();
            $themes = $this->db->table('holiday_themes')->select("id,theme_name as Name")->where('holiday_themes.status', 'active')->like('theme_name', $term)->get()->getResultArray();
            $holiday_list = $this->db->table('holiday_list')->select("id,holiday_name as Name")->where('holiday_list.status', 'active')->like('holiday_name', $term)->get()->getResultArray();

            return ['destination' => $destination, 'themes' => $themes, 'holiday_list' => $holiday_list];
        } else {
            $themes = $this->db->table('holiday_themes')->select("id,theme_name as Name")->where('holiday_themes.status', 'active')->like('theme_name', $term)->limit(8)->get()->getResultArray();
            return ['destination' => null, 'themes' => $themes, 'holiday_list' => null];
        }
    }

    function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name')->get()->getResultArray();
    }

    public function offers_list()
    {
        return $this->db->table('super_admin_offers')->select('id,title,description,service,url,image')
            ->where('status', 'active')->where('service', 'holiday')->limit(9)->orderBy('id', 'DESC')->get()->getResultArray();
    }

    public function holiday_themes_list()
    {
        $holiday_themes = $this->db->table('holiday_themes')->select('holiday_themes.id,holiday_themes.theme_name,holiday_themes.theme_slug,holiday_themes.theme_image,holiday_themes.starting_price')
            ->where('holiday_themes.status', 'active')->where('holiday_themes.show_on_home', 1)->limit(20)->orderBy("holiday_themes.id", "DESC")
            ->get()->getResultArray();

        foreach ($holiday_themes as $key => $data) {
            $id = $data['id'];
            $total_packages = $this->db->table('holiday_list')->select('COUNT(id) as total_packages')->where('status', 'active')->where('find_in_set("' . $id . '", holiday_themes_id) <> 0')->get()->getRowArray();
            ;

            $holiday_themes[$key]['total_packages'] = $total_packages['total_packages'];
        }

        return $holiday_themes;
    }

    public function holiday_list()
    {
        return $this->db->table('holiday_list')->select('id,holiday_name,holiday_slug,starting_price_standard,no_of_night,starting_price_deluxe,holiday_image,starting_price_luxury,advance_value,advance_days,price_on_request,holiday_themes_id,holiday_destination_id,holiday_includes,holiday_inclusion_id,holiday_exclusion_id,status')
            ->where('holiday_list.status', 'active')->where('holiday_list.show_on_home', 1)->limit(8)->orderBy("holiday_list.id", "DESC")
            ->get()->getResultArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function get_block_detail_data($tts_search_token, $web_partner_id)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_holiday_log');
        $builder->select('response,service');
        $builder->where(['tts_search_token' => $tts_search_token, 'service' => 'blockholiday', 'web_partner_id' => $web_partner_id]);
        $builder->orderBy("id", "DESC");
        return $builder->get()->getRowArray();
    }

    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function super_admin_booking_pre_fix_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('pre_fix');
        return $builder->get()->getRowArray();
    }

    function insertBatchData($tableName, $insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }

    function getData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }


    function getDataArray($tableName, $where, $singalRecord = 1, $whereApply = 1, $selectedColumnValue = null)
    {
        $builder = $this->db->table($tableName);

        if ($selectedColumnValue != null) {
            $builder->select($selectedColumnValue);
        }
        if ($whereApply) {
            $builder->where($where);
        }
        if ($singalRecord) {
            return $builder->get()->getRowArray();
        } else {
            return $builder->get()->getResultArray();
        }
    }

    function holiday_settings_list_select($web_partner_id)
    {
        $builder = $this->db->table('website_common_data');
        $builder->select('key_source,value');
        $builder->whereIn('key_source', ['polices', 'payment_structure', 'documentation', 'cancellation_policy', 'refund_policy']);
        $builder->where(['web_partner_id' => $web_partner_id, 'service' => 'Holiday']);
        return $builder->get()->getResultArray();
    }

    function getBookingConfirmationData($bookingid)
    {

        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_source,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.agent_fare_break_up,holiday_booking_list.customer_fare_break_up,holiday_booking_list.conveniencefee,holiday_booking_list.coupon_info,
        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up");
        $builder->where(['holiday_booking_list.id' => $bookingid]);

        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();

        //get all travellers
        $builder = $this->db->table('holiday_booking_travelers');
        $builder->select("holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
        holiday_booking_travelers.pax_type,holiday_booking_travelers.gendar,holiday_booking_travelers.age,holiday_booking_travelers.child_need_bed,holiday_booking_travelers.pan_number,
        holiday_booking_travelers.passport_number,holiday_booking_travelers.child_need_bed");
        $builder->where(['holiday_booking_travelers.holiday_booking_id' => $bookingid]);
        $query['travellers'] = $builder->get()->getResultArray();

        return $query;
    }

    function getBookingWithVariableFieldNameData($bookingid, $fieldName)
    {

        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.id' => $bookingid]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $fieldName)
    {
        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.booking_ref_number' => $bookingRefNumber]);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    public function holiday_booking_list($webPartnerId, $userId, $userType, $bookingType = "all")
    {
        $tomorrowTimestamp = strtotime(date('Y-m-d'));
        $tomorrowTimestamp = date("Y-m-d", $tomorrowTimestamp);
        $fromDate = strtotime(date('Y-m-d', strtotime($tomorrowTimestamp)) . '00:00');
        $toDate = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $dateArray = [
            'holiday_booking_list.created >=' => $fromDate,
            'holiday_booking_list.created <=' => $toDate
        ];
        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.booking_source,holiday_booking_list.is_manual,holiday_booking_list.webpartner_assign_user,holiday_booking_list.webpartner_update_ticket_by,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.wl_agent_id,holiday_booking_list.wl_customer_id,
            holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
            holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
            holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
            agent.company_name, agent.company_id, CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')

            ->where('holiday_booking_list.web_partner_id', $webPartnerId)
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join('admin_users', "admin_users.id = holiday_booking_list.webpartner_assign_user", 'left')
            ->join('agent', "holiday_booking_list.wl_agent_id = agent.id", 'left');


        if ($bookingType === "Processing") {
            $builder->where('holiday_booking_list.booking_status', 'Processing');
        } elseif ($bookingType === "Cancelled") {
            $builder->whereIn('holiday_booking_list.booking_status', ['Cancelled', 'PartialCancelled']);
        }

        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate(30);
    }

    function search_data($data, $web_partner_id, $userId, $userType, $bookingType = "all", $source = null)
    {
        $arrayValue = array("booking_ref_number" => "holiday_booking_list.booking_ref_number", "booking_status" => "holiday_booking_list.booking_status", "payment_status" => "holiday_booking_list.payment_status", "first_name" => "holiday_booking_travelers.first_name", "last_name" => "holiday_booking_travelers.last_name");
        $bookinSource = ["B2B" => "Wl_b2b", "B2C" => "Wl_b2c"];

        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.booking_source,holiday_booking_list.is_manual,holiday_booking_list.webpartner_assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,holiday_booking_list.webpartner_update_ticket_by,
            holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
            holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.wl_agent_id,holiday_booking_list.wl_customer_id,
            holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
            agent.company_name, agent.company_id, CONCAT(admin_users.first_name," ",admin_users.last_name) as assign_user_name')


            ->where('holiday_booking_list.web_partner_id', $web_partner_id)
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join('admin_users', "admin_users.id = holiday_booking_list.webpartner_assign_user", 'left')
            ->join('agent', "holiday_booking_list.wl_agent_id = agent.id", 'left');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];
        if (isset($data['booking_source']) && $data['booking_source'] != "") {
            $array['holiday_booking_list.booking_source'] = $bookinSource[$data['booking_source']];
        }
        if ($bookingType == "Processing") {
            $array['holiday_booking_list.booking_status'] = "Processing";
        }
        if ($bookingType == "Cancelled") {
            $array['holiday_booking_list.booking_status'] = "Cancelled";
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
        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate(30);
    }

    public function search_data_sales_report($web_partner_id, $data, $page)
    {
        $arrayValue = array("booking_ref_number" => "holiday_booking_list.booking_ref_number", "booking_status" => "holiday_booking_list.booking_status", "payment_status" => "holiday_booking_list.payment_status", "first_name" => "holiday_booking_travelers.first_name", "last_name" => "holiday_booking_travelers.last_name");
        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,holiday_booking_list.agent_fare_break_up,holiday_booking_list.booking_source,holiday_booking_list.wl_customer_id,holiday_booking_list.wl_agent_id,holiday_booking_list.customer_fare_break_up,
         agent.company_name,agent.company_id,
                    ')->where('holiday_booking_list.web_partner_id', $web_partner_id)
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join('agent', "agent.id = holiday_booking_list.wl_agent_id", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (!empty($array)) {
            $builder->where($array);
        }

        if (!empty($Datearray)) {
            $builder->where($Datearray);
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        $builder->whereIn('holiday_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->where('holiday_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("holiday_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate($page);
    }

    public function data_sales_report($web_partner_id, $page)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
                    holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
                    holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
                    holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,holiday_booking_list.agent_fare_break_up,holiday_booking_list.booking_source,holiday_booking_list.wl_customer_id,holiday_booking_list.wl_agent_id,holiday_booking_list.customer_fare_break_up,
                     agent.company_name,agent.company_id,
                    ')->where($array)->where('holiday_booking_list.web_partner_id', $web_partner_id)
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join('agent', "agent.id = holiday_booking_list.wl_agent_id", 'left');
        $builder->whereIn('holiday_booking_list.booking_source', ['Wl_b2b', 'Wl_b2c']);
        $builder->where('holiday_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("holiday_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate($page);
    }

    function getBookingDetailData($web_partner_id, $ref)
    {
        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.tts_search_token,holiday_booking_list.customer_fare_break_up,
        concat('[', group_concat(JSON_OBJECT('id', holiday_booking_travelers.id,'email_id',holiday_booking_travelers.email_id,'title',holiday_booking_travelers.title,'first_name',holiday_booking_travelers.first_name,'last_name',holiday_booking_travelers.last_name,'pax_type',holiday_booking_travelers.pax_type,'gendar',holiday_booking_travelers.gendar,'mobile_number',holiday_booking_travelers.mobile_number,'age',holiday_booking_travelers.age,'child_need_bed',holiday_booking_travelers.child_need_bed,'pan_number',holiday_booking_travelers.pan_number,'lead_pax',holiday_booking_travelers.lead_pax,'passport_number',holiday_booking_travelers.passport_number) separator ','), ']') as travellers,holiday_booking_list.booking_source,holiday_booking_list.coupon_info,holiday_booking_list.conveniencefee,holiday_booking_list.web_partner_id,holiday_booking_list.wl_customer_id,holiday_booking_list.wl_agent_id,holiday_booking_list.confirmation_no,holiday_booking_list.webpartner_assign_user,holiday_booking_list.wl_agent_user_id,
        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.agent_fare_break_up,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.booking_channel,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,CONCAT(customer.first_name,' ',customer.last_name) as customer_name");
        $builder->where(['holiday_booking_list.booking_ref_number' => $ref, 'holiday_booking_list.web_partner_id' => $web_partner_id])
            ->join("holiday_booking_travelers", "holiday_booking_travelers.holiday_booking_id=holiday_booking_list.id", 'left')
            ->join("agent_users", "agent_users.agent_id=holiday_booking_list.wl_agent_id", 'left')
            ->join("customer", "customer.id=holiday_booking_list.wl_customer_id", 'left');
        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $query['package_details'] = json_decode($query['package_details'], true);
            if ($query['booking_source'] == 'Wl_b2b') {
                $builder = $this->db->table('agent_account_log');
                $builder->select("id,acc_ref_number,debit,credit,invoice_number,balance,service,remark,service_log,transaction_id,payment_mode,transaction_type,action_type,extra_param,created");
                $builder->where(['booking_ref_no' => $query['id'], 'web_partner_id' => $query['web_partner_id'], 'service' => 'holiday']);
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('agent');
                $agentBuilder->select("agent.company_name,agent_users.login_email,agent_users.mobile_no,agent_users.first_name,agent_users.last_name,agent.company_id");
                $agentBuilder->join("agent_users", "agent.id = agent_users.agent_id");
                $agentBuilder->where(['agent.web_partner_id' => $query['web_partner_id'], "agent.id" => $query['wl_agent_id']]);
                $query['AgentInfo'] = $agentBuilder->get()->getRowArray();
            } else {
                $builder = $this->db->table('customer_account_log');
                $builder->select("id,acc_ref_number,debit,credit,invoice_number,balance,service,remark,service_log,transaction_id,payment_mode,transaction_type,action_type,extra_param,created");
                $builder->where(['booking_ref_no' => $query['id'], 'web_partner_id' => $query['web_partner_id'], 'service' => 'holiday']);
                $query['paymentInfo'] = $builder->get()->getResultArray();
                $agentBuilder = $this->db->table('customer');
                $agentBuilder->select("customer.customer_id,customer.email_id,customer.mobile_no,customer.first_name,customer.last_name");
                $agentBuilder->where(['customer.web_partner_id' => $query['web_partner_id'], "customer.id" => $query['wl_customer_id']]);
                $query['CustomerInfo'] = $agentBuilder->get()->getRowArray();
            }

            $query['email'] = '';
            $query['mobile_no'] = '';
        }
        return $query;
    }

    public function amendment_list($web_partner_id, $booking_reference_number)
    {
        $result = $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users", "admin_users.id=holiday_amendment.agent_staff_id", 'left')
            ->where(["holiday_amendment.booking_ref_no" => $booking_reference_number, "holiday_amendment.web_partner_id" => $web_partner_id, 'amendment_id' => null])
            ->get()->getResultArray();
        if ($result) {
            foreach ($result as $key => $data) {
                $reply_data = HolidayModel::amendment_list_reply($booking_reference_number, $data['id']);
                $result[$key]['admin_reply'] = $reply_data;
            }
        }

        return $result;
    }

    public function amendment_list_reply($booking_reference_number, $amendment_id)
    {
        return $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
            ->join("admin_users", "admin_users.id=holiday_amendment.agent_staff_id", 'left')
            ->where(["holiday_amendment.booking_ref_no" => $booking_reference_number, 'amendment_id' => $amendment_id])
            ->get()->getResultArray();
    }
    function updateData($tableName, $whereClause, $data)
    {
        $this->db->table($tableName)->where($whereClause)->update($data);
    }
    function getDataRowType($tableName, $whereCondition, $field)
    {
        $builder = $this->db->table($tableName)->select($field);
        if ($whereCondition) {
            $builder->where($whereCondition);
        }
        return $builder->get()->getRowArray();
    }
    public function web_partner_available_balance($web_partner_id)
    {
        return $this->db->table('web_partner_account_log')->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    function updateWithTableData($tableName, $updateData, $Where)
    {
        return $this->db->table($tableName)->where($Where)->update($updateData);
    }

    public function agent_user_available_balance($tableName, $key, $user_id, $web_partner_id)
    {
        return $this->db->table($tableName)->select('balance')->where($key, $user_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function get_packages_autosuggestion($data, $web_partner_id)
    {
        return $this->db->table('holiday_list')->select('id,holiday_name,holiday_slug')->where('web_partner_id', $web_partner_id)->like("holiday_name", $data)->orLike('holiday_slug', $data)->limit(20)->get()->getResultArray();
    }
}
