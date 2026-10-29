<?php 

/** Define Clear Temp Routes */

$routes->group("", function ($routes) {
    $routes->get('clear-temp-data', '\Modules\ClearTempData\Controllers\ClearTemp::index');
});