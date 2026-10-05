<?php
/** Download the public, client-owned WordPress content used by the theme importer. */
$root = dirname( __DIR__ );
$api  = 'https://bajwacpa.com/wp-json/wp/v2/';

function bajwa_fetch_json( string $url ): array {
	$context = stream_context_create( array( 'http' => array( 'timeout' => 45, 'user_agent' => 'Bajwa CPA content migration/1.0' ) ) );
	$json = @file_get_contents( $url, false, $context );
	if ( false === $json ) { throw new RuntimeException( 'Unable to fetch ' . $url ); }
	$data = json_decode( $json, true );
	if ( ! is_array( $data ) ) { throw new RuntimeException( 'Invalid JSON from ' . $url ); }
	return $data;
}

$page_fields = 'id,slug,link,title,content,excerpt,parent,featured_media,status,menu_order';
$post_fields = 'id,slug,link,date,modified,title,content,excerpt,featured_media,categories,status';
$pages = bajwa_fetch_json( $api . 'pages?per_page=100&orderby=id&order=asc&_fields=' . $page_fields );
$posts = bajwa_fetch_json( $api . 'posts?per_page=100&orderby=date&order=desc&_fields=' . $post_fields );
foreach ( $pages as &$page ) {
	$html = @file_get_contents( $page['link'], false, stream_context_create( array( 'http' => array( 'timeout' => 45, 'user_agent' => 'Bajwa CPA content migration/1.0' ) ) ) );
	if ( false === $html ) { $page['live_rendered'] = ''; continue; }
	$dom = new DOMDocument(); libxml_use_internal_errors( true ); $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html );
	$xpath = new DOMXPath( $dom ); $article = $xpath->query( '//article[@id="post-' . (int) $page['id'] . '"]' )->item( 0 );
	$rendered = '';
	if ( $article ) { foreach ( $article->childNodes as $child ) { $rendered .= $dom->saveHTML( $child ); } }
	$page['live_rendered'] = trim( $rendered );
}
unset( $page );
$media_ids = array_values( array_unique( array_filter( array_merge( array_column( $pages, 'featured_media' ), array_column( $posts, 'featured_media' ) ) ) ) );
$media = array();
foreach ( $media_ids as $id ) {
	try {
		$item = bajwa_fetch_json( $api . 'media/' . (int) $id . '?_fields=id,source_url,alt_text,caption,media_details' );
		$media[ (string) $id ] = $item;
	} catch ( RuntimeException $error ) {
		$media[ (string) $id ] = array( 'id' => (int) $id, 'unavailable' => true );
	}
}
$write = static function ( string $file, array $data ) use ( $root ): void {
	$json = json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( false === file_put_contents( $root . '/data/' . $file, $json . "\n" ) ) { throw new RuntimeException( 'Unable to write ' . $file ); }
};
$write( 'live-pages.json', $pages );
$write( 'posts.json', $posts );
$write( 'live-media.json', $media );
$normalize = static function ( string $html ): string {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags_compat( $html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
};
function wp_strip_all_tags_compat( string $html ): string { return strip_tags( preg_replace( '/<(script|style)[^>]*>.*?<\/\1>/si', '', $html ) ); }
$report = array( 'generated_at' => gmdate( 'c' ), 'pages' => array(), 'posts' => array() );
foreach ( $pages as $item ) { $text = $normalize( $item['live_rendered'] ?: ( $item['content']['rendered'] ?? '' ) ); $report['pages'][ $item['slug'] ] = array( 'url' => $item['link'], 'title' => html_entity_decode( strip_tags( $item['title']['rendered'] ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ), 'words' => str_word_count( $text ), 'sha256' => hash( 'sha256', $text ), 'rendered_html_captured' => ! empty( $item['live_rendered'] ) ); }
foreach ( $posts as $item ) { $text = $normalize( $item['content']['rendered'] ?? '' ); $report['posts'][ $item['slug'] ] = array( 'url' => $item['link'], 'title' => html_entity_decode( strip_tags( $item['title']['rendered'] ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ), 'words' => str_word_count( $text ), 'sha256' => hash( 'sha256', $text ) ); }
$write( 'live-site-verification.json', $report );
echo json_encode( array( 'pages' => count( $pages ), 'posts' => count( $posts ), 'media' => count( $media ), 'report' => 'data/live-site-verification.json' ), JSON_PRETTY_PRINT ) . "\n";
