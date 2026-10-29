<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AgentModel extends Model
{
    protected $table = 'agent_users';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function agent_list($web_partner_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('agent_account_log');
        $builder->whereIn('id', function(BaseBuilder $builder) {
          return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id','DESC')->groupBy('agent_id');
        });
        $subquery = $builder->getCompiledSelect();
        
        return  $this->select('agent_users.id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.email_id,agent_users.mobile_number,agent_users.status,agent_users.company_name,agent_users.credit_limit,agent_class.class_name,agent_account_log.balance')
            ->join('agent_class', 'agent_class.id = agent_users.agent_class')
            ->join("($subquery) agent_account_log", 'agent_account_log.agent_id = agent_users.id')
            ->orderBy('agent_users.id', 'DESC')
            ->where('agent_users.web_partner_id', $web_partner_id)
            ->paginate(40);
    }


    public function agent_list_details($id, $web_partner_id)
    {
        return  $this->select('*')->where("id", $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function agent_view_details($id, $web_partner_id)
    {
        return  $this->select('agent_users.*,agent_class.class_name')->join('agent_class', 'agent_class.id = agent_users.agent_class', 'left')->where('agent_users.id', $id)->where('agent_users.web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    function search_data($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['agent_users.created >=' => $from_date, 'agent_users.created <=' => $to_date];
                return  $this->select('agent_users.id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.email_id,agent_users.mobile_number,agent_users.status,agent_users.company_name,agent_users.credit_limit,agent_class,agent_class.class_name')->join('agent_class', 'agent_class.id = agent_users.agent_class', 'left')->where('agent_users.web_partner_id', $web_partner_id)->where($array)->paginate(40);
            } else {
                $array = ['agent_users.created >=' => $from_date, 'agent_users.created <=' => $to_date];
                return  $this->select('agent_users.id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.email_id,agent_users.mobile_number,agent_users.status,agent_users.company_name,agent_users.credit_limit,agent_class,agent_class.class_name')->join('agent_class', 'agent_class.id = agent_users.agent_class', 'left')->where('agent_users.web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('agent_users.id,agent_users.title,agent_users.first_name,agent_users.last_name,agent_users.email_id,agent_users.mobile_number,agent_users.status,agent_users.company_name,agent_users.credit_limit,agent_class,agent_class.class_name')->join('agent_class', 'agent_class.id = agent_users.agent_class', 'left')->where('agent_users.web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function delete_image($id, $web_partner_id)
    {
        return  $this->select('profile_pic,company_logo,gst_scan_copy,pan_scan_copy')->where("id", $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function remove_agent($id, $web_partner_id)
    {

        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("id", $id)->delete();
    }

    public function agent_status_change($web_partner_id, $ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }


    public function agent_export($web_partner_id, $data)
    {

        if ($data['from_date'] && $data['to_date']) {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            $array=['agent_users.created >='=> $from_date,'agent_users.created <='=> $to_date];


            $db      = \Config\Database::connect();
            $builder = $db->table('agent_account_log');
            $builder->whereIn('id', function(BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('agent_account_log')->orderBy('id','DESC')->groupBy('agent_id');
            });
            $subquery = $builder->getCompiledSelect();

            return  $this->select('agent_users.*,agent_class.class_name,agent_account_log.balance')
                ->join('agent_class', 'agent_class.id = agent_users.agent_class')
                ->join("($subquery) agent_account_log", 'agent_account_log.agent_id = agent_users.id')
                ->orderBy('agent_users.id', 'DESC')
                ->where('agent_users.web_partner_id', $web_partner_id)
                ->where($array)->get()->getResultArray();

        } else {
            return $this->select('*')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->get()->getResultArray();
        }
    }
}
