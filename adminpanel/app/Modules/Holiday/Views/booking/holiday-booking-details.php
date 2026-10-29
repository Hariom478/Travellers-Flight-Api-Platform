<div class="page-content">
    <div class="table_title">
        <section class="cart_information p0">
            <?php //pr($bookingDetail);
            ?>
            <div class="container-fluid p0">
                <div class="sale_bar">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-lg-0">
                            <h5 class="m0"> Holiday Booking Details (
                                <?php echo $bookingDetail['booking_ref_number']; ?>
                                )
                            </h5>
                        </div>
                        <div class="col-md-8 text-end">
                            <a class="badge badge-wt"
                                href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['Booking_id'], "BookingToken" => $bookingDetail['tts_search_token']))); ?>">Booking
                                Summary</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12 col-lg-12">
                        <div class="cart_info">
                            <div class="accordion" id="accordionExample">

                                <?php if (isset($amendments_details) && $amendments_details == true) { ?>
                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseamendments" aria-expanded="true"
                                            aria-controls="collapseamendments">
                                            <span class="acordian_heading">Amendment Information :
                                                <?php echo $bookingDetail['id']; ?>
                                            </span>
                                        </button>
                                        <div id="collapseamendments" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body cart-details-borderline">

                                                <div class="row">
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Id
                                                                :<span class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['id']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Status :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;&nbsp;
                                                                        <?php echo ucfirst($bookingDetail['amendment_status']); ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amendment Type
                                                                :<span class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo ucfirst($bookingDetail['amendment_type']); ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Remark From User:<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['remark_from_user']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Remark From
                                                                Company :<span class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $bookingDetail['remark_from_web_partner']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Created On :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo date_created_format($bookingDetail['created']); ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseamendmentDetail" aria-expanded="true"
                                            aria-controls="collapseamendmentDetail">
                                            <span class="acordian_heading">Amendment Detail </span>
                                        </button>
                                        <div id="collapseamendmentDetail" class="accordion-collapse collapse show"
                                            aria-labelledby="headingamendmentDetail" data-bs-parent="#accordionExample">
                                            <div class="accordion-body cart-details-borderline">
                                                <div class="amend_details-passengers--list">
                                                    <form
                                                        action="<?php echo site_url('holiday/amendment-cancellation-charge'); ?>"
                                                        method="post" tts-form="true" name="cancellation_charge_update">
                                                        <?php
                                                        $amendment_charges = array();
                                                        $charge = 0;
                                                        $service_charge = 0;

                                                        $refund = 0;
                                                        $service_charge_gst = 0;
                                                        $TDSReturnIdentifier = "no";
                                                        $TDSReturnIdentifierChecked = "";
                                                        if ($bookingDetail['amendment_charges'] != Null) {
                                                            $amendment_charges = json_decode($bookingDetail['amendment_charges'], true);
                                                            $charge = $amendment_charges['Charge'];
                                                            $service_charge = $amendment_charges['ServiceCharge'];
                                                            $refund = $amendment_charges['Refund'];
                                                            $TDSReturnIdentifier = isset($amendment_charges['TDSReturnIdentifier']) ? $amendment_charges['TDSReturnIdentifier'] : "no";
                                                            $TDSReturnIdentifierChecked = $TDSReturnIdentifier == "yes" ? "checked" : "";
                                                            $service_charge_gst = $amendment_charges['GST']['TotalGSTAmount'];
                                                        }
                                                        if ($bookingDetail['booking_source'] == "Wl_b2b") {
                                                            $fareBreakUp = json_decode($bookingDetail['agent_fare_break_up'], true);
                                                        } else {
                                                            $fareBreakUp = json_decode($bookingDetail['customer_fare_break_up'], true);
                                                        }


                                                        $offeredFare = $fareBreakUp['OfferedPrice'];



                                                        $publishedFare = $fareBreakUp['PublishedPrice'];

                                                        $CommEarned = $fareBreakUp['AgentCommission'];

                                                        $Discount = $fareBreakUp['Discount'];

                                                        $TDS = $fareBreakUp['TDS'];
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-sm-12 passenger_faredetail">
                                                                <div class="row">

                                                                    <div
                                                                        class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                        <p class="mg_right-50">Offered Fare</p>
                                                                        <p class="price-width-left " id="offeredFare"
                                                                            agentOfferedFare="<?php echo $offeredFare; ?>">
                                                                            ₹
                                                                            <?php echo $offeredFare; ?>
                                                                        </p>
                                                                    </div>

                                                                    <div
                                                                        class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                        <p class="mg_right-50">Published Fare</p>
                                                                        <p class="price-width-left " id="publishedFare">
                                                                            ₹
                                                                            <?php echo $publishedFare; ?>
                                                                        </p>
                                                                    </div>


                                                                    <div
                                                                        class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                        <p class="mg_right-50">Agent
                                                                            Commission
                                                                        </p>
                                                                        <p class="price-width-left " id="agent_commission"
                                                                            AgentCommission="<?php echo $CommEarned; ?>">
                                                                            ₹
                                                                            <?php echo $CommEarned; ?>
                                                                        </p>
                                                                    </div>



                                                                    <div
                                                                        class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                        <p class="mg_right-50">Discount

                                                                        </p>
                                                                        <p class="price-width-left " id="discount"
                                                                            AgentCommission="<?php echo $Discount; ?>">
                                                                            ₹
                                                                            <?php echo $Discount; ?>
                                                                        </p>
                                                                    </div>



                                                                    <!--<div class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                      <p class="mg_right-50">GST Amount</p>
                                                      <p class="price-width-left text-right"
                                                         id="airline_gst_amount"
                                                         airlineGSTAmount="<?php /*echo $GSTAmount; */ ?>">
                                                          ₹ <?php /*echo $GSTAmount; */ ?></p>
                                                      </div>
                                                      -->

                                                                    <div
                                                                        class="col-sm-2 col-xs-6 col-6 padd-left-amendment">
                                                                        <p class="mg_right-50">TDS</p>
                                                                        <p class="price-width-left " id="tds"
                                                                            TDS="<?php echo $TDS; ?>">
                                                                            ₹
                                                                            <?php echo $TDS; ?>
                                                                        </p>
                                                                    </div>


                                                                    <input type="hidden" name="amendment_id"
                                                                        value="<?php echo dev_encode($bookingDetail['id']); ?>">
                                                                    <?php if ($bookingDetail['amendment_type'] == "cancellation" || $bookingDetail['amendment_type'] == "full_refund") {
                                                                        ?>

                                                                        <div
                                                                            class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                            <div class="form-group row">
                                                                                <label for="charge"
                                                                                    class="col-sm-6 col-form-label">Cancellation
                                                                                    Charge</label>
                                                                                <div class="col-sm-6">
                                                                                    <input class="form-control" type="text"
                                                                                        name="charge"
                                                                                        value="<?php echo $charge; ?>"
                                                                                        id="charge"
                                                                                        oninput='getFlightRefundCharges(event)'>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="col-sm-4 col-xs-6 col-6 padd-left-amendment">


                                                                            <div class="form-group row">
                                                                                <label for="service_charge"
                                                                                    class="col-sm-6 col-form-label">Cancellation
                                                                                    Service Charge</label>
                                                                                <div class="col-sm-6">
                                                                                    <input class="form-control" type="text"
                                                                                        name="service_charge"
                                                                                        value="<?php echo $service_charge; ?>"
                                                                                        id="service_charge"
                                                                                        oninput='getFlightRefundCharges(event)'>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <div
                                                                            class="col-sm-4 col-xs-6 col-6 padd-left-amendment">

                                                                            <div class="form-group row">
                                                                                <label for="service_charge_gst"
                                                                                    class="col-sm-6 col-form-label">Cancellation
                                                                                    Charge GST</label>
                                                                                <div class="col-sm-6">
                                                                                    <input class="form-control" type="text"
                                                                                        name="service_charge_gst"
                                                                                        value="<?php echo $service_charge_gst; ?>"
                                                                                        id="service_charge_gst" readonly>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div
                                                                            class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                            <div class="form-group row">
                                                                                <label for="refund"
                                                                                    class="col-sm-6 col-form-label">Refund
                                                                                    Amount</label>
                                                                                <div class="col-sm-6">
                                                                                    <input class="form-control" type="text"
                                                                                        name="refund"
                                                                                        value="<?php echo $refund; ?>"
                                                                                        id="refund" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="col-sm-4 col-xs-6 col-6 padd-left-amendment">
                                                                            <label
                                                                                class="price-width-left text-right form-check-label"><input
                                                                                    class="form-check-input" type="checkbox"
                                                                                    name="tdsreturn" value="yes" id="tdsreturn"
                                                                                    onclick='getFlightRefundCharges(event)'
                                                                                    <?php echo $TDSReturnIdentifierChecked; ?>>TDS
                                                                                Return</label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($bookingDetail['refund_status'] != "Close" && ($bookingDetail['amendment_type'] == "cancellation" || $bookingDetail['amendment_type'] == "full_refund")) { ?>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button class="btn btn-info pull-right" type="submit">
                                                                        Update
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                <?php } ?>

                                <div class="accordion-item">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span class="acordian_heading">Booking Basic Detail</span>
                                    </button>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">

                                            <div class="row">
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Booking Ref Number :<span
                                                                class="cart_info-field--detail"><span> &nbsp;
                                                                    <?php echo $bookingDetail['booking_ref_number']; ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Amount :<span
                                                                class="cart_info-field--detail"><span> &nbsp;₹&nbsp;
                                                                    <?php echo $bookingDetail['total_price']; ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Booking Status :<span
                                                                class="cart_info-field--detail"><span> &nbsp;
                                                                    <?php echo $bookingDetail['booking_status']; ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Payment Status :<span
                                                                class="cart_info-field--detail"><span> &nbsp;
                                                                    <?php echo $bookingDetail['payment_status']; ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Channel Type :<span
                                                                class="cart_info-field--detail"><span> &nbsp;
                                                                    <?php echo $bookingDetail['booking_channel']; ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">CreatedOn :<span
                                                                class="cart_info-field--detail"><span> &nbsp;
                                                                    <?php echo date_created_format($bookingDetail['created']); ?>
                                                                </span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <a href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $bookingDetail['Booking_id'], "BookingToken" => $bookingDetail['tts_search_token'])));
                                                        ; ?>"
                                                            class="">Booking Summary</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Booking User :<span
                                                                class="cart_info-field--detail"><span> &nbsp;<a href="#"
                                                                        class="">
                                                                        <?php echo ($bookingDetail['booking_source'] == 'Wl_b2c') ? $bookingDetail['customer_name'] : $bookingDetail['staff_name']; ?>
                                                                    </a></span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 col-6">
                                                    <div class="cart_info-field">
                                                        <p class="cart_info-field--title">Booking Source :<span
                                                                class="cart_info-field--detail"><span> &nbsp;<a href="#"
                                                                        class="">
                                                                        <?php echo service_booking_source($bookingDetail['booking_source']); ?>
                                                                    </a></span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <?php if (isset($bookingDetail['AgentInfo']) && !empty($bookingDetail['AgentInfo'])) {
                                    $AgentInfo = $bookingDetail['AgentInfo'];
                                    ?>
                                    <div class="accordion-item">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseAgentInfo" aria-expanded="true"
                                            aria-controls="collapseAgentInfo">
                                            <span class="acordian_heading">Agent Info </span>
                                        </button>
                                        <div id="collapseAgentInfo" class="accordion-collapse collapse show"
                                            aria-labelledby="collapseWebPAgentInfo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Company name :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $AgentInfo['company_name']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Company Id
                                                                :<span class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $AgentInfo['company_id']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-3">
                           <div class="cart_info-field">
                              <p class="cart_info-field--title">Agent Name
                                 :<span class="cart_info-field--detail"><span> &nbsp;<?php echo $AgentInfo['first_name'] . " " . $AgentInfo['last_name']; ?></span></span>
                              </p>
                           </div>
                        </div> -->
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Email :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $AgentInfo['login_email']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Mobile Number :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $AgentInfo['mobile_no']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (isset($bookingDetail['CustomerInfo']) && !empty($bookingDetail['CustomerInfo'])) {
                                    $CustomerInfo = $bookingDetail['CustomerInfo']; ?>
                                    <div class="accordion-item">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCustomerInfo" aria-expanded="true"
                                            aria-controls="collapseCustomerInfo">
                                            <span class="acordian_heading">Customer Info </span>
                                        </button>
                                        <div id="collapseCustomerInfo" class="accordion-collapse collapse show"
                                            aria-labelledby="collapseWebPCustomerInfo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title"> Customer ID :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $CustomerInfo['customer_id']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Customer Name
                                                                :<span class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $CustomerInfo['first_name'] . " " . $CustomerInfo['last_name']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Email :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $CustomerInfo['email_id']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Mobile Number :<span
                                                                    class="cart_info-field--detail"><span> &nbsp;
                                                                        <?php echo $CustomerInfo['mobile_no']; ?>
                                                                    </span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
                                                                            <!-- <td><?php //if ($traveller['pax_type'] == "Child" || $traveller['pax_type'] == "Infant") echo $traveller['age'];  ?></td> -->
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        <span class="acordian_heading">User Information </span>
                                    </button>
                                    <div id="collapseSix" class="accordion-collapse collapse show"
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">

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
                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
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
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="table-active">
                                                                <tr>
                                                                    <th>Sl.No.</th>
                                                                    <th>Booking Ref. No.</th>
                                                                    <th>Invoice No.</th>
                                                                    <th>Credit Note No.</th>
                                                                    <th>Date</th>
                                                                    <th>Payments Type/Transaction id</th>
                                                                    <th>Debit</th>
                                                                    <th>Credit</th>
                                                                    <th>Balance</th>
                                                                    <th style="white-space:unset">Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (!empty($bookingDetail['paymentInfo']) && is_array($bookingDetail['paymentInfo'])) {
                                                                    foreach ($bookingDetail['paymentInfo'] as $key => $data) {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $key + 1; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php $extra_parm = json_decode($data['extra_param'], true);
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
                                                                            <td style="white-space:unset">
                                                                                <?php $controller = ($bookingDetail['booking_source'] == "Wl_b2b") ? 'agent' : 'customer' ?>
                                                                                <a href="javascript:void(0);"
                                                                                    view-data-modal="true"
                                                                                    data-controller='webpartneraccounts'
                                                                                    data-href="<?php echo site_url('/' . $controller . '/view-remark/') . dev_encode($data['id']) ?>"><i
                                                                                        class="fa-solid fa-eye"></i> View</a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php }
                                                                } else {
                                                                    echo "<tr> <td colspan='10' class='text-center'><b>No Data Found</b></td></tr>";
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseSeven" aria-expanded="false"
                                        aria-controls="collapseSeven">
                                        <span class="acordian_heading">Fare Breakup </span>
                                    </button>
                                    <div id="collapseSeven" class="accordion-collapse collapse show"
                                        aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body cart-details-borderline">
                                            <div class="table-responsive">
                                                <?php $FareBreakUp = $bookingDetail['FareBreakUp'];
                                                if ($FareBreakUp) { ?>
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th scope="row">
                                                                <?php echo $FareBreakUp['WebPMarkUp']['LabelText']; ?>
                                                                :
                                                            </th>
                                                            <td>₹
                                                                <?php echo $FareBreakUp['WebPMarkUp']['Value']; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php echo $FareBreakUp['WebPDiscount']['LabelText']; ?>
                                                                :
                                                            </th>
                                                            <td>₹
                                                                <?php echo $FareBreakUp['WebPDiscount']['Value']; ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <?php foreach ($FareBreakUp['FareBreakup'] as $fare) {
                                                            ?>
                                                            <tr>
                                                                <th scope="row">
                                                                    <?php echo $fare['LabelText']; ?>:
                                                                </th>
                                                                <td>₹
                                                                    <?php echo $fare['Value']; ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>
                                                                :
                                                            </th>
                                                            <th scope="row">
                                                                ₹
                                                                <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="table-responsive">
                                                    <?php if (isset($FareBreakUp['GSTDetails']) && $FareBreakUp['GSTDetails']) { ?>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Service Charges</th>
                                                                <th>Taxable Value</th>
                                                                <th>
                                                                    CGST@
                                                                    <?php echo $FareBreakUp['GSTDetails']['CGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th>
                                                                    SGST@
                                                                    <?php echo $FareBreakUp['GSTDetails']['SGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th>
                                                                    IGST@
                                                                    <?php echo $FareBreakUp['GSTDetails']['IGSTRate']; ?>
                                                                    %
                                                                </th>
                                                                <th>Total</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Service Charges</th>
                                                                <th>
                                                                    <?php echo $FareBreakUp['GSTDetails']['TaxableAmount']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $FareBreakUp['GSTDetails']['CGSTAmount']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $FareBreakUp['GSTDetails']['SGSTAmount']; ?>
                                                                </th>
                                                                <th>
                                                                    <?php echo $FareBreakUp['GSTDetails']['IGSTAmount']; ?>
                                                                </th>
                                                                <th>
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
                                    <div id="" class="accordion-collapse collapse show"
                                        aria-labelledby="panelsStayOpen-headingsix">
                                        <?php if (isset($UpdateVoucher) && $UpdateVoucher) { ?>
                                            <form name="web-partner" tts-form='true'
                                                action="<?php echo site_url('holiday/holiday-update-voucher-info'); ?>"
                                                method="POST" id="hotel-update-Voucher">
                                                <div class="row ">
                                                    <div class="col-md-12">
                                                        <h6 class="tts-flight-upload-border"></h6>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Issue Supplier *</label>
                                                            <select class="form-select" name="supplier">
                                                                <option value="" selected>Select</option>
                                                                <?php if ($hotelSupplier) {
                                                                    foreach ($hotelSupplier as $supplier) { ?>
                                                                        <option value="<?php echo $supplier['supplier_name']; ?>"
                                                                            <?php if (isset($bookingDetail['issue_supplier']) && strtolower($bookingDetail['issue_supplier']) == strtolower($supplier['supplier_name'])) {
                                                                                echo "selected";
                                                                            } ?>>
                                                                            <?php echo $supplier['supplier_name']; ?>
                                                                        </option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Booking Status *</label>
                                                            <select class="form-select" name="booking_status"
                                                                placeholder="Booking Status">
                                                                <option value="Confirmed" <?php echo $bookingDetail['booking_status'] == "Confirmed" ? "selected" : ""; ?>>
                                                                    Confirmed
                                                                </option>
                                                                <option value="Cancelled" <?php echo $bookingDetail['booking_status'] == "Cancelled" ? "selected" : ""; ?>>
                                                                    Cancelled
                                                                </option>
                                                                <option value="Processing" <?php echo $bookingDetail['booking_status'] == "Processing" ? "selected" : ""; ?>>
                                                                    Processing
                                                                </option>
                                                                <option value="Hold" <?php echo $bookingDetail['booking_status'] == "Hold" ? "selected" : ""; ?>>
                                                                    Hold
                                                                </option>
                                                                <option value="Failed" <?php echo $bookingDetail['booking_status'] == "Failed" ? "selected" : ""; ?>>
                                                                    Failed
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Payment Status *</label>
                                                            <select class="form-select" name="payment_status"
                                                                placeholder="Payment Status">
                                                                <option value="Successful" <?php echo $bookingDetail['payment_status'] == "Successful" ? "selected" : ""; ?>>
                                                                    Successful
                                                                </option>
                                                                <?php if ($bookingDetail['payment_status'] != "Successful") { ?>
                                                                    <option value="Failed" <?php echo $bookingDetail['payment_status'] == "Failed" ? "selected" : ""; ?>>
                                                                        Failed
                                                                    </option>
                                                                    <option value="Processing" <?php echo $bookingDetail['payment_status'] == "Processing" ? "selected" : ""; ?>>
                                                                        Processing
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Confirmation Number *</label>
                                                            <input class="form-control" name="confirmation_number"
                                                                placeholder="Confirmation Number"
                                                                value="<?php echo ($bookingDetail['confirmation_no']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="yes"
                                                                    name="refundbookingamount">Refund
                                                                Booking Amount
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" value="yes"
                                                                    name="deductbookingamount" <?php if ($bookingDetail['payment_status'] != "Successful") {
                                                                        echo "checked";
                                                                    } ?>>Deduct Booking Amount
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="hotel_booking_id"
                                                        value="<?php echo dev_encode($bookingDetail['Booking_id']); ?>">
                                                    <input type="hidden" name="booking_ref_number"
                                                        value="<?php echo dev_encode($bookingDetail['booking_ref_number']); ?>">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Note</label>
                                                            <textarea class="form-control" name="remark" placeholder="Note"
                                                                rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-md-right">
                                                        <button class="btn btn-primary" type="submit">Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php } ?>
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
<div id="holiday-raise-amendment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AMENDMENTS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo site_url('holiday/raise-amendment'); ?>" method="post" tts-form="true"
                name="form_change_status">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-select" name="amendment_status">
                                    <option value="">Amendment Status</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="success">Success</option>
                                </select>
                            </div>
                            <input type="hidden" name="booking_ref_number"
                                value="<?php echo $bookingDetail['booking_ref_number']; ?>">
                            <input type="hidden" name="amendment_id" id="amendment-id">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark</label>
                                <textarea class="form-control" name="remark" rows="3" cols="15"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" value="Save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function getFlightRefundCharges(evt) {
        var flightgst = 18;
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        /*  if ((charCode > 31) && (charCode <= 48 || charCode > 57)) { */
        var charge = parseFloat(document.getElementById("charge").value);
        var serviceCharge = parseFloat(document.getElementById("service_charge").value);




        var tds = 0;
        if ($("#tdsreturn").prop('checked') == true) {
            var tds = parseFloat(document.getElementById("tds").getAttribute('TDS'));
        }
        var offeredFare = parseFloat(document.getElementById("offeredFare").getAttribute('agentOfferedFare'));

        var agent_commission = parseFloat(document.getElementById("agent_commission").getAttribute('AgentCommission'));
        var agent_discount = parseFloat(document.getElementById("discount").getAttribute('Discount'));


        var TotalpaxFare = parseFloat((offeredFare + tds)).toFixed(2);
        var serviceChargeGst = calculate_hotel_gst(serviceCharge, flightgst);
        var totalRefundAmount = parseFloat((charge + serviceCharge + serviceChargeGst));



        var refund = (TotalpaxFare - totalRefundAmount).toFixed(2);

        if (!isNaN(serviceChargeGst)) {
            document.getElementById("service_charge_gst").value = serviceChargeGst;
        } else {
            document.getElementById("service_charge_gst").value = 0;
        }
        if (!isNaN(refund)) {
            if (refund < 0) {
                $("[data-message]").addClass('error_popup').html("Please check refund amount value is negative.");
            } else {
                $("[data-message]").removeClass('error_popup').html("");
            }
            document.getElementById("refund").value = parseFloat(refund);
        } else {
            document.getElementById("refund").value = 0;
        }
        /* } */
    }

    function calculate_hotel_gst(serviceCharge, flightgst) {
        var returnval = Math.round(((serviceCharge * flightgst) / 100), 2);
        return returnval;
    }
</script>