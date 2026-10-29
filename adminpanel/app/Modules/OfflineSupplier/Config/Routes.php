<?php
/**
 * Define offline supplier  Routes
 */
    $routes->group("offline-issue-supplier", ["filter" => "auth"] , function($routes) {
    $routes->get('', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::index');
    $routes->post('add-supplier-template', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::add_supplier_view');
    $routes->post('add-supplier', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::add_supplier');

    $routes->post('edit-supplier-template/(:any)', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::edit_supplier_view');
    $routes->post('edit-supplier/(:any)', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::edit_supplier');
    $routes->post('status-change', '\Modules\OfflineSupplier\Controllers\OfflineSupplier::status_change');
});


