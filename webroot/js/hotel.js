
var child_age_html = '<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>';
$(document).on("keydown", "[tts-hotel-location]", function (event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'hotel/city-list',
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

            $("[cityDom]").val(ui.item.city_dom);
            $("[hotel-check-in-date]").focus();
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    }
    );
});
$(document).on("focus", "[hotel-check-in-date]", function (event) {
    $("[hotel-check-in-date]").datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        onClose: function (selectedDate) {
            $("[hotel-check-out-date]").datepicker("option", "minDate", selectedDate).focus().select();
            $("[hotel-check-out-date]").focus();
        }
    });

});
$(document).on("focus", "[hotel-check-out-date]", function (event) {
    $("[hotel-check-out-date]").datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function () {
            var dateObject = $('[hotel-check-in-date]').datepicker("getDate");
            dateObject.setDate(dateObject.getDate() + 1);
             var dateString = $.datepicker.formatDate("dd-mm-yy", dateObject);
            var newdate = dateString.split("-").reverse().join("-");
            var newdate = new Date(newdate);
            $(this).datepicker("option", "minDate", newdate);

        },
        onClose: function (selectedDate) {
            $("[hotel-check-in-date]").datepicker("option", selectedDate);
        }
    });

});
function hoteltravellercount() {
    var adtval = 0;
    var chdval = 0;
    var totalselectedrooms = $("[hotel-total-selected-rooms]").val();
    for (i = 1; i <= totalselectedrooms; i++) {
        adtval += parseInt(document.forms["hotelform"]["adult_" + i].value);
        chdval += parseInt(document.forms["hotelform"]["child_" + i].value);
    }
    var totalguest = adtval + chdval;
    var checkroomtext = totalselectedrooms == 1 ? " Room" : " Rooms";
    var roomguesttext = totalguest + " Guest , " + totalselectedrooms + checkroomtext;
    $("[tts-hotel-guest-info]").html(totalguest);
    $("[tts-hotel-rooms-info]").html(totalselectedrooms);
}
function get_hotel_adt(thisval) {
    hoteltravellercount();
}
function add_child_age(getroom, getselect) {
    hoteltravellercount();
    if (!getselect == 0) {
        var child_age_element = '';
        var j = 1;
        for (j = 1; j <= getselect; j++) {
            child_age_element += '<div class="col-3"><span>Age</span><select class="form-control tts__input__select__room"  name="age_' + getroom + '_' + j + '">' + child_age_html + '</select></div>';
        }
        $("[add-room-child-age-element-" + getroom + "]").html(child_age_element);
    }
}
function add_room() {
    var totalselectedrooms = parseInt($("[hotel-total-selected-rooms]").val());
    var roomNumber = totalselectedrooms + 1;
    if (roomNumber <= 5) {
        var addRoom = '<div class="tts__traveller__select__room my-2" remove-extra-hotel-room-' + roomNumber + ' = "true" ><h6>Room ' + roomNumber + '</h6><div class="row px-2"><div class="col-6 px-2"><span>Adults(12y +)</span>  <select class="form-control tts__input__select__room"  name="adult_' + roomNumber + '" onchange="get_hotel_adt(this)">'
            + '<option value  =  "1">1</option><option value  =  "2" selected>2</option><option value  =  "3">3</option><option value  =  "4">4</option> <option value  =  "5">5</option></select></div> <div class="col-6 px-2">'
            + '<span>Children <i class  =  "fa fa-info-circle" data-bs-toggle="tooltip" data-bs-html="true" title="(Age 12y and below)"></i></span><select class="form-control tts__input__select__room" name="child_' + roomNumber + '" onchange="add_child_age(' + roomNumber + ',this.value)">'
            + '<option value  =  "0" selected>0</option><option value  =  "1">1</option><option value  =  "2">2</option><option value  =  "3">3</option><option value  =  "4">4</option></select></div><div class="col-12 px-2">'
            + '<div class  =  "row"  add-room-child-age-element-' + roomNumber + ' =  "true"></div></div></div></div>';
        $("[append-extra-hotel-room]").append(addRoom);
        $("[hotel-total-selected-rooms]").val(roomNumber);
        $("[remove-extra-hotel-room-event]").removeClass('hide');
        hoteltravellercount();
    }
    if (roomNumber >= 5) {
        $("[add-extra-hotel-room-event]").hide();
    }
}
function remove_room() {
    var totalselectedrooms = parseInt($("[hotel-total-selected-rooms]").val());
    var roomNumber = totalselectedrooms - 1;
    if (roomNumber < 2) {
        $("[remove-extra-hotel-room-event]").addClass('hide');
    }
    if (totalselectedrooms > 1) {
        $("[add-extra-hotel-room-event]").show();
        $("[remove-extra-hotel-room-" + totalselectedrooms + "]").remove();
        $("[hotel-total-selected-rooms]").val(roomNumber);
        hoteltravellercount();
    }
}
$(document).on("click", "[hotel-room-dropdown-event]", function (event) {
    $('[hotel-room-dropdown]').removeClass('show');
});

