<?php
/**
 * The main template file.
 *
 * This theme is purely for the purpose of testing theme options in Options Framework plugin.
 *
 * @package WordPress
 * @subpackage Options Check
 */

get_header(); ?>

	<article>
	
		<header class="entry-header">
			<p>Responsive + Images = </p>
			<h1>Responsage</h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
		
			<h3>About</h3>
			
			<p>This is a small WordPress theme plugin that allows you to make your images responsive. It detects the user-agent string of the browser (Thanks to <a href="http://code.google.com/p/php-mobile-detect/">Mobile Detect</a>) and returns the url of the resized image.</p>
			
			<h3>How to Include in Your Own Project</h3>
			
			<p>Just drag the responsage folder of this theme and functions.php into the theme of your choice.</p>
			<p>To use Responsage, call the function ra_attachment_image_src in your template files with the image ID and size to produce the image URL.</p>
			
			<hr>

            <h3>Example Code</h3>
            
            <pre>
&lt;?php query_posts( array('post_type' => 'post', 'posts_per_page' => 5) );
if (have_posts()) : while (have_posts()) : the_post();
	$image_url = ra_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
?>

&lt;div class="post">
	&lt;a href="&lt;?php the_permalink(); ?>">
		&lt;?php if (has_post_thumbnail()) : ?>
			&lt;img src="&lt;?php echo $image_url; ?>" />
		&lt;?php endif; ?>
	&lt;/a>
&lt;/div>  

&lt;?php endwhile; endif; ?>
</pre>
        
            
            <h3>Adding Sizes</h3>
			<p>Simply add another array with the relevant data to add an image size to your theme.</p>
			<pre>
 //Called in the functions.php file	
 	ra_add_image_size("widget", 400, 400, 250, 250, false);
  	ra_add_image_size("post", 1200, 900, 640, 480, false);
  	ra_add_image_size("gallery-portfolio", 300, 300, 300, 300, true);

//Update the default WordPress sizes to include mobile equivalents
	ra_update_image_size("full", 960,960,false);
    	ra_update_image_size("large", 576,576,false);
   	ra_update_image_size("medium", 288,288,false);
    	ra_update_image_size("thumbnail", 100,100,true);
			
			</pre>
		</div><!-- .entry-content -->
	</article><!-- #post-0 -->

<?php get_footer(); ?>