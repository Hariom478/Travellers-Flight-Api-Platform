<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayListModel extends Model
{
    protected $table = 'holiday_list';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function holiday_list($web_partner_id)
    {
        return  $this->select('id,web_partner_id,holiday_name,holiday_slug,status,show_on_home,starting_price_standard,starting_price_deluxe,starting_price_luxury,price_on_request,book_online')->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }


    public function holiday_list_details($id,$web_partner_id)
    {
        return  $this->select('id,web_partner_id,holiday_name,holiday_slug,starting_price_standard,starting_price_deluxe,holiday_image,starting_price_luxury,advance_value,advance_days,price_on_request,holiday_themes_id,holiday_destination_id,holiday_includes,holiday_inclusion_id,holiday_exclusion_id,holiday_description,policies,show_on_home,passport_required_adult,pan_card_required_adult,passport_required_child,pan_card_required_child,payment_structure,documentation,refund_policy,cancellation_policy,book_online,meta_title,meta_keyword,meta_description,meta_robots,status')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('holiday_image')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }


    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("holiday_list")->select('id,web_partner_id,holiday_name,holiday_slug')->where(['holiday_slug'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
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
                return  $this->select('id,holiday_name,holiday_slug,status,show_on_home,starting_price_standard,starting_price_deluxe,starting_price_luxury,price_on_request,book_online')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,holiday_name,holiday_slug,status,show_on_home,starting_price_standard,starting_price_deluxe,starting_price_luxury,price_on_request,book_online')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('id,holiday_name,holiday_slug,status,show_on_home,starting_price_standard,starting_price_deluxe,starting_price_luxury,price_on_request,book_online')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }


    public function remove_holiday_list($ids,$web_partner_id)
    {
        return  $this->select('*')->where("id",$ids)->where(['web_partner_id'=>$web_partner_id])->delete();
    }

    public function holiday_list_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}


