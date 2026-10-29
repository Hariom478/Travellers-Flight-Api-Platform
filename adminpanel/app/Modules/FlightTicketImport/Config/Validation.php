<?php

namespace Modules\FlightTicketImport\Config;


class Validation
{
    public function pax_validation($data)

    {
        $booking_validation = [];
        foreach ($data['pax'] as $key => $requestParameter) {
                    $booking_validation["pax.$key.Title"] = [
                        'label' => 'title',
                        'rules' => 'trim|required|in_list[Mr,Ms,Mrs,Miss,Mstr]',
                        'errors' => [
                            'required' => 'Please select title'
                        ],
                    ];
                    $booking_validation["pax.$key.FirstName"] = [
                        'label' => 'first name',
                        'rules' => 'trim|required|alpha_space',
                        'errors' => [
                            'required' => 'Please enter first name',
                            'alpha_space' => 'Please enter valid first name'
                        ],
                    ];
                    $booking_validation["pax.$key.LastName"] = [
                        'label' => 'last name',
                        'rules' => 'trim|required|alpha_space',
                        'errors' => [
                            'required' => 'Please enter last name',
                            'alpha_space' => 'Please enter valid last name'
                        ],
                    ];
                    $booking_validation["pax.$key.PaxType"] = [
                        'label' => 'pax type',
                        'rules' => 'trim|required|in_list[Adult,Child,Infant]',
                        'errors' => [
                            'required' => 'Please select pax type',
                            'in_list' => 'Please select valid pax type'
                        ],
                    ];
                
                     /*  $booking_validation["pax.$key.Gender"] = [
                        'label' => 'Gendar',
                        'rules' => 'trim|required|in_list[Male,Female]',
                        'errors' => [
                            'required' => 'Please select gendar'
                        ],
                    ]; */
                    if ($requestParameter['PaxType'] == "Child"|| $requestParameter['PaxType'] == "Infant") {
                        $booking_validation["pax.$key.DateOfBirth"] = [
                            'label' => 'dob',
                            'rules' => 'trim|permit_empty|valid_date[d M yy]',
                            'errors' => [
                                'required' => 'Please enter dob',
                                'valid_date' => 'Please select valid dob'
                            ],
                        ];
                    }
                    else{
                        $booking_validation["pax.$key.DateOfBirth"] = [
                            'label' => 'dob',
                            'rules' => 'trim|permit_empty|valid_date[d M yy]',
                            'errors' => [
                                'valid_date' => 'Please select valid dob'
                            ],
                        ];
                    }
                        $booking_validation["pax.$key.PAN"] = [
                            'label' => 'pan card number',
                            'rules' => 'trim|permit_empty|regex_match[/[A-Z]{5}[0-9]{4}[A-Z]{1}/]',
                            'errors' => [
                                'required' => 'Please enter pan card number',
                                'regex_match' => 'Please enter valid pan card number'
                            ],
                        ];
                        $booking_validation["pax.$key.Nationality"] = [
                            'label' => 'passport nationality',
                            'rules' => 'trim|permit_empty|exact_length[2]',
                            'errors' => [
                                'required' => 'Please select passport nationality',
                                'exact_length' => 'Please select valid passport nationality',
                            ],
                        ];
                        $booking_validation["pax.$key.PassportNo"] = [
                            'label' => 'passport number',
                            'rules' => 'trim|permit_empty',
                            'errors' => [
                                'required' => 'Please enter passport number',
                                'regex_match' => 'Please enter valid passport number',
                            ],
                        ];
                        $booking_validation["pax.$key.PassportIssue"] = [
                            'label' => 'passport issue date',
                            'rules' => 'trim|permit_empty|valid_date[d M yy]',
                            'errors' => [
                                'required' => 'Please enter passport issue date',
                                'valid_date' => 'Please select valid passport issue date'
                            ],
                        ];
                        $booking_validation["pax.$key.PassportExpiry"] = [
                            'label' => 'passport expire date',
                            'rules' => 'trim|permit_empty|valid_date[d M yy]',
                            'errors' => [
                                'required' => 'Please enter passport expire date',
                                'valid_date' => 'Please select valid passport expire date'
                            ],
                        ];
                   $booking_validation["pax.$key.PNR"] = [
                        'label' => 'Pnr',
                        'rules' => 'trim|required|alpha_numeric|exact_length[6]',
                        'errors' => [
                            'required' => 'Please enter pnr',
                            'alpha_numeric' => 'Pnr contain only alpha numeric character',
                            'exact_length' => 'Please enter  exact  six digit long pnr',
                        ],
                    ];

                   $booking_validation["pax.$key.TicketNumber"] = [
                        'label' => 'Ticket Number',
                        'rules' => 'trim|required|alpha_numeric',
                        'errors' => [
                            'required' => 'Please enter Ticket Number',
                            'alpha_numeric' => 'Ticket number contain only alpha numeric character',
                        ],
                    ];
                   $booking_validation["pax.$key.TicketId"] = [
                        'label' => 'Ticket Number',
                        'rules' => 'trim|permit_empty|alpha_numeric',
                        'errors' => [
                            'required' => 'Please enter Ticket Id',
                            'alpha_numeric' => 'Ticket Id contain only alpha numeric character',
                        ],
                    ];
                    /* fare validation */
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
    function checkPNR($data) {
        
    $checkPNR ['tts_web_partner_info_id']  =  [
        'label' => 'Web Partner',
        'rules' => "trim|required",
        'errors' => [
            'required' => 'Please select Web Partner'

        ],
    ];
    $checkPNR ['supplier']  =  [
        'label' => 'Supplier',
        'rules' => "trim|required",
        'errors' => [
            'required' => 'Please select Api Supplier'
        ],
    ];
    $checkPNR ['pnr']  =  [
        'label' => 'PNR',
        'rules' => "trim|required|exact_length[6]",
        'errors' => [
            'required' => 'Please enter PNR',
            'exact_length' => 'Please enter exact 6 digit  PNR',
        ],
    ];
    if( $data ['supplier']=="TBO"){
    $checkPNR ['last_name']  =  [
        'label' => 'Last Name',
        'rules' => "trim|required",
        'errors' => [
            'required' => 'Please enter last name'
        ],
    ];
}
   return $checkPNR;
    }
    public function segmentsinfo_validation($data)

    {
        $segmentsinfo_validation = [];
        $segmentsinfo_validation["ticket_type"] = [
            'label' => 'Ticket Type',
            'rules' => 'trim|required|in_list[ImportPNR]',
            'errors' => [
                'required' => 'Please select ticket type',
                'in_list' => 'Please select ticket type',
            ],
        ];
        if($data['ticket_type']=="ImportPNR"){
            $segmentsinfo_validation["supplier"] = [
                'label' => 'Supplier',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select supplier',
                ],
            ];
            $segmentsinfo_validation["webpartner_info"] = [
                'label' => 'Web Partner',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select web partner',
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
                        'rules' => 'trim|permit_empty|exact_length[6]',
                        'errors' => [
                            'exact_length' => 'Please enter 6 digit airline pnr',

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

