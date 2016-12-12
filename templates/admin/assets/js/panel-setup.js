$(function(){
	'use strict';


    // Panel actions
	// close panel
    $(document).on('click', '.panel [data-close]', function(e){
        e.preventDefault();

        var $this = $(this),
            panel = $this.attr('data-close'),
            dataAnimated = ($this.attr('data-animate') === undefined) ? 'fadeOut' : $this.attr('data-animate') ;

        $(panel).addClass('animated ' + dataAnimated)
            .one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
                $(this).addClass('hide').removeClass('animated ' + dataAnimated);
            });
    });


    // collapse panel
    $(document).on('click', '.panel [data-collapse]', function(e){
        e.preventDefault();
        var $this = $(this),
            panel = $this.attr('data-collapse'),
            panel_body = $(panel).children('.panel-body'),
            table = $(panel).children('.table'),
            list_group = $(panel).children('.list-group'),
            collapse_element = $(panel).children('.panel-collapse-element');

        $(panel_body).slideToggle(200);
        $(table).slideToggle(200);
        $(list_group).slideToggle(200);
        $(collapse_element).slideToggle(200);
    }).one('click', function(){
        var $this = $(this),
            panel = $this.attr('data-collapse');

        $(panel).removeClass('panel-collapsed');
    });


    // expand panel
    $(document).on('click', '.panel [data-expand]', function(e){
        e.preventDefault();
        var $this = $(this),
            panel = $this.attr('data-expand');

        $('body').toggleClass('expand-panel');
        $(panel).toggleClass('panel-expand');

        var scrollNice = $(panel).find('[data-toggle="niceScroll"]');

        if (scrollNice.length > 0) {
            scrollNice.getNiceScroll().resize();
        };
    });
    $(document).on('click', '.panel [data-expand][data-expand-mode="fullscreen"]', function(){
        var $this = $(this),
            panel = $this.attr('data-expand'),
            panel = $(panel)[0];

        if (screenfull.enabled) {
            screenfull.toggle(panel);
            if(screenfull.isFullscreen){
                $(panel).addClass('panel-expand');
            }
            else{
                $(panel).removeClass('panel-expand');
            }
        }

        var scrollNice = $(panel).find('[data-toggle="niceScroll"]');

        if (scrollNice.length > 0) {
            scrollNice.getNiceScroll().resize();
        };
    });

    
    // refresh panel
    $(document).on('click', '.panel [data-refresh]', function(e){
        e.preventDefault();
        var $this = $(this),
            panel = $this.attr('data-refresh'),
            loader = ($this.attr('data-loader') === undefined) ? 'loader-spinner' : $this.attr('data-loader'),
            url = ($this.attr('data-url') === undefined) ? false : $this.attr('data-url'),
            placement = ($this.attr('data-target-place') === undefined) ? '.panel-body' : $this.attr('data-target-place'),
            error_place = ($this.attr('data-error-place') === undefined) ? false : $this.attr('data-error-place');

        $(panel).append('<div class="panel-loader"><div class="loader-container"><div class="' + loader + '"></div></div></div>');
        
        // Default callback action
        if (!url) {
            setTimeout(function(){
                $(panel).find('.panel-loader').remove();
                var errorTemplate = '<div class="error-refresh alert alert-warning alert-dismissable no-padding animated fadeIn">'
                                    +'        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                    +'        <p><strong>Warning!</strong> data-url not defined.</p>'
                                    +'</div>',
                        hasBody = $(panel).find('.panel-body:not([data-toggle="niceScroll"])').length;

                    // remove exist error
                    $(panel).find('.error-refresh').remove();

                    // adding new error template
                    if (error_place) {
                        $(error_place).prepend(errorTemplate);
                    }
                    else{
                        if (hasBody > 0) {
                            error_place = $(panel).find('.panel-body');
                            $(error_place[0]).prepend(errorTemplate);
                        }
                        else{
                            error_place = $(panel).find('.panel-heading');
                            error_place.after(errorTemplate);
                        }
                    }
            }, 2000);
        }
        else{
            $.ajax({
                url: url,
                error: function( jqxhr, status, exception ){
                    $(panel).find('.panel-loader').remove();

                    var errorTemplate = '<div class="error-refresh alert alert-danger alert-dismissable no-padding animated fadeIn">'
                                    +'        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                    +'        <p><strong><span class="text-capital">'+ status + ' ' + jqxhr.status +'!</strong> The requested resource ' + url + ' was ' + exception + '.</p>'
                                    +'</div>',
                        hasBody = $(panel).find('.panel-body:not([data-toggle="niceScroll"])').length;

                    // remove exist error
                    $(panel).find('.error-refresh').remove();

                    // adding new error template
                    if (error_place) {
                        $(error_place).prepend(errorTemplate);
                    }
                    else{
                        if (hasBody > 0) {
                            error_place = $(panel).find('.panel-body');
                            $(error_place[0]).prepend(errorTemplate);
                        }
                        else{
                            error_place = $(panel).find('.panel-heading');
                            error_place.after(errorTemplate);
                        }
                    }
                }
            })
            .done(function( data ) {
                $(panel).find('.panel-loader').remove();
                $(panel).find(placement).html(data);
                console.clear();
            });
        }
    });


    // contextual panel
    $(document).on('click', '.panel [data-context]', function(e){
        e.preventDefault();
        var $this = $(this),
            panel = $this.attr('data-target'),
            context = $this.attr('data-context');

        $(panel).removeClass('panel-default panel-primary panel-success panel-info panel-warning panel-danger');
        $(panel).addClass(context);
    })
    // End Panel actions
})