<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class BusMarkupModel extends Model
{
    protected $table = 'agent_bus_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function bus_markup_list($web_partner_id,$wl_agent_id)
    {
        return  $this->select('agent_bus_markup.*')->where('web_partner_id',$web_partner_id)
            ->where('wl_agent_id',$wl_agent_id)
            ->orderBy("agent_bus_markup.id","DESC")->paginate(40);
    }

    public function bus_markup_details($id)
    {
        return  $this->select('agent_bus_markup.*')->where("id",$id)->get()->getRowArray();
    }



    public function remove_markup($id,$web_partner_id,$wl_agent_id)
    {
        return  $this->select('*')->where(['web_partner_id'=>$web_partner_id,'wl_agent_id'=>$wl_agent_id])->where("id",$id)->delete();
    }

    public function status_change($ids, $data)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->set($data)->update();
    }

    function search_data($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];



                return  $this->select('agent_bus_markup.*')->where('web_partner_id',$web_partner_id)
                    ->where($array)->where('wl_agent_id',$wl_agent_id)
                    ->orderBy("agent_bus_markup.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];



                return  $this->select('agent_bus_markup.*')->where('web_partner_id',$web_partner_id)->where('wl_agent_id',$wl_agent_id)
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("agent_bus_markup.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('agent_bus_markup.*')->where('web_partner_id',$web_partner_id)->where('wl_agent_id',$wl_agent_id)
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("agent_bus_markup.id","DESC")->paginate(40);
        }
    }
}