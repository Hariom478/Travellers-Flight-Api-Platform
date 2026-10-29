<section class="dashboard-section d-flex align-items-center ">
    <div class="container">
        <?php echo view('\Modules\Setting\Views\top_menu_bar'); ?>
        <div class="card rounded-0 border-0">
            <div class="card-header border-0">
                <div class="row align-items-center ">
                    <div class="col-md-4">
                        <h5 class="m-0">User Management </h5>
                    </div>
                    <div class="col-md-8 text-end">
                        <?php if (permission_access("Setting", "add_staff") || 1) { ?>
                            <a href="javascript:void(0);" class="btn btn-secondary btn-sm" onclick="ttsopenmodel('adduser')"><i class="fa-solid fa-add"></i> Add Staff </a>
                        <?php } ?>
                        <?php if (permission_access("Setting", "staff_status") || 1) { ?>
                            <button class="btn btn-primary  btn-sm" onclick="confirm_change_status('status_change')">
                                <i class="fa fa-exchange"></i> Change Status</button>
                        <?php } ?>
                    </div>
                    <div class="col">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?php echo site_url('setting/delete_user'); ?>" method="POST" tts-form="true" id="formuserlist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <?php if (permission_access("Setting", "delete_staff") || permission_access("Setting", "staff_status") || 1) { ?>
                                        <th><label><input type="checkbox" name="check_all" id="selectall"></label>
                                        </th>
                                    <?php } ?>
                                    <th>User Name</th>
                                    <th>User Email</th>
                                    <th>Mobile No</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <?php if (permission_access("Setting", "staff_permissions") || 1) { ?>
                                        <th>Access</th>
                                    <?php } ?>
                                    <th>Created Date</th>
                                    <?php if (permission_access("Setting", "edit_staff") || 1) { ?>
                                        <th>Action</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($staff_lists) {
                                    foreach ($staff_lists as $staff_list) {
                                        if ($staff_list['status'] == 'active') {
                                            $class = 'active-status';
                                        } else {
                                            $class = 'inactive-status';
                                        }
                                ?>
                                        <tr>
                                            <?php if (permission_access("Setting", "delete_staff") || 1) { ?>
                                                <td>
                                                    <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $staff_list['id']; ?>"></label>
                                                </td>
                                            <?php } ?>
                                            <td><a href="javascript:void(0);" class="txt_led_clr"><?php echo ucfirst($staff_list['first_name']); ?><?php echo ucfirst($staff_list['last_name']); ?> </a>
                                            </td>
                                            <td><?php echo $staff_list['login_email']; ?></td>
                                            <td>
                                                <span class="wite_spce"> <?php echo $staff_list['mobile_isd']; ?>-<?php echo $staff_list['mobile_no']; ?></span>
                                            </td>

                                            <td>
                                                <span class="wite_spce"> <?php echo $staff_list['designation']; ?></span>
                                            </td>

                                            <td>
                                                <span class="<?php echo $class ?>">
                                                    <?php echo ucfirst($staff_list['status']); ?>
                                                </span>
                                            </td>
                                            <?php if (permission_access("Setting", "staff_permissions") || 1) { ?>
                                                <td><a href="javascript:void(0);" class="tag_label" view-data-modal="false" data-controller="setting" data-id="<?php echo dev_encode($staff_list['id']); ?>" data-href="<?php echo site_url('setting/user_access_permission/') . dev_encode($staff_list['id']); ?>">Access
                                                        Permission </a></td>
                                            <?php } ?>

                                            <td><?php echo date_created_format($staff_list['created_date']); ?></td>
                                            <?php if (permission_access("Setting", "edit_staff") || 1) { ?>
                                                <td>
                                                    <a href="javascript:void(0);" class="tag_label" view-data-modal="false" data-controller='setting' data-id="<?php echo dev_encode($staff_list['id']); ?>" data-href="<?php echo site_url('setting/edit_user/') . dev_encode($staff_list['id']); ?>">
                                                        <i class="fa fa-edit "></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="tag_label" view-data-modal="true" data-controller='setting' data-id="<?php echo dev_encode($staff_list['id']); ?>" data-href="<?php echo site_url('setting/staff-change-password/') . dev_encode($staff_list['id']); ?>"><i class="fa fa-lock"></i> </a>
                                                <?php } ?>
                                                </td>
                                        </tr>

                                <?php }
                                } else {
                                    echo '<tr><td colspan="9" class="text-center"> No Record Found.</td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>



                </form>
            </div>
        </div>

    </div>
</section>



<!-- Show Detail Lead Modal content -->
<div id="view_setting" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" data-modal-view='view_modal_data'>
        </div>
    </div>
</div>

<!-- Add Lead Modal content -->
<div id="adduser" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form name="adduser" action="<?php echo site_url('setting/add_user'); ?>" method="POST" id="formlead" tts-form="true">
                <div class="modal-header">
                    <h5 class="modal-title">Add User / Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>First Name* </label>
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
                                <label>Email ID / Login Email*</label>
                                <input class="form-control" type="text" name="login_email" placeholder="Email ID">
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
                                <label> Dial Code</label>
                                <select class="form-control select_search" name="mobile_isd" data-dialcode>
                                    <?php
                                    $country_codes = get_countary_code();
                                    if ($country_codes) {
                                        foreach ($country_codes as $country_code) { ?>
                                            <option value="<?php echo $country_code['dialcode']; ?>" <?php if ($country_code['dialcode'] == 91) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?php echo $country_code['countryname']; ?>
                                                (
                                                <?php echo $country_code['dialcode']; ?> )
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 pl0">
                            <div class="form-group">
                                <label> Mobile Number *</label>
                                <input class="form-control" type="text" name="mobile_no" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Whatsapp No </label>
                                <input class="form-control" type="text" name="whatsapp_no" placeholder="Whatsapp No">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Date of Birth</label>
                                <input class="form-control" type="text" readonly name="date_of_birth" dob-calendor placeholder="Date of Birth">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Street</label>
                                <input class="form-control" type="text" name="street" placeholder="Street">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City</label>
                                <input class="form-control" type="text" name="city" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State</label>
                                <input class="form-control" type="text" name="state" placeholder="State">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control select_search" name="country">
                                    <?php if ($country_codes) {
                                        foreach ($country_codes as $country_code) { ?>
                                            <option value="<?php echo $country_code['countryname']; ?>" <?php if ($country_code['countryname'] == $company_details['country']) {
                                                                                                            echo "Selected";
                                                                                                        } ?>>

                                                <?php echo $country_code['countryname']; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Zip Code</label>
                                <input class="form-control" type="text" name="pin_code" placeholder="Zip Code">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Designation</label>
                                <input class="form-control" type="text" name="designation" placeholder="Designation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Job Joining Date</label>
                                <input class="form-control" type="text" readonly name="job_joining_date" dob-calendor placeholder="joining date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <input class="btn btn-outline-primary" type="submit" value="Save">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- status status change content -->
<div id="status_change" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form action="<?php echo site_url('setting/user-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value="" selected="selected">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <input type="hidden" name="checkedvalue">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary" type="submit" value="Save">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Show  status Modal content -->