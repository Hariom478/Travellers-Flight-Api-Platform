<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class ApiSupplierModel extends Model
{
    protected $table = 'api_supplier';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function supplier_list($service="")
    {
       $builder =   $this->select('id,supplier_name')->where('status','active');
       if($service!="") {
        $builder->where($service,'active');
       }
       else{
        $builder->where('hotel','active');
       }
        return  $builder->get()->getResultArray();
    }

}


