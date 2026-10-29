<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0">Supplier List</h5>
               </div>
               <div class="col-md-8 text-end">

                  <?php if (permission_access("Supplier", "supplier_class_list")) { ?>
                     <button class="badge badge-wt" view-data-modal="true" data-controller='suppliers'
                        data-href="<?php echo site_url('suppliers/supplier-class') ?>">
                        <i class="fa fa-add "></i> Supplier Class </button>
                  <?php } ?>

                  <?php if (permission_access("Supplier", "add_supplier")) { ?>
                     <a href="<?php echo site_url('suppliers/add-supplier-view') ?>" class="badge badge-wt"> <i
                           class="fa fa-add "></i> Add Supplier</a>
                  <?php } ?>

                  <?php if (permission_access("Supplier", "supplier_status")) { ?>
                     <button class="badge badge-wt" onclick="confirm_change_status('status_change')">
                        <i class="fa fa-exchange"></i> Change Status </button>
                  <?php } ?>

                  <?php if (permission_access("Supplier", "delete_supplier")) { ?>
                     <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                        onclick="confirm_delete('formsupplierlist')"><i class="tts-icon delete "></i> Delete
                     </button>
                  <?php } ?>
                  <?php if (permission_access("Supplier", "supplier_export")) { ?>
                     <?php if ($pager->getTotal() != 0): ?>
                        <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"> <i
                              class="fa-solid fa-download"></i> Export </button>
                     <?php endif ?>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <!----------Start Search Bar ----------------->
            <form action="<?php echo site_url('suppliers'); ?>" method="GET" class="tts-dis-content"
               name="web-partner-search" onsubmit="return searchvalidateForm()">
               <div class="row">
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'web-partner-search')"
                           tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="company_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'company_name') {
                              echo "selected";
                           } ?>>Company Name</option>
                           <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                              echo "selected";
                           } ?>>First Name</option>
                           <option value="login_email" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'login_email') {
                              echo "selected";
                           } ?>>Email ID</option>
                           <option value="mobile_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'mobile_number') {
                              echo "selected";
                           } ?>>Mobile No</option>
                           <option value="class_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'class_name') {
                              echo "selected";
                           } ?>>Supplier Class</option>
                             <option value="status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'status') {
                                        echo "selected";
                                    } ?> >Status
                                    </option>
                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                           } ?>>Date Range</option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                     } ?>">
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>
                           <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                              echo $search_bar_data['key-text'] . " *";
                           } else {
                              echo "Value";
                           } ?>
                        </label>
                        <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                           echo $search_bar_data['value'];
                        } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                            echo "disabled";
                         } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                           } else {
                              echo 'tts-validatation="Required"';
                           } ?> tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>From Date</label>
                        <input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                           echo $search_bar_data['from_date'];
                        } ?>" placeholder="Select From Date" class="form-control"
                           tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label>
                        <input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                           echo $search_bar_data['to_date'];
                        } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date"
                           readonly />
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                              class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <? if (isset($search_bar_data['key'])): ?>
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('suppliers'); ?>">Reset Search</a>
                     </div>
                     <? endif ?>
                  </div>
               </div>
            </form>
            <!----------End Search Bar ----------------->
            <?php $trash_uri = "suppliers/remove-supplier"; ?>
            <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formsupplierlist">
               <div class="table-responsive ">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("Supplier", "delete_supplier") || permission_access("Supplier", "supplier_status")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall" /></label></th>
                           <?php } ?>
                           <th>Supplier Id</th>
                           <th>Company Name</th>
                           <th>Class Name</th>
                           <th>Login Email</th>
                           <th>Mobile</th>
                           <th>Balance</th>
                           <th>Region</th>
                           <th>Status</th>
                           <th>Created Date</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if (!empty($supplier_list) && is_array($supplier_list)) {
                           foreach ($supplier_list as $data) {

                              if ($data['status'] == 'active') {
                                 $status_class = 'active-status';
                              } else {
                                 $status_class = 'inactive-status';
                              }

                              ?>
                        <tr>
                           <?php if (permission_access("Supplier", "delete_supplier") || permission_access("Supplier", "supplier_status")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                    value="<?php echo $data['id']; ?>" /></label>
                           </td>
                           <?php } ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true" data-controller='suppliers'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('suppliers/supplier-details/') . dev_encode($data['id']); ?>"><?php echo ucfirst($data['company_id']); ?></a>
                           </td>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true" data-controller='suppliers'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('suppliers/supplier-details/') . dev_encode($data['id']); ?>"><?php echo ucfirst($data['company_name']); ?></a>
                           </td>
                           <td>
                              <?php echo ucfirst($data['class_name']); ?>
                           </td>
                           <td>
                              <?php echo ucfirst($data['login_email']); ?>
                           </td>
                           <td>
                              <?php echo $data['mobile_isd'] . ' ' . $data['mobile_no']; ?>
                           </td>
                           <td><i class="fa fa-inr" aria-hidden="true"></i>
                              <?php echo custom_money_format($data['balance']); ?>
                           </td>
                           <td>
                              <?php echo $data['state']; ?> |
                              <?php echo $data['city']; ?>
                              |
                              <?php echo $data['country']; ?>
                           </td>
                           <td>
                              <div class="<?php echo $status_class ?>">
                                 <?php echo ucfirst($data['status']); ?>
                              </div>
                           </td>
                           <td>
                              <?php
                              if (isset($data['created'])) {
                                 echo date_created_format($data['created']);
                              }
                              ?>
                           </td>
                           <td>
                              <button class="actbtn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                 data-bs-toggle="dropdown" aria-expanded="false">
                                 Action
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                 <?php if (permission_access("Supplier", "edit_supplier")) { ?>
                                 <li><a class="dropdown-item"
                                       href="<?php echo site_url('/suppliers/edit-supplier-view/') . dev_encode($data['id']); ?>">Edit
                                       Supplier</a></li>
                                 <?php } ?>


                                 <?php if (permission_access("Supplier", "virtual_topup")) { ?>
                                 <li> <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                       data-controller='suppliers' data-id="<?php echo dev_encode($data['id']); ?>"
                                       data-href="<?php echo site_url('suppliers/virtual-topup-template/') . dev_encode($data['id']); ?>">Virtual
                                       Top Up </i></a> </li>
                                 <?php } ?>

                                 <?php if (permission_access("Supplier", "virtual_deduct")) { ?>
                                 <li> <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                       data-controller='suppliers' data-id="<?php echo dev_encode($data['id']); ?>"
                                       data-href="<?php echo site_url('suppliers/virtual-debit-template/') . dev_encode($data['id']); ?>">Virtual
                                       Deduct </i></a> </li>
                                 <?php } ?>



                                 <?php if (permission_access("Supplier", "account_logs")) { ?>
                                 <li><a class="dropdown-item"
                                       href="<?php echo site_url('/suppliers/supplier-account-logs/') . dev_encode($data['id']); ?>">Account
                                        Ledger</a></li>
                                 <?php } ?>

                                 <?php if (permission_access("Supplier", "change_password")) { ?>
                                 <li><a class="dropdown-item" href="javascript:void(0);"
                                       onclick='change_password_modal("<?php echo dev_encode($data["id"]); ?>","<?php echo ucfirst($data["company_name"]) ?>")'>Change
                                       Password</a></li>
                                 <?php } ?>

                                 <?php if (permission_access("Supplier", "access_account")) { ?>
                                 <li><a class="dropdown-item"
                                       href="<?php echo root_url . 'supplier/access-account/' . dev_encode($data['login_email'] . '-' . $data['id'] . '-' . $UserIp); ?>"
                                       target="_blank">Emulate</a></li>
                                 <?php } ?>

                              </ul>
                           </td>
                        </tr>
                        <?php }
                        } else {
                           echo "<tr> <td colspan='12' class='text-center'><b>No Supplier Found</b></td></tr>";
                        } ?>
                     </tbody>
                  </table>
               </div>
            </form>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page
                     <?= $pager->getCurrentPage() ?>
                     of
                     <?= $pager->getPageCount() ?>, total
                     <?= $pager->getTotal() ?> records found
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager): ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('suppliers/supplier-status-change'); ?>" method="post" tts-form="true"
            name="form_change_status">
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
               <button class="btn btn-primary" type="submit" value="Save">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- status password change  change content -->
