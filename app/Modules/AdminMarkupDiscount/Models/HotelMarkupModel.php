<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class HotelMarkupModel extends Model
{
    protected $table = 'agent_hotel_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function markup_list($web_partner_id, $wl_agent_id)
    {

        return $this->select('agent_hotel_markup.*')->where(["agent_hotel_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])
            ->orderBy("agent_hotel_markup.id", "DESC")->paginate(40);
    }

    public function remove_markup($ids,$web_partner_id)
    {
        return $this->select('*')->where('web_partner_id',$web_partner_id)->whereIn("id", $ids)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function markup_details($id)
    {
        return $this->select('agent_hotel_markup.*')->where('agent_hotel_markup.id', $id)

            ->orderBy("agent_hotel_markup.id", "DESC")->get()->getRowArray();
    }

    function search_data($web_partner_id, $wl_agent_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['agent_hotel_markup.created >=' => $from_date, 'agent_hotel_markup.created <=' => $to_date];
                return $this->select('agent_hotel_markup.*')

                    ->orderBy('agent_hotel_markup.id', 'DESC')->where(["agent_hotel_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->where($array)->paginate(10);
            } else {
                $array = ['agent_hotel_markup.created >=' => $from_date, 'agent_hotel_markup.created <=' => $to_date];
                return $this->select('agent_hotel_markup.*')

                    ->orderBy('agent_hotel_markup.id', 'DESC')->where(["agent_hotel_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('agent_hotel_markup.*')

                ->orderBy('agent_hotel_markup.id', 'DESC')->where(["agent_hotel_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }
}