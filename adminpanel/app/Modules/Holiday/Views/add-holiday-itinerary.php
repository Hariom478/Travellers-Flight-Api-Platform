<style>
   .ui-widget-content {
      border: unset;
      background: #fff;
      color: #333;
   }

   .ui-accordion-content-active {
      height: unset !important;
   }

   .ui-accordion-content {
      height: 228.188px;
      width: 100% !important;
      left: 0px !important;
      position: relative !important;
      margin: 0 !important;
      border: 1px solid #ddd !important;
      padding: 1em 0 !important;
   }
</style>
<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-body">
            <div class="table_title">
               <div class="sale_bar">
                  <div class="row align-items-center">
                     <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Package Itinerary</h5>
                     </div>
                     <?php if (permission_access("Holiday", "holiday_itinerary_add")) { ?>
                        <div class="col-md-8 text-end">
                           <button class="badge badge-wt"
                              onclick='add_more_items_holiday(event,"package-itinerary-html",15,"<?php echo site_url(); ?>")'>
                              <i class="fa-solid fa-add"></i> Add Itinerary
                           </button>
                        </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
            <?php if ($itinerary) { ?>
               <form action="<?php echo site_url('holiday/update-holiday-itinerary/') . dev_encode($holiday_list_id); ?>"
                  method="post" tts-form="true" name="add_add_holiday_itinerary" enctype="multipart/form-data">
                  <input type="hidden" name="counter" id="tts-holiday-counter" value="<?php echo count($itinerary) ?>">
                  <div style="display: contents;" id="package-itinerary-html">
                     <?php foreach ($itinerary as $key => $data) {
                        $flight_data = null;
                        $hotel_data = null;
                        $transfer_data = null;
                        if ($data['flight_data']) {
                           $flight_data = json_decode($data['flight_data'], true);
                        }
                        if ($data['hotel_data']) {
                           $hotel_data = json_decode($data['hotel_data'], true);
                        }
                        if ($data['transfer_data']) {
                           $transfer_data = json_decode($data['transfer_data'], true);
                        }

                        ?>
                        <div class="tts-itinerary-row">
                           <div id="tts-package-accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
                              <h3 class="text-bold count text_wrap ui-accordion-header ui-corner-top ui-state-default ui-accordion-header-active ui-state-active ui-accordion-icons"
                                 get-text="Day" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="true"
                                 aria-expanded="true" tabindex="0"><span
                                    class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>Day
                                 <?php echo $key + 1; ?>
                              </h3>
                              <div
                                 class="row ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active"
                                 id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">
                                 <div class="col-md-12">
                                    <div class="row">
                                       <div class="col-md-12 text-end mb-10">
                                          <button class="badge badge-wt" view-data-modal="false"
                                             data-counter="<?php echo $key + 1 ?>" data-controller='holiday'
                                             itinerary-service="flight"
                                             data-href="<?php echo site_url('holiday/add-itinerary-flight-template') ?>">
                                             <i class="fa-solid fa-add"></i> Flight
                                          </button>
                                          <button class="badge badge-wt" view-data-modal="false" data-controller='holiday'
                                             itinerary-service="hotel" data-counter="<?php echo $key + 1 ?>"
                                             data-href="<?php echo site_url('holiday/add-itinerary-hotel-template') ?>">
                                             <i class="fa-solid fa-add"></i> Hotel
                                          </button>
                                          <button class="badge badge-wt" view-data-modal="false" data-controller='holiday'
                                             itinerary-service="transfer" data-counter="<?php echo $key + 1 ?>"
                                             data-href="<?php echo site_url('holiday/add-itinerary-transfer-template') ?>">
                                             <i class="fa-solid fa-add"></i> Transfer
                                          </button>
                                          <button type="button" class="action badge badge-wt"
                                             onclick="remove_more_items_holidays(this,'package-itinerary-html', <?php echo $data['id'] ?>)">
                                             <i class="fa-solid fa-trash"></i>
                                          </button>
                                       </div>

                                    </div>
                                    <div class="row">
                                       <input type="hidden" name="itinerary[<?php echo $key + 1 ?>][primary_id]"
                                          value="<?php echo $data['id'] ?>">
                                       <div class="col-md-3">
                                          <div class="form-group form-mb-20">
                                             <label>City *</label>
                                             <!--  <input class="form-control" type="text"
                                          value="<?php echo $data['city'] ?>"
                                          name="itinerary[<?php echo $key + 1 ?>][city]"
                                          placeholder="Itinerary City"> -->
                                             <select class="form-control" name="itinerary[<?php echo $key + 1 ?>][city]"
                                                tts-select2-ajax="true" tts-method-nam="holiday/get-cities"
                                                placeholder="Itinerary City">
                                                <option value="<?php echo $data['city'] ?>">
                                                   <?php echo $data['city'] ?>
                                                </option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-3">
                                          <div class="form-group form-mb-20">
                                             <label>Day Title *</label>
                                             <input class="form-control" type="text"
                                                value="<?php echo $data['itinerary_title'] ?>"
                                                name="itinerary[<?php echo $key + 1 ?>][day_title]" placeholder="Day Title">
                                          </div>
                                       </div>
                                       <div class="col-md-3">
                                          <div class="form-group form-mb-20">
                                             <label>Meal *</label>
                                             <select class="form-control select_search"
                                                name="itinerary[<?php echo $key + 1 ?>][meal_type]" placeholder="Meal">
                                                <option value="Breakfast Lunch Dinner" <?php if ($data['day_meal_plan'] == "Breakfast Lunch Dinner") {
                                                   echo "selected";
                                                } ?>>Breakfast Lunch Dinner
                                                </option>
                                                <option value="Breakfast Lunch or Dinner" <?php if ($data['day_meal_plan'] == "Breakfast Lunch or Dinner") {
                                                   echo "selected";
                                                } ?>>Breakfast Lunch or Dinner
                                                </option>
                                                <option value="Breakfast Only" <?php if ($data['day_meal_plan'] == "Breakfast Only") {
                                                   echo "selected";
                                                } ?>>Breakfast Only
                                                </option>
                                                <option value="No Meal" <?php if ($data['day_meal_plan'] == "No Meal") {
                                                   echo "selected";
                                                } ?>>No Meal
                                                </option>
                                                <option value="Lunch Only" <?php if ($data['day_meal_plan'] == "Lunch Only") {
                                                   echo "selected";
                                                } ?>>Lunch Only
                                                </option>
                                                <option value="Dinner Only" <?php if ($data['day_meal_plan'] == "Dinner Only") {
                                                   echo "selected";
                                                } ?>>Dinner Only
                                                </option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-3">
                                          <div class="form-group form-mb-20">
                                             <label>Itinerary Image </label>
                                             <input class="form-control" type="file"
                                                name="itinerary[<?php echo $key + 1 ?>][image]" placeholder="Itinerary Image">
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <div class="form-group form-mb-20">
                                             <label>Description *</label>
                                             <textarea class="form-control " type="textarea"
                                                name="itinerary[<?php echo $key + 1 ?>][description]" rows="3"
                                                placeholder="Description"><?php echo $data['itinerary_description'] ?></textarea>
                                          </div>
                                       </div>
                                    </div>
                                    <div tts-append-html="true" class="tts-append-html-counter<?php echo $key + 1 ?>">
                                       <?php if ($flight_data) {
                                          foreach ($flight_data as $fkey => $flight):
                                             ?>
                                             <div class="row"
                                                id="package-itinerary-flight-html_<?php echo $key + 1 ?>_<?= $fkey ?>">
                                                <div class="col-md-12  mb_10">
                                                   <div class="sale_bar">
                                                      <div class="row align-items-center">
                                                         <div class="col-md-4">
                                                            <span>Edit Flight</span>
                                                         </div>
                                                         <div class="col-md-8 text-end">
                                                            <button class="badge badge-wt"
                                                               onclick="remove_more_items_services(this,'package-itinerary-flight-html_<?= ($key + 1) . '_' . ($fkey) ?>')">
                                                               <i class="tts-icon delete"></i>Delete
                                                            </button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Origin *</label>
                                                      <input class="form-control" tts-get-holiday-airport="true" type="text"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][origin]"
                                                         value="<?php echo $flight['origin']; ?>" placeholder="Origin">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Destination *</label>
                                                      <input class="form-control" type="text" tts-get-holiday-airport="true"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][destination]"
                                                         value="<?php echo $flight['destination']; ?>" placeholder="Destination">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Airline *</label>
                                                      <input class="form-control" tts-get-airline="true" type="text"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][airline]"
                                                         value="<?php echo $flight['airline']; ?>" placeholder="Airline code">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Departure Time *</label>
                                                      <input class="form-control" type="text"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][depart_time]"
                                                         value="<?php echo $flight['depart_time']; ?>" placeholder="Departure Time">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Arrival Time *</label>
                                                      <input class="form-control" type="text"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][arrival_time]"
                                                         value="<?php echo $flight['arrival_time']; ?>" placeholder="Arrival Time">
                                                   </div>
                                                </div>
                                                <div class="col-md-12">
                                                   <div class="form-group form-mb-20">
                                                      <label>Remark *</label>
                                                      <textarea class="form-control" type="textarea"
                                                         name="flight[<?php echo $key + 1 ?>][<?= $fkey ?>][remark]" rows="3"
                                                         placeholder="Remark"><?php echo $flight['remark']; ?></textarea>
                                                   </div>
                                                </div>
                                             </div>
                                          <?php endforeach; ?>
                                       <?php } ?>
                                       <?php if ($hotel_data) {
                                          foreach ($hotel_data as $hkey => $hotel):
                                             ?>
                                             <div class="row" id="package-itinerary-hotel-html_<?php echo $key + 1 ?>_<?= $hkey ?>">
                                                <div class="col-md-12  mb_10">
                                                   <div class="sale_bar">
                                                      <div class="row align-items-center">
                                                         <div class="col-md-4">
                                                            <span>Add Hotel</span>
                                                         </div>
                                                         <div class="col-md-8 text-end">
                                                            <button class="badge badge-wt"
                                                               onclick="remove_more_items_services(this,'package-itinerary-hotel-html_<?= ($key + 1) . '_' . ($hkey) ?>')">
                                                               <i class="fa-solid fa-trash"></i>Delete
                                                            </button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Hotel Name *</label>
                                                      <input class="form-control" type="text"
                                                         name="hotel[<?php echo $key + 1 ?>][<?= $hkey ?>][hotel_name]"
                                                         value="<?php echo $hotel['hotel_name']; ?>" placeholder="Hotel Name">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Hotel City *</label>
                                                      <input class="form-control" type="text"
                                                         name="hotel[<?php echo $key + 1 ?>][<?= $hkey ?>][hotel_city]"
                                                         value="<?php echo $hotel['hotel_city']; ?>" placeholder="Hotel City">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Hotel Address *</label>
                                                      <input class="form-control" type="text"
                                                         name="hotel[<?php echo $key + 1 ?>][<?= $hkey ?>][hotel_address]"
                                                         value="<?php echo $hotel['hotel_address']; ?>" placeholder="Hotel Address">
                                                   </div>
                                                </div>
                                                <div class="col-md-12">
                                                   <div class="form-group form-mb-20">
                                                      <label>Remark *</label>
                                                      <textarea class="form-control " type="textarea"
                                                         name="hotel[<?php echo $key + 1 ?>][<?= $hkey ?>][remark]" rows="3"
                                                         placeholder="Enter Remark"><?php echo $hotel['remark']; ?></textarea>
                                                   </div>
                                                </div>
                                             </div>
                                          <?php endforeach; ?>
                                       <?php } ?>
                                       <?php if ($transfer_data) {
                                          foreach ($transfer_data as $tkey => $transfer):
                                             ?>
                                             <div class="row"
                                                id="package-itinerary-transfer-html_<?php echo $key + 1 ?>_<?= $tkey ?>">
                                                <div class="col-md-12  mb_10">
                                                   <div class="sale_bar">
                                                      <div class="row align-items-center">
                                                         <div class="col-md-4">
                                                            <span>Add Transfer</span>
                                                         </div>
                                                         <div class="col-md-8 text-end">
                                                            <button class="badge badge-wt"
                                                               onclick="remove_more_items_services(this,'package-itinerary-transfer-html_<?= ($key + 1) . '_' . ($tkey) ?>')">
                                                               <i class="tts-icon delete"></i>Delete
                                                            </button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Transfer Type *</label>
                                                      <input class="form-control" type="text"
                                                         name="transfer[<?php echo $key + 1 ?>][<?= $tkey ?>][transfer_type]"
                                                         value="<?php echo $transfer['transfer_type']; ?>"
                                                         placeholder="Transfer Type">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Pickup Form/place *</label>
                                                      <input class="form-control" type="text"
                                                         name="transfer[<?php echo $key + 1 ?>][<?= $tkey ?>][pickup]"
                                                         value="<?php echo $transfer['pickup']; ?>" placeholder="Pickup Form/place">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Drop to/place *</label>
                                                      <input class="form-control" type="text"
                                                         name="transfer[<?php echo $key + 1 ?>][<?= $tkey ?>][drop]"
                                                         value="<?php echo $transfer['drop']; ?>" placeholder="Drop to/place">
                                                   </div>
                                                </div>
                                                <div class="col-md-3">
                                                   <div class="form-group form-mb-20">
                                                      <label>Pickup time *</label>
                                                      <input class="form-control" type="text"
                                                         name="transfer[<?php echo $key + 1 ?>][<?= $tkey ?>][pickuptime]"
                                                         value="<?php echo $transfer['pickuptime']; ?>" placeholder="Pickup time">
                                                   </div>
                                                </div>
                                                <div class="col-md-12">
                                                   <div class="form-group form-mb-20">
                                                      <label>Remark *</label>
                                                      <textarea class="form-control " type="textarea"
                                                         name="transfer[<?php echo $key + 1 ?>][<?= $tkey ?>][remark]" rows="3"
                                                         placeholder="Remark"><?php echo $transfer['remark']; ?></textarea>
                                                   </div>
                                                </div>
                                             </div>
                                          <?php endforeach; ?>
                                       <?php } ?>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>
                     <?php } ?>
                  </div>
                  <div class="row">
                     <div class="col-md-12 text-md-right mt-3">
                        <button class="badge badge-md badge-primary badge_search float-right" type="submit">Update</button>
                     </div>
                  </div>
               </form>
            <?php } else { ?>
               <form action="<?php echo site_url('holiday/add-holiday-itinerary/') . dev_encode($holiday_list_id); ?>"
                  method="post" tts-form="true" name="add_add_holiday_itinerary" enctype="multipart/form-data">
                  <input type="hidden" name="counter" id="tts-holiday-counter" value="1">
                  <div style="display: contents;" id="package-itinerary-html">
                     <div class="tts-itinerary-row">
                        <div id="tts-package-accordion">
                           <h3 class="text-bold count text-wrap" get-text="Day">Day 1</h3>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="row">
                                    <div class="col-md-12 text-end mb-10">
                                       <button class="badge badge-wt" view-data-modal="false" data-counter="1"
                                          data-controller='holiday' itinerary-service="flight"
                                          data-href="<?php echo site_url('holiday/add-itinerary-flight-template') ?>">
                                          <i class="fa-solid fa-add"></i> Flight
                                       </button>
                                       <button class="badge badge-wt" view-data-modal="false" data-controller='holiday'
                                          data-counter="1" itinerary-service="hotel"
                                          data-href="<?php echo site_url('holiday/add-itinerary-hotel-template') ?>">
                                          <i class="fa-solid fa-add"></i> Hotel
                                       </button>
                                       <button class="badge badge-wt" view-data-modal="false" data-controller='holiday'
                                          data-counter="1" itinerary-service="transfer"
                                          data-href="<?php echo site_url('holiday/add-itinerary-transfer-template') ?>">
                                          <i class="fa-solid fa-add"></i> Transfer
                                       </button>
                                       <span class="action fa-solid fa-trash"
                                          onclick="remove_more_items_holiday(this,'package-itinerary-html')"></span>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-3">
                                       <div class="form-group form-mb-20">
                                          <label>City *</label>
                                          <select class="form-control" name="itinerary[1][city]" tts-select2-ajax="true"
                                             tts-method-nam="holiday/get-cities" placeholder="Itinerary City">
                                             <option value="">Select City</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="form-group form-mb-20">
                                          <label>Day Title *</label>
                                          <input class="form-control" type="text" name="itinerary[1][day_title]"
                                             placeholder="Day Title">
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="form-group form-mb-20">
                                          <label>Meal *</label>
                                          <select class="form-control select_search" name="itinerary[1][meal_type]"
                                             placeholder="Meal">
                                             <option value="Breakfast Lunch Dinner">Breakfast Lunch
                                                Dinner
                                             </option>
                                             <option value="Breakfast Lunch or Dinner">Breakfast Lunch or
                                                Dinner
                                             </option>
                                             <option value="Breakfast Only">Breakfast Only</option>
                                             <option value="No Meal">No Meal</option>
                                             <option value="Lunch Only">Lunch Only</option>
                                             <option value="Dinner Only">Dinner Only</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-md-3">
                                       <div class="form-group form-mb-20">
                                          <label>Itinerary Image </label>
                                          <input class="form-control" type="file" name="itinerary[1][image]"
                                             placeholder="Itinerary Image">
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group form-mb-20">
                                          <label>Description *</label>
                                          <textarea class="form-control " type="textarea" name="itinerary[1][description]"
                                             rows="3" placeholder="Description"></textarea>
                                       </div>
                                    </div>
                                 </div>
                                 <div tts-append-html="true" class="tts-append-html-counter1">
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 text-end mt-3">
                        <button class="badge badge-md badge-primary badge_search float-right " type="submit">Save</button>
                     </div>
                  </div>
               </form>
            <?php } ?>
         </div>
      </div>
   </div>
</div>