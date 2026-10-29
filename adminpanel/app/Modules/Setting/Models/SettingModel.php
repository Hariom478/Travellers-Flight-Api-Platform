<?php

namespace App\Modules\Setting\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'web_partner';
    protected $primaryKey = 'id';
    protected $protectFields = false;


    public function get_staff_list($web_partner_id, $id)
    {
        return $this->db->table('admin_users')->where('primary_user', 0)->whereNotIn('id', [$id])->where('web_partner_id', $web_partner_id)
            ->orderBy("id", "DESC")->get()->getResultArray();
    }

    public function add_user($data)
    {
        return $this->db->table('admin_users')->insert($data);
    }


    public function user_status_change($web_partner_id, $user_id, $update_data)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($update_data)->update();
    }

    public function user_access_list($web_partner_id, $user_id)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->get()->getRow();
    }


    public function delete_user_data($web_partner_id, $user_id)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->whereIn('id', $user_id)->delete();
    }

    public function update_permition($web_partner_id, $user_id, $data_access)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($data_access)->update();
    }

    public function edit_user($web_partner_id, $user_id)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->get()->getRowArray();
    }

    public function update_user($web_partner_id, $user_id, $update_data)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($update_data)->update();
    }

    public function change_password($web_partner_id, $user_id, $data_access)
    {
        return $this->db->table('admin_users')->where('web_partner_id', $web_partner_id)->where('id', $user_id)->set($data_access)->update();
    }

    public function whitelabel_webpartner_email_setting($web_partner_id)
    {
        return $this->db->table('whitelabel_webpartner_setting')->select('email_setting,payment_gateway_name,payment_gateway_setting')->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    function checkRegisterUser($email, $web_partner_id)
    {
        return $this->db->table("admin_users")->select("admin_users.id,admin_users.login_email")
            ->where(["admin_users.login_email" => $email, "admin_users.web_partner_id" => $web_partner_id])
            ->get()
            ->getNumRows();
    }
}
