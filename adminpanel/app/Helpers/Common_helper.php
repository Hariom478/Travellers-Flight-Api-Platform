<?php
//ss
use App\Models\CommonModel;
use CodeIgniter\I18n\Time; 

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


/**
 * -------------------------------------
 * service_log Function
 * -------------------------------------
 */

if (!function_exists('service_log')) {
    function service_log($service, $action_type, $service_log)
    {
        if (!isset ($service_log['TicketNo'])) {
            $service_log['TicketNo'] = '';
        }
        if (!isset ($service_log['AirlineString'])) {
            $service_log['AirlineString'] = '';
        }
        switch ($service) {
            case "hotel":
                $service_log_data = '<b>Ticket Created:' . '</b> ' . $service_log['PaxName'] . ' | ' . 'City:' . $service_log['City'] . '<br/>' . 'CheckInDate:' . date('d-m-Y', strtotime($service_log['CheckInDate'])) . ' | ' . 'CheckInDate:' . date('d-m-Y', strtotime($service_log['CheckOutDate']));
                break;
            case "bus":
                $service_log_data = '<b>Ticket Created:' . '</b> ' . $service_log['PaxName'] . ' | ' . 'Sector:' . $service_log['Sector'] . ' | ' . 'Travel Date:' . date('d-m-Y', strtotime($service_log['TravelDate']));
                break;
            case "flight":
                $service_log_data = '<b>Ticket Created:' . '</b> ' . $service_log['PaxName'] . ' | <b>' . $service_log['Sector'] . '</b> <br/> ' . 'TICKET NO:' . $service_log['TicketNo'] . ' | ' . 'Travel Date:' . date('d-m-Y', strtotime($service_log['TravelDate'])) . ' | ' . $service_log['AirlineString'];
                break;
            case "holiday":
                $service_log_data = '<b>Ticket Created:' . '</b> ' . $service_log['PaxName'] . ' | ' . 'Sector:' . $service_log['Sector'];
                break;
            case "visa":
                $service_log_data = '<b>Ticket Created:' . '</b> ' . $service_log['PaxName'] . '<br/>' . 'Sector:' . $service_log['Sector'] . '';
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

        /*   $booking_ref_no = $pre_fix . $ref_no; */
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
        if (!isset ($service_log['TicketNo'])) {
            $service_log['TicketNo'] = '';
        }
        if (!isset ($service_log['AirlineString'])) {
            $service_log['AirlineString'] = '';
        }
        switch ($service) {
            case "hotel":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'City:' . $service_log['City'] . 'CheckInDate:' . $service_log['CheckInDate'] . 'CheckInDate:' . $service_log['CheckOutDate'];
                break;
            case "bus":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'] . 'TravelDate:' . $service_log['TravelDate'];
                break;
            case "flight":
                $service_log_data = ucfirst($service) . ' ' . ucfirst($action_type) . ':' . ' ' . $service_log['PaxName'] . 'Sector:' . $service_log['Sector'] . 'TravelDate:' . $service_log['TravelDate'] . 'Airline:' . $service_log['AirlineString'];
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

if (!function_exists('get_fare_type')) {
    function get_fare_type()
    {
        $data_set = [
            "FF" => "FF",
            "SPLC" => "SPLC",
            "Coupon" => "Coupon",
            "TBF" => "TBF",
            "SPLS" => "SPLS",
            "CPNS" => "CPNS",
            "CDF" => "CDF",
            "SME" => "SME",
            "Corporate" => "Corporate",
            "FLX" => "FLX",
            "InstantPur" => "InstantPur",
            "Regular" => "Regular",
            "Publish" => "Publish"
        ];

        return $data_set;
    }
}


if (!function_exists('markup_used_for')) {
    function markup_used_for()
    {
        $data = [
            'BackOffice' => 'Back Office'
        ];
        if (isset (admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
            if (isset (admin_cookie_data()['whitelabel_setting_data']['b2c_business']) && admin_cookie_data()['whitelabel_setting_data']['b2c_business'] == "active") {
                $data['WhiteLabelB2C'] = 'WhiteLabelB2C';
            }
            if (isset (admin_cookie_data()['whitelabel_setting_data']['b2b_business']) && admin_cookie_data()['whitelabel_setting_data']['b2b_business'] == "active") {
                $data['WhiteLabelB2B'] = 'WhiteLabelB2B';
            }
        }
        return $data;
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
 * Permission access funtion and module
 * -------------------------------------
 */

if (!function_exists('permission_access')) {
    function permission_access($module_name, $function_name)
    {
        if (isset (admin_cookie_data()['admin_user_access'][$module_name])) {
            if (admin_cookie_data()['admin_user_access'][$module_name][$module_name . "_Module"] == "active") {
                $module_exist = admin_cookie_data()['admin_user_access'][$module_name];
                if (isset ($module_exist[$function_name])) {
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
    function permission_access_error($module_name, $function_name)
    {
        if (isset (admin_cookie_data()['admin_user_access'][$module_name])) {
            if (admin_cookie_data()['admin_user_access'][$module_name][$module_name . "_Module"] == "active") {
                $module_exist = admin_cookie_data()['admin_user_access'][$module_name];
                if (isset ($module_exist[$function_name])) {
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
        if (!empty ($extraprameter)) {
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

        if (isset ($response_status[0]) && $response_status[0] == 1701) {
            $sms_status = 'success';
        } else {
            $sms_status = 'pending';
        }

        $request_data = service('request');
        $store_data = [
            'web_partner_id' => isset (admin_cookie_data()['admin_user_details']['web_partner_id']) ? admin_cookie_data()['admin_user_details']['web_partner_id'] : null,
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


 
if (!function_exists('send_email')) {
    function send_email($to, $subject, $message, $email_type = null, $attachment = null,$extraprameter =  array(),$param1=null)
    {
 
        $email_settings = json_decode(whitelabel['email_setting'], true);

        $service =  null;
        $booking_id =  null;
        $sending_type =  null;
        if(!empty($extraprameter)){
            $service =  $extraprameter['service'];
            $booking_id =  $extraprameter['booking_id'];
            $sending_type =  $extraprameter['sending_type'];
        }
        

        $from_email = $email_settings['from_email'];

        $cc = "";
        $bcc = "";
        if ($param1=='ticketing'){
            $bcc = isset(whitelabel['bcc_email'])?whitelabel['bcc_email']:"";
            //$cc = isset(super_admin_website_setting['bcc_email'])?super_admin_website_setting['bcc_email']:"";
        }


        $email = \Config\Services::email();
        $config['protocol'] = 'mail';
        $config['SMTPHost'] = $email_settings['mail_server'];
        $config['SMTPPort'] = $email_settings['port'];
        $config['SMTPUser'] = $email_settings['email_id'];
        $config['SMTPPass'] = $email_settings['mail_password'];
        $config['SMTPCrypto'] = '';
        $config['mailType'] = 'html'; // or html
        $config['newline'] = "\r\n";
        $config['charset'] = 'utf-8';
        $config['wordWrap'] = true;

        $email->initialize($config);

        $email->setFrom($from_email, super_admin_website_setting['company_name']);
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
//            echo $email->printDebugger();
//             die;
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
            'service' =>  $service,
            'booking_id' =>  $booking_id,
            'sending_type' =>  $sending_type,
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

        return base64_decode($string);
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

// if (!function_exists('date_created_format')) {
//     function date_created_format($created_date)
//     {
//         $timezone = admin_cookie_data()['admin_comapny_detail']['timezone'];
//         date_default_timezone_set($timezone);
//         return date("d M Y / g:i A", $created_date);
//     }
// }






if (!function_exists('date_created_format')) {
    function date_created_format($created_date)
    {
        // Replace this with your actual function to fetch admin data
        $admin_data = admin_cookie_data();

        if ($admin_data && isset ($admin_data['admin_comapny_detail']['timezone'])) {
            $timezone = $admin_data['admin_comapny_detail']['timezone'];

            // Ensure $created_date is a valid timestamp
            if (!is_numeric($created_date)) {
                $created_date = strtotime($created_date);
            }

            if ($created_date !== false) {
                date_default_timezone_set($timezone);
                return date("d M Y / g:i A", $created_date);
            }
        }

        return " "; // Return a default value if there's no valid date or timezone
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
        $timezone = admin_cookie_data()['admin_comapny_detail']['timezone'];
        date_default_timezone_set($timezone);
        return date("d M Y ", $created_date);
    }
}
/**
 * -------------------------------------
 * Time stump to date   Function
 * -------------------------------------
 */

if (!function_exists('date_to_custom_date')) {
    function date_to_custom_date($date)
    {
        $timezone = admin_cookie_data()['admin_comapny_detail']['timezone'];
        date_default_timezone_set($timezone);
        return date("d M Y ", strtotime($date));
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
    $web_partner_id = session()->get('admin_user')['web_partner_id'];
    $balance = $commonmodel->web_partner_balance($web_partner_id);

    if (isset ($balance['balance'])) {
        $balance = round_value($balance['balance']);
    } else {
        $balance = 0;
    }
    return $balance;
}

if (!function_exists('getDateDiffrence')) {
    function getDateDiffrence($start, $end)
    {

        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );
        foreach ($period as $key => $value) {
            $date[] = $value->format('Y-m-d');
        }
        $date[] = $end;
        return $date;
    }
}
/**
 * -------------------------------------
 * Admin all Cookes data   Function
 * -------------------------------------
 */
if (!function_exists('admin_cookie_data')) {
    function admin_cookie_data()
    {
        $crm_user = session()->get('admin_user');
        // pr(whitelabel['web_partner_id']);

        if (!empty ($crm_user)) {
            $crm_comapny_detail = json_decode(dev_decode(session()->get('admin_comapny_detail')), true);
            $crm_user_details_data = json_decode(dev_decode(session()->get('admin_user_details')), true);

            if (empty ($crm_comapny_detail) || empty ($crm_user_details_data)) {

                $db = \Config\Database::connect();
                $query = $db->query('SELECT * FROM web_partner WHERE id=' . whitelabel['web_partner_id'] . ' LIMIT 1');
                $comapny_details_arr = $query->getRowArray();

                $query = $db->query('SELECT * FROM whitelabel_webpartner_setting WHERE web_partner_id=' . whitelabel['web_partner_id'] . ' LIMIT 1');
                $whitelabel_webpartner_setting_arr = $query->getRowArray();

                $query_user = $db->query('SELECT * FROM admin_users WHERE id=' . $crm_user['id'] . ' LIMIT 1');
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
                        if ($img->move(FCPATH . "../uploads/$upload_folder/", $newNameRandom)) {
                            /*---------generate thumbnail-----*/
                            $path = FCPATH . "../uploads/$upload_folder/" . $newNameRandom;
                            $thumbpath = FCPATH . "../uploads/$upload_folder/thumbnail/" . $newNameRandom;
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

                    if ($file->move(FCPATH . "../uploads/$upload_folder/", $newName)) {

                        /*---------generate thumbnail-----*/
                        $path = FCPATH . "../uploads/$upload_folder/" . $newName;
                        $thumbpath = FCPATH . "../uploads/$upload_folder/thumbnail/" . $newName;
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
        $includes = ['Meal' => 'Meal', 'Transfer' => 'Transfer', 'Hotel' => 'Hotel', 'Flight' => 'Flight', 'Activities' => 'Activities', 'Cruise' => 'Cruise', 'Tourguide' => 'Tourguide', 'Bus' => 'Bus', 'Sightseeing' => 'Sightseeing'];

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
        } else if ($service == "Hotel") {
            if ($isDomestic) {
                $prefix = "DOM-H";
            } else {
                $prefix = "INT-H";
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


if (!function_exists('create_date_multiple')) {
    function create_date_multiple()
    {
        date_default_timezone_set("UTC");
        return strtotime(date("Y-m-d G:i:s"));
    }
}

function get_milliseconds()
{
    $timestamp = microtime(true);
    return (int) (($timestamp - (int) $timestamp) * 1000);
}



if (!function_exists('image_upload_multiple')) {
    function image_upload_multiple($file, $field_name, $upload_folder, $resizeDim): array
    {
        $validation = \Config\Services::validation();
        $request_data = service('request');
        $status_code = 1;
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
                        $newNameRandom = create_date_multiple() . get_milliseconds() . '_' . $img->getName();
                        if ($img->move(FCPATH . "../uploads/$upload_folder/", $newNameRandom)) {
                            /*---------generate thumbnail-----*/
                            if ($img->getMimeType() != 'application/pdf') {
                                $path = "../uploads/$upload_folder/" . $newNameRandom;
                                $thumbpath = "../uploads/$upload_folder/thumbnail/" . $newNameRandom;
                                $image = service('image');
                                $image->withFile($path)
                                    ->resize($resizeDim['width'], $resizeDim['height'], true, 'auto')
                                    ->save($thumbpath);
                            }

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
                    $newName = create_date_multiple() . get_milliseconds() . '_' . $file->getName();
                    if ($file->move(FCPATH . "../uploads/$upload_folder/", $newName)) {

                        /*---------generate thumbnail-----*/
                        if ($file->getClientmimeType() != 'application/pdf') {
                            $path = "../uploads/$upload_folder/" . $newName;
                            $thumbpath = "../uploads/$upload_folder/thumbnail/" . $newName;
                            $image = service('image');
                            $image->withFile($path)
                                ->resize($resizeDim['width'], $resizeDim['height'], true, 'auto')
                                ->save($thumbpath);
                        }
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

if (!function_exists('time_picker')) {
    function time_picker()
    {
        $start = new DateTime(date(DATE_ATOM, strtotime('12:00am')));
        $end = new DateTime(date(DATE_ATOM, strtotime('11:59pm')));
        $interval = new DateInterval('PT1M');
        $start->sub($interval);

        $time_slots = [];
        while ($start->add($interval) <= $end) {
            $time_slots[$start->format('H:i')] = $start->format('H:i');
        }

        return $time_slots;
    }
}

/**
 *  
 * Edit Permission Checker for Flight
 * 
 */
if (!function_exists('edit_permission_status')) {
    function edit_permission_status($whitelabel_status, $inventory_source, $api_source)
    {
        $permission = false;
        $supplier = "TTS";
        switch ($whitelabel_status) {
            case "active":
                $permission = true;
                $supplier = $api_source;
                break;
            case "inactive":
                if ($inventory_source == "super_admin") {
                    $permission = false;
                    $supplier = "TTS";
                } else if ($inventory_source == "web_partner") {
                    $permission = true;
                    $supplier = $api_source;
                }
                break;
            default:
                $permission = false;
                $supplier = "TTS";
        }

        return ['permission' => $permission, 'supplier' => $supplier];
    }
}


//created by abhay start

if (!function_exists('User_Query_date')) {
    function User_Query_date($date_string)
    {
        $date = date('Y-m-d G:i', strtotime($date_string));
        return strtotime($date);
    }
}

//created by abhay End
