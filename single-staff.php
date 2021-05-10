<?php

get_header(); ?>

<div id="container">

<?php

// the wordpress loop
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); 
    
?>

<div class="post-card-full green-background">
    <?php the_post_thumbnail('large', ['class' => 'staff-member-photo']); ?>
    <h3><a href="<?php the_permalink() ?>" class="different-color"> <?php the_title() ?> </a></h3>
    <?php 
    $job_position = get_post_meta( get_the_ID(), '_job_title', true ); 
    if ( ! empty($job_position)){
        echo '<h3> Job position: ' . $job_position . '<h3>';
    }  
    ?>
    <!-- show the taxonomy for this custom post type -->
    <?php
    $post_tags = get_the_terms(get_the_ID(), 'Qualifications');
        if ($post_tags) {
            foreach($post_tags as $tag) {
                echo $tag->name . " ";
            }
        }
    ?>
    <?php the_content() ?>
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