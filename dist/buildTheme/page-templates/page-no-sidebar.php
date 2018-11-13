<?php
/**
 * Template Name: Page - No Sidebar
 *
 * @package Codexin
 * @subpackage Template
 */

// Do not allow directly accessing this file.
defined( 'ABSPATH' ) OR die( esc_html__( 'This script cannot be accessed directly.', 'TEXT_DOMAIN' ) );

get_header(); ?>

<!-- Start of Main Content Wrapper -->
<div id="content" class="main-content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12">
				<main id="primary" class="site-main">
					<?php 
					if ( have_posts() ) { 

						// Start the loop
						while ( have_posts() ) {
							the_post();

							// Load the page content template
							get_template_part( 'template-parts/page/content', 'page' );
						}
					} else { 
						// No posts to display
					}
					?>
				</main> <!-- end of #primary -->

				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
	</div> <!-- end of container -->
</div>
<!-- End of Main Content Wrapper -->

<?php get_footer(); ?>