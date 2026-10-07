<?php
/**
 * Shared, dependency-free section renderers.
 * Used by the WordPress templates and by the standalone preview, so they must not call WordPress functions.
 */

if ( ! function_exists( 'bajwa_sections_e' ) ) {
	function bajwa_sections_e( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
}

function bajwa_section_icon( $name ) {
	$icons = array(
		'shield'   => '<path d="M12 3 4.5 6v5.5c0 4.6 3.2 8.4 7.5 9.5 4.3-1.1 7.5-4.9 7.5-9.5V6L12 3Z"/><path d="m8.8 12.2 2.2 2.2 4.4-4.6"/>',
		'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4M8 14h2.5M13.5 14H16M8 17h2.5"/>',
		'people'   => '<circle cx="9" cy="8.5" r="3.2"/><path d="M3.5 19.5c.6-3.2 2.8-5 5.5-5s4.9 1.8 5.5 5"/><circle cx="16.8" cy="9.4" r="2.5"/><path d="M15.6 14.6c2.6-.2 4.4 1.4 4.9 4.4"/>',
		'building' => '<path d="M4 20.5V5.5L12 3v17.5M12 8.5l8 2.5v9.5M2.5 20.5h19M7 8h2M7 11.5h2M7 15h2M15 13h2M15 16.5h2"/>',
		'person'   => '<circle cx="12" cy="8" r="3.6"/><path d="M5 20.5c.9-4 3.6-6.2 7-6.2s6.1 2.2 7 6.2"/>',
		'briefcase'=> '<rect x="3" y="7" width="18" height="13" rx="2.5"/><path d="M8.5 7V5.2A1.7 1.7 0 0 1 10.2 3.5h3.6a1.7 1.7 0 0 1 1.7 1.7V7M3 12.5h18M10.5 12.5v1.8h3v-1.8"/>',
		'chat'     => '<path d="M20.5 12a8 8 0 0 1-11.6 7.1L3.5 20.5l1.4-5A8 8 0 1 1 20.5 12Z"/><path d="M8.5 10.5h7M8.5 13.8h4.5"/>',
		'folder'   => '<path d="M3.5 7.2A2.2 2.2 0 0 1 5.7 5h3.8l2 2.3h6.8a2.2 2.2 0 0 1 2.2 2.2v8.3a2.2 2.2 0 0 1-2.2 2.2H5.7a2.2 2.2 0 0 1-2.2-2.2V7.2Z"/><path d="m9 13.6 2.1 2.1 4-4.1"/>',
		'file'     => '<path d="M14 3.5H7.2A2.2 2.2 0 0 0 5 5.7v12.6a2.2 2.2 0 0 0 2.2 2.2h9.6a2.2 2.2 0 0 0 2.2-2.2V8.5l-5-5Z"/><path d="M14 3.5v5h5M8.5 13h7M8.5 16.5h4.5"/>',
		'compass'  => '<circle cx="12" cy="12" r="8.5"/><path d="m15.5 8.5-2.2 4.8-4.8 2.2 2.2-4.8 4.8-2.2Z"/>',
		'download' => '<path d="M12 3.5v11.5M7 10.5l5 5 5-5M4.5 16.5v2.2a1.8 1.8 0 0 0 1.8 1.8h11.4a1.8 1.8 0 0 0 1.8-1.8v-2.2"/>',
		'clock'    => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
		'alert'    => '<path d="M12 3.5 2.8 19.5h18.4L12 3.5Z"/><path d="M12 10v4M12 17h.01"/>',
		'book'     => '<path d="M4 5.5A2 2 0 0 1 6 3.5h13v15H6a2 2 0 0 0-2 2v-15Z"/><path d="M4 20.5a2 2 0 0 1 2-2h13v2H6M8.5 8h7M8.5 11.5h5"/>',
		'percent'  => '<path d="M18.5 5.5 5.5 18.5"/><circle cx="7.5" cy="7.5" r="2.3"/><circle cx="16.5" cy="16.5" r="2.3"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'phone'    => '<path d="M21 16.4v2.8a1.9 1.9 0 0 1-2 1.9 18.6 18.6 0 0 1-8.1-2.9 18.3 18.3 0 0 1-5.6-5.6A18.6 18.6 0 0 1 2.4 4.5a1.9 1.9 0 0 1 1.9-2h2.8a1.9 1.9 0 0 1 1.9 1.6c.1.9.4 1.8.7 2.6a1.9 1.9 0 0 1-.4 2L8 9.9a15 15 0 0 0 5.6 5.6l1.2-1.2a1.9 1.9 0 0 1 2-.4c.8.3 1.7.6 2.6.7a1.9 1.9 0 0 1 1.6 1.8Z"/>',
	);
	return '<svg class="bajwa-icon" aria-hidden="true" viewBox="0 0 24 24">' . ( $icons[ $name ] ?? '' ) . '</svg>';
}

/** Three brand promises shown directly under the homepage hero. */
function bajwa_render_home_pillars() {
	$pillars = array(
		array( 'shield', 'Quality Professional Services', 'Every return and financial statement is prepared and reviewed by a licensed CPA, with close attention to detail.', 'Detail-driven CPA work' ),
		array( 'calendar', 'Year-Round Support', 'Questions about CRA letters, instalments or planning get answered all year, not just at tax time.', 'Available 12 months a year' ),
		array( 'people', 'Friendly &amp; Approachable Staff', 'You work with the same people every year, who know your history and explain things in plain language.', 'Personal, one-to-one service' ),
	);
	?>
	<section class="home-pillars" aria-labelledby="home-pillars-title"><div class="container">
		<h2 id="home-pillars-title" class="screen-reader-text">Why clients choose Bajwa CPA</h2>
		<div class="home-pillars__grid">
			<?php foreach ( $pillars as $i => $pillar ) : ?>
			<article class="pillar-card">
				<div class="pillar-card__top"><span class="pillar-card__icon"><?php echo bajwa_section_icon( $pillar[0] ); ?></span><span class="pillar-card__num"><?php echo str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ); ?></span></div>
				<h3><?php echo $pillar[1]; ?></h3>
				<p><?php echo bajwa_sections_e( $pillar[2] ); ?></p>
				<span class="pillar-card__tag"><?php echo bajwa_sections_e( $pillar[3] ); ?></span>
			</article>
			<?php endforeach; ?>
		</div>
	</div></section>
	<?php
}

