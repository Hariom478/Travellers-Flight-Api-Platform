<?php

namespace App\Modules\Blog\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table = 'blog_post';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function blog_list($web_partner_id)
    {
        return  $this->select('blog_post.*, blog_category.category_name ')->join('blog_category','blog_category.id = blog_post.category_id','left')->where('blog_post.web_partner_id', $web_partner_id)->paginate(40);
    }

    public function blog_category_list($web_partner_id)
    {

        return  $this->db->table("blog_category")->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id","DESC")->get()->getResultArray();


    }

    public function blog_list_details($blog_id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$blog_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }



    public function CheckUniqueSlug($value,$web_partner_id)
    {
      return $this->db->table("blog_post")->select('*')->where(['post_slug'=>$value,'web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }


    function search_data($web_partner_id,$data)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['blog_post.created >='=> $from_date,'blog_post.created <='=> $to_date];
                return  $this->select('blog_post.*, blog_category.category_name ')->join('blog_category','blog_category.id = blog_post.category_id','left')->where('blog_post.web_partner_id', $web_partner_id)->where($array)->paginate(40);
            } else {
                $array=['blog_post.created >='=> $from_date,'blog_post.created <='=> $to_date];
                return  $this->select('blog_post.*, blog_category.category_name ')->join('blog_category','blog_category.id = blog_post.category_id','left')->where('blog_post.web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('blog_post.*, blog_category.category_name ')->join('blog_category','blog_category.id = blog_post.category_id','left')->where('blog_post.web_partner_id', $web_partner_id)->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function delete_image($category_id,$web_partner_id)
    {
        return  $this->select('post_images')->where("id",$category_id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }

    public function remove_blog($blog_ids,$web_partner_id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("id",$blog_ids)->delete();
    }

    public function blog_status_change($web_partner_id, $ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }
}


