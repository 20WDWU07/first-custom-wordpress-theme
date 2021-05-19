<?php

get_header(); ?>

<div>
<!-- <img src="<?php bloginfo('stylesheet_directory');?>/images/image.jpg" alt="mars landscape"/> -->
</div>
 <!-- get theme mod gets our wp_customizer setting- the name should be equal to the setting name -->
<div>
 <h1><?php echo get_theme_mod("my_custom_message");?></h1>
</div>
<div>
<img src="<?php echo get_theme_mod('custom_image');?>" alt="custom home page image" class="custom-image"/>
</div>
<div id="container">

<!-- <h1> Welcome to my website, enjoy your stay </h1> -->
<?php

// the wordpress loop

if ( have_posts() ) :
    while ( have_posts() ) : the_post(); 
    
?>

<div class="post-card">
    <h3><a href="<?php the_permalink() ?>"> <?php the_title() ?> </a></h3>
    <?php the_time('F jS, Y'); ?>
    <br>
    <?php the_time(); ?>
    <?php the_excerpt() ?>
</div>
<?php endwhile;

else :
	echo '<p>There are no posts!</p>';
endif; ?>



</div>
<div id="container">
<?php

//  tell wordpress we only want to show the post types with the name 'staff'
query_posts( array(
    'post_type' => 'staff'
));

// the wordpress loop
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); 
    
?>

<div class="post-card-full green-background">
    <?php the_post_thumbnail('thumb', ['class' => 'staff-member-photo']); ?>
    <h3><a href="<?php the_permalink() ?>" class="different-color"> <?php the_title() ?> </a></h3>
    <?php the_excerpt() ?>
</div>

<?php endwhile;

else :
	echo '<p>There are no posts!</p>';
endif; ?>
</div>

<?php
// echo do_shortcode('[contact-form-7 id="34" title="Contact form 1"]');
echo do_shortcode('[calc]');
?>

<?php

get_footer();

?>

<p>This is the front page template.</p>