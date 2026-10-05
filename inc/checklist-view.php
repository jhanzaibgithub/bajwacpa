<?php
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $val ) {
		return htmlspecialchars( (string) $val, ENT_QUOTES, 'UTF-8' );
	}
}


if ( ! isset( $image ) ) {
	$image = function_exists( 'get_theme_file_uri' ) ? get_theme_file_uri( 'assets/images/service-books.jpg' ) : 'assets/images/service-books.jpg';
}
if ( ! isset( $contact ) ) {
	$contact = function_exists( 'home_url' ) ? esc_url( home_url( '/contact/' ) ) : ( isset( $url ) ? $url( 'contact' ) : '#contact' );
}
if ( ! isset( $checklist_arrow ) ) {
	$checklist_arrow = function_exists( 'bajwa_icon' ) ? bajwa_icon( 'arrow' ) : '→';
}
if ( ! isset( $doc ) ) {
	$doc = static function ( $local_file, $remote_url = '' ) {
		if ( function_exists( 'get_theme_file_uri' ) ) {
			return esc_url( get_theme_file_uri( 'assets/documents/' . $local_file ) );
		}
		return 'assets/documents/' . rawurlencode( $local_file );
	};
}

$categories = array(
	'personal' => array(
		'title' => 'Personal Tax',
		'subtitle' => 'For individual taxpayers, self-employed professionals, and rental property owners.',
		'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
		'docs' => array(
			array(
				'title' => 'Personal Tax Return Checklist',
				'code' => 'T1 GENERAL',
				'desc' => 'Comprehensive checklist for Canadian personal tax filings, income slips, RRSP deductions, tuition, medical, and childcare receipts.',
				'file' => 'personal-tax-return-t1-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Personal-Tax-Return-T1-Checklist.pdf'
			),
			array(
				'title' => 'Personal Tax Expenses T2125',
				'code' => 'FORM T2125',
				'desc' => 'Statement of business or professional activities for sole proprietors, freelancers, home-office deductions, and vehicle mileage tracking.',
				'file' => 'personal-tax-expenses-t2125.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Personal-Tax-Expenses-T2125.pdf'
			),
			array(
				'title' => 'Personal Tax Expenses Employment T2200',
				'code' => 'FORM T2200',
				'desc' => 'Declaration of conditions of employment, required travel and vehicle expenses, work-from-home supplies, and employer allowances.',
				'file' => 'personal-tax-expenses-employment-t2200.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Personal-Tax-Expenses-Employment-T2200.pdf'
			),
			array(
				'title' => 'Personal - Rental Income & Expenses',
				'code' => 'RENTAL SCHEDULE',
				'desc' => 'Documentation requirements for residential and commercial rental properties, gross rent, mortgage interest, utilities, and capital cost allowance.',
				'file' => 'personal-rental-income-expenses.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2023/01/Personal-Rental-Income-_-Expenses.pdf'
			),
		)
	),
	'corporate' => array(
		'title' => 'Corporate Tax',
		'subtitle' => 'For Canadian corporations, owner-managed businesses, and incorporated professionals.',
		'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>',
		'docs' => array(
			array(
				'title' => 'Corporate Tax Return (T2) Checklist',
				'code' => 'T2 CORPORATION',
				'desc' => 'Full corporate filing package: trial balances, financial statements, shareholder loan statements, asset purchases, and bank reconciliations.',
				'file' => 'corporate-tax-return-t2-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Corporate-Tax-Return-T2-Checklist.pdf'
			),
			array(
				'title' => 'RC1 Checklist',
				'code' => 'CRA FORM RC1',
				'desc' => 'CRA business number registration checklist for corporate tax accounts, GST/HST registration, payroll deductions, and import/export accounts.',
				'file' => 'rc1-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/RC1-Checklist.pdf'
			),
			array(
				'title' => 'T4/T5 Checklist',
				'code' => 'T4 / T5 FILING',
				'desc' => 'Annual payroll and dividend checklist for employee remuneration, source deductions, and shareholder dividend distribution declarations.',
				'file' => 't4-t5-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/T4-T5-Checklist.pdf'
			),
		)
	),
	'non-resident' => array(
		'title' => 'Non Resident Tax',
		'subtitle' => 'For cross-border individuals, non-resident property owners, and international investors.',
		'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>',
		'docs' => array(
			array(
				'title' => 'NR4 Checklist',
				'code' => 'CRA FORM NR4',
				'desc' => 'Statement of amounts paid or credited to non-residents of Canada, Part XIII tax withholding, and cross-border distribution records.',
				'file' => 'nr4-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/NR4-Checklist.pdf'
			),
			array(
				'title' => 'NR6 Checklist',
				'code' => 'CRA FORM NR6',
				'desc' => 'Undertaking to file an income tax return by a non-resident receiving rent from Canadian real property or timber royalties.',
				'file' => 'nr6-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/NR6-Checklist.pdf'
			),
			array(
				'title' => 'Section 216 Checklist',
				'code' => 'SECTION 216',
				'desc' => 'Elective Canadian tax return for non-residents earning Canadian rental income, allowing tax calculation on net income rather than gross.',
				'file' => 'section-216-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Section-216-Checklist.pdf'
			),
			array(
				'title' => 'T2062 Checklist',
				'code' => 'FORM T2062',
				'desc' => 'Notice by a non-resident of Canada regarding the disposition of taxable Canadian property and Certificate of Compliance applications.',
				'file' => 't2062-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/T2062-Checklist.pdf'
			),
		)
	),
	'bookkeeping' => array(
		'title' => 'Bookkeeping',
		'subtitle' => 'For monthly reconciliations, sales ledgers, and year-end audit readiness.',
		'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="16" y1="14" x2="16" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>',
		'docs' => array(
			array(
				'title' => 'Bookkeeping Checklist',
				'code' => 'GENERAL LEDGER',
				'desc' => 'Checklist for monthly bank statements, credit card reconciliations, vendor invoices, sales registers, accounts payable, and GST/HST source filings.',
				'file' => 'bookkeeping-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Bookkeeping-Checklist.pdf'
			),
		)
	),
	'incorporation' => array(
		'title' => 'Incorporation & Business Registration',
		'subtitle' => 'For new ventures, Ontario & federal incorporations, and business structure setups.',
		'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/></svg>',
		'docs' => array(
			array(
				'title' => 'Incorporation Checklist',
				'code' => 'ARTICLES OF INC.',
				'desc' => 'Legal name search (Nuans), share structure configuration, director identification, registered office address, and CRA program registrations.',
				'file' => 'incorporation-checklist.pdf',
				'remote' => 'https://bajwacpa.com/wp-content/uploads/2020/01/Incorporation-Checklist.pdf'
			),
		)
	),
);
?>
<main id="main">
	<header class="page-hero page-hero--image checklist-hero">
		<div class="page-hero__media">
			<img class="page-hero__image" src="<?php echo esc_url( $image ); ?>" width="1672" height="941" alt="Organized accounting records prepared for tax filing" fetchpriority="high">
			<span class="page-hero__overlay"></span>
		</div>
		<div class="container page-hero__content">
			<p class="eyebrow">Official Client Resource Hub</p>
			<h1>Tax Checklists &amp; Filing Schedules</h1>
			<p class="page-hero__intro">A clear starting point for gathering the records behind an accurate, efficient filing.</p>
		</div>
	</header>

	<!-- Executive Client Document Hub -->
	<section class="section checklist-hub">
		<div class="container checklist-hub__container">
			
			<!-- Hub Header -->
			<div class="checklist-hub__header">
				<div class="checklist-hub__header-left">
					<p class="eyebrow">Document Library</p>
					<h2>We provide these checklists to make your tax preparation easier:</h2>
					<p class="checklist-hub__subtitle">Click on the title to access the document. These verified checklists ensure all necessary slips, receipts, and supporting schedules are prepared before your appointment.</p>
				</div>
				<div class="checklist-hub__header-search">
					<div class="checklist-search-box">
						<svg class="checklist-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
						<input type="text" id="checklistSearchInput" placeholder="Search checklists (e.g. T1, T2, T2125, Rental, NR4)..." autocomplete="off" aria-label="Search tax checklists">
						<button type="button" id="checklistSearchClear" class="checklist-search-clear" aria-label="Clear search" style="display:none;">&times;</button>
					</div>
				</div>
			</div>

			<!-- Filter Bar -->
			<div class="checklist-hub__filters" role="tablist" aria-label="Checklist Categories">
				<button type="button" class="hub-filter-tab is-active" data-filter="all">
					<span>All Checklists</span>
					<small>13</small>
				</button>
				<?php foreach ( $categories as $cat_key => $cat_data ) : ?>
					<button type="button" class="hub-filter-tab" data-filter="<?php echo esc_attr( $cat_key ); ?>">
						<span><?php echo esc_html( $cat_data['title'] ); ?></span>
						<small><?php echo count( $cat_data['docs'] ); ?></small>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- Document Suites Grid -->
			<div class="checklist-hub__suites" id="checklistSuitesContainer">
				<?php foreach ( $categories as $cat_key => $cat_data ) : ?>
					<div class="checklist-suite" data-suite="<?php echo esc_attr( $cat_key ); ?>">
						<!-- Suite Header -->
						<div class="checklist-suite__header">
							<div class="checklist-suite__title-area">
								<span class="checklist-suite__icon" aria-hidden="true"><?php echo $cat_data['icon']; ?></span>
								<div>
									<h3 class="checklist-suite__title"><?php echo esc_html( $cat_data['title'] ); ?></h3>
									<p class="checklist-suite__desc"><?php echo esc_html( $cat_data['subtitle'] ); ?></p>
								</div>
							</div>
							<span class="checklist-suite__count"><?php echo count( $cat_data['docs'] ); ?> <?php echo count( $cat_data['docs'] ) === 1 ? 'Checklist' : 'Checklists'; ?></span>
						</div>

						<!-- Suite Documents List -->
						<div class="checklist-suite__items">
							<?php foreach ( $cat_data['docs'] as $d ) : ?>
								<a href="<?php echo $doc( $d['file'], $d['remote'] ); ?>" target="_blank" rel="noopener noreferrer" class="checklist-doc-card" data-doc-title="<?php echo esc_attr( strtolower( $d['title'] . ' ' . $d['code'] . ' ' . $d['desc'] ) ); ?>">
									<div class="checklist-doc-card__meta">
										<span class="checklist-doc-card__badge"><?php echo esc_html( $d['code'] ); ?></span>
										<span class="checklist-doc-card__type">PDF Document</span>
									</div>
									<div class="checklist-doc-card__body">
										<h4 class="checklist-doc-card__title"><?php echo esc_html( $d['title'] ); ?></h4>
										<p class="checklist-doc-card__desc"><?php echo esc_html( $d['desc'] ); ?></p>
									</div>
									<div class="checklist-doc-card__action">
										<span class="checklist-doc-card__btn">
											<span>Download Checklist</span>
											<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
										</span>
									</div>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>

				<!-- No search results notification -->
				<div id="checklistNoResults" class="checklist-no-results" style="display:none;">
					<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
					<h3>No checklists found</h3>
					<p>No documents matched your search query. Try searching for "Personal", "Corporate", "T2125", or "Rental".</p>
					<button type="button" id="checklistResetBtn" class="button button--gold button--small">Reset Search</button>
				</div>
			</div>

			<!-- Preparation Concierge Banner -->
			<div class="checklist-concierge">
				<div class="checklist-concierge__glow"></div>
				<div class="checklist-concierge__inner">
					<div class="checklist-concierge__copy">
						<span class="checklist-concierge__tag">CPA Consultation Guidance</span>
						<h3>Not sure which checklists apply to your tax year?</h3>
						<p>Tax situations can involve multiple streams of income, foreign holdings, or blended business structures. Contact our chartered professional accountants directly for guidance tailored to your scenario.</p>
					</div>
					<div class="checklist-concierge__actions">
						<a class="button button--gold" href="<?php echo $contact; ?>">Book a consultation <?php echo $checklist_arrow; ?></a>
						<div class="checklist-concierge__contact">
							<span>Phone: <a href="tel:4169070568">416-907-0568</a></span>
							<span>Fax: <a href="tel:9056981218">905-698-1218</a></span>
							<span>Email: <a href="mailto:info@bajwacpa.com">info@bajwacpa.com</a></span>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</main>

