<?php

namespace Modules\Pages\Controllers;

use App\Modules\Pages\Models\PagesModel;
use App\Controllers\BaseController;
use Modules\Pages\Config\Validation;


class Pages extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Pages";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];

    }


    public function pages()
    {
        $slug = $this->request->uri->getSegment(3);
        $PagesModel = new PagesModel();
        $details = $PagesModel->get_page_details($slug, $this->web_partner_id);

        $data = [
            'title' => $this->title,
            'details' => $details,
            'view' => 'Pages\Views\pages-details'
        ];
        return view('template/default-layout', $data);

    }

    public function contact_us()
    {
        $data = [
            'title' => 'Contactus',
            'view' => 'Pages\Views\contact_us'
        ];
        return view('template/default-layout', $data);
    }

}
