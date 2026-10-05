<?php
/** Builds suggested services and articles for the standalone service and article pages. */
$related_current_service = $service ?? '';
$related_current_article = $current['slug'] ?? ( $article ?? '' );
$related_keys = array_keys( $services );
$related_position = array_search( $related_current_service, $related_keys, true );
if ( false !== $related_position ) { $related_keys = array_merge( array_slice( $related_keys, $related_position + 1 ), array_slice( $related_keys, 0, $related_position ) ); }
$related_services = array();
foreach ( array_slice( $related_keys, 0, 4 ) as $related_slug ) {
	$related_img = $card_images[ $related_slug ] ?? 'service-corporate';
	$related_small = file_exists( __DIR__ . '/assets/images/' . $related_img . '-600.jpg' ) ? '-600' : '-960';
	$related_services[] = array(
		'title' => $services[ $related_slug ][0],
		'url'   => html_entity_decode( $service_url( $related_slug ) ),
		'image' => '<img class="related-service__image" src="assets/images/' . $related_img . $related_small . '.jpg" loading="lazy" decoding="async" alt="' . htmlspecialchars( $services[ $related_slug ][0] ) . ' services">',
		'text'  => $services[ $related_slug ][1],
	);
}
$related_posts = array();
foreach ( $blog_posts as $related_post ) {
	if ( count( $related_posts ) >= 3 ) { break; }
	if ( ( $related_post['slug'] ?? '' ) === $related_current_article ) { continue; }
	$related_img = $blog_image( $related_post['slug'] );
	$related_small = file_exists( __DIR__ . '/assets/images/' . $related_img . '-600.jpg' ) ? '-600' : '-960';
	$related_posts[] = array(
		'title' => html_entity_decode( strip_tags( $related_post['title']['rendered'] ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
		'url'   => html_entity_decode( $article_url( $related_post['slug'] ) ),
		'image' => '<img class="related-post__image" src="assets/images/' . $related_img . $related_small . '.jpg" loading="lazy" decoding="async" alt="">',
		'date'  => date( 'M j, Y', strtotime( $related_post['date'] ?? 'now' ) ),
		'text'  => bajwa_sections_trim_words( $related_post['excerpt']['rendered'] ?? '', 18 ),
	);
}
