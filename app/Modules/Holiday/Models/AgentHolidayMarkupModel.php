<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class AgentHolidayMarkupModel extends Model
{
    protected $table = 'agent_holiday_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function getHolidayMarkup($web_partner_id,$input,$wl_agent_id){

        $builder = $this->db->table('agent_holiday_markup');
        $builder->select('max_limit,markup_type,value,display_markup,theme_id,destination_id,holiday_package_id');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('wl_agent_id',$wl_agent_id);
        if($input['tag'] == 'Themes'){
            $builder->groupStart();
            $builder->where('find_in_set("'.$input['id'].'",theme_id) <> 0');
            $builder->orWhere('theme_id','ANY');
            $builder->groupEnd();
        }
        if($input['tag'] == 'Destinations'){
            $builder->groupStart();
            $builder->where('find_in_set("'.$input['id'].'",destination_id) <> 0');
            $builder->orWhere('destination_id','ANY');
            $builder->groupEnd();
        }
        if($input['tag'] == 'Packages'){
            $builder->groupStart();
            $builder->where('find_in_set("'.$input['id'].'",holiday_package_id) <> 0');
            $builder->orWhere('holiday_package_id','ANY');
            $builder->groupEnd();
        }
        $builder->where('status', 'active');
        $builder->orderBy('id','desc');
       return $result = $builder->get()->getRowArray();
    }


}
?>