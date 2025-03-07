<?php

if ( ! class_exists( 'MyGallery_Admin_Gallery_MetaBox_Fields' ) ) {

	class MyGallery_Admin_Gallery_MetaBox_Fields {

		function __construct() {
			//handle some default field types that all templates can reuse
			add_filter( 'mygallery_alter_gallery_template_field', array( $this, 'alter_gallery_template_field' ), 10, 2 );

			//render the different types of fields for our gallery settings
			add_action( 'mygallery_render_gallery_template_field', array( $this, 'render_gallery_template_field' ), 10, 3 );

			//allow changing of field values
			add_filter( 'mygallery_render_gallery_template_field_value', array( $this, 'check_lightbox_value' ), 10, 4 );
		}

		/**
		 * Renders a gallery template field into the gallery settings metabox for a MyGallery
		 *
		 * @param array $field
		 * @param       $gallery MyGallery
		 * @param       $template
		 */
		function render_gallery_template_field( $field = array(), $gallery, $template ) {
			$template_slug = $template['slug'];

			//only declare up front so no debug warnings are shown
			$type = $id = $desc = $default = $placeholder = $choices = $class = $spacer = $opactiy = null;

			extract( $field );

			$id = $template_slug . '_' . $id;

			$field['value'] = apply_filters( 'mygallery_render_gallery_template_field_value', $gallery->get_meta( $id, $default ), $field, $gallery, $template );

			$field_class = empty($class) ? '' : ' class="' . $class . '"';

			$field['choices'] = apply_filters( 'mygallery_render_gallery_template_field_choices', $choices, $field, $gallery );

			//allow for UI customization
			do_action( 'mygallery_render_gallery_template_field_before', $field, $gallery );

			echo '<div class="mygallery_metabox_field-' . $type . '">';

			switch ( $type ) {

				case 'html':
					echo $desc;
					$desc = '';
					break;

				case 'checkbox':
					if ( isset($gallery->settings[$id]) && $gallery->settings[$id] == 'on' ) {
						$field['value'] = 'on';
					} else if ( ! isset($gallery->settings) && $default == 'on' ) {
						$field['value'] = 'on';
					} else {
						$field['value'] = '';
					}

					$checked = 'on' === $field['value'] ? ' checked="checked"' : '';
					echo '<input' . $field_class . ' type="checkbox" id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']" value="on"' . $checked . ' />';
					break;

				case 'select':
					echo '<select' . $field_class . ' id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']">';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' selected="selected"';
						}
						echo '<option ' . $selected . ' value="' . $value . '">' . $label . '</option>';
					}

					echo '</select>';
					break;

				case 'radio':
					$i = 0;
					$spacer = isset($spacer) ? $spacer : '<br />';
					foreach ( $choices as $value => $label ) {
						$selected = '';
						if ( $field['value'] == $value ) {
							$selected = ' checked="checked"';
						}
						echo '<input' . $field_class . $selected . ' type="radio" name="' . MYG_META_SETTINGS . '[' . $id . ']"  id="MyGallerySettings_' . $id . $i . '" value="' . $value . '"> <label for="MyGallerySettings_' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo $spacer;
						}
						$i++;
					}
					break;

				case 'textarea':
					echo '<textarea' . $field_class . ' id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']" placeholder="' . $placeholder . '">' . esc_attr( $field['value'] ) . '</textarea>';

					break;

				case 'text':
					echo '<input' . $field_class . ' type="text" id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'colorpicker':

					$opacity_attribute = empty($opacity) ? '' : ' data-show-alpha="true"';

					echo '<input ' . $opacity_attribute . ' class="colorpicker" type="text" id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'number':
					$min = isset($min) ? $min : 0;
					$step = isset($step) ? $step : 1;
					echo '<input class="regular-text ' . $class . '" type="number" step="' . $step . '" min="' . $min .'" id="MyGallerySettings_' . $id . '" name="' . MYG_META_SETTINGS . '[' . $id . ']" placeholder="' . $placeholder . '" value="' . esc_attr( $field['value'] ) . '" />';

					break;

				case 'checkboxlist':
					$i = 0;
					foreach ( $choices as $value => $label ) {

						$checked = '';
						if ( isset($field['value'][$value]) && $field['value'][$value] == 'true' ) {
							$checked = 'checked="checked"';
						}

						echo '<input' . $field_class . ' ' . $checked . ' type="checkbox" name="' . MYG_META_SETTINGS . '[' . $id . '|' . $value . ']" id="MyGallerySettings_' . $id . $i . '" value="on"> <label for="MyGallerySettings_' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $choices ) - 1 ) {
							echo '<br />';
						}
						$i++;
					}

					break;
				case 'icon':
					$i = 0;
					$input_name = MYG_META_SETTINGS . '[' . $id . ']';
					$icon_html = '';
					foreach ( $choices as $value => $icon ) {
						$selected = ( $field['value'] == $value ) ? ' checked="checked"' : '';
						$icon_html .= '<input style="display:none" name="' . $input_name. '" id="MyGallerySettings_' . $id . $i . '" ' . $selected . ' type="radio" value="' . $value . '" tabindex="' . $i . '"/>';
						$title = $icon['label'];
						$img = $icon['img'];
						$icon_html .= '<label for="MyGallerySettings_' . $id . $i . '" title="' . $title . '"><img src="' . $img . '" /></label>';
						$i++;
					}
					echo $icon_html;
					break;

				case 'thumb_size':
					$width = is_array( $field['value'] ) ? $field['value']['width'] : 150;
					$height = is_array( $field['value'] ) ? $field['value']['height'] : 150;
					$crop = is_array( $field['value'] ) && array_key_exists( 'crop', $field['value'] ) ? $field['value']['crop'] : 0;
					$crop_checked = ( $crop == 1 ) ? ' checked="checked"' : '';
					echo '<label for="MyGallerySettings_' . $id . '_width">' . esc_html__( 'Width', 'mygallery' ) . '</label>';
					echo '<input class="small-text" type="number" step="1" min="0" id="MyGallerySettings_' . $id . '_width" name="' . MYG_META_SETTINGS . '[' . $id . '][width]" value="' . esc_attr( $width ) . '" />';
					echo '<label for="MyGallerySettings_' . $id . '_width">' . esc_html__( 'Height', 'mygallery' ) . '</label>';
					echo '<input class="small-text" type="number" step="1" min="0" id="MyGallerySettings_' . $id . '_height" name="' . MYG_META_SETTINGS . '[' . $id . '][height]" value="' . esc_attr( $height ) . '" />';
					echo '<input name="' . MYG_META_SETTINGS . '[' . $id . '][crop]" type="hidden" id="MyGallerySettings_' . $id . '_nocrop" value="0" />';
					echo '<input name="' . MYG_META_SETTINGS . '[' . $id . '][crop]" type="checkbox" id="MyGallerySettings_' . $id . '_crop" value="1"' . $crop_checked . '>';
					echo '<label for="MyGallerySettings_' . $id . '_crop">' . esc_html__( 'Crop thumbnail to exact dimensions', 'mygallery' ) . '</label>';
					break;

				default:
					do_action( 'mygallery_render_gallery_template_field_custom', $field, $gallery, $template );
					break;
			}

			if (!empty($suffix)) {
				echo $suffix;
			}

			echo '</div>';
			if ( isset( $desc ) ) {
				echo '<small>' . $desc . '</small>';
			}

			//allow for more customization
			do_action( 'mygallery_render_gallery_template_field_after', $field, $gallery );
		}

		function alter_gallery_template_field( $field, $gallery ) {
			if ( $field ) {
				switch ( $field['type'] ) {
					case 'thumb_link':
						$field['type'] = 'radio';
						$field['choices'] = $this->get_thumb_link_field_choices();
						break;
					case 'lightbox':
						$field['lightbox'] = true;
						$lightboxes = $this->get_lightbox_field_choices();
						if ( 1 === count( $lightboxes ) && array_key_exists( 'none', $lightboxes ) ) {
							$field['type'] = 'html';
							$field['desc'] = '<strong>' . esc_html__( 'You have no lightbox extensions activated!', 'mygallery' ) . '</strong><br />';
							$api = new MyGallery_Extensions_API();
							if ( $api->is_downloaded( false, MYG_FOOBOX_FREE_EXTENSION_SLUG ) ) {
								//just need to activate it
								$foobox_install_link = mygallery_build_admin_menu_url( array(
									'page' => 'mygallery-extensions',
									'extension' => MYG_FOOBOX_FREE_EXTENSION_SLUG,
									'action' => 'activate',
								));
								$field['desc'] .= '<a target="_blank" href="' . esc_url( $foobox_install_link ). '">' . esc_html__( 'Activate FooBox FREE right now!', 'mygallery' ) . '</a>';
							} else {
								//we need to download it
								$foobox_install_link = mygallery_build_admin_menu_url( array(
									'page' => 'mygallery-extensions',
									'extension' => MYG_FOOBOX_FREE_EXTENSION_SLUG,
									'action' => 'download',
								));
								$foobox_install_html = '<a target="_blank" href="' . esc_url( $foobox_install_link ) . '">' . esc_html__( 'Download and activate FooBox FREE', 'mygallery' ) . '</a>';
								$field['desc'] .= sprintf( esc_html__( '%s which works flawlessly with %s.', 'mygallery' ), $foobox_install_html, mygallery_plugin_name() );
							}
						} else {
							$field['type'] = 'select';
							$field['choices'] = $lightboxes;
						}
						break;
				}

				if ( isset($field['help']) && $field['help'] ) {
					$field['type'] = 'help';
				}
			}
			return $field;
		}

		function get_thumb_size_choices() {
			global $_wp_additional_image_sizes;
			$sizes = array();
			foreach( get_intermediate_image_sizes() as $s ){
				$sizes[ $s ] = array( 0, 0 );
				if ( in_array( $s, array( 'thumbnail', 'medium', 'large', ) ) ){
					$sizes[ $s ] = $s . ' (' . get_option( $s . '_size_w' ) . 'x' . get_option( $s . '_size_h' ) . ')';
				} else {
					if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
						$sizes[ $s ] = $s . ' (' . $_wp_additional_image_sizes[ $s ]['width'] . 'x' . $_wp_additional_image_sizes[ $s ]['height'] . ')';
				}
			}
			return $sizes;
		}

		function get_thumb_link_field_choices() {
			return apply_filters( 'mygallery_gallery_template_field_thumb_links', array(
				'image'  => esc_html__( 'Full Size Image', 'mygallery' ),
				'page'   => esc_html__( 'Image Attachment Page', 'mygallery' ),
				'custom' => esc_html__( 'Custom URL', 'mygallery' ),
				'none'   => esc_html__( 'Not linked', 'mygallery' ),
			) );
		}

		function get_lightbox_field_choices() {
			$lightboxes = apply_filters( 'mygallery_gallery_template_field_lightboxes', array() );
			$lightboxes['none'] = esc_html__( 'None', 'mygallery' );
			return $lightboxes;
		}

		/***
		 * Check if we have a lightbox value from FooBox free and change it if foobox free is no longer active
		 * @param $value
		 * @param $field
		 * @param $gallery
		 * @param $template
		 *
		 * @return string
		 */
		function check_lightbox_value($value, $field, $gallery, $template) {

			if ( isset( $field['lightbox'] ) ) {
				if ( 'foobox-free' === $value ) {
					if ( !class_exists( 'Foobox_Free' ) ) {
						return 'foobox';
					}
				}
			}

			return $value;
		}
	}
}
