<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightOfflineModel extends Model
{
    protected $table = 'flight_offline';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function flight_offline_list($web_partner_id)
    {
        return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->paginate(40);
    }

    public function flight_offline_details($id, $web_partner_id)
    {
        return  $this->select('*')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    function search_data($data, $web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where(['web_partner_id'=>$web_partner_id])->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where(['web_partner_id'=>$web_partner_id])->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_flight_offline($id, $web_partner_id)
    {
        return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->whereIn("id",$id)->delete();
    }

    public function status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where(['web_partner_id'=>$web_partner_id])->whereIn('id', $ids)->set($data)->update();
    }
}


