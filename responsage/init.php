<?php
/*
Description: Deliver optimized images for different devices
Author: Adrian Ciaschetti
Author URI: http://www.spaceheadconcepts.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define('RESPONSAGE_VERSION', '0.1');
define('RESPONSAGE_PREFIX', 'responsage_');
define('RESPONSAGE_MOBILE_PREFIX', '-m');

/* You can define additional non-default image sizes using $ra_image_size_array
* and define their mobile counter part in $ra_m_image_size_array
* eg:
* $ra_image_size_array = array(
			array(	'size' 		=> 	'widget',
					'width' 	=> 	'400'	,
					'height' 	=> 	'400'	)
			);
*/

/* Define the mobile image sizes using $ra_m_image_size_array.
* Make sure to add additional sizes when adding additional image sizes above.
*/	
		
$ra_m_image_size_array = array(
			array(	'size' 		=> 	'full'	,
					'width' 	=> 	'960'	,
					'height' 	=> 	'960'	),
			
			array(	'size' 		=> 	'large'	,
					'width' 	=> 	'576'	,
					'height' 	=> 	'576'	),
			
			array(	'size' 		=> 	'medium',
					'width' 	=> 	'288'	,
					'height' 	=> 	'288'	)
			);


/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "I don't think you're meant to be here!";
	exit;
}

/* Load the appropriate mobile image sizes*/

if ( function_exists( 'add_theme_support' ) ) {
	if( isset($ra_image_size_array) ) {
		foreach( $ra_image_size_array as $image_array ) {
			add_image_size( RESPONSAGE_PREFIX . $image_array['size'], $image_array['width'], $image_array['height'] );
		}
	}
	
	if( isset($ra_m_image_size_array) ) {
		foreach( $ra_m_image_size_array as $image_array ) {
			add_image_size( RESPONSAGE_PREFIX . $image_array['size'] . RESPONSAGE_MOBILE_PREFIX, $image_array['width'], $image_array['height'] );
		}
	}
	
}


/* Loads the Mobile Detect class */

add_action('init', 'responsage_init' );

function responsage_init() {
	
	if ( !class_exists( 'Mobile_Detect' ) ) {
		require_once dirname( __FILE__ ) . '/mobile_detect.php';
	}
	
	/**
	 * Determining Mobile Device.
	 *
	 * Tablets are ignored as mobile devices due to their higher resolutions
	 * and higher connectivity speeds.
	*/ 
	
	$mdetect = new Mobile_Detect();
	if ( $mdetect -> isMobile() && $mdetect -> isTablet() == false) {
	  	define( 'RESPONSAGE_IS_MOBILE' , true );
	
	} else {
		define( 'RESPONSAGE_IS_MOBILE' , false );
	
	}
	
}


if ( ! function_exists( 'ra_attachment_image_src' ) ) {

	/**
	 * Attachment Image Source.
	 *
	 * Helper function to return the url of the post image according to device.
	 * If a size has not been recognized, it returns 'full'.
	*/ 
	 
	function ra_attachment_image_src( $id, $size = 'full' ) {
		
		if ( ! isset( $id ) ) {
			return "";
		}
		
		if ( ! ra_is_image_size_valid($size) ) {
			$size = 'full';
		}
		
		$image_size_string = RESPONSAGE_PREFIX . $size;
		 
		if ( RESPONSAGE_IS_MOBILE ) {
			$image_size_string .= RESPONSAGE_MOBILE_PREFIX;
		}
		
		$wp_image_src =  wp_get_attachment_image_src( $id, $image_size_string );
		
		return $wp_image_src[0];
	}
	
	/**
	* Helper function to determine if a size is included in the mobile images array
	*/
	
	function ra_is_image_size_valid($size) {
		global $ra_m_image_size_array;
		
		foreach( $ra_m_image_size_array as $image_array ) {
			if(  $image_array[ 'size' ] == strtolower( $size ) ) {
				return true;
			}
		}
		
		return false;
	}
	
}