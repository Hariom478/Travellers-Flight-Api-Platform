var TTSGLOBAL = TTSGLOBAL || {};

$(function () {

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // global initialization functions
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    TTSGLOBAL.global = {
        editor: function () {
            if (document.querySelectorAll('.tts-editornote').length != 0) {
                $('.tts-editornote').summernote({
                    tabsize: 2,
                    height: 350,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                        ['height', ['height']]
                    ]
                });
            }
        },
        ttsselect2search: function () {
            $('.tts_select_search').select2();
        },
        select2search: function () {
            $('.select_search').multiselect({
				columns: 1,
				texts: {
					placeholder: 'Select',
					search     : 'Search'
				},
				search: true,
				selectAll: true
			});
        },
        select2tokenizer: function () {
            $(".tokenizer").select2({
                tags: true,
                tokenSeparators: ['/',',',';'," "] 
            })
        },
        select2ajax: function () {
            let methodName = $("[tts-select2-ajax]").attr('tts-method-nam')
            $("[tts-select2-ajax]").select2({
                ajax: {
                    delay: 250,
                    url: site_url + methodName,
                    data: function (params) {
                        var query = {
                            term: params.term,
                        }

                        return query;
                    },
                    processResults: function (data) {
                        var fdata = JSON.parse(data);
                        return {
                            results: fdata.results
                        };
                    }
                }
            });
        },
        verticalTabs: function () {
            $("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
            $("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");
        },
        callajax: function (url, method, reqdata , function_name=null) {
            $.ajax({
                url: url,
                method: method,
                data: reqdata,
                cache: false,
                success: function (resp) {
                     //console.log(resp.passengerView);
                     
                    if (function_name == "visa-upload/passenger-details") {
                        $('[visa-upload-add-passenger-abhay]').attr('disabled', false);
                        $("[visa-upload-add-passenger-abhay]").attr("passenger-counter", resp.passengerCounter); 
                        $("[tts-call-put-passenger-html]").append(resp.passengerView);

                    } else if (function_name == "bus-upload-list/cancellation-add") {
                        $("[bus-upload-cancellation]").attr('disabled', false);
                        $("[bus-upload-cancellation]").attr("cancellation-counter", resp.CancellationCounter); 
                        $("[tts-call-put-cancellation-html]").append(resp.CancellationView);

                    } else if (function_name == "bus-upload-list/passenger-add") {
                        $('[bus-upload-passenger]').attr('disabled', false);
                        $("[bus-upload-passenger]").attr("passenger-counter", resp.PassengerCounter); 
                        $("[tts-call-put-passenger-html]").append(resp.PassengerView);
                        

                    } else if (function_name == "hotel-upload/add-room") {
                        $('[hotel-upload-add-room]').attr('disabled', false);
                        $("[hotel-upload-add-room]").attr("room-counter", resp.roomCounter); 
                        $("[tts-call-put-room-html]").append(resp.roomView);
                        TTSGLOBAL.global.select2tokenizer();
                       
                        TTSGLOBAL.global.editor();

                    } else if (function_name == "hotel-upload/add-passanger") {
                        if(resp.paxType=="Adult")
                        {
                            $("[hotel-upload-add-adt-pax]").attr('disabled', false);
                            $("[hotel-upload-add-adt-pax]").attr("pax-adt-counter", resp.adtCounter); 
                        }
                        if(resp.paxType=="Child")
                        {
                            $("[hotel-upload-add-chd-pax]").attr('disabled', false);
                            $("[hotel-upload-add-chd-pax]").attr("pax-chd-counter", resp.chdCounter); 
                        }
                
                        $("[tts-call-put-passanger-html='"+ resp.roomCounter +"']").append(resp.passengerView);

                        TTSGLOBAL.global.select2search();
                        TTSGLOBAL.global.ttsselect2search();
                       
                    }
                    else if (function_name == "flight-ticket-upload/segment-details") {
                        $("[tts-call-put-segment-html-" + resp.TripIndicator + "]").append(resp.segmentView);
                        $("[tripIndicator=" + resp.TripIndicator + "]").attr("segment-indicator", resp.SegmentIndicator);
                    } else if (function_name == "flight-ticket-upload/passenger-details") {
                        $("[flight-ticket-upload-add-passenger-harish]").attr("passenger-counter", resp.passengerCounter);
                        var passengerpricingStatus = $("[tts-call-put-passenger-" + resp.pax_type + "-pricing-html]").attr(resp.pax_type + "-pricing-status");
                        if ((resp.pax_type == "Child" || resp.pax_type == "Infant") && (passengerpricingStatus == "no")) {
                            $("[tts-call-put-passenger-" + resp.pax_type + "-pricing-html]").html(resp.passengerPricingView);
                            $("[flight-ticket-upload-add-passenger-harish]").attr("passenger-counter-" + resp.pax_type, resp.paxTypeCount);
                        }
                        $("[tts-call-put-passenger-" + resp.pax_type + "-html]").append(resp.passengerView);
                    } else if (function_name == "flight-ticket-upload/add-trip-details") {
                        $("[flight-ticket-upload-trip-indicator-couter]").val(resp.TripIndicator);
                        $("[tts-call-put-trip-html]").append(resp.TripView);
                    } else if(function_name == "cruise/get-cruise-cabin-id-select"){
                         $("[tts-call-cruise-cabin-put-html]").html(resp);
                    }
                    else{
                        $("[tts-call-put-html]").html(resp);
                      
                    } 
                },
                error: function (res) {
                    alert("Unexpected error! Try again.");
                }
            });
        },

        AddWaterMark: function () {
            let company_id = $("#web-partner-company-id").val();
            let agentid = company_id;
            let text = '';
            let max = 1000;
            for (let i = 0; i < max; i++) {
                text += ' ' + agentid;
            }
            if (document.getElementById('AddWaterMark')) {
                document.getElementById('AddWaterMark').innerHTML = text;
            }

        }
    
    };

    setTimeout(() => {
        $(".success_popup,.error_popup").addClass("hide");
    }, 1500);

    $(document).ready(function () {
        TTSGLOBAL.global.ttsselect2search();
        TTSGLOBAL.global.select2search();
        TTSGLOBAL.global.select2ajax();
        TTSGLOBAL.global.verticalTabs();
        TTSGLOBAL.global.editor();
        TTSGLOBAL.global.AddWaterMark();
    });


    let cityArray = [];
    $(document).on('select2:select','[tts-select2-ajax]' ,function (e) {
       let city = $('#city_id').val()
        if(city != ""){
            cityArray = city.split(",");
        }
        if(e.params.data['selected']){
            cityArray.push(e.params.data['value']);
            let city = $('#city_id').val();
             city = cityArray.toString();
            $('#city_id').val(city)
        }
    });

    $(document).on("change", '[tts-markup-used-for]', function () {
        let markup_for = $(this).val();
        if (markup_for == "B2B") {
            $("[tts-agent-class]").removeClass('d-none')
        } else {
            $("[tts-agent-class]").addClass('d-none')
        }
    });

    $(document).on("change", "#selectall", function () {
        $(".checkbox").prop('checked', $(this).prop("checked"));
        if ($('input[name="checklist[]"]:checked').length == 0) {
            $(".tts_topoption").hide('slow');
            $("[tts-disable_badge]").addClass("disable_badge");
        } else {
            $(".tts_topoption").show('slow');
            $("[tts-disable_badge]").removeClass("disable_badge");
        }
    });
    $(document).on("change", 'input[name="checklist[]"]', function () {
        var check = ($('input[name="checklist[]').filter(":checked").length == $('input[name="checklist[]').length);
        $("#selectall").prop('checked', check);

        if ($('input[name="checklist[]"]:checked').length == 0) {
            $(".tts_topoption").hide('slow');
            $("[tts-disable_badge]").addClass("disable_badge");
        } else {
            $(".tts_topoption").show('slow');
            $("[tts-disable_badge]").removeClass("disable_badge");
        }
    });
    $(document).on("submit", "[tts-form-sales='true']", function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        var name = form.attr('name');
        $("#export-data-btn").attr('disabled', true).val('Loading...');
        $("span.error-message").replaceWith("");

        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (resp) {
                console.log(resp);
                $("#export-data-btn").attr('disabled', false).val('Export Data');
                if (resp.StatusCode == 5) {
                    var $a = $("<a>");
                    $a.attr("href", resp.file);
                    $("body").append($a);
                    $a.attr("download", resp.filename);
                    $a[0].click();
                    $a.remove();
                }
            },
            error: function (res) {
                alert("Unexpected error! Try again.");
                // location.reload();
            }
        });


    });

    $(document).on("click", ".export-praveen", function (e) {
        e.preventDefault();
        var method = $(this).attr('praveen-method');
        if (method == 'post') {
            $("#sales-export").attr('tts-form-sales', true);
            $("#from_date").attr("tts-validatation", 'Required')
            $("#to_date").attr("tts-validatation", 'Required');
        } else {
            $("#sales-export").removeAttr('tts-form-sales');
            $("#to_date").removeAttr("tts-validatation");
            $("#from_date").removeAttr("tts-validatation");
        }
        $("#sales-export").attr("method", method);
        var url = $(this).attr('data-url');
        $("#sales-export").attr("action", url);
        $("#sales-export").submit();
    });


    $(document).on("focus", "[karan-from-date]", function (event) {
        $(event.target).datepicker({
            dateFormat: "d-M-y",
            changeMonth: false,
            numberOfMonths: 1,
            minDate: "+0d",
            maxDate: "+15d",
        });
    });

    $(document).on("click","#add-plan-info",function(){
        var counter = parseInt($('#counter').val())+1;
        let planInfo = '<div class="row">'+
        '<div class="col-md-3">Point '+counter+'</div>'+
        '<div class="col-md-3">'+
            '<div class="form-group form-mb-20">'+
                '<label>Title *</label>'+
                '<input type="text" class="form-control" name="planInfo['+counter+'][title]" placeholder="Title">'+
            '</div>'+
        '</div>'+
        '<div class="col-md-3">'+
            '<div class="form-group form-mb-20">'+
                '<label>Description *</label>'+
                '<input type="text" class="form-control" name="planInfo['+counter+'][description]" placeholder="Description">'+
            '</div>'+
        '</div>'+
        '<div class="col-md-3">'+
            '<label for="" role="button" class="form-label removePlanInfo"><i class="fa-solid fa-circle-xmark"></i></label>'+
        '</div>'+
        '</div>';
        $('#counter').val(counter)
        $('.plan-info-div').append(planInfo)

    });

    $(document).on("click",'.removePlanInfo',function(){
        var counter =parseInt($('#counter').val());
        if(counter > 1){
            $(this).parent().parent().remove();
            $('#counter').val(counter-1)
        }
    })

    $(document).on("click", ".select-module", function (e) {
        var module = $(this).attr('data-module');
        $("." + module).prop('checked', $(this).prop("checked"));
    });

    $(document).on("change", "[data-permission-input]", function (e) {
        var module = $(this).attr('class');
        var check = ($(":input." + module).filter(":checked").length == $(":input." + module).length);
        $("#flexCheck" + module).prop('checked', check);
    });

    $(document).on("change", "[data-main-module]", function (e) {
        var module = $(this).attr('name').split("[")[0];
        if ($(this).prop("checked")) {
            $("." + module).attr("disabled", false);
            $("#flexCheck" + module).attr("disabled", false);
        } else {
            $("." + module).prop('checked', false);
            $("#flexCheck" + module).prop('checked', false);

            $("." + module).attr("disabled", true);
            $("#flexCheck" + module).attr("disabled", true);
        }
    });


    $(document).on("submit", "[tts-form='true']", function (e) {
        e.preventDefault();
        //var form = $(this);
        var form =  $(this).closest('form');
        var url = form.attr('action');
        var method = form.attr('method');
        var name = form.attr('name');
        $("[data-message]").removeClass().html("");
        $(".form-error").removeClass().html("");
        var buttontxt;
        if ($("input[type=submit]", form).attr('value')) {
            buttontxt = $("input[type=submit]", form).attr('value');
            $("input[type=submit]", form).attr('disabled', true).val('Loading...');
        } else {
            buttontxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).attr('disabled', true).html('Loading...');
        }
        $("span.error-message", form).replaceWith("");

        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (resp) {
                if ($("input[type=submit]", form).attr('value')) {
                    $("input[type=submit]", form).attr('disabled', false).val(buttontxt);
                } else {
                    $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                }
                if (resp.StatusCode == 1) {
                    $.each(resp.ErrorMessage, function (key, val) {
                        if (key == 'service.*') {
                            $('.boot-multiselect').addClass('praveen-border');
                        }

                        if (key == 'supplier.*') {
                            $('.boot-multiselect').addClass('praveen-border');
                            // $('[name="supplier[]"]').addClass('praveen-border');
                        }

                        if (key == 'cabin_class.*') {
                            $('.boot-multiselect').addClass('praveen-border');
                        }
                        if (key == 'holiday_includes.*') {
                            $('.boot-multiselect').addClass('praveen-border');
                        }

                        if (key == 'holiday_themes_id.*') {
                            $('[href*="tts-holiday-themes"]').addClass('back-praveen-danger');

                            $(".tts-holiday-themes").html('Please select theme').addClass('tts-text-danger');
                        }

                        if (key == 'holiday_destination_id.*') {
                            $('[href*="tts-holiday-destinations"]').addClass('back-praveen-danger');

                            $(".tts-holiday-destinations").html('Please select destination').addClass('tts-text-danger');
                        }

                        // if (key == 'holiday_inclusion_id.*') {
                        //     $('[href*="tts-holiday-inclusions"]').addClass('back-praveen-danger');

                        //     $(".tts-holiday-destinations").html('Please select inclusion').addClass('tts-text-danger');
                        // }

                        if (key == 'holiday_inclusion_id.*') {
                            $('[href*="tts-holiday-inclusions"]').addClass('back-praveen-danger');

                            $(".tts-holiday-inclusions").html('Please select inclusion').addClass('tts-text-danger');
                        }

                        if (key == 'holiday_exclusion_id.*') {
                            $('[href*="tts-holiday-exclusion"]').addClass('back-praveen-danger');

                            $(".tts-holiday-exclusion").html('Please select exclusion').addClass('tts-text-danger');
                        }
                        if (key == 'holiday_description') {
                            $('[href*="tts-package-description"]').addClass('back-praveen-danger');
                        }

                        if (key == 'policies') {
                            $('[href*="tts-package-policy"]').addClass('back-praveen-danger');
                        }

                        if (key == 'payment_structure') {
                            $('[href*="tts-payment-structure"]').addClass('back-praveen-danger');
                        }

                        if (key == 'documentation') {
                            $('[href*="tts-documentation"]').addClass('back-praveen-danger');
                        }
                        if (key == 'refund_policy') {
                            $('[href*="tts-refund-policy"]').addClass('back-praveen-danger');
                        }

                        if (key == 'cancellation_policy') {
                            $('[href*="tts-cancellation-policy"]').addClass('back-praveen-danger');
                        }
                        if (key.indexOf(".") !== -1) {
                            var finalkey = key.split(".");

                            if (finalkey[5]) {
                                key = finalkey[0]+"["+finalkey[1]+"]"+"["+finalkey[2]+"]"+"["+finalkey[3]+"]"+"["+finalkey[4]+"]"+"["+finalkey[5]+"]";
                               
                            } else if (finalkey[4]) {
                                key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]" + "[" + finalkey[3] + "]" + "[" + finalkey[4] + "]";
                            }
                            else if (finalkey[3]) {
                                key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]" + "[" + finalkey[3] + "]";
                            }
                            else if (finalkey[2]) {
                                key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]";
                            } else {
                                key = finalkey[0] + "[" + finalkey[1] + "]";
                            }
                            $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');

                        } else if (key.indexOf("[]") !== -1) {
                           
                            var input = document.getElementsByName(key);
                            for (var i = 0; i < input.length; i++) {
                                var a = input[i];
                                if (val == '') {
                                } else {
                                    $(a).after('<span class="help-block form-error">' + val + '</span>');
                                }
                            }
                        } else {
                           
                            $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="help-block form-error">' + val + '</span>');
                        }
                    });
                } else if (resp.StatusCode == 0) {

                    if (resp.Reload && resp.Reload == 'false') {
                        if (resp.loadurl) {
                            var url = resp.loadurl;
                            $.get(url).done(function (data) {
                                $("[hideloader]").replaceWith("");
                                $("[data-lead-content]").html(data);
                            }).fail(function () {
                                alert("Unexpected error! Try again.");
                            });
                        }
                        if (resp.DivReload == 'true') {

                        }

                    } else {
                        setTimeout(function () {
                            window.location.reload();
                        }, 10);
                    }
                    if (resp.FormBlank) {
                        if (resp.FormBlank == 'false') {

                        } else {
                            form[0].reset();
                        }
                    }
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);

                } else if (resp.StatusCode == 5) {
                    var $a = $("<a>");
                    $a.attr("href", resp.file);
                    $("body").append($a);
                    $a.attr("download", resp.filename);
                    $a[0].click();
                    $a.remove();
                } else if (resp.StatusCode == 3) {
                    window.location.replace(resp.Redirect_Url);
                } else if (resp.StatusCode == 7) {
                    $("[tts-table-html]").html(resp.Html_data);
                    form[0].reset();
                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                } else if (resp.StatusCode == 9) {
                    //show error modal
                    $("[tts-error-message]").html(resp.ErrorMessage);
                    let ttsModal = new bootstrap.Modal(document.getElementById('error-modal'));
                    ttsModal.show();

                } else {

                    $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
                }

            },
            error: function (res) {
                alert("Unexpected error! Try again.");
                // location.reload();
            }
        });


    });

    window.onload = function () {
        $("[data-dialcode]").find(":selected").attr('data-dialcode-text', $("[data-dialcode]").find(":selected").text());
        $("[data-dialcode]").find(":selected").text($("[data-dialcode]").find(":selected").val());
    }
    $("[data-dialcode]").on('change load', function () {
        $(this).find(":selected").attr('data-dialcode-text', $(this).find(":selected").text());
        $(this).find(":selected").text($(this).find(":selected").val());
    });
    $("[data-dialcode]").on("mousedown", function (e) {
        var option = $(this).find("option:selected").attr('data-dialcode-text');
        if (option === undefined) {
        } else {
            $(this).find("option:selected").text(option);
        }
    });

    $(window).scroll(function () {
        scroll = $(window).scrollTop();
        if (scroll >= 100) {
            $(".header-with").removeClass('d-none');
            $(".header").addClass('sticky-top');

            $(".tts-logo-white").hide();
            $(".tts-logo-ragular").show();

        } else {
            $(".header-with").addClass('d-none');
            $(".header").removeClass('sticky-top');

            $(".tts-logo-white").show();
            $(".tts-logo-ragular").hide();

        }
    });

    $(document).on("click","[view-data-modal]", function (e) {
        e.preventDefault();
        $("[data-modal-view='view_modal_data']").html('<div class="text-center"><p><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" hideloader="true" aria-hidden="true" focusable="false" width="50" height="50" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" class="rotating" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><circle cx="12" cy="20" r="1" fill="#626262"/><circle cx="12" cy="4" r="1" fill="#626262"/><circle cx="6.343" cy="17.657" r="1" fill="#626262"/><circle cx="17.657" cy="6.343" r="1" fill="red"/><circle cx="4" cy="12" r="1.001" fill="green"/><circle cx="20" cy="12" r="1" fill="#626262"/><circle cx="6.343" cy="6.344" r="1" fill="#626262"/><circle cx="17.657" cy="17.658" r="1" fill="#626262"/></svg></p> <p>Please wait a few seconds</p></div>');
        $("[data-message]").removeClass().html("");
        var url = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        var view_data_modal = $(this).attr('view-data-modal');
        var entity_type = $(this).attr('data-controller');
       /*  var counter = $('#tts-holiday-counter').val()
        var holiday_counter = $(this).attr('data-counter'); */
        var counter = $(this).attr('data-counter')
        var holiday_counter = $(this).attr('data-counter');
        var  lthis = $(this);
        //ttsopenmodel('view_' + entity_type);
        if (view_data_modal == 'true') {
            
            ttsopenmodel('common_modal');
          
        }

        $.ajax({
            url: url,
            type: "post",
            data: { id: id, entity_type: entity_type,holday_counter:holiday_counter,data_counter:counter},
            success: function (resp) {
                if (resp) {
                   
                    if (resp.StatusCode == 0) {
                        $("[data-modal-view='view_modal_data']").html(resp.Message);
                        TTSGLOBAL.global.editor();
                        TTSGLOBAL.global.select2search();
                        TTSGLOBAL.global.ttsselect2search();

                        // reset list checkbox
                        $("#selectall").prop('checked', false);
                        $('input[name="checklist[]').prop('checked', false);

                    } else if (resp.StatusCode == 7) {
                        if(entity_type == "holiday" && holiday_counter > 0){
                            // var hCount = Number(holiday_counter)+1;
                            // $(lthis).attr('data-counter',hCount);
                            $(".tts-append-html-counter"+counter).append(resp.Html_data);
                        }else{
                            $("[tts-append-html]").append(resp.Html_data);
                        }
                    } else if (resp.StatusCode == 2) {
                     
                        $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);

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

    $(document).on("click", "ul.tabs li", function () {
        var tab_id = $(this).attr('data-tab');
        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');
        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    })

    $(document).on("click", ".error-message", function () {
        $(this).remove();
    });

   // airpot single select holiday autocomplete
   $(document).on("keydown", "[tts-get-holiday-airport]", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    $(this).autocomplete({
        minLength: 3,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: site_url + 'flight/get-airports',
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
        focus: function () {
            return false;
        },
        select: function (event, ui) {
            $(this).val(ui.item.city);
            return false;
        }, change: function (event, ui) {
            $(this).val((ui.item ? ui.item.city : ""));
        },
        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (
                ul, item) {
                var cityname = item.city;
                var airportcode = item.airport_code;
                var airportname = item.airport_name;
                var country_code = item.country_code;
                var countryName = item.country_name;
                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class='dest_left'>" +
                        "<samp class='city'>" +
                        cityname +
                        "</samp>" +
                        "<samp class='airpotcode'>&nbsp;(" +
                        airportname +
                        ")&nbsp;</samp>" +
                        "</div><div><samp class='aircode'>[" +
                        airportcode +
                        "]</samp><i class='flag " + (country_code.toLowerCase()) + "'></i></div>" +
                        "</a>").appendTo(ul);
            };
        },
    });
});

    // agent autocomplete
    $(document).on("keydown", "[tts-get-agent-info]", function (event) {

        $(this).autocomplete({
            minLength: 2,
            maxResults: 10,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'agent/get-agent',
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
                $(this).val(ui.item.label);
                $("[tts-agent-info-id]").val(ui.item.id);
                $("[agentinfo]").html('Agent Name: ' + ui.item.label + "<br/> " + "Balance : " + ui.item.balance);
                $("input[name='email']").val(ui.item.email_id)
                $("input[name='mobile_number']").val(ui.item.mobile_number)
                $("[tts-agent-info]").val('Agent Name: ' + ui.item.label + "<br/> " + "Balance : " + ui.item.balance);
                return false;
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.label : ""));
                $("[tts-agent-info-id]").val((ui.item ? ui.item.id : ""));

            },
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (
                    ul, item) {
                    var webpartnername = item.label;
                    return $("<li>")
                        .data("ui-autocomplete-item", item)
                        .append(
                            "<a>" +
                            "<div class=''>" +
                            "<span class='city'>" +
                            webpartnername +
                            "</span>" + "</div>" +
                            "</a>"
                        ).appendTo(ul);
                };
            },
        });
    });

     // customer autocomplete
     $(document).on("keydown", "[tts-get-customer-info]", function (event) {

        $(this).autocomplete({
            minLength: 2,
            maxResults: 10,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'customer/get-customer',
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
                $(this).val(ui.item.label);
                $("[tts-customer-info-id]").val(ui.item.id);
                $("[customerinfo]").html('Customer Name: ' + ui.item.label + "<br/> " + "Balance : " + ui.item.balance);
                $("input[name='email']").val(ui.item.email_id)
                $("input[name='mobile_number']").val(ui.item.mobile_number)
                $("[tts-customer-info]").val('Customer Name: ' + ui.item.label + "<br/> " + "Balance : " + ui.item.balance);
                return false;
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.label : ""));
                $("[tts-customer-info-id]").val((ui.item ? ui.item.id : ""));

            },
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (
                    ul, item) {
                    var webpartnername = item.label;
                    return $("<li>")
                        .data("ui-autocomplete-item", item)
                        .append(
                            "<a>" +
                            "<div class=''>" +
                            "<span class='city'>" +
                            webpartnername +
                            "</span>" + "</div>" +
                            "</a>"
                        ).appendTo(ul);
                };
            },
        });
    });




     // ailine multiple select autocomplete
     $(document).on("keydown", "[tts-get-airline-multiple]", function (event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }
        $(this).autocomplete({
            minLength: 2,
            maxResults: 10,
            open: function () {
                $(".ui-autocomplete").addClass('tts-autocomplet');
            },
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
            focus: function () {
                return false;
            },
            select: function (event, ui) {
                var terms = split(this.value);
                terms.pop();
                terms.push(ui.item.value);
                terms.push("");
                this.value = terms.join(",");
                return false;
            },


        });
    });

    $("body").on("focus", "[data-searchbar-from]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-1:+0",
            numberOfMonths: 2,
            maxDate: 0,
            onClose: function (selectedDate) {
                var newdate = new Date(selectedDate);
                $("[data-searchbar-to]").datepicker("option", "minDate", newdate);
            }
        });
    });
    $("body").on("focus", "[data-searchbar-to]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-1:+0",
            numberOfMonths: 2,
            maxDate: 0,
            beforeShow: function () {
                var seldate = $("[data-searchbar-from]").val();
                var newdate = new Date(seldate);
                $(this).datepicker("option", "minDate", newdate);
            },
            onClose: function (selectedDate) {
                $("[data-searchbar-from]").datepicker("option", selectedDate);
            }
        });
    });


    $(document).on("focus", "[data-export-from]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            maxDate: 0,
            onClose: function (selectedDate) {
                var newdate = new Date(selectedDate);
                $("[data-export-to]").datepicker("option", "minDate", newdate);
            }
        });
    });
    $(document).on("focus", "[data-export-to]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            maxDate: 0,
            beforeShow: function () {
                var seldate = $("[data-export-from]").val();
                var newdate = new Date(seldate);
                $(this).datepicker("option", "minDate", newdate);
            },
            onClose: function (selectedDate) {
                $("[data-export-from]").datepicker("option", selectedDate);

                var date1 = new Date(selectedDate);
                var date2 = new Date($('[data-export-from]').val());
                var Difference_In_Time = date2.getTime() - date1.getTime();
                var Difference_In_Days = Math.abs(Difference_In_Time / (1000 * 3600 * 24));
                if (Difference_In_Days > 365) {
                    $(this).val('');
                    alert('you can select 365 day export data');
                    return false;
                }

            }
        });
    });




    $(document).on("focus", "[data-sales-export-from]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            maxDate: 0,
            onClose: function (selectedDate) {
                $('[data-sales-export-to]').val('')
                var newdate = new Date(selectedDate);
                $("[data-export-to]").datepicker("option", "minDate", newdate);
            }
        });
    });
    $(document).on("focus", "[data-sales-export-to]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            maxDate: 0,
            beforeShow: function () {

                var seldate = $("[data-sales-export-from]").val();
                var newdate = new Date(seldate);
                $(this).datepicker("option", "minDate", newdate);
            },
            onClose: function (selectedDate) {
                $("[data-sales-export-from]").datepicker("option", selectedDate);

                var date1 = new Date(selectedDate);
                var date2 = new Date($('[data-sales-export-from]').val());
                var Difference_In_Time = date2.getTime() - date1.getTime();
                var Difference_In_Days = Math.abs(Difference_In_Time / (1000 * 3600 * 24));
                if (Difference_In_Days > 30) {
                    $(this).val('');
                    alert('you can select 30 day export data');
                    return false;
                }

            }
        });
    });


    $("body").on("focus", "[data-searchbar-calendar-from]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-1:+0",
            numberOfMonths: 2,

            onClose: function (selectedDate) {
                var newdate = new Date(selectedDate);
                $("[data-searchbar-calendar-to]").datepicker("option", "minDate", newdate).focus().select();
                ;

                $("[data-searchbar-calendar-to]").focus();
            }
        });
    });
    $("body").on("focus", "[data-searchbar-calendar-to]", function () {
        $(this).datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-1:+0",
            numberOfMonths: 2,

            beforeShow: function () {
                var seldate = $("[data-searchbar-from]").val();
                var newdate = new Date(seldate);
                $(this).datepicker("option", "minDate", newdate);
            },
            onClose: function (selectedDate) {
                $("[data-searchbar-calendar-from]").datepicker("option", selectedDate);
            }


        });
    });

    $(document).on("focus", "[nolim-calendor]", function () {
        $("[nolim-calendor]").datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-80:+10",

        });
    });

    $(document).on("focus", "[dob-calendor]", function () {
        $("[dob-calendor]").datepicker({
            dateFormat: 'dd M yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            maxDate: "0",
        });
    });

    $(document).on("focus", "[travel-date-calendor]", function () {
        $("[travel-date-calendor]").datepicker({
            dateFormat: 'dd M yy',
            changeMonth: false,
            changeYear: false,
            minDate: "0",
            maxDate: "+12m",
        });
    });

    // airpot multiple select autocomplete
    $(document).on("keydown", "[tts-get-airport]", function (event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }
        $(this).autocomplete({
            minLength: 3,
            maxResults: 10,
            open: function () {
                $(".ui-autocomplete").addClass('tts-autocomplet');
            },
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'flight/get-airports',
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
            focus: function () {
                return false;
            },
            select: function (event, ui) {
                var terms = split(this.value);
                terms.pop();
                terms.push(ui.item.airport_code);
                terms.push("");
                this.value = terms.join(",");
                return false;
            },
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (
                    ul, item) {
                    var cityname = item.city;
                    var airportcode = item.airport_code;
                    var airportname = item.airport_name;
                    var country_code = item.country_code;
                    var countryName = item.country_name;
                    return $("<li>")
                        .data("ui-autocomplete-item", item)
                        .append(
                            "<a>" +
                            "<div class='dest_left'>" +
                            "<span class='city'>" +
                            cityname +
                            "</span>" +
                            "<span class='airpotcode'>&nbsp;(" +
                            airportname +
                            ")&nbsp;</span>" +
                            "</div><div><span class='aircode'>[" +
                            airportcode +
                            "]</span><i class='flag " + (country_code.toLowerCase()) + "'></i></div>" +
                            "</a>").appendTo(ul);
                };
            },
        });
    });





    $(document).on("keydown", "[tts-bus-upload-origin-city]", function (event) {
        $(this).autocomplete({
            minLength: 3,
            maxResults: 15,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'bus-upload-list/city-list',
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
    
                $('input[name="origin_city_id"]').val(ui.item.id);
                $("[tts-bus-upload-origin-city]").val(ui.item.value);
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.value : ""));
            }
    
        });
    });
    
    $(document).on("keydown", "[tts-bus-upload-destination-city]", function (event) {
        $(this).autocomplete({
            minLength: 3,
            maxResults: 15,
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'bus-upload-list/city-list',
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
    
                $('input[name="destination_city_id"]').val(ui.item.id);
                $("[tts-bus-upload-destination-city]").val(ui.item.value);
            },
            change: function (event, ui) {
                $(this).val((ui.item ? ui.item.value : ""));
            }
    
        });
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


    // airpot single select autocomplete
    $(document).on("keydown", "[tts-get-single-airport]", function (event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }
        $(this).autocomplete({
            minLength: 3,
            maxResults: 10,
            open: function () {
                $(".ui-autocomplete").addClass('tts-autocomplet');
            },
            source: function (request, response) {
                $.ajax({
                    url: site_url + 'flight/get-airports',
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
            focus: function () {
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.airport_code);
                return false;
            }, change: function (event, ui) {
                $(this).val((ui.item ? ui.item.airport_code : ""));
            },
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (
                    ul, item) {
                    var cityname = item.city;
                    var airportcode = item.airport_code;
                    var airportname = item.airport_name;
                    var country_code = item.country_code;
                    var countryName = item.country_name;
                    return $("<li>")
                        .data("ui-autocomplete-item", item)
                        .append(
                            "<a>" +
                            "<div class='dest_left'>" +
                            "<span class='city'>" +
                            cityname +
                            "</span>" +
                            "<span class='airpotcode'>&nbsp;(" +
                            airportname +
                            ")&nbsp;</span>" +
                            "</div><div><span class='aircode'>[" +
                            airportcode +
                            "]</span><i class='flag " + (country_code.toLowerCase()) + "'></i></div>" +
                            "</a>").appendTo(ul);
                };
            },
        });
    });


    $(document).on("change", "[tts-from-any]", function (event) {
        if (this.checked) {
            $('[name="from_airport_code"]').val(this.value).attr('readonly', true);
            $('[name="from_airport_code"]').addClass('tts-read-only');
        } else {
            $('[name="from_airport_code"]').val('').attr('readonly', false);
            $('[name="from_airport_code"]').removeClass('tts-read-only');
        }
    });

    $(document).on("change", "[tts-to-any]", function (event) {
        if (this.checked) {
            $('[name="to_airport_code"]').val(this.value).attr('readonly', true);
            $('[name="to_airport_code"]').addClass('tts-read-only');
        } else {
            $('[name="to_airport_code"]').val('').attr('readonly', false);
            $('[name="to_airport_code"]').removeClass('tts-read-only');
        }
    });

    $(document).on("change", "[tts-booking-any]", function (event) {
        if (this.checked) {
            $('[name="booking_class"]').val(this.value).attr('readonly', true);
            $('[name="booking_class"]').addClass('tts-read-only');
        } else {
            $('[name="booking_class"]').val('').attr('readonly', false);
            $('[name="booking_class"]').removeClass('tts-read-only');
        }
    });

    $(document).on("change", "[tts-call-select]", function (event) {
        var selval = $(this).find(":selected").val();
        var seltext = $(this).find(":selected").text().trim();
        var method_name = $(this).attr('tts-method-name');
        var url = site_url + method_name;
        if (typeof method_name !== 'undefined') {
            var reqdata = { 'country_id': selval, 'country_name': seltext };
            TTSGLOBAL.global.callajax(url, 'Post', reqdata,method_name);
           
        } else {
            alert('Please define method name');
        }
    });
});

