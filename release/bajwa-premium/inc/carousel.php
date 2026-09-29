<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bajwa_register_content_types() {
	register_post_type( 'service', array(
		'labels' => array( 'name' => 'Services', 'singular_name' => 'Service', 'add_new_item' => 'Add New Service', 'edit_item' => 'Edit Service' ),
		'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-chart-area', 'has_archive' => true,
		'rewrite' => array( 'slug' => 'service' ), 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
	) );
	register_post_type( 'hero_slide', array(
		'labels' => array( 'name' => 'Hero Slides', 'singular_name' => 'Hero Slide', 'add_new_item' => 'Add New Hero Slide', 'edit_item' => 'Edit Hero Slide' ),
		'public' => false, 'show_ui' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-images-alt2',
		'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
	) );
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'bajwa_slide_details', 'Slide content', 'bajwa_slide_metabox', 'hero_slide', 'normal', 'high' );
} );

function bajwa_slide_metabox( $post ) {
	wp_nonce_field( 'bajwa_save_slide', 'bajwa_slide_nonce' );
	$fields = array(
		'bajwa_eyebrow' => array( 'Eyebrow', 'Small text above the heading' ),
		'bajwa_slide_text' => array( 'Supporting paragraph', 'Keep this concise for mobile visitors.' ),
		'bajwa_button_label' => array( 'Button label', 'Example: Book a consultation' ),
		'bajwa_button_url' => array( 'Button link', 'A page URL or relative path, e.g. /contact/' ),
		'bajwa_image_position' => array( 'Image focal point', 'CSS position, e.g. center center or 65% center' ),
	);
	echo '<p><strong>Heading:</strong> use the title field above. <strong>Image:</strong> use Featured Image. <strong>Order:</strong> use the Order field in Page Attributes.</p>';
	foreach ( $fields as $key => $details ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="%1$s" name="%1$s" value="%3$s" placeholder="%4$s"></p>', esc_attr( $key ), esc_html( $details[0] ), esc_attr( $value ), esc_attr( $details[1] ) );
	}
}

add_action( 'save_post_hero_slide', function ( $post_id ) {
	if ( ! isset( $_POST['bajwa_slide_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bajwa_slide_nonce'] ) ), 'bajwa_save_slide' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( array( 'bajwa_eyebrow', 'bajwa_slide_text', 'bajwa_button_label', 'bajwa_image_position' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) { update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ); }
	}
	if ( isset( $_POST['bajwa_button_url'] ) ) { update_post_meta( $post_id, 'bajwa_button_url', esc_url_raw( wp_unslash( $_POST['bajwa_button_url'] ) ) ); }
} );

function bajwa_render_hero() {
	$slides = get_posts( array( 'post_type' => 'hero_slide', 'numberposts' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
	if ( ! $slides ) { return; }
	?>
	<section class="hero" data-carousel aria-roledescription="carousel" aria-label="Featured services">
		<div class="hero__slides">
		<?php foreach ( $slides as $index => $slide ) :
			$attachment_id = get_post_thumbnail_id( $slide );
			$image = $attachment_id ? get_the_post_thumbnail_url( $slide, 'full' ) : '';
			$fallback = get_post_meta( $slide->ID, 'bajwa_fallback_image', true ) ?: 'hero-advisory.jpg';
			if ( ! $image ) { $image = get_theme_file_uri( 'assets/images/' . $fallback ); }
			$position = get_post_meta( $slide->ID, 'bajwa_image_position', true ) ?: 'center center';
			?>
			<article class="hero__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-slide role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ($index + 1) . ' of ' . count( $slides ) ); ?>" aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
				<?php if ( $attachment_id ) :
					echo wp_get_attachment_image( $attachment_id, 'full', false, array( 'class' => 'hero__image', 'alt' => '', 'sizes' => '100vw', 'style' => 'object-position:' . esc_attr( $position ), 'fetchpriority' => 0 === $index ? 'high' : 'auto', 'loading' => 0 === $index ? 'eager' : 'lazy' ) );
				else : $base = pathinfo( $fallback, PATHINFO_FILENAME ); ?>
				<img class="hero__image" src="<?php echo esc_url( $image ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $base . '-960.jpg' ) ); ?> 960w, <?php echo esc_url( get_theme_file_uri( 'assets/images/' . $base . '-1280.jpg' ) ); ?> 1280w, <?php echo esc_url( $image ); ?> 1672w" sizes="100vw" alt="" width="1672" height="941" style="object-position:<?php echo esc_attr( $position ); ?>" <?php echo 0 === $index ? 'fetchpriority="high" loading="eager"' : 'loading="lazy" decoding="async"'; ?>>
				<?php endif; ?>
				<div class="hero__veil"></div>
				<div class="container hero__inner"><div class="hero__content">
					<p class="hero__eyebrow"><?php echo esc_html( get_post_meta( $slide->ID, 'bajwa_eyebrow', true ) ); ?></p>
					<?php printf( '<%1$s class="hero__title">%2$s</%1$s>', 0 === $index ? 'h1' : 'h2', esc_html( get_the_title( $slide ) ) ); ?>
					<p class="hero__copy"><?php echo esc_html( get_post_meta( $slide->ID, 'bajwa_slide_text', true ) ); ?></p>
					<a class="button button--gold" href="<?php echo esc_url( get_post_meta( $slide->ID, 'bajwa_button_url', true ) ?: home_url( '/contact/' ) ); ?>"><?php echo esc_html( get_post_meta( $slide->ID, 'bajwa_button_label', true ) ?: 'Book a consultation' ); ?> <?php echo bajwa_icon( 'arrow' ); ?></a>
				</div></div>
			</article>
		<?php endforeach; ?>
		</div>
		<div class="container hero__controls">
			<button class="hero__arrow" type="button" data-prev aria-label="Previous slide">&#8592;</button>
			<div class="hero__dots" role="tablist" aria-label="Choose slide"><?php foreach ( $slides as $i => $slide ) : ?><button type="button" role="tab" data-dot="<?php echo esc_attr( $i ); ?>" aria-label="Show slide <?php echo esc_attr( $i + 1 ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"></button><?php endforeach; ?></div>
			<button class="hero__arrow" type="button" data-next aria-label="Next slide">&#8594;</button>
		</div>
	</section>
	<noscript><style>.hero__slide:not(:first-child),.hero__controls{display:none}.hero__slide:first-child{position:relative;opacity:1;visibility:visible}</style></noscript>
	<?php
}
