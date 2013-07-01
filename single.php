<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php /* Start the Loop */ ?>
            <?php 
            	if(get_post_type(get_the_ID()) == 'site') {
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            		query_posts($query_string . '&orderby=title&order=ASC&posts_per_page=-1&paged=$paged'); 
            	}
            ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				if(get_post_type(get_the_ID()) == 'site') {
					get_template_part( 'content', 'site' );
				} else {
					get_template_part( 'content', get_post_format() ); 	
				}
				?>

				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>