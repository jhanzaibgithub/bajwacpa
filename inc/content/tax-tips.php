<?php
/**
 * Designed Tax Tips page sections (seeded into the Tax Tips page editor).
 * Content from the Bajwa CPA "Tax Tips" PDF. Expects $theme_uri and $contact.
 */
if ( ! defined( 'ABSPATH' ) && ! defined( 'BAJWA_STANDALONE' ) ) { exit; }
$bajwa_tip_groups = array(
	'general' => array( 'book', 'General tax tips', 'Everyday planning points for individuals and families.', array(
		array( 'Individual tax return deadline', 'To avoid penalties, file your return on time even if you are unable to pay the balance due. Tax returns are due by April 30. If you are self-employed the filing deadline is extended to June 15, but any balance owing is still payable by April 30.' ),
		array( 'RRSP contributions', 'RRSP contributions must be made by the end of February to be deducted from your current year’s income. Your maximum contribution is shown on your notice of assessment.' ),
		array( 'Medical expenses', 'Medical expenses can be claimed for any 12-month period ending in the tax year, so try to group large costs together to maximize your claim. Only expenses above the lesser of 3% of your net income or the annual threshold ($2,397 for 2020) qualify for the credit.' ),
		array( 'GST/HST credit', 'You must file an income tax return to be eligible to receive the GST/HST credit.' ),
		array( 'Self-employed capital purchases', 'If you are self-employed, consider making capital purchases such as equipment or vehicles before December 31. Please consult us for further information.' ),
		array( 'Interest on RRSP loans', 'Interest on money borrowed to invest in an RRSP is not deductible. Borrowing to maximize your contribution may still be beneficial, so we recommend consulting a professional before deciding.' ),
		array( 'Charitable donations', 'If you and your spouse both made charitable donations, consider pooling them on one return to get the maximum tax benefit.' ),
	) ),
	'employees' => array( 'briefcase', 'Tax tips for employees', 'Ways to reduce tax on employment income and benefits.', array(
		array( 'Job-related courses', 'Ask your employer to pay for job-related courses directly rather than paying you additional remuneration. If you pay for post-secondary courses related to your current employment, you may be able to claim a tuition tax credit.' ),
		array( 'Home office expenses', 'If you work from home, try to arrange your employment terms so you can deduct expenses related to your home office. Your employer must sign form T2200 as evidence of this requirement.' ),
		array( 'Commission employees', 'If you are paid at least partly by commission, consider leasing rather than buying your cell phone, computer or fax machine, as you cannot claim capital cost allowance on these items.' ),
		array( 'Company car', 'You may reduce your operating cost benefit by reimbursing your employer for some or all operating costs, or for 100% of the personal-use portion, and by minimizing personal driving. Reduce your standby charge by limiting the days the car is available to you, and keep records of personal and business kilometres.' ),
		array( 'Compensation packages', 'When negotiating a compensation package, consider employee benefits that are not subject to tax.' ),
		array( 'Retiring allowance', 'Consider transferring a retiring allowance directly to your RRSP (up to the eligible amount) to avoid withholding tax.' ),
		array( 'Tradespeople’s tools', 'Eligible tradespeople can deduct up to $500 of tools. Cell phones and computers do not qualify for this deduction.' ),
	) ),
	'business' => array( 'building', 'Tax tips for business owners', 'Planning points for incorporated owners and their families.', array(
		array( 'Tax instalments', 'Pay your monthly or quarterly tax instalments if required by law or requested by the CRA to avoid interest on late payments or non-payment.' ),
		array( 'Final tax balances', 'Pay final corporate income tax balances within two months after year end (three months for certain Canadian-controlled private corporations).' ),
		array( 'Salary and dividend mix', 'Determine the optimal salary and dividend mix for you and other family members for the current year. If your personal tax rate is higher than the corporation’s, consider retaining income in the corporation to defer personal tax.' ),
		array( 'Remuneration accruals', 'Accrue salary and bonuses before your business’s year end, and ensure accrued amounts are paid within 180 days of the corporation’s year end.' ),
		array( 'Employee gifts and awards', 'Consider the CRA’s current policies when designing an employee gift and award program.' ),
		array( 'Salaries to family members', 'Pay a reasonable salary to a spouse or child in a lower tax bracket who provides services to your business.' ),
		array( 'Depreciable assets', 'In certain circumstances, consider accelerating the purchase of depreciable assets.' ),
		array( 'Capital gains exemption', 'The lifetime capital gains exemption was $866,912 effective January 1, 2019. Make sure the company qualifies as a qualified small business corporation, consider crystallizing the gain (plan a share sale two years in advance), let your spouse or children share in future growth to use their own exemption, and check whether a cumulative net investment loss (CNIL) will affect your claim.' ),
		array( 'Shareholder loans to your corporation', 'Have your corporation pay deductible interest on shareholder loans to reduce active business income to the $500,000 small business threshold. The threshold may differ in some jurisdictions.' ),
		array( 'Fines and penalties', 'Most government and court fines are not deductible.' ),
	) ),
	'students' => array( 'person', 'Tax tips for students', 'Credits and deductions available while you study.', array(
		array( 'Scholarships and prizes', 'Scholarships, fellowships, bursaries and prizes from a program that entitles you to education-related tax credits are generally tax-free.' ),
		array( 'Moving expenses', 'If you moved to attend post-secondary school full time, your moving expenses may be deductible.' ),
		array( 'Tuition tax credits', 'Claim tuition credits if you are a full- or part-time student, or if you pay for post-secondary education related to your current employment that your employer does not reimburse.' ),
		array( 'Foreign university tuition', 'If you attend a foreign university, your tuition fees may be eligible for a tuition credit in Canada.' ),
	) ),
);
$bajwa_chip_labels = array( 'general' => 'General', 'employees' => 'Employees', 'business' => 'Business owners', 'students' => 'Students' );
$bajwa_tip_total = 0;
foreach ( $bajwa_tip_groups as $bajwa_group ) { $bajwa_tip_total += count( $bajwa_group[3] ); }
?>
<section class="section res-intro"><div class="container res-intro__grid">
	<div class="res-intro__copy">
		<p class="eyebrow">Make your tax preparation successful</p>
		<h2>Practical tax tips for individuals, employees, business owners and students.</h2>
		<p class="lead">Use these resources to help you prepare for tax season. Browse <?php echo (int) $bajwa_tip_total; ?> tips below or download the complete guide.</p>
	</div>
	<aside class="res-download">
		<div class="res-download__doc" aria-hidden="true"><span>PDF</span><strong>Tax<br>Tips</strong><i></i><i></i><i></i></div>
		<div class="res-download__body">
			<p class="eyebrow">Free download</p>
			<h3>Tax Tips guide</h3>
			<p>The complete Bajwa CPA tax tips reference in one printable document.</p>
			<small>PDF · 4 pages · 2 MB</small>
			<a class="button button--gold" href="<?php echo bajwa_sections_e( $theme_uri . '/assets/documents/tax-tips.pdf' ); ?>" target="_blank" rel="noopener">Download PDF <?php echo bajwa_section_icon( 'download' ); ?></a>
		</div>
	</aside>
