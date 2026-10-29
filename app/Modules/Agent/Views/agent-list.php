<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Agent List</span>
                    </div>
                    <div class="tts-col-10 text_right">

                        <?php if (permission_access("Agent", "agent_class_list")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='agent' data-href="<?php echo site_url('agent/agent-class') ?>"><i  class="tts-icon add "></i> Agent Class
                        </button>
                        <?php }?>

                        <?php if (permission_access("Agent", "add_agent")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='agent'
                                data-href="<?php echo site_url('agent/add-agent-template') ?>"><i
                                    class="tts-icon add "></i> Add Agent
                        </button>
                        <?php }?>
                        <?php if (permission_access("Agent", "agent_status")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i class="tts-icon swap"></i> Change Status
                        </button>
                        <?php }?>
                       <?php if (permission_access("Agent", "agent_export")) { ?>
                        <?php if ($pager->getTotal()!=0) : ?>
                            <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"> <i class="tts-icon export"></i> Export </button>
                        <?php endif ?>
                        <?php }?>

                        <?php if (permission_access("Agent", "delete_agent")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formagentlist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
                <div class="tts_row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('agent'); ?>" method="GET" class="tts-dis-content" name="agent-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'agent-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="first_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='first_name'){ echo "selected";} ?>>First Name</option>
                                    <option value="email_id" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='email_id'){ echo "selected";} ?>  >Email ID</option>
                                    <option value="mobile_number" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='mobile_number'){ echo "selected";} ?>  >Mobile No</option>
                                    <option value="class_name" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='class_name'){ echo "selected";} ?>  >Agent Class</option>
                                    <option value="date-range" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range'){ echo "selected";} ?>>Date Range</option>
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                                <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="form-group">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                            </div>
                        </div>
                        <div class="tts-col-1">
                            <div class="form-group">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary">Search</button>
                            </div>
                        </div>
                        <? if(isset($search_bar_data['key'])): ?>
                            <div class="tts-col-1">
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('agent');?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->
                <div class="responcive_table table_box_shadow">
                    <?php $trash_uri = "agent/remove-agent"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formagentlist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Agent", "delete_agent") || permission_access("Agent", "agent_status")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>Company</th>
                                <th>Agent Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Agent Class</th>
                                <th>Credit Limit</th>
                                <th>Avl Balance</th>
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
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>


                                        <td>
                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                                               data-controller='agent' data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['id']); ?>"><?php echo ucfirst($data['company_name']); ?></a>
                                        </td>

                                        <td>
                                            <?php
                                            $title = ucfirst($data['title']);
                                            $name = ucfirst($data['first_name'] . ' ' . $data['last_name']);

                                            echo $title . ' ' . $name;
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $data['email_id']; ?>
                                        </td>
                                        <td><?php echo $data['mobile_number']; ?></td>
                                        <td><?php echo ucfirst($data['class_name']); ?></td>
                                        <td><?php echo $data['credit_limit']; ?></td>
                                        <td><?php echo $data['balance']; ?></td>
                                        <td>
                                            <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actdropdown">
                                                <button class="actbtn" type="button">Action</button>
                                                <div class="actdropdcont">
                                                    <?php if (permission_access("Agent", "edit_agent")) { ?>
                                                    <a href="javascript:void(0);" view-data-modal="true"
                                                       data-controller='agent'
                                                       data-id="<?php echo dev_encode($data['id']); ?>"
                                                       data-href="<?php echo site_url('/agent/edit-agent-template/') . dev_encode($data['id']); ?>">Edit
                                                        Agent</i>
                                                    </a>
                                                    <?php }?>
                                                    <?php if (permission_access("Agent", "virtual_topup")) { ?>
                                                    <a href="javascript:void(0);" view-data-modal="true"
                                                       data-controller='agent'
                                                       data-id="<?php echo dev_encode($data['id']); ?>"
                                                       data-href="<?php echo site_url('/agent/virtual-topup-template/') . dev_encode($data['id']); ?>">Virtual Top up Credit
                                                        </i>
                                                    </a>
                                                    <?php }?>
                                                    <?php if (permission_access("Agent", "virtual_deduct")) { ?>
                                                    <a href="javascript:void(0);" view-data-modal="true"
                                                       data-controller='agent'
                                                       data-id="<?php echo dev_encode($data['id']); ?>"
                                                       data-href="<?php echo site_url('/agent/virtual-debit-template/') . dev_encode($data['id']); ?>">Virtual Deduct Credit
                                                        </i>
                                                    </a>
                                                    <?php }?>
                                                    <?php if (permission_access("Agent", "account_logs")) { ?>
                                                    <a href="<?php echo site_url('/agent/agent-account-logs/') . dev_encode($data['id']); ?>">Account Logs</a>
                                                    <?php }?>
                                                    <?php if (permission_access("Agent", "change_password")) { ?>
                                                    <a href="javascript:void(0);"
                                                       onclick=change_password_modal("<?php echo dev_encode($data['id']) ?>","<?php echo ucfirst($data['company_name']) ?>")>Change
                                                        Password</a>
                                                    <?php }?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Agent Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="tts-col-6">
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
    </div>
</div>


<!-- status status change content -->
<div id="status_change" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('agent/agent-status-change'); ?>" method="post" tts-form="true"
              name="form_change_status">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel('status_change')">&times;</span>
                <h5>Change Status</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-12">
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
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Show  status Modal content -->


<!-- status password change  change content -->
<div id="password_change" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('agent/change-agent-password'); ?>" method="post" tts-form="true"
              name="form_password_change">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel('password_change')">&times;</span>
                <h5>Change Password</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <div class="form-group">
                            <p>Company Name : <strong class="company_name tts_agent_company"></strong></p>
                        </div>
                        <div class="form-group">
                            <label>New Password*</label>
                            <input class="form-control" type="password" name="password" placeholder="Password">
                            <button class="mt8 badge badge-md badge-primary" type="button" onclick="generatePassword(10,'form_password_change');">Generate Password</button>
                        </div>
                        <input type="hidden" name="agent_id" class="tts_agent_id">
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="send_email" value="send">Send password on agent register email id</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Change Password</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Show  password change Modal content -->



<!-- start export modal content -->
<div id="tts_export_modal" class="modal">
    <div class="top-model-content">
        <form action="<?php echo site_url('agent/export-agent'); ?>" method="post" tts-form="true">
            <div class="modal-header">
                <span class="close" onclick="ttsclosemodel('tts_export_modal')">&times;</span>
                <h5>Export Data</h5>
            </div>
            <div class="modal-body">
                <div class="tts_row">
                    <div class="tts-col-6 pr0">
                        <div class="form-group">
                            <label>From Date *</label>
                            <input class="form-control" type="text" name="from_date" data-export-from="true" placeholder="Select From Date" readonly>
                        </div>
                    </div>
                    <div class="tts-col-6 pl0">
                        <div class="form-group">
                            <label>To Date *</label>
                            <input class="form-control borl0" type="text" name="to_date" data-export-to="true" placeholder="Select To Date" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-model-footer">
                <div class="tts_row">
                    <div class="tts-col-12">
                        <button class="badge badge-md badge-primary" type="submit" value="Save">Export</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- end export modal content -->


