<div class="row">
    <div class="tts-col-12">
        <ul class="tabs search_tabs">
            <a href="<?php echo site_url('flight') ?> ">
            <li class="tab-link <?php echo active_tab("Flight"); ?>" >
                <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/flight.svg"
                           alt="Flight"> <br> Flight </span>
            </li>
            </a>
            <a href="<?php echo site_url('hotel') ?> ">
            <li class="tab-link " >
                <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/hotel.svg"
                           alt="Hotel"> <br> Hotel </span>
            </li>
            </a>
            <a href="<?php echo site_url('bus') ?> ">
            <li class="tab-link ">
                <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/bus.svg" alt="Bus"> <br> Bus </span>
            </li>
            </a>
            <a href="<?php echo site_url('holiday') ?> ">
                <li class="tab-link " >
                    <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/holiday.svg" alt="Holiday "> <br> Holiday </span>
                </li>
            </a>
            <a href="<?php echo site_url('car-extranet') ?> ">
                <li class="tab-link " >
                <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/car_rental.svg"
                           alt="Car"> <br> Car </span>
                </li>
            </a>
            <a href="<?php echo site_url('cruise') ?> ">
                <li class="tab-link <?php echo active_tab("Cruise"); ?>" >
                <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/cruise.svg"
                           alt="Cruise"> <br> Cruise </span>
                </li>
            </a>

            <a href="<?php echo site_url('visa') ?> ">
                <li class="tab-link <?php echo active_tab("VisaBooking"); ?>">
                    <span><img src="<?php echo site_url('webroot'); ?>/img/svg_icon/visa.svg"
                               alt="Visa"> <br> Visa </span>
                </li>
            </a>
        </ul>

        <div class="mt-4">
            <div id="flight" class="tab-content <?php echo active_tab("Flight"); ?>">
                <div class="row ">
                    <div class="tab-pane fade show active" id="tts__flight__oneway" role="tabpanel"
                         aria-labelledby="tts__flight__oneway-tab">
                        <form action="<?php echo site_url("flight/flight-result"); ?>" type="POST"
                              class="tts__form_wrapper">
                            <div class="row no-gutters">
                                <div class="col col-12 p0">
                                    <div class="position-relative">
                                        <span class="tts__input__label">FROM AIRPORT</span>
                                        <input type="text" class="form-control pt-3 tts__input__input" id="project"
                                               placeholder="Origin" value="Delhi (DEL), India">
                                        <img class="tts__interchange__arrow"
                                             src="<?php echo site_url('webroot'); ?>/img/svg_icon/arrows.svg"
                                             alt="arrows">
                                        <input type="hidden" id="project-id">
                                    </div>
                                </div>
                                <div class="col col-12  p0">
                                    <div class="position-relative">
                                        <span class="tts__input__label pl-md-2">TO AIRPORT</span>
                                        <input type="text" class="form-control pt-3 tts__input__input pl-md-4"
                                               placeholder="Destination" value="Mumbai (BOM), India">
                                    </div>
                                </div>
                                <div class="col col-12  p0">
                                    <div class="position-relative">
                                        <span class="tts__input__label">DEPARTURE DATE</span>
                                        <input type="text" class="form-control pt-3 tts__input__input"
                                               placeholder="Depart Date" value="31-May-2021">
                                    </div>
                                </div>
                                <div class="col col-12  p0">
                                    <div class="position-relative">
                                        <span class="tts__input__label">RETURN DATE</span>
                                        <input type="text" class="form-control pt-3 tts__input__input" readonly
                                               placeholder="Depart Date" value="31-May-2021">
                                    </div>
                                </div>
                                <div class="col col-12 p0">
                                    <div class="position-relative tts__dropdown__wrapper">
                                        <span class="tts__input__label">Traveller, Booking class</span>
                                        <div class="pt-3 p-2 tts__traveller_select " id="select_flight_pax"
                                             data-bs-toggle="dropdown" aria-expanded="false"
                                             data-bs-auto-close="outside">
                                            <span class="pl-1 ">4 Traveller(s)</span> <span class="pl-2">Economy</span>
                                        </div>

                                        <div class=" tts__dropdown__menu__right p-3 dropdown-menu"
                                             aria-labelledby="select_flight_pax">
                                            <div class="row">
                                                <div class="col-4 pr-1 pl-2">
                                                    <div class="tts__traveller__select my-2">
                                                        <h6>Adult</h6>
                                                        <span class="tts__counter" style="margin-right: -5px;">-</span>
                                                        <span class="tts_traveller__counter_span">1</span>
                                                        <input class="form-control tts_traveller__counter" type="hidden"
                                                               value="1">
                                                        <span class="tts__counter" style="margin-left: -6px;">+</span>
                                                    </div>
                                                </div>
                                                <div class="col-4 px-0">
                                                    <div class="tts__traveller__select my-2">
                                                        <h6>Children</h6>
                                                        <div class="">
                                                            <span class="tts__counter"
                                                                  style="margin-right: -5px;">-</span>
                                                            <span class="tts_traveller__counter_span">1</span>
                                                            <input class="form-control tts_traveller__counter"
                                                                   type="hidden" value="1">
                                                            <span class="tts__counter"
                                                                  style="margin-left: -6px;">+</span>
                                                        </div>
                                                        <span class="tts__traveller__limit">(2+ 12 yrs)</span>
                                                    </div>
                                                </div>
                                                <div class="col-4 pl-1 pr-2">
                                                    <div class="tts__traveller__select my-2">
                                                        <h6>Infant</h6>
                                                        <div>
                                                            <span class="tts__counter"
                                                                  style="margin-right: -5px;">-</span>
                                                            <span class="tts_traveller__counter_span">1</span>
                                                            <input class="form-control tts_traveller__counter"
                                                                   type="hidden" value="1">
                                                            <span class="tts__counter"
                                                                  style="margin-left: -6px;">+</span>
                                                        </div>
                                                        <span class="tts__traveller__limit">(Below 2 Years)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row py-2">
                                                <div class="col-12">
                                                    <label class="tts__inputradio_label">
                                                        <input type="radio" name="flightOnewayClass" value="Economy"
                                                               class="mr-1">
                                                        Economy
                                                    </label>
                                                </div>
                                                <div class="col-12">
                                                    <label class="tts__inputradio_label">
                                                        <input type="radio" name="flightOnewayClass" value="Business"
                                                               class="mr-1">
                                                        Business
                                                    </label>
                                                </div>
                                                <div class="col-12">
                                                    <label class="tts__inputradio_label">
                                                        <input type="radio" name="flightOnewayClass" value="PremEconomy"
                                                               class="mr-1"> Premium
                                                        Economy
                                                    </label>
                                                </div>
                                                <div class="col-12">
                                                    <label class="tts__inputradio_label">
                                                        <input type="radio" name="flightOnewayClass"
                                                               value="PremiumBusiness" class="mr-1"> Premium
                                                        Business
                                                    </label>
                                                </div>
                                                <div class="col-12">
                                                    <label class="tts__inputradio_label">
                                                        <input type="radio" name="flightOnewayClass" value="First"
                                                               class="mr-1">
                                                        First
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 p0">
                                    <button class="oneway_btn btn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div id="hotel" class="tab-content  ">
                <div class="tts-col-12 p0">
                    <form action="/" class="tts__form_wrapper">
                        <div class="row no-gutters">
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">CITY / HOTEL / AREA / BUILDING</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="CITY / HOTEL / AREA / BUILDING" value="Goa, India">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Check-In</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="Check-In" value="31-May-2021">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Check-Out</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="Check-Out" value="01-Jun-2021">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative tts__dropdown__wrapper">
                                    <span class="tts__input__label">ROOMS & GUESTS</span>
                                    <div class="pt-3 p-2 tts__traveller_select" id="select_hotel_pax"
                                         data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                        <span class="pl-1">4 Guest , 2 Rooms </span></div>
                                    <div class="tts__dropdown__menu__right p-3 dropdown-menu"
                                         aria-labelledby="select_hotel_pax">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="tts__traveller__select__room my-2">
                                                    <h6>Room 1</h6>
                                                    <div class="row px-2">
                                                        <div class="col-3 px-2">
                                                            <span>Adult</span>
                                                            <select class="form-control tts__input__select__room">
                                                                <option selected="">1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-3 px-2">
                                                            <span>Child</span>
                                                            <select class="form-control tts__input__select__room">
                                                                <option selected>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-3 px-2">
                                                            <span>Age</span>
                                                            <select class="form-control tts__input__select__room">
                                                                <option selected>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-3 px-2">
                                                            <span>Age</span>
                                                            <select class="form-control tts__input__select__room">
                                                                <option selected>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <a href="javascript:void();" class="tts__add__room">add room</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 ml-auto">
                                                <div class="d-flex justify-content-end">
                                                    <button class="oneway_btn btn px-2 py-0 tts__close_dropdown">done
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Country</span>
                                    <select class="form-control tts__input__select">
                                        <option selected>India</option>
                                        <option>Russia</option>
                                        <option>Japan</option>
                                        <option>Vietnam</option>
                                        <option>Mexico</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1 p0">
                                <button class="oneway_btn btn">Search</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>


            <div id="bus" class="tab-content ">
                <div class="tts-col-12 p0">

                    <form class="tts__form_wrapper">
                        <div class="row no-gutters">
                            <div class="col-sm-4  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">from</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="Kanpur, Uttar Pradesh" value="Delhi, Delhi">
                                </div>
                            </div>
                            <div class="col-sm-4  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">to</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="Destination" value="Kanpur, Uttar Pradesh">
                                </div>
                            </div>
                            <div class="col-sm-2  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">TRAVEL DATE</span>
                                    <input type="text" class="form-control pt-3 tts__input__input border-right"
                                           placeholder="Depart Date" value="31-May-2021">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <button class="oneway_btn btn">Search</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


            <div id="holiday" class="tab-content ">
                <div class="tts-col-12 p0">

                    <form class="tts__form_wrapper">
                        <div class="row no-gutters">
                            <div class="col-sm-5  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">from city</span>
                                    <input type="text" class="form-control pt-3 tts__input__input"
                                           placeholder="From City" value="New Delhi, India">
                                </div>
                            </div>
                            <div class="col-sm-5  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">TO CITY / COUNTRY</span>
                                    <input type="text" class="form-control pt-3 tts__input__input border-right"
                                           placeholder="Search Over A Million Tour And Travels, Sight Seeings"
                                           value="Handpicked Holidays for you">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <button class="oneway_btn btn">Search</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>


            <div id="cruise" class="tab-content <?php echo active_tab("Cruise"); ?>">
                <div class="tts-col-12 p0">

                    <form class="tts__form_wrapper">
                        <div class="row no-gutters">
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Destinations</span>
                                    <select class="form-control tts__input__select border-left">
                                        <option selected>Destinations</option>
                                        <option>Africa</option>
                                        <option>Alaska</option>
                                        <option>Asia</option>
                                        <option>Australia/New Zealand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">DEPARTURE port</span>
                                    <select class="form-control tts__input__select border-left">
                                        <option selected>Departure Port</option>
                                        <option>Cape Town, South Africa</option>
                                        <option>Lisbon, Portugal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">depart date</span>
                                    <input type="text" class="form-control pt-3 tts__input__input border-right"
                                           placeholder="Depart Date"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">no. of nights</span>
                                    <select class="form-control tts__input__select border-left">
                                        <option selected>No. of Nights</option>
                                        <option>2-4 Nights</option>
                                        <option>5-7 Nights</option>
                                        <option>8-10 Nights</option>
                                        <option>11-12 Nights</option>
                                        <option>13+ Nights</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 p0">

                                <button class="oneway_btn btn">Search</button>

                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <div id="visa" class="tab-content <?php echo active_tab("VisaBooking"); ?>">
                <div class="tts-col-12 p0">

                    <form class="tts__form_wrapper" action="<?php echo site_url('visa/search') ?>" method="get">
                        <div class="row no-gutters">
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Destinations</span>
                                    <select class="form-control tts__input__select border-left" name="country_id"
                                            tts-method-name="visa/get-visa-list-api" tts-call-select="true">
                                        <option value="" selected>Destinations</option>
                                        <?php
                                        if (!empty($country_list) && is_array($country_list)) {
                                            foreach ($country_list as $country) {
                                                ?>
                                                <option value="<?php echo $country['CountryId'] ?>"><?php echo $country['CountryName']; ?></option>
                                            <?php }
                                        } else { ?>
                                            <option>No Country Found</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Visa Type</span>
                                    <select class="form-control tts__input__select border-left" tts-call-put-html="true"
                                            name="visa_type_id">
                                        <option value="" selected>Visa Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">Travellers</span>

                                    <select class="form-control tts__input__select border-left" name="travellers">
                                        <?php for ($i = 1; $i <= 15; $i++) { ?>
                                            <option value="<?php echo $i; ?>" <?php if ($i == 1) {
                                                echo 'selected';
                                            } ?>><?php echo $i; ?> Travellers
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <button class="oneway_btn btn">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="car_rental" class="tab-content ">
                <div class="tts-col-12 ">
                    <h6>car_rental</h6>
                </div>
            </div>

        </div>
    </div>
</div>
<!----------End Search Bar ----------------->
