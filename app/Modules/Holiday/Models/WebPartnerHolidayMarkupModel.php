<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class WebPartnerHolidayMarkupModel extends Model
{
    protected $table = 'web_partner_holiday_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function getHolidayMarkup($web_partner_id,$input){

        $builder = $this->db->table('web_partner_holiday_markup');
        $builder->select('max_limit,markup_type,value,display_markup,theme_id,destination_id,holiday_package_id');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('find_in_set("'.$input['agent_class'].'",agent_class) <> 0');
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
        $builder->where('markup_for', "B2B");
        $builder->where('status', 'active');
        $builder->orderBy('id','desc');
       return $result = $builder->get()->getRowArray();
    }


}
?>