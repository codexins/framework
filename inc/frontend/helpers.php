<?php

/**
 * Various helper functions definition related to Codexin framework
 *
 * @package     Codexin
 * @subpackage  Core
 * @since       1.0
 */


// Do not allow directly accessing this file.
defined( 'ABSPATH' ) OR die( esc_html__( 'This script cannot be accessed directly.', 'TEXT_DOMAIN' ) );

if (!function_exists('codexin_get_option')){
    /**
     * Function to get options in front-end
     * @param int $option The option we need from the DB
     * @param string $default If $option doesn't exist in DB return $default value
     * @return string
     */
    function codexin_get_option( $option = false, $default = false ){
        if($option === false){
            return false;
        }
        $codexin_get_options = wp_cache_get( CODEXIN_THEME_OPTIONS );
        if( ! $codexin_get_options ){
            $codexin_get_options = get_option( CODEXIN_THEME_OPTIONS );
            wp_cache_delete( CODEXIN_THEME_OPTIONS );
            wp_cache_add( CODEXIN_THEME_OPTIONS, $codexin_get_options );
        }

        if(isset($codexin_get_options[$option]) && $codexin_get_options[$option] !== ''){
            return $codexin_get_options[$option];
        }else{
            return $default;
        }
    }
}

if ( ! function_exists( 'codexin_meta' ) ) {
    /**
     * Helper Function to get meta data from metabox
     *
     * @param   string     $key        The meta key name from which we want to get the value
     * @param   array      $args       The arguments of the meta key
     * @param   int        $post_id    The ID of the post
     * @return  mixed
     * @since   v1.0
     */
    function codexin_meta( $key, $args = array(), $post_id = null ){
        if( function_exists( 'rwmb_meta' ) ) {
            return rwmb_meta( $key, $args, $post_id );
        } else {
            return null;
        }
    }
}

if ( ! function_exists( 'codexin_comment_function' ) ) {
    /**
     * Custom Callback Function for Comment layout
     *
     * @param   $comment
     * @param   $args
     * @param   $depth
     * @since   v1.0
     */
    function codexin_comment_function( $comment, $args, $depth ) {

        $GLOBALS['comment'] = $comment; ?>

        <li id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-single">
                    <div class="comment-single-left comment-author vcard">
                        <?php echo get_avatar( $comment, $size='90' ); ?>
                    </div>

                    <div class="comment-single-right comment-info">
                    <?php printf( '<span class="fn" itemprop="name">%s</span>', get_comment_author_link() ); ?>
                        <div class="comment-text" itemprop="text">
                            <?php comment_text(); ?>
                        </div>

                        <div class="comment-meta">
                            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
                                <time datetime="<?php echo get_the_time('c'); ?>" itemprop="datePublished">
                                    <?php printf( esc_html__('%1$s at %2$s', 'reveal'), get_comment_date(),  get_comment_time() ); ?>
                                </time>
                            </a>
                            <?php edit_comment_link( esc_html__( '(Edit)', 'reveal' ),'  ','' ) ?>
                            <span class="comment-reply">
                                <?php 
                                comment_reply_link( array_merge( $args, 
                                    array( 
                                        'depth' => $depth, 
                                        'max_depth' => $args['max_depth'], 
                                        'before' => ' &nbsp;&nbsp;<i class="fa fa-caret-right"></i> &nbsp;&nbsp;' 
                                    ) 
                                ) ); 
                                ?>
                            </span>
                        </div>

                        <?php if ($comment->comment_approved == '0') { ?>
                            <div class="moderation-notice"><em><?php echo esc_html__('Your comment is awaiting moderation.', 'reveal') ?></em></div>
                        <?php } ?>

                    </div>
                </div>     
            </div>

     <?php
    }
}


if ( ! function_exists( 'codexin_sanitize_hex_color' ) ) {
    /**
     * Helper function to sanitize hex colors
     *
     * @param   string  $color  The color code
     * @return  string
     * @since   v1.0
     */
    function codexin_sanitize_hex_color( $color ) {
        if ( '' === $color ) {
            return '';
        }

        // make sure the color starts with a hash
        $color = '#' . ltrim( $color, '#' );

        // 3 or 6 hex digits, or the empty string.
        if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
            return $color;
        }

        return null;
    }
}



