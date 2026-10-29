<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayThemesModel extends Model
{
    protected $table = 'holiday_themes';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function holiday_themes_list_select_ajax($data,$web_partner_id){
        return  $this->select('id,theme_name as value')->like("theme_name",$data)->where(["web_partner_id"=>$web_partner_id])->get()->getResultArray();

    }

}