var modal;

function ttsopenmodel(modelid) {
    $("#" + modelid).modal('show');
}

function ttsclosemodel(thisval) {
    modelid = thisval.parentNode.closest('.modal-content,.top-model-content').parentNode.getAttribute('id');
    modal = document.getElementById(modelid);
    modal.style.display = "none";
}

function confirm_delete(formid) {
    var checkbox = document.getElementsByName('checklist[]');
    var ln = 0;
    for (var i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked)
            ln++;
    }
    if (ln === 0) {
        alert("Please Select  at least one Record");

    } else {
        var txt;
        var r = confirm("Do you want to delete record !");
        if (r == true) {
            $("#" + formid).submit();
        }
    }
}

function tts_slug_url(val, inputid) {
    var str = val.trim();
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
    // remove accents, swap ñ for n, etc
    var from = "ãàáäâáº½èéëêìíïîõòóöôùúüûñç·/_,:;";
    var to = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }
    str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    const input=document.getElementById(inputid);
    input.value=str;
}

function confirm_change_status(formid) {
    var checkedvalue = new Array();
    var checkbox = document.getElementsByName('checklist[]');
    var ln = 0;
    for (var i = 0; i < checkbox.length; i++) {
        if (checkbox[i].checked) {
            checkedvalue.push(checkbox[i].value);
            ln++;
        }
    }


    if (ln === 0) {
        alert("Please select atleast one record");
    } else {
        document.querySelector("#" + formid + '  input[name="checkedvalue"]').value = checkedvalue;
        //  document.forms['form_change_status']["checkedvalue"].value = checkedvalue;
        ttsopenmodel(formid);
    }
}

function tts_searchkey(thisval, formname) {

    var form = document.getElementsByName(formname)[0];
    var errorelements = form.querySelectorAll('.error-message');
    for (var q = 0; q < errorelements.length; ++q) {
        var itemerror = errorelements.item(q);
        itemerror.remove();
    }

    var selectedText = thisval.options[thisval.selectedIndex].innerHTML;
    var selectedValue = thisval.value;
    document.forms[formname]["key-text"].value = selectedText.trim();
    if (selectedValue) {
        if (selectedValue == 'date-range') {
            document.forms[formname]["value"].disabled = true;
            document.forms[formname]['value'].previousElementSibling.innerHTML = "Value";
            document.forms[formname]['value'].placeholder = "Value";

            if (typeof document.forms[formname]['from_date'] !== 'undefined') {


                var fromtext = document.forms[formname]['from_date'].previousElementSibling.innerHTML;
                var totext = document.forms[formname]['to_date'].previousElementSibling.innerHTML;

                document.forms[formname]['from_date'].previousElementSibling.innerHTML = fromtext + " *";
                document.forms[formname]['to_date'].previousElementSibling.innerHTML = totext + " *";

                document.forms[formname]['from_date'].setAttribute("tts-validatation", "Required");
                document.forms[formname]['to_date'].setAttribute("tts-validatation", "Required");
                document.forms[formname]['value'].removeAttribute("tts-validatation");

            }

        } else {
            document.forms[formname]["value"].disabled = false;
            document.forms[formname]['value'].previousElementSibling.innerHTML = selectedText + " *";
            document.forms[formname]['value'].placeholder = "Please Enter " + selectedText;

            if (typeof document.forms[formname]['from_date'] !== 'undefined') {
                var fromtext = document.forms[formname]['from_date'].previousElementSibling.innerHTML;
                var totext = document.forms[formname]['to_date'].previousElementSibling.innerHTML;

                document.forms[formname]['from_date'].previousElementSibling.innerHTML = fromtext.replace('*', '');
                document.forms[formname]['to_date'].previousElementSibling.innerHTML = totext.replace('*', '');


                document.forms[formname]['from_date'].removeAttribute("tts-validatation");
                document.forms[formname]['to_date'].removeAttribute("tts-validatation");
                document.forms[formname]['value'].setAttribute("tts-validatation", "Required");

            }
        }

    } else {
        document.forms[formname]['value'].previousElementSibling.innerHTML = "Value";
        document.forms[formname]['value'].placeholder = "Value";
    }
}

function searchvalidateForm() {

    var error_length = 0;
    var form = document.querySelector('form');

    var elements = form.querySelectorAll('input,select,textarea');
    var errorelements = form.querySelectorAll('.error-message');

    for (var q = 0; q < errorelements.length; ++q) {
        var itemerror = errorelements.item(q);
        itemerror.remove();
    }

    for (var i = 0; i < elements.length; ++i) {
        var item = elements.item(i);
        if (item.hasAttribute('tts-validatation')) {
            if (item.getAttribute("tts-validatation") === "Required") {
                if (item.value != '') {

                } else {
                    if (item.hasAttribute('tts-error-msg')) {
                        var error_msg = item.getAttribute("tts-error-msg");
                    } else {
                        var error_msg = "Field is required";
                    }
                    item.insertAdjacentHTML("afterend", "<div class='error-message'>" + error_msg + "</div>");
                    error_length++;
                }
            }
        }
    }
    if (error_length == 0) {
        return true;
    }
    return false;
}
function payment_status_modal(id, company) {

    let ttsModal = new bootstrap.Modal(document.getElementById('payment_status_change'));
    ttsModal.show();

    $(".payment_id").val(id);
    $(".tts_agent_company").html(company);
}
function generatePassword(len, formname) {
    var length = (len) ? (len) : (10),
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    document.forms[formname]['password'].value = retVal;
}

function change_password_modal(id, company) {
    $("#password_change").modal('show');
    document.forms['form_password_change']['password'].value = "";
    $(".tts_agent_id").val(id);
    $(".tts_agent_company").html(company.replace('-', " "));
}

function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}

function add_more_items(e, id, limit) {
    e.preventDefault();
    if (typeof limit !== 'undefined') {
    } else {
        limit = 10;
    }
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    if (lists.length >= limit) {
        alert('you have reached the maximum limit of item');
        return false;
    }


    const html = document.querySelector("#" + id).children[0].outerHTML;
    document.getElementById(id).insertAdjacentHTML('beforeend', html);

    var lastelement = document.querySelector("#" + id).children[lists.length];
    var inputs = lastelement.getElementsByClassName('form-control');
    Array.prototype.forEach.call(inputs, valhtml => {
        valhtml.value = "";
    });


    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    lists.forEach(function (index, key) {
        var countval = key + 1;
        var previoustext = index.getElementsByClassName("count")[0].getAttribute("get-text");
        if (previoustext == null) {
            previoustext = '';
        }
        index.getElementsByClassName("count")[0].innerHTML = previoustext + ' ' + countval;
    });

    TTSGLOBAL.global.select2ajax();
    TTSGLOBAL.global.select2search();
    TTSGLOBAL.global.ttsselect2search();
     
}



function remove_more_items(thisval, id) {
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    if (lists.length > 1) {
        if (confirm('Do you want to delete item ?')) {
            $(thisval).parent().parent().remove();

            var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
            lists.forEach(function (index, key) {
                var countval = key + 1;
                var previoustext = index.getElementsByClassName("count")[0].getAttribute("get-text");
                if (previoustext == null) {
                    previoustext = '';
                }
                index.getElementsByClassName("count")[0].innerHTML = previoustext + ' ' + countval;
            });
        }
    } else {
        alert("You can't delete at least 1 item");
    }
}

function add_more_items_holiday(e, id, limit, site_url) {
    e.preventDefault();
    if (typeof limit !== 'undefined') {
    } else {
        limit = 10;
    }
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    if (lists.length >= limit) {
        alert('you have reached the maximum limit of item');
        return false;
    }


    const html = document.querySelector("#" + id).children[0].outerHTML;


    var counter = document.getElementById('tts-holiday-counter').value;

    var countval = Number(counter) + 1;

    document.getElementById('tts-holiday-counter').value = countval;

    var variable_html = '' +
        '<div class="tts-itinerary-row">' +
        '                            <div id="tts-package-accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">' +
        '                                <h3 class="text-bold count text_wrap ui-accordion-header ui-corner-top ui-state-default ui-accordion-header-active ui-state-active ui-accordion-icons" get-text="Day" role="tab" id="ui-id-1" aria-controls="ui-id-2" aria-selected="true" aria-expanded="true" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>Day' + countval + '</h3>' +
        '                                <div class="row ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active"  id="ui-id-2" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">' +
        '                                    <div class="col-md-11">' +
        '                                        <div class="row">' +
        '                                            <div class="col-md-12 text-end mb_10">' +
        '' +
        '                                                <button class="badge badge-wt" view-data-modal="false" itinerary-service="flight" type="button" data-counter="' +
        countval +
        '"   data-controller="holiday" data-href="' + site_url + 'holiday/add-itinerary-flight-template">' +
        '                                                    <i class="fa-solid fa-add"></i>Flight' +
        '                                                </button>' +
        '                                                <button class="badge badge-wt" view-data-modal="false" itinerary-service="hotel" type="button" data-counter="' +
        countval +
        '"  data-controller="holiday" data-href="' + site_url + 'holiday/add-itinerary-hotel-template">' +
        '                                                    <i class="fa-solid fa-add"></i>Hotel' +
        '                                                </button>' +
        '                                                <button class="badge badge-wt" view-data-modal="false" itinerary-service="transfer" type="button" data-counter="' +
        countval +
        '"  data-controller="holiday" data-href="' + site_url + 'holiday/add-itinerary-transfer-template">' +
        '                                                    <i class="tts-icon add "></i>Transfer' +
        '                                                </button>' +
        '' +
        '                                            </div>' +
        '                                        </div>' +
        '' +
        '                                        <div class="row">' +
        '                                            <div class="col-md-3">' +
        '                                                <div class="form-group">' +
        '                                                    <label>City *</label>' +
        '                                                    <select class="form-control select2-hidden-accessible" name="itinerary[' + countval + '][city]" tts-select2-ajax="true" tts-method-nam="holiday/get-cities" placeholder="Itinerary City" data-select2-id="select2-data-4-2cgn" tabindex="-1" aria-hidden="true">' +
        '                                                        <option value="" data-select2-id="select2-data-6-z5zh">Select City</option>' +
        '                                                    </select>' +
        '                                                </div>' +
        '                                            </div>' +
        '                                            <div class="col-md-3">' +
        '                                                <div class="form-group">' +
        '                                                    <label>Day Title *</label>' +
        '                                                    <input class="form-control" type="text" name="itinerary[' + countval + '][day_title]" placeholder="Day Title">' +
        '                                                    <input type="hidden" name="itinerary[' + countval + '][primary_id]">' +
        '                                                </div>' +
        '                                            </div>' +
        '                                            <div class="col-md-3">' +
        '                                                <div class="form-group">' +
        '                                                    <label>Meal *</label>' +
        '                                                    <select class="form-control select_search select2-hidden-accessible" name="itinerary[' + countval + '][meal_type]" placeholder="Meal" data-select2-id="select2-data-1-h712" tabindex="-1" aria-hidden="true">' +
        '                                                        <option value="Breakfast Lunch Dinner" selected="" data-select2-id="select2-data-3-kto3">Breakfast Lunch Dinner</option>' +
        '                                                        <option value="Breakfast Lunch or Dinner">Breakfast Lunch or Dinner</option>' +
        '                                                        <option value="Breakfast Only">Breakfast Only</option>' +
        '                                                        <option value="No Meal">No Meal</option>' +
        '                                                        <option value="Lunch Only">Lunch Only</option>' +
        '                                                        <option value="Dinner Only">Dinner Only</option>' +
        '                                                    </select>' +
        '                                                </div>' +
        '                                            </div>' +
        '' +
        '                                            <div class="col-md-3">' +
        '                                                <div class="form-group">' +
        '                                                    <label>Itinerary Image *</label>' +
        '                                                    <input class="form-control" type="file" name="itinerary[' + countval + '][image]" placeholder="Itinerary Image">' +
        '                                                </div>' +
        '                                            </div>' +
        '' +
        '                                            <div class="col-md-12">' +
        '                                                <div class="form-group">' +
        '                                                    <label>Description *</label>' +
        '                                                    <textarea class="form-control " type="textarea" name="itinerary[' + countval + '][description]" rows="3" placeholder="Description"></textarea>' +
        '                                                </div>' +
        '                                            </div>' +
        '' +
        '                                        </div>' +
        '' +
        '                                        <div tts-append-html="true" class="tts-append-html-counter' + countval + '">' +
        '' +
        '                                        </div>' +
        '' +
        '' +
        '' +
        '                                    </div>' +
        '                                    <div class="col-md-1">' +
        '                                        <span class="action fa-solid fa-trash" onclick="remove_more_items_holiday(this,\'package-itinerary-html\')"></span>' +
        '                                    </div>' +
        '                                </div>' +
        '                            </div>' +
        '                        </div>' +
        '';


    document.getElementById(id).insertAdjacentHTML('beforeend', variable_html);
    TTSGLOBAL.global.select2ajax();
    TTSGLOBAL.global.select2search();
    TTSGLOBAL.global.ttsselect2search();
}


function remove_more_items_holiday(thisval, id) {
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    if (lists.length > 1) {
        if (confirm('Do you want to delete item ?')) {
            $(thisval).parent().parent().parent().parent().remove();
            var counter = document.getElementById('tts-holiday-counter').value;
            var countval = Number(counter) - 1;
            document.getElementById('tts-holiday-counter').value = countval;
            var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
            lists.forEach(function (index, key) {
                var countval = key + 1;
                var previoustext = index.getElementsByClassName("count")[0].getAttribute("get-text");
                if (previoustext == null) {
                    previoustext = '';
                }
                index.getElementsByClassName("count")[0].innerHTML = previoustext + ' ' + countval;
            });
        }
    } else {
        alert("You can't delete at least 1 item");
    }
}

function remove_more_items_holidays(thisval, id, itinerary_id) {
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    if (lists.length > 1) {
      if (confirm("Do you want to delete item ?")) {
  
        $.ajax({
          type: 'POST',
          url: site_url + "holiday/remove-holiday-itinerary",
          data: {itinerary_id : itinerary_id},
          success: function(response) {
            $(thisval).parent().parent().parent().parent().parent().remove();
            var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
            lists.forEach(function (index, key) {
              var countval = key + 1;
              var previoustext = index
                .getElementsByClassName("count")[0]
                .getAttribute("get-text");
              if (previoustext == null) {
                previoustext = "";
              }
              index.getElementsByClassName("count")[0].innerHTML =
                previoustext + " " + countval;
            });
            $("[data-message]")
                .addClass(response.class)
                .attr("onClick", "this.classList.add('hide')")
                .html(response.message);
          },
          error: function(error) {
            console.error("Error removing item:", error);
          }
        });
      }
    } else {
      alert("You can't delete at least 1 item");
    }
  }
function remove_more_items_services(thisval, id) {
    if (confirm('Do you want to delete item ?')) {
        $(thisval).parent().parent().parent().parent().parent().remove();
    }
}

function gst_detail(thisval, id) {
    var gst = 'false';
    if (thisval.getAttribute('aria-expanded') == 'true') {
        thisval.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
        gst = 'true';
    } else {
        thisval.innerHTML = 'ADD';
        gst = 'false';
    }
    document.getElementById(id).value = gst;
}
function showHide(id) {
    $("." + id).toggleClass("d-none");
    $("#" + id).after().toggleClass("d-none");
    let moreLess = $("[" + id + "]").html();
    if (moreLess == "View More..") {
        $("[" + id + "]").html('Hide');
    } else if (moreLess == "Hide") {
        $("[" + id + "]").html('View More..');
    } else {
        $("[" + id + "]").html('Hide');
    }
}

function continue_payment(thisval, id, formid) {

    var errorelements = document.querySelectorAll('.terms-condition');
    for (var q = 0; q < errorelements.length; ++q) {
        var itemerror = errorelements.item(q);
        itemerror.remove();
    }
    if (document.getElementById(id).checked == false) {
        document.getElementById(id).parentElement.insertAdjacentHTML('afterend', '<p class="form-error terms-condition">Please select terms and conditions</p>');
        return false;
    } else {
        if (formid) {
            document.getElementById(formid).submit();
        } else {
            alert("Please Pass form id in function");
        }
    }
}

 // city autocomplete
$(document).on("keydown", "[tts-get-city]", function (event) {
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'hotel-extranet/get-city',
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
            $(this).val(ui.item.destination);
            $("[tts-city-id]").val(ui.item.id);
            return false;
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.destination : ""));
            $("[tts-city-id]").val((ui.item ? ui.item.id : ""));
        },
        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (
                ul, item) {
                var cityname = item.destination;
                var countryName = item.country;
                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class=''>" +
                        "<samp class='city'>" +
                        cityname +
                        "</samp>" + "</div>" +
                        "</a>"
                    ).appendTo(ul);
            };
        },
    });
});

$(document).on("focus", "[start-date]", function () {
    $("[start-date]").datepicker({
        dateFormat: 'dd M yy',
        changeMonth: false,
        changeYear: false,
        minDate: "0",
        onClose: function (selectedDate) {
            $("[end-date]").datepicker("option", "minDate", selectedDate).focus().select();
        }
    });
});

$(document).on("focus", "[end-date]", function () {
    $("[end-date]").datepicker({
        dateFormat: 'dd M yy',
        changeMonth: false,
        changeYear: false,
        minDate: "0",
        beforeShow: function () {
            var selectedDate = $('[start-date]').val();
            var newdate = selectedDate.split("-").reverse().join("-");
            var newdate = new Date(newdate);
            $(this).datepicker("option", "minDate", newdate);
        },
        onClose: function (selectedDate) {
            $("[start-date]").datepicker("option", selectedDate);
        }
    });
});



//** * *********************************  Sidebar menu Js    ***************************** *//

$(function () {
    $navigation = $('#navigation');
    $dropdowns = $navigation.find('ul').parent('li');
    $a = $dropdowns.children('a');
    $(".dropdown").click(function () {
        if ($(this).hasClass('p_active')) {
            $(this).toggleClass('p_active');
            $(this).find('.submenu').slideToggle('fast').toggleClass('c_active');
        } else {
            $(".dropdown").removeClass('p_active').find('.submenu').hide();
            $(this).find('.submenu').slideToggle('fast').toggleClass('c_active');
            $(this).toggleClass('p_active');
        }
    });
    /* Start  active tab bootstrap */
    var url = window.location.href;
    var activeTab = url.substring(url.indexOf("#") + 1);
    $('.nav-tabs a[href="#' + activeTab + '"]').tab('show');
    $('[data-toggle="tab"]').click(function () {
        var active_tab_url = $(this).attr("href");
        window.location.hash = active_tab_url;
    });
});
//** * *********************************  Sidebar menu Js  END    ***************************** *//

function exportExcel() {
    $("[name  = 'export_excel']").val(1);
}

function noExportExcel() {
    $("[name  = 'export_excel']").val(0);
}



$(document).on("click", "[tts-activity-destination]", function (event) {
    $(event.target).autocomplete({
        minLength: 0,
        maxResults: 15,
      source: function(request, response) {
        $.ajax({
          url: site_url + 'activities/get-city-activity',
          dataType: 'json',
          data: {
            term: request.term.trim()
          },
          success: function(data) {
            response(data);
          }
        });
      },
     
      select: function(event, ui) {
        $(this).val(ui.item.label);
        return false;
      },
      focus: function(event, ui) {
        return false;
      },
      open: function(event, ui) {
        $(".ui-autocomplete").addClass('tts-autocomplet');
      },
      close: function(event, ui) {
       
      },
      change: function(event, ui) {
        
      },
    });
  }); 

  $(document).on("click", "[tts-activity-destination]", function (event) {
    setTimeout(() => {
        event.target.select();
        $(event.target).autocomplete("search", " ");
    }, 50);
});
 

