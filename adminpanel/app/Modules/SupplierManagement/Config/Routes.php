<?php
/**
 * Define SupplierManagement Routes
 */

$routes->group("supplier-management", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\SupplierManagement\Controllers\SupplierManagement::index');
    $routes->match(['post'], 'api-flight-supplier-mgt-template', '\Modules\SupplierManagement\Controllers\SupplierManagement::apiFlightSupplierMgtTemplate');
    $routes->match(['post'], 'api-flight-supplier-mgt', '\Modules\SupplierManagement\Controllers\SupplierManagement::apiFlightSupplierMgt');
    $routes->post('add-flight-api-mgt-template', '\Modules\SupplierManagement\Controllers\SupplierManagement::add_flight_api_mgt_template');
    $routes->post('add-flight-api-mgt', '\Modules\SupplierManagement\Controllers\SupplierManagement::add_flight_api_mgt');
    $routes->post('remove-flight-api-mgt', '\Modules\SupplierManagement\Controllers\SupplierManagement::remove_flight_api_mgt');
    $routes->post('edit-flight-api-mgt-template/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_flight_api_mgt_template');
    $routes->post('edit-flight-api-mgt/(:any)', '\Modules\SupplierManagement\Controllers\SupplierManagement::edit_flight_api_mgt');


    $routes->post('flight-api-mgt-status-change', '\Modules\SupplierManagement\Controllers\SupplierManagement::flight_api_mgt_status_change');
});
