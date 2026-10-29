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
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];
        $this->whitelabel_user = admin_cookie_data()['admin_comapny_detail']['whitelabel_user'];
        if ($this->whitelabel_user != "active"){
            access_denied();
        }
        if(permission_access_error("Newsletter","Newsletter_Module")) {

        }
    }

    public function index()
    {
        $newsletterModel = new NewsletterModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$newsletterModel->search_data($this->web_partner_id,$this->request->getGet());
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
        if (permission_access_error("Newsletter", "newsletter_export")) {
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
            $newsletter_excel = $newsletterModel->newsletter_list_excel($this->web_partner_id, $this->request->getPost());

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
        }
    }


    public function import_newsletter()
    {
        if (permission_access_error("Newsletter", "newsletter_import")) {
            $validationRule = [
                'fileimport' => [
                    'label' => 'File',
                    'rules' => 'uploaded[fileimport]'
                        . '|mime_in[fileimport,text/csv,text/plain,inode/x-empty,application/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]'
                        . '|max_size[fileimport,10000]',
                ],
            ];
            if (!$this->validate($validationRule)) {
                custom_error_page('Please upload valid file format -', $this->validator->getErrors()['fileimport'], '');
            }
            $file = $this->request->getFile('fileimport');
            if (!$file->hasMoved()) {
                $filename = $file->getName();
                if ($file->move(FCPATH . "../uploads/import_file/", $filename)) {
                    $new_filename = $file->getName();
                    return redirect()->to(site_url('/newsletter/import-data?filename=' . $new_filename));
                }
            } else {
                custom_error_page('File already moved -', 'The file has already been moved.', '');
            }
        }
    }

    public function import_data()
    {
        if (permission_access_error("Newsletter", "newsletter_import")) {
            ini_set('memory_limit', '1024M');

            $filename = $this->request->getVar('filename');
            $data = [
                'title' => 'Import data',
                'view' => 'Newsletter\Views\import-data',
            ];

            $filepath = "../uploads/import_file/" . $filename;
            if (file_exists($filepath)) {
                $fileinfo = pathinfo($filepath);
                if ($fileinfo['extension'] == 'csv') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif ($fileinfo['extension'] == 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }
                $spreadsheet = $reader->load($filepath);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $arrayCount = count($allDataInSheet);
                $data['allDataInSheet'] = $allDataInSheet;
                $data['fileinfo'] = array("name" => $fileinfo['basename'], "size" => filesize($filepath));
                $data['dbfields'] = array('name', 'email');
                $data['filefields'] = current($allDataInSheet);


                if ($this->request->getMethod() == 'post') {
                    $SheetDataKey = $this->request->getPost();
                    $NewsletterModel = new NewsletterModel();
                    for ($i = 2; $i <= $arrayCount; $i++) {
                        $name = $SheetDataKey['name'];
                        $email = $SheetDataKey['email'];
                        $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                        $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_EMAIL);
                        $fetchData[] = array('web_partner_id' => $this->web_partner_id, 'name' => $name, 'email' => $email, 'created' => create_date());
                    }
                    foreach (array_chunk($fetchData, 200) as $chunkarray) {
                        if ($NewsletterModel->insertBatch($chunkarray)) {
                            $message = array("StatusCode" => 0, "Message" => "file has been successfully uploaded", "Class" => "success_popup");
                        } else {
                            $message = array("StatusCode" => 1, "Message" => "something went wrong", "Class" => "error_popup");
                        }
                    }
                    unlink($filepath);
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url('newsletter'));
                }

                return view('template/sidebar-layout', $data);
            } else {
                custom_error_page('File not exists', 'file does not exist verify that the correct file name was given', 404);
            }
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
        if (permission_access_error("Newsletter", "add_newsletter")) {
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
                'details' => $NewsletterModel->newsletter_details($this->web_partner_id, $id),
            ];
            $blog_details = view('Modules\Newsletter\Views\edit-newsletter', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_newsletters()
    {
        if (permission_access_error("Newsletter", "edit_newsletter")) {
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

                $added_data = $NewsletterModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Newsletter Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Newsletter not  Updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_newsletter()
    {
        if (permission_access_error("Newsletter", "delete_newsletter")) {
            $NewsletterModel = new NewsletterModel();
            $ids = $this->request->getPost('checklist');
            $delete = $NewsletterModel->remove_newsletter($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Newsletter Successfully  Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Newsletter  not Deleted", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
}