(function ($) {
    "use strict";
    var G5PlusCountdown = {
        init: function () {
            G5PlusCountdown.count_down();
        },
        count_down: function () {
            $('.gel-countdown').each(function () {
                var date_end = $(this).data('date-end');
                var $this = $(this);
                $this.countdown(date_end, function (event) {
                    count_down_callback(event, $this);
                }).on('update.countdown', function (event) {
                    count_down_callback(event, $this);
                }).on('finish.countdown', function (event) {
                    $('.gel-countdown-seconds.gel-countdown-value', $this).html('00');
                });
            });

            function count_down_callback(event, $this) {
                var seconds = parseInt(event.offset.seconds),
                    minutes = parseInt(event.offset.minutes),
                    hours = parseInt(event.offset.hours),
                    days = parseInt(event.offset.totalDays);

                var g = 0;
                if (days < 10) days = '0' + days;
                if (hours < 10) hours = '0' + hours;
                if (minutes < 10) minutes = '0' + minutes;
                if (seconds < 10) seconds = '0' + seconds;

                $('.gel-countdown-day.gel-countdown-value', $this).text(days);
                $('.gel-countdown-hours.gel-countdown-value', $this).text(hours);
                $('.gel-countdown-minutes.gel-countdown-value', $this).text(minutes);
                $('.gel-countdown-seconds.gel-countdown-value', $this).text(seconds);

                if (g == 0) {
                    $("input", $this).knob();
                    g = 1
                }
                setTimeout(function () {
                    $this.css("opacity", "1")
                }, 500);
                $("#days", $this).val(days).trigger("change");
                $("#hours", $this).val(hours).trigger("change");
                $("#minutes", $this).val(minutes).trigger("change");
                $("#seconds", $this).val(seconds).trigger("change");
            }
        }
    };
    $(document).ready(function () {
        G5PlusCountdown.init()
    });
})(jQuery);