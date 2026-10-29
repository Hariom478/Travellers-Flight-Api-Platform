<?php

namespace Modules\SalesReport\Controllers;


use App\Modules\SalesReport\Models\SalesReportModel;
use App\Modules\Flight\Models\FlightBookingModel;
use App\Modules\Hotel\Models\HotelBookingModel;
use App\Modules\Holiday\Models\HolidayModel;
use App\Modules\Visa\Models\VisaModel;
use App\Modules\CarExtranet\Models\CarBookingModel;
use App\Modules\Bus\Models\BusBookingModel;
use App\Modules\Cruise\Models\CruiseBookingModel;
use App\Controllers\BaseController;
use Modules\SalesReport\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalesReport extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Sales Report";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    }

    public function index()
    {
        $service = $this->request->getGet('q');
        $html_view = "";
        $list = "";
        $pager = "";
        $getData = "";
        if ($service == "Flight") {
            $FlightBookingModel = new FlightBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {
                $list = $FlightBookingModel->search_bookings_sales_report($this->web_partner_id,$getData);
            } else {
                $list = $FlightBookingModel->flight_booking_list_sales_report($this->web_partner_id);
            }
            $pager = $FlightBookingModel->pager;
            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views\Flight-booking-list", $booking_data);

        } else if ($service == "Hotel") {
            $HotelBookingModel = new HotelBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {

                $list = $HotelBookingModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $HotelBookingModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            
            $pager = $HotelBookingModel->pager;
            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views\hotel-booking-list", $booking_data);
        } else if ($service == "Holiday") {
            $HolidayBookingModel = new HolidayModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {
                $list = $HolidayBookingModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $HolidayBookingModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            $pager = $HolidayBookingModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views\holiday-booking-list", $booking_data);
        } else if ($service == "Visa") {
            $VisaModel = new VisaModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {

                $list = $VisaModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $VisaModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            $pager = $VisaModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views/visa-booking-list", $booking_data);
        } else if ($service == "Car") {
            $CarBookingModel = new CarBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {

                $list = $CarBookingModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $CarBookingModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            $pager = $CarBookingModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views/car-booking-list", $booking_data);
        } else if ($service == "Bus") {
            $BusBookingModel = new BusBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {

                $list = $BusBookingModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $BusBookingModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            $pager = $BusBookingModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,

            ];
            $html_view = view("Modules\SalesReport\Views/bus-booking-list", $booking_data);
        } else if($service == "Cruise"){
            $CruiseBookingModel = new CruiseBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {

                $list = $CruiseBookingModel->search_data_sales_report($this->web_partner_id,$getData, $page = 40);
            } else {
                $list = $CruiseBookingModel->data_sales_report($this->web_partner_id,$page = 40);
            }
            $pager = $CruiseBookingModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,
            ];
            $html_view = view("Modules\SalesReport\Views\cruise-booking-list", $booking_data);
        } else {
            #default case
            $service = "Flight";

            $FlightBookingModel = new FlightBookingModel();
            $getData = $this->request->getGET();
            if (isset($getData['key'])) {
                $list = $FlightBookingModel->search_bookings_sales_report($this->web_partner_id,$getData);;
            } else {
                $list = $FlightBookingModel->flight_booking_list_sales_report($this->web_partner_id);
            }
            $pager = $FlightBookingModel->pager;


            $booking_data = [
                "list" => $list,
                'html_view' => $html_view,
                "search_bar_data" => $getData,
                'pager' => $pager,
            ];
            $html_view = view("Modules\SalesReport\Views\Flight-booking-list", $booking_data);
        }

        $data = [
            'title' => $this->title,
            'service' => $service,
            "list" => $list,
            'html_view' => $html_view,
            "search_bar_data" => $getData,
            'pager' => $pager,
            'view' => "SalesReport\Views\index",
        ];
        return view('template/sidebar-layout', $data);
    }

    public function get_report()
    {
        $data = $this->request->getPost();
        $rules = $this->validate([
            'service' => [
                'label' => 'service',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select service'
                ]
            ],
            'from_date' => [
                'label' => 'From Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select from date.'
                ]
            ],
            'to_date' => [
                'label' => 'To Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select to date.'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            if ($data['service'] == 'Flight') {
                $data_validation = SalesReport::flight_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif ($data['service'] == 'Hotel') {
                $data_validation = SalesReport::hotel_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif ($data['service'] == 'Visa') {
                $data_validation = SalesReport::visa_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif ($data['service'] == 'Car') {
                $data_validation = SalesReport::car_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif ($data['service'] == 'Bus') {
                $data_validation = SalesReport::bus_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif ($data['service'] == 'Holiday') {
                $data_validation = SalesReport::holiday_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } elseif($data['service'] == 'Cruise'){
                $data_validation = SalesReport::cruise_report($data['service'], $data);
                if ($data_validation) {
                    return $this->response->setJSON($data_validation);
                } else {
                    $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                    return $this->response->setJSON($data_validation);
                }
            } else {
                $data_validation = array("StatusCode" => 10, 'Message' => 'Service Not Available');
                return $this->response->setJSON($data_validation);
            }
        }
    }


    public function flight_report($service, $data)
    {
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->flight_booking_list_report($this->web_partner_id,$data);
        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'PNR');
        $sheet->setCellValue('E1', 'Airline');
        $sheet->setCellValue('F1', 'Booking Reference No');
        $sheet->setCellValue('G1', 'Pax Name');
        $sheet->setCellValue('H1', 'Ticket No');
        $sheet->setCellValue('I1', 'Sector');
        $sheet->setCellValue('J1', 'Flight No.');
        $sheet->setCellValue('K1', 'Travel Date');
        $sheet->setCellValue('L1', 'Product');

        $sheet->setCellValue('M1', 'Basic Fare');
        $sheet->setCellValue('N1', 'Taxes');
        $sheet->setCellValue('O1', 'Ot. & Service Charge');
        $sheet->setCellValue('P1', 'SSR');


        $sheet->setCellValue('Q1', 'Comm. Earned');
        $sheet->setCellValue('R1', 'Discount');
        $sheet->setCellValue('S1', 'TDS ');

        $sheet->setCellValue('T1', 'Management Fee');
        $sheet->setCellValue('U1', 'CGST');
        $sheet->setCellValue('V1', 'SGST');
        $sheet->setCellValue('W1', 'IGST');
        $sheet->setCellValue('X1', 'GST');

        $sheet->setCellValue('Y1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {

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
                if($val['booking_source']=="Wl_b2b"){
                    $AccountTable ="agent_account_log";
                    $client_name = $val['company_name'];
                    $booking_fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
                    $fareBreakupArray = json_decode($traveller['agent_fare'], true);
                }
                if($val['booking_source']=="Wl_b2c"){
                    $AccountTable ="customer_account_log";
                    $client_name = '';
                    $booking_fareBreakupArray = json_decode($val['customer_fare_break_up'], true);
                    $fareBreakupArray = json_decode($traveller['customer_fare'], true);
                }
                $InvoiceInfo = $SalesReportModel->getData($AccountTable,['service'=>'flight','booking_ref_no'=>$val['id'],'action_type'=>'booking'],'invoice_number');
                $invoice_number = (isset($InvoiceInfo['invoice_number']))?$InvoiceInfo['invoice_number']:NULL;
                $booking_fareBreakupArray_gst = null;
                if (isset($booking_fareBreakupArray['GST'])) {
                    $booking_fareBreakupArray_gst = $booking_fareBreakupArray['GST'];
                }

                $TaxableAmount = null;
                $CGSTAmount = null;
                $SGSTAmount = null;
                $IGSTAmount = null;
                $TotalGST = null;
                $AgentCommission = null;
                $BaseFare = null;
                $Tax = null;
                $Discount = null;
                $TDS = null;
                $OfferedPrice = null;
                $OtherCharges = null;
                $ServiceCharges = null;
                $MealCharges = null;
                $BaggageCharges = null;
                if (isset($fareBreakupArray['BaseFare'])) {
                    $BaseFare = $fareBreakupArray['BaseFare'];
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

                if (isset($fareBreakupArray['OfferedPrice'])) {
                    $OfferedPrice = $fareBreakupArray['OfferedPrice'];
                }
                if (isset($fareBreakupArray['OtherCharges'])) {
                    $OtherCharges = $fareBreakupArray['OtherCharges'];
                }

                if (isset($fareBreakupArray['ServiceCharges'])) {
                    $ServiceCharges = $fareBreakupArray['ServiceCharges'];
                }

                if (isset($fareBreakupArray['BaggageCharges'])) {
                    $BaggageCharges = $fareBreakupArray['BaggageCharges'];
                }

                if (isset($fareBreakupArray['MealCharges'])) {
                    $MealCharges = $fareBreakupArray['MealCharges'];
                }

                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $BaseFare, "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => $Tax, "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $OtherCharges + $ServiceCharges, "LabelText" => "Other & Service Charges"),
                        "MealBaggageCharge" => array("Value" => $MealCharges + $BaggageCharges, "LabelText" => "SSR"),
                        "CommEarned" => array("Value" => $AgentCommission, "LabelText" => "Comm Earned (-)"),
                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                        "TDS" => array("Value" => $TDS, "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => $TDS + $OfferedPrice, "LabelText" => "Total Amount"),
                    "GSTDetails" => $booking_fareBreakupArray_gst
                );

                if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                    $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'] / $traveller_count;
                }

                if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                    $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'] / $traveller_count;
                }

                if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                    $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'] / $traveller_count;
                }
                if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                    $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'] / $traveller_count;
                }
                $lead_pax_ticket_number = null;

                $lead_pax_name = $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'];
                $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

                $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
                $sheet->setCellValue('B' . $rows, $invoice_number);
                $sheet->setCellValue('C' . $rows, $client_name);
                $sheet->setCellValue('D' . $rows, $val['pnr']);
                $sheet->setCellValue('E' . $rows, $AirLineName);
                $sheet->setCellValue('F' . $rows, $val['booking_ref_number']);
                $sheet->setCellValue('G' . $rows, $lead_pax_name);
                $sheet->setCellValue('H' . $rows, $traveller['ticket_number']);
                $sheet->setCellValue('I' . $rows, $val['origin'] . '-' . $val['destination']);
                $sheet->setCellValue('J' . $rows, $val['airline_code'] . '-' . $flightNo);
                $sheet->setCellValue('K' . $rows, display_custom_date_format($val['departure_date']));
                $sheet->setCellValue('L' . $rows, 'Flight');

                $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['BaseFare']['Value']);
                $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
                $sheet->setCellValue('O' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);
                $sheet->setCellValue('P' . $rows, $FareBreakUp['FareBreakup']['MealBaggageCharge']['Value']);


                $sheet->setCellValue('Q' . $rows, $FareBreakUp['FareBreakup']['CommEarned']['Value']);
                $sheet->setCellValue('R' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
                $sheet->setCellValue('S' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


                $sheet->setCellValue('T' . $rows, $TaxableAmount);
                $sheet->setCellValue('U' . $rows, $CGSTAmount);
                $sheet->setCellValue('V' . $rows, $SGSTAmount);
                $sheet->setCellValue('W' . $rows, $IGSTAmount);
                $sheet->setCellValue('X' . $rows, $GST);
                $sheet->setCellValue('Y' . $rows, $FareBreakUp['TotalAmount']['Value']);


                $rows++;
            }
        }
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }


    public function hotel_report($service, $data)
    {
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->hotel_booking_detail($this->web_partner_id,$data);

        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AM1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AM")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);

        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'Hotel Name');
        $sheet->setCellValue('E1', 'Destination');
        $sheet->setCellValue('F1', 'Checkin');
        $sheet->setCellValue('G1', 'Checkout');
        $sheet->setCellValue('H1', 'Booking Reference No');
        $sheet->setCellValue('I1', 'Lead Pax Name');

        $sheet->setCellValue('J1', 'CNF No.');
        $sheet->setCellValue('K1', 'Product');

        $sheet->setCellValue('L1', 'Gross');

        $sheet->setCellValue('M1', 'Comm. Earned');
        $sheet->setCellValue('N1', 'TDS ');

        $sheet->setCellValue('O1', 'Management Fee');
        $sheet->setCellValue('P1', 'CGST');
        $sheet->setCellValue('Q1', 'SGST');
        $sheet->setCellValue('R1', 'IGST');
        $sheet->setCellValue('S1', 'GST');

        $sheet->setCellValue('T1', 'Net AMT.');




        $rows = 2;

        foreach ($BookingDetail as $key => $val) {


            $travelersInfo = json_decode($val['hotel_rooms_details'], true);
            $travelersInfo = $travelersInfo[0]['HotelPassenger'];

            $client_name = $val['company_name'];
            if ($val['is_domestic']) {
                $is_domestic = 'dom';
            } else {
                $is_domestic = 'int';
            }
            $publishedFare = 0;
            $offeredFare = 0;
            $CommEarned = 0;
            $TDS = 0;
            $ApplyDiscount = 0;
            $ApplyMarkup = 0;
            $CGSTAmount = 0;
            $IGSTAmount = 0;
            $SGSTAmount = 0;
            $TaxableAmount = 0;

            $web_partner_fare_break_up = json_decode($val['web_partner_fare_break_up'], true);
            if($val['booking_source']=="Wl_b2b"){
                $AccountTable ="agent_account_log";
                $client_name = $val['company_name'];
                $fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
            }
            if($val['booking_source']=="Wl_b2c"){
                $AccountTable ="customer_account_log";
                $client_name = '';
                $fareBreakupArray = json_decode($val['customer_fare_break_up'], true);
            }
            $InvoiceInfo = $SalesReportModel->getData($AccountTable,['service'=>'hotel','booking_ref_no'=>$val['id'],'action_type'=>'booking'],'invoice_number');
            $invoice_number = (isset($InvoiceInfo['invoice_number']))?$InvoiceInfo['invoice_number']:NULL;
            $GSTDATA = $web_partner_fare_break_up[0]['GST'];
            unset($fareBreakupArray['couponAmount']);
            foreach ($fareBreakupArray as $key => $HotelRooms) {
                if (isset($HotelRooms['GST'])) {
                    $GST = $HotelRooms['GST'];
                    $GSTDATA['CGSTAmount'] = $CGSTAmount + $GST['CGSTAmount'];
                    $GSTDATA['IGSTAmount'] = $IGSTAmount + $GST['IGSTAmount'];
                    $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                    $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                }
                $web_partner_fare_break_up = $web_partner_fare_break_up[$key];
                $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
                $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
                $ApplyDisPlayMarkup = isset($web_partner_fare_break_up['WebPDisplayMarkup']) ? $web_partner_fare_break_up['WebPDisplayMarkup'] : 'in_tax';
                $ApplyMarkup = $ApplyMarkup + $markup;
                $ApplyDiscount = $ApplyDiscount + $discount;
                $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                $TDS = $TDS + $HotelRooms['TDS'];
            }

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "Gross" => array("Value" => custom_money_format(round_value($publishedFare)), "LabelText" => "Gross"),
                    "CommEarned" => array("Value" => custom_money_format(round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                    "TDS" => array("Value" => custom_money_format(round_value($TDS)), "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => custom_money_format(round_value($offeredFare + $TDS)), "LabelText" => "Total Amount"),
                "GSTDetails" => $GSTDATA,
                "WebPMarkUp" => array("Value" => custom_money_format(round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                "WebPDiscount" => array("Value" => custom_money_format(round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
            );
            if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'];
            }
            if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'];
            }
            $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

            $lead_pax_name = null;

            if ($travelersInfo) {
                foreach ($travelersInfo as $pax_key => $traveller) {
                    if ($pax_key == 0) {
                        $lead_pax_name = $traveller['Title'] . ' ' . $traveller['FirstName'] . ' ' . $traveller['LastName'];
                        $lead_pax_pan = $traveller['PAN'];
                        $lead_pax_passport = $traveller['PassportNo'];
                        break;
                    }
                }
            }

            $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
            $sheet->setCellValue('B' . $rows, $invoice_number);
            $sheet->setCellValue('C' . $rows, $client_name);
            $sheet->setCellValue('D' . $rows, $val['hotel_name']);
            $sheet->setCellValue('E' . $rows, $val['city'] . ' / ' . $val['country_code']);
            $sheet->setCellValue('F' . $rows, display_custom_date_format($val['check_in_date']));
            $sheet->setCellValue('G' . $rows, display_custom_date_format($val['check_out_date']));
            $sheet->setCellValue('H' . $rows, $val['booking_ref_number']);
            $sheet->setCellValue('I' . $rows, $lead_pax_name);

            $sheet->setCellValue('J' . $rows, $val['confirmation_no']);
            $sheet->setCellValue('K' . $rows, 'Hotel');


            $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['Gross']['Value']);
            $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['CommEarned']['Value']);
            $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


            $sheet->setCellValue('O' . $rows, $TaxableAmount);
            $sheet->setCellValue('P' . $rows, $CGSTAmount);
            $sheet->setCellValue('Q' . $rows, $SGSTAmount);
            $sheet->setCellValue('R' . $rows, $IGSTAmount);
            $sheet->setCellValue('S' . $rows, $GST);
            $sheet->setCellValue('T' . $rows, $FareBreakUp['TotalAmount']['Value']);


            $rows++;
        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }

    public function holiday_report($service, $data)
    {
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->holiday_booking_list_report($this->web_partner_id,$data);
        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'Package');
        $sheet->setCellValue('E1', 'Duration');
        $sheet->setCellValue('F1', 'Booking Reference No');
        $sheet->setCellValue('G1', 'Pax Name');
        $sheet->setCellValue('H1', 'Travel Date');
        $sheet->setCellValue('I1', 'Product');
        $sheet->setCellValue('J1', 'Basic Fare');
        $sheet->setCellValue('K1', 'Taxes');
        $sheet->setCellValue('L1', 'Ot. & Service Charge');
        $sheet->setCellValue('M1', 'Comm. Earned');
        $sheet->setCellValue('N1', 'Discount');
        $sheet->setCellValue('O1', 'TDS ');
        $sheet->setCellValue('P1', 'Management Fee');
        $sheet->setCellValue('Q1', 'CGST');
        $sheet->setCellValue('R1', 'SGST');
        $sheet->setCellValue('S1', 'IGST');
        $sheet->setCellValue('T1', 'GST');
        $sheet->setCellValue('U1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {


            $web_partner_fare_break_up = json_decode($val['web_partner_fare_break_up'], true);
            if($val['booking_source']=="Wl_b2b"){
                $AccountTable ="agent_account_log";
                $client_name = $val['company_name'];
                $fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
            }
            if($val['booking_source']=="Wl_b2c"){
                $AccountTable ="customer_account_log";
                $client_name = 'NA';
                $fareBreakupArray = json_decode($val['customer_fare_break_up'], true);
            }
            $InvoiceInfo = $SalesReportModel->getData($AccountTable,['service'=>'holiday','web_partner_id'=>$val['web_partner_id'],'booking_ref_no'=>$val['id'],'action_type'=>'booking'],'invoice_number');
            $invoice_number = (isset($InvoiceInfo['invoice_number']))?$InvoiceInfo['invoice_number']:NULL;
           

            $TaxableAmount = null;
            $CGSTAmount = null;
            $SGSTAmount = null;
            $IGSTAmount = null;

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BasePrice" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */

                    /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                    "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                    "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST']
            );

            if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'];
            }
            if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'];
            }
            $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;


            $lead_pax_name = ucfirst($val['title']) . ' ' . ucfirst($val['first_name']) . ' ' . ucfirst($val['last_name']);;


            $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
            $sheet->setCellValue('B' . $rows, $invoice_number);
            $sheet->setCellValue('C' . $rows, $client_name);
            $sheet->setCellValue('D' . $rows, $val['package_name']);
            $sheet->setCellValue('E' . $rows, $val['day_nights']);
            $sheet->setCellValue('F' . $rows, $val['booking_ref_number']);
            $sheet->setCellValue('G' . $rows, $lead_pax_name);
            $sheet->setCellValue('H' . $rows, $val['travel_date']);
            $sheet->setCellValue('I' . $rows, 'Holiday');
            $sheet->setCellValue('J' . $rows, $FareBreakUp['FareBreakup']['BasePrice']['Value']);
            $sheet->setCellValue('K' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
            $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);
            $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['CommEarned']['Value']);
            $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
            $sheet->setCellValue('O' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);
            $sheet->setCellValue('P' . $rows, $TaxableAmount);
            $sheet->setCellValue('Q' . $rows, $CGSTAmount);
            $sheet->setCellValue('R' . $rows, $SGSTAmount);
            $sheet->setCellValue('S' . $rows, $IGSTAmount);
            $sheet->setCellValue('T' . $rows, $GST);
            $sheet->setCellValue('U' . $rows, $FareBreakUp['TotalAmount']['Value']);
            $rows++;
        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }

    public function visa_report($service, $data)
    {
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->visa_booking_list_report($this->web_partner_id,$data);
        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'Country');
        $sheet->setCellValue('E1', 'Visa Type');
        $sheet->setCellValue('F1', 'Booking Reference No');
        $sheet->setCellValue('G1', 'Lead Pax Name');

        $sheet->setCellValue('H1', 'Travel Date');
        $sheet->setCellValue('I1', 'Product');

        $sheet->setCellValue('J1', 'Basic Fare');
        $sheet->setCellValue('K1', 'Taxes');
        $sheet->setCellValue('L1', 'Ot. & Service Charge');


        $sheet->setCellValue('M1', 'Discount');
        $sheet->setCellValue('N1', 'TDS ');

        $sheet->setCellValue('O1', 'Management Fee');
        $sheet->setCellValue('P1', 'CGST');
        $sheet->setCellValue('Q1', 'SGST');
        $sheet->setCellValue('R1', 'IGST');
        $sheet->setCellValue('S1', 'GST');

        $sheet->setCellValue('T1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {


            $acc_ref_number = $val['acc_ref_number'];
            $invoice_number = $val['invoice_number'];
            $client_name = $val['company_name'];

            $fareBreakupArray = json_decode($val['web_partner_fare_break_up'], true);
            $booking_fareBreakupArray_gst = null;
            if (isset($fareBreakupArray['GST'])) {
                $booking_fareBreakupArray_gst = $fareBreakupArray['GST'];
            }


            $TaxableAmount = null;
            $CGSTAmount = null;
            $SGSTAmount = null;
            $IGSTAmount = null;
            $TotalGST = null;
            $AgentCommission = null;
            $Discount = null;
            $TDS = null;
            $OfferedPrice = null;
            $OtherCharges = null;
            $ServiceCharges = null;

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BasePrice" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */

                    /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                    /*"CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),*/
                    "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST']
            );

            if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'];
            }
            if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'];
            }
            $lead_pax_ticket_number = null;

            $lead_pax_name = ucfirst($val['title']) . ' ' . ucfirst($val['first_name']) . ' ' . ucfirst($val['last_name']);;
            $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

            $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
            $sheet->setCellValue('B' . $rows, $invoice_number);
            $sheet->setCellValue('C' . $rows, $client_name);
            $sheet->setCellValue('D' . $rows, $val['visa_country']);
            $sheet->setCellValue('E' . $rows, $val['visa_type']);
            $sheet->setCellValue('F' . $rows, $val['booking_ref_number']);
            $sheet->setCellValue('G' . $rows, $lead_pax_name);
            $sheet->setCellValue('H' . $rows, $val['date_of_journey']);
            $sheet->setCellValue('I' . $rows, 'Visa');


            $sheet->setCellValue('J' . $rows, $FareBreakUp['FareBreakup']['BasePrice']['Value']);
            $sheet->setCellValue('K' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
            $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);


            $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
            $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


            $sheet->setCellValue('O' . $rows, $TaxableAmount);
            $sheet->setCellValue('P' . $rows, $CGSTAmount);
            $sheet->setCellValue('Q' . $rows, $SGSTAmount);
            $sheet->setCellValue('R' . $rows, $IGSTAmount);
            $sheet->setCellValue('S' . $rows, $GST);
            $sheet->setCellValue('T' . $rows, $FareBreakUp['TotalAmount']['Value']);


            $rows++;

        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }

    public function car_report($service, $data)
    {
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->car_booking_list_report($this->web_partner_id,$data);


        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'Car');
        $sheet->setCellValue('E1', 'Origin');

        $sheet->setCellValue('F1', 'Destination');
        $sheet->setCellValue('G1', 'Booking Reference No');
        $sheet->setCellValue('H1', 'Pax Name');

        $sheet->setCellValue('I1', 'Departure Date');
        $sheet->setCellValue('J1', 'Product');

        $sheet->setCellValue('K1', 'Basic Fare');
        $sheet->setCellValue('L1', 'Taxes');
        $sheet->setCellValue('M1', 'Ot. & Service Charge');


        $sheet->setCellValue('N1', 'Comm. Earned');
        $sheet->setCellValue('O1', 'Discount');
        $sheet->setCellValue('P1', 'TDS ');

        $sheet->setCellValue('Q1', 'Management Fee');
        $sheet->setCellValue('R1', 'CGST');
        $sheet->setCellValue('S1', 'SGST');
        $sheet->setCellValue('T1', 'IGST');
        $sheet->setCellValue('U1', 'GST');

        $sheet->setCellValue('V1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {

            $flight_travellers_list = $SalesReportModel->flight_travellers_list($val['id']);


            $acc_ref_number = $val['acc_ref_number'];
            $invoice_number = $val['invoice_number'];
            $client_name = $val['company_name'];

            $fareBreakupArray = json_decode($val['web_partner_fare_break_up'], true);
            $booking_fareBreakupArray_gst = null;
            if (isset($fareBreakupArray['GST'])) {
                $booking_fareBreakupArray_gst = $fareBreakupArray['GST'];
            }


            $TaxableAmount = null;
            $CGSTAmount = null;
            $SGSTAmount = null;
            $IGSTAmount = null;
            $TotalGST = null;
            $AgentCommission = null;
            $Discount = null;
            $TDS = null;
            $OfferedPrice = null;
            $OtherCharges = null;
            $ServiceCharges = null;

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BasePrice" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */

                    /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                    "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                    "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST']
            );

            if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'];
            }
            if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'];
            }
            $lead_pax_ticket_number = null;

            $lead_pax_name = ucfirst($val['title']) . ' ' . ucfirst($val['first_name']) . ' ' . ucfirst($val['last_name']);;
            $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

            $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
            $sheet->setCellValue('B' . $rows, $invoice_number);
            $sheet->setCellValue('C' . $rows, $client_name);
            $sheet->setCellValue('D' . $rows, $val['car_name']);
            $sheet->setCellValue('E' . $rows, $val['source']);
            $sheet->setCellValue('F' . $rows, $val['destination']);
            $sheet->setCellValue('G' . $rows, $val['booking_ref_number']);
            $sheet->setCellValue('H' . $rows, $lead_pax_name);
            $sheet->setCellValue('I' . $rows, $val['departure_date']);
            $sheet->setCellValue('J' . $rows, 'Car');


            $sheet->setCellValue('K' . $rows, $FareBreakUp['FareBreakup']['BasePrice']['Value']);
            $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
            $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);


            $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['CommEarned']['Value']);
            $sheet->setCellValue('O' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
            $sheet->setCellValue('P' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


            $sheet->setCellValue('Q' . $rows, $TaxableAmount);
            $sheet->setCellValue('R' . $rows, $CGSTAmount);
            $sheet->setCellValue('S' . $rows, $SGSTAmount);
            $sheet->setCellValue('T' . $rows, $IGSTAmount);
            $sheet->setCellValue('U' . $rows, $GST);
            $sheet->setCellValue('V' . $rows, $FareBreakUp['TotalAmount']['Value']);


            $rows++;

        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }

    public function bus_report($service, $data)
    {

        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->bus_booking_list_report($this->web_partner_id,$data);


        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'PNR');
        $sheet->setCellValue('E1', 'Ticket No');
        $sheet->setCellValue('F1', 'Booking Reference No');
        $sheet->setCellValue('G1', 'Pax Name');
        $sheet->setCellValue('H1', 'Bus Type');
        $sheet->setCellValue('I1', 'Sector');

        $sheet->setCellValue('J1', 'Departure Date');
        $sheet->setCellValue('K1', 'Product');

        $sheet->setCellValue('L1', 'Basic Fare');
        $sheet->setCellValue('M1', 'Taxes');
        $sheet->setCellValue('N1', 'Ot. & Service Charge');


        $sheet->setCellValue('O1', 'Comm. Earned');
        $sheet->setCellValue('P1', 'Discount');
        $sheet->setCellValue('Q1', 'TDS ');

        $sheet->setCellValue('R1', 'Management Fee');
        $sheet->setCellValue('S1', 'CGST');
        $sheet->setCellValue('T1', 'SGST');
        $sheet->setCellValue('U1', 'IGST');
        $sheet->setCellValue('V1', 'GST');

        $sheet->setCellValue('W1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {

            $bus_travellers_list = $SalesReportModel->bus_travellers_list($val['id']);

            $traveller_count = count($bus_travellers_list);

            foreach ($bus_travellers_list as $key => $traveller) {

                $CorporateGSTNumber = null;

                if($val['booking_source']=="Wl_b2b"){
                    $AccountTable ="agent_account_log";
                    $client_name = $val['company_name'];
                    $booking_fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
                    $fareBreakupArray = json_decode($traveller['agent_fare'], true);
                }
                if($val['booking_source']=="Wl_b2c"){
                    $AccountTable ="customer_account_log";
                    $client_name = '';
                    $booking_fareBreakupArray = json_decode($val['customer_fare_break_up'], true);
                    $fareBreakupArray = json_decode($traveller['customer_fare'], true);
                }
                $InvoiceInfo = $SalesReportModel->getData($AccountTable,['service'=>'bus','booking_ref_no'=>$val['id'],'action_type'=>'booking'],'invoice_number');
                $invoice_number = (isset($InvoiceInfo['invoice_number']))?$InvoiceInfo['invoice_number']:NULL;
                $booking_fareBreakupArray_gst = null;
                if (isset($booking_fareBreakupArray['GST'])) {
                    $booking_fareBreakupArray_gst = $booking_fareBreakupArray['GST'];
                }

                $TaxableAmount = 0;
                $CGSTAmount = 0;
                $SGSTAmount = 0;
                $IGSTAmount = 0;
                $TotalGST = 0;
                $AgentCommission = 0;
                $Discount = 0;
                $TDS = 0;
                $OfferedPrice = 0;
                $OtherCharges = 0;
                $ServiceCharges = 0;
                if (isset($fareBreakupArray['AgentCommission'])) {
                    $AgentCommission = $fareBreakupArray['AgentCommission'];
                }
                if (isset($fareBreakupArray['Discount'])) {
                    $Discount = $fareBreakupArray['Discount'];
                }

                if (isset($fareBreakupArray['TDS'])) {
                    $TDS = $fareBreakupArray['TDS'];
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

                $FareBreakUp = array(
                    "FareBreakup" => array(
                        "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Fare"),
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                        "ServiceAndOtherCharge" => array("Value" => $OtherCharges + $ServiceCharges, "LabelText" => "Other & Service Charges"),

                        "CommEarned" => array("Value" => $AgentCommission, "LabelText" => "Comm Earned (-)"),
                        "Discount" => array("Value" => $Discount, "LabelText" => "Discount (-)"),
                        "TDS" => array("Value" => $TDS, "LabelText" => "TDS (+)")
                    ),
                    "TotalAmount" => array("Value" => $TDS + $OfferedPrice, "LabelText" => "Total Amount"),
                    "GSTDetails" => $booking_fareBreakupArray_gst
                );

                if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                    $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'] / $traveller_count;
                }

                if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                    $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'] / $traveller_count;
                }

                if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                    $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'] / $traveller_count;
                }
                if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                    $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'] / $traveller_count;
                }


                $lead_pax_name = $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'];
                $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

                $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
                $sheet->setCellValue('B' . $rows, $invoice_number);
                $sheet->setCellValue('C' . $rows, $client_name);
                $sheet->setCellValue('D' . $rows, $val['travel_operator_pnr']);
                $sheet->setCellValue('E' . $rows, $val['ticket_no']);
                $sheet->setCellValue('F' . $rows, $val['booking_ref_number']);
                $sheet->setCellValue('G' . $rows, $lead_pax_name);
                $sheet->setCellValue('H' . $rows, $val['bus_type']);
                $sheet->setCellValue('I' . $rows, $val['origin_city'] . '-' . $val['destination_city']);

                $sheet->setCellValue('J' . $rows, display_custom_date_format($val['date_of_journey']));
                $sheet->setCellValue('K' . $rows, 'Bus');

                $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['BaseFare']['Value']);
                $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
                $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);


                $sheet->setCellValue('O' . $rows, $FareBreakUp['FareBreakup']['CommEarned']['Value']);
                $sheet->setCellValue('P' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
                $sheet->setCellValue('Q' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


                $sheet->setCellValue('R' . $rows, $TaxableAmount);
                $sheet->setCellValue('S' . $rows, $CGSTAmount);
                $sheet->setCellValue('T' . $rows, $SGSTAmount);
                $sheet->setCellValue('U' . $rows, $IGSTAmount);
                $sheet->setCellValue('V' . $rows, $GST);
                $sheet->setCellValue('W' . $rows, $FareBreakUp['TotalAmount']['Value']);


                $rows++;
            }
        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;

    }

    public function cruise_report($service,$data){
        $SalesReportModel = new SalesReportModel();
        $BookingDetail = $SalesReportModel->cruise_booking_list_report($this->web_partner_id,$data);


        $fileName = $service . '-Sales-Report' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:AS1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:AS")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);


        $sheet->setCellValue('A1', 'Invoice Date');
        $sheet->setCellValue('B1', 'Invoice');
        $sheet->setCellValue('C1', 'Company Name');
        $sheet->setCellValue('D1', 'Cruise Line');
        $sheet->setCellValue('E1', 'Ship Name');
        $sheet->setCellValue('F1', 'Booking Reference No');
        $sheet->setCellValue('G1', 'Lead Pax Name');

        $sheet->setCellValue('H1', 'Travel Date');
        $sheet->setCellValue('I1', 'Product');

        $sheet->setCellValue('J1', 'Basic Fare');
        $sheet->setCellValue('K1', 'Taxes');
        $sheet->setCellValue('L1', 'Ot. & Service Charge');


        $sheet->setCellValue('M1', 'Discount');
        $sheet->setCellValue('N1', 'TDS ');

        $sheet->setCellValue('O1', 'Management Fee');
        $sheet->setCellValue('P1', 'CGST');
        $sheet->setCellValue('Q1', 'SGST');
        $sheet->setCellValue('R1', 'IGST');
        $sheet->setCellValue('S1', 'GST');

        $sheet->setCellValue('T1', 'Net AMT.');



        $rows = 2;

        foreach ($BookingDetail as $key => $val) {


            $acc_ref_number = $val['acc_ref_number'];
            $invoice_number = $val['invoice_number'];
            $client_name = $val['company_name'];

            $fareBreakupArray = json_decode($val['web_partner_fare_break_up'], true);
            $booking_fareBreakupArray_gst = null;
            if (isset($fareBreakupArray['GST'])) {
                $booking_fareBreakupArray_gst = $fareBreakupArray['GST'];
            }


            $TaxableAmount = null;
            $CGSTAmount = null;
            $SGSTAmount = null;
            $IGSTAmount = null;
            $TotalGST = null;
            $AgentCommission = null;
            $Discount = null;
            $TDS = null;
            $OfferedPrice = null;
            $OtherCharges = null;
            $ServiceCharges = null;

            $FareBreakUp = array(
                "FareBreakup" => array(
                    "BasePrice" => array("Value" => $fareBreakupArray['TTSBreakup']['BasePrice'], "LabelText" => "Base Price"),
                    "Taxes" => array("Value" => $fareBreakupArray['TTSBreakup']['Tax'], "LabelText" => "Taxes"),
                    "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['TTSBreakup']['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                    /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */

                    /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                    /*"CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),*/
                    "Discount" => array("Value" => $fareBreakupArray['TTSBreakup']['Discount'], "LabelText" => "Discount (-)"),
                    "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                ),
                "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['TTSBreakup']['OfferedPrice'], "LabelText" => "Total Amount"),
                "GSTDetails" => $fareBreakupArray['GST']
            );

            if (isset($FareBreakUp['GSTDetails']['TaxableAmount'])) {
                $TaxableAmount = $FareBreakUp['GSTDetails']['TaxableAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['CGSTAmount'])) {
                $CGSTAmount = $FareBreakUp['GSTDetails']['CGSTAmount'];
            }

            if (isset($FareBreakUp['GSTDetails']['SGSTAmount'])) {
                $SGSTAmount = $FareBreakUp['GSTDetails']['SGSTAmount'];
            }
            if (isset($FareBreakUp['GSTDetails']['IGSTAmount'])) {
                $IGSTAmount = $FareBreakUp['GSTDetails']['IGSTAmount'];
            }
            $lead_pax_ticket_number = null;

            $lead_pax_name = ucfirst($val['title']) . ' ' . ucfirst($val['first_name']) . ' ' . ucfirst($val['last_name']);;
            $GST = $CGSTAmount + $SGSTAmount + $IGSTAmount;

            $sheet->setCellValue('A' . $rows, date_created_format($val['created']));
            $sheet->setCellValue('B' . $rows, $invoice_number);
            $sheet->setCellValue('C' . $rows, $client_name);
            $sheet->setCellValue('D' . $rows, $val['cruise_line_name']);
            $sheet->setCellValue('E' . $rows, $val['ship_name']);
            $sheet->setCellValue('F' . $rows, $val['booking_ref_number']);
            $sheet->setCellValue('G' . $rows, $lead_pax_name);
            $sheet->setCellValue('H' . $rows, $val['sailing_date']);
            $sheet->setCellValue('I' . $rows, 'Cruise');


            $sheet->setCellValue('J' . $rows, $FareBreakUp['FareBreakup']['BasePrice']['Value']);
            $sheet->setCellValue('K' . $rows, $FareBreakUp['FareBreakup']['Taxes']['Value']);
            $sheet->setCellValue('L' . $rows, $FareBreakUp['FareBreakup']['ServiceAndOtherCharge']['Value']);


            $sheet->setCellValue('M' . $rows, $FareBreakUp['FareBreakup']['Discount']['Value']);
            $sheet->setCellValue('N' . $rows, $FareBreakUp['FareBreakup']['TDS']['Value']);


            $sheet->setCellValue('O' . $rows, $TaxableAmount);
            $sheet->setCellValue('P' . $rows, $CGSTAmount);
            $sheet->setCellValue('Q' . $rows, $SGSTAmount);
            $sheet->setCellValue('R' . $rows, $IGSTAmount);
            $sheet->setCellValue('S' . $rows, $GST);
            $sheet->setCellValue('T' . $rows, $FareBreakUp['TotalAmount']['Value']);


            $rows++;

        }

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
        return $data_validation;
    }
}
