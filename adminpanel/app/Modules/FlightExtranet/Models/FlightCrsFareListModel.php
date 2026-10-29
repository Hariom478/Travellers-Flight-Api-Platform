<?php

namespace App\Modules\FlightExtranet\Models;

use CodeIgniter\Model;

class FlightCrsFareListModel extends Model
{
    protected $table = 'flight_crs_fare_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_crs_fare_list($web_partner_id)
    {
        return $this->select('id,web_partner_id,inventory_name,origin,destination,trip_type,journey_type,onward_stops,return_stops,onward_segment_detail,status,disable_before_departure,is_next_day_arrival,created,modified')
        ->where(['web_partner_id'=>$web_partner_id])
        ->orderBy('id', 'DESC')
        ->paginate(40);
    }

    public function remove_flight_crs_fare($ids,$web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->whereIn("id", $ids)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }

    public function flight_crs_fare_details($id,$web_partner_id)
    {
        return  $this->select('*')->where('id', $id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }


    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where($array)->where(['web_partner_id'=>$web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->orderBy('id', 'DESC')->where(['web_partner_id'=>$web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }

}


