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