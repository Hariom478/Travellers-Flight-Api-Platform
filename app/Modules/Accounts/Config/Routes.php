<?php
/**
 * Define Account Routes
 */
$routes->group("accounts", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], 'payment-processing', '\Modules\Accounts\Controllers\Accounts::MakePaymentView');
    $routes->match(['post'], 'make-payment-processing', '\Modules\Accounts\Controllers\Accounts::MakePayment');

    $routes->match(['get'], 'online-payment-processing', '\Modules\Accounts\Controllers\Accounts::RechargeMakePaymentView');
    $routes->match(['post'], 'online-make-payment-processing', '\Modules\Accounts\Controllers\Accounts::RechargeMakePayment');

    $routes->match(['get'], 'payment-history', '\Modules\Accounts\Controllers\Accounts::payment_history');

    $routes->post('payment-history-detail/(:any)', '\Modules\Accounts\Controllers\Accounts::payment_history_detail');

});
