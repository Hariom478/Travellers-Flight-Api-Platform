<?php

namespace App\Modules\Accounts\Models;

use CodeIgniter\Model;

class WebPartnerAccountLogModel extends Model
{
    protected $table = 'web_partner_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($web_partner_id)
    {
        return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);
    }

    public function account_logs($web_partner_id,$searchdata  =  array())
    {
        if(isset($searchdata['from_date']) && isset($searchdata['to_date'])){
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];
        return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created')->where($array)->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);
        }
        else{
            return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);
        }
    }

    public function available_balance($web_partner_id){
        return  $this->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}


