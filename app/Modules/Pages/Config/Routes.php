<?php
/**
 * Define Pages Routes
 */
$routes->group("pages", function ($routes) {
    $routes->get('pages/(:any)', '\Modules\Pages\Controllers\Pages::pages');
    
});

$routes->group("", function ($routes) {
    $routes->get('contact-us', '\Modules\Pages\Controllers\Pages::contact_us');

});
