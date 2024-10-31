(function (MYGALLERY_DEF_TEMPLATE, $, undefined) {
	"use strict";
	MYGALLERY_DEF_TEMPLATE.setPreviewClasses = function() {

		var $previewImage = $('.mygallery-thumbnail-preview'),
			border_style = $('input[name="mygallery_settings[default_border-style]"]:checked').val(),
			hover_effect = $('input[name="mygallery_settings[default_hover-effect]"]:checked').val(),
		    hover_effect_type = $('input[name="mygallery_settings[default_hover-effect-type]"]:checked').val();

		$previewImage.attr('class' ,'mygallery-thumbnail-preview mygallery-container mygallery-default ' + hover_effect + ' ' + border_style + ' ' + hover_effect_type);

		var $hoverEffectrow = $('.gallery_template_field-default-hover-effect');
		if ( hover_effect_type === '' ) {
			$hoverEffectrow.show();
		} else {
			$hoverEffectrow.hide();
		}
	};

	MYGALLERY_DEF_TEMPLATE.adminReady = function () {
		$('body').on('mygallery-gallery-template-changed-default', function() {
			MYGALLERY_DEF_TEMPLATE.setPreviewClasses();
		});

		$('input[name="mygallery_settings[default_border-style]"], input[name="mygallery_settings[default_hover-effect]"], input[name="mygallery_settings[default_hover-effect-type]"]').change(function() {
			MYGALLERY_DEF_TEMPLATE.setPreviewClasses();
		});

		$('.mygallery-thumbnail-preview').on("click", function(e) {
			e.preventDefault();
		});
	};

}(window.MYGALLERY_DEF_TEMPLATE = window.MYGALLERY_DEF_TEMPLATE || {}, jQuery));

jQuery(function () {
	MYGALLERY_DEF_TEMPLATE.adminReady();
});