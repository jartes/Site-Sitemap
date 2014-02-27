<?php
/*
Plugin Name: Site Sitemap
Plugin URI: http://joanartes.com
Description: Add a complete list of all posts in any page by [site-sitemap] shortcode. Atts: 'post_type', 'per_page'. Example: <code>[site-sitemap post_type="post, page" per_page="20"]</code>
Author: Joan Artés
Version: 1.0
Author URI: http://joanartes.com/
*/

/**
 * @todo
 * 	1. Exclude parameters
 * 	2. Documentation (xD)
 * 
 */ 

define( 'SS_VERSION', '1' );

function ss_shortcode( $atts ) {

	extract( shortcode_atts( array(
		'post_type' 	=> 'post',
		'per_page'		=> '10'
	), $atts ) );

	$post_types = explode(',', $post_type);
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$query_args = array(
		'post_type'			=> $post_types,
		'posts_per_page'	=> (int) $per_page,
		'paged'				=> $paged
	);

	$sitemap = wp_cache_get( 'ss_site_sitemap' );

	if ( $sitemap === false ) {
		$sitemap = new WP_Query( $query_args );
	 	wp_cache_set( 'ss_site_sitemap', $sitemap );
	} 
?>
	<ul>
		<?php while ( $sitemap->have_posts() ) : $sitemap->the_post(); ?>	
			<li><a href="<?php the_permalink();?>"><?php the_title(); ?></a></li>
		<?php endwhile; ?> 
	</ul>
	<nav class="site-sitemap-nav">
		<?php previous_posts_link( '&laquo; Previous', $sitemap->max_num_pages ); ?>
        <?php next_posts_link( 'Next &raquo;', $sitemap->max_num_pages) ?>
	</nav>
<?php
	wp_reset_postdata();

}
add_shortcode( 'site-sitemap', 'ss_shortcode' );

function ss_styles() {

	wp_enqueue_style( 'site-sitemap-styles', plugin_dir_url( __FILE__ ) . "/css/site-sitemap.css", array(), SS_VERSION );

}
add_action( 'wp_enqueue_scripts', 'ss_styles' );

?>