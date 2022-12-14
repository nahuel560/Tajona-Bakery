(function ($) {
	"use strict";
	vc.atts.g5element_typography = {
		parse: function(param) {
			var $field = this.content().find('[data-vc-shortcode-param-name="' + param.param_name + '"]');
			var data = vc.atts.g5element_typography.extractValues.call(this, param, $field);
			return encodeURIComponent(JSON.stringify(data));
		},
		extractValues: function(param, $el) {
			var data = {};
			$el.find('[data-element-name]').each(function() {
				var $this = $(this);
				data[$this.data('element-name')] = $this.val();
			});
			console.log(data);
			return data;
		},
		init: function (param, $field) {
			var $font_variants = $field.find('.g5element-field-typography-variants-select'),
				$variants_font_weight = $field.find('[data-element-name="font_weight"]'),
				$variants_font_style = $field.find('[data-element-name="font_style"]'),
				$font_family = $field.find('[data-element-name="font_family"]'),
				$color = $field.find('.g5element-vc_gel_color-color input'),
				$select_color = $field.find('.g5element-vc_gel_color-select > select');

			$font_family.on('change', function () {
				var $this = $(this),
					current_font_family = $this.val(),
					variants = $this.find('option[value="' + current_font_family + '"]').data('font-variants');
				$font_variants.html($("<option>").val('').html('Default'));

				if (variants !== undefined) {
					variants = (variants + '').split('|');
					$.each(variants, function (index, value) {
						$font_variants.append( $("<option>").val(value).html(value));
					});
				}
				$font_variants.trigger('change');
			});

			$font_variants.on('change', function () {
				var variant = $(this).val();
				if (variant !== '') {
					if (variant.indexOf('italic') != -1) {
						$variants_font_style.val('italic');
					}
					else {
						$variants_font_style.val('normal');
					}
					variant = variant.replace('italic', '');
					$variants_font_weight.val(variant === '' ? '400' : (variant === 'regular' ? '400' : variant));
				}
				else {
					$variants_font_style.val('');
					$variants_font_weight.val('');
				}
			});

			$select_color.on('change', function () {
				var $this = $(this),
					$color_wrap = $(this).closest('.g5element-field-typography-color'),
					color_name = $this.val();
				if (color_name === 'custom') {
					$color_wrap.find('.g5element-vc_gel_color-color').show();
				}
				else {
					$color_wrap.find('.g5element-vc_gel_color-color').hide();
				}

				vc.atts.g5element_typography.setColorValue($color_wrap);
			});

			if ($().wpColorPicker) {
				$color.wpColorPicker({
					change: function (event, ui) {
						setTimeout(function () {
							vc.atts.g5element_typography.setColorValue($(event.target).closest('.g5element-field-typography-color'));
						}, 10);
					},
					clear: function (event, ui) {
						setTimeout(function () {
							vc.atts.g5element_typography.setColorValue($(event.target).closest('.g5element-field-typography-color'));
						}, 10);
					}
				});
			}
		},
		setColorValue: function ($wrap) {
			var $field = $wrap.find('[data-element-name]'),
				$select = $wrap.find('.g5element-vc_gel_color-select > select'),
				$color = $wrap.find('.g5element-vc_gel_color-color input'),
				color_name = $select.val(),
				color_code = $color.val();
			$field.val(color_name === 'custom' ? color_code : color_name);
		}
	}
})(jQuery);
