<?php

namespace App\Modules\Blog\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'blog_category';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function blog_category_list($web_partner_id)
    {

        return  $this->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->paginate(40);

    }

    public function blog_category_details($category_id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$category_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function delete_image($category_id,$web_partner_id)
    {
        return  $this->select('category_img')->where("id",$category_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    public function remove_category($category_id,$web_partner_id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("id",$category_id)->delete();
    }

    public function category_status_change($web_partner_id, $ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }





    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("blog_category")->select('*')->where(['category_slug'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }





}