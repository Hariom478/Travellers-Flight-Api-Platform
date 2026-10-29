<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;

class AgentClassModel extends Model
{
    protected $table = 'agent_class';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function agent_class_list($web_partner_id)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->paginate(40);
    }


    public function remove_agent_class($web_partner_id, $blog_ids)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->where("id", $blog_ids)->delete();
    }

}


