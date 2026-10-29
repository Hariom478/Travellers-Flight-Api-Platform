<?php

namespace App\Modules\FlightExtranet\Models;

use CodeIgniter\Model;

class FlightCrsDetailsModel extends Model
{
    protected $table = 'flight_crs_details';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_crs_details_list($privateFareId,$web_partner_id)
    {
        return $this->select('*')->where(["privatefare_id"=>$privateFareId,'web_partner_id'=>$web_partner_id])->orderBy("id", "DESC")->paginate(40);
    }
    public function flight_crs_fare_detail($privateFareId)
    {
        return $this->db->table('flight_crs_fare_list')->select('*')->where(["id"=>$privateFareId,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

   
}


