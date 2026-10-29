<?php
/**
 * Define Login Routes
 */
$routes->group("login", function($routes) {
    $routes->match(['get', 'post'],'/', '\Modules\Login\Controllers\Login::index');

    $routes->match(['get', 'post'],'signup-successful', '\Modules\Login\Controllers\Login::signup_successful');

    $routes->match(['get', 'post'],'register-agency', '\Modules\Login\Controllers\Login::register_agency');

    $routes->post('generate-otp', '\Modules\Login\Controllers\Login::generate_otp');
    $routes->post('validate-otp', '\Modules\Login\Controllers\Login::validate_otp');
    $routes->post('validate-pan', '\Modules\Login\Controllers\Login::validate_pan');
    $routes->post('validate-gst', '\Modules\Login\Controllers\Login::validate_gst');

    $routes->post('forgot-password', '\Modules\Login\Controllers\Login::forgot_password');

    $routes->post('validate-otp-password', '\Modules\Login\Controllers\Login::validate_otp_password');

    $routes->post('state', '\Modules\Login\Controllers\Login::state');

    $routes->post('city', '\Modules\Login\Controllers\Login::city');

});
$routes->get('signout', '\Modules\Login\Controllers\Login::signout');
$routes->get('access-account/(:any)', '\Modules\Login\Controllers\Login::access_account');