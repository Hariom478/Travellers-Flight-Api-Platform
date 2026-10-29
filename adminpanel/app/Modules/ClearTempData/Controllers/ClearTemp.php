<?php

namespace Modules\ClearTempData\Controllers;

use App\Controllers\BaseController;
use Modules\ClearTempData\Config\Validation;
use App\Modules\ClearTempData\Models\ClearTempModel;

use DateTime;


class ClearTemp extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Clear Temp Data";
        helper('filesystem');
    }

    public function index()
    {
        $logpath=FCPATH.'writable/logs/*.log';
        $debugbarpath=FCPATH.'writable/debugbar/*.json';
        $sessionpath=FCPATH.'writable/session/*';

        $ok = @array_map('unlink', glob($logpath));
        $ok = @array_map('unlink', glob($debugbarpath));
        $ok = @array_map('unlink', glob($sessionpath));
    }
}
