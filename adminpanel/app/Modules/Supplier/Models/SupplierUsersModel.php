<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SupplierUsersModel extends Model
{
    protected $table = 'supplier_users';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function supplier_users_details($id, $web_partner_id)
    {
        return $this->select('id,first_name,last_name,title,login_email,mobile_isd,supplier_id,mobile_no,whatsapp_no,street,city,state,country,pin_code')
            ->where('primary_user', 1)
            ->where('supplier_id', $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function remove_supplier_users($supplier_id, $web_partner_id)
    {
        return $this->select('*')->whereIn('supplier_id', $supplier_id)->where(["web_partner_id" => $web_partner_id])->delete();
    }
    public function supplier_users_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('supplier_id', $ids)->where(["web_partner_id" => $web_partner_id])->set($data)->update();
    }
}