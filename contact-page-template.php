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

<div class="post-card-full">
    <h3><a href="<?php the_permalink() ?>"> <?php the_title() ?> </a></h3>
    <?php the_content() ?>
    <form action="/">
    <label for="fname">First name:</label><br>
    <input type="text" id="fname" name="fname" value="John"><br>
    <label for="lname">Last name:</label><br>
    <input type="text" id="lname" name="lname" value="Doe"><br><br>
    <input type="submit" value="Submit">
</form> 
</div>

<?php endwhile;

else :
	echo '<p>There are no posts!</p>';
endif; ?>

</div>

<?php

get_footer();

?>

<p>This is a custom contact page template.</p>