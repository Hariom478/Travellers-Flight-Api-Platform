<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SupplierAccountModel extends Model
{
    protected $table = '';
    protected $primarykey = '';
    protected $protectFields = false;

    function search_suppliers($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('supplier_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('supplier_account_log')->orderBy('id', 'DESC')->groupBy('supplier_id');
        });
        $subquery = $builder->getCompiledSelect();
        $supplier_users_subquery = $db->table('supplier_users')->select('id,supplier_id,login_email,mobile_isd,mobile_no')->where('primary_user',1);
        $supplier_users_subquery = $supplier_users_subquery->getCompiledSelect();
            return $this->db->table('suppliers')->select('suppliers.id,suppliers.company_id,suppliers.company_name,suppliers.pan_name,suppliers.pan_number,supplier_account_log.balance,
                 suppliers.pan_card, suppliers.status,suppliers.created,
                suppliers.modified,supplier_users.login_email,supplier_users.mobile_isd,supplier_users.mobile_no')
                ->join("($supplier_users_subquery) supplier_users", 'supplier_users.supplier_id = suppliers.id', 'left')
                ->join("($subquery) supplier_account_log", 'supplier_account_log.supplier_id = suppliers.id','left')
                ->where('suppliers.id', trim($data['key-value']))->orderBy('suppliers.id', 'DESC')
                ->get()->getRowArray();
        
    }
    function supplierInfo($data)
    {
        $db = \Config\Database::connect();
        $supplier_users_subquery = $db->table('supplier_users')->select('id,supplier_id,login_email,mobile_isd,mobile_no')->where('primary_user',1);
        $supplier_users_subquery = $supplier_users_subquery->getCompiledSelect();
            return $this->db->table('suppliers')->select('suppliers.id,suppliers.company_id,suppliers.company_name,suppliers.pan_name,suppliers.pan_number')
                ->join("($supplier_users_subquery) supplier_users", 'supplier_users.supplier_id = suppliers.id', 'left')
                ->like(trim('suppliers.company_id'), trim($data),"after")->orderBy('suppliers.id', 'DESC')
                ->orLike(trim('suppliers.company_name'), trim($data),"after")->orderBy('suppliers.id', 'DESC')
               /*  ->orLike(trim('suppliers.pan_number'), trim($data))->orderBy('suppliers.id', 'DESC') */
                ->get()->getResultArray();
        
    }
    public function servicesId($servicename,$booking_ref_number)
    {
        $tableName =  $servicename."_booking_list";
        return $this->db->table($tableName)->select('id')->where("booking_ref_number", $booking_ref_number)->get()->getRowArray();
    }
    

}