function checkActivitySearchValidation() {

    setTimeout(function () {

            $(".error-message").remove();
            var form = $("[name  =  'activityform']");
            if ($("[name  =  'activityform']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");

                $.ajax({
                    url: site_url + 'activities/activity-check-search-validation',
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



function add_more_items_segment(e, id, limit, site_url,triptype) {
    e.preventDefault();
    if (typeof limit !== 'undefined') {
    } else {
        limit = 10;
    }
    var lists = document.querySelectorAll("#" + id + "> .tts-segment-row");
    if (lists.length >= limit) {
        alert('you have reached the maximum limit of item');
        return false;
    }
    const html = document.querySelector("#" + id).children[0].outerHTML;
    var divkeyIdentifier  =  'segment-itinerary-html';
    if(triptype==1)
    {
        var divkeyIdentifier  =  'return-segment-itinerary-html';
        var counter = document.getElementById('tts-segment-return-counter').value;
        var countval = Number(counter) + 1;
        document.getElementById('tts-segment-return-counter').value = countval;
    }
    else{
        var divkeyIdentifier  =  'segment-itinerary-html';
    var counter = document.getElementById('tts-segment-counter').value;
    var countval = Number(counter) + 1;
    document.getElementById('tts-segment-counter').value = countval;
    }
    var variable_html = '' +
        '<div class="tts-itinerary-row">' +
        '' +
        '                            <div class="row mb-3">' +
        '' +
        '' +
        '                                <div class="col-md-11">' +
        '' +
        '                                    <h5 class="dash-borderRadius main-heading-content">' +
        '                                            Segment Information</h5>' +
        '                                    ' +
        '' +
        '                                </div>' +
        '                                <div class="col-md-1 text-end"><span class="action fa-solid fa-trash" onclick="remove_more_items_segment(this, \''+divkeyIdentifier+'\')"></span>' +
        '                                </div></div>' +
        '' +
        '                               <div class="row"> <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Origin Airport Code *</label>' +
        '                                        <input class="form-control" type="text" tts-get-single-airport="true" name="onward[' + triptype + '][' + countval + '][origin_airport_code]" placeholder="Origin Airport Code">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Origin Terminal </label>' +
        '                                        <input class="form-control" type="text" name="onward[' + triptype + '][' + countval + '][origin_terminal]" placeholder="Origin Terminal">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Destination Airport Code *</label>' +
        '                                        <input class="form-control" type="text" tts-get-single-airport="true" name="onward[' + triptype + '][' + countval + '][destination_airport_code]" placeholder="Destination Airport Code">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Destination Terminal </label>' +
        '                                        <input class="form-control" type="text" name="onward[' + triptype + '][' + countval + '][destination_terminal]" placeholder="Origin Terminal">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Airline Code *</label>' +
        '                                        <input class="form-control" type="text" tts-get-airline="true" name="onward[' + triptype + '][' + countval + '][airline_code]" placeholder="Airline Code">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Flight Number </label>' +
        '                                        <input class="form-control" type="text" name="onward[' + triptype + '][' + countval + '][flight_number]" placeholder="Flight Number">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Aircraft </label>' +
        '                                        <input class="form-control" type="text" name="onward[' + triptype + '][' + countval + '][Craft]" placeholder="Aircraft">' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label class="form-label">Departure Time</label>' +
        '                                        <select class="form-control" name="onward[' + triptype + '][' + countval + '][departure_time]">' +
        '                                            <option value="" selected="">Departure Time</option>' +
        '                                                                                            <option value="00:00">00:00</option>' +
        '                                                                                            <option value="00:01">00:01</option>' +
        '                                                                                            <option value="00:02">00:02</option>' +
        '                                                                                            <option value="00:03">00:03</option>' +
        '                                                                                            <option value="00:04">00:04</option>' +
        '                                                                                            <option value="00:05">00:05</option>' +
        '                                                                                            <option value="00:06">00:06</option>' +
        '                                                                                            <option value="00:07">00:07</option>' +
        '                                                                                            <option value="00:08">00:08</option>' +
        '                                                                                            <option value="00:09">00:09</option>' +
        '                                                                                            <option value="00:10">00:10</option>' +
        '                                                                                            <option value="00:11">00:11</option>' +
        '                                                                                            <option value="00:12">00:12</option>' +
        '                                                                                            <option value="00:13">00:13</option>' +
        '                                                                                            <option value="00:14">00:14</option>' +
        '                                                                                            <option value="00:15">00:15</option>' +
        '                                                                                            <option value="00:16">00:16</option>' +
        '                                                                                            <option value="00:17">00:17</option>' +
        '                                                                                            <option value="00:18">00:18</option>' +
        '                                                                                            <option value="00:19">00:19</option>' +
        '                                                                                            <option value="00:20">00:20</option>' +
        '                                                                                            <option value="00:21">00:21</option>' +
        '                                                                                            <option value="00:22">00:22</option>' +
        '                                                                                            <option value="00:23">00:23</option>' +
        '                                                                                            <option value="00:24">00:24</option>' +
        '                                                                                            <option value="00:25">00:25</option>' +
        '                                                                                            <option value="00:26">00:26</option>' +
        '                                                                                            <option value="00:27">00:27</option>' +
        '                                                                                            <option value="00:28">00:28</option>' +
        '                                                                                            <option value="00:29">00:29</option>' +
        '                                                                                            <option value="00:30">00:30</option>' +
        '                                                                                            <option value="00:31">00:31</option>' +
        '                                                                                            <option value="00:32">00:32</option>' +
        '                                                                                            <option value="00:33">00:33</option>' +
        '                                                                                            <option value="00:34">00:34</option>' +
        '                                                                                            <option value="00:35">00:35</option>' +
        '                                                                                            <option value="00:36">00:36</option>' +
        '                                                                                            <option value="00:37">00:37</option>' +
        '                                                                                            <option value="00:38">00:38</option>' +
        '                                                                                            <option value="00:39">00:39</option>' +
        '                                                                                            <option value="00:40">00:40</option>' +
        '                                                                                            <option value="00:41">00:41</option>' +
        '                                                                                            <option value="00:42">00:42</option>' +
        '                                                                                            <option value="00:43">00:43</option>' +
        '                                                                                            <option value="00:44">00:44</option>' +
        '                                                                                            <option value="00:45">00:45</option>' +
        '                                                                                            <option value="00:46">00:46</option>' +
        '                                                                                            <option value="00:47">00:47</option>' +
        '                                                                                            <option value="00:48">00:48</option>' +
        '                                                                                            <option value="00:49">00:49</option>' +
        '                                                                                            <option value="00:50">00:50</option>' +
        '                                                                                            <option value="00:51">00:51</option>' +
        '                                                                                            <option value="00:52">00:52</option>' +
        '                                                                                            <option value="00:53">00:53</option>' +
        '                                                                                            <option value="00:54">00:54</option>' +
        '                                                                                            <option value="00:55">00:55</option>' +
        '                                                                                            <option value="00:56">00:56</option>' +
        '                                                                                            <option value="00:57">00:57</option>' +
        '                                                                                            <option value="00:58">00:58</option>' +
        '                                                                                            <option value="00:59">00:59</option>' +
        '                                                                                            <option value="01:00">01:00</option>' +
        '                                                                                            <option value="01:01">01:01</option>' +
        '                                                                                            <option value="01:02">01:02</option>' +
        '                                                                                            <option value="01:03">01:03</option>' +
        '                                                                                            <option value="01:04">01:04</option>' +
        '                                                                                            <option value="01:05">01:05</option>' +
        '                                                                                            <option value="01:06">01:06</option>' +
        '                                                                                            <option value="01:07">01:07</option>' +
        '                                                                                            <option value="01:08">01:08</option>' +
        '                                                                                            <option value="01:09">01:09</option>' +
        '                                                                                            <option value="01:10">01:10</option>' +
        '                                                                                            <option value="01:11">01:11</option>' +
        '                                                                                            <option value="01:12">01:12</option>' +
        '                                                                                            <option value="01:13">01:13</option>' +
        '                                                                                            <option value="01:14">01:14</option>' +
        '                                                                                            <option value="01:15">01:15</option>' +
        '                                                                                            <option value="01:16">01:16</option>' +
        '                                                                                            <option value="01:17">01:17</option>' +
        '                                                                                            <option value="01:18">01:18</option>' +
        '                                                                                            <option value="01:19">01:19</option>' +
        '                                                                                            <option value="01:20">01:20</option>' +
        '                                                                                            <option value="01:21">01:21</option>' +
        '                                                                                            <option value="01:22">01:22</option>' +
        '                                                                                            <option value="01:23">01:23</option>' +
        '                                                                                            <option value="01:24">01:24</option>' +
        '                                                                                            <option value="01:25">01:25</option>' +
        '                                                                                            <option value="01:26">01:26</option>' +
        '                                                                                            <option value="01:27">01:27</option>' +
        '                                                                                            <option value="01:28">01:28</option>' +
        '                                                                                            <option value="01:29">01:29</option>' +
        '                                                                                            <option value="01:30">01:30</option>' +
        '                                                                                            <option value="01:31">01:31</option>' +
        '                                                                                            <option value="01:32">01:32</option>' +
        '                                                                                            <option value="01:33">01:33</option>' +
        '                                                                                            <option value="01:34">01:34</option>' +
        '                                                                                            <option value="01:35">01:35</option>' +
        '                                                                                            <option value="01:36">01:36</option>' +
        '                                                                                            <option value="01:37">01:37</option>' +
        '                                                                                            <option value="01:38">01:38</option>' +
        '                                                                                            <option value="01:39">01:39</option>' +
        '                                                                                            <option value="01:40">01:40</option>' +
        '                                                                                            <option value="01:41">01:41</option>' +
        '                                                                                            <option value="01:42">01:42</option>' +
        '                                                                                            <option value="01:43">01:43</option>' +
        '                                                                                            <option value="01:44">01:44</option>' +
        '                                                                                            <option value="01:45">01:45</option>' +
        '                                                                                            <option value="01:46">01:46</option>' +
        '                                                                                            <option value="01:47">01:47</option>' +
        '                                                                                            <option value="01:48">01:48</option>' +
        '                                                                                            <option value="01:49">01:49</option>' +
        '                                                                                            <option value="01:50">01:50</option>' +
        '                                                                                            <option value="01:51">01:51</option>' +
        '                                                                                            <option value="01:52">01:52</option>' +
        '                                                                                            <option value="01:53">01:53</option>' +
        '                                                                                            <option value="01:54">01:54</option>' +
        '                                                                                            <option value="01:55">01:55</option>' +
        '                                                                                            <option value="01:56">01:56</option>' +
        '                                                                                            <option value="01:57">01:57</option>' +
        '                                                                                            <option value="01:58">01:58</option>' +
        '                                                                                            <option value="01:59">01:59</option>' +
        '                                                                                            <option value="02:00">02:00</option>' +
        '                                                                                            <option value="02:01">02:01</option>' +
        '                                                                                            <option value="02:02">02:02</option>' +
        '                                                                                            <option value="02:03">02:03</option>' +
        '                                                                                            <option value="02:04">02:04</option>' +
        '                                                                                            <option value="02:05">02:05</option>' +
        '                                                                                            <option value="02:06">02:06</option>' +
        '                                                                                            <option value="02:07">02:07</option>' +
        '                                                                                            <option value="02:08">02:08</option>' +
        '                                                                                            <option value="02:09">02:09</option>' +
        '                                                                                            <option value="02:10">02:10</option>' +
        '                                                                                            <option value="02:11">02:11</option>' +
        '                                                                                            <option value="02:12">02:12</option>' +
        '                                                                                            <option value="02:13">02:13</option>' +
        '                                                                                            <option value="02:14">02:14</option>' +
        '                                                                                            <option value="02:15">02:15</option>' +
        '                                                                                            <option value="02:16">02:16</option>' +
        '                                                                                            <option value="02:17">02:17</option>' +
        '                                                                                            <option value="02:18">02:18</option>' +
        '                                                                                            <option value="02:19">02:19</option>' +
        '                                                                                            <option value="02:20">02:20</option>' +
        '                                                                                            <option value="02:21">02:21</option>' +
        '                                                                                            <option value="02:22">02:22</option>' +
        '                                                                                            <option value="02:23">02:23</option>' +
        '                                                                                            <option value="02:24">02:24</option>' +
        '                                                                                            <option value="02:25">02:25</option>' +
        '                                                                                            <option value="02:26">02:26</option>' +
        '                                                                                            <option value="02:27">02:27</option>' +
        '                                                                                            <option value="02:28">02:28</option>' +
        '                                                                                            <option value="02:29">02:29</option>' +
        '                                                                                            <option value="02:30">02:30</option>' +
        '                                                                                            <option value="02:31">02:31</option>' +
        '                                                                                            <option value="02:32">02:32</option>' +
        '                                                                                            <option value="02:33">02:33</option>' +
        '                                                                                            <option value="02:34">02:34</option>' +
        '                                                                                            <option value="02:35">02:35</option>' +
        '                                                                                            <option value="02:36">02:36</option>' +
        '                                                                                            <option value="02:37">02:37</option>' +
        '                                                                                            <option value="02:38">02:38</option>' +
        '                                                                                            <option value="02:39">02:39</option>' +
        '                                                                                            <option value="02:40">02:40</option>' +
        '                                                                                            <option value="02:41">02:41</option>' +
        '                                                                                            <option value="02:42">02:42</option>' +
        '                                                                                            <option value="02:43">02:43</option>' +
        '                                                                                            <option value="02:44">02:44</option>' +
        '                                                                                            <option value="02:45">02:45</option>' +
        '                                                                                            <option value="02:46">02:46</option>' +
        '                                                                                            <option value="02:47">02:47</option>' +
        '                                                                                            <option value="02:48">02:48</option>' +
        '                                                                                            <option value="02:49">02:49</option>' +
        '                                                                                            <option value="02:50">02:50</option>' +
        '                                                                                            <option value="02:51">02:51</option>' +
        '                                                                                            <option value="02:52">02:52</option>' +
        '                                                                                            <option value="02:53">02:53</option>' +
        '                                                                                            <option value="02:54">02:54</option>' +
        '                                                                                            <option value="02:55">02:55</option>' +
        '                                                                                            <option value="02:56">02:56</option>' +
        '                                                                                            <option value="02:57">02:57</option>' +
        '                                                                                            <option value="02:58">02:58</option>' +
        '                                                                                            <option value="02:59">02:59</option>' +
        '                                                                                            <option value="03:00">03:00</option>' +
        '                                                                                            <option value="03:01">03:01</option>' +
        '                                                                                            <option value="03:02">03:02</option>' +
        '                                                                                            <option value="03:03">03:03</option>' +
        '                                                                                            <option value="03:04">03:04</option>' +
        '                                                                                            <option value="03:05">03:05</option>' +
        '                                                                                            <option value="03:06">03:06</option>' +
        '                                                                                            <option value="03:07">03:07</option>' +
        '                                                                                            <option value="03:08">03:08</option>' +
        '                                                                                            <option value="03:09">03:09</option>' +
        '                                                                                            <option value="03:10">03:10</option>' +
        '                                                                                            <option value="03:11">03:11</option>' +
        '                                                                                            <option value="03:12">03:12</option>' +
        '                                                                                            <option value="03:13">03:13</option>' +
        '                                                                                            <option value="03:14">03:14</option>' +
        '                                                                                            <option value="03:15">03:15</option>' +
        '                                                                                            <option value="03:16">03:16</option>' +
        '                                                                                            <option value="03:17">03:17</option>' +
        '                                                                                            <option value="03:18">03:18</option>' +
        '                                                                                            <option value="03:19">03:19</option>' +
        '                                                                                            <option value="03:20">03:20</option>' +
        '                                                                                            <option value="03:21">03:21</option>' +
        '                                                                                            <option value="03:22">03:22</option>' +
        '                                                                                            <option value="03:23">03:23</option>' +
        '                                                                                            <option value="03:24">03:24</option>' +
        '                                                                                            <option value="03:25">03:25</option>' +
        '                                                                                            <option value="03:26">03:26</option>' +
        '                                                                                            <option value="03:27">03:27</option>' +
        '                                                                                            <option value="03:28">03:28</option>' +
        '                                                                                            <option value="03:29">03:29</option>' +
        '                                                                                            <option value="03:30">03:30</option>' +
        '                                                                                            <option value="03:31">03:31</option>' +
        '                                                                                            <option value="03:32">03:32</option>' +
        '                                                                                            <option value="03:33">03:33</option>' +
        '                                                                                            <option value="03:34">03:34</option>' +
        '                                                                                            <option value="03:35">03:35</option>' +
        '                                                                                            <option value="03:36">03:36</option>' +
        '                                                                                            <option value="03:37">03:37</option>' +
        '                                                                                            <option value="03:38">03:38</option>' +
        '                                                                                            <option value="03:39">03:39</option>' +
        '                                                                                            <option value="03:40">03:40</option>' +
        '                                                                                            <option value="03:41">03:41</option>' +
        '                                                                                            <option value="03:42">03:42</option>' +
        '                                                                                            <option value="03:43">03:43</option>' +
        '                                                                                            <option value="03:44">03:44</option>' +
        '                                                                                            <option value="03:45">03:45</option>' +
        '                                                                                            <option value="03:46">03:46</option>' +
        '                                                                                            <option value="03:47">03:47</option>' +
        '                                                                                            <option value="03:48">03:48</option>' +
        '                                                                                            <option value="03:49">03:49</option>' +
        '                                                                                            <option value="03:50">03:50</option>' +
        '                                                                                            <option value="03:51">03:51</option>' +
        '                                                                                            <option value="03:52">03:52</option>' +
        '                                                                                            <option value="03:53">03:53</option>' +
        '                                                                                            <option value="03:54">03:54</option>' +
        '                                                                                            <option value="03:55">03:55</option>' +
        '                                                                                            <option value="03:56">03:56</option>' +
        '                                                                                            <option value="03:57">03:57</option>' +
        '                                                                                            <option value="03:58">03:58</option>' +
        '                                                                                            <option value="03:59">03:59</option>' +
        '                                                                                            <option value="04:00">04:00</option>' +
        '                                                                                            <option value="04:01">04:01</option>' +
        '                                                                                            <option value="04:02">04:02</option>' +
        '                                                                                            <option value="04:03">04:03</option>' +
        '                                                                                            <option value="04:04">04:04</option>' +
        '                                                                                            <option value="04:05">04:05</option>' +
        '                                                                                            <option value="04:06">04:06</option>' +
        '                                                                                            <option value="04:07">04:07</option>' +
        '                                                                                            <option value="04:08">04:08</option>' +
        '                                                                                            <option value="04:09">04:09</option>' +
        '                                                                                            <option value="04:10">04:10</option>' +
        '                                                                                            <option value="04:11">04:11</option>' +
        '                                                                                            <option value="04:12">04:12</option>' +
        '                                                                                            <option value="04:13">04:13</option>' +
        '                                                                                            <option value="04:14">04:14</option>' +
        '                                                                                            <option value="04:15">04:15</option>' +
        '                                                                                            <option value="04:16">04:16</option>' +
        '                                                                                            <option value="04:17">04:17</option>' +
        '                                                                                            <option value="04:18">04:18</option>' +
        '                                                                                            <option value="04:19">04:19</option>' +
        '                                                                                            <option value="04:20">04:20</option>' +
        '                                                                                            <option value="04:21">04:21</option>' +
        '                                                                                            <option value="04:22">04:22</option>' +
        '                                                                                            <option value="04:23">04:23</option>' +
        '                                                                                            <option value="04:24">04:24</option>' +
        '                                                                                            <option value="04:25">04:25</option>' +
        '                                                                                            <option value="04:26">04:26</option>' +
        '                                                                                            <option value="04:27">04:27</option>' +
        '                                                                                            <option value="04:28">04:28</option>' +
        '                                                                                            <option value="04:29">04:29</option>' +
        '                                                                                            <option value="04:30">04:30</option>' +
        '                                                                                            <option value="04:31">04:31</option>' +
        '                                                                                            <option value="04:32">04:32</option>' +
        '                                                                                            <option value="04:33">04:33</option>' +
        '                                                                                            <option value="04:34">04:34</option>' +
        '                                                                                            <option value="04:35">04:35</option>' +
        '                                                                                            <option value="04:36">04:36</option>' +
        '                                                                                            <option value="04:37">04:37</option>' +
        '                                                                                            <option value="04:38">04:38</option>' +
        '                                                                                            <option value="04:39">04:39</option>' +
        '                                                                                            <option value="04:40">04:40</option>' +
        '                                                                                            <option value="04:41">04:41</option>' +
        '                                                                                            <option value="04:42">04:42</option>' +
        '                                                                                            <option value="04:43">04:43</option>' +
        '                                                                                            <option value="04:44">04:44</option>' +
        '                                                                                            <option value="04:45">04:45</option>' +
        '                                                                                            <option value="04:46">04:46</option>' +
        '                                                                                            <option value="04:47">04:47</option>' +
        '                                                                                            <option value="04:48">04:48</option>' +
        '                                                                                            <option value="04:49">04:49</option>' +
        '                                                                                            <option value="04:50">04:50</option>' +
        '                                                                                            <option value="04:51">04:51</option>' +
        '                                                                                            <option value="04:52">04:52</option>' +
        '                                                                                            <option value="04:53">04:53</option>' +
        '                                                                                            <option value="04:54">04:54</option>' +
        '                                                                                            <option value="04:55">04:55</option>' +
        '                                                                                            <option value="04:56">04:56</option>' +
        '                                                                                            <option value="04:57">04:57</option>' +
        '                                                                                            <option value="04:58">04:58</option>' +
        '                                                                                            <option value="04:59">04:59</option>' +
        '                                                                                            <option value="05:00">05:00</option>' +
        '                                                                                            <option value="05:01">05:01</option>' +
        '                                                                                            <option value="05:02">05:02</option>' +
        '                                                                                            <option value="05:03">05:03</option>' +
        '                                                                                            <option value="05:04">05:04</option>' +
        '                                                                                            <option value="05:05">05:05</option>' +
        '                                                                                            <option value="05:06">05:06</option>' +
        '                                                                                            <option value="05:07">05:07</option>' +
        '                                                                                            <option value="05:08">05:08</option>' +
        '                                                                                            <option value="05:09">05:09</option>' +
        '                                                                                            <option value="05:10">05:10</option>' +
        '                                                                                            <option value="05:11">05:11</option>' +
        '                                                                                            <option value="05:12">05:12</option>' +
        '                                                                                            <option value="05:13">05:13</option>' +
        '                                                                                            <option value="05:14">05:14</option>' +
        '                                                                                            <option value="05:15">05:15</option>' +
        '                                                                                            <option value="05:16">05:16</option>' +
        '                                                                                            <option value="05:17">05:17</option>' +
        '                                                                                            <option value="05:18">05:18</option>' +
        '                                                                                            <option value="05:19">05:19</option>' +
        '                                                                                            <option value="05:20">05:20</option>' +
        '                                                                                            <option value="05:21">05:21</option>' +
        '                                                                                            <option value="05:22">05:22</option>' +
        '                                                                                            <option value="05:23">05:23</option>' +
        '                                                                                            <option value="05:24">05:24</option>' +
        '                                                                                            <option value="05:25">05:25</option>' +
        '                                                                                            <option value="05:26">05:26</option>' +
        '                                                                                            <option value="05:27">05:27</option>' +
        '                                                                                            <option value="05:28">05:28</option>' +
        '                                                                                            <option value="05:29">05:29</option>' +
        '                                                                                            <option value="05:30">05:30</option>' +
        '                                                                                            <option value="05:31">05:31</option>' +
        '                                                                                            <option value="05:32">05:32</option>' +
        '                                                                                            <option value="05:33">05:33</option>' +
        '                                                                                            <option value="05:34">05:34</option>' +
        '                                                                                            <option value="05:35">05:35</option>' +
        '                                                                                            <option value="05:36">05:36</option>' +
        '                                                                                            <option value="05:37">05:37</option>' +
        '                                                                                            <option value="05:38">05:38</option>' +
        '                                                                                            <option value="05:39">05:39</option>' +
        '                                                                                            <option value="05:40">05:40</option>' +
        '                                                                                            <option value="05:41">05:41</option>' +
        '                                                                                            <option value="05:42">05:42</option>' +
        '                                                                                            <option value="05:43">05:43</option>' +
        '                                                                                            <option value="05:44">05:44</option>' +
        '                                                                                            <option value="05:45">05:45</option>' +
        '                                                                                            <option value="05:46">05:46</option>' +
        '                                                                                            <option value="05:47">05:47</option>' +
        '                                                                                            <option value="05:48">05:48</option>' +
        '                                                                                            <option value="05:49">05:49</option>' +
        '                                                                                            <option value="05:50">05:50</option>' +
        '                                                                                            <option value="05:51">05:51</option>' +
        '                                                                                            <option value="05:52">05:52</option>' +
        '                                                                                            <option value="05:53">05:53</option>' +
        '                                                                                            <option value="05:54">05:54</option>' +
        '                                                                                            <option value="05:55">05:55</option>' +
        '                                                                                            <option value="05:56">05:56</option>' +
        '                                                                                            <option value="05:57">05:57</option>' +
        '                                                                                            <option value="05:58">05:58</option>' +
        '                                                                                            <option value="05:59">05:59</option>' +
        '                                                                                            <option value="06:00">06:00</option>' +
        '                                                                                            <option value="06:01">06:01</option>' +
        '                                                                                            <option value="06:02">06:02</option>' +
        '                                                                                            <option value="06:03">06:03</option>' +
        '                                                                                            <option value="06:04">06:04</option>' +
        '                                                                                            <option value="06:05">06:05</option>' +
        '                                                                                            <option value="06:06">06:06</option>' +
        '                                                                                            <option value="06:07">06:07</option>' +
        '                                                                                            <option value="06:08">06:08</option>' +
        '                                                                                            <option value="06:09">06:09</option>' +
        '                                                                                            <option value="06:10">06:10</option>' +
        '                                                                                            <option value="06:11">06:11</option>' +
        '                                                                                            <option value="06:12">06:12</option>' +
        '                                                                                            <option value="06:13">06:13</option>' +
        '                                                                                            <option value="06:14">06:14</option>' +
        '                                                                                            <option value="06:15">06:15</option>' +
        '                                                                                            <option value="06:16">06:16</option>' +
        '                                                                                            <option value="06:17">06:17</option>' +
        '                                                                                            <option value="06:18">06:18</option>' +
        '                                                                                            <option value="06:19">06:19</option>' +
        '                                                                                            <option value="06:20">06:20</option>' +
        '                                                                                            <option value="06:21">06:21</option>' +
        '                                                                                            <option value="06:22">06:22</option>' +
        '                                                                                            <option value="06:23">06:23</option>' +
        '                                                                                            <option value="06:24">06:24</option>' +
        '                                                                                            <option value="06:25">06:25</option>' +
        '                                                                                            <option value="06:26">06:26</option>' +
        '                                                                                            <option value="06:27">06:27</option>' +
        '                                                                                            <option value="06:28">06:28</option>' +
        '                                                                                            <option value="06:29">06:29</option>' +
        '                                                                                            <option value="06:30">06:30</option>' +
        '                                                                                            <option value="06:31">06:31</option>' +
        '                                                                                            <option value="06:32">06:32</option>' +
        '                                                                                            <option value="06:33">06:33</option>' +
        '                                                                                            <option value="06:34">06:34</option>' +
        '                                                                                            <option value="06:35">06:35</option>' +
        '                                                                                            <option value="06:36">06:36</option>' +
        '                                                                                            <option value="06:37">06:37</option>' +
        '                                                                                            <option value="06:38">06:38</option>' +
        '                                                                                            <option value="06:39">06:39</option>' +
        '                                                                                            <option value="06:40">06:40</option>' +
        '                                                                                            <option value="06:41">06:41</option>' +
        '                                                                                            <option value="06:42">06:42</option>' +
        '                                                                                            <option value="06:43">06:43</option>' +
        '                                                                                            <option value="06:44">06:44</option>' +
        '                                                                                            <option value="06:45">06:45</option>' +
        '                                                                                            <option value="06:46">06:46</option>' +
        '                                                                                            <option value="06:47">06:47</option>' +
        '                                                                                            <option value="06:48">06:48</option>' +
        '                                                                                            <option value="06:49">06:49</option>' +
        '                                                                                            <option value="06:50">06:50</option>' +
        '                                                                                            <option value="06:51">06:51</option>' +
        '                                                                                            <option value="06:52">06:52</option>' +
        '                                                                                            <option value="06:53">06:53</option>' +
        '                                                                                            <option value="06:54">06:54</option>' +
        '                                                                                            <option value="06:55">06:55</option>' +
        '                                                                                            <option value="06:56">06:56</option>' +
        '                                                                                            <option value="06:57">06:57</option>' +
        '                                                                                            <option value="06:58">06:58</option>' +
        '                                                                                            <option value="06:59">06:59</option>' +
        '                                                                                            <option value="07:00">07:00</option>' +
        '                                                                                            <option value="07:01">07:01</option>' +
        '                                                                                            <option value="07:02">07:02</option>' +
        '                                                                                            <option value="07:03">07:03</option>' +
        '                                                                                            <option value="07:04">07:04</option>' +
        '                                                                                            <option value="07:05">07:05</option>' +
        '                                                                                            <option value="07:06">07:06</option>' +
        '                                                                                            <option value="07:07">07:07</option>' +
        '                                                                                            <option value="07:08">07:08</option>' +
        '                                                                                            <option value="07:09">07:09</option>' +
        '                                                                                            <option value="07:10">07:10</option>' +
        '                                                                                            <option value="07:11">07:11</option>' +
        '                                                                                            <option value="07:12">07:12</option>' +
        '                                                                                            <option value="07:13">07:13</option>' +
        '                                                                                            <option value="07:14">07:14</option>' +
        '                                                                                            <option value="07:15">07:15</option>' +
        '                                                                                            <option value="07:16">07:16</option>' +
        '                                                                                            <option value="07:17">07:17</option>' +
        '                                                                                            <option value="07:18">07:18</option>' +
        '                                                                                            <option value="07:19">07:19</option>' +
        '                                                                                            <option value="07:20">07:20</option>' +
        '                                                                                            <option value="07:21">07:21</option>' +
        '                                                                                            <option value="07:22">07:22</option>' +
        '                                                                                            <option value="07:23">07:23</option>' +
        '                                                                                            <option value="07:24">07:24</option>' +
        '                                                                                            <option value="07:25">07:25</option>' +
        '                                                                                            <option value="07:26">07:26</option>' +
        '                                                                                            <option value="07:27">07:27</option>' +
        '                                                                                            <option value="07:28">07:28</option>' +
        '                                                                                            <option value="07:29">07:29</option>' +
        '                                                                                            <option value="07:30">07:30</option>' +
        '                                                                                            <option value="07:31">07:31</option>' +
        '                                                                                            <option value="07:32">07:32</option>' +
        '                                                                                            <option value="07:33">07:33</option>' +
        '                                                                                            <option value="07:34">07:34</option>' +
        '                                                                                            <option value="07:35">07:35</option>' +
        '                                                                                            <option value="07:36">07:36</option>' +
        '                                                                                            <option value="07:37">07:37</option>' +
        '                                                                                            <option value="07:38">07:38</option>' +
        '                                                                                            <option value="07:39">07:39</option>' +
        '                                                                                            <option value="07:40">07:40</option>' +
        '                                                                                            <option value="07:41">07:41</option>' +
        '                                                                                            <option value="07:42">07:42</option>' +
        '                                                                                            <option value="07:43">07:43</option>' +
        '                                                                                            <option value="07:44">07:44</option>' +
        '                                                                                            <option value="07:45">07:45</option>' +
        '                                                                                            <option value="07:46">07:46</option>' +
        '                                                                                            <option value="07:47">07:47</option>' +
        '                                                                                            <option value="07:48">07:48</option>' +
        '                                                                                            <option value="07:49">07:49</option>' +
        '                                                                                            <option value="07:50">07:50</option>' +
        '                                                                                            <option value="07:51">07:51</option>' +
        '                                                                                            <option value="07:52">07:52</option>' +
        '                                                                                            <option value="07:53">07:53</option>' +
        '                                                                                            <option value="07:54">07:54</option>' +
        '                                                                                            <option value="07:55">07:55</option>' +
        '                                                                                            <option value="07:56">07:56</option>' +
        '                                                                                            <option value="07:57">07:57</option>' +
        '                                                                                            <option value="07:58">07:58</option>' +
        '                                                                                            <option value="07:59">07:59</option>' +
        '                                                                                            <option value="08:00">08:00</option>' +
        '                                                                                            <option value="08:01">08:01</option>' +
        '                                                                                            <option value="08:02">08:02</option>' +
        '                                                                                            <option value="08:03">08:03</option>' +
        '                                                                                            <option value="08:04">08:04</option>' +
        '                                                                                            <option value="08:05">08:05</option>' +
        '                                                                                            <option value="08:06">08:06</option>' +
        '                                                                                            <option value="08:07">08:07</option>' +
        '                                                                                            <option value="08:08">08:08</option>' +
        '                                                                                            <option value="08:09">08:09</option>' +
        '                                                                                            <option value="08:10">08:10</option>' +
        '                                                                                            <option value="08:11">08:11</option>' +
        '                                                                                            <option value="08:12">08:12</option>' +
        '                                                                                            <option value="08:13">08:13</option>' +
        '                                                                                            <option value="08:14">08:14</option>' +
        '                                                                                            <option value="08:15">08:15</option>' +
        '                                                                                            <option value="08:16">08:16</option>' +
        '                                                                                            <option value="08:17">08:17</option>' +
        '                                                                                            <option value="08:18">08:18</option>' +
        '                                                                                            <option value="08:19">08:19</option>' +
        '                                                                                            <option value="08:20">08:20</option>' +
        '                                                                                            <option value="08:21">08:21</option>' +
        '                                                                                            <option value="08:22">08:22</option>' +
        '                                                                                            <option value="08:23">08:23</option>' +
        '                                                                                            <option value="08:24">08:24</option>' +
        '                                                                                            <option value="08:25">08:25</option>' +
        '                                                                                            <option value="08:26">08:26</option>' +
        '                                                                                            <option value="08:27">08:27</option>' +
        '                                                                                            <option value="08:28">08:28</option>' +
        '                                                                                            <option value="08:29">08:29</option>' +
        '                                                                                            <option value="08:30">08:30</option>' +
        '                                                                                            <option value="08:31">08:31</option>' +
        '                                                                                            <option value="08:32">08:32</option>' +
        '                                                                                            <option value="08:33">08:33</option>' +
        '                                                                                            <option value="08:34">08:34</option>' +
        '                                                                                            <option value="08:35">08:35</option>' +
        '                                                                                            <option value="08:36">08:36</option>' +
        '                                                                                            <option value="08:37">08:37</option>' +
        '                                                                                            <option value="08:38">08:38</option>' +
        '                                                                                            <option value="08:39">08:39</option>' +
        '                                                                                            <option value="08:40">08:40</option>' +
        '                                                                                            <option value="08:41">08:41</option>' +
        '                                                                                            <option value="08:42">08:42</option>' +
        '                                                                                            <option value="08:43">08:43</option>' +
        '                                                                                            <option value="08:44">08:44</option>' +
        '                                                                                            <option value="08:45">08:45</option>' +
        '                                                                                            <option value="08:46">08:46</option>' +
        '                                                                                            <option value="08:47">08:47</option>' +
        '                                                                                            <option value="08:48">08:48</option>' +
        '                                                                                            <option value="08:49">08:49</option>' +
        '                                                                                            <option value="08:50">08:50</option>' +
        '                                                                                            <option value="08:51">08:51</option>' +
        '                                                                                            <option value="08:52">08:52</option>' +
        '                                                                                            <option value="08:53">08:53</option>' +
        '                                                                                            <option value="08:54">08:54</option>' +
        '                                                                                            <option value="08:55">08:55</option>' +
        '                                                                                            <option value="08:56">08:56</option>' +
        '                                                                                            <option value="08:57">08:57</option>' +
        '                                                                                            <option value="08:58">08:58</option>' +
        '                                                                                            <option value="08:59">08:59</option>' +
        '                                                                                            <option value="09:00">09:00</option>' +
        '                                                                                            <option value="09:01">09:01</option>' +
        '                                                                                            <option value="09:02">09:02</option>' +
        '                                                                                            <option value="09:03">09:03</option>' +
        '                                                                                            <option value="09:04">09:04</option>' +
        '                                                                                            <option value="09:05">09:05</option>' +
        '                                                                                            <option value="09:06">09:06</option>' +
        '                                                                                            <option value="09:07">09:07</option>' +
        '                                                                                            <option value="09:08">09:08</option>' +
        '                                                                                            <option value="09:09">09:09</option>' +
        '                                                                                            <option value="09:10">09:10</option>' +
        '                                                                                            <option value="09:11">09:11</option>' +
        '                                                                                            <option value="09:12">09:12</option>' +
        '                                                                                            <option value="09:13">09:13</option>' +
        '                                                                                            <option value="09:14">09:14</option>' +
        '                                                                                            <option value="09:15">09:15</option>' +
        '                                                                                            <option value="09:16">09:16</option>' +
        '                                                                                            <option value="09:17">09:17</option>' +
        '                                                                                            <option value="09:18">09:18</option>' +
        '                                                                                            <option value="09:19">09:19</option>' +
        '                                                                                            <option value="09:20">09:20</option>' +
        '                                                                                            <option value="09:21">09:21</option>' +
        '                                                                                            <option value="09:22">09:22</option>' +
        '                                                                                            <option value="09:23">09:23</option>' +
        '                                                                                            <option value="09:24">09:24</option>' +
        '                                                                                            <option value="09:25">09:25</option>' +
        '                                                                                            <option value="09:26">09:26</option>' +
        '                                                                                            <option value="09:27">09:27</option>' +
        '                                                                                            <option value="09:28">09:28</option>' +
        '                                                                                            <option value="09:29">09:29</option>' +
        '                                                                                            <option value="09:30">09:30</option>' +
        '                                                                                            <option value="09:31">09:31</option>' +
        '                                                                                            <option value="09:32">09:32</option>' +
        '                                                                                            <option value="09:33">09:33</option>' +
        '                                                                                            <option value="09:34">09:34</option>' +
        '                                                                                            <option value="09:35">09:35</option>' +
        '                                                                                            <option value="09:36">09:36</option>' +
        '                                                                                            <option value="09:37">09:37</option>' +
        '                                                                                            <option value="09:38">09:38</option>' +
        '                                                                                            <option value="09:39">09:39</option>' +
        '                                                                                            <option value="09:40">09:40</option>' +
        '                                                                                            <option value="09:41">09:41</option>' +
        '                                                                                            <option value="09:42">09:42</option>' +
        '                                                                                            <option value="09:43">09:43</option>' +
        '                                                                                            <option value="09:44">09:44</option>' +
        '                                                                                            <option value="09:45">09:45</option>' +
        '                                                                                            <option value="09:46">09:46</option>' +
        '                                                                                            <option value="09:47">09:47</option>' +
        '                                                                                            <option value="09:48">09:48</option>' +
        '                                                                                            <option value="09:49">09:49</option>' +
        '                                                                                            <option value="09:50">09:50</option>' +
        '                                                                                            <option value="09:51">09:51</option>' +
        '                                                                                            <option value="09:52">09:52</option>' +
        '                                                                                            <option value="09:53">09:53</option>' +
        '                                                                                            <option value="09:54">09:54</option>' +
        '                                                                                            <option value="09:55">09:55</option>' +
        '                                                                                            <option value="09:56">09:56</option>' +
        '                                                                                            <option value="09:57">09:57</option>' +
        '                                                                                            <option value="09:58">09:58</option>' +
        '                                                                                            <option value="09:59">09:59</option>' +
        '                                                                                            <option value="10:00">10:00</option>' +
        '                                                                                            <option value="10:01">10:01</option>' +
        '                                                                                            <option value="10:02">10:02</option>' +
        '                                                                                            <option value="10:03">10:03</option>' +
        '                                                                                            <option value="10:04">10:04</option>' +
        '                                                                                            <option value="10:05">10:05</option>' +
        '                                                                                            <option value="10:06">10:06</option>' +
        '                                                                                            <option value="10:07">10:07</option>' +
        '                                                                                            <option value="10:08">10:08</option>' +
        '                                                                                            <option value="10:09">10:09</option>' +
        '                                                                                            <option value="10:10">10:10</option>' +
        '                                                                                            <option value="10:11">10:11</option>' +
        '                                                                                            <option value="10:12">10:12</option>' +
        '                                                                                            <option value="10:13">10:13</option>' +
        '                                                                                            <option value="10:14">10:14</option>' +
        '                                                                                            <option value="10:15">10:15</option>' +
        '                                                                                            <option value="10:16">10:16</option>' +
        '                                                                                            <option value="10:17">10:17</option>' +
        '                                                                                            <option value="10:18">10:18</option>' +
        '                                                                                            <option value="10:19">10:19</option>' +
        '                                                                                            <option value="10:20">10:20</option>' +
        '                                                                                            <option value="10:21">10:21</option>' +
        '                                                                                            <option value="10:22">10:22</option>' +
        '                                                                                            <option value="10:23">10:23</option>' +
        '                                                                                            <option value="10:24">10:24</option>' +
        '                                                                                            <option value="10:25">10:25</option>' +
        '                                                                                            <option value="10:26">10:26</option>' +
        '                                                                                            <option value="10:27">10:27</option>' +
        '                                                                                            <option value="10:28">10:28</option>' +
        '                                                                                            <option value="10:29">10:29</option>' +
        '                                                                                            <option value="10:30">10:30</option>' +
        '                                                                                            <option value="10:31">10:31</option>' +
        '                                                                                            <option value="10:32">10:32</option>' +
        '                                                                                            <option value="10:33">10:33</option>' +
        '                                                                                            <option value="10:34">10:34</option>' +
        '                                                                                            <option value="10:35">10:35</option>' +
        '                                                                                            <option value="10:36">10:36</option>' +
        '                                                                                            <option value="10:37">10:37</option>' +
        '                                                                                            <option value="10:38">10:38</option>' +
        '                                                                                            <option value="10:39">10:39</option>' +
        '                                                                                            <option value="10:40">10:40</option>' +
        '                                                                                            <option value="10:41">10:41</option>' +
        '                                                                                            <option value="10:42">10:42</option>' +
        '                                                                                            <option value="10:43">10:43</option>' +
        '                                                                                            <option value="10:44">10:44</option>' +
        '                                                                                            <option value="10:45">10:45</option>' +
        '                                                                                            <option value="10:46">10:46</option>' +
        '                                                                                            <option value="10:47">10:47</option>' +
        '                                                                                            <option value="10:48">10:48</option>' +
        '                                                                                            <option value="10:49">10:49</option>' +
        '                                                                                            <option value="10:50">10:50</option>' +
        '                                                                                            <option value="10:51">10:51</option>' +
        '                                                                                            <option value="10:52">10:52</option>' +
        '                                                                                            <option value="10:53">10:53</option>' +
        '                                                                                            <option value="10:54">10:54</option>' +
        '                                                                                            <option value="10:55">10:55</option>' +
        '                                                                                            <option value="10:56">10:56</option>' +
        '                                                                                            <option value="10:57">10:57</option>' +
        '                                                                                            <option value="10:58">10:58</option>' +
        '                                                                                            <option value="10:59">10:59</option>' +
        '                                                                                            <option value="11:00">11:00</option>' +
        '                                                                                            <option value="11:01">11:01</option>' +
        '                                                                                            <option value="11:02">11:02</option>' +
        '                                                                                            <option value="11:03">11:03</option>' +
        '                                                                                            <option value="11:04">11:04</option>' +
        '                                                                                            <option value="11:05">11:05</option>' +
        '                                                                                            <option value="11:06">11:06</option>' +
        '                                                                                            <option value="11:07">11:07</option>' +
        '                                                                                            <option value="11:08">11:08</option>' +
        '                                                                                            <option value="11:09">11:09</option>' +
        '                                                                                            <option value="11:10">11:10</option>' +
        '                                                                                            <option value="11:11">11:11</option>' +
        '                                                                                            <option value="11:12">11:12</option>' +
        '                                                                                            <option value="11:13">11:13</option>' +
        '                                                                                            <option value="11:14">11:14</option>' +
        '                                                                                            <option value="11:15">11:15</option>' +
        '                                                                                            <option value="11:16">11:16</option>' +
        '                                                                                            <option value="11:17">11:17</option>' +
        '                                                                                            <option value="11:18">11:18</option>' +
        '                                                                                            <option value="11:19">11:19</option>' +
        '                                                                                            <option value="11:20">11:20</option>' +
        '                                                                                            <option value="11:21">11:21</option>' +
        '                                                                                            <option value="11:22">11:22</option>' +
        '                                                                                            <option value="11:23">11:23</option>' +
        '                                                                                            <option value="11:24">11:24</option>' +
        '                                                                                            <option value="11:25">11:25</option>' +
        '                                                                                            <option value="11:26">11:26</option>' +
        '                                                                                            <option value="11:27">11:27</option>' +
        '                                                                                            <option value="11:28">11:28</option>' +
        '                                                                                            <option value="11:29">11:29</option>' +
        '                                                                                            <option value="11:30">11:30</option>' +
        '                                                                                            <option value="11:31">11:31</option>' +
        '                                                                                            <option value="11:32">11:32</option>' +
        '                                                                                            <option value="11:33">11:33</option>' +
        '                                                                                            <option value="11:34">11:34</option>' +
        '                                                                                            <option value="11:35">11:35</option>' +
        '                                                                                            <option value="11:36">11:36</option>' +
        '                                                                                            <option value="11:37">11:37</option>' +
        '                                                                                            <option value="11:38">11:38</option>' +
        '                                                                                            <option value="11:39">11:39</option>' +
        '                                                                                            <option value="11:40">11:40</option>' +
        '                                                                                            <option value="11:41">11:41</option>' +
        '                                                                                            <option value="11:42">11:42</option>' +
        '                                                                                            <option value="11:43">11:43</option>' +
        '                                                                                            <option value="11:44">11:44</option>' +
        '                                                                                            <option value="11:45">11:45</option>' +
        '                                                                                            <option value="11:46">11:46</option>' +
        '                                                                                            <option value="11:47">11:47</option>' +
        '                                                                                            <option value="11:48">11:48</option>' +
        '                                                                                            <option value="11:49">11:49</option>' +
        '                                                                                            <option value="11:50">11:50</option>' +
        '                                                                                            <option value="11:51">11:51</option>' +
        '                                                                                            <option value="11:52">11:52</option>' +
        '                                                                                            <option value="11:53">11:53</option>' +
        '                                                                                            <option value="11:54">11:54</option>' +
        '                                                                                            <option value="11:55">11:55</option>' +
        '                                                                                            <option value="11:56">11:56</option>' +
        '                                                                                            <option value="11:57">11:57</option>' +
        '                                                                                            <option value="11:58">11:58</option>' +
        '                                                                                            <option value="11:59">11:59</option>' +
        '                                                                                            <option value="12:00">12:00</option>' +
        '                                                                                            <option value="12:01">12:01</option>' +
        '                                                                                            <option value="12:02">12:02</option>' +
        '                                                                                            <option value="12:03">12:03</option>' +
        '                                                                                            <option value="12:04">12:04</option>' +
        '                                                                                            <option value="12:05">12:05</option>' +
        '                                                                                            <option value="12:06">12:06</option>' +
        '                                                                                            <option value="12:07">12:07</option>' +
        '                                                                                            <option value="12:08">12:08</option>' +
        '                                                                                            <option value="12:09">12:09</option>' +
        '                                                                                            <option value="12:10">12:10</option>' +
        '                                                                                            <option value="12:11">12:11</option>' +
        '                                                                                            <option value="12:12">12:12</option>' +
        '                                                                                            <option value="12:13">12:13</option>' +
        '                                                                                            <option value="12:14">12:14</option>' +
        '                                                                                            <option value="12:15">12:15</option>' +
        '                                                                                            <option value="12:16">12:16</option>' +
        '                                                                                            <option value="12:17">12:17</option>' +
        '                                                                                            <option value="12:18">12:18</option>' +
        '                                                                                            <option value="12:19">12:19</option>' +
        '                                                                                            <option value="12:20">12:20</option>' +
        '                                                                                            <option value="12:21">12:21</option>' +
        '                                                                                            <option value="12:22">12:22</option>' +
        '                                                                                            <option value="12:23">12:23</option>' +
        '                                                                                            <option value="12:24">12:24</option>' +
        '                                                                                            <option value="12:25">12:25</option>' +
        '                                                                                            <option value="12:26">12:26</option>' +
        '                                                                                            <option value="12:27">12:27</option>' +
        '                                                                                            <option value="12:28">12:28</option>' +
        '                                                                                            <option value="12:29">12:29</option>' +
        '                                                                                            <option value="12:30">12:30</option>' +
        '                                                                                            <option value="12:31">12:31</option>' +
        '                                                                                            <option value="12:32">12:32</option>' +
        '                                                                                            <option value="12:33">12:33</option>' +
        '                                                                                            <option value="12:34">12:34</option>' +
        '                                                                                            <option value="12:35">12:35</option>' +
        '                                                                                            <option value="12:36">12:36</option>' +
        '                                                                                            <option value="12:37">12:37</option>' +
        '                                                                                            <option value="12:38">12:38</option>' +
        '                                                                                            <option value="12:39">12:39</option>' +
        '                                                                                            <option value="12:40">12:40</option>' +
        '                                                                                            <option value="12:41">12:41</option>' +
        '                                                                                            <option value="12:42">12:42</option>' +
        '                                                                                            <option value="12:43">12:43</option>' +
        '                                                                                            <option value="12:44">12:44</option>' +
        '                                                                                            <option value="12:45">12:45</option>' +
        '                                                                                            <option value="12:46">12:46</option>' +
        '                                                                                            <option value="12:47">12:47</option>' +
        '                                                                                            <option value="12:48">12:48</option>' +
        '                                                                                            <option value="12:49">12:49</option>' +
        '                                                                                            <option value="12:50">12:50</option>' +
        '                                                                                            <option value="12:51">12:51</option>' +
        '                                                                                            <option value="12:52">12:52</option>' +
        '                                                                                            <option value="12:53">12:53</option>' +
        '                                                                                            <option value="12:54">12:54</option>' +
        '                                                                                            <option value="12:55">12:55</option>' +
        '                                                                                            <option value="12:56">12:56</option>' +
        '                                                                                            <option value="12:57">12:57</option>' +
        '                                                                                            <option value="12:58">12:58</option>' +
        '                                                                                            <option value="12:59">12:59</option>' +
        '                                                                                            <option value="13:00">13:00</option>' +
        '                                                                                            <option value="13:01">13:01</option>' +
        '                                                                                            <option value="13:02">13:02</option>' +
        '                                                                                            <option value="13:03">13:03</option>' +
        '                                                                                            <option value="13:04">13:04</option>' +
        '                                                                                            <option value="13:05">13:05</option>' +
        '                                                                                            <option value="13:06">13:06</option>' +
        '                                                                                            <option value="13:07">13:07</option>' +
        '                                                                                            <option value="13:08">13:08</option>' +
        '                                                                                            <option value="13:09">13:09</option>' +
        '                                                                                            <option value="13:10">13:10</option>' +
        '                                                                                            <option value="13:11">13:11</option>' +
        '                                                                                            <option value="13:12">13:12</option>' +
        '                                                                                            <option value="13:13">13:13</option>' +
        '                                                                                            <option value="13:14">13:14</option>' +
        '                                                                                            <option value="13:15">13:15</option>' +
        '                                                                                            <option value="13:16">13:16</option>' +
        '                                                                                            <option value="13:17">13:17</option>' +
        '                                                                                            <option value="13:18">13:18</option>' +
        '                                                                                            <option value="13:19">13:19</option>' +
        '                                                                                            <option value="13:20">13:20</option>' +
        '                                                                                            <option value="13:21">13:21</option>' +
        '                                                                                            <option value="13:22">13:22</option>' +
        '                                                                                            <option value="13:23">13:23</option>' +
        '                                                                                            <option value="13:24">13:24</option>' +
        '                                                                                            <option value="13:25">13:25</option>' +
        '                                                                                            <option value="13:26">13:26</option>' +
        '                                                                                            <option value="13:27">13:27</option>' +
        '                                                                                            <option value="13:28">13:28</option>' +
        '                                                                                            <option value="13:29">13:29</option>' +
        '                                                                                            <option value="13:30">13:30</option>' +
        '                                                                                            <option value="13:31">13:31</option>' +
        '                                                                                            <option value="13:32">13:32</option>' +
        '                                                                                            <option value="13:33">13:33</option>' +
        '                                                                                            <option value="13:34">13:34</option>' +
        '                                                                                            <option value="13:35">13:35</option>' +
        '                                                                                            <option value="13:36">13:36</option>' +
        '                                                                                            <option value="13:37">13:37</option>' +
        '                                                                                            <option value="13:38">13:38</option>' +
        '                                                                                            <option value="13:39">13:39</option>' +
        '                                                                                            <option value="13:40">13:40</option>' +
        '                                                                                            <option value="13:41">13:41</option>' +
        '                                                                                            <option value="13:42">13:42</option>' +
        '                                                                                            <option value="13:43">13:43</option>' +
        '                                                                                            <option value="13:44">13:44</option>' +
        '                                                                                            <option value="13:45">13:45</option>' +
        '                                                                                            <option value="13:46">13:46</option>' +
        '                                                                                            <option value="13:47">13:47</option>' +
        '                                                                                            <option value="13:48">13:48</option>' +
        '                                                                                            <option value="13:49">13:49</option>' +
        '                                                                                            <option value="13:50">13:50</option>' +
        '                                                                                            <option value="13:51">13:51</option>' +
        '                                                                                            <option value="13:52">13:52</option>' +
        '                                                                                            <option value="13:53">13:53</option>' +
        '                                                                                            <option value="13:54">13:54</option>' +
        '                                                                                            <option value="13:55">13:55</option>' +
        '                                                                                            <option value="13:56">13:56</option>' +
        '                                                                                            <option value="13:57">13:57</option>' +
        '                                                                                            <option value="13:58">13:58</option>' +
        '                                                                                            <option value="13:59">13:59</option>' +
        '                                                                                            <option value="14:00">14:00</option>' +
        '                                                                                            <option value="14:01">14:01</option>' +
        '                                                                                            <option value="14:02">14:02</option>' +
        '                                                                                            <option value="14:03">14:03</option>' +
        '                                                                                            <option value="14:04">14:04</option>' +
        '                                                                                            <option value="14:05">14:05</option>' +
        '                                                                                            <option value="14:06">14:06</option>' +
        '                                                                                            <option value="14:07">14:07</option>' +
        '                                                                                            <option value="14:08">14:08</option>' +
        '                                                                                            <option value="14:09">14:09</option>' +
        '                                                                                            <option value="14:10">14:10</option>' +
        '                                                                                            <option value="14:11">14:11</option>' +
        '                                                                                            <option value="14:12">14:12</option>' +
        '                                                                                            <option value="14:13">14:13</option>' +
        '                                                                                            <option value="14:14">14:14</option>' +
        '                                                                                            <option value="14:15">14:15</option>' +
        '                                                                                            <option value="14:16">14:16</option>' +
        '                                                                                            <option value="14:17">14:17</option>' +
        '                                                                                            <option value="14:18">14:18</option>' +
        '                                                                                            <option value="14:19">14:19</option>' +
        '                                                                                            <option value="14:20">14:20</option>' +
        '                                                                                            <option value="14:21">14:21</option>' +
        '                                                                                            <option value="14:22">14:22</option>' +
        '                                                                                            <option value="14:23">14:23</option>' +
        '                                                                                            <option value="14:24">14:24</option>' +
        '                                                                                            <option value="14:25">14:25</option>' +
        '                                                                                            <option value="14:26">14:26</option>' +
        '                                                                                            <option value="14:27">14:27</option>' +
        '                                                                                            <option value="14:28">14:28</option>' +
        '                                                                                            <option value="14:29">14:29</option>' +
        '                                                                                            <option value="14:30">14:30</option>' +
        '                                                                                            <option value="14:31">14:31</option>' +
        '                                                                                            <option value="14:32">14:32</option>' +
        '                                                                                            <option value="14:33">14:33</option>' +
        '                                                                                            <option value="14:34">14:34</option>' +
        '                                                                                            <option value="14:35">14:35</option>' +
        '                                                                                            <option value="14:36">14:36</option>' +
        '                                                                                            <option value="14:37">14:37</option>' +
        '                                                                                            <option value="14:38">14:38</option>' +
        '                                                                                            <option value="14:39">14:39</option>' +
        '                                                                                            <option value="14:40">14:40</option>' +
        '                                                                                            <option value="14:41">14:41</option>' +
        '                                                                                            <option value="14:42">14:42</option>' +
        '                                                                                            <option value="14:43">14:43</option>' +
        '                                                                                            <option value="14:44">14:44</option>' +
        '                                                                                            <option value="14:45">14:45</option>' +
        '                                                                                            <option value="14:46">14:46</option>' +
        '                                                                                            <option value="14:47">14:47</option>' +
        '                                                                                            <option value="14:48">14:48</option>' +
        '                                                                                            <option value="14:49">14:49</option>' +
        '                                                                                            <option value="14:50">14:50</option>' +
        '                                                                                            <option value="14:51">14:51</option>' +
        '                                                                                            <option value="14:52">14:52</option>' +
        '                                                                                            <option value="14:53">14:53</option>' +
        '                                                                                            <option value="14:54">14:54</option>' +
        '                                                                                            <option value="14:55">14:55</option>' +
        '                                                                                            <option value="14:56">14:56</option>' +
        '                                                                                            <option value="14:57">14:57</option>' +
        '                                                                                            <option value="14:58">14:58</option>' +
        '                                                                                            <option value="14:59">14:59</option>' +
        '                                                                                            <option value="15:00">15:00</option>' +
        '                                                                                            <option value="15:01">15:01</option>' +
        '                                                                                            <option value="15:02">15:02</option>' +
        '                                                                                            <option value="15:03">15:03</option>' +
        '                                                                                            <option value="15:04">15:04</option>' +
        '                                                                                            <option value="15:05">15:05</option>' +
        '                                                                                            <option value="15:06">15:06</option>' +
        '                                                                                            <option value="15:07">15:07</option>' +
        '                                                                                            <option value="15:08">15:08</option>' +
        '                                                                                            <option value="15:09">15:09</option>' +
        '                                                                                            <option value="15:10">15:10</option>' +
        '                                                                                            <option value="15:11">15:11</option>' +
        '                                                                                            <option value="15:12">15:12</option>' +
        '                                                                                            <option value="15:13">15:13</option>' +
        '                                                                                            <option value="15:14">15:14</option>' +
        '                                                                                            <option value="15:15">15:15</option>' +
        '                                                                                            <option value="15:16">15:16</option>' +
        '                                                                                            <option value="15:17">15:17</option>' +
        '                                                                                            <option value="15:18">15:18</option>' +
        '                                                                                            <option value="15:19">15:19</option>' +
        '                                                                                            <option value="15:20">15:20</option>' +
        '                                                                                            <option value="15:21">15:21</option>' +
        '                                                                                            <option value="15:22">15:22</option>' +
        '                                                                                            <option value="15:23">15:23</option>' +
        '                                                                                            <option value="15:24">15:24</option>' +
        '                                                                                            <option value="15:25">15:25</option>' +
        '                                                                                            <option value="15:26">15:26</option>' +
        '                                                                                            <option value="15:27">15:27</option>' +
        '                                                                                            <option value="15:28">15:28</option>' +
        '                                                                                            <option value="15:29">15:29</option>' +
        '                                                                                            <option value="15:30">15:30</option>' +
        '                                                                                            <option value="15:31">15:31</option>' +
        '                                                                                            <option value="15:32">15:32</option>' +
        '                                                                                            <option value="15:33">15:33</option>' +
        '                                                                                            <option value="15:34">15:34</option>' +
        '                                                                                            <option value="15:35">15:35</option>' +
        '                                                                                            <option value="15:36">15:36</option>' +
        '                                                                                            <option value="15:37">15:37</option>' +
        '                                                                                            <option value="15:38">15:38</option>' +
        '                                                                                            <option value="15:39">15:39</option>' +
        '                                                                                            <option value="15:40">15:40</option>' +
        '                                                                                            <option value="15:41">15:41</option>' +
        '                                                                                            <option value="15:42">15:42</option>' +
        '                                                                                            <option value="15:43">15:43</option>' +
        '                                                                                            <option value="15:44">15:44</option>' +
        '                                                                                            <option value="15:45">15:45</option>' +
        '                                                                                            <option value="15:46">15:46</option>' +
        '                                                                                            <option value="15:47">15:47</option>' +
        '                                                                                            <option value="15:48">15:48</option>' +
        '                                                                                            <option value="15:49">15:49</option>' +
        '                                                                                            <option value="15:50">15:50</option>' +
        '                                                                                            <option value="15:51">15:51</option>' +
        '                                                                                            <option value="15:52">15:52</option>' +
        '                                                                                            <option value="15:53">15:53</option>' +
        '                                                                                            <option value="15:54">15:54</option>' +
        '                                                                                            <option value="15:55">15:55</option>' +
        '                                                                                            <option value="15:56">15:56</option>' +
        '                                                                                            <option value="15:57">15:57</option>' +
        '                                                                                            <option value="15:58">15:58</option>' +
        '                                                                                            <option value="15:59">15:59</option>' +
        '                                                                                            <option value="16:00">16:00</option>' +
        '                                                                                            <option value="16:01">16:01</option>' +
        '                                                                                            <option value="16:02">16:02</option>' +
        '                                                                                            <option value="16:03">16:03</option>' +
        '                                                                                            <option value="16:04">16:04</option>' +
        '                                                                                            <option value="16:05">16:05</option>' +
        '                                                                                            <option value="16:06">16:06</option>' +
        '                                                                                            <option value="16:07">16:07</option>' +
        '                                                                                            <option value="16:08">16:08</option>' +
        '                                                                                            <option value="16:09">16:09</option>' +
        '                                                                                            <option value="16:10">16:10</option>' +
        '                                                                                            <option value="16:11">16:11</option>' +
        '                                                                                            <option value="16:12">16:12</option>' +
        '                                                                                            <option value="16:13">16:13</option>' +
        '                                                                                            <option value="16:14">16:14</option>' +
        '                                                                                            <option value="16:15">16:15</option>' +
        '                                                                                            <option value="16:16">16:16</option>' +
        '                                                                                            <option value="16:17">16:17</option>' +
        '                                                                                            <option value="16:18">16:18</option>' +
        '                                                                                            <option value="16:19">16:19</option>' +
        '                                                                                            <option value="16:20">16:20</option>' +
        '                                                                                            <option value="16:21">16:21</option>' +
        '                                                                                            <option value="16:22">16:22</option>' +
        '                                                                                            <option value="16:23">16:23</option>' +
        '                                                                                            <option value="16:24">16:24</option>' +
        '                                                                                            <option value="16:25">16:25</option>' +
        '                                                                                            <option value="16:26">16:26</option>' +
        '                                                                                            <option value="16:27">16:27</option>' +
        '                                                                                            <option value="16:28">16:28</option>' +
        '                                                                                            <option value="16:29">16:29</option>' +
        '                                                                                            <option value="16:30">16:30</option>' +
        '                                                                                            <option value="16:31">16:31</option>' +
        '                                                                                            <option value="16:32">16:32</option>' +
        '                                                                                            <option value="16:33">16:33</option>' +
        '                                                                                            <option value="16:34">16:34</option>' +
        '                                                                                            <option value="16:35">16:35</option>' +
        '                                                                                            <option value="16:36">16:36</option>' +
        '                                                                                            <option value="16:37">16:37</option>' +
        '                                                                                            <option value="16:38">16:38</option>' +
        '                                                                                            <option value="16:39">16:39</option>' +
        '                                                                                            <option value="16:40">16:40</option>' +
        '                                                                                            <option value="16:41">16:41</option>' +
        '                                                                                            <option value="16:42">16:42</option>' +
        '                                                                                            <option value="16:43">16:43</option>' +
        '                                                                                            <option value="16:44">16:44</option>' +
        '                                                                                            <option value="16:45">16:45</option>' +
        '                                                                                            <option value="16:46">16:46</option>' +
        '                                                                                            <option value="16:47">16:47</option>' +
        '                                                                                            <option value="16:48">16:48</option>' +
        '                                                                                            <option value="16:49">16:49</option>' +
        '                                                                                            <option value="16:50">16:50</option>' +
        '                                                                                            <option value="16:51">16:51</option>' +
        '                                                                                            <option value="16:52">16:52</option>' +
        '                                                                                            <option value="16:53">16:53</option>' +
        '                                                                                            <option value="16:54">16:54</option>' +
        '                                                                                            <option value="16:55">16:55</option>' +
        '                                                                                            <option value="16:56">16:56</option>' +
        '                                                                                            <option value="16:57">16:57</option>' +
        '                                                                                            <option value="16:58">16:58</option>' +
        '                                                                                            <option value="16:59">16:59</option>' +
        '                                                                                            <option value="17:00">17:00</option>' +
        '                                                                                            <option value="17:01">17:01</option>' +
        '                                                                                            <option value="17:02">17:02</option>' +
        '                                                                                            <option value="17:03">17:03</option>' +
        '                                                                                            <option value="17:04">17:04</option>' +
        '                                                                                            <option value="17:05">17:05</option>' +
        '                                                                                            <option value="17:06">17:06</option>' +
        '                                                                                            <option value="17:07">17:07</option>' +
        '                                                                                            <option value="17:08">17:08</option>' +
        '                                                                                            <option value="17:09">17:09</option>' +
        '                                                                                            <option value="17:10">17:10</option>' +
        '                                                                                            <option value="17:11">17:11</option>' +
        '                                                                                            <option value="17:12">17:12</option>' +
        '                                                                                            <option value="17:13">17:13</option>' +
        '                                                                                            <option value="17:14">17:14</option>' +
        '                                                                                            <option value="17:15">17:15</option>' +
        '                                                                                            <option value="17:16">17:16</option>' +
        '                                                                                            <option value="17:17">17:17</option>' +
        '                                                                                            <option value="17:18">17:18</option>' +
        '                                                                                            <option value="17:19">17:19</option>' +
        '                                                                                            <option value="17:20">17:20</option>' +
        '                                                                                            <option value="17:21">17:21</option>' +
        '                                                                                            <option value="17:22">17:22</option>' +
        '                                                                                            <option value="17:23">17:23</option>' +
        '                                                                                            <option value="17:24">17:24</option>' +
        '                                                                                            <option value="17:25">17:25</option>' +
        '                                                                                            <option value="17:26">17:26</option>' +
        '                                                                                            <option value="17:27">17:27</option>' +
        '                                                                                            <option value="17:28">17:28</option>' +
        '                                                                                            <option value="17:29">17:29</option>' +
        '                                                                                            <option value="17:30">17:30</option>' +
        '                                                                                            <option value="17:31">17:31</option>' +
        '                                                                                            <option value="17:32">17:32</option>' +
        '                                                                                            <option value="17:33">17:33</option>' +
        '                                                                                            <option value="17:34">17:34</option>' +
        '                                                                                            <option value="17:35">17:35</option>' +
        '                                                                                            <option value="17:36">17:36</option>' +
        '                                                                                            <option value="17:37">17:37</option>' +
        '                                                                                            <option value="17:38">17:38</option>' +
        '                                                                                            <option value="17:39">17:39</option>' +
        '                                                                                            <option value="17:40">17:40</option>' +
        '                                                                                            <option value="17:41">17:41</option>' +
        '                                                                                            <option value="17:42">17:42</option>' +
        '                                                                                            <option value="17:43">17:43</option>' +
        '                                                                                            <option value="17:44">17:44</option>' +
        '                                                                                            <option value="17:45">17:45</option>' +
        '                                                                                            <option value="17:46">17:46</option>' +
        '                                                                                            <option value="17:47">17:47</option>' +
        '                                                                                            <option value="17:48">17:48</option>' +
        '                                                                                            <option value="17:49">17:49</option>' +
        '                                                                                            <option value="17:50">17:50</option>' +
        '                                                                                            <option value="17:51">17:51</option>' +
        '                                                                                            <option value="17:52">17:52</option>' +
        '                                                                                            <option value="17:53">17:53</option>' +
        '                                                                                            <option value="17:54">17:54</option>' +
        '                                                                                            <option value="17:55">17:55</option>' +
        '                                                                                            <option value="17:56">17:56</option>' +
        '                                                                                            <option value="17:57">17:57</option>' +
        '                                                                                            <option value="17:58">17:58</option>' +
        '                                                                                            <option value="17:59">17:59</option>' +
        '                                                                                            <option value="18:00">18:00</option>' +
        '                                                                                            <option value="18:01">18:01</option>' +
        '                                                                                            <option value="18:02">18:02</option>' +
        '                                                                                            <option value="18:03">18:03</option>' +
        '                                                                                            <option value="18:04">18:04</option>' +
        '                                                                                            <option value="18:05">18:05</option>' +
        '                                                                                            <option value="18:06">18:06</option>' +
        '                                                                                            <option value="18:07">18:07</option>' +
        '                                                                                            <option value="18:08">18:08</option>' +
        '                                                                                            <option value="18:09">18:09</option>' +
        '                                                                                            <option value="18:10">18:10</option>' +
        '                                                                                            <option value="18:11">18:11</option>' +
        '                                                                                            <option value="18:12">18:12</option>' +
        '                                                                                            <option value="18:13">18:13</option>' +
        '                                                                                            <option value="18:14">18:14</option>' +
        '                                                                                            <option value="18:15">18:15</option>' +
        '                                                                                            <option value="18:16">18:16</option>' +
        '                                                                                            <option value="18:17">18:17</option>' +
        '                                                                                            <option value="18:18">18:18</option>' +
        '                                                                                            <option value="18:19">18:19</option>' +
        '                                                                                            <option value="18:20">18:20</option>' +
        '                                                                                            <option value="18:21">18:21</option>' +
        '                                                                                            <option value="18:22">18:22</option>' +
        '                                                                                            <option value="18:23">18:23</option>' +
        '                                                                                            <option value="18:24">18:24</option>' +
        '                                                                                            <option value="18:25">18:25</option>' +
        '                                                                                            <option value="18:26">18:26</option>' +
        '                                                                                            <option value="18:27">18:27</option>' +
        '                                                                                            <option value="18:28">18:28</option>' +
        '                                                                                            <option value="18:29">18:29</option>' +
        '                                                                                            <option value="18:30">18:30</option>' +
        '                                                                                            <option value="18:31">18:31</option>' +
        '                                                                                            <option value="18:32">18:32</option>' +
        '                                                                                            <option value="18:33">18:33</option>' +
        '                                                                                            <option value="18:34">18:34</option>' +
        '                                                                                            <option value="18:35">18:35</option>' +
        '                                                                                            <option value="18:36">18:36</option>' +
        '                                                                                            <option value="18:37">18:37</option>' +
        '                                                                                            <option value="18:38">18:38</option>' +
        '                                                                                            <option value="18:39">18:39</option>' +
        '                                                                                            <option value="18:40">18:40</option>' +
        '                                                                                            <option value="18:41">18:41</option>' +
        '                                                                                            <option value="18:42">18:42</option>' +
        '                                                                                            <option value="18:43">18:43</option>' +
        '                                                                                            <option value="18:44">18:44</option>' +
        '                                                                                            <option value="18:45">18:45</option>' +
        '                                                                                            <option value="18:46">18:46</option>' +
        '                                                                                            <option value="18:47">18:47</option>' +
        '                                                                                            <option value="18:48">18:48</option>' +
        '                                                                                            <option value="18:49">18:49</option>' +
        '                                                                                            <option value="18:50">18:50</option>' +
        '                                                                                            <option value="18:51">18:51</option>' +
        '                                                                                            <option value="18:52">18:52</option>' +
        '                                                                                            <option value="18:53">18:53</option>' +
        '                                                                                            <option value="18:54">18:54</option>' +
        '                                                                                            <option value="18:55">18:55</option>' +
        '                                                                                            <option value="18:56">18:56</option>' +
        '                                                                                            <option value="18:57">18:57</option>' +
        '                                                                                            <option value="18:58">18:58</option>' +
        '                                                                                            <option value="18:59">18:59</option>' +
        '                                                                                            <option value="19:00">19:00</option>' +
        '                                                                                            <option value="19:01">19:01</option>' +
        '                                                                                            <option value="19:02">19:02</option>' +
        '                                                                                            <option value="19:03">19:03</option>' +
        '                                                                                            <option value="19:04">19:04</option>' +
        '                                                                                            <option value="19:05">19:05</option>' +
        '                                                                                            <option value="19:06">19:06</option>' +
        '                                                                                            <option value="19:07">19:07</option>' +
        '                                                                                            <option value="19:08">19:08</option>' +
        '                                                                                            <option value="19:09">19:09</option>' +
        '                                                                                            <option value="19:10">19:10</option>' +
        '                                                                                            <option value="19:11">19:11</option>' +
        '                                                                                            <option value="19:12">19:12</option>' +
        '                                                                                            <option value="19:13">19:13</option>' +
        '                                                                                            <option value="19:14">19:14</option>' +
        '                                                                                            <option value="19:15">19:15</option>' +
        '                                                                                            <option value="19:16">19:16</option>' +
        '                                                                                            <option value="19:17">19:17</option>' +
        '                                                                                            <option value="19:18">19:18</option>' +
        '                                                                                            <option value="19:19">19:19</option>' +
        '                                                                                            <option value="19:20">19:20</option>' +
        '                                                                                            <option value="19:21">19:21</option>' +
        '                                                                                            <option value="19:22">19:22</option>' +
        '                                                                                            <option value="19:23">19:23</option>' +
        '                                                                                            <option value="19:24">19:24</option>' +
        '                                                                                            <option value="19:25">19:25</option>' +
        '                                                                                            <option value="19:26">19:26</option>' +
        '                                                                                            <option value="19:27">19:27</option>' +
        '                                                                                            <option value="19:28">19:28</option>' +
        '                                                                                            <option value="19:29">19:29</option>' +
        '                                                                                            <option value="19:30">19:30</option>' +
        '                                                                                            <option value="19:31">19:31</option>' +
        '                                                                                            <option value="19:32">19:32</option>' +
        '                                                                                            <option value="19:33">19:33</option>' +
        '                                                                                            <option value="19:34">19:34</option>' +
        '                                                                                            <option value="19:35">19:35</option>' +
        '                                                                                            <option value="19:36">19:36</option>' +
        '                                                                                            <option value="19:37">19:37</option>' +
        '                                                                                            <option value="19:38">19:38</option>' +
        '                                                                                            <option value="19:39">19:39</option>' +
        '                                                                                            <option value="19:40">19:40</option>' +
        '                                                                                            <option value="19:41">19:41</option>' +
        '                                                                                            <option value="19:42">19:42</option>' +
        '                                                                                            <option value="19:43">19:43</option>' +
        '                                                                                            <option value="19:44">19:44</option>' +
        '                                                                                            <option value="19:45">19:45</option>' +
        '                                                                                            <option value="19:46">19:46</option>' +
        '                                                                                            <option value="19:47">19:47</option>' +
        '                                                                                            <option value="19:48">19:48</option>' +
        '                                                                                            <option value="19:49">19:49</option>' +
        '                                                                                            <option value="19:50">19:50</option>' +
        '                                                                                            <option value="19:51">19:51</option>' +
        '                                                                                            <option value="19:52">19:52</option>' +
        '                                                                                            <option value="19:53">19:53</option>' +
        '                                                                                            <option value="19:54">19:54</option>' +
        '                                                                                            <option value="19:55">19:55</option>' +
        '                                                                                            <option value="19:56">19:56</option>' +
        '                                                                                            <option value="19:57">19:57</option>' +
        '                                                                                            <option value="19:58">19:58</option>' +
        '                                                                                            <option value="19:59">19:59</option>' +
        '                                                                                            <option value="20:00">20:00</option>' +
        '                                                                                            <option value="20:01">20:01</option>' +
        '                                                                                            <option value="20:02">20:02</option>' +
        '                                                                                            <option value="20:03">20:03</option>' +
        '                                                                                            <option value="20:04">20:04</option>' +
        '                                                                                            <option value="20:05">20:05</option>' +
        '                                                                                            <option value="20:06">20:06</option>' +
        '                                                                                            <option value="20:07">20:07</option>' +
        '                                                                                            <option value="20:08">20:08</option>' +
        '                                                                                            <option value="20:09">20:09</option>' +
        '                                                                                            <option value="20:10">20:10</option>' +
        '                                                                                            <option value="20:11">20:11</option>' +
        '                                                                                            <option value="20:12">20:12</option>' +
        '                                                                                            <option value="20:13">20:13</option>' +
        '                                                                                            <option value="20:14">20:14</option>' +
        '                                                                                            <option value="20:15">20:15</option>' +
        '                                                                                            <option value="20:16">20:16</option>' +
        '                                                                                            <option value="20:17">20:17</option>' +
        '                                                                                            <option value="20:18">20:18</option>' +
        '                                                                                            <option value="20:19">20:19</option>' +
        '                                                                                            <option value="20:20">20:20</option>' +
        '                                                                                            <option value="20:21">20:21</option>' +
        '                                                                                            <option value="20:22">20:22</option>' +
        '                                                                                            <option value="20:23">20:23</option>' +
        '                                                                                            <option value="20:24">20:24</option>' +
        '                                                                                            <option value="20:25">20:25</option>' +
        '                                                                                            <option value="20:26">20:26</option>' +
        '                                                                                            <option value="20:27">20:27</option>' +
        '                                                                                            <option value="20:28">20:28</option>' +
        '                                                                                            <option value="20:29">20:29</option>' +
        '                                                                                            <option value="20:30">20:30</option>' +
        '                                                                                            <option value="20:31">20:31</option>' +
        '                                                                                            <option value="20:32">20:32</option>' +
        '                                                                                            <option value="20:33">20:33</option>' +
        '                                                                                            <option value="20:34">20:34</option>' +
        '                                                                                            <option value="20:35">20:35</option>' +
        '                                                                                            <option value="20:36">20:36</option>' +
        '                                                                                            <option value="20:37">20:37</option>' +
        '                                                                                            <option value="20:38">20:38</option>' +
        '                                                                                            <option value="20:39">20:39</option>' +
        '                                                                                            <option value="20:40">20:40</option>' +
        '                                                                                            <option value="20:41">20:41</option>' +
        '                                                                                            <option value="20:42">20:42</option>' +
        '                                                                                            <option value="20:43">20:43</option>' +
        '                                                                                            <option value="20:44">20:44</option>' +
        '                                                                                            <option value="20:45">20:45</option>' +
        '                                                                                            <option value="20:46">20:46</option>' +
        '                                                                                            <option value="20:47">20:47</option>' +
        '                                                                                            <option value="20:48">20:48</option>' +
        '                                                                                            <option value="20:49">20:49</option>' +
        '                                                                                            <option value="20:50">20:50</option>' +
        '                                                                                            <option value="20:51">20:51</option>' +
        '                                                                                            <option value="20:52">20:52</option>' +
        '                                                                                            <option value="20:53">20:53</option>' +
        '                                                                                            <option value="20:54">20:54</option>' +
        '                                                                                            <option value="20:55">20:55</option>' +
        '                                                                                            <option value="20:56">20:56</option>' +
        '                                                                                            <option value="20:57">20:57</option>' +
        '                                                                                            <option value="20:58">20:58</option>' +
        '                                                                                            <option value="20:59">20:59</option>' +
        '                                                                                            <option value="21:00">21:00</option>' +
        '                                                                                            <option value="21:01">21:01</option>' +
        '                                                                                            <option value="21:02">21:02</option>' +
        '                                                                                            <option value="21:03">21:03</option>' +
        '                                                                                            <option value="21:04">21:04</option>' +
        '                                                                                            <option value="21:05">21:05</option>' +
        '                                                                                            <option value="21:06">21:06</option>' +
        '                                                                                            <option value="21:07">21:07</option>' +
        '                                                                                            <option value="21:08">21:08</option>' +
        '                                                                                            <option value="21:09">21:09</option>' +
        '                                                                                            <option value="21:10">21:10</option>' +
        '                                                                                            <option value="21:11">21:11</option>' +
        '                                                                                            <option value="21:12">21:12</option>' +
        '                                                                                            <option value="21:13">21:13</option>' +
        '                                                                                            <option value="21:14">21:14</option>' +
        '                                                                                            <option value="21:15">21:15</option>' +
        '                                                                                            <option value="21:16">21:16</option>' +
        '                                                                                            <option value="21:17">21:17</option>' +
        '                                                                                            <option value="21:18">21:18</option>' +
        '                                                                                            <option value="21:19">21:19</option>' +
        '                                                                                            <option value="21:20">21:20</option>' +
        '                                                                                            <option value="21:21">21:21</option>' +
        '                                                                                            <option value="21:22">21:22</option>' +
        '                                                                                            <option value="21:23">21:23</option>' +
        '                                                                                            <option value="21:24">21:24</option>' +
        '                                                                                            <option value="21:25">21:25</option>' +
        '                                                                                            <option value="21:26">21:26</option>' +
        '                                                                                            <option value="21:27">21:27</option>' +
        '                                                                                            <option value="21:28">21:28</option>' +
        '                                                                                            <option value="21:29">21:29</option>' +
        '                                                                                            <option value="21:30">21:30</option>' +
        '                                                                                            <option value="21:31">21:31</option>' +
        '                                                                                            <option value="21:32">21:32</option>' +
        '                                                                                            <option value="21:33">21:33</option>' +
        '                                                                                            <option value="21:34">21:34</option>' +
        '                                                                                            <option value="21:35">21:35</option>' +
        '                                                                                            <option value="21:36">21:36</option>' +
        '                                                                                            <option value="21:37">21:37</option>' +
        '                                                                                            <option value="21:38">21:38</option>' +
        '                                                                                            <option value="21:39">21:39</option>' +
        '                                                                                            <option value="21:40">21:40</option>' +
        '                                                                                            <option value="21:41">21:41</option>' +
        '                                                                                            <option value="21:42">21:42</option>' +
        '                                                                                            <option value="21:43">21:43</option>' +
        '                                                                                            <option value="21:44">21:44</option>' +
        '                                                                                            <option value="21:45">21:45</option>' +
        '                                                                                            <option value="21:46">21:46</option>' +
        '                                                                                            <option value="21:47">21:47</option>' +
        '                                                                                            <option value="21:48">21:48</option>' +
        '                                                                                            <option value="21:49">21:49</option>' +
        '                                                                                            <option value="21:50">21:50</option>' +
        '                                                                                            <option value="21:51">21:51</option>' +
        '                                                                                            <option value="21:52">21:52</option>' +
        '                                                                                            <option value="21:53">21:53</option>' +
        '                                                                                            <option value="21:54">21:54</option>' +
        '                                                                                            <option value="21:55">21:55</option>' +
        '                                                                                            <option value="21:56">21:56</option>' +
        '                                                                                            <option value="21:57">21:57</option>' +
        '                                                                                            <option value="21:58">21:58</option>' +
        '                                                                                            <option value="21:59">21:59</option>' +
        '                                                                                            <option value="22:00">22:00</option>' +
        '                                                                                            <option value="22:01">22:01</option>' +
        '                                                                                            <option value="22:02">22:02</option>' +
        '                                                                                            <option value="22:03">22:03</option>' +
        '                                                                                            <option value="22:04">22:04</option>' +
        '                                                                                            <option value="22:05">22:05</option>' +
        '                                                                                            <option value="22:06">22:06</option>' +
        '                                                                                            <option value="22:07">22:07</option>' +
        '                                                                                            <option value="22:08">22:08</option>' +
        '                                                                                            <option value="22:09">22:09</option>' +
        '                                                                                            <option value="22:10">22:10</option>' +
        '                                                                                            <option value="22:11">22:11</option>' +
        '                                                                                            <option value="22:12">22:12</option>' +
        '                                                                                            <option value="22:13">22:13</option>' +
        '                                                                                            <option value="22:14">22:14</option>' +
        '                                                                                            <option value="22:15">22:15</option>' +
        '                                                                                            <option value="22:16">22:16</option>' +
        '                                                                                            <option value="22:17">22:17</option>' +
        '                                                                                            <option value="22:18">22:18</option>' +
        '                                                                                            <option value="22:19">22:19</option>' +
        '                                                                                            <option value="22:20">22:20</option>' +
        '                                                                                            <option value="22:21">22:21</option>' +
        '                                                                                            <option value="22:22">22:22</option>' +
        '                                                                                            <option value="22:23">22:23</option>' +
        '                                                                                            <option value="22:24">22:24</option>' +
        '                                                                                            <option value="22:25">22:25</option>' +
        '                                                                                            <option value="22:26">22:26</option>' +
        '                                                                                            <option value="22:27">22:27</option>' +
        '                                                                                            <option value="22:28">22:28</option>' +
        '                                                                                            <option value="22:29">22:29</option>' +
        '                                                                                            <option value="22:30">22:30</option>' +
        '                                                                                            <option value="22:31">22:31</option>' +
        '                                                                                            <option value="22:32">22:32</option>' +
        '                                                                                            <option value="22:33">22:33</option>' +
        '                                                                                            <option value="22:34">22:34</option>' +
        '                                                                                            <option value="22:35">22:35</option>' +
        '                                                                                            <option value="22:36">22:36</option>' +
        '                                                                                            <option value="22:37">22:37</option>' +
        '                                                                                            <option value="22:38">22:38</option>' +
        '                                                                                            <option value="22:39">22:39</option>' +
        '                                                                                            <option value="22:40">22:40</option>' +
        '                                                                                            <option value="22:41">22:41</option>' +
        '                                                                                            <option value="22:42">22:42</option>' +
        '                                                                                            <option value="22:43">22:43</option>' +
        '                                                                                            <option value="22:44">22:44</option>' +
        '                                                                                            <option value="22:45">22:45</option>' +
        '                                                                                            <option value="22:46">22:46</option>' +
        '                                                                                            <option value="22:47">22:47</option>' +
        '                                                                                            <option value="22:48">22:48</option>' +
        '                                                                                            <option value="22:49">22:49</option>' +
        '                                                                                            <option value="22:50">22:50</option>' +
        '                                                                                            <option value="22:51">22:51</option>' +
        '                                                                                            <option value="22:52">22:52</option>' +
        '                                                                                            <option value="22:53">22:53</option>' +
        '                                                                                            <option value="22:54">22:54</option>' +
        '                                                                                            <option value="22:55">22:55</option>' +
        '                                                                                            <option value="22:56">22:56</option>' +
        '                                                                                            <option value="22:57">22:57</option>' +
        '                                                                                            <option value="22:58">22:58</option>' +
        '                                                                                            <option value="22:59">22:59</option>' +
        '                                                                                            <option value="23:00">23:00</option>' +
        '                                                                                            <option value="23:01">23:01</option>' +
        '                                                                                            <option value="23:02">23:02</option>' +
        '                                                                                            <option value="23:03">23:03</option>' +
        '                                                                                            <option value="23:04">23:04</option>' +
        '                                                                                            <option value="23:05">23:05</option>' +
        '                                                                                            <option value="23:06">23:06</option>' +
        '                                                                                            <option value="23:07">23:07</option>' +
        '                                                                                            <option value="23:08">23:08</option>' +
        '                                                                                            <option value="23:09">23:09</option>' +
        '                                                                                            <option value="23:10">23:10</option>' +
        '                                                                                            <option value="23:11">23:11</option>' +
        '                                                                                            <option value="23:12">23:12</option>' +
        '                                                                                            <option value="23:13">23:13</option>' +
        '                                                                                            <option value="23:14">23:14</option>' +
        '                                                                                            <option value="23:15">23:15</option>' +
        '                                                                                            <option value="23:16">23:16</option>' +
        '                                                                                            <option value="23:17">23:17</option>' +
        '                                                                                            <option value="23:18">23:18</option>' +
        '                                                                                            <option value="23:19">23:19</option>' +
        '                                                                                            <option value="23:20">23:20</option>' +
        '                                                                                            <option value="23:21">23:21</option>' +
        '                                                                                            <option value="23:22">23:22</option>' +
        '                                                                                            <option value="23:23">23:23</option>' +
        '                                                                                            <option value="23:24">23:24</option>' +
        '                                                                                            <option value="23:25">23:25</option>' +
        '                                                                                            <option value="23:26">23:26</option>' +
        '                                                                                            <option value="23:27">23:27</option>' +
        '                                                                                            <option value="23:28">23:28</option>' +
        '                                                                                            <option value="23:29">23:29</option>' +
        '                                                                                            <option value="23:30">23:30</option>' +
        '                                                                                            <option value="23:31">23:31</option>' +
        '                                                                                            <option value="23:32">23:32</option>' +
        '                                                                                            <option value="23:33">23:33</option>' +
        '                                                                                            <option value="23:34">23:34</option>' +
        '                                                                                            <option value="23:35">23:35</option>' +
        '                                                                                            <option value="23:36">23:36</option>' +
        '                                                                                            <option value="23:37">23:37</option>' +
        '                                                                                            <option value="23:38">23:38</option>' +
        '                                                                                            <option value="23:39">23:39</option>' +
        '                                                                                            <option value="23:40">23:40</option>' +
        '                                                                                            <option value="23:41">23:41</option>' +
        '                                                                                            <option value="23:42">23:42</option>' +
        '                                                                                            <option value="23:43">23:43</option>' +
        '                                                                                            <option value="23:44">23:44</option>' +
        '                                                                                            <option value="23:45">23:45</option>' +
        '                                                                                            <option value="23:46">23:46</option>' +
        '                                                                                            <option value="23:47">23:47</option>' +
        '                                                                                            <option value="23:48">23:48</option>' +
        '                                                                                            <option value="23:49">23:49</option>' +
        '                                                                                            <option value="23:50">23:50</option>' +
        '                                                                                            <option value="23:51">23:51</option>' +
        '                                                                                            <option value="23:52">23:52</option>' +
        '                                                                                            <option value="23:53">23:53</option>' +
        '                                                                                            <option value="23:54">23:54</option>' +
        '                                                                                            <option value="23:55">23:55</option>' +
        '                                                                                            <option value="23:56">23:56</option>' +
        '                                                                                            <option value="23:57">23:57</option>' +
        '                                                                                            <option value="23:58">23:58</option>' +
        '                                                                                            <option value="23:59">23:59</option>' +
        '                                                                                    </select>' +
        '' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label>Arrival Time</label>' +
        '                                        <select class="form-control" name="onward[' + triptype + '][' + countval + '][arrival_time]">' +
        '                                            <option value="" selected="">Arrival Time</option>' +
        '                                                                                            <option value="00:00">00:00</option>' +
        '                                                                                            <option value="00:01">00:01</option>' +
        '                                                                                            <option value="00:02">00:02</option>' +
        '                                                                                            <option value="00:03">00:03</option>' +
        '                                                                                            <option value="00:04">00:04</option>' +
        '                                                                                            <option value="00:05">00:05</option>' +
        '                                                                                            <option value="00:06">00:06</option>' +
        '                                                                                            <option value="00:07">00:07</option>' +
        '                                                                                            <option value="00:08">00:08</option>' +
        '                                                                                            <option value="00:09">00:09</option>' +
        '                                                                                            <option value="00:10">00:10</option>' +
        '                                                                                            <option value="00:11">00:11</option>' +
        '                                                                                            <option value="00:12">00:12</option>' +
        '                                                                                            <option value="00:13">00:13</option>' +
        '                                                                                            <option value="00:14">00:14</option>' +
        '                                                                                            <option value="00:15">00:15</option>' +
        '                                                                                            <option value="00:16">00:16</option>' +
        '                                                                                            <option value="00:17">00:17</option>' +
        '                                                                                            <option value="00:18">00:18</option>' +
        '                                                                                            <option value="00:19">00:19</option>' +
        '                                                                                            <option value="00:20">00:20</option>' +
        '                                                                                            <option value="00:21">00:21</option>' +
        '                                                                                            <option value="00:22">00:22</option>' +
        '                                                                                            <option value="00:23">00:23</option>' +
        '                                                                                            <option value="00:24">00:24</option>' +
        '                                                                                            <option value="00:25">00:25</option>' +
        '                                                                                            <option value="00:26">00:26</option>' +
        '                                                                                            <option value="00:27">00:27</option>' +
        '                                                                                            <option value="00:28">00:28</option>' +
        '                                                                                            <option value="00:29">00:29</option>' +
        '                                                                                            <option value="00:30">00:30</option>' +
        '                                                                                            <option value="00:31">00:31</option>' +
        '                                                                                            <option value="00:32">00:32</option>' +
        '                                                                                            <option value="00:33">00:33</option>' +
        '                                                                                            <option value="00:34">00:34</option>' +
        '                                                                                            <option value="00:35">00:35</option>' +
        '                                                                                            <option value="00:36">00:36</option>' +
        '                                                                                            <option value="00:37">00:37</option>' +
        '                                                                                            <option value="00:38">00:38</option>' +
        '                                                                                            <option value="00:39">00:39</option>' +
        '                                                                                            <option value="00:40">00:40</option>' +
        '                                                                                            <option value="00:41">00:41</option>' +
        '                                                                                            <option value="00:42">00:42</option>' +
        '                                                                                            <option value="00:43">00:43</option>' +
        '                                                                                            <option value="00:44">00:44</option>' +
        '                                                                                            <option value="00:45">00:45</option>' +
        '                                                                                            <option value="00:46">00:46</option>' +
        '                                                                                            <option value="00:47">00:47</option>' +
        '                                                                                            <option value="00:48">00:48</option>' +
        '                                                                                            <option value="00:49">00:49</option>' +
        '                                                                                            <option value="00:50">00:50</option>' +
        '                                                                                            <option value="00:51">00:51</option>' +
        '                                                                                            <option value="00:52">00:52</option>' +
        '                                                                                            <option value="00:53">00:53</option>' +
        '                                                                                            <option value="00:54">00:54</option>' +
        '                                                                                            <option value="00:55">00:55</option>' +
        '                                                                                            <option value="00:56">00:56</option>' +
        '                                                                                            <option value="00:57">00:57</option>' +
        '                                                                                            <option value="00:58">00:58</option>' +
        '                                                                                            <option value="00:59">00:59</option>' +
        '                                                                                            <option value="01:00">01:00</option>' +
        '                                                                                            <option value="01:01">01:01</option>' +
        '                                                                                            <option value="01:02">01:02</option>' +
        '                                                                                            <option value="01:03">01:03</option>' +
        '                                                                                            <option value="01:04">01:04</option>' +
        '                                                                                            <option value="01:05">01:05</option>' +
        '                                                                                            <option value="01:06">01:06</option>' +
        '                                                                                            <option value="01:07">01:07</option>' +
        '                                                                                            <option value="01:08">01:08</option>' +
        '                                                                                            <option value="01:09">01:09</option>' +
        '                                                                                            <option value="01:10">01:10</option>' +
        '                                                                                            <option value="01:11">01:11</option>' +
        '                                                                                            <option value="01:12">01:12</option>' +
        '                                                                                            <option value="01:13">01:13</option>' +
        '                                                                                            <option value="01:14">01:14</option>' +
        '                                                                                            <option value="01:15">01:15</option>' +
        '                                                                                            <option value="01:16">01:16</option>' +
        '                                                                                            <option value="01:17">01:17</option>' +
        '                                                                                            <option value="01:18">01:18</option>' +
        '                                                                                            <option value="01:19">01:19</option>' +
        '                                                                                            <option value="01:20">01:20</option>' +
        '                                                                                            <option value="01:21">01:21</option>' +
        '                                                                                            <option value="01:22">01:22</option>' +
        '                                                                                            <option value="01:23">01:23</option>' +
        '                                                                                            <option value="01:24">01:24</option>' +
        '                                                                                            <option value="01:25">01:25</option>' +
        '                                                                                            <option value="01:26">01:26</option>' +
        '                                                                                            <option value="01:27">01:27</option>' +
        '                                                                                            <option value="01:28">01:28</option>' +
        '                                                                                            <option value="01:29">01:29</option>' +
        '                                                                                            <option value="01:30">01:30</option>' +
        '                                                                                            <option value="01:31">01:31</option>' +
        '                                                                                            <option value="01:32">01:32</option>' +
        '                                                                                            <option value="01:33">01:33</option>' +
        '                                                                                            <option value="01:34">01:34</option>' +
        '                                                                                            <option value="01:35">01:35</option>' +
        '                                                                                            <option value="01:36">01:36</option>' +
        '                                                                                            <option value="01:37">01:37</option>' +
        '                                                                                            <option value="01:38">01:38</option>' +
        '                                                                                            <option value="01:39">01:39</option>' +
        '                                                                                            <option value="01:40">01:40</option>' +
        '                                                                                            <option value="01:41">01:41</option>' +
        '                                                                                            <option value="01:42">01:42</option>' +
        '                                                                                            <option value="01:43">01:43</option>' +
        '                                                                                            <option value="01:44">01:44</option>' +
        '                                                                                            <option value="01:45">01:45</option>' +
        '                                                                                            <option value="01:46">01:46</option>' +
        '                                                                                            <option value="01:47">01:47</option>' +
        '                                                                                            <option value="01:48">01:48</option>' +
        '                                                                                            <option value="01:49">01:49</option>' +
        '                                                                                            <option value="01:50">01:50</option>' +
        '                                                                                            <option value="01:51">01:51</option>' +
        '                                                                                            <option value="01:52">01:52</option>' +
        '                                                                                            <option value="01:53">01:53</option>' +
        '                                                                                            <option value="01:54">01:54</option>' +
        '                                                                                            <option value="01:55">01:55</option>' +
        '                                                                                            <option value="01:56">01:56</option>' +
        '                                                                                            <option value="01:57">01:57</option>' +
        '                                                                                            <option value="01:58">01:58</option>' +
        '                                                                                            <option value="01:59">01:59</option>' +
        '                                                                                            <option value="02:00">02:00</option>' +
        '                                                                                            <option value="02:01">02:01</option>' +
        '                                                                                            <option value="02:02">02:02</option>' +
        '                                                                                            <option value="02:03">02:03</option>' +
        '                                                                                            <option value="02:04">02:04</option>' +
        '                                                                                            <option value="02:05">02:05</option>' +
        '                                                                                            <option value="02:06">02:06</option>' +
        '                                                                                            <option value="02:07">02:07</option>' +
        '                                                                                            <option value="02:08">02:08</option>' +
        '                                                                                            <option value="02:09">02:09</option>' +
        '                                                                                            <option value="02:10">02:10</option>' +
        '                                                                                            <option value="02:11">02:11</option>' +
        '                                                                                            <option value="02:12">02:12</option>' +
        '                                                                                            <option value="02:13">02:13</option>' +
        '                                                                                            <option value="02:14">02:14</option>' +
        '                                                                                            <option value="02:15">02:15</option>' +
        '                                                                                            <option value="02:16">02:16</option>' +
        '                                                                                            <option value="02:17">02:17</option>' +
        '                                                                                            <option value="02:18">02:18</option>' +
        '                                                                                            <option value="02:19">02:19</option>' +
        '                                                                                            <option value="02:20">02:20</option>' +
        '                                                                                            <option value="02:21">02:21</option>' +
        '                                                                                            <option value="02:22">02:22</option>' +
        '                                                                                            <option value="02:23">02:23</option>' +
        '                                                                                            <option value="02:24">02:24</option>' +
        '                                                                                            <option value="02:25">02:25</option>' +
        '                                                                                            <option value="02:26">02:26</option>' +
        '                                                                                            <option value="02:27">02:27</option>' +
        '                                                                                            <option value="02:28">02:28</option>' +
        '                                                                                            <option value="02:29">02:29</option>' +
        '                                                                                            <option value="02:30">02:30</option>' +
        '                                                                                            <option value="02:31">02:31</option>' +
        '                                                                                            <option value="02:32">02:32</option>' +
        '                                                                                            <option value="02:33">02:33</option>' +
        '                                                                                            <option value="02:34">02:34</option>' +
        '                                                                                            <option value="02:35">02:35</option>' +
        '                                                                                            <option value="02:36">02:36</option>' +
        '                                                                                            <option value="02:37">02:37</option>' +
        '                                                                                            <option value="02:38">02:38</option>' +
        '                                                                                            <option value="02:39">02:39</option>' +
        '                                                                                            <option value="02:40">02:40</option>' +
        '                                                                                            <option value="02:41">02:41</option>' +
        '                                                                                            <option value="02:42">02:42</option>' +
        '                                                                                            <option value="02:43">02:43</option>' +
        '                                                                                            <option value="02:44">02:44</option>' +
        '                                                                                            <option value="02:45">02:45</option>' +
        '                                                                                            <option value="02:46">02:46</option>' +
        '                                                                                            <option value="02:47">02:47</option>' +
        '                                                                                            <option value="02:48">02:48</option>' +
        '                                                                                            <option value="02:49">02:49</option>' +
        '                                                                                            <option value="02:50">02:50</option>' +
        '                                                                                            <option value="02:51">02:51</option>' +
        '                                                                                            <option value="02:52">02:52</option>' +
        '                                                                                            <option value="02:53">02:53</option>' +
        '                                                                                            <option value="02:54">02:54</option>' +
        '                                                                                            <option value="02:55">02:55</option>' +
        '                                                                                            <option value="02:56">02:56</option>' +
        '                                                                                            <option value="02:57">02:57</option>' +
        '                                                                                            <option value="02:58">02:58</option>' +
        '                                                                                            <option value="02:59">02:59</option>' +
        '                                                                                            <option value="03:00">03:00</option>' +
        '                                                                                            <option value="03:01">03:01</option>' +
        '                                                                                            <option value="03:02">03:02</option>' +
        '                                                                                            <option value="03:03">03:03</option>' +
        '                                                                                            <option value="03:04">03:04</option>' +
        '                                                                                            <option value="03:05">03:05</option>' +
        '                                                                                            <option value="03:06">03:06</option>' +
        '                                                                                            <option value="03:07">03:07</option>' +
        '                                                                                            <option value="03:08">03:08</option>' +
        '                                                                                            <option value="03:09">03:09</option>' +
        '                                                                                            <option value="03:10">03:10</option>' +
        '                                                                                            <option value="03:11">03:11</option>' +
        '                                                                                            <option value="03:12">03:12</option>' +
        '                                                                                            <option value="03:13">03:13</option>' +
        '                                                                                            <option value="03:14">03:14</option>' +
        '                                                                                            <option value="03:15">03:15</option>' +
        '                                                                                            <option value="03:16">03:16</option>' +
        '                                                                                            <option value="03:17">03:17</option>' +
        '                                                                                            <option value="03:18">03:18</option>' +
        '                                                                                            <option value="03:19">03:19</option>' +
        '                                                                                            <option value="03:20">03:20</option>' +
        '                                                                                            <option value="03:21">03:21</option>' +
        '                                                                                            <option value="03:22">03:22</option>' +
        '                                                                                            <option value="03:23">03:23</option>' +
        '                                                                                            <option value="03:24">03:24</option>' +
        '                                                                                            <option value="03:25">03:25</option>' +
        '                                                                                            <option value="03:26">03:26</option>' +
        '                                                                                            <option value="03:27">03:27</option>' +
        '                                                                                            <option value="03:28">03:28</option>' +
        '                                                                                            <option value="03:29">03:29</option>' +
        '                                                                                            <option value="03:30">03:30</option>' +
        '                                                                                            <option value="03:31">03:31</option>' +
        '                                                                                            <option value="03:32">03:32</option>' +
        '                                                                                            <option value="03:33">03:33</option>' +
        '                                                                                            <option value="03:34">03:34</option>' +
        '                                                                                            <option value="03:35">03:35</option>' +
        '                                                                                            <option value="03:36">03:36</option>' +
        '                                                                                            <option value="03:37">03:37</option>' +
        '                                                                                            <option value="03:38">03:38</option>' +
        '                                                                                            <option value="03:39">03:39</option>' +
        '                                                                                            <option value="03:40">03:40</option>' +
        '                                                                                            <option value="03:41">03:41</option>' +
        '                                                                                            <option value="03:42">03:42</option>' +
        '                                                                                            <option value="03:43">03:43</option>' +
        '                                                                                            <option value="03:44">03:44</option>' +
        '                                                                                            <option value="03:45">03:45</option>' +
        '                                                                                            <option value="03:46">03:46</option>' +
        '                                                                                            <option value="03:47">03:47</option>' +
        '                                                                                            <option value="03:48">03:48</option>' +
        '                                                                                            <option value="03:49">03:49</option>' +
        '                                                                                            <option value="03:50">03:50</option>' +
        '                                                                                            <option value="03:51">03:51</option>' +
        '                                                                                            <option value="03:52">03:52</option>' +
        '                                                                                            <option value="03:53">03:53</option>' +
        '                                                                                            <option value="03:54">03:54</option>' +
        '                                                                                            <option value="03:55">03:55</option>' +
        '                                                                                            <option value="03:56">03:56</option>' +
        '                                                                                            <option value="03:57">03:57</option>' +
        '                                                                                            <option value="03:58">03:58</option>' +
        '                                                                                            <option value="03:59">03:59</option>' +
        '                                                                                            <option value="04:00">04:00</option>' +
        '                                                                                            <option value="04:01">04:01</option>' +
        '                                                                                            <option value="04:02">04:02</option>' +
        '                                                                                            <option value="04:03">04:03</option>' +
        '                                                                                            <option value="04:04">04:04</option>' +
        '                                                                                            <option value="04:05">04:05</option>' +
        '                                                                                            <option value="04:06">04:06</option>' +
        '                                                                                            <option value="04:07">04:07</option>' +
        '                                                                                            <option value="04:08">04:08</option>' +
        '                                                                                            <option value="04:09">04:09</option>' +
        '                                                                                            <option value="04:10">04:10</option>' +
        '                                                                                            <option value="04:11">04:11</option>' +
        '                                                                                            <option value="04:12">04:12</option>' +
        '                                                                                            <option value="04:13">04:13</option>' +
        '                                                                                            <option value="04:14">04:14</option>' +
        '                                                                                            <option value="04:15">04:15</option>' +
        '                                                                                            <option value="04:16">04:16</option>' +
        '                                                                                            <option value="04:17">04:17</option>' +
        '                                                                                            <option value="04:18">04:18</option>' +
        '                                                                                            <option value="04:19">04:19</option>' +
        '                                                                                            <option value="04:20">04:20</option>' +
        '                                                                                            <option value="04:21">04:21</option>' +
        '                                                                                            <option value="04:22">04:22</option>' +
        '                                                                                            <option value="04:23">04:23</option>' +
        '                                                                                            <option value="04:24">04:24</option>' +
        '                                                                                            <option value="04:25">04:25</option>' +
        '                                                                                            <option value="04:26">04:26</option>' +
        '                                                                                            <option value="04:27">04:27</option>' +
        '                                                                                            <option value="04:28">04:28</option>' +
        '                                                                                            <option value="04:29">04:29</option>' +
        '                                                                                            <option value="04:30">04:30</option>' +
        '                                                                                            <option value="04:31">04:31</option>' +
        '                                                                                            <option value="04:32">04:32</option>' +
        '                                                                                            <option value="04:33">04:33</option>' +
        '                                                                                            <option value="04:34">04:34</option>' +
        '                                                                                            <option value="04:35">04:35</option>' +
        '                                                                                            <option value="04:36">04:36</option>' +
        '                                                                                            <option value="04:37">04:37</option>' +
        '                                                                                            <option value="04:38">04:38</option>' +
        '                                                                                            <option value="04:39">04:39</option>' +
        '                                                                                            <option value="04:40">04:40</option>' +
        '                                                                                            <option value="04:41">04:41</option>' +
        '                                                                                            <option value="04:42">04:42</option>' +
        '                                                                                            <option value="04:43">04:43</option>' +
        '                                                                                            <option value="04:44">04:44</option>' +
        '                                                                                            <option value="04:45">04:45</option>' +
        '                                                                                            <option value="04:46">04:46</option>' +
        '                                                                                            <option value="04:47">04:47</option>' +
        '                                                                                            <option value="04:48">04:48</option>' +
        '                                                                                            <option value="04:49">04:49</option>' +
        '                                                                                            <option value="04:50">04:50</option>' +
        '                                                                                            <option value="04:51">04:51</option>' +
        '                                                                                            <option value="04:52">04:52</option>' +
        '                                                                                            <option value="04:53">04:53</option>' +
        '                                                                                            <option value="04:54">04:54</option>' +
        '                                                                                            <option value="04:55">04:55</option>' +
        '                                                                                            <option value="04:56">04:56</option>' +
        '                                                                                            <option value="04:57">04:57</option>' +
        '                                                                                            <option value="04:58">04:58</option>' +
        '                                                                                            <option value="04:59">04:59</option>' +
        '                                                                                            <option value="05:00">05:00</option>' +
        '                                                                                            <option value="05:01">05:01</option>' +
        '                                                                                            <option value="05:02">05:02</option>' +
        '                                                                                            <option value="05:03">05:03</option>' +
        '                                                                                            <option value="05:04">05:04</option>' +
        '                                                                                            <option value="05:05">05:05</option>' +
        '                                                                                            <option value="05:06">05:06</option>' +
        '                                                                                            <option value="05:07">05:07</option>' +
        '                                                                                            <option value="05:08">05:08</option>' +
        '                                                                                            <option value="05:09">05:09</option>' +
        '                                                                                            <option value="05:10">05:10</option>' +
        '                                                                                            <option value="05:11">05:11</option>' +
        '                                                                                            <option value="05:12">05:12</option>' +
        '                                                                                            <option value="05:13">05:13</option>' +
        '                                                                                            <option value="05:14">05:14</option>' +
        '                                                                                            <option value="05:15">05:15</option>' +
        '                                                                                            <option value="05:16">05:16</option>' +
        '                                                                                            <option value="05:17">05:17</option>' +
        '                                                                                            <option value="05:18">05:18</option>' +
        '                                                                                            <option value="05:19">05:19</option>' +
        '                                                                                            <option value="05:20">05:20</option>' +
        '                                                                                            <option value="05:21">05:21</option>' +
        '                                                                                            <option value="05:22">05:22</option>' +
        '                                                                                            <option value="05:23">05:23</option>' +
        '                                                                                            <option value="05:24">05:24</option>' +
        '                                                                                            <option value="05:25">05:25</option>' +
        '                                                                                            <option value="05:26">05:26</option>' +
        '                                                                                            <option value="05:27">05:27</option>' +
        '                                                                                            <option value="05:28">05:28</option>' +
        '                                                                                            <option value="05:29">05:29</option>' +
        '                                                                                            <option value="05:30">05:30</option>' +
        '                                                                                            <option value="05:31">05:31</option>' +
        '                                                                                            <option value="05:32">05:32</option>' +
        '                                                                                            <option value="05:33">05:33</option>' +
        '                                                                                            <option value="05:34">05:34</option>' +
        '                                                                                            <option value="05:35">05:35</option>' +
        '                                                                                            <option value="05:36">05:36</option>' +
        '                                                                                            <option value="05:37">05:37</option>' +
        '                                                                                            <option value="05:38">05:38</option>' +
        '                                                                                            <option value="05:39">05:39</option>' +
        '                                                                                            <option value="05:40">05:40</option>' +
        '                                                                                            <option value="05:41">05:41</option>' +
        '                                                                                            <option value="05:42">05:42</option>' +
        '                                                                                            <option value="05:43">05:43</option>' +
        '                                                                                            <option value="05:44">05:44</option>' +
        '                                                                                            <option value="05:45">05:45</option>' +
        '                                                                                            <option value="05:46">05:46</option>' +
        '                                                                                            <option value="05:47">05:47</option>' +
        '                                                                                            <option value="05:48">05:48</option>' +
        '                                                                                            <option value="05:49">05:49</option>' +
        '                                                                                            <option value="05:50">05:50</option>' +
        '                                                                                            <option value="05:51">05:51</option>' +
        '                                                                                            <option value="05:52">05:52</option>' +
        '                                                                                            <option value="05:53">05:53</option>' +
        '                                                                                            <option value="05:54">05:54</option>' +
        '                                                                                            <option value="05:55">05:55</option>' +
        '                                                                                            <option value="05:56">05:56</option>' +
        '                                                                                            <option value="05:57">05:57</option>' +
        '                                                                                            <option value="05:58">05:58</option>' +
        '                                                                                            <option value="05:59">05:59</option>' +
        '                                                                                            <option value="06:00">06:00</option>' +
        '                                                                                            <option value="06:01">06:01</option>' +
        '                                                                                            <option value="06:02">06:02</option>' +
        '                                                                                            <option value="06:03">06:03</option>' +
        '                                                                                            <option value="06:04">06:04</option>' +
        '                                                                                            <option value="06:05">06:05</option>' +
        '                                                                                            <option value="06:06">06:06</option>' +
        '                                                                                            <option value="06:07">06:07</option>' +
        '                                                                                            <option value="06:08">06:08</option>' +
        '                                                                                            <option value="06:09">06:09</option>' +
        '                                                                                            <option value="06:10">06:10</option>' +
        '                                                                                            <option value="06:11">06:11</option>' +
        '                                                                                            <option value="06:12">06:12</option>' +
        '                                                                                            <option value="06:13">06:13</option>' +
        '                                                                                            <option value="06:14">06:14</option>' +
        '                                                                                            <option value="06:15">06:15</option>' +
        '                                                                                            <option value="06:16">06:16</option>' +
        '                                                                                            <option value="06:17">06:17</option>' +
        '                                                                                            <option value="06:18">06:18</option>' +
        '                                                                                            <option value="06:19">06:19</option>' +
        '                                                                                            <option value="06:20">06:20</option>' +
        '                                                                                            <option value="06:21">06:21</option>' +
        '                                                                                            <option value="06:22">06:22</option>' +
        '                                                                                            <option value="06:23">06:23</option>' +
        '                                                                                            <option value="06:24">06:24</option>' +
        '                                                                                            <option value="06:25">06:25</option>' +
        '                                                                                            <option value="06:26">06:26</option>' +
        '                                                                                            <option value="06:27">06:27</option>' +
        '                                                                                            <option value="06:28">06:28</option>' +
        '                                                                                            <option value="06:29">06:29</option>' +
        '                                                                                            <option value="06:30">06:30</option>' +
        '                                                                                            <option value="06:31">06:31</option>' +
        '                                                                                            <option value="06:32">06:32</option>' +
        '                                                                                            <option value="06:33">06:33</option>' +
        '                                                                                            <option value="06:34">06:34</option>' +
        '                                                                                            <option value="06:35">06:35</option>' +
        '                                                                                            <option value="06:36">06:36</option>' +
        '                                                                                            <option value="06:37">06:37</option>' +
        '                                                                                            <option value="06:38">06:38</option>' +
        '                                                                                            <option value="06:39">06:39</option>' +
        '                                                                                            <option value="06:40">06:40</option>' +
        '                                                                                            <option value="06:41">06:41</option>' +
        '                                                                                            <option value="06:42">06:42</option>' +
        '                                                                                            <option value="06:43">06:43</option>' +
        '                                                                                            <option value="06:44">06:44</option>' +
        '                                                                                            <option value="06:45">06:45</option>' +
        '                                                                                            <option value="06:46">06:46</option>' +
        '                                                                                            <option value="06:47">06:47</option>' +
        '                                                                                            <option value="06:48">06:48</option>' +
        '                                                                                            <option value="06:49">06:49</option>' +
        '                                                                                            <option value="06:50">06:50</option>' +
        '                                                                                            <option value="06:51">06:51</option>' +
        '                                                                                            <option value="06:52">06:52</option>' +
        '                                                                                            <option value="06:53">06:53</option>' +
        '                                                                                            <option value="06:54">06:54</option>' +
        '                                                                                            <option value="06:55">06:55</option>' +
        '                                                                                            <option value="06:56">06:56</option>' +
        '                                                                                            <option value="06:57">06:57</option>' +
        '                                                                                            <option value="06:58">06:58</option>' +
        '                                                                                            <option value="06:59">06:59</option>' +
        '                                                                                            <option value="07:00">07:00</option>' +
        '                                                                                            <option value="07:01">07:01</option>' +
        '                                                                                            <option value="07:02">07:02</option>' +
        '                                                                                            <option value="07:03">07:03</option>' +
        '                                                                                            <option value="07:04">07:04</option>' +
        '                                                                                            <option value="07:05">07:05</option>' +
        '                                                                                            <option value="07:06">07:06</option>' +
        '                                                                                            <option value="07:07">07:07</option>' +
        '                                                                                            <option value="07:08">07:08</option>' +
        '                                                                                            <option value="07:09">07:09</option>' +
        '                                                                                            <option value="07:10">07:10</option>' +
        '                                                                                            <option value="07:11">07:11</option>' +
        '                                                                                            <option value="07:12">07:12</option>' +
        '                                                                                            <option value="07:13">07:13</option>' +
        '                                                                                            <option value="07:14">07:14</option>' +
        '                                                                                            <option value="07:15">07:15</option>' +
        '                                                                                            <option value="07:16">07:16</option>' +
        '                                                                                            <option value="07:17">07:17</option>' +
        '                                                                                            <option value="07:18">07:18</option>' +
        '                                                                                            <option value="07:19">07:19</option>' +
        '                                                                                            <option value="07:20">07:20</option>' +
        '                                                                                            <option value="07:21">07:21</option>' +
        '                                                                                            <option value="07:22">07:22</option>' +
        '                                                                                            <option value="07:23">07:23</option>' +
        '                                                                                            <option value="07:24">07:24</option>' +
        '                                                                                            <option value="07:25">07:25</option>' +
        '                                                                                            <option value="07:26">07:26</option>' +
        '                                                                                            <option value="07:27">07:27</option>' +
        '                                                                                            <option value="07:28">07:28</option>' +
        '                                                                                            <option value="07:29">07:29</option>' +
        '                                                                                            <option value="07:30">07:30</option>' +
        '                                                                                            <option value="07:31">07:31</option>' +
        '                                                                                            <option value="07:32">07:32</option>' +
        '                                                                                            <option value="07:33">07:33</option>' +
        '                                                                                            <option value="07:34">07:34</option>' +
        '                                                                                            <option value="07:35">07:35</option>' +
        '                                                                                            <option value="07:36">07:36</option>' +
        '                                                                                            <option value="07:37">07:37</option>' +
        '                                                                                            <option value="07:38">07:38</option>' +
        '                                                                                            <option value="07:39">07:39</option>' +
        '                                                                                            <option value="07:40">07:40</option>' +
        '                                                                                            <option value="07:41">07:41</option>' +
        '                                                                                            <option value="07:42">07:42</option>' +
        '                                                                                            <option value="07:43">07:43</option>' +
        '                                                                                            <option value="07:44">07:44</option>' +
        '                                                                                            <option value="07:45">07:45</option>' +
        '                                                                                            <option value="07:46">07:46</option>' +
        '                                                                                            <option value="07:47">07:47</option>' +
        '                                                                                            <option value="07:48">07:48</option>' +
        '                                                                                            <option value="07:49">07:49</option>' +
        '                                                                                            <option value="07:50">07:50</option>' +
        '                                                                                            <option value="07:51">07:51</option>' +
        '                                                                                            <option value="07:52">07:52</option>' +
        '                                                                                            <option value="07:53">07:53</option>' +
        '                                                                                            <option value="07:54">07:54</option>' +
        '                                                                                            <option value="07:55">07:55</option>' +
        '                                                                                            <option value="07:56">07:56</option>' +
        '                                                                                            <option value="07:57">07:57</option>' +
        '                                                                                            <option value="07:58">07:58</option>' +
        '                                                                                            <option value="07:59">07:59</option>' +
        '                                                                                            <option value="08:00">08:00</option>' +
        '                                                                                            <option value="08:01">08:01</option>' +
        '                                                                                            <option value="08:02">08:02</option>' +
        '                                                                                            <option value="08:03">08:03</option>' +
        '                                                                                            <option value="08:04">08:04</option>' +
        '                                                                                            <option value="08:05">08:05</option>' +
        '                                                                                            <option value="08:06">08:06</option>' +
        '                                                                                            <option value="08:07">08:07</option>' +
        '                                                                                            <option value="08:08">08:08</option>' +
        '                                                                                            <option value="08:09">08:09</option>' +
        '                                                                                            <option value="08:10">08:10</option>' +
        '                                                                                            <option value="08:11">08:11</option>' +
        '                                                                                            <option value="08:12">08:12</option>' +
        '                                                                                            <option value="08:13">08:13</option>' +
        '                                                                                            <option value="08:14">08:14</option>' +
        '                                                                                            <option value="08:15">08:15</option>' +
        '                                                                                            <option value="08:16">08:16</option>' +
        '                                                                                            <option value="08:17">08:17</option>' +
        '                                                                                            <option value="08:18">08:18</option>' +
        '                                                                                            <option value="08:19">08:19</option>' +
        '                                                                                            <option value="08:20">08:20</option>' +
        '                                                                                            <option value="08:21">08:21</option>' +
        '                                                                                            <option value="08:22">08:22</option>' +
        '                                                                                            <option value="08:23">08:23</option>' +
        '                                                                                            <option value="08:24">08:24</option>' +
        '                                                                                            <option value="08:25">08:25</option>' +
        '                                                                                            <option value="08:26">08:26</option>' +
        '                                                                                            <option value="08:27">08:27</option>' +
        '                                                                                            <option value="08:28">08:28</option>' +
        '                                                                                            <option value="08:29">08:29</option>' +
        '                                                                                            <option value="08:30">08:30</option>' +
        '                                                                                            <option value="08:31">08:31</option>' +
        '                                                                                            <option value="08:32">08:32</option>' +
        '                                                                                            <option value="08:33">08:33</option>' +
        '                                                                                            <option value="08:34">08:34</option>' +
        '                                                                                            <option value="08:35">08:35</option>' +
        '                                                                                            <option value="08:36">08:36</option>' +
        '                                                                                            <option value="08:37">08:37</option>' +
        '                                                                                            <option value="08:38">08:38</option>' +
        '                                                                                            <option value="08:39">08:39</option>' +
        '                                                                                            <option value="08:40">08:40</option>' +
        '                                                                                            <option value="08:41">08:41</option>' +
        '                                                                                            <option value="08:42">08:42</option>' +
        '                                                                                            <option value="08:43">08:43</option>' +
        '                                                                                            <option value="08:44">08:44</option>' +
        '                                                                                            <option value="08:45">08:45</option>' +
        '                                                                                            <option value="08:46">08:46</option>' +
        '                                                                                            <option value="08:47">08:47</option>' +
        '                                                                                            <option value="08:48">08:48</option>' +
        '                                                                                            <option value="08:49">08:49</option>' +
        '                                                                                            <option value="08:50">08:50</option>' +
        '                                                                                            <option value="08:51">08:51</option>' +
        '                                                                                            <option value="08:52">08:52</option>' +
        '                                                                                            <option value="08:53">08:53</option>' +
        '                                                                                            <option value="08:54">08:54</option>' +
        '                                                                                            <option value="08:55">08:55</option>' +
        '                                                                                            <option value="08:56">08:56</option>' +
        '                                                                                            <option value="08:57">08:57</option>' +
        '                                                                                            <option value="08:58">08:58</option>' +
        '                                                                                            <option value="08:59">08:59</option>' +
        '                                                                                            <option value="09:00">09:00</option>' +
        '                                                                                            <option value="09:01">09:01</option>' +
        '                                                                                            <option value="09:02">09:02</option>' +
        '                                                                                            <option value="09:03">09:03</option>' +
        '                                                                                            <option value="09:04">09:04</option>' +
        '                                                                                            <option value="09:05">09:05</option>' +
        '                                                                                            <option value="09:06">09:06</option>' +
        '                                                                                            <option value="09:07">09:07</option>' +
        '                                                                                            <option value="09:08">09:08</option>' +
        '                                                                                            <option value="09:09">09:09</option>' +
        '                                                                                            <option value="09:10">09:10</option>' +
        '                                                                                            <option value="09:11">09:11</option>' +
        '                                                                                            <option value="09:12">09:12</option>' +
        '                                                                                            <option value="09:13">09:13</option>' +
        '                                                                                            <option value="09:14">09:14</option>' +
        '                                                                                            <option value="09:15">09:15</option>' +
        '                                                                                            <option value="09:16">09:16</option>' +
        '                                                                                            <option value="09:17">09:17</option>' +
        '                                                                                            <option value="09:18">09:18</option>' +
        '                                                                                            <option value="09:19">09:19</option>' +
        '                                                                                            <option value="09:20">09:20</option>' +
        '                                                                                            <option value="09:21">09:21</option>' +
        '                                                                                            <option value="09:22">09:22</option>' +
        '                                                                                            <option value="09:23">09:23</option>' +
        '                                                                                            <option value="09:24">09:24</option>' +
        '                                                                                            <option value="09:25">09:25</option>' +
        '                                                                                            <option value="09:26">09:26</option>' +
        '                                                                                            <option value="09:27">09:27</option>' +
        '                                                                                            <option value="09:28">09:28</option>' +
        '                                                                                            <option value="09:29">09:29</option>' +
        '                                                                                            <option value="09:30">09:30</option>' +
        '                                                                                            <option value="09:31">09:31</option>' +
        '                                                                                            <option value="09:32">09:32</option>' +
        '                                                                                            <option value="09:33">09:33</option>' +
        '                                                                                            <option value="09:34">09:34</option>' +
        '                                                                                            <option value="09:35">09:35</option>' +
        '                                                                                            <option value="09:36">09:36</option>' +
        '                                                                                            <option value="09:37">09:37</option>' +
        '                                                                                            <option value="09:38">09:38</option>' +
        '                                                                                            <option value="09:39">09:39</option>' +
        '                                                                                            <option value="09:40">09:40</option>' +
        '                                                                                            <option value="09:41">09:41</option>' +
        '                                                                                            <option value="09:42">09:42</option>' +
        '                                                                                            <option value="09:43">09:43</option>' +
        '                                                                                            <option value="09:44">09:44</option>' +
        '                                                                                            <option value="09:45">09:45</option>' +
        '                                                                                            <option value="09:46">09:46</option>' +
        '                                                                                            <option value="09:47">09:47</option>' +
        '                                                                                            <option value="09:48">09:48</option>' +
        '                                                                                            <option value="09:49">09:49</option>' +
        '                                                                                            <option value="09:50">09:50</option>' +
        '                                                                                            <option value="09:51">09:51</option>' +
        '                                                                                            <option value="09:52">09:52</option>' +
        '                                                                                            <option value="09:53">09:53</option>' +
        '                                                                                            <option value="09:54">09:54</option>' +
        '                                                                                            <option value="09:55">09:55</option>' +
        '                                                                                            <option value="09:56">09:56</option>' +
        '                                                                                            <option value="09:57">09:57</option>' +
        '                                                                                            <option value="09:58">09:58</option>' +
        '                                                                                            <option value="09:59">09:59</option>' +
        '                                                                                            <option value="10:00">10:00</option>' +
        '                                                                                            <option value="10:01">10:01</option>' +
        '                                                                                            <option value="10:02">10:02</option>' +
        '                                                                                            <option value="10:03">10:03</option>' +
        '                                                                                            <option value="10:04">10:04</option>' +
        '                                                                                            <option value="10:05">10:05</option>' +
        '                                                                                            <option value="10:06">10:06</option>' +
        '                                                                                            <option value="10:07">10:07</option>' +
        '                                                                                            <option value="10:08">10:08</option>' +
        '                                                                                            <option value="10:09">10:09</option>' +
        '                                                                                            <option value="10:10">10:10</option>' +
        '                                                                                            <option value="10:11">10:11</option>' +
        '                                                                                            <option value="10:12">10:12</option>' +
        '                                                                                            <option value="10:13">10:13</option>' +
        '                                                                                            <option value="10:14">10:14</option>' +
        '                                                                                            <option value="10:15">10:15</option>' +
        '                                                                                            <option value="10:16">10:16</option>' +
        '                                                                                            <option value="10:17">10:17</option>' +
        '                                                                                            <option value="10:18">10:18</option>' +
        '                                                                                            <option value="10:19">10:19</option>' +
        '                                                                                            <option value="10:20">10:20</option>' +
        '                                                                                            <option value="10:21">10:21</option>' +
        '                                                                                            <option value="10:22">10:22</option>' +
        '                                                                                            <option value="10:23">10:23</option>' +
        '                                                                                            <option value="10:24">10:24</option>' +
        '                                                                                            <option value="10:25">10:25</option>' +
        '                                                                                            <option value="10:26">10:26</option>' +
        '                                                                                            <option value="10:27">10:27</option>' +
        '                                                                                            <option value="10:28">10:28</option>' +
        '                                                                                            <option value="10:29">10:29</option>' +
        '                                                                                            <option value="10:30">10:30</option>' +
        '                                                                                            <option value="10:31">10:31</option>' +
        '                                                                                            <option value="10:32">10:32</option>' +
        '                                                                                            <option value="10:33">10:33</option>' +
        '                                                                                            <option value="10:34">10:34</option>' +
        '                                                                                            <option value="10:35">10:35</option>' +
        '                                                                                            <option value="10:36">10:36</option>' +
        '                                                                                            <option value="10:37">10:37</option>' +
        '                                                                                            <option value="10:38">10:38</option>' +
        '                                                                                            <option value="10:39">10:39</option>' +
        '                                                                                            <option value="10:40">10:40</option>' +
        '                                                                                            <option value="10:41">10:41</option>' +
        '                                                                                            <option value="10:42">10:42</option>' +
        '                                                                                            <option value="10:43">10:43</option>' +
        '                                                                                            <option value="10:44">10:44</option>' +
        '                                                                                            <option value="10:45">10:45</option>' +
        '                                                                                            <option value="10:46">10:46</option>' +
        '                                                                                            <option value="10:47">10:47</option>' +
        '                                                                                            <option value="10:48">10:48</option>' +
        '                                                                                            <option value="10:49">10:49</option>' +
        '                                                                                            <option value="10:50">10:50</option>' +
        '                                                                                            <option value="10:51">10:51</option>' +
        '                                                                                            <option value="10:52">10:52</option>' +
        '                                                                                            <option value="10:53">10:53</option>' +
        '                                                                                            <option value="10:54">10:54</option>' +
        '                                                                                            <option value="10:55">10:55</option>' +
        '                                                                                            <option value="10:56">10:56</option>' +
        '                                                                                            <option value="10:57">10:57</option>' +
        '                                                                                            <option value="10:58">10:58</option>' +
        '                                                                                            <option value="10:59">10:59</option>' +
        '                                                                                            <option value="11:00">11:00</option>' +
        '                                                                                            <option value="11:01">11:01</option>' +
        '                                                                                            <option value="11:02">11:02</option>' +
        '                                                                                            <option value="11:03">11:03</option>' +
        '                                                                                            <option value="11:04">11:04</option>' +
        '                                                                                            <option value="11:05">11:05</option>' +
        '                                                                                            <option value="11:06">11:06</option>' +
        '                                                                                            <option value="11:07">11:07</option>' +
        '                                                                                            <option value="11:08">11:08</option>' +
        '                                                                                            <option value="11:09">11:09</option>' +
        '                                                                                            <option value="11:10">11:10</option>' +
        '                                                                                            <option value="11:11">11:11</option>' +
        '                                                                                            <option value="11:12">11:12</option>' +
        '                                                                                            <option value="11:13">11:13</option>' +
        '                                                                                            <option value="11:14">11:14</option>' +
        '                                                                                            <option value="11:15">11:15</option>' +
        '                                                                                            <option value="11:16">11:16</option>' +
        '                                                                                            <option value="11:17">11:17</option>' +
        '                                                                                            <option value="11:18">11:18</option>' +
        '                                                                                            <option value="11:19">11:19</option>' +
        '                                                                                            <option value="11:20">11:20</option>' +
        '                                                                                            <option value="11:21">11:21</option>' +
        '                                                                                            <option value="11:22">11:22</option>' +
        '                                                                                            <option value="11:23">11:23</option>' +
        '                                                                                            <option value="11:24">11:24</option>' +
        '                                                                                            <option value="11:25">11:25</option>' +
        '                                                                                            <option value="11:26">11:26</option>' +
        '                                                                                            <option value="11:27">11:27</option>' +
        '                                                                                            <option value="11:28">11:28</option>' +
        '                                                                                            <option value="11:29">11:29</option>' +
        '                                                                                            <option value="11:30">11:30</option>' +
        '                                                                                            <option value="11:31">11:31</option>' +
        '                                                                                            <option value="11:32">11:32</option>' +
        '                                                                                            <option value="11:33">11:33</option>' +
        '                                                                                            <option value="11:34">11:34</option>' +
        '                                                                                            <option value="11:35">11:35</option>' +
        '                                                                                            <option value="11:36">11:36</option>' +
        '                                                                                            <option value="11:37">11:37</option>' +
        '                                                                                            <option value="11:38">11:38</option>' +
        '                                                                                            <option value="11:39">11:39</option>' +
        '                                                                                            <option value="11:40">11:40</option>' +
        '                                                                                            <option value="11:41">11:41</option>' +
        '                                                                                            <option value="11:42">11:42</option>' +
        '                                                                                            <option value="11:43">11:43</option>' +
        '                                                                                            <option value="11:44">11:44</option>' +
        '                                                                                            <option value="11:45">11:45</option>' +
        '                                                                                            <option value="11:46">11:46</option>' +
        '                                                                                            <option value="11:47">11:47</option>' +
        '                                                                                            <option value="11:48">11:48</option>' +
        '                                                                                            <option value="11:49">11:49</option>' +
        '                                                                                            <option value="11:50">11:50</option>' +
        '                                                                                            <option value="11:51">11:51</option>' +
        '                                                                                            <option value="11:52">11:52</option>' +
        '                                                                                            <option value="11:53">11:53</option>' +
        '                                                                                            <option value="11:54">11:54</option>' +
        '                                                                                            <option value="11:55">11:55</option>' +
        '                                                                                            <option value="11:56">11:56</option>' +
        '                                                                                            <option value="11:57">11:57</option>' +
        '                                                                                            <option value="11:58">11:58</option>' +
        '                                                                                            <option value="11:59">11:59</option>' +
        '                                                                                            <option value="12:00">12:00</option>' +
        '                                                                                            <option value="12:01">12:01</option>' +
        '                                                                                            <option value="12:02">12:02</option>' +
        '                                                                                            <option value="12:03">12:03</option>' +
        '                                                                                            <option value="12:04">12:04</option>' +
        '                                                                                            <option value="12:05">12:05</option>' +
        '                                                                                            <option value="12:06">12:06</option>' +
        '                                                                                            <option value="12:07">12:07</option>' +
        '                                                                                            <option value="12:08">12:08</option>' +
        '                                                                                            <option value="12:09">12:09</option>' +
        '                                                                                            <option value="12:10">12:10</option>' +
        '                                                                                            <option value="12:11">12:11</option>' +
        '                                                                                            <option value="12:12">12:12</option>' +
        '                                                                                            <option value="12:13">12:13</option>' +
        '                                                                                            <option value="12:14">12:14</option>' +
        '                                                                                            <option value="12:15">12:15</option>' +
        '                                                                                            <option value="12:16">12:16</option>' +
        '                                                                                            <option value="12:17">12:17</option>' +
        '                                                                                            <option value="12:18">12:18</option>' +
        '                                                                                            <option value="12:19">12:19</option>' +
        '                                                                                            <option value="12:20">12:20</option>' +
        '                                                                                            <option value="12:21">12:21</option>' +
        '                                                                                            <option value="12:22">12:22</option>' +
        '                                                                                            <option value="12:23">12:23</option>' +
        '                                                                                            <option value="12:24">12:24</option>' +
        '                                                                                            <option value="12:25">12:25</option>' +
        '                                                                                            <option value="12:26">12:26</option>' +
        '                                                                                            <option value="12:27">12:27</option>' +
        '                                                                                            <option value="12:28">12:28</option>' +
        '                                                                                            <option value="12:29">12:29</option>' +
        '                                                                                            <option value="12:30">12:30</option>' +
        '                                                                                            <option value="12:31">12:31</option>' +
        '                                                                                            <option value="12:32">12:32</option>' +
        '                                                                                            <option value="12:33">12:33</option>' +
        '                                                                                            <option value="12:34">12:34</option>' +
        '                                                                                            <option value="12:35">12:35</option>' +
        '                                                                                            <option value="12:36">12:36</option>' +
        '                                                                                            <option value="12:37">12:37</option>' +
        '                                                                                            <option value="12:38">12:38</option>' +
        '                                                                                            <option value="12:39">12:39</option>' +
        '                                                                                            <option value="12:40">12:40</option>' +
        '                                                                                            <option value="12:41">12:41</option>' +
        '                                                                                            <option value="12:42">12:42</option>' +
        '                                                                                            <option value="12:43">12:43</option>' +
        '                                                                                            <option value="12:44">12:44</option>' +
        '                                                                                            <option value="12:45">12:45</option>' +
        '                                                                                            <option value="12:46">12:46</option>' +
        '                                                                                            <option value="12:47">12:47</option>' +
        '                                                                                            <option value="12:48">12:48</option>' +
        '                                                                                            <option value="12:49">12:49</option>' +
        '                                                                                            <option value="12:50">12:50</option>' +
        '                                                                                            <option value="12:51">12:51</option>' +
        '                                                                                            <option value="12:52">12:52</option>' +
        '                                                                                            <option value="12:53">12:53</option>' +
        '                                                                                            <option value="12:54">12:54</option>' +
        '                                                                                            <option value="12:55">12:55</option>' +
        '                                                                                            <option value="12:56">12:56</option>' +
        '                                                                                            <option value="12:57">12:57</option>' +
        '                                                                                            <option value="12:58">12:58</option>' +
        '                                                                                            <option value="12:59">12:59</option>' +
        '                                                                                            <option value="13:00">13:00</option>' +
        '                                                                                            <option value="13:01">13:01</option>' +
        '                                                                                            <option value="13:02">13:02</option>' +
        '                                                                                            <option value="13:03">13:03</option>' +
        '                                                                                            <option value="13:04">13:04</option>' +
        '                                                                                            <option value="13:05">13:05</option>' +
        '                                                                                            <option value="13:06">13:06</option>' +
        '                                                                                            <option value="13:07">13:07</option>' +
        '                                                                                            <option value="13:08">13:08</option>' +
        '                                                                                            <option value="13:09">13:09</option>' +
        '                                                                                            <option value="13:10">13:10</option>' +
        '                                                                                            <option value="13:11">13:11</option>' +
        '                                                                                            <option value="13:12">13:12</option>' +
        '                                                                                            <option value="13:13">13:13</option>' +
        '                                                                                            <option value="13:14">13:14</option>' +
        '                                                                                            <option value="13:15">13:15</option>' +
        '                                                                                            <option value="13:16">13:16</option>' +
        '                                                                                            <option value="13:17">13:17</option>' +
        '                                                                                            <option value="13:18">13:18</option>' +
        '                                                                                            <option value="13:19">13:19</option>' +
        '                                                                                            <option value="13:20">13:20</option>' +
        '                                                                                            <option value="13:21">13:21</option>' +
        '                                                                                            <option value="13:22">13:22</option>' +
        '                                                                                            <option value="13:23">13:23</option>' +
        '                                                                                            <option value="13:24">13:24</option>' +
        '                                                                                            <option value="13:25">13:25</option>' +
        '                                                                                            <option value="13:26">13:26</option>' +
        '                                                                                            <option value="13:27">13:27</option>' +
        '                                                                                            <option value="13:28">13:28</option>' +
        '                                                                                            <option value="13:29">13:29</option>' +
        '                                                                                            <option value="13:30">13:30</option>' +
        '                                                                                            <option value="13:31">13:31</option>' +
        '                                                                                            <option value="13:32">13:32</option>' +
        '                                                                                            <option value="13:33">13:33</option>' +
        '                                                                                            <option value="13:34">13:34</option>' +
        '                                                                                            <option value="13:35">13:35</option>' +
        '                                                                                            <option value="13:36">13:36</option>' +
        '                                                                                            <option value="13:37">13:37</option>' +
        '                                                                                            <option value="13:38">13:38</option>' +
        '                                                                                            <option value="13:39">13:39</option>' +
        '                                                                                            <option value="13:40">13:40</option>' +
        '                                                                                            <option value="13:41">13:41</option>' +
        '                                                                                            <option value="13:42">13:42</option>' +
        '                                                                                            <option value="13:43">13:43</option>' +
        '                                                                                            <option value="13:44">13:44</option>' +
        '                                                                                            <option value="13:45">13:45</option>' +
        '                                                                                            <option value="13:46">13:46</option>' +
        '                                                                                            <option value="13:47">13:47</option>' +
        '                                                                                            <option value="13:48">13:48</option>' +
        '                                                                                            <option value="13:49">13:49</option>' +
        '                                                                                            <option value="13:50">13:50</option>' +
        '                                                                                            <option value="13:51">13:51</option>' +
        '                                                                                            <option value="13:52">13:52</option>' +
        '                                                                                            <option value="13:53">13:53</option>' +
        '                                                                                            <option value="13:54">13:54</option>' +
        '                                                                                            <option value="13:55">13:55</option>' +
        '                                                                                            <option value="13:56">13:56</option>' +
        '                                                                                            <option value="13:57">13:57</option>' +
        '                                                                                            <option value="13:58">13:58</option>' +
        '                                                                                            <option value="13:59">13:59</option>' +
        '                                                                                            <option value="14:00">14:00</option>' +
        '                                                                                            <option value="14:01">14:01</option>' +
        '                                                                                            <option value="14:02">14:02</option>' +
        '                                                                                            <option value="14:03">14:03</option>' +
        '                                                                                            <option value="14:04">14:04</option>' +
        '                                                                                            <option value="14:05">14:05</option>' +
        '                                                                                            <option value="14:06">14:06</option>' +
        '                                                                                            <option value="14:07">14:07</option>' +
        '                                                                                            <option value="14:08">14:08</option>' +
        '                                                                                            <option value="14:09">14:09</option>' +
        '                                                                                            <option value="14:10">14:10</option>' +
        '                                                                                            <option value="14:11">14:11</option>' +
        '                                                                                            <option value="14:12">14:12</option>' +
        '                                                                                            <option value="14:13">14:13</option>' +
        '                                                                                            <option value="14:14">14:14</option>' +
        '                                                                                            <option value="14:15">14:15</option>' +
        '                                                                                            <option value="14:16">14:16</option>' +
        '                                                                                            <option value="14:17">14:17</option>' +
        '                                                                                            <option value="14:18">14:18</option>' +
        '                                                                                            <option value="14:19">14:19</option>' +
        '                                                                                            <option value="14:20">14:20</option>' +
        '                                                                                            <option value="14:21">14:21</option>' +
        '                                                                                            <option value="14:22">14:22</option>' +
        '                                                                                            <option value="14:23">14:23</option>' +
        '                                                                                            <option value="14:24">14:24</option>' +
        '                                                                                            <option value="14:25">14:25</option>' +
        '                                                                                            <option value="14:26">14:26</option>' +
        '                                                                                            <option value="14:27">14:27</option>' +
        '                                                                                            <option value="14:28">14:28</option>' +
        '                                                                                            <option value="14:29">14:29</option>' +
        '                                                                                            <option value="14:30">14:30</option>' +
        '                                                                                            <option value="14:31">14:31</option>' +
        '                                                                                            <option value="14:32">14:32</option>' +
        '                                                                                            <option value="14:33">14:33</option>' +
        '                                                                                            <option value="14:34">14:34</option>' +
        '                                                                                            <option value="14:35">14:35</option>' +
        '                                                                                            <option value="14:36">14:36</option>' +
        '                                                                                            <option value="14:37">14:37</option>' +
        '                                                                                            <option value="14:38">14:38</option>' +
        '                                                                                            <option value="14:39">14:39</option>' +
        '                                                                                            <option value="14:40">14:40</option>' +
        '                                                                                            <option value="14:41">14:41</option>' +
        '                                                                                            <option value="14:42">14:42</option>' +
        '                                                                                            <option value="14:43">14:43</option>' +
        '                                                                                            <option value="14:44">14:44</option>' +
        '                                                                                            <option value="14:45">14:45</option>' +
        '                                                                                            <option value="14:46">14:46</option>' +
        '                                                                                            <option value="14:47">14:47</option>' +
        '                                                                                            <option value="14:48">14:48</option>' +
        '                                                                                            <option value="14:49">14:49</option>' +
        '                                                                                            <option value="14:50">14:50</option>' +
        '                                                                                            <option value="14:51">14:51</option>' +
        '                                                                                            <option value="14:52">14:52</option>' +
        '                                                                                            <option value="14:53">14:53</option>' +
        '                                                                                            <option value="14:54">14:54</option>' +
        '                                                                                            <option value="14:55">14:55</option>' +
        '                                                                                            <option value="14:56">14:56</option>' +
        '                                                                                            <option value="14:57">14:57</option>' +
        '                                                                                            <option value="14:58">14:58</option>' +
        '                                                                                            <option value="14:59">14:59</option>' +
        '                                                                                            <option value="15:00">15:00</option>' +
        '                                                                                            <option value="15:01">15:01</option>' +
        '                                                                                            <option value="15:02">15:02</option>' +
        '                                                                                            <option value="15:03">15:03</option>' +
        '                                                                                            <option value="15:04">15:04</option>' +
        '                                                                                            <option value="15:05">15:05</option>' +
        '                                                                                            <option value="15:06">15:06</option>' +
        '                                                                                            <option value="15:07">15:07</option>' +
        '                                                                                            <option value="15:08">15:08</option>' +
        '                                                                                            <option value="15:09">15:09</option>' +
        '                                                                                            <option value="15:10">15:10</option>' +
        '                                                                                            <option value="15:11">15:11</option>' +
        '                                                                                            <option value="15:12">15:12</option>' +
        '                                                                                            <option value="15:13">15:13</option>' +
        '                                                                                            <option value="15:14">15:14</option>' +
        '                                                                                            <option value="15:15">15:15</option>' +
        '                                                                                            <option value="15:16">15:16</option>' +
        '                                                                                            <option value="15:17">15:17</option>' +
        '                                                                                            <option value="15:18">15:18</option>' +
        '                                                                                            <option value="15:19">15:19</option>' +
        '                                                                                            <option value="15:20">15:20</option>' +
        '                                                                                            <option value="15:21">15:21</option>' +
        '                                                                                            <option value="15:22">15:22</option>' +
        '                                                                                            <option value="15:23">15:23</option>' +
        '                                                                                            <option value="15:24">15:24</option>' +
        '                                                                                            <option value="15:25">15:25</option>' +
        '                                                                                            <option value="15:26">15:26</option>' +
        '                                                                                            <option value="15:27">15:27</option>' +
        '                                                                                            <option value="15:28">15:28</option>' +
        '                                                                                            <option value="15:29">15:29</option>' +
        '                                                                                            <option value="15:30">15:30</option>' +
        '                                                                                            <option value="15:31">15:31</option>' +
        '                                                                                            <option value="15:32">15:32</option>' +
        '                                                                                            <option value="15:33">15:33</option>' +
        '                                                                                            <option value="15:34">15:34</option>' +
        '                                                                                            <option value="15:35">15:35</option>' +
        '                                                                                            <option value="15:36">15:36</option>' +
        '                                                                                            <option value="15:37">15:37</option>' +
        '                                                                                            <option value="15:38">15:38</option>' +
        '                                                                                            <option value="15:39">15:39</option>' +
        '                                                                                            <option value="15:40">15:40</option>' +
        '                                                                                            <option value="15:41">15:41</option>' +
        '                                                                                            <option value="15:42">15:42</option>' +
        '                                                                                            <option value="15:43">15:43</option>' +
        '                                                                                            <option value="15:44">15:44</option>' +
        '                                                                                            <option value="15:45">15:45</option>' +
        '                                                                                            <option value="15:46">15:46</option>' +
        '                                                                                            <option value="15:47">15:47</option>' +
        '                                                                                            <option value="15:48">15:48</option>' +
        '                                                                                            <option value="15:49">15:49</option>' +
        '                                                                                            <option value="15:50">15:50</option>' +
        '                                                                                            <option value="15:51">15:51</option>' +
        '                                                                                            <option value="15:52">15:52</option>' +
        '                                                                                            <option value="15:53">15:53</option>' +
        '                                                                                            <option value="15:54">15:54</option>' +
        '                                                                                            <option value="15:55">15:55</option>' +
        '                                                                                            <option value="15:56">15:56</option>' +
        '                                                                                            <option value="15:57">15:57</option>' +
        '                                                                                            <option value="15:58">15:58</option>' +
        '                                                                                            <option value="15:59">15:59</option>' +
        '                                                                                            <option value="16:00">16:00</option>' +
        '                                                                                            <option value="16:01">16:01</option>' +
        '                                                                                            <option value="16:02">16:02</option>' +
        '                                                                                            <option value="16:03">16:03</option>' +
        '                                                                                            <option value="16:04">16:04</option>' +
        '                                                                                            <option value="16:05">16:05</option>' +
        '                                                                                            <option value="16:06">16:06</option>' +
        '                                                                                            <option value="16:07">16:07</option>' +
        '                                                                                            <option value="16:08">16:08</option>' +
        '                                                                                            <option value="16:09">16:09</option>' +
        '                                                                                            <option value="16:10">16:10</option>' +
        '                                                                                            <option value="16:11">16:11</option>' +
        '                                                                                            <option value="16:12">16:12</option>' +
        '                                                                                            <option value="16:13">16:13</option>' +
        '                                                                                            <option value="16:14">16:14</option>' +
        '                                                                                            <option value="16:15">16:15</option>' +
        '                                                                                            <option value="16:16">16:16</option>' +
        '                                                                                            <option value="16:17">16:17</option>' +
        '                                                                                            <option value="16:18">16:18</option>' +
        '                                                                                            <option value="16:19">16:19</option>' +
        '                                                                                            <option value="16:20">16:20</option>' +
        '                                                                                            <option value="16:21">16:21</option>' +
        '                                                                                            <option value="16:22">16:22</option>' +
        '                                                                                            <option value="16:23">16:23</option>' +
        '                                                                                            <option value="16:24">16:24</option>' +
        '                                                                                            <option value="16:25">16:25</option>' +
        '                                                                                            <option value="16:26">16:26</option>' +
        '                                                                                            <option value="16:27">16:27</option>' +
        '                                                                                            <option value="16:28">16:28</option>' +
        '                                                                                            <option value="16:29">16:29</option>' +
        '                                                                                            <option value="16:30">16:30</option>' +
        '                                                                                            <option value="16:31">16:31</option>' +
        '                                                                                            <option value="16:32">16:32</option>' +
        '                                                                                            <option value="16:33">16:33</option>' +
        '                                                                                            <option value="16:34">16:34</option>' +
        '                                                                                            <option value="16:35">16:35</option>' +
        '                                                                                            <option value="16:36">16:36</option>' +
        '                                                                                            <option value="16:37">16:37</option>' +
        '                                                                                            <option value="16:38">16:38</option>' +
        '                                                                                            <option value="16:39">16:39</option>' +
        '                                                                                            <option value="16:40">16:40</option>' +
        '                                                                                            <option value="16:41">16:41</option>' +
        '                                                                                            <option value="16:42">16:42</option>' +
        '                                                                                            <option value="16:43">16:43</option>' +
        '                                                                                            <option value="16:44">16:44</option>' +
        '                                                                                            <option value="16:45">16:45</option>' +
        '                                                                                            <option value="16:46">16:46</option>' +
        '                                                                                            <option value="16:47">16:47</option>' +
        '                                                                                            <option value="16:48">16:48</option>' +
        '                                                                                            <option value="16:49">16:49</option>' +
        '                                                                                            <option value="16:50">16:50</option>' +
        '                                                                                            <option value="16:51">16:51</option>' +
        '                                                                                            <option value="16:52">16:52</option>' +
        '                                                                                            <option value="16:53">16:53</option>' +
        '                                                                                            <option value="16:54">16:54</option>' +
        '                                                                                            <option value="16:55">16:55</option>' +
        '                                                                                            <option value="16:56">16:56</option>' +
        '                                                                                            <option value="16:57">16:57</option>' +
        '                                                                                            <option value="16:58">16:58</option>' +
        '                                                                                            <option value="16:59">16:59</option>' +
        '                                                                                            <option value="17:00">17:00</option>' +
        '                                                                                            <option value="17:01">17:01</option>' +
        '                                                                                            <option value="17:02">17:02</option>' +
        '                                                                                            <option value="17:03">17:03</option>' +
        '                                                                                            <option value="17:04">17:04</option>' +
        '                                                                                            <option value="17:05">17:05</option>' +
        '                                                                                            <option value="17:06">17:06</option>' +
        '                                                                                            <option value="17:07">17:07</option>' +
        '                                                                                            <option value="17:08">17:08</option>' +
        '                                                                                            <option value="17:09">17:09</option>' +
        '                                                                                            <option value="17:10">17:10</option>' +
        '                                                                                            <option value="17:11">17:11</option>' +
        '                                                                                            <option value="17:12">17:12</option>' +
        '                                                                                            <option value="17:13">17:13</option>' +
        '                                                                                            <option value="17:14">17:14</option>' +
        '                                                                                            <option value="17:15">17:15</option>' +
        '                                                                                            <option value="17:16">17:16</option>' +
        '                                                                                            <option value="17:17">17:17</option>' +
        '                                                                                            <option value="17:18">17:18</option>' +
        '                                                                                            <option value="17:19">17:19</option>' +
        '                                                                                            <option value="17:20">17:20</option>' +
        '                                                                                            <option value="17:21">17:21</option>' +
        '                                                                                            <option value="17:22">17:22</option>' +
        '                                                                                            <option value="17:23">17:23</option>' +
        '                                                                                            <option value="17:24">17:24</option>' +
        '                                                                                            <option value="17:25">17:25</option>' +
        '                                                                                            <option value="17:26">17:26</option>' +
        '                                                                                            <option value="17:27">17:27</option>' +
        '                                                                                            <option value="17:28">17:28</option>' +
        '                                                                                            <option value="17:29">17:29</option>' +
        '                                                                                            <option value="17:30">17:30</option>' +
        '                                                                                            <option value="17:31">17:31</option>' +
        '                                                                                            <option value="17:32">17:32</option>' +
        '                                                                                            <option value="17:33">17:33</option>' +
        '                                                                                            <option value="17:34">17:34</option>' +
        '                                                                                            <option value="17:35">17:35</option>' +
        '                                                                                            <option value="17:36">17:36</option>' +
        '                                                                                            <option value="17:37">17:37</option>' +
        '                                                                                            <option value="17:38">17:38</option>' +
        '                                                                                            <option value="17:39">17:39</option>' +
        '                                                                                            <option value="17:40">17:40</option>' +
        '                                                                                            <option value="17:41">17:41</option>' +
        '                                                                                            <option value="17:42">17:42</option>' +
        '                                                                                            <option value="17:43">17:43</option>' +
        '                                                                                            <option value="17:44">17:44</option>' +
        '                                                                                            <option value="17:45">17:45</option>' +
        '                                                                                            <option value="17:46">17:46</option>' +
        '                                                                                            <option value="17:47">17:47</option>' +
        '                                                                                            <option value="17:48">17:48</option>' +
        '                                                                                            <option value="17:49">17:49</option>' +
        '                                                                                            <option value="17:50">17:50</option>' +
        '                                                                                            <option value="17:51">17:51</option>' +
        '                                                                                            <option value="17:52">17:52</option>' +
        '                                                                                            <option value="17:53">17:53</option>' +
        '                                                                                            <option value="17:54">17:54</option>' +
        '                                                                                            <option value="17:55">17:55</option>' +
        '                                                                                            <option value="17:56">17:56</option>' +
        '                                                                                            <option value="17:57">17:57</option>' +
        '                                                                                            <option value="17:58">17:58</option>' +
        '                                                                                            <option value="17:59">17:59</option>' +
        '                                                                                            <option value="18:00">18:00</option>' +
        '                                                                                            <option value="18:01">18:01</option>' +
        '                                                                                            <option value="18:02">18:02</option>' +
        '                                                                                            <option value="18:03">18:03</option>' +
        '                                                                                            <option value="18:04">18:04</option>' +
        '                                                                                            <option value="18:05">18:05</option>' +
        '                                                                                            <option value="18:06">18:06</option>' +
        '                                                                                            <option value="18:07">18:07</option>' +
        '                                                                                            <option value="18:08">18:08</option>' +
        '                                                                                            <option value="18:09">18:09</option>' +
        '                                                                                            <option value="18:10">18:10</option>' +
        '                                                                                            <option value="18:11">18:11</option>' +
        '                                                                                            <option value="18:12">18:12</option>' +
        '                                                                                            <option value="18:13">18:13</option>' +
        '                                                                                            <option value="18:14">18:14</option>' +
        '                                                                                            <option value="18:15">18:15</option>' +
        '                                                                                            <option value="18:16">18:16</option>' +
        '                                                                                            <option value="18:17">18:17</option>' +
        '                                                                                            <option value="18:18">18:18</option>' +
        '                                                                                            <option value="18:19">18:19</option>' +
        '                                                                                            <option value="18:20">18:20</option>' +
        '                                                                                            <option value="18:21">18:21</option>' +
        '                                                                                            <option value="18:22">18:22</option>' +
        '                                                                                            <option value="18:23">18:23</option>' +
        '                                                                                            <option value="18:24">18:24</option>' +
        '                                                                                            <option value="18:25">18:25</option>' +
        '                                                                                            <option value="18:26">18:26</option>' +
        '                                                                                            <option value="18:27">18:27</option>' +
        '                                                                                            <option value="18:28">18:28</option>' +
        '                                                                                            <option value="18:29">18:29</option>' +
        '                                                                                            <option value="18:30">18:30</option>' +
        '                                                                                            <option value="18:31">18:31</option>' +
        '                                                                                            <option value="18:32">18:32</option>' +
        '                                                                                            <option value="18:33">18:33</option>' +
        '                                                                                            <option value="18:34">18:34</option>' +
        '                                                                                            <option value="18:35">18:35</option>' +
        '                                                                                            <option value="18:36">18:36</option>' +
        '                                                                                            <option value="18:37">18:37</option>' +
        '                                                                                            <option value="18:38">18:38</option>' +
        '                                                                                            <option value="18:39">18:39</option>' +
        '                                                                                            <option value="18:40">18:40</option>' +
        '                                                                                            <option value="18:41">18:41</option>' +
        '                                                                                            <option value="18:42">18:42</option>' +
        '                                                                                            <option value="18:43">18:43</option>' +
        '                                                                                            <option value="18:44">18:44</option>' +
        '                                                                                            <option value="18:45">18:45</option>' +
        '                                                                                            <option value="18:46">18:46</option>' +
        '                                                                                            <option value="18:47">18:47</option>' +
        '                                                                                            <option value="18:48">18:48</option>' +
        '                                                                                            <option value="18:49">18:49</option>' +
        '                                                                                            <option value="18:50">18:50</option>' +
        '                                                                                            <option value="18:51">18:51</option>' +
        '                                                                                            <option value="18:52">18:52</option>' +
        '                                                                                            <option value="18:53">18:53</option>' +
        '                                                                                            <option value="18:54">18:54</option>' +
        '                                                                                            <option value="18:55">18:55</option>' +
        '                                                                                            <option value="18:56">18:56</option>' +
        '                                                                                            <option value="18:57">18:57</option>' +
        '                                                                                            <option value="18:58">18:58</option>' +
        '                                                                                            <option value="18:59">18:59</option>' +
        '                                                                                            <option value="19:00">19:00</option>' +
        '                                                                                            <option value="19:01">19:01</option>' +
        '                                                                                            <option value="19:02">19:02</option>' +
        '                                                                                            <option value="19:03">19:03</option>' +
        '                                                                                            <option value="19:04">19:04</option>' +
        '                                                                                            <option value="19:05">19:05</option>' +
        '                                                                                            <option value="19:06">19:06</option>' +
        '                                                                                            <option value="19:07">19:07</option>' +
        '                                                                                            <option value="19:08">19:08</option>' +
        '                                                                                            <option value="19:09">19:09</option>' +
        '                                                                                            <option value="19:10">19:10</option>' +
        '                                                                                            <option value="19:11">19:11</option>' +
        '                                                                                            <option value="19:12">19:12</option>' +
        '                                                                                            <option value="19:13">19:13</option>' +
        '                                                                                            <option value="19:14">19:14</option>' +
        '                                                                                            <option value="19:15">19:15</option>' +
        '                                                                                            <option value="19:16">19:16</option>' +
        '                                                                                            <option value="19:17">19:17</option>' +
        '                                                                                            <option value="19:18">19:18</option>' +
        '                                                                                            <option value="19:19">19:19</option>' +
        '                                                                                            <option value="19:20">19:20</option>' +
        '                                                                                            <option value="19:21">19:21</option>' +
        '                                                                                            <option value="19:22">19:22</option>' +
        '                                                                                            <option value="19:23">19:23</option>' +
        '                                                                                            <option value="19:24">19:24</option>' +
        '                                                                                            <option value="19:25">19:25</option>' +
        '                                                                                            <option value="19:26">19:26</option>' +
        '                                                                                            <option value="19:27">19:27</option>' +
        '                                                                                            <option value="19:28">19:28</option>' +
        '                                                                                            <option value="19:29">19:29</option>' +
        '                                                                                            <option value="19:30">19:30</option>' +
        '                                                                                            <option value="19:31">19:31</option>' +
        '                                                                                            <option value="19:32">19:32</option>' +
        '                                                                                            <option value="19:33">19:33</option>' +
        '                                                                                            <option value="19:34">19:34</option>' +
        '                                                                                            <option value="19:35">19:35</option>' +
        '                                                                                            <option value="19:36">19:36</option>' +
        '                                                                                            <option value="19:37">19:37</option>' +
        '                                                                                            <option value="19:38">19:38</option>' +
        '                                                                                            <option value="19:39">19:39</option>' +
        '                                                                                            <option value="19:40">19:40</option>' +
        '                                                                                            <option value="19:41">19:41</option>' +
        '                                                                                            <option value="19:42">19:42</option>' +
        '                                                                                            <option value="19:43">19:43</option>' +
        '                                                                                            <option value="19:44">19:44</option>' +
        '                                                                                            <option value="19:45">19:45</option>' +
        '                                                                                            <option value="19:46">19:46</option>' +
        '                                                                                            <option value="19:47">19:47</option>' +
        '                                                                                            <option value="19:48">19:48</option>' +
        '                                                                                            <option value="19:49">19:49</option>' +
        '                                                                                            <option value="19:50">19:50</option>' +
        '                                                                                            <option value="19:51">19:51</option>' +
        '                                                                                            <option value="19:52">19:52</option>' +
        '                                                                                            <option value="19:53">19:53</option>' +
        '                                                                                            <option value="19:54">19:54</option>' +
        '                                                                                            <option value="19:55">19:55</option>' +
        '                                                                                            <option value="19:56">19:56</option>' +
        '                                                                                            <option value="19:57">19:57</option>' +
        '                                                                                            <option value="19:58">19:58</option>' +
        '                                                                                            <option value="19:59">19:59</option>' +
        '                                                                                            <option value="20:00">20:00</option>' +
        '                                                                                            <option value="20:01">20:01</option>' +
        '                                                                                            <option value="20:02">20:02</option>' +
        '                                                                                            <option value="20:03">20:03</option>' +
        '                                                                                            <option value="20:04">20:04</option>' +
        '                                                                                            <option value="20:05">20:05</option>' +
        '                                                                                            <option value="20:06">20:06</option>' +
        '                                                                                            <option value="20:07">20:07</option>' +
        '                                                                                            <option value="20:08">20:08</option>' +
        '                                                                                            <option value="20:09">20:09</option>' +
        '                                                                                            <option value="20:10">20:10</option>' +
        '                                                                                            <option value="20:11">20:11</option>' +
        '                                                                                            <option value="20:12">20:12</option>' +
        '                                                                                            <option value="20:13">20:13</option>' +
        '                                                                                            <option value="20:14">20:14</option>' +
        '                                                                                            <option value="20:15">20:15</option>' +
        '                                                                                            <option value="20:16">20:16</option>' +
        '                                                                                            <option value="20:17">20:17</option>' +
        '                                                                                            <option value="20:18">20:18</option>' +
        '                                                                                            <option value="20:19">20:19</option>' +
        '                                                                                            <option value="20:20">20:20</option>' +
        '                                                                                            <option value="20:21">20:21</option>' +
        '                                                                                            <option value="20:22">20:22</option>' +
        '                                                                                            <option value="20:23">20:23</option>' +
        '                                                                                            <option value="20:24">20:24</option>' +
        '                                                                                            <option value="20:25">20:25</option>' +
        '                                                                                            <option value="20:26">20:26</option>' +
        '                                                                                            <option value="20:27">20:27</option>' +
        '                                                                                            <option value="20:28">20:28</option>' +
        '                                                                                            <option value="20:29">20:29</option>' +
        '                                                                                            <option value="20:30">20:30</option>' +
        '                                                                                            <option value="20:31">20:31</option>' +
        '                                                                                            <option value="20:32">20:32</option>' +
        '                                                                                            <option value="20:33">20:33</option>' +
        '                                                                                            <option value="20:34">20:34</option>' +
        '                                                                                            <option value="20:35">20:35</option>' +
        '                                                                                            <option value="20:36">20:36</option>' +
        '                                                                                            <option value="20:37">20:37</option>' +
        '                                                                                            <option value="20:38">20:38</option>' +
        '                                                                                            <option value="20:39">20:39</option>' +
        '                                                                                            <option value="20:40">20:40</option>' +
        '                                                                                            <option value="20:41">20:41</option>' +
        '                                                                                            <option value="20:42">20:42</option>' +
        '                                                                                            <option value="20:43">20:43</option>' +
        '                                                                                            <option value="20:44">20:44</option>' +
        '                                                                                            <option value="20:45">20:45</option>' +
        '                                                                                            <option value="20:46">20:46</option>' +
        '                                                                                            <option value="20:47">20:47</option>' +
        '                                                                                            <option value="20:48">20:48</option>' +
        '                                                                                            <option value="20:49">20:49</option>' +
        '                                                                                            <option value="20:50">20:50</option>' +
        '                                                                                            <option value="20:51">20:51</option>' +
        '                                                                                            <option value="20:52">20:52</option>' +
        '                                                                                            <option value="20:53">20:53</option>' +
        '                                                                                            <option value="20:54">20:54</option>' +
        '                                                                                            <option value="20:55">20:55</option>' +
        '                                                                                            <option value="20:56">20:56</option>' +
        '                                                                                            <option value="20:57">20:57</option>' +
        '                                                                                            <option value="20:58">20:58</option>' +
        '                                                                                            <option value="20:59">20:59</option>' +
        '                                                                                            <option value="21:00">21:00</option>' +
        '                                                                                            <option value="21:01">21:01</option>' +
        '                                                                                            <option value="21:02">21:02</option>' +
        '                                                                                            <option value="21:03">21:03</option>' +
        '                                                                                            <option value="21:04">21:04</option>' +
        '                                                                                            <option value="21:05">21:05</option>' +
        '                                                                                            <option value="21:06">21:06</option>' +
        '                                                                                            <option value="21:07">21:07</option>' +
        '                                                                                            <option value="21:08">21:08</option>' +
        '                                                                                            <option value="21:09">21:09</option>' +
        '                                                                                            <option value="21:10">21:10</option>' +
        '                                                                                            <option value="21:11">21:11</option>' +
        '                                                                                            <option value="21:12">21:12</option>' +
        '                                                                                            <option value="21:13">21:13</option>' +
        '                                                                                            <option value="21:14">21:14</option>' +
        '                                                                                            <option value="21:15">21:15</option>' +
        '                                                                                            <option value="21:16">21:16</option>' +
        '                                                                                            <option value="21:17">21:17</option>' +
        '                                                                                            <option value="21:18">21:18</option>' +
        '                                                                                            <option value="21:19">21:19</option>' +
        '                                                                                            <option value="21:20">21:20</option>' +
        '                                                                                            <option value="21:21">21:21</option>' +
        '                                                                                            <option value="21:22">21:22</option>' +
        '                                                                                            <option value="21:23">21:23</option>' +
        '                                                                                            <option value="21:24">21:24</option>' +
        '                                                                                            <option value="21:25">21:25</option>' +
        '                                                                                            <option value="21:26">21:26</option>' +
        '                                                                                            <option value="21:27">21:27</option>' +
        '                                                                                            <option value="21:28">21:28</option>' +
        '                                                                                            <option value="21:29">21:29</option>' +
        '                                                                                            <option value="21:30">21:30</option>' +
        '                                                                                            <option value="21:31">21:31</option>' +
        '                                                                                            <option value="21:32">21:32</option>' +
        '                                                                                            <option value="21:33">21:33</option>' +
        '                                                                                            <option value="21:34">21:34</option>' +
        '                                                                                            <option value="21:35">21:35</option>' +
        '                                                                                            <option value="21:36">21:36</option>' +
        '                                                                                            <option value="21:37">21:37</option>' +
        '                                                                                            <option value="21:38">21:38</option>' +
        '                                                                                            <option value="21:39">21:39</option>' +
        '                                                                                            <option value="21:40">21:40</option>' +
        '                                                                                            <option value="21:41">21:41</option>' +
        '                                                                                            <option value="21:42">21:42</option>' +
        '                                                                                            <option value="21:43">21:43</option>' +
        '                                                                                            <option value="21:44">21:44</option>' +
        '                                                                                            <option value="21:45">21:45</option>' +
        '                                                                                            <option value="21:46">21:46</option>' +
        '                                                                                            <option value="21:47">21:47</option>' +
        '                                                                                            <option value="21:48">21:48</option>' +
        '                                                                                            <option value="21:49">21:49</option>' +
        '                                                                                            <option value="21:50">21:50</option>' +
        '                                                                                            <option value="21:51">21:51</option>' +
        '                                                                                            <option value="21:52">21:52</option>' +
        '                                                                                            <option value="21:53">21:53</option>' +
        '                                                                                            <option value="21:54">21:54</option>' +
        '                                                                                            <option value="21:55">21:55</option>' +
        '                                                                                            <option value="21:56">21:56</option>' +
        '                                                                                            <option value="21:57">21:57</option>' +
        '                                                                                            <option value="21:58">21:58</option>' +
        '                                                                                            <option value="21:59">21:59</option>' +
        '                                                                                            <option value="22:00">22:00</option>' +
        '                                                                                            <option value="22:01">22:01</option>' +
        '                                                                                            <option value="22:02">22:02</option>' +
        '                                                                                            <option value="22:03">22:03</option>' +
        '                                                                                            <option value="22:04">22:04</option>' +
        '                                                                                            <option value="22:05">22:05</option>' +
        '                                                                                            <option value="22:06">22:06</option>' +
        '                                                                                            <option value="22:07">22:07</option>' +
        '                                                                                            <option value="22:08">22:08</option>' +
        '                                                                                            <option value="22:09">22:09</option>' +
        '                                                                                            <option value="22:10">22:10</option>' +
        '                                                                                            <option value="22:11">22:11</option>' +
        '                                                                                            <option value="22:12">22:12</option>' +
        '                                                                                            <option value="22:13">22:13</option>' +
        '                                                                                            <option value="22:14">22:14</option>' +
        '                                                                                            <option value="22:15">22:15</option>' +
        '                                                                                            <option value="22:16">22:16</option>' +
        '                                                                                            <option value="22:17">22:17</option>' +
        '                                                                                            <option value="22:18">22:18</option>' +
        '                                                                                            <option value="22:19">22:19</option>' +
        '                                                                                            <option value="22:20">22:20</option>' +
        '                                                                                            <option value="22:21">22:21</option>' +
        '                                                                                            <option value="22:22">22:22</option>' +
        '                                                                                            <option value="22:23">22:23</option>' +
        '                                                                                            <option value="22:24">22:24</option>' +
        '                                                                                            <option value="22:25">22:25</option>' +
        '                                                                                            <option value="22:26">22:26</option>' +
        '                                                                                            <option value="22:27">22:27</option>' +
        '                                                                                            <option value="22:28">22:28</option>' +
        '                                                                                            <option value="22:29">22:29</option>' +
        '                                                                                            <option value="22:30">22:30</option>' +
        '                                                                                            <option value="22:31">22:31</option>' +
        '                                                                                            <option value="22:32">22:32</option>' +
        '                                                                                            <option value="22:33">22:33</option>' +
        '                                                                                            <option value="22:34">22:34</option>' +
        '                                                                                            <option value="22:35">22:35</option>' +
        '                                                                                            <option value="22:36">22:36</option>' +
        '                                                                                            <option value="22:37">22:37</option>' +
        '                                                                                            <option value="22:38">22:38</option>' +
        '                                                                                            <option value="22:39">22:39</option>' +
        '                                                                                            <option value="22:40">22:40</option>' +
        '                                                                                            <option value="22:41">22:41</option>' +
        '                                                                                            <option value="22:42">22:42</option>' +
        '                                                                                            <option value="22:43">22:43</option>' +
        '                                                                                            <option value="22:44">22:44</option>' +
        '                                                                                            <option value="22:45">22:45</option>' +
        '                                                                                            <option value="22:46">22:46</option>' +
        '                                                                                            <option value="22:47">22:47</option>' +
        '                                                                                            <option value="22:48">22:48</option>' +
        '                                                                                            <option value="22:49">22:49</option>' +
        '                                                                                            <option value="22:50">22:50</option>' +
        '                                                                                            <option value="22:51">22:51</option>' +
        '                                                                                            <option value="22:52">22:52</option>' +
        '                                                                                            <option value="22:53">22:53</option>' +
        '                                                                                            <option value="22:54">22:54</option>' +
        '                                                                                            <option value="22:55">22:55</option>' +
        '                                                                                            <option value="22:56">22:56</option>' +
        '                                                                                            <option value="22:57">22:57</option>' +
        '                                                                                            <option value="22:58">22:58</option>' +
        '                                                                                            <option value="22:59">22:59</option>' +
        '                                                                                            <option value="23:00">23:00</option>' +
        '                                                                                            <option value="23:01">23:01</option>' +
        '                                                                                            <option value="23:02">23:02</option>' +
        '                                                                                            <option value="23:03">23:03</option>' +
        '                                                                                            <option value="23:04">23:04</option>' +
        '                                                                                            <option value="23:05">23:05</option>' +
        '                                                                                            <option value="23:06">23:06</option>' +
        '                                                                                            <option value="23:07">23:07</option>' +
        '                                                                                            <option value="23:08">23:08</option>' +
        '                                                                                            <option value="23:09">23:09</option>' +
        '                                                                                            <option value="23:10">23:10</option>' +
        '                                                                                            <option value="23:11">23:11</option>' +
        '                                                                                            <option value="23:12">23:12</option>' +
        '                                                                                            <option value="23:13">23:13</option>' +
        '                                                                                            <option value="23:14">23:14</option>' +
        '                                                                                            <option value="23:15">23:15</option>' +
        '                                                                                            <option value="23:16">23:16</option>' +
        '                                                                                            <option value="23:17">23:17</option>' +
        '                                                                                            <option value="23:18">23:18</option>' +
        '                                                                                            <option value="23:19">23:19</option>' +
        '                                                                                            <option value="23:20">23:20</option>' +
        '                                                                                            <option value="23:21">23:21</option>' +
        '                                                                                            <option value="23:22">23:22</option>' +
        '                                                                                            <option value="23:23">23:23</option>' +
        '                                                                                            <option value="23:24">23:24</option>' +
        '                                                                                            <option value="23:25">23:25</option>' +
        '                                                                                            <option value="23:26">23:26</option>' +
        '                                                                                            <option value="23:27">23:27</option>' +
        '                                                                                            <option value="23:28">23:28</option>' +
        '                                                                                            <option value="23:29">23:29</option>' +
        '                                                                                            <option value="23:30">23:30</option>' +
        '                                                                                            <option value="23:31">23:31</option>' +
        '                                                                                            <option value="23:32">23:32</option>' +
        '                                                                                            <option value="23:33">23:33</option>' +
        '                                                                                            <option value="23:34">23:34</option>' +
        '                                                                                            <option value="23:35">23:35</option>' +
        '                                                                                            <option value="23:36">23:36</option>' +
        '                                                                                            <option value="23:37">23:37</option>' +
        '                                                                                            <option value="23:38">23:38</option>' +
        '                                                                                            <option value="23:39">23:39</option>' +
        '                                                                                            <option value="23:40">23:40</option>' +
        '                                                                                            <option value="23:41">23:41</option>' +
        '                                                                                            <option value="23:42">23:42</option>' +
        '                                                                                            <option value="23:43">23:43</option>' +
        '                                                                                            <option value="23:44">23:44</option>' +
        '                                                                                            <option value="23:45">23:45</option>' +
        '                                                                                            <option value="23:46">23:46</option>' +
        '                                                                                            <option value="23:47">23:47</option>' +
        '                                                                                            <option value="23:48">23:48</option>' +
        '                                                                                            <option value="23:49">23:49</option>' +
        '                                                                                            <option value="23:50">23:50</option>' +
        '                                                                                            <option value="23:51">23:51</option>' +
        '                                                                                            <option value="23:52">23:52</option>' +
        '                                                                                            <option value="23:53">23:53</option>' +
        '                                                                                            <option value="23:54">23:54</option>' +
        '                                                                                            <option value="23:55">23:55</option>' +
        '                                                                                            <option value="23:56">23:56</option>' +
        '                                                                                            <option value="23:57">23:57</option>' +
        '                                                                                            <option value="23:58">23:58</option>' +
        '                                                                                            <option value="23:59">23:59</option>' +
        '                                                                                    </select>' +
        '' +
        '                                    </div>' +
        '                                </div>' +
        '' +
        '                                <div class="col-md-3">' +
        '                                    <div class="form-group">' +
        '                                        <label>Is Arrival Next Day?</label>' +
        '                                        <select class="form-control" name="onward[' + triptype + '][' + countval + '][is_next_day_arrival]" placeholder="Is Arrival Next Day?">' +
        '                                            <option value="No" selected="">No</option>' +
        '                                            <option value="Yes">Yes</option>' +
        '                                        </select>' +
        '                                    </div>' +
        '                                </div>' +
        '                            </div>' +
        '                        </div>' +
        '';
    ;


    document.getElementById(id).insertAdjacentHTML('beforeend', variable_html);
}


function remove_more_items_segment(thisval, id) {
    var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
    console.log(lists);
    if (lists.length > 1) {
        if (confirm('Do you want to delete item ?')) {
            $(thisval).parent().parent().parent().remove();

            var lists = document.querySelectorAll("#" + id + "> .tts-itinerary-row");
           
            if (lists.length == 1) {
                var counter_reset = 0;
            } else {
                var counter_reset = lists.length - 1;
            }
            document.getElementById('tts-segment-counter').value = counter_reset;
        }
    } else {
        alert("You can't delete at least 1 item");
    }
}




 // airpot single select autocomplete
 $(document).on("keydown", "[tts-get-flight-top-route]", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    $(this).autocomplete({
        minLength: 3,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: site_url + 'flight-top-routes/get-airports',
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
        focus: function () {
            return false;
        },
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $(this).next().val(ui.item.airport_code); 
            return false;
        }, change: function (event, ui) {
            $(this).val((ui.item ? ui.item.label : ""));
        },
        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                var cityname = item.city;
                var airportcode = item.airport_code;
                var airportname = item.airport_name;
                var country_code = item.country_code;
                var countryName = item.country_name;
                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class='dest_left'>" +
                        "<samp class='city'>" +
                        cityname +
                        "</samp>" +
                        "<samp class='airpotcode'>&nbsp;(" +
                        airportname +
                        ")&nbsp;</samp>" +
                        "</div><div><samp class='aircode'>[" +
                        airportcode +
                        "]</samp><i class='flag " + (country_code.toLowerCase()) + "'></i></div>" +
                        "</a>").appendTo(ul);
            };
        },
    });
});


/* ***************** use for this code holiday markup amd discount start  ********************* */



 // Holiday package multiple select autocomplete 
 
 var selectHolidayid=[];
$(document).on("keydown", "[tts-get-holiday-package='true']", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: site_url + 'holiday/get-packages',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    var manipulatedData = data.map(function (item) {
                        return {
                            PackageID: item.PackageID,
                            Package: item.Package,
                            label: item.Package + ' (' + item.PackageID + ')'
                        };
                    });

                    response(manipulatedData);
                }
            });
        },
        focus: function () {
            return false;
        },
        select: function (event, ui) {
            var currentValues = this.value.split(',').map(function (value) {
                return value.trim();
            });

            currentValues.pop();

            var selectedPackage = ui.item.Package.trim();

            if (!currentValues.includes(selectedPackage)) {
                currentValues.push(selectedPackage);
                currentValues.push("");
                this.value = currentValues.join(",");

                selectHolidayid.push(ui.item.PackageID);
                var id = selectHolidayid.join(",");
                $("[tts-holiday-package-id]").val(id);
            }

            return false;
        },
        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (
                ul, item) {
                var packagename = item.Package;
                var packageid = item.PackageID;
                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class='dest_left'>" +
                        "<span class='city'>" +
                        packagename +
                        "</span>" +
                        "<span class='airpotcode'>&nbsp;(" +
                        packageid +
                        ")&nbsp;</span>" +
                        "</div></a>").appendTo(ul);
            };
        },
    });
});


