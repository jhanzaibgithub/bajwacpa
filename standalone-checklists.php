<?php
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $val ) {
		return htmlspecialchars( (string) $val, ENT_QUOTES, 'UTF-8' );
	}
}


if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $value ) {
		return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	}
}
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $value ) {
		return htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
	}
}
$image = 'assets/images/service-books.jpg';
$contact = isset( $url ) ? $url( 'contact' ) : '#contact';
$checklist_arrow = $arrow ?? '→';
$doc = static function ( $local_file, $remote_url = '' ) {
	$local_path = __DIR__ . '/assets/documents/' . $local_file;
	if ( file_exists( $local_path ) ) {
		return 'assets/documents/' . rawurlencode( $local_file );
	}
	return $remote_url ?: 'assets/documents/' . rawurlencode( $local_file );
};

require __DIR__ . '/inc/checklist-view.php';
