<?php
/**
 * Designed Tax Resources hub (seeded into the Tax Resources page editor).
 * Expects $theme_uri, $contact and $resource_urls (tips, deadlines, checklists, blog).
 */
if ( ! defined( 'ABSPATH' ) && ! defined( 'BAJWA_STANDALONE' ) ) { exit; }
$bajwa_resources = array(
	array( 'book', 'Tax Tips', '28 practical tips for individuals, employees, business owners and students.', $resource_urls['tips'], 'View tax tips', $theme_uri . '/assets/documents/tax-tips.pdf' ),
	array( 'calendar', 'Tax Filing Deadlines', 'Key filing and payment dates, with the penalties that apply when they are missed.', $resource_urls['deadlines'], 'View deadlines', $theme_uri . '/assets/documents/tax-filing-deadlines.pdf' ),
	array( 'folder', 'Tax Checklists', 'Downloadable checklists showing exactly which documents to gather before filing.', $resource_urls['checklists'], 'Browse checklists', '' ),
	array( 'file', 'Articles & Insights', 'Plain-language articles on Canadian tax, accounting and business decisions.', $resource_urls['blog'], 'Read articles', '' ),
);
?>
<section class="section res-intro res-hub-intro"><div class="container">
	<div class="res-heading">
		<p class="eyebrow">Make your tax preparation successful</p>
		<h2>Everything you need to prepare, in one place.</h2>
		<p>Use these resources to help you be prepared with your taxes: tips, key dates, document checklists and articles from our team.</p>
	</div>
	<div class="res-hub-grid">
		<?php foreach ( $bajwa_resources as $bajwa_i => $bajwa_res ) : ?>
		<article class="res-hub-card">
			<div class="res-hub-card__top"><span class="res-group__icon"><?php echo bajwa_section_icon( $bajwa_res[0] ); ?></span><span class="res-hub-card__num"><?php echo str_pad( (string) ( $bajwa_i + 1 ), 2, '0', STR_PAD_LEFT ); ?></span></div>
			<h3><a href="<?php echo bajwa_sections_e( $bajwa_res[3] ); ?>"><?php echo bajwa_sections_e( $bajwa_res[1] ); ?></a></h3>
			<p><?php echo bajwa_sections_e( $bajwa_res[2] ); ?></p>
			<div class="res-hub-card__actions">
				<a class="res-hub-card__link" href="<?php echo bajwa_sections_e( $bajwa_res[3] ); ?>"><?php echo bajwa_sections_e( $bajwa_res[4] ); ?> <?php echo bajwa_section_icon( 'arrow' ); ?></a>
				<?php if ( $bajwa_res[5] ) : ?><a class="res-hub-card__pdf" href="<?php echo bajwa_sections_e( $bajwa_res[5] ); ?>" target="_blank" rel="noopener"><?php echo bajwa_section_icon( 'download' ); ?> PDF</a><?php endif; ?>
			</div>
		</article>
		<?php endforeach; ?>
	</div>
</div></section>

<section class="section related-hub res-hub-articles"><div class="container">
	<div class="related-block__heading"><div><p class="eyebrow">Articles to assist with your taxes</p><h2>Latest from our blog</h2></div><a class="related-block__all" href="<?php echo bajwa_sections_e( $resource_urls['blog'] ); ?>">All articles <?php echo bajwa_section_icon( 'arrow' ); ?></a></div>
	[bajwa_latest_posts count="3"]
</div></section>

<section class="section res-note"><div class="container res-note__inner">
	<span class="res-note__icon"><?php echo bajwa_section_icon( 'chat' ); ?></span>
	<div><p class="eyebrow">Get started today</p><h2>Have a question these resources don’t answer?</h2><p>Every tax situation is different. Our CPAs can tell you what applies to you and what to do next.</p></div>
	<a class="button button--gold" href="<?php echo bajwa_sections_e( $contact ); ?>">Work with us <?php echo bajwa_section_icon( 'arrow' ); ?></a>
</div></section>
