<?php
/**
 * Define Blog Routes
 */
$routes->group("blog", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\Blog\Controllers\Blog::index');
    $routes->post('add-blog-template', '\Modules\Blog\Controllers\Blog::add_blog_view');
    $routes->post('add-blogs', '\Modules\Blog\Controllers\Blog::add_blog');
    $routes->post('remove-blog', '\Modules\Blog\Controllers\Blog::remove_blog');
    $routes->post('edit-blog-template/(:any)', '\Modules\Blog\Controllers\Blog::edit_blog_view');
    $routes->post('edit-blogs/(:any)', '\Modules\Blog\Controllers\Blog::edit_blogs');
    $routes->post('blog-status-change', '\Modules\Blog\Controllers\Blog::blog_status_change');
    
    //category urls

    $routes->match(['get'], 'blog-category-list', '\Modules\Blog\Controllers\Blog::blog_category_list');
    $routes->post('add-blog-category-template', '\Modules\Blog\Controllers\Blog::add_blog_category_view');
    $routes->post('add-blogs-category', '\Modules\Blog\Controllers\Blog::add_blog_category');
    $routes->post('edit-blog-category-template/(:any)', '\Modules\Blog\Controllers\Blog::edit_blog_category_view');
    $routes->post('edit-blogs-category/(:any)', '\Modules\Blog\Controllers\Blog::edit_blogs_category');
    $routes->post('remove-blog-category', '\Modules\Blog\Controllers\Blog::remove_blog_category');
    $routes->post('blog-category-status-change', '\Modules\Blog\Controllers\Blog::blog_category_status_change');
});
