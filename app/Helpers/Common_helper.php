<?php

use App\Models\CommonModel;
use CodeIgniter\I18n\Time;

require 'vendor/phpmailer/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * ----------------------------------------------------------------------
 * This file is part of application used for common functions
 * -----------------------------------------------------------------------
 */

/**
 * -------------------------------------
 * Pretty print format of array
 * -------------------------------------
 */

if (!function_exists('pr')) {
    function pr($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}

if (!function_exists('limitTextChars')) {
    function limitTextChars($content = false, $limit = false, $stripTags = false, $ellipsis = false)
    {
        if ($content && $limit) {
            $content = ($stripTags ? strip_tags($content) : $content);
            $ellipsis = ($ellipsis ? "..." : $ellipsis);
            $content = mb_strimwidth($content, 0, $limit, $ellipsis);
        }
        return $content;
    }
}
/**
 * -------------------------------------
 * service_log Function
 * -------------------------------------
 */

if (!function_exists('service_log')) {
    function service_log($service, $action_type, $service_log)
    {
        $AirlineString = "";
        if (isset($service_log['AirlineString'])) {
            $AirlineString = $service_log['AirlineString'];
        }
        switch ($service) {
            case "hotel":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . '<br/>' . 'City:' . $service_log['City'] . '<br/>' . 'CheckInDate:' . $service_log['CheckInDate'] . '<br/>' . 'CheckInDate:' . $service_log['CheckOutDate'];
                break;
            case "bus":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . '<br/>' . 'Sector:' . $service_log['Sector'] . '<br/>' . 'TravelDate:' . $service_log['TravelDate'];
                break;
            case "flight":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . '<br/>' . 'Sector:' . $service_log['Sector'] . '<br/>' . 'TravelDate:' . (isset($service_log['TravelDate']) ? $service_log['TravelDate'] : 'Not specified') . '<br/>' . 'Airline:' . $AirlineString;
                break;
            case "holiday":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . '<br/>' . 'Sector:' . $service_log['Sector'];
                break;
            case "visa":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . '<br/>' . 'Sector:' . $service_log['Sector'] . '';
                break;
            default:
                $service_log_data = null;
                break;
        }
        return $service_log_data;
    }
}

/**
 * -------------------------------------
 * service_log Function
 * -------------------------------------
 */

if (!function_exists('service_log_link')) {
    function service_log_link($service, $ref_no)
    {
        $pre_fix = super_admin_website_setting['pre_fix'];

        /*$booking_ref_no = $pre_fix . $ref_no;*/
        $booking_ref_no = $ref_no;
        switch ($service) {
            case "hotel":
                $service_log_data = site_url('hotel/details/') . $booking_ref_no;
                break;
            case "bus":
                $service_log_data = site_url('bus/details/') . $booking_ref_no;
                break;
            case "flight":
                $service_log_data = site_url('flight/details/') . $booking_ref_no;
                break;
            case "holiday":
                $service_log_data = site_url('holiday/holiday-booking-details/') . $booking_ref_no;
                break;
            case "visa":
                $service_log_data = site_url('visa/visa-booking-details/') . $booking_ref_no;
                break;
            default:
                $service_log_data = null;
                break;
        }
        return $service_log_data;
    }
}


/**
 * -------------------------------------
 * service_log Function
 * -------------------------------------
 */

if (!function_exists('service_log_excel')) {
    function service_log_excel($service, $action_type, $service_log)
    {
        $AirlineString = "";
        if (isset($service_log['AirlineString'])) {
            $AirlineString = $service_log['AirlineString'];
        }
        switch ($service) {
            case "hotel":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'City:' . $service_log['City'] . 'CheckInDate:' . $service_log['CheckInDate'] . 'CheckInDate:' . $service_log['CheckOutDate'];
                break;
            case "bus":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'] . 'TravelDate:' . $service_log['TravelDate'];
                break;
            case "flight":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'] . 'TravelDate:' . $service_log['TravelDate'] . 'Airline:' . $AirlineString;
                break;
            case "holiday":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'];
                break;
            case "visa":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'] . '';
                break;
            default:
                $service_log_data = null;
                break;
        }
        return $service_log_data;
    }
}

/**
 * -------------------------------------
 * Pretty print format  with die function of array
 * -------------------------------------
 */

if (!function_exists('prd')) {
    function prd($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        die();
    }
}

/**
 * -------------------------------------
 * Get Last Modify Time Of File
 * -------------------------------------
 */

if (!function_exists('last_modifytime')) {
    function last_modifytime($filename, $QReq = null)
    {
        if (file_exists($filename)) {
            if ($QReq == null) {
                return "?" . filemtime($filename);
            } else {
                return filemtime($filename);
            }
        }
    }
}


/**
 * -------------------------------------
 * Permission access function and module
 * -------------------------------------
 */

if (!function_exists('permission_access')) {
    function permission_access($module_name, $function_name)
    {
        if (isset(admin_cookie_data()['admin_user_access'][$module_name])) {
            if (admin_cookie_data()['admin_user_access'][$module_name][$module_name . "_Module"] == "active") {
                $module_exist = admin_cookie_data()['admin_user_access'][$module_name];
                if (isset($module_exist[$function_name])) {
                    if ($module_exist[$function_name] == "active") {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


/**
 * -------------------------------------
 * Permission access Error funtion and module
 * -------------------------------------
 */

if (!function_exists('permission_access_error')) {
    function permission_access_error($module_name, $function_name = null)
    {
        if (isset(admin_cookie_data()['admin_user_access'][$module_name])) {
            if (admin_cookie_data()['admin_user_access'][$module_name][$module_name . "_Module"] == "active") {
                $module_exist = admin_cookie_data()['admin_user_access'][$module_name];
                if (isset($module_exist[$function_name])) {
                    if ($module_exist[$function_name] == "active") {
                        return true;
                    } else {
                        return access_denied();
                    }
                } else {
                    return access_denied();
                }
            } else {
                return access_denied();
            }
        } else {
            return access_denied();
        }
    }
}


/**
 * -------------------------------------
 * Custom error page for errors
 * -------------------------------------
 */

if (!function_exists('access_denied')) {
    function access_denied()
    {
        echo view(
            "errors/html/custom_error",
            [
                'error_title' => "Permission Denied",
                'error_message' => "You don't have permission to access this page",
                'error_code' => 403
            ]
        );
        die();
    }
}


/**
 * -------------------------------------
 * Active Top Nav baar Controller Function
 * -------------------------------------
 */

if (!function_exists('active_nav')) {
    function active_nav($controller_name)
    {
        $router = service('router');
        $class_name = $router->controllerName();
        $classparm = explode("\\", $class_name);
        $getcontrollername = end($classparm);
        if ($controller_name == $getcontrollername) {
            echo "active";
        } else {
            return null;
        }
    }
}


/**
 * -------------------------------------
 * Active Top Nav baar Controller Function
 * -------------------------------------
 */

if (!function_exists('active_tab')) {
    function active_tab($controller_name)
    {
        $router = service('router');
        $class_name = $router->controllerName();
        $classparm = explode("\\", $class_name);
        $getcontrollername = end($classparm);
        if ($controller_name == $getcontrollername) {
            echo "current";
        } else {
            return null;
        }
    }
}


/**
 * -------------------------------------
 * Active Top header Controller Function
 * -------------------------------------
 */

if (!function_exists('active_header')) {
    function active_header($controller_name)
    {
        $router = service('router');
        $class_name = $router->controllerName();
        $classparm = explode("\\", $class_name);
        $getcontrollername = end($classparm);
        if ($controller_name == $getcontrollername) {
            echo "active";
        } else {
            return null;
        }
    }
}


/**
 * -------------------------------------
 * CRM Active Menu Controller and method Active
 * -------------------------------------
 */
if (!function_exists('active_list_mod')) {
    function active_list_mod($controller_name, $function_name)
    {
        $router = service('router');
        $class_name = $router->controllerName();
        $classparm = explode("\\", $class_name);
        $getcontrollername = end($classparm);
        $method = $router->methodName();
        $request = service('request');
        if ($controller_name == $getcontrollername) {
            if ($request->uri->getTotalSegments() >= 3 && $request->uri->getSegment(3)) {

                if ($request->uri->getSegment(3) == $function_name) {
                    echo "active";
                }
            } else {
                if ($function_name == $method) {
                    echo "active";
                } else {
                }
            }
        } else {
            return null;
        }
    }
}


/**
 * -------------------------------------
 * Send SMS Function
 * -------------------------------------
 */

if (!function_exists('send_sms')) {
    function send_sms($to_mob, $message, $tempid, $sms_type, $extraprameter = array())
    {

        $user_name = "";
        $password = "";
        $entityid = "";
        $service = null;
        $booking_id = null;
        $sending_type = null;
        if (!empty($extraprameter)) {
            $service = $extraprameter['service'];
            $booking_id = $extraprameter['booking_id'];
            $sending_type = $extraprameter['sending_type'];
        }
        $message = urlencode($message);

        $request_url = "http://103.16.101.52:8080/sendsms/bulksms?username={$user_name}&password={$password}&type=0&dlr=1&destination={$to_mob}&source=TURSTA&message={$message}&entityid={$entityid}&tempid={$tempid}";

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            )
        );

        $response = curl_exec($curl);

        curl_close($curl);


        $response_status = explode('|', $response);

        if (isset($response_status[0]) && $response_status[0] == 1701) {
            $sms_status = 'success';
        } else {
            $sms_status = 'pending';
        }

        $request_data = service('request');
        $store_data = [
            'web_partner_id' => isset(admin_cookie_data()['admin_user_details']['web_partner_id']) ? admin_cookie_data()['admin_user_details']['web_partner_id'] : null,
            'to_sms' => $to_mob,
            'status' => $sms_status,
            'sms_type' => $sms_type,
            'message' => $message,
            'sms_api_response' => $response,
            'role' => 'web_partner',
            'ip_address' => $request_data->getIPAddress(),
            'request' => $request_url,
            'service' => $service,
            'booking_id' => $booking_id,
            'sending_type' => $sending_type,
            'created' => create_date()
        ];

        $commonmodel = new CommonModel();

        $commonmodel->insertData('logs_sms', $store_data);
    }
}


/**
 * -------------------------------------
 * Send Email Function
 * -------------------------------------
 */

// if (!function_exists('send_email')) {
//     function send_email($to, $subject, $message, $email_type = null, $attachment = null, $extraprameter = array(), $param1 = null)
//     {


//         $email_settings = json_decode(whitelabel['email_setting'], true);

//         $service = null;
//         $booking_id = null;
//         $sending_type = null;
//         if (!empty($extraprameter)) {
//             $service = $extraprameter['service'];
//             $booking_id = $extraprameter['booking_id'];
//             $sending_type = $extraprameter['sending_type'];
//         }


//         $from_email = $email_settings['from_email'];
//         $cc = "";
//         $bcc = "";
//         if ($param1 == 'ticketing') {
//             $bcc = web_partner_details['support_email'] ?? "";
//             //$cc = isset(super_admin_website_setting['bcc_email'])?super_admin_website_setting['bcc_email']:"";
//         }


//         $mail = new PHPMailer(true);
//         $mail->isSMTP();
//         $mail->setFrom($from_email, web_partner_details['company_name']);
//         $mail->Username = $email_settings['email_id'];
//         $mail->Password = $email_settings['mail_password'];
//         $mail->Host = $email_settings['mail_server'];
//         $mail->Port = $email_settings['port'];

//         $mail->SMTPAuth = true;
//         $mail->SMTPSecure = 'tls';


//         $mail->addAddress($to);
//         if ($cc) {
//             $mail->addCC($cc);
//         }
//         if ($bcc) {
//             $mail->addBCC($bcc);
//         }

//         $mail->isHTML(true);
//         $mail->Subject = $subject;
//         $mail->Body = $message;

//         // print_r($mail);
//         $mail->ErrorInfo;

//         if ($mail->Send()) {
//             $status = "success";
//         } else {
//             $status = "pending";
//         }



//         $store_data = [
//             'web_partner_id' => web_partner_details['id'],
//             'from_email' => $from_email,
//             'status' => $status,
//             'subject' => $subject,
//             'message' => $message,
//             'to_email' => $to,
//             'bcc_email' => $bcc,
//             'cc_email' => $cc,
//             'email_type' => $email_type,
//             'service' => $service,
//             'booking_id' => $booking_id,
//             'sending_type' => $sending_type,
//             'created' => create_date()
//         ];

//         $commonmodel = new CommonModel();

//         $commonmodel->insertData('logs_email', $store_data);
//     }
// }

if (!function_exists('send_email')) {
    function send_email($to, $subject, $message, $email_type = null, $attachment = null, $extraprameter = array(), $param1 = null)
    {

        $email_settings = json_decode(whitelabel['email_setting'], true);


        $service = null;
        $booking_id = null;
        $sending_type = null;
        if (!empty($extraprameter)) {
            $service = $extraprameter['service'];
            $booking_id = $extraprameter['booking_id'];
            $sending_type = $extraprameter['sending_type'];
        }


        $from_email = $email_settings['from_email'];

        $cc = "";
        $bcc = "";
        if ($param1 == 'ticketing') {
            $bcc = web_partner_details['support_email'] ?? "";
            // $bcc = isset(super_admin_website_setting['bcc_email']) ? super_admin_website_setting['bcc_email'] : "";
            //$cc = isset(super_admin_website_setting['bcc_email'])?super_admin_website_setting['bcc_email']:"";
        }

        $email = \Config\Services::email();
        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = $email_settings['mail_server'];
        $config['SMTPPort'] = $email_settings['port'];
        $config['SMTPUser'] = $email_settings['email_id'];
        $config['SMTPPass'] = $email_settings['mail_password'];
        $config['SMTPCrypto'] = 'ssl';
        $config['mailType'] = 'html'; // or html
        $config['newline'] = "\r\n";
        $config['charset'] = 'utf-8';
        $config['wordWrap'] = true;

        $email->initialize($config);

        $email->setFrom($from_email, web_partner_details['company_name']);
        $email->setTo($to);
        if ($cc) {
            $email->setCC($cc);
        }
        if ($bcc) {
            $email->setBCC($bcc);
        }
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($attachment) {
            $email->attach($attachment);
        }
        if ($email->send()) {
            $status = "success";
        } else {

            $status = "pending";
        }


        $store_data = [
            'web_partner_id' => isset(admin_cookie_data()['admin_user_details']['web_partner_id']) ? admin_cookie_data()['admin_user_details']['web_partner_id'] : null,
            'from_email' => $from_email,
            'status' => $status,
            'subject' => $subject,
            'message' => $message,
            'to_email' => $to,
            'bcc_email' => $bcc,
            'cc_email' => $cc,
            'email_type' => $email_type,
            'service' => $service,
            'booking_id' => $booking_id,
            'sending_type' => $sending_type,
            'created' => create_date()
        ];

        $commonmodel = new CommonModel();

        $commonmodel->insertData('logs_email', $store_data);

    }
}

/**
 * -------------------------------------
 * Encode Function
 * -------------------------------------
 */
if (!function_exists('dev_encode')) {
    function dev_encode($string, $key = "", $url_safe = true)
    {
        if ($key == null || $key == "") {
            $key = "dev@traveltechnologysolution";
        }
        $encrypted = openssl_encrypt($string, "AES-128-ECB", $key);

        $encrypted = strtr(
            $encrypted,
            array(
                '+' => '.',
                '=' => '-',
                '/' => '~',
            )
        );

        return $encrypted;
    }
}

/**
 * -------------------------------------
 * Decode  Function
 * -------------------------------------
 */

if (!function_exists('dev_decode')) {
    function dev_decode($string, $key = "")
    {
        if ($key == null || $key == "") {
            $key = "dev@traveltechnologysolution";
        }
        $string = strtr(
            $string,
            array(
                '.' => '+',
                '-' => '=',
                '~' => '/',
            )
        );
        $decrypted = openssl_decrypt($string, "AES-128-ECB", $key);
        return $decrypted;
    }
}


/**
 * -------------------------------------
 * Decode  Function
 * -------------------------------------
 */

if (!function_exists('dev_decode_direct_access')) {
    function dev_decode_direct_access($string, $key = "")
    {
        if ($key == null || $key == "") {
            $key = "dev@traveltechnologysolution";
        }

        $string = strtr(
            $string,
            array(
                '.' => '+',
                '-' => '=',
                '~' => '/',
            )
        );

        $decrypted = openssl_decrypt($string, "AES-128-ECB", $key);
        return $decrypted;
    }
}

/**
 * -------------------------------------
 * Check Empty  Function
 * -------------------------------------
 */

if (!function_exists('check_empty')) {
    function check_empty($value)
    {
        if ($value) {
            return $value;
        } else {
            return "-";
        }
    }
}

/**
 * -------------------------------------
 * Create Date  Function
 * -------------------------------------
 */

if (!function_exists('create_date')) {
    function create_date()
    {
        return strtotime(date("Y-m-d G:i"));
    }
}

/**
 * -------------------------------------
 * Create Date Format  Function
 * -------------------------------------
 */

if (!function_exists('date_created_format')) {
    function date_created_format($created_date)
    {
        $timezone = web_partner_details['timezone'];
        ;
        date_default_timezone_set($timezone);
        return date("d M Y / g:i A", $created_date);
    }
}

/**
 * -------------------------------------
 * Time stump to date   Function
 * -------------------------------------
 */

if (!function_exists('timestamp_to_date')) {
    function timestamp_to_date($created_date)
    {
        $timezone = web_partner_details['timezone'];
        ;
        date_default_timezone_set($timezone);
        return date("d M Y ", $created_date);
    }
}

/**
 * -------------------------------------
 * Current Date Format  Function
 * -------------------------------------
 */

if (!function_exists('get_current_date')) {
    function get_current_date()
    {
        return date("d M Y");
    }
}

/**
 * -------------------------------------
 * PDF library Function  Function
 * -------------------------------------
 */

function pdf_lib($title = "", $content = "", $pdf_filename = "", $view = "")
{

    require_once APPPATH . 'ThirdParty/tcpdf/config/lang/eng.php';
    require_once APPPATH . 'ThirdParty/tcpdf/tcpdf.php';

    if ($title == '' || $title == null) {
        $title = "Download PDF";
    }

    if ($pdf_filename == '' || $pdf_filename == null) {
        $pdf_filename = "";
    }
    if ($view == true) {
        $pdf_view = "I";
    } else {
        $pdf_view = "D";
    }

    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //	$obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle($title);
    //  $obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    //	$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    // $obj_pdf->SetFont('helvetica', '', 9);

    $obj_pdf->SetMargins(3, 5, 3);

    $obj_pdf->setFontSubsetting(false);
    $obj_pdf->AddPage();
    ob_start(); // we can have any view part here like HTML, PHP etc
    ob_end_clean();
    $obj_pdf->writeHTML($content, true, true, true, false, '');
    $obj_pdf->Output($pdf_filename . ".pdf", $pdf_view);
}


/**
 * -------------------------------------
 * Staff List data   Function
 * -------------------------------------
 */

if (!function_exists('staff_list')) {
    function staff_list()
    {
        $commonmodel = new CommonModel();
        $web_partner_id = session()->get('admin_user')['web_partner_id'];
        return $commonmodel->staff_list($web_partner_id);
    }
}

function get_balance()
{
    $commonmodel = new CommonModel();
    $web_partner_id = session()->get('wl_agent')['web_partner_id'];
    $balance = $commonmodel->web_partner_balance($web_partner_id);
    if (isset($balance['balance'])) {
        $balance = $balance['balance'];
    } else {
        $balance = 0;
    }
    return $balance;
}


function wl_agent_get_balance()
{
    $commonmodel = new CommonModel();
    $web_partner_id = session()->get('wl_agent')['web_partner_id'];
    $wl_agent_id = session()->get('wl_agent')['agent_id'];
    $balance = $commonmodel->wl_agent_balance($web_partner_id, $wl_agent_id);

    if (isset($balance['balance'])) {
        $balance = $balance['balance'];
    } else {
        $balance = 0;
    }
    return $balance;
}


/**
 * -------------------------------------
 * Admin all Cookes data   Function
 * -------------------------------------
 */
if (!function_exists('admin_cookie_data')) {
    function admin_cookie_data()
    {
        $crm_user = session()->get('wl_agent');
        if (!empty($crm_user)) {
            $crm_comapny_detail = json_decode(dev_decode(session()->get('admin_comapny_detail')), true);
            $crm_user_details_data = json_decode(dev_decode(session()->get('admin_user_details')), true);

            if (empty($crm_comapny_detail) || empty($crm_user_details_data)) {

                $db = \Config\Database::connect();
                $query = $db->query('SELECT * FROM agent WHERE id=' . $crm_user['agent_id'] . ' LIMIT 1');
                $comapny_details_arr = $query->getRowArray();

                $query = $db->query('SELECT * FROM whitelabel_webpartner_setting WHERE web_partner_id=' . $crm_user['web_partner_id'] . ' LIMIT 1');
                $whitelabel_webpartner_setting_arr = $query->getRowArray();

                $query_user = $db->query('SELECT * FROM agent_users WHERE id=' . $crm_user['id'] . ' LIMIT 1');
                $admin_user_db = $query_user->getRowArray();

                $whitelabel_setting_data = dev_encode(json_encode($whitelabel_webpartner_setting_arr));
                $crm_comapny_details_data = dev_encode(json_encode($comapny_details_arr));
                $crm_users_data = dev_encode(json_encode($admin_user_db));
                session()->set('admin_comapny_detail', $crm_comapny_details_data);
                session()->set('whitelabel_setting_data', $whitelabel_setting_data);
                session()->set('admin_user_details', $crm_users_data);
            } else {
                $crm_comapny_details_data = session()->get('admin_comapny_detail');
                $crm_users_data = session()->get('admin_user_details');
                $whitelabel_setting_data = session()->get('whitelabel_setting_data');
            }

            $whitelabel_setting_data = json_decode(dev_decode($whitelabel_setting_data), true);

            $crm_users_data_cookie = json_decode(dev_decode($crm_users_data), true);
            $crm_comapny_details = json_decode(dev_decode($crm_comapny_details_data), true);
            $crm_user_access = json_decode($crm_users_data_cookie['access_permission'], true);

            return array(
                "admin_comapny_detail" => $crm_comapny_details,
                "admin_user_details" => $crm_users_data_cookie,
                "admin_user_access" => $crm_user_access,
                "whitelabel_setting_data" => $whitelabel_setting_data
            );
        }
    }
}


/**
 * -------------------------------------
 * CRM Country code list data   Function
 * -------------------------------------
 */

if (!function_exists('get_countary_code')) {
    function get_countary_code()
    {
        $jsondata = file_get_contents(FCPATH . "webroot/CountryCodes.json");
        return json_decode($jsondata, true);
    }
}





/**
 * -------------------------------------
 * Single/Multiple Image upload  Function
 * -------------------------------------
 */



if (!function_exists('image_upload')) {
    function image_upload($file, $field_name, $upload_folder, $resizeDim): array
    {
        $validation = \Config\Services::validation();
        $request_data = service('request');

        $msg = '';
        if (is_array($file)) {
            //code used for multiple files uploading
            $validation->setRules([
                $field_name => [
                    "uploaded[$field_name].0",
                    "mime_in[$field_name,image/jpg,image/jpeg,image/png,image/ico,image/icon,image/x-icon,text/ico,application/ico,image/svg,image/webp]",
                    "max_size[$field_name,1024]",
                ]
            ]);
            if ($validation->withRequest($request_data)->run()) {
                $newName = '';
                foreach ($file as $key => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $ImageName = str_replace(" ", "_", $img->getName());
                        $newNameRandom = create_date() . '_' . str_replace(['(', ')'], '', strtolower($ImageName));
                        if ($img->move(FCPATH . "./uploads/$upload_folder/", $newNameRandom)) {
                            /*---------generate thumbnail-----*/
                            $path = FCPATH . "./uploads/$upload_folder/" . $newNameRandom;
                            $thumbpath = FCPATH . "./uploads/$upload_folder/thumbnail/" . $newNameRandom;
                            $image = service('image');
                            $image->withFile($path)
                                ->resize($resizeDim['width'], $resizeDim['height'], true, 'height')
                                ->save($thumbpath);

                            $msg = 'file uploaded successfully';
                            $status_code = 0;
                            $newName .= $newNameRandom . ',';
                        } else {
                            $msg = $img->getErrorString() . " " . $img->getError();
                            $status_code = 1;
                        }
                    }
                }
                $newName = rtrim($newName, ",");
            } else {
                $msg = $validation->getError($field_name);
                $status_code = 1;
            }
        } else {

            $validation->setRules([
                $field_name => [
                    "uploaded[$field_name]",
                    "mime_in[$field_name,image/jpg,image/jpeg,image/png,image/ico,image/icon,image/x-icon,text/ico,application/ico,image/svg,image/webp]",
                    "max_size[$field_name,1024]",
                ]
            ]);

            if ($validation->withRequest($request_data)->run()) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $ImageFileName = str_replace(" ", "_", $file->getName());
                    $newName = create_date() . '_' . str_replace(['(', ')'], '', strtolower($ImageFileName));
                    /*  pr(FCPATH . "./uploads/$upload_folder/", $newName);exit; */
                    if ($file->move(FCPATH . "./uploads/$upload_folder/", $newName)) {

                        /*---------generate thumbnail-----*/
                        $path = FCPATH . "./uploads/$upload_folder/" . $newName;
                        $thumbpath = FCPATH . "./uploads/$upload_folder/thumbnail/" . $newName;
                        $image = service('image');
                        $image->withFile($path)
                            ->resize($resizeDim['width'], $resizeDim['height'], true, 'height')
                            ->save($thumbpath);

                        $msg = 'file uploaded successfully';
                        $status_code = 0;
                    } else {
                        $msg = $file->getErrorString() . " " . $file->getError();
                        $status_code = 1;
                    }
                }
            } else {
                $msg = $validation->getError($field_name);
                $status_code = 1;
            }
        }

        if ($status_code == 1) {
            $file_name = '';
        } else {
            $file_name = $newName;
        }

        $return_data = [
            'status_code' => $status_code,
            'message' => $msg,
            'file_name' => $file_name
        ];
        return $return_data;
    }
}


/**
 * -------------------------------------
 * Get file size information
 * -------------------------------------
 */

if (!function_exists('formatBytes')) {
    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('bytes', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}


/**
 * -------------------------------------
 * Access Denied Page data Function
 * -------------------------------------
 */

if (!function_exists('access_denied_page')) {
    function access_denied_page($dataaccess_type)
    {
        if ($dataaccess_type == "inactive") {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}

/**
 * -------------------------------------
 * Access Denied Page data Function
 * -------------------------------------
 */
if (!function_exists('custom_error_page')) {
    function custom_error_page($title, $message, $error_code)
    {
        echo view(
            "errors/html/custom_error",
            [
                'error_title' => $title,
                'error_message' => $message,
                'error_code' => $error_code
            ]
        );
        die();
    }
}


/**
 * -------------------------------------
 * holiday package includes
 * -------------------------------------
 */

if (!function_exists('package_includes')) {
    function package_includes()
    {
        $includes = ['Meal' => 'Meal', 'Transfer' => 'Transfer', 'Hotel' => 'Hotel', 'Flight', 'Optional' => 'Optional'];

        return $includes;
    }
}


/**
 * -------------------------------------
 * Round value decimal places
 * -------------------------------------
 */

if (!function_exists('round_value')) {
    function round_value($value, $places = 2)
    {
        return round($value, $places);
    }
}


/**
 * -------------------------------------
 * Price in number format with decimal places
 * -------------------------------------
 */

if (!function_exists('number_format_value')) {
    function number_format_value($value, $places = 2)
    {
        return number_format($value, $places);
    }
}

/**
 * -------------------------------------
 * Reference Number
 * -------------------------------------
 */

if (!function_exists('reference_number')) {
    function reference_number($bookingid, $service = null, $isDomestic = null, $action_type = null)
    {
        $prefix = "TW";
        if ($service == "Flight") {
            if ($isDomestic) {
                $prefix = "DOM";
            } else {
                $prefix = "INT";
            }
        }
        $financial_year = get_financial_year();
        return $prefix . '/' . $financial_year . '/' . $bookingid;
    }
}

/**
 * -------------------------------------
 * Get Financial Year
 * -------------------------------------
 */

if (!function_exists('get_financial_year')) {
    function get_financial_year()
    {
        $format = "y";
        $date = date_create(Time::now());
        if (date_format($date, "m") >= 4) {
            $financial_year = (date_format($date, $format)) . (date_format($date, $format) + 1);
        } else {
            $financial_year = (date_format($date, $format) - 1) . date_format($date, $format);
        }
        return $financial_year;
    }
}


if (!function_exists('booking_time_out')) {
    function booking_time_out($timestamp)
    {
        $addtime = 900; // 15 minute
        $newtime = $timestamp + $addtime;
        $currenttime = create_date();
        if ($currenttime > $newtime) {
            //return true;
            return false;
        } else {
            return false;
        }
    }
}



/**
 * -------------------------------------
 * Custom Money format
 * -------------------------------------
 */
if (!function_exists('custom_money_format')) {

    function custom_money_format($number)
    {
        $decimal = (string) ($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $delimiter .= ',';
            }
            $delimiter .= $money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if ($decimal != '0') {
            $result = $result . $decimal;
        }

        return $result;
    }
}

/**
 * ----------------------------------------------------------------------------
 * Get Cities From itinerary with day Stay
 * -----------------------------------------------------------------------------
 */
if (!function_exists('holiday_city_iternary_stay')) {
    function holiday_city_iternary_stay($cities)
    {
        $key = 0;
        $cityInc[$key] = array(
            'name' => $cities[0],
            'value' => 1
        );
        for ($i = 0; $i < count($cities) - 1; $i++) {
            if (strcasecmp($cities[$i], $cities[$i + 1]) == 0) {
                $cityInc[$key]['value'] += 1;
            } else {
                ++$key;
                $cityInc[$key]['name'] = $cities[$i + 1];
                $cityInc[$key]['value'] = 1;
            }
        }
        return $cityInc;
    }
}

/**
 * -------------------------------------
 * Generate Confirmation Number
 * -------------------------------------
 */
if (!function_exists('GenerateConfirmationNumber')) {
    function GenerateConfirmationNumber($service, $confirmationPrefix, $confirmationCounter)
    {
        $format = "y";
        $date = date_create(Time::now());
        if (date_format($date, "m") >= 4) {
            $financial_year = (date_format($date, $format)) . (date_format($date, $format) + 1);
        } else {
            $financial_year = (date_format($date, $format) - 1) . date_format($date, $format);
        }
        return $confirmationPrefix . "/" . $financial_year . "/" . $confirmationCounter;
    }
}


if (!function_exists('walletBalance')) {
    function walletBalance()
    {
        $web_partner_id = session()->get('wl_agent')['web_partner_id'];
        $wl_agent_id = session()->get('wl_agent')['agent_id'];
        $response = null;
        $commonmodel = new CommonModel();
        $balanceData = $commonmodel->wl_agent_balance($web_partner_id, $wl_agent_id);
        $balance = 0;
        $DepositeAmount = 0;
        if (isset($balanceData['balance'])) {
            $balance = floatval($balanceData['balance']);
        }
        $response['Balance'] = $balance;
        $DepositeAmount = $balance;
        $CheckCreditLimit = $commonmodel->check_agent_credit_limit($web_partner_id, $wl_agent_id);
        $DueAmount = 0;
        $CreditLimit = 0;
        $ExpireDate = "";
        $CreditStatus = 'NA';
        if ($response['Balance'] < 0) {
            $DueAmount = $response['Balance'];
            $DepositeAmount = 0;
            /* $response['Balance'] =0;  */
        }
        if ($CheckCreditLimit) {

            $curentDate = strtotime(date("Y-m-d"));
            $ExpireDate = date("d-m-Y", $CheckCreditLimit['credit_expire_date']);

            if ($CheckCreditLimit['credit_expire_date'] >= $curentDate && $CheckCreditLimit['credit_expire'] == "No") {

                $CreditLimit = $CheckCreditLimit['credit_limit'];
                $CreditStatus = 'Active';
                $response['Balance'] = (($response['Balance'] + $CreditLimit));
            } else {

                if ($response['Balance'] < 0) {
                    $response['Balance'] = 0;
                }
                $commonmodel->updateData("agent_credit_log", ["wl_agent_id" => $wl_agent_id], ["credit_expire" => "Yes"]);
                $commonmodel->insertData("agent_credit_log", ["web_partner_id" => $web_partner_id, "wl_agent_id" => $wl_agent_id, "credit_expire" => "Yes", "credit_expire_date" => $CheckCreditLimit['credit_expire_date'], "credit_limit" => 0]);
            }
        }
        $response['CreditLimit'] = floatval($CreditLimit);
        $response['DueAmount'] = floatval(abs($DueAmount));
        $response['CreditStatus'] = $CreditStatus;
        $response['WalletStatus'] = "Active";
        $response['DepositeAmount'] = $DepositeAmount;
        $response['ExpireDate'] = $ExpireDate;
        return $response;
    }
}
