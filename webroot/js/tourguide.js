function checkTourguideSearchValidation() {
  setTimeout(function () {
    $(".error-message").remove();
    var form = $("[name  =  'tourguide-form']");
    if ($("[name  =  'tourguide-form']").find('.error').length == 0) {
      $("[data-message]").removeClass().html("");
      var buttontxt;
      buttontxt = $("button[type=submit]", form).text();
      $("button[type=submit]", form).attr('disabled', true).html('Wait...');
      $("span.error-message", form).replaceWith("");
      $.ajax({
        url: site_url + 'tourguide/tourguide-check-search-validation',
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
  }, 100);
  return false;
}



$(document).on("click", "[tts-tourguide-destination]", function (event) {
  $(event.target).autocomplete({
    minLength: 0,
    maxResults: 15,
    source: function (request, response) {
      $.ajax({
        url: site_url + 'tourguide/auto-suggest',
        dataType: 'json',
        data: {
          term: request.term.trim()
        },
        success: function (data) {
          response(data);
        }
      });
    },

    select: function (event, ui) {
      $(this).val(ui.item.label);
      $("[tts-tourguide-location_id]").val(ui.item.location_id);
      return false;
    },
    focus: function (event, ui) {
      return false;
    },
    open: function (event, ui) {
      $(".ui-autocomplete").addClass('tts-autocomplet');
    },
    close: function (event, ui) {

    },
    change: function (event, ui) {

    },
  });
});

$(document).on("click", "[tts-tourguide-destination]", function (event) {
  setTimeout(() => {
    event.target.select();
    $(event.target).autocomplete("search", " ");
  }, 50);
});



$(document).on("click", "[data-adult-next]", function (event) {
  var child_val = $("[child-input]").val();
  var adt_val = $("[adult-input]").val();
  var adt_child_inf_to = Number(adt_val) + Number(child_val);
   
  if (adt_child_inf_to < 9) {
    adt_val++;
    $("[data-adult-count]").text(adt_val);
    $("[adult-input]").val(adt_val);
  }
  travelpaxcount();
});

$(document).on("click", "[data-adult-pre]", function (event) {
  var adt_val = $("[adult-input]").val();
  if (adt_val > 1) {
    adt_val--;
    $("[data-adult-count]").text(adt_val);
    $("[adult-input]").val(adt_val);
  }
  travelpaxcount();
});

$(document).on("click", "[data-child-next]", function (event) {
  var child_val = $("[child-input]").val();
  var adt_val = $("[adult-input]").val();
  var adt_child_inf_to = Number(adt_val) + Number(child_val);
  if (adt_child_inf_to < 9) {
      child_val++;
      $("[data-child-count]").text(child_val);
      $("[child-input]").val(child_val);
  }
  travelpaxcount();
});

$(document).on("click", "[data-child-pre]", function (event) {
  var child_val = $("[child-input]").val();
  if (child_val > 0) {
      child_val--;
      $("[data-child-count]").text(child_val);
      $("[child-input]").val(child_val);
  }
  travelpaxcount();
});


function travelpaxcount() 
{
  var adult = 0;
  var child = 0;
  
  var adult = parseInt(document.forms["tourguideforms"]["adults"].value);
  var child = parseInt(document.forms["tourguideforms"]["child"].value);
  var total = adult + child; 
  $("#pax").attr('data-total',(total));
  $("#pax").attr('data-adults',(adult));
  $("#pax").attr('data-children',(child));
  $("#pax").attr('placeholder','Total: '+total+' • Adults: '+adult+' • Children: '+child);

}

$(document).on("click",".tourguidebtn",function(e){
         
  var travelDate=document.tourguideforms.travel_date.value;  
  var timeSlot=document.tourguideforms.time_slot.value;
  let errorMessage = {};
  let form = $('#tourMonumentsForm');
  $('span.text-danger',form).replaceWith('');
  // $('[avail-slot]').replaceWith('');
  if(travelDate == '' || travelDate == null){
      errorMessage['travel_date'] = "Please Select Travel Date";
  }
  if(timeSlot == '' || timeSlot == null){
      errorMessage['time_slot'] = "Time Slot is Required";
  }
  console.log(errorMessage);
  console.log(Object.keys(errorMessage).length);
  if(Object.keys(errorMessage).length == 0){
     $(form).submit();
  }else{
      $('[travel-date-calendor]',form).after('<span class="text-danger">'+errorMessage['travel_date']+'</span>')
      $('[avail-slot]',form).after('<span class="text-danger">'+errorMessage['time_slot']+'</span>')
      return false;
  }
})



