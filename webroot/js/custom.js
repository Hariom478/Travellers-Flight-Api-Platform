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
        select2search: function () {
            $('.select_search').select2();
        },



        select2ajax: function () {
            let methodName = $("[tts-select2-ajax]").attr('tts-method-name')
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



     /*   packageAccordion: function () {
            $("#tts-package-accordion").accordion();
        },
*/
      /*  verticalTabs: function () {
            $("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
            $("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");
        },*/
        callajax: function (url, method, reqdata) {
            $.ajax({
                url: url,
                method: method,
                data: reqdata,
                cache: false,
                success: function (resp) {
                    $("[tts-call-put-html]").html(resp);
                },
                error: function (res) {
                    alert("Unexpected error! Try again.");
                }
            });
        },

        AddWaterMark:function () {
            let company_id = $("#web-partner-company-id").val();
            let agentid =company_id;
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
        TTSGLOBAL.global.select2search();
        TTSGLOBAL.global.select2ajax();
        //TTSGLOBAL.global.verticalTabs();
        //TTSGLOBAL.global.packageAccordion();

        TTSGLOBAL.global.editor();
        TTSGLOBAL.global.AddWaterMark();

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
        var form = $(this);
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
                        if (key.indexOf(".") !== -1) {
                            var finalkey = key.split(".");
                            if (finalkey[4]) {
                                key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]" + "[" + finalkey[3] + "]" + "[" + finalkey[4] + "]";
                            }
                            else if (finalkey[3]) {
                                key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]"+ "[" + finalkey[3] + "]";
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
                                if (a.value != '') {
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

    // $(window).scroll(function () {
    //     scroll = $(window).scrollTop();
    //     if (scroll >= 100) {
    //         $(".header-with").removeClass('d-none');
    //         $(".header").addClass('sticky-top');

    //         $(".tts-logo-white").hide();
    //         $(".tts-logo-ragular").show();
      
    //     } else {
    //         $(".header-with").addClass('d-none');
    //         $(".header").removeClass('sticky-top');

    //         $(".tts-logo-white").show();
    //         $(".tts-logo-ragular").hide();

    //     }
    // });

    $("[view-data-modal]").on("click", function (e) {
        e.preventDefault();
        $("[data-modal-view='view_modal_data']").html('<div class="text_center"><p><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" hideloader="true" aria-hidden="true" focusable="false" width="50" height="50" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" class="rotating" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><circle cx="12" cy="20" r="1" fill="#626262"/><circle cx="12" cy="4" r="1" fill="#626262"/><circle cx="6.343" cy="17.657" r="1" fill="#626262"/><circle cx="17.657" cy="6.343" r="1" fill="red"/><circle cx="4" cy="12" r="1.001" fill="green"/><circle cx="20" cy="12" r="1" fill="#626262"/><circle cx="6.343" cy="6.344" r="1" fill="#626262"/><circle cx="17.657" cy="17.658" r="1" fill="#626262"/></svg></p> <p>Please wait a few seconds</p></div>');
        $("[data-message]").removeClass().html("");
        var url = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        var view_data_modal = $(this).attr('view-data-modal');
        var entity_type = $(this).attr('data-controller');
        if (view_data_modal == 'true') {
            ttsopenmodel('common_modal');
        }else{
            ttsopenmodel('view_' + entity_type);
        }

        $.ajax({
            url: url,
            type: "post",
            data: {id: id, entity_type: entity_type},
            success: function (resp) {
                if (resp) {
                    if (resp.StatusCode == 0) {
                        $("[data-modal-view='view_modal_data']").html(resp.Message);
                        TTSGLOBAL.global.editor();
                        TTSGLOBAL.global.select2search();

                        // reset list checkbox
                        $("#selectall").prop('checked', false);
                        $('input[name="checklist[]').prop('checked', false);

                    } else if (resp.StatusCode == 7) {
                        $("[tts-append-html]").append(resp.Html_data);
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
                $(".ui-autocomplete").css('z-index', '999');
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


    $(document).on("change", "[tts-call-select]", function (event) {
        var selval = $(this).find(":selected").val();
        var seltext = $(this).find(":selected").text().trim();
        var method_name = $(this).attr('tts-method-name');
        var url = site_url + method_name;
        if (typeof method_name !== 'undefined') {
            var reqdata = {'country_id': selval, 'country_name': seltext};
            TTSGLOBAL.global.callajax(url, 'Post', reqdata);
        } else {
            alert('Please define method name');
        }
    });
    $(document).on("change", "[tts-cruise-select]", function (event) {
        var selval = $(this).find(":selected").val();
        var seltext = $(this).find(":selected").text().trim();
        var method_name = $(this).attr('tts-method-name');
        var url = site_url + method_name;
        if (typeof method_name !== 'undefined') {
            var reqdata = {'cruise_line_id': selval, 'cruise_line_name': seltext};
            TTSGLOBAL.global.callajax(url, 'Post', reqdata);
        } else {
            alert('Please define method name');
        }
    });
});

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
            var terms = split( this.value );
            var id= split(inputfield.val());
            id.pop();
            id.push( ui.item.id );
            id.push( "" );
            var ids = id.join(",");
            inputfield.val(ids);
          
            terms.pop();
            terms.push( ui.item.value );
            terms.push( "" );
            this.value = terms.join( ", " );

            return false;
        },
    });
});

$(document).on("change", "[tts-commnon-any]", function (event) {
    if (this.checked) {
        var resettag=$(this).attr('resettag');
        $("["+resettag+"]").parent().find('input[tts-common-autocomplete=true]').val(this.value).attr('readonly', true).addClass('tts-read-only');
        $("["+resettag+"]").val($(this).val());
    } else {
       var resettag=$(this).attr('resettag');
       $("["+resettag+"]").parent().find('input[tts-common-autocomplete=true]').val('').attr('readonly', false).removeClass('tts-read-only');
       $("["+resettag+"]").val('');
    }
});


/* ***************** Use For This Code Common Markup amd Discount End  ********************* */


var modal;

function ttsopenmodel(modelid) {
   $("#"+modelid).modal('show');
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


function tts_slug_url(val, id) {
    var str = val.trim();
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();
    // remove accents, swap ñ for n, etc
    var from = "ãàáäâáº½èéëêìíïîõòóöôùúüûñç·/_,:;";
    var to = "aaaaaeeeeeiiiiooooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }
    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes
    document.getElementById(id).value = str;

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
    document.forms['form_password_change']['password'].value = "";
    document.getElementById('password_change').style.display = "block";
    $(".tts_agent_id").val(id);
    $(".tts_agent_company").html(company);
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
function showHide(id){
    $("."+id).toggleClass("d-none");
    $("#"+id).after().toggleClass("d-none");
    let moreLess =  $("["+id+"]").html();
   if (moreLess == "View More.."){
       $("["+id+"]").html('Hide');
   }else if (moreLess == "Hide"){
       $("["+id+"]").html('View More..');
   }else  {
       $("["+id+"]").html('Hide');
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

$('.holiday_slider').slick({
    dots: true,
    infinite: true,
    speed: 100,
    slidesToShow: 8,
    slidesToScroll: 8,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
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