<?php
/**
 * Define Newsletter Routes
 */

    $routes->group("newsletter", ["filter" => "auth"] , function($routes) {
    $routes->match(['get'],'/', '\Modules\Newsletter\Controllers\Newsletter::index');
    $routes->post('add-newsletter-template', '\Modules\Newsletter\Controllers\Newsletter::add_newsletter_view');
    $routes->post('add-newsletter', '\Modules\Newsletter\Controllers\Newsletter::add_newsletter');
    $routes->post('remove-newsletter', '\Modules\Newsletter\Controllers\Newsletter::remove_newsletter');
    $routes->post('edit-newsletter-template/(:any)', '\Modules\Newsletter\Controllers\Newsletter::edit_newsletter_view');
    $routes->post('edit-newsletter/(:any)', '\Modules\Newsletter\Controllers\Newsletter::edit_newsletters');
    $routes->post('export-newsletter', '\Modules\Newsletter\Controllers\Newsletter::export_newsletter');
   
});


