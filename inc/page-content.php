<?php
/**
 * Editable designed pages.
 *
 * The Home, About, Contact and Tax Checklists designs are stored in each page's editor content
 * as one "Custom HTML" block per section (dynamic parts are shortcodes). Templates render the
 * page content, so edits in the WordPress page editor appear on the site.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Bump when the seeded designs change so pages that were never edited are refreshed. */
define( 'BAJWA_DESIGNED_CONTENT_VERSION', 12 );

/** Designed page keys mapped to their content source file and page lookup. */
function bajwa_designed_pages() {
	return array(
		'home'       => array( 'file' => 'inc/content/home.php', 'slugs' => array( 'home' ), 'template' => '' ),
		'about'      => array( 'file' => 'inc/content/about.php', 'slugs' => array( 'about', 'about-us' ), 'template' => 'page-about.php' ),
		'contact'    => array( 'file' => 'inc/content/contact.php', 'slugs' => array( 'contact', 'contact-us' ), 'template' => 'page-contact.php' ),
		'checklists' => array( 'file' => 'inc/checklist-view.php', 'slugs' => array( 'tax-checklists' ), 'template' => 'page-tax-checklists.php' ),
		'tips'       => array( 'file' => 'inc/content/tax-tips.php', 'slugs' => array( 'tax-tips' ), 'template' => 'page-tax-tips.php' ),
		'deadlines'  => array( 'file' => 'inc/content/tax-filing-deadlines.php', 'slugs' => array( 'tax-filing-deadlines' ), 'template' => 'page-tax-filing-deadlines.php' ),
		'resources'  => array( 'file' => 'inc/content/tax-resources.php', 'slugs' => array( 'tax-resources' ), 'template' => 'page-tax-resources.php' ),
	);
}

/** Page ID that displays a designed page, or 0. */
function bajwa_designed_page_id( $key ) {
	$pages = bajwa_designed_pages();
	if ( 'home' === $key && 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) { return (int) get_option( 'page_on_front' ); }
	// get_posts rather than get_pages/get_page_by_path so child pages (e.g. under Tax Resources) are found.
	$query = array( 'post_type' => 'page', 'numberposts' => 1, 'post_status' => array( 'publish', 'draft', 'private' ) );
	if ( ! empty( $pages[ $key ]['template'] ) ) {
		$found = get_posts( $query + array( 'meta_key' => '_wp_page_template', 'meta_value' => $pages[ $key ]['template'] ) );
		if ( $found ) { return (int) $found[0]->ID; }
	}
	foreach ( $pages[ $key ]['slugs'] ?? array() as $slug ) {
		$found = get_posts( $query + array( 'name' => $slug ) );
		if ( $found ) { return (int) $found[0]->ID; }
	}
	return 0;
}

/** Full HTML produced by a designed page source file. */
function bajwa_designed_source_html( $key ) {
	$pages = bajwa_designed_pages();
	if ( empty( $pages[ $key ] ) ) { return ''; }
	$image = get_theme_file_uri( 'assets/images/service-books.jpg' );
	$contact = esc_url( home_url( '/contact/' ) );
	$checklist_arrow = bajwa_icon( 'arrow' );
	$theme_uri = get_template_directory_uri();
	$resource_urls = array(
		'tips' => bajwa_template_page_url( 'page-tax-tips.php', '/tax-tips/' ),
		'deadlines' => bajwa_template_page_url( 'page-tax-filing-deadlines.php', '/tax-filing-deadlines/' ),
		'checklists' => bajwa_template_page_url( 'page-tax-checklists.php', '/tax-checklists/' ),
		'blog' => bajwa_template_page_url( 'page-blog.php', '/blog/' ),
	);
	ob_start();
	include get_theme_file_path( $pages[ $key ]['file'] );
	return (string) ob_get_clean();
}

/** Top-level <section> elements of a designed page, without HTML comments. */
function bajwa_designed_sections( $key ) {
	$html = preg_replace( '/<!--(?!\s*\/?wp:).*?-->/s', '', bajwa_designed_source_html( $key ) );
	preg_match_all( '/<section\b.*?<\/section>/s', $html, $matches );
	return array_map( 'trim', $matches[0] );
}

/** Block-editor content for a designed page: one Custom HTML block per section, shortcodes as Shortcode blocks. */
function bajwa_designed_block_content( $key ) {
	$blocks = array();
	foreach ( bajwa_designed_sections( $key ) as $section ) {
		$blocks[] = "<!-- wp:html -->\n" . $section . "\n<!-- /wp:html -->";
	}
	return implode( "\n\n", $blocks );
}

