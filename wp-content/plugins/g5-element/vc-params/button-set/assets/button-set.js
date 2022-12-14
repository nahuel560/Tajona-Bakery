(function ($) {
	"use strict";
	vc.atts.g5element_button_set = {
		init: function (param, $field) {
			var $inputField = $field.find('.g5element_button_set_field');
			$field.find('.g5element-field-button_set-field').on('change',function(){
				var value = '';
				$field.find('.g5element-field-button_set-field').each(function(){
					if ($(this).is(':checked')) {
						if (value === '') {
							value += $(this).val();
						} else  {
							value += ',' + $(this).val();
						}
					}
				});
				$inputField.val(value);
				$inputField.trigger('change');
			});
		}
	}
})(jQuery);
