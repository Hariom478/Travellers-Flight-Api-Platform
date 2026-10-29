<?php

/**
 * Define Carservice Routes
 */

$routes->group("holidayservice/rest", ["filter" => "auth"], function ($routes) {
   $routes->get('themes',"\Modules\Holidayservice\Controllers\Rest::holiday_themes");
   $routes->get('get-themes-by-slug',"\Modules\Holidayservice\Controllers\Rest::get_themes_by_slug");
   $routes->get('get-destinations-by-slug',"\Modules\Holidayservice\Controllers\Rest::get_destinations_by_slug");
    $routes->get('holiday-list',"\Modules\Holidayservice\Controllers\Rest::holiday_list");
    $routes->get('holiday-details',"\Modules\Holidayservice\Controllers\Rest::holiday_details");
    $routes->get('search',"\Modules\Holidayservice\Controllers\Rest::search");

    $routes->post('block-holiday',"\Modules\Holidayservice\Controllers\Rest::block_holiday");

    $routes->post('holiday-block-detail',"\Modules\Holidayservice\Controllers\Rest::holiday_block_detail");
    $routes->post('submitamendment', '\Modules\Holidayservice\Controllers\Rest::SubmitAmendment');
});
$routes->post('holidayservice/rest/generate-voucher-invoice', '\Modules\Holidayservice\Controllers\Rest::generateVoucherInvoice');
$routes->post('holidayservice/rest/generate-wl-voucher-invoice', '\Modules\Holidayservice\Controllers\Rest::generateWLVoucherInvoice');
$routes->post('holidayservice/rest/generate-credit-note', '\Modules\Holidayservice\Controllers\Rest::generateCreditNote');
$routes->post('holidayservice/rest/generate-wl-credit-note', '\Modules\Holidayservice\Controllers\Rest::generateWLCreditNote');

$routes->post('holidayservice/rest/adminsubmitamendment', '\Modules\Holidayservice\Controllers\Rest::SubmitAmendmentAdmin');