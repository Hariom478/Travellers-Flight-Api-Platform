<?php

namespace Modules\Airservice\Config;

use CodeIgniter\I18n\Time;

class Validation
{

    public function search_validation($input)
    {

        $search_validation = [
            'UserIp' => ['rules' => 'trim|required|valid_ip'],
            'Adult' => ['rules' => 'trim|required|is_natural_no_zero|less_than[10]'],
            'Child' => ['rules' => 'trim|required|numeric|less_than[10]'],
            'Infant' => ['rules' => 'trim|required|numeric|less_than[10]'],
            'DirectFlight' => ['rules' => 'trim'],
            'JourneyType' => ['rules' => 'trim|required|numeric|in_list[1,2,3]'],
            'PreferredCarriers' => [],
            'CabinClass' => ['rules' => 'trim|required|numeric|in_list[1,2,3,4,5,6]'],
            'Supplier' => ['rules' => 'trim|permit_empty|in_list[TBO,TRAVELPORT,INDIGO]'],
            'AirSegments.*.Origin' => [
                'rules' => 'trim|required|alpha|exact_length[3]',
                'errors' => [
                    'required' => 'Origin field is required.',
                    'alpha' => 'Origin field may only contain alphabetical characters.',
                    'exact_length' => 'Origin field must be exactly 3 characters in length.',
                ]
            ],
            'AirSegments.*.Destination' => [
                'rules' => 'trim|required|alpha|exact_length[3]|differs[AirSegments.*.Origin]',
                'errors' => [
                    'required' => 'Destination field is required.',
                    'alpha' => 'Destination field may only contain alphabetical characters.',
                    'exact_length' => 'Destination field must be exactly 3 characters in length.',
                    'differs' => "The 'Origin' and 'Destination' cannot be same. Please re-type"
                ]
            ],
            'AirSegments.*.PreferredTime' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'PreferredTime field is required.',
                ]
            ],
            'Sources' => ['rules' => 'trim'],
        ];

        $no_adult = $input['Adult'];
        $no_child = $input['Child'];
        $no_infants = $input['Infant'];
        $totaltraveller = $no_adult + $no_child + $no_infants;
        if ($totaltraveller < 1) {
            $message = "At least 1 Traveller required";
            api_custom_message(400, $message, false);
        }
        if ($totaltraveller > 9) {
            $message = "Currently, bookings can only be made for upto 9 travelers.";
            api_custom_message(400, $message, false);
        }
        if ($no_adult < $no_infants) {
            $message = "Number of Infant can not be exceed the number of Adults.";
            api_custom_message(400, $message, false);
        }

        $no_segments = count($input['AirSegments']);
        if ($input['JourneyType'] == 1 && $no_segments !== 1) {
            $message = "Invalid Segment length.";
            api_custom_message(400, $message, false);
        } else if ($input['JourneyType'] == 2 && $no_segments !== 2) {
            $message = "Invalid Segment length.";
            api_custom_message(400, $message, false);
        }

        return $search_validation;
    }

    public $calendar_fare_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        // Required: TBOFlight::GetCalendarFare() multiplies the per-pax fare by
        // Adult+Child+Infant to get the total fare shown (TBOFlight.php:379,495).
        'Adult' => ['rules' => 'trim|required|is_natural_no_zero|less_than[10]'],
        'Child' => ['rules' => 'trim|required|numeric|less_than[10]'],
        'Infant' => ['rules' => 'trim|required|numeric|less_than[10]'],
        'JourneyType' => ['rules' => 'trim|required|numeric|in_list[1]'],
        'PreferredCarriers' => [],
        'CabinClass' => ['rules' => 'trim|required|numeric|in_list[1,2,3,4,5,6]'],
        'AirSegments.*.Origin' => [
            'rules' => 'trim|required|alpha|exact_length[3]',
            'errors' => [
                'required' => 'Origin field is required.',
                'alpha' => 'Origin field may only contain alphabetical characters.',
                'exact_length' => 'Origin field must be exactly 3 characters in length.',
            ]
        ],
        'AirSegments.*.Destination' => [
            'rules' => 'trim|required|alpha|exact_length[3]|differs[AirSegments.*.Origin]',
            'errors' => [
                'required' => 'Destination field is required.',
                'alpha' => 'Destination field may only contain alphabetical characters.',
                'exact_length' => 'Destination field must be exactly 3 characters in length.',
                'differs' => "The 'Origin' and 'Destination' cannot be same. Please re-type"
            ]
        ],
        'AirSegments.*.PreferredTime' => [
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'PreferredTime field is required.',
            ]
        ],
        'Sources' => ['rules' => 'trim'],
    ];


    public $farerule_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];

    public $fareconfirmation_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];


    public $ssr_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];

    public $book_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];
    public $ticket_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'PNR' => ['rules' => 'trim|required'],
        'ResultIndex' => ['rules' => 'trim|required'],
        'SearchTokenId' => ['rules' => 'trim|required'],
        'BookingId' => ['rules' => 'trim|required|numeric'],
    ];


    public function dob_validate($AirSegments, $dob, $paxtype)
    {
        if ($paxtype != 3) {
            $depart_date = $AirSegments[0]['PreferredTime'];
        } else {
            $lastsegment = end($AirSegments);
            $depart_date = $lastsegment['PreferredTime'];
        }

        $GetTimeZone = app_timezone();
        $current = Time::parse($depart_date, $GetTimeZone);
        $second = Time::parse($dob, $GetTimeZone);
        $diff = $current->difference($second);
        $days = abs($diff->days);
        if ($paxtype == 1) {
            // 4383 days means 12 years
            if ($days >= 4383) {

            } else {
                $message = "Adult age Should be greater than equals to 12 years.";
                api_custom_message(108, $message, false);
            }
        }
        if ($paxtype == 2) {
            // 730.5 days means 2 year and 4383 days means 12 years
            if ($days >= 730.5 && $days < 4383) {

            } else {
                $message = "Child age Should be greater than equals to 2 years OR less than 12 years.";
                api_custom_message(108, $message, false);
            }
        }
        if ($paxtype == 3) {
            // 730.5 days means 2 year and 4383 days means 12 years
            if ($days < 730.5) {

            } else {
                $message = "Valid Infant age should be under 2 years till the time of return flight last segment departure date";
                api_custom_message(108, $message, false);
            }
        }
    }

    public function passport_validation($AirSegments, $passportexpiry, $paxkey)
    {
        $depart_date = $AirSegments[0]['PreferredTime'];
        if (strtotime($passportexpiry) > strtotime($depart_date)) {

        } else {
            $message = "Passport for passenger $paxkey will be expired before travel date.";
            api_custom_message(108, $message, false);
        }
    }

    public function book_pax_validation($input, $data, $search_request_array)
    {

        if ($data['IsPanRequiredAtBook']) {
            $IsPanRequired = 'required';
        } else {
            $IsPanRequired = 'permit_empty';
        }
        if ($data['IsPassportRequiredAtBook']) {
            $IsPassportRequired = 'required';
        } else {
            $IsPassportRequired = 'permit_empty';
        }
        if ($data['IsGSTMandatory']) {
            $IsGSTMandatory = 'required';
        } else {
            $IsGSTMandatory = 'permit_empty';
        }
        if ($data['GSTAllowed'] == false) {
            if ($input['Passengers'][0]['GSTNumber']) {
                $message = "GST not allowed for this booking";
                api_custom_message(108, $message, false);
            }
        }
        $IsDOBRequired = '';
        if ($input['Passengers']) {
            $noadt = 0;
            $nochd = 0;
            $noinf = 0;
            $paxcount = 0;
            $pax_validation = array();
            foreach ($input['Passengers'] as $key => $Passenger) {
                $paxcount = $key + 1;

                if ($Passenger['PaxType'] == 1) {
                    $noadt++;
                    $IsDOBRequired = 'permit_empty';
                }
                if ($Passenger['PaxType'] == 2) {
                    $nochd++;
                    $IsDOBRequired = 'required';
                }
                if ($Passenger['PaxType'] == 3) {
                    $noinf++;
                    $IsDOBRequired = 'required';
                }

                if (isset ($Passenger['DateOfBirth']) && $Passenger['DateOfBirth']) {
                    Validation::dob_validate($search_request_array['AirSegments'], $Passenger['DateOfBirth'], $Passenger['PaxType']);
                }

                if (isset ($Passenger['PassportExpiry']) && $Passenger['PassportExpiry']) {
                    $IsDOBRequired = 'required';
                    Validation::passport_validation($search_request_array['AirSegments'], $Passenger['PassportExpiry'], $paxcount);
                }

                if (isset ($Passenger['IsLeadPax'])) {
                    if ($key == 0) {
                        if ($Passenger['IsLeadPax'] != true) {
                            $message = "First Passenger is a lead passenger. lead passenger value is true.";
                            api_custom_message(400, $message, false);
                        }
                    } else {
                        if ($Passenger['IsLeadPax'] != false) {
                            $message = "Second passenger is not a lead passenger.";
                            api_custom_message(400, $message, false);
                        }
                    }
                } else {
                    $message = "IsLeadPax field is Required";
                    api_custom_message(400, $message, false);
                }


                $pax_validation["Passengers.$key.Title"] = [
                    'rules' => 'trim|required|in_list[Mr,Mrs,Miss,Mstr,Ms]',
                    'errors' => [
                        'required' => 'Title field is required.',
                    ],
                ];

                $pax_validation["Passengers.$key.FirstName"] = [
                    'rules' => 'trim|required|alpha_space',
                    'errors' => [
                        'required' => 'FirstName field is required.',
                        'alpha_space' => 'FirstName field may only contain alphabetical characters.',
                    ],
                ];


                $pax_validation["Passengers.$key.LastName"] = [
                    'rules' => 'trim|required|alpha_space|min_length[2]',
                    'errors' => [
                        'required' => 'LastName field is required.',
                        'alpha_space' => 'LastName field may only contain alphabetical characters.',
                        'min_length' => 'Last name should minimum two character long',
                    ],
                ];

                $pax_validation["Passengers.$key.PaxType"] = [
                    'rules' => 'trim|required|in_list[1,2,3]',
                    'errors' => [
                        'required' => 'PaxType field is required.',
                        'in_list' => 'PaxType value only in list[1,2,3].',
                    ],
                ];
                $pax_validation["Passengers.$key.DateOfBirth"] = [
                    'rules' => 'trim|' . $IsDOBRequired . '|valid_date[Y-m-d\T00:00:00]',
                    'errors' => [
                        'required' => "Passenger $paxcount DateOfBirth field is required.",
                        'valid_date' => "Passenger $paxcount DateOfBirth format invalid."
                    ],
                ];
                $pax_validation["Passengers.$key.Gender"] = [
                    'rules' => 'trim|required|in_list[1,2]',
                    'errors' => [
                        'required' => 'Gender field is required.',
                        'in_list' => 'Gender value only in list[1,2].',
                    ],
                ];

                $pax_validation["Passengers.$key.FFAirline"] = [
                    'rules' => 'trim|permit_empty|exact_length[2]|required_with[Passengers.' . $key . '.FFNumber]',
                    'errors' => [
                        'required_with' => 'FFAirline field is required.',
                        'exact_length' => 'FFAirline is exact 2 characters.'
                    ],
                ];
                $pax_validation["Passengers.$key.FFNumber"] = [
                    'rules' => 'trim|permit_empty|max_length[4]|required_with[Passengers.' . $key . '.FFAirline]',
                    'errors' => [
                        'required_with' => 'FFNumber field is required.',
                        'max_length' => 'Please enter valid FFNumber.'
                    ],
                ];

                $pax_validation["Passengers.$key.PAN"] = [
                    'rules' => 'trim|' . $IsPanRequired . '|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]',
                    'errors' => [
                        'required' => 'PAN field is required.',
                        'regex_match' => 'Please enter valid PAN.'
                    ],
                ];
                $pax_validation["Passengers.$key.Nationality"] = [
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[Passengers.' . $key . '.PassportNo]|exact_length[2]',
                    'errors' => [
                        'required_with' => 'Nationality field is required.',
                        'required' => 'Nationality field is required.',
                        'exact_length' => 'Please enter valid Nationality.'
                    ],
                ];
                $pax_validation["Passengers.$key.PassportNo"] = [
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[Passengers.' . $key . '.PassportExpiry]|regex_match[[A-Z]{1}[0-9]{7}]',
                    'errors' => [
                        'required_with' => 'PassportNo field is required.',
                        'required' => 'PassportNo field is required.',
                        'regex_match' => 'Please enter valid PassportNo.'
                    ],
                ];

                $pax_validation["Passengers.$key.PassportExpiry"] = [
                    'rules' => 'trim|' . $IsPassportRequired . '|required_with[Passengers.' . $key . '.PassportNo]|valid_date[Y-m-d\T00:00:00]',
                    'errors' => [
                        'required_with' => 'PassportExpiry field is required.',
                        'required' => 'PassportExpiry field is required.',
                        'valid_date' => 'PassportExpiry date format invalid.'
                    ],
                ];

                if ($key == 0) {

                    $pax_validation["Passengers.$key.AddressLine1"] = [
                        'rules' => 'trim|required|max_length[30]',
                        'errors' => [
                            'required' => 'AddressLine1 field is required.',
                            'max_length' => 'AddressLine1 is max length is 30 characters.'
                        ],
                    ];
                    $pax_validation["Passengers.$key.AddressLine2"] = [
                        'rules' => 'trim|max_length[30]',
                        'errors' => [
                            'max_length' => 'AddressLine2 is max length is 30 characters.'
                        ],
                    ];
                    $pax_validation["Passengers.$key.City"] = [
                        'rules' => 'trim|required|alpha_numeric_space',
                        'errors' => [
                            'required' => 'City field is required.',
                            'alpha_numeric_space' => 'Please enter valid City.',
                        ],
                    ];
                    $pax_validation["Passengers.$key.CountryCode"] = [
                        'rules' => 'trim|required|alpha|max_length[2]',
                        'errors' => [
                            'required' => 'CountryCode field is required.',
                            'alpha' => 'Please enter valid CountryCode.',
                            'max_length' => 'Please enter valid CountryCode.'
                        ],
                    ];
                    $pax_validation["Passengers.$key.CountryName"] = [
                        'rules' => 'trim|required|alpha_space|max_length[60]',
                        'errors' => [
                            'required' => 'CountryName field is required.',
                            'alpha_space' => 'Please enter valid CountryName.',
                            'max_length' => 'Please enter valid CountryName.',
                        ],
                    ];
                    $pax_validation["Passengers.$key.ContactNo"] = [
                        'rules' => 'trim|required|numeric|min_length[7]|max_length[15]',
                        'errors' => [
                            'required' => 'ContactNo field is required.',
                            'numeric' => 'ContactNo must be numeric.',
                            'min_length' => 'Please enter valid ContactNo.',
                            'max_length' => 'Please enter valid ContactNo.'
                        ],
                    ];
                    $pax_validation["Passengers.$key.Email"] = [
                        'rules' => 'trim|required|valid_email',
                        'errors' => [
                            'required' => 'Email field is required.',
                            'valid_email' => 'Please enter valid Email.',
                        ],
                    ];

                    $pax_validation["Passengers.$key.GSTCompanyAddress"] = [
                        'rules' => 'trim|' . $IsGSTMandatory . '|required_with[Passengers.' . $key . '.GSTNumber]',
                        'errors' => [
                            'required_with' => 'GSTCompanyAddress field is required.',
                            'required' => 'GSTCompanyAddress field is required.',
                        ],
                    ];
                    $pax_validation["Passengers.$key.GSTCompanyContactNumber"] = [
                        'rules' => 'trim|' . $IsGSTMandatory . '|numeric|min_length[7]|max_length[15]|required_with[Passengers.' . $key . '.GSTNumber]',
                        'errors' => [
                            'required_with' => 'GSTCompanyContactNumber field is required.',
                            'required' => 'GSTCompanyContactNumber field is required.',
                            'numeric' => 'GSTCompanyContactNumber must be numeric.',
                            'min_length' => 'Please enter valid GSTCompanyContactNumber.',
                            'max_length' => 'Please enter valid GSTCompanyContactNumber.',
                        ],
                    ];
                    $pax_validation["Passengers.$key.GSTCompanyName"] = [
                        'rules' => 'trim|' . $IsGSTMandatory . '|required_with[Passengers.' . $key . '.GSTNumber]',
                        'errors' => [
                            'required' => 'GSTCompanyName field is required.',
                            'required_with' => 'GSTCompanyName field is required.',
                        ],
                    ];
                    $pax_validation["Passengers.$key.GSTNumber"] = [
                        'rules' => 'trim|' . $IsGSTMandatory . '|regex_match[/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/]',
                        'errors' => [
                            'required' => 'GSTNumber field is required.',
                            'regex_match' => 'Please enter valid GST number.'
                        ],
                    ];
                    $pax_validation["Passengers.$key.GSTCompanyEmail"] = [
                        'rules' => 'trim|' . $IsGSTMandatory . '|valid_email|required_with[Passengers.' . $key . '.GSTNumber]',
                        'errors' => [
                            'required' => 'GSTCompanyEmail field is required.',
                            'valid_email' => 'Please enter valid GSTCompanyEmail.',
                            'required_with' => 'GSTCompanyEmail field is required.',
                        ],
                    ];
                }

            }
        }
        if (intval($search_request_array['Adult']) != $noadt) {
            $message = "Invalid passenger count";
            api_custom_message(108, $message, false);
        }
        if (intval($search_request_array['Child']) != $nochd) {
            $message = "Invalid passenger count";
            api_custom_message(108, $message, false);
        }
        if (intval($search_request_array['Infant']) != $noinf) {
            $message = "Invalid passenger count";
            api_custom_message(108, $message, false);
        }
        return $pax_validation;
    }


    public $getbookingdetail_validation = [
        'UserIp' => ['rules' => 'trim|required|valid_ip'],
        'BookingId' => ['rules' => 'trim|required'],
        'PNR' => ['rules' => 'trim'],
        'SearchTokenId' => ['rules' => 'trim|required']
    ];

    public function cancel_charge_validation($input)
    {
        $cancel_charge_validation = [
            'UserIp' => ['rules' => 'trim|required|valid_ip'],
            'BookingId' => ['rules' => 'trim|required'],
            'SearchTokenId' => ['rules' => 'trim|required'],
            'RequestType' => ['rules' => 'trim|required|in_list[FullCancellation,PartialCancellation]'],

        ];
        return $cancel_charge_validation;
    }
    public function cancel_status_validation($input)
    {
        $cancel_status_validation = [
            'UserIp' => ['rules' => 'trim|required|valid_ip'],
            'BookingId' => ['rules' => 'trim|required'],
            'SearchTokenId' => ['rules' => 'trim|required'],
            'PaxId.*' => [
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'PaxId field is required.'
                ]
            ],
        ];
        return $cancel_status_validation;
    }
    public function cancel_validation($input)
    {
        $cancel_validation = [
            'UserIp' => ['rules' => 'trim|required|valid_ip'],
            'BookingId' => ['rules' => 'trim|required'],
            'SearchTokenId' => ['rules' => 'trim|required'],
            'RequestType' => ['rules' => 'trim|required|in_list[FullCancellation,PartialCancellation]'],
            'Remark' => ['rules' => 'trim|required'],
        ];


        if ($input['RequestType'] == "PartialCancellation") {

            if (isset ($input['Sectors'])) {
                $cancel_validation['Sectors.*.Origin'] = array(
                    'rules' => 'trim|required|alpha|exact_length[3]',
                    'errors' => [
                        'required' => 'Origin field is required.',
                        'alpha' => 'Origin field may only contain alphabetical characters.',
                        'exact_length' => 'Origin field must be exactly 3 characters in length.',
                    ],
                );

                $cancel_validation['Sectors.*.Destination'] = array(
                    'rules' => 'trim|required|alpha|exact_length[3]',
                    'errors' => [
                        'required' => 'Destination field is required.',
                        'alpha' => 'Destination field may only contain alphabetical characters.',
                        'exact_length' => 'Destination field must be exactly 3 characters in length.',
                    ],
                );

            } else {
                $message = "Partial Cancellation is Required for Sectors Information.";
                api_custom_message(400, $message, false);
            }

            if (isset ($input['PaxId'])) {
                $cancel_validation['PaxId.*'] = array(
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'PaxId field is required.'
                    ],
                );

            } else {
                $message = "Partial Cancellation is Required for PaxId.";
                api_custom_message(400, $message, false);
            }
        }

        if ($input['RequestType'] == "FullCancellation") {
            if (isset ($input['Sectors'])) {
                $message = "Pax IDs / Sectors can be sent in case of partial cancellation only.";
                api_custom_message(400, $message, false);
            }
            if (isset ($input['PaxId'])) {
                $message = "pax IDs / Sectors can be sent in case of partial cancellation only.";
                api_custom_message(400, $message, false);
            }

        }

        return $cancel_validation;
    }

    public $generate_html_ticket_invoice = [
        'HtmlType' => ['rules' => 'trim|required|in_list[Ticket,AgencyInvoice,CustomerInvoice,CreditNote]'],
        'SearchTokenId' => ['rules' => 'trim'],
        'BookingId.*' => ['rules' => 'trim|required'],
        'UserType' => ['rules' => 'trim|required|in_list[WebPartner,Admin,wl-agent,wl-customer,wl-agent-customer]'],
        'WithPrice' => ['rules' => 'trim|required|in_list[1,0]'],
        'WithAgencyDetail' => ['rules' => 'trim|required|in_list[1,0]'],
        'TicketInvoiceJourney' => ['rules' => 'trim|required|in_list[Onward,Return,Both]'],
        'ViewService' => ['rules' => 'trim|required|in_list[Email,View,Pdf]'],
        'ViewSize' => ['rules' => 'trim'],
        /* 'RequestBy' => ['rules' => 'trim|in_list[WebPartner,Agent]'], */
    ];

    public function amendment_validation($input)
    {
        $amendment_validation = [
            'BookingId' => ['rules' => 'trim|required|numeric'],
            'Type' => ['rules' => 'trim|required|in_list[cancellation,full_refund,reissue,correction,reissue_quotation,cancellation_quotation,reissue,no_show]'],
            'Remarks' => ['rules' => 'trim|required'],
            'RequesterInfo.Requester' => ['rules' => 'trim|required|in_list[WebPartner,SuperAdmin,Whitelabel,WhitelabelB2C]']
        ];
        /* if ($input['Type'] == "cancellation") {
            $amendment_validation['AmendmentStatus'] = ['rules' => 'trim|required|in_list[approved,rejected]'];
        } */
        if ($input['RequesterInfo']['Requester'] == "SuperAdmin") {
            $amendment_validation['AmendmentStatus'] = ['rules' => 'trim|required|in_list[approved,rejected]'];
            $amendment_validation['AmendmentId'] = ['rules' => 'trim|required'];
        }
        if ($input['RequesterInfo']['Requester'] == "WebPartner" || $input['RequesterInfo']['Requester'] == "Whitelabel" || $input['RequesterInfo']['Requester'] == "WhitelabelB2C") {
            $amendment_validation['RequesterInfo.RequesterId'] = ['rules' => 'trim|required'];
        }
        $amendment_validation['Sectors.*.Origin'] = array(
            'rules' => 'trim|required|alpha|exact_length[3]',
            'errors' => [
                'required' => 'Origin field is required.',
                'alpha' => 'Origin field may only contain alphabetical characters.',
                'exact_length' => 'Origin field must be exactly 3 characters in length.',
            ],
        );

        $amendment_validation['Sectors.*.Destination'] = array(
            'rules' => 'trim|required|alpha|exact_length[3]',
            'errors' => [
                'required' => 'Destination field is required.',
                'alpha' => 'Destination field may only contain alphabetical characters.',
                'exact_length' => 'Destination field must be exactly 3 characters in length.',
            ],
        );
        $amendment_validation['PaxId.*'] = array(
            'rules' => 'required',
            'errors' => [
                'required' => 'PaxId field is required.'
            ],
        );
        return $amendment_validation;
    }
}
