<?php
namespace Modules\SupplierManagement\Config;


class Validation
{
    public $flight_api_supllier_mgt_validation = [
        'api_supplier_id' => [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  supplier'

            ],
        ],

        'air_type' => [
            'label' => 'air type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  air type'

            ],
        ],

        'search_type' => [
            'label' => 'search type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  search type'

            ],
        ],


        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'

            ],
        ],


        'allowed_airline' => [
            'label' => 'allowed airline',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please select  allowed airline'

            ],
        ],


        'excluded_airline' => [
            'label' => 'excluded airline',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please select excluded airline'

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

}

