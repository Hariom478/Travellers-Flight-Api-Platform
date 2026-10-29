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
        return    $this->db->table('bank_account_detail')->where("web_partner_id",0)->select('id,bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function super_admin_bank_account_details($id)
    {
        return  $this->db->table('bank_account_detail')->where("web_partner_id",0)->select('bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->where("id",$id)->get()->getRowArray();
    }
    public function insertData($tableName,$insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }
    public function getconveniencefees()
    {
        return  $this->db->table('super_admin_convenience_fee')->select('bank_name,branch_name,account_holder_name,account_no,ifsc_code,swift_code')->where("service","MakePayment")->get()->getResultArray();
    }


    public function payment_history($web_partner_id)
    {
        return  $this->db->table('web_partner_make_payment')->select('payment_date,amount,status,payment_mode,id')->where("web_partner_id",$web_partner_id)->orderBy("id","DESC")->paginate(10);
    }

}


