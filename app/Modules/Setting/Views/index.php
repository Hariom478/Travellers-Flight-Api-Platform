<section class="dashboard-section d-flex align-items-center ">
   <div class="container">
      <?php echo view('\Modules\Setting\Views\top_menu_bar'); ?>
      <div class="card rounded-0 border-0">
         <div class="card-header border-0">
            <div class="row align-items-center">
               <div class="col-md-6">
                  <h5 class="m-0">Company Settings</h5>
               </div>
               <div class="col-md-6">
                  <div class="text-md-end">
                     <button data-bs-toggle="modal" data-bs-target="#edit_settings" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit </button>
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
                              <th scope="row"><span class=" item-text-head">Company Name</span></th>
                              <td>
                                 <span class="item-text-value"> <?php echo $company_details['company_name']; ?> </span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Address</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['address']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Country</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['country']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">State</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['state']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">City</span></th>
                              <td><span class="item-text-value"><?php echo $company_details['city']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class="item-text-head">Zip</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['pincode']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">GST</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['gst_number']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">name</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['first_name']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Pan Card number</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['pan_number']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Support No</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['support_no']; ?></span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Support Email</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['support_email']; ?></span>
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
                              <th scope="row"><span class=" item-text-head">Name</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['first_name'] . ' ' . $company_details['last_name']; ?> </span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Email</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['login_email'] ?> </span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Mobile</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['mobile_no'] ?> </span>
                              </td>
                           </tr>
                           <tr>
                              <th scope="row"><span class=" item-text-head">Name</span></th>
                              <td>
                                 <span class="item-text-value"><?php echo $company_details['first_name'] . ' ' . $company_details['last_name']; ?> </span>
                              </td>
                           </tr>
                           <?php if ($company_details['company_logo']) { ?>
                              <tr>
                                 <th scope="row"><span class=" item-text-head">Logo</span></th>
                                 <td><span class="item-text-value"><a href="<?php echo root_url . "uploads/agent/logo/" . $company_details['company_logo']; ?>" target="_blank">Logo</a> </span></td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>


   </div>
   <!-- Add Edit Settings Modal content -->
   <?php $country_codes = get_countary_code();
   ?>
   <div id="edit_settings" class="modal fade">
      <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content">
            <form name="formlead" action="<?php echo site_url('setting/update_company_setting'); ?>" method="POST" tts-form='true'>
               <div class="modal-header">
                  <h5 class="modal-title">Edit settings</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <div class="row gy-3">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Logo</label>
                           <input class="form-control" type="file" name="company_logo" placeholder="Logo">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label> Street / Address</label>
                           <input class="form-control" type="text" value="<?php echo $company_details['address']; ?>" name="address" placeholder="Street">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Country</label>
                           <select class="form-control" name="country">
                              <?php if ($country_codes) {
                                 foreach ($country_codes as $country_code) { ?>
                                    <option value="<?php echo $country_code['countryname']; ?>" <?php
                                                                                                if ($country_code['countryname'] == $company_details['country']) {
                                                                                                   echo "Selected";
                                                                                                } ?>>
                                       <?php echo $country_code['countryname']; ?>
                                    </option>
                              <?php }
                              } ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>State</label>
                           <input class="form-control" type="text" value="<?php echo $company_details['state']; ?>" name="state" placeholder=" State">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>City</label>
                           <input class="form-control" type="text" value="<?php echo $company_details['city']; ?>" name="city" placeholder="City">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label>Zip Code</label>
                           <input class="form-control" type="text" value="<?php echo $company_details['pincode']; ?>" name="pincode" placeholder="Zip Code">
                        </div>
                     </div>
                     <div class="col-md-12">
                        <h6 class="view_head">Contact Details</h6>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group">
                           <label>Support No</label>
                           <input class="form-control" type="text" name="support_no" placeholder="Support No" value="<?php echo $company_details['support_no']; ?>">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group">
                           <label>Support Email</label>
                           <input class="form-control" type="text" name="support_email" placeholder="Support Email" value="<?php echo $company_details['support_email']; ?>">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button class="btn btn-outline-primary" type="submit">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>