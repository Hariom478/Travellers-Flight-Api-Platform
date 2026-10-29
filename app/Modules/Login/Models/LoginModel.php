<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'agent_users';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function check_user($email, $password, $web_partner_id)
    {
        //
        return $this->select('agent.*,agent_users.*')->where(['agent_users.login_email' => $email, 'agent_users.password' => md5($password), 'agent_users.web_partner_id' => $web_partner_id])
            ->join('agent', 'agent_users.agent_id = agent.id')->get()->getRowArray();
    }

    public function check_admin_user($email, $user_id, $web_partner_id)
    {
        //
        return $this->select('agent.*,agent_users.*')->where(['agent_users.login_email' => $email, 'agent_users.primary_user' => 1, 'agent.id' => $user_id, 'agent_users.web_partner_id' => $web_partner_id])
            ->join('agent', 'agent_users.agent_id = agent.id')->get()->getRowArray();
    }

    public function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return $this->db->insertID();
    }

    public function updateData($tableName, $where, $data)
    {
        return $this->db->table($tableName)->where($where)->set($data)->update();
    }

    public function getCompanyName($email)
    {

        return $this->select('agent_users.login_email,agent_users.mobile_no,agent.company_name')->where(['agent_users.login_email' => $email])
            ->join('agent', 'agent_users.agent_id = agent.id')->get()->getRowArray();
    }

    public function gst_state_list()
    {
        return $this->db->table('gst_city')->select('gst_state_name,gst_state_code')->get()->getResultArray();
    }

    public function country_list()
    {
        return $this->db->table('countries')->select('id,name')->get()->getResultArray();
    }

    public function state_list($country_id)
    {
        return $this->db->table('states')->select('id,name')->where('country_id', $country_id)->get()->getResultArray();
    }

    public function city_list($state_id)
    {
        return $this->db->table('cities')->select('id,name')->where('state_id', $state_id)->get()->getResultArray();
    }


    public function country_list_row($country_id)
    {
        return $this->db->table('countries')->select('id,name')->where('id', $country_id)->get()->getRowArray();
    }

    public function state_list_row($state_id)
    {
        return $this->db->table('states')->select('id,name')->where('id', $state_id)->get()->getRowArray();
    }

    public function city_list_row($city_id)
    {
        return $this->db->table('cities')->select('id,name')->where('id', $city_id)->get()->getRowArray();
    }
}