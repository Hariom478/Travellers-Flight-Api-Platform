<?php
/**
 * Define Invoice Routes
 */
$routes->group("invoice", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\Invoice\Controllers\Invoice::index');

    $routes->match(['post'], 'get-report', '\Modules\Invoice\Controllers\Invoice::getReport');
});
