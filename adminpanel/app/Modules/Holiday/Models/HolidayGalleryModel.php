<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayGalleryModel extends Model
{
    protected $table = 'holiday_gallery';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_gallery($holiday_list_id,$web_partner_id)
    {
        return  $this->select('id,web_partner_id,images,image_title,status,created,modified')->where('holiday_list_id', $holiday_list_id)->where(["web_partner_id"=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

    public function holiday_gallery_details($id)
    {
        return  $this->select('id,web_partner_id,images,image_title,status')->where("id",$id)->get()->getRowArray();
    }

    public function delete_image($id,$web_partner_id)
    {
        return  $this->select('images')->where(["web_partner_id"=>$web_partner_id])->where("id",$id)->get()->getRowArray();
    }


    public function remove_holiday_gallery($id,$web_partner_id)

    {
        if (!is_array($id)) {
            $id = [$id];
        }
        return  $this->select('*')->whereIn("id",$id)->where(["web_partner_id"=>$web_partner_id])->delete();
    }

    public function holiday_gallery_status_change($ids, $data,$web_partner_id)
    {
        $ids = explode(',',$ids);
        return $this->select('*')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
}


