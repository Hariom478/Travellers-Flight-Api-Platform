function checkActivitySearchValidation() {
    setTimeout(function () {
        $(".error-message").remove();
        var form = $("[name ='activityform']");

        if ($("[name='activityform']").find('.error').length === 0) {
            $("[data-message]").removeClass().html("");
            var buttonTxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).prop('disabled', true).html('Wait...');

            $.ajax({
                url: site_url + 'activities/activity-check-search-validation',
                dataType: "json",
                type: "POST",
                cache: false,
                data: form.serialize(),
                success: function (resp) {
                    $("button[type=submit]", form).prop('disabled', false).html(buttonTxt);

                    if (resp.StatusCode === 1) {
                        var count = Object.keys(resp.ErrorMessage).length;
                        if (count > 0) {
                            $.each(resp.ErrorMessage, function (key, val) {
                                $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                            });
                        } else {
                            alert("Unexpected error! Try again.");
                        }
                    } else if (resp.StatusCode === 0) {
                        if (resp.ChangeURL) {
                            $("[name='activityform']").attr("action", resp.ChangeURL);
                        }

                        setTimeout(() => {
                            form.submit();
                        }, 5);
                    } else {
                        alert("Unexpected error! Try again.");
                    }
                },
                error: function () {
                    $("button[type=submit]", form).prop('disabled', false).html(buttonTxt);
                    alert("Unexpected error! Try again.");
                }
            });
        }
    }, 100);
    return false;
}

  




$(document).on("click", "[tts-activity-destination]", function (event) {
    var $input = $(this);

    $input.autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'activities/get-city-activity',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term.trim()
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
            $("[tts-activity-id]").val(ui.item.id);
            $("[tts-activity-tag]").val(ui.item.tag);
            $("[tts-activity-slug]").val(ui.item.activity_slug);
        },
        change: function (event, ui) {
            $input.val((ui.item ? ui.item.value : ""));
        },
        create: function () {
            $input.data('ui-autocomplete')._renderMenu = function (ul, items) {
                this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
                var that = this, currentCategory = "";
                $.each(items, function (index, item) {
                    var li;
                    if (item.tag != currentCategory) {
                        var iconClass = (item.tag == 'location') ? 'fa-map-marker' : (item.tag == 'categorys') ? 'fa-map-marker' : 'fa-umbrella';
                        ul.append("<li class='ui-autocomplete-category'> <i class='fa " + iconClass + " px-1' aria-hidden='true'></i>" + item.tag + "</li>");
                        currentCategory = item.tag;
                    }

                    li = that._renderItemData(ul, item);
                    if (item.tag) {
                        li.attr("aria-label", item.tag + " : " + item.Name);
                    }
                });
            };
        }
    });

    setTimeout(() => {
        $input.select();
        $input.autocomplete("search", " ");
    }, 200);
});






$(document).on("click", "[date-price-pick]", function (event) {
    let selected_date =  $(this).attr('selected-date');
    let price_id =  $(this).attr('price-id');
    $("[tts-price-key]").val(price_id);
    $("[tts-travel-date]").val(selected_date);
});

function plusOrMinus(filedVar, actType, minValue, Maxvalue) {
    dxArr = filedVar.split("_");
    var fieldValue = parseInt(document.getElementById(filedVar).value, 10);
  
    if (actType == "minus" && fieldValue > minValue) {
      if (dxArr[1] == "adults" && fieldValue == 1) {
      } else {
        document.getElementById(filedVar).value = fieldValue - 1;
      }
    } else if (actType == "plus" && fieldValue < Maxvalue) {
      document.getElementById(filedVar).value = fieldValue + 1;
    }
  }


 
