<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Edit Customer</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('customer/edit-customer/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="edit_agent" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Title *</label>
                        <select class="form-control" name="title" placeholder="Title">
                            <option value="">Select Title</option>
                            <option value="Mr" <?php if ($details['title'] == "Mr") {
                                echo "selected";
                            } ?>>Mr
                            </option>
                            <option value="Ms" <?php if ($details['title'] == "Ms") {
                                echo "selected";
                            } ?>>Ms
                            </option>
                            <option value="Mrs" <?php if ($details['title'] == "Mrs") {
                                echo "selected";
                            } ?>>Mrs
                            </option>
                            <option value="Miss" <?php if ($details['title'] == "Miss") {
                                echo "selected";
                            } ?>>Miss
                            </option>

                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>First Name*</label>
                        <input class="form-control" type="text" name="first_name"
                               value="<?php echo $details['first_name'] ?>" placeholder="First Name">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" name="last_name"
                               value="<?php echo $details['last_name'] ?>" placeholder="Last Name">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" name="dob" dob-calendor="true"
                               value="<?php echo $details['dob'] ?>" placeholder="DOB">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control" type="email" name="email_id"
                               value="<?php echo $details['email_id'] ?>" placeholder="Email">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Mobile No*</label>
                        <input class="form-control" type="text" name="mobile_no"
                               value="<?php echo $details['mobile_no'] ?>" placeholder="Mobile No">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Profile Pic </label>
                        <input class="form-control" type="file" name="profile_pic" placeholder="Profile Pic">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="email_verify" value="Verified"
                                   class="Lead" <?php if ($details['email_verify'] == 1) {
                                echo 'checked';
                            } ?> >Email Verified
                        </label>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="mobile_verify" value="Verified"
                                   class="Lead" <?php if ($details['mobile_verify'] == 1) {
                                echo 'checked';
                            } ?> >Mobile Verified
                        </label>
                    </div>
                </div>


                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
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

                <div class="tts-col-12 p0">
                    <h6 class="viewld_h5">Customer Address</h6>
                </div>
                <div class="tts-col-12">
                    <div class="form-group">
                        <label>Address*</label>
                        <input class="form-control" type="text" name="address" value="<?php echo $details['address'] ?>"
                               placeholder="Address">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>City*</label>
                        <input class="form-control" type="text" value="<?php echo $details['city'] ?>" name="city"
                               placeholder="City">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>State*</label>
                        <input class="form-control" type="text" name="state" value="<?php echo $details['state'] ?>"
                               placeholder="State">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Country*</label>
                        <input class="form-control" type="text" value="<?php echo $details['country'] ?>" name="country"
                               placeholder="Country">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Pin code*</label>
                        <input class="form-control" type="text" value="<?php echo $details['pin_code'] ?>"
                               name="pin_code" placeholder="Pin code">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="tts_row">
                <div class="tts-col-12">
                    <input class="badge badge-md badge-primary" type="submit" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>  
    
