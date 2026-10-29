<?php
if ($details) { ?>


    <div class="modal-header">
        <span class="close" onclick="ttsclosemodel(this)">&times;</span>
        <h5><? echo $title . ' '; ?>Details</h5>
    </div>
    <div class="vewmodelhed">

        <div class="tts_row m0">
            <div class="tts-col-2">
                <div class="vi_mod_dsc">
                    <span>Agent ID </span>
                    <span class="primary"> <b><?php echo $details['id']; ?></b> </span>
                </div>
            </div>
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Name</span>
                    <span class="primary"> <b><?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?></b> </span>
                </div>
            </div>
            <div class="tts-col-4">
                <div class="vi_mod_dsc">
                    <span>Email </span>
                    <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                </div>
            </div>
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Mobile No</span>
                    <span class="primary"> <b><?php echo $details['mobile_number']; ?> </b> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body m0" style="padding-bottom: 15%;">
        <ul class="tabs">
            <li class="tab-link current" data-tab="agent_details">Agent Details</li>
            <?php if (permission_access("Agent", "account_logs")) { ?>
                <li class="tab-link " data-tab="account_logs">Account Logs</li>
            <?php } ?>
        </ul>

        <!-- Start of agent Details  Tab Content -->
        <div id="agent_details" class="tab-content current p0">
            <div class="tts-col-12 p0">
                <h6 class="viewld_h5"><?php echo 'Company Information'; ?></h6>
            </div>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tbody class="lead_details">
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Agent ID</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['id']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Name</b></span></td>
                    <td>
                        <span class="item-text-value"><?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['email_id']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Mobile</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['mobile_number']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Company Name</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['company_name']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Agent Class</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['class_name']); ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Credit Limit</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['credit_limit']; ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Available Balance</b></span></td>
                    <td>
                        <span class="item-text-value">
                            <?php
                            if (isset($details['balance'])) {
                                echo $details['balance'];
                            }
                            ?>
                        </span>
                    </td>
                </tr>


                <tr>
                    <td align="right"><span class=" item-text-head"><b>Status</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Company Logo</b></span></td>
                    <td>
                        <?php if ($details['company_logo']) { ?>
                            <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/agent/" . $details['company_logo']; ?>"
                                        target="_blank"><?php echo 'View Logo'; ?></a></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>GST Holder Name</b></span></td>
                    <td>
                        <?php echo ucfirst($details['gst_holder_name']); ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>GST Number</b></span></td>
                    <td>
                        <span class="item-text-value">
                                     <?php echo $details['gst_number']; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>GST Scan Copy</b></span></td>
                    <td>
                        <?php if ($details['gst_scan_copy']) { ?>
                            <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/agent/" . $details['gst_scan_copy']; ?>"
                                        target="_blank"><?php echo 'View GST Scan Copy'; ?></a></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Pan Holder Name</b></span></td>
                    <td>
                        <span class="item-text-value">
                            <?php echo ucfirst($details['pan_holder_name']); ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>PAN Number</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['pan_number']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>PAN Scan Copy</b></span></td>
                    <td>
                        <?php if ($details['pan_scan_copy']) { ?>
                            <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/agent/" . $details['pan_scan_copy']; ?>"
                                        target="_blank"><?php echo 'View PAN Scan Copy'; ?></a></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Profile Pic</b></span></td>
                    <td>
                        <?php if ($details['profile_pic']) { ?>
                            <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/agent/" . $details['profile_pic']; ?>"
                                        target="_blank"><?php echo 'View Profile Pic'; ?></a></span>
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Address</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['address']); ?></span>
                    </td>
                </tr>


                <tr>
                    <td align="right"><span class="item-text-head"><b>State</b> </span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['state']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Country</b> </span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['country']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>City</b> </span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['city']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Pin Code</b> </span></td>
                    <td><span class="item-text-value"><?php echo $details['pincode']; ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Created</b> </span></td>
                    <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Modified</b> </span></td>
                    <td style="width: 74%;"><span class="item-text-value"><?php
                            if (isset($details['modified'])) {
                                echo date_created_format($details['modified']);
                            } else {
                                echo '-';
                            }
                            ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- End of agent Details  Tab Content -->

        <!-- Start of account logs  Tab Content -->
        <div id="account_logs" class="tab-content p0">
            <div class="tts-col-12 p0">
                <h6 class="viewld_h5"><?php echo 'Account Logs'; ?></h6>
            </div>
            <table class="table-strip divName">
                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Credit</th>
                    <th>Debit</th>
                    <th>Balance</th>
                    <th>Remark</th>
                    <th>Created</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                if (!empty($account_logs) && is_array($account_logs)) {
                    foreach ($account_logs as $data) {


                        ?>

                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                <?php echo $data['credit']; ?>
                            </td>
                            <td>
                                <?php echo $data['debit']; ?>
                            </td>
                            <td><?php echo $data['balance']; ?></td>

                            <td>
                                <?php echo $data['remark']; ?>
                            </td>

                            <td><?php
                                if (isset($data['created'])) {
                                    echo date_created_format($data['created']);
                                }
                                ?></td>
                        </tr>
                        <?php $count++;
                    }
                } else {
                    echo "<tr> <td colspan='11' class='text_center'><b>No Account Log Found</b></td></tr>";
                } ?>
                </tbody>
            </table>
        </div>
        <!-- End of account logs  Tab Content -->
    </div>
    <div class="modal-footer">
        <div class="tts_row">
            <div class="tts-col-12">

            </div>
        </div>
    </div>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>