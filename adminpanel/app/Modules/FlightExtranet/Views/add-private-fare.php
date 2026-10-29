<?php
$country_codes = get_countary_code();
$timePicker = time_picker();
?>

<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Add Inventory</h5>
               </div>
               <div class="col-md-8 text-md-end">

               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <form name="web-partner" tts-form='true'
                     action="<?php echo site_url('private-fare/add-private-fare'); ?>" method="POST" id="web-partner"
                     class="col-md-12">
                     <div class="row view_head">
                        <div class="col-md-12">
                           <span>Basic Information</span>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Inventory Name</label>
                              <input class="form-control" type="text" name="inventory_name"
                                 placeholder="Inventory Name">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Disable Before departure (hr)</label>
                              <input class="form-control" type="text" name="disable_before_departure"
                                 placeholder="Disable Before departure (hr)">
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Trip Type *</label>
                              <select class="form-control trip_type" name="trip_type" placeholder="Trip Type"
                                 privatefare-trip-type-select="true">
                                 <option value="domestic" selected>Domestic</option>
                                 <option value="international">International</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group form-mb-20">
                              <label>Journey Type *</label>
                              <select class="form-control journey_type" name="journey_type" placeholder="Journey Type"
                                 privatefare-journey-type-select="true">
                                 <option value="oneway" selected>Oneway</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <input type="hidden" name="onward_stops" id="tts-segment-counter" value="0">
                     <input type="hidden" name="return_stops" id="tts-segment-return-counter" value="0">
                     <div class="row align-items-center mb-3">
                        <div class="col-md-9">
                           <h5 class="dash-borderRadius main-heading-content">
                              Trip 1
                           </h5>
                        </div>
                        <div class="col-md-3 text-md-right">
                           <button type="button" class="badge badge-wt"
                              onclick='add_more_items_segment(event,"segment-itinerary-html",15,"<?php echo site_url(); ?>","0")'>
                              <i class="fa-solid fa-add"></i> Add More Segment
                           </button>
                        </div>
                     </div>
                     <div id="segment-itinerary-html">
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
                                       name="onward[0][<?php echo 0; ?>][origin_airport_code]"
                                       placeholder="Origin Airport Code">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Origin Terminal </label>
                                    <input class="form-control" type="text"
                                       name="onward[0][<?php echo 0; ?>][origin_terminal]"
                                       placeholder="Origin Terminal">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Destination Airport Code *</label>
                                    <input class="form-control" type="text" tts-get-single-airport="true"
                                       name="onward[0][<?php echo 0; ?>][destination_airport_code]"
                                       placeholder="Destination Airport Code">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Destination Terminal </label>
                                    <input class="form-control" type="text"
                                       name="onward[0][<?php echo 0; ?>][destination_terminal]"
                                       placeholder="Origin Terminal">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Airline Code *</label>
                                    <input class="form-control" type="text" tts-get-airline="true"
                                       name="onward[0][<?php echo 0; ?>][airline_code]" placeholder="Airline Code">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Flight Number *</label>
                                    <input class="form-control" type="text"
                                       name="onward[0][<?php echo 0; ?>][flight_number]" placeholder="Flight Number">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Aircraft</label>
                                    <input class="form-control" type="text" name="onward[0][<?php echo 0; ?>][Craft]"
                                       placeholder="Aircraft">
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group form-mb-20">
                                    <label>Departure Time *</label>
                                    <select class="form-control" name="onward[0][<?php echo 0; ?>][departure_time]">
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
                                    <select class="form-control" name="onward[0][<?php echo 0; ?>][arrival_time]">
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
                                    <select class="form-control" name="onward[0][<?php echo 0; ?>][is_next_day_arrival]"
                                       placeholder="Is Arrival Next Day?">
                                       <option value="No" selected>No</option>
                                       <option value="Yes">Yes</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div tts-call-put-html="true"></div>
                     <div class="form__buttons text-md-right">
                        <button type="submit" class="btn btn-primary ">Save & Continue</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>