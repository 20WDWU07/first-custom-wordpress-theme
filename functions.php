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

// this is our custom function which loads our metabox stylesheet from the root directory
function custom_metabox_assets() {
    wp_enqueue_style('metabox-stylesheet', get_template_directory_uri() . '/css/metabox-styles.css');
}

// generate special css
function generate_special_css(){
    $color_picker = get_theme_mod('color_picker');
    $custom_image = get_theme_mod('custom_image');
    ?>
        <style type="text/css" id="custom-style-from-customiser">
            .special-color {
                color:<?php echo $color_picker; ?>;
            }
            /* ------ enable dark mode */
            <?php
            if (get_theme_mod("my_custom_select") == "enabled") {
                // whatever comes in here gets run if dark mode is enabled
                ?>
                body {
                background:black;
                }
                a, p, h1, h2, h3, h4, div, span {
                color:white;
                }
                <?php
            }
            ?>
            /* custom column numbers, as chosen in the customizer */
            <?php
            if (get_theme_mod("select_column_count") == "3cols"){
                ?>
                .post-card {
                    flex: 0 29%;
                }
                <?php
            }
            ?>
        </style>
    <?php
}
add_action('wp_head', 'generate_special_css');

add_action('admin_enqueue_scripts', 'custom_metabox_assets');

// register our custom navigation menu in the backend
register_nav_menus( [ 'primary' => __( 'Primary Menu' )]);

