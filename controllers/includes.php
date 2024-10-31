<?php

//common controllers
require_once( MYG_PATH . 'controllers/constants.php' );
require_once( MYG_PATH . 'controllers/functions.php' );
require_once( MYG_PATH . 'admin/class-mygallery-options.php');
require_once( MYG_PATH . 'ajax/class-mygallery-ajax.php');
require_once( MYG_PATH . 'controllers/class-mygallery-posttypes.php' );
require_once( MYG_PATH . 'controllers/class-mygallery.php' );
require_once( MYG_PATH . 'controllers/class-mygallery-attachment.php' );

if ( is_admin() ) {

	//only admin
	require_once( MYG_PATH . 'controllers/admin/class-mygallery-database-manager.php');
	require_once( MYG_PATH . 'controllers/admin/class-mygallery-activation-controller.php');
	require_once( MYG_PATH . 'controllers/admin/class-gallery-metaboxes.php' );
	require_once( MYG_PATH . 'controllers/admin/class-attachment-fields.php' );
	require_once( MYG_PATH . 'controllers/admin/class-admin.php' );

} else {

	//only front-end
	require_once ( MYG_PATH . 'controllers/class-mygallery-script-controller.php');
	require_once( MYG_PATH . 'controllers/public/class-mygallery-shortcode.php' );
	require_once( MYG_PATH . 'controllers/public/class-public.php' );
}
