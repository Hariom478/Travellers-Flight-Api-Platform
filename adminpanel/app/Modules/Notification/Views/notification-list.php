<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row">
               <div class="col-md-4">
                  <span>  <?php echo $title . ' '; ?>List</span>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access_error("Setting", "add_notification")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='feedback'
                     data-href="<?php echo site_url('notification/add-notification-template') ?>"><i
                     class="fa-solid fa-add"></i> Add Notification
                  </button>
                  <?php } ?>
                  <?php if (permission_access_error("Setting", "status_notification")) { ?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                     class="fa-solid fa-excahnge"></i> Change Status
                  </button>
                  <?php } ?>
                  <?php if (permission_access_error("Setting", "delete_notification")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formbloglist')"><i class="fa-solid fa-delete"></i> Delete
                  </button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <!----------Start Search Bar ----------------->
            <form action="<?php echo site_url('notification'); ?>" method="GET" class="tts-dis-content" name="feedback-search" onsubmit="return searchvalidateForm()">
               <div class="row mb_10">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'feedback-search')"
                           tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="title" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'title') {
                              echo "selected";
                              } ?>>Title
                           </option>
                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                              } ?>>Date Range
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                        } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           } else {
                           echo "Value";
                           } ?> </label>
                        <input type="text" name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              } ?>"
                           class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                                  echo 'tts-validatation="Required"';
                              } ?> tts-error-msg="Please enter value"/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date"
                           value="<?php if (isset($search_bar_data['from_date'])) {
                              echo $search_bar_data['from_date'];
                              } ?>" placeholder="Select From Date" class="form-control"
                           tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date"
                           value="<?php if (isset($search_bar_data['to_date'])) {
                              echo $search_bar_data['to_date'];
                              } ?>" placeholder="Select To Date" class="form-control"
                           tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                       
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])): ?>
                  <div class="col-md-2">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('notification'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </div>
            </form>

                        <div class="table-responsive">
            <?php $trash_uri = "notification/remove-notification"; ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbloglist">
               <table class="table table-bordered ">
                  <thead>
                     <tr>
                        <?php if (permission_access_error("Setting", "delete_notification") || permission_access_error("Setting", "status_notification")) { ?>
                        <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                        </th>
                        <?php } ?>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Type</th>
                        <!--<th>Description</th>-->
                        <th>Created Date</th>
                        <?php if (permission_access_error("Setting", "edit_notification")) { ?>
                        <th>Action</th>
                        <?php } ?>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $data) {
                                if ($data['status'] == 'active') {
                                    $status_class = 'active-status';
                                } else {
                                    $status_class = 'inactive-status';
                                }
                                ?>
                     <tr>
                        <td>
                           <label><input type="checkbox" name="checklist[]" class="checkbox"
                              value="<?php echo $data['id']; ?>"/></label>
                        </td>
                        <td><?php echo($data['title']); ?></td>
                        <td>
                           <span class="<?php echo $status_class ?>">
                           <?php echo ucfirst($data['status']); ?>
                           </span>
                        </td>
                        <td><?php echo($data['type']); ?></td>
                        <!--                                        <td>--><?php //echo($data['description']); ?><!--</td>-->
                        <td><?php echo date_created_format($data['created']); ?></td>
                        <?php if (permission_access_error("Setting", "edit_notification")) { ?>
                        <td>
                           <a href="javascript:void(0);" view-data-modal="true"
                              data-controller='notification'
                              data-id="<?php echo dev_encode($data['id']); ?>"
                              data-href="<?php echo site_url('/notification/edit-notification-template/') . dev_encode($data['id']); ?>"><i
                              class="fa-solid fa-edit "></i></a>
                        </td>
                        <?php } ?>
                     </tr>
                     <?php }
                        } else {
                            echo "<tr> <td colspan='11' class='text_center'><b>No Data Found</b></td></tr>";
                        } ?>
                  </tbody>
               </table>
            </form>
         </div>
         <div class="row">
               <div class="col-md-6">
                  <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                     of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found 
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager) : ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
         <!----------End Search Bar ----------------->
      </div>
   </div>
</div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('notification/notification-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
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
               <div class="row">
                  <div class="col-md-12">
                     <button class="btn btn-primary" type="submit" value="Save">Save</button>
                  </div>
               </div>
            </div>
         </form>
      </div>
  </div>
</div>