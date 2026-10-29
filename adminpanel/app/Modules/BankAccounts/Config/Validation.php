<?php
namespace Modules\BankAccounts\Config;


class Validation
{
    public $bank_accounts_validation = [
        'bank_name' => [
            'label' => 'bank name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter bank name'

            ],
        ],
        'branch_name' => [
            'label' => 'branch name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter branch name'

            ],
        ],
        'account_holder_name' => [
            'label' => 'account holder name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter account holder name'
            ],
        ],
        'account_no' => [
            'label' => 'account no',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please select account no'
            ],
        ],
        'ifsc_code' => [
            'label' => 'ifsc code',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter ifsc code'
            ],
        ],
        'swift_code' => [
            'label' => 'swift code',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter swift code'
            ],
        ],

    ];



}

