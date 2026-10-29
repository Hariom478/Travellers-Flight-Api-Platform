<?php
/**
 * Define Pages Routes
 */
$routes->group("pages", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'],'/', '\Modules\Pages\Controllers\Pages::index');
    $routes->post('add-pages-template', '\Modules\Pages\Controllers\Pages::add_pages_view');
    $routes->post('add-pages', '\Modules\Pages\Controllers\Pages::add_pages');
    $routes->post('remove-pages', '\Modules\Pages\Controllers\Pages::remove_pages');
    $routes->post('edit-pages-template/(:any)', '\Modules\Pages\Controllers\Pages::edit_pages_view');
    $routes->post('edit-pages/(:any)', '\Modules\Pages\Controllers\Pages::edit_pages');
    $routes->post('pages-status-change', '\Modules\Pages\Controllers\Pages::pages_status_change');

    $routes->match(['get','post'],'menu-list', '\Modules\Pages\Controllers\Pages::menu_list');
    $routes->post('update-menu', '\Modules\Pages\Controllers\Pages::update_menu');
    $routes->post('remove-menu', '\Modules\Pages\Controllers\Pages::remove_menu');

    $routes->post('menu-labels', '\Modules\Pages\Controllers\Pages::menu_labels');
    $routes->post('sort-menu', '\Modules\Pages\Controllers\Pages::sortMenu');
    $routes->post('edit-menu-labels', '\Modules\Pages\Controllers\Pages::edit_menu_labels');
    
});
