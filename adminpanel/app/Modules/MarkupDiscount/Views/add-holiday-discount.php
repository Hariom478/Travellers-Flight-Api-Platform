
 <div class="modal-header">
        <h5 class="modal-title">Add <?php echo 'Holiday Discount '; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>


    <form action="<?php echo site_url('markup-discount/add-super-admin-holiday-discount'); ?>" method="post" tts-form="true"
          name="add_visa_markup">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Web Partner Class *</label>
                        <select class="form-control select_search" name="web_partner_class_id[]" multiple="multiple"
                                placeholder="Web Partner Class">

                            <?php if ($web_partner_class) {
                                foreach ($web_partner_class as $class) { ?>
                                    <option value="<?php echo $class['id'] ?>"    ><?php echo ucfirst($class['class_name']) ?></option>
                                <?php }
                            } ?>
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
                        <label>Extra Discount </label>
                        <input class="form-control" type="text"  name="extra_discount" placeholder="Extra Discount">
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

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Holiday Destination *</label>

                        <input class="form-control" type="text" name="destination_name" holiday-destination="true" tts-method-name="<?php echo site_url("holiday/get-destinations")?>" placeholder="Holiday Destination">

                        <input type="hidden" name="destination_id" tts-destination-id="true">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Holiday Theme *</label>
                        <input class="form-control" type="text" name="theme_name" placeholder="Holiday Destination" holiday-theme="true" tts-method-name="<?php echo site_url("holiday/get-themes")?>">
                        <input type="hidden" name="theme_id" tts-theme-id="true">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <Button class="btn btn-primary" type="submit" >Save</Button>
        </div>
    </form>

