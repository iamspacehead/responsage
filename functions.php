<?php

/* 
 * Loads Responsage
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
 
if ( !function_exists( 'responsage_init' ) ) {

	define( 'RESPONSAGE_DIRECTORY', get_template_directory() . '/responsage/' );

	require_once (RESPONSAGE_DIRECTORY . 'init.php');

}