<section class="payment-section p-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 col-md-8">
                <div class="payment-title">
                    <h5>Payment Options</h5>
                </div>
                <div class="card">
                    <div class="card-body p-0">

                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                 aria-orientation="vertical">

                                <button class="nav-link active" id="my-wallet-tab" data-bs-toggle="tab"
                                        data-bs-target="#my-wallet" type="button" role="tab" aria-controls="my-wallet"
                                        aria-selected="false">My Wallet
                                </button>

                                <button class="nav-link" id="credit-card-tab" data-bs-toggle="pill"
                                        data-bs-target="#credit-card" type="button" role="tab"
                                        aria-controls="credit-card" aria-selected="true">Credit Card
                                </button>

                                <button class="nav-link" id="debit-card-tab" data-bs-toggle="pill"
                                        data-bs-target="#debit-card" type="button" role="tab" aria-controls="debit-card"
                                        aria-selected="false">Debit Card
                                </button>

                                <button class="nav-link" id="net-banking-tab" data-bs-toggle="pill"
                                        data-bs-target="#net-banking" type="button" role="tab"
                                        aria-controls="net-banking" aria-selected="false">Net Banking
                                </button>

                                <button class="nav-link border-bottom-0" id="mob-wallet-tab" data-bs-toggle="pill"
                                        data-bs-target="#mob-wallet" type="button" role="tab" aria-controls="mob-wallet"
                                        aria-selected="false">Mobile Wallets
                                </button>

                            </div>
                            <div class="tab-content p-3" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="my-wallet" role="tabpanel"
                                     aria-labelledby="my-wallet-tab">
                                    <?php $wallet_balance = get_balance();
                                    $walletpayment_token = dev_encode(json_encode(array('mode' => 'wallet', 'service' => $service, 'id' => $booking_id, 'fare' => $total_price)));
                                    ?>
                                    <h6>My Wallet Balance : ₹ <?php echo number_format_value($wallet_balance); ?> </h6>
                                    <?php if ($wallet_balance >= $total_price) { ?>
                                        <form action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $walletpayment_token; ?>"
                                              id="mywallet-form">
                                            <p class="mb-4 ml-0"><b>Please note:</b> By placing this order, you agree to
                                                our Terms Of Use and Privacy Policy</p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="mywallet-terms">
                                                <label class="form-check-label" for="mywallet-terms">I accept <a href=""
                                                                                                                 title="terms and conditions">
                                                        terms and conditions</a> and <a href="" title="privacy policy">
                                                        privacy policy </a>.</label>
                                            </div>
                                            <button type="button" class="btn btn-danger"
                                                    onclick="continue_payment(this,'mywallet-terms','mywallet-form')">
                                                Pay Now ₹ <?php echo number_format_value($total_price); ?> </button>
                                        </form>
                                    <?php } else {
                                        echo "<h5 class='text-danger'>Agency account do not have sufficient balance</h5>";
                                    } ?>

                                </div>
                                <div class="tab-pane fade" id="credit-card" role="tabpanel"
                                     aria-labelledby="credit-card-tab">

                                    <?php
                                    /*remove comment code*/
                                   /* $credit_data = calculate_convenience_fee($convenience_fee_list, 'credit_card', $total_price);
                                    $creditpayment_token = dev_encode(json_encode(array('mode' => 'CRDC', 'service' => $service, 'id' => $booking_id, 'fare' => $credit_data['totalfare'], 'cfee' => $credit_data['conveniencefee'])));*/


                                    #visa_credit_card code
                                    $credit_data = calculate_convenience_fee($convenience_fee_list, 'visa_credit_card', $total_price);

                                    $creditpayment_token_visa = dev_encode(json_encode(array('mode' => 'CRDC','card_name'=>'VisaCreditCard', 'service' => $service, 'id' => $booking_id, 'fare' => $credit_data['totalfare'], 'cfee' => $credit_data['conveniencefee'])));

                                    $visa_credit_card_conv = number_format_value($credit_data['conveniencefee']);

                                    $visa_credit_card_totalfare = number_format_value($credit_data['totalfare']);

                                    #visa_credit_card code end

                                    #rupay_credit_card code
                                    $credit_data_rupay = calculate_convenience_fee($convenience_fee_list, 'rupay_credit_card', $total_price);

                                    $creditpayment_token_rupay = dev_encode(json_encode(array('mode' => 'CRDC','card_name'=>'RuPayCreditCard', 'service' => $service, 'id' => $booking_id, 'fare' => $credit_data_rupay['totalfare'], 'cfee' => $credit_data_rupay['conveniencefee'])));

                                    $rupay_credit_card_conv = number_format_value($credit_data_rupay['conveniencefee']);

                                    $rupay_credit_card_totalfare = number_format_value($credit_data_rupay['totalfare']);
                                    #rupay_credit_card code end


                                    #mastercard_credit_card code
                                    $credit_data_mastercard_data = calculate_convenience_fee($convenience_fee_list, 'mastercard_credit_card', $total_price);

                                    $creditpayment_token_mastercard = dev_encode(json_encode(array('mode' => 'CRDC','card_name'=>'MastercardCreditCard', 'service' => $service, 'id' => $booking_id, 'fare' => $credit_data_mastercard_data['totalfare'], 'cfee' => $credit_data_mastercard_data['conveniencefee'])));

                                    $mastercard_credit_card_conv = number_format_value($credit_data_mastercard_data['conveniencefee']);

                                    $mastercard_credit_card_totalfare = number_format_value($credit_data_mastercard_data['totalfare']);
                                    #mastercard_credit_card code end


                                    #mastercard_credit_card code
                                    $american_express_data = calculate_convenience_fee($convenience_fee_list, 'american_express_credit_card', $total_price);

                                    $creditpayment_token_american_express = dev_encode(json_encode(array('mode' => 'CRDC','card_name'=>'AmericanExpressCreditCard', 'service' => $service, 'id' => $booking_id, 'fare' => $american_express_data['totalfare'], 'cfee' => $american_express_data['conveniencefee'])));

                                    $american_express_credit_card_conv = number_format_value($american_express_data['conveniencefee']);

                                    $american_express_credit_card_totalfare = number_format_value($american_express_data['totalfare']);
                                    #mastercard_credit_card code end



                                    ?>
                                    <form action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token_visa; ?>"
                                          id="credit-card-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="card_name" id="VisaCreditCard" value="VisaCreditCard" checked>
                                            <label class="form-check-label" for="VisaCreditCard">Visa</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="card_name" id="RuPayCreditCard" value="RuPayCreditCard">
                                            <label class="form-check-label" for="RuPayCreditCard">RuPay</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="card_name" id="MastercardCreditCard" value="MastercardCreditCard" >
                                            <label class="form-check-label" for="MastercardCreditCard">Mastercard</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="card_name" id="AmericanExpressCreditCard" value="AmericanExpressCreditCard" >
                                            <label class="form-check-label" for="AmericanExpressCreditCard">American Express</label>
                                        </div>


                                        <p>Payment Fee :
                                            ₹<span id="tts-conveniencefee"><?php echo number_format_value($credit_data['conveniencefee']); ?></span></p>
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
                                            ₹<span id="tts-totalfare"><?php echo number_format_value($credit_data['totalfare']); ?></span></button>
                                    </form>

                                    <script>
                                        $(document).on("change", 'input[name="card_name"]', function () {
                                            let card_name = $('input[name="card_name"]:checked').val();

                                            if (card_name=='VisaCreditCard'){

                                                $("#tts-conveniencefee").html("<?php echo $visa_credit_card_conv;?>");
                                                $("#tts-totalfare").html("<?php echo $visa_credit_card_totalfare;?>");

                                                $('#credit-card-form').attr( 'action',"<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token_visa; ?>");


                                            }else if (card_name=='RuPayCreditCard'){
                                                $("#tts-conveniencefee").html("<?php echo $rupay_credit_card_conv;?>");
                                                $("#tts-totalfare").html("<?php echo $rupay_credit_card_totalfare;?>");
                                                $('#credit-card-form').attr( 'action',"<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token_rupay; ?>");

                                            }else if (card_name=='MastercardCreditCard'){
                                                $("#tts-conveniencefee").html("<?php echo $mastercard_credit_card_conv;?>");
                                                $("#tts-totalfare").html("<?php echo $mastercard_credit_card_totalfare;?>");

                                                $('#credit-card-form').attr( 'action',"<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token_mastercard; ?>");

                                            }else if (card_name=='AmericanExpressCreditCard'){
                                                $("#tts-conveniencefee").html("<?php echo $american_express_credit_card_conv;?>");
                                                $("#tts-totalfare").html("<?php echo $american_express_credit_card_totalfare;?>");
                                                $('#credit-card-form').attr( 'action',"<?php echo site_url('payment/proceed-payment/'); ?><?php echo $creditpayment_token_american_express; ?>");

                                            }
                                        });
                                    </script>

                                </div>
                                <div class="tab-pane fade" id="debit-card" role="tabpanel"
                                     aria-labelledby="debit-card-tab">

                                    <?php
                                    $debit_data = calculate_convenience_fee($convenience_fee_list, 'debit_card', $total_price);
                                    $debitpayment_token = dev_encode(json_encode(array('mode' => 'DBCRD', 'service' => $service, 'id' => $booking_id, 'fare' => $debit_data['totalfare'], 'cfee' => $debit_data['conveniencefee'])));

                                    ?>
                                    <form action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $debitpayment_token; ?>"
                                          id="debit-card-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹<?php echo number_format_value($debit_data['conveniencefee']); ?></p>
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
                                            ₹<?php echo number_format_value($debit_data['totalfare']); ?></button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="net-banking" role="tabpanel"
                                     aria-labelledby="net-banking-tab">

                                    <?php $net_banking_data = calculate_convenience_fee($convenience_fee_list, 'net_banking', $total_price);
                                    $netpayment_token = dev_encode(json_encode(array('mode' => 'NBK', 'service' => $service, 'id' => $booking_id, 'fare' => $net_banking_data['totalfare'], 'cfee' => $net_banking_data['conveniencefee'])));
                                    ?>
                                    <form action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $netpayment_token; ?>"
                                          id="net-baning-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹<?php echo number_format_value($net_banking_data['conveniencefee']); ?></p>
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
                                            ₹<?php echo number_format_value($net_banking_data['totalfare']); ?></button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="mob-wallet" role="tabpanel"
                                     aria-labelledby="mob-wallet-tab">

                                    <?php $mob_wallet_data = calculate_convenience_fee($convenience_fee_list, 'mobile_wallet', $total_price);
                                    $mob_paymentdata = dev_encode(json_encode(array('mode' => 'MOBP', 'service' => $service, 'id' => $booking_id, 'fare' => $mob_wallet_data['totalfare'], 'cfee' => $mob_wallet_data['conveniencefee'])));
                                    ?>
                                    <form action="<?php echo site_url('payment/proceed-payment/'); ?><?php echo $mob_paymentdata; ?>"
                                          id="mob-wallet-form">
                                        <p class="mb-4"><b>Please note:</b> You may be redirected to bank page to
                                            complete your transaction. By making this booking, you agree to our Terms of
                                            Use and Privacy Policy.</p>
                                        <p>Payment Fee :
                                            ₹<?php echo number_format_value($mob_wallet_data['conveniencefee']); ?></p>
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
                                            ₹<?php echo number_format_value($mob_wallet_data['totalfare']); ?></button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--   <div class="col-lg-4 col-12 col-md-4">
                  <div class="payment-title">
                      <h5>Booking Summary</h5>
                  </div>
                  <div class="card">
                      <div class="card-body booking-summary-card"></div>
                  </div>
                  <div class="payment-title">
                      <h5>Fare Summary</h5>
                  </div>
                  <div class="card">
                      <div class="card-body booking-summary-card"></div>
                  </div>
              </div> -->
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