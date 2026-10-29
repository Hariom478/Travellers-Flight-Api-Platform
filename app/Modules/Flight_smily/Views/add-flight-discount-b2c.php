<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add <?php echo 'Flight Discount B2C'; ?></h5>
</div>


<div class="vewmodelhed">

    <form action="<?php echo site_url('flight/add-discount-b2c'); ?>" method="post" tts-form="true"
          name="add_flight_discount">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Airline Code * </label>
                        <input class="form-control" type="text" tts-get-airline="true" value="ANY-Any Airline" name="airline_code"
                               placeholder="Airline Code">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Flight Type *</label>
                        <select class="form-control" name="is_domestic" placeholder="Flight Type">
                            <option value="1" selected>Domestic</option>
                            <option value="0">International</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Journey Type *</label>
                        <select class="form-control" name="journey_type" placeholder="Journey Type">
                            <option value="oneway" selected>Oneway</option>
                            <option value="round-trip">Round Trip</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>From Airport *</label>
                        <input class="form-control" type="text" tts-get-airport="true" name="from_airport_code"
                               placeholder="From Airport">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>To Airport * </label>
                        <input class="form-control" type="text" tts-get-airport="true"  name="to_airport_code"
                               placeholder="To Airport">
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
                        <input class="form-control" type="text" nolim-calendor="true" name="travel_date_from" placeholder="From Date">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>To Date </label>
                        <input class="form-control" type="text" nolim-calendor="true" name="travel_date_to" placeholder="To Date">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Pax Type *</label>
                        <select class="form-control" name="pax_type" placeholder="Pax Type">
                            <option value="ANY" selected>ANY</option>
                            <option value="ADT">ADULT</option>
                            <option value="CHD">CHILD</option>
                            <option value="INF">INFANT</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Cabin Class *</label>
                        <select class="form-control" name="cabin_class" placeholder="Cabin Class">
                            <option value="ANY" selected>ANY</option>
                            <option value="Economy">Economy</option>
                            <option value="PremiumEconomy">Premium Economy</option>
                            <option value="Business">Business</option>
                            <option value="First">First</option>
                        </select>
                    </div>
                </div>



                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Discount Type *</label>
                        <select class="form-control" name="discount_type" placeholder="Discount Type">
                            <option value="fixed" selected>Fixed</option>
                            <option value="percent">Percent</option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Amount *</label>
                        <input class="form-control" type="text" name="amount" placeholder="Amount">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
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
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>
