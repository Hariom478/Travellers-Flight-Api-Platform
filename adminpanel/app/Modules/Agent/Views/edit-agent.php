<div class="modal-header">
    <h5 class="modal-title">Edit Agent</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
    <form action="<?php echo site_url('agent/edit-agent/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="edit_agent" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <h6 class="viewld_h5">Personal  Information</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Title *</label>
                        <select class="form-select" name="title" placeholder="Title">
                            <option value="" >Select Title</option>
                            <option value="Mr" <?php if ($details['title'] == "Mr") {
                                echo "selected";
                            } ?>>Mr</option>
                            <option value="Ms" <?php if ($details['title'] == "Ms") {
                                echo "selected";
                            } ?>>Ms</option>
                            <option value="Mrs" <?php if ($details['title'] == "Mrs") {
                                echo "selected";
                            } ?>>Mrs</option>
                            <option value="Miss" <?php if ($details['title'] == "Miss") {
                                echo "selected";
                            } ?>>Miss</option>

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name*</label>
                        <input class="form-control" type="text" name="first_name" value="<?php echo $details['first_name']?>" placeholder="First Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" name="last_name" value="<?php echo $details['last_name']?>" placeholder="Last Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" name="dob" dob-calendor="true" value="<?php echo $details['dob']?>" placeholder="DOB">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control" type="email" name="email_id" value="<?php echo $details['login_email']?>" placeholder="Email">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mobile No</label>
                        <input class="form-control" type="text" name="mobile_number" value="<?php echo $details['mobile_no']?>" placeholder="Mobile No">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Profile Pic </label>
                        <input class="form-control" type="file" name="profile_pic" placeholder="Profile Pic">
                    </div>
                </div>

                <div class="tts-col-12 p0">
                    <h6 class="viewld_h5">Company Information</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Company Name*</label>
                        <input class="form-control" type="text" name="company_name" value="<?php echo $details['company_name']?>"  placeholder="Company Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Company Logo</label>
                        <input class="form-control" type="file" name="company_logo" placeholder="Company Logo">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Agent Class *</label>
                        <select class="form-select" name="agent_class" placeholder="Agent Class">
                            <option value="" >Select agent class</option>
                            <?php if($agent_class){ foreach ($agent_class as $class){?>
                                <option value="<?php echo $class['id']?>" <?php if ($details['agent_class'] ==  $class['id']) {
                                    echo "selected";
                                } ?> ><?php echo $class['class_name']?></option>
                            <?php }}?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST Name</label>
                        <input class="form-control" type="text" name="gst_holder_name" value="<?php echo $details['gst_holder_name']?>"  placeholder="GST Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST Number</label>
                        <input class="form-control" type="text" name="gst_number" value="<?php echo $details['gst_number']?>" placeholder="GST Number">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST Scan Copy</label>
                        <input class="form-control" type="file" name="gst_scan_copy" placeholder="GST Scan Copy">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>PAN Card Name*</label>
                        <input class="form-control" type="text" value="<?php echo $details['pan_holder_name']?>" name="pan_holder_name" placeholder="PAN Card Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>PAN Number*</label>
                        <input class="form-control" type="text" name="pan_number" value="<?php echo $details['pan_number']?>" placeholder="PAN Number">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>PAN Card Scan Copy*</label>
                        <input class="form-control" type="file" name="pan_scan_copy" placeholder="PAN Card Scan Copy">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Aadhar Number*</label>
                        <input class="form-control" type="text" name="aadhaar_no" value="<?php echo $details['aadhaar_no']?>" placeholder="Aadhar Number">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Aadhar Card Scan Copy*</label>
                        <input class="form-control" type="file" name="aadhar_scan_copy" placeholder="Aadhar Card Scan Copy">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Status">
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
                    <h6 class="viewld_h5">Billing Address</h6>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address*</label>
                        <input class="form-control" type="text" name="address" value="<?php echo $details['address']?>" placeholder="Address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City*</label>
                        <input class="form-control" type="text" name="city" value="<?php echo $details['city']?>" placeholder="City">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>State*</label>
                        <input class="form-control" type="text" name="state" value="<?php echo $details['state']?>" placeholder="State">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Country*</label>
                        <input class="form-control" type="text" name="country" value="<?php echo $details['country']?>"  placeholder="Country">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pin code*</label>
                        <input class="form-control" type="text" name="pincode" value="<?php echo $details['pincode']?>" placeholder="Pin code">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>  
    
