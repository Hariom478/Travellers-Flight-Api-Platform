<?php
/**
 * Define Flight Routes
 */
require_once("UploadTicketRoutes.php");
$routes->group("flight", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Flight\Controllers\Flight::index');
    $routes->get('assign-update-flight-ticket/(:any)', '\Modules\Flight\Controllers\Flight::AssignUpdateflightTicket');

    /** flight Booking  routes Harish*/
    $routes->get('get-airports', '\Modules\Flight\Controllers\Flight::get_airports');
    $routes->get('get-airline', '\Modules\Flight\Controllers\Flight::get_airline');
    $routes->get('confirmation/(:any)', '\Modules\Flight\Controllers\Flight::confirmation');
    $routes->post('update-webpartner-markup-discount', '\Modules\Flight\Controllers\Flight::updateWebPartnerMarkupDiscount');
    $routes->get('get-invoice-ticket', '\Modules\Flight\Controllers\Flight::getInvoiceTicket');
    $routes->post('get-invoice-ticket', '\Modules\Flight\Controllers\Flight::getInvoiceTicket');
    /** flight Booking routes Harish*/
    
    /** flight Booking Lists routes Harish*/
    $routes->get('bookings', '\Modules\Flight\Controllers\Flight::bookingLists');
    $routes->get('pending-bookings', '\Modules\Flight\Controllers\FlightListings::bookingLists');
    $routes->get('cancelled-bookings', '\Modules\Flight\Controllers\FlightListings::bookingLists');
    $routes->get('bookings-search', '\Modules\Flight\Controllers\Flight::bookingLists');
    $routes->get('details/(:any)', '\Modules\Flight\Controllers\Flight::bookingDetails');
    $routes->post('raise-amendment', '\Modules\Flight\Controllers\Flight::raiseAmendment');
    $routes->get('amendment-itinerary', '\Modules\Flight\Controllers\Flight::amendmentItinerary');
    $routes->post('raise-amendment-type', '\Modules\Flight\Controllers\Flight::raiseAmendmentType');
    $routes->get('amendment-details/(:any)', '\Modules\Flight\Controllers\Flight::amendmentDetails');
    $routes->get('amendment-detail/(:any)', '\Modules\Flight\Controllers\Flight::amendmentDetail');
    $routes->post('flight-amendment-cancellation-charge', '\Modules\Flight\Controllers\Flight::flightAmendmentCancellationCharge');
    $routes->post('flight-refund-close', '\Modules\Flight\Controllers\Flight::flightRefundClose');
    /** flight Booking Lists routes Harish*/
    
    /** MarkupDiscount discount routes*/
    $routes->get('flight-discount', '\Modules\Flight\Controllers\Flight::flight_discount');
    $routes->post('flight-discount-view', '\Modules\Flight\Controllers\Flight::flight_discount_view');
    $routes->post('add-discount', '\Modules\Flight\Controllers\Flight::add_discount');
    $routes->post('discount-status-change', '\Modules\Flight\Controllers\Flight::discount_status_change');
    $routes->post('remove-discount', '\Modules\Flight\Controllers\Flight::remove_discount');
    $routes->post('edit-discount-template/(:any)', '\Modules\Flight\Controllers\Flight::edit_discount_view');
    $routes->post('edit-discount/(:any)', '\Modules\Flight\Controllers\Flight::edit_discount');
    $routes->post('flight-discount-details/(:any)', '\Modules\Flight\Controllers\Flight::flight_discount_details');
    /** MarkupDiscount discount routes*/

    /** MarkupDiscount markup routes*/
    $routes->get('flight-markup', '\Modules\Flight\Controllers\Flight::flight_markup');
    $routes->post('flight-markup-view', '\Modules\Flight\Controllers\Flight::flight_markup_view');
    $routes->post('add-markup', '\Modules\Flight\Controllers\Flight::add_markup');
    $routes->post('markup-status-change', '\Modules\Flight\Controllers\Flight::markup_status_change');
    $routes->post('remove-markup', '\Modules\Flight\Controllers\Flight::remove_markup');
    $routes->post('edit-markup-template/(:any)', '\Modules\Flight\Controllers\Flight::edit_markup_view');
    $routes->post('edit-markup/(:any)', '\Modules\Flight\Controllers\Flight::edit_markup');
    $routes->post('flight-markup-details/(:any)', '\Modules\Flight\Controllers\Flight::flight_markup_details');
    /** MarkupDiscount markup routes*/
    $routes->get('get-credit-note', '\Modules\Flight\Controllers\Flight::getCreditNote');
    $routes->get('booking-calender', '\Modules\Flight\Controllers\Flight::flight_booking_calender');
    /** flight Amendments Lists routes Praveen*/
    $routes->get('amendments-details/(:any)', '\Modules\Flight\Controllers\Flight::amendmentsDetails');
    $routes->get('flight-amendments', '\Modules\Flight\Controllers\Flight::flightAmendmentLists');
    $routes->post('amendment-status-change', '\Modules\Flight\Controllers\Flight::amendmentStatusChange');
    $routes->get('flight-refunds', '\Modules\Flight\Controllers\Flight::flightRefundLists');
    /** flight Amendments Lists routes Praveen*/

    $routes->match(['get'], 'get-update-flight-ticket-info/(:any)', '\Modules\Flight\Controllers\Flight::GetUpdateFlightTicketInfo');
    $routes->match(['post'], 'flight-update-ticket-info', '\Modules\Flight\Controllers\Flight::UpdateFlightTicketInfo');

    $routes->get('get-seat-bookings/(:any)', '\Modules\Flight\Controllers\FlightListings::get_seat_bookings');

});  
/**
 *Flight Ticket Update Routes End
 */

 $routes->group("flight-ticket-upload", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], 'get-update-flight-ticket-info/(:any)', '\Modules\Flight\Controllers\FlightTicketUpload::GetUpdateFlightTicketInfo');

    $routes->match(['post'], 'flight-update-ticket-info', '\Modules\Flight\Controllers\FlightTicketUpload::UpdateFlightTicketInfo');

});


 /**
 *Flight Offline Routes
 */

