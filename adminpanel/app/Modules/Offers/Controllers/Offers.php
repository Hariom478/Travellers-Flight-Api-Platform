<?php

namespace Modules\Offers\Controllers;

use App\Modules\Offers\Models\OfferModel;
use App\Controllers\BaseController;
use Modules\Offers\Config\Validation;


class Offers extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Offers";
        $this->folder_name = "offers";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        if (permission_access_error("Offers", "Offers_Module")) {

        }
    }

    public function index()
    {
        $OfferModel = new OfferModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$OfferModel->search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists=$OfferModel->offer_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Offers\Views\offer-list",
            'pager' => $OfferModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function add_offer_view()
    {
        if (permission_access_error("Offers", "add_offer")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Offers\Views\add-offer', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_offer()
    {
        if (permission_access("Offers", "add_offer")) {
            $validate = new Validation();
            $rules = $this->validate($validate->offer_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $OfferModel = new OfferModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 600, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['image'] = $image_upload['file_name'];
                    $added_data = $OfferModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "offer successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "offer not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_offer_view()
    {
        if (permission_access_error("Offers", "edit_offer")) {
            
            $id = dev_decode($this->request->uri->getSegment(3));
               
            $OfferModel = new OfferModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $OfferModel->offer_details($id,$this->web_partner_id),
            ];
            $blog_details = view('Modules\Offers\Views\edit-offer', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_offer()
    {

        if (permission_access("Offers", "edit_offer")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $field_name = 'image';

        $validate = new Validation();

        $post_images = $this->request->getFile($field_name);
        if ($post_images->getName() == '') {
            unset($validate->offer_validation[$field_name]);
        }

        $rules = $this->validate($validate->offer_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $OfferModel = new OfferModel();
            $data = $this->request->getPost();
            $previous_data = $OfferModel->offer_details($id,$this->web_partner_id);
            $file = $this->request->getFile($field_name);
            if ($file->getName() != '') {
                $resizeDim = array('width' => 600, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {

                    if ($previous_data[$field_name]) {
                        if (file_exists(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                            unlink(FCPATH."../uploads/$this->folder_name/" . $previous_data[$field_name]);
                            unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                        }
                    }


                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $OfferModel->where("id", $id)->where(["web_partner_id"=>$this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "offer successfully Edit", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "offer not  Edit", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }
            } else {

                $data[$field_name] = $previous_data[$field_name];
                $added_data = $OfferModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "offer successfully edit", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "offer not  edit", "Class" => "error_popup", "Reload" => "true");
                }
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }



    public function remove_offer()
    {
        if (permission_access("Offers", "delete_offer")) {

        $OfferModel = new OfferModel();
        $ids = $this->request->getPost('checklist');

        $field_name = 'image';

        foreach ($ids as $id) {
            $blog_details = $OfferModel->delete_image($id,$this->web_partner_id);

            if ($blog_details[$field_name]) {
                if (file_exists(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                    unlink(FCPATH."../uploads/$this->folder_name/" . $blog_details[$field_name]);
                    unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                }
            }

            $delete = $OfferModel->remove_offer($id,$this->web_partner_id);
        }

        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "offer  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "offer  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function offers_status_change()
    {
        if (permission_access("Offers", "change_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->offers_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $OfferModel = new OfferModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $OfferModel->offers_status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Offers status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Offers status not changed successfully", "Class" => "error_popup", "Reload" => "true");
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