<?php

// add theme support
add_theme_support('post-thumbnails');
add_theme_support('woocommerce');

// this is our custom function which loads our stylesheet from the root directory
function custom_theme_assets() {
    wp_enqueue_style('tim-custom-style', get_stylesheet_uri());
    wp_enqueue_script('tim-js-file', get_template_directory_uri() . '/js/script.js');
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

// let's set up a custom post type - Books

function create_books_posttype() {
    $args = array (
        'labels' => array(
            // Name of the post type which shows in the CMS backend
            'name' => __('Books'),
            'singular_name' => __('Book')
        ),
        'public' => true,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array ('title', 'editor', 'thumbnail')
    );
    register_post_type('books', $args ); 
}

add_action('init', 'create_books_posttype');

// custom function to change the placeholder
function change_staff_title_placeholder($title) {
    $current_screen = get_current_screen();
        if ($current_screen->post_type == 'staff') {
            $title = 'Full name of staff member';
        }
        return $title;
}

add_filter('enter_title_here', 'change_staff_title_placeholder');

/* Register and display metabox */
add_action( 'add_meta_boxes', 'staff_position_add_metabox');

function staff_position_add_metabox()
{
    add_meta_box('my-meta-box-id', //metabox ID
                 'Job Title', //title seen by the user
                 'staff_position_meta_box_callback', //here's the callback function which runs during this function
                 'staff', //our custom post type which the meta box attaches too
                 'normal' //position of the metabox 
                );
}

function staff_position_meta_box_callback($post)  
{  
    $job_title = get_post_meta( $post->ID, '_job_title', true );
    echo "<input type='text' name='jobtitle' value='" . esc_attr($job_title) . "'>";     
}

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'save_staff_meta_box_data' );

function save_staff_meta_box_data($post_id) {

    // If it's autosaving, bail.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Make sure that the right post data is set.
    if ( ! isset( $_POST['jobtitle'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['jobtitle'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_job_title', $my_data );
}

// ---------------start creating a metabox for our books------------------------------

/* Register and display metabox */
add_action( 'add_meta_boxes', 'book_author_add_metabox');

function book_author_add_metabox()
{
    add_meta_box('book-author-id', //metabox ID
                 'Book Author', //title seen by the user
                 'book_author_meta_box_callback', //here's the callback function which runs during this function
                 'books', //our custom post type which the meta box attaches too
                 'normal' //position of the metabox 
                );
}

function book_author_meta_box_callback($post)  
{  
    $book_author = get_post_meta( $post->ID, '_bookauthor', true );
    echo "<p>Enter the full name</p>";
    echo "<input type='text' name='bookauthor' value='" . esc_attr($book_author) . "'>";     
}

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'save_books_meta_box_data' );

function save_books_meta_box_data($post_id) {

    // If it's autosaving, bail.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Make sure that the right post data is set.
    if ( ! isset( $_POST['bookauthor'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['bookauthor'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_bookauthor', $my_data );
}

// ---------------create a new metabox which contains multiple fields------------------------------

function create_metabox_mutiple_fields(){
    add_meta_box(
        'id-of-metabox',
        'Metabox With Multiple Fields',
        'add_multiple_fields_content',
        'books'
    );
}

function add_multiple_fields_content() {
    global $post;

    // Get Value of Fields From Database
    $radio_field = get_post_meta($post->ID, 'custom_meta_radio_field', true);
    echo $radio_field;
    ?>
    <div class="my-row">
        <div class="custom-label">Radio Fields</div>
        <div class="fields">
         <label><input type="radio" name="custom_meta_radio_field" value="Option 1" <?php if($radio_field == "Option 1") echo 'checked';?>>Option 1</label>
         <label><input type="radio" name="custom_meta_radio_field" value="Option 2" <?php if($radio_field == "Option 2") echo 'checked';?>>Option 2</label>
         <label><input type="radio" name="custom_meta_radio_field" value="Option 3" <?php if($radio_field == "Option 3") echo 'checked';?>>Option 3</label>
        </div>
    </div>
    <?php
}

/* Register and display metabox */
add_action( 'add_meta_boxes', 'create_metabox_mutiple_fields');

// save function which will save our custom multiple fields metabox

function save_multiple_fields_metabox($post_id, $post) {
     
    // check current use permissions
     $post_type = get_post_type_object( $post->post_type );

     if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
        return $post_id;
    }
    // Do not save the data if autosave
       if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // define your own post type here
        if( $post->post_type != 'books' ) {
        return $post_id;
    }
    if(isset($_POST["custom_meta_radio_field"])) :
    update_post_meta($post->ID,'custom_meta_radio_field', $_POST["custom_meta_radio_field"]);
    endif;
}

add_action('save_post', 'save_multiple_fields_metabox', 200, 2); 

// hook

add_action('init', 'create_qualifications_staff_taxomony', 0);

// let's create a custom taxonomy

function create_qualifications_staff_taxomony() {
    $labels = array(
        'name' => _x('Qualifications', 'general name'),
        'singular_name' => _x('Qualification', 'singular name'),
        'search_items' => __('Search Qualifications'),
        'all_items' => __('All Qualifications'),
        'parent_item' => __('Parent Qualification'),
        'parent_item_colon' => __('Parent Qualification:'),
        'edit_item' => __('Edit Qualification'),
        'update_item' => __('Update Qualification'),
        'add_new_item' => __('Add New Qualification'),
        'new_item_name' => __('New Qualification Name'),
        'menu_name' => __('Qualification')
    );
    // register the taxonomy in wordpress and plug in the data from our labels array
    register_taxonomy('Qualifications', array('staff'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true
        )
    );
}

// Removing fields and customising the Woocommerce cart

add_filter('woocommerce_checkout_fields','custom_override_checkout_fields');
function custom_override_checkout_fields($fields) {
    unset($fields['order']['order_comments']);
    return $fields; 
}

// make the phone number optional
add_filter( 'woocommerce_billing_fields', 'custom_change_fields' );
function custom_change_fields($address_fields){
    $address_fields['billing_phone']['required'] = false;
    unset($address_fields['billing_postcode']);
    return $address_fields; 
}


?>