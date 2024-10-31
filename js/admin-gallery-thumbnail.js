(function (MYGALLERY_THUMBNAIL_TEMPLATE, $, undefined) {
	"use strict";
	MYGALLERY_THUMBNAIL_TEMPLATE.setPreviewClasses = function() {

		var $previewImage = $('.mygallery-thumbnail-preview'),
			border_style = $('input[name="mygallery_settings[thumbnail_border-style]"]:checked').val(),
			hover_effect = $('input[name="mygallery_settings[thumbnail_hover-effect]"]:checked').val();

		$previewImage.attr('class' ,'mygallery-thumbnail-preview mygallery-container mygallery-thumbnail ' + hover_effect + ' ' + border_style);
	};

	MYGALLERY_THUMBNAIL_TEMPLATE.adminReady = function () {
		$('body').on('mygallery-gallery-template-changed-thumbnail', function() {
			MYGALLERY_THUMBNAIL_TEMPLATE.setPreviewClasses();
		});

		$('input[name="mygallery_settings[thumbnail_border-style]"], input[name="mygallery_settings[thumbnail_hover-effect]"]').change(function() {
			MYGALLERY_THUMBNAIL_TEMPLATE.setPreviewClasses();
		});

		$('.mygallery-thumbnail-preview').on("click", function(e) {
			e.preventDefault();
		});
	};

}(window.MYGALLERY_THUMBNAIL_TEMPLATE = window.MYGALLERY_THUMBNAIL_TEMPLATE || {}, jQuery));

jQuery(function () {
	MYGALLERY_THUMBNAIL_TEMPLATE.adminReady();
});