<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Agent List</h5>
               </div>
               <div class="col-md-8 text-md-end">
                  <?php if (permission_access("Agent", "agent_class_list")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='agent' data-href="<?php echo site_url('agent/agent-class') ?>">
                  <i  class="fa-solid fa-add "></i> Agent Class
                  </button>
                  <?php } ?>
                  <?php if (permission_access("Agent", "add_agent")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='agent'
                     data-href="<?php echo site_url('agent/add-agent-template') ?>"><i
                     class="fa-solid fa-add "></i> Add Agent
                  </button>
                  <?php } ?>
                  <?php if (permission_access("Agent", "agent_status")) { ?>
                  <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="fa-solid fa-exchange"></i> Change Status
                  </button>
                  <?php } ?>
                  <?php if (permission_access("Agent", "agent_export")) { ?>
                  <?php if ($pager->getTotal()!=0) : ?>
                  <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"> <i class="fa-solid fa-download"></i> Export </button>
                  <?php endif ?>
                  <?php } ?>
                  <?php if (permission_access("Agent", "delete_agent")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formagentlist')"><i class="fa-solid fa-trash"></i> Delete
                  </button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <!----------Start Search Bar ----------------->
               <form class="row" action="<?php echo site_url('agent'); ?>" method="GET" class="tts-dis-content" name="agent-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label class="form-label">Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'agent-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="company_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='company_name'){ echo "selected";} ?>>Company Name</option>
                           <option value="first_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='first_name'){ echo "selected";} ?>>First Name</option>
                           <option value="login_email" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='login_email'){ echo "selected";} ?>  >Email ID</option>
                           <option value="mobile_number" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='mobile_number'){ echo "selected";} ?>  >Mobile No</option>
                           <option value="class_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='class_name'){ echo "selected";} ?>  >Agent Class</option>
                           <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label class="form-label"><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                        <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group">
                        <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group">
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa-solid fa-search"></i></button>
                     </div>
                  </div>
                  <? if(isset($search_bar_data['key'])): ?>
                  <div class="col-md-2">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('agent');?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php $trash_uri = "agent/remove-agent";?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
               id="formagentlist">
               <table class="table table-bordered table-hover">
                  <thead class="table-active">
                     <tr>
                        <?php if (permission_access("Agent", "delete_agent") || permission_access("Agent", "agent_status")) { ?>
                         <th><label><input type="checkbox" name="check_all" id="selectall"/></label></th>
                        <?php } ?>
                        <th>Agent ID</th>
                        <th>Company Name</th>
                        <th>Agent Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Agent Class</th>
                        <th>Balance</th>
                        <th>Credit Limit</th>
                        <th>Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                    
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $data) {
                                if ($data['status'] == 'active') {
                                    $class = 'active-status';
                                } else {
                                    $class = 'inactive-status';
                                }
                                ?>
                     <tr>
                        <?php if (permission_access("Agent", "delete_agent") || permission_access("Agent", "agent_status")) { ?>
                        <td>
                           <label><input type="checkbox" name="checklist[]" class="checkbox"
                              value="<?php echo $data['agent_id']; ?>"/></label>
                        </td>
                        <?php } ?>

                        <td>
                        <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                              data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>"
                              data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['agent_id']); ?>"> <?php echo $data['company_id']; ?></a>
                        </td>
                        <td>
                           <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                              data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>"
                              data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['agent_id']); ?>"><?php echo ucfirst($data['company_name']); ?></a>
                        </td>
                        <td>
                           <?php
                              $title = ucfirst($data['title']);
                              $name = ucfirst($data['first_name'] . ' ' . $data['last_name']);
                              echo $title . ' ' . $name;
                              ?>
                        </td>
                        <td>
                           <?php echo $data['login_email']; ?>
                        </td>
                        <td><?php echo $data['mobile_no']; ?></td>
                        <td><?php echo ucfirst($data['class_name']); ?></td>
                        <td><?php if($data['balance']) { echo custom_money_format($data['balance']); } else { echo "0"; } ?></td>
                        <td><?php if($data['credit_limit']) { echo custom_money_format($data['credit_limit']); } else { echo "N/A"; } ?></td>
                        <td>
                           <span class="<?php echo $class ?>">
                           <?php echo ucfirst($data['status']); ?>
                           </span>
                        </td>
                        <td>
                           <button class="actbtn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
                           <div class="dropdown-menu">
                              <?php if (permission_access("Agent", "edit_agent")) { ?>
                                 <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                    data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>"  data-href="<?php echo site_url('/agent/edit-agent-template/') . dev_encode($data['agent_id']); ?>">Edit  Agent</i>
                                 </a>
                              <?php } ?>
                              <?php if (permission_access("Agent", "virtual_topup")) { ?>
                                 <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true"
                                    data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>" data-href="<?php echo site_url('/agent/virtual-topup-template/') . dev_encode($data['agent_id']); ?>">Virtual Top Up </i>
                                 </a>
                              <?php } ?>
                              <?php if (permission_access("Agent", "virtual_deduct")) { ?>
                                  <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>"data-href="<?php echo site_url('/agent/virtual-debit-template/') . dev_encode($data['agent_id']); ?>">Virtual Deduct </i></a>
                              <?php } ?>

                              <?php if (permission_access("Agent", "account_logs")) { ?>
                                  <a class="dropdown-item" href="<?php echo site_url('/agent/agent-account-logs/') . dev_encode($data['agent_id']); ?>">Account Logs</a>
                              <?php } ?>

                              <?php if (permission_access("Agent", "credit_limit")) { ?>
                                 <a class="dropdown-item" href="javascript:void(0);" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['agent_id']); ?>"data-href="<?php echo site_url('/agent/credit-limit-template/') . dev_encode($data['agent_id']); ?>">Credit Limit </i></a>
                              <?php } ?>

                              <?php if (permission_access("Agent", "credit_account_logs")) { ?>
                                 <a class="dropdown-item" href="<?php echo site_url('/agent/agent-account-credit-logs/') . dev_encode($data['agent_id']); ?>">Credit Account Logs </i></a>
                              <?php } ?>
                              
                              <?php if (permission_access("Agent", "change_password")) { ?>
                              <a class="dropdown-item" href="javascript:void(0);"
                                 onclick=change_password_modal('<?php echo dev_encode($data['agent_id']) ?>','<?php echo ucfirst(str_replace(" ","-",$data['company_name'])) ?>')>Change
                              Password</a>
                              <?php } ?>

                              <?php if (permission_access("Agent", "access_account")) { ?>                              
                                 <a class="dropdown-item" href="<?php echo root_url . '/access-account/' . dev_encode($data['login_email'] . '-' . $data['agent_id'] . '-' . $data['web_partner_id'] . '-' . $UserIp); ?>" target="_blank">Access Account</a>
                              <?php } ?>

                           </div>
                        </td>
                     </tr>
                     <?php }
                        } else {
                            echo "<tr> <td colspan='11' class='text-center'><b>No Agent Found</b></td></tr>";
                        } ?>
                  </tbody>
               </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
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
      </div>
   </div>
