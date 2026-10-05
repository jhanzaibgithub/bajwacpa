<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** One-time migration of client-owned copy from the previous Bajwa CPA website. */
function bajwa_import_legacy_site_content() {
	if ( get_option( 'bajwa_legacy_content_version' ) >= 5 ) { return; }
	$pages_file = BAJWA_PREMIUM_DIR . 'data/live-pages.json';
	if ( is_readable( $pages_file ) ) {
		$source_pages = json_decode( file_get_contents( $pages_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$page_ids = array();
		foreach ( (array) $source_pages as $source ) {
			$slug = sanitize_title( $source['slug'] ?? '' );
			if ( ! $slug || in_array( $slug, array( 'test', '404-2' ), true ) ) { continue; }
			// Match by slug under any parent: setup creates resource pages as children of Tax Resources.
			$matches = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'any', 'numberposts' => 1 ) );
			$existing = $matches ? $matches[0] : null;
			if ( ! $existing && 'about' === $slug ) { $existing = get_page_by_path( 'about-us', OBJECT, 'page' ); }
			$payload = array(
				'post_type' => 'page', 'post_status' => 'publish', 'post_name' => $slug,
				'post_title' => wp_strip_all_tags( $source['title']['rendered'] ?? '' ),
				'post_content' => wp_kses_post( ! empty( $source['live_rendered'] ) ? $source['live_rendered'] : ( $source['content']['rendered'] ?? '' ) ),
				'post_excerpt' => trim( preg_replace( '/\[\/?[a-z_]+[^\]]*\]/i', '', html_entity_decode( wp_strip_all_tags( $source['excerpt']['rendered'] ?? '' ), ENT_QUOTES, 'UTF-8' ) ) ),
				'menu_order' => (int) ( $source['menu_order'] ?? 0 ),
			);
			// Designed pages keep the theme sections (and intro) seeded into their editor content.
			$is_designed = $existing && get_post_meta( $existing->ID, '_bajwa_designed_content', true );
			if ( $is_designed ) { unset( $payload['post_excerpt'] ); }
			if ( $existing && ( $is_designed || '' === trim( wp_strip_all_tags( $payload['post_content'] ) ) ) ) { unset( $payload['post_content'] ); }
			if ( $existing ) { $payload['ID'] = $existing->ID; $local_id = wp_update_post( $payload ); }
			else { $local_id = wp_insert_post( $payload ); }
			if ( $local_id && ! is_wp_error( $local_id ) ) {
				$page_ids[ (int) $source['id'] ] = (int) $local_id;
				update_post_meta( $local_id, '_bajwa_content_source', esc_url_raw( $source['link'] ?? '' ) );
				$source_html = ! empty( $source['live_rendered'] ) ? $source['live_rendered'] : ( $source['content']['rendered'] ?? '' );
				update_post_meta( $local_id, '_bajwa_source_sha256', hash( 'sha256', trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( $source_html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) ) ) );
			}
		}
		foreach ( (array) $source_pages as $source ) {
			$old_parent = (int) ( $source['parent'] ?? 0 );
			if ( $old_parent && isset( $page_ids[ (int) $source['id'] ], $page_ids[ $old_parent ] ) ) { wp_update_post( array( 'ID' => $page_ids[ (int) $source['id'] ], 'post_parent' => $page_ids[ $old_parent ] ) ); }
		}
	}
	$posts_file = BAJWA_PREMIUM_DIR . 'data/posts.json';
	if ( is_readable( $posts_file ) ) {
		$source_posts = json_decode( file_get_contents( $posts_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		foreach ( (array) $source_posts as $source ) {
			$slug = sanitize_title( $source['slug'] ?? '' ); if ( ! $slug ) { continue; }
			$existing = get_page_by_path( $slug, OBJECT, 'post' );
			$payload = array( 'post_type' => 'post', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => wp_strip_all_tags( $source['title']['rendered'] ?? '' ), 'post_content' => wp_kses_post( $source['content']['rendered'] ?? '' ), 'post_excerpt' => wp_strip_all_tags( $source['excerpt']['rendered'] ?? '' ), 'post_date' => sanitize_text_field( $source['date'] ?? current_time( 'mysql' ) ) );
			if ( $existing ) { $payload['ID'] = $existing->ID; $local_id = wp_update_post( $payload ); } else { $local_id = wp_insert_post( $payload ); }
			if ( $local_id && ! is_wp_error( $local_id ) ) { update_post_meta( $local_id, '_bajwa_content_source', esc_url_raw( $source['link'] ?? '' ) ); }
		}
	}
	$service_content = array(
		'corporate-tax-return' => '<p>We provide corporate tax preparation and advice for companies across many sectors. By staying current with Canadian tax law, we help corporate clients file accurately, meet deadlines and make informed decisions.</p><h2>Corporate tax services</h2><ul><li>T2 corporate tax return preparation and filing</li><li>Tax schedules, instalments and year-end adjusting entries</li><li>T4 employment and T5 dividend slips</li><li>GST/HST returns and payroll remittances</li><li>Compilation financial statements</li><li>Monthly, quarterly or annual bookkeeping and general ledgers</li><li>Corporate tax planning and tax-minimization strategies</li></ul><h2>Personalized for your business</h2><p>We take time to understand each client’s business and provide professional services in a clear, timely and reliable way.</p>',
		'business-tax-return' => '<p>We assist sole proprietors, partnerships and unincorporated businesses with professional bookkeeping, accounting, GST/HST and income-tax filing services. We can also guide business registration and incorporation.</p><h2>Business tax services</h2><ul><li>Statement of Business or Professional Activities (T2125)</li><li>GST/HST return preparation and filing</li><li>Payroll services for employees</li><li>Bookkeeping, general ledgers and financial statements</li><li>Year-round business tax planning</li></ul><h2>Tailored to your small business</h2><p>Every business is different. We learn how yours operates and explain the work in a practical, easy-to-understand way.</p>',
		'bookkeeping' => '<p>Accurate, current books are critical for measuring growth, planning development and filing reliable year-end returns. We serve corporations, sole proprietors and unincorporated businesses with personalized one-to-one support.</p><h2>Accounting and bookkeeping support</h2><ul><li>Monthly, quarterly and annual bookkeeping</li><li>General ledgers and financial statement preparation</li><li>Accounting-system setup for new businesses</li><li>Compilation financial statements</li><li>Payroll processing</li><li>Accounts payable and receivable</li><li>Bank and credit-card reconciliations</li><li>Monthly financial reports</li></ul><p>Our QuickBooks ProAdvisor-certified team helps keep records timely and accurate so owners can understand their financial position and manage the business successfully.</p>',
		'personal-tax-return' => '<p>We provide high-quality personal income tax return preparation (T1 General) for a wide range of circumstances. Canadian tax rules change frequently, and professional preparation helps keep filings accurate and on time.</p><p>We assist clients with employment, interest, rental, employment-insurance and self-employment income, while identifying applicable deductions and credits.</p>',
		'non-resident-tax-returns' => '<p>Canadian non-residents can face specialized filing, withholding and reporting requirements. We assist property owners and other non-residents with the documentation and returns required for Canadian-source income.</p><h2>Non-resident support</h2><ul><li>NR4 information and withholding reporting</li><li>NR6 applications</li><li>Section 216 rental-income returns</li><li>T2062 clearance-certificate support for property dispositions</li><li>Rental income and expense reporting</li></ul>',
		'real-estate-tax-returns' => '<p>Real estate ownership and transactions can create rental-income reporting, capital-gain, principal-residence and GST/HST considerations. We help property owners, investors and real estate professionals organize their records and report transactions correctly.</p><h2>Real estate tax support</h2><ul><li>Rental income and expense reporting</li><li>Property purchase and disposition documentation</li><li>Capital-gain and principal-residence reporting</li><li>Non-resident rental and disposition filings</li><li>GST/HST rental-property rebate guidance</li></ul>',
		'trust-estate-tax-return' => '<p>Trust and estate administration brings important tax filings and deadlines. We assist trustees, executors and legal representatives with organized, sensitive support.</p><h2>Trust and estate services</h2><ul><li>T3 trust income tax and information returns</li><li>Final personal tax returns</li><li>Estate income and beneficiary reporting</li><li>Clearance-certificate support</li><li>Guidance on records and filing deadlines</li></ul>',
		'incorporation-business-registration' => '<p>Choosing the right structure affects tax, compliance and administration. We help entrepreneurs register or incorporate their businesses and understand the responsibilities that follow.</p><h2>Business setup support</h2><ul><li>Business-name registration</li><li>Federal or provincial incorporation support</li><li>CRA business-number and program accounts</li><li>GST/HST and payroll registration</li><li>Professional corporation setup</li><li>Initial accounting and bookkeeping guidance</li></ul>',
		'financial-statements' => '<p>Reliable financial statements make it easier for owners, lenders and other users to understand what the numbers mean. They are an important tool for financing, compliance and sound business decisions.</p><h2>Compilation financial statements</h2><p>We compile financial information provided by management into professional financial statements and proposed year-end adjusting entries. These statements may support lending, a business purchase or sale, and other external requirements.</p>',
		'tax-planning' => '<p>Tax planning is most valuable before a filing deadline or major transaction. We help individuals and businesses review their structure, timing and available options so decisions are made with a clearer understanding of the tax consequences.</p><h2>Forward-looking advice</h2><ul><li>Owner-manager compensation planning</li><li>Corporate and personal instalment planning</li><li>Business structure and incorporation considerations</li><li>Investment and real estate transaction planning</li><li>Year-end tax-minimization strategies</li></ul>',
		'budgeting-forecasting' => '<p>A budget is an annual financial plan that identifies desired results, while a forecast projects expected results during the year. Used together, they help business owners compare plans with performance and respond earlier.</p><h2>Planning support</h2><ul><li>Annual operating budgets</li><li>Cash-flow projections</li><li>Scenario and sensitivity analysis</li><li>Performance monitoring</li><li>Financing and growth projections</li></ul>',
		'business-consulting' => '<p>Whether a business is established or just starting, owners make important decisions every day. Our advisory and business-consulting services help companies evaluate opportunities, improve performance and work toward financial success.</p><h2>Advice for every stage</h2><ul><li>Startup and structure decisions</li><li>Financial performance review</li><li>Cash-flow and profitability analysis</li><li>Growth planning</li><li>Practical owner-manager guidance</li></ul>',
	);
	$live_content = array();
	$live_content_file = BAJWA_PREMIUM_DIR . 'data/service-content-live.json';
	if ( is_readable( $live_content_file ) ) {
		$live_content = json_decode( file_get_contents( $live_content_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		foreach ( (array) $live_content as $slug => $entry ) { if ( ! empty( $entry['content'] ) ) { $service_content[ $slug ] = $entry['content']; } }
	}
	foreach ( array( 'professional-corporations', 'hst-new-residential-rental-property-rebate' ) as $removed_slug ) { $removed = get_page_by_path( $removed_slug, OBJECT, 'service' ); if ( $removed ) { wp_trash_post( $removed->ID ); } }
	foreach ( $service_content as $slug => $content ) {
		$post = get_page_by_path( $slug, OBJECT, 'service' );
		if ( ! $post ) { continue; }
		$update = array( 'ID' => $post->ID, 'post_content' => $content, 'post_excerpt' => '' );
		if ( ! empty( $live_content[ $slug ]['title'] ) ) { $update['post_title'] = $live_content[ $slug ]['title']; }
		wp_update_post( $update );
		$source_url = isset( $live_content[ $slug ]['source_url'] ) ? $live_content[ $slug ]['source_url'] : 'https://bajwacpa.com/service/' . $slug . '/';
		update_post_meta( $post->ID, '_bajwa_content_source', $source_url );
		foreach ( array( 'h1' => '_bajwa_h1', 'meta_title' => '_bajwa_seo_title', 'meta_description' => '_bajwa_seo_description' ) as $key => $meta_key ) { if ( ! empty( $live_content[ $slug ][ $key ] ) ) { update_post_meta( $post->ID, $meta_key, $live_content[ $slug ][ $key ] ); } }
	}
	update_option( 'bajwa_legacy_content_version', 5 );
}
add_action( 'admin_init', 'bajwa_import_legacy_site_content' );

function bajwa_service_seo_title( $title ) {
	if ( is_singular( 'service' ) ) { $seo = get_post_meta( get_queried_object_id(), '_bajwa_seo_title', true ); if ( $seo ) { return $seo; } }
	return $title;
}
add_filter( 'pre_get_document_title', 'bajwa_service_seo_title' );

function bajwa_service_seo_description() {
	if ( is_singular( 'service' ) ) { $seo = get_post_meta( get_queried_object_id(), '_bajwa_seo_description', true ); if ( $seo ) { echo '<meta name="description" content="' . esc_attr( $seo ) . "\">\n"; } }
}
add_action( 'wp_head', 'bajwa_service_seo_description', 1 );
