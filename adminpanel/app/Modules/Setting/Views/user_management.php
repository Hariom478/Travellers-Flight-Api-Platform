<div class="content">
    <div class="page-content">
        <?php echo view('\Modules\Setting\Views\top_menu_bar'); ?>
        <div class="container-fluid p-0 mt-3 mb-3">
            <div class="settings-panel">
                <div class="page-actions-panel">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="m-0">User Management </h5>
                        </div>
                        <div class="col-md-8 text-end">

                            <?php if (permission_access("Setting", "add_user")) { ?>
                                <a href="javascript:void(0);" class=" badge badge-wt" onclick="ttsopenmodel('adduser')"><i class="fa-solid fa-add"></i> Add User </a>
                            <?php } ?>
                            <?php if (permission_access("Setting", "user_status")) { ?>
                                <button class="badge badge badge-wt" onclick="confirm_change_status('status_change')">
                                    <i class="fa fa-exchange"></i> Change Status</button>
                            <?php } ?> 
                        </div>
                    </div>
                </div>

                <form action="<?php echo site_url('setting/delete_user'); ?>" method="POST" tts-form="true" id="formuserlist">
                    <div class="setting-content">
                        <div class="col-md-12 p0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-active">
                                        <tr>
                                            <?php if (permission_access("Setting", "delete_user") || permission_access("Setting", "user_status")) { ?>
                                                <th><label><input type="checkbox" name="check_all" id="selectall"></label>
                                                </th>
                                            <?php } ?>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile No</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            <?php if (permission_access("Setting", "user_permissions")) { ?>
                                                <th>Access</th>
                                            <?php } ?>
                                            <th>Created Date</th>
                                            <?php if (permission_access("Setting", "edit_user")) { ?>
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
                                                    <?php if (permission_access("Setting", "delete_user") || permission_access("Setting", "user_status"))  { ?>
                                                        <td>
                                                            <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $staff_list['id']; ?>"></label>
                                                        </td>
                                                    <?php } ?>
                                                    <td><?php echo ucfirst($staff_list['first_name']); ?> <?php echo ucfirst($staff_list['last_name']); ?>
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
                                                    <?php if (permission_access("Setting", "user_permissions")) { ?>
                                                        <td><a href="javascript:void(0);" class="tag_label" view-data-modal="true" data-controller="setting" data-id="<?php echo dev_encode($staff_list['id']); ?>" data-href="<?php echo site_url('setting/user_access_permission/') . dev_encode($staff_list['id']); ?>">Access
                                                                Permission </a></td>
                                                    <?php } ?>

                                                    <td><?php echo date_created_format($staff_list['created_date']); ?></td>
                                                    <?php if (permission_access("Setting", "edit_user")) { ?>
                                                        <td>
                                                            <a href="javascript:void(0);" class="tag_label" view-data-modal="true" data-controller='setting' data-id="<?php echo dev_encode($staff_list['id']); ?>" data-href="<?php echo site_url('setting/edit_user/') . dev_encode($staff_list['id']); ?>">
                                                                <i class="fa-solid fa-edit "></i>
                                                            </a>
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

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>



<!-- Add User Modal content -->
<div id="adduser" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User / Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="adduser" action="<?php echo site_url('setting/add_user'); ?>" method="POST" id="formlead" tts-form="true">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group form-mb-20 ">
                                <label>First Name *</label>
                                <input class="form-control" type="text" name="first_name" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Last Name *</label>
                                <input class="form-control" type="text" name="last_name" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Email ID / Login Email *</label>
                                <input class="form-control" type="text" name="login_email" placeholder="Email ID">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Password *</label>
                                <input class="form-control" type="password" name="password" placeholder="Password">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label> Dial Code</label>
                                <select class="form-control select_search" name="mobile_isd" data-dialcode>
                                    <?php
                                    $country_codes = get_countary_code();
                                    if ($country_codes) {
                                        foreach ($country_codes as $country_code) { ?>
                                            <option value="<?php echo $country_code['dialcode']; ?>" <?php if ($country_code['dialcode'] =='+91') { echo "selected"; } ?>>
                                                 <?php echo $country_code['countryname']; ?> ( <?php echo $country_code['dialcode']; ?> )
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label> Mobile No. *</label>
                                <input class="form-control" type="text" name="mobile_no" placeholder="Mobile">
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="form-group form-mb-20">
                                <label> Whatsapp No </label>
                                <input class="form-control" type="text" name="whatsapp_no" placeholder="Whatsapp No">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label> Date of Birth</label>
                                <input class="form-control" type="text"  name="date_of_birth" dob-calendor placeholder="Date of Birth">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Street</label>
                                <input class="form-control" type="text" name="street" placeholder="Street">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>City</label>
                                <input class="form-control" type="text" name="city" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>State</label>
                                <input class="form-control" type="text" name="state" placeholder="State">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
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
                            <div class="form-group form-mb-20">
                                <label>Zip Code</label>
                                <input class="form-control" type="text" name="pin_code" placeholder="Zip Code">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Designation *</label>
                                <input class="form-control" type="text" name="designation" placeholder="Designation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Job Joining Date *</label>
                                <input class="form-control" type="text"  name="job_joining_date" dob-calendor placeholder="joining date" readonly>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="send" name="send_email" id="send-email">
                                <label class="form-check-label" for="send-email">
                                    Send account details on user email id
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- status status change content -->
<div id="status_change" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('setting/user-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-mb-20">

                                <select class="form-select" name="status">
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Show  status Modal content -->