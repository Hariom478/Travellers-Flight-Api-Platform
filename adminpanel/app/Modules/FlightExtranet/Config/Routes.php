<?php
/**
 * Define FlightExtranet Routes
 */

$routes->group("private-fare", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], 'private-fare-list', '\Modules\FlightExtranet\Controllers\FlightExtranet::private_fare_list');
    $routes->match(['get','post'], 'add-trip-details', '\Modules\FlightExtranet\Controllers\FlightExtranet::addTripDetails');
    $routes->get('add-private-fare-page', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_private_fare_template');
    $routes->post('add-private-fare', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_private_fare');

    $routes->post('segment-details', '\Modules\FlightExtranet\Controllers\FlightExtranet::segment_details');
    $routes->post('segment-details-edit/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::segment_details_edit');
    $routes->get('edit-private-fare-page/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_private_fare_template');
    $routes->post('edit-private-fare/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_private_fare');

    $routes->post('remove-private-fare', '\Modules\FlightExtranet\Controllers\FlightExtranet::remove_private_fare');
    $routes->post('private-fare-status-change', '\Modules\FlightExtranet\Controllers\FlightExtranet::private_fare_status_change');
    $routes->get('private-fare-option/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::privateFareDetailList');
    $routes->post('add-private-fare-pnr-page/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::addPrivateFarePnrTemplate');


    $routes->get('fare-rule', '\Modules\FlightExtranet\Controllers\FlightExtranet::fare_rule');
    $routes->post('fare-rule-save', '\Modules\FlightExtranet\Controllers\FlightExtranet::fare_rule_save');
    $routes->get('edit-fare-rule/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_fare_rule');
    $routes->post('edit-rate-plan-update/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_rate_plan_update');
    $routes->post('remove-fare-rule', '\Modules\FlightExtranet\Controllers\FlightExtranet::remove_fare_rule');
    $routes->post('fare-rule-status-change', '\Modules\FlightExtranet\Controllers\FlightExtranet::fare_rule_status_change');
    $routes->get('fare-rule-list', '\Modules\FlightExtranet\Controllers\FlightExtranet::fare_rule_list');

    $routes->get('rate-plan', '\Modules\FlightExtranet\Controllers\FlightExtranet::rate_plan');
    $routes->post('add-rate-plan-template', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_rate_plan_template');
    $routes->post('add-rate-plan', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_rate_plan');
    $routes->post('edit-rate-plan-template/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_rate_plan_template');
    $routes->post('edit-rate-plan/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_rate_plan');
    $routes->post('remove-rate-plan', '\Modules\FlightExtranet\Controllers\FlightExtranet::remove_rate_plan');


    $routes->get('seat-allocation/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::seat_allocation');
    $routes->post('add-seat-allocation-template/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_seat_allocation_template');
    $routes->post('add-seat-allocation/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_seat_allocation');
    $routes->post('add-return-international-seat-allocation/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::add_return_international_seat_allocation');
    $routes->post('edit-seat-allocation-template/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_seat_allocation_template');
    $routes->post('edit-seat-allocation/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_seat_allocation');
    $routes->post('edit-return-international-seat-allocation/(:any)', '\Modules\FlightExtranet\Controllers\FlightExtranet::edit_return_international_seat_allocation');
    $routes->post('remove-seat-allocation', '\Modules\FlightExtranet\Controllers\FlightExtranet::remove_seat_allocation');




});

