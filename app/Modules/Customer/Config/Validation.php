<?php

namespace Modules\Customer\Config;

class Validation
{

 public $customer_validation =[
     'title' => [
         'label' => 'title',
         'rules' => 'trim|required|max_length[10]',
         'errors' => [
             'required' => 'Please select title'
         ],
     ],

     'first_name' => [
         'label' => 'first name',
         'rules' => 'trim|required|max_length[40]',
         'errors' => [
             'required' => 'Please enter first name'
         ],
     ],


     'email_id' => [
         'label' => 'Email',
         'rules' => 'trim|required|valid_email|is_unique[customer.email_id]',
         'errors' => [
             'required' => 'Please enter  email',
             'is_unique'=>'Email already exists please use another email'
         ],
     ],
     'mobile_no' => [
         'label' => 'mobile number',
         'rules' => 'trim|required|numeric|min_length[10]|max_length[18]',
         'errors' => [
             'numeric' => 'Please enter valid mobile number'
         ],
     ],

     'password' => [
         'label' => 'password',
         'rules' => 'trim|required|min_length[6]|max_length[15]',
         'errors' => [
             'required' => 'Please enter password'
         ],
     ],

     'status' => [
         'label' => 'status',
         'rules' => 'trim|required',
         'errors' => [
             'required' => 'Please select status'
         ],
     ],

     'address' => [
         'label' => 'address',
         'rules' => 'trim',
     ],
     'city' => [
         'label' => 'city',
         'rules' => 'trim',
     ],

     'state' => [
         'label' => 'state',
         'rules' => 'trim',
     ],
     'country' => [
         'label' => 'country',
         'rules' => 'trim',
     ],
     'pin_code' => [
         'label' => 'pin code',
         'rules' => 'trim',
     ],
 ];

    public $status = [

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];
    public $customer_password_change =[
        'password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[6]|max_length[15]',
            'errors' => [
                'required' => 'Please enter password'
            ],
        ],
    ];

    public $virtual_credit =[
        'credit' => [
            'label' => 'credit amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter credit amount'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],
        'payment_mode' => [
            'label' => 'payment mode',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select payment mode'
            ],
        ],
    ];

    public $virtual_debit =[
        'debit' => [
            'label' => 'debit amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter debit amount'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],
    ];


    public $traveller_validation =[
        'title' => [
            'label' => 'title',
            'rules' => 'trim|required|max_length[10]',
            'errors' => [
                'required' => 'Please select title'
            ],
        ],

        'first_name' => [
            'label' => 'first name',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter first name'
            ],
        ],


        'email_id' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|is_unique[customer.email_id]',
            'errors' => [
                'required' => 'Please enter  email',
                'is_unique'=>'Email already exists please use another email'
            ],
        ],
        'mobile_no' => [
            'label' => 'mobile number',
            'rules' => 'trim|required|numeric|min_length[10]|max_length[18]',
            'errors' => [
                'numeric' => 'Please enter valid mobile number'
            ],
        ],


        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select slider status'
            ],
        ],
        'marital_status' => [
            'label' => 'marital status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select marital status'
            ],
        ],

        'passport_number' => [
            'label' => 'passport number',
            'rules' => 'trim',
        ],

        'passport_expiry_date' => [
            'label' => 'passport expiry date',
            'rules' => 'trim',
        ],
        'passport_issue_date' => [
            'label' => 'passport issue date',
            'rules' => 'trim',
        ],
        'passport_issuing_country' => [
            'label' => 'passport issuing country',
            'rules' => 'trim',
        ],
    ];
}
