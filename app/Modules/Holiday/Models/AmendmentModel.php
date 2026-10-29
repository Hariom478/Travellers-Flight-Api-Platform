<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class AmendmentModel extends Model
{
    protected $table = 'holiday_amendment';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function amendment_list($web_partner_id,$wl_agent_id)
    {
        return $this->select(

        'holiday_amendment.*,holiday_booking_list.id as Booking_id,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.travel_date,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
 
        '

        )


            ->where(['holiday_booking_list.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id])
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'left')->orderBy("holiday_amendment.id", "DESC")->paginate(40);
    }

    function search_data($web_partner_id,$wl_agent_id,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            if ($data['key'] == 'date-range') {
                $array = ['holiday_amendment.created >=' => $from_date, 'holiday_amendment.created <=' => $to_date,'holiday_amendment.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id];


                return $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.super_admin_fare_break_up,
        holiday_booking_list.amendment_charges,holiday_booking_list.booking_channel,holiday_booking_list.tts_search_token,holiday_booking_list.travel_date,holiday_booking_list.day_nights,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
      
        ')
                    ->where($array)
                    ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'Left')
                    ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'Left')
                    ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'Left')->orderBy("holiday_amendment.id", "DESC")->paginate(40);

            } else {
                $array = ['holiday_amendment.created >=' => $from_date, 'holiday_amendment.created <=' => $to_date,'holiday_amendment.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id];
                $dataArray = ['booking_ref_number'=>'holiday_booking_list.booking_ref_number','amendment_type'=>'holiday_amendment.amendment_type','booking_status'=>'holiday_booking_list.booking_status','amendment_status'=>'holiday_amendment.amendment_status','id'=>'holiday_amendment.id'];

                return $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.super_admin_fare_break_up,
        holiday_booking_list.amendment_charges,holiday_booking_list.booking_channel,holiday_booking_list.tts_search_token,holiday_booking_list.travel_date,holiday_booking_list.day_nights,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
      
        ')
                    ->where($array)->like($dataArray[trim($data['key'])], trim($data['value']))->where(['holiday_amendment.web_partner_id' => $web_partner_id])
                    ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'Left')
                    ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'Left')
                    ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'Left')->orderBy("holiday_amendment.id", "DESC")->paginate(40);


            }
        } else {
            $dataArray = ['booking_ref_number'=>'holiday_booking_list.booking_ref_number','amendment_type'=>'holiday_amendment.amendment_type','booking_status'=>'holiday_booking_list.booking_status','amendment_status'=>'holiday_amendment.amendment_status','id'=>'holiday_amendment.id'];
            return $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.super_admin_fare_break_up,
        holiday_booking_list.amendment_charges,holiday_booking_list.booking_channel,holiday_booking_list.tts_search_token,holiday_booking_list.travel_date,holiday_booking_list.day_nights,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
      
        ')
                ->where(['holiday_amendment.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id]) ->like($dataArray[trim($data['key'])], trim($data['value']))
                ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'Left')
                ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'Left')
                ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'Left')->orderBy("holiday_amendment.id", "DESC")->paginate(40);
        }
    }

    public function amendment_detail($web_partner_id,$wl_agent_id,$amendment_id)
    {
        $builder = $this->db->table('holiday_amendment');
        $builder = $this->select("holiday_amendment.*,holiday_booking_list.id as Booking_id,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.booking_ref_number,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,holiday_booking_list.agent_fare_break_up,
        holiday_booking_list.amendment_charges,holiday_booking_list.booking_channel,holiday_booking_list.tts_search_token,holiday_booking_list.travel_date,holiday_booking_list.day_nights,
        concat('[', group_concat(JSON_OBJECT('id', holiday_booking_travelers.id,'email_id',holiday_booking_travelers.email_id,'title',holiday_booking_travelers.title,'first_name',holiday_booking_travelers.first_name,'last_name',holiday_booking_travelers.last_name,'pax_type',holiday_booking_travelers.pax_type,'gendar',holiday_booking_travelers.gendar,'mobile_number',holiday_booking_travelers.mobile_number,'age',holiday_booking_travelers.age,'child_need_bed',holiday_booking_travelers.child_need_bed,'pan_number',holiday_booking_travelers.pan_number,'lead_pax',holiday_booking_travelers.lead_pax,'passport_number',holiday_booking_travelers.passport_number) separator ','), ']') as travelersInfo,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name,' ',agent_users.last_name) as staff_name,
      
        ")
             ->where(['holiday_amendment.id' => $amendment_id,'holiday_amendment.web_partner_id' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id])
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'Left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'Left')
            ->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = holiday_booking_list.id")
            ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'Left');
        $query = $builder->get()->getRowArray();

        if ($query) {
            $builder = $this->db->table('agent_account_log');
            $builder->select("id,acc_ref_number,debit,credit,invoice_number,balance,service,remark,service_log,transaction_id,payment_mode,transaction_type,action_type,extra_param,created");
            $builder->where(['booking_ref_no' =>  $query['Booking_id'], 'web_partner_id' => $web_partner_id,'service'=>'holiday']);
            $query['paymentInfo'] = $builder->get()->getResultArray();
        }
        return $query;
    }
    public function refund_list($web_partner_id,$wl_agent_id)
    {


        $array = ['holiday_amendment.web_partner_id >=' => $web_partner_id,'holiday_booking_list.wl_agent_id' => $wl_agent_id];

        $builder =  $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.booking_ref_number,holiday_booking_list.travel_date,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
        ')
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'left')
            ->whereIn("refund_status", array('Open', 'Close'))->where($array);

        return $builder->orderBy("holiday_amendment.id", "DESC")->paginate(40);


    }

    function search_refund_list($web_partner_id,$wl_agent_id,$data)
    {
        if (isset($data['from_date']) && isset($data['to_date']) && $data['from_date'] != "" && $data['to_date'] != "") {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $Datearray = ['holiday_amendment.modified >=' => $from_date, 'holiday_amendment.modified <=' => $to_date];

        }

            $array['holiday_amendment.web_partner_id'] = $web_partner_id;
        $array['holiday_amendment.wl_agent_id'] = $wl_agent_id;



        $arrayValue = array("booking_ref_number" => "booking_ref_number", "id" => "holiday_amendment.id", "refund_status" => "holiday_amendment.refund_status", 'pnr' => 'flight_booking_list.pnr');
        $builder =  $this->select('holiday_amendment.*,holiday_booking_list.id as Booking_id,holiday_booking_list.booking_ref_number,holiday_booking_list.travel_date,
       
       holiday_booking_list.package_details,holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.payment_status,
        holiday_booking_list.booking_status,holiday_booking_list.created as booking_created,holiday_booking_list.total_price,holiday_booking_list.web_partner_fare_break_up,
       
        web_partner.company_name,web_partner.company_id,CONCAT(agent_users.first_name," ",agent_users.last_name) as staff_name,
        ')
            ->join("agent_users", "agent_users.id=holiday_amendment.agent_staff_id", 'left')
            ->join("holiday_booking_list", "holiday_booking_list.id=holiday_amendment.booking_ref_no", 'left')
            ->join('web_partner', "holiday_amendment.web_partner_id = web_partner.id", 'left');
        $builder->whereIn("refund_status", array('Open', 'Close'));

            $builder->where($array);

        if (!empty($Datearray)) {
            $builder->groupStart();
            $builder->where($Datearray);
            $Datearray = ['holiday_amendment.refund_date >=' => $from_date, 'holiday_amendment.refund_date <=' => $to_date];
            $builder->orWhere($Datearray);
            $builder->groupEnd();
        }
        if (isset($arrayValue[trim($data['key'])])) {
            $builder->like($arrayValue[trim($data['key'])], trim($data['value']));
        }
        return $builder->groupBy("holiday_amendment.id")->orderBy("holiday_amendment.id", "DESC")->paginate(40);
    }
}


