<?php

namespace App\Modules\Airservice\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class RestModel extends Model
{
    function getAirportCodeTimeZone($AirportCode)
    {
      $timezone = "Asia/Kolkata";
        $builder =  $this->db->table("timezone");
        $builder->select("timezone");
        $builder->where("airportcode",$AirportCode);
        $data  =  $builder->get()->getRowArray();
        if($data){
          $timezone =  $data['timezone'];
        }
        return  $timezone;
    }
    function get_api_supplier($name = null)
    {
        $builder = $this->db->table('api_supplier');
        $builder->select('supplier_name');
        if ($name) {
            $builder->where('supplier_name', $name);
            $builder->where('flight', 'active');
            $builder->where('status', 'active');
            return $builder->get()->getRowArray();
        } else {
            $builder->where('flight', 'active');
            $builder->where('status', 'active');
            $array = $builder->get()->getResultArray();
            return array_column($array, 'supplier_name', 'supplier_name');
        }
    }

    function insert_tts_flight_logs($web_partner_id, $tts_search_token, $request, $response, $service, $selected_index = null, $tts_index_response = null)
    {
        $this->GetTimeZone = app_timezone();
        $insertlog = array(
            'web_partner_id' => $web_partner_id,
            'tts_search_token' => $tts_search_token,
            'request' => json_encode($request),
            'response' => json_encode($response),
            'selected_index' => $selected_index,
            'tts_index_response' => json_encode($tts_index_response),
            'service' => $service,
            'created' => strtotime(Time::now($this->GetTimeZone))
        );
        $db = \Config\Database::connect('api');
        $db->table('tts_flight_log')->insert($insertlog);
    }

