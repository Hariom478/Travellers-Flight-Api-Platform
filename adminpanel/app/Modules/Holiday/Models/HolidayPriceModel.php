<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayPriceModel extends Model
{
    protected $table = 'holiday_price';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_price_list($holiday_list_id,$web_partner_id)
    {
        return  $this->select('holiday_price.id,holiday_price.start_date,holiday_price.end_date,holiday_price.standard_adult_price,holiday_price.standard_child_price,holiday_price.standard_extra_bad_price,holiday_price.deluxe_adult_price,holiday_price.deluxe_child_price,holiday_price.deluxe_extra_bad_price,holiday_price.luxury_adult_price,holiday_price.luxury_child_price,holiday_price.luxury_extra_bad_price,holiday_price.created,holiday_list.holiday_name')
            ->join('holiday_list', 'holiday_list.id = holiday_price.holiday_list_id')
            ->where('holiday_price.holiday_list_id', $holiday_list_id)->where('holiday_price.web_partner_id',$web_partner_id)->orderBy("holiday_price.id","DESC")->paginate(40);
    }

    public function holiday_price_details($id,$web_partner_id)
    {
        return  $this->select('id,start_date,end_date,standard_adult_price,standard_child_price,standard_extra_bad_price,deluxe_adult_price,deluxe_child_price,deluxe_extra_bad_price,luxury_adult_price,luxury_child_price,luxury_extra_bad_price')->where("id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
    }

    public function remove_holiday_price($ids,$web_partner_id)
    {

        return  $this->select('*')->wherein("id",$ids)->where('web_partner_id',$web_partner_id)->delete();
    }

}


