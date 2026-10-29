<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayDestinationModel extends Model
{
    protected $table = 'holiday_destination';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function holiday_destination_list($web_partner_id)
    {
        return  $this->select('id,web_partner_id,destination_name,destination_slug,destination_image,starting_price,show_on_home,destination_type,destination_country,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }


    public function holiday_destination_details($id,$web_partner_id)
    {
        return  $this->select('id,web_partner_id,destination_name,destination_slug,destination_image,starting_price,show_on_home,status,description,destination_type,destination_country,meta_title,meta_keyword,meta_description,meta_robots')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function holiday_destination_list_select($web_partner_id){
        return  $this->select('id,destination_name,web_partner_id')->where(['web_partner_id'=>$web_partner_id,"status"=>'active'])->get()->getResultArray();
    }


    public function holiday_destination_list_select_ajax($data,$web_partner_id){
        return  $this->select('id,destination_name as value')->like("destination_name",$data)->where(['web_partner_id'=>$web_partner_id,"status"=>'active'])->get()->getResultArray();

    }

 


  
    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("holiday_destination")->select('id,web_partner_id,destination_name,destination_slug')->where(['destination_slug'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
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
                return  $this->select('id,destination_name,destination_slug,destination_image,starting_price,show_on_home,status,created,modified')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,destination_name,destination_slug,destination_image,starting_price,show_on_home,status,created,modified')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('id,destination_name,destination_slug,destination_image,starting_price,show_on_home,status,created,modified')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('destination_image')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_holiday_destination($ids,$web_partner_id)
    {
        return  $this->select('*')->where("id",$ids)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function holiday_destination_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}


