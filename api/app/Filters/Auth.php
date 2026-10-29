<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\AuthModel;

Class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = \Config\Services::throttler();
        $response = \Config\Services::response();
        // Restrict an IP address to no more than 1 request per second across the entire site.
        if ($throttler->check(md5($request->getIPAddress()), 60, MINUTE) === false) {
          return api_default_message(429);
        }

        if ($request->hasHeader('Username') && $request->hasHeader('Password')) {
            $login_credential=array('Username'=>$request->getHeaderLine('Username'),'Password'=>$request->getHeaderLine('Password'));

            $validation =  \Config\Services::validation();
            $validation->setRules(['Username' => ['rules' => 'required|alpha_numeric|max_length[15]'],'Password' => ['rules' => 'required|max_length[20]']]);
            $rules=$validation->run($login_credential);
            if(!$rules) {
                $message=api_validation_message('Auth_error');
                return api_custom_message(401,$message);
            } else {
                /* Start Login  */
                $uri = $request->getUri();
                $service=$uri->getSegment(1);
                //$ip=$request->getServer('REMOTE_ADDR');
                $ip=trim($request->getServer('SERVER_ADDR'));
                $AuthModel=new AuthModel();
                $UserAuthdata=$AuthModel->login($login_credential,$service,$ip);
                if($UserAuthdata)
                {
                    $request->UserAuthdata=$UserAuthdata;
                } else {
                    $message=api_validation_message('Access_error');
                    return api_custom_message(401,$message);
                }                
            }

        } else {
            return api_custom_message(401,"Header Authenticate Information not Present");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }   
}