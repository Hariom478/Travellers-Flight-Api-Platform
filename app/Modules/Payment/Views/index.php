<section class="payment-section pb-4 pt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12 col-md-8">
                <div class="payment-title">
                    <h5>Payment Options</h5>
                </div>
                <div class="card">
                    <div class="card-body p-0">

                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <?php
                                $web_partner_wallet_balance = get_balance();
                                $activeWallet = 0;
                                if ($web_partner_wallet_balance > $total_price && isset(session()->get('wl_agent')['id'])) {
                                    $activeWallet = 1; ?>
                                    <button class="nav-link  <?php if ($web_partner_wallet_balance > $total_price) {
                                        echo "active";
                                    } ?>" id="my-wallet-tab" data-bs-toggle="tab" data-bs-target="#my-wallet"
                                        type="button" role="tab" aria-controls="my-wallet" aria-selected="false">My Wallet
                                    </button>
                                <?php } ?>
                                <?php if (0) { ?>
                                    <button class="nav-link" id="credit-card-tab" data-bs-toggle="pill"
                                        data-bs-target="#credit-card" type="button" role="tab" aria-controls="credit-card"
                                        aria-selected="true">Credit Card
                                    </button>

                                    <button class="nav-link" id="debit-card-tab" data-bs-toggle="pill"
                                        data-bs-target="#debit-card" type="button" role="tab" aria-controls="debit-card"
                                        aria-selected="false">Debit Card
                                    </button>

                                    <button class="nav-link" id="net-banking-tab" data-bs-toggle="pill"
                                        data-bs-target="#net-banking" type="button" role="tab" aria-controls="net-banking"
                                        aria-selected="false">Net Banking
                                    </button>

                                    <button class="nav-link" id="mob-wallet-tab" data-bs-toggle="pill"
                                        data-bs-target="#mob-wallet" type="button" role="tab" aria-controls="mob-wallet"
                                        aria-selected="false">Mobile Wallets
                                    </button>

                                    <button class="nav-link border-bottom-0" id="upi-payment-tab" data-bs-toggle="pill"
                                        data-bs-target="#upi-payment" type="button" role="tab" aria-controls="upi-payment"
                                        aria-selected="false">Upi Payment
                                    </button>
                                <?php } ?>

                            </div>
                            <div class="tab-content p-3" id="v-pills-tabContent">

                                <div class="tab-pane fade <?php if ($web_partner_wallet_balance > $total_price && isset(session()->get('wl_agent')['id'])) {
                                    echo "show active";
                                } ?>" id="my-wallet" role="tabpanel" aria-labelledby="my-wallet-tab">
                                    <?php $wallet_balance = wl_agent_get_balance();
                                    $walletpayment_token = dev_encode(json_encode(array('mode' => 'wallet', 'service' => $service, 'id' => $booking_id, 'fare' => $total_price, "search_token_id" => $search_token_id)));
                                    ?>
                                    <h6>My Wallet Balance :
                                        ₹
                                        <?php echo number_format_value($wallet_balance); ?>
                                    </h6>
                                    <?php if ($wallet_balance >= $total_price) { ?>
                                        <form
                                            action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $walletpayment_token; ?>"
                                            id="mywallet-form">
                                            <p class="mb-4 ml-0"><b>Please note:</b> By placing this order, you
                                                agree to
                                                our Terms Of Use and Privacy Policy</p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="mywallet-terms">
                                                <label class="form-check-label" for="mywallet-terms">I accept <a href=""
                                                        title="terms and conditions">
                                                        terms and conditions</a> and <a href="" title="privacy policy">
                                                        privacy policy </a>.</label>
                                            </div>
                                            <button type="button" class="btn btn-danger mt-3"
                                                onclick="continue_payment(this,'mywallet-terms','mywallet-form')">
                                                Pay Now ₹
                                                <?php echo number_format_value($total_price); ?>
                                            </button>
                                        </form>
                                    <?php } else {
                                        echo "<h5 class='text-danger'>Your wallet do not have sufficient balance</h5>";
                                    } ?>
                                </div>
                                <div class="tab-pane fade  <?php if ($web_partner_wallet_balance < $total_price && !isset(session()->get('wl_agent')['id']) && !$activeWallet) {
                                    echo "show active";
                                } ?>" id="credit-card" role="tabpanel" aria-labelledby="credit-card-tab">

                                    <?php
                                    /*remove comment code*/
                                    #visa_credit_card code
                                    $credit_data = calculate_convenience_fee($convenience_fee_list, 'credit_card', $total_price);

                                    $creditpayment_token = dev_encode(json_encode(array('mode' => 'CRDC', 'service' => $service, 'id' => $booking_id, 'fare' => $credit_data['totalfare'], 'cfee' => $credit_data['conveniencefee'], "search_token_id" => $search_token_id)));

                                    $credit_card_conv = number_format_value($credit_data['conveniencefee']);

                                    $credit_card_totalfare = number_format_value($credit_data['totalfare']);

                                    #visa_credit_card code end
                                    


                                    ?>
                                    <form
                                        action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token; ?>"
                                        id="credit-card-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>

                                        <p>Payment Fee :
                                            ₹<span id="tts-conveniencefee">
                                                <?php echo number_format_value($credit_data['conveniencefee']); ?>
                                            </span>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="credit-card-terms">
                                            <label class="form-check-label" for="credit-card-terms">I accept <a href=""
                                                    title="terms and conditions">
                                                    terms and conditions</a> and <a href="" title="privacy policy">
                                                    privacy policy </a>.</label>
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="continue_payment(this,'credit-card-terms','credit-card-form')">
                                            Pay Now
                                            ₹<span id="tts-totalfare">
                                                <?php echo number_format_value($credit_data['totalfare']); ?>
                                            </span>
                                        </button>
                                    </form>

                                </div>
                                <div class="tab-pane fade" id="debit-card" role="tabpanel"
                                    aria-labelledby="debit-card-tab">
                                    <?php
                                    $debit_data = calculate_convenience_fee($convenience_fee_list, 'debit_card', $total_price);
                                    $debitpayment_token = dev_encode(json_encode(array('mode' => 'DBCRD', 'service' => $service, 'id' => $booking_id, 'fare' => $debit_data['totalfare'], 'cfee' => $debit_data['conveniencefee'], "search_token_id" => $search_token_id)));
                                    ?>
                                    <form
                                        action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $debitpayment_token; ?>"
                                        id="debit-card-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹
                                            <?php echo number_format_value($debit_data['conveniencefee']); ?>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="debit-card-terms">
                                            <label class="form-check-label" for="debit-card-terms">I accept <a href=""
                                                    title="terms and conditions">
                                                    terms and conditions</a> and <a href="" title="privacy policy">
                                                    privacy policy </a>.</label>
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="continue_payment(this,'debit-card-terms','debit-card-form')">
                                            Pay Now
                                            ₹
                                            <?php echo number_format_value($debit_data['totalfare']); ?>
                                        </button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="net-banking" role="tabpanel"
                                    aria-labelledby="net-banking-tab">

                                    <?php $net_banking_data = calculate_convenience_fee($convenience_fee_list, 'net_banking', $total_price);
                                    $netpayment_token = dev_encode(json_encode(array('mode' => 'NBK', 'service' => $service, 'id' => $booking_id, 'fare' => $net_banking_data['totalfare'], 'cfee' => $net_banking_data['conveniencefee'], "search_token_id" => $search_token_id)));
                                    ?>
                                    <form
                                        action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $netpayment_token; ?>"
                                        id="net-baning-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹
                                            <?php echo number_format_value($net_banking_data['conveniencefee']); ?>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="net-baning-terms">
                                            <label class="form-check-label" for="net-baning-terms">I accept <a href=""
                                                    title="terms and conditions">
                                                    terms and conditions</a> and <a href="" title="privacy policy">
                                                    privacy policy </a>.</label>
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="continue_payment(this,'net-baning-terms','net-baning-form')">
                                            Pay Now
                                            ₹
                                            <?php echo number_format_value($net_banking_data['totalfare']); ?>
                                        </button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="mob-wallet" role="tabpanel"
                                    aria-labelledby="mob-wallet-tab">

                                    <?php $mob_wallet_data = calculate_convenience_fee($convenience_fee_list, 'mobile_wallet', $total_price);
                                    $mob_paymentdata = dev_encode(json_encode(array('mode' => 'WLT', 'service' => $service, 'id' => $booking_id, 'fare' => $mob_wallet_data['totalfare'], 'cfee' => $mob_wallet_data['conveniencefee'], "search_token_id" => $search_token_id)));
                                    ?>
                                    <form
                                        action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $mob_paymentdata; ?>"
                                        id="mob-wallet-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹
                                            <?php echo number_format_value($mob_wallet_data['conveniencefee']); ?>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="mob-wallet-terms">
                                            <label class="form-check-label" for="mob-wallet-terms">I accept <a href=""
                                                    title="terms and conditions">
                                                    terms and conditions</a> and <a href="" title="privacy policy">
                                                    privacy policy </a>.</label>
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="continue_payment(this,'mob-wallet-terms','mob-wallet-form')">
                                            Pay Now
                                            ₹
                                            <?php echo number_format_value($mob_wallet_data['totalfare']); ?>
                                        </button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="upi-payment" role="tabpanel"
                                    aria-labelledby="upi-payment-tab">

                                    <?php $mob_wallet_data = calculate_convenience_fee($convenience_fee_list, 'mobile_wallet', $total_price);
                                    $mob_paymentdata = dev_encode(json_encode(array('mode' => 'UPI', 'service' => $service, 'id' => $booking_id, 'fare' => $mob_wallet_data['totalfare'], 'cfee' => $mob_wallet_data['conveniencefee'], "search_token_id" => $search_token_id)));
                                    ?>
                                    <form
                                        action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $mob_paymentdata; ?>"
                                        id="upi-payment-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹
                                            <?php echo number_format_value($mob_wallet_data['conveniencefee']); ?>
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="upi-payment-terms">
                                            <label class="form-check-label" for="upi-payment-terms">I accept <a href=""
                                                    title="terms and conditions">
                                                    terms and conditions</a> and <a href="" title="privacy policy">
                                                    privacy policy </a>.</label>
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="continue_payment(this,'upi-payment-terms','upi-payment-form')">
                                            Pay Now
                                            ₹
                                            <?php echo number_format_value($mob_wallet_data['totalfare']); ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php if ($booking_data && $service == "flight") { ?>
                <div class="col-lg-4 col-12 col-md-4">
                    <div class="payment-title ">
                        <h5>Booking Summary</h5>
                    </div>
                    <div class="card">
                        <div class="card-body booking-summary-card">
                            <?php
                            $airlineLogoClass = "domAirLogo";
                            foreach ($booking_data as $journeykey => $bookingData) {

                                $rtype = $bookingData['trip_indicator'] == 1 ? "OB" : "IB";
                                $fareBreakupArray = json_decode($bookingData['agent_fare_break_up'], true);
                                $markup = isset($fareBreakupArray['AgentWebPMarkUp']) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                                $discount = isset($fareBreakupArray['AgentWebPDiscount']) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                                $addMarkupInTax = 0;
                                $addMarkupInServiceCharge = 0;
                                $MealCharge = isset($fareBreakupArray['TotalMealCharges']) ? $fareBreakupArray['TotalMealCharges'] : 0;
                                $SeatCharge = isset($fareBreakupArray['TotalSeatCharges']) ? $fareBreakupArray['TotalSeatCharges'] : 0;
                                $BaggageCharge = isset($fareBreakupArray['TotalBaggageCharges']) ? $fareBreakupArray['TotalBaggageCharges'] : 0;
                                if (isset($fareBreakupArray['AgentWebPDisplayMarkup']) && $fareBreakupArray['AgentWebPDisplayMarkup'] == 'in_service_charge') {
                                    $addMarkupInServiceCharge = $markup;
                                } else {
                                    $addMarkupInTax = $markup;
                                }
                                $FareBreakUp = array(
                                    "FareBreakup" => array(
                                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
                                        "Taxes" => array("Value" => $fareBreakupArray['Tax'] + $addMarkupInTax, "LabelText" => "Taxes"),
                                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'] + $addMarkupInServiceCharge, "LabelText" => "Other & Service Charges"),
                                        "MealCharges" => array("Value" => round_value($MealCharge), "LabelText" => "Meal Charges"),
                                        "BaggageCharge" => array("Value" => round_value($BaggageCharge), "LabelText" => "Baggage Charges"),
                                        /*  "SeatCharge" => array("Value" => round_value($SeatCharge), "LabelText" => "Seat Charges"), */
                                        "GST" => array("Value" => round_value($fareBreakupArray['GST']['CGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['IGSTAmount']), "LabelText" => "GST (+)"),
                                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),

                                    ),
                                );
                                if ($discount) {
                                    $FareBreakUp['FareBreakup']['Discount'] = array("Value" => round_value($discount), "LabelText" => "Discount (-)");
                                }
                                $FareBreakUp['FareBreakup']['TotalAmount'] = array("Value" => $fareBreakupArray['PublishedPrice'] + $markup - $discount + $MealCharge + $BaggageCharge, "LabelText" => "Pay  Amount");
                                $FareBreakUpDataArray[$rtype] = $FareBreakUp;
                                $TravellerInfo = json_decode($bookingData['travelersInfo'], true);
                                if ($bookingData['is_domestic']) {
                                    $airlineLogoClass = "domAirLogo";
                                } else {
                                    $airlineLogoClass = "intAirLogo";
                                }
                                $segmentInfo = json_decode($bookingData['segments'], true);
                                foreach ($segmentInfo as $tripkey => $trips) {
                                    ?>
                                    <div class="location">
                                        <?php echo ($journeykey == 'OB' && $tripkey == 0) ? "DEPART" : "Return"; ?>
                                    </div>
                                    <?php if ($trips) {
                                        foreach ($trips as $segmentIndicatorkey => $segment) {
                                            ?>
                                            <div class="location-block">
                                                <div class="flight-name">
                                                    <span
                                                        class="airline-logo intAirLogo size-18 x<?php echo $segment['Airline']['AirlineCode']; ?> me-2"></span>
                                                    <?php echo $segment['Airline']['AirlineName']; ?><br />
                                                    <?php echo $segment['Airline']['AirlineCode']; ?>-
                                                    <?php echo $segment['Airline']['FlightNumber']; ?>
                                                    <?php echo isset($segment['Airline']['FareClass']) && $segment['Airline']['FareClass'] != "" ? $segment['Airline']['FareClass'] : "-"; ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-12 col-md-4">
                                                        <div class="depart-detail">
                                                            <span class="date">
                                                                <?php echo get_flight_date($segment['Origin']['DepartTime']); ?>
                                                            </span>
                                                            <span class="time">
                                                                <?php echo $segment['Origin']['CityCode']; ?>
                                                                <?php echo get_flight_time($segment['Origin']['DepartTime']); ?>
                                                            </span>
                                                            <span> Terminal -
                                                                <?php echo isset($segment['Origin']['Terminal']) && $segment['Origin']['Terminal'] != "" ? $segment['Origin']['Terminal'] : ""; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-12 col-md-4">
                                                        <div class="duration-detail">
                                                            <span>
                                                                <?php echo get_convertToHoursMinsfromMinDuration($segment['Duration']); ?>
                                                            </span>
                                                            <img src="<?php echo site_url('webroot/img/detail-plane.svg'); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-12 col-md-4">
                                                        <div class="arrive-block">
                                                            <span class="date">
                                                                <?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?>
                                                            </span>
                                                            <span class="time">
                                                                <?php echo $segment['Destination']['CityCode']; ?>
                                                                <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?>
                                                            </span>
                                                            <span> Terminal -
                                                                <?php echo isset($segment['Destination']['Terminal']) && $segment['Destination']['Terminal'] != "" ? $segment['Destination']['Terminal'] : ""; ?>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php }
                                    }
                                }
                            } ?>
                            <div class="traveller">
                                <div class="title">TRAVELLERS</div>
                                <?php foreach ($TravellerInfo as $traveller) { ?>
                                    <div class="traveller-detail">
                                        <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                                        <span class="name">
                                            <?php echo $traveller['title'] . " " . $traveller['first_name'] . " " . $traveller['last_name']; ?>
                                        </span>
                                        <span class="gender">
                                            <?php echo ucfirst($traveller['pax_type']); ?>,
                                            <?php echo ucfirst(substr($traveller['gendar'], 0, 1)); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="contact_form">
                                <div class="title">Contact</div>
                                <div class="contact_form-detail">
                                    <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                                    <span class="phone">
                                        <?php echo $TravellerInfo[0]['mobile_number']; ?>
                                    </span>

                                </div>
                                <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                                <span class="email">&nbsp;
                                    <?php echo $TravellerInfo[0]['email_id']; ?>
                                </span>
                            </div>
                        </div>

                    </div>
                    <?php if ($FareBreakUpDataArray) {
                        foreach ($FareBreakUpDataArray as $farejourneykey => $FareBreakUpData) { ?>
                            <div class="payment-title ">
                                <?php if ($journeykey == "IB") { ?>
                                    <h5>
                                        <?php echo $farejourneykey == "OB" ? "Onward" : "Return"; ?> Fare Summary
                                    </h5>
                                </div>
                            <?php } else { ?>
                                <h5> Fare Summary</h5>
                            <?php } ?>
                            <div class="card ">
                                <?php foreach ($FareBreakUpData['FareBreakup'] as $FareBreakup) { ?>
                                    <div class="card-body payment_farebrkup">
                                        <div class="d-flex justify-content-between  align-items-center">
                                            <h5>
                                                <?php echo $FareBreakup['LabelText']; ?>
                                            </h5>
                                            <b>₹
                                                <?php echo $FareBreakup['Value']; ?>
                                            </b>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }
                    } ?>
                </div>
            <?php } else if ($booking_data && $service == "hotel") {
                $roomInfo = json_decode($booking_data['hotel_rooms_details'], true);
                ?>
                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="payment-title ">
                            <h5>Booking Summary</h5>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body booking-summary-card">


                                <div class="location-block mb-3">

                                    <div class="row">
                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="depart-detail">
                                                <span>
                                                <?php echo $booking_data['hotel_name']; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="arrive-block">
                                                <span class="date">Check In</span>
                                                <span>Nights </span>
                                                <span class="date">Check Out</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="arrive-block">
                                                <span class="date">
                                                <?php echo date('d-M-Y', strtotime($booking_data['check_in_date'])); ?>
                                                </span>
                                                <span>
                                                <?php echo ($booking_data['no_of_nights']); ?>
                                                </span>
                                                <span class="date">
                                                <?php echo date('d-M-Y', strtotime($booking_data['check_out_date'])); ?>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="traveller mb-3">
                                    <div class="title" style="font-weight: 600;">Room Info</div>
                                <?php $publishedFare = 0;
                                $WebPDiscount = 0;
                                foreach ($roomInfo as $roomkey => $roomInfos) {
                                    $Discount = isset($roomInfos['Price']['WebPDiscount']) ? $roomInfos['Price']['WebPDiscount'] : 0;
                                    $publishedFare = $publishedFare + $roomInfos['Price']['PublishedPrice'];
                                    $WebPDiscount = $WebPDiscount + $Discount; ?>
                                        <div class="traveller-detail">
                                            <div class="title" style="font-weight: 400;">Room
                                            <?php echo ($roomkey + 1); ?>
                                            </div>
                                            <span>
                                            <?php echo $roomInfos['RoomTypeName']; ?>(
                                            <?php echo $roomInfos['RatePlanName']; ?>)
                                            </span><br />
                                        <?php foreach ($roomInfos['HotelPassenger'] as $traveller) { ?>
                                                <div>
                                                    <span class="name">
                                                    <?php echo $traveller['Title'] . " " . $traveller['FirstName'] . " " . $traveller['LastName']; ?>
                                                    </span>
                                                    <span class="gender">
                                                    <?php echo $traveller['PaxType'] == "1" ? "Adult" : "Child"; ?>
                                                    </span>
                                                </div><br />
                                        <?php } ?>
                                        </div>
                                <?php } ?>
                                </div>
                                <div class="contact_form">
                                    <div class="title" style="font-weight: 600;">CONTACT</div>
                                    <div class="contact_form-detail">
                                        <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                                        <span class="phone">
                                        <?php echo $booking_data['mobile_number']; ?>
                                        </span>

                                    </div>
                                    <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                                    <span class="email">&nbsp;
                                    <?php echo $booking_data['email_id']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="payment-title ">
                            <h5> Fare Summary</h5>
                        </div>
                        <div class="card ">
                            <div class="card-body payment_farebrkup">
                                <div class="d-flex justify-content-between  align-items-center">
                                    <h6 class="card-title mb-0">Hotel Price</h6>
                                    <p class="card-text">₹
                                    <?php echo $publishedFare + $WebPDiscount; ?>
                                    </p>
                                </div>
                            <?php if ($WebPDiscount) { ?>
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">Discount(-)</h6>
                                        <p class="card-text">₹
                                        <?php echo $WebPDiscount; ?>
                                        </p>
                                    </div>
                            <?php } ?>
                                <div class="d-flex justify-content-between  align-items-center">
                                    <h6 class="card-title mb-0">Total Amount</h6>
                                    <p class="card-text">₹
                                    <?php echo ($publishedFare); ?>
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
        <?php } else if ($booking_data && $service == "bus") {
                $TravellerInfo = json_decode($booking_data['travelersInfo'], true);
                ?>
                    <div class="col-lg-4 col-12 col-md-4">
                        <div class="payment-title ">
                            <h5>Booking Summary</h5>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body booking-summary-card">


                                <div class="location-block mb-3">

                                    <div class="row">
                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="depart-detail">
                                                <span>
                                            <?php echo $booking_data['origin_city'] . "-" . $booking_data['destination_city']; ?>
                                                </span>
                                                <span>
                                            <?php echo $booking_data['bus_name'] . "(" . $booking_data['bus_type'] . ')'; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="arrive-block">
                                                <span class="date">Origin</span>
                                                <span>Destination </span>
                                                <span class="date">Travel Date</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-12 col-md-12">
                                            <div class="arrive-block">
                                                <span class="date">
                                            <?php echo $booking_data['origin_city'] ?>
                                                </span>
                                                <span>
                                            <?php echo $booking_data['destination_city']; ?>
                                                </span>
                                                <span class="date">
                                            <?php echo date('d-M-Y', strtotime($booking_data['date_of_journey'])); ?>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="traveller mb-3">
                                    <div class="traveller mb-3">
                                        <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                                <?php foreach ($TravellerInfo as $traveller) { ?>
                                            <div class="traveller-detail">
                                                <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                                                <span class="name">
                                            <?php echo $traveller['title'] . " " . $traveller['first_name'] . " " . $traveller['last_name']; ?>
                                                </span>
                                                <span class="gender">
                                            <?php echo ucfirst(substr($traveller['gendar'], 0, 1)); ?>
                                                </span>
                                            </div>
                                <?php } ?>
                                    </div>
                                    <div class="contact_form">
                                        <div class="title" style="font-weight: 600;">CONTACT</div>
                                        <div class="contact_form-detail">
                                            <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                                            <span class="phone">
                                        <?php echo $TravellerInfo[0]['mobile_number']; ?>
                                            </span>

                                        </div>
                                        <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                                        <span class="email">&nbsp;
                                    <?php echo $TravellerInfo[0]['email_id']; ?>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="payment-title ">
                                <h5> Fare Summary</h5>
                            </div>
                    <?php $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
                    $PublishedPrice = 0;
                    $OfferedPrice = 0;
                    $AgentCommission = 0;
                    $TDS = 0;
                    $TotalAmount = 0;
                    foreach ($agent_fare_break_up as $agent_fare_break) {
                        $PublishedPrice = $PublishedPrice + $agent_fare_break['PublishedPrice'];
                        $OfferedPrice = $OfferedPrice + $agent_fare_break['OfferedPrice'];
                        $TDS = $TDS + $agent_fare_break['TDS'];
                        $TotalAmount = $TotalAmount + $agent_fare_break['OfferedPrice'] + $agent_fare_break['TDS'];
                    }

                    ?>
                            <div class="card ">
                                <div class="card-body payment_farebrkup">
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">Published Price</h6>
                                        <p class="card-text">₹
                                    <?php echo $PublishedPrice; ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">Offered Price</h6>
                                        <p class="card-text">₹
                                    <?php echo $OfferedPrice; ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">Comm Earned (-)</h6>
                                        <p class="card-text">₹
                                    <?php echo $AgentCommission; ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">TDS (+)</h6>
                                        <p class="card-text">₹
                                    <?php echo $TDS; ?>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between  align-items-center">
                                        <h6 class="card-title mb-0">Total Amount</h6>
                                        <p class="card-text">₹
                                    <?php echo $TotalAmount; ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

        <?php } else if ($booking_data && $service == "holiday") { ?>
                        <div class="col-lg-4 col-12 col-md-4">
                            <div class="payment-title ">
                                <h5>Booking Summary</h5>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body booking-summary-card">


                                    <div class="tts-holiday-review-details">
                            <?php if (isset($booking_data['HolidayImage'])) { ?>
                                            <img src="<?php echo $booking_data['package_image'] ?>" class="img-fluid">
                            <?php } else { ?>
                                            <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                            <?php } ?>

                                        <div class="">
                                            <ul class="">
                                                <li>
                                                    <h6>Package</h6>
                                                    <h5>
                                            <?php echo $booking_data['package_name'] ?>
                                                    </h5>

                                                </li>

                                                <li class="Duration-list">
                                                    <h6>Category</h6>
                                                    <h5>
                                            <?php echo $booking_data['package_category'] ?>
                                                    </h5>
                                                </li>

                                                <li class="text-end">
                                                    <h6>Duration</h6>
                                                    <h5>
                                            <?php echo $night = $booking_data['day_nights']; ?>
                                                    </h5>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="traveller mb-3">
                                        <div class="traveller mb-3">
                                            <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                                            <div class="traveller-detail">
                                                <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                                                <span class="name">
                                        <?php echo $booking_data['title'] . " " . $booking_data['first_name'] . " " . $booking_data['last_name']; ?>
                                                </span>
                                                <span class="gender">
                                        <?php #echo ucfirst(substr($traveller['gendar'], 0, 1)); 
                                            ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="contact_form">
                                            <div class="title" style="font-weight: 600;">CONTACT</div>
                                            <div class="contact_form-detail">
                                                <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                                                <span class="phone">
                                        <?php echo $booking_data['mobile_number']; ?>
                                                </span>

                                            </div>
                                            <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                                            <span class="email">&nbsp;
                                    <?php echo $booking_data['email_id']; ?>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="payment-title ">
                                    <h5> Fare Summary</h5>
                                </div>
                    <?php $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
                    $fareBreakupArray = $agent_fare_break_up;
                    $gst = $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'];
                    $FareBreakUp = array();
                    $discount = 0;
                    $markup = 0;
                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $fareBreakupArray['AgentCommission'] + $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                            "GST" => array("Value" => $gst, "LabelText" => "GST (+)")
                        ),
                        "TotalAmount" => array("Value" => $fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS'], "LabelText" => "Total Amount"),
                        "WebPMarkUp" => $markup,
                        "WebPDiscount" => $discount,
                    );


                    ?>
                                <div class="card ">
                                    <div class="card-body payment_farebrkup">
                            <?php foreach ($FareBreakUp['FareBreakup'] as $key => $fareBreakUp): ?>
                                            <div class="d-flex justify-content-between  align-items-center">
                                                <h6 class="card-title mb-0">
                                        <?= $fareBreakUp['LabelText'] ?>
                                                </h6>
                                                <p class="card-text">₹
                                        <?php echo $fareBreakUp['Value']; ?>
                                                </p>
                                            </div>
                            <?php endforeach; ?>
                                        <div class="d-flex justify-content-between  align-items-center">
                                            <h6 class="card-title mb-0">
                                    <?= $FareBreakUp['TotalAmount']['LabelText'] ?>
                                            </h6>
                                            <p class="card-text">₹
                                    <?= $FareBreakUp['TotalAmount']['Value'] ?>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
        <?php } else if ($booking_data && $service == "car") { ?>

            <?php // pr($booking_data);exit;      ?>
                            <div class="col-lg-4 col-12 col-md-4">
                                <div class="payment-title ">
                                    <h5>Booking Summary</h5>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body booking-summary-card">
                                        <div class="tts-holiday-review-details">
                            <?php if (isset($booking_data['car_type'])) { ?>
                                                <img src="<?php echo root_url . 'uploads/car-extranet/' . car_image($booking_data['car_type']); ?>"
                                                    alt="<?php echo $booking_data['car_type']; ?>" class="img-fluid">
                            <?php } else { ?>
                                                <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                            <?php } ?>
                                            <div class="">
                                                <ul class="">
                                                    <li>
                                                        <h6>Car Name</h6>
                                                        <h5>
                                            <?php echo $booking_data['car_name'] ?>
                                                        </h5>
                                                    </li>
                                                    <li class="Duration-list">
                                                        <h6>Source</h6>
                                                        <h5>
                                            <?php echo $booking_data['source'] ?>
                                                        </h5>
                                                    </li>
                                                    <li class="text-end">
                                                        <h6>destination</h6>
                                                        <h5>
                                            <?php echo $booking_data['destination']; ?>
                                                        </h5>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="traveller mb-3">
                                            <div class="traveller mb-3">
                                                <div class="title" style="font-weight: 600;">TRAVELLERS</div>
                                                <div class="traveller-detail">
                                                    <img src="<?php echo site_url('webroot/img/user-icon.svg'); ?>">
                                                    <span class="name">
                                        <?php echo $booking_data['title'] . " " . $booking_data['first_name'] . " " . $booking_data['last_name']; ?>
                                                    </span>
                                                    <span class="gender">
                                        <?php $departureDate = new DateTime($booking_data['departure_date']); ?>


                                        <?php echo $departureDate->format('d F Y'); ?>

                                                    </span>
                                                </div>
                                            </div>
                                            <div class="contact_form">
                                                <div class="title" style="font-weight: 600;">CONTACT</div>
                                                <div class="contact_form-detail">
                                                    <img src="<?php echo site_url('webroot/img/phone-icon.svg'); ?>">
                                                    <span class="phone">
                                        <?php echo $booking_data['mobile_number']; ?>
                                                    </span>
                                                </div>
                                                <img src="<?php echo site_url('webroot/img/email-icon.svg'); ?>">
                                                <span class="email">&nbsp;
                                    <?php echo $booking_data['email_id']; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="payment-title ">
                                        <h5> Fare Summary</h5>
                                    </div>
                    <?php $agent_fare_break_up = json_decode($booking_data['agent_fare_break_up'], true);
                    $fareBreakupArray = $agent_fare_break_up;
                    $gst = $fareBreakupArray['GST']['IGSTAmount'] + $fareBreakupArray['GST']['SGSTAmount'] + $fareBreakupArray['GST']['CGSTAmount'];

                    $FareBreakUp = array();
                    $discount = (isset($fareBreakupArray['AgentWebPDiscount']) && $fareBreakupArray['AgentWebPDiscount'] > 0) ? $fareBreakupArray['AgentWebPDiscount'] : 0;
                    $markup = (isset($fareBreakupArray['AgentWebPMarkUp']) && $fareBreakupArray['AgentWebPMarkUp'] > 0) ? $fareBreakupArray['AgentWebPMarkUp'] : 0;
                    if ($fareBreakupArray['AgentWebPDisplayMarkup'] == "in_tax") {
                        $fareBreakupArray['Tax'] += $markup;
                    }
                    if ($fareBreakupArray['AgentWebPDisplayMarkup'] == "in_service_charge") {
                        $fareBreakupArray['ServiceCharges'] += $markup;
                    }
                    $FareBreakUp = array(
                        "FareBreakup" => array(
                            "BaseFare" => array("Value" => $fareBreakupArray['BasePrice'], "LabelText" => "Base Price"),
                            "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
                            "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
                            "Discount" => array("Value" => $discount, "LabelText" => "Discount (-)"),
                            "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)"),
                            "GST" => array("Value" => $gst, "LabelText" => "GST (+)")
                        ),
                        "TotalAmount" => array("Value" => round_value($fareBreakupArray['PublishedPrice'] + $markup - $discount), "LabelText" => "Pay  Amount"),
                        "OfferedFare" => array("Value" => round_value($fareBreakupArray['OfferedPrice'] + $fareBreakupArray['TDS']), "LabelText" => "Offered Fare"),
                        "WebPMarkUp" => $markup,
                        "WebPDiscount" => $discount,
                    );

                    ?>
                                    <div class="card ">
                                        <div class="card-body payment_farebrkup">
                            <?php foreach ($FareBreakUp['FareBreakup'] as $key => $fareBreakUp):
                                if ($fareBreakUp['Value']):
                                    ?>
                                                    <div class="d-flex justify-content-between  align-items-center">
                                                        <h6 class="card-title mb-0">
                                            <?= $fareBreakUp['LabelText'] ?>
                                                        </h6>
                                                        <p class="card-text">₹
                                            <?php echo $fareBreakUp['Value']; ?>
                                                        </p>
                                                    </div>
                                <?php endif; endforeach; ?>
                                            <div class="d-flex justify-content-between  align-items-center">
                                                <h6 class="card-title mb-0">
                                    <?= $FareBreakUp['TotalAmount']['LabelText'] ?>
                                                </h6>
                                                <p class="card-text">₹
                                    <?= $FareBreakUp['TotalAmount']['Value'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        <?php } ?>
    </div>

    </div>
    </div>
</section>

<style>
    .nav-pills .nav-link.active {
        border-left: 2px solid #c92e2a;
        color: #c92e2a;
        border-radius: 0;
        background: transparent;
    }

    .nav-pills .nav-link {
        background: 0 0;
        border-radius: 0;
        width: 200px;
        text-align: left;
        color: #000;
        font-size: 14px;
        font-weight: 500;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        padding: 15px;
    }

    #bookingCounter {
        display: inline-block;
        height: 56px;
        background: #000;
        text-align: center;
        width: 100%;
        z-index: 999999;
        position: fixed;
        line-height: 56px;
        left: 0;
        bottom: 0;
        color: #fff;
        font-size: 16px;
    }
</style>

<!--
<div id="bookingCounter" style="display: inline-block;">
    <i class="fa fa-clock"></i> Your session will expire in
    <span class="" id="demo"></span>
</div>-->


<script>
    // Set the date we're counting down to
    var countDownDate = new Date("Fri Aug 05 2022 13:53:23").getTime();
    var x = setInterval(function () {
        //var now=new Date(new Date().toUTCString()).getTime();

        var now = new Date(new Date().toUTCString().slice(0, -3)).getTime();

        console.log(new Date(new Date().toUTCString().slice(0, -3)));

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("demo").innerHTML = minutes + " min " + seconds + " sec ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>