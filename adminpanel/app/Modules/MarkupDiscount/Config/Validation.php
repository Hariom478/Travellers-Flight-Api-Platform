<?php

namespace Modules\MarkupDiscount\Config;


class Validation
{

    public $flight_discount_markup_validation = [
        'supplier.*' => [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier'
            ],
        ],
        'airline_code' => [
            'label' => 'airline code',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Please enter airline code'
            ],
        ],

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter web partner class'
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


    public $car_markup_validation = [

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $car_discount_validation = [


        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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


        'discount_type' => [
            'label' => 'discount type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];


    public $bus_markup_validation = [

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $bus_discount_validation = [


        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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
    ];


    public $hotel_markup_validation = [

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
            ],
        ],

        'supplier.*' => [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],


        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'region_type.*' => [
            'label' => 'region type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select region type'
            ],
        ],

        'hotel_markup_type' => [
            'label' => 'hotel markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select hotel markup type'
            ],
        ],

        'star_rating.*' => [
            'label' => 'star rating',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select star rating'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $hotel_discount_validation = [


        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
            ],
        ],

        'supplier.*' => [
            'label' => 'supplier',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select supplier'
            ],
        ],

        'region_type.*' => [
            'label' => 'region type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select region type'
            ],
        ],

        'value' => [
            'label' => 'value',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter value'
            ],
        ],

        'extra_discount' => [
            'label' => 'extra discount',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter extra discount'
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
    ];

    public $visa_markup_validation = [

        'visa_country_id' => [
            'label' => 'country ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select country'

            ],
        ],

        'visa_type_id.*' => [
            'label' => 'visa type id',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select visa type'

            ],
        ],

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $visa_discount_validation = [

        'visa_country_id' => [
            'label' => 'country ',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select country'

            ],
        ],

        'visa_type_id.*' => [
            'label' => 'visa type id',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select visa type'

            ],
        ],

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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



        'discount_type' => [
            'label' => 'discount type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $cruise_markup_validation = [
        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
            ],
        ],

        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'

            ],
        ],

        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'

            ],
        ],
        'departure_port_id' => [
            'label' => 'departure port',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select departure port'
            ],
        ],

        'cabin_id' => [
            'label' => 'cruise cabin',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise cabin'
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

        'display_markup' => [
            'label' => ' display markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  display markup'
            ],
        ],

        'markup_type' => [
            'label' => 'markup type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select markup type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $cruise_discount_validation = [
        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
            ],
        ],

        'cruise_line_id' => [
            'label' => 'cruise line',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise line'

            ],
        ],

        'cruise_ship_id' => [
            'label' => 'cruise ship',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise ship'

            ],
        ],
        'departure_port_id' => [
            'label' => 'departure port',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select departure port'
            ],
        ],

        'cabin_id' => [
            'label' => 'cruise cabin',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select cruise cabin'
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



        'discount_type' => [
            'label' => 'discount type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select discount type'
            ],
        ],

        'status' => [
            'label' => ' Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  status'
            ],
        ],
    ];

    public $holiday_markup_validation = [

        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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

        'theme_name' => [
            'label' => 'theme',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  theme'
            ],
        ],

        'destination_name' => [
            'label' => 'destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  destination'
            ],
        ],
    ];

    public $holiday_discount_validation = [


        'web_partner_class_id.*' => [
            'label' => 'web partner class',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select web partner class'
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
        'theme_name' => [
            'label' => 'theme',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  theme'
            ],
        ],

        'destination_name' => [
            'label' => 'destination',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select destination'
            ],
        ],
    ];


}

