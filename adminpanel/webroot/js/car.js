$(document).on("keydown", "[tts-car-from-city]", function (event) {
    $(this).autocomplete({
        minLength: 3,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car/car-city',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {

            $('input[name="from_city_id"]').val(ui.item.id);
            $("[tts-car-to-city]").focus();
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});


$(document).on("keydown", "[tts-car-to-city]", function (event) {
    $(this).autocomplete({
        minLength: 3,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car/car-city',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {

            $('input[name="to_city_id"]').val(ui.item.id);
            $("[tts-car-to-city]").val(ui.item.value);
            $("[car-depart-date]").focus();
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});

$(document).on("keydown", "[tts-car-airport]", function (event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car/car-airport',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {
            $('input[name="airport_id"]').val(ui.item.id);
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});



$(document).on("keydown", "[tts-car-upload-from-city]", function (event) {
    $(this).autocomplete({
        minLength: 3,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car-ticket-upload/car-city',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {

            $('input[name="from_city_id"]').val(ui.item.id);
            $("[tts-car-to-city]").focus();
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});


$(document).on("keydown", "[tts-car-upload-to-city]", function (event) {
    $(this).autocomplete({
        minLength: 3,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car-ticket-upload/car-city',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {

            $('input[name="to_city_id"]').val(ui.item.id);
            $("[tts-car-to-city]").val(ui.item.value);
            $("[car-depart-date]").focus();
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});

$(document).on("keydown", "[tts-car-upload-airport]", function (event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'car-ticket-upload/car-airport',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplete');
        },
        select: function (event, ui) {
            $('input[name="airport_id"]').val(ui.item.id);
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});


$(document).on("focus", "[car-depart-date]", function (event) {
    $("[car-depart-date]").datepicker({
        dateFormat:'d M y',
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
    });

});
$(document).on("focus", "[car-return-date]", function (event) {
    $("[car-return-date]").datepicker({
        dateFormat:'d M y',
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function () {
            var dateObject = $('[car-depart-date]').datepicker("getDate");
            dateObject.setDate(dateObject.getDate() + 1);
            var dateString = $.datepicker.formatDate("dd-mm-yy", dateObject);
            var newdate = dateString.split("-").reverse().join("-");
            var newdate = new Date(newdate);
            $(this).datepicker("option", "minDate", newdate);
        },
    });
});

$(document).on("change", 'select[name="trip_type"]', function () {
    let trip_type_val = $('select[name="trip_type"]').val();

    //$('input[name="car_trip_type"]').val(trip_type_val);

    $('[tts-car-from-city]').val('');
    $('[tts-car-to-city]').val('');

    $('input[name="airport"]').val('');
    $('input[name="city"]').val('');


    if (trip_type_val == "outstation-round-trip") {
        $("[tts-car-one-round]").removeClass('hide');
        $("[tts-car-airport-transfer]").addClass('hide');

        $("[tts-pick-time-class]").removeClass('col-md-2');
        $("[tts-pick-time-class]").addClass('col-md-1');

        $("[tts-drop-time-class]").removeClass('hide');
    } else if (trip_type_val == "outstation-one-way") {
        $("[tts-car-return]").val('');
        $("[tts-car-one-round]").removeClass('hide');
        $("[tts-car-airport-transfer]").addClass('hide');

        $("[tts-pick-time-class]").removeClass('col-md-1');
        $("[tts-pick-time-class]").addClass('col-md-2');
        $("[tts-drop-time-class]").addClass('hide');

    } else if (trip_type_val == "airport-transfers") {
        $("[tts-car-return]").val('');
        $("[tts-pick-time-class]").removeClass('col-md-1');
        $("[tts-pick-time-class]").addClass('col-md-2');
        $("[tts-drop-time-class]").addClass('hide');

        $("[tts-car-one-round]").addClass('hide');
        $("[tts-car-airport-transfer]").removeClass('hide');
    }
});

const car_from_airport = $("[car-from-airport-transfer]").html();
const car_from_city = $("[car-to-city-transfer]").html();

$(document).on("change", "[tts-car-airport-pickup-type]", function (event) {
    let airport_pickup_type = $("[tts-car-airport-pickup-type]").val();

    $('input[name="airport"]').val('');
    $('input[name="city"]').val('');

    $('[tts-car-from-city]').val('');
    $('[tts-car-to-city]').val('');


    if (airport_pickup_type == 'airport_pickup') {
        $("[car-from-airport-transfer-label]").html("AIRPORT");
        $("[car-to-city-transfer-label]").html("CITY");
        $("[car-to-city-transfer]").html(car_from_city);
        $("[car-from-airport-transfer]").html(car_from_airport);

    } else {
        $("[car-from-airport-transfer-label]").html("CITY");
        $("[car-to-city-transfer-label]").html("AIRPORT");
        $("[car-to-city-transfer]").html(car_from_airport);
        $("[car-from-airport-transfer]").html(car_from_city);

    }

});

$(document).on("keydown", "[tts-get-autocomplete]", function (event) {
    var method_name = $(this).attr('tts-method-name');
    var city_type = $(this).attr('tts-city-type');
    var url = site_url + method_name;
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        source: function (request, response) {
            $.ajax({
                url: url,
                dataType: "json",
                cache: false,
                data: {
                    term: request.term,
                    type: city_type
                },
                success: function (data) {
                    response(data)
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").css('z-index', '9999');
        },
        select: function (event, ui) {
            $(this).val(ui.item.value);
            $("[google_place_id]").val(ui.item.place_id);
            return false;
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        },
    });
});
