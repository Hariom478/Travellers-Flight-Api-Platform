<?php
/**
 * Define Supplier Routes
 */
$routes->group("suppliers", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Supplier\Controllers\Supplier::index');
    $routes->post('supplier-class', '\Modules\Supplier\Controllers\Supplier::Supplier_class');
    $routes->post('add-supplier-class', '\Modules\Supplier\Controllers\Supplier::add_supplier_class');
    $routes->post('edit-supplier-class/(:any)', '\Modules\Supplier\Controllers\Supplier::edit_supplier_class');
    $routes->get('remove-supplier-class/(:any)', '\Modules\Supplier\Controllers\Supplier::remove_supplier_class');
    $routes->get('add-supplier-view', '\Modules\Supplier\Controllers\Supplier::add_supplier_view');
    $routes->post('add-supplier-save', '\Modules\Supplier\Controllers\Supplier::add_supplier_save');
    $routes->get('edit-supplier-view/(:any)', '\Modules\Supplier\Controllers\Supplier::edit_supplier_view');
    $routes->post('edit-supplier-save/(:any)', '\Modules\Supplier\Controllers\Supplier::edit_supplier_save');
    $routes->post('remove-supplier', '\Modules\Supplier\Controllers\Supplier::remove_supplier');
    $routes->post('supplier-status-change', '\Modules\Supplier\Controllers\Supplier::supplier_status_change');
    $routes->get('supplier-account-logs/(:any)', '\Modules\Supplier\Controllers\Supplier::supplier_account_logs');
    $routes->match(['post'], 'supplier-details/(:any)', '\Modules\Supplier\Controllers\Supplier::supplier_details');
    $routes->post('change-password', '\Modules\Supplier\Controllers\Supplier::change_password');
    $routes->get('supplier-manage', '\Modules\Supplier\Controllers\Supplier::supplier_manage_accounts');
    $routes->post('export-supplier', '\Modules\Supplier\Controllers\Supplier::export_supplier');
    $routes->match(['post'], 'supplier-manage-account-info/(:any)', '\Modules\Supplier\Controllers\Supplier::supplier_manage_account_info');
    $routes->get('get-supplier', '\Modules\Supplier\Controllers\Supplier::getSupplier');
    $routes->post('view-remark/(:any)', '\Modules\Supplier\Controllers\Supplier::view_remark');
    $routes->post('account-update-log-remark/(:any)', '\Modules\Supplier\Controllers\Supplier::accountUpdateLogRemark');
    /** supplier  routes*/

    /** supplier wallet routes*/
    $routes->post('virtual-topup/(:any)', '\Modules\Supplier\Controllers\Supplier::virtual_topup');
    $routes->post('virtual-topup-template/(:any)', '\Modules\Supplier\Controllers\Supplier::virtual_topup_view');
    $routes->post('virtual-debit/(:any)', '\Modules\Supplier\Controllers\Supplier::virtual_debit');
    $routes->post('virtual-debit-template/(:any)', '\Modules\Supplier\Controllers\Supplier::virtual_debit_view');
});