<?php
/**
 * Template Name: Tax Checklists
 */
get_header();
the_post();
// The checklist search and filter script lives in the view; the sections come from the page editor.
preg_match( '/<script>.*?<\/script>/s', bajwa_designed_source_html( 'checklists' ), $bajwa_checklist_script );
?>
<main id="main">
	<?php bajwa_page_header( array( 'eyebrow' => 'Official Client Resource Hub', 'title' => 'Tax Checklists & Filing Schedules', 'intro' => 'A clear starting point for gathering the records behind an accurate, efficient filing.', 'class' => 'checklist-hero' ) ); ?>
	<?php bajwa_render_designed_body( 'checklists' ); ?>
</main>
<?php echo $bajwa_checklist_script[0] ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme script. ?>
<?php get_footer(); ?>
