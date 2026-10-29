<?php

namespace Modules\FlightTopRoutes\Config;


class Validation
{

    public $flight_top_routes_validation = [
         
        'journeytype' => [
            'label' => 'journey type',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select journey type'
            ],
        ],
        'origin' => [
            'label' => 'origin',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter origin'
            ],
        ],
        'destination' => [
            'label' => 'destination',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter destination'
            ],
        ],
        'direct_flight' => [
            'label' => 'direct flight',
            'rules' => 'trim|required|max_length[40]',
            'errors' => [
                'required' => 'Please enter direct flight'
            ],
        ], 
        // 'depart_date' => [
        //     'label' => 'depart date',
        //     'rules' => 'trim|required|max_length[25]',
        //     'errors' => [
        //         'required' => 'Please select depart date'
        //     ],
        // ], 
        // 'return_date' => [
        //     'label' => 'return date',
        //     'rules' => 'trim|required|max_length[25]',
        //     'errors' => [
        //         'required' => 'Please select depart date'
        //     ],
        // ], 
        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ], 
        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'adult' => [
            'label' => 'adult',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select adult'
            ],
        ],
        'child' => [
            'label' => 'child',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select child'
            ],
        ],
        'infant' => [
            'label' => 'infant',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select infant'
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

