<?php

namespace Modules\OfflineSupplier\Controllers;

use App\Modules\OfflineSupplier\Models\OfflineProvider;
use App\Controllers\BaseController;
use Modules\OfflineSupplier\Config\Validation;
use PhpOffice\PhpSpreadsheet\IOFactory;


class OfflineSupplier extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Offline Supplier";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
       
    }

    public function index()
    { 
        if(permission_access_error("Setting", "offline_supplier_list")) 
        {
            $OfflineProvider = new OfflineProvider();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $OfflineProvider->search_data($this->request->getGet(),$this->web_partner_id);   
            } else {
                $lists = $OfflineProvider->offline_provider_list($this->web_partner_id);
            }
            
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => 'OfflineSupplier\Views\offline-provider-list',
                'pager' => $OfflineProvider->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }


    public function add_supplier_view()
    {
        if (permission_access_error("Setting", "add_offline_supplier")) {
            $data = [
                'title' => $this->title,
            ];
            $view = view('Modules\OfflineSupplier\Views\add-supplier', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_supplier()
    { 
        if (permission_access("Setting", "add_offline_supplier")) {
            $validate = new Validation();
            $rules = $this->validate($validate->supplier_validation);
             
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = $this->request->getPost();
                $ActiveServices = get_active_whitelable_service();
                $Activeservicedata = [];

                foreach ($ActiveServices as $ActiveService) {
                    $Activeservicedata[] = strtolower($ActiveService) . "_service";
                }

                $services = $Activeservicedata;
                foreach ($services as $service) {
                    $data[$service] = isset($data[$service]) && $data[$service] == 1 ? 'active' : 'inactive';
                }
                $data['web_partner_id'] = $this->web_partner_id;
                $data['created'] = create_date();

                $OfflineProvider = new OfflineProvider();
                $added_data = $OfflineProvider->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Supplier successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Supplier not added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            // User does not have permission
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_supplier_view()
    {
        if(permission_access_error("Setting", "edit_offline_supplier")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $OfflineProvider = new OfflineProvider();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $OfflineProvider->offline_provider_details($id,$this->web_partner_id),
            ];
            $blog_details = view('Modules\OfflineSupplier\Views\edit-supplier', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function edit_supplier()
    {
        if (permission_access("Setting", "edit_offline_supplier")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->supplier_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $OfflineProvider = new OfflineProvider();
                $data = $this->request->getPost();

                $ActiveServices = get_active_whitelable_service();
                $Activeservicedata = [];

                foreach ($ActiveServices as $ActiveService) {
                    $Activeservicedata[] = strtolower($ActiveService) . "_service";
                }
 
                $services = $Activeservicedata;
                foreach ($services as $service) {
                    $data[$service] = isset($data[$service]) && $data[$service] == 1 ? 'active' : 'inactive';
                }

              
                $data['modified'] = create_date();

              
                $updated_data = $OfflineProvider ->where("id", $id)->where("web_partner_id", $this->web_partner_id)->set($data)->update();

                if ($updated_data) {
                    $message = array("StatusCode" => 0, "Message" => "Supplier successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Supplier not updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            // User does not have permission
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }




    public function status_change()
    {
        if(permission_access("Setting", "change_offline_supplier_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $OfflineProvider = new OfflineProvider();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $OfflineProvider->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Status changed successfully", "Class" => "error_popup", "Reload" => "true");
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