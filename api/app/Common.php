<?php
/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

use CodeIgniter\I18n\Time;

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
 * --------------------------------------------------------
 *  GST Calaculate
 *  $service - Flight,Hotel,Bus etc
 *  $user_state_code - Auth User State code (nature of supply )
 *  $admin_state_code - Super admin State code  (nature of supply )
 *  $amount - Apply on GST
 * --------------------------------------------------------
 */
if (!function_exists('gst_calculate_holiday')) {
    function gst_calculate_holiday($service, $user_state_code, $admin_state_code, $amount)
    {
        $value = 0;
        $gst = 5;

        $value = ($amount * $gst) / 100;

        $CGSTAmount = 0;
        $CGSTRate = 0;
        $IGSTAmount = 0;
        $IGSTRate = 0;
        $SGSTAmount = 0;
        $SGSTRate = 0;
        if ($user_state_code) {
            if ($admin_state_code == $user_state_code) {
                $CGSTRate = round_value($gst / 2);
                $SGSTRate = round_value($gst / 2);
                $CGSTAmount = round_value($value / 2);
                $SGSTAmount = round_value($value / 2);
            } else {
                $IGSTRate = $gst;
                $IGSTAmount = $value;
            }
        } else {
            $IGSTRate = $gst;
            $IGSTAmount = $value;
        }
        return array(
            'CGSTAmount' => $CGSTAmount,
            'CGSTRate' => $CGSTRate,
            'IGSTAmount' => $IGSTAmount,
            'IGSTRate' => $IGSTRate,
            'SGSTAmount' => $SGSTAmount,
            'SGSTRate' => $SGSTRate,
            'TaxableAmount' => $amount,
            'TotalGSTAmount' => $value
        );
    }
}

/**
 * -------------------------------------
 * Validate Json Format Request
 * -------------------------------------
 */
if (!function_exists('json_validate')) {
    function json_validate($string)
    {
        $result = json_decode($string, true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = '';
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            api_custom_message(400, $error, false);
        }
        return $result;
    }
}

/**
 * -------------------------------------
 * Generate Random Token ID Use for Search
 * -------------------------------------
 */
if (!function_exists('generate_token')) {
    function generate_token()
    {
        date_default_timezone_set("UTC");
        $t = microtime(true);
        $micro = sprintf("%03d", ($t - floor($t)) * 1000);
        $date = new DateTime(date('Y-m-d H:i:s.' . $micro));
        $timestamp = $date->format("Y-m-d\TH:i:s:") . $micro . 'Z';
        $string_value = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 8)), 8, 8);
        $tokenid = sha1(base64_encode(sha1(base64_decode($string_value) . $timestamp, true)));
        return $tokenid;
    }
}

/**
 * -------------------------------------
 * api default message return
 * -------------------------------------
 */
if (!function_exists('api_default_message')) {
    function api_default_message(int $code)
    {

        $default_message_error = [
            '400' => 'Bad Request',
            '401' => 'Not Authentication',
            '402' => 'Payment Required',
            '403' => 'You Request is Forbidden',
            '404' => 'URL Not Found',
            '405' => 'Method Not Allowed by System',
            '406' => 'You Request Not Acceptable',
            '408' => 'Request Timeout',
            '409' => 'Request Conflict',
            '410' => 'Request Gone',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '416' => 'Requested Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '428' => 'Precondition Required',
            '429' => 'Too Many Requests',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Unavailable',
            '504' => 'Gateway Timeout',
            '505' => 'HTTP Version not Supporterd',
            '511' => 'Network Authentication Required'
        ];

        if (array_key_exists($code, $default_message_error)) {
            $message = $default_message_error[$code];
        } else {
            $message = "error not defined";
        }
        $output = array();
        $output['Error']['ErrorCode'] = $code;
        $output['Error']['ErrorMessage'] = $message;
        $response = \Config\Services::response();
        return $response->setStatusCode($code)->setJSON($output);
    }
}

