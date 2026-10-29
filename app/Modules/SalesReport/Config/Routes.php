<?php
/**
 * Define WebPartnerAccount Routes
 */
$routes->group("sale-result", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\SalesReport\Controllers\SalesReport::index');

    $routes->match(['post'], 'get-report', '\Modules\SalesReport\Controllers\SalesReport::get_report');

    $routes->match(['get'], 'service-report', '\Modules\SalesReport\Controllers\SalesReport::service_report');
    
    $routes->match(['post'], 'get-service-report', '\Modules\SalesReport\Controllers\SalesReport::get_service_report');

});
