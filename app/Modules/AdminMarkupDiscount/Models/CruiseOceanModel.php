<?php

namespace App\Modules\AdminMarkupDiscount\Models;

use CodeIgniter\Model;

class CruiseOceanModel extends Model
{
    protected $table = 'cruise_ocean';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cruise_ocean_list($web_partner_id)
    {
        return  $this->select('id,ocean_name,status,created,modified')
            ->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    }

    public function search_data($data,$web_partner_id){
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if($data['key'] == 'date-range'){
                $array = ['cruise_ocean.created >=' => $from_date, 'cruise_ocean.created <=' => $to_date];
                return  $this->select('id,ocean_name,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->where($array)
                ->orderBy('id', 'DESC')->paginate(40);
            }else{
                $array = ['cruise_ocean.created >=' => $from_date, 'cruise_ocean.created <=' => $to_date];
                return  $this->select('id,ocean_name,status,created,modified')->where(['web_partner_id'=>$web_partner_id])->where($array)
                ->like(trim($data['key']), trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
            }
        }else{
            return  $this->select('id,ocean_name,status,created,modified')->where(['web_partner_id'=>$web_partner_id])
            ->like(trim($data['key']), trim($data['value']))->orderBy('id', 'DESC')->paginate(40);
        }
    }

	 public function remove_cruise_ocean($id,$web_partner_id)
    {
        return  $this->select('*')->whereIn("id",$id)->where(['web_partner_id'=>$web_partner_id])->delete();
    }
	 public function cruise_ocean_status_change($ids, $data,$web_partner_id)
    {
        $ids= explode(",", $ids);
        return $this->select('status')->whereIn('id', $ids)->where(['web_partner_id'=>$web_partner_id])->set($data)->update();
    }
	  public function cruise_ocean_details($id,$web_partner_id)
    {
        return  $this->select('id,ocean_name,status,')->where("id",$id)->where(['web_partner_id'=>$web_partner_id])->get()->getRowArray();
    }

    public function cruise_ocean_select($web_partner_id)
    {
        return  $this->select('id,ocean_name')->where(['web_partner_id'=>$web_partner_id])->get()->getResultArray();
    }
    
}


