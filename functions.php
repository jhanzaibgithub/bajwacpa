<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BAJWA_PREMIUM_VERSION', '5.9.9' );
define( 'BAJWA_PREMIUM_DIR', trailingslashit( get_template_directory() ) );

require_once BAJWA_PREMIUM_DIR . 'inc/setup.php';
require_once BAJWA_PREMIUM_DIR . 'inc/carousel.php';
require_once BAJWA_PREMIUM_DIR . 'inc/contact.php';
require_once BAJWA_PREMIUM_DIR . 'inc/editor.php';
require_once BAJWA_PREMIUM_DIR . 'inc/legacy-content.php';
require_once BAJWA_PREMIUM_DIR . 'inc/sections.php';
require_once BAJWA_PREMIUM_DIR . 'inc/page-content.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/site.css' );
	register_nav_menus( array( 'primary' => __( 'Primary navigation', 'bajwa-premium' ), 'footer' => __( 'Footer navigation', 'bajwa-premium' ) ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bajwa-premium', get_theme_file_uri( 'assets/css/site.css' ), array(), BAJWA_PREMIUM_VERSION );
	wp_enqueue_script( 'bajwa-premium', get_theme_file_uri( 'assets/js/site.js' ), array(), BAJWA_PREMIUM_VERSION, true );
} );

add_action( 'init', 'bajwa_register_content_types' );
add_action( 'after_switch_theme', 'bajwa_maybe_auto_setup' );
add_action( 'admin_menu', 'bajwa_setup_admin_menu' );
add_action( 'admin_post_bajwa_premium_setup', 'bajwa_handle_setup' );

