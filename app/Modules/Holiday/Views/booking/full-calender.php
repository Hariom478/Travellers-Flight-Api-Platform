<?php if ($holiday_list['Error']['ErrorCode'] == 0) {
    $result = $holiday_list['Result'];
    $HolidayType = $holiday_list['HolidayType'];

    $Price = $result['Price'];
    $MarkupData = $holiday_list['MarkupArray'];
    $DiscountArray = $holiday_list['DiscountArray'];
    $agent_gst_code = $holiday_list['agent_gst_code'];
    $gst_state_code = $holiday_list['gst_state_code'];
    $AgentMarkupData = $holiday_list['AgentMarkupArray']
        ?>
    <link rel="stylesheet"
        href="<?php echo site_url('webroot'); ?>/FullCalender/fullcalendar.min.css<?php echo last_modifytime(FCPATH . 'webroot/FullCalender/fullcalendar.min.css'); ?>">
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/FullCalender/moment.min.js<?php echo last_modifytime(FCPATH . 'webroot/FullCalender/moment.min.js'); ?>"></script>
    <script type="text/javascript"
        src="<?php echo site_url('webroot'); ?>/FullCalender/fullcalendar.min.js<?php echo last_modifytime(FCPATH . 'webroot/FullCalender/fullcalendar.min.js'); ?>"></script>
    <style>
        .fc-day-grid-container {
            height: auto !important;
            overflow: visible !important;
        }

        .fc button .fc-icon {
            vertical-align: text-top !important;
        }
    </style>
    <!-- 
    <style>
        .fc-view,
        .fc-view > table {
            z-index: 0;
        }

        .fc-day-grid-container {
            height: auto !important;
            overflow: visible !important;
        }

        .fc-center h2 {
            color: #777;
            font-weight: 100;
            font-size: 22px;
        }

        .fc-day-header span {
            font-weight: 400;
            font-size: 12px;
        }

        .fc-event {
            background-color: white;
            border-color: white;
            padding: 4px 6px;
        }

        th.fc-day-header {
            background: #f9f9f9;
            border: 1px solid #dbd6d6;
            padding: 10px 0px;
            text-transform: uppercase;
        }

        button.ble_close.close:focus,
        button.ble_close.close:hover {
            color: #fff;
            text-decoration: none;
            opacity: 1;
        }

        .fc-ltr .fc-basic-view .fc-day-top .fc-day-number {
            color: #443c3c;
            font-size: 13px;
            font-family: 'Rubik', sans-serif;
            font-weight: 500;
        }

        .fc-unthemed td.fc-today {
            background: #034203b0 !important;
            color: #fff;
            position: relative;
        }

        button.ble_close {
            position: absolute;
            right: -19px !important;
            background: #213f98;
            color: #fff;
            font-size: 26px;
            padding: 2px 9px 8px 10px;
            opacity: 1;
            top: -15px;
            border-radius: 20px;
        }

        button.fc-month-button {
            background: #213f98;
            border: #213f98;
            color: #fff;
        }

        .fc-unthemed td.fc-today span {
            position: absolute;
            top: 0px;
            left: 0px;
            background: #c1bfbd;
            left: 0px;
            right: 0px;
            height: 73px;
            text-align: center;
        }

        .fc .fc-row .fc-content-skeleton td {
            text-align: center;
        }





        fc-day-grid .fc-row {

            height: 157px !important;
        }

        #calendar1 .fc-event-container .fc-content {
            text-align: center;
        }

        .fc-row .fc-content-skeleton td,
        .fc-row .fc-helper-skeleton td {
            border-bottom-color: #fff;
        }

        .fc tbody .fc-row .fc-content-skeleton table {
            min-height: 120px;
        }

        .fc td {
            border-style: solid;
            border-width: 1px;
            padding: 0;
            vertical-align: top;
        }

        tr td:empty {
            visibility: unset;
        }

        .fc-day-grid-event .fc-content {
            white-space: pre-line;
            color: #000000;
            font-size: 13px;
        }

        #farebookmodal .homepopup_form .form-control {
            border-radius: 0px;
            height: 32px;
        }

        .fc-basic-view .fc-body .fc-row {
            height: 77px !important;
        }

    </style> -->

    <script>
        $(document).ready(function () {
            $('#calendar').fullCalendar({

                eventRender: function (eventObj, $el) {
                    $el.popover({
                        title: eventObj.discription,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body'
                    });
                    $el.find('span.fc-title').html($el.find('span.fc-title')
                        .text());
                },

                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                // defaultView: 'year',


                eventOrder: "eventOrder",

                editable: true,
                textEscape: false,
                eventLimit: 2, // allow "more" link when too many events

                views: {
                    basic: {
                        eventLimit: 'ture'
                    },
                    year: {
                        type: 'agenda',
                        buttonText: '12 days',
                        duration: {
                            months: 3
                        },
                        slotDuration: {
                            days: 60
                        }

                    }
                },

                eventClick: function (event) {
                    if (event.url) {
                        window.open(event.url);
                        return false;
                    }
                },
                eventLimitClick: function (cellInfo, jsEvent) {

                    $('#calendar').fullCalendar('gotoDate', cellInfo.date);
                    $('#calendar').fullCalendar('changeView', 'basic');

                    console.log(
                        "cellInfo.dayEl: jQuery element for the day cel",
                        cellInfo.date);
                },
                events: [
                    <?php foreach ($Price as $key => $value) {

                        $StartDate = new DateTime(timestamp_to_date_holiday($value['StartDate']));
                        $EndDate = new DateTime(timestamp_to_date_holiday_enddate($value['EndDate']));
                        $holiday_price_key = "{$HolidayType}AdultPrice";
                        $holiday_price = $value[$holiday_price_key];
                        $priceBreakUp = get_fare_breakup($holiday_price);

                        $holiday_price = get_fare_price([], $MarkupData, $DiscountArray, $AgentMarkupData, $agent_gst_code, $gst_state_code, $priceBreakUp)['Fare']['PublishedPrice'];

                        $interval = DateInterval::createFromDateString('1 day');
                        $period = new DatePeriod($StartDate, $interval, $EndDate);

                        foreach ($period as $dt) {
                            $date = $dt->format("Y-m-d");
                            $date_show = $dt->format("d-M-Y");

                            $today = date("Y-m-d");

                            if ($date >= $today) {
                                ?>

                                                                                                                    {
                                    id: '<?php echo $value['id']; ?>',
                                    title: "<button type  =  'button' class  =  'btn btn-success btn-lg w-100' date-price-pick='true' selected-date='<?php echo $date_show; ?>' price-id='<?php echo $value['id'] ?>' >Book</button><br/><b><i class  = 'fa fa-inr'></i><?php echo $holiday_price; ?></b>",


                                    start: '<?php echo $date; ?>',
                                    discription: ''
                                },


                            <?php }
                        }
                    } ?>
                ]
            });

        });


    </script>
    <div id='calendar'></div>
<?php } ?>