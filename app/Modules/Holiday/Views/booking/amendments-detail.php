<!-- <style>

    .input-floating-label {
        font-size: 13px;
        padding: 13.5px 0;
        border: none;
        border-bottom: solid 1px #e5e5e5;
        width: 100%;
        box-sizing: border-box;
        transition: all 0.3s linear;
        color: #333;
        font-weight: 400;
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        border-radius: 0;
        background: transparent;
    }
</style> -->
<div class="page-content">
    <div class="table_title">


        <section class="cart_information"> <?php //pr($amendmentDetail);
                                            ?>
            <div class="container">
                <div class="sale_bar">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="m-0"> Amendment Details (<?php echo $amendmentDetail['id']; ?>)</h5>
                        </div>
                        <div class="col-md-8 text-md-end">
                            <a class="badge badge-wt" href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $amendmentDetail['Booking_id'], "BookingToken" => $amendmentDetail['tts_search_token']))); ?>">Booking
                                Summary</a>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12 col-12 col-lg-12">
                        <div class="cart_info">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseamendment" aria-expanded="true" aria-controls="collapseamendment">
                                            <span class="acordian_heading">Amendment Information : <?php echo $amendmentDetail['id']; ?></span>
                                        </button>
                                        <div id="collapseamendment" class="accordion-collapse collapse show" aria-labelledby="headingamendment" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Id :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['id']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Status :<span class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo ucfirst($amendmentDetail['amendment_status']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst($amendmentDetail['amendment_type']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Remark From Agent:<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['remark_from_user']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Remark From Company :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['remark_from_web_partner']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['created']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="acordian_heading">Cart Information : <?php echo $amendmentDetail['booking_ref_number']; ?></span>
                                    </button>

                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Ref Number :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_ref_number']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amount :<span class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo $amendmentDetail['total_price']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_status']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Payment Status :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['payment_status']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Channel Type :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_channel']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">CreatedOn :<span class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['booking_created']); ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <a href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $amendmentDetail['Booking_id'], "BookingToken" => $amendmentDetail['tts_search_token'])));; ?>" class="">Booking Summary</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking User :<span class="cart_info-field--detail"><span> &nbsp;<a href="#" class=""><?php echo $amendmentDetail['staff_name']; ?></a></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="acordian_heading">Booking Details</span>

                                    </button>
                                    <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="tts-holiday-review-details">
                                                        <div class="my-3">
                                                            <ul class="d-flex align-items-center justify-content-between">
                                                                <li>
                                                                    <h6>Package</h6>
                                                                    <h6><?php echo $amendmentDetail['package_name'] ?> </h6>

                                                                </li>

                                                                <li>
                                                                    <h6>Category</h6>
                                                                    <h6><?php echo $amendmentDetail['package_category'] ?> </h6>
                                                                </li>
                                                                <li>
                                                                    <h6>Travel Date</h6>
                                                                    <h6><?php echo $amendmentDetail['travel_date'] ?> </h6>
                                                                </li>
                                                                <li>
                                                                    <h6>Duration</h6>
                                                                    <h6><?php echo $night = $amendmentDetail['day_nights']; ?></h6>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                                                    <div class="table-responsive  travelimp__thanku--responsivewrap">
                                                        <h6>Passenger Details</h6>
                                                        <table class="table table-bordered table-hover travelimp__thanku--tablewrap">
                                                            <thead class="table-active">
                                                                <tr>
                                                                    <th>Sr.</th>
                                                                    <th>Name</th>
                                                                    <th>Pax Type</th>
                                                                    <th>Pan</th>
                                                                    <th>Passport</th>
                                                                    <th>Age</th>
                                                                    <th>Child Need Bed</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if ($amendmentDetail['travelersInfo']) {
                                                                    $travelersInfo = json_decode($amendmentDetail['travelersInfo'], true);
                                                                    foreach ($travelersInfo as $key => $traveller) {
                                                                        if ($traveller['lead_pax'] == 1) {
                                                                            $amendmentDetail['email'] = $traveller['email_id'];
                                                                            $amendmentDetail['mobile_no'] = $traveller['mobile_number'];
                                                                        }
                                                                ?>
                                                                        <tr>
                                                                            <td><?php echo $key + 1; ?></td>
                                                                            <td>
                                                                                <?php echo $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $traveller['pax_type']; ?>
                                                                            </td>
                                                                            <td><?php echo $traveller['pan_number']; ?></td>
                                                                            <td><?php echo $traveller['passport_number']; ?></td>
                                                                            <td><?php if ($traveller['pax_type'] == "Child" || $traveller['pax_type'] == "Infant") echo $traveller['age']; ?></td>
                                                                            <td><?php echo $traveller['child_need_bed']; ?></td>
                                                                        </tr>
                                                                <?php }
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        <span class="acordian_heading">Payment Process</span>
                                        <span class="ball__mainwrapper"><span class="ball__border info_length-green"><span class="numbering-section"><?php echo count($amendmentDetail['paymentInfo']); ?></span></span></span>
                                    </button>
                                    <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="table-active">
                                                                <tr>
                                                                    <th>S. No.</th>
                                                                    <th>Booking Ref. No.</th>
                                                                    <th>Invoice No.</th>
                                                                    <th>Credit Note No.</th>
                                                                    <th>Date</th>
                                                                    <th>Payments Type/Transaction id</th>
                                                                    <th>Debit</th>
                                                                    <th>Credit</th>
                                                                    <th>Balance</th>
                                                                    <th>Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <?php
                                                                if (!empty($amendmentDetail['paymentInfo']) && is_array($amendmentDetail['paymentInfo'])) {
                                                                    foreach ($amendmentDetail['paymentInfo'] as $key => $data) {
                                                                        $extra_parm = json_decode($data['extra_param'], true);
                                                                ?>
                                                                        <tr>
                                                                            <td><?= $key + 1 ?></td>
                                                                            <td> <?php
                                                                                    if (isset($extra_parm['booking_ref_number'])) {
                                                                                        echo '<a href="' . site_url('holiday/holiday-booking-details/' . $extra_parm['booking_ref_number']) . '">' . $extra_parm['booking_ref_number'] . '</a>';
                                                                                    } else {
                                                                                        echo '------';
                                                                                    }

                                                                                    ?></td>
                                                                            <td><?php echo ($data['action_type'] == "booking") ? $data['invoice_number'] : '-'; ?></td>
                                                                            <td><?php echo ($data['action_type'] == "refund") ? $data['invoice_number'] : '-'; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                echo date_created_format($data['created']);
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                $transaction_id = '';

                                                                                $payment_mode = str_replace("_", " ", $data['payment_mode']);
                                                                                if (isset($bookingDetail['paymentInfo']) && ($data['action_type'] == "recharge")) {

                                                                                    $transaction_id = "/" . $bookingDetail['paymentInfo'][$key]['transaction_id'];
                                                                                }





                                                                                echo $payment_mode != "" ? "<b></b> " . $payment_mode . $transaction_id . "<br/>" : ""; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo custom_money_format($data['debit']); ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo custom_money_format($data['credit']); ?>
                                                                            </td>
                                                                            <td><?php echo custom_money_format(round_value($data['balance'])); ?></td>

                                                                            <td>
                                                                                <a href="javascript:void(0);" view-data-modal="true" data-controller='webpartneraccounts' data-href="<?php echo site_url('/' . 'agent/view-remark/') . dev_encode($data['id']) ?>"><i class="fa-solid fa-eye"></i> View</a>
                                                                            </td>
                                                                        </tr>
                                                                <?php }
                                                                } else {
                                                                    echo "<tr> <td colspan='6' class='text_center'><b>No Data Found</b></td></tr>";
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="acordian_heading">Fare Breakup </span>

                                    </button>
                                    <div id="collapseSeven" class="accordion-collapse collapse show" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="">
                                                <?php $FareBreakUp = $amendmentDetail['FareBreakUp'];
                                                if ($FareBreakUp) { ?>
                                                    <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">
                                                        <tr>
                                                            <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹ <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹ <?php echo $FareBreakUp['WebPDiscount']['Value']; ?></td>
                                                        </tr>
                                                    </table>

                                                    <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">


                                                        <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                                            <tr>
                                                                <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc"><?php echo $fare['LabelText']; ?>
                                                                    :
                                                                </td>
                                                                <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">
                                                                    ₹ <?php echo $fare['Value']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;"><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?></td>
                                                        </tr>
                                                    </table>

                                                    <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                                        <table style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">

                                                            <tr>
                                                                <th style="padding: 5px 12px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Service Charges
                                                                </th>
                                                                <th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Taxable Value
                                                                </th>
                                                                <th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    CGST
                                                                    @ <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    SGST
                                                                    @ <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th style="padding: 5px 12px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    IGST
                                                                    @<?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Total
                                                                </th>
                                                            </tr>


                                                            <tr>
                                                                <th style="padding: 5px 12px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Service Charges
                                                                </th>
                                                                <th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?></th>
                                                                <th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"><?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?></th>
                                                                <th style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?></th>
                                                                <th style="padding: 5px 12px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                                                <th style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;"> <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?></th>
                                                            </tr>

                                                        </table>
                                                <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
