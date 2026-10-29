<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class WebPartnerHolidayDiscountModel extends Model
{
    protected $table = 'web_partner_holiday_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;

    function getHolidayDiscount($web_partner_id,$input){

        $builder = $this->db->table('web_partner_holiday_discount');
        $builder->select('max_limit,discount_type,value,extra_discount,theme_id,destination_id');
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
        $builder->where('discount_for', "B2B");
        $builder->where('status', 'active');
        $builder->orderBy('id','desc');
       return $result = $builder->get()->getRowArray();
    }


}
?>