//

(function(MYG, $, undefined) {
	"use strict";
	MYG.loadGalleries = function() {
		$('.mygallery-modal-wrapper .spinner').addClass('is-active');
		$('.mygallery-modal-reload').hide();
		var data = 'action=mygallery_load_galleries' +
			'&mygallery_load_galleries=' + $('#mygallery_load_galleries').val() +
			'&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			success: function(data) {
				$('.mygallery-attachment-container').html(data);
				MYG.clearSelection();
			},
			complete: function() {
				$('.mygallery-modal-wrapper .spinner').removeClass('is-active');
				$('.mygallery-modal-reload').show();
			}
		});
	};

	//hook up the extensions search
	MYG.bindEditorButton = function() {
		$('.mygallery-modal-trigger').on('click', function(e) {
			e.preventDefault();
			$('.mygallery-modal-wrapper').show();
			if ( $('.mygallery-modal-loading').length ) {
				MYG.loadGalleries();
			} else {
				MYG.clearSelection();
			}
		});
	};

	MYG.bindModalElements = function() {
		$('.media-modal-close, .mygallery-modal-cancel').on('click', function() {
			$('.mygallery-modal-wrapper').hide();
		});

		$('.mygallery-modal-reload').on('click', function(e) {
			e.preventDefault();
			MYG.loadGalleries();
		});

		$('.mygallery-modal-wrapper').on('click', '.mygallery-gallery-select', function(e) {
			var $this = $(this);
			if ( $this.is('.mygallery-add-gallery') ) {
				//if the add icon is click then do nothing
				return;
			} else {
				$('.mygallery-gallery-select').removeClass('selected');
				$(this).addClass('selected');
				MYG.changeSelection();
			}
		});

		$('.mygallery-modal-insert').on('click', function(e) {
			e.preventDefault();
			if ( $(this).attr('disabled') ) {
				return;
			}
			var shortcode_tag = window.MYG_SHORTCODE || 'mygallery',
				shortcode = '[' + shortcode_tag + ' id="' + $('.mygallery-gallery-select.selected').data('mygallery-id') + '"]';
			wp.media.editor.insert(shortcode);
			$('.mygallery-modal-wrapper').hide();
		});
	};

	MYG.changeSelection = function() {
		var selected = $('.mygallery-gallery-select.selected');
		if (selected.length) {
			$('.mygallery-modal-insert').removeAttr('disabled');
		} else {
			$('.mygallery-modal-insert').attr('disabled', 'disabled');
		}
	};

	MYG.clearSelection = function() {
		$('.mygallery-gallery-select').removeClass('selected');
		MYG.changeSelection();
	};

	$(function() { //wait for ready
		MYG.bindEditorButton();
		MYG.bindModalElements();
	});
}(window.MYG = window.MYG || {}, jQuery));