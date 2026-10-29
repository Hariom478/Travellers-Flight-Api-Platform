<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class CreditNotesModel extends Model
{
    protected $table = 'agent_account_log';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function credit_notes($wl_agent_id, $web_partner_id)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];
        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_holder_name,pan_number');
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
        agent_account_log.created,agent_account_log.booking_ref_no,agent.company_name,agent.pan_holder_name,agent.pan_number")->where($array)
            ->where(['agent_account_log.action_type' => 'refund', 'agent_account_log.web_partner_id' => $web_partner_id])
            ->where(['agent_account_log.wl_agent_id' => $wl_agent_id])
            ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')
            ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
            ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
            ->orderBy("agent_account_log.id", "DESC")->paginate(40);

        return $data;
    }


    function credit_notes_search($wl_agent_id, $web_partner_id, $data)
    {


        $db = \Config\Database::connect();
        $subquery = $db->table('agent')->select('id,company_name,pan_holder_name,pan_number');
        $subquery = $subquery->getCompiledSelect();
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];

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
        agent_account_log.created,agent_account_log.booking_ref_no,agent.company_name,agent.pan_holder_name,agent.pan_number")
                    ->where(['agent_account_log.action_type' => 'refund', 'agent_account_log.web_partner_id' => $web_partner_id])
                    ->where(['agent_account_log.wl_agent_id' => $wl_agent_id])
                    ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                    ->where($array)
                    ->orderBy("agent_account_log.id", "DESC")->paginate(40);

                return $data;

            } else {
                $array = ['agent_account_log.created >=' => $from_date, 'agent_account_log.created <=' => $to_date];

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
        agent_account_log.created,agent_account_log.booking_ref_no,agent.company_name,agent.pan_holder_name,agent.pan_number")
                    ->where(['agent_account_log.action_type' => 'refund', 'agent_account_log.web_partner_id' => $web_partner_id])
                    ->where(['agent_account_log.wl_agent_id' => $wl_agent_id])
                    ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')
                    ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                    ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                    ->where($array)->like(trim($data['key']), trim($data['value']))
                    ->orderBy("agent_account_log.id", "DESC")->paginate(40);

                return $data;
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
        agent_account_log.created,agent_account_log.booking_ref_no,agent.company_name,agent.pan_holder_name,agent.pan_number")
                ->where(['agent_account_log.action_type' => 'refund', 'agent_account_log.web_partner_id' => $web_partner_id])
                ->where(['agent_account_log.wl_agent_id' => $wl_agent_id])
                ->join("($subquery) agent", 'agent.id = agent_account_log.wl_agent_id', 'Left')
                ->join('flight_booking_list', "flight_booking_list.id = agent_account_log.booking_ref_no", 'Left')
                ->join('flight_booking_travelers', "flight_booking_travelers.refund_account_id = agent_account_log.id", 'Right')
                ->like(trim($data['key']), trim($data['value']))
                ->orderBy("agent_account_log.id", "DESC")->paginate(40);

            return $data;
        }
    }



}