$(document).on("change", "[tts-holiday-any]", function (event) {
    if (this.checked) {
        $('[name="holiday_package"]').val(this.value).attr('readonly', true);
        $('[name="holiday_package"]').addClass('tts-read-only');
        $("[tts-holiday-package-id]").val('ANY');
        selectHolidayid=[];
    } else {
        $('[name="holiday_package"]').val('').attr('readonly', false);
        $('[name="holiday_package"]').removeClass('tts-read-only');
        $("[tts-holiday-package-id]").val('');
    }
});


$(document).on("keydown", "[holiday-theme]", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    var method_name = $(this).attr('tts-method-name');
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: method_name,
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
        focus: function () {
            return false;
        },
        select: function (event, ui) {
           /*  var terms = split(this.value);
            terms.pop();
            terms.push(ui.item.value);
            terms.push("");
            this.value = terms.join(",");

            //
            var val = $("[tts-theme-id]").val();

            var terms_id = split(val);
            terms_id.pop();
            terms_id.push(ui.item.id);
            terms_id.push("");
            terms_id = terms_id.join(",");

            $("[tts-theme-id]").val(terms_id);


            return false; */




            var currentValues = this.value.split(',').map(function (value) {
                return value.trim();
            });
            currentValues.pop();
          
      
            var selectedPackageName = ui.item['value'];

            if (!currentValues.includes(selectedPackageName)) {
                currentValues.push(selectedPackageName);
                currentValues.push("");
                this.value=currentValues.join(",");

                selectHolidayid.push(ui.item['id']);
                var id = selectHolidayid.join(",");
                $("[tts-theme-id]").val(id);
            } 
            return false; 

        },


    });
});



