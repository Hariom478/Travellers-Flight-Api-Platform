<section class="holiday_details" style="background-image: url(https://www.travkart.com/img/images/fabric_of_squares_gray.png); position: relative;" >
   <?php if ($holiday_list['Error']['ErrorCode'] == 0) {
      $result = $holiday_list['Result']; ?>
   <div class="container main-row2">
      <div class="row firstdiv-deata holiday_details_deets m0">
         <div class="col-md-7">
            <div class="holiday_detail_title ">
               <h4 class="m0"><?php echo $result['Name'] ?></h4>
               <p class="exploare-para m0"><?php echo $result['DaysNights'] ?></p>
            </div>
            <div class="holiday_detail_icons">
               <?php if ($result['Themes']) {
                  foreach ($result['Themes'] as $theme) {
                      if ($theme['Icon']) { ?>
               <span class="rignt-ic-mar packgdtshspan" data-bs-toggle="tooltip"
                  data-bs-placement="bottom" data-bs-title="<?php echo $theme['Name']; ?>">
               <img src="<?php echo $theme['Icon']; ?>" alt="<?php echo $theme['Name']; ?>"
                  class="icon-image img-fluid">
               </span>
               <?php }
                  }
                  } ?>
            </div>
         </div>
         <div class="col-md-5 text-end">
            <?php if ($result['BookOnline'] && $result['PriceOnRequest'] != 1) { ?>
            <div class="book-para"> Starting from
               <span class="book-rate"><i class="fa fa-inr"></i> <?php echo $result['ShowPrice'] ?>
               </span> per person
               <input type="hidden" name="rate_start_from" id="rate_start_from" value="1900">
            </div>
            <?php } else { ?>
            <div class="book-para">
               <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tts-holiday-query">
               Price On Request
               </button>
            </div>
            <?php } ?>
         </div>
      </div>
      <div class="row  mt-3">
         <div class="col-md-4 left-summery">
            <h3 class="summery-p">Package Summary</h3>
            <div class="col-sm-12 left-cons maar-boot">
               <h4 class="summery-incl">Inclusions</h4>
               <span>
               <?php if ($result['Includes']) {
                  foreach ($result['Includes'] as $include) {
                      ?><?php if ($include == 'Flight') { ?>
               <span class="badge mar-bot-5"> <i class="fa fa-fighter-jet icons-destinat"></i>Flight
               </span>
               <?php } ?><?php if ($include == 'Transfer') { ?>
               <span class="badge mar-bot-5"> <i class="fa fa-car icons-destinat"></i>Transfer
               </span>
               <?php } ?><?php if ($include == 'Hotel') { ?>
               <span class="badge mar-bot-5"> <i class="fa fa-hotel icons-destinat"></i>Hotel
               </span>
               <?php } ?>
               <?php if ($include == 'Meal') { ?>
               <span class="badge mar-bot-5"> <i class="fa fa-cutlery icons-destinat"></i>Meal
               </span>
               <?php } ?><?php }
                  } ?>
               </span>
            </div>



           <!-- <div class="col-sm-12 left-cons maar-boot">
               <h4 class="summery-incl">Departure Dates</h4>
               <p><i class="text-warning"></i>No Departure Dates Available</p>
               <div class="row">
                  <div class="col-4 col-md-4">               
                     <a  style="  margin-bottom:5px;" class="btn btn-default btn-xs btn-info">
                     Download Pdf</a>
                  </div>
                  <div class="col-4 col-md-4">
                     <a  style=" margin-bottom:5px;" class="btn btn-default btn-xs btn-success">
                     Download Doc</a>
                  </div>
                  <div class="col-4 col-md-4">
                     <a  class="btn btn-default btn-xs btn-warning" id="Price-Calender-tab"  style="margin-bottom:5px;">Price Calendar</a>
                  </div>
               </div>
            </div> -->
         
            <div class="col-sm-12 left-cons maar-boot">
               <h4 class="summery-incl">Enquire Now</h4>
               <div class="row">
                  <div class="col-md-4 ">
                     <p class="text-center">
                        <a class="ico-text" data-bs-toggle="modal" href="#tts-holiday-query" role="button">
                        <i class="fa fa-envelope icons-details"></i> <br><span>Send Enquiry</span> </a>
                     </p>
                  </div>
                  <div class="col-md-4 ">
                     <p class="text-center">
                        <a href="#" class="ico-text"> <i class="fa fa-phone icons-details"></i>
                        <br> <span>Get a Call Back</span></a>
                     </p>
                  </div>
                  <!-- <div class="col-md-4 ">
                     <p class="text-center">
                         <a href="#" class="ico-text">
                             <i class="fa fa-user-plus icons-details"></i>
                             <br>
                             <span>Refer A Friend</span></a>
                     </p>
                     </div>-->
               </div>
            </div>


            <div class="col-sm-12 left-cons maar-boot">
               <h4 class="summery-incl">Share It Now</h4>
               <div class="row">
                  <div class="col-sm-6 col-md-12">               
                      <div class="addthis_inline_share_toolbox"></div>
                  </div>
               </div>
            </div>
            <!-- <div class="col-sm-12 left-cons maar-boot">
               <h4 class="summery-incl">Map</h4>
               <div class="responsive-map">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d3502.4081695768123!2d77.38278006542902!3d28.617526491510013!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d28.617600699999997!2d77.3850765!4m5!1s0x390ceff35ce54fb3%3A0x909e7d6ae55b2c39!2sC%20Block%2C%20Sector%2063%2C%20Noida%2C%20Uttar%20Pradesh%20201307!3m2!1d28.6174823!2d77.384725!5e0!3m2!1sen!2sin!4v1659791867991!5m2!1sen!2sin"
                           width="100%" height="768" allowfullscreen="" loading="lazy"
                           referrerpolicy="no-referrer-when-downgrade"></iframe>
               </div>
               </div>-->
            <!DOCTYPE html>
            <html>
               <head>
                  <style>
                     #map {
                     height: 780px;
                     width: 100%;
                     }
                  </style>
               </head>
               <body>
                  <div class="map_list">
                     <div id="map"></div>
                  </div>
                  <script>
                     function initMap() {
                         var locations = JSON.parse('<?php echo json_encode($result['location_data'])?>');
                         if (locations) {
                             var map = new google.maps.Map(document.getElementById('map'), {
                                 zoom: 5,
                                 center: new google.maps.LatLng(locations[0][1], locations[0][2]),
                                 mapTypeId: google.maps.MapTypeId.ROADMAP
                             });
                     
                             var infowindow = new google.maps.InfoWindow();
                     
                             var marker, i;
                     
                             for (i = 0; i < locations.length; i++) {
                                 marker = new google.maps.Marker({
                                     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                     map: map
                                 });
                     
                                 google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                     return function () {
                                         infowindow.setContent(locations[i][0]);
                                         infowindow.open(map, marker);
                                     }
                                 })(marker, i));
                             }
                         }
                     }
                  </script>
                  <script async
                     src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc(env('google.mapsApiKey'), 'url'); ?>&callback=initMap"></script>
               </body>
            </html>
         </div>
         <div class="col-md-8 right-summery">
            <?php if ($result['Gallery']) { ?>
            <div class="col-md-12">
               <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
                  <!--<div class="carousel-indicators">
                     <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                             class="active" aria-current="true" aria-label="Slide 1"></button>
                     <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                             aria-label="Slide 2"></button>
                     <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                             aria-label="Slide 3"></button>
                     </div>-->
                  <div class="carousel-inner">
                     <?php
                        foreach ($result['Gallery'] as $galKey => $gallery) {
                            ?>
                     <div class="carousel-item <?php if ($galKey == 0) {
                        echo "active";
                        } ?>">
                        <img src="<?php echo $gallery['images'] ?>" class="d-block w-100"
                           alt="<?php echo $gallery['Title'] ?>">
                     </div>
                     <?php } ?>
                  </div>
                  <button class="carousel-control-prev" type="button"
                     data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button"
                     data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                  </button>
               </div>
            </div>
            <?php } ?>
            <div class="col-md-12">
               <ul class="nav nav-tabs holiday_tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                     <button class="nav-link active" id="Overview-tab" data-bs-toggle="tab"
                        data-bs-target="#Overview-tab-pane" type="button" role="tab"
                        aria-controls="Overview-tab-pane" aria-selected="true">Overview
                     </button>
                  </li>
                  <?php if ($result['BookOnline'] && $result['PriceOnRequest'] != 1) { ?>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="Price-Calender-tab" data-bs-toggle="tab"
                        data-bs-target="#Price-Calender-tab-pane" type="button" role="tab"
                        aria-controls="Price-Calender-tab-pane" aria-selected="false">Price Calender
                     </button>
                  </li>
                  <?php } else { ?>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" data-bs-toggle="modal" data-bs-target="#tts-holiday-query">
                     Price On Request
                     </button>
                  </li>
                  <?php } ?>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="Inclusions-Exclusions-tab" data-bs-toggle="tab"
                        data-bs-target="#Inclusions-Exclusions-tab-pane" type="button" role="tab"
                        aria-controls="Inclusions-Exclusions-tab-pane" aria-selected="false">Inclusions
                     & Exclusions
                     </button>
                  </li>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="Booking-Procedure-tab" data-bs-toggle="tab"
                        data-bs-target="#Booking-Procedure-tab-pane" type="button" role="tab"
                        aria-controls="Booking-Procedure-tab-pane" aria-selected="false">Booking
                     Procedure
                     </button>
                  </li>
                  <?php if ($result['BookOnline'] && $result['PriceOnRequest'] != 1) { ?>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="booking-online-tab" data-bs-toggle="tab"
                        data-bs-target="#booking-online-tab-pane" type="button" role="tab"
                        aria-controls="booking-online-tab-pane" aria-selected="false">Book Online
                     </button>
                  </li>
                  <?php } ?>
               </ul>
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="Overview-tab-pane" role="tabpanel"
                     aria-labelledby="Overview-tab" tabindex="0">
                     <div class="holiday_detail_tab_box">
                        <?php echo $result['Description']; ?>
                     </div>
                     <div class="holiday_detail_tab_box">
                        <?php if ($result['itinerary']) {
                           foreach ($result['itinerary'] as $itenKey => $itinerary) { ?>
                        <div class="clearfix main-iti-divs">
                           <div class="col-12 mb-3 itinere-head">
                              <span class="dayss">Day <?php echo $itenKey + 1 ?></span>
                              <span class="stay-span"><?php echo $itinerary['itinerary_title'] ?></span>
                              <span class="float-end me-2"> City: <?php echo $itinerary['city'] ?></span>
                           </div>
                           <div class="row ">
                              <div class="col-sm-3 text-center ">
                                 <img class=" itinerary_image img-thumbnail"
                                    src="<?php echo $itinerary['itinerary_image']; ?>"
                                    alt="<?php echo $result['Name'] ?>">
                              </div>
                              <div class="col-sm-9 text-center-res">
                                 <p class="content-tans-p "><?php echo $itinerary['itinerary_description'] ?></p>
                              </div>
                           </div>
                           <!--         <div class="daycountdiv_border">
                              <div class="row">
                                  <div class="col-lg-12">
                                      <div class="daycountdiv">
                                          <h2 class="day-count">Day 1</h2>
                                      </div>
                                      <div class="">
                                          <h4>Delhi</h4>
                                          <p>
                                              Welcome at the airport and transfer to the hotel .Overnight stay at hotel. hotel Metro view3*/ Jivitesh/Hotel J.P.Sidhartha5*.. No city tour. </p>
                                      </div>
                                  </div>
                              </div>
                              </div>-->
                           <?php if ($itinerary['flight_data']) {
                              $airline = explode('-', $itinerary['flight_data']['airline']) ?>
                           <div class="daycountdiv_border">
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="d-flex align-items-center">
                                       <h2 class="day-for"><i class="fa fa-plane"></i></h2>
                                       <h3>Flight</h3>
                                    </div>
                                 </div>
                                 <div class="col-sm-8">
                                    <div class="row align-items-center">
                                       <div class="col-md-4 col-4">
                                          <div class="flight-leftresult1">
                                             <ul class="flight-listair d-flex align-items-center ">
                                                <li class=""><span
                                                   class="airline-logo mx-2 domAirLogo size-28 x<?php echo $airline[0]; ?>"></span>
                                                </li>
                                                <li>
                                                   <?php echo $airline[1]; ?>
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                       <div class="col-md-2 col-2">
                                          <ul class="flight-listair">
                                             <li class="">
                                                <p class="flight-city mb-0"><?php echo $itinerary['flight_data']['origin'] ?></p>
                                                <span class="flight-timefont "><?php echo $itinerary['flight_data']['depart_time'] ?></span>
                                             </li>
                                          </ul>
                                       </div>
                                       <div class="col-md-4 col-4 text-center">
                                          <img src="<?php echo site_url('webroot/img/flight_icon.png') ?>"
                                             alt="" class="img-fluid ">
                                       </div>
                                       <div class="col-sm-2 col-2 text-end">
                                          <ul class="flight-listair">
                                             <li class="">
                                                <p class="flight-city ng-binding mb-0"><?php echo $itinerary['flight_data']['destination'] ?></p>
                                                <span class="flight-timefont "><?php echo $itinerary['flight_data']['arrival_time'] ?></span>
                                             </li>
                                          </ul>
                                       </div>
                                       <?php if ($itinerary['flight_data']['remark']) { ?>
                                       <div class="col-sm-12 mt-3">
                                          <p>
                                             <strong>Remark:</strong><?php echo $itinerary['flight_data']['remark'] ?>
                                          </p>
                                       </div>
                                       <?php } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                           <?php if ($itinerary['hotel_data']) { ?>
                           <div class="daycountdiv_border">
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="d-flex align-items-center">
                                       <h2 class="day-for"><i class="fa fa-hotel"></i></h2>
                                       <h3>Hotel</h3>
                                    </div>
                                 </div>
                                 <div class="col-sm-8">
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <p><?php echo $itinerary['hotel_data']['hotel_name'] ?></p>
                                          <p>
                                             <strong>City: </strong><?php echo $itinerary['hotel_data']['hotel_city'] ?>
                                          </p>
                                          <p>
                                             <strong>Address: </strong><?php echo $itinerary['hotel_data']['hotel_address'] ?>
                                          </p>
                                          <?php if ($itinerary['hotel_data']['remark']) { ?>
                                          <p>
                                             <strong>Remark: </strong><?php echo $itinerary['hotel_data']['remark'] ?>
                                          </p>
                                          <?php } ?>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                           <?php if ($itinerary['day_meal_plan']) { ?>
                           <div class="daycountdiv_border">
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="d-flex align-items-center">
                                       <h2 class="day-for"><i class="fa fa-cutlery"></i>
                                       </h2>
                                       <h3>Meal Plan</h3>
                                    </div>
                                 </div>
                                 <div class="col-sm-8">
                                    <div class="row">
                                       <div class="col-sm-4">
                                          <p><?php echo $itinerary['day_meal_plan']; ?></p>
                                       </div>
                                       <div class="col-sm-8">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                           <?php if ($itinerary['transfer_data']) { ?>
                           <div class="daycountdiv_border">
                              <div class="row">
                                 <div class="col-sm-4 col-4">
                                    <div class="d-flex align-items-center">
                                       <h2 class="day-for"><i class="fa fa-car"></i></h2>
                                       <h3><?php echo ucwords($itinerary['transfer_data']['transfer_type']) ?></h3>
                                    </div>
                                 </div>
                                 <div class="col-sm-8 col-8">
                                    <div class="row">
                                       <div class="col-sm-4 col-4">
                                          <p class="iflight-name"><?php echo ucwords($itinerary['transfer_data']['pickup']) ?></p>
                                       </div>
                                       <div class="col-sm-4 col-4">
                                          <img src="<?php echo site_url('webroot/img/car-xxl.png') ?>"
                                             alt="" class="img-fluid">
                                       </div>
                                       <div class="col-sm-4 col-4">
                                          <p class="iflight-name"><?php echo ucwords($itinerary['transfer_data']['drop']) ?></p>
                                       </div>
                                       <?php if ($itinerary['transfer_data']['remark']) { ?>
                                       <p>
                                          <strong>Remark: </strong><?php echo $itinerary['transfer_data']['remark'] ?>
                                       </p>
                                       <?php } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                        </div>
                        <?php }
                           } ?>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="Price-Calender-tab-pane" role="tabpanel"
                     aria-labelledby="Price-Calender-tab" tabindex="0">
                     <div class="holiday_detail_tab_box">
                        <h2 class="title_contanier allbelow">All Below Prices are Per Person in INR </h2>
                        <h4 class="title_contanier mt-3">Category: </h4>
                        <?php if ($result['StandardPrice'] != '' && $result['StandardPrice'] != 0) { ?>
                        <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                           tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>"
                           class="btn btn-xs btn-success" holiday-price-category="Standard">
                        <label class="categry-gre" for="">Standard</label> </a>
                        <?php } ?>
                        <?php if ($result['DeluxePrice'] != '' && $result['DeluxePrice'] != 0) { ?>
                        <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                           tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>"
                           class="btn btn-xs btn-primary tts-holiday-category"
                           holiday-price-category="Deluxe">
                        <label class="categry-gre" for="">Deluxe</label> </a>
                        <?php } ?>
                        <?php if ($result['LuxuryPrice'] != '' && $result['LuxuryPrice'] != 0) { ?>
                        <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                           tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>"
                           class="btn btn-xs btn-primary tts-holiday-category"
                           holiday-price-category="Luxury">
                        <label class="categry-gre" for="">Luxury</label> </a>
                        <?php } ?>
                        <hr>
                        <!--<div class="">
                           <div class="alert alert-warning holiday_alert"
                              style="    padding: 8px 9px; font-weight:bold; margin-bottom:15px; color: #F44336; font-family: sans-serif; font-size: 12px;">

                           </div>
                        </div>-->
                        <div class="w-100" tts-calender-holiday="true">
                           <?php echo $calender; ?>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="Inclusions-Exclusions-tab-pane" role="tabpanel"
                     aria-labelledby="Inclusions-Exclusions-tab" tabindex="0">
                     <div class="holiday_detail_tab_box">
                        <h2 class="title_contanier">Inclusions</h2>
                        <ul class="holiday_list">
                           <?php if ($result['Inclusion']) {
                              foreach ($result['Inclusion'] as $inclusion) { ?>
                           <li><?php echo $inclusion['Title'] ?></li>
                           <?php }
                              } ?>
                        </ul>
                        <hr>
                        <h2 class="title_contanier">Exclusions</h2>
                        <ul class="holiday_list">
                           <?php if ($result['Exclusion']) {
                              foreach ($result['Exclusion'] as $exclusion) { ?>
                           <li><?php echo $exclusion['Title'] ?></li>
                           <?php }
                              } ?>
                        </ul>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="Booking-Procedure-tab-pane" role="tabpanel"
                     aria-labelledby="Booking-Procedure-tab" tabindex="0">
                     <div class="holiday_detail_tab_box">
                        <h2 class="title_contanier">Booking Procedure</h2>
                        <p class="text-justify mb-0"> We have a variety of Products suiting the requirements
                           of everyone. You can select the tour as per your preference.
                           <br>Once you select a Tour and agreed upon the Terms and Conditions, You can
                           book the package online. Guests who are unable to book online, can deposit
                           clearing cheques in any of our Bank Accounts
                        </p>
                        <h5>What happens after you book?</h5>
                        <div class=" bank-details-main-div">
                           <ul class="holiday_list">
                              <li>Receive Holiday confirmation voucher within 24 hours.</li>
                              <li>Review travel dates, destination etc in confirmation voucher.</li>
                              <li> In case of discrepancy mail us / call us with your Booking ID at</li>
                           </ul>
                        </div>
                        <div class="holiday_list_tab_box d-flex align-items-start mt-3">
                           <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                              aria-orientation="vertical">
                              <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                 data-bs-target="#v-pills-home" type="button" role="tab"
                                 aria-controls="v-pills-home" aria-selected="true">Payment Structure
                              & Bank details
                              </button>
                              <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                 data-bs-target="#v-pills-profile" type="button" role="tab"
                                 aria-controls="v-pills-profile" aria-selected="false">Documentation
                              </button>
                              <button class="nav-link" id="v-pills-disabled-tab" data-bs-toggle="pill"
                                 data-bs-target="#v-pills-disabled" type="button" role="tab"
                                 aria-controls="v-pills-disabled" aria-selected="false">Cancellation
                              Policy & Charges
                              </button>
                              <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                 data-bs-target="#v-pills-messages" type="button" role="tab"
                                 aria-controls="v-pills-messages" aria-selected="false">Refund policy
                              </button>
                              <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                 data-bs-target="#v-pills-settings" type="button" role="tab"
                                 aria-controls="v-pills-settings" aria-selected="false">Terms &
                              Conditions
                              </button>
                           </div>
                           <div class="tab-content holiday_tab_content " id="v-pills-tabContent">
                              <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                 aria-labelledby="v-pills-home-tab" tabindex="0">
                                 <?php echo $result['PaymentStructure'] ?>
                              </div>
                              <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                 aria-labelledby="v-pills-profile-tab" tabindex="0">
                                 <?php echo $result['Documentation'] ?>
                              </div>
                              <div class="tab-pane fade" id="v-pills-disabled" role="tabpanel"
                                 aria-labelledby="v-pills-disabled-tab" tabindex="0">
                                 <?php echo $result['CancellationPolicy'] ?>
                              </div>
                              <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                 aria-labelledby="v-pills-messages-tab" tabindex="0">
                                 <?php echo $result['RefundPolicy'] ?>
                              </div>
                              <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                 aria-labelledby="v-pills-settings-tab" tabindex="0">
                                 <?php echo $result['Policies'] ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="booking-online-tab-pane" role="tabpanel"
                     aria-labelledby="booking-online-tab" tabindex="0">
                     <div class="holiday_detail_tab_box">
                        <h2 class="title_contanier">Booking Online</h2>
                        <form action="<?php echo site_url('holiday/holiday-block'); ?>" method="post"
                           tts-form="true" name="holiday_form">
                           <div class="row">
                              <div class="col-lg-4 col-sm-6 col-12 mb-3">
                                 <div class="form-group">
                                    <label class="" for="select-menu">Travel Date:</label>
                                    <input type="text" tts-travel-date="true" name="travel_date"
                                       class="form-control" readonly date-book-pick="true">
                                 </div>
                              </div>
                              <hr>
                              <div class="col-lg-4 col-sm-6 col-12 mb-3">
                                 <div class="row align-items-center">
                                    <div class="col-sm-6">
                                       <label for="email">Room Required : </label>
                                    </div>
                                    <div class="col-sm-6">
                                       <select class="form-control field-input22" id="txt_num_rooms"
                                          name="txt_num_rooms"
                                          onchange="javascript: showHideRooms(this.value);">
                                          <option value="1" selected="">01</option>
                                          <option value="2">02</option>
                                          <option value="3">03</option>
                                          <option value="4">04</option>
                                          <option value="5">05</option>
                                          <option value="7">07</option>
                                          <option value="8">08</option>
                                          <option value="9">09</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <?php
                                 $total_rooms_limit = 9;
                                 $max_child_Per_room_limit = 3;
                                 $max_adult_Per_room = 3;
                                 ?>
                              <input type="hidden" name="SearchTokenId"
                                 value="<?php echo $holiday_list['SearchTokenId']; ?>">
                              <input type="hidden" id="holiday_id" name="holiday_id"
                                 value="<?php echo dev_encode($result['id']) ?>">
                              <input type="hidden" id="total_rooms"
                                 value="<?php echo $total_rooms_limit; ?>">
                              <input type="hidden" id="maxChildren"
                                 value="<?php echo $max_child_Per_room_limit; ?>">
                              <input type="hidden" name="infantAgeTo" id="infantAgeTo" value="2">
                              <input type="hidden" name="tts_holiday_category" tts-holiday-category="true"
                                 value="<?php echo $holiday_list['HolidayType']; ?>">
                              <input type="hidden" name="tts_price_key" tts-price-key="true">
                              <?php for ($total_rooms = 1; $total_rooms <= $total_rooms_limit; $total_rooms++) { ?>
                              <div class="col-lg-4 col-sm-6 col-12"
                                 id="div_RoomOcc_<?php echo $total_rooms; ?>"
                                 style="display: <?php if ($total_rooms != 1) {
                                    echo 'none';
                                    } ?>">
                                 <div class="bord-bor">
                                    <h4> Room <?php echo $total_rooms; ?> </h4>
                                    <div class="row align-items-center mb-3">
                                       <div class="col-sm-4">
                                          <p class="mb-0">Adults: </p>
                                       </div>
                                       <div class="col-sm-8">
                                          <span class="right-align">
                                          <a class="change-col"
                                             onClick="plusOrMinus('txt_adults_<?php echo $total_rooms; ?>','minus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                             class="fa fa-minus zoom"></i></a>
                                          <input type="text" class="form-control inlinee field-input22" readonly
                                             name="txt_adults_<?php echo $total_rooms; ?>"
                                             id="txt_adults_<?php echo $total_rooms; ?>" value="1" maxlength="2">
                                          <a class="change-col"
                                             onClick="plusOrMinus('txt_adults_<?php echo $total_rooms; ?>','plus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                             class="fa fa-plus zoom"></i></a>
                                          </span>
                                       </div>
                                    </div>
                                    <div class="col-sm-12">
                                       <p><i>Age Above '<strong>11</strong>' treated as
                                          <strong>Adult</strong></i>
                                       </p>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                       <div class="col-sm-4">
                                          <p class="mb-0">Children: </p>
                                       </div>
                                       <div class="col-sm-8">
                                          <span class="right-align">
                                          <a class="change-col"
                                             onclick="plusOrMinus('txt_children_<?php echo $total_rooms; ?>','minus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                             class="fa fa-minus zoom"></i></a>
                                          <input type="text" class="form-control inlinee field-input22" readonly=""
                                             name="txt_children_<?php echo $total_rooms; ?>"
                                             id="txt_children_<?php echo $total_rooms; ?>" value="0" maxlength="1">
                                          <a class="change-col"
                                             onclick="plusOrMinus('txt_children_<?php echo $total_rooms; ?>','plus',0,<?php echo $max_child_Per_room_limit; ?>)"><i
                                             class="fa fa-plus zoom"></i></a>
                                          </span>
                                       </div>
                                    </div>
                                    <?php for ($max_child_Per_room = 1; $max_child_Per_room <= $max_child_Per_room_limit; $max_child_Per_room++) { ?>
                                    <div class="holiday_chd_age"
                                       id="div_ChildAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                       style="display:none;">
                                       <div class="row">
                                          <div class="col-sm-5">
                                             <p class="mb-0">Child Age: </p>
                                          </div>
                                          <div class="col-sm-7">
                                             <span class="right-align">
                                             <a class="change-col"
                                                onClick="plusOrMinus('txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>','minus',0,12)">
                                             <i class="fa fa-minus zoom"></i> </a>
                                             <input type="text" class="form-control inlinee inlinee22 field-input22"
                                                readonly
                                                name="txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                                id="txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                                value="0" maxlength="2">
                                             <a class="change-col"
                                                onClick="plusOrMinus('txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>','plus',0,11)">
                                             <i class="fa fa-plus zoom"></i> </a>
                                             <i class="neebed_1_1 d-flex align-items-center justify-content-between"
                                                id="neebed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                                style="display:none!important;">
                                             <input name="need_bed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                                class="d-block"
                                                id="need_bed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                                type="checkbox" value="1"/> Need Bed
                                             </i> </span>
                                          </div>
                                       </div>
                                    </div>
                                    <?php } ?>
                                 </div>
                              </div>
                              <?php } ?>
                           </div>
                           <!--<button class="btn btn-success">Continue</button>-->
                           <input class="btn btn-success" type="submit" value="Continue">
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php } else { ?>
   <div class="container">
      <div class="row m0">
         <div class="col-12 tts-api-error-msg" ng-if="ErrorCode!=0">
            <img src="<?php echo site_url('webroot/img/no-cab-found.png'); ?>" class="img-fluid">
            <h5 class="mt-4"><?php echo $holiday_list['Error']['ErrorMessage']; ?></h5>
            <p class="mb-2">No holiday found for your selected destination. Please change your criteria</p>
            <a href="<?php echo site_url('holiday'); ?>" class="btn btn-outline-danger">SEARCH HOLIDAYS</a>
         </div>
      </div>
   </div>
   <?php } ?>
</section>
<div class="modal fade holiday_details_modal" id="tts-holiday-query" aria-hidden="true"
   aria-labelledby="tts-holiday-queryLabel" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered   modal-lg">
      <div class="modal-content w-100">
         <div class="modal-header">
            <h5 class="modal-title" id="tts-holiday-queryLabel">Tell us what you are looking for</h5>
            <button type="button" class="holiday_close_btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i>
            </button>
         </div>
         <div class="modal-body">
            <!------form----->
            <form action="<?php echo site_url('holiday/holiday-query'); ?>" method="post" tts-form="true"
               name="holiday_query">
               <div class="row">
                  <div class="col-lg-4 col-sm-6 col-12">
                     <div class="form-group">
                        <label class="">Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name">
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-6 col-12">
                     <div class="form-group">
                        <label class="">Contact Number:</label>
                        <input type="text" name="mobile" class="form-control"
                           placeholder="Enter Contact Number">
                     </div>
                  </div>
                  <div class="col-md-4 col-sm-12 col-12">
                     <div class="form-group">
                        <label>Email Id:</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email Id">
                     </div>
                  </div>
                  <input type="hidden" name="holiday_id" value="<?php echo $result['id'] ?>">
                  <input type="hidden" name="holiday_name" value="<?php echo $result['Name'] ?>">
               </div>
               <div class="row">
                  <div class="col-lg-4 col-sm-6 col-12">
                     <div class="form-group">
                        <label class="" for="select-menu">Travel Date</label>
                        <input type="text" class="form-control" name="travel_date" travel-date-calendor="true"
                           readonly>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-6 col-12">
                     <div class="form-group">
                        <label>Duration</label>
                        <select class="custom-select d-block form-control" name="no_of_nights">
                           <?php for ($i = 1; $i <= 30; $i++) { ?>
                           <option value="<?php echo $i; ?>"><?php echo $i; ?> Night</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-12 col-12">
                     <div class="form-group">
                        <label>Person</label>
                        <select class="custom-select d-block form-control" name="no_of_person">
                           <?php for ($i = 1; $i <= 15; $i++) { ?>
                           <option value="<?php echo $i; ?>"><?php echo $i; ?> Persons</option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-12 col-sm-12 col-12">
                     <div class="form-group">
                        <label for="Textarea2">Comments</label>
                        <textarea class="form-control" name="comment" placeholder="Leave a comment here"
                           id="extarea2" style="height: 100px"></textarea>
                     </div>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-lg-12 col-sm-12 col-12 text-center">
                     <button class="btn btn-danger rounded-0" type="submit">Submit</button>
                  </div>
               </div>
            </form>
            <!-----end------->
         </div>
      </div>
   </div>
</div>


