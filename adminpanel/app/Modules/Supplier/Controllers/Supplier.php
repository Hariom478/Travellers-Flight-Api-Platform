<?php

namespace Modules\Supplier\Controllers;
 
use App\Modules\Supplier\Models\SuppliersModel;  
use App\Modules\Supplier\Models\SupplierAccountLogModel;
use App\Modules\Supplier\Models\SupplierClassModel;
use App\Modules\Supplier\Models\SupplierUsersModel;
use App\Modules\Supplier\Models\SupplierAccountModel;
use App\Controllers\BaseController;
use Modules\Supplier\Config\Validation;

require 'vendor/excel/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Supplier extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Supplier";
       
        $admin_cookie_data=admin_cookie_data();
        $this->web_partner_id=$admin_cookie_data['admin_user_details']['web_partner_id'];
        $this->user_id=$admin_cookie_data['admin_user_details']['id'];
        $this->whitelabel_user=$admin_cookie_data['admin_comapny_detail']['whitelabel_user'];
        $this->company_name =$admin_cookie_data['admin_comapny_detail']['company_name'];
        $whitelabel_setting_data=$admin_cookie_data['whitelabel_setting_data'];
        $this->aadhar_folder = "suppliers/aadhar";
        $this->gst_folder = "suppliers/gst";
        $this->pan_folder = "suppliers/pan-card";

        if ($this->whitelabel_user != "active" || $whitelabel_setting_data['splr_business'] != "active") {
            access_denied();
        }
        if(permission_access_error("Supplier", "Supplier_Module")) {

        }
    }
    public function export_supplier()
    {
        if (permission_access("Supplier", "supplier_export")) {
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

            $fileName = 'supplier.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getStyle("A1:S1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A:S")->getFont()->setName('Arial')->setSize(11);
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
        

            $sheet->setCellValue('A1', 'Sr. No.');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Mobile');
            $sheet->setCellValue('E1', 'Supplier Class');
            $sheet->setCellValue('F1', 'Company Name');
            $sheet->setCellValue('G1', 'DOB');
            $sheet->setCellValue('H1', 'Address');
            $sheet->setCellValue('I1', 'City');
            $sheet->setCellValue('J1', 'State');
            $sheet->setCellValue('K1', 'Country');
            $sheet->setCellValue('L1', 'Pin Code');
            $sheet->setCellValue('M1', 'status');
            $sheet->setCellValue('N1', 'Balance');
            $sheet->setCellValue('O1', 'Credit Limit');
            $sheet->setCellValue('O1', 'GST Holder Name');
            $sheet->setCellValue('P1', 'GST Number');
            $sheet->setCellValue('Q1', 'PAN Holder Name');
            $sheet->setCellValue('R1', 'Pan Number');
            $sheet->setCellValue('S1', 'modified');
            $sheet->setCellValue('T1', 'Created Date');

            $rows = 2;
            $SuppliersModel = new SuppliersModel();
            $supplier_excel = $SuppliersModel->supplier_export($this->web_partner_id, $this->request->getPost());


            foreach ($supplier_excel as $key => $val) {

                if ($val['modified'] && $val['modified'] != null) {
                    $modified = date_created_format(intval($val['modified']));
                } else {
                    $modified = '';
                }

                if ($val['date_of_birth']!=null) {
                    $dob = timestamp_to_date(intval($val['date_of_birth']));
                } else {
                    $dob = '';
                }

                $sheet->setCellValue('A' . $rows, $key + 1);
                $sheet->setCellValue('B' . $rows, $val['title'] . ' ' . $val['first_name'] . ' ' . $val['last_name']);
                $sheet->setCellValue('C' . $rows, $val['login_email']);
                $sheet->setCellValue('D' . $rows, $val['mobile_no']);
                $sheet->setCellValue('E' . $rows, $val['class_name']);
                $sheet->setCellValue('F' . $rows, $val['company_name']);
                $sheet->setCellValue('G' . $rows, $dob);
                $sheet->setCellValue('H' . $rows, $val['address']);
                $sheet->setCellValue('I' . $rows, $val['city']);
                $sheet->setCellValue('J' . $rows, $val['state']);
                $sheet->setCellValue('K' . $rows, $val['country']);
                $sheet->setCellValue('L' . $rows, $val['pincode']);
                $sheet->setCellValue('M' . $rows, $val['status']);
                $sheet->setCellValue('N' . $rows, $val['balance']);
                $sheet->setCellValue('O' . $rows, $val['gst_holder_name']);
                $sheet->setCellValue('P' . $rows, $val['gst_number']);
                $sheet->setCellValue('Q' . $rows, $val['pan_name']);
                $sheet->setCellValue('R' . $rows, $val['pan_number']);
                $sheet->setCellValue('S' . $rows, $modified);
                $sheet->setCellValue('T' . $rows, date_created_format(intval($val['created'])));
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

    public function Supplier_class()
    {    
        if(permission_access("Supplier", "supplier_class_list")) {
            $SupplierClassModel = new SupplierClassModel();
            $data = [
                'title' => $this->title,
                'list' => $SupplierClassModel->supplier_class_list($this->web_partner_id),
                'pager' => $SupplierClassModel->pager
            ];
            $class = view('Modules\Supplier\Views\supplier-class', $data);
            $data = array("StatusCode" => 0, "Message" => $class, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
       
    }

    public function add_supplier_class()
    {
        if(permission_access("Supplier", "add_supplier_class")) 
        {
            $validate = new Validation();
            $rules = $this->validate($validate->class_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SupplierClassModel = new SupplierClassModel();
                $data = $this->request->getPost();
                $data['created_date'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $SupplierClassModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Supplier Class Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Supplier Class does not added", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
       
    }

    public function edit_supplier_class()
    {
        if(permission_access("Supplier", "edit_supplier_class")) {
            $validate = new Validation();
            $rules = $this->validate($validate->class_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $id = dev_decode($this->request->uri->getSegment(3));
                $SupplierClassModel = new SupplierClassModel();
                $data = $this->request->getPost();
                $data['class_name'] = ucwords($data['class_name']);
                $data['modified'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $update_data = $SupplierClassModel->update_supplier_class($data,$this->web_partner_id,$id);
                if ($update_data) {
                    $message = array("StatusCode" => 0, "Message" => "Supplier Class Successfully Update", "Class" => "success_popup","Reload"=>"false");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Supplier Class does not  Update", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function index()
    {  
        $SuppliersModel = new SuppliersModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $SuppliersModel->search_data($this->request->getGet(),$this->web_partner_id);
        } else {
            $lists = $SuppliersModel->suppliers_list($this->web_partner_id);
        }
        $data = [
            'UserIp' => $this->request->getIpAddress(),
            'title' => $this->title,
            'supplier_list' => $lists,
            'view' => "Supplier\Views\supplier-list",
            'pager' => $SuppliersModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function add_supplier_view()
    {
        if (permission_access_error("Supplier", "add_supplier")) 
        {
            $SupplierClassModel = new SupplierClassModel();
            $supplier_class = $SupplierClassModel->supplier_class_list_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'supplier_class' => $supplier_class,
                'view' => "Supplier\Views\add-supplier",
            ];
            return view('template/sidebar-layout', $data);
        }
       
    }

    public function add_supplier_save()
    {  
        if (permission_access("Supplier", "add_supplier")) 
        {
            $validate = new Validation();
            $resizeDim = array('width' => 150, 'height' => 80);
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $field_name_gst_scan_copy = "gst_scan_copy";
            $gst_scan_copy = $this->request->getFile($field_name_gst_scan_copy);
        if ($gst_scan_copy->getName() != '') {
            $validate->supplier_validation[$field_name_gst_scan_copy]['rules'] = "uploaded[$field_name_gst_scan_copy]|max_size[$field_name_gst_scan_copy,1024]|mime_in[$field_name_gst_scan_copy,image/jpg,image/jpeg,image/png]";
            $validate->supplier_validation[$field_name_gst_scan_copy]['errors'] = $errors;
        }
            $rules = $this->validate($validate->supplier_validation);
            if (!$rules) {
              
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $permissions_supplier = file_get_contents(FCPATH . "app/Libraries/permissions_supplier.json");
                $SuppliersModel = new SuppliersModel();
                $data = $this->request->getPost();
                $checkRegisterSupplier = $SuppliersModel->checkRegisterSupplier($data['login_email'], $this->web_partner_id);
                if ($checkRegisterSupplier > 0) {
                    $errors['email_id'] = "This supplier is already registered. ";
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'pan_card';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->pan_folder, $resizeDim);
                $field_name_aadhar_scan_copy = 'aadhar_scan_copy';
                $aadhar_scan_copy = $this->request->getFile($field_name_aadhar_scan_copy);
                $image_upload_aadhar = image_upload($aadhar_scan_copy, $field_name_aadhar_scan_copy, $this->aadhar_folder, $resizeDim);
                if ($image_upload['status_code'] == 0 &&  $image_upload_aadhar['status_code'] == 0) {

                    $login_email = $data['login_email'];
                    $title = $data['title'];
                    $date_of_birth = $data['dob'];
                    $password = md5(trim($data['user_password']));
                    $first_name = $data['user_first_name'];
                    $last_name = $data['user_last_name'];
                    $mobile_isd = +91;
                    $mobile_no = $data['user_mobile_no'];
                    $whatsapp_no = '';
                    $street = $data['address'];
                    $city = $data['city'];
                    $state = $data['state'];
                    $country = $data['country'];
                    $pin_code = $data['pincode'];
                    unset(
                        $data['title'],
                        $data['login_email'],
                        $data['user_password'],
                        $data['user_first_name'],
                        $data['user_last_name'],
                        $data['dob'],
                        $data['user_mobile_no']
                    );
                    if (!empty($data['gst_number'])) {
                        $data['gst_state_code'] = substr($data['gst_number'], 0, 2);
                    }
                    $data['created'] = create_date();
                    $data['pan_card'] = $image_upload['file_name'];
                    $data['user_access'] = $permissions_supplier;
                    $data['web_partner_id'] = $this->web_partner_id;
                    if ($gst_scan_copy->getName() != '') {
                        $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->gst_folder, $resizeDim);
                        if ($gst_scan_copy_upload['status_code'] == 0) {
                            $gst_scan_copy = $gst_scan_copy_upload['file_name'];
                        } else {
                            $gst_scan_copy = null;
                            $message = array("StatusCode" => 2, "Message" => $gst_scan_copy_upload['message'], "Class" => "error_popup", "Reload" => "true");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    } else {
                        $gst_scan_copy = null;
                    }
                    $data[$field_name_gst_scan_copy] = $gst_scan_copy;
                    $data[$field_name_aadhar_scan_copy] = $image_upload_aadhar['file_name'];
                    $added_data_id = $SuppliersModel->insert($data);
                    $company_id['company_id'] = $added_data_id;
                
                    $SuppliersModel->where(array("web_partner_id"=>$this->web_partner_id,"id"=>$added_data_id))->set($company_id)->update();

                    $log['web_partner_id'] = $this->web_partner_id;
                    $log['supplier_id'] = $added_data_id;
                    $log['credit'] = 0;
                    $log['balance'] = 0;
                    $log['transaction_type'] = 'credit';
                    $log['role'] = 'web_partner';
                    $log['remark'] = 'Account created by ' . $this->company_name;
                    $log['user_id'] = $this->user_id;
                    $log['created'] = create_date();

                    $SupplierAccountLogModel = new SupplierAccountLogModel();
                    $account_log_added = $SupplierAccountLogModel->insert($log);

                    $user_details = [
                        'login_email' => $login_email,
                        'title' => $title,
                        'date_of_birth' => $date_of_birth,
                        'password' => $password,
                        'status' => $data['status'],
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'mobile_isd' => $mobile_isd,
                        'mobile_no' => $mobile_no,
                        'whatsapp_no' => $whatsapp_no,
                        'street' => $street,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'pin_code' => $pin_code,
                        'web_partner_id' =>$this->web_partner_id,
                        'primary_user' => 1,
                        'supplier_id' => $added_data_id,
                        'job_joining_date' => get_current_date(),
                        'access_permission'=>$permissions_supplier,
                        'created_date' => create_date(),
                    ];
            
                    $SupplierUsersModel = new  SupplierUsersModel();
                    $user_login = $SupplierUsersModel->insert($user_details); 
                    if ($added_data_id) {
                        $email = [
                            "email" => $this->request->getPost('login_email'),
                            "mobile_no" => $this->request->getPost('user_mobile_no'),
                            "password" => $this->request->getPost('user_password'),
                            "message" => "Thanks for signing up! We’re so glad you decided to be part of the family."
                        ];

                        $subject = "Congratulations! Your account has been created successfully.";
                        $message = view('Views/emails/common-registration-email', $email);
                        $email_type = 'Your Account Has Been Created';
                        send_email($email['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);

                        $message = array("StatusCode" => 0, "Message" => "Supplier has been successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Supplier does not added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
         
    }

    public function edit_supplier_view()
    {
        if (permission_access_error("Supplier", "edit_supplier")) 
        {
            $id = dev_decode($this->request->uri->getSegment(3));
            $SupplierClassModel = new SupplierClassModel();
            $supplier_class = $SupplierClassModel->supplier_class_list_select($this->web_partner_id);

            $SuppliersModel = new SuppliersModel();
            $details = $SuppliersModel->supplier_list_details($this->web_partner_id,$id);

            $SupplierUsersModel = new  SupplierUsersModel();
            $user_details = $SupplierUsersModel->supplier_users_details($id,$this->web_partner_id);
            if (isset($user_details['date_of_birth']) && $user_details['date_of_birth'] != '') {
                $user_details['dob'] = timestamp_to_date($details['date_of_birth']);
            } else {
                $user_details['dob'] = '';
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'user_details' => $user_details,
                'supplier_class' => $supplier_class,
                'details' => $details,
                'view' => 'Supplier\Views\edit-supplier',
            ];
            return view('template/sidebar-layout', $data);
        }
         
    }


   public function edit_supplier_save()
    {
        if (permission_access("Supplier", "edit_supplier")) 
        {
            $errors = [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ];
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name_pan_scan_copy = 'pan_card';
            $field_name_gst_scan_copy = "gst_scan_copy";
            $field_name_aadhar_scan_copy = 'aadhar_scan_copy';
            $validate = new Validation();
            $validate->supplier_validation['pan_number']['rules'] = "trim|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]";
            
            $pan_scan_copy = $this->request->getFile($field_name_pan_scan_copy);
            if ($pan_scan_copy->getName() == '') {
                unset($validate->supplier_validation[$field_name_pan_scan_copy]);
            }

            $aadhar_scan_copy = $this->request->getFile($field_name_aadhar_scan_copy);
            if ($aadhar_scan_copy->getName() == '') {
                unset($validate->supplier_validation[$field_name_aadhar_scan_copy]);
            }
            $gst_scan_copy = $this->request->getFile($field_name_gst_scan_copy);
            if ($gst_scan_copy->getName() != '') {
                $validate->supplier_validation[$field_name_gst_scan_copy]['rules'] = "uploaded[$field_name_gst_scan_copy]|max_size[$field_name_gst_scan_copy,1024]|mime_in[$field_name_gst_scan_copy,image/jpg,image/jpeg,image/png]";
                $validate->supplier_validation[$field_name_gst_scan_copy]['errors'] = $errors;
            }

           
            unset($validate->supplier_validation['login_email']);
            unset($validate->supplier_validation['user_password']);
            $rules = $this->validate($validate->supplier_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $permissions_supplier = file_get_contents(FCPATH . "app/Libraries/permissions_supplier.json");
                $SuppliersModel = new SuppliersModel();
                $data = $this->request->getPost();

                $title = $data['title'];
                $first_name = $data['user_first_name'];
                $last_name = $data['user_last_name'];
                $mobile_isd = +91;
                $mobile_no = $data['user_mobile_no'];
                $whatsapp_no = '';
                $date_of_birth = $data['dob'];
                $street = $data['address'];
                $city = $data['city'];
                $state = $data['state'];
                $country = $data['country'];
                $pin_code = $data['pincode'];
                unset(
                    $data['title'],
                    $data['dob'],
                    $data['user_first_name'],
                    $data['user_last_name'],
                    $data['user_mobile_no'],

                );
                if (!empty($data['gst_number'])) {
                    $data['gst_state_code'] = substr($data['gst_number'], 0, 2);
                }
                $user_details = [
                    'status' => $data['status'],
                    'title' => $title,
                    'date_of_birth' => $date_of_birth,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'mobile_isd' => $mobile_isd,
                    'mobile_no' => $mobile_no,
                    'whatsapp_no' => $whatsapp_no,
                    'street' => $street,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'pin_code' => $pin_code,
                    'modified' => create_date()
                ];
                $data['modified'] = create_date();
                $SupplierUsersModel = new  SupplierUsersModel();
                $previous_data = $SuppliersModel->supplier_list_details($this->web_partner_id,$id);
                $file = $this->request->getFile($field_name_pan_scan_copy);
                $resizeDim = array('width' => 360, 'height' => 200);
                if ($file->getName() != '') {
                   
                    $image_upload = image_upload($file, $field_name_pan_scan_copy, $this->pan_folder, $resizeDim);
                    if ($image_upload['status_code'] == 0) {
                        if ($previous_data[$field_name_pan_scan_copy]) {
                            if (file_exists(FCPATH."../uploads/$this->pan_folder/" . $previous_data[$field_name_pan_scan_copy])) {
                                unlink(FCPATH."../uploads/$this->pan_folder/" . $previous_data[$field_name_pan_scan_copy]);
                                unlink(FCPATH."../uploads/$this->pan_folder/thumbnail/" . $previous_data[$field_name_pan_scan_copy]);
                            }
                        }
                        $data[$field_name_pan_scan_copy] = $image_upload['file_name'];
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $data[$field_name_pan_scan_copy] = $previous_data[$field_name_pan_scan_copy];
                }
                if ($gst_scan_copy->getName() != '') {
                    $gst_scan_copy_upload = image_upload($gst_scan_copy, $field_name_gst_scan_copy, $this->gst_folder, $resizeDim);
                    if ($gst_scan_copy_upload['status_code'] == 0) {
                        $data[$field_name_gst_scan_copy]  = $gst_scan_copy_upload['file_name'];
                        if ($previous_data[$field_name_gst_scan_copy]) {
                            if (file_exists(FCPATH . "../uploads/$this->gst_folder/" . $previous_data[$field_name_gst_scan_copy])) {
                                unlink(FCPATH . "../uploads/$this->gst_folder/" . $previous_data[$field_name_gst_scan_copy]);
                                unlink(FCPATH . "../uploads/$this->gst_folder/thumbnail/" . $previous_data[$field_name_gst_scan_copy]);
                            }
                        }
                    } else {
                        $data[$field_name_gst_scan_copy]  = null;
                        $message = array("StatusCode" => 2, "Message" => $gst_scan_copy_upload['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $data[$field_name_gst_scan_copy]  = $previous_data[$field_name_gst_scan_copy];
                }
                
                if ($aadhar_scan_copy->getName() != '') {
                    $image_upload_aadhar = image_upload($aadhar_scan_copy, $field_name_aadhar_scan_copy, $this->aadhar_folder, $resizeDim);
                    if ($image_upload_aadhar['status_code'] == 0) {

                        if ($previous_data[$field_name_aadhar_scan_copy]) {
                            if (file_exists(FCPATH . "../uploads/$this->aadhar_folder/" . $previous_data[$field_name_aadhar_scan_copy])) {
                                unlink(FCPATH . "../uploads/$this->aadhar_folder/" . $previous_data[$field_name_aadhar_scan_copy]);
                                unlink(FCPATH . "../uploads/$this->aadhar_folder/thumbnail/" . $previous_data[$field_name_aadhar_scan_copy]);
                            }
                        }
                        $data[$field_name_aadhar_scan_copy] = $image_upload_aadhar['file_name'];
                    } else {
                        $aadhar_scan_copy = null;
                        $message = array("StatusCode" => 2, "Message" => $image_upload_aadhar['message'], "Class" => "error_popup", "Reload" => "true");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                } else {
                    $data[$field_name_aadhar_scan_copy] = $previous_data[$field_name_aadhar_scan_copy];
                }
                    $added_data = $SuppliersModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();
                    $user_data = $SupplierUsersModel->where(["supplier_id"=>$id,"web_partner_id"=>$this->web_partner_id])->where('primary_user', 1)->set($user_details)->update();
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Supplier has been successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Supplier does not updated", "Class" => "error_popup", "Reload" => "true");
                    }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function remove_supplier()
    {
        $SuppliersModel = new SuppliersModel();
        $SupplierUsersModel = new SupplierUsersModel();
        $blog_ids = $this->request->getPost('checklist');

        $field_name = 'pan_card';

        foreach ($blog_ids as $id) {
            $blog_details = $SuppliersModel->delete_image($id,$this->web_partner_id);

            if ($blog_details[$field_name]) {
                if (file_exists("./uploads/$this->pan_folder/" . $blog_details[$field_name])) {
                    unlink("./uploads/$this->pan_folder/" . $blog_details[$field_name]);
                    unlink("./uploads/$this->pan_folder/thumbnail/" . $blog_details[$field_name]);
                }
            }

            $delete = $SuppliersModel->remove_supplier($id,$this->web_partner_id);
            $delete_users = $SupplierUsersModel->remove_supplier_users($id, $this->web_partner_id);
        }

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "Supplier  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Supplier  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    
    }


    public function supplier_status_change()
    { 
        $validate = new Validation();
        $rules = $this->validate($validate->status);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
           
            $ids = $this->request->getPost('checkedvalue');
            $data['status'] = $this->request->getPost('status');
            
            $SupplierUsersModel = new SupplierUsersModel();
            $SupplierUsersModel->supplier_users_change($ids, $data,$this->web_partner_id);

            $SuppliersModel = new SuppliersModel();
            $update = $SuppliersModel->supplier_status_change($ids, $data,$this->web_partner_id);


            if ($update) {
                $message = array("StatusCode" => 0, "Message" => "supplier status  successfully changed", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "supplier status not changed successfully", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        
    }

    public function supplier_account_logs()
    { 
        $supplier_id = dev_decode($this->request->uri->getSegment(3));
        
        $SuppliersModel = new SuppliersModel();
        $SupplierAccountLogModel = new SupplierAccountLogModel();

        $available_balance = $SupplierAccountLogModel->available_balance($supplier_id,$this->web_partner_id);
       
        if (isset($available_balance['balance'])) {
            $balance = $available_balance['balance'];
        } else {
            $balance = 0;
        }
        $searchData = array();
        if ($this->request->getGet() && $this->request->getGet('from_date') && $this->request->getGet('to_date')) {
            $searchData = $this->request->getGet();
            $account_logs = $SupplierAccountLogModel->account_logs($supplier_id, $this->web_partner_id,$searchData);
            $pager  =  $SupplierAccountLogModel->pager;
        } else {
            $account_logs = $SupplierAccountLogModel->account_logs($supplier_id, $this->web_partner_id,$searchData);
            $pager  =  $SupplierAccountLogModel->pager;
            
        }
        if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {
            Supplier::export_supplier_account_logs($account_logs);
        }
        $Supplierdetails=$SuppliersModel->supplier_details_page($supplier_id, $this->web_partner_id);
        $Supplierdetails['balance'] = $balance;
        $data = [
            'title' => $this->title,
            'account_logs' => $account_logs,
            'details' => $Supplierdetails,
            'view' => "Supplier\Views\supplier-account-logs-list",
            'pager' => $pager,
            'available_balance' => $balance,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
         
    }


    public function supplier_details()
    { 
        $Supplier_id = dev_decode($this->request->uri->getSegment(3));
        $SuppliersModel = new SuppliersModel();
        $details = $SuppliersModel->supplier_details_page($Supplier_id,$this->web_partner_id);
   
        $SupplierAccountLogModel = new SupplierAccountLogModel();
        $available_balance = $SupplierAccountLogModel->available_balance($Supplier_id,$this->web_partner_id);
         
        if (isset($available_balance['balance'])) {
            $details['balance'] = $available_balance['balance'];
        } else {
            $details['balance'] = 0;
        }

        $data = [
            'title' => $this->title,
            'id' => $Supplier_id,
            'details' => $details, 
            'available_balance' => $details['balance'],
        ];
        $blog_details = view('Modules\Supplier\Views\supplier-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        
    }
   
    public function change_password()
    {
        if (permission_access_error("Supplier", "change_password")) {

            $validate = new Validation();
            $rules = $this->validate($validate->password_change);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SupplierUsersModel = new SupplierUsersModel();
                $send_email = $this->request->getPost('send_email'); //send email pending
                $id = dev_decode($this->request->getPost('supplier_id'));
                $primary_user_data = $SupplierUsersModel->supplier_users_details($id,$this->web_partner_id);
                $EmailId = $primary_user_data['login_email'];
                $data['password'] = md5($this->request->getPost('password'));

                $send_status = 0;
                if (isset($send_email) && $send_email) {
                    if ($send_email == 'send') {
                        $send_status = 1;
                        unset($send_email);
                    }
                }
                $update = $SupplierUsersModel->where("web_partner_id", $this->web_partner_id)->where("id", $primary_user_data['id'])->set($data)->update();
                if ($update) {

                    if ($send_status == 1) {
                        $PasswordReset = [
                            "email" => $EmailId,
                            "password" => $this->request->getPost('password'),
                            "passwordMessage" => "Congratulations! Your password has been reset successfully."
                        ];

                        $subject = "Congratulations! Your password has been reset successfully.";
                        $message = view('Views/emails/common-registration-email', $PasswordReset);
                        $email_type = 'Password Reset';
                        send_email($PasswordReset['email'], $subject, $message, $email_type, $attachment = null, $extraprameter = null, $param = null);
                    }



                    $message = array("StatusCode" => 0, "Message" => "Password  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Password not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }
   

    public function getSupplier()
    {
        $terms = trim($this->request->getGet('term'));
        $SupplierAccountLogModel = new SupplierAccountLogModel();

        $SupplierAccountModel = new SupplierAccountModel();
        $supplierInfo = $SupplierAccountModel->supplierInfo($terms);
        $availablesupplierinfo = [];
        if (!empty($supplierInfo)) {
            foreach ($supplierInfo as $data) {
                $supplierbalanceInfo = $SupplierAccountLogModel->available_balance($data['id'],$this->web_partner_id);
                $webPartnerBalance = isset($supplierbalanceInfo['balance']) ? $supplierbalanceInfo['balance'] : 0;
                $availablesupplierinfo[] = ['id' => $data['id'], 'company_name' => $data['company_name'], 'label' => $data['company_name'] . ' (' . $data['company_id'] . ')' . '', 'company_id' => $data['company_id'], 'pan_name' => $data['pan_name'], 'balance' => $webPartnerBalance];
            }
        }
        echo json_encode($availablesupplierinfo);
    }

    public function view_remark()
    {
        $id = dev_decode($this->request->uri->getSegment(3));
        $SupplierAccountLogModel = new SupplierAccountLogModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'data' => $SupplierAccountLogModel->view_remark_detail($id, $this->web_partner_id),
        ];
        $blog_details = view('Modules\Supplier\Views\view-remark', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
   
    public function accountUpdateLogRemark()
    {
        if (permission_access_error("Supplier", "update_remark")) 
        {
            $validate = new Validation();
            $rules = $this->validate($validate->accountUpdateLogRemark);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $supplier_data = dev_decode($this->request->uri->getSegment(3));
                $added_data_id = explode("-", $supplier_data)[0];
                $supplier_id = explode("-", $supplier_data)[1];
                $SupplierAccountLogModel = new SupplierAccountLogModel();
                $data = array();
                $Postdata = $this->request->getPost();
            
                $data['user_id'] = $this->user_id;
                $data['modified'] = create_date();
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                $SupplierAccountLogModel->where(["id" => $added_data_id, 'web_partner_id' => $this->web_partner_id, 'supplier_id' => $supplier_id])->set($data)->update();

                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account remark successfully updated", "Class" => "success_popup", "Reload" => "false");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account remark", "Class" => "error_popup", "Reload" => "false");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function export_supplier_account_logs($account_logs)
    {
        $fileName = 'supplier-Account-Logs' . "." . 'xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle("A1:K1")->getFont()->setBold(true)->setName('Arial')->setSize(11);
        $sheet->getStyle("A:K")->getFont()->setName('Arial')->setSize(11);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
       


        $sheet->setCellValue('A1', 'Sr.No.');
        $sheet->setCellValue('B1', 'Company');
        $sheet->setCellValue('C1', 'Debit');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Balance');
        $sheet->setCellValue('F1', 'Date');
        $sheet->setCellValue('G1', 'Payment Type');
        $sheet->setCellValue('H1', 'Staff Name');
        $sheet->setCellValue('I1', 'Remark');
        $rows = 2;

        foreach ($account_logs as $key => $val) {
            $PaymentType = "";
            $transaction_id = '';
            if (isset($val['transaction_id']) && $val['transaction_id'] != '') {
                $PaymentType = $PaymentType . 'Transaction Id -' . $val['transaction_id'] . " ";
            }

            if (isset($val['payment_mode']) && $val['payment_mode'] != '') {
                $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . $val['payment_mode'];
            } else {
                $PaymentType = $PaymentType . ucfirst($val['action_type']) . ' - ' . 'Wallet';
            }

           
            $sheet->setCellValue('A' . $rows, $key + 1);
            $sheet->setCellValue('B' . $rows, $val['company_name']);
            $sheet->setCellValue('C' . $rows, $val['debit']);
            $sheet->setCellValue('D' . $rows, $val['credit']);
            $sheet->setCellValue('E' . $rows, $val['balance']);
            $sheet->setCellValue('F' . $rows, $val['created']);
            $sheet->setCellValue('G' . $rows, $PaymentType);
            $sheet->setCellValue('H' . $rows, $val['web_partner_staff_name']);
            $sheet->setCellValue('I' . $rows, $val['remark']);
            $rows++;
        }

        $writer = new Xlsx($spreadsheet);
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Expires: 0');
        $writer->save("php://output");
        exit;
    }

    public function virtual_topup_view()
    {
        if (permission_access("Supplier", "virtual_topup")) {
              $supplier_id = dev_decode($this->request->uri->getSegment(3));
            $SupplierAccountLogModel = new SupplierAccountLogModel();
            $SupplierUsersModel = new SupplierUsersModel();
            $available_balance = $SupplierAccountLogModel->available_balance($supplier_id, $this->web_partner_id);
            $data['details'] = $SupplierUsersModel->supplier_users_details($supplier_id, $this->web_partner_id);
            $data['details']['balance'] =0;
            if (isset($available_balance['balance'])) {
                $data['details']['balance'] = $available_balance['balance'];
            }
            $view = view('Modules\Supplier\Views\topup-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_topup()
    {
        if (permission_access("Supplier", "virtual_topup")) {

            $validate = new Validation();
            $rules = $this->validate($validate->virtual_credit);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {

                $supplier_id = dev_decode($this->request->uri->getSegment(3));
                $SupplierAccountLogModel = new SupplierAccountLogModel();
                $available_balance = $SupplierAccountLogModel->available_balance($supplier_id, $this->web_partner_id);

                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['supplier_id'] = $supplier_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "credit";
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['credit'] = $Postdata['credit'];
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $SupplierAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id)['id'];

                }
                $data['balance'] = $available_balance['balance'] + $Postdata['credit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);
                $added_data_id = $SupplierAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data_id);
                $SupplierAccountLogModel->where(["id" => $added_data_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit_view()
    {
        if (permission_access("Supplier", "virtual_deduct")) {
            $supplier_id = dev_decode($this->request->uri->getSegment(3));
            $SupplierAccountLogModel = new SupplierAccountLogModel();
            $SupplierUsersModel = new SupplierUsersModel();
            $available_balance = $SupplierAccountLogModel->available_balance($supplier_id, $this->web_partner_id);
            $data['details'] = $SupplierUsersModel->supplier_users_details($supplier_id, $this->web_partner_id);
            $data['details']['balance'] =0;
            if (isset($available_balance['balance'])) {;
                $data['details']['balance'] = $available_balance['balance'];
            }
            $view = view('Modules\Supplier\Views\debit-template', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function virtual_debit()
    {
        if (permission_access("Supplier", "virtual_deduct")) {
            $validate = new Validation();

            $rules = $this->validate($validate->virtual_debit);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $supplier_id = dev_decode($this->request->uri->getSegment(3));

                $SupplierAccountLogModel = new SupplierAccountLogModel();
                $available_balance = $SupplierAccountLogModel->available_balance($supplier_id, $this->web_partner_id);
                if (!$available_balance) {
                    $available_balance['balance'] = 0;
                }
                $Postdata = $this->request->getPost();
                $data['supplier_id'] = $supplier_id;
                $data['web_partner_id'] = $this->web_partner_id;
                $data['user_id'] = $this->user_id;
                $data['created'] = create_date();
                $data['transaction_type'] = "debit";
                $data['debit'] = $Postdata['debit'];
                $data['action_type'] = $Postdata['action_type'];
                $data['role'] = 'web_partner';
                $data['remark'] = $Postdata['remark'];
                if ($Postdata['service'] != "") {
                    $data['service'] = $Postdata['service'];
                    $data['booking_ref_no'] = $SupplierAccountLogModel->servicesId($Postdata['service'], $Postdata['booking_reference_number'], $this->web_partner_id)['id'];

                }
                $data['balance'] = $available_balance['balance'] - $Postdata['debit'];
                $data['acc_ref_number'] = mt_rand(100000, 999999);
                $added_data_id = $SupplierAccountLogModel->insert($data);
                $update['acc_ref_number'] = reference_number($added_data_id);
                $SupplierAccountLogModel->where(["id" => $added_data_id, "web_partner_id" => $this->web_partner_id])->set($update)->update();
                if ($added_data_id) {
                    $message = array("StatusCode" => 0, "Message" => "Account logs successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Unable to update account logs", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

 
}