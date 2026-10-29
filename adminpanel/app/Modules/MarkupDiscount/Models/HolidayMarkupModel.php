<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class HolidayMarkupModel extends Model
{
    protected $table = 'web_partner_holiday_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function super_admin_holiday_markup_list($web_partner_id)
    {
        return  $this->select('web_partner_holiday_markup.*' )->where('web_partner_id',$web_partner_id)

            ->orderBy("web_partner_holiday_markup.id","DESC")->paginate(40);
    }

    public function super_admin_holiday_markup_details($id,$web_partner_id)
    {
        return  $this->select('web_partner_holiday_markup.*')->where("web_partner_holiday_markup.id",$id)->where('web_partner_id',$web_partner_id)->get()->getRowArray();
    }



    public function remove_markup($id,$web_partner_id)
    {
        return  $this->select('*')->where("id",$id)->where('web_partner_id',$web_partner_id)->delete();
    }

    public function status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id',$web_partner_id)->set($data)->update();
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

                return  $this->select('web_partner_holiday_markup.*')
                    ->where($array)->where('web_partner_id',$web_partner_id)
                    ->orderBy("web_partner_holiday_markup.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('web_partner_holiday_markup.*')->where('web_partner_id',$web_partner_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("web_partner_holiday_markup.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('web_partner_holiday_markup.*')->where('web_partner_id',$web_partner_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("web_partner_holiday_markup.id","DESC")->paginate(40);


        }
    }
}