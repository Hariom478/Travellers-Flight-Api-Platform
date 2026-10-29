<?php

namespace Modules\Login\Controllers;

use CodeIgniter\I18n\Time;
use App\Modules\Login\Models\LoginModel;
use App\Controllers\BaseController;
use App\Modules\Login\Models\WebPartnerModel;


use App\Modules\Login\Models\AgentAccountLogModel;

use App\Modules\Login\Models\LoginLogsModel;


use Modules\Login\Config\Validation;
use phpDocumentor\Reflection\Type;

class Login extends BaseController
{
    public $title = "Travel Agency Login";

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->aadhar_folder = 'agent/aadhar';
        $this->pan_folder = 'agent/pan-card';
        helper('Modules\Login\Helpers\login');

        $this->verify_url = 'https://api.cashfree.com/verification/';
        $this->x_client_id = "";
        $this->x_client_secret = "";
        $this->signature = getSignature($this->x_client_id);
        $this->credientials = ['x_client_id' => $this->x_client_id, 'x_client_secret' => $this->x_client_secret, 'signature' => $this->signature];

        $this->template_id_forgot_password_otp = "";
        $this->template_id_mobile_verification_otp = "";
        $this->template_id_user_credientils = "";
        $this->template_id_user_id = "";
        $this->template_id_forgot_password_otp = "";
        $this->template_id_password_reset = "";
        $this->web_partner_details = web_partner_details;
     
