<?php
/**
 * Define WebPartnerAccount Routes
 */
$routes->group("sale-result", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\SalesReport\Controllers\SalesReport::index');

    $routes->match(['post'], 'get-report', '\Modules\SalesReport\Controllers\SalesReport::get_report');
   

});
