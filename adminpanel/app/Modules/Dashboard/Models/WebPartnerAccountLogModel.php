<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;

class WebPartnerAccountLogModel extends Model
{
    protected $table = 'web_partner_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;
    public function available_balance($web_partner_id){
        return  $this->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}