/**
 * "A clearer financial path" introduction.
 *
 * @param array $args image (src), srcset, contact (url), checklists (url).
 */
function bajwa_render_home_path( $args ) {
	$audiences = array(
		array( 'building', 'Established companies', 'Corporate tax, financial statements and year-end planning' ),
		array( 'briefcase', 'Self-employed entrepreneurs', 'Business returns, bookkeeping and GST/HST filings' ),
		array( 'person', 'Individuals &amp; families', 'Personal returns with every eligible credit and deduction' ),
	);
	?>
	<section class="section home-path" aria-labelledby="home-path-title"><div class="container home-path__grid">
		<div class="home-path__media">
			<figure class="home-path__photo"><img src="<?php echo bajwa_sections_e( $args['image'] ); ?>" srcset="<?php echo bajwa_sections_e( $args['srcset'] ); ?>" sizes="(max-width:900px) 100vw, 46vw" width="1672" height="941" loading="lazy" decoding="async" alt="CPA meeting with business owners in the Greater Toronto Area"></figure>
			<div class="home-path__badge"><strong>CPA</strong><span>Chartered Professional<br>Accountants</span></div>
			<div class="home-path__stat"><strong>15+</strong><span>Years of professional experience</span></div>
		</div>
		<div class="home-path__copy">
			<p class="eyebrow">A clearer financial path</p>
			<h2 id="home-path-title">Your CPA in Brampton and Mississauga, for Clients Big &amp; Small</h2>
			<p class="lead">Welcome to Bajwa CPA Professional Corporation. Founded in 2016 by Vaishali Bajwa, CPA, the firm brings more than 15 years of professional experience to individuals, self-employed professionals and businesses across Brampton, Mississauga and Ontario.</p>
			<p>Whether you run an established company, work for yourself or file a simple personal return, we know Canadian tax rules and CRA requirements. Our goal is simple: lower taxes where the law allows, accurate filings and a far less stressful tax season.</p>
			<ul class="home-path__audiences">
				<?php foreach ( $audiences as $audience ) : ?>
				<li><span class="home-path__icon"><?php echo bajwa_section_icon( $audience[0] ); ?></span><div><strong><?php echo $audience[1]; ?></strong><small><?php echo bajwa_sections_e( $audience[2] ); ?></small></div></li>
				<?php endforeach; ?>
			</ul>
			<div class="home-path__actions">
				<a class="button button--gold" href="<?php echo bajwa_sections_e( $args['contact'] ); ?>">Book a consultation <?php echo bajwa_section_icon( 'arrow' ); ?></a>
				<a class="home-path__call" href="tel:4169070568"><span><?php echo bajwa_section_icon( 'phone' ); ?></span><span><small>Speak with a CPA</small>416-907-0568</span></a>
			</div>
		</div>
	</div></section>
	<?php
}

