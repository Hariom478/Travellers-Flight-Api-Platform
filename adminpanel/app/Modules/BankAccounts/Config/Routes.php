<?php
/**
 * Define BankAccounts Routes
 */


$routes->group("bankaccounts", ["filter" => "auth"] , function($routes) {
    $routes->match(['get'],'/', '\Modules\BankAccounts\Controllers\BankAccounts::index');
    $routes->post('add-account-template', '\Modules\BankAccounts\Controllers\BankAccounts::add_account_view');
    $routes->post('add-account', '\Modules\BankAccounts\Controllers\BankAccounts::add_account');
    $routes->post('edit-bank-template/(:any)', '\Modules\BankAccounts\Controllers\BankAccounts::edit_account_view');
    $routes->post('edit-account/(:any)', '\Modules\BankAccounts\Controllers\BankAccounts::edit_account');
    $routes->post('remove-account', '\Modules\BankAccounts\Controllers\BankAccounts::remove_account');
});