/** Store the designed sections in a page's editor content (keeps any previous content in post meta). */
function bajwa_seed_designed_page( $key, $force = false ) {
	$page_id = bajwa_designed_page_id( $key );
	if ( ! $page_id ) { return; }
	$seeded = (int) get_post_meta( $page_id, '_bajwa_designed_content', true );
	if ( $seeded && ! $force ) {
		// Refresh only pages whose content is still exactly the previously seeded design.
		if ( $seeded >= BAJWA_DESIGNED_CONTENT_VERSION || get_post_meta( $page_id, '_bajwa_designed_hash', true ) !== md5( (string) get_post_field( 'post_content', $page_id ) ) ) { return; }
	}
	$content = bajwa_designed_block_content( $key );
	if ( '' === $content ) { return; }
	$previous = (string) get_post_field( 'post_content', $page_id );
	if ( ! $seeded && '' !== trim( $previous ) && ! get_post_meta( $page_id, '_bajwa_previous_content', true ) ) {
		update_post_meta( $page_id, '_bajwa_previous_content', wp_slash( $previous ) );
	}
	$restore_kses = false !== has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $restore_kses ) { kses_remove_filters(); }
	$update = array( 'ID' => $page_id, 'post_content' => $content );
	// Drop excerpts imported from the previous site's page builder so the designed hero intro is used.
	if ( false !== strpos( (string) get_post_field( 'post_excerpt', $page_id ), '[' ) ) { $update['post_excerpt'] = ''; }
	wp_update_post( wp_slash( $update ) );
	if ( $restore_kses ) { kses_init_filters(); }
	$pages = bajwa_designed_pages();
	if ( ! empty( $pages[ $key ]['template'] ) && in_array( get_post_meta( $page_id, '_wp_page_template', true ), array( '', 'default' ), true ) ) {
		update_post_meta( $page_id, '_wp_page_template', $pages[ $key ]['template'] );
	}
	update_post_meta( $page_id, '_bajwa_designed_content', BAJWA_DESIGNED_CONTENT_VERSION );
	update_post_meta( $page_id, '_bajwa_designed_hash', md5( (string) get_post_field( 'post_content', $page_id ) ) );
}

function bajwa_seed_designed_pages( $force = false ) {
	foreach ( array_keys( bajwa_designed_pages() ) as $key ) { bajwa_seed_designed_page( $key, $force ); }
	update_option( 'bajwa_designed_content_version', BAJWA_DESIGNED_CONTENT_VERSION );
}

/** Also seed when the theme ZIP replaces an older version without re-activation. */
add_action( 'admin_init', function () {
	if ( ! current_user_can( 'edit_pages' ) || (int) get_option( 'bajwa_designed_content_version' ) >= BAJWA_DESIGNED_CONTENT_VERSION || ! get_option( 'bajwa_theme_setup_complete' ) ) { return; }
	bajwa_seed_designed_pages();
}, 20 );

/**
 * Output a designed page body: the page editor content once seeded, otherwise the built-in design.
 */
function bajwa_render_designed_body( $key ) {
	$page_id = get_the_ID();
	if ( get_post_meta( $page_id, '_bajwa_designed_content', true ) ) {
		$html = apply_filters( 'the_content', get_the_content( null, false, $page_id ) );
	} else {
		$html = do_shortcode( implode( "\n", bajwa_designed_sections( $key ) ) );
	}
	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- page content.
	if ( 'home' === $key ) { echo bajwa_faq_schema_from_html( $html ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-LD.
}

/** FAQPage structured data built from the FAQ items currently on the page, so it follows edits. */
function bajwa_faq_schema_from_html( $html ) {
	if ( ! preg_match_all( '/<details[^>]*class="[^"]*faq-item[^"]*"[^>]*>\s*<summary[^>]*>(.*?)<\/summary>(.*?)<\/details>/s', $html, $matches, PREG_SET_ORDER ) ) { return ''; }
	$schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
	foreach ( $matches as $match ) {
		$question = trim( html_entity_decode( wp_strip_all_tags( $match[1] ), ENT_QUOTES, 'UTF-8' ) );
		$answer = trim( preg_replace( '/\s+/', ' ', html_entity_decode( wp_strip_all_tags( $match[2] ), ENT_QUOTES, 'UTF-8' ) ) );
		if ( $question && $answer ) { $schema['mainEntity'][] = array( '@type' => 'Question', 'name' => $question, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ) ); }
	}
	return $schema['mainEntity'] ? '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>' : '';
}

/**
 * Content imported from the previous site is page-builder HTML. wpautop would wrap its line breaks in
 * <p> tags and split elements such as the pricing buttons, so skip it for that markup.
 */
add_filter( 'the_content', function ( $content ) {
	if ( false !== strpos( $content, 'wpb_wrapper' ) || false !== strpos( $content, 'vc_row' ) ) {
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'bajwa_restore_wpautop', 11 );
		// Drop "–" placeholder rows the old pricing tables used for alignment.
		$content = preg_replace( '#<p>\s*(?:–|&ndash;|&\#8211;|-)\s*</p>#u', '', $content );
	}
	return $content;
}, 8 );

