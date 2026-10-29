<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add Customer</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('customer/add-customer'); ?>" method="post" tts-form="true" name="add_customer"
          enctype="multipart/form-data">

        <div class="modal-body">
            <div class="tts_row">
                <div class="tts-col-12 p0">
                    <h6 class="viewld_h5">Personal Details</h6>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Title *</label>
                        <select class="form-control" name="title" placeholder="Title">
                            <option value="" >Select Title</option>
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>

                        </select>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>First Name*</label>
                        <input class="form-control" type="text" name="first_name" placeholder="First Name">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>DOB</label>
                        <input class="form-control" type="text" dob-calendor="true" name="dob" placeholder="DOB">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control" type="email" name="email_id" placeholder="Email">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Mobile No*</label>
                        <input class="form-control" type="text" name="mobile_no" placeholder="Mobile No">
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
                            <input type="checkbox" name="email_verify"  value="Verified" class="Lead"  >Email Verified
                        </label>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="mobile_verify"  value="Verified" class="Lead" >Mobile Verified
                        </label>
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Password*</label>
                        <input class="form-control" type="text" name="password" placeholder="Password">
                        <button class="mt8 badge badge-md badge-primary" type="button" onclick=generatePassword(10,'add_customer');>Generate Password</button>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label><input type="checkbox" name="send_email" value="send">Send account details on customer email id</label>
                        </div>
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-control" name="status" placeholder="Status">
                            <option value="active" selected>Active</option>
                            <option value="inactive"> Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="tts-col-12 p0">
                    <h6 class="viewld_h5">Customer Address</h6>
                </div>
                <div class="tts-col-12">
                    <div class="form-group">
                        <label>Address*</label>
                        <input class="form-control" type="text" name="address" placeholder="Address">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>City*</label>
                        <input class="form-control" type="text" name="city" placeholder="City">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>State*</label>
                        <input class="form-control" type="text" name="state" placeholder="State">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Country*</label>
                        <input class="form-control" type="text" name="country" placeholder="Country">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Pin code*</label>
                        <input class="form-control" type="text" name="pin_code" placeholder="Pin code">
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
    
