<?php

namespace Modules\Slider\Controllers;

use App\Modules\Slider\Models\SliderModel;

use App\Controllers\BaseController;
use Modules\Slider\Config\Validation;


class Slider extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Slider";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        $this->folder_name = 'sliders';
        if(permission_access_error("Slider","Slider_Module")) {

        }
    }

    public function index()
    {
        $SliderModel = new SliderModel();
        $data = [
            'title' => $this->title,
            'list' => $SliderModel->slider_list($this->web_partner_id),
            'view' => "Slider\Views\slider-list",
            'pager' => $SliderModel->pager
        ];
        return view('template/sidebar-layout', $data);
    }

    public function add_slider_view()
    {
        if (permission_access_error("Slider", "add_slider")) {
            $add_slider_view = view('Modules\Slider\Views\add-slider');
            $data = array("StatusCode" => 0, "Message" => $add_slider_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_slider()
    {
        if (permission_access_error("Slider", "add_slider")) {
            $validate = new Validation();
            $rules = $this->validate($validate->slider_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SliderModel = new SliderModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'slider_image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 150, 'height' => 80);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $SliderModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Slider Successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Slider not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_slider_view()
    {
        if (permission_access_error("Slider", "edit_slider")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $SliderModel = new SliderModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $SliderModel->slider_list_details($id, $this->web_partner_id),
            ];
            $details = view('Modules\Slider\Views\edit_slider', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function edit_slider()
    {
        if (permission_access_error("Slider", "edit_slider")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $field_name = 'slider_image';
            $validate = new Validation();

            $slider_images = $this->request->getFile($field_name);
            if ($slider_images->getName() == '') {
                unset($validate->slider_validation[$field_name]);
            }

            $rules = $this->validate($validate->slider_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SliderModel = new SliderModel();
                $data = $this->request->getPost();
                $previous_data = $SliderModel->slider_list_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 150, 'height' => 80);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $SliderModel->where("id", $id)->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Slider Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Slider not  Updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $SliderModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Slider Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Slider not  Updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_slider()
    {
        if (permission_access_error("Slider", "delete_slider")) {
            $SliderModel = new SliderModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'slider_image';

            foreach ($ids as $id) {
                $blog_details = $SliderModel->delete_image($id, $this->web_partner_id);

                if ($blog_details[$field_name]) {
                    if (file_exists("../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink("../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }
                $delete = $SliderModel->remove_slider($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Slider  Successfully  Deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Slider  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }



    public function slider_status_change()
    {

        if (permission_access_error("Slider", "slider_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->slider_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $SliderModel = new SliderModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $SliderModel->slider_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Slider status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Slider status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }
}
