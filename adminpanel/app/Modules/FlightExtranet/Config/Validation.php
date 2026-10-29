<?php

namespace Modules\FlightExtranet\Config;


class Validation
{
    public function private_fare($data)
    {
        $validation = [];

        $validation['inventory_name'] = [
            'label' => 'inventory name',
            'rules' => 'trim|required|max_length[100]',
            'errors' => [
                'required' => 'Please enter inventory name'
            ],
        ];
        $validation['disable_before_departure'] = [
            'label' => 'disable before departure',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter disable before departure'
            ],
        ];

        $validation['trip_type'] = [
            'label' => 'trip type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select trip type'
            ],
        ];

        $validation['onward_stops'] = [
            'label' => 'onward stops',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select onward stops'
            ],
        ];

        if (isset($data['onward'])) {
            foreach ($data['onward'] as $key => $requestParameter) {

                foreach ($requestParameter as $supkey => $subrequestParameter) {
                $validation["onward.$key.$supkey.origin_airport_code"] = [
                    'label' => 'origin airport code',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select origin airport code'
                    ],
                ];

                $validation["onward.$key.$supkey.destination_airport_code"] = [
                    'label' => 'destination airport code',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select destination airport code'
                    ],
                ];

                $validation["onward.$key.$supkey.airline_code"] = [
                    'label' => 'airline code',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select airline code'
                    ],
                ];

                $validation["onward.$key.$supkey.flight_number"] = [
                    'label' => 'flight number',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please enter flight number'
                    ],
                ];

                $validation["onward.$key.$supkey.departure_time"] = [
                    'label' => 'departure time',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select departure time'
                    ],
                ];

                $validation["onward.$key.$supkey.arrival_time"] = [
                    'label' => 'arrival time',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select arrival time'
                    ],
                ];

                $validation["onward.$key.$supkey.is_next_day_arrival"] = [
                    'label' => 'is next day arrival',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select is next day arrival'
                    ],
                ];
            }}
        }

       return $validation;
    }



    public $status = [

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];

    public $seat_allocation = [
        // 'rate_plan_id' => [
        //     'label' => 'rate plan',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select rate plan'
        //     ],
        // ],
        

        'start_date' => [
            'label' => 'start date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select start date'
            ],
        ],

        'end_date' => [
            'label' => 'end date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select end date'
            ],
        ],



        'available_seats' => [
            'label' => 'available  seat',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter available  seat'
            ],
        ],
        'booking_class' => [
            'label' => 'booking class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],

        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],

        'adult_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'adult_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'adult_gst' => [
            'label' => 'tax',
            'rules' => 'trim|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'child_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'child_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'child_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'infant_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'infant_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

     /*    'infant_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


    ];
    public $seat_international_return_allocation = [
        // 'rate_plan_id' => [
        //     'label' => 'rate plan',
        //     'rules' => 'trim|required',
        //     'errors' => [
        //         'required' => 'Please select rate plan'
        //     ],
        // ],

        'date' => [
            'label' => 'start date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select onward date'
            ],
        ],

        'date_return' => [
            'label' => 'end date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select return date'
            ],
        ],



        'available_seats' => [
            'label' => 'available  seat',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter available  seat'
            ],
        ],
        'booking_class' => [
            'label' => 'booking class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],

        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],

        'adult_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'adult_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'adult_gst' => [
            'label' => 'tax',
            'rules' => 'trim|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'child_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'child_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'child_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'infant_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'infant_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

     /*    'infant_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */



    ];

    public $fare_rule = [
        'description' => [
            'label' => 'description',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter description'
            ],
        ],
        'booking_class' => [
            'label' => 'booking_class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],
        'air_type' => [
            'label' => 'air type',
            'rules' => 'trim|required|in_list[All,Domestic,International]',
            'errors' => [
                'required' => 'Please select air type'
            ],
        ],
        'status' => [
            'label' => 'status',
            'rules' => 'trim|required|in_list[active,inactive]',
            'errors' => [
                'required' => 'Please select fare status'
            ],
        ],

        'airline_code' => [
            'label' => 'airline',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select airline'
            ],
        ],
        'hand_baggage_adult' => [
            'label' => 'baggage adult',
            'rules' => 'trim',
        ],

        'hand_baggage_child' => [
            'label' => 'baggage child',
            'rules' => 'trim',
        ],

        'hand_baggage_infant' => [
            'label' => 'baggage infant',
            'rules' => 'trim',
        ],

        'checkin_baggage_adult' => [
            'label' => 'baggage adult',
            'rules' => 'trim',
        ],
        'checkin_baggage_child' => [
            'label' => 'baggage child',
            'rules' => 'trim',
        ],

        'checkin_baggage_infant' => [
            'label' => 'baggage infant',
            'rules' => 'trim',
        ],
        'refundable_type' => [
            'label' => 'refundable type',
            'rules' => 'trim|required|in_list[Refundable,NonRefundable]',
        ],
    ];

    public $rate_plan = [
        'plan_name' => [
            'label' => 'plan name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter plan name'
            ],
        ],
        'booking_class' => [
            'label' => 'booking class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],

        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],

        'adult_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'adult_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'adult_gst' => [
            'label' => 'tax',
            'rules' => 'trim|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'child_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'child_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

        /* 'child_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */


        'infant_base_fare' => [
            'label' => 'base fare',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter base fare'
            ],
        ],

        'infant_tax' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter tax'
            ],
        ],

     /*    'infant_gst' => [
            'label' => 'tax',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter gst'
            ],
        ], */

    ];
}

