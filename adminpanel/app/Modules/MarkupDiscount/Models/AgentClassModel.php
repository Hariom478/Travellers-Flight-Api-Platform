<?php

namespace App\Modules\MarkupDiscount\Models;

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
}


