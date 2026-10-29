<?php
/**
 * Define Slider Routes
 */
$routes->group("slider", ["filter" => "auth"], function ($routes) {
    $routes->match(['get'],'/', '\Modules\Slider\Controllers\Slider::index');
    $routes->post('add-slider-template', '\Modules\Slider\Controllers\Slider::add_slider_view');
    $routes->post('add-slider', '\Modules\Slider\Controllers\Slider::add_slider');
    $routes->post('remove-slider', '\Modules\Slider\Controllers\Slider::remove_slider');

    $routes->post('edit-slider-template/(:any)', '\Modules\Slider\Controllers\Slider::edit_slider_view');
    $routes->post('edit-slider/(:any)', '\Modules\Slider\Controllers\Slider::edit_slider');

    $routes->post('slider-status-change', '\Modules\Slider\Controllers\Slider::slider_status_change');

});
