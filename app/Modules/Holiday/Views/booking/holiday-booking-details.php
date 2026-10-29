<div class="page-content">
    <div class="table_title">


        <section class="cart_information">
            <?php //pr($bookingDetail);
            ?>
            <div class="container">
                <div class="sale_bar">
                    <div class="tts_row">
                        <div class="tts-col-6">
                            <h3> Holiday Booking Details (
                                <?php echo $bookingDetail['booking_ref_number']; ?>)
                            </h3>
                        </div>
                        <div class="tts-col-6 text_right">
                            <a class="btn btn-sm btn-info text-white"
                                href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token']))); ?>">Booking
                                Summary</a>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12 col-12 col-lg-12">
                        <div class="cart_info">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">

                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="acordian_heading">Cart Information :
                                            <?php echo $bookingDetail['booking_ref_number']; ?>
                                        </span>
                                    </button>

                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Ref Number :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['booking_ref_number']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amount :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;₹&nbsp;
                                                                        <?php echo $bookingDetail['total_price']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Status :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['booking_status']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Payment Status :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['payment_status']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Channel Type :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['booking_channel']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">CreatedOn :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo date_created_format($bookingDetail['created']); ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <a href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['id'], "BookingToken" => $bookingDetail['tts_search_token'])));
                                                            ; ?>" class="">Booking Summary</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking User :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;<a
                                                                            href="#" class="">
                                                                            <?php echo $bookingDetail['staff_name']; ?>
                                                                        </a></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">

                                        <span class="acordian_heading"> Amendments</span>
                                        <span class="ball__mainwrapper"><span
                                                class="ball__border info_length-green"><span class="numbering-section">
                                                    <?php echo count($amendment_list) ?>
                                                </span></span></span>
                                        <div class="cssCircle addsign" data-bs-toggle="modal"
                                            data-bs-target="#holiday-raise-amendment">
                                            <span class="cssCircle-plusdesign">+ Raise Amendments</span>
                                        </div>


                                    </button>
                                    <div id="collapseThree" class="accordion-collapse collapse show"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">

                                        <?php if ($amendment_list) {
                                            foreach ($amendment_list as $amendment) {
                                                if ($amendment['staff_name']) {
                                                    $border = "tts-amendment-partner-border";
                                                    $remark = "text-primary";
                                                } else {
                                                    $border = "tts-amendment-admin-border";
                                                    $remark = "text-success";
                                                }
                                                ?>

                                                <div class="accordion-body mb-1 cart-details-borderline <?php echo $border; ?>">
                                                    <div class="row">


                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Generation Time :
                                                                <span class="cart_info-field--detail">
                                                                    <?php echo date_created_format($amendment['created']); ?>
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Amendment Id : <span
                                                                    class="cart_info-field--detail">
                                                                    <?php echo $amendment['id']; ?>
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <?php if ($amendment['staff_name']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title">User : <span
                                                                        class="cart_info-field--detail">
                                                                        <?php echo $amendment['staff_name']; ?>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>

                                                        <div class="tts-col-3">
                                                            <p class="cart_info-field--title">Status : <span
                                                                    class="cart_info-field--detail">
                                                                    <?php echo ucfirst($amendment['amendment_status']); ?>
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <?php if ($amendment['remark_from_user']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title text-danger">Remark
                                                                    : <span class="cart_info-field--detail <?php echo $remark; ?>">
                                                                        <?php echo $amendment['remark_from_user']; ?>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($amendment['remark_from_web_partner']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title text-danger">Remark
                                                                    : <span class="cart_info-field--detail <?php echo $remark; ?>">
                                                                        <?php echo $amendment['remark_from_web_partner']; ?>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($amendment['amendment_type']) { ?>
                                                            <div class="tts-col-3">
                                                                <p class="cart_info-field--title">Type : <span
                                                                        class="cart_info-field--detail">
                                                                        <?php echo ucwords(str_replace('_', ' ', $amendment['amendment_type'])); ?>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <?php if ($amendment['admin_reply']) {
                                                    $border_admin = "tts-amendment-admin-border";
                                                    $remark_admin = "text-success";
                                                    foreach ($amendment['admin_reply'] as $amendment_reply) {
                                                        ?>
                                                        <div
                                                            class="accordion-body mb-1 cart-details-borderline <?php echo $border_admin; ?>">
                                                            <div class="row">


                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Generation Time :
                                                                        <span class="cart_info-field--detail">
                                                                            <?php echo date_created_format($amendment_reply['created']); ?>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Amendment Id :
                                                                        <span class="cart_info-field--detail">
                                                                            <?php echo $amendment['id']; ?>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <?php if ($amendment_reply['staff_name']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title">User : <span
                                                                                class="cart_info-field--detail">
                                                                                <?php echo $amendment_reply['staff_name']; ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <div class="tts-col-3">
                                                                    <p class="cart_info-field--title">Status : <span
                                                                            class="cart_info-field--detail">
                                                                            <?php echo ucfirst($amendment_reply['amendment_status']); ?>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <?php if ($amendment_reply['remark_from_web_partner']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title text-danger">
                                                                            Remark
                                                                            : <span
                                                                                class="cart_info-field--detail <?php echo $remark_admin; ?>">
                                                                                <?php echo $amendment_reply['remark_from_web_partner']; ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <?php if ($amendment_reply['remark_from_super_admin']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title text-danger">
                                                                            Remark
                                                                            : <span
                                                                                class="cart_info-field--detail <?php echo $remark_admin; ?>">
                                                                                <?php echo $amendment_reply['remark_from_super_admin']; ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>

                                                                <?php if ($amendment_reply['amendment_type']) { ?>
                                                                    <div class="tts-col-3">
                                                                        <p class="cart_info-field--title">Type : <span
                                                                                class="cart_info-field--detail">
                                                                                <?php echo ucwords(str_replace('_', ' ', $amendment_reply['amendment_type'])); ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                        <span class="acordian_heading">Booking Details</span>

                                    </button>
                                    <div id="collapseFour" class="accordion-collapse collapse show"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="tts-holiday-review-details">
                                                        <div class="my-3">
                                                            <ul
                                                                class="d-flex align-items-center justify-content-between">
                                                                <li>
                                                                    <h6>Package</h6>
                                                                    <h6>
                                                                        <?php echo $bookingDetail['package_details']['Name'] ?>
                                                                    </h6>

                                                                </li>

                                                                <li>
                                                                    <h6>Category</h6>
                                                                    <h6>
                                                                        <?php echo $bookingDetail['package_details']['HolidayCategory'] ?>
                                                                    </h6>
                                                                </li>
                                                                <li>
                                                                    <h6>Travel Date</h6>
                                                                    <h6>
                                                                        <?php echo $bookingDetail['package_details']['TravelDate'] ?>
                                                                    </h6>
                                                                </li>
                                                                <li>
                                                                    <h6>Duration</h6>
                                                                    <h6>
                                                                        <?php echo $night = $bookingDetail['package_details']['DaysNights']; ?>
                                                                    </h6>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="col-md-12 travelimp__thanku--panelHeadwrap">
                                                    <div class="table-responsive travelimp__thanku--responsivewrap">
                                                        <h6>Passenger Details</h6>
                                                        <table
                                                            class="table table-bordered travelimp__thanku--tablewrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr.</th>
                                                                    <th>Name</th>
                                                                    <th>Pax Type</th>
                                                                    <th>Pan</th>
                                                                    <th>Passport</th>
                                                                    <!-- <th>Age</th> -->
                                                                    <th>Child Need Bed</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if ($bookingDetail['travellers']) {
                                                                    $travellers = json_decode($bookingDetail['travellers'], true);
                                                                    foreach ($travellers as $key => $traveller) {
                                                                        if ($traveller['lead_pax'] == 1) {
                                                                            $bookingDetail['email'] = $traveller['email_id'];
                                                                            $bookingDetail['mobile_no'] = $traveller['mobile_number'];
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $key + 1; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $traveller['title'] . ' ' . $traveller['first_name'] . ' ' . $traveller['last_name'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $traveller['pax_type']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $traveller['pan_number']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $traveller['passport_number']; ?>
                                                                            </td>
                                                                            <!-- <td>
                                                                                <?php //if ($traveller['pax_type'] == "Child" || $traveller['pax_type'] == "Infant")
                                                                                        //echo $traveller['age'];  ?>
                                                                            </td> -->
                                                                            <td>
                                                                                <?php echo $traveller['child_need_bed']; ?>
                                                                            </td>
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
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                        <span class="acordian_heading">Payment Process</span>
                                        <span class="ball__mainwrapper"><span
                                                class="ball__border info_length-green"><span class="numbering-section">
                                                    <?php echo count($bookingDetail['paymentInfo']); ?>
                                                </span></span></span>
                                    </button>
                                    <div id="collapseFive" class="accordion-collapse collapse show"
                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered ">
                                                            <thead>
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
                                                                if (!empty($bookingDetail['paymentInfo']) && is_array($bookingDetail['paymentInfo'])) {
                                                                    foreach ($bookingDetail['paymentInfo'] as $key => $data) {
                                                                        $extra_parm = json_decode($data['extra_param'], true);
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?= $key + 1 ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php
                                                                                if (isset($extra_parm['booking_ref_number'])) {
                                                                                    echo '<a href="' . site_url('holiday/holiday-booking-details/' . $extra_parm['booking_ref_number']) . '">' . $extra_parm['booking_ref_number'] . '</a>';
                                                                                } else {
                                                                                    echo '------';
                                                                                }

                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo ($data['action_type'] == "booking") ? $data['invoice_number'] : '-'; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo ($data['action_type'] == "refund") ? $data['invoice_number'] : '-'; ?>
                                                                            </td>
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
                                                                            <td>
                                                                                <?php echo custom_money_format(round_value($data['balance'])); ?>
                                                                            </td>

                                                                            <td>
                                                                                <a href="javascript:void(0);"
                                                                                    view-data-modal="true"
                                                                                    data-controller='webpartneraccounts'
                                                                                    data-href="<?php echo site_url('/' . 'agent/view-remark/') . dev_encode($data['id']) ?>"><i
                                                                                        class="fa-solid fa-eye"></i> View</a>
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
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSeven" aria-expanded="false"
                                        aria-controls="collapseSeven">
                                        <span class="acordian_heading">Fare Breakup </span>

                                    </button>
                                    <div id="collapseSeven" class="accordion-collapse collapse show"
                                        aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="">
                                                <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                                                if ($FareBreakUp) { ?>
                                                    <table
                                                        style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">
                                                        <tr>
                                                            <td
                                                                style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;">
                                                                <?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td
                                                                style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹
                                                                <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;">
                                                                <?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td
                                                                style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹
                                                                <?php echo $FareBreakUp['WebPDiscount']['Value']; ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <table
                                                        style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;margin-bottom:20px;">


                                                        <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
                                                            <tr>
                                                                <td
                                                                    style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc">
                                                                    <?php echo $fare['LabelText']; ?>
                                                                    :
                                                                </td>
                                                                <td
                                                                    style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc">
                                                                    ₹
                                                                    <?php echo $fare['Value']; ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <td
                                                                style="  padding: 10px; text-align: left; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight: bold;">
                                                                <?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                                                :
                                                            </td>
                                                            <td
                                                                style="  padding: 10px; text-align: right; font-weight: normal; font-size:13px; border:1px solid #ccc; font-weight:bold;">
                                                                ₹
                                                                <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                                        <table
                                                            style=" text-align: left;  width:100%; border-collapse: collapse; border: 1px solid #ddd;">

                                                            <tr>
                                                                <th
                                                                    style="padding: 5px 12px;width:32%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Service Charges
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Taxable Value
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    CGST
                                                                    @
                                                                    <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px;width:15%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    SGST
                                                                    @
                                                                    <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px;width:13%; border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    IGST
                                                                    @
                                                                    <?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Total
                                                                </th>
                                                            </tr>


                                                            <tr>
                                                                <th
                                                                    style="padding: 5px 12px; width:32%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    Service Charges
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    <?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?>
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    <?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?>
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:15%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?>
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:13%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                                                </th>
                                                                <th
                                                                    style="padding: 5px 12px; width:10%;border-right: 1px solid #C7C7C7;border-bottom: 1px solid #C7C7C7;text-align: center;">
                                                                    <?php echo $FareBreakUp['GSTDetails']['CGSTAmount'] + $FareBreakUp['GSTDetails']['SGSTAmount'] + $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                                                </th>
                                                            </tr>

                                                        </table>
                                                    <?php }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        <span class="acordian_heading">User Information </span>
                                    </button>
                                    <div id="collapseSix" class="accordion-collapse collapse"
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="amend_details-passengers--list">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Contact's Email:<span
                                                                    class="cart_info-field--detail">
                                                                    <span>
                                                                        <?php echo $bookingDetail['email']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Pax contact:<span
                                                                    class="cart_info-field--detail"><span>
                                                                        <?php echo $bookingDetail['mobile_no']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
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


<div class="modal fade" id="holiday-raise-amendment" tabindex="-1" aria-labelledby="holiday-raise-amendmentLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="holiday-raise-amendmentLabel">AMENDMENTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('holiday/raise-amendment'); ?>" method="post" tts-form="true"
                name="hotel-raise-amendment">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Amendment Type</label>
                        <select class="form-select" name="amendment_type">
                            <option value="">Amendment Type</option>
                            <?php if (strtotime($bookingDetail['package_details']['TravelDate']) >= strtotime(date('Y-m-d'))) { ?>
                                <option value="cancellation">Cancellation</option>
                            <?php } ?>
                            <option value="full_refund">Full Refund</option>
                        </select>
                        <input type="hidden" name="booking_ref_number"
                            value="<?php echo $bookingDetail['booking_ref_number']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Remark:</label>
                        <textarea class="form-control" name="remark" rows="3" cols="15"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Raise</button>
                </div>
            </form>
        </div>
    </div>
</div>