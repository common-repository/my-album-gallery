<?php

if ( ! class_exists( 'MyGallery_Activation_Controller' ) ) {
	class MyGallery_Activation_Controller {
	
		public function __construct() {
			register_activation_hook("my-album-gallery/my-album-gallery.php", array($this, 'execute_activation_hooks'));
		}
	
		public function execute_activation_hooks() {
			$database_manager = new mygallery_Database_Manager();
			$database_manager->create_custom_tables();
			
			if ( is_plugin_active( 'album-gallery-pro/album-gallery-pro.php' ) ) {
				//plugin is activated
				deactivate_plugins( 'album-gallery-pro/album-gallery-pro.php' );
			} 
			// Do activate Stuff now.
		}
	}
}

?>