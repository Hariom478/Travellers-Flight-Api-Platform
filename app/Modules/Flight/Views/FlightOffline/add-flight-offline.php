<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add <?php echo 'Flight Offline'; ?></h5>
</div>


<div class="vewmodelhed">

    <form action="<?php echo site_url('flightoffline/flight-offline'); ?>" method="post" tts-form="true"
          name="add_flight_offline">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Airline Code * </label>
                        <input class="form-control" type="text" tts-get-airline="true" value="ANY-Any Airline" name="airline_code"
                               placeholder="Airline Code">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>Business Type *</label>
                        <select class="form-control" name="business_type" placeholder="Business Type">
                            <option value="ANY" selected>ANY</option>
                            <option value="B2C">B2C</option>
                            <option value="B2B">B2B</option>
                        </select>
                    </div>
                </div>


                <div class="tts-col-6">
                    <div class="form-group">
                        <label>From Airport *</label>
                        <input class="form-control"  type="text" tts-get-single-airport="true" name="from_airport_code"
                               placeholder="From Airport">
                    </div>
                </div>

                <div class="tts-col-6">
                    <div class="form-group">
                        <label>To Airport * </label>
                        <input class="form-control" type="text" tts-get-single-airport="true" name="to_airport_code"
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
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive </option>
                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label for="hold">
                        <input class="" id="hold" type="radio" name="tts_is_hold" value="Hold" checked=""> Hold
                            </label>
                        <label for="pending">
                        <input class="" id="pending" type="radio" name="tts_is_hold"  value="Pending"> Pending
                            </label>
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
