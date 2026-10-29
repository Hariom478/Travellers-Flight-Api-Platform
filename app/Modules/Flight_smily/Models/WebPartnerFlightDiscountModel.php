<?php

namespace App\Modules\Flight\Models;

use CodeIgniter\Model;

class WebPartnerFlightDiscountModel extends Model
{
    protected $table = 'web_partner_flight_discount';
    protected $primarykey = 'id';
    protected $protectFields = false;



    function getFlightdiscount($web_partner_id, $wl_agent_class,$input){
      
        if($input['IsDomestic']){
            $input['IsDomestic']=1;
        }
        else{
            $input['IsDomestic']= 0;
        }
        $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");
        $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");
        $firstAirSegment = reset($input['AirSegments']);
        $lastAirSegment = end($input['AirSegments']);
          $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);
        $builder = $this->db->table('web_partner_flight_discount');
        $builder->select('airline_code,airline_name,from_airport_code,to_airport_code,is_domestic,booking_class,faretype,journey_type,travel_date_from,travel_date_to,cabin_class,discount_type,value,extra_discount,max_limit');
        $builder->where('status', 'active');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('discount_for', "B2B");
        $builder->where('find_in_set("' . $input['IsDomestic'] . '", is_domestic) <> 0');
        $builder->where('find_in_set("' . $journeytype[$input['JourneyType']] . '", journey_type) <> 0');
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
        $builder->where('find_in_set("' . $wl_agent_class . '", agent_class) <> 0');
        return $builder->get()->getResultArray();
    }
}


