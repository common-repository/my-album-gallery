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
$is_sidebar = !empty($data['myg_sp_show_sidebar'])?$data['myg_sp_show_sidebar']:'yes';
$sidebar_pos = !empty($data['myg_sp_sidebar_pos'])?$data['myg_sp_sidebar_pos']:'left';
$sidebar = !empty($data['myg_sp_sidebar'])?$data['myg_sp_sidebar']:'';
$columns = !empty($data['myg_sp_image_size'])?$data['myg_sp_image_size']:'mygallery-four';

$show_album_title = !empty($data['myg_sp_album_title'])?$data['myg_sp_album_title']:'no';
$show_album_desc = !empty($data['myg_sp_album_desc'])?$data['myg_sp_album_desc']:'no';

$gallery_type = !empty($data['myg_sp_gallery_type'])?$data['myg_sp_gallery_type']:'masonry';

$ajax_single = new mygallery_Ajax();
if($columns == 'mygallery-four') {
	$image_size = 'mygallery-four';
}elseif($columns == 'mygallery-five') {
	$image_size = 'mygallery-five';
}elseif($columns == 'mygallery-six') {
	$image_size = 'mygallery-six';
}elseif($columns == 'mygallery-three') {
	$image_size = 'mygallery-three';
}elseif($columns == 'mygallery-two') {
	$image_size = 'mygallery-two';
}
			
?>

<main id="site-content" role="main">

	<div class="myg_container myg_single_page">
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
			global $post;
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					?>
					<?php if($show_album_title == 'yes' || $show_album_desc == 'yes' ){ ?>
                    <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<div class="post-inner">
                    	<?php if($show_album_title == 'yes' ){ ?>
                        <header class="entry-header alignwide">
                            <?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
                        </header>
                        <?php } ?>
                        <?php if($show_album_desc == 'yes' ){ ?>
						<div class="entry-content">
						<?php the_content( );?>
						</div><!-- .entry-content -->
                        <?php } ?>
					</div><!-- .post-inner -->
					</article><!-- .post -->
                    <?php } ?>
					<?php
				}
			}
			?>
        	<?php
			if ( $gallery_type == 'thumbnails' ) {
			?>
            <div class="myg_gallery myg_clearfix">
            <?php
			$gallery = MyGallery::get_by_id( $post->ID );
			foreach ( $gallery->attachments($image_size) as $attach ) {
				$attachment = MyGalleryAttachment::get_by_id( $attach->ID, $image_size );
				echo  '<div class="myg_image '.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				echo  '<div class="myg_image_wrap">';
				echo  '<div class="myg_hover_content">';
				echo  '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" title="'.$attachment->title.'" src="'.$attachment->url.'" />';
				echo  '</div>';
				echo  '</div>';
				echo  '</div>';
			}
            ?>
            </div>
            <?php
			}else{
			?>
            <ul class="myg_gallery myg-masonry myg_clearfix">
            <?php
			$gallery = MyGallery::get_by_id( $post->ID );
			foreach ( $gallery->attachments($image_size) as $attach ) {
				$attachment = MyGalleryAttachment::get_by_id( $attach->ID, 'mygallery-masonry' );
				echo  '<li class="myg_image myg-item '.$image_size.'" data-myg-attachment-id="'.$attachment->ID.'">';
				echo  '<div class="myg_image_wrap">';
				echo  '<img data-event="myg_image_open" data-myg-attachment-id="'.$attachment->ID.'" data-myg-parent-id="'.$attachment->post_parent.'" src="'.$attachment->url.'" />';				
				echo  '</div>';
				echo  '</li>';
			}
			?>
            </ul>
            <?php
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