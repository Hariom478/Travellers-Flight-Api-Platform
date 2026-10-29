<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/js/jquery.form-validator.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.form-validator.min.js'); ?>"></script>
<link rel="stylesheet"
    href="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.css'); ?>">
<style>
    .error-message {
        top: 59px;
    }

    .firms_selected input {
        width: 15px;
        height: 15px;
    }

    .firms_selected strong {
        font-size: 16px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        padding-left: 13px;
        font-size: 16px;
        text-transform: capitalize;
    }

    /*.select2-container .select2-selection--single {
   height: 58px;
   }
   .select2-container--default .select2-selection--single .select2-selection__arrow {
   height: 58px;
   }*/
    .form-text {
        font-size: 14px;
    }

    .Registration-section .form-control {
        padding-left: 2.375rem;
    }

    .Registration-section .form-control:focus {
        box-shadow: none;
        border-color: #432346;
    }

    .Registration-section .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }

    .Registration-section form .form-check {
        font-size: 16px;
        font-weight: 600;
    }

    .Registration-section form .form-check a {
        color: #cc181e;
    }

    .Registration-section .register_form h3 {
        font-size: 18px;
        font-weight: 600;
    }
</style>
<section class="Registration-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty(session()->get("Message"))):
                    $message = session()->get("Message"); ?>
                    <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');">
                        <?php echo $message['Message']; ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="<?php echo site_url('login/register-agency') ?>" id="tts-agency-register"
                    tts-form-register="true" enctype="multipart/form-data" class="register_form">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4>Get Started - Register Your Agency</h4>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="mb-3">Account Information</h3>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-address-card form-control-feedback"></span>
                            <input type="text" class="form-control" name="company_name" id="company-name"
                                placeholder="Company Name" data-validation="required"
                                data-validation-error-msg="Please enter company name">
                        </div>
                        <div class="col-md-3  mb-3">
                            <span class="form-control-feedback">+91</span>
                            <input type="number" class="form-control border" name="user_mobile_no" id="mobile-number"
                                placeholder="Mobile Number" data-validation="required" maxlength="10"
                                aria-describedby="group-number"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                data-validation-error-msg="Please enter mobile number">
                            <!-- <div id="mobile-number-help" class="form-text">An OTP will be sent on your above entered
                        number for verification.
                        </div>-->
                        </div>
                        <div class="col-md-5  mb-3">
                            <span class="fa fa-envelope form-control-feedback"></span>
                            <input type="email" class="form-control" name="email_id" id="email" placeholder="Email "
                                data-validation-error-msg="Please enter email" data-validation="email"
                                autocomplete="off">
                            <div id="email-help" class="form-text">An OTP will be sent on your above entered email for
                                verification.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-lock form-control-feedback"></span>
                            <input type="password" class="form-control border" name="user_password" id="password"
                                placeholder="Password" data-validation="required"
                                data-validation-error-msg="Please enter password " autocomplete="off">
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-lock form-control-feedback"></span>
                            <input type="password" class="form-control border" name="confirm_password"
                                id="confirm_password" placeholder="Confirm Password" data-validation="required"
                                data-validation-error-msg="Please enter password " autocomplete="off">
                        </div>

                        <!-- <div class="col-md-4 hide mb-3" success-key-email="true">
                            <span class="fa fa-envelope form-control-feedback"></span>
                            <input type="number" class="form-control" name="email_otp" id="email-otp" placeholder="Enter OTP" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                        </div> -->
                        <!-- <div class="col-md-2  mb-3 d-flex md-5 d-none" email-verified-div="true">
                            <div class="valid-feedback d-block">
                                <label>
                                    <span class="">Verified</span>
                                </label>
                            </div>
                            <div class="invalid-feedback">
                                <label>
                                    <i class="fa fa-pencil mr-2"></i>
                                    <span class="">Edit Number</span>
                                </label>
                            </div>
                        </div> -->
                        <!-- <div class="col-auto col-lg-2 mb-3">
                            <button type="button" class="btn register_btn mb-3" success-key-email-btn="true" tts-url-email="<?php echo site_url('login/generate-otp') ?>" tts-generate-otp="true" otp-type="email">Send OTP
                            </button>
                        </div> -->
                    </div>
                    <div class="row">

                        <div class="col-md-8">
                            <h3 class="mb-3" pan-card-div="true">Pan Information</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <span class="fa fa-address-card form-control-feedback"></span>
                                    <input type="text" class="form-control" tts-pan-number="true" name="pan_number"
                                        id="pan-number" placeholder="Pan Number" data-validation="required"
                                        data-validation-error-msg="Please enter pan number" maxlength="10"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <span class="fa fa-address-card form-control-feedback"></span>
                                    <input type="text" class="form-control" name="pan_name" id="pan-name"
                                        placeholder="Pan Name " data-validation-error-msg="Please enter pan name"
                                        data-validation="required">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h3 class="mb-3">Upload Pan </h3>
                            <div class="row">
                                <div class="col-md-12 mb-3">

                                    <input class="form-control ps-2" name="pan_card" placeholder="Upload PAN Card "
                                        type="file" id="pan-card-file" data-validation="required"
                                        data-validation-error-msg="Please upload pan card">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <h3 class="mb-3">GST Information(Optional)</h3>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-address-card form-control-feedback"></span>
                            <input type="text" class="form-control" tts-gst-number="true" name="company_gst_no"
                                id="gst-number" placeholder="GST Number Optional" maxlength="15"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                            <div class="valid-feedback" gst-verified-div="true">
                                <label>
                                    <span class="">Verified</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 d-none" edit-gst-number-div="true">
                            <div class="valid-feedback d-block">
                                <label>
                                    <span class="">Verified</span>
                                </label>
                            </div>
                            <div class="invalid-feedback d-block">
                                <label edit-gst-number="true">
                                    <i class="fa fa-pencil mr-2"></i>
                                    <span class="">Edit Number</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="mb-3">Address Information</h3>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-user form-control-feedback"></span>
                            <input type="text" class="form-control" name="first_name" id="first_name"
                                placeholder="First Name" data-validation="required"
                                data-validation-error-msg="Please enter first name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-user form-control-feedback"></span>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                placeholder="Last Name" data-validation="required"
                                data-validation-error-msg="Please enter last name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="fa fa-address-card form-control-feedback"></span>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Address"
                                data-validation="required" data-validation-error-msg="Please enter address">
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="fa fa-address-card form-control-feedback"></span>
                            <input type="number" class="form-control" name="aadhaar_no" id="address"
                                placeholder="Aadhaar No." maxlength="12"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                data-validation="required" data-validation-error-msg="Please aadhaar no">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input class="form-control ps-2" name="aadhaar_scan_copy" placeholder="Upload Aadhaar Card "
                                type="file" data-validation="required"
                                data-validation-error-msg="Please upload aadhaar card">
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select select_search country_select_agent" name="country" id="country"
                                placeholder="Country" data-validation="required"
                                data-validation-error-msg="Please enter country">
                                <option value="" selected>select country</option>
                                <?php if ($country_list) {
                                    foreach ($country_list as $country) {
                                        ?>
                                        <option value="<?php echo $country['id'] ?>">
                                            <?php echo $country['name'] ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select select_search state_select_agent" name="state" id="state"
                                placeholder="State" data-validation="required"
                                data-validation-error-msg="Please enter state">
                                <option value="" selected>select state</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <select class="form-select select_search city_select_agent" name="city" id="city"
                                placeholder="City" data-validation="required"
                                data-validation-error-msg="Please enter city">
                                <option value="" selected>select city</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="fa fa-map-pin form-control-feedback"></span>
                            <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Pin Code"
                                data-validation="required" data-validation-error-msg="Please enter pin code">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="terms_conditions" value="accepted"
                                id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                I accept the <a href="<?php echo site_url('pages/pages/terms-conditions'); ?>"
                                    target="_blank">terms & conditions</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 mb-0">
                        <button type="submit" id="agency-register-btn" class="btn register_submit">Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div data-message="true"></div>
<div data-message-email="true"></div>
<style>
    body {
        background-color: #ffffff;
    }
</style>
<style>
    <?php echo whitelabel['custom_css']; ?>
</style>
<script type="text/javascript"
    src="<?php echo site_url('webroot'); ?>/vendor/select2/select2.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/select2/select2.min.js'); ?>"></script>

<script>
    $('.select_search').select2();


    let empstatus = [];
    empstatus['mobile_status'] = "success";
    $(document).on("click", ".help-block form-error", function () {
        $(this).remove();
    });
    $(document).on("click", ".help-block form-error", function () {
        $(this).remove();
    });
    $.validate({
        decimalSeparator: ','
    });


    $(document).on("click", "[tts-generate-otp = 'true']", function (e) {
        $(".help-block").remove();
        var type = $(this).attr('otp-type');
        var form = $("#tts-agency-register");
        if (type == 'mobile') {
            var data_key = $("#mobile-number").val();

            var url = $(this).attr('tts-url-mobile');
            if ($(this).attr('tts-url-mobile') == "<?php echo site_url('login/validate-otp') ?>") {
                var data_key = $("#mobile-otp").val();
            }

            $("[otp-type = 'mobile']").attr('disabled', true).html('Loading...');

        } else if (type == 'email') {
            var data_key = $("#email").val();
            var url = $(this).attr('tts-url-email');
            if ($(this).attr('tts-url-email') == "<?php echo site_url('login/validate-otp') ?>") {
                var data_key = $("#email-otp").val();
            }

            $("[otp-type = 'email']").attr('disabled', true).html('Loading...');
        }

        let gst_number = $("[tts-gst-number]").val();


        $.ajax({
            url: url,
            method: 'POST',
            data: {
                'type': type,
                'data_key': data_key
            },
            cache: false,
            success: function (resp) {

                let generateBtnText = $("[tts-generate-otp]").text();

                if (type == 'email') {
                    $("[otp-type = 'email']").attr('disabled', false).html("Send OTP");

                } else if (type == 'mobile') {
                    $("[otp-type = 'mobile']").attr('disabled', false).html("Send OTP");
                }


                if (resp.StatusCode == 0) {
                    if (type == 'email') {
                        $("[success-key-mobile-btn]").text('Verify OTP');
                        $("[otp-type = 'email']").attr('disabled', false).html('Send OTP');
                        $("[success-key-email]").removeClass('hide');
                        $("[success-key-email-btn]").text('Verify OTP');
                        $("[tts-url-email]").attr('tts-url-email', "<?php echo site_url('login/validate-otp') ?>");
                    } else if (type == 'mobile') {
                        $("[success-key-email-btn]").text('Send OTP');
                        $("[otp-type = 'mobile']").attr('disabled', false).html('Send OTP');
                        $("[success-key-mobile]").removeClass('hide');
                        $("[success-key-mobile-btn]").text('Verify OTP');
                        $("[tts-url-mobile]").attr('tts-url-mobile', "<?php echo site_url('login/validate-otp') ?>");

                    }

                    if (gst_number) {
                        if (empstatus['email_status'] == "success" && empstatus['mobile_status'] == "success") {
                            $("#agency-register-btn", form).attr('disabled', false);
                        }
                    } else {
                        if (empstatus['email_status'] == "success") {
                            $("#agency-register-btn", form).attr('disabled', false);
                        }
                    }

                } else if (resp.StatusCode == 3) {
                    if (type == 'mobile') {
                        $("[otp-type = 'mobile']").attr('disabled', false).html('Send OTP');
                        if (resp.verify_status == "success") {
                            empstatus['mobile_status'] = "success";
                            $("#mobile-number", form).attr('readonly', true);

                        }
                        $("[success-key-mobile]").addClass('hide');
                        $("[success-key-mobile-btn]").addClass('hide')
                    } else if (type == 'email') {
                        $("[otp-type = 'email']").attr('disabled', false).html('Send OTP');
                        if (resp.verify_status == "success") {
                            empstatus['email_status'] = "success";
                            $("#email", form).attr('readonly', true);
                        }
                        $("[success-key-email]").addClass('hide');
                        $("[success-key-email-btn]").addClass('hide')
                    }
                    if (gst_number) {
                        if (empstatus['email_status'] == "success" && empstatus['mobile_status'] == "success") {
                            $("#agency-register-btn", form).attr('disabled', false);
                        }
                    } else {
                        if (empstatus['email_status'] == "success") {
                            $("#agency-register-btn", form).attr('disabled', false);

                            $("[tts-gst-number]", form).attr('disabled', false);

                        }
                    }

                    if (empstatus['email_status'] == "success" && empstatus['mobile_status'] == "success") {
                        $("#pan-number1").attr('disabled', false)

                        $("#pan-number").attr('disabled', false)
                    }


                    if (type == 'email') {
                        //$("[data-message-email]").addClass(resp.class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                        $("[email-verified-div]").removeClass('d-none');
                    } else if (type == 'mobile') {
                        $("[mobile-verified-div]").removeClass('d-none');
                    } else {
                        $("[data-message]").addClass(resp.class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                    }

                } else if (resp.StatusCode == 2) {

                    if (resp.type == 'email') {
                        $("[otp-type = 'email']").attr('disabled', false).html('Send OTP');
                    } else if (resp.type == 'mobile') {

                        $("[otp-type = 'mobile']").attr('disabled', false).html('Send OTP');

                    }

                    $.each(resp.ErrorMessage, function (key, val) {

                        $('[name="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');

                    });
                } else if (resp.StatusCode == 5) {
                    if (resp.type == 'email') {
                        $("[success-key-email-btn]").text('Verify OTP');
                    } else if (resp.type == 'mobile') {
                        $("[success-key-mobile-btn]").text('Verify OTP');
                    }

                    $.each(resp.ErrorMessage, function (key, val) {

                        $('[name="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');

                    });
                } else {
                    $("[data-message]").addClass(resp.class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                }

            },
            error: function (res) {
                alert("Unexpected error! Try again.");
            }
        });
    });


    $(document).on("submit", "[tts-form-register='true']", function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var name = form.attr('name');
        $("[data-message]").removeClass().html("");
        $("[data-message-email]").removeClass().html("");
        var buttontxt;
        if ($("input[type=submit]", form).attr('value')) {
            buttontxt = $("input[type=submit]", form).attr('value');
            $("input[type=submit]", form).attr('disabled', true).val('Loading...');
        } else {
            buttontxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).attr('disabled', true).html('Loading...');
        }
        $("span.help-block form-error", form).replaceWith("");

        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (resp) {
                if ($("input[type=submit]", form).attr('value')) {
                    $("input[type=submit]", form).attr('disabled', false).val(buttontxt);
                } else {
                    $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                }
                if (resp.StatusCode == 1) {
                    $.each(resp.ErrorMessage, function (key, val) {
                        if (key.indexOf("[]") !== -1) {
                            var input = document.getElementsByName(key);
                            for (var i = 0; i < input.length; i++) {
                                var a = input[i];
                                if (a.value != '') { } else {
                                    $(a).after('<span class="help-block form-error">' + val + '</span>');
                                }
                            }
                        } else {
                            $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');
                        }
                    });
                } else if (resp.StatusCode == 3) {
                    window.location.replace(resp.redirect_url);
                } else if (resp.StatusCode == 0) {

                    if (resp.Reload && resp.Reload == 'false') {
                        if (resp.loadurl) {
                            var url = resp.loadurl;
                            $.get(url).done(function (data) {
                                $("[hideloader]").replaceWith("");
                                $("[data-lead-content]").html(data);
                            }).fail(function () {
                                alert("Unexpected error! Try again.");
                            });
                        }
                        if (resp.DivReload == 'true') {

                        }

                    } else {
                        setTimeout(function () {
                            window.location.reload();
                        }, 10);
                    }
                    if (resp.FormBlank) {
                        if (resp.FormBlank == 'false') {

                        } else {
                            form[0].reset();
                        }
                    }
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);

                } else {
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                }

            },
            error: function (res) {
                alert("Unexpected error! Try again.");
                // location.reload();
            }
        });
    });

    $(document).on("click", "[edit-gst-number]", function () {
        $("[tts-gst-number]").attr('readonly', false);

        $("[tts-gst-number-btn-div]").removeClass('d-none');

        $("[edit-gst-number-div]").addClass('d-none')
    });


    $('.country_select_agent').on('change', function () {
        var d = $('.country_select_agent').val();
        var hiturl = $("#site_url").val();
        $.ajax({
            url: "<?php echo site_url('login/state') ?>",
            type: 'POST',
            data: {
                'id': d
            },
            success: function (data) {
                $('.state_select_agent').html("<option value=''>select state</option>" + data);
            }
        })

    })

    $('.state_select_agent').on('change', function () {
        var city = $('.state_select_agent').val();
        var hiturl = $("#site_url").val();
        $.ajax({
            url: "<?php echo site_url('login/city') ?>",
            type: 'POST',
            data: {
                'id': city
            },
            success: function (data) {
                $('.city_select_agent').html("<option value=''>city</option>" + data);
            }
        })

    })


    $(document).on("change", 'input[name="firm_type"]', function () {
        let firm_type = $('input[name="firm_type"]:checked').val();

        if (firm_type == "Partnership") {
            $("[tts-pan-card1]").removeClass('d-none');
            $("[pan-card-div]").html('Partners PAN Information (Verify atleast 2)')
        } else {
            $("[tts-pan-card1]").addClass('d-none');
            $("[pan-card-div]").html('Pan Information');
        }
    });
</script>