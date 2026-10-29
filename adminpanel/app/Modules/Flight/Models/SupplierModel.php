<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function get_supplier_detail($supplier_id,$web_partner_id)
    {
        return $this->select('suppliers.company_name,suppliers.company_id,supplier_users.login_email,supplier_users.first_name,supplier_users.last_name')->join('supplier_users','supplier_users.distributor_id = suppliers.id','left')->where('supplier_users.primary_user',1)->where('suppliers.web_partner_id', $web_partner_id)->where('suppliers.id',$supplier_id)->orderBy("id", "DESC")->get()->getRowArray();
    }
}


