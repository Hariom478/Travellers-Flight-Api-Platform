<?php

namespace App\Modules\Payment\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    function checkWalletRecharge($webPartnerid, $transaction_id)
    {
        $from_date = strtotime(date('Y-m-d') . ' 00:00');
        $to_date = strtotime(date('Y-m-d') . ' 23:59');
        $array = ['web_partner_account_log.created >=' => $from_date, 'web_partner_account_log.created <=' => $to_date];
        $builder = $this->db->table('web_partner_account_log')->select('id');
        $builder->where($array);
        $builder->where(['web_partner_account_log.transaction_id' => $transaction_id, 'web_partner_account_log.web_partner_id' => $webPartnerid]);
        $data = $builder->countAllResults();
        if ($data > 0) {
            return 0;
        } else {
            return 1;
        }
    }
    function convenience_fee($service, $web_partner_id)
    {
        $convenience_fee = $this->db->table('web_partner_convenience_fee')->select('credit_card_type,credit_card_value,debit_card_type,debit_card_value,
        debit_card_value,debit_card_type,net_banking_value,net_banking_type,cash_card_value,cash_card_type,mobile_wallet_value,mobile_wallet_type')->where('web_partner_id', $web_partner_id)->where('find_in_set("' . $service . '", service) <> 0')->get()->getRowArray();
        if ($convenience_fee) {
            return $convenience_fee;
        } else {
            return array(
                'credit_card_value' => 0,
                'credit_card_type' => 'fixed',

                'debit_card_value' => 0,
                'debit_card_type' => 'fixed',
                'net_banking_value' => 0,
                'net_banking_type' => 'fixed',
                'cash_card_value' => 0,
                'cash_card_type' => 'fixed',
                'mobile_wallet_value' => 0,
                'mobile_wallet_type' => 'fixed'
            );
        }
    }

    function get_booking_detail($service, $bookingid, $web_partner_id)
    {

        if ($service == 'bus') {
            $builder = $this->db->table('bus_booking_list');
            $builder->select("origin_city,booking_ref_number,web_partner_fare_break_up,agent_fare_break_up,web_partner_booking_total_price,destination_city,date_of_journey,bus_name,bus_type,departure_time,arrival_time,title,first_name,last_name,email_id,mobile_number,no_of_seats,total_price,created,concat('[', group_concat(JSON_OBJECT('id', bus_booking_travelers.id,'title',bus_booking_travelers.title,'first_name',bus_booking_travelers.first_name,'last_name',bus_booking_travelers.last_name,'age',bus_booking_travelers.age,'email_id',bus_booking_travelers.email_id,'mobile_number',bus_booking_travelers.mobile_number,'lead_pax',bus_booking_travelers.lead_pax,'gendar',bus_booking_travelers.gendar,'id_type',bus_booking_travelers.id_type,'id_number',bus_booking_travelers.id_number,'seat_name',bus_booking_travelers.seat_name) separator ','), ']') as travelersInfo");

            $builder->where(['bus_booking_list.id' => $bookingid, 'bus_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('bus_booking_travelers', "bus_booking_travelers.bus_booking_id = $bookingid");
            $builder->groupBy('bus_booking_list.id');
            $query = $builder->get()->getRowArray();
            if ($query) {
                $PaxName = $query['first_name'] . ' ' . $query['last_name'] . ' X ' . $query['no_of_seats'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $query['origin_city'] . '-' . $query['destination_city'], 'TravelDate' => $query['date_of_journey']));
            }
            return $query;
        } else if ($service == 'hotel') {
            $builder = $this->db->table('hotel_booking_list');
            $builder->select("city,check_in_date,booking_ref_number,web_partner_fare_break_up,agent_fare_break_up,web_partner_booking_total_price,check_out_date,hotel_rooms_details,total_price,room_guests,hotel_name,no_of_nights,created");
            $builder->where(['hotel_booking_list.id' => $bookingid, 'hotel_booking_list.web_partner_id' => $web_partner_id]);
            $query = $builder->get()->getRowArray();
            if ($query) {
                $totalpax = 0;
                $room_guests = json_decode($query['room_guests'], true);
                $hotel_rooms_details = json_decode($query['hotel_rooms_details'], true);
                foreach ($room_guests as $room_guest) {
                    $totalpax = $totalpax + $room_guest['Adult'] + $room_guest['Child'];
                }
                $query['first_name'] = $hotel_rooms_details[0]['HotelPassenger'][0]['FirstName'];
                $query['last_name'] = $hotel_rooms_details[0]['HotelPassenger'][0]['LastName'];
                $query['email_id'] = $hotel_rooms_details[0]['HotelPassenger'][0]['Email'];
                $query['mobile_number'] = $hotel_rooms_details[0]['HotelPassenger'][0]['Phoneno'];
                $PaxName = $hotel_rooms_details[0]['HotelPassenger'][0]['FirstName'] . ' ' . $hotel_rooms_details[0]['HotelPassenger'][0]['LastName'] . ' X ' . $totalpax;
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'City' => $query['city'], 'CheckInDate' => $query['check_in_date'], 'CheckOutDate' => $query['check_out_date']));
            }
            return $query;
        } else if ($service == 'visa') {
            $builder = $this->db->table('visa_booking_list');
            $builder->select("visa_booking_travelers.title,booking_ref_number,web_partner_booking_total_price,web_partner_fare_break_up,agent_fare_break_up,first_name,visa_booking_travelers.last_name,visa_booking_travelers.email_id,visa_booking_travelers.mobile_number,visa_booking_list.total_price,visa_booking_list.created,visa_booking_list.no_of_travellers,visa_booking_list.visa_country,visa_booking_list.visa_type,visa_booking_list.date_of_journey");

            $builder->where(['visa_booking_list.id' => $bookingid, 'visa_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('visa_booking_travelers', "visa_booking_travelers.visa_booking_id = $bookingid");
            $builder->groupBy('visa_booking_list.id');
            $query = $builder->get()->getRowArray();
            if ($query) {
                $PaxName = $query['first_name'] . ' ' . $query['last_name'] . ' X ' . $query['no_of_travellers'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $query['visa_country'] . '-' . $query['visa_type'], 'TravelDate' => $query['date_of_journey']));
            }
            return $query;
        } else if ($service == 'car') {
            $builder = $this->db->table('car_booking_list');
            $builder->select("car_name,source,destination,booking_ref_number,web_partner_booking_total_price,departure_date,web_partner_fare_break_up,agent_fare_break_up,total_price,title,first_name,last_name,gendar,email,mobile_number,created");
            $builder->where(['car_booking_list.id' => $bookingid, 'car_booking_list.web_partner_id' => $web_partner_id]);
            $query = $builder->get()->getRowArray();
            if ($query) {

                $query['first_name'] = $query['first_name'];
                $query['last_name'] = $query['last_name'];
                $query['email_id'] = $query['email'];
                $query['mobile_number'] = $query['mobile_number'];
                $PaxName = $query['first_name'] . ' ' . $query['last_name'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $query['source'] . '-' . $query['destination'], 'TravelDate' => $query['departure_date']));
            }
            return $query;
        } else if ($service == 'holiday') {
            $builder = $this->db->table('holiday_booking_list');
            $builder->select("holiday_booking_list.booking_ref_number,holiday_booking_list.id,web_partner_booking_total_price,web_partner_fare_break_up,agent_fare_break_up,holiday_booking_travelers.title,holiday_booking_travelers.first_name,holiday_booking_travelers.last_name, holiday_booking_list.package_name,holiday_booking_list.package_category,holiday_booking_list.day_nights,holiday_booking_list.package_image,holiday_booking_list.travel_date,holiday_booking_list.created,holiday_booking_list.total_price,holiday_booking_travelers.email_id,holiday_booking_travelers.mobile_number");
            $builder->where(['holiday_booking_list.id' => $bookingid, 'holiday_booking_list.web_partner_id' => $web_partner_id, 'holiday_booking_travelers.lead_pax' => 1]);

            $builder->join('holiday_booking_travelers', "holiday_booking_travelers.holiday_booking_id = $bookingid");
            $builder->groupBy('holiday_booking_list.id');
            $query = $builder->get()->getRowArray();
            if ($query) {
                $PaxName = $query['first_name'] . ' ' . $query['last_name'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $query['package_name'] . '-' . $query['package_category']));
            }
            return $query;
        } else if ($service == "tourguide") {
            $builder = $this->db->table("tourguide_booking_list");
            $builder->select("agent_fare_break_up,web_partner_fare_break_up,web_partner_booking_total_price,tourguide_booking_list.id,tourguide_booking_list.booking_ref_number,tourguide_booking_list.guide_name,tourguide_booking_list.monument_title,tourguide_booking_list.monument_duration,tourguide_booking_list.travel_date,tourguide_booking_list.first_name,tourguide_booking_list.last_name,tourguide_booking_list.start_time,tourguide_booking_list.end_time,tourguide_booking_list.total_price,tourguide_booking_list.email_id,tourguide_booking_list.mobile_number,tourguide_booking_list.created");
            $builder->where(['tourguide_booking_list.id' => $bookingid, 'tourguide_booking_list.web_partner_id' => $web_partner_id]);
            $query = $builder->get()->getRowArray();

            if ($query) {
                $PaxName = $query['first_name'] . ' ' . $query['last_name'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'GuideName' => $query['guide_name'], 'MonumentTitle' => $query['monument_title'], 'MonumentDuration' => $query['monument_duration'], 'StartTime' => $query['start_time'], 'EndTime' => $query['end_time'], 'BookingRefNumber' => $query['booking_ref_number'], 'TravelDate' => $query['travel_date']));
            }
            return $query;
        } else if ($service == 'cruise') {
            $builder = $this->db->table('cruise_booking_list');

            $builder->select("agent_fare_break_up,cruise_booking_list.id,cruise_booking_list.booking_ref_number,cruise_booking_travelers.first_name,cruise_booking_travelers.last_name, cruise_booking_list.cruise_line_name,cruise_booking_list.ship_name,cruise_booking_list.created,cruise_booking_list.no_of_travellers,cruise_booking_list.total_price,cruise_booking_list.sailing_date,cruise_booking_list.departure_port,cruise_booking_travelers.email_id,cruise_booking_list.cruise_ocean,cruise_booking_travelers.mobile_number,web_partner_fare_break_up,web_partner_booking_total_price");

            $builder->where(['cruise_booking_list.id' => $bookingid, 'cruise_booking_list.web_partner_id' => $web_partner_id, 'cruise_booking_travelers.lead_pax' => 1]);

            $builder->join('cruise_booking_travelers', "cruise_booking_travelers.cruise_booking_id = $bookingid");

            $builder->groupBy('cruise_booking_list.id');

            $query = $builder->get()->getRowArray();

            if ($query) {

                $PaxName = $query['first_name'] . ' ' . $query['last_name'] . ' X ' . $query['no_of_travellers'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'SailingDate' => $query['sailing_date'], "CruiseLineName" => $query['cruise_line_name'], "ShipName" => $query['ship_name'], "CruiseOcean" => $query['cruise_ocean'], "DeparturePort" => $query['departure_port']));
            }

            return $query;
        } else if ($service == 'activities') {
            $builder = $this->db->table('activities_booking_list');
            $builder->select("activities_booking_list.id,activities_booking_list.booking_ref_number,activities_booking_list.customer_fare_break_up,activities_booking_travelers.first_name,activities_booking_travelers.last_name,activities_booking_travelers.activity_booking_id,activities_booking_list.created,activities_booking_list.total_price, activities_booking_list.agent_fare_break_up, activities_booking_list.web_partner_fare_break_up, activities_booking_list.web_partner_booking_total_price, activities_booking_travelers.email_id,activities_booking_travelers.mobile_number");

            $builder->where(['activities_booking_list.id' => $bookingid, 'activities_booking_list.web_partner_id' => $web_partner_id]);

            $builder->join('activities_booking_travelers', "activities_booking_travelers.activity_booking_id = activities_booking_list.id");
            $builder->groupBy('activities_booking_list.id');

            $query = $builder->get()->getRowArray();

            if ($query) {
                $PaxName = $query['first_name'] . ' ' . $query['last_name'];
                $query['service_log'] = json_encode(array('PaxName' => $PaxName, 'Sector' => $query['total_price'] . '-' . $query['booking_ref_number']));
            }
            return $query;
        }
    }

    function get_flight_booking_detail($service, $bookingids, $web_partner_id, $SearchTokenId)
    {
        $queryData = array();
        if ($service == 'flight') {
            foreach ($bookingids as $rtype => $flightBookinid) {
                $builder = $this->db->table('flight_booking_list');
                $builder->select("flight_booking_list.id,web_partner_fare_break_up,agent_fare_break_up,segments,is_domestic,booking_ref_number,trip_indicator,origin,destination,departure_date,airline_code,journey_type,total_price,created,flight_booking_travelers.title,flight_booking_travelers.first_name,flight_booking_travelers.last_name,flight_booking_travelers.email_id,flight_booking_travelers.mobile_number,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'nationality',flight_booking_travelers.nationality) separator ','), ']') as travelersInfo");
                $builder->where(['flight_booking_list.id' => $flightBookinid, 'flight_booking_list.web_partner_id' => $web_partner_id]);
                $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
                $builder->groupBy('flight_booking_list.id');
                $query = $builder->get()->getRowArray();
                $queryData[$rtype] = $query;
                if (!$query) {
                    $queryData = array();
                    break;
                }
            }
        }
        return $queryData;
    }

    function checkpayment_record($tableName, $whereCondition)
    {
        return $this->db->table($tableName)->select('id,web_partner_id')->where($whereCondition)->get()->getRowArray();
    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function updateData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    public function get_payment_detail($order_id)
    {
        return $this->db->table("web_partner_payment_transaction")->select('id,web_partner_id,user_id,booking_ref_no,service,booking_prefix,booking_ref_number,service_log,convenience_fee,payment_mode,payment_status,payment_response')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }

    function getMakePaymentDetails($whereCondition)
    {
        return $this->db->table("wl_agent_make_payment")->select('id,web_partner_id,payment_mode,amount,remark')->where($whereCondition)->get()->getRowArray();
    }

    function getInvoiceSuffixData($whereCondition, $otherParameter)
    {
        $field = '';
        if ($otherParameter['checkTaxableInvoce'] == 1) {
            $field = 'taxable_couter as couter,taxable_prefix as prefix,financial_year';
        } else {
            $field = 'nontaxable_couter as couter,nontaxable_prefix as prefix,financial_year';
        }
        $builder = $this->db->table('invoice_suffix_list')->select($field);
        $builder->where($whereCondition);
        $data = $builder->get()->getRowArray();
        if ($data) {
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
            return $data;
        } else {

            $insertData['financial_year'] = $otherParameter['financialYear'];
            $insertData['service'] = $otherParameter['service'];
            $insertData['taxable_prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
            $insertData['nontaxable_prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
            $insertData['taxable_couter'] = 1;
            $insertData['nontaxable_couter'] = 1;
            $insertData['invoice_type'] = $otherParameter['invoice_type'];
            $this->db->table('invoice_suffix_list')->insert($insertData);
            if ($otherParameter['checkTaxableInvoce'] == 1) {
                $data['prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            } else {
                $data['prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            }
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
        }

        return $data;
    }

    function super_admin_booking_pre_fix_code($service)
    {
        if ($service != "Make_Payment") {
            $builder = $this->db->table('super_admin_website_setting');
            $builder->select('pre_fix');
            return $builder->get()->getRowArray();
        } else {
            return array();
        }
    }
}
