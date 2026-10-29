<?php

namespace Modules\WebPartnerAccount\Controllers;

use App\Modules\WebPartnerAccount\Models\WebPartnerAccountModel;
use App\Modules\WebPartnerAccount\Models\WebPartnerAccountLogModel;
use App\Modules\WebPartnerAccount\Models\AgentAccountLogModel;
use App\Modules\WebPartnerAccount\Models\CustomerAccountLogModel;
use App\Modules\WebPartnerAccount\Models\WebPartnerModel;
use App\Controllers\BaseController;
use Modules\WebPartnerAccount\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WebPartnerAccount extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "WebPartnerAccount";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
    }

    public function index()
    {
        $WebPartnerAccountModel = new WebPartnerAccountModel();
        $webpartnerInfo = array();
        if ($this->request->getGet() && $this->request->getGet('key-value') && $this->request->getGet('key-value') != "") {
            $webpartnerInfo = $WebPartnerAccountModel->search_webpartner($this->request->getGet());
        }
        $data = [
            'title' => $this->title,
            'view' => "WebPartnerAccount\Views\index",
            'webpartnerInfo' => $webpartnerInfo,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);
    }




    public function web_partner_account_logs()
    {

        $account_logs = array();
        $webpartnerDetail = array();
        $balance = 0;
        $pager = array();
        $searchData = $this->request->getGet();
        if ($this->request->getGet() && $this->request->getGet('from_date') && $this->request->getGet('to_date')) {

            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }

            $account_logs = $WebPartnerAccountLogModel->account_logs($web_partner_id, $searchData);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        } else {
            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }
            $account_logs = $WebPartnerAccountLogModel->account_logs_without_serach($web_partner_id);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        }
        if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
            WebPartnerAccount::export_web_partner_account_logs($account_logs, $webpartnerDetail);
        }
        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $webpartnerDetail,
            'view' => "WebPartnerAccount\Views\web-partner-account-logs-list",
            'pager' => $pager,
            'available_balance' => $balance,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function export_web_partner_account_logs($account_logs, $webpartnerDetail)
    {

        $fileName = $webpartnerDetail['company_name'] . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:I1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:I")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('j')->setAutoSize(true);

        $sheet->setCellValue('A1', 'Ref.No.');
        $sheet->setCellValue('B1', 'Booking  CNF. No');
        $sheet->setCellValue('C1', 'Remark');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Debit');
        $sheet->setCellValue('F1', 'Balance');
        $sheet->setCellValue('G1', 'Payments Type');
        /* $sheet->setCellValue('H1', 'Transaction Type');
        $sheet->setCellValue('I1', 'Action Type'); */
        $sheet->setCellValue('H1', 'Created date');
        $rows = 2;
        foreach ($account_logs as $key => $val) {
            $prefix_booking_ref_number = '';

            $booking_id = $val['booking_ref_no'];
            if ($val['service'] == 'flight') {
                $prefix_booking_ref_number = $val['flight_booking_ref_number'];
            }
            if ($val['service'] == 'hotel') {
                $prefix_booking_ref_number = $val['hotel_booking_ref_number'];
            }

            if ($val['service'] == 'bus') {
                $prefix_booking_ref_number = $val['bus_booking_ref_number'];
            }

            if ($val['service'] == 'holiday') {
                $prefix_booking_ref_number = $val['holiday_booking_ref_number'];
            }

            if ($val['service'] == 'visa') {
                $prefix_booking_ref_number = $val['visa_booking_ref_number'];
            }

            if ($val['service'] == 'cruise') {
                $prefix_booking_ref_number = $val['cruise_booking_ref_number'];
            }

            if ($val['service'] == 'car') {
                $prefix_booking_ref_number = $val['car_booking_ref_number'];
            }

            if ($val['action_type'] == 'booking') {
                if ($val['service_log']) {
                    $service_log = json_decode($val['service_log'], true);

                    $remark = mb_convert_encoding(service_log_excel($val['service'], $val['action_type'], $service_log), "UTF-8", "EUC-JP");
                } else {
                    $remark = ucfirst($val['service']) . ' ' . ucfirst($val['action_type']);
                }
            } else {
                $remark = ucfirst($val['action_type']);
            }
            $transaction_id = '';
            if (isset($val['transaction_id'])) {
                $transaction_id = '-' . $val['transaction_id'];
            }

            $transaction_type  =  ucfirst($val['action_type']) . "-";
            $transaction_type =  $transaction_type . $val['payment_mode'] != "" ?  $val['payment_mode'] . $transaction_id . "" : "Wallet";

            $sheet->setCellValue('A' . $rows, $prefix_booking_ref_number);
            $sheet->setCellValue('B' . $rows, $val['booking_confirmation_number']);
            $sheet->setCellValue('C' . $rows, $remark . "/" . $val['remark']);
            $sheet->setCellValue('D' . $rows, $val['credit']);
            $sheet->setCellValue('E' . $rows, $val['debit']);
            $sheet->setCellValue('F' . $rows, $val['balance']);
            $sheet->setCellValue('G' . $rows, $transaction_type);
            /* $sheet->setCellValue('G' . $rows, $val['transaction_type']);
            $sheet->setCellValue('H' . $rows, $val['action_type']); */
            $sheet->setCellValue('H' . $rows, date_created_format($val['created']));
            $rows++;
        }
        ob_start();
        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        header("Cache-Control: max-age=0");
        header("Expires:0");

        $writer->save('php://output');
        exit(0);
    }


    public function web_partner_debit_logs()
    {
        $account_logs = array();
        $webpartnerDetail = array();
        $balance = 0;
        $pager = array();
        $searchData = $this->request->getGet();
        if ($this->request->getGet() && $this->request->getGet('from_date') && $this->request->getGet('to_date')) {

            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }

            $account_logs = $WebPartnerAccountLogModel->debit_account_logs($web_partner_id, $searchData);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        } else {
            $WebPartnerModel = new WebPartnerModel();
            $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
            $web_partner_id = $this->web_partner_id;
            $available_balance = $WebPartnerAccountLogModel->available_balance($web_partner_id);
            if (isset($available_balance['balance'])) {
                $balance = $available_balance['balance'];
            }
            $account_logs = $WebPartnerAccountLogModel->debit_account_logs_without_serach($web_partner_id);
            $webpartnerDetail = $WebPartnerModel->web_partner_list_details($web_partner_id);
            $pager = $WebPartnerAccountLogModel->pager;
        }

        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $webpartnerDetail,
            'view' => "WebPartnerAccount\Views\web-partner-debit-logs-list",
            'pager' => $pager,
            'available_balance' => $balance,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }


    public function flight_credit_notes_Pre()
    {
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes($this->web_partner_id);
        }


        $pager = $WebPartnerAccountLogModel->pager;
        $data = [
            'title' => $this->title,
            'account_logs' => $credit_notes,
            'view' => "WebPartnerAccount\Views\Flight-credit-notes-list",
            'pager' => $pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }



    public function credit_notes_hotel()
    {
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_hotel_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_hotel($this->web_partner_id,);
        }

        $pager = $WebPartnerAccountLogModel->pager;

        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views\hotel-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Hotel'
        ];

        return view('template/sidebar-layout', $data);
    }


    public function credit_notes_holiday()
    {   
        $getData = $this->request->getGet();
        if(isset($getData['booking_source']) && $getData['booking_source'] == "B2C"){
            $AccountLogModel = new CustomerAccountLogModel();
        }else{
            $AccountLogModel = new AgentAccountLogModel();
        }

        if ($this->request->getGet()) {
            $credit_notes = $AccountLogModel->credit_notes_holiday_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $AccountLogModel->credit_notes_holiday($this->web_partner_id);
        }
        $pager = $AccountLogModel->pager;
        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views\holiday-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Holiday'
        ];


        return view('template/sidebar-layout', $data);
    }

    public function credit_notes_visa()
    {
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_visa_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_visa($this->web_partner_id,);
        }

        $pager = $WebPartnerAccountLogModel->pager;
        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views/visa-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Visa'
        ];
        return view('template/sidebar-layout', $data);
    }

    public function credit_notes_car()
    {
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_car_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_car($this->web_partner_id,);
        }

        $pager = $WebPartnerAccountLogModel->pager;
        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views/car-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Car'
        ];
        return view('template/sidebar-layout', $data);
    }


    public function credit_notes_bus()
    {
        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_bus_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_bus($this->web_partner_id,);
        }

        $pager = $WebPartnerAccountLogModel->pager;
        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views/bus-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Bus'
        ];
        return view('template/sidebar-layout', $data);
    }

    public function credit_notes_cruise()
    {

        $WebPartnerAccountLogModel = new WebPartnerAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_cruise_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $WebPartnerAccountLogModel->credit_notes_cruise($this->web_partner_id);
        }

        $pager = $WebPartnerAccountLogModel->pager;
        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views/cruise-credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Cruise'
        ];
        return view('template/sidebar-layout', $data);
    }


    public function credit_notes()
    {

        $AgentAccountLogModel = new AgentAccountLogModel();

        if ($this->request->getGet()) {
            $credit_notes = $AgentAccountLogModel->credit_notes_search($this->web_partner_id, $this->request->getGet());
        } else {
            $credit_notes = $AgentAccountLogModel->credit_notes($this->web_partner_id,);
        }

        $pager = $AgentAccountLogModel->pager;

        $booking_data = [
            'account_logs' => $credit_notes,
            'search_bar_data' => $this->request->getGet(),
            'pager' => $pager,

        ];
        $html_view = view("Modules\WebPartnerAccount\Views\credit-notes-list", $booking_data);

        $data = [
            'title' => $this->title,
            'html_view' => $html_view,
            'view' => "WebPartnerAccount\Views\credit-index",
            'service' => 'Flight'
        ];
        return view('template/sidebar-layout', $data);
    }
}
