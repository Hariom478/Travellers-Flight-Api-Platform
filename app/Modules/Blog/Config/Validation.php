<?php
namespace Modules\Blog\Config;

class Validation
{
    public $blogs_validation = [
        'post_title' => [
            'label' => 'Blog  Title',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter blog  title'
                
            ],
        ],

        'posted_by' => [
            'label' => 'Posted By',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter posted by'

            ],
        ],

        'post_slug' => [
            'label' => 'post slug',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter post slug'

            ],
        ],

		'category_id' => [
            'label' => 'Blog Category',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please select blog category'
            ],
        ],

		'status' => [
            'label' => 'Blog Status',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please select blog status'
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
        ],
		'post_desc' => [
            'label' => 'Blog Post  Description',
            'rules' => 'trim|required',
            'errors' => [
                 'required' => 'Please enter blog post description'
            ],
        ],
		'post_images' => [
            'label' => 'Blog Post Image',
            'rules' => 'uploaded[post_images]|max_size[post_images,1024]|mime_in[post_images,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
            
        ],
    ];


    public $blog_status = [

        'status' => [
            'label' => 'Blog Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select blog status'
            ],
        ],
    ];

    public $category_status = [

        'status' => [
            'label' => 'category Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select category status'
            ],
        ],
    ];



    public $category_validation = [
        'category_name' => [
            'label' => 'Blog Category  Title',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter  blog Category title'

            ],
        ],
        'status' => [
            'label' => 'Blog Category Status',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please select  blog category status'
            ],
        ],

        'category_slug' => [
            'label' => 'category slug',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter category slug'
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
        ],


        'category_img' => [
            'label' => 'Blog Category Image',
            'rules' => 'uploaded[category_img]|max_size[category_img,1024]|mime_in[category_img,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Image size should not be more than 1024kb',
                'mime_in'=>'Please upload valid image. allowed image types are jpg, jpeg, png'
            ],
        ],

        'description' => [
            'label' => 'category  description',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Please enter  description'
            ],
        ],
    ];

}
