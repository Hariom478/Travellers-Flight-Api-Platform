<?php

use App\Modules\Payment\Models\PaymentModel;
use App\Modules\SalesReport\Models\SalesReportModel;
/* get Flight Time */


if (!function_exists('get_service_options')) {
    function get_service_options($service)
    {
        $data = array();
        switch ($service) {
            case "Flight":
                $data = array(
                    'booking_ref_number' => 'Booking Ref Number', 'journey_type' => 'Journey Type', 'origin' => 'Origin', 'destination' => 'Destination', 'issue_supplier' => 'Issue Supplier', 'fare_type' => 'Fare Type', 'airline_remark' => 'Airline Remark', 'api_supplier' => 'API Supplier', 'departure_date' => 'Departure Date', 'created' => 'Created', 'is_refundable' => 'Is Refundable', 'validating_airline_code' => 'Airline Code', 'segments' => 'Airline',
                    'payment_status' => 'Payment Status', 'total_price' => 'Total Amount', 'pnr' => 'PNR', 'booking_channel' => 'Booking Channel', 'agent_fare_break_up' => 'Price', 'lead_pax' => 'Lead Pax Name', 'booking_status' => 'Booking Status','staff_name'=>'Staff Name'
                );
                break;
            case "Hotel":
                $data = array();
                break;
            default:
                $data = array(
                    'booking_ref_number' => 'Booking Ref Number', 'journey_type' => 'Journey Type', 'origin' => 'Orgin', 'destination' => 'Destination', 'issue_supplier' => 'Issue Supplier', 'fare_type' => 'Fare Type', 'airline_remark' => 'Airline Remark', 'api_supplier' => 'API Supplier', 'departure_date' => 'Departure Date', 'created' => 'Created', 'is_refundable' => 'Is Refundable', 'validating_airline_code' => 'Airline Code', 'segments' => 'Airline',
                    'payment_status' => 'Payment Status', 'total_price' => 'Total Amount', 'pnr' => 'PNR', 'booking_channel' => 'Booking Channel', 'agent_fare_break_up' => 'Price', 'lead_pax' => 'Lead Pax Name', 'booking_status' => 'Booking Status','staff_name'=>'Staff Name'
                );

                break;
        }
        return $data;
    }
}


