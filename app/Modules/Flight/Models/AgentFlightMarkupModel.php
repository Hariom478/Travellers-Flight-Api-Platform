<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class AgentFlightMarkupModel extends Model
{
    protected $table = 'agent_flight_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;



    function getAgentFlightmarkup($web_partner_id, $wl_agent_id,$input){
      
  
        if($input['IsDomestic']){
            $input['IsDomestic']=1;
        }
        else{
            $input['IsDomestic']= 0;
        }
        $builder = $this->db->table('agent_flight_markup');
        $builder->select('airline_code,markup_type,value,display_markup');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('is_domestic', $input['IsDomestic']);
        $builder->where('wl_agent_id', $wl_agent_id);
        $builder->where('status', 'active');
        $builder->orderBy('id', 'Desc');
       return $result = $builder->get()->getResultArray();
    }
}


