<?php

/**
 * Define  Routes For Dashboard
 */

$routes->group("dashboard", ["filter" => "auth"] , function($routes) {
    $routes->get('', '\Modules\Dashboard\Controllers\Dashboard::index');
    $routes->get('index', '\Modules\Dashboard\Controllers\Dashboard::index_index');
});




?>