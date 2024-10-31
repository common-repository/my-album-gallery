<?php

class mygallery_Script_Controller{

    public function enque_scripts(){
        add_action('wp_enqueue_scripts', array($this, 'include_scripts_styles'));
		add_action('wp_head', array($this, 'include_custom_styles'));
    }

    /*
     * Include AJAX plugin specific scripts and pass the neccessary data.
     *
     * @param  -
     * @return -
     */

    public function include_scripts_styles(){
        global $post;
		
		$data = get_option('mygallery_options');		
		
        wp_register_script('mygallery_ajax', plugins_url('js/mygallery-ajax.js', dirname(__FILE__)), array("jquery"));
        wp_enqueue_script('mygallery_ajax');

        $nonce = wp_create_nonce("unique_key");

        $ajax = new mygallery_Ajax();
        $ajax->initialize();
		
		$user_ip = myg_get_client_ip();
		if(is_user_logged_in()){
			$user_id = 	get_current_user_id();
			$src = str_replace('&','&amp;',get_avatar_url(get_current_user_id()));
		}else{
			$user_id = 0;
			$src = MYG_URL.'images/avatar_black.png';
		}
		
		
		
        $config_array = array(
            'ajaxURL' => admin_url('admin-ajax.php'),
            'ajaxActions' => $ajax->ajax_actions,
            'ajaxNonce' => $nonce,
            'siteURL' => site_url(),
			'pluginsURL' => plugins_url(),
			'userIP' => $user_ip,
			'userID' => $user_id,
			'avatar' => $src,
        );

        wp_localize_script('mygallery_ajax', 'mygallery_conf', $config_array);

        wp_register_style('mygallery_styles_css', plugins_url('css/mygallery-style.css', dirname(__FILE__)));
        wp_enqueue_style('mygallery_styles_css');
    }