$(document).on("change", "[tts-theme_name-any]", function (event) {
    if (this.checked) {
        $('[name="theme_name"]').val(this.value).attr('readonly', true);
        $('[name="theme_name"]').addClass('tts-read-only');
        $("[tts-theme-id]").val("ANY");
        selectHolidayid=[];
    } else {
        $('[name="theme_name"]').val('').attr('readonly', false);
        $('[name="theme_name"]').removeClass('tts-read-only');
        $("[tts-theme-id]").val("");
    }
});





$(document).on("keydown", "[holiday-destination]", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    var method_name = $(this).attr('tts-method-name');
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: method_name,
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
        focus: function () {
            return false;
        },
        select: function (event, ui) {
             
            var currentValues = this.value.split(',').map(function (value) {
                return value.trim();
            });
            currentValues.pop();
          
      
            var selectedPackageName = ui.item['value'];

            if (!currentValues.includes(selectedPackageName)) {
                currentValues.push(selectedPackageName);
                currentValues.push("");
                this.value=currentValues.join(",");

                selectHolidayid.push(ui.item['id']);
                var id = selectHolidayid.join(",");
                $("[tts-destination-id]").val(id);
            } 

        
            return false; 


        },
    });
});

$(document).on("change", "[tts-destination-any]", function (event) {
    if (this.checked) {
        $('[name="destination_name"]').val(this.value).attr('readonly', true);
        $('[name="destination_name"]').addClass('tts-read-only');
        $("[tts-destination-id]").val("ANY");
        selectHolidayid=[];
    } else {
        $('[name="destination_name"]').val('').attr('readonly', false);
        $('[name="destination_name"]').removeClass('tts-read-only');
        $("[tts-destination-id]").val("");
    }
});