/**
 * -------------------------------------
 * API custome message return
 * -------------------------------------
 */
if (!function_exists('api_custom_message')) {
    function api_custom_message(int $code, $message = null, $return = true)
    {
        $outpout = array();
        $outpout['Error']['ErrorCode'] = $code;
        $outpout['Error']['ErrorMessage'] = $message;
        if ($return === true) {
            $response = \Config\Services::response();
            return $response->setStatusCode($code)->setJSON($outpout);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($outpout);
            die();
        }

    }
}

/**
 * -------------------------------------
 * API Validation Error Message Return
 * -------------------------------------
 */
if (!function_exists('api_validation_message')) {
    function api_validation_message(string $key)
    {
        $default_message_error = [
            'Auth_error' => 'Required header parameter is missing  or invalid',
            'Access_error' => 'You are not authorized to access this API',
            'param_error' => 'Bad Request - Your request is missing parameters or incorrect value.',
            'supplier_error' => 'We are getting error from supplier end.',
            'supplier_inactive_error' => 'You have no supplier activated in your account.',
            'invalid_token_error' => 'Invalid search token.',
            'not_lcc_flight' => 'Do direct ticket  for lcc flight.',
            'expire_token_error' => 'Your search token id (SearchTokenId) is expired.'
        ];

        if (array_key_exists($key, $default_message_error)) {
            $message = $default_message_error[$key];
        } else {
            $message = "Bad Request";
        }
        return $message;
    }
}

/**
 * --------------------------------------------------------
 * Validation Error Message list of Array to String Message
 * --------------------------------------------------------
 */
if (!function_exists('validation_string_message')) {
    function validation_string_message(array $array)
    {
        $message = '';
        if ($array) {
            foreach ($array as $list) {
                $message .= $list;
            }
        }
        return $message;
    }
}


/**
 * --------------------------------------------------------
 *  Value in decimal places
 * --------------------------------------------------------
 */
if (!function_exists('round_value')) {
    function round_value($value, $places = 2)
    {
        return round($value, $places);
    }
}

/**
 * --------------------------------------------------------
 *  TDS Calaculate
 * --------------------------------------------------------
 */
if (!function_exists('tds_calculate')) {
    function tds_calculate($value)
    {
        $tdsvalue = 0;
        $tds_apply = 5;  // TDS Value
        if ($value) {
            $tdsvalue = ($value * $tds_apply) / 100;
            $tdsvalue = round_value($tdsvalue);
        }
        return $tdsvalue;
    }
}

/**
 * --------------------------------------------------------
 *  GST Calaculate
 *  $service - Flight,Hotel,Bus etc
 *  $user_state_code - Auth User State code (nature of supply )
 *  $admin_state_code - Super admin State code  (nature of supply )
 *  $amount - Apply on GST
 * --------------------------------------------------------
 */
if (!function_exists('gst_calculate')) {
    function gst_calculate($service, $user_state_code, $admin_state_code, $amount)
    {
        $value = 0;
        $gst = 18;

        $value = ($amount * $gst) / 100;

        $CGSTAmount = 0;
        $CGSTRate = 0;
        $IGSTAmount = 0;
        $IGSTRate = 0;
        $SGSTAmount = 0;
        $SGSTRate = 0;
        if ($user_state_code) {
            if ($admin_state_code == $user_state_code) {
                $CGSTRate = round_value($gst / 2);
                $SGSTRate = round_value($gst / 2);
                $CGSTAmount = round_value($value / 2);
                $SGSTAmount = round_value($value / 2);
            } else {
                $IGSTRate = $gst;
                $IGSTAmount = $value;
            }
        } else {
            $IGSTRate = $gst;
            $IGSTAmount = $value;
        }
        return array(
            'CGSTAmount' => $CGSTAmount,
            'CGSTRate' => $CGSTRate,
            'IGSTAmount' => $IGSTAmount,
            'IGSTRate' => $IGSTRate,
            'SGSTAmount' => $SGSTAmount,
            'SGSTRate' => $SGSTRate,
            'TaxableAmount' => $amount,
            'TotalGSTAmount' => $value
        );
    }
}




