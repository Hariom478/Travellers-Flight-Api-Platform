<?php
namespace Modules\Offers\Config;

class Validation
{
    public $offer_validation = [
        'title' => [
            'label' => 'Title',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter title'
            ],
        ],
        'service' => [
            'label' => 'service',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select service'
            ],
        ],
        'url' => [
            'label' => 'url',
            'rules' => 'trim',
        ],
        'description' => [
            'label' => 'Description',
            'rules' => 'trim|required|max_length[255]',
            'errors' => [
                'required' => 'Please enter  description'
            ],
        ],
        'image' => [
            'label' => 'Offer  Image',
            'rules' => 'uploaded[image]|max_size[image,1024]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png, webp'
            ],

        ],
    ];



    public $offers_status = [

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];

}
