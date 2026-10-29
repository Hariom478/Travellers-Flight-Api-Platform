<style>
    .tts-holiday-review-details {
        background: #fff;
        border: 1px solid rgba(156, 170, 179, 0.28);
        -webkit-box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
        padding: 10px 15px;
        border-radius: 5px;
    }

    .flightDetailWrapper .tts-holiday-review-details img {
        height: 400px;
        object-fit: cover;
        width: 100%;
    }

    .flightDetailWrapper .tts-holiday-review-details ul {
        flex-flow: row wrap;
        box-sizing: border-box;
        display: flex;
        place-content: stretch flex-start;
        align-items: stretch;
    }

    .flightDetailWrapper .tts-holiday-review-details ul li {
        width: calc(50% - 50px);
        cursor: pointer;
        padding: 15px;
        position: relative;
        transition: all .5s ease-in .1s;
    }

    .flightDetailWrapper .tts-holiday-review-details ul li h6 {
        margin: 0 !important;
    }

    .flightDetailWrapper .tts-holiday-review-details ul li.Duration-list h6 {
        margin: 0 !important;
        color: #000;
    }

    .flightDetailWrapper .tts-holiday-review-details ul li.Duration-list {
        width: 100px;
        text-align: center;
    }
</style>

<div class="flightDetailWrapper pt-4 pb-4">
    <div class="container">
        <?php if ($details['Error']['ErrorCode'] == 0) {
            $room_data = $details['Result']['SelectedRoomOccupancy'];
            $AdultPanCardRequired = $details['Result']['AdultPanCardRequired'];
            $AdultPassportRequired = $details['Result']['AdultPassportRequired'];

            $ChildPanCardRequired = $details['Result']['ChildPanCardRequired'];
            $ChildPassportRequired = $details['Result']['ChildPassportRequired'];
            $faredetail = $details['Result']['PriceBreakup'];


            ?>
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12">
                    <div class="tts-holiday-review-details">
                        <?php $filename = root_url . 'uploads/holiday/thumbnail/'; ?>
                        <?php if (isset($details['Result']['HolidayImage'])) { ?>
                            <img src="<?php echo $filename . $details['Result']['HolidayImage'] ?>" class="img-fluid">
                        <?php } else { ?>
                            <img src="<?php echo site_url('webroot/img/resort.png') ?>" class="img-fluid">
                        <?php } ?>

                        <div class="">
                            <ul class="">
                                <li>
                                    <h6>Package</h6>
                                    <h5><?php echo $details['Result']['Name'] ?> </h5>

                                </li>

                                <li class="Duration-list">
                                    <h6>Category</h6>
                                    <h5><?php echo $details['Result']['HolidayCategory'] ?> </h5>
                                </li>

                                <li class="text-end">
                                    <h6>Duration</h6>
                                    <h5><?php echo $night = $details['Result']['DaysNights']; ?></h5>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <form action="<?php echo site_url('holiday/validate-travellers'); ?>" method="post" method="post"
                        tts-form="true" name="holiday-booking">
                        <input type="hidden" name="SearchTokenId" value="<?php echo $details['SearchTokenId']; ?>">


                        <input type="hidden" name="adult_pancard_required" value="<?php echo $AdultPanCardRequired; ?>">
                        <input type="hidden" name="adult_passport_required" value="<?php echo $AdultPassportRequired; ?>">

                        <input type="hidden" name="child_pancard_required" value="<?php echo $ChildPanCardRequired; ?>">
                        <input type="hidden" name="child_passport_required" value="<?php echo $ChildPassportRequired; ?>">

                        <input type="hidden" name="TravelDate" value="<?php echo $details['Result']['TravelDate'] ?>">

                        <div class="flightLeftWrapper">
                            <div class="flightTraveller mt-4 mb-4">
                                <p>Traveller Details</p>
                                <div class="flightTravellerDetail">
                                    <p><i class="fa fa-info-circle"></i> Please make sure you enter the Name as per your
                                        Government photo id.</p>

                                    <?php
                                    foreach ($room_data as $room_key => $room) {
                                        $total_adult = $room['total_adult'];

                                        $total_child = $room['total_child'];
                                        ?>
                                        <div class="pax-repeat-div my-3">
                                            <div class="card">
                                                <div class="card-header card_header">
                                                    <span class="title ps-0">Room <?php echo $room_key + 1; ?></span>
                                                </div>
                                                <div class="card-body">
                                                    <?php
                                                    for ($adult = 1; $adult <= $total_adult; $adult++) { ?>
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-2 col-12 mb-3 fw-bold">
                                                                Adult <?php echo $adult; ?>
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <select class="form-select form-control"
                                                                    name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][title]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Title</option>
                                                                    <option value="Mr">Mr</option>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Mrs">Mrs</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <input type="text" class="form-control" placeholder="First Name"
                                                                    name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][first_name]"
                                                                    data-validation="required alphanumeric"
                                                                    data-validation-error-msg-required="First Name is required"
                                                                    data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <input type="text" class="form-control" placeholder="Last Name"
                                                                    name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][last_name]"
                                                                    data-validation="required alphanumeric"
                                                                    data-validation-error-msg-required="Last Name is required"
                                                                    data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <select class="form-select form-control"
                                                                    name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][gender]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Gender</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                            </div>
                                                            <?php if ($AdultPanCardRequired) { ?>
                                                                <div class="col-lg-2 col-12 mb-3">
                                                                    <input type="text" class="form-control" placeholder="PAN Card"
                                                                        name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][pancard]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="PAN Card is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($AdultPassportRequired) { ?>
                                                            <div class="row">
                                                                <div class="col-lg-2 col-12 mb-3">
                                                                    Passport Detail
                                                                </div>
                                                                <div class="col-lg-3 col-12 mb-3">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Number"
                                                                        name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][passport_no]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Number is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                                </div>
                                                                <div class="col-lg-3 col-12 mb-3">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Issue Date"
                                                                        name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][passport_issue_date]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Issue Date is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date"
                                                                        hotel_pass_issue="true" readonly>
                                                                </div>
                                                                <div class="col-lg-3 col-12 mb-3">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Expire Date"
                                                                        name="pax[<?php echo $room_key; ?>][Adult][<?php echo $adult; ?>][passport_expire_date]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Expire Date is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date"
                                                                        holiday_pass_expiry="true" readonly>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        if ($adult == $total_adult) {
                                                            echo "";
                                                        }
                                                    } ?>

                                                    <?php $counter = 0;
                                                    for ($child = 1; $child <= $total_child; $child++) {



                                                        ?>


                                                        <div class="row align-items-center">
                                                            <div class="col-lg-2 col-12 mb-3 fw-bold">
                                                                Child <?php echo $child; ?>
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <select class="form-select form-control"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][title]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Title</option>
                                                                    <option value="Ms">Ms</option>
                                                                    <option value="Master">Master</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <input type="text" class="form-control" placeholder="First Name"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][first_name]"
                                                                    data-validation="required alphanumeric"
                                                                    data-validation-error-msg-required="First Name is required"
                                                                    data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <input type="text" class="form-control" placeholder="Last Name"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][last_name]"
                                                                    data-validation="required alphanumeric"
                                                                    data-validation-error-msg-required="Last Name is required"
                                                                    data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                            </div>
                                                            <?php if ($ChildPanCardRequired) { ?>
                                                                <div class="col-lg-2 col-12 mb-3">
                                                                    <input type="text" class="form-control" placeholder="PAN Card"
                                                                        name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][pancard]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="PAN Card is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                                </div>
                                                            <?php } ?>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <input type="text" class="form-control" placeholder="Age"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][age]"
                                                                    data-validation="required number"
                                                                    data-validation-error-msg-required="Age is required"
                                                                    data-validation-error-msg-number="Please enter a valid Age"
                                                                    value="<?php echo "Age- " . $room['child_details'][$counter]['child_age']; ?> Yr"
                                                                    readonly>

                                                                <input type="hidden"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][child_age]"
                                                                    value="<?php echo $room['child_details'][$counter]['child_age']; ?>">

                                                                <input type="hidden"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][child_need_bed]"
                                                                    value="<?php echo $room['child_details'][$counter]['child_need_bed']; ?>">
                                                            </div>
                                                            <div class="col-lg-2 col-12 mb-3">
                                                                <select class="form-select form-control"
                                                                    name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][gender]"
                                                                    data-validation="required"
                                                                    data-validation-error-msg="Title is required">
                                                                    <option value="">Gender</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php if ($ChildPassportRequired) { ?>
                                                            <div class="row">
                                                                <div class="col-lg-2 col-12 mb-3">
                                                                    Passport Detail
                                                                </div>
                                                                <div class="col-lg-3 col-12">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Number"
                                                                        name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][passport_no]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Number is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                                </div>
                                                                <div class="col-lg-3 col-12">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Issue Date"
                                                                        name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][passport_issue_date]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Issue Date is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Issue Date"
                                                                        hotel_pass_issue="true" readonly>
                                                                </div>
                                                                <div class="col-lg-3 col-12">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Passport Expire Date"
                                                                        name="pax[<?php echo $room_key; ?>][Child][<?php echo $child; ?>][passport_expire_date]"
                                                                        data-validation="required alphanumeric"
                                                                        data-validation-error-msg-required="Passport Expire Date is required"
                                                                        data-validation-error-msg-alphanumeric="Please enter a valid Passport Expire Date"
                                                                        hotel_pass_expiry="true" readonly>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                        $counter += 1;
                                                        if ($child == $total_child) {
                                                            echo "";
                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                            <div class="flightTravellerContact mb-4">
                                <p>Contact Details</p>
                                <div class="flightContactMail">
                                    <div class="mailHead">
                                        <img class="flightContactImg"
                                            src="<?php echo site_url('webroot/img/svg_icon') ?>/flight-detail-mail.svg"
                                            alt="mail" />
                                        <p>Your ticket and holiday information will be sent here.</p>
                                    </div>
                                    <div class="row mt-3">

                                        <div class="col-lg-2 col-md-4 col-5">
                                            <select class="form-select form-control select_search" name="dial_code"
                                                data-validation="required"
                                                data-validation-error-msg="Dial Code is required">
                                                <option value="">Dial Code</option>
                                                <?php if ($dial_code) {
                                                    foreach ($dial_code as $code) { ?>
                                                        <option value="<?php echo $code['phonecode']; ?>" <?php if ($code['phonecode'] == 91) {
                                                               echo "selected";
                                                           } ?>><?php echo $code['name']; ?>
                                                            ( <?php echo $code['phonecode']; ?>)
                                                        </option>
                                                    <?php }
                                                } ?>

                                            </select>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-7">
                                            <input type="text" class="form-control" name="mobile_number"
                                                placeholder="Mobile Number" data-validation="required number length"
                                                data-validation-length="7-15"
                                                value="<?php echo session()->get('wl_agent')['mobile_no'] ?>"
                                                data-validation-error-msg-required="Please enter Mobile Number"
                                                data-validation-error-msg-number="Please enter a valid Mobile Number"
                                                data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <input type="text" class="form-control" name="email" placeholder="Email"
                                                data-validation="required email"
                                                value="<?php echo session()->get('wl_agent')['login_email'] ?>"
                                                data-validation-error-msg-required="Please enter Email"
                                                data-validation-error-msg-email="Please enter a valid Email" />
                                        </div>
                                    </div>
                                </div>

                                <div class="flightGstNumber mt-4">
                                    <h6>
                                        Use GSTIN for this booking(Optional)

                                        <button class="btn btn-outline-danger float-end" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse-bus-gst"
                                            aria-expanded="false" aria-controls="collapse-bus-gst"
                                            onclick="gst_detail(this,'add_gst_detail')">ADD
                                        </button>


                                    </h6>
                                    <span>Claim credit of GST charges. Your taxes may get updated post submitting your
                                        GST
                                        details.</span>
                                    <div class="collapse" id="collapse-bus-gst">
                                        <div class="card card-body">
                                            <div class="row  align-items-center">
                                                <input type="hidden" name="add_gst_detail" id="add_gst_detail"
                                                    value="false">
                                                <div class="col-lg-4 col-md-4 col-12 mb-3">
                                                    <input type="text" class="form-control" name="gst[number]"
                                                        placeholder="GST Number" data-validation="required alphanumeric"
                                                        data-validation-error-msg-required="Please enter GST Number"
                                                        data-validation-error-msg-alphanumeric="Please enter a valid GST Number">
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <input type="text" class="form-control" name="gst[name]"
                                                        placeholder="Registered Company Name" data-validation="required"
                                                        data-validation-error-msg-required="Please enter Company Name" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <input type="text" class="form-control" name="gst[phone]"
                                                        placeholder="Registered Contact No"
                                                        data-validation="required number" data-validation-length="7-15"
                                                        data-validation-error-msg-required="Please enter Contact No"
                                                        data-validation-error-msg-number="Please enter a valid Contact No"
                                                        data-validation-error-msg-length="Please enter 7-15 digit mobile number." />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <input type="text" class="form-control" name="gst[email]"
                                                        placeholder="Registered Email" data-validation="required email"
                                                        data-validation-error-msg-required="Please enter Email"
                                                        data-validation-error-msg-email="Please enter a valid Email" />
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <input type="text" class="form-control" name="gst[address]"
                                                        placeholder="Registered Address" data-validation="required"
                                                        data-validation-error-msg-required="Please enter Address" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="continuePayment mb-4">
                                <button type="submit" class="btn btn-link">Continue Payment</button>
                            </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-12">
                <div class="flightRightWrapper">
                    <p class="flightFare">Price Summary</p>
                    <div class="flightFareTypes">
                        <div id="accordion">
                            <div class="card card1">

                                <?php foreach ($FareBreakUp['FareBreakup'] as $key => $fareBreakUp):
                                    if ($fareBreakUp['Value']):
                                        ?>
                                        <div class="card-header card_header">
                                            <div class="row">
                                                <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                    <ul>
                                                        <li><?= $fareBreakUp['LabelText'] ?></li>
                                                    </ul>
                                                    <ul>
                                                        <li>₹ <?php echo $fareBreakUp['Value']; ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; endforeach; ?>
                                <div class="card-header card_header">
                                    <div class="row">
                                        <div class="col-md-12 d-flex align-items-center justify-content-between">
                                            <ul>
                                                <li><?= $FareBreakUp['TotalAmount']['LabelText'] ?></li>
                                            </ul>
                                            <ul>
                                                <li>₹ <?= $FareBreakUp['TotalAmount']['Value'] ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-12 tts-api-error-msg" ng-if="ErrorCode!=0">
                <img src="<?php echo site_url('webroot/img/no-cab-found.png'); ?>">
                <h5 class="mt-4"> <?php echo $details['Error']['ErrorMessage']; ?></h5>
                <a href="<?php echo site_url('holiday'); ?>" class="btn btn-outline-danger">SEARCH HOLIDAYS</a>
            </div>
        <?php } ?>
    </div>
</div>

<!-- error Modal -->
<div class="modal fade" id="error-modal" tabindex="-1" aria-labelledby="error-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong tts-error-message="true"></strong>
            </div>
        </div>
    </div>
</div>