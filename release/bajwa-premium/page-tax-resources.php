<?php
/**
 * Template Name: Tax Resources
 */
get_header(); the_post(); ?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'Tax resources', 'title' => get_the_title(), 'intro' => bajwa_clean_excerpt( 'Tips, key dates, checklists and articles to help you prepare for tax season with confidence.' ) ) ); ?>
	<?php bajwa_render_designed_body( 'resources' ); ?>
</main>
<?php get_footer(); ?>
