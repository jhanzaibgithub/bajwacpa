<?php
/**
 * Template Name: Tax Tips
 */
get_header(); the_post(); ?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'Tax resources', 'title' => get_the_title(), 'intro' => bajwa_clean_excerpt( 'Practical, year-round tax tips for individuals, employees, business owners and students.' ) ) ); ?>
	<?php bajwa_render_designed_body( 'tips' ); ?>
</main>
<?php get_footer(); ?>
