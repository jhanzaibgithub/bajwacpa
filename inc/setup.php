<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bajwa_maybe_auto_setup() {
	bajwa_register_content_types();
	bajwa_run_setup();
	flush_rewrite_rules();
}

function bajwa_setup_admin_menu() {
	add_theme_page( 'Bajwa Theme Setup', 'Bajwa Theme Setup', 'manage_options', 'bajwa-setup', 'bajwa_setup_screen' );
}

function bajwa_setup_screen() {
	?>
	<div class="wrap"><h1>Bajwa CPA Premium Setup</h1><p>This creates or refreshes the required pages, services, hero slides and navigation. Existing content with the same slug is preserved.</p>
	<?php if ( isset( $_GET['complete'] ) ) : ?><div class="notice notice-success"><p>Website content and navigation are ready.</p></div><?php endif; ?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"><?php wp_nonce_field( 'bajwa_setup' ); ?><input type="hidden" name="action" value="bajwa_premium_setup"><?php submit_button( 'Create website content' ); ?></form></div>
	<?php
}

function bajwa_handle_setup() {
	if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Not allowed.' ); }
	check_admin_referer( 'bajwa_setup' );
	bajwa_run_setup(); flush_rewrite_rules();
	wp_safe_redirect( admin_url( 'themes.php?page=bajwa-setup&complete=1' ) ); exit;
}

function bajwa_upsert_post( $type, $slug, $title, $content = '', $excerpt = '', $parent = 0, $order = 0 ) {
	$existing = get_page_by_path( $slug, OBJECT, $type );
	if ( $existing ) {
		$updates = array( 'ID' => $existing->ID );
		if ( '' === trim( (string) $existing->post_content ) && '' !== trim( $content ) ) { $updates['post_content'] = $content; }
		if ( '' === trim( (string) $existing->post_excerpt ) && '' !== trim( $excerpt ) ) { $updates['post_excerpt'] = $excerpt; }
		if ( $parent && ! $existing->post_parent ) { $updates['post_parent'] = $parent; }
		if ( count( $updates ) > 1 ) { wp_update_post( $updates ); }
		return $existing->ID;
	}
	return wp_insert_post( array( 'post_type' => $type, 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $title, 'post_content' => $content, 'post_excerpt' => $excerpt, 'post_parent' => $parent, 'menu_order' => $order ) );
}

