<?php

class portfolio_Mygallery {
	
	public $data;
	public $full;
	public $columns;
	public $limit;
	public $show_title;
	public $show_desc;
	
	function __construct(){
		global $data;
		$this->data = $data;
		$this->full = $this->data['myg_full_width']?'-fluid':'';		
		$this->columns = !empty($this->data['myg_columns'])? $this->data['myg_columns']: 4;
		$this->limit = !empty($this->data['myg_image_number'])? $this->data['myg_image_number']: 10;
		$this->text_size = !empty($this->data['myg_text_size'])? $this->data['myg_text_size']: 20;
		$this->show_title = $this->data['myg_show_title'] == 'yes'? 'yes': 'no';
		$this->show_desc = $this->data['myg_show_desc'] == 'yes'? 'yes': 'no';
	}
	
	public function get_mygallery(){

		if($this->columns == 4) {
			$image_size = 'mygallery-four';
		}elseif($this->columns == 5) {
			$image_size = 'mygallery-five';
		}elseif($this->columns == 6) {
			$image_size = 'mygallery-six';
		}elseif($this->columns == 3) {
			$image_size = 'mygallery-three';
		}elseif($this->columns == 2) {
			$image_size = 'mygallery-two';
		}
							
		$html = '';
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		
		$args = array(
			'post_type' => 'mygallery',
			'paged' => $paged,
			'posts_per_page' => $this->limit,
		);
							
		$query = new WP_Query($args);
		
		$html .= '<div class="myg_container">';
		$html .= '<div class="myg_gallery myg_portfolio myg_gallery_thumbnail myg_loadmore_gallery clearfix">';
		while($query->have_posts()): $query->the_post();
			$attachment = MyGalleryAttachment::get_by_id( get_the_ID(), $image_size );
			$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$this->show_title.'" data-show-desc="'.$this->show_desc.'" data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'">';
			$html .= '<div class="myg_image_wrap">';
			$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
			if($this->show_title == 'yes' && $attachment->title || $this->show_desc == 'yes' && $attachment->description){
				$html .= '<div class="myg_gallery_con_wrap">';
				if($this->show_title == 'yes' && $attachment->title){
					$attachment_url = get_permalink($attachment->ID);
					$html .= '<a href="'. $attachment_url . '"><h4>' . $attachment->title . '</h4></a>';
				}
				if($this->show_desc == 'yes' && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description, $this->text_size) . '</p>';
					$html .= myg_content_size($attachment->description, $text_size)?'<p><span class="read-all" data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'">'.esc_html__('Read more','mygallery').'&nbsp;&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i></span></p>':'';
				}
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</div>';
		endwhile;
		$html .= '</div>';
		$html .= '</div>';
		if($query->found_posts >= $this->limit){
			$html .= '<div class="galleryloading mygalleryLoading text-center clearfix"></div>';
			$html .= '<div class="loadmore text-center clearfix"><span class="bdr-btn btn-medium" data-event="load-more-gallery">'.esc_html__('Load More','mygallery').'&nbsp;&nbsp;&nbsp;<i class="fa fa-long-arrow-down"></i></span></div>';
		}
		wp_reset_query();
		return $html;
	}
	
	
	public function get_more_mygallery($last_attachment_id){

		if($this->columns == 4) {
			$image_size = 'mygallery-four';
		}elseif($this->columns == 5) {
			$image_size = 'mygallery-five';
		}elseif($this->columns == 6) {
			$image_size = 'mygallery-six';
		}elseif($this->columns == 3) {
			$image_size = 'mygallery-three';
		}elseif($this->columns == 2) {
			$image_size = 'mygallery-two';
		}
							
		$html = '';
		
		global $wpdb;
				
		$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT %d", $last_attachment_id, $this->limit));

		
		if($this->layout == 'thumbnail') {
			if(!empty($query)){
				foreach($query as $post_id) {
					$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, $image_size );
					$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$this->show_title.'" data-show-desc="'.$this->show_desc.'" data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'">';
					$html .= '<div class="myg_image_wrap">';
					$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
					if($this->show_title == 'yes' && $attachment->title || $this->show_desc == 'yes' && $attachment->description){
						$html .= '<div class="myg_gallery_con_wrap">';
						if($this->show_title == 'yes' && $attachment->title){
							$attachment_url = get_permalink($attachment->ID);
							$html .= '<a href="'. $attachment_url . '"><h4>' . $attachment->title . '</h4></a>';
						}
						if($this->show_desc == 'yes' && $attachment->description){
							$html .= '<p>' . myg_content($attachment->description) . '</p>';
							$html .= myg_content_size($attachment->description, $text_size)?'<p><span class="read-all" data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'">'.esc_html__('Read more','mygallery').'&nbsp;&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i></span></p>':'';
						}
					}
						$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
				}
			}
		}
		if(!empty($query)){
			return $html;
		}else{
			return 0;
		}
		
	}
	
	    /*
     * myg_load_modal_content_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function load_modal_content($attachment_id) {
		$chat = array();
		$a = &$chat;
		$html = '';				
		$attachment = MyGalleryAttachment::get_by_id( $attachment_id, 'large' );
		
		$a["attachment_id"] = $attachment_id;
		$a["image_src"] = $attachment->url;
		$a["title"] = $attachment->title;
		$a["alink"] = get_attachment_link($attachment_id);
		$a["desc"] = $attachment->description;
		$a["social"] = $this->gallery_social_share($attachment_id, $attachment->title);
		$a["meta"] =	$this->portfolio_meta($attachment_id, $attachment->author);
		$a["comments"] = '';
		
		if(comments_open( $attachment_id ) || get_comments_number( $attachment_id )){
			$a["comments"] = '<a class="bdr-btn btn-mini" href="'. get_comments_link( $attachment_id ).'" target="_blank"><i class="fa fa-comments-o"></i>&nbsp;&nbsp;'.get_comments_number( $attachment_id ).' Comments</a>';
		}
		
		return json_encode($chat);
    }

    /*
     * myg_next_modal_content_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function load_next_modal_content($attachment_id, $myg_nav) {
		header("Content-Type: application/json");
		global $wpdb;
		$chat = array();
		$a = &$chat;
		$a["has_next"] = true;						
		$a["myg_nav"] = '';
		$post_type = 'attachment';
		$post_status = 'inherit';
		if($myg_nav == 'next'){				
			$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT 1", $attachment_id));
		}else{			
			$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT 1", $attachment_id));
		}
		if(!empty($query)){
			foreach($query as $post_id) {				
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, 'large' );
				$a["myg_nav"] = $myg_nav;
				$a["attachment_id"] = $post_id->ID;
				$a["image_src"] = $attachment->url;
				$a["title"] = $attachment->title;
				$a["alink"] = get_attachment_link($attachment_id);
				$a["desc"] = $attachment->description;
				$a["comments"] = $attachment->comment_count;
				$a["social"] = $this->gallery_social_share($post_id->ID, $attachment->title);
				$a["meta"] =	$this->portfolio_meta($post_id->ID, $attachment->author);
				$a["comments"] = '';
		
				if(comments_open( $post_id->ID ) || get_comments_number( $post_id->ID )){
					$a["comments"] = '<a class="bdr-btn btn-mini" href="'. get_comments_link( $post_id->ID ).'" target="_blank"><i class="fa fa-comments-o"></i>&nbsp;&nbsp;'.get_comments_number( $post_id->ID ).' Comments</a>';
				}
				
			}
		}else{
			$a["has_next"] = false;
		}
		
		return json_encode($chat);
    }
	
	public function gallery_social_share($post_id, $post_title){
		$html = '';
		
		$html .= '<div class="myg-social-share pt-15 pb-15 myg-bdr-bottom myg-bdr-top">
				'.esc_html__( 'Share: ', 'mygallery' ).'
				<a href="https://www.facebook.com/sharer/sharer.php?u='.get_the_permalink($post_id).'&amp;t='.$post_title.'" class="rds-50" title="facebook" onclick="return myg_sharegallery(this.href)"><i class="fa fa-facebook"></i></a>
				<a href="https://twitter.com/home?status='.$post_title.''.get_the_permalink($post_id).'" class="rds-50" title="twitter" onclick="return myg_sharegallery(this.href)"><i class="fa fa-twitter"></i></a>
				<a href="http://linkedin.com/shareArticle?mini=true&amp;url='.get_the_permalink($post_id).'&amp;title='.$post_title.'" class="rds-50" title="Linked in" onclick="return myg_sharegallery(this.href)"><i class="fa fa-linkedin"></i></a>
				<script>
					 function myg_sharegallery(url) {
						newwindow=window.open(url,"name","height=400,width=500");
						if (window.focus) {newwindow.focus()}
						return false;
					}
				</script>
			</div>';
		return $html;
		
	}
	
	public function portfolio_meta($post_id, $author_id){
		$html = '';
		
		if($this->data['myg_port_meta']){
			$html .= '<div class="myg-blog-meta myg-sub meta-info pt-15 pb-15 myg-bdr-top">';
				if($this->data['myg_port_meta_author']){
					$html .=  get_the_author_meta('display_name',$author_id).'<span class="sep"> . </span>';
				}
				if($this->data['myg_port_meta_date']){
					$html .= get_the_time($this->data['myg_port_date_format'], $post_id);
				}
				if($this->data['myg_port_category']){
					$categories = get_the_category($post_id);
					$separator = ', ';
					$output = '';
					if ( ! empty( $categories ) ) {
						foreach( $categories as $category ) {
							$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( esc_html__( 'View all portfolio in %s', 'mygallery' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
						}						
						$html .= '<span class="sep"> . </span><span>'.trim( $output, $separator ).'</span>';
					}
					
					
				}
			$html .= '</div>';
		}
		return $html;
	}
}