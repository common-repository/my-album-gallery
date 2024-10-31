<?php
/**
 * Class MyGalleryAttachment
 *
 * An easy to use wrapper class for a MyGallery Attachment
 */
if ( ! class_exists( 'MyGalleryAttachment' ) ) {

	class MyGalleryAttachment {
		/**
		 * public constructor
		 *
		 * @param null $post
		 */
		private $liked = array();
		private $viewed = array();
		
		public function __construct( $post = null, $img_size ) {
			$this->set_defaults();

			if ( $post !== null ) {
				//view, like, comment,
				$this->load( $post, $img_size );
			}
		}

		/**
		 *  Sets the default when a new gallery is instantiated
		 */
		private function set_defaults() {
			$this->_post = null;
			$this->ID = 0;
			$this->title = '';
			$this->caption = '';
			$this->description = '';
			$this->post_parent = '';
			$this->link = '';
			$this->url = '';
			$this->width = 0;
			$this->height = 0;
			$this->likes = '';
			$this->views = '';
			$this->comments = '';
			$this->is_liked = false;
			$this->is_viewed = false;
		}

		/**
		 * private attachment load function
		 * @param $post
		 */
		private function load( $post, $img_size = 'thumbnail' ) {
			global $wpdb;
			
			$this->_post = $post;
			$this->ID = $post->ID;
			$this->title = trim( $post->post_title );
			$this->caption = trim( $post->post_excerpt );
			$this->description = trim( $post->post_content );
			$this->post_parent = $post->post_parent;
			$this->link = get_permalink($this->ID);
			$image_attributes = wp_get_attachment_image_src( $this->ID, $img_size );
			$post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $this->ID ), $img_size );
			if ( !empty($image_attributes) ) {
				$this->url = $image_attributes[0];
				$this->width = $image_attributes[1];
				$this->height = $image_attributes[2];
			}elseif(!empty($post_thumb)){
				$this->url = $post_thumb[0];
				$this->width = $post_thumb[1];
				$this->height = $post_thumb[2];
			}
			
			$rows1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}myg_likes WHERE image_id = %d AND is_liked = 1", $this->ID));
			$rows2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}myg_likes WHERE image_id = %d AND is_viewed = 1", $this->ID));
			$rows3 = $wpdb->get_results($wpdb->prepare("SELECT id FROM {$wpdb->base_prefix}myg_comments WHERE image_id = %d", $this->ID));
			$this->likes = !empty($rows1)?count($rows1):'';
			$this->views = !empty($rows2)?count($rows2):'';
			$this->comments = !empty($rows3)?count($rows3):'';
			
			if(is_user_logged_in()){
				$user_id = 	get_current_user_id();
				foreach($rows1 as $row1){
					$this->liked[] = $row1->user_id;
				}
				foreach($rows2 as $row2){
					$this->viewed[] = $row2->user_id;
				}
				
				$this->is_liked = in_array($user_id, $this->liked)?true:false;
				$this->is_viewed = in_array($user_id, $this->viewed)?true:false;
				
			}else{
				$user_ip = myg_get_client_ip();
				foreach($rows1 as $row1){
					$this->liked[] = $row1->user_ip;
				}
				foreach($rows2 as $row2){
					$this->viewed[] = $row2->user_ip;
				}
				
				$this->is_liked = in_array($user_id, $this->liked)?true:false;
				$this->is_viewed = in_array($user_id, $this->viewed)?true:false;
			}
			
		}
		

		/**
		 * Static function to load a MyGalleryAttachment instance by passing in a post object
		 * @static
		 *
		 * @param $post
		 *
		 * @return MyGalleryAttachment
		 */
		public static function get( $post, $img_size = 'thumbnail' ) {
			return new self( $post, $img_size );
		}

		/**
		 * Static function to load a MyGalleryAttachment instance by passing in an attachment_id
		 * @static
		 *
		 * @param $attachment_id
		 *
		 * @return MyGalleryAttachment
		 */
		public static function get_by_id( $attachment_id, $img_size = 'thumbnail' ) {
			$post = get_post( $attachment_id );
			return new self( $post, $img_size );
		}
		
		public static function get_by_post_parent( $post_parent_id, $img_size = 'thumbnail', $post_number) {
			
			$attachments = array();
			$query = new WP_Query( 
			  array( 
				'post_type' => 'attachment', 
				'post_status' => 'inherit', 
				'posts_per_page' => $post_number,
				'post_parent' => $post_parent_id,
			  ) 
			);
			
			while($query->have_posts()): $query->the_post();
			
				$image_attributes = wp_get_attachment_image_src( get_the_ID(), $img_size );
				$attachments[] = $image_attributes[0];
			endwhile;
			return $attachments;
		}
		
		public static function get_by_post( $post_parent_id, $img_size = 'thumbnail', $post_number) {
			
			$attachments = array();
			$query = new WP_Query( 
			  array( 
				'post_type' => 'attachment', 
				'post_status' => 'inherit', 
				'posts_per_page' => $post_number,
				'post_parent' => $post_parent_id,
			  ) 
			);
			
			while($query->have_posts()): $query->the_post();
			
				$post = get_post(get_the_ID());
				
				$attachments[get_the_ID()] = new stdClass();
				$attachments[get_the_ID()]->attachment_id = get_the_ID();
				$attachments[get_the_ID()]->post_title = $post->post_title;
				$attachments[get_the_ID()]->post_content = $post->post_content;
				$attachments[get_the_ID()]->post_parent = $post->post_parent;
				$attachments[get_the_ID()]->post_link = get_attachment_link(get_the_ID());
				$attachments[get_the_ID()]->post_date = $post->post_date;
				$attachments[get_the_ID()]->post_modified_date = $post->post_modified;
				$attachments[get_the_ID()]->comment_count = $post->comment_count;
				
				$image_attributes = wp_get_attachment_image_src( get_the_ID(), $img_size );
				
				$attachments[get_the_ID()]->image_src = $image_attributes[0];
				
			endwhile;
			
			return $attachments;
		}	
		
		
	}
}