<script>
(function() {
	var searchInput = document.getElementById('checklistSearchInput');
	var clearBtn = document.getElementById('checklistSearchClear');
	var resetBtn = document.getElementById('checklistResetBtn');
	var filterTabs = document.querySelectorAll('.hub-filter-tab');
	var suites = document.querySelectorAll('.checklist-suite');
	var docCards = document.querySelectorAll('.checklist-doc-card');
	var noResults = document.getElementById('checklistNoResults');
	var currentFilter = 'all';

	function applyFilters() {
		var query = (searchInput.value || '').trim().toLowerCase();
		clearBtn.style.display = query.length > 0 ? 'block' : 'none';
		var totalVisible = 0;

		suites.forEach(function(suite) {
			var suiteCat = suite.getAttribute('data-suite');
			var matchesCategory = (currentFilter === 'all' || currentFilter === suiteCat);
			var suiteCards = suite.querySelectorAll('.checklist-doc-card');
			var suiteVisibleCount = 0;

			suiteCards.forEach(function(card) {
				var docText = card.getAttribute('data-doc-title') || '';
				var matchesSearch = query === '' || docText.indexOf(query) !== -1;

				if (matchesCategory && matchesSearch) {
					card.style.display = 'grid';
					suiteVisibleCount++;
					totalVisible++;
				} else {
					card.style.display = 'none';
				}
			});

			if (suiteVisibleCount > 0) {
				suite.style.display = 'block';
			} else {
				suite.style.display = 'none';
			}
		});

		if (noResults) {
			noResults.style.display = totalVisible === 0 ? 'flex' : 'none';
		}
	}

	filterTabs.forEach(function(tab) {
		tab.addEventListener('click', function() {
			filterTabs.forEach(function(t) { t.classList.remove('is-active'); });
			tab.classList.add('is-active');
			currentFilter = tab.getAttribute('data-filter');
			applyFilters();
		});
	});

	if (searchInput) {
		searchInput.addEventListener('input', applyFilters);
	}

	if (clearBtn) {
		clearBtn.addEventListener('click', function() {
			searchInput.value = '';
			applyFilters();
			searchInput.focus();
		});
	}

	if (resetBtn) {
		resetBtn.addEventListener('click', function() {
			searchInput.value = '';
			currentFilter = 'all';
			filterTabs.forEach(function(t) {
				if (t.getAttribute('data-filter') === 'all') t.classList.add('is-active');
				else t.classList.remove('is-active');
			});
			applyFilters();
		});
	}
})();
</script>
