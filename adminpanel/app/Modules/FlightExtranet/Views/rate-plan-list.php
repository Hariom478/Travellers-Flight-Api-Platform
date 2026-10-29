
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0">Search Rate Plan</h5>
               </div>
               <div class="col-md-8 text-md-right">
                  <button class="badge badge-wt" view-data-modal="true" data-controller='private-fare'
                     data-href="<?php echo site_url('private-fare/add-rate-plan-template') ?>"><i
                     class="fa-solid fa-add"></i> Add Rate Plan
                  </button>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('remove-rate-plan')"><i class="fa-solid fa-trash"></i> Delete
                  </button>
               </div>
            </div>
         </div>
      <!-- <ol class="breadcrumb-arrow">
         <li><a href="<?php site_url('webpartner') ?>"><i class="fa fa-home"></i></a></li>
         
         <li><span>rate-plan</span></li>
         
         </ol> -->
      <div class="body_manage dash-padding dashboard__main__content">
         <div class="row">
           
            <form action="<?php echo site_url('private-fare/rate-plan'); ?>" method="GET"
               class="col-md-12"
               name="web-partner-search" onsubmit="return searchvalidateForm()">
               <div class="row ">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-control "
                           onchange="tts_searchkey(this,'web-partner-search')"
                           tts-validatation="Required"
                           tts-error-msg="Please select search key">
                           <option value="">Select key to search by *</option>
                           <option value="plan_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'plan_name') {
                              echo "selected";
                              
                              } ?>>Plan Name
                           </option>
                           <option value="status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'status') {
                              echo "selected";
                              
                              } ?>>Status
                           </option>
                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                              
                              } ?>>Date Range
                           </option>
                        </select>
                        <input type="hidden" name="key-text"
                           value="<?php if (isset($search_bar_data['key-text'])) {
                              echo trim($search_bar_data['key-text']);
                              
                              } ?>">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label for="floatingDynamicId"><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           
                           } else {
                           
                           echo "Value";
                           
                           } ?></label>
                        <input type="text" class="form-control "
                           id="floatingDynamicId" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                              
                                  echo 'tts-validatation="Required"';
                              
                              } ?> tts-error-msg="Please enter value"
                           name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              
                              } ?>">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label for="from-date">From Date</label>
                        <input type="text" class="form-control" id="from-date" data-searchbar-from="true"
                           name="from_date"
                           placeholder="Select From Date"
                           value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              
                              } ?>" tts-error-msg="Please select from date" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label for="to-date">From Date</label>
                        <input type="text" data-searchbar-to="true" name="to_date" id="to-date"
                           value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              
                              } ?>" placeholder="Select From Date" class="form-control"
                           tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <? if (isset($search_bar_data['key'])) : ?>
                  <a href="<?php echo site_url('private-fare/rate-plan'); ?>" class="btn sign_btn">Reset
                  Search</a>
                  <? endif ?>
                  </div>
               </div>
               
            </form>
            <div class="col-md-12 mt-3">
                                 <?php $trash_uri = "private-fare/remove-rate-plan"; ?>
                  <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                     id="remove-rate-plan">
                     <div class=" table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active ">
                           <tr>
                              <th><label class="m0"><input type="checkbox" name="check_all" id="selectall"/></label></th>
                              <th>Created On <i class="fa fa-sort"></i></th>
                              <th>name <i class="fa fa-sort"></i></th>
                              <th>Cabin Class <i class="fa fa-sort"></i></th>
                              <th>Booking Class</th>
                              <th>Base Fare</th>
                              <th>Tax Rate</th>
                              <!-- <th>Gst</th> -->
                              <th>Update</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if (!empty($list) && is_array($list)) {
                              
                              
                              
                                  foreach ($list as $data) {
                              
                              
                              
                              
                              
                                      ?>
                           <tr>
                              <td>
                                 <label><input type="checkbox" name="checklist[]" class="checkbox"
                                    value="<?php echo $data['id']; ?>"/></label>
                              </td>
                              <td title="<?php echo date_created_format($data['created']); ?>"
                                 class="ellipses"><?php echo date_created_format($data['created']); ?></td>
                              <td title="<?php echo $data['plan_name']; ?>"
                                 class="ellipses"> <?php echo $data['plan_name']; ?>
                              </td>
                              <td title="<?php echo $data['cabin_class']; ?>"
                                 class="no-ellipses"> <?php echo $data['cabin_class']; ?></td>
                              <td title="<?php echo $data['booking_class']; ?>"
                                 class="ellipses"><?php echo $data['booking_class']; ?></td>
                              <td title="ADULT - <?php echo custom_money_format($data['adult_base_fare']); ?>, CHILD - <?php echo custom_money_format($data['child_base_fare']); ?>, INFANT - <?php echo custom_money_format($data['infant_base_fare']); ?>"
                                 class="ellipses">ADULT -
                                 <?php echo custom_money_format($data['adult_base_fare']); ?>, CHILD
                                 - <?php echo custom_money_format($data['child_base_fare']); ?>, INFANT
                                 - <?php echo custom_money_format($data['infant_base_fare']); ?>
                              </td>
                              <td title="ADULT - <?php echo custom_money_format($data['adult_tax']); ?>, CHILD - <?php echo custom_money_format($data['child_tax']); ?>, INFANT - <?php echo custom_money_format($data['infant_tax']); ?>"
                                 class="ellipses">ADULT -
                                 <?php echo custom_money_format($data['adult_tax']); ?>, CHILD
                                 - <?php echo custom_money_format($data['child_tax']); ?>, INFANT
                                 - <?php echo custom_money_format($data['infant_tax']); ?>
                              </td>
                              <!-- <td title="ADULT - <?php echo $data['adult_gst']; ?>, CHILD - <?php echo $data['child_gst']; ?>, INFANT - <?php echo $data['infant_gst']; ?>"
                                 class="ellipses">ADULT -
                                 
                                 <?php echo $data['adult_gst']; ?>, CHILD
                                 
                                 - <?php echo $data['child_gst']; ?>, INFANT
                                 
                                 - <?php echo $data['infant_gst']; ?>
                                 
                                 </td> -->
                              <td><a href="javascript:void(0);" view-data-modal="true"
                                 data-controller='private-fare'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('private-fare/edit-rate-plan-template/') . dev_encode($data['id']); ?>"><i
                                 class="fa fa-edit linkcontainer__icon"></i>
                                 <span class="link_container-link"></span></a>
                              </td>
                           </tr>
                           <?php }
                              } ?>
                        </tbody>
                     </table>
                  </div>
                  </form>
            </div>
            <div class="col-md-12">
         <div class="row pagiantion_row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($pager) : ?>
                            <?= $pager->links() ?>
                        <?php endif ?>
                    </div>
                </div>
      </div>
         </div>
      </div>
      
<!-------modal--->