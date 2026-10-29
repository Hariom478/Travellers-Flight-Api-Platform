<?php
/**
 * Define Pages Routes
 */
$routes->group("payment", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], 'opt/(:any)', '\Modules\Payment\Controllers\Payment::index');
    $routes->match(['post'], 'response', '\Modules\Payment\Controllers\Payment::response');
    $routes->match(['post'], 'makepaymentesponse', '\Modules\Payment\Controllers\Payment::makepaymentesponse');
    $routes->match(['get'], 'proceed-payment/(:any)', '\Modules\Payment\Controllers\Payment::proceed_payment');
    $routes->match(['get'], 'make-payment/(:any)', '\Modules\Payment\Controllers\Payment::makePayment');
    $routes->match(['get'], 'payment-error', '\Modules\Payment\Controllers\Payment::payment_error');

});