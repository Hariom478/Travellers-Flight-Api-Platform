<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AgentUsersModel extends Model
{
    protected $table = 'agent_users';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function agent_users_status_change($web_partner_id, $ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('agent_id', $ids)->set($data)->update();
    }

    public function remove_agent_users($id, $web_partner_id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("agent_id", $id)->delete();
    }

    public function agent_list_details($id, $web_partner_id)
    {
        return  $this->select('*')->where("agent_id", $id)->where('web_partner_id', $web_partner_id)->where('primary_user', 1)->get()->getRowArray();
    }



    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("agent_users")->select('*')->where(['login_email'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

}
