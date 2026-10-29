<?php

namespace Modules\Setting\Config;

class Validation
{
    public $companysetting = [
        'address' => [
            'label' => 'Address',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter street / address',
            ],
        ],
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

        'support_no' => [
            'label' => 'support no',
            'rules' => 'trim',
        ],
        'support_email' => [
            'label' => 'support email',
            'rules' => 'trim',
        ],

    ];




    public $common_websett = [
        'value' => [
            'label' => 'value',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter value',
            ],
        ],
        'key_source' => [
            'label' => 'Key Source',
            'rules' => 'required',
            'errors' => [
                'required' => 'Please Select Source',
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
            'rules' => 'required|valid_email|is_unique[agent_users.login_email]',
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

        'mobile_no' => [
            'label' => 'Phone',
            'rules' => 'required|numeric|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter phone',
            ],
        ],
        'profile_pic' => [
            'label' => 'Blog Post Image',
            'rules' => 'uploaded[profile_pic]|max_size[profile_pic,1024]|mime_in[profile_pic,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],

        ],
        'pin_code' => [
            'label' => 'Pin Code',
            'rules' => 'permit_empty|numeric|min_length[3]|max_length[8]',
            'errors' => [
                'required' => 'Please enter your pin code',
            ],
        ]
    ];


    public $change_password_user = [
        'confirm_password' => [
            'label' => 'Confirm Password',
            'rules' => 'required|matches[change_password]',
            'errors' => [
                'required' => 'Please enter Confirm Password',
            ],
        ],
        'change_password' => [
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

/* 'profile_pic' => [
    'uploaded[profile_pic]',
    'mime_in[profile_pic,image/jpg,image/jpeg,image/png]',
    'max_size[profile_pic,1024]',
]*/