<?php

namespace Modules\Invoice\Controllers;

use App\Modules\Invoice\Models\InvoiceModel;

use App\Controllers\BaseController;
use Modules\Invoice\Config\Validation;


class Invoice extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Invoice";

        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_agent_details = session()->get('wl_agent');
        $this->user_id = session()->get('wl_agent')['id'];
        $this->wl_agent_id = session()->get('wl_agent')['agent_id'];
        $this->wl_agent_class_id = $this->wl_agent_details['agent_class'];
        $this->booking_source = "Wl_b2b";
        $this->Services = API_REQUEST_URL . '/invoiceservice/rest/';;
        helper('Modules\Invoice\Helpers\invoice');
    }

    public function index()
    {

        $getData = "";
        $html_view = "";
        $getData = $this->request->getGet();
        $data = [
            'title' => $this->title,
            "search_bar_data" => $getData,
            'view' => "Invoice\Views\index",
        ];
        return view('template/default-layout', $data);
    }

    public function getReport()
    {
        $data = $this->request->getPost();
        $rules = $this->validate([
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
            $from_date = $data['from_date'].'00:00';
            $to_date = $data['to_date'].'23:59';
            $diff = invoice_date_diff($from_date, $to_date);
            if ($diff >= 0 && $diff <= 15) {
                $TicketViewRequest = array(
                    "UserType" => "Agent",
                    "Service" => ['flight', 'hotel', 'bus'],
                    "WebPartnerId" => $this->web_partner_id,
                    "FromDate" => strtotime($from_date),
                    "ToDate" => strtotime($to_date),
                    "WlAgentId" =>  $this->wl_agent_id,
                    "WlAgentUserId" => $this->user_id,
                    "WithAgencyDetail" => 1,
                    "ViewService" => "Pdf"
                );
                $url = $this->Services . 'generate-wl-invoice';
                $response = RequestWithoutAuth($TicketViewRequest, $url);
               // echo $response;exit;
                if (isset($response['Error']['ErrorCode']) && $response['Error']['ErrorCode'] == 0 && isset($response['Result']['Url'])) {
                    $RedirectUrl = $response['Result']['Url'];
                    $PdfName = $response['Result']['PdfName'];
                    $message = array("StatusCode" => 5, "Message" => "File Generate Successfully", "file" => $RedirectUrl,"Class" => "success_popup", "filename" => $PdfName);
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }else{
                    $message = array("StatusCode" => 2, "Message" => "No Data Found", "Class" => "error_popup", "Reload" => "true");
                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => "Days Difference must be between 15 days", "Class" => "error_popup", "Reload" => "true");
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }
}
