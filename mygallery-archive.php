<?php
/**
 * The template for displaying single Album Gallery posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 */

get_header();

$data = get_option('mygallery_options');
$is_sidebar = !empty($data['myg_ap_show_sidebar'])?$data['myg_ap_show_sidebar']:'yes';
$sidebar_pos = !empty($data['myg_ap_sidebar_pos'])?$data['myg_ap_sidebar_pos']:'left';
$sidebar = !empty($data['myg_ap_sidebar'])?$data['myg_ap_sidebar']:'';
?>

<main id="site-content" role="main">

	<div class="myg_container myg_archive_page">
        <div class="myg_row">
        	<?php if($is_sidebar == 'yes' && $sidebar_pos == 'left' && is_active_sidebar($sidebar)): ?>
            <div class="myg-col-md-3">
                <div class="myg-left-sidebar">
                <?php dynamic_sidebar( $sidebar ); ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if ( $is_sidebar == 'yes') : ?>
            <div class="myg-col-md-9">
            <?php else: ?>
            <div class="myg-col-md-12">
            <?php endif; ?>
            
            <?php
			global $post; global $wp_query;
			if ( have_posts() ) {
				echo '<div class="myg_album_container myg_clearfix">';
				while ( have_posts() ) {
					the_post();
					?>
					<?php
                     $gallery = MyGallery::get_by_id( get_the_ID() );
					$image_ids = $gallery->attachment_ids;
					$title = esc_html( get_the_title() );
					$permalink = get_permalink(get_the_ID());
					$count = sizeof( $image_ids );
					
					if($count >= 1){
						$attachment1 = MyGalleryAttachment::get_by_id( $image_ids[0], 'thumbnail' );
						echo '<div class="myg_album_image_wrap">';
						echo '<a class="myg_album_image" href="'.$permalink.'" data-myg-album-id="'.get_the_ID().'">';
						echo '<img class="myg_album_single" src="'.$attachment1->url.'" />';
						echo '</a>';
						echo '<div class="myg_album_image_count">'.$title.' ('.$count.')</div>';
						echo '</div>';
					}
				}
				echo '</div>';
				
				echo mygallery_pagination($wp_query->max_num_pages, $range = 6);
			}
			?>            
            </div><!-- .col-md-9,12 -->
            <?php if ( $is_sidebar == 'yes'  && $sidebar_pos == 'right' && is_active_sidebar($sidebar) ) : ?>
            <div class="myg-col-md-3">
                <div class="myg-right-sidebar">
                <?php dynamic_sidebar( $sidebar ); ?>
                </div>
            </div>
            <?php endif; ?>
        
        </div><!-- .myg_row -->
	</div><!-- .myg_container -->
</main><!-- #site-content -->

<?php get_footer(); ?>