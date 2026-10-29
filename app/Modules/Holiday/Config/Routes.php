<?php

/**
 * Define Holiday Routes
 */
$routes->group("holiday", ["filter" => "auth"], function ($routes) {
    /** holiday list routes*/
    $routes->get('/', '\Modules\Holiday\Controllers\HolidayBooking::index');
    $routes->get('holiday-result', '\Modules\Holiday\Controllers\HolidayBooking::holiday_result');

    $routes->get('holiday-details', '\Modules\Holiday\Controllers\HolidayBooking::holiday_details');
    $routes->get('search', '\Modules\Holiday\Controllers\HolidayBooking::search');
    $routes->post('holiday-check-search-validation', '\Modules\Holiday\Controllers\HolidayBooking::check_search_validation');
    $routes->post('holiday-query', '\Modules\Holiday\Controllers\HolidayBooking::holiday_query');
    $routes->get('auto-suggest', '\Modules\Holiday\Controllers\HolidayBooking::auto_suggest');

    $routes->post('fare-calender', '\Modules\Holiday\Controllers\HolidayBooking::fare_calender');

    $routes->post('holiday-block', '\Modules\Holiday\Controllers\HolidayBooking::holiday_block');

    $routes->get('booking-review', '\Modules\Holiday\Controllers\HolidayBooking::booking_review');
    $routes->post('validate-travellers', '\Modules\Holiday\Controllers\HolidayBooking::validate_travellers');


    $routes->get('payment-status/(:any)', '\Modules\Holiday\Controllers\HolidayBooking::payment_status');
    $routes->get('get-invoice-ticket', '\Modules\Holiday\Controllers\HolidayBooking::get_invoice_ticket');
    $routes->get('error', '\Modules\Holiday\Controllers\HolidayBooking::error');

    $routes->get('confirmation/(:any)', '\Modules\Holiday\Controllers\HolidayBooking::confirmation');

    $routes->post('get-invoice-ticket', '\Modules\Holiday\Controllers\HolidayBooking::get_invoice_ticket');


    $routes->post('update-web-partner-markup-discount', '\Modules\Holiday\Controllers\HolidayBooking::update_web_partner_markup_discount');


    $routes->get('booking-list', '\Modules\Holiday\Controllers\HolidayBooking::booking_list');

    $routes->get('holiday-booking-details/(:any)', '\Modules\Holiday\Controllers\HolidayBooking::holiday_booking_detail');

    $routes->post('raise-amendment', '\Modules\Holiday\Controllers\HolidayBooking::raiseAmendment');
    /**end holiday list routes*/
    $routes->get('amendments-details/(:any)', '\Modules\Holiday\Controllers\HolidayBooking::amendments_details');
    $routes->get('amendments', '\Modules\Holiday\Controllers\HolidayBooking::amendment_lists');
    $routes->get('refunds', '\Modules\Holiday\Controllers\HolidayBooking::refund_lists');
    /**end holiday list routes*/

     /** autocomplete routes*/
     $routes->get('get-destinations', '\Modules\Holiday\Controllers\HolidayBooking::get_destinations');
     $routes->get('get-themes', '\Modules\Holiday\Controllers\HolidayBooking::get_themes');
     $routes->get('get-packages', '\Modules\Holiday\Controllers\HolidayBooking::get_package');
});
