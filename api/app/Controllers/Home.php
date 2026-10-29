<?php

namespace App\Controllers;

class Home extends BaseController
{   
    public function index()
    {
        $data['company_name']="Travel Technology Solution";
        return view('home_page',$data);
    }
}
