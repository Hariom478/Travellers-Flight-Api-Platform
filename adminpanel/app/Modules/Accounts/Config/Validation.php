<?php

namespace Modules\Accounts\Config;

class Validation
{


    public function makePaymentRequest($data,$upload_file)
    {
        $makePaymentRequest = [];

        $makePaymentRequest['amount'] = [
            'label' => 'amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter  amount'
            ],
        ];
        $makePaymentRequest['payment_mode'] = [
            'label' => 'action type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  Payment Mode'
            ],
        ];
        if ($data['payment_mode'] == "Cheque" || $data['payment_mode'] == "Draft" || $data['payment_mode'] == "RTGS" || $data['payment_mode'] == "NEFT") {
            $errorMessage = "Please enter " . strtolower($data['payment_mode']) . " number";
            $makePaymentRequest['cheque_draft_utr_number'] = [
                'label' => 'cheque draft utr number',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => $errorMessage
                ],
            ];
        }

        if ($upload_file->getName() != '') {
            $errorMessage = "Upload " . strtolower($data['payment_mode']) . " image";
            $makePaymentRequest['upload_file'] = [
                'label' => 'upload document',
                'rules' => 'uploaded[upload_file]|max_size[upload_file,1024]|mime_in[upload_file,image/png,image/jpg,image/jpeg,image/pdf]',
                'errors' => [
                    'required' => $errorMessage
                ],
            ];
        }
        $onlinepaymentMode = array('VisaCreditCard','AmericanExpressCreditCard','MastercardCreditCard','RuPayCreditCard' ,'DebitCard', 'NetBanking', 'UPIPayments');
        if (!in_array($data['payment_mode'], $onlinepaymentMode)) {
            $makePaymentRequest['date'] = [
                'label' => 'date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => "Please Select Date"
                ],
            ];
            $makePaymentRequest['bank'] = [
                'label' => 'bank',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => "Please enter bank name"
                ],
            ];
            $makePaymentRequest['branch'] = [
                'label' => 'branch',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => "Please enter branch name"
                ],
            ];
            $makePaymentRequest['company_bank_account'] = [
                'label' => 'company bank account',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => "Please select company bank account"
                ],
            ];
        }
        $makePaymentRequest['remark'] = [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ];

        return $makePaymentRequest;
    }


    public $payment_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'admin_remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];

}
