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
}


