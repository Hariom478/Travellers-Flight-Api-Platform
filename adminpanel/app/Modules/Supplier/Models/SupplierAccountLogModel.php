<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class SupplierAccountLogModel extends Model
{
    protected $table = 'supplier_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function account_logs($supplier_id, $web_partner_id, $searchdata = array())
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('suppliers')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['supplier_account_log.created >=' => $from_date, 'supplier_account_log.created <=' => $to_date];
        if ((isset($searchdata['from_date']) && $searchdata['from_date'] != "") && (isset($searchdata['to_date']) && $searchdata['to_date'] != "")) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['supplier_account_log.created >=' => $from_date, 'supplier_account_log.created <=' => $to_date];
        }
        if (isset($searchdata['search-text']) && $searchdata['key-value'] != "") {
            $supplier_id = $searchdata['key-value'];
        }

        if (isset($searchdata['key']) && $searchdata['key'] != '') {


            if ($searchdata['key'] == 'service') {
                $array['supplier_account_log.service'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'credit') {
                $array['supplier_account_log.transaction_type'] = 'credit';
                $array['supplier_account_log.credit'] = $searchdata['value'];
            }
            if ($searchdata['key'] == 'balance') {
                $array['supplier_account_log.balance'] = 'balance';
            }

            if ($searchdata['key'] == 'debit') {
                $array['supplier_account_log.transaction_type'] = 'debit';
                $array['supplier_account_log.debit'] = $searchdata['value'];
            }
        }
        $array['supplier_account_log.web_partner_id'] = $web_partner_id;
        $array['supplier_account_log.supplier_id'] = $supplier_id;
        return $this->select('supplier_account_log.*,suppliers.company_name,suppliers.pan_name,suppliers.pan_number,CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name')
            ->join('admin_users', 'admin_users.id = supplier_account_log.user_id', 'Left')
            ->join("($subquery) suppliers", 'suppliers.id = supplier_account_log.supplier_id', 'Left')->where($array)
            ->orderBy("supplier_account_log.id", "DESC")->paginate(40);
    }
    public function available_balance($supplier_id, $web_partner_id)
    {
        return $this->select('balance')->where(['supplier_id' => $supplier_id, "web_partner_id" => $web_partner_id])->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    public function view_remark_detail($id, $web_partner_id)
    {
        $array['supplier_account_log.web_partner_id'] = $web_partner_id;
        $array['supplier_account_log.id'] = $id;
        return $this->select('supplier_account_log.supplier_id,supplier_account_log.id,supplier_account_log.transaction_type,supplier_account_log.web_partner_id,supplier_account_log.remark,supplier_account_log.extra_param,supplier_account_log.payment_mode,supplier_account_log.transaction_id,supplier_account_log.service_log,supplier_account_log.action_type,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = supplier_account_log.user_id', 'Left')
            ->where($array)->get()->getRowArray();
    }
    public function servicesId($servicename, $booking_ref_number, $web_partner_id)
    {
        $tableName = $servicename . "_booking_list";
        return $this->db->table($tableName)->select('id')->where("booking_ref_number", $booking_ref_number)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }
}