function bajwa_run_setup() {
	if ( ! function_exists( 'wp_insert_post' ) ) { return; }
	$home = bajwa_upsert_post( 'page', 'home', 'Home', '<!-- wp:heading --><h2>Premium tax, accounting and advisory support</h2><!-- /wp:heading --><!-- wp:paragraph {"className":"lead"} --><p class="lead">Bajwa CPA helps individuals, professionals and growing businesses make informed financial decisions with confidence.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Homepage hero slides, service cards and testimonials are managed through their dedicated WordPress content areas. This editor content is available for additional homepage copy.</p><!-- /wp:paragraph -->' );
	$about = bajwa_upsert_post( 'page', 'about-us', 'About Us', '<p class="lead">Bajwa CPA Professional Corporation is a Chartered Professional Accounting firm serving businesses, families and individuals across the Greater Toronto Area.</p><h2>Professional advice, personal attention</h2><p>We provide high-quality, efficient and timely tax, accounting and advisory services. Our goal is to create practical solutions around each client’s circumstances and build long-term relationships grounded in trust, professional integrity and responsive service.</p><h2>Led by Vaishali Bajwa, CPA, CGA</h2><p>Our team combines current knowledge of Canadian tax rules with a clear, approachable style. Whether you are building a company, managing property, filing a personal return or planning for the future, we help you understand the numbers and make confident decisions.</p>' );
	$checklists = bajwa_upsert_post( 'page', 'tax-checklists', 'Tax Checklists', '<p class="lead">Prepare for your appointment with the records commonly needed for accurate, efficient tax filing.</p><h2>Personal tax checklist</h2><ul><li>T4, T4A, T5 and other income slips</li><li>RRSP contribution receipts</li><li>Medical, childcare, tuition and donation receipts</li><li>Rental, investment and self-employment records</li><li>Prior-year Notice of Assessment</li></ul><h2>Business tax checklist</h2><ul><li>Bank and credit-card statements</li><li>Sales invoices and expense receipts</li><li>Payroll, GST/HST and WSIB records</li><li>Asset purchases and financing agreements</li><li>Prior-year financial statements and tax returns</li></ul><p><a class="button button--navy" href="/contact/">Ask us what to bring</a></p>' );
	$contact = bajwa_upsert_post( 'page', 'contact', 'Contact Us', '<!-- wp:heading --><h2>Start a confidential conversation</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Tell us about the accounting, tax or business question you are working through. Our team will respond with a practical next step.</p><!-- /wp:paragraph -->' );
	$resources = bajwa_upsert_post( 'page', 'tax-resources', 'Tax Resources', '<p class="lead">Straightforward Canadian tax and accounting information to help you stay informed and prepared.</p>' );
	$resource_pages = array(
		'blog' => array( 'Blog Posts', '<p>Insights and practical guidance from the Bajwa CPA team.</p>[bajwa_posts category="blogs"]' ),
		'articles' => array( 'Articles', '<p>Helpful articles on Canadian taxes, accounting and business decisions.</p>[bajwa_posts category="articles"]' ),
		'newsletters' => array( 'Newsletters', '<p>Updates and reminders for individuals and business owners.</p>[bajwa_posts]' ),
		'ask-a-question' => array( 'Ask a Question', '<p class="lead">Have a Canadian tax or accounting question? Send it to our team and we will help you identify the right next step.</p><p><a class="button button--navy" href="/contact/">Ask Bajwa CPA</a></p>' ),
		'tax-tips' => array( 'Tax Tips', '<h2>Keep better records all year</h2><p>Separate business and personal transactions, keep digital copies of receipts and reconcile accounts regularly. Good records make tax filing faster and support the deductions you claim.</p><h2>Plan before year end</h2><p>Tax planning is most useful before a transaction or deadline. Speak with a CPA early about compensation, investments, major purchases or business changes.</p>' ),
		'tax-filing-deadlines' => array( 'Tax Filing Deadlines', '<p class="lead">Canadian tax dates vary by taxpayer and can change. Confirm current deadlines with the Canada Revenue Agency or with our office.</p><h2>Common annual deadlines</h2><ul><li>Most individual income tax returns: April 30</li><li>Self-employed individuals and spouses: June 15, with balances generally due April 30</li><li>Corporate returns: six months after fiscal year end</li><li>GST/HST: based on your assigned filing frequency</li></ul><p><em>When a due date falls on a weekend or public holiday, CRA rules may provide for the next business day. Contact us for advice specific to your filing.</em></p>' ),
		'faq' => array( 'Frequently Asked Questions', '<details><summary>When should I contact a CPA?</summary><p>Ideally before a major financial decision, business change or tax deadline. Early advice usually gives you more options.</p></details><details><summary>Do you work with both individuals and companies?</summary><p>Yes. We serve individuals, self-employed professionals, corporations, investors and non-residents.</p></details><details><summary>Can you help year-round?</summary><p>Yes. Our work includes ongoing bookkeeping, compliance, planning and advisory support—not only tax-season filing.</p></details>' ),
	);
	$resource_ids = array();
	foreach ( $resource_pages as $slug => $data ) { $resource_ids[ $slug ] = bajwa_upsert_post( 'page', $slug, $data[0], $data[1], '', $resources ); }
	$template_map = array( $about => 'page-about.php', $contact => 'page-contact.php', $resource_ids['blog'] ?? 0 => 'page-blog.php', $resource_ids['tax-tips'] ?? 0 => 'page-tax-tips.php', $resource_ids['tax-filing-deadlines'] ?? 0 => 'page-tax-filing-deadlines.php' );
	foreach ( $template_map as $page_id => $template ) { if ( $page_id && in_array( get_post_meta( $page_id, '_wp_page_template', true ), array( '', 'default' ), true ) ) { update_post_meta( $page_id, '_wp_page_template', $template ); } }

	$services = array(
		'corporate-tax-return' => array( 'Corporate Tax Return', 'Accurate corporate filings, compliance and year-round tax guidance for companies of every size.', '<p>We prepare and file corporate income tax returns while helping business owners understand their obligations, available deductions and planning opportunities.</p><h2>Corporate tax support that goes beyond filing</h2><ul><li>T2 corporate income tax returns</li><li>Tax instalment and deadline guidance</li><li>Shareholder and compensation planning</li><li>CRA correspondence and adjustments</li><li>Year-end tax planning</li></ul>' ),
		'business-tax-return' => array( 'Business Tax Return', 'Professional tax and accounting support for sole proprietors, partnerships and growing businesses.', '<p>We help self-employed clients and partnerships organize their records, report income correctly and claim appropriate business expenses.</p><h2>Clear guidance for business owners</h2><p>Our team can coordinate bookkeeping, HST returns, tax filing and business registration so you can focus on running the business.</p>' ),
		'bookkeeping' => array( 'Bookkeeping', 'Reliable monthly bookkeeping that keeps your records current and decisions grounded in accurate numbers.', '<p>Timely books are the foundation of useful financial information. We can organize transactions, reconcile accounts and prepare reports tailored to your business.</p><h2>Organized records, fewer surprises</h2><ul><li>Bank and credit-card reconciliations</li><li>General ledger maintenance</li><li>GST/HST support</li><li>Payroll record coordination</li><li>Monthly and quarterly reporting</li></ul>' ),
		'personal-tax-return' => array( 'Personal Tax Return', 'Careful personal tax preparation designed to minimize stress and avoid missed credits or deductions.', '<p>Canadian tax rules change frequently. We prepare personal income tax returns accurately and on time, including more complex situations involving investments, rental properties or self-employment.</p>' ),
		'non-resident-tax-returns' => array( 'Non-Resident Tax Return', 'Canadian tax guidance for non-residents, newcomers and people with cross-border circumstances.', '<p>Residency status and Canadian-source income can create specialized reporting requirements. We help clients understand and meet their Canadian filing obligations.</p>' ),
		'real-estate-tax-returns' => array( 'Real Estate Tax Return', 'Tax preparation and planning for rental properties, property dispositions and real estate professionals.', '<p>Real estate transactions can involve rental income, capital gains, principal-residence reporting, GST/HST and other issues. We help you document and report them correctly.</p>' ),
		'trust-estate-tax-return' => array( 'Trust & Estate Tax Return', 'Sensitive, organized tax assistance for trusts, estates and executors.', '<p>We help trustees and legal representatives navigate tax filings, information requirements and key deadlines during estate administration.</p>' ),
		'incorporation-business-registration' => array( 'Incorporation & Business Registration', 'Practical help choosing and establishing the right structure for your new business.', '<p>We assist with business name registration, incorporation and initial tax-account setup, and explain the ongoing responsibilities that come with each structure.</p>' ),
		'financial-statements' => array( 'Financial Statements', 'Clear financial statements that support compliance, lending and better business decisions.', '<p>We prepare compilation financial statements based on management-provided information and help owners understand what their numbers show.</p>' ),
		'tax-planning' => array( 'Tax Planning', 'Forward-looking strategies for business owners, families and investors.', '<p>Effective planning happens before filing time. We review your structure, timing, compensation and major transactions to identify practical tax-saving opportunities.</p>' ),
		'professional-corporations' => array( 'Professional Corporations', 'Incorporation and tax guidance for regulated professionals including doctors, dentists, lawyers and consultants.', '<p>We help regulated professionals evaluate and establish professional corporations, coordinate tax registrations and understand their ongoing filing responsibilities.</p>' ),
		'hst-new-residential-rental-property-rebate' => array( 'GST/HST Rental Property Rebate', 'Guidance for eligible purchasers applying for the GST/HST new residential rental property rebate.', '<p>Investment-property buyers may pay GST/HST at closing and later qualify to apply for a rebate. We help organize the information and prepare the application accurately.</p>' ),
		'budgeting-forecasting' => array( 'Budgeting & Forecasting', 'Financial plans and forward-looking projections that help owners allocate resources and make confident decisions.', '<p>Budgets define desired results while forecasts estimate likely outcomes. We build practical financial views that help owners monitor performance and plan ahead.</p>' ),
		'business-consulting' => array( 'Business Consulting', 'Practical advisory support for entrepreneurs making growth, structure and performance decisions.', '<p>From startup through growth, we help owners evaluate opportunities, understand financial implications and make decisions with a clearer view of the business.</p>' ),
	);
	$service_ids = array(); $order = 0;
	foreach ( $services as $slug => $data ) { $service_ids[ $slug ] = bajwa_upsert_post( 'service', $slug, $data[0], $data[2], $data[1], 0, ++$order ); }

	$image_assignments = array(
		'page-about.jpg' => array( $about => 'Diverse Canadian business owners meeting with a professional advisor in a modern Toronto office' ),
		'page-contact.jpg' => array( $contact => 'Welcoming modern professional office reception in Toronto' ),
		'service-corporate.jpg' => array( $service_ids['corporate-tax-return'] => 'Executive team reviewing corporate financial strategy', $service_ids['business-tax-return'] => 'Business team reviewing financial strategy' ),
		'service-books.jpg' => array( $service_ids['bookkeeping'] => 'Organized bookkeeping workspace with financial charts and calculator', $service_ids['financial-statements'] => 'Professional financial reporting workspace' ),
		'service-personal.jpg' => array( $service_ids['personal-tax-return'] => 'Canadian family discussing personal financial planning', $service_ids['non-resident-tax-returns'] => 'Family having a professional financial planning conversation', $service_ids['trust-estate-tax-return'] => 'Multigenerational family discussing estate planning' ),
		'service-realestate.jpg' => array( $service_ids['real-estate-tax-returns'] => 'Couple and advisor discussing a Canadian real estate investment' ),
		'card-professional-corporation.jpg' => array( $service_ids['professional-corporations'] => 'Canadian medical professional discussing corporation planning with an advisor' ),
		'card-hst-rebate.jpg' => array( $service_ids['hst-new-residential-rental-property-rebate'] => 'Modern Canadian condominium investment and property keys' ),
		'card-forecasting.jpg' => array( $service_ids['budgeting-forecasting'] => 'Business leadership team reviewing financial forecasts' ),
		'card-consulting.jpg' => array( $service_ids['business-consulting'] => 'Entrepreneur meeting with a strategic business consultant' ),
		'hero-planning.jpg' => array( $service_ids['tax-planning'] => 'Professional tax planning discussion' ),
		'hero-advisory.jpg' => array( $service_ids['incorporation-business-registration'] => 'Business advisory meeting for a growing company' ),
		'service-books.jpg#resources' => array( $checklists => 'Organized accounting workspace', $resources => 'Professional accounting resources' ),
	);
	foreach ( $image_assignments as $source_key => $posts_for_image ) {
		$source = strtok( $source_key, '#' );
		$attachment_id = bajwa_import_theme_image( $source, reset( $posts_for_image ) );
		if ( $attachment_id ) { foreach ( $posts_for_image as $target_id => $alt ) { if ( ! has_post_thumbnail( $target_id ) ) { set_post_thumbnail( $target_id, $attachment_id ); } update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt ); } }
	}
	$resource_images = array( $resource_ids['blog'] ?? 0 => 'blog-insights.jpg', $resource_ids['articles'] ?? 0 => 'blog-insights.jpg', $resource_ids['tax-tips'] ?? 0 => 'service-books.jpg', $resource_ids['tax-filing-deadlines'] ?? 0 => 'service-books.jpg' );
	foreach ( $resource_images as $page_id => $filename ) { if ( $page_id ) { $attachment_id = bajwa_import_theme_image( $filename, get_the_title( $page_id ) . ' resources' ); if ( $attachment_id && ! has_post_thumbnail( $page_id ) ) { set_post_thumbnail( $page_id, $attachment_id ); } } }

	$slides = array(
		array( 'Clarity for every financial decision.', 'Chartered Professional Accountants', 'Tax, accounting and advisory support built around your goals—not a one-size-fits-all answer.', 'Book a consultation', '/contact/', 'hero-clarity.jpg', 'center center' ),
		array( 'Plan today. Grow with confidence.', 'Strategic Tax Planning', 'Year-round advice that helps business owners protect cash flow, reduce surprises and move forward.', 'Explore tax planning', '/service/tax-planning/', 'hero-planning.jpg', '60% center' ),
		array( 'Your business deserves a clear view.', 'Accounting & Advisory', 'Reliable books, meaningful financial reporting and practical advice from a team that understands your business.', 'View our services', '/service/', 'hero-advisory.jpg', 'center center' ),
	);
	foreach ( $slides as $i => $slide ) {
		$id = bajwa_upsert_post( 'hero_slide', sanitize_title( $slide[0] ), $slide[0], '', '', 0, $i );
		foreach ( array( 'bajwa_eyebrow' => $slide[1], 'bajwa_slide_text' => $slide[2], 'bajwa_button_label' => $slide[3], 'bajwa_button_url' => $slide[4], 'bajwa_fallback_image' => $slide[5], 'bajwa_image_position' => $slide[6] ) as $key => $value ) { if ( '' === get_post_meta( $id, $key, true ) ) { update_post_meta( $id, $key, $value ); } }
	}

	update_option( 'show_on_front', 'page' ); update_option( 'page_on_front', $home );
	if ( ! get_option( 'permalink_structure' ) ) { update_option( 'permalink_structure', '/%postname%/' ); }
	bajwa_create_menu( $about, $checklists, $contact, $resources, $resource_ids, $service_ids );
	bajwa_import_articles();
	update_option( 'bajwa_theme_setup_complete', 1 );
}

