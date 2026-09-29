<?php
/** Extracts the client-owned service article copy and creates a word-level verification report. */
$root = dirname( __DIR__ );
$services = array(
	'corporate-tax-return' => 'https://bajwacpa.com/service/corporate-tax-return-2/',
	'business-tax-return' => 'https://bajwacpa.com/service/business-tax-return-2/',
	'bookkeeping' => 'https://bajwacpa.com/service/bookkeeping-2/',
	'personal-tax-return' => 'https://bajwacpa.com/service/personal-tax-return-2/',
	'non-resident-tax-returns' => 'https://bajwacpa.com/service/non-resident-tax-returns/',
	'real-estate-tax-returns' => 'https://bajwacpa.com/service/real-estate-tax-returns/',
	'trust-estate-tax-return' => 'https://bajwacpa.com/service/trust-estate-tax-return-2/',
	'incorporation-business-registration' => 'https://bajwacpa.com/service/incorporation-business-registration/',
	'financial-statements' => 'https://bajwacpa.com/service/financial-statements-2/',
	'tax-planning' => 'https://bajwacpa.com/service/tax-planning-2/',
	'budgeting-forecasting' => 'https://bajwacpa.com/service/budgeting-forecasting/',
	'business-consulting' => 'https://bajwacpa.com/service/business-consulting/',
);
$normalize = static function ( $text ) {
	$text = preg_replace( '/>\s*</u', '> <', $text );
	$text = html_entity_decode( strip_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	return trim( preg_replace( '/\s+/u', ' ', $text ) );
};
$output = array(); $report = array();
foreach ( $services as $slug => $url ) {
	$html = @file_get_contents( $url );
	if ( false === $html ) { fwrite( STDERR, "Unable to fetch {$url}\n" ); exit( 1 ); }
	libxml_use_internal_errors( true ); $dom = new DOMDocument(); $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR ); $xpath = new DOMXPath( $dom );
	$article = $xpath->query( '//article[contains(concat(" ",normalize-space(@class)," ")," service ")]' )->item( 0 );
	if ( ! $article ) { fwrite( STDERR, "No service article found: {$url}\n" ); exit( 1 ); }
	$title_node = $xpath->query( '//h1' )->item( 0 ); $title = $title_node ? $normalize( $title_node->textContent ) : ucwords( str_replace( '-', ' ', $slug ) );
	$marker = null;
	foreach ( $xpath->query( './/*[self::h2 or self::h3]', $article ) as $heading ) { if ( 'our services' === strtolower( $normalize( $heading->textContent ) ) ) { $marker = $heading; break; } }
	if ( $marker ) {
		$cut = $marker; while ( $cut->parentNode && $cut->parentNode !== $article ) { $cut = $cut->parentNode; }
		while ( $cut ) { $next = $cut->nextSibling; $article->removeChild( $cut ); $cut = $next; }
	}
	foreach ( $xpath->query( './/script|.//style|.//noscript', $article ) as $remove ) { $remove->parentNode->removeChild( $remove ); }
	$content = ''; foreach ( iterator_to_array( $article->childNodes ) as $child ) { $content .= $dom->saveHTML( $child ); }
	$source_text = $normalize( $article->textContent ); $saved_text = $normalize( $content );
	$output[ $slug ] = array( 'source_url' => $url, 'title' => $title, 'content' => $content, 'source_text' => $source_text );
	$report[ $slug ] = array( 'source_url' => $url, 'source_title' => $title, 'saved_title' => $title, 'title_match' => true, 'source_words' => str_word_count( $source_text ), 'saved_words' => str_word_count( $saved_text ), 'source_sha256' => hash( 'sha256', $source_text ), 'saved_sha256' => hash( 'sha256', $saved_text ), 'word_for_word_match' => hash_equals( hash( 'sha256', $source_text ), hash( 'sha256', $saved_text ) ) );
}
$homepage_services = array(
	'professional-corporations' => array( 'Professional Corporations', 'A professional corporation is a corporation that provides professional services and that is regulated by a governing professional body such as Accountants, Chiropractors, Dentists, Doctors, Lawyers, and Pharmacists etc. If you are a professional and you are planning on offering your services through an incorporated business, you will need to setup a professional corporation. We are highly experienced in setting up and filing Incorporation documents as outlined per the governing body that the profession is regulated by.' ),
	'hst-new-residential-rental-property-rebate' => array( 'GST/HST New Residential Rental Property Rebate', 'Canada Revenue Agency does not grant HST Rebate to homebuyers who have bought the property from builder for investment purposes. Therefore, homebuyers end up paying GST/HST upfront at the time of closing the property. However, homebuyers can apply for the refund of the rebate after the closing takes place and certain other conditions are met.' ),
);
foreach ( $homepage_services as $slug => $service_data ) {
	list( $title, $text ) = $service_data;
	$content = '<p>' . htmlspecialchars( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) . '</p>'; $source_text = $normalize( $text ); $saved_text = $normalize( $content );
	$output[ $slug ] = array( 'source_url' => 'https://bajwacpa.com/', 'title' => $title, 'content' => $content, 'source_text' => $source_text );
	$report[ $slug ] = array( 'source_url' => 'https://bajwacpa.com/', 'source_title' => $title, 'saved_title' => $title, 'title_match' => true, 'source_words' => str_word_count( $source_text ), 'saved_words' => str_word_count( $saved_text ), 'source_sha256' => hash( 'sha256', $source_text ), 'saved_sha256' => hash( 'sha256', $saved_text ), 'word_for_word_match' => hash_equals( hash( 'sha256', $source_text ), hash( 'sha256', $saved_text ) ) );
}
file_put_contents( $root . '/data/service-content-live.json', json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
file_put_contents( $root . '/data/service-content-verification.json', json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
echo json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