    public function colourchanger($hex, $percent) {
		// Work out if hash given
		$hash = '';
		if (stristr($hex,'#')) {
			$hex = str_replace('#','',$hex);
			$hash = '#';
		}
		/// HEX TO RGB
		$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
		for ($i=0; $i<3; $i++) {
			// See if brighter or darker
			if ($percent > 0) {
				// Lighter
				$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
			} else {
				// Darker
				$positivePercent = $percent - ($percent*2);
				$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
			}
			// In case rounding up causes us to go to 256
			if ($rgb[$i] > 255) {
				$rgb[$i] = 255;
			}
		}
		//// RBG to Hex
		$hex = '';
		for($i=0; $i < 3; $i++) {
			// Convert the decimal digit to hex
			$hexDigit = dechex($rgb[$i]);
			// Add a leading zero if necessary
			if(strlen($hexDigit) == 1) {
			$hexDigit = "0" . $hexDigit;
			}
			// Append to the hex string
			$hex .= $hexDigit;
		}
		return $hash.$hex;
	}
	//$colour = '#ae64fe';
	//$brightness = 0.5; // lighter
	//$brightness = 0.3; // more lighter
	//$brightness = 0.1; // close to white
	//$newColour = colourchanger($colour,$brightness);
	//$colour = '#ae64fe';
	//$brightness = -0.5; // 50% darker
	//$brightness = -0.3; // more darker
	//$brightness = -0.1; // more darker close to black
	//$newColour = colourchanger($colour,$brightness);
	public function hex2rgba($hex,$opc) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = 'rgba('.$r.','.$g.','.$b.','.$opc.')';
	   return $rgb; // returns an array with the rgb values
	}
	
	public function include_custom_styles(){
		
		$data = get_option('mygallery_options');
    	$df_bdr = !empty($data['myg_default_bdr_color']) && $data['myg_default_bdr_color'] != '#'?$data['myg_default_bdr_color']:'';
		
		$paginate = !empty($data['myg_paginate_color']) && $data['myg_paginate_color']!= '#'?$data['myg_paginate_color']:'';
						
		$mdl_bg_color = !empty($data['myg_modal_bg_color']) && $data['myg_modal_bg_color'] != '#'?$data['myg_modal_bg_color']:'';
		$mdl_close_color = !empty($data['myg_modal_close_color']) && $data['myg_modal_close_color'] != '#'?$data['myg_modal_close_color']:'';
		$mdl_arrow_color = !empty($data['myg_modal_arrow_color']) && $data['myg_modal_arrow_color'] != '#'?$data['myg_modal_arrow_color']:'';
		$mdl_arrow_bg_color = !empty($data['myg_modal_arrow_bg_color']) && $data['myg_modal_arrow_bg_color'] != '#'?$data['myg_modal_arrow_bg_color']:'';
						
		$sa_page_width = !empty($data['myg_sap_width']) && $data['myg_sap_width'] != '#'?$data['myg_sap_width']:'';
		
		$border = !empty($df_bdr)?'
		body .myg_image_wrap, 
		body .myg_right_content, 
		body .myg_album_tm_image_wrap,
		body .myg_album_time_year,
		body .myg_album_time_wrap{
			border-color:'.$df_bdr.';
		}
		.myg_tl_m{
			border-bottom-color:'.$df_bdr.';
		}
		body .myg_album_timeline_bar{
			background-color:'.$df_bdr.';
		}
		.myg_arrow_left:after{border-color: rgba(255, 255, 255, 0);border-right-color: #ffffff;}
		.myg_arrow_left:before{border-color: rgba(218, 222, 225, 0);border-right-color: '.$df_bdr.';}
		.myg_arrow_right:after{border-color: rgba(255, 255, 255, 0);border-left-color: #ffffff;}
		.myg_arrow_right:before{border-color: rgba(218, 222, 225, 0);border-left-color: '.$df_bdr.';}
		':'';
		
		$border_rd = $data['myg_default_bdr_radious'] != 0?'
		body .myg_image_wrap{
			-moz-border-radius:'.$data['myg_default_bdr_radious'].'px;
			-webkit-border-radius:'.$data['myg_default_bdr_radious'].'px;
			-ms-border-radius:'.$data['myg_default_bdr_radious'].'px;
			-o-border-radius:'.$data['myg_default_bdr_radious'].'px;
			border-radius:'.$data['myg_default_bdr_radious'].'px;
		}
		':'';
		
		$mdl_color = !empty($mdl_bg_color)?'
		#myg_modal_wrap{
			background-color:'.$mdl_bg_color.';
		}
		':'';
		$mdl_color .= !empty($mdl_close_color)?'
		#myg_modal_close line{
			stroke:'.$mdl_close_color.';
		}
		':'';
		
		$mdl_color .= !empty($mdl_arrow_color)?'
		#myg_image_left:before, #myg_image_right:before{
			color:'.$mdl_arrow_color.';
		}
		
		':'';
		$mdl_color .= !empty($mdl_arrow_bg_color)?'
		#myg_image_left, #myg_image_right{
			background:'.$mdl_arrow_bg_color.';
			border-color:'.$mdl_arrow_bg_color.';
		}
		
		':'';
		
		$sap_width = !empty($sa_page_width)?'
		@media (min-width: 1200px) {
			.myg_container.myg_single_page,
			.myg_container.myg_archive_page{
				max-width:'.$sa_page_width.';
			}
		}
		@media (max-width: 992px) {
			.myg_container.myg_single_page,
			.myg_container.myg_archive_page{
				max-width:100%;
			}
		}
		':'';
		
		$pagination = !empty($paginate)?'
		body .mygallery-pagination a{
		background-color:'.$paginate.';
		*background-color:'.$paginate.';
		background-image: -moz-linear-gradient(top, '.$this->colourchanger($paginate, .8).', '.$this->colourchanger($paginate, -.5).');
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from('.$this->colourchanger($paginate, .8).'), to('.$this->colourchanger($paginate, -.5).'));
		background-image: -webkit-linear-gradient(top, '.$this->colourchanger($paginate, .8).', '.$this->colourchanger($paginate, -.5).');
		background-image: -o-linear-gradient(top, '.$this->colourchanger($paginate, .8).', '.$this->colourchanger($paginate, -.5).');
		background-image: -ms-linear-gradient(top, '.$this->colourchanger($paginate, .8).', '.$this->colourchanger($paginate, -.5).');
		background-image: linear-gradient(to bottom, '.$this->colourchanger($paginate, .8).', '.$this->colourchanger($paginate, -.5).');
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="'.$this->colourchanger($paginate, .8).'", endColorstr="'.$this->colourchanger($paginate, -.5).'", GradientType=0);
		filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
		box-shadow:none;
		text-shadow: none;
		color:#ffffff;
		border-color:'.$paginate.';
		}
		body .mygallery-pagination a:hover{
		background-color:'.$this->colourchanger($paginate, -.5).';
		*background-color:'.$this->colourchanger($paginate, -.5).';
		background-image: -moz-linear-gradient(top, '.$this->colourchanger($paginate, -.5).', '.$this->colourchanger($paginate, .8).');
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from('.$this->colourchanger($paginate, -.5).'), to('.$this->colourchanger($paginate, .8).'));
		background-image: -webkit-linear-gradient(top, '.$this->colourchanger($paginate, -.5).', '.$this->colourchanger($paginate, .8).');
		background-image: -o-linear-gradient(top, '.$this->colourchanger($paginate, -.5).', '.$this->colourchanger($paginate, .8).');
		background-image: -ms-linear-gradient(top, '.$this->colourchanger($paginate, -.5).', '.$this->colourchanger($paginate, .8).');
		background-image: linear-gradient(to bottom, '.$this->colourchanger($paginate, -.5).', '.$this->colourchanger($paginate, .8).');
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="'.$this->colourchanger($paginate, -.5).'", endColorstr="'.$this->colourchanger($paginate, .8).'", GradientType=0);
		filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
		box-shadow:none;
		text-shadow: none;
		color:#ffffff;
		border-color:'.$paginate.';
		}
		body .mygallery-pagination span.myg-active {
		  border-color:'.$paginate.';
		}
		':'';
		
		
		$custom_css = !empty($data['myg_custom_style'])?$data['myg_custom_style']:'';	
		
		echo "<style type=\"text/css\">".$border.$border_rd.$sap_width.$pagination.$custom_css.$mdl_color."</style>"; 
    }
	

}

?>