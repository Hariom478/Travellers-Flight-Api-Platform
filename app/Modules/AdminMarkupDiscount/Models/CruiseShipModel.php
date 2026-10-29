<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class CruiseShipModel extends Model
{
    protected $table = 'cruise_ship';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_ship_list($web_partner_id)
    {
        return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
            ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
            ->orderBy('id', 'DESC')->paginate(40);
    }

    public function cruise_ship_details($id,$web_partner_id)
    {
        return  $this->select('id,cruise_line_id,ship_name,status,ship_name_slug,ship_description,cancellation_policy,payment_policy,ship_image')->where(["id"=>$id,'web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['cruise_ship.created >='=> $from_date,'cruise_ship.created <='=> $to_date];


                return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                    ->where($array)->orderBy('id', 'DESC')->paginate(40);
            } else {
                $array=['cruise_ship.created >='=> $from_date,'cruise_ship.created <='=> $to_date];

                return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                    ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                    ->where($array)->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);


            }
        } else {
            return  $this->select('cruise_ship.id,cruise_ship.ship_name,cruise_ship.ship_image,cruise_ship.status,cruise_ship.ship_name_slug,cruise_ship.created,cruise_ship.modified,cruise_line.cruise_line_name')
                ->join('cruise_line', 'cruise_line.id = cruise_ship.cruise_line_id','Left')->where(['cruise_ship.web_partner_id'=>$web_partner_id])
                ->like(trim($data['key']),trim($data['value']))->orderBy('id', 'DESC')->paginate(40);

        }
    }
    public function cruise_ship_select($web_partner_id)
    {
        return  $this->select('id,ship_name')->where("web_partner_id",$web_partner_id)->get()->getResultArray();
    }

    public function cruise_ship_select_cruise_line($cruise_line_id){
        return  $this->select('id,ship_name')->where("cruise_line_id",$cruise_line_id)->where('status','active')->get()->getResultArray();
    }
}