/**
 * --------------------------------------------------------
 *  Json/Array Tag and Value Compair
 * --------------------------------------------------------
 */
if (!function_exists('tag_compair_key_value')) {
    function tag_compair_key_value(array $item1, array $item2)
    {

    }
}

/**
 * --------------------------------------------------------
 *  Check and Verify Web Partner Balance
 * --------------------------------------------------------
 */
if (!function_exists('check_web_partner_balance')) {
    function check_web_partner_balance($web_partner_balance, $invoice_amount)
    {
        if ($web_partner_balance) {
            if ($invoice_amount <= $web_partner_balance['balance']) {
                return true;
            } else {
                $message = 'Agency do not have insufficient balance';
                api_custom_message(105, $message, false);
            }
        } else {
            $message = 'Agency do not have insufficient balance';
            api_custom_message(105, $message, false);
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
        return strtotime(Time::now());
    }
}

/**
 * -------------------------------------
 * strtotime To API Date Format with Time
 * -------------------------------------
 */

if (!function_exists('api_date_format_with_time')) {
    function api_date_format_with_time($strtotime)
    {
        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        return $date_obj->format('Y-m-d\TH:i:s');
    }
}

/**
 * -------------------------------------
 * strtotime To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('custom_date_format')) {
    function custom_date_format($strtotime)
    {
        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        return $date_obj->format('d M Y');
    }
}

/**
 * -------------------------------------
 * strtotime To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('datetime_utc_to_ist')) {
    function datetime_utc_to_ist($datetime,$format=null)
    {
        if($format)
        {
            $newformat=$format;
        }else {
            $newformat='Y-m-d\TH:i:s';
        }
    
        $date = new DateTime($datetime, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $date->format($newformat);
    }
}


/**
 * -------------------------------------
 * Reference Number
 * -------------------------------------
 */


if (!function_exists('reference_number')) {
    function reference_number($bookingid,$service=null,$isDomestic=null,$action_type=null)
    {
        $prefix =  "TW";
        if($service=="Flight")
        {
          if($isDomestic)
          {
            $prefix =  "DOM";
          }
          else{
            $prefix =  "INT";
          }
        }
        $financial_year = get_financial_year();
        return $prefix.'/' . $financial_year . '/' . $bookingid;
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


/**
 * -------------------------------------
 *  check set value
 * -------------------------------------
 */
if (!function_exists('check_isset')) {
    function check_isset($value, $field) {

        if (isset($value[$field])) {
            $finalvalue = $value[$field];
        } else {
            $finalvalue = NULL;
        }
        return $finalvalue;
    }
}


if (!function_exists('folderToZip')) {
 function folderToZip($folder, &$zipFile, $exclusiveLength) {
    $handle = opendir($folder);
    while (false !== $f = readdir($handle)) {
      if ($f != '.' && $f != '..') {
        $filePath = "$folder/$f";
        // Remove prefix from file path before add to zip.
        $localPath = substr($filePath, $exclusiveLength);
        if (is_file($filePath)) {
          $zipFile->addFile($filePath, $localPath);
        } elseif (is_dir($filePath)) {
          // Add sub-directory.
          $zipFile->addEmptyDir($localPath);
          folderToZip($filePath, $zipFile, $exclusiveLength);
        }
      }
    }
    closedir($handle);
  }
}
if (!function_exists('zipDir')) {
  function zipDir($sourcePath, $outZipPath)
  {
    $pathInfo = pathInfo($sourcePath);
    $parentPath = $pathInfo['dirname'];
    $dirName = $pathInfo['basename'];

    $z = new ZipArchive();
    $z->open($outZipPath, ZIPARCHIVE::CREATE);
    $z->addEmptyDir($dirName);
    folderToZip($sourcePath, $z, strlen("$parentPath/"));
    $z->close();
  }
}