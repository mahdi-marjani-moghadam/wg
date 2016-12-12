$(function(){
	'use strict';

	
	// JQUERY UI SORTABLE
    $('[data-toggle="sortable-widget"]').each(function(){
        var $this = $(this),
            containment = ($this.data('containment') === undefined) ? '.content' : $this.data('containment'),
            connectWith = ($this.data('connect') === undefined) ? '[data-toggle="sortable-widget"]' : $this.data('connect');

        $this.sortable({
            placeholder: 'sortable-widget-item sortable-placeholder',
            forcePlaceholderSize: true,
            revert: "invalid",
            handle: ".sortable-widget-handle",
            items: ".sortable-widget-item",
            connectWith: connectWith,
            dropOnEmpty: true,
            containment: containment
        });
    });

    $('[data-toggle="sortable-list"]').each(function(){
        var $this = $(this),
            containment = ($this.data('containment') === undefined) ? $this : $this.data('containment'),
            connectWith = ($this.data('connect') === undefined) ? '[data-toggle="sortable-list"]' : $this.data('connect');

        $this.sortable({
            placeholder: 'sortable-list-item sortable-placeholder',
            forcePlaceholderSize: true,
            revert: "invalid",
            handle: ".sortable-list-handle",
            items: ".sortable-list-item",
            connectWith: connectWith,
            dropOnEmpty: true,
            containment: containment
        });
    });
    // END JQUERY UI SORTABLE
});