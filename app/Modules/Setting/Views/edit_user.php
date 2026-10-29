<?php if ($user_detail) { ?>
    <div class="modal-header">
        <h5 class="modal-title">Edit User Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form name="edit_user" action="<?php echo site_url('setting/update_user'); ?>/<?php echo dev_encode($edit_user_id); ?>"
        tts-form="true" enctype="multipart/form-data" method="Post">
        <div class="modal-body">
            <div id="lead_details" class="current">
                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name </label>
                            <input class="form-control" type="text" name="first_name"
                                value="<?php echo $user_detail['first_name']; ?>" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input class="form-control" type="text" name="last_name"
                                value="<?php echo $user_detail['last_name']; ?>" placeholder="Last Name">
                        </div>
                    </div>

                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label> Phone *</label>
                            <input class="form-control" type="text" name="mobile_no"
                                value="<?php echo $user_detail['mobile_no']; ?>" placeholder="Phone">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" name="street"
                                value="<?php echo $user_detail['street']; ?>" placeholder="address">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control" type="text" name="city" value="<?php echo $user_detail['city']; ?>"
                                placeholder="City">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State</label>
                            <input class="form-control" type="text" name="state"
                                value="<?php echo $user_detail['state']; ?>" placeholder="state">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country</label>
                            <input class="form-control" type="text" name="country"
                                value="<?php echo $user_detail['country']; ?>" placeholder="country">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input class="form-control" type="text" name="pin_code"
                                value="<?php echo $user_detail['pin_code']; ?>" placeholder="Zip Code">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label>Profile </label>
                            <input class="form-control" type="file" name="profile_pic" placeholder="Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" type="submit">Update</button>
        </div>

    </form>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>