<?php

namespace App\Modules\SuperAdminMarkupDiscount\Models;

use CodeIgniter\Model;

class FlightAirportModel extends Model
{
    protected $table = 'flight_airports';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function airport_list()
    {
        return  $this->select('*')->paginate(40);
    }


    public function airport_details($id)
    {
        return  $this->select('*')->where("id",$id)->get()->getRowArray();
    }

    public function get_airport_autosuggestion($data)
    {
        return  $this->select('*')->like("code",$data)->orLike('city_name',$data)->orLike('country_name',$data)->orLike('country_code',$data)->limit(20)->get()->getResultArray();;
    }

    function search_data($data)
    {
        if(isset($data['from_date']) && isset($data['to_date']))
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }


    public function remove_airport($id)
    {

        return  $this->select('*')->whereIn("id",$id)->delete();

    }
}


