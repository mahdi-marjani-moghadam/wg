$(function(){
	'use strict';

	
	
    // DASHBOARD DEMO
    // skycons.set("clear-day", Skycons.CLEAR_DAY);
    // skycons.set("clear-night", Skycons.CLEAR_NIGHT);
    // skycons.set("partly-cloudy-day", Skycons.PARTLY_CLOUDY_DAY);
    // skycons.set("partly-cloudy-night", Skycons.PARTLY_CLOUDY_NIGHT);
    // skycons.set("cloudy", Skycons.CLOUDY);
    // skycons.set("rain", Skycons.RAIN);
    // skycons.set("sleet", Skycons.SLEET);
    // skycons.set("snow", Skycons.SNOW);
    // skycons.set("wind", Skycons.WIND);
    // skycons.set("fog", Skycons.FOG);
    // 
    if($(document).find('#dashboard-weather').length > 0){

    var skycons_dashboard = new Skycons({"color": "#ecf0f1"});
        skycons_dashboard.set("skycons-sun", Skycons.CLEAR_DAY);
        skycons_dashboard.set("skycons-wind1", Skycons.WIND);
        skycons_dashboard.set("skycons-wind2", Skycons.WIND);
        skycons_dashboard.set("skycons-snow", Skycons.SNOW);
        skycons_dashboard.set("skycons-cloudy", Skycons.CLOUDY);
        skycons_dashboard.set("skycons-fog", Skycons.FOG);
        skycons_dashboard.set("skycons-rain", Skycons.RAIN);

        skycons_dashboard.play();
    };
    // END DASHBOARD DEMO
    
    





    // GALLERY PAGE DEMO
    $('[data-toggle="gallery-expand"]').on('click', function(e){
        e.preventDefault();

        var $this = $(this),
            galleryItem = $this.parent(),
            target = galleryItem.find('.gallery-item-expand');


        if (target.length > 0) {
            $('body').addClass('gallery-expand');
            target.slideDown();
        }
    });
    $('[data-toggle="gallery-expand-close"]').on('click', function(e){
        e.preventDefault();

        var $this = $(this),
            target = ( $this.parent().hasClass('btn-group') ) ? $this.parent().parent() : $this.parent();


        $('body').removeClass('gallery-expand');
        target.slideUp();
    });
    $('[data-toggle="gallery-expand-caption"]').on('click', function(e){
        e.preventDefault();

        var $this = $(this),
            target = ( $this.parent().hasClass('btn-group') ) ? $this.parent().parent() : $this.parent();

        target.toggleClass('caption-expand');
    });
    // END GALLERY PAGE DEMO





	// ADVANCE PANEL REFRESH DEMO
    // Custom actions to panel actions refresh demo
    // Custom callback to manipulation data
    var getRandomLength = function(min, max) {
        return Math.round(Math.random() * (max - min) + min);
    }
    $(document).on('ajaxComplete', function(event, xhr, settings) {
        if(settings.url === "data-sample/sample-data.json"){
            // get data response
            var data = $.parseJSON(xhr.responseText),
                startIndex = Math.round(Math.random() * data.length),
                startIndex = (startIndex > 90) ? startIndex-10 : startIndex ,
                dataLength = getRandomLength(5, 10),
                x = 1,

                table_content  = '<thead>';
                table_content += '  <tr>';
                table_content += '      <th>Name</th>';
                table_content += '      <th>Company</th>';
                table_content += '      <th>Country</th>';
                table_content += '  </tr>';
                table_content += '</thead>';

                table_content += '<tbody>';

            $.each(data, function(index){

                if (index >= startIndex) {
                    if (x <= dataLength) {
                        table_content += '<tr>';
                        table_content += '<td>'+ this.name +'</td>';
                        table_content += '<td>'+ this.company +'</td>';
                        table_content += '<td>'+ this.country +'</td>';
                        table_content += '</tr>';

                        x++;
                    };
                };
            });

            table_content += '</tbody>';

            $('#demo-target-refresh2').html(table_content);
            console.clear();
            console.log('Success! you get ' + dataLength + ' data.');
        }
        else{
            return false;
        }
    });





    // TYPEAHEAD DEMO
    // Single dataset
    $('#typeahead-local').typeahead({
        name: 'teams',
        local: [ "Courtney Wilkins", "Rama Obrien", "Ross Mills", "Craig Banks", "Rae Franco", "Darrel Carlson", "Lynn Mcbride", "Noelle Martinez", "Risa Fletcher", "Dennis Mejia", "Blaze Eaton", "Theodore Kelly", "Roth Velazquez", "Xena Holden", "Deirdre Rodriquez", "Nita Marquez", "Amanda Hicks", "Alan Ford", "Judith Talley", "Kuame Boyle"]
    });
    // Prefetches
    $('#typeahead-prefetches').typeahead({                                
        name: 'countries',                                                          
        prefetch: 'data-sample/countries.json',                                         
        limit: 10                                                                   
    });
    // Custom template
    $('#typeahead-customtemplate').typeahead({
        name: 'twitter-oss',
        prefetch: 'data-sample/repos.json',
        template: [
            '<p class="pull-right text-italic text-muted">{{language}}</p>',
            '<p class="text-16 text-bold">{{name}}</p>',
            '<p class="text-13">{{description}}</p>'
        ].join(''),
        engine: Hogan
    });
    // Two Datasets
    $('#typeahead-2datasets').typeahead([
        {
            name: 'nba-teams',
            prefetch: 'data-sample/nba.json',
            header: '<h3 class="dataset-heading">NBA Teams</h3>'
        },
        {
            name: 'nhl-teams',
            prefetch: 'data-sample/nhl.json',
            header: '<h3 class="dataset-heading">NHL Teams</h3>'
        }
    ]);
    // END TYPEAHEAD DEMO
    



    // DATE RANGE PICKER DEMO
    $('#reportrange').daterangepicker({
        ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            startDate: moment().subtract('days', 29),
            endDate: moment()
        },
        function(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        })
        .find('span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
    // END DATE RANGE PICKER DEMO



    // validate signup form on keyup and submit
    $("#signupForm").validate({
        focusCleanup: true,
        errorClass: "text-danger",
        errorPlacement: function(error, element) {
            if ( element.parent().hasClass('nice-checkbox') || element.parent().hasClass('nice-radio') || element.parent().hasClass('input-group') ) {
                error.appendTo( element.parent().parent() );
            }
            else{
                error.appendTo( element.parent() );
            }
        },
        rules: {
            firstname: "required",
            lastname: "required",
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            agree: "required"
        },
        messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 2 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address",
            agree: "Please accept our policy"
        }
    });
    // propose username by combining first- and lastname
    $("#username").focus(function() {
        var firstname = $("#firstname").val();
        var lastname = $("#lastname").val();
        if(firstname && lastname && !this.value) {
            this.value = firstname + $.ucfirst(lastname);
        }
    });
    // END FORM VALIDATE DEMO


    // MORRIS CHART DEMO
    // data stolen from http://howmanyleft.co.uk/vehicle/jaguar_'e'_type
    var tax_data = [
        {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
        {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
        {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
        {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
        {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
        {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
        {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
        {"period": "2006 Q4", "licensed": 3245, "sorned": null},
        {"period": "2005 Q4", "licensed": 3289, "sorned": null}
    ];

    var morris_graph = $(document).find('#morris-graph');
    if (morris_graph.length > 0) {
        Morris.Line({
            element: morris_graph,
            data: tax_data,
            lineColors: ['#13A89E', '#95a5a6'],
            xkey: 'period',
            ykeys: ['licensed', 'sorned'],
            labels: ['Licensed', 'Off the road'],
            hideHover: 'auto'
        });
    };

    var morris_bar = $(document).find('#morris-bar');
    if (morris_bar.length > 0) {
        Morris.Bar({
            element: morris_bar,
            barColors: ['#394264', '#515E8E'],
            data: [
                { y: '2006', a: 100, b: 90 },
                { y: '2007', a: 75,  b: 65 },
                { y: '2008', a: 50,  b: 40 },
                { y: '2009', a: 75,  b: 65 },
                { y: '2010', a: 50,  b: 40 },
                { y: '2011', a: 75,  b: 65 },
                { y: '2012', a: 100, b: 90 }
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['iPhone', 'Android'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto'
        });
    };

    var morris_area = $(document).find('#morris-area');
    if (morris_area.length > 0) {
        Morris.Area({
            element: morris_area,
            lineColors: ['#13A89E', '#13A880', '#13A862'],
            data: [
                {period: '2010 Q1', iphone: 2666, ipad: null, itouch: 2647},
                {period: '2010 Q2', iphone: 2778, ipad: 2294, itouch: 2441},
                {period: '2010 Q3', iphone: 4912, ipad: 1969, itouch: 2501},
                {period: '2010 Q4', iphone: 3767, ipad: 3597, itouch: 5689},
                {period: '2011 Q1', iphone: 6810, ipad: 1914, itouch: 2293},
                {period: '2011 Q2', iphone: 5670, ipad: 4293, itouch: 1881},
                {period: '2011 Q3', iphone: 4820, ipad: 3795, itouch: 1588},
                {period: '2011 Q4', iphone: 15073, ipad: 5967, itouch: 5175},
                {period: '2012 Q1', iphone: 10687, ipad: 4460, itouch: 2028},
                {period: '2012 Q2', iphone: 8432, ipad: 5713, itouch: 1791}
            ],
            xkey: 'period',
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['iPhone', 'iPad', 'iPod Touch'],
            pointSize: 2,
            hideHover: 'auto'
        });
    };

    var morris_donut = $(document).find('#morris-donut');
    if (morris_donut.length > 0) {
        Morris.Donut({
            element: morris_donut,
            colors: ['#394264', '#515E8E', '#697AB9', '#8296E3'],
            data: [
                {label: 'Jam', value: 25 },
                {label: 'Frosted', value: 40 },
                {label: 'Custard', value: 25 },
                {label: 'Sugar', value: 10 }
            ],
            formatter: function (y) { return y + "%" }
        });
    };
    // END MORRIS CHART DEMO
    




    // FLOT CHART DEMO
    // Combine Chart
    var flot_combine = $(document).find('#flot-combine');
    if (flot_combine.length > 0) {
        var gd = function(year, month, day) {
                return new Date(year, month - 1, day).getTime();
            },
            wind = [
                [gd(2012, 1, 1), 11], [gd(2012, 1, 2), 9], [gd(2012, 1, 3), 7], [gd(2012, 1, 4), 13],
                [gd(2012, 1, 5), 11], [gd(2012, 1, 6), 11], [gd(2012, 1, 7), 9], [gd(2012, 1, 8), 10],
                [gd(2012, 1, 9), 7], [gd(2012, 1, 10), 11], [gd(2012, 1, 11), 7], [gd(2012, 1, 12), 6],
                [gd(2012, 1, 13), 4], [gd(2012, 1, 14), 5], [gd(2012, 1, 15), 11], [gd(2012, 1, 16), 8],
                [gd(2012, 1, 17), 9], [gd(2012, 1, 18), 16], [gd(2012, 1, 19), 11], [gd(2012, 1, 20), 18],
                [gd(2012, 1, 21), 8], [gd(2012, 1, 22), 17], [gd(2012, 1, 23), 11], [gd(2012, 1, 24), 13],
                [gd(2012, 1, 25), 11], [gd(2012, 1, 26), 11], [gd(2012, 1, 27), 9], [gd(2012, 1, 28), 8],
                [gd(2012, 1, 29), 7], [gd(2012, 1, 30), 8], [gd(2012, 1, 31), 20]
            ],
            pressure = [
                [gd(2012, 1, 1), 320], [gd(2012, 1, 2), 430], [gd(2012, 1, 3), 540], [gd(2012, 1, 4), 560],
                [gd(2012, 1, 5), 964], [gd(2012, 1, 6), 1022], [gd(2012, 1, 7), 436], [gd(2012, 1, 8), 750],
                [gd(2012, 1, 9), 648], [gd(2012, 1, 10), 639], [gd(2012, 1, 11), 654], [gd(2012, 1, 12), 300],
                [gd(2012, 1, 13), 739], [gd(2012, 1, 14), 748], [gd(2012, 1, 15), 768], [gd(2012, 1, 16), 1023],
                [gd(2012, 1, 17), 869], [gd(2012, 1, 18), 489], [gd(2012, 1, 19), 987], [gd(2012, 1, 20), 980],
                [gd(2012, 1, 21), 580], [gd(2012, 1, 22), 387], [gd(2012, 1, 23), 345], [gd(2012, 1, 24), 560],
                [gd(2012, 1, 25), 405], [gd(2012, 1, 26), 876], [gd(2012, 1, 27), 543], [gd(2012, 1, 28), 650],
                [gd(2012, 1, 29), 600], [gd(2012, 1, 30), 900], [gd(2012, 1, 31), 531]
            ],
            temp = [
                [gd(2012, 1, 1), 1], [gd(2012, 1, 2), -2], [gd(2012, 1, 3), -2], [gd(2012, 1, 4), 1],
                [gd(2012, 1, 5), 3], [gd(2012, 1, 6), 4], [gd(2012, 1, 7), 5], [gd(2012, 1, 8), 6],
                [gd(2012, 1, 9), 7], [gd(2012, 1, 10), 7], [gd(2012, 1, 11), 6], [gd(2012, 1, 12), 7],
                [gd(2012, 1, 13), 8], [gd(2012, 1, 14), 8], [gd(2012, 1, 15), 3], [gd(2012, 1, 16), 2],
                [gd(2012, 1, 17), 4], [gd(2012, 1, 18), -1], [gd(2012, 1, 19), 5], [gd(2012, 1, 20), 6],
                [gd(2012, 1, 21), -2], [gd(2012, 1, 22), -7], [gd(2012, 1, 23), -9], [gd(2012, 1, 24), -8],
                [gd(2012, 1, 25), -7], [gd(2012, 1, 26), -6], [gd(2012, 1, 27), -3], [gd(2012, 1, 28), 1], 
                [gd(2012, 1, 29), 6], [gd(2012, 1, 30), 9], [gd(2012, 1, 31), 8]
            ];

        var dataset_combine = [
            {
                label: "Sea Level Pressure",
                data: pressure,         
                color: "#ecf0f1",
                bars: {
                    show: true, 
                    align: "center",
                    barWidth: 24 * 60 * 60 * 600,
                    lineWidth:1
                }
            }, {
                label: "Wind Speed",
                data: wind,
                yaxis: 2,
                color: "#13A89E",
                points: { fillColor: "#13A89E", show: true },
                lines: {show:true}
            }, {
                label: "Temperature",
                data: temp,
                yaxis: 3,
                color: "#394264",
                points: { fillColor: "#394264", show: true },
                lines: { show: true }
            }
        ];
        $.plot(flot_combine, dataset_combine, {
            xaxis: {
                mode: "time",
                tickSize: [3, "day"],        
                tickLength: 0,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Open Sans, Arial',
                axisLabelColour: '#394264',
                axisLabelPadding: 10
            },
            yaxes: [{
                    position: "left",
                    max: 1070,
                    axisLabel: "Sea Level Pressure (hPa)",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Open Sans, Arial',
                    axisLabelColour: '#394264',
                    axisLabelPadding: 3            
                }, {
                    position: "right",
                    axisLabel: "Wind Speed (km/hr)",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Open Sans, Arial',
                    axisLabelColour: '#394264',
                    axisLabelPadding: 3            
                },{
                    position: "right",
                    axisLabel: "Temperature (°C)",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Open Sans, Arial',
                    axisLabelColour: '#394264',
                    axisLabelPadding: 3            
                }
            ],
            legend: {
                noColumns: 1,
                position: "nw"        
            },
            grid: {
                hoverable: true,
                borderWidth: 0,
                color: '#394264'
            }
        });
    }
    

    // Flot Line
    var flot_line = $(document).find('#flot-line');
    if (flot_line.length > 0) {
        var sin = [],
            cos = [];

        for (var i = 0; i < 14; i += 0.5) {
            sin.push([i, Math.sin(i)]);
            cos.push([i, Math.cos(i)]);
        }
        $.plot(flot_line, [
            { data: sin, label: "sin(x)"},
            { data: cos, label: "cos(x)"}
        ], 
        {
            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            colors: ["#394264", "#13A89E"],
            grid: {
                hoverable: true,
                borderWidth: 0,
                color: '#394264'
            },
            legend: {
                position: 'se'
            },
            xaxis: {
                tickLength: 0
            },
            yaxis: {
                min: -1.2,
                max: 1.2,
                tickSize: 1,
                tickColor: '#ecf0f1'
            }
        });
    }
    
    

    // Flot Bar
    var flot_bar = $(document).find('#flot-bar');
    if (flot_bar.length > 0) {
        var data_bar = [ ["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9] ];

        $.plot(flot_bar, [ data_bar ], {
            series: {
                bars: {
                    show: true,
                    barWidth: 0.5,
                    align: "center",
                    fillColor: { colors: [ { opacity: 0.9 }, { opacity: 0.1 } ] }
                }
            },
            colors: ["#13A89E"],
            grid: {
                hoverable: true,
                borderWidth: 0,
                color: '#394264'
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            },
            yaxis: {
                tickColor: '#ecf0f1'
            }
        });
    }
    

    // Area chart
    var flot_area = $(document).find('#flot-area');
    if (flot_area.length > 0) {
        var year = function(year) {
                return new Date(year, 1, 1).getTime();
            },
            // raw data
            america = [
                [year(1800), 700], [year(1850), 2600], [year(1900), 8200], [year(1950), 17162], [year(1955), 18688],
                [year(1960), 20415], [year(1965), 21957], [year(1970), 23194], [year(1975), 24343], [year(1980), 25607],
                [year(1985), 26946], [year(1990), 28355], [year(1995), 29944], [year(2000), 31592], [year(2005), 33216],
                [year(2010), 34412]
            ],
            uerope = [
                [year(1800), 20300], [year(1850), 27600], [year(1900), 40800], [year(1950), 54740], [year(1955), 57518],
                [year(1960), 60140], [year(1965), 63403], [year(1970), 65586], [year(1975), 67554], [year(1980), 69243],
                [year(1985), 70601], [year(1990), 72158], [year(1995), 72741], [year(2000), 72799], [year(2005), 72472],
                [year(2010), 72708]
            ],
            asia = [
                [year(1800), 63500], [year(1850), 80900], [year(1900), 94700], [year(1950), 139849], [year(1955), 154195],
                [year(1960), 167434], [year(1965), 189942], [year(1970), 214312], [year(1975), 239751], [year(1980), 263234],
                [year(1985), 288755], [year(1990), 316781], [year(1995), 343005], [year(2000), 367974], [year(2005), 391751],
                [year(2010), 411963]
            ];

        var dataSet_area = [
            { label: "Asia", data: asia, color: "#13A89E" },
            { label: "Europe", data: uerope, color: "#394264" },
            { label: "North America", data: america, color: "#DA4F49" }
        ];
        $.plot($("#flot-area"), dataSet_area, {
            series: {
                lines: {
                    show: true,
                    fill: true
                }
            },
            xaxis: {
                tickLength: 0,
                axisLabel: "Year",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelPadding: 10,        
                axisLabelFontFamily: 'Open Sans, sans-serif',
                axisLabelColour: '#394264',
                mode: "time",
                tickSize: [20, "year"],
                timeformat: "%Y"
            },
            yaxis: {
                axisLabel: "Population (multiply by 10,000)",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelPadding: 3,
                axisLabelFontFamily: 'Open Sans, sans-serif',
                axisLabelColour: '#394264',
                tickFormatter: function (v, axis) {
                    return $.formatNumber(v, { format: "#,###", locale: "us" });
                }
            },
            legend: {
                noColumns: 3,
                position: "nw"
            },
            grid: {
                hoverable: true,
                borderWidth: 0,
                color: '#394264'
            }
        });
    }
    

    // HORIZONTAL BAR CHART
    var flot_barhor = $(document).find('#flot-barhor');
    if (flot_barhor.length > 0) {
        var data_barhor = [
                [1582.3, 0], //Gold/oz
                [28.95, 1],  //Silver/oz
                [1603, 2],   //PLATINUM /oz
                [774, 3],     //PALLADIUM /oz
                [1245, 4],     //Rhodium
                [85, 5],       //Ruthenium 
                [1025, 6]      //Iridium 
            ],
            dataSet_barhor = [
                { label: "Precious Metal Price", data: data_barhor, color: "#394264" }
            ],
            ticks_barhor = [
                [0, "Gold"], [1, "Silver"], [2, "Platinum"], [3, "Palldium"], [4, "Rhodium"], [5, "Ruthenium"], [6, "Iridium"]
            ];

        $.plot(flot_barhor, dataSet_barhor, {
            series: {
                bars: {
                    show: true
                }
            },
            bars: {
                align: "center",
                barWidth: 0.5,
                horizontal: true,
                fillColor: { colors: [{ opacity: 1 }, { opacity: 1}] },
                lineWidth: 1
            },
            xaxis: {
                axisLabel: "Price (USD/oz)",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelPadding: 10,
                axisLabelFontFamily: 'Open Sans, sans-serif',
                axisLabelColour: '#394264',
                tickFormatter: function (v, axis) {
                    return $.formatNumber(v, { format: "#,###", locale: "us" });                        
                },
                max: 2000
            },
            yaxis: {
                axisLabel: "Precious Metals",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelPadding: 3,
                axisLabelFontFamily: 'Open Sans, sans-serif',
                axisLabelColour: '#394264',
                tickLength: 0,
                ticks: ticks_barhor
            },
            legend: {
                noColumns: 0,
                position: "ne"
            },
            grid: {
                hoverable: true,
                borderWidth: 0,
                color: '#394264'
            }
        });
    }
    

    // Realtime
    var flot_realtime = $(document).find('#flot-realtime'),
        getRandomData = function() {

            if (data_realtime.length > 0)
                data_realtime = data_realtime.slice(1);

            // Do a random walk
            while (data_realtime.length < totalPoints) {

                var prev = data_realtime.length > 0 ? data_realtime[data_realtime.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }

                data_realtime.push(y);
            }

            // Zip the generated y values with the x values
            var res = [];
            for (var i = 0; i < data_realtime.length; ++i) {
                res.push([i, data_realtime[i]])
            }

            return res;
        },
        update = function() {
            flotRealtime.setData([{ data: getRandomData(), color: '#13A89E' }]);

            // Since the axes don't change, we don't need to call plot.setupGrid()
            flotRealtime.draw();
            setTimeout(update, updateInterval);
        };

    if (flot_realtime.length > 0) {
        var data_realtime = [],
            totalPoints = 300;

        // Set up the control widget
        var updateInterval = 30;
        var flotRealtime = $.plot(flot_realtime, [{ data: getRandomData(), color: '#13A89E' }], {
            series: {
                lines: {
                    show: true,
                    fill: true
                },
                shadowSize: 0   // Drawing is faster without shadows
            },
            yaxis: {
                min: 0,
                max: 100
            },
            xaxis: {
                show: false
            },
            grid: {
                borderWidth: 0,
                color: '#394264'
            }
        });

        update();
    }
    

    // Tooltips chart
    $("<div id='flot-tooltip' class='flot-tooltip'></div>").appendTo("body");
    $(document).on("plothover", "#flot-line, #flot-bar", function (event, pos, item) {

        if (item) {
            var x = item.datapoint[0].toFixed(2),
                y = item.datapoint[1].toFixed(2),
                series = (item.series.label === undefined) ? item.series.data[item.dataIndex][0] : item.series.label + " of " + x;
                
            $("#flot-tooltip").html(series + " = " + y)
            .css({top: item.pageY+5, left: item.pageX+5})
            .fadeIn(300);
        } 
        else {
            $("#flot-tooltip").hide();
        }
    });
    // costumize tooltip
    $("<div id='flot-tooltip2' class='flot-tooltip'></div>").appendTo("body");
    $(document).on("plothover", "#flot-area", function (event, pos, item) {

        if (item) {
            var x = item.datapoint[0],
                y = item.datapoint[1],
                color = item.series.color;
                
            $("#flot-tooltip2").html( "<strong>" + item.series.label + "</strong><br>" + new Date(x).getFullYear() +
                " : <strong>Population : " + $.formatNumber(y, { format: "#,###", locale: "us" }) + "</strong> <br> <em>(multiply by 10,000)</em>")
            .css({top: item.pageY+5, left: item.pageX+5, borderColor: color, color: '#394264', backgroundColor: '#ffffff'})
            .fadeIn(300);
        } 
        else {
            $("#flot-tooltip2").hide();
        }
    });
    $("<div id='flot-tooltip3' class='flot-tooltip'></div>").appendTo("body");
    $(document).on("plothover", "#flot-barhor", function (event, pos, item) {

        if (item) {
            var x = item.datapoint[0],
                y = item.datapoint[1],
                color = item.series.color;
                
            $("#flot-tooltip3").html( "<strong>" + item.series.label + ": </strong>" + x)
            .css({top: item.pageY+9, left: item.pageX-100, borderColor: color, color: '#394264', backgroundColor: '#ffffff'})
            .fadeIn(300);
        } 
        else {
            $("#flot-tooltip3").hide();
        }
    });
    $("<div id='flot-tooltip4' class='flot-tooltip'></div>").appendTo("body");
    $(document).on("plothover", "#flot-combine", function (event, pos, item) {

        if (item) {
            var x = item.datapoint[0],
                y = item.datapoint[1],
                color = item.series.color,
                date = "Jan " + new Date(x).getDate(),
                unit = "";

            if (item.series.label == "Sea Level Pressure") {
                unit = "hPa";
            } else if (item.series.label == "Wind Speed") {
                unit = "km/hr";
            } else if (item.series.label == "Temperature") {
                unit = "°C";
            }
                
            $("#flot-tooltip4").html( "<strong>" + item.series.label + "</strong><br>" + date + " : <strong>" + y + "</strong> " + unit)
            .css({top: item.pageY+5, left: item.pageX+5, borderColor: color, color: '#394264', backgroundColor: '#ffffff'})
            .fadeIn(300);
        } 
        else {
            $("#flot-tooltip4").hide();
        }
    });
    // END FLOT CHART DEMO
    
    




    // INLINE CHART DEMO
    // EASY PIE CHART
    // update pie chart
    $(document).on('click', '#updatePieCharts', function(e) {
        e.preventDefault();
        $('.easyPieChart').each(function() {
            $(this).data('easyPieChart').update(Math.floor(100*Math.random()));
        });
    });

    // SPARKLINE
    // Line charts taking their values from the tag
    $('.sparkline').sparkline();
    // Bar charts using inline values
    $('.sparkbar').sparkline('html', {type: 'bar'});
    // Composite line charts, the second using values supplied via javascript
    $('#compositeline').sparkline('html', { fillColor: false, changeRangeMin: 0, chartRangeMax: 10 });
    $('#compositeline').sparkline([4,1,5,7,9,9,8,7,6,6,4,7,8,4,3,2,2,5,6,7], 
        { composite: true, fillColor: false, lineColor: 'red', changeRangeMin: 0, chartRangeMax: 10 });
    // Line charts with normal range marker
    $('#normalline').sparkline('html', 
        { fillColor: false, normalRangeMin: -1, normalRangeMax: 8 });
    $('#normalExample').sparkline('html', 
        { fillColor: false, normalRangeMin: 80, normalRangeMax: 95, normalRangeColor: '#4f4' });
    // Bar + line composite charts
    $('#compositebar').sparkline('html', { type: 'bar', barColor: '#aaf' });
    $('#compositebar').sparkline([4,1,5,7,9,9,8,7,6,6,4,7,8,4,3,2,2,5,6,7], 
        { composite: true, fillColor: false, lineColor: 'red' });
    // Discrete charts
    $('.discrete1').sparkline('html', 
        { type: 'discrete', lineColor: 'blue', xwidth: 18 });
    $('#discrete2').sparkline('html', 
        { type: 'discrete', lineColor: 'blue', thresholdColor: 'red', thresholdValue: 4 });
    // Customized line chart
    $('#linecustom').sparkline('html', 
        {height: '1.5em', width: '8em', lineColor: '#f00', fillColor: '#ffa', 
        minSpotColor: false, maxSpotColor: false, spotColor: '#77f', spotRadius: 3});
    // Tri-state charts using inline values
    $('.sparktristate').sparkline('html', {type: 'tristate'});
    $('.sparktristatecols').sparkline('html', 
        {type: 'tristate', colorMap: {'-2': '#fa7', '2': '#44f'} });
    // Box plots
    $('.sparkboxplot').sparkline('html', { type: 'box'});
    $('.sparkboxplotraw').sparkline([ 1, 3, 5, 8, 10, 15, 18 ], 
        {type:'box', raw: true, showOutliers:true, target: 6});
    // Bullet charts
    $('.sparkbullet').sparkline('html', { type: 'bullet' });

    // Pie charts
    $('.sparkpie').sparkline('html', { type: 'pie', height: '1.0em' });
    /** 
    ** Draw the little mouse speed animated graph
    ** This just attaches a handler to the mousemove event to see
    ** (roughly) how far the mouse has moved
    ** and then updates the display a couple of times a second via
    ** setTimeout()
    **/
    var drawMouseSpeedDemo = function () {
        var mrefreshinterval = 500; // update display every 500ms
        var lastmousex=-1; 
        var lastmousey=-1;
        var lastmousetime;
        var mousetravel = 0;
        var mpoints = [];
        var mpoints_max = 30;
        $('html').mousemove(function(e) {
            var mousex = e.pageX;
            var mousey = e.pageY;
            if (lastmousex > -1) {
                mousetravel += Math.max( Math.abs(mousex-lastmousex), Math.abs(mousey-lastmousey) );
            }
            lastmousex = mousex;
            lastmousey = mousey;
        });
        var mdraw = function() {
            var md = new Date();
            var timenow = md.getTime();
            if (lastmousetime && lastmousetime!=timenow) {
                var pps = Math.round(mousetravel / (timenow - lastmousetime) * 1000);
                mpoints.push(pps);
                if (mpoints.length > mpoints_max)
                    mpoints.splice(0,1);
                mousetravel = 0;
                $('#mousespeed').sparkline(mpoints, { width: mpoints.length*2, tooltipSuffix: ' pixels per second' });
            }
            lastmousetime = timenow;
            setTimeout(mdraw, mrefreshinterval);
        }
        // We could use setInterval instead, but I prefer to do it this way
        setTimeout(mdraw, mrefreshinterval); 
    };
    drawMouseSpeedDemo();
    // END INLINE CHART DEMO
    




    // FULL CALENDAR DEMO
    var demo_calendar = function(){
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /* initialize the calendar
        -----------------------------------------------------------------*/
        var calendar = $('#calendar').fullCalendar({
            header: false,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                        true // make the event "stick"
                    );
                }
                calendar.fullCalendar('unselect');
            },
            editable: true,
            events: [
                {
                    title: 'All Day Event',
                    start: new Date(y, m, 1)
                },
                {
                    title: 'Long Event',
                    start: new Date(y, m, d-5),
                    end: new Date(y, m, d-2)
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d-3, 16, 0),
                    allDay: false
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d+4, 16, 0),
                    allDay: false
                },
                {
                    title: 'Meeting',
                    start: new Date(y, m, d, 10, 30),
                    allDay: false
                },
                {
                    title: 'Lunch',
                    start: new Date(y, m, d, 12, 0),
                    end: new Date(y, m, d, 14, 0),
                    allDay: false
                },
                {
                    title: 'Birthday Party',
                    start: new Date(y, m, d+1, 19, 0),
                    end: new Date(y, m, d+1, 22, 30),
                    allDay: false
                },
                {
                    title: 'Click for Google',
                    start: new Date(y, m, 28),
                    end: new Date(y, m, 29),
                    url: 'http://google.com/'
                }
            ],
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(date, allDay) { // this function is called when something is dropped
            
                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                
                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                
                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }   
            }
        });

        // Calendar control
        $('#calendar-viewtitle').text($('#calendar').fullCalendar('getView').title);
        
        $(document).on('click', '#calendar-viewmonth', function(){
            $('#calendar').fullCalendar( 'changeView', 'month' );

            $(this).parent().find('.btn-panel').removeClass('active');
            $(this).addClass('active');
        });
        $(document).on('click', '#calendar-viewweek', function(){
            $('#calendar').fullCalendar( 'changeView', 'agendaWeek' )
                .find('.fc-agenda > div').children('div:last-child').niceScroll({
                    cursorcolor: 'rgba(0, 0, 0, .1)',
                    cursorwidth: '12px',
                    cursorborder: '3px solid transparent',
                    cursorborderradius: '6px'
                });

            $(this).parent().find('.btn-panel').removeClass('active');
            $(this).addClass('active');
        });
        $(document).on('click', '#calendar-viewday', function(){
            $('#calendar').fullCalendar( 'changeView', 'agendaDay' )
                .find('.fc-agenda > div').children('div:last-child').niceScroll({
                    cursorcolor: 'rgba(0, 0, 0, .1)',
                    cursorwidth: '12px',
                    cursorborder: '3px solid transparent',
                    cursorborderradius: '6px'
                });

            $(this).parent().find('.btn-panel').removeClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', '#calendar-viewtoday', function(){
            $('#calendar').fullCalendar( 'today' );
        });
        $(document).on('click', '#calendar-viewnext', function(e){
            e.preventDefault();
            $('#calendar').fullCalendar('next');
        });
        $(document).on('click', '#calendar-viewnextYear', function(e){
            e.preventDefault();
            $('#calendar').fullCalendar('nextYear');
        });
        $(document).on('click', '#calendar-viewprev', function(e){
            e.preventDefault();
            $('#calendar').fullCalendar('prev');
        });
        $(document).on('click', '#calendar-viewprevYear', function(e){
            e.preventDefault();
            $('#calendar').fullCalendar('prevYear');
        });

        $(document).on('click', '#calendar-viewtoday, #calendar-viewmonth, #calendar-viewweek, #calendar-viewday, #calendar-viewnext, #calendar-viewprev, #calendar-viewnextYear, #calendar-viewprevYear', function(){
            var view = $('#calendar').fullCalendar('getView'),
                title = view.title;

            title = title.replace('&#8212;', 'to');
            $('#calendar-viewtitle').text(title);
            console.log(title)
        });

        /* initialize the external events
        -----------------------------------------------------------------*/
        $('#external-events div.external-event').each(function() {
        
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
            
        });
    }
    // initialize demo calendar
    if($(document).find('#calendar').length > 0){
        demo_calendar();
    }
    // END FULL CALENDAR DEMO
    




    // VMAP DEMO
    if($(document).find('#vmap').length > 0){
        var defaultNum = [23566,215866,174649,386,654865],
            visitAnime = new countUp("legend-visit", 0, defaultNum[0], 0, 5),
            memberAnime = new countUp("legend-member", 0, defaultNum[1], 0, 5),
            customerAnime = new countUp("legend-customer", 0, defaultNum[2], 0, 5),
            onlineAnime = new countUp("legend-online", 0, defaultNum[3], 0, 5),
            revenueAnime = new countUp("legend-revenue", 0, defaultNum[4], 0, 5);

        visitAnime.start(function(e){
            $('#legend-visit').text(defaultNum[0].formatMoney());
        });
        memberAnime.start(function(e){
            $('#legend-member').text(defaultNum[1].formatMoney());
        });
        customerAnime.start(function(e){
            $('#legend-customer').text(defaultNum[2].formatMoney());
        });
        onlineAnime.start(function(e){
            $('#legend-online').text(defaultNum[3].formatMoney());
        });
        revenueAnime.start(function(e){
            $('#legend-revenue').text(defaultNum[4].formatMoney());
        });

        $('#vmap').vectorMap({
            map: 'world_en',
            backgroundColor: null,
            color: '#ffffff',
            hoverOpacity: 0.7,
            selectedColor: '#666666',
            enableZoom: true,
            showTooltip: true,
            values: sample_data,
            scaleColors: ['#1376A8', '#13A89E'],
            normalizeFunction: 'polynomial',
            onRegionOver: function(event, code, region){
                $('#mapRegion').text(region + ' (' + code + ')');

                var legend_visit = randomNumber(20000, 100000),
                    legend_member = randomNumber(100000, 300000),
                    legend_customer = randomNumber(50000, 250000),
                    legend_online = randomNumber(300, 600),
                    legend_revenue = randomNumber(100000, 600000);

                $('#legend-region').text('Summary of ' + region);
                $('#legend-visit').text(legend_visit.formatMoney());
                $('#legend-member').text(legend_member.formatMoney());
                $('#legend-customer').text(legend_customer.formatMoney());
                $('#legend-online').text(legend_online.formatMoney());
                $('#legend-revenue').text(legend_revenue.formatMoney());
            },
            onRegionOut: function(event, code, region){
                var oldTitle = $('#mapRegion').data('ariginalTitle');

                $('#mapRegion').text(oldTitle);
            }
        });
        
        $(document).on('click', '#changeMapRegion a', function(e){
            e.preventDefault();

            var $this = $(this),
                parent = $this.parent(),
                newTitle = $this.text(),
                dataMap = $this.data('map');

            parent.parent().find('li').removeClass('active');
            parent.addClass('active');

            $('#mapRegion').data('ariginalTitle', newTitle + ' Map');
            $('#mapRegion').text(newTitle + ' Map');
            $('#legend-region').text('Summary of ' + newTitle);

            $('#vmap').replaceWith('<div id="vmap" style="width:100%;height:400px;" class="vmap"></div>');

            var $newMap = $('#vmap');

            $newMap.vectorMap({
                map: dataMap,
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#666666',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial',
                onRegionOver: function(event, code, region){
                    $('#mapRegion').text(region + ' (' + code + ')');

                    var legend_visit = randomNumber(20000, 100000),
                        legend_member = randomNumber(100000, 300000),
                        legend_customer = randomNumber(50000, 250000),
                        legend_online = randomNumber(300, 600),
                        legend_revenue = randomNumber(100000, 600000);

                    $('#legend-region').text('Summary of ' + region);
                    $('#legend-visit').text(legend_visit.formatMoney());
                    $('#legend-member').text(legend_member.formatMoney());
                    $('#legend-customer').text(legend_customer.formatMoney());
                    $('#legend-online').text(legend_online.formatMoney());
                    $('#legend-revenue').text(legend_revenue.formatMoney());
                },
                onRegionOut: function(event, code, region){
                    var oldTitle = $('#mapRegion').data('ariginalTitle');

                    $('#mapRegion').text(oldTitle);
                }
            });
        });
    }; // end #vmap
    
    // vmap-inverse
    if($(document).find('#vmap-inverse').length > 0){
        // optional style
        $('#vmap-inverse').vectorMap({
            map: 'world_en',
            backgroundColor: null,
            color: '#8296E3',
            hoverOpacity: 0.9,
            selectedColor: '#ecf0f1',
            enableZoom: true,
            showTooltip: true,
            values: sample_data,
            scaleColors: ['#515E8E', '#394264'],
            normalizeFunction: 'polynomial'
        });
    }; // end #vmap-inverse
    // END VMAP DEMO
});