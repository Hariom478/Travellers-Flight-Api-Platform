<section class="dashboard-section d-flex align-items-center ">
   <div class="container">
      <?php echo view('\Modules\Setting\Views\top_menu_bar'); ?>
      <div class="card rounded-0 border-0">
         <div class="card-header border-0">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <h5 class="m-0"> Profile </h5>
               </div>
               <div class="col-md-6">
                  <div class="text-md-end">
                     <button data-bs-toggle="modal" data-bs-target="#change_password" class="btn btn-primary btn-sm"><i class="fa fa-lock"></i> Change Password</button>
                     <button class="btn btn-primary btn-sm" view-data-modal="false" data-controller='setting' data-id="<?php echo dev_encode($profile_details['id']); ?>" data-href="<?php echo site_url('setting/edit_user/') . dev_encode($profile_details['id']); ?>">
                     <i class="fa fa-edit"></i> Edit Details </button>
                  </div>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="table-responsive">
                     <table class="table table-bordered">
                        <tbody>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Name</span></th>
                              <td><span class="item-text-value"><?php echo ucfirst($profile_details['first_name']) . ' ' . ucfirst($profile_details['last_name']);  ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head"> Email Id </span></th>
                              <td><span class="item-text-value"><?php echo $profile_details['login_email'];  ?></span></td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Phone</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $profile_details['mobile_no'];  ?></span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="table-responsive">
                     <table class="table table-bordered">
                        <tbody>
                           <tr>
                              <th scope="row"><span class="item-text-head">Address </span></th>
                              <td> <span class="item-text-value"><?php echo $profile_details['address'];  ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Country</span></th>
                              <td> <span class="item-text-value"><?php echo $profile_details['country'];  ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">State</span></th>
                              <td> <span class="item-text-value"><?php echo $profile_details['state'];  ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class="item-text-head">City</span></th>
                              <td> <span class="item-text-value"><?php echo $profile_details['city'];  ?>
                                 </span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class="item-text-head">Pin Code </span></th>
                              <td> <span class="item-text-value"><?php echo $profile_details['pincode'];  ?></span>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Show Detail Lead Modal content -->
<div id="view_setting" class="modal">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content" data-modal-view='view_modal_data'>
      </div>
   </div>
</div>
<div id="change_password" class="modal fade" tabindex="-1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5>Change Passwords</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('setting/change_password'); ?>" method="post" tts-form="true" name="formleadassign">
            <div class="modal-body">
               <div class="row gy-3">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label class="form-label">New Passwords </label>
                        <input class="form-control" type="password" name="change_password" placeholder="Enter new password">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label class="form-label">Confirm Passwords </label>
                        <input class="form-control" type="password" name="confirm_password" placeholder="Enter Confirm password">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Change Passwords content -->