/** Four-step working process. */
function bajwa_render_home_process( $args ) {
	$steps = array(
		array( 'chat', 'Book a conversation', 'Tell us about your income, business or tax question at our Brampton office, by phone or by video.' ),
		array( 'folder', 'Share your documents', 'Use our <a href="' . bajwa_sections_e( $args['checklists'] ) . '">tax checklists</a> to gather what you need, then share documents in person or electronically.' ),
		array( 'file', 'We prepare &amp; file', 'A CPA prepares your return or statements, walks you through the result and files with the CRA on time.' ),
		array( 'compass', 'Year-round advice', 'Keep direct access to your CPA for planning, CRA letters and instalment questions all year.' ),
	);
	?>
	<section class="section home-process" aria-labelledby="home-process-title"><div class="container">
		<div class="home-process__heading">
			<div><p class="eyebrow">How we work</p><h2 id="home-process-title">A simple, transparent process from first call to filing.</h2></div>
			<p>Working with an accountant should feel organized and stress-free. Here is what to expect when you choose Bajwa CPA.</p>
		</div>
		<ol class="home-process__steps">
			<?php foreach ( $steps as $i => $step ) : ?>
			<li class="process-step"><span class="process-step__num"><?php echo str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ); ?></span><span class="process-step__icon"><?php echo bajwa_section_icon( $step[0] ); ?></span><h3><?php echo $step[1]; ?></h3><p><?php echo $step[2]; ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div></section>
	<?php
}

function bajwa_home_faqs( $args ) {
	return array(
		array( 'What accounting and tax services does Bajwa CPA offer?', 'Personal, business and corporate tax returns, bookkeeping, tax planning, incorporation, financial statements, real estate, non-resident, and trust and estate returns, plus budgeting and business consulting. <a href="' . bajwa_sections_e( $args['services'] ) . '">View all services</a>.' ),
		array( 'Where is Bajwa CPA located and which areas do you serve?', 'Our office is at 2 County Court Blvd, Suite 400, Brampton, ON L6W 3W8. We serve clients in Brampton, Mississauga, every Greater Toronto Area city and the rest of Ontario, in person or by phone and video.' ),
		array( 'When is the personal tax filing deadline in Canada?', 'Most individuals must file and pay by April 30. If you or your spouse or common-law partner are self-employed, you have until June 15 to file, but any balance owing is still due April 30. See our <a href="' . bajwa_sections_e( $args['deadlines'] ) . '">tax filing deadlines</a> for more dates.' ),
		array( 'Do you only help during tax season?', 'No. We support clients all year with bookkeeping, HST filings, corporate year-ends, instalments, CRA letters and tax planning.' ),
		array( 'What should I bring to my first appointment?', 'Bring last year’s tax return and notice of assessment, this year’s slips and receipts, and any letters from the CRA. Our <a href="' . bajwa_sections_e( $args['checklists'] ) . '">tax checklists</a> show exactly what applies to your situation.' ),
		array( 'How do I choose the best accountant in Brampton or Mississauga?', 'Check that the accountant is a licensed CPA in CPA Ontario’s public directory, ask for a fixed quote upfront, and make sure they are available outside tax season. Reviews from clients in a similar situation are the final check.' ),
	);
}

