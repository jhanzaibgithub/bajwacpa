<?php
/**
 * Designed Tax Filing Deadlines page sections (seeded into the page editor).
 * Content from the Bajwa CPA "Tax Filing Deadlines" PDF. Expects $theme_uri and $contact.
 */
if ( ! defined( 'ABSPATH' ) && ! defined( 'BAJWA_STANDALONE' ) ) { exit; }
$bajwa_key_dates = array(
	array( '15th', 'Every month', 'Payroll remittances', 'Monthly source-deduction remittances are due by the 15th of every month.' ),
	array( 'Feb 28', 'Every year', 'T4 payroll summary', 'File your T4 slips and summary by February 28.' ),
	array( 'Apr 30', 'Every year', 'Personal returns & balances owing', 'Personal returns are due, and every individual balance owing is payable, by April 30.' ),
	array( 'Jun 15', 'Every year', 'Sole proprietors & partnerships', 'Returns for sole proprietorships, partnerships and limited partnerships are due by June 15.' ),
	array( '6 mo.', 'After year end', 'Corporate tax returns', 'Corporate returns are due six months after the corporation’s year end.' ),
);
$bajwa_standard_penalty = array(
	array( 'Late filing penalty', '5% of the balance owing, plus 1% per month' ),
	array( 'Repeated failure to file', '10% of the balance owing, plus 2% per month' ),
	array( 'Interest', 'Charged at the prescribed rate from the day payment was due until the CRA receives it' ),
);
$bajwa_deadline_cards = array(
	array( 'person', 'Personal returns', 'Individuals', array( array( 'Filing deadline', 'On or before April 30 of every year' ), array( 'Balance owing due', 'On or before April 30 of every year' ) ) ),
	array( 'briefcase', 'Business returns', 'Sole proprietorships, partnerships & limited partnerships', array( array( 'Filing deadline', 'On or before June 15 of every year' ), array( 'Balance owing due', 'On or before April 30 of every year' ) ) ),
	array( 'building', 'Corporate returns', 'Corporations (T2)', array( array( 'Filing deadline', '6 months after year end' ), array( 'Balance owing due', '3 months after year end for a CCPC; 2 months for other corporations' ) ) ),
	array( 'people', 'Payroll', 'Employers', array( array( 'T4 summary', 'Filed by February 28 of every year' ), array( 'Remittances', 'Due on the 15th of every month' ), array( 'Late filing & payment', 'Penalties apply to outstanding balances owing' ) ) ),
);
?>
<section class="section res-intro"><div class="container res-intro__grid">
	<div class="res-intro__copy">
		<p class="eyebrow">Make your tax preparation successful</p>
		<h2>Important Canadian tax dates to remember.</h2>
		<p class="lead">Filing and paying on time avoids penalties and interest. Here are the key deadlines for personal, business, corporate and payroll filings.</p>
		<div class="res-chips" role="group" aria-label="Jump to a section on this page">
			<button type="button" data-scroll-target="key-dates"><?php echo bajwa_section_icon( 'calendar' ); ?><span>Key dates</span></button>
			<button type="button" data-scroll-target="deadline-details"><?php echo bajwa_section_icon( 'file' ); ?><span>By return type</span></button>
			<button type="button" data-scroll-target="penalties"><?php echo bajwa_section_icon( 'percent' ); ?><span>Penalties &amp; interest</span></button>
		</div>
	</div>
	<aside class="res-download">
		<div class="res-download__doc" aria-hidden="true"><span>PDF</span><strong>Filing<br>Deadlines</strong><i></i><i></i><i></i></div>
		<div class="res-download__body">
			<p class="eyebrow">Free download</p>
			<h3>Tax Filing Deadlines</h3>
			<p>A one-page reference of the dates and penalties below.</p>
			<small>PDF · 1 page · 210 KB</small>
			<a class="button button--gold" href="<?php echo bajwa_sections_e( $theme_uri . '/assets/documents/tax-filing-deadlines.pdf' ); ?>" target="_blank" rel="noopener">Download PDF <?php echo bajwa_section_icon( 'download' ); ?></a>
		</div>
	</aside>
</div></section>