add_action( 'wp_head', function () {
	printf( '<link rel="icon" href="%s" type="image/png" sizes="512x512">' . "\n", esc_url( get_theme_file_uri( 'assets/images/bajwa-favicon.png' ) ) );
	printf( '<link rel="icon" href="%s" type="image/png" sizes="32x32">' . "\n", esc_url( get_theme_file_uri( 'assets/images/bajwa-favicon-32.png' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s" sizes="192x192">' . "\n", esc_url( get_theme_file_uri( 'assets/images/bajwa-favicon-192.png' ) ) );
}, 0 );

add_action( 'wp_head', function () {
	if ( ! is_front_page() ) { return; }
	$slides = get_posts( array( 'post_type' => 'hero_slide', 'numberposts' => 1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	if ( ! $slides ) { return; }
	$url = get_the_post_thumbnail_url( $slides[0], 'full' );
	$srcset = '';
	if ( $url ) { $srcset = wp_get_attachment_image_srcset( get_post_thumbnail_id( $slides[0] ), 'full' ); }
	else {
		$file = get_post_meta( $slides[0]->ID, 'bajwa_fallback_image', true ) ?: 'hero-advisory.jpg';
		$url = get_theme_file_uri( 'assets/images/' . $file );
		$base = pathinfo( $file, PATHINFO_FILENAME );
		$srcset = get_theme_file_uri( 'assets/images/' . $base . '-960.jpg' ) . ' 960w, ' . get_theme_file_uri( 'assets/images/' . $base . '-1280.jpg' ) . ' 1280w, ' . $url . ' 1672w';
	}
	if ( $url ) { printf( '<link rel="preload" as="image" href="%s" imagesrcset="%s" imagesizes="100vw" fetchpriority="high">' . "\n", esc_url( $url ), esc_attr( $srcset ) ); }
}, 1 );

function bajwa_phone_href( $phone ) { return preg_replace( '/[^0-9+]/', '', $phone ); }

function bajwa_icon( $name ) {
	$icons = array(
		'arrow' => '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'check' => '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg>',
		'phone' => '<svg aria-hidden="true" viewBox="0 0 24 24"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.4 1.8.6 2.8.7a2 2 0 0 1 1.7 2.1z"/></svg>',
	);
	return $icons[ $name ] ?? '';
}

function bajwa_header_image_name( $post_id = 0 ) {
	if ( 'service' === get_post_type( $post_id ) ) {
		$map = array(
			'corporate-tax-return' => 'service-corporate', 'business-tax-return' => 'service-corporate',
			'bookkeeping' => 'service-books', 'financial-statements' => 'service-books',
			'personal-tax-return' => 'service-personal', 'non-resident-tax-returns' => 'service-personal', 'trust-estate-tax-return' => 'service-personal',
			'real-estate-tax-returns' => 'service-realestate', 'incorporation-business-registration' => 'page-about', 'tax-planning' => 'hero-planning',
		);
		return $map[ get_post_field( 'post_name', $post_id ) ] ?? 'service-corporate';
	}
	$slug = get_post_field( 'post_name', $post_id );
	if ( 'contact' === $slug ) { return 'page-contact'; }
	if ( 'about-us' === $slug ) { return 'page-about'; }
	if ( in_array( $slug, array( 'tax-checklists', 'tax-resources', 'articles', 'blog', 'newsletters', 'tax-tips', 'tax-filing-deadlines', 'faq', 'ask-a-question' ), true ) ) { return 'service-books'; }
	return 'page-about';
}

function bajwa_page_header( $args = array() ) {
	$args = wp_parse_args( $args, array( 'post_id' => get_the_ID(), 'eyebrow' => 'Bajwa CPA Professional Corporation', 'title' => get_the_title(), 'intro' => '', 'class' => '' ) );
	$custom_eyebrow = get_post_meta( $args['post_id'], '_bajwa_hero_eyebrow', true );
	$custom_title = get_post_meta( $args['post_id'], '_bajwa_hero_title', true );
	$custom_intro = get_post_meta( $args['post_id'], '_bajwa_hero_intro', true );
	if ( '' !== $custom_eyebrow ) { $args['eyebrow'] = $custom_eyebrow; }
	if ( '' !== $custom_title ) { $args['title'] = $custom_title; }
	if ( '' !== $custom_intro ) { $args['intro'] = $custom_intro; }
	$attachment_id = get_post_thumbnail_id( $args['post_id'] );
	$name = bajwa_header_image_name( $args['post_id'] );
	?>
	<header class="page-hero page-hero--image <?php echo esc_attr( $args['class'] ); ?>">
		<div class="page-hero__media">
		<?php if ( $attachment_id ) : echo wp_get_attachment_image( $attachment_id, 'full', false, array( 'class' => 'page-hero__image', 'sizes' => '100vw', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); else : ?>
			<img class="page-hero__image" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $name . '.jpg' ) ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $name . '-960.jpg' ) ); ?> 960w, <?php echo esc_url( get_theme_file_uri( 'assets/images/' . $name . '-1280.jpg' ) ); ?> 1280w, <?php echo esc_url( get_theme_file_uri( 'assets/images/' . $name . '.jpg' ) ); ?> 1672w" sizes="100vw" alt="" width="1672" height="941" loading="eager" fetchpriority="high">
		<?php endif; ?><span class="page-hero__overlay"></span></div>
		<div class="container page-hero__content"><p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p><h1><?php echo esc_html( $args['title'] ); ?></h1><?php if ( $args['intro'] ) : ?><p class="page-hero__intro"><?php echo esc_html( $args['intro'] ); ?></p><?php endif; ?></div>
	</header><?php
}

function bajwa_service_image_name( $post_id ) {
	$slug = get_post_field( 'post_name', $post_id );
	$map = array(
		'corporate-tax-return' => 'service-corporate', 'business-tax-return' => 'service-corporate',
		'bookkeeping' => 'service-books', 'financial-statements' => 'service-books',
		'personal-tax-return' => 'service-personal', 'non-resident-tax-returns' => 'service-personal', 'trust-estate-tax-return' => 'service-personal',
		'real-estate-tax-returns' => 'service-realestate', 'incorporation-business-registration' => 'page-about', 'tax-planning' => 'hero-planning',
		'budgeting-forecasting' => 'card-forecasting', 'business-consulting' => 'card-consulting',
	);
	return $map[ $slug ] ?? 'service-corporate';
}

function bajwa_service_card_image( $post_id, $class = 'service-visual__image' ) {
	$attachment_id = get_post_thumbnail_id( $post_id );
	$alt = get_the_title( $post_id ) . ' services';
	if ( $attachment_id ) { return wp_get_attachment_image( $attachment_id, 'medium_large', false, array( 'class' => $class, 'alt' => $alt, 'loading' => 'lazy', 'decoding' => 'async' ) ); }
	$name = bajwa_service_image_name( $post_id );
	$is_card = 0 === strpos( $name, 'card-' );
	$small_suffix = $is_card ? '-600.jpg' : '-960.jpg';
	return sprintf( '<img class="%s" src="%s" srcset="%s %dw, %s %dw" sizes="(max-width:760px) 100vw, 33vw" width="%d" height="%d" loading="lazy" decoding="async" alt="%s">', esc_attr( $class ), esc_url( get_theme_file_uri( 'assets/images/' . $name . '.jpg' ) ), esc_url( get_theme_file_uri( 'assets/images/' . $name . $small_suffix ) ), $is_card ? 600 : 960, esc_url( get_theme_file_uri( 'assets/images/' . $name . '.jpg' ) ), $is_card ? 1000 : 1672, $is_card ? 1000 : 1672, $is_card ? 750 : 941, esc_attr( $alt ) );
}

/** Keep a post's archive fallback image consistent with its article hero. */
function bajwa_blog_image_name( $post_id = 0 ) {
	$slug = strtolower( (string) get_post_field( 'post_name', $post_id ?: get_the_ID() ) );
	$rules = array(
		'service-realestate' => array( 'real-estate', 'realestate', 'property', 'rental' ),
		'service-books'      => array( 'gst', 'hst', 'rates', 'bookkeep', 'financial-statement' ),
		'service-personal'   => array( 'personal', 'cerb', 'individual', 'income-tax' ),
		'service-corporate'  => array( 'corporate', 'business', 'company' ),
	);
	foreach ( $rules as $image => $terms ) {
		foreach ( $terms as $term ) { if ( false !== strpos( $slug, $term ) ) { return $image; } }
	}
	$images = array( 'service-corporate', 'service-personal', 'service-realestate', 'service-books', 'card-forecasting', 'card-consulting' );
	return $images[ abs( crc32( $slug ) ) % count( $images ) ];
}

add_action( 'wp_head', function () {
	if ( is_front_page() ) { echo bajwa_local_business_schema( home_url( '/' ), get_theme_file_uri( 'assets/images/bajwa-logo.png' ) ) . "\n"; }
}, 5 );

/** Permalink of the first page using a template, or a fallback path. */
function bajwa_template_page_url( $template, $fallback ) {
	$pages = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'meta_key' => '_wp_page_template', 'meta_value' => $template, 'numberposts' => 1 ) );
	return $pages ? get_permalink( $pages[0] ) : home_url( $fallback );
}

/** Suggested services and articles for service and blog detail pages. */
function bajwa_render_related_content( $first = 'services' ) {
	$current = get_the_ID();
	$all = get_posts( array( 'post_type' => 'service', 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC', 'fields' => 'ids' ) );
	$position = array_search( $current, $all, true );
	// Start with the services that follow the current one so each page suggests a different set.
	$service_ids = false === $position ? $all : array_merge( array_slice( $all, $position + 1 ), array_slice( $all, 0, $position ) );
	$services = array();
	foreach ( array_slice( $service_ids, 0, 4 ) as $id ) {
		$services[] = array( 'title' => bajwa_plain_text( get_the_title( $id ) ), 'url' => get_permalink( $id ), 'image' => bajwa_service_card_image( $id, 'related-service__image' ), 'text' => bajwa_plain_text( wp_trim_words( get_the_excerpt( $id ), 14 ) ) );
	}
	$posts = bajwa_post_card_items( 3, array( $current ) );
	bajwa_render_related_hub( $services, $posts, array( 'first' => $first, 'services_url' => get_post_type_archive_link( 'service' ), 'blog_url' => bajwa_template_page_url( 'page-blog.php', '/blog/' ) ) );
}

/** Page excerpt for the hero intro, ignoring page-builder shortcode text imported from the previous site. */
function bajwa_clean_excerpt( $fallback = '' ) {
	if ( ! has_excerpt() ) { return $fallback; }
	$text = trim( preg_replace( '/\s+/', ' ', preg_replace( '/\[\/?[a-z_]+[^\]]*\]/i', ' ', get_the_excerpt() ) ) );
	// Keep the hero intro short: imported summaries can be several paragraphs long.
	return ( '' === $text || false !== strpos( $text, '[' ) ) ? $fallback : wp_trim_words( $text, 30 );
}

/** Article card data (title, url, image, date, text) for the latest posts. */
function bajwa_post_card_items( $count = 3, $exclude = array() ) {
	$posts = array();
	$query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $count, 'post__not_in' => $exclude, 'no_found_rows' => true, 'ignore_sticky_posts' => true ) );
	foreach ( $query->posts as $post ) {
		if ( has_post_thumbnail( $post ) ) {
			$image = get_the_post_thumbnail( $post, 'medium_large', array( 'class' => 'related-post__image', 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) );
		} else {
			$name = bajwa_blog_image_name( $post->ID );
			$small = file_exists( BAJWA_PREMIUM_DIR . 'assets/images/' . $name . '-600.jpg' ) ? '-600' : '-960';
			$image = sprintf( '<img class="related-post__image" src="%s" width="%d" height="%d" loading="lazy" decoding="async" alt="">', esc_url( get_theme_file_uri( 'assets/images/' . $name . $small . '.jpg' ) ), '-600' === $small ? 600 : 960, '-600' === $small ? 450 : 540 );
		}
		$posts[] = array( 'title' => bajwa_plain_text( get_the_title( $post ) ), 'url' => get_permalink( $post ), 'image' => $image, 'date' => get_the_date( 'M j, Y', $post ), 'text' => bajwa_plain_text( wp_trim_words( get_the_excerpt( $post ), 18 ) ) );
	}
	return $posts;
}

add_shortcode( 'bajwa_latest_posts', function ( $atts ) {
	$atts = shortcode_atts( array( 'count' => 3 ), $atts );
	return bajwa_render_post_cards( bajwa_post_card_items( max( 1, min( 12, (int) $atts['count'] ) ) ) );
} );

/** Plain text for card data that the section renderers escape (avoids double-encoded &amp;amp; and &amp;hellip;). */
function bajwa_plain_text( $text ) {
	return html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}
