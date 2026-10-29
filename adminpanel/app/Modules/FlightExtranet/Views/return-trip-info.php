<?php $country_codes = get_countary_code();
   $timePicker = time_picker();
   
   ?>
<div class="row align-items-center mb-3">
                           <div class="col-md-9">
                              <h5 class="dash-borderRadius main-heading-content">
                                 Trip 2
                              </h5>
                           </div>
                           <div class="col-md-3 text-md-right">
                           <button type="button" class="badge badge-wt"onclick='add_more_items_segment(event,"return-segment-itinerary-html",15,"<?php echo site_url(); ?>","1")'>
                           <i class="fa-solid fa-add"></i> Add More Segment
                        </button>
                        </div>
                     </div>
                     <div id="return-segment-itinerary-html">
                  <div class="tts-itinerary-row">
                     <div class="row align-items-center mb-3">
                       
                           <div class="col-md-12">
                              <h5 class="dash-borderRadius main-heading-content">
                                 Segment Information
                              </h5>
                           </div>
                     </div>
                     <div class="row align-items-center">  
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Origin Airport Code *</label>
                              <input class="form-control" type="text" tts-get-single-airport="true"
                                 name="onward[1][<?php echo 0; ?>][origin_airport_code]"
                                 placeholder="Origin Airport Code">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Origin Terminal </label>
                              <input class="form-control" type="text"
                                 name="onward[1][<?php echo 0; ?>][origin_terminal]"
                                 placeholder="Origin Terminal">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Destination Airport Code *</label>
                              <input class="form-control" type="text" tts-get-single-airport="true"
                                 name="onward[1][<?php echo 0; ?>][destination_airport_code]"
                                 placeholder="Destination Airport Code">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Destination Terminal </label>
                              <input class="form-control" type="text"
                                 name="onward[1][<?php echo 0; ?>][destination_terminal]"
                                 placeholder="Origin Terminal">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Airline Code *</label>
                              <input class="form-control" type="text" tts-get-airline="true"
                                 name="onward[1][<?php echo 0; ?>][airline_code]"
                                 placeholder="Airline Code">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Flight Number * </label>
                              <input class="form-control" type="text"
                                 name="onward[1][<?php echo 0; ?>][flight_number]"
                                 placeholder="Flight Number">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Departure Time *</label>
                              <select class="form-control" name="onward[1][<?php echo 0; ?>][departure_time]">
                                 <option value="" selected>Departure Time</option>
                                 <?php foreach ($timePicker as $key => $time) { ?>
                                 <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Arrival Time *</label>
                              <select class="form-control" name="onward[1][<?php echo 0; ?>][arrival_time]">
                                 <option value="" selected>Arrival Time</option>
                                 <?php foreach ($timePicker as $key => $time) { ?>
                                 <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Is Arrival Next Day?</label>
                              <select class="form-control"
                                 name="onward[1][<?php echo 0; ?>][is_next_day_arrival]"
                                 placeholder="Is Arrival Next Day?">
                                 <option value="No" selected>No</option>
                                 <option value="Yes">Yes</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>