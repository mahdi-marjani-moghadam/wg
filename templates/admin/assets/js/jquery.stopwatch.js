/*

 JQUERY: STOPWATCH & COUNTDOWN

 This is a basic stopwatch & countdown plugin to run with jquery. Start timer, pause it, stop it or reset it. Same behaviour with the countdown besides you need to input the countdown value in seconds first. At the end of the countdown a callback function is invoked.

 Any questions, suggestions? marc.fuehnen(at)gmail.com

 */
(function($){

    $.extend({

        stopwatch : {

            formatTimer : function(a) {
                if (a < 10) {
                    a = '0' + a;
                }
                return a;
            },

            startTimer : function(dir) {

                var a;

// save type
                $.stopwatch.dir = dir;

// get current date
                $.stopwatch.d1 = new Date();

                switch($.stopwatch.state) {

                    case 'pause' :

// resume timer
// get current timestamp (for calculations) and
// substract time difference between pause and now
                        $.stopwatch.t1 = $.stopwatch.d1.getTime() - $.stopwatch.td;

                        break;

                    default :

// get current timestamp (for calculations)
                        $.stopwatch.t1 = $.stopwatch.d1.getTime();

// if countdown add ms based on seconds in textfield
                        if ($.stopwatch.dir === 'cd') {
                            $.stopwatch.t1 += parseInt($('#cd_seconds').val())*1000;
                        }

                        break;

                }

// reset state
                $.stopwatch.state = 'alive';
                $('#' + $.stopwatch.dir + '_status').html('Running');

// start loop
                $.stopwatch.loopTimer();

            },

            pauseTimer : function() {

// save timestamp of pause
                $.stopwatch.dp = new Date();
                $.stopwatch.tp = $.stopwatch.dp.getTime();

// save elapsed time (until pause)
                $.stopwatch.td = $.stopwatch.tp - $.stopwatch.t1;

// change button value
                $('#' + $.stopwatch.dir + '_start').val('Resume');

// set state
                $.stopwatch.state = 'pause';
                $('#' + $.stopwatch.dir + '_status').html('Paused');

            },

            stopTimer : function() {

// change button value
                $('#' + $.stopwatch.dir + '_start').val('Restart');

// set state
                $.stopwatch.state = 'stop';
                $('#' + $.stopwatch.dir + '_status').html('Stopped');

            },

            resetTimer : function() {

// reset display
                $('#' + $.stopwatch.dir + '_ms,#' + $.stopwatch.dir + '_s,#' + $.stopwatch.dir + '_m,#' + $.stopwatch.dir + '_h').html('00');

// change button value
                $('#' + $.stopwatch.dir + '_start').val('Start');

// set state
                $.stopwatch.state = 'reset';
                $('#' + $.stopwatch.dir + '_status').html('Reset & Idle again');

            },

            endTimer : function(callback) {

// change button value
                $('#' + $.stopwatch.dir + '_start').val('Restart');

// set state
                $.stopwatch.state = 'end';

// invoke callback
                if (typeof callback === 'function') {
                    callback();
                }

            },

            loopTimer : function() {

                var td;
                var d2,t2;

                var ms = 0;
                var s  = 0;
                var m  = 0;
                var h  = 0;

                if ($.stopwatch.state === 'alive') {

// get current date and convert it into
// timestamp for calculations
                    d2 = new Date();
                    t2 = d2.getTime();

// calculate time difference between
// initial and current timestamp
                    if ($.stopwatch.dir === 'sw') {
                        td = t2 - $.stopwatch.t1;
// reversed if countdown
                    } else {
                        td = $.stopwatch.t1 - t2;
                        if (td <= 0) {
// if time difference is 0 end countdown
                            $.stopwatch.endTimer(function(){
                                $.stopwatch.resetTimer();
                                $('#' + $.stopwatch.dir + '_status').html('Ended & Reset');
                            });
                        }
                    }

// calculate milliseconds
                    ms = td%1000;
                    if (ms < 1) {
                        ms = 0;
                    } else {
// calculate seconds
                        s = (td-ms)/1000;
                        if (s < 1) {
                            s = 0;
                        } else {
// calculate minutes
                            var m = (s-(s%60))/60;
                            if (m < 1) {
                                m = 0;
                            } else {
// calculate hours
                                var h = (m-(m%60))/60;
                                if (h < 1) {
                                    h = 0;
                                }
                            }
                        }
                    }

// substract elapsed minutes & hours
                    ms = Math.round(ms/100);
                    s  = s-(m*60);
                    m  = m-(h*60);

// update display
                    $('#' + $.stopwatch.dir + '_ms').html($.stopwatch.formatTimer(ms));
                    $('#' + $.stopwatch.dir + '_s').html($.stopwatch.formatTimer(s));
                    $('#' + $.stopwatch.dir + '_m').html($.stopwatch.formatTimer(m));
                    $('#' + $.stopwatch.dir + '_h').html($.stopwatch.formatTimer(h));

// loop
                    $.stopwatch.t = setTimeout($.stopwatch.loopTimer,1);

                } else {

// kill loop
                    clearTimeout($.stopwatch.t);
                    return true;

                }

            }

        }

    });

})(jQuery);
