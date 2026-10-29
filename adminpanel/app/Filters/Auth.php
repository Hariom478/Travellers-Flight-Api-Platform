<?php
namespace App\Filters;
use App\Models\CommonModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class Auth implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        /*------Start Check Browser destroy session on payment getway -----*/
        if($request->getPath()=='payment/response' || $request->getPath()=='payment/makepaymentesponse')
        {
            if($request->getVar('orderNo'))
            {
                $orderNo = $request->getVar('orderNo');
                $CommonModel = new CommonModel();
                $payment_detail=$CommonModel->get_payment_detail($orderNo);
                if($payment_detail)
                {   
                    $this->session = \Config\Services::session();
                    $user=$CommonModel->get_user_detail($payment_detail['user_id'],$payment_detail['web_partner_id']);
                    $this->session->set('admin_user', $user);
                }
            }
        }
        /*------End Check Browser destroy session on payment getway -----*/
        if (!session()->get('admin_user')) {
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    
    }
}