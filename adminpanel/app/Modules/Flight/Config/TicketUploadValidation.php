<?php
namespace Modules\Flight\Config;
class TicketUploadValidation

{

    public function pax_validation($data)

    {

        $booking_validation = [];

        foreach ($data['pax'] as $key => $requestParameter) {

                    $booking_validation["pax.$key.title"] = [

                        'label' => 'title',

                        'rules' => 'trim|required|in_list[Mr,Ms,Mrs,Miss,Mstr]',

                        'errors' => [

                            'required' => 'Please select title'

                        ],

                    ];

                    $booking_validation["pax.$key.first_name"] = [

                        'label' => 'first name',

                        'rules' => 'trim|required|alpha_space',

                        'errors' => [

                            'required' => 'Please enter first name',

                            'alpha_space' => 'Please enter valid first name'

                        ],

                    ];



                    $booking_validation["pax.$key.last_name"] = [

                        'label' => 'last name',

                        'rules' => 'trim|required|alpha_space',

                        'errors' => [

                            'required' => 'Please enter last name',

                            'alpha_space' => 'Please enter valid last name'

                        ],

                    ];

                    $booking_validation["pax.$key.pax_type"] = [

                        'label' => 'pax type',

                        'rules' => 'trim|required|in_list[Adult,Child,Infant]',

                        'errors' => [

                            'required' => 'Please select pax type',

                            'in_list' => 'Please select valid pax type'

                        ],

                    ];

                      $booking_validation["pax.$key.gendar"] = [

                        'label' => 'Gendar',

                        'rules' => 'trim|required|in_list[Male,Female]',

                        'errors' => [

                            'required' => 'Please select gendar'

                        ],

                    ];

                    if ($requestParameter['pax_type'] == "Child"|| $requestParameter['pax_type'] == "Infant") {

                        $booking_validation["pax.$key.date_of_birth"] = [

                            'label' => 'dob',

                            'rules' => 'trim|required|valid_date[d M yy]',

                            'errors' => [

                                'required' => 'Please enter dob',

                                'valid_date' => 'Please select valid dob'

                            ],

                        ];

                    }

                    else{

                        $booking_validation["pax.$key.date_of_birth"] = [

                            'label' => 'dob',

                            'rules' => 'trim|permit_empty|valid_date[d M yy]',

                            'errors' => [

                                'valid_date' => 'Please select valid dob'

                            ],

                        ];

                    }

                        $booking_validation["pax.$key.pan_number"] = [

                            'label' => 'pan card number',

                            'rules' => 'trim|permit_empty|regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]',

                            'errors' => [

                                'required' => 'Please enter pan card number',

                                'regex_match' => 'Please enter valid pan card number'

                            ],

                        ];

                        $booking_validation["pax.$key.passport_nationality"] = [

                            'label' => 'passport nationality',

                            'rules' => 'trim|permit_empty|exact_length[2]',

                            'errors' => [

                                'required' => 'Please select passport nationality',

                                'exact_length' => 'Please select valid passport nationality',

                            ],

                        ];

                        $booking_validation["pax.$key.passport_number"] = [

                            'label' => 'passport number',

                            'rules' => 'trim|permit_empty|regex_match[/^[A-PR-WYa-pr-wy][1-9]\\d\\s?\\d{4}[1-9]$/]',

                            'errors' => [

                                'required' => 'Please enter passport number',

                                'regex_match' => 'Please enter valid passport number',

                            ],

                        ];

                        $booking_validation["pax.$key.passport_issue_date"] = [

                            'label' => 'passport issue date',

                            'rules' => 'trim|permit_empty|valid_date[d M yy]',

                            'errors' => [

                                'required' => 'Please enter passport issue date',

                                'valid_date' => 'Please select valid passport issue date'

                            ],

                        ];

                        $booking_validation["pax.$key.passport_expiry"] = [

                            'label' => 'passport expire date',

                            'rules' => 'trim|permit_empty|valid_date[d M yy]',

                            'errors' => [

                                'required' => 'Please enter passport expire date',

                                'valid_date' => 'Please select valid passport expire date'

                            ],

                        ];

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

                  

                    /* fare validation */

        }

      /* fare validation */

        foreach($data['pricing'] as $key => $Parameter)

        {

            $booking_validation["pricing.$key.base_fare"] = [

                'label' => 'base_fare',

                'rules' => 'trim|required|greater_than_equal_to[0]',

                'errors' => [

                    'required' => 'Please enter base fare value',

                    'greater_than_equal_to' => 'Please enter valid base fare value',

                ],

            ];

            $booking_validation["pricing.$key.tax"] = [

                'label' => 'Tax',

                'rules' => 'trim|required|greater_than_equal_to[0]',

                'errors' => [

                    'required' => 'Please enter tax value',

                    'greater_than_equal_to' => 'Please enter valid tax value',

                ],

            ];

           $booking_validation["pricing.$key.other_charges"] = [

                'label' => 'Other Charge',

                'rules' => 'trim|required|greater_than_equal_to[0]',

                'errors' => [

                    'required' => 'Please enter other charges value',

                    'greater_than_equal_to' => 'Please enter valid other charges value',

                ],

            ];

        /*    $booking_validation["pricing.$key.markup"] = [

                'label' => 'Markup',

                'rules' => 'trim|required|greater_than_equal_to[0]',

                'errors' => [

                    'required' => 'Please enter markup value',

                    'greater_than_equal_to' => 'Please enter valid sMarkup value',

                ],

            ]; */

        }
        $booking_validation["deal.basic"] = [
            'label' => 'Basic',
            'rules' => 'trim|required|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter Basic value',
                'greater_than_equal_to' => 'Please enter valid Basic value',
            ],
        ];
        $booking_validation["deal.yq"] = [
            'label' => 'YQ',
            'rules' => 'trim|required|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter YQ value',
                'greater_than_equal_to' => 'Please enter valid YQ value',
            ],
        ];
        $booking_validation["deal.basic_iata"] = [
            'label' => 'Basic IATA',
            'rules' => 'trim|required|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter Basic IATA value',
                'greater_than_equal_to' => 'Please enter valid Basic IATA value',
            ],
        ];
        $booking_validation["deal.yq_iata"] = [
            'label' => 'YQ IATA',
            'rules' => 'trim|required|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter YQ IATA value',
                'greater_than_equal_to' => 'Please enter valid YQ IATA value',
            ],
        ];
        $booking_validation["deal.markup"] = [
            'label' => 'Markup',
            'rules' => 'trim|required|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter markup value',
                'greater_than_equal_to' => 'Please enter valid markup value',
            ],
        ];
        $booking_validation["deal.display_markup"] = [
            'label' => 'Display Markup',
            'rules' => 'trim|required|in_list[in_tax,in_service_charge]',
            'errors' => [
                'required' => 'Please select Display Markup',
                'in_list' => 'Please select valid Display Markup'
            ],
        ];

        $booking_validation['temptripSegmentId'] = [

            'label' => 'TripSegmentId',

            'rules' => 'trim|required'

        ];

        return $booking_validation;

    }

    public function ticket_update_validation($data)
    {
        $booking_validation = [];

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

    public function segmentsinfo_validation($data)

    {

        $segmentsinfo_validation = [];

        $segmentsinfo_validation["ticket_type"] = [

            'label' => 'Ticket Type',

            'rules' => 'trim|required|in_list[UploadTicket,ModifyTicket,Reissue]',

            'errors' => [

                'required' => 'Please select ticket type',

                'in_list' => 'Please select ticket type',

            ],

        ];

        if($data['ticket_type']=="UploadTicket"){
            
            $segmentsinfo_validation["bussiness_type"] = [
                'label' => 'Business Type',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select business type',
                ],
            ];

            if($data['bussiness_type'] == 'B2B'){
                $segmentsinfo_validation['agent_info'] = [
                    'label' => 'agent name',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select agent name'
                    ],
                ];
            }

            if($data['bussiness_type'] == 'B2C'){
                $segmentsinfo_validation['customer_info'] = [
                    'label' => 'customer info',
                    'rules' => 'trim|required',
                    'errors' => [
                        'required' => 'Please select customer name'
                    ],
                ];
            }
            
            
            $segmentsinfo_validation["supplier"] = [

                'label' => 'Supplier',

                'rules' => 'trim|required',

                'errors' => [

                    'required' => 'Please select supplier',

                ],

            ];

            $segmentsinfo_validation["cabin_class"] = [

                'label' => 'Cabin Class',

                'rules' => 'trim|required',

                'errors' => [

                    'required' => 'Please select cabin class',

                ],

            ];

            $segmentsinfo_validation["is_refundable"] = [

                'label' => 'Refundable Type',

                'rules' => 'trim|required',

                'errors' => [

                    'required' => 'Please select refundable type',

                ],

            ];

        }

        foreach ($data['segmentinfo'] as $key => $requestParameter) {

            foreach ($requestParameter as $key1 => $requestParameter1) {
                $segmentsinfo_validation["segmentinfo.$key.$key1.airline_pnr"] = [
                    'label' => 'Airline Pnr',
                    'rules' => 'trim|permit_empty|min_length[6]|max_length[10]',
                    'errors' => [
                        'exact_length' => 'Please enter 6 digit airline pnr',
                        'min_length' => 'Please enter  min  6 digit long pnr',
                            'max_length' => 'Please enter  max  10 digit long pnr',
                    ],
                ];
                    $segmentsinfo_validation["segmentinfo.$key.$key1.origin_airport_code"] = [

                        'label' => 'Origin Airport',

                        'rules' => 'trim|required|exact_length[3]',

                        'errors' => [

                            'required' => 'Please select origin airport',

                            'exact_length' => 'Please select valid origin airport',

                        ],

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.destination_airport_code"] = [

                        'label' => 'Destination Airport',

                        'rules' => 'trim|required|exact_length[3]',

                        'errors' => [

                            'required' => 'Please select destination airport',

                            'exact_length' => 'Please select valid origin airport',

                        ],

                    ];
                    $segmentsinfo_validation["segmentinfo.$key.$key1.depart_date"] = [
                        'label' => 'Depart Date',
                        'rules' => 'trim|required|valid_date[d-m-yy]',
                        'errors' => [
                            'required' => 'Please enter depart  dat',
                            'valid_date' => 'Please select valid depart  date'
                        ],
                    ];
                    $segmentsinfo_validation["segmentinfo.$key.$key1.depart_time"] = [
                        'label' => 'Depart Time',
                        'rules' => 'trim|required|valid_date[H:i]',
                        'errors' => [
                            'required' => 'Please enter depart  time',
                            'valid_date' => 'Please select valid depart  time'
                        ],
                    ];
                    $segmentsinfo_validation["segmentinfo.$key.$key1.arrival_date"] = [
                        'label' => 'Arrival Date',
                        'rules' => 'trim|required|valid_date[d-m-yy]',
                        'errors' => [
                            'required' => 'Please enter arrival  date',
                            'valid_date' => 'Please select valid arrival  date'

                        ],
                    ];
                    $segmentsinfo_validation["segmentinfo.$key.$key1.arrival_time"] = [
                        'label' => 'Arrival Time',
                        'rules' => 'trim|required|valid_date[H:i]',
                        'errors' => [
                            'required' => 'Please enter arrival  time',
                            'valid_date' => 'Please select valid arrival   time'

                        ],
                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.airline_code"] = [

                        'label' => 'Airline Code',

                        'rules' => 'trim|required',

                        'errors' => [

                            'required' => 'Please enter airline code'

                        ],

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.flight_number"] = [

                        'label' => 'Flight Number',

                        'rules' => 'trim|required',

                        'errors' => [

                            'required' => 'Please enter flight number'

                        ],

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.origin_terminal"] = [

                        'label' => 'Origin Terminal',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.destination_terminal"] = [

                        'label' => 'Destination Terminal',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.craft"] = [

                        'label' => 'Craft',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.fare_class"] = [

                        'label' => 'Fare Class',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.fare_basis"] = [

                        'label' => 'Fare Basis',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.baggage"] = [

                        'label' => 'Baggage',

                        'rules' => 'trim'

                    ];

                    $segmentsinfo_validation["segmentinfo.$key.$key1.cabin_baggage"] = [

                        'label' => 'Cabin Baggage',

                        'rules' => 'trim'

                    ];

    }

}

return $segmentsinfo_validation;

}

}