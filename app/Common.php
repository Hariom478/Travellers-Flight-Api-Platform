<?php

use CodeIgniter\I18n\Time;
use App\Models\CommonModel;

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
function gettingCountryCodeWithCountryName()
{
    $CommonModel = new CommonModel();
    return $CountryCodeWithCountryName = $CommonModel->gettingCountryCodeWithCountryName();
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
 * -------------------------------------
 * strtotime To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('datetime_utc_to_ist')) {
    function datetime_utc_to_ist($datetime, $format = null)
    {
        if ($format) {
            $newformat = $format;
        } else {
            $newformat = 'Y-m-d';
        }

        $date = new DateTime($datetime, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $date->format($newformat);
    }
}

/**
 * -------------------------------------
 *Booking Date To Custom Date Format
 * -------------------------------------
 */
if (!function_exists('display_custom_date_format')) {
    function display_custom_date_format($date, $time = null)
    {
        $newformat = 'd M Y';
        $strtotime = strtotime($date);
        if ($time) {
            $newformat = 'd M Y H:i:s';
            $date = str_replace("T", " ", $date);
            $strtotime = strtotime($date);
        }

        $date_obj = Time::createFromTimestamp($strtotime, 'Asia/Kolkata');
        $dateTime = $date_obj->format('Y-m-d\TH:i:s');
        return datetime_utc_to_ist($dateTime, $newformat);
    }
}

/**
 * -------------------------------------
 * Get Common String Format
 * -------------------------------------
 */
if (!function_exists('get_uc_text_format')) {
    function get_uc_text_format($text)
    {
        return ucfirst(strtolower($text));
    }
}
/**
 * -------------------------------------
 * Get change_money_format
 * -------------------------------------
 */
if (!function_exists('change_money_format')) {

    function change_money_format($number)
    {
        return number_format($number);
    }
}

/**
 * -------------------------------------
 * Whitelabel domain name
 * -------------------------------------
 */

if (!function_exists('getWLurl')) {

    function getWLurl($input_d)
    {
        $input_d = trim($input_d, '/');
        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input_d)) {
            $input_d = 'http://' . $input_d;
        }
        $urlParts = parse_url($input_d);
        // remove www
        $domain = preg_replace('/^www\./', '', $urlParts['host']);
        return $domain;
    }
}
/**
 * -------------------------------------
 * Check Taxable or Non Taxable Invoice
 * -------------------------------------
 */
if (!function_exists('checkTaxableNonTaxableINV')) {
    function checkTaxableNonTaxableINV($InvoiceAmountData, $GstNumber, $service, $INVTYPE)
    {
        $AgentpartnerGSTInfo = (isset($GstNumber)) ? $GstNumber : '';
        $taxableInvoce = 0;
        $taxableValue = 0;
        if ($INVTYPE == 'INV') {
            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'hotel') {
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'bus') {
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'visa') {
                foreach ($InvoiceAmountData as $InvoiceAmount) {
                    $taxableValue = $taxableValue + $InvoiceAmount['GST']['TaxableAmount'];
                }
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $taxableValue + $InvoiceAmountData['GST']['TaxableAmount'];
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            }
        } else if ($INVTYPE == 'RFND') {

            if ($service == 'flight') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'visa') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            } else if ($service == 'cruise') {
                $taxableValue = $InvoiceAmountData;
                if ($taxableValue != 0 && ($AgentpartnerGSTInfo != '')) {
                    if ($AgentpartnerGSTInfo != null) {
                        $taxableInvoce = 1;
                    }
                }
            }
        }
        return $taxableInvoce;
    }
}
/**
 * -------------------------------------
 * get Taxable or Non Taxable Invoice Suffix
 * -------------------------------------
 */
