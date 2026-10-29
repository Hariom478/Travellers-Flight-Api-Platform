<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AgentCreditLogModel extends Model
{
    protected $table = 'agent_credit_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_credit_logs_detail($wl_agent_id, $web_partner_id)
    {
        return $this->select('*')->where(['wl_agent_id' => $wl_agent_id, 'web_partner_id' => $web_partner_id])->orderBy("id", "DESC")->paginate(40);
    }





    public function account_credit_logs($wl_agent_id, $web_partner_id)
    {

        return $this->select('*')->where(['wl_agent_id' => $wl_agent_id, 'web_partner_id' => $web_partner_id])->orderBy("id", "DESC")->paginate(40);

    }



    public function available_credit_details($wl_agent_id, $web_partner_id)
    {
        return $this->select('id,credit_limit,credit_expire,credit_expire_date,remark')->where(['wl_agent_id' => $wl_agent_id, 'web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }





    function search_result_data($data, $wl_agent_id, $web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date, "web_partner_id" => $web_partner_id, 'wl_agent_id' => $wl_agent_id];

                return $this->select('*')->where($array)->orderBy("agent_credit_log.id", "DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date, "web_partner_id" => $web_partner_id, 'wl_agent_id' => $wl_agent_id];

                return $this->select('*')
                    ->where($array)->like(trim($data['key']), trim($data['value']))->orderBy("id", "DESC")->paginate(40);
            }
        } else {
            return $this->select('*')
                ->where(trim($data['key']), trim($data['value']))->where("web_partner_id", $web_partner_id)->where("wl_agent_id", $wl_agent_id)->orderBy("id", "DESC")->paginate(40);
        }
    }







}