</div>
</div>
<!-- status status change content -->
<div id="status_change" class="modal fade" tabindex="1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('agent/agent-status-change'); ?>" method="post" tts-form="true" name="form_change_status">
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
               <button class="btn btn-primary" type="submit">Save</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Show  status Modal content -->
<!-- status password change  change content -->
<div id="password_change" class="modal fade" tabindex="1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('agent/change-agent-password'); ?>" method="post" tts-form="true" name="form_password_change">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <p>Company Name : <strong class="company_name tts_agent_company"></strong></p>
                     </div>
                     <div class="form-group">
                        <label>New Password*</label>
                        <input class="form-control" type="text" name="password" placeholder="Password">
                        <button class="mt8 badge badge-wt badge-primary mt-1" type="button" onclick=generatePassword(10,'form_password_change');>Generate Password</button>
                     </div>
                     <input type="hidden" name="agent_id" class="tts_agent_id">
                     <div class="form-check">
                           <input class="form-check-input" type="checkbox" value="send" name="send_email" id="agent-send-email">
                           <label class="form-check-label" for="agent-send-email">
                           Send account details on agent email id
                           </label>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Change Password</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- Show  password change Modal content -->
<!-- start export modal content -->
<div id="tts_export_modal" class="modal fade" tabindex="1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Export Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('agent/export-agent'); ?>" method="post" tts-form="true">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>From Date *</label>
                        <input class="form-control" type="text" name="from_date" data-export-from="true" placeholder="Select From Date" readonly>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>To Date *</label>
                        <input class="form-control borl0" type="text" name="to_date" data-export-to="true" placeholder="Select To Date" readonly>
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
<!-- end export modal content -->