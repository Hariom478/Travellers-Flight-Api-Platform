<?php

namespace App\Modules\FlightExtranet\Models;

use CodeIgniter\Model;

class FlightCrsFareRuleModel extends Model
{
    protected $table = 'flight_crs_fare_rule';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function fare_rule_list($web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->paginate(40);
    }


    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function remove_fare_rule_list($id,$web_partner_id)
    { 
        return $this->select('*')->whereIn("id", $id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }


    public function fare_rule_status_change($ids,$data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
    public function fare_rule_detail($id,$web_partner_id)
    {
        return $this->select('*')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }
}


