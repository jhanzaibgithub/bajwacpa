<?php
if ( ! isset( $asset ) ) { $asset = static function ( $file ) { return esc_url( get_theme_file_uri( 'assets/images/' . $file ) ); }; }
if ( ! isset( $contact ) ) { $contact = esc_url( home_url( '/contact/' ) ); }
$doc = static function ( $file ) { return function_exists( 'get_theme_file_uri' ) ? esc_url( get_theme_file_uri( 'assets/documents/' . $file ) ) : 'assets/documents/' . rawurlencode( $file ); };
$checklist_arrow = function_exists( 'bajwa_icon' ) ? bajwa_icon( 'arrow' ) : ( $arrow ?? '' );
$checklist_groups = array(
	'Personal tax' => array(
		array( 'Personal Tax Return Checklist', 'personal-tax-return-t1-checklist.pdf' ),
		array( 'Business / Professional Expenses (T2125)', 'personal-tax-expenses-t2125.pdf' ),
		array( 'Employment Expenses (T2200)', 'personal-tax-expenses-employment-t2200.pdf' ),
		array( 'Rental Income & Expenses', 'personal-rental-income-expenses.pdf' ),
	),
	'Corporate tax' => array(
		array( 'Corporate Tax Return (T2) Checklist', 'corporate-tax-return-t2-checklist.pdf' ),
		array( 'CRA Business Number (RC1) Checklist', 'rc1-checklist.pdf' ),
		array( 'T4 / T5 Information Return Checklist', 't4-t5-checklist.pdf' ),
	),
	'Non-resident tax' => array(
		array( 'NR4 Checklist', 'nr4-checklist.pdf' ),
		array( 'NR6 Checklist', 'nr6-checklist.pdf' ),
		array( 'Section 216 Checklist', 'section-216-checklist.pdf' ),
		array( 'T2062 Checklist', 't2062-checklist.pdf' ),
	),
	'Business records' => array(
		array( 'Bookkeeping Checklist', 'bookkeeping-checklist.pdf' ),
		array( 'Incorporation Checklist', 'incorporation-checklist.pdf' ),
	),
);
?>
<main id="main">
	<header class="page-hero page-hero--image checklist-hero"><div class="page-hero__media"><img class="page-hero__image" src="<?php echo esc_url( $image ); ?>" width="1672" height="941" alt="Organized accounting records prepared for tax filing" fetchpriority="high"><span class="page-hero__overlay"></span></div><div class="container page-hero__content"><p class="eyebrow">Prepare with confidence</p><h1>Tax checklists, thoughtfully organized.</h1><p class="page-hero__intro">A clear starting point for gathering the records behind an accurate, efficient filing.</p></div></header>
	<section class="section checklist-studio"><div class="container checklist-studio__layout"><div class="checklist-studio__intro"><p class="eyebrow">Your filing workspace</p><h2>Everything in place. Nothing left to chance.</h2><p>Choose the checklist that matches your filing. These practical guides help you arrive prepared and give our team a clearer picture from the start.</p><div class="checklist-studio__image"><img src="<?php echo $asset( 'service-books.jpg' ); ?>" width="1672" height="941" alt="Organized documents and financial workspace" loading="lazy"><span>Organized records.<br>Clearer answers.</span></div></div><div class="checklist-documents"><article class="checklist-sheet checklist-sheet--personal"><div class="checklist-sheet__tab">Personal</div><header><span>01</span><div><p>Individual filing</p><h2>Personal tax checklist</h2></div></header><ul><li>T4, T4A, T5 and other income slips</li><li>RRSP contribution receipts</li><li>Medical, childcare, tuition and donation receipts</li><li>Rental, investment and self-employment records</li><li>Prior-year Notice of Assessment</li></ul><footer><span>Bring digital or paper copies</span><strong>01—05</strong></footer></article><article class="checklist-sheet checklist-sheet--business"><div class="checklist-sheet__tab">Business</div><header><span>02</span><div><p>Company filing</p><h2>Business tax checklist</h2></div></header><ul><li>Bank and credit-card statements</li><li>Sales invoices and expense receipts</li><li>Payroll, GST/HST and WSIB records</li><li>Asset purchases and financing agreements</li><li>Prior-year statements and tax returns</li></ul><footer><span>Well-organized records save time</span><strong>01—05</strong></footer></article></div></div></section>
	<section class="checklist-guidance"><div class="container checklist-guidance__inner"><span class="checklist-guidance__mark">?</span><div><p class="eyebrow">Not sure what applies?</p><h2>Your situation may need something different.</h2><p>Tell us what changed this year and we’ll identify the records relevant to your return.</p></div><a class="button button--gold" href="<?php echo $contact; ?>">Ask us what to bring <?php echo $checklist_arrow; ?></a></div></section>
</main>
