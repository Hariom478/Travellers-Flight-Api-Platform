<?php

namespace App\Modules\AgentAccount\Models;

use CodeIgniter\Model;

class AgentAccountLogModel extends Model
{
    protected $table = 'agent_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;



    public function account_logs_detail($web_partner_id,$agent_id)
    {
        return  $this->select('id,web_partner_id,acc_ref_number,action_type,service,service_log,credit,debit,balance,remark,created,booking_ref_no,payment_mode,transaction_type,action_type')
            ->where('web_partner_id', $web_partner_id)->where('wl_agent_id',$agent_id)->orderBy("id","DESC")->paginate(40);
    }


    public function account_logs($wl_agent_id, $web_partner_id, $searchdata)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_holder_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');
        $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        if ((isset($searchdata['from_date']) && $searchdata['from_date'] != "") && (isset($searchdata['to_date']) && $searchdata['to_date'] != "")) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        }
        if (isset($searchdata['search-text']) && $searchdata['key-value'] != "") {
            $wl_agent_id = $searchdata['key-value'];
        }

        if (isset($searchdata['key']) && $searchdata['key'] != '') {
            if ($searchdata['key'] == 'invoice_number') {
                $array['agent_account_log.invoice_number'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'service') {
                $array['agent_account_log.service'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'credit') {
                $array['agent_account_log.transaction_type'] = 'credit';
                $array['agent_account_log.credit'] = $searchdata['value'];
            }
            if ($searchdata['key'] == 'balance') {
                $array['agent_account_log.balance'] = 'balance';
            }

            if ($searchdata['key'] == 'debit') {
                $array['agent_account_log.transaction_type'] = 'debit';
                $array['agent_account_log.debit'] = $searchdata['value'];
            }
        }
        $array['agent_account_log.web_partner_id'] = $web_partner_id;
        $array['agent_account_log.wl_agent_id'] = $wl_agent_id;
        return $this->select('agent_account_log.*,agent.company_name,agent.pan_holder_name as pan_name,agent.pan_number,
        CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_staff_name,
            ')
            ->join('agent_users', 'agent_users.id = agent_account_log.wl_agent_staff_id', 'Left')->where($array)
            ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')->where($array)
            ->orderBy("agent_account_log.id", "DESC")->paginate(40);
    }

    public function debit_account_logs($wl_agent_id, $web_partner_id, $searchdata)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_holder_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');
        $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        if ((isset($searchdata['from_date']) && $searchdata['from_date'] != "") && (isset($searchdata['to_date']) && $searchdata['to_date'] != "")) {
            $from_date = strtotime(date('Y-m-d', strtotime($searchdata['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($searchdata['to_date'])) . '23:59');
            $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        }
        if (isset($searchdata['search-text']) && $searchdata['key-value'] != "") {
            $wl_agent_id = $searchdata['key-value'];
        }

        if (isset($searchdata['key']) && $searchdata['key'] != '') {
          

            if ($searchdata['key'] == 'service') {
                $array['agent_account_log.service'] = $searchdata['value'];
            }

            if ($searchdata['key'] == 'balance') {
                $array['agent_account_log.balance'] = 'balance';
            }

            if ($searchdata['key'] == 'debit') {
                $array['agent_account_log.debit'] = $searchdata['value'];
            }
        }
        $array['agent_account_log.web_partner_id'] = $web_partner_id;
        $array['agent_account_log.wl_agent_id'] = $wl_agent_id;
        $array['agent_account_log.transaction_type'] = 'debit';
        return $this->select('agent_account_log.*,agent.company_name,agent.pan_holder_name as pan_name,agent.pan_number,
        CONCAT(agent_users.first_name," ",agent_users.last_name) as agent_staff_name,
            ')
            ->join('agent_users', 'agent_users.id = agent_account_log.wl_agent_staff_id', 'Left')->where($array)
            ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')->where($array)
            ->orderBy("agent_account_log.id", "DESC")->paginate(40);
    }

    public function available_balance($web_partner_id,$agent_id){
        return  $this->select('balance')->where('web_partner_id', $web_partner_id)->where('wl_agent_id',$agent_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }


    public function view_remark_detail($id, $web_partner_id)
    {
        $array['agent_account_log.web_partner_id'] = $web_partner_id;
        $array['agent_account_log.id'] = $id;
        return $this->select('agent_account_log.wl_agent_id,agent_account_log.id,agent_account_log.transaction_type,agent_account_log.web_partner_id,agent_account_log.remark,agent_account_log.extra_param,agent_account_log.payment_mode,agent_account_log.transaction_id,agent_account_log.service_log,agent_account_log.action_type,agent_account_log.service,
               CONCAT(admin_users.first_name," ",admin_users.last_name) as web_partner_staff_name,
            ')
            ->join('admin_users', 'admin_users.id = agent_account_log.user_id', 'Left')
            ->where($array)->get()->getRowArray();
    }




    public function credit_notes($web_partner_id,$agent_id)
    {
        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();

        $data = $this->select("agent_account_log.id as AccountLogId,agent_account_log.web_partner_id,agent_account_log.acc_ref_number,agent_account_log.payment_mode,
        agent_account_log.action_type,agent_account_log.service,agent_account_log.service_log,agent_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        agent_account_log.credit,agent_account_log.debit,agent_account_log.balance,agent_account_log.remark,
        agent_account_log.created,agent_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")

            ->where('agent_account_log.wl_agent_id',$agent_id)->where(['agent_account_log.action_type' => 'refund','agent_account_log.web_partner_id' =>$web_partner_id])
            ->join("($subquery) web_partner", 'web_partner.id = agent_account_log.web_partner_id', 'Left')
            ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
            ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
            ->orderBy("agent_account_log.id", "DESC")->paginate(40);

        return $data ;
    }


    function credit_notes_search($web_partner_id,$agent_id,$data)
    {

        $db = \Config\Database::connect();
        $subquery = $db->table('web_partner')->select('id,company_name,pan_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        if($data['from_date'] && $data['to_date'])
        {
            $from_date=strtotime(date('Y-m-d',strtotime($data['from_date'])).'00:00');
            $to_date=strtotime(date('Y-m-d',strtotime($data['to_date'])).'23:59');
            if($data['key']=='date-range')
            {
                $array=['agent_account_log.created >='=> $from_date,'agent_account_log.created <='=> $to_date];

                $data = $this->select("agent_account_log.id as AccountLogId,agent_account_log.web_partner_id,agent_account_log.acc_ref_number,agent_account_log.payment_mode,
        agent_account_log.action_type,agent_account_log.service,agent_account_log.service_log,agent_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        agent_account_log.credit,agent_account_log.debit,agent_account_log.balance,agent_account_log.remark,
        agent_account_log.created,agent_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['agent_account_log.action_type' => 'refund','agent_account_log.web_partner_id' =>$web_partner_id])->where('agent_account_log.wl_agent_id',$agent_id)
                    ->join("($subquery) web_partner", 'web_partner.id = agent_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                    ->where($array)
                    ->orderBy("agent_account_log.id", "DESC")->paginate(40);

                return $data ;

            } else {
                $array=['agent_account_log.created >='=> $from_date,'agent_account_log.created <='=> $to_date];

                $data = $this->select("agent_account_log.id as AccountLogId,agent_account_log.web_partner_id,agent_account_log.acc_ref_number,agent_account_log.payment_mode,
        agent_account_log.action_type,agent_account_log.service,agent_account_log.service_log,agent_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*,flight_booking_travelers.id as TravelerId,
        agent_account_log.credit,agent_account_log.debit,agent_account_log.balance,agent_account_log.remark,
        agent_account_log.created,agent_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                    ->where(['agent_account_log.action_type' => 'refund','agent_account_log.web_partner_id' =>$web_partner_id])->where('agent_account_log.wl_agent_id',$agent_id)
                    ->join("($subquery) web_partner", 'web_partner.id = agent_account_log.web_partner_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                    ->where($array)->like(trim($data['key']),trim($data['value']))
                    ->orderBy("agent_account_log.id", "DESC")->paginate(40);

                return $data ;
            }
        } else {

            $data = $this->select("agent_account_log.id as AccountLogId,agent_account_log.web_partner_id,agent_account_log.acc_ref_number,agent_account_log.payment_mode,
        agent_account_log.action_type,agent_account_log.service,agent_account_log.service_log,agent_account_log.transaction_type,
       flight_booking_list.id as flightBookingId,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,
        flight_booking_list.destination,flight_booking_list.airline_code,flight_booking_list.pnr,
        flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,
        
        flight_booking_travelers.*, flight_booking_travelers.id as TravelerId,
        agent_account_log.credit,agent_account_log.debit,agent_account_log.balance,agent_account_log.remark,
        agent_account_log.created,agent_account_log.booking_ref_no,web_partner.company_name,web_partner.pan_name,web_partner.pan_number")
                ->where(['agent_account_log.action_type' => 'refund','agent_account_log.web_partner_id' =>$web_partner_id])->where('agent_account_log.wl_agent_id',$agent_id)
                ->join("($subquery) web_partner", 'web_partner.id = agent_account_log.web_partner_id', 'Left')
                ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                ->like(trim($data['key']),trim($data['value']))
                ->orderBy("agent_account_log.id", "DESC")->paginate(40);

            return $data ;
        }
    }
}


