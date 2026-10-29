<section class="holiday_details">
   <?php if ($holiday_list['Error']['ErrorCode'] == 0) {
      $result = $holiday_list['Result'];


      ?>
      <div class="container main-row2">
         <div class="row pb-3">
            <div class="col-md-8">
               <div class="breadcrumbsitem">
                  <ul class="list-inline forLogoAlignment">
                     <li class="list-inline-item"><a class="logo_color2" href="<?php echo site_url('/'); ?>"
                           title="home">Home</a>
                     </li>
                     <li class="list-inline-item" aria-current="page">
                        <span>
                           <?php echo $result['Name'] ?>
                        </span>
                     </li>
                  </ul>
               </div>
               <div class="tourName d-md-flex">
                  <div class="tourNameWrapper">
                     <div class="d-md-flex align-items-center">
                        <h1 class="package-title"> <span>
                              <?php echo $result['Name'] ?>
                           </span></h1>
                        <div class="d-flex count-info">
                           <div class="tourDays">
                              <span>
                                 <?php echo $result['DaysNights'] ?>
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="priceNDates">
                  <div class="d-md-flex justify-content-md-center justify-content-between priceDateConteiner">
                     <div class="price-box">
                        <div class="deals-box"><span class="deal">Super Deal Price</span></div>
                        <p class="priceStart"> STARTS FROM </p>
                        <div class="d-flex align-items-lg-center justify-content-end">
                           <span class="price-discounted"><i class="fa fa-inr"></i>
                              <?php echo $result['ShowPrice'] ?>
                           </span>
                        </div>
                        <p> per person</p>
                     </div>
                     <!-- <div class="selectDate">
                        <a class="btn-primary btn-lg full-width" data-bs-toggle="modal" href="#tts-holiday-query"
                           role="button"> <i class="fa fa-envelope"></i> Get a quote</a>
                     </div> -->
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-9 pr-photos ">
               <div class="package-photos">
                  <?php if ($result['Gallery']) { ?>
                     <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
                        <div class="carousel-inner">
                           <?php
                           foreach ($result['Gallery'] as $galKey => $gallery) {
                              ?>
                              <?php $filenameSA = root_url . 'uploads/holiday/' . $gallery['images']; ?>
                              <div class="carousel-item <?php if ($galKey == 0) {
                                 echo "active";
                              } ?>">
                                 <img src="<?php echo $filenameSA ?>" class="d-block w-100"
                                    alt="<?php echo $gallery['Title'] ?>">
                              </div>
                           <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                           data-bs-slide="prev">
                           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                           <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                           data-bs-slide="next">
                           <span class="carousel-control-next-icon" aria-hidden="true"></span>
                           <span class="visually-hidden">Next</span>
                        </button>
                     </div>
                  <?php } else { ?>
                     <?php $filenameSA = root_url . 'uploads/holiday/' . $result['HolidayImage']; ?>
                     <img alt="" class="img-fluid" src="<?php echo $filenameSA; ?>"
                        style="height:400px; width:100%; object-fit:cover;">
                  <?php } ?>
               </div>
               <div class="package-details-links">
                  <ul>
                     <li class="package-detail-link active"><a href="#Overview-tab">Overview</a></li>
                     <li class="package-detail-link"><a href="#PriceCalender">Price Calendar</a></li>
                     <li class="package-detail-link"><a href="#tourinformation">Inclusions & Exclusions</a></li>
                     <li class="package-detail-link"><a href="#Payment-Structure">Payment Structure & Bank details </a>
                     </li>
                     <li class="package-detail-link"><a href="#Documentation">Documentation </a></li>
                     <li class="package-detail-link"><a href="#Cancellation-Policy">Cancellation Policy & Charges</a></li>
                     <li class="package-detail-link"><a href="#Refund-policy">Refund policy</a></li>
                     <li class="package-detail-link"><a href="#Terms-Conditions">Terms & Conditions</a></li>
                     <?php if ($result['BookOnline']) { ?>
                        <li class="package-detail-link"><a href="#booking-online-tab">Book Online</a></li>
                     <?php } ?>
                  </ul>
               </div>
               <!-- Overview Starts -->
               <div class="Overview" id="Overview">
                  <div class="holiday_detail_tab_box p-3 bg-light">
                     <?php echo $result['Description']; ?>
                  </div>
                  <ul class="timeline">
                     <?php if ($result['itinerary']) {
                        foreach ($result['itinerary'] as $itenKey => $itinerary) { ?>
                           <li class="timeline-item ms-3 active">
                              <div class="timeline-arrow"></div>
                              <div id="accordion21813">
                                 <div class="card">
                                    <div class="card-header">
                                       <a class="card-link" data-bs-toggle="collapse" href="#description121813"
                                          aria-expanded="true">
                                          <span class="small text-gray timelinedate"><i class="fa fa-clock-o mr-1"></i>Day
                                             <?php echo $itenKey + 1 ?> (
                                             <?php echo $itinerary['itinerary_title'] ?>)
                                          </span>
                                          <h2 class="h5 mb-0 timelinetitle">
                                             <?php echo $itinerary['city'] ?>
                                          </h2>
                                       </a>
                                    </div>
                                    <div class="card-body pt-0">
                                       <?php $filenames = root_url . 'uploads/holiday/thumbnail/' . $itinerary['itinerary_image']; ?>
                                       <?php //if (file_exists($filenames)) {         ?>
                                       <div class="col-sm-3">
                                          <img class=" itinerary_image img-thumbnail" src="<?php echo $filenames; ?>"
                                             alt="<?php echo $result['Name'] ?>">
                                       </div>
                                       <?php //}         ?>
                                       <p class="text-small mt-2">
                                          <?php echo $itinerary['itinerary_description'] ?>
                                       </p>
                                       <!-- Flight Itinerary -->
                                       <?php if (isset($itinerary['flight_data']) && $itinerary['flight_data']) {
                                          $flighitinerarytDaTa = $itinerary['flight_data']; ?>
                                          <?php if (!empty($flighitinerarytDaTa)) { ?>
                                             <div class="i-inclusions">
                                                <?php
                                                foreach ($flighitinerarytDaTa as $flightdata) {
                                                   $airlines = isset($flightdata['airline']) ? $flightdata['airline'] : null;
                                                   $airline = explode('-', $airlines);

                                                   ?>
                                                   <ul>
                                                      <li class="break-fast"> <i class="fa fa-plane"></i> Flight</li>
                                                   </ul>
                                                   <div class="col-sm-8">
                                                      <div class="row align-items-center">
                                                         <div class="col-md-4 col-4">
                                                            <div class="flight-leftresult1">
                                                               <ul class="flight-listair d-flex align-items-center ">
                                                                  <li class=""><span
                                                                        class="airline-logo mx-2 domAirLogo size-28 x<?php echo $airline[0]; ?>"></span>
                                                                  </li>
                                                                  <li>
                                                                     <?php echo isset($airline[1]) ? $airline[1] : ""; ?>
                                                                  </li>
                                                               </ul>
                                                            </div>
                                                         </div>
                                                         <div class="col-md-2 col-2">
                                                            <p class="flight-city mb-0">
                                                               <?php echo $flightdata['origin']; ?>
                                                            </p>
                                                            <span class="flight-timefont ">
                                                               <?php echo $flightdata['depart_time'] ?>
                                                            </span>
                                                         </div>
                                                         <div class="col-md-4 col-4 text-center">
                                                            <img src="<?php echo site_url('webroot/img/flight_icon.png') ?>" alt=""
                                                               class="img-fluid ">
                                                         </div>
                                                         <div class="col-sm-2 col-2 text-end">
                                                            <p class="flight-city ng-binding mb-0">
                                                               <?php echo $flightdata['destination'] ?>
                                                            </p>
                                                            <span class="flight-timefont ">
                                                               <?php echo $flightdata['arrival_time'] ?>
                                                            </span>
                                                         </div>
                                                         <?php if ($flightdata['remark']) { ?>
                                                            <div class="col-sm-12 mt-3">
                                                               <p>
                                                                  <strong>Remark:</strong>
                                                                  <?php echo $flightdata['remark'] ?>
                                                               </p>
                                                            </div>
                                                         <?php } ?>
                                                      </div>
                                                   </div>
                                                <?php } ?>
                                             </div>
                                          <?php }
                                       } ?>
                                       <!-- Flight Itinerary Ends -->
                                       <!-- Hotel Itinerary Starts -->
                                       <?php if (isset($itinerary['hotel_data']) && $itinerary['hotel_data']) {
                                          $hotelitinerarytDaTa = $itinerary['hotel_data']; ?>
                                          <?php if (!empty($hotelitinerarytDaTa)) { ?>
                                             <div class="i-inclusions">
                                                <?php foreach ($hotelitinerarytDaTa as $hoteldata) { ?>
                                                   <ul>
                                                      <li class="break-fast"> <i class="fa fa-hotel"></i> Hotel</li>
                                                   </ul>
                                                   <div class="col-sm-12">
                                                      <div class="row align-items-center">
                                                         <div class="col-sm-4">
                                                            <p>
                                                               <?php echo $hoteldata['hotel_name'] ?>
                                                            </p>
                                                         </div>
                                                         <div class="col-sm-4">
                                                            <p>
                                                               <strong>City: </strong>
                                                               <?php echo $hoteldata['hotel_city'] ?>
                                                            </p>
                                                         </div>
                                                         <div class="col-sm-4">
                                                            <p>
                                                               <strong>Address: </strong>
                                                               <?php echo $hoteldata['hotel_address'] ?>
                                                            </p>
                                                         </div>
                                                         <?php if ($hoteldata['remark']) { ?>
                                                            <div class="col-sm-12 mt-3">
                                                               <p>
                                                                  <strong>Remark: </strong>
                                                                  <?php echo $hoteldata['remark'] ?>
                                                               </p>
                                                            </div>
                                                         <?php } ?>
                                                      </div>
                                                   </div>
                                                <?php } ?>
                                             </div>
                                          <?php }
                                       } ?>
                                       <!-- Hotel Itinerary Ends -->
                                       <!-- Day Meal Starts -->
                                       <?php if ($itinerary['day_meal_plan']) { ?>
                                          <div class="i-inclusions">
                                             <ul>
                                                <li class="break-fast"> <i class="fa fa-cutlery"></i>Meal Plan</li>
                                             </ul>
                                             <div class="col-sm-8">
                                                <div class="row">
                                                   <div class="col-sm-4">
                                                      <p>
                                                         <?php echo $itinerary['day_meal_plan']; ?>
                                                      </p>
                                                   </div>
                                                   <div class="col-sm-8">
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       <?php } ?>
                                       <!-- Day Meal Ends -->
                                       <!-- Transfer Itineraryl Starts -->
                                       <?php if (isset($itinerary['transfer_data']) && $itinerary['transfer_data']) {
                                          $transferitinerarytDaTa = $itinerary['transfer_data']; ?>
                                          <?php if (!empty($transferitinerarytDaTa)) { ?>
                                             <div class="i-inclusions">
                                                <?php foreach ($transferitinerarytDaTa as $transferData) { ?>
                                                   <ul>
                                                      <li class="break-fast"> <i class="fa fa-car"></i>
                                                         <?php echo ucwords($transferData['transfer_type']) ?>
                                                      </li>
                                                   </ul>
                                                   <div class="col-sm-8 col-12">
                                                      <div class="row">
                                                         <div class="col-sm-4">
                                                            <p class="iflight-name">
                                                               <?php echo ucwords($transferData['pickup']) ?>
                                                            </p>
                                                         </div>
                                                         <div class="col-sm-4">
                                                            <img src="<?php echo site_url('webroot/img/car-xxl.png') ?>" alt=""
                                                               class="img-fluid">
                                                         </div>
                                                         <div class="col-sm-4">
                                                            <p class="iflight-name">
                                                               <?php echo ucwords($transferData['drop']) ?>
                                                            </p>
                                                         </div>
                                                         <?php if ($transferData['remark']) { ?>
                                                            <p>
                                                               <strong>Remark: </strong>
                                                               <?php echo $transferData['remark'] ?>
                                                            </p>
                                                         <?php } ?>
                                                      </div>
                                                   </div>
                                                <?php } ?>
                                             </div>
                                          <?php }
                                       } ?>
                                       <!-- Transfer Itineraryl Ends -->
                                    </div>
                                 </div>
                              </div>
                           </li>
                        <?php }
                     } ?>
                  </ul>
               </div>
               <!-- Overview Ends -->
               <!-- Price Calendar Starts -->


               <?php if ($result['BookOnline'] && $result['PriceOnRequest'] == 0) { ?>
                  <div class="Price-Calender tourdetails" id="PriceCalender">
                     <h2>Pricing Calendar</h2>
                     <div class="PriceCalenderbox">
                        <h3>All Below Prices are Per Person in INR</h3>
                        <h4 class="title_contanier">Category: </h4>
                        <?php if ($result['StandardPrice'] != '' && $result['StandardPrice'] != 0) { ?>
                           <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                              tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>" class="btn btn-sm btn-success"
                              holiday-price-category="Standard">
                              <label class="categry-gre" for="">Standard</label> </a>
                        <?php } ?>
                        <?php if ($result['DeluxePrice'] != '' && $result['DeluxePrice'] != 0) { ?>
                           <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                              tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>"
                              class="btn btn-sm btn-primary tts-holiday-category" holiday-price-category="Deluxe">
                              <label class="categry-gre" for="">Deluxe</label> </a>
                        <?php } ?>
                        <?php if ($result['LuxuryPrice'] != '' && $result['LuxuryPrice'] != 0) { ?>
                           <a href="javascript: void(0);" tts-holiday-slug="<?php echo $result['Slug'] ?>"
                              tts-url-calendar="<?php echo site_url("holiday/fare-calender"); ?>"
                              class="btn btn-sm btn-primary tts-holiday-category" holiday-price-category="Luxury">
                              <label class="categry-gre" for="">Luxury</label> </a>
                        <?php } ?>
                        <hr>
                        <div class="w-100" tts-calender-holiday="true">
                           <?php echo $calender; ?>
                        </div>
                     </div>
                  </div>
               <?php } ?>
               <!-- Price Calendar Ends -->
               <!-- Tour Itinerary Starts -->
               <div class="tourdetails tourinformation" id="tourinformation">
                  <h2>Tour Information</h2>
                  <ul class="nav nav-tabs row" id="nav-tab" role="tablist">
                     <li class="nav-item col-md-4 col-12">
                        <a class="nav-link active" id="tourinclusions-tab" data-bs-toggle="tab" href="#tourinclusions"
                           role="tab" aria-controls="tourinclusions" aria-selected="true"> Inclusions </a>
                     </li>
                     <li class="nav-item col-md-4 col-12">
                        <a class="nav-link" id="tourexclusions-tab" data-bs-toggle="tab" href="#tourexclusions" role="tab"
                           aria-controls="tourexclusions" aria-selected="false"> Exclusions </a>
                     </li>
                     <li class="nav-item col-md-4 col-12">
                        <a class="nav-link" id="Booking-Procedure-tab" data-bs-toggle="tab" href="#bookingprocedure"
                           role="tab" aria-controls="bookingprocedure" aria-selected="false"> Booking Procedure </a>
                     </li>
                  </ul>
                  <div class="tab-content" id="nav-tabContent">
                     <div class="tab-pane fade active show" id="tourinclusions" role="tabpanel"
                        aria-labelledby="tourinclusions-tab">
                        <ul class="package-section-list bullet">
                           <?php if ($result['Inclusion']) {
                              foreach ($result['Inclusion'] as $inclusion) { ?>
                                 <li class="list-item">
                                    <?php echo $inclusion['Title'] ?>
                                 </li>
                              <?php }
                           } ?>
                        </ul>
                     </div>
                     <div class="tab-pane fade" id="tourexclusions" role="tabpanel" aria-labelledby="tourexclusions-tab">
                        <ul class="package-section-list exclusion-table bullet">
                           <?php if ($result['Exclusion']) {
                              foreach ($result['Exclusion'] as $exclusion) { ?>
                                 <li class="list-item">
                                    <?php echo $exclusion['Title'] ?>
                                 </li>
                              <?php }
                           } ?>
                        </ul>
                     </div>
                     <div class="tab-pane fade" id="bookingprocedure" role="tabpanel"
                        aria-labelledby="bookingprocedure-tab">
                        <h2>Booking Procedure</h2>
                        <p class="text-justify"> We have a variety of Products suiting the requirements
                           of everyone. You can select the tour as per your preference.
                           <br>Once you select a Tour and agreed upon the Terms and Conditions, You can
                           book the package online. Guests who are unable to book online, can deposit
                           clearing cheques in any of our Bank Accounts
                        </p>
                        <h5>What happens after you book?</h5>
                        <div class=" bank-details-main-div">
                           <ul class="package-section-list blackbullet bullet">
                              <li class="list-item">Receive Holiday confirmation voucher within 24 hours.</li>
                              <li class="list-item">Review travel dates, destination etc in confirmation voucher.</li>
                              <li class="list-item"> In case of discrepancy mail us / call us with your Booking ID at</li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tourdetails tourinformation" id="Payment-Structure">
                  <div class="bg-light p-3">
                     <h2>Payment Structure & Bank details</h2>
                     <?php echo $result['PaymentStructure'] ?>
                  </div>
               </div>
               <div class="tourdetails tourinformation" id="Documentation">
                  <div class="bg-light p-3">
                     <h2>Documentation</h2>
                     <?php echo $result['Documentation'] ?>
                  </div>
               </div>
               <div class="tourdetails tourinformation" id="Cancellation-Policy">
                  <div class="bg-light p-3">
                     <h2>Cancellation Policy & Charges</h2>
                     <?php echo $result['CancellationPolicy'] ?>
                  </div>
               </div>
               <div class="tourdetails tourinformation" id="Refund-policy">
                  <div class="bg-light p-3">
                     <h2>Refund policy</h2>
                     <?php echo $result['RefundPolicy'] ?>
                  </div>
               </div>
               <div class="tourdetails tourinformation" id="Terms-Conditions">
                  <div class="bg-light p-3">
                     <h2>Terms & Conditions</h2>
                     <?php echo $result['Policies'] ?>
                  </div>
               </div>
            </div>
            <div class="col-md-3 mt-5 mt-lg-0">
               <div class="packageIncludeNActions">
                  <div class="packageInclude">
                     <h2>Tour includes</h2>
                     <div class="includeWrapper">
                        <div class="includeList d-flex">
                           <?php if ($result['Includes']) {
                              foreach ($result['Includes'] as $include) {
                                 ?>
                                 <?php if ($include == 'Flight') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class="fa-solid fa-plane-departure"></i>
                                       </div>
                                       <span>Flight</span>
                                    </div>
                                 <?php } ?>
                                 <?php if ($include == 'Transfer') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class="fa-solid fa-car"></i>
                                       </div>
                                       <span>Transfer</span>
                                    </div>
                                 <?php } ?>
                                 <?php if ($include == 'Hotel') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class="fa-solid fa-hotel"></i>
                                       </div>
                                       <span>Hotel</span>
                                    </div>
                                 <?php } ?>
                                 <?php if ($include == 'Meal') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class="fa-solid fa-cutlery"></i>
                                       </div>
                                       <span>Meal</span>
                                    </div>
                                 <?php } ?>

                                 <?php if ($include == 'Cruise') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class="fa-solid fa-ship"></i>
                                       </div>
                                       <span>Cruise</span>
                                    </div>
                                 <?php } ?>
                                 <?php if ($include == 'Tourguide') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <span><svg width="22px" height="22px" viewBox="0 -2 24 24"
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
                                             </svg></span>
                                       </div>
                                       <span>Cruise</span>
                                    </div>
                                 <?php } ?>
                                 <?php if ($include == 'Sightseeing') { ?>
                                    <div class="hotelInclude">
                                       <div class="iconsHolder hotelInclude">
                                          <i class='fas'>&#xf59f;</i>
                                       </div>
                                       <span>Sightseeing</span>
                                    </div>
                                 <?php } ?>
                              <?php }
                           } ?>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="userInfoWrapper world-user ">
                  <div class="box-header d-flex">
                     <span>Package SUMMARY</span>
                  </div>
                  <div class="reviewInfo d-flex flex-column">
                     <hr class="hr" />
                     <div class="summary-hight-holder">
                        <div
                           class="reviewHeader d-flex justify-content-between justify-content-md-start justify-content-lg-between">
                           <div class="departureBox d-flex">
                              <div class="label">City</div>
                              <?php
                              foreach ($result['Destinations'] as $destn) {
                                 ?>
                                 <div class="value">
                                    <?= $destn['Name'] . ",  " ?>
                                 </div>
                                 <?php
                              }
                              ?>
                           </div>
                        </div>
                        <div class="reviewBody">
                           <div class="travellerBox">
                              <div class="label"> Price (per person) </div>
                              <div class="value submittedValue">
                                 <?php if (isset($result['StandardPrice']) && $result['StandardPrice']) { ?>
                                    <p>Standard Package <span class=""> : </span><span class=""> ₹
                                          <?php echo $result['StandardPrice']; ?>
                                       </span></p>
                                 <?php } ?>
                                 <?php if (isset($result['DeluxePrice']) && $result['DeluxePrice']) { ?>
                                    <p>Deluxe Package <span class=""> : </span><span class=""> ₹
                                          <?php echo $result['DeluxePrice']; ?>
                                       </span></p>
                                 <?php } ?>
                                 <?php if (isset($result['LuxuryPrice']) && $result['LuxuryPrice']) { ?>
                                    <p>Luxury Package <span class=""> : </span><span class=""> ₹
                                          <?php echo $result['LuxuryPrice']; ?>
                                       </span></p>
                                 <?php } ?>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="mt-auto">
                        <div class="reviewBody price-section mt-auto">
                           <div class="price d-flex justify-content-between">
                              <div class="priceBox d-flex">
                                 <div class="w-100 mt-md-auto mt-2">
                                    <div class="label">Basic Price</div>
                                    <!-- <div class="priceBreak">
                                    <a href="javascript:void(0)" class="breakPriceTag link-button ">View Pricing Table</a>
                                    </div> -->
                                 </div>
                              </div>
                              <div class="priceValueBox">
                                 <div class="actualPrice "><i class="fa fa-inr"></i>
                                    <?= $result['ShowPrice'] ?>
                                 </div>
                                 <div class="note ">per person</div>
                              </div>
                           </div>
                        </div>
                     </div>
                     </hr>
                     <div class="proceedingPatch">
                        <div class="afterConfirmButtons ">
                           <a class="btn-primary w-100 btn-sm" data-bs-toggle="modal" href="#tts-holiday-query">Get a
                              quote</a>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="userInfoWrapper freecallus">
                  <div class="reviewInfo d-flex flex-column p-0">
                     <div class="box-header d-flex">
                        <span>Feel free to reach us</span>
                     </div>
                     <div class="summary-hight-holder">
                        <div class="reviewBody">
                           <!-- <div class="departureBox">
                           <div class="label">Toll Free No : </div>
                           <div class="value ">1800-532-3636, 1800-212-0136</div>
                           </div> -->
                           <div class="departureBox">
                              <div class="label">24/7 Service : </div>
                              <div class="value ">
                                 <?php echo web_partner_details['support_no'] ?>
                              </div>
                           </div>
                           <div class="departureBox">
                              <div class="label">Mail Us : </div>
                              <div class="value ">
                                 <?php echo web_partner_details['support_email'] ?>
                              </div>
                           </div>
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
               <h5 class="mt-4">
                  <?php echo $holiday_list['Error']['ErrorMessage']; ?>
               </h5>
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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('holiday/holiday-query'); ?>" method="post" tts-form="true"
            name="holiday_query">
            <!------form----->
            <div class="modal-body">
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
                        <input type="text" name="mobile" class="form-control" placeholder="Enter Contact Number">
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
                        <input type="text" class="form-control" name="travel_date" travel-date-calendor="true" readonly>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-6 col-12">
                     <div class="form-group">
                        <label>Duration</label>
                        <select class="custom-select d-block form-select" name="no_of_nights">
                           <?php for ($i = 1; $i <= 30; $i++) { ?>
                           <option value="<?php echo $i; ?>">
                              <?php echo $i; ?> Night
                           </option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4 col-sm-12 col-12">
                     <div class="form-group">
                        <label>Person</label>
                        <select class="custom-select d-block form-select" name="no_of_person">
                           <?php for ($i = 1; $i <= 15; $i++) { ?>
                           <option value="<?php echo $i; ?>">
                              <?php echo $i; ?> Persons
                           </option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-12 col-sm-12 col-12">
                     <div class="form-group">
                        <label for="Textarea2">Comments</label>
                        <textarea class="form-control" name="comment" placeholder="Leave a comment here" id="extarea2"
                           style="height: 100px"></textarea>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <div class="row">
                  <div class="col-lg-12 col-sm-12 col-12 text-center">
                     <button class="btn btn-danger" type="submit">Submit</button>
                  </div>
               </div>
            </div>
         </form>
         <!-----end------->
      </div>
   </div>
</div>
<div class="modal fade holiday_details_modal" id="tts-online-holiday-query" aria-hidden="true"
   aria-labelledby="tts-online-holiday-queryLabel" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered   modal-lg">
      <div class="modal-content w-100">
         <div class="modal-header">
            <h5 class="modal-title" id="tts-online-holiday-queryLabel">Booking Online</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('holiday/holiday-block'); ?>" method="post" tts-form="true"
            name="holiday_form">
            <div class="modal-body">
               <div class="row">
                  <div class="col-lg-4 col-sm-6 col-12 mb-3">
                     <div class="form-group">
                        <label class="form-label" for="select-menu">Travel Date:</label>
                        <input type="text" tts-travel-date="true" name="travel_date" class="form-control" readonly
                           date-book-pick="true">
                     </div>
                  </div>
                  <hr>
                  <div class="col-lg-4 col-sm-6 col-12 mb-3">
                     <div class="row align-items-center">
                        <div class="col-sm-6">
                           <label for="email">Room Required : </label>
                        </div>
                        <div class="col-sm-6">
                           <select class="form-select field-input22" id="txt_num_rooms" name="txt_num_rooms"
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
                  <input type="hidden" name="SearchTokenId" value="<?php echo $holiday_list['SearchTokenId']; ?>">
                  <input type="hidden" id="holiday_id" name="holiday_id"
                     value="<?php echo dev_encode($result['id']) ?>">
                  <input type="hidden" id="total_rooms" value="<?php echo $total_rooms_limit; ?>">
                  <input type="hidden" id="maxChildren" value="<?php echo $max_child_Per_room_limit; ?>">
                  <input type="hidden" name="infantAgeTo" id="infantAgeTo" value="2">
                  <input type="hidden" name="tts_holiday_category" tts-holiday-category="true"
                     value="<?php echo $holiday_list['HolidayType']; ?>">
                  <input type="hidden" name="tts_price_key" tts-price-key="true">
                  <?php for ($total_rooms = 1; $total_rooms <= $total_rooms_limit; $total_rooms++) { ?>
                  <div class="col-lg-4 col-sm-12 col-12" id="div_RoomOcc_<?php echo $total_rooms; ?>" style="display: <?php if ($total_rooms != 1) {
                        echo 'none';
                     } ?>">
                     <h4> Room
                        <?php echo $total_rooms; ?>
                     </h4>
                     <div class="row align-items-center mb-3">
                        <div class="col-sm-4">
                           <p class="mb-0">Adults: </p>
                        </div>
                        <div class="col-sm-8">
                           <div class="GwMit">
                              <a class="change-col"
                                 onClick="plusOrMinus('txt_adults_<?php echo $total_rooms; ?>','minus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                    class="fa fa-minus zoom"></i></a>
                              <input type="text" class="form-control inlinee field-input22" readonly
                                 name="txt_adults_<?php echo $total_rooms; ?>"
                                 id="txt_adults_<?php echo $total_rooms; ?>" value="1" maxlength="2">
                              <a class="change-col"
                                 onClick="plusOrMinus('txt_adults_<?php echo $total_rooms; ?>','plus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                    class="fa fa-plus zoom"></i></a>
                           </div>
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <p><i>Age Above '<strong>11</strong>' treated as <strong>Adult</strong></i></p>
                     </div>
                     <div class="row align-items-center mb-3">
                        <div class="col-sm-4">
                           <p class="mb-0">Children: </p>
                        </div>
                        <div class="col-sm-8">
                           <div class="GwMit">
                              <a class="change-col"
                                 onclick="plusOrMinus('txt_children_<?php echo $total_rooms; ?>','minus',0,<?php echo $max_adult_Per_room; ?>)"><i
                                    class="fa fa-minus zoom"></i></a>
                              <input type="text" class="form-control inlinee field-input22" readonly=""
                                 name="txt_children_<?php echo $total_rooms; ?>"
                                 id="txt_children_<?php echo $total_rooms; ?>" value="0" maxlength="1">
                              <a class="change-col"
                                 onclick="plusOrMinus('txt_children_<?php echo $total_rooms; ?>','plus',0,<?php echo $max_child_Per_room_limit; ?>)"><i
                                    class="fa fa-plus zoom"></i></a>
                           </div>
                        </div>
                     </div>
                     <?php for ($max_child_Per_room = 1; $max_child_Per_room <= $max_child_Per_room_limit; $max_child_Per_room++) { ?>
                     <div class="holiday_chd_age"
                        id="div_ChildAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                        style="display:none;">
                        <div class="row">
                           <div class="col-sm-4">
                              <p class="mb-0">Child Age: </p>
                           </div>
                           <div class="col-sm-8">
                              <div class="GwMit mb-2">
                                 <a class="change-col"
                                    onClick="plusOrMinus('txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>','minus',0,12)">
                                    <i class="fa fa-minus zoom"></i>
                                 </a>
                                 <input type="text" class="form-control inlinee inlinee22 field-input22" readonly
                                    name="txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                    id="txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                    value="0" maxlength="2">
                                 <a class="change-col"
                                    onClick="plusOrMinus('txt_childAge_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>','plus',0,11)">
                                    <i class="fa fa-plus zoom"></i>
                                 </a>
                              </div>
                              <div class="neebed_1_1 d-flex align-items-center"
                                 id="neebed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                 style="display:none!important;">
                                 <input name="need_bed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                    class="d-block"
                                    id="need_bed_<?php echo $total_rooms; ?>_<?php echo $max_child_Per_room; ?>"
                                    type="checkbox" value="1" /> Need Bed
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
                  <?php } ?>
               </div>
            </div>
            <div class="modal-footer">
               <input class="btn btn-success" type="submit" value="Continue">
            </div>
         </form>
         <!-----end------->
      </div>
   </div>
</div>