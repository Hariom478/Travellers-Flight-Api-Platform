<?php

/**
 * Define GroupTicket Routes
 */
$routes->group("groupticket", ["filter" => "auth"], function ($routes) {
    /** GroupTicket list routes*/
    $routes->get('/', '\Modules\GroupTicket\Controllers\GroupTicket::index');
    $routes->post('pax-info','\Modules\GroupTicket\Controllers\GroupTicket::paxInfo');
    $routes->post('generate-ticket','\Modules\GroupTicket\Controllers\GroupTicket::generateTicket');
    $routes->get('request-list','\Modules\GroupTicket\Controllers\GroupTicket::group_ticket_list');
    $routes->post('group-ticket-view/(:any)','\Modules\GroupTicket\Controllers\GroupTicket::group_ticket_view');
});
