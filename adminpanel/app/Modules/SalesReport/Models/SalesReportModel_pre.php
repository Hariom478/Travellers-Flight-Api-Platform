<?php

namespace App\Modules\SalesReport\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SalesReportModel extends Model
{

    public function flight_booking_detail($web_partner_id, $userId, $userType,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['flight_booking_list.created >=' => $from_date, 'flight_booking_list.created <=' => $to_date];

            $builder = $this->db->table('flight_booking_list');
            $builder->select("flight_booking_list.*,concat('[', group_concat(JSON_OBJECT('id', flight_booking_travelers.id,'booking_status',flight_booking_travelers.booking_status,'ticket_number',flight_booking_travelers.ticket_number,'title',flight_booking_travelers.title,'first_name',flight_booking_travelers.first_name,'last_name',flight_booking_travelers.last_name,'pax_type',flight_booking_travelers.pax_type,'gendar',flight_booking_travelers.gendar,'date_of_birth',flight_booking_travelers.date_of_birth,'pan_number',flight_booking_travelers.pan_number,'passport_number',flight_booking_travelers.passport_number,'passport_expiry',flight_booking_travelers.passport_expiry,'lead_pax',flight_booking_travelers.lead_pax,'email_id',flight_booking_travelers.email_id,'mobile_number',flight_booking_travelers.mobile_number,'address_1',flight_booking_travelers.address_1,'address_2',flight_booking_travelers.address_2,'city',flight_booking_travelers.city,'country_code',flight_booking_travelers.country_code,'country_name',flight_booking_travelers.country_name,'ff_airline',flight_booking_travelers.ff_airline,'ff_number',flight_booking_travelers.ff_number,'baggage',flight_booking_travelers.baggage,'meal',flight_booking_travelers.meal,'fare',flight_booking_travelers.fare,'date_of_birth',flight_booking_travelers.date_of_birth) separator ','), ']') as travelersInfo,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name")
                ->where($array)->join("admin_users", "admin_users.id=flight_booking_list.agent_staff_id", 'left');
            $builder->where(['flight_booking_list.web_partner_id' => $web_partner_id]);
            $builder->join('flight_booking_travelers', "flight_booking_travelers.flight_booking_id = flight_booking_list.id");
            $builder->groupBy('flight_booking_list.id');
            $query = $builder->get()->getResultArray();
            if ($query) {
                foreach ($query as $key => $data) {
                    $builder = $this->db->table('flight_booking_list');
                    $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                        ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=flight_booking_list.id");
                    $builder->where(['flight_booking_list.booking_ref_number' => $data['booking_ref_number'], 'flight_booking_list.web_partner_id' => $web_partner_id, "web_partner_account_log.service" => "flight", "web_partner_account_log.action_type" => "booking"]);
                    $query[$key]['paymentInfo'] = $builder->get()->getRowArray();

                    $fareBreakupArray = json_decode($data['web_partner_fare_break_up'], true);
                    $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                    $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                            /*   "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
                            "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
                            /* "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                            "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                            "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                        "GSTDetails" => $fareBreakupArray['GST'],
                        "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                        "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
                    );
                    $query[$key]['FareBreakUp'] = $FareBreakUp;
                }
            }
            return $query;
        }else {
            return null;
        }
    }


    public function hotel_booking_detail($web_partner_id, $userId, $userType,$data)
    {
        if ($data['from_date'] && $data['to_date']) {
            $from_date = strtotime(date('Y-m-d', strtotime($data['from_date'])) . '00:00');
            $to_date = strtotime(date('Y-m-d', strtotime($data['to_date'])) . '23:59');
            $array = ['hotel_booking_list.created >=' => $from_date, 'hotel_booking_list.created <=' => $to_date];

            $builder = $this->db->table('hotel_booking_list');
            $builder->select("hotel_booking_list.id,hotel_booking_list.last_cancellation_date,hotel_booking_list.payment_mode,hotel_booking_list.guest_nationality,hotel_booking_list.is_domestic,hotel_booking_list.booking_ref_number,hotel_booking_list.gst_info,hotel_booking_list.city,hotel_booking_list.web_partner_fare_break_up,hotel_booking_list.check_in_date,hotel_booking_list.check_out_date,hotel_booking_list.no_of_rooms,hotel_booking_list.created,hotel_booking_list.country_code,hotel_booking_list.address1,hotel_booking_list.star_rating,hotel_booking_list.no_of_nights,hotel_booking_list.room_guests,hotel_booking_list.hotel_norms,hotel_booking_list.hotel_policy_detail,hotel_booking_list.hotel_name,hotel_booking_list.confirmation_no,hotel_booking_list.payment_status,hotel_booking_list.booking_status,hotel_booking_list.total_price,hotel_booking_list.agent_staff_id,hotel_booking_list.last_cancellation_date,hotel_booking_list.lead_passenger_name,hotel_booking_list.booking_channel,hotel_booking_list.hotel_rooms_details,hotel_booking_list.contact_number,hotel_booking_list.contact_email_id,CONCAT(admin_users.first_name,' ',admin_users.last_name) as staff_name");
                    $builder->where($array)->join("admin_users","admin_users.id=hotel_booking_list.agent_staff_id",'left');
                    $builder->where(["hotel_booking_list.web_partner_id"=>$web_partner_id]);
                   $query =  $builder->get()->getResultArray();

            if ($query) {
                foreach ($query as $key => $data) {
                    $builder = $this->db->table('hotel_booking_list');
                    $builder->select("web_partner_account_log.id,web_partner_account_log.acc_ref_number,web_partner_account_log.debit,web_partner_account_log.credit,web_partner_account_log.service,web_partner_account_log.remark,web_partner_account_log.service_log,web_partner_account_log.transaction_id,web_partner_account_log.payment_mode,web_partner_account_log.transaction_type,web_partner_account_log.action_type,web_partner_account_log.created")
                        ->join("web_partner_account_log", "web_partner_account_log.booking_ref_no=hotel_booking_list.id");
                    $builder->where(['hotel_booking_list.booking_ref_number' => $data['booking_ref_number'], 'hotel_booking_list.web_partner_id' => $web_partner_id, "web_partner_account_log.service" => "hotel", "web_partner_account_log.action_type" => "booking"]);
                    $query[$key]['paymentInfo'] = $builder->get()->getRowArray();

                    $fareBreakupArray = json_decode($data['web_partner_fare_break_up'], true);
                    if ($fareBreakupArray) {
                        $publishedFare = 0;
                        $offeredFare = 0;
                        $CommEarned = 0;
                        $TDS = 0;

                        $HotelRoomsDetails = json_decode($data['hotel_rooms_details'], true);
                        foreach ($HotelRoomsDetails as $HotelRooms) {
                            $publishedFare = $publishedFare + $HotelRooms['Price']['PublishedPrice'];
                            $offeredFare = $offeredFare + $HotelRooms['Price']['OfferedPrice'];
                            $CommEarned = $CommEarned + $HotelRooms['Price']['AgentCommission'] + $HotelRooms['Price']['Discount'];
                            $TDS = $TDS + $HotelRooms['Price']['TDS'];
                        }

                        $markup = isset($fareBreakupArray['WebPMarkUp']) ? $fareBreakupArray['WebPMarkUp'] : 0;
                        $discount = isset($fareBreakupArray['WebPDiscount']) ? $fareBreakupArray['WebPDiscount'] : 0;
                        $FareBreakUp = array(
                            "FareBreakup" => array(
                                "Published_Price" => array("Value" => $publishedFare, "LabelText" => "Published Price"),
                                "Offered_Price" => array("Value" => $offeredFare, "LabelText" => "Offered Price"),

                                "CommEarned" => array("Value" => $CommEarned, "LabelText" => "Comm Earned (-)"),
                                //"Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                                "TDS" => array("Value" =>$TDS, "LabelText" => "TDS (+)")
                            ),
                            "TotalAmount" => array("Value" => $offeredFare + $TDS, "LabelText" => "Total Amount"),
                            //"GSTDetails" => $fareBreakupArray['GST'],
                            "GSTDetails" => '',
                            "WebPMarkUp" => array("Value" => $markup, "LabelText" => "Apply Mark Up"),
                            "WebPDiscount" => array("Value" => $discount, "LabelText" => "Apply Discount"),
                        );
                        $query[$key]['FareBreakUp'] = $FareBreakUp;
                    }
                }
            }
            return $query;
        }else {
            return null;
        }
    }


}
