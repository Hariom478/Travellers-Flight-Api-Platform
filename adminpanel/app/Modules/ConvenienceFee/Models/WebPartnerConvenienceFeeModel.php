<?php

namespace App\Modules\ConvenienceFee\Models;

use CodeIgniter\Model;

class WebPartnerConvenienceFeeModel extends Model
{
    protected $table = 'web_partner_convenience_fee';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function convenience_list($web_partner_id)
    {
        $main_query = $this->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->paginate(40);

        return $main_query;
    }

    

    public function convenience_details($id,$web_partner_id)
    {
        return $this->select('*')->where('web_partner_id',$web_partner_id)->where("id", $id)->get()->getRowArray();
    }


    function search_data($data,$web_partner_id)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['web_partner_id'=>$web_partner_id,'created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where($array)->paginate(40);
            } else {
                $array = ['web_partner_id'=>$web_partner_id,'created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('*')->orderBy('id', 'DESC')->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('*')->orderBy('id', 'DESC')->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    public function remove_convenience($id,$web_partner_id)
    {
        return $this->select('*')->where('web_partner_id',$web_partner_id)->whereIn("id", $id)->delete();
    }
}


