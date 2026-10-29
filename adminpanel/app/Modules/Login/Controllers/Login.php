<?php

namespace Modules\Login\Controllers;

use CodeIgniter\I18n\Time;
use App\Modules\Login\Models\LoginModel;
use App\Controllers\BaseController;
use App\Modules\Login\Models\WebPartnerModel;
use App\Modules\Login\Models\LoginLogsModel;
use App\Modules\Login\Models\AdminUsersModel;
use Modules\Login\Config\Validation;
use phpDocumentor\Reflection\Type;

class Login extends BaseController
{

    public function __construct()
    {
        $this->title = "Admin Login";
        helper('Modules\Login\Helpers\login');
        $this->template_id_forgot_password_otp = "";
        $this->template_id_mobile_verification_otp = "";
        $this->template_id_user_credientils = "";
        $this->template_id_user_id = "";
        $this->template_id_forgot_password_otp = "";
        $this->template_id_password_reset = "";
    }

    public function index()
    {
        if ($this->session->get('admin_user')) {
            return redirect()->to(site_url('/dashboard'));
        }
        if ($this->request->getMethod() == 'post') {
            $rules = $this->validate([
                'user_email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|trim',
                    'errors' => [
                        'required' => 'Please enter your email id.',
                        'valid_email' => 'Please enter a valid email id.'
                    ]
                ],
                'user_password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]',
                    'errors' => [
                        'required' => 'Please enter your password.',
                        'min_length' => 'Password must be at least 8 digits'
                    ]
                ]
            ]);
            if (!$rules) {
                return view('Modules\Login\Views\login', [
                    'title' => $this->title,
                    'validation' => $this->validator,
                ]);
            }
            $email = $this->request->getVar('user_email');
            $password = $this->request->getVar('user_password');
            $loginmodel = new LoginModel();
            $user = $loginmodel->where('login_email', $email)->where('web_partner_id', whitelabel['web_partner_id'])->where('password', md5($password))->first();
            if ($user) {
                $user_status = $user['status'];
                if ($user_status == "active") {
                    $this->session->set('admin_user', $user);
                    $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                    $this->session->setFlashdata('Message', $message);
                    $LoginLogsModel = new LoginLogsModel();
                    $agent = $this->request->getUserAgent();
                    $loginLogs = [
                        "web_partner_id" => $user['web_partner_id'],
                        "user_id" => $user['id'],
                        "user_name" => $user['first_name'],
                        "login_browser" => $agent->getBrowser() . ' ' . $agent->getVersion(),
                        'platform' => $agent->getPlatform(),
                        'role' => 'Admin',
                        "login_time" => create_date(),
                        "login_ip_address" => $this->request->getIpAddress()
                    ];
                    $LoginLogsModel->insert($loginLogs);
                    return redirect()->to(site_url('/dashboard'));
                } else {
                    $message = array("StatusCode" => 1, "Message" => "Your account has been deactivated.Please contact your system administrator", "Class" => "error_popup");
                }
            } else {
                $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return redirect()->to(site_url('/login'));
        }
        $this->session->remove('admin_comapny_detail');
        $this->session->remove('admin_user_details');
        $this->session->remove('whitelabel_setting_data');
        $data = [
            'title' => $this->title,
            'validation' => $this->validation
        ];
        return view('Modules\Login\Views\login', $data);
    }
    public function access_account()
    {
        if ($this->request->getMethod() == 'get') {
            $this->session->remove('admin_user');
            $this->session->remove('comapny_detail');
            $this->session->remove('admin_user_details');
            $access_token = dev_decode_direct_access($this->request->uri->getSegment(2));
            $access_token = explode('-', $access_token);
            if (isset($access_token[0]) && isset($access_token[1]) && isset($access_token[2])) {
                $token_ip = $access_token[2];
                $UserIp = $this->request->getIpAddress();
                if ($UserIp == $token_ip) {
                    $login_email = $access_token[0];
                    $web_partner_id = $access_token[1];
                    $loginmodel = new LoginModel();
                    $user = $loginmodel->where('login_email', $login_email)->where('web_partner_id', $web_partner_id)->where('primary_user', 1)->first();
                    if ($user) {
                        $user['accessBySuperAdmin'] = 1;
                        $this->session->set('admin_user', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url('/dashboard'));
                    } else {
                        $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url('/login'));
                } else {
                    $message = array("StatusCode" => 1, "Message" => "access token invalid", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url('/login'));
                }
            } else {
                $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
                $this->session->setFlashdata('Message', $message);
                return redirect()->to(site_url('/login'));
            }
        }
    }
    public function forgot_password()
    {
        $rules = $this->validate([
            'registered_email' => [
                'label' => 'registered email',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => 'Please enter your email id.',
                    'valid_email' => 'Please enter a valid email id.'
                ]
            ],

        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        }
        $email = trim($this->request->getPost('registered_email'));
        $loginmodel = new LoginModel();
        $user = $loginmodel->where('login_email', $email)->first();
        if ($user) {
            $otp = mt_rand(1000, 9999);
            $this->session->set('forgot_password_email', $email);
            $this->session->set('forgot_password_otp', $otp);
            $data['otp'] = $otp;
            $message = view('Views/emails/otp-emails.php', $data);
            $Otp_message = "OTP to reset your login password is {$otp}. Please DO NOT SHARE the OTP with anyone. If not requested by you, please contact immediately on";
            $to_mob = $user['mobile_no'];
            $tempid = $this->template_id_forgot_password_otp;
            $sms_type = 'forgot password';
            send_sms($to_mob, $Otp_message, $tempid, $sms_type);
            send_email($email, $subject = "Forgot Password", $message, $email_type = 'Forgot Password', $attachment = null);
            $message = array("StatusCode" => 0, "Message" => "OTP Sent", 'temp_otp' => $otp, 'class' => 'success_popup', 'Reload' => 'false');
            return $this->response->setJSON($message);
        } else {
            $errors['registered_email'] = "Email does not exists";
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors), 'Reload' => 'false');
            return $this->response->setJSON($data_validation);
        }
    }
    public function validate_otp_password()
    {
        $rules = $this->validate([
            'OTP' => [
                'label' => 'OTP',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter OTP.',

                ]
            ],
            'new_password' => [
                'label' => 'new password',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Please enter new password.',
                    'min_length' => 'Password must be at least 8 digits'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $StatusCode = 1;
            $data = $this->request->getPost();
            $forgot_password_otp = session()->get('forgot_password_otp');
            $email = session()->get('forgot_password_email');
            if ($forgot_password_otp == $data['OTP']) {
                $StatusCode = 0;
                $loginmodel = new LoginModel();
                $update_password = [
                    "password" => md5($data['new_password'])
                ];
                $loginmodel->where("login_email", $email)->set($update_password)->update();
                $this->session->remove('forgot_password_email');
                $this->session->remove('forgot_password_otp');
            }
            if ($StatusCode == 0) {
                $loginmodel = new LoginModel();
                $user = $loginmodel->where('login_email', $email)->first();
                $WebPartnerModel = new WebPartnerModel();
                $WebPartnerModel = $WebPartnerModel->web_partner_list_details($user['web_partner_id']);
                $company_name = $WebPartnerModel['company_name'];
                $message = "Dear {$company_name}, your password is successfully reset. For more details contact ";
                $to_mob = $user['mobile_no'];
                $tempid = $this->template_id_password_reset;
                $sms_type = 'password reset';
                send_sms($to_mob, $message, $tempid, $sms_type);
                $message = array("StatusCode" => 0, "Message" => "Password changes successfully", 'Reload' => 'true', "Class" => "success_popup");
                $this->session->setFlashdata('Message', $message);
            } else {
                $errors['OTP'] = "Invalid OTP";
                $message = array("StatusCode" => 1, "ErrorMessage" => $errors, 'Reload' => 'false');
            }
            return $this->response->setJSON($message);
        }
    }
    public function signout()
    {
        $this->session->remove('admin_user');
        $this->session->remove('comapny_detail');
        $this->session->remove('admin_user_details');
        return redirect()->to(site_url('/login'));
    }
}