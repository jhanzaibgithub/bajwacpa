<?php
/**
 * Template Name: Tax Filing Deadlines
 */
get_header(); the_post(); ?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'Tax resources', 'title' => get_the_title(), 'intro' => bajwa_clean_excerpt( 'Key Canadian filing and payment dates, with the penalties that apply when they are missed.' ) ) ); ?>
	<?php bajwa_render_designed_body( 'deadlines' ); ?>
</main>
<?php get_footer(); ?>
