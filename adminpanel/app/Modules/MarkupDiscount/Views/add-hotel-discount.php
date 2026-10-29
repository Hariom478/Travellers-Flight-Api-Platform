
            <div class="modal-header">
                <h5 class="modal-title" >Add <?php echo 'Hotel Discount '; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>


    <form action="<?php echo site_url('markup-discount/add-super-admin-hotel-discount'); ?>" method="post" tts-form="true"
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
                                    <option value="<?php echo $class['id'] ?>"><?php echo ucfirst($class['class_name']) ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Supplier *</label>
                        <select class="form-control select_search" name="supplier[]" multiple="multiple">

                            <?php if ($ApiSupplierModel) {
                                foreach ($ApiSupplierModel as $data) { ?>
                                    <option value="<?php echo $data['supplier_name'] ?>">
                                        <?php echo ucfirst($data['supplier_name']); ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Region Type *</label>
                        <select class="form-control select_search" name="region_type[]" placeholder="Region Type" multiple="multiple">
                            <option value="domestic" selected>Domestic</option>
                            <option value="international">International</option>
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
                        <label>Extra Discount *</label>
                        <input class="form-control" type="text" name="extra_discount" placeholder="Extra Discount">
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
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit" >Save</button>
        </div>
    </form>