</div></section>

<section class="section res-tips"><div class="container">
	<div class="res-filter" data-tip-filters>
		<p class="res-filter__label">Filter tips by category</p>
		<div class="res-chips" role="group" aria-label="Filter tax tips by category">
			<button type="button" data-tip-filter="all" aria-pressed="true"><span>All tips</span><small><?php echo (int) $bajwa_tip_total; ?></small></button>
			<?php foreach ( $bajwa_tip_groups as $bajwa_key => $bajwa_group ) : ?><button type="button" data-tip-filter="<?php echo $bajwa_key; ?>" aria-pressed="false"><?php echo bajwa_section_icon( $bajwa_group[0] ); ?><span><?php echo bajwa_sections_e( $bajwa_chip_labels[ $bajwa_key ] ); ?></span><small><?php echo count( $bajwa_group[3] ); ?></small></button><?php endforeach; ?>
		</div>
	</div>
	<?php foreach ( $bajwa_tip_groups as $bajwa_key => $bajwa_group ) : ?>
	<div class="res-group" data-tip-group="<?php echo $bajwa_key; ?>">
		<div class="res-group__head"><span class="res-group__icon"><?php echo bajwa_section_icon( $bajwa_group[0] ); ?></span><div><h2><?php echo bajwa_sections_e( $bajwa_group[1] ); ?></h2><p><?php echo bajwa_sections_e( $bajwa_group[2] ); ?></p></div><span class="res-group__count"><?php echo count( $bajwa_group[3] ); ?> tips</span></div>
		<div class="res-tip-grid">
			<?php foreach ( $bajwa_group[3] as $bajwa_i => $bajwa_tip ) : ?>
			<article class="res-tip"><span class="res-tip__num"><?php echo str_pad( (string) ( $bajwa_i + 1 ), 2, '0', STR_PAD_LEFT ); ?></span><h3><?php echo bajwa_sections_e( $bajwa_tip[0] ); ?></h3><p><?php echo bajwa_sections_e( $bajwa_tip[1] ); ?></p></article>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div></section>

<section class="section res-note"><div class="container res-note__inner">
	<span class="res-note__icon"><?php echo bajwa_section_icon( 'alert' ); ?></span>
	<div><p class="eyebrow">Please note</p><h2>General information, not personal advice.</h2><p>The information above is generic in nature and may not apply to every individual. Tax rules, limits and thresholds change each year. Please contact us for guidance based on your situation.</p></div>
	<a class="button button--gold" href="<?php echo bajwa_sections_e( $contact ); ?>">Talk to a CPA <?php echo bajwa_section_icon( 'arrow' ); ?></a>
</div></section>
