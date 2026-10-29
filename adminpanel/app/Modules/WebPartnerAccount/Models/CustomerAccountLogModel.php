<?php

namespace App\Modules\WebPartnerAccount\Models;

use CodeIgniter\Model;

class CustomerAccountLogModel extends Model
{
    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function credit_notes_holiday($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['customer_account_log.created >='=> $from_date,'customer_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,holiday_booking_list.api_supplier,holiday_booking_list.confirmation_no,
        holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.travel_date,holiday_booking_list.total_price,
       holiday_booking_list.payment_status,holiday_booking_list.booking_status,holiday_booking_list.total_price,holiday_booking_list.amendment_charges,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,holiday_booking_list.booking_source,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'holiday','customer_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = web_partner_account_log.id", 'Right')->whereIn('holiday_booking_list.booking_source',['Wl_b2b','Wl_b2c'])
            ->orderBy("customer_account_log.id", "DESC")->paginate(40);

        return $data ;
    }

    public function credit_notes_holiday_search($web_partner_id,$data)
    {
        $arrayValue = array("booking_ref_number" => "holiday_booking_list.booking_ref_number", "first_name" => "holiday_booking_travelers.first_name", "last_name" => "holiday_booking_travelers.last_name",   "booking_status" => "holiday_booking_list.booking_status", "payment_status" => "holiday_booking_list.payment_status");
        $bookingSource = ["B2B"=>"Wl_b2b","B2C"=>"Wl_b2c"];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("customer_account_log.id as AccountLogId,customer_account_log.web_partner_id,customer_account_log.acc_ref_number,customer_account_log.payment_mode,
        customer_account_log.action_type,customer_account_log.service,customer_account_log.service_log,customer_account_log.transaction_type,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,holiday_booking_list.api_supplier,
        holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.travel_date, 
       holiday_booking_list.confirmation_no, holiday_booking_list.total_price,holiday_booking_list.booking_status,
       holiday_booking_list.payment_status,holiday_booking_list.amendment_charges,holiday_booking_list.total_price,
        customer_account_log.credit,customer_account_log.debit,customer_account_log.balance,customer_account_log.remark,holiday_booking_list.booking_source,
        customer_account_log.created,customer_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['customer_account_log.action_type' => 'refund','customer_account_log.service' => 'holiday','customer_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = customer_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = customer_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['customer_account_log.created >=' => $from_date, 'customer_account_log.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['holiday_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
        }

        if(isset($bookingSource[$data['booking_source']])){
            $builder->where("holiday_booking_list.booking_source",$bookingSource[$data['booking_source']]);
        }else{
            $builder->whereIn('holiday_booking_list.booking_source',['Wl_b2b','Wl_b2c']);
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

        return $builder->orderBy("customer_account_log.id", "DESC")->paginate(40);
    }
}