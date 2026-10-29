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

    public function agent_class_list_select($web_partner_id)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->get()->getResultArray();
    }
    public function update_agent_class($data,$web_partner_id,$id)
    {
        return $this->select('*')->where(array('web_partner_id'=>$web_partner_id,'id'=>$id))->set($data)->update();
    }
}


