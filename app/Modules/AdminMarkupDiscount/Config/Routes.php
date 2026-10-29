<?php

/**
 * Define AdminMarkupDiscount Routes
 */

$routes->group("markup-discount", ["filter" => "auth"], function ($routes) {
  $routes->get('get-airports', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::get_airports');
  $routes->get('get-airline', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::get_airline');


  /** AdminMarkupDiscount Flight markup routes start*/
  $routes->get('flight-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::flight_markup');
  $routes->post('flight-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::flight_markup_view');
  $routes->post('add-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_markup');
  $routes->post('markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::markup_status_change');
  $routes->post('remove-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_markup');
  $routes->post('edit-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_markup_view');
  $routes->post('edit-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_markup');
  $routes->post('flight-markup-details/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::flight_markup_details');
  /** AdminMarkupDiscount Flight markup routes end*/

  /** AdminMarkupDiscount Hotel markup routes start*/
  $routes->get('hotel-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::hotel_markup');
  $routes->post('hotel-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::hotel_markup_view');
  $routes->post('add-hotel-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_hotel_markup');
  $routes->post('hotel-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::hotel_markup_status_change');
  $routes->post('remove-hotel-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_hotel_markup');
  $routes->post('edit-hotel-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_hotel_markup_view');
  $routes->post('edit-hotel-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_hotel_markup');
  $routes->post('hotel-markup-details/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::hotel_markup_details');
  /** AdminMarkupDiscount Flight markup routes end*/

  /** AdminMarkupDiscount Bus  markup routes start */
  $routes->get('bus-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::bus_markup_list');
  $routes->post('bus-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::bus_markup_view');
  $routes->post('add-bus-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_bus_markup');
  $routes->post('bus-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::bus_markup_status_change');
  $routes->post('remove-bus-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_bus_markup');
  $routes->post('edit-bus-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_bus_markup_template');
  $routes->post('edit-bus-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_bus_markup');
  /** end AdminMarkupDiscount Bus  markup routes*/

  /** AdminMarkupDiscount Visa markup routes start*/
  $routes->get('visa-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::flight_markup');
  /** AdminMarkupDiscount Visa markup routes end*/

  /** AdminMarkupDiscount cruise markup routes start */
  $routes->get('cruise-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::cruise_markup_list');
  $routes->post('cruise-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::cruise_markup_view');
  $routes->post('add-cruise-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_cruise_markup');
  $routes->post('cruise-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::cruise_markup_status_change');
  $routes->post('remove-cruise-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_cruise_markup');
  $routes->post('edit-cruise-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_cruise_markup_template');
  $routes->post('edit-cruise-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_cruise_markup');
  /** AdminMarkupDiscount cruise markup routes end*/

  /** AdminMarkupDiscount Holiday markup routes start */
  $routes->get('holiday-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::holiday_markup_list');
  $routes->post('holiday-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::holiday_markup_view');
  $routes->post('add-holiday-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_holiday_markup');
  $routes->post('holiday-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::holiday_markup_status_change');
  $routes->post('remove-holiday-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_holiday_markup');
  $routes->post('edit-holiday-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_holiday_markup_template');
  $routes->post('edit-holiday-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_holiday_markup');
  /** AdminMarkupDiscount Holiday markup routes end*/


  /**
   * TourguideMarkup routes start
   */
  $routes->get('tourguide-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::tourguide_markup_list');
  $routes->post('tourguide-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::tourguide_markup_view');
  $routes->post('add-tourguide-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_tourguide_markup');
  $routes->post('edit-tourguide-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_tourguide_markup_template');
  $routes->post('edit-tourguide-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_tourguide_markup');
  $routes->post('tourguide-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::tourguide_markup_status_change');
  $routes->post('remove-tourguide-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_tourguide_markup');

  /**
   * TourguideMarkup routes end
   */


   $routes->get('activities-markup-list', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::activities_markup_list');
   $routes->post('activities-markup-view', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::activities_markup_view');
   $routes->post('add-activities-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::add_activities_markup');
   $routes->post('activities-markup-status-change', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::activities_markup_status_change');
   $routes->post('remove-activities-markup', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::remove_activities_markup');
   $routes->post('edit-admin-activities-markup-template/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_admin_activities_markup_template');
   $routes->post('edit-admin-activities-markup/(:any)', '\Modules\AdminMarkupDiscount\Controllers\AdminMarkupDiscount::edit_admin_activities_markup');









});
