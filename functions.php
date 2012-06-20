<?php

/* 
 * Loads Responsage
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
 

if ( !function_exists( 'responsage_init' ) ) {

	define( 'RESPONSAGE_DIRECTORY', get_template_directory() . '/functions/responsage/' );

	require_once (RESPONSAGE_DIRECTORY . 'init.php');

}

add_action('init', 'responsage_init' );

if ( function_exists( 'ra_add_image_size' ) ) {
	/* function ra_add_image_size :
	 *
	 * Parameters: 	name (name of new image size)
	 				width (width of desktop image size)
	 				height (height of desktop image size)
	 				mwidth (width of mobile image size)
	 				mheight (height of mobile image size)
	 				crop (crop the image or not)
	 *
	 * found in functions/responsage/init.php
	 */ 
	 
	add_theme_support( 'post-thumbnails' ); //Enable theme-wide post thumbnail support
    	set_post_thumbnail_size( 200, 200, true); //Set default thumbnail size
    	add_image_size("slide-thumb", 32, 32, true); //Regular WordPress function, does not use responsage
    
    	ra_update_image_size("full", 960,960,false); //Adds mobile sizes for standard WordPress image sizes
    	ra_update_image_size("large", 576,576,false);
    	ra_update_image_size("medium", 288,288,false);
    	ra_update_image_size("thumbnail", 100,100,true);
   
    	ra_add_image_size("widget", 400, 400, 250, 250, false); //Add new theme image size with desktop and mobile sizes
  	ra_add_image_size("post", 1200, 900, 640, 480, false);
  	ra_add_image_size("gallery-portfolio", 300, 300, 300, 300, true);

}

