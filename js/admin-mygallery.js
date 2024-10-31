(function (MYGALLERY, $, undefined) {
	"use strict";
    MYGALLERY.media_uploader = false;
	MYGALLERY.RequestState1 = true;
    MYGALLERY.previous_post_id = 0;
    MYGALLERY.attachments = [];
    MYGALLERY.selected_attachment_id = 0;

    MYGALLERY.calculateAttachmentIds = function() {
        var sorted = [];
        $('.mygallery-attachments-list li:not(.add-attachment)').each(function() {
            sorted.push( $(this).data('attachment-id') );
        });

        $('#mygallery_attachments').val( sorted.join(',') );
    };

    MYGALLERY.initAttachments = function() {
        var attachments = $('#mygallery_attachments').val();
		if (attachments) {
			MYGALLERY.attachments = $.map(attachments.split(','), function (value) {
				return parseInt(value, 10);
			});
		}
    };

	MYGALLERY.settingsChanged = function() {
		var selectedTemplate = $('#MyGallerySettings_GalleryTemplate').val();

		//hide all template fields
		$('.mygallery-metabox-settings .gallery_template_field').not('.gallery_template_field_selector').hide();

		//show all fields for the selected template only
		$('.mygallery-metabox-settings .gallery_template_field-' + selectedTemplate).show();

		//include a preview CSS if possible
		MYGALLERY.includePreviewCss();

		//trigger a change so custom template js can do something
		MYGALLERY.triggerTemplateChangedEvent();
	};

	MYGALLERY.initSettings = function() {
		$('#MyGallerySettings_GalleryTemplate').change(MYGALLERY.settingsChanged);

		//include our selected preview CSS
		MYGALLERY.includePreviewCss();

		//trigger this onload too!
		MYGALLERY.triggerTemplateChangedEvent();
		MYGALLERY.settingsChanged();
	};

	MYGALLERY.includePreviewCss = function() {
		var selectedPreviewCss = $('#MyGallerySettings_GalleryTemplate').find(":selected").data('preview-css');

		if ( selectedPreviewCss ) {
			$('#mygallery-preview-css').remove();
			$('head').append('<link id="mygallery-preview-css" rel="stylesheet" href="' + selectedPreviewCss +'" type="text/css" />');
		}
	};

	MYGALLERY.triggerTemplateChangedEvent = function() {
		var selectedTemplate = $('#MyGallerySettings_GalleryTemplate').val();
		$('body').trigger('mygallery-gallery-template-changed-' + selectedTemplate );
	};

    MYGALLERY.addAttachmentToGalleryList = function(attachment) {

        if ($.inArray(attachment.id, MYGALLERY.attachments) !== -1) return;

        var $template = $($('#mygallery-attachment-template').val());

        $template.attr('data-attachment-id', attachment.id);

        $template.find('img').attr('src', attachment.src);

        $('.mygallery-attachments-list .add-attachment').before($template);

        MYGALLERY.attachments.push( attachment.id );

        MYGALLERY.calculateAttachmentIds();
    };

    MYGALLERY.removeAttachmentFromGalleryList = function(id) {
        var index = $.inArray(id, MYGALLERY.attachments);
        if (index !== -1) {
            MYGALLERY.attachments.splice(index, 1);
        }
		$('[data-attachment-id="' + id + '"]').remove();

        MYGALLERY.calculateAttachmentIds();
    };

	MYGALLERY.showAttachmentInfoModal = function(id) {
		MYGALLERY.openMediaModal( id );
	};

	MYGALLERY.openMediaModal = function(selected_attachment_id) {
		if (!selected_attachment_id) { selected_attachment_id = 0; }
		MYGALLERY.selected_attachment_id = selected_attachment_id;

		//if the media frame already exists, reopen it.
		if ( MYGALLERY.media_uploader !== false ) {
			// Open frame
			MYGALLERY.media_uploader.open();
			return;
		}

		// Create the media frame.
		MYGALLERY.media_uploader = wp.media.frames.file_frame = wp.media({
			title: MYGALLERY.mediaModalTitle,
			//frame: 'post',
			button: {
				text: MYGALLERY.mediaModalButtonText
			},
			multiple: 'add',  // Set to allow multiple files to be selected
			toolbar:  'select'
		});

		// When an image is selected, run a callback.
		MYGALLERY.media_uploader
			.on( 'select', function() {
				var attachments = MYGALLERY.media_uploader.state().get('selection').toJSON();

				$.each(attachments, function(i, item) {
					if (item && item.id && item.sizes) {
						if (item.sizes.thumbnail) {
							var attachment = {
								id: item.id,
								src: item.sizes.thumbnail.url
							};
						} else {
							//thumbnail could not be found for whatever reason
							var attachment = {
								id: item.id,
								src: item.url
							};
						}

						MYGALLERY.addAttachmentToGalleryList(attachment);
					} else {
						//there was a problem adding the item! Move on to the next
					}
				});
			})
			.on( 'open', function() {
				var selection = MYGALLERY.media_uploader.state().get('selection');
				if (selection) { selection.set(); }   //clear any previos selections

				if (MYGALLERY.selected_attachment_id > 0) {
					var attachment = wp.media.attachment(MYGALLERY.selected_attachment_id);
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );
				} else {
					//would be nice to have all previously added media selected
				}
			});

		// Finally, open the modal
		MYGALLERY.media_uploader.open();
	};

	MYGALLERY.initUsageMetabox = function() {
		$('#mygallery_create_page').on('click', function(e) {
			e.preventDefault();

			$('#mygallery_create_page_spinner').addClass('is-active');
			var data = 'action=mygallery_create_gallery_page' +
				'&mygallery_id=' + $('#post_ID').val() +
				'&mygallery_create_gallery_page_nonce=' + $('#mygallery_create_gallery_page_nonce').val() +
				'&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(data) {
					//refresh page
					location.reload();
				}
			});
		});
	};

	MYGALLERY.initThumbCacheMetabox = function() {
		$('#mygallery_clear_thumb_cache').on('click', function(e) {
			e.preventDefault();

			$('#mygallery_clear_thumb_cache_spinner').addClass('is-active');
			var data = 'action=mygallery_clear_gallery_thumb_cache' +
				'&mygallery_id=' + $('#post_ID').val() +
				'&mygallery_clear_gallery_thumb_cache_nonce=' + $('#mygallery_clear_gallery_thumb_cache_nonce').val() +
				'&_wp_http_referer=' + encodeURIComponent($('input[name="_wp_http_referer"]').val());

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function(data) {
					alert(data);
					$('#mygallery_clear_thumb_cache_spinner').removeClass('is-active');
				}
			});
		});
	};

    MYGALLERY.adminReady = function () {
        $('.upload_image_button').on('click', function(e) {
            e.preventDefault();
			MYGALLERY.mediaModalTitle = $(this).data( 'uploader-title' );
			MYGALLERY.mediaModalButtonText = $(this).data( 'uploader-button-text' );
			MYGALLERY.openMediaModal(0);
        });

        MYGALLERY.initAttachments();

		MYGALLERY.initSettings();

		MYGALLERY.initUsageMetabox();

		MYGALLERY.initThumbCacheMetabox();

        $('.mygallery-attachments-list')
            .on('click' ,'a.remove', function(e) {
				e.preventDefault();
                var $selected = $(this).parents('li:first'),
				attachment_id = $selected.data('attachment-id');
				if(confirm('Are you sure you want to delete?')){
					//MIRUPLOAD.removeAttachmentFromGalleryList(attachment_id);
					MYGALLERY.deleteImage(attachment_id);
				}
            })
			.on('click' ,'a.info', function() {
				var $selected = $(this).parents('li:first'),
					attachment_id = $selected.data('attachment-id');
				MYGALLERY.showAttachmentInfoModal(attachment_id);
			})
            .sortable({
                items: 'li:not(.add-attachment)',
                distance: 10,
                placeholder: 'attachment placeholder',
                stop : function() {
                    MYGALLERY.calculateAttachmentIds();
                }
            });

    };
	
	MYGALLERY.deleteImage = function(attach_id) {
		if(MYGALLERY.RequestState1 == true) {
			MYGALLERY.RequestState1 = false;	
			MYGALLERY.CreateModal('');
			$('#mygallery_modal_content_wrap').prepend('<div id="mygallery_spinner" style="text-align:center"><span class="mygallery_spinnerx16"></span><br/> Deleting image...</div>');
			$.ajax({
				url: mygallery_conf.ajaxURL,
				type: "POST",
				dataType: "JSON",
				data:{
					attach_id : attach_id,
					action : mygallery_conf.ajaxActions.mygallery_delete_image.action,
					nonce : mygallery_conf.ajaxNonce,
				},
				success: function(data) {
					var result = data.mygallery_error_data;
					var attachment_id = data.attachment_id;
					if(!parseInt(data.mygallery_error)){
						MYGALLERY.removeAttachmentFromGalleryList(attachment_id);
						$('#mygallery_modal_content_wrap').html(result)
						.delay(2000, "steps")
						.queue("steps", function(next) {
							$('#mygallery_modal_wrap').remove();
							next();
						})
						.dequeue( "steps" ); 
						
					}else{
						$('#mygallery_modal_content_wrap').html(result)
						.delay(2000, "steps")
						.queue("steps", function(next) {
							$('#mygallery_modal_wrap').remove();
							next();
						})
						.dequeue( "steps" ); 
					}
				},
				complete: function() {
					MYGALLERY.RequestState1 = true;
				}
			});
		}
	};
	
	MYGALLERY.CreateModal = function(modal_content){
		var htm_el = '';
			htm_el += '<div id="mygallery_modal_wrap">';
				htm_el += '<div class="mygallery_container">';
					htm_el += '<div id="mygallery_modal_content_wrap">';
						htm_el += modal_content;
					htm_el += '</div>';
				htm_el += '</div>';
			htm_el += '</div>';
			
			$("body").append(htm_el);
	};

}(window.MYGALLERY = window.MYGALLERY || {}, jQuery));

jQuery(function ($) {
	if ( $('#mygallery_attachments').length > 0 ) {
		MYGALLERY.adminReady();
	}
});