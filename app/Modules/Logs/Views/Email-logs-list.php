<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span>Email Logs</span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <?php if (permission_access("Logs", "delete_email_log")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formsmslogslist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="query-followup">
                <ul class="lm_navigation">
                    <?php if (permission_access("Logs", "sms_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "sms_logs"); ?>">
                            <a href="<?php echo site_url("logs"); ?>"> <span>SMS Logs</span> </a>
                        </li>
                    <?php }?>
                    <?php if (permission_access("Logs", "email_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "email_logs"); ?>">
                            <a href="<?php echo site_url("logs/email-logs"); ?>"> <span>Email Logs</span> </a>
                        </li>
                    <?php }?>
                    <?php if (permission_access("Logs", "login_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "login_logs"); ?>">
                            <a href="<?php echo site_url("logs/login-logs"); ?>">
                                <span>Login Logs</span>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tts_row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('logs/email-logs'); ?>" method="GET" class="tts-dis-content" name="email-logs-search" onsubmit="return searchvalidateForm()">
                        <div class="tts-col-3">
                            <div class="form-group">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-control" onchange="tts_searchkey(this,'email-logs-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="to_email" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='to_email'){ echo "selected";} ?>>Email Id</option>
                                    <option value="status" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='status'){ echo "selected";} ?>  >Status</option>
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
                                    <a href="<?php echo site_url('logs/email-logs');?>">Reset Search</a>
                                </div>
                            </div>
                        <? endif ?>
                    </form>
                </div>

                <!----------End Search Bar ----------------->
                <div class="responcive_table table_box_shadow">
                    <?php
                    $trash_uri = "logs/remove-email-logs";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formsmslogslist">
                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <?php if (permission_access("Logs", "delete_email_log")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>
                                <th>From Email</th>
                                <th>To Email</th>
                                <th>Email Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <?php if (permission_access("Logs", "resend_email")) { ?>
                                <th>Action</th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($email_logs) && is_array($email_logs)) {
                                foreach ($email_logs as $data) {
                                    if ($data['status'] == 'success') {
                                        $status_class = 'active-status';
                                    } else {
                                        $status_class = 'inactive-status';
                                    }
                                    ?>
                                    <tr>
                                        <?php if (permission_access("Logs", "delete_email_log")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php }?>
                                        <td>
                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true"
                                               data-controller='logs' data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('logs/email-log-details/') . dev_encode($data['id']); ?>"><?php echo $data['from_email']; ?></a>

                                        </td>
                                        <td>
                                            <?php echo $data['to_email']; ?>
                                        </td>
                                        <td><?php echo $data['email_type']; ?></td>
                                        <td>
                                            <span class="<?php echo $status_class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            if (isset($data['created'])) {
                                                echo date_created_format($data['created']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <?php if (permission_access("Logs", "resend_email")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" class="txt_led_clr"
                                               data-controller='logs'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('logs/resend-email/') . dev_encode($data['id']); ?>"
                                               onclick="return confirm('Do you want to Resend SMS!',this)">Resend</a>
                                        </td>
                                        <?php }?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Email Logs Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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

<!-- Show Detail Lead Modal content -->
<div id="view_logs" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>