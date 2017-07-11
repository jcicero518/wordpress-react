<?php

$GLOBALS['env'] = isset($_GET['dev']) ? 'dev' : 'production';

if (file_exists(dirname(__FILE__) . '/build/' . $env . '/asset-manifest.json')) {
    $GLOBALS['asset_manifest'] = json_decode(file_get_contents(dirname(__FILE__) . '/build/' . $env . '/asset-manifest.json'), true);
}

if ( ! function_exists( 'wpReact_setup' ) ) {

	function wpReact_setup() {

		add_theme_support( 'title-tag' );

		add_theme_support( 'menus' );

		register_nav_menus( array(
			'top' => __( 'Top Menu', 'react-theme-menu' )
		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
			'chat',
		) );

	}

}
add_action( 'after_setup_theme', 'wpReact_setup' );

function disable_wp_emojicons() {
    // all actions related to emojis
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    // filter to remove TinyMCE emojis
    add_filter( 'emoji_svg_url', '__return_false' );
    // add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

function get_wp_installation(){
    $full_path = getcwd();
    $ar = explode("wp-", $full_path);
    return $ar[0];
}

add_action( 'wp_ajax_nopriv_react_get_page', 'react_get_page' );
add_action( 'wp_ajax_react_get_page', 'react_get_page' );
function react_get_page() {

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

        $html = '';
        $wpFooter = '';

        if( isset($_POST['page_id']) && $_POST['page_id'] != 'false' ) {
            $post_id = $_POST['page_id'];
        }

        if( isset($_POST['uri']) && $_POST['uri'] != 'false' ) {
            $post_id = url_to_postid($_POST['uri']);
        }

        $page_template = get_post_meta($post_id, '_wp_page_template', true);
        $post_type = get_post_type($post_id);

        if($post_type == 'page'){
            if($page_template == 'default'){
                ob_start();
                global $post;
                $post = get_post($post_id);
                setup_postdata($post);

                $content = $post->post_content;
                $content = apply_filters('the_content', $content);
                $html = str_replace(']]>', ']]&gt;', $content);

                wp_footer();
                $wpFooter = ob_get_clean();

                wp_reset_postdata($post);
                $buffer = ob_get_length();
                if($buffer){
                    ob_end_flush();
                }
            } else {

                ob_start();
                $via_ajax = true;
                include $page_template;
                $html = ob_get_clean();
                $buffer = ob_get_length();
                if($buffer){
                    ob_end_flush();
                }

                ob_start();
                global $post;
                $post = get_post($post_id);
                setup_postdata($post);

                wp_footer();
                $wpFooter = ob_get_clean();

                wp_reset_postdata($post);
                $buffer = ob_get_length();
                if($buffer){
                    ob_end_flush();
                }
            }
        }

        if($post_type == 'post'){

            ob_start();
            global $post;
            $post = get_post($post_id);
            setup_postdata($post);

            $content = $post->post_content;
            $content = apply_filters('the_content', $content);
            $html = str_replace(']]>', ']]&gt;', $content);

            wp_footer();
            $wpFooter = ob_get_clean();

            wp_reset_postdata($post);
            $buffer = ob_get_length();
            if($buffer){
                ob_end_flush();
            }
        }

        $page_array = [
            $post_id => [
                'page_id' => (int)$post_id,
                'page_uri' => get_page_uri($post_id),
                'html' => $html,
                'wp_footer' => $wpFooter,
                'server_request_time' => strtotime(date('Y-m-d G:i:s')),
                'page_template' => $page_template,
                'post_type' => $post_type
            ],
            'last_page_id' => (int)$post_id
        ];

        echo json_encode($page_array);

    }

    die();

}

add_action( 'wp_ajax_nopriv_react_get_post_not_in_menu', 'react_get_post_not_in_menu' );
add_action( 'wp_ajax_react_get_post_not_in_menu', 'react_get_post_not_in_menu' );
function react_get_post_not_in_menu() {

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

        if( isset($_POST['uri']) && $_POST['uri'] != 'false' ) {
            $uri = basename(untrailingslashit($_POST['uri']));


            $args = array(
              'name' => $uri,
              // 'post_type' => 'post',
              // 'post_status' => 'publish',
              'numberposts' => 1
            );

            $my_post = get_posts($args);

            if( $my_post ) {
                $post_id = $my_post[0]->ID;

                $my_post[0]->object_id = (string)$post_id;
                $my_post[0]->url = get_page_link($post_id);
                $my_post[0]->menu_item_parent = '0';
                $my_post[0]->not_in_menu = true;
                echo json_encode($my_post[0]);
            } else {
                $page_by_path = get_page_by_path($uri);
                $post_id = $page_by_path->ID;

                $page_by_path->object_id = (string)$post_id;
                $page_by_path->url = get_page_link($post_id);
                $page_by_path->menu_item_parent = '0';
                $page_by_path->not_in_menu = true;
                echo json_encode($page_by_path);
            }

        }

    }

    die();

}
