<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class CruiseLineModel extends Model
{
    protected $table = 'cruise_line';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_line_select($web_partner_id)
    {
        return  $this->select('id,cruise_line_name')->where(['web_partner_id'=>$web_partner_id,'status'=>'active'])->get()->getResultArray();
    }

}


