<?php

namespace App\Modules\CRSFlight\Models;

use CodeIgniter\Model;

class CRSFlightModel extends Model
{
  function getDataRowType($tableName, $whereCondition, $field)
  {
    $builder =  $this->db->table($tableName)->select($field);
    if ($whereCondition) {
      $builder->where($whereCondition);
    }
    return $builder->get()->getRowArray();
  }
  function GetSectorData($input)
  {
    $TotalpaxExcludeInfant  =  $input['Adult'] + $input['Child'];
    $CabinClass =  get_crs_cabinclass($input['CabinClass']);
    $AirSegments  =  $input['AirSegments'];
    $DirectFlight =   $input['DirectFlight'];
    $JourneyFareInfo  =  array();
    if (!$input['IsDomestic'] && $input['JourneyType'] == 2) {
      $AirSegment  =  $AirSegments[0];
      $JourneyDate  =   explode("T", $AirSegment['PreferredTime'])[0];
      $JourneyReturnDate  =   explode("T", $AirSegments[1]['PreferredTime'])[0];
      $JourneyDate  =  date("Y-m-d", strtotime($JourneyDate));
      $JourneyReturnDate  =  date("Y-m-d", strtotime($JourneyReturnDate));
      $JourneyOrigin  = $AirSegment['Origin'];
      $JourneyDestination  = $AirSegment['Destination'];
      $sectorInfoBuilder  =  $this->db->table("flight_crs_fare_list")->select("suppliers.status as SupplierStatus,flight_crs_fare_list.id As SectorId,flight_crs_fare_list.origin As Origin,flight_crs_fare_list.destination As  Destination,flight_crs_fare_list.trip_type As TripType,flight_crs_fare_list.onward_segment_detail As SectorSegmentDetail,flight_crs_fare_list.disable_before_departure As DisableBeforeDeparture,flight_crs_details.id As FarePnrId,flight_crs_details.supplier_id As FarePnrsupplierId,flight_crs_details.date As FarePnrDate,flight_crs_details.date_return As FareReturnPnrDate,flight_crs_details.pnr As FarePnr,flight_crs_details.available_seats As AvailableSeats,flight_crs_details.booked_seats As BookedSeats,flight_crs_details.segment_info As SegmentInfo,flight_crs_details.booking_class As BookingClass,flight_crs_details.cabin_class As CabinClass,flight_crs_details.adult_base_fare As AdultBaseFare,flight_crs_details.adult_tax As AdultTax,flight_crs_details.adult_gst As AdultGst,flight_crs_details.child_base_fare As ChildBaseFare,flight_crs_details.child_tax As ChildTax,flight_crs_details.child_gst As ChildGst,flight_crs_details.infant_base_fare As InfantBaseFare,flight_crs_details.infant_tax As InfantTax,flight_crs_details.infant_gst As InfantGst");
      $sectorInfoBuilder->join("flight_crs_details", "flight_crs_fare_list.id=flight_crs_details.privatefare_id");
      $sectorInfoBuilder->join("suppliers", "flight_crs_details.supplier_id=suppliers.id", "Left");
      /*  $sectorInfoBuilder->join("flight_crs_details","flight_crs_details.rate_plan_id=flight_crs_details.id"); */
      $sectorInfoBuilder->where("flight_crs_fare_list.origin", $JourneyOrigin);
      //$sectorInfoBuilder->where("suppliers.status","active");
      $sectorInfoBuilder->where("flight_crs_fare_list.destination", $JourneyDestination);
      $sectorInfoBuilder->where("flight_crs_fare_list.status", "active");
      if ($DirectFlight) {
        $sectorInfoBuilder->where("flight_crs_fare_list.onward_stops", 0);
      }
      if ($CabinClass != "Any") {
        $sectorInfoBuilder->where("flight_crs_details.cabin_class", $CabinClass);
      }
      $sectorInfoBuilder->where("flight_crs_details.available_seats>=", $TotalpaxExcludeInfant);
      $sectorInfoBuilder->where("flight_crs_fare_list.journey_type", "roundtrip");
      $sectorInfoBuilder->where("date(flight_crs_details.date)", $JourneyDate);
      $sectorInfoBuilder->where("date(flight_crs_details.date_return)", $JourneyReturnDate);
      $JourneyFareInfo[0] = $sectorInfoBuilder->get()->getResultArray();
    } else {
     
      foreach ($AirSegments as  $AirSegmentKey => $AirSegment) {
        $JourneyDate  =   explode("T", $AirSegment['PreferredTime'])[0];
        $JourneyDate  =  date("Y-m-d", strtotime($JourneyDate));
        $JourneyOrigin  = $AirSegment['Origin'];
        $JourneyDestination  = $AirSegment['Destination'];
        $sectorInfoBuilder  =  $this->db->table("flight_crs_fare_list")->select("suppliers.status as SupplierStatus,flight_crs_fare_list.id As SectorId,flight_crs_fare_list.origin As Origin,flight_crs_fare_list.destination As  Destination,flight_crs_fare_list.trip_type As TripType,flight_crs_fare_list.onward_segment_detail As SectorSegmentDetail,flight_crs_fare_list.disable_before_departure As DisableBeforeDeparture,flight_crs_details.id As FarePnrId,flight_crs_details.supplier_id As FarePnrsupplierId,flight_crs_details.date As FarePnrDate,flight_crs_details.date_return As FareReturnPnrDate,flight_crs_details.pnr As FarePnr,flight_crs_details.available_seats As AvailableSeats,flight_crs_details.booked_seats As BookedSeats,flight_crs_details.segment_info As SegmentInfo,flight_crs_details.booking_class As BookingClass,flight_crs_details.cabin_class As CabinClass,flight_crs_details.adult_base_fare As AdultBaseFare,flight_crs_details.adult_tax As AdultTax,flight_crs_details.adult_gst As AdultGst,flight_crs_details.child_base_fare As ChildBaseFare,flight_crs_details.child_tax As ChildTax,flight_crs_details.child_gst As ChildGst,flight_crs_details.infant_base_fare As InfantBaseFare,flight_crs_details.infant_tax As InfantTax,flight_crs_details.infant_gst As InfantGst");
        $sectorInfoBuilder->join("flight_crs_details", "flight_crs_fare_list.id=flight_crs_details.privatefare_id");
        $sectorInfoBuilder->join("suppliers", "flight_crs_details.supplier_id=suppliers.id", "Left");
        /*   $sectorInfoBuilder->join("flight_crs_details","flight_crs_details.rate_plan_id=flight_crs_details.id"); */
        $sectorInfoBuilder->where("flight_crs_fare_list.origin", $JourneyOrigin);
        $sectorInfoBuilder->where("flight_crs_fare_list.destination", $JourneyDestination);
        $sectorInfoBuilder->where("flight_crs_fare_list.status", "active");
        // $sectorInfoBuilder->where("suppliers.status","active");
        $sectorInfoBuilder->where("flight_crs_fare_list.journey_type", "oneway");
        if ($DirectFlight) {
          $sectorInfoBuilder->where("flight_crs_fare_list.onward_stops", 0);
        }
        if ($CabinClass != "Any") {
          $sectorInfoBuilder->where("flight_crs_details.cabin_class", $CabinClass);
        }
       
        $sectorInfoBuilder->where("flight_crs_details.available_seats>=", $TotalpaxExcludeInfant);
        $sectorInfoBuilder->where("date(flight_crs_details.date)", $JourneyDate);
        $JourneyFareInfo[$AirSegmentKey] = $sectorInfoBuilder->get()->getResultArray();
      }
    }

    return $JourneyFareInfo;
  }
  function GetSelectedSectorFareData($input)
  {
    $TotalpaxExcludeInfant  =  $input['Adult'] + $input['Child'];
    $SectorId  =  $input['indexinfo']['SectorId'];
    $FarePnrId  =  $input['indexinfo']['FarePnrId'];
    $CabinClass =  get_crs_cabinclass($input['CabinClass']);
    $JourneyFareInfo  =  array();
    $sectorInfoBuilder  =  $this->db->table("flight_crs_fare_list")->select("flight_crs_fare_list.id As SectorId,flight_crs_fare_list.origin As Origin,flight_crs_fare_list.destination As  Destination,flight_crs_fare_list.trip_type As TripType,flight_crs_fare_list.onward_segment_detail As SectorSegmentDetail,flight_crs_fare_list.disable_before_departure As DisableBeforeDeparture,flight_crs_details.id As FarePnrId,flight_crs_details.supplier_id As FarePnrsupplierId,flight_crs_details.date As FarePnrDate,flight_crs_details.date_return As FareReturnPnrDate,flight_crs_details.pnr As FarePnr,flight_crs_details.available_seats As AvailableSeats,flight_crs_details.booked_seats As BookedSeats,flight_crs_details.segment_info As SegmentInfo,flight_crs_details.booking_class As BookingClass,flight_crs_details.cabin_class As CabinClass,flight_crs_details.adult_base_fare As AdultBaseFare,flight_crs_details.adult_tax As AdultTax,flight_crs_details.adult_gst As AdultGst,flight_crs_details.child_base_fare As ChildBaseFare,flight_crs_details.child_tax As ChildTax,flight_crs_details.child_gst As ChildGst,flight_crs_details.infant_base_fare As InfantBaseFare,flight_crs_details.infant_tax As InfantTax,flight_crs_details.infant_gst As InfantGst");
    $sectorInfoBuilder->join("flight_crs_details", "flight_crs_fare_list.id=flight_crs_details.privatefare_id");
    /*  $sectorInfoBuilder->join("flight_crs_details","flight_crs_details.rate_plan_id=flight_crs_details.id"); */
    $sectorInfoBuilder->where("flight_crs_fare_list.id", $SectorId);
    $sectorInfoBuilder->where("flight_crs_details.id", $FarePnrId);
    $sectorInfoBuilder->where("flight_crs_fare_list.status", "active");
    if ($CabinClass != "Any") {
      $sectorInfoBuilder->where("flight_crs_details.cabin_class", $CabinClass);
    }
    $sectorInfoBuilder->where("flight_crs_details.available_seats>=", $TotalpaxExcludeInfant);
    $JourneyFareInfo = $sectorInfoBuilder->get()->getRowArray();
    return $JourneyFareInfo;
  }
  function GetOriginDsetinationAirportCodeInfo($originDestionation)
  {
    $builder = $this->db->table("flight_airports");
    $builder->select("name,city_name,country_name,country_code,city_code");
    $builder->whereIn("code", $originDestionation);
    return $builder->get()->getResultArray();
  }
  function GetAirlineInfo($AirlineCode, $fieldName)
  {
    $builder = $this->db->table("flight_airline_code");
    $builder->select($fieldName);
    $builder->where("airline_code", $AirlineCode);
    return $builder->get()->getRowArray();
  }