if (!function_exists('getTaxableNonTaxableINVSuffix')) {
    function getTaxableNonTaxableINVSuffix($INVType, $TaxableINV, $service)
    {
        $InvoceSuffix = array("TaxablePrfix" => '', "NONTaxablePrfix" => '');

        if ($INVType == 'INV') {
            switch ($service) {
                case "flight":
                    $InvoceSuffix = array("TaxablePrfix" => 'FG', "NONTaxablePrfix" => 'FW');
                    break;
                case "hotel":
                    $InvoceSuffix = array("TaxablePrfix" => 'HG', "NONTaxablePrfix" => 'HW');
                    break;
                case "holiday":
                    $InvoceSuffix = array("TaxablePrfix" => 'PG', "NONTaxablePrfix" => 'PW');
                    break;
                case "bus":
                    $InvoceSuffix = array("TaxablePrfix" => 'TG', "NONTaxablePrfix" => 'TW');
                    break;
                case "car":
                    $InvoceSuffix = array("TaxablePrfix" => 'TG', "NONTaxablePrfix" => 'TW');
                    break;
                case "cruise":
                    $InvoceSuffix = array("TaxablePrfix" => 'CG', "NONTaxablePrfix" => 'CW');
                    break;
                case "visa":
                    $InvoceSuffix = array("TaxablePrfix" => 'VG', "NONTaxablePrfix" => 'VW');
                    break;
            }
        } elseif ($INVType == 'RFND') {
            switch ($service) {
                case "flight":
                    $InvoceSuffix = array("TaxablePrfix" => 'GG', "NONTaxablePrfix" => 'GW');
                    break;
                case "hotel":
                    $InvoceSuffix = array("TaxablePrfix" => 'IG', "NONTaxablePrfix" => 'IW');
                    break;
                case "holiday":
                    $InvoceSuffix = array("TaxablePrfix" => 'QG', "NONTaxablePrfix" => 'QW');
                    break;
                case "bus":
                    $InvoceSuffix = array("TaxablePrfix" => 'UG', "NONTaxablePrfix" => 'UW');
                    break;
                case "car":
                    $InvoceSuffix = array("TaxablePrfix" => 'UG', "NONTaxablePrfix" => 'UW');
                    break;
                case "cruise":
                    $InvoceSuffix = array("TaxablePrfix" => 'DG', "NONTaxablePrfix" => 'DW');
                    break;
                case "visa":
                    $InvoceSuffix = array("TaxablePrfix" => 'WG', "NONTaxablePrfix" => 'WW');
                    break;
            }
        }
        return $InvoceSuffix;
    }
}

/**
 * -------------------------------------
 * Generate Invoice Number
 * -------------------------------------
 */

if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($InvoiceData)
    {
        $digitPrefix = '00000000';
        $counterLength = "-" . strlen($InvoiceData['couter']);
        $digitPrifix = substr($digitPrefix, 0, $counterLength);
        $updateData = array();
        $InvoiceNumber = $InvoiceData['prefix'] . $InvoiceData['financial_year'] . $digitPrifix . $InvoiceData['couter'];
        if ($InvoiceData['IsTaxableInvoice'] == 1) {
            $updateData['taxable_couter'] = $InvoiceData['couter'] + 1;
        } else {
            $updateData['nontaxable_couter'] = $InvoiceData['couter'] + 1;
        }
        return array("updateData" => $updateData, "InvoiceNumber" => $InvoiceNumber);
    }
}


/**
 * ----------------------------------------------
 * Color Class and use view in this function created by abhay
 * ----------------------------------------------
 */

if (!function_exists('getStatusClass')) {

    function getStatusClass($status)
    {
        switch ($status) {
            case 'Confirmed':
                return 'successful-status';
            case 'Cancelled':
                return 'cancelled-status';
            case 'Failed':
                return 'failed-status';
            case 'Successful':
                return 'successful-status';
            case 'approved':
                return 'successful-status';
            case 'requested':
                return 'requested-status';
            case 'rejected':
                return 'rejected-status';
            case 'Open':
                return 'successful-status';
            case 'Close':
                return 'cancelled-status';
            case 'Processing':
                return 'processing-status';
            default:
                return 'processing-status';
        }
    }
}




