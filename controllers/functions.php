<?php
/**
 * MyGallery global functions
 *
 */

/**
 * Returns the name of the plugin. (Allows the name to be overridden from extensions or functions.php)
 * @return string
 */
function mygallery_plugin_name() {
	return apply_filters( 'mygallery_plugin_name', 'MyGallery' );
}

/**
 * Builds up a MyGallery gallery shortcode
 *
 * @param $gallery_id
 *
 * @return string
 */
function mygallery_build_gallery_shortcode( $gallery_id ) {
	return '[' . mygallery_gallery_shortcode_tag() . ' id="' . $gallery_id . '"]';
}

/**
 * Returns the gallery shortcode tag
 *
 * @return string
 */
function mygallery_gallery_shortcode_tag() {
	return apply_filters( 'mygallery_gallery_shortcode_tag', MYG_CPT_GALLERY );
}


function mygallery_columns_id($columns){
	$post_new_columns = array(
       'mygallery_id' => 'ID'
    );
    return array_merge( $columns, $post_new_columns );
}
add_filter('manage_mygallery_posts_columns', 'mygallery_columns_id', 5);

function mygallery_custom_id_columns($column, $post_id){
	if($column === 'mygallery_id'){
		echo $post_id;
	}
}
add_action('manage_mygallery_posts_custom_column', 'mygallery_custom_id_columns', 5, 2);
			
/**
 * Parse some content and return an array of all gallery shortcodes that are used inside it
 *
 * @param $content The content to search for gallery shortcodes
 *
 * @return array An array of all the mygallery shortcodes found in the content
 */
function mygallery_extract_gallery_shortcodes( $content ) {
	$shortcodes = array();

	$regex_pattern = mygallery_gallery_shortcode_regex();
	if ( preg_match_all( '/' . $regex_pattern . '/s', $content, $matches ) ) {
		for ( $i = 0; $i < count( $matches[0] ); ++$i ) {
			$shortcode = $matches[0][$i];
			$args = $matches[3][$i];
			$attribure_string = str_replace( ' ', '&', trim( $args ) );
			$attribure_string = str_replace( '"', '', $attribure_string );
			$attributes = wp_parse_args( $attribure_string );
			if ( array_key_exists( 'id', $attributes ) ) {
				$id           = intval( $attributes['id'] );
				$shortcodes[ $id ] = $shortcode;
			}
		}
	}

	return $shortcodes;
}

/**
 * Build up the MyGallery shortcode regex
 *
 * @return string
 */
function mygallery_gallery_shortcode_regex() {
	$tag = mygallery_gallery_shortcode_tag();

	return
		'\\['                              	 // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($tag)"                     		 // 2: Shortcode name
		. '(?![\\w-])'                       // Not followed by word character or hyphen
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag ...
		.     '\\]'                          // ... and closing bracket
		. '|'
		.     '\\]'                          // Closing bracket
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing bracket for escaping shortcodes: [[tag]]
}

/**
 * Image resize during upload
 */
 add_action( 'init', 'mygallery_image' );
 function mygallery_image(){
	add_image_size('mygallery-two', 1280, 960, true);
	add_image_size('mygallery-three', 854, 640, true);
	add_image_size('mygallery-four', 640, 480, true);
	add_image_size('mygallery-five', 512, 384, true);
	add_image_size('mygallery-six', 427, 320, true);
	add_image_size('mygallery-tabs', 80, 80, true);
	add_image_size('mygallery-masonry', 1280, 960, false);
	add_image_size('mygallery-modal', 1440, 720, false);
	add_image_size('mygallery-hd', 1920, 1080, true);
 }

