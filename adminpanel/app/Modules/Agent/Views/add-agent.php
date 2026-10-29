<div class="modal-header">
    <h5 class="modal-title">Add Agent</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('agent/add-agent'); ?>" method="post" tts-form="true" name="add_agent"
          enctype="multipart/form-data">

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
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>

                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name*</label>
                        <input class="form-control" type="text" name="first_name" placeholder="First Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name*</label>
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" dob-calendor="true" name="dob" placeholder="DOB">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control" type="email" name="email_id" placeholder="Email">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mobile No*</label>
                        <input class="form-control" type="text" name="mobile_number" placeholder="Mobile No">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password*</label>
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Profile Pic </label>
                        <input class="form-control" type="file" name="profile_pic" placeholder="Profile Pic">
                    </div>
                </div>


                <div class="col-md-12 p0">
                    <h6 class="viewld_h5">Company Information</h6>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Company Name*</label>
                        <input class="form-control" type="text" name="company_name" placeholder="Company Name">
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
                                <option value="<?php echo $class['id']?>"><?php echo $class['class_name']?></option>
                            <?php }}?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST Name</label>
                        <input class="form-control" type="text" name="gst_holder_name" placeholder="GST Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>GST Number</label>
                        <input class="form-control" type="text" name="gst_number" placeholder="GST Number">
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
                        <input class="form-control" type="text" name="pan_holder_name" placeholder="PAN Card Name">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>PAN Number*</label>
                        <input class="form-control" type="text" name="pan_number" placeholder="PAN Number">
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
                        <input class="form-control" type="text" name="aadhaar_no" placeholder="Aadhar Number">
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
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <h6 class="viewld_h5">Billing Address</h6>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address*</label>
                        <input class="form-control" type="text" name="address" placeholder="Address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City*</label>
                        <input class="form-control" type="text" name="city" placeholder="City">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>State*</label>
                        <input class="form-control" type="text" name="state" placeholder="State">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Country*</label>
                        <input class="form-control" type="text" name="country" placeholder="Country">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pin code*</label>
                        <input class="form-control" type="text" name="pincode" placeholder="Pin code">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Add Agent</button>
        </div>
    </form>
</div>  
    
