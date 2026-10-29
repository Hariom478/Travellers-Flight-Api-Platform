<?php
/**
 * Define Airservice Routes
 */

$routes->group("airservice/rest", ["filter" => "auth"], function ($routes) {
    $routes->post('search', '\Modules\Airservice\Controllers\Rest::Search');
    $routes->post('getcalendarfare', '\Modules\Airservice\Controllers\Rest::GetCalendarFare');
    $routes->post('farerule', '\Modules\Airservice\Controllers\Rest::FareRule');
    $routes->post('fareconfirmation', '\Modules\Airservice\Controllers\Rest::FareConfirmation');
    $routes->post('ssr', '\Modules\Airservice\Controllers\Rest::SSR');
    $routes->post('book', '\Modules\Airservice\Controllers\Rest::Book');
    $routes->post('ticket', '\Modules\Airservice\Controllers\Rest::Ticket');
    $routes->post('getbookingdetail', '\Modules\Airservice\Controllers\Rest::GetBookingDetail');
    $routes->post('cancelrequest', '\Modules\Airservice\Controllers\Rest::CancelRequest');
    $routes->post('cancelstatus', '\Modules\Airservice\Controllers\Rest::GetChangeRequestStatus');
    $routes->post('cancellationcharges', '\Modules\Airservice\Controllers\Rest::GetCancellationCharges');
    $routes->post('submitamendment', '\Modules\Airservice\Controllers\Rest::SubmitAmendment');
  
});

$routes->get('airservice/rest/get-logs/(:any)', '\Modules\Airservice\Controllers\Rest::GetLogs');
$routes->post('airservice/rest/generate-ticket-invoice', '\Modules\Airservice\Controllers\Rest::generateTicketInvoice');
$routes->post('airservice/rest/generate-wl-ticket-invoice', '\Modules\Airservice\Controllers\Rest::generateWLTicketInvoice');
$routes->post('airservice/rest/generate-credit-note', '\Modules\Airservice\Controllers\Rest::generateCreditNote');


