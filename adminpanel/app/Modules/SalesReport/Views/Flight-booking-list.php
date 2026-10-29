<div class="card-body">

    <div class="table_title">
        <div class="sale_bar">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="m-0"> Flight Sales Report</h5>
                </div>
                <div class="col-md-8 text-md-right">
                </div>
            </div>
        </div>
        <div class="page-content-area p-0 mt-3">


            <!----------Start Search Bar ----------------->

            <form action="<?php echo site_url('sale-result'); ?>" method="GET" class="row g-3 mb-3 tts-dis-content" id="sales-export" name="markup-search" onsubmit="return searchvalidateForm()">
                <input type="hidden" name="q" value="Flight">

                <div class="col-md-2">
                    <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'markup-search')" tts-error-msg="Please select search key">
                            <option value="">Please select</option>
                            <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                                                    echo "selected";
                                                                } ?>>Booking Ref Number
                            </option>
                            <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                                            echo "selected";
                                                        } ?>>First Name
                            </option>
                            <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                                                            echo "selected";
                                                        } ?>>Last Name
                            </option>
                            <option value="ticket_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'ticket_number') {
                                                                echo "selected";
                                                            } ?>>Ticket No
                            </option>
                            <option value="pnr" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'pnr') {
                                                    echo "selected";
                                                } ?>>PNR
                            </option>
                            <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                                                echo "selected";
                                                            } ?>>Booking Status
                            </option>
                            <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                                                echo "selected";
                                                            } ?>>Payment Status
                            </option>
                            <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                                            echo "selected";
                                                        } ?>>Date Range
                            </option>
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

                                    echo '';
                                } ?> tts-error-msg="Please enter value" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" id="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                            echo $search_bar_data['from_date'];
                                                                                                                                        } else {
                                                                                                                                            echo date('d M Y');
                                                                                                                                        }  ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" id="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                    echo $search_bar_data['to_date'];
                                                                                                                                } else {
                                                                                                                                    echo date('d M Y');
                                                                                                                                }  ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                    </div>
                </div>
                <div class="col-md-1 align-self-end">
                    <div class="form-group form-mb-20">
                        <button type="submit" data-url="<?php echo site_url('sale-result'); ?>" praveen-method="get" class="badge badge-md badge-primary export-praveen badge_search">Search <i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-1">
                    <? if (isset($search_bar_data['key'])) : ?>
                        <div class="search-reset-btn">
                            <a href="<?php echo site_url('sale-result?q=Flight'); ?>">Reset Search</a>
                        </div>
                    <? endif ?>
                </div>
                <div class="col-md-1 text-md-right align-self-end">
                    <div class="form-group form-mb-20">
                        <button type="submit" data-url="<?php echo site_url('sale-result/get-report'); ?>" praveen-method="post" id="export-data-btn" class="btn_excel export-praveen">
                            <img src="<?php echo site_url('webroot/img/excel.svg'); ?>" class="img_fluid">
                        </button>
                    </div>
                </div>
                <input type="hidden" name="service" value="Flight">
            </form>


            <!----------End Search Bar ----------------->

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-active">
                        <tr>

                            <th>Ref. No.</th>
                            <th>Booking Source</th>
                            <th>Journey Type</th>
                            <th>Traveller Name</th>
                            <th>Sector</th>
                            <th>Departure</th>

                            <th>Refundable</th>
                            <th>Airline</th>
                            <th>PNR</th>
                            <th>Price</th>
                            <th>GST</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>


                            <!--<th>Airline Remark</th>-->


                            <th>Created</th>
                            <th>Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $data) {
                                if ($data['booking_status'] == 'Confirmed') {
                                    $class = 'tts-text-success';
                                } else {
                                    $class = 'tts-text-danger';
                                }

                                if ($data['payment_status'] == 'Successful') {
                                    $payment_class = 'tts-text-success';
                                } else {
                                    $payment_class = 'tts-text-danger';
                                }
                                if ($data['booking_source'] == "Wl_b2b") {
                                    $fareBreakupArray = json_decode($data['agent_fare_break_up'], true);
                                } else if ($data['booking_source'] == "Wl_b2c") {
                                    $fareBreakupArray = json_decode($data['customer_fare_break_up'], true);
                                }

                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                                        /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
                                        "MealBaggageCharge" => array("Value" => $fareBreakupArray['TotalBaggageCharges'] + $fareBreakupArray['TotalMealCharges'], "LabelText" => "Meal & Baggage Charges"),
                                        /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
                                        "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
                                        "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
                                    ),
                                    "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
                                    "GSTDetails" => $fareBreakupArray['GST']
                                );


                        ?>
                                <tr>

                                    <td>
                                        <a href="<?php echo site_url('/flight/details/') . $data['booking_ref_number']; ?>" target="_blank"><?php echo $data['booking_ref_number']; ?></a>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($data['booking_source'] == "Wl_b2b") : ?>
                                            <span><?php echo service_booking_source($data['booking_source'] ?? "") . ' - ' . $data['company_id']; ?> </span><br />
                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['wl_agent_id']); ?>" data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['wl_agent_id']); ?>"> <?php echo (isset($data['company_name']) && !empty($data['company_name'])) ? $data['company_name'] : 'NA' ?></a>
                                        <?php else : ?>
                                            <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='customer' data-id="<?php echo dev_encode($data['wl_customer_id']); ?>" data-href="<?php echo site_url('customer/customer-details/') . dev_encode($data['wl_customer_id']); ?>"> <span><?php echo service_booking_source($data['booking_source'] ?? ""); ?> </span></a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ucfirst($data['journey_type']); ?></td>
                                    <td><?php echo ucfirst($data['lead_passenger_name']); ?></td>
                                    <td><?php echo ucfirst($data['origin']) . "-" . ucfirst($data['destination']); ?></td>
                                    <td><?php echo display_custom_date_format($data['departure_date']); ?></td>

                                    <td>
                                        <span class="<?php echo $data['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"><?php echo $data['is_refundable'] == 1 ? "Yes" : "NO"; ?></span>
                                    </td>
                                    <td> <?php echo $data['validating_airline_code']; ?></td>
                                    <td><?php echo $data['pnr']; ?></td>
                                    <td>
                                        <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                            <b><?php echo $fare['LabelText']; ?>:</b>
                                            ₹ <?php echo $fare['Value']; ?>
                                            <br />
                                        <?php } ?>

                                        <b><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</b>
                                        ₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
                                    </td>

                                    <td>
                                        <b>Taxable
                                            Value:₹ <?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></b> </br>
                                        <b>CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                            %:</b>₹ <?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?>
                                        <br />
                                        <b>SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                            %:</b>₹ <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?>
                                        <br />
                                        <b>IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                            %:</b>₹ <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                        <br />
                                        <b>Total:</b>₹ <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                        <br />


                                    </td>
                                    <td>
                                        <span class="<?php echo $payment_class ?>"><?php echo ucfirst($data['payment_status']); ?></span>
                                    </td>
                                    <td>
                                        <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['booking_status']); ?>
                                        </span>
                                    </td>


                                    <!--  <td> <?php /*echo $data['airline_remark']; */ ?>  </td>-->


                                    <td><?php echo date_created_format($data['created']); ?> </td>
                                    <td>
                                        <a href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($data['id']))); ?>" target="_blank"><i class="tts-icon eye"> View</i></a>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo "<tr> <td colspan='21' class='text-center'><b>No Booking Found</b></td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="row pagiantion_row align-items-center">
                <div class="col-md-6">
                    <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                        of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
                        found </p>
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