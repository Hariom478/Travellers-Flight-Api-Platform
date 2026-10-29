<?php

namespace Modules\AgentAccount\Controllers;

use App\Modules\AgentAccount\Models\AgentAccountModel;
use App\Modules\AgentAccount\Models\AgentAccountLogModel;

use App\Controllers\BaseController;
use Modules\WebPartnerAccount\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AgentAccount extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "AgentAccount";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->user_id = session()->get('wl_agent')['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_detail = session()->get('wl_agent');

    }


    public function web_partner_account_logs()
    {

        $AgentAccountLogModel = new AgentAccountLogModel();

        if ($this->request->getGet() || $this->request->getGet('from_date') && $this->request->getGet('to_date') || $this->request->getGet('key-value') != "") {
            $searchData = $this->request->getGet();

            $web_partner_id = $this->web_partner_id;
            $account_logs = $AgentAccountLogModel->account_logs($this->wl_agent_id,$web_partner_id, $searchData);

            $pager = $AgentAccountLogModel->pager;
        } else {


            $web_partner_id = $this->web_partner_id;
            $account_logs = $AgentAccountLogModel->account_logs($this->wl_agent_id,$web_partner_id,[]);
            $pager = $AgentAccountLogModel->pager;
        }
        if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
            AgentAccount::export_web_partner_account_logs($account_logs, $this->wl_agent_detail);
        }
        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $this->wl_agent_detail,
            'view' => "AgentAccount\Views\agent-account-logs-list",
            'pager' => $pager,
            'available_balance' => wl_agent_get_balance(),
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);

    }

    public function export_web_partner_account_logs($account_logs, $AgentDetail)
    {

        $fileName = $AgentDetail['company_name'] . "." . 'xlsx';
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

        $sheet->setCellValue('A1', 'Ref. Number.');
        $sheet->setCellValue('B1', 'Remark');
        $sheet->setCellValue('C1', 'Credit');
        $sheet->setCellValue('D1', 'Debit');
        $sheet->setCellValue('E1', 'Balance');
        $sheet->setCellValue('F1', 'Mode');
        $sheet->setCellValue('G1', 'Transaction Type');
        $sheet->setCellValue('H1', 'Action Type');
        $sheet->setCellValue('I1', 'Created date');
        $rows = 2;
        foreach ($account_logs as $key => $val) {
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
            $sheet->setCellValue('A' . $rows, $val['acc_ref_number']);
            $sheet->setCellValue('B' . $rows, $remark . "/" . $val['remark']);
            $sheet->setCellValue('C' . $rows, $val['credit']);
            $sheet->setCellValue('D' . $rows, $val['debit']);
            $sheet->setCellValue('E' . $rows, $val['balance']);
            $sheet->setCellValue('F' . $rows, $val['payment_mode']);
            $sheet->setCellValue('G' . $rows, $val['transaction_type']);
            $sheet->setCellValue('H' . $rows, $val['action_type']);
            $sheet->setCellValue('I' . $rows, date_created_format($val['created']));
            $rows++;
        }
        ob_start();
        ob_end_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        header("Cache-Control: max-age=0");
        $writer->save('php://output');

    }

    public function web_partner_debit_logs(){
        $AgentAccountLogModel = new AgentAccountLogModel();

        if ($this->request->getGet() || $this->request->getGet('from_date') && $this->request->getGet('to_date') || $this->request->getGet('key-value') != "") {
            $searchData = $this->request->getGet();

            $web_partner_id = $this->web_partner_id;
            $account_logs = $AgentAccountLogModel->debit_account_logs($this->wl_agent_id,$web_partner_id, $searchData);

            $pager = $AgentAccountLogModel->pager;
        } else {


            $web_partner_id = $this->web_partner_id;
            $account_logs = $AgentAccountLogModel->debit_account_logs($this->wl_agent_id,$web_partner_id,[]);
            $pager = $AgentAccountLogModel->pager;
        }
        if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
            AgentAccount::export_web_partner_account_logs($account_logs, $this->wl_agent_detail);
        }
        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $this->wl_agent_detail,
            'view' => "AgentAccount\Views\agent-debit-logs-list",
            'pager' => $pager,
            'available_balance' => wl_agent_get_balance(),
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);
    }

    public function view_remark()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $AgentAccountLogModel = new AgentAccountLogModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'data' => $AgentAccountLogModel->view_remark_detail($id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\AgentAccount\Views\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }


    public function flight_credit_notes()
    {
        //$WebPartnerAccountLogModel = new WebPartnerAccountLogModel();
        $AgentAccountLogModel = new AgentAccountLogModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $credit_notes = $AgentAccountLogModel->credit_notes_search($this->web_partner_id,$this->wl_agent_id, $this->request->getGet());
        } else {
            $credit_notes = $AgentAccountLogModel->credit_notes($this->web_partner_id,$this->wl_agent_id);
        }


        $pager = $AgentAccountLogModel->pager;
        $data = [
            'title' => $this->title,
            'account_logs' => $credit_notes,
            'view' => "AgentAccount\Views\Flight-credit-notes-list",
            'pager' => $pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/default-layout', $data);
    }

}
