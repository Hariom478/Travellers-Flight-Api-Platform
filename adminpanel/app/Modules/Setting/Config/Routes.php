<?php

/**
 * Define  Routes For Dashboard
 */

$routes->group("setting", ["filter" => "auth","namespace" => "\Modules\Setting\Controllers"] , function($routes) {
    $routes->get('', 'Setting::index');
    $routes->post('update_company_setting/(:any)', 'Setting::update_company_setting');
    $routes->get('user-management', 'Setting::user_management');
    $routes->post('add_user', 'Setting::add_user');
    $routes->post('user_access_permission/(:any)', 'Setting::user_access_permission');
    $routes->post('delete_user', 'Setting::delete_user');
    $routes->post('access_level_update/(:any)', 'Setting::access_level_update');
    $routes->post('edit_user/(:any)', 'Setting::edit_user');
    $routes->post('update_user/(:any)', 'Setting::update_user');
    $routes->get('profile', 'Setting::profile');
    $routes->post('change_password', 'Setting::change_password');
    $routes->post('user-status-change', 'Setting::user_status_change');
 
});

?>