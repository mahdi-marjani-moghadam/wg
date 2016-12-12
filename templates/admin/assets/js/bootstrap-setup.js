$(function(){
	'use strict';


	// BOOTSTRAP TOOLTIPS
    $('[data-toggle="tooltip"]').not('.btn').tooltip();
    $('[rel="tooltip"]').not('.btn').tooltip();
    $('[rel="tooltip-bottom"], .panel-actions .btn-panel').not('.btn').tooltip({
        placement : 'bottom'
    }); 
    $('[rel="tooltip-right"]').not('.btn').tooltip({
        placement : 'right'
    });
    $('[rel="tooltip-left"]').not('.btn').tooltip({
        placement : 'left'
    });

    // tooltip on buttons
    $('.btn[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
    $('.btn[rel="tooltip"]').tooltip({
        container: 'body'
    });
    $('.btn[rel="tooltip-bottom"], .panel-actions .btn-panel').tooltip({
        placement : 'bottom',
        container: 'body'
    }); 
    $('.btn[rel="tooltip-right"]').tooltip({
        placement : 'right',
        container: 'body'
    });
    $('.btn[rel="tooltip-left"]').tooltip({
        placement : 'left',
        container: 'body'
    });

    // destroy a tooltip (helper)
    $('.disable-tooltip').tooltip('destroy')
    // END BOOTSTRAP TOOLTIPS
    




    // BOOTSTRAP POPOVER
    $('[data-toggle="popover"]').each(function(){
        var $this = $(this),
            custom_class = ($this.attr('data-context') === undefined) ? '' : 'popover-' + $this.attr('data-context'),
            template = '<div class="popover ' + custom_class + '"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>';

        
        if ($this.hasClass('btn')) {
            $this.popover({
                container: 'body',
                template: template
            });
        } 
        else{
            $this.popover({
                template: template
            });
        }
    });
    $('[rel="popover"]').each(function(){
        var $this = $(this),
            custom_class = ($this.attr('data-context') === undefined) ? '' : 'popover-' + $this.attr('data-context'),
            template = '<div class="popover ' + custom_class + '"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>';

        
        if ($this.hasClass('btn')) {
            $this.popover({
                container: 'body',
                placement: 'top',
                template: template
            });
        } 
        else{
            $this.popover({
                placement: 'top',
                template: template
            });
        }
    });
    $('[rel="popover-bottom"]').each(function(){
        var $this = $(this),
            custom_class = ($this.attr('data-context') === undefined) ? '' : 'popover-' + $this.attr('data-context'),
            template = '<div class="popover ' + custom_class + '"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>';

        
        if ($this.hasClass('btn')) {
            $this.popover({
                container: 'body',
                placement: 'bottom',
                template: template
            });
        } 
        else{
            $this.popover({
                placement: 'bottom',
                template: template
            });
        }
    });
    $('[rel="popover-right"]').each(function(){
        var $this = $(this),
            custom_class = ($this.attr('data-context') === undefined) ? '' : 'popover-' + $this.attr('data-context'),
            template = '<div class="popover ' + custom_class + '"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>';

        
        if ($this.hasClass('btn')) {
            $this.popover({
                container: 'body',
                placement: 'right',
                template: template
            });
        } 
        else{
            $this.popover({
                placement: 'right',
                template: template
            });
        }
    });
    $('[rel="popover-left"]').each(function(){
        var $this = $(this),
            custom_class = ($this.attr('data-context') === undefined) ? '' : 'popover-' + $this.attr('data-context'),
            template = '<div class="popover ' + custom_class + '"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>';

        
        if ($this.hasClass('btn')) {
            $this.popover({
                container: 'body',
                placement: 'left',
                template: template
            });
        } 
        else{
            $this.popover({
                placement: 'left',
                template: template
            });
        }
    });

    // destroy a popover (helper)
    $('.disable-popover').popover('destroy')
    // END BOOTSTRAP POPOVER
});