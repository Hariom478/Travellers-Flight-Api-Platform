<?php
if ($details) {  ?>


    <div class="modal-header">
        <span class="close" onclick="ttsclosemodel('view_logs')">&times;</span>
        <h5><? echo 'SMS' . ' '; ?>Details</h5>
    </div>

    <div class="modal-body m0" style="padding-bottom: 15%;">
        <div id="short_description" class="tab-content current p0">
            <div class="tts-col-12 p0">
                <h6 class="viewld_h5"><? echo 'SMS' . ' '; ?> Details</h6>
            </div>

            <table cellspacing="0" cellpadding="0" width="100%">
                <tbody class="lead_details">
                <tr>
                    <td align="right"><span class=" item-text-head"><b>To SMS</b></span></td>
                    <td><span class="item-text-value"><b><?php echo $details['to_sms']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>SMS Type</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['sms_type'] ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Status</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>role</b></span></td>
                    <td><span class="item-text-value"> <?php echo ucfirst($details['role']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>IP Address</b></span></td>
                    <td><span class="item-text-value"> <?php echo $details['ip_address']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Message</b></span></td>
                    <td><span class="item-text-value"> <?php echo $details['message']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>API Response</b></span></td>
                    <td> <span class="item-text-value"><code><?php echo ($details['sms_api_response']); ?></code></span></td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Created Date</b></span></td>
                    <td> <span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class="item-text-head"><b>Modified Date</b></span></td>
                    <td>
                        <span class="item-text-value">
                            <?php
                            if(isset($details['modified_time'])){
                                echo date_created_format($details['modified_time']);
                            }else{
                                echo '-';
                            }
                            ?>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
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