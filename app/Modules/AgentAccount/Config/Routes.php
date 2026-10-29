<?php
/**
 * Define WebPartnerAccount Routes
 */
$routes->group("agent-account", ["filter" => "auth"], function ($routes) {


    $routes->match(['post'], 'web-account-info/(:any)', '\Modules\AgentAccount\Controllers\AgentAccount::webAccountinfo');
    $routes->match(['get'], 'get-web-partner', '\Modules\AgentAccount\Controllers\AgentAccount::getWebpartner');
    $routes->match(['get'], 'get-agent-account-info', '\Modules\AgentAccount\Controllers\AgentAccount::web_partner_account_logs');
    $routes->match(['get'], 'get-agent-debit-info', '\Modules\AgentAccount\Controllers\AgentAccount::web_partner_debit_logs');
$routes->post('view-remark/(:any)', '\Modules\AgentAccount\Controllers\AgentAccount::view_remark');
    $routes->match(['get'], 'flight-credit-notes', '\Modules\AgentAccount\Controllers\AgentAccount::flight_credit_notes');
});
