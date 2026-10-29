

$(document).on("change", "[tts-cruise-line]", function (event) {
    var cruise_line_id = $(this).find(":selected").val();
    var cruise_line_name = $(this).find(":selected").text().trim();
    $("[tts-cruise-line-name]").val(cruise_line_name);

    $.ajax({
        url: site_url+'cruise/cruise-ship',
        method: 'POST',
        data: {
            'cruise_line_id':cruise_line_id
        },
        cache: false,
        success: function (resp) {
            $("[tts-cruise-ship-html]").html(resp);
        },
        error: function (res) {
            alert("Unexpected error! Try again.");
        }
    });
});

$(document).on("change","[tts-cruise-ship]", function (event){
    var ship_name = $(this).find(":selected").text().trim();
    $("[tts-ship-name]").val(ship_name);

});


$(document).on("change", "[tts-cruise-ocean]", function (event) {
    var cruise_ocean_id = $(this).find(":selected").val();
    var cruise_ocena_name = $(this).find(":selected").text().trim();
    $("[tts-cruise-ocean-name]").val(cruise_ocena_name);

    $.ajax({
        url: site_url+'cruise/cruise-port',
        method: 'POST',
        data: {
            'cruise_ocean_id':cruise_ocean_id
        },
        cache: false,
        success: function (resp) {
            $("[tts-cruise-port-html]").html(resp);
        },
        error: function (res) {
            alert("Unexpected error! Try again.");
        }
    });
});
$(document).on("click", "[data-cruise-next]", function (event) {
    var adt_val = $("[cruise-input]").val();
    var maxpax = $("#maxpaxstay").val();
    var cruise_to = Number(adt_val);
    if (cruise_to < maxpax) {
        adt_val++;
        $("[data-cruise-count]").text(adt_val);
        $("[cruise-input]").val(adt_val);
    }
    travelpaxcount();
});

$(document).on("click", "[data-cruise-pre]", function (event) {
    var adt_val = $("[cruise-input]").val();
    if (adt_val > 1) {
        adt_val--;
        $("[data-cruise-count]").text(adt_val);
        $("[cruise-input]").val(adt_val);
    }
    travelpaxcount();
});

function travelpaxcount(){
    var adt_val = $("[cruise-input]").val();
    var agehtml = "";
    for(var i=0;i<adt_val;i++){
        agehtml += '<div class="col-4 col-md-2"> <h6>Guest '+(i+1)+' <span class="text-red">*</span></h6><input type="number" min="1" max="99" required="" name="age['+i+']" class="form-control AgeChange" id="Guest1AgeTextfield_'+i+'"> <div class="text-danger"> required </div> </div>';
    }
    $(".age-star-inserted").html(agehtml)
}

$(document).on("click","[cruise-pax-btn]",function(){
    var adt_val = $("[cruise-input]").val();
    let cruiseinput = 0;
    for(var i=0;i<adt_val;i++){
      var ageInputId =''; var ageInput='';
        ageInputId = 'Guest1AgeTextfield_'+i;
        ageInput = $('#'+ageInputId).val();
        if(parseInt(ageInput) > 0){
            cruiseinput++;
            $("#"+ageInputId).next("div").remove();
    
        }else{
           $("#"+ageInputId).after().html('<div class="text-danger"> required </div>') 
        }
    }
    if(cruiseinput == adt_val){
        var form = $('#cruisepaxinfo');
        $(this).text("Checking....").attr("disabled",true)
        $.ajax({  
            type:"POST",  
            url:site_url+ "cruise/check-cruise-availability",
            data:form.serialize(),  
            success:function(response){ 
                $("[cruise-pax-btn]").text("Continue").attr("disabled",false) 
                var fdata = JSON.parse(response)
                if(fdata.status_code == 0){
                    window.location.href = fdata.RedirectUrl;
                }else{
                    $('.errorMsg').text(fdata.message)
                } 
            },
            error:function(err){
                alert(err);
                $("[cruise-pax-btn]").text("Continue").attr("disabled",false)
            }
         }) 
    }
})


function checkCruiseSearchValidation(formName) {
    $("#PreferredCarriers").val($(".PreferredFlights").val());


    setTimeout(function () {
            var form = $("[name  =  '" + formName + "']");
            if ($("[name  =    '" + formName + "']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");

                $.ajax({
                    url: site_url + 'cruise/cruise-check-search-validtion',
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
                                    var finalkey = key.split(".");
                                    if (finalkey[2]) {
                                        key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]";
                                        $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                                    } else {
                                        $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                                    }

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

$(document).on("focus", "[flight_pass_issue]", function (event) {
    $("[flight_pass_issue]").datepicker({
        defaultDate: "",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        maxDate: "-1D",
        onClose: function (selectedDate) {
            $("[flight_pass_expiry]").focus();
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
            var flightpassportMinDate = $("[flightpassportMinDate]").val();
            var flightpassportMinDate = new Date(flightpassportMinDate);
            $(this).datepicker("option", "minDate", flightpassportMinDate);
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