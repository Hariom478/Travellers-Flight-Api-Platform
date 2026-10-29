<?php
/**
 * Define Query Routes
 */

$routes->group("query", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'],'/', '\Modules\Query\Controllers\Query::index'); 
    $routes->post('remove-query', '\Modules\Query\Controllers\Query::remove_query'); 

    $routes->post('export-query', '\Modules\Query\Controllers\Query::export_query');
    

}); 