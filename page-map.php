<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

if(isset($_GET['csv'])) {

	header('Content-type: text/plain');

?>
title,address,excerpt,url,featured image url
<?php query_posts('post_type=site&posts_per_page=-1'); ?>
<?php 

while ( have_posts() ) : the_post();
 
	$address = types_render_field("address", array("raw"=>"true","separator"=>";"));
	$feature_image_url = wp_get_attachment_thumb_url( get_post_thumbnail_id($post->ID) );

	// title, address, content, url, featured_image url
	//echo get_the_title().",\"".$address."\",\"".get_the_excerpt()."\",".get_permalink().",".$feature_image_url."\n"; 
	//echo str_replace( 'transparent.local/', '', $feature_image_url)."\n";
	echo str_replace( 'transparent.local/', '', get_permalink())."\n";

endwhile;

} else {

	get_header();

	?>
	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
	<?php 

	get_sidebar();
	get_footer(); 

}