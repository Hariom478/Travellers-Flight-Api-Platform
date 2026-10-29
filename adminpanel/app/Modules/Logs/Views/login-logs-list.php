<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-2">
                        <span>Login Logs</span>
                    </div>
                    <div class="col-md-10 text-end">
                        <?php if (permission_access("Logs", "delete_login_log")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formaloginlogslist')"><i class="fa-solid fa-trash "></i> Delete
                        </button>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="query-followup">
                <ul class="lm_navigation">
                <?php if (permission_access("Logs", "login_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "login_logs"); ?>">
                            <a href="<?php echo site_url("logs/login-logs"); ?>">
                                <span>Login Logs</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(0){ ?>
                    <?php if (permission_access("Logs", "sms_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "sms_logs"); ?>">
                            <a href="<?php echo site_url("logs"); ?>"> <span>SMS Logs</span> </a>
                        </li>
                    <?php } ?>
                    <?php } ?>
                    <?php if (permission_access("Logs", "email_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "email_logs"); ?>">
                            <a href="<?php echo site_url("logs/email-logs"); ?>"> <span>Email Logs</span> </a>
                        </li>
                    <?php } ?>
                   


                  
                </ul>
            </div>

            <div class="card-body">
            <div class="table-responsive table_box_shadow">
                    <?php
                    $trash_uri = "logs/remove-login-logs";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formaloginlogslist">
                          <table class="table table-bordered table-hover">
                          <thead class="table-active">
                            <tr>
                                <?php //if (permission_access("Logs", "delete_login_log")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php //} ?>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Login Browser</th>
                                <th>Platform</th>
                                <th>IP Address</th>
                                <th>Login Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($login_logs) && is_array($login_logs)) {
                                foreach ($login_logs as $data) { ?>
                                    <tr>

                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>

                                        <td>
                                            <?php echo $data['user_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo ($data['role'] != 'WebPartner')?$data['role']:'Admin'; ?>
                                        </td>
                                        <td><?php echo $data['login_browser']; ?></td>
                                        <td>
                                            <?php echo $data['platform']; ?>
                                        </td>

                                        <td>
                                            <?php echo $data['login_ip_address']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (isset($data['login_time'])) {
                                                echo date_created_format($data['login_time']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Login Logs Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form> 
                </div>
                <div class="row pagiantion_row align-items-center">
                            <div class="col-md-6">
                                <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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

<!-- Show Detail Lead Modal content -->
<div id="view_logs" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>