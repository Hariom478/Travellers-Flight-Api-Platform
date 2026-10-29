<?php

namespace App\Modules\Accounts\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class WhiteLabelMakePaymentModel extends Model
{
    protected $table = 'wl_agent_make_payment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function payment_history($web_partner_id)
    {


        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $paymentDatasubquery = $db->table('web_partner_payment_transaction')->select('booking_ref_no,transaction_id,order_id')->where(["web_partner_payment_transaction.service"=>"Make_Payment","payment_source"=>'Wl_b2b']);
        $paymentDatasubquery = $paymentDatasubquery->getCompiledSelect();
        return  $this->select('wl_agent_make_payment.payment_date,wl_agent_make_payment.amount,web_partner_payment_transaction.transaction_id as pg_transaction_id,web_partner_payment_transaction.order_id as pg_order_id,wl_agent_make_payment.status,wl_agent_make_payment.payment_mode, wl_agent_make_payment.file_name,
        wl_agent_make_payment.id,wl_agent_make_payment.admin_remark,wl_agent_make_payment.bank_transaction_id,agent.id as agent_id,wl_agent_make_payment.sup_staff_id,
        agent.company_name,agent_users.first_name,agent_users.last_name,agent.pan_number')
            ->join("($subquery) agent", 'agent.id = wl_agent_make_payment.wl_agent_id','left')
            ->join("agent_users", 'agent_users.id = wl_agent_make_payment.user_id','left')
            ->join("($paymentDatasubquery) web_partner_payment_transaction", 'web_partner_payment_transaction.booking_ref_no = wl_agent_make_payment.id ','left')
            ->where(["wl_agent_make_payment.web_partner_id"=>$web_partner_id])->orderBy("id", "DESC")->paginate(40);
    }

    public function payment_history_detail__($id, $web_partner_id)
    {

        return $this->select('id as ref_no,amount,cheque_draft_utr_number,payment_date,bank,bank_branch,payment_mode,bank_transaction_id,agent_staff_id,status,company_bank_name,file_name,
        company_branch_name,company_account_holder_name,company_account_no,company_ifsc_code,remark,company_swift_code,admin_remark,created')
            ->where('id', $id)->where('web_partner_id', $web_partner_id)->get()->getRowArray();
    }


    public function payment_history_detail($id,$web_partner_id)
    {
        $db = \Config\Database::connect();
        $paymentDatasubquery = $db->table('web_partner_payment_transaction')->select('booking_ref_no,transaction_id,order_id')->where(["web_partner_payment_transaction.service"=>"Make_Payment","payment_source"=>'Wl_b2b']);
        $paymentDatasubquery = $paymentDatasubquery->getCompiledSelect();
        return $this->select('wl_agent_make_payment.id as ref_id,wl_agent_make_payment.amount,wl_agent_make_payment.cheque_draft_utr_number,web_partner_payment_transaction.transaction_id as pg_transaction_id,web_partner_payment_transaction.order_id as pg_order_id,wl_agent_make_payment.payment_date,bank,bank_branch,wl_agent_make_payment.payment_mode,bank_transaction_id,agent_staff_id,status,company_bank_name,
        wl_agent_make_payment.file_name,wl_agent_make_payment.sup_staff_id,
        company_branch_name,company_account_holder_name,company_account_no,company_ifsc_code,remark,company_swift_code,admin_remark,wl_agent_make_payment.created,wl_agent_make_payment.web_partner_id')->join("($paymentDatasubquery) web_partner_payment_transaction", 'web_partner_payment_transaction.booking_ref_no = wl_agent_make_payment.id','left')->where(['wl_agent_make_payment.id'=>$id,"web_partner_id"=>$web_partner_id])->get()->getRowArray();
    }


    function search_data($web_partner_id, $data)
    {
        $dataWhere = array('amount'=>'wl_agent_make_payment.amount','bank_transaction_id'=>'wl_agent_make_payment.bank_transaction_id','status'=>'wl_agent_make_payment.status');
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];


                $db = \Config\Database::connect();
                $subquery = $db->table('agent')->select('id,company_name,pan_number');
                $subquery = $subquery->getCompiledSelect();
                $paymentDatasubquery = $db->table('web_partner_payment_transaction')->select('booking_ref_no,transaction_id,order_id')->where(["web_partner_payment_transaction.service"=>"Make_Payment","payment_source"=>'Wl_b2b']);
                $paymentDatasubquery = $paymentDatasubquery->getCompiledSelect();
                return  $this->select('wl_agent_make_payment.payment_date,wl_agent_make_payment.amount,web_partner_payment_transaction.transaction_id as pg_transaction_id,web_partner_payment_transaction.order_id as pg_order_id,wl_agent_make_payment.status,wl_agent_make_payment.payment_mode, wl_agent_make_payment.file_name,
                wl_agent_make_payment.id,wl_agent_make_payment.admin_remark,wl_agent_make_payment.bank_transaction_id,agent.id as agent_id,wl_agent_make_payment.sup_staff_id,
                agent.company_name,agent_users.first_name,agent_users.last_name,agent.pan_number')
                    ->join("($subquery) agent", 'agent.id = wl_agent_make_payment.wl_agent_id','left')
                    ->join("agent_users", 'agent_users.id = wl_agent_make_payment.user_id','left')
                    ->join("($paymentDatasubquery) web_partner_payment_transaction", 'web_partner_payment_transaction.booking_ref_no = wl_agent_make_payment.id ','left')
                    ->where(["wl_agent_make_payment.web_partner_id"=>$web_partner_id])->orderBy("id", "DESC")->where($array)->paginate(40);

            } else {
                $array = ['created >=' => $from_date, 'created <=' => $to_date];


                $db = \Config\Database::connect();
                $subquery = $db->table('agent')->select('id,company_name,pan_number');
                $subquery = $subquery->getCompiledSelect();
                $paymentDatasubquery = $db->table('web_partner_payment_transaction')->select('booking_ref_no,transaction_id,order_id')->where(["web_partner_payment_transaction.service"=>"Make_Payment","payment_source"=>'Wl_b2b']);
                $paymentDatasubquery = $paymentDatasubquery->getCompiledSelect();
                return  $this->select('wl_agent_make_payment.payment_date,wl_agent_make_payment.amount,web_partner_payment_transaction.transaction_id as pg_transaction_id,web_partner_payment_transaction.order_id as pg_order_id,wl_agent_make_payment.status,wl_agent_make_payment.payment_mode, wl_agent_make_payment.file_name,
                wl_agent_make_payment.id,wl_agent_make_payment.admin_remark,wl_agent_make_payment.bank_transaction_id,agent.id as agent_id,wl_agent_make_payment.sup_staff_id,
                agent.company_name,agent_users.first_name,agent_users.last_name,agent.pan_number')
                    ->join("($subquery) agent", 'agent.id = wl_agent_make_payment.wl_agent_id','left')
                    ->join("agent_users", 'agent_users.id = wl_agent_make_payment.user_id','left')
                    ->join("($paymentDatasubquery) web_partner_payment_transaction", 'web_partner_payment_transaction.booking_ref_no = wl_agent_make_payment.id ','left')
                    ->where(["wl_agent_make_payment.web_partner_id"=>$web_partner_id])->orderBy("id", "DESC")->where($array)->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
            }
        } else {

            $db = \Config\Database::connect();
            $subquery = $db->table('agent')->select('id,company_name,pan_number');
            $subquery = $subquery->getCompiledSelect();
            $paymentDatasubquery = $db->table('web_partner_payment_transaction')->select('booking_ref_no,transaction_id,order_id')->where(["web_partner_payment_transaction.service"=>"Make_Payment","payment_source"=>'Wl_b2b']);
            $paymentDatasubquery = $paymentDatasubquery->getCompiledSelect();
            return  $this->select('wl_agent_make_payment.payment_date,wl_agent_make_payment.amount,web_partner_payment_transaction.transaction_id as pg_transaction_id,web_partner_payment_transaction.order_id as pg_order_id,wl_agent_make_payment.status,wl_agent_make_payment.payment_mode, wl_agent_make_payment.file_name,
            wl_agent_make_payment.id,wl_agent_make_payment.admin_remark,wl_agent_make_payment.bank_transaction_id,agent.id as agent_id,wl_agent_make_payment.sup_staff_id,
            agent.company_name,agent_users.first_name,agent_users.last_name,agent.pan_number')
                ->join("($subquery) agent", 'agent.id = wl_agent_make_payment.wl_agent_id','left')
                ->join("agent_users", 'agent_users.id = wl_agent_make_payment.user_id','left')
                ->join("($paymentDatasubquery) web_partner_payment_transaction", 'web_partner_payment_transaction.booking_ref_no = wl_agent_make_payment.id ','left')
                ->where(["wl_agent_make_payment.web_partner_id"=>$web_partner_id])->orderBy("id", "DESC")->like($dataWhere[trim($data['key'])], trim($data['value']))->paginate(40);
        }
    }


    public function payment_status_change($ids, $data)
    {
        return $this->select('*')->where('id', $ids)->set($data)->update();
    }

    public function available_balance($web_partner_id,$agent_id){
        return   $this->db->table("agent_account_log")->select('balance')->where('wl_agent_id', $agent_id)->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
}


