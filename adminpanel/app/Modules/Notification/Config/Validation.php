<?php
namespace Modules\Notification\Config;


class Validation
{
    public $notification_validation = [
        'title' => [
            'label' => 'title',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter title'

            ],
        ],



        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'

            ],
        ],

        'description' => [
            'label' => 'description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter  description'
            ],
        ],


    ];

    public $status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

}

