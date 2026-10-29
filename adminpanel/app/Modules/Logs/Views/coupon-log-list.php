<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0">Coupon Log</h5>
                    </div>
                    <div class="col-md-8 text-end">
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">

            <div class="query-followup">
                <ul class="lm_navigation">
                    <?php if (permission_access("Logs", "sms_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "sms_logs"); ?>">
                            <a href="<?php echo site_url("logs"); ?>"> <span>SMS Logs</span> </a>
                        </li>
                    <?php } ?>
                    <?php if (permission_access("Logs", "email_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "email_logs"); ?>">
                            <a href="<?php echo site_url("logs/email-logs"); ?>"> <span>Email Logs</span> </a>
                        </li>
                    <?php } ?>
                    <?php if (permission_access("Logs", "login_logs_list")) { ?>
                        <li class="lm_navLst <?php echo active_list_mod("Logs", "login_logs"); ?>">
                            <a href="<?php echo site_url("logs/login-logs"); ?>">
                                <span>Login Logs</span>
                            </a>
                        </li>
                    <?php } ?> 
                    
                </ul>
            </div>
                <div class="row mb_10">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('coupon/coupon-log'); ?>" method="GET" class="tts-dis-content" name="discount-search" onsubmit="return searchvalidateForm()">
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'discount-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="token" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'token') {
                                                                        echo "selected";
                                                                    } ?>>Token</option>
                                    <option value="use_for" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'use_for') {
                                                                        echo "selected";
                                                                    } ?>>Service</option>

                                    <option value="coupon_code" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'coupon_code') {
                                                                    echo "selected";
                                                                } ?>>Coupon Code</option>

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
                                <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                                            echo $search_bar_data['key-text'] . " *";
                                        } else {
                                            echo "Value";
                                        } ?> </label>
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
                                <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                    echo $search_bar_data['from_date'];
                                                                                                                                } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                        } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group form-mb-20">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <? if (isset($search_bar_data['key'])) : ?>

                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('coupon/coupon-log'); ?>">Reset Search</a>
                                </div>

                            <? endif ?>
                        </div>
                        </div>
                    </form>
                </div>

                <!----------End Search Bar ----------------->


                <?php
                $trash_uri = "#";
                ?>
                <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formdiscountlist">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <!--                                --><?php /*if (permission_access("Flight", "flight_discount_b2c_status") || permission_access("Flight", "delete_flight_discount_b2c")) { */ ?>

                                    <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                                    </th>
                                    <?php /*}*/ ?>

                                    <th>Token</th>
                                    <th>Service</th>
                                    <th>Coupon Code</th>
                                    <th>Coupon Info</th>
                                    <?php /*if (permission_access("Flight", "edit_flight_discount_b2c")) { */ ?>
                                    <th>Created</th>
                                    <!-- --><?php /*}*/ ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($list) && is_array($list)) {

                                    foreach ($list as $data) {
                                ?>
                                        <tr>
                                            <?php /*if (permission_access("Flight", "flight_discount_b2c_status") || permission_access("Flight", "delete_flight_discount_b2c")) { */ ?><!--
                                        -->
                                            <td>
                                                <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                            </td>
                                            <?php /*}*/ ?>
                                            <td><?php echo ucfirst($data['token']); ?></td>
                                            <td><?php echo ucfirst($data['use_for']); ?></td>
                                            <td>
                                               <?php echo ucfirst($data['coupon_code']); ?>
                                            </td>
                                            <td>
                                                <?php $couponInfo = json_decode($data['couponInfo'],true); ?>
                                                <p><b>Coupon Type:-</b> <?=$couponInfo['coupon_type']?></p>
                                                <p><b>Coupon Value:-</b><?=$couponInfo['value']?></p>
                                                <p><b>Max Limit:-</b><?=$couponInfo['max_limit']?></p>
                                            </td>
                                            <!-- --><?php /*if (permission_access("Flight", "edit_flight_discount_b2c")) { */ ?>
                                            <td>
                                               <?php echo date('d M,Y',$data['created'])?>
                                            </td>
                                            <?php /*}*/ ?>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr> <td colspan='11' class='text-center'><b>No Flight Coupon Found</b></td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </form>


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
</div>
