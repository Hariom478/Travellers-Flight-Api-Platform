<div class="content">
        
    <div class="page-content">
    <?php echo view('\Modules\Setting\Views\top_menu_bar'); ?>
        <div class="container-fluid p-0 mt-3">
            <div class="settings-panel">
                <div class="page-actions-panel">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="m-0"> Profile Details </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <button data-bs-toggle="modal" data-bs-target="#change_password" class="badge badge-wt"><i class="fa fa-key"></i> Change  Password </button>
                                <button class=" badge badge-wt" view-data-modal="true" data-controller='setting'
                                    data-id="<?php echo dev_encode($profile_details['id']); ?>"
                                    data-href="<?php echo site_url('setting/edit_user/') . dev_encode($profile_details['id']); ?>"><i class="fa-solid fa-edit"></i> Edit
                                    Details </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="setting-content">
                    <div class="row">
                  
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">Name</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo ucfirst($profile_details['first_name']).' '.ucfirst($profile_details['last_name']);  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head"> Email Id </span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['login_email'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">Contact number</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['mobile_isd'];  ?>-<?php echo $profile_details['mobile_no'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">Whats App</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['mobile_isd'];  ?><?php echo $profile_details['whatsapp_no'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">Date Of Birth</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['date_of_birth'];  ?></span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row"><span class="item-text-head">Street </span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['street'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">Country</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['country'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head">State</span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['state'];  ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class="item-text-head">City</span></th>
                                        <td> <span class="item-text-value"><?php echo $profile_details['city'];  ?>
                                            </span> </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class="item-text-head">Zip Code </span></th>
                                        <td> <span
                                                class="item-text-value"><?php echo $profile_details['pin_code'];  ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <br /><br /><br />
    </div>
</div>


<!-- Change Passwords content -->
<div class="modal fade" id="change_password" tabindex="-1" aria-labelledby="change_passwordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="change_passwordLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo site_url('setting/change_password'); ?>" method="post" tts-form="true" name="form_password_change">
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <label>New Password*</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" onclick="generatePassword(8,'form_password_change')"><i class="fa fa-key"></i></span>
                        <input class="form-control" type="text" name="password" placeholder="New Password">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Change Password</button>
      </div>
    </form>
    </div>
  </div>
</div>
