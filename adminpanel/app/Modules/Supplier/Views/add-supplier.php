<?php $country_codes = get_countary_code();  ?>

<div class="content">
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Add <?php echo $title ?> </h5>
            </div>
            <div class="card-body">
                <form name="web-partner" tts-form='true' action="<?php echo site_url('suppliers/add-supplier-save'); ?>"
                    method="POST" id="web-partner">
                    <div class="row">
                        <div class="col-md-12 ">
                            <h6 class="view_head">Company Information</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Company Name *</label>
                                <input class="form-control" type="text" name="company_name" placeholder="Company Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>GST Name</label>
                                <input class="form-control" type="text" name="gst_holder_name" placeholder="GST Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>GST No </label>
                                <input class="form-control" type="text" name="gst_number" placeholder="GST No">
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
                                <input class="form-control" type="text" name="pan_name" placeholder="PAN Name">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>PAN Number *</label>
                                <input class="form-control" type="text" name="pan_number" placeholder="PAN Number">
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
                                <input class="form-control" type="text" name="aadhaar_no" placeholder="Aadhar Number">
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
                                    <?php if ($supplier_class) {
                                        foreach ($supplier_class as $class) { ?>
                                    <option value="<?php echo $class['id'] ?>">
                                        <?php echo ucfirst($class['class_name']) ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Status *</label>
                                <select class="form-select" name="status" placeholder="Status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Address *</label>
                                <input class="form-control" type="text" name="address" placeholder="Address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>City *</label>
                                <input class="form-control" type="text" name="city" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>State *</label>
                                <input class="form-control" type="text" name="state" placeholder="State">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Country *</label>
                                <input class="form-control" type="text" name="country" placeholder="Country">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Pin code *</label>
                                <input class="form-control" type="text" name="pincode" placeholder="Pin code">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="view_head">User Profile</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Email *</label>
                                <input class="form-control" type="text" name="login_email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Password *</label>
                                <input class="form-control" type="text" name="user_password" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Title *</label>
                                <select class="form-select" name="title" placeholder="Title">
                                    <option value="">Select Title</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Ms">Ms</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Miss">Miss</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>First Name *</label>
                                <input class="form-control" type="text" name="user_first_name" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-mb-20">
                                <label>Last Name *</label>
                                <input class="form-control" type="text" name="user_last_name" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="col-md-3 pr-0">
                            <div class="form-group form-mb-20">
                                <label> Phone *</label>
                                <input class="form-control" type="text" name="user_mobile_no" placeholder="Phone">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>DOB</label>
                            <input class="form-control" type="text" dob-calendor="true" name="dob" placeholder="DOB">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>