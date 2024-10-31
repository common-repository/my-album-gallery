<?php

if ( ! class_exists( 'MyGallery_Shortcode' ) ) {
	
	class MyGallery_Shortcode {
		public $cleanup;
		public $cleanupTime;
		protected $log_file;
		
		function __construct(){
			add_filter('the_content', array( $this, 'shortcodes_formatter' ));
			add_filter('widget_text', array( $this, 'shortcodes_formatter' ));
			add_shortcode('myg_album_gallery', array( $this, 'shortcode_myg_album_gallery' ));
		}
	
		public function shortcodes_formatter($content) {
			$block = join("|",array("myg_album_gallery"));
		
			// opening tag
			$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
		
			// closing tag
			$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)/","[/$2]",$rep);
		
			return $rep;
		}
		
		public function social_content($likes, $views, $comments, $myg_like, $myg_view, $like_event, $attach_id, $title, $link, $desc){
			
			$html = '';
			
			$html .= '<div class="myg_social myg_clearfix">';
				$html .= '<div class="myg_social_left">';
					$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
					$html .= myg_social_share($title, $link, myg_content($desc));
				$html .= '</div>';
				$html .= '<div class="myg_social_right">';
					$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attach_id.'" data-event="myg_image_open">&nbsp;</span><span>'.$views.'</span>';
					$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attach_id.'" data-myg-attachment-id="'.$attach_id.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attach_id.'">'.$likes.'</span>';
					$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attach_id.'" data-event="myg_image_open">&nbsp;</span><span>'.$comments.'</span>';
				$html .= '</div>';
			$html .= '</div>';
			
			return $html;

		}
		public function social_content_mini($likes, $views, $myg_like, $myg_view, $like_event, $attach_id, $parent_id, $link){
			
			$html = '';
			
			$html .= '<div class="myg_social_mini">';
				$html .= '<div class="myg_social_mini_left">';
					$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attach_id.'" data-myg-parent-id="'.$parent_id.'" data-event="myg_image_open">&nbsp;</span><span>'.$views.'</span>';
				$html .= '</div>';
				$html .= '<div class="myg_social_mini_right">';
					$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attach_id.'" data-myg-attachment-id="'.$attach_id.'" data-myg-parent-id="'.$parent_id.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attach_id.'">'.$likes.'</span>';
				$html .= '</div>';
			$html .= '</div>';
			
			return $html;

		}
				
		//////////////////////////////////////////////////////////////////
		// MyGallery Shortcode
		//////////////////////////////////////////////////////////////////
		function shortcode_myg_album_gallery($atts, $content = null) {
			global $data;
			extract(shortcode_atts(array(
				'id' => 0,
				'gallery' => '',
				'layout' => 'gallery',
				'full_width' => 'no',
				'columns' => 4,
				'album_type' => '',
				'image_number' => 10,
				'border' => 'no',
				'border_color' => '',
				'margin' => '',
				'padding' => '',
				'style_class' => '',
				'style_css' => '',
				'show_title' => 'no',
				'show_desc' => 'no',
				'shadow' => '',
			), $atts));
			
			$bdr = ''; $mar = ''; $pad = ''; $style = ''; $color = '';
					
			if($columns == 4) {
				$image_size = 'mygallery-four';
			}elseif($columns == 5) {
				$image_size = 'mygallery-five';
			}elseif($columns == 6) {
				$image_size = 'mygallery-six';
			}elseif($columns == 3) {
				$image_size = 'mygallery-three';
			}elseif($columns == 2) {
				$image_size = 'mygallery-two';
			}
			
			$data = get_option('mygallery_options');
			$bdr_color = !empty($data['myg_default_bdr_color']) && $data['myg_default_bdr_color'] != '#'?$data['myg_default_bdr_color']:'';
			
			if($border == 'yes'){
				if(!empty($bdr_color)){
					$color = !empty($bdr_color)?$bdr_color.';':'#eaeaea;';
				}else{
					$color = $border_color?$border_color.';':'#eaeaea;';
				}
				
				$bdr = 'border:1px solid '.$color;
			}
			if($margin){
				$mar = 'margin:'.$margin.';';
			}
			if($padding){
				$pad = 'padding:'.$padding.';';
			}
			if($border == 'yes' || !empty($margin) || !empty($padding)|| !empty($style_css)){
				$style = ' style="'.$bdr.$mar.$pad.$style_css.'"';
			}
			$width = ($full_width == 'yes')?' myg_full_width':'';
			
			$html = '';
			if(is_front_page()) {
				$paged = (get_query_var('page')) ? get_query_var('page') : 1;
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			$html .= '<div class="myg_container'.$width.'">';
			$html .= '<div class="myg_row">';
			if($layout == 'gallery'){
				if ( $id != 0 ) {
					$ids = explode(',', $id);
					$html .= '<div class="myg_gallery myg_clearfix">';
					foreach($ids as $post_id) {
						$gallery = MyGallery::get_by_id( trim($post_id) );
						foreach ( $gallery->attachments($image_size) as $attach ) {
							$attachment = MyGalleryAttachment::get_by_id( $attach->ID, $image_size );
							$html .= '<div class="myg_image '.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
							$html .= '<div class="myg_image_wrap"'.$style.'>';
							$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
							$html .= '</div>';
							$html .= '</div>';
						}
					}
					$html .= '</div>';
				}else{
					$query_post = new WP_Query( 
					  array( 
						'post_type' => 'mygallery', 
						'posts_per_page' => -1,
						'fields' => 'ids'
					  ) 
					);
					
					$query = new WP_Query( 
					  array( 
						'post_type' => 'attachment', 
						'post_status' => 'inherit', 
						'post_mime_type' => 'image',
						'posts_per_page' => $image_number,
						'paged' => $paged, 
						'post_parent__in' => $query_post->posts, 
						'orderby' => 'ID',
						'order' => 'DESC'
					  ) 
					);
					$html .= '<div class="myg_gallery myg_clearfix">';
					
					if(!empty($query->posts)){
						foreach ( $query->posts as $attach ) {
							$attachment = MyGalleryAttachment::get_by_id( $attach->ID, $image_size );
							$html .= '<div class="myg_image '.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
							$html .= '<div class="myg_image_wrap"'.$style.'>';
							$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
							$html .= '</div>';
							$html .= '</div>';
						}
					}
					$html .= '</div>';
					$html .= mygallery_pagination($query->max_num_pages, $range = 6);
				}
				
				wp_reset_query();
			}elseif($layout == 'masonry'){
				$query_post = new WP_Query( 
				  array( 
					'post_type' => 'mygallery', 
					'posts_per_page' => -1,
					'fields' => 'ids'
				  ) 
				);
				$query = new WP_Query( 
				  array( 
					'post_type' => 'attachment', 
					'post_status' => 'inherit', 
					'post_mime_type' => 'image',
					'posts_per_page' => $image_number,
					'paged' => $paged, 
					'post_parent__in' => $query_post->posts, 
				  ) 
				);
				$html .= '<ul class="myg_gallery myg-masonry myg_clearfix">';
				while($query->have_posts()): $query->the_post();
					$attachment = MyGalleryAttachment::get_by_id( get_the_ID(), 'mygallery-masonry' );
					$html .= '<li class="myg_image myg-item '.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
					$html .= '<div class="myg_image_wrap"'.$style.'>';
					$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" src="'.$attachment->url.'" />';
					$html .= '</div>';
					$html .= '</li>';
				endwhile;
				$html .= '</ul>';
				$html .= mygallery_pagination($query->max_num_pages, $range = 4);
				wp_reset_query();
			}elseif($layout == 'portfolio'){				
				$args = array(
					'post_type' => 'mygallery',
					'posts_per_page' => $image_number,
					'paged' => $paged
				);
				if(isset($atts['album_slug']) && !empty($atts['album_slug'])){
					$cat_id = explode(',', $atts['album_slug']);
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'mygallery_category',
							'field' => 'slug',
							'terms' => $cat_id
						)
					);
				}

				$query = new WP_Query($args);
				$html .= '<div class="myg_gallery myg_portfolio myg_clearfix">';
				while($query->have_posts()): $query->the_post();
					$permalink = get_permalink(get_the_ID());
					$html .= '<div class="myg_image '.$image_size.'" data-myg-attachment-id="'.get_the_ID().'">';
					$html .= '<div class="myg_image_wrap"'.$style.'>';
					if(has_post_thumbnail( get_the_ID() )){
					$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
					$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.get_the_ID().'" data-myg-parent-id="'.get_the_ID().'" title="'.$attachment->title.'" src="'.$src[0].'" />';
					}
					$attachment = MyGalleryAttachment::get_by_id( get_the_ID() );
					if($show_title == 'yes' && $attachment->title){
						$html .= '<h4><a href="'.$permalink.'">' . $attachment->title . '</a></h4>';
					}
					if($show_desc == 'yes' && $attachment->description){
						$html .= '<p>' . myg_content($attachment->description) . '</p>';
					}
					$html .= '</div>';
					$html .= '</div>';
				endwhile;
				$html .= '</div>';
				$html .= mygallery_pagination($query->max_num_pages, $range = 6);
				wp_reset_query();
			}elseif($layout == 'album') {
				$args = array(
					'post_type' => 'mygallery',
					'posts_per_page' => $image_number,
					'paged' => $paged
				);

				$query = new WP_Query($args);
				$html .= '<div class="myg_album_container myg_clearfix">';
				while($query->have_posts()): $query->the_post();
					
					$gallery = MyGallery::get_by_id( get_the_ID() );
					$image_ids = $gallery->attachment_ids;
					$title = esc_html( get_the_title() );
					$permalink = get_permalink(get_the_ID());
					$count = sizeof( $image_ids );
					
					if($count >= 1){
						$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'thumbnail' );
						$html .= '<div class="myg_album_image_wrap">';
						$html .= '<a class="myg_album_image" href="'.$permalink.'" data-myg-album-id="'.get_the_ID().'">';
						$html .= '<img class="myg_album_single" src="'.$attachment1->url.'" />';
						$html .= '</a>';
						$html .= '<div class="myg_album_image_count">'.$title.' ('.$count.')</div>';
						$html .= '</div>';
					}
					
				endwhile;
				$html .= '</div>';
				$html .= mygallery_pagination($query->max_num_pages, $range = 2);				
				wp_reset_query();
			}
			$html .= '</div>';
			$html .= '</div>';
			return $html;
		}
	}
}

?>