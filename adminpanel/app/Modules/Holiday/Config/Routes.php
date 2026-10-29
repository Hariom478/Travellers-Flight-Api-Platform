<?php

/**
 * Define Holiday Routes
 */
$routes->group("holiday", ["filter" => "auth"], function ($routes) {
    /** holiday list routes*/

    $routes->get('get-google-location', '\Modules\Holiday\Controllers\Holiday::get_google_place_id');

    $routes->get('/', '\Modules\Holiday\Controllers\Holiday::index');
    $routes->get('add-holiday-list', '\Modules\Holiday\Controllers\Holiday::add_holiday_list');
    $routes->post('add-holiday-list-save', '\Modules\Holiday\Controllers\Holiday::add_holiday_list_save');
    $routes->get('edit-holiday-list-view/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_holiday_list_view');
    $routes->post('edit-holiday-list/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_holiday_list');
    $routes->post('remove-holiday-list', '\Modules\Holiday\Controllers\Holiday::remove_holiday_list');
    $routes->post('holiday-list-status-change', '\Modules\Holiday\Controllers\Holiday::holiday_list_status_change');
    /**end holiday list routes*/

    /** holiday gallery routes*/
    $routes->post('holiday-gallery/(:any)', '\Modules\Holiday\Controllers\Holiday::holiday_gallery');
    $routes->post('add-holiday-gallery/(:any)', '\Modules\Holiday\Controllers\Holiday::add_holiday_gallery');
    $routes->post('remove-holiday-gallery', '\Modules\Holiday\Controllers\Holiday::remove_holiday_gallery');
    $routes->post('holiday-gallery-status-change', '\Modules\Holiday\Controllers\Holiday::holiday_gallery_status_change');
    /**end holiday gallery routes*/

    /** holiday Itinerary routes */
    $routes->get('get-cities', '\Modules\Holiday\Controllers\Holiday::get_cities');
    $routes->get('holiday-itinerary-list/(:any)', '\Modules\Holiday\Controllers\Holiday::holiday_itinerary_list');
    $routes->get('add-holiday-itinerary/(:any)', '\Modules\Holiday\Controllers\Holiday::add_holiday_itinerary');
    $routes->post('add-itinerary-flight-template', '\Modules\Holiday\Controllers\Holiday::add_itinerary_flight_template');
    $routes->post('add-itinerary-hotel-template', '\Modules\Holiday\Controllers\Holiday::add_itinerary_hotel_template');
    $routes->post('add-itinerary-transfer-template', '\Modules\Holiday\Controllers\Holiday::add_itinerary_transfer_template');
    $routes->post('add-holiday-itinerary/(:any)', '\Modules\Holiday\Controllers\Holiday::add_holiday_itinerary');
    $routes->post('update-holiday-itinerary/(:any)', '\Modules\Holiday\Controllers\Holiday::update_holiday_itinerary');
    $routes->post('remove-holiday-itinerary', '\Modules\Holiday\Controllers\Holiday::remove_holiday_itinerary');
    $routes->post('holiday-itinerary-status-change', '\Modules\Holiday\Controllers\Holiday::holiday_itinerary_status_change');
    /** end holiday Itinerary routes */

    /** holiday price routes*/
    $routes->get('holiday-price-list/(:any)', '\Modules\Holiday\Controllers\Holiday::holiday_price_list');
    $routes->post('add-holiday-price-template/(:any)', '\Modules\Holiday\Controllers\Holiday::add_holiday_price_view');
    $routes->post('add-holiday-price/(:any)', '\Modules\Holiday\Controllers\Holiday::add_holiday_price');
    $routes->post('edit-holiday-price-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_holiday_price_view');
    $routes->post('edit-holiday-price/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_holiday_price');
    $routes->post('remove-holiday-price', '\Modules\Holiday\Controllers\Holiday::remove_holiday_price');
    /**end holiday price routes*/

    /** holiday theme routes*/
    $routes->get('settings', '\Modules\Holiday\Controllers\Holiday::themes_list');
    $routes->get('themes-list', '\Modules\Holiday\Controllers\Holiday::themes_list');
    $routes->post('add-themes-template', '\Modules\Holiday\Controllers\Holiday::add_themes_view');
    $routes->post('add-themes', '\Modules\Holiday\Controllers\Holiday::add_themes');
    $routes->post('remove-themes', '\Modules\Holiday\Controllers\Holiday::remove_themes');
    $routes->post('edit-themes-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_themes_view');
    $routes->post('edit-themes/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_themes');
    $routes->post('themes-status-change', '\Modules\Holiday\Controllers\Holiday::themes_status_change');
    /**end holiday theme routes*/

    /** holiday destination routes*/
    $routes->get('destination-list', '\Modules\Holiday\Controllers\Holiday::destination_list');
    $routes->post('add-destination-template', '\Modules\Holiday\Controllers\Holiday::add_destination_view');
    $routes->post('add-destination', '\Modules\Holiday\Controllers\Holiday::add_destination');
    $routes->post('remove-destination', '\Modules\Holiday\Controllers\Holiday::remove_destination');
    $routes->post('edit-destination-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_destination_view');
    $routes->post('edit-destination/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_destination');
    $routes->post('destination-status-change', '\Modules\Holiday\Controllers\Holiday::destination_status_change');
    /**end holiday destination routes*/

    /** holiday inclusion routes*/
    $routes->get('inclusion-list', '\Modules\Holiday\Controllers\Holiday::inclusion_list');
    $routes->post('add-inclusion-template', '\Modules\Holiday\Controllers\Holiday::add_inclusion_view');
    $routes->post('add-inclusion', '\Modules\Holiday\Controllers\Holiday::add_inclusion');
    $routes->post('remove-inclusion', '\Modules\Holiday\Controllers\Holiday::remove_inclusion');
    $routes->post('edit-inclusion-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_inclusion_view');
    $routes->post('edit-inclusion/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_inclusion');
    $routes->post('inclusion-status-change', '\Modules\Holiday\Controllers\Holiday::inclusion_status_change');
    /**end holiday inclusion routes*/

    /** holiday exclusion routes*/
    $routes->get('exclusion-list', '\Modules\Holiday\Controllers\Holiday::exclusion_list');
    $routes->post('add-exclusion-template', '\Modules\Holiday\Controllers\Holiday::add_exclusion_view');
    $routes->post('add-exclusion', '\Modules\Holiday\Controllers\Holiday::add_exclusion');
    $routes->post('remove-exclusion', '\Modules\Holiday\Controllers\Holiday::remove_exclusion');
    $routes->post('edit-exclusion-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_exclusion_view');
    $routes->post('edit-exclusion/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_exclusion');
    $routes->post('exclusion-status-change', '\Modules\Holiday\Controllers\Holiday::exclusion_status_change');
    /**end holiday exclusion routes*/

    /** booking routes*/
    $routes->get('booking-list', '\Modules\Holiday\Controllers\Holiday::booking_list');
    $routes->get('confirmation/(:any)', '\Modules\Holiday\Controllers\Holiday::confirmation');
    $routes->match(['get','post'],'get-invoice-ticket', '\Modules\Holiday\Controllers\Holiday::get_invoice_ticket');

    /** autocomplete routes*/
    $routes->get('get-destinations', '\Modules\Holiday\Controllers\Holiday::get_destinations');
    $routes->get('get-themes', '\Modules\Holiday\Controllers\Holiday::get_themes');
    $routes->post('raise-amendment', '\Modules\Holiday\Controllers\Holiday::raiseAmendment');
    $routes->get('query-list', '\Modules\Holiday\Controllers\Holiday::query_list'); 
    $routes->post('export-query', '\Modules\Holiday\Controllers\Holiday::export_query');
    $routes->get('holiday-booking-details/(:any)', '\Modules\Holiday\Controllers\Holiday::holiday_booking_detail');



    $routes->get('amendments', '\Modules\Holiday\Controllers\Holiday::amendment_lists');




    /**  Amendments Lists routes praveen*/
    $routes->get('amendments-details/(:any)', '\Modules\Holiday\Controllers\Holiday::amendments_details');
    $routes->get('hotel-amendments', '\Modules\Holiday\Controllers\Holiday::amendment_lists');
    $routes->post('amendment-cancellation-charge', '\Modules\Holiday\Controllers\Holiday::amendment_cancellation_charge');
    $routes->post('amendment-status-change', '\Modules\Holiday\Controllers\Holiday::amendment_status_change');
    $routes->post('raise-amendment', '\Modules\Holiday\Controllers\Holiday::raiseAmendment');

    $routes->post('export-holiday-amendments', '\Modules\Holiday\Controllers\Holiday::export_amendments');


    $routes->get('refunds', '\Modules\Holiday\Controllers\Holiday::refund_lists');
    $routes->post('refund-close', '\Modules\Holiday\Controllers\Holiday::refund_close');
    /**  Refund Lists routes praveen*/

    /** */
    $routes->match(['get','post'],'holiday-settings', '\Modules\Holiday\Controllers\Holiday::holiday_settings');


    $routes->post('holiday-update-voucher-info', '\Modules\Holiday\Controllers\Holiday::UpdateHolidayVoucherInfo');
    $routes->get('assign-update-holiday-ticket/(:any)', '\Modules\Holiday\Controllers\Holiday::AssignUpdateHolidayTicket');
    $routes->get('get-update-holiday-voucher-info/(:any)', '\Modules\Holiday\Controllers\Holiday::getUpdateholidayVoucherInfo');

    $routes->get('get-credit-note', '\Modules\Holiday\Controllers\Holiday::getCreditNote');



    /** holiday  markup routes start */
    $routes->get('holiday-markup-list', '\Modules\Holiday\Controllers\Holiday::holiday_markup_list');
    $routes->post('holiday-markup-view', '\Modules\Holiday\Controllers\Holiday::holiday_markup_view');
    $routes->post('add-holiday-markup', '\Modules\Holiday\Controllers\Holiday::add_holiday_markup');
    $routes->post('holiday-markup-status-change', '\Modules\Holiday\Controllers\Holiday::holiday_markup_status_change');
    $routes->post('remove-holiday-markup', '\Modules\Holiday\Controllers\Holiday::remove_holiday_markup');
    $routes->post('edit-admin-holiday-markup-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_admin_holiday_markup_template');
    $routes->post('edit-admin-holiday-markup/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_admin_holiday_markup');
    /** end holiday  markup routes*/


    /** holiday  discount routes start */
    $routes->get('holiday-discount-list', '\Modules\Holiday\Controllers\Holiday::holiday_discount_list');
    $routes->post('holiday-discount-view', '\Modules\Holiday\Controllers\Holiday::holiday_discount_view');
    $routes->post('add-holiday-discount', '\Modules\Holiday\Controllers\Holiday::add_holiday_discount');
    $routes->post('holiday-discount-status-change', '\Modules\Holiday\Controllers\Holiday::holiday_discount_status_change');
    $routes->post('remove-holiday-discount', '\Modules\Holiday\Controllers\Holiday::remove_holiday_discount');
    $routes->post('edit-admin-holiday-discount-template/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_admin_holiday_discount_template');
    $routes->post('edit-admin-holiday-discount/(:any)', '\Modules\Holiday\Controllers\Holiday::edit_admin_holiday_discount');
    /** end holiday  discount routes */


    $routes->get('get-packages', '\Modules\Holiday\Controllers\Holiday::get_package');



});


$routes->group("holiday-booking", ["filter" => "auth"], function ($routes) { 
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
    // $routes->get('amendments-details/(:any)', '\Modules\Holiday\Controllers\HolidayBooking::amendments_details');
    // $routes->get('amendments', '\Modules\Holiday\Controllers\HolidayBooking::amendment_lists');
    // $routes->get('refunds', '\Modules\Holiday\Controllers\HolidayBooking::refund_lists');

    $routes->get('get-credit-note', '\Modules\Holiday\Controllers\HolidayBooking::getCreditNote');
});