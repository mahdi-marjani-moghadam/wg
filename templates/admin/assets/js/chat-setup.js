$(function(){
	'use strict';

	
	// CHAT MODULE
    $(document).on('click', '.contacts-list a', function(e){
        e.preventDefault();

        // hide scroll on module
        $('.module[data-toggle="niceScroll"]').getNiceScroll().hide();
        
        // set default scroll to bottom
        var scrollResize = $('.chatbox-body[data-toggle="niceScroll"]').getNiceScroll().resize(),
            getScrollBottom = scrollResize[0].page.h;
        $('.chatbox-body').animate({scrollTop: getScrollBottom}, 300);

        // show chatbox
        $('.chatbox').addClass('show');
    });
    $(document).on('click', '[data-toggle="chatbox-close"]', function(e){
        e.preventDefault();

        // show scroll on module
        $('.module[data-toggle="niceScroll"]').getNiceScroll().show();
        
        // hide chatbox
        $('.chatbox').removeClass('show');
    });
    // END CHAT SETUP
    

    // CHAT SIMULATION for demo page (Dummy!)
    $(document).on('click', '.contacts-list a', function(){
        var $this = $(this),
            user = $this.text(),    // secent user name selected
            status = $this.parent().attr('class'),        // secent user status selected
            statusClass = 'text-primary',   // helper status on chatbox heading
            borderClass = 'border-primary';   // helper status on border color

        if (status == 'offline') { statusClass = 'text-danger', borderClass = 'border-danger' }
        else if (status == 'idle') { statusClass = 'text-warning', borderClass = 'border-warning' }
        else if (status == 'disable') { statusClass = 'text-midnight', borderClass = 'border-midnight' }
        
        // set new template for chat heading
        var tmpl_chatHeading = '<a data-toggle="chatbox-close" href="#" class="pull-right text-sm text-silver">'
                    +'        <i class="fa fa-times"></i>'
                    +'    </a>'
                    +'    <i class="fa fa-circle-o '+ statusClass +'" title="'+ status +'"></i>' + user;

        // apply to chatbox
        $('.chatbox-heading').html(tmpl_chatHeading).find('[data-toggle="chatbox-close"]').on('click', function(e){
            e.preventDefault();

            // show scroll on module
            $('.module[data-toggle="niceScroll"]').getNiceScroll().show();
            
            // hide chatbox
            $('.chatbox').removeClass('show');
        });
        $('.chatbox-body').find('.chat-in > .chat-user').text(user);
        $('.chat-form .chat-status').html('<i class="fa fa-spinner fa-spin"></i>  ' + user + ' is type...');
        // adjust the border color with the status
        $('.chatbox-body .chat-in').removeClass('border-primary border-danger border-warning border-midnight')
            .addClass(borderClass);
    });
    $(document).on('submit', '.chat-form', function(e){
        e.preventDefault();

        var $this = $(this),
            data = $this.serializeArray(),
            data_chat = data[0]['value'],
            template_out = '<div class="chat-out">'
                        +'    <div class="chat-user">Me</div>'
                        +'    <div class="chat-msg">'
                        +'        <p>'+ data_chat +'</p>'
                        +'        <time class="chat-time" datetime="">Now</time>'
                        +'    </div>'
                        +'</div>';

        $('.chatbox-body').append(template_out);
        var scrollResize = $('.chatbox-body[data-toggle="niceScroll"]').getNiceScroll().resize(),
            getScrollBottom = scrollResize[0].page.h;
        $('.chatbox-body').animate({scrollTop: getScrollBottom}, 300)

        // clear data_chat
        $('[name="send_chat"]').val('').focus;
    });
    // END CHAT SIMULATION
});