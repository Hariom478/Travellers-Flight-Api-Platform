<?php

namespace App\Modules\Agent\Models;

use CodeIgniter\Model;

class BusMarkupModel extends Model
{
    protected $table = 'agent_bus_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function markup_list($web_partner_id)
    {
        return $this->select('agent_bus_markup.id,agent_bus_markup.web_partner_id,agent_bus_markup.markup_type,agent_bus_markup.min_range,agent_bus_markup.max_range,agent_bus_markup.value,agent_bus_markup.status,agent_bus_markup.created,agent_bus_markup.modified,agent_class.class_name')
            ->join('agent_class', 'agent_class.id = agent_bus_markup.agent_class')
            ->where('agent_bus_markup.web_partner_id', $web_partner_id)->orderBy("agent_bus_markup.id", "DESC")->paginate(40);
    }

    public function remove_markup($web_partner_id, $ids)
    {
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn("id", $ids)->delete();
    }

    public function status_change($web_partner_id, $ids, $data)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn('id', $ids)->set($data)->update();
    }

    public function markup_details($web_partner_id,$id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where('id', $id)->where("id",$id)->get()->getRowArray();
    }


    function search_data($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id', $web_partner_id)->where($array)->paginate(10);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id', $web_partner_id)->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id', $web_partner_id)->like(trim($data['key']), trim($data['value']))->paginate(10);
        }
    }

}
