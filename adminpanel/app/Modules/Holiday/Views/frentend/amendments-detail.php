<style>

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
</style>
<div class="page-content">
    <div class="table_title">


        <section class="cart_information p-0"> <?php //pr($amendmentDetail);?>
            <div class="container-fluid">
                <div class="sale_bar">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="m-0"> Amendment Details (<?php echo $amendmentDetail['id']; ?>)</h5>
                        </div>
                        <div class="col-md-8 text-md-end">
                            <a class="badge badge-wt"
                               href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $amendmentDetail['Booking_id'], "BookingToken" => $amendmentDetail['tts_search_token']))); ?>">Booking
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
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseamendment" aria-expanded="true"
                                                aria-controls="collapseamendment">
                                            <span class="acordian_heading">Amendment Information : <?php echo $amendmentDetail['id']; ?></span>
                                        </button>
                                        <div id="collapseamendment" class="accordion-collapse collapse show"
                                             aria-labelledby="headingamendment" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Id :<span
                                                                            class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['id']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Status :<span
                                                                            class="cart_info-field--detail"><span> &nbsp;&nbsp;<?php echo ucfirst($amendmentDetail['amendment_status']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Amendment Type :<span
                                                                            class="cart_info-field--detail"><span> &nbsp;<?php echo ucfirst($amendmentDetail['amendment_type']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Remark From Agent:<span
                                                                            class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['remark_from_web_partner']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">Remark From Company :<span
                                                                            class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['remark_from_super_admin']; ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 col-6">
                                                            <div class="cart_info-field">
                                                                <p class="cart_info-field--title">CreatedOn :<span
                                                                            class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['created']); ?></span></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>











                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        <span class="acordian_heading">Cart Information : <?php echo $amendmentDetail['booking_ref_number']; ?></span>
                                    </button>

                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                         aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Ref Number :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_ref_number']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Amount :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;₹&nbsp;<?php echo $amendmentDetail['total_price']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking Status :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_status']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Payment Status :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['payment_status']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Channel Type :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<?php echo $amendmentDetail['booking_channel']; ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">CreatedOn :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<?php echo date_created_format($amendmentDetail['booking_created']); ?></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <a href="<?php echo site_url('/holiday/confirmation/') . $ticketData = dev_encode(json_encode(array("BookingId" => $amendmentDetail['Booking_id'], "BookingToken" => $amendmentDetail['tts_search_token'])));; ?>"
                                                               class="">Booking Summary</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-xs-6 col-6">
                                                        <div class="cart_info-field">
                                                            <p class="cart_info-field--title">Booking User :<span
                                                                        class="cart_info-field--detail"><span> &nbsp;<a
                                                                                href="#"
                                                                                class=""><?php echo $amendmentDetail['staff_name']; ?></a></span></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                        <span class="acordian_heading">Booking Details</span>

                                    </button>
                                    <div id="collapseFour" class="accordion-collapse collapse show"
                                         aria-labelledby="headingFour" data-bs-parent="#accordionExample">
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
                                                <hr/>
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

                                                                foreach ($amendmentDetail['travelersInfo'] as $key => $traveller) {
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                        <span class="acordian_heading">Payment Process</span>
                                        <span class="ball__mainwrapper"><span
                                                    class="ball__border info_length-green"><span
                                                        class="numbering-section"><?php echo count($amendmentDetail['paymentInfo']); ?></span></span></span>
                                    </button>
                                    <div id="collapseFive" class="accordion-collapse collapse show"
                                         aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="table-active">
                                                            <tr>
                                                                <th>Ref. Number</th>
                                                                <th>Remark</th>
                                                                <th>Credit</th>
                                                                <th>Debit</th>
                                                                <th>Type</th>
                                                                <th>Created</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php
                                                            if (!empty($amendmentDetail['paymentInfo']) && is_array($amendmentDetail['paymentInfo'])) {
                                                                foreach ($amendmentDetail['paymentInfo'] as $data) {
                                                                    ?>
                                                                    <tr>
                                                                        <td> <?php echo $data['acc_ref_number']; ?></td>
                                                                        <td><?php echo $data['remark']; ?></td>
                                                                        <td> ₹ <?php echo $data['credit']; ?></td>
                                                                        <td> ₹ <?php echo $data['debit']; ?></td>
                                                                        <td><?php echo ucfirst($data['action_type']); ?></td>
                                                                        <td>
                                                                            <?php echo date_created_format($data['created']); ?>
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


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div id="holiday-raise-amendment" class="modal fade" tabindex="1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AMENDMENTS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('holiday/raise-amendment'); ?>" method="post" tts-form="true" name="form_change_status">
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
                                   value="<?php echo $amendmentDetail['booking_ref_number']; ?>">

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
                    <button class="btn btn-primary" type="submit">Save</button>
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

