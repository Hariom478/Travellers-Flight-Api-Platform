<?php
namespace Modules\WebPartnerAccount\Config;

class Validation
{
    public $web_partner_account =[
        'amount' => [
            'label' => 'amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter  amount'
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
}
