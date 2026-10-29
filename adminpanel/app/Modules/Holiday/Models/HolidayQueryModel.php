<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayQueryModel extends Model
{
    protected $table = 'holiday_query';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function query_list($web_partner_id)
    {
        return  $this->select('holiday_query.*,web_partner.company_name')->orderBy("id","DESC")
           ->join('web_partner', "web_partner.id = holiday_query.web_partner_id","Left")
            ->paginate(40);
    }
    public function query_list_export_query($web_partner_id)
    {
        return  $this->select('holiday_query.*,web_partner.company_name')->orderBy("id","DESC")
           ->join('web_partner', "web_partner.id = holiday_query.web_partner_id","Left")->where('web_partner_id',$web_partner_id)
           ->get()->getResultArray();
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['.holiday_query.created >='=> $from_date,'holiday_query.created <='=> $to_date];
                return  $this->select('holiday_query.*,web_partner.company_name')->orderBy('id', 'DESC')->join('web_partner', "web_partner.id = holiday_query.web_partner_id","Left")->where($array)->where('web_partner_id',$web_partner_id)->paginate(40);
            } else {
                $array=['holiday_query.created >='=> $from_date,'holiday_query.created <='=> $to_date];
                return  $this->select('holiday_query.*,web_partner.company_name')->orderBy('id', 'DESC')->where($array)->join('web_partner', "web_partner.id = holiday_query.web_partner_id","Left")->where('web_partner_id',$web_partner_id)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('holiday_query.*,web_partner.company_name')->orderBy('id', 'DESC')->where('web_partner_id',$web_partner_id)->join('web_partner', "web_partner.id = holiday_query.web_partner_id","Left")->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

}


