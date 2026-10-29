<?php

namespace Modules\Query\Controllers;

use App\Modules\Query\Models\QueryModel;

use App\Controllers\BaseController;
use Modules\Query\Config\Validation;

use PhpParser\Node\Expr\PreDec;
require 'vendor/excel/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Query extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Query"; 
       

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

    }

    public function index()
    {
        $QueryModel = new QueryModel();
        $data = [
            'title' => $this->title,
            'list' => $QueryModel->query_list($this->web_partner_id),
            'view' => "Query\Views\query-list",
            'pager' => $QueryModel->pager
        ];
        return view('template/sidebar-layout', $data);
    }
 

    public function remove_query()
    {
        $QueryModel = new QueryModel();
        $ids = $this->request->getPost('checklist');  
 
        foreach ($ids as $id) {
            
            $delete = $QueryModel->remove_query($id,$this->web_partner_id);
        }

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Query  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Query  not Deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        
    } 



    public function export_query()
    {
       /*  if (permission_access("Agent", "agent_export")) { */
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
            }

            $fileName = 'contact-us.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:F1")->getFont()->setBold(true)->setName('Arial')->setSize(11); 
            $sheet->getStyle("A:U")->getFont()->setName('Arial')->setSize(11);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true); 
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true); 
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->setCellValue('A1', 'Sr. No.');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Mobile Number');
            $sheet->setCellValue('E1', 'Subject');
            $sheet->setCellValue('F1', 'modified');
            $sheet->setCellValue('G1', 'Created Date');
            $rows = 2;
            $QueryModel = new QueryModel(); 
            $Query_excel =  $QueryModel->query_list($this->web_partner_id);
            foreach ($Query_excel as $key => $val) {

                if ($val['modified'] && $val['modified'] != null) {
                    $modified = date_created_format(intval($val['modified']));
                } else {
                    $modified = '';
                }

                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, ucwords($val['name']));
                $sheet->setCellValue('C' . $rows, $val['email']);
                $sheet->setCellValue('D' . $rows, $val['phone']);
                $sheet->setCellValue('E' . $rows, $val['subject']);
                $sheet->setCellValue('F' . $rows, $modified);
                $sheet->setCellValue('G' . $rows, date_created_format(intval($val['created_date']))); 
                $rows++;
            }
            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
            return $this->response->setJSON($data_validation);
       /*  } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        } */
    }

    
}
