<?php

class mygallery_Ajax {

    public $ajax_actions;
    /*
     * Configuring and intializing ajax files and actions
     *
     * @param  -
     * @return -
     */

    public function initialize() {
        $this->configure_actions();
    }

    /*
     * Confire the application specific AJAX actions array and
     * load the AJAX actions bases on supplied parameters
     *
     * @param  -
     * @return -
     */

    public function configure_actions() {

        $this->ajax_actions = array(
			"myg_infinite_gallery" => array("action" => "myg_infinite_gallery_action", "function" => "myg_infinite_gallery_function"),
			"myg_infinite_portfolio" => array("action" => "myg_infinite_portfolio_action", "function" => "myg_infinite_portfolio_function"),
			"myg_infinite_masonry" => array("action" => "myg_infinite_masonry_action", "function" => "myg_infinite_masonry_function"),
			"myg_infinite_right_content" => array("action" => "myg_infinite_right_content_action", "function" => "myg_infinite_right_content_function"),
			"myg_infinite_hover_gallery" => array("action" => "myg_infinite_hover_gallery_action", "function" => "myg_infinite_hover_gallery_function"),
			"myg_infinite_slider" => array("action" => "myg_infinite_slider_action", "function" => "myg_infinite_slider_function"),
            "myg_infinite_album" => array("action" => "myg_infinite_album_action", "function" => "myg_infinite_album_function"),
			"myg_timeline_album" => array("action" => "myg_timeline_album_action", "function" => "myg_timeline_album_function"),
			"myg_like" => array("action" => "myg_like_action", "function" => "myg_like_function"),
			"myg_post_comment" => array("action" => "myg_post_comment_action", "function" => "myg_post_comment_function"),
			"myg_load_modal_content" => array("action" => "myg_load_modal_content_action", "function" => "myg_load_modal_content_function"),
			"myg_load_thumb_modal" => array("action" => "myg_load_thumb_modal_action", "function" => "myg_load_thumb_modal_function"),
			"myg_next_modal_content" => array("action" => "myg_next_modal_content_action", "function" => "myg_next_modal_content_function"),
			"myg_load_album_gallery" => array("action" => "myg_load_album_gallery_action", "function" => "myg_load_album_gallery_function"),
			"myg_reload_album_gallery" => array("action" => "myg_reload_album_gallery_action", "function" => "myg_reload_album_gallery_function")
        );

        /*
         * Add the AJAX actions into WordPress
         */
        foreach ($this->ajax_actions as $custom_key => $custom_action) {

            if (isset($custom_action["logged"]) && $custom_action["logged"]) {
                // Actions for users who are logged in
                add_action("wp_ajax_" . $custom_action['action'], array($this, $custom_action["function"]));
            } else if (isset($custom_action["logged"]) && !$custom_action["logged"]) {
                // Actions for users who are not logged in
                add_action("wp_ajax_nopriv_" . $custom_action['action'], array($this, $custom_action["function"]));
            } else {
                // Actions for users who are logged in and not logged in
                add_action("wp_ajax_nopriv_" . $custom_action['action'], array($this, $custom_action["function"]));
                add_action("wp_ajax_" . $custom_action['action'], array($this, $custom_action["function"]));
            }
        }
    }
		
