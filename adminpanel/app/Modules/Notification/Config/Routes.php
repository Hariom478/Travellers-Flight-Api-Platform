<?php
/**
 * Define webpartner_notification Routes
 */

$routes->group("notification", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Notification\Controllers\Notification::index');
    $routes->post('add-notification-template', '\Modules\Notification\Controllers\Notification::add_notification_view');
    $routes->post('add-notification', '\Modules\Notification\Controllers\Notification::add_notification');
    $routes->post('remove-notification', '\Modules\Notification\Controllers\Notification::remove_notification');
    $routes->post('edit-notification-template/(:any)', '\Modules\Notification\Controllers\Notification::edit_notification_view');
    $routes->post('edit-notification/(:any)', '\Modules\Notification\Controllers\Notification::edit_notification');

    $routes->post('notification-details/(:any)', '\Modules\Notification\Controllers\Notification::notification_details');

    $routes->post('notification-status-change', '\Modules\Notification\Controllers\Notification::notification_status_change');
});
