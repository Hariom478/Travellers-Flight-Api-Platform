<?php

namespace App\Modules\WebPartnerAccount\Models;

use CodeIgniter\Model;

class WebPartnerAccountLogModel extends Model
{
    protected $table = 'web_partner_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function account_logs_detail($web_partner_id)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
        return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
         super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
         hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
         bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
         visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
         car_booking_list.booking_ref_number as car_booking_ref_number,
         
          flight_booking_list.api_supplier,flight_booking_list.pnr,
           CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
            ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
            ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
            ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
            ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
            ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
            ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
            ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
            ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(10);
    }

    public function account_logs($web_partner_id, $searchdata = array())
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        if (isset($searchdata['from_date']) && isset($searchdata['to_date'])) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];
            $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
            return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
         super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
         hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
         bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
         visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
         car_booking_list.booking_ref_number as car_booking_ref_number,
         
          flight_booking_list.api_supplier,flight_booking_list.pnr,
           CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
                ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
                ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
                ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
                ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
                ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
                ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
                ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
                ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
                ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
                ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
        } else {
            $db = \Config\Database::connect();
            $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
            $subquery = $subquery->getCompiledSelect();
            $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
            return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
            web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
            web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
            web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
             super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
             hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
             bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
             visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
             car_booking_list.booking_ref_number as car_booking_ref_number,
             
              flight_booking_list.api_supplier,flight_booking_list.pnr,
               CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
            ')
                ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
                ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
                ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
                ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
                ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
                ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
                ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
                ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
                ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
                ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
        }
    }

    public function debit_account_logs($web_partner_id, $searchdata = array())
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        if (isset($searchdata['from_date']) && isset($searchdata['to_date'])) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];
            $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
            $array['web_partner_account_log.transaction_type'] = "debit";
            return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
         super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
         hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
         bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
         visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
         car_booking_list.booking_ref_number as car_booking_ref_number,
         
          flight_booking_list.api_supplier,flight_booking_list.pnr,
           CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
                ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
                ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
                ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
                ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
                ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
                ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
                ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
                ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
                ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
                ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
        } else {
            $db = \Config\Database::connect();
            $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
            $subquery = $subquery->getCompiledSelect();
            $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
            $array['web_partner_account_log.transaction_type'] = "debit";
            return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
            web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
            web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
            web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
             super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
             hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
             bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
             visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
             car_booking_list.booking_ref_number as car_booking_ref_number,
             
              flight_booking_list.api_supplier,flight_booking_list.pnr,
               CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
            ')
                ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
                ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
                ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
                ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
                ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
                ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
                ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
                ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
                ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
                ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
        }
    }



    public function account_logs_without_serach($web_partner_id)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
        return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
         super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
         hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
         bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
         visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
         car_booking_list.booking_ref_number as car_booking_ref_number,
         
          flight_booking_list.api_supplier,flight_booking_list.pnr,
           CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
            ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
            ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
            ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
            ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
            ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
            ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
            ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
            ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);


    }

    public function debit_account_logs_without_serach($web_partner_id)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $array['web_partner_account_log.web_partner_id'] = $web_partner_id;
        $array['web_partner_account_log.transaction_type'] = "debit";
        return $this->select('web_partner_account_log.id,web_partner_account_log.booking_confirmation_number,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,
         super_admin_payment_transaction.transaction_id, flight_booking_list.booking_ref_number as flight_booking_ref_number,
         hotel_booking_list.booking_ref_number as hotel_booking_ref_number,
         bus_booking_list.booking_ref_number as bus_booking_ref_number, holiday_booking_list.booking_ref_number as holiday_booking_ref_number,
         visa_booking_list.booking_ref_number as visa_booking_ref_number,cruise_booking_list.booking_ref_number as cruise_booking_ref_number,
         car_booking_list.booking_ref_number as car_booking_ref_number,
         
          flight_booking_list.api_supplier,flight_booking_list.pnr,
           CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')
            ->join('super_admin_users', 'super_admin_users.id = web_partner_account_log.user_id', 'Left')
            ->join('flight_booking_list', 'flight_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="flight"', 'Left')
            ->join('hotel_booking_list', 'hotel_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="hotel"', 'Left')
            ->join('bus_booking_list', 'bus_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="bus"', 'Left')
            ->join('holiday_booking_list', 'holiday_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="holiday"', 'Left')
            ->join('visa_booking_list', 'visa_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="visa"', 'Left')
            ->join('cruise_booking_list', 'cruise_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="cruise"', 'Left')
            ->join('car_booking_list', 'car_booking_list.id = web_partner_account_log.booking_ref_no AND web_partner_account_log.service="car"', 'Left')
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('super_admin_payment_transaction', 'super_admin_payment_transaction.id = web_partner_account_log.payment_transaction_id', 'Left')->where($array)
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);


    }

    public function available_balance($web_partner_id)
    {
        return $this->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }


    public function credit_notes($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")->where($array)
            ->where(['web_partner_account_log.action_type' => 'refund', 'web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('flight_booking_list', "flight_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
            ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data;
    }


    function credit_notes_search($web_partner_id, $data)
    {

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];

                $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['web_partner_account_log.action_type' => 'refund', 'web_partner_account_log.web_partner_id' => $web_partner_id])
                    ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right')
                    ->where($array)
                    ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

                return $data;

            } else {
                $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];

                $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['web_partner_account_log.action_type' => 'refund', 'web_partner_account_log.web_partner_id' => $web_partner_id])
                    ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

                return $data;
            }
        } else {

            $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*, flight_booking_travelers.id as TravelerId,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                ->where(['web_partner_account_log.action_type' => 'refund', 'web_partner_account_log.web_partner_id' => $web_partner_id])
                ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
                ->join('flight_booking_list', "flight_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
                ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

            return $data;
        }
    }


    public function credit_notes_hotel($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       hotel_booking_list.id as hotelBookingId,hotel_booking_list.booking_ref_number,hotel_booking_list.api_supplier,
        hotel_booking_list.supplier_booking_id,
        hotel_booking_list.lead_passenger_name,hotel_booking_list.city,hotel_booking_list.country_code,
        
        hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.confirmation_no,hotel_booking_list.hotel_name,
       
        hotel_booking_list.is_domestic,hotel_booking_list.payment_status,hotel_booking_list.booking_status,
        hotel_booking_list.total_price,hotel_booking_list.amendment_charges,hotel_booking_list.hotel_rooms_details,
        

        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")->where($array)
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('hotel_booking_list', "hotel_booking_list.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $builder ;
    }

    public function credit_notes_hotel_search($web_partner_id,$data)
    {

        $arrayValue = array("booking_ref_number" => "hotel_booking_list.booking_ref_number", "first_name" => "hotel_booking_list.lead_passenger_name", "last_name" => "hotel_booking_list.lead_passenger_name",   "booking_status" => "hotel_booking_list.booking_status", "payment_status" => "hotel_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       hotel_booking_list.id as hotelBookingId,hotel_booking_list.booking_ref_number,hotel_booking_list.api_supplier,
        hotel_booking_list.supplier_booking_id,
        hotel_booking_list.lead_passenger_name,hotel_booking_list.city,hotel_booking_list.country_code,
        
        hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.confirmation_no,hotel_booking_list.hotel_name,
       
        hotel_booking_list.is_domestic,hotel_booking_list.payment_status,hotel_booking_list.booking_status,
        hotel_booking_list.total_price,hotel_booking_list.amendment_charges,hotel_booking_list.hotel_rooms_details,
        

        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('hotel_booking_list', "hotel_booking_list.refund_account_id = web_partner_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];
        }

        if ($data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['hotel_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }

    public function credit_notes_holiday($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,holiday_booking_list.api_supplier,holiday_booking_list.travel_date,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,
        holiday_booking_list.package_name,holiday_booking_list.package_category,
       holiday_booking_list.confirmation_no, holiday_booking_list.total_price,
       holiday_booking_list.payment_status,holiday_booking_list.booking_status,
        holiday_booking_list.total_price,holiday_booking_list.amendment_charges,
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'holiday','web_partner_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data ;
    }

    public function credit_notes_holiday_search($web_partner_id,$data)
    {
        $arrayValue = array("booking_ref_number" => "holiday_booking_list.booking_ref_number", "first_name" => "holiday_booking_travelers.first_name", "last_name" => "holiday_booking_travelers.last_name",   "booking_status" => "holiday_booking_list.booking_status", "payment_status" => "holiday_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       holiday_booking_list.id as BookingId,holiday_booking_list.booking_ref_number,holiday_booking_list.api_supplier,
       
        holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.travel_date,
        
       holiday_booking_list.confirmation_no, holiday_booking_list.total_price,
       
       holiday_booking_list.payment_status,holiday_booking_list.booking_status,
        holiday_booking_list.total_price,holiday_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'holiday','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('holiday_booking_list', "holiday_booking_list.refund_account_id = web_partner_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['holiday_booking_list.created >=' => $from_date, 'holiday_booking_list.created <=' => $to_date];
        }

        if (isset($data['value']) && $data['value']) {
            $Datearray = [];
        }
        $array =[];

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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }


    public function credit_notes_visa($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       visa_booking_list.id as BookingId,visa_booking_list.booking_ref_number,
       
        visa_booking_list.visa_type,visa_booking_list.visa_country,visa_booking_list.date_of_journey,
        
       visa_booking_list.confirmation_no,visa_booking_list.total_price,
       
       visa_booking_list.payment_status,visa_booking_list.booking_status,
        visa_booking_list.total_price,visa_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'visa','web_partner_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('visa_booking_list', "visa_booking_list.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data ;
    }


    public function credit_notes_visa_search($web_partner_id,$data)
    {

        $arrayValue = array("booking_ref_number" => "visa_booking_list.booking_ref_number", "first_name" => "visa_booking_travelers.first_name", "last_name" => "visa_booking_travelers.last_name",   "booking_status" => "visa_booking_list.booking_status", "payment_status" => "visa_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       visa_booking_list.id as BookingId,visa_booking_list.booking_ref_number,
       
        visa_booking_list.visa_type,visa_booking_list.visa_country,visa_booking_list.date_of_journey,
        
       visa_booking_list.confirmation_no,visa_booking_list.total_price,
       
       visa_booking_list.payment_status,visa_booking_list.booking_status,
        visa_booking_list.total_price,visa_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'visa','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('visa_booking_list', "visa_booking_list.refund_account_id = web_partner_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['visa_booking_list.created >=' => $from_date, 'visa_booking_list.created <=' => $to_date];
        }

        if ($data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['visa_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }


    public function credit_notes_car($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       car_booking_list.id as BookingId,car_booking_list.booking_ref_number,
       
        car_booking_list.car_name,car_booking_list.travel_types,car_booking_list.source,car_booking_list.destination,car_booking_list.departure_date,car_booking_list.pick_up_time,
        
       car_booking_list.confirmation_no, car_booking_list.total_price,
       
       car_booking_list.payment_status,car_booking_list.booking_status,
        car_booking_list.total_price,car_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'car','web_partner_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('car_booking_list', "car_booking_list.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data ;
    }


    public function credit_notes_car_search($web_partner_id,$data)
    {

        $arrayValue = array("booking_ref_number" => "car_booking_list.booking_ref_number", "first_name" => "car_booking_list.first_name", "last_name" => "car_booking_list.last_name",   "booking_status" => "car_booking_list.booking_status", "payment_status" => "car_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       car_booking_list.id as BookingId,car_booking_list.booking_ref_number,
       
        car_booking_list.car_name,car_booking_list.travel_types,car_booking_list.source,car_booking_list.destination,car_booking_list.departure_date,car_booking_list.pick_up_time,
        
       car_booking_list.confirmation_no, car_booking_list.total_price,
       
       car_booking_list.payment_status,car_booking_list.booking_status,
        car_booking_list.total_price,car_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'car','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('car_booking_list', "car_booking_list.refund_account_id = web_partner_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['car_booking_list.created >=' => $from_date, 'car_booking_list.created <=' => $to_date];
        }

        if ($data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['car_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }

    public function credit_notes_bus($web_partner_id)
    {

        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        bus_booking_list.id as BookingId,bus_booking_list.booking_ref_number,
       
        bus_booking_list.bus_name,
        bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,
        
       bus_booking_list.confirmation_no,bus_booking_list.total_price,
       
       bus_booking_list.payment_status,bus_booking_list.booking_status,
       bus_booking_list.total_price,
        bus_booking_travelers.*,bus_booking_travelers.id as TravelerId,
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'bus','web_partner_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')


            ->join('bus_booking_list', "bus_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
            ->join('bus_booking_travelers', "bus_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right')

            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data ;
    }


    public function credit_notes_bus_search($web_partner_id,$data)
    {
        $arrayValue = array("booking_ref_number" => "bus_booking_list.booking_ref_number", "first_name" => "bus_booking_travelers.first_name", "last_name" => "bus_booking_travelers.last_name",   "booking_status" => "bus_booking_list.booking_status", "payment_status" => "bus_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
        bus_booking_list.id as BookingId,bus_booking_list.booking_ref_number,
       
        bus_booking_list.bus_name,
        bus_booking_list.origin_city,bus_booking_list.destination_city,bus_booking_list.date_of_journey,
        
       bus_booking_list.confirmation_no,bus_booking_list.total_price,
       
       bus_booking_list.payment_status,bus_booking_list.booking_status,
       bus_booking_list.total_price,
        bus_booking_travelers.*,bus_booking_travelers.id as TravelerId,
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'bus','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')


            ->join('bus_booking_list', "bus_booking_list.id = web_partner_account_log.booking_ref_no", 'Left')
            ->join('bus_booking_travelers', "bus_booking_travelers.refund_account_id = web_partner_account_log.id", 'Right');



        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];
        }

        if ($data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['bus_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }

    public function credit_notes_cruise($web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp= date("Y-m-d", $tomorrow_timestamp);
        $from_date=strtotime(date('Y-m-d',strtotime($tomorrow_timestamp)).'00:00');

        $to_date=strtotime(date('Y-m-d',strtotime(date('Y-m-d'))).'23:59');

        $array=['web_partner_account_log.created >='=> $from_date,'web_partner_account_log.created <='=> $to_date];

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       cruise_booking_list.id as BookingId,cruise_booking_list.booking_ref_number,
       
       cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.sailing_date,
        
       cruise_booking_list.confirmation_no,cruise_booking_list.total_price,
       
       cruise_booking_list.payment_status,cruise_booking_list.booking_status,
        cruise_booking_list.total_price,cruise_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'cruise','web_partner_account_log.web_partner_id' => $web_partner_id])->where($array)
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('cruise_booking_list', "cruise_booking_list.refund_account_id = web_partner_account_log.id", 'Right')
            ->orderBy("web_partner_account_log.id", "DESC")->paginate(40);

        return $data ;
    }


    public function credit_notes_cruise_search($web_partner_id,$data)
    {

        $arrayValue = array("booking_ref_number" => "cruise_booking_list.booking_ref_number", "first_name" => "cruise_booking_travelers.first_name", "last_name" => "cruise_booking_travelers.last_name",   "booking_status" => "cruise_booking_list.booking_status", "payment_status" => "cruise_booking_list.payment_status");


        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $builder = $this->select("web_partner_account_log.id as AccountLogId,web_partner_account_log.web_partner_id,web_partner_account_log.acc_ref_number,web_partner_account_log.payment_mode,
        web_partner_account_log.action_type,web_partner_account_log.service,web_partner_account_log.service_log,web_partner_account_log.transaction_type,
       cruise_booking_list.id as BookingId,cruise_booking_list.booking_ref_number,
       
        cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.sailing_date,
        
       cruise_booking_list.confirmation_no,cruise_booking_list.total_price,
       
       cruise_booking_list.payment_status,cruise_booking_list.booking_status,
        cruise_booking_list.total_price,cruise_booking_list.amendment_charges,
        
  
        web_partner_account_log.credit,web_partner_account_log.debit,web_partner_account_log.balance,web_partner_account_log.remark,
        web_partner_account_log.created,web_partner_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
            ->where(['web_partner_account_log.action_type' => 'refund','web_partner_account_log.service' => 'cruise','web_partner_account_log.web_partner_id' => $web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = web_partner_account_log.web_partner_id', 'Left')
            ->join('cruise_booking_list', "cruise_booking_list.refund_account_id = web_partner_account_log.id", 'Right');


        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . ' 00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . ' 23:59');
            $Datearray = ['cruise_booking_list.created >=' => $from_date, 'cruise_booking_list.created <=' => $to_date];
        }

        if ($data['value']) {
            $Datearray = [];
        }
        $array =[];

        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['cruise_booking_list.web_partner_id'] = $data['tts_web_partner_info'];
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

        return $builder->orderBy("web_partner_account_log.id", "DESC")->paginate(40);
    }

}


