
    <div class="modal-header">
        <h5 class="modal-title">Edit <?php echo 'Flight Markup '; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

    <form action="<?php echo site_url('markup-discount/edit-markup/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="add_flight_markup">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Web Partner Class *</label>
                        <select class="form-control select_search" name="web_partner_class_id[]" multiple="multiple"
                                placeholder="Web Partner Class">

                            <?php if ($web_partner_class) {
                                foreach ($web_partner_class as $class) { ?>
                                    <option value="<?php echo $class['id'] ?>" <?php if (in_array($class['id'], $details['web_partner_class_id'])) {echo "selected";} ?>   ><?php echo ucfirst($class['class_name']) ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Supplier *</label>
                        <select class="form-control select_search" name="supplier[]"  multiple="multiple">
                            <?php if ($ApiSupplierModel){
                                foreach ($ApiSupplierModel as $list){ ?>
                                    <option value="<?php echo $list['supplier_name']?>" <?php if (in_array($list['supplier_name'], $details['supplier'])) {
                                        echo "selected";
                                    } ?> ><?php echo $list['supplier_name']?></option>
                                <?php }
                            }?>

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Airline Code * </label>
                        <input class="form-control" type="text" tts-get-airline="true" value="<?php echo $details['airline_code'].'-'.$details['airline_name']?>" name="airline_code"
                               placeholder="Airline Code">
                    </div>
                </div>




                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>From Airport *</label>
                        <input class="form-control" type="text" tts-get-airport="true" name="from_airport_code"
                               value="<?php echo $details['from_airport_code']?>"   placeholder="From Airport">
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
                               value="<?php echo $details['to_airport_code']?>"  placeholder="To Airport">
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
                               placeholder="Like A,B" value="<?php echo $details['booking_class']?>">
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
                     <option value="<?php echo $data ?>" <?php if (in_array($data, $details['faretype'])) {  echo "selected"; }?>>
                        <?php echo ucfirst($data); ?>
                     </option>
                     <?php }
                        } ?>
                  </select>
               </div>
            </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>From Date *</label>
                        <input class="form-control" type="text" nolim-calendor="true" value="<?php echo $details['travel_date_from']?>" name="travel_date_from" placeholder="From Date">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>To Date *</label>
                        <input class="form-control" type="text" nolim-calendor="true" value="<?php echo $details['travel_date_to']?>" name="travel_date_to" placeholder="To Date">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Flight Type *</label>
                        <select class="form-control select_search" name="is_domestic[]"  multiple="multiple">
                            <option value="1" <?php if (in_array("1", $details['is_domestic'])) {echo "selected";} ?> >Domestic</option>
                            <option value="0" <?php if (in_array("0", $details['is_domestic'])) {echo "selected";} ?>>International</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Journey Type *</label>

                        <select class="form-control select_search" name="journey_type[]"  multiple="multiple">

                            <option value="oneway" <?php if (in_array("oneway", $details['journey_type'])) {echo "selected";} ?> >Oneway</option>
                            <option value="round-trip" <?php if (in_array("round-trip", $details['journey_type'])) {echo "selected";} ?>>Round Trip</option>

                        </select>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Cabin Class *</label>
                        <select class="form-control select_search" name="cabin_class[]" placeholder="Cabin Class" multiple="multiple">

                            <option value="Economy" <?php if (in_array("Economy", $details['cabin_class'])) {echo "selected";} ?> >Economy</option>
                            <option value="PremiumEconomy" <?php if (in_array("PremiumEconomy", $details['cabin_class'])) {echo "selected";} ?>>Premium Economy</option>
                            <option value="Business" <?php if (in_array("Business", $details['cabin_class'])) {echo "selected";} ?> >Business</option>
                            <option value="First" <?php if (in_array("First", $details['cabin_class'])) {echo "selected";} ?>>First</option>
                            <option value="PremiumBusiness" <?php if (in_array("PremiumBusiness", $details['cabin_class'])) {echo "selected";} ?> >Premium Business</option>
                        </select>
                    </div>
                </div>





                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Markup Type *</label>
                        <select class="form-control" name="markup_type" placeholder="Markup Type">
                            <option value="fixed" <?php if ($details['markup_type'] == "fixed") {
                                echo "selected";
                            } ?>>Fixed</option>
                            <option value="percent" <?php if ($details['markup_type'] == "percent") {
                                echo "selected";
                            } ?> >Percent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Display Markup *</label>
                        <select class="form-control" name="display_markup" placeholder="Display Markup">

                            <option value="in_tax" <?php if ($details['display_markup'] == "in_tax") {
                                echo "selected";
                            } ?>>In Tax</option>
                            <option value="in_service_charge" <?php if ($details['display_markup'] == "in_service_charge") {
                                echo "selected";
                            } ?> >In Service Charge</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Value *</label>
                        <input class="form-control" type="text" value="<?php echo $details['value']?>" name="value" placeholder="Value">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Max Limit </label>
                        <input class="form-control" type="text" name="max_limit"  value="<?php echo $details['max_limit']?>" placeholder="Max Limit">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" <?php if ($details['status'] == "active") {
                                echo "selected";
                            } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($details['status'] == "inactive") {
                                echo "selected";
                            } ?>> Inactive
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>


