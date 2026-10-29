<div class="modal-header">
    <h5>Edit <?php echo 'Flight Markup '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<form action="<?php echo site_url('markup-discount/edit-markup/' . dev_encode($id)); ?>" method="post" tts-form="true" name="add_flight_markup">


    <div class="modal-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Airline Code * </label>
                    <input class="form-control" type="text" tts-get-airline="true" value="<?php echo $details['airline_code'] . '-' . $details['airline_name'] ?>" name="airline_code" placeholder="Airline Code">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Fare Type *</label>
                    <select class="form-select select_search" name="faretype[]" multiple="multiple">
                        <?php if ($ApiFlighFareType) {
                            $fareType = explode(',', $details['faretype']);
                            foreach ($ApiFlighFareType as $data) { ?>
                                <option value="<?php echo $data ?>" <?= (in_array($data, $fareType)) ? 'selected' : '' ?>>
                                    <?php echo ucfirst($data); ?>
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>From Airport *</label>
                    <input class="form-control" type="text" tts-get-airport="true" value="<?= (isset($details['from_airport_code'])) ? $details['from_airport_code'] : '' ?>" name="from_airport_code" placeholder="From Airport">
                </div>
            </div>
            <div class="col-md-6 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" name="" tts-from-any="true" value="ANY" class="Lead" <?= (isset($details['from_airport_code']) && $details['from_airport_code'] == "ANY") ? 'checked' : '' ?>>From Any Airport
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>To Airport * </label>
                    <input class="form-control" type="text" tts-get-airport="true" value="<?= (isset($details['to_airport_code'])) ? $details['to_airport_code'] : '' ?>" name="to_airport_code" placeholder="To Airport">
                </div>
            </div>



            <div class="col-md-6 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" name="" tts-to-any="true" value="ANY" class="Lead" <?= (isset($details['to_airport_code']) && $details['to_airport_code'] == "ANY") ? 'checked' : '' ?>>To Any Airport
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Booking Class * </label>
                    <input class="form-control" type="text" name="booking_class" value="<?= (isset($details['booking_class'])) ? $details['booking_class'] : '' ?>" placeholder="Like A,B">
                </div>
            </div>



            <div class="col-md-6 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" name="" tts-booking-any="true" value="ANY" class="Lead" <?= (isset($details['booking_class']) && $details['booking_class'] == "ANY") ? 'checked' : '' ?>>Any Booking Class
                    </label>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>From Date *</label>
                    <input class="form-control" type="text" nolim-calendor="true" name="travel_date_from" value="<?= (isset($details['travel_date_from'])) ? date('d M Y', $details['travel_date_from']) : '' ?>" placeholder="From Date">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>To Date *</label>
                    <input class="form-control" type="text" nolim-calendor="true" name="travel_date_to" value="<?= (isset($details['travel_date_to'])) ? date('d M Y', $details['travel_date_to']) : '' ?>" placeholder="To Date">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Cabin Class *</label>
                    <select class="form-select select_search" name="cabin_class[]" placeholder="Cabin Class" multiple="multiple">
                        <?php $cabinClass = explode(',', $details['cabin_class']); ?>
                        <option value="Economy" <?= (in_array('Economy', $cabinClass)) ? 'selected' : '' ?>>Economy</option>
                        <option value="PremiumEconomy" <?= (in_array('PremiumEconomy', $cabinClass)) ? 'selected' : '' ?>>Premium Economy</option>
                        <option value="Business" <?= (in_array('Business', $cabinClass)) ? 'selected' : '' ?>>Business</option>
                        <option value="First" <?= (in_array('First', $cabinClass)) ? 'selected' : '' ?>>First</option>
                        <option value="PremiumBusiness" <?= (in_array('PremiumBusiness', $cabinClass)) ? 'selected' : '' ?>>Premium Business</option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Flight Type *</label>
                    <?php $flightType = explode(',', $details['is_domestic']); ?>
                    <select class="form-select select_search" name="is_domestic[]" placeholder="Flight Type" multiple="multiple">
                        <option value="1" <?= (in_array('1', $flightType)) ? 'selected' : '' ?>>Domestic</option>
                        <option value="0" <?= (in_array('0', $flightType)) ? 'selected' : '' ?>>International</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Journey Type *</label>
                    <?php $journeyType = explode(',', $details['journey_type']); ?>
                    <select class="form-select select_search" name="journey_type[]" placeholder="Journey Type" multiple="multiple">
                        <option value="oneway" <?= (in_array('oneway', $journeyType)) ? 'selected' : '' ?>>Oneway</option>
                        <option value="round-trip" <?= (in_array('round-trip', $journeyType)) ? 'selected' : '' ?>>Round Trip</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Markup Type *</label>
                    <select class="form-select" name="markup_type" placeholder="Markup Type">
                        <option value="fixed" <?= ('fixed' == $details['markup_type']) ? 'selected' : '' ?>>Fixed</option>
                        <option value="percent" <?= ('percent' == $details['markup_type']) ? 'selected' : '' ?>>Percent</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-0">
                <div class="form-group form-mb-20">
                    <label>Display Markup *</label>
                    <select class="form-select" name="display_markup" placeholder="Display Markup">
                        <option value="in_tax" <?= ('in_tax' == $details['display_markup']) ? 'selected' : '' ?>>In Tax</option>
                        <option value="in_service_charge" <?= ('in_service_charge' == $details['display_markup']) ? 'selected' : '' ?>>In Service Charge</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Value *</label>
                    <input class="form-control" type="text" name="value" value="<?= (isset($details['value'])) ? $details['value'] : '' ?>" placeholder="Value">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Max Limit </label>
                    <input class="form-control" type="text" name="max_limit" value="<?= (isset($details['max_limit'])) ? $details['max_limit'] : '' ?>" placeholder="Max Limit">
                </div>
            </div>
            <div class="col-md-4 mb-0">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
                        <option value="active" <?= ('active' == $details['status']) ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ('inactive' == $details['status']) ? 'selected' : '' ?>> Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <Button class="badge badge-md badge-primary" type="submit" value="Save">Save</Button>
    </div>
</form>