<?php

namespace App\Modules\Accounts\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class MakePaymentModel extends Model
{
    protected $table = 'web_partner_make_payment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function payment_history($web_partner_id)
    {
        return $this->select('payment_date,amount,status,payment_mode,id,admin_remark,bank_transaction_id,file_name')->where("web_partner_id", $web_partner_id)->orderBy("id", "DESC")->paginate(50);
    }

    public function payment_history_detail($id, $web_partner_id)
    {
        return $this->select('id as ref_no,amount,cheque_draft_utr_number,payment_date,bank,bank_branch,payment_mode,bank_transaction_id,agent_staff_id,status,company_bank_name,file_name,
        company_branch_name,company_account_holder_name,company_account_no,company_ifsc_code,remark,company_swift_code,admin_remark,created')
            ->where('id', $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    function search_data($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('payment_date,amount,status,payment_mode,id,admin_remark,bank_transaction_id,file_name')->where('web_partner_id', $web_partner_id)->where($array)->paginate(50);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('payment_date,amount,status,payment_mode,id,admin_remark,bank_transaction_id,file_name')->where('web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']), trim($data['value']))->paginate(50);
            }
        } else {
            return $this->select('payment_date,amount,status,payment_mode,id,admin_remark,bank_transaction_id,file_name')->where('web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))->paginate(50);
        }
    }



    function convenience_fee($service,$web_partner_class_id,$payment_getway)
    {

        $builder = $this->db->table('super_admin_convenience_fee')->select('rupay_credit_card_value,rupay_credit_card_type,visa_credit_card_value,visa_credit_card_type,
        mastercard_credit_card_value,mastercard_credit_card_type,american_express_credit_card_value,american_express_credit_card_type,
        debit_card_value,debit_card_type,net_banking_value,net_banking_type,cash_card_value,cash_card_type,mobile_wallet_value,mobile_wallet_type,min_amount,max_amount');
        $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
        $builder->where('find_in_set("' . $service . '", service) <> 0');
        $builder->where('find_in_set("' . $payment_getway . '", payment_getway) <> 0');
        $convenience_fee =    $builder->get()->getResultArray();
        if ($convenience_fee) {

            return $convenience_fee;

        } else {

            return array(array(

                'rupay_credit_card_value' => 0,
                'rupay_credit_card_type' => 'fixed',
                'visa_credit_card_value' => 0,
                'visa_credit_card_type' => 'fixed',
                'mastercard_credit_card_value' => 0,
                'mastercard_credit_card_type' => 'fixed',
                'american_express_credit_card_value' => 0,
                'american_express_credit_card_type' => 'fixed',
                'debit_card_value' => 0,
                'debit_card_type' => 'fixed',
                'net_banking_value' => 0,
                'net_banking_type' => 'fixed',
                'cash_card_value' => 0,
                'min_amount' => 0,
                'max_amount' => 0,
                'cash_card_type' => 'fixed',
                'mobile_wallet_value' => 0,
                'mobile_wallet_type' => 'fixed'

            ));

        }

    }
}


