<?php

/**
 * Define Agent Routes
 */
$routes->group("agent", ["filter" => "auth"], function ($routes) {
    /** agent routes*/
    $routes->match(['get'], '/', '\Modules\Agent\Controllers\Agent::index');
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
    $routes->post('edit-agent-class/(:any)', '\Modules\Agent\Controllers\Agent::edit_agent_class');
    $routes->post('export-agent', '\Modules\Agent\Controllers\Agent::export_agent');
    $routes->get('get-agent', '\Modules\Agent\Controllers\Agent::getAgent');
    $routes->post('view-remark/(:any)', '\Modules\Agent\Controllers\Agent::view_remark');
    $routes->post('account-update-log-remark/(:any)', '\Modules\Agent\Controllers\Agent::accountUpdateLogRemark');
    /** agent  routes*/

    /** agent wallet routes*/
    $routes->post('virtual-topup/(:any)', '\Modules\Agent\Controllers\Agent::virtual_topup');
    $routes->post('virtual-topup-template/(:any)', '\Modules\Agent\Controllers\Agent::virtual_topup_view');
    $routes->post('virtual-debit/(:any)', '\Modules\Agent\Controllers\Agent::virtual_debit');
    $routes->post('virtual-debit-template/(:any)', '\Modules\Agent\Controllers\Agent::virtual_debit_view');

    $routes->post('credit-limit-template/(:any)', '\Modules\Agent\Controllers\Agent::credit_limit_view');
    $routes->post('virtual-creditlimit/(:any)', '\Modules\Agent\Controllers\Agent::virtual_creditlimit');
    $routes->get('agent-account-credit-logs/(:any)', '\Modules\Agent\Controllers\Agent::agent_account_credit_logs');
    /** agent wallet routes*/





    $routes->match(['get'], 'get-agent-debit-info', '\Modules\Agent\Controllers\Agent::getAgent_debit_logs');






});