        $this->web_partner_id = web_partner_details['id'];
    }

    public function index()
    {
        if ($this->session->get('admin_user')) {
            return redirect()->to(site_url('/flight'));
        }

        if ($this->request->getMethod() == 'post') {

            $rules = $this->validate([
                'user_email' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
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
                $data=    [
                    'title' => $this->title,
                    'validation' => $this->validator,
                    'view' => 'Login\Views\login',
                ];
                return view('template/default-layout', $data);
            }

            $email = trim($this->request->getPost('user_email'));
            $password = $this->request->getPost('user_password');
            $loginmodel = new LoginModel();


            $user = $loginmodel->check_user($email,$password,$this->web_partner_id);
            if ($user) {
                $user_status = $user['status'];
                if ($user_status == "active") {
                    $this->session->set('wl_agent', $user);
                    $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                    $this->session->setFlashdata('Message', $message);


                    $LoginLogsModel = new LoginLogsModel();
                    $agent = $this->request->getUserAgent();
                    $loginLogs = [
                        "web_partner_id" => $this->web_partner_id,
                        "user_id" => $user['id'],
                        "user_name" => $user['first_name'],
                        "login_browser" => $agent->getBrowser() . ' ' . $agent->getVersion(),
                        'platform' => $agent->getPlatform(),
                        'role' => 'agent',
                        "login_time" => create_date(),
                        "login_ip_address" => $this->request->getIpAddress()
                    ];

                    $LoginLogsModel->insert($loginLogs);

                    return redirect()->to(site_url('/flight'));

                } else {
                    $message = array("StatusCode" => 1, "Message" => "Your account has been not activated yet.Please contact your system administrator", "Class" => "error_popup");
                }

            } else {
                $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
            }
            $this->session->setFlashdata('Message', $message);
            return redirect()->to(site_url('/login'));
        }

        $this->session->remove('wl_agent');
        $data=    [
            'title' => $this->title,
            'validation' => $this->validation,
            'view' => 'Login\Views\login',
        ];
        return view('template/default-layout', $data);
    }
    public function access_account()
    {

        if ($this->request->getMethod() == 'get') {
            $this->session->remove('admin_user');
            $this->session->remove('admin_comapny_detail');
            $this->session->remove('admin_user_details');
            $this->session->remove('wl_agent');
            $access_token = dev_decode_direct_access($this->request->uri->getSegment(2));

            $access_token = explode('-', $access_token);
            if (isset($access_token[0]) && isset($access_token[1]) && isset($access_token[2]) && isset($access_token[3])) {
                $token_ip = $access_token[3];
                $UserIp = $this->request->getIpAddress();
                if ($UserIp == $token_ip) {
                    $login_email = $access_token[0];
                    $user_id = $access_token[1];
                    $web_partner_id = $access_token[2];
                    $loginmodel = new LoginModel();

                    $user = $loginmodel->check_admin_user($login_email,$user_id,$web_partner_id);
                    if ($user) {
                        $user_status = $user['status'];
                        if ($user_status == "active") {
                        $user['accessByAdmin']  =  1;
                        $this->session->set('wl_agent', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url('/flight'));
                        }else{
                            $message = array("StatusCode" => 1, "Message" => "Your account has been not activated yet.Please contact your system administrator", "Class" => "error_popup"); 
                        }
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
    public function signout()
    {

        $this->session->remove('wl_agent');
        $this->session->remove('admin_comapny_detail');
        $this->session->remove('admin_user_details');
        return redirect()->to(site_url('/login'));

    }

    public function signup_successful()
    {
        $data = [
            'title' => $this->title,
            'view'=>'Login\Views\signup-successful'
        ];
        return view('template/default-layout', $data);
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

            $company_support_no = $this->web_partner_details['support_no'];
            $company_support_email = $this->web_partner_details['support_email'];
            $message = "OTP to reset your login password is {$otp}. Please DO NOT SHARE the OTP with anyone. If not requested by you, please contact immediately on {$company_support_no} or {$company_support_email}";

            //$to_mob = $user['mobile_no'];
            $tempid = $this->template_id_forgot_password_otp;
            $sms_type = 'forgot password';
            //send_sms($to_mob, $message, $tempid, $sms_type);


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

                $user = $loginmodel->getCompanyName($email);


                $company_name = $user['company_name'];

                $company_support_no = $this->web_partner_details['support_no'];
                $company_support_email = $this->web_partner_details['support_email'];

                $message = "Dear {$company_name}, your password is successfully reset. For more details contact {$company_support_no} or {$company_support_email}";


               /* $to_mob = $user['mobile_no'];
                $tempid = $this->template_id_password_reset;
                $sms_type = 'password reset';
                send_sms($to_mob, $message, $tempid, $sms_type);*/

                $message = array("StatusCode" => 0, "Message" => "Password changes successfully", 'Reload' => 'true', "Class" => "success_popup");
                $this->session->setFlashdata('Message', $message);
            } else {
                $errors['OTP'] = "Invalid OTP";
                $message = array("StatusCode" => 1, "ErrorMessage" => $errors, 'Reload' => 'false');
            }
            return $this->response->setJSON($message);
        }
    }

    public function generate_otp()
    {
        if ($this->request->isAJAX()) {
            $validate = new Validation();
            $data = $this->request->getPost();
            $type = $data['type'];

            if ($type == 'mobile') {
                $validate->generate_otp['data_key']['label'] = "mobile number";
                $validate->generate_otp['data_key']['rules'] = "trim|required|numeric|exact_length[10]";
            } elseif ($type == 'email') {
                $validate->generate_otp['data_key']['label'] = "email";
                $validate->generate_otp['data_key']['rules'] = "trim|required|valid_email|is_unique[agent_users.login_email]'";
            }
            $validate->generate_otp['data_key']['errors'] = 'Please enter valid ' . $type;
            $rules = $this->validate($validate->generate_otp);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if ($type == 'email') {
                    if ($errors['data_key'] == "The email field must contain a unique value.") {
                        $errors['email_id'] = "Email already exists please use another email";
                    } else {
                        $errors['email_id'] = $errors['data_key'];
                    }

                } elseif ($type == "mobile") {
                    $errors['user_mobile_no'] = $errors['data_key'];
                }
                unset($errors['data_key']);
                $data_validation = array("StatusCode" => 2, "ErrorMessage" => array_filter($errors), 'type' => $data['type']);
                return $this->response->setJSON($data_validation);
            } else {

                if ($type == 'mobile') {
                    $otp = mt_rand(1000, 9999);
                    $this->session->set('MobileOTP', $otp);

                    $company_support_no = $this->web_partner_details['support_no'];
                    $company_support_email = $this->web_partner_details['support_email'];

                    $message = "Dear Travel Partner, your OTP to Verify mobile is  {$otp}.DO NOT SHARE the OTP. If you did not request an OTP contact us on {$company_support_no} or {$company_support_email}";

                    $to_mob = $data['data_key'];
                    $tempid = $this->template_id_mobile_verification_otp;
                    $sms_type = 'signup mobile verification';
                    send_sms($to_mob, $message, $tempid, $sms_type);

                } else if ($data['type'] == 'email') {
                    $otp = mt_rand(1000, 9999);
                    $this->session->set('EmailOTP', $otp);
                    $data['otp'] = $otp;
                    $email = $data['data_key'];
                    $message = view('Views/emails/otp-emails.php', $data);
                    send_email($email, $subject = "Sign Up OTP", $message, $email_type = 'verification otp', $attachment = null);
                }
                $message = array("StatusCode" => 0, "Message" => "OTP Sent", 'type' => $data['type'], 'verify_status' => '', 'class' => 'success_popup');
            }
        } else {
            $message = array("StatusCode" => 1, "Message" => "error message", "Class" => "error_popup");
        }

        return $this->response->setJSON($message);
    }

    public function validate_otp()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();
            $type = $data['type'];
            $validate = new Validation();
            $validate->validate_otp['data_key']['label'] = "OTP";
            $validate->validate_otp['data_key']['rules'] = "trim|required|numeric";
            $rules = $this->validate($validate->validate_otp);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if ($type == 'email') {
                    $errors['email_otp'] = $errors['data_key'];
                } elseif ($type == "mobile") {
                    $errors['mobile_otp'] = $errors['data_key'];
                }
                unset($errors['data_key']);
                $data_validation = array("StatusCode" => 5, "ErrorMessage" => array_filter($errors), 'type' => $data['type']);
                return $this->response->setJSON($data_validation);
            } else {
                $StatusCode = 1;
                if ($data['type'] == 'mobile') {
                    $SessionMobileOTP = session()->get('MobileOTP');

                    if ($SessionMobileOTP == $data['data_key']) {
                        $StatusCode = 0;
                        $this->session->set('MobileVerify', 'success');
                        $this->session->remove('MobileOTP');
                    }
                    $mobile_or_email = 'mobile_otp';

                } elseif ($data['type'] == 'email') {
                    $SessionEmailOTP = session()->get('EmailOTP');
                    if ($SessionEmailOTP == $data['data_key']) {
                        $StatusCode = 0;
                        $this->session->set('EmailVerify', 'success');
                        $this->session->remove('EmailOTP');
                    }
                    $mobile_or_email = 'email_otp';

                }
                if ($StatusCode == 0) {
                    $message = array("StatusCode" => 3, "Message" => "$type verified Successfully", 'type' => $data['type'], 'verify_status' => 'success', "class" => "success_popup");
                } else {
                    $errors[$mobile_or_email] = "Invalid OTP";
                    $message = array("StatusCode" => 5, "ErrorMessage" => $errors, 'type' => $data['type']);
                }
            }
        } else {
            $message = array("StatusCode" => 1, "Message" => "error message",);
        }


        return $this->response->setJSON($message);
    }

    public function validate_pan()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();
            $validate = new Validation();
            $validate->validate_pan['pan_number']['label'] = "pan number";
            $validate->validate_pan['pan_number']['rules'] = "trim|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]";
            $rules = $this->validate($validate->validate_pan);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                if ($errors['pan_number'] == "The pan number field must contain a unique value.") {
                    $errors['pan_number'] = "Pan number already exists please use another pan number";
                }
                $data_validation = array("StatusCode" => 2, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data = [
                    'name' => '',
                    'pan' => $data['pan_number']
                ];

              $response = RequestPan($data, $this->verify_url . 'pan', $this->credientials);
            //   $response = array(
            //     'valid'=>1,
            //     'message'=>"BNZAA2318J is valid",
            //     'registered_name'=>'Karan Chadha'
            // );
                $this->session->set('PanResponse', $response);


                if (isset($response['valid']) && $response['valid'] == 1) {
                    $this->session->set('PanVerify', 'success');

                    $errors['pan_number'] = $response['message'];

                    $data_validation = array("StatusCode" => 0, "ErrorMessage" => array_filter($errors), 'name' => $response['registered_name']);
                    return $this->response->setJSON($data_validation);
                } else {
                    $errors['pan_number'] = $response['message'];
                    $data_validation = array("StatusCode" => 2, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }
            }
        }
    }


    public function validate_gst()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();
            $validate = new Validation();
            $validate->validate_pan['company_gst_no']['label'] = "gst number";
            $validate->validate_pan['company_gst_no']['rules'] = "trim|permit_empty|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]";
            $rules = $this->validate($validate->validate_pan);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 2, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $gst_data = [
                    'businessName' => '',
                    'GSTIN' => $data['company_gst_no']
                ];
                $response = Request($gst_data, $this->verify_url . 'gstin', $this->credientials);

                $this->session->set('GstResponse', $response);
                if (isset($response['valid']) && $response['valid'] == 1) {
                    $this->session->set('GstVerify', 'success');

                    $gst_state = substr($response['GSTIN'], 0, 2);

                    $gst_state_prefix_zero = substr($gst_state, 0, 1);
                    if ($gst_state_prefix_zero != 0) {
                        $gst_state = substr($response['GSTIN'], 0, 2);
                    } else {
                        $gst_state = substr($response['GSTIN'], 1, 1);
                    }
                    $legal_name_of_business = "";
                    if (isset($response['legal_name_of_business'])) {
                        $legal_name_of_business = $response['legal_name_of_business'];
                    }

                    $data_validation = array("StatusCode" => 0, "ErrorMessage" => $response['message'], 'name' => $legal_name_of_business, 'gst_state' => $gst_state);
                    return $this->response->setJSON($data_validation);
                } else {
                    $this->session->set('GstVerify', 'failed');
                    $errors['company_gst_no'] = $response['message'];
                    $data_validation = array("StatusCode" => 2, "ErrorMessage" => array_filter($errors));
                    return $this->response->setJSON($data_validation);
                }
            }
        }
    }


    public function state()
    {
        $LoginModel = new LoginModel();
        $data = $this->request->getPost();
        $id = trim($data['id']);
        $city_list = $LoginModel->state_list($id);

        if ($city_list) {
            foreach ($city_list as $state) {
                echo "<option value=" . $state['id'] . ">" . $state['name'] . "</option>";
            }
        }
    }

    public function city()
    {
        $LoginModel = new LoginModel();
        $data = $this->request->getPost();
        $id = trim($data['id']);
        $city_list = $LoginModel->city_list($id);

        if ($city_list) {
            foreach ($city_list as $city) {
                echo "<option value=" . $city['id'] . ">" . $city['name'] . "</option>";
            }
        }
    }


    public function register_agency()
    {

        $LoginModel = new LoginModel();
        $gst_state = $LoginModel->gst_state_list();
        $country_list = $LoginModel->country_list();

        $data = [
            'title' => $this->title,
            "gst_state" => $gst_state,
            "country_list" => $country_list
        ];

        if ($this->request->getMethod() == 'post') {
            $data = $this->request->getPost();

            $validate = new Validation();
            // if (isset($data['company_gst_no']) && $data['company_gst_no']) {
            //     $validate->webpartner_validation['gst_state_code']['label'] = "gst state";
            //     $validate->webpartner_validation['gst_state_code']['rules'] = "trim|required|numeric";
            // }
            $rules = $this->validate($validate->webpartner_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $this->session->set('MobileVerify', 'success');
                $resizeDim = array('width' => 360, 'height' => 200);

                $errors = [
                    'max_size' => 'Image size should not be more than 1024kb',
                    'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png,pdf '
                ];
                $validate = new Validation();
                // $field_name_pan1 = "pan_card1";


                // $pan_card1 = $this->request->getFile($field_name_pan1);
                // if ($pan_card1->getName() != '') {
                //     $validate->webpartner_validation[$field_name_pan1]['rules'] = "uploaded[$field_name_pan1]|max_size[$field_name_pan1,1024]|mime_in[$field_name_pan1,image/jpg,image/jpeg,image/png,application/pdf]";
                //     $validate->webpartner_validation[$field_name_pan1]['errors'] = $errors;
                // }

                $permissions_agent = file_get_contents(FCPATH . "app/Libraries/permissions_agent.json");

                $city_data = $LoginModel->city_list_row($data['city']);
                $data['city'] = $city_data['name'];

                $country_data = $LoginModel->country_list_row($data['country']);
                $data['country'] = $country_data['name'];

                $state_data = $LoginModel->state_list_row($data['state']);
                $data['state'] = $state_data['name'];

                $PanVerify = session()->get('PanVerify');
                $MobileVerify = session()->get('MobileVerify');
                $EmailVerify = session()->get('EmailVerify');

                $PanResponse = session()->get('PanResponse');

                $GstResponse = null;
                $verification_condition = null;
                if (isset($data['company_gst_no']) && $data['company_gst_no']) {
                   /*  $GstVerify = session()->get('GstVerify'); */
                   $GstVerify = "success";
                    $GstResponse = session()->get('GstResponse');

                    $verification_condition = "$MobileVerify == 'success' && $EmailVerify == 'success'";
                } else {
                    $verification_condition = "$EmailVerify == 'success'";
                }

                if ($verification_condition) {

                    $LoginModel = new LoginModel();

                    #use getFile() for single image or getFiles() for multiple image
                    $field_name = 'pan_card';
                    $file = $this->request->getFile($field_name);
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->pan_folder, $resizeDim);

                    $field_name_aadhaar = 'aadhaar_scan_copy';
                    $file = $this->request->getFile($field_name_aadhaar);
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload_aadhaar = image_upload($file, $field_name_aadhaar, $this->aadhar_folder, $resizeDim);

                    if ($image_upload['status_code'] == 0 && $image_upload_aadhaar['status_code'] == 0) {

                        $email_id = $data['email_id'];
                        $password = md5(trim($data['user_password']));

                        $raw_password = trim($data['user_password']);

                        $mobile_isd = "+91";
                        $mobile_no = $data['user_mobile_no'];
                        $street = $data['address'];
                        $city = $data['city'];
                        $state = $data['state'];
                        $country = $data['country'];
                        $pin_code = $data['pincode'];

                        $first_name = $data['first_name'];
                        $last_name = $data['last_name'];


                        unset(
                            $data['email_id'],
                            $data['user_password'],
                            $data['user_mobile_no'],
                            $data['pincode'],
                            $data['mobile_otp'],
                            $data['email_otp'],
                            $data['confirm_password'],
                            $data['first_name'],
                            $data['last_name'],
                        );
                        $data['pincode'] = $pin_code;
                        $data['pincode'] = $pin_code;


                        $data['created'] = create_date();
                        $data['pan_card'] = $image_upload['file_name'];
                        $data['aadhaarNo'] = $data['aadhaar_no'];
                        $data['user_access'] = $permissions_agent;
                        $data['aadhar_scan_copy'] =$image_upload_aadhaar['file_name'];  
                        $data['pan_verification'] = json_encode($PanResponse);
                        $data['gst_verification'] = json_encode($GstResponse);
                        $agent_details = [
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email_id'=>$email_id,
                            //'mobile_isd' => $mobile_isd,
                            'mobile_number' => $mobile_no,
                            'email_id' => $email_id,
                            'password' => $password,
                            'address' => $street,
                            'status' => 'inactive',
                            'city' => $city,
                            'state' => $state,
                            'country' => $country,
                            'pincode' => $pin_code,
                            'company_name'=>$data['company_name'],
                            'pan_number'=>$data['pan_number'],
                            'aadhaar_no'=>$data['aadhaarNo'],
                            'gst_number'=>$data['company_gst_no'],
                            'pan_scan_copy'=>$data['pan_card'],
                            'pan_holder_name' => $data['pan_name'],
                            'aadhar_scan_copy'=> $data['aadhar_scan_copy'],
                            'pan_verification'=>$data['pan_verification'],
                            'gst_verification'=>$data['gst_verification'],
                            'user_access'=> $permissions_agent,
                            'web_partner_id' => $this->web_partner_id,
                            'created' => create_date()
                        ];


                        $agent_list = array(
                            'user_access'=>$agent_details['user_access'],
                            'web_partner_id'=>$agent_details['web_partner_id'],
                            'agent_class' => 5,
                            'company_name' => $agent_details['company_name'],
                            'gst_number' => $agent_details['gst_number'],
                            'pan_holder_name'=>$agent_details['pan_holder_name'],
                            'pan_number' => $agent_details['pan_number'],
                            'pan_scan_copy' => $agent_details['pan_scan_copy'],
                            'aadhaar_no' => $agent_details['aadhaar_no'],
                            'aadhar_scan_copy' => $agent_details['aadhar_scan_copy'],
                            'address' => $agent_details['address'],
                            'country' => $agent_details['country'],
                            'state' => $agent_details['state'],
                            'city' => $agent_details['city'],
                            'pincode' => $agent_details['pincode'],
                            'status' => $agent_details['status'],
                            'created' => $agent_details['created']
                        );

                        if(!empty($agent_details['gst_number'])){
                            $agent_list['gst_state_code'] = substr($agent_details['gst_number'],0,2);
                        }

                        $agent_user = array(
                            'web_partner_id'=>$agent_details['web_partner_id'],
                            'login_email' => $agent_details['email_id'],
                            'password' => $agent_details['password'],
                            'first_name' => $agent_details['first_name'],
                            'last_name' => $agent_details['last_name'],
                            'mobile_no' => $agent_details['mobile_number'],
                            'street' => $agent_details['address'],
                            'city' => $agent_details['city'],
                            'state' => $agent_details['state'],
                            'country' => $agent_details['country'],
                            'pin_code' => $agent_details['pincode'],
                            'access_permission' => $permissions_agent,
                            'status' => $agent_details['status'],
                            'primary_user' => 1,
                            'created_date' => $agent_details['created']
                        );
                      
                        $agent_id = $LoginModel->insertData("agent",$agent_list);

                        $update_agent['company_id'] = $this->web_partner_details['agent_pre_fix'] . $agent_id;
                        
                        $agent_user['agent_id'] = $agent_id;
                        $LoginModel->updateData('agent',['id'=>$agent_id],$update_agent);

                        $LoginModel->insert($agent_user);

                        $log['created'] = create_date();
                        $log['web_partner_id'] = $this->web_partner_id;
                        $log['wl_agent_id'] = $agent_id;
                        $log['credit'] = 0;
                        $log['balance'] = 0; 
                        $log['transaction_type'] = 'credit';
                        $log['action_type'] = 'recharge';
                        $log['role'] = 'web_partner';
                        $log['acc_ref_number'] = mt_rand(100000, 999999);
                        $log['remark'] = 'Account created with 0 balance ' . $this->web_partner_details['company_name'];
                        $AgentAccountLogModel = new AgentAccountLogModel();
                        $account_log_added = $AgentAccountLogModel->insert($log);
 
                        $data['name'] = $data['company_name'];
                        //$message = view('Views/emails/registration-success-email', $data);

                        //send_email($email_id, $subject = "Agency Registration", $message, $email_type = 'Agency Registration', $attachment = null);
                        $company_name = $data['company_name'];

                        //$message = "Dear {#var#},Thanks for registering with {$company_name}. your login details are as follows. Username-{$email_id} ,Password-{$raw_password}";

                        $to_mob = $mobile_no;
                        $tempid = $this->template_id_user_credientils;
                        $sms_type = 'account credentials';
                        //send_sms($to_mob, $message, $tempid, $sms_type);


                          $message = "Dear {$company_name}, Your Email {$email_id} has been successfully registered with us. Your user id is : {$agent_id}.Team ".$this->web_partner_details['company_name'];


                        $tempid = $this->template_id_user_id;
                        $sms_type = 'user id';
                        //send_sms($to_mob, $message, $tempid, $sms_type);

                        $redirect_url = site_url('login/signup-successful');

                        if ($agent_id) {
                            $message = array("StatusCode" => 3,"redirect_url"=>$redirect_url, "Message" => "Agency successfully registered,your account will be activated shortly", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Agency  Not Registered", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Verification Failed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
        $data['view']=   'Login\Views\register_agency';
        return view('template/default-layout', $data);
    }
}
