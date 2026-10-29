$(document).on("focus", "[tts-flight-origin]", function (event) {

    $(event.target).autocomplete({

        minLength: 0,

        maxResults: 15,

        source: function (request, response) {

            $.ajax({

                url: site_url + 'flight/get-airports',

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

            var top =$(event.target).offset().top + $(event.target).outerHeight()-20;

            $(".ui-autocomplete").addClass('tts-autocomplete').css("top", top);

        },

        select: function (event, ui) {    

        

            var city=ui.item.city;

            var airportcode=ui.item.airport_code;

            var airportname=ui.item.airport_name;

            ui.item.value=city;



            $(event.target).val(city);

            $(event.target).next().val(ui.item.label);

           // $(event.target).next().next().text('['+airportcode+'] '+airportname);

            $(event.target).siblings(".flight_text_p").text('['+airportcode+'] '+airportname);



            

            if ($(event.target).attr('data-key') === undefined) {

                $("[tts-flight-destination]").trigger('focus');

            } else {

                setTimeout(() => {

                    var datakey = $(event.target).attr('data-key');

                    var placeholder = $(event.target).attr('placeholder');

                    if (placeholder == 'Origin') {

                        $("input[name='search_data[" + datakey + "][destination]']").trigger('focus');

                    }

                }, 10);



            }

        },

        change: function (event, ui) {

            $(this).val((ui.item ? ui.item.value : ""));

        },

        close: function( event, ui ) {

          if($(event.target).val()=='')

          {

            $(event.target).next().val('');

            $(event.target).next().next().text('');

          }

        }

    });

});



$(document).on("focus", "[tts-flight-destination]", function (event) {

    $(event.target).autocomplete({

        minLength: 0,

        maxResults: 15,

        source: function (request, response) {

            $.ajax({

                url: site_url + 'flight/get-airports',

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

            var top =$(event.target).offset().top + $(event.target).outerHeight()-20;

            $(".ui-autocomplete").addClass('tts-autocomplete').css("top", top);

        },

        select: function (event, ui) {



            var city=ui.item.city;

            var airportcode=ui.item.airport_code;

            var airportname=ui.item.airport_name;

            ui.item.value=city;



            $(event.target).val(city);

            $(event.target).next().val(ui.item.label);

           // $(event.target).next().next().text('['+airportcode+'] '+airportname);

           $(event.target).siblings(".flight_text_p").text('['+airportcode+'] '+airportname);



            if ($(event.target).attr('data-key') === undefined) {

                $("[flight-departure-date]").trigger('focus');



            } else {

                setTimeout(() => {

                    var datakey = $(event.target).attr('data-key');

                    var placeholder = $(event.target).attr('placeholder');

                    if (placeholder == 'Destination') {

   

                        var newdatakey = parseInt(datakey) + parseInt(1);

                        $("input[name='search_data[" + newdatakey + "][origin]']").prev().val(city);

                        $("input[name='search_data[" + newdatakey + "][origin]']").val(ui.item.label);

                        $("input[name='search_data[" + newdatakey + "][origin]']").next().text('['+airportcode+'] '+airportname);





                        $("input[name='search_data[" + datakey + "][departdate]']").trigger('focus');

                    }

                }, 20);

            }

        },

        change: function (event, ui) {

            $(this).val((ui.item ? ui.item.value : ""));

        },

        close: function( event, ui ) {

            if($(event.target).val()=='')

            {

              $(event.target).next().val('');

              $(event.target).next().next().text('');

            }

          }

    });

});

$(document).on("focus", "[flight-departure-date]", function (event) {

    $(event.target).datepicker({

        dateFormat: DateFormat,

        changeMonth: false,

        numberOfMonths: 2,

        minDate: "0",

        maxDate: "+12m",

    });

});

$(document).on("focus", "[flight-return-date]", function (event) {

    $(event.target).datepicker({

        dateFormat: DateFormat,

        changeMonth: false,

        numberOfMonths: 2,

        minDate: "0",

        maxDate: "+12m",

    });

});



$(document).on("focus", "[flight_pass_issue]", function (event) {

    $("[flight_pass_issue]").datepicker({

        defaultDate: "",

        dateFormat: "dd-mm-yy",

        changeMonth: true,

        changeYear: true,

        numberOfMonths: 1,

        maxDate: "-1D",

        onClose: function (selectedDate) {

          /*   $("[flight_pass_expiry]").focus(); */

        }

    });

});

$(document).on("focus", "[flight_pass_expiry]", function (event) {

    $("[flight_pass_expiry]").datepicker({

        defaultDate: "",

        minDate: 0,

        dateFormat: "dd-mm-yy",

        changeMonth: true,

        changeYear: true,

        numberOfMonths: 1,

        beforeShow: function () {

            // var flightpassportMinDate = $("[flightpassportMinDate]").val();

            // var flightpassportMinDate = new Date(flightpassportMinDate);

            // $(this).datepicker("option", "minDate", flightpassportMinDate);

        }

    });

});



$(document).on("focus", "[adult_dob_date]", function (event) {

    $("[adult_dob_date]").datepicker({

        dateFormat: "dd-mm-yy",

        changeMonth: true,

        changeYear: true,

        yearRange: '-100y:c+nn',

        maxDate: "-12Y",

        numberOfMonths: 1,

    });

});

$(document).on("focus", "[child_dob_date]", function (event) {

    $("[child_dob_date]").datepicker({

        dateFormat: "dd-mm-yy",

        minDate: "-12Y",

        changeMonth: true,

        changeYear: true,

        maxDate: "-2Y",

        numberOfMonths: 1,

    });

});

$(document).on("focus", "[infant_dob_date]", function (event) {

    $("[infant_dob_date]").datepicker({

        dateFormat: "dd-mm-yy",

        minDate: "-2Y",

        changeMonth: true,

        changeYear: true,

        maxDate: "+0D",

        numberOfMonths: 1,

    });

});



$(document).on("click", "[tts-flight-origin],[tts-flight-destination]", function (event) {



    setTimeout(() => {

        event.target.select();

        $(event.target).autocomplete("search", " ");

    }, 50);

});



// airline autocomplete

$(document).on("keydown", "[tts-get-airline]", function (event) {



    $(this).autocomplete({

        minLength: 2,

        maxResults: 10,

        source: function (request, response) {

            $.ajax({

                url: site_url + 'flight/get-airline',

                dataType: "json",

                cache: false,

                data: {

                    term: request.term

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

            return false;

        },

        change: function (event, ui) {

            $(this).val((ui.item ? ui.item.value : ""));

        }

    });

});



$(document).on("click",".pax-add",function(event){
    var type = $(this).attr('data-attr');
    var count = parseInt($(this).attr('data-count'));
    var flightType = $('#flight-type').val();
    let pthis = $(this);
    pthis.prop('disabled', true);

    let adult_count = parseInt($('.pax-add[data-attr="adult"]').attr('data-count'));
    let child_count = parseInt($('.pax-add[data-attr="child"]').attr('data-count'));
    let infant_count = parseInt($('.pax-add[data-attr="infant"]').attr('data-count'));

    let total_pax = adult_count+child_count+infant_count;
    console.log(total_pax);
    
    if(total_pax > 8) {
        alert('Maximum passenger added.');
        pthis.prop('disabled', false);
        return;
    }

    $.ajax({
        url: site_url + 'groupticket/pax-info',
        type: "POST",
        cache: false,
        data: {
            type:type,
            flightType:flightType,
            count:count
        },
        success: function (resp) {
            $('.tts-pax-info').append(resp)
            $(pthis).attr('data-count',(count+1))
            pthis.prop('disabled', false);
        }
    })
})



$(document).on("change","#flight-type",function(e){
    var type = 'adult';
    var count = 0;
    var flightType = $(this).val();
    let pthis = $('.adult-pax');
    $.ajax({
        url: site_url + 'groupticket/pax-info',
        type: "POST",
        cache: false,
        data: {
            type:type,
            flightType:flightType,
            count:count
        },
        success: function (resp) {
            console.log(resp);
            $('.tts-pax-info').html(resp)
            $(pthis).attr('data-count',(count+1))
        }
    })
})

$(document).on("click", ".tts_remove_Passenger", function () {
    $(this).closest('#tts-passenger-html').remove();
    let pax_type = $(this).attr('data-remove-attribute');
    let add_pax_button = $('.pax-add[data-attr="' + pax_type + '"]');
    let dataCount = add_pax_button.attr('data-count');
    add_pax_button.attr('data-count', (dataCount-1));
    
});


$(document).on("change",'#journey-type',function(e){

        var jtype = $(this).val()

        if(jtype == "roundtrip"){

            $('[tts-return-html]').html('<div class="row"><div class="col-md-3 mb-3"> <div class="form-group form-mb-20 "> <label class="form-label">Return Date *</label> <input class="form-control" type="text" name="return_date" placeholder="Return Date" readonly flight-return-date="true"> </div> </div><div class="col-md-3 mb-3"> <div class="form-group form-mb-20 "> <label class="form-label">Return Airline Preference *</label> <input type="text" class="form-control inputtext airlinelist" tts-get-airline="true" placeholder="Airline Preference" name="destination_airline_prefrence"> </div> </div> <div class="col-md-3 mb-3"> <div class="form-group form-mb-20 "> <label class="form-label">Return Flight Number *</label> <input type="text" class="form-control inputtext airlinelist" placeholder="Flight Number" name="destination_preference_flight_number"> </div> </div></div>')

        }

        if(jtype == "oneway"){

            $('[tts-return-html]').html('') 

        }

})