<section class="section res-dates" id="key-dates"><div class="container">
	<div class="res-heading"><p class="eyebrow">At a glance</p><h2>Key dates through the year</h2></div>
	<ol class="res-timeline">
		<?php foreach ( $bajwa_key_dates as $bajwa_date ) : ?>
		<li class="res-date"><div class="res-date__when"><strong><?php echo bajwa_sections_e( $bajwa_date[0] ); ?></strong><small><?php echo bajwa_sections_e( $bajwa_date[1] ); ?></small></div><h3><?php echo bajwa_sections_e( $bajwa_date[2] ); ?></h3><p><?php echo bajwa_sections_e( $bajwa_date[3] ); ?></p></li>
		<?php endforeach; ?>
	</ol>
</div></section>

<section class="section res-deadlines" id="deadline-details"><div class="container">
	<div class="res-heading"><p class="eyebrow">By return type</p><h2>Deadlines, balances and penalties</h2></div>
	<div class="res-deadline-grid">
		<?php foreach ( $bajwa_deadline_cards as $bajwa_card ) : ?>
		<article class="res-deadline">
			<div class="res-deadline__head"><span class="res-group__icon"><?php echo bajwa_section_icon( $bajwa_card[0] ); ?></span><div><h3><?php echo bajwa_sections_e( $bajwa_card[1] ); ?></h3><p><?php echo bajwa_sections_e( $bajwa_card[2] ); ?></p></div></div>
			<dl>
				<?php foreach ( $bajwa_card[3] as $bajwa_row ) : ?><div><dt><?php echo bajwa_sections_e( $bajwa_row[0] ); ?></dt><dd><?php echo bajwa_sections_e( $bajwa_row[1] ); ?></dd></div><?php endforeach; ?>
				<?php if ( 'Payroll' !== $bajwa_card[1] ) : foreach ( $bajwa_standard_penalty as $bajwa_row ) : ?><div class="res-deadline__penalty"><dt><?php echo bajwa_sections_e( $bajwa_row[0] ); ?></dt><dd><?php echo bajwa_sections_e( $bajwa_row[1] ); ?></dd></div><?php endforeach; else : ?><div class="res-deadline__penalty"><dt>Interest</dt><dd><?php echo bajwa_sections_e( $bajwa_standard_penalty[2][1] ); ?></dd></div><?php endif; ?>
			</dl>
		</article>
		<?php endforeach; ?>
	</div>
</div></section>

<section class="section res-penalties" id="penalties"><div class="container">
	<div class="res-heading res-heading--light"><p class="eyebrow">Penalties &amp; interest</p><h2>What happens when a deadline is missed</h2><p>These penalties apply to all outstanding balances owing on late-filed returns.</p></div>
	<div class="res-penalty-grid">
		<article><span><?php echo bajwa_section_icon( 'clock' ); ?></span><strong>5% + 1%<small>/ month</small></strong><h3>Late filing</h3><p>5% of the balance owing, plus 1% for each month the return is late.</p></article>
		<article><span><?php echo bajwa_section_icon( 'alert' ); ?></span><strong>10% + 2%<small>/ month</small></strong><h3>Repeated failure to file</h3><p>A higher penalty applies when returns are filed late again.</p></article>
		<article><span><?php echo bajwa_section_icon( 'percent' ); ?></span><strong>Prescribed<small> rate</small></strong><h3>Interest on balances</h3><p>Charged from the day the amount was due until the day the CRA receives payment.</p></article>
	</div>
</div></section>

<section class="section res-note"><div class="container res-note__inner">
	<span class="res-note__icon"><?php echo bajwa_section_icon( 'calendar' ); ?></span>
	<div><p class="eyebrow">Stay ahead of every deadline</p><h2>Not sure which dates apply to you?</h2><p>When a due date falls on a weekend or public holiday, the CRA generally accepts filing or payment on the next business day. Deadlines can change, so contact us for dates specific to your situation.</p></div>
	<a class="button button--gold" href="<?php echo bajwa_sections_e( $contact ); ?>">Talk to a CPA <?php echo bajwa_section_icon( 'arrow' ); ?></a>
</div></section>