/** Frequently asked questions with matching FAQPage structured data. */
function bajwa_render_home_faq( $args ) {
	$faqs = bajwa_home_faqs( $args );
	$schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
	foreach ( $faqs as $faq ) {
		$schema['mainEntity'][] = array( '@type' => 'Question', 'name' => $faq[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => trim( strip_tags( $faq[1] ) ) ) );
	}
	?>
	<section class="section home-faq" aria-labelledby="home-faq-title"><div class="container home-faq__grid">
		<div class="home-faq__intro">
			<p class="eyebrow">Frequently asked questions</p>
			<h2 id="home-faq-title">Answers before you get started.</h2>
			<p>Have a question that is not listed? Our team is happy to help you understand what applies to your situation.</p>
			<a class="button button--navy" href="<?php echo bajwa_sections_e( $args['contact'] ); ?>">Ask a question <?php echo bajwa_section_icon( 'arrow' ); ?></a>
		</div>
		<div class="home-faq__list">
			<?php foreach ( $faqs as $i => $faq ) : ?>
			<details class="faq-item"<?php echo 0 === $i ? ' open' : ''; ?>><summary><span><?php echo bajwa_sections_e( $faq[0] ); ?></span><i aria-hidden="true"></i></summary><div class="faq-item__answer"><p><?php echo $faq[1]; ?></p></div></details>
			<?php endforeach; ?>
		</div>
	</div></section>
	<script type="application/ld+json"><?php echo json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?></script>
	<?php
}

/** Local business structured data for the homepage. */
function bajwa_local_business_schema( $home_url, $logo_url ) {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type' => 'AccountingService',
		'name' => 'Bajwa CPA Professional Corporation',
		'url' => $home_url,
		'logo' => $logo_url,
		'image' => $logo_url,
		'telephone' => '+1-416-907-0568',
		'faxNumber' => '+1-905-698-1218',
		'email' => 'info@bajwacpa.com',
		'address' => array( '@type' => 'PostalAddress', 'streetAddress' => '2 County Court Blvd, Suite 400', 'addressLocality' => 'Brampton', 'addressRegion' => 'ON', 'postalCode' => 'L6W 3W8', 'addressCountry' => 'CA' ),
		'areaServed' => array( 'Brampton', 'Mississauga', 'Toronto', 'Greater Toronto Area' ),
		'knowsAbout' => array( 'Corporate tax returns', 'Personal tax returns', 'Bookkeeping', 'Financial statements', 'Tax planning', 'Non-resident tax returns', 'Business consulting' ),
	);
	return '<script type="application/ld+json">' . json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ) . '</script>';
}

/**
 * Suggested services and articles shown at the end of service and blog detail pages.
 *
 * @param array  $services Items: title, url, image (img HTML), text.
 * @param array  $posts    Items: title, url, image (img HTML), date, text.
 * @param array  $args     first ('services'|'posts'), services_url, blog_url.
 */
