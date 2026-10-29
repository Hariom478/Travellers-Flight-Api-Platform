<?php
namespace Modules\OfflineSupplier\Config;

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

    public $supplier_validation = [
        'supplier_name' => [
            'label' => 'Supplier Name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter supplier name.',
            ],
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'trim',
            'errors' => [
                'valid_email'=>'Please enter valid email id.'
            ],
        ],
        'mobile_no' => [
            'label' => 'Mobile Number',
            'rules' => 'trim',
        ],
        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
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
	

}
