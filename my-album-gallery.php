<?php
	/*		
		Plugin Name: My Album Gallery
		Plugin URI: https://album-gallery.wpapplab.com/
		Description: My Album Gallery is a wordpress photo gallery, photo album plugin. You can decorate your website gallery by album and album category. Then access the photo gallery via shortcode. It is based on gallery shortcode so compatible to all WordPress theme. There is a Pro Version of this plugin with Ultimate photo gallery features.
		Version: 1.0.4
		Author: Mircode
		Author URI: https://mircode.com
		License: GPLv2
	*/
		
define( 'MYG_SLUG', 'mygallery' );
define( 'MYG_PATH', plugin_dir_path( __FILE__ ) );
define( 'MYG_URL', plugin_dir_url( __FILE__ ) );
define( 'MYG_FILE', __FILE__ );
define( 'MYG_VERSION', '1.0.4' );

//with trailing slash
//include everything we need!
require_once( MYG_PATH . 'controllers/includes.php' );

if ( ! class_exists( 'MyGallery_Plugin' ) ) {
	class MyGallery_Plugin {
		
		private static $instance;
	
		public static function get_instance() {
			if ( ! isset(self::$instance) && ! (self::$instance instanceof MyGallery_Plugin) ) {
				self::$instance = new MyGallery_Plugin();
			}
	
			return self::$instance;
		}
	
		
		 public function __construct() {
				
		}
		
		public function initialize_app_controllers() {
						
			//setup gallery post type
			new MyGallery_PostTypes();
			
			if ( is_admin() ) {
				new MyGallery_Admin();
			} else {
				new MyGallery_Public();
			}
			
		}
	
	}
}

$mygallery_init = new MyGallery_Plugin();
$mygallery_init->initialize_app_controllers();

function mygallery_load_ajax(){
	
	$ajax = new mygallery_Ajax();
	$ajax->initialize();
}

add_action('init', 'mygallery_load_ajax');

add_filter( 'single_template', 'mygallery_single_template' );
function mygallery_single_template( $single_template ){
    global $post;
	$file = '';
	if (is_single() && get_post_type($post) == 'mygallery') {
    	$file = dirname(__FILE__) .'/mygallery-single.php';
	}
    if( file_exists( $file ) ) $single_template = $file;

    return $single_template;
}

add_filter( 'archive_template', 'mygallery_archive_template' ) ;
function mygallery_archive_template( $archive_template ) {
     global $post;	 
	 $file = '';
	 if (is_archive() && get_post_type($post) == 'mygallery') {
		  $file = dirname( __FILE__ ) . '/mygallery-archive.php';
	 }
	 if( file_exists( $file ) ) $archive_template = $file;
	 return $archive_template;
 
}

add_filter( 'plugin_row_meta', 'mygallery_plugin_row_meta', 10, 2 );
 
function mygallery_plugin_row_meta( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
		  'mygallery_pro'    => '<a href="' . esc_url( 'https://wpapplab.com/plugins/album-gallery-wordpress-photo-gallery-plugin/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Album Gallery Pro', 'mygallery' ) . '" style="color:red;"><b>' . esc_html__( 'Get Pro Version', 'mygallery' ) . '</b></a>'
        );
 
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}

?>