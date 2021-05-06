<?php

// add theme support
add_theme_support('post-thumbnails');

// this is our custom function which loads our stylesheet from the root directory
function custom_theme_assets() {
    wp_enqueue_style('tim-custom-style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'custom_theme_assets');

// register our custom navigation menu in the backend
register_nav_menus( [ 'primary' => __( 'Primary Menu' )]);

// this function will set the excerpt length
function customize_the_excerpt_length() {
    // return 10 characters
    return 10;
}

// a filter hook to modify the default Wordpress excerpt length
add_filter('excerpt_length', 'customize_the_excerpt_length');

// let's set up a custom post type - Staff members

function create_staff_posttype() {
    $args = array (
        'labels' => array(
            // Name of the post type which shows in the CMS backend
            'name' => __('Staff'),
            'singular_name' => __('Staff')
        ),
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array ('title', 'editor', 'thumbnail')
    );
    register_post_type('staff', $args ); 
}

add_action('init', 'create_staff_posttype');

// custom function to change the placeholder
function change_staff_title_placeholder($title) {
    $current_screen = get_current_screen();
        if ($current_screen->post_type == 'staff') {
            $title = 'Full name of staff member';
        }
        return $title;
}

add_filter('enter_title_here', 'change_staff_title_placeholder');

add_action( 'admin_menu', 'staff_add_metabox' );


?>