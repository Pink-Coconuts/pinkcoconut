// uri segment
var uri_segment = (function(a) {
    if (a == "") return {};
    var b = {};
    for (var i = 0; i < a.length; ++i) {
        var p = a[i].split('=', 2);
        if (p.length == 1)
            b[p[0]] = "";
        else
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
    }
    return b;
})(window.location.search.substr(1).split('&'));

if (uri_segment['visited'] != 1) {
    var end = new Date(document.getElementById('expired_at').value).getTime();

    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;

    function showRemaining() {
        var d = new Date();
        var now = new Date(
            d.getUTCFullYear(),
            d.getUTCMonth(),
            d.getUTCDate(),
            d.getUTCHours(),
            d.getUTCMinutes(),
            d.getUTCSeconds()
        ).getTime();

        var distance = end - now;
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById('countdown_2').innerHTML = 'Expired!';
            document.getElementById('countdown_1').innerHTML = 'Expired!';
            window.location.href = APP_URL + '/reservation/expire/' + $('#reservation_id').val();
            return;
        }
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);

        if ($("#countdown_2").length !== 0) {
            document.getElementById('countdown_2').innerHTML = hours + ':';
            document.getElementById('countdown_2').innerHTML += minutes + ':';
            document.getElementById('countdown_2').innerHTML += seconds + '';

            document.getElementById('countdown_1').innerHTML = hours + ':';
            document.getElementById('countdown_1').innerHTML += minutes + ':';
            document.getElementById('countdown_1').innerHTML += seconds + '';
        }
    }

    timer = setInterval(showRemaining, 1000);

    $(document).ready(function() {
        $("[id$='-trigger']").click(function() {
            var id = '#' + $(this).attr('id').replace('-trigger', '');

            $("#reserve_id").val(id);
            $(id).removeClass('hide');
            $(id).addClass('show');
            $(id).attr('aria-hidden', 'false');
        });

        $('[data-behavior="modal-close"]').click(function(event) {
            event.preventDefault();
            $('.modal').removeClass('show');
            $('.modal').attr('aria-hidden', 'true');
        });

        $('#decline_submit').click(function(){
    var cancel = $('#decline_reason');
    var msg =  $('#decline_message');
    var other_reason = $('#decline_reason_other');

    if(cancel.val() == '' && msg.val() == '' )
    {
        $('#errors').removeClass('hide');
        $('#decline_message').focus();
        return false;
    }
    else if(cancel.val() == 'other' && other_reason.val() == '' )
    {
        $('#other_errors').removeClass('hide');
        $('#decline_reason_other').focus();
        return false;
    }    
});

        $("#decline_message").keyup(function () {
if($('#decline_reason').val() != '' || $('#decline_message').val() != '')
    {
       $('#errors').addClass('hide');
    }
    });

        $("#decline_reason_other").keyup(function () {
if($('#decline_reason_other').val() != '')
    {
       $('#other_errors').addClass('hide');
    }
    });

        $('#decline_reason').change(function() {
            if ($(this).val() == 'other') {
                $('#decline_reason_other_div').removeClass('hide');
                $('#decline_reason_other_div').addClass('show');
                $('#errors').addClass('hide');
            } else {
                $('#decline_reason_other_div').removeClass('show');
                $('#decline_reason_other_div').addClass('hide');
                $('#errors').addClass('hide');
            }

    if(($('#decline_reason').val() != 'other' && $('#decline_reason').val() != '') || $('#decline_message').val() != '')
    {
       $('#errors').addClass('hide');
    }
        });

    });
}

app.controller('host_change', ['$scope', '$http', function($scope, $http) {

    setTimeout(function() {


        var data = $scope.room_id;

        var room_id = $scope.room_id;

        var checkin = $("#start_date").val();
        var checkout = $("#end_date").val();


        $http.post('../rooms/rooms_calendar_alter', {
            data: data,
            checkin: checkin,
            checkout: checkout
        }).then(function(response) {
            var changed_price = response.data.changed_price;
            var array = response.data.not_avilable;

            $('#start_date').datepicker({
                minDate: 0,
                dateFormat: 'dd-mm-yy',
                setDate: new Date($('#start_date').val()),
                beforeShowDay: function(date) {
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    if ($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onSelect: function(date) {
                    var checkout = $('#start_date').datepicker('getDate');
                    checkout.setDate(checkout.getDate() + 1);
                    $('#end_date').datepicker('setDate', checkout);
                    $('#end_date').datepicker('option', 'minDate', checkout);

                    setTimeout(function() {
                        $("#end_date").datepicker("show");
                    }, 20);

                    var checkin = $(this).val();
                    var checkout = $("#end_date").val();
                    var guest = $("#pricing_guests").val();
                    calculation(checkout, checkin, guest, room_id);

                    if (date != new Date()) {
                        $('.ui-datepicker-today').removeClass('ui-datepicker-today');
                    }
                }
            });

            jQuery('#end_date').datepicker({
                minDate: 1,
                dateFormat: 'dd-mm-yy',
                setDate: new Date($('#end_date').val()),
                beforeShowDay: function(date) {
                    var date = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    if ($.inArray(date, array) != -1)
                        return [false];
                    else
                        return [true];
                },
                onClose: function() {
                    var checkin = $('#start_date').datepicker('getDate');
                    var checkout = $('#end_date').datepicker('getDate');

                    if (checkout <= checkin) {
                        var minDate = $('#end_date').datepicker('option', 'minDate');
                        $('#end_date').datepicker('setDate', minDate);
                    }

                    var checkout = $(this).val();
                    var checkin = $("#start_date").val();
                    var guest = $("#pricing_guests").val();

                    if (checkin != '') {
                        calculation(checkout, checkin, guest, room_id);
                    }
                }
            });

        });

        $("#pricing_guests").change(function() {

            var guest = $(this).val();
            var checkin = $("#start_date").val();
            var checkout = $("#end_date").val();

            if (checkin != '' && checkout != '') {
                $('.js-book-it-status').addClass('loading');
                calculation(checkout, checkin, guest, room_id);
            }
        });

        $("#property").change(function() {
            var room_id = $(this).val();
            var guest = $("#pricing_guests").val();
            var checkin = $("#start_date").val();
            var checkout = $("#end_date").val();

            if (checkin != '' && checkout != '') {
                $('.js-book-it-status').addClass('loading');
                calculation(checkout, checkin, guest, room_id);
            }
        });

    }, 10);


    function calculation(checkout, checkin, guest, room_id) {
        $('.special-offer-date-fields').addClass('loading');
        var change_reservation = $("#reservation_code").val();

        $http.post('../rooms/price_calculation', {
            checkin: checkin,
            checkout: checkout,
            guest_count: guest,
            room_id: room_id,
            change_reservation: change_reservation
        }).then(function(response) {
            $('.special-offer-date-fields').removeClass('loading');
            $('#total_price').val(response.data.subtotal);
        });
    }

    $("#alter-submit-button").click(function() {
        $("#chage_confirm").attr("aria-hidden", "false");

    });
    $("#cancellation-policy-link").click(function() {
        $("#cancellation_policy").show();

    });
    $("#no_alteration").click(function() {
        $("#chage_confirm").attr("aria-hidden", "true");

    });
    $("#alteration-submit").click(function() {
        $("#details-form").submit();
    });
}]);