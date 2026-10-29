<?php

namespace Modules\Holiday\Config;

class Validation
{
    public function voucher_update_validation($data)
    {
        $booking_validation["confirmation_number"] = [
            'label' => 'Confirmation Number',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter confirmation number'
            ],
        ];
        $booking_validation["booking_status"] = [
            'label' => 'Booking Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select booking status',
            ],
        ];

        $booking_validation["supplier"] = [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier',
            ],
        ];
        $booking_validation["remark"] = [
            'label' => 'remark',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select remark',
            ],
        ];
        return $booking_validation;
    }
    public $raiseAmendment = [
        "amendment_status" => ['rules' => 'trim|required|in_list[success,rejected]', 'errors' => [
            'required' => 'Please select Amendment Status'
        ],],
        "remark" => ['rules' => 'required', 'errors' => [
            'required' => 'Please enter Remark'
        ]],
        "booking_ref_number" => ['rules' => 'required'],
    ];
    public $holiday_theme_validation = [
        'theme_name' => [
            'label' => 'theme name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter theme name'
            ],
        ],

        'show_on_home' => [
            'label' => 'show on home',
            'rules' => 'trim|max_length[1]',
        ],

        'theme_slug' => [
            'label' => 'theme slug',
            'rules' => 'trim|required|max_length[90]',
            'errors' => [
                'required' => 'Please enter theme slug'
            ],
        ],
        'starting_price' => [
            'label' => 'starting price',
            'rules' => 'trim|numeric|permit_empty',
            'errors' => [
                'required' => 'Please enter starting price'
            ],
        ],
        'description' => [
            'label' => 'description',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter description'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],

        'theme_image' => [
            'label' => 'Image',
            'rules' => 'uploaded[theme_image]|max_size[theme_image,1024]|mime_in[theme_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],

        ],
        'theme_icon' => [
            'label' => 'theme icon',
            'rules' => 'uploaded[theme_icon]|max_size[theme_icon,1024]|mime_in[theme_icon,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
        'meta_robots' => [
            'label' => 'Meta Robots',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please select meta robots'
            ],
        ],
		'meta_title' => [
            'label' => 'Meta Title',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta title'
            ],
        ],
		'meta_keyword' => [
            'label' => 'Meta Keyword',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta keyword'
            ],
        ],'meta_description' => [
            'label' => 'Meta Description',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta description'
            ],
        ]
    ];

    public $search_validation = [
        'slug' => [
            'label' => 'Holidays',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Destinations Themes Holidays'
            ],
        ],
    ];

    public $holiday_destination_validation = [
        'destination_name' => [
            'label' => 'destination name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter destination name'
            ],
        ],

        'show_on_home' => [
            'label' => 'show on home',
            'rules' => 'trim|max_length[1]',
        ],

        'destination_slug' => [
            'label' => 'destination slug',
            'rules' => 'trim|required|max_length[90]',
            'errors' => [
                'required' => 'Please enter destination slug'
            ],
        ],
        'starting_price' => [
            'label' => 'starting price',
            'rules' => 'trim|numeric|permit_empty',
            'errors' => [
                'required' => 'Please enter starting price'
            ],
        ],
        'description' => [
            'label' => 'description',
            'rules' => 'trim',
            'errors' => [
                'required' => 'Please enter description'
            ],
        ],

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
        'destination_type' => [
            'label' => 'destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select destination type'
            ],
        ],

        'destination_image' => [
            'label' => 'Image',
            'rules' => 'uploaded[destination_image]|max_size[destination_image,1024]|mime_in[destination_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
        'meta_robots' => [
            'label' => 'Meta Robots',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please select meta robots'
            ],
        ],
		'meta_title' => [
            'label' => 'Meta Title',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta title'
            ],
        ],
		'meta_keyword' => [
            'label' => 'Meta Keyword',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta keyword'
            ],
        ],'meta_description' => [
            'label' => 'Meta Description',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter meta description'
            ],
        ]
    ];


    public $holiday_inclusion_validation = [
        'inclusion_title' => [
            'label' => 'inclusion title',
            'rules' => 'trim|required|max_length[200]',
            'errors' => [
                'required' => 'Please enter inclusion title'
            ],
        ],


        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $holiday_exclusion_validation = [
        'exclusion_title' => [
            'label' => 'exclusion title',
            'rules' => 'trim|required|max_length[200]',
            'errors' => [
                'required' => 'Please enter exclusion title'
            ],
        ],


        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $holiday_list_validation = [
        'holiday_name' => [
            'label' => 'package name',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter package name'
            ],
        ],

        'holiday_slug' => [
            'label' => 'package slug',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please enter package slug'
            ],
        ],

        'starting_price_standard' => [
            'label' => 'starting price standard',
            'rules' => 'trim|numeric|permit_empty',
            'errors' => [
                'required' => 'Please enter starting price standard'
            ],
        ],
        'starting_price_deluxe' => [
            'label' => 'starting price deluxe',
            'rules' => 'trim|numeric|permit_empty',
            'errors' => [
                'required' => 'Please enter starting price deluxe'
            ],
        ],
        'starting_price_luxury' => [
            'label' => 'starting price luxury',
            'rules' => 'trim|numeric|permit_empty',
            'errors' => [
                'required' => 'Please enter starting price luxury'
            ],
        ],
        /*  'advance_value' => [
              'label' => 'advance payment value in percentage',
              'rules' => 'trim|required|numeric',
              'errors' => [
                  'required' => 'Please enter advance payment value in percentage'
              ],
          ],
          'advance_days' => [
              'label' => 'advance payment value in percentage',
              'rules' => 'trim|required|numeric',
              'errors' => [
                  'required' => 'Please enter Advance payment before days'
              ],
          ],*/

        'holiday_description' => [
            'label' => 'description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter description'
            ],
        ],

        'policies' => [
            'label' => 'policies',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter policies'
            ],
        ],

        'refund_policy' => [
            'label' => 'refund policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter refund policy'
            ],
        ],

        'cancellation_policy' => [
            'label' => 'cancellation policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter cancellation policy'
            ],
        ],
        'documentation' => [
            'label' => 'documentation',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter documentation'
            ],
        ],
        'payment_structure' => [
            'label' => 'payment structure',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter payment structure'
            ],
        ],

        'holiday_themes_id.*' => [
            'label' => 'holiday themes ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select holiday themes'
            ],
        ],


        'holiday_destination_id.*' => [
            'label' => 'holiday destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select holiday destination'
            ],
        ],

        'holiday_inclusion_id.*' => [
            'label' => 'holiday inclusion',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select holiday inclusion'
            ],
        ],

        'holiday_includes.*' => [
            'label' => 'holiday includes',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select holiday includes'
            ],
        ],

        'holiday_exclusion_id.*' => [
            'label' => 'holiday exclusion',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select holiday exclusion'
            ],
        ],

        'show_on_home' => [
            'label' => 'show on home',
            'rules' => 'trim|max_length[1]',
        ],

        'price_on_request' => [
            'label' => 'price on request',
            'rules' => 'trim|max_length[1]',
        ],
        'passport_required' => [
            'label' => 'passport required',
            'rules' => 'trim|max_length[1]',
        ],
        'pan_card_required' => [
            'label' => 'pan card required',
            'rules' => 'trim|max_length[1]',
        ],
        'book_online' => [
            'label' => 'book online',
            'rules' => 'trim|max_length[1]',
        ],

        'meta_robots' => [
            'label' => 'Meta Robots',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select meta robots'
            ],
        ],
        'meta_title' => [
            'label' => 'Meta Title',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter meta title'
            ],
        ],
        'meta_keyword' => [
            'label' => 'Meta Keyword',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter meta keyword'
            ],
        ],
        'meta_description' => [
            'label' => 'Meta Description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter meta description'
            ],
        ],

        'holiday_image' => [
            'label' => 'Image',
            'rules' => 'uploaded[holiday_image]|max_size[holiday_image,1024]|mime_in[holiday_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
    ];

    public $holiday_gallery_validation = [
        'book_online' => [
            'label' => 'image_title',
            'rules' => 'trim',
        ],

        'images' => [
            'label' => 'Image',
            'rules' => 'uploaded[images]|max_size[images,1024]|mime_in[images,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
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

    public $holiday_price_validation = [
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
        'standard_adult_price' => [
            'label' => 'standard adult price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter standard adult price'
            ],
        ],
        'standard_child_price' => [
            'label' => 'standard child price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter standard child price'
            ],
        ],
        'standard_extra_bad_price' => [
            'label' => 'standard extra bad  price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter standard extra bad  price'
            ],
        ],

        'deluxe_adult_price' => [
            'label' => 'deluxe adult price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter deluxe adult price'
            ],
        ],

        'deluxe_child_price' => [
            'label' => 'deluxe child price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter deluxe child price'
            ],
        ],

        'deluxe_extra_bad_price' => [
            'label' => 'deluxe extra bad price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'deluxe extra bad price'
            ],
        ],
        'luxury_adult_price' => [
            'label' => 'luxury adult price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'luxury adult price'
            ],
        ],
        'luxury_child_price' => [
            'label' => 'luxury child price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'luxury child price'
            ],
        ],
        'luxury_extra_bad_price' => [
            'label' => 'luxury extra bad  price',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'luxury luxury extra bad  price'
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


    public function holiday_itinerary_validation($data)
    {

        $itinerary_validation = [];
        foreach ($data['itinerary'] as $key => $requestParameter) {


            $itinerary_validation["itinerary.$key.city"] = [
                'label' => 'city',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please select itinerary city'
                ],
            ];
            $itinerary_validation["itinerary.$key.day_title"] = [
                'label' => 'day title',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter Please enter day title'
                ],
            ];

            $itinerary_validation["itinerary.$key.meal_type"] = [
                'label' => 'meal type',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter meal type'
                ],
            ];

            $itinerary_validation["itinerary.$key.description"] = [
                'label' => 'description',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter day description'
                ],
            ];
            if ($_FILES['itinerary']['name'][$key]['image']) {
                $itinerary_validation["itinerary.$key.image"] = [
                    'label' => 'image',
                    'rules' => "uploaded[itinerary.$key.image]|max_size[itinerary.$key.image,1024]|mime_in[itinerary.$key.image,image/jpg,image/jpeg,image/png]",
                    'errors' => [
                        'max_size' => 'Image size should not be more than 1024kb',
                        'mime_in' => 'Please upload valid image. allowed image types are jpg, jpeg, png'
                    ],
                ];
            }

            if (isset($data['flight'][$key])) {
            
                foreach ($data['flight'][$key] as $key1 => $requestParameter) {
                    
    
                    $itinerary_validation["flight.$key.$key1.origin"] = [
                        'label' => 'origin',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter origin'
                        ],
                    ];
    
                    $itinerary_validation["flight.$key.$key1.destination"] = [
                        'label' => 'destination',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter destination'
                        ],
                    ];
                    $itinerary_validation["flight.$key.$key1.airline"] = [
                        'label' => 'airline ',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter airline '
                        ],
                    ];
    
                    $itinerary_validation["flight.$key.$key1.depart_time"] = [
                        'label' => 'depart time',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter depart time'
                        ],
                    ];
                    $itinerary_validation["flight.$key.$key1.arrival_time"] = [
                        'label' => 'depart time',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter depart time'
                        ],
                    ];
    
                    $itinerary_validation["flight.$key.$key1.remark"] = [
                        'label' => 'remark',
                        'rules' => 'trim',
                        'errors' => [
                            'required' => 'Please enter remark'
                        ],
                    ];
                }
            }

            if (isset($data['hotel'][$key])) {
                foreach ($data['hotel'][$key] as $key1 => $requestParameter) {
    
    
                    $itinerary_validation["hotel.$key.$key1.hotel_name"] = [
                        'label' => 'hotel name',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter hotel name'
                        ],
                    ];
                    $itinerary_validation["hotel.$key.$key1.hotel_city"] = [
                        'label' => 'hotel city',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter hotel city'
                        ],
                    ];
                    $itinerary_validation["hotel.$key.$key1.hotel_address"] = [
                        'label' => 'hotel address',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter hotel address'
                        ],
                    ];
                    $itinerary_validation["hotel.$key.$key1.remark"] = [
                        'label' => 'remark',
                        'rules' => 'trim',
                        'errors' => [
                            'required' => 'Please enter remark'
                        ],
                    ];
                }
            }

            if (isset($data['transfer'][$key])) {
                foreach ($data['transfer'][$key] as $key1 => $requestParameter) {
    
    
                    $itinerary_validation["transfer.$key.$key1.transfer_type"] = [
                        'label' => 'transfer type',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter transfer type'
                        ],
                    ];
    
                    $itinerary_validation["transfer.$key.$key1.pickup"] = [
                        'label' => 'pickup',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter pickup'
                        ],
                    ];
                    $itinerary_validation["transfer.$key.$key1.drop"] = [
                        'label' => 'drop',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter drop'
                        ],
                    ];
                    $itinerary_validation["transfer.$key.$key1.pickuptime"] = [
                        'label' => 'pickup time',
                        'rules' => 'trim|required',
                        'errors' => [
                            'required' => 'Please enter pickup time'
                        ],
                    ];
    
                    $itinerary_validation["transfer.$key.$key1.remark"] = [
                        'label' => 'remark',
                        'rules' => 'trim',
                        'errors' => [
                            'required' => 'Please enter remark'
                        ],
                    ];
                }
            }
        }


        return $itinerary_validation;
    }

    public $holiday_settings_validation = [
        'polices' => [
            'label' => 'Terms and Conditions',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Terms and Conditions'
            ],
        ],
        'payment_structure' => [
            'label' => 'Payment Structure',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Payment Structure'
            ],
        ],
        'cancellation_policy' => [
            'label' => 'Cancellation Policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Cancellation Policy'
            ],
        ],
        'documentation' => [
            'label' => 'Documentation',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Documentation'
            ],
        ],
        'refund_policy' => [
            'label' => 'Refund Policy',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Refund Policy'
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
        'charge' => [
            'label' => 'charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter charge',
                'numeric' => 'Please enter valid charge',
                'greater_than_equal_to' => 'Please enter valid charge'
            ],
        ],
        'service_charge' => [
            'label' => 'service charge',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge',
                'numeric' => 'Please enter service valid charge',
                'greater_than_equal_to' => 'Please enter service valid charge'
            ],
        ],
        'service_charge_gst' => [
            'label' => 'service charge gst',
            'rules' => 'trim|required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Please enter service charge gst',
                'numeric' => 'Please enter service valid charge gst',
                'greater_than_equal_to' => 'Please enter service valid charge gst'
            ],
        ],


    ];

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

    public $holiday_markup_validation = [

        'agent_class.*' => [
            'label' => 'agent class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'max_limit' => [
            'label' => 'max limit',
            'rules' => 'trim|permit_empty|numeric',
        ], 
        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],
        'markup_for' => [
            'label' => 'markup for',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup for'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'theme_name.*' => [
            'label' => 'theme',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  theme'
            ],
        ],

        'destination_name.*' => [
            'label' => 'destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  destination'
            ],
        ],

        'holiday_package.*' => [
            'label' => 'package',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select package'
            ],
        ],
    ];

    public $holiday_discount_validation = [


        'agent_class.*' => [
            'label' => 'agent class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select agent class'
            ],
        ],
        'discount_for' => [
            'label' => 'discount for',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount for'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'max_limit' => [
            'label' => 'max limit',
            'rules' => 'trim|permit_empty|numeric',
        ],


        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
        'theme_name.*' => [
            'label' => 'theme',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  theme'
            ],
        ],

        'destination_name.*' => [
            'label' => 'destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select destination'
            ],
        ],
        
        'holiday_package.*' => [
            'label' => 'package',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select package'
            ],
        ],
    ];
}
