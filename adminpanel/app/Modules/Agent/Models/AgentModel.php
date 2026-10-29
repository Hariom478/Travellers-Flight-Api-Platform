<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AgentModel extends Model
{
    protected $table = 'agent';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function checkRegisterAgent($email, $web_partner_id)
    {
        return $this->db->table("agent")->select("agent.id as agent_id,agent.web_partner_id as web_partnerid")->join('agent_users', 'agent_users.agent_id = agent.id')->where(["login_email" => $email, "agent.web_partner_id" => $web_partner_id, "primary_user" => 1])->get()->getNumRows();
    }


    public function agent_list($web_partner_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('agent_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
        });
        $subquery = $builder->getCompiledSelect();
        //

        $builder2 = $db->table('agent_credit_log');
        $builder2->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('agent_credit_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
        });
        $creditlogsubquery = $builder2->getCompiledSelect();


        return $this->select('agent.id as agent_id,agent.distributor_id,agent.web_partner_id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.login_email,agent_users.mobile_no,agent_credit_log.credit_limit,agent.status,agent.company_name,agent_class.class_name,agent_account_log.balance,agent.company_id')
            ->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')
            ->join('agent_class', 'agent_class.id = agent.agent_class', 'Left')
            ->join("($subquery) agent_account_log", 'agent_account_log.wl_agent_id = agent.id', 'Left')
            ->join("($creditlogsubquery) agent_credit_log", 'agent_credit_log.wl_agent_id = agent.id', 'left')
            ->orderBy('agent.id', 'DESC')
            ->where('agent.web_partner_id', $web_partner_id)->where('agent_users.primary_user', 1)
            ->paginate(40);
    }


    public function agent_list_details($id, $web_partner_id)
    {
        return $this->select('agent.*,agent_users.id As agent_user_id,agent_users.*')->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')->where("agent.id", $id)->where(["agent_users.primary_user" => 1])->where('agent.web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function agent_view_details($id, $web_partner_id)
    {
        $db = \Config\Database::connect();
        $builder2 = $db->table('agent_credit_log');
        $builder2->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('agent_credit_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
        });
        $creditlogsubquery = $builder2->getCompiledSelect();

        return $this->select('agent.*,agent_users.*,agent_class.class_name,agent_credit_log.credit_limit')
            ->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')
            ->join("($creditlogsubquery) agent_credit_log", 'agent_credit_log.wl_agent_id = agent.id', 'left')
            ->join('agent_class', 'agent_class.id = agent.agent_class', 'left')
            ->where('agent.id', $id)->where('agent_users.primary_user', 1)
            ->where('agent.web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function agentinfo($data, $web_partner_id)
    {
        return $this->select('agent.id,agent_users.first_name,agent_users.last_name,agent_users.login_email,agent_users.mobile_no,agent.pan_holder_name')
            ->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')
            ->where('agent.web_partner_id', $web_partner_id)
            ->like(trim('agent_users.first_name'), trim($data))->orderBy('agent_users.id', 'DESC')
            ->orLike(trim('agent_users.last_name'), trim($data))->orderBy('agent_users.id', 'DESC')
            ->get()->getResultArray();
    }

    function search_data($web_partner_id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('agent_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
        });
        $builder2 = $db->table('agent_credit_log');
        $builder2->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('agent_credit_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
        });
        $creditlogsubquery = $builder2->getCompiledSelect();
        $dataWhere = ['first_name' => 'agent_users.first_name', 'distributor_id' => 'agent.distributor_id', 'login_email' => 'agent_users.login_email', 'mobile_number' => 'agent_users.mobile_no', 'class_name' => 'agent_class.class_name', 'company_name' => 'agent.company_name'];
        $subquery = $builder->getCompiledSelect();
        $agentBuilder = $this->select('agent.id as agent_id,agent.distributor_id,agent.web_partner_id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.login_email,agent_users.mobile_no,agent_credit_log.credit_limit,agent.status,agent.company_name,agent_class.class_name,agent_account_log.balance,agent.company_id')->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')->join('agent_class', 'agent_class.id = agent.agent_class', 'left')->join("($subquery) agent_account_log", 'agent_account_log.wl_agent_id = agent.id', 'Left')->join("($creditlogsubquery) agent_credit_log", 'agent_credit_log.wl_agent_id = agent.id', 'left')->where('agent.web_partner_id', $web_partner_id)->where('agent_users.primary_user', 1);
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['agent.created >=' => $from_date, 'agent.created <=' => $to_date];
                return $agentBuilder->where($array)->paginate(40);
            } else {
                $array = ['agent.created >=' => $from_date, 'agent.created <=' => $to_date];
                return $agentBuilder->where($array)->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
            }
        } else {
            return $agentBuilder->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
        }
    }

    public function delete_image($id, $web_partner_id)
    {
        return $this->select('agent_users.profile_pic,agent.company_logo,agent.gst_scan_copy,agent.pan_scan_copy')
            ->join('agent_users', 'agent_users.agent_id = agent.id', 'Left')
            ->where('agent.id', $id)->where('agent_users.agent_id', $id)->where('agent.web_partner_id', $web_partner_id)->get()->getRowArray();
    }



    

    public function remove_agent($id, $web_partner_id)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->where("id", $id)->delete();    
    }

    public function agent_status_change($web_partner_id, $ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }


    public function agent_export($web_partner_id, $data)
    {

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['agent.created >=' => $from_date, 'agent.created <=' => $to_date];
            $db = \Config\Database::connect();
            $builder = $db->table('agent_account_log');
            $builder->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
            });
            $subquery = $builder->getCompiledSelect();

            $builder2 = $db->table('agent_credit_log');
            $builder2->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('agent_credit_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
            });
            $creditlogsubquery = $builder2->getCompiledSelect();
            return $this->select('agent.*,agent_users.*,agent_class.class_name,agent_account_log.balance,agent_credit_log.credit_limit')
                ->join('agent_class', 'agent_class.id = agent.agent_class')
                ->join('agent_users', 'agent_users.agent_id = agent.id')
                ->join("($subquery) agent_account_log", 'agent_account_log.wl_agent_id = agent.id')
                ->join("($creditlogsubquery) agent_credit_log", 'agent_credit_log.wl_agent_id = agent.id', 'left')
                ->orderBy('agent.id', 'DESC')
                ->where('agent.web_partner_id', $web_partner_id)->where($array)
                ->get()->getResultArray();
        } else {
            $db = \Config\Database::connect();
            $builder = $db->table('agent_account_log');
            $builder->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
            });
            $subquery = $builder->getCompiledSelect();

            $builder2 = $db->table('agent_credit_log');
            $builder2->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('agent_credit_log')->orderBy('id', 'DESC')->groupBy('wl_agent_id');
            });
            $creditlogsubquery = $builder2->getCompiledSelect();
            return $this->select('agent.*,agent_users.*,agent_class.class_name,agent_account_log.balance,agent_credit_log.credit_limit')
                ->join('agent_class', 'agent_class.id = agent.agent_class')
                ->join('agent_users', 'agent_users.agent_id = agent.id')
                ->join("($subquery) agent_account_log", 'agent_account_log.wl_agent_id = agent.id')
                ->join("($creditlogsubquery) agent_credit_log", 'agent_credit_log.wl_agent_id = agent.id', 'left')
                ->orderBy('agent.id', 'DESC')
                ->where('agent.web_partner_id', $web_partner_id)
                ->get()->getResultArray();
        }
    }
}
