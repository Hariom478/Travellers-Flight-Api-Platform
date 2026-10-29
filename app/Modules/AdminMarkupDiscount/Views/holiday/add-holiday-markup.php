<div class="modal-header">
    <h5 class="modal-title">Add <?php echo 'Holiday Markup '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?php echo site_url('markup-discount/add-holiday-markup'); ?>" method="post" tts-form="true" name="add_visa_markup">

    <div class="modal-body">
        <div class="row gy-3">
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

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Holiday Destination *</label>

                    <input class="form-control" type="text" name="destination_name" tts-common-autocomplete="true" tts-method-name="<?php echo site_url("holiday/get-destinations") ?>" placeholder="Holiday Destination">

                    <input type="hidden" name="destination_id" tts-destination-id="true">
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tts-destination-id" value="ANY" class="Lead"> Any Destination Name
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Holiday Theme *</label>
                    <input class="form-control" type="text" name="theme_name" placeholder="Holiday Theme" tts-common-autocomplete="true" tts-method-name="<?php echo site_url("holiday/get-themes") ?>">
                    <input type="hidden" name="theme_id" tts-theme-id="true">
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tts-theme-id" value="ANY" class="Lead"> Any Holiday Theme
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Package Name *</label>
                    <input class="form-control" type="text" name="holiday_package" placeholder="Package Name" tts-common-autocomplete="true" tts-method-name="<?php echo site_url("holiday/get-packages") ?>">
                    <input type="hidden" name="holiday_package_id" tts-holiday-package-id="true">
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-commnon-any="true" resettag="tts-holiday-package-id" value="ANY" class="Lead"> Any Holiday Package Name
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>