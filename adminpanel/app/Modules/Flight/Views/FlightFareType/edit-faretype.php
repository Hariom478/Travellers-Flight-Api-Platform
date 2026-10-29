<div class="modal-header">
    <h5 class="modal-title">Edit
        <?php echo 'Fare Type'; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<form action="<?php echo site_url('flightfaretype/edit-faretype/' . dev_encode($id)); ?>" method="post" tts-form="true"
    name="add_feedback" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label>Supplier Fare Type * </label>
                    <input class="form-control" type="text" name="supplier_fare_type" placeholder="Supplier Fare Type"
                        value="<?= $details['supplier_fare_type'] ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-mb-20">
                    <label> API Fare Type * </label>
                    <input class="form-control" type="text" name="api_fare_type" placeholder="API Fare Type"
                        value="<?= $details['api_fare_type'] ?>">
                    <span class="text-danger"> value should be without special characters and space</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-mb-20">
                    <label>API Supplier * </label>
                    <select class="form-control" name="api_supplier" placeholder="API Supplier">
                        <option value="">Select * </option>
                        <?php
                        foreach ($api_supplier as $supplier) {
                            ?>
                        <option value="<?php echo $supplier['supplier_name'] ?>" <?php
                                if ($supplier['supplier_name'] === $details['api_supplier']) {
                                    echo "selected";
                                }
                            ?>>
                            <?= $supplier['supplier_name'] ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>