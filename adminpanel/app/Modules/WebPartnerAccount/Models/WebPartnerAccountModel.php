<?php

namespace App\Modules\WebPartnerAccount\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class WebPartnerAccountModel extends Model
{
    protected $table = '';
    protected $primarykey = '';
    protected $protectFields = false;

    function search_webpartner($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('web_partner_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('web_partner_account_log')->orderBy('id', 'DESC')->groupBy('web_partner_id');
        });
        $subquery = $builder->getCompiledSelect();
        $admin_users_subquery = $db->table('admin_users')->select('id,web_partner_id,login_email,mobile_isd,mobile_no')->where('primary_user',1);
        $admin_users_subquery = $admin_users_subquery->getCompiledSelect();
            return $this->db->table('web_partner')->select('web_partner.id,web_partner.company_id,web_partner.company_name,web_partner.pan_name,web_partner.pan_number,web_partner_account_log.balance,
                web_partner.api_user,web_partner.pan_card,web_partner.whitelabel_user,web_partner.api_user,web_partner.status,web_partner.created,
                web_partner.modified,admin_users.login_email,admin_users.mobile_isd,admin_users.mobile_no')
                ->join("($admin_users_subquery) admin_users", 'admin_users.web_partner_id = web_partner.id', 'left')
                ->join("($subquery) web_partner_account_log", 'web_partner_account_log.web_partner_id = web_partner.id','left')
                ->where('web_partner.id', trim($data['key-value']))->orderBy('web_partner.id', 'DESC')
                ->get()->getRowArray();
        
    }
    function webpartnerinfo($data)
    {
        $db = \Config\Database::connect();
        $admin_users_subquery = $db->table('admin_users')->select('id,web_partner_id,login_email,mobile_isd,mobile_no')->where('primary_user',1);
        $admin_users_subquery = $admin_users_subquery->getCompiledSelect();
            return $this->db->table('web_partner')->select('web_partner.id,web_partner.company_id,web_partner.company_name,web_partner.pan_name,web_partner.pan_number')
                ->join("($admin_users_subquery) admin_users", 'admin_users.web_partner_id = web_partner.id', 'left')
                ->like(trim('web_partner.company_id'), trim($data))->orderBy('web_partner.id', 'DESC')
                ->orLike(trim('web_partner.company_name'), trim($data))->orderBy('web_partner.id', 'DESC')
                ->orLike(trim('web_partner.pan_number'), trim($data))->orderBy('web_partner.id', 'DESC')
                ->get()->getResultArray();
        
    }
    public function servicesId($servicename,$booking_ref_number)
    {
        $tableName =  $servicename."_booking_list";
        return $this->db->table($tableName)->select('id')->where("booking_ref_number", $booking_ref_number)->get()->getRowArray();
    }
    

}


