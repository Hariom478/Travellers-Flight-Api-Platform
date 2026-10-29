<?php if ($user_detail) {  ?>

<div class="modal-header">
    <h5 class="modal-title">Edit User Detail</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form name="edit_user" action="<?php echo site_url('setting/update_user'); ?>/<?php echo dev_encode($edit_user_id); ?>" tts-form="true" enctype="multipart/form-data" method="Post">
    <div class="modal-body" >
    <div id="lead_details" class="current p0">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>First Name </label>
                    <input class="form-control" type="text" name="first_name" value="<?php echo $user_detail['first_name']; ?>" placeholder="First Name">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Last Name</label>
                    <input class="form-control" type="text" name="last_name"  value="<?php echo $user_detail['last_name']; ?>" placeholder="Last Name">
                </div>
            </div>
            <div class="col-md-4">
         
                <div class="form-group form-mb-20">
                    <label> Dial Code</label>
                    <select class="form-select select_search" name="mobile_isd" data-dialcode> <?php   $country_codes = get_countary_code();
                                if ($country_codes) {
                                    foreach ($country_codes as $country_code) {    ?>
                        <option value="<?php echo $country_code['dialcode']; ?>" 
                          <?php if ($country_code['dialcode'] == $user_detail['mobile_isd']) {  echo "selected";  } ?>>
                            <?php echo $country_code['countryname']; ?> ( <?php echo $country_code['dialcode']; ?> )</option>
                        <?php }
                                } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Mobile No *</label>
                    <input class="form-control" type="text" name="mobile_no" value="<?php echo $user_detail['mobile_no']; ?>"  placeholder="Mobile">
                </div>
            </div>
            <div class="col-md-4 ">
                <div class="form-group form-mb-20">
                    <label> Whatsapp No </label>
                    <input class="form-control" type="text" name="whatsapp_no" value="<?php echo $user_detail['whatsapp_no']; ?>" placeholder="Whatsapp No">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label> Date of Birth</label>
                    <input class="form-control" type="text" name="date_of_birth" value="<?php echo $user_detail['date_of_birth']; ?>" dob-calendor
                        placeholder="Date of Birth">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Street</label>
                    <input class="form-control" type="text" name="street" value="<?php echo $user_detail['street']; ?>" placeholder="street">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>City</label>
                    <input class="form-control" type="text" name="city" value="<?php echo $user_detail['city']; ?>" placeholder="City">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>State</label>
                    <input class="form-control" type="text" name="state" value="<?php echo $user_detail['state']; ?>" placeholder="state">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Country</label>
                    <input class="form-control" type="text" name="country" value="<?php echo $user_detail['country']; ?>" placeholder="country">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Zip Code</label>
                    <input class="form-control" type="text" name="pin_code" value="<?php echo $user_detail['pin_code']; ?>" placeholder="Zip Code">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Designation</label>
                    <input class="form-control" type="text" name="designation" value="<?php echo $user_detail['designation']; ?>" placeholder="Designation">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Job Joining Date</label>
                    <input class="form-control" type="text" name="job_joining_date" value="<?php echo $user_detail['job_joining_date']; ?>" dob-calendor
                        placeholder="joining date">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-mb-20">
                    <label>Change Password</label>
                    <input class="form-control" type="text" name="password" placeholder="Change Password">
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
   <button type="submit" class="btn btn-primary">Update</button>
</div>

                            </form>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
}?>