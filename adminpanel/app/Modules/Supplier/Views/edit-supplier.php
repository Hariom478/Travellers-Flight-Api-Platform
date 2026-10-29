<div class="content">
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Edit <?php echo $title ?> </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('suppliers/edit-supplier-save/' . dev_encode($id)); ?>" method="post"
                    tts-form="true" name="web-partner" enctype="multipart/form-data">

                    <div class="row">


                        <div class="col-md-12 ">
                            <h6 class="view_head">Company Information</h6>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Company Name *</label>
                                <input class="form-control" type="text" name="company_name"
                                    value="<?php echo $details['company_name'];?>" placeholder="Company Name">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>GST Name</label>
                                <input class="form-control" type="text" name="gst_holder_name" placeholder="GST Name"
                                    value="<?php echo $details['gst_holder_name'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>GST No </label>
                                <input class="form-control" type="text" name="gst_number" placeholder="GST No"
                                    value="<?php echo $details['gst_number'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>GST Scan Copy</label>
                                <input class="form-control" type="file" name="gst_scan_copy"
                                    placeholder="GST Scan Copy">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>PAN Name *</label>
                                <input class="form-control" type="text" name="pan_name" placeholder="PAN Name"
                                    value="<?php echo $details['pan_name'];?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>PAN Number *</label>
                                <input class="form-control" type="text" name="pan_number" placeholder="PAN Number"
                                    value="<?php echo $details['pan_number'];?>">
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>PAN Card Scan Copy*</label>
                                <input class="form-control" type="file" name="pan_card"
                                    placeholder="PAN Card Scan Copy">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Aadhar Number*</label>
                                <input class="form-control" type="text" name="aadhaar_no" placeholder="Aadhar Number"
                                    value="<?php echo $details['aadhaar_no'];?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Aadhar Card Scan Copy*</label>
                                <input class="form-control" type="file" name="aadhar_scan_copy"
                                    placeholder="Aadhar Card Scan Copy">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Supplier Class *</label>
                                <select class="form-select" name="supplier_class_id">
                                    <option value="">Select supplier class</option>
                                    <?php if($supplier_class){ foreach ($supplier_class as $class){?>
                                    <option value="<?php echo $class['id']?>" <?php if ($details['supplier_class_id'] ==  $class['id']) {
                                            echo "selected";
                                        } ?>><?php echo ucfirst($class['class_name'])?></option>
                                    <?php }}?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Status *</label>
                                <select class="form-select" name="status" placeholder=" Status">
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

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Address *</label>
                                <input class="form-control" type="text" name="address" placeholder="Address"
                                    value="<?php echo $details['address'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>City *</label>
                                <input class="form-control" type="text" name="city" placeholder="City"
                                    value="<?php echo $details['city'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>State *</label>
                                <input class="form-control" type="text" name="state" placeholder="State"
                                    value="<?php echo $details['state'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Country *</label>
                                <input class="form-control" type="text" name="country" placeholder="Country"
                                    value="<?php echo $details['country'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Pin code *</label>
                                <input class="form-control" type="text" name="pincode" placeholder="Pin code"
                                    value="<?php echo $details['pincode'];?>">
                            </div>
                        </div>


                    </div>




                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="view_head">User Profile</h6>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Title *</label>
                                <select class="form-select" name="title" placeholder="Title">
                                    <option value="">Select Title</option>
                                    <option value="Mr" <?php if ($user_details['title'] == "Mr") {
                                echo "selected";
                            } ?>>Mr</option>
                                    <option value="Ms" <?php if ($user_details['title'] == "Ms") {
                                echo "selected";
                            } ?>>Ms</option>
                                    <option value="Mrs" <?php if ($user_details['title'] == "Mrs") {
                                echo "selected";
                            } ?>>Mrs</option>
                                    <option value="Miss" <?php if ($user_details['title'] == "Miss") {
                                echo "selected";
                            } ?>>Miss</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>First Name *</label>
                                <input class="form-control" type="text" name="user_first_name" placeholder="First Name"
                                    value="<?php echo $user_details['first_name'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Last Name *</label>
                                <input class="form-control" type="text" name="user_last_name" placeholder="Last Name"
                                    value="<?php echo $user_details['last_name'];?>">
                            </div>
                        </div>

                        <div class="col-md-3 pl-0">
                            <div class="form-group form-mb-20">
                                <label> Phone *</label>
                                <input class="form-control" type="text" name="user_mobile_no" placeholder="Phone"
                                    value="<?php echo $user_details['mobile_no'];?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>DOB</label>
                                <input class="form-control" type="text" dob-calendor="true" name="dob" placeholder="DOB"
                                    value="<?php echo $user_details['dob'];?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>