    /*
     * myg_infinite_gallery_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_gallery_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$style_css = sanitize_text_field($_POST["style_css"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$post_type = 'attachment';
		$post_status = 'inherit';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		
		$limit = !empty($data['myg_gallery_img_per_page'])?$data['myg_gallery_img_per_page']:10;
				
		global $wpdb;
						
		$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT %d", $last_attachment_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, $image_size );
				$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'"  data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if($show_title == 'yes' && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc == 'yes' && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social == 'yes'){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}
			$a["infinite_gallery"] = $html;
		}else{
			$a["infinite_gallery"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }
	
	/*
     * myg_infinite_portfolio_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_portfolio_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$style_css = sanitize_text_field($_POST["style_css"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$post_type = 'attachment';
		$post_status = 'inherit';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		
		$limit = !empty($data['myg_gallery_img_per_page'])?$data['myg_gallery_img_per_page']:10;
				
		global $wpdb;
		$query = $wpdb->get_results($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='mygallery' AND post_status='publish' AND ID < %d ORDER BY ID DESC LIMIT %d", $last_attachment_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'" data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$post_id->ID.'">';
				$html .= '<div class="myg_image_wrap"'.$style.'>';
				if(has_post_thumbnail( $post_id->ID )){
				$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id->ID ), $image_size );
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$post_id->ID.'" data-myg-parent-id="'.$post_id->ID.'" title="'.$attachment->title.'" src="'.$src[0].'" />';
				}
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID );
				if($show_social == 'yes'){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= $this->social_content($attachment->likes, $attachment->views, $attachment->comments, $myg_like, $myg_view, $like_event, $attachment->ID, $attachment->title, $attachment->link, $attachment->description);
				}
				if($show_title == 'yes' && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc == 'yes' && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}
			$a["infinite_gallery"] = $html;
		}else{
			$a["infinite_gallery"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }
	
	 /*
     * myg_infinite_masonry_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_masonry_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$post_type = 'attachment';
		$post_status = 'inherit';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		
		$limit = !empty($data['myg_gallery_img_per_page'])?$data['myg_gallery_img_per_page']:10;
				
		global $wpdb;
						
		$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT %d", $last_attachment_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, 'mygallery-masonry' );
				$html .= '<li class="myg_image myg-item myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'"  data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				$html .= '<div class="myg_image_wrap '.$style_class.'">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" src="'.$attachment->url.'" />';
				if($show_social == 'yes'){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= $this->social_content_mini($attachment->likes, $attachment->views, $myg_like, $myg_view, $like_event, $attachment->ID, $attachment->post_parent, $attachment->link);
				}
				$html .= '</div>';
				$html .= '</li>';
			}
			$a["infinite_gallery"] = $html;
		}else{
			$a["infinite_gallery"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_infinite_right_content_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_right_content_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$style_css = sanitize_text_field($_POST["style_css"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$post_type = 'attachment';
		$post_status = 'inherit';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$limit = !empty($data['myg_gallery_img_per_page'])?$data['myg_gallery_img_per_page']:10;
		global $wpdb;
						
		$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type='mygallery' AND post_status='inherit' AND post_status='publish' AND ID < %d ORDER BY ID DESC LIMIT %d", $last_attachment_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, $image_size );
				$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'"  data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<div class="myg_right_content myg_arrow myg_arrow_left">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if(has_post_thumbnail( $post_id->ID )){
				$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id->ID ), $image_size );
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$post_id->ID.'" data-myg-parent-id="'.$post_id->ID.'" title="'.$attachment->title.'" src="'.$src[0].'" />';
				}
				if($show_title == 'yes' && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc == 'yes' && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social == 'yes'){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '<div class="myg_clearfix">&nbsp;</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$a["infinite_gallery"] = $html;
		}else{
			$a["infinite_gallery"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_infinite_hover_gallery_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_hover_gallery_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$style_css = sanitize_text_field($_POST["style_css"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$post_type = 'attachment';
		$post_status = 'inherit';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$limit = !empty($data['myg_gallery_img_per_page'])?$data['myg_gallery_img_per_page']:10;
		$hc_class = array('myg-slideFromTop', 'myg-slideFromBottom', 'myg-slideFromLeft', 'myg-slideFromRight');
		
		global $wpdb;
				
		$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT %d", $last_attachment_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, $image_size );
				$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'"  data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<div class="myg_hover_content">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				$html .= '<article data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" class="myg-animate '.$hc_class[array_rand($hc_class)].'">';
				if($show_title == 'yes' && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc == 'yes' && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social == 'yes'){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= $this->social_content_mini($attachment->likes, $attachment->views, $myg_like, $myg_view, $like_event, $attachment->ID,$attachment->post_parent, $attachment->link);
				}
				$html .= '</article>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$a["infinite_gallery"] = $html;
		}else{
			$a["infinite_gallery"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_infinite_slider_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_slider_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_attachment_id = sanitize_text_field($_POST["last_attachment_id"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$direction = sanitize_text_field($_POST["direction"])=='next'?'prev':'next';
		$post_type = 'attachment';
		$post_status = 'inherit';
		$id = 'myg_isid_'.time();
		$pause = array(2000, 2500, 3000, 3300, 3600, 4000, 4300, 4600, 5000, 5300, 5600, 6000, 6500);
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		
		global $wpdb;
				
		$query = $wpdb->get_results($wpdb->prepare("SELECT attachment.ID AS ID FROM $wpdb->posts attachment INNER JOIN $wpdb->posts post ON (attachment.post_parent = post.ID) WHERE attachment.post_type='attachment' AND attachment.post_status='inherit' AND post.post_status='publish' AND post.post_type='mygallery' AND attachment.ID < %d ORDER BY attachment.ID DESC LIMIT 10", $last_attachment_id));
		
		if(!empty($query)){
				
			$slide_ata = '';
			$slide_img = '';
			foreach($query as $post_id) {
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, 'mygallery-six' );
				$slide_img .= '<li data-myg-attachment-id="'.$attachment->ID.'" data-slide-direction="'.$direction.'" data-show-social="'.$show_social.'"><img data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open" src="'.$attachment->url.'" /></li>';
				$slide_ata .= $attachment->ID.',';
			}
				$slide_ata = rtrim($slide_ata, ",");
				$html .= '<div id="'.$id.'" class="myg-bxslider-infinite">';
				$html .= '<ul class="bxslider" data-attachment-ids="'.$slide_ata.'">';
				$html .= $slide_img;
				$html .= '</ul>';
				$html .= '</div>';
				$html .='<script type="text/javascript">
							(function($){
								$("#'.$id.' .bxslider").bxSlider({
									controls : false,
									pager : false,
									auto : true,
									moveSlides : 1,
									minSlides: 2,
									maxSlides: 6,
									slideWidth: 240,
									slideMargin: 0,
									pause : '.$pause[array_rand($pause, 1)].',
									autoDirection : "'.$direction.'"
								});
							})(jQuery);
						</script>';
			
			$a["infinite_slider"] = $html;
		}else{
			$a["infinite_slider"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_infinite_album_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_infinite_album_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$album_id = sanitize_text_field($_POST["album_id"]);
		$album_no = sanitize_text_field($_POST["album_no"]);
		$post_type = 'mygallery';
		$post_status = 'publish';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$limit = !empty($data['myg_album_img_per_page'])?$data['myg_album_img_per_page']:5;
		global $wpdb;
		
		$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = '%s' AND post_status = '%s' AND ID < %d ORDER BY ID DESC LIMIT %d", $post_type, $post_status, $album_id, $limit));
				
		if(!empty($query)){
			foreach($query as $post_id) {				
				$gallery = MyGallery::get_by_id( $post_id->ID );
				$image_ids = $gallery->attachment_ids;
				$title = $post_id->post_title;
				$count = sizeof( $image_ids );

				if($count == 0){
					// Do nothing
				}elseif($count == 1){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'thumbnail' );
					$html .= '<div class="myg_album_image_wrap" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<div class="myg_album_image" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<img class="myg_album_single" src="'.$attachment1->url.'" />';
					$html .= '</div>';
					$html .= '<div class="myg_album_image_count">'.$title.' ('.$count.')</div>';
					$html .= '</div>';
				}elseif($count == 2){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'thumbnail' );
					$attachment2 = MyGalleryAttachment::get_by_id( $image_ids[1], 'thumbnail' );
					$html .= '<div class="myg_album_image_wrap" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<div class="myg_album_image" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<img class="myg_album_photo1" src="'.$attachment1->url.'" />';
					$html .= '<img class="myg_album_photo2" src="'.$attachment2->url.'" />';
					$html .= '</div>';
					$html .= '<div class="myg_album_image_count">'.$title.' ('.$count.')</div>';
					$html .= '</div>';
				}elseif($count > 2){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'thumbnail' );
					$attachment2 = MyGalleryAttachment::get_by_id( $image_ids[1], 'thumbnail' );
					$attachment3 = MyGalleryAttachment::get_by_id( $image_ids[2], 'thumbnail' );
					$html .= '<div class="myg_album_image_wrap" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<div class="myg_album_image" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
					$html .= '<img class="myg_album_photo1" src="'.$attachment1->url.'" />';
					$html .= '<img class="myg_album_photo2" src="'.$attachment2->url.'" />';
					$html .= '<img class="myg_album_photo3" src="'.$attachment3->url.'" />';
					$html .= '</div>';
					$html .= '<div class="myg_album_image_count">'.$title.' ('.$count.')</div>';
					$html .= '</div>';
				}
			}
			$a["infinite_album"] = $html;
		}else{
			$a["infinite_album"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_timeline_album_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_timeline_album_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$last_album_id = sanitize_text_field($_POST["album_id"]);
		
		$post_type = 'mygallery';
		$post_status = 'publish';
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$limit = !empty($data['myg_album_img_per_page'])?$data['myg_album_img_per_page']:5;
		
		$lr_no = sanitize_text_field($_POST["album_no"])+1;
		
		global $wpdb;
		
		$query = $wpdb->get_results($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = '%s' AND post_status = '%s' AND ID < %d ORDER BY ID DESC LIMIT %d", $post_type, $post_status, $last_album_id, $limit));
		
		if(!empty($query)){
			foreach($query as $post_id) {
				$gallery = MyGallery::get_by_id( $post_id->ID );
				$image_ids = $gallery->attachment_ids;
				
				$count = sizeof( $image_ids );
				
				if($lr_no % 2 != 0){
					$lr_class = 'myg_album_timeline_left';
					$lr_arrow = 'myg_arrow_right';
					$tm_margin = 'margin-right:60px;';
					$tm_pos = 'right:-100px;';
				}else{
					$lr_class = 'myg_album_timeline_right';
					$lr_arrow = 'myg_arrow_left';
					$tm_margin = 'margin-left:60px;';
					$tm_pos = 'left:-100px;';
				}
				$html .= '<div class="myg_album_timeline_row '.$lr_class.'" data-myg-album-id="'.$post_id->ID.'">';
				if($count == 0){
					// Do nothing
				}elseif($count == 1){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'mygallery-four' );
					$html .= '<div class="myg_album_tm_wrap" style="'.$tm_margin.'">';
						$html .= '<div class="myg_album_tm_image_wrap myg_arrow '.$lr_arrow.'">';
							$html .= '<div class="myg_album_tm_image myg_clearfix" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
							$html .= '<img class="myg_album_tm_photo1" src="'.$attachment1->url.'" />';
							$html .= '</div>';
						$html .= '</div>';
						$html .= '<div class="myg_album_time_wrap" style="'.$tm_pos.'">';
							$html .= '<div class="myg_album_time_year">';
							$html .= date('Y', strtotime($gallery->post_date));
							$html .= '</div>';
							$html .= '<div class="myg_album_time_date">';
							$html .= '<div class="myg_tl_m">'.date('M, d', strtotime($gallery->post_date)).'</div>';
							$html .= '<div class="myg_tl_d">'.myg_time_elapsed($gallery->post_date).'</div>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				}elseif($count == 2){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'mygallery-six' );
					$attachment2 = MyGalleryAttachment::get_by_id( $image_ids[1], 'mygallery-six' );
					$html .= '<div class="myg_album_tm_wrap" style="'.$tm_margin.'">';
						$html .= '<div class="myg_album_tm_image_wrap myg_arrow '.$lr_arrow.'">';
							$html .= '<div class="myg_album_tm_image myg_clearfix" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
							$html .= '<img class="myg_album_tm_photo2" src="'.$attachment1->url.'" />';
							$html .= '<img class="myg_album_tm_photo3" src="'.$attachment2->url.'" />';
							$html .= '</div>';
						$html .= '</div>';
						$html .= '<div class="myg_album_time_wrap" style="'.$tm_pos.'">';
							$html .= '<div class="myg_album_time_year">';
							$html .= date('Y', strtotime($gallery->post_date));
							$html .= '</div>';
							$html .= '<div class="myg_album_time_date">';
							$html .= '<div class="myg_tl_m">'.date('M, d', strtotime($gallery->post_date)).'</div>';
							$html .= '<div class="myg_tl_d">'.myg_time_elapsed($gallery->post_date).'</div>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				}elseif($count > 2){
					$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'mygallery-four' );
					$attachment2 = MyGalleryAttachment::get_by_id( $image_ids[1], 'mygallery-six' );
					$attachment3 = MyGalleryAttachment::get_by_id( $image_ids[2], 'mygallery-six' );
					$html .= '<div class="myg_album_tm_wrap" style="'.$tm_margin.'">';
						$html .= '<div class="myg_album_tm_image_wrap myg_arrow '.$lr_arrow.'">';
							$html .= '<div class="myg_album_tm_image myg_clearfix" data-event="myg_open_album_gallery" data-myg-album-id="'.$post_id->ID.'">';
							$html .= '<img class="myg_album_tm_photo1" src="'.$attachment1->url.'" />';
							$html .= '<img class="myg_album_tm_photo2" src="'.$attachment2->url.'" />';
							$html .= '<img class="myg_album_tm_photo3" src="'.$attachment3->url.'" />';
							$html .= '</div>';
							$html .= '<div class="myg_album_tm_image_count">'.$gallery->image_count().'</div>';
						$html .= '</div>';
						$html .= '<div class="myg_album_time_wrap" style="'.$tm_pos.'">';
							$html .= '<div class="myg_album_time_year">';
							$html .= date('Y', strtotime($gallery->post_date));
							$html .= '</div>';
							$html .= '<div class="myg_album_time_date">';
							$html .= '<div class="myg_tl_m">'.date('M, d', strtotime($gallery->post_date)).'</div>';
							$html .= '<div class="myg_tl_d">'.myg_time_elapsed($gallery->post_date).'</div>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$lr_no++;
			}
			$a["timeline_album"] = $html;
		}else{
			$a["timeline_album"] = "<center style=\"margin: 10px\">".$no_result."</center>";
		}
						
		echo json_encode($chat);
		exit;
    }
	
    /*
     * myg_load_modal_content_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_load_modal_content_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$a["comments_row"] = '';
		$attachment_id = sanitize_text_field($_POST["attachment_id"]);
		$parent_id = sanitize_text_field($_POST["parent_id"]);
		$userid = sanitize_text_field($_POST["user_id"]);
		$userip = sanitize_text_field($_POST["user_ip"]);
		
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
				
		global $wpdb;
		$new_view = $this->myg_view_function($attachment_id);
		$attachment = MyGalleryAttachment::get_by_id( $attachment_id, 'mygallery-modal' );

		$a["attachment_id"] = $attachment_id;
		$a["post_parent"] = $parent_id;
		$a["image_src"] = $attachment->url;
		$a["title"] = $attachment->title;
		$a["desc"] = $attachment->description;
		$a["myg_like"] = $attachment->is_liked?'myg_liked':'myg_like';
		$a["myg_view"] = $attachment->is_viewed?'myg_viewed':'myg_view';
		$a["like_event"] = $attachment->is_liked?'':' data-event="myg_like"';
		
		$a["likes"] = $attachment->likes;
		$a["views"] = $attachment->views;
		$a["comments"] = $attachment->comments;
		$a["social"] = myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
		$a["thumbnails"] = '';
		if($data['myg_modal_thumbnail']){
			$thumbs = MyGalleryAttachment::get_by_post( $parent_id, 'mygallery-tabs', 20);
					
			if(!empty($thumbs)){
				$a["thumbnails"] .= '<ul class="myg_modal_thumbnail myg_thumb_slider modal_caroufredsel">';
				foreach($thumbs as $key => $obj) {
					$a["thumbnails"] .= '<li><img data-event="myg_image_thumb" data-myg-attachment-id="'.$obj->attachment_id.'" data-myg-parent-id="'.$parent_id.'" src="'.$obj->image_src.'"/></li>'; //need post parent and post id
				}
				$a["thumbnails"] .= '</ul>';
				$a["thumbnails"] .= '<div class="caro-controls caro-wrapper"><div class="caro-controls-direction"><a id="msprev" class="caro-prev" href=""><i class="mygi-arrow_back"></i></a><a id="msnext" class="caro-next" href=""><i class="mygi-arrow_forward"></i></a></div></div>';
			}
		}
		
		echo json_encode($chat);
		exit;
    }
	
	public function myg_load_thumb_modal_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$a["comments_row"] = '';
		$attachment_id = sanitize_text_field($_POST["attachment_id"]);
		$userid = sanitize_text_field($_POST["user_id"]);
		$userip = sanitize_text_field($_POST["user_ip"]);
		
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
				
		global $wpdb;
		$new_view = $this->myg_view_function($attachment_id);
		$attachment = MyGalleryAttachment::get_by_id( $attachment_id, 'mygallery-modal' );

		$a["attachment_id"] = $attachment_id;
		$a["post_parent"] = $attachment->post_parent;
		$a["image_src"] = $attachment->url;
		$a["title"] = $attachment->title;
		$a["desc"] = $attachment->description;
		$a["myg_like"] = $attachment->is_liked?'myg_liked':'myg_like';
		$a["myg_view"] = $attachment->is_viewed?'myg_viewed':'myg_view';
		$a["like_event"] = $attachment->is_liked?'':' data-event="myg_like"';
		
		$a["likes"] = $attachment->likes;
		$a["views"] = $attachment->views;
		$a["comments"] = $attachment->comments;
		$a["social"] = myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));

		if($data['myg_modal_comments']){
			$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}myg_comments WHERE image_id = %d ORDER BY ID ASC LIMIT 50", $attachment_id));
			if(!empty($query)){
				foreach($query as $image) {
					if(!$image->is_guest){
						$src = $this->get_user_image_src($image->user_id);
					}else{
						$src = MYG_URL.'images/avatar_black.png';
					}
					$get_date = date("d-M-Y", strtotime($image->comment_time));
					$get_time = $this->time_elapsed($image->comment_time);
					
					$a["comments_row"] .= '<div class="myg_comment_row myg_clearfix">';
					$a["comments_row"] .= '<div class="myg_comment_avatar"><img src="'.$src.'"></div>';
					$a["comments_row"] .= '<div class="myg_comment_text">';
					$a["comments_row"] .= '<div>'.$image->comment.'</div>';
					$a["comments_row"] .= '<div class="myg_comment_time">';
						$a["comments_row"] .= '<div class="myg_half">';
						$a["comments_row"] .= $get_date; 
						$a["comments_row"] .= '</div>';
						$a["comments_row"] .= '<div class="myg_half myg_text_right">';
						$a["comments_row"] .= $get_time;
						$a["comments_row"] .= '</div>';
					$a["comments_row"] .= '</div>';
					$a["comments_row"] .= '</div>';
					$a["comments_row"] .= '</div>';
				}
			}
		}
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_next_modal_content_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_next_modal_content_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$a["has_next"] = true;
		$a["comments_row"] = '';
		$attachment_id = sanitize_text_field($_POST["attachment_id"]);
		$post_parent = sanitize_text_field($_POST["parent_id"]);
		$userid = sanitize_text_field($_POST["user_id"]);
		$userip = sanitize_text_field($_POST["user_ip"]);
		$myg_nav = sanitize_text_field($_POST["myg_nav"]);
		
		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
				
		global $wpdb;
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
		
				$new_view = $this->myg_view_function($post_id->ID);
				
				$attachment = MyGalleryAttachment::get_by_id( $post_id->ID, 'mygallery-modal' );
				$a["myg_nav"] = $myg_nav;
				$a["attachment_id"] = $post_id->ID;
				$a["parent_id"] = $post_parent;
				$a["image_src"] = $attachment->url;
				$a["title"] = $attachment->title;
				$a["desc"] = $attachment->description;
				$a["myg_like"] = $attachment->is_liked?'myg_liked':'myg_like';
				$a["myg_view"] = $attachment->is_viewed?'myg_viewed':'myg_view';
				$a["like_event"] = $attachment->is_liked?'':' data-event="myg_like"';
				
				$a["likes"] = $attachment->likes;
				$a["views"] = $attachment->views;
				$a["comments"] = $attachment->comments;
				$a["social"] = myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
			}
		}else{
			$a["has_next"] = false;
		}
		
		echo json_encode($chat);
		exit;
    }

    /*
     * myg_load_album_gallery_function for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_load_album_gallery_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$a["album_gallery"] = '';
		$html = '';
		$album_id = sanitize_text_field($_POST["album_id"]);
		
		$data = get_option('mygallery_options');
		
		$image_size = !empty($data['myg_algal_image_size'])?$data['myg_algal_image_size']:'mygallery-four';
		$style_class = !empty($data['myg_algal_style_class'])?$data['myg_algal_style_class']:'';
		$style_css = !empty($data['myg_algal_style_css'])?$data['myg_algal_style_css']:'';
		$show_title = $data['myg_algal_show_title']?$data['myg_algal_show_title']:0;
		$show_desc = $data['myg_algal_show_desc']?$data['myg_algal_show_desc']:0;
		$show_social = $data['myg_algal_show_social']?$data['myg_algal_show_social']:0;
		$show_border = $data['myg_algal_show_border']?$data['myg_algal_show_border']:0;
		$image_per_page = !empty($data['myg_algal_image_per_page'])?$data['myg_algal_image_per_page']:10;
		$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$layout = !empty($data['myg_album_gallery_layout'])?$data['myg_album_gallery_layout']:'default';
		$hc_class = array('myg-slideFromTop', 'myg-slideFromBottom', 'myg-slideFromLeft', 'myg-slideFromRight');
		if($show_border){
			$border = 'border: 1px solid #eaeaea; padding:5px; margin:5px;';
		}else{
			$border = '';
		}
		
		$gallery = MyGallery::get_by_id( $album_id );
		$attachment_ids = $gallery->attachment_ids;
		arsort($attachment_ids);
		$count = sizeof( $attachment_ids );
		$sliced_ids = array_slice($attachment_ids, 0, $image_per_page);
		$no = 0;
		$a["album_id"] = $album_id;
		$a["title"] = $gallery->name;
		//$a["desc"] ='';		
		foreach($sliced_ids as $attachment_id): 
			$attachment = MyGalleryAttachment::get_by_id( $attachment_id, $image_size );
			$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'" data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
			if($layout == 'default'){
				$html .= '<div class="myg_image_wrap" style="'.$border.' '.$style_css.'">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
			}elseif($layout == 'hover-content'){
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<div class="myg_hover_content">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				$html .= '<article data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" class="myg-animate '.$hc_class[array_rand($hc_class)].'">';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				$html .= '</article>';
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}elseif($layout == 'right-content'){
				$html .= '<div class="myg_image_wrap" style="margin:5px;'.$style_css.'">';
				$html .= '<div class="myg_right_content myg_arrow myg_arrow_left">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= '</div>';
		endforeach;
		
		$a["album_gallery"] = $html;
		
		if($image_per_page >= $count){
			$a["album_gallery"] .= "<center style=\"margin: 10px\">".$no_result."</center>";
			
		}
				
		$a["is_more_gallery"] = $count > $image_per_page? true : false;
						
		echo json_encode($chat);
		exit;
    }


    public function myg_reload_album_gallery_function() {
		header("Content-Type: application/json");
		$chat = array();
		$a = &$chat;
		$html = '';
		$a["album_gallery"] = '';
		$album_id = sanitize_text_field($_POST["album_id"]);
		$last_attachment_id = sanitize_text_field($_POST["last_attach_id"]);
		$image_size = sanitize_text_field($_POST["image_size"]);
		$style_class = sanitize_text_field($_POST["style_class"]);
		$style_css = sanitize_text_field($_POST["style_css"]);
		$show_title = sanitize_text_field($_POST["show_title"]);
		$show_desc = sanitize_text_field($_POST["show_desc"]);
		$show_social = sanitize_text_field($_POST["show_social"]);
		$no_of_img = sanitize_text_field($_POST["no_of_img"]);

		$data = get_option('mygallery_options');
    	$no_result = !empty($data['myg_lg_no_result'])?$data['myg_lg_no_result']:'No more results.';
		$limit = !empty($data['myg_algal_image_per_page'])?$data['myg_algal_image_per_page']:10;
		
		$layout = !empty($data['myg_album_gallery_layout'])?$data['myg_album_gallery_layout']:'default';
		$hc_class = array('myg-slideFromTop', 'myg-slideFromBottom', 'myg-slideFromLeft', 'myg-slideFromRight');
		global $wpdb;
		
		$gallery = MyGallery::get_by_id( $album_id );
		$attachment_ids = $gallery->attachment_ids;
		$count = sizeof( $attachment_ids );
		arsort($attachment_ids);
		if($count > $no_of_img && $count - $no_of_img < $limit){
			$last = $count - $no_of_img;
			$sliced_ids = array_slice($attachment_ids, $no_of_img, $last);
		}else{
			$sliced_ids = array_slice($attachment_ids, $no_of_img, $limit);
		}
		$a["album_id"] = $album_id;
				
		foreach($sliced_ids as $attachment_id): 
			$attachment = MyGalleryAttachment::get_by_id( $attachment_id, $image_size );
			$html .= '<div class="myg_image myg_infinite_image '.$image_size.'" data-show-social="'.$show_social.'" data-show-title="'.$show_title.'" data-show-desc="'.$show_desc.'"  data-myg-columns="'.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
			if($layout == 'default'){
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
			}elseif($layout == 'hover-content'){
				$html .= '<div class="myg_image_wrap" style="'.$style_css.'">';
				$html .= '<div class="myg_hover_content">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				$html .= '<article data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" class="myg-animate '.$hc_class[array_rand($hc_class)].'">';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				$html .= '</article>';
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}elseif($layout == 'right-content'){
				$html .= '<div class="myg_image_wrap" style="margin:5px;'.$style_css.'">';
				$html .= '<div class="myg_right_content myg_arrow myg_arrow_left">';
				$html .= '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				if($show_title && $attachment->title){
					$html .= '<h4>' . $attachment->title . '</h4>';
				}
				if($show_desc && $attachment->description){
					$html .= '<p>' . myg_content($attachment->description) . '</p>';
				}
				if($show_social){
					$myg_like = $attachment->is_liked?'myg_liked':'myg_like';
					$myg_view = $attachment->is_viewed?'myg_viewed':'myg_view';
					$like_event = $attachment->is_liked?'':' data-event="myg_like"';
					$html .= '<div class="myg_social myg_clearfix">';
						$html .= '<div class="myg_social_left">';
							$html .= '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
							$html .= myg_social_share($attachment->title, $attachment->link, myg_content($attachment->description));
						$html .= '</div>';
						$html .= '<div class="myg_social_right">';
							$html .= '<span class="'.$myg_view.'" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->views.'</span>';
							$html .= '<span class="myg_like_tab '.$myg_like.' myg_like_'.$attachment->ID.'" data-myg-attachment-id="'.$attachment->ID.'"'.$like_event.'>&nbsp;</span><span class="myg_like_no_'.$attachment->ID.'">'.$attachment->likes.'</span>';
							$html .= '<span class="myg_comment" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" data-event="myg_image_open">&nbsp;</span><span>'.$attachment->comments.'</span>';
						$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}
			
			$html .= '</div>';
			$no_of_img++;
		endforeach;
		$a["album_gallery"] .= $html;
		
		if($no_of_img >= $count){
			$a["album_gallery"] .= "<center style=\"margin: 10px\">".$no_result."</center>";
			
		}
		
		$a["no_of_img"] = $no_of_img;
				
		$a["is_more_gallery"] = $count > $no_of_img? true : false;
					
		echo json_encode($chat);
		exit;
    }
				
	 /*
     * myg_like_function functions for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_like_function() {

        header("Content-Type: application/json");
		
		$chat = array();
		$a = &$chat;
		
		global $wpdb;
		
		$attachment_id = sanitize_text_field($_POST["attachment_id"]);
		$user_id = sanitize_text_field($_POST["user_id"]);
		$user_ip = myg_get_client_ip();
		$like = 1;
		
		if($user_id == 0){
			$guest = 1;
		}else{
			$guest = 0;
		}
					
		$insert_id = $wpdb->insert( 
			$wpdb->base_prefix.'myg_likes', 
			array( 
				'image_id' => $attachment_id, 
				'user_id' => $user_id,
				'user_ip' => $user_ip,
				'is_liked' => $like,
				'is_guest' => $guest,
			), 
			array( 
				'%d', 
				'%d',
				'%s',
				'%d',
				'%d',
			) 
		);
		
		$a["attachment_id"] = $attachment_id;
		if($insert_id){
			$a["insert_id"] = $insert_id;
		}else{
			$a["insert_id"] = 0;
		}
		
		echo json_encode($chat);
        exit;
    }
	
	 /*
     * myg_view_function functions for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_view_function($attachment_id) {
		
		global $wpdb;
		$is_viewed = 1;
		if(is_user_logged_in()){
			$user_id = 	get_current_user_id();
			$user_ip = myg_get_client_ip();
			$is_guest = 0;
			$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}myg_likes WHERE image_id = %d AND user_id = %d ORDER BY ID ASC LIMIT 1", $attachment_id, $user_id));
		}else{
			$user_id = 	0;
			$user_ip = myg_get_client_ip();
			$is_guest = 1;
			$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->base_prefix}myg_likes WHERE image_id = %d AND user_ip = %d ORDER BY ID ASC LIMIT 1", $attachment_id, $user_ip));
			
		}
		
		if(empty($query)){
			$insert_id = $wpdb->insert( 
				$wpdb->base_prefix.'myg_likes', 
				array( 
					'image_id' => $attachment_id, 
					'user_id' => $user_id,
					'user_ip' => $user_ip,
					'is_viewed' => $is_viewed,
					'is_guest' => $is_guest,
				), 
				array( 
					'%d', 
					'%d',
					'%s',
					'%d',
					'%d',
				) 
			);
			
			if($insert_id){
				return true;
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
    }
	/*
     * myg_post_comment_function functions for handling AJAX request
     *
     * @param  -
     * @return -
     */

