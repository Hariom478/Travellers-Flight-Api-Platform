<div class="modal-header">
    <h5 class="modal-title">Add <?php echo 'Holiday Discount '; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<form action="<?php echo site_url('holiday/add-holiday-discount'); ?>" method="post" tts-form="true" name="add_visa_markup">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Discount For *</label>
                    <select class="form-select" name="discount_for" tts-markup-used-for="true">
                        <option value="">Please Select</option>
                        <?php $markup_used_for = get_active_whitelable_business();
                        foreach ($markup_used_for as $key => $data) {
                        ?>
                            <option value="<?php echo $key ?>"><?php echo $key ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 d-none" tts-agent-class="true">
                <div class="form-group">
                    <label>Agent Class *</label>
                    <select class="form-select select_search" name="agent_class[]" placeholder="discount Type" multiple="multiple">

                        <?php foreach ($agent_class as $key => $data) {  ?>
                            <option value="<?php echo $data['id'] ?>"><?php echo $data['class_name'] ?></option>
                        <?php } ?>
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
                    <label>Discount Type *</label>
                    <select class="form-select" name="discount_type" placeholder="Discount Type">
                        <option value="fixed" selected>Fixed</option>
                        <option value="percent">Percent</option>
                    </select>
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
                    <label>Extra Discount </label>
                    <input class="form-control" type="text" name="extra_discount" placeholder="Extra Discount">
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
                    <label>Holiday Destination *</label>
                    <select name="destination_name[]" id="destination-name" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_destination as $key => $destination) : ?>
                            <option value="<?= $destination['id'] . '_' . $destination['destination_name'] ?>"><?= $destination['destination_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="destination-name" name="destination_select" value="ANY" class="Lead"> Any Destination Name
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Holiday Theme *</label>
                    <select name="theme_name[]" id="theme-name" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_theme as $key => $theme) : ?>
                            <option value="<?= $theme['id'] . '_' . $theme['theme_name'] ?>"><?= $theme['theme_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="theme-name" name="theme_select" value="ANY" class="Lead"> Any Holiday Theme
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group form-mb-20">
                    <label>Package Name *</label>
                    <select name="holiday_package[]" id="holiday-package" class="form-select select_search" multiple="multiple">
                        <?php foreach ($holiday_package as $key => $package) : ?>
                            <option value="<?= $package['id'] . '_' . $package['holiday_name'] ?>"><?= $package['holiday_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <div class="form-group form-mb-20">
                    <label>
                        <input type="checkbox" tts-select-any="true" resettag="holiday-package" name="package_select" value="ANY" class="Lead"> Any Holiday Package Name
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <Button class="btn btn-primary" type="submit">Save</Button>
    </div>
</form>