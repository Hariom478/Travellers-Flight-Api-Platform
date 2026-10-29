<?php

namespace Modules\Setting\Config;

class Validation
{
    public $companysetting = [
        'company_name' => [
            'label' => 'Company Name',
            'rules' => 'required|trim',
            'errors' => [
                'required' => 'Please enter company name',
            ],
        ],
        'pan_name' => [
            'label' => 'Pan Name',
            'rules' => 'required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter Pan Name',
            ],
        ],
        'pan_number' => [
            'label' => 'Pan Number',
            'rules' => 'trim|permit_empty|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]',
            'errors' => [
                'required' => 'Please enter Pan Number',
            ],
        ],
        'company_gst_no' => [
            'label' => 'GST Number',
            'rules' => 'trim|permit_empty|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
            'errors' => [
                'required' => 'Please enter GST Number',
            ],
        ],
        'address' => [
            'label' => 'Address',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter street / address',
            ],
        ],
        // 'whatsapp_no' => [
        //     'label' => 'Whatsapp Number',
        //     'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        //     'errors' => [
        //         'required' => 'Please enter Whatsapp number',
        //     ],
        // ],
        'country' => [
            'label' => 'Country',
            'rules' => 'required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please select country',
            ],
        ],
        'state' => [
            'label' => 'State',
            'rules' => 'required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter state',
            ],
        ],
        'city' => [
            'label' => 'City',
            'rules' => 'required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter city',
            ],
        ],
        'pincode' => [
            'label' => 'Zip Code',
            'rules' => 'required|numeric|min_length[4]|max_length[6]',
            'errors' => [
                'required' => 'Please enter zip code',
            ],
        ],
        'email_setting.mailer' => [
            'label' => 'Mailer',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select mailer',
            ],
        ],
    ];

    public $adduser = [
        'first_name' => [
            'label' => 'First Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter first name',
            ],
        ],
        'last_name' => [
            'label' => 'Last Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter last name',
            ],
        ],
        'login_email' => [
            'label' => 'Email ID',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Please enter email id',
                'valid_email' => 'Please enter a valid email id.',
                'is_unique' => 'Email ID already exists.Please choose a different email id.'
            ],
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'Please enter password',
                'min_length' => 'Password must be at least 8 digits'
            ],
        ],
        'mobile_isd' => [
            'label' => 'Dial Code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select dial code',
            ],
        ],
        'mobile_no' => [
            'label' => 'Mobile Number',
            'rules' => 'required|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter mobile number',
            ],
        ],
        'whatsapp_no' => [
            'label' => 'Whatsapp Number',
            'rules' => 'permit_empty|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter Whatsapp number',
            ],
        ],
        'designation' => [
            'label' => 'Designation',
            'rules' => 'required|alpha_space',
            'errors' => [
                'required' => 'Please enter designation',
            ],
        ],
        'job_joining_date' => [
            'label' => 'Joining Date',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter joining date',
            ],
        ],
        'pin_code' => [
            'label' => 'Zip Code',
            'rules' => 'permit_empty|numeric|min_length[3]|max_length[8]',
            'errors' => [
                'required' => 'Please enter your Zip Code',
            ],
        ],
    ];





    public $update_user = [
        'first_name' => [
            'label' => 'First Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter first name',
            ],
        ],
        'last_name' => [
            'label' => 'Last Name',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter last name',
            ],
        ],
        'mobile_isd' => [
            'label' => 'Dial Code',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select dial code',
            ],
        ],
        'mobile_no' => [
            'label' => 'Mobile Number',
            'rules' => 'required|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter mobile number',
            ],
        ],
        'whatsapp_no' => [
            'label' => 'Whatsapp Number',
            'rules' => 'permit_empty|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter Whatsapp number',
            ],
        ],
        'designation' => [
            'label' => 'Designation',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter designation',
            ],
        ],
        'job_joining_date' => [
            'label' => 'Joining Date',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter joining date',
            ],
        ],
        'pin_code' => [
            'label' => 'Zip Code',
            'rules' => 'permit_empty|numeric|min_length[3]|max_length[8]',
            'errors' => [
                'required' => 'Please enter your Zip Code',
            ],
        ]
    ];


    public $change_password_user = [
        'password' => [
            'label' => 'New Password',
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'Please enter New password',
            ],
        ]
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
}