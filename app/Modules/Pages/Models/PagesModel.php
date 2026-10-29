<?php

namespace App\Modules\Pages\Models;

use CodeIgniter\Model;

class PagesModel extends Model
{
    protected $table = 'pages';
    protected $primarykey = 'id';
    protected $protectFields = false;




    public function get_page_details($slug,$web_partner_id)
    {
        return  $this->select('*')->where(["slug_url"=>$slug,"web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }
}


