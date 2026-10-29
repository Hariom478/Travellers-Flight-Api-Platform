<?php
if ($details) { ?>


<div class="modal-header">
    <h5 class="modal-title">
        <? echo $title . ' '; ?>Details
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="vewmodelhed">
    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-2">
                <div class="vi_mod_dsc">
                    <span>Supplier ID </span>
                    <span class="primary"> <b>
                            <?php echo $details['company_id']; ?>
                        </b> </span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="vi_mod_dsc">
                    <span>Name</span>
                    <span class="primary"> <b>
                            <?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?>
                        </b> </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="vi_mod_dsc">
                    <span>Email </span>
                    <span class="primary"> <b>
                            <?php echo $details['login_email'] ?>
                        </b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Mobile No</span>
                    <span class="primary"> <b>
                            <?php echo $details['mobile_no']; ?>
                        </b> </span>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                    type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Agent Details</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                tabindex="0">
                <div class="col-md-12">
                    <h6 class="viewld_h5">
                        <?php echo 'Company Information'; ?>
                    </h6>
                </div>
                <table class="table table-bordered ">
                    <tbody class="lead_details">

                        <tr>
                            <td><span class=" item-text-head"><b>Supplier ID</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['company_id']); ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>Name</b></span></td>
                            <td>
                                <span class="item-text-value">
                                    <?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Email</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo $details['login_email']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Mobile</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo $details['mobile_no']; ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>Company Name</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['company_name']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Supplier Class</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['class_name']); ?>
                                </span></td>
                        </tr>



                        <tr>
                            <td><span class=" item-text-head"><b>Balance</b></span></td>
                            <td>
                                <span class="item-text-value">
                                    <?php
                                        if (isset($details['balance'])) {
                                            echo custom_money_format($details['balance']);
                                        } else {
                                            echo "0";
                                        }
                                        ?>
                                </span>
                            </td>
                        </tr>



                        <tr>
                            <td><span class=" item-text-head"><b>Status</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['status']); ?>
                                </span></td>
                        </tr>


                        <tr>
                            <td><span class=" item-text-head"><b>GST Holder Name</b></span></td>
                            <td>
                                <?php echo ucfirst($details['gst_holder_name']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>GST Number</b></span></td>
                            <td>
                                <span class="item-text-value">
                                    <?php echo $details['gst_number']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>GST Scan Copy</b></span></td>
                            <td>
                                <?php if ($details['gst_scan_copy']) { ?>
                                <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/suppliers/gst/" . $details['gst_scan_copy']; ?>"
                                        target="_blank"><?php echo 'View GST Scan Copy'; ?></a></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>Pan Holder Name</b></span></td>
                            <td>
                                <span class="item-text-value">
                                    <?php echo ucfirst($details['pan_name']); ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>PAN Number</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo $details['pan_number']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>PAN Scan Copy</b></span></td>
                            <td>
                                <?php if ($details['pan_card']) { ?>
                                <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/suppliers/pan-card/" . $details['pan_card']; ?>"
                                        target="_blank"><?php echo 'View PAN Scan Copy'; ?></a></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Aadhar Number</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo $details['aadhaar_no']; ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class=" item-text-head"><b>Aadhar Scan Copy</b></span></td>
                            <td>
                                <?php if ($details['aadhar_scan_copy']) { ?>
                                <span class="item-text-value"><a
                                        href="<?php echo root_url . "uploads/suppliers/aadhar/" . $details['aadhar_scan_copy']; ?>"
                                        target="_blank"><?php echo 'View Aadhar Scan Copy'; ?></a></span>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td><span class=" item-text-head"><b>Address</b></span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['address']); ?>
                                </span>
                            </td>
                        </tr>


                        <tr>
                            <td><span class="item-text-head"><b>State</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['state']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>Country</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['country']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>City</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php echo ucfirst($details['city']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>Pin Code</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php echo $details['pincode']; ?>
                                </span></td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>Created</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php echo date_created_format($details['created']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="item-text-head"><b>Modified</b> </span></td>
                            <td><span class="item-text-value">
                                    <?php
                                        if (isset($details['modified'])) {
                                            echo date_created_format($details['modified']);
                                        } else {
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
    </div>

    <?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>