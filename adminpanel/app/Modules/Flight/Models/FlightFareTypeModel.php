<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightFareTypeModel extends Model
{

    protected $table = 'api_flight_fare_type';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function flight_fare_type_list()
    {
        return $this->select('*')->paginate(40);
    }

    function search_data($data)
    {
        return $this->select('*')->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(40);
    }

    public function api_supplier_list()
    {
        $builder = $this->db()->table('api_supplier');
        return $builder->select('api_supplier.supplier_name')
            ->where('api_supplier.flight', 'active')
            ->get()->getResultArray();
    }

    public function flight_fare_type_details($id)
    {
        return $this->select('*')->where("id", $id)->get()->getRowArray();
    }

    public function remove_fare_type($id)
    {

        return $this->select('*')->whereIn("id", $id)->delete();

    }



}