<?php

get_header(); ?>

<div id="container">

<?php

// the wordpress loop
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); 
    
?>

<div class="post-card-full green-background">
    <h3><a href="<?php the_permalink() ?>" class="different-color"> <?php the_title() ?> </a></h3>
    <?php
        echo "<p>Below is the Value of Textarea Field</p>";
        echo get_post_meta($post->ID, 'custom_meta_textarea_field', true); 
        echo "<p>Below is the Value of Dropdown Field</p>";
        echo get_post_meta($post->ID, 'custom_meta_select_field', true);   
        echo "<p>Below is the Value of Radio Field</p>";
        echo get_post_meta($post->ID, 'custom_meta_radio_field', true);   
        echo "<p>Here's the checkbox data, we need to use a loop</p>";  
        $checkbox_data = get_post_meta($post->ID, 'custom_meta_checkbox_field', true); 
        // foreach ($checkbox_data as $single_checkbox_data) {
        //     echo $single_checkbox_data . " ";
        // }
        // Use implode function to join
        // comma in the array
        $formatted_checkbox_array = implode(' + ', $checkbox_data);
        print_r($formatted_checkbox_array);
    ?>
    <?php 
    echo "<p>Below is the content</p>";
    the_content() ?>
</div>

<?php endwhile;

else :
	echo '<p>There are no posts!</p>';
endif; ?>

</div>

<?php

get_footer();

?>

<p>This is an individual staff page template.</p>