function checkHotelSearchValidation()
{

    setTimeout(function(){
    var form  = $("[name  =  'hotelform']");
    if ($("[name  =  'hotelform']").find('.error').length == 0) {
    $("[data-message]").removeClass().html("");
        var buttontxt;
            buttontxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).attr('disabled', true).html('Wait...');
        $("span.error-message", form).replaceWith("");

    $.ajax({
        url: site_url + 'hotel/hotel-check-search-validtion',
        dataType: "json",
        type: "POST",
        cache: false,
        data: form.serialize(),
        success: function (resp) {
            $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
            if (resp.StatusCode == 1) {
                var count = Object.keys(resp.ErrorMessage).length;
                if(count>0){
                $.each(resp.ErrorMessage, function (key, val) {
                        $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                    
                });
            }
            else{
                alert("Unexpected error! Try again."); 
            }
            } 
            else if (resp.StatusCode == 0) {
                 form.submit(); 
            }
            else{
                alert("Unexpected error! Try again."); 
            }
        },
        error:function(resp){
            $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
            alert("Unexpected error! Try again.");
        }
    });
}
    }
 ,100);
 return false;
}
$(document).on("focus", "[hotel_pass_issue]", function (event) {
    $("[hotel_pass_issue]").datepicker({
        defaultDate: "",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        maxDate: "-1D",
        onClose: function (selectedDate) {
                    $("[hotel_pass_expiry]").foucus();
                }
    });
    });
    $(document).on("focus", "[hotel_pass_expiry]", function (event) {
    $("[hotel_pass_expiry]").datepicker({
        defaultDate: "",
        minDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        beforeShow: function () {
            var CheckInDate  =  "<?php echo  $searchRequest['CheckInDate'];  ?>";
             var CheckInDate = new Date(CheckInDate);
                    $(this).datepicker("option", "minDate", CheckInDate);
        }
    });
    });
    function showHideroomcancellationPolicy(key, buttonkey = "") {
        let showHidefareRule = document.querySelector('[' + key + ']');
        if (showHidefareRule.classList.contains('d-none')) {
            showHidefareRule.classList.remove('d-none');
            if (buttonkey != "") {
                let viewFareRuleButton = document.querySelector('[' + buttonkey + ']' + ' i');
                if (viewFareRuleButton.classList.contains('fa-plus')) {
                    viewFareRuleButton.classList.remove('fa-plus');
                    viewFareRuleButton.classList.add('fa-minus');
                }
            }
        } else {
            showHidefareRule.classList.add('d-none');
            if (buttonkey != "") {
                let viewFareRuleButton = document.querySelector('[' + buttonkey + ']' + ' i');
                if (viewFareRuleButton.classList.contains('fa-minus')) {
                    viewFareRuleButton.classList.remove('fa-minus');
                    viewFareRuleButton.classList.add('fa-plus');
                }
            }
        }
    }
