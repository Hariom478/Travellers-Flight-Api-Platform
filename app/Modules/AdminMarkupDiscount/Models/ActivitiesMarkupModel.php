<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class ActivitiesMarkupModel extends Model   
{
    protected $table = 'agent_activities_markup';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function activities_markup_list($web_partner_id,$wl_agent_id)
    {
        return  $this->select('agent_activities_markup.*')->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])

            ->orderBy("agent_activities_markup.id","DESC")->paginate(40);
    }

    public function activities_markup_details($id,$web_partner_id,$wl_agent_id)
    {
        return  $this->select('agent_activities_markup.*')->where("agent_activities_markup.id",$id)->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->get()->getRowArray();
    }



    public function remove_markup($id,$web_partner_id,$wl_agent_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->delete();
    }

    public function status_change($ids, $data,$web_partner_id,$wl_agent_id)
    {
        $ids= explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])->set($data)->update();
    }

    

    function search_data($data,$web_partner_id,$wl_agent_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('agent_activities_markup.*')
                    ->where($array)->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])
                    ->orderBy("agent_activities_markup.id","DESC")->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];

                return  $this->select('agent_activities_markup.*')->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])
                    ->like(trim($data['key']), trim($data['value']))
                    ->orderBy("agent_activities_markup.id","DESC")->paginate(40);
            }
        } else {

            return  $this->select('agent_activities_markup.*')->where(["agent_activities_markup.web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id])
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("agent_activities_markup.id","DESC")->paginate(40);


        }
    }
}