function myg_content($raw_content, $strip_html = true) {
	$data = get_option('mygallery_options');
	$limit = !empty($data['myg_gallery_text_limit'])?$data['myg_gallery_text_limit']:10;
	if($strip_html) {
		$raw_content = strip_shortcodes( strip_tags( $raw_content ) );
	} else {
		$raw_content = strip_shortcodes( $raw_content );
	}

	if($raw_content) {
		$content = explode(' ', $raw_content, $limit);
		if (count($content)>=$limit) {
			array_pop($content);
			$content = implode(" ",$content).' ...';
		} else {
			$content = implode(" ",$content);
		}	
		$content = preg_replace('/\[.+\]/','', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}
}


/**
 * Pagination function
 */

if(!function_exists('mygallery_pagination')){ 
	function mygallery_pagination($pages = '', $range = 6){
		 $showitems = ($range * 2)+1;  
		 global $paged;
		 if(empty($paged)) $paged = 1;
		 if($pages == ''){
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages){
				 $pages = 1;
			 }
		 }  
		 $paginate = ''; 
		 if(1 != $pages){
			 $paginate .= '<div class="mygallery-pagination-holder myg_clearfix">';
			 $paginate .= '<ul class="mygallery-pagination">';
			 if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
			 	$paginate .= '<li><a class="mygallery-pagination-first" href="'.get_pagenum_link(1).'" title="First">&laquo;</a></li>';
			 }
			 if($paged > 1){
				 $paginate .= '<li><a class="mygallery-pagination-prev" title="Previous" href="'.get_pagenum_link($paged - 1).'">&lsaquo;</a></li>';
			 }
			 for ($i=1; $i <= $pages; $i++){
				 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					 $paginate .= ($paged == $i)? '<li><span class="myg-active">'.$i.'</span></li>':'<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
				 }
			 }
			 if ($paged < $pages){
				$paginate .= '<li><a class="mygallery-pagination-next" title="Next" href="'.get_pagenum_link($paged + 1).'">&rsaquo;</a></li>'; 
			 }
			 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages){
				$paginate .= '<li><a class="mygallery-pagination-last" href="'.get_pagenum_link($pages).'" title="Last">&raquo;</a></li>';
			 }
			 $paginate .= "</ul>";
			 $paginate .= "</div>";
		 }
		 return $paginate;
	}
}
function mygallery_pagination_1() {

	global $gallery;

	/** Stop execution if there's only 1 page */
	if( $gallery->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $gallery->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="ft_pagination"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link('&laquo;') );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link('&raquo;') );

	echo '</ul></div>' . "\n";

}

// Function to get the client IP address
function myg_get_client_ip(){
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])){
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER['HTTP_X_FORWARDED'])){
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}elseif(isset($_SERVER['HTTP_FORWARDED'])){
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
	}elseif(isset($_SERVER['REMOTE_ADDR'])){
			$ipaddress = $_SERVER['REMOTE_ADDR'];
	}else{
			$ipaddress = 'UNKNOWN';
	}
    return $ipaddress;
}

function myg_social_share($title, $link, $desc){
	
	$social = '<div class="myg_social_tab"><div class="myg_social_wrap">
			<a href="https://www.facebook.com/sharer/sharer.php?u='.$link.'&amp;t='.$title.'" class="social" target="_blank" onclick="return revs_sharegallery(this.href)" title="facebook"><img src="'.MYG_URL.'images/facebook.png" ></a>
			<a href="https://twitter.com/home?status='.$title.$link.'" class="social" target="_blank" title="twitter" onclick="return revs_sharegallery(this.href)"><img src="'.MYG_URL.'images/twitter.png" ></a>
			<a href="https://linkedin.com/shareArticle?mini=true&amp;url='.$link.'&amp;title='.$title.'" class="social" target="_blank" title="Linkedin" onclick="return revs_sharegallery(this.href)"><img src="'.MYG_URL.'images/linkedin.png" ></a>
			<a href="https://www.tumblr.com/share/link?url='.urlencode($link).'&amp;name='.urlencode($title).'&amp;description='.urlencode($desc).'" class="social" target="_blank" title="Tumblr" onclick="return revs_sharegallery(this.href)"><img src="'.MYG_URL.'images/tumblr.png" ></a>
			<a href="https://pinterest.com/pin/create/button/?url='.urlencode($link).'&amp;description='.urlencode($title).'" class="social"  target="_blank" title="Pinterest" onclick="return revs_sharegallery(this.href)"><img src="'.MYG_URL.'images/pinterest.png" ></a>
		</div>
		<script>
			function revs_sharegallery(url) {
				newwindow=window.open(url,"name","height=400,width=500");
				if (window.focus) {newwindow.focus()}
				return false;
			}
		</script>
		</div>';
	
	return $social;

}

function myg_social_content($likes, $views, $comments, $myg_like, $myg_view, $like_event, $attach_id, $title, $link, $desc){
			
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

function myg_time_elapsed($times){
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