function bajwa_render_related_hub( $services, $posts, $args ) {
	if ( ! $services && ! $posts ) { return; }
	ob_start();
	if ( $services ) : ?>
		<div class="related-block related-block--services">
			<div class="related-block__heading"><div><p class="eyebrow">Explore more services</p><h2>Other ways our CPAs can help</h2></div><a class="related-block__all" href="<?php echo bajwa_sections_e( $args['services_url'] ); ?>">All services <?php echo bajwa_section_icon( 'arrow' ); ?></a></div>
			<div class="related-services">
				<?php foreach ( $services as $i => $item ) : ?>
				<a class="related-service" href="<?php echo bajwa_sections_e( $item['url'] ); ?>">
					<span class="related-service__media"><?php echo $item['image']; ?><span class="related-service__num"><?php echo str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ); ?></span></span>
					<span class="related-service__body"><strong><?php echo bajwa_sections_e( $item['title'] ); ?></strong><?php if ( ! empty( $item['text'] ) ) : ?><small><?php echo bajwa_sections_e( $item['text'] ); ?></small><?php endif; ?><span class="related-service__link">View service <?php echo bajwa_section_icon( 'arrow' ); ?></span></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif;
	$services_html = ob_get_clean();
	ob_start();
	if ( $posts ) : ?>
		<div class="related-block related-block--posts">
			<div class="related-block__heading"><div><p class="eyebrow">Tax &amp; accounting insights</p><h2>Suggested reading</h2></div><a class="related-block__all" href="<?php echo bajwa_sections_e( $args['blog_url'] ); ?>">All articles <?php echo bajwa_section_icon( 'arrow' ); ?></a></div>
			<div class="related-posts-grid">
				<?php foreach ( $posts as $item ) : ?>
				<article class="related-post">
					<a class="related-post__media" href="<?php echo bajwa_sections_e( $item['url'] ); ?>" tabindex="-1" aria-hidden="true"><?php echo $item['image']; ?></a>
					<div class="related-post__body">
						<p class="related-post__meta"><span><?php echo bajwa_sections_e( $item['date'] ); ?></span><span>Insights</span></p>
						<h3><a href="<?php echo bajwa_sections_e( $item['url'] ); ?>"><?php echo bajwa_sections_e( $item['title'] ); ?></a></h3>
						<?php if ( ! empty( $item['text'] ) ) : ?><p><?php echo bajwa_sections_e( $item['text'] ); ?></p><?php endif; ?>
						<a class="related-post__link" href="<?php echo bajwa_sections_e( $item['url'] ); ?>">Read article <?php echo bajwa_section_icon( 'arrow' ); ?></a>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif;
	$posts_html = ob_get_clean();
	echo '<section class="section related-hub" aria-label="Suggested services and articles"><div class="container">';
	echo 'posts' === ( $args['first'] ?? 'services' ) ? $posts_html . $services_html : $services_html . $posts_html;
	echo '</div></section>';
}

/** Grid of article cards. Items: title, url, image (img HTML), date, text. */
function bajwa_render_post_cards( $posts ) {
	if ( ! $posts ) { return ''; }
	$html = '<div class="related-posts-grid">';
	foreach ( $posts as $item ) {
		$url = bajwa_sections_e( $item['url'] );
		$html .= '<article class="related-post"><a class="related-post__media" href="' . $url . '" tabindex="-1" aria-hidden="true">' . $item['image'] . '</a><div class="related-post__body"><p class="related-post__meta"><span>' . bajwa_sections_e( $item['date'] ) . '</span><span>Insights</span></p><h3><a href="' . $url . '">' . bajwa_sections_e( $item['title'] ) . '</a></h3>' . ( empty( $item['text'] ) ? '' : '<p>' . bajwa_sections_e( $item['text'] ) . '</p>' ) . '<a class="related-post__link" href="' . $url . '">Read article ' . bajwa_section_icon( 'arrow' ) . '</a></div></article>';
	}
	return $html . '</div>';
}