function bajwa_restore_wpautop( $content ) {
	add_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_content', 'bajwa_restore_wpautop', 11 );
	return $content;
}

/* Shortcodes for the dynamic parts of designed pages. */

add_shortcode( 'bajwa_service_carousel', function () {
	$featured_slugs = array( 'corporate-tax-return', 'business-tax-return', 'personal-tax-return', 'financial-statements', 'budgeting-forecasting', 'business-consulting' );
	$featured = get_posts( array( 'post_type' => 'service', 'numberposts' => 8, 'post_name__in' => $featured_slugs, 'orderby' => 'post_name__in' ) );
	if ( ! $featured ) { $featured = get_posts( array( 'post_type' => 'service', 'numberposts' => 8, 'orderby' => 'menu_order', 'order' => 'ASC' ) ); }
	ob_start();
	?><div class="service-carousel" data-service-carousel aria-roledescription="carousel" aria-label="CPA services"><div class="service-carousel-viewport"><div class="visual-service-grid"><?php foreach ( $featured as $index => $service ) : ?><a class="service-visual" href="<?php echo esc_url( get_permalink( $service ) ); ?>"><div class="service-visual__media"><?php echo bajwa_service_card_image( $service->ID ); ?><span class="service-visual__shade"></span><span class="service-visual__index"><?php echo esc_html( str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span></div><div class="service-visual__body"><h3><?php echo esc_html( get_the_title( $service ) ); ?></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt( $service ), 18 ) ); ?></p><span class="service-visual__link">Explore service <?php echo bajwa_icon( 'arrow' ); ?></span></div></a><?php endforeach; ?></div></div><div class="service-carousel-nav"><button type="button" data-service-prev aria-label="Previous services">&larr;</button><div class="service-carousel-dots" aria-label="Choose service group"></div><button type="button" data-service-next aria-label="Next services">&rarr;</button></div></div><?php
	return ob_get_clean();
} );

add_shortcode( 'bajwa_service_list', function () {
	$services = get_posts( array( 'post_type' => 'service', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$html = '<div class="service-mini-list">';
	foreach ( $services as $service ) {
		$html .= '<a href="' . esc_url( get_permalink( $service ) ) . '"><span class="service-mini-list__image">' . bajwa_service_card_image( $service->ID, '' ) . '</span><strong>' . esc_html( get_the_title( $service ) ) . '</strong>' . bajwa_icon( 'arrow' ) . '</a>';
	}
	return $html . '</div>';
} );

add_shortcode( 'bajwa_contact_form', function () {
	$sent = isset( $_GET['sent'] ) ? sanitize_key( wp_unslash( $_GET['sent'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	ob_start();
	?><form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><div class="form-loader" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/bajwa-logo.png' ) ); ?>" width="250" height="68" alt=""><span></span><p>Sending securely…</p></div><input type="hidden" name="action" value="bajwa_contact"><?php wp_nonce_field( 'bajwa_contact', 'bajwa_contact_nonce' ); ?><div class="honeypot" aria-hidden="true"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div><?php if ( null !== $sent ) : ?><div class="form-notice <?php echo '1' === $sent ? 'success' : 'error'; ?>" role="status"><?php echo '1' === $sent ? 'Thank you. Your message has been sent.' : 'Please check your details and try again, or call us.'; ?></div><?php endif; ?><div class="form-heading"><span>01</span><div><p class="eyebrow">Confidential enquiry</p><h2>How can we help?</h2><p>Fields marked * are required.</p></div></div><div class="form-row"><label><span>Full name *</span><input name="name" required autocomplete="name" placeholder="Your full name"></label><label><span>Email address *</span><input type="email" name="email" required autocomplete="email" placeholder="you@example.com"></label></div><div class="form-row"><label><span>Phone number</span><input type="tel" name="phone" autocomplete="tel" placeholder="(416) 000-0000"></label><label><span>How can we help?</span><select name="service_interest"><option value="">Select a service</option><option>Personal tax</option><option>Corporate &amp; business tax</option><option>Bookkeeping &amp; financial statements</option><option>Real estate tax</option><option>Tax planning</option><option>Business consulting</option><option>Other</option></select></label></div><label><span>Tell us about your needs *</span><textarea name="message" rows="6" required placeholder="Share the question, deadline or situation you would like help with."></textarea></label><label class="form-consent"><input type="checkbox" required><span>I agree that Bajwa CPA may contact me about this enquiry.</span></label><div class="form-submit"><button class="button button--navy" type="submit">Send secure enquiry <?php echo bajwa_icon( 'arrow' ); ?></button><small>Your information is used only to respond to this enquiry.</small></div></form><?php
	return ob_get_clean();
} );
