<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayCitiesModel extends Model
{
    protected $table = 'cities';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cities_list($data)
    {
        return  $this->select('id,name')->Like('name',$data)->limit(20)->get()->getResultArray();;
    }
}


