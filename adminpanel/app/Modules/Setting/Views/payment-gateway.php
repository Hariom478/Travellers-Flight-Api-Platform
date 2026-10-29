<div class="row">
    <?php if ($gateway_type == 'PAYU') { ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>Payu Key</label>
                <input class="form-control" type="text" name="payment_gateway_setting[payu_key]"
                    value="<?php echo $payment_gateway_setting['payu_key'] ?>" placeholder="Payu Key">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>Payu Salt</label>
                <input class="form-control" type="text" name="payment_gateway_setting[payu_salt]"
                    value="<?php echo $payment_gateway_setting['payu_salt'] ?>" placeholder="Payu Salt">
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>Payu Url</label>
                <input class="form-control" type="text" name="payment_gateway_setting[payu_url]"
                    value="<?php echo $payment_gateway_setting['payu_url'] ?>" placeholder="Payu Url">
            </div>
        </div>
    <?php } elseif ($gateway_type == 'CCAVENUE') { ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>CCavenue Merchant Id</label>
                <input class="form-control" type="text" name="payment_gateway_setting[ccavenue_merchant_id]"
                    value="<?php echo $payment_gateway_setting['ccavenue_merchant_id'] ?>"
                    placeholder="CCavenue  Merchant Id">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>CCavenue Working Key</label>
                <input class="form-control" type="text" name="payment_gateway_setting[ccavenue_working_key]"
                    value="<?php echo $payment_gateway_setting['ccavenue_working_key'] ?>" placeholder="CCavenue Key">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>CCavenue Access Code</label>
                <input class="form-control" type="text" name="payment_gateway_setting[ccavenue_access_code]"
                    value="<?php echo $payment_gateway_setting['ccavenue_access_code'] ?>"
                    placeholder="CCavenue Access Code">
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>CCavenue Url</label>
                <input class="form-control" type="text" name="payment_gateway_setting[url]"
                    value="<?php echo $payment_gateway_setting['url'] ?>" placeholder="CCavenue Url">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-mb-20">
                <label>CCavenue Status Url</label>
                <input class="form-control" type="text" name="payment_gateway_setting[status_url]"
                    value="<?php echo $payment_gateway_setting['status_url'] ?>" placeholder="CCavenue Status Url">
            </div>
        </div>
    <?php } elseif ($gateway_type == 'RAZORPAY') { ?>
        <div class="col-md-12">
            <div class="col-md-12 ">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>Key ID</label>
                <input class="form-control" type="text" name="payment_gateway_setting[key_id]"
                    value="<?php echo $payment_gateway_setting['key_id'] ?>" placeholder="Key ID">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>Secret Key</label>
                <input class="form-control" type="text" name="payment_gateway_setting[secret_key]"
                    value="<?php echo $payment_gateway_setting['secret_key'] ?>" placeholder="Secret Key">
            </div>
        </div>
    <?php } elseif ($gateway_type == 'CASHFREE') { ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>App ID</label>
                <input class="form-control" type="text" name="payment_gateway_setting[app_id]"
                    value="<?php echo $payment_gateway_setting['app_id'] ?>" placeholder="App ID">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>Secret Key</label>
                <input class="form-control" type="text" name="payment_gateway_setting[secret_key]"
                    value="<?php echo $payment_gateway_setting['secret_key'] ?>" placeholder="Secret Key">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>Url</label>
                <input class="form-control" type="text" name="payment_gateway_setting[url]"
                    value="<?php echo $payment_gateway_setting['url'] ?>" placeholder="Url">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group form-mb-20">
                <label>Mode</label>
                <select class="form-control" name="payment_gateway_setting[mode]">
                    <option value="LIVE" <?php if ($payment_gateway_setting['mode'] == "LIVE") {
                        echo "selected";
                    } ?>>LIVE</option>
                    <option value="TEST" <?php if ($payment_gateway_setting['mode'] == "TEST") {
                        echo "selected";
                    } ?>>TEST</option>
                </select>
            </div>
        </div>
    <?php } elseif ($gateway_type == 'ICICIBANK') { ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
    <?php } elseif ($gateway_type == 'HDFCBANK') { ?>
        <div class="col-md-12">
            <div class="col-md-12">
                <h6 class="viewld_h5">
                    <?php echo $gateway_type . ' ' . 'Payment Gateway Settings' ?>
                </h6>
            </div>
        </div>
    <?php } else { ?>

    <?php } ?>
</div>