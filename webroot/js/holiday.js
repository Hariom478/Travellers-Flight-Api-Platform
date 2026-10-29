$(document).on("keydown", "[holiday-destination]", function (event) {
    $(this).autocomplete({
            minLength: 0,
            maxResults: 15,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'holiday/auto-suggest',
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
                $("[tts-holiday-id]").val(ui.item.id);
                $("[tts-holiday-tag]").val(ui.item.tag);
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.value : ""));
            },
            create: function () {
                $(this).data('ui-autocomplete')._renderMenu = function (ul, items) {

                    this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
                    var that = this, currentCategory = "";
                    $.each(items, function (index, item) {
                        var li;
                        if (item.tag != currentCategory) {
                            if (item.tag == 'Themes') {
                                ul.append("<li class='ui-autocomplete-category'> <i class='fa fa-map-marker px-1' aria-hidden='true'></i>" + item.tag + "</li>");
                                currentCategory = item.tag;
                            } else if (item.tag == 'Destinations') {
                                ul.append("<li class='ui-autocomplete-category'> <i class='fa fa-map-marker px-1' aria-hidden='true'></i>" + item.tag + "</li>");
                                currentCategory = item.tag;
                            } else {
                                ul.append("<li class='ui-autocomplete-category'> <i class='fa fa-umbrella px-1' aria-hidden='true'></i>" + item.tag + "</li>");
                                currentCategory = item.tag;
                            }
                        }

                        li = that._renderItemData(ul, item);
                        if (item.tag) {
                            li.attr("aria-label", item.tag + " : " + item.Name);
                        }
                    });
                };
            }
        }
    ).focus(function () {
        $(this).autocomplete('search', $(this).val())
    });

});


function checkHolidaySearchValidation() {

    setTimeout(function () {

            $(".error-message").remove();
            var form = $("[name  =  'holiday-form']");
            if ($("[name  =  'holiday-form']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");

                $.ajax({
                    url: site_url + 'holiday/holiday-check-search-validation',
                    dataType: "json",
                    type: "POST",
                    cache: false,
                    data: form.serialize(),
                    success: function (resp) {
                        $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                        if (resp.StatusCode == 1) {
                            var count = Object.keys(resp.ErrorMessage).length;
                            if (count > 0) {
                                $.each(resp.ErrorMessage, function (key, val) {
                                    $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');

                                });
                            } else {
                                alert("Unexpected error! Try again.");
                            }
                        } else if (resp.StatusCode == 0) {
                            form.submit();
                        } else {
                            alert("Unexpected error! Try again.");
                        }
                    },
                    error: function (resp) {
                        $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                        alert("Unexpected error! Try again.");
                    }
                });
            }
        }
        , 100);
    return false;
}

$(document).on("click", "[date-price-pick]", function (event) {
    /*  $("#Price-Calender-tab").removeClass('active');
    $("#booking-online-tab").addClass('active');
    $("#Price-Calender-tab-pane").removeClass('active show');
    $("#booking-online-tab-pane").addClass('active show');*/
    let selected_date =  $(this).attr('selected-date'); 
    let price_id =  $(this).attr('price-id');
    $("[tts-price-key]").val(price_id);
    $("[tts-travel-date]").val(selected_date);
    var myModal = new bootstrap.Modal(document.getElementById('tts-online-holiday-query'));
    myModal.show();
});

$(document).on("click", "[date-book-pick]", function (event) {
    $("#Price-Calender-tab").addClass('active');
    $("#booking-online-tab").removeClass('active');
    $("#Price-Calender-tab-pane").addClass('active show');
    $("#booking-online-tab-pane").removeClass('active show');
});


$(document).on("click", "[holiday-price-category]", function (event) {
    let price_categeory =  $(this).attr('holiday-price-category');
    let url =  $(this).attr('tts-url-calendar');
    let slug = $(this).attr('tts-holiday-slug')
    $("[tts-holiday-category]").val(price_categeory);
    $("[holiday-price-category]").removeClass('btn-success');
    $(this).addClass('btn-success');
    $("[holiday-price-category]").addClass('btn-primary');
    $(this).removeClass('btn-primary');


    if (price_categeory =="Standard"){
        $(this).addClass('btn-success');
        $(this).removeClass('btn-primary');
    }else if (price_categeory =="Deluxe"){

    }else if (price_categeory =="Luxury"){

    }
    $.ajax({
        url: url,
        type: "post",
        data: {price_category: price_categeory,slug:slug},
        success: function (resp) {
            if (resp) {
                if (resp.StatusCode == 0) {
                    $("[tts-calender-holiday]").html(resp.Html_data);
                } else {
                    $("[data-message]").addClass(resp.class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                }
            }
        },
        error: function (res) {
            alert("Unexpected error! Try again.");
            // location.reload();
        }
    });
});

function showHideRooms(roomReq) {
    var x;
    var maxRooms = parseInt(document.getElementById('total_rooms').value, 10);
    // hid_maxRooms hid_maxChildren
    for (x = 1; x <= maxRooms; x++) {
        if (x <= roomReq) {
            document.getElementById('div_RoomOcc_' + x).style.display = '';
        } else {
            document.getElementById('div_RoomOcc_' + x).style.display = 'none';
        }
    }
}


function plusOrMinus(filedVar, actType, minValue, Maxvalue) {

    dxArr = filedVar.split('_');
    var fieldValue = parseInt(document.getElementById(filedVar).value, 10);

    if (actType == 'minus' && fieldValue > minValue) {
        if (dxArr[1] == 'adults' && fieldValue == 1) {

        } else {
            document.getElementById(filedVar).value = (fieldValue - 1);
        }

        if (dxArr[0] == 'txt' && dxArr[1] == 'childAge') {
            if (parseInt(document.getElementById(filedVar).value) <= parseInt(document.getElementById('infantAgeTo').value)) {
                document.getElementById('neebed_' + dxArr[2] + '_' + dxArr[3]).style.display = 'none';
            }
        }
    } else if (actType == 'plus' && fieldValue < Maxvalue) {
        document.getElementById(filedVar).value = (fieldValue + 1);
        if (dxArr[0] == 'txt' && dxArr[1] == 'childAge') {
            if (parseInt(document.getElementById(filedVar).value) > parseInt(document.getElementById('infantAgeTo').value)) {
                document.getElementById('neebed_' + dxArr[2] + '_' + dxArr[3]).style.display = '';
            }
        }
    }
    showHideChildAge();

}

function showHideChildAge() {
    var maxRooms = parseInt(document.getElementById('total_rooms').value, 10);
    var maxChildren = parseInt(document.getElementById('maxChildren').value, 10);
    var numChildren = 0;
    for (x = 1; x <= maxRooms; x++) {
        numChildren = parseInt(document.getElementById('txt_children_' + x).value, 10);
        for (y = 1; y <= maxChildren; y++) {
            if (y <= numChildren) {
                document.getElementById('div_ChildAge_' + x + '_' + y).style.display = '';
            } else {
                document.getElementById('div_ChildAge_' + x + '_' + y).style.display = 'none';
            }
        }
    }

}

$(document).on("focus", "[hotel_pass_issue]", function (event) {
    $("[hotel_pass_issue]").datepicker({
        defaultDate: "",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        maxDate: "-1D",
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
$(document).on("focus", "[holiday_pass_expiry]", function (event) {
    $("[holiday_pass_expiry]").datepicker({
        defaultDate: "",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        minDate: 0, 
    });
});
