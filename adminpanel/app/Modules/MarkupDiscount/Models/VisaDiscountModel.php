<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class VisaDiscountModel extends Model
{
    protected $table = 'web_partner_visa_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function super_admin_visa_discount_list($web_partner_id)
    {
        return  $this->select('web_partner_visa_discount.*')->where('web_partner_id',$web_partner_id)

            ->orderBy("id","DESC")->paginate(40);
    }

    public function super_admin_visa_discount_details($id)
    {
        return  $this->select('web_partner_visa_discount.*')->where("id",$id)->get()->getRowArray();
    }



    public function remove_discount($id)
    {
        return  $this->select('*')->where("id",$id)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function web_partner_class()
    {

        return  $this->db->table("web_partner_class")->select('id,class_name')->orderBy("id","DESC")->get()->getResultArray();


    }

    function search_data($web_partner_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('web_partner_visa_discount.*')
                    ->where('web_partner_id',$web_partner_id)
                    ->where($array)
                    ->orderBy("id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('web_partner_visa_discount.*')
                    ->where('web_partner_id',$web_partner_id)
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('web_partner_visa_discount.*')
                ->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("id","DESC")->paginate(40);
        }
    }
}