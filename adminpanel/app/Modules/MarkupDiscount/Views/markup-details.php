<?php
if ($details) { ?>


    <div class="modal-header">
        <h5 class="modal-title"><? echo 'Flight Markup ' . ' '; ?>Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="vewmodelhed">

        <div class="row m0">
            <div class="col-md-2">
                <div class="vi_mod_dsc">
                    <span>Airline Code</span>
                    <span class="primary"> <b><?php echo $details['airline_code']; ?></b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Airline Name</span>
                    <span class="primary"> <b><?php echo $details['airline_name'] ; ?></b> </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="vi_mod_dsc">
                    <span>Flight Type</span>
                    <span class="primary">
                        <b>
                            <?php

                            $is_domestic = explode(',',$details['is_domestic']);

                            if (in_array("1", $is_domestic)) {
                                echo 'Domestic,';
                            } if (in_array("0", $is_domestic)) {
                                echo 'International';
                            }
                            ?>
                        </b>
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="vi_mod_dsc">
                    <span>Markup Type</span>
                    <span class="primary"> <b><?php echo ucfirst($details['markup_type']); ?> </b> </span>
                </div>
            </div>
            <div class="col-md-1">
                <div class="vi_mod_dsc">
                    <span>Value</span>
                    <span class="primary"> <b><?php echo $details['value']; ?> </b> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body">
        <ul class="tabs">
            <li class="tab-link current" data-tab="markup_details">Flight Markup </li>
        </ul>

        <!-- Start of  Details  Tab Content -->
        <div id="markup_details" class="tab-content current p0">
            <div class="col-md-12 p0">
                <h6 class="viewld_h5"><?php echo 'Markup Details'; ?></h6>
            </div>
            <table class="table table-bordered table-hover">
                <tbody class="lead_details">
                <tr>
                    <th scope="row"><span class=" item-text-head">Airline Code</span></th>
                    <td><span class="item-text-value"><?php echo $details['airline_code'];  ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Airline Name</span></th>
                    <td><span class="item-text-value"><?php echo $details['airline_name'];  ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Flight Type</span></th>
                    <td>
                        <span class="item-text-value">
                            <?php $is_domestic = explode(',',$details['is_domestic']);

                            if (in_array("1", $is_domestic)) {
                                echo 'Domestic,';
                            } if (in_array("0", $is_domestic)) {
                                echo 'International';
                            }  ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Markup Type</span></th>
                    <td><span class="item-text-value"><?php echo $details['markup_type'];  ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">Value</span></th>
                    <td><span class="item-text-value"><?php echo $details['value'];  ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">From Airport</span></th>
                    <td><span class="item-text-value"><?php echo rtrim($details['from_airport_code'], ',');  ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><span class=" item-text-head">To Airport</span></th>
                    <td><span class="item-text-value"><?php echo rtrim($details['to_airport_code'], ',') ; ?></span></td>
                </tr>

                <tr>
                    <th scope="row"><span class=" item-text-head">Travel From Date</span></th>
                    <td><span class="item-text-value">
                            <?php
                            if (isset($details['travel_date_from']) && $details['travel_date_from']!='') {
                                echo timestamp_to_date($details['travel_date_from']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><span class=" item-text-head">Travel From To</span></th>
                    <td><span class="item-text-value">

                            <?php
                            if (isset($details['travel_date_to']) && $details['travel_date_to']!='') {
                                echo timestamp_to_date($details['travel_date_to']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </span>
                    </td>
                </tr>


                <tr>
                    <th scope="row"><span class=" item-text-head">Journey Type</span></th>
                    <td><span class="item-text-value"><?php echo $details['journey_type']; ?></span></td>
                </tr>


                <!--<tr>
                    <th scope="row"><span class=" item-text-head"><b>Pax Type</b></span></td>
                    <td>
                       <span class="item-text-value"> <?php /*echo $details['pax_type']; */?> </span>
                    </td>
                </tr>-->
                <tr>
                    <th scope="row"><span class="item-text-head">Cabin Class</span></th>
                    <td>
                        <span class="item-text-value">
                                     <?php echo $details['cabin_class']; ?>
                        </span>
                    </td>
                </tr>

               

                <tr>
                    <th scope="row"><span class="item-text-head">Status </span></th>
                    <td><span class="item-text-value"><?php echo ucfirst($details['status']); ?></span>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><span class="item-text-head">Created </span></th>
                    <td><span class="item-text-value"><?php echo date_created_format($details['created']); ?></span></td>
                </tr>
                <tr>
                    <th scope="row"><span class="item-text-head">Modified </span></th>
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
<?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>