<?php
namespace Modules\ConvenienceFee\Config;


class Validation
{
    public $convenience_fee_validation = [
        'payment_getway' => [
            'label' => 'payment gateway',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter payment gateway'
            ],
        ],

        'service.*' => [
            'label' => 'service',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter service'

            ],
        ],

        'web_partner_class_id.*' => [
            'label' => 'Web Partner Class',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please select Web Partner Class'

            ],
        ],

        'credit_card_value' => [
            'label' => 'credit card type',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter credit card value'
            ],
        ],

        'credit_card_type' => [
            'label' => 'credit card type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select credit card type'
            ],
        ],
 
        'debit_card_value' => [
            'label' => 'debit card type',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter debit card value'
            ],
        ],

        'debit_card_type' => [
            'label' => 'type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select debit card type'
            ],
        ],






        'net_banking_value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'net_banking_type' => [
            'label' => 'type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter type'
            ],
        ],

        'upi_value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'upi_type' => [
            'label' => 'type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter type'
            ],
        ],

        'mobile_wallet_value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'mobile_wallet_type' => [
            'label' => 'type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter type'
            ],
        ],

    ];



}

