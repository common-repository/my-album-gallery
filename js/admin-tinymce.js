(function() {
	"use strict";
	tinymce.PluginManager.add('mygallery', function( editor, url ) {

		function getParentMyGallery( node ) {
			while ( node && node.nodeName !== 'BODY' ) {
				if ( isMyGallery( node ) ) {
					return node;
				}

				node = node.parentNode;
			}
		}

		function isMyGallery( node ) {
			return node && /mygallery-tinymce-view/.test( node.className );
		}

		function unselectMyGallery( dom ) {
			dom.removeClass(dom.select('div.mygallery-tinymce-selected'), 'mygallery-tinymce-selected');
		}

		editor.on( 'BeforeSetContent', function( event ) {
			if ( ! event.content ) {
				return;
			}

			var shortcode_tag = window.MYG_SHORTCODE || 'mygallery',
				regexp = new RegExp('\\[' + shortcode_tag + ' ([^\\]]*)\\]', 'g');

			event.content = event.content.replace( regexp, function( match ) {

				var data = window.encodeURIComponent( match ),
					idRegex = / id=\"(.*?)\"/ig,
					idMatch = idRegex.exec( match),
					id = idMatch ? idMatch[1] : 0;

				return '<div class="mygallery-tinymce-view mceNonEditable" data-mygallery="' + data + '" contenteditable="false" data-mce-resize="false" data-mce-placeholder="1" data-mygallery-id="' + id + '">' +
					'  <div class="mygallery-tinymce-toolbar">' +
					'    <a class="dashicons dashicons-edit mygallery-tinymce-toolbar-edit" href="post.php?post=' + id + '&action=edit" target="_blank">&nbsp;</a>' +
					'    <div class="dashicons dashicons-no-alt mygallery-tinymce-toolbar-delete">&nbsp;</div>' +
					'  </div>' +
					'  <div class="mygallery-pile">' +
					'    <div class="mygallery-pile-inner">' +
					'      <div class="mygallery-pile-inner-thumb">&nbsp;</div>' +
					'    </div>' +
					'  </div>' +
					'  <div class="mygallery-tinymce-title">&nbsp;</div>' +
					'  <div class="mygallery-tinymce-count">&nbsp;</div>' +
					'</div>';
			});
		});

		editor.on( 'LoadContent', function( event ) {
			if ( ! event.content ) {
				return;
			}

			var dom = editor.dom;

			// Replace the mygallery node with the shortcode
			tinymce.each( dom.select( 'div[data-mygallery]', event.node ), function( node ) {

				if ( !dom.hasClass(node, 'mygallery-tinymce-databound') ) {
					dom.addClass(node, 'mygallery-tinymce-databound');

					//we need to post to our ajax handler and get some gallery info
					var id = dom.getAttrib( node, 'data-mygallery-id'),
						nonce = jQuery('#mygallery-timnymce-action-nonce').val(),
						data = 'action=mygallery_tinymce_load_info&mygallery_id=' + id + '&nonce=' + nonce;

					jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: data,
						dataType: 'JSON',
						success: function(data) {
							var titleDiv = dom.select( '.mygallery-tinymce-title', node),
								countDiv = dom.select( '.mygallery-tinymce-count', node),
								galleryImg = dom.select( '.mygallery-pile-inner-thumb', node );

							if (titleDiv && titleDiv.length) {
								titleDiv[0].textContent = data.name;
							}
							if (countDiv && countDiv.length) {
								countDiv[0].textContent = data.count;
							}
							if (galleryImg && galleryImg.length) {
								jQuery(galleryImg[0]).replaceWith('<img src="' + data.src + '" />');
							}
						}
					});
				}
			});
		});

		editor.on( 'PreProcess', function( event ) {
			var dom = editor.dom;

			// Replace the mygallery node with the shortcode
			tinymce.each( dom.select( 'div[data-mygallery]', event.node ), function( node ) {
				// Empty the wrap node
				if ( 'textContent' in node ) {
					node.textContent = '\u00a0';
				} else {
					node.innerText = '\u00a0';
				}
			});
		});

		editor.on( 'PostProcess', function( event ) {
			if ( event.content ) {
				event.content = event.content.replace( /<div [^>]*?data-mygallery="([^"]*)"[^>]*>[\s\S]*?<\/div>/g, function( match, shortcode ) {
					if ( shortcode ) {
						return '<p>' + window.decodeURIComponent( shortcode ) + '</p>';
					}
					return ''; // If error, remove the mygallery view
				});
			}
		});

		editor.on( 'mouseup', function( event ) {
			var dom = editor.dom,
				node = event.target,
				fg = getParentMyGallery( node );

			// Don't trigger on right-click
			if ( event.button !== 2 ) {



				if (fg) {
					//we have clicked somewhere in the mygallery element

					if (node.nodeName === 'A' && dom.hasClass(node, 'mygallery-tinymce-toolbar-edit')) {
						//alert('EDIT : ' + dom.getAttrib( fg, 'data-mygallery-id' ))
					} else if (node.nodeName === 'DIV' && dom.hasClass(node, 'mygallery-tinymce-toolbar-delete')) {
						//alert('DELETE : ' + dom.getAttrib( fg, 'data-mygallery-id' ))
						dom.remove(fg);
					} else {

						if (!dom.hasClass(fg, 'mygallery-tinymce-selected')) {
							unselectMyGallery(dom);
							dom.addClass(fg, 'mygallery-tinymce-selected');
						}

					}
				} else {
					unselectMyGallery(dom);
				}
			}
		});
	});
})();