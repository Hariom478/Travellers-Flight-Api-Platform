<!-- flight oneway result page starts here -->
<div class="tts__flight__result__page py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12 ">
            <div class="hotal-filter-card d-none d-md-flex align-items-center justify-content-between justify-content-md-between my-3 p-2">
                <ul class="modify_list  d-flex align-items-center ">
                    <li class="d-flex align-items-center">
                        <h2>Del <span  class="d-block" >New Delhi</span></h2>
                        <span class="icons"><i class="fa fa-arrow-right"></i></span>
                        <h2>CCU <span class="d-block" >Kolkata</span></h2>
                    </li>
                    <li class="d-flex align-items-center">
                        <h3 class="">Departure <span class="d-block"><strong class="">22</strong> Jun’22 , Wednesday</span></h3>
                    </li>
                    <li>
                        <h3 class="">Travellers <span class="d-block"><strong class="">01</strong></span></h3>
                    </li>
                    <li>
                        <h3 class="">Travel Class<span class="d-block"><strong class="">Economy</strong></span></h3>
                    </li>
                </ul>

                <ul class="modify_list d-flex align-items-center">
                    <li class="me-2">
                       <button type="button" class="btn btn-link border text-black" data-toggle="tooltip" data-placement="top" title="Share">
                        <i class="fa fa-share-alt"></i>
                         </button>
                    </li>
                    <li>
                    <button type="button" class="btn btn-link border" data-bs-toggle="modal" data-bs-target="#staticBackdrop">modify search
                        <!-- <span  class="text"></span>   -->
                    </button>
                   <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content modal_content">
                        <div class="modal-header modal_header"> 
                            <h5 class="modal-title">Book Flight Tickets</h5>   
                                                 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal_body">
                            <div id="flight" class="tab-content current">
            <!----radio----->
            <div class="mb-2">
                 <div class="form-check form-check-inline">
                      <input class="form-check-input form-check-input1" type="radio" name="exampleRadios" id="exampleRadios20" value="option20" checked>
                      <label class="form-check-label" for="exampleRadios20">
                       Oneway
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input form-check-input1" type="radio" name="exampleRadios" id="exampleRadios21" value="option21">
                      <label class="form-check-label" for="exampleRadios21">
                        Round trip                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input form-check-input1" type="radio" name="exampleRadios" id="exampleRadios22" value="option22" >
                      <label class="form-check-label" for="exampleRadios22">
                        Multi Trip
                      </label>
                    </div>
            </div>    
            <!----radio----->
            <div class="row">
                <div class="tab-pane fade show active" id="tts__flight__oneway" role="tabpanel" aria-labelledby="tts__flight__oneway-tab">
                    <form action="https://test.bdsdtechnology.com/ttsproduct/admin/flight/flight-result" type="POST" class="tts__form_wrapper">
                        <div class="row no-gutters border-top border-bottom align-items-center">
                            <div class="col-sm-3 p0">
                                <div class="position-relative">
                                    
                                    <span class="tts__input__label">FROM AIRPORT</span>
                                    <input type="text" class="form-control pt-3 tts__input__input" id="project" placeholder="Origin" value="Delhi (DEL), India">
                                    <img class="tts__interchange__arrow" src="https://test.bdsdtechnology.com/ttsproduct/admin/webroot/img/svg_icon/arrows.svg" alt="arrows">
                                    <input type="hidden" id="project-id">
                                    
                                </div>
                            </div>
                            <div class="col-sm-2  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label pl-md-2">TO AIRPORT</span>
                                    <input type="text" class="form-control pt-3 tts__input__input pl-md-4" placeholder="Destination" value="Mumbai (BOM), India">
                                </div>
                            </div>
                            <div class="col-sm-2  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">DEPARTURE DATE</span>
                                    <input type="text" class="form-control pt-3 tts__input__input" placeholder="Depart Date" value="31-May-2021">
                                </div>
                            </div>
                            <div class="col-sm-2  p0">
                                <div class="position-relative">
                                    <span class="tts__input__label">RETURN DATE</span>
                                    <input type="text" class="form-control pt-3 tts__input__input" readonly="" placeholder="Depart Date" value="31-May-2021">
                                </div>
                            </div>
                            <div class="col-sm-2 p0">
                                <div class="position-relative tts__dropdown__wrapper">
                                    <span class="tts__input__label">Traveller, Booking class</span>
                                    <div class="pt-3 p-2 tts__traveller_select " id="select_flight_pax" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                        <span class="pl-1 ">4 Traveller(s)</span> <span class="pl-2">Economy</span>
                                    </div>

                                    <div class=" tts__dropdown__menu__right p-3 dropdown-menu" aria-labelledby="select_flight_pax" style="width:290px;">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="tts__traveller__select my-2">
                                                    <h6>Adult</h6>
                                                    <span class="tts__counter" style="margin-right: -5px;">-</span>
                                                    <span class="tts_traveller__counter_span">1</span>
                                                    <input class="form-control tts_traveller__counter" type="hidden" value="1">
                                                    <span class="tts__counter" style="margin-left: -6px;">+</span>
                                                </div>
                                            </div>
                                            <div class="col-4 ">
                                                <div class="tts__traveller__select my-2">
                                                    <h6>Children</h6>
                                                    <div class="">
                                                            <span class="tts__counter" style="margin-right: -5px;">-</span>
                                                        <span class="tts_traveller__counter_span">1</span>
                                                        <input class="form-control tts_traveller__counter" type="hidden" value="1">
                                                        <span class="tts__counter" style="margin-left: -6px;">+</span>
                                                    </div>
                                                    <span class="tts__traveller__limit">(2+ 12 yrs)</span>
                                                </div>
                                            </div>
                                            <div class="col-4 ">
                                                <div class="tts__traveller__select my-2">
                                                    <h6>Infant</h6>
                                                    <div>
                                                            <span class="tts__counter" style="margin-right: -5px;">-</span>
                                                        <span class="tts_traveller__counter_span">1</span>
                                                        <input class="form-control tts_traveller__counter" type="hidden" value="1">
                                                        <span class="tts__counter" style="margin-left: -6px;">+</span>
                                                    </div>
                                                    <span class="tts__traveller__limit">(Below 2 Years)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row py-2">
                                            <div class="col-12">
                                                <label class="tts__inputradio_label">
                                                    <input type="radio" name="flightOnewayClass" value="Economy" class="mr-1">
                                                    Economy
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label class="tts__inputradio_label">
                                                    <input type="radio" name="flightOnewayClass" value="Business" class="mr-1">
                                                    Business
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label class="tts__inputradio_label">
                                                    <input type="radio" name="flightOnewayClass" value="PremEconomy" class="mr-1"> Premium
                                                    Economy
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label class="tts__inputradio_label">
                                                    <input type="radio" name="flightOnewayClass" value="PremiumBusiness" class="mr-1"> Premium
                                                    Business
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label class="tts__inputradio_label">
                                                    <input type="radio" name="flightOnewayClass" value="First" class="mr-1">
                                                    First
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1 p0">
                                <button class="oneway_btn oneway_search_btn btn">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form-check bg-transparent mt-3">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Direct Flight</label>
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
            <!-- filter starts here -->
            <div class="col-sm-3">
                <?php echo view('Modules/Flight/Views/html_template\flight_filter.php'); ?>
            </div>
            <!-- filter ends here -->




            <!-- oneway result starts here -->
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tts__travel__date p-2">
                            <img src="<?php echo site_url(); ?>webroot/img/svg_icon/watch.svg" alt="watch" class="img-fluid">
                            <strong>92% booked for your travel dates.</strong>
                            <span>Don’t wait until it’s too late, book now.</span>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="tts__show__result p-2">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="tts__showing__result__heading"><b>Showing Result 122 of 122 Flights</b>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="tts__showing__price__filter text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted mr-2">SORT BY:</span>
                                                Price - Low to High
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button class="dropdown-item" type="button">Price - Low to High</button>
                                                <button class="dropdown-item" type="button">Price - High to Low</button>
                                                <button class="dropdown-item" type="button">Depart - Early</button>
                                                <button class="dropdown-item" type="button">Depart - Late</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="tts__sorting__title p-2">
                            <div class="row">
                                <div class="col-2">
                                    <div class="tts__airline__name">
                                        <a href="#none">Airlines</a>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="tts__airline__name">
                                        <a href="#none">Depart</a>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="tts__airline__name">
                                        <a href="#none">Arrive</a>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="tts__airline__name">
                                        <a href="#none">Duration</a>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="tts__airline__name">
                                        <a href="#none">Price</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <?php for ($i = 1; $i <= 3; $i++) { ?>

                        <!-- <div class="col-sm-12 mt-3">
                            <div class="tts__flight__details__box py-2">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-8 pr-0">
                                        <div class="row align-items-center pt-1 pb-2 ">
                                            <div class="col-12 col-md-3 text-left">
                                                <div class="">
                                                    <img src="<?php echo site_url(); ?>webroot/img/indigo_icon.png" alt="indigo_icon">
                                                    <span class="d-block tts__flight__span">Indigo</span>
                                                    <span class="d-block tts__flight__span">6E-456</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3 text-left">
                                                <div class="position-relative d-block">
                                                    <span class="d-block tts__flight__time">05:00</span>
                                                    <span class="d-block tts__flight__place">BANGALORE</span>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3 text-center">
                                                <div class="position-relative text-center">
                                                    <span class="tts__flight__span">01h : 45m</span>
                                                    !-- <span class="d-none d-lg-inline">|</span>
                                                    <br class="d-block d-sm-none"> --
                                                    <div class="presentation">!----><!----><!----
                                                         <span class="stop"></span>!----><!----
                                                         <span class="stop1"></span>
                                                     </div>
                                                    <div class="flight-Icons">
                                                    <i class="fa fa-plane"></i>
                                                    </div>     
                                                        <span class="tts__flight__span">Non Stop</span>
                                                        
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3 text-end">
                                                <div class="">
                                                    <span class="d-block tts__flight__time">06:45</span>
                                                    <span class="d-block tts__flight__place">MUMBAI</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 d-flex align-items-center justify-content-end">
                                        <div style="margin-right:10px">
                                            <span class="tts__flight__price">₹ 599</span>
                                        </div>
                                        <div class="tts__book__btn">
                                                <div class="" style="float:right";>
                                                 <a href="<?php echo site_url("flight/flight-details"); ?>">   <button class="booknow_btn btn">Book <span class="d-none d-lg-inline">Now</span></button>
                                        </div>
                                    </div>
                                       
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pt-2 px-2 border-top">
                                            <div class="row d-flex align-items-center ">
                                                <div class="col-lg-4 col-12">
                                                    <a class="tts__flight__timing border-0 pb-1"  data-bs-toggle="collapse" href="#tts__flight__details<?php echo $i; ?>" role="button" aria-expanded="false" aria-controls="tts__flight__details<?php echo $i; ?>"  >
                                                        <img src="<?php echo site_url(); ?>webroot/css/icon/flight.svg"  alt="flight icon">
                                                        Flight Details
                                                    </a>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <span>Refundable</span>
                                                </div>
                                                <div class="col-lg-4 col-12">
                                                    <span><img src="<?php echo site_url(); ?>webroot/img/svg_icon/seat.svg" style="width: 18px; height: 18px; filter: brightness(0%);" alt="flight seat">4 Seat Left</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="collapse pt-2" id="tts__flight__details<?php echo $i; ?>">
                                            <div class="tts__flight__wrapper__box">
                                                <div class="tts__flight__details__wrapper">
                                                    <ul class="nav nav-tabs tts__flight__tab" id="tts__flight__detail__tab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="tts__flight__detail-tab" data-bs-toggle="collapse" href="#tts__flight__detail" role="button" aria-expanded="false" aria-controls="tts__flight__detail"    ><img src="<?php echo site_url(); ?>webroot/css/icon/flight_white.svg" style="width: 18px; height: 18px; filter: brightness(0%);" alt="flight icon"> Flight Details</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tts__flight__fare__backup-tab" data-toggle="tab" href="#tts__flight__fare__backup" role="tab" aria-controls="tts__flight__fare__backup" aria-selected="false">Fare Backup</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tts__flight__fare__rule-tab" data-toggle="tab" href="#tts__flight__fare__rule" role="tab" aria-controls="tts__flight__fare__rule" aria-selected="false">Fare Rule</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tts__flight__baggage-tab" data-toggle="tab" href="#tts__flight__baggage" role="tab" aria-controls="tts__flight__baggage" aria-selected="false">Baggage</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class=" tts__flight__tabContent" id=" tts__flight__detail__tabContent">
                                                    <div class="tab-pane fade show active" id="tts__flight__detail" role="tabpanel" aria-labelledby="tts__flight__detail-tab">
                                                        <div class="p-2">
                                                            <h3 class="tts__flight__details__heading px-2">
                                                                <img src="<?php echo site_url(); ?>webroot/img/svg_icon/flight.svg" style="width: 20px; height: 20px; filter: brightness(0%); margin-right: 3px;" alt="flight icon">
                                                                Delhi - Mumbai Sat, 12 Jun 2021
                                                            </h3>
                                                            <div class="row align-items-center">
                                                                <div class="col-2">
                                                                    <div class="">
                                                                        <img src="<?php echo site_url(); ?>webroot/img/indigo_icon.png" alt="indigo_icon">
                                                                        <span class="d-block tts__flight__span">Indigo</span>
                                                                        <span class="d-block tts__flight__span">6E-456</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-10">
                                                                    <div class="">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-lg-4 col-12">
                                                                                <div class="text-left">
                                                                                    <span class="d-block tts__flight__span">12
                                                                                        Jun ' 21 , Sat</span>
                                                                                    <span class="tts__flight__time">DEL
                                                                                        04:45</span>
                                                                                    <span class="d-block tts__flight__span">Delhi
                                                                                        India</span>
                                                                                    <span class="d-block">Terminal - 1</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-12">
                                                                                <div class="text-center">
                                                                                    <span class="tts__flight__border__dotted">
                                                                                        <span class="d-block tts__flight__span py-1">02h
                                                                                            : 15m</span>
                                                                                        <img src="<?php echo site_url(); ?>webroot/img/svg_icon/take-off.svg" class="tts__take__off" alt="take-off">
                                                                                        <img src="<?php echo site_url(); ?>webroot/img/svg_icon/arrival.svg" class="tts__arrival" alt="arrival">
                                                                                    </span>


                                                                                    <span class="d-block tts__flight__span py-1">Flight
                                                                                        Duration</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-4 col-12">
                                                                                <div class="text-right">
                                                                                    <span class="d-block tts__flight__span">12
                                                                                        Jun ' 21 , Sat</span>
                                                                                    <span class="tts__flight__time">BOM
                                                                                        07:00</span>
                                                                                    <span class="d-block tts__flight__span">Mumbai
                                                                                        India
                                                                                        Mumbai</span>
                                                                                    <span class="d-block">Terminal - 1</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="text-capitalize mb-2 pt-2 px-2"><b>Airline
                                                                    Remark:</b>TestHAP. API Fare-6E.</p>
                                                            <div class="px-2 pt-1 border-top">
                                                                <div class="row align-items-center">
                                                                    <div class="col-3">
                                                                        <span class="text-capitalize"><b>Fare Class : </b>
                                                                            R</span>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <img src="<?php echo site_url(); ?>webroot/img/svg_icon/seatleft.svg" style="width: 16px" alt="seatleft">
                                                                        <span>9 Seat left </span>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <span>Refundable</span>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <span class="text-capitalize "><b>Craft Type : </b>
                                                                            Airbus A320</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade hide" id="tts__flight__fare__backup" role="tabpanel" aria-labelledby="tts__flight__fare__backup-tab">
                                                        <div class="p-2">
                                                            <h3 class="tts__flight__details__heading px-2">
                                                                Fare breakup
                                                            </h3>
                                                            <table class="table tts__table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Type</th>
                                                                        <th>Fare</th>
                                                                        <th>Fare</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th>Adult (s)</th>
                                                                        <td>₹ 100 x 1</td>
                                                                        <td>₹ 100</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Tax Amount</th>
                                                                        <td>₹ 500</td>
                                                                        <td>₹ 500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Other Amount</th>
                                                                        <td>₹ 50</td>
                                                                        <td>₹ 50</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Discount</th>
                                                                        <td>₹ - 113</td>
                                                                        <td>₹ - 113</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><b>Total Amount</b></th>
                                                                        <td> </td>
                                                                        <td><b>₹ 537</b></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade hide" id="tts__flight__fare__rule" role="tabpanel" aria-labelledby="tts__flight__fare__rule-tab">
                                                        <div class="p-2">
                                                            <h3 class="tts__flight__details__heading ">
                                                                6E : Delhi ( DEL ) - Mumbai ( BOM )
                                                            </h3>
                                                            <p class="mb-2">The FareBasisCode is: RTCT</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade hide" id="tts__flight__baggage" role="tabpanel" aria-labelledby="tts__flight__baggage-tab">
                                                        <div class="p-2 border-bottom">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <div class="pl-2">
                                                                        <span class="">Airline</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="">
                                                                        <span class="">Check In</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="pr-2">
                                                                        <span class="">Cabin Baggage</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-2">
                                                                    <div class="pl-2">
                                                                        <img src="<?php echo site_url(); ?>webroot/img/indigo_icon.png" alt="indigo_icon">
                                                                        <span class="d-block tts__flight__span">Indigo</span>
                                                                        <span class="d-block tts__flight__span">6E-456</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="">
                                                                        <span class=""><b>15 KG Check In Baggage
                                                                                Included</b></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="pr-2">
                                                                        <span class=""><b>7 KG Cabin Baggage Included</b></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    <div class="col-sm-12 mt-3">
                        <div class="flight-rowmain">
                    <div class="row">
                          <div class="col-sm-6">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="flight-leftresult1">
                                            <ul class="flight-listair d-flex align-items-center ">
                                                <li class=""><img class="airline-logo " src="//static.travelimpression.in/img/airlineLogo/v1/6E.png"></li>
                                                <li class="">
                                                     IndiGo

                                                    <div class="flight-holdid">
                                                        <span class="flightids" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="6E-5031,6E-2048">6E-5031,6E-2048</span>

                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-4">
                                       <ul class="flight-listair">
                                          <li class="">
                                             <p class="flight-city">SXR</p>
                                             <span class="flight-timefont">18:20</span> <br><span class="flight-dur-timefont">Aug 18</span>
                                          </li>
                                       </ul>
                                    </div>
                                    <div class="col-sm-2 col-4 text-center">
                                       <div class="atls-holdid"><span class="flight-arrow">1 Stop(s)</span></div>
                                       <span class="fa fa-long-arrow-right d-block "></span>7h 35m
                                    </div>
                                    <div class="col-sm-2 col-4 text-end">
                                       <ul class="flight-listair">
                                          <li class="">
                                             <p class="flight-city">BLR</p>
                                             <span class="flight-timefont">01:55</span> <br><span class=" flight-dur-timefont">Aug 19</span>
                                          </li>
                                       </ul>
                                    </div>
                                    <div class="view-details-mainbtn">
                                        <button type="button" class="btn flight-viewbtn">View Details <i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-sm-12 clearfix">
                                        <div class="indicator-content">
                                            <span class="flightarrives-after"> <i class="fa fa-plane flightarrive-icons"></i> Flight Arrives after 1 Day(s)</span>
                                            <span class="handbag-icons handbag-icons-positionHandle" style="color:#c90201;"> Seats left: 9</span>
                                        </div>
                                    </div>
                                </div>
                          </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <ul class="flight-radiolist ">
                                                 <li class="">
                                                    <div class="form-check">
                                                      
                                                       <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault10">
                                                      <label class="form-check-label" for="flexRadioDefault10">
                                                        <span>₹8,333.70 </span>
                                                      </label>
                                                      
                                                      <ul class="d-flex align-items-center mb-2">
                                                          <li class="label-warning ">Coupon</li>
                                                          <li class="">
                                                              <span class="ars-refunsleft ars-lastre">Economy<span class="cursor-pointer">, Refundable</span></span>
                                                          </li>
                                                      </ul>
                                                    </div>
                                                 </li>
                                                 <hr class="m-0">
                                                 <li class="">
                                                    <div class="form-check">
                                                     
                                                       <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault11">
                                                      <label class="form-check-label" for="flexRadioDefault11">
                                                        <span>₹8,333.70 </span>
                                                      </label>
                                                      
                                                      <ul class="d-flex align-items-center mb-2">
                                                          <li class="label-warning ">Coupon</li>
                                                          <li class="">
                                                              <span class="ars-refunsleft ars-lastre ">Economy<span class="cursor-pointer">, Refundable</span></span>
                                                          </li>
                                                      </ul>
                                                    </div>
                                                 </li>
                                                  <hr class="m-0">
                                                 <li class="">
                                                    <div class="form-check">
                                                       <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault12">
                                                      <label class="form-check-label" for="flexRadioDefault12">
                                                        <span>₹8,333.70 </span>
                                                      </label>
                                                      
                                                      <ul class="d-flex align-items-center  mb-2">
                                                          <li class="label-warning ">Coupon</li>
                                                          <li class="">
                                                              <span class="ars-refunsleft ars-lastre ">Economy<span class="cursor-pointer">, Refundable</span></span>
                                                          </li>
                                                      </ul>
                                                    </div>
                                                 </li>
                                                
                                       </ul>
                                    </div>
                                     <div class="col-sm-3"><button type="button" class="btn flight-book" id="flightdetails">BOOK</button></div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                           <div class="col-12 col-md-12">
                               <div class="flight-list-tab">
                                   <nav class="d-flex align-items-center justify-content-between border-bottom">
                                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active flight-list-tab-btn" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Flight Details
                                        </button>
                                        <button class="nav-link flight-list-tab-btn" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false" tabindex="-1">Fare Details
                                        </button>
                                        <button class="nav-link flight-list-tab-btn" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false" tabindex="-1">Fare Rules
                                        </button>
                                        <button class="nav-link flight-list-tab-btn" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false" tabindex="-1">Baggage Information
                                        </button>
                                      </div>
                                        <div class="">
                                           <button type="button" class="btn cross-btn"> <i class="fas fa-times"></i></button>
                                        </div>
                                    </nav>
                                    <div class="tab-content flight-list-tab-content" id="nav-tabContent">
                                      <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                          <p class="flight-details-top-list">
                                            <b class="">
                                                <span>Delhi</span>
                                                <span class="ars-arright">→</span>
                                                <span>Bengaluru</span>
                                            </b>
                                            <span class="graycolor"> Tue, Aug 23rd 2022</span>
                                         </p>
                                         <div class="row align-items-center">
                                            <div class="col-sm-3 pr-0">
                                                <ul class="flight-listair1 d-flex ">
                                                     <li><img class="fightairline-logo " src="//static.travelimpression.in/img/airlineLogo/v1/SG.png"></li>
                                                     <li>
                                                        <div class="flight-holdid">
                                                           <span class="at-fontweight arct-idcode">SG-8190</span><span class="equipType"><i class="fa fa-plane"></i>-7M8</span>
                                                        </div>
                                                     </li>
                                                  </ul>
                                               </div>
                                               <div class="col-sm-7">
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <div class="flight-listair1">
                                                                
                                                                <span>Aug 23, Tue, 20:25</span> 
                                                                <br><span class="at-fontweight atb-airport graycolor">Delhi, India</span> 
                                                                <span class="at-fontweight atb-airport graycolor">Delhi Indira Gandhi Intl</span> 
                                                                <span class="graycolor">Terminal 3</span>
                                                            
                                                            </div>
                                                        </div>
                                                         <div class="col-sm-2 text-center">
                                                           
                                                                <span class="ars-lsprice ars-prclist atb-iconclass abt-nnstop stop-arrowline">Non-Stop</span>
                                                                <span class="fa fa-long-arrow-right d-block "></span>
                                                            
                                                        </div>
                                                         <div class="col-sm-5">
                                                            <div class="flight-listair1 text-end">
                                                                <span>Aug 23, Tue, 22:35</span> 
                                                                <br><span class="at-fontweight atb-airport graycolor">Pune, India</span> 
                                                                <span class="at-fontweight atb-airport graycolor">Lohegaon Arpt</span> 
                                                                <span class="graycolor">Terminal 1</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  <!-- <ul class="flight-listair1">
                                                     <li class="ars-lsprice ars-prclist ">Aug 23, Tue, 20:25 
                                                        <br><span class="at-fontweight atb-airport graycolor">Delhi, India</span> 
                                                        <span class="at-fontweight atb-airport graycolor">Delhi Indira Gandhi Intl</span> 
                                                        <span class="graycolor">Terminal 3</span>
                                                    </li>
                                                     <li class="ars-lsprice ars-prclist stop-arrowline">
                                                        <span class="ars-lsprice ars-prclist atb-iconclass abt-nnstop stop-arrowline">Non-Stop</span>
                                                        <span class="fa fa-long-arrow-right d-block text-center"></span>
                                                    </li>
                                                     <li class="ars-lsprice ars-prclist">Aug 23, Tue, 22:35 
                                                        <br><span class="at-fontweight atb-airport graycolor">Pune, India</span> 
                                                        <span class="at-fontweight atb-airport graycolor">Lohegaon Arpt</span> 
                                                        <span class="graycolor">Terminal 1</span>
                                                    </li>
                                                  </ul> -->
                                               </div>
                                               <div class="col-sm-2 text-end flight-listair1">
                                                  <span>2h 10m</span><br>
                                                  <span class="at-fontweight atb-airport graycolor">Economy</span>
                                                  <span class="at-fontweight atb-airport graycolor">CB:HR </span>
                                                  <span class=""> 4 seat(s) left</span>
                                               </div>
                                            </div>
                                      </div>
                                      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                          <div class="row">
                                              <div class="col-sm-3">
                                                <h5 class="flight-typefare">
                                                    TYPE
                                                </h5>
                                                
                                                
                                            </div>
                                            <div class="col-sm-4">
                                                <h5 class="flight-typefare">
                                                    Fare
                                                </h5>

                                            </div>
                                            <div class="col-sm-5">
                                                <h5 class="flight-typefare">Total</h5>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <span class="graycolor fare-details-text-label">Fare Details for Adult (CB: X)</span>
                                              <div class="col-sm-3">
                                                
                                                  <ul class="ars-trasfee">
                                                    <li class="list-fare-ddetials-content"><span>Base Price</span></li>
                                                    <li class="list-fare-ddetials-content"><span>Taxes and fees</span>
                                                        <i class="fa fa-info info-fa-icon"></i>
                                                    </li>
                                                </ul>
                                              </div>
                                              <div class="col-sm-4">
                                               
                                                  <ul class="ars-trasfee">
                                                    <li class="list-fare-ddetials-content"><span>₹3,700.00 x 1</span></li>
                                                    <li class="list-fare-ddetials-content"><span>₹679.00 x 1</span></li>
                                                </ul>
                                              </div>
                                              <div class="col-sm-5">
                                               
                                                  <ul class="ars-trasfee">
                                                    <li class="list-fare-ddetials-content"><span>₹3,700.00</span></li>
                                                    <li class="list-fare-ddetials-content"><span>₹679.00</span></li>
                                                </ul>
                                              </div>
                                          </div>
                                          <div class="row border-top">
                                              <div class="col-sm-7 mt-2">
                                                  <h4>Total</h4>
                                              </div>
                                              <div class="col-sm-5 mt-2">
                                                  <h4>₹4,379.00</h4>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                                          <div class="row">
                                               <div class="col-md-12">
                                                   <button class="ars-activelist fare-rules-tabs">DEL-IXC</button>
                                                   <div class="star-text">Mentioned fees are Per Pax Per Sector</div>
                                               </div> 
                                            <div class="col-sm-1">
                                                <h5 class="flight-typefare at-fontweight">
                                                    TYPE
                                                </h5> 
                                                <p class="graycolor">ALL</p>   
                                            </div>
                                            <div class="col-sm-3">
                                                <h5 class="flight-typefare at-fontweight">
                                                    Cancellation Fee
                                                </h5>
                                                 <p>
                                                     ₹3,500 +₹50 within 3hrs - 72 hrs of departure. Rs 3000 + Rs 50 before 72hrs of departure.
                                                 </p>   
                                            </div>
                                            <div class="col-sm-3">
                                                <h5 class="flight-typefare at-fontweight">Date Change Fee</h5>
                                                <p>
                                                    ₹3,000 +₹50 or Airfare charges plus Fare difference will be charged (Whichever is lower).
                                                </p>
                                            </div>
                                            <div class="col-sm-3">
                                                <h5 class="flight-typefare at-fontweight">No Show</h5>
                                                <p>
                                                    Only taxes will be Refunded.
                                                </p>
                                            </div>
                                            <div class="col-sm-2">
                                                <h5 class="flight-typefare at-fontweight">Seat Chargeable</h5>
                                                <p>
                                                    Paid seats as per Airlines
                                                </p>
                                            </div>
                                          </div>
                                      </div>
                                      <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">
                                          <div class="row">
                                              <div class="col-sm-3">
                                                <h5 class="flight-typefare at-fontweight">
                                                    SECTOR
                                                </h5>
                                                
                                                
                                            </div>
                                            <div class="col-sm-4">
                                                <h5 class="flight-typefare at-fontweight">
                                                   CHECKIN
                                                </h5>

                                            </div>
                                            <div class="col-sm-5">
                                                <h5 class="flight-typefare at-fontweight">
                                                    CHECKIN
                                                </h5>
                                            </div>
                                          </div>
                                          <div class="baggage__data ">
                                              <div class="row">
                                                
                                                  <div class="col-sm-3">
                                                    
                                                      <ul class="ars-trasfee">
                                                        <li class="list-fare-ddetials-content"><span>DEL-IXC</span></li>
                                                        
                                                    </ul>
                                                  </div>
                                                  <div class="col-sm-4">
                                                   
                                                      <ul class="ars-trasfee">
                                                        <li class="list-fare-ddetials-content"><span>Adult : 15 Kg</span></li>
                                                        
                                                    </ul>
                                                  </div>
                                                  <div class="col-sm-5">
                                                   
                                                      <ul class="ars-trasfee">
                                                        <li class="list-fare-ddetials-content"><span>Adult : 7 Kg</span></li>
                                                        
                                                    </ul>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                    </div>    
                                </div>
                           </div>
                       </div>
                 </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
            <!-- oneway result ends here -->
        </div>
    </div>
</div>
<!-- flight oneway result page ends here -->




<style>

.flight_list_asp-btm .select-flightsname {
    text-align: left;
    padding-left: 33px !important;
    color: #fff;
}


</style>