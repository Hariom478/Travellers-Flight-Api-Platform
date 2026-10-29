<?php

namespace App\Modules\Slider\Models;

use CodeIgniter\Model;

class SliderModel extends Model
{
    protected $table = 'slider';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function slider_list($web_partner_id)
    {
        return  $this->select('id,slider_image,slider_text1,slider_text2,image_category,url_button_text,status,created,modified')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);
    }


    public function slider_list_details($id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('slider_image')->where("id",$id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    public function remove_slider($ids,$web_partner_id)
    {

        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("id",$ids)->delete();

    }

    public function slider_status_change($web_partner_id, $ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }

    function search_data($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where("web_partner_id",$web_partner_id)->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where("web_partner_id",$web_partner_id)->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->where("web_partner_id",$web_partner_id)->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
         
    }
}