/* ***************** Use for this code holiday markup amd discount End  ********************* */
 

/* ***************** Use For This Code Common Markup amd Discount Start  ********************* */
$(document).on("keydown", "[tts-common-autocomplete='true']", function (event) {
    if (event.keyCode === $.ui.keyCode.TAB &&
        $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
    var method_name = $(this).attr('tts-method-name');
    $(this).autocomplete({
        minLength: 2,
        maxResults: 10,
        open: function () {
            $(".ui-autocomplete").addClass('tts-autocomplet');
        },
        source: function (request, response) {
            $.ajax({
                url: method_name,
                dataType: "json",
                cache: false,
                data: {
                    term: request.term.trim()
                },
                success: function (data) {
                    response(data)
                }
            });
        },
        open: function () {
            $(".ui-autocomplete").css('z-index', '9999');
        },
        focus: function () {
            return false;
        },
        select: function (event, ui) {

           let inputfield=$(this).siblings('input').eq(0); 
            var terms = split(this.value);
            var id= split(inputfield.val());
            id.pop();
            id.push(ui.item.id);
            id.push("");
            var ids = id.join(",");
            inputfield.val(ids);
          
            terms.pop();
            terms.push(ui.item.value);
            terms.push("");

            this.value = terms.join(",");

            return false;
        },
    });
});

$(document).on("change", "[tts-commnon-any]", function (event) {
    if (this.checked) {
        var resettag=$(this).attr('resettag');
        $("["+resettag+"]").parent().find('select[tts-common-autocomplete=true]').val(this.value).attr('readonly', true).addClass('tts-read-only');
        $("["+resettag+"]").val($(this).val());
    } else {
       var resettag=$(this).attr('resettag');
       $("["+resettag+"]").parent().find('select[tts-common-autocomplete=true]').val('').attr('readonly', false).removeClass('tts-read-only');
       $("["+resettag+"]").val('');
    }
});

$(document).on("change", "[tts-select-any]", function (event) {
    if (this.checked) {
        var resettag=$(this).attr('resettag');
        $("#"+resettag).parent().find('select').prop("disabled",true);
        $("#"+resettag).parent().find('select').nextAll('div.ms-options-wrap').find('button').prop("disabled",true)
    } else {
       var resettag=$(this).attr('resettag');
       $("#"+resettag).parent().find('select').prop("disabled",false);
       $("#"+resettag).parent().find('select').nextAll('div.ms-options-wrap').find('button').prop("disabled",false)
       
    }
});


/* ***************** Use For This Code Common Markup amd Discount End  ********************* */


/* ***************** Use For This Code VISA HIDE AND SHOW Agent & Customer NAME Start  ********************* */

$(document).on("change","[agent-customer]",function(event){
    if($(this).val() == 'B2B'){
        $('[agent-customer-show]').html(`<div class="form-group form-mb-20"> <label> Agent Name *</label> <input type="text" class="form-control" name="agent_info" value="" tts-get-agent-info="true" tts-error-msg="Please enter search type" placeholder="Agent Name" autocomplete="off"> <input type="hidden" name="tts_agent_info_id" tts-agent-info-id="true" value=""> <input type="hidden" name="tts_agent_info" tts-agent-info="true" value=""> <span class="success" agentinfo="true"></span> </div>`)
    }else if($(this).val() == 'B2C'){
        $('[agent-customer-show]').html(` <div class="form-group form-mb-20"> <label> Customer Name *</label> <input type="text" class="form-control" name="customer_info" value="" tts-get-customer-info="true" tts-error-msg="Please enter search type" placeholder="Customer Name" autocomplete="off"> <input type="hidden" name="tts_customer_info_id" tts-customer-info-id="true" value=""> <input type="hidden" name="tts_customer_info" tts-customer-info="true" value=""> <span class="success" customerinfo="true"></span> </div>`)
    }
})


$(document).on("click", "[visa-upload-add-passenger-abhay]", function (event) {
    var method_name = $(this).attr('tts-visa-passenger-method-name');
    var passenger_counter = $(this).attr('passenger-counter'); 
    if(passenger_counter<= 19)
    {  
        var url = site_url + method_name;
        var visainfokey = $("[name='visainfokey']").val();
        var reqdata = {
            'passenger_counter': passenger_counter,
            'visainfokey': visainfokey, 
        };

        $('[visa-upload-add-passenger-abhay]').attr('disabled', true);
        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    }else{
        alert("You can add maximum 20 passenger.");
    }

});


$(document).on("click", "[visa-upload-remove-passenger-abhay]", function (event) {
    var passenger_counter= $("[visa-upload-add-passenger-abhay]").attr("passenger-counter");
    if(passenger_counter > 1)
    {
        $("[passenger="+passenger_counter+"]").remove();
        passenger_counter--;
        $("[visa-upload-add-passenger-abhay]").attr("passenger-counter", passenger_counter); 
    } else {
        alert("At least one passenger is required");
    }
   
});


$(document).on("click", "[bus-upload-cancellation]", function (event) {
    var method_name = $(this).attr('tts-cancellation-method-name');
    var cancellation_counter = $(this).attr('cancellation-counter');  

    if(cancellation_counter<= 7)
    {
        var url = site_url + method_name; 
        var reqdata = {
            'cancellation_counter': cancellation_counter, 
        };
        $('[bus-upload-cancellation]').attr('disabled', true);
        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    } else{
        alert("Maximum 8 Cancellation Policy allowed");
    }

});

$(document).on("click", "[bus-upload-cancellation-remove]", function (event) {
    var cancellation_counter= $("[bus-upload-cancellation]").attr("cancellation-counter");
    if(cancellation_counter > 1)
    {
        $("[cancellation-count-row="+cancellation_counter+"]").remove();
        cancellation_counter--;
        $("[bus-upload-cancellation]").attr("cancellation-counter", cancellation_counter); 
    } else {
        alert("at least one cancellation policy is required");
    }
   
});

$(document).on("click", "[bus-upload-passenger]", function (event) {
    var method_name = $(this).attr('tts-passenger-method-name');
    var passenger_counter = $(this).attr('passenger-counter');  

    if(passenger_counter<= 5)
    { 
        var url = site_url + method_name; 
        var reqdata = {
            'passenger_counter':passenger_counter, 
        };

        $('[bus-upload-passenger]').attr('disabled', true);

        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    } else {
        alert("Maximum 6 Passanger allowed");
    }

});


$(document).on("click", "[bus-upload-passenger-remove]", function (event) {
    var pax_counter= $("[bus-upload-passenger]").attr("passenger-counter");
    if(pax_counter > 1)
    {
        $("[pax-count-row="+pax_counter+"]").remove();
        pax_counter--;
        $("[bus-upload-passenger]").attr("passenger-counter", pax_counter); 
    } else {
        alert("at least one passanger is required");
    }
   
});

/* ***************** Use For This Code Activities Markup amd Discount End  ********************* */


/* ***************** Start Hotel Upload Ticket  ********************* */

$(document).on("click", "[hotel-upload-add-room]", function (event) {
    var method_name = $(this).attr("tts-hotel-upload-method-name");
    var room_counter = $(this).attr('room-counter');  
    if(room_counter<= 3)
    {
        $('[hotel-upload-add-room]').attr('disabled', true);
        var url = site_url + method_name;
        var reqdata = {
            'room_counter': room_counter,
            'id': $("#id").val(),
        };
        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    } else {
        alert("you can add maximum 4 rooms.");
    }
  
});

$(document).on("click", "[hotel-upload-romm-remove]", function (event) {
    var room_counter= $("[hotel-upload-add-room]").attr("room-counter");
    if(room_counter > 1)
    {
        $("[room="+room_counter+"]").remove();
        room_counter--;
        $("[hotel-upload-add-room]").attr("room-counter", room_counter); 
    } else {
        alert("at least one room is required");
    }
   
});
$(document).on("click", "[hotel-upload-add-adt-pax]", function (event) {
    var adt_counter = $(this).attr("pax-adt-counter");     
    if(adt_counter<= 3)
    {       
        $("[hotel-upload-add-adt-pax]").attr('disabled', true);

        var method_name = $(this).attr("tts-hotel-upload-method-name");
        var room_counter = $(this).attr("room-counter");  
        var pax_type = $(this).attr("pax-type");  
    
        var url = site_url + method_name;
        var reqdata = {
            'room_counter': room_counter,
            'pax_type': pax_type,
            'adt_counter': adt_counter,
            'passport_required': $("#passport_required").val(),
            'pan_required': $("#pan_required").val(),
            'id': $("#id").val(),
        };
        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
     } else {
        alert("you can add maximum 4 adults.");
     }
});
$(document).on("click", "[hotel-upload-add-chd-pax]", function (event) {
  
    var chd_counter = $(this).attr("pax-chd-counter");  

    if(chd_counter<= 1)
    {
    
        $("[hotel-upload-add-chd-pax]").attr('disabled', true);

        var method_name = $(this).attr("tts-hotel-upload-method-name");
        var room_counter = $(this).attr("room-counter");    
       
        var pax_type = $(this).attr("pax-type");  

        var url = site_url + method_name;
        var reqdata = {
            'room_counter': room_counter,
            'pax_type': pax_type,
            'chd_counter': chd_counter,
            'passport_required': $("#passport_required").val(),
            'pan_required': $("#pan_required").val(),
            'id': $("#id").val(),
        };
        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    } else {
        alert("you can add maximum 2 childs.");
    }
});
/* ***************** End Hotel Upload Ticket  ********************* */



//** * ********************************* Use For Flight Upload Ticket And Reissue   Harish   ***************************** *//
$(document).on("click", "[flight-ticket-upload-add-trip]", function (event) {
    var method_name = $(this).attr('tts-method-name');
    var trip_indicator_counter = $("[flight-ticket-upload-trip-indicator-couter]").val();
    var url = site_url + method_name;
    var reqdata = {
        'trip_indicator': trip_indicator_counter,
    };
    TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);

});
$(document).on("click", "[flight-ticket-upload-add-segment-harish]", function (event) {
    var method_name = $(this).attr('tts-method-name');
    var segmentIndicator = $(this).attr('segment-indicator');
    var tripIndicator = $(this).attr('tripindicator');
    var url = site_url + method_name;

    var reqdata = {
        'segmentIndicator': segmentIndicator,
        'tripIndicator': tripIndicator,
    };

    TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);

});

