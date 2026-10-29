<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayItineraryModel extends Model
{
    protected $table = 'holiday_itinerary';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_itinerary($holiday_list_id,$web_partner_id)
    {
        return  $this->select('*')->where('holiday_list_id', $holiday_list_id)->where(["web_partner_id"=>$web_partner_id])->orderBy("id","ASC")->get()->getResultArray();
    }



    public function delete_image($id)
    {
        return  $this->select('itinerary_image')->where("id",$id)->get()->getRowArray();
    }



}


