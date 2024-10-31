<?php

if ( ! class_exists( 'mygalleryOptions' ) ) {
	class mygalleryOptions {
			
		function __construct() {
			
	  		add_action('wp_loaded', array($this,'create_mygallery_Options'));
		}
		
		public function create_mygallery_Options() {
			require_once(MYG_PATH . "admin/admin-page-class.php");
			/**
			* configure your admin page
			*/
			$config = array(    
				'menu'           => 'settings',             //sub page to settings page
				'page_title'     => esc_html__('Album options','apc'),       //The name of this page 
				'capability'     => 'edit_posts',         // The capability needed to view the page 
				'option_group'   => 'mygallery_options',       //the name of the option to create in the database
				'id'             => 'algal_admin_page',   // meta box id, unique per page
				'fields'         => array(),    // list of fields (can be added by field arrays)
				'local_images'   => false,   // Use local or hosted images (meta box images for add/remove)
				'use_with_theme' => false //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
			);  
			
			/**
			* instantiate your admin page
			*/
			$options_panel = new myg_Admin_Page_Class($config);
			$options_panel->OpenTabs_container('');
			
			/**
			* define your admin page tabs listing
			*/
			$options_panel->TabsListing(array(
			'links' => array(
			  'options_1' =>  esc_html__('Style Options','apc'),
			  'options_8' =>  esc_html__('Modal Style','apc'),
			  'options_6' =>  esc_html__('Custom Style','apc'),
			  'options_9' => esc_html__('Single page','apc'),
			  'options_10' => esc_html__('Archive page','apc'),
			  'options_2' => esc_html__('Pro Version','apc'),
			  'options_7' =>  esc_html__('Import Export','apc'),
			)
			));
						
			/**
			* Open admin page first tab
			*/
			$options_panel->OpenTab('options_1');
			
			/**
			* Add fields to your admin page first tab
			* 
			* Simple options:
			* input text, checbox, select, radio 
			* textarea
			*/
			//title
			$options_panel->Title(esc_html__("Style Options","apc"));
			//An optionl descrption paragraph
			
			//Color field
			$options_panel->addColor('myg_default_bdr_color',array('name'=> esc_html__('Default border color','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			$options_panel->addText('myg_default_bdr_radious',
			array(
			  'name'     => esc_html__('Default border radius','apc'),
			  'std'      => 0,
			  'desc'     => esc_html__("value can be 2, 3, 4, 5 .... etc Default: 0 i.e no border radius.",'apc'),
			  'validate' => array(
				  'minvalue' => array('param' => 0,'message' => esc_html__("Must be numeric with a min value of 0",'apc'))
			  ),
			  'validate' => array(
				  'maxvalue' => array('param' => 30,'message' => esc_html__("Must be numeric with a Max value of 1",'apc'))
			  )
			)
			);
			$options_panel->addColor('myg_default_bg_color',array('name'=> esc_html__('Default background color ','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			$options_panel->addColor('myg_paginate_color',array('name'=> esc_html__('Pagination background color ','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
						
			/**
			* Close first tab
			*/   
			$options_panel->CloseTab();
									
			$options_panel->OpenTab('options_8');
			
			/**
			* Add fields to your admin page 4th tab
			* 
			* WordPress Options:
			*   Taxonomies dropdown
			*  posts dropdown
			*  Taxonomies checkboxes list
			*  posts checkboxes list
			*  
			*/
			//title
			$options_panel->Title(esc_html__("Modal Style","apc"));
			//taxonomy select field
			$options_panel->addColor('myg_modal_bg_color',array('name'=> esc_html__('Modal background color','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			$options_panel->addColor('myg_modal_close_color',array('name'=> esc_html__('Modal close color','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			$options_panel->addColor('myg_modal_arrow_color',array('name'=> esc_html__('Modal arrow color','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			$options_panel->addColor('myg_modal_arrow_bg_color',array('name'=> esc_html__('Modal arrow background color','apc'), 'std' => '', 'desc' => esc_html__('','apc')));
			
			$options_panel->addText('myg_lg_no_result', array('name'=> esc_html__('No results','apc'), 'std'=> 'No results', 'desc' => esc_html__('','apc')));
									
			/**
			* Close 4th tab
			*/
			
			$options_panel->CloseTab();
			
			/**
			* Open admin page 9th tab
			*/
			
			//sidebar manager
			$sidebar_name = array();
			global $wp_registered_sidebars;
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
				$sidebar_name[$sidebar['id']] = $sidebar['name'];
			}
						
			$options_panel->OpenTab('options_9');
			//title
			$options_panel->Title(esc_html__("Single page","apc"));
			
			$options_panel->addSelect(
				'myg_sp_gallery_type',
				array(
					'thumbnails'=>'Thumbnails',
					'masonry'=>'Masonry',
				),
				array(
					'name'=> esc_html__('Single page gallery type?','apc'),
					'std'=> array('masonry'), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addSelect(
				'myg_sp_show_sidebar',
				array(
					'yes'=>'Yes',
					'no'=>'No',
				),
				array(
					'name'=> esc_html__('Show Sidebar?','apc'),
					'std'=> array('yes'), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addSelect(
				'myg_sp_sidebar_pos',
				array(
					'left'=>'Left',
					'right'=>'Right',
				),
				array(
					'name'=> esc_html__('Sidebar Position?','apc'),
					'std'=> array('left'), 
					'desc' => esc_html__('','apc')
				)
			);
						
			$options_panel->addSelect(
				'myg_sp_sidebar',
				$sidebar_name,
				array(
					'name'=> esc_html__('Select Sidebar','apc'),
					'std'=> array(''), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addRadio(
				'myg_sp_image_size',
				array(
					'mygallery-six'=>'6 Column',
					'mygallery-five'=>'5 Column',
					'mygallery-four'=>'4 Column',
					'mygallery-three'=>'3 Column',
					'mygallery-two'=>'2 Column',
				),
				array(
					'name'=> esc_html__('Gallery column','apc'),
					'std'=> array('mygallery-four'), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addSelect(
				'myg_sp_album_title',
				array(
					'no'=>'No',
					'yes'=>'Yes',
				),
				array(
					'name'=> esc_html__('Show album title?','apc'),
					'std'=> array('no'), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addSelect(
				'myg_sp_album_desc',
				array(
					'no'=>'No',
					'yes'=>'Yes',
				),
				array(
					'name'=> esc_html__('Show album description?','apc'),
					'std'=> array('no'), 
					'desc' => esc_html__('','apc')
				)
			);
									
			/**
			* Close 6th tab
			*/
			$options_panel->CloseTab();
			
			/**
			* Open admin page 10th tab
			*/
			$options_panel->OpenTab('options_10');
			//title
			$options_panel->Title(esc_html__("Archive page","apc"));
			
			$options_panel->addSelect(
				'myg_ap_show_sidebar',
				array(
					'yes'=>'Yes',
					'no'=>'No',
				),
				array(
					'name'=> esc_html__('Show Sidebar?','apc'),
					'std'=> array('yes'), 
					'desc' => esc_html__('','apc')
				)
			);
			
			$options_panel->addSelect(
				'myg_ap_sidebar_pos',
				array(
					'left'=>'Left',
					'right'=>'Right',
				),
				array(
					'name'=> esc_html__('Sidebar Position?','apc'),
					'std'=> array('left'), 
					'desc' => esc_html__('','apc')
				)
			);
						
			$options_panel->addSelect(
				'myg_ap_sidebar',
				$sidebar_name,
				array(
					'name'=> esc_html__('Select Sidebar','apc'),
					'std'=> array(''), 
					'desc' => esc_html__('','apc')
				)
			);
			
			/**
			* Close 6th tab
			*/
			$options_panel->CloseTab();
			
			/**
			* Open admin page 6th tab
			*/
			$options_panel->OpenTab('options_6');
			//title
			$options_panel->Title(esc_html__("Custom Style","apc"));
			
			$options_panel->addCode('myg_custom_style',array('name'=> esc_html__('Custom Style Editor ','apc'),'syntax' => 'css', 'desc' => esc_html__('','apc')));
						
			/**
			* Close 6th tab
			*/
			$options_panel->CloseTab();
			
			/**
			* Open admin page 6th tab
			*/
			$options_panel->OpenTab('options_2');
			//title
			$options_panel->Title(esc_html__("Pro Version","apc"));			
			$options_panel->addParagraph(
			'<h3>Features</h3>
			<p>
			<a href="https://wpapplab.com/plugins/album-gallery-wordpress-photo-gallery-plugin/" target="_blank" class="btn-info btn">Download Pro Version</a>&nbsp;&nbsp;&nbsp;
			<a href="https://album-gallery.wpapplab.com/get-started/" target="_blank" class="btn-info btn">Get Started</a>&nbsp;&nbsp;&nbsp;
			<a href="https://album-gallery.wpapplab.com/documentation/" target="_blank" class="btn-info btn">Documentation</a>&nbsp;&nbsp;&nbsp;
			</p>
<ul>
<li><span>&radic;</span> Unlimited Album, Unlimited Gallery.</li>
<li><span>&radic;</span> Drag and drop multi-image uploader.</li>
<li><span>&radic;</span> Gallery and Album image preview</li>
<li><span>&radic;</span> Masonry, Thumbnail, Portfolio Gallery</li>
<li><span>&radic;</span> Ajax infinite scroll for both gallery and album</li>
<li><span>&radic;</span> Google plug like album view with both pagination and infinite scroll</li>
<li><span>&radic;</span> Album timeline view</li>
<li><span>&radic;</span> Social integration with share button for facebook, google+, twitter, linkedin, tumblr, pinterest</li>
<li><span>&radic;</span> Like, comments, view like facebook both for guest and logged in user.</li>
<li><span>&radic;</span> Pop up ajax image view with navigation control</li>
<li><span>&radic;</span> 4 way gallery preview. (Thumbnail, Masonry, Right Content, Hover Content)</li>
<li><span>&radic;</span> 2 way album decoration. (Google+ like and Timeline)</li>
<li><span>&radic;</span> Built in easy to use shortcode for gallery show up.</li>
<li><span>&radic;</span> Translation Ready to Any Language</li>
<li><span>&radic;</span> Powerfull option panel to change color skin and some settings.</li>
<li><span>&radic;</span> Wordpress multisite ready</li>
</ul>'
			);
						
			/**
			* Close 6th tab
			*/
			$options_panel->CloseTab();
			
						
			/**
			* Open admin page 7th tab
			*/
			$options_panel->OpenTab('options_7');
			
			//title
			$options_panel->Title(esc_html__("Import Export","apc"));
			
			/**
			* add import export functionallty
			*/
			$options_panel->addImportExport();
			
			/**
			* Close 7th tab
			*/
			$options_panel->CloseTab();
			$options_panel->CloseTab();
			
		}
	
	}
	new mygalleryOptions();
}

?>