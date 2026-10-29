<?php
/**
 * Define FlightTopRoutes Route
 */

$routes->group("flight-top-routes", ["filter" => "auth"], function ($routes) {
  $routes->match(['get'], '/', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::index'); 
  
  /** FlightTopRoutes markup routes*/ 
  $routes->get('get-airports', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::get_airports');
  $routes->post('flight-top-routes-view', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::FlightTopRoutesListView');
  $routes->post('flight-top-routes-saved', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::add_FlightTopRoutes_Saved');
  $routes->post('removed-flight-top-routes', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::remove_top_routes_List');
  $routes->post('flight-top-routes-change-routes', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::flight_top_routes_status_change');
  $routes->post('edit-flight-top-routes-template/(:any)', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::edit_flight_top_routes_view');
  $routes->post('edit-flight-top-routes-seved/(:any)', '\Modules\FlightTopRoutes\Controllers\FlightTopRoutes::edit_flight_top_routes_Seved');


 

 
});

