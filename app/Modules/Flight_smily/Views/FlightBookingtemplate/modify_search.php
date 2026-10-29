<div class="col-md-12 col-12 ">
    <div class="hotal-filter-card d-none d-md-flex align-items-center justify-content-between justify-content-md-between my-3 p-2">
        <?php
        $Date = date("Y/m/d");
        $origin = "Delhi (DEL), India";
        $destination = "Mumbai (BOM), India";
        $originAirportCode = "DEL";
        $originCity = "Delhi";
        $destinationAirportCode = "BOM";
        $destinationCity = "Mumbai";
        $departdate = date(DateFormat, strtotime($Date . ' + 1 days'));;
        $returndate = "";
        if ($searchData['journeytype'] == 'Oneway' || $searchData['journeytype'] == 'Roundtrip') {
            $origin = $searchData['origin'];
            $destination = $searchData['destination'];
            $departdate = $searchData['departdate'];
            $returndate = $searchData['returndate'];
            $originAirportCode = AirportCode($origin);
            $destinationAirportCode =AirportCode($destination);
            $originCity = AirportCity($origin);
             $destinationCity = AirportCity($destination);

            ?>
            <ul class="modify_list  d-flex align-items-center ">
                <li class="d-flex align-items-center">
                    <h2><?php echo AirportCode($searchData['origin']); ?> <span
                                class="d-block"><?php echo AirportCity($searchData['origin']); ?></span></h2>
                    <span class="icons"><i class="fa fa-arrow-right"></i></span>
                    <h2><?php echo AirportCode($searchData['destination']); ?> <span
                                class="d-block"><?php echo AirportCity($searchData['destination']); ?></span></h2>
                </li>
                <li class="d-flex align-items-center">
                    <h3 class="">Departure <span class="d-block"><strong
                                    class=""><?php echo get_modify_search_date($searchData['departdate'], 'd'); ?></strong> <?php echo get_modify_search_date($searchData['departdate']); ?></span>
                    </h3>
                </li>
                <?php if ($searchData['journeytype'] == 'Roundtrip') {
                    $returndate = $searchData['returndate']; ?>
                    <li class="d-flex align-items-center">
                        <h3 class="">Return <span class="d-block"><strong
                                        class=""><?php echo get_modify_search_date($searchData['returndate'], 'd'); ?></strong> <?php echo get_modify_search_date($searchData['returndate']); ?></span>
                        </h3>
                    </li>
                <?php } ?>
                <li>
                    <h3 class="">Travellers <span class="d-block"><strong
                                    class=""><?php echo get_total_number_pax($searchData); ?></strong></span></h3>
                </li>
                <li>
                    <h3 class="">Travel Class<span class="d-block"><strong
                                    class=""><?php echo $searchData['cabinclass']; ?></strong></span></h3>
                </li>
            </ul>
        <?php } ?>
        <?php
        if ($searchData['journeytype'] == 'Multicity') {
            ?>
            <div class="modify_list multi_city  d-flex align-items-center ">
                <?php foreach ($searchData['search_data'] as $originDestination) { ?>
                    <li class="d-flex align-items-center">
                        <h2><?php echo AirportCode($originDestination['origin']); ?> <span
                                    class="d-block"><?php echo AirportCity($originDestination['origin']); ?></span></h2>
                        <span class="icons"><i class="fa fa-arrow-right"></i></span>
                        <h2><?php echo AirportCode($originDestination['destination']); ?> <span
                                    class="d-block"><?php echo AirportCity($originDestination['destination']); ?></span>
                        </h2>
                        <h3 class="">Departure <span class="d-block"><strong
                                        class=""><?php echo get_modify_search_date($originDestination['departdate'], 'd'); ?></strong> <?php echo get_modify_search_date($originDestination['departdate']); ?></span>
                        </h3>
                    </li>
                <?php } ?>

            </div>
            <div class="modify_list2  d-flex align-items-center ">
                <li>
                    <h3 class="">Travellers <span class="d-block"><strong
                                    class=""><?php echo get_total_number_pax($searchData); ?></strong></span></h3>
                </li>
                <li>
                    <h3 class="">Travel Class<span class="d-block"><strong
                                    class=""><?php echo $searchData['cabinclass']; ?></strong></span></h3>
                </li>
            </div>
        <?php } ?>

        <ul class="modify_list1 d-flex align-items-center">
            <li>
                <button type="button" class="btn btn-link border" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">modify search
                </button>
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                      aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content modal_content">
                            <div class="modal-header modal_header">
                                <h5 class="modal-title">Modify Search</h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body modal_body pt-0 pb-0">
                                <div id="flight" class="tab-content current">
                                    <!----radio----->
                                    <div class="flight-search d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="search-check">
                                                <label class="search-check-label"> <input class="form-check-input"
                                                                                          type="radio"
                                                                                          name="flightjtype"
                                                                                          value="Oneway"
                                                                                          onclick="checkflightJourneytype('Oneway')" <?php echo $searchData['journeytype'] == 'Oneway' ? "checked" : ""; ?> />
                                                    Oneway </label>
                                            </div>
                                            <div class="search-check ">
                                                <label class="search-check-label"> <input class="form-check-input"
                                                                                          type="radio"
                                                                                          name="flightjtype"
                                                                                          value="Roundtrip"
                                                                                          onclick="checkflightJourneytype('Roundtrip')" <?php echo $searchData['journeytype'] == 'Roundtrip' ? "checked" : ""; ?> />
                                                    Round Trip</label>
                                            </div>
                                            <div class="search-check">
                                                <label class="search-check-label"> <input class="form-check-input"
                                                                                          type="radio"
                                                                                          name="flightjtype"
                                                                                          value="Multicity"
                                                                                          onclick="checkflightJourneytype('Multicity')" <?php echo $searchData['journeytype'] == 'Multicity' ? "checked" : ""; ?> />
                                                    Multi City</label>
                                            </div>
                                        </div>
                                        <h4>Book Flight Tickets</h4>
                                    </div>
                                    <!----radio----->
                                    <div class="row align-items-center">
                                        <div class="tab-pane fade show active">
                                            <form action="<?php echo site_url("flight/search"); ?>" type="get"
                                                  class="tts__form_wrapper" name="flight-form"
                                                  flight-oneway-roundtrip-form="true">
                                                <input type="hidden" value="<?php echo $searchData['journeytype']; ?>"
                                                       name="journeytype">
                                                <input type="hidden" class="form-check-input" name="direct_flight"
                                                       value="<?php echo $searchData['direct_flight']; ?>">

                                                <input type="hidden" name="preferred_carriers"  value="<?php echo $searchData['preferred_carriers']; ?>"  id="PreferredCarriers">
                                                <div class="row no-gutters align-items-center flight_search_border">
                                                    <div class="col-md-3 col-6 p0">
                                                        <div class="position-relative">
                                                            <span class="tts__input__label">FROM AIRPORT</span>
                                                            <input type="text" class="form-control tts__input__input"
                                                                    placeholder="Origin"
                                                                   value="<?php echo $originCity; ?>"
                                                                   tts-flight-origin="true" data-validation="required"
                                                                   data-validation-error-msg="Please select from airport">
                                                                <input type="hidden" name="origin" value="<?php echo $origin; ?>">
                                                                <div class="flight_text_p">[<?php echo  $originAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$originAirportCode])?$OriginDestinationAirportDetail[$originAirportCode]:""; ?></div> 
                                                            <img class="tts__interchange__arrow"
                                                                 src="<?php echo site_url('webroot'); ?>/img/svg_icon/arrows.svg"
                                                                 alt="arrows" swape-city="true">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-6 p0 ">
                                                        <div class="position-relative">
                                                            <span class="tts__input__label pl-md-2">TO AIRPORT</span>
                                                            <input type="text"
                                                                   class="form-control  tts__input__input pl-md-4"
                                                                   name="destination" placeholder="Destination"
                                                                   value="<?php echo $destinationCity; ?>"
                                                                   tts-flight-destination="true"
                                                                   data-validation="required"
                                                                   data-validation-error-msg="Please select to airport">
                                                                   <input type="hidden" name="destination" value="<?php echo $destination; ?>">
                                                                   <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode])?$OriginDestinationAirportDetail[$destinationAirportCode]:""; ?></div> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-6 p0 d-flex">
                                                        <div class="position-relative">
                                                            <span class="tts__input__label"><i class="far fa-calendar-alt fs_16 me-2"></i>  DEPART <i class="fas fa-angle-down fs_16"></i></span>
                                                            <input type="text" class="form-control tts__input__input tts-cursor-pointer"
                                                                   name="departdate" placeholder="Depart Date"
                                                                   value="<?php echo $departdate; ?>"
                                                                   data-validation="required"
                                                                   data-validation-error-msg="Please select departure date"
                                                                   flight-departure-date="true" readonly>
                                                              <div class="flight_text_p"><?php echo date('l', strtotime($departdate)); ?></div>
                                                        </div>
                                                        <div class="position-relative"  onclick="selectroundtripDate('Roundtrip')">
                                                            <span class="tts__input__label"><i class="far fa-calendar-alt fs_16 me-2"></i> RETURN <i class="fas fa-angle-down fs_16"></i></span>
                                                            <input type="text"
                                                                   class="form-control tts__input__input flight-return-date-disable tts-cursor-pointer"
                                                                   name="returndate" readonly placeholder=""
                                                                   value="<?php echo $returndate; ?>"
                                                                   data-validation="required"
                                                                   data-validation-error-msg="Please select return date"
                                                                   flight-return-date="true" readonly>
                                                                 <div class="flight_text_p"><?php if($returndate) { echo date('l', strtotime($returndate)); } ?></div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-2 col-12 p0">
                                                        <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                                            <span class="tts__input__label">Travellers & Class <i class="fas fa-angle-down fs_16"></i></span>
                                                            <div class="tts__traveller_select tts__traveller_select11"
                                                                 id="select_flight_pax" data-bs-toggle="dropdown"
                                                                 aria-expanded="false" data-bs-auto-close="outside">
                                                                <span class="tts__traveller_select_tts_span"
                                                                      data-total-pax="true"> <?php echo $searchData['adults'] + $searchData['child'] + $searchData['infant']; ?> </span>
                                                                <span>Traveller(s)</span> <span
                                                                        flight-cabin-class="true"> <?php echo $searchData['cabinclass']; ?></span>
                                                            </div>
                                                            <div class=" tts__dropdown__menu__right p-3 dropdown-menu"
                                                                 aria-labelledby="select_flight_pax"
                                                                 style="width: 300px;">
                                                                <div class="row">
                                                                    <div class="col-md-4 col-12">
                                                                        <div class="tts__traveller__select my-2">
                                                                            <h6>Adult</h6>
                                                                            <span class="tts__counter"
                                                                                  style="margin-right: -5px;"
                                                                                  data-adult-pre="true">-</span>
                                                                            <span class="tts_traveller__counter_span"
                                                                                  data-adult-count="true"><?php echo $searchData['adults']; ?></span>
                                                                            <input class="form-control tts_traveller__counter"
                                                                                   type="hidden"
                                                                                   value="<?php echo $searchData['adults']; ?>"
                                                                                   name="adults" adult-input="true">
                                                                            <span class="tts__counter"
                                                                                  style="margin-left: -6px;"
                                                                                  data-adult-next="true">+</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4 ">
                                                                        <div class="tts__traveller__select my-2">
                                                                            <h6>Children</h6>
                                                                            <div class="">
                                                                                <span class="tts__counter"
                                                                                      style="margin-right: -5px;"
                                                                                      data-child-pre>-</span>
                                                                                <span class="tts_traveller__counter_span"
                                                                                      data-child-count="true"><?php echo $searchData['child']; ?></span>
                                                                                <input class="form-control tts_traveller__counter"
                                                                                       type="hidden"
                                                                                       value="<?php echo $searchData['child']; ?>"
                                                                                       name="child" child-input="true">
                                                                                <span class="tts__counter"
                                                                                      style="margin-left: -6px;"
                                                                                      data-child-next>+</span>
                                                                            </div>
                                                                            <span class="tts__traveller__limit">(2y - 12y)</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4 ">
                                                                        <div class="tts__traveller__select my-2">
                                                                            <h6>Infant</h6>
                                                                            <div>
                                                                                <span class="tts__counter"
                                                                                      style="margin-right: -5px;"
                                                                                      data-infant-pre>-</span>
                                                                                <span class="tts_traveller__counter_span"
                                                                                      data-infant-count="true"><?php echo $searchData['infant']; ?></span>
                                                                                <input class="form-control tts_traveller__counter"
                                                                                       type="hidden"
                                                                                       value="<?php echo $searchData['infant']; ?>"
                                                                                       name="infant"
                                                                                       infant-input="true">
                                                                                <span class="tts__counter"
                                                                                      style="margin-left: -6px;"
                                                                                      data-infant-next>+</span>
                                                                            </div>
                                                                            <span class="tts__traveller__limit">(below 2y)</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row py-2">
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="Any" class="mr-1"
                                                                                   onclick="changeCabinclass('Any' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Any' ? "checked" : ""; ?>>
                                                                            Any</label>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="Economy" class="mr-1"
                                                                                   onclick="changeCabinclass('Economy','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Economy' ? "checked" : ""; ?>>
                                                                            Economy
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="Business" class="mr-1"
                                                                                   onclick="changeCabinclass('Business','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Business' ? "checked" : ""; ?>>
                                                                            Business
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="PremiumEconomy" class="mr-1"
                                                                                   onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumEconomy' ? "checked" : ""; ?>>
                                                                            Premium Economy</label>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="PremiumBusiness" class="mr-1"
                                                                                   onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumBusiness' ? "checked" : ""; ?>>
                                                                            Premium
                                                                            Business</label>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="tts__inputradio_label">
                                                                            <input type="radio" name="cabinclass"
                                                                                   value="First" class="mr-1"
                                                                                   onclick="changeCabinclass('First' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'First' ? "checked" : ""; ?>>
                                                                            First</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 col-12 p0">
                                                        <button type="submit" class="oneway_btn oneway_search_btn btn"
                                                                onclick="return checkFlightSearchValidation('flight-form');">
                                                            Search
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <form action="<?php echo site_url("flight/search"); ?>" type="get"
                                                  class="tts__form_wrapper" name="flight-multi-form"
                                                  flight-multicity-form="true" style="display: none;">
                                                <input type="hidden" value="Multicity" name="journeytype">
                                                <input type="hidden" class="form-check-input" name="direct_flight"
                                                       value="<?php echo $searchData['direct_flight'] ?>">
                                                <input type="hidden" name="preferred_carriers"  value="<?php echo $searchData['preferred_carriers']; ?>"  id="PreferredCarriers">
                                                <div multicity-addmore class="flight-mult-city">
                                                    <div class="row no-gutters align-items-center flight_search_border"
                                                         data-journey-key="0">
                                                        <div class="col-md-3 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label">FROM AIRPORT</span>
                                                                <input type="text"
                                                                       class="form-control tts__input__input"
                                                                       placeholder="Origin" tts-flight-origin="true"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select from airport"
                                                                       value="<?php echo $originCity; ?>" data-key="0">
                                                                       <input type="hidden" name="search_data[0][origin]" value="<?php echo $origin; ?>">
                                                                       <div class="flight_text_p">[<?php echo  $originAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$originAirportCode])?$OriginDestinationAirportDetail[$originAirportCode]:""; ?></div> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label pl-md-2">TO AIRPORT</span>
                                                                <input type="text"
                                                                       class="form-control  tts__input__input pl-md-4"
                                                                       placeholder="Destination"
                                                                       tts-flight-destination="true"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select to airport"
                                                                       value="<?php echo $destinationCity; ?>" data-key="0">
                                                                       <input type="hidden" name="search_data[0][destination]" value="<?php echo $destination; ?>">
                                                                       <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode])?$OriginDestinationAirportDetail[$destinationAirportCode]:""; ?></div> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label"><i class="far fa-calendar-alt fs_16 me-2"></i>  DEPART <i class="fas fa-angle-down fs_16"></i></span>
                                                                <input type="text"
                                                                       class="form-control tts__input__input tts-cursor-pointer"
                                                                       name="search_data[0][departdate]"
                                                                       placeholder="Depart Date"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select departure date"
                                                                       flight-departure-date="true"
                                                                       value="<?php echo $departdate; ?>" data-key="0" readonly>
                                                                       <div class="flight_text_p"><?php echo date('l', strtotime($departdate)); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12 p0">
                                                            <div class="position-relative tts__dropdown__wrapper tts-cursor-pointer">
                                                                <span class="tts__input__label">Travellers & Class <i class="fas fa-angle-down fs_16"></i></span>
                                                                <div class="tts__traveller_select tts__traveller_select11"
                                                                     id="select_flight_pax" data-bs-toggle="dropdown"
                                                                     aria-expanded="false" data-bs-auto-close="outside">
                                                                    <span class="tts__traveller_select_tts_span"
                                                                          data-total-pax="true"> <?php echo $searchData['adults'] + $searchData['child'] + $searchData['infant']; ?> </span>
                                                                    <span>Traveller(s)</span> <span
                                                                            flight-cabin-class="true"> <?php echo $searchData['cabinclass']; ?></span>
                                                                </div>
                                                                <div class=" tts__dropdown__menu__right p-3 dropdown-menu"
                                                                     aria-labelledby="select_flight_pax"
                                                                     style="width: 300px;">
                                                                    <div class="row">
                                                                        <div class="col-md-4 col-12">
                                                                            <div class="tts__traveller__select my-2">
                                                                                <h6>Adult</h6>
                                                                                <span class="tts__counter"
                                                                                      style="margin-right: -5px;"
                                                                                      data-adult-pre="true">-</span>
                                                                                <span class="tts_traveller__counter_span"
                                                                                      data-adult-count="true"><?php echo $searchData['adults']; ?></span>
                                                                                <input class="form-control tts_traveller__counter"
                                                                                       type="hidden"
                                                                                       value="<?php echo $searchData['adults']; ?>"
                                                                                       name="adults" adult-input="true">
                                                                                <span class="tts__counter"
                                                                                      style="margin-left: -6px;"
                                                                                      data-adult-next="true">+</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 ">
                                                                            <div class="tts__traveller__select my-2">
                                                                                <h6>Children</h6>
                                                                                <div class="">
                                                                                    <span class="tts__counter"
                                                                                          style="margin-right: -5px;"
                                                                                          data-child-pre>-</span>
                                                                                    <span class="tts_traveller__counter_span"
                                                                                          data-child-count="true"><?php echo $searchData['child']; ?></span>
                                                                                    <input class="form-control tts_traveller__counter"
                                                                                           type="hidden"
                                                                                           value="<?php echo $searchData['child']; ?>"
                                                                                           name="child"
                                                                                           child-input="true">
                                                                                    <span class="tts__counter"
                                                                                          style="margin-left: -6px;"
                                                                                          data-child-next>+</span>
                                                                                </div>
                                                                                <span class="tts__traveller__limit">(2y - 12y)</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4 ">
                                                                            <div class="tts__traveller__select my-2">
                                                                                <h6>Infant</h6>
                                                                                <div>
                                                                                    <span class="tts__counter"
                                                                                          style="margin-right: -5px;"
                                                                                          data-infant-pre>-</span>
                                                                                    <span class="tts_traveller__counter_span"
                                                                                          data-infant-count="true"><?php echo $searchData['infant']; ?></span>
                                                                                    <input class="form-control tts_traveller__counter"
                                                                                           type="hidden"
                                                                                           value="<?php echo $searchData['infant']; ?>"
                                                                                           name="infant"
                                                                                           infant-input="true">
                                                                                    <span class="tts__counter"
                                                                                          style="margin-left: -6px;"
                                                                                          data-infant-next>+</span>
                                                                                </div>
                                                                                <span class="tts__traveller__limit">(below 2y)</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row py-2">
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="Any" class="mr-1"
                                                                                       onclick="changeCabinclass('Any' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Any' ? "checked" : ""; ?>>
                                                                                Any</label>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="Economy" class="mr-1"
                                                                                       onclick="changeCabinclass('Economy','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Economy' ? "checked" : ""; ?>>
                                                                                Economy
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="Business" class="mr-1"
                                                                                       onclick="changeCabinclass('Business','flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'Business' ? "checked" : ""; ?>>
                                                                                Business
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="PremiumEconomy"
                                                                                       class="mr-1"
                                                                                       onclick="changeCabinclass('Premium  Economy' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumEconomy' ? "checked" : ""; ?>>
                                                                                Premium Economy</label>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="PremiumBusiness"
                                                                                       class="mr-1"
                                                                                       onclick="changeCabinclass('Premium  Business' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'PremiumBusiness' ? "checked" : ""; ?>>
                                                                                Premium
                                                                                Business</label>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <label class="tts__inputradio_label">
                                                                                <input type="radio" name="cabinclass"
                                                                                       value="First" class="mr-1"
                                                                                       onclick="changeCabinclass('First' ,'flight-cabin-class')" <?php echo $searchData['cabinclass'] == 'First' ? "checked" : ""; ?>>
                                                                                First</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-12 p0">
                                                            <button type="submit"
                                                                    class="oneway_btn oneway_search_btn btn"
                                                                    onclick="return checkFlightSearchValidation('flight-multi-form');">
                                                                Search
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row no-gutters align-items-center border-top-0 flight_search_border"
                                                         data-journey-key="1">
                                                        <div class="col-md-3 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label">FROM AIRPORT</span>
                                                                <input type="text"
                                                                       class="form-control tts__input__input"
                                                                    
                                                                       placeholder="Origin" tts-flight-origin="true"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select from airport"
                                                                       value="<?php echo $destinationCity; ?>" data-key="1">
                                                                
                                                                       <input type="hidden" name="search_data[1][origin]" value="<?php echo $destination; ?>">
                                                                       <div class="flight_text_p">[<?php echo  $destinationAirportCode; ?>] <?php echo  isset($OriginDestinationAirportDetail[$destinationAirportCode])?$OriginDestinationAirportDetail[$destinationAirportCode]:""; ?></div> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label pl-md-2">TO AIRPORT</span>
                                                                <input type="text"
                                                                       class="form-control  tts__input__input pl-md-4"
                                                                   
                                                                       placeholder="Destination"
                                                                       tts-flight-destination="true"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select to airport"
                                                                       data-key="1">
                                                                       <input type="hidden"  name="search_data[1][destination]" value="<?php echo $destination; ?>">
                                                                       <div class="flight_text_p"></div> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-6 p0">
                                                            <div class="position-relative">
                                                                <span class="tts__input__label"><i class="far fa-calendar-alt fs_16 me-2"></i>  DEPART <i class="fas fa-angle-down fs_16"></i></span>
                                                                <input type="text"
                                                                       class="form-control tts__input__input tts-cursor-pointer"
                                                                       name="search_data[1][departdate]"
                                                                       placeholder="Depart Date"
                                                                       data-validation="required"
                                                                       data-validation-error-msg="Please select departure date"
                                                                       flight-departure-date="true" data-key="1" readonly>
                                                                     <div class="flight_text_p"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-6 p0">
                                                            <div class="ms-2 float-start">
                                                                <button type="button"
                                                                        class="btn btn-outline-secondary btn-sm"
                                                                        add-mult-city="true">Add City
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="form-check bg-transparent mt-3">
                                        <input type="checkbox" class="form-check-input"
                                               check-direct-flight="true" <?php echo $searchData['direct_flight'] == 1 ? "checked" : ""; ?>>
                                        <label class="form-check-label">Direct Flight</label>

                                       <!-- <a class="form-check-label mx-5"  id="default-open-select2" data-bs-toggle="collapse" href="#select-preferred-flight" role="button" aria-expanded="false" aria-controls="select-preferred-flight">
                                            Select Preferred Flight
                                        </a>-->
                                        <div class="collapse" id="select-preferred-flight">
                                            <div class="card card-body" style="width: 43%;">
                                                <select class="form-select form-select-lg mb-3  PreferredFlights" id="select_search" name="PreferredFlights[]" aria-label=".form-select-lg" multiple="multiple">
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

                            </div>

                        </div>
                    </div>
                </div>

            </li>
        </ul>
    </div>
</div>
<script>
    $(function () {
        if (flightsearchData != '') {
            setTimeout(function () {
                var type = flightsearchData.journeytype.toString();
                if (type == 'Multicity') {
                    checkflightJourneytype(type);
                    var modify_info = flightsearchData;
                    var OriginDestinationAirportDetail = '<?php echo json_encode($OriginDestinationAirportDetail);?>';
                    var modify_search_info_count = Number(Object.keys(modify_info.search_data).length) - 2;
                    for ($msi = 1; $msi <= modify_search_info_count; $msi++) {
                        $("[add-mult-city]").trigger('click');
                    }
                    form_fill_modify_search('[flight-multicity-form]', modify_info,OriginDestinationAirportDetail);
                } else {
                    checkflightJourneytype(type);
                }
            }, 2000);
        }
    });</script>