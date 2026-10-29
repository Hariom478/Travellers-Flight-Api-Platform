<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class WebPartnerModel extends Model
{
    protected $table = 'web_partner';
    protected $primarykey = 'id';
    protected $protectFields = false;

    

    public function web_partner_list_details($id)
    {
        return $this->select('*')->where("id", $id)->get()->getRowArray();
    }
}
