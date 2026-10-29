<?php
/**
 * Define Agent Routes
 */
$routes->group("agent", ["filter" => "auth"], function ($routes) {
    /** agent routes*/
    $routes->match(['get'],'/', '\Modules\Agent\Controllers\Agent::index');
    $routes->post('add-agent-template', '\Modules\Agent\Controllers\Agent::add_agent_view');
    $routes->post('add-agent', '\Modules\Agent\Controllers\Agent::add_agent');
    $routes->post('remove-agent', '\Modules\Agent\Controllers\Agent::remove_agent');
    $routes->post('edit-agent-template/(:any)', '\Modules\Agent\Controllers\Agent::edit_agent_view');
    $routes->post('edit-agent/(:any)', '\Modules\Agent\Controllers\Agent::edit_agent');
    $routes->post('agent-status-change', '\Modules\Agent\Controllers\Agent::agent_status_change');
    $routes->post('agent-details/(:any)', '\Modules\Agent\Controllers\Agent::agent_details');
    $routes->get('agent-account-logs/(:any)', '\Modules\Agent\Controllers\Agent::agent_account_logs');
    $routes->post('change-agent-password', '\Modules\Agent\Controllers\Agent::change_agent_password');
    $routes->post('agent-class', '\Modules\Agent\Controllers\Agent::agent_class');
    $routes->post('add-agent-class', '\Modules\Agent\Controllers\Agent::add_agent_class');
    $routes->get('remove-agent-class/(:any)', '\Modules\Agent\Controllers\Agent::remove_agent_class');
    $routes->post('export-agent', '\Modules\Agent\Controllers\Agent::export_agent');
    /** agent  routes*/

    /** agent wallet routes*/
    $routes->post('virtual-topup/(:any)', '\Modules\Agent\Controllers\Agent::virtual_topup');
    $routes->post('virtual-topup-template/(:any)', '\Modules\Agent\Controllers\Agent::virtual_topup_view');
    $routes->post('virtual-debit/(:any)', '\Modules\Agent\Controllers\Agent::virtual_debit');
    $routes->post('virtual-debit-template/(:any)', '\Modules\Agent\Controllers\Agent::virtual_debit_view');
    /** agent wallet routes*/

    /** flight discount routes*/
    $routes->get('get-airports', '\Modules\Agent\Controllers\Agent::get_airports');
    $routes->get('get-airline', '\Modules\Agent\Controllers\Agent::get_airline');

    $routes->get('flight-discount', '\Modules\Agent\Controllers\Agent::flight_discount');
    $routes->post('flight-discount-view', '\Modules\Agent\Controllers\Agent::flight_discount_view');
    $routes->post('add-flight-discount', '\Modules\Agent\Controllers\Agent::add_flight_discount');
    $routes->post('flight-discount-status-change', '\Modules\Agent\Controllers\Agent::flight_discount_status_change');
    $routes->post('remove-flight-discount', '\Modules\Agent\Controllers\Agent::remove_flight_discount');
    $routes->post('edit-flight-discount-template/(:any)', '\Modules\Agent\Controllers\Agent::edit_flight_discount_view');
    $routes->post('edit-flight-discount/(:any)', '\Modules\Agent\Controllers\Agent::edit_flight_discount');
    $routes->post('flight-discount-details/(:any)', '\Modules\Agent\Controllers\Agent::flight_discount_details');
    /** flight discount routes*/

    /** flight markup routes*/
    $routes->get('flight-markup', '\Modules\Agent\Controllers\Agent::flight_markup');
    $routes->post('flight-markup-view', '\Modules\Agent\Controllers\Agent::flight_markup_view');
    $routes->post('add-flight-markup', '\Modules\Agent\Controllers\Agent::add_flight_markup');
    $routes->post('flight-markup-status-change', '\Modules\Agent\Controllers\Agent::flight_markup_status_change');
    $routes->post('remove-flight-markup', '\Modules\Agent\Controllers\Agent::remove_flight_markup');
    $routes->post('edit-flight-markup-template/(:any)', '\Modules\Agent\Controllers\Agent::edit_flight_markup_view');
    $routes->post('edit-flight-markup/(:any)', '\Modules\Agent\Controllers\Agent::edit_flight_markup');
    $routes->post('flight-markup-details/(:any)', '\Modules\Agent\Controllers\Agent::flight_markup_details');
    /** flight markup routes*/


    /** bus markup routes*/
    $routes->get('bus-markup', '\Modules\Agent\Controllers\Agent::bus_markup');
    $routes->post('bus-markup-view', '\Modules\Agent\Controllers\Agent::bus_markup_view');
    $routes->post('add-bus-markup', '\Modules\Agent\Controllers\Agent::add_bus_markup');
    $routes->post('bus-markup-status-change', '\Modules\Agent\Controllers\Agent::bus_markup_status_change');
    $routes->post('remove-bus-markup', '\Modules\Agent\Controllers\Agent::remove_bus_markup');
    $routes->post('edit-bus-markup-template/(:any)', '\Modules\Agent\Controllers\Agent::edit_bus_markup_view');
    $routes->post('edit-bus-markup/(:any)', '\Modules\Agent\Controllers\Agent::edit_bus_markup');
    /** bus markup routes*/

    $routes->post('view-remark/(:any)', '\Modules\Agent\Controllers\Agent::view_remark');

});
