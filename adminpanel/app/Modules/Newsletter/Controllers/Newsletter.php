<?php

namespace Modules\Newsletter\Controllers;

use App\Modules\Newsletter\Models\NewsletterModel;
use App\Controllers\BaseController;
use Modules\Newsletter\Config\Validation;
require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Newsletter extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->title = "Newsletter";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        if(permission_access_error("Newsletter","Newsletter_Module")) {

        }
    }

    public function index()
    {
        $newsletterModel = new NewsletterModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$newsletterModel->search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists=$newsletterModel->newsletter_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'newsletter_list' =>$lists,
            'view' => 'Newsletter\Views\newsletter-list',
            'pager' => $newsletterModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

  
    public function export_newsletter()
    {
        if (permission_access("Newsletter", "newsletter_export")) {
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

            $fileName = 'newsletter.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:D1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A:D")->getFont()->setName('Arial')->setSize(11);
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);

            $sheet->setCellValue('A1', 'Sr. No.');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Created Date');

            $rows = 2;
            $newsletterModel = new NewsletterModel();
            $newsletter_excel = $newsletterModel->newsletter_list_excel($this->request->getPost(),$this->web_partner_id);

            foreach ($newsletter_excel as $key => $val) {
                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['name']);
                $sheet->setCellValue('C' . $rows, $val['email']);
                $sheet->setCellValue('D' . $rows, date_created_format($val['created']));
                $rows++;
            }
            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $data_validation = array("StatusCode" => 5, 'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($xlsData), 'filename' => $fileName);
            return $this->response->setJSON($data_validation);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        } 
    }
 

    public function add_newsletter_view()
    {
        if (permission_access_error("Newsletter", "add_newsletter")) {
            $data = [
                'title' => $this->title,
            ];
            $view = view('Modules\Newsletter\Views\add-newsletter', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }

    public function add_newsletter()
    {
        if (permission_access("Newsletter", "add_newsletter")) {
            $validate = new Validation();
            $rules = $this->validate($validate->newsletter_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $NewsletterModel = new NewsletterModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $NewsletterModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Newsletter Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Newsletter not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_newsletter_view()
    {
        if (permission_access_error("Newsletter", "edit_newsletter")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $NewsletterModel = new NewsletterModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $NewsletterModel->newsletter_details($id,$this->web_partner_id),
            ];
            $blog_details = view('Modules\Newsletter\Views\edit-newsletter', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_newsletters()
    {
        if (permission_access("Newsletter", "edit_newsletter")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $validate->newsletter_validation['email']['rules'] = "required|valid_email|is_unique[newsletter.email,id,$id]";
            $rules = $this->validate($validate->newsletter_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $NewsletterModel = new NewsletterModel();
                $data = $this->request->getPost();

                $added_data = $NewsletterModel->where("id", $id)->where(['web_partner_id'=>$this->web_partner_id])->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Newsletter Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Newsletter not  Updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_newsletter()
    {
        if (permission_access("Newsletter", "delete_newsletter")) {
            $NewsletterModel = new NewsletterModel();
            $ids = $this->request->getPost('checklist');
            $delete = $NewsletterModel->remove_newsletter($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Newsletter Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Newsletter  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
}