<?php
/*
 * MyGallery Admin Columns class
 */

if ( ! class_exists( 'MyGallery_Admin_Columns' ) ) {

	class MyGallery_Admin_Columns {

		private $include_clipboard_script = false;

		public function __construct() {
			add_filter( 'manage_edit-' . MYG_CPT_GALLERY . '_columns', array( $this, 'gallery_custom_columns' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'gallery_custom_column_content' ) );
			add_action( 'admin_footer', array( $this, 'include_clipboard_script' ) );
		}

		public function gallery_custom_columns( $columns ) {
			return array_slice( $columns, 0, 1, true ) +
					array( 'icon' => '' ) +
					array_slice( $columns, 1, null, true ) +
					array(
						MYG_CPT_GALLERY . '_count' => esc_html__( 'Media', 'mygallery' ),
						MYG_CPT_GALLERY . '_shortcode' => esc_html__( 'Shortcode', 'mygallery' ),
					);
		}

		public function gallery_custom_column_content( $column ) {
			global $post;

			switch ( $column ) {
				case MYG_CPT_GALLERY . '_count':
					$gallery = MyGallery::get( $post );
					echo $gallery->image_count();
					break;
				case MYG_CPT_GALLERY . '_shortcode':
					$gallery = MyGallery::get( $post );
					$shortcode = $gallery->shortcode();

					echo '<input type="text" readonly="readonly" size="' . strlen( $shortcode )  . '" value="' . esc_attr( $shortcode ) . '" class="mygallery-shortcode" />';

					$this->include_clipboard_script = true;

					break;
				case 'icon':
					$gallery = MyGallery::get( $post );
					$img = $gallery->featured_image_html( array(80, 60), true );
					if ( $img ) {
						echo $img;
					}
					break;
			}
		}

		public function include_clipboard_script() {
			if ( $this->include_clipboard_script ) { ?>
				<script>
					jQuery(function($) {
						$('.mygallery-shortcode').click( function () {
							try {
								//select the contents
								this.select();
								//copy the selection
								document.execCommand('copy');
								//show the copied message
								$('.mygallery-shortcode-message').remove();
								$(this).after('<p class="mygallery-shortcode-message"><?php esc_html_e( 'Shortcode copied to clipboard :)','mygallery' ); ?></p>');
							} catch(err) {
								console.log('Oops, unable to copy!');
							}
						});
					});
				</script>
				<?php
			}
		}
	}
}
