<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class DistributorModel extends Model
{
    protected $table = 'distributors';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function get_distributor_detail($distributor_id,$web_partner_id)
    {
        return $this->select('distributors.company_name,distributors.company_id,distributor_users.login_email,distributor_users.first_name,distributor_users.last_name')->join('distributor_users','distributor_users.distributor_id = distributors.id','left')->where('distributor_users.primary_user',1)->where('distributors.web_partner_id', $web_partner_id)->where('distributors.id',$distributor_id)->orderBy("id", "DESC")->get()->getRowArray();
    }
}


