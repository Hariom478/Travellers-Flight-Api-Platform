<?php

namespace Modules\Flight\Config;


class Validation
{


    public $raiseAmendment = [
        "amendment_type" => [
            'rules' => 'trim|required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]',
            'errors' => [
                'required' => 'Please select Amendment Type'
            ],
        ],
        "booking_ref_number" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter booking refrence number'
            ]
        ],
    ];
    public $raiseAmendmentType = [
        "amendment_type" => [
            'rules' => 'trim|required|in_list[cancellation,full_refund,cancellation_quotation,correction,re_issue,no_show,reissue]',
            'errors' => [
                'required' => 'Please select Amendment Type'
            ],
        ],
        "booking_ref_number" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter booking refrence number'
            ]
        ],
        "remark" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please enter remark'
            ]
        ],
        "passengers.*" => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Please select passengers'
            ]
        ],
    ];

    public function pax_validation($data, $searchRequest)
    {
        $booking_validation = [];
        if ($data['passport_requird']) {
            $IsPassportRequired = 'required';
        } else {
            $IsPassportRequired = 'permit_empty';
        }
        foreach ($data['pax'] as $key => $requestParameter) {
            foreach ($requestParameter as $key1 => $requestParameter1) {
                $booking_validation["pax.$key.$key1.title"] = [
                    'label' => 'title',
                    'rules' => 'trim|required|in_list[Mr,Ms,Mrs,Miss,Mstr]',
                    'errors' => [
                        'required' => 'Please select title'
                    ],
                ];
                $booking_validation["pax.$key.$key1.first_name"] = [
                    'label' => 'first name',
                    'rules' => 'trim|required|alpha_space',
                    'errors' => [
                        'required' => 'Please enter first name',
                        'alpha_space' => 'Please enter valid first name'
                    ],
                ];
                $booking_validation["pax.$key.$key1.last_name"] = [
                    'label' => 'last name',
                    'rules' => 'trim|required|alpha_space|min_length[2]',
                    'errors' => [
                        'required' => 'Please enter last name',
                        'alpha_space' => 'Please enter valid last name',
                        'min_length' => 'Last name should minimum two character long',
                    ],
                ];
                if ($key == "Child" || $key == "Infant") {
                    $booking_validation["pax.$key.$key1.dob"] = [
                        'label' => 'dob',
                        'rules' => 'trim|required|valid_date[d-m-yy]',
                        'errors' => [
                            'required' => 'Please enter dob',
                            'valid_date' => 'Please select valid dob'
                        ],
                    ];
                } else {
                    if ($data['adult_dob']) {
                        $booking_validation["pax.$key.$key1.dob"] = [
                            'label' => 'dob',
                            'rules' => 'trim|required|valid_date[d-m-yy]',
                            'errors' => [
                                'required' => 'Please enter dob',
                                'valid_date' => 'Please select valid dob'
                            ],
                        ];
                    } else {
                        $booking_validation["pax.$key.$key1.dob"] = [
                            'label' => 'dob',
                            'rules' => 'trim|permit_empty|valid_date[d-m-yy]',
                            'errors' => [
                                'valid_date' => 'Please select valid dob'
                            ],
                        ];
                    }
                }

                if ($data['pancard_requird']) {
                    $booking_validation["pax.$key.$key1.pancard"] = [
                        'label' => 'pan card number',
                        'rules' => 'trim|required|regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]',
                        'errors' => [
                            'required' => 'Please enter pan card number',
                            'regex_match' => 'Please enter valid pan card number'
                        ],
                    ];
                }
                $booking_validation["pax.$key.$key1.frequent_fly_airline"] = [
                    'label' => 'frequent fly airline',
                    'rules' => 'trim|permit_empty|exact_length[2]',
                    'errors' => [
                        'required' => 'Please enter frequent fly airline',
                        'exact_length' => 'Please select valid frequent fly airline',
                    ],
                ];

                $booking_validation["pax.$key.$key1.nationality"] = [
                    'label' => 'passport nationality',
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[pax.' . $key . '.' . $key1 . '.passport_no]|exact_length[2]',
                    'errors' => [
                        'required' => 'Please select passport nationality',
                        'exact_length' => 'Please select valid passport nationality',
                    ],
                ];
                $booking_validation["pax.$key.$key1.passport_no"] = [
                    'label' => 'passport number',
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[pax.' . $key . '.' . $key1 . '.passport_expire_date]',
                    'errors' => [
                        'required' => 'Please enter passport number',
                        'regex_match' => 'Please enter valid passport number',
                    ],
                ];
                $booking_validation["pax.$key.$key1.passport_issue_date"] = [
                    'label' => 'passport issue date',
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[pax.' . $key . '.' . $key1 . '.passport_no]|valid_date[d-m-yy]',
                    'errors' => [
                        'required' => 'Please enter passport issue date',
                        'valid_date' => 'Please select valid passport issue date'
                    ],
                ];
                $booking_validation["pax.$key.$key1.passport_expire_date"] = [
                    'label' => 'passport expire date',
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[pax.' . $key . '.' . $key1 . '.passport_no]|valid_date[d-m-yy]',
                    'errors' => [
                        'required' => 'Please enter passport expire date',
                        'valid_date' => 'Please select valid passport expire date'
                    ],
                ];


            }
        }
        $booking_validation['SearchTokenId'] = [
            'label' => 'SearchTokenId',
            'rules' => 'trim|required'
        ];
        $booking_validation['farecode'] = [
            'label' => 'farecode',
            'rules' => 'trim|required'
        ];
        if ($searchRequest['JourneyType'] == 2 && $searchRequest['IsDomestic']) {
            $booking_validation['farecodereturn'] = [
                'label' => 'farecode',
                'rules' => 'trim|required'
            ];
        }
        $booking_validation['rtype'] = [
            'label' => 'rtype|in_list[frcmn]',
            'rules' => 'trim|required'
        ];
        $booking_validation['email'] = [
            'label' => 'email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ];
        $booking_validation['dial_code'] = [
            'label' => 'dial code',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'numeric' => 'Please select dial code'
            ],
        ];
        if ($data['dial_code'] == "91") {
            $booking_validation['mobile_number'] = [
                'label' => 'mobile number',
                'rules' => 'trim|required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'numeric' => 'Please enter valid mobile number',
                    'required' => 'Please enter mobile number'
                ],
            ];
        } else {
            $booking_validation['mobile_number'] = [
                'label' => 'mobile number',
                'rules' => 'trim|required|numeric|min_length[7]|max_length[15]',
                'errors' => [
                    'numeric' => 'Please enter valid mobile number',
                    'required' => 'Please enter mobile number'
                ],
            ];
        }

        if ($data['add_gst_detail'] == 'true') {
            $booking_validation['gst.email'] = [
                'label' => 'email',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => 'Please enter email',
                    'valid_email' => 'Please enter valid email'
                ],
            ];
            $booking_validation['gst.name'] = [
                'label' => 'company name',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter company name'
                ],
            ];

            $booking_validation['gst.phone'] = [
                'label' => 'mobile number',
                'rules' => 'trim|required|numeric|min_length[7]|max_length[15]',
                'errors' => [
                    'required' => 'Please enter mobile number'
                ],
            ];

            $booking_validation['gst.address'] = [
                'label' => 'address',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter address'
                ],
            ];

            $booking_validation['gst.number'] = [
                'label' => 'gst number',
                'rules' => 'trim|required|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
                'errors' => [
                    'required' => 'Please enter gst number'
                ],
            ];
        }
        return $booking_validation;
    }
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

    public $flight_discount_markup_b2c_validation = [

        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
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

        'is_domestic' => [
            'label' => 'flight type',
            'rules' => 'trim|required|exact_length[1]',
            'errors' => [
                'required' => 'Please select flight type'
            ],
        ],

        'journey_type' => [
            'label' => 'journey type',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select journey type'
            ],
        ],

        'travel_date_from' => [
            'label' => 'from date',
            'rules' => 'trim|max_length[25]',
            'errors' => [
                'required' => 'Please select from date'
            ],
        ],

        'travel_date_to' => [
            'label' => 'to date',
            'rules' => 'trim|max_length[25]',
            'errors' => [
                'required' => 'Please select to date'
            ],
        ],

        'pax_type' => [
            'label' => 'pax type',
            'rules' => 'trim|required|max_length[3]',
            'errors' => [
                'required' => 'Please select pax type'
            ],
        ],

        'cabin_class' => [
            'label' => 'cabin class',
            'rules' => 'trim|required|max_length[20]',
            'errors' => [
                'required' => 'Please select cabin class'
            ],
        ],



        'amount' => [
            'label' => 'amount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter amount'
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
            'rules' => 'trim|max_length[3]',
        ],

        'to_airport_code' => [
            'label' => 'to airport',
            'rules' => 'trim|max_length[3]',
        ],


        'business_type' => [
            'label' => 'business type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select business type'
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
    function search_validation($data)
    {
        $search_validation_array = array();
        $search_validation_array['cabinclass'] = [
            'label' => 'Cabin Class',
            'rules' => 'trim|required|in_list[Any,Economy,Business,PremiumEconomy,PremiumBusiness,First]',
            'errors' => [
                'required' => 'Please select cabinclass'
            ],
        ];
        if (isset($data['journeytype']) && $data['journeytype'] == "Multicity") {
            foreach ($data['search_data'] as $key => $search_data) {
                $search_validation_array["search_data.$key.origin"] = [
                    'label' => 'From airport',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select from airport'
                    ],
                ];
                $search_validation_array["search_data.$key.destination"] = [
                    'label' => 'To airport',
                    'rules' => "trim|required|differs[search_data.$key.origin]",
                    'errors' => [
                        'required' => 'Please select to airport',
                        'differs' => 'Please select different airports'
                    ],
                ];
                $search_validation_array["search_data.$key.departdate"] = [
                    'label' => 'Departure Date',
                    'rules' => 'trim|required|valid_date[d M y]',
                    'errors' => [
                        'required' => 'Please select departure date',
                        'valid_date' => 'Please select departure date'
                    ],
                ];
            }
        } else {

            if ($data['journeytype'] == "Roundtrip") {
                $search_validation_array['returndate'] = [
                    'label' => 'Return Date',
                    'rules' => 'trim|required|valid_date[d M y]',
                    'errors' => [
                        'required' => 'Please select return date',
                        'valid_date' => 'Please select return date'
                    ],
                ];
            }
            $search_validation_array['origin'] = [
                'label' => 'From airport',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select from airport'
                ],
            ];
            $search_validation_array['destination'] = [
                'label' => 'To airport',
                'rules' => 'trim|required|differs[origin]',
                'errors' => [
                    'required' => 'Please select to airport',
                    'differs' => 'Please select different airports'
                ],
            ];
            $search_validation_array['departdate'] = [
                'label' => 'Departure Date',
                'rules' => 'trim|required|valid_date[d M y]',
                'errors' => [
                    'required' => 'Please select departure date',
                    'valid_date' => 'Please select departure date'
                ],
            ];
        }
        return $search_validation_array;
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
}