$routes->group("flightoffline", ["filter" => "auth"], function ($routes) {

    $routes->match(['get'], '/', '\Modules\Flight\Controllers\FlightOffline::index');

    $routes->post('flight-offline-view', '\Modules\Flight\Controllers\FlightOffline::flight_offline_view');

    $routes->post('flight-offline', '\Modules\Flight\Controllers\FlightOffline::flight_offline');

    $routes->post('edit-flight-offline-view/(:any)', '\Modules\Flight\Controllers\FlightOffline::edit_flight_offline_view');

    $routes->post('edit-flight-offline/(:any)', '\Modules\Flight\Controllers\FlightOffline::edit_flight_offline');

    $routes->post('remove-flight-offline', '\Modules\Flight\Controllers\FlightOffline::remove_flight_offline');

    $routes->post('flight-offline-status-change', '\Modules\Flight\Controllers\FlightOffline::flight_offline_status_change');

});


/**
 *Flight Settings Routes
 */

 $routes->group("flightsettings", ["filter" => "auth"], function ($routes) {
    /** flight airport routes*/
    $routes->match(['get'], '/', '\Modules\Flight\Controllers\FlightSettings::index');
    $routes->post('add-airport-template', '\Modules\Flight\Controllers\FlightSettings::add_airport_template');
    $routes->post('add-airport', '\Modules\Flight\Controllers\FlightSettings::add_airport');
    $routes->post('edit-airport-template/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airport_view');
    $routes->post('edit-airports/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airports');
    $routes->post('remove-airport', '\Modules\Flight\Controllers\FlightSettings::remove_airport');

    /** flight airline routes*/
    $routes->match(['get'], 'flight-airlines-list', '\Modules\Flight\Controllers\FlightSettings::airlines_list');
    $routes->post('add-airline-template', '\Modules\Flight\Controllers\FlightSettings::add_airline_template');
    $routes->post('add-airline', '\Modules\Flight\Controllers\FlightSettings::add_airline');
    $routes->post('remove-airline', '\Modules\Flight\Controllers\FlightSettings::remove_airline');
    $routes->post('edit-airline-template/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airline_view');
    $routes->post('edit-airline/(:any)', '\Modules\Flight\Controllers\FlightSettings::edit_airline');
});


/*API Flight Fare Type */

$routes->group("flightfaretype", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Flight\Controllers\FlightFareType::index');
    $routes->post('add-faretype-template', '\Modules\Flight\Controllers\FlightFareType::add_faretype_template');
    $routes->post('add-faretype', '\Modules\Flight\Controllers\FlightFareType::add_faretype');
    $routes->post('edit-faretype-template/(:any)', '\Modules\Flight\Controllers\FlightFareType::edit_faretype_view');
    $routes->post('edit-faretype/(:any)', '\Modules\Flight\Controllers\FlightFareType::edit_faretype');
    $routes->post('remove-faretype', '\Modules\Flight\Controllers\FlightFareType::remove_faretype');
});

/* API Flight Fare Type End */