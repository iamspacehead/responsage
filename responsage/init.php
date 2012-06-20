<?php
/*
Description: Deliver optimized images for different devices
Author: Spacehead Concepts
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

define('RESPONSAGE_VERSION', '0.2');
define('RESPONSAGE_PREFIX', 'responsage-');
define('RESPONSAGE_MOBILE_PREFIX', '-m');

/* You can define additional non-default image sizes using $ra_image_size_array
* and define their mobile counter part in $ra_m_image_size_array.
* These are added in using the helper function ra_add_image_size
* in functions.php, or hardcoding into the array.
* eg:
* $ra_image_size_array = array(
				array(	'size' 		=> 	'widget',
					'width' 	=> 	'400'	,
					'height' 	=> 	'400'	,
					'crop'		=> 	false	)
			);
*/

$ra_image_size_array = array();

/* Define the mobile image sizes using $ra_m_image_size_array.
* Make sure to add additional sizes when adding additional image sizes above.
*/	

		
$ra_m_image_size_array = array();



/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "I don't think you're meant to be here!";
	exit;
}

/* Add image sizes to array helper function
 *
 * Adds non-default image sizes to the responsage array
 */

if ( !function_exists('ra_add_image_size') ) {
	
	/* function ra_add_image_size :
	 *
	 * Parameters: 	name (name of new image size)
	 				width (width of desktop image size)
	 				height (height of desktop image size)
	 				mwidth (width of mobile image size)
	 				mheight (height of mobile image size)
	 				crop (crop the image or not)
	 *
	 */ 
	 
	function ra_add_image_size($name, $width = 0, $height = 0, $mwidth = 0, $mheight = 0, $crop = false) {
	
		global $ra_image_size_array, $ra_m_image_size_array;
		
		$ra_image_size_array[] = array( 	'size' 		=> $name,
							'width' 	=> $width,
							'height'	=> $height,
							'crop' 		=> $crop);
											
		$ra_m_image_size_array[] = array( 	'size' 		=> $name,
							'width' 	=> $mwidth,
							'height'	=> $mheight,
							'crop' 		=> $crop);
	
	}
}



/* Update image sizes to array helper function
 *
 * Adds non-default image sizes to the responsage array
 */

if ( !function_exists('ra_update_image_size') ) {
	
	/* function ra_update_image_size :
	 *
	 * Parameters: 	name (name of new image size)
	 				mwidth (width of mobile image size)
	 				mheight (height of mobile image size)
	 				crop (crop the image or not)
	 *
	 */ 
	 
	function ra_update_image_size($name, $mwidth = 0, $mheight = 0, $crop = false) {
	
		global $ra_m_image_size_array;
											
		$ra_m_image_size_array[] = array( 	'size' 		=> $name,
							'width' 	=> $mwidth,
							'height'	=> $mheight,
							'crop' 		=> $crop);
	
	}
}



/* Loads the Mobile Detect class and image sizes */


function responsage_init() {
	
	if ( !class_exists( 'Mobile_Detect' ) ) {
		require_once dirname( __FILE__ ) . '/mobile_detect.php';
	}
	
	
	/* Load the appropriate mobile image sizes*/

	if ( function_exists( 'add_theme_support' ) ) {
		global $ra_image_size_array, $ra_m_image_size_array;
		
		if( isset($ra_image_size_array) ) {
			foreach( $ra_image_size_array as $image_array ) {
				add_image_size( RESPONSAGE_PREFIX . $image_array['size'], $image_array['width'], $image_array['height'], $image_array['crop'] );
			}
		}
		
		if( isset($ra_m_image_size_array) ) {
			foreach( $ra_m_image_size_array as $image_array ) {
				add_image_size( RESPONSAGE_PREFIX . $image_array['size'] . RESPONSAGE_MOBILE_PREFIX, $image_array['width'], $image_array['height'], $image_array['crop'] );
			}
		}
		
	}


	/**
	 * Determining Mobile Device.
	 *
	*/ 
	
	$mdetect = new Mobile_Detect();
	if ( $mdetect -> isMobile()) {
	  	define( 'RESPONSAGE_IS_MOBILE' , true );
	
	} else {
		define( 'RESPONSAGE_IS_MOBILE' , false );
	
	}
	
}

if ( ! function_exists( 'ra_generate_size_string' ) ) {

	/**
	 * Generate Size String.
	 *
	 * Helper function to return the size of the post image according to device.
	 * If a size has not been recognized, it returns 'thumb'.
	*/ 
	 
	function ra_generate_size_string( $size = 'thumbnail' ) {
		
		$size = strtolower( $size );
		
		if ( ! ra_is_image_size_valid($size) ) {
			$size = 'thumbnail';
		}
		
		if ( !RESPONSAGE_IS_MOBILE ) {
			if ( $size != "full" && $size != "large" && $size != "medium" && $size != "thumbnail") {
				$image_size_string = RESPONSAGE_PREFIX . $size;
			} else {
				$image_size_string = $size;		
			}
		} else {
			
			$image_size_string = RESPONSAGE_PREFIX . $size . RESPONSAGE_MOBILE_PREFIX;
		}
		
		return $image_size_string;
	}
	

	/**
	* Helper function to determine if a size is included in the mobile images array
	*/
	
	function ra_is_image_size_valid($size) {
		global $ra_m_image_size_array;
		
		foreach( $ra_m_image_size_array as $image_array ) {
			if(  $image_array[ 'size' ] == $size ) {
				return true;
			}
		}
		
		return false;
	}
	
}

if ( ! function_exists( 'ra_attachment_image_src' ) ) {

	/**
	 * Attachment Image Source.
	 *
	 * Helper function to return the url of the post image according to device.
	 * If a size has not been recognized, it returns 'thumb'.
	*/ 
	 
	function ra_attachment_image_src( $id, $size = 'thumbnail' ) {
		
		if ( ! isset( $id ) ) {
			return "";
		}
				
		$wp_image_src =  wp_get_attachment_image_src( $id, ra_generate_size_string($size) );
		
		return $wp_image_src[0];
	}
		
}


if ( ! function_exists( 'ra_attachment_image_link' ) ) {

	/**
	 * Attachment Image Source.
	 *
	 * Helper function to return the url of the post image according to device.
	 * If a size has not been recognized, it returns 'thumbnail'.
	*/ 
	 
	function ra_attachment_image_link( $id, $size = 'thumbnail' ) {
		
		if ( ! isset( $id ) ) {
			return "";
		}
				
		$wp_image_link =  wp_get_attachment_link( $id, ra_generate_size_string($size) );
		
		return $wp_image_link;
	}
		
}


if ( ! function_exists( 'ra_attachment_image_dimensions' ) ) {

	/**
	 * Attachment Image Dimensions.
	 *
	 * Helper function to return the dimensions of the post image according to device.
	*/ 
	 
	function ra_attachment_image_dimensions( $id, $size = 'thumbnail' ) {
		
		if ( ! isset( $id ) ) {
			return "";
		}
				
		$wp_image_src =  wp_get_attachment_image_src( $id, ra_generate_size_string($size) );
		
		$image_dimensions[] = $wp_image_src[1]; //width
		$image_dimensions[] = $wp_image_src[2]; //height
		
		return $image_dimensions;
	}
		
}