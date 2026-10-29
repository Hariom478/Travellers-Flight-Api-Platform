<style>
    .tts-meal-section {
        background: #fff;
        border: 1px solid rgba(156, 170, 179, 0.28);
        -webkit-box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 0 9px 0 rgb(0 0 0 / 10%);
        padding: 10px 15px;
        border-radius: 5px;
    }
</style>
<div class="flightDetailWrapper" ng-app="flightDetailApp" ng-controller="flightDetailCtrl">
    <div class="container">
        <?php

        $adult_dob_requird_airline = array('I5');
        $airlinecodesarray = array();
        $adultDob = false;

        $originOB = '';
        $destinationOB = '';

        $originIB = '';
        $destinationIB = '';


        if (isset($confirmationResponse['Error']['ErrorCode']) && $confirmationResponse['Error']['ErrorCode'] == 0) { ?>
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12">
                    <div class="flightLeftWrapper">
                        <div class="flightHeadWrap">
                            <p>Review Your Flight Details</p>
                            <?php if (isset($_GET['token']) && $_GET['token'] != "") { ?>
                                <a href="<?php echo site_url('flight/search?token=' . $_GET['token']) ?>">
                                    < Back To Search </a>
                            <?php } else { ?>
                                <a href="javascript:void(0);" onclick="history.back()">
                                    < Back To Search </a>
                            <?php } ?>
                        </div>
                        <?php
                        foreach ($confirmationResponse['flightConfrimationResponse'] as $journeykey => $journeys) {
                            foreach ($journeys['Result']['Segments'] as $tripkey => $trips) {
                                $Duration = array_column($trips, "Duration");
                                /* $TotalDurationMin = array_sum($Duration); */
                                $firstSegment = reset($trips);
                                $lastegment = end($trips);
                                $airlinecodesarray[] = $firstSegment['Airline']['AirlineCode'];
                                $TotalDurationMin = $firstSegment['TotalDuration'];
                                if ($journeykey == 'OB') {
                                    $originOB = $firstSegment['Origin']['CityName'];
                                    $destinationOB = $lastegment['Destination']['CityName'];
                                }
                                if ($journeykey == 'IB') {
                                    $originIB = $firstSegment['Origin']['CityName'];
                                    $destinationIB = $lastegment['Destination']['CityName'];
                                }

                                ?>
                                <div class="flightBookDetail">
                                    <div class="flightPoint">
                                        <div class="row align-items-center">
                                            <div class="col-lg-8 col-md-8 col-12">
                                                <h4>
                                                    <?php echo ($journeykey == 'OB' && $tripkey == 0) ? "DEPART" : "Return"; ?>
                                                    <span> <?php echo $firstSegment['Origin']['CityName'] ?> → <?php echo $lastegment['Destination']['CityName'] ?> </span>
                                                </h4>

                                                <h5>
                                                    <?php echo $firstSegment['Origin']['CityName'] ?><span class="text">|</span> <?php $stops = count($trips);
                                                       echo $stops == 0 ? "Non Stop" : ($stops - 1) . " Stops"; ?><span
                                                            class="text">|</span><?php echo get_convertToHoursMinsfromMinDuration($TotalDurationMin); ?>
                                                </h5>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-12 d-flex align-items-center justify-content-end">
                                                <?php if ($journeys['Result']['IsRefundable']) { ?>
                                                    <p class="partialRef text-success" style="margin-right: 15px">
                                                        Refundable
                                                    </p>
                                                <?php } else { ?>
                                                    <p class="partialRef text-danger" style="margin-right: 15px">
                                                        None Refundable
                                                    </p>
                                                <?php } ?>
                                                <?php /* if(0) { */?>
                                                <a class="" href="javascript:void(0);"
                                                   onclick="getFareRule('<?php echo $journeys['SearchTokenId'] ?>','<?php echo $journeys['Result']['ResultIndex']; ?>')">Fare
                                                    Rules</a>
                                                <?php /* } */?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($trips) {
                                        foreach ($trips as $segmentIndicatorkey => $segment) {
                                            ?>
                                            <div class="flightTypeDetail align-items-center justify-content-between justify-content-ms-between ">
                                                <div class="flightTypeName">
                                                    <h3>
                                                        <span class="airline-logo intAirLogo size-28 x<?php echo $segment['Airline']['AirlineCode']; ?> "></span>
                                                        <span><?php echo $segment['Airline']['AirlineName']; ?></span><span
                                                                class="mt-1"><?php echo $segment['Airline']['AirlineCode']; ?> -<?php echo $segment['Airline']['FlightNumber']; ?></span>
                                                    </h3>
                                                </div>

                                                <ul class="list-unstyled flight_details_ul ">
                                                    <li>
                                                        <h2>Fare Class
                                                            <span> <?php echo isset($segment['Airline']['FareClass']) && $segment['Airline']['FareClass'] != "" ? $segment['Airline']['FareClass'] : "-"; ?></span>
                                                        </h2>
                                                    </li>
                                                    <li>
                                                        <h2>Aircraft <span> <?php echo $segment['Craft']; ?></span></h2>
                                                    </li>
                                                    <li>
                                                        <h2>Travel Class
                                                            <span><?php echo $segment['CabinClass']; ?></span></h2>
                                                    </li>
                                                    <li>
                                                        <h2>
                                                            Check-In Baggage
                                                            <span>Adult-<?php echo $segment['CheckInBaggage']; ?> Child-<?php echo $segment['CheckInBaggage']; ?> Infant-NA</span>
                                                        </h2>
                                                    </li>
                                                    <li>
                                                        <h2>
                                                            Cabin Baggage
                                                            <span>Adult-<?php echo $segment['CabinBaggage']; ?> Child-<?php echo $segment['CabinBaggage']; ?> Infant-<?php echo $segment['CabinBaggage']; ?></span>
                                                        </h2>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="flightDetailTiming">
                                                <ul class="list-unstyled align-items-center ">
                                                    <li>
                                                        <h2>
                                                            <?php echo get_flight_time($segment['Origin']['DepartTime']); ?>
                                                            <span class="timeDate"> <?php echo get_flight_date($segment['Origin']['DepartTime']); ?></span>
                                                            <span class="timePlace"><?php echo $segment['Origin']['CityName']; ?> <strong>[<?php echo $segment['Origin']['CityCode']; ?>]</strong></span>

                                                            <span class="timePlace"><?php echo $segment['Origin']['AirportName']; ?> </span>
                                                            <?php if ($segment['Origin']['Terminal'] != "") { ?>
                                                                <span class="timePlace"> Terminal <?php echo $segment['Origin']['Terminal']; ?> </span>
                                                            <?php } ?>
                                                        </h2>
                                                    </li>
                                                    <li>
                                                        <h3>
                                                            <?php echo get_convertToHoursMinsfromMinDuration($segment['Duration']); ?>
                                                            <div class="borderBottom"></div>
                                                            <img src="<?php echo site_url('webroot/img/svg_icon') ?>/flight-detail-plane-one.svg"
                                                                 alt=""/>
                                                        </h3>
                                                    </li>
                                                    <li>
                                                        <h2>
                                                            <?php echo get_flight_time($segment['Destination']['ArrivalTime']); ?>
                                                            <span class="timeDate"> <?php echo get_flight_date($segment['Destination']['ArrivalTime']); ?></span>
                                                            <span class="timePlace"><?php echo $segment['Destination']['CityName']; ?> <strong>[<?php echo $segment['Destination']['CityCode']; ?> ]</strong></span>

                                                            <span class="timePlace"><?php echo $segment['Destination']['AirportName']; ?> </span>
                                                            <?php if ($segment['Destination']['Terminal'] != "") { ?>
                                                                <span class="timePlace"> Terminal <?php echo $segment['Destination']['Terminal']; ?> </span>
                                                            <?php } ?>
                                                        </h2>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            <?php }
                        }
                        foreach ($airlinecodesarray as $airlinecodes) {

                            if (in_array($airlinecodes, $adult_dob_requird_airline)) {
                                $adultDob = true;
                                break;
                            }
                        }

                        ?>
                        <form action="<?php echo site_url('flight/validate-travellers'); ?>" method="post"
                              tts-form="true" name="flight-booking">
                            <div class="flightTraveller mt-4 mb-4">
                                <p>Traveller Details</p>
                                <div class="flightTravellerDetail">
                                    <p>
                                        Please make sure you enter the Name as per your Government
                                        photo id.
                                    </p>
                                    <input type="hidden" name="add_gst_detail" id="add_gst_detail"
                                           value="<?php echo $IsGSTMandatory; ?>">
                                    <input type="hidden" name="rtype"
                                           value="<?php echo isset($_GET['rtype']) ? $_GET['rtype'] : ""; ?>">
                                    <input type="hidden" name="pancard_requird" value="<?php echo $IsPanMandatory; ?>">
                                    <input type="hidden" name="adult_dob" value="<?php echo $adultDob; ?>">
                                    <input type="hidden" name="passport_requird"
                                           value="<?php echo $IsPassportMandatory; ?>">
                                    <input type="hidden" name="SearchTokenId" value="<?php echo $_GET['token'] ?>">
                                    <input type="hidden" name="farecode" value="<?php echo $_GET['farecode'] ?>">
                                    <input type="hidden" name="farecodereturn"
                                           value="<?php echo isset($_GET['farecodereturn']) ? $_GET['farecodereturn'] : ""; ?>">
                                    <input type="hidden" name="onwardagentwebpartnermarkup"
                                           value="{{onwardAgentWebPMarkUp}}">
                                    <input type="hidden" name="onwardagentwebpartnerdiscount"
                                           value="{{onwardAgentWebPDiscount}}">
                                           <input type="hidden" name="onwardagentdisplaymarkup"
                                           value="{{onwardAgentWebPDisplayMarkup}}">
                                    <input type="hidden" name="returnagentwebpartnermarkup"
                                           value="{{returnAgentWebPMarkUp}}">
                                    <input type="hidden" name="returnagentwebpartnerdiscount"
                                           value="{{returnAgentWebPDiscount}}">
                                    <input type="hidden" name="returnagentdisplayMarkup"
                                           value="{{returnAgentWebPDisplayMarkup}}">
                                    <?php
                                    $paxDobFormat = array('Adult' => "adult_dob_date", "Child" => "child_dob_date", "Infant" => "infant_dob_date");
                                    foreach ($searchPaxInfo as $paxKey => $noOfPax) {
                                        if ($noOfPax) { ?>
                                            <div class="pax-repeat-div my-3">
                                                <div class="card">
                                                    <div class="card-header card_header">
                                                        <span class="title ps-0"><?php echo $paxKey; ?> X <?php echo $noOfPax ?></span>
                                                    </div>
                                                    <div class="card-body">
                                                        <?php for ($paxCount = 1; $paxCount <= $noOfPax; $paxCount++) { ?>
                                                            <div class="row">
                                                                <div class="col-lg-2 col-12 mb-3 fw-bold">
                                                                    <?php echo $paxKey; ?>                <?php echo $paxCount; ?>
                                                                </div>
                                                                <div class="col-lg-2 col-12 mb-3">
                                                                    <select class="form-select form-control"
                                                                            name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][title]"
                                                                            data-validation="required"
                                                                            data-validation-error-msg="Title is required">
                                                                        <option value="">Title</option>
                                                                        <?php if ($paxKey == "Adult") { ?>
                                                                            <option value="Mr">Mr</option>
                                                                            <option value="Ms">Ms</option>
                                                                            <option value="Mrs">Mrs</option>
                                                                        <?php } else { ?>
                                                                            <option value="Ms">Ms</option>
                                                                            <option value="Mstr">Mstr</option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2 col-12">
                                                                    <input type="text" class="form-control"
                                                                           placeholder="First Name"
                                                                           name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][first_name]"
                                                                           data-validation="required alphanumeric"
                                                                           data-validation-error-msg-required="First Name is required"
                                                                           data-validation-error-msg-alphanumeric="Please enter a valid First Name">
                                                                </div>
                                                                <div class="col-lg-2 col-12">
                                                                    <input type="text" class="form-control"
                                                                           placeholder="Last Name"
                                                                           name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][last_name]"
                                                                           data-validation="required alphanumeric"
                                                                           data-validation-error-msg-required="Last Name is required"
                                                                           data-validation-error-msg-alphanumeric="Please enter a valid Last Name">
                                                                </div>
                                                                <?php if ($paxKey == "Adult") {
                                                                    $adult_dob_validation = "";
                                                                    if ($adultDob) {
                                                                        $adult_dob_validation = 'data-validation="required"';
                                                                    }

                                                                    ?>
                                                                    <div class="col-lg-2 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="DOB"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][dob]" <?php echo $adult_dob_validation; ?>
                                                                               data-validation-error-msg-required="DOB is required" <?php echo $paxDobFormat[$paxKey]; ?>
                                                                        ="true" readonly>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="col-lg-2 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="DOB"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][dob]"
                                                                               data-validation="required"
                                                                               data-validation-error-msg-required="DOB is required" <?php echo $paxDobFormat[$paxKey]; ?>
                                                                        ="true" readonly>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if ($IsPanMandatory) { ?>
                                                                    <div class="col-lg-2 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="PAN Card"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][pancard]"
                                                                               data-validation="required alphanumeric"
                                                                               data-validation-error-msg-required="PAN Card is required"
                                                                               data-validation-error-msg-alphanumeric="Please enter a valid PAN Card">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if ($IsGDS) { ?>
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-12 mb-3">
                                                                    </div>
                                                                    <div class="col-lg-4 col-12 mb-3">
                                                                        <a href="javascript:void(0);" class="collapsed"
                                                                           data-bs-toggle="collapse"
                                                                           data-bs-target="#collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>"
                                                                           aria-expanded="false"
                                                                           aria-controls="collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>"><b>Add
                                                                                Frequent Flyer Number</b></a>
                                                                    </div>
                                                                </div>
                                                                <div class="row collapse"
                                                                     id="collapse-frequentfly-<?php echo $paxKey . $paxCount; ?>">
                                                                    <div class="col-lg-2 col-12 mb-3">
                                                                    </div>
                                                                    <div class="col-lg-2 col-12 mb-3">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="Airline"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][frequent_fly_airline]"
                                                                               oninput="this.value = this.value.toUpperCase()">
                                                                    </div>
                                                                    <div class="col-lg-2 col-12 mb-3">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="Number"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][frequent_fly_number]">
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <?php $Passportrequired = "";
                                                            if ($IsPassportMandatory) {
                                                                $Passportrequired = "required";
                                                            }
                                                            if ($IsPassportMandatory || !($IsDomestic)) { ?>
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-12 mb-3">
                                                                        Passport Detail
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-4 col-5">
                                                                        <select class="form-select form-control select_search"
                                                                                name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][nationality]""
                                                                        data-validation="<?php echo $Passportrequired; ?>
                                                                        "
                                                                        data-validation-error-msg="Nationality">
                                                                        <option value="">Nationality</option>
                                                                        <?php if ($dial_code) {
                                                                            foreach ($dial_code as $code) { ?>
                                                                                <option value="<?php echo $code['iso2']; ?>"><?php echo $code['name']; ?>
                                                                                </option>
                                                                            <?php }
                                                                        } ?>

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-3 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="Passport Number"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_no]"
                                                                               data-validation="<?php echo $Passportrequired; ?> alphanumeric"
                                                                               data-validation-error-msg-required="Passport Number is required"
                                                                               data-validation-error-msg-alphanumeric="Please enter a valid Passport Number">
                                                                    </div>
                                                                    <div class="col-lg-2 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="Issue Date"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_issue_date]"
                                                                               data-validation="<?php echo $Passportrequired; ?>"
                                                                               data-validation-error-msg-required="Passport Issue Date is required"
                                                                               flight_pass_issue="true" readonly>
                                                                    </div>
                                                                    <div class="col-lg-2 col-12">
                                                                        <input type="text" class="form-control"
                                                                               placeholder="Expire Date"
                                                                               name="pax[<?php echo $paxKey; ?>][<?php echo $paxCount; ?>][passport_expire_date]"
                                                                               data-validation="<?php echo $Passportrequired; ?>"
                                                                               data-validation-error-msg-required="Passport Expire Date is required"
                                                                               flight_pass_expiry="true" readonly>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                            if ($paxCount != $noOfPax) {
                                                                echo "<hr>";
                                                            }
                                                        } ?>


                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>


                            <div class="flightTravellerContact mb-4">
                                <p>Contact Details</p>

                                <div class="flightContactMail">
                                    <div class="mailHead">
                                        <img class="flightContactImg"
                                             src="<?php echo site_url('webroot/img/svg_icon/flight-detail-mail.svg'); ?>"
                                             alt="mail">
                                        <p>Your ticket and flight details will be shared here</p>
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

                                        <div class="col-lg-4 col-md-4 col-7 ">
                                            <input type="text" class="form-control mb-3" name="mobile_number"
                                                   placeholder="Mobile Number" data-validation="required number length"
                                                   data-validation-length="7-15"
                                                   value="<?php echo session()->get('wl_agent')['mobile_no'] ?>"
                                                   data-validation-error-msg-required="Please enter Mobile Number"
                                                   data-validation-error-msg-number="Please enter a valid Mobile Number"
                                                   data-validation-error-msg-length="Please enter 7-15 digit mobile number."/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <input type="text" class="form-control mb-3" name="email"
                                                   placeholder="Email" data-validation="required email"
                                                   value="<?php echo session()->get('wl_agent')['login_email'] ?>"
                                                   data-validation-error-msg-required="Please enter Email"
                                                   data-validation-error-msg-email="Please enter a valid Email"/>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($GSTAllowed) { ?>
                                    <div class="flightGstNumber mt-4">
                                        <h6>
                                            Use GSTIN for this
                                            booking(<?php echo $IsGSTMandatory == "true" ? "Required" : "Optional"; ?>)
                                            <?php if (!$IsGSTMandatory) { ?>
                                                <button class="btn btn-outline-danger float-end" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse-flight-gst"
                                                        aria-expanded="false" aria-controls="collapse-bus-gst"
                                                        onclick="gst_detail(this,'add_gst_detail')">ADD
                                                </button>
                                            <?php } ?>
                                        </h6>
                                        <span>Claim credit of GST charges. Your taxes may get updated post submitting your GST
                                        details.</span>
                                        <div class="collapse <?php if ($IsGSTMandatory) {
                                            echo "show";
                                        } ?>" id="collapse-flight-gst">
                                            <div class="card card-body">
                                                <div class="row mt-3">

                                                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                                                        <input type="text" class="form-control" name="gst[number]"
                                                               placeholder="GST Number"
                                                               data-validation="required alphanumeric"
                                                               data-validation-error-msg-required="Please enter GST Number"
                                                               data-validation-error-msg-alphanumeric="Please enter a valid GST Number">
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-12">
                                                        <input type="text" class="form-control" name="gst[name]"
                                                               placeholder="Registered Company Name"
                                                               data-validation="required"
                                                               data-validation-error-msg-required="Please enter Company Name"/>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-12">
                                                        <input type="text" class="form-control" name="gst[phone]"
                                                               placeholder="Registered Contact No"
                                                               data-validation="required number"
                                                               data-validation-length="7-15"
                                                               data-validation-error-msg-required="Please enter Contact No"
                                                               data-validation-error-msg-number="Please enter a valid Contact No"
                                                               data-validation-error-msg-length="Please enter 7-15 digit mobile number."/>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-12">
                                                        <input type="text" class="form-control" name="gst[email]"
                                                               placeholder="Registered Email"
                                                               data-validation="required email"
                                                               data-validation-error-msg-required="Please enter Email"
                                                               data-validation-error-msg-email="Please enter a valid Email"/>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-12">
                                                        <input type="text" class="form-control" name="gst[address]"
                                                               placeholder="Registered Address"
                                                               data-validation="required"
                                                               data-validation-error-msg-required="Please enter Address"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>

                                                    <div class="flightGstNumber mt-4">
                                                        <h6>
                                                            Use GSTIN for this
                                                            booking(<?php echo $IsGSTMandatory == "true" ? "Required" : "Optional"; ?>)
                                                        </h6>

                                                        <div class="collapse show" id="collapse-flight-gst">
                                                            <div class="card card-body">
                                                                <div class="row mt-3">
                                                                    <div class="col-lg-12 col-md-4 col-12 mb-3">
                                                                        <b class="text-danger"> GST not applicable on this booking</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php } ?>
                                <!--<div class="flightGstNumber mt-4">
                                    <ul style="flex-direction: row; box-sizing: border-box; display: flex; place-content: stretch flex-start; align-items: stretch;">
                                        <li class="ng-star-inserted" style="margin-right: 15px;">
                                            <div style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center; align-items: center;">
                                                <span style="margin-right: 10px;"></span>
                                                <p>Add Meals</p>
                                            </div>
                                        </li>
                                        <li style="margin-right: 15px;">
                                            <div style="flex-direction: row; box-sizing: border-box; display: flex; place-content: center; align-items: center;">
                                                <span style="margin-right: 10px;"></span>
                                                <p>Add Baggage</p>
                                            </div>

                                        </li>
                                    </ul>
                                </div>-->

                                <?php if (isset($ssr['ssrOB']['Error']['ErrorCode']) && $ssr['ssrOB']['Error']['ErrorCode'] == 0) { ?>
                                    <div class="mt-4 tts-meal-section">
                                        <div class="mailHead">
                                            <p>Select Meal/Baggage (Optional)</p>
                                        </div>
                                        <?php
                                        foreach ($searchPaxInfo as $paxKey => $noOfPax) {
                                            if ($paxKey != "Infant") {
                                                if ($noOfPax) { ?>
                                                    <div class="pax-repeat-div my-3">
                                                        <div class="card">

                                                            <div class="card-header card_header">
                                                                <span class="title ps-0">Passenger - (<?php echo ucfirst($paxKey . ' X ' . $noOfPax); ?>)</span>
                                                            </div>

                                                            <?php for ($paxCount = 1; $paxCount <= $noOfPax; $paxCount++) { ?>
                                                                <?php if ($originOB && $destinationOB) { ?>

                                                                                                                    <div class="card-body">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-lg-2 col-12 mb-3 ">
                                                                                                                                <span class="fw-bold"> <?php echo $paxKey . ' - '; ?><?php echo $paxCount; ?></span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <div class="row">


                                                                                                                            <?php if ($ssr['ssrOB']['Error']['ErrorCode'] == 0) {

                                                                                                                                if ($IsGDS == 1) {
                                                                                                                                    $meals_sector = $ssr['ssrOB']['Result']['Meal'];
                                                                                                                                    ?>

                                                                                                                                                        <div class="col-lg-5 col-12 mb-3 ">
                                                                                                                                                            <span class="fw-bold">Add Meal <?php echo $originOB; ?> → <?php echo $destinationOB; ?> </span><br/>
                                                                                                                                                            <select class="form-select form-control"
                                                                                                                                                                    name="">
                                                                                                                                                                <option value="">Add Meal
                                                                                                                                                                </option>
                                                                                                                                                                <?php foreach ($meals_sector as $meal) { ?>
                                                                                                                                                                    <option value=""><?php echo $meal['AirlineDescription'] . ' ' . "(Price: {$meal['Price']} Qty:{$meal['Quantity']})" ?></option>
                                                                                                                                                        <?php } ?>

                                                                                                                                                            </select>
                                                                                                                                                        </div>
                                                                                                                                                    <?php
                                                                                                                                } else {

                                                                                                                                    $meals_sector = $ssr['ssrOB']['Result']['Meal'];
                                                                                                                                    foreach ($meals_sector as $sector => $meals) {
                                                                                                                                        $origin_destination = explode('-', $sector);

                                                                                                                                        ?>

                                                                                                                                                                <div class="col-lg-4 col-12 mb-3 ">
                                                                                                                                                                    <span class="fw-bold">Add Meal <?php echo $origin_destination[0]; ?> → <?php echo $origin_destination[1]; ?> </span><br/>
                                                                                                                                                                    <select class="form-select form-control"
                                                                                                                                                                            name="">
                                                                                                                                                                        <option value="">Add Meal
                                                                                                                                                                        </option>
                                                                                                                                                                        <?php foreach ($meals as $meal) { ?>
                                                                                                                                                                            <option value=""><?php echo $meal['AirlineDescription'] . ' ' . "(Price: {$meal['Price']} Qty:{$meal['Quantity']})" ?></option>
                                                                                                                                                                <?php } ?>

                                                                                                                                                                    </select>
                                                                                                                                                                </div>
                                                                                                                                                    <?php }
                                                                                                                                }
                                                                                                                            } ?>
                                                                                                                        </div>
                                                                                                                        <div class="row">

                                                                                                                            <?php if ($ssr['ssrOB']['Error']['ErrorCode'] == 0) {
                                                                                                                                $Baggage_sector = $ssr['ssrOB']['Result']['Baggage'];
                                                                                                                                foreach ($Baggage_sector as $sector => $Baggages) {
                                                                                                                                    $origin_destination = explode('-', $sector);
                                                                                                                                    ?>

                                                                                                                                                    <div class="col-lg-4 col-12 mb-3 ">
                                                                                                                                                        <span class="fw-bold">Add Baggage <?php echo $origin_destination[0]; ?> → <?php echo $origin_destination[1]; ?> </span><br/>
                                                                                                                                                        <select class="form-select form-control"
                                                                                                                                                                name="">
                                                                                                                                                            <option value="">Add Baggage
                                                                                                                                                            </option>
                                                                                                                                                            <?php foreach ($Baggages as $Baggage) { ?>
                                                                                                                                                                <option value=""><?php echo 'weight: ' . $Baggage['Weight'] . ' price ' . $Baggage['Price'] ?></option>
                                                                                                                                                    <?php } ?>
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                        <?php }
                                                                                                                            } ?>
                                                                                                                        </div>

                                                                                                                    </div>
                                                                                                        <?php }

                                                                ?>

                                                                <?php if ($originIB && $destinationIB) { ?>

                                                                                                                    <div class="card-body">
                                                                                                                        <div class="row">

                                                                                                                            <?php if (isset($ssr['ssrIB']['Error']['ErrorCode']) && $ssr['ssrIB']['Error']['ErrorCode'] == 0) {
                                                                                                                                $meals_sector = $ssr['ssrIB']['Result']['Meal'];
                                                                                                                                foreach ($meals_sector as $sector => $meals) {
                                                                                                                                    $origin_destination = explode('-', $sector);
                                                                                                                                    ?>

                                                                                                                                                    <div class="col-lg-5 col-12 mb-3 ">
                                                                                                                                                        <span class="fw-bold">Add Meal <?php echo $origin_destination[0]; ?> → <?php echo $origin_destination[1]; ?> </span><br/>
                                                                                                                                                        <select class="form-select form-control"
                                                                                                                                                                name="">
                                                                                                                                                            <option value="">Add Meal</option>
                                                                                                                                                            <?php foreach ($meals as $meal) { ?>
                                                                                                                                                                <option value=""><?php echo $meal['AirlineDescription'] . ' ' . "(Price: {$meal['Price']} Qty:{$meal['Quantity']})" ?></option>
                                                                                                                                                    <?php } ?>

                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                        <?php }
                                                                                                                            } ?>
                                                                                                                        </div>
                                                                                                                        <div class="row">

                                                                                                                            <?php if ($ssr['ssrIB']['Error']['ErrorCode'] == 0) {
                                                                                                                                $Baggage_sector = $ssr['ssrIB']['Result']['Baggage'];
                                                                                                                                foreach ($Baggage_sector as $sector => $Baggages) {
                                                                                                                                    $origin_destination = explode('-', $sector);
                                                                                                                                    ?>
                                                                                                                                    <div class="col-lg-5 col-12 mb-3 ">
                                                                                                                                        <span class="fw-bold">Add Baggage <?php echo $origin_destination[0]; ?> → <?php echo $origin_destination[1]; ?> </span><br/>
                                                                                                                                        <select class="form-select form-control"
                                                                                                                                                name="">
                                                                                                                                            <option value="">Add Baggage
                                                                                                                                            </option>
                                                                                                                                            <?php foreach ($Baggages as $Baggage) { ?>
                                                                                                                                                <option value=""><?php echo 'weight: ' . $Baggage['Weight'] . ' price' . $Baggage['Price'] ?></option>
                                                                                                                                    <?php } ?>

                                                                                                                                        </select>
                                                                                                                                    </div>
                                                                                                                        <?php }
                                                                                                                            } ?>

                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                        <?php }
                                                            } ?>
                                                        </div>
                                                    </div>
                                            <?php }
                                            }
                                        } ?>
                                    </div>
                                <?php } ?>
                                <?php if (!$processedbutton) { ?>
                                    <div class="flightGstNumber mt-4">
                                            <h6 class  =  "text-center text-danger"> Please contact to  administrator.  </h6>
                                        </div>
                                    <?php } ?>
                            </div>
                            <?php if ($processedbutton) { ?>
                                <div class="continuePayment mb-4">
                                    <button type="submit" class="btn btn-link">Continue Payment</button>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-12 d-none " faredetailview="true">
                
                    <div class="flightRightWrapper" ng-if="onwardFarelength > 0 ">
                       <div class="d-flex align-items-center justify-content-between">
                            <p class="flightFare">{{onwardfaretext}} Fare Summary</p>
                            <p class="form-check form-switch float-right pt-2"><input class="form-check-input" type="checkbox" role="switch"  ng-model="shownetfare" id="net-fare"><label class="form-check-label" for="net-fare">Show Net Fare</label></p>               
                            <p class="flightFare text-end"><a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#MarkupOptionModel"><i class="fa fa-edit" title=" Edit Markup/Discount"></i></a></p>
                       </div>
                        <div class="flightFareTypes">
                            <div>
                                <div class="card card1">
                                    <div class="card-header card_header"
                                         ng-repeat="(onwardFarekey, onwardFare) in FareInfoOB.FareBreakup">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>{{ onwardFare.LabelText}}</li>
                                                </ul>
                                                <ul>
                                                <li ng-if="onwardFarekey=='Taxes'">₹
                                                        {{onwardFare.Value + addonwardAgentWebPMarkUpInTax }}</li>
                                                    <li ng-if="onwardFarekey=='ServiceAndOtherCharge'">₹
                                                        {{onwardFare.Value + addonwardAgentWebPMarkUpInServiceCharge}}</li>
                                                    <li ng-if="onwardFarekey!='ServiceAndOtherCharge'  && onwardFarekey!='Discount' && onwardFarekey!='PublishedPrice'&&onwardFarekey!='Taxes'">
                                                        ₹ {{onwardFare.Value}}
                                                    </li>
                                                    <li ng-if="onwardFarekey=='Discount'">₹
                                                        {{onwardAgentWebPDiscount}}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer card_footer">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li><strong>{{FareInfoOB.TotalAmount.LabelText}} </strong></li>
                                                </ul>
                                                <ul>
                                                    <li>
                                                        <span><strong>₹ {{FareInfoOB.TotalAmount.Value+onwardAgentWebPMarkUp-onwardAgentWebPDiscount}} </strong></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer card_footer"   ng-if="shownetfare">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li><strong>{{FareInfoOB.OfferedFare.LabelText}} </strong></li>
                                                </ul>
                                                <ul>
                                                    <li>
                                                        <span><strong>₹ {{FareInfoOB.OfferedFare.Value}} </strong></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flightRightWrapper" ng-if="returnFarelength > 0 ">
                        <p class="flightFare">{{returnfaretext}} Fare Summary</p>
                        <div class="flightFareTypes">
                            <div>
                                <div class="card card1">

                                    <div class="card-header card_header"
                                         ng-repeat="(returnFarekey, returnFare) in FareInfoIB.FareBreakup">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li>{{ returnFare.LabelText}}</li>
                                                </ul>
                                                <ul>
                                                    <li ng-if="returnFarekey=='Taxes'">₹
                                                        {{returnFare.Value + addreturnAgentWebPMarkUpInTax }}</li>
                                                    <li ng-if="returnFarekey=='ServiceAndOtherCharge'">₹
                                                        {{returnFare.Value + addreturnAgentWebPMarkUpInServiceCharge }}</li>
                                                    <li ng-if="returnFarekey!='ServiceAndOtherCharge' && returnFarekey!='Discount' && returnFarekey!='PublishedPrice'&&returnFarekey!='Taxes'">
                                                        ₹ {{returnFare.Value}}
                                                    </li>
                                                    <li ng-if="returnFarekey=='Discount'">₹
                                                        {{returnAgentWebPDiscount}}
                                                    </li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer card_footer">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li><strong>{{FareInfoIB.TotalAmount.LabelText}} </strong></li>
                                                </ul>
                                                <ul>
                                                    <li>
                                                        <span><strong>₹ {{FareInfoIB.TotalAmount.Value+returnAgentWebPMarkUp-returnAgentWebPDiscount}} </strong></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer card_footer"   ng-if="shownetfare">
                                        <div class="row">
                                            <div class="col-md-12 d-flex align-items-center justify-content-between">
                                                <ul>
                                                    <li><strong>{{FareInfoIB.OfferedFare.LabelText}} </strong></li>
                                                </ul>
                                                <ul>
                                                    <li>
                                                        <span><strong>₹ {{FareInfoIB.OfferedFare.Value}} </strong></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo isset($confirmationResponse['Error']['ErrorMessage']) ? $confirmationResponse['Error']['ErrorMessage'] : "No Result Found."; ?></h4>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="modal fade" id="MarkupOptionModel" tabindex="-1" aria-labelledby="error-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-default">
        <div class="modal-content w-100">
            <div class="modal-header">
            <h5 class="modal-title">MarkUp/Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="pos-rel" ng-if="onwardFarelength >0">
                                                <div class="row">
                                                <div class="col-sm-12"><h5 class="">{{onwardfaretext}}</h5>
                                                    </div>
                                                    <hr>
                                                    <div class="col-sm-12 ">
                                                        <div class="floating-label"><label for="markupPrice_feild"
                                                                                           class="">Display Markup</label>
                                                    <select  class="form-control" get-display-markup-value-OB="true">
                                                                                            <option value  =  "in_tax"    ng-selected="onwardAgentWebPDisplayMarkup=='in_tax'" >In Tax</option>
                                                                                            <option value  = "in_service_charge" ng-selected="returnAgentWebPDisplayMarkup=='in_service_charge'">In Service Charge</option>
                                                                                            </select>
                                                        </div>
                                                        <div class="floating-label"><label for="markupPrice_feild"
                                                                                           class="">Markup
                                                                Price</label><input type="number" class="form-control"
                                                                                    placeholder="Markup Price"
                                                                                    get-markup-value-OB="true" value  =  "{{onwardAgentWebPMarkUp}}">
                                                        </div>
                                                        <div class="floating-label"><label>Discount
                                                                Price</label><input type="number" class="form-control"
                                                                                    placeholder="Discount Price"
                                                                                    get-discount-value-OB="true" value  =  "{{onwardAgentWebPDiscount}}">
                                                        </div>
                                                    </div> 
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-sm btn-tts-markup mt-3 mb-3"
                                                                ng-click="getMarkupValue('OB')">Update
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="col-sm-12"><h5 class="text-info"  markup-text-message =  "true">{{MarkupDiscountMessage}}</h5>
                                            </div>
            <div class="pos-rel "  ng-if="returnFarelength > 0 "> 
                                                <div class="row">
                                                    <div class="col-sm-12"><h5 class="">{{returnfaretext}}</h5>
                                                    </div>
                                                    <hr>
                                                    <div class="col-sm-12 ">
                                                    <div class="floating-label"><label for="markupPrice_feild"
                                                                                           class="">Display Markup</label>
                                                    <select  class="form-control" get-display-markup-value-IB="true">
                                                                                            <option value  =  "in_tax"    ng-selected="returnAgentWebPDisplayMarkup=='in_tax'" >In Tax</option>
                                                                                            <option value  = "in_service_charge" ng-selected="returnAgentWebPDisplayMarkup=='in_service_charge'">In Service Charge</option>
                                                                                            </select>
                                                        <div class="floating-label"><label for="markupPrice_feild"
                                                                                           class="">Markup
                                                                Price</label> <input type="number" min="0"
                                                                                     class="form-control"
                                                                                     placeholder="Markup Price"
                                                                                     get-markup-value-IB="true"  value  =  "{{returnAgentWebPMarkUp}}">
                                                        </div>
                                                        <div class="floating-label"><label>Discount
                                                                Price</label><input type="number" class="form-control"
                                                                                    placeholder="Discount Price"
                                                                                    get-discount-value-IB="true"
                                                                                    value  =  "{{returnAgentWebPDiscount}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-sm btn btn-sm btn-tts-markup mt-3 mb-0"
                                                                ng-click="getMarkupValue('IB')">Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" value="<?php echo trim(explode("T", $searchRequest['AirSegments'][0]['PreferredTime'])[0]); ?>"
       flightpassportMinDate="true">
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
<!------Start Flight fare rule modal------>
<div class="modal fade bd-example-modal-lg" id="FareRulesModel" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal_content">
            <div class="modal-header modal_header">
                <h5 class="modal-title">Fare Rules</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="hotel-main">
                    <!----- start loading ---->
                    <div class="row" fareRuleDeatailLoading="true">
                        <div class="col-md-12">
                            <div class="text-center">
                                <div class="loader mt-3 mb-3">
                                    <div role="status" class="spinner-grow text-primary">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div role="status" class="spinner-grow text-secondary">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div role="status" class="spinner-grow text-danger">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div role="status" class="spinner-grow text-dark">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <h5> Please Wait... </h5>
                            </div>
                        </div>
                    </div>
                    <!----- end loading -->
                    <div class="row" fareruleData="true">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------End Flight Fare Rule modal------>
<!-- flight oneway result page ends here -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
<script>
    var appFlightDetail = angular.module('flightDetailApp', []);
    appFlightDetail.controller('flightDetailCtrl', function ($scope, $http) {
        $scope.shownetfare = false;
        $scope.FareConfirmationData = '<?php echo json_encode($confirmationResponse['FareBreakUpData']); ?>';
        $scope.FareConfirmationData = angular.fromJson($scope.FareConfirmationData);
        $scope.FareConfirmationDetailErrorCode = 0;
        if ($scope.FareConfirmationDetailErrorCode == 0) {
            let faredetailview = document.querySelector('[faredetailview]');
            if (faredetailview.classList.contains('d-none')) {
                faredetailview.classList.remove('d-none');
            }
            $scope.FareInfoOB = {};
            $scope.FareInfoIB = {};
            $scope.onwardtext = "";
            $scope.onwardAgentWebPDisplayMarkup = "in_tax";
            $scope.returnAgentWebPDisplayMarkup = "in_tax";
            $scope.onwardFarelength = 0;
            $scope.returnFarelength = 0;
            $scope.onwardAgentWebPMarkUp = 0;
            $scope.returnAgentWebPMarkUp = 0;
            $scope.addonwardAgentWebPMarkUpInTax = 0;
            $scope.addreturnAgentWebPMarkUpInTax = 0;
            $scope.addonwardAgentWebPMarkUpInServiceCharge = 0;
            $scope.addreturnAgentWebPMarkUpInServiceCharge = 0;
            $scope.onwardAgentWebPDiscount = 0;
            $scope.returnAgentWebPDiscount = 0;
            if ($scope.FareConfirmationData.OB) {
                $scope.FareInfoOB = $scope.FareConfirmationData.OB;
                $scope.onwardFarelength = Object.keys($scope.FareInfoOB).length;
                $scope.onwardAgentWebPDiscount =  $scope.FareInfoOB.AgentWebPDiscount;
                $scope.onwardAgentWebPMarkUp =  $scope.FareInfoOB.AgentWebPMarkUp;
                $scope.onwardDisplayMarkup =  $scope.FareInfoOB.AgentWebPDisplayMarkup;
                if($scope.onwardDisplayMarkup=="in_service_charge"){
                    $scope.addonwardAgentWebPMarkUpInServiceCharge  = $scope.onwardAgentWebPMarkUp;
                }
                else{
                    $scope.addonwardAgentWebPMarkUpInTax  = $scope.onwardAgentWebPMarkUp;
                }

            }
            if ($scope.FareConfirmationData.IB) {
                $scope.onwardfaretext = "Onward";
                $scope.returnfaretext = "Return";
                $scope.FareInfoIB = $scope.FareConfirmationData.IB;
                $scope.returnFarelength = Object.keys($scope.FareInfoIB).length;
                $scope.returnAgentWebPMarkUp =   $scope.FareInfoIB.AgentWebPMarkUp;
                $scope.returnAgentWebPDiscount =  $scope.FareInfoIB.AgentWebPDiscount;
                $scope.returnDisplayMarkup =  $scope.FareInfoOB.AgentWebPDisplayMarkup;
                if($scope.returnDisplayMarkup=="in_service_charge"){
                    $scope.addreturnAgentWebPMarkUpInServiceCharge  = $scope.returnAgentWebPMarkUp;
                }
                else{
                    $scope.addreturnAgentWebPMarkUpInTax  = $scope.returnAgentWebPMarkUp;
                }
            }
            $scope.showMarkupOption = function (rtype) {
                let markupoptionView = document.querySelector('[markup-option-' + rtype + ']');
                if (markupoptionView.classList.contains('d-none')) {
                    markupoptionView.classList.remove('d-none');
                }
            }
            $scope.hideMarkupOption = function (rtype) {
                let markupoptionView = document.querySelector('[markup-option-' + rtype + ']');
                if (!markupoptionView.classList.contains('d-none')) {
                    markupoptionView.classList.add('d-none');
                }
            }
            $scope.getMarkupValue = function (rtype) {
                let getmarkupvalue = document.querySelector('[ get-markup-value-' + rtype + ']');
                let getdiscountvalue = document.querySelector('[ get-discount-value-' + rtype + ']');
                let getdisplaymarkupvalue = document.querySelector('[ get-display-markup-value-' + rtype + ']');
                if (rtype == "OB") {
                    $scope.onwardAgentWebPMarkUp = Number(getmarkupvalue.value);
                    $scope.onwardAgentWebPDiscount = Number(getdiscountvalue.value);
                    $scope.onwardDisplayMarkup =  getdisplaymarkupvalue.value;
                    $scope.addonwardAgentWebPMarkUpInServiceCharge =0;
                    $scope.addonwardAgentWebPMarkUpInTax =0;
                if($scope.onwardDisplayMarkup=="in_service_charge"){
                    $scope.addonwardAgentWebPMarkUpInServiceCharge  = $scope.onwardAgentWebPMarkUp;
                }
                else{
                    $scope.addonwardAgentWebPMarkUpInTax  = $scope.onwardAgentWebPMarkUp;
                }
                } else if (rtype == "IB") {
                    $scope.returnAgentWebPMarkUp = Number(getmarkupvalue.value);
                    $scope.returnAgentWebPDiscount = Number(getdiscountvalue.value);
                    $scope.returnDisplayMarkup =  getdisplaymarkupvalue.value;
                    $scope.addreturnAgentWebPMarkUpInServiceCharge =0;
                    $scope.addreturnAgentWebPMarkUpInTax =0;
                    if($scope.returnDisplayMarkup=="in_service_charge"){
                    $scope.addreturnAgentWebPMarkUpInServiceCharge  = $scope.returnAgentWebPMarkUp;
                }
                else{
                    $scope.addreturnAgentWebPMarkUpInTax  = $scope.returnAgentWebPMarkUp;
                }
                }
                $scope.MarkupDiscountMessage = "Markup Discount Apply Successfully";
                setTimeout(() => {
                    $("[markup-text-message]").html(" ");
                }, 500);
            }

        }
    });
</script>