<?php
if ($details) { ?>


    <div class="modal-header">
        <span class="close" onclick="ttsclosemodel(this)">&times;</span>
        <h5><? echo 'Flight Markup ' . ' '; ?>Details</h5>
    </div>
    <div class="vewmodelhed">

        <div class="tts_row m0">
            <!--<div class="tts-col-2">
                <div class="vi_mod_dsc">
                    <span>Airline Code</span>
                    <span class="primary"> <b><?php /*echo $details['airline_code']; */ ?></b> </span>
                </div>
            </div>
            <div class="tts-col-3">
                <div class="vi_mod_dsc">
                    <span>Airline Name</span>
                    <span class="primary"> <b><?php /*echo $details['airline_name'] ; */ ?></b> </span>
                </div>
            </div>
            <div class="tts-col-2">
                <div class="vi_mod_dsc">
                    <span>Flight Type</span>
                    <span class="primary">
                        <b>
                            <?php
                            /*
                            if ($details['is_domestic'] == 1) {
                                echo 'Domestic';
                            } elseif ($details['is_domestic'] == 0) {
                                echo 'International';
                            } else {
                                echo 'Unknown';
                            }

                            */ ?>
                        </b>
                    </span>
                </div>
            </div>-->

            <tr>
                <td align="right"><span class=" item-text-head"><b>Display Markup</b></span></td>
                <td><span class="item-text-value"><?php echo $details['display_markup'];  ?></span>
                </td>
            </tr>

            <div class="tts-col-2">
                <div class="">
                    <span>Markup Type</span>
                    <span class="primary"> <b><?php echo ucfirst($details['markup_type']); ?> </b> </span>
                </div>
            </div>
            <tr>
                <td align="right"><span class=" item-text-head"><b>Web Partner Class</b></span></td>
                <td><span class="item-text-value"><?php echo $details['class_name'];  ?></span>
                </td>
            </tr>
            <div class="tts-col-3">
                <div class="">
                    <span>Value</span>
                    <span class="primary"> <b><?php echo $details['value']; ?> </b> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body m0" style="padding-bottom: 15%;">
        <ul class="tabs">
            <li class="tab-link current" data-tab="markup_details">Flight Markup </li>
        </ul>

        <!-- Start of  Details  Tab Content -->
        <div id="markup_details" class="tab-content current p0">
            <div class="tts-col-12 p0">
                <h6 class="viewld_h5"><?php echo 'Markup Details'; ?></h6>
            </div>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tbody class="lead_details">
                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Airline Code</b></span></td>
                        <td><span class="item-text-value"><?php echo $details['airline_code'];  ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Airline Name</b></span></td>
                        <td><span class="item-text-value"><?php echo $details['airline_name'];  ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Flight Type</b></span></td>
                        <td>
                            <span class="item-text-value">
                                <?php

                                if ($details['is_domestic'] == 1) {
                                    echo 'Domestic';
                                } elseif ($details['is_domestic'] == 0) {
                                    echo 'International';
                                } else {
                                    echo 'Unknown';
                                }

                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Markup Type</b></span></td>
                        <td><span class="item-text-value"><?php echo $details['markup_type'];  ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Value</b></span></td>
                        <td><span class="item-text-value"><?php echo $details['value'];  ?></span>
                        </td>
                    </tr>






                    <tr>
                        <td align="right"><span class=" item-text-head"><b>Journey Type</b></span></td>
                        <td><span class="item-text-value"><?php echo $details['journey_type']; ?></span></td>
                    </tr>





                    <tr>
                        <td align="right"><span class="item-text-head"><b>Status</b> </span></td>
                        <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="right"><span class="item-text-head"><b>Created</b> </span></td>
                        <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
                    </tr>
                    <tr>
                        <td align="right"><span class="item-text-head"><b>Modified</b> </span></td>
                        <td style="width: 74%;">
                            <span class="item-text-value">
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
        <!-- End of  Details  Tab Content -->
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