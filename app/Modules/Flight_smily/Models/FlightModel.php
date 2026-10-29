<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class FlightModel extends Model
{


    public function selected_airport_detail($airportCodeArray)
    {
        if (empty($airportCodeArray)) {
            $airportCodeArray[0] = "DEL";
            $airportCodeArray[1] = "BOM";
        }
        $airportDetail = $this->db->table('flight_airports')->select('code,name')->whereIn("code", $airportCodeArray)->get()->getResultArray();
        $airportDetail = array_column($airportDetail, 'name', 'code');
        return $airportDetail;
    }

    // public function offers_list()
    // {
    //     return $this->db->table('super_admin_offers')->select('id,title,description,service,url,image')
    //         ->where('status', 'active')->where('service', 'flight')->limit(9)->orderBy('id', 'DESC')->get()->getResultArray();
    // }

    public function offers_list($web_partner_id)
    {
        return $this->db->table('web_partner_offers')
            ->select('id,title,description,service,url,image')
            ->where('status', 'active')
            ->where('service', 'flight')
            ->groupStart() 
            ->where(["web_partner_offers.web_partner_id"=>$web_partner_id]) 
            ->orWhere(["web_partner_offers.web_partner_id"=>0]) 
            ->groupEnd() 
            ->limit(9)->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();    
    }

    function get_dial_code()
    {
        return $this->db->table('countries')->select('phonecode,name,iso2')->get()->getResultArray();
    }

    function admin_notification()
    {
        return $this->db->table('slider')->where('status', 'active')->where('image_category', 'Admin-Notification')->get()->getResultArray();
    }

    function getApiLogsData($whereClause, $gettingColumn)
    {
        $apiDb = \Config\Database::connect('api');
        $builder = $apiDb->table("tts_flight_log");
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }

    function updateApiLogsData($whereClause, $updateData)
    {
        $apiDb = \Config\Database::connect('api');
        $builder = $apiDb->table("tts_flight_log");
        $builder->where($whereClause)->set($updateData)->update();
    }

    function getData($tableName, $whereClause, $gettingColumn)
    {
        $builder = $this->db->table($tableName);
        $builder->select($gettingColumn);
        $builder->orderBy("id", "DESC");
        return $builder->where($whereClause)->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
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

    function updateUserData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function getBookingData($bookingid, $web_partner_id)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select("flight_booking_list.id,flight_booking_list.is_lcc,flight_booking_list.is_gst_mandatory,flight_booking_list.is_gst_allowed,flight_booking_list.booking_ref_number,flight_booking_list.resultIndex,flight_booking_list.trip_indicator,flight_booking_list.book_request,flight_booking_list.booking_status,flight_booking_list.gst_info,flight_booking_list.tts_search_token,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'passport_issue_date',flight_booking_travelers.passport_issue_date,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'nationality',flight_booking_travelers.nationality) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.id' => $bookingid, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    function getBookingConfirmationData($bookingid, $web_partner_id, $userId,$userType)
    {

        $array = ['flight_booking_list.web_partner_id' => $web_partner_id, 'flight_booking_list.wl_agent_id' => $userId,'flight_booking_list.booking_source'=>$userType,'flight_booking_list.id' => $bookingid ];


        $builder = $this->db->table("flight_booking_list");
        $builder->select("flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'validating_airline',flight_booking_travelers.validating_airline,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal) separator ','), ']') as travelersInfo");
        $builder->where($array);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        if ($query) {
            $paymentgatewayDetailbuilder = $this->db->table('flight_booking_list');
            $paymentgatewayDetailbuilder->select("super_admin_payment_transaction.payment_status,super_admin_payment_transaction.order_id,super_admin_payment_transaction.amount,super_admin_payment_transaction.payment_response")
                ->join("super_admin_payment_transaction", "super_admin_payment_transaction.booking_ref_no=flight_booking_list.id");
            $paymentgatewayDetailbuilder->where(['flight_booking_list.id' => $bookingid, 'flight_booking_list.web_partner_id' => $web_partner_id, "super_admin_payment_transaction.service" => "flight"]);
            $query['paymentgatewayDetail'] = $paymentgatewayDetailbuilder->get()->getRowArray();
        }
        return $query;
    }

    function getBookingWithVariableFieldNameData($bookingid, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.id' => $bookingid, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    function getBookingWithBookingRefNumberWithVariableFieldNameData($bookingRefNumber, $web_partner_id, $fieldName)
    {

        $builder = $this->db->table("flight_booking_list");
        $builder->select($fieldName);
        $builder->where(['flight_booking_list.booking_ref_number' => $bookingRefNumber, 'flight_booking_list.web_partner_id' => $web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }
}
