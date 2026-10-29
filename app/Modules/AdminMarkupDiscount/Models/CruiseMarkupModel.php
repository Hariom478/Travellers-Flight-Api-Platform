<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class CruiseMarkupModel extends Model
{
    protected $table = 'agent_cruise_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function markup_list($web_partner_id, $wl_agent_id)
    {

        return $this->select('agent_cruise_markup.id,agent_cruise_markup.markup_type,agent_cruise_markup.value,agent_cruise_markup.max_limit,agent_cruise_markup.display_markup,agent_cruise_markup.status,agent_cruise_markup.created,agent_cruise_markup.modified,cruise_line.cruise_line_name,cruise_ship.ship_name,cruise_ports.port_name')->join('cruise_line', 'cruise_line.id = agent_cruise_markup.cruise_line_id','Left')
        ->join('cruise_ship', 'cruise_ship.id = agent_cruise_markup.cruise_ship_id','Left')
        ->join('cruise_ports', 'cruise_ports.id = agent_cruise_markup.departure_port_id','Left')
         ->where(["agent_cruise_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])
            ->orderBy("agent_cruise_markup.id", "DESC")->paginate(40);
    }

    public function remove_markup($ids)
    {
        return $this->select('*')->whereIn("id", $ids)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    public function markup_details($id)
    {
        return $this->select('agent_cruise_markup.*')->where('agent_cruise_markup.id', $id)

            ->orderBy("agent_cruise_markup.id", "DESC")->get()->getRowArray();
    }


    function search_data($web_partner_id, $wl_agent_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['agent_cruise_markup.created >=' => $from_date, 'agent_cruise_markup.created <=' => $to_date];
                return $this->select('agent_cruise_markup.*')

                    ->orderBy('agent_cruise_markup.id', 'DESC')->where(["agent_cruise_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->where($array)->paginate(10);
            } else {
                $array = ['agent_cruise_markup.created >=' => $from_date, 'agent_cruise_markup.created <=' => $to_date];
                return $this->select('agent_cruise_markup.*')

                    ->orderBy('agent_cruise_markup.id', 'DESC')->where(["agent_cruise_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('agent_cruise_markup.*')

                ->orderBy('agent_cruise_markup.id', 'DESC')->where(["agent_cruise_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }
}
