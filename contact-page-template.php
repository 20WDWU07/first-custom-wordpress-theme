<?php

/** Template Name: Contact page template */

?>

<?php

get_header(); ?>

<div id="container">

<?php

// the wordpress loop

if ( have_posts() ) :
    while ( have_posts() ) : the_post(); 
    
?>

<?php the_content() ?>

<?php endwhile;

else :
	echo '<p>There are no posts!</p>';
endif; ?>

</div>

<?php

get_footer();

?>

<p>This is a custom contact page template.</p>