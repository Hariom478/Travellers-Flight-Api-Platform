<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayInclusionModel extends Model
{
    protected $table = 'holiday_inclusion';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function holiday_inclusion_list($web_partner_id)
    {
        return  $this->select('id,web_partner_id,inclusion_title,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }


    public function holiday_inclusion_details($id,$web_partner_id)
    {
        return  $this->select('id,inclusion_title,status')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function holiday_inclusion_list_select($web_partner_id){
        return  $this->select('id,inclusion_title,web_partner_id')->where(['web_partner_id'=>$web_partner_id,"status"=>'active'])->get()->getResultArray();

    }

    function search_data($data)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,inclusion_title,status,created,modified')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,inclusion_title,status,created,modified')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('id,inclusion_title,status,created,modified')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_holiday_inclusion($ids ,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$ids)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function holiday_inclusion_status_change($ids, $data, $web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}