    function verify_tts_search_token($array_condition, $selcolumn = null)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_flight_log');
        $builder->select("tts_search_token,tts_index_response,created,$selcolumn");
        $builder->where($array_condition);
        $builder->orderBy("id", "Desc");
        return $builder->get()->getRowArray();
    }

    function get_search_request($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_flight_log');
        $builder->select("request");
        $builder->where($array_condition);
        $builder->orderBy("id", "Desc");
        return $builder->get()->getRowArray();
    }

    function get_fare_rule($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_flight_log');
        $builder->select("response");
        $builder->where($array_condition);
        $builder->orderBy("id", "Desc");
        return $builder->get()->getRowArray();
    }

    function book_record_exists($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('tts_flight_log');
        $builder->select('tts_search_token');
        $builder->where($array_condition);
        return $builder->get()->getRowArray();
    }

    function get_flight_offline($airline_code, $from_airport_code, $to_airport_code)
    {
        $builder = $this->db->table('flight_offline');
        $builder->select('airline_code,is_hold,is_offline');
        $builder->where('status', 'active');
        $builder->where('airline_code', $airline_code);
        $builder->orWhere('airline_code', "ANY");
        $builder->groupStart();
        $builder->where('from_airport_code', $from_airport_code);
        $builder->orWhere('from_airport_code', 'ANY');
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('to_airport_code', $to_airport_code);
        $builder->orWhere('to_airport_code', 'ANY');
        $builder->groupEnd();
        $builder->orderBy("id", "Desc");
        return $builder->get()->getRowArray();
    }

    function get_auth_user_account_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function get_country_code(array $airport_code_array)
    {
        return $this->db->table('flight_airports')->select('country_code')->whereIn('code', $airport_code_array)->get()->getResultArray();
    }
    
    
    function super_admin_markup($web_partner_class_id, $input, $supplier)
    {
        $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");
        $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");
        $firstAirSegment = reset($input['AirSegments']);
        $lastAirSegment = end($input['AirSegments']);
        $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);
        $builder = $this->db->table('super_admin_flight_markup');
        $builder->select('airline_code,airline_name,from_airport_code,to_airport_code,is_domestic,journey_type,travel_date_from,travel_date_to,cabin_class,markup_type,value,max_limit,display_markup,supplier');
        $builder->where('web_partner_class_id', $web_partner_class_id);
        $builder->where('is_domestic', $input['IsDomestic']);
        $builder->where('status', 'active');
        $builder->where('journey_type', $journeytype[$input['JourneyType']]);
        $builder->groupStart();
        $builder->where('cabin_class', $cabinClass[$input['CabinClass']]);
        $builder->orWhere('cabin_class', "ANY");
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('travel_date_from<=', $departureDate);
        $builder->where('travel_date_to>=', $departureDate);
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('find_in_set("' . $firstAirSegment['Origin'] . '", from_airport_code) <> 0');
        $builder->orWhere('from_airport_code', "ANY");
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('find_in_set("' . $firstAirSegment['Destination'] . '", to_airport_code) <> 0');
        $builder->orWhere('to_airport_code', "ANY");
        $builder->groupEnd();
        return $result = $builder->get()->getResultArray();

    }

    function super_admin_discount($web_partner_class_id, $input, $supplier)
    {
        $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");
        $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");
        $firstAirSegment = reset($input['AirSegments']);
        $lastAirSegment = end($input['AirSegments']);
        $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);
        $builder = $this->db->table('super_admin_flight_discount');
        $builder->select('airline_code,airline_name,from_airport_code,to_airport_code,is_domestic,journey_type,travel_date_from,travel_date_to,cabin_class,discount_type,value,extra_discount,max_limit,supplier');
        $builder->where('web_partner_class_id', $web_partner_class_id);
        $builder->where('is_domestic', $input['IsDomestic']);
        $builder->where('status', 'active');
        $builder->where('journey_type', $journeytype[$input['JourneyType']]);
        $builder->groupStart();
        $builder->where('cabin_class', $cabinClass[$input['CabinClass']]);
        $builder->orWhere('cabin_class', "ANY");
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('travel_date_from<=', $departureDate);
        $builder->where('travel_date_to>=', $departureDate);
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('find_in_set("' . $firstAirSegment['Origin'] . '", from_airport_code) <> 0');
        $builder->orWhere('from_airport_code', "ANY");
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('find_in_set("' . $firstAirSegment['Destination'] . '", to_airport_code) <> 0');
        $builder->orWhere('to_airport_code', "ANY");
        $builder->groupEnd();
        return $builder->get()->getResultArray();
    }

    function super_admin_gst_state_code()
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select('gst_state_code');
        return $builder->get()->getRowArray();
    }

    function insertBatchData($table, $data)
    {
        $db = \Config\Database::connect('api');
        $db->reconnect();
        $db->table($table)->insertBatch($data);
    }

    function insertBatchDataPrimayDB($table, $data)
    {
        $this->db->reconnect();
        $this->db->table($table)->insertBatch($data);
    }

    function get_airline_name($airline_code)
    {
        return $this->db->table('flight_airline_code')->select('airline_name')->where('airline_code', $airline_code)->get()->getRowArray();
    }

    function get_airline_info($airline_code)
    {
        return $this->db->table('flight_airline_code')->select('airline_name,islcc,airline_contact_no')->where('airline_code', $airline_code)->get()->getRowArray();
    }

    function get_airport($airport_code)
    {
        return $this->db->table('flight_airports')->select('code,name,city_code,city_name,country_name,country_code')->where('code', $airport_code)->get()->getRowArray();
    }

    function getbookingDetailByToken($tts_search_token, $resultIndex, $fields)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->where("tts_search_token", $tts_search_token);
        $builder->where("resultIndex", $resultIndex);
        $builder->select($fields);
        return $builder->get()->getRowArray();
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

    function verify_booking_detail($array_condition)
    {
        $BookingDetail = $this->db->table('flight_booking_list')->select('*')->where($array_condition)->get()->getRowArray();
        if ($BookingDetail) {
            $PassengerDetails = $this->db->table('flight_booking_travelers')->select('*')->where('flight_booking_id', $BookingDetail['id'])->get()->getResultArray();
            $BookingDetail['PassengerDetails'] = $PassengerDetails;
            $AccountDetails = $this->db->table('web_partner_account_log')->select('acc_ref_number,debit,created')->where(['booking_ref_no' => $BookingDetail['id'], 'service' => 'flight'])->get()->getRowArray();
            $BookingDetail['AccountDetails'] = $AccountDetails;
        }
        return $BookingDetail;
    }

    function GetpartnerInfo($tableName, $Where, $fieldName)
    {
        return $this->db->table($tableName)->select($fieldName)->where($Where)->get()->getRowArray();

    }

    function getBookingData($booking_id)
    {
        $builder = $this->db->table("flight_booking_list");
        $builder->select("flight_booking_list.*,flight_booking_list.tts_search_token,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'validating_airline',flight_booking_travelers.validating_airline,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'agent_fare',flight_booking_travelers.agent_fare,'customer_fare',flight_booking_travelers.customer_fare) separator ','), ']') as travelersInfo");
        $builder->where(['flight_booking_list.id' => $booking_id]);
        $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
        $builder->groupBy('flight_booking_list.id');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    function super_admin_detail($fieldName)
    {
        $builder = $this->db->table('super_admin_website_setting');
        $builder->select($fieldName);
        return $builder->get()->getRowArray();
    }

    function getWebpartnerBookingAccountInfo($fieldName, $where)
    {
        $builder = $this->db->table('agent_account_log');
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }

    function GetAmendmentInfo($tableName, $Where, $fieldName)
    {
        $checkamendmentStatus =1;
        $paxids  =  $Where['pax_id'];
        foreach($paxids as $paxId) {
        $builder  =  $this->db->table($tableName);
        $builder->select($fieldName);
        $builder->where(["booking_ref_no"=>$Where['booking_ref_no']]);
        $builder->where(["amendment_status!="=>"rejected"]);
        $builder->where('find_in_set("' . $paxId . '", pax_id) <> 0');
        $data   =  $builder->get()->getRowArray();
        if(!empty($data))
        {
            $checkamendmentStatus =0;
            break;
        }
        }
        return $checkamendmentStatus;
    }

    function get_booking_info($array_condition)
    {
        return $this->db->table('flight_booking_list')->select('id,web_partner_id,tts_search_token')->where($array_condition)->get()->getRowArray();
    }

    function getTravellerData($traveller_id)
    {
        return $this->db->table('flight_booking_travelers')->select('*')->where('id', $traveller_id)->get()->getRowArray();
    }

    function getBookingAccountInfo($tableName,$fieldName,$where){
        $builder = $this->db->table($tableName);
        $builder->select($fieldName);
        $builder->where($where);
        return $builder->get()->getRowArray();
    }

    function getaccountLogCreditNote($web_partner_id,$refund_account_id)
    {
        return $this->db->table('agent_account_log')->select('id,web_partner_id,acc_ref_number,created')->where('web_partner_id', $web_partner_id)->where('id', $refund_account_id)->get()->getRowArray();
    }

    function get_supplier_logs($array_condition)
    {
        $db = \Config\Database::connect('api');
        $builder = $db->table('common_flight_log');
        $builder->select('request,response,service');
        $builder->where($array_condition);
        return $builder->get()->getResultArray();
    }

    function get_detail_by_refno($refno)
    {
        $builder = $this->db->table('flight_booking_list');
        $builder->select('booking_ref_number,tts_search_token,api_supplier');
        $builder->where("booking_ref_number", $refno);
        return $builder->get()->getRowArray();
    }
    function get_include_exclude_supplier_airline($input)
    {
        $air_type  =  "Domestic";
        if(!$input['IsDomestic']){
        $air_type  =  "International";
        }
        $journeytype = array("1" => "Oneway", "2" => "Roundtrip", "3" => "Multicity");
        $builder = $this->db->table('api_supplier_flight_mgt');
        $builder->select('allowed_airline,excluded_airline,fare_type,trim(supplier_name) as api_supplier')->join("api_supplier","api_supplier.id=api_supplier_flight_mgt.api_supplier_id");
        $builder->where('api_supplier_flight_mgt.status', 'active');
        $builder->groupStart();
        $builder->where('find_in_set("' . $journeytype[$input['JourneyType']] . '", search_type) <> 0');
        $builder->orWhere('search_type', "All");
        $builder->groupEnd();
        $builder->groupStart();
        $builder->where('find_in_set("' .  $air_type . '", air_type) <> 0');
        $builder->orWhere('air_type', "All");
        $builder->groupEnd();
        $builder->orderBy("api_supplier_flight_mgt.id","Desc");
        return $builder->get()->getResultArray();
    }
    function get_super_admin_flight_deal($web_partner_class_id,$input)
    {
        $trip_type  =  "Domestic";
        if(!$input['IsDomestic']){
        $trip_type  =  "International";
        }
        $builder = $this->db->table('super_admin_flight_deal_sheet');
        $builder->select('airline_code,basic,yq,basic_iata,yq_iata,booking_class_included,cabin_class,booking_class_excluded,sector_included,sector_excluded,travel_start_date,travel_end_date,booking_start_date,booking_end_date,supplier');
        $builder->where('super_admin_flight_deal_sheet.web_partner_class_id', $web_partner_class_id);
        $builder->groupStart();
        $builder->where('find_in_set("' . $trip_type . '", trip_type) <> 0');
        $builder->orWhere('trip_type', "All");
        $builder->groupEnd();
        $builder->orderBy("super_admin_flight_deal_sheet.id","Desc");
        return $builder->get()->getResultArray();
    }

    function getwhiteLabelSetting($web_partner_id)
    {
        $builder  =  $this->db->table('whitelabel_webpartner_setting');
        $builder->select('domain_name,is_direct_website')->where('web_partner_id',$web_partner_id);
       return $result   = $builder->get()->getRowArray();
    }
}
