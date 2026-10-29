<?php
/**
 * Define MarkupDiscount Routes
 */

$routes->group("markup-discount", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'], '/', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::index');
    $routes->get('get-airports', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::get_airports');
    $routes->get('get-airline', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::get_airline');

    /** MarkupDiscount discount routes*/
    $routes->get('flight-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_discount');
    $routes->post('flight-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_discount_view');
    $routes->post('add-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_discount');
    $routes->post('discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::discount_status_change');
    $routes->post('remove-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_discount');
    $routes->post('edit-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_discount_view');
    $routes->post('edit-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_discount');
    $routes->post('flight-discount-details/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_discount_details');
    /** MarkupDiscount discount routes*/

    /** MarkupDiscount markup routes*/
    $routes->get('flight-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_markup');
    $routes->post('flight-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_markup_view');
    $routes->post('add-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_markup');
    $routes->post('markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::markup_status_change');
    $routes->post('remove-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_markup');
    $routes->post('edit-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_markup_view');
    $routes->post('edit-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_markup');
    $routes->post('flight-markup-details/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::flight_markup_details');
    /** MarkupDiscount markup routes*/


    /** CarExtranet  markup routes start */
    $routes->get('car-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_markup_list');
    $routes->post('car-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_markup_view');
    $routes->post('add-car-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_car_markup');
    $routes->post('car-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_markup_status_change');
    $routes->post('remove-car-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_car_markup');
    $routes->post('edit-admin-car-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_car_markup_template');
    $routes->post('edit-admin-car-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_car_markup');
    /** end CarExtranet  markup routes*/


    /** CarExtranet  discount routes start */
    $routes->get('car-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_discount_list');
    $routes->post('car-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_discount_view');
    $routes->post('add-car-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_car_discount');
    $routes->post('car-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::car_discount_status_change');
    $routes->post('remove-car-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_car_discount');
    $routes->post('edit-admin-car-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_car_discount_template');
    $routes->post('edit-admin-car-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_car_discount');
    /** end CarExtranet  discount routes */

    /** Bus  markup routes start */
    $routes->get('bus-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_markup_list');
    $routes->post('bus-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_markup_view');
    $routes->post('add-bus-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_bus_markup');
    $routes->post('bus-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_markup_status_change');
    $routes->post('remove-bus-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_bus_markup');
    $routes->post('edit-admin-bus-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_bus_markup_template');
    $routes->post('edit-admin-bus-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_bus_markup');
    /** end Bus  markup routes*/


    /** Bus  discount routes start */
    $routes->get('bus-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_discount_list');
    $routes->post('bus-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_discount_view');
    $routes->post('add-bus-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_bus_discount');
    $routes->post('bus-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::bus_discount_status_change');
    $routes->post('remove-bus-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_bus_discount');
    $routes->post('edit-admin-bus-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_bus_discount_template');
    $routes->post('edit-admin-bus-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_bus_discount');
    /** end Bus  discount routes */

    /** Hotel  markup routes start */
    $routes->get('hotel-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_markup_list');
    $routes->post('hotel-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_markup_view');
    $routes->post('add-hotel-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_hotel_markup');
    $routes->post('hotel-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_markup_status_change');
    $routes->post('remove-hotel-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_hotel_markup');
    $routes->post('edit-admin-hotel-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_hotel_markup_template');
    $routes->post('edit-admin-hotel-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_hotel_markup');
    /** end Hotel  markup routes*/


    /** Hotel  discount routes start */
    $routes->get('hotel-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_discount_list');
    $routes->post('hotel-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_discount_view');
    $routes->post('add-hotel-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_hotel_discount');
    $routes->post('hotel-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::hotel_discount_status_change');
    $routes->post('remove-hotel-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_hotel_discount');
    $routes->post('edit-admin-hotel-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_hotel_discount_template');
    $routes->post('edit-admin-hotel-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_hotel_discount');
    /** end Hotel  discount routes */


    /** visa  markup routes start */
    $routes->get('visa-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_markup_list');
    $routes->post('visa-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_markup_view');
   
    $routes->post('add-visa-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_visa_markup');
    $routes->post('visa-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_markup_status_change');
    $routes->post('remove-visa-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_visa_markup');
    $routes->post('edit-admin-visa-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_visa_markup_template');
    $routes->post('edit-admin-visa-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_visa_markup');
    /** end visa  markup routes*/


    /** visa  discount routes start */
    $routes->get('visa-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_discount_list');
    $routes->post('visa-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_discount_view');
    $routes->post('add-visa-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_visa_discount');
    $routes->post('visa-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::visa_discount_status_change');
    $routes->post('remove-visa-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_visa_discount');
    $routes->post('edit-admin-visa-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_visa_discount_template');
    $routes->post('edit-admin-visa-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_visa_discount');
    /** end visa  discount routes*/


    /** cruise  markup routes start */
    $routes->get('cruise-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_markup_list');
    $routes->post('cruise-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_markup_view');
    $routes->post('add-cruise-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_cruise_markup');
    $routes->post('cruise-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_markup_status_change');
    $routes->post('remove-cruise-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_cruise_markup');
    $routes->post('edit-admin-cruise-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_cruise_markup_template');
    $routes->post('edit-admin-cruise-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_cruise_markup');
    /** end cruise  markup routes*/


    /** cruise  discount routes start */
    $routes->get('cruise-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_discount_list');
    $routes->post('cruise-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_discount_view');
    $routes->post('add-cruise-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_cruise_discount');
    $routes->post('cruise-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::cruise_discount_status_change');
    $routes->post('remove-cruise-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_cruise_discount');
    $routes->post('edit-admin-cruise-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_cruise_discount_template');
    $routes->post('edit-admin-cruise-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_cruise_discount');
    /** end cruise  discount routes */


    /** holiday  markup routes start */
    $routes->get('holiday-markup-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_markup_list');
    $routes->post('holiday-markup-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_markup_view');
    $routes->post('add-holiday-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_holiday_markup');
    $routes->post('holiday-markup-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_markup_status_change');
    $routes->post('remove-holiday-markup', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_holiday_markup');
    $routes->post('edit-admin-holiday-markup-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_holiday_markup_template');
    $routes->post('edit-admin-holiday-markup/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_holiday_markup');
    /** end holiday  markup routes*/


    /** holiday  discount routes start */
    $routes->get('holiday-discount-list', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_discount_list');
    $routes->post('holiday-discount-view', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_discount_view');
    $routes->post('add-holiday-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::add_holiday_discount');
    $routes->post('holiday-discount-status-change', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::holiday_discount_status_change');
    $routes->post('remove-holiday-discount', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::remove_holiday_discount');
    $routes->post('edit-admin-holiday-discount-template/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_holiday_discount_template');
    $routes->post('edit-admin-holiday-discount/(:any)', '\Modules\MarkupDiscount\Controllers\MarkupDiscount::edit_admin_holiday_discount');
    /** end holiday  discount routes */

});

