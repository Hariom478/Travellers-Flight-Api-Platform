<?php

namespace App\Modules\Currency\Models;

use CodeIgniter\Model;

class CurrencyModel extends Model
{
    protected $table = 'currency';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function currency_list($web_partner_id)
    {
    return $this->select('id,base_currency,web_partner_id,convert_currency,convertion_rate,base_currency_name,base_currency_symbol,
    convert_currency_name,convert_currency_symbol,created,modified')->where(['web_partner_id'=>$web_partner_id])->paginate(40);
    }

    public function multi_currency($web_partner_id)
    {
    return $this->db->table('whitelabel_webpartner_setting')->select('id,multi_currency')->where(['web_partner_id'=>$web_partner_id])->where(['multi_currency'=>'active'])->get()->getResultArray();

    }

    public function currency_details($id,$web_partner_id)
    {
        return $this->select('id,base_currency,convert_currency,convertion_rate,base_currency_name,
        base_currency_symbol,convert_currency_name,convert_currency_symbol,created')->where(["web_partner_id"=> $web_partner_id,"id"=>$id])->get()->getRowArray();
    }

    function search_data($data, $web_partner_id)
    {
        
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,base_currency,convert_currency,convertion_rate,base_currency_name,created,modified,convert_currency_symbol,base_currency_symbol,convert_currency_name,base_currency_name')->where(['web_partner_id' => $web_partner_id])->where($array)->paginate(40);
            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];
                return $this->select('id,base_currency,convert_currency,convertion_rate,base_currency_name,created,modified,convert_currency_symbol,base_currency_symbol,convert_currency_name,base_currency_name')->where(['web_partner_id' => $web_partner_id])->where($array)->like(trim($data['key']), trim($data['value']))->paginate(40);
            }
        } else {
            return $this->select('id,base_currency,convert_currency,convertion_rate,base_currency_name,created,modified,convert_currency_symbol,base_currency_symbol,convert_currency_name,
            base_currency_name')->where(['web_partner_id' => $web_partner_id])->like(trim($data['key']), trim($data['value']))->paginate(40);
        }
    }

    

    public function remove_currency($id,$web_partner_id)
    {
        return $this->select('*')->where(["id"=> $hotel_id,'web_partner_id'=>$web_partner_id])->delete();
    }

    

    function get_currency()
    {
        return $this->db->table('countries')->select('id,currency,currency_name,currency_symbol')->get()->getResultArray();
    }


   


}


