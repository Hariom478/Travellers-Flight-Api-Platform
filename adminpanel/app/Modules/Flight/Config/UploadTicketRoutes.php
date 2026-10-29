<?php /**
 *Flight Ticket Upload Routes End
 */

$routes->group("flight-ticket-upload", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\Flight\Controllers\FlightTicketUpload::ticket_upload');
    $routes->match(['post'], 'segment-details', '\Modules\Flight\Controllers\FlightTicketUpload::segment_details');
    $routes->match(['post'], 'add-trip-details', '\Modules\Flight\Controllers\FlightTicketUpload::addTripDetails');
    $routes->match(['post'], 'store-segement-info', '\Modules\Flight\Controllers\FlightTicketUpload::storeSegementInfo');
    $routes->match(['post'], 'passenger-details', '\Modules\Flight\Controllers\FlightTicketUpload::passenger_details');
    $routes->match(['get'], 'segment-passenger-detail', '\Modules\Flight\Controllers\FlightTicketUpload::segmentPassengerDetail');
    $routes->match(['post'], 'save-passenger', '\Modules\Flight\Controllers\FlightTicketUpload::savePassenger');
    $routes->match(['get'], 'update-flight-confirmation-number', '\Modules\Flight\Controllers\FlightTicketUpload::UpdateFlightConfirmationNumber');
    $routes->match(['get'], 'review-detail', '\Modules\Flight\Controllers\FlightTicketUpload::reviewDetail');
    $routes->match(['get'], 'generate-ticket', '\Modules\Flight\Controllers\FlightTicketUpload::generateUploadTicket');
});


/**
 *Flight Ticket Upload Routes End
 */
 ?>