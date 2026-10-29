<?php
namespace Modules\Currency\Config;

class Validation
{
    public $currency_validation = [
        'base_currency' => [
            'label' => 'base_currency',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter base_currency'

            ],
        ],

        'convert_currency' => [
            'label' => 'convert_currency',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convert_currency'

            ],
        ],


        'convertion_rate' => [
            'label' => 'convertion rate',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convertion Rate'

            ],
        ],

        'base_currency_name' => [
            'label' => 'Base Currency Name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Base Currency Name'

            ],
        ],


        'convert_currency_name' => [
            'label' => 'Convert Currency Name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Convert Currency Name'

            ],
        ],

        'base_currency_symbol' => [
            'label' => 'base currency symbol',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select base currency symbol'

            ],
        ],

        'convert_currency_symbol' => [
            'label' => 'convert currency symbol',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convert currency symbol'

            ],
        ],



       
    ];



    public $currency_validation_update = [
        


        'convertion_rate' => [
            'label' => 'convertion rate',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select convertion Rate'

            ],
        ],

      


       
    ];



   

}
