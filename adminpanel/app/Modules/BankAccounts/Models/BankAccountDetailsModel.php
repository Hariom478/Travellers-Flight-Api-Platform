<?php

namespace App\Modules\BankAccounts\Models;

use CodeIgniter\Model;

class BankAccountDetailsModel extends Model
{
    protected $table = 'web_partner_bank_account_detail';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function bank_details($web_partner_id,$id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->where("id",$id)->get()->getRowArray();
    }
   
    public function bank_list($web_partner_id)
    {
        return  $this->select('*')->where('web_partner_id',$web_partner_id)->orderBy('id', 'DESC')->paginate(40);
    }

    function search_data($web_partner_id,$data)
    {
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id',$web_partner_id)->where($array)->paginate(40);
            } else {
                $array=['created >='=> $from_date,'created <='=> $to_date];
                return  $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id',$web_partner_id)->where($array)->like(trim($data['key']),trim($data['value']))->paginate(40);
            }
        } else {
            return  $this->select('*')->orderBy('id', 'DESC')->where('web_partner_id',$web_partner_id)->like(trim($data['key']),trim($data['value']))->paginate(40);
        }
    }

    public function remove_bank($web_partner_id,$id)
    {
        return  $this->select('*')->where('web_partner_id', $web_partner_id)->whereIn("id",$id)->delete();
    }
}


