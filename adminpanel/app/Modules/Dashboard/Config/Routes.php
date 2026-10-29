<?php

/**
 * Define  Routes For Dashboard
 */

$routes->group("dashboard", ["filter" => "auth"] , function($routes) {
    $routes->get('', '\Modules\Dashboard\Controllers\Dashboard::index');
});




?>