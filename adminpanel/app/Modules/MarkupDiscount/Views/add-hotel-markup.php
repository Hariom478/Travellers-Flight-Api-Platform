
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Add <?php echo 'Hotel Markup '; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('markup-discount/add-super-admin-hotel-markup'); ?>" method="post" tts-form="true"
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
                        <label>Hotel Markup Type *</label>
                        <select class="form-control" name="hotel_markup_type" placeholder="Hotel Markup Type">
                            <option value="per_night" selected>Per Night</option>
                            <option value="per_room">Per Room</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Star Rating *</label>
                        <select class="form-control select_search" name="star_rating[[]" placeholder="Star Rating" multiple="multiple">
                            <option value="1">1 Star</option>
                            <option value="2">2 Star</option>
                            <option value="3">3 Star</option>
                            <option value="4">4 Star</option>
                            <option value="5">5 Star</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group form-mb-20">
                        <label>Display Markup *</label>
                        <select class="form-control" name="display_markup" placeholder="Display Markup">
                            <option value="in_tax" selected>In Tax</option>
                            <option value="in_service_charge">In Service Charge</option>
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
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
