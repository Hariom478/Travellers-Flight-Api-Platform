<?php

namespace App\Modules\FlightTopRoutes\Models;
use CodeIgniter\Model;

class FlightTopRoutesModel extends Model
{
    protected $table = 'flight_top_routes';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function flight_top_route_list($web_partner_id)
    {
        return $this->select('flight_top_routes.*')->where(['web_partner_id'=>$web_partner_id])->orderBy("id", "DESC")->paginate(40);
    }

    public function remove_top_routes_list($ids,$web_partner_id)
    {
        return $this->select('*')->where("id", $ids)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function status_change($ids, $data ,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

    public function flight_top_routus_details($id,$web_partner_id)
    {

        return $this->select('flight_top_routes.*')->where(['web_partner_id'=>$web_partner_id])->where('flight_top_routes.id', $id)

            ->orderBy("id", "DESC")->get()->getRowArray();

    }


    function search_data($data,$web_partner_id)
    {
            return $this->select('flight_top_routes.*')->where(['web_partner_id'=>$web_partner_id])
                ->orderBy('flight_top_routes.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
       
    }

}


