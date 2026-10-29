<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;

class AgentAccountLogModel extends Model
{
    protected $table = 'agent_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($agent_id,$web_partner_id)
    {
        return  $this->select('id,agent_id,credit,debit,balance,remark,created')->where('wl_agent_id', $agent_id)->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(10);
    }

    public function account_logs($agent_id,$web_partner_id)
    {
        return  $this->select('id,agent_id,credit,debit,balance,remark,created')->where('wl_agent_id', $agent_id)->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(20);
    }

    public function available_balance($agent_id,$web_partner_id){
        return  $this->select('balance')->where('wl_agent_id', $agent_id)->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }

    public function view_remark_detail($id,$web_partner_id,$agent_id){
        return $this->select('')->where('wl_agent_id', $agent_id)->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}