function get_excel_flight_data($filterKey, $selectedKey, $booking_details)
{


    $flight_data = array();
    $SalesReportModel = new SalesReportModel();
    $headers = array();
    $flight_keys = array();
    foreach ($selectedKey as $skey => $data) {
        if ($data == "agent_fare_break_up") {
            $agent_fare_break_up = array_values($filterKey['agent_fare_break_up']);
            foreach ($agent_fare_break_up as $key => $fareBreakUp) {
                array_push($headers, $fareBreakUp);
            }
        } elseif ($data == "segments") {
            $segments = array_values($filterKey['segments']);
            foreach ($segments as $key => $segment) {
                array_push($headers, $segment);
            }
        } elseif ($data == "lead_pax") {
            $lead_pax = array_values($filterKey['lead_pax']);
            foreach ($lead_pax as $key => $pax) {
                array_push($headers, $pax);
            }
        } elseif ($data == "created") {
            $invoice_data = array_values($filterKey['created']);
            foreach ($invoice_data as $key => $invoice) {
                array_push($headers, $invoice);
            }
        } else {
            array_push($headers, $filterKey[$data]);
        }
    }

    $rows = 0;
    foreach ($booking_details as $key => $val) {

        $flight_travellers_list = $SalesReportModel->flight_travellers_list($val['id']);

        $traveller_count = count($flight_travellers_list);

        foreach ($flight_travellers_list as $key => $traveller) {
            $segment = json_decode($val['segments'], true);
            if (count($segment) == 1) {
                $first_segment = current($segment[0]);
                $last_segment = end($segment[0]);
            } else {
                $first_segment = current($segment);
                $first_segment = current($first_segment);

                $last_segment = end($segment);
                $last_segment = end($last_segment);
            }

            $flightNo = (isset($first_segment['Airline']['FlightNumber'])) ? $first_segment['Airline']['FlightNumber'] : 0;
            $AirLineName = (isset($first_segment['Airline']['AirlineName'])) ? $first_segment['Airline']['AirlineName'] : 'N/A';

            $CorporateGSTNumber = null;


            if ($val['is_domestic']) {
                $is_domestic = 'dom';
            } else {
                $is_domestic = 'int';
            }


            if ($val['is_lcc']) {
                $is_lcc = 'LLC';
            } else {
                $is_lcc = 'GDS';
            }

            $PaxName = $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'];
            $PassportNumber = $traveller['passport_number'];
            $PaxType = $traveller['pax_type'];
            $TicketNumber = $traveller['ticket_number'];
            $DateOfBirth = display_custom_date_format($traveller['date_of_birth']);

            $acc_ref_number = $val['acc_ref_number'];
            $invoice_number = $val['invoice_number'];
            $InvoiceDate = date_created_format($val['created']);
            $client_name = $val['company_name'];

            $booking_fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
            $booking_fareBreakupArray_gst = null;
            if (isset($booking_fareBreakupArray['GST'])) {
                $booking_fareBreakupArray_gst = $booking_fareBreakupArray['GST'];
            }

            $fareBreakupArray = json_decode($traveller['agent_fare'], true);

            $TaxableAmount = null;
            $BaseFare = null;
            $Tax = null;
            $YQTax = null;
            $CGSTAmount = 0;
            $SGSTAmount = 0;
            $IGSTAmount = 0;
            $CGSTRate = null;
            $IGSTRate = null;
            $SGSTRate = null;
            $TotalGST = 0;
            $AgentCommission = null;
            $Discount = null;
            $TDS = null;
            $MealAmount = null;
            $BaggageAmount = null;
            $OfferedPrice = null;
            $OtherCharges = null;
            $ServiceCharges = null;
            $totalAmount = null;
            if (isset($fareBreakupArray['BaseFare'])) {
                $BaseFare = $fareBreakupArray['BaseFare'];
            }
            if (isset($fareBreakupArray['YQTax'])) {
                $YQTax = $fareBreakupArray['YQTax'];
            }
            if (isset($fareBreakupArray['Tax'])) {
                $Tax = $fareBreakupArray['Tax'];
            }
            if (isset($fareBreakupArray['AgentCommission'])) {
                $AgentCommission = $fareBreakupArray['AgentCommission'];
            }
            if (isset($fareBreakupArray['Discount'])) {
                $Discount = $fareBreakupArray['Discount'];
            }

            if (isset($fareBreakupArray['TDS'])) {
                $TDS = $fareBreakupArray['TDS'];
            }
            if (isset($fareBreakupArray['BaggageCharges'])) {
                $BaggageAmount = $fareBreakupArray['BaggageCharges'];
            }

            if (isset($fareBreakupArray['MealCharges'])) {
                $MealAmount = $fareBreakupArray['MealCharges'];
            }

            if (isset($fareBreakupArray['OfferedPrice'])) {
                $OfferedPrice = $fareBreakupArray['OfferedPrice'];
            }
            if (isset($fareBreakupArray['OtherCharges'])) {
                $OtherCharges = $fareBreakupArray['OtherCharges'];
            }

            if (isset($fareBreakupArray['ServiceCharges'])) {
                $ServiceCharges = $fareBreakupArray['ServiceCharges'];
            }
            if (isset($booking_fareBreakupArray_gst['TaxableAmount'])) {
                $TaxableAmount = $booking_fareBreakupArray_gst['TaxableAmount'] / $traveller_count;
            }

            if (isset($booking_fareBreakupArray_gst['CGSTAmount'])) {
                $CGSTAmount = $booking_fareBreakupArray_gst['CGSTAmount'] / $traveller_count;
            }

            if (isset($booking_fareBreakupArray_gst['SGSTAmount'])) {
                $SGSTAmount = $booking_fareBreakupArray_gst['SGSTAmount'] / $traveller_count;
            }
            if (isset($booking_fareBreakupArray_gst['IGSTAmount'])) {
                $IGSTAmount = $booking_fareBreakupArray_gst['IGSTAmount'] / $traveller_count;
            }

            if (isset($booking_fareBreakupArray_gst['IGSTRate'])) {
                $IGSTRate = $booking_fareBreakupArray_gst['IGSTRate'];
            }

            if (isset($booking_fareBreakupArray_gst['SGSTRate'])) {
                $SGSTRate = $booking_fareBreakupArray_gst['SGSTRate'];
            }
            if (isset($booking_fareBreakupArray_gst['CGSTRate'])) {
                $CGSTRate = $booking_fareBreakupArray_gst['CGSTRate'];
            }
            $TotalGST = $CGSTAmount + $SGSTAmount + $IGSTAmount;
            $totalAmount = $TDS + $OfferedPrice + $MealAmount + $BaggageAmount;
            foreach ($selectedKey as $skey => $data) {
                if ($data == "agent_fare_break_up") {
                    $agent_fare_break_up = array_keys($filterKey['agent_fare_break_up']);
                    foreach ($agent_fare_break_up as $key => $fareBreakUp) {
                        $flight_data[$rows][$fareBreakUp] = $$fareBreakUp;
                        array_push($flight_keys, $fareBreakUp);
                    }
                } elseif ($data == "segments") {
                    $segments = array_keys($filterKey['segments']);
                    foreach ($segments as $key => $segment) {
                        $flight_data[$rows][$segment] = $$segment;
                        array_push($flight_keys, $segment);
                    }
                } elseif ($data == "lead_pax") {
                    $lead_pax = array_keys($filterKey['lead_pax']);
                    foreach ($lead_pax as $key => $pax) {
                        $flight_data[$rows][$pax] = $$pax;
                        array_push($flight_keys, $pax);
                    }
                }elseif ($data == "created") {
                    $invoiceDetail = array_keys($filterKey['created']);
                    foreach ($invoiceDetail as $key => $invoice) {
                        $flight_data[$rows][$invoice] = $$invoice;
                        array_push($flight_keys, $invoice);
                    }
                } else {
                    if ($data == 'departure_date') {
                        $flight_data[$rows][$data] = display_custom_date_format($val[$data]);
                    } elseif ($data == "is_refundable") {
                        $flight_data[$rows][$data] = ($val[$data] == 1) ? 'yes' : 'no';
                    } else {
                        $flight_data[$rows][$data] = $val[$data];
                    }
                    array_push($flight_keys, $data);
                }
            }

            $rows++;
        }
    }
    return ['headers' => $headers, 'data' => $flight_data, 'keys' => $flight_keys];
}
