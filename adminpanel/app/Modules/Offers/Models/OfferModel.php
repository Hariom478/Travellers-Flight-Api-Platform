<?php

namespace App\Modules\Offers\Models;

use CodeIgniter\Model;

class OfferModel extends Model
{
    protected $table = 'web_partner_offers';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function offer_list($web_partner_id)
    {
        return $this->select('id,title,description,service,url,status,image,created')->where(["web_partner_id"=>$web_partner_id])->paginate(40);
    }

    public function offer_details($id,$web_partner_id)
    {
        return $this->select('id,title,description,service,url,status,image')->where("id", $id)->where(["web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }

    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,title,description,service,url,status,image,created')->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,title,description,service,url,status,image,created')->where($array)->like(trim($data['key']), trim($data['value']))->where(["web_partner_id"=>$web_partner_id])->paginate(40);
            }
        } else {
            return $this->select('id,title,description,service,url,image,status,created')->like(trim($data['key']), trim($data['value']))->where(["web_partner_id"=>$web_partner_id])->paginate(40);
        }
    }

    public function delete_image($id,$web_partner_id)
    {
        return $this->select('image')->where("id", $id)->where(["web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }

    public function remove_offer($id,$web_partner_id)
    {
        return $this->select('*')->where("id", $id)->where(["web_partner_id"=>$web_partner_id])->delete();
    }

    public function offers_status_change($ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }
}


