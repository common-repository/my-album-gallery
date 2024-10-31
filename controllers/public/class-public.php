<?php
/*
 * MyGallery Public class
 */

if ( ! class_exists( 'MyGallery_Public' ) ) {

	class MyGallery_Public {

		function __construct() {
			new MyGallery_Shortcode();
			add_action('init', array($this, 'font_controller'));
			
		}
		
		public function font_controller(){
			$script_controller = new mygallery_Script_Controller();
			$script_controller->enque_scripts();
		}

	}

}