if (!function_exists('get_active_whitelable_service')) {
    function get_active_whitelable_service()
    {


        $active_service_type = array();
        /* if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") { */
        $whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
        if (isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") {
            array_push($active_service_type, "Flight");
        }
        if (isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") {
            array_push($active_service_type, "Hotel");
        }
        if (isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") {
            array_push($active_service_type, "Bus");
        }
        if (isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") {
            array_push($active_service_type, "Holiday");
        }
        if (isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") {
            array_push($active_service_type, "Car");
        }
        if (isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") {
            array_push($active_service_type, "Visa");
        }
        if (isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") {
            array_push($active_service_type, "Cruise");
        }
        if (isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") {
            array_push($active_service_type, "Activities");
        }
        if (isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") {
            array_push($active_service_type, "TourGuide");
        }
        /*  } */
        return $active_service_type;
    }
}


/**
 * -------------------------------------
 * Meta Information data Function Created. by Abhay
 * -------------------------------------
 */

if (!function_exists('static_meta_information')) {
    function static_meta_information($service, $key)
    {
        $meta_information = array(
            'Flight' => array(
                "Index" => array(
                    'title' => "",
                    'keyword' => "",
                    'description' => "",
                    'robots' => "",
                ),
                "Result" => array(
                    'title' => "Flight, Cheap Air Tickets, Hotels, Holiday, Trains Package Booking " . web_partner_details['company_name'] . ".",
                    'keyword' => "flight, flights, flight booking,flight tickets,Flight reservation,International air travel,Budget flights,Last-minute flight deals,Business class flights,Economy class tickets,One-way flights,Round-trip bookings,Flight promotions,Online flight check-in,Red-eye flights,Flight status updates,Direct flights,Connecting flights,Flight search engine,Flight booking app,Best airfare rates,Family vacation flights,Group flight discounts,Student airfares " . web_partner_details['company_name'] . ".",
                    'description' => "Flight booking, cheap air tickets of domestic & international airlines with " . web_partner_details['company_name'] . " in India. Get the best travel deals for hotels, holidays, trains, and air tickets. Enjoy deals on domestic flights booking around the world. Book air tickets online to your favorite destination in India.",
                    'robots' => "INDEX, FOLLOW",
                ),
                "Details" => array(
                    'title' => "Flight, Cheap Air Tickets, Hotels, Holiday, Trains Package Booking " . web_partner_details['company_name'] . ".",
                    'keyword' => "flight, flights, flight booking,flight tickets,Flight reservation,International air travel,Budget flights,Last-minute flight deals,Business class flights,Economy class tickets,One-way flights,Round-trip bookings,Flight promotions,Online flight check-in,Red-eye flights,Flight status updates,Direct flights,Connecting flights,Flight search engine,Flight booking app,Best airfare rates,Family vacation flights,Group flight discounts,Student airfares " . web_partner_details['company_name'] . ".",
                    'description' => "Flight booking, cheap air tickets of domestic & international airlines with " . web_partner_details['company_name'] . " in India. Get the best travel deals for hotels, holidays, trains, and air tickets. Enjoy deals on domestic flights booking around the world. Book air tickets online to your favorite destination in India.",
                    'robots' => "INDEX, FOLLOW",
                ),
            ),
            'Holiday' => array(
                "Index" => array(
                    'title' => 'Holiday',
                    'keyword' => 'Holiday Keywords',
                    'description' => 'Holiday Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),


            'Contactus' => array(
                "Index" => array(
                    'title' => 'Contactus',
                    'keyword' => 'Contactus Keywords',
                    'description' => 'Contactus Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
            'Blog' => array(
                "Index" => array(
                    'title' => 'Blog',
                    'keyword' => 'Blog Keywords',
                    'description' => 'Blog Description',
                    'robots' => "INDEX, FOLLOW",
                )
            ),
        );


        if (isset($meta_information[$service][$key])) {
            return $meta_information[$service][$key];
        } else {
            return '';
        }

    }

}
