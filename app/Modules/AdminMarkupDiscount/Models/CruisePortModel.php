<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class CruisePortModel extends Model
{
    protected $table = 'cruise_ports';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_port_select_all($web_partner_id)
    {
        return  $this->select('id,port_name')->where(['web_partner_id'=>$web_partner_id,'status'=>'active'])->get()->getResultArray();
    }
    
}