function bajwa_import_theme_image( $filename, $alt = '' ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_bajwa_source_asset', 'meta_value' => $filename ) );
	if ( $existing ) { return $existing[0]->ID; }
	$path = BAJWA_PREMIUM_DIR . 'assets/images/' . basename( $filename );
	if ( ! is_readable( $path ) ) { return 0; }
	$upload = wp_upload_bits( basename( $filename ), null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $upload['error'] ) ) { return 0; }
	$type = wp_check_filetype( $upload['file'] );
	$id = wp_insert_attachment( array( 'post_mime_type' => $type['type'], 'post_title' => ucwords( str_replace( array( '-', '_' ), ' ', pathinfo( $filename, PATHINFO_FILENAME ) ) ), 'post_status' => 'inherit' ), $upload['file'] );
	if ( is_wp_error( $id ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt ); update_post_meta( $id, '_bajwa_source_asset', $filename );
	return $id;
}

function bajwa_import_articles() {
	$category_file = BAJWA_PREMIUM_DIR . 'data/categories.json';
	$post_file = BAJWA_PREMIUM_DIR . 'data/posts.json';
	if ( ! is_readable( $post_file ) ) { return; }
	$categories = is_readable( $category_file ) ? json_decode( file_get_contents( $category_file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$category_map = array();
	foreach ( (array) $categories as $category ) {
		$term = term_exists( $category['slug'], 'category' );
		if ( ! $term ) { $term = wp_insert_term( $category['name'], 'category', array( 'slug' => $category['slug'] ) ); }
		if ( ! is_wp_error( $term ) ) { $category_map[ (int) $category['id'] ] = (int) ( is_array( $term ) ? $term['term_id'] : $term ); }
	}
	$posts = json_decode( file_get_contents( $post_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	foreach ( (array) $posts as $source ) {
		if ( empty( $source['slug'] ) || get_page_by_path( $source['slug'], OBJECT, 'post' ) ) { continue; }
		$term_ids = array(); foreach ( (array) ( $source['categories'] ?? array() ) as $old_id ) { if ( isset( $category_map[ (int) $old_id ] ) ) { $term_ids[] = $category_map[ (int) $old_id ]; } }
		$post_id = wp_insert_post( array(
			'post_type' => 'post', 'post_status' => 'publish', 'post_name' => sanitize_title( $source['slug'] ),
			'post_title' => wp_strip_all_tags( $source['title']['rendered'] ?? '' ),
			'post_content' => wp_kses_post( $source['content']['rendered'] ?? '' ),
			'post_excerpt' => wp_strip_all_tags( $source['excerpt']['rendered'] ?? '' ),
			'post_date' => sanitize_text_field( $source['date'] ?? current_time( 'mysql' ) ), 'post_category' => $term_ids,
		) );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$image_name = function_exists( 'bajwa_blog_image_name' ) ? bajwa_blog_image_name( $post_id ) . '.jpg' : 'blog-insights.jpg';
			$attachment_id = bajwa_import_theme_image( $image_name, wp_strip_all_tags( $source['title']['rendered'] ?? 'Bajwa CPA article' ) );
			if ( $attachment_id ) { set_post_thumbnail( $post_id, $attachment_id ); }
		}
	}
}

function bajwa_create_menu( $about, $checklists, $contact, $resources, $resource_ids, $service_ids ) {
	$name = 'Primary Navigation'; $menu = wp_get_nav_menu_object( $name ); $menu_id = $menu ? $menu->term_id : wp_create_nav_menu( $name );
	if ( ! $menu_id || is_wp_error( $menu_id ) ) { return; }
	if ( wp_get_nav_menu_items( $menu_id ) ) { $locations = (array) get_theme_mod( 'nav_menu_locations', array() ); $locations['primary'] = $menu_id; $locations['footer'] = $menu_id; set_theme_mod( 'nav_menu_locations', $locations ); return; }
	$add = function ( $object_id, $title, $parent = 0, $type = 'page' ) use ( $menu_id ) { return wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => $object_id, 'menu-item-object' => $type, 'menu-item-parent-id' => $parent, 'menu-item-title' => $title, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) ); };
	$add( get_option( 'page_on_front' ), 'Home' ); $add( $about, 'About Us' );
	$services_parent = wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Services', 'menu-item-url' => get_post_type_archive_link( 'service' ), 'menu-item-type' => 'custom', 'menu-item-status' => 'publish' ) );
	foreach ( $service_ids as $id ) { $add( $id, get_the_title( $id ), $services_parent, 'service' ); }
	$add( $checklists, 'Tax Checklists' ); $resource_parent = $add( $resources, 'Tax Resources' );
	foreach ( array( 'blog' => 'Blog Posts', 'tax-tips' => 'Tax Tips', 'tax-filing-deadlines' => 'Tax Filing Deadlines' ) as $slug => $label ) { if ( isset( $resource_ids[ $slug ] ) ) { $add( $resource_ids[ $slug ], $label, $resource_parent ); } }
	$add( $contact, 'Contact Us' );
	set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id, 'footer' => $menu_id ) );
}

add_shortcode( 'bajwa_posts', function ( $atts ) {
	$atts = shortcode_atts( array( 'category' => '' ), $atts ); $args = array( 'post_type' => 'post', 'posts_per_page' => 9 ); if ( $atts['category'] ) { $args['category_name'] = sanitize_title( $atts['category'] ); }
	$q = new WP_Query( $args ); ob_start(); echo '<div class="card-grid">'; while ( $q->have_posts() ) { $q->the_post(); echo '<article class="post-card"><p class="eyebrow">' . esc_html( get_the_date() ) . '</p><h2><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2><p>' . esc_html( wp_trim_words( get_the_excerpt(), 24 ) ) . '</p><a class="text-link" href="' . esc_url( get_permalink() ) . '">Read article ' . bajwa_icon( 'arrow' ) . '</a></article>'; } echo '</div>'; wp_reset_postdata(); return ob_get_clean();
} );
