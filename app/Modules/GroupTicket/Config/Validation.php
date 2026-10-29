<?php

namespace Modules\GroupTicket\Config;

use PHPUnit\Runner\DefaultTestResultCache;

class Validation
{

    public function pax_validation($data)
    {
        $booking_validation = [];

        $booking_validation['user_type'] = [
            'label' => 'User Type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter User Type',
            ],
        ];

        $booking_validation['flight_type'] = [
            'label' => 'Flight Type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Flight Type',
            ],
        ];

        $booking_validation['journey_type'] = [
            'label' => 'Journey Type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Journey Type',
            ],
        ];

        $booking_validation['origin_airport_code'] = [
            'label' => 'Origin',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Origin',
            ],
        ];

        $booking_validation['destination_airport_code'] = [
            'label' => 'Destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Destination',
            ],
        ];

        $booking_validation['depart_date'] = [
            'label' => 'Departure Date',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Departure Date',
            ],
        ];

        $booking_validation['cabin_class'] = [
            'label' => 'Cabin Class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Cabin Class',
            ],
        ];

        $booking_validation['onward_airline_prefrence'] = [
            'label' => 'Airline Preference',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Airline Preference',
            ],
        ];

        $booking_validation['origin_preference_flight_number'] = [
            'label' => 'Flight Number',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Flight Number',
            ],
        ];

        if ($data['journey_type'] == "roundtrip") {
            $booking_validation['return_date'] = [
                'label' => 'Return Date',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter Return Date'
                ],
            ];
            $booking_validation['destination_airline_prefrence'] = [
                'label' => 'Return Airline Preference',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter Return Airline Preference'
                ],
            ];
            $booking_validation['destination_preference_flight_number'] = [
                'label' => 'Return Flight Number',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter Return Flight Number'
                ],
            ];
        }

        $booking_validation['contact_email'] = [
            'label' => 'email',
            'rules' => 'trim|required|valid_email',
            'errors' => [
                'required' => 'Please enter  email',
                'valid_email' => 'Please enter valid email'
            ],
        ];
        $booking_validation['contact_number'] = [
            'label' => 'mobile number',
            'rules' => 'trim|required|numeric|min_length[10]|max_length[10]',
            'errors' => [
                'numeric' => 'Please enter valid mobile number',
                'required' => 'Please enter mobile number'
            ],
        ];

        $booking_validation['address'] = [
            'label' => 'Address',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Address'
            ],
        ];
        $booking_validation['city'] = [
            'label' => 'City',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter City'
            ],
        ];

        $booking_validation['state'] = [
            'label' => 'State',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter State'
            ],
        ];

        $booking_validation['country'] = [
            'label' => 'Country',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Country'
            ],
        ];

        $booking_validation['pincode'] = [
            'label' => 'Pin Code',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Pin Code'
            ],
        ];

        if (isset($data['pax'])) {
            foreach ($data['pax'] as $key => $pax) {
                foreach($pax as $key2 => $val){
                    $booking_validation["pax.$key.$key2.title"] = [
                        'label' => 'Title',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please select Title'
                        ],
                    ];
                    $booking_validation["pax.$key.$key2.first_name"] = [
                        'label' => 'First Name',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter First Name'
                        ],
                    ];
                    $booking_validation["pax.$key.$key2.last_name"] = [
                        'label' => 'Last Name',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter Last Name'
                        ],
                    ];
                    $booking_validation["pax.$key.$key2.dob"] = [
                        'label' => 'Date of Birth',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please select DOB'
                        ],
                    ];
                    if($data['flight_type'] == "international"){
                        $booking_validation["pax.$key.$key2.country"] = [
                            'label' => 'Country',
                            'rules' => 'trim|required',
                            'errors' => [
                                'required' => 'Please select Country'
                            ],
                        ];
                        $booking_validation["pax.$key.$key2.passport_no"] = [
                            'label' => 'Passport Number',
                            'rules' => 'trim|required',
                            'errors' => [
                                'required' => 'Please select Passport Number'
                            ],
                        ];
                        $booking_validation["pax.$key.$key2.issue_date"] = [
                            'label' => 'Issue Date',
                            'rules' => 'trim|required',
                            'errors' => [
                                'required' => 'Please select Issue Date'
                            ],
                        ];
                        $booking_validation["pax.$key.$key2.expiry_date"] = [
                            'label' => 'Expiry Date',
                            'rules' => 'trim|required',
                            'errors' => [
                                'required' => 'Please select Expiry Date'
                            ],
                        ];
                    }
                }
            }
        }

        return $booking_validation;
    }
}