if ( ! function_exists( 'codexin_hex_to_rgba' ) ) {
    /**
     * Helper function to convert hex color to RGBA
     *
     * @param   string      $hex_color     The color code in hexadecimal
     * @param   float       $opacity       The color opacity we want
     * @return  string
     * @since   v1.0
     */
    function codexin_hex_to_rgba( $hex_color, $opacity = '' ) {
        $hex_color = str_replace( "#", "", $hex_color );
        if ( strlen( $hex_color ) == 3 ) {
            $r = hexdec( substr( $hex_color, 0, 1 ) . substr( $hex_color, 0, 1 ) );
            $g = hexdec( substr( $hex_color, 1, 1 ) . substr( $hex_color, 1, 1 ) );
            $b = hexdec( substr( $hex_color, 2, 1 ) . substr( $hex_color, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex_color, 0, 2 ) );
            $g = hexdec( substr( $hex_color, 2, 2 ) );
            $b = hexdec( substr( $hex_color, 4, 2 ) );
        }
        $rgb = $r . ',' . $g . ',' . $b;

        if ( '' == $opacity ) {
            return $rgb;
        } else {
            $opacity = floatval( $opacity );
            return 'rgba(' . $rgb . ',' . $opacity . ')';
        }
    }
}


if ( ! function_exists( 'codexin_adjust_color_brightness' ) ) {
    /**
     * Helper function to adjust brightness of a color
     *
     * @param   string      $hex_color        The color code in hexadecimal
     * @param   float       $percent_adjust   The percentage we want to lighten/darken the color
     * @return  string
     * @since   v1.0
     */
    function codexin_adjust_color_brightness( $hex_color, $percent_adjust = 0 ) {
        $percent_adjust = round( $percent_adjust/100,2 );    
        $hex = str_replace( "#","",$hex_color );

        $r = ( strlen( $hex ) == 3 ) ? hexdec ( substr ( $hex,0,1 ) . substr( $hex,0,1 ) ) : hexdec( substr( $hex,0,2 ) );
        $g = ( strlen( $hex ) == 3 ) ? hexdec ( substr ( $hex,1,1 ) . substr( $hex,1,1 ) ) : hexdec( substr( $hex,2,2 ) );
        $b = ( strlen( $hex ) == 3 ) ? hexdec ( substr ( $hex,2,1 ) . substr( $hex,2,1 ) ) : hexdec( substr( $hex,4,2 ) );
        $r = round( $r - ( $r*$percent_adjust ) );
        $g = round( $g - ( $g*$percent_adjust ) );
        $b = round( $b - ( $b*$percent_adjust ) );

        $new_hex = "#".str_pad( dechex( max( 0,min( 255,$r ) ) ),2,"0",STR_PAD_LEFT )
            .str_pad( dechex( max( 0,min( 255,$g ) ) ),2,"0",STR_PAD_LEFT)
            .str_pad( dechex( max( 0,min( 255,$b ) ) ),2,"0",STR_PAD_LEFT);

        return codexin_sanitize_hex_color( $new_hex );    
    }
}


if ( ! function_exists( 'codexin_get_page_title' ) ) {
    /**
     * Helper function to return a custom formated page title
     *
     * @param   string      $title      Title to show
     * @param   int         $id         Post ID
     * @return  mixed
     * @since   v1.0
     */
    function codexin_get_page_title( $title, $id = null ) {
        ?>
        <!-- Start of Page Title -->
        <div id="page_title">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <h1><?php echo wp_kses_post( $title ); ?></h1>   
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Page Title -->
        <?php
    }
}


if ( ! function_exists( 'codexin_char_limit' ) ) {
    /**
     * Helper Function to limit the character length without breaking any word
     *
     * @param   int       $limit     The number of character to limit
     * @param   string    $type      The type of content (possible values: title/excerpt)
     * @return  mixed
     * @since   v1.0
     */
    function codexin_char_limit( $limit, $type ) {
        $content = ( $type == 'title' && $type !== 'excerpt' ) ? get_the_title() : get_the_excerpt();
        $limit++;

        if ( mb_strlen( $content ) > $limit ) {
            $subex = mb_substr( $content, 0, $limit);
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ( $excut < 0 ) {
                echo mb_substr( $subex, 0, $excut );
            } else {
                return $subex;
            }
            echo '...';
        } else {
            return $content;
        }
    }
}