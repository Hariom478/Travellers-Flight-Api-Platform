<?php
if ($details) {  ?> 
    <div class="modal-header">
        <h5 class="modal-title"><? echo 'Email' . ' '; ?>Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>From Email </span>
                    <span class="primary"> <b><?php echo $details['from_email']; ?></b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>To Email </span>
                    <span class="primary"> <b><?php echo $details['to_email'] ?> </b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>CC Email</span>
                    <span class="primary"> <b><?php echo $details['cc_email']; ?> </b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>BCC Email</span>
                    <span class="primary"> <b><?php echo $details['bcc_email']; ?> </b> </span>
                </div>
            </div>
        </div>
        <div id="short_description" class="tab-content current">
            <div class="col-md-12 ">
                <h6 class="viewld_h5"><? echo 'Email' . ' '; ?> Details</h6>
            </div>

            <table cellspacing="0" cellpadding="0" width="100%">
                <tbody class="lead_details">

                <tr>
                    <td align="right"><span class=" item-text-head"><b>From Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['from_email']; ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>To Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['to_email']; ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>CC Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['cc_email']; ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>BCC Email</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['bcc_email']; ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Status</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span></td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>role</b></span></td>
                    <td><span class="item-text-value"> <?php //echo ucfirst($details['role']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class=" item-text-head"><b>IP Address</b></span></td>
                    <td><span class="item-text-value"> <?php echo $details['ip_address']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Created Date</b></span></td>
                    <td> <span class="item-text-value"><b><?php echo date_created_format($details['created']); ?></span></td>
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
                <tr>
                    <td align="right"><span class=" item-text-head"><b>Message</b></span></td>
                    <td><span class="item-text-value"> <?php echo $details['message']; ?></span>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
    </div>
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>