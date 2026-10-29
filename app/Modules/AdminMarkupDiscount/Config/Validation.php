<?php
namespace Modules\AdminMarkupDiscount\Config;



class Validation
{

    public $flight_discount_markup_validation = [

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
            'label' => 'Fare Type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Fare Type'
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

    public $hotel_discount_markup_validation = [
        
        'star_rating.*' => [
            'label' => 'Star Rating',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Star Rating'
            ],
        ],

        'region_type.*' => [
            'label' => 'Region type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select Region type'
            ],
        ],

        'hotel_markup_type' => [
            'label' => 'hotel_markup_type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Markup Type'
            ],
        ],

        'display_markup' => [
            'label' => 'display_markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Display Markup'
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

    public $bus_markup_validation = [

        'markup_type' => [
            'label' => 'markup_type',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Markup Type'
            ],
        ],

        'display_markup' => [
            'label' => 'display_markup',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter Display Markup'
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
            'label' => 'Max Limit',
            'rules' => 'trim|required|numeric',
            'errors' => [
                'required' => 'Please enter Max Limit'
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

    public $cruise_markup_validation = [
               
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

    public $holiday_markup_validation = [

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
        'holiday_package' => [
            'label' => 'package',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select package'
            ],
        ],
    ];

    // Tourguide Markup Validation
    public $tourguide_markup_validation = [

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

        'city_name' => [
            'label' => 'City',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  City'
            ],
        ],

        'tour_guide_name' => [
            'label' => 'Tourguide',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  Tourguide'
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





    public $activities_markup_validation = [

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

       

        'destination_name' => [
            'label' => 'Destination Name',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  Destination Name'
            ],
        ],

    ];


    

}

