<?php
$url = $argv[1] ?? 'https://bajwacpa.com/about/';
$needle = $argv[2] ?? 'We want individuals';
$html = file_get_contents( $url );
$dom = new DOMDocument(); libxml_use_internal_errors( true ); $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html );
$xpath = new DOMXPath( $dom );
foreach ( $xpath->query( "//*[contains(normalize-space(string(.)), '" . str_replace( "'", '', $needle ) . "')]" ) as $node ) {
	if ( in_array( strtolower( $node->nodeName ), array( 'div', 'main', 'article', 'section' ), true ) ) {
		echo $node->nodeName . ' id=' . $node->getAttribute( 'id' ) . ' class=' . $node->getAttribute( 'class' ) . ' chars=' . strlen( $dom->saveHTML( $node ) ) . "\n";
	}
}
