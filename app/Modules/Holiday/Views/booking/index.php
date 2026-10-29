<?php echo view('template/search.php'); ?>
<?php echo view('template/home-page-offers.php'); ?>
<?php if ($holiday_themes_list) { ?>
   <section class="holidays_theme sections-bg">
      <div class="container">
         <div class="home_heading">
            <h2>Theme Holidays</h2>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="holiday_slider">
                  <?php
                  foreach ($holiday_themes_list as $key => $theme) {
                  ?>
                     <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $theme['Image']; ?>
                     <?php $filenameIcon =  root_url . 'uploads/holiday/thumbnail/' . $theme['Icon']; ?>
                     <div class="holiday_column ">
                        <a href="<?php echo site_url('holiday/search?id=') . $theme['id'] . '&slug=' . $theme['Name'] . '&tag=' . 'Themes' ?>">
                           <div class="card-group">
                              <div class="card text-white">
                                 <img src="<?php echo $filename; ?>" class="img-fluid" alt="<?php echo $theme['Name'] ?>">
                                 <div class="card-img-overlay holidayimg_overlay text-center d-flex flex-column justify-content-center">
                                    <?php if ($theme['Icon']) { ?>
                                       <img src="<?php echo $filenameIcon; ?>" class="img-fluid mx-auto">
                                    <?php } ?>
                                    <h5 class=""><?php echo $theme['Name'] ?></h5>
                                    <h6>
                                       <span class=""><?php echo $theme['TotalPackages'] ?>
                                          Trips</span>
                                    </h6>
                                 </div>
                              </div>
                           </div>
                        </a>
                     </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </section>
<?php } ?>
<!-- <?php if ($holiday_list) { ?>
<section class="holidayTrendsWrapper">
   <div class="container">
      <div class="home_heading">
         <h2 class="">Trending Holidays</h2>
      </div>
      <div class="row">
         <?php foreach ($holiday_list as $super_key => $holiday) {
            $itinerary = $holiday['itinerary'] ?>
         <div class="col-lg-3 col-md-6 col-12 mb-3">
            <div class="holidayCardWrapper_box">
               <div class="holidayImageContentWrap ">
                  <div class="holidayImageContent">
                     <div class="holidayImage">
                        <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $holiday['Image']; ?>
                        <img src="<?php echo $filename; ?>" class="card-img-top img-fluid" alt="<?php echo $holiday['Name']; ?>">
                        <?php if (in_array('Flight', $holiday['Includes'])) { ?>
                        <span>With Airfare</span>
                        <?php } ?>
                     </div>
                     <div class="holidayContentbox">
                        <div class="holidayHoverContent">
                           <a class="card-title holidayCard-title"><?php echo $holiday['Name']; ?></a>
                           <p class="holidayDura mb-1"><?php echo count($itinerary) - 1; ?>
                              Nights - <?php echo count($itinerary); ?>
                              Days
                           </p>
                           <a class="holidayHoverBtn btn" href="<?php echo site_url('holiday/holiday-details?id') . $holiday['id'] . '&slug=' . $holiday['Slug'] ?>">Book
                           Now</a>
                           <span class="holidayHoverIcon d-block mb-2">
                           <?php if ($holiday['Includes']) {
                              foreach ($holiday['Includes'] as $include) {
                           ?><?php if ($include == 'Flight') { ?>
                           <i class="fa fa-fighter-jet holiday_list_icn" aria-hidden="true"></i>
                           <?php } ?><?php if ($include == 'Hotel') { ?>
                           <i class="fa fa-bed holiday_list_icn" aria-hidden="true"></i>
                           <?php } ?><?php if ($include == 'Transfer') { ?>
                           <i class="fa fa-car holiday_list_icn" aria-hidden="true"></i>
                           <?php } ?>
                           <?php if ($include == 'Meal') { ?>
                           <i class="fa fa-cutlery holiday_list_icn" aria-hidden="true"></i>
                           <?php } ?><?php }
                                 } ?>
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-body">
                  <h5 class="holidaytitle_hight">
                     <a class="card-title holidayCard-title"><?php echo $holiday['Name']; ?></a>
                  </h5>
                  <div class="holidayContentWrap">
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-12 text-left">
                           <p class="holidayDura"><?php echo count($itinerary) - 1; ?>
                              Nights - <?php echo count($itinerary); ?>
                              Days
                           </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                           <span class="holidayIcon">
                           <?php if ($holiday['Includes']) {
                              foreach ($holiday['Includes'] as $include) {
                           ?><?php if ($include == 'Flight') { ?>
                           <i class="fa fa-fighter-jet" aria-hidden="true"></i>
                           <?php } ?><?php if ($include == 'Hotel') { ?>
                           <i class="fa fa-bed" aria-hidden="true"></i>
                           <?php } ?><?php if ($include == 'Hotel') { ?>
                           <i class="fa fa-car" aria-hidden="true"></i>
                           <?php } ?>
                           <?php if ($include == 'Meal') { ?>
                           <i class="fa fa-cutlery" aria-hidden="true"></i>
                           <?php } ?><?php }
                                 } ?>
                           </span>
                        </div>
                     </div>
                     <div class="holidayLocWrap holidaylist_hight">
                        <div class="holidayLocWrap  
                           <?php echo "tts-holiday-" . $super_key ?>">
                           <?php if ($itinerary) {
                              foreach ($itinerary as $key => $item) { ?>
                           <?php if ($key <= 1) { ?>
                           <span class="holidayDest d-block ">
                           <i class="fa fa-map-marker me-1"></i>
                           <?php echo $item['city'] ?>
                           </span>
                           <?php } ?>
                           <?php }
                           } ?>
                        </div>
                        <div class="holidayLocWrap d-none" id="tts-holiday-<?php echo $super_key ?>">
                           <?php if ($itinerary) {
                              foreach ($itinerary as $key => $item) { ?>
                           <span class="holidayDest d-block ">
                           <i class="fa fa-map-marker me-1"></i>
                           <?php echo $item['city'] ?>
                           </span>
                           <?php }
                           } ?>
                        </div>
                        <?php if (count($itinerary) > 2) { ?>
                        <div class="d-flex align-items-center justify-content-end">
                           <div class="holidayDest holidayDest1"></div>
                           <div class="holidayDest">
                              <a class="" href="javascript:void(0);" tts-holiday-<?php echo $super_key ?> onclick='showHide("tts-holiday-<?php echo $super_key ?>")'>View
                              More</a>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                     <hr>
                     <div class="holidayPrice">
                        <div class="priceAmount">
                           <?php if ($holiday['PriceOnCall'] == 1) { ?>
                           <i class="fa fa-rupee-sign me-1"></i><?php echo $holiday['ShowPrice']; ?>
                           <span>Per Pax</span>
                           <?php } else { ?>
                           <span>Price on request</span>
                           <?php } ?>
                        </div>
                        <div class="priceIcon d-flex align-items-center justify-content-between">
                           <div></div>
                           <a href="<?php echo site_url('holiday/holiday-details?id') . $holiday['id'] . '&slug=' . $holiday['Slug'] ?>" class="btn priceBtn">Details</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
</section>
<?php } ?> -->
<?php if ($holiday_list) { ?>
   <section class="holidayTrendsWrapper">
      <div class="container">
         <div class="home_heading">
            <h2>Trending Holidays</h2>
         </div>
         <div class="row">
            <?php foreach ($holiday_list as $super_key => $holiday) {
               $itinerary = $holiday['itinerary'] ?>
               <div class="col-lg-3 col-md-6 col-12 mb-3">
                  <div class="holidayCardWrapper_box">
                     <div class="holidayCard-body">
                        <h5 class="card-title">
                           <a data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo ucwords($holiday['Name']); ?>"><?php echo ucwords($holiday['Name']); ?></a>
                        </h5>
                        <div class="makeRelative specialImage">
                           <a href="#">
                              <div class="img-wrapper">
                                 <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $holiday['Image']; ?>
                                 <img src="<?php echo $filename; ?>" class="card-img-top img-fluid" alt="<?php echo $holiday['Name']; ?>">
                                 <?php if (in_array('Flight', $holiday['Includes'])) { ?>
                                    <span>With Airfare</span>
                                 <?php } ?>
                              </div>
                              <span class="daysbadge" data-testid="days-badge"><?php echo count($itinerary) - 1; ?>
                                 Nights / <?php echo count($itinerary); ?>
                                 Days</span>
                           </a>
                        </div>
                        <div class="smileychoice">
                           <div class="recommended under-checkbox">Recommended <span></span></div>
                        </div>
                     </div>
                     <div class="holidayCarditem">
                        <ul class="holidayItineraryWrapper makeFlex spaceBetween itineraryList ">
                           <?php if ($holiday['Includes']) {
                              foreach ($holiday['Includes'] as $include) { ?>
                                 <?php if ($include == 'Flight') { ?>
                                    <li class="includesWrapper ">
                                       <span class="fa fa-fighter-jet"></span>
                                       <p>Flight</p>
                                    </li>
                                 <?php } ?>
                                 <?php if ($include == 'Hotel') { ?>
                                    <li class="includesWrapper ">
                                       <span class="fa fa-bed"></span>
                                       <p>Hotel</p>
                                    </li>
                                 <?php } ?>
                                 <?php if ($include == 'Hotel') { ?>
                                    <li class="includesWrapper ">
                                       <span class="fa fa-car"></span>
                                       <p>Car</p>
                                    </li>
                                 <?php } ?>
                                 <?php if ($include == 'Meal') { ?>
                                    <li class="includesWrapper ">
                                       <span class="fa fa-cutlery"></span>
                                       <p>Meal</p>
                                    </li>
                                 <?php } ?>
                           <?php }
                           } ?>
                        </ul>
                        <div class="destinationWrapper <?php echo "tts-holiday-" . $super_key ?>">
                           <?php if ($itinerary) :
                              $cities = array_column($itinerary, 'city');
                              $cityInc = holiday_city_iternary_stay($cities);
                           ?>
                              <?php foreach ($cityInc as $key => $item) : ?>
                                 <div class="d-flex align-items-center">
                                    <span class="redText"><?php echo isset($item['value']) ? $item['value'] : 1; ?>D</span>
                                    <div class="destinationName"><span><?php echo $item['name'] ?></span><span>|</span>
                                    </div>
                                 </div>
                              <?php endforeach ?>
                           <?php endif ?>
                        </div>
                     </div>
                     <div class="footer-new slashed-price">
                        <h3 class="priceAmount">
                           <?php if ($holiday['PriceOnCall'] == 1) { ?>
                              Starting Price
                              <span class="slashed"> ₹ <?php echo $holiday['ShowPrice']; ?></span>
                              <span>Per Person</span>
                           <?php } else { ?>
                              <span>Price on request</span>
                           <?php } ?>
                        </h3>
                        <div class="priceIcon">
                           <div></div>
                           <a href="<?php echo site_url('holiday/holiday-details?id') . $holiday['id'] . '&slug=' . $holiday['Slug'] ?>" class="btn priceBtn">Details</a>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
         </div>
      </div>
   </section>
<?php } ?>