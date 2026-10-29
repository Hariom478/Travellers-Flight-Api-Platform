<?php
/**
 * Define ConvenienceFee Routes
 */


$routes->group("convenience-fee", ["filter" => "auth"] , function($routes) {

    $routes->match(['get'],'/', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::index');
    $routes->post('add-convenience-template', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::add_convenience_view');
    $routes->post('add-convenience', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::add_convenience');
    $routes->post('remove-convenience', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::remove_convenience');
    $routes->post('edit-convenience-template/(:any)', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::edit_convenience_view');
    $routes->post('edit-convenience/(:any)', '\Modules\ConvenienceFee\Controllers\ConvenienceFee::edit_convenience');

});
