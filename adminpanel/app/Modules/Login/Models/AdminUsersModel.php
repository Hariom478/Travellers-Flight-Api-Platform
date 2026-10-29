<?php

namespace App\Modules\Login\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class AdminUsersModel extends Model
{
    protected $table = 'admin_users';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function admin_user_details($id)
    {

        return $this->select('id,first_name,last_name,mobile_isd,mobile_no,
        whatsapp_no,street,city,state,country,pin_code')
            ->where('primary_user', 1)
            ->where('web_partner_id', $id)->get()->getRowArray();
    }
}
