(function ($) {
	"use strict";
	vc.atts.g5element_color = {
		parse: function(param) {
			var $field = this.content().find('[data-vc-shortcode-param-name="' + param.param_name + '"]');
			var color_name = $field.find('.g5element-vc_gel_color-select > select').val();
			if (color_name !== 'custom') {
				return color_name;
			}
			return $field.find('.g5element-vc_gel_color-color input').val();
		},
		init: function (param, $field) {
			var self = this,
				$inputField = $field.find('.g5element_color_field');
			if ($().wpColorPicker) {
				$field.find('.g5element-vc_gel_color-color input').wpColorPicker({
					change: function () {
						setTimeout(function () {
							$field.find('.g5element-vc_gel_color-select > select').trigger('change');
						}, 10);
					},
					clear: function (event, ui) {
						setTimeout(function () {
							$field.find('.g5element-vc_gel_color-select > select').trigger('change');
						}, 10);
					}
				});
			}

			$field.find('.g5element-vc_gel_color-select > select').on('change', function () {
				var $this = $(this),
					color_name = $this.val();
				if (color_name === 'custom') {
					$(this).closest('.g5element-vc_gel_color-inner').find('.g5element-vc_gel_color-color').show();
				}
				else {
					$(this).closest('.g5element-vc_gel_color-inner').find('.g5element-vc_gel_color-color').hide();
				}

				$inputField.val(vc.atts.g5element_color.parse.call(self, param));
				$inputField.trigger('change');
			});
		}
	}
})(jQuery);
