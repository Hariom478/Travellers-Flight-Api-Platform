<?php
/**
 * Define Customer Routes
 */
$routes->group("customer", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'],'/', '\Modules\Customer\Controllers\Customer::index');
    $routes->post('add-customer-template', '\Modules\Customer\Controllers\Customer::add_customer_view');
    $routes->post('add-customer', '\Modules\Customer\Controllers\Customer::add_customer');
    $routes->post('remove-customer', '\Modules\Customer\Controllers\Customer::remove_customer');
    $routes->post('customer-status-change', '\Modules\Customer\Controllers\Customer::customer_status_change');
    $routes->post('edit-customer-template/(:any)', '\Modules\Customer\Controllers\Customer::edit_customer_view');
    $routes->post('edit-customer/(:any)', '\Modules\Customer\Controllers\Customer::edit_customer');
    $routes->post('change-customer-password', '\Modules\Customer\Controllers\Customer::change_customer_password');
    $routes->get('customer-account-logs/(:any)', '\Modules\Customer\Controllers\Customer::customer_account_logs');
    $routes->post('virtual-topup/(:any)', '\Modules\Customer\Controllers\Customer::virtual_topup');
    $routes->post('virtual-topup-template/(:any)', '\Modules\Customer\Controllers\Customer::virtual_topup_view');
    $routes->post('virtual-debit/(:any)', '\Modules\Customer\Controllers\Customer::virtual_debit');
    $routes->post('virtual-debit-template/(:any)', '\Modules\Customer\Controllers\Customer::virtual_debit_view');
    $routes->post('customer-details/(:any)', '\Modules\Customer\Controllers\Customer::customer_details');

    $routes->post('export-customer', '\Modules\Customer\Controllers\Customer::export_customer');

    /*travellers routes start*/
    $routes->get('travellers-list/(:any)', '\Modules\Customer\Controllers\Customer::travellers_list');
    $routes->post('add-travellers-template/(:any)', '\Modules\Customer\Controllers\Customer::add_travellers_view');
    $routes->post('add-travellers/(:any)', '\Modules\Customer\Controllers\Customer::add_travellers');
    $routes->post('traveller-status-change', '\Modules\Customer\Controllers\Customer::traveller_status_change');
    $routes->post('remove-traveller', '\Modules\Customer\Controllers\Customer::remove_traveller');
    $routes->post('edit-traveller-template/(:any)', '\Modules\Customer\Controllers\Customer::edit_traveller_view');
    $routes->post('edit-travellers/(:any)', '\Modules\Customer\Controllers\Customer::edit_travellers');
    $routes->post('traveller-details/(:any)', '\Modules\Customer\Controllers\Customer::traveller_details');
});