/** Trim plain text to a word count without WordPress. */
function bajwa_sections_trim_words( $text, $words = 18 ) {
	$text = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( strip_tags( (string) $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
	$parts = explode( ' ', $text );
	return count( $parts ) > $words ? implode( ' ', array_slice( $parts, 0, $words ) ) . '…' : $text;
}

/**
 * Article presentation: turns the plain "About the Author" paragraphs and the "Frequently Asked Questions"
 * paragraph pairs into designed blocks. Text is unchanged.
 */
function bajwa_enhance_article_html( $html ) {
	$html = (string) $html;
	// About the Author: label paragraph followed by the bio paragraph.
	$html = preg_replace_callback(
		'#<p[^>]*>\s*<strong>\s*About the Author\s*</strong>\s*</p>\s*(<p[^>]*>.*?</p>)#is',
		function ( $m ) {
			$bio = preg_replace( '#^<p[^>]*>|</p>$#i', '', trim( $m[1] ) );
			return '<aside class="author-card"><span class="author-card__avatar" aria-hidden="true">VB</span><div><p class="author-card__label">About the Author</p><p class="author-card__bio">' . $bio . '</p></div></aside>';
		},
		$html
	);
	// FAQ: heading followed by question/answer paragraph pairs.
	$html = preg_replace_callback(
		'#(<h2[^>]*>\s*(?:<strong>)?\s*Frequently Asked Questions\s*(?:</strong>)?\s*</h2>)(.*)$#is',
		function ( $m ) {
			$rest = $m[2];
			$items = '';
			$pattern = '#^\s*<p[^>]*>\s*<strong>([^<]*\?)</strong>\s*</p>\s*<p[^>]*>(.*?)</p>#is';
			while ( preg_match( $pattern, $rest, $q ) ) {
				$items .= '<details class="faq-item"><summary><span>' . $q[1] . '</span><i aria-hidden="true"></i></summary><div class="faq-item__answer"><p>' . $q[2] . '</p></div></details>';
				$rest = substr( $rest, strlen( $q[0] ) );
			}
			if ( '' === $items ) { return $m[0]; }
			return '<section class="article-faq">' . $m[1] . $items . '</section>' . $rest;
		},
		$html
	);
	return $html;
}
if ( function_exists( 'add_filter' ) ) { add_filter( 'the_content', function ( $content ) { return is_singular( 'post' ) ? bajwa_enhance_article_html( $content ) : $content; }, 25 ); }

/**
 * Links in article text that point at the old live domain: switch them to this site's own pages when a matching
 * page exists, otherwise drop the link and keep the text. $resolver( $path ) returns a URL or null.
 */
function bajwa_localize_post_links( $html, $resolver ) {
	return preg_replace_callback(
		'#<a\s[^>]*href=["\']https?://(?:www\.)?bajwacpa\.com/?([^"\']*)["\'][^>]*>(.*?)</a>#is',
		function ( $m ) use ( $resolver ) {
			$url = call_user_func( $resolver, trim( $m[1], '/' ) );
			return $url ? '<a href="' . htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' ) . '">' . $m[2] . '</a>' : $m[2];
		},
		(string) $html
	);
}

if ( function_exists( 'add_filter' ) ) {
	add_filter( 'the_content', function ( $content ) {
		if ( ! is_singular( 'post' ) ) { return $content; }
		return bajwa_localize_post_links( $content, function ( $path ) {
			if ( 'how-to-file-a-zero-income-tax-return-in-canada' === get_post_field( 'post_name', get_the_ID() ) ) { return null; }
			if ( '' === $path ) { return home_url( '/' ); }
			if ( 'service' === $path ) { return get_post_type_archive_link( 'service' ); }
			if ( 0 === strpos( $path, 'service/' ) ) { $svc = get_page_by_path( substr( $path, 8 ), OBJECT, 'service' ); return $svc ? get_permalink( $svc ) : null; }
			if ( 'contact' === $path ) { return home_url( '/contact/' ); }
			if ( 'tax-checklists' === $path ) { return bajwa_template_page_url( 'page-tax-checklists.php', '/tax-checklists/' ); }
			$post = get_page_by_path( $path, OBJECT, 'post' );
			return $post ? get_permalink( $post ) : null;
		} );
	}, 24 );
}
