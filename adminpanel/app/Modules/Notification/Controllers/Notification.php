<?php

namespace Modules\Notification\Controllers;

use App\Modules\Notification\Models\NotificationModel;
use App\Controllers\BaseController;
use Modules\Notification\Config\Validation;


class Notification extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Notification";


        /*if(permission_access_error("Feedback","Feedback_Module")) {

        }*/

    }

    public function index()
    {
        if (permission_access_error("Setting", "notification_list")) {
        $NotificationModel = new NotificationModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $NotificationModel->search_data($this->request->getGet());
        } else {
            $lists = $NotificationModel->notification_list();
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Notification\Views/notification-list",
            'pager' => $NotificationModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];


        return view('template/sidebar-layout', $data);
    }
    }

    public function add_notification_view()
    {
        if (permission_access_error("Setting", "add_notification")) {
        $data = [
            'title' => $this->title,
        ];
        $add_blog_view = view('Modules\Notification\Views\add-notification', $data);
        $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
        }
    }


    public function add_notification()
    {
        if (permission_access_error("Setting", "add_notification")) {
        $validate = new Validation();
        $rules = $this->validate($validate->notification_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $NotificationModel = new NotificationModel();
            $data = $this->request->getPost();

            $data['created'] = create_date();
            //$data['web_partner_id'] = $this->web_partner_id;

            $added_data = $NotificationModel->insert($data);

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "notification successfully added", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "notification not  added", "Class" => "error_popup");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        }
    }

    public function edit_notification_view()
    {
        if (permission_access_error("Setting", "edit_notification")) {
        $id = dev_decode($this->request->uri->getSegment(3));
        $NotificationModel = new NotificationModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $NotificationModel->notification_details($id),
        ];
        $blog_details = view('Modules\Notification\Views\edit-notification', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
        }
    }


    public function edit_notification()
    {

        if (permission_access_error("Setting", "edit_notification")) {
            $id = dev_decode($this->request->uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->notification_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $NotificationModel = new NotificationModel();
                $data = $this->request->getPost();

                $added_data = $NotificationModel->where("id", $id)->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "notification successfully updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "notification not updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }



    public function remove_notification()
    {
        if (permission_access_error("Setting", "delete_notification")) {
        $NotificationModel = new NotificationModel();
            $ids = $this->request->getPost('checklist');
            $delete = $NotificationModel->remove_notification($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "notification successfully deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "notification  not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function notification_status_change()
    {

        if (permission_access_error("Setting", "status_notification")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $NotificationModel = new NotificationModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $NotificationModel->notification_status_change($ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "notification status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "notification status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

}