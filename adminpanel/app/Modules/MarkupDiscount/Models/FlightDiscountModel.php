<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class FlightDiscountModel extends Model
{
    protected $table = 'web_partner_flight_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function discount_list($web_partner_id)
    {
        return $this->select('web_partner_flight_discount.*')->where('web_partner_id',$web_partner_id)
            ->orderBy("id", "DESC")->paginate(40);
    }

    public function remove_discount($ids)
    {
        return $this->select('*')->whereIn("id", $ids)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function discount_details($id)
    {

        return $this->select('web_partner_flight_discount.*')->where('web_partner_flight_discount.id', $id)

            ->orderBy("id", "DESC")->get()->getRowArray();

    }


    function search_data($web_partner_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_flight_discount.created >=' => $from_date, 'web_partner_flight_discount.created <=' => $to_date];
                return $this->select('web_partner_flight_discount.*')->where('web_partner_id',$web_partner_id)
                    ->orderBy('web_partner_flight_discount.id', 'DESC')->where($array)->paginate(10);
            } else {
                $array = ['web_partner_flight_discount.created >=' => $from_date, 'web_partner_flight_discount.created <=' => $to_date];
                return $this->select('web_partner_flight_discount.*')->where('web_partner_id',$web_partner_id)
                    ->orderBy('web_partner_flight_discount.id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('web_partner_flight_discount.*')->where('web_partner_id',$web_partner_id)
                ->orderBy('web_partner_flight_discount.id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }
    function getApiFlighFareType()
    {
        $fareTypes =  array();
    $builder  =  $this->db->table('api_flight_fare_type');
    $builder->select('supplier_fare_type,api_fare_type');
    $result   = $builder->get()->getResultArray();
    if($result)
    {
        $fareTypes =  array_column($result,'api_fare_type','supplier_fare_type') ;
    }
    return  $fareTypes;
    }
}


