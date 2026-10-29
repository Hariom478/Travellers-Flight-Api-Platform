<?php

namespace App\Modules\Query\Models;

use CodeIgniter\Model;

class QueryModel extends Model
{
    protected $table = 'contact_us';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function query_list($web_partner_id)
    {
        return  $this->select('*')->where(['web_partner_id'=>$web_partner_id])->orderBy("id","DESC")->paginate(40);
    } 

    public function remove_query($ids,$web_partner_id)
    {

        return  $this->select('*')->where("id",$ids)->where(['web_partner_id'=>$web_partner_id])->delete();

    } 
}


