<?php

namespace App\Modules\FlightExtranet\Models;

use CodeIgniter\Model;

class FlightCrsSeatAllocationModel extends Model
{
    protected $table = 'flight_crs_details';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function seat_allocation_list($private_fare_id, $web_partner_id)
    {
        return $this->select('flight_crs_details.*,flight_crs_rate_plan.plan_name')->where('privatefare_id', $private_fare_id)
            ->join('flight_crs_rate_plan', 'flight_crs_rate_plan.id = flight_crs_details.rate_plan_id', 'Left')
            ->where(['flight_crs_details.web_partner_id' => $web_partner_id])
            ->orderBy('id', 'DESC')->paginate(40);
    }

    public function details($id, $web_partner_id)
    {
        return $this->select('*')->where("id", $id)->where(['web_partner_id' => $web_partner_id])->get()->getRowArray();
    }
    public function details_by_date($date, $web_partner_id)
    {
        return $this->select('*')->where("date", $date)->where(['web_partner_id' => $web_partner_id])->get()->getRowArray();
    }

    function search_data($data, $web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->where(['web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->where(['web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function remove_seat_allocation($id, $web_partner_id)
    {
        return $this->select('*')->where(['web_partner_id' => $web_partner_id])->whereIn("id", $id)->delete();
    }


    function updateDataTable($datatoinsert, $id)
    {
        $this->db->table('flight_crs_details')->where('privatefare_id', $id)->update($datatoinsert);

    }

    function isFareDetailExist($ids)
    {
        return $this->db->table("flight_crs_details")
            ->select("id")
            ->whereIn("privatefare_id", $ids)
            ->get()
            ->getResultArray();
    }
}


