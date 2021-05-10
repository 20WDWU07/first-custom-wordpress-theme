<?php

get_header(); ?>

<div id="container">
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

<?php

get_footer();

?>

<p>This is the index page template.</p>