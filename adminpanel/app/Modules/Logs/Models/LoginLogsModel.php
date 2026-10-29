<?php

namespace App\Modules\Logs\Models;

use CodeIgniter\Model;

class LoginLogsModel extends Model
{
    protected $table = 'logs_login';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function loginlogs_list($web_partner_id)
    {
        return  $this->select('*')->where("web_partner_id",$web_partner_id)->orderBy('id', 'DESC')->paginate(40);
    }


    public function remove_login_logs($web_partner_id,$id)
    {

        return  $this->select('*')->where("web_partner_id",$web_partner_id)->whereIn("id",$id)->delete();

    }
}


