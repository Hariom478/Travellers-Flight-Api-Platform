
<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Flight Discount'; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>
<div class="vewmodelhed">
   <form action="<?php echo site_url('markup-discount/add-discount'); ?>" method="post" tts-form="true"
      name="add_flight_discount">
      <div class="modal-body">
         <div class="row align-items-center">
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Web Partner Class *</label>
                  <select class="form-control select_search" name="web_partner_class_id[]" multiple="multiple"
                     placeholder="Web Partner Class">
                     <?php if ($web_partner_class) {
                        foreach ($web_partner_class as $class) { ?>
                     <option value="<?php echo $class['id'] ?>"><?php echo ucfirst($class['class_name']) ?></option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Supplier *</label>
                  <select class="form-control select_search" name="supplier[]" multiple="multiple">
                     <?php if ($ApiSupplierModel) {
                        foreach ($ApiSupplierModel as $data) { ?>
                     <option value="<?php echo $data['supplier_name'] ?>">
                        <?php echo ucfirst($data['supplier_name']); ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Airline Code * </label>
                  <input class="form-control" type="text" tts-get-airline="true" value="ANY-Any Airline" name="airline_code"
                     placeholder="Airline Code">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>From Airport *</label>
                  <input class="form-control" type="text" tts-get-airport="true" name="from_airport_code"
                     placeholder="From Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                  <input type="checkbox" name="" tts-from-any="true"  value="ANY" class="Lead"  >From Any Airport
                  </label>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group form-mb-20">
                  <label>To Airport * </label>
                  <input class="form-control" type="text" tts-get-airport="true"  name="to_airport_code"
                     placeholder="To Airport">
               </div>
            </div>
            <div class="col-md-6 align-self-end">
               <div class="form-group form-mb-20">
                  <label>
                  <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead" >To Any Airport
                  </label>
               </div>
            </div>
            <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Booking Class * </label>
                        <input class="form-control" type="text"   name="booking_class"
                               placeholder="Like A,B">
                    </div>
                </div>

                <div class="col-md-6 align-self-end">
                    <div class="form-group form-mb-20">
                        <label>
                            <input type="checkbox" name="" tts-booking-any="true" value="ANY" class="Lead" >Any Booking Class
                        </label>
                    </div>
                </div>
            <div class="col-md-12">
               <div class="form-group form-mb-20">
                  <label>Fare Type *</label>
                  <select class="form-control select_search" name="faretype[]" multiple="multiple">
                     <?php if (ApiFlighFareType) {
                        foreach (ApiFlighFareType as $data) { ?>
                     <option value="<?php echo $data ?>">
                        <?php echo ucfirst($data); ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Flight Type *</label>
                  <select class="form-control select_search" name="is_domestic[]" placeholder="Flight Type" multiple="multiple">
                     <option value="1" selected>Domestic</option>
                     <option value="0">International</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Journey Type *</label>
                  <select class="form-control select_search" name="journey_type[]" placeholder="Journey Type" multiple="multiple">
                     <option value="oneway" selected>Oneway</option>
                     <option value="round-trip">Round Trip</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>From Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_from" placeholder="From Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>To Date *</label>
                  <input class="form-control" type="text" nolim-calendor="true" name="travel_date_to" placeholder="To Date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Cabin Class *</label>
                  <select class="form-control select_search" name="cabin_class[]" placeholder="Cabin Class" multiple="multiple">
                     <option value="Economy">Economy</option>
                     <option value="PremiumEconomy">Premium Economy</option>
                     <option value="Business">Business</option>
                     <option value="First">First</option>
                     <option value="PremiumBusiness">Premium Business</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Discount Type *</label>
                  <select class="form-control" name="discount_type" placeholder="Discount Type">
                     <option value="fixed" selected>Fixed</option>
                     <option value="percent">Percent</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Value *</label>
                  <input class="form-control" type="text" name="value" placeholder="Value">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Extra Discount </label>
                  <input class="form-control" type="text" name="extra_discount" placeholder="Extra Discount">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Max Limit </label>
                  <input class="form-control" type="text" name="max_limit" placeholder="Max Limit">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group form-mb-20">
                  <label>Status *</label>
                  <select class="form-control" name="status" placeholder="Status">
                     <option value="active" selected>Active</option>
                     <option value="inactive"> Inactive</option>
                  </select>
               </div>
            </div>
         </div>
      </div>
      <div class="modal-footer">
         <button class="btn btn-primary" type="submit" >Save</button>
      </div>
   </form>
</div>