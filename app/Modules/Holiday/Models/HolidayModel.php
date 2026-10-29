<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayModel extends Model
{
    protected $table = 'holiday_booking_list';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function auto_suggest($term, $web_partner_id)
    {
        if ($term) {
            $destination = $this->db->table('holiday_destination')->select("id,destination_name as Name")->where('holiday_destination.status', 'active')->where('holiday_destination.web_partner_id', $web_partner_id)->like('destination_name', $term)->get()->getResultArray();
            $themes = $this->db->table('holiday_themes')->select("id,theme_name as Name")->where('holiday_themes.status', 'active')->like('theme_name', $term)->where('holiday_themes.web_partner_id', $web_partner_id)->get()->getResultArray();
            $holiday_list = $this->db->table('holiday_list')->select("id,holiday_name as Name")->where('holiday_list.web_partner_id', $web_partner_id)->where('holiday_list.status', 'active')->like('holiday_name', $term)->get()->getResultArray();

            return ['destination' => $destination, 'themes' => $themes, 'holiday_list' => $holiday_list];
        } else {
            $themes = $this->db->table('holiday_themes')->select("id,theme_name as Name")->where('holiday_themes.status', 'active')->like('theme_name', $term)->where('holiday_themes.web_partner_id', $web_partner_id)->limit(8)->get()->getResultArray();
            return ['destination' => null, 'themes' => $themes, 'holiday_list' => null];
        }
    }


    public function slider_list()
    {
        return $this->db->table('slider')->select('*')
            ->where('status', 'active')->where('image_category', 'Home-Slider')->orderBy('id', 'DESC')->get()->getResultArray();
    }
    function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name')->get()->getResultArray();
    }

    function important_notification()
    {
        return $this->db->table('webpartner_notification')->where('status', 'active')->where('type', 'important')->limit(5)->get()->getResultArray();
    }

    function general_notification()
    {
        return $this->db->table('webpartner_notification')->where('status', 'active')->where('type', 'general')->limit(5)->get()->getResultArray();
    }
    public function offers_list($web_partner_id)
    {
        return $this->db->table('web_partner_offers')->select('id,title,description,service,url,image')->where('web_partner_id', $web_partner_id)
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

    public function get_packages_autosuggestion($web_partner_id, $data)
    {
        return $this->db->table('holiday_list')->select('id,holiday_name,holiday_slug')->where('web_partner_id', $web_partner_id)->like("holiday_name", $data)->orLike('holiday_slug', $data)->limit(20)->get()->getResultArray();
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

    function service_booking_pre_fix_code()
    {
        if (whitelabel['is_direct_website'] == "inactive") {
            $builder = $this->db->table('super_admin_website_setting');
        } else {
            $builder = $this->db->table('web_partner');
        }
        $builder->select('holiday_pre_fix as pre_fix');
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

    function getBookingConfirmationData($bookingid, $web_partner_id, $userId, $userType)
    {
        $array = ['holiday_booking_list.web_partner_id' => $web_partner_id, 'holiday_booking_list.wl_agent_id' => $userId, 'holiday_booking_list.booking_source' => $userType, 'holiday_booking_list.id' => $bookingid,];
        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.agent_fare_break_up,holiday_booking_list.conveniencefee,
        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up");
        $builder->where($array);

        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();

        //get all travellers
        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
        holiday_booking_travelers.pax_type,holiday_booking_travelers.gendar,holiday_booking_travelers.age,holiday_booking_travelers.child_need_bed,holiday_booking_travelers.pan_number,
        holiday_booking_travelers.passport_number,holiday_booking_travelers.child_need_bed");
        $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id");

        $query['travellers'] = $builder->get()->getResultArray();

        return $query;
    }

    function getBookingWithVariableFieldNameData($bookingid, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("holiday_booking_list");
        $builder->select($fieldName);
        $builder->where(['holiday_booking_list.booking_ref_number' => $bookingRefNumber, 'holiday_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    public function holiday_booking_list($web_partner_id, $userId, $userType)
    {
        $array = ['holiday_booking_list.web_partner_id' => $web_partner_id, 'holiday_booking_list.wl_agent_id' => $userId, 'holiday_booking_list.booking_source' => $userType];
        return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
            ->where($array)
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
    }


    public function search_data_sales_report($web_partner, $userId, $data, $page)
    {
        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
                    holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
                    holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
                    holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
                     web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
                    ')->where(['holiday_booking_list.web_partner_id' => $web_partner, 'holiday_booking_list.wl_agent_id' => $userId])
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join("agent_users", "agent_users.id=holiday_booking_list.agent_staff_id", 'left')
            ->join('web_partner', "holiday_booking_list.web_partner_id = web_partner.id", 'left');

        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array = [];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['holiday_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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
        $builder->where('holiday_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("holiday_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate($page);
    }

    public function data_sales_report($web_partner, $userId, $page)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        $builder = $this->select('holiday_booking_list.update_ticket_by,holiday_booking_list.is_manual,holiday_booking_list.assign_user,holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
                    holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,holiday_booking_list.web_partner_fare_break_up,
                    holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
                    holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name,
                     web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
                    ')->where($array)->where(['holiday_booking_list.web_partner_id' => $web_partner, 'holiday_booking_list.wl_agent_id' => $userId])
            ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
            ->join("agent_users", "agent_users.id=holiday_booking_list.agent_staff_id", 'left')
            ->join('web_partner', "holiday_booking_list.web_partner_id = web_partner.id", 'left');

        $builder->where('holiday_booking_list.payment_status', 'Successful');
        $builder->whereNotIn("holiday_booking_list.booking_status", ['Failed', 'Processing']);
        return $builder->groupBy("holiday_booking_list.id")->orderBy("holiday_booking_list.id", "DESC")->paginate($page);
    }



    function search_data($data, $web_partner_id, $userId, $userType)
    {
        $agent_array = ['holiday_booking_list.web_partner_id' => $web_partner_id, 'holiday_booking_list.wl_agent_id' => $userId, 'holiday_booking_list.booking_source' => $userType];

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];


                return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
       holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
                    ->where($agent_array)
                    ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
                    ->where($array)
                    ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);

            } else {
                $array = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];


                return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
        holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
                    ->where($agent_array)
                    ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
            }
        } else {


            return $this->select('holiday_booking_list.id,holiday_booking_list.web_partner_id,holiday_booking_list.package_name,holiday_booking_list.tts_search_token,
        holiday_booking_list.day_nights,holiday_booking_list.travel_date,holiday_booking_list.booking_ref_number,
        holiday_booking_list.payment_mode,holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,
       holiday_booking_list.created,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name')
                ->where($agent_array)
                ->join('holiday_booking_travelers', 'holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id', 'Left')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("holiday_booking_list.id", "DESC")->groupBy("holiday_booking_list.id")->paginate(40);
        }

    }


    function getBookingDetailData($ref, $web_partner_id, $userId, $userType)
    {

        $array = ['holiday_booking_list.web_partner_id' => $web_partner_id, 'holiday_booking_list.wl_agent_id' => $userId, 'holiday_booking_list.booking_source' => $userType, 'holiday_booking_list.booking_ref_number' => $ref];

        $builder = $this->db->table('holiday_booking_list');
        $builder->select("holiday_booking_list.id, holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,holiday_booking_list.tts_search_token,

        concat('[', group_concat(JSON_OBJECT('id', holiday_booking_travelers.id,'email_id',holiday_booking_travelers.email_id,'title',holiday_booking_travelers.title,'first_name',holiday_booking_travelers.first_name,'last_name',holiday_booking_travelers.last_name,'pax_type',holiday_booking_travelers.pax_type,'gendar',holiday_booking_travelers.gendar,'mobile_number',holiday_booking_travelers.mobile_number,'age',holiday_booking_travelers.age,'child_need_bed',holiday_booking_travelers.child_need_bed,'pan_number',holiday_booking_travelers.pan_number,'lead_pax',holiday_booking_travelers.lead_pax,'passport_number',holiday_booking_travelers.passport_number) separator ','), ']') as travellers,

        holiday_booking_list.booking_status,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_list.agent_fare_break_up,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.booking_channel,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name");
        $builder->where($array)
            ->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id")
            ->join("agent_users", "agent_users.id=holiday_booking_list.wl_agent_user_id", 'left');
        $builder->groupBy('holiday_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $query['package_details'] = json_decode($query['package_details'], true);

            $builder = $this->db->table('agent_account_log');
            $builder->select("id,acc_ref_number,debit,credit,invoice_number,balance,service,remark,service_log,transaction_id,payment_mode,transaction_type,action_type,extra_param,created");
            $builder->where(['booking_ref_no' => $query['id'], 'web_partner_id' => $web_partner_id, 'service' => 'holiday']);
            $query['paymentInfo'] = $builder->get()->getResultArray();



            $query['email'] = '';
            $query['mobile_no'] = '';
        }
        return $query;
    }

    public function amendment_list($web_partner_id, $booking_reference_number)
    {
        $result = $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'left')
            ->where(["holiday_amendment.web_partner_id" => $web_partner_id, "holiday_amendment.booking_ref_no" => $booking_reference_number, 'amendment_id' => null])
            ->get()->getResultArray();

        if ($result) {
            foreach ($result as $key => $data) {
                $reply_data = HolidayModel::amendment_list_reply($web_partner_id, $booking_reference_number, $data['id']);
                $result[$key]['admin_reply'] = $reply_data;
            }
        }

        return $result;
    }

    public function amendment_list_reply($web_partner_id, $booking_reference_number, $amendment_id)
    {
        return $this->db->table('holiday_amendment')->select("holiday_amendment.*,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name")
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'left')
            ->where(["holiday_amendment.web_partner_id" => $web_partner_id, "holiday_amendment.booking_ref_no" => $booking_reference_number, 'amendment_id' => $amendment_id])
            ->get()->getResultArray();
    }

}


