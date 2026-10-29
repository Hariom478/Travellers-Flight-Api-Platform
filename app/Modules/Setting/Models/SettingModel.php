<?php

namespace App\Modules\Setting\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'agent_users';
    protected $primaryKey = 'id';
    protected $protectFields = false;

    public function get_staff_list($web_partner_id,$agent_id, $id)
    {
        return $this->db->table('agent_users')->where('primary_user', 0)->where('agent_id', $agent_id)
        ->where('web_partner_id', $web_partner_id)
        ->where('id !=', $id)
        ->get()->getResultArray();
    }

    public function add_user($data)
    {
        return $this->db->table('agent_users')->insert($data);
    }

    public function update_user($web_partner_id,$agent_id,$user_id,$update_data)
    {
        return $this->db->table('agent_users')->where(['web_partner_id'=> $web_partner_id,'agent_id'=>$agent_id])->where('id', $user_id)->set($update_data)->update();
    }

    public function update_agent($web_partner_id, $user_id, $update_data){
        return $this->db->table('agent')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($update_data)->update();
    }

    public function edit_user($web_partner_id,$agent_id,$user_id)
    {
        return $this->db->table('agent_users')->select('agent.*,agent_users.*,')
                     ->join('agent','agent_users.agent_id = agent.id')->where(['agent_users.web_partner_id'=> $web_partner_id,'agent_users.agent_id'=>$agent_id])->where('agent_users.id', $user_id)->get()->getRowArray();
    }

    public function user_access_list($web_partner_id,$agent_id,$user_id)
    {
        return $this->db->table('agent_users')->where('web_partner_id', $web_partner_id)->where('agent_id',$agent_id)->where('id', $user_id)->get()->getRow();
    }

    public function update_permition($web_partner_id,$agent_id,$user_id,$data_access)
    {
        return $this->db->table('agent_users')->where('web_partner_id',$web_partner_id)->where('agent_id',$agent_id)->where('id', $user_id)->set($data_access)->update();
    }


    public function change_password($web_partner_id,$user_id,$update_data)
    {
        return $this->db->table('agent_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($update_data)->update();
    }

    
}
