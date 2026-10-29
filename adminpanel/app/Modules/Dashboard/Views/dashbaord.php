<div class="page-content">
   <!--    <div class="row">
      <div class="col-lg-12">
         <h2 class="dashboard_title">Welcome to Dashboard</h2>
      </div>
   </div> -->
   <?php
   if (isset(admin_cookie_data()['admin_comapny_detail']['whitelabel_user']) && admin_cookie_data()['admin_comapny_detail']['whitelabel_user'] == "active") {
      $whitelabel_user_status = "active";
   } else {
      $whitelabel_user_status = "inactive";
   }
   $whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];
   ?>
   <div class="row">
      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
         <?php if (permission_access("Flight", "Flight_Module")) : ?>
            <?php if (isset($totle_booking_cont['Flight'])) { ?>
               <div class="col-lg-2">
                  <div class="card tile-stats">
                     <a href="<?php echo site_url('flight/bookings?source=dashboard') ?>" class="tile-red">
                        <div class="icon"><i class="fa-solid fa-plane"></i></div>
                        <div class="card-body">
                           <h3>
                              <?php echo isset($totle_booking_cont['Flight']) ? $totle_booking_cont['Flight'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Flight Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") : ?>
         <?php if (permission_access("Hotel", "Hotel_Module")) : ?>
            <?php if (isset($totle_booking_cont['Hotel'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('hotel/bookings?source=dashboard') ?>" class="tile-green">
                        <div class="icon"><i class="fa-solid fa-hotel"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Hotel']) ? $totle_booking_cont['Hotel'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Hotel Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
         <?php if (permission_access("Holiday", "Holiday_Module")) : ?>
            <?php if (isset($totle_booking_cont['Holiday'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('holiday/booking-list?source=dashboard') ?>" class="tile-blue">
                        <div class="icon"><i class="fa-solid fa-route"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Holiday']) ? $totle_booking_cont['Holiday'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Holiday Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
         <?php if (permission_access("Bus", "Bus_Module")) : ?>
            <?php if (isset($totle_booking_cont['Bus'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('bus/bus-booking-list?source=dashboard') ?>" class="tile-aqua">
                        <div class="icon"><i class="fa-solid fa-bus"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Bus']) ? $totle_booking_cont['Bus'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Bus Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
         <?php if (permission_access("Visa", "Visa_Module")) : ?>
            <?php if (isset($totle_booking_cont['Visa'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('visa/booking-list?source=dashboard') ?>" class="tile-pink">
                        <div class="icon"><i class="fa-brands fa-cc-visa"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Visa']) ? $totle_booking_cont['Visa'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Visa Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>


      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
         <?php if (permission_access("Cruise", "Cruise_Module")) : ?>
            <?php if (isset($totle_booking_cont['Cruise'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('cruise?source=dashboard') ?>" class="tile-pink">
                        <div class="icon"><i class="fa-solid fa-ship"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Cruise']) ? $totle_booking_cont['Cruise'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Cruise Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
         <?php if (permission_access("CarExtranet", "CarExtranet_Module")) : ?>
            <?php if (isset($totle_booking_cont['Car'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('car/booking-list?source=dashboard') ?>" class="tile-black">
                        <div class="icon"><i class="fa-solid fa-car"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Car']) ? $totle_booking_cont['Car'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Car Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") : ?>
         <?php if (permission_access("TourGuide", "TourGuide_Module")) : ?>
            <?php if (isset($totle_booking_cont['TourGuide'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('tourguide/booking-list?source=dashboard') ?>" class="tile-black">
                        <div class="icon"><i class="fas fa-female"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['TourGuide']) ? $totle_booking_cont['TourGuide'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Tour Guide Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>

      <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") : ?>
         <?php if (permission_access("Activities", "Activities_Module")) : ?>
            <?php if (isset($totle_booking_cont['Activities'])) { ?>
               <div class="col-md-2">
                  <div class="card tile-stats ">
                     <a href="<?php echo site_url('activities/activities-booking-list?source=dashboard') ?>" class="tile-black">
                        <div class="icon"><i class="fa fa-area-chart" aria-hidden="true"></i></div>
                        <div class="card-body">
                           <h3 class="">
                              <?php echo isset($totle_booking_cont['Activities']) ? $totle_booking_cont['Activities'] : '0'; ?>
                           </h3>
                           <h5 class="card-title">Activities Bookings</h5>
                        </div>
                     </a>
                  </div>
               </div>
            <?php } ?>
         <?php endif; ?>
      <?php endif; ?>
   </div>

   <div class="row">
      <div class="col-md-6">
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Bookings</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered table-striped ">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>All Bookings </th>
                           <th>Pending </th>
                           <th>Cancelled </th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
                           <?php if (permission_access("Flight", "Flight_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Flight'])) { ?>
                                    <th>Flight</th>
                                    <td><a href="<?php echo site_url('flight/bookings?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Flight']) ? $totle_booking_cont['Flight'] : '0'; ?> </a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Flight'])) { ?>
                                    <td><a href="<?php echo site_url('flight/bookings?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr"><?php echo isset($commonData['Flight']['Processing']) ? $commonData['Flight']['Processing'] : '0'; ?> </a></td>
                                    <td><a href="<?php echo site_url('flight/bookings?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr"><?php echo isset($commonData['Flight']['Cancelled']) ? $commonData['Flight']['Cancelled'] : '0'; ?> </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") : ?>
                           <?php if (permission_access("Hotel", "Hotel_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Hotel'])) { ?>
                                    <th>Hotel</th>
                                    <td><a href="<?php echo site_url('hotel/bookings?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Hotel']) ? $totle_booking_cont['Hotel'] : '0'; ?> </a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Hotel'])) { ?>
                                    <td><a href="<?php echo site_url('hotel/bookings?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr"><?php echo isset($commonData['Hotel']['Processing']) ? $commonData['Hotel']['Processing'] : '0'; ?> </a></td>
                                    <td><a href="<?php echo site_url('hotel/bookings?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr"><?php echo isset($commonData['Hotel']['Cancelled']) ? $commonData['Hotel']['Cancelled'] : '0'; ?> </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
                           <?php if (permission_access("Holiday", "Holiday_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Holiday'])) { ?>
                                    <th>Holiday</th>
                                    <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Holiday']) ? $totle_booking_cont['Holiday'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Holiday'])) { ?>
                                    <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Holiday']['Processing']) ? $commonData['Holiday']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Holiday']['Cancelled']) ? $commonData['Holiday']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
                           <?php if (permission_access("Bus", "Bus_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Bus'])) { ?>
                                    <th>Bus</th>
                                    <td><a href="<?php echo site_url('bus/bus-booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Bus']) ? $totle_booking_cont['Bus'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Bus'])) { ?>
                                    <td><a href="<?php echo site_url('bus/bus-booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Bus']['Processing']) ? $commonData['Bus']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('bus/bus-booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Bus']['Cancelled']) ? $commonData['Bus']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
                           <?php if (permission_access("Visa", "Visa_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Visa'])) { ?>
                                    <th>Visa</th>
                                    <td><a href="<?php echo site_url('visa/booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Visa']) ? $totle_booking_cont['Visa'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Visa'])) { ?>
                                    <td><a href="<?php echo site_url('visa/booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Visa']['Processing']) ? $commonData['Visa']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('visa/booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Visa']['Cancelled']) ? $commonData['Visa']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
                           <?php if (permission_access("Cruise", "Cruise_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Cruise'])) { ?>
                                    <th>Cruise</th>
                                    <td><a href="<?php echo site_url('cruise?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Cruise']) ? $totle_booking_cont['Cruise'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Cruise'])) { ?>
                                    <td><a href="<?php echo site_url('cruise?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Cruise']['Processing']) ? $commonData['Cruise']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('cruise?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Cruise']['Cancelled']) ? $commonData['Cruise']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
                           <?php if (permission_access("CarExtranet", "CarExtranet_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Car'])) { ?>
                                    <th>Car</th>
                                    <td><a href="<?php echo site_url('car/booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Car']) ? $totle_booking_cont['Car'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Car'])) { ?>
                                    <td><a href="<?php echo site_url('car/booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Car']['Processing']) ? $commonData['Car']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($commonData['Car']['Cancelled']) ? $commonData['Car']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") : ?>
                           <?php if (permission_access("TourGuide", "TourGuide_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['TourGuide'])) { ?>
                                    <th>Tour Guide</th>
                                    <td><a href="<?php echo site_url('tourguide/booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['TourGuide']) ? $totle_booking_cont['TourGuide'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['TourGuide'])) { ?>
                                    <td><a href="<?php echo site_url('tourguide/booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['TourGuide']['Processing']) ? $commonData['TourGuide']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('tourguide/booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['TourGuide']['Cancelled']) ? $commonData['TourGuide']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") : ?>
                           <?php if (permission_access("Activities", "Activities_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_booking_cont['Activities'])) { ?>
                                    <th>Activities</th>
                                    <td><a href="<?php echo site_url('activities/activities-booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Activities']) ? $totle_booking_cont['Activities'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($commonData['Activities'])) { ?>
                                    <td><a href="<?php echo site_url('activities/activities-booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Activities']['Processing']) ? $commonData['Activities']['Processing'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('activities/activities-booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                          <?php echo isset($commonData['Activities']['Cancelled']) ? $commonData['Activities']['Cancelled'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <?php // pr($CommonDataAmendment['Flight']); 
         ?>
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Amendment</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive ">
                  <table class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>All Amendment </th>
                           <th>Requested </th>
                           <th>Approved </th>
                           <th>Rejected </th>
                           <th>Processing </th>
                        </tr>
                     </thead>
                     <tbody>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
                           <?php if (permission_access("Flight", "Flight_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Flight'])) { ?>
                                    <th>Flight</th>
                                    <td><a href="<?php echo site_url('flight/flight-amendments?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Flight']) ? $totle_amendment_cont['Flight'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Flight'])) { ?>
                                    <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['requested']) ? $CommonDataAmendment['Flight']['requested'] : '0'; ?> </a></td>
                                    <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr"> <?php echo isset($CommonDataAmendment['Flight']['approved']) ? $CommonDataAmendment['Flight']['approved'] : '0'; ?></a> </td>
                                    <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['rejected']) ? $CommonDataAmendment['Flight']['rejected'] : '0'; ?> </a> </td>
                                    <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['processing']) ? $CommonDataAmendment['Flight']['processing'] : '0'; ?> </a> </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") : ?>
                           <?php if (permission_access("Hotel", "Hotel_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Hotel'])) { ?>
                                    <th>Hotel</th>
                                    <td><a href="<?php echo site_url('hotel/hotel-amendments?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Hotel']) ? $totle_amendment_cont['Hotel'] : '0'; ?> </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Hotel'])) { ?>
                                    <td>
                                       <a href="<?php echo site_url('hotel/hotel-amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Hotel']['requested']) ? $CommonDataAmendment['Hotel']['requested'] : '0'; ?> </a>
                                    </td>
                                    <td>
                                       <a href="<?php echo site_url('hotel/hotel-amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Hotel']['approved']) ? $CommonDataAmendment['Hotel']['approved'] : '0'; ?> </a>
                                    </td>
                                    <td>
                                       <a href="<?php echo site_url('hotel/hotel-amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Hotel']['rejected']) ? $CommonDataAmendment['Hotel']['rejected'] : '0'; ?> </a>
                                    </td>
                                    <td>
                                       <a href="<?php echo site_url('hotel/hotel-amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Hotel']['processing']) ? $CommonDataAmendment['Hotel']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
                           <?php if (permission_access("Holiday", "Holiday_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Holiday'])) { ?>
                                    <th>Holidays</th>
                                    <td><a href="<?php echo site_url('holiday/amendments?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Holiday']) ? $totle_amendment_cont['Holiday'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Holiday'])) { ?>
                                    <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Holiday']['requested']) ? $CommonDataAmendment['Holiday']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Holiday']['approved']) ? $CommonDataAmendment['Holiday']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Holiday']['rejected']) ? $CommonDataAmendment['Holiday']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Holiday']['processing']) ? $CommonDataAmendment['Holiday']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
                           <?php if (permission_access("Bus", "Bus_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Bus'])) { ?>
                                    <th>Bus</th>
                                    <td><a href="<?php echo site_url('bus/amendments?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Bus']) ? $totle_amendment_cont['Bus'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Bus'])) { ?>
                                    <td><a href="<?php echo site_url('bus/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Bus']['requested']) ? $CommonDataAmendment['Bus']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('bus/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Bus']['approved']) ? $CommonDataAmendment['Bus']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('bus/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Bus']['rejected']) ? $CommonDataAmendment['Bus']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('bus/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Bus']['processing']) ? $CommonDataAmendment['Bus']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
                           <?php if (permission_access("Visa", "Visa_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Visa'])) { ?>
                                    <th>Visa</th>
                                    <td><a href="<?php echo site_url('visa/amendments?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Visa']) ? $totle_amendment_cont['Visa'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Visa'])) { ?>
                                    <td><a href="<?php echo site_url('visa/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Visa']['requested']) ? $CommonDataAmendment['Visa']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('visa/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Visa']['approved']) ? $CommonDataAmendment['Visa']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('visa/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Visa']['rejected']) ? $CommonDataAmendment['Visa']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('visa/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Visa']['processing']) ? $CommonDataAmendment['Visa']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
                           <?php if (permission_access("Cruise", "Cruise_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Cruise'])) { ?>
                                    <th>Cruise</th>
                                    <td><a href="<?php echo site_url('cruise/amendments?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Cruise']) ? $totle_amendment_cont['Cruise'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Cruise'])) { ?>
                                    <td><a href="<?php echo site_url('cruise/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Cruise']['requested']) ? $CommonDataAmendment['Cruise']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('cruise/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Cruise']['approved']) ? $CommonDataAmendment['Cruise']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('cruise/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Cruise']['rejected']) ? $CommonDataAmendment['Cruise']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('cruise/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Cruise']['processing']) ? $CommonDataAmendment['Cruise']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
                           <?php if (permission_access("CarExtranet", "CarExtranet_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Car'])) { ?>
                                    <th>Car</th>
                                    <td><a href="<?php echo site_url('car/amendments?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Car']) ? $totle_amendment_cont['Car'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Car'])) { ?>
                                    <td><a href="<?php echo site_url('car/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Car']['requested']) ? $CommonDataAmendment['Car']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('car/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Car']['approved']) ? $CommonDataAmendment['Car']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('car/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Car']['rejected']) ? $CommonDataAmendment['Car']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('car/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Car']['processing']) ? $CommonDataAmendment['Car']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") : ?>
                           <?php if (permission_access("TourGuide", "TourGuide_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['TourGuide'])) { ?>
                                    <th>Tour Guide</th>
                                    <td><a href="<?php echo site_url('tourguide/amendment-list?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['TourGuide']) ? $totle_amendment_cont['TourGuide'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['TourGuide'])) { ?>
                                    <td><a href="<?php echo site_url('tourguide/amendment-list?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['TourGuide']['requested']) ? $CommonDataAmendment['TourGuide']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('tourguide/amendment-list?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['TourGuide']['approved']) ? $CommonDataAmendment['TourGuide']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('tourguide/amendment-list?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['TourGuide']['rejected']) ? $CommonDataAmendment['TourGuide']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('tourguide/amendment-list?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['TourGuide']['processing']) ? $CommonDataAmendment['TourGuide']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") : ?>
                           <?php if (permission_access("Activities", "Activities_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Activities'])) { ?>
                                    <th>Activities</th>
                                    <td><a href="<?php echo site_url('activities/get-amendment-lists?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Activities']) ? $totle_amendment_cont['Activities'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDataAmendment['Activities'])) { ?>
                                    <td><a href="<?php echo site_url('activities/get-amendment-lists?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Activities']['requested']) ? $CommonDataAmendment['Activities']['requested'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('activities/get-amendment-lists?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Activities']['approved']) ? $CommonDataAmendment['Activities']['approved'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('activities/get-amendment-lists?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDataAmendment['Activities']['rejected']) ? $CommonDataAmendment['Activities']['rejected'] : '0'; ?>
                                       </a></td>
                                    <td>
                                       <a href="<?php echo site_url('activities/get-amendment-lists?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Activities']['processing']) ? $CommonDataAmendment['Activities']['processing'] : '0'; ?> </a>
                                    </td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>

      </div>

      <?php $whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];  ?>

      <div class="col-md-6">
         <?php if ($whitelabel_setting_data['b2b_business'] == "active") : ?>
            <div class="page-content-area card p-0 mb-3">
               <div class="bg_white">
                  <div class="card-header">
                     <div class="table_title">
                        <div class="topbardash d-flex align-items-center justify-content-between">
                           <h5>Pending Payment List</h5>
                           <span class="pull-right"><a href="<?php echo site_url('accounts/wl-payment-history') ?>" target="_self">View More</a></span>
                        </div>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Company</th>
                              <th>Ref No</th>
                              <th>Mode</th>
                              <th>Amount</th>
                              <th>Date</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           if (!empty($payment_history) && is_array($payment_history)) {
                              foreach ($payment_history as $key => $data) {
                                 if ($data['status'] == 'approved') {
                                    $class = 'active-status';
                                 } else if ($data['status'] == 'rejected') {
                                    $class = 'inactive-status';
                                 } else if ($data['status'] == 'pending') {
                                    $class = 'pending-status';
                                 }

                           ?>
                                 <tr>
                                    <td>
                                       <?php echo $data['company_name']; ?>
                                    </td>
                                    <td>
                                       <?php echo $data['bank_transaction_id']; ?>
                                    </td>
                                    <td>
                                       <?php echo $data['payment_mode']; ?>
                                       <?php if ($data['file_name']) { ?> <br />

                                          <a href="<?php echo root_url . "uploads/make_payment/" . $data['file_name']; ?>" target="<?php echo target; ?>"><?php echo 'View ' . $data['payment_mode']; ?></a>

                                       <?php } ?>
                                    </td>
                                    <td>
                                       <a href="javascript:void(0);" view-data-modal="true" data-controller='accounts' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/accounts/wl-payment-history-detail/') . dev_encode($data['id']); ?>"><i class="fa fa-inr" aria-hidden="true"></i>
                                          <?php echo custom_money_format($data['amount']); ?>
                                       </a>

                                    </td>
                                    <td>
                                       <?php
                                       if (isset($data['created'])) {
                                          echo date_created_format($data['created']);
                                       }
                                       ?>
                                    </td>
                                 </tr>
                           <?php }
                           } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>

            <div class="page-content-area card p-0 mb-3 ">
               <div class="bg_white">
                  <div class="card-header">
                     <div class="table_title">
                        <div class="topbardash d-flex align-items-center justify-content-between">
                           <h5>Agent Activation List</h5>
                           <span class="pull-right"><a href="<?php echo site_url('agent') ?>" target="_self">View
                                 More</a></span>
                        </div>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Company </th>
                              <th>Region</th>
                              <th>Status</th>
                              <th>Date</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php

                           if (!empty($agent_registered_list) && is_array($agent_registered_list)) {
                              foreach ($agent_registered_list as $data) {
                                 if ($data['status'] == 'active') {
                                    $status_class = 'active-status';
                                 } else {
                                    $status_class = 'inactive-status';
                                 }
                           ?>
                                 <tr>
                                    <td>
                                       <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='dashboard' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['id']); ?>"><?php echo ucfirst($data['company_name']); ?></a>

                                    </td>

                                    <td>
                                       <?php echo $data['state']; ?> |
                                       <?php echo $data['city']; ?> |
                                       <?php echo $data['country']; ?>

                                    </td>

                                    <td>
                                       <div class="<?php echo $status_class ?>">
                                          <?php echo ucfirst($data['status']); ?>
                                       </div>
                                    </td>
                                    <td>
                                       <?php
                                       if (isset($data['created'])) {
                                          echo date_created_format($data['created']);
                                       }
                                       ?>
                                    </td>
                                 </tr>
                           <?php }
                           } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         <?php endif; ?>
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Refunds</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>All Refunds </th>
                           <th>Opened Refund </th>
                           <th>Closed Refund </th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['flight_module']) && $whitelabel_setting_data['flight_module'] == "active") : ?>
                           <?php if (permission_access("Flight", "Flight_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Flight'])) { ?>
                                    <th>Flight</th>
                                    <td><a href="<?php echo site_url('flight/flight-refunds?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Flight']) ? $totle_amendment_cont['Flight'] : '0'; ?></a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Flight'])) { ?>
                                    <td><a href="<?php echo site_url('flight/flight-refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Flight']['Open']) ? $CommonDatarefund_status['Flight']['Open'] : '0'; ?></a></td>
                                    <td><a href="<?php echo site_url('flight/flight-refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Flight']['Close']) ? $CommonDatarefund_status['Flight']['Close'] : '0'; ?></a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['hotel_module']) && $whitelabel_setting_data['hotel_module'] == "active") : ?>
                           <?php if (permission_access("Hotel", "Hotel_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Hotel'])) { ?>
                                    <th>Hotel</th>
                                    <td><a href="<?php echo site_url('hotel/hotel-refunds?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Hotel']) ? $totle_amendment_cont['Hotel'] : '0'; ?> </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Hotel'])) { ?>
                                    <td><a href="<?php echo site_url('hotel/hotel-refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Hotel']['Open']) ? $CommonDatarefund_status['Hotel']['Open'] : '0'; ?></a></td>
                                    <td><a href="<?php echo site_url('hotel/hotel-refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Hotel']['Close']) ? $CommonDatarefund_status['Hotel']['Close'] : '0'; ?></a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['holiday_module']) && $whitelabel_setting_data['holiday_module'] == "active") : ?>
                           <?php if (permission_access("Holiday", "Holiday_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Holiday'])) { ?>
                                    <th>Holidays</th>
                                    <td><a href="<?php echo site_url('holiday/refunds?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Holiday']) ? $totle_amendment_cont['Holiday'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Holiday'])) { ?>
                                    <td><a href="<?php echo site_url('holiday/refunds?key=refund_status&key-text=Refund+Status&value=Open')?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Holiday']['Open']) ? $CommonDatarefund_status['Holiday']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('holiday/refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Holiday']['Close']) ? $CommonDatarefund_status['Holiday']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['bus_module']) && $whitelabel_setting_data['bus_module'] == "active") : ?>
                           <?php if (permission_access("Bus", "Bus_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Bus'])) { ?>
                                    <th>Bus</th>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Bus']) ? $totle_amendment_cont['Bus'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Bus'])) { ?>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Bus']['Open']) ? $CommonDatarefund_status['Bus']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Bus']['Close']) ? $CommonDatarefund_status['Bus']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['visa_module']) && $whitelabel_setting_data['visa_module'] == "active") : ?>
                           <?php if (permission_access("Visa", "Visa_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Visa'])) { ?>
                                    <th>Visa</th>
                                    <td><a href="<?php echo site_url('visa/refunds?source=dashboard') ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Visa']) ? $totle_amendment_cont['Visa'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Visa'])) { ?>
                                    <td><a href="<?php echo site_url('visa/refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Visa']['Open']) ? $CommonDatarefund_status['Visa']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('visa/refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Visa']['Close']) ? $CommonDatarefund_status['Visa']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['cruise_module']) && $whitelabel_setting_data['cruise_module'] == "active") : ?>
                           <?php if (permission_access("Cruise", "Cruise_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Cruise'])) { ?>
                                    <th>Cruise</th>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Cruise']) ? $totle_amendment_cont['Cruise'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Cruise'])) { ?>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Cruise']['Open']) ? $CommonDatarefund_status['Cruise']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Cruise']['Close']) ? $CommonDatarefund_status['Cruise']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?> 

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['car_module']) && $whitelabel_setting_data['car_module'] == "active") : ?>
                           <?php if (permission_access("CarExtranet", "CarExtranet_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Car'])) { ?>
                                    <th>Car</th>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Car']) ? $totle_amendment_cont['Car'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Car'])) { ?>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Car']['Open']) ? $CommonDatarefund_status['Car']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Car']['Close']) ? $CommonDatarefund_status['Car']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['tourguide_module']) && $whitelabel_setting_data['tourguide_module'] == "active") : ?>
                           <?php if (permission_access("TourGuide", "TourGuide_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['TourGuide'])) { ?>
                                    <th>Tour Guide</th>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['TourGuide']) ? $totle_amendment_cont['TourGuide'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['TourGuide'])) { ?>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['TourGuide']['Open']) ? $CommonDatarefund_status['TourGuide']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="javascript:void(0);" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['TourGuide']['Close']) ? $CommonDatarefund_status['TourGuide']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($whitelabel_user_status == "active" && isset($whitelabel_setting_data['activities_module']) && $whitelabel_setting_data['activities_module'] == "active") : ?>
                           <?php if (permission_access("Activities", "Activities_Module")) : ?>
                              <tr>
                                 <?php if (isset($totle_amendment_cont['Activities'])) { ?>
                                    <th>Activities</th>
                                    <td><a href="<?php echo site_url('activities/refunds?source=dashboard'); ?>" class="txt_led_clr">
                                          <?php echo isset($totle_amendment_cont['Activities']) ? $totle_amendment_cont['Activities'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                                 <?php if (isset($CommonDatarefund_status['Activities'])) { ?>
                                    <td><a href="<?php echo site_url('activities/refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Activities']['Open']) ? $CommonDatarefund_status['Activities']['Open'] : '0'; ?>
                                       </a></td>
                                    <td><a href="<?php echo site_url('activities/refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr">
                                          <?php echo isset($CommonDatarefund_status['Activities']['Close']) ? $CommonDatarefund_status['Activities']['Close'] : '0'; ?>
                                       </a></td>
                                 <?php } ?>
                              </tr>
                           <?php endif; ?>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>