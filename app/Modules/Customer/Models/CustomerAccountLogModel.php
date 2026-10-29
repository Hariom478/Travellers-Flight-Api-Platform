<?php

namespace App\Modules\Customer\Models;

use CodeIgniter\Model;

class CustomerAccountLogModel extends Model
{
    protected $table = 'customer_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($customer_id,$web_partner_id)
    {
        return  $this->select('id,customer_id,credit,debit,balance,remark,created')->where('customer_id', $customer_id)->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(10);
    }

    public function account_logs($customer_id,$web_partner_id)
    {
        return  $this->select('id,customer_id,credit,debit,balance,remark,created')->where('customer_id', $customer_id)->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);
    }

    public function available_balance($customer_id,$web_partner_id){
        return  $this->select('balance')->where('customer_id', $customer_id)->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();

    }

}


