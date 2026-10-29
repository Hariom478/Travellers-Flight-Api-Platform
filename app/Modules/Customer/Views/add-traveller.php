<div class="modal-header">
    <span class="close" onclick="ttsclosemodel(this)">&times;</span>
    <h5>Add Traveller</h5>
</div>
<div class="vewmodelhed">

    <form action="<?php echo site_url('customer/add-travellers/' . dev_encode($customer_id)); ?>" method="post" tts-form="true" name="add_traveller"
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
                        <label>Marital Status </label>
                        <select class="form-control" name="marital_status" placeholder="Marital Status">
                            <option value="single" selected>Single</option>
                            <option value="married">Married</option>
                        </select>
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
                    <h6 class="viewld_h5">Passport Details</h6>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Passport Number</label>
                        <input class="form-control" type="text" name="passport_number" placeholder="Passport Number">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Passport Expiry Date</label>
                        <input class="form-control" type="text" name="passport_expiry_date" placeholder="Passport Expiry Date">
                    </div>
                </div>

                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Passport Issue Date</label>
                        <input class="form-control" type="text" nolim-calendor="true" name="passport_issue_date" placeholder="Passport Issue Date">
                    </div>
                </div>
                <div class="tts-col-4">
                    <div class="form-group">
                        <label>Passport Issuing Country</label>
                        <input class="form-control" type="text" nolim-calendor="true" name="passport_issuing_country" placeholder="Passport Issuing Country">
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
    
