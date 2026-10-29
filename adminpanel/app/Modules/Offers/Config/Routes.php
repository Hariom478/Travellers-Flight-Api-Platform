<?php
/**
 * Define Offers Routes
 */

$routes->group("offers", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Offers\Controllers\Offers::index');
    $routes->post('add-offer-template', '\Modules\Offers\Controllers\Offers::add_offer_view');
    $routes->post('add-offer', '\Modules\Offers\Controllers\Offers::add_offer');
    $routes->post('remove-offer', '\Modules\Offers\Controllers\Offers::remove_offer');

    $routes->post('edit-offer-view/(:any)', '\Modules\Offers\Controllers\Offers::edit_offer_view');
    $routes->post('edit-offer/(:any)', '\Modules\Offers\Controllers\Offers::edit_offer');
    $routes->post('offers-status-change', '\Modules\Offers\Controllers\Offers::offers_status_change');
});