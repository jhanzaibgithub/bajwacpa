<?php
/**
 * Template Name: Contact
 */
get_header();
the_post();
?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'We are ready to listen', 'title' => "Let's talk about what comes next.", 'intro' => 'Tell us what you are working through. We will help you identify a clear, practical next step.', 'class' => 'contact-hero' ) ); ?>
	<?php bajwa_render_designed_body( 'contact' ); ?>
</main>
<?php get_footer(); ?>
