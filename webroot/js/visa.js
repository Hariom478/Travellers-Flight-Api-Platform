function checkVisaSearchValidation()
{

    setTimeout(function(){

            $(".error-message").remove();
            var form  = $("[name  =  'tts-visa-form']");
            if ($("[name  =  'tts-visa-form']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");

                $.ajax({
                    url: site_url + 'visa/visa-check-search-validation',
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
