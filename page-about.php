<?php
/**
 * Template Name: About Us
 */
get_header();
the_post();
?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'About Bajwa CPA', 'title' => 'About Us', 'intro' => 'We work hard to have a strong understanding of tax law so we can help anyone, from individuals to corporations.', 'class' => 'about-hero' ) ); ?>
	<?php bajwa_render_designed_body( 'about' ); ?>
</main>
<?php get_footer(); ?>
