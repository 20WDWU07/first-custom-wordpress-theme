<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head()?>
</head>
<body <?php body_class(); ?>>
    <div id="site-header">
        <h1 id="site-title"><a href="<?php echo home_url() ?>"><?php bloginfo('name'); ?></a></h1>
       <?php bloginfo('description'); ?></p> 
    </div>
    <div id="full-menu-container">
	    <?php $args = ['theme_location' => 'primary']; ?>
	    <?php wp_nav_menu($args) ?>
	    <div class="container" onclick="myFunction(this)">
		  <div class="bar1"></div>
		  <div class="bar2"></div>
		  <div class="bar3"></div>
		</div>
	</div>

