<?php

namespace App\Modules\Supplier\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SuppliersModel extends Model
{
    protected $table = 'suppliers';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function suppliers_list($web_partner_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('supplier_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('supplier_account_log')->orderBy('id', 'DESC')->groupBy('supplier_id');
        });
        $subquery = $builder->getCompiledSelect();

        $supplier_users_subquery = $db->table('supplier_users')->select('id,supplier_id,login_email,mobile_isd,mobile_no')->where('primary_user', 1);
        $supplier_users_subquery = $supplier_users_subquery->getCompiledSelect();

        $Execute_Query = $this->select('suppliers.id,suppliers.company_id,suppliers.company_name,suppliers.pan_name,suppliers.pan_number,supplier_account_log.balance,supplier_users.login_email,supplier_users.mobile_isd,supplier_users.mobile_no,suppliers.state, suppliers.city,suppliers.country,suppliers.pan_card,suppliers.status,suppliers.created,suppliers.modified,supplier_class.class_name')
            ->join('supplier_class', 'supplier_class.id = suppliers.supplier_class_id', 'left')
            ->join("($supplier_users_subquery) supplier_users", 'supplier_users.supplier_id = suppliers.id', 'left')
            ->join("($subquery) supplier_account_log", 'supplier_account_log.supplier_id = suppliers.id', 'left')
            ->orderBy('suppliers.id', 'DESC')->where('suppliers.web_partner_id', $web_partner_id)
            ->paginate(40);

        return $Execute_Query;
    }

    function search_data($data, $web_partner_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('supplier_account_log');
        $builder->whereIn('id', function (BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('supplier_account_log')->orderBy('id', 'DESC')->groupBy('supplier_id');
        });
        $subquery = $builder->getCompiledSelect();
        $supplier_users_subquery = $db->table('supplier_users')->select('id,supplier_id,login_email,mobile_isd,mobile_no')->where('primary_user', 1);
        $supplier_users_subquery = $supplier_users_subquery->getCompiledSelect();
        $dataWhere = ['first_name' => 'supplier_users.first_name', 'login_email' => 'supplier_users.login_email', 'mobile_number' => 'supplier_users.mobile_no', 'class_name' => 'supplier_class.class_name', 'company_name' => 'suppliers.company_name','status' => 'suppliers.status'];
        $Execute_Query = $this->select('suppliers.id,suppliers.company_id,suppliers.company_name,suppliers.pan_name,suppliers.pan_number,supplier_account_log.balance,supplier_users.login_email,supplier_users.mobile_isd,supplier_users.mobile_no,suppliers.state, suppliers.city,suppliers.country,suppliers.pan_card,suppliers.status,suppliers.created,suppliers.modified,supplier_class.class_name')
            ->join('supplier_class', 'supplier_class.id = suppliers.supplier_class_id', 'left')
            ->join("($supplier_users_subquery) supplier_users", 'supplier_users.supplier_id = suppliers.id', 'left')
            ->join("($subquery) supplier_account_log", 'supplier_account_log.supplier_id = suppliers.id', 'left')->where('suppliers.web_partner_id', $web_partner_id)->orderBy('suppliers.id', 'DESC');

        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['suppliers.created >=' => $from_date, 'suppliers.created <=' => $to_date];
                return $Execute_Query->where($array)->paginate(40);
            } else {
                $array = ['suppliers.created >=' => $from_date, 'suppliers.created <=' => $to_date];
                return $Execute_Query->where($array)->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
            }
        } else {
            return $Execute_Query->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
        }
    }

    public function supplier_list_details($web_partner_id, $id)
    {
        return $this->select('*')->where(["web_partner_id" => $web_partner_id, "id" => $id])->get()->getRowArray();
    }

    public function delete_image($id, $web_partner_id)
    {
        return $this->select('pan_card')->where(["id" => $id, "web_partner_id" => $web_partner_id])->get()->getRowArray();
    }

    public function remove_supplier($ids, $web_partner_id)
    {
        return $this->select('*')->where(["id" => $ids, "web_partner_id" => $web_partner_id])->delete();
    }

    public function supplier_status_change($ids, $data, $web_partner_id)
    {
        $ids = explode(",", $ids);
        return $this->select('*')->whereIn('id', $ids)->where(["web_partner_id" => $web_partner_id])->set($data)->update();
    }

    public function supplier_details_page($id, $web_partner_id)
    {
        $db = \Config\Database::connect();
        $supplier_users_subquery = $db->table('supplier_users')->select('id,supplier_id,login_email,mobile_isd,mobile_no,title,first_name,last_name')->where('primary_user', 1);
        $supplier_users_subquery = $supplier_users_subquery->getCompiledSelect();
        return $this->select('suppliers.*,supplier_class.class_name,supplier_users.*')
            ->join("($supplier_users_subquery) supplier_users", 'supplier_users.supplier_id = suppliers.id', 'left')
            ->join('supplier_class', 'supplier_class.id = suppliers.supplier_class_id', 'left')
            ->where(['suppliers.id' => $id, "suppliers.web_partner_id" => $web_partner_id])->get()->getRowArray();
    }


    function checkRegisterSupplier($email, $web_partner_id, $supplier_id = null)
    {
        $builder = $this->db->table("suppliers")->select("suppliers.id as supplier_id")->join('supplier_users', 'supplier_users.supplier_id = suppliers.id')->where(["login_email" => $email, "suppliers.web_partner_id" => $web_partner_id, "primary_user" => 1]);
        if ($supplier_id != null) {
            $builder->where(["suppliers.id!=" => $supplier_id]);
        }
        return $builder->get()->getNumRows();
    }

    
        public function supplier_export($web_partner_id, $data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['suppliers.created >=' => $from_date, 'suppliers.created <=' => $to_date];
           
            $db = \Config\Database::connect();
            $builder = $db->table('supplier_account_log');
            $builder->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('supplier_account_log')->orderBy('id', 'DESC')->groupBy('supplier_id');
            });
            $subquery = $builder->getCompiledSelect();
            return $this->select('suppliers.*,supplier_users.*,supplier_class.class_name,supplier_account_log.balance')
                ->join('supplier_class', 'supplier_class.id = suppliers.supplier_class_id')
                ->join('supplier_users', 'supplier_users.supplier_id = suppliers.id')
                ->join("($subquery) supplier_account_log", 'supplier_account_log.supplier_id = suppliers.id')
                ->orderBy('suppliers.id', 'DESC')
                ->where('suppliers.web_partner_id', $web_partner_id)->where($array)
                ->get()->getResultArray();
        } else {
            $db = \Config\Database::connect();
            $builder = $db->table('supplier_account_log');
            $builder->whereIn('id', function (BaseBuilder $builder) {
                return $builder->select('MAX(id)', false)->from('supplier_account_log')->orderBy('id', 'DESC')->groupBy('supplier_id');
            });
            $subquery = $builder->getCompiledSelect();
            return $this->select('suppliers.*,supplier_users.*,supplier_class.class_name,supplier_account_log.balance')
                ->join('supplier_class', 'supplier_class.id = suppliers.supplier_class_id')
                ->join('supplier_users', 'supplier_users.supplier_id = suppliers.id')
                ->join("($subquery) supplier_account_log", 'supplier_account_log.supplier_id = suppliers.id')
                ->orderBy('suppliers.id', 'DESC')
                ->where('suppliers.web_partner_id', $web_partner_id)
                ->get()->getResultArray();
        }
    }

       
}