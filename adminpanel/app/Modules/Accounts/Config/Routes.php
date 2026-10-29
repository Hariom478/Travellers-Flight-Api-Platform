<?php
/**
 * Define Account Routes
 */
$routes->group("accounts", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], 'wl-payment-history', '\Modules\Accounts\Controllers\Accounts::wl_payment_history');

    $routes->post('wl-payment-history-detail/(:any)', '\Modules\Accounts\Controllers\Accounts::wl_payment_history_detail');
    $routes->post('wl-payment-status-change', '\Modules\Accounts\Controllers\Accounts::wl_payment_status_change');

});