    public function myg_post_comment_function() {

        header("Content-Type: application/json");
		
		$chat = array();
		$a = &$chat;
		
		global $wpdb;
		$wpdb->show_cr_errors = true;
		
		$comment = sanitize_text_field($_POST["myg_comment"]);
		$attachment_id = sanitize_text_field($_POST["attachment_id"]);
		$time = sanitize_text_field($_POST["time"]);
		if(is_user_logged_in()){
			$user_id = get_current_user_id();
			$is_guest = 0;
		}else{
			$user_id = 0;
			$is_guest = 1;
		}
		$user_ip = myg_get_client_ip();
		$time = date('Y-m-d H:i:s');
		$row = $wpdb->insert( 
			$wpdb->base_prefix.'myg_comments', 
			array( 
				'image_id' => $attachment_id,
				'user_id' => $user_id, 
				'user_ip' => $user_ip,
				'comment' => $comment,
				'comment_time' => $time,
				'is_guest' => $is_guest,
			), 
			array( 
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%d'
			) 
		);
		
		$a['row_time'] = $time;
		
		if($row){
			$a['is_insert'] = 1;
		}else{
			$a['is_insert'] = 0;
		}
		
		echo json_encode($chat);
        exit;
    }
	
	public function get_user_image_src($UserId){
		$src = str_replace('&','&amp;',get_avatar_url($UserId));
		
		return $src;
		
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
	
	public function time_elapsed($times){
		$ptime = strtotime($times);
		$etime = time() - $ptime;
	
		if ($etime < 1){
			return '0 sec';
		}
	
		$a = array( 365 * 24 * 60 * 60  =>  'year',
					 30 * 24 * 60 * 60  =>  'month',
						  24 * 60 * 60  =>  'day',
							   60 * 60  =>  'hr',
									60  =>  'min',
									 1  =>  'sec'
					);
		$a_plural = array( 'year'   => 'years',
						   'month'  => 'months',
						   'day'    => 'days',
						   'hr'   => 'hrs',
						   'min' => 'mins',
						   'sec' => 'secs'
					);
	
		foreach ($a as $secs => $str){
			$d = $etime / $secs;
			if ($d >= 1){
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
			}
		}
	}

	
}

?>