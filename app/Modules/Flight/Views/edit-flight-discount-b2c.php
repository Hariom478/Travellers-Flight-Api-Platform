<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Edit <?php echo 'Flight Discount B2C'; ?></h5>
</div>

<div class="vewmodelhed">

    <form action="<?php echo site_url('flight/edit-discount-b2c/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="add_flight_discount">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Airline Code * </label>
                        <input class="form-control" type="text" tts-get-airline="true" value="<?php echo $details['airline_code'].'-'.$details['airline_name']?>" name="airline_code"
                               placeholder="Airline Code">
                    </div>
                </div>



                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Flight Type *</label>
                        <select class="form-control" name="is_domestic" placeholder="Flight Type">
                            <option value="1" <?php if ($details['is_domestic'] == 1) {
                                echo "selected";
                            } ?>>Domestic</option>
                            <option value="0" <?php if ($details['is_domestic'] == 0) {
                                echo "selected";
                            } ?> >International</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Journey Type *</label>
                        <select class="form-control" name="journey_type" placeholder="Journey Type">
                            <option value="oneway" <?php if ($details['journey_type'] == "oneway") {
                                echo "selected";
                            } ?> >Oneway</option>
                            <option value="round-trip" <?php if ($details['journey_type'] == "round-trip") {
                                echo "selected";
                            } ?> >Round Trip</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>From Airport *</label>
                        <input class="form-control" type="text" tts-get-airport="true" name="from_airport_code"
                               value="<?php echo $details['from_airport_code']?>"   placeholder="From Airport">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>To Airport * </label>
                        <input class="form-control" type="text" tts-get-airport="true"  name="to_airport_code"
                               value="<?php echo $details['to_airport_code']?>"  placeholder="To Airport">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="" tts-from-any="true"  value="ANY" class="Lead"  >From Any Airport
                        </label>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead" >To Any Airport
                        </label>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>From Date </label>
                        <input class="form-control" type="text" nolim-calendor="true" value="<?php echo $details['travel_date_from']?>" name="travel_date_from" placeholder="From Date">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>To Date </label>
                        <input class="form-control" type="text" nolim-calendor="true" value="<?php echo $details['travel_date_to']?>" name="travel_date_to" placeholder="To Date">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Pax Type *</label>
                        <select class="form-control" name="pax_type" placeholder="Pax Type">
                            <option value="ANY" <?php if ($details['pax_type'] == "ANY") {
                                echo "selected";
                            } ?> >ANY</option>
                            <option value="ADT" <?php if ($details['pax_type'] == "ADT") {
                                echo "selected";
                            } ?> >ADULT</option>
                            <option value="CHD" <?php if ($details['pax_type'] == "CHD") {
                                echo "selected";
                            } ?> >CHILD</option>
                            <option value="INF" <?php if ($details['pax_type'] == "INF") {
                                echo "selected";
                            } ?> >INFANT</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Cabin Class *</label>
                        <select class="form-control" name="cabin_class" placeholder="Cabin Class">
                            <option value="ANY" <?php if ($details['cabin_class'] == "ANY") {
                                echo "selected";
                            } ?> >ANY</option>
                            <option value="Economy" <?php if ($details['cabin_class'] == "Economy") {
                                echo "selected";
                            } ?> >Economy</option>
                            <option value="PremiumEconomy" <?php if ($details['cabin_class'] == "PremiumEconomy") {
                                echo "selected";
                            } ?> >Premium Economy</option>
                            <option value="Business" <?php if ($details['cabin_class'] == "Business") {
                                echo "selected";
                            } ?> >Business</option>
                            <option value="First" <?php if ($details['cabin_class'] == "First") {
                                echo "selected";
                            } ?> >First</option>
                        </select>
                    </div>
                </div>


                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Discount Type *</label>
                        <select class="form-control" name="discount_type" placeholder="Discount Type">
                            <option value="fixed" <?php if ($details['discount_type'] == "fixed") {
                                echo "selected";
                            } ?>>Fixed</option>
                            <option value="percent" <?php if ($details['discount_type'] == "percent") {
                                echo "selected";
                            } ?> >Percent</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Amount *</label>
                        <input class="form-control" type="text" value="<?php echo $details['amount']?>" name="amount" placeholder="Amount">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
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
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>

