<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightAmendmentModel extends Model
{
    protected $table = 'flight_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function flight_amendment_list($web_partner_id,$wl_agent_id)
    {
        return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name')
            ->where(['flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id])
            ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);
    }

    function search_data($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date,'flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id];


                return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                    flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                    flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                    flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                    web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name')
                    ->where($array)
                    ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
                    ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);

            } else {
                $array = ['flight_amendment.created >=' => $from_date, 'flight_amendment.created <=' => $to_date,'flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id];

                return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                    flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                    flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                    flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                    web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name')
                    ->where($array)->like(trim($data['key']), trim($data['value']))->where(['flight_amendment.web_partner_id' => $web_partner_id])
                    ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
                    ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                    ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);


            }
        } else {

            return $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
                flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
                flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
                flight_booking_list.total_price,flight_booking_list.agent_staff_id,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
                web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name')
                ->where(['flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id]) ->like(trim($data['key']), trim($data['value']))
                ->join("agent_users", "agent_users.id=flight_amendment.wl_agent_staff_id", 'left')
                ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
                ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')->orderBy("flight_amendment.id", "DESC")->paginate(40);
        }
    }

    public function flight_refund_list($web_partner_id,$wl_agent_id,$bookingType = 'all', $source = null)
    {
        $tomorrow_timestamp = strtotime(date('Y-m-d'));
        $tomorrow_timestamp = date("Y-m-d", $tomorrow_timestamp);
        $from_date = strtotime(date('Y-m-d', strtotime($tomorrow_timestamp)) . '00:00');

        $to_date = strtotime(date('Y-m-d', strtotime(date('Y-m-d'))) . '23:59');

        $array = ['flight_amendment.refund_date >=' => $from_date, 'flight_amendment.refund_date <=' => $to_date];

        $builder = $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
         CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')->where(['flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id])
            ->join('super_admin_users', 'super_admin_users.id = flight_amendment.sup_staff_id','Left')
            ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left')
            ->whereIn("refund_status", array('Open', 'Close'));
        if ($source != 'dashboard') {
            //$builder->where($array);
        }

        return $builder->orderBy("flight_amendment.id", "DESC")->paginate(40);


    }


    function search_flight_refund_list($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['key']){
            unset($data['from_date'],$data['to_date']);
        }
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            //$Datearray = ['flight_amendment.modified >=' => $from_date, 'flight_amendment.modified <=' => $to_date];

            $Datearray = ['flight_amendment.refund_date >=' => $from_date, 'flight_amendment.refund_date <=' => $to_date];

        }
        if (isset($data['tts_web_partner_info']) && $data['tts_web_partner_info'] != "") {
            $array['flight_amendment.web_partner_id'] = $data['tts_web_partner_info'];
        }


        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "flight_amendment.id", "refund_status" => "flight_amendment.refund_status", 'pnr' => 'flight_booking_list.pnr');

        $builder = $this->select('flight_amendment.*,flight_booking_list.id as flightBokkingid,flight_booking_list.booking_ref_number,flight_booking_list.fare_type,flight_booking_list.airline_remark,flight_booking_list.api_supplier,
        flight_booking_list.supplier_booking_id,flight_booking_list.journey_type,flight_booking_list.origin,flight_booking_list.destination,flight_booking_list.departure_date,
        flight_booking_list.is_domestic,flight_booking_list.is_refundable,flight_booking_list.validating_airline_code,flight_booking_list.payment_status,flight_booking_list.booking_status,
        flight_booking_list.total_price,flight_booking_list.pnr,flight_booking_list.last_ticket_date,flight_booking_list.booking_channel,
        web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name," ",admin_users.last_name) as staff_name,
        CONCAT(super_admin_users.first_name," ",super_admin_users.last_name) as super_admin_staff_name,
        ')->where(['flight_amendment.web_partner_id' => $web_partner_id,'flight_amendment.wl_agent_id'=>$wl_agent_id])
            ->join('super_admin_users', 'super_admin_users.id = flight_amendment.sup_staff_id','Left')
            ->join("admin_users", "admin_users.id=flight_amendment.agent_staff_id", 'left')
            ->join("flight_booking_list", "flight_booking_list.id=flight_amendment.booking_ref_no", 'left')
            ->join('web_partner', "flight_amendment.web_partner_id = web_partner.id", 'left');
        $builder->whereIn("refund_status", array('Open', 'Close'));
        if (!empty($array)) {
            $builder->where($array);
        }
        if (!empty($Datearray)) {

            $builder->where($Datearray);

        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("flight_amendment.id")->orderBy("flight_amendment.refund_date", "DESC")->paginate(40);
    }
}


