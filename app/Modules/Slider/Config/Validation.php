<?php

namespace Modules\Slider\Config;

class Validation
{
    public $slider_validation = [
        'slider_text1' => [
            'label' => 'slider text1',
            'rules' => 'trim|max_length[150]',
        ],

        'slider_text2' => [
            'label' => 'slider text2',
            'rules' => 'trim|max_length[150]',
        ],
        'url' => [
            'label' => 'url',
            'rules' => 'permit_empty|trim|valid_url_strict|max_length[250]',
            'errors' => [
                'valid_url_strict' => 'Please enter a valid url'
            ],
        ],
        'url_button_text' => [
            'label' => 'button text',
            'rules' => 'trim|max_length[60]',
        ],
        'image_category' => [
            'label' => 'category',
            'rules' => 'trim|required|max_length[80]',
            'errors' => [
                'required' => 'Please select slider category'
            ],
        ],
        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select slider status'
            ],
        ],

        'slider_image' => [
            'label' => 'Slider Image',
            'rules' => 'uploaded[slider_image]|max_size[slider_image,1024]|mime_in[slider_image,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],
    ];


    public $slider_status = [

        'status' => [
            'label' => 'status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select status'
            ],
        ],
    ];
}
