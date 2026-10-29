<div class="content ">
    <div class="">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0"> Hotel Sales Report</h5>
                    </div>
                    <div class="col-md-8 text-md-right">
                    </div>
                </div>
            </div>
            <?php if (empty($search_bar_data)) {
                $search_bar_data['key'] = "date-range";
            } ?>
            <div class="page-content-area p-0">

                <!----------Start Search Bar ----------------->
                <form action="<?php echo site_url('sale-result'); ?>" method="GET" class=" mb-3 tts-dis-content" id="sales-export" name="markup-search" onsubmit="return searchvalidateForm()">
                    <input type="hidden" name="q" value="Hotel">
                    <div class="row align-items-center mt-3">
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
                                                                                                                                                } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" id="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                                        } else {
                                                                                                                                            echo date('d M Y');
                                                                                                                                        } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
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
                                    <a href="<?php echo site_url('sale-result?q=Hotel'); ?>">Reset Search</a>
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
                        <input type="hidden" name="service" value="Hotel">
                    </div>
                </form>

                <!----------End Search Bar ----------------->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-active">
                            <tr>
                                <th>Ref. No.</th>
                                <th>Booking Source</th>
                                <th>Hotel Name</th>
                                <th>Destination</th>
                                <th>Checkin/Checkout</th>
                                <th>CNF Number</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>GST</th>
                                <th>Pay Status</th>
                                <th>Book Status</th>


                                <th>Created</th>
                                <th>Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {

                                $count = 1;
                                foreach ($list as $data) {
                                    if ($data['booking_status'] == 'Confirmed') {
                                        $class = 'tts-text-success';
                                    } else {
                                        $class = 'tts-text-danger';
                                    }

                                    if ($data['payment_status'] == 'Successful') {
                                        $payment_class = 'tts-text-success';
                                    } else {
                                        $payment_class   = 'tts-text-danger';
                                    }



                                    $publishedFare = 0;
                                    $offeredFare = 0;
                                    $CommEarned = 0;
                                    $TDS = 0;
                                    $ApplyDiscount = 0;
                                    $ApplyMarkup = 0;
                                    $CGSTAmount = 0;
                                    $IGSTAmount = 0;
                                    $SGSTAmount = 0;
                                    $TaxableAmount = 0;

                                    $web_partner_fare_break_up = json_decode($data['web_partner_fare_break_up'], true);
                                    if ($data['booking_source'] == "Wl_b2b") {
                                        $fareBreakUp = json_decode($data['agent_fare_break_up'], true);
                                    } else if ($data['booking_source'] == "Wl_b2c") {
                                        $fareBreakUp = json_decode($data['customer_fare_break_up'], true);
                                    }
                                    // pr($fareBreakUp);exit;
                                    unset($fareBreakUp['couponAmount']);
                                    $GSTDATA = $fareBreakUp[0]['GST'];
                                    foreach ($fareBreakUp as $key => $HotelRooms) {
                                        if (isset($HotelRooms['GST'])) {
                                            $GST = $HotelRooms['GST'];
                                            $GSTDATA['CGSTAmount'] = $CGSTAmount + $GST['CGSTAmount'];
                                            $GSTDATA['IGSTAmount'] = $IGSTAmount + $GST['IGSTAmount'];
                                            $GSTDATA['SGSTAmount'] = $SGSTAmount + $GST['SGSTAmount'];
                                            $GSTDATA['TaxableAmount'] = $TaxableAmount + $GST['TaxableAmount'];
                                        }
                                        $web_partner_fare_break_up = $web_partner_fare_break_up[$key];
                                        $markup = isset($web_partner_fare_break_up['WebPMarkUp']) ? $web_partner_fare_break_up['WebPMarkUp'] : 0;
                                        $discount = isset($web_partner_fare_break_up['WebPDiscount']) ? $web_partner_fare_break_up['WebPDiscount'] : 0;
                                        $ApplyDisPlayMarkup = isset($web_partner_fare_break_up['WebPDisplayMarkup']) ? $web_partner_fare_break_up['WebPDisplayMarkup'] : 'in_tax';
                                        $ApplyMarkup = $ApplyMarkup + $markup;
                                        $ApplyDiscount = $ApplyDiscount + $discount;
                                        $publishedFare = $publishedFare + $HotelRooms['PublishedPrice'];
                                        $offeredFare = $offeredFare + $HotelRooms['OfferedPrice'];
                                        $CommEarned = $CommEarned + $HotelRooms['AgentCommission'] + $HotelRooms['Discount'];
                                        $TDS = $TDS + $HotelRooms['TDS'];
                                    }

                                    $FareBreakUp = array(
                                        "FareBreakup" => array(
                                            "Gross" => array("Value" => (round_value($publishedFare)), "LabelText" => "Gross"),
                                            "CommEarned" => array("Value" => (round_value($CommEarned)), "LabelText" => "Comm Earned (-)"),
                                            "TDS" => array("Value" => (round_value($TDS)), "LabelText" => "TDS (+)")
                                        ),
                                        "TotalAmount" => array("Value" => (round_value($offeredFare + $TDS)), "LabelText" => "Total Amount"),
                                        "GSTDetails" => $GSTDATA,
                                        "WebPMarkUp" => array("Value" => (round_value($ApplyMarkup)), "LabelText" => "Apply Mark Up"),
                                        "WebPDiscount" => array("Value" => (round_value($ApplyDiscount)), "LabelText" => "Apply Discount"),
                                        "ApplyDisPlayMarkup" => array("Value" => $ApplyDisPlayMarkup, "LabelText" => "Apply DisPlay Markup"),
                                    );
                            ?>
                                    <tr>

                                        <td> <a href="<?php echo site_url('/hotel/details/') . $data['booking_ref_number']; ?>" target="<?php echo target; ?>"><?php echo $data['booking_ref_number']; ?></a></td>
                                        <td class="text-center">
                                            <?php if ($data['booking_source'] == "Wl_b2b") : ?>
                                                <span><?php echo service_booking_source($data['booking_source'] ?? "") . ' - ' . $data['company_id']; ?> </span><br />
                                                <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='agent' data-id="<?php echo dev_encode($data['wl_agent_id']); ?>" data-href="<?php echo site_url('agent/agent-details/') . dev_encode($data['wl_agent_id']); ?>"> <?php echo (isset($data['company_name']) && !empty($data['company_name'])) ? $data['company_name'] : 'NA' ?></a>
                                            <?php else : ?>
                                                <a href="javascript:void(0);" class="txt_led_clr" view-data-modal="true" data-controller='customer' data-id="<?php echo dev_encode($data['wl_customer_id']); ?>" data-href="<?php echo site_url('customer/customer-details/') . dev_encode($data['wl_customer_id']); ?>"> <span><?php echo service_booking_source($data['booking_source'] ?? ""); ?> </span></a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo ucfirst($data['hotel_name']); ?></td>
                                        <td><?php echo $data['city'] . ' / ' . $data['country_code']; ?></td>
                                        <td><?php echo 'CheckIn: ' . display_custom_date_format($data['check_in_date']); ?><br><?php echo 'CheckOut: ' . display_custom_date_format($data['check_out_date']); ?></td>
                                        <td><?php echo $data['confirmation_no']; ?></td>
                                        <td>
                                            <?php foreach ($FareBreakUp['FareBreakup'] as $fare) {
                                                if ($fare['LabelText'] != 'Comm Earned (-)' && $fare['LabelText'] != 'Discount (-)' && $fare['LabelText'] != 'TDS (+)') { ?>
                                                    <b><?php echo $fare['LabelText']; ?>:</b>
                                                    <i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($fare['Value']); ?>
                                                    <br />
                                                <?php }
                                                ?>
                                            <?php } ?>
                                            <b><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</b>
                                            <i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['TotalAmount']['Value']); ?>
                                        </td>
                                        <td>
                                            <?php foreach ($FareBreakUp['FareBreakup'] as $fare) {
                                                if ($fare['LabelText'] == 'Comm Earned (-)' || $fare['LabelText'] == 'Discount (-)' || $fare['LabelText'] == 'TDS (+)') { ?>
                                                    <b><?php echo $fare['LabelText']; ?>:</b>
                                                    <i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($fare['Value']); ?>
                                                    <br />
                                                <?php }
                                                ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <b>Taxable
                                                Value:<i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['GSTDetails']['TaxableAmount']); ?>
                                            </b> </br>
                                            <b>CGST @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                                %:</b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['GSTDetails']['CGSTAmount']); ?>
                                            <br />
                                            <b>SGST @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                                %:</b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['GSTDetails']['SGSTAmount']); ?>
                                            <br />
                                            <b>IGST @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                                %:</b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['GSTDetails']['IGSTAmount']); ?>
                                            <br />
                                            <b>Total:</b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo custom_money_format($FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']); ?>
                                            <br />
                                        </td>

                                        <td> <span class="<?php echo $payment_class ?>"><?php echo ucfirst($data['payment_status']); ?></span></td>
                                        <td>
                                            <span class="<?php echo $class ?>">
                                                <?php echo ucfirst($data['booking_status']); ?>
                                            </span>
                                        </td>


                                        <td>
                                            <?php echo date_created_format($data['created']); ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url('/hotel/confirmation/') . $data['booking_ref_number']; ?>" target="<?php echo target; ?>"><i class="tts-icon eye"> View</i></a>

                                        </td>
                                    </tr>
                            <?php }
                            } else {
                                echo "<tr> <td colspan='19' class='text-center'><b>No Booking Found</b></td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row pagiantion_row align-items-center">
                    <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                            of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
                        </p>
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