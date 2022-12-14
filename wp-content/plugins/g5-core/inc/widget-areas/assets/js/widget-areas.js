(function ($) {
	"use strict";

	var $widgetTemplate = '',
		$widgetArea = '',
		$widgetWrap = '';

	var GF_Widget_Areas = {
		init : function() {
			$widgetTemplate = $('#g5core-add-widget-template');
			$widgetArea = $('#widgets-right');
			$widgetWrap = $('.sidebars-column-' + $widgetArea.children().length);

			GF_Widget_Areas.addFormHtml();
			GF_Widget_Areas.addDelButton();
			GF_Widget_Areas.deleteWidgetArea();
			GF_Widget_Areas.event();
		},
		addFormHtml : function() {
			$widgetWrap.append($widgetTemplate.html());
		},
		event : function() {

		},
		addDelButton : function() {
			$('.sidebar-g5core-widgets-custom .widgets-sortables').append('<span class="g5core-widget-area-delete"></span>');
		},
		deleteWidgetArea : function() {
			$widgetArea.on('click','.g5core-widget-area-delete',function() {
				if (!confirm(g5core_widget_areas_variable.confirm_delete)) {
					return;
				}

				var $widget = $(this).parent(),
					widget_name = $widget.attr('id'),
					nonce = $('input[name="g5core-widget-areas-nonce"]').val();

				$.ajax({
					type: "POST",
					url: g5core_widget_areas_variable.ajax_url,
					data: {
						action: 'g5core_delete_widget_area',
						name: widget_name,
						_wpnonce: nonce
					},

					success: function (response) {
						if (response.trim() == 'widget-area-deleted') {
							$widget.parent().slideUp(200).remove();
						}
					}
				});

			});
		}
	};

	$(function () {
		GF_Widget_Areas.init();
	});
})(jQuery);
