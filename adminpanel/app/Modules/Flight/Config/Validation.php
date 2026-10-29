<?php
namespace Modules\Flight\Config;
class Validation
{

    public $airport_validation = [
        'code' => [
            'label' => 'code',
            'rules' => "trim|required|exact_length[3]|alpha|is_unique[flight_airports.code]",
            'errors' => [
                'required' => 'Please enter airport code'

            ],
        ],

        'name' => [
            'label' => 'airport name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter airport name'

            ],
        ],

        'city_name' => [
            'label' => 'city name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter city name'

            ],
        ],

        'city_code' => [
            'label' => 'city code',
            'rules' => 'trim|required|exact_length[3]|alpha',
            'errors' => [
                'required' => 'Please enter city code'

            ],
        ],

        'country_name' => [
            'label' => 'country',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter country'
            ],
        ],

        'country_code' => [
            'label' => 'country code',
            'rules' => 'trim|required|exact_length[2]|alpha',
            'errors' => [
                'required' => 'Please enter country code'

            ],
        ],
    ];

    public $airline_validation = [
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|is_unique[flight_airline_code.airline_code]|exact_length[2]|alpha_numeric',
            'errors' => [
                'required' => 'Please enter airline code'

            ],
        ],
        'airline_name' => [
            'label' => 'airline name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter airline name'

            ],
        ],
    ];
    
    public $raiseAmendment = [
        "amendment_type"=>['rules' => 'trim|required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]','errors' => [
            'required' => 'Please select Amendment Type'
        ],],
        "booking_ref_number"=>['rules' => 'required','errors' => [
            'required' => 'Please enter booking reference number'
        ]],
    ];
    public $raiseAmendmentType = [
        "amendment_type"=>['rules' => 'trim|required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]','errors' => [
            'required' => 'Please select Amendment Type'
        ],],
        "booking_ref_number"=>['rules' => 'required','errors' => [
            'required' => 'Please enter booking refrence number'
        ]],
        "remark"=>['rules' => 'required','errors' => [
            'required' => 'Please enter remark'
        ]],
        "passengers.*"=>['rules' => 'required','errors' => [
            'required' => 'Please select passengers'
        ]],
    ];

    public $flight_offline = [
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
            ],
        ],

        'from_airport_code' => [
            'label' => 'from airport',
            'rules' => 'trim|required|max_length[3]',
            'errors' => [
                'required' => 'Please enter from airport'
            ],
        ],

        'departure_days' => [
            'label' => 'departure days',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter departure days'
            ],
        ],
        'supplier.*' => [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier'
            ],
        ],
        'is_domestic.*' => [
            'label' => 'is_domestic',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select fare type'
            ],
        ],
        'faretype.*' => [
            'label' => 'faretype',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],
        'cabin_class.*' => [
            'label' => 'cabin_class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],
        'to_airport_code' => [
            'label' => 'to airport',
            'rules' => 'trim|required|max_length[3]',
            'errors' => [
                'required' => 'Please enter to airport'
            ],
        ],
        'booking_class' => [
            'label' => 'to airport',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],
        'is_hold' => [
            'label' => 'is hold',
            'rules' => 'trim|max_length[10]',
        ],
        'is_offline' => [
            'label' => 'is offline',
            'rules' => 'trim|max_length[10]',
        ],
        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];


    public $flight_discount_markup_validation = [
       
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
            ],
        ],

        'agent_class.*' => [
            'label' => 'Agent class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter agent class'
            ],
        ],

        'from_airport_code' => [
            'label' => 'from airport',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter from airport'
            ],
        ],

        'to_airport_code' => [
            'label' => 'to airport',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter to airport'
            ],
        ],
        'booking_class' => [
            'label' => 'Booking Class',
            'rules' => 'trim|required|max_length[250]',
            'errors' => [
                'required' => 'Please enter booking class'
            ],
        ],

        'is_domestic.*' => [
            'label' => 'flight type',
            'rules' => 'trim|required|exact_length[1]',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],

        'journey_type.*' => [
            'label' => 'journey type',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select journey type'
            ],
        ],

        'travel_date_from' => [
            'label' => 'from date',
            'rules' => 'trim|required|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date_to' => [
            'label' => 'to date',
            'rules' => 'trim|required|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'cabin_class.*' => [
            'label' => 'cabin class',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],

        'faretype.*' => [
            'label' => 'fare type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select fare type'
            ],
        ],


        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
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
    public function ticket_update_validation($data)
    {
        $booking_validation = [];

        $booking_validation["supplier"] = [
            'label' => 'Supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Supplier',
            ],
        ];

        foreach ($data['pax'] as $key => $requestParameter) {



            $booking_validation["pax.$key.pnr"] = [

                'label' => 'Pnr',

                'rules' => 'trim|required|alpha_numeric|min_length[6]|max_length[10]',

                'errors' => [

                    'required' => 'Please enter pnr',

                    'alpha_numeric' => 'Pnr contain only alpha numeric character',

                    'exact_length' => 'Please enter  exact  six digit long pnr',
                    'min_length' => 'Please enter  min  6 digit long pnr',
                    'max_length' => 'Please enter  max  10 digit long pnr',

                ],

            ];

            $booking_validation["pax.$key.ticket_number"] = [

                'label' => 'Ticket Number',

                'rules' => 'trim|required|alpha_numeric',

                'errors' => [

                    'required' => 'Please enter Ticket Number',

                    'alpha_numeric' => 'Ticket number contain only alpha numeric character',

                ],

            ];

            $booking_validation["pax.$key.booking_status"] = [
                'label' => 'Booking Status',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select booking status',
                ],
            ];
        }

        return $booking_validation;
    }
    public $EmailTicketValidation = [
        "email" => [
            'label' => 'email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ]
    ];
    public $refund_close_status = [

        'status' => [
            'label' => 'Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
        'account_remark' => [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter remark'
            ],
        ],

    ];
    public $amendment_status = [

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
    public $amendment_refund_validation = [

        'amendment_id' => [
            'label' => 'AmendmentId',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter AmendmentId'
            ],
        ],
        'charge.*.charge' => [
            'label' => 'charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter charge',
                'numeric' => 'Please enter valid charge',
                'greater_than_equal_to' => 'Please enter valid charge'
            ],
        ],
        'charge.*.service_charge' => [
            'label' => 'service charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge',
                'numeric' => 'Please enter service valid charge',
                'greater_than_equal_to' => 'Please enter service valid charge'
            ],
        ],
        'charge.*.service_charge_gst' => [
            'label' => 'service charge gst',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge gst',
                'numeric' => 'Please enter service valid charge gst',
                'greater_than_equal_to' => 'Please enter service valid charge gst'
            ],
        ],
        'charge.*.pax_id' => [
            'label' => 'paxId',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter paxId'
            ],
        ]

    ];

    public $api_fairtype_validation = [
        'supplier_fare_type' => [
            'label' => 'Supplier Fare Type',
            'rules' => 'trim|required|is_unique[api_flight_fare_type.supplier_fare_type]',
            'errors' => [
                'required' => 'Please enter Supplier Fare Type'

            ],
        ],
        'api_fare_type' => [
            'label' => 'API Fare Type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter API Fare Type'
            ],
        ],
        'api_supplier' => [
            'label' => 'API Supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter API Supplier'
            ],
        ],
    ];
}

