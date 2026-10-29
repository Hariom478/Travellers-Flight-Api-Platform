<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\CommonModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['Common', 'form', 'url', 'cookie'];
    protected $admin_user = [];
    protected $crm_comapny_detail = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

       
        date_default_timezone_set("UTC");
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();

        ini_set("memory_limit", "128M");
        ini_set('serialize_precision', -1);
        $date_format = 'd M y';
        $CommonModel = new CommonModel();
        defined('DateFormat') || define('DateFormat', $date_format);


        $fareTypes = $CommonModel->getApiFlighFareType();
        defined('ApiFlighFareType') || define('ApiFlighFareType', $fareTypes);

     /*    $_SERVER['HTTP_HOST'] = 'https://www.gdsapidev.liamtra.com/'; */

        // Local dev router maps 127.0.0.1 to a registered white-label domain
        $site_url = $_SERVER['TTS_LOCAL_TENANT_HOST'] ?? $_SERVER['HTTP_HOST'];
        $webpartner_url = getWLurl($site_url);
        $domain_name = ["https://www." . $webpartner_url, "https://" . $webpartner_url, $webpartner_url, "http://" . $webpartner_url, "http://www." . $webpartner_url, "http://" . $webpartner_url . "/"];
       /*  pr($domain_name);die; */
        $whitelabel_details = $CommonModel->webpartner_whitelabel_details_bydomain($domain_name);
      
      /*   pr($whitelabel_details);die; */
        defined('whitelabel') || define('whitelabel', $whitelabel_details);

        
         $whitelabel_web_partner_id =$whitelabel_details['web_partner_id'];        
        

        if (isset(session()->get('admin_user')['web_partner_id'])) {
            defined('DateFormat') || define('DateFormat', $date_format);
        }
        $super_admin_website_setting = $CommonModel->admin_website_setting($whitelabel_web_partner_id);
  
        defined('DepositeBalanceLimitAlert') || define('DepositeBalanceLimitAlert', 500000);
        $this->session->set('super_admin_website_setting', $super_admin_website_setting);
        defined('super_admin_website_setting') || define('super_admin_website_setting', $super_admin_website_setting);
        $this->SmsTemplate = array(
            "FlightBookingFailed" => "",
            "FlightBookingHold" => "",
            "FlightBookingConfirm" => "",
        );

        
    }
}