  function updateUserData($tableName, $whereCondition, $updateData)
  {
    $this->db->table($tableName)->where($whereCondition)->update($updateData);
  }
  function insert_flight_logs($web_partner_id, $tts_search_token, $request, $response, $service, $api_supplier, $created)
  {
    $insertlog = array(
      'web_partner_id'   => $web_partner_id,
      'tts_search_token' => $tts_search_token,
      'request'          => json_encode($request),
      'response'         => json_encode($response),
      'service'          => $service,
      'api_supplier'     => $api_supplier,
      'created'          => $created
    );
    $db = \Config\Database::connect('api');
    $db->table('common_flight_log')->insert($insertlog);
  }

  function GetData($tableName, $where, $fieldName)
  {
    $builder  =  $this->db->table("flight_crs_details");
    $builder->select($fieldName);
    $builder->where($where);
    return $builder->get()->getRowArray();
  }

  function get_data($tableName, $where, $fieldName)
  {
    $builder  =  $this->db->table($tableName);
    $builder->select($fieldName);
    $builder->where($where);
    return $builder->get()->getRowArray();
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
  /* function getFareRule($input)
   {
    $this->
   } */

  function getFareRule($input, $bookingClass, $airline_code)
  {
    $air_type =  "Domestic";
    if (!$input['IsDomestic']) {
      $air_type =  "International";
    }
    $builder  = $this->db->table("flight_crs_fare_rule");
    $builder->select("refundable_type,hand_baggage_adult,hand_baggage_child,hand_baggage_infant,checkin_baggage_adult,checkin_baggage_child,description");
    $builder->where("status", "active");
    $builder->groupStart();
    $builder->where("air_type", "All");
    $builder->orWhere("air_type", $air_type);
    $builder->groupEnd();
    $builder->groupStart();
    $builder->where("airline_code", "ANY");
    $builder->orWhere("airline_code", $airline_code);
    $builder->groupEnd();
    $builder->groupStart();
    $builder->where('find_in_set("' . $bookingClass . '", booking_class) <> 0');
    $builder->orWhere('booking_class', "All");
    $builder->groupEnd();
    $builder->orderBy("id", "DESC");
    $FareRuledata    = $builder->get()->getRowArray();
    return $FareRuledata;
  }
  function getAirportCodeTimeZoneIn($AirportCodes)
  {
    $builder =  $this->db->table("timezone");
    $builder->select("airportcode,timezone");
    $builder->whereIn("airportcode", $AirportCodes);
    $data  =  $builder->get()->getResultArray();
    return array_column($data, "timezone", "airportcode");
  }

  function get_auth_supplier_account_balance($supplier_id)
  {
      return $this->db->table("supplier_account_log")->select('balance')->where('supplier_id', $supplier_id)->orderBy("id", "DESC")->get()->getRowArray();
  }

  function insertData($tableName, $insertData)
  {
      $this->db->table($tableName)->insert($insertData);
      return $this->db->insertID();
  }

  function supplier_markup($input)
  {
    return array();
  }
  function supplier_markup_bck($input)
  {

    $TotalpaxExcludeInfant  =  $input['Adult'] + $input['Child'];
    $CabinClass =  get_crs_cabinclass($input['CabinClass']);
    $AirSegments  =  $input['AirSegments'];
    $DirectFlight =   $input['DirectFlight'];
    $JourneyFareInfo  =  array();
    if (!$input['IsDomestic'] && $input['JourneyType'] == 2) {
      $AirSegment  =  $AirSegments[0];
      $JourneyDate  =   explode("T", $AirSegment['PreferredTime'])[0];
      $JourneyReturnDate  =   explode("T", $AirSegments[1]['PreferredTime'])[0];
      $JourneyDate  =  date("Y-m-d", strtotime($JourneyDate));
      $JourneyReturnDate  =  date("Y-m-d", strtotime($JourneyReturnDate));
      $JourneyOrigin  = $AirSegment['Origin'];
      $JourneyDestination  = $AirSegment['Destination'];
      $sectorInfoBuilder  =  $this->db->table("flight_crs_fare_list")->select("suppliers.status as SupplierStatus,flight_crs_fare_list.id As SectorId,flight_crs_fare_list.origin As Origin,flight_crs_fare_list.destination As  Destination,flight_crs_fare_list.trip_type As TripType,flight_crs_fare_list.onward_segment_detail As SectorSegmentDetail,flight_crs_fare_list.disable_before_departure As DisableBeforeDeparture,flight_crs_details.id As FarePnrId,flight_crs_details.supplier_id As FarePnrsupplierId,flight_crs_details.date As FarePnrDate,flight_crs_details.date_return As FareReturnPnrDate,flight_crs_details.pnr As FarePnr,flight_crs_details.available_seats As AvailableSeats,flight_crs_details.booked_seats As BookedSeats,flight_crs_details.segment_info As SegmentInfo,flight_crs_details.booking_class As BookingClass,flight_crs_details.cabin_class As CabinClass,flight_crs_details.adult_base_fare As AdultBaseFare,flight_crs_details.adult_tax As AdultTax,flight_crs_details.adult_gst As AdultGst,flight_crs_details.child_base_fare As ChildBaseFare,flight_crs_details.child_tax As ChildTax,flight_crs_details.child_gst As ChildGst,flight_crs_details.infant_base_fare As InfantBaseFare,flight_crs_details.infant_tax As InfantTax,flight_crs_details.infant_gst As InfantGst");
      $sectorInfoBuilder->join("flight_crs_details", "flight_crs_fare_list.id=flight_crs_details.privatefare_id");
      $sectorInfoBuilder->join("suppliers", "flight_crs_details.supplier_id=suppliers.id", "Left");
      /*  $sectorInfoBuilder->join("flight_crs_details","flight_crs_details.rate_plan_id=flight_crs_details.id"); */
      $sectorInfoBuilder->where("flight_crs_fare_list.origin", $JourneyOrigin);
      //$sectorInfoBuilder->where("suppliers.status","active");
      $sectorInfoBuilder->where("flight_crs_fare_list.destination", $JourneyDestination);
      $sectorInfoBuilder->where("flight_crs_fare_list.status", "active");
      if ($DirectFlight) {
        $sectorInfoBuilder->where("flight_crs_fare_list.onward_stops", 0);
      }
      if ($CabinClass != "Any") {
        $sectorInfoBuilder->where("flight_crs_details.cabin_class", $CabinClass);
      }
      $sectorInfoBuilder->where("flight_crs_details.available_seats>=", $TotalpaxExcludeInfant);
      $sectorInfoBuilder->where("flight_crs_fare_list.journey_type", "roundtrip");
      $sectorInfoBuilder->where("date(flight_crs_details.date)", $JourneyDate);
      $sectorInfoBuilder->where("date(flight_crs_details.date_return)", $JourneyReturnDate);
      $JourneyFareInfo[0] = $sectorInfoBuilder->get()->getResultArray();
    } else {
      foreach ($AirSegments as  $AirSegmentKey => $AirSegment) {
        $JourneyDate  =   explode("T", $AirSegment['PreferredTime'])[0];
        $JourneyDate  =  date("Y-m-d", strtotime($JourneyDate));
        $JourneyOrigin  = $AirSegment['Origin'];
        $JourneyDestination  = $AirSegment['Destination'];
        $sectorInfoBuilder  =  $this->db->table("flight_crs_fare_list")->select("suppliers.status as SupplierStatus,flight_crs_fare_list.id As SectorId,flight_crs_fare_list.origin As Origin,flight_crs_fare_list.destination As  Destination,flight_crs_fare_list.trip_type As TripType,flight_crs_fare_list.onward_segment_detail As SectorSegmentDetail,flight_crs_fare_list.disable_before_departure As DisableBeforeDeparture,flight_crs_details.id As FarePnrId,flight_crs_details.supplier_id As FarePnrsupplierId,flight_crs_details.date As FarePnrDate,flight_crs_details.date_return As FareReturnPnrDate,flight_crs_details.pnr As FarePnr,flight_crs_details.available_seats As AvailableSeats,flight_crs_details.booked_seats As BookedSeats,flight_crs_details.segment_info As SegmentInfo,flight_crs_details.booking_class As BookingClass,flight_crs_details.cabin_class As CabinClass,flight_crs_details.adult_base_fare As AdultBaseFare,flight_crs_details.adult_tax As AdultTax,flight_crs_details.adult_gst As AdultGst,flight_crs_details.child_base_fare As ChildBaseFare,flight_crs_details.child_tax As ChildTax,flight_crs_details.child_gst As ChildGst,flight_crs_details.infant_base_fare As InfantBaseFare,flight_crs_details.infant_tax As InfantTax,flight_crs_details.infant_gst As InfantGst");
        $sectorInfoBuilder->join("flight_crs_details", "flight_crs_fare_list.id=flight_crs_details.privatefare_id");
        $sectorInfoBuilder->join("suppliers", "flight_crs_details.supplier_id=suppliers.id", "Left");
        /*   $sectorInfoBuilder->join("flight_crs_details","flight_crs_details.rate_plan_id=flight_crs_details.id"); */
        $sectorInfoBuilder->where("flight_crs_fare_list.origin", $JourneyOrigin);
        $sectorInfoBuilder->where("flight_crs_fare_list.destination", $JourneyDestination);
        $sectorInfoBuilder->where("flight_crs_fare_list.status", "active");
        // $sectorInfoBuilder->where("suppliers.status","active");
        $sectorInfoBuilder->where("flight_crs_fare_list.journey_type", "oneway");
        if ($DirectFlight) {
          $sectorInfoBuilder->where("flight_crs_fare_list.onward_stops", 0);
        }
        if ($CabinClass != "Any") {
          $sectorInfoBuilder->where("flight_crs_details.cabin_class", $CabinClass);
        }
        $sectorInfoBuilder->where("flight_crs_details.available_seats>=", $TotalpaxExcludeInfant);
        $sectorInfoBuilder->where("date(flight_crs_details.date)", $JourneyDate);
        $JourneyFareInfo[$AirSegmentKey] = $sectorInfoBuilder->get()->getResultArray();
      }
    }

    $result = [];

    if ($JourneyFareInfo) {

      // ###############################
      $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");
      $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");
      $firstAirSegment = reset($input['AirSegments']);
      $lastAirSegment = end($input['AirSegments']);
      $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);
      $builder = $this->db->table('supplier_markup');
      if (!$input['IsDomestic']) {
        $input['IsDomestic'] = 0;
      }
      $builder->select('airline_code,airline_name,from_airport_code,to_airport_code,is_domestic,journey_type,travel_date_from,travel_date_to,cabin_class,markup_type,value,max_limit,display_markup,supplier');
      // $builder->where('find_in_set("' . $web_partner_class_id . '", web_partner_class_id) <> 0');
      $builder->where('find_in_set("' . $input['IsDomestic'] . '", is_domestic) <> 0');
      $builder->where('find_in_set("' . $journeytype[$input['JourneyType']] . '", journey_type) <> 0');
      $builder->where('status', 'active');
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
      $result = $builder->get()->getResultArray();
    }
    return $result;
  }
}
