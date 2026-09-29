<?php
/** Verify rendered local service titles and body copy against the saved live-source snapshot. */
$base = $argv[1] ?? 'http://localhost/Bajwacpa/bajwa-premium';
$data = json_decode( file_get_contents( dirname( __DIR__ ) . '/data/service-content-live.json' ), true );
$normalize = static function ( string $html ): string {
	$text = html_entity_decode( strip_tags( $html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $text ) );
};
$failures = 0;
foreach ( $data as $slug => $expected ) {
	$url = rtrim( $base, '/' ) . '/services/' . $slug . '/';
	$html = @file_get_contents( $url );
	preg_match( '/\s(\d{3})\s/', $http_response_header[0] ?? '', $status_match );
	$status = (int) ( $status_match[1] ?? 0 );
	preg_match( '/<h1[^>]*>(.*?)<\/h1>/si', (string) $html, $title_match );
	preg_match( '/<div class="prose">(.*?)<div class="inline-cta">/si', (string) $html, $body_match );
	$title_ok = $normalize( $title_match[1] ?? '' ) === $normalize( $expected['title'] ?? '' );
	$body_ok  = hash_equals( hash( 'sha256', $normalize( $expected['content'] ?? '' ) ), hash( 'sha256', $normalize( $body_match[1] ?? '' ) ) );
	$ok = 200 === $status && $title_ok && $body_ok;
	$failures += $ok ? 0 : 1;
	echo sprintf( "%s | HTTP %d | title %s | body %s\n", $slug, $status, $title_ok ? 'MATCH' : 'FAIL', $body_ok ? 'MATCH' : 'FAIL' );
}
echo sprintf( "Verified: %d | Failures: %d\n", count( $data ) - $failures, $failures );
exit( $failures ? 1 : 0 );
