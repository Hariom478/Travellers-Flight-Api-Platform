<?php

namespace Modules\Logs\Controllers;

use App\Modules\Logs\Models\LoginLogsModel;

use App\Modules\Logs\Models\SmsLogsModel;
use App\Modules\Logs\Models\EmailLogsModel;
use App\Modules\Logs\Models\CouponLogModel;

use App\Controllers\BaseController;
use Modules\Logs\Config\Validation;


class Logs extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Logs";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        if(permission_access_error("Logs","Logs_Module")) {

        }
    }


    public function sms_logs()
    {
        if (permission_access_error("Logs", "sms_logs_list")) {
            $SmsLogsModel = new SmsLogsModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $SmsLogsModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $SmsLogsModel->smslogs_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'sms_logs' => $lists,
                'view' => "Logs\Views\sms-logs-list",
                'pager' => $SmsLogsModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function sms_log_details(){
        $id = dev_decode($this->request->uri->getSegment(3));
        $SmsLogsModel = new SmsLogsModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $SmsLogsModel->sms_logs_details($this->web_partner_id,$id),
        ];
        $blog_details = view('Modules\Logs\Views\sms-logs-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }



    public function remove_sms_logs()
    {
        if (permission_access_error("Logs", "delete_sms_log")) {
            $SmsLogsModel = new SmsLogsModel();
            $ids = $this->request->getPost('checklist');
            $delete = $SmsLogsModel->remove_sms_logs($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Sms Log Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Sms Log not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function login_logs()
    {
        if (permission_access_error("Logs", "login_logs_list")) {
            $LoginLogsModel = new LoginLogsModel();
            $data = [
                'title' => $this->title,
                'login_logs' => $LoginLogsModel->loginlogs_list($this->web_partner_id),
                'view' => "Logs\Views\login-logs-list",
                'pager' => $LoginLogsModel->pager,
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function remove_login_logs()
    {
        if (permission_access_error("Logs", "delete_login_log")) {
            $LoginLogsModel = new LoginLogsModel();
            $ids = $this->request->getPost('checklist');
            $delete = $LoginLogsModel->remove_login_logs($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Login Log Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Login Log not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function email_logs()
    {
        if (permission_access_error("Logs", "email_logs_list")) {
            $EmailLogsModel = new EmailLogsModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $EmailLogsModel->search_data($this->web_partner_id, $this->request->getGet());
            } else {
                $lists = $EmailLogsModel->emaillogs_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'email_logs' => $lists,
                'view' => "Logs\Views\Email-logs-list",
                'pager' => $EmailLogsModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function email_log_details(){
        $id = dev_decode($this->request->uri->getSegment(3));
        $EmailLogsModel = new EmailLogsModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $EmailLogsModel->email_logs_details($this->web_partner_id,$id),
        ];
        $blog_details = view('Modules\Logs\Views\email-logs-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function remove_email_logs()
    {
        if (permission_access_error("Logs", "delete_email_log")) {
            $EmailLogsModel = new EmailLogsModel();
            $ids = $this->request->getPost('checklist');
            $delete = $EmailLogsModel->remove_email_logs($this->web_partner_id, $ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Email Log Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Email Log not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }



    public function coupon_log(): string
    {
        //if (permission_access_error("Flight", "flight_discount_list")) {
        $CouponLogModel = new CouponLogModel();

        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CouponLogModel->search_data($this->request->getGet());
        } else {
            $lists = $CouponLogModel->couponlog_list();
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Logs\Views\coupon-log-list",
            'pager' => $CouponLogModel->pager,
            'search_bar_data' => $this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
        //}
    }

}