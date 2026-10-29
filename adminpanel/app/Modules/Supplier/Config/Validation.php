<?php
namespace Modules\Supplier\Config;

class Validation
{
    public $supplier_validation = [
        'company_name' => [
            'label' => 'Company Name',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter company name',
            ],
        ],
        'pan_card' => [
            'label' => 'PAN Card Scan Copy',
            'rules' => 'uploaded[pan_card]|max_size[pan_card,1024]|mime_in[pan_card,image/jpg,image/jpeg,image/png,application/pdf]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
        'pan_name' => [
            'label' => 'Pan Name',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter Pan Name',
            ],
        ],
        'pan_number' => [
            'label' => 'Pan Number',
            'rules' => 'trim|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]',
            'errors' => [
                'required' => 'Please enter Pan Number',
            ],
        ],
        'gst_number' => [
            'label' => 'GST Number',
            'rules' => 'trim|permit_empty|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
            'errors' => [
                'required' => 'Please enter GST Number',
            ],
        ],
        'title' => [
            'label' => 'title',
            'rules' => 'trim|required|max_length[10]',
            'errors' => [
                'required' => 'Please select title'
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
        
        'aadhaar_no' => [
            'label' => 'Aadhar number',
            'rules' => 'trim|numeric|required',
            'errors' => [
                'required' => 'Please enter Aadhar number',
                'regex_match' => 'invalid Aadhar number'

            ],
        ],


       

        'aadhar_scan_copy' => [
            'label' => 'Aadhar scan copy',
            'rules' => 'uploaded[aadhar_scan_copy]|max_size[aadhar_scan_copy,1024]|mime_in[aadhar_scan_copy,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png '
            ],
        ],
        'address' => [
            'label' => 'Address',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter street / address',
            ],
        ],
        'country' => [
            'label' => 'Country',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please select country',
            ],
        ],
        'state' => [
            'label' => 'State',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter state',
            ],
        ],
        'city' => [
            'label' => 'City',
            'rules' => 'trim|required|alpha_numeric_space',
            'errors' => [
                'required' => 'Please enter city',
            ],
        ],
        'pincode' => [
            'label' => 'Zip Code',
            'rules' => 'trim|required|numeric|min_length[4]|max_length[6]',
            'errors' => [
                'required' => 'Please enter zip code',
            ],
        ],
        'supplier_class_id' => [
            'label' => 'supplier class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier class'
            ],
        ],
        'login_email' => [
            'label' => 'Email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'is_unique' => 'Email already exists please use another email'
            ],
        ],
        'user_password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[8]',
            'errors' => [
                'required' => 'Please enter password'
            ],
        ],
        'user_first_name' => [
            'label' => 'first name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter first name'
            ],
        ],
        'user_last_name' => [
            'label' => 'last name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter last name'
            ],
        ],
        'user_mobile_no' => [
            'label' => 'mobile no',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter mobile no'
            ],
        ]
    ];

    public $password_change = [
        'password' => [
            'label' => 'password',
            'rules' => 'trim|required|min_length[8]|max_length[15]',
            'errors' => [
                'required' => 'Please enter password'
            ],
        ],
    ];

    public $status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];

    public $class_validation = [
        'class_name' => [
            'label' => 'supplier class',
            'rules' => 'trim|required|max_length[35]',
            'errors' => [
                'required' => 'Please enter supplier class'
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