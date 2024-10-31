<?php
/*
 * MyGallery Admin class
 */

if ( ! class_exists( 'MyGallery_Admin' ) ) {

	/**
	 * Class MyGallery_Admin
	 */
	 
	class MyGallery_Admin {

		/**
		 *
		 */
		public $ajax_actions;
		
		function __construct() {
			$this->configure_ajax_actions();
			add_action( 'init', array( $this, 'init' ) );
			new MyGallery_Activation_Controller();
			new MyGallery_Admin_Gallery_MetaBoxes();
			new MyGallery_Attachment_Fields();
		}

		function init() {
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		}
		
		public function configure_ajax_actions() {

			$this->ajax_actions = array(
				"mygallery_delete_image" => array("action" => "mygallery_delete_image_action", "function" => "mygallery_delete_image_function"),
			);
	
			/*
			 * Add the AJAX actions into WordPress
			 */
			foreach ($this->ajax_actions as $custom_key => $custom_action) {
	
				if (isset($custom_action["logged"]) && $custom_action["logged"]) {
					// Actions for users who are logged in
					add_action("wp_ajax_" . $custom_action['action'], array($this, $custom_action["function"]));
				} else if (isset($custom_action["logged"]) && !$custom_action["logged"]) {
					// Actions for users who are not logged in
					add_action("wp_ajax_nopriv_" . $custom_action['action'], array($this, $custom_action["function"]));
				} else {
					// Actions for users who are logged in and not logged in
					add_action("wp_ajax_nopriv_" . $custom_action['action'], array($this, $custom_action["function"]));
					add_action("wp_ajax_" . $custom_action['action'], array($this, $custom_action["function"]));
				}
			}
		}

		function admin_scripts_styles() {
			global $post_type;		
			wp_register_style( 'admin-mygallery-css', MYG_URL. 'css/admin-mygallery.css');
			wp_enqueue_style( 'admin-mygallery-css' );	
    		if( 'mygallery' == $post_type ){
				wp_register_script('admin-gallery-default-js', MYG_URL. 'js/admin-gallery-default.js', array("jquery"));
				wp_enqueue_script('admin-gallery-default-js');
				wp_register_script('admin-gallery-thumbnail-js', MYG_URL. 'js/admin-gallery-thumbnail.js', array("jquery"));
				wp_enqueue_script('admin-gallery-thumbnail-js');
				wp_register_script('admin-mygallery-js', MYG_URL. 'js/admin-mygallery.js', array("jquery"));
				wp_enqueue_script('admin-mygallery-js');
				
				$nonce = wp_create_nonce("unique_key");
				$config_array = array(
					'ajaxURL' => admin_url('admin-ajax.php'),
					'ajaxActions' => $this->ajax_actions,
					'ajaxNonce' => $nonce,
					'siteURL' => site_url(),
				);
			
				wp_localize_script('admin-mygallery-js', 'mygallery_conf', $config_array);
			
			}
		}

		/**
		 * @param $links
		 *
		 * @return string
		 */
		function plugin_listing_links( $links ) {
			// Add a 'Settings' link to the plugin listing
			$links[] = '<a href="' . esc_url( mygallery_admin_settings_url() ) . '"><b>' . esc_html__( 'Settings', 'mygallery' ) . '</b></a>';

			$links[] = '<a href="' . esc_url( mygallery_admin_help_url() ) . '"><b>' . esc_html__( 'Help', 'mygallery' ) . '</b></a>';

			return $links;
		}

		function output_shortcode_variable() {
			if ( mygallery_gallery_shortcode_tag() != MYG_CPT_GALLERY ) {
				?>
				<script type="text/javascript">
					window.MYG_SHORTCODE = '<?php echo mygallery_gallery_shortcode_tag(); ?>';
				</script>
			<?php
			}
		}
		public function mygallery_delete_image_function(){
			header("Content-Type: application/json");
			
			$mirup = array();
			$a = &$mirup;
			
			global $wpdb;
			
			$db_error = 'Database Error ';
			$a['mygallery_error'] = 0;
			$a['mygallery_error_data'] = '';
			
			$attach_id = sanitize_text_field($_POST["attach_id"]);
			
			$a['attachment_id'] = $attach_id;
			if(is_user_logged_in()){
				$delete = wp_delete_attachment( $attach_id, true );
				if($delete){
					$a['mygallery_error'] = 0;
					$a['mygallery_error_data'] = '<span style="color:#2b542c">' . esc_html__( 'Image Deleted successfully.', 'mygallery' ) . '</span>';
				}else{
					$a['mygallery_error'] = 1;
					$a['mygallery_error_data'] = '<span style="color:#8a6d3b">' . esc_html__( 'There is a problem while deleting image. Please try again.', 'mygallery' ) . '</span>';
				}
			}else{
				$a['mygallery_error'] = 1;
				$a['mygallery_error_data'] = '<span style="color:#8a6d3b">' . esc_html__( 'Please login to delete image.', 'mygallery' ) . '</span>';
			}
						
			echo json_encode($mirup);
			exit;
		}
	}
}
