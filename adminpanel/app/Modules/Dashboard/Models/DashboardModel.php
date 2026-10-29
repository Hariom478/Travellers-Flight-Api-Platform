<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class DashboardModel extends Model
{

    function getServiceCountInfo($tablename, $where, $select, $whereIn = array())
    {
        $builder  =  $this->db->table($tablename);
        if (!empty($select)) {
            $builder->select($select);
        }
        if (!empty($where)) {
            $builder->where($where);
        }
        if (!empty($whereIn)) {
            foreach ($whereIn as $key => $value) {
                $builder->whereIn($key, $value);
            }
        }
        return  $builder->get()->getResultArray();
    }

 


    public function payment_history_pending($web_partner_id, $page = 10, $status = null)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id, company_name, pan_holder_name, pan_number')->where('agent.web_partner_id', $web_partner_id);
        $subquery = $subquery->getCompiledSelect();

        $tomorrow_timestamp = strtotime("- 1 day");
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = [
            'wl_agent_make_payment.created >=' => $from_date,
            'wl_agent_make_payment.created <=' => $to_date
        ];

        $query = $this->db->table('wl_agent_make_payment')
            ->select('wl_agent_make_payment.payment_date, wl_agent_make_payment.amount, wl_agent_make_payment.status, wl_agent_make_payment.payment_mode, wl_agent_make_payment.file_name,
                wl_agent_make_payment.created, wl_agent_make_payment.id, wl_agent_make_payment.admin_remark, wl_agent_make_payment.bank_transaction_id,
                agent.id as wl_agent_id, wl_agent_make_payment.sup_staff_id, agent.company_name, agent.pan_holder_name, agent.pan_number')
            ->where('wl_agent_make_payment.status', $status)
            ->where('wl_agent_make_payment.web_partner_id', $web_partner_id)

            ->where($array)  
            ->join("($subquery) agent", 'agent.id = wl_agent_make_payment.wl_agent_id', 'left')
            ->orderBy("wl_agent_make_payment.id", "DESC")
            ->limit($page)
            ->get();

        return $query->getResultArray();
    }

    public function agent_registered_list($web_partner_id) 
    {
        $db = \Config\Database::connect();
        return  $this->db->table('agent')
        ->select('id, company_name, status, state,city,country,created')
        ->where('agent.status', "inactive")
        ->where('agent.web_partner_id', $web_partner_id)
        ->orderBy("agent.id", "DESC")
        ->limit(5)
        ->get()
        ->getResultArray();
    }


    // public function agent_running_balance()
    // {  
    //     $db = \Config\Database::connect();
    //     $builder = $db->table('agent');
    //     $builder->whereIn('id', function (BaseBuilder $builder) {
    //         return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id', 'DESC')->groupBy('web_partner_id');
    //     });
    //     $subquery = $builder->getCompiledSelect();
        
    //     return $this->db->table('agent_account_log')->select('agent.id,agent.company_id,agent.company_name,agent.pan_holder_name,agent.pan_number,agent_account_log.balance,')
         
    //         ->join("($subquery) agent_account_log", 'agent_account_log.web_partner_id = web_partner.id', 'Left')
    //         ->orderBy('agent_account_log.created', 'DESC')
    //         ->paginate();
    // }


    public function agent_running_balance()
    {
        $builder = $this->db->table('agent a');
        $builder->select('a.id, a.title, a.first_name, a.company_id,MAX(al.id) as MaxId, al.balance AS highest_balance');
        $builder->join('agent_account_log al', 'a.id = al.wl_agent_id');
        $builder->groupBy('a.id, a.title, a.first_name, a.company_id');
        $builder->orderBy('highest_balance', 'DESC');
        $builder->limit(10);

        $query = $builder->get(); 
        return $query->getResultArray();
    }

}
