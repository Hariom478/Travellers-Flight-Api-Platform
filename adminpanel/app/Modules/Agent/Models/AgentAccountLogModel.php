<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;

class AgentAccountLogModel extends Model
{
    protected $table = 'agent_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($wl_agent_id, $web_partner_id)
    {
        return $this->select('id,wl_agent_id,credit,debit,balance,remark,created')->where('wl_agent_id', $wl_agent_id)->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->paginate(10);
    }

    public function account_logs($wl_agent_id, $web_partner_id, $searchdata = array())
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_holder_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        if ((isset($searchdata['from_date']) && $searchdata['from_date'] != "") && (isset($searchdata['to_date']) && $searchdata['to_date'] != "")) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        }
        if (isset($searchdata['search-text']) && $searchdata['key-value'] != "") {
            $wl_agent_id = $searchdata['key-value'];
        }

        if (isset($searchdata['key']) && $searchdata['key'] != '') {
            if ($searchdata['key'] == 'invoice_number') {
                $array['agent_account_log.invoice_number'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'service') {
                $array['agent_account_log.service'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'credit') {
                $array['agent_account_log.transaction_type'] = 'credit';
                $array['agent_account_log.credit'] = $searchdata['value'];
            }
            if ($searchdata['key'] == 'balance') {
                $array['agent_account_log.balance'] = 'balance';
            }

            if ($searchdata['key'] == 'debit') {
                $array['agent_account_log.transaction_type'] = 'debit';
                $array['agent_account_log.debit'] = $searchdata['value'];
            }
        }
        $array['agent_account_log.web_partner_id'] = $web_partner_id;
        $array['agent_account_log.wl_agent_id'] = $wl_agent_id;
        return $this->select('agent_account_log.*,agent.company_name,agent.pan_holder_name as pan_name,agent.pan_number,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = agent_account_log.user_id', 'Left')
            ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')->where($array)
            ->orderBy("agent_account_log.id", "DESC")->paginate(40);
    }

    public function available_balance($wl_agent_id, $web_partner_id)
    {
        return $this->select('balance')->where('wl_agent_id', $wl_agent_id)->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();

    }


    public function servicesId($servicename, $booking_ref_number, $web_partner_id)
    {
        $tableName = $servicename . "_booking_list";
        return $this->db->table($tableName)->select('id')->where("booking_ref_number", $booking_ref_number)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }
    public function view_remark_detail($id, $web_partner_id)
    {
        $array['agent_account_log.web_partner_id'] = $web_partner_id;
        $array['agent_account_log.id'] = $id;
        return $this->select('agent_account_log.wl_agent_id,agent_account_log.id,agent_account_log.transaction_type,agent_account_log.web_partner_id,agent_account_log.remark,agent_account_log.extra_param,agent_account_log.payment_mode,agent_account_log.transaction_id,agent_account_log.service_log,agent_account_log.action_type,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,agent_account_log.service,agent_account_log.invoice_number,
            ')
            ->join('admin_users', 'admin_users.id = agent_account_log.user_id', 'Left')
            ->where($array)->get()->getRowArray();
    }



   /*  public function available_balance($web_partner_id)
    {
        return $this->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    } */


}