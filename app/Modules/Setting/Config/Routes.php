<?php

/**
 * Define  Routes For Dashboard
 */

$routes->group("setting", ["filter" => "auth","namespace" => "\Modules\Setting\Controllers"] , function($routes) {
    $routes->get('', 'Setting::index');
    $routes->post('update_company_setting', 'Setting::update_company_setting');
    $routes->get('user-management', 'Setting::user_management');
    $routes->post('add_user', 'Setting::add_user');
    $routes->post('user_access_permission/(:any)', 'Setting::user_access_permission');
    $routes->get('crm-options', 'Setting::crm_options');
    $routes->post('delete_user', 'Setting::delete_user');
    $routes->post('access_level_update/(:any)', 'Setting::access_level_update');
    $routes->post('add_common_websett', 'Setting::add_common_websett');
    $routes->post('delete_common_data', 'Setting::delete_common_data');
    $routes->post('edit_common_option/(:any)', 'Setting::edit_common_option');
    $routes->post('update_common_data/(:any)', 'Setting::update_common_data');
    $routes->post('edit_user/(:any)', 'Setting::edit_user');
    $routes->post('update_user/(:any)', 'Setting::update_user');
    $routes->get('profile', 'Setting::profile');
    $routes->post('change_password', 'Setting::change_password');
    $routes->post('staff-change-password/(:any)', 'Setting::staff_change_password');
    $routes->post('update-staff-change-password/(:any)', 'Setting::update_staff_change_password');
    $routes->post('user-status-change', 'Setting::user_status_change');
});




?>