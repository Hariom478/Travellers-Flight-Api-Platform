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
            'rules' => 'trim|required|valid_email|is_unique[agent.email_id]',
            'errors' => [
                'required' => 'Please enter  email',
                'is_unique'=>'Email already exists please use another email'
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
                'regex_match'=>'invalid gst number'
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
                'regex_match'=>'invalid pan number'

            ],
        ],


        'pan_scan_copy' => [
            'label' => 'pan scan copy',
            'rules' => 'uploaded[pan_scan_copy]|max_size[pan_scan_copy,1024]|mime_in[pan_scan_copy,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png '
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


    public $status = [

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];

    public $agent_class_validation =[
        'class_name' => [
            'label' => 'agent class',
            'rules' => 'trim|required|max_length[35]',
            'errors' => [
                'required' => 'Please enter agent class'
            ],
        ],
    ];

    public $agent_password_change =[
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



    public $flight_discount_markup_validation = [

        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
            ],
        ],

        'from_airport_code' => [
            'label' => 'from airport',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter from airport'
            ],
        ],

        'to_airport_code' => [
            'label' => 'to airport',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter to airport'
            ],
        ],

        'is_domestic' => [
            'label' => 'flight type',
            'rules' => 'trim|required|exact_length[1]',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],

        'journey_type' => [
            'label' => 'journey type',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select journey type'
            ],
        ],

        'travel_date_from' => [
            'label' => 'from date',
            'rules' => 'trim|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date_to' => [
            'label' => 'to date',
            'rules' => 'trim|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'pax_type' => [
            'label' => 'pax type',
            'rules' => 'trim|required|max_length[3]',
            'errors' => [
                'required' => 'Please select pax type'
            ],
        ],

        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],



        'amount' => [
            'label' => 'amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter amount'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],

        'agent_class' => [
            'label' => 'agent class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],
    ];


    public $bus_markup_validation = [

        'min_range' => [
            'label' => 'min range',
            'rules' => 'trim|required|numeric|is_natural_no_zero',
            'errors' => [
                'required' => 'Please enter min range'
            ],
        ],

        'max_range' => [
            'label' => 'max range',
            'rules' => 'trim|required|numeric|is_natural_no_zero',
            'errors' => [
                'required' => 'Please enter max range'
            ],
        ],


        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],

        'agent_class' => [
            'label' => 'agent class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],
    ];
}