// this function will set the excerpt length
function customize_the_excerpt_length() {
    // return 10 characters
    return get_theme_mod('my_custom_number');
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
    $checkbox_field = get_post_meta($post->ID, 'custom_meta_checkbox_field', true);
    $dropdown_field = get_post_meta($post->ID, 'custom_meta_select_field', true);
    $textarea_field = get_post_meta($post->ID, 'custom_meta_textarea_field', true);

    ?>
    <div class="my-row">
        <div class="custom-label">Radio Fields</div>
        <div class="fields">
         <label><input type="radio" name="custom_meta_radio_field" value="Option 1" <?php if($radio_field == "Option 1") echo 'checked';?>>Option 1</label>
         <label><input type="radio" name="custom_meta_radio_field" value="Option 2" <?php if($radio_field == "Option 2") echo 'checked';?>>Option 2</label>
         <label><input type="radio" name="custom_meta_radio_field" value="Option 3" <?php if($radio_field == "Option 3") echo 'checked';?>>Option 3</label>
        </div>
    </div>
    <div class="my-row" style="margin-bottom: 10px;">
        <div class="custom-label" style="margin-bottom: 10px;">Checkbox Fields</div>
        <div class="fields">
         <label><input type="checkbox" name="custom_meta_checkbox_field[]" value="Checkbox Option 1" <?php if(in_array("Checkbox Option 1", $checkbox_field)) echo 'checked';?>/>Checkbox Option 1</label>
         <label><input type="checkbox" name="custom_meta_checkbox_field[]" value="Checkbox Option 2" <?php if(in_array("Checkbox Option 2", $checkbox_field)) echo 'checked';?>/>Checkbox Option 2</label>
         <label><input type="checkbox" name="custom_meta_checkbox_field[]" value="Checkbox Option 3" <?php if(in_array("Checkbox Option 3", $checkbox_field)) echo 'checked';?>/>Checkbox Option 3</label>
        </div>
    </div>
    <div class="my-row" style="margin-bottom: 10px;">
        <div class="custom-label"  style="margin-bottom: 10px;">Select Dropdown</div>
        <div class="fields">
        <select name="custom_meta_select_field">
            <option value="">Select Option</option>
            <option value="Select Option 1" <?php if($dropdown_field == "Select Option 1") echo 'selected';?>>Select Option 1</option>
            <option value="Select Option 2" <?php if($dropdown_field == "Select Option 2") echo 'selected';?>>Select Option 2</option>
            <option value="Select Option 3" <?php if($dropdown_field == "Select Option 3") echo 'selected';?>>Select Option 3</option>
        </select>
        </div>
    </div>
    <div class="my-row" style="margin-bottom: 10px;">
        <div class="custom-label"  style="margin-bottom: 10px;">Textarea</div>
        <div class="fields">
        <textarea rows="5" name="custom_meta_textarea_field"><?php echo $textarea_field; ?></textarea>
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

    // save the radio input data
    if(isset($_POST["custom_meta_radio_field"])) :
        update_post_meta($post->ID,'custom_meta_radio_field', $_POST["custom_meta_radio_field"]);
    endif;

    // save the checkbox input data
    if(isset($_POST["custom_meta_checkbox_field"])) :
        update_post_meta($post->ID,'custom_meta_checkbox_field', $_POST["custom_meta_checkbox_field"]);
    endif;

    // save the dropdown input data
    if(isset($_POST["custom_meta_select_field"])) :
        update_post_meta($post->ID,'custom_meta_select_field', $_POST["custom_meta_select_field"]);
    endif;


    // Sanitize user input.
    $textarea_data_clean = sanitize_text_field( $_POST['custom_meta_textarea_field'] );

    // save the textarea input data
    if(isset($_POST["custom_meta_textarea_field"])) :
        update_post_meta($post->ID,'custom_meta_textarea_field', $textarea_data_clean);
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

// ----------------------register a custom section in the WP customizer

function mytheme_customize_register($wp_customize) {
    $wp_customize->add_section("my_custom_section", array(
        "title" => __("My custom settings", "customizer_custom_section"),
        "priority" => 20,
    ));
    // the first argument of add_setting function is the name of the control
    $wp_customize->add_setting("my_custom_message", array(
        "default" => "",
        "transport" => "refresh"
    ));
    // ------------settings
    $wp_customize->add_setting("color_picker", array(
        "default" => "#666666",
        "transport" => "refresh"
    ));
    $wp_customize->add_setting("custom_image", array(
        "default" => "",
        "transport" => "refresh"
    ));
    $wp_customize->add_setting("my_custom_number", array(
        "default" => "",
        "transport" => "refresh",
    ));
    $wp_customize->add_setting("my_custom_select", array(
        "default" => "",
        "transport" => "refresh",
    ));
    $wp_customize->add_setting("select_column_count", array(
        "default" => "",
        "transport" => "refresh",
    ));
    // --------------controls
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, "my_custom_message", array(
        "label" => __("Enter a custom message here", "customizer_control_label"),
        "section" => "my_custom_section",
        "setting" => "my_custom_message",
        "type" => "textarea"
        )
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "color_picker", array(
        'label' => 'Hyperlink colors',
        'section' => 'my_custom_section',
        'settings' => 'color_picker'
        )
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, "custom_image", array(
        'label' => 'Edit My Image',
        'settings' => 'custom_image',
        'section'   => 'my_custom_section'

        )
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize,"my_custom_number",
   array(
       "label" => __("Enter Custom number", "customizer_control_label"),
       "section" => "my_custom_section",
       "settings" => "my_custom_number",
       "type" => "number",
        )
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize,"my_custom_select",
    array(
        "label" => __("Dark mode theme", "customizer_select_label"),
        "section" => "my_custom_section",
        "settings" => "my_custom_select",
        "type" => "select",
        "choices" => array(
            'default' => 'Default - off',
            'enabled' => 'Enabled'
        )
         )
     ));
     $wp_customize->add_control(new WP_Customize_Control($wp_customize,"select_column_count",
     array(
         "label" => __("Select the amount of columns for posts", "customizer_select_label2"),
         "section" => "my_custom_section",
         "settings" => "select_column_count",
         "type" => "select",
         "choices" => array(
             'default' => '4 Columns default',
             '3cols' => '3 Columns',
             '2cols' => '2 Columns',
             '1col' => '1 Columns'
         )
          )
      ));
}

add_action("customize_register", "mytheme_customize_register");

?>