$(document).on("click", "[flight-ticket-upload-add-passenger-harish]", function (event) {


    var method_name = $(this).attr('tts-passenger-method-name');
    var passenger_counter = $(this).attr('passenger-counter');
    var pax_type = $(this).attr('pax-type');
    var pax_type_count_value = $(this).attr("passenger-counter-" + pax_type);
    var url = site_url + method_name;
    var temptripSegmentId = $("[name='temptripSegmentId']").val();
    var reqdata = {
        'passenger_counter': passenger_counter,
        'temptripSegmentId': temptripSegmentId,
        'pax_type': pax_type,
        'pax_type_count_value': pax_type_count_value,
    };

    TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);

});
$(document).on("click", "[tts-flight-ticket-upload-meal-seclect]", function (e) {
    var paxkeyValue = $(this).attr("pax-key");
    if ($(this).is(':checked')) {
        $("[pax_" + paxkeyValue + "]").show();
    } else {
        $("[pax_" + paxkeyValue + "]").hide();
    }
});
/* upload and import ticket  script start*/
$(document).on("click", "[tts-flight-ticket-upload-showpax-info]", function (e) {
    var paxkeyValue = $(this).attr("pax-key");
    if ($(this).is(':checked')) {
        $("[showpaxinfo_" + paxkeyValue + "]").show();
    } else {
        $("[showpaxinfo_" + paxkeyValue + "]").hide();
    }
});
$(document).on("focus", "[harish-upload-import-from-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: false,
        numberOfMonths: 1,
        minDate: "0",
    });
});
$(document).on("focus", "[harish-upload-import-to-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: false,
        numberOfMonths: 1,
        minDate: "0",
        beforeShow: function () {
            /*  var dateString = $('[harish-upload-import-from-date]').val();
             var newdate = new Date(newdate);
             alert(dateString);
             $(this).datepicker("option", "minDate", newdate); */
        },
        onClose: function (selectedDate) {
            $("[harish-upload-import-from-date]").datepicker("option", selectedDate);
        }
    });

});
/* upload and import ticket  script end*/
$(document).on("click", "[data-datetimer-from]", function () {
    $(this).datetimepicker({
        ownerDocument: document,
        contentWindow: window,
        rtl: false,
        format: 'd-m-Y H:i',
        formatTime: 'H:i',
        formatDate: 'd-m-Y',
        lazyInit: false,
        openOnFocus: true,
        timepicker: true,
        datepicker: true,
        step: 1,
        minDate: 0,
    });
});
$(document).on("click", "[data-datetimer-to]", function () {
    $(this).datetimepicker({
        ownerDocument: document,
        contentWindow: window,
        rtl: false,
        format: 'd-m-Y H:i',
        formatTime: 'H:i',
        formatDate: 'd-m-Y',
        step: 1,
        openOnFocus: true,
        timepicker: true,
        datepicker: true,
        lazyInit: false,
        minDate: 0
    });
});
function remove_more_segment(thisval, id) {

    var lists = document.querySelectorAll("#" + id + "> .tts-segment-row");

    if (lists.length > 1) {
        if (confirm('Do you want to delete item ?')) {
            $(thisval).parent().parent().parent().parent().remove();


            let counter = $("[segment_counter]").val();
            counter = counter - 1;
            $("[segment_counter]").val(counter)

        }
    } else {
        alert("You can't delete at least 1 item");
    }
}


