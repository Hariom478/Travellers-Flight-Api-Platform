<?php

namespace App\Controllers;
use App\Models\CommonModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
class BaseController extends Controller
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
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        date_default_timezone_set("UTC");
        $this->session = \Config\Services::session();
        $this->validation =  \Config\Services::validation();

        ini_set("memory_limit","128M");
        ini_set('serialize_precision', -1);
        $date_format  = 'd M y';
        defined('DateFormat') || define('DateFormat', $date_format);
        $CommonModel = new CommonModel();

      /*   $_SERVER['HTTP_HOST'] = 'https://www.gdsapidev.liamtra.com/'; */

          // Local dev router maps 127.0.0.1 to a registered white-label domain
          $site_url = $_SERVER['TTS_LOCAL_TENANT_HOST'] ?? $_SERVER['HTTP_HOST'];

      

        $webpartner_url = getWLurl($site_url);
        $domain_name = ["https://www.".$webpartner_url, "https://".$webpartner_url,$webpartner_url, "http://".$webpartner_url];

        // $web_partner_id=1;
        $whitelabel_details = $CommonModel->webpartner_whitelabel_details_bydomain($domain_name);
       /*  $whitelabel_details   = array(); */

        defined('whitelabel') || define('whitelabel', $whitelabel_details);



        if ($whitelabel_details && $whitelabel_details['b2b_business'] =='active') {
            $webpartner_details = $CommonModel->webpartner_details($whitelabel_details['web_partner_id']);
            $get_staff_list = $CommonModel->get_staff_list($whitelabel_details['web_partner_id']);
            $webpartner_details['user_detail']=$get_staff_list;
            if ($webpartner_details['whitelabel_user'] == "active") {


                defined('web_partner_details') || define('web_partner_details', $webpartner_details);

 

                $api_webpartner_setting = $CommonModel->api_webpartner_setting($webpartner_details['id']);


                if (is_array($api_webpartner_setting) && !empty($api_webpartner_setting)) {
                    $auth_data = [
                        'Username' => $api_webpartner_setting['api_username'],
                        'Password' => $api_webpartner_setting['api_password'],
                        'Btype' => 'Web'
                    ];
                }else{
                    $auth_data = [
                        'Username' => null,
                        'Password' => null,
                        'Btype' => 'Web'
                    ];
                }
                defined('credential') || define('credential', $auth_data);

            } else {
                view('template/inactive_account');
            }

        } else {

                view('template/inactive_account');


        }

        $super_admin_website_setting = $CommonModel->super_admin_website_setting();
        $this->session->set('super_admin_website_setting', $super_admin_website_setting);
        defined('super_admin_website_setting') || define('super_admin_website_setting', $super_admin_website_setting);

        $tts_pages = $CommonModel->pages_list($whitelabel_details['web_partner_id']);
        defined('tts_pages') || define('tts_pages', $tts_pages);
        $this->SmsTemplate  =  array(
            "FlightBookingFailed"=>"1207166564333744985",
            "FlightBookingHold"=>"1207166564342280000",
            "FlightBookingConfirm"=>"1207161899226020379",

        );
    }
}