<div id="password_change" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('suppliers/change-password'); ?>" method="post" tts-form="true"
            name="form_password_change">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group form-mb-20">
                        <strong>
                           <p>Company Name :
                        </strong>
                        <span class="company_name tts_agent_company"></span>
                     </div>
                     <div class="form-group form-mb-20">
                        <label>New Password*</label>
                        <input class="form-control" type="text" name="password" placeholder="Password">

                        <button class="mt8 badge badge-wt badge-primary mt-1" type="button"
                           onclick=generatePassword(10,'form_password_change');>Generate Password</button>
                     </div>
                     <input type="hidden" name="supplier_id" class="tts_agent_id">
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="send" name="send_email"
                           id="supplier-send-email">
                        <label class="form-check-label" for="supplier-send-email">
                           Send account details on supplier email id
                        </label>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit" value="Save">Change Password</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="tts_export_modal" class="modal fade" tabindex="1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Export Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('suppliers/export-supplier'); ?>" method="post" tts-form="true">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>From Date *</label>
                        <input class="form-control" type="text" name="from_date" data-export-from="true"
                           placeholder="Select From Date" readonly>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>To Date *</label>
                        <input class="form-control borl0" type="text" name="to_date" data-export-to="true"
                           placeholder="Select To Date" readonly>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Export</button>
            </div>
         </form>
      </div>
   </div>
</div>