<div class="modal-header">
    <h5 class="modal-title">Edit
        <?php echo 'Tourguide Markup '; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="<?php echo site_url('markup-discount/edit-tourguide-markup/') . dev_encode($id); ?>" method="post"
    tts-form="true" name="edit_visa_markup">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Markup Type *</label>
                    <select class="form-select" name="markup_type" placeholder="Markup Type">
                        <option value="fixed" <?php if ($details['markup_type'] == "fixed") {
                            echo "selected";
                        } ?>>Fixed</option>
                        <option value="percent" <?php if ($details['markup_type'] == "percent") {
                            echo "selected";
                        } ?>>Percent</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Value *</label>
                    <input class="form-control" type="text" value="<?php echo $details['value']; ?>" name="value"
                        placeholder="Value">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Display Markup *</label>
                    <select class="form-select" name="display_markup" placeholder="Display Markup">
                        <option value="in_tax" <?php if ($details['display_markup'] == "in_tax") {
                            echo "selected";
                        } ?>>In Tax</option>
                        <option value="in_service_charge" <?php if ($details['display_markup'] == "in_service_charge") {
                            echo "selected";
                        } ?>>In Service Charge</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Max Limit </label>
                    <input class="form-control" type="text" value="<?php echo $details['max_limit']; ?>"
                        name="max_limit" placeholder="Max Limit">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
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
        <div class="row">
            
        <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label for="">City Name*</label>
                    <!-- <select class="form-select" name="city_id[]" tts-select2-ajax="true"
                        tts-method-name="tourguide/get-cities" placeholder="City" multiple="multiple">
                        <option value=""></option>
                    </select> -->
                    <input class="form-control" type="text" name="city_name" tts-common-autocomplete="true"
                        tts-method-name="<?php echo site_url("tourguide/get-cities") ?>"
                        placeholder="City Name" value="<?php echo $details['city_name']; ?>">
                    <input type="hidden" name="city_id" tour-guide-city="true" value="<?php echo $details['city_id']; ?>">
                </div>
            </div>

            <div class="col-md-4 align-self-end" style="margin-top:-18px;">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tour-guide-city" value="ANY" <?php echo ($details['city_id'] == 'ANY') ? 'checked' : ''; ?>> Any City
                    </label>
                </div>
            </div>


        
            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Tour Guide Name *</label>

                    <input class="form-control" type="text" name="tour_guide_name" tts-common-autocomplete="true"
                        tts-method-name="<?php echo site_url("tourguide/get-tourguidename") ?>"
                        placeholder="Tourguide Name"  value="<?php echo $details['tour_guide_name']; ?>">

                    <input type="hidden" name="tour_guide_id" tour-guide-id="true" value="<?php echo $details['tour_guide_id']; ?>">
                </div>
            </div>

            <div class="col-md-4 align-self-end" style="margin-top:-18px;">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tour-guide-id" value="ANY"  <?php echo ($details['tour_guide_id'] == 'ANY') ? 'checked' : ''; ?>> Any Tour
                        Guide Name
                    </label>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>