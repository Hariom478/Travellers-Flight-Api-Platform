<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayDestinationModel extends Model
{
    protected $table = 'holiday_destination';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_destination_list_select_ajax($data,$web_partner_id){
        return  $this->select('id,destination_name as value')->like("destination_name",$data)->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();

    }

}


