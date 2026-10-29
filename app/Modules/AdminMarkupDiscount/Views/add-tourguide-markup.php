<div class="modal-header">
    <h5 class="modal-title">Add
        <?php echo 'Tourguide Markup '; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('markup-discount/add-tourguide-markup'); ?>" method="post" tts-form="true"
    name="add_visa_markup">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Display Markup *</label>
                    <select class="form-select" name="display_markup" placeholder="Display Markup">
                        <option value="in_tax" selected>In Tax</option>
                        <option value="in_service_charge">In Service Charge</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Markup Type *</label>
                    <select class="form-select" name="markup_type" placeholder="Markup Type">
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
                    <label>Max Limit </label>
                    <input class="form-control" type="text" name="max_limit" placeholder="Max Limit">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Status *</label>
                    <select class="form-select" name="status" placeholder="Status">
                        <option value="active" selected>Active</option>
                        <option value="inactive"> Inactive</option>
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
                        placeholder="City Name">
                    <input type="hidden" name="city_id" tour-guide-city="true">
                </div>
            </div>

            <div class="col-md-4 align-self-end" style="margin-top:-18px;">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tour-guide-city" value="ANY"> Any City
                    </label>
                </div>
            </div>


            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Tour Guide Name *</label>

                    <input class="form-control" type="text" name="tour_guide_name" tts-common-autocomplete="true"
                        tts-method-name="<?php echo site_url("tourguide/get-tourguidename") ?>"
                        placeholder="Tourguide Name">

                    <input type="hidden" name="tour_guide_id" tour-guide-id="true">
                </div>
            </div>

            <div class="col-md-4 align-self-end" style="margin-top:-18px;">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tour-guide-id" value="ANY"> Any Tour
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


<script>
    $(document).ready(function() {

        TTSGLOBAL.global.select2ajax();
    })
</script>