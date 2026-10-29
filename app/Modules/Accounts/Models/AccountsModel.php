<?php

namespace App\Modules\Accounts\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AccountsModel extends Model
{
    protected $table = '';
    protected $primarykey = '';
    protected $protectFields = false;

    public function super_admin_bank_account_info()
    {
        return    $this->db->table('web_partner_bank_account_detail')->select('id,bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->whereNotIn('web_partner_id', [0])->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function bank_list($web_partner_id)
{
    return  $this->db->table('web_partner_bank_account_detail')->select('id,bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->get()->getResultArray();
}

    public function super_admin_bank_account_details($id)
    {
        return  $this->db->table('web_partner_bank_account_detail')->select('bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->whereNotIn('web_partner_id', [0])->where("id",$id)->get()->getRowArray();
    }
    public function insertData($tableName,$insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }
    
    public function payment_history($web_partner_id,$agent_id)
    {
        return  $this->db->table('wl_agent_make_payment')->select('payment_date,amount,status,payment_mode,id')->where('wl_agent_id',$agent_id)->where("web_partner_id",$web_partner_id)->orderBy("id","DESC")->paginate(10);
    }

}


