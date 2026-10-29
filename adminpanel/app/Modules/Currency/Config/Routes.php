<?php
/**
 * Define currency Routes
 */

$routes->group("currency", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Currency\Controllers\Currency::index');
    $routes->post('add-currency-view', '\Modules\Currency\Controllers\Currency::add_currency_view');
    $routes->post('add-currency', '\Modules\Currency\Controllers\Currency::add_currency');
    $routes->post('remove-currency', '\Modules\Currency\Controllers\Currency::remove_currency');

    $routes->post('edit-currency-view/(:any)', '\Modules\Currency\Controllers\Currency::edit_currency_view');
    $routes->post('edit-currency/(:any)', '\Modules\Currency\Controllers\Currency::edit_currency');


});