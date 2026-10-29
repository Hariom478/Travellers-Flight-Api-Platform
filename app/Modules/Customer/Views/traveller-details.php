<?php
if ($details) { ?>


    <div class="modal-header">
        <span class="close" onclick="ttsclosemodel(this)">&times;</span>
        <h5><? echo   'Traveller'; ?>Details</h5>
    </div>
    <div class="vewmodelhed">

        <div class="tts_row m0">
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Name</span>
                    <span class="primary"> <b><?php echo ucfirst($details['title']) . ' ' . ucfirst($details['first_name']) . ' ' . ucfirst($details['last_name']); ?></b> </span>
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
                    <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                </div>
            </div>
            <div class="tts-col-2">
                <div class="vi_mod_dsc">
                    <span>Marital Status</span>
                    <span class="primary"> <b><?php echo ucfirst($details['marital_status']); ?> </b> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body m0" style="padding-bottom: 15%;">
        <ul class="tabs">
            <li class="tab-link current" data-tab="traveller_details">Traveller Details</li>
        </ul>

        <!-- Start of agent Details  Tab Content -->
        <div id="agent_details" class="tab-content current p0">
            <div class="tts-col-12 p0">
                <h6 class="viewld_h5"><?php echo 'Traveller Information'; ?></h6>
            </div>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tbody class="lead_details">


                <tr>
                    <td align="right"><span class=" item-text-head"><b>Status</b></span></td>
                    <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>DOB</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['dob']; ?></span></td>
                </tr>

                <tr>
                    <td align="right"><span class=" item-text-head"><b>Passport Number</b></span></td>
                    <td><span class="item-text-value"><?php echo $details['passport_number']; ?></span>
                    </td>
                </tr>


                <tr>
                    <td align="right"><span class="item-text-head"><b>Passport Expiry Date</b> </span></td>
                    <td><span class="item-text-value"><?php echo $details['passport_expiry_date']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><span class="item-text-head"><b>Passport Issue Date</b> </span></td>
                    <td><span class="item-text-value"><?php echo $details['passport_issue_date']; ?></span>
                    </td>
                </tr>

                <tr>
                    <td align="right"><span class="item-text-head"><b>Created</b> </span></td>
                    <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
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