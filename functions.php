<?php
/**
 * docomomo.hk functions library
 * 
 */


/**
 * Use large size for attachments, instead of original
 */
add_filter( 'wp_get_attachment_link', 'adjust_attachment_link' , 10, 6 );
function adjust_attachment_link($markup, $id, $size, $permalink, $icon, $text) {
    
    $id = intval( $id );
    $_post = get_post( $id );

    if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url_array = wp_get_attachment_image_src( $_post->ID, 'large' ) )
            return __( 'Missing Attachment' );
    
    $url = $url_array[0];

    if ( $permalink )
            $url = get_attachment_link( $_post->ID );

    $post_title = esc_attr( $_post->post_title );

    if ( $text )
            $link_text = $text;
    elseif ( $size && 'none' != $size )
            $link_text = wp_get_attachment_image( $id, $size, $icon );
    else
            $link_text = '';

    if ( trim( $link_text ) == '' )
            $link_text = $_post->post_title;
    
    return "<a href='$url' title='$post_title'>$link_text</a>";
    
}

function language_selector(){
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
        echo '<ul>';
        foreach($languages as $l){
            echo '<li><a href="'.$l['url'].'">';
            if(!$l['active']) echo '<b>';
            echo $l['translated_name'];
            if(!$l['active']) echo '</b>';
            echo '</a></li>';
        }
        echo '</ul>';
    }
}

function language_selector_flags(){
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if(!empty($languages)){
        foreach($languages as $l){
            if(!$l['active']) echo '<a href="'.$l['url'].'">';
            echo '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />';
            if(!$l['active']) echo '</a>';
        }
    }
}

if ( ! function_exists( 'docomomo_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function docomomo_entry_meta() {
    // Translators: used between list items, there is a space after the comma.
    $categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

    // Translators: used between list items, there is a space after the comma.
    $tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

    $date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );

    /*$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), coauthors_posts_links(null,null,null,null,FALSE) ) ),
        get_the_author()
    );*/

    /*if(function_exists('coauthors')) {
        $i = new CoAuthorsIterator();
        $i->iterate();
        $author = '';
        do{
          if (!$i->is_first()) $author .= $i->is_last() ? ' a ' : ', ';
                $author .= sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
                           esc_url( get_author_posts_url( get_the_author_meta( $i->ID ) ) ),
                           esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
                           get_the_author()
                        );
        } while($i->iterate());
     } else {
        $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
                           esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                           esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
                           get_the_author()
                        );
     }*/

    foreach( get_coauthors() as $coauthor ) {
         $author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
                           esc_url( get_author_posts_url( $coauthor->ID, $coauthor->user_nicename ) ),
                           esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), $coauthor->display_name ) ),
                           $coauthor->display_name
                        );       
    }

    // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
    if ( $tag_list ) {
        $utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
    } elseif ( $categories_list ) {
        $utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
    } else {
        $utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
    }

    printf(
        $utility_text,
        $categories_list,
        $tag_list,
        $date,
        $author
    );
}
endif;


function docomomo_author_bio() {
    echo '';
}



/*<?php if ( is_singular() && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
<?php foreach( get_coauthors() as $coauthor ) : ?>
    <div class="author-info">
        <div class="author-avatar">
            <?php echo get_avatar( $coauthor->user_email, apply_filters( 'twentytwelve_author_bio_avatar_size', 68 ) ); ?>
        </div><!-- .author-avatar -->
        <div class="author-description">
            <h2><?php printf( __( 'About %s', 'twentytwelve' ), $coauthor->display_name ); ?></h2>
            <p><?php $coauthor->description; ?></p>
            <div class="author-link">
                <a href="<?php echo esc_url( get_author_posts_url( $coauthor->ID ) ); ?>" rel="author">
                    <?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentytwelve' ), $coauthor->display_name ); ?>
                </a>
            </div><!-- .author-link -->
        </div><!-- .author-description -->
    </div><!-- .author-info -->
<?php endforeach; endif; ?> */


function custom_post_author_archive( &$query )
{
    if ( $query->is_author )
        $query->set( 'post_type', 'site' );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' ); // run once!
}
add_action( 'pre_get_posts', 'custom_post_author_archive' );


function icl_post_languages(){
  $languages = icl_get_languages('skip_missing=1');
  if(1 < count($languages)){
    echo __('This post is also available in: ');
    foreach($languages as $l){
      if(!$l['active']) $langs[] = '<a href="'.$l['url'].'">'.$l['translated_name'].'</a>';
    }
    echo join(', ', $langs);
  }
}

