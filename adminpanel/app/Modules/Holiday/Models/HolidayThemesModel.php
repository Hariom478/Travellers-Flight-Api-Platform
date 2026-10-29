<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayThemesModel extends Model
{
    protected $table = 'holiday_themes';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function holiday_themes_list($web_partner_id)
    {
        return  $this->select('id,web_partner_id,theme_name,theme_slug,theme_image,theme_icon,starting_price,show_on_home,status,created,modified')->where(["web_partner_id"=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }


    public function holiday_themes_details($id,$web_partner_id)
    {
        return  $this->select('id,web_partner_id,theme_name,theme_slug,theme_image,theme_icon,starting_price,show_on_home,status,description,meta_title,meta_keyword,meta_description,meta_robots')->where("id",$id)->where(["web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }

    public function holiday_themes_list_select($web_partner_id){
        return  $this->select('id,theme_name,web_partner_id')->where(["web_partner_id"=>$web_partner_id,"status"=>'active'])->get()->getResultArray();
    }

    public function holiday_themes_list_select_ajax($data,$web_partner_id){
        return  $this->select('id,theme_name as value')->like("theme_name",$data)->where(["web_partner_id"=>$web_partner_id,"status"=>'active'])->get()->getResultArray();

    }

    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("holiday_themes")->select('id,web_partner_id,theme_name,theme_slug')->where(['theme_slug'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }

    

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('theme_image,theme_icon,web_partner_id')->where("id",$id)->where(["web_partner_id"=>$web_partner_id])->get()->getRowArray();
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
                return  $this->select('id,theme_name,theme_slug,theme_image,theme_icon,starting_price,show_on_home,status,created,modified')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('id,theme_name,theme_slug,theme_image,theme_icon,starting_price,show_on_home,status,created,modified')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('id,theme_name,theme_slug,theme_image,starting_price,theme_icon,show_on_home,status,created,modified')->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_holiday_themes($ids,$web_partner_id)
    {
        return  $this->select('*')->where("id",$ids)->where(["web_partner_id"=>$web_partner_id])->delete();
    }

    public function holiday_themes_status_change($ids, $data, $web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(["web_partner_id"=>$web_partner_id])->set($data)->update();
    }
}


