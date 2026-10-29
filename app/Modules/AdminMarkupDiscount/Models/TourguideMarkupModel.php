<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class TourguideMarkupModel extends Model
{
    protected $table = 'agent_tourguide_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function tourguide_markup_list($web_partner_id, $wl_agent_id)
    {
        return $this->select('agent_tourguide_markup.*')->where('wl_agent_id', $wl_agent_id)->where('web_partner_id', $web_partner_id)

            ->orderBy("agent_tourguide_markup.id", "DESC")->paginate(40);
    }

    public function tourguide_markup_details($id, $web_partner_id, $wl_agent_id)
    {
        return $this->select('agent_tourguide_markup.*')->where("agent_tourguide_markup.id", $id)->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)->get()->getRowArray();
    }


    function search_data($web_partner_id, $wl_agent_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return $this->select('agent_tourguide_markup.*')
                    ->where($array)->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)
                    ->orderBy("agent_tourguide_markup.id", "DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return $this->select('agent_tourguide_markup.*')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("agent_tourguide_markup.id", "DESC")->paginate(40);
            }
        } else {

            return $this->select('agent_tourguide_markup.*')->where('web_partner_id', $web_partner_id)->where('wl_agent_id', $wl_agent_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("agent_tourguide_markup.id", "DESC")->paginate(40);
        }
    }

    public function status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where('web_partner_id', $web_partner_id)->set($data)->update();
    }

    public function remove_markup($id, $web_partner_id)
    {
        return $this->select('*')->where("id", $id)->where('web_partner_id', $web_partner_id)->delete();
    }

    

}