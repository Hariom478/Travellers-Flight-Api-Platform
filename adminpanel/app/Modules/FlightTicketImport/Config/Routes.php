<?php
/**
 * Define Flight Ticket Import Routes
 */

$routes->group("flight-ticket-import", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::index');
    $routes->match(['get'], 'segment-passenger-detail', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::segmentPassengerDetail');
    $routes->match(['post'], 'check-pnr', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::checkPNR'); 
    $routes->match(['post'], 'save-passenger', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::savePassenger'); 
    $routes->match(['post'], 'store-segement-info', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::storeSegementInfo'); 
    $routes->match(['get'], 'review-detail', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::reviewDetail'); 
    $routes->match(['get'], 'generate-ticket', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::generateTicket'); 
    $routes->match(['get','post'], 'import-pnr-details', '\Modules\FlightTicketImport\Controllers\FlightTicketImport::ImportPNRDetails'); 
});



