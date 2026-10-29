<?php

namespace Modules\Agent\Config;

class Validation
{
    public $agent_validation = [
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
        'last_name' => [
            'label' => 'first name',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter last name'
            ],
        ],

        'email_id' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                //'is_unique' => 'Email already exists please use another email'
            ],
        ],
        'mobile_number' => [
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


        'address' => [
            'label' => 'address',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter address'
            ],
        ],

        'country' => [
            'label' => 'country',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter country'
            ],
        ],

        'state' => [
            'label' => 'state',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter state'
            ],
        ],

        'city' => [
            'label' => 'city',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter city'
            ],
        ],

        'pincode' => [
            'label' => 'pin code',
            'rules' => 'trim|required|numeric|max_length[10]',
            'errors' => [
                'required' => 'Please enter pin code'
            ],
        ],


        'company_name' => [
            'label' => 'company name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter company name'
            ],
        ],


        'dob' => [
            'label' => 'dob',
            'rules' => 'trim|max_length[12]',
            'errors' => [
                'required' => 'Please enter dob'
            ],
        ],

        'gst_holder_name' => [
            'label' => 'gst name',
            'rules' => 'trim|permit_empty|max_length[45]',
            'errors' => [
                'required' => 'Please gst name'
            ],
        ],

        'gst_number' => [
            'label' => 'gst number',
            'rules' => 'trim|permit_empty|max_length[15]|regex_match[^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$]',
            'errors' => [
                'required' => 'Please gst number',
                'regex_match' => 'invalid gst number'
            ],
        ],

        'pan_holder_name' => [
            'label' => 'pan holder name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter pan holder name'
            ],
        ],

        'pan_number' => [
            'label' => 'pan number',
            'rules' => 'trim|alpha_numeric|required|regex_match[[A-Z]{5}[0-9]{4}[A-Z]{1}]',
            'errors' => [
                'required' => 'Please enter pan number',
                'regex_match' => 'invalid pan number'

            ],
        ],

        'aadhaar_no' => [
            'label' => 'Aadhar number',
            'rules' => 'trim|numeric|required',
            'errors' => [
                'required' => 'Please enter Aadhar number',
                'regex_match' => 'invalid Aadhar number'

            ],
        ],


        'pan_scan_copy' => [
            'label' => 'pan scan copy',
            'rules' => 'uploaded[pan_scan_copy]|max_size[pan_scan_copy,2048]|mime_in[pan_scan_copy,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 2MB',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ],
        ],

        'aadhar_scan_copy' => [
            'label' => 'Aadhar scan copy',
            'rules' => 'uploaded[aadhar_scan_copy]|max_size[aadhar_scan_copy,2048]|mime_in[aadhar_scan_copy,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 2MB',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ],
        ],


        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select slider status'
            ],
        ],

        'agent_class' => [
            'label' => 'agent class',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],

    ];


    public $virtual_creditlimt = [
        'credit_limit' => [
            'label' => 'credit Limit',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter credit limit'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],
        'credit_expire_date' => [
            'label' => 'credit expire date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select credit expire date'
            ],
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

    public $agent_class_validation = [
        'class_name' => [
            'label' => 'agent class',
            'rules' => 'trim|required|max_length[35]',
            'errors' => [
                'required' => 'Please enter agent class'
            ],
        ],
    ];

    public $agent_password_change = [
        'password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter password'
            ],
        ],
    ];

    public $virtual_credit = [
        'credit' => [
            'label' => 'credit amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter  credit amount'
            ],
        ],
        'action_type' => [
            'label' => 'action type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  action type'
            ],
        ],
        'booking_reference_number' => [
            'label' => 'booking reference number',
            'rules' => 'trim|required_with[service]',
            'errors' => [
                'required' => 'Please enter  booking reference number'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ]
    ];

    public $virtual_debit = [
        'debit' => [
            'label' => 'debit amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter debit amount'
            ],
        ],

        'action_type' => [
            'label' => 'action type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  action type'
            ],
        ],
        'booking_reference_number' => [
            'label' => 'booking reference number',
            'rules' => 'trim|required_with[service]',
            'errors' => [
                'required' => 'Please enter  booking reference number'
            ],
        ],

        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ]
    ];
    public $accountUpdateLogRemark = [


        'remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ]
    ];
}