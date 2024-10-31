<?php

/*
 * MyGallery Admin Gallery MetaBoxes class
 */

if ( ! class_exists( 'MyGallery_Admin_Gallery_MetaBoxes' ) ) {

	class MyGallery_Admin_Gallery_MetaBoxes {

		private $_gallery;

		public function __construct() {
			
			//add our mygallery metaboxes
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_to_gallery' ) );

			//save extra post data for a gallery
			add_action( 'save_post', array( $this, 'save_gallery' ) );

			//save custom field on a page or post
			add_Action( 'save_post', array( $this, 'attach_gallery_to_post' ), 10, 2 );

			//whitelist metaboxes for our gallery postype
			add_filter( 'mygallery_metabox_sanity', array( $this, 'whitelist_metaboxes' ) );
		}
		
		public function whitelist_metaboxes() {
			return array(
				MYG_CPT_GALLERY => array(
					'whitelist'  => apply_filters( 'mygallery_metabox_sanity_mygallery',
						array(
							'submitdiv',
							'slugdiv',
							'postimagediv',
							'mygallery_items',
							'mygallery_options',
							'mygallery_help',
							'mygallery_pages',
							'mygallery_thumb_cache'
						) ),
					'contexts'   => array( 'normal', 'advanced', 'side', ),
					'priorities' => array( 'high', 'core', 'default', 'low', ),
				)
			);
		}

		public function add_meta_boxes_to_gallery() {
			global $post;

			add_meta_box(
				'mygallery_items',
				__( 'MyGallery Items', 'mygallery' ),
				array( $this, 'render_gallery_media_metabox' ),
				MYG_CPT_GALLERY,
				'normal',
				'high'
			);
			add_meta_box(
				'mygallery_options',
				__( 'MyGallery Options', 'mygallery' ),
				array( $this, 'mygallery_options' ),
				MYG_CPT_GALLERY,
				'normal',
				'high'
			);
		}
		
		public function mygallery_options(){	
			include 'metaboxes/style.php';
			include 'metaboxes/mygallery_options.php';
		}

		public function get_gallery( $post ) {
			if ( ! isset($this->_gallery) ) {
				$this->_gallery = MyGallery::get( $post );
			}

			return $this->_gallery;
		}

		public function save_gallery( $post_id ) {
			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// verify nonce
			if ( array_key_exists( MYG_CPT_GALLERY . '_nonce', $_POST ) &&
				wp_verify_nonce( $_POST[MYG_CPT_GALLERY . '_nonce'], plugin_basename( MYG_FILE ) )
			) {
				//if we get here, we are dealing with the Gallery custom post type

				$attachments = apply_filters( 'mygallery_save_gallery_attachments', explode( ',', $_POST[MYG_META_ATTACHMENTS] ) );
				update_post_meta( $post_id, MYG_META_ATTACHMENTS, $attachments );
				if(!empty($attachments)) {
					foreach($attachments as $key => $image_id) {
						wp_update_post(
							array(
								'ID' => $image_id, 
								'post_parent' => $post_id
							)
						);
					}
				}
				
				$myg_gallery_caption = sanitize_text_field($_POST['myg_gallery_caption']);
				$myg_gallery_description = sanitize_text_field($_POST['myg_gallery_description']);
				
				update_post_meta( $post_id, 'myg_gallery_caption', $myg_gallery_caption );
				update_post_meta( $post_id, 'myg_gallery_description', $myg_gallery_description );
				
				foreach($_POST as $key => $value) {
					if(strstr($key, 'myg_')) {
						update_post_meta($post_id, $key, $value);
					}
				}
			}
		}

		public function attach_gallery_to_post( $post_id, $post ) {

			// check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			//only do this check for a page or post
			if ( 'post' == $post->post_type || 'page' == $post->post_type ) {

                do_action( 'mygallery_start_attach_gallery_to_post', $post_id );

				//Clear any mygallery usages that the post might have
				delete_post_meta( $post_id, MYG_META_POST_USAGE );

				//get all mygallery shortcodes that are on the page/post
				$gallery_shortcodes = mygallery_extract_gallery_shortcodes( $post->post_content );

                if ( is_array( $gallery_shortcodes ) && count( $gallery_shortcodes ) > 0 ) {

                    foreach ( $gallery_shortcodes as $id => $shortcode ) {
                        //if the content contains the mygallery shortcode then add a custom field
                        add_post_meta( $post_id, MYG_META_POST_USAGE, $id, false );

                        do_action( 'mygallery_attach_gallery_to_post', $post_id, $id );
                    }
                }
			}
		}

		public function render_gallery_media_metabox( $post ) {
			$gallery = $this->get_gallery( $post );

			wp_enqueue_media();

			?>
			<input type="hidden" name="<?php echo MYG_CPT_GALLERY; ?>_nonce"
				   id="<?php echo MYG_CPT_GALLERY; ?>_nonce"
				   value="<?php echo wp_create_nonce( plugin_basename( MYG_FILE ) ); ?>"/>
			<input type="hidden" name='mygallery_attachments' id="mygallery_attachments"
				   value="<?php echo $gallery->attachment_id_csv(); ?>"/>
			<div>
				<ul class="mygallery-attachments-list">
				<?php
				if ( $gallery->has_attachments() ) {
					foreach ( $gallery->attachments() as $attachment ) {
						$this->render_gallery_item( $attachment );
					}
				} ?>
					<li class="add-attachment">
						<a href="#" data-uploader-title="<?php _e( 'Add Media To Gallery', 'mygallery' ); ?>"
						   data-uploader-button-text="<?php _e( 'Add Media', 'mygallery' ); ?>"
						   data-post-id="<?php echo $post->ID; ?>" class="upload_image_button"
						   title="<?php _e( 'Add Media To Gallery', 'mygallery' ); ?>">
							<div class="dashicons dashicons-camera"></div>
							<span><?php _e( 'Add Media', 'mygallery' ); ?></span>
						</a>
					</li>
				</ul>
				<div style="clear: both;"></div>
			</div>
			<textarea style="display: none" id="mygallery-attachment-template">
				<?php $this->render_gallery_item(); ?>
			</textarea>
		<?php

		}

		public function render_gallery_item( $attachment_post = false ) {
			if ( $attachment_post != false ) {
				$attachment_id = $attachment_post->ID;
				$attachment = wp_get_attachment_image_src( $attachment_id );
			} else {
				$attachment_id = '';
				$attachment = '';
			}
			$data_attribute = empty($attachment_id) ? '' : "data-attachment-id=\"{$attachment_id}\"";
			$img_tag        = empty($attachment) ? '<img width="150" height="150" />' : "<img width=\"150\" height=\"150\" src=\"{$attachment[0]}\" />";
			?>
			<li class="attachment details" <?php echo $data_attribute; ?>>
				<div class="attachment-preview type-image">
					<div class="thumbnail">
						<div class="centered">
							<?php echo $img_tag; ?>
						</div>
					</div>
					<a class="info" href="#" title="<?php _e( 'Edit Info', 'mygallery' ); ?>">
						<span class="dashicons dashicons-info"></span>
					</a>
					<a class="remove" href="#" title="<?php _e( 'Remove from gallery', 'mygallery' ); ?>">
						<span class="dashicons dashicons-dismiss"></span>
					</a>
				</div>
				<!--				<input type="text" value="" class="describe" data-setting="caption" placeholder="Caption this image…" />-->
			</li>
		<?php
		}
				
		public function text($id, $label, $desc = ''){
			global $post;
			$html = '';
			$html .= '<div class="myg_metabox_field">';
				$html .= '<label for="myg_' . $id . '">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<div class="field">';
					$html .= '<input type="text" id="myg_' . $id . '" name="myg_' . $id . '" value="' . get_post_meta($post->ID, 'myg_' . $id, true) . '" />';
					if($desc) {
						$html .= '<p>' . $desc . '</p>';
					}
				$html .= '</div>';
			$html .= '</div>';
			
			echo $html;
		}
		
		public function select($id, $label, $options, $desc = ''){
			global $post;
			$html = '';
			$html .= '<div class="myg_metabox_field">';
				$html .= '<label for="myg_' . $id . '">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<div class="field">';
					$html .= '<select id="myg_' . $id . '" name="myg_' . $id . '">' ;
					foreach($options as $key => $option) {
						if(get_post_meta($post->ID, 'myg_' . $id, true) == $key) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
					}
					$html .= '</select>';
					if($desc) {
						$html .= '<p>' . $desc . '</p>';
					}
				$html .= '</div>';
			$html .= '</div>';
			echo $html;
		}
		public function multiple($id, $label, $options, $desc = ''){
			global $post;
			$html = '';
			$html .= '<div class="myg_metabox_field">';
				$html .= '<label for="myg_' . $id . '">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<div class="field">';
					$html .= '<select multiple="multiple" id="myg_' . $id . '" name="myg_' . $id . '[]">' ;
					foreach($options as $key => $option) {
						if(is_array(get_post_meta($post->ID, 'myg_' . $id, true)) && in_array($key, get_post_meta($post->ID, 'myg_' . $id, true))) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						
						$html .= '<option ' . $selected . 'value="' . $key . '">' . $option . '</option>';
					}
					$html .= '</select>';
					if($desc) {
						$html .= '<p>' . $desc . '</p>';
					}
				$html .= '</div>';
			$html .= '</div>';
			echo $html;
		}
	
		public function textarea($id, $label, $desc = ''){
			global $post;
			$html = '';
			$html = '';
			$html .= '<div class="myg_metabox_field">';
				$html .= '<label for="myg_' . $id . '">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<div class="field">';
					$html .= '<textarea cols="120" rows="10" id="myg_' . $id . '" name="myg_' . $id . '">' . get_post_meta($post->ID, 'myg_' . $id, true) . '</textarea>';
					if($desc) {
						$html .= '<p>' . $desc . '</p>';
					}
				$html .= '</div>';
			$html .= '</div>';
			
			echo $html;
		}
	
		public function upload($id, $label, $desc = ''){
			global $post;
	
			$html = '';
			$html = '';
			$html .= '<div class="myg_metabox_field">';
				$html .= '<label for="myg_' . $id . '">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<div class="field">';
					$html .= '<input name="myg_' . $id . '" class="upload_field" id="myg_' . $id . '" type="text" value="' . get_post_meta($post->ID, 'myg_' . $id, true) . '" />';
					$html .= '<input class="upload_button" type="button" value="Browse" />';
					if($desc) {
						$html .= '<p>' . $desc . '</p>';
					}
				$html .= '</div>';
			$html .= '</div>';
			
			echo $html;
		}

	}
}
