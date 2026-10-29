<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;

class SupplierClassModel extends Model
{
    protected $table = 'supplier_class';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function supplier_class_list($web_partner_id)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->paginate(40);
    }

    public function supplier_class_list_select($web_partner_id)
    {
        return $this->select('class_name,id')->where('web_partner_id', $web_partner_id)->get()->getResultArray();
    }
    public function update_supplier_class($data, $web_partner_id, $id)
    {
        return $this->select('*')->where(array('web_partner_id' => $web_partner_id, 'id' => $id))->set($data)->update();
    }
}