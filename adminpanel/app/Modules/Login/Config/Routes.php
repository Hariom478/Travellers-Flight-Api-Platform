<?php
/**
 * Define Login Routes
 */
$routes->group("login", function($routes) {
    $routes->match(['get', 'post'],'/', '\Modules\Login\Controllers\Login::index');
    $routes->post('forgot-password', '\Modules\Login\Controllers\Login::forgot_password');
    $routes->post('validate-otp-password', '\Modules\Login\Controllers\Login::validate_otp_password');

});
$routes->get('signout', '\Modules\Login\Controllers\Login::signout');
$routes->get('access-account/(:any)', '\Modules\Login\Controllers\Login::access_account');