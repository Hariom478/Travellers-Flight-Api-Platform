<?php
$router = service('router');
$class_name = $router->controllerName();
$classparm = explode("\\", $class_name);
$controller = end($classparm);

// pr($controller); 

?>
<div class="hero-banner">
   <?php if (isset($slider_list) && !empty($slider_list)): ?>
      <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
         <?php foreach ($slider_list as $key => $slider): ?>
            <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>"
               style="background-image:url(<?php echo root_url . 'uploads/sliders/thumbnail/' . $slider['slider_image'] ?>);">
               <img class="d-block w-100"
                  src="<?php echo root_url . 'uploads/sliders/thumbnail/' . $slider['slider_image'] ?>"
                  alt="<?= (!empty($slider['slider_text1'])) ? $slider['slider_text1'] : '' ?>">
            </div>
         <?php endforeach; ?>

      </div>
   <?php endif; ?>
</div>
<section class="search_form_section">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="search_form_box">
               <div class="searchform_home">
                  <ul class=" search_tabs">
                     <?php if (whitelabel['flight_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("Flight"); ?> flights">
                           <a href="<?php echo site_url('flight') ?> ">
                              <span><i class="fa-solid fa-plane"></i> Flight </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['hotel_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("Hotel"); ?>">
                           <a href="<?php echo site_url('hotel') ?> ">
                              <span> <i class="fa-solid fa-building"></i> Hotel </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['bus_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("Bus"); ?>">
                           <a href="<?php echo site_url('bus') ?> ">
                              <span><i class="fa-solid fa-bus"></i> Bus </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['holiday_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("HolidayBooking"); ?>">
                           <a href="<?php echo site_url('holiday') ?> ">
                              <span> <i class="fa-solid fa-umbrella-beach"></i> Holidays </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['visa_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("VisaBooking"); ?>">
                           <a href="<?php echo site_url('visa') ?> ">
                              <span><i class="fa-brands fa-cc-visa"></i> Visa </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['car_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("CarBooking"); ?>">
                           <a href="<?php echo site_url('car') ?> ">
                              <span> <i class="fa-solid fa-car"></i> Car </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['cruise_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("CruiseBooking"); ?>">
                           <a href="<?php echo site_url('cruise') ?> ">
                              <span> <i class="fa-solid fa-ship"></i> Cruise </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['tourguide_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("Tourguide"); ?>">
                           <a href="<?php echo site_url('tourguide') ?> ">
                              <span>
                                 <svg width="23.2px" height="23.2px" viewBox="0 -2 24 24"
                                    id="meteor-icon-kit__regular-guide" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                       d="M17 0C16.4477 0 16 0.44772 16 1V19C16 19.5523 16.4477 20 17 20C17.5523 20 18 19.5523 18 19V8.7808L23.2425 7.47014C23.6877 7.35885 24 6.95887 24 6.5V1C24 0.44772 23.5523 0 23 0H17zM22 5.71922L18 6.71922V2H22V5.71922z"
                                       fill="#000000"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                       d="M7 11C9.20914 11 11 9.2091 11 7C11 4.79086 9.20914 3 7 3C4.79086 3 3 4.79086 3 7C3 9.2091 4.79086 11 7 11zM7 9C8.10457 9 9 8.1046 9 7C9 5.89543 8.10457 5 7 5C5.89543 5 5 5.89543 5 7C5 8.1046 5.89543 9 7 9z"
                                       fill="#000000"></path>
                                    <path
                                       d="M5 14C3.34315 14 2 15.3431 2 17V19C2 19.5523 1.55228 20 1 20C0.447715 20 0 19.5523 0 19V17C0 14.2386 2.23858 12 5 12H9C11.7614 12 14 14.2386 14 17V19C14 19.5523 13.5523 20 13 20C12.4477 20 12 19.5523 12 19V17C12 15.3431 10.6569 14 9 14H5z"
                                       fill="#000000"></path>
                                 </svg>
                                 Tour Guide </span>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (whitelabel['activities_module'] == 'active') { ?>
                        <li class="tab-link <?php echo active_tab("Activities"); ?>">
                           <a href="<?php echo site_url('activities') ?> ">
                              <span> <i class="fa-solid fa-binoculars"></i>Activity </span>
                           </a>
                        </li>
                     <?php } ?>
                  </ul>
                  <div class="tts_product_box">
                     <?php if ($controller == 'Flight') { ?>
                        <div id="flight" class="tab-content <?php echo active_tab("Flight"); ?>">
                           <!----radio----->
                        <div class="row">
                           <div class="col-12">
                              <div class="flight-search d-md-flex align-items-center justify-content-between mb-3">
                                 <div class="d-flex align-items-center mb-3 mb-lg-0">
                                    <div class="search-check">
                                       <label class="search-check-label"> <input class="form-check-input" type="radio"
                                             name="flightjtype" value="Oneway"
                                             onclick="checkflightJourneytype('Oneway')" checked /> Oneway </label>
                                    </div>
                                    <div class="search-check ">
                                       <label class="search-check-label"> <input class="form-check-input" type="radio"
                                             name="flightjtype" value="Roundtrip"
                                             onclick="checkflightJourneytype('Roundtrip')" /> Round Trip</label>
                                    </div>
                                    <div class="search-check">
                                       <label class="search-check-label"> <input class="form-check-input" type="radio"
                                             name="flightjtype" value="Multicity"
                                             onclick="checkflightJourneytype('Multicity')" /> Multi City</label>
                                    </div>
                                 </div>
                                 <h3>Book Flight Tickets</h3>
                              </div>
                           </div>
                        </div>
                        <!----radio----->
                        <div class="row align-items-center">
                           <div class="tab-pane fade show active">
                              <form action="<?php echo site_url("flight/search"); ?>" type="get"
                                 class="tts__form_wrapper" name="flight-form" flight-oneway-roundtrip-form="true">
                                 <input type="hidden" value="Oneway" name="journeytype">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-lg-3 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">From Airport</label>
                                          <input type="text" class="form-control tts__input__input tts-cursor-pointer"
                                             placeholder="Origin" value="Delhi" tts-flight-origin="true"
                                             data-validation="required"
                                             data-validation-error-msg="Please select from airport">
                                          <input type="hidden" name="origin" value="Delhi (DEL), India">
                                          <div class="flight_text_p">[DEL] Indira Gandhi International</div>
                                          <img class="tts__interchange__arrow"
                                             src="<?php echo site_url('webroot'); ?>/img/svg_icon/arrows.svg"
                                             alt="arrows" swape-city="true">
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 p0 ">
                                       <div class="position-relative">
                                          <label class="form-label pl-md-2">To Airport</label>
                                          <input type="text" class="form-control  tts__input__input tts-cursor-pointer"
                                             placeholder="Destination" value="Mumbai" tts-flight-destination="true"
                                             data-validation="required"
                                             data-validation-error-msg="Please select to airport">
                                          <input type="hidden" name="destination" value="Mumbai (BOM), India">
                                          <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 p0 d-flex">
                                       <div class="position-relative">
                                          <label class="form-label">Depart</label>
                                          <input type="text" class="form-control tts__input__input tts-cursor-pointer"
                                             name="departdate" placeholder="Depart" readonly
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                             data-validation="required"
                                             data-validation-error-msg="Please select departure date"
                                             flight-departure-date="true">
                                          <div class="flight_text_p">
                                             <?php echo date('l', strtotime($Date . ' + 1 days')); ?>
                                          </div>
                                       </div>
                                       <div class="position-relative" onclick="selectroundtripDate('Roundtrip')">
                                          <label class="form-label">Return</label>
                                          <input type="text"
                                             class="form-control tts__input__input flight-return-date-disable tts-cursor-pointer"
                                             name="returndate" readonly placeholder="" data-validation="required"
                                             data-validation-error-msg="Please select return date"
                                             flight-return-date="true" placeholder="Return Date">
                                          <div class="flight_text_p">Book a round trip to save more</div>
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                          <label class="form-label">Travellers & Class </label>
                                          <div class="tts__traveller_select tts__traveller_select11 form-select"
                                             id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false"
                                             data-bs-auto-close="outside">
                                             <span class="tts__traveller_select_tts_span" data-total-pax="true"> 1
                                             </span>
                                             <span>Traveller(s)</span> <span class="cabinclass"
                                                flight-cabin-class="true"> Any</span>
                                          </div>
                                          <div class=" tts__dropdown__menu__right p-3 dropdown-menu"
                                             aria-labelledby="select_flight_pax">
                                             <div class="row">
                                                <div class="col-md-4 col-12">
                                                   <div class="tts__traveller__select">
                                                      <h6>Adult</h6>
                                                      <div class="GwMit">
                                                         <span class="tts__counter" data-adult-pre="true">-</span>
                                                         <span class="tts_traveller__counter_span"
                                                            data-adult-count="true">1</span>
                                                         <input class="form-control tts_traveller__counter"
                                                            type="hidden" value="1" name="adults" adult-input="true">
                                                         <span class="tts__counter" data-adult-next="true">+</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                   <div class="tts__traveller__select">
                                                      <h6>Children </h6>
                                                      <div class="GwMit">
                                                         <span class="tts__counter" data-child-pre>-</span>
                                                         <span class="tts_traveller__counter_span"
                                                            data-child-count="true">0</span>
                                                         <input class="form-control tts_traveller__counter"
                                                            type="hidden" value="0" name="child" child-input="true">
                                                         <span class="tts__counter" data-child-next>+</span>
                                                      </div>
                                                      <span class="tts__traveller__limit">(2y - 12y)</span>
                                                   </div>
                                                </div>
                                                <div class="col-md-4 col-12 ">
                                                   <div class="tts__traveller__select">
                                                      <h6>Infant </h6>
                                                      <div class="GwMit">
                                                         <span class="tts__counter" data-infant-pre>-</span>
                                                         <span class="tts_traveller__counter_span"
                                                            data-infant-count="true">0</span>
                                                         <input class="form-control tts_traveller__counter"
                                                            type="hidden" value="0" name="infant" infant-input="true">
                                                         <span class="tts__counter" data-infant-next>+</span>
                                                      </div>
                                                      <span class="tts__traveller__limit">(below 2y)</span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row py-2">
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="Any" class="mr-1"
                                                         onclick="changeCabinclass('Any' ,'flight-cabin-class')"
                                                         checked> Any</label>
                                                </div>
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="Economy" class="mr-1"
                                                         onclick="changeCabinclass('Economy','flight-cabin-class')">
                                                      Economy
                                                   </label>
                                                </div>
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="Business"
                                                         class="mr-1"
                                                         onclick="changeCabinclass('Business','flight-cabin-class')">
                                                      Business
                                                   </label>
                                                </div>
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="PremiumEconomy"
                                                         class="mr-1"
                                                         onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')">
                                                      Premium Economy</label>
                                                </div>
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="PremiumBusiness"
                                                         class="mr-1"
                                                         onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')">
                                                      Premium
                                                      Business</label>
                                                </div>
                                                <div class="col-12">
                                                   <label class="tts__inputradio_label">
                                                      <input type="radio" name="cabinclass" value="First" class="mr-1"
                                                         onclick="changeCabinclass('First' ,'flight-cabin-class')">
                                                      First</label>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-12 p0">
                                       <button type="submit" class="oneway_btn oneway_search_btn btn"
                                          onclick="return checkFlightSearchValidation('flight-form');">Search</button>
                                    </div>
                                 </div>
                                 <input type="hidden" class="form-check-input" name="direct_flight" value="0">
                                 <input type="hidden" name="preferred_carriers" id="PreferredCarriers">
                              </form>
                              <form action="<?php echo site_url("flight/search"); ?>" type="get"
                                 class="tts__form_wrapper" name="flight-multi-form" flight-multicity-form="true"
                                 style="display: none;">
                                 <input type="hidden" value="Multicity" name="journeytype">
                                 <input type="hidden" class="form-check-input" name="direct_flight" value="0">
                                 <div multicity-addmore class="flight-mult-city">
                                    <div class="row no-gutters align-items-center flight_search_border m-0"
                                       data-journey-key="0">
                                       <div class="col-lg-3 col-md-6 col-12 p0">
                                          <div class="position-relative">
                                             <label class="form-label">From Airport</label>
                                             <input type="text" class="form-control tts__input__input"
                                                placeholder="Origin" tts-flight-origin="true" data-validation="required"
                                                data-validation-error-msg="Please select from airport" value="Delhi"
                                                data-key="0">
                                             <input type="hidden" name="search_data[0][origin]"
                                                value="Delhi (DEL), India">
                                             <div class="flight_text_p">[DEL] Indira Gandhi International</div>
                                          </div>
                                       </div>
                                       <div class="col-lg-3 col-md-6 col-12 p0">
                                          <div class="position-relative">
                                             <label class="form-label pl-md-2">To Airport</label>
                                             <input type="text" class="form-control  tts__input__input"
                                                placeholder="Destination" tts-flight-destination="true"
                                                data-validation="required" value="Mumbai"
                                                data-validation-error-msg="Please select to airport" data-key="0">
                                             <input type="hidden" name="search_data[0][destination]"
                                                value="Mumbai (BOM), India">
                                             <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                          </div>
                                       </div>
                                       <div class="col-md-2 col-6 p0">
                                          <div class="position-relative">
                                             <label class="form-label">Depart</label>
                                             <input type="text"
                                                class="form-control tts__input__input tts-cursor-pointer"
                                                name="search_data[0][departdate]" placeholder="Depart Date"
                                                data-validation="required"
                                                data-validation-error-msg="Please select departure date"
                                                value="<?php $Date = date("Y/m/d");
                                                echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                                flight-departure-date="true" data-key="0" readonly>
                                             <div class="flight_text_p">
                                                <?php echo date('l', strtotime($Date . ' + 1 days')); ?>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-3 col-12 p0">
                                          <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                             <label class="form-label">Travellers & Class </label>
                                             <div class="tts__traveller_select tts__traveller_select11"
                                                id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false"
                                                data-bs-auto-close="outside">
                                                <span class="tts__traveller_select_tts_span" data-total-pax="true"> 1
                                                </span>
                                                <span>Traveller(s)</span> <span class="cabinclass"
                                                   flight-cabin-class="true"> Any</span>
                                             </div>
                                             <div class=" tts__dropdown__menu__right p-3 dropdown-menu"
                                                aria-labelledby="select_flight_pax">
                                                <div class="row">
                                                   <div class="col-md-4 col-12">
                                                      <div class="tts__traveller__select">
                                                         <h6>Adult</h6>
                                                         <span class="tts__counter" data-adult-pre="true">-</span>
                                                         <span class="tts_traveller__counter_span"
                                                            data-adult-count="true">1</span>
                                                         <input class="form-control tts_traveller__counter"
                                                            type="hidden" value="1" name="adults" adult-input="true">
                                                         <span class="tts__counter" data-adult-next="true">+</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-4 col-12 ">
                                                      <div class="tts__traveller__select">
                                                         <h6>Children</h6>
                                                         <div class="">
                                                            <span class="tts__counter" data-child-pre>-</span>
                                                            <span class="tts_traveller__counter_span"
                                                               data-child-count="true">0</span>
                                                            <input class="form-control tts_traveller__counter"
                                                               type="hidden" value="0" name="child" child-input="true">
                                                            <span class="tts__counter" data-child-next>+</span>
                                                         </div>
                                                         <span class="tts__traveller__limit">(2y - 12y)</span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-4 col-12 ">
                                                      <div class="tts__traveller__select">
                                                         <h6>Infant</h6>
                                                         <div>
                                                            <span class="tts__counter" data-infant-pre>-</span>
                                                            <span class="tts_traveller__counter_span"
                                                               data-infant-count="true">0</span>
                                                            <input class="form-control tts_traveller__counter"
                                                               type="hidden" value="0" name="infant"
                                                               infant-input="true">
                                                            <span class="tts__counter" data-infant-next>+</span>
                                                         </div>
                                                         <span class="tts__traveller__limit">(below 2y)</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="row py-2">
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Any" class="mr-1"
                                                            onclick="changeCabinclass('Any' ,'flight-cabin-class')"
                                                            checked> Any</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Economy"
                                                            class="mr-1"
                                                            onclick="changeCabinclass('Economy','flight-cabin-class')">
                                                         Economy
                                                      </label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="Business"
                                                            class="mr-1"
                                                            onclick="changeCabinclass('Business','flight-cabin-class')">
                                                         Business
                                                      </label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="PremiumEconomy"
                                                            class="mr-1"
                                                            onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')">
                                                         Premium Economy</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="PremiumBusiness"
                                                            class="mr-1"
                                                            onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')">
                                                         Premium
                                                         Business</label>
                                                   </div>
                                                   <div class="col-12">
                                                      <label class="tts__inputradio_label">
                                                         <input type="radio" name="cabinclass" value="First"
                                                            class="mr-1"
                                                            onclick="changeCabinclass('First' ,'flight-cabin-class')">
                                                         First</label>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-lg-1 col-md-3 col-12 p0">
                                          <button type="submit" class="oneway_btn oneway_search_btn btn"
                                             onclick="return checkFlightSearchValidation('flight-multi-form');">Search</button>
                                       </div>
                                    </div>
                                    <div class="row no-gutters align-items-center border-top-0 flight_search_border m-0"
                                       data-journey-key="1">
                                       <div class="col-lg-3 col-md-6 col-12 p0">
                                          <div class="position-relative">
                                             <label class="form-label">From Airport</label>
                                             <input type="text" class="form-control tts__input__input"
                                                name="search_data[1][origin]" placeholder="Origin"
                                                tts-flight-origin="true" value="Mumbai" data-validation="required"
                                                data-validation-error-msg="Please select from airport" data-key="1">
                                             <input type="hidden" name="search_data[1][origin]"
                                                value="Mumbai (BOM), India">
                                             <div class="flight_text_p">[BOM] Chhatrapati Shivaji</div>
                                          </div>
                                       </div>
                                       <div class="col-lg-3 col-md-6 col-12 p0">
                                          <div class="position-relative">
                                             <label class="form-label pl-md-2">To Airport</label>
                                             <input type="text" class="form-control  tts__input__input"
                                                placeholder="Destination" tts-flight-destination="true"
                                                data-validation="required"
                                                data-validation-error-msg="Please select to airport" data-key="1">
                                             <input type="hidden" name="search_data[1][destination]">
                                             <div class="flight_text_p"></div>
                                          </div>
                                       </div>
                                       <div class="col-md-2 col-6 p0">
                                          <div class="position-relative">
                                             <label class="form-label">Depart</label>
                                             <input type="text"
                                                class="form-control tts__input__input tts-cursor-pointer"
                                                name="search_data[1][departdate]" placeholder="Depart Date"
                                                data-validation="required"
                                                data-validation-error-msg="Please select departure date"
                                                flight-departure-date="true" data-key="1" readonly>
                                             <div class="flight_text_p"></div>
                                          </div>
                                       </div>
                                       <div class="col-md-4 col-6 p0">
                                          <div class="ms-2 float-start">
                                             <button type="button" class="btn addcity-btn" add-mult-city="true">Add
                                                City</button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <input type="hidden" name="preferred_carriers" id="PreferredCarriers">
                              </form>
                           </div>
                        </div>
                        <div class="form-check bg-transparent mt-3">
                           <input type="checkbox" class="form-check-input" check-direct-flight="true">
                           <label class="form-check-label">Direct Flight</label>
                           <a class="form-check-label mx-5" id="default-open-select2" data-bs-toggle="collapse"
                              href="#select-preferred-flight" role="button" aria-expanded="false"
                              aria-controls="select-preferred-flight">
                              Select Preferred Flight
                           </a>
                           <div class="collapse" id="select-preferred-flight">
                              <div class="card card-body" style="width: 43%;">
                                 <select class="form-select form-select-lg mb-3 select_search PreferredFlights"
                                    name="PreferredFlights[]" aria-label=".form-select-lg" multiple="multiple">
                                    <option value="all">All</option>
                                    <option value="6E">IndiGo-6E</option>
                                    <option value="SG">SpiceJet-SG</option>
                                    <option value="G8">Go First-G8</option>
                                    <option value="AI">Air India-AI</option>
                                    <option value="UK">Vistara-UK</option>
                                    <option value="I5">Air Asia-I5</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if ($controller == 'Hotel') { ?>
                     <div id="hotel" class="tab-content  <?php echo active_tab("Hotel"); ?>">
                        <div class="row mb-3">
                           <div class="col-12">
                              <div class="flight-search d-flex align-items-center justify-content-between">
                                 <h3>Book Domestic and International Hotels</h3>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 p0">
                           <form action="<?php echo site_url('hotel/hotel-result') ?>" class="tts__form_wrapper"
                              name="hotelform" type="get">
                              <div class="row no-gutters flight_search_border m-0">
                                 <div class="col-sm-3 p0">
                                    <div class="position-relative">
                                       <label class="form-label">City</label>
                                       <input type="text" class="form-control tts__input__input" placeholder="CITY"
                                          value="Goa, India" data-validation="required" name="location"
                                          data-validation-error-msg="Please select city" tts-hotel-location="true">
                                       <!--  <div class="flight_text_p">[DEL] Indira Gandhi International</div> -->
                                          <input type="hidden" name="cityDom" cityDom="true" value="Goa_119805_IN"
                                             data-validation="required">
                                          <input type="hidden" name="room" hotel-total-selected-rooms="true" value="1"
                                             data-validation="required">
                                          <!--    <input type="hidden" name="rating" data-validation="required" value="0_5"> -->
                                       </div>
                                    </div>
                                    <div class="col-sm-2 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Check-In</label>
                                          <input type="text" class="form-control tts__input__input" placeholder="Check-In"
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                             data-validation="required"
                                             data-validation-error-msg="Please select check in date"
                                             hotel-check-in-date="true" name="checkIn">
                                          <!-- <div class="flight_text_p">Monday</div> -->
                                       </div>
                                    </div>
                                    <div class="col-sm-2 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Check-Out</label>
                                          <input type="text" class="form-control tts__input__input" placeholder="Check-Out"
                                             data-validation="required"
                                             data-validation-error-msg="Please select check out date"
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 2 days')); ?>"
                                             hotel-check-out-date="true" name="checkOut">
                                          <!--   <div class="flight_text_p">Monday</div> -->
                                       </div>
                                    </div>
                                    <div class="col-sm-2 p0">
                                       <div class="position-relative tts__dropdown__wrapper">
                                          <label class="form-label">Room & Guests</label>
                                          <div class="pt-3 p-2 tts__traveller_select border-end " id="select_hotel_pax"
                                             data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                             <span class="tts__traveller_select_tts_span"
                                                tts-hotel-guest-info="true">2</span>Guest,
                                             <span class="pl-1 "><span class="tts__traveller_select_tts_span"
                                                   tts-hotel-rooms-info="true">1</span> Rooms </span>
                                          </div>
                                          <div class="tts__dropdown__menu__right p-3 dropdown-menu"
                                             aria-labelledby="select_hotel_pax" hotel-room-dropdown="true"
                                             style="width:284px">
                                             <div class="row">
                                                <div class="col-12">
                                                   <div
                                                      class="tts__traveller__select my-2 d-flex justify-content-start__room ">
                                                      <h6>Star Rating</h6>
                                                      <select class="form-control" name="rating">
                                                         <option value="0">Show All</option>
                                                         <option value="1">1 Star or less</option>
                                                         <option value="2">2 Star or less</option>
                                                         <option value="3">3 Star or less</option>
                                                         <option value="4">4 Star or less</option>
                                                         <option value="5">5 Star or less</option>
                                                         <option value="6">1 Star or More</option>
                                                         <option value="7">2 Star or More</option>
                                                         <option value="8">3 Star or More</option>
                                                         <option value="9" selected>4 Star or More</option>
                                                         <option value="10">5 Star or More</option>
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-12">
                                                   <div
                                                      class="tts__traveller__select my-2 d-flex justify-content-start__room ">
                                                      <h6>Room 1</h6>
                                                      <div class="row px-2">
                                                         <div class="col-6 px-2">
                                                            <span>Adults(12y +)</span>
                                                            <select class="form-control tts__input__select__room"
                                                               name="adult_1" onchange="get_hotel_adt(this)">
                                                               <option value="1">1</option>
                                                               <option value="2" selected>2</option>
                                                               <option value="3">3</option>
                                                               <option value="4">4</option>
                                                               <option value="5">5</option>
                                                            </select>
                                                         </div>
                                                         <div class="col-6 px-2">
                                                            <span>Children <i class="fa fa-info-circle"
                                                                  data-bs-toggle="tooltip" data-bs-html="true"
                                                                  title="(Age 12y and below)"></i></span>
                                                            <select class="form-control tts__input__select__room"
                                                               name="child_1" onchange="add_child_age('1',this.value);">
                                                               <option value="0" selected>0</option>
                                                               <option value="1">1</option>
                                                               <option value="2">2</option>
                                                               <option value="3">3</option>
                                                               <option value="4">4</option>
                                                            </select>
                                                         </div>
                                                         <div class="col-12 px-2">
                                                            <div class="row" add-room-child-age-element-1="true">
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-12" append-extra-hotel-room="true">
                                                </div>
                                                <div class="col-12 mb-3">
                                                   <a href="javascript:void(0);" class="tts__add__room"
                                                      add-extra-hotel-room-event="true" onclick="add_room()">add room</a>
                                                   <a href="javascript:void(0);" class="tts__remove__room hide"
                                                      remove-extra-hotel-room-event="true" onclick="remove_room()">remove
                                                      room</a>
                                                </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-sm-12 ml-auto">
                                                   <div class="d-flex justify-content-end">
                                                      <button type="button"
                                                         class="oneway_btn btn px-2 py-0 tts__close_dropdown"
                                                         hotel-room-dropdown-event="true">done
                                                      </button>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-sm-2 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Country</label>
                                          <?php $CountryCodes = gettingCountryCodeWithCountryName() ?>
                                          <select class="form-select tts__input__select tts__traveller_select"
                                             name="nationalitycode">
                                             <?php if ($CountryCodes) {
                                                foreach ($CountryCodes as $CountryCode) { ?>
                                                   <option value="<?php echo $CountryCode['CountryCode']; ?>" <?php echo $CountryCode['CountryCode'] == "IN" ? "selected" : ""; ?>>
                                                      <?php echo $CountryCode['CountryName']; ?>
                                                   </option>
                                                <?php }
                                             } else { ?>
                                                <option Value="IN">India</option>
                                             <?php } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-sm-1 p0 col-12">
                                       <button type="submit" class="oneway_btn btn"
                                          onclick="return checkHotelSearchValidation();">
                                          Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     <?php } ?>
                     <?php if ($controller == 'Bus') { ?>
                        <div id="bus" class="tab-content <?php echo active_tab("Bus"); ?>">
                           <div class="row mb-3">
                              <div class="col-12">
                                 <div class="flight-search d-flex align-items-center justify-content-between">
                                    <h3>Bus Ticket Booking</h3>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 p0">
                              <form class="tts__form_wrapper" action="<?php echo site_url("bus/bus-result"); ?>"
                                 name="tts-bus-form">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-sm-4  p0">
                                       <div class="position-relative">
                                          <label class="form-label">From</label>
                                          <input type="text" class="form-control  tts__input__input bus-source"
                                             name="source" tts-bus-source="true" data-validation="required"
                                             data-validation-error-msg="Please select from city" placeholder="Source"
                                             value="Bangalore">
                                          <input type="hidden" name="source_id" source_id="true" value="8463">
                                       </div>
                                    </div>
                                    <div class="col-sm-4  p0">
                                       <div class="position-relative">
                                          <label class="form-label">To</label>
                                          <input type="text" class="form-control tts__input__input bus-destination"
                                             name="destination" tts-bus-destination="true" data-validation="required"
                                             data-validation-error-msg="Please select to city" placeholder="Destination"
                                             value="Hyderabad">
                                          <input type="hidden" name="destination_id" destination_id="true" value="9573">
                                       </div>
                                    </div>
                                    <div class="col-sm-3  p0">
                                       <div class="position-relative">
                                          <label class="form-label">Travel Date</label>
                                          <input type="text" class="form-control tts__input__input border-end-0 bus-depart"
                                             name="journey_date" data-validation="required"
                                             data-validation-error-msg="Please select date" placeholder="Depart Date"
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                             travel-date-calendor="true" readonly />
                                       </div>
                                    </div>
                                    <div class="col-sm-1 p0 col-12">
                                       <button class="oneway_btn btn" onclick="return checkBusSearchValidation();">Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     <?php } ?>
                     <?php if ($controller == 'HolidayBooking') { ?>
                        <div id="holiday" class="tab-content <?php echo active_tab("HolidayBooking"); ?>">
                           <div class="row mb-3">
                              <div class="col-12">
                                 <div class="flight-search d-flex align-items-center justify-content-between">
                                    <h3>Book Domestic and International Holiday Packages</h3>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 p0">
                              <form class="tts__form_wrapper" action="<?php echo site_url('holiday/search') ?>"
                                 name="holiday-form">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-sm-11  p0 col-12">
                                       <div class="position-relative">
                                          <label class="form-label">To City / Country</label>
                                          <input type="hidden" value="" name="tag" tts-holiday-tag="true">
                                          <input type="text" name="slug"
                                             class="form-control tts__input__input border-end-0" holiday-destination="true"
                                             placeholder="Search Destinations Themes Holidays">
                                          <input type="hidden" value="" name="id" tts-holiday-id="true">
                                       </div>
                                    </div>
                                    <div class="col-sm-1 p0 col-12">
                                       <button class="oneway_btn btn"
                                          onclick="return checkHolidaySearchValidation();">Search</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     <?php } ?>
                     <?php if ($controller == 'VisaBooking') { ?>
                        <div id="visa" class="tab-content <?php echo active_tab("VisaBooking"); ?>">
                           <div class="row mb-3">
                              <div class="col-12">
                                 <div class="flight-search d-flex align-items-center justify-content-between">
                                    <h3>Visa Booking</h3>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-12 p0">
                              <form class="tts__form_wrapper" action="<?php echo site_url('visa/search') ?>" method="get"
                                 name="tts-visa-form">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-sm-3 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Destinations</label>
                                          <select
                                             class="form-control form-select tts__input__select tts__traveller_select tts-input-padding"
                                             name="country_id" tts-method-name="visa/get-visa-list-api"
                                             tts-call-select="true" data-validation="required"
                                             data-validation-error-msg="Please select country">
                                             <option value="" selected>Destinations</option>
                                             <?php
                                             if (!empty($country_list) && is_array($country_list)) {
                                                foreach ($country_list as $country) {
                                                   ?>
                                                   <option value="<?php echo $country['CountryId'] ?>">
                                                      <?php echo $country['CountryName']; ?>
                                                   </option>
                                                <?php }
                                             } else { ?>
                                                <option>No Country Found</option>
                                             <?php } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-sm-2 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Visa Type</label>
                                          <select
                                             class="form-control form-select tts__input__select tts__traveller_select tts-input-padding"
                                             tts-call-put-html="true" name="visa_type_id" data-validation="required"
                                             data-validation-error-msg="Please select visa type">
                                             <option value="" selected>Visa Type</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-sm-3 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Travellers</label>
                                          <select
                                             class="form-control form-select tts__input__select tts__traveller_select tts-input-padding"
                                             name="travellers" data-validation="required"
                                             data-validation-error-msg="Please select travellers">
                                             <?php for ($i = 1; $i <= 15; $i++) { ?>
                                                <option value="<?php echo $i; ?>" <?php if ($i == 1) {
                                                      echo 'selected';
                                                   } ?>><?php echo $i; ?> Travellers
                                                </option>
                                             <?php } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-sm-3 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Travel date</label>
                                          <input type="text" class="form-control  tts__input__input border-end-0"
                                             placeholder="Travel date" value="Travel date" travel-date-calendor="true"
                                             name="travel_date" data-validation="required"
                                             data-validation-error-msg="Please select date" readonly />
                                       </div>
                                    </div>
                                    <div class="col-sm-1 p0 col-12">
                                       <button class="oneway_btn btn" onclick="return checkVisaSearchValidation();">Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     <?php } ?>
                     <?php if ($controller == 'CarBooking') { ?>
                        <div id="car_rental" class="tab-content <?php echo active_tab("CarBooking"); ?>">
                           <!----radio----->
                        <div class="row mb-3">
                           <div class="col-md-12">
                              <div class="flight-search d-flex align-items-center justify-content-between">
                                 <div class="d-flex align-items-center">
                                    <!-- Default radio -->
                                       <div class="search-check">
                                          <label class="search-check-label" for="Outstation-One-Way">
                                             <input class="search-check-input" type="radio" name="trip_type"
                                                id="Outstation-One-Way" value="outstation-one-way" checked />
                                             Outstation One Way</label>
                                       </div>
                                       <!-- Default checked radio -->
                                       <div class="search-check">
                                          <label class="search-check-label" for="Outstation-Round-Trip">
                                             <input class="search-check-input" type="radio" name="trip_type"
                                                id="Outstation-Round-Trip" value="outstation-round-trip" />
                                             Outstation Round Trip</label>
                                       </div>
                                       <!-- Default checked radio -->
                                       <div class="search-check ">
                                          <label class="search-check-label" for="Airport-Transfers">
                                             <input class="search-check-input" type="radio" name="trip_type"
                                                id="Airport-Transfers" value="airport-transfers" />
                                             Airport Transfers</label>
                                       </div>
                                    </div>
                                    <h3>Online Cab Booking</h3>
                                 </div>
                              </div>
                           </div>

                           <!----radio----->
                        <div class="row" tts-car-one-round="true">
                           <div class="tab-pane fade show active" id="tts__flight__oneway" role="tabpanel"
                              aria-labelledby="tts__flight__oneway-tab">
                              <form action="<?php echo site_url("car/car-result"); ?>" class="tts__form_wrapper"
                                 name="car-form">
                                 <input type="hidden" name="car_trip_type" value="outstation-one-way">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-md-3 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">From City</label>
                                          <input type="hidden" name="from_city_id" id="from-city-id">
                                          <input type="text" class="form-control tts__input__input"
                                             placeholder="From City" name="from_city" tts-car-from-city="true"
                                             tts-car-from-city="true">
                                          <img class="tts__interchange__arrow"
                                             src="<?php echo site_url('webroot'); ?>/img/svg_icon/arrows.svg"
                                             alt="arrows">
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label pl-md-2">To City</label>
                                          <input type="hidden" name="to_city_id" id="from-city-id">
                                          <input type="text" class="form-control tts__input__input"
                                             placeholder="To City" name="to_city" tts-car-to-city="true">
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Departure Date</label>
                                          <input type="text" class="form-control tts__input__input" name="depart_date"
                                             car-depart-date="true" placeholder="Depart Date"
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                             readonly>
                                       </div>
                                    </div>
                                    <div class="col-md-2 col-12  p0">
                                       <div class="position-relative">
                                          <label class="form-label">Return Date</label>
                                          <input type="text" name="return_date" tts-car-return="true"
                                             car-return-date="true" class="form-control tts__input__input" readonly
                                             placeholder="Return Date" value="Return Date">
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-12 p0" tts-pick-time-class="true">
                                       <div class="position-relative">
                                          <label class="form-label">Pickup Me</label>
                                          <select class="form-select form-control  tts__input__input border-end-0"
                                             name="pickup_time">
                                             <?php $time_pick = time_picker(); ?>
                                             <?php if (is_array($time_pick) && !empty($time_pick)) {
                                                foreach ($time_pick as $time_key => $time) {
                                                   ?>
                                             <option value="<?php echo $time_key; ?>">
                                                <?php echo $time; ?>
                                             </option>
                                             <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-12 p0 hide" tts-drop-time-class="true">
                                       <div class="position-relative">
                                          <label class="form-label">Drop Me</label>
                                          <select class="form-select form-control  tts__input__input" name="drop_time">
                                             <?php $time_pick = time_picker(); ?>
                                             <?php if (is_array($time_pick) && !empty($time_pick)) {
                                                foreach ($time_pick as $time_key => $time) {
                                                   ?>
                                             <option value="<?php echo $time_key; ?>">
                                                <?php echo $time; ?>
                                             </option>
                                             <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-12 p0">
                                       <button class="oneway_btn oneway_search_btn btn"
                                          onclick="return checkCarSearchValidation();">Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                        <div class="row hide" tts-car-airport-transfer="true">
                           <div class="tab-pane fade show active" id="tts__flight__oneway" role="tabpanel"
                              aria-labelledby="tts__flight__oneway-tab">
                              <form action="<?php echo site_url("car/car-result"); ?>" class="tts__form_wrapper"
                                 name="car-form-airport">
                                 <input type="hidden" name="car_trip_type" value="airport-transfers">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-lg-3 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Pickup Me</label>
                                          <select class="form-select form-control tts__input__input" name="pickup_type"
                                             tts-car-airport-pickup-type="true">
                                             <option value="airport_pickup" selected> From the Airport</option>
                                             <option value="airport_drop"> To the Airport</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-md-2 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label"
                                             car-from-airport-transfer-label="true">Airport</label>
                                          <div car-from-airport-transfer="true">
                                             <input type="text" class="form-control  tts__input__input"
                                                placeholder="Airport" name="airport" tts-car-airport="true">
                                          </div>
                                          <img class="tts__interchange__arrow"
                                             src="<?php echo site_url('webroot'); ?>/img/svg_icon/arrows.svg"
                                             alt="arrows">
                                       </div>
                                    </div>
                                    <input type="hidden" name="to_city_id" id="to-city-id">
                                    <input type="hidden" name="airport_id" id="airport-id">
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label pl-md-2"
                                             car-to-city-transfer-label="true">City</label>
                                          <div car-to-city-transfer="true">
                                             <input type="text" class="form-control tts__input__input"
                                                placeholder="City" name="city" tts-car-to-city="true">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Departure Date</label>
                                          <input type="text" class="form-control tts__input__input" name="depart_date"
                                             car-depart-date="true" placeholder="Depart Date"
                                             value="<?php $Date = date("Y/m/d");
                                             echo date(DateFormat, strtotime($Date . ' + 1 days')); ?>"
                                             readonly>
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Pickup Me</label>
                                          <select class="form-select form-control tts__input__input" name="pickup_time">
                                             <?php $time_pick = time_picker(); ?>
                                             <?php if (is_array($time_pick) && !empty($time_pick)) {
                                                foreach ($time_pick as $time_key => $time) {
                                                   ?>
                                             <option value="<?php echo $time_key; ?>">
                                                <?php echo $time; ?>
                                             </option>
                                             <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-12 p0">
                                       <button class="oneway_btn oneway_search_btn btn"
                                          onclick="return checkCarSearchValidationAirport();">Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if ($controller == 'CruiseBooking') { ?>
                     <div id="cruise" class="tab-content <?php echo active_tab("CruiseBooking"); ?>">
                        <div class="row mb-3">
                           <div class="col-12">
                              <div class="flight-search d-flex align-items-center justify-content-between">
                                 <h3>Cruise Ticket Booking</h3>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12 ">
                              <form class="tts__form_wrapper" name="cruise-form"
                                 action="<?php echo site_url('cruise/cruise-result') ?>">
                                 <div class="row no-gutters flight_search_border align-items-center m0">
                                    <div class="col-lg-3 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <input type="hidden" name="ocean_name" tts-cruise-ocean-name="true">
                                          <label class="form-label"> Destination/River </label>
                                          <select
                                             class="tts__traveller_select tts__traveller_select11 form-select tts-input-padding"
                                             name="ocean_id" tts-cruise-ocean="true">
                                             <option value="" selected>Destinations</option>
                                             <?php
                                             if (isset($OceanLists)) {
                                                foreach ($OceanLists as $ocean) {
                                                   ?>
                                             <option value="<?php echo $ocean['id'] ?>">
                                                <?php echo ucwords($ocean['ocean_name']) ?>
                                             </option>
                                             <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <input type="hidden" name="port_name" tts-cruise-port-name="true">
                                          <label class="form-label">Departure Port</label>
                                          <select
                                             class="tts__traveller_select tts__traveller_select11 form-select tts-input-padding"
                                             name="tts_port_id" tts-cruise-port="true" tts-cruise-port-html>
                                             <option value="" selected>Departure</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <label class="form-label">Cruise Line</label>
                                          <input type="hidden" name="cruise_line" tts-cruise-line-name="true">
                                          <select
                                             class="tts__traveller_select tts__traveller_select11 form-select tts-input-padding"
                                             name="cruise_line_id" tts-cruise-line="true">
                                             <option value="" selected>Cruise Line</option>
                                             <?php if (isset($CruiseLineId)) {
                                                foreach ($CruiseLineId as $cruise_line) {
                                                   ?>
                                             <option value="<?php echo $cruise_line['id']; ?>">
                                                <?php echo ucwords($cruise_line['cruise_line_name']) ?>
                                             </option>
                                             <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-12 p0">
                                       <div class="position-relative">
                                          <input type="hidden" name="ship_name" tts-ship-name="true">
                                          <label class="form-label">Ship</label>
                                          <select
                                             class="tts__traveller_select tts__traveller_select11 form-select tts-input-padding"
                                             name="ship_id" tts-cruise-ship-html="true" tts-cruise-ship>
                                             <option value="" selected>Ship</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-12 p0">
                                       <button class="oneway_btn btn"
                                          onclick="return checkCruiseSearchValidation('cruise-form');">Search</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if (($controller == 'Tourguide')) { ?>
                     <div id="tourguide" class="tab-content  <?php echo active_tab("Tourguide"); ?>">
                        <div class="row mb-3">
                           <div class="col-12">
                              <div class="flight-search d-flex align-items-center justify-content-between">
                                 <h3>Tourguide Booking</h3>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <form action="<?php echo site_url('tourguide/tourguide-search') ?>" name="tourguide-form"
                                 class="tts__form_wrapper">
                                 <div class="row no-gutters flight_search_border align-items-center m0">
                                    <div class="col-sm-11 p0">
                                       <div class="position-relative">
                                          <input type="text" class="form-control  tts__input__input " name="tourguide"
                                             tts-tourguide-destination data-validation="required"
                                             data-validation-error-msg="Where are you going"
                                             placeholder="Please enter City">
                                          <input type="hidden" name="location_id" tts-tourguide-location_id="true">
                                       </div>
                                    </div>
                                    <div class="col-md-1 col-12 oneway oneway_2 p0">
                                       <button type="submit" class="oneway_btn oneway_search_btn btn"
                                          onclick="return checkTourguideSearchValidation();">
                                          Search
                                       </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if (($controller == 'Activities')) { ?>
                     <div id="activities" class="tab-content  <?php echo active_tab("Activities"); ?>">
                        <div class="row mb-3">
                           <div class="col-12">
                              <div class="flight-search d-flex align-items-center justify-content-between">
                                 <h3>Book Activities </h3>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <form action="<?php echo site_url('activities/activity-search') ?>"
                                 class="tts__form_wrapper" name="activityform" method="post">
                                 <div class="row no-gutters flight_search_border m-0">
                                    <div class="col-sm-11 p0">
                                       <div class="position-relative">
                                          <label class="form-label">To Location / Name</label>
                                          <input type="text" class="form-control  tts__input__input " name="activity"
                                             tts-activity-destination="true" data-validation="required"
                                             data-validation-error-msg="Please select from city"
                                             placeholder="Search Location,Categories Name">
                                          <input type="hidden" name="tag" tts-activity-tag="true">
                                          <input type="hidden" name="id" tts-activity-id="true">
                                          <input type="hidden" name="activity_slug" tts-activity-slug="true">
                                       </div>
                                    </div>
                                    <div class="col-lg-1 col-md-6 p0 col-12">
                                       <button type="submit" class="oneway_btn btn"
                                          onclick="return checkActivitySearchValidation();">Search </button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!--------End Search Bar --------------->