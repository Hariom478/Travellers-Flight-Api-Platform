<?php
namespace Modules\Newsletter\Config;

class Validation
{
    public $search_validation = [
        'key' => [
            'label' => 'Key',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select key'
            ],
        ],
        'value' => [
            'label' => 'Value',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter value',
            ],
        ],
        'from_date' => [
            'label' => 'From Date',
            'rules' => 'trim',
            'errors' => [],
        ],
        'to_date' => [
            'label' => 'To Date',
            'rules' => 'trim',
            'errors' => [],
        ],
    ];


    public $newsletter_validation = [
        'name' => [
            'label' => 'Name',
            'rules' => 'trim',
            'errors' => [],
        ],

        'email' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[newsletter.email]',
            'errors' => [
                'required' => 'Please enter your email address',
                'is_unique'=>'Your email id already exists please use another email'
            ],
        ],
    ];
	

}