function remove_more_passenger(thisval, id, paxType) {

    var lists = document.querySelectorAll("#" + id + "> .tts-passenger-row");

    if (lists.length > 1) {
        if (confirm('Do you want to delete item ?')) {
            $(thisval).parent().parent().parent().parent().remove();
            let counter = $("[passenger_counter]").val();
            counter = counter - 1;
            $("[passenger_counter]").val(counter)
            let paxtypeCountValue = $("[flight-ticket-upload-add-passenger-harish]").attr("passenger-counter-" + paxType);
            paxtypeCountValue = paxtypeCountValue - 1;
            $("[flight-ticket-upload-add-passenger-harish]").attr("passenger-counter-" + paxType, (paxtypeCountValue));
            if (paxtypeCountValue == 0) {
                $("[tts-call-put-passenger-" + paxType + "-pricing-html]").html('');
                $("[" + paxType + "-pricing-status]").attr('no');
            }

        }
    } else {
        alert("You can't delete at least 1 item");
    }
}

//** * *********************************  Use For Flight Upload Ticket And Reissue END  Harish   ***************************** *//


$(document).on("change", "[privatefare-call-select]", function (event) {
    var journey_type = $(".journey_type option:selected").val();
    var trip_type = $(".trip_type option:selected").val();
    var onward_stops = null;
    var return_stops = null;
    /* if (journey_type == "roundtrip" && trip_type == 'domestic') {
        $("[return-segnemt-hide]").removeClass('hide');
        onward_stops = $(".onward_stops option:selected").val();
        return_stops = $(".return_stops option:selected").val();
    } */
    if (journey_type == "roundtrip" && trip_type == 'international') {
        $("[return-segnemt-hide]").removeClass('hide');
        onward_stops = $(".onward_stops option:selected").val();
        return_stops = $(".return_stops option:selected").val();
    } else {
        $("[return-segnemt-hide]").addClass('hide');
        onward_stops = $(".onward_stops option:selected").val();
    }

    var method_name = $(this).attr('tts-method-name');
    var data_key = $(this).attr('data-key');
    var url = site_url + method_name;
    if (typeof method_name !== 'undefined' && trip_type != '') {
        var reqdata = {
            'journey_type': journey_type,
            'trip_type': trip_type,
            'onward_stops': onward_stops,
            'return_stops': return_stops,
            'data_key': data_key
        };

        TTSGLOBAL.global.callajax(url, 'Post', reqdata, method_name);
    } else {
        alert('Please select Trip Type');
    }
});

$(document).on("change", "[privatefare-trip-type-select]", function (event) {
    var trip_type = $(".trip_type option:selected").val();
    $("[tts-call-put-html]").html(' ');
    if (trip_type != '') {
        if (trip_type == 'international') {
            var journey_type = ' <option value="oneway">Oneway</option><option value="roundtrip">Round trip</option>';
        } else {
            var journey_type = '<option value="oneway">Oneway</option>';
        }
        $(".journey_type").html(journey_type);
    } else {
        alert('Please select Trip Type');
    }
});
$(document).on("change", "[privatefare-journey-type-select]", function (event) {
    var journey_type = $(".journey_type option:selected").val();
    var trip_type = $(".trip_type option:selected").val();
    $("[tts-call-put-html]").html('');
    var method_name  =  "private-fare/add-trip-details";
    var url = site_url + method_name;
        if (trip_type == 'international'&&  journey_type=="roundtrip") 
        {
            var  reqdata  =  {};
            TTSGLOBAL.global.callajax(url, 'Post', reqdata, 'private-fare/add-trip-details');
        } 
});

function showHidefareRule(key, buttonkey = "") {
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

function amendment_status_modal(id, company) {
    ttsopenmodel('amendment_status_change');
    $(".amendment_id").val(id);
    $(".tts_agent_company").html(company);
}

function flight_refund_close_modal(id, company) {
    ttsopenmodel('flight_refund_close');
    $(".amendment_id").val(id);
    $(".tts_agent_company").html(company);
}

//** * *********************************  Print Function   ***************************** *//
function print_stvinv(divName) {
   
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

//** * *********************************   Print Function    ***************************** *//
$(document).on("focus", "[praveen-from-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: "d-M-y",
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
    });
});
$(document).on("focus", "[praveen-to-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: "d-M-y",
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function () {
            var dateString = $('[praveen-from-date]').val();
            var newdate = dateString.split(" ").join("-");
            var newdate = new Date(newdate);
            $(this).datepicker("option", "minDate", newdate);
        },
        onClose: function (selectedDate) {
            $("[praveen-from-date]").datepicker("option", selectedDate);
        }
    });

});

$(document).ready(function () {
    $("[tts-input-box-value]").on("keyup", function () {
        var taget=$(this).attr('tts-input-box-value');
        var value = $(this).val().toLowerCase();
        $("["+taget+"]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});


$(document).on("change", "#dayPrice", function (e) {
    let value = parseInt($(this).val());
    const copyLabel = document.querySelectorAll(".copyLabel");
    copyLabel.forEach((label) => {
      label.remove();
    });
    if (Number.isInteger(value) && parseInt(value) > 0) {
      $(
        '<label for="" class="copyLabel"><input type="checkbox" id="copyPrice"> Copy to All</label>'
      ).insertBefore("#dayPrice");
    } else {
      copyLabel.forEach((label) => {
        label.remove();
      });
    }
  });
  
  $(document).on("click", "#copyPrice", function () {
    var days = ["tue", "wed", "thu", "fri", "sat", "sun"];
    if ($(this).is(":checked")) {
      days.forEach((day) => {
        $('[name="' + day + '"]').val($("#dayPrice").val());
      });
    } else {
      days.forEach((day) => {
        $('[name="' + day + '"]').val("");
      });
    }
  });



  $(document).on("change", "#childdayPrice", function (e) {
    let value = parseInt($(this).val());
    const copyLabel = document.querySelectorAll(".copyLabel");
    copyLabel.forEach((label) => {
      label.remove();
    });
    if (Number.isInteger(value) && parseInt(value) > 0) {
      $(
        '<label for="" class="copyLabel"><input type="checkbox" id="childcopyPrice"> Copy to All</label>'
      ).insertBefore("#childdayPrice");
    } else {
      copyLabel.forEach((label) => {
        label.remove();
      });
    }
  });
  
  $(document).on("click", "#childcopyPrice", function () {
    var days = ["tue_child","wed_child","thu_child","fri_child","sat_child","sun_child"];
    if ($(this).is(":checked")) {
      days.forEach((day) => {
        $('[name="' + day + '"]').val($("#childdayPrice").val());
      });
    } else {
      days.forEach((day) => {
        $('[name="' + day + '"]').val("");
      });
    }
  });





  $(document).on("focus", "[activity-destination-location]", function (event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'activities/get-location-activity',
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

            $('input[name="location_id"]').val(ui.item.id); 
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        }

    });
});

$(document).on("click", "[activity-destination-location]", function (event) {
    setTimeout(() => {
        event.target.select();
        $(event.target).autocomplete("search", " ");
    }, 10);
});



$(document).on("focus", "[flight-departure-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: 'dd M yy',
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        onClose: function (selectedDate, inst) {
            var d = new Date(selectedDate);
            var dayName = days[d.getDay()];
            $(event.target).siblings(".flight_text_p").text(dayName);

            var newDate = new Date(d.getTime() + 86400000);
            var newDateString = $.datepicker.formatDate('dd M yy', newDate);
            $("[flight-return-date]").val(newDateString);
            $("[flight-return-date]").datepicker("option", "minDate", newDate).focus().select();
        }
    });
});

$(document).on("focus", "[flight-return-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: 'dd M yy',
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function () {
            var dateString = $('[flight-departure-date]').val();
            var newDate = new Date(dateString);
            $(this).datepicker("option", "minDate", newDate);
        },
        onClose: function (selectedDate) {
            var d = new Date(selectedDate);
            var dayName = days[d.getDay()];
            $(event.target).siblings(".flight_text_p").text(dayName);
        }
    });
});



