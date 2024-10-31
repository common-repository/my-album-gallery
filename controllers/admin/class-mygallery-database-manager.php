<?php

if ( ! class_exists( 'MyGallery_Database_Manager' ) ) {
	class MyGallery_Database_Manager {
	
		public function __construct() {
			
		}
	
		public function create_custom_tables() {
			global $wpdb;
			$table_name1 = $wpdb->prefix . "myg_comments";
			$table_name2 = $wpdb->prefix . "myg_likes";
	
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
			$sql1 ="CREATE TABLE IF NOT EXISTS $table_name1 (
				id bigint(20) NOT NULL AUTO_INCREMENT, 
				image_id bigint(20) NOT NULL, 
				user_id bigint(20) NOT NULL,
				user_ip varchar(64) NOT NULL,
				comment mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, 
				comment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				is_guest tinyint(1) NOT NULL, 
				PRIMARY KEY (id)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
				
			$sql2 ="CREATE TABLE IF NOT EXISTS $table_name2 (
				id bigint(20) NOT NULL AUTO_INCREMENT, 
				image_id bigint(20) NOT NULL, 
				user_id bigint(20) NOT NULL,
				user_ip varchar(64) NOT NULL,
				is_liked tinyint(1) NOT NULL, 
				is_viewed tinyint(1) NOT NULL,
				is_guest tinyint(1) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
	
			
			dbDelta($sql1);
			dbDelta($sql2);
	
		}
	
	}
}

?>