<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightTicketUploadModel extends Model
{
    protected $table = '';
    protected $primarykey = '';
    protected $protectFields = false;
    function getDataRowType($tableName,$whereCondition,$field)
    {
      $builder =  $this->db->table($tableName)->select($field);
      if($whereCondition)
      {
      $builder->where($whereCondition);
      }
     return $builder->get()->getRowArray();
    }
  
    public function agent_user_available_balance($tableName,$key,$id,$web_partner_id){
        return $this->db->table($tableName)->select('balance')->where($key,$id)->where('web_partner_id',$web_partner_id)->orderBy('id','desc')->limit(1)->get()->getRowArray();
    }
    function insertData($tableName,$data){
        $this->db->table($tableName)->insert($data);
       return $this->db->insertID();
    }
    function getData($tableName,$where,$singalRecord = 1,$whereApply  =  1,$selectedColumnValue  = null){
        $builder   = $this->db->table($tableName);

        if($selectedColumnValue!=null)
        {
            $builder->select($selectedColumnValue);
        }
    if($whereApply){
        $builder->where($where);
    }
    if($singalRecord)
    {
       return   $builder->get()->getRowArray();
    }
    else
    {
        return   $builder->get()->getResultArray();
    }
    }
    public function selected_airport_detail($airportCodeArray)
    {
    
       $airportDetail =   $this->db->table('flight_airports')->select('code,name,city_name,city_code,country_name,country_code')->whereIn("code",$airportCodeArray)->get()->getResultArray();
       return $airportDetail;
    }
    function updateData($tableName,$whereClause,$data){
        $this->db->table($tableName)->where($whereClause)->update($data);
       return $this->db->insertID();
    }
    public function web_partner_available_balance($web_partner_id)
    {
        return  $this->db->table('web_partner_account_log')->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id','DESC')->limit(1)->get()->getRowArray();
    }
    function insertBatchData($tableName, $insertData)
    {
        $this->db->table($tableName)->insertBatch($insertData);
    }
    function super_admin_booking_pre_fix_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('pre_fix');
        return $builder->get()->getRowArray();
    }
    public function flight_booking_detail($booking_refrence_number)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select("web_partner.company_name,web_partner.company_id,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name,flight_booking_list.*,CONCAT(super_admin_users.first_name,' ',super_admin_users.last_name) as assign_user_name,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth,'ticket_id',flight_booking_travelers.ticket_id,'fare',flight_booking_travelers.fare) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->join('web_partner', "flight_booking_list.web_partner_id = web_partner.id", 'left');
        $builder->join("admin_users", "admin_users.id=flight_booking_list.agent_staff_id", 'left');
        $builder->join('super_admin_users', "super_admin_users.id = flight_booking_list.assign_user", 'Left');
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $builder = $this->db->table('flight_booking_list');
            $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.invoice_number,web_partner_account_log.booking_refund_confirmation_number,web_partner_account_log.balance,web_partner_account_log.booking_confirmation_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=flight_booking_list.id");
            $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, "web_partner_account_log.service" => "flight"]);
            $query['paymentInfo'] = $builder->get()->getResultArray();
            $WebPartnerBuilder = $this->db->table('web_partner');
            $WebPartnerBuilder->select("web_partner.company_name,web_partner.company_id,admin_users.login_email,admin_users.mobile_no,admin_users.first_name,admin_users.last_name")
                ->join("flight_booking_list", "flight_booking_list.web_partner_id=web_partner.id",'left')
                ->join("admin_users", "admin_users.web_partner_id=web_partner.id", 'left');
            $WebPartnerBuilder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number, "admin_users.primary_user" => "1"]);
            $query['WebPartnerInfo'] = $WebPartnerBuilder->get()->getRowArray();
            $bookingNoteBuilder = $this->db->table('web_partner_booking_notes');
            $bookingNoteBuilder->select("web_partner_booking_notes.comment,web_partner_booking_notes.created,super_admin_users.first_name,super_admin_users.last_name,web_partner_booking_notes.add_by")
                ->join("super_admin_users", "super_admin_users.id=web_partner_booking_notes.sup_staff_id");
            $bookingNoteBuilder->where(['web_partner_booking_notes.booking_ref_no' => $query['id'], "web_partner_booking_notes.service_type" => "flight","web_partner_booking_notes.add_by" => "superadmin"]);
            $query['BookingNotes'] = $bookingNoteBuilder->get()->getResultArray();
        }
        return $query;
    }
    public function flight_booking_detail_with_selected_pax($booking_refrence_number,$pax_info)
{
    $builder = $this->db->table('flight_booking_list');
    $builder->select("flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'passport_issue_date',flight_booking_travelers.passport_issue_date,'ticket_id',flight_booking_travelers.ticket_id,'pan_number',flight_booking_travelers.pan_number,'passport_nationality',flight_booking_travelers.nationality) separator ','), ']') as travelersInfo");
    $builder->where(['flight_booking_list.booking_ref_number' => $booking_refrence_number]);
    $builder->whereIn('flight_booking_travelers.id', $pax_info);
    $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
    $builder->groupBy('flight_booking_list.id');
    $query = $builder->get()->getRowArray();
    return $query;
}
function getAirportCodeTimeZoneIn($AirportCodes)
  {
      $builder =  $this->db->table("timezone");
      $builder->select("airportcode,timezone");
      $builder->whereIn("airportcode",$AirportCodes);
      $data  =  $builder->get()->getResultArray();
      return array_column($data,"timezone","airportcode");
  }

}


