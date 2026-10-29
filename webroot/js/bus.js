


function checkBusSearchValidation() {
    $(".form-error").replaceWith("");
    setTimeout(function () {
            $(".error-message").remove();
            var form = $("[name  =  'tts-bus-form']");
            if ($("[name  =  'tts-bus-form']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");
                $("span.form-error", form).replaceWith("");
                $.ajax({
                    url: site_url + 'bus/bus-check-search-validation',
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
                                    $('[name="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');

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



/*bus code start here*/

$(document).on("keydown", "[tts-bus-source]", function (event) {
    $(this).autocomplete({
            minLength: 0,
            maxResults: 15,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'bus/city-list',
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

                $("[source_id]").val(ui.item.id);
                $("[tts-bus-destination]").trigger('focus');
        
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.value : ""));
            }

        }
    );
});

$(document).on("keydown", "[tts-bus-destination]", function (event) {
    $(this).autocomplete({
            minLength: 0,
            maxResults: 15,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'bus/city-list',
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
                $("[destination_id]").val(ui.item.id);
                $(".bus-depart").trigger('focus');
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.value : ""));
            }
        }
    );
});

