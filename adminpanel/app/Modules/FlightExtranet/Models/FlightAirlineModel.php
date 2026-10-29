<?php

namespace App\Modules\SuperAdminMarkupDiscount\Models;

use CodeIgniter\Model;

class FlightAirlineModel extends Model
{
    protected $table = 'flight_airline_code';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function airline_list()
    {
        return  $this->select('*')->paginate(40);
    }

    public function get_airline_autosuggestion($data)
    {
        return  $this->select('airline_code,airline_name')->like("airline_code",$data)->orLike('airline_name',$data)->limit(20)->get()->getResultArray();;
    }

    public function airline_details($id)
    {
        return  $this->select('*')->where("id",$id)->get()->getRowArray();
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

    public function remove_airline($id)
    {

        return  $this->select('*')->whereIn("id",$id)->delete();

    }
}


