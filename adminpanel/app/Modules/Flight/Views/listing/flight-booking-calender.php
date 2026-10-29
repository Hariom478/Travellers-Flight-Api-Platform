<style>
    a.flightdisablelinks {
        pointer-events: none;
    }


    .booking__calendar-main-container {
        padding: 15px;
        background: #fff;
        margin-top: 15px;
        border-radius: 6px;
        box-shadow: 1px 1px 15px rgb(0 0 0 / 10%);
    }

    .booking__calendar-main-list {
        padding: 0;
        display: flex;
        align-items: center;
    }

    .booking__calendar-date-time {
        width: 10%;
        text-align: center;
        display: flex;
        text-align: center;
        justify-content: space-around;
        align-items: center;
    }

    .booking__calendar-date {
        font-weight: bold;
        margin: 0;
        color: #014681;
        font-size: 30px;
    }

    .booking__calendar-day {
        display: grid;
        font-weight: normal;
        font-size: 14px;
        color: #999;
    }

    .booking__calendar-details-containerWrapper {
        width: 100%;
    }

    .booking__calendar-details-container:last-child {
        margin-bottom: 0;
    }

    .booking__calendar-details-container {
        width: 100%;
        display: inline-flex;
        margin-bottom: 8px;
        margin-top: 5px;
        position: relative;
        min-height: 40px;
    }

    .booking__calendar-date-logo {
        width: 10%;
        float: left;
    }

    .booking-generation-time {
        width: 40px;
        display: inline-block;
    }

    .booking__calendar-before-icon {
        position: relative;
        width: 25px;
        height: 25px;
        border: 1px solid #333;
        border-radius: 50%;
        text-align: center;
        line-height: 25px !important;
        color: #034581;
    }

    .booking__calendar-bookingid-name {
        width: 20%;
        float: left;
    }

    .booking__calendar-city-name {
        width: 30%;
        float: left;
        font-size: 12px;
    }

    .booking__calendar-roundtrip-button {
        width: 50px;
        float: left;
    }

    .hidden {
        display: none !important;
    }

    .button-multicity {
        background: #fff3f5;
        color: #9f3148;
    }

    .booking__calendar-button-bg {
        background: #f0fff0;
        color: #007214;
        padding: 2px 4px;
        border-radius: 5px;
        font-size: 12px;
        height: 25px;
        width: 25px;
        border-radius: 50%;
        display: inline-block;
        line-height: 22px;
        text-align: center;
    }

    .booking__calendar-biiking-idcolor {
        color: #0079b1;
        word-break: break-all;
    }

    .booking__calendar-bookingid-name p {
        margin-bottom: 0 !important;
    }
</style>
<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m0"> Flight Booking Calender</h5>
                    </div>
                    <div class="col-md-8 text-md-right">
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">

                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('flight/booking-calender'); ?>" method="GET"
                          class="row tts-dis-content" name="web-partner-search"
                          onsubmit="return searchvalidateForm()">
                        <?
                        $to_date = new DateTime('now');
                        $to_date->modify('last day of this month');
                        $to_date = $to_date->format('d M Y');
                        ?>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label class="form-label">From Date</label> 
                                <input type="text" class="form-control" id="from-date"
                                                                data-searchbar-calendar-from="true" name="from_date"
                                                                tts-validatation="Required"
                                                                placeholder="Select From Date"
                                                                value="<?php if (isset($search_bar_data['from_date'])) {
                                                                    echo $search_bar_data['from_date'];
                                                                } else {
                                                                    echo date('d M Y');
                                                                } ?>" tts-error-msg="Please select from date"
                                                                readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-mb-20">
                                <label class="form-label">To Date</label> 
                                <input type="text" data-searchbar-calendar-to="true"
                                                              name="to_date" id="to-date"
                                                              tts-validatation="Required"
                                                              value="<?php if (isset($search_bar_data['to_date'])) {
                                                                  echo $search_bar_data['to_date'];
                                                              } else {
                                                                  echo $to_date;
                                                              } ?>" placeholder="Select From Date"
                                                              class="form-control"
                                                              tts-error-msg="Please select from date" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <div class="form-group ">
                                
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                                            class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="search-reset-btn">
                                <? if (isset($search_bar_data['from_date'])) : ?>
                                    <a href="<?php echo site_url('flight/booking-calender'); ?>">Reset Search</a>
                                <? endif ?>
                            </div>
                        </div>
                    </form>

                    <!----------End Search Bar ----------------->

                </div>
            </div>


            <div class="table-responsive">
                <?php
                if (!empty($list) && is_array($list)) {
                    foreach ($list as $month => $data) {
                        $dateValue = strtotime($month);
                        $month = date('M', $dateValue);
                        $day = date('d', $dateValue);
                        ?>
                        <div class="booking__calendar-main-container">
                            <div class="booking__calendar-main-list">
                                <div class="booking__calendar-date-time">
                                    <h2 class="booking__calendar-date"><?php echo $day; ?> <span
                                                class="booking__calendar-day"><?php echo $month; ?> </span></h2>
                                </div>
                                <div class="booking__calendar-details-containerWrapper">
                                    <?php foreach ($data as $calender) { ?>
                                        <div class="booking__calendar-details-container">
                                            <div class="booking__calendar-date-logo"><span
                                                        class="booking-generation-time"><?php echo $calender['generationTime']; ?></span><i
                                                        class="fa fa-plane booking__calendar-before-icon"></i></div>
                                            <div class="booking__calendar-bookingid-name">
                                                <p><?php echo $calender['leadPassengerName']; ?></p>
                                            </div>
                                            <div class="booking__calendar-bookingid-name">
                                                <p>Ref. No. - <a
                                                            href="<?php echo site_url('/flight/details/') . $calender['bookingRefNo']; ?>"><span
                                                                class="booking__calendar-biiking-idcolor"><?php echo $calender['bookingRefNo']; ?></span></a>
                                                </p>
                                            </div>
                                            <div class="booking__calendar-bookingid-name">
                                                <p>Summary -<a
                                                            href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($calender['bookingId']))); ?>"><span
                                                                class="booking__calendar-biiking-idcolor">View</span></a>
                                                </p>
                                            </div>
                                            <div class="booking__calendar-city-name"><?php echo $calender['summary']; ?>
                                            </div>
                                            <div class="booking__calendar-roundtrip-button hidden"><span
                                                        class="booking__calendar-button-bg button-multicity"><?php echo $calender['Triptype']; ?></span>
                                            </div>
                                            <div class="booking__calendar-action-name"><span
                                                        class="booking__calendar-Success text-success"><?php echo $calender['bookingStatus']; ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>

            </div>
        </div>


















 
