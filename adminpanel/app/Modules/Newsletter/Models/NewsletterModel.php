<?php

namespace App\Modules\Newsletter\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table = 'newsletter';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function newsletter_list($web_partner_id)
    {
        return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->paginate(40);
    }

    public function newsletter_list_excel($data,$web_partner_id)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            $array=['created >='=> $from_date,'created <='=> $to_date];
            return  $this->select('*')->orderBy('id', 'DESC')->where(['web_partner_id'=>$web_partner_id])->where($array)->get()->getResultArray();
        } else {
            return  $this->select('*')->orderBy('id', 'DESC')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
        }
    }

    public function newsletter_details($id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_newsletter($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
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
                return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy('id', 'DESC')->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }
}


