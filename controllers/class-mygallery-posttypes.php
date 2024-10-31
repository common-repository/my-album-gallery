<?php

/*
 * Manage developer books throughout the portfolio
 * application.
 *
 */
if ( ! class_exists( 'MyGallery_PostTypes' ) ) {
	class MyGallery_PostTypes {
	
		private $post_type;
		private $post_category;
		private $error_message;
	
	
		/*
		 * Execute initiamizations for the books
		 *
		 * @param  object  Twig Template
		 * @return -
		*/
		public function __construct( ) {
			$this->post_type = "mygallery";
			$this->post_category = "mygallery_category";
	
			$this->error_message = "";
	
			add_action( 'init', array( $this, 'create_mygallery_post_type' ) );
	
		}
	
		/*
		 * Register custom post type for books
		 *Warning: call_user_func_array() expects parameter 1 to be a valid callback, array must have exactly two members in C:\wamp\www\marry\wp-includes\plugin.php on line 496
		 * @param  -
		 * @return -
		*/
		public function create_mygallery_post_type() {
	
			$labels = array(
				'name'                  => esc_html__( 'Album Gallery', 'mygallery' ),
				'singular_name'         => esc_html__( 'Album Gallery', 'mygallery' ),
				'add_new'               => esc_html__( 'Add New Album', 'mygallery' ),
				'add_new_item'          => esc_html__( 'Add New Album', 'mygallery' ),
				'edit_item'             => esc_html__( 'Edit Album', 'mygallery' ),
				'new_item'              => esc_html__( 'New Album', 'mygallery' ),
				'all_items'             => esc_html__( 'All Album', 'mygallery' ),
				'view_item'             => esc_html__( 'View Album', 'mygallery' ),
				'search_items'          => esc_html__( 'Search Album', 'mygallery' ),
				'not_found'             => esc_html__( 'No Album found', 'mygallery' ),
				'not_found_in_trash'    => esc_html__( 'No Album found in the Trash', 'mygallery' ),
				'parent_item_colon'     => '',
				'menu_name'             => esc_html__( 'Album Gallery', 'mygallery' ),
			);
	
			$args = array(
				'labels'                => $labels,
				'hierarchical'          => false,
				'description'           => 'Album Gallery',
				'supports'              => array('title', 'editor', 'author', 'thumbnail', 'comments'),
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'show_in_nav_menus'     => true,
				'publicly_queryable'    => true,
				'exclude_from_search'   => false,
				'has_archive'           => true,
				'query_var'             => true,
				'can_export'            => true,
				'rewrite'               => true,
				'capability_type'       => 'post',
				'map_meta_cap' 			=> true,
				'menu_icon' 			=> '',
				'menu_position' 		=> null,
	
			);
			
			register_post_type( $this->post_type, $args );
			
			$category_labels = array(
				'name' => esc_html__( 'Album Category' ),
				'singular_name' => esc_html__( 'Album Category' ),
				'search_items' =>  esc_html__( 'Search Category' ),
				'all_items' => esc_html__( 'All Categories' ),
				'parent_item' => esc_html__( 'Parent Category' ),
				'parent_item_colon' => esc_html__( 'Parent Category:' ),
				'edit_item' => esc_html__( 'Edit Category' ), 
				'update_item' => esc_html__( 'Update Category' ),
				'add_new_item' => esc_html__( 'Add New Category' ),
				'new_item_name' => esc_html__( 'New Category Name' ),
				'menu_name' => esc_html__( 'Album Category' ),
			  );    
			 
			$category_args = array(
				'hierarchical' => true,
				'labels' => $category_labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'mygallery_category' ),
			  );
			   
			  register_taxonomy($this->post_category, $this->post_type, $category_args);
	  
		